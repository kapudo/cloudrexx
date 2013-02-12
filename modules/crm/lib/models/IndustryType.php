<?php

class IndustryType {

    private $moduleName = "crm";
    public $arrIndustryTypes;

    function  __construct() {
        $this->arrIndustryTypes = $this->getIndustryTypes();
    }

    function getIndustryTypes($intIndustryId=null, $intParentId=null, $status = false) {
        global $_ARRAYLANG, $objDatabase, $_LANGID;

        $arrIndustries = array();
        $whereParentId = '';
        $whereActive   = '';
        
        if (!empty($intIndustryId)) {
            $whereParentId = '';
        } else {
            if (!empty($intParentId)) {
                $whereParentId = "AND parent_id = {$intParentId}";
            } else {
                $whereParentId = "AND parent_id = 0";
            }            
        }

        if($status) {
            $whereActive = "AND (Intype.status = '1') ";
        } else {
			$whereActive = '';
	}

        $sortOrder = 'ORDER BY sorting ASC';

        $objIndustries = $objDatabase->Execute("SELECT Intype.id,
                                                       Intype.parent_id,
                                                       Intype.sorting,
                                                       Intype.status,
                                                       Inloc.value
                                                 FROM `".DBPREFIX."module_{$this->moduleName}_industry_types` AS Intype
                                                 LEFT JOIN `".DBPREFIX."module_{$this->moduleName}_industry_type_local` AS Inloc
                                                    ON Intype.id = Inloc.entry_id
                                                 WHERE Inloc.lang_id = ".$_LANGID."
                                                 $whereParentId
                                                 $whereActive
                                                 $sortOrder
                                                 ");
        if ($objIndustries) {
            while (!$objIndustries->EOF) {

                $arrIndustry = array();

                $arrIndustry['id']          = $objIndustries->fields['id'];
                $arrIndustry['name']        = $objIndustries->fields['value'];
                $arrIndustry['parent_id']   = $objIndustries->fields['parent_id'];
                $arrIndustry['sorting']     = $objIndustries->fields['sorting'];
                $arrIndustry['status']      = $objIndustries->fields['status'];

                $arrIndustry['children']    = $this->getIndustryTypes(null, $objIndustries->fields['id']);

                $arrIndustries[$objIndustries->fields['id']] = $arrIndustry;
                $objIndustries->MoveNext();
            }
        }

        return $arrIndustries;
    }

    function  __set($name,  $value) {
        $this->{$name} = $value;
    }

    function  __get($name) {
        return $this->{$name};
    }
}
