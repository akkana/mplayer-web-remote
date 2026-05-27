mp-web-remote
==================

A PHP tool for browsing files and playing videos using MPlayer.

Run on your server, and you can browse the files and control the
player with your mobile phone.

You need to run as the user that's logged in to X.
For instance, you can run
```
php -S localhost:8000
```
from the directory containing the PHP files.

I don't suggest you run this on a public server, for (hopefully) obvious reasons.

Original proof of concept by Josh Heidenreich (TheJosh) for mplayer,
heavily modified and switched to use mpv by Akkana Peck
because mpv has more consistent commands and, most importantly,
can remember its position in files. TheJosh's version didn't quite
work for me, so I got it working, then stashed the working code in
a subdirectory mplayer when I changed gears to work on the mpv version.

Requires packages: mpv php socat
On Debian, php pulls in apache2, which seems kind of annoying and unnecessary.

Create a file named mp-remote.ini in the same directory as the
rest of these files, containing one line:

```
mediadir = /path/to/video/files
```

Make a symbolic link in the directory you're using (mpv or mplayer)
to ../images, e.g.
```
cd mpv; ln -s ../images .
```

Then, from the directory with the index.php file (mplayer or mpv),
run the remote control as:
```
    php -S localhost:8000
```
to test locally, or
```
    php -S [hostname-or-IP-addr]:8000
```
if you want it accessible to other machines, like a phone.

It needs to be run as the user who owns the X session,
and who also has permissions for things like audio.
