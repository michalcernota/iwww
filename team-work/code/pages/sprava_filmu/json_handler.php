<?php
include "../config.php";
//https://www.youtube.com/watch?v=I4SRqAS7J8U

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT id, nazev, reziser, rok_vydani, popisek_filmu FROM film");
$stmt->execute();

$json = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $json[] = $row;
}

$encode = json_encode($json);
//echo $encode;

//https://www.w3schools.com/php/php_file_create.asp
$filename = "json_filmy.json";
$file = fopen($filename, "w") or die ("Do souboru nelze zapisovat.");
fwrite($file, $encode);

//http://php.net/manual/en/function.readfile.php
if(file_exists($filename)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: public');
    header("Content-Transfer-Encoding: binary");
    readfile($filename, BASE_URL . 'json_filmy.json');
    exit;
}

fclose($file);