<?php

/*+-----------------------------------------------------------------------------------------------------------------+*/
//
// (C) Kriss Daniels 2008 http://www.XIXs.com
//
// This file made available under the terms of The MIT License : http://www.opensource.org/licenses/mit-license.php
//
// Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//
/*+-----------------------------------------------------------------------------------------------------------------+*/


// simple security
if ( ( $_SERVER["PHP_AUTH_USER"] != 'admin' ) || ( $_SERVER["PHP_AUTH_PW"] != "secret" ) ) {

header( 'WWW-Authenticate: Basic realm="Private"' );
header( 'HTTP/1.0 401 Unauthorized' );
echo 'Authorization Required.';
exit;

}


if(!file_exists("games"))
{
	mkdir("games"); // we need a subfolder to store stuff in...
}


//$file = "games.xml";

$file="http://www.mochiads.com/feeds/games";


$xmlhead="<?php\n\n\$xmlstr=<<<ENDOFSTRING\n
<data>
";

$xmltail="\n</data>\nENDOFSTRING;\n

require \"../game.php\";
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



$dat=array();
$dat["ent"]=null;
$dat["dep"]=array();
$dat["depn"]=0;
$dat["cnt"]=0;
$dat["gd"]=array();


function startElement($parser, $name, $attrs) 
{
global $dat;
	
	$dat["dep"][$depn]=$name;
	
	if($name=="ENTRY") //start
	{
		$dat["ent"]=array();
		$dat["ent"]["s"]="";
		$dat["ent"]["title"]="";
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
	}
}

function endElement($parser, $name) 
{
global $dat;

	if($name=="ENTRY") //end
	{
	
		
		$dat["ent"]["title"]=sanitize_title($dat["ent"]["title"]);
		
		$fp=fopen("games/".$dat["ent"]["title"].".php","w");
		fwrite($fp,$GLOBALS["xmlhead"]);
		fwrite($fp,$dat["ent"]["s"]);
		fwrite($fp,$GLOBALS["xmltail"]);
		fclose($fp);
		echo " ".$dat["cnt"]." : ".$dat["ent"]["title"]."<br/>";
		flush();
		
		$dat["gd"][$dat["cnt"]]=array();
		$dat["gd"][$dat["cnt"]]["title"]=$dat["ent"]["title"];
		$dat["gd"][$dat["cnt"]]["img"]=$dat["ent"]["img"];
		
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



$fp=fopen("gamesdata.php","w");

fwrite($fp,
"<?php>

\$gamesdata=array(
");

foreach($dat["gd"] as $k=>$v)
{
fwrite($fp, '"'.$v["title"].'","'.$v["img"]."\",\n");
}

fwrite($fp,
"'');
");

fclose($fp);


