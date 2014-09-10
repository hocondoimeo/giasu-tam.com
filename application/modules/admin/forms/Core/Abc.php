<?php

class Application_Form_Core_Abc extends Zend_Form
{

    /**
     * @author code generate
     * @return mixed
     */
    public function __construct($option = array())
    {
        $id = new Zend_Form_Element_Hidden('id');
        $id->setDecorators(array('ViewHelper'));
        $this->addElement($id);

        $value = new Zend_Form_Element_Text('value');
        $value->setLabel('value');
        $value->addFilter('StringTrim');
        $value->addValidator('Int');
        $value->setRequired(true);
        $value->setDecorators(array('ViewHelper'));
        $this->addElement($value);

    }
}