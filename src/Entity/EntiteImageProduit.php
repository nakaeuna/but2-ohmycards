<?php

namespace App\Entity;

use App\Repository\EntiteImageProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EntiteImageProduitRepository::class)]
#[Vich\Uploadable]
class EntiteImageProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'produits_images', fileNameProperty: 'nomFichier', size: 'taille')]
    private ?File $fichier = null;

    #[ORM\Column(nullable: true)]
    private ?string $nomFichier = null;

    #[ORM\Column]
    private ?int $taille = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $derniereMiseAJour = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?EntiteProduit $entiteProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $FichierImage
     */
    public function setFichier(?File $fichier = null): void
    {
        $this->fichier = $fichier;

        if (null !== $fichier) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->derniereMiseAJour = new \DateTimeImmutable();
        }
    }

    public function getFichier(): ?File
    {
        return $this->fichier;
    }

    public function setNomFichier(?string $nomFichier): void
    {
        $this->nomFichier = $nomFichier;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setTaille(?int $nomFichier): void
    {
        $this->taille = $nomFichier;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }


    public function getEntiteProduit(): ?EntiteProduit
    {
        return $this->entiteProduit;
    }

    public function setEntiteProduit(?EntiteProduit $entiteProduit): static
    {
        $this->entiteProduit = $entiteProduit;

        return $this;
    }
}
