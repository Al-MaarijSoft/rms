<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation,
    Doctrine\Common\Collections\ArrayCollection;
/**
 * An Branch entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="branches", uniqueConstraints={@ORM\UniqueConstraint(name="name_company_index", columns={"name", "company_id"})})
 * 
 * @property int $id
 * @property string $name
 * @property smallint $branch_type
 * @property smallint $status
 * @property string $description
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * @Annotation\Name("Branch")
 * 
 */
class Branch
{

    const MAIN = 1; //Head Office or main branch
    const FRANCHISE = 2; // Normal Branch which is not Main nor Franchise
    const SIMPLE = 3; // Normal Branch which is not Main nor Franchise
    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer",length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $name;

    /**
     * @ORM\Column(type="smallint", length=1, options={"default" = 3}, nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $branch_type = Branch::SIMPLE;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 1});
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
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="Branches")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $Company;

    /**
     * @ORM\OneToMany(targetEntity="\Account\Entity\Account", mappedBy="Branch", orphanRemoval=true)
     * 
     * @Annotation\Required(false)
     */
    protected $Accounts;

    /**
     * @ORM\OneToMany(targetEntity="BioInfo", mappedBy="Branch", orphanRemoval=true)
     * 
     * @Annotation\Required(false)
     */
    protected $BioInfos;

    public function __construct()
    {
        $this->BioInfos =  new ArrayCollection();
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

    public function getBranchType()
    {
        return $this->branch_type;
    }

    public function setBranchType(\Zend\XmlRpc\Value\Integer $branch_type)
    {
        $this->branch_type = $branch_type;
    }

    public function getCompany()
    {
        return $this->Company;
    }

    public function setCompany(Company $Company)
    {
        $this->Company = $Company;
    }

    public function getAccounts()
    {
        return $this->Accounts;
    }

    public function setAccounts(\Account\Entity\Account $Accounts)
    {
        $this->Accounts = $Accounts;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getBioInfos()
    {
        return $this->BioInfos;
    }

    public function setBioInfos(BioInfo $BioInfos)
    {
        $this->BioInfos = $BioInfos;
        return $this;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->status = isset($data['status']) ? $data['status'] : Branch::ACTIVE;
        $this->branch_type = isset($data['branch_type']) ? $data['branch_type'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
