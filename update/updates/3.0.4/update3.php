<?php

/**
 * This file updates a RC1 or RC2 installation to a stable installation
 * To update your installation perform the following:
 * 1. Copy all your files to a backup folder
 * 2. Create a backup of your database
 * 3. Copy all stable files to your folder
 * 4. Copy /config/configuration.php, /config/settings.php and /.htaccess back
 * 5. Copy your customized files back
 * 6. Execute this script
 */

$documentRoot = ASCMS_PATH . ASCMS_PATH_OFFSET;

$objResultRc1 = \Cx\Lib\UpdateUtil::sql('SELECT `target` FROM `'.DBPREFIX.'backend_areas` WHERE `area_id` = 186');
$objResultRc2 = \Cx\Lib\UpdateUtil::sql('SELECT `order_id` FROM `'.DBPREFIX.'backend_areas` WHERE `area_id` = 2');
if (!$objResultRc1 || !$objResultRc2) {
    die('ERROR: Could not execute query.');
}
if ($objResultRc1->fields['target'] != '_blank') {
    $version = 'rc1';
} elseif ($objResultRc2->fields['order_id'] != 6) {
    $version = 'rc2';
} elseif ($_CONFIG['coreCmsVersion'] == '3.0.0') {
    $version = 'stable';
} elseif ($_CONFIG['coreCmsVersion'] == '3.0.0.1') {
    $version = 'hotfix';
} elseif ($_CONFIG['coreCmsVersion'] == '3.0.1') {
    $version = 'sp1';
} elseif ($_CONFIG['coreCmsVersion'] == '3.0.2') {
    $version = 'sp2';
} elseif ($_CONFIG['coreCmsVersion'] == '3.0.3') {
    $version = 'sp3';
} else {
    // nothing to do
    return true;
}


$objResult = \Cx\Lib\UpdateUtil::sql('SELECT 1 FROM `'.DBPREFIX.'module_filesharing_mail_template` WHERE (`id` = 1 OR `id` = 2)');
if (!$objResult) {
    die('ERROR: Could not execute query.');
}
if ($objResult->RecordCount() == 0) {
    $rc1UpdateFilesharing[] = '
        INSERT INTO `'.DBPREFIX.'module_filesharing_mail_template` (`id`, `lang_id`, `subject`, `content`) VALUES (1, 1, "Jemand teilt eine Datei mit Ihnen", "Guten Tag,\r\n\r\nJemand hat auf [[DOMAIN]] eine Datei mit Ihnen geteilt.\r\n\r\n<!-- BEGIN filesharing_file -->\r\nDownload-Link: [[FILE_DOWNLOAD]]\r\n<!-- END filesharing_file -->\r\n\r\nDie Person hat eine Nachricht hinterlassen:\r\n[[MESSAGE]]\r\n\r\nFreundliche Grüsse");
    ';
    $rc1UpdateFilesharing[] = '
        INSERT INTO `'.DBPREFIX.'module_filesharing_mail_template` (`id`, `lang_id`, `subject`, `content`) VALUES (2, 2, "Somebody is sharing a file with you", "Hi,\r\n\r\nSomebody shared a file with you on [[DOMAIN]].\r\n\r\n<!-- BEGIN filesharing_file -->\r\nDownload link: [[FILE_DOWNLOAD]]\r\n<!-- END filesharing_file -->\r\n\r\nThe person has left a message for you:\r\n[[MESSAGE]]\r\n\r\nBest regards");
    ';
} else {
    $rc1UpdateFilesharing = null;
}

