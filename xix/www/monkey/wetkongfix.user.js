// ==UserScript==
// @name           wetkongfix
// @namespace      wetkongfix
// @description    example script to makes kongs ratings "better"
// @include        http://*.kongregate.com/*
// @include        http://kongregate.com/*
// ==/UserScript==


//unsafeWindow.console.info('start');


var node = document.getElementById('game_ratings_size');


var s=node.innerHTML
var total=parseFloat(node.innerHTML.split(" ")[0]);
var ave=parseFloat(node.innerHTML.split("(")[1].split(" ")[0]);
var rate=Math.floor(total*ave);

// rate is now the total number of points given to a game, all you 1/5 haters are now an asset...



var v=128;
var width=0;
var disp=0;

while(v<rate)
{
	v=v*2;
	width+=13;
	disp+=1;
}

if(v!=128)
{
	v=v/2;
	width+=Math.floor(13*((rate-(v))/v));
	disp+=Math.floor(10*((rate-(v))/v))/10;
}
else
{
	width+=Math.floor(13*(rate/v));
	disp+=Math.floor(10*(rate/v))/10;
}
// one star at 128, 2 stars at 256, 3 stars at 512, 4 stars at 1024 and so on...



var star = "<div style='width:"+width+"px;height:12px;background-image:url(http://kongregate.com/images/presentation/star_rating.gif);background-position: 0px -24px;'> </div>";


node.innerHTML += "<br>Wet rating: "+ disp +" "+ star;

