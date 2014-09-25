<?php

class Application_Form_Classes extends Zend_Form
{

    /**
     * @author code generate
     * @return mixed
     */
    public function __construct($option = array())
    {
        $classId = new Zend_Form_Element_Hidden('ClassId');
        $classId->setDecorators(array('ViewHelper'));
        $this->addElement($classId);

        $classAddress = new Zend_Form_Element_Text('TutorId');
        $classAddress->setLabel('Mã số của bạn *');
        $classAddress->addFilter('StringTrim');
        $classAddress->setRequired(true);
        $classAddress->addValidator('int');
         $classAddress->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $classAddress->addValidator('stringLength', false, array(1, 10, "messages" => "Mã số chỉ dài tối đa 10 chữ số"));
        $this->addElement($classAddress);
        
        $save = new Zend_Form_Element_Submit('Save');
        $save->setLabel('Ứng tuyển');
        $save->setAttrib('class', 'btn btn-primary');
        $save->setDecorators(array(
        		'ViewHelper',
        		//array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		//array('Label', array('class' => 'col-lg-2 control-label')),
        		//array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group full-left'))
        ));
        $this->addElement($save);

        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setLabel('Làm lại');
        $reset->setAttrib('class', 'btn btn-primary');
        $reset->setDecorators(array(
        		'ViewHelper',
        		//array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		//array('Label', array('class' => 'col-lg-2 control-label')),
        		//array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $this->addElement($reset);

    }
    
     public function changeModeToAdd() {
    	$this->removeElement('LastUpdated');
    	$this->removeElement('CreatedDate');
    	$this->getElement('Save')->setLabel('Add');
    }
    
    public function changeModeToUpdate($cateId) {
    	//$this->removeElement('CreatedDate');
    	//$this->removeElement('LastUpdated');
    	//$this->getElement('MenuCode')->setAttrib('disabled', true);
    	$this->getElement('Save')->setLabel('Update')->setAttrib('class', 'btn btn-warning');
    }
    
    public function changeModeToDelete($cateId) {
    	//$this->removeElement('CreatedDate');
    	//$this->removeElement('LastUpdated');
    	$this->getElement('Save')->setLabel('Delete')->setAttrib('class', 'btn btn-danger');
    }
}