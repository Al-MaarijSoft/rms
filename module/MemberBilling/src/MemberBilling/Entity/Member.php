<?php

namespace MemberBilling\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * An Member entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="members")
 * 
 * @property integer $id
 * @property integer $old_member_id
 * @property string $code
 * @property string $name
 * @property string $image_name
 * @property string $nic
 * @property string $fname
 * @property string $sname
 * @property string $address
 * @property smallint $status
 * @property smallint $is_private
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 */
class Member
{

    const ACTIVE = 1;
    const INACTIVE = 0;
    const YES = 1;
    const NO = 0;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=50, nullable=false)
     * 
     */
    protected $old_member_id;

    /**
     * @ORM\Column(type="string", length=60, unique=true, nullable=false)
     * 
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     * 
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=60, unique=true, nullable=false)
     * 
     */
    protected $image_name;

    /**
     * @ORM\Column(type="string", length=15, unique=true, nullable=false)
     * 
     */
    protected $nic;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * 
     */
    protected $fname;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * 
     */
    protected $sname;

    /**
     * @ORM\Column(type="string", length=150, unique=true, nullable=true)
     * 
     */
    protected $address;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 1});
     * 
     */
    protected $status;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 1});
     * 
     */
    protected $is_private;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    protected $creation_date;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    protected $modification_date;

    /**
     * @ORM\ManyToOne(targetEntity="Plot", inversedBy="Members")
     * @ORM\JoinColumn(name="plot_id", referencedColumnName="id", nullable=false)
     * 
     */
    protected  $Plot;


    /*
     * Constructor
     */

    public function __construct()
    {
        //*****
        $now = new \DateTime("now");
        if ($this->id == null)
            $this->creation_date = $now;
        else
            $this->modification_date = $now;
        //*****
//        $this->Branches = new ArrayCollection();
//        $this->Accounts = new ArrayCollection();
//        $this->BioInfos = new ArrayCollection();
//        $this->Users = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOldMemberId()
    {
        return $this->old_member_id;
    }

    public function setOldMemberId($old_member_id)
    {
        $this->old_member_id = $old_member_id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getImageName()
    {
        return $this->image_name;
    }

    public function setImageName($image_name)
    {
        $this->image_name = $image_name;
    }

    public function getNic()
    {
        return $this->nic;
    }

    public function setNic($nic)
    {
        $this->nic = $nic;
    }

    public function getFname()
    {
        return $this->fname;
    }

    public function setFname($fname)
    {
        $this->fname = $fname;
    }

    public function getSname()
    {
        return $this->sname;
    }

    public function setSname($sname)
    {
        $this->sname = $sname;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getIsPrivate()
    {
        return $this->is_private;
    }

    public function setIsPrivate($is_private)
    {
        $this->is_private = $is_private;
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

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->status = isset($data['status']) ? $data['status'] : self::ACTIVE;
        $plot_id = isset($data['plot_id']) ? $data['plot_id'] : null;
        if ($plot_id )
        {
            $EM= new \Doctrine\ORM\EntityManager();
            
            $EM->getRepository($entityName);
        }
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
