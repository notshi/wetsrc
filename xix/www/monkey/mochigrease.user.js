// ==UserScript==
// @name           mochigrease
// @namespace      mg
// @description    oil up some mochi pages
// @include        https://www.mochiads.com/dev/dashboard
// ==/UserScript==


/*

example bits of page we need to find and use...



<p id="server_time">
	All data current as of: <strong>04:13 PST (UTC-8), Mar 14, 2008</strong>
</p>




<td class="impressions">
	<span class="spark" id="spark_0aeed3222e17602f"> </span>
	<span>1</span>
	<em> / 0</em>
</td>


now add an esitimate of the total hits for today onto the current impressions data.

*/


var minutes;



var e;
var a,aa;

e = document.getElementById('server_time');

a=e.innerHTML.split("<strong>");

aa=a[1].split(":");
aa[1]=aa[1].split(" ")[0];
minutes=Math.floor(aa[0])*60 + Math.floor(aa[1]);


//unsafeWindow.console.info(minutes + ": " + aa[0]+" : "+aa[1]);




function adjust_totals(e)
{
var a;

	a=e.innerHTML.split("<span>");
	a=a[1].split("</span>")[0];
	
	a=a.split(",").join("");

	a=Math.floor(a);

	a=Math.floor(a*60*24/minutes); // estimate todays hits

	e.innerHTML+="<sub>("+a+")</sub>";

//	unsafeWindow.console.info(a);
}


var allDivs;


allDivs = document.evaluate(
    "//td[@class='impressions']",
    document,
    null,
    XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE,
    null);
for (var i = 0; i < allDivs.snapshotLength; i++) {
	adjust_totals(allDivs.snapshotItem(i))
}
allDivs = document.evaluate(
    "//th[@class='imp-totals']",
    document,
    null,
    XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE,
    null);
for (var i = 0; i < allDivs.snapshotLength; i++) {
	adjust_totals(allDivs.snapshotItem(i))
}

