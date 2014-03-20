<?php
if (empty($config)) $config=array();
if (empty($root_url)) $root_url=_ASSETS.get_path('galleries');
if (empty($current_dir)) $current_dir='';
if (empty($gallery_attributes)) $gallery_attributes=array();
if (empty($gallery_attributes['title'])) $gallery_attributes['title'] = '';
if (empty($images)) $images=array();
if (empty($current_page)) $current_page=1;
if (empty($total_pages)) $total_pages=null;

# ajout au titre
$title_adding = "<a href=\"".build_url(array('controller'=>'galleries','action'=>'index'))."\">&lt;&lt; retour</a>";

# pager ?
if (!is_null($total_pages)) {
	$gallery_attributes['title'] .= ' ('.$current_page.'/'.$total_pages.')';
	$title_adding .= "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
	if ($current_page>1)
		$title_adding .= "<a href=\""
			.build_url(array('controller'=>'galleries','action'=>'photos', 'dir'=>$current_dir, 'offset'=>($current_page-1)))
			."\" title=\""
			."Photos ".( ($current_page-1)*$config['pagelimit'] )." &agrave; ".( ($current_page*$config['pagelimit'])+1 )
			."\">&lt; page ".($current_page-1)."</a>";
	if ($current_page>1 && $current_page<$total_pages)
		$title_adding .= "&nbsp;/&nbsp;";
	if ($current_page<$total_pages)
		$title_adding .= "<a href=\""
			.build_url(array('controller'=>'galleries','action'=>'photos', 'dir'=>$current_dir, 'offset'=>($current_page+1)))
			."\" title=\""
			."Photos ".( ($current_page+1)*$config['pagelimit'] )." &agrave; ".( (($current_page+2)*$config['pagelimit'])-1 )
			."\">page ".($current_page+1)." &gt;</a>";
}
$gallery_attributes['title'] .= htmlentities("<br /><br /><font face=\"Arial\" size=\"11\">".$title_adding."</font>");
?>
<channel>
<?php foreach ($images as $_img) : ?>
  <image imageURL="<?php echo $root_url; ?>/<?php echo $_img['FileName']; ?>"
	thumbURL="<?php echo $root_url; ?>/thumbs/<?php echo $_img['FileName']; ?>"
	linkURL="<?php echo $root_url; ?>/<?php echo $_img['FileName']; ?>"
	linkTarget="_blank">
    <caption><![CDATA[<?php

    echo "<b>".$_img['object name']."</b><br />";
    if (!empty($_img['legend'])) { echo '<p>'.$_img['legend'].'</p>'; }
	echo '<br />'.$_img['DateTimeOriginalTransformed'];
	if (!empty($_img['GPSLatitudeTransformed']) && !empty($_img['GPSLongitudeTransformed'])) {
		echo "<br /><a href=\"http://www.wikimapia.org/#lat=".$_img['GPSLatitudeTransformed']."&amp;lon="
			.$_img['GPSLongitudeTransformed']."&amp;z=17&amp;m=w\" target=\"_blank\">Localiser sur une carte</a>";
	}
	echo "<br /><font size=\"10pt\">";
	if (!empty($_img['Make']) && !empty($_img['Model'])) {
		echo "<br />Appareil: ";
		if (!empty($_img['Model'])) { echo trim($_img['Model']); } 
		if (!empty($_img['Make'])) { echo ' ('.trim($_img['Make']).')'; }
	}
	if (!empty($_img['FocalLength'])) {
		echo "<br />Focale: ".round($_img['FocalLength'], 0)." mm";
	}
	if (!empty($_img['ISOSpeedRatings'])) {
		echo "<br />ISO: ".$_img['ISOSpeedRatings'];
	}
	if (!empty($_img['ApertureFNumber'])) {
		echo "<br />Ouverture: ".$_img['ApertureFNumber'];
	}
	if (!empty($_img['ExposureTime'])) {
		echo "<br />Exposition: ".$_img['ExposureTime'];
	}
	if (!empty($_img['DigitalZoomRatio'])) {
		echo "<br />Zoom Digital: ".$_img['DigitalZoomRatio'];
	}
	echo "</font>";

?>]]></caption>
  </image>
<?php endforeach; ?>
</channel>
