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
                    if ($this->getHelper()->isbillingorshipping() == 0) {
                        $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                    } else {
                        $smsto = $order->getShippingAddress()->getTelephone();
                    }
                }
                if ($smsto) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vapi/server/sendSMS";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSender();
                    $smsmsg = $this->getHelper()->getMessage($order);
                    $data = '?uid=' . urlencode($username);
                    $data .= '&pass=' . urlencode($password);
                    $data .= '&dest=' . urlencode($smsto);
                    $data .= '&orig=' . urlencode($smsfrom);
                    $data .= '&msg=' . urlencode($smsmsg);
                    $data .= '&validity=300&format=json';
                    $url = $host . $path . $data;
                    $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                    $verify_number = $this->getHelper()->verify_number($verify_number_url);
                    if ($verify_number->result == 200) {
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
        if ($this->getHelper()->isOrdersNotify() and $this->getHelper()->getAdminTelephone()) {
            $orders = $observer->getEvent()->getOrderIds();
            $order = Mage::getModel('sales/order')->load($orders['0']);
            if ($order instanceof Mage_Sales_Model_Order) {
                $host = "http://www.voodoosms.com/";
                $path = "vapi/server/sendSMS";
                $username = $this->getHelper()->getUsername();
                $password = $this->getHelper()->getPassword();
                $smsfrom = $this->getHelper()->getSender();
                $smsto = $this->getHelper()->getAdminTelephone();
                $smsmsg = Mage::helper('voodoo')->__('A new order has been placed: %s', $order->getIncrementId());
                $data = '?uid=' . urlencode($username);
                $data .= '&pass=' . urlencode($password);
                $data .= '&dest=' . urlencode($smsto);
                $data .= '&orig=' . urlencode($smsfrom);
                $data .= '&msg=' . urlencode($smsmsg);
                $data .= '&validity=300&format=json';
                $url = $host . $path . $data;
                $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                $verify_number = $this->getHelper()->verify_number($verify_number_url);
                if ($verify_number->result == 200) {
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


    public function sendSmsOnOrderHold(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrderHoldEnabled()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED) {
                    if ($this->getHelper()->isOptinsEnabled()) {
                        $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                        if ($smsto == '') {
                            $resource = Mage::getSingleton('core/resource');
                            $readConnection = $resource->getConnection('core_read');
                            $query = "SELECT sms_number FROM voodoo_number where order_id =" . $order->getIncrementId() . " LIMIT 1";
                            $results = $readConnection->fetchRow($query);
                            $smsto = $results['sms_number'];
                            if ($smsto == '') {
                                if ($this->getHelper()->isbillingorshipping() == 0) {
                                    $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                                } else {
                                    $smsto = $order->getShippingAddress()->getTelephone();
                                }
                            }
                        }
                    } else {
                        if ($this->getHelper()->isbillingorshipping() == 0) {
                            $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                        } else {
                            $smsto = $order->getShippingAddress()->getTelephone();
                        }
                    }
                    if ($smsto) {
                        $host = "http://www.voodoosms.com/";
                        $path = "vapi/server/sendSMS";
                        $username = $this->getHelper()->getUsername();
                        $password = $this->getHelper()->getPassword();
                        $smsfrom = $this->getHelper()->getSenderForOrderHold();
                        $smsmsg = $this->getHelper()->getMessageForOrderHold($order);
                        $data = '?uid=' . urlencode($username);
                        $data .= '&pass=' . urlencode($password);
                        $data .= '&dest=' . urlencode($smsto);
                        $data .= '&orig=' . urlencode($smsfrom);
                        $data .= '&msg=' . urlencode($smsmsg);
                        $data .= '&validity=300&format=json';
                        $url = $host . $path . $data;
                        $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                        $verify_number = $this->getHelper()->verify_number($verify_number_url);
                        if ($verify_number->result == 200) {
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
        if ($this->getHelper()->isOrdersHoldNotify() and $this->getHelper()->getAdminHoldTelephone()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vapi/server/sendSMS";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSenderForOrderHold();
                    $smsto = $this->getHelper()->getAdminHoldTelephone();
                    $smsmsg = Mage::helper('voodoo')->__('%s has been placed on hold', $order->getIncrementId());
                    $data = '?uid=' . urlencode($username);
                    $data .= '&pass=' . urlencode($password);
                    $data .= '&dest=' . urlencode($smsto);
                    $data .= '&orig=' . urlencode($smsfrom);
                    $data .= '&msg=' . urlencode($smsmsg);
                    $data .= '&validity=300&format=json';
                    $url = $host . $path . $data;
                    $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                    $verify_number = $this->getHelper()->verify_number($verify_number_url);
                    if ($verify_number->result == 200) {
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

    public function sendSmsOnOrderUnhold(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrderUnholdEnabled()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED) {
                    if ($this->getHelper()->isOptinsEnabled()) {
                        $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                        if ($smsto == '') {
                            $resource = Mage::getSingleton('core/resource');
                            $readConnection = $resource->getConnection('core_read');
                            $query = "SELECT sms_number FROM voodoo_number where order_id =" . $order->getIncrementId() . " LIMIT 1";
                            $results = $readConnection->fetchRow($query);
                            $smsto = $results['sms_number'];
                            if ($smsto == '') {
                                if ($this->getHelper()->isbillingorshipping() == 0) {
                                    $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                                } else {
                                    $smsto = $order->getShippingAddress()->getTelephone();
                                }
                            }
                        }
                    } else {
                        if ($this->getHelper()->isbillingorshipping() == 0) {
                            $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                        } else {
                            $smsto = $order->getShippingAddress()->getTelephone();
                        }
                    }
                    if ($smsto) {
                        $host = "http://www.voodoosms.com/";
                        $path = "vapi/server/sendSMS";
                        $username = $this->getHelper()->getUsername();
                        $password = $this->getHelper()->getPassword();
                        $smsfrom = $this->getHelper()->getSenderForOrderUnhold();
                        $smsmsg = $this->getHelper()->getMessageForOrderUnhold($order);
                        $data = '?uid=' . urlencode($username);
                        $data .= '&pass=' . urlencode($password);
                        $data .= '&dest=' . urlencode($smsto);
                        $data .= '&orig=' . urlencode($smsfrom);
                        $data .= '&msg=' . urlencode($smsmsg);
                        $data .= '&validity=300&format=json';
                        $url = $host . $path . $data;
                        $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                        $verify_number = $this->getHelper()->verify_number($verify_number_url);
                        if ($verify_number->result == 200) {
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
        if ($this->getHelper()->isOrdersUnholdNotify() and $this->getHelper()->getAdminUnholdTelephone()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vapi/server/sendSMS";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSenderForOrderUnhold();
                    $smsto = $this->getHelper()->getAdminUnholdTelephone();
                    $smsmsg = Mage::helper('voodoo')->__('%s has been placed on unhold', $order->getIncrementId());
                    $data = '?uid=' . urlencode($username);
                    $data .= '&pass=' . urlencode($password);
                    $data .= '&dest=' . urlencode($smsto);
                    $data .= '&orig=' . urlencode($smsfrom);
                    $data .= '&msg=' . urlencode($smsmsg);
                    $data .= '&validity=300&format=json';
                    $url = $host . $path . $data;

                    $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                    $verify_number = $this->getHelper()->verify_number($verify_number_url);
                    if ($verify_number->result == 200) {
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

    public function getHelper()
    {
        return Mage::helper('voodoo/Data');
    }

    public function sendSmsOnOrderCanceled(Varien_Event_Observer $observer)
    {
        if ($this->getHelper()->isOrderCanceledEnabled()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_CANCELED) {
                    if ($this->getHelper()->isOptinsEnabled()) {
                        $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                        if ($smsto == '') {
                            $resource = Mage::getSingleton('core/resource');
                            $readConnection = $resource->getConnection('core_read');
                            $query = "SELECT sms_number FROM voodoo_number where order_id =" . $order->getIncrementId() . " LIMIT 1";
                            $results = $readConnection->fetchRow($query);
                            $smsto = $results['sms_number'];
                            if ($smsto == '') {
                                if ($this->getHelper()->isbillingorshipping() == 0) {
                                    $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                                } else {
                                    $smsto = $order->getShippingAddress()->getTelephone();
                                }
                            }
                        }
                    } else {
                        if ($this->getHelper()->isbillingorshipping() == 0) {
                            $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                        } else {
                            $smsto = $order->getShippingAddress()->getTelephone();
                        }
                    }
                    if ($smsto) {
                        $host = "http://www.voodoosms.com/";
                        $path = "vapi/server/sendSMS";
                        $username = $this->getHelper()->getUsername();
                        $password = $this->getHelper()->getPassword();
                        $smsfrom = $this->getHelper()->getSenderForOrderCanceled();
                        $smsmsg = $this->getHelper()->getMessageForOrderCanceled($order);
                        $data = '?uid=' . urlencode($username);
                        $data .= '&pass=' . urlencode($password);
                        $data .= '&dest=' . urlencode($smsto);
                        $data .= '&orig=' . urlencode($smsfrom);
                        $data .= '&msg=' . urlencode($smsmsg);
                        $data .= '&validity=300&format=json';
                        $url = $host . $path . $data;
                        $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                        $verify_number = $this->getHelper()->verify_number($verify_number_url);
                        if ($verify_number->result == 200) {
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
        if ($this->getHelper()->isOrdersCancelledNotify() and $this->getHelper()->getAdminCancelledTelephone()) {
            $order = $observer->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_CANCELED) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vapi/server/sendSMS";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSenderForOrderCanceled();
                    $smsto = $this->getHelper()->getAdminCancelledTelephone();
                    $smsmsg = Mage::helper('voodoo')->__('%s has been placed cancelled', $order->getIncrementId());
                    $data = '?uid=' . urlencode($username);
                    $data .= '&pass=' . urlencode($password);
                    $data .= '&dest=' . urlencode($smsto);
                    $data .= '&orig=' . urlencode($smsfrom);
                    $data .= '&msg=' . urlencode($smsmsg);
                    $data .= '&validity=300&format=json&format=json';
                    $url = $host . $path . $data;
                    $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                    $verify_number = $this->getHelper()->verify_number($verify_number_url);
                    if ($verify_number->result == 200) {
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

    public function sendSmsOnShipmentCreated(Varien_Event_Observer $observer)
    {
        $shipment = $observer->getEvent();
        if ($this->getHelper()->isShipmentsEnabled()) {
            $shipment = $observer->getEvent()->getShipment();
            $order = $shipment->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                if ($this->getHelper()->isOptinsEnabled()) {
                    $smsto = Mage::getSingleton('core/session')->getTGMVoodoo();
                    if ($smsto == '') {
                        $resource = Mage::getSingleton('core/resource');
                        $readConnection = $resource->getConnection('core_read');
                        $query = "SELECT sms_number FROM voodoo_number where order_id =" . $order->getIncrementId() . " LIMIT 1";
                        $results = $readConnection->fetchRow($query);
                        $smsto = $results['sms_number'];
                        if ($smsto == '') {
                            if ($this->getHelper()->isbillingorshipping() == 0) {
                                $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                            } else {
                                $smsto = $order->getShippingAddress()->getTelephone();
                            }
                        }
                    }
                } else {
                    if ($this->getHelper()->isbillingorshipping() == 0) {
                        $smsto = $this->getHelper()->getTelephoneFromOrder($order);
                    } else {
                        $smsto = $order->getShippingAddress()->getTelephone();
                    }
                }
                if ($smsto) {
                    $host = "http://www.voodoosms.com/";
                    $path = "vapi/server/sendSMS";
                    $username = $this->getHelper()->getUsername();
                    $password = $this->getHelper()->getPassword();
                    $smsfrom = $this->getHelper()->getSenderForShipment();
                    $smsmsg = $this->getHelper()->getMessageForShipment($order);
                    $data = '?uid=' . urlencode($username);
                    $data .= '&pass=' . urlencode($password);
                    $data .= '&dest=' . urlencode($smsto);
                    $data .= '&orig=' . urlencode($smsfrom);
                    $data .= '&msg=' . urlencode($smsmsg);
                    $data .= '&validity=300&format=json';
                    $url = $host . $path . $data;
                    $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                    $verify_number = $this->getHelper()->verify_number($verify_number_url);
                    if ($verify_number->result == 200) {
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
        if ($this->getHelper()->isOrdersShipmentsNotify() and $this->getHelper()->getAdminShipmentsTelephone()) {
            $shipment = $observer->getEvent()->getShipment();
            $order = $shipment->getOrder();
            if ($order instanceof Mage_Sales_Model_Order) {
                $host = "http://www.voodoosms.com/";
                $path = "vapi/server/sendSMS";
                $username = $this->getHelper()->getUsername();
                $password = $this->getHelper()->getPassword();
                $smsfrom = $this->getHelper()->getSenderForShipment();
                $smsto = $this->getHelper()->getAdminTelephone();
                $smsmsg = Mage::helper('voodoo')->__('%s is on shipment state', $order->getIncrementId());
                $data = '?uid=' . urlencode($username);
                $data .= '&pass=' . urlencode($password);
                $data .= '&dest=' . urlencode($smsto);
                $data .= '&orig=' . urlencode($smsfrom);
                $data .= '&msg=' . urlencode($smsmsg);
                $data .= '&validity=300&format=json';
                $url = $host . $path . $data;
                $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                $verify_number = $this->getHelper()->verify_number($verify_number_url);
                if ($verify_number->result == 200) {
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


    public function sendSmsOnM2EOrderCreatedAuto(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        $data['ebay_order_id'] = $order->getData('ebay_order_id');
        $data['buyer_email'] = $order->getData('buyer_email');
        $data['buyer_name'] = $order->getData('buyer_name');
        $data['paid_amount'] = $order->getData('paid_amount');
        $data['currency'] = $order->getData('currency');
        $data['shipping_details'] = json_decode($order->getData('shipping_details'));
        $data['payment_details'] = json_decode($order->getData('payment_details'));
        $smsto = str_replace(' ', '', $data['shipping_details']->address->phone);
        //echo '<pre>';print_r($data);echo '</pre>';die('ob_pay');
        if ($this->getHelper()->isM2eEnabled()) {
            $host = "http://www.voodoosms.com/";
            $path = "vapi/server/sendSMS";
            $username = $this->getHelper()->getUsername();
            $password = $this->getHelper()->getPassword();
            $smsfrom = $this->getHelper()->getSenderForM2e();
            $smsmsg = $this->getHelper()->getMessageForM2eShip($data);
            $data = '?uid=' . urlencode($username);
            $data .= '&pass=' . urlencode($password);
            $data .= '&dest=' . urlencode($smsto);
            $data .= '&orig=' . urlencode($smsfrom);
            $data .= '&msg=' . urlencode($smsmsg);
            $data .= '&validity=300&format=json';
            $url = $host . $path . $data;
            $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
            $verify_number = $this->getHelper()->verify_number($verify_number_url);
            if ($verify_number->result == 200) {
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
            if ($this->getHelper()->isOrdersM2eNotify() and $this->getHelper()->getAdminM2eTelephone()) {
                $smsto = $this->getHelper()->getAdminM2eTelephone();
                $smsmsg = Mage::helper('voodoo')->__('A new m2e order # %s is placed', $data['ebay_order_id']);
                $data = '?uid=' . urlencode($username);
                $data .= '&pass=' . urlencode($password);
                $data .= '&dest=' . urlencode($smsto);
                $data .= '&orig=' . urlencode($smsfrom);
                $data .= '&msg=' . urlencode($smsmsg);
                $data .= '&validity=300&format=json';
                $url = $host . $path . $data;
                $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                $verify_number = $this->getHelper()->verify_number($verify_number_url);
                if ($verify_number->result == 200) {
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

    public function sendSmsOnM2EOrderCreated($order)
    {
        $data['ebay_order_id'] = $order->getData('ebay_order_id');
        $data['buyer_email'] = $order->getData('buyer_email');
        $data['buyer_name'] = $order->getData('buyer_name');
        $data['paid_amount'] = $order->getData('paid_amount');
        $data['currency'] = $order->getData('currency');
        $data['shipping_details'] = json_decode($order->getData('shipping_details'));
        $data['payment_details'] = json_decode($order->getData('payment_details'));
        $smsto = str_replace(' ', '', $data['shipping_details']->address->phone);
        //echo '<pre>';print_r($data);echo '</pre>';die('ob_pay');
        if ($this->getHelper()->isM2eEnabled()) {
            $host = "http://www.voodoosms.com/";
            $path = "vapi/server/sendSMS";
            $username = $this->getHelper()->getUsername();
            $password = $this->getHelper()->getPassword();
            $smsfrom = $this->getHelper()->getSenderForM2e();
            $smsmsg = $this->getHelper()->getMessageForM2eShip($data);
            $data = '?uid=' . urlencode($username);
            $data .= '&pass=' . urlencode($password);
            $data .= '&dest=' . urlencode($smsto);
            $data .= '&orig=' . urlencode($smsfrom);
            $data .= '&msg=' . urlencode($smsmsg);
            $data .= '&validity=300&format=json';
            $url = $host . $path . $data;
            $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
            $verify_number = $this->getHelper()->verify_number($verify_number_url);
            if ($verify_number->result == 200) {
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
            if ($this->getHelper()->isOrdersM2eNotify() and $this->getHelper()->getAdminM2eTelephone()) {
                $smsto = $this->getHelper()->getAdminM2eTelephone();
                $smsmsg = Mage::helper('voodoo')->__('A new m2e order # %s is placed', $data['ebay_order_id']);
                $data = '?uid=' . urlencode($username);
                $data .= '&pass=' . urlencode($password);
                $data .= '&dest=' . urlencode($smsto);
                $data .= '&orig=' . urlencode($smsfrom);
                $data .= '&msg=' . urlencode($smsmsg);
                $data .= '&validity=300&format=json';
                $url = $host . $path . $data;
                $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                $verify_number = $this->getHelper()->verify_number($verify_number_url);
                if ($verify_number->result == 200) {
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

    public function sendSmsOnM2EShipmentCreated($order)
    {

        $data['ebay_order_id'] = $order->getData('ebay_order_id');
        $data['buyer_email'] = $order->getData('buyer_email');
        $data['buyer_name'] = $order->getData('buyer_name');
        $data['paid_amount'] = $order->getData('paid_amount');
        $data['currency'] = $order->getData('currency');
        $data['shipping_details'] = json_decode($order->getData('shipping_details'));
        $data['payment_details'] = json_decode($order->getData('payment_details'));
        $smsto = str_replace(' ', '', $data['shipping_details']->address->phone);
        //echo '<pre>';print_r($data);echo '</pre>';die('ob_pay');
        if ($this->getHelper()->isM2eEnabled() && $this->getHelper()->isM2eShipEnabled()) {
            $host = "http://www.voodoosms.com/";
            $path = "vapi/server/sendSMS";
            $username = $this->getHelper()->getUsername();
            $password = $this->getHelper()->getPassword();
            $smsfrom = $this->getHelper()->getSenderForM2e();
            $smsmsg = $this->getHelper()->getMessageForM2eShip($data);
            $data = '?uid=' . urlencode($username);
            $data .= '&pass=' . urlencode($password);
            $data .= '&dest=' . urlencode($smsto);
            $data .= '&orig=' . urlencode($smsfrom);
            $data .= '&msg=' . urlencode($smsmsg);
            $data .= '&validity=300&format=json';
            $url = $host . $path . $data;
            $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
            $verify_number = $this->getHelper()->verify_number($verify_number_url);
            if ($verify_number->result == 200) {
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
            if ($this->getHelper()->isOrdersM2eNotify() and $this->getHelper()->getAdminM2eTelephone()) {
                $smsto = $this->getHelper()->getAdminM2eTelephone();
                $smsmsg = Mage::helper('voodoo')->__('m2e order # %s is now Shipped', $data['ebay_order_id']);
                $data = '?uid=' . urlencode($username);
                $data .= '&pass=' . urlencode($password);
                $data .= '&dest=' . urlencode($smsto);
                $data .= '&orig=' . urlencode($smsfrom);
                $data .= '&msg=' . urlencode($smsmsg);
                $data .= '&validity=300&format=json';
                $url = $host . $path . $data;
                $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                $verify_number = $this->getHelper()->verify_number($verify_number_url);

                if ($verify_number->result == 200) {
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


    public function sendSmsOnM2EPaymentCreated($order)
    {

        $data['ebay_order_id'] = $order->getData('ebay_order_id');
        $data['buyer_email'] = $order->getData('buyer_email');
        $data['buyer_name'] = $order->getData('buyer_name');
        $data['paid_amount'] = $order->getData('paid_amount');
        $data['currency'] = $order->getData('currency');
        $data['shipping_details'] = json_decode($order->getData('shipping_details'));
        $data['payment_details'] = json_decode($order->getData('payment_details'));
        $smsto = str_replace(' ', '', $data['shipping_details']->address->phone);
        //echo '<pre>';print_r($data);echo '</pre>';die('ob_pay');
        if ($this->getHelper()->isM2eEnabled() && $this->getHelper()->isM2ePayEnabled()) {
            $host = "http://www.voodoosms.com/";
            $path = "vapi/server/sendSMS";
            $username = $this->getHelper()->getUsername();
            $password = $this->getHelper()->getPassword();
            $smsfrom = $this->getHelper()->getSenderForM2e();
            $smsmsg = $this->getHelper()->getMessageForM2ePay($data);
            $data = '?uid=' . urlencode($username);
            $data .= '&pass=' . urlencode($password);
            $data .= '&dest=' . urlencode($smsto);
            $data .= '&orig=' . urlencode($smsfrom);
            $data .= '&msg=' . urlencode($smsmsg);
            $data .= '&validity=300&format=json';
            $url = $host . $path . $data;
            $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
            $verify_number = $this->getHelper()->verify_number($verify_number_url);
            if ($verify_number->result == 200) {
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
            if ($this->getHelper()->isOrdersM2eNotify() and $this->getHelper()->getAdminM2eTelephone()) {
                $smsto = $this->getHelper()->getAdminM2eTelephone();
                $smsmsg = Mage::helper('voodoo')->__('m2e order # %s is paid', $data['ebay_order_id']);
                $data = '?uid=' . urlencode($username);
                $data .= '&pass=' . urlencode($password);
                $data .= '&dest=' . urlencode($smsto);
                $data .= '&orig=' . urlencode($smsfrom);
                $data .= '&msg=' . urlencode($smsmsg);
                $data .= '&validity=300&format=json';
                $url = $host . $path . $data;
                $verify_number_url = "http://voodoosms.com/vapi/server/checkRanges?uid=" . urlencode($username) . "&pass=" . urlencode($password) . "&dest=" . urlencode($smsto) . "&format=json";
                $verify_number = $this->getHelper()->verify_number($verify_number_url);
                if ($verify_number->result == 200) {
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

    /**
     * Event Hook: checkout_type_onepage_save_order
     *
     * @author Ammar
     *
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
        if ($_voodoo_data != null) {
            $write = Mage::getSingleton("core/resource")->getConnection("core_write");

// Concatenated with . for readability
            $query = "insert into voodoo_number "
                . "(order_id, sms_number) values "
                . "(:order_id, :sms_number)";

            $binds = array(
                'order_id'   => $incrementId,
                'sms_number' => $_voodoo_data,
            );
            $write->query($query, $binds);
        }

        //Save fhc id to order obcject
        $order->setData(self::ORDER_ATTRIBUTE_FHC_ID, $_voodoo_data);
        $order->save();

    }
}