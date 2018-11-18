<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Přidání promítacího času</h1>
        </div>
        <form method="post" class="form_align">
            <input list="filmy" name="film" placeholder="Výběr filmů">
            <input type="time" value="12:00" name="zacatek">
            <input type="date" value="<?php echo date('Y-m-d'); ?>" name="datum">
            <input type="submit" name="ok" value="Přidat">
        </form>

        <!--
        https://stackoverflow.com/questions/6982692/html5-input-type-date-default-value-to-today
        https://www.w3schools.com/tags/att_input_list.asp
        -->

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

                $stmt = $conn->prepare("INSERT INTO promitani (zacatek, datum, id_film)
    VALUES (:zacatek, :datum, (SELECT id FROM film where nazev = :film))");
                $stmt->bindParam(':zacatek', $_POST["zacatek"]);
                $stmt->bindParam(':datum', $_POST["datum"]);
                $stmt->bindParam(':film', $_POST["film"]);
                $stmt->execute();
            } else {
                foreach ($errorFeedbacks as $errorFeedback) {
                    echo $errorFeedback . "<br>";
                }
            }
        }
        ?>


        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT promitani.id, promitani.zacatek, promitani.datum, film.nazev FROM promitani
JOIN film on promitani.id_film = film.id")->fetchAll();
        echo '<table class="table_vypis">';

        echo '  
  <tr>
    <th>ID</th>
    <th>Začátek promítání</th> 
    <th>Datum promítání</th>
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
