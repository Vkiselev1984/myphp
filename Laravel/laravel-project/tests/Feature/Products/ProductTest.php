<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_indexed(): void
    {
        Product::factory()->count(3)->create();

        $resp = $this->getJson('/api/products');
        $resp->assertOk();
        $resp->assertJsonStructure(['data']);
        $this->assertGreaterThanOrEqual(3, $resp->json('total') ?? 0);
    }

    public function test_product_can_be_shown(): void
    {
        $product = Product::factory()->create();
        $resp = $this->getJson('/api/products/' . $product->id);
        $resp->assertOk()->assertJsonFragment(['id' => $product->id]);
    }

    public function test_product_can_be_stored(): void
    {
        $payload = [
            'sku' => 'SKU-1234-AB',
            'name' => 'New Product',
            'price' => 123.456,
        ];
        $resp = $this->postJson('/api/products', $payload);
        $resp->assertCreated()->assertJsonFragment(['sku' => 'SKU-1234-AB']);
        $this->assertDatabaseHas('products', ['sku' => 'SKU-1234-AB']);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::factory()->create([
            'sku' => 'SKU-1111-AA',
            'name' => 'Old Name',
            'price' => 10.000,
        ]);

        $resp = $this->putJson('/api/products/' . $product->id, [
            'name' => 'Updated Name',
            'price' => 99.990,
        ]);

        $resp->assertOk()->assertJsonFragment(['name' => 'Updated Name']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_product_can_be_destroyed(): void
    {
        $product = Product::factory()->create();

        $resp = $this->deleteJson('/api/products/' . $product->id);
        $resp->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
