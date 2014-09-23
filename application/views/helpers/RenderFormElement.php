<?php

class Zend_View_Helper_RenderFormElement extends Zend_View_Helper_Abstract
{
    /**
     * Enter description here ...
     * @param Zend_Form_Element $element
     * @author Tung Ly
     */
    public function renderFormElement($element, $vertical = 0) {

        if($element->getType()!="Zend_Form_Element_Checkbox"){
                $element->setAttrib('class',  $element->getAttrib('class') . ' form-control');
        }
        if($element->isRequired()){
        	$element->setLabel($element->getLabel().' *');
            $element->setAttrib('class',  $element->getAttrib('class') . ' required');
        }
        switch ($element->getType()) {
            case 'Zend_Form_Element_Textarea':
                $element->setAttrib('rows', 5);
                $element->setAttrib('cols', 80);
            break;
            case 'Zend_Form_Element_Hidden':
                return $element;

            default:
                ;
            break;
        }
        
        $error = '';
        if ($element->hasErrors()) {
            $error = 'has-error';
        }
        if($element->getType() == 'Zend_Form_Element_Textarea'){
            
        }
        
        $btn = array(
        	'Zend_Form_Element_Submit',
        	'Zend_Form_Element_Reset'
        );
        if(in_array($element->getType(), $btn)){
        	//$t ='<button type="reset" class="btn"><i class="icon-refresh"></i> '.$element->getLabel().'</button>';
        	$t = '<div class="span2">'.$element.'</div>';
        }else{
        	$label = trim(preg_replace("/([A-Z])/", " $1", "{$element->getLabel()}"), ' ');
	        $variables = array(
	            '%%ERROR_CLASS%%' => $error,
	            '%%ELEMENT_NAME%%' => $element->getName(),
	            '%%ELEMENT_LABEL%%' => $label,
	            '%%ELEMENT%%' => $element,
	            '%%HELP_MESSAGE%%' => current($element->getMessages()),
	        );
	        $t = str_replace(array_keys($variables), $variables, $this->_getTemplate($vertical));
        }
        return $t;
    }
    
    private function _getTemplate($vertical){
    	if($vertical)
	        $t = '
	            <div class="%%ERROR_CLASS%%">
	                <label for="%%ELEMENT_NAME%%" class="control-label">%%ELEMENT_LABEL%%</label>
	                <div class="">
	                    %%ELEMENT%%
	                    <label class="control-label">%%HELP_MESSAGE%%</label>
	                </div>
	            </div>
	        ';
    	else
    		$t = '
	            <div class="form-group %%ERROR_CLASS%%">
	                <label for="%%ELEMENT_NAME%%" class="col-lg-2 control-label">%%ELEMENT_LABEL%%</label>
	                <div class="col-lg-6">
	                    %%ELEMENT%%
	                    <label class="control-label">%%HELP_MESSAGE%%</label>
	                </div>
	            </div>
	        ';
        return $t;
    }
}
/*
<div class="control-group error">
<label for="inputError" class="control-label">Input with error</label>
<div class="controls">
<input type="text" id="inputError">
<span class="help-inline">Please correct the error</span>
</div>
</div>
*/