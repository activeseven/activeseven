<?php


namespace activeseven;

use activeseven\interfaces\ActiveStringInterface;

class ActiveString implements ActiveStringInterface
{

    protected $string = [];

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString()
    {
        return $this->string;
    }
}