$updatesRc1ToRc2 = array(
    /*
    array(
        'table' => ,
        'structure' => ,
        'keys' => ,
    ),
     */
    array(
        'table' => DBPREFIX.'access_user_profile',
        'structure' => array(
            'user_id'            => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'primary' => true),
            'gender'             => array('type' => 'ENUM(\'gender_undefined\',\'gender_female\',\'gender_male\')', 'notnull' => true, 'default' => 'gender_undefined', 'after' => 'user_id'),
            'title'              => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'gender'),
            'firstname'          => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'title'),
            'lastname'           => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'firstname'),
            'company'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'lastname'),
            'address'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'company'),
            'city'               => array('type' => 'VARCHAR(50)', 'notnull' => true, 'default' => '', 'after' => 'address'),
            'zip'                => array('type' => 'VARCHAR(10)', 'notnull' => true, 'default' => '', 'after' => 'city'),
            'country'            => array('type' => 'SMALLINT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'zip'),
            'phone_office'       => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'country'),
            'phone_private'      => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'phone_office'),
            'phone_mobile'       => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'phone_private'),
            'phone_fax'          => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'phone_mobile'),
            'birthday'           => array('type' => 'VARCHAR(11)', 'notnull' => false, 'after' => 'phone_fax'),
            'website'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'birthday'),
            'profession'         => array('type' => 'VARCHAR(150)', 'notnull' => true, 'default' => '', 'after' => 'website'),
            'interests'          => array('type' => 'text', 'notnull' => true, 'after' => 'profession'),
            'signature'          => array('type' => 'text', 'notnull' => true, 'after' => 'interests'),
            'picture'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'signature'),
        ),
        'keys' => array(
            'profile'        => array('fields' => array('firstname' => 100, 'lastname' => 100, 'company' => 50))
        ),
        'engine' => 'InnoDB',
    ),
    '
        UPDATE `'.DBPREFIX.'access_user_profile`
        SET  `interests` = NULL , `signature` = NULL
        WHERE (`interests` = "" AND `signature` = "");
    ',
    '
        INSERT INTO `'.DBPREFIX.'settings` (`setid`, `setname`, `setvalue`, `setmodule`)
        VALUES (103, "availableComponents", "", 66)
    ',
    array(
        'table' => DBPREFIX.'modules',
        'structure' => array(
            'id'                         => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => false),
            'name'                       => array('type' => 'VARCHAR(250)', 'notnull' => true, 'default' => '', 'after' => 'id'),
            'distributor'                => array('type' => 'CHAR(50)', 'after' => 'name'),
            'description_variable'       => array('type' => 'VARCHAR(50)', 'notnull' => true, 'default' => '', 'after' => 'distributor'),
            'status'                     => array('type' => 'SET(\'y\',\'n\')', 'notnull' => true, 'default' => 'n', 'after' => 'description_variable'),
            'is_required'                => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'status'),
            'is_core'                    => array('type' => 'TINYINT(4)', 'notnull' => true, 'default' => '0', 'after' => 'is_required'),
            'is_active'                  => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'is_core'),
        ),
        'keys' => array(
            'id'                         => array('fields' => array('id'), 'type' => 'UNIQUE'),
        ),
    ),
    '
        UPDATE `'.DBPREFIX.'modules`
        SET `distributor` = "Comvation AG"
    ',
    array(
        'table' => DBPREFIX.'module_mediadir_rel_entry_inputfields_clean1',
        'structure' => array(
            'id'             => array('type' => 'INT(11)', 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'entry_id'       => array('type' => 'INT(7)', 'after' => 'id'),
            'lang_id'        => array('type' => 'INT(7)', 'after' => 'entry_id'),
            'form_id'        => array('type' => 'INT(7)', 'after' => 'lang_id'),
            'field_id'       => array('type' => 'INT(7)', 'after' => 'form_id'),
            'value'          => array('type' => 'longtext', 'after' => 'field_id'),
        ),
        'keys' => array(
            'value'          => array('fields' => array('value'), 'type' => 'FULLTEXT'),
        ),
    ),
    '
        INSERT INTO `'.DBPREFIX.'module_mediadir_rel_entry_inputfields_clean1`
        SELECT NULL, `entry_id`, `lang_id`, `form_id`, `field_id`, `value`
        FROM `'.DBPREFIX.'module_mediadir_rel_entry_inputfields`
        GROUP BY `entry_id`, `form_id`, `field_id`, `lang_id`, `value`
    ',
    '
        TRUNCATE `'.DBPREFIX.'module_mediadir_rel_entry_inputfields`
    ',
    array(
        'table' => DBPREFIX.'module_mediadir_rel_entry_inputfields',
        'structure' => array(
            'entry_id'       => array('type' => 'INT(7)'),
            'lang_id'        => array('type' => 'INT(7)', 'after' => 'entry_id'),
            'form_id'        => array('type' => 'INT(7)', 'after' => 'lang_id'),
            'field_id'       => array('type' => 'INT(7)', 'after' => 'form_id'),
            'value'          => array('type' => 'longtext', 'after' => 'field_id'),
        ),
        'keys' => array(
            'entry_id'       => array('fields' => array('entry_id','lang_id','form_id','field_id'), 'type' => 'UNIQUE'),
            'value'          => array('fields' => array('value'), 'type' => 'FULLTEXT'),
        ),
    ),
    '
        INSERT IGNORE INTO `'.DBPREFIX.'module_mediadir_rel_entry_inputfields`
        SELECT `entry_id`, `lang_id`, `form_id`, `field_id`, `value`
        FROM `'.DBPREFIX.'module_mediadir_rel_entry_inputfields_clean1`
        ORDER BY `id` DESC
    ',
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_mediadir_rel_entry_inputfields_clean1`
    ',
    array(
        'table' => DBPREFIX.'module_repository',
        'structure' => array(
            'id'                 => array('type' => 'INT(6)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true),
            'moduleid'           => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'content'            => array('type' => 'mediumtext', 'after' => 'moduleid'),
            'title'              => array('type' => 'VARCHAR(250)', 'notnull' => true, 'default' => '', 'after' => 'content'),
            'cmd'                => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'title'),
            'expertmode'         => array('type' => 'SET(\'y\',\'n\')', 'notnull' => true, 'default' => 'n', 'after' => 'cmd'),
            'parid'              => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'expertmode'),
            'displaystatus'      => array('type' => 'SET(\'on\',\'off\')', 'notnull' => true, 'default' => 'on', 'after' => 'parid'),
            'username'           => array('type' => 'VARCHAR(250)', 'notnull' => true, 'default' => '', 'after' => 'displaystatus'),
            'displayorder'       => array('type' => 'SMALLINT(6)', 'notnull' => true, 'default' => '100', 'after' => 'username')
        ),
        'keys' => array(
            'contentid'          => array('fields' => array('id'), 'type' => 'UNIQUE'),
            'fulltextindex'      => array('fields' => array('title','content'), 'type' => 'FULLTEXT')
        ),
    ),
    '
        TRUNCATE TABLE `'.DBPREFIX.'module_repository`
    ',
    '
        UPDATE `'.DBPREFIX.'backend_areas`
        SET `target` = "_blank"
        WHERE `area_id` = 186
    ',
);
if (!empty($rc1UpdateFilesharing)) {
    $updatesRc1ToRc2 = array_merge($updatesRc1ToRc2, $rc1UpdateFilesharing);
}

$updatesRc2ToStable = array(
    '
        UPDATE `'.DBPREFIX.'core_setting`
        SET `value` = "off"
        WHERE (`section` = "filesharing" AND `name` = "permission" AND `group` = "config")
    ',
    '
        INSERT INTO `'.DBPREFIX.'core_text` (`id`, `lang_id`, `section`, `key`, `text`)
        VALUES  (4, 1, "shop", "currency_name", "Euro"),
                (5, 1, "shop", "currency_name", "United States Dollars")
    ',
    '
        INSERT INTO `'.DBPREFIX.'settings` (`setid`, `setname`, `setvalue`, `setmodule`)
        VALUES  (104, "upgradeUrl", "http://license.contrexx.com/", 66),
                (105, "isUpgradable", "on", 66),
                (106, "dashboardMessages", "", 66)
    ',
    '
        DELETE FROM `'.DBPREFIX.'backend_areas`
        WHERE `'.DBPREFIX.'backend_areas`.`area_id` = 179
    ',
    '
        UPDATE `'.DBPREFIX.'backend_areas`
        SET `order_id` = 4
        WHERE `'.DBPREFIX.'backend_areas`.`area_id` = 8
    ',
    '
        UPDATE `'.DBPREFIX.'backend_areas`
        SET `order_id` = 5
        WHERE `'.DBPREFIX.'backend_areas`.`area_id` = 29
    ',
    '
        UPDATE `'.DBPREFIX.'backend_areas`
        SET `order_id` = 6
        WHERE `'.DBPREFIX.'backend_areas`.`area_id` = 2
    ',
    '
        TRUNCATE TABLE `'.DBPREFIX.'module_repository`
    ',
);
$updatesStableToHotfix = array(
    'UPDATE `'.DBPREFIX.'content_page` SET `customContent` = \'\' WHERE `customContent` = \'(Default)\'',
);
$updatesHotfixToSp1 = array(
    array(
        'table' => DBPREFIX.'module_block_rel_lang_content',
        'structure' => array(
            'block_id'       => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0'),
            'lang_id'        => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'block_id'),
            'content'        => array('type' => 'mediumtext', 'after' => 'lang_id'),
            'active'         => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'content'),
        ),
        'keys' => array(
            'id_lang'        => array('fields' => array('block_id','lang_id'), 'type' => 'UNIQUE'),
        ),
    ),
    array(
        'table' => DBPREFIX.'module_contact_form',
        'structure' => array(
            'id'                     => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'mails'                  => array('type' => 'text', 'after' => 'id'),
            'showForm'               => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'mails'),
            'use_captcha'            => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'showForm'),
            'use_custom_style'       => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'use_captcha'),
            'send_copy'              => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'use_custom_style'),
            'use_email_of_sender'    => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'send_copy'),
            'html_mail'              => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'use_email_of_sender'),
            'send_attachment'        => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'html_mail'),
        ),
        'keys' => array(
        ),
    ),
    array(
        'table' => DBPREFIX.'module_newsletter_access_user',
        'structure' => array(
            'accessUserID'               => array('type' => 'INT(5)', 'unsigned' => true),
            'newsletterCategoryID'       => array('type' => 'INT(11)', 'after' => 'accessUserID'),
            'code'                       => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'newsletterCategoryID'),
        ),
        'keys' => array(
            'rel'                        => array('fields' => array('accessUserID','newsletterCategoryID'), 'type' => 'UNIQUE'),
            'accessUserID'               => array('fields' => array('accessUserID')),
        ),
    ),
    'UPDATE '.DBPREFIX.'module_newsletter_access_user SET `code` = SUBSTR(MD5(RAND()),1,12) WHERE `code` = \'\'',
    array(
        'table' => DBPREFIX.'content_page',
        'structure' => array(
            'id'                                 => array('type' => 'INT(11)', 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'node_id'                            => array('type' => 'INT(11)', 'notnull' => false, 'after' => 'id'),
            'nodeIdShadowed'                     => array('type' => 'INT(11)', 'notnull' => false, 'after' => 'node_id'),
            'lang'                               => array('type' => 'INT(11)', 'after' => 'nodeIdShadowed'),
            'type'                               => array('type' => 'VARCHAR(16)', 'after' => 'lang'),
            'caching'                            => array('type' => 'TINYINT(1)', 'after' => 'type'),
            'updatedAt'                          => array('type' => 'timestamp', 'default' => null, 'notnull' => false, 'after' => 'caching'),
            'updatedBy'                          => array('type' => 'CHAR(40)', 'after' => 'updatedAt'),
            'title'                              => array('type' => 'VARCHAR(255)', 'after' => 'updatedBy'),
            'linkTarget'                         => array('type' => 'VARCHAR(16)', 'notnull' => false, 'after' => 'title'),
            'contentTitle'                       => array('type' => 'VARCHAR(255)', 'after' => 'linkTarget'),
            'slug'                               => array('type' => 'VARCHAR(255)', 'after' => 'contentTitle'),
            'content'                            => array('type' => 'longtext', 'after' => 'slug'),
            'sourceMode'                         => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'content'),
            'customContent'                      => array('type' => 'VARCHAR(64)', 'notnull' => false, 'after' => 'sourceMode'),
            'cssName'                            => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'customContent'),
            'cssNavName'                         => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'cssName'),
            'skin'                               => array('type' => 'INT(11)', 'notnull' => false, 'after' => 'cssNavName'),
            'metatitle'                          => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'skin'),
            'metadesc'                           => array('type' => 'text', 'after' => 'metatitle'),
            'metakeys'                           => array('type' => 'text', 'after' => 'metadesc'),
            'metarobots'                         => array('type' => 'VARCHAR(7)', 'notnull' => false, 'after' => 'metakeys'),
            'start'                              => array('type' => 'timestamp', 'notnull' => false, 'default' => null, 'after' => 'metarobots'),
            'end'                                => array('type' => 'timestamp', 'notnull' => false, 'default' => null, 'after' => 'start'),
            'editingStatus'                      => array('type' => 'VARCHAR(16)', 'after' => 'end'),
            'protection'                         => array('type' => 'INT(11)', 'after' => 'editingStatus'),
            'frontendAccessId'                   => array('type' => 'INT(11)', 'after' => 'protection'),
            'backendAccessId'                    => array('type' => 'INT(11)', 'after' => 'frontendAccessId'),
            'display'                            => array('type' => 'TINYINT(1)', 'after' => 'backendAccessId'),
            'active'                             => array('type' => 'TINYINT(1)', 'after' => 'display'),
            'target'                             => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'active'),
            'module'                             => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'target'),
            'cmd'                                => array('type' => 'VARCHAR(50)', 'notnull' => true, 'default' => '', 'after' => 'module'),
        ),
        'keys' => array(
            'node_id'                            => array('fields' => array('node_id','lang'), 'type' => 'UNIQUE'),
            'IDX_D8E86F54460D9FD7'               => array('fields' => array('node_id')),
        ),
        'engine' => 'InnoDB',
    ),
    array(
        'table' => DBPREFIX.'core_mail_template',
        'structure' => array(
            'key'            => array('type' => 'tinytext'),
            'section'        => array('type' => 'tinytext', 'notnull' => true, 'after' => 'key'),
            'text_id'        => array('type' => 'INT(10)', 'unsigned' => true, 'after' => 'section'),
            'html'           => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'text_id'),
            'protected'      => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'html'),
        ),
        'keys' => array(),
    ),
    '
        ALTER TABLE `' . DBPREFIX.'core_mail_template` ADD PRIMARY KEY (`key` (32), `section` (32))
    ',
    array(
        'table' => DBPREFIX.'languages',
        'structure' => array(
            'id'                     => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'lang'                   => array('type' => 'VARCHAR(5)', 'notnull' => true, 'default' => '', 'after' => 'id'),
            'name'                   => array('type' => 'VARCHAR(250)', 'notnull' => true, 'default' => '', 'after' => 'lang'),
            'charset'                => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => 'iso-8859-1', 'after' => 'name'),
            'themesid'               => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'charset'),
            'print_themes_id'        => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'themesid'),
            'pdf_themes_id'          => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'print_themes_id'),
            'frontend'               => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'pdf_themes_id'),
            'backend'                => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'frontend'),
            'is_default'             => array('type' => 'SET(\'true\',\'false\')', 'notnull' => true, 'default' => 'false', 'after' => 'backend'),
            'mobile_themes_id'       => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'is_default'),
            'fallback'               => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'mobile_themes_id'),
            'app_themes_id'          => array('type' => 'INT(2)', 'after' => 'fallback'),
        ),
        'keys' => array(
            'lang'                   => array('fields' => array('lang'), 'type' => 'UNIQUE'),
            'defaultstatus'          => array('fields' => array('is_default')),
        ),
    ),
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_alias_source`
    ',
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_alias_target`
    ',
    array(
        'table' => DBPREFIX.'sessions',
        'structure' => array(
            'sessionid'      => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'primary' => true),
            'remember_me'    => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'sessionid'),
            'startdate'      => array('type' => 'VARCHAR(14)', 'notnull' => true, 'default' => '', 'after' => 'remember_me'),
            'lastupdated'    => array('type' => 'VARCHAR(14)', 'notnull' => true, 'default' => '', 'after' => 'startdate'),
            'status'         => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'lastupdated'),
            'user_id'        => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'status'),
            'datavalue'      => array('type' => 'text', 'notnull' => false, 'after' => 'user_id'),
        ),
        'keys' => array(
            'LastUpdated'    => array('fields' => array('lastupdated')),
        ),
    ),
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_shop_countries`
    ',
    array(
        'table' => DBPREFIX.'module_shop_currencies',
        'structure' => array(
            'id'             => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'code'           => array('type' => 'CHAR(3)', 'notnull' => true, 'default' => '', 'after' => 'id'),
            'symbol'         => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'code'),
            'rate'           => array('type' => 'DECIMAL(10,4)', 'unsigned' => true, 'notnull' => true, 'default' => '1.0000', 'after' => 'symbol'),
            'ord'            => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'rate'),
            'active'         => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'ord'),
            'default'        => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'active'),
            'increment'      => array('type' => 'DECIMAL(6,5)', 'unsigned' => true, 'notnull' => true, 'default' => '0.01000', 'after' => 'default'),
        ),
        'keys' => array(),
    ),
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_shop_mail`
    ',
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_shop_mail_content`
    ',
    array(
        'table' => DBPREFIX.'module_shop_payment_processors',
        'structure' => array(
            'id'             => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'type'           => array('type' => 'ENUM(\'internal\',\'external\')', 'notnull' => true, 'default' => 'internal', 'after' => 'id'),
            'name'           => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'type'),
            'description'    => array('type' => 'text', 'after' => 'name'),
            'company_url'    => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'description'),
            'status'         => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'company_url'),
            'picture'        => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'status'),
        ),
        'keys' => array(),
    ),
    '
        DROP TABLE IF EXISTS `'.DBPREFIX.'module_shop_products_downloads`
    ',
);

