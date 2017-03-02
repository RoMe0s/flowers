<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Thumb
 * @package App\Facades
 */
class Thumb extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {

        return 'thumb';
    }
}