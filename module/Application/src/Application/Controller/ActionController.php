<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/20/14
 * Time: 8:46 PM
 */

namespace Application\Controller;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class ActionController extends AbstractActionController{
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @return EntityManager
     */
    public function getEntityManager(){
        if (! $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }

        return $this->em;

    }


} 