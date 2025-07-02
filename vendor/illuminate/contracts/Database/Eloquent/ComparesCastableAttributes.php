<?php

namespace SmokeTestgen202507\Illuminate\Contracts\Database\Eloquent;

use SmokeTestgen202507\Illuminate\Database\Eloquent\Model;
interface ComparesCastableAttributes
{
    /**
     * Determine if the given values are equal.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $firstValue
     * @param  mixed  $secondValue
     * @return bool
     */
    public function compare(Model $model, string $key, $firstValue, $secondValue);
}
