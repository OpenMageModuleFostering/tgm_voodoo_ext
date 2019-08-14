<?php
class TGM_Voodoo_Model_Observer
{
	public function sendSmsOnOrderCreated(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrdersEnabled()) {
            $orders = $observer->getEvent()->getOrderIds();
			$order = Mage::getModel('sales/order')->load($orders['0']);
			if ($order instanceof Mage_Sales_Model_Order) {
                $host = "http://www.voodoosms.com/";
                $path = "vsapi/server.php";
                $username = $this->getHelper()->getUsername();
                $password = $this->getHelper()->getPassword();;
                $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                $smsfrom = $this->getHelper()->getSender();;
                $smsmsg = $this->getHelper()->getMessage($order);;
                $data  = '?method=sendSMS';
                $data  .= '&username=' . urlencode($username);
                $data .= '&password=' . urlencode($password);
                $data .= '&destination=' . urlencode($smsto);
                $data .= '&originator=' . urlencode($smsfrom);
                $data .= '&message=' . urlencode($smsmsg);
                $data .= '&validity=300';
                $url = $host.$path.$data;
                $sendSms = $this->getHelper()->voodoo($url);
            	try {
					Mage::getModel('voodoo/voodoo')
						->setOrderId($order->getIncrementId())
						->setFrom($smsfrom)
						->setTo($smsto)
						->setSmsMessage($smsmsg)
						->setStatus($sendSms['status'])
						->setStatusMessage($sendSms['status_message'])
						->setCreatedTime(now())
						->save();
				}
                catch (Exception $e) {}

				if($this->getHelper()->isOrdersNotify() and $this->getHelper()->getAdminTelephone()) {
					$smsto = $this->getHelper()->getAdminTelephone();
					$smsmsg = Mage::helper('voodoo')->__('A new order has been placed: %s',$order->getIncrementId());
                    $data  = '?method=sendSMS';
                    $data  .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host.$path.$data;
                    $sendSms = $this->getHelper()->voodoo($url);
					try {
						Mage::getModel('voodoo/voodoo')
							->setOrderId($order->getIncrementId())
							->setFrom($smsfrom)
							->setTo($smsto)
							->setSmsMessage($smsmsg)
							->setStatus($sendSms['status'])
							->setStatusMessage($sendSms['status_message'])
							->setCreatedTime(now())
							->save();
					}
					catch (Exception $e) {}
				}
			}
		}
	}
	
	public function sendSmsOnOrderHold(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrderHoldEnabled()) {
			$order = $observer->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
				if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
					$username = $this->getHelper()->getUsername();
					$password = $this->getHelper()->getPassword();
					$smsto = $this->getHelper()->getTelephoneFromOrder($order);
					$smsfrom = $this->getHelper()->getSenderForOrderHold();
					$smsmsg = $this->getHelper()->getMessageForOrderHold($order);
                    $data  = '?method=sendSMS';
                    $data  .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host.$path.$data;
                    $sendSms = $this->getHelper()->voodoo($url);
					try {
						Mage::getModel('voodoo/voodoo')
							->setOrderId($order->getIncrementId())
							->setFrom($smsfrom)
							->setTo($smsto)
							->setSmsMessage($smsmsg)
							->setStatus($sendSms['status'])
							->setStatusMessage($sendSms['status_message'])
							->setCreatedTime(now())
							->save();
					}
					catch (Exception $e) {}
                    if($this->getHelper()->isOrdersHoldNotify() and $this->getHelper()->getAdminHoldTelephone()) {
                        $smsto = $this->getHelper()->getAdminHoldTelephone();
                        $smsmsg = Mage::helper('voodoo')->__('%s has been placed on hold',$order->getIncrementId());
                        $data  = '?method=sendSMS';
                        $data  .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host.$path.$data;
                        $sendSms = $this->getHelper()->voodoo($url);
                        try {
                            Mage::getModel('voodoo/voodoo')
                                ->setOrderId($order->getIncrementId())
                                ->setFrom($smsfrom)
                                ->setTo($smsto)
                                ->setSmsMessage($smsmsg)
                                ->setStatus($sendSms['status'])
                                ->setStatusMessage($sendSms['status_message'])
                                ->setCreatedTime(now())
                                ->save();
                        }
                        catch (Exception $e) {}
                    }
				}
			}
		}
	}
	
	public function sendSmsOnOrderUnhold(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrderUnholdEnabled()) {
			$order = $observer->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
				if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
					$username = $this->getHelper()->getUsername();
					$password = $this->getHelper()->getPassword();
					$smsto = $this->getHelper()->getTelephoneFromOrder($order);
					$smsfrom = $this->getHelper()->getSenderForOrderUnhold();
					$smsmsg = $this->getHelper()->getMessageForOrderUnhold($order);
                    $data  = '?method=sendSMS';
                    $data  .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host.$path.$data;
                    $sendSms = $this->getHelper()->voodoo($url);
					try {
						Mage::getModel('voodoo/voodoo')
							->setOrderId($order->getIncrementId())
							->setFrom($smsfrom)
							->setTo($smsto)
							->setSmsMessage($smsmsg)
							->setStatus($sendSms['status'])
							->setStatusMessage($sendSms['status_message'])
							->setCreatedTime(now())
							->save();
					}
					catch (Exception $e) {}
                    if($this->getHelper()->isOrdersUnholdNotify() and $this->getHelper()->getAdminUnholdTelephone()) {
                        $smsto = $this->getHelper()->getAdminUnholdTelephone();
                        $smsmsg = Mage::helper('voodoo')->__('%s has been placed on unhold',$order->getIncrementId());
                        $data  = '?method=sendSMS';
                        $data  .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host.$path.$data;
                        $sendSms = $this->getHelper()->voodoo($url);
                        try {
                            Mage::getModel('voodoo/voodoo')
                                ->setOrderId($order->getIncrementId())
                                ->setFrom($smsfrom)
                                ->setTo($smsto)
                                ->setSmsMessage($smsmsg)
                                ->setStatus($sendSms['status'])
                                ->setStatusMessage($sendSms['status_message'])
                                ->setCreatedTime(now())
                                ->save();
                        }
                        catch (Exception $e) {}
                    }
				}
			}
		}
	}
	
	public function sendSmsOnOrderCanceled(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrderCanceledEnabled()) {
			$order = $observer->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
				if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_CANCELED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
					$username = $this->getHelper()->getUsername();
					$password = $this->getHelper()->getPassword();
					$smsto = $this->getHelper()->getTelephoneFromOrder($order);
					$smsfrom = $this->getHelper()->getSenderForOrderCanceled();
					$smsmsg = $this->getHelper()->getMessageForOrderCanceled($order);
                    $data  = '?method=sendSMS';
                    $data  .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host.$path.$data;
                    $sendSms = $this->getHelper()->voodoo($url);
					try {
						Mage::getModel('voodoo/voodoo')
							->setOrderId($order->getIncrementId())
							->setFrom($smsfrom)
							->setTo($smsto)
							->setSmsMessage($smsmsg)
							->setStatus($sendSms['status'])
							->setStatusMessage($sendSms['status_message'])
							->setCreatedTime(now())
							->save();
					}
					catch (Exception $e) {}
                    if($this->getHelper()->isOrdersCancelledNotify() and $this->getHelper()->getAdminCancelledTelephone()) {
                        $smsto = $this->getHelper()->getAdminCancelledTelephone();
                        $smsmsg = Mage::helper('voodoo')->__('%s has been placed cancelled',$order->getIncrementId());
                        $data  = '?method=sendSMS';
                        $data  .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host.$path.$data;
                        $sendSms = $this->getHelper()->voodoo($url);
                        try {
                            Mage::getModel('voodoo/voodoo')
                                ->setOrderId($order->getIncrementId())
                                ->setFrom($smsfrom)
                                ->setTo($smsto)
                                ->setSmsMessage($smsmsg)
                                ->setStatus($sendSms['status'])
                                ->setStatusMessage($sendSms['status_message'])
                                ->setCreatedTime(now())
                                ->save();
                        }
                        catch (Exception $e) {}
                    }
				}
			}
		}
	}
	
	public function sendSmsOnShipmentCreated(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isShipmentsEnabled()) {
			$shipment = $observer->getEvent()->getShipment();
			$order = $shipment->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
                $host = "http://www.voodoosms.com/";
                $path = "vsapi/server.php";
				$username = $this->getHelper()->getUsername();
				$password = $this->getHelper()->getPassword();
				$smsto = $this->getHelper()->getTelephoneFromOrder($order);
				$smsfrom = $this->getHelper()->getSenderForShipment();
				$smsmsg = $this->getHelper()->getMessageForShipment($order);
                $data  = '?method=sendSMS';
                $data  .= '&username=' . urlencode($username);
                $data .= '&password=' . urlencode($password);
                $data .= '&destination=' . urlencode($smsto);
                $data .= '&originator=' . urlencode($smsfrom);
                $data .= '&message=' . urlencode($smsmsg);
                $data .= '&validity=300';
                $url = $host.$path.$data;
                $sendSms = $this->getHelper()->voodoo($url);
				try {
					Mage::getModel('voodoo/voodoo')
						->setOrderId($order->getIncrementId())
						->setFrom($smsfrom)
						->setTo($smsto)
						->setSmsMessage($smsmsg)
						->setStatus($sendSms['status'])
						->setStatusMessage($sendSms['status_message'])
						->setCreatedTime(now())
						->save();
				}
                catch (Exception $e) {}
                if($this->getHelper()->isOrdersShipmentsNotify() and $this->getHelper()->getAdminShipmentsTelephone()) {
                    $smsto = $this->getHelper()->getAdminTelephone();
                    $smsmsg = Mage::helper('voodoo')->__('%s is on shipment state',$order->getIncrementId());
                    $data  = '?method=sendSMS';
                    $data  .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host.$path.$data;
                    $sendSms = $this->getHelper()->voodoo($url);
                    try {
                        Mage::getModel('voodoo/voodoo')
                            ->setOrderId($order->getIncrementId())
                            ->setFrom($smsfrom)
                            ->setTo($smsto)
                            ->setSmsMessage($smsmsg)
                            ->setStatus($sendSms['status'])
                            ->setStatusMessage($sendSms['status_message'])
                            ->setCreatedTime(now())
                            ->save();
                    }
                    catch (Exception $e) {}
                }
			}
		}
	}

	public function getHelper()
    {
        return Mage::helper('voodoo/Data');
    }
}