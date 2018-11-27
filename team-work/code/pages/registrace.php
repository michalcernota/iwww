<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["jmeno"])) {
        $feedbackMessage = "Nebylo zadáno jméno.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["prijmeni"])) {
        $feedbackMessage = "Nebylo zadáno příjmení.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["heslo"])) {
        $feedbackMessage = "Nebylo zadáno heslo.";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["email"])) {
        $feedbackMessage = "Nebyl zadán email.";
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
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
    echo $successFeedback;
}
?>

<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Registrace</h1>
        </div>
        <form method="post">
            <input type="text" name="jmeno" placeholder="Jméno">
            <input type="text" name="prijmeni" placeholder="Příjmení">
            <input type="email" name="email" placeholder="E-mail"/>
            <input type="password" name="heslo" placeholder="Heslo">
            <input type="submit" name="isSubmitted" value="OK">
        </form>
    </div>
</main>
