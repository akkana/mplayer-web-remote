<?php
/*
   Run this as php -S localhost:8000
   from the directory with the index.php file.
   It needs to be run as the user who owns the X session,
   and which also has access to things like audio.
 */

//shell_exec('gnome-screensaver-command -p');

shell_exec('killall mplayer');
shell_exec('rm -f /tmp/mplayer-fifo');
shell_exec('mkfifo /tmp/mplayer-fifo');
shell_exec('ls -l /tmp/mplayer-fifo >&2');

shell_exec('echo will try to play "' . $_GET['file'] . '" >&2');

shell_exec('sh ./play.sh "' . $_GET['file'] . '" </dev/null >/dev/null 2>&1 &');
//shell_exec('sh ./play.sh "' . $_GET['file'] . '" </dev/null &');

header('HTTP/1.0 302 Temp');
header('Location: controls.php');
for ($i = 0; $i < 1000; $i++) { echo 'AAAAAAA'; }
flush();

