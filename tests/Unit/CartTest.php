<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Classes\Cart;

// Пропустил тест на декримент продукта (Product_1) из корзины (нужен он)???
// Например, Product_1 в корзине 3 шт., мы нажимаем '-' напротив этого продукта и теперь Product_1 2 шт.
class CartTest extends TestCase
{
    protected Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart = new Cart;
    }

    public function testCartClassExists()
    {
        $this->assertTrue(true);
    }

    public function testAddProductsToCart()
    {
        $ids = [1, 3, 4, 5, 3, 4, 4];
        $this->fillCart($ids);

        $this->assertSame(array_unique($ids), $this->cart->getProductsInCart());
    }

    public function testFailedWhenAddNonExistentProductToCart()
    {
        $ids = [1, 3, 1, 3, 4, 5, 6];

        $this->expectExceptionMessage("The product doesn't exist");
        $this->fillCart($ids);
    }

    public function testCheckCountProductsInCart()
    {
        $ids = [1, 3, 4, 5, 3, 3, 5];
        $this->fillCart($ids);

        $this->assertEquals(count($ids), $this->cart->getCountProductsInCart());
    }

    public function testCheckCountInCartByProduct()
    {
        $testingProductId = 3;
        $ids = [1, $testingProductId, 4, 5, $testingProductId, $testingProductId, 5];
        $this->fillCart($ids);

        $this->assertEquals(
            array_count_values($ids)[$testingProductId],
            $this->cart->getProductCountInCartById($testingProductId)
        );
    }

    public function testRemoveProductFromCart()
    {
        $testingProductId = 3;
        $ids = [1, $testingProductId, 4, 5, $testingProductId, $testingProductId, 5];
        $this->fillCart($ids);

        $this->cart->removeProduct($testingProductId);

        $this->assertEquals(4, $this->cart->getCountProductsInCart());
        $this->assertEquals([1, 4, 5], $this->cart->getProductsInCart());
        $this->assertEquals(null, $this->cart->getProductInCartById($testingProductId));
    }

    public function testCleanCart()
    {
        $ids = [1, 3, 4, 5, 3, 4, 4];
        $this->fillCart($ids);

        $this->cart->cleanCart();

        $this->assertEquals(0, $this->cart->getCountProductsInCart());
        $this->assertEquals([], $this->cart->getProductsInCart());
    }

    /**
     * Filling our cart
     */
    protected function fillCart(array $productIds): void
    {
        foreach ($productIds as $id) {
            $this->cart->addProduct($id);
        }
    }
}
