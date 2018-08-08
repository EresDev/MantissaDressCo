<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/19/14
 * Time: 11:01 PM
 */

namespace Admin\Setting\Form;


use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class SettingForm extends Form{
    public function __construct($name = null){
        parent::__construct("setting");
        $this->setAttribute("method", "post");
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => "Save",
            )
        ));
        $this->setAttribute("id", "entityform");
    }
    public function addElements($data = array()){
        foreach($data as $entity){
            if($entity->setting_type == "Text"){
                $this->add(
                    array(
                        'name' => $entity->setting_key,
                        'attributes' => array(
                            'type' => $entity->setting_type,
                            'value' => $entity->setting_value,
                        ),
                        'options' => array(
                            'label' => $entity->setting_name,
                        ),
                    )
                );
            }
            else if($entity->setting_type == "Textarea"){
                $textarea = new Textarea();
                $textarea->setLabel($entity->setting_name);
                $textarea->setValue($entity->setting_value);
                $textarea->setName($entity->setting_key);
                $this->add($textarea);
            }
        }

    }
} 