<?php

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\get;

test('homepage contains empty table', function () {
    get('products')
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});

test('homepage contains non empty table', function () {
    $product = Product::create([
        'name' => 'Product 1',
        'price' => 123,
    ]);

    get('/products')
        ->assertStatus(200)
        ->assertDontSee(__('No products found'))
        ->assertSee('Product 1')
        ->assertViewHas('products', fn (LengthAwarePaginator $collection) => $collection->contains($product));
});

test('paginated products table doesn\'t contain 11th record', function () {
    for ($i = 1; $i <= 11; $i++) {
        $product = Product::create([
            'name' => 'Product '.$i,
            'price' => rand(100, 999),
        ]);
    }

    get('/products')
        ->assertStatus(200)
        ->assertViewHas('products', fn (LengthAwarePaginator $collection) => $collection->doesntContain($product));
});
