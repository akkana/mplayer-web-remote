<?php

try {
    if (file_exists(getcwd() . '/mp-remote.ini'))
        $config = parse_ini_file(getcwd() . '/.config/mp-remote.ini');
    else if (file_exists(getenv('HOME') . '/.config/mp-remote.ini'))
        $config = parse_ini_file(getenv('HOME') . '/.config//mp-remote.ini');
    //echo "Read from config file:";
    //print_r($config);
    $mediadir = $config['mediadir'];

} catch (Exception $e) {
    $mediadir = [];
}

error_log("Media Dir: " . $mediadir, 0);

include "header.php";
?>

<ul>
<?php
if ($mediadir) {
    foreach (glob($config['mediadir'] . '/*') as $f) {
        echo '<p><a href="browse.php?dir=' . $f . '">' . basename($f)
           . '</a></p>' . PHP_EOL;
    }
} else {
    echo "You must specify mediadir = &lt;some path&gt; in mp-remote.ini";
}

echo '</ul>' . PHP_EOL . PHP_EOL;

include 'footer.php';
?>

