<?php

// simple security
/*
if ( ( $_SERVER["PHP_AUTH_USER"] != 'admin' ) || ( $_SERVER["PHP_AUTH_PW"] != "secret" ) ) {

header( 'WWW-Authenticate: Basic realm="Private"' );
header( 'HTTP/1.0 401 Unauthorized' );
echo 'Authorization Required.';
exit;

}
*/


// we need subfolders to store stuff in...
if(!file_exists("games")) {	mkdir("games"); }
if(!file_exists("data")) {	mkdir("data");  }
if(!file_exists("data/tags")) {	mkdir("data/tags"); }




//$file = "games.xml";
$file="http://www.mochiads.com/feeds/games";


$xmlhead="<?php\n\n\$xmlstr=<<<ENDOFSTRING\n
<data>
";

$xmltail="\n</data>\nENDOFSTRING;\n

if(!\$done_game_php)
{
require \"../game.php\";
}
";



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

function desc_to_note($s)
{
	$s=htmlspecialchars($s);
	if(strlen($s)>256)
	{
		$s=substr($s,0,256)."...";
	}
	return $s;
}

$dat=array();
$dat["ent"]=null;
$dat["dep"]=array();
$dat["depn"]=0;
$dat["cnt"]=0;
$dat["gd"]=array();
$dat["tags"]=array();


function startElement($parser, $name, $attrs) 
{
global $dat;
	
	$dat["dep"][$depn]=$name;
	
	if($name=="ENTRY") //start
	{
		$dat["ent"]=array();
		$dat["ent"]["s"]="";
		$dat["ent"]["title"]="";
		$dat["ent"]["cat"]="";
		$dat["ent"]["desc"]="";
		$dat["ent"]["date"]="";
	}
	else
	{
		$dat["depn"]++;
		$dat["dep"][ $dat["depn"] ]=$name;
		
		$dat["ent"]["s"].="<".strtolower($name);
		foreach($attrs as $k=>$v)
		{
			$dat["ent"]["s"].=" ".strtolower($k)."=\"".htmlspecialchars($v)."\"";
		}
		$dat["ent"]["s"].=">";
		
		if($name=="IMG")
		{
			$dat["ent"]["img"]=$attrs["SRC"];
		}
		
//		if($name=="CATEGORY")
//		{
//			$dat["ent"]["cat"].=" ".$attrs["TERM"];
//		}
	}
}

function endElement($parser, $name) 
{
global $dat;

	if($name=="ENTRY") //end
	{
	
		
		$dat["ent"]["title"]=sanitize_title($dat["ent"]["title"]);
		
		$dat["gd"][$dat["cnt"]]=array();
		$dat["gd"][$dat["cnt"]]["title"]=$dat["ent"]["title"];
		$dat["gd"][$dat["cnt"]]["date"]=strtotime($dat["ent"]["date"]);
		$dat["gd"][$dat["cnt"]]["img"]=$dat["ent"]["img"];
		$dat["gd"][$dat["cnt"]]["cat"]=$dat["ent"]["cat"];
		$dat["gd"][$dat["cnt"]]["xml"]=$dat["ent"]["s"];
		$dat["gd"][$dat["cnt"]]["desc"]=$dat["ent"]["desc"];
		$dat["gd"][$dat["cnt"]]["note"]=desc_to_note($dat["ent"]["desc"]);

		echo " ".$dat["cnt"]." : ".date("Y-m-d",$dat["gd"][$dat["cnt"]]["date"])." : ".$dat["ent"]["title"]."<br/>";
		flush();
		
		
		$dat["cnt"]++;
	}
	else
	{
		$dat["depn"]--;
		
		$dat["ent"]["s"].="</".strtolower($name).">";
	}
	
	
}   
   

function characterData($parser, $data) 
{
global $dat;

	$dat["ent"]["s"].=htmlspecialchars($data);
	
	if($dat["dep"][$dat["depn"]]=="TITLE")
	{
		$dat["ent"]["title"].=$data;
	}
	else
	if($dat["dep"][$dat["depn"]]=="MEDIA:DESCRIPTION")
	{
		$dat["ent"]["desc"].=$data;
	}
	else
	if($dat["dep"][$dat["depn"]]=="MEDIA:KEYWORDS")
	{
		$dat["ent"]["cat"].=$data;
	}
	else
	if($dat["dep"][$dat["depn"]]=="UPDATED")
	{
		$dat["ent"]["date"].=$data;
	}
}

