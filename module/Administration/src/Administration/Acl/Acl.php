<?php

/**
 * File for Acl Class
 *
 * @category  Administration
 * @package   Administration
 * @author    Marco Neumann <webcoder_at_binware_dot_org>
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */
//*************************************

/**
 * @namespace
 * 
 */

namespace Administration\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Class to handle Acl
 * 
 * This class is for loading ACL defined in a "module.acl.roles.php"
 */
class Acl extends ZendAcl
{
    /**
     * Default Role
     */

    const DEFAULT_ROLE = 'Guest';

//    const DEFAULT_ROLE = 'Member';
//    const DEFAULT_ROLE = 'Assistant Accounts';
//    const DEFAULT_ROLE = 'Membership Operator';
//    const DEFAULT_ROLE = 'Accounts Manager';
//    const DEFAULT_ROLE = 'Membership Manager';
//    const DEFAULT_ROLE = 'Administrator';
//    const DEFAULT_ROLE = 'SuperAdministrator';

    /**
     * Constructor
     *
     * @param array $config
     * @return void
     * @throws \Exception
     */
    public function __construct($config)
    {
        if (!isset($config['acl']['roles']) || !isset($config['acl']['resources']))
        {
            throw new \Exception('Invalid ACL Config found');
        }

        $roles = $config['acl']['roles'];
        if (!isset($roles[self::DEFAULT_ROLE]))
        {
            $roles[self::DEFAULT_ROLE] = '';
        }

        $this->_addRoles($roles)
                ->_addResources($config['acl']['resources']);
    }

    /**
     * Adds Roles to ACL
     *
     * @param array $roles
     * @return User\Acl
     */
    protected function _addRoles($roles)
    {
        foreach ($roles as $name => $parent)
        {
            if (!$this->hasRole($name))
            {
                if (empty($parent))
                {
                    $parent = array();
                }
                else
                {
                    $parent = explode(',', $parent);
                }

                $this->addRole(new Role($name), $parent);
            }
        }

        return $this;
    }

    /**
     * Adds Resources to ACL
     *
     * @param $resources
     * @return User\Acl
     * @throws \Exception
     */
    protected function _addResources($resources)
    {
        foreach ($resources as $permission => $controllers)
        {
            foreach ($controllers as $controller => $actions)
            {
                if ($controller == 'all')
                {
                    $controller = null;
                }
                else
                {
                    if (!$this->hasResource($controller))
                    {
                        $this->addResource(new Resource($controller));
                    }
                }

                foreach ($actions as $action => $role)
                {
                    if ($action == 'all')
                    {
                        $action = null;
                    }

                    if ($permission == 'allow')
                    {
                        $this->allow($role, $controller, $action);
                    }
                    elseif ($permission == 'deny')
                    {
                        $this->deny($role, $controller, $action);
                    }
                    else
                    {
                        throw new \Exception('No valid permission defined: ' . $permission);
                    }
                }
            }
        }

        return $this;
    }
}
