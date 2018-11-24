<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
header(BASE_URL."?page=sprava_promitani&action=delete");
echo 'neco';
}


?>

<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Rezervace</h1>
        </div>

        <?php
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_GET["id"];

        $data = $conn->query("SELECT promitani.zacatek, promitani.datum, film.nazev,
promitani.id_film, promitani.cena_dospely, promitani.cena_dite FROM promitani
JOIN film on promitani.id_film = film.id WHERE promitani.id = ".$id."")->fetch();

        //echo $data["zacatek"] . '<br>';
        //echo $data["datum"] . '<br>';
        //echo $data["nazev"] . '<br>';
        //echo $data["cena_dite"];

echo '
    <form class="rezervace_pocet" method="get">
        <table>
            <tr>
                <th>Typ</th>
                <th>Cena</th>
                <th>Počet</th>
            </tr>
            <tr>
                <td>Dospělý</td>
                <td>'.$data["cena_dospely"].' Kč</td>
                <td>
                    <select name="dospely_pocet">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Dítě</td>
                <td>'.$data["cena_dite"].'  Kč</td>
                <td>
                    <select name="deti_pocet">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" value="OK">
    </form>
';

    echo "<div class='kino_div'>";
    for ($i = 0; $i < 10; $i++) {
        echo "<div class='rezervace_row_div'>";
        $ipom = $i + 1;
        echo '<div class="rezervace_rada_div">'.$ipom.'</div>';
        for ($j = 0; $j < 8; $j++) {
            $jpom = $j + 1;
            echo '<div class="rezervace_seat_div">'.$jpom.'</div>';
        }
        echo "</div>";
    }
    echo "</div>";
?>

    </div>
</main>