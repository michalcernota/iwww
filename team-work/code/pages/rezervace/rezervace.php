<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Rezervace</h1>
        </div>
        <form method="post" class="form_align">
            <input type="date" value="<?php echo date('Y-m-d')?>" name="datum">
            <input type="submit" name="ok" value="Zobrazit">
        </form>
<?php
if(empty($_SESSION)) {
    echo '<div class="alert_div">Pro rezervování míst musíte být <br> <a href="?page=login">přihlášen</a></div>';
} else {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(!isset($_POST["datum"])) {
        $datum = date('Y-m-d');
    } else {
        $datum = $_POST["datum"];
    }

    $data = $conn->query("SELECT promitani.zacatek, promitani.datum, film.nazev, promitani.id FROM promitani
JOIN film on promitani.id_film = film.id WHERE promitani.datum = '".$datum."' ORDER BY film.nazev")->fetchAll();

    echo '<table class="table_vypis">';
    echo '  
  <tr>
    <th>Film</th> 
    <th>Datum</th>
    <th></th>
  </tr>';

    $count = 1;
    foreach ($data as $row) {
        if($count%2 == 0) {
            echo '<tr class="background_gray">';
        } else {
            echo '<tr class="background_white">';
        }
        $count = $count+1;
        $date = strtotime($row["datum"]);
        $date = date('d/m/Y', $date);
        echo '
    <td >' . $row["nazev"] . '</td >
    <td >' . $date . '</td >
    <td ><a href="?dir=rezervace&page=rezervace_pocet&id='.$row["id"].'">' . $row["zacatek"] . '</a></td >  
  </tr >';

    }
    echo '</table>';
}?>

    </div>
</main>
