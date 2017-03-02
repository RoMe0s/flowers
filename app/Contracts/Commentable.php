<?php

namespace App\Contracts;

/**
 * Interface Commentable
 * @package App\Contracts
 */
interface Commentable
{

    /**
     * @return string
     */
    public function getAdminUrl();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getImage();
}