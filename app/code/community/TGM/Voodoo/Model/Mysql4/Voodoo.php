<?php

class TGM_Voodoo_Model_Mysql4_Voodoo extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the sendsms_id refers to the key field in your database table.
        $this->_init('voodoo/voodoo', 'voodoo_id');
    }
}