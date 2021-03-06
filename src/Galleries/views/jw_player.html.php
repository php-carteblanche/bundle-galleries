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

if (empty($vid_url)) return '';
if (empty($root_url)) $root_url=_ASSETS;
if (empty($div_id)) $div_id='sv-container';
if (empty($width)) $width='100%';
if (empty($height)) $height='100%';
if (empty($preview_url)) $preview_url='';

$jw_js = \TemplateEngine\TemplateEngine::getInstance()
    ->getAssetsLoader()->find('jw_player/jwplayer.js');
//    ->getAssetsLoader()->findInPackage('svcore/js/simpleviewer.js', 'carte-blanche/bundle-galleries');

$jw_swf = \TemplateEngine\TemplateEngine::getInstance()
    ->getAssetsLoader()->find('jw_player/player.swf');
//    ->getAssetsLoader()->findInPackage('svcore/js/simpleviewer.js', 'carte-blanche/bundle-galleries');

?>
<!-- START OF THE PLAYER EMBEDDING TO COPY-PASTE -->
<div id="mediaplayer">JW Player goes here</div>
<script type="text/javascript" src="<?php echo $jw_js; ?>"></script>
<script type="text/javascript">
    jwplayer("mediaplayer").setup({
        flashplayer: "<?php echo $jw_swf; ?>",
        file: "<?php echo $vid_url; ?>",
        image: "<?php echo $preview_url; ?>"
    });
</script>
<!-- END OF THE PLAYER EMBEDDING -->
