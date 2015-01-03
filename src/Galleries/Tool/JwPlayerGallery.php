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

namespace Galleries\Tool;

use \CarteBlanche\Abstracts\AbstractTool;

class JwPlayerGallery extends AbstractTool
{

    var $views_dir = 'Galleries/views/';
    var $view='jw_player_playlist.xml';

    var $media=null;
    private $full_images=null;
    var $gallery_attributes=array();
    var $root_url=null;
    var $config=array();
    var $current_page=1;
    var $current_dir=null;
    private $total_pages=null;

    static $default_attributes = array(
        'useFlickr'=>"false",
        'resizeOnImport'=>"true",
        'cropToFit'=>"false",
        'backgroundTransparent'=>"false",
        'galleryStyle'=>"MODERN"
    );

    static $default_config = array(
        'multipage'=>"false",
        'pagelimit'=>50,
    );

    public function buildViewParams()
    {
        $_conf = array_merge(self::$default_config, $this->config);
        if (
            isset($_conf['multipage']) && true==$_conf['multipage'] &&
            isset($_conf['pagelimit']) && count($this->images)>$_conf['pagelimit']
        ) self::buildPager($_conf['pagelimit']);

        return array(
            'config'=> $_conf,
            'current_dir'=>$this->current_dir,
            'root_url'=>$this->root_url,
            'images'=>$this->images,
            'full_images'=>$this->full_images,
            'current_page'=>$this->current_page,
            'total_pages'=>$this->total_pages,
            'gallery_attributes'=>
                array_merge(self::$default_attributes, $this->gallery_attributes)
        );
    }

    public function buildPager( $pagelimit=null )
    {
        $this->full_images = $this->images;
        $this->total_pages = ceil( count($this->images) / $pagelimit );
        if ($this->current_page<1) $this->current_page = 1;
        if ($this->current_page>$this->total_pages) $this->current_page = $this->total_pages;
        $this->images = array_slice($this->images, ($this->current_page-1)*$pagelimit, $pagelimit);
    }

}

// Endfile