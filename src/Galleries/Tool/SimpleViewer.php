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

namespace Galleries\Tool;

use \CarteBlanche\CarteBlanche;
use \CarteBlanche\Abstracts\AbstractTool;

class SimpleViewer extends AbstractTool
{
	var $views_dir = 'Galleries/views/';
	var $view='simple_viewer';

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