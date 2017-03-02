<?php

namespace App\Contracts;

/**
 * Interface MetaGettable
 * @package App\Contracts
 */
interface MetaGettable
{

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @return string
     */
    public function getMetaImage();

    /**
     * @return string
     */
    public function getUrl();
}