<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Děkujeme za Vaši rerervaci.</h1>
        </div>
        <div>
            <h2>Přejete si uložit účtenku?</h2>
            <a target="_blank" href="/IWWW_sem/team-work/code/pages/rezervace/rezervace_pdf.php">Ano</a>
            <a href="<?php BASE_URL ?>">Ne</a>
        </div>
        <?php print_r($_SESSION); ?>
    </div>
</main>


<?php
/*
if(!empty($_POST)) {
    if (isset($_POST['ano'])) {
        header("Location:" . BASE_URL . '?dir=rezervace&page=rezervace_pdf');
    } else {
        unset($_SESSION['sedadla']);
        unset($_SESSION['dospelych']);
        unset($_SESSION["deti"]);
        unset($_SESSION["reservations"]);
        unset($_SESSION["reservations_c"]);
        unset($_SESSION["reserved_seats"]);
        header("Location:" . BASE_URL);
    }
}
*/
?>