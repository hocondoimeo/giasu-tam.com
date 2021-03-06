<?php

class Application_Form_Tutors extends Zend_Form
{
	public function init() {
		$this->getView();
	}

    /**
     * @author code generate
     * @return mixed
     */
    public function __construct($option = array())
    {
        $userId = new Zend_Form_Element_Hidden('TutorId');
        $userId->setDecorators(array('ViewHelper'));
        $this->addElement($userId);

        $birthDay = new Zend_Form_Element_Text('Birthday');
        $birthDay->setLabel('Ngày sinh *');
        $birthDay->addFilter('StringTrim');
        $birthDay->setRequired(true);
        $birthDay->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $birthDay->addValidator('stringLength', false, array(2, 50, "messages" => "Ngày sinh dài tối đa 50 ký tự"));
        $this->addElement($birthDay);
        
        $gender = new Zend_Form_Element_Select('Gender');
        $gender->setLabel('Giới tính *');
        $gender->addFilter('StringTrim');
        $gender->setRequired(true);
        $gender->setMultiOptions(array('1'=>'Nam', '0'=>'Nữ'));
        $gender->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $gender->setSeparator('');
        $gender->setValue("1");
        $this->addElement($gender);

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
        $email->addValidator(new Zend_Validate_Db_NoRecordExists("Tutors","Email"));
        $email->addValidator('EmailAddress', true);
        $email->setRequired(true)->addValidator('NotEmpty',true,array('messages'=>array('isEmpty'=>"Email không phù hợp")));
        $this->addElement($email);

        $userName = new Zend_Form_Element_Text('UserName');
        $userName->setLabel('Họ tên *');
        $userName->addFilter('StringTrim');
        $userName->setRequired(true);
        $userName->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $userName->addValidator('stringLength', false, array(2, 50, "messages" => "Họ tên dài tối đa 2-50 ký tự"));
        $this->addElement($userName);

        $address= new Zend_Form_Element_Text('Address');
        $address->setLabel('Địa chỉ *');
        $address->addFilter('StringTrim');
        $address->setRequired(true);
        $address->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $userName->addValidator('stringLength', false, array(1, 100, "messages" => "Địa chỉ dài tối đa 100 ký tự"));
        $this->addElement($address);

        $phone  = new Zend_Form_Element_Text('Phone');
        $phone->setLabel('Điện thoại *');
        $phone->addFilter('StringTrim');
        $phone->setRequired(true);
        $phone->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'col-lg-2 control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $phone->addValidator('stringLength', false, array(6, 50, "messages" => "Điện thoại dài tối đa 6-50 ký tự"));
        $this->addElement($phone);
        
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);        
        
        $level = new Zend_Form_Element_Select('Level');
        $level->setLabel('Trình độ *');
        $level->addFilter('StringTrim');
        $level->addValidator('Int');
        $level->setRequired(true);
        $level->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
       $level ->addValidators (array ($required));
        $level->setMultiOptions(unserialize(TUTOR_LEVELS));
        $this->addElement($level);
        
        $university  = new Zend_Form_Element_Text('University');
        $university->setLabel('Trường tốt nghiệp *');
        $university->addFilter('StringTrim');
        $university->setRequired(true);
        $university->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $university->addValidator('stringLength', false, array(1, 100, "messages" => "Trường tố nghiệp dài tối đa 100 ký tự"));
        $this->addElement($university);
        
        $subject  = new Zend_Form_Element_Text('Subject');
        $subject->setLabel('Chuyên ngành *');
        $subject->addFilter('StringTrim');
        $subject->setRequired(true);
        $subject->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $subject->addValidator('stringLength', false, array(1, 100, "messages" => "Chuyên ngành dài tối đa 100 ký tự"));
        $this->addElement($subject);
        
        $experienceYears  = new Zend_Form_Element_Select('ExperienceYears');
        $experienceYears->setLabel('Số Năm Kinh Nghiệm *');
        $experienceYears->addFilter('StringTrim');
        $experienceYears->setRequired(false);
        $experienceYears->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $options = unserialize(EXPERIENCE_YEAR);
        //$experienceYears->addMultiOptions(array_combine($options, $options));
        $experienceYears->setMultiOptions(unserialize(EXPERIENCE_YEAR));
        $this->addElement($experienceYears);
        
        $career = new Zend_Form_Element_Select('Career');
        $career->setLabel('Hiện tại là *');
        $career->addFilter('StringTrim');
        $career->addValidator('Int');
        $career->setRequired(true);
        $career->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $career->setMultiOptions(unserialize(TUTOR_CAREERS));
        $this->addElement($career);
        
