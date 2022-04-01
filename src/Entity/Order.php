<?php

namespace App\Entity;

class Order
{
    public $amount;

    public $currency;

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency(string $currency) {
        $this->currency = $currency;
    }
}