<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * An Company entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="companies")
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property smallint $status
 * @property string $description
 * @property datetime $creation_date
 * @property datetime $modification_date
 * @property string $email
 * @property string $website
 * @property string $contact_person
 * @property string $contact_number
 * @property string $community
 * 
 */
class Company
{

    const ACTIVE = 1;
    const INACTIVE = 0;
    const DEFAULTCOMPANY = 1;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     */public function getEmail() {
        return $this->email;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function getContactPerson() {
        return $this->contact_person;
    }

    public function getContactNumber() {
        return $this->contact_number;
    }

    public function getCommunity() {
        return $this->community;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setWebsite($website) {
        $this->website = $website;
    }

    public function setContact_person($contact_person) {
        $this->contact_person = $contact_person;
    }

    public function setContact_number($contact_number) {
        $this->contact_number = $contact_number;
    }

    public function setCommunity($community) {
        $this->community = $community;
    }

        protected $email;
    
    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=true)
     * 
     */
    protected $website;
    
    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     */
    protected $contact_person;
    
    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     */
    protected $contact_number;
    
    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     */
    protected $community;
    
    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 1});
     * 
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=500)
     * 
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Branch", mappedBy="Company", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $Branches;

    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="Company", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $Roles;

    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\FinancialYear", mappedBy="Company", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $FinancialYears;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="Company", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $Users;

    /**
     * @ORM\OneToMany(targetEntity="BioInfo", mappedBy="Company", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $BioInfos;

    /**
     * @ORM\OneToMany(targetEntity="\Account\Entity\Account", mappedBy="Company", orphanRemoval=true)
     * 
     */
    protected $Accounts;

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
        $this->Branches = new ArrayCollection();
        $this->Accounts = new ArrayCollection();
        $this->BioInfos = new ArrayCollection();
        $this->Users = new ArrayCollection();
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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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

    public function getBranches()
    {
        return $this->Branches;
    }

    public function setBranches($Branches)
    {
        $this->Branches = $Branches;
        return $this;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->status = isset($data['status']) ? $data['status'] : self::ACTIVE;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->website = isset($data['website']) ? $data['website'] : null;
        $this->contact_number = isset($data['contact_number']) ? $data['contact_number'] : null;
        $this->contact_person = isset($data['contact_person']) ? $data['contact_person'] : null;
        $this->community = isset($data['community']) ? $data['community'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

