<?php

namespace App\Classes;

class Cart
{
    protected array $products = [];

    public function addProduct(int $productId): bool
    {
        if (!Product::exists($productId)) {
            throw new \Exception("The product doesn't exist");
        }

        $product = $this->getProductInCartById($productId);

        if ($product) {
            $this->products[$productId]['count'] = ++$this->products[$productId]['count'];
        } else {
            $this->products[$productId] = [
                'id' => $productId,
                'count' => 1,
            ];
        }

        return true;
    }

    public function getProductCountInCartById(int $productId): int
    {
        $product = $this->getProductInCartById($productId);

        return $product['count'] ?? 0;
    }

    public function getProductInCartById(int $productId): ?array
    {
        foreach ($this->getCart() as $product) {
            if ($product['id'] === $productId) {
                return $product;
            }
        }

        return null;
    }

    public function getCountProductsInCart(): int
    {
        $cart = $this->getCart();

        if (!$cart) {
            return 0;
        }

        return array_reduce($cart, function ($count, $product) {
            $count += $product['count'];

            return $count;
        });
    }

    public function getProductsInCart(): array
    {
        return array_keys($this->getCart());
    }

    public function getCart(): array
    {
        return $this->products;
    }

    public function removeProduct(int $productID): void
    {
        if (array_key_exists($productID, $this->products)) {
            unset($this->products[$productID]);
        }
    }

    public function cleanCart(): void
    {
        $this->products = [];
    }
}
