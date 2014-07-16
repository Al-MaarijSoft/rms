<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation;

/**
 * An State entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="states")
 * 
 * @property int $id
 * @property string $name
 * 
 * @Annotation\Name("State")
 * 
 */
class State
{

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11);
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
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="States")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $Country;

    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="State", orphanRemoval=true, cascade={"persist"})
     * 
     * @Annotation\Required(true)
     */
    protected $Cities;

    public function __construct()
    {
        ;
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

    public function getCountry()
    {
        return $this->Country;
    }

    public function setCountry($Country)
    {
        $this->Country = $Country;
        return $this;
    }

    public function getCities()
    {
        return $this->Cities;
    }

    public function setCities($Cities)
    {
        $this->Cities = $Cities;
        return $this;
    }

}

?>
