<?php

try {
    $config = parse_ini_file(getcwd() . '/mp-remote.ini');
    //echo "Read from config file:";
    //print_r($config);

    $mediadir = $config['mediadir'];
} catch (Exception $e) {
    $mediadir = [];
}

$title = 'Media Centre PRO 4000 Extreme Edition';   // silly title
?>

<!DOCTYPE html>
<head>
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?php echo $title; ?></h1>

<ul>
<?php

if ($mediadir) {
    foreach (glob($config['mediadir'] . '/*') as $f) {
        echo '<li><a href="browse.php?dir=' . $f . '">' . basename($f) . '</a>';
    }
} else {
    echo "You must specify mediadir = &lt;some path&gt; in mp-remote.ini";
}
?>

</ul>
