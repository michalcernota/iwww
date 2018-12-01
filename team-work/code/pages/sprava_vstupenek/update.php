<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["row"])) {
        $feedbackMessage = "Nebyla zadána řada.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["seat"])) {
        $feedbackMessage = "Nebylo zadáno sedadlo.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["user"])) {
        $feedbackMessage = "Nebyl email uživatele.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["id_promitani"])) {
        $feedbackMessage = "Nebylo zadáno id promítání.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $conn->query("SELECT id FROM uzivatel 
WHERE email = '".$_POST["user"]."'")->fetch();

        $stmt = $conn->prepare("UPDATE vstupenka SET rada = :rada,
sedadlo = :sedadlo, id_promitani = :id_promitani, id_uzivatel = :id_uzivatel WHERE id=:id");
        $stmt->bindParam(':rada', $_POST["row"]);
        $stmt->bindParam(':sedadlo', $_POST["seat"]);
        $stmt->bindParam(':id_promitani', $_POST["id_promitani"]);
        $stmt->bindParam(':id_uzivatel', $id["id"]);
        $stmt->bindParam(':id', $_GET["id"]);
        $stmt->execute();
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
            <h1>Správa vstupenek</h1>
        </div>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT promitani.id, promitani.zacatek, promitani.datum, 
film.nazev FROM promitani JOIN film on promitani.id_film = film.id")->fetchAll();
        echo '<table class="table_vypis">';
        echo '  
  <tr>
    <th>ID</th>
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
  </tr >';
        }
        echo '</table>';
        ?>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_vstupenky = $_GET["id"];
        $data = $conn->query("SELECT vstupenka.id, vstupenka.rada, vstupenka.sedadlo,
        vstupenka.id_promitani, uzivatel.email, promitani.datum, promitani.zacatek, film.nazev FROM vstupenka 
        JOIN uzivatel on vstupenka.id_uzivatel = uzivatel.id
        JOIN promitani on vstupenka.id_promitani = promitani.id
        JOIN film on promitani.id_film = film.id WHERE vstupenka.id = ".$id_vstupenky."")->fetch();
        ?>

        <form method="post" class="form_align">
            <input list="users" name="user" title="Uživatel" value="<?php echo $data["email"] ?>">
            <input type="text" name="row" title="Řada" value="<?php echo $data["rada"] ?>">
            <input type="text" name="seat" title="Sedadlo" value="<?php echo $data["sedadlo"] ?>">
            <input type="text" name="id_promitani" title="ID promítání" value="<?php echo $data["id_promitani"] ?>">
            <input type="submit" name="ok" value="Uložit">
        </form>

        <datalist id="users">
            <?php
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $mails = $conn->query("SELECT email FROM uzivatel")->fetchAll();
            foreach ($mails as $row) {
                echo '<option value="'. $row["email"] .'">';
            }
            ?>
        </datalist>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT vstupenka.id, vstupenka.rada, vstupenka.sedadlo,
        uzivatel.email, promitani.datum, promitani.zacatek, film.nazev FROM vstupenka 
        JOIN uzivatel on vstupenka.id_uzivatel = uzivatel.id
        JOIN promitani on vstupenka.id_promitani = promitani.id
        JOIN film on promitani.id_film = film.id")->fetchAll();
        echo '<table class="table_vypis">';

        echo '  
  <tr>
    <th>ID</th>
    <th>Řada</th> 
    <th>Sedadlo</th>
    <th>Uživatel</th>
    <th>Datum promítání</th>
    <th>Začátek promítání</th>
    <th>Film</th>
    ' . '
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
    <td >' . $row["rada"] . '</td > 
    <td >' . $row["sedadlo"] . '</td > 
    <td >' . $row["email"] . '</td >
    <td >' . $row["datum"] . '</td >
    <td >' . $row["zacatek"] . '</td >
    <td class="td_wider">' . $row["nazev"] . '</td >
    <td class="td_upravit">
        <a class="update_btn" href="?dir=sprava_vstupenek&page=update&id='.$row["id"].'">Update</a>
        <a class="delete_btn" href="?dir=sprava_vstupenek&page=odebrat&id='.$row["id"].'">Delete</a>
    </td>
  </tr >';
        }

        echo '</table>';
        ?>

    </div>
</main>
