<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Titularisation
 *
 * @ORM\Table(name="titularisation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TitularisationRepository")
 */
class Titularisation
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
     *
     * @ORM\Column(name="dateTitu", type="string", length=255, nullable=true)
     */
    private $dateTitu;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="buchette", type="string", length=255, nullable=true)
     */
    private $buchette;

    /**
     * @var string
     *
     * @ORM\Column(name="fonction", type="string", length=255, nullable=true)
     */
    private $fonction;

    /**
     * @var string
     *
     * @ORM\Column(name="entite", type="string", length=255, nullable=true)
     */
    private $entite;

    /**
     * Un chef est concerné par plusieurs titularisations
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chef", inversedBy="titularisations")
     * @ORM\JoinColumn(name="chef", referencedColumnName="id")
     */
    private $chef;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="publie_par", type="string", length=25, nullable=true)
     */
    private $publiePar;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="modifie_par", type="string", length=25, nullable=true)
     */
    private $modifiePar;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="publie_le", type="datetimetz", nullable=true)
     */
    private $publieLe;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modifie_le", type="datetimetz", nullable=true)
     */
    private $modifieLe;



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
     * Set dateTitu
     *
     * @param string $dateTitu
     *
     * @return Titularisation
     */
    public function setDateTitu($dateTitu)
    {
        $this->dateTitu = $dateTitu;

        return $this;
    }

    /**
     * Get dateTitu
     *
     * @return string
     */
    public function getDateTitu()
    {
        return $this->dateTitu;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Titularisation
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set buchette
     *
     * @param string $buchette
     *
     * @return Titularisation
     */
    public function setBuchette($buchette)
    {
        $this->buchette = $buchette;

        return $this;
    }

    /**
     * Get buchette
     *
     * @return string
     */
    public function getBuchette()
    {
        return $this->buchette;
    }

    /**
     * Set publiePar
     *
     * @param string $publiePar
     *
     * @return Titularisation
     */
    public function setPubliePar($publiePar)
    {
        $this->publiePar = $publiePar;

        return $this;
    }

    /**
     * Get publiePar
     *
     * @return string
     */
    public function getPubliePar()
    {
        return $this->publiePar;
    }

    /**
     * Set modifiePar
     *
     * @param string $modifiePar
     *
     * @return Titularisation
     */
    public function setModifiePar($modifiePar)
    {
        $this->modifiePar = $modifiePar;

        return $this;
    }

    /**
     * Get modifiePar
     *
     * @return string
     */
    public function getModifiePar()
    {
        return $this->modifiePar;
    }

    /**
     * Set publieLe
     *
     * @param \DateTime $publieLe
     *
     * @return Titularisation
     */
    public function setPublieLe($publieLe)
    {
        $this->publieLe = $publieLe;

        return $this;
    }

    /**
     * Get publieLe
     *
     * @return \DateTime
     */
    public function getPublieLe()
    {
        return $this->publieLe;
    }

    /**
     * Set modifieLe
     *
     * @param \DateTime $modifieLe
     *
     * @return Titularisation
     */
    public function setModifieLe($modifieLe)
    {
        $this->modifieLe = $modifieLe;

        return $this;
    }

    /**
     * Get modifieLe
     *
     * @return \DateTime
     */
    public function getModifieLe()
    {
        return $this->modifieLe;
    }

    /**
     * Set chef
     *
     * @param \AppBundle\Entity\Chef $chef
     *
     * @return Titularisation
     */
    public function setChef(\AppBundle\Entity\Chef $chef = null)
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * Get chef
     *
     * @return \AppBundle\Entity\Chef
     */
    public function getChef()
    {
        return $this->chef;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     *
     * @return Titularisation
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set entite
     *
     * @param string $entite
     *
     * @return Titularisation
     */
    public function setEntite($entite)
    {
        $this->entite = $entite;

        return $this;
    }

    /**
     * Get entite
     *
     * @return string
     */
    public function getEntite()
    {
        return $this->entite;
    }
}
