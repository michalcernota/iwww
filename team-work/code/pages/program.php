<main>
    <div class="align_center">
        <div class="margin_top">
            <h1>Právě v našem kině</h1>
        </div>

        <?php

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query("SELECT * FROM film")->fetchAll();
        foreach ($data as $row) {
            echo '<div class="inline">
                    <div class="program_align">
                        <div class="program_img_div">';
                            echo '<img alt="' . $row["nazev"] . '" class="program_img" src="data:image/jpeg;base64,' . base64_encode($row['obrazek']) . '" />';
                        echo '</div>
                        <div class="program_popis_div">
                            <ul class="program_list">';
                                echo '<li><h3>'.$row["nazev"].'</h3></li>';
                                echo '<li>Režie: '.$row["reziser"].'</li>';
                                echo '<li>Rok vydání: '.$row["rok_vydani"].'</li>';
                                echo '<li>'.$row["popisek_filmu"].'</li>
                            </ul>
                        </div>
                    </div>
                </div>';
        }
        ?>

        </div>
</main>