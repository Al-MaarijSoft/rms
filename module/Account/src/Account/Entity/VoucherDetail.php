<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

//Zend\Form\Annotation;

/**
 * A Main Voucher entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="voucher_details")
 * 
 * @property int $id
 * @property string $narration
 * @property decimal $debit
 * @property decimal $credit
 * @property smallint $status
 * 
 * @Annotation\Name("VoucherDetail")
 */
class VoucherDetail
{

    const OPEN = 1;
    const CLOSED = 0;
    const DEBIT_DEFAULT = 0;
    const CREDIT_DEFAULT = 0;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Annotation\Required(false)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=1000)
     * 
     * @Annotation\Required(false)
     */
    protected $narration;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=2)
     * 
     * @Annotation\Required(false)
     */
    protected $debit;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=2)
     * 
     * @Annotation\Required(false)
     */
    protected $credit;

    /**
     * @ORM\Column(type="smallint", length=1)
     * 
     * @Annotation\Required(false)
     */
    protected $status = VoucherDetail::OPEN;

    /**
     * @ORM\ManyToOne(targetEntity="Voucher", inversedBy="VoucherDetails")
     * @ORM\JoinColumn(name="voucher_id", referencedColumnName="id", nullable=false)
     */
    protected $Voucher;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="VoucherDetails")
     */
    protected $Account;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNarration()
    {
        return $this->narration;
    }

    public function setNarration($narration)
    {
        $this->narration = $narration;
    }

    public function getDebit()
    {
        return $this->debit;
    }

    public function setDebit($debit)
    {
        $this->debit = $debit;
    }

    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getVoucher()
    {
        return $this->Voucher;
    }

    public function setVoucher(Voucher $Voucher)
    {
        $this->Voucher = $Voucher;
    }

    public function getAccount()
    {
        return $this->Account;
    }

    public function setAccount(Account $Account)
    {
        $this->Account = $Account;
    }

    /*
     * Constructor
     */

    public function __construct()
    {
        $now = new \DateTime("now");
    }

    public function exchangeArray($data)
    {

        $this->id = isset($data['id']) ? $data['id'] : $this->id;
        $this->narration = isset($data['narration']) ? $data['narration'] : null;
        $this->status = isset($data['status']) ? $data['status'] : VoucherDetail::OPEN;
        $this->debit = isset($data['debit']) ? $data['debit'] : VoucherDetail::DEBIT_DEFAULT;
        $this->credit = isset($data['credit']) ? $data['credit'] : VoucherDetail::CREDIT_DEFAULT;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