$updatesSp1ToSp2 = array(
    array (
        'table' => DBPREFIX.'module_block_categories',
        'structure' => array(
            'id'             => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'parent'         => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'name'           => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'parent'),
            'seperator'      => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'name'),
            'order'          => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'seperator'),
            'status'         => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '1', 'after' => 'order')
        ),
    ),
    array (
        'table' => DBPREFIX.'module_block_blocks',
        'structure' => array(
            'id'                 => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'start'              => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'end'                => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'start'),
            'name'               => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'end'),
            'random'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'name'),
            'random_2'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random'),
            'random_3'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random_2'),
            'random_4'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random_3'),
            'global'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random_4'),
            'category'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'global'),
            'direct'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'category'),
            'active'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'direct'),
            'order'              => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'active'),
            'cat'                => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'order'),
            'wysiwyg_editor'     => array('type' => 'INT(1)', 'notnull' => true, 'default' => '1', 'after' => 'cat')
        ),
    ),
    array (
        'table' => DBPREFIX.'module_block_rel_pages',
        'structure' => array(
            'block_id'       => array('type' => 'INT(7)', 'notnull' => true, 'default' => '0', 'primary' => true),
            'page_id'        => array('type' => 'INT(7)', 'notnull' => true, 'default' => '0', 'after' => 'block_id', 'primary' => true),
            'placeholder'    => array('type' => 'ENUM(\'global\',\'direct\',\'category\')', 'notnull' => true, 'default' => 'global', 'after' => 'page_id', 'primary' => true)
        ),
    ),
    '
        INSERT IGNORE INTO `'.DBPREFIX.'access_settings` (`key`, `value`, `status`) VALUES
        (\'sociallogin\', \'\', 0),
        (\'sociallogin_active_automatically\', \'\', 1),
        (\'sociallogin_assign_to_groups\', \'3\', 0),
        (\'sociallogin_show_signup\', \'\', 0),
        (\'use_usernames\', \'0\', 1)
    ',
    array(
        'table' => DBPREFIX.'access_users',
        'structure' => array(
            'id'                     => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'is_admin'               => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'username'               => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'is_admin'),
            'password'               => array('type' => 'VARCHAR(32)', 'notnull' => false, 'after' => 'username'),
            'regdate'                => array('type' => 'INT(14)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'password'),
            'expiration'             => array('type' => 'INT(14)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'regdate'),
            'validity'               => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'expiration'),
            'last_auth'              => array('type' => 'INT(14)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'validity'),
            'last_auth_status'       => array('type' => 'INT(1)', 'notnull' => true, 'default' => '1', 'after' => 'last_auth'),
            'last_activity'          => array('type' => 'INT(14)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'last_auth_status'),
            'email'                  => array('type' => 'VARCHAR(255)', 'notnull' => false, 'after' => 'last_activity'),
            'email_access'           => array('type' => 'ENUM(\'everyone\',\'members_only\',\'nobody\')', 'notnull' => true, 'default' => 'nobody', 'after' => 'email'),
            'frontend_lang_id'       => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'email_access'),
            'backend_lang_id'        => array('type' => 'INT(2)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'frontend_lang_id'),
            'active'                 => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'backend_lang_id'),
            'primary_group'          => array('type' => 'INT(6)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'active'),
            'profile_access'         => array('type' => 'ENUM(\'everyone\',\'members_only\',\'nobody\')', 'notnull' => true, 'default' => 'members_only', 'after' => 'primary_group'),
            'restore_key'            => array('type' => 'VARCHAR(32)', 'notnull' => true, 'default' => '', 'after' => 'profile_access'),
            'restore_key_time'       => array('type' => 'INT(14)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'restore_key'),
            'u2u_active'             => array('type' => 'ENUM(\'0\',\'1\')', 'notnull' => true, 'default' => '1', 'after' => 'restore_key_time'),
        ),
        'keys' => array(
            'username'               => array('fields' => array('username'))
        ),
    ),
    array(
        'table' => DBPREFIX.'access_user_network',
        'structure' => array(
            'id'                 => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'oauth_provider'     => array('type' => 'VARCHAR(100)', 'notnull' => true, 'default' => '', 'after' => 'id'),
            'oauth_id'           => array('type' => 'VARCHAR(100)', 'notnull' => true, 'default' => '', 'after' => 'oauth_provider'),
            'user_id'            => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'oauth_id')
        ),
    ),
    '
        INSERT IGNORE INTO `'.DBPREFIX.'core_setting` (`section`, `name`, `group`, `type`, `value`, `values`, `ord`) VALUES
        (\'access\', \'providers\', \'sociallogin\', \'text\', \'{"facebook":{"active":"0","settings":["",""]},"twitter":{"active":"0","settings":["",""]},"google":{"active":"0","settings":["","",""]}}\', \'\', 0)
    ',
    array(
        'table' => DBPREFIX.'module_knowledge_tags_articles',
        'structure' => array(
            'article'    => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0'),
            'tag'        => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'article'),
        ),
        'keys' => array(
            'article'    => array('fields' => array('article','tag'), 'type' => 'UNIQUE'),
        )
    ),
);

$userData = json_encode(array(
    'id'   => $_SESSION['contrexx_update']['user_id'],
    'name' => $_SESSION['contrexx_update']['username'],
));
$updatesSp2ToSp3 = array(
    array (
        'table' => DBPREFIX.'module_block_categories',
        'structure' => array(
            'id'             => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'parent'         => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'name'           => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'parent'),
            'seperator'      => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'name'),
            'order'          => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'seperator'),
            'status'         => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '1', 'after' => 'order')
        ),
    ),
    array (
        'table' => DBPREFIX.'module_block_blocks',
        'structure' => array(
            'id'                 => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'start'              => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'end'                => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'start'),
            'name'               => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'end'),
            'random'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'name'),
            'random_2'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random'),
            'random_3'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random_2'),
            'random_4'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random_3'),
            'global'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'random_4'),
            'category'           => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'global'),
            'direct'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'category'),
            'active'             => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'direct'),
            'order'              => array('type' => 'INT(1)', 'notnull' => true, 'default' => '0', 'after' => 'active'),
            'cat'                => array('type' => 'INT(10)', 'notnull' => true, 'default' => '0', 'after' => 'order'),
            'wysiwyg_editor'     => array('type' => 'INT(1)', 'notnull' => true, 'default' => '1', 'after' => 'cat')
        ),
    ),
    array (
        'table' => DBPREFIX.'module_block_rel_pages',
        'structure' => array(
            'block_id'       => array('type' => 'INT(7)', 'notnull' => true, 'default' => '0', 'primary' => true),
            'page_id'        => array('type' => 'INT(7)', 'notnull' => true, 'default' => '0', 'after' => 'block_id', 'primary' => true),
            'placeholder'    => array('type' => 'ENUM(\'global\',\'direct\',\'category\')', 'notnull' => true, 'default' => 'global', 'after' => 'page_id', 'primary' => true)
        ),
    ),
    "INSERT INTO `".DBPREFIX."access_settings` (`key`, `value`, `status`) VALUES ('use_usernames', '0', '1') ON DUPLICATE KEY UPDATE `key` = `key`",
    array (
        'table' => DBPREFIX.'settings_image',
        'structure' => array(
            'id'         => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'name'       => array('type' => 'VARCHAR(50)', 'after' => 'id'),
            'value'      => array('type' => 'text', 'after' => 'name')
        ),
    ),
    "INSERT IGNORE INTO `".DBPREFIX."settings_image` (`name`, `value`) VALUES ('image_cut_width', '500')",
    "INSERT IGNORE INTO `".DBPREFIX."settings_image` (`name`, `value`) VALUES ('image_cut_height', '500')",
    "INSERT IGNORE INTO `".DBPREFIX."settings_image` (`name`, `value`) VALUES ('image_scale_width', '800')",
    "INSERT IGNORE INTO `".DBPREFIX."settings_image` (`name`, `value`) VALUES ('image_scale_height', '800')",
    "INSERT IGNORE INTO `".DBPREFIX."settings_image` (`name`, `value`) VALUES ('image_compression', '100')",
    array (
        'table' => DBPREFIX.'module_egov_product_calendar',
        'structure' => array(
            'calendar_id'            => array('type' => 'INT(11)', 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'calendar_product'       => array('type' => 'INT(11)', 'notnull' => true, 'default' => '0', 'after' => 'calendar_id'),
            'calendar_order'         => array('type' => 'INT(11)', 'notnull' => true, 'default' => '0', 'after' => 'calendar_product'),
            'calendar_day'           => array('type' => 'INT(2)', 'notnull' => true, 'default' => '0', 'after' => 'calendar_order'),
            'calendar_month'         => array('type' => 'INT(2)', 'zerofill' => true, 'default' => '00', 'after' => 'calendar_day'),
            'calendar_year'          => array('type' => 'INT(4)', 'notnull' => true, 'default' => '0', 'after' => 'calendar_month'),
            'calendar_act'           => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'calendar_year')
        ),
        'keys' => array(
            'calendar_product'       => array('fields' => array('calendar_product'))
        ),
    ),
    array (
        'table' => DBPREFIX.'voting_results',
        'structure' => array(
            'id'                     => array('type' => 'INT(11)', 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'voting_system_id'       => array('type' => 'INT(11)', 'notnull' => false, 'after' => 'id'),
            'question'               => array('type' => 'CHAR(200)', 'notnull' => false, 'after' => 'voting_system_id'),
            'votes'                  => array('type' => 'INT(11)', 'notnull' => false, 'default' => '0', 'after' => 'question')
        ),
    ),
    array (
        'table' => DBPREFIX.'voting_system',
        'structure' => array(
            'id'                     => array('type' => 'INT(11)', 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'date'                   => array('type' => 'timestamp', 'notnull' => true, 'after' => 'id'),
            'title'                  => array('type' => 'VARCHAR(60)', 'notnull' => true, 'default' => '', 'after' => 'date'),
            'question'               => array('type' => 'text', 'after' => 'title', 'notnull' => false),
            'status'                 => array('type' => 'TINYINT(1)', 'default' => '1', 'notnull' => false, 'after' => 'question'),
            'submit_check'           => array('type' => 'ENUM(\'cookie\',\'email\')', 'notnull' => true, 'default' => 'cookie', 'after' => 'status'),
            'votes'                  => array('type' => 'INT(11)', 'notnull' => false, 'default' => '0', 'after' => 'submit_check'),
            'additional_nickname'    => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'votes'),
            'additional_forename'    => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_nickname'),
            'additional_surname'     => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_forename'),
            'additional_phone'       => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_surname'),
            'additional_street'      => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_phone'),
            'additional_zip'         => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_street'),
            'additional_email'       => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_zip'),
            'additional_city'        => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_email'),
            'additional_comment'     => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'additional_city')
        ),
    ),
    array (
        'table' => DBPREFIX.'module_shop_currencies',
        'structure' => array(
            'id' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'code' => array('type' => 'CHAR(3)', 'notnull' => true, 'default' => '', 'after' => 'id'),
            'symbol' => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'code'),
            'rate' => array('type' => 'DECIMAL(10,4)', 'unsigned' => true, 'notnull' => true, 'default' => '1.0000', 'after' => 'symbol'),
            'ord' => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'rate'),
            'active' => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'after' => 'ord'),
            'default' => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'active'),
            'increment' => array('type' => 'DECIMAL(6,5)', 'unsigned' => true, 'notnull' => true, 'default' => '0.01', 'after' => 'default'),
        ),
    ),
    array (
        'table' => DBPREFIX.'module_shop_payment_processors',
        'structure' => array(
            'id' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'type' => array('type' => 'ENUM(\'internal\',\'external\')', 'notnull' => true, 'default' => 'internal'),
            'name' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => ''),
            'description' => array('type' => 'TEXT'),
            'company_url' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => ''),
            'status' => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1'),
            'picture' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => ''),
        ),
    ),
    array (
        'table' => DBPREFIX.'module_knowledge_tags_articles',
        'structure' => array(
            'article'    => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0'),
            'tag'        => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'article')
        ),
        'keys' => array(
            'article'    => array('fields' => array('article', 'tag'), 'type' => 'UNIQUE', 'force' => true)
        ),
    ),
    "INSERT INTO `".DBPREFIX."access_settings` (`key`, `value`, `status`) VALUES ('sociallogin_activation_timeout', '10', '0') ON DUPLICATE KEY UPDATE `key` = `key`",
    array(
        'table' => DBPREFIX.'access_user_profile',
        'structure' => array(
            'user_id'            => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'primary' => true),
            'gender'             => array('type' => 'ENUM(\'gender_undefined\',\'gender_female\',\'gender_male\')', 'notnull' => true, 'default' => 'gender_undefined', 'after' => 'user_id'),
            'title'              => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'gender'),
            'firstname'          => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'title'),
            'lastname'           => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'firstname'),
            'company'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'lastname'),
            'address'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'company'),
            'city'               => array('type' => 'VARCHAR(50)', 'notnull' => true, 'default' => '', 'after' => 'address'),
            'zip'                => array('type' => 'VARCHAR(10)', 'notnull' => true, 'default' => '', 'after' => 'city'),
            'country'            => array('type' => 'SMALLINT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'zip'),
            'phone_office'       => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'country'),
            'phone_private'      => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'phone_office'),
            'phone_mobile'       => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'phone_private'),
            'phone_fax'          => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => '', 'after' => 'phone_mobile'),
            'birthday'           => array('type' => 'VARCHAR(11)', 'notnull' => false, 'after' => 'phone_fax'),
            'website'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'birthday'),
            'profession'         => array('type' => 'VARCHAR(150)', 'notnull' => true, 'default' => '', 'after' => 'website'),
            'interests'          => array('type' => 'text', 'notnull' => true, 'after' => 'profession'),
            'signature'          => array('type' => 'text', 'notnull' => true, 'after' => 'interests'),
            'picture'            => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'signature'),
        ),
        'keys' => array(
            'profile'        => array('fields' => array('firstname' => 100, 'lastname' => 100, 'company' => 50))
        ),
        'engine' => 'InnoDB',
    ),
    array (
        'table' => DBPREFIX.'core_setting',
        'structure' => array(
            'section' => array('type' => 'VARCHAR(32)', 'default' => '', 'primary' => true),
            'name' => array('type' => 'VARCHAR(255)', 'default' => '', 'primary' => true),
            'group' => array('type' => 'VARCHAR(32)', 'default' => '', 'primary' => true),
            'type' => array('type' => 'VARCHAR(32)', 'notnull' => true, 'default' => 'text', 'after' => 'group'),
            'value' => array('type' => 'text', 'notnull' => true, 'after' => 'type'),
            'values' => array('type' => 'text', 'notnull' => true, 'after' => 'value'),
            'ord' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'values'),
        ),
    ),
    array (
        'table' => DBPREFIX.'core_text',
        'structure' => array(
            'id' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'primary' => true),
            'lang_id' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'default' => '1', 'primary' => true, 'after' => 'id'),
            'section' => array('type' => 'VARCHAR(32)', 'notnull' => true, 'default' => '', 'primary' => true, 'after' => 'lang_id'),
            'key' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'primary' => true, 'after' => 'section'),
            'text' => array('type' => 'text', 'after' => 'key'),
        ),
        'keys' => array(
            'text' => array('fields' => array('text'), 'type' => 'FULLTEXT'),
        ),
    ),
    'UPDATE `' . DBPREFIX . 'log_entry` SET `username` = \'' . $userData . '\' WHERE `username` = \'currently_loggedin_user\'',
    array (
        'table' => DBPREFIX.'module_newsletter_tmp_sending',
        'structure' => array(
            'id'             => array('type' => 'INT(11)', 'notnull' => true, 'auto_increment' => true, 'primary' => true),
            'newsletter'     => array('type' => 'INT(11)', 'notnull' => true, 'default' => '0', 'after' => 'id'),
            'email'          => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => '', 'after' => 'newsletter'),
            'sendt'          => array('type' => 'TINYINT(1)', 'notnull' => true, 'default' => '0', 'after' => 'email'),
            'type'           => array('type' => 'ENUM(\'access\',\'newsletter\',\'core\')', 'notnull' => true, 'default' => 'newsletter', 'after' => 'sendt'),
            'code'           => array('type' => 'VARCHAR(10)', 'after' => 'type'),
        ),
        'keys' => array(
            'unique_email'   => array('fields' => array('newsletter','email'), 'type' => 'UNIQUE'),
            'email'          => array('fields' => array('email')),
        ),
    ),
    'UPDATE `' . DBPREFIX . 'modules` SET `status` = \'y\' WHERE `id` = 68',
);

