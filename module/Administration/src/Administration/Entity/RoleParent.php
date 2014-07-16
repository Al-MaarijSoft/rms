<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * An RoleParent entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="role_parents", uniqueConstraints={@ORM\UniqueConstraint(name="role_parent_index", columns={"id","role_id", "parent_id"})})
 * 
 * @property int $id
 * @property string $name
 * @property datetime $creation_date
 * @Annotation\Name("RoleParent")
 * 
 */
class RoleParent
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
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="RoleChildren")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * 
     * @Annotation\Required(true)
     */
    protected $RoleChild;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="RoleParents")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $RoleParent;

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

    public function getRoleChild()
    {
        return $this->RoleChild;
    }

    public function setRoleChild(Role $RoleChild)
    {
        $this->RoleChild = $RoleChild;
    }

    public function getRoleParent()
    {
        return $this->RoleParent;
    }

    public function setRoleParent(Role $RoleParent)
    {
        $this->RoleParent = $RoleParent;
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
