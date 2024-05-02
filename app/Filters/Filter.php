<?php

namespace App\Filters;


use Illuminate\Http\Request;

class Filter
{
    public function __construct(public Request $request){ }
}