$updatesSp3ToSp4 = array(
    'UPDATE  `' . DBPREFIX . 'backend_areas` SET  `scope` =  \'backend\' WHERE  `area_id` = 161',
);

$updatesRc1ToSp4    = array_merge($updatesRc1ToRc2, $updatesRc2ToStable, $updatesStableToHotfix, $updatesHotfixToSp1, $updatesSp1ToSp2, $updatesSp2ToSp3, $updatesSp3ToSp4);
$updatesRc2ToSp4    = array_merge($updatesRc2ToStable, $updatesStableToHotfix, $updatesHotfixToSp1, $updatesSp1ToSp2, $updatesSp2ToSp3, $updatesSp3ToSp4);
$updatesStableToSp4 = array_merge($updatesStableToHotfix, $updatesHotfixToSp1, $updatesSp1ToSp2, $updatesSp2ToSp3, $updatesSp3ToSp4);
$updatesHotfixToSp4 = array_merge($updatesHotfixToSp1, $updatesSp1ToSp2, $updatesSp2ToSp3, $updatesSp3ToSp4);
$updatesSp1ToSp4    = array_merge($updatesSp1ToSp2, $updatesSp2ToSp3, $updatesSp3ToSp4);
$updatesSp2ToSp4    = array_merge($updatesSp2ToSp3, $updatesSp3ToSp4);
$updatesSp3ToSp4    = $updatesSp3ToSp4;

