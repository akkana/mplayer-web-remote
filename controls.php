<?php

if (! `pidof mplayer`) {
    shell_exec('rm /tmp/mplayer-fifo');
    header('Location: index.php');
    exit();
}

# No way to know if it's paused, if ...XXX
$paused = 0;

function send_mp_cmd($cmd) {
    shell_exec('echo "' . $cmd . '" >/tmp/mplayer-fifo');
}

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
    sleep(1);
    //usleep(500000);

    // and read whatever mplayer wrote after that
    $fp = fopen($mplayer_outfile, "r");
    $result = fread($fp, filesize($mplayer_outfile));
    return $result;
}

if (isset($_GET['action'])) {
    // http://www.mplayerhq.hu/DOCS/tech/slave.txt
    // also, mplayer -input cmdlist
    // prefixes:
    // pausing: pause ASAP after processing the command
    // pausing_keep: do command only if already paused;
    // pausing_toggle: do command only if not already paused.
    // but neither of these actually work for "pause",
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

        case 'volumeup':
            send_mp_cmd("volume +.3");

        case 'volumedown':
            send_mp_cmd("volume -.3");

        case 'close':
            send_mp_cmd("quit");
            while (`pidof mplayer`) {
                sleep(1);
            }
            break;

        case 'status':
            // Without pausing_keep_force, get_property pause will unpause
            // a paused video and thus will always return ANS_pause=no.
            // With it, it will return ANS_pause=yes if currently paused
            // and won't unpause it.
            //error_log('trying to get status\n', 0);
            $result = read_mp_val("pausing_keep_force get_property pause");
            //error_log('Got: ' . $result, 0);
            echo '<p>Read result: ' . $result;
            echo '<p>';
            //read_mp_val("get_property percent_pos");
    }

    //header('Location: controls.php?paused=' . $_GET['paused']);
    //exit();
}

?>

<!DOCTYPE html>
<title>Media Centre PRO 3000 Extreme Edition</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
<link rel="stylesheet" href="style.css">

<table class="controls">
<tr>
<td><a href="?action=back" class="unibutton">&#x23EA;</a></td>
<td>
<?php
if (isset($_GET['paused'])): ?>
    <a href="?action=play" class="unibutton">&#x23F5;</a>
<?php else: ?>
    <a href="?action=pause" class="unibutton">&#x23F8;</a>
<?php endif; ?>
</td>
<td><a href="?action=forward" class="unibutton">&#x23E9;</a>
</tr></table>

<p>
<a href="?action=close">Quit</a>

<p>
<a href="?action=status">Get status</a>


