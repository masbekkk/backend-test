<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
// use Symfony\Component\HttpFoundation\Response;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'User Admin',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        $this->actingAs($this->user);
    }
    /**
     * A test user cant retrieve products.
     */
    public function test_user_cant_retrieve_products(): void
    {
        $this->user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'user'
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/api/admin/product');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    /**
     * A test admin can retrieve products.
     */
    public function test_admin_can_retrieve_products(): void
    {
        $response = $this->get('/api/admin/product');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => Product::getJsonStructure()
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Products retrieved Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_fail_create_product(): void
    {
        $response = $this->post('api/admin/product', [
            // 'name' => 'Undangan Nikah',
            // 'price' => 500000
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['errors']);
        $response->assertJson([
            'errors' => [
                'name' => ['The name field is required.'],
                'description' => ['The description field is required.'],
                'price' => ['The price field is required.'],
            ],
        ]);
    }
    public function test_admin_can_create_product(): void
    {
        $response = $this->post('api/admin/product', [
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);
        // $response->dump();
        $this->assertDatabaseHas('products', [
            'id' => $response->json('data')['id'],
            'name' => $response->json('data')['name'],
            'description' => $response->json('data')['description'],
            'price' => $response->json('data')['price'],
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'name',
                'description',
                'price',
                'updated_at',
                'created_at',
                'id',
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product created Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_update_product_not_found(): void
    {
        $response = $this->put('api/admin/product/1', [
            'name' => 'Undangan Nikah',
            'description' => 'ssas',
            'price' => 500000
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);
        $response = $this->put('api/admin/product/' . $product->id, [
            'name' => 'Video Nikah',
            'description' => 'Video Prewedd Nikah',
            'price' => 5000000
        ]);
        // $response->dump();
        $this->assertDatabaseHas('products', [
            'id' => $response->json('data')['id'],
            'name' => $response->json('data')['name'],
            'description' => $response->json('data')['description'],
            'price' => $response->json('data')['price'],
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'price',
                'created_at',
                'updated_at',
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product updated Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_can_delete_product():void
    {
        $product = Product::factory()->create([
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);

        $response = $this->delete('api/admin/product/' . $product->id);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product deleted Successfully!',
            'data' => null,
        ]);
    }
}
