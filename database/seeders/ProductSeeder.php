<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            ['name' => 'Laptop Gaming', 'price' => 15000000, 'stock' => 10, 'image' => 'products/laptop_gaming.jpg', 'url' => 'https://images.unsplash.com/photo-1593642634524-b40b5baae6bb'],
            ['name' => 'Smartphone Pro', 'price' => 8000000, 'stock' => 25, 'image' => 'products/smartphone_pro.jpg', 'url' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb'],
            ['name' => 'Tablet Premium', 'price' => 6000000, 'stock' => 15, 'image' => 'products/tablet_premium.jpg', 'url' => 'https://images.unsplash.com/photo-1546054454-aa26e2b734c7'],
            ['name' => 'Smart TV 55"', 'price' => 9000000, 'stock' => 8, 'image' => 'products/smart_tv.jpg', 'url' => 'https://images.unsplash.com/photo-1571415060716-baff5f717c37'],
            
            // Audio
            ['name' => 'Headphone Wireless', 'price' => 1200000, 'stock' => 30, 'image' => 'products/headphone_wireless.jpg', 'url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e'],
            ['name' => 'Earphone TWS', 'price' => 400000, 'stock' => 45, 'image' => 'products/earphone_tws.jpg', 'url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df'],
            ['name' => 'Speaker Bluetooth', 'price' => 600000, 'stock' => 35, 'image' => 'products/speaker_bluetooth.jpg', 'url' => 'https://images.unsplash.com/photo-1558379850-823f103f866a'],
            ['name' => 'Soundbar Home Theater', 'price' => 2500000, 'stock' => 12, 'image' => 'products/soundbar.jpg', 'url' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d'],
            
            // Wearables
            ['name' => 'Smartwatch', 'price' => 2500000, 'stock' => 15, 'image' => 'products/smartwatch.jpg', 'url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30'],
            ['name' => 'Fitness Tracker', 'price' => 800000, 'stock' => 20, 'image' => 'products/fitness_tracker.jpg', 'url' => 'https://images.unsplash.com/photo-1576243345690-4e4b79b63288'],
            ['name' => 'VR Headset', 'price' => 5000000, 'stock' => 5, 'image' => 'products/vr_headset.jpg', 'url' => 'https://images.unsplash.com/photo-1593508512255-86ab42a8e620'],
            
            // Computer Accessories
            ['name' => 'Mouse Ergonomis', 'price' => 300000, 'stock' => 50, 'image' => 'products/mouse_ergonomis.jpg', 'url' => 'https://images.unsplash.com/photo-1527814050087-3793815479db'],
            ['name' => 'Keyboard Mechanical', 'price' => 900000, 'stock' => 20, 'image' => 'products/keyboard_mechanical.jpg', 'url' => 'https://images.unsplash.com/photo-1583445013765-46c20a11c5cd'],
            ['name' => 'Monitor 4K', 'price' => 4500000, 'stock' => 8, 'image' => 'products/monitor_4k.jpg', 'url' => 'https://images.unsplash.com/photo-1546538915-a9e2c8d0a8e7'],
            ['name' => 'Webcam HD', 'price' => 500000, 'stock' => 30, 'image' => 'products/webcam_hd.jpg', 'url' => 'https://images.unsplash.com/photo-1592155931584-901ac15763e3'],
            ['name' => 'Printer Multifungsi', 'price' => 2000000, 'stock' => 10, 'image' => 'products/printer_multifungsi.jpg', 'url' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3'],
            
            // Camera Equipment
            ['name' => 'Kamera Mirrorless', 'price' => 12000000, 'stock' => 5, 'image' => 'products/kamera_mirrorless.jpg', 'url' => 'https://images.unsplash.com/photo-1512790182412-b19e6d62bc39'],
            ['name' => 'Tripod Aluminium', 'price' => 400000, 'stock' => 25, 'image' => 'products/tripod_aluminium.jpg', 'url' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32'],
            ['name' => 'Drone 4K', 'price' => 8000000, 'stock' => 7, 'image' => 'products/drone_4k.jpg', 'url' => 'https://images.unsplash.com/photo-1579829366248-204fe8413f31'],
            
            // Storage & Networking
            ['name' => 'SSD 512GB', 'price' => 1000000, 'stock' => 20, 'image' => 'products/ssd_512gb.jpg', 'url' => 'https://images.unsplash.com/photo-1587202372775-e229f1725a0b'],
            ['name' => 'Flash Drive 64GB', 'price' => 150000, 'stock' => 100, 'image' => 'products/flash_drive.jpg', 'url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704'],
            ['name' => 'Router WiFi', 'price' => 700000, 'stock' => 15, 'image' => 'products/router_wifi.jpg', 'url' => 'https://images.unsplash.com/photo-1604442351281-a3e8a8dbcb1e'],
            
            // Others
            ['name' => 'Power Bank 10000mAh', 'price' => 200000, 'stock' => 60, 'image' => 'products/power_bank.jpg', 'url' => 'https://images.unsplash.com/photo-1583864697784-a0efc8379f70'],
            ['name' => 'Charger Fast', 'price' => 150000, 'stock' => 80, 'image' => 'products/charger_fast.jpg', 'url' => 'https://images.unsplash.com/photo-1605722243979-fe0be8158232'],
            ['name' => 'Cooling Pad Laptop', 'price' => 350000, 'stock' => 25, 'image' => 'products/cooling_pad.jpg', 'url' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ed26b6'],
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
            // Log error if needed
            logger()->error("Failed to download image: {$url}", ['error' => $e->getMessage()]);
        }
        
        return null;
    }
}