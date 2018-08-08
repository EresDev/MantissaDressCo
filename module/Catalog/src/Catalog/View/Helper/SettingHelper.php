<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/21/14
 * Time: 4:06 PM
 */

namespace Catalog\View\Helper;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;


class SettingHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{

    //use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    protected $sm;
    private $setting = array();
    public function __invoke($setting_key){
        if(!$setting_key){
            new \Exception("Invalid setting key");
        }

        if(isset($this->setting[$setting_key])){
            return $this->setting[$setting_key];
        }
        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default');

        $repository = $em->getRepository('Catalog\Entity\Setting');
        /** @var \Catalog\Entity\Setting $setting */
        $setting = $repository->findOneBy(array('settingKey' => $setting_key));

        $this->setting[$setting_key] = $setting->getSettingValue();
        return $setting->getSettingValue();
    }
    public function getServiceLocator(){
        return $this->sm;
    }
    public function setServiceLocator(ServiceLocatorInterface $sm){
        $this->sm = $sm;
        return $this;
    }


} 