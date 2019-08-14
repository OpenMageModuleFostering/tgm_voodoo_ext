<?php

class TGM_Voodoo_Helper_Data extends Mage_Core_Helper_Abstract
{
	const CONFIG_PATH = 'voodoo/';

	public function isOrdersEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/enabled');
    }

    public function isOptinsEnabled()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'optins/enabled');
    }
    public function isM2eEnabled()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/enabled');
    }
    public function isM2ePayEnabled()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/enablebaypay');
    }
    public function isM2eShipEnabled()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/enablebayship');
    }
    public function isbillingorshipping(){
        return Mage::getStoreConfig(self::CONFIG_PATH.'deno/bish');
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
    public function getSenderForM2e()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/senderid');
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
        $shipmentCollection = $order->getShipmentsCollection();
        foreach ($shipmentCollection as $shipment){


            foreach($shipment->getAllTracks() as $tracknum)
            {
                $tracknums[]=$tracknum->getNumber();
            }
        }
        $codes = array('{{firstname}}','{{middlename}}','{{lastname}}','{{fax}}','{{postal}}','{{city}}','{{email}}','{{order_id}}','{{shipping_id}}');
        $accurate = array($billingAddress->getFirstname(),
            $billingAddress->getMiddlename(),
            $billingAddress->getLastname(),
            $billingAddress->getFax(),
            $billingAddress->getPostcode(),
            $billingAddress->getCity(),
            $billingAddress->getEmail(),
            $order->getIncrementId(),
            $tracknums[0]
        );
		return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'shipments/message'));
	}
    public function getMessageForM2e($data)
    {
        $codes = array(
            '{{ebay_order_id}}',
            '{{email}}',
            '{{name}}',
            '{{amount}}',
            '{{currency}}',
            '{{city}}',
            '{{country_code}}',
            '{{country_name}}',
            '{{state}}',
            '{{postal_code}}',
            '{{phone}}',
            '{{street}}',
            '{{service}}',
            '{{shipping_price}}',
            '{{payment_method}}'
        );
        $accurate = array(
            $data['ebay_order_id'],
            $data['buyer_email'],
            $data['buyer_name'],
            $data['paid_amount'],
            $data['currency'],
            $data['shipping_details']->address->city,
            $data['shipping_details']->address->country_code,
            $data['shipping_details']->address->country_name,
            $data['shipping_details']->address->state,
            $data['shipping_details']->address->postal_code,
            $data['shipping_details']->address->phone,
            $data['shipping_details']->address->street[0].' '.$data['shipping_details']->address->street[1],
            $data['shipping_details']->service,
            $data['shipping_details']->price,
            $data['payment_details']->method,
        );
        return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/messagebay'));
    }

    public function getMessageForM2eShip($data)
    {
        $codes = array(
            '{{ebay_order_id}}',
            '{{email}}',
            '{{name}}',
            '{{amount}}',
            '{{currency}}',
            '{{city}}',
            '{{country_code}}',
            '{{country_name}}',
            '{{state}}',
            '{{postal_code}}',
            '{{phone}}',
            '{{street}}',
            '{{service}}',
            '{{shipping_price}}',
            '{{payment_method}}'
        );
        $accurate = array(
            $data['ebay_order_id'],
            $data['buyer_email'],
            $data['buyer_name'],
            $data['paid_amount'],
            $data['currency'],
            $data['shipping_details']->address->city,
            $data['shipping_details']->address->country_code,
            $data['shipping_details']->address->country_name,
            $data['shipping_details']->address->state,
            $data['shipping_details']->address->postal_code,
            $data['shipping_details']->address->phone,
            $data['shipping_details']->address->street[0].' '.$data['shipping_details']->address->street[1],
            $data['shipping_details']->service,
            $data['shipping_details']->price,
            $data['payment_details']->method,
        );
        return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/messagebayship'));
    }

    public function getMessageForM2ePay($data)
    {
        $codes = array(
            '{{ebay_order_id}}',
            '{{email}}',
            '{{name}}',
            '{{amount}}',
            '{{currency}}',
            '{{city}}',
            '{{country_code}}',
            '{{country_name}}',
            '{{state}}',
            '{{postal_code}}',
            '{{phone}}',
            '{{street}}',
            '{{service}}',
            '{{shipping_price}}',
            '{{payment_method}}'
        );
        $accurate = array(
            $data['ebay_order_id'],
            $data['buyer_email'],
            $data['buyer_name'],
            $data['paid_amount'],
            $data['currency'],
            $data['shipping_details']->address->city,
            $data['shipping_details']->address->country_code,
            $data['shipping_details']->address->country_name,
            $data['shipping_details']->address->state,
            $data['shipping_details']->address->postal_code,
            $data['shipping_details']->address->phone,
            $data['shipping_details']->address->street[0].' '.$data['shipping_details']->address->street[1],
            $data['shipping_details']->service,
            $data['shipping_details']->price,
            $data['payment_details']->method,
        );
        return str_replace($codes,$accurate,Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/messagebaypay'));
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

    //admin Notifier functions on  M2e order
    public function isOrdersM2eNotify()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/notify');
    }

    public function getAdminM2eTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'mtwoe/receiver');
    }



    public function voodoo($url) {
		try {
			$sendSms = $this->file_get_contents_curl($url);
		}
		catch(Exception $e) {
            $sendSms = '';
		}
		if($sendSms) {
			switch($sendSms->result) {
				case '401':
					$status_message = Mage::helper('voodoo')->__('Voodoo Username or password incorrect (UNAUTHORIZED).');
					$status = Mage::helper('voodoo')->__('Not sent');
					break;
				case '400':
					$status_message = Mage::helper('voodoo')->__('Wrong Number Inserted (FORBIDDEN).');
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
        $data = json_decode($data);
		return $data;
	}
    public function credits($url){
        $credits = $this->file_get_contents_curl($url);
        if($credits->credit){
            return $credits->credit;
        }else{
            return false;
        }
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

    public function verify_number($url)
    {
        $verify_number = $this->file_get_contents_curl($url);
        return $verify_number;
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
    public function gettingSmsNumber(Mage_Sales_Model_Order $order){
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT sms_number FROM voodoo_number where order_id =".$order->getIncrementId()." LIMIT 1";
        $results = $readConnection->fetchRow($query);
        return $results['sms_number'];

    }
}