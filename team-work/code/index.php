<?php
ob_start();                 // output buffering
session_start();            // spusteni session
include "pages/config.php"; // include configu
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./css/css.css">
    <title>Kino Vlašim</title>
</head>
<body>
<header>
    <div>
        <nav>
            <a href="<?=BASE_URL ?>">Hlavní stránka</a>
            <a href="<?=BASE_URL . "?page=informace" ?>">Informace</a>
            <a href="<?=BASE_URL . "?page=program" ?>">Program</a>
            <a href="<?=BASE_URL . "?dir=rezervace&page=rezervace" ?>">Rezervace</a>
            <a href="#">Hodnocení</a>

            <?php if(empty($_SESSION["user_id"])) { ?>
                    <a href="<?=BASE_URL . "?page=login" ?>">Login</a>
            <?php } else { ?>
                    <a href="<?=BASE_URL . "?page=logout" ?>">Logout</a>
            <?php } ?>

            <?php if($_SESSION["user_role"] == "A") { ?>
                <a href="<?=BASE_URL . "?dir=sprava_filmu&page=pridani" ?>">Filmy</a>
                <a href="<?=BASE_URL . "?dir=sprava_promitani&page=sprava_promitani" ?>">Promítání</a>
                <a href="<?=BASE_URL . "?dir=sprava_uzivatelu&page=pridani" ?>">Uživatelé</a>
                <a href="<?=BASE_URL . "?dir=sprava_vstupenek&page=zobrazeni" ?>">Vstupenky</a>
            <?php } ?>

        </nav>
    </div>
</header>

<?php
if(isset($_GET["dir"])) {
    $file = "./pages/" . $_GET["dir"] . "/" . $_GET["page"] . ".php";
    if(file_exists($file)) {
        include $file;
    } else {
        include "pages/default.php";
    }
} else {
    $file = "./pages/" . $_GET["page"] . ".php";
    if(file_exists($file)) {
        include $file;
    } else {
        include "pages/default.php";
    }
}
?>

<footer>
    <div class="full-width-wrapper">
        <div class="flex-wrap">
            <section>
                <h4>Kino</h4>
                <ul>
                    <li><a href="#">Informace</a></li>
                    <li><a href="#">Reference</a></li>
                    <li><a href="#">Přihlášení</a></li>
                </ul>
            </section>

            <section>
                <h4>Odkazy</h4>
                <ul>
                    <li><a target="_blank" href="https://www.csfd.cz/">ČSFD</a></li>
                    <li><a target="_blank" href="https://www.upce.cz/">UPCE</a></li>
                    <li><a target="_blank" href="https://www.facebook.com/">Facebook</a></li>
                </ul>
            </section>

            <section>
                <h4>Kontakt</h4>
                <address>
                    Komenského 39, 258 01 Vlašim<br>
                    Česká republika <br>
                    +420 723 622 967 <br>
                    Email: <a href="mailto:st52494@student.upce.cz">
                    st52494@student.upce.cz</a> <br>
                </address>
            </section>
        </div>
    </div>
</footer>

</body>
</html>