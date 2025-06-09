<?php

namespace App\Entity;

use App\Repository\MdpRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MdpRepository::class)]
class Mdp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    #[ORM\Column(length: 255)]
    private ?string $Identifiant = null;

    #[ORM\Column(length: 255)]
    private ?string $Mdp = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $udpdateAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getIdentifiant(): ?string
    {
        return $this->Identifiant;
    }

    public function setIdentifiant(string $Identifiant): static
    {
        $this->Identifiant = $Identifiant;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->Mdp;
    }

    public function setMdp(string $Mdp): static
    {
        $this->Mdp = $Mdp;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUdpdateAt(): ?\DateTimeImmutable
    {
        return $this->udpdateAt;
    }

    public function setUdpdateAt(\DateTimeImmutable $udpdateAt): static
    {
        $this->udpdateAt = $udpdateAt;

        return $this;
    }
}
