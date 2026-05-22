<?php

if (! `pidof mplayer`) {
    shell_exec('rm /tmp/mplayer-fifo');
    header('Location: index.php');
    exit();
}

# No way to know if it's paused, if ...XXX
$paused = 0;

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'pause':
            shell_exec('echo "pause" >/tmp/mplayer-fifo');
            $paused = 1;
            break;

        case 'play':
            shell_exec('echo "pause" >/tmp/mplayer-fifo');
            break;

        case 'back':
            shell_exec('echo "pausing_keep seek -10" >/tmp/mplayer-fifo');
            break;

        case 'forward':
            shell_exec('echo "pausing_keep seek +10" >/tmp/mplayer-fifo');
            break;

        case 'close':
            shell_exec('echo "quit" >/tmp/mplayer-fifo');
            while (`pidof mplayer`) {
                sleep(1);
            }
            break;
    }

    header('Location: controls.php?paused=' . $_GET['paused']);
    exit();
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



