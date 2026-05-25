mp-web-remote
==================

A PHP tool for browsing files and playing videos using MPlayer.

Run on your server, and you can browse the files and control the
player with your mobile phone.

You need to run as the user that's logged in to X.
For instance, you can run
php -S localhost:8000
from the directory containing the PHP files.

I don't suggest you run this on a public server, for (hopefully) obvious reasons.

Original proof of concept by Josh Heidenreich (TheJosh's) for mplayer,
heavily modified and switched to use mpv by Akkana Peck
because mpv has more consistent commands and, most importantly,
can remember its position in files. TheJosh's version didn't quite
work for me, so I got it working, then stashed the working code in
a subdirectory mplayer when I changed gears to work on the mpv version.

Create a file named mp-remote.ini in the same directory as the
rest of these files, containing one line:

mediadir = /path/to/video/files
