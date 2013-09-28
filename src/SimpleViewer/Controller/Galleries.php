<?php
/**
 * CarteBlanche - PHP framework package - Simple Viewer bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/carte-blanche>
 */

namespace SimpleViewer\Controller;

use \CarteBlanche\CarteBlanche;
use \CarteBlanche\App\Abstracts\AbstractControllerConfigurable;
use \CarteBlanche\Model\DirectoryModel;
use \CarteBlanche\Model\ImageModel;

/**
 * SimpleViewer controller : get gelleries for SimpleViewer
 *
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Galleries extends AbstractControllerConfigurable
{

	/**
	 * The default global template file
	 */
	static $template = 'SimpleViewer/views/template.htm';
	static $template_xml = 'SimpleViewer/views/template.xml';

	/**
	 * The controller views directory
	 */
	static $views_dir = 'SimpleViewer/views/';

    protected $galleries_dir;
    protected $galleries_path;

	/**
	 * Initialization: to be overwritten (if needed, this method is called at the end of constructor)
	 */
	protected function init()
	{
        $cfg = $this->getConfig();
        if (!empty($cfg) && isset($cfg['galleris_dir'])) {
            $this->galleries_dir = $cfg['galleris_dir'];
        } else {
            $this->galleries_dir = _GALLERIES;
        }
        $_abs_gal = CarteBlanche::getPath('web_dir').$this->galleries_dir;
        if (!file_exists($_abs_gal)) {
            $_abs_gal = CarteBlanche::getPath('web_path').$this->galleries_dir;
        }
        if (file_exists($_abs_gal) && is_dir($_abs_gal)) {
            $this->galleries_path = $_abs_gal;
        } else {
            throw new NotFoundException(
                sprintf('Galleries directory "%s" not found!', $this->galleries_dir)
            );
        }
        
	}

	/**
	 * The home page of the controller
	 *
	 * @return string The home page view content
	 */
	public function indexAction(  )
	{
		$this->getContainer()->get('router')->setReferer();
		$_dir = new DirectoryModel;
		$_dir->setPath($this->galleries_path);
		$config = CarteBlanche::getConfig('globals');

		$_alldirs = array();
		if ($_dir->pathExists()) {
            foreach ($_dir->dump() as $_subdir) {
                $_ndir = new DirectoryModel;
                $_ndir->setPath($this->galleries_path.'/'.$_subdir);
                $_nimg = new ImageModel;
                $_nimg->setPath($this->galleries_path.'/'.$_subdir);
                $_alldirs[] = array(
                    'dirname' => $_subdir,
                    'title' => $_ndir->getDisplayDirname(),
                    'counts' => $_ndir->count(),
                    'src' => $_nimg->findFirst()
                );
            }
        }

/*
echo '<pre>';
var_export($_alldirs);
exit('yo');
*/
		return array(self::$views_dir.'index.htm', array(
            '_alldirs'=>$_alldirs,
			'title'=>!empty($config['title']) ? $config['title'] : 'accueil',
		));
	}

	public function videosAction($dir = null, $vid = 1)
	{
		$this->getContainer()->get('router')->setReferer();
		$jw_player_config = CarteBlanche::getConfig('jw_player');

		$_dir = new DirectoryModel;
		$_dir->setPath($this->galleries_path);
		$_dir->setDirname($dir);

		$vids_data=array();
		$_images = $_dir->scanDir();
		sort($_images);
		foreach ($_images as $i=>$_img) {
			$vids_data[$i+1] = $_img;
		}

		$galleryURL = $this->getContainer()->get('router')->buildUrl(array('controller'=>'galleries','action'=>'videosGallery', 'dir'=>$dir));

		return array(self::$views_dir.'jw_player_2.htm', array(
            'vid_url'=>CarteBlanche::getPath('root_http')._GALLERIES.$_dir->getDirname().'/'.$vids_data[$vid],
//			'playlist_url'=>$galleryURL,
            'player_key' => isset($jw_player_config['key']) ? $jw_player_config['key'] : null,
			'title' => $_dir->getDisplayDirname(),
		));
	}

	public function videosGalleryAction($dir = null, $page = 1)
	{
		self::$template = self::$template_xml;
		$ctt = '';

		$this->getContainer()->get('router')->setReferer();
		$jw_player_config = CarteBlanche::getConfig('jw_player');

		$_dir = new DirectoryModel;
		$_dir->setPath($this->galleries_path);
		$_dir->setDirname($dir);

		$img_data=array();
		$_images = $_dir->scanDir();
		sort($_images);
		foreach ($_images as $_img) {
			$_i = new ImageModel;
			$_i->setPath( $_dir->getPath().$_dir->getDirname() );
			$_i->setFilename( $_img );
			if ($_i->getExtension()=='mp4') $img_data[] = $_i->getInfos();
		}
//echo '<pre>';var_export($img_data);exit('yo');
		$embed = new \SimpleViewer\Tool\JwPlayerGallery(array(
			'current_page' => $page,
			'current_dir' => $dir,
			'config' => $jw_player_config,
			'media' => $img_data,
			'root_url' => CarteBlanche::getPath('root_http')._GALLERIES.$dir,
			'gallery_attributes' => array(
				'title'=>$_dir->getDisplayDirname()
			)
		));

		$this->getContainer()->get('response')->setContentType('xml');
		return array(
			'title' => $_dir->getDisplayDirname(),
			'output'=> $embed
		);
	}

	public function photosAction( $dir=null, $page=1 )
	{
		$this->getContainer()->get('router')->setReferer();
		$_dir = new DirectoryModel;
		$_dir->setPath($this->galleries_path);
		$_dir->setDirname($dir);

		if ($page===1) {
			$galleryURL = $this->getContainer()->get('router')->buildUrl(array(
			    'controller'=>'galleries','action'=>'photosGallery', 'dir'=>$dir
			));
		} else {
			$galleryURL = $this->getContainer()->get('router')->buildUrl(array(
			    'controller'=>'galleries','action'=>'photosGallery', 'dir'=>$dir, 'page'=>$page
			));
		}

		$embed = new \SimpleViewer\Tool\SimpleViewer(array(
			'flash_vars'=>array( 
				'galleryURL'=>$galleryURL,
				'baseURL' => CarteBlanche::getPath('root_http'),
			),
		));

		return array(
			'title' => $_dir->getDisplayDirname(),
			'output'=> $embed
		);
	}

	public function photosGalleryAction( $dir=null, $page=1 )
	{
		self::$template = self::$template_xml;
		$ctt = '';

		$this->getContainer()->get('router')->setReferer();
		$simple_viewer = CarteBlanche::getConfig('simple_viewer');

		$_dir = new DirectoryModel;
		$_dir->setPath($this->galleries_path);
		$_dir->setDirname($dir);

		$img_data=array();
		$_images = $_dir->scanDir();
		sort($_images);
		foreach ($_images as $_img) {
			$_i = new ImageModel;
			$_i->setPath( $_dir->getPath().$_dir->getDirname() );
			$_i->setFilename( $_img );
			if ($_i->isImage()) $img_data[] = $_i->getInfos();
		}
//echo '<pre>';var_export($img_data);exit('yo');
		$embed = new \SimpleViewer\Tool\SimpleViewerGallery(array(
			'current_page' => $page,
			'current_dir' => $dir,
			'config' => $simple_viewer,
			'images' => $img_data,
			'root_url' => CarteBlanche::getPath('root_http')._GALLERIES.$dir,
			'root_path' => CarteBlanche::getPath('web_path')._GALLERIES.$dir,
			'gallery_attributes' => array(
				'title'=>$_dir->getDisplayDirname()
			)
		));

		$this->getContainer()->get('response')->setContentType('xml');
		return array(
			'title' => $_dir->getDisplayDirname(),
			'output'=> $embed
		);
	}

	/**
	 * Page for uninstalled application
	 *
	 * @param string $altdb The alternative database
	 * @return string The view content
	 */
	public function emptyAction( $altdb )
	{
		$this->render(array(
			'output'=> $this->view(
				'empty.htm'
			),
			'title' => "System not installed"
		));
	}

// -------------------
// Special method for tests
// -------------------

	/**
	 * Page of test
	 */
	public function testAction(  )
	{
	}

}

// Endfile