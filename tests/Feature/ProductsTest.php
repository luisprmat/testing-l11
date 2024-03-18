<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('homepage contains empty table', function () {
    actingAs($this->user)
        ->get('products')
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});

test('homepage contains non empty table', function () {
    $product = Product::create([
        'name' => 'Product 1',
        'price' => 123,
    ]);

    actingAs($this->user)
        ->get('products')
        ->assertStatus(200)
        ->assertDontSee(__('No products found'))
        ->assertSee('Product 1')
        ->assertViewHas('products', fn (LengthAwarePaginator $collection) => $collection->contains($product));
});

test('paginated products table doesn\'t contain 11th record', function () {
    $products = Product::factory(11)->create();
    $lastProduct = $products->last();

    actingAs($this->user)
        ->get('products')
        ->assertStatus(200)
        ->assertViewHas('products', fn (LengthAwarePaginator $collection) => $collection->doesntContain($lastProduct));
});

test('admin can see products create button', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    actingAs($admin)
        ->get('products')
        ->assertStatus(200)
        ->assertSee(__('Add new product'));
});

test('non admin cannot see products create button', function () {
    actingAs($this->user)
        ->get('products')
        ->assertStatus(200)
        ->assertDontSee(__('Add new product'));
});

test('admin can access product create page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    actingAs($admin)
        ->get('/products/create')
        ->assertStatus(200);
});

test('non admin cannot access product create page', function () {
    actingAs($this->user)
        ->get('/products/create')
        ->assertStatus(403);
});
