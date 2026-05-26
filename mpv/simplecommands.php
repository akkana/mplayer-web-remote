<?php

include 'commands.php';

if (isset($_GET['cmd']) && isset($_GET['val'])) {
    error_log('cmd = ' . $_GET['cmd'] . ' and val = ' . $_GET['val'], 0);
    if ($_GET['cmd'] == 'volume') {
        send_mpv_cmd('{ "command": ["set_property", "volume", '
                   . $_GET['val'] . '] }');
        echo "Set volume &rarr; " . $_GET['val'];
    }
}
?>
