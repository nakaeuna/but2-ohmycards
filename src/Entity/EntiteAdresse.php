<?php

namespace App\Entity;

use App\Repository\EntiteAdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntiteAdresseRepository::class)]
class EntiteAdresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $codePost = null;

    #[ORM\Column(length: 255)]
    private ?string $nomRue = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroRue = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodePost(): ?int
    {
        return $this->codePost;
    }

    public function setCodePost(int $codePost): static
    {
        $this->codePost = $codePost;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): static
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    public function getNumeroRue(): ?string
    {
        return $this->numeroRue;
    }

    public function setNumeroRue(string $numeroRue): static
    {
        $this->numeroRue = $numeroRue;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
}
