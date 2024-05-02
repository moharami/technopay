<?php

namespace App\Filters\Order;


use App\Filters\Filter;

class MinAmountFilter extends Filter
{
    public function apply()
    {
        if (!is_null($this->value)){
            return $this->builder->where('amount', '>', $this->value);
        }
    }
}