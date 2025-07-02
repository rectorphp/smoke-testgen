<?php

namespace SmokeTestgen202507\Illuminate\Contracts\Database\Query;

use SmokeTestgen202507\Illuminate\Database\Grammar;
interface Expression
{
    /**
     * Get the value of the expression.
     *
     * @param  \Illuminate\Database\Grammar  $grammar
     * @return string|int|float
     */
    public function getValue(Grammar $grammar);
}
