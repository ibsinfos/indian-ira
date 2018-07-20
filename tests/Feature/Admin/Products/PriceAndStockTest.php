<?php

namespace Tests\Feature\Admin\Products;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\ProductPriceAndOption;
use IndianIra\Utilities\Directories;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PriceAndStockTest extends TestCase
{
    use RefreshDatabase, Directories;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_products_price_and_stock_section()
    {
        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.priceAndStock'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_products_price_and_stock_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.products.priceAndStock'))
             ->assertViewIs('admin.products-price-and-stock.index')
             ->assertSee('List of All Products With Price And Stock');
    }

    /** @test */
    function no_products_data_exists()
    {
        $this->assertCount(0, Product::all());
    }

    /** @test */
    function super_administrator_can_edit_the_product_price_and_stock()
    {
        $category  = factory(\IndianIra\Category::class)->create();
        $option  = factory(ProductPriceAndOption::class)->create();
        $product = $option->product;
        $product->categories()->attach([$category->id]);

        $productsData = $this->mergePriceAndStockData(['gst_percent' => 15]);

        $response = $this->withoutExceptionHandling()
                         ->post(
                            route('admin.products.priceAndStock.update', [
                                $product->code, $option->option_code
                            ]),
                            $productsData
                        );

        $result = json_decode($response->getContent());

        $products = $this->getAllProducts();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product prices and stock updated successfully!');
        $this->assertEquals($result->htmlResult, view('admin.products-price-and-stock.table', compact('products'))->render());

        $this->assertNotEquals($option->selling_price, 500.00);
        $this->assertEquals($option->fresh()->selling_price, 500.00);

        $this->assertNotEquals($product->gst_percent, 15.0);
        $this->assertEquals($product->fresh()->gst_percent, 15.0);
    }

    /** @test */
    function selling_price_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['selling_price' => '']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('selling_price');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('selling_price'),
            'The selling price field is required.'
        );
    }

    /** @test */
    function selling_price_field_should_contain_only_nummbers_upto_2_decimals_only()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['selling_price' => '28413.52841']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('selling_price');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('selling_price'),
            'The selling price should contain only numbers upto 2 precisions.'
        );
    }

    /** @test */
    function discount_price_field_should_contain_only_nummbers_upto_2_decimals_only()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['discount_price' => '28413.52841']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('discount_price');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('discount_price'),
            'The discount price should contain only numbers upto 2 precisions.'
        );
    }

    /** @test */
    function stock_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['stock' => '']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('stock');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('stock'),
            'The stock field is required.'
        );
    }

    /** @test */
    function stock_should_be_an_integer()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['stock' => '2yzjdfm']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('stock');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('stock'),
            'The stock must be an integer.'
        );
    }

    /** @test */
    function stock_cannot_be_less_than_0()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['stock' => '-28']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('stock');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('stock'),
            'The stock must be at least 0.'
        );
    }

    /** @test */
    function gst_percent_field_should_contain_only_nummbers_upto_2_decimals_only()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $formValues = array_merge($option->toArray(), ['gst_percent' => '28413.52841']);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndStock.update', [$option->product->code, $option->option_code]), $formValues)
            ->assertSessionHasErrors('gst_percent');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gst_percent'),
            'The gst percent should contain only numbers upto 2 precisions.'
        );
    }

    protected function mergePriceAndStockData($atttributes = [])
    {
        return array_merge([
            'stock'          => 100,
            'gst_percent'    => 18.0,
            'selling_price'  => 500.00,
            'discount_price' => 0.0,
        ], $atttributes);
    }

    /**
     * Get all the products.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllProducts()
    {
        $allProducts = Product::withTrashed()
                        ->with(['options'])
                        ->orderBy('id', 'DESC')
                        ->get();

        $productsCollection = collect();

        foreach ($allProducts as $product) {
            if ($product->options->isNotEmpty()) {
                foreach ($product->options as $option) {
                    $productsCollection->push($option);
                }
            }
        }

        return $productsCollection;
    }
}
