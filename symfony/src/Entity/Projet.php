<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 */
class Projet
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
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreEtudiant;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Etudiant", inversedBy="projets")
     * 
     */
    private $etudiants;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Range(
     *      min = "0",
     *      max = "20"
     * )
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matiere", inversedBy="projets")
     */
    private $matiere;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNombreEtudiant(): ?int
    {
        return $this->nombreEtudiant;
    }

    public function setNombreEtudiant(int $nombreEtudiant): self
    {
        $this->nombreEtudiant = $nombreEtudiant;

        return $this;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
        }

        return $this;
    }


    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->contains($etudiant)) {
            $this->etudiants->removeElement($etudiant);
        }

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }


     /**
    * @Assert\Callback
    */
    public function validate(ExecutionContextInterface $context) {
        if(empty($this->getNom()) ){
            $context->buildViolation("No field can be empty ")
                ->addViolation();
        }
        
        if(count($this->etudiants)> $this->getNombreEtudiant()){
            $context->buildViolation("Error ,you have to select maximun ". $this->getNombreEtudiant()." students ")
                ->addViolation();
        }
    }
}
