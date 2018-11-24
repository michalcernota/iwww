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

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_uzivatele = $_GET["id"];
        $data = $conn->query("SELECT email FROM uzivatel WHERE id = $id_uzivatele")->fetch();
        if($data["email"] != $_POST["email"]) {
            $email_verify = $_POST["email"];
            $verify = $conn->query("SELECT id FROM uzivatel WHERE email = '".$email_verify."'")->fetch();
        }

        if(empty($verify)) {
            //https://www.youtube.com/watch?v=xLmJeIwOHdI
            $heslo = $_POST["heslo"];
            $hash = password_hash($heslo, PASSWORD_DEFAULT);

            if (empty($_POST["heslo"])) {
                $stmt = $conn->prepare("UPDATE uzivatel SET jmeno = :jmeno, prijmeni = :prijmeni,
 email = :email, role = :role WHERE id=:id");
                $stmt->bindParam(':id', $_GET["id"]);
                $stmt->bindParam(':jmeno', $_POST["jmeno"]);
                $stmt->bindParam(':prijmeni', $_POST["prijmeni"]);
                $stmt->bindParam(':email', $_POST["email"]);
                $stmt->bindParam(':role', $_POST["role"]);
                $stmt->execute();
            } else {
                $heslo = $_POST["heslo"];
                $hash = password_hash($heslo, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE uzivatel SET jmeno = :jmeno, prijmeni = :prijmeni,
 email = :email, heslo = :heslo, role = :role WHERE id=:id");

                $stmt->bindParam(':id', $_GET["id"]);
                $stmt->bindParam(':jmeno', $_POST["jmeno"]);
                $stmt->bindParam(':prijmeni', $_POST["prijmeni"]);
                $stmt->bindParam(':email', $_POST["email"]);
                $stmt->bindParam(':heslo',  $hash);
                $stmt->bindParam(':role', $_POST["role"]);
                $stmt->execute();
            }
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
            <h1>Úprava uživatelů</h1>
        </div>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_uzivatele = $_GET["id"];
        $data = $conn->query("SELECT * FROM uzivatel WHERE id = $id_uzivatele")->fetch();
        ?>

        <form method="post" class="form_align">
            <input type="text" name="jmeno" value="<?php echo $data["jmeno"] ?>">
            <input type="text" name="prijmeni" value="<?php echo $data["prijmeni"] ?>">
            <input type="email" name="email" value="<?php echo $data["email"] ?>">
            <input type="text" name="heslo" placeholder="Heslo">
            <input list="role_list" name="role" value="<?php echo $data["role"] ?>">
            <input type="submit" name="isSubmitted" value="Přidat">
        </form>

        <datalist id="role_list">
            <option value="U">
            <option value="A">
        </datalist>


        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT * FROM uzivatel")->fetchAll();
        echo '<table class="table_vypis">';

        echo '  
  <tr>
    <th>ID</th>
    <th>Jméno</th> 
    <th>Příjmení</th>
    <th>Email</th>
    <th>Den registrace</th>
    <th>Role</th>
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
    <td >' . $row["jmeno"] . '</td > 
    <td >' . $row["prijmeni"] . '</td > 
    <td >' . $row["email"] . '</td >
    <td >' . $row["den_registrace"] . '</td >
    <td >' . $row["role"] . '</td >
    <td class="td_upravit">
        <a class="update_btn" href="?dir=sprava_uzivatelu&page=update&id='.$row["id"].'">Update</a>
        <a class="delete_btn" href="?dir=sprava_uzivatelu&page=odebrat&id='.$row["id"].'">Delete</a>
    </td>
  </tr >';

        }
        echo '</table>';

        ?>

    </div>
</main>
