<?php

namespace App\Services\Hms;

use Carbon\CarbonImmutable;

/**
 * A sellable price option for one HMS room type: board type (rate plan) + refundable / non-refundable price.
 */
final readonly class HmsOffer
{
    /**
     * @param  array<int, array{date: string, price: string}>  $dailyRates
     */
    public function __construct(
        public int $ratePlanId,
        public string $ratePlanName,
        public string $priceKey,
        public bool $nonRefundable,
        public string $total,
        public string $currency,
        public array $dailyRates,
    ) {}

    /**
     * Build offers from one item of the HMS room list response.
     *
     * @param  array<string, mixed>  $roomItem
     * @return array<int, self>
     */
    public static function fromRoomItem(array $roomItem): array
    {
        if ((int) ($roomItem['roomCount'] ?? 0) < 1) {
            return [];
        }

        $offers = [];

        foreach ($roomItem['accommodationTypes'] ?? [] as $ratePlan) {
            if (! is_array($ratePlan['prices'] ?? null)) {
                continue;
            }

            foreach ($ratePlan['prices'] as $priceKey => $price) {
                if (! is_numeric($price['price'] ?? null)) {
                    continue;
                }

                $offers[] = new self(
                    ratePlanId: (int) $ratePlan['id'],
                    ratePlanName: (string) $ratePlan['title'],
                    priceKey: (string) $priceKey,
                    nonRefundable: ($price['nonRefundable'] ?? '') !== '',
                    total: number_format((float) $price['price'], 2, '.', ''),
                    currency: (string) ($price['currency'] ?? $roomItem['currency'] ?? 'TRY'),
                    dailyRates: collect($price['prices'] ?? [])
                        ->map(fn (array $day): array => [
                            'date' => CarbonImmutable::createFromFormat('d.m.Y', $day['tarih'])->toDateString(),
                            'price' => number_format((float) $day['price'], 2, '.', ''),
                        ])
                        ->values()
                        ->all(),
                );
            }
        }

        // Refundable before non-refundable, cheaper first within each board type
        usort($offers, fn (self $a, self $b): int => [$a->ratePlanName, $a->nonRefundable, (float) $a->total]
            <=> [$b->ratePlanName, $b->nonRefundable, (float) $b->total]);

        return $offers;
    }

    public function matches(int $ratePlanId, string $priceKey): bool
    {
        return $this->ratePlanId === $ratePlanId && $this->priceKey === $priceKey;
    }
}
