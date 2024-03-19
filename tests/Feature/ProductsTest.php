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
    asAdmin()
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
    asAdmin()
        ->get('/products/create')
        ->assertStatus(200);
});

test('non admin cannot access product create page', function () {
    actingAs($this->user)
        ->get('/products/create')
        ->assertStatus(403);
});

test('create product successful', function (string $name, int $price) {
    asAdmin()
        ->post('products', compact('name', 'price'))
        ->assertStatus(302)
        ->assertRedirect('products');

    // Checks whether the record exists in a certain DB table
    $this->assertDatabaseHas('products', compact('name', 'price'));

    $lastProduct = Product::latest()->first();
    expect($name)->toBe($lastProduct->name)
        ->and($price)->toBe($lastProduct->price);
})->with([
    ['Product 1', 123],
    ['Product 2', 456],
]);

test('product edit contains correct values', function () {
    $product = Product::factory()->create();

    asAdmin()->get('products/'.$product->id.'/edit')
        ->assertStatus(200)
        ->assertSee('value="'.$product->name.'"', false)
        ->assertSee('value="'.$product->price.'"', false)
        ->assertViewHas('product', $product);
});

test('product update validation error redirects back to form', function () {
    $product = Product::factory()->create();

    asAdmin()->put('products/'.$product->id, [
        'name' => '',
        'price' => '',
    ])
        ->assertStatus(302)
        ->assertInvalid(['name', 'price'])
        ->assertSessionHasErrors(['name', 'price']);
});

test('product delete successful', function () {
    $product = Product::factory()->create();

    asAdmin()
        ->delete('products/'.$product->id)
        ->assertStatus(302)
        ->assertRedirect('products');

    $this->assertDatabaseMissing('products', $product->toArray());
    $this->assertDatabaseCount('products', 0);

    // Alternative to above assertions ...
    $this->assertModelMissing($product);
    $this->assertDatabaseEmpty('products');
});
