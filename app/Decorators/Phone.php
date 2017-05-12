<?php

namespace App\Decorators;

/**
 * Class Phone
 * @package App\Decorators
 */
class Phone extends AbstractDecorator
{

    /**
     * @return string
     */
    function getDecorated()
    {
        return preg_replace('/\D/', '', $this->object);
    }
}