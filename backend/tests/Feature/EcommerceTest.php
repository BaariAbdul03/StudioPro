<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_checkout_session_draft_order(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Store Project',
            'slug' => 'store-project',
        ]);

        $product1 = Product::create([
            'project_id' => $project->id,
            'title' => 'Minimalist Lamp',
            'slug' => 'minimalist-lamp',
            'price' => 100.00,
            'inventory_quantity' => 10,
        ]);

        $product2 = Product::create([
            'project_id' => $project->id,
            'title' => 'Keyboard',
            'slug' => 'keyboard',
            'price' => 150.00,
            'inventory_quantity' => 5,
        ]);

        $response = $this->postJson('/api/checkout/session', [
            'project_id' => $project->id,
            'customer_email' => 'customer@example.com',
            'items' => [
                ['product_id' => $product1->id, 'quantity' => 2],
                ['product_id' => $product2->id, 'quantity' => 1],
            ],
        ]);

        $response->assertOk()
            ->assertJsonStructure(['id', 'url']);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals(350.00, $order->total_amount); // 200 + 150
        $this->assertCount(2, $order->items);
    }

    public function test_can_load_payment_simulator_view(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Store Project',
            'slug' => 'store-project',
        ]);

        $order = Order::create([
            'project_id' => $project->id,
            'stripe_session_id' => 'cs_test_123',
            'customer_email' => 'client@example.com',
            'total_amount' => 100.00,
        ]);

        $response = $this->get('/checkout/simulate?session_id=cs_test_123');

        $response->assertOk()
            ->assertViewIs('checkout.simulate')
            ->assertSee('client@example.com')
            ->assertSee('cs_test_123');
    }

    public function test_payment_success_simulation_updates_inventory_and_status(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Store Project',
            'slug' => 'store-project',
        ]);

        $product = Product::create([
            'project_id' => $project->id,
            'title' => 'Minimalist Lamp',
            'slug' => 'minimalist-lamp',
            'price' => 100.00,
            'inventory_quantity' => 6,
            'inventory_status' => 'in_stock',
        ]);

        $order = Order::create([
            'project_id' => $project->id,
            'stripe_session_id' => 'cs_test_123',
            'customer_email' => 'client@example.com',
            'total_amount' => 100.00,
            'payment_status' => 'pending',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'price' => 100.00,
            'quantity' => 2,
        ]);

        $response = $this->post('/checkout/simulate/pay', [
            'session_id' => 'cs_test_123',
        ]);

        $response->assertOk();
        
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);

        $product->refresh();
        $this->assertEquals(4, $product->inventory_quantity); // 6 - 2
        $this->assertEquals('low_stock', $product->inventory_status); // <= 5 is low stock
    }

    public function test_payment_cancel_simulation_marks_order_as_failed(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Store Project',
            'slug' => 'store-project',
        ]);

        $order = Order::create([
            'project_id' => $project->id,
            'stripe_session_id' => 'cs_test_123',
            'customer_email' => 'client@example.com',
            'total_amount' => 100.00,
            'payment_status' => 'pending',
        ]);

        $response = $this->post('/checkout/simulate/cancel', [
            'session_id' => 'cs_test_123',
        ]);

        $response->assertOk();

        $order->refresh();
        $this->assertEquals('failed', $order->payment_status);
    }
}
