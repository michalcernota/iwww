<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["jmeno"])) {
        $feedbackMessage = "Nebylo zadáno jméno uživatele.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["prijmeni"])) {
        $feedbackMessage = "Nebylo zadáno příjmení uživatele.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["email"])) {
        $feedbackMessage = "Nebyl email uživatele.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["role"])) {
        $feedbackMessage = "Nebyla zadána role uživatele.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["heslo"])) {
        $feedbackMessage = "Nebylo zadáno heslo uživatele.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email_verify = $_POST["email"];
        $verify = $conn->query("SELECT id FROM uzivatel WHERE email = '".$email_verify."'")->fetch();

        if(empty($verify)) {
            //https://www.youtube.com/watch?v=xLmJeIwOHdI
            $heslo = $_POST["heslo"];
            $hash = password_hash($heslo, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO uzivatel (jmeno, prijmeni, email, den_registrace, role, heslo)
        VALUES (:jmeno, :prijmeni, :email, NOW(), 'U', :heslo)");
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->bindParam(':jmeno', $_POST["jmeno"]);
            $stmt->bindParam(':prijmeni', $_POST["prijmeni"]);
            $stmt->bindParam(':heslo', $hash);
            $stmt->execute();
        } else {
            echo 'zadaný email již někdo používá';
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
            <h1>Správa vstupenek</h1>
        </div>

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
