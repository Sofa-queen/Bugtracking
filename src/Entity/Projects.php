<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectsRepository")
 */
class Projects
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_proj;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getNameProj(): ?string
    {
        return $this->name_proj;
    }

    public function setNameProj(string $name_proj): self
    {
        $this->name_proj = $name_proj;

        return $this;
    }
}