$xml_parser = xml_parser_create();
// use case-folding so we are sure to find the tag in $map_array
xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
if (!($fp = fopen($file, "r"))) {
    die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
    }
}

xml_parser_free($xml_parser);


// sort games by time updated not time published so new stuff always gets bumped
function gdcmp($a, $b)
{
global $tags;

    if ($a["date"] == $b["date"]) { return 0; }
    return ( $a["date"] > $b["date"] ) ? -1 : 1;
}
usort($dat["gd"],gdcmp);



$tags=array();
foreach($dat["gd"] as $k=>$v)
{
	$aa=explode(",",$v["cat"]);
	foreach($aa as $aak=>$aav)
	{
		if(($aav)&&($aav!="")) // check we have a tag
		{
			$tag=strtolower(sanitize_title($aav));
			
			if($tag!="")
			{
			
				if($tag=="dressup") { $dat["gd"][$k]["dressup"]=true; } // evil dressup
				if($tag=="customize") { $dat["gd"][$k]["dressup"]=true; } // evil dressup
				
				if(!$tags[$tag]) { $tags[$tag]=array(); }
								
				$doinsert=true;
				foreach($tags[$tag] as $tk=>$tv)
				{
					if($tv==$k) {$doinsert=false;} // already tagged?
				}
				if($doinsert)
				{
					array_push($tags[$tag],$k); // remember idx as tagname
				}
				
			}
		}
	}
}



$tags_order=array();
foreach($tags as $k=>$v)
{
	if(count($v)>=1) // minimum number of games using this tag before we care about it
	{
		array_push($tags_order,$k);
	}
}

function tagcmp($a, $b)
{
global $tags;

	$ac=count($tags[$a]);
	$bc=count($tags[$b]);
    if ($ac == $bc) { return 0; }
    return ( $ac > $bc ) ? -1 : 1;
}

//usort($tags_order, "tagcmp");
// alpha order
sort($tags_order);


foreach($tags_order as $k=>$v)
{
	$t=$tags[$v];
	echo " ".$v." ";
}







$fp=fopen("data/tags.php","w");

fwrite($fp,
"<?php>

\$tagsdata=array(
");

foreach($tags_order as $k=>$v)
{
	$t=$tags[$v];
	fwrite($fp, '"'.$v.'",'.count($t).",\n");
}

fwrite($fp,
"'');
");

fclose($fp);



foreach($tags_order as $k=>$v)
{
	$t=$tags[$v];
	$fp=fopen("data/tags/".$v.".php","w");

fwrite($fp,
"<?php>

\$gamesdata=array(
");

	foreach($t as $tk=>$tv)
	{
		fwrite($fp, '"'.$dat["gd"][$tv]["title"].'","'.$dat["gd"][$tv]["img"]."\",\"".$dat["gd"][$tv]["note"]."\",\n");
	}

fwrite($fp,
"'');
");

	fclose($fp);

}


// save all games, under tag all

$fp=fopen("data/tags/all.php","w");

fwrite($fp,
"<?php>

\$gamesdata=array(
");

foreach($dat["gd"] as $k=>$v)
{
fwrite($fp, '"'.$v["title"].'","'.$v["img"]."\",\"".$v["note"]."\",\n");
}

fwrite($fp,
"'');
");

fclose($fp);



// save new (as in newly updated) games under tag new

$fp=fopen("data/tags/new.php","w");

fwrite($fp,
"<?php>

\$gamesdata=array(
");

$i=0;
foreach($dat["gd"] as $k=>$v)
{

//	if(!$v["dressup"]) // filter out dressup on front page if you wish
	{
		fwrite($fp, '"'.$v["title"].'","'.$v["img"]."\",\"".$v["note"]."\",\n");

		$i++;
		if($i>80) break;
	}
}

fwrite($fp,
"'');
");

fclose($fp);


// save all the cached xml of the games to special php files

foreach($dat["gd"] as $k=>$v)
{
		$fp=fopen("games/".$v["title"].".php","w");
		fwrite($fp,$GLOBALS["xmlhead"]);
		fwrite($fp,$v["xml"]);
		fwrite($fp,"\n<like id=\"".$v["id"]."\" />\n");
		fwrite($fp,$GLOBALS["xmltail"]);
		fclose($fp);
}
		

