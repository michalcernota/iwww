<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Děkujeme za Vaši zerervaci.</h1>
        </div>
        <div>
            <form title="Přejete si uložit účtenku?">
                <input type="submit" name="ano" value="ANO">
                <input type="submit" name="ano" value="NE">
            </form>
        </div>
    </div>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ano'])) {
        // btnDelete
    } else {
        header(BASE_URL);
    }
}
?>