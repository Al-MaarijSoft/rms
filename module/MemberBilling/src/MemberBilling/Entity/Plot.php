<?php

namespace MemberBilling\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * An Plot entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="plots")
 * 
 * @property integer $id
 * @property integer $plot_no
 * @property integer $kanal
 * @property integer $marla
 * @property string $square_feet
 * @property string $is_commercial
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 */
class Plot
{

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
    protected $plot_no;

    /**
     * @ORM\Column(type="smallint", length=5, nullable=true)
     * 
     */
    protected $kanal;

    /**
     * @ORM\Column(type="smallint", length=5, nullable=true)
     * 
     */
    protected $marla;

    /**
     * @ORM\Column(type="decimal",precision=8,scale=2, nullable=true)
     * 
     */
    protected $square_feet;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=true)
     * 
     */
    protected $is_commercial;

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
     * @ORM\OneToMany(targetEntity="Member", mappedBy="Plot", orphanRemoval=true, cascade={"persist"})
     * 
     */
    protected $Members;

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

    public function getPlotNo()
    {
        return $this->plot_no;
    }

    public function setPlotNo($plot_no)
    {
        $this->plot_no = $plot_no;
    }

    public function getKanal()
    {
        return $this->kanal;
    }

    public function setKanal($kanal)
    {
        $this->kanal = $kanal;
    }

    public function getMarla()
    {
        return $this->marla;
    }

    public function setMarla($marla)
    {
        $this->marla = $marla;
    }

    public function getSquareFeet()
    {
        return $this->square_feet;
    }

    public function setSquareFeet($square_feet)
    {
        $this->square_feet = $square_feet;
    }

    public function getIsCommercial()
    {
        return $this->is_commercial;
    }

    public function setIsCommercial($is_commercial)
    {
        $this->is_commercial = $is_commercial;
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
        $this->plot_no = isset($data['plot_no']) ? $data['plot_no'] : null;
        $this->kanal = isset($data['kanal']) ? $data['kanal'] : 0;
        $this->marla = isset($data['marla']) ? $data['marla'] : 0;
        $this->square_feet = isset($data['square_feet']) ? $data['square_feet'] : 0;
        $this->is_commercial = isset($data['is_commercial']) ? $data['is_commercial'] : 0;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
