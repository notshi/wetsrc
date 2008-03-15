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

require "gamesdata.php";


$head='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en"><head><title> Simple Games Site Script </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="games.css"></link>
</head>
<body class="zero" ><center><div class="games">
<h1> Simple Games Site Script </h1><br/>
';

$tail='
</div>
</center>
</body>
</html>';


header('Content-type: text/html; charset=utf-8');


	echo $head;

$i=0;

echo("<center>");
while( $gamesdata[$i] )
{

echo( "<a href=\"games/".$gamesdata[$i].".php\" title=\"".$gamesdata[$i]."\"><img width=\"100\" height=\"100\" src=\"".$gamesdata[$i+1]."\" /></a>\n");

$i+=2;
}

echo("<br /><br /><small><a href=\"http://www.WetGenes.com/\">Simple game site script from www.wetgenes.com</a><br /><a href=\"http://wetsrc.sf.net/\">Get the free simple game site script here.</a></small></center>");


	echo $tail;

	