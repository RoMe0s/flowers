<?php

namespace App\Contracts;

/**
 * Interface Permissions
 * @package App\Contracts
 */
interface Permissions
{

    /**
     * Load permissions list single level array
     *
     * @return array
     */
    public function load();

    /**
     * Load permissions list in multi-dimensional array
     *
     * @return array
     */
    public function loadArray();
}