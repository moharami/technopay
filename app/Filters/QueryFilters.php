<?php

namespace App\Filters;

use App\Filters\Order\StatusFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;


class QueryFilters
{

    public function __construct(protected $request, protected Builder $builder)
    {

    }

    public function apply()
    {
        $pipeline = [StatusFilter::class];
        Pipeline::send($this->builder)
            ->through($pipeline)
            ->thenReturn();
        return $this->builder;
    }

    public function filters()
    {
        return $this->request->all();
    }
}