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

if (empty($_alldirs)) $_alldirs = array();
$effects_types = array( 'caption', 'captionfull', 'peek', 'thecombo', 'slideright', 'slidedown' );

$_template->getTemplateObject('JavascriptTag')->add(<<<EOT
/*
Sliding Boxes and Captions with JQuery
March 2009 - By Sam Dunn - www.buildinternet.com / www.onemightyroar.com
*/
$(document).ready(function(){
	//To switch directions up/down and left/right just place a "-" in front of the top/left attribute
	//Vertical Sliding
	$('.boxgrid.slidedown').hover(function(){
		$(".cover", this).stop().animate({top:'-260px'},{queue:false,duration:300});
	}, function() {
		$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:300});
	});
	//Horizontal Sliding
	$('.boxgrid.slideright').hover(function(){
		$(".cover", this).stop().animate({left:'325px'},{queue:false,duration:300});
	}, function() {
		$(".cover", this).stop().animate({left:'0px'},{queue:false,duration:300});
	});
	//Diagnal Sliding
	$('.boxgrid.thecombo').hover(function(){
		$(".cover", this).stop().animate({top:'260px', left:'325px'},{queue:false,duration:300});
	}, function() {
		$(".cover", this).stop().animate({top:'0px', left:'0px'},{queue:false,duration:300});
	});
	//Partial Sliding (Only show some of background)
	$('.boxgrid.peek').hover(function(){
		$(".cover", this).stop().animate({top:'90px'},{queue:false,duration:160});
	}, function() {
		$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:160});
	});
	//Full Caption Sliding (Hidden to Visible)
	$('.boxgrid.captionfull').hover(function(){
		$(".cover", this).stop().animate({top:'160px'},{queue:false,duration:160});
	}, function() {
		$(".cover", this).stop().animate({top:'260px'},{queue:false,duration:160});
	});
	//Caption Sliding (Partially Hidden to Visible)
	$('.boxgrid.caption').hover(function(){
		$(".cover", this).stop().animate({top:'160px'},{queue:false,duration:160});
	}, function() {
		$(".cover", this).stop().animate({top:'220px'},{queue:false,duration:160});
	});
});

EOT
);
?>
<!--
Sliding Boxes and Captions with JQuery
March 2009
By Sam Dunn
www.buildinternet.com / www.onemightyroar.com
-->
<style type="text/css">
.boxgrid a{ color:#C8DCE5; }
.boxgrid h3, .boxgrid h3 a{ 
	margin: 10px 10px 0 10px; color:#FFF; font:18pt Arial, sans-serif; letter-spacing:-1px; font-weight: bold;
	text-decoration: none; }
.boxgrid {
	width: 325px; height: 260px; margin:10px; float:left; overflow: hidden; position: relative; 
	background:#161613; border: solid 2px #404040; 
}
.boxgrid img { position: absolute; top: 0; left: 0; border: 0; }
.boxgrid p { padding: 0 10px; color:#afafaf; font-weight:bold; font:10pt "Lucida Grande", Arial, sans-serif; }
.boxcaption{ 
	float: left; position: absolute; 
	background: #000; height: 100px; width: 100%; 
	opacity: .9; 
/* For IE 5-7 */
filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=90);
/* For IE 8 */
-MS-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=90)";
}
.captionfull .boxcaption {top: 260;left: 0;}
.caption .boxcaption {top: 220;left: 0;}
</style>
<?php foreach ($_alldirs as $_dir) : ?>
	<div class="boxgrid <?php echo $effects_types[array_rand($effects_types)]; ?>">
		<a href="<?php echo build_url(array(
				'controller'=>'galleries','action'=>'photos', 'dir'=>$_dir['dirname'],
			)); ?>" title="Voir la galerie">
			<img src="<?php echo get_path('galleries').$_dir['dirname'].'/'.$_dir['src']; ?>" alt="<?php echo $_dir['src']; ?>" width="325" height="260" style="width:325px;height:260px;" border="0" /></a>
		<div class="cover boxcaption">
			<h3><a href="<?php echo build_url(array(
				'controller'=>'galleries','action'=>'photos', 'dir'=>$_dir['dirname'],
			)); ?>"><?php echo $_dir['title']; ?></a></h3>
			<p>
	<?php if (isset($_dir['counts'])) : ?>
			<?php echo $_dir['counts']; ?> photos&nbsp;|&nbsp;
	<?php endif; ?>
			<small><a href="<?php echo build_url(array(
				'controller'=>'galleries','action'=>'photos', 'dir'=>$_dir['dirname'],
			)); ?>">voir la galerie</a></small>
			</p>
		</div>
	</div>
<?php endforeach; ?>
<br class="clear" />