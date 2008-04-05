<?php


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



$dat=array();
$dat["dep"]=array();
$dat["depn"]=0;
$dat["tags"]="";



$anal_str=<<<ENDOFSTRING
ENDOFSTRING;


function startElement($parser, $name, $attrs) 
{
global $dat;
	
	$dat["depn"]++;
	
	$dat["dep"][ $dat["depn"] ]=$name;
	
	switch($name)
	{
		case "MEDIA:PLAYER":
		
			$dat["swf"]=$attrs["URL"];
			$dat["xp"]=floor($attrs["WIDTH"]);
			$dat["yp"]=floor($attrs["HEIGHT"]);
		break;
		
//		case "CATEGORY";
//			$dat["tags"].=" ".$attrs["TERM"];
//		break;
	}
	
	$dat["text"]="";
	$dat["class"]=$attrs["CLASS"];
	
}

function endElement($parser, $name) 
{
global $dat;

	$dat["depn"]--;
	
	switch($name)
	{
		case "DD":
			switch($dat["class"])
			{
				case "description":
					$dat["desc"]=$dat["text"];
				break;
			}
		break;
		
		case "TITLE":
			$dat["title"]=$dat["text"];
		break;
	}
}

function characterData($parser, $data) 
{
global $dat;

	$dat["text"].=$data;
	
	switch($dat["dep"][ $dat["depn"] ])
	{
		case "MEDIA:KEYWORDS":
			$dat["tags"].=$data;
		break;		
	}
}


$xml_parser = xml_parser_create();

xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");

$xmlstr="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".$xmlstr;

if (!xml_parse($xml_parser, $xmlstr, null ))
{
	die(sprintf("XML error: %s at line %d",
				xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser)));
}

xml_parser_free($xml_parser);


$text='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en"><head><title>simple games site script : '. $dat["title"] .'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../games.css"></link>
<script type="text/javascript" src="../swfobject.js"></script>
</head>
<body class="zero">
<a href="../games.php" target="_TOP">Back to list<br /> of new games.<br /></a>
<center><div style="width:'.$dat["xp"].'px;">
';

	echo $text;


$text='
<center>
<div class="zero" id="flashgame">
</div>

<script type="text/javascript">
   var so = new SWFObject("'.$dat["swf"].'", "itsagame", "'.$dat["xp"].'", "'.$dat["yp"].'", "7", null );
	so.addParam("quality", "high");
	so.addParam("allowScriptAccess", "always");
	so.addParam("allowFullScreen", "true");
   so.write("flashgame");
</script>
';
	echo $text;

	
$tags=explode(" ",$dat["tags"]);
$s="";
foreach($tags as $k=>$v)
{
	$t=strtolower(sanitize_title($v));
	
	if($t!="")
	{
		if($s!="") {$s.=" , "; }
		
		$s.="<a href=\"../games.php?tag=".$t."\" target=\"_TOP\">".$t."</a>";
	}
}

echo "<h1>".$dat["title"]."</h1><br/>";
echo $dat["desc"] . "<br/><br/>";
echo $s . "<br/>";

echo "<a href=\"../games.php\" target=\"_TOP\"><h1>Back to list of new games.</h1></a><br/><br /><br /><small><a href=\"http://www.WetGenes.com/\">Original SGSS code from www.wetgenes.com</a></small>";

$text='
</center>
</div>
</center>
'.$GLOBALS["anal_str"].'
</body>
</html>';

	echo $text;
