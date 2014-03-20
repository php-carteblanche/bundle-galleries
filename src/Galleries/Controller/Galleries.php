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

namespace Galleries\Controller;

use \CarteBlanche\CarteBlanche;
use \CarteBlanche\Abstracts\AbstractControllerConfigurable;
use \CarteBlanche\Model\DirectoryModel;
use \CarteBlanche\Model\ImageModel;
use \Library\Helper\Directory as DirectoryHelper;

/**
 * Galleries controller : get gelleries for Galleries
 *
 * @author 		Piero Wbmstr <piwi@ateliers-pierrot.fr>
 */
class Galleries
    extends AbstractControllerConfigurable
{

	/**
	 * The default global template file
	 */
	static $template_xml = 'Galleries/views/template.xml.php';

	/**
	 * The controller views directory
	 */
	static $views_dir = 'Galleries/views/';

    protected $galleries_dir;
    protected $galleries_path;

	/**
	 * Initialization: to be overwritten (if needed, this method is called at the end of constructor)
	 */
	protected function init()
	{
        $cfg = $this->getConfig();
        if (!empty($cfg) && isset($cfg['galleris_dir'])) {
            $this->galleries_dir = DirectoryHelper::slashDirname($cfg['galleris_dir']);
        } else {
            $this->galleries_dir = DirectoryHelper::slashDirname(
                CarteBlanche::getContainer()->get('config')->get('galleries.galleries_dir')
            );
        }
        $_abs_gal = CarteBlanche::getPath('web_dir').$this->galleries_dir;
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
		return array(self::$views_dir.'index', array(
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

		return array(self::$views_dir.'jw_player_2', array(
            'vid_url'=>CarteBlanche::getPath('root_http').$this->galleries_dir.$_dir->getDirname().'/'.$vids_data[$vid],
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
		$embed = new \Galleries\Tool\JwPlayerGallery(array(
			'current_page' => $page,
			'current_dir' => $dir,
			'config' => $jw_player_config,
			'media' => $img_data,
			'root_url' => CarteBlanche::getPath('root_http').$this->galleries_dir.$dir,
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

		$embed = new \Galleries\Tool\SimpleViewer(array(
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
/*/
echo '<pre>';
var_export($_dir);
var_export($img_data);
exit('yo');
//*/
		$embed = new \Galleries\Tool\SimpleViewerGallery(array(
			'current_page' => $page,
			'current_dir' => $dir,
			'config' => $simple_viewer,
			'images' => $img_data,
			'root_url' => CarteBlanche::getPath('root_http').$this->galleries_dir.$dir,
			'root_path' => CarteBlanche::getFullPath('web_dir').$this->galleries_dir.$dir,
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