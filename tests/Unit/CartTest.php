<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Classes\Cart;

class CartTest extends TestCase
{
    protected Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart = new Cart;
        $this->cart->addProduct(1);
        $this->cart->addProduct(2);
        $this->cart->addProduct(3);
    }

    /** @test */
    public function cart_model_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function add_product_to_cart()
    {
        $this->assertEquals(true, $this->cart->productExists(1));
        $this->assertEquals(true, $this->cart->productExists(2));
        $this->assertEquals(true, $this->cart->productExists(3));
    }

    /** @test */
    public function check_count_products_in_cart()
    {
        $this->assertEquals(3, $this->cart->count());
    }

    /** @test */
    public function remove_products_from_cart()
    {
        $this->cart->removeProduct(2);
        $this->cart->removeProduct(3);

        $this->assertEquals(1, $this->cart->count());
        $this->assertEquals(true, $this->cart->productExists(1));
    }

    /** @test */
    public function clean_cart()
    {
        $this->cart->clean();

        $this->assertEquals(0, $this->cart->count());
    }
}
