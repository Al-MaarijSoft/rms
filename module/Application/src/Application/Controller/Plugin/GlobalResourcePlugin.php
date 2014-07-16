<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GlobalResourcePlugin extends AbstractPlugin
{

    public function doSomething()
    {
        die('KKKK');
    }

    public function isUniqueObject(array $params)
    {
        $editId = $params['editId'];
        $fields = $params['fields'];
        $thisContext = $params['context'];
        $errorArrayKey = $params['errorArrayKey'];
        $fieldLabel = $params['fieldLabel'];
        $Repository = $params['Repository'];
        $Obj = $Repository->findBy($fields);
        if (null === $editId)
        {
            if (count($Obj))
            {
                $thisContext->errorMsgs[$errorArrayKey] = $fieldLabel . ' already exists';
                return false;
            }
            else
                return true;
        }
        else //For Edit
        {
            $valBeforePost = @$params['valBeforePost']; //after post val of the form
            $valAfterPost = @$params['valAfterPost']; //before post val mean that old value
            if (count($Obj) && $valAfterPost !== $valBeforePost)
            {
                $thisContext->errorMsgs[$errorArrayKey] = $fieldLabel . ' already exists';
                return false;
            }
            else
                return true;
        }
    }

}