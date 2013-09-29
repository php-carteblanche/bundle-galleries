<?php
/**
 * CarteBlanche - PHP framework package - Simple Viewer bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License Apache-2.0 <http://www.apache.org/licenses/LICENSE-2.0.html>
 * Sources <http://github.com/php-carteblanche/carteblanche>
 */

namespace SimpleViewer\Tool;

use \CarteBlanche\CarteBlanche;
use \CarteBlanche\App\Abstracts\AbstractTool;
use \Library\Helper\Directory as DirectoryHelper;

class SimpleViewerGallery extends AbstractTool
{
	var $views_dir = 'SimpleViewer/views/';
	var $view='simple_viewer_gallery.xml';

	var $images=null;
	private $full_images=null;
	var $gallery_attributes=array();
	var $root_url=null;
	var $root_path=null;
	var $config=array();
	var $current_page=1;
	var $current_dir=null;
	private $total_pages=null;
	
	var $thumbs_width = 75;
	var $thumbs_height = 75;

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

        $this->thumbs = array();
        foreach ($this->images as $_image) {
            $_img_path = DirectoryHelper::slashDirname($this->root_path) . $_image['FileName'];
            $this->thumbs[$_img_path] = new \MediaProcessing\ImageFilter\ImageFilter(
                $_img_path, null, 'resize',
                array('max_width'=>$this->thumbs_width,'max_height'=>$this->thumbs_height)
            );
        }

		return array(
			'config'=> $_conf,
			'current_dir'=>$this->current_dir,
			'root_url'=>$this->root_url,
			'images'=>$this->images,
			'thumbs'=>$this->thumbs,
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