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
 * Main controller for Block
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  module_block
 */

namespace Cx\Modules\Block\Controller;

/**
 * Main controller for Block
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  module_block
 */
class ComponentController extends \Cx\Core\Core\Model\Entity\SystemComponentController {
    public function getControllerClasses() {
        // Return an empty array here to let the component handler know that there
        // does not exist a backend, nor a frontend controller of this component.
        return array('JsonBlock', 'EsiWidget');
    }

    public function getControllersAccessableByJson() {
        return array('JsonBlockController', 'EsiWidgetController');
    }

     /**
     * Load your component.
     *
     * @param \Cx\Core\ContentManager\Model\Entity\Page $page       The resolved page
     */
    public function load(\Cx\Core\ContentManager\Model\Entity\Page $page) {
        global $_CORELANG, $subMenuTitle, $objTemplate;

        switch ($this->cx->getMode()) {
            case \Cx\Core\Core\Controller\Cx::MODE_BACKEND:
                $this->cx->getTemplate()->addBlockfile('CONTENT_OUTPUT', 'content_master', 'LegacyContentMaster.html');
                $objTemplate = $this->cx->getTemplate();

                \Permission::checkAccess(76, 'static');
                $subMenuTitle = $_CORELANG['TXT_BLOCK_SYSTEM'];
                $objBlock = new \Cx\Modules\Block\Controller\BlockManager();
                $objBlock->getPage();
                break;
        }
    }

    /**
     * Do something after system initialization
     *
     * USE CAREFULLY, DO NOT DO ANYTHING COSTLY HERE!
     * CALCULATE YOUR STUFF AS LATE AS POSSIBLE.
     * This event must be registered in the postInit-Hook definition
     * file config/postInitHooks.yml.
     * @param \Cx\Core\Core\Controller\Cx   $cx The instance of \Cx\Core\Core\Controller\Cx
     */
    public function postInit()
    {
        $block            = new Block();
        $widgetController = $this->getComponent('Widget');

        // Set blocks [[BLOCK_<ID>]]
        $blockNames = $block->getBlockNamesByCriteria('block');
        if ($blockNames) {
            foreach ($blockNames as $blockName) {
                $widgetController->createWidget(
                    $this,
                    $blockName
                );
            }
        }

        // Set global block [[BLOCK_GLOBAL]]
        $widgetController->createWidget(
            $this,
            $block->blockNamePrefix . 'GLOBAL'
        );

        // Set category blocks [[BLOCK_CAT_<ID>]]
        $catBlockNames = $block->getBlockNamesByCriteria('categoryBlock');
        foreach ($catBlockNames as $catBlockName) {
            $widgetController->createWidget(
                $this,
                $catBlockName
            );
        }

        // Set random blocks [[BLOCK_RANDOMIZER]], [[BLOCK_RANDOMIZER_2]],
        //                   [[BLOCK_RANDOMIZER_3]], [[BLOCK_RANDOMIZER_4]]
        $randomizerNames = $block->getBlockNamesByCriteria('randomizerBlock');
        foreach ($randomizerNames as $key => $randomizerName) {
            if ($key > 0) {
                $key += 1;
            }
            $availableBlocks = $block->getBlockNamesForRandomizer($key);
            $widgetController->createRandomWidget(
                $this,
                $randomizerName,
                $availableBlocks
            );
        }
    }
}
