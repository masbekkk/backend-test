<?php

namespace Tests\Feature;

use App\Jobs\ProcessOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;
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
     * A basic create order test
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
        // dd($response->json('data'));
        $this->assertDatabaseHas('orders', [
            'id' => $response->json('data')['id'],
            'customer_user_id' => $response->json('data')['customer_user_id'],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'customer_user_id',
                'updated_at',
                'created_at',
                'id',
            ]
        ]);
        // dd($response);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Order created successfully!',
            'data' => $response->json('data'),
        ]);
    }
    public function test_user_can_create_order_and_dispatchIt(): void
    {
        Queue::fake();

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
        // dd($response->json('data'));
        $this->assertDatabaseHas('orders', [
            'id' => $response->json('data')['id'],
            'customer_user_id' => $response->json('data')['customer_user_id'],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'customer_user_id',
                'updated_at',
                'created_at',
                'id',
            ]
        ]);
        // dd($response);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Order created successfully!',
            'data' => $response->json('data'),
        ]);

        Queue::assertPushed(ProcessOrder::class, function ($job) use ($response) {
            return $job->order->customer_user_id === $this->user->id;
        });
    }
}
