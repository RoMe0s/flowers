<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ImageUploader
 * @package App\Facades
 */
class ImageUploader extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {

        return 'image_uploader';
    }
}