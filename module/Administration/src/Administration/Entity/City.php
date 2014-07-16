<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation;

/**
 * An City entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="cities")
 * 
 * @property int $id
 * @property string $name
 * 
 * @Annotation\Name("City")
 */
class City
{

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="State", inversedBy="Cities")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
     */
    protected $State;

    /**
     * @ORM\OneToMany(targetEntity="BioInfo", mappedBy="City", orphanRemoval=true, cascade={"persist"})
     * 
     * @Annotation\Required(true)
     */
    protected $BioInfos;

    public function __construct()
    {
        $this->BioInfos = new Doctrine\ORM\PersistentCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getState()
    {
        return $this->State;
    }

    public function setState($State)
    {
        $this->State = $State;
        return $this;
    }

    public function getBioInfos()
    {
        return $this->BioInfos;
    }

    public function setBioInfos($BioInfos)
    {
        $this->BioInfos = $BioInfos;
        return $this;
    }

}
