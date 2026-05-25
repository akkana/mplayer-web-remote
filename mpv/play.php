<?php
$filepath = $_GET['file'];
error_log('echo will try to play "' . $filepath, 0);

$SOCKETNAME='/tmp/mpvsocket';

if (! `pidof mpv` or !file_exists($SOCKETNAME)) {
    // mpv isn't running yet, so start it.
    //shell_exec('gnome-screensaver-command -p');
    //shell_exec('rm -f ' . $SOCKETNAME);

   shell_exec('mpv --save-position-on-quit --fs --input-ipc-server=' . $SOCKETNAME . ' ' . $filepath . ' &');
}

else {
    // mpv is already running; tell it to load the new file.
    shell_exec('echo loadfile "' . $filepath . '" | socat - /tmp/mpvsocket');
}

header('HTTP/1.0 302 Temp');
header('Location: controls.php');
for ($i = 0; $i < 1000; $i++) { echo 'AAAAAAA'; }
flush();

