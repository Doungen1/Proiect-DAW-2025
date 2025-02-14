<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../inc/essentials.php');
require('../inc/db_config.php');
adminLogin();

    // Adauga feature in tabelul features 
    if(isset($_POST['add_feature'])){
        $frm_data = filteration($_POST); // Se filtreaza datele din POST 

        $q = "INSERT INTO `features`(`name`) VALUES (?)"; // Se insereaza feature-ul in tabelul features 
        $values = [$frm_data['name']]; // Se atribuie variabilei values valoarea din frm_data['name'] 
        $res = insert ($q, $values, 's'); // Se executa functia insert din fisierul essentials.php
        echo $res; // Afiseaza rezultatul functiei insert din fisierul essentials.php
    };

    // Afiseaza feature-urile din tabelul features 
    if(isset($_POST['get_features'])){
        $res = selectAll('features'); // Selecteaza toate feature-urile din tabelul features
        $i = 1; // Variabila i este folosita pentru a numara feature-urile din tabelul features

        while($row = mysqli_fetch_assoc($res)){ // Loop prin feature-urile din tabelul features
            echo <<<data
                <tr>
                    <td>$i</td>
                    <td>$row[name]</td>
                    <td>
                        <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i></i>Delete
                        </button>
                    </td>
                </tr>
            data; // Afiseaza feature-urile din tabelul features
            $i++; // Incrementeaza variabila i
        }
    };
    
    // Sterge feature-ul din tabelul features in functie de id
    if(isset($_POST['rem_feature'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_feature']]; // Se atribuie variabilei values valoarea din frm_data['rem_feature']

        $check_q = select("SELECT * FROM `room_features` WHERE `features_id`=?", $frm_data['rem_feature'], 'i'); // Se selecteaza feature-ul din tabelul room_features in functie de features_id
        if(mysqli_num_rows($check_q) == 0){ // Daca numarul de randuri din tabelul room_features in functie de features_id este 0, atunci se executa urmatorul cod
            $q = "DELETE FROM `features` WHERE `id`=?"; // Se sterge feature-ul din tabelul features in functie de id
            $res = delete($q, $values, "i"); // Se executa functia delete din fisierul essentials.php
            echo $res; 
        }
        else{
            echo 0;
        }

        $q = "DELETE FROM `features` WHERE `id`=?";
        $res = delete($q, $values, "i");
        echo $res;
    };

    // Adauga facility in tabelul facilities 
    if(isset($_POST['add_facility'])){
        $frm_data = filteration($_POST);
        
        $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER); // Se incarca imaginea in folderul FACILITIES_FOLDER (incarca imaginea in folderul facilities din folderul img din folderul admin)

        if($img_r == "Invalid Image Format"){
            echo $img_r;
        }
        else if($img_r == "Image size should be less than 5MB"){
            echo $img_r;
        }
        else if($img_r == "Image cannot be uploaded"){
            echo $img_r;
        }
        else{
            $q = "INSERT INTO `facilities`(`icon`, `name`, `description`) VALUES (?,?,?)"; // Se insereaza facility-ul in tabelul facilities
            $values = [$img_r, $frm_data['name'], $frm_data['desc']]; // Se atribuie variabilei values valorile din frm_data['name'] si frm_data['desc']
            $res = insert ($q, $values, "sss"); // Se executa functia insert din fisierul essentials.php
            echo $res; 
        }


    };

    // Afiseaza facility-urile din tabelul facilities
    if(isset($_POST['get_facilities'])){
        $res = selectAll('facilities'); // Selecteaza toate facility-urile din tabelul facilities 
        $i = 1; // Variabila i este folosita pentru a numara facility-urile din tabelul facilities
        $path = FACILITIES_IMG_PATH; // Path-ul imaginilor din tabelul facilities

        while($row = mysqli_fetch_assoc($res)){ // Loop prin facility-urile din tabelul facilities
            echo <<<data
                <tr class="align-middle">
                    <td>$i</td>
                    <td><img src="$path$row[icon]" width ="30px"></td>
                    <td>$row[name]</td>
                    <td>$row[description]</td>
                    <td>
                        <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i></i>Delete
                        </button>
                    </td>
                </tr>
            data; // Afiseaza facility-urile din tabelul facilities
            $i++; // Incrementeaza variabila i
        }
    };

    // Sterge facility-ul din tabelul facilities in functie de id
    if(isset($_POST['rem_facility'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_facility']];

        $check_q = select("SELECT * FROM `room_facilities` WHERE `facilities_id`=?", $frm_data['rem_facility'], 'i'); // Se selecteaza facility-ul din tabelul room_facilities in functie de facilities_id
        if(mysqli_num_rows($check_q) == 0){ // Daca numarul de randuri din tabelul room_facilities in functie de facilities_id este 0, atunci se executa urmatorul cod
            $q = "DELETE FROM `facilities` WHERE `id`=?"; // Se sterge facility-ul din tabelul facilities in functie de id
            $res = delete($q, $values, "i");
            echo $res;
        }
        else{
            echo 0; 
        }
        
        $pre_q = "SELECT * FROM `facilities` WHERE `id`=?"; // Se selecteaza facility-ul din tabelul facilities in functie de id
        $res = select($pre_q, $values, "i"); // Se executa functia select din fisierul essentials.php
        $img = mysqli_fetch_assoc($res); // Se atribuie variabilei img rezultatul functiei mysqli_fetch_assoc (rezultatul functiei mysqli_fetch_assoc este un array cu datele imaginii din tabelul facilities)

        if(deleteImage($img['icon'], FACILITIES_FOLDER)){ // Daca functia deleteImage din fisierul essentials.php returneaza true, atunci se executa urmatorul cod

            $q = "DELETE FROM `facilities` WHERE `id`=?"; // Se sterge facility-ul din tabelul facilities in functie de id
            $res = delete($q, $values, "i"); // Se executa functia delete din fisierul essentials.php
            echo $res;
        }
        else{
            echo 0;
        }

    };
?>