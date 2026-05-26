<?php

function send_mpv_cmd($cmd) {
    //error_log('sh_exec: ' . 'echo ' . $cmd . ' | socat - /tmp/mpvsocket', 0);
    $s = shell_exec("echo '" . $cmd . "' | socat - /tmp/mpvsocket");
    //error_log("Read: " . $s, 0);
    //error_log("Type: " . gettype($s), 0);
    $j = json_decode($s, $associative=true);
    //error_log("json_decode gives: " . print_r($j, true), 0);
    return $j['data'];
}

?>
