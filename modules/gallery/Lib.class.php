<?PHP
/**
 * Gallery library
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Comvation Development Team <info@comvation.com>
 * @version     1.0.0
 * @package     contrexx
 * @subpackage  module_gallery
 * @todo        Edit PHP DocBlocks!
 */

/**
 * Gallery library
 *
 * Library for the Gallery
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Comvation Development Team <info@comvation.com>
 * @access      public
 * @version     1.0.0
 * @package     contrexx
 * @subpackage  module_gallery
 */

require_once ASCMS_MODULE_PATH . '/gallery/sql.class.php';

class GalleryLibrary
{
    protected $sql;
    
    public function __construct()
    {
        $this->sql = new GallerySql();
    }
	
	/**
    * Gets the gallery settings
    *
    * @global  object  $objDatabase
    */
    public function getSettings()
    {
    	global $objDatabase;
    	$objResult = $objDatabase->Execute("SELECT name,value FROM ".DBPREFIX."module_gallery_settings");
    	while (!$objResult->EOF) {
    		$this->arrSettings[$objResult->fields['name']] = $objResult->fields['value'];
    		$objResult->MoveNext();
    	}
    }
}
?>
