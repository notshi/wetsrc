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




$dat=array();
$dat["dep"]=array();
$dat["depn"]=0;



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
				
				case "categories":
					$dat["cat"]=$dat["text"];
				break;
				
				case "keywords":
					$dat["keys"]=$dat["text"];
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
		
	}
}


$xml_parser = xml_parser_create();

xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");


if (!xml_parse($xml_parser, $xmlstr, null ))
{
	die(sprintf("XML error: %s at line %d",
				xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser)));
}

xml_parser_free($xml_parser);





$head='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en"><head><title> Simple Games Site Script : '. $dat["title"] .'</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../games.css"></link>
<script type="text/javascript" src="../swfobject.js"></script>
</head>
<body class="zero" ><center><div class="game" style="width:'.$dat["xp"].'px;">
';


$tail='
</div>
</center>
</body>
</html>';





header('Content-type: text/html; charset=utf-8');


	echo $head;


$text='
<center>
<br />
<br />
<div class="zero" id="flashgame">
</div>

<script type="text/javascript">
   var so = new SWFObject("'.$dat["swf"].'", "itsagame", "'.$dat["xp"].'", "'.$dat["yp"].'", "7", "#000000");
	so.addParam("quality", "high");
	so.addParam("allowScriptAccess", "always");
	so.addParam("allowFullScreen", "true");
   so.write("flashgame");
</script>
';
	echo $text;

	
echo "<h1>".$dat["title"]."</h1><br/>";
echo $dat["desc"] . "<br/><br/>";
echo $dat["cat"] . ", ";
echo $dat["keys"] . "<br/>";

echo "<a href=\"../games.php\"><h1>Back to list of games.</h1></a><br/><br /><br /><small><a href=\"http://www.WetGenes.com/\">Simple game site script by www.wetgenes.com</a></small></center>";


	echo $tail;
