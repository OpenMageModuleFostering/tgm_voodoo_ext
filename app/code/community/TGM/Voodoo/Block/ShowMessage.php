<?php
class TGM_Voodoo_Block_ShowMessage extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html = '<div id="showhidemessagetext"></div>';

        return $html;



    }
}
?>