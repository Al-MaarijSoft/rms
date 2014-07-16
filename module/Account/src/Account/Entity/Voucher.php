<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use \DateTime;

//Zend\Form\Annotation;

/**
 * A Main Voucher entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="vouchers")
 * 
 * @property int $id
 * @property date $voucher_date
 * @property string $voucher_number
 * @property int $exchange_rate
 * @property date $checque_date
 * @property string $cheque_number
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * @Annotation\Name("Voucher")
 */
class Voucher
{

    const SERIAL_DEFAULT = 1;
    const LOCAL_RATE = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer",length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Annotation\Required(true)
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=10)
     * 
     * @Annotation\Required(true)
     */
    protected $voucher_number;

    /**
     * @ORM\Column(type="integer",length=10 )
     * 
     * @Annotation\Required(true)
     */
    protected $serial = Voucher::SERIAL_DEFAULT;

    /**
     * @ORM\Column(type="float",length=10,scale=2)
     * 
     * @Annotation\Required(true)
     */
    protected $exchange_rate = Voucher::LOCAL_RATE;

    /**
     * @ORM\Column(type="date")
     * 
     * @Annotation\Required(true)
     */
    protected $voucher_date;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $cheque_number;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     * @Annotation\Required(false)
     */
    protected $cheque_date;

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
     * @ORM\ManyToOne(targetEntity="VoucherType", inversedBy="Vouchers")
     * @ORM\JoinColumn(name="voucher_type_id", referencedColumnName="id")
     * 
     */
    protected $VoucherType;

    /**
     * @ORM\OneToMany(targetEntity="VoucherDetail", mappedBy="Voucher", orphanRemoval=true, cascade={"persist"})
     */
    protected $VoucherDetails;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Currency", inversedBy="Vouchers")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     */
    protected $Currency;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\User", inversedBy="VouchersCreatedBy")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $Creator;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\User", inversedBy="VouchersModifiedBy")
     * @ORM\JoinColumn(name="modified_by", referencedColumnName="id", nullable=true)
     */
    protected $Modifier;

    /*
     * Constructor
     */

    public function __construct($SL = null)
    {
//        $now = new \DateTime("now");
        $now = \Application\Library\Application::makeDateTimeObjectForDB("now");
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
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getVoucherNumber()
    {
        return $this->voucher_number;
    }

    public function setVoucherNumber($voucher_number)
    {
        $this->voucher_number = $voucher_number;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function setSerial($serial = Voucher::SERIAL_DEFAULT)
    {
        $this->serial = $serial;
    }

    public function getExchangeRate()
    {
        return $this->exchange_rate;
    }

    public function setExchangeRate($exchange_rate)
    {
        $this->exchange_rate = $exchange_rate;
    }

    public function getVoucherDate()
    {
        return $this->voucher_date;
    }

    public function setVoucherDate($voucher_date)
    {
        $this->voucher_date = \Application\Library\Application::makeDateObjectForDB($voucher_date);
    }

    public function getChequeNumber()
    {
        return $this->cheque_number;
    }

    public function setChequeNumber($cheque_number)
    {
        $this->cheque_number = $cheque_number;
    }

    public function getChequeDate()
    {
        return $this->cheque_date;
    }

    public function setChequeDate($cheque_date)
    {
        $this->cheque_date = \Application\Library\Application::makeDateObjectForDB($cheque_date);
    }

    public function getVoucherType()
    {
        return $this->VoucherType;
    }

    public function setVoucherType(VoucherType $VoucherType)
    {
        $this->VoucherType = $VoucherType;
    }

    public function getVoucherDetails()
    {
        return $this->VoucherDetails;
    }

    public function setVoucherDetails($VoucherDetails)
    {
        $this->VoucherDetails = $VoucherDetails;
    }

    public function getCurrency()
    {
        return $this->Currency;
    }

    public function setCurrency(\Administration\Entity\Currency $Currency)
    {
        $this->Currency = $Currency;
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
        $this->modification_date = \Application\Library\Application::makeDateTimeObjectForDB($modification_date);
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
//        $this->voucher_date = isset($data['voucher_date']) ? $data['voucher_date'] : null;
        $this->voucher_number = isset($data['voucher_number']) ? $data['voucher_number'] : null;
        $this->serial = isset($data['serial']) ? $data['serial'] : Voucher::SERIAL_DEFAULT;
        $this->exchange_rate = isset($data['exchange_rate']) ? $data['exchange_rate'] : null;
        $this->cheque_number = isset($data['cheque_number']) ? $data['cheque_number'] : null;
//        $this->cheque_date = isset($data['cheque_date']) ? $data['cheque_date'] : null;
    }

}

?>
