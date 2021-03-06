<?php

require "data/tags.php";


// minimum number of times a tag must be used before it will show up in the tag cloud.
$tagminuse=8;

function sanitize_title($s)
{
	$list = '';

	for ($i = 0; $i < 32; $i++) $list .= chr($i);

	for ($i =  33; $i <  48; $i++) $list .= chr($i);
	for ($i =  58; $i <  65; $i++) $list .= chr($i);
	for ($i =  91; $i <  95; $i++) $list .= chr($i);
	for ($i =  96; $i <  97; $i++) $list .= chr($i);
	for ($i = 123; $i < 256; $i++) $list .= chr($i);
	
	$s=str_replace("\0", "", strtr($s, $list, str_repeat("\0", strlen($list))));
	
	$s=trim($s);
	while($s!=str_replace("  "," ",$s))
	{
		$s=str_replace("  "," ",$s);
	}

		
	$s=str_replace(" ","_",$s);
	
	return ($s);
}

// get a tag or all games?
$tag=$_GET["tag"];
if(!$tag) { $tag="new"; }
$tag=sanitize_title($tag);


// check tag is valid
if(!file_exists("data/tags/$tag.php")) { $tag="new"; }



$anal_str=<<<ENDOFSTRING
ENDOFSTRING;

$text='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en"><head><title>'.$tag.' games at simple games site script </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="games.css"></link>
<script type="text/javascript" src="boxover.js"></script>
</head>
<body class="zero">
<center><div class="games">
';

	echo $text;

if($tag=="all") { $tagminuse=2; }


echo("<center><br /><div class=\"tagbox\"><h1> ".$tag." games at Simple Games Site Script</h1><br />");

	echo( "<a href=\"games.php?tag="."all"."\" style=\"color:#ffffff\">"."all"."</a> ");
	echo( "<a href=\"games.php?tag="."new"."\" style=\"color:#ffffff\">"."new"."</a> ");
for($i=0 ; $tagsdata[$i] ; $i+=2 )
{
	$tc=floor($tagsdata[$i+1]);
	if($tc>=$tagminuse) // number of games to have tag before we show it
	{
		$cb=floor(log($tagsdata[$i+1],2)*2);
		$cb=floor($cb*(255)/16);
		if($cb<0){$cb=0;}
		if($cb>255){$cb=255;}
		$cb=sprintf("%02x%02x%02x",$cb,$cb,$cb);
		
		echo( "<a href=\"games.php?tag=".$tagsdata[$i]."\" style=\"color:#".$cb."\">".$tagsdata[$i]."</a> ");
	}
}

echo("</div><br /><br /><div style=\"width:724px\">");


// if you want to pimp your games, tag them all with your name, for example wetgenes and use some code like this
// to always insert your games at the head of the main new page
/*
if($tag=="new") // add my games at top of front page...
{
	require "data/tags/wetgenes.php";
	for($i=0 ; $gamesdata[$i] ; $i+=3 )
	{
		$title=str_replace("_"," ",$gamesdata[$i]);
		$titleurl=str_replace("_","+",$gamesdata[$i]);
		echo( "<a href=\"games/".$gamesdata[$i].".php\" title=\"header=[".$title."] body=[".$gamesdata[$i+2]."]\"><img width=\"100\" height=\"100\" src=\"".$gamesdata[$i+1]."\" /></a>\n");
	}
}
*/

require "data/tags/$tag.php";
for($i=0 ; $gamesdata[$i] ; $i+=3 )
{
	$title=str_replace("_"," ",$gamesdata[$i]);
	$titleurl=str_replace("_","+",$gamesdata[$i]);
	echo( "<a href=\"games/".$gamesdata[$i].".php\" title=\"header=[".$title."] body=[".$gamesdata[$i+2]."]\"><img width=\"100\" height=\"100\" src=\"".$gamesdata[$i+1]."\" /></a>\n");
}

echo("</div><br /><br /><small><a href=\"http://www.WetGenes.com/\">Original SGSS code from www.wetgenes.com</a></small></center>");


$text='
</div>
</center>
'.$GLOBALS["anal_str"].'
</body>
</html>';

	echo $text;

	