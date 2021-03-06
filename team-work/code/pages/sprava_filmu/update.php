<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["nazev"])) {
        $feedbackMessage = "Nebyl zadán název filmu.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["reziser"])) {
        $feedbackMessage = "Nebylo zadáno jméno režiséra.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["rok_vydani"])) {
        $feedbackMessage = "Nebyl zadán rok vydání.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_FILES["img"]["size"] == 0) {
            $stmt = $conn->prepare("UPDATE film SET nazev = :nazev, reziser = :reziser,
 rok_vydani = :rok_vydani, popisek_filmu = :popis WHERE id=:id");
            $stmt->bindParam(':id', $_GET["id"]);
            $stmt->bindParam(':nazev', $_POST["nazev"]);
            $stmt->bindParam(':reziser', $_POST["reziser"]);
            $stmt->bindParam(':rok_vydani', $_POST["rok_vydani"]);
            $stmt->bindParam(':popis', $_POST["popisek"]);
            $stmt->execute();
        } else {
            $data = file_get_contents($_FILES["img"]["tmp_name"]);
            $stmt = $conn->prepare("UPDATE film SET nazev = :nazev, reziser = :reziser,
 rok_vydani = :rok_vydani, obrazek = :obr, popisek_filmu = :popis WHERE id=:id");
            $stmt->bindParam(':id', $_GET["id"]);
            $stmt->bindParam(':nazev', $_POST["nazev"]);
            $stmt->bindParam(':reziser', $_POST["reziser"]);
            $stmt->bindParam(':rok_vydani', $_POST["rok_vydani"]);
            $stmt->bindParam(':obr',  $data);
            $stmt->bindParam(':popis', $_POST["popisek"]);
            $stmt->execute();
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
            <h1>Úprava filmu z nabídky</h1>
        </div>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $id_filmu = $_GET["id"];
        $data = $conn->query("SELECT * FROM film WHERE id = $id_filmu")->fetch();
        ?>

        <form method="post" class="form_align" enctype="multipart/form-data">
            <input type="text" name="nazev" value="<?php echo $data["nazev"] ?>">
            <input type="text" name="reziser" value="<?php echo $data["reziser"] ?>">
            <input type="text" name="rok_vydani" value="<?php echo $data["rok_vydani"] ?>"> <br>
            <?php echo '<img width="80px" src="data:image/jpeg;base64,' . base64_encode( $data['obrazek'] ) . '" />'; ?>
            <input type="file" name="img"> <br>
            <textarea name="popisek" cols="40" rows="4"><?php echo $data["popisek_filmu"] ?></textarea> <br>
            <input type="submit" name="isSubmitted" value="Uložit">
        </form>


        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT * FROM film")->fetchAll();

        echo '<table class="table_vypis">';
        echo '  
  <tr>
    <th>ID</th>
    <th>Název</th> 
    <th>Režisér</th>
    <th>Rok vydání</th>
  </tr>';

        $count = 0;
        foreach ($data as $row) {
            if($count%2 == 0) {
                echo '<tr class="background_gray">';
            } else {
                echo '<tr class="background_white">';
            }
            $count = $count + 1;
            echo '
    <td >' . $row["id"] . '</td >
    <td >' . $row["nazev"] . '</td > 
    <td >' . $row["reziser"] . '</td > 
    <td >' . $row["rok_vydani"] . '</td >
    <td class="td_upravit">
        <a class="update_btn" href="?page=sprava_filmu&action=update&id='.$row["id"].'">Update</a>
        <a class="delete_btn" href="?page=sprava_filmu&action=delete&id='.$row["id"].'">Delete</a>
    </td>
  </tr >';

        }
        echo '</table>';
        ?>

    </div>
</main>
