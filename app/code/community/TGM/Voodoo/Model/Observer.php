<?php
class TGM_Voodoo_Model_Observer
{
    const ORDER_ATTRIBUTE_FHC_ID = 'voodoo';

    public function sendSmsOnOrderCreated(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrdersEnabled()) {
            $orders = $observer->getEvent()->getOrderIds();
            $order = Mage::getModel('sales/order')->load($orders['0']);
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($this->getHelper()->isOptinsEnabled()) {
                    $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                } else {
                    if ($this->getHelper()->isbillingorshipping()==0) {
                        $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                    }else{
                        $smsto = $order->getShippingAddress()->getTelephone();
                    }
                }
                if ($smsto) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSender();
                    $smsmsg = $this->getHelper()->getMessage($order);
                    $data = '?method=sendSMS';
                    $data .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host . $path . $data;
                    //$sendSms = $this->getHelper()->exportOrder($order,$url);
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
                    } catch (Exception $e) {
                    }

                    if ($this->getHelper()->isOrdersNotify() and $this->getHelper()->getAdminTelephone()) {
                        $smsto = $this->getHelper()->getAdminTelephone();
                        $smsmsg = Mage::helper('voodoo')->__('A new order has been placed: %s', $order->getIncrementId());
                        $data = '?method=sendSMS';
                        $data .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host . $path . $data;
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
                        } catch (Exception $e) {
                        }
                    }
                }
            }
        }
    }

    public function sendSmsOnOrderHold(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrderHoldEnabled()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED) {
                    if ($this->getHelper()->isOptinsEnabled()) {
                        $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                    } else {
                        if ($this->getHelper()->isbillingorshipping()==0) {
                            $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                        }else{
                            $smsto = $order->getShippingAddress()->getTelephone();
                        }
                    }
                    if ($smsto) {
                        $host = "http://www.voodoosms.com/";
                        $path = "vsapi/server.php";
                        $username = $this->getHelper()->getUsername();
                        $password = $this->getHelper()->getPassword();
                        $smsfrom = $this->getHelper()->getSenderForOrderHold();
                        $smsmsg = $this->getHelper()->getMessageForOrderHold($order);
                        $data = '?method=sendSMS';
                        $data .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host . $path . $data;
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
                        } catch (Exception $e) {
                        }
                        if ($this->getHelper()->isOrdersHoldNotify() and $this->getHelper()->getAdminHoldTelephone()) {
                            $smsto = $this->getHelper()->getAdminHoldTelephone();
                            $smsmsg = Mage::helper('voodoo')->__('%s has been placed on hold', $order->getIncrementId());
                            $data = '?method=sendSMS';
                            $data .= '&username=' . urlencode($username);
                            $data .= '&password=' . urlencode($password);
                            $data .= '&destination=' . urlencode($smsto);
                            $data .= '&originator=' . urlencode($smsfrom);
                            $data .= '&message=' . urlencode($smsmsg);
                            $data .= '&validity=300';
                            $url = $host . $path . $data;
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
                            } catch (Exception $e) {
                            }
                        }
                    }
                }
            }
        }
    }

    public function getHelper()
    {
        return Mage::helper('voodoo/Data');
    }

    public function sendSmsOnOrderUnhold(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrderUnholdEnabled()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED) {
                    if ($this->getHelper()->isOptinsEnabled()) {
                        $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                    } else {
                        if ($this->getHelper()->isbillingorshipping()==0) {
                            $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                        }else{
                            $smsto = $order->getShippingAddress()->getTelephone();
                        }
                    }
                    if ($smsto) {
                        $host = "http://www.voodoosms.com/";
                        $path = "vsapi/server.php";
                        $username = $this->getHelper()->getUsername();
                        $password = $this->getHelper()->getPassword();
                        $smsfrom = $this->getHelper()->getSenderForOrderUnhold();
                        $smsmsg = $this->getHelper()->getMessageForOrderUnhold($order);
                        $data = '?method=sendSMS';
                        $data .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host . $path . $data;
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
                        } catch (Exception $e) {
                        }
                        if ($this->getHelper()->isOrdersUnholdNotify() and $this->getHelper()->getAdminUnholdTelephone()) {
                            $smsto = $this->getHelper()->getAdminUnholdTelephone();
                            $smsmsg = Mage::helper('voodoo')->__('%s has been placed on unhold', $order->getIncrementId());
                            $data = '?method=sendSMS';
                            $data .= '&username=' . urlencode($username);
                            $data .= '&password=' . urlencode($password);
                            $data .= '&destination=' . urlencode($smsto);
                            $data .= '&originator=' . urlencode($smsfrom);
                            $data .= '&message=' . urlencode($smsmsg);
                            $data .= '&validity=300';
                            $url = $host . $path . $data;
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
                            } catch (Exception $e) {
                            }
                        }
                    }

                }
            }
        }
    }

    public function sendSmsOnOrderCanceled(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrderCanceledEnabled()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_CANCELED) {
                    if ($this->getHelper()->isOptinsEnabled()) {
                        $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                    } else {
                        if ($this->getHelper()->isbillingorshipping()==0) {
                            $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                        }else{
                            $smsto = $order->getShippingAddress()->getTelephone();
                        }
                    }
                    if ($smsto) {
                        $host = "http://www.voodoosms.com/";
                        $path = "vsapi/server.php";
                        $username = $this->getHelper()->getUsername();
                        $password = $this->getHelper()->getPassword();
                        $smsfrom = $this->getHelper()->getSenderForOrderCanceled();
                        $smsmsg = $this->getHelper()->getMessageForOrderCanceled($order);
                        $data = '?method=sendSMS';
                        $data .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host . $path . $data;
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
                        } catch (Exception $e) {
                        }
                        if ($this->getHelper()->isOrdersCancelledNotify() and $this->getHelper()->getAdminCancelledTelephone()) {
                            $smsto = $this->getHelper()->getAdminCancelledTelephone();
                            $smsmsg = Mage::helper('voodoo')->__('%s has been placed cancelled', $order->getIncrementId());
                            $data = '?method=sendSMS';
                            $data .= '&username=' . urlencode($username);
                            $data .= '&password=' . urlencode($password);
                            $data .= '&destination=' . urlencode($smsto);
                            $data .= '&originator=' . urlencode($smsfrom);
                            $data .= '&message=' . urlencode($smsmsg);
                            $data .= '&validity=300';
                            $url = $host . $path . $data;
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
                            } catch (Exception $e) {
                            }
                        }
                    }

                }
            }
        }
    }

    public function sendSmsOnShipmentCreated(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isShipmentsEnabled()) {
            $shipment = $observer->getEvent()->getShipment();
            $order = $shipment->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($this->getHelper()->isOptinsEnabled()) {
                    $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                } else {
                    if ($this->getHelper()->isbillingorshipping()==0) {
                        $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                    }else{
                        $smsto = $order->getShippingAddress()->getTelephone();
                    }
                }
                if ($smsto) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vsapi/server.php";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSenderForShipment();
                    $smsmsg = $this->getHelper()->getMessageForShipment($order);
                    $data = '?method=sendSMS';
                    $data .= '&username=' . urlencode($username);
                    $data .= '&password=' . urlencode($password);
                    $data .= '&destination=' . urlencode($smsto);
                    $data .= '&originator=' . urlencode($smsfrom);
                    $data .= '&message=' . urlencode($smsmsg);
                    $data .= '&validity=300';
                    $url = $host . $path . $data;
                    echo $url;
                    exit;
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
                    } catch (Exception $e) {
                    }
                    if ($this->getHelper()->isOrdersShipmentsNotify() and $this->getHelper()->getAdminShipmentsTelephone()) {
                        $smsto = $this->getHelper()->getAdminTelephone();
                        $smsmsg = Mage::helper('voodoo')->__('%s is on shipment state', $order->getIncrementId());
                        $data = '?method=sendSMS';
                        $data .= '&username=' . urlencode($username);
                        $data .= '&password=' . urlencode($password);
                        $data .= '&destination=' . urlencode($smsto);
                        $data .= '&originator=' . urlencode($smsfrom);
                        $data .= '&message=' . urlencode($smsmsg);
                        $data .= '&validity=300';
                        $url = $host . $path . $data;
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
                        } catch (Exception $e) {
                        }
                    }
                }

            }
        }
    }

    /**
     * Event Hook: checkout_type_onepage_save_order
     *
     * @author Ammar
     * @param $observer Varien_Event_Observer
     */
    public function hookToOrderSaveEvent()
    {
        /**
         * NOTE:
         * Order has already been saved, now we simply add some stuff to it,
         * that will be saved to database. We add the stuff to Order object property
         * called "voodoo"
         */
        $order = new Mage_Sales_Model_Order();
        $incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order->loadByIncrementId($incrementId);

        //Fetch the data from select box and throw it here
        $_voodoo_data = null;
        $_voodoo_data = Mage::getSingleton('core/session')->getTGMVoodoo();
        if ($_voodoo_data!=null) {
            $write = Mage::getSingleton("core/resource")->getConnection("core_write");

// Concatenated with . for readability
            $query = "insert into voodoo_number "
                . "(order_id, sms_number) values "
                . "(:order_id, :sms_number)";

            $binds = array(
                'order_id' => $incrementId,
                'sms_number' => $_voodoo_data,
            );
            $write->query($query, $binds);
        }

        //Save fhc id to order obcject
        $order->setData(self::ORDER_ATTRIBUTE_FHC_ID, $_voodoo_data);
        $order->save();

    }
}