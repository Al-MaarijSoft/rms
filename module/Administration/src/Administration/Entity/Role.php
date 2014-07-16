<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * An Role entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="roles", uniqueConstraints={@ORM\UniqueConstraint(name="name_company_index", columns={"name", "company_id"})})
 * 
 * @property int $id
 * @property string $name
 * @property datetime $creation_date
 * @property datetime $modification_date
 * 
 * @Annotation\Name("Role")
 * 
 */
class Role
{

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
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="Roles")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $Company;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="Role", orphanRemoval=true, cascade={"persist"})
     * @Annotation\Required(true)
     */
    protected $Users;

    /**
     * @ORM\OneToMany(targetEntity="ResourceToRole", mappedBy="Role", orphanRemoval=true, cascade={"persist"})
     * @Annotation\Required(true)
     */
    protected $ResourcesToRoles;

    /**
     * @ORM\OneToMany(targetEntity="RoleParent", mappedBy="RoleChild", orphanRemoval=true, cascade={"persist"})
     * 
     * @Annotation\Required(true)
     */
    protected $RoleChildren;

    /**
     * @ORM\OneToMany(targetEntity="RoleParent", mappedBy="RoleParent", orphanRemoval=true, cascade={"persist"})
     * 
     * @Annotation\Required(true)
     */
    protected $RoleParents;

    public function __construct()
    {
        
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

    public function getCompany()
    {
        return $this->Company;
    }

    public function setCompany(Company $Company)
    {
        $this->Company = $Company;
    }

    public function getUsers()
    {
        return $this->Users;
    }

    public function setUsers(User $Users)
    {
        $this->Users = $Users;
    }

    public function getResourcesToRoles()
    {
        return $this->ResourcesToRoles;
    }

    public function setResourcesToRoles(ResourceToRole $ResourcesToRoles)
    {
        $this->ResourcesToRoles = $ResourcesToRoles;
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

    public function getRoleChildren()
    {
        return $this->RoleChildren;
    }

    public function setRoleChildren(RoleParent $RoleChildren)
    {
        $this->RoleChildren = $RoleChildren;
    }

    public function getRoleParents()
    {
        return $this->RoleParents;
    }

    public function setRoleParents(RoleParent $RoleParents)
    {
        $this->RoleParents = $RoleParents;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
