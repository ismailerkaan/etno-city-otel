<?php

namespace Tests\Unit\Models;

use App\Models\RoomImage;
use Tests\TestCase;

class RoomImageTest extends TestCase
{
    public function test_public_image_path_returns_public_asset_url(): void
    {
        $roomImage = new RoomImage(['image_url' => '/images/rooms/economy-room.jpg']);

        $this->assertSame(asset('images/rooms/economy-room.jpg'), $roomImage->imageUrl());
    }

    public function test_storage_image_path_keeps_storage_asset_url(): void
    {
        $roomImage = new RoomImage(['image_url' => 'rooms/uploaded-room.jpg']);

        $this->assertSame(asset('storage/rooms/uploaded-room.jpg'), $roomImage->imageUrl());
    }
}
