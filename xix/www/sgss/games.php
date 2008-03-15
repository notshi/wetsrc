<?php

require "data/tags.php";


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


require "data/tags/$tag.php";

header('Content-type: text/html; charset=utf-8');


$anal_str=<<<ENDOFSTRING
ENDOFSTRING;

$text='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en"><head><title>'.$tag.' games . simple games site script</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="games.css"></link>
</head>
<body class="zero"><center><div>
';

	echo $text;


echo("<center><h1>".$tag." games . simple game site script </h1><div style=\"width:640px\">");

	echo( "<a href=\"games.php?tag="."all"."\" style=\"color:#ffffff\">"."all"."</a> ");
	echo( "<a href=\"games.php?tag="."new"."\" style=\"color:#ffffff\">"."new"."</a> ");
for($i=0 ; $tagsdata[$i] ; $i+=2 )
{
	$tc=floor($tagsdata[$i+1]);
	if($tc>7) // number of games to have tag before we show it
	{
		$cb=floor(log($tagsdata[$i+1],2)*2);
		$cb=floor($cb*(255)/16);
		if($cb<0){$cb=0;}
		if($cb>255){$cb=255;}
		$cb=sprintf("%02x%02x%02x",$cb,$cb,$cb);
		
		echo( "<a href=\"games.php?tag=".$tagsdata[$i]."\" style=\"color:#".$cb."\">".$tagsdata[$i]."</a> ");
	}
}

echo("</div><br /><br /><div style=\"width:800px\">");

for($i=0 ; $gamesdata[$i] ; $i+=2 )
{
	$title=str_replace("_"," ",$gamesdata[$i]);
	$titleurl=str_replace("_","+",$gamesdata[$i]);
	echo( "<a href=\"games/".$gamesdata[$i].".php\" title=\"".$title."\"><img width=\"100\" height=\"100\" src=\"".$gamesdata[$i+1]."\" /></a>\n");
}

echo("</div><br /><br /><small><a href=\"http://www.WetGenes.com/\">Original site code from www.wetgenes.com</a></small></center>");


$text='
</div>
</center>
'.$GLOBALS["anal_str"].'
</body>
</html>';

	echo $text;

	