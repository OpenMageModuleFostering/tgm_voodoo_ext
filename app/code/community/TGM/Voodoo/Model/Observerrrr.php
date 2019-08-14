<?php
class TGM_Voodoo_Model_Observerrrr
{
	public function sendSmsOnOrderCreated(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isEnabled(1)) {
            $orders = $observer->getEvent()->getOrderIds();
			$order = Mage::getModel('sales/order')->load($orders['0']);
			if ($order instanceof Mage_Sales_Model_Order) {
                $host = "http://www.voodoosms.com/";
                $path = "vsapi/server.php";
                $username = $this->getHelper()->getUsername();
                $password = $this->getHelper()->getPassword();
                $smsto = $this->getHelper()->getDestination($order);
                $smsfrom = $this->getHelper()->getSender(1);
                $smsmsg = $this->getHelper()->getMessage($order,1);;
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

				if($this->getHelper()->isNotifyEnabled(1) and $this->getHelper()->getAdminDestination(1)) {
					$smsto = $this->getHelper()->getAdminDestination(1);
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
        if($this->getHelper()->isEnabled(2)) {
			$order = $observer->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
				if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();;
                    $smsto = $this->getHelper()->getDestination($order);
                    $smsfrom = $this->getHelper()->getSender(2);
                    $smsmsg = $this->getHelper()->getMessage($order,2);
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
                    if($this->getHelper()->isNotifyEnabled(2) and $this->getHelper()->getAdminDestination(2)) {
                        $smsto = $this->getHelper()->getAdminDestination(2);
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
        if($this->getHelper()->isEnabled(3)) {
			$order = $observer->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
				if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();;
                    $smsto = $this->getHelper()->getDestination($order);
                    $smsfrom = $this->getHelper()->getSender(3);
                    $smsmsg = $this->getHelper()->getMessage($order,3);
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
                    if($this->getHelper()->isNotifyEnabled(3) and $this->getHelper()->getAdminDestination(3)) {
                    $smsto = $this->getHelper()->getAdminDestination(3);
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
        if($this->getHelper()->isEnabled(4)) {
			$order = $observer->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
				if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_CANCELED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();;
                    $smsto = $this->getHelper()->getDestination($order);
                    $smsfrom = $this->getHelper()->getSender(4);
                    $smsmsg = $this->getHelper()->getMessage($order,4);
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
                    if($this->getHelper()->isNotifyEnabled(4) and $this->getHelper()->getAdminDestination(4)) {
                        $smsto = $this->getHelper()->getAdminDestination(4);
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
        if($this->getHelper()->isEnabled(5)) {
			$shipment = $observer->getEvent()->getShipment();
			$order = $shipment->getOrder();
			if ($order instanceof Mage_Sales_Model_Order) {
                $host = "http://www.voodoosms.com/";
                $path = "vsapi/server.php";
                $username = $this->getHelper()->getUsername();
                $password = $this->getHelper()->getPassword();;
                $smsto = $this->getHelper()->getDestination($order);
                $smsfrom = $this->getHelper()->getSender(5);
                $smsmsg = $this->getHelper()->getMessage($order,5);
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
                if($this->getHelper()->isNotifyEnabled(5) and $this->getHelper()->getAdminDestination(5)) {
                    $smsto = $this->getHelper()->getAdminDestination(5);
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