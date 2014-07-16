<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * An ResourceToRole entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="resources_to_roles", uniqueConstraints={@ORM\UniqueConstraint(name="resource_role_index", columns={"role_id", "resource_id"})})
 * 
 * @property int $id
 * @property string $controller
 * @property string $action
 * 
 * @Annotation\Name("ResourceToRole")
 * 
 */
class ResourceToRole
{

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer",length=11);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint",length=1);
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="ResourcesToRoles")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id", nullable=false)
     * @Annotation\Required(true)
     */
    protected $Resource;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="ResourcesToRoles")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * @Annotation\Required(true)
     */
    protected $Role;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getResource()
    {
        return $this->Resource;
    }

    public function setResource(Resource $Resource)
    {
        $this->Resource = $Resource;
    }

    public function getRole()
    {
        return $this->Role;
    }

    public function setRole(Role $Role)
    {
        $this->Role = $Role;
    }

    public function getIsAllowed()
    {
        return $this->status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function __construct()
    {
        
    }

    public function exchangeArray($data)
    {
        print_r($data);
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->status = isset($data['status']) ? $data['status'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
