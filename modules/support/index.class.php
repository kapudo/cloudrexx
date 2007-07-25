<?php

/**
 * Support system including Tickets, Knowledge Base and Mail support.
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Reto Kohli <reto.kohli@comvation.com>
 * @version     0.0.1
 * @package     contrexx
 * @subpackage  module_support
 */

/**
 * Common functions and methods used by both front- and backend
 */
require_once ASCMS_MODULE_PATH.'/support/lib/SupportCommon.class.php';
/**
 * Support Category
 */
require_once ASCMS_MODULE_PATH.'/support/lib/SupportCategory.class.php';

/**
 * Support system frontend
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Reto Kohli <reto.kohli@comvation.com>
 * @version     0.0.1
 * @package     contrexx
 * @subpackage  module_support
 */
class Support
{
    /**
     * @access  private
     * @var     HTML_Template_Sigma
     */
    var $objTemplate;

    /**
     * Status message
     * @access  private
     * @var     string
     */
    var $statusMessage = '';


    /**
     * Constructor (PHP4)
     * @copyright   CONTREXX CMS - COMVATION AG
     * @author      Reto Kohli <reto.kohli@comvation.com>
     * @version     0.0.1
     * @param       string  $strTemplate
     * @see     __construct()
     */
    function Support($strTemplate)
    {
        $this->__construct($strTemplate);
    }

    /**
     * Constructor (PHP5)
     * @copyright   CONTREXX CMS - COMVATION AG
     * @author      Reto Kohli <reto.kohli@comvation.com>
     * @version     0.0.1
     * @param       string  $strTemplate
     */
    function __construct($strTemplate)
    {
        if (1) {
            global $objDatabase; $objDatabase->debug = 1;
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        $this->objTemplate = new HTML_Template_Sigma('.');
        $this->objTemplate->setErrorHandling(PEAR_ERROR_DIE);
        $this->objTemplate->setTemplate($strTemplate);
//        $this->initialize();
    }


    /**
     * Call the appropriate method to set up the requested page.
     * @copyright   CONTREXX CMS - COMVATION AG
     * @author      Reto Kohli <reto.kohli@comvation.com>
     * @version     0.0.1
     * @access  public
     * @return  string      The created content
     */
    function getPage()
    {
        if (!isset($_GET['cmd'])) {
            $_GET['cmd'] = '';
        }
        switch ($_GET['cmd']) {
/*
          case '':
            $this->_();
            break;
*/
          default:
            $this->welcomePage();
            break;
        }
        return $this->objTemplate->get();
    }


    /**
     * Set up and return the support ticket welcome page.
     *
     * Welcome the user and let her choose a category for the ticket.
     * @copyright   CONTREXX CMS - COMVATION AG
     * @author      Reto Kohli <reto.kohli@comvation.com>
     * @version     0.0.1
     * @return      string          The support ticket welcome page
     * @global      $_ARRAYLANG     Language array
     */
    function welcomePage()
    {
        global $_ARRAYLANG;

        $this->objTemplate->setVariable(array(
            'TXT_SUPPORT_WELCOME'           => $_ARRAYLANG['TXT_SUPPORT_WELCOME'],
            'TXT_SUPPORT_CHOOSE_CATEGORY'   => $_ARRAYLANG['TXT_SUPPORT_CHOOSE_CATEGORY'],
            'TXT_SUPPORT_CONTINUE'          => $_ARRAYLANG['TXT_SUPPORT_CONTINUE'],
            'SUPPORT_CATEGORIES'            => $this->getSupportCategoriesMenu(),
        ));
        $this->objTemplate->parse();
echo("template: ");var_export($this->objTemplate);echo("<br />");
        return $this->objTemplate->get();
    }


    /**
     * Returns HTML code for the Support Categories dropdown menu.
     *
     * Note that the enclosing <select> block is not included!
     * @param   integer     $parentCategoryId   The parent Support Category ID
     * @param   integer     $selectedCategoryId The selected Support Category ID
     * @return  string                          The dropdown menu HTML code
     * @global  Init        $objInit            Init object
     */
    function getSupportCategoriesMenu($parentCategoryId=0, $selectedCategoryId=0)
    {
        global $objInit;

        // *MUST* return an array(id => "category name", ...)
        $arrCategories =
            SupportCategory::getSupportCategoryNameArray(
                $objInit->userFrontendLangId,
                $parentCategoryId
            );
        if (!is_array($arrCategories) || count($arrCategories) == 0) {
echo("getSupportCategoriesMenu($parentCategoryId=0, $selectedCategoryId=0): failed to get name array<br />");
            // no categories here.  abort.
            return false;
        }
        $strOptions = '';
        foreach ($arrCategories as $id => $categoryName) {
            $strOptions .=
               "<option value='$id' ".
               ($id == $selectedCategoryId
                   ? 'selected="selected"'
                   : ''
               ).'>'.
               htmlentities($categoryName, ENT_QUOTES, CONTREXX_CHARSET).
               "</option>\n";
        }
        return $strOptions;
    }
}

?>
