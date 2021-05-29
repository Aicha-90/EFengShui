<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiagnosticRepository")
 */
class Diagnostic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut_paiement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $expertise;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut_expertise;

   
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Critere", inversedBy="diagnostic", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $critere;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatutPaiement(): ?bool
    {
        return $this->statut_paiement;
    }

    public function setStatutPaiement(bool $statut_paiement): self
    {
        $this->statut_paiement = $statut_paiement;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getExpertise(): ?string
    {
        return $this->expertise;
    }

    public function setExpertise(?string $expertise): self
    {
        $this->expertise = $expertise;

        return $this;
    }

    public function getStatutExpertise(): ?bool
    {
        return $this->statut_expertise;
    }

    public function setStatutExpertise(bool $statut_expertise): self
    {
        $this->statut_expertise = $statut_expertise;

        return $this;
    }

    public function getCritere(): ?Critere
    {
        return $this->critere;
    }

    public function setCritere(Critere $critere): self
    {
        $this->critere = $critere;

        return $this;
    }
}
