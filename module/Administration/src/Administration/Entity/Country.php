<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation;

/**
 * An Country entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="countries")
 * 
 * @property int $id
 * @property string $name
 * 
 * @Annotation\Name("Country")
 * 
 */
class Country
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
     * @ORM\OneToMany(targetEntity="State", mappedBy="Country", orphanRemoval=true, cascade={"persist"})
     * 
     * @Annotation\Required(true)
     */
    protected $States;

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

    public function getStates()
    {
        return $this->States;
    }

    public function setStates($States)
    {
        $this->States = $States;
        return $this;
    }
     public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}