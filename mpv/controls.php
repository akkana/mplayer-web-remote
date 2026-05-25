<?php

if (! `pidof mpv`) {
    header('Location: index.php');
    exit();
}

include 'commands.php';

$message = '&nbsp;';

# Get paused state, since this affects lots of other things,
# like which commands might unwantedly un-pause.
$paused = send_mpv_cmd('{ "command": ["get_property", "pause"] }\n');
error_log("paused status: " . print_r($paused, true), 0);
if ($paused)
    error_log('Paused', 0);
else
    error_log('NOT Paused', 0);

$curvol = send_mpv_cmd('{ "command": ["get_property", "volume"] }\n');
error_log('Volume ' . $curvol, 0);

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
            send_mpv_cmd('{ "command": ["set_property", "pause", true] }');
            $paused = 1;
            break;

        case 'play':
            send_mpv_cmd('{ "command": ["set_property", "pause", false] }');
            break;

        case 'back':
            send_mpv_cmd('{ "command": [ "seek", "-10" ] }');
            break;

        case 'forward':
            send_mpv_cmd('{ "command": [ "seek", "+10" ] }');
            break;

        case 'mute':
            send_mpv_cmd('{ "command": ["set_property", "mute", true] }');
            break;

        case 'unmute':
            send_mpv_cmd('{ "command": ["set_property", "mute", false] }');
            break;

        case 'volumeup':
            $curvol += 5;
            if ($curvol > 100)
                $curvol = 100;
            send_mpv_cmd('{ "command": ["set_property", "volume", '
                       . $curvol . '] }');
            break;

        case 'volumedown':
            $curvol -= 5;
            if ($curvol < 0)
                $curvol = 0;
            send_mpv_cmd('{ "command": ["set_property", "volume", '
                       . $curvol . '] }');
            break;

        case 'aspect':
            // change_rectangle <val1> <val2>
            $message = "Sorry, don't know how to change aspect ratio yet";
            break;

        case 'status':
            $message = shell_exec('sh ./mpvstatus.sh');

        case 'close':
            break;

        case 'reallydelete':
            // sadly, pausing_keep_force doesn't work with get_property filename
            // or path: it doesn't print anything
            $filepath = send_mpv_cmd('{ "command": ["get_property", "path"] }\n');
            error_log("filepath: " . $filepath);

            //send_mpv_cmd("quit");
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
<title>Media Centre PRO 4000 Extreme Edition</title>
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
<?php if ($paused): ?>
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
<td><a href="index.php">Browse</a>
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

