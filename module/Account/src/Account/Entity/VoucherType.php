<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM;

//Zend\Form\Annotation;

/**
 * A Main Voucher entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="voucher_types")
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property integer $behaviour
 * 
 */
class VoucherType
{

    const PAYMENT = 1;
    const RECEIPT = 2;
    const JOURNAL = 3;
    const TRANSFER = 4;

    //*********** Voucher Default Types and their default ids
    const CASH_PAYMENT_VOUCHER = 1;
    const CASH_RECEIPT_VOUCHER = 2;
    const BANK_PAYMENT_VOUCHER = 3;
    const BANK_RECEIPT_VOUCHER = 4;
    const JOURNAL_VOUCHER = 5;
    const BANK_TO_BANK_TRANSFER = 6;
    const CASH_TO_CASH_TRANSFER = 7;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer",length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=50)
     * 
     */
    protected $name;

    /**
     * @ORM\Column(type="string" ,length=3)
     * 
     */
    protected $code;

    /**
     * @ORM\Column(type="smallint",length=1)
     * 
     */
    protected $behaviour;

    /**
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="VoucherType", orphanRemoval=true)
     * 
     */
    protected $Vouchers;

    /**
     * @ORM\ManyToOne(targetEntity="AccountType", inversedBy="VoucherTypes")
     * @ORM\JoinColumn(name="account_type_id", referencedColumnName="id")
     * 
     */
    protected $AccountType;

    public function getAccountType()
    {
        return $this->AccountType;
    }

    public function setAccountType(AccountType $AccountType)
    {
        $this->AccountType = $AccountType;
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getBehaviour()
    {
        return $this->behaviour;
    }

    public function setBehaviour($behaviour)
    {
        $this->behaviour = $behaviour;
    }

    public function getVouchers()
    {
        return $this->vouchers;
    }

    public function setVouchers(Voucher $vouchers)
    {
        $this->vouchers = $vouchers;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->code = isset($data['code']) ? $data['code'] : null;
        $this->behaviour = isset($data['behaviour']) ? $data['behaviour'] : null;
    }

}
