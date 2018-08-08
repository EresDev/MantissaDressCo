<?php
namespace Admin\Colour\Controller;
use Application\Controller\ActionController;
use Catalog\Entity\ProductColour;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\View\Model\ViewModel;

class ColourController extends ActionController{
    public function indexAction(){
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            if(isset($post['colour_name'])){
                //add
                $post['colour_name'] = trim(rtrim($post['colour_name']));
                $colour = $this->getEntityManager()->getRepository('Catalog\Entity\ProductColour')
                    ->findOneBy(array('colourName' => $post['colour_name']));
                if($colour){
                    $this->flashMessenger()->addErrorMessage("The colour is already available in list and can't be added again.");

                }
                else{
                    $post['colour_name'] = ucfirst($post['colour_name']);
                    $colour = new ProductColour();
                    $colour->setColourName($post['colour_name']);
                    $this->getEntityManager()->persist($colour);
                    $this->getEntityManager()->flush();
                    $this->flashMessenger()->addSuccessMessage("Colour added successfully.");

                }
            }

        }
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\ProductColour');
        $queryBuilder = $repository->createQueryBuilder('colours')

            ->orderBy('colours.productColourId', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);
        $view = new ViewModel(
            array(
                'colours' => $paginator,

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

                    $entity = $this->getEntityManager()->getRepository('Catalog\Entity\ProductColour')->find($entity_id);
                    $this->getEntityManager()->remove($entity);
                }
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage("Colour/Colours deleted successfully.");
            }
        }
        return $this->redirect()->toRoute('admin-colour');
    }

} 