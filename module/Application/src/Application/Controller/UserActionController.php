<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/23/14
 * Time: 4:13 AM
 */

namespace Application\Controller;


use Zend\Authentication\Storage\Session;

class UserActionController extends ActionController{
    protected $authservice;
    protected $storage;
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }
    /** @return Session */
    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('AuthStorage');
        }

        return $this->storage;
    }

} 