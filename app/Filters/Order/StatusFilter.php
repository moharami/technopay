<?php

namespace App\Filters\Order;


use App\Filters\Filter;

class StatusFilter extends Filter
{
    public function apply()
    {
        return $this->builder->where('status', $this->value);
    }
}