<?php
class TGM_Voodoo_Block_VerifyApiAccount extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $verified = Mage::getModel('voodoo/voodoo')->verify_api();
        if ($verified != '1') {
            $verified = 'Enter correct combination of username and password';
            $html ="<div style='font-weight: bold; font-size: 14px; color:red;'>$verified</div>";
        }
        else{
        $verified = "Your username and password are correct";
        $html ="<div style='font-weight: bold; font-size: 14px; color:green;'>$verified</div>";
        }

        return $html;



    }
}
?>