<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 1:57 PM
 */

namespace Catalog\Controller;


use Application\Controller\ActionController;

class InformationController extends ActionController{
    public function indexAction(){
        $entity_id = (int) $this->params()->fromRoute('information_id', 0);
        $em = $this->getEntityManager();
        //first verify the category id is correct
        $entity = $em->getRepository('Catalog\Entity\InformationPage')->find($entity_id);
        if(!$entity || $entity->getEnabled() == false){ // category do not exist
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return array(
          "entity" => $entity
        );
    }
} 