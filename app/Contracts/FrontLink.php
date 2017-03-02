<?php

namespace App\Contracts;

/**
 * Class FrontLink
 * @package App\Contracts
 */
interface FrontLink
{

    /**
     * @return string
     */
    public function getUrl();
}