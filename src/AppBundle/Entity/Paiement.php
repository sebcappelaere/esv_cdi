<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaiementRepository")
 */
class Paiement
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
     * @var \DateTime
     * @Assert\Date
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="nature", type="string", length=50)
     */
    private $nature;

    /**
     * @var Personne
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Personne", inversedBy="paiements", cascade={"persist"})
     */
    private $personne;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Paiement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return Paiement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set nature
     *
     * @param string $nature
     *
     * @return Paiement
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return string
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * @return Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * @param Personne $personne
     */
    public function setPersonne($personne)
    {
        $this->personne = $personne;
    }
}

