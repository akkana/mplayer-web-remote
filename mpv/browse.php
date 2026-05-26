<?php

$viddir = '/' . trim($_GET['dir'], '/');
// print("viddir =" . $viddir);

require 'commands.php';

// Find out what's currently playing, if anything
try {
    $filename = send_mpv_cmd('{ "command": ["get_property", "path"] }\n');
    $filename = basename($filename);
} catch (Exception $e) {
    $filename = null;
}

$title = 'Media Centre PRO 4000 Extreme Edition: ' . basename($viddir);

echo '<!DOCTYPE html>';
echo "<title>{$title}</title>";
echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">';
echo '<link rel="stylesheet" href="style.css">';
echo '</head>';
echo '<body>';
echo "<h1>{$title}</h1>";

if (! $viddir) {
    echo "Nothing found";
    return;
}

$files = array();
$dirs = array();

// glob() gives full pathnames
foreach (glob($viddir . '/*') as $f) {
    if (is_file($f)) {
        array_push($files, $f);
    } else {
        array_push($dirs, $f);
    }
}

asort($files);
asort($dirs);

echo '<ul>';

foreach ($dirs as $d) {
    $bn = basename($d);
    $encoded = urlencode(trim("$d"));
    echo "<li><a href=\"browse.php?dir={$encoded}\">{$bn}</a>";
}

foreach ($files as $f) {
    $bn = basename($f);
    $encoded = urlencode(trim("$f"));
    echo "<li><a href=\"play.php?file={$encoded}\">{$bn}</a>";
    if ($bn == $filename)
        echo " &nbsp; &nbsp; &larr; NOW PLAYING";
}

$p = explode('/', $viddir);
array_pop($p);
$s = urlencode(implode('/', $p));
echo "<li><a href=\"browse.php?dir={$s}\">Up One Level</a><br />";

echo "<li><a href=\"index.php\">Main Menu</a>";

?>

</ul>

<?php require 'footer.php'; ?>

</body>
</html>
