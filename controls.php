<?php

/*
*/
if (! `pidof mplayer`) {
    shell_exec('rm /tmp/mplayer-fifo');
    header('Location: index.php');
    exit();
}

# No way to know if it's paused, if ...XXX
$paused = 0;

$message = '&nbsp;';

function send_mp_cmd($cmd) {
    shell_exec('echo "' . $cmd . '" >/tmp/mplayer-fifo');
}

// Read a single value from mplayer.
// Pass in the command to read it, e.g.
//     pausing_keep_force get_property pause
// Returns the value as a string, e.g. 'yes'.
function read_mp_val($prop_cmd) {
    $mplayer_outfile = '/tmp/mplayer.out';
    // Zero out the mplayer output file
    $fp = fopen($mplayer_outfile, "w");
    // fwrite($fp, '');
    fclose($fp);

    // Now tell mplayer to write its status to stdout
    // get_percent_pos, get_time_pos
    // volume <value> [abs]
    // get the 'pause' property
    // get_property pause
    send_mp_cmd($prop_cmd);
    //sleep(1);
    usleep(100000);    // microseconds

    // and read whatever mplayer wrote after that
    $fp = fopen($mplayer_outfile, "r");
    // The read includes, for some reason, a lot of nulls at the beginning,
    // so trim them off.
    $result = trim(fread($fp, filesize($mplayer_outfile)));
    error_log('Read: ' . $result, 0);

    // This is something like
    // "ANS_pause=no" or yes
    // ANS_percent_pos=N
    // ANS_path=/path/to/file.mp4

    //if (str_starts_with($result, 'ANS_'))
    //    $result = substr($result, 4);

    $equals = strpos($result, '=');
    if ($equals) {
        $result = substr($result, $equals+1);
        error_log('Stripped equals: ' . $result, 0);
    }
    return $result;
}

if (isset($_GET['action'])) {
    // http://www.mplayerhq.hu/DOCS/tech/slave.txt
    // also, mplayer -input cmdlist
    // prefixes:
    //   pausing: pause ASAP after processing the command
    //   pausing_keep: do command only if already paused;
    //   pausing_toggle: do command only if not already paused.
    // but none of these actually work for "pause",
    // it toggles regardless of whether it's prefixed with pausing_keep
    // or pausing_toggle.

    switch ($_GET['action']) {
        case 'pause':
            send_mp_cmd("pausing_toggle pause");
            $paused = 1;
            break;

        case 'play':
            send_mp_cmd("pausing_keep pause");
            break;

        case 'back':
            send_mp_cmd("pausing_keep seek -10");
            break;

        case 'forward':
            send_mp_cmd("pausing_keep seek +10");
            break;

        case 'mute':
            send_mp_cmd("mute");
            break;

        case 'volumeup':
            send_mp_cmd("volume +.3");
            break;

        case 'volumedown':
            send_mp_cmd("volume -.3");
            break;

        case 'aspect':
            // change_rectangle <val1> <val2>
            $message = "Sorry, don't know how to change aspect ratio yet";
            break;

        case 'status':
            // Without pausing_keep_force, get_property pause will unpause
            // a paused video and thus will always return ANS_pause=no.
            // With it, it will return ANS_pause=yes if currently paused
            // and won't unpause it.
            //error_log('trying to get status\n', 0);
            $message = 'Paused? ' +
                       read_mp_val("pausing_keep_force get_property pause");
            $message = $message . ' Percent: '
                     . read_mp_val("get_property percent_pos");

        case 'close':
            send_mp_cmd("quit");
            while (`pidof mplayer`) {
                sleep(1);
            }
            break;

        case 'reallydelete':
            // sadly, pausing_keep_force doesn't work with get_property filename
            // or path: it doesn't print anything
            $filepath = read_mp_val("get_property path");
            error_log("filepath: " . $filepath);

            //send_mp_cmd("quit");
            //shell_exec('rm ' . $filepath);
            $message = 'Would delete ' . $filepath;
            break;

        case 'poweroff':
            // shell_exec('sudo poweroff');
            $message = "Poweroff disabled during testing";
            break;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Media Centre PRO 3000 Extreme Edition</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body>

<center>

<h1>Media Centre PRO 3000 Extreme Edition</h1>

<table class="controls">
<tr>
<td><a href="?action=back">
    <img src="images/skip-backward.svg"
         width="64" height=64"" alt="Back"></a></td>
<td>
<?php
if (isset($_GET['paused'])): ?>
    <a href="?action=play">
    <img src="images/start.svg" width="64" height="64" alt="Play"></a>
<?php else: ?>
    <a href="?action=pause">
    <img src="images/pause.svg" width="64" height="64" alt="Pause"></a>
<?php endif; ?>
</td>
<td><a href="?action=forward">
    <img src="images/skip-forward.svg" width="64" height="64" alt="Forward"></a>
</tr>

<tr class="spacer"><td>&nbsp;

<tr>
<td><a href="?action=volumedown">
    <img src="images/volume-down.svg"
         width="64" height="64" alt="Volume down"></a></td>
<td><a href="?action=volumeup">
    <img src="images/volume-up.svg"
         width="64" height="64" alt="Volume down"></a></td>

<td>

<button command="show-modal" commandfor="delete-dialog">
<img src="images/trash.svg" width="64" height="64" alt="Delete">
</button>

</tr>

<tr class="spacer"><td>&nbsp;

<tr>
<td><a href="/">Browse</a>
<td><a href="?action=aspect">Aspect</a>
<td><a href="?action=status">Get status</a>

<tr>
<td><a href="?action=close">Quit</a>
<td><button command="show-modal" commandfor="poweroff-dialog">
<img src="images/power.svg" width="64" height="64" alt="Power button">
</button>

</table>

<div id="status">
<?php echo $message; ?>
</div>

<dialog id="delete-dialog" class="dialog">
  <p>Really delete?

  &nbsp; &nbsp; &nbsp; &nbsp;
  <a href="?action=reallydelete"><button commandfor="delete-dialog" command="close">Yes</button></a>

  &nbsp; &nbsp; &nbsp; &nbsp;
  <button commandfor="delete-dialog" command="close">No</button>
</dialog>

<dialog id="poweroff-dialog"class="dialog">
  <p>Really power off?

  &nbsp; &nbsp; &nbsp; &nbsp;
  <a href="?action=poweroff"><button commandfor="poweroff-dialog" command="close">Yes</button></a>

  &nbsp; &nbsp; &nbsp; &nbsp;
  <button commandfor="poweroff-dialog" command="close">No</button>
</dialog>

</center>
</body>
</html>

