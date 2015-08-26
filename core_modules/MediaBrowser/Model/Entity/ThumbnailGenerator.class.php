<?php

/**
 * Class ThumbnailGenerator
 *
 * @copyright   Comvation AG
 * @author      Robin Glauser <robin.glauser@comvation.com>
 * @package     contrexx
 * @subpackage  coremodule_mediabrowser
 */

namespace Cx\Core_Modules\MediaBrowser\Model\Entity;

use Cx\Core\Core\Controller\Cx;
use Cx\Core\MediaSource\Model\Entity\MediaSourceManager;
use Cx\Core_Modules\Uploader\Controller\UploaderConfiguration;
use Cx\Model\Base\EntityBase;

/**
 * Class ThumbnailGenerator
 *
 * @copyright   Comvation AG
 * @author      Robin Glauser <robin.glauser@comvation.com>
 */
class ThumbnailGenerator extends EntityBase
{

    const THUMBNAIL_GENERATOR_SUCCESS = 'green';
    const THUMBNAIL_GENERATOR_FAIL = 'red';
    const THUMBNAIL_GENERATOR_NEUTRAL = 'yellow';

    /**
     * Create all the thumbnails for a picture.
     *
     * @param string        $path          Path to the file. This can be a virtual path or a absolute path.
     * @param string        $fileNamePlain Plain file name without extension
     * @param string        $fileExtension Extension of the file
     * @param \ImageManager $imageManager
     *
     * <code>
     * <?php
     * \Cx\Core_Modules\MediaBrowser\Model\FileSystem::createThumbnail(
     *      'files/',
     *      'Django,
     *      'jpg',
     *      new ImageManager() // Please recycle the instance and don't create a new anonymous instance for each call.
     *                         // This is just a simple example.
     * );
     * ?>
     * </code>
     *
     * @param bool          $generateThumbnailByRatio
     * @param bool          $force
     *
     * @return array With all thumbnail types and if they were generated successfully.
     */
    public static function createThumbnail(
        $path, $fileNamePlain, $fileExtension, \ImageManager $imageManager,
        $generateThumbnailByRatio = false,$force = false
    ) {
        $thumbnails = array();
        foreach (
            UploaderConfiguration::getInstance()->getThumbnails() as $thumbnail
        ) {
            if ($force){
                \Cx\Lib\FileSystem\FileSystem::delete_file(MediaSourceManager::getAbsolutePath($path) .'/' . $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension);
            }
            elseif (\Cx\Lib\FileSystem\FileSystem::exists(
                MediaSourceManager::getAbsolutePath($path) .'/' . $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension
            ) ) {
                $thumbnails[] = $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension;
                continue;
            }
            if ($imageManager->_createThumb(
                MediaSourceManager::getAbsolutePath($path) . '/',
                '',
                $fileNamePlain . '.' . $fileExtension,
                $thumbnail['size'],
                $thumbnail['quality'],
                $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension,
                $generateThumbnailByRatio
            )
            ) {
                $thumbnails[] = $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension;
                continue;
            }
        }
        return $thumbnails;
    }

    public static function createThumbnailFromPath($filePath, $force = false) {
        $cx       = Cx::instanciate();
        if (!file_exists($filePath)){
            $filePath = $cx->getWebsitePath() .$filePath;
        }
        $fileInfo = pathinfo( $filePath);
        return self::createThumbnail(
            $fileInfo['dirname'],
            preg_replace('/\.thumb_[a-z]+/i', '', $fileInfo['filename']),
            $fileInfo['extension'], new \ImageManager(),false, $force
        );
    }


} 
