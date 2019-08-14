<?php
class TGM_Voodoo_Model_Adminhtml_System_Config_Source_Color
{
    public function toOptionArray()
    {
        $themes = array(
            array('value' => 0, 'label' => 'Billing'),
            array('value' => 1, 'label' => 'Shipping'),
        );

        return $themes;
    }
}

?>