<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * An BioInfo entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="bio_infos")
 * 
 * @property int $id
 * @property string $zip_code
 * @property string $address
 * @property string $email
 * @property string cell
 * @property string $phone1
 * @property string $phone2
 * @property string $fax
 * 
 * @Annotation\Name("BioInfo")
 * 
 */
class BioInfo
{

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint", length=5, nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $zip_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Annotation\Required(true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $cell;

    /**
     * @ORM\Column(type="string", length=15, nullable=false)
     * 
     * @Annotation\Required(false)
     */
    protected $phone1;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $phone2;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $fax;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="BioInfos")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $City;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="BioInfos")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $Company;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="BioInfos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $User;

    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="BioInfos")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $Branch;

    public function __construct()
    {
        
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

    public function getZipCode()
    {
        return $this->zip_code;
    }

    public function setZipCode($zip_code)
    {
        $this->zip_code = $zip_code;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getCell()
    {
        return $this->cell;
    }

    public function setCell($cell)
    {
        $this->cell = $cell;
        return $this;
    }

    public function getPhone1()
    {
        return $this->phone1;
    }

    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
        return $this;
    }

    public function getPhone2()
    {
        return $this->phone2;
    }

    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
        return $this;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    public function getCity()
    {
        return $this->City;
    }

    public function setCity(City $City)
    {
        $this->City = $City;
        return $this;
    }

    public function getCompany()
    {
        return $this->Company;
    }

    public function setCompany(Company $Company)
    {
        $this->Company = $Company;
        return $this;
    }

    public function getBranch()
    {
        return $this->Branch;
    }

    public function setBranch(Branch $Branch)
    {
        $this->Branch = $Branch;
        return $this;
    }

    public function getUser()
    {
        return $this->User;
    }

    public function setUser(\Administration\Entity\User $User)
    {
        $this->User = $User;
        return $this;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->zip_code = isset($data['zip_code']) ? $data['zip_code'] : null;
        $this->address = isset($data['address']) ? $data['address'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->cell = isset($data['cell']) ? $data['cell'] : null;
        $this->phone1 = isset($data['phone1']) ? $data['phone1'] : null;
        $this->phone2 = isset($data['phone2']) ? $data['phone2'] : null;
        $this->fax = isset($data['fax']) ? $data['fax'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
