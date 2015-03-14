My intent with this code is to give an easy way for authors with a game site containing their flash games to add in all the games from the mochiads feed next to theirs. Hopefully making their site a little more inviting to users.

This script runs off of the mochiads games feed and manages a simple site containing all the games.

See [Recent Games](#recent-games) for how this code can also be used to display a recent games widget in other parts of your site.

Some examples of this script in use

- http://games.wetgenes.com/
- http://www.gamesmosaic.net/

The freehostia one no longer works as the admin page gets killed before it completes, such are free hosting sites :/

#A Simple Games Site (PHP) Script that does not require a database.

UPDATE:20080405
- Mochi changed the feed slightly, this fixes that and adds boxover support.
	


##Required
A web host, that supports php
The files, that are distributed with this file that you are reading.


##Aditional
The ability to edit these simple php scripts into something that you like , you will need to do that, a nicer layout etc is your problem :)


#Recent Games
How to setup a Recent Games Widget using SGSS

*NOTE : There is extra data as of 20080405 code update, so what was $i+=2 is now $i+=3*

Just put the SGSS scripts in a sub folder, in this example it is assumed to be "sgss", make sure you've run admin.php at least once then use some php code like the following on the page you want the recent games links to be in.

```
require "sgss/data/tags/new.php";
for($i=0 ; $i<15; $i+=3 )
{
echo( "<a href=\"sgss/games/".$gamesdata[$i].".php\" title=\"".$gamesdata[$i]."\"><img width=\"100\" height=\"100\" src=\"".$gamesdata[$i+1]."\" /></a>\n");
}
```

Untested :) so may need slight fixes, but that should give you the most recent 5 games,.You will also need to make sure admin.php is run once a day by crontab or something for it to actually update.


##How?

Edit these files to your liking.

game.php is single game display

games.php is the games list display

games.css you like css right?

swfobject.js The wonderful swfobject code -> http://blog.deconcept.com/swfobject/

boxover.js The wonderful boxover code -> http://boxover.swazz.org/

admin.php sets everything up, you should run it occasionally to get new games. ( crontab is your friend )

There is a simple comented out password check at the top of the file if you want to enable it.

upload these files (the files you got with this file) to somewhere on your server (you need a server remember)

open the admin.php in a web browser,

this will create a games and a data subdirectory and read the mochi games feed for new games creating lots of files within them

this may take some time

wait for it to finish , there will be  splurge of all the tags used by the games at the end

you may now visit games.php on your site and play any games you see

you are free to hack these scripts around as you like

In fact I sugest you do it now

but remember

there is no support!!!!1111oneoneone

If things go bad, you are on your own :)


Kriss

(C) Kriss Daniels 2008 http://www.XIXs.com

This file made available under the terms of The MIT License : http://www.opensource.org/licenses/mit-license.php

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
