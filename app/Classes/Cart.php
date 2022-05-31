<?php

namespace App\Classes;

use JetBrains\PhpStorm\ArrayShape;

class Cart
{
    protected array $products = [];

    #[ArrayShape(['status' => "bool", 'message' => "string"])]
    public function addProducts(array $productIds): array
    {
        $statusResponse = true;
        $messageResponse = 'The products have been added successfully';

        try {
            foreach ($productIds as $id) {
                $this->addProduct($id);
            }
        } catch (\Exception $exception) {
            $this->cleanCart();
            $statusResponse = false;
            $messageResponse = $exception->getMessage();
        }

        return [
            'status' => $statusResponse,
            'message' => $messageResponse,
        ];
    }

    public function addProduct(int $productId): bool
    {
        if (!Product::exists($productId)) {
            throw new \Exception("The product doesn't exist");
        }

        $this->incrementProduct($productId);

        return true;
    }

    public function getProductCountInCartById(int $productId): int
    {
        $product = $this->getItem($productId);

        return $product['count'] ?? 0;
    }

    public function getItem(int $id): ?array
    {
        return array_key_exists($id, $this->products)
            ? $this->products[$id]
            : null;
    }

    public function setItem(array $data): void
    {
        $this->products[$data['id']] = $data;
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

    public function removeProduct(int $productId): void
    {
        if (array_key_exists($productId, $this->products)) {
            unset($this->products[$productId]);
        }
    }

    public function incrementProduct(int $productId, int $amount = 1): void
    {
        $product = $this->getItem($productId);
        $this->setItem([
            'id' => $productId,
            'count' => null !== $product
                ? $product['count'] + $amount
                : $amount,
        ]);
    }

    public function decrementProduct(int $productId): bool
    {
        $product = $this->getItem($productId);

        if (null === $product) {
            return false;
        }

        $count = $product['count'] - 1;

        if ($count < 1) {
            $this->removeProduct($productId);

            return true;
        }

        $this->setItem([
            'id' => $productId,
            'count' => $count,
        ]);

        return true;
    }

    public function cleanCart(): void
    {
        $this->products = [];
    }
}
