<?php

namespace App\DTO;

class IngredientsDto
{
    public $name;
    public $unit;

    public function __construct($name, $unit)
    {
        $this->name = $name;
        $this->unit = $unit;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUnit()
    {
        return $this->unit;
    }
}