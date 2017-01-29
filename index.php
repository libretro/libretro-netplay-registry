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
    $contents = '<table class="table"><thead><tr>';
    $contents .= '<th>Username</th><th>Game</th><th>Core</th><th>Connectable</th></thead><tbody>';
    // Loop through every row.
    foreach ($entries as $entry) {
        $connectable = $entry['connectable'] ? '&checkmark;' : '&cross;';
        $contents .= '<tr>';
        $contents .= "<th>{$entry['username']}</th>";
        $contents .= "<td>{$entry['gamename']}</td>";
        $contents .= "<td>{$entry['corename']}</td>";
        $contents .= "<td>{$connectable}</td>";
        $contents .= '</tr>';
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
<script
    src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
    integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
    crossorigin="anonymous"></script>
<link rel="shortcut icon" href="/media/icon_dark.ico"/>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">RetroArch Lobby Browser</h1>
            <p class="lead">Currently available netplay rooms in RetroArch</p>
        </div>
        <?php
            echo $contents;
        ?>
    </div>
</body>
</html>
