<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Rezervace</h1>
        </div>

<?php
        //print_r($_SESSION);
        $count = $_SESSION['dospelych'] + $_SESSION['deti'];

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_promitani = $_SESSION['film_id'];
        $zabrana_sedadla = $conn->query("SELECT rada, sedadlo FROM vstupenka 
WHERE id_promitani = $id_promitani")->fetchAll();

        echo 'Volné místo: <button class="volne_sedadlo">1</button> <br> Obsazené místo: <button class="zabrane_sedadlo">1</button> <br>
Vybrané místo: <button class="vybrane_sedadlo">1</button>';

        echo '
        <form method="post" action="">
            <input name="reservations" type="hidden" id="reservations">
            <input name="reservations_c" type="hidden" id="reservations_c">
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
                $shoda = false;
                foreach ($zabrana_sedadla as $row) {
                    if($row["rada"] == $ipom && $row["sedadlo"] == $jpom) {
                        $shoda = true;
                    }
                }

                if($shoda) {
                    echo "<button disabled class='zabrane_sedadlo' '>".$jpom." </button>";
                } else {
                    echo "<button class='volne_sedadlo' onclick='markReservation(this, $ipom, $jpom, $count)'>".$jpom." </button>";
                }

            }
            echo "</div>";
        }
        echo "</div>";
?>

    </div>
</main>

    <script>
        var reservations = [];
        function markReservation($element, $x, $y, $count) {
            var r = {"x" :$x, "y" : $y};
            var notIncluded = true;

            for( var i = 0; i < reservations.length; i++){
                if (reservations[i].x === $x && reservations[i].y === $y) {
                    reservations.splice(i, 1);
                    $element.style.backgroundColor = "#ffcc00";
                    notIncluded = false;
                    document.getElementById("reservations").value = JSON.stringify(reservations);
                    document.getElementById("reservations_c").value = reservations.length;
                }
            }

            if($count > reservations.length && notIncluded) {
                $element.style.backgroundColor = "green";
                reservations.push(r);
                document.getElementById("reservations").value = JSON.stringify(reservations);
                document.getElementById("reservations_c").value = reservations.length;
            }

            console.log(reservations);
        }
    </script>

<?php
if(!empty($_POST)) {
    if($_POST['reservations_c'] < $count) {
        echo 'málo vybraných míst';
    } else {
        $reservations_array = array();
        $output = $_POST["reservations"];
        $reservations_array = json_decode($output);

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        for($i = 0; $i < count($reservations_array); $i++) {
            //echo $reservations_array[$i]->x . ' ' . $reservations_array[$i]->y . '<br>';

            $stmt = $conn->prepare("INSERT INTO vstupenka (rada, sedadlo, id_promitani, id_uzivatel)
    VALUES (:rada, :sedadlo, :id_promitani, :id_uzivatel)");
            $stmt->bindParam(':rada', $reservations_array[$i]->x);
            $stmt->bindParam(':sedadlo', $reservations_array[$i]->y);
            $stmt->bindParam(':id_promitani', $_SESSION["film_id"]);
            $stmt->bindParam(':id_uzivatel', $_SESSION["user_id"]);
            $stmt->execute();
        }

        //$_SESSION['reserved_seats'] = $reservations_array;
        header("Location:" . BASE_URL . '?dir=rezervace&page=rezervace_konec');
    }
}
?>