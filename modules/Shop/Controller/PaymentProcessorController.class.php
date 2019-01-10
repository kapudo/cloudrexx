<?php
/**
 * Cloudrexx
 *
 * @link      http://www.cloudrexx.com
 * @copyright Cloudrexx AG 2007-2018
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Cloudrexx" is a registered trademark of Cloudrexx AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

/**
 * PaymentProcessorController to handle payments
 *
 * @copyright   Cloudrexx AG
 * @author      Sam Hawkes <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  module_shop
 */
namespace Cx\Modules\Shop\Controller;

/**
 * PaymentProcessorController to handle payments
 *
 * @copyright   Cloudrexx AG
 * @author      Sam Hawkes <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  module_shop
 */
class PaymentProcessorController extends \Cx\Core\Core\Model\Entity\Controller
{
    /**
     * Get ViewGenerator options for Payment entity
     *
     * @param $options array predefined ViewGenerator options
     * @return array includes ViewGenerator options for Payment entity
     */
    public function getViewGeneratorOptions($options)
    {
        global $_ARRAYLANG;

        $options['functions']['edit'] = false;
        $options['functions']['delete'] = false;

        $options['fields'] = array(
            'id' => array(
                'showOverview' => false,
            ),
            'type' => array(
                'showOverview' => false,
            ),
            'name' => array(
                'table' => array(
                    'parse' => function($value) {
                        global $_ARRAYLANG;
                        return $_ARRAYLANG['TXT_SHOP_PSP_' . strtoupper($value)];
                    },
                    'attributes' => array(
                        'class' => 'payment-processor-name'
                    )
                ),
            ),
            'status' => array(
                'table' => array(
                    'attributes' => array(
                        'class' => 'payment-processor-status'
                    )
                ),
            ),
            'description' => array(
                'header' => $_ARRAYLANG['TXT_STATEMENT'],
                'table' => array(
                    'parse' => function($value, $rowData) {
                        return $this->getPaymentProcessorDetails($rowData);
                    }
                ),
            ),
            'companyUrl' => array(
                'showOverview' => false,
            ),
            'picture' => array(
                'showOverview' => false,
            ),
            'payments' => array(
                'showOverview' => false,
            ),
        );

        return $options;
    }

    protected function getPaymentProcessorDetails($rowData)
    {
        \Cx\Core\Setting\Controller\Setting::init('Shop', 'config');

        $details = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
        $editDetail = $this->getEditDetail($rowData['name']);

        $information = array();

        $methodName = 'get' .
            ucfirst(
                explode(
                    '_', $rowData['name']
                )[0]
            ) .'Details';
        if (method_exists($this, $methodName)) {
            $information = $this->$methodName();
        }
        $table = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
        foreach ($information as $info) {
            $this->addSingleDetailFields($info, $table);
        }

        $table->setAttributes(
            array(
                'id' => $rowData['name'],
                'class' => 'payment-processor-info hide',
            )
        );

        $details->addChildren(array($editDetail, $table));
        return $details;
    }

    protected function getEditDetail($processor)
    {
        global $_ARRAYLANG;

        $editDetail = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
        $label = new \Cx\Core\Html\Model\Entity\HtmlElement('label');
        $text = new \Cx\Core\Html\Model\Entity\TextElement(
            $_ARRAYLANG['TXT_SHOP_EDIT']
        );
        $label->setAttribute('for', $processor . '_swapper');

        $input = new \Cx\Core\Html\Model\Entity\DataElement(
            $processor . '_swapper',
            1
        );
        $input->setAttributes(
            array(
                'id' => $processor . '_swapper',
                'type' => 'checkbox',
                'onclick' => 'swapBlock("'. $processor .'");'
            )
        );
        $editDetail->setAttribute('class', 'payment-processor-edit');

        $label->addChild($text);
        $editDetail->addChildren(array($label, $input));

        return $editDetail;
    }

