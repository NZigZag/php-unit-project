<?php

namespace App\Classes;

class Cart
{
    protected array $products = [];

    public function addProduct(int $productID): void
    {
        $this->products[] = $productID;
    }

    public function productExists(int $productID): bool
    {
        return in_array($productID, $this->products);
    }

    public function count(): int
    {
        return count($this->products);
    }

    public function removeProduct(int $productID): void
    {
        if (($key = array_search($productID, $this->products)) !== false) {
            unset($this->products[$key]);
        }
    }

    public function clean(): void
    {
        $this->products = [];
    }
}
