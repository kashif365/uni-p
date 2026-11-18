<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([SettingSeeder::class, ColorSettingsSeeder::class, EventSeeder::class]);

        $offers = [
            ['Books & Stationery', 'books-stationery', 'Campus Book Nook', 'campus-book-nook', '15% off notebooks', 'DEMOBOOK15'],
            ['Technology', 'technology', 'Study Byte', 'study-byte', '20% off laptop sleeves', 'DEMOBYTE20'],
            ['Food & Drink', 'food-drink', 'Quad Cafe', 'quad-cafe', '10% off a study snack', 'DEMOQUAD10'],
            ['Travel', 'travel', 'Semester Shuttle', 'semester-shuttle', '25% off a day pass', 'DEMORIDE25'],
            ['Sports & Fitness', 'sports-fitness', 'Campus Active', 'campus-active', '15% off training gear', 'DEMOMOVE15'],
            ['Arts & Crafts', 'arts-crafts', 'Studio Corner', 'studio-corner', '20% off art supplies', 'DEMOART20'],
        ];

        foreach ($offers as $index => [$categoryName, $categorySlug, $storeName, $storeSlug, $title, $code]) {
            $category = Category::firstOrCreate(['seo_url' => $categorySlug], [
                'category_name' => $categoryName,
                'status' => true,
                'featured' => true,
                'show_home' => true,
                'recommended' => true,
                'student_discount' => true,
                'sort_order' => $index,
            ]);

            $store = Store::firstOrCreate(['seo_url' => $storeSlug], [
                'store_name' => $storeName,
                'detail_description' => 'Fictional store for the Campus Deals university demo.',
                'featured' => true,
                'show_trending' => true,
                'student_discount' => true,
                'status' => true,
                'sort_order' => $index,
            ]);

            $store->categories()->syncWithoutDetaching([$category->id]);

            Coupon::firstOrCreate(['coupon_code' => $code, 'brand_store' => $store->store_name], [
                'coupon_title' => $title,
                'category_id' => $category->id,
                'description' => 'Sample offer for demonstrating coupon search and redemption.',
                'terms' => 'Demonstration only. This code cannot be redeemed at a real store.',
                'hot_deals' => true,
                'featured' => true,
                'student_offer' => true,
                'status' => true,
                'sort_order' => $index,
            ]);
        }
    }
}
