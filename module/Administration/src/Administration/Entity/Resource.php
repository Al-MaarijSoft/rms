<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * An Branch entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="resources")
 * 
 * @property int $id
 * @property string $controller
 * @property string $action
 * 
 * @Annotation\Name("Resource")
 * 
 */
class Resource
{

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
     * @ORM\Column(type="string", length=50, nullable=true)
     * 
     * @Annotation\Required(true)
     */
    protected $code;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     * 
     * @Annotation\Required(true)
     */
    protected $level;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     * 
     * @Annotation\Required(true)
     */
    protected $serial;

    /**
     * @ORM\OneToMany(targetEntity="ResourceToRole", mappedBy="Resource")
     * @Annotation\Required(true)
     */
    protected $ResourcesToRoles;

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="Parent", orphanRemoval=true)
     * 
     * @Annotation\Required(true)
     */
    protected $Children;

    /**
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="Children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * 
     * 
     * @Annotation\Required(true)
     */
    protected $Parent;

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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getChildren()
    {
        return $this->Children;
    }

    public function setChildren($Children)
    {
        $this->Children = $Children;
    }

    public function getParent()
    {
        return $this->Parent;
    }

    public function setParent($Parent)
    {
        $this->Parent = $Parent;
    }

    public function getResourceToRole()
    {
        return $this->ResourcesToRoles;
    }

    public function setResourceToRole(ResourceToRole $ResourceToRole)
    {
        $this->ResourcesToRoles = $ResourceToRole;
    }

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->code = isset($data['code']) ? $data['code'] : null;
        $this->level = isset($data['level']) ? $data['level'] : null;
        $this->serial = isset($data['serial']) ? $data['serial'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}

?>
