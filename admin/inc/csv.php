<?php

// Conectare la baza de date
require "db_config.php";

$csvFile = "693381926.csv";

// Deschideți fișierul CSV pentru citire
$file = fopen($csvFile, "r");

if ($file) {
    // Ignorați prima linie (antetul)
    fgetcsv($file);

    while (($data = fgetcsv($file, 1000, ",")) !== false) {
        // Extrageți datele din fișierul CSV
        $date = mysqli_real_escape_string($con, $data[0]);
        $numberOfReQ = mysqli_real_escape_string($con, $data[1]);
        $averageScore = mysqli_real_escape_string($con, $data[2]);

        // Inserați datele în baza de date
        $insertQuery = "INSERT INTO excel (`Date`, `Number of ReQ`, `Average Score`) VALUES ('$date', '$numberOfReQ', '$averageScore')";
        
        if (mysqli_query($con, $insertQuery)) {
            echo "Datele au fost importate cu succes!";
        } else {
            echo "Eroare la importul datelor: " . mysqli_error($con);
        }
    }

    // Închideți fișierul CSV
    fclose($file);
} else {
    echo "Eroare la deschiderea fișierului CSV.";
}

// Închideți conexiunea la baza de date
mysqli_close($con);
?>