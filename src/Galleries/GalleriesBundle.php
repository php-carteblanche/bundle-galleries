<?php
/**
 * This file is part of the CarteBlanche PHP framework.
 *
 * (c) Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * License Apache-2.0 <http://github.com/php-carteblanche/carteblanche/blob/master/LICENSE>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Galleries;

use \CarteBlanche\CarteBlanche;
use \CarteBlanche\Abstracts\AbstractBundle;

use \Library\Helper\Directory as DirectoryHelper;

class GalleriesBundle
    extends AbstractBundle
{

    /**
     * @param   array $options
     * @return  mixed
     */
    public function init(array $options = array())
    {
        parent::init($options);

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