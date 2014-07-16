<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * An User entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="username_company_index", columns={"username", "company_id"})})
 * 
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property smallint $status
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * 
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("User")
 */
class User
{

    const YES = 1;
    const NO = 0;
    const ACTIVE = 1;
    const INACTIVE = 0;
    const SALT = 'ACK3HtAz[|U8]Of9@?Ppj:!';
    const SALT_LENGTH = 16;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer",length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * 
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Username:"})
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * 
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Display Name:"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Password:"})
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     * 
     */
    protected $salt;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 1});
     * 
     */
    private $status;

    /**
     * @ORM\Column(type="smallint", length=50, nullable=true, options={"default"=0})
     * 
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Remember Me ?:"})
     */
    protected $remember_me;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $creation_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $modification_date;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="Users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $Company;
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="Users")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $Role;

    /**
     * @ORM\OneToMany(targetEntity="BioInfo", mappedBy="User", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $BioInfos;

    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\Account", mappedBy="Creator")
     * 
     */
    protected $AccountsCreatedBy;
    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\Account", mappedBy="Modifier")
     * 
     */
    protected $AccountsModifiedBy;
    
    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\Voucher", mappedBy="Creator")
     * 
     */
    protected $VouchersCreatedBy;
    
    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\Voucher", mappedBy="Modifier")
     * 
     */
    protected $VouchersModifiedBy;
    
    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\FinancialYear", mappedBy="Creator")
     * 
     */
    protected $FinancialYearsCreatedBy;
    
    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\FinancialYear", mappedBy="Modifier")
     * 
     */
    protected $FinancialYearsModifiedBy;
    
    /**
     * @ORM\ManyToMany(targetEntity="Account\Entity\FinancialYear", inversedBy="Users")
     * @ORM\JoinTable(name="users_financial_years",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="financial_years_id", referencedColumnName="id")}
     *      )
     */
    protected $FinancialYears;
    
    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":""})
     */
    protected $submit;

    public function __construct()
    {
        //*****
        $now = new \DateTime("now");
        if ($this->id == null)
            $this->creation_date = $now;
        else
            $this->modification_date = $now;
        //*****
        $this->BioInfos = new ArrayCollection();
        $this->FinancialYears = new ArrayCollection();
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

    public function getUserName()
    {
        return $this->username;
    }

    public function setUserName($user_name)
    {
        $this->username = $user_name;
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

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $Bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $Bcrypt->setSalt(base64_decode($this->salt));
        $hashedPassword = $Bcrypt->create($password);
        $this->password = $hashedPassword;
        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt = null)
    {
        if (null === $salt)
        {
            $salt = \Zend\Math\Rand::getBytes(self::SALT_LENGTH, $strong = false);
            $encrytSalt = base64_encode($salt);
        }
        $this->salt = $encrytSalt;
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

    public function getRememberMe()
    {
        return $this->remember_me;
    }

    public function setRememberMe($remember_me)
    {
        $this->remember_me = $remember_me;
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

    public function getCompany()
    {
        return $this->Company;
    }

    public function setCompany($Company)
    {
        $this->Company = $Company;
        return $this;
    }

    public function getBioInfos()
    {
        return $this->BioInfos;
    }

    public function setBioInfos(\Administration\Entity\BioInfo $BioInfos)
    {
        $this->BioInfos = $BioInfos;
        return $this;
    }

    public function getRole()
    {
        return $this->Role;
    }

    public function setRole(Role $Role)
    {
        $this->Role = $Role;
    }

    public function getAccountsCreatedBy()
    {
        return $this->AccountsCreatedBy;
    }

    public function setAccountsCreatedBy(\Account\Entity\Account $AccountsCreatedBy)
    {
        $this->AccountsCreatedBy = $AccountsCreatedBy;
    }

    public function getAccountsModifiedBy()
    {
        return $this->AccountsModifiedBy;
    }

    public function setAccountsModifiedBy(\Account\Entity\Account $AccountsModifiedBy)
    {
        $this->AccountsModifiedBy = $AccountsModifiedBy;
    }

    public function getVouchersCreatedBy()
    {
        return $this->VouchersCreatedBy;
    }

    public function setVouchersCreatedBy(\Account\Entity\Voucher $VouchersCreatedBy)
    {
        $this->VouchersCreatedBy = $VouchersCreatedBy;
    }

    public function getVouchersModifiedBy()
    {
        return $this->VouchersModifiedBy;
    }

    public function setVouchersModifiedBy(\Account\Entity\Voucher $VouchersModifiedBy)
    {
        $this->VouchersModifiedBy = $VouchersModifiedBy;
    }

    public function getFinancialYearsCreatedBy()
    {
        return $this->FinancialYearsCreatedBy;
    }

    public function setFinancialYearsCreatedBy($FinancialYearsCreatedBy)
    {
        $this->FinancialYearsCreatedBy = $FinancialYearsCreatedBy;
    }

    public function getFinancialYearsModifiedBy()
    {
        return $this->FinancialYearsModifiedBy;
    }

    public function setFinancialYearsModifiedBy($FinancialYearsModifiedBy)
    {
        $this->FinancialYearsModifiedBy = $FinancialYearsModifiedBy;
    }

    public function getFinancialYears()
    {
        return $this->FinancialYears;
    }

    public function setFinancialYears($FinancialYears)
    {
        $this->FinancialYears = $FinancialYears;
    }

    public function addFinancialYears($FinancialYears)
    {
        foreach ($FinancialYears as $FinancialYear)
        {
            $this->FinancialYears->add($FinancialYear);
        }
    }

    public function removeFinancialYears($FinancialYears)
    {
        foreach ($FinancialYears as $FinancialYear)
        {
            $this->FinancialYears->removeElement($FinancialYear);
        }
    }
    
    public function updateFinancialYears($FinancialYearsOld, $FinancialYearsNew)
    {
        $this->removeFinancialYears($FinancialYearsOld);
        $this->addFinancialYears($FinancialYearsNew);
    }
    
    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->username = isset($data['username']) ? $data['username'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->password = isset($data['password']) ? $data['password'] : null;
        $this->setSalt(null);
        $this->setPassword($this->password);
        $this->status = isset($data['status']) ? $data['status'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public static function verifyHashedPassword(\Administration\Entity\User $User, $passwordGiven)
    {
        $Bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $Bcrypt->setSalt(base64_decode($User->getSalt()));
        return $Bcrypt->verify($passwordGiven, $User->getPassword());
    }

}
