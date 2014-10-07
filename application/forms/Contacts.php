<?php

class Application_Form_Contacts extends Zend_Form
{

    /**
     * @author code generate
     * @return mixed
     */
    public function __construct($option = array())
    {
        $contactId = new Zend_Form_Element_Hidden('ContactId');
        $contactId->setDecorators(array('ViewHelper'));
        $this->addElement($contactId);

        $contactName = new Zend_Form_Element_Text('ContactName');
        $contactName->setLabel('Họ tên *');
        $contactName->addFilter('StringTrim');
        $contactName->setRequired(true);
        $contactName->setDecorators(array('ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $contactName->addValidator('stringLength', false, array(1, 50, "messages" => " dài tối đa 50 ký tự"));
        $this->addElement($contactName);

        $userId = new Zend_Form_Element_Text('UserId');
        $userId->setLabel('UserId');
        $userId->addFilter('StringTrim');
        $userId->setAttrib('disabled', true);
        $userId->setDecorators(array('ViewHelper'));
        $this->addElement($userId);

        $contactPhone = new Zend_Form_Element_Text('ContactPhone');
        $contactPhone->setLabel('Điện thoại *');
        $contactPhone->addFilter('StringTrim');
        $contactPhone->setRequired(true);
        $contactPhone->setDecorators(array('ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $contactPhone->addValidator('stringLength', false, array(1, 15, "messages" => " dài tối đa 50 ký tự"));
        $this->addElement($contactPhone);

        $contactTitle = new Zend_Form_Element_Text('ContactTitle');
        $contactTitle->setLabel('Tiêu đề *');
        $contactTitle->addFilter('StringTrim');
        $contactTitle->setRequired(true);
        $contactTitle->setDecorators(array('ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $contactTitle->addValidator('stringLength', false, array(1, 250, "messages" => " dài tối đa 250 ký tự"));
        $this->addElement($contactTitle);
        
        $contactContent = new Zend_Form_Element_Textarea('ContactContent');
        $contactContent->setLabel('Nội dung *');
        $contactContent->setRequired(true);
        $contactContent->setOptions(array('cols' => '10', 'rows' => '4'));
        $contactContent->setDecorators(array('ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $contactPhone->addValidator('stringLength', false, array(1, 2000, "messages" => " dài tối đa 2000 ký tự"));
        $this->addElement($contactContent);

        $save = new Zend_Form_Element_Submit('Save');
        $save->setLabel('Gửi');
        $save->setAttrib('class', 'btn btn-primary');
        $save->setDecorators(array('ViewHelper'));
        $this->addElement($save);

        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setLabel('Làm lại');
        $reset->setAttrib('class', 'btn btn-primary');
        $reset->setDecorators(array('ViewHelper'));
        $this->addElement($reset);

    }
    
     public function changeModeToAdd() {
    	//$this->removeElement('LastUpdated');
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