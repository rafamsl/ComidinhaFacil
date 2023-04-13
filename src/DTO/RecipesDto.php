<?php

namespace App\DTO;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;

class RecipesDto
{
    public $name;
    public $description;
    public $owner;

    public function __construct($name, $description, $owner)
    {
        $this->name = $name;
        $this->description = $description;
        $this->owner = $owner;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner ;
    }

}