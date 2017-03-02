<?php

namespace App\Contracts;

/**
 * Interface Transformer
 * @package App\Contracts
 */
interface Transformer
{

    /**
     * @param $model
     *
     * @return array
     */
    public static function transform($model);
}