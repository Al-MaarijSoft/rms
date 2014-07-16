<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * An Currency entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="currencies")
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property smallint $is_local
 * 
 * @Annotation\Name("Currency")
 * 
 */
class Currency
{

    const YES = 1;
    const NO = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", length=11)
     */
    protected $id;

    /** @ORM\Column(type="string",length=50, unique=true, nullable=false) 
     */
    protected $name;

    /** @ORM\Column(type="string", length=3, unique=true, nullable=false)
     */
    protected $code;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $is_local = self::NO;

    /**
     * @ORM\OneToMany(targetEntity="Account\Entity\Voucher", mappedBy="Currency")
     */
    protected $Vouchers;

/////////////////////////////////////////////////////////// Getters And Setters
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

    public function getIsLocal()
    {
        return $this->is_local;
    }

    public function setIsLocal($is_local)
    {
        $this->is_local = $is_local;
        return $this;
    }

    public function getVouchers()
    {
        return $this->Vouchers;
    }

    public function setVouchers(\Account\Entity\Voucher $Vouchers)
    {
        $this->Vouchers = $Vouchers;
        return $this;
    }

}

?>
