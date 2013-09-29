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

class SimpleViewer extends AbstractTool
{
	var $views_dir = 'SimpleViewer/views/';
	var $view='simple_viewer.htm';

	var $div_id=null;
	var $width=null;
	var $height=null;
	var $backgroundColor=null;
	var $useFlash=null;
	var $flash_vars=array();
	var $params=array();
	var $attributes=array();
	
	public function buildViewParams()
	{
		return array(
			'root_url'=>CarteBlanche::getPath('root_http'),
			'div_id'=>$this->div_id,
			'width'=>$this->width,
			'height'=>$this->height,
			'backgroundColor'=>$this->backgroundColor,
			'useFlash'=>$this->useFlash,
			'flash_vars'=>$this->flash_vars,
			'params'=>$this->params,
			'attributes'=>$this->attributes
		);
	}

}

// Endfile