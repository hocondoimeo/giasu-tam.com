<?php
class My_Form extends Zend_Form
{
	// Form
	protected $_formDecorator = array('FormElements', 'Form');

	// Display Groups
	protected $_groupDecoratorStd = array('FormElements', array('HtmlTag', array('tag'=> 'dl')), 'Fieldset');
	protected $_groupDecoratorCtl = array('FormElements', 'Fieldset');

	// Ctls and hidden elements
	protected $_elementDecoratorCtl = array('ViewHelper');

	public function __construct($options = null)
	{
		parent::__construct($options);

		// Add our HTMLPurifier filter(s)
		$this->addElementPrefixPath('My_Filter', 'my/Filter/', 'filter');
		// Add our Confirmation validator
		$this->addElementPrefixPath('My_Validate', 'my/Validate/', 'validate');
		// Add our custom elements path
		$this->addPrefixPath('My_Form_Element', 'my/Form/Element/', Zend_Form::ELEMENT);

		$this->setAttrib('accept-charset', 'UTF-8');
		$this->setMethod('post');

		$this->setDecorators($this->_formDecorator);

		$this->setDisplayGroupDecorators($this->_groupDecoratorStd);
	}

	public function addAntiCSRF($salt, $timeout = 300, $name = 'no_csrf_foo')
	{
		$this->addElement('hash', $name, array(
				'decorators' => $this->_elementDecoratorCtl,
				'salt' => $salt,
				'timeout' => $timeout
		));
	}

	public function addSubmit($labelSubmit = 'Submit')
	{
		$this->addElement('submit', 'submit', array(
				'label' => $labelSubmit,
				'decorators' => $this->_elementDecoratorCtl,
				'class' => 'submit'
		));

		$this->addDisplayGroup(
				array('submit'), $this->getGroupName('controls'),
				array(
						'legend' => 'Controls',
						'class' => $this->getControlsClass(),
						'decorators' => $this->_groupDecoratorCtl
				)
		);
	}

	public function addSubmitReset($labelSubmit = 'Submit', $labelReset = 'Reset')
	{
		$this->addElement('submit', 'submit', array(
				'label' => $labelSubmit,
				'decorators' => $this->_elementDecoratorCtl,
				'class' => 'submit'
		));

		$this->addElement('reset', 'reset', array(
				'label' => $labelReset,
				'decorators' => $this->_elementDecoratorCtl,
				'class' => 'reset'
		));

		$this->addDisplayGroup(
				array('submit', 'reset'), $this->getGroupName('controls'),
				array(
						'legend' => 'Controls',
						'class' => $this->getControlsClass(),
						'decorators' => $this->_groupDecoratorCtl
				)
		);
	}

	protected function getGroupName($name)
	{
		return get_class($this).'-'.$name;
	}

	protected function getControlsClass()
	{
		return array('controls', get_class($this).'-controls');
	}

	protected function getInputsClass()
	{
		return array('controls', get_class($this).'-inputs');
	}
}

class Application_Form_TestForm extends My_Form
{
	public function init()
	{
		$this->addElement('text', 'title', array(
				'label' => 'Title',
				'validators' => array(
						array('StringLength', false, array(2,50))
				),
		));

		$this->addElement('file', 'file', array(
				'label' => 'file',
		));
		

		$this->addElement(
				'hidden', 'shush', array(
						'value' => 'its quiet',
						'decorators' => $this->_elementDecoratorCtl
				)
		);

		$this->addElement('text', 'comments', array(
				'label' => 'Comments',
				'validators' => array(
						array('StringLength', false, array(2,50))
				),
		));

		$this->addDisplayGroup(
				array('title', 'file', 'dp', 'comments'), 'inputs',
				array(
						'legend' => 'INputs'
				)
		);

		$this->addSubmitReset();
	}
	
	class My_Form_DisplayGroup extends Zend_Form_DisplayGroup {
		public function loadDefaultDecorators() {
			if ($this->loadDefaultDecoratorsIsDisabled()) {
				return;
			}
	
			$this->setDecorators(
					array(
							'ViewHelper',
							array(array('td' => 'HtmlTag'), array('tag' => 'td', 'style' => 'width: 360px;')),
							array('Label', array('tag' => 'td', 'requiredSuffix' => ' *')),
							array(array('tr' => 'HtmlTag'), array('tag' => 'tr')),
							array(array('tbody' => 'HtmlTag'), array('tag' => 'tbody')),
							array(array('table' => 'HtmlTag'), array('tag' => 'table', 'class' => 'tiny')),
							array(array('separator' => 'HtmlTag'), array('tag' => 'div', 'class' => 'separator'))
					)
			);
		}
	
	}//$this->setDefaultDisplayGroupClass('App_Form_DisplayGroup');
	
}