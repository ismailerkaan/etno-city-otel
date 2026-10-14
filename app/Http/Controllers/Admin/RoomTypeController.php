<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomTypeRequest;
use App\Http\Requests\Admin\UpdateRoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoomTypeController extends Controller
{
    public function index(): View
    {
        return view('admin.rooms.index', [
            'roomTypes' => RoomType::query()->with('images')->withCount('reservations')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.rooms.create', ['roomType' => new RoomType]);
    }

    public function store(StoreRoomTypeRequest $request): RedirectResponse
    {
        $roomType = DB::transaction(function () use ($request): RoomType {
            $roomType = RoomType::query()->create($this->roomAttributes($request->validated()));
            $this->syncImages($roomType, $request->validated('images', []));

            return $roomType;
        });

        return redirect()->route('admin.rooms.edit', $roomType)->with('success', 'Oda tipi oluşturuldu.');
    }

    public function show(RoomType $roomType): RedirectResponse
    {
        return redirect()->route('admin.rooms.edit', $roomType);
    }

    public function edit(RoomType $roomType): View
    {
        return view('admin.rooms.edit', ['roomType' => $roomType->load('images')]);
    }

    public function update(UpdateRoomTypeRequest $request, RoomType $roomType): RedirectResponse
    {
        DB::transaction(function () use ($request, $roomType): void {
            $roomType->update($this->roomAttributes($request->validated()));
            $this->syncImages($roomType, $request->validated('images', []));
        });

        return back()->with('success', 'Oda tipi ve görselleri güncellendi.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        if ($roomType->reservations()->exists()) {
            return back()->withErrors(['room' => 'Rezervasyonu bulunan oda tipi silinemez; pasife alabilirsiniz.']);
        }

        $roomType->images()->each(fn ($image) => $this->deleteStoredImage($image->image_url));
        $roomType->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Oda tipi silindi.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function roomAttributes(array $validated): array
    {
        return [
            'slug' => $validated['slug'],
            'name' => $validated['name'],
            'name_en' => $validated['name_en'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'nightly_price' => $validated['nightly_price'],
            'currency' => 'TRY',
            'max_adults' => $validated['max_adults'],
            'max_children' => $validated['max_children'],
            'size_sqm' => $validated['size_sqm'] ?? null,
            'bed_type' => $validated['bed_type'] ?? null,
            'bed_type_en' => $validated['bed_type_en'] ?? null,
            'features' => $this->lines($validated['features_text'] ?? null),
            'features_en' => $this->lines($validated['features_en_text'] ?? null),
            'sort_order' => $validated['sort_order'],
            'is_active' => $validated['is_active'] ?? false,
            'show_on_home' => $validated['show_on_home'] ?? false,
        ];
    }

    /** @return array<int, string> */
    private function lines(?string $value): array
    {
        return collect(preg_split('/\R/u', $value ?? ''))
            ->map(fn (string $line): string => Str::of($line)->trim()->toString())
            ->filter()
            ->values()
            ->all();
    }

    /** @param array<int, array<string, mixed>> $images */
    private function syncImages(RoomType $roomType, array $images): void
    {
        $keptIds = [];
        $primaryWasAssigned = false;

        foreach ($images as $sortOrder => $imageData) {
            $image = isset($imageData['id']) ? $roomType->images()->whereKey($imageData['id'])->first() : null;

            if ($image && ! empty($imageData['remove'])) {
                $this->deleteStoredImage($image->image_url);
                $image->delete();

                continue;
            }

            $uploadedFile = $imageData['file'] ?? null;

            if (! $image && ! $uploadedFile instanceof UploadedFile) {
                continue;
            }

            if ($uploadedFile instanceof UploadedFile) {
                $previousImageUrl = $image?->image_url;
                $imageData['image_url'] = $uploadedFile->store('rooms/'.$roomType->id, 'public');
                $this->deleteStoredImage($previousImageUrl);
            }

            $image ??= $roomType->images()->make();
            $image->fill([
                'image_url' => $imageData['image_url'] ?? $image->image_url,
                'alt_text' => $imageData['alt_text'] ?? null,
                'alt_text_en' => $imageData['alt_text_en'] ?? null,
                'sort_order' => $sortOrder + 1,
                'is_primary' => ! $primaryWasAssigned && ! empty($imageData['is_primary']),
            ])->save();

            $primaryWasAssigned = $primaryWasAssigned || $image->is_primary;
            $keptIds[] = $image->id;
        }

        $removedImages = $roomType->images()->when($keptIds, fn ($query) => $query->whereNotIn('id', $keptIds))->get();
        $removedImages->each(fn ($image) => $this->deleteStoredImage($image->image_url));
        $roomType->images()->whereKey($removedImages->modelKeys())->delete();

        if (! $primaryWasAssigned && $keptIds !== []) {
            $roomType->images()->whereKey($keptIds[0])->update(['is_primary' => true]);
        }
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if ($imageUrl && ! str_starts_with($imageUrl, 'http://') && ! str_starts_with($imageUrl, 'https://')) {
            Storage::disk('public')->delete($imageUrl);
        }
    }
}
