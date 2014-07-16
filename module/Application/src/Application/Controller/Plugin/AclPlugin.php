<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource,
    Application\Model\AuthStorage;

class AclPlugin extends AbstractPlugin
{

    protected $sesscontainer;

    private function getSessContainer($sm)
    {
        if (!$this->sesscontainer)
        {
            $this->sesscontainer = new AuthStorage();
        }
        if ($this->sesscontainer->isEmpty())
        {
            $sessionReturn = (object) array('role' => 'anonymous');
        }
        else
        {
            $sessionReturn = $this->sesscontainer->read();
            $userModel = $sm->get('User');
            $user = $userModel->getByEmail($sessionReturn->email);
            if (!$user)
            {
                $this->sesscontainer->forgetMe();
                $this->sesscontainer->clear();
                $sessionReturn->role = 'anonymous';
            }
            else
            {
                $this->sesscontainer->write((object) $user->getSessionRepresentation());
                $sessionReturn = $this->sesscontainer->read();
            }
        }
        return $sessionReturn;
    }

    public function doAuthorization($e)
    {
        //setting ACL...
        $acl = new Acl();
        //add role ..
        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('normal'));
        $acl->addRole(new Role('admin'), 'normal');

        $acl->addResource(new Resource('Application'));
        $acl->addResource(new Resource('User'));
        $acl->addResource(new Resource('Session'));
        $acl->addResource(new Resource('Password'));
        $acl->addResource(new Resource('Admin'));
        $acl->addResource(new Resource('Category'));
        //$acl->addResource(new Resource('Credit'));
        //$acl->addResource(new Resource('Category'));
        $acl->addResource(new Resource('Registration'));

        $acl->allow('normal', 'User', 'view');
        $acl->allow('normal', 'Application', 'view');
        $acl->allow('admin', 'Application', 'view');
        $acl->allow('normal', 'Category', 'view');
        $acl->allow('normal', 'Application', 'view');
        $acl->deny('admin', 'User', 'view');

        $acl->allow('anonymous', 'Application', 'view');
        $acl->allow('anonymous', 'Session', 'view');
        $acl->allow('anonymous', 'Registration', 'view');
        $acl->allow('anonymous', 'Password', 'view');

        //$acl->deny('staff', array('Application'), array('view'));
        //$acl->allow('normal', array('Software'), array('view'));
        $acl->allow('admin', array('Admin'), array('view'));
        // allowable actions:  publish, edit, and view

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $role = (!$this->getSessContainer($sm)->role ) ? 'anonymous' : $this->getSessContainer($sm)->role;
        if (!$acl->isAllowed($role, $namespace, 'view'))
        {
            $router = $e->getRouter();
            if ($role == 'admin')
            {
                $url = $router->assemble(array(), array('name' => 'admin_user'));
            }
            else
            {
                if ($namespace == 'Admin')
                {
                    $url = $router->assemble(array(), array('name' => 'session_login'));
                }
                else
                {
                    if ($role == 'normal')
                    {
                        $url = $router->assemble(array(), array('name' => 'user'));
                    }
                    else
                    {
                        $url = $router->assemble(array(), array('name' => 'home'));
                    }
                }
            }

            $response = $e->getResponse();
            $response->setStatusCode(302);
            //redirect to login route...
            $response->getHeaders()->addHeaderLine('Location', $url);
        }
        else
        {
            $controller->layout()->session = $controller->session = $this->getSessContainer($sm);
            if (is_readable(__DIR__ . '/view/layout/layout.phtml'))
            {
                $controller->layout(strtolower($namespace));
            }
        }
    }

}
