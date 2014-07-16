<?php

namespace Account\Entity;

use Administration\Entity\User;
use Application\Library\Application;
use Doctrine\ORM\Mapping as ORM;

/**
 * An FinancialYear entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="financial_years", uniqueConstraints={@ORM\UniqueConstraint(name="name_company_index", columns={"name", "company_id"})})
 * 
 * @property int $id
 * @property string $name
 * @property datetime $start_date
 * @property datetime $end_date
 * 
 */
class FinancialYear
{

    const COMPANY = 1;
    const OPEN = 1;
    const CLOSE = 0;
    const YES = 1;
    const NO = 0;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * 
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime",unique=true, nullable=false)
     * 
     */
    protected $start_date;

    /**
     * @ORM\Column(type="datetime",unique=true, nullable=false)
     * 
     */
    protected $end_date;
    
    /**
     * @ORM\Column(type="smallint", length=1)
     * 
     */
    protected $status;
    
    /**
     * @ORM\Column(type="smallint", length=1)
     * 
     */
    protected $is_current;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     */
    protected $creation_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     */
    protected $modification_date;
    
    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Company", inversedBy="FinancialYears")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
     */
    protected $Company;
    
    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\User", inversedBy="FinancialYearsCreatedBy")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $Creator;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\User", inversedBy="FinancialYearsModifiedBy")
     * @ORM\JoinColumn(name="modified_by", referencedColumnName="id", nullable=true)
     */
    protected $Modifier;
    
    /**
     * @ORM\ManyToMany(targetEntity="Administration\Entity\User", mappedBy="FinancialYears")
     */
    protected $Users;

    public function __construct($SL=null)
    {
        $now = Application::makeDateTimeObjectForDB("now");
        if (null !== $SL)
        {
            $userAuth = $SL->get('Zend\Authentication\AuthenticationService');
            $User = $userAuth->getIdentity();
            if ($this->id == null)
                $this->Creator = $User;
            else
                $this->Modifier = $User;
        }
        if ($this->id == null)
            $this->creation_date = $now;
        else
            $this->modification_date = $now;
        
        $this->Users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getCompany()
    {
        return $this->Company;
    }

    public function setCompany($Company)
    {
        $this->Company = $Company;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = Application::makeDateObjectForDB($start_date);
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setEndDate($end_date)
    {
        $this->end_date = Application::makeDateObjectForDB($end_date);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getIsCurrent()
    {
        return $this->is_current;
    }

    public function setIsCurrent($is_current)
    {
        $this->is_current = $is_current;
    }

            
    public function getCreator()
    {
        return $this->Creator;
    }

    public function setCreator(User $Creator)
    {
        $this->Creator = $Creator;
    }

    public function getModifier()
    {
        return $this->Modifier;
    }

    public function setModifier(User $Modifier)
    {
        $this->Modifier = $Modifier;
    }

    public function getCreationDate()
    {
        return $this->creation_date;
    }

    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function getModificationDate()
    {
        return $this->modification_date;
    }

    public function setModificationDate($modification_date)
    {
        $this->modification_date = Application::makeDateTimeObjectForDB($modification_date);
    }
    
    public function getUsers()
    {
        return $this->Users;
    }

    public function setUsers($Users)
    {
        $this->Users = $Users;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->status = isset($data['status']) ? $data['status'] : self::OPEN;
        $this->is_current = isset($data['is_current']) ? $data['is_current'] : self::NO;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
