<?php

namespace App\Decorators;

/**
 * Class AbstractDecorator
 * @package App\Decorators
 */
abstract class AbstractDecorator
{

    /**
     * @var
     */
    protected $object;

    /**
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDecorated();
    }

    /**
     * @return string
     */
    abstract function getDecorated();
}