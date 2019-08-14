<?php
class TGM_Voodoo_Helper_Dataaa extends Mage_Core_Helper_Abstract
{

    const EXT_PATH = 'voodoo/';

    public function isEnabled($case){
        switch($case){
            case 1:
                return Mage::getStoreConfig(self::EXT_PATH.'orders/enabled');
            break;
            case 2:
                return Mage::getStoreConfig(self::EXT_PATH.'order_hold/enabled');
                break;
            case 3:
                return Mage::getStoreConfig(self::EXT_PATH.'order_unhold/enabled');
                break;
            case 4:
                return Mage::getStoreConfig(self::EXT_PATH.'order_cancelled/enabled');
            break;
            case 5:
                return Mage::getStoreConfig(self::EXT_PATH.'order_shipment/enabled');
            break;
        }
    }

    public function getUsername()
    {
        return Mage::getStoreConfig(self::EXT_PATH.'enter/username');
    }

    public function getPassword()
    {
        return Mage::getStoreConfig(self::EXT_PATH.'enter/password');
    }

    public function getSender($case)
    {
        switch($case){
            case 1:
                return Mage::getStoreConfig(self::EXT_PATH.'orders/sender');
            break;
            case 2:
                return Mage::getStoreConfig(self::EXT_PATH.'order_hold/sender');
            break;
            case 3:
                return Mage::getStoreConfig(self::EXT_PATH.'order_unhold/sender');
            break;
            case 4:
                return Mage::getStoreConfig(self::EXT_PATH.'order_cancelled/sender');
            break;
            case 5:
                return Mage::getStoreConfig(self::EXT_PATH.'order_shipment/sender');
            break;
        }
    }

    public function getMessage(Mage_Sales_Model_Order $order,$case)
    {
        $billingAddress = $order->getBillingAddress();
        $codes = array('{{firstname}}','{{middlename}}','{{lastname}}','{{fax}}','{{postal}}','{{city}}','{{email}}','{{order_id}}');
        $accurate = array($billingAddress->getFirstname(),
            $billingAddress->getMiddlename(),
            $billingAddress->getLastname(),
            $billingAddress->getFax(),
            $billingAddress->getPostcode(),
            $billingAddress->getCity(),
            $billingAddress->getEmail(),
            $order->getIncrementId()
        );

        switch($case){
            case 1:
                return str_replace($codes,$accurate,Mage::getStoreConfig(self::EXT_PATH.'orders/message'));
            break;
            case 2:
                return str_replace($codes,$accurate,Mage::getStoreConfig(self::EXT_PATH.'order_hold/message'));
            break;
            case 3:
                return str_replace($codes,$accurate,Mage::getStoreConfig(self::EXT_PATH.'order_unhold/message'));
            break;
            case 4:
                return str_replace($codes,$accurate,Mage::getStoreConfig(self::EXT_PATH.'order_cancelled/message'));
            break;
            case 5:
                return str_replace($codes,$accurate,Mage::getStoreConfig(self::EXT_PATH.'order_shipment/message'));
            break;
        }
    }
    public function getDestination(Mage_Sales_Model_Order $order)
    {
        $billingAddress = $order->getBillingAddress();
        $number = $billingAddress->getTelephone();
        return $number;
    }

    public function isNotifyEnabled($case){
        switch($case){
            case 1:
                return Mage::getStoreConfig(self::EXT_PATH.'orders/notify');
            break;
            case 2:
                return Mage::getStoreConfig(self::EXT_PATH.'order_hold/notify');
            break;
            case 3:
                return Mage::getStoreConfig(self::EXT_PATH.'order_unhold/notify');
            break;
            case 4:
                return Mage::getStoreConfig(self::EXT_PATH.'order_cancelled/notify');
            break;
            case 5:
                return Mage::getStoreConfig(self::EXT_PATH.'order_shipment/notify');
            break;
        }
    }

    public function getAdminDestination($case){
        switch($case){
            case 1:
                return Mage::getStoreConfig(self::EXT_PATH.'orders/receiver');
                break;
            case 2:
                return Mage::getStoreConfig(self::EXT_PATH.'order_hold/receiver');
                break;
            case 3:
                return Mage::getStoreConfig(self::EXT_PATH.'order_unhold/receiver');
                break;
            case 4:
                return Mage::getStoreConfig(self::EXT_PATH.'order_cancelled/receiver');
                break;
            case 5:
                return Mage::getStoreConfig(self::EXT_PATH.'order_shipment/receiver');
                break;
        }
    }

    public function voodoo($url) {
        try {
            $smsSent = $this->file_get_contents_curl($url);
        }
        catch(Exception $e) {
            $smsSent = '';
        }
        if($smsSent) {
            switch($smsSent) {
                case '401:	Unauthorized':
                    $status_message = Mage::helper('voodoo')->__('Voodoo Username or password incorrect (UNAUTHORIZED).');
                    $status = Mage::helper('voodoo')->__('Not sent');
                    break;
                case '403:	Forbidden':
                    $status_message = Mage::helper('voodoo')->__('Wrong Number Inserted (FORBIDDEN).');
                    $status = Mage::helper('voodoo')->__('Not sent');
                    break;
                case '400:	Bad request':
                    $status_message = Mage::helper('voodoo')->__('There might be something wrong happened (BAD REQUEST).');
                    $status = Mage::helper('voodoo')->__('Not sent');
                    break;
                case '402:	Not enough credit':
                    $status_message = Mage::helper('voodoo')->__('Insufficient Credit to send (NOT ENOUGH CREDIT).');
                    $status = Mage::helper('voodoo')->__('Not sent');
                    break;
                case '513:	Message too Large':
                    $status_message = Mage::helper('voodoo')->__('Too long message to send (LARGE MESSAGE).');
                    $status = Mage::helper('voodoo')->__('Not sent');
                    break;
                default:
                    $status_message = Mage::helper('voodoo')->__('Sms successfully sent.');
                    $status = Mage::helper('voodoo')->__('Sent');
                    break;
            }
        }
        else {
            $status_message = Mage::helper('voodoo')->__('Not able to send the sms. Please contact the developer.');
            $status = 'Not sent';
        }
        $ret['status_message'] = $status_message;
        $ret['status'] = $status;
        return $ret;
    }

    public function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function credits($url){
        $credits = $this->file_get_contents_curl($url);
        return $credits;
    }

}