<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Personne
 *
 * @ORM\Table(name="personne")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonneRepository")
 */
class Personne
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Le nom ne peut être vide")
     * @Assert\Length(max="50",min="2",
     * minMessage="Votre nom doit faire plus de {{ limit }} caractères!",
     * maxMessage="Votre nom doit faire moins de {{ limit }} caractères!")
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Le prénom ne peut être vide")
     * @Assert\Length(max="50",min="2",
     * minMessage="Votre prénom doit faire plus de {{ limit }} caractères!",
     * maxMessage="Votre prénom doit faire moins de {{ limit }} caractères!")
     * @ORM\Column(name="prenom", type="string", length=50)
     */
    private $prenom;

    /**
     * @var string
     * @Assert\NotBlank(message="Vous devez saisir une adresse mail")
     * @Assert\Email(message="Vous devez saisir une adresse mail valide")
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Paiement", mappedBy="personne", cascade={"persist"})
     */
    private $paiements;

    /**
     * @var Adresse
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Adresse", inversedBy="personnes", cascade={"persist"})
     */
    private $adresse;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Personne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Personne
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return ArrayCollection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }

    /**
     * @param ArrayCollection $paiements
     */
    public function setPaiements($paiements)
    {
        $this->paiements = $paiements;
    }

    /**
     * Ajout d'un paiement à la personne
     * @param $paiement
     */
    public function addPaiement($paiement){
        $this->paiements[] = $paiement;
    }

    /**
     * @return Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param Adresse $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }
}

