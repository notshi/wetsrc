// ==UserScript==
// @name           techcrunchlogos
// @namespace      tcl
// @description    display demopit techcrunchlogos for shi :)
// @include        http://www.techcrunch40.com/2007/demopit.php
// ==/UserScript==


// I love ya baby cakes :)



var allLinks, thisLink;
allLinks = document.evaluate(
    '//a[@href]',
    document,
    null,
    XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE,
    null);
for (var i = 0; i < allLinks.snapshotLength; i++) {
    thisLink = allLinks.snapshotItem(i);
	
    // do something with thisLink
	
	
	var aa=thisLink.href.split("demopit=");
	
	if(aa[1])
	{
	
		thisLink.innerHTML="<img src=\"http://www.techcrunch40.com/2007/images/logos/demo_pit_"+aa[1]+".gif\" />";
	}
}




