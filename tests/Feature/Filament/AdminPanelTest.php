<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\ManageAbout;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CategoryResource\RelationManagers\SubcategoriesRelationManager;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\ProductOptionsRelationManager;
use App\Models\About;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Fully self-seeding: uses RefreshDatabase against the isolated sqlite
 * test connection configured in phpunit.xml. Must never depend on, or
 * risk touching, the real dev database.
 */
class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected Subcategory $subcategory;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);

        $this->category = Category::create(['name' => 'Vegetables', 'sort_order' => 1, 'is_enabled' => true]);
        $this->subcategory = $this->category->subcategories()->create(['name' => 'Leafy Greens', 'sort_order' => 1, 'is_enabled' => true]);

        $this->product = Product::create([
            'subcategory_id' => $this->subcategory->id,
            'name' => 'Test Cabbage',
            'is_enabled' => true,
        ]);

        $this->product->productOptions()->create([
            'option_text' => '600-800g',
            'original_price' => 100,
            'price' => 85,
            'inventory' => 10,
            'sort_order' => 1,
            'is_enabled' => true,
        ]);

        About::create(['title' => 'About Us', 'content' => 'content', 'image' => null]);
    }

    protected function makeOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '0900000000',
            'shipping_email' => 'test@example.com',
            'shipping_city' => '台北市',
            'shipping_district' => '信義區',
            'shipping_address_detail' => '測試路1號',
            'shipping_zip_code' => '110',
            'address' => 'test',
            'order_status' => 'not_selected_payment',
            'amount' => 100,
            'user_id' => $this->admin->id,
        ], $overrides));
    }

    public function test_categories_index_renders(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/categories')
            ->assertOk();
    }

    public function test_categories_create_renders(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/categories/create')
            ->assertOk();
    }

    public function test_categories_edit_renders(): void
    {
        $this->actingAs($this->admin)
            ->get("/admin/categories/{$this->category->id}/edit")
            ->assertOk();
    }

    public function test_products_index_renders(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/products')
            ->assertOk();
    }

    public function test_products_create_renders(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/products/create')
            ->assertOk();
    }

    public function test_products_edit_renders(): void
    {
        $this->actingAs($this->admin)
            ->get("/admin/products/{$this->product->id}/edit")
            ->assertOk();
    }

    public function test_category_create_appends_sort_order(): void
    {
        $this->actingAs($this->admin);

        $max = Category::max('sort_order') ?? 0;

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => 'New Category', 'is_enabled' => true])
            ->call('create')
            ->assertHasNoFormErrors();

        $created = Category::where('name', 'New Category')->first();
        $this->assertNotNull($created);
        $this->assertSame($max + 1, $created->sort_order);
    }

    public function test_category_delete_blocked_when_has_subcategories(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListCategories::class)
            ->callTableAction('delete', $this->category);

        $this->assertNotNull($this->category->fresh());
    }

    public function test_category_delete_allowed_when_no_subcategories(): void
    {
        $this->actingAs($this->admin);

        $empty = Category::create(['name' => 'Empty Category', 'sort_order' => 99, 'is_enabled' => true]);

        Livewire::test(ListCategories::class)
            ->callTableAction('delete', $empty);

        $this->assertNull($empty->fresh());
    }

    public function test_subcategory_name_unique_is_scoped_per_category(): void
    {
        $this->actingAs($this->admin);

        $categoryB = Category::create(['name' => 'Category B', 'sort_order' => 2, 'is_enabled' => true]);

        // Same name under a DIFFERENT category should be allowed (scoped uniqueness).
        Livewire::test(SubcategoriesRelationManager::class, [
            'ownerRecord' => $categoryB,
            'pageClass' => EditCategory::class,
        ])
            ->callTableAction('create', data: ['name' => 'Leafy Greens', 'is_enabled' => true])
            ->assertHasNoTableActionErrors();

        $this->assertSame(
            2,
            Subcategory::where('name', 'Leafy Greens')->count(),
            'same name should be allowed across different categories'
        );

        // Same name under the SAME category should be rejected.
        Livewire::test(SubcategoriesRelationManager::class, [
            'ownerRecord' => $this->category,
            'pageClass' => EditCategory::class,
        ])
            ->callTableAction('create', data: ['name' => 'Leafy Greens', 'is_enabled' => true])
            ->assertHasTableActionErrors(['name']);
    }

    public function test_product_option_create_appends_scoped_sort_order(): void
    {
        $this->actingAs($this->admin);

        $maxOrder = $this->product->productOptions()->max('sort_order') ?? 0;
        $optionText = 'Test Option ' . uniqid();

        Livewire::test(ProductOptionsRelationManager::class, [
            'ownerRecord' => $this->product,
            'pageClass' => EditProduct::class,
        ])
            ->callTableAction('create', data: [
                'option_text' => $optionText,
                'original_price' => 100,
                'price' => 90,
                'inventory' => 5,
                'is_enabled' => true,
            ])
            ->assertHasNoTableActionErrors();

        $created = $this->product->productOptions()->where('option_text', $optionText)->first();
        $this->assertNotNull($created);
        $this->assertSame($maxOrder + 1, $created->sort_order);
    }

    public function test_product_option_text_unique_scoped_per_product(): void
    {
        $this->actingAs($this->admin);

        $existing = $this->product->productOptions()->first();

        Livewire::test(ProductOptionsRelationManager::class, [
            'ownerRecord' => $this->product,
            'pageClass' => EditProduct::class,
        ])
            ->callTableAction('create', data: [
                'option_text' => $existing->option_text,
                'original_price' => 100,
                'price' => 90,
                'inventory' => 5,
                'is_enabled' => true,
            ])
            ->assertHasTableActionErrors(['option_text']);
    }

    public function test_product_image_set_primary_and_delete_guard(): void
    {
        $this->actingAs($this->admin);

        $imgA = ProductImage::create(['product_id' => $this->product->id, 'image' => 'products/test/a.jpg', 'is_primary' => true, 'sort_order' => 1]);
        $imgB = ProductImage::create(['product_id' => $this->product->id, 'image' => 'products/test/b.jpg', 'is_primary' => false, 'sort_order' => 2]);

        $livewire = Livewire::test(ProductImagesRelationManager::class, [
            'ownerRecord' => $this->product,
            'pageClass' => EditProduct::class,
        ]);

        // Set B as primary.
        $livewire->callTableAction('setPrimary', $imgB);
        $this->assertTrue((bool) $imgB->fresh()->is_primary);
        $this->assertFalse((bool) $imgA->fresh()->is_primary);

        // Delete A (non-primary now) — should succeed, 1 image remains.
        $livewire->callTableAction('delete', $imgA);
        $this->assertNull($imgA->fresh());

        // Try to delete the last remaining image — should be blocked.
        $livewire->callTableAction('delete', $imgB);
        $this->assertNotNull($imgB->fresh(), 'the last image must not be deletable');
    }

    public function test_orders_index_renders(): void
    {
        $this->makeOrder();

        $this->actingAs($this->admin)
            ->get('/admin/orders')
            ->assertOk();
    }

    public function test_orders_no_create_or_edit_routes(): void
    {
        $order = $this->makeOrder();

        $this->assertFalse(Route::has('filament.admin.resources.orders.create'));
        $this->assertFalse(Route::has('filament.admin.resources.orders.edit'));

        $this->actingAs($this->admin)
            ->get("/admin/orders/{$order->order_number}")
            ->assertOk();
    }

    public function test_order_list_change_status_action(): void
    {
        $this->actingAs($this->admin);

        $order = $this->makeOrder();
        $this->assertSame('not_selected_payment', $order->order_status);

        Livewire::test(ListOrders::class)
            ->callTableAction('changeStatus', $order, data: ['order_status' => 'paid'])
            ->assertHasNoTableActionErrors();

        $this->assertSame('paid', $order->fresh()->order_status);
    }

    public function test_order_list_change_status_action_allows_send_before_paid(): void
    {
        $this->actingAs($this->admin);

        $order = $this->makeOrder();

        Livewire::test(ListOrders::class)
            ->callTableAction('changeStatus', $order, data: ['order_status' => 'send_before_paid'])
            ->assertHasNoTableActionErrors();

        $this->assertSame('send_before_paid', $order->fresh()->order_status);
    }

    public function test_order_status_filter_excludes_send_before_paid(): void
    {
        $options = array_keys(Order::ORDER_STATUS_LABELS);
        $this->assertNotContains('send_before_paid', $options);
        $this->assertContains('send_before_paid', array_keys(Order::ALL_ORDER_STATUS_LABELS));
    }

    public function test_view_order_renders_and_computes_amounts(): void
    {
        $order = $this->makeOrder();

        $this->actingAs($this->admin)
            ->get("/admin/orders/{$order->order_number}")
            ->assertOk();
    }

    public function test_view_order_change_status_header_action(): void
    {
        $this->actingAs($this->admin);

        $order = $this->makeOrder();

        Livewire::test(ViewOrder::class, ['record' => $order->order_number])
            ->callAction('changeStatus', data: ['order_status' => 'paid'])
            ->assertHasNoActionErrors();

        $this->assertSame('paid', $order->fresh()->order_status);
    }

    public function test_manage_about_renders(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/manage-about')
            ->assertOk();
    }

    public function test_manage_about_updates_existing_singleton_without_touching_untouched_image(): void
    {
        $this->actingAs($this->admin);

        $existing = About::first();
        $existing->update(['image' => 'about/existing.jpg']);
        Storage::fake('public');
        Storage::disk('public')->put('about/existing.jpg', 'fake-image-contents');

        Livewire::test(ManageAbout::class)
            ->fillForm(['title' => 'Updated Title'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(1, About::count(), 'save() must update the singleton, not insert a second row');
        $this->assertSame('Updated Title', $existing->fresh()->title);
        $this->assertSame('about/existing.jpg', $existing->fresh()->image, 'image should be untouched when the field was not changed');
        $this->assertTrue(Storage::disk('public')->exists('about/existing.jpg'), 'untouched image file must not be deleted');
    }
}
