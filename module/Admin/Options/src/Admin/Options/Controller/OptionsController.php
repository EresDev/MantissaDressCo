<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 10/10/14
 * Time: 12:14 PM
 */

namespace Admin\Options\Controller;


use Application\Controller\ActionController;
use Catalog\Entity\Options;
use Doctrine\DBAL\Schema\View;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\View\Model\ViewModel;

class OptionsController extends ActionController{
    public function indexAction(){

        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\Options');
        $queryBuilder = $repository->createQueryBuilder('options')
            ->groupBy('options.name')
            ->orderBy('options.optionsId', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);
        $view = new ViewModel(
            array(
                'paginator' => $paginator,

            )
        );
        return $view;
    }

    public function addAction(){
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $name = addslashes(trim(rtrim($post['name'])));
            $already = $this->getEntityManager()->getRepository('Catalog\Entity\Options')->findOneBy(array('name' => $name));
            if($already){
                $this->flashMessenger()->addErrorMessage("The given option group name '$name' is already in use. Try again.");
            }
            else{
                foreach($post['option_name'] as $option){
                    $entity = new Options();
                    $entity->setName(addslashes(trim(rtrim($post['name']))));
                    $entity->setOptionName(addslashes(trim(rtrim($option))));
                    $this->getEntityManager()->persist($entity);
                    $this->getEntityManager()->flush();
                }
                $this->flashMessenger()->addSuccessMessage("Options group added successfully.");
                return $this->redirect()->toRoute('admin-options');
            }
        }
        $view = new ViewModel();
        $view->setTemplate('admin/options/add');
        return $view;
    }

    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-options', array(
                'action' => 'add'
            ));
        }
        $em = $this->getEntityManager();
        $entity = $em->getRepository('Catalog\Entity\Options')->find($id);
        if(!$entity){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $name = $entity->getName();
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $post['name'] = trim(rtrim($post['name']));
            if($name != $post['name']){
                $already  = $em->getRepository('Catalog\Entity\Options')->findOneBy(array('name' => addslashes($post['name'])));
                if($already){
                    $this->flashMessenger()->addErrorMessage("The new name of option group '{$post['name']}' is already in use. Try again.");
                    $entities = $em->getRepository('Catalog\Entity\Options')->findBy(array('name' => $name));
                    if(count($entities) == 0){
                        return $this->redirect()->toRoute('admin-options', array(
                            'action' => 'add'
                        ));
                    }
                    $view = new ViewModel(
                        array(
                            'entities' => $entities,
                            'entity' => $entity
                        )
                    );

                    return $view;
                }
            }
            $entities = $em->getRepository('Catalog\Entity\Options')->findBy(array('name' => $name));
            foreach($entities as $entity){
                $em->remove($entity);
                $em->flush();
            }

            foreach($post['option_name'] as $option){
                //echo addslashes(trim(rtrim($option))); exit;
                $entity = new Options();
                $entity->setName(addslashes(trim(rtrim($post['name']))));
                $entity->setOptionName(addslashes(trim(rtrim($option))));
                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();
            }
            $this->flashMessenger()->addSuccessMessage("Options group updated successfully.");
            return $this->redirect()->toRoute('admin-options');

        }
        $entities = $em->getRepository('Catalog\Entity\Options')->findBy(array('name' => $name));
        if(count($entities) == 0){
            return $this->redirect()->toRoute('admin-options', array(
                'action' => 'add'
            ));
        }
        $view = new ViewModel(
            array(
                'entities' => $entities,
                'entity' => $entity
            )
        );

        return $view;

    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $entities = $request->getPost()->toArray();
            if(isset($entities["entities"])){
                foreach($entities["entities"] as $entity_id){

                    $entity = $this->getEntityManager()->getRepository('Catalog\Entity\Options')->find($entity_id);
                    $entities = $this->getEntityManager()->getRepository('Catalog\Entity\Options')->findBy(array('name' => $entity->getName()));
                    foreach($entities as $ent){
                        $this->getEntityManager()->remove($ent);
                    }

                }
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage("Options Group(s) deleted successfully.");
            }
        }
        return $this->redirect()->toRoute('admin-options');
    }
} 