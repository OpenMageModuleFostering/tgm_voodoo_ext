<?php
class TGM_Voodoo_Model_Ebay_Order extends Ess_M2ePro_Model_Ebay_Order
{
    private function processConnector($action, array $params = array())
    {
        /** @var $dispatcher Ess_M2ePro_Model_Connector_Ebay_Order_Dispatcher */
        $dispatcher = Mage::getModel('M2ePro/Connector_Ebay_Order_Dispatcher');

        return $dispatcher->process($action, $this->getParentObject(), $params);
    }

    public function canUpdatePaymentStatus()
    {
        // ebay restriction
        if (stripos($this->getPaymentMethod(), 'paisa') !== false) {
            return false;
        }

        return !$this->isPaymentCompleted() && !$this->isPaymentStatusUnknown();
    }

    public function updatePaymentStatus(array $params = array())
    {
        if (!$this->canUpdatePaymentStatus()) {
            return false;
        }
        mail("ammar.khan@topgearmedia.co.uk","asd","asdasdas");
        echo '<pre>';print_r($params);echo '</pre>';die('Call');
        //return $this->processConnector(Ess_M2ePro_Model_Connector_Ebay_Order_Dispatcher::ACTION_PAY, $params);
    }



    public function canUpdateShippingStatus(array $trackingDetails = array())
    {
        if (!$this->isPaymentCompleted() || $this->isShippingStatusUnknown()) {
            return false;
        }


        if (stripos($this->getPaymentMethod(), 'paisa') !== false) {
            return false;
        }

        if (!$this->isShippingMethodNotSelected() && !$this->isShippingInProcess() && empty($trackingDetails)) {
            return false;
        }

        return true;
    }

    public function updateShippingStatus(array $trackingDetails = array())
    {
        $params = array();
        $action = Ess_M2ePro_Model_Connector_Ebay_Order_Dispatcher::ACTION_SHIP;

        if (!empty($trackingDetails['tracking_number'])) {
            $action = Ess_M2ePro_Model_Connector_Ebay_Order_Dispatcher::ACTION_SHIP_TRACK;

            // Prepare tracking information
            // -------------
            $params['tracking_number'] = $trackingDetails['tracking_number'];
            $params['carrier_code'] = Mage::helper('M2ePro/Component_Ebay')->getCarrierTitle(
                $trackingDetails['carrier_code'], $trackingDetails['carrier_title']
            );

            // remove unsupported by eBay symbols
            $params['carrier_code'] = str_replace(array('\'', '"', '+', '(', ')'), array(), $params['carrier_code']);
            // -------------
        }
        mail("ammar.khan@topgearmedia.co.uk","asd","asdasdas");
        echo '<pre>';print_r($params);echo '</pre>';die('Call');

        //return $this->processConnector($action, $params);
    }


}