if ($version == 'rc1') {
    $updates = $updatesRc1ToSp4;
} elseif ($version == 'rc2') {
    $updates = $updatesRc2ToSp4;
} elseif ($version == 'stable') {
    $updates = $updatesStableToSp4;
} elseif ($version == 'hotfix') {
    $updates = $updatesHotfixToSp4;
} elseif ($version == 'sp1') {
    $updates = $updatesSp1ToSp4;
} elseif ($version == 'sp2') {
    $updates = $updatesSp2ToSp4;
} else {
    $updates = $updatesSp3ToSp4;
}

foreach ($updates as $update) {
    if (is_array($update)) {
        try {
            \Cx\Lib\UpdateUtil::table(
                $update['table'],
                $update['structure'],
                $update['keys'],
                isset($update['engine']) ? $update['engine'] : 'MyISAM'
            );
        } catch (\Cx\Lib\UpdateException $e) {
            return \Cx\Lib\UpdateUtil::DefaultActionHandler($e);
        }
    } else {
        $result = \Cx\Lib\UpdateUtil::sql($update);
        if (!$result) {
            setUpdateMsg('Update failed: ' . contrexx_raw2xhtml($update));
            return false;
        }
    }
}

// reimport module repository
\Cx\Lib\UpdateUtil::sql('TRUNCATE TABLE `'.DBPREFIX.'module_repository`');
$sqlQuery = '';
$fp = @fopen($documentRoot.'/installer/data/contrexx_dump_data.sql', 'r');
if ($fp !== false) {
    while (!feof($fp)) {
        $buffer = fgets($fp);
        if ((substr($buffer,0,1) != '#') && (substr($buffer,0,2) != '--')) {
            $sqlQuery .= $buffer;
            if (preg_match('/;[ \t\r\n]*$/', $buffer)) {
                $sqlQuery = preg_replace('/SET FOREIGN_KEY_CHECKS = 0;/', '', $sqlQuery);
                if (substr(trim($sqlQuery), 0, 40) != 'INSERT INTO `contrexx_module_repository`') {
                    $sqlQuery = '';
                    continue;
                }
                $sqlQuery = preg_replace('#`contrexx_module_repository`#', '`'.DBPREFIX.'module_repository`', $sqlQuery);
                $result = \Cx\Lib\UpdateUtil::sql($sqlQuery);
                if ($result === false) {
                    setUpdateMsg('Update failed: ' . contrexx_raw2xhtml($sqlQuery));
                    return false;
                }
                $sqlQuery = '';
            }
        }
    }
} else {
    die('Could not read data dump file!');
}

