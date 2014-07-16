<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation,
    \Doctrine\Common\Collections\ArrayCollection;

/**
 * An Account entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="accounts", uniqueConstraints={@ORM\UniqueConstraint(name="name_company_index", columns={"name", "company_id"})})
 * 
 * @property int $id
 * @property string $name
 * @property int $class
 * @property smallint $category
 * @property smallint $level
 * @property int $serial
 * @property smallint $status
 * @property string $description
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * @Annotation\Name("Account")

 */
class Account
{

    const ACTIVE = 1;
    const INACTIVE = 0;

    //*** Account Category Values
    const SUPER_CONTROL = 1;
    const CONTROL = 2;
    const DETAILED = 3;

    //*** Account Class Values
    const ASSET = 1;
    const INCOME = 2;
    const EXPENSE = 3;
    const LIABILITY = 4;
    const CAPITAL = 5;

    //*** SuperControl Account Codes
    const CODE_ASSET = '01';
    const CODE_INCOME = '02';
    const CODE_EXPENSE = '03';
    const CODE_LIABILITY = '04';
    const CODE_CAPITAL = '05';

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
     * @ORM\Column(type="string", length=150, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $code;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     * 
     * @Annotation\Required(false)
     */
    protected $class;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $category;

    /**
     * @ORM\Column(type="smallint", length=4, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $level;

    /**
     * @ORM\Column(type="smallint", length=6, nullable=false, options={"default"=0})
     * 
     * @Annotation\Required(true)
     */
    protected $serial = 0;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false);
     * @Annotation\Options({"label":"Status:"})
     * 
     * @Annotation\Required(true)
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="AccountType", inversedBy="Accounts")
     * @ORM\JoinColumn(name="account_type_id", referencedColumnName="id", nullable=true)
     * 
     */
    protected $AccountType;

    /**
     * @ORM\ManyToOne(targetEntity="\Administration\Entity\Branch", inversedBy="Accounts")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", nullable=true)
     */
    protected $Branch;

    /**
     * @ORM\ManyToOne(targetEntity="\Administration\Entity\Company", inversedBy="Accounts")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     */
    protected $Company;

    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\OpeningBalance", mappedBy="Account", orphanRemoval=true)
     * 
     * @Annotation\Required(false)
     */
    protected $OpeningBalances;
//    /**
//     * @ORM\OneToMany(targetEntity="Account\Entity\FinancialYear", mappedBy="Account", orphanRemoval=true)
//     * 
//     * @Annotation\Required(false)
//     */
//    protected $FinancialYears;

    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\Account", mappedBy="ParentAccount", orphanRemoval=true)
     * 
     * @Annotation\Required(false)
     */
    protected $ChildAccounts;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="ChildAccounts")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $ParentAccount;

    /**
     * @ORM\OneToMany(targetEntity="VoucherDetail", mappedBy="Account")
     */
    protected $VoucherDetails;

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
     * @ORM\ManyToOne(targetEntity="Administration\Entity\User", inversedBy="AccountsCreatedBy")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $Creator;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\User", inversedBy="AccountsModifiedBy")
     * @ORM\JoinColumn(name="modified_by", referencedColumnName="id", nullable=true)
     */
    protected $Modifier;

    public function __construct($SL=null)
    {
        //*****
        $now = new \DateTime("now");
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
        //*****
        $this->ChildAccounts = new ArrayCollection();
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    public function getCompany()
    {
        return $this->Company;
    }

    public function setCompany(\Administration\Entity\Company $Company)
    {
        $this->Company = $Company;
    }

    public function getVoucherDetails()
    {
        return $this->VoucherDetails;
    }

    public function setVoucherDetails(VoucherDetail $VoucherDetails)
    {
        $this->VoucherDetails = $VoucherDetails;
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

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getAccountType()
    {
        return $this->AccountType;
    }

    public function setAccountType(AccountType $AccountType)
    {
        $this->AccountType = $AccountType;
    }

    public function getBranch()
    {
        return $this->Branch;
    }

    public function setBranch(\Administration\Entity\Branch $Branch)
    {
        $this->Branch = $Branch;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getAccountTitle()
    {
        return $this->account_title;
    }

    public function setAccountTitle($account_title)
    {
        $this->account_title = $account_title;
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
    }

    public function getModificationDate()
    {
        return $this->modification_date;
    }

    public function setModificationDate($modification_date)
    {
        $this->modification_date = $modification_date;
    }

    public function getParentAccount()
    {
        return $this->ParentAccount;
    }

    public function setParentAccount(Account $ParentAccount)
    {
        $this->ParentAccount = $ParentAccount;
    }

    public function getChildAccounts()
    {
        return $this->ChildAccounts;
    }

    public function setChildAccounts(Account $ChildAccounts)
    {
        $this->ChildAccounts = $ChildAccounts;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getOpeningBalances()
    {
        return $this->OpeningBalances;
    }

    public function setOpeningBalances($OpeningBalances)
    {
        $this->OpeningBalances = $OpeningBalances;
    }

    public function getCreation_date()
    {
        return $this->creation_date;
    }

    public function setCreation_date($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function getModification_date()
    {
        return $this->modification_date;
    }

    public function setModification_date($modification_date)
    {
        $this->modification_date = $modification_date;
    }

    public function getCreator()
    {
        return $this->Creator;
    }

    public function setCreator(\Administration\Entity\User $Creator)
    {
        $this->Creator = $Creator;
    }

    public function getModifier()
    {
        return $this->Modifier;
    }

    public function setModifier(\Administration\Entity\User $Modifier)
    {
        $this->Modifier = $Modifier;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->code = isset($data['code']) ? $data['code'] : null;
        $this->level = isset($data['level']) ? $data['level'] : 0;
        $this->serial = isset($data['serial']) ? $data['serial'] : 0;
        $this->status = isset($data['status']) ? $data['status'] : null;
        $this->class = isset($data['class']) ? $data['class'] : null;
        $this->category = isset($data['category']) ? $data['category'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
