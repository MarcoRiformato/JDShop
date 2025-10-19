<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role',
        ]);

        Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Regular user role',
        ]);

        // Create admin user
        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        // Create regular user
        $this->regularUser = User::factory()->create();
    }

    public function test_admin_can_view_product_dashboard(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_view_product_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_product(): void
    {
        $productData = [
            'title' => 'Test Product',
            'description' => 'Test Description',
            'tags' => 'test,product',
            'price' => 99.99,
            'sold_out' => false,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('products.store'), $productData);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'title' => 'Test Product',
            'price' => 99.99,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('products.update', $product), [
                'title' => 'Updated Product',
                'description' => 'Updated Description',
                'tags' => 'updated',
                'price' => 149.99,
                'sold_out' => true,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Updated Product',
            'price' => 149.99,
            'sold_out' => true,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('products.destroy', $product));

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_product_title_cannot_exceed_35_characters(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('products.store'), [
                'title' => str_repeat('a', 36),
                'price' => 99.99,
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_product_price_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('products.store'), [
                'title' => 'Test Product',
            ]);

        $response->assertSessionHasErrors('price');
    }
}

