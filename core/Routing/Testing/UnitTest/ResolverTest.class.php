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
 * ResolverTest
 * 
 * @copyright   CLOUDREXX CMS - CLOUDREXX AG
 * @author      Cloudrexx Development Team <info@cloudrexx.com>
 * @author      SS4U <ss4u.comvation@gmail.com>
 * @version     1.0.0
 * @package     cloudrexx
 * @subpackage  core_resolver
 */

namespace Cx\Core\Routing\Testing\UnitTest;

/**
 * ResolverTest
 * 
 * @copyright   CLOUDREXX CMS - CLOUDREXX AG
 * @author      Cloudrexx Development Team <info@cloudrexx.com>
 * @author      SS4U <ss4u.comvation@gmail.com>
 * @version     1.0.0
 * @package     cloudrexx
 * @subpackage  core_resolver
 */
class ResolverTest extends \Cx\Core\Test\Model\Entity\DatabaseTestCase
{
    protected $mockFallbackLanguages = array(
        1 => 2,
        2 => 3
    );

    /**
     * Constructs a test case with the given name.
     *
     * @param string    $name
     * @param array     $data
     * @param string    $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dataSetFolder = $this->cx->getCodeBaseCorePath() . '/Routing/Testing/UnitTest/Data';
    }

    /**
     * @dataProvider resolverDataProvider Data value provider
     */
    public function testResolver($language, $expectedResult)
    {
        global $url;

        $langCode = \FWLanguage::getLanguageCodeById($language);
        $url      = new \Cx\Core\Routing\Url('http://example.com/'. $langCode .'/'. $expectedResult);
        $resolver = new \Cx\Core\Routing\Resolver($url, $language, self::$em, '', $this->mockFallbackLanguages, false);
        $resolver->resolve();
        $p = $resolver->getPage();
        $this->assertEquals($expectedResult, $p->getSlug());
    }

    /**
     * Test records for the testResolver method
     *
     * @return array
     */
    public function resolverDataProvider()
    {
        return array(
            array(1, 'Simple-content-page'),
        );
    }
}
