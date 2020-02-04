<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TileRepository")
 */
class Tile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $coordX;

    /**
     * @ORM\Column(type="integer")
     */
    private $coordY;

    /**
     * @ORM\Column(type="boolean")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasTicket;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHideout;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoordX(): ?int
    {
        return $this->coordX;
    }

    public function setCoordX(int $coordX): self
    {
        $this->coordX = $coordX;

        return $this;
    }

    public function getCoordY(): ?int
    {
        return $this->coordY;
    }

    public function setCoordY(int $coordY): self
    {
        $this->coordY = $coordY;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function hasTicket(): ?bool
    {
        return $this->hasTicket;
    }

    public function setHasTicket(bool $hasTicket): self
    {
        $this->hasTicket = $hasTicket;

        return $this;
    }

    public function getIsHideout(): ?bool
    {
        return $this->isHideout;
    }

    public function setIsHideout(bool $isHideout): self
    {
        $this->isHideout = $isHideout;

        return $this;
    }
}
