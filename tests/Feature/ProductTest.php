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

        $response->assertStatus(401);
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
}
