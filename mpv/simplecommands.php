<?php

// A shim that JS can call via AJAX
// simplecommands.php?cmd=foo&val=bar

include 'commands.php';

if (isset($_GET['property'])) {

    if (isset($_GET['val'])) {
        error_log('cmd = ' . $_GET['property'] . ' and val = ' . $_GET['val'], 0);
        send_mpv_cmd('{ "command": ["set_property", "' . $_GET['property']
                   . '", '
                   . $_GET['val'] . '] }');
        echo 'Set ' . $_GET['property'] . ' &rarr; ' . $_GET['val'];
    } else {
        error_log('cmd = ' . $_GET['property'], 0);
        $val = send_mpv_cmd('{ "command": ["get_property", "'
                          . $_GET['property'] . '" ] }');
        echo $val;
    }

}
?>
