<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * An Account-Type entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="account_types")
 * 
 * @property int $id
 * @property string $name
 * 
 * @Annotation\Name("AccountType")
 */
class AccountType
{

    const CASH = 1;
    const BANK = 2;
    const OTHER = 3;
    const YES = 1;
    const NO = 0;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * 
     * @Annotation\Required(true)
     */
    protected $name;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false, options={"default" = 0})
     * 
     * @Annotation\Required(true)
     */
    protected $is_default;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="AccountType", orphanRemoval=true)
     * 
     * @Annotation\Required(false)
     */
    protected $Accounts;

    /**
     * @ORM\OneToMany(targetEntity="VoucherType", mappedBy="AccountType", orphanRemoval=true)
     * 
     * @Annotation\Required(true)
     */
    protected $VoucherTypes;

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

    public function getAccounts()
    {
        return $this->Accounts;
    }

    public function setAccounts($Accounts)
    {
        $this->Accounts = $Accounts;
    }

}

