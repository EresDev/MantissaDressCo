<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/19/14
 * Time: 11:35 PM
 */

namespace Admin\Setting\Controller;


use Admin\Setting\Form\SettingForm;
use Admin\Setting\Model\Entity\Setting;
use Zend\Mvc\Controller\AbstractActionController;

class SettingController extends AbstractActionController{
    public $settingTable;
    public function indexAction(){
        $success_message = "";
        $request = $this->getRequest();

        $form = new SettingForm();
        $setting = $this->getSettingTable()->fetchAll();
        $form->addElements($setting);
        if($request->isPost()){
            $setting = new Setting();

            $form->setInputFilter($setting->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                foreach($form->getData() as $setting_key => $setting_value){
                    if($setting_key == 'submit') continue;
                    $setting = new Setting();
                    $setting->exchangeArray(
                      array(
                          'setting_key' => $setting_key,
                          'setting_value' => $setting_value,
                      )
                    );
                    $this->getSettingTable()->saveSetting($setting);
                    $success_message = 'Settings updated successfully.';
                }
                $form = new SettingForm();
                $setting = $this->getSettingTable()->fetchAll();
                $form->addElements($setting);
            }
        }

        return
            array(
                'entityform' => $form,
                'success_message' => $success_message

        );
    }
    public function getSettingTable(){
        if(!$this->settingTable){
            $sm = $this->getServiceLocator();
            $this->settingTable = $sm->get('Admin\Setting\Model\SettingTable');
        }
        return $this->settingTable;
    }
} 