<?php

namespace App\Filters\Order;


use App\Filters\Filter;

class MobileNumberFilter extends Filter
{
    public function apply()
    {
        $value = $this->value;
        return $this->builder->whereHas('user', function ($query) use ($value) {
            $query->where('mobile_number', $value);
        });

    }
}