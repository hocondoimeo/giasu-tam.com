<?php
class Application_Form_DisplayGroup extends Zend_Form_DisplayGroup {
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
}