<?php

class Application_Form_Users extends Zend_Form
{

    /**
     * @author code generate
     * @return mixed
     */
    public function __construct($option = array())
    {
        $classId = new Zend_Form_Element_Hidden('UserId');
        $classId->setDecorators(array('ViewHelper'));
        $this->addElement($classId);

        $email = new Zend_Form_Element_Text('Email');
        $email->setLabel('Email *');
        $email->addFilter('StringTrim');
        $email->setRequired(true);
         $email->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
         
        $email->addValidator(new Zend_Validate_Db_NoRecordExists("Users","Email"));
        $email->addValidator('EmailAddress', true);
        $email->setRequired(true)->addValidator('NotEmpty',true,array('messages'=>array('isEmpty'=>"Email không phù hợp")));
        
        $this->addElement($email);
        
        $save = new Zend_Form_Element_Submit('Save');
        $save->setLabel('Đăng ký');
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