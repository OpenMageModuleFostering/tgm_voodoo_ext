<?php
class TGM_Voodoo_Block_SendTestSmsOnUnHold extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('button')
            ->setLabel(Mage::helper('voodoo')->__('Send Test SMS'))
            ->setOnClick("sendtestmessge('voodoo_order_unhold_')")
            ->toHtml();
        return $html;
    }
}
?>