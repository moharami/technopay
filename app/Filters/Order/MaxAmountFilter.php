<?php

namespace App\Filters\Order;


use App\Filters\Filter;

class MaxAmountFilter extends Filter
{
    public function apply()
    {
        return $this->builder->where('amount', '<', $this->value);
    }
}