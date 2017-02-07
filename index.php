<?php
/**
 * Web-based RetroArch Netplay Room Browser.
 */

use RobLoach\LibretroNetplayRegistry\Registry;

// Autoload the required classes.
require_once __DIR__ . '/src/autoload.php';

// Load the entries from the registry.
$registry = new Registry();
$entries = $registry->selectAll();

// Construct the lobby contents.
$contents = '';
if (empty($entries)) {
    $contents = '<div class="alert alert-info" role="alert">There are currently no lobbies open.</div>';
} else {
    // Table header.
    $contents = '<table class="table"><thead><tr><th>Username</th><th>Game</th><th>Core</th></thead><tbody>';
    // Loop through every row.
    foreach ($entries as $entry) {
        $contents .= "<tr><th>{$entry['username']}</th><td>{$entry['gamename']}</td><td>{$entry['corename']}</td></tr>";
    }
    // Table footer.
    $contents .= '</tbody></table>';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RetroArch Lobby Browser</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link
    rel="stylesheet"
    href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
    integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ"
    crossorigin="anonymous">
<link rel="icon" href="https://www.libretro.com/wp-content/uploads/2016/01/ic_launcher.png" sizes="32x32"/>
<link rel="icon" href="https://www.libretro.com/wp-content/uploads/2016/01/ic_launcher.png" sizes="192x192"/>
<link rel="apple-touch-icon-precomposed" href="https://www.libretro.com/wp-content/uploads/2016/01/ic_launcher.png"/>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">RetroArch Lobby Browser</h1>
            <p class="lead">Currently available netplay rooms in <a href="http://libretro.com">RetroArch</a>.</p>
            <hr class="my-4">
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="https://www.libretro.com/" role="button">RetroArch</a>
                <a class="btn btn-info btn-lg" href="https://www.youtube.com/watch?v=oh7hhoOBg54" role="button">How to Join</a>
                <a class="btn btn-info btn-lg" href="https://www.youtube.com/watch?v=n6aF0wNcm7E" role="button">How to Host</a>
            </p>
        </div>
        <?php
            echo $contents;
        ?>
    </div>
    <script
        src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
        crossorigin="anonymous"></script>
</body>
</html>
