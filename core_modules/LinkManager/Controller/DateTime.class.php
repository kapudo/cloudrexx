<?php

/**
 * Cloudrexx
 *
 * @link      http://www.cloudrexx.com
 * @copyright Cloudrexx AG 2007-2015
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
 * DateTime class
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  coremodule_linkmanager
 */

namespace Cx\Core_Modules\LinkManager\Controller;

/**
 * DateTime class
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  coremodule_linkmanager
 */

class DateTime extends \DateTime {

    /**
     * Return the correct date and time format
     *
     * @global array $_ARRAYLANG
     *
     * @param datetime $time
     *
     * @return string
     */
    public static function formattedDateAndTime($time)
    {
        global $_ARRAYLANG;

        return $time->format('d.m.Y - H.i').' '.$_ARRAYLANG['TXT_CORE_MODULE_LINKMANAGER_LABEL_CLOCK'];
    }

    /**
     * find the difference between two date
     *
     * @param datetime $start
     * @param datetime $end
     *
     * @return string
     */
    public static function diffTime($start, $end)
    {
        if (empty($start) || empty($end)) {
            return;
        }

        $duration = $end->diff($start);
        return $duration->format('%H:%I:%S');
    }
}
