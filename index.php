<?php header("Expires: 0"); header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); header("cache-control: no-store, no-cache, must-revalidate"); header("Pragma: no-cache");?>
<!-- Force browser not to use cache -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<head>
<title>Slideshow</title>
<?php
$pattern = '/\.(png|gif|jpg|jpeg)$/i';
$dh = opendir('.');
$height = 0;
while (false !== ($filename = readdir($dh))) {
  if (!is_dir($filename) && $filename[0] != '.' && preg_match($pattern, $filename)) {
    $files[] = $filename;
    if ($height == 0) {
        list($width, $height) = getimagesize($filename);
    }
  }
}
$count = intval($_REQUEST['count']);
if ($count == 0) $count = 30;
/*
if (count($files) == 0) {
  throw new Exception(“No images found“);
}
*/
shuffle($files);
if (count($files) > $count) {
  array_splice($files, $count);
}
closedir($dh);
?>
<script type="text/javascript">
window.setTimeout(function(){ document.location.reload(true); }, 86400000); /* refresh page 1000 = 1 sek */
</script>
<style type="text/css">
body {
  overflow:hidden;
  color: #333333; // text color
}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
/***
Simple jQuery Slideshow Script
Released by Jon Raasch (jonraasch.com) under FreeBSD license: free to use or modify, not responsible for anything, etc.  Please link out to me if you like it :)
***/
function slideSwitch() {
  var $active = $('#slideshow IMG.active');
  if ( $active.length == 0 ) $active = $('#slideshow IMG:last');
  var $next =  $active.next().length ? $active.next() : $('#slideshow IMG:first');
  $active.addClass('last-active');
  $next.css({opacity: 0.0}).addClass('active').animate({opacity: 1.0}, 10, "linear", function() {$active.removeClass('active last-active');});
}
$(function() {
  setInterval( "slideSwitch()", 6000 );
});
</script>
<style type="text/css">
body {padding:0; margin:0;}
#slideshow { cursor:none; position:relative; height:100%; }  /***  Hides cursor  ***/
#slideshow IMG {position:absolute; top:0; left:0; z-index:8; opacity:0.0;}
#slideshow IMG.active {z-index:10; opacity:1.0;}
#slideshow IMG.last-active {z-index:9;}
</style>
</head>

<?php
  //
  // Sletter filer når datoen er overstået i formatet 2017 12 30
  // F.eks. slettes slidedato_20180917.jpg den 17 sep 2018
  //
foreach (glob("slidedato_*.jpg") as $filename)
{
    list(, $date, $time) = explode('_', substr($filename, 0, -4));

    $date = substr($date, 0, 4) . '.'  . substr($date, 4, 2) . '.'  . substr($date, 6, 2);
    // echo "The datetime for <b>$filename</b> is $date<br />";

$timestamp = $date . "T00:20";

$today = new DateTime(); // This object represents current date/time
$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

$match_date = DateTime::createFromFormat( "Y.m.d\\TH:i", $timestamp );
$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

$diff = $today->diff( $match_date );
$diffDays = (integer)$diff->format( "%R%a" ); // Extract days count in interval

switch( $diffDays ) {

	default:
        if ($diffDays >= 0) { echo "";}
	    if ($diffDays <= -1) { unlink($filename);}
		break;
 }
}
?>



<body bgcolor="#000">
<div id="slideshow">
<?php

//  Skærmskåner der vises fra kl. 02 til 06
    if (date('H') >= 2 && date('H')  <= 6) {
   echo '<h1> Skærmskåner!</h1><br>';
   // date('H') er klokken i hele timer

} else {
$first = true;
foreach ($files as $file) {
    echo ' <img width="100%" src="'.$file.'"'.($first ? ' class="active"' : '').' />';
    $first = false;
} }


?>
</div>
</body>
</html>