// add missing "remove page" log entries
$sqlQuery = '
    SELECT
        MAX(l1.version) as `version`,
        l1.object_id
    FROM
        `' . DBPREFIX . 'log_entry` AS l1
    WHERE
        l1.object_id NOT IN (
            SELECT
                id
            FROM
                `' . DBPREFIX . 'content_page`
        )
        AND l1.object_id NOT IN (
            SELECT
                l3.object_id
            FROM
                `' . DBPREFIX . 'log_entry` AS l3
            WHERE
                l3.action LIKE \'remove\'
        )
    GROUP BY
        l1.object_id
';
$result = \Cx\Lib\UpdateUtil::sql($sqlQuery);
if ($result === false) {
    // error, abort
    setUpdateMsg('Update failed: ' . contrexx_raw2xhtml($sqlQuery));
    return false;
}
while (!$result->EOF) {
    $sqlQuery = '
        INSERT INTO
            `' . DBPREFIX . 'log_entry`
            (
                `action`,
                `logged_at`,
                `version`,
                `object_id`,
                `object_class`,
                `data`,
                `username`
            )
        VALUES
            (
                \'remove\',
                NOW(),
                ' . ($result->fields['version'] + 1) . ',
                ' . $result->fields['object_id'] . ',
                \'Cx\\\\Core\\\\ContentManager\\\\Model\\\\Doctrine\\\\Entity\\\\Page\',
                \'N;\',
                \'' . $userData . '\'
            )
    ';
    $result2 = \Cx\Lib\UpdateUtil::sql($sqlQuery);
    if ($result2 === false) {
        // error, abort
        setUpdateMsg('Update failed: ' . contrexx_raw2xhtml($sqlQuery));
        return false;
    }
    $result->MoveNext();
}


