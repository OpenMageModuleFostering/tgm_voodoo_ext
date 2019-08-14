<?php

class TGM_Voodoo_Helper_Data extends Mage_Core_Helper_Abstract
{
	const CONFIG_PATH = 'voodoo/';

	public function isOrdersEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/enabled');
    }

	public function isOrderHoldEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/enabled');
    }

	public function isOrderUnholdEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/enabled');
    }

	public function isOrderCanceledEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/enabled');
    }

	public function isShipmentsEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'shipments/enabled');
    }

	public function getUsername()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'enter/username');
	}

	public function getPassword()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'enter/password');
	}

	public function getSender()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/sender');
	}

	public function getSenderForOrderHold()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/sender');
	}

	public function getSenderForOrderUnhold()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/sender');
	}

	public function getSenderForOrderCanceled()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/sender');
	}

	public function getSenderForShipment()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'shipments/sender');
	}

	public function getMessage(Mage_Sales_Model_Order $order)
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

		return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'orders/message'));
	}

	public function getMessageForOrderHold(Mage_Sales_Model_Order $order)
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

		return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/message'));
	}

	public function getMessageForOrderUnhold(Mage_Sales_Model_Order $order)
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

		return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/message'));
	}

	public function getMessageForOrderCanceled(Mage_Sales_Model_Order $order)
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

		return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/message'));
	}

	public function getMessageForShipment(Mage_Sales_Model_Order $order)
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

		return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'shipments/message'));
	}

	public function getTelephoneFromOrder(Mage_Sales_Model_Order $order)
    {
        $billingAddress = $order->getBillingAddress();


        $number = $billingAddress->getTelephone();
        return $number;
    }

    //admin Notifier functions on order
	public function isOrdersNotify()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/notify');
    }

	public function getAdminTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'orders/receiver');
    }

    //admin Notifier functions on order hold
    public function isOrdersHoldNotify()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/notify');
    }

    public function getAdminHoldTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/receiver');
    }

    //admin Notifier functions on order Unhold
    public function isOrdersUnholdNotify()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/notify');
    }

    public function getAdminUnholdTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/receiver');
    }

    //admin Notifier functions on order cancelled
    public function isOrdersCancelledNotify()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/notify');
    }

    public function getAdminCancelledTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/receiver');
    }

    //admin Notifier functions on order shipment
    public function isOrdersShipmentsNotify()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'shipments/notify');
    }

    public function getAdminShipmentsTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'shipments/receiver');
    }


    public function voodoo($url) {
		try {
			$sendSms = $this->file_get_contents_curl($url);
		}
		catch(Exception $e) {
            $sendSms = '';
		}
		if($sendSms) {
			switch($sendSms) {
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
    public function verify_api($url)
    {
        $verified = $this->file_get_contents_curl($url);
        return $verified;
    }
    public function verify_others($url)
    {
        $verify_others = $this->file_get_contents_curl($url);
        return $verify_others;
    }
}