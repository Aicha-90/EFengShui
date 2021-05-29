<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CritereRepository")
 */
class Critere
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"demande"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=10)
     *  @Serializer\Groups({"demande"})
     */
    private $nb_m_carre;

    /**
     * @ORM\Column(name="lieu", type="string", columnDefinition="enum('bureau', 'domicile')")
     * @Serializer\Groups({"demande"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"demande"})
     */
    private $annee_constr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"demande"})
     */
    private $plan_lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"demande"})
     */
    private $photo_lieu;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"demande"})
     */
    private $orientation;

    /**
     * @ORM\Column(type="string", length=20)
     * @Serializer\Groups({"demande"})
     */
    private $titre_diagnostic;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Diagnostic", mappedBy="critere", cascade={"persist", "remove"})
     */
    private $diagnostic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="critere")
     */
    private $user;

       
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbMCarre(): ?string
    {
        return $this->nb_m_carre;
    }

    public function setNbMCarre(string $nb_m_carre): self
    {
        $this->nb_m_carre = $nb_m_carre;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getAnneeConstr(): ?int
    {
        return $this->annee_constr;
    }

    public function setAnneeConstr(int $annee_constr): self
    {
        $this->annee_constr = $annee_constr;

        return $this;
    }

    public function getPlanLieu(): ?string
    {
        return $this->plan_lieu;
    }

    public function setPlanLieu(?string $plan_lieu): self
    {
        $this->plan_lieu = $plan_lieu;

        return $this;
    }

    public function getPhotoLieu(): ?string
    {
        return $this->photo_lieu;
    }

    public function setPhotoLieu(?string $photo_lieu): self
    {
        $this->photo_lieu = $photo_lieu;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(?string $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getTitreDiagnostic(): ?string
    {
        return $this->titre_diagnostic;
    }

    public function setTitreDiagnostic(string $titre_diagnostic): self
    {
        $this->titre_diagnostic = $titre_diagnostic;

        return $this;
    }


    public function getDiagnostic(): ?Diagnostic
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(Diagnostic $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        // set the owning side of the relation if necessary

        if ($diagnostic->getCritere() !== $this) {
            $diagnostic->setCritere($this);

        }

        return $this;
    }


    public function getUser(): ?User


    {
        return $this->user;
    }


    public function setUser(?User $user): self

    {
        $this->user = $user;

        return $this;
    }

}