if ($version == 'rc1') {
    $em = \Env::em();
    $pageRepo = $em->getRepository('Cx\Model\ContentManager\Page');

    $fallbackPages = $pageRepo->findBy(array(
        'type' => \Cx\Model\ContentManager\Page::TYPE_FALLBACK,
    ));

    foreach ($fallbackPages as $page) {
        $page->setModule($page->getModule());
        $page->setCmd($page->getCmd());
        $page->setUpdatedAtToNow();
        $em->persist($page);
    }
    $em->flush();
}

// update for sp3
if ($version == 'rc1' || $version == 'rc2'
    || $version == '3.0.0' || $version == '3.0.0.1'
    || $version == '3.0.1' || $version == '3.0.2') {

    // newsletter module
    // decode the urls of newsletter module
    try {
        $objResult = \Cx\Lib\UpdateUtil::sql('SELECT `id`, `url` FROM `'.DBPREFIX.'module_newsletter_email_link`');
        if ($objResult !== false && $objResult->RecordCount() > 0) {
            while (!$objResult->EOF) {
                \Cx\Lib\UpdateUtil::sql(
                    'UPDATE `'.DBPREFIX.'module_newsletter_email_link` SET `url` = ? WHERE `id` = ?',
                        array(html_entity_decode($objResult->fields['url'], ENT_QUOTES, CONTREXX_CHARSET), $objResult->fields['id'])
                );
                $objResult->MoveNext();
            }
        }
        $_SESSION['contrexx_update']['newsletter_links_decoded'] = true;
    } catch (\Cx\Lib\UpdateException $e) {
        return \Cx\Lib\UpdateUtil::DefaultActionHandler($e);
    }

    // shop
    $table_name = DBPREFIX.'module_shop_currencies';
    if (   \Cx\Lib\UpdateUtil::table_exist($table_name)
        && \Cx\Lib\UpdateUtil::column_exist($table_name, 'name')) {
        $query = "
            UPDATE `$table_name`
            SET sort_order = 0 WHERE sort_order IS NULL";
        \Cx\Lib\UpdateUtil::sql($query);
        // Currencies table fields
        \Cx\Lib\UpdateUtil::table($table_name,
            array(
                'id' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
                'code' => array('type' => 'CHAR(3)', 'notnull' => true, 'default' => ''),
                'symbol' => array('type' => 'VARCHAR(20)', 'notnull' => true, 'default' => ''),
                'name' => array('type' => 'VARCHAR(50)', 'notnull' => true, 'default' => ''),
                'rate' => array('type' => 'DECIMAL(10,4)', 'unsigned' => true, 'notnull' => true, 'default' => '1.0000'),
                'sort_order' => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0'),
                'status' => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1'),
                'is_default' => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '0'),
            )
        );
    }
    $table_name = DBPREFIX.'module_shop_payment_processors';
    if (Cx\Lib\UpdateUtil::table_exist($table_name)) {
        \Cx\Lib\UpdateUtil::table($table_name,
            array(
                'id' => array('type' => 'INT(10)', 'unsigned' => true, 'notnull' => true, 'auto_increment' => true, 'primary' => true),
                'type' => array('type' => 'ENUM(\'internal\',\'external\')', 'notnull' => true, 'default' => 'internal'),
                'name' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => ''),
                'description' => array('type' => 'TEXT'),
                'company_url' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => ''),
                'status' => array('type' => 'TINYINT(1)', 'unsigned' => true, 'notnull' => true, 'default' => '1'),
                'picture' => array('type' => 'VARCHAR(255)', 'notnull' => true, 'default' => ''),
            )
        );
    }

    // contact core_module
    // update the content pages
    $em = \Env::em();
    $cl = \Env::get('ClassLoader');
    $cl->loadFile(ASCMS_CORE_MODULE_PATH . '/contact/admin.class.php');
    $pageRepo = $em->getRepository('Cx\Model\ContentManager\Page');
    $Contact = new \ContactManager();
    $Contact->initContactForms();

    foreach ($Contact->arrForms as $id => $form) {
        foreach ($form['lang'] as $langId => $lang) {
            if ($lang['is_active'] == true) {
                $page = $pageRepo->findOneByModuleCmdLang('contact', $id, $langId);
                if ($page) {
                    $page->setContent($Contact->_getSourceCode($id, $langId));
                    $page->setUpdatedAtToNow();
                    $em->persist($page);
                }
            }
        }
    }
    $em->flush();

}

