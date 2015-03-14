#WetPlay : An XSPF based MP3 flash player. 

*Updated Feb 4, 2010 by KrissD*

You can find this player hiding in the top right hand corner of all my flash [games](http://www.wetgenes.com).

We support http://grabb.it/ as an XSPF source. That's web editable MP3 play lists for the uninitiated.

Remember flash has security issues, so if you run the created swf locally you probably won't be able to access any of the files (IE MP3s) on the internet from it. It needs to be run from an actually website or you need to adjust your flash security settings.

I've updated the source download, many changes over the last year, everything is better, hit the downloads section for the code.

When you have all of that you can use a windows machine to build, go into the wetplay directory and run the bake.bat file. It should, just build, nothing else is needed to install. Everything, including all build tools, is in the zip and all directories are relative. There may be some problems with spaces in directory names with some tools used so do not put it in any folders with spaces in them, such as the one named "my documents".

After bake.bat has run wetplay/out will contain a compiled .swf and a .html test page that can be loaded into a browser.

Here is a prebuilt .swf

https://github.com/notshi/wetsrc/blob/master/xix/swf/wetplay/fin/WetPlay.20080316.swf

Depending on your browser the above link may just work and open in your browser or prompt you to download, this is due to the missing Content-Type in the headers being sent from googlecode so there is nothing I can do about it...

See below for documentation of the possible runtime configuration.


#WetPlayFlashvars
###Runtime configuration of WetPlay

*Updated Feb 4, 2010 by KrissD*

Flashvars, are extra bits of configuration you can tack onto the end of a swf to tell it how to behave. WetPlay has a number of things you can change and these will also work when it is embedded in games.

- wp_jpg = A link to a background image, which give a simple interface skinning.
- wp_w = The width in pixels. Default of 380.
- wp_h = The height in pixels. Default of 200.
- wp_x = Number of pixels to skip from left before we start drawing gizmos. Default of 10.
- wp_y = Number of pixels to skip from left before we start drawing gizmos. Default of 10.
- wp_s = The row size, or font size. Every gizmo is a multiple of this wide or high. Default of 20.
- wp_fore = Foreground color.
- wp_back = Background color.
- wp_back_alpha = Alpha value of background.
- wp_mp3 = Load this MP3 file.
- wp_xspf = Load this XSPF file.
- wp_vol = Set volume to this.
- wp_force = Override user settings with all these settings.
- wp_auto = Start playing automatically.
- wp_shuffle = Randomize the playing order of MP3s.
- wp_loop = Repeat, never stop playing.
- wp_sfx = Enable sound effects (Only makes sense inside games)
