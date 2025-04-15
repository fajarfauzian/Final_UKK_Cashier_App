<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
        // (opsional)
    public function run(): void
    {
        $products = [
            ['name' => 'MacBook Pro', 'price' => 15000000, 'stock' => 10, 'image' => 'products/laptop.jpg', 'url' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9'],
            ['name' => 'iPad Air', 'price' => 8000000, 'stock' => 12, 'image' => 'products/tablet.jpg', 'url' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0'],
            ['name' => 'Samsung Smart TV', 'price' => 10000000, 'stock' => 8, 'image' => 'products/smart_tv.jpg', 'url' => 'https://images.unsplash.com/photo-1593784991095-a205069470b6'],
            ['name' => 'Sony Headphones', 'price' => 2500000, 'stock' => 20, 'image' => 'products/headphones.jpg', 'url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df'],
            ['name' => 'AirPods Pro', 'price' => 3500000, 'stock' => 25, 'image' => 'products/earbuds.jpg', 'url' => 'https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7'],
            ['name' => 'JBL Speaker', 'price' => 1500000, 'stock' => 15, 'image' => 'products/speaker.jpg', 'url' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb'],
            ['name' => 'Apple Watch', 'price' => 5000000, 'stock' => 10, 'image' => 'products/smartwatch.jpg', 'url' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12'],
            ['name' => 'Fitbit Tracker', 'price' => 1200000, 'stock' => 18, 'image' => 'products/fitness_tracker.jpg', 'url' => 'https://images.unsplash.com/photo-1576243345690-4e4b79b63288'],
            ['name' => 'Logitech Mouse', 'price' => 500000, 'stock' => 30, 'image' => 'products/mouse.jpg', 'url' => 'https://images.unsplash.com/photo-1527814050087-3793815479db'],
            ['name' => 'Mechanical Keyboard', 'price' => 1200000, 'stock' => 15, 'image' => 'products/keyboard.jpg', 'url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3'],
            ['name' => 'Canon DSLR', 'price' => 10000000, 'stock' => 6, 'image' => 'products/camera.jpg', 'url' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32'],
            ['name' => 'DJI Drone', 'price' => 12000000, 'stock' => 4, 'image' => 'products/drone.jpg', 'url' => 'https://images.unsplash.com/photo-1579829366248-204fe8413f31'],
        ];

        foreach ($products as $product) {
            $imagePath = $this->downloadImage($product['url'], $product['image']);

            Product::create([
                'name' => $product['name'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => $imagePath,
            ]);
        }
    }

    protected function downloadImage(string $url, string $path): ?string
    {
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            logger()->error("Failed to download image: {$url}", ['error' => $e->getMessage()]);
        }

        return null;
    }
}
