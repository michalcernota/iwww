<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Login</h1>
        </div>
            <form method="post" class="login_form">
                <input type="email" name="loginMail" placeholder="E-mail"> <br>
                <input type="password" name="loginPassword" placeholder="Heslo"> <br>
                <input type="submit" name="ok" value="Přihlásit">
            </form>
        <a href="<?=BASE_URL . "?page=registrace" ?>"">Registrace</a>
    </div>
</main>
<?php
include "config.php";

if (!empty($_POST) && !empty($_POST["loginMail"]) && !empty($_POST["loginPassword"])) {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id, email, role FROM uzivatel WHERE email= :email and heslo = :password");
    $stmt->bindParam(':email', $_POST["loginMail"]);
    $stmt->bindParam(':password', $_POST["loginPassword"]);
    $stmt->execute();

    $user = $stmt->fetch();
    if (!$user) {
        echo "user not found";
    } else {
        echo "you are logged in. Your ID is: " . $user["id"];
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["username"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_role"] = $user["role"];
        header("Location:" . BASE_URL);
    }
} else if (!empty($_POST)) {
    echo "Username and password are required";
}
?>