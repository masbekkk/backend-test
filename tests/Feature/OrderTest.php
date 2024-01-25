<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'role' => 'user'
        ]);

        $this->actingAs($this->user);
    }
    /**
     * A basic feature test example.
     */
    public function test_user_can_create_order(): void
    {
        $product = Product::factory(2)->create([
            'name' => 'Undangan Nikah',
            'description' => 'Web Undangan Nikah',
            'price' => 500000
        ]);
        
        $product1 = $product->first();
        $product2 = $product->last();
        $response = $this->post('/api/customer/process-order', [
            'customer_user_id' => $this->user->id,
            'items' => array(
                array(
                    'product_id' => $product1->id,
                    'quantity' => 3,
                    'unit_price' => 500.000
                ),
                array(
                    'product_id' => $product2->id,
                    'quantity' => 2,
                    'unit_price' => 700.000
                )
            )
        ]);
        // dd($response);
        $response->assertStatus(Response::HTTP_CREATED);
    }
}
