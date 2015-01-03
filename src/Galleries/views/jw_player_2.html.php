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

//if (empty($vid_url) || empty($playlist_url)) return '';
if (empty($root_url)) $root_url=_ASSETS;
if (empty($div_id)) $div_id='player-container';
if (empty($width)) $width='80%';
if (empty($aspectratio)) $aspectratio='4:3';
if (empty($preview_url)) $preview_url='';

$jw_js = \TemplateEngine\TemplateEngine::getInstance()
    ->getAssetsLoader()->find('jw_player_2/jwplayer.js');
//    ->getAssetsLoader()->findInPackage('svcore/js/simpleviewer.js', 'carte-blanche/bundle-galleries');

$jw_swf = \TemplateEngine\TemplateEngine::getInstance()
    ->getAssetsLoader()->find('jw_player_2/player.swf');
//    ->getAssetsLoader()->findInPackage('svcore/js/simpleviewer.js', 'carte-blanche/bundle-galleries');

?>
<!-- START OF JW PLAYER EMBEDDING -->
<div id="<?php echo $div_id; ?>"></div>
<script type="text/javascript" src="<?php echo $jw_js; ?>"></script>
<script type="text/javascript">
<?php if (!empty($player_key)) : ?>
    jwplayer.key="<?php echo $player_key; ?>";
<?php endif; ?>
    jwplayer("<?php echo $div_id; ?>").setup({
//        flashplayer: "<?php echo $jw_swf; ?>",
        width: "<?php echo $width; ?>",
        aspectratio: "<?php echo $aspectratio; ?>",
<?php if (!empty($playlist_url)) : ?>
        playlist: "<?php echo $playlist_url; ?>"
<?php else : ?>
        file: "<?php echo $vid_url; ?>",
        image: "<?php echo $preview_url; ?>",
        title: "<?php echo basename($vid_url); ?>"
<?php endif; ?>
    });
</script>
<!-- END OF THE PLAYER EMBEDDING -->