if (   !$objUpdate->_isNewerVersion($_CONFIG['coreCmsVersion'], '3.0.0')
    && $objUpdate->_isNewerVersion($_CONFIG['coreCmsVersion'], '3.0.3')
) {
    try {
        // replace sigma template block in discounts page
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegex(
            array('module'=>'shop', 'cmd' => 'discounts'),
            '/<!--\s+(BEGIN|END)\s+shopProductRow1\s+-->/', '<!-- $1 shopProductRow -->',
            array('content'), '3.0.3'
        );


        // add product options to product-listing and discounts page
        $search = array(
            '/.*\{SHOP_PRODUCT_DESCRIPTION\}.*/ms',
        );
        $callback = function($matches) {
            $htmlProductOptions = <<<HTML

                    <!-- BEGIN shopProductOptionsRow -->
                    <div class="shop_options">
                        {SHOP_PRODUCT_OPTIONS_TITLE}<br />
                        <div id="product_options_layer{SHOP_PRODUCT_ID}" style="display: none;">
                            <div class="shop_options_click">
                                <!-- BEGIN shopProductOptionsValuesRow -->
                                <strong>
                                    {SHOP_PRODUCT_OPTIONS_NAME}
                                    <!-- BEGIN product_attribute_mandatory -->
                                    <span class="mandatory">&nbsp;*</span>
                                    <!-- END product_attribute_mandatory -->
                                </strong><br />
                                {SHOP_PRODCUT_OPTION}
                                <!-- END shopProductOptionsValuesRow -->
                            </div>
                        </div>
                    </div>
                    <!-- END shopProductOptionsRow -->

HTML;
            if (!preg_match('/<!--\s+BEGIN\s+shopProductOptionsRow\s+-->.*<!--\s+END\s+shopProductOptionsRow\s+-->/ms', $matches[0])) {
                $placeholder = '{SHOP_PRODUCT_DESCRIPTION}';
                return str_replace($placeholder, $placeholder.$htmlProductOptions, $matches[0]);
            } else {
                return $matches[0];
            }
        };
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegexCallback(array('module' => 'shop', 'cmd' => ''), $search, $callback, array('content'), '3.0.3');
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegexCallback(array('module' => 'shop', 'cmd' => 'discounts'), $search, $callback, array('content'), '3.0.3');


        // add needed placeholders
        // this adds the missing placeholders [[SHOP_AGB]], [[SHOP_CANCELLATION_TERMS_CHECKED]]
        $search = array(
        '/(<input[^>]+name=")(agb|cancellation_terms)(")([^>]*>)/ms',
        );
        $callback = function($matches) {
            switch ($matches[2]) {
                case 'agb':
                    $placeholder = "{SHOP_AGB}";
                    break;
                case 'cancellation_terms':
                    $placeholder = "{SHOP_CANCELLATION_TERMS_CHECKED}";
                    break;
            }
            if (strpos($matches[1].$matches[4], $placeholder) === false) {
                return $matches[1].$matches[2].$matches[3].' '.$placeholder.' '.$matches[4];
            } else {
                return $matches[0];
            }
        };
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegexCallback(array('module' => 'shop', 'cmd' => 'payment'), $search, $callback, array('content'), '3.0.3');
        \Cx\Lib\UpdateUtil::setSourceModeOnContentPage(array('module' => 'shop', 'cmd' => 'payment'), '3.0.3');

        // replace comments placeholder with a sigma block , news module
        $search = array(
            '/.*\{NEWS_COUNT_COMMENTS\}.*/ms',
        );
        $callback = function($matches) {
            $placeholder = '{NEWS_COUNT_COMMENTS}';
            $htmlCode = '<!-- BEGIN news_comments_count -->'.$placeholder.'<!-- END news_comments_count -->';
            if (!preg_match('/<!--\s+BEGIN\s+news_comments_count\s+-->.*<!--\s+END\s+news_comments_count\s+-->/ms', $matches[0])) {
                return str_replace($placeholder, $htmlCode, $matches[0]);
            } else {
                return $matches[0];
            }
        };
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegexCallback(array('module' => 'news', 'cmd' => ''), $search, $callback, array('content'), '3.0.3');
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegexCallback(array('module' => 'news', 'cmd' => 'details'), $search, $callback, array('content'), '3.0.3');

        // remove the script tag at the beginning of the gallery page
        \Cx\Lib\UpdateUtil::migrateContentPageUsingRegex(array('module' => 'gallery'), '/^\s*(<script[^>]+>.+?Shadowbox.+?<\/script>)+/sm', '', array('content'), '3.0.3');
    } catch (\Cx\Lib\UpdateException $e) {
        return \Cx\Lib\UpdateUtil::DefaultActionHandler($e);
    }
}

try {
    // migrate content page to version 3.0.1
    $search = array(
        '/(.*)/ms',
    );
    $callback = function($matches) {
        $content = $matches[1];
        if (empty($content)) {
            return $content;
        }

        // fix duplicated social networks blocks
        if (preg_match('/<!--\s+BEGIN\s+access_social_networks\s+-->.*<!--\s+BEGIN\s+access_social_networks\s+-->/ms', $content)) {
            $content = preg_replace('/<br\s+\/><br\s+\/><!--\s+BEGIN\s+access_social_networks\s+-->.*?<!--\s+END\s+access_social_networks\s+-->/ms', '', $content);
        }

        // add missing access_social_networks template block
        if (!preg_match('/<!--\s+BEGIN\s+access_social_networks\s+-->.*<!--\s+END\s+access_social_networks\s+-->/ms', $content)) {
            $content = preg_replace('/(<!--\s+BEGIN\s+access_signup_form\s+-->.*?)(<div[^>]*>|)(.*?\{ACCESS_SIGNUP_MESSAGE\}.*?)(<\/div>|)/ms', '$1<br /><br /><!-- BEGIN access_social_networks --><fieldset><legend>oder Login mit Social Media</legend><!-- BEGIN access_social_networks_facebook -->        <a class="facebook loginbutton" href="{ACCESS_SOCIALLOGIN_FACEBOOK}">Facebook</a>        <!-- END access_social_networks_facebook -->        <!-- BEGIN access_social_networks_google -->        <a class="google loginbutton" href="{ACCESS_SOCIALLOGIN_GOOGLE}">Google</a>        <!-- END access_social_networks_google -->        <!-- BEGIN access_social_networks_twitter -->        <a class="twitter loginbutton" href="{ACCESS_SOCIALLOGIN_TWITTER}">Twitter</a>        <!-- END access_social_networks_twitter -->    </fieldset>    <!-- END access_social_networks -->$2$3$4', $content);
        }

        return $content;
    };

    \Cx\Lib\UpdateUtil::migrateContentPageUsingRegexCallback(array('module' => 'access', 'cmd' => 'signup'), $search, $callback, array('content'), '3.0.3');
} catch (\Cx\Lib\UpdateException $e) {
    return \Cx\Lib\UpdateUtil::DefaultActionHandler($e);
}

require(dirname(__FILE__).'/config.inc.php');
\Cx\Lib\UpdateUtil::sql('UPDATE `'.DBPREFIX.'settings` SET `setvalue` = \'' . $arrUpdate['cmsVersion'] . '\' WHERE `setname` = \'coreCmsVersion\'');
\Cx\Lib\UpdateUtil::sql('UPDATE `'.DBPREFIX.'settings` SET `setvalue` = \'' . $arrUpdate['cmsCodeName'] . '\' WHERE `setname` = \'coreCmsCodeName\'');
\Cx\Lib\UpdateUtil::sql('UPDATE `'.DBPREFIX.'settings` SET `setvalue` = \'' . $arrUpdate['cmsReleaseDate'] . '\' WHERE `setname` = \'coreCmsReleaseDate\'');
\Cx\Lib\UpdateUtil::sql('UPDATE `'.DBPREFIX.'settings` SET `setvalue` = \'' . $arrUpdate['cmsName'] . '\' WHERE `setname` = \'coreCmsName\'');
\Cx\Lib\UpdateUtil::sql('UPDATE `'.DBPREFIX.'settings` SET `setvalue` = \'' . $arrUpdate['cmsStatus'] . '\' WHERE `setname` = \'coreCmsStatus\'');

$objSettings = new \settingsManager();
$objSettings->writeSettingsFile();
require($documentRoot.'/config/settings.php');
return true;
