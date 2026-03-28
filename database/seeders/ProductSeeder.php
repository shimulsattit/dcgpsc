<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'type' => 'course',
            'title' => 'Advanced Mathematics for HSC',
            'description' => '<p>Master the complexities of Advanced Mathematics with our comprehensive HSC course. Including video lectures, PDFs, and practice sets.</p>',
            'price' => 2500,
            'discount_price' => 1999,
            'image_url' => 'https://images.unsplash.com/photo-1509228468518-180dd48219d1?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_active' => true,
            'order' => 1,
        ]);

        Product::create([
            'type' => 'book',
            'title' => 'English Grammar Masterclass',
            'description' => '<p>A complete guide to English grammar for all levels. Perfectly designed for competitive exams.</p>',
            'price' => 450,
            'discount_price' => 380,
            'image_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_active' => true,
            'order' => 2,
        ]);
        
        Product::create([
            'type' => 'course',
            'title' => 'Physics: Mechanics Deep Dive',
            'description' => '<p>Understanding the fundamental laws of motion and mechanics. 20+ hours of content.</p>',
            'price' => 1500,
            'discount_price' => null,
            'is_active' => true,
            'order' => 3,
        ]);
    }
}
