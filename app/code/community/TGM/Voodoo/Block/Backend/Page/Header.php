<?php

class TGM_Voodoo_Block_Backend_Page_Header
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     *
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '
<script type="text/javascript">

function sendtestmessge(section){
    var uid = document.getElementById("voodoo_enter_username").value;
    var pass = document.getElementById("voodoo_enter_password").value;
    var orig = document.getElementById(section+"sender").value;
    var msg = document.getElementById(section+"message").value;
    var dest = document.getElementById(section+"oplctest").value;
    //var url = "https://www.voodoosms.com/vapi/server/sendSMS?uid="+uid+"&pass="+pass+"&orig="+orig+"&dest="+dest+"&msg="+msg+"&validty=300&default_cc=44";
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
            }
        };
    xhttp.open("GET", "http://magento.voodoosms.com/magento/sendtestmessageajax.php?uid="+uid+"&pass="+pass+"&orig="+orig+"&dest="+dest+"&msg="+msg, true);
    xhttp.send();
}
</script>
<div style="background:#EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:20px 15px 15px; border-radius: 5px;">
                    <img src="https://www.voodoosms.com/ecommerce-extension/images/logo.png" style="margin-left:-15px;"/>
                    <div style="margin: 0 auto;">

                    <h4 style="color:#EA7601;">Voodoo SMS Extension Community Edition v4.1.1 by <a target="_blank" href="https://www.voodoosms.com"><strong>VoodooSMS</strong></a></h4>
                    <h4>This module requires an account,
                     API username/password and SMS credits with <a href="https://www.voodoosms.com">www.voodoosms.com</a>.
                     <br>To register an account <a href="https://www.voodoosms.com/portal.html">click here</a>
                     <br>To view instructions on how to configure this module <a href="https://www.voodoosms.com/portal/help/online_help/?help=69&p=173#help_detail_post_173">click here</a></h4>
					<p>Query? Feel free to contact the team by <a href="https://www.voodoosms.com/contact-us.html" target="_blank">clicking here</a></p>
                    </div>
                ';

        return $html;
    }
}