        $careerLocation  = new Zend_Form_Element_Text('CareerLocation');
        $careerLocation->setLabel('Nơi Công Tác ( Giáo hoặc Giảng Viên ) *');
        $careerLocation->addFilter('StringTrim');
        $careerLocation->setRequired(false);
        $careerLocation->setAttrib('style', 'min-height: 30px;');
        $careerLocation->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $careerLocation->addValidator('stringLength', false, array(1, 100, "messages" => "Nơi Công Tác dài tối đa 100 ký tự"));
        $this->addElement($careerLocation);
        
        $teachableInClass = new Zend_Form_Element_Text('TeachableInClass');
        $teachableInClass->setLabel('Lớp Có Thể Dạy');
        $teachableInClass->addFilter('StringTrim');
        $teachableInClass->setRequired(false);
        $teachableInClass->setAttrib('disabled', true);
        $teachableInClass->setDescription('<a id="grades-modal" class="btn btn-info" title="Chọn lớp">...</a>');
        $teachableInClass->setDecorators(array(
        		'ViewHelper',
        		array('Description', array('escape' => false)),
        		//array(array('Errors' => 'HtmlTag'), array('placement' => 'append','tag' => 'a', 'id' => 'classes-modal', 'class' => "btn btn-info", 'title' => 'Chọn lớp')),
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control col-lg-6')),
        		array('Label', array('class' => 'control-label col-lg-2')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $this->addElement($teachableInClass);
        
        $teachableSubjects = new Zend_Form_Element_Text('TeachableSubjects');
        $teachableSubjects->setLabel('Môn Có Thể Dạy');
        $teachableSubjects->addFilter('StringTrim');
        $teachableSubjects->setRequired(false);
        $teachableSubjects->setAttrib('disabled', true);
        $teachableSubjects->setDescription('<a id="subjects-modal" class="btn btn-info" title="Chọn môn">...</a>');
        $teachableSubjects->setDecorators(array(
        		'ViewHelper',
        		array('Description', array('escape' => false)),
        		//array(array('Errors' => 'HtmlTag'), array('placement' => 'append','tag' => 'a', 'id' => 'subjects-modal', 'class' => "btn btn-info", 'title' => 'Chọn môn')),
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control col-lg-6')),
        		array('Label', array('class' => 'control-label col-lg-2')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $this->addElement($teachableSubjects);
        
        $teachableDistricts = new Zend_Form_Element_Text('TeachableDistricts');
        $teachableDistricts->setLabel('Khu Vực Có Thể Dạy');
        $teachableDistricts->addFilter('StringTrim');
        $teachableDistricts->setRequired(false);
        $teachableDistricts->setAttrib('disabled', true);
        $teachableDistricts->setDescription('<span id="districts-modal" class="btn btn-info" title="Chọn quận">...</span>');
        $teachableDistricts->setDecorators(array(
        		'ViewHelper',
        		array('Description', array('escape' => false)),
        		//array(array('Errors' => 'HtmlTag'), array('placement' => 'append','tag' => 'a', 'id' => 'districts-modal', 'class' => "btn btn-info", 'title' => 'Chọn quận')),
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control col-lg-6')),
        		array('Label', array('class' => 'control-label col-lg-2')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $this->addElement($teachableDistricts);
        
        $avatar  = new Zend_Form_Element_Hidden('Upload');
        $avatar->setLabel('Hình đại diện');
        $avatar->setRequired(false);
        $avatar->setDescription('fieldlabel');
        $avatar->setDecorators(array(
        		'ViewHelper',
        		array(array('Description' => 'HtmlTag'), array('tag' => 'div', 'id' =>"file-uploader")),
        		array(array('Errors' => 'HtmlTag'), array('placement' => 'append','tag' => 'img', 'id' => 'progress-img', 'src' => "/scripts/upload/loading.gif")),
        		//array(array('Errors' => 'HtmlTag'), array('placement' => 'prepend')),
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control', 'style' => "position: relative;float: left;margin-left: 20px;")),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')),
        ));
        $this->addElement($avatar);
        
        $avatarNote  = new Zend_Form_Element_Hidden('Avatar');
        $avatarNote->setLabel('Chú ý');
        $avatarNote->setRequired(false);
        $avatarNote->setDescription("Kích thước khoảng: 240 x 120 (px)<br>Kích cỡ cho phép: ".IMAGE_SIZE_LIMIT." kB");
        $avatarNote->setDecorators(array(
        		'ViewHelper',
        		array('Description', array('escape' => false, 'tag' => 'div')),
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group', 'style' => "float: left;")),
        ));
        $this->addElement($avatarNote);
        
        $introduction  = new Zend_Form_Element_Textarea('Introduction');
        $introduction->setLabel('Giới thiệu bản thân');
        $introduction->addFilter('StringTrim');
        $introduction->setRequired(false);
        $introduction->setOptions(array('cols' => '10', 'rows' => '4'));
        /* $introduction->setDecorators(array('ViewHelper')); */
        $introduction->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $subject->addValidator('stringLength', false, array(1, 2000, "messages" => "Giới thiệu bản thân dài tối đa 2000 ký tự"));
        $this->addElement($introduction);

        $isDisabled = new Zend_Form_Element_Text('IsDisabled');
        $isDisabled->setLabel('IsDisabled');
        $isDisabled->addFilter('StringTrim');
        $isDisabled->addValidator('Int');
       $isDisabled ->setDecorators(array(
        		'ViewHelper',
        		array(array('control' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-control')),
        		array('Label', array('class' => 'control-label')),
        		array(array('controls' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $this->addElement($isDisabled);
        
        $submit = new Zend_Form_Element_Submit('Save');
        $submit->setLabel('Đăng ký');
        $submit->setAttrib('class', 'btn btn-primary');
        $submit->setDecorators(array('ViewHelper'));
        $this->addElement($submit);
        
        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setLabel('Làm lại');
        $reset->setAttrib('class', 'btn btn-primary');
        $reset->setDecorators(array('ViewHelper'));
        $this->addElement($reset);
        
        	       
        $this->addDisplayGroup(array(
        		'UserName',
        		'Gender',        
        		'Birthday',
        		'Email',
        		'Address',
        		'Phone',        
        ),'contact',array(	'disableLoadDefaultDecorators' => false, 'legend' => 'Thông tin cá nhân'));
        
        $contact = $this->getDisplayGroup('contact');
        $contact->setDecorators(array(        
        		'FormElements',
        		'Fieldset',
        		array('HtmlTag', array('tag'=>'fieldset', 'class'=>'well the-fieldset')),
        ));
        
        $this->addDisplayGroup(array(
        		'Level',
        		'University',
        		'Subject',
        		'Career',
        		'CareerLocation',
        		'ExperienceYears',
        ),'level',array('disableLoadDefaultDecorators' => false, 'legend' => 'Thông tin học vấn'));
        
        $level = $this->getDisplayGroup('level');
        $level->setDecorators(array(
        		'FormElements',
        		'Fieldset',
        		array('HtmlTag', array('tag'=>'fieldset', 'class'=>'well the-fieldset')),
        ));
        
        $this->addDisplayGroup(array(
        		'TeachableInClass',
        		'TeachableSubjects',
        		'TeachableDistricts',
        		'Upload',
        		'Avatar',
        		'Introduction',
        ),'extra',array('disableLoadDefaultDecorators' => false, 'legend' => 'Thông tin thêm'));
        
        $extra = $this->getDisplayGroup('extra');
        $extra->setDecorators(array(
        		'FormElements',
        		'Fieldset',
        		array('HtmlTag', array('tag'=>'fieldset', 'class'=>'well the-fieldset')),
        ));
        
        $this->addDisplayGroup(array(
        		'Save',
        		'Reset',
        ),'submit',array('disableLoadDefaultDecorators' => false, 'legend' => 'Thông tin học vấn'));
        
        $submit = $this->getDisplayGroup('submit');
        $submit->setDecorators(array(
        		'FormElements',
        		'Fieldset',
        		array('HtmlTag', array('tag'=>'fieldset', 'class'=>'well the-fieldset')),
        ));
    }
    
    public function changeModeToAdd() {
    	$this->getElement('TeachableDistricts')->setAttrib('subs','');
    	$this->getElement('TeachableSubjects')->setAttrib('subs','');
    	$this->getElement('TeachableInClass')->setAttrib('subs','');
    }
    
    public function changeModeToDistrictId() {
    	$cateModel =  new Application_Model_Districts();
    	$this->getElement('DistrictId')->addMultiOptions($cateModel->getFormPairs());
    }
    
    public function changeModeToClass($grades, $gradesText) {
    	$this->getElement('TeachableInClass')->setValue($gradesText);
    	$this->getElement('TeachableInClass')->setAttrib('subs', $grades);
    }
    
    public function changeModeToSubjects($subjects, $subjectsText) {
    	$this->getElement('TeachableSubjects')->setValue($subjectsText);
    	$this->getElement('TeachableSubjects')->setAttrib('subs', $subjects);
    }
    
    public function changeModeToDistricts($districts, $districtsText) {
    	$this->getElement('TeachableDistricts')->setValue($districtsText);
    	$this->getElement('TeachableDistricts')->setAttrib('subs', $districts);
    }
}