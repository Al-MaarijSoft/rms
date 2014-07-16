<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation,
    \Doctrine\Common\Collections\ArrayCollection;

/**
 * An Account entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="opening_balances")
 * 
 * @property int $id
 * @property decimal $debit
 * @property decimal $credit
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * @Annotation\Name("FinancialYear")

 */
class OpeningBalance
{

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\ManyToOne(targetEntity="Account\Entity\Account", inversedBy="OpeningBalances")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=true)
     */
    protected $Account;

    /**
     * @ORM\ManyToOne(targetEntity="Account\Entity\FinancialYear", inversedBy="OpeningBalances")
     * @ORM\JoinColumn(name="financial_year_id", referencedColumnName="id", nullable=true)
     */
    protected $FinancialYear;

    public function __construct()
    {
        //*****
        $now = new \DateTime("now");
        if ($this->id == null)
            $this->creation_date = $now;
        else
            $this->modification_date = $now;
        //*****
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getAccount()
    {
        return $this->Account;
    }

    public function setAccount(Account $Account)
    {
        $this->Account = $Account;
    }

    public function getFinancialYear()
    {
        return $this->FinancialYear;
    }

    public function setFinancialYear(FinancialYear $FinancialYear)
    {
        $this->FinancialYear = $FinancialYear;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->debit = isset($data['debit']) ? $data['debit'] : null;
        $this->credit = isset($data['credit']) ? $data['credit'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
