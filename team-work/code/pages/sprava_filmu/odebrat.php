<?php

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("DELETE FROM promitani WHERE id_film = :id");
$stmt->bindParam(":id", $_GET["id"]);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM film WHERE id = :id");
$stmt->bindParam(":id", $_GET["id"]);
$stmt->execute();
?>

<?php
include "pridani.php";
?>
