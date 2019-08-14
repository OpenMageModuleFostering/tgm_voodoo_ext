<?php

require_once 'Mage/Checkout/controllers/OnepageController.php';

class TGM_Voodoo_OnepageController extends Mage_Checkout_OnepageController
{
    public function doSomestuffAction()
    {
		if(true) {
			$result['update_section'] = array(
            	'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
			);					
		}
    	else {
			$result['goto_section'] = 'shipping';
		}		
    }    

    public function savePaymentAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('payment', array());
            /*
            * first to check payment information entered is correct or not
            */

            try {
                $result = $this->getOnepage()->savePayment($data);
            }
            catch (Mage_Payment_Exception $e) {
                if ($e->getFields()) {
                    $result['fields'] = $e->getFields();
                }
                $result['error'] = $e->getMessage();
            }
            catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
				$this->loadLayout('checkout_onepage_voodoo');

                $result['goto_section'] = 'voodoo';
            }

            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }

    public function saveVoodooAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {

            //Grab the submited value voodoo for us value
        	$_tgm_voodoo = $this->getRequest()->getPost('getvoice');
			Mage::getSingleton('core/session')->setTGMVoodoo($_tgm_voodoo);

			$result = array();
            
            $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (!$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');

                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );

            }

            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }    
}
