<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\WhatsappContact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the storefront with the catalogue from njikokafashions.com.
     */
    public function run(): void
    {
        $categories = collect(['Men', 'Women'])
            ->mapWithKeys(fn ($name) => [$name => Category::create(['name' => $name, 'slug' => Str::slug($name)])]);

        $products = [
            ['Dress 1', 'dress-1', 'Women', 2000, 'images/products/Patrick-U-Patrick_R1-08-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 2', 'dress-2', 'Women', 2000, 'images/products/Patrick-U-Patrick_R1-09-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 3', 'dress-3', 'Women', 2000, 'images/products/Patrick-U-Patrick_R1-04-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 4', 'dress-4', 'Women', 30000, 'images/products/Patrick-U-Patrick_R1-05-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 10', 'dress-5', 'Women', 30000, 'images/products/Patrick-U-Patrick_R1-13-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 6', 'dress-6', 'Women', 30000, 'images/products/Patrick-U-Patrick_R1-07-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 7', 'dress-7', 'Women', 30000, 'images/products/Patrick-U-Patrick_R1-01-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 8', 'dress-8', 'Women', 28500, 'images/products/Patrick-U-Patrick_R1-02-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 9', 'dress-9', 'Women', 28500, 'images/products/Patrick-U-Patrick_R1-03-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 11', 'dress-11', 'Women', 28500, 'images/products/Patrick-U-Patrick_R1-14-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 12', 'dress-12', 'Women', 28500, 'images/products/Patrick-U-Patrick_R1-15-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 13', 'dress-13', 'Women', 27000, 'images/products/Patrick-U-Patrick_R1-10-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 14', 'dress-14', 'Women', 27000, 'images/products/Patrick-U-Patrick_R1-11-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 15', 'dress-15', 'Women', 27000, 'images/products/Patrick-U-Patrick_R1-12-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress 5', 'dress-5-2', 'Women', 27000, 'images/products/Patrick-U-Patrick_R1-06-scaled.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Integer vitae justo eget magna fermentum iaculis. Vivamus suscipit tortor eget felis porttitor volutpat.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel neque at arcu tincidunt tincidunt.</p>'],
            ['Dress', 'dress', 'Men', 26000, 'images/products/Patrick-u-Patrick-02-_2000-2000-01-scaled.png', '', ''],
            ['Dress', 'dress-10', 'Men', 26000, 'images/products/Patrick-u-Patrick-02-_2000-2000-02-scaled.png', '', ''],
            ['Dress', 'dress-16', 'Men', 26000, 'images/products/Patrick-u-Patrick-02-_2000-2000-03-scaled.png', '', ''],
            ['Dress', 'dress-17', 'Men', 26000, 'images/products/Patrick-u-Patrick-02-_2000-2000-04-scaled.png', '', ''],
            ['Dress', 'dress-18', 'Men', 25000, 'images/products/Patrick-u-Patrick-02-_2000-2000-05-scaled.png', '', ''],
            ['Dress', 'dress-19', 'Men', 25000, 'images/products/Patrick-u-Patrick-02-_2000-2000-06-scaled.png', '', ''],
            ['Dress', 'dress-20', 'Men', 25000, 'images/products/Patrick-u-Patrick-02-_2000-2000-07-scaled.png', '', ''],
            ['Dress', 'dress-21', 'Men', 25000, 'images/products/Patrick-u-Patrick-02-_2000-2000-08-scaled.png', '', ''],
        ];

        foreach ($products as [$name, $slug, $category, $price, $image, $summary, $description]) {
            Product::create([
                'category_id' => $categories[$category]->id,
                'name' => $name,
                'slug' => $slug,
                'summary' => $summary,
                'description' => $description ?: null,
                'price_cents' => $price,
                'image' => $image,
                'in_stock' => true,
            ]);
        }

        foreach ([['Ify', '2348135438144'], ['Paschal', '2347031102089']] as $position => [$label, $number]) {
            WhatsappContact::firstOrCreate(['number' => $number], ['label' => $label, 'position' => $position]);
        }
    }
}
