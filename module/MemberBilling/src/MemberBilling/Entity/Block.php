<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * An Block entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="blocks")
 * 
 * @property int $id
 * @property string $name
 * @property smallint $status
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * 
 */
class Block
{

    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=5, unique=true, nullable=false)
     * 
     */
    protected $name;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 1});
     * 
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    protected $creation_date;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    protected $modification_date;

    /*
     * Constructor
     */

    public function __construct()
    {
        //*****
        $now = new \DateTime("now");
        if ($this->id == null)
            $this->creation_date = $now;
        else
            $this->modification_date = $now;
        //*****
//        $this->Branches = new ArrayCollection();
//        $this->Accounts = new ArrayCollection();
//        $this->BioInfos = new ArrayCollection();
//        $this->Users = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreationDate()
    {
        return $this->creation_date;
    }

    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
        return $this;
    }

    public function getModificationDate()
    {
        return $this->modification_date;
    }

    public function setModificationDate($modification_date)
    {
        $this->modification_date = $modification_date;
        return $this;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->status = isset($data['status']) ? $data['status'] : self::ACTIVE;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}