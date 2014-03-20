<?php
/**
 * This file is part of the CarteBlanche PHP framework
 * (c) Pierre Cassat and contributors
 * 
 * Sources <http://github.com/php-carteblanche/bundle-galleries>
 *
 * License Apache-2.0
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Galleries;

use \CarteBlanche\CarteBlanche;

use \Library\Helper\Directory as DirectoryHelper;

class GalleriesBundle
{

    protected static $bundle_config_file = 'galleries_config.ini';

    public function __construct()
    {
        $cfgfile = \CarteBlanche\App\Locator::locateConfig(self::$bundle_config_file);
        if (!file_exists($cfgfile)) {
            throw new ErrorException( 
                sprintf('Galleries bundle configuration file not found in "%s" [%s]!', $this->getPath('config_dir'), $cfgfile)
            );
        }
        $cfg = CarteBlanche::getContainer()->get('config')
            ->load($cfgfile, true, 'galleries')
            ->get('galleries');

        $galleries_web_dir = isset($cfg['galleries_dir']) ? $cfg['galleries_dir'] : null;
        if (!empty($galleries_web_dir)) {
            $galleries_path = DirectoryHelper::slashDirname(CarteBlanche::getPath('web_dir')) . $galleries_web_dir;
            @DirectoryHelper::ensureExists($galleries_path);
            if (!file_exists($galleries_path) || !is_dir($galleries_path)) {
                CarteBlanche::getKernel()->addBootError(
                    sprintf("Can't create web directory '%s' for galleries bundle!", $galleries_web_dir)
                );
            }
        }
        CarteBlanche::getContainer()->get('kernel')
            ->addPath('galleries', $galleries_web_dir);
    }

}

// Endfile