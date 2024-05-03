<?php

namespace App\Filters\Order;


use App\Filters\Filter;

class NationalCodeFilter extends Filter
{
    public function apply()
    {
        $value = $this->value;
        return $this->builder->whereHas('user', function ($query) use ($value) {
            $query->where('national_code', $value);
        });

    }
}