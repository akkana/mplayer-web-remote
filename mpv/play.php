<?php
$filepath = $_GET['file'];
error_log('will try to play ' . $filepath, 0);

$SOCKETNAME = '/tmp/mpvsocket';

if (! `pidof mpv` or !file_exists($SOCKETNAME)) {
    // mpv isn't running yet, so start it.
    //shell_exec('gnome-screensaver-command -p');
    //shell_exec('rm -f ' . $SOCKETNAME);

    error_log('Starting a new mpv ...', 0);
    shell_exec('mpv --save-position-on-quit --fs --input-ipc-server='
             . $SOCKETNAME . ' ' . $filepath . ' </dev/null >/dev/null 2>&1 &');
    error_log('Started it', 0);
}

else {
    // mpv is already running; tell it to load the new file.
    error_log('Telling mpv to play ' . $filepath, 0);
    shell_exec('echo loadfile "' . $filepath . '" | socat - /tmp/mpvsocket');
    error_log('Told it', 0);
}

error_log("Sleeping ...", 0);
sleep(2);
error_log("Showing controls", 0);
//header('HTTP/1.0 302 Temp');
header('Location: controls.php');

// header() doesn't seem to work consistently, so in case it doesn't,
// here's another possible way:
echo '<script type="text/javascript">';
echo 'window.location = "controls.php";';
echo '</script>';

?>
