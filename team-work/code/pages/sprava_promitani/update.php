<?php
$errorFeedbacks = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["film"])) {
        $feedbackMessage = "Nebyl zadán název filmu.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["datum"])) {
        $feedbackMessage = "Nebyl zadán datum promítání.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["zacatek"])) {
        $feedbackMessage = "Nebyl zadán začátek promítání.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE promitani SET zacatek = :zacatek,
datum = :datum, cena_dospely = :cena, cena_dite = :cena_dite ,id_film = :film WHERE id=:id");
        $stmt->bindParam(':id', $_GET["id"]);
        $stmt->bindParam(':zacatek', $_POST["zacatek"]);
        $stmt->bindParam(':datum', $_POST["datum"]);
        $stmt->bindParam(':cena', $_POST["cena"]);
        $stmt->bindParam(':cena_dite', $_POST["cena_dite"]);
        $id_film = $conn->query("SELECT id FROM film WHERE nazev ='".$_POST["film"]."' ")->fetch();
        $stmt->bindParam(':film', $id_film["id"]);
        $stmt->execute();
    } else {
        foreach ($errorFeedbacks as $errorFeedback) {
            echo $errorFeedback . "<br>";
        }
    }
}
?>

<?php
if (!empty($errorFeedbacks)) {
    echo "Form contains following errors:<br>";
    foreach ($errorFeedbacks as $errorFeedback) {
        echo $errorFeedback . "<br>";
    }
}
?>

<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Úprava promítání filmů</h1>
        </div>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $id_promitani = $_GET["id"];
        $data = $conn->query("SELECT promitani.id, promitani.zacatek, promitani.datum, 
promitani.cena_dospely, promitani.cena_dite, film.nazev FROM promitani
JOIN film on promitani.id_film = film.id WHERE promitani.id = ".$id_promitani."")->fetch();
        ?>

        <form method="post" class="form_align">
            <input list="filmy" title="Název" name="film" value="<?php echo $data["nazev"] ?>">
            <input type="time" title="Začátek promítání" value="<?php echo $data["zacatek"] ?>" name="zacatek">
            <input type="date" title="Datum promítání" value="<?php echo $data["datum"] ?>" name="datum"> <br>
            <label for="cena">Cena dospělého: </label>
            <input type="text" id="cena" value="<?php echo $data["cena_dospely"] ?>" name="cena"><br>
            <label for="cena_dite">Cena dítěte: </label>
            <input type="text" id="cena_dite" value="<?php echo $data["cena_dite"] ?>" name="cena_dite"> <br>
            <input type="submit" name="ok" value="Uložit">
        </form>


        <datalist id="filmy">
            <?php
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $data = $conn->query("SELECT nazev FROM film")->fetchAll();
            foreach ($data as $row) {
                echo '<option value="'. $row["nazev"] .'">';
            }
            ?>
        </datalist>


        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT promitani.id, promitani.zacatek, promitani.datum, 
promitani.cena_dospely, promitani.cena_dite, film.nazev FROM promitani
JOIN film on promitani.id_film = film.id")->fetchAll();
        echo '<table class="table_vypis">';

        echo '  
  <tr>
    <th>ID</th>
    <th>Začátek promítání</th> 
    <th>Datum promítání</th>
    <th>Cena</th>
    <th>Cena za dítě</th>
    <th>Název filmu</th>
  </tr>';

        $count = 1;
        foreach ($data as $row) {
            if($count%2 == 0) {
                echo '<tr class="background_gray">';
            } else {
                echo '<tr class="background_white">';
            }
            $count = $count+1;
            echo '
    <td >' . $row["id"] . '</td >
    <td >' . $row["zacatek"] . '</td > 
    <td >' . $row["datum"] . '</td > 
    <td >' . $row["cena_dospely"] . '</td >
    <td >' . $row["cena_dite"] . '</td > 
    <td >' . $row["nazev"] . '</td >
    <td class="td_upravit">
        <a class="update_btn" href="?page=sprava_promitani&action=update&id='.$row["id"].'">Update</a>
        <a class="delete_btn" href="?page=sprava_promitani&action=delete&id='.$row["id"].'">Delete</a>
    </td>
  </tr >';

        }
        echo '</table>';
        ?>

    </div>
</main>
