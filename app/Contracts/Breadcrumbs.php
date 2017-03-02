<?php

namespace App\Contracts;

/**
 * Interface Breadcrumbs
 * @package App\Contracts
 */
interface Breadcrumbs
{

    /**
     * @param object|null $model
     */
    public function setBreadcrumbs($model);
}