    protected function addSingleDetailFields($info, $table)
    {
        $titleWrapper = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
        $inputWrapper = new \Cx\Core\Html\Model\Entity\HtmlElement('div');

        if ($info['recursive']) {
            $input = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
            foreach ($info['children'] as $child) {
                $input->addChild($this->getDetailInput($child));
            }
        } else {
            $input = $this->getDetailInput($info);
        }

        $titleWrapper->setAttributes(
            array(
                'class' => 'title'
            )
        );
        $inputWrapper->setAttributes(
            array(
                'class' => 'input'
            )
        );

        $titleWrapper->addChild($this->getDetailTitle($info['title']));
        $inputWrapper->addChild($input);
        $table->addChild($titleWrapper);
        $table->addChild($inputWrapper);

        return $table;
    }

    protected function getDetailTitle($title)
    {
        $tdTitle = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
        $title = new \Cx\Core\Html\Model\Entity\TextElement($title);
        $tdTitle->addChild($title);
        return $tdTitle;
    }

    protected function getDetailInput($info)
    {
        $type = $info['type'];

        if ($info['type'] == 'text') {
            return new \Cx\Core\Html\Model\Entity\TextElement('');
        }

        if ($info['type'] == 'checkbox' || $info['type'] == 'radio') {
            $type = 'input';
        }

        $input = new \Cx\Core\Html\Model\Entity\DataElement(
            $info['name'],
            $info['value'],
            $type,
            null,
            empty($info['validValues']) ? array() : $info['validValues']
        );

        if ($info['id']) {
            $input->setAttribute('id', $info['id']);
        }
        if ($info['maxlength']) {
            $input->setAttribute('maxlength', $info['maxlength']);
        }

        if ($info['type'] == 'checkbox' || $info['type'] == 'radio') {
            $input->setAttribute('type', $info['type']);
            if ($info['checked']) {
                $input->setAttribute('checked', 'checked');
            }
            if ($info['type'] == 'radio') {
                $wrapper = new \Cx\Core\Html\Model\Entity\HtmlElement('div');
                $label = new \Cx\Core\Html\Model\Entity\HtmlElement('label');
                $labelInfo = new \Cx\Core\Html\Model\Entity\TextElement($info['title']);

                $label->setAttribute('for', $info['id']);

                $label->addChild($labelInfo);
                $wrapper->addChildren(array($input, $label));
                return $wrapper;
            }
        }

        return $input;
    }

    protected function getPaypalDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_PAYPAL_EMAIL_ACCOUNT'],
                'type' => 'input',
                'name' => 'paypal_account_email',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'paypal_account_email',
                        'Shop'
                    )
                ),
                'maxlength' => '254'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYPAL_DEFAULT_CURRENCY'],
                'type' => 'select',
                'name' => 'paypal_default_currency',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'paypal_default_currency',
                    'Shop'
                ),
                'validValues' => \PayPal::getAcceptedCurrencyCodeArray(),
            ),
        );
    }

    protected function getSaferpayDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_ACCOUNT_ID'],
                'type' => 'input',
                'name' => 'saferpay_id',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'saferpay_id',
                    'Shop'
                ),
                'maxlength' => '60'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_USE_TEST_ACCOUNT'],
                'type' => 'checkbox',
                'name' => 'saferpay_use_test_account',
                'value' => 1,
                'checked' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'saferpay_use_test_account',
                    'Shop'
                ),
            ),
            array(
                'title' => $_ARRAYLANG['TXT_FINALIZE_PAYMENT'],
                'type' => 'checkbox',
                'name' => 'saferpay_finalize_payment',
                'value' => 1,
                'checked' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'saferpay_finalize_payment',
                    'Shop'
                ),
            ),
            array(
                'title' => $_ARRAYLANG['TXT_INDICATE_PAYMENT_WINDOW_AS'],
                'type' => 'select',
                'name' => 'saferpay_window_option',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'saferpay_window_option',
                    'Shop'
                ),
                'validValues' => \Saferpay::getWindowIds(),
            ),
        );
    }

    protected function getYellowpayDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_YELLOWPAY_PSPID'],
                'type' => 'input',
                'name' => 'postfinance_shop_id',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'postfinance_shop_id',
                    'Shop'
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_YELLOWPAY_HASH_SIGNATURE_IN'],
                'type' => 'input',
                'name' => 'postfinance_hash_signature_in',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'postfinance_hash_signature_in',
                        'Shop'
                    )
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_YELLOWPAY_HASH_SIGNATURE_OUT'],
                'type' => 'input',
                'name' => 'postfinance_hash_signature_out',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'postfinance_hash_signature_out',
                        'Shop'
                    )
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_AUTORIZATION'],
                'type' => 'select',
                'name' => 'postfinance_authorization_type',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'postfinance_authorization_type',
                    'Shop'
                ),
                'validValues' => \Yellowpay::getAuthorizationOptions(),
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_YELLOWPAY_USE_TESTSERVER'],
                'type' => 'checkbox',
                'name' => 'postfinance_use_testserver',
                'value' => 1,
                'checked' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'postfinance_use_testserver',
                    'Shop'
                ),
            ),
        );
    }

    protected function getMobilesolutionsDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_POSTFINANCE_MOBILE_WEBUSER'],
                'type' => 'input',
                'name' => 'postfinance_mobile_webuser',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'postfinance_mobile_webuser',
                        'Shop'
                    )
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_POSTFINANCE_MOBILE_SIGN'],
                'type' => 'input',
                'name' => 'postfinance_mobile_sign',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'postfinance_mobile_sign',
                        'Shop'
                    )
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_POSTFINANCE_MOBILE_IJUSTWANTTOTEST'],
                'type' => 'checkbox',
                'name' => 'postfinance_mobile_ijustwanttotest',
                'value' => 1,
                'checked' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'postfinance_mobile_ijustwanttotest',
                    'Shop'
                ),
            ),
        );
    }

    protected function getDatatransDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_DATATRANS_MERCHANT_ID'],
                'type' => 'input',
                'name' => 'datatrans_merchant_id',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'datatrans_merchant_id',
                    'Shop'
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_AUTHORIZATION'],
                'type' => 'select',
                'name' => 'datatrans_request_type',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'datatrans_request_type',
                    'Shop'
                ),
                'validValues' => \Datatrans::getReqtypeOptions()
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_DATATRANS_USE_TESTSERVER'],
                'recursive' => true,
                'children' => array(
                    array(
                        'title' => $_ARRAYLANG['TXT_SHOP_YES'],
                        'type' => 'radio',
                        'name' => 'datatrans_use_testserver',
                        'id' => 'datatrans_use_testserver_yes',
                        'value' => 1,
                        'checked' => \Cx\Core\Setting\Controller\Setting::getValue(
                            'datatrans_use_testserver',
                            'Shop'
                        ),
                    ),
                    array(
                        'title' => $_ARRAYLANG['TXT_SHOP_NO'],
                        'type' => 'radio',
                        'name' => 'datatrans_use_testserver',
                        'id' => 'datatrans_use_testserver_no',
                        'value' => 1,
                        'checked' =>\Cx\Core\Setting\Controller\Setting::getValue(
                            'datatrans_use_testserver',
                            'Shop'
                        ),
                    ),
                ),
            ),
        );
    }

    protected function getPaymillDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_MODE'],
                'type' => 'select',
                'name' => 'paymill_use_test_account',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'paymill_use_test_account',
                    'Shop'
                ),
                'validValues' => array(
                    $_ARRAYLANG['TXT_SHOP_TEST'],
                    $_ARRAYLANG['TXT_SHOP_LIVE'],
                )
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMILL_TEST_ACCOUNT'],
                'type' => 'text',
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMILL_PRIVATE_KEY'],
                'type' => 'input',
                'name' => 'paymill_test_private_key',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'paymill_test_private_key',
                        'Shop'
                    )
                ),
                'maxlength' => '254'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMILL_PUBLIC_KEY'],
                'type' => 'input',
                'name' => 'paymill_test_public_key',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'paymill_test_public_key',
                        'Shop'
                    )
                ),
                'maxlength' => '254'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMILL_LIVE_ACCOUNT'],
                'type' => 'text',
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMILL_PRIVATE_KEY'],
                'type' => 'input',
                'name' => 'paymill_live_private_key',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'paymill_live_private_key',
                        'Shop'
                    )
                ),
                'maxlength' => '254'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMILL_PUBLIC_KEY'],
                'type' => 'input',
                'name' => 'paymill_live_public_key',
                'value' => contrexx_raw2xhtml(
                    \Cx\Core\Setting\Controller\Setting::getValue(
                        'paymill_live_public_key',
                        'Shop'
                    )
                ),
                'maxlength' => '254'
            ),
        );
    }

    protected function getPayrexxDetails()
    {
        global $_ARRAYLANG;

        return array(
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMENT_PAYREXX_INSTANCE_NAME'],
                'type' => 'input',
                'name' => 'payrexx_instance_name',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'payrexx_instance_name',
                    'Shop'
                ),
                'maxlength' => '50'
            ),
            array(
                'title' => $_ARRAYLANG['TXT_SHOP_PAYMENT_PAYREXX_API_SECRET'],
                'type' => 'input',
                'name' => 'payrexx_api_secret',
                'value' => \Cx\Core\Setting\Controller\Setting::getValue(
                    'payrexx_api_secret',
                    'Shop'
                ),
                'maxlength' => '50'
            ),
        );
    }

    /**
     * Returns the HTML code for the Yellowpay payment method.
     * @return  string  HTML code
     */
    static function _YellowpayProcessor()
    {
        global $_ARRAYLANG;

        $arrShopOrder = array(
// 20111227 - Note that all parameter names should now be uppercase only
            'ORDERID'   => $_SESSION['shop']['order_id'],
            'AMOUNT'    => intval(bcmul($_SESSION['shop']['grand_total_price'], 100, 0)),
            'CURRENCY'  => \Cx\Modules\Shop\Controller\CurrencyController::getActiveCurrencyCode(),
            'PARAMPLUS' => 'section=Shop'.MODULE_INDEX.'&cmd=success&handler=yellowpay',
// Custom code for adding more Customer data to the form.
// Enable as needed.
            // COM          Order description
            // CN           Customer name. Will be pre-initialized (but still editable) in the cardholder name field of the credit card details.
//            'CN' => $_SESSION['shop']['firstname'].' '.$_SESSION['shop']['lastname'],
            // EMAIL        Customer's e-mail address
//            'EMAIL' => $_SESSION['shop']['email'],
            // owneraddress Customer's street name and number
//            'owneraddress' => $_SESSION['shop']['address'],
            // ownerZIP     Customer's ZIP code
//            'ownerZIP' => $_SESSION['shop']['zip'],
            // ownertown    Customer's town/city name
//            'ownertown' => $_SESSION['shop']['city'],
            // ownercty     Customer's country
//            'ownercty' => \Cx\Core\Country\Controller\Country::getNameById($_SESSION['shop']['countryId']),
            // ownertelno   Customer's telephone number
//            'ownertelno' => $_SESSION['shop']['phone'],
        );

        $landingPage = \Env::get('em')->getRepository('Cx\Core\ContentManager\Model\Entity\Page')->findOneByModuleCmdLang('Shop'.MODULE_INDEX, 'success', FRONTEND_LANG_ID);

        $return = \Yellowpay::getForm($arrShopOrder, $_ARRAYLANG['TXT_ORDER_NOW'], false, null, $landingPage);

        if (_PAYMENT_DEBUG && \Yellowpay::$arrError) {
            $strError =
                '<font color="red"><b>'.
                $_ARRAYLANG['TXT_SHOP_PSP_FAILED_TO_INITIALISE_YELLOWPAY'].
                '<br /></b>';
            if (_PAYMENT_DEBUG) {
                $strError .= join('<br />', \Yellowpay::$arrError); //.'<br />';
            }
            return $strError.'</font>';
        }
        if (empty ($return)) {
            foreach (\Yellowpay::$arrError as $error) {
                \DBG::log("Yellowpay Error: $error");
            }
        }
        return $return;
    }

    /**
     * Returns the HTML code for the Saferpay payment form.
     * @param   array   $arrCards     The optional accepted card types
     * @return  string                The HTML code
     * @static
     */
    static function _SaferpayProcessor()
    {
        global $_ARRAYLANG;

        $arrShopOrder = array(
            'AMOUNT'        => str_replace('.', '', $_SESSION['shop']['grand_total_price']),
            'CURRENCY'      => \Cx\Modules\Shop\Controller\CurrencyController::getActiveCurrencyCode(),
            'ORDERID'       => $_SESSION['shop']['order_id'],
            'ACCOUNTID'     => \Cx\Core\Setting\Controller\Setting::getValue('saferpay_id','Shop'),
            'SUCCESSLINK'   => \Cx\Core\Routing\Url::fromModuleAndCmd('Shop'.MODULE_INDEX, 'success', '',
                array('result' => 1, 'handler' => 'saferpay'))->toString(),
            'FAILLINK'      => \Cx\Core\Routing\Url::fromModuleAndCmd('Shop'.MODULE_INDEX, 'success', '',
                array('result' => 0, 'handler' => 'saferpay'))->toString(),
            'BACKLINK'      => \Cx\Core\Routing\Url::fromModuleAndCmd('Shop'.MODULE_INDEX, 'success', '',
                array('result' => 2, 'handler' => 'saferpay'))->toString(),
            'DESCRIPTION'   => '"'.$_ARRAYLANG['TXT_ORDER_NR'].
                ' '.$_SESSION['shop']['order_id'].'"',
            'LANGID'        => \FWLanguage::getLanguageCodeById(FRONTEND_LANG_ID),
            'NOTIFYURL'     => \Cx\Core\Routing\Url::fromModuleAndCmd('Shop'.MODULE_INDEX, 'success', '',
                array('result' => '-1', 'handler' => 'saferpay'))->toString(),
            'ALLOWCOLLECT'  => 'no',
            'DELIVERY'      => 'no',
        );
        $payInitUrl = \Saferpay::payInit($arrShopOrder,
            \Cx\Core\Setting\Controller\Setting::getValue('saferpay_use_test_account','Shop'));
//DBG::log("PaymentProcessing::_SaferpayProcessor(): payInit URL: $payInitUrl");
        // Fixed: Added check for empty return string,
        // i.e. on connection problems
        if (!$payInitUrl) {
            return
                "<font color='red'><b>".
                $_ARRAYLANG['TXT_SHOP_PSP_FAILED_TO_INITIALISE_SAFERPAY'].
                "<br />$payInitUrl</b></font>".
                "<br />".\Saferpay::getErrors();
        }
        $return = "<script src='http://www.saferpay.com/OpenSaferpayScript.js'></script>\n";
        switch (\Cx\Core\Setting\Controller\Setting::getValue('saferpay_window_option','Shop')) {
            case 0: // iframe
                return
                    $return.
                    $_ARRAYLANG['TXT_ORDER_PREPARED']."<br/><br/>\n".
                    "<iframe src='$payInitUrl' width='580' height='400' scrolling='no' marginheight='0' marginwidth='0' frameborder='0' name='saferpay'></iframe>\n";
            case 1: // popup
                return
                    $return.
                    $_ARRAYLANG['TXT_ORDER_LINK_PREPARED']."<br/><br/>\n".
                    "<script type='text/javascript'>
                     function openSaferpay() {
                       strUrl = '$payInitUrl';
                       if (strUrl.indexOf(\"WINDOWMODE=Standalone\") == -1) {
                         strUrl += \"&WINDOWMODE=Standalone\";
                       }
                       oWin = window.open(strUrl, 'SaferpayTerminal',
                           'scrollbars=1,resizable=0,toolbar=0,location=0,directories=0,status=1,menubar=0,width=580,height=400'
                       );
                       if (oWin == null || typeof(oWin) == \"undefined\") {
                         alert(\"The payment couldn't be initialized.  It seems like you are using a popup blocker!\");
                       }
                     }
                     </script>\n".
                    "<input type='button' name='order_now' value='".
                    $_ARRAYLANG['TXT_ORDER_NOW'].
                    "' onclick='openSaferpay()' />\n";
            default: //case 2: // new window
        }
        return
            $return.
            $_ARRAYLANG['TXT_ORDER_LINK_PREPARED']."<br/><br/>\n".
            "<form method='post' action='$payInitUrl'>\n<input type='submit' value='".
            $_ARRAYLANG['TXT_ORDER_NOW'].
            "' />\n</form>\n";
    }

    /**
     * Returns the HTML code for the Paymill payment method.
     *
     * @return  string  HTML code
     */
    static function _PaymillProcessor($processMethod)
    {
        global $_ARRAYLANG;

        $landingPage = \Env::get('em')->getRepository('Cx\Core\ContentManager\Model\Entity\Page')->findOneByModuleCmdLang('Shop'.MODULE_INDEX, 'success', FRONTEND_LANG_ID);

        $arrShopOrder = array(
            'order_id'  => $_SESSION['shop']['order_id'],
            'amount'    => intval(bcmul($_SESSION['shop']['grand_total_price'], 100, 0)),
            'currency'  => \Cx\Modules\Shop\Controller\CurrencyController::getActiveCurrencyCode(),
        );

        switch ($processMethod) {
            case 'paymill_cc':
                $return = \PaymillCCHandler::getForm($arrShopOrder, $landingPage);
                break;
            case 'paymill_elv':
                $return = \PaymillELVHandler::getForm($arrShopOrder, $landingPage);
                break;
            case 'paymill_iban':
                $return = \PaymillIBANHandler::getForm($arrShopOrder, $landingPage);
                break;
        }

        if (_PAYMENT_DEBUG && \PaymillHandler::$arrError) {
            $strError =
                '<font color="red"><b>'.
                $_ARRAYLANG['TXT_SHOP_PSP_FAILED_TO_INITIALISE_YELLOWPAY'].
                '<br /></b>';
            if (_PAYMENT_DEBUG) {
                $strError .= join('<br />', \PaymillHandler::$arrError); //.'<br />';
            }
            return $strError.'</font>';
        }
        if (empty ($return)) {
            foreach (\PaymillHandler::$arrError as $error) {
                \DBG::log("Paymill Error: $error");
            }
        }

        return $return;
    }

    /**
     * Returns the HTML code for the Payrexx payment method.
     * @return  string  HTML code
     */
    static function _PayrexxProcessor()
    {
        global $_ARRAYLANG, $_CONFIG;

        $return = \PayrexxProcessor::getModalCode();
        if (!$return) {
            $strError =
                '<font color="red"><b>'.
                $_ARRAYLANG['TXT_SHOP_PSP_FAILED_TO_INITIALISE_PAYREXX'].
                '<br /></b>';
            $strError .= join('<br />', \PayrexxProcessor::$arrError); //.'<br />';
            return $strError.'</font>';
        }
        return $return;
    }

    static function getOrderId()
    {
        if (empty($_REQUEST['handler'])) {
//DBG::log("PaymentProcessing::getOrderId(): No handler, fail");
            return false;
        }
        switch ($_REQUEST['handler']) {
            case 'saferpay':
                return \Saferpay::getOrderId();
            case 'paypal':
                return \PayPal::getOrderId();
            case 'yellowpay':
                return \Yellowpay::getOrderId();
            case 'payrexx':
                return \PayrexxProcessor::getOrderId();
            // Added 20100222 -- Reto Kohli
            case 'mobilesolutions':
//DBG::log("getOrderId(): mobilesolutions");
                $order_id = \PostfinanceMobile::getOrderId();
//DBG::log("getOrderId(): mobilesolutions, Order ID $order_id");
                return $order_id;
            // Added 20081117 -- Reto Kohli
            case 'datatrans':
                return \Datatrans::getOrderId();
            // For the remaining types, there's no need to check in, so we
            // return true and jump over the validation of the order ID
            // directly to success!
            // Note: A backup of the order ID is kept in the session
            // for payment methods that do not return it. This is used
            // to cancel orders in all cases where false is returned.
            case 'Internal':
            case 'Internal_CreditCard':
            case 'Internal_Debit':
            case 'Internal_LSV':
            case 'dummy':
                return (isset($_SESSION['shop']['order_id_checkin'])
                    ? $_SESSION['shop']['order_id_checkin']
                    : false);
        }
        // Anything else is wrong.
        return false;
    }
}