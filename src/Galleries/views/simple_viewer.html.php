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

if (empty($root_url)) $root_url=_ASSETS;
if (empty($div_id)) $div_id='sv-container';
if (empty($width)) $width='100%';
if (empty($height)) $height='100%';
if (empty($backgroundColor)) $backgroundColor='222222';
if (empty($useFlash)) $useFlash='true';
if (empty($flash_vars)) $flash_vars=array();
if (empty($params)) $params=array();
if (empty($attributes)) $attributes=array();

$sv_js = \TemplateEngine\TemplateEngine::getInstance()
    ->getAssetsLoader()->findInPackage('svcore/js/simpleviewer.js', 'carte-blanche/bundle-galleries');

?>
<!--START SIMPLEVIEWER EMBED -->
<script type="text/javascript" src="<?php echo $sv_js; ?>"></script>
<script type="text/javascript">
function doLayout() 
{
	var winHeight, headerHeight, footerHeight;
	winHeight = window.innerHeight ? window.innerHeight : $(window).height();
	headerHeight = $('#page_header').outerHeight();
	footerHeight = $('#page_footer').outerHeight();
	var newH = parseInt(winHeight) - parseInt(headerHeight) - parseInt(footerHeight);
	$('#flashContent').height(newH);
}

var flashvars = {};
<?php foreach ($flash_vars as $fv_index => $fv_value ) { echo "flashvars.$fv_index = \"".str_replace('&amp;', '%26', $fv_value)."\";"; } ?>

var params = {};
<?php foreach ($params as $pa_index => $pa_value ) { echo "params.$pa_index = \"$pa_value\";"; } ?>

var attributes = {};
<?php foreach ($attributes as $at_index => $at_value ) { echo "attributes.$at_index = \"$at_value\";"; } ?>

$(document).ready(function() {
	doLayout();	   
	$(window).bind('resize', doLayout);
	SV.simpleviewer.load(
		"<?php echo $div_id; ?>", 
		"<?php echo $width; ?>", 
		"<?php echo $height; ?>", 
		"<?php echo $backgroundColor; ?>", 
		<?php echo $useFlash; ?>, 
		flashvars, params, attributes);
});
</script>
<div id="flashContent">
	<div id="<?php echo $div_id; ?>"></div>
</div>
<!--END SIMPLEVIEWER EMBED -->
