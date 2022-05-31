<?php

namespace Tests\Unit;

use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;
use App\Classes\Cart;

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

    public function testAddOnlyUniqueProductsToCart()
    {
        $ids = [1, 3, 4, 5];

        $this->assertEquals(true, ($this->fillCart($ids))['status']);
        $this->assertSame($ids, $this->cart->getProductsInCart());
        $this->assertEquals(count($ids), $this->cart->getCountProductsInCart());
    }

    public function testAddAlsoDoubleProductsToCart()
    {
        $ids = [1, 3, 4, 5, 3, 4, 4];

        $this->assertEquals(true, ($this->fillCart($ids))['status']);
        $this->assertSame(array_unique($ids), $this->cart->getProductsInCart());
        $this->assertEquals(count($ids), $this->cart->getCountProductsInCart());
    }

    public function testFailedWhenAddNonExistentProductToCart()
    {
        $ids = [1, 3, 1, 3, 4, 5, 6];

        $this->assertEquals(false, ($this->fillCart($ids))['status']);
        $this->assertEquals(0, $this->cart->getCountProductsInCart());
        $this->assertEquals([], $this->cart->getProductsInCart());
    }

//     United
//    public function testCheckCountProductsInCart()
//    {
//        $ids = [1, 3, 4, 5, 3, 3, 5];
//        $this->fillCart($ids);
//
//        $this->assertEquals(count($ids), $this->cart->getCountProductsInCart());
//    }

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
        $this->assertEquals(null, $this->cart->getItem($testingProductId));
    }

    public function testCleanCart()
    {
        $ids = [1, 3, 4, 5, 3, 4, 4];
        $this->fillCart($ids);

        $this->cart->cleanCart();

        $this->assertEquals(0, $this->cart->getCountProductsInCart());
        $this->assertEquals([], $this->cart->getProductsInCart());
    }

    public function testIncrementProductAlreadyExistsInCart()
    {
        $incrementedProduct = 4;
        $ids = [1, 3, $incrementedProduct, 5, 3, $incrementedProduct, $incrementedProduct];

        $this->fillCart($ids);

        $this->cart->incrementProduct($incrementedProduct);
        $this->cart->incrementProduct($incrementedProduct, 3);
        $this->cart->incrementProduct($incrementedProduct, 5);

        $this->assertSame(12, $this->cart->getProductCountInCartById($incrementedProduct));
    }

    public function testIncrementProductNonExistingInCart()
    {
        $incrementedProduct = 4;
        $ids = [1, 3, 5, 3];

        $this->fillCart($ids);

        $this->cart->incrementProduct($incrementedProduct, 2);
        $this->cart->incrementProduct($incrementedProduct);

        $this->assertSame(3, $this->cart->getProductCountInCartById($incrementedProduct));
    }

    public function testDecrementProductWithoutRemoving()
    {
        $decrementedProduct = 4;
        $ids = [1, $decrementedProduct, 5, $decrementedProduct, $decrementedProduct];

        $this->fillCart($ids);

        $this->cart->decrementProduct($decrementedProduct);
        $this->cart->decrementProduct($decrementedProduct);

        $this->assertSame(1, $this->cart->getProductCountInCartById($decrementedProduct));
    }

    public function testDecrementProductWithRemoving()
    {
        $decrementedProduct = 4;
        $ids = [1, $decrementedProduct, 5, 5, $decrementedProduct, $decrementedProduct];

        $this->fillCart($ids);

        $this->cart->decrementProduct($decrementedProduct);
        $this->cart->decrementProduct($decrementedProduct);
        $this->cart->decrementProduct($decrementedProduct);

        $this->assertEquals(3, $this->cart->getCountProductsInCart());
        $this->assertEquals([1, 5], $this->cart->getProductsInCart());
        $this->assertEquals(null, $this->cart->getItem($decrementedProduct));
    }

    public function testDecrementNonExistingInCartProduct()
    {
        $decrementedProduct = 4;
        $ids = [1, 2, 3, 2, 1, 5, 5,];

        $this->fillCart($ids);

        $this->assertEquals(false, $this->cart->decrementProduct($decrementedProduct));
        $this->assertEquals(count($ids), $this->cart->getCountProductsInCart());
        $this->assertSame(array_values(array_unique($ids)), $this->cart->getProductsInCart());
    }

    /**
     * Filling our cart
     */
    #[ArrayShape(['status' => "bool", 'message' => "string"])]
    protected function fillCart(array $productIds): array
    {
        return $this->cart->addProducts($productIds);
    }
}
