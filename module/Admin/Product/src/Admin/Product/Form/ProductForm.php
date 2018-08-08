<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/7/14
 * Time: 7:58 AM
 */

namespace Admin\Product\Form;
use Admin\Category\Model\CategoryTable;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class ProductForm extends Form{
    public function __construct($name = null, CategoryTable $categoryTable){
        parent::__construct("product");
        $this->setAttribute("method", "post");
        $this->setAttribute("id", "productform");
        $this->add(
            array(
                'name' => 'product_id',
                'attributes' => array(
                    'type' => 'hidden',
                    //'value' => 0
                )
            )
        );

        $this->add(
            array(
                'name' => 'title',
                'attributes' => array(
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => "Product Name"
                )
            )
        );


        $this->add(
            (new Textarea())
                ->setAttributes(
                    array(
                        'name' => 'description',
                        'id' => 'description',

                    )
                )
                ->setOptions(array(
                    'label' => 'Description'
                ))
        );

        $this->add(
            array(
                'name' => 'option_title[]',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Option Title',
                    'value' => 'default',
                    'class' => 'form-control custom-title',
                    'readonly' => 'readonly',
                ),

            )
        );
        $this->add(
            array(
                'name' => 'available_qty[]',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Available Quantity',

                    'class' => 'form-control'
                ),


            )
        );
        $this->add(
            array(
                'name' => 'available_qty[]',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Available Quantity',

                    'class' => 'form-control'
                ),


            )
        );
        $this->add(
            array(
                'name' => 'price',
                'attributes' => array(
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => "Unit Price"
                )
            )
        );

        $select_category = new Select();
        $select_category->setName("category_id");
        $select_category->setLabel("Category");
        $value_options = array("0" => "None");

        $categories = $categoryTable->fetchAll2();

        foreach($categories as $category){

            $name = $category->getTitle();
            $temp = $category;
            while($temp->getParentCategoryId() != 0){

                $temp = $categoryTable->getCategory($temp->getParentCategoryId());

                $name = $temp->getTitle() . " > " . $name;
            }

                $value_options[$category->getId()] = $name;


        }
        $select_category->setValueOptions($value_options);
        $this->add($select_category);

        $this->add(
            array(
                'name' => 'meta_title',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Meta Title'
                ),
            )
        );

        $this->add((new Textarea())
                ->setAttributes(
                    array(
                        'name' => 'meta_description',
                    )
                )
                ->setOptions(array(
                    'label' => 'Meta Description'
                ))
        );

        $this->add(
            array(
                'name' => 'meta_keyword',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Meta Keywords'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'sort_order',
                'attributes' => array(
                    'type' => 'text',
                    'value' => 0,
                ),
                'options' => array(
                    'label' => 'Sort Order'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'main_image',
                'attributes' => array(
                    'type' => 'text',
                    'id' => 'main_image',
                    'class'=>'form-control image_input',
                    'placeholder' => 'Product Main Image',
                    'onchange' => 'handleImage(this);',
                    'readonly' => 'readonly'
                ),
                'options' => array(
                    //'label' => 'Product main Image'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'image[]',
                'attributes' => array(
                    'type' => 'hidden',
                    'id' =>'image',
                    'class'=>'more_images',
                    'onchange' => 'handleImage(this);',

                ),

            )
        );

        //category status
        $this->add(array(
            'type'  => 'radio',
            'name'  => 'enabled',

            'options' => array(
                'label' => 'Status',

                'value_options' => array(
                    'enabled' => array(
                        'label' => 'Enabled',
                        'value' => '1',

                    ),
                    'disabled' => array(
                        'label' => 'Disabled',
                        'value' => '0',

                    ),

                ),
            ),
            'attributes' => array(
                'value' => 1
            )
        ));


        $this->add(
            array(
                'name' => 'barcode',
                'attributes' => array(
                    'type' => 'text',

                ),
                'options' => array(
                    'label' => 'Barcode'
                ),
            )
        );

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => "Save",
            )
        ));
    }
} 