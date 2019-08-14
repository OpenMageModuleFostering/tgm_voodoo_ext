<?php

class TGM_Voodoo_Model_Mysql4_Voodoo_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('voodoo/voodoo');
    }
}