<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["nazev"])) {
        $feedbackMessage = "Nebylo zadán název filmu.";
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
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*
         https://www.youtube.com/watch?v=XfobCv4YBdk
         */
        $name = $_FILES["img"]["name"];
        $type = $_FILES["img"]["type"];
        $data = file_get_contents($_FILES["img"]["tmp_name"]); /*tady je prej chyba*/

        if(isset($_FILES["img"])){
            echo 'soubor nahrán';
            echo $name;
            echo $type;
        }

        if($_FILES['img']['size'] == 0) {
            echo $name;
            echo $type;
            echo $_FILES["img"]['name'];
            echo 'obrázek má velikost 0';
        }

        /*$stmt = $conn->prepare("INSERT INTO film (nazev, reziser, rok_vydani, obrazek)
    VALUES (:nazev, :reziser, :rok_vydani, :obr)");
        $stmt->bindParam(':nazev', $_POST["nazev"]);
        $stmt->bindParam(':reziser', $_POST["reziser"]);
        $stmt->bindParam(':rok_vydani', $_POST["rok_vydani"]);
        $stmt->bindParam(':obr',  $data);
        $stmt->execute();*/
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
            <h1>Správa filmů z nabídky</h1>
        </div>
        <form method="post" class="form_align" enctype="multipart/form-data">
            <input type="text" name="nazev" placeholder="Název filmu">
            <input type="text" name="reziser" placeholder="Jméno režiséra">
            <input type="text" name="rok_vydani" placeholder="Rok vydání">
            <input type="file" name="img">
            <input type="submit" name="isSubmitted" value="Přidat">
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
