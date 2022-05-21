<?php
$pdo = new PDO('mysql:x;port=3306;dbname=y', 'z', '!');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
