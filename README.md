mplayer-web-remote
==================


A PHP tool for browsing files and playing videos using MPlayer.

Run on your server, and you can browse the files and control the
player with your mobile phone.

You need to run as the user that's logged in to X.
For instance, you can run
php -S localhost:8000
from the directory containing the PHP files.

I don't suggest you run this on a public server, for (hopefully) obvious reasons.

Originally proof of concept by Josh Heidenreich (TheJosh's),
heavily modified by Akkana Peck.

Create a file named mplayer-remote.ini in the same directory as the
rest of these ffiles, containing one line:

mediadir = /path/to/video/files
