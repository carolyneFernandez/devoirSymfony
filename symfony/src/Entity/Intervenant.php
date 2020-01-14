<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntervenantRepository")
 */
class Intervenant
{
    /**
     * @ORM\Id()use Symfony\Component\Validator\Constraints as Assert;
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

      /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = "18",
     *      max = "65",
     *      minMessage ="le intervenant doit faire mayor de edad",
     *      maxMessage = "le intervenant debe tener menos de 65 años"
     * )
     */
    private $age;

    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Matiere",
     *      mappedBy="intervenant"
     * )
     */

    private $matieres;

    

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }


     /**
    * @Assert\Callback
    */
    public function validate(ExecutionContextInterface $context) {

        if(count($this->matieres) > 2){
            $context->buildViolation("Error ,you have to select maximun 2 matiere ")
                ->addViolation();
        }
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->setIntervenant($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->contains($matiere)) {
            $this->matieres->removeElement($matiere);
            // set the owning side to null (unless already changed)
            if ($matiere->getIntervenant() === $this) {
                $matiere->setIntervenant(null);
            }
        }

        return $this;
    }
}
