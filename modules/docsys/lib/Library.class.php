<?php
/**
 * Class Document System
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author Comvation Development Team <info@comvation.com>
 * @access public
 * @version 1.0.0
 * @package     contrexx
 * @subpackage  module_docsys
 * @todo        Edit PHP DocBlocks!
 */

/**
 * Class Document System
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author Comvation Development Team <info@comvation.com>
 * @access public
 * @version 1.0.0
 * @package     contrexx
 * @subpackage  module_docsys
 * @todo        Edit PHP DocBlocks!
 */
class docSysLibrary
{
    /**
    * Gets the categorie option menu string
    *
    * @global    ADONewConnection
    * @param     string     $lang
    * @param     string     $selectedOption
    * @return    string     $modulesMenu
    */
    function getCategoryMenu($langId, $selectedCatId="")
    {
        global $objDatabase;

        $strMenu = "";
        $query="SELECT catid,
                       name
                  FROM ".DBPREFIX."module_docsys".MODULE_INDEX."_categories
                 WHERE lang=".$langId."
              ORDER BY catid";

        $objResult = $objDatabase->Execute($query);
        while (!$objResult->EOF) {
            $selected = "";
            if($selectedCatId==$objResult->fields['catid']){
                $selected = "selected";
            }
            $strMenu .="<option value=\"".$objResult->fields['catid']."\" $selected>".stripslashes($objResult->fields['name'])."</option>\n";
            $objResult->MoveNext();
        }
        return $strMenu;
    }
}

?>
