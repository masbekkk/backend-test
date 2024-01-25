<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryTest extends TestCase
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
    public function test_user_cant_retrieve_category(): void
    {
        $this->user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'user'
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/api/admin/category');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    /**
     * A test admin can retrieve Category.
     */
    public function test_admin_can_retrieve_category(): void
    {
        $response = $this->get('/api/admin/category');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => ['name']
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Categories retrieved Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_fail_create_category(): void
    {
        $response = $this->post('api/admin/category', [
          
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['errors']);
        $response->assertJson([
            'errors' => [
                'name' => ['The name field is required.'],
            ],
        ]);
    }
    public function test_admin_can_create_category(): void
    {
        $response = $this->post('api/admin/category', [
            'name' => 'Undangan',
        ]);
        // $response->dump();
        $this->assertDatabaseHas('categories', [
            'id' => $response->json('data')['id'],
            'name' => $response->json('data')['name'],
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'name',
                'updated_at',
                'created_at',
                'id',
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Category created Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_update_category_not_found(): void
    {
        $response = $this->put('api/admin/category/1', [
            'name' => 'Video',
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Video',
        ]);
        $response = $this->put('api/admin/category/' . $category->id, [
            'name' => 'Undangan',
        ]);
        // $response->dump();
        $this->assertDatabaseHas('categories', [
            'id' => $response->json('data')['id'],
            'name' => $response->json('data')['name'],
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
            ]
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Category updated Successfully!',
            'data' => $response->json('data'),
        ]);
    }

    public function test_admin_can_delete_category():void
    {
        $Category = Category::factory()->create([
            'name' => 'Video',
        ]);

        $response = $this->delete('api/admin/category/' . $Category->id);
        $this->assertDatabaseMissing('categories', [
            'id' => $Category->id,
            'name' => $Category->name,
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Category deleted Successfully!',
            'data' => null,
        ]);
    }
}
