<?php

class TGM_Voodoo_Model_Voodoo extends Mage_Core_Model_Abstract
{
    const CONFIG_PATH = 'voodoo/';
    public function _construct()
    {
        parent::_construct();
        $this->_init('voodoo/voodoo');
    }

    public function getCredit(){
        $username = Mage::helper('voodoo/Data')->getUsername();
        $password = Mage::helper('voodoo/Data')->getPassword();
        $host = "http://www.voodoosms.com/";
        $path = "vapi/server/getcredit";
        $data = '?uid=' . urlencode($username);
        $data .= '&pass=' . urlencode($password);
        $data .= '&format=json';
        $url = $host.$path.$data;
        $credits = Mage::helper('voodoo/Data')->credits($url);
        return $credits;
    }

    public function verify_api(){
        $username = Mage::helper('voodoo/Data')->getUsername();
        $password = Mage::helper('voodoo/Data')->getPassword();
        $host = "http://www.voodoosms.com/";
        $path = "vsapi/server-test.php";
        $data = '?method=verify_api';
        $data .= '&username='.urlencode($username);
        $data .= '&password='.urlencode($password);
        $url = $host.$path.$data;
        $verified = Mage::helper('voodoo/Data')->verify_api($url);
        return $verified;
    }

    public function verify_others(){
        $originator = array();
        $originator[0] = Mage::getStoreConfig(self::CONFIG_PATH.'orders/sender');
        $originator[1] = Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/sender');
        $originator[2] = Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/sender');
        $originator[3] = Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/sender');;
        $originator[4] = Mage::getStoreConfig(self::CONFIG_PATH.'shipments/sender');

        $message = array();
        $message[0] = Mage::getStoreConfig(self::CONFIG_PATH.'orders/message');
        $message[1] = Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/message');
        $message[2] = Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/message');
        $message[3] = Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/message');
        $message[4] = Mage::getStoreConfig(self::CONFIG_PATH.'shipments/message');

        $verify_others = 1;
        foreach ($originator as $row){
            $url = "www.voodoosms.com/vsapi/server-test.php?method=validate_nonperm_words";
            $url.="&message=$row&type=2";
            $verify_others&= Mage::helper('voodoo/Data')->verify_others($url);

        }
        foreach ($message as $row){
            $url = "www.voodoosms.com/vsapi/server-test.php?method=validate_nonperm_words";
            $url.="&message=$row&type=1";
            $verify_others&= Mage::helper('voodoo/Data')->verify_others($url);

        }
        return $verify_others;
    }

    public function exportOrder($order,$sendSms)
    {
        $dirPath = Mage::getBaseDir('var') . DS . 'export';

        //if the export directory does not exist, create it
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        file_put_contents(
            $dirPath. DS .$order->getIncrementId().'.txt',
            $sendSms
        );

        return true;
    }


}