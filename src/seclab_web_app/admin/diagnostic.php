<?php
require_once("../config.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Diagnostic Réseau - Admin</title>
    <style>
        body { font-family: Arial; background:#f4f7f6; padding:20px; }
        .box { background:white; padding:20px; border-radius:8px; }
        .result { background:#222; color:#0f0; padding:15px; margin-top:20px; }
    </style>
</head>

<body>

<div class="box">

<h1>Diagnostic Serveur</h1>

<form method="POST">
    <input type="text" name="target" placeholder="/var/www/html">
    <button type="submit">Run</button>
</form>

<?php
if (isset($_POST['target'])) {

    $target = $_POST['target'];

    echo "<h3>Input:</h3>";
    echo htmlspecialchars($target);

    echo "<div class='result'>";

    // ⚠️ LAB VULNERABILITY (intentionally unsafe)
    $cmd = "/usr/bin/du -sh " . $target . " 2>&1";

    $output = shell_exec($cmd);

    echo htmlspecialchars($output ?: "No output");

    echo "</div>";
}
?>

</div>

</body>
</html>
