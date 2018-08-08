<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/26/14
 * Time: 6:49 AM
 */

namespace Catalog\Navigation;


use Catalog\Entity\Category;

use Doctrine\ORM\EntityManager;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Collections\ArrayCollection;

class CatalogNavigation extends DefaultNavigationFactory
{

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {

        $navigation = array();

        if ($this->pages == null) {
            /** @var EntityManager $em */
            $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
            /** @var Category[] $parent */
            $cats = $em->getRepository('Catalog\Entity\Category')->findBy(array('parentCategory' => null));

                $navigation = $this->get_list($cats, $em);




            $mvcEvent = $serviceLocator->get('Application')
                ->getMvcEvent();

            $routeMatch = $mvcEvent->getRouteMatch();
            $router = $mvcEvent->getRouter();
            $pages = $this->getPagesFromConfig($navigation);

            $this->pages = $this->injectComponents(
                $pages,
                $routeMatch,
                $router
            );

        }
        return $this->pages;


    }

    private function get_list($parent, $em)
    {
        $result = array();
        $result[] = array(
            'label' => "Home",
            'route' => 'home',
        );
        foreach($parent as $ele){
            if(!$ele->getEnabled()){
                continue;
            }
            $children = $em->getRepository('Catalog\Entity\Category')->findBy(array('parentCategory' => $ele->getCategoryId()));
            $pages = array();
            foreach($children as $child){
                if(!$child->getEnabled()){
                    continue;
                }
                $pages[] = array(
                    'label' => $child->getTitle(),
                    'route' => 'products',
                    'params' => array(
                            'category_id' => $child->getCategoryId(),
                    )
                );
            }
            if(count($children) == 0){
                $result[] = array(
                    'label' => $ele->getTitle(),
                    'route' => 'products',
                    'params' => array(
                        'category_id' => $ele->getCategoryId(),
                    )
                );
            }
            else{
                $result[] = array(
                    'label' => $ele->getTitle(),
                    'route' => 'products',
                    'params' => array(
                        'category_id' => $ele->getCategoryId(),
                    ),
                    'pages' => $pages
                );
            }
        }

        return $result;
    }
} 