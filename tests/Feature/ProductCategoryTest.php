<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
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
     * A test user cant retrieve Category.
     */
    public function test_user_cant_retrieve_product_category(): void
    {
        $this->user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'user'
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/api/admin/product-category');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    /**
     * A test admin can retrieve Category.
     */
    public function test_admin_can_retrieve_product_category(): void
    {
        $response = $this->get('/api/admin/product-category');
        // $response->dump();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => ['product_id', 'category_id']
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product Categories retrieved Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_fail_create_product_category(): void
    {
        $response = $this->post('api/admin/product-category', [
            
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['errors']);
        $response->assertJson([
            'errors' => [
                'product_id' => ['The product id field is required.'],
                'category_id' => ['The category id field is required.'],
            ],
        ]);
    }
    public function test_admin_can_create_product_category(): void
    {
        $product = Product::factory()->create([
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);
        $category = Category::factory()->create([
            'name' => 'Video',
        ]);
        $response = $this->post('api/admin/product-category', [
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);
        // $response->dump();
        $this->assertDatabaseHas('product_categories', [
            'id' => $response->json('data')['id'],
            'product_id' => $response->json('data')['product_id'],
            'category_id' => $response->json('data')['category_id'],
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'product_id',
                'category_id',
                'updated_at',
                'created_at',
                'id',
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product Category created Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_update_category_not_found(): void
    {
        $response = $this->put('api/admin/product-category/1', [
            'product_id' => 4,
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_admin_can_update_product_category(): void
    {
        $product = Product::factory()->create([
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);
        $category = Category::factory()->create([
            'name' => 'Video',
        ]);
        $productCategory = ProductCategories::factory()->create([
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);
        $response = $this->put('api/admin/product-category/' . $productCategory->id, [
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);
        // $response->dump();
        $this->assertDatabaseHas('product_categories', [
            'id' => $response->json('data')['id'],
            'product_id' => $response->json('data')['product_id'],
            'category_id' => $response->json('data')['category_id'],
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'product_id',
                'category_id',
                'created_at',
                'updated_at',
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product Category updated Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_can_delete_product_category():void
    {
        $product = Product::factory()->create([
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);
        $category = Category::factory()->create([
            'name' => 'Video',
        ]);

        $productCategory = ProductCategories::factory()->create([
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);

        $response = $this->delete('api/admin/product-category/' . $productCategory->id);
        $this->assertDatabaseMissing('product_categories', [
            'id' => $productCategory->id,
            'product_id' => $productCategory->product_id,
            'category_id' => $productCategory->category_id,
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Product Category deleted Successfully!',
            'data' => null,
        ]);
    }
}
