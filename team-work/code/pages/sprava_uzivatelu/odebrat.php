<?php

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("DELETE FROM uzivatel WHERE id = :id");
$stmt->bindParam(":id", $_GET["id"]);
$stmt->execute();
?>

<?php
include "pridani.php";
?>
