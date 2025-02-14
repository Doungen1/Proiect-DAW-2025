


<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
require('../inc/essentials.php');
require('../inc/db_config.php');
require('../../fpdf/fpdf.php');



adminLogin();

if(isset($_POST['add_room'])){ //adauga camera in baza de date
    $features = filteration(json_decode($_POST['features'])); // preia datele din POST si le filtreaza (functia filteration este definita in fisierul essentials.php)
    $facilities = filteration(json_decode($_POST['facilities'])); 
    
    $frm_data = filteration($_POST); 
    $flag = 0; //flag-ul este folosit pentru a verifica daca query-urile au fost executate cu succes

    $q1 = "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?, ?, ?, ?, ?, ?, ?)"; //query pentru inserarea camerei in baza de date
    $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc']]; //valori pentru query-ul de mai sus
    
    if(insert($q1, $values, 'siiiiis')){ //executa query-ul
        $flag = 1; //daca query-ul a fost executat cu succes, flag-ul devine 1
    }

    $room_id = mysqli_insert_id($con); //preia id-ul camerei care a fost inserata in baza de date (id-ul este auto incrementat)

    $q2 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?, ?)"; //query pentru inserarea dotarilor camerei in baza de date

    if($stmt = mysqli_prepare($con, $q2)){ //pregateste query-ul pentru a fi executat
        foreach($facilities as $f){ //pentru fiecare dotare din array-ul cu dotari
            mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f); //leaga parametrii de query
            mysqli_stmt_execute($stmt); //executa query-ul
        }
        mysqli_stmt_close($stmt); //inchide query-ul
    }
    else{
        $flag = 0; //daca query-ul nu a fost executat cu succes, flag-ul devine 0 
        die('query failed - insert room facilities'); 
    }
    $q3 = "INSERT INTO `room_features` (`room_id`, `features_id`) VALUES (?, ?)"; //query pentru inserarea facilitatilor camerei in baza de date

    if($stmt = mysqli_prepare($con, $q3)){ //pregateste query-ul pentru a fi executat
        foreach($features as $f){ //pentru fiecare facilitate din array-ul cu facilitati 
            mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f); //leaga parametrii de query
            mysqli_stmt_execute($stmt); //executa query-ul
        }
        mysqli_stmt_close($stmt); //inchide query-ul
    }
    else{
        $flag = 0;
        die('query failed - insert room features');
    }
    if($flag){ //daca toate query-urile au fost executate cu succes
        echo 1;
    }
    else{
        echo 0;
    }
}

// Check if the request is to generate a PDF for all rooms
if(isset($_POST['generate_all_rooms_pdf'])){
    // Create a new PDF document using FPDF or TCPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Times', 'B', 20);

    $col1_y = 10; // Initial Y position for column 1
    $col2_y = 10; // Initial Y position for column 2
    $col_width = 90; // Width of each column
    $line_height = 10; // Height of each line
    $space_between_rooms = 10; // Space between each room details
    $col = 1; // Start with column 1
    $fill = false; // Boolean variable to toggle the fill
    $fill_color_1 = [150, 150, 150]; // Dark gray color
    $fill_color_2 = [220, 220, 220]; // Light gray color
    // Fetch all room details from the database
    $res = select("SELECT * FROM `rooms` WHERE `removed`=?", [0], 'i');
  // Assuming $res is the result of your query
  while ($row = mysqli_fetch_assoc($res)) {
    // Set Y for the start of the room details
    $start_y = $col == 1 ? $col1_y : $col2_y; // Y position based on column
    $pdf->SetFillColor($fill ? $fill_color_2[0] : $fill_color_1[0], 
    $fill ? $fill_color_2[1] : $fill_color_1[1],  
    $fill ? $fill_color_2[2] : $fill_color_1[2]); // Set the fill color based on the boolean variable
    $box_height = 7 * $line_height; // Height of the box for room details


    $pdf->SetY($start_y); // Y position for the start of the room details
    $pdf->SetX($col == 1 ? 10 : 110);   // X position based on column
    $pdf->Cell($col_width, $box_height, '', 0, 0, '', true); // Draw a box for the room details

    $pdf->SetY($start_y); // Y position for the start of the room details
    $pdf->SetX($col == 1 ? 10 : 110); // X position based on column
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $col_width, $box_height); // Draw a box for the room details


    // Write room details
    $pdf->SetY($start_y);
    $pdf->SetX($col == 1 ? 10 : 110); // X position based on column
    $pdf->Cell($col_width, $line_height, "Room Name: " . $row['name']);
    $pdf->Ln();

    $pdf->SetY($start_y + $line_height); // Y position for the next line
    $pdf->SetX($col == 1 ? 10 : 110); // X position based on column
    $pdf->Cell($col_width, $line_height, "Area: " . $row['area'] . " sq.ft."); // Write the area
    $pdf->Ln(); // Move to the next line

    $pdf->SetY($start_y + 2 * $line_height);
    $pdf->SetX($col == 1 ? 10 : 110);
    $pdf->Cell($col_width, $line_height, "Price: $" . $row['price']);
    $pdf->Ln();

    $pdf->SetY($start_y + 3 * $line_height);
    $pdf->SetX($col == 1 ? 10 : 110);
    $pdf->Cell($col_width, $line_height, "Quantity: " . $row['quantity']);
    $pdf->Ln();

    $pdf->SetY($start_y + 4 * $line_height);
    $pdf->SetX($col == 1 ? 10 : 110);
    $pdf->Cell($col_width, $line_height, "Adult: " . $row['adult']);
    $pdf->Ln();

    $pdf->SetY($start_y + 5 * $line_height);
    $pdf->SetX($col == 1 ? 10 : 110);
    $pdf->Cell($col_width, $line_height, "Children: " . $row['children']);
    $pdf->Ln();

    $pdf->SetY($start_y + 6 * $line_height);
    $pdf->SetX($col == 1 ? 10 : 110);
    $pdf->Cell($col_width, $line_height, "Description: " . $row['description']);
    $pdf->Ln();


    // Update Y position for the next room in the current column
    // Added additional space between rooms
    if ($col == 1) {
        $col1_y = $start_y + 7 * $line_height + $space_between_rooms;
    } else {
        $col2_y = $start_y + 7 * $line_height + $space_between_rooms;
    }

    // Alternate columns
    $col = $col == 1 ? 2 : 1;
    $fill = $fill ? false : true; // Toggle the boolean variable
    }
    // Output the PDF to the browser or save it
    $pdf->Output('D', 'all_room_data.pdf');
    exit;
}


if(isset($_POST['get_all_rooms'])){ //preia toate camerele din baza de date si le afiseaza in tabel
    $res = select("SELECT * FROM `rooms` WHERE `removed`=?", [0], 'i'); //preia toate camerele din baza de date care nu au fost sterse (removed = 0)
    $i=1;

    $data = ""; //variabila care va contine toate camerele din baza de date

    while($row = mysqli_fetch_assoc($res)){ //pentru fiecare camera din baza de date
        if($row['status']==1){ //daca camera este activa 
            $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>"; //afiseaza butonul de active
        }
        else{
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>Inactive</button>"; //afiseaza butonul de inactive
        }

        $data.="
        <tr class='align-middle'>
            <td>$i</td>
            <td>$row[name]</td>
            <td>$row[area] sq.ft.</td>
            <td>
                <span class='badge rounded-pill bg-light text-dark'>
                    Adult: $row[adult]
                </span><br>
                <span class='badge rounded-pill bg-light text-dark'>
                    Children: $row[children]</span>
            </td>
            <td>$$row[price]</td>
            <td>$row[quantity]</td>
            <td>$status</td>
            <td>
                <button type='button' onclick='edit_details($row[id])' class='btn btn-primary btn-sm shadow-none' data-bs-toggle='modal' data-bs-target='#edit-room'>
                    <i class='bi bi-pencil-square'></i></button>
                <button type='button' onclick=\"room_images($row[id], '$row[name]')\" class='btn btn-info btn-sm shadow-none' data-bs-toggle='modal' data-bs-target='#room-images'>
                    <i class='bi bi-images'></i></button>
                <button type='button' onclick=\"remove_room($row[id])\" class='btn btn-danger btn-sm shadow-none'>
                    <i class='bi bi-trash'></i></button>
            </td>
        </tr>
        "; //afiseaza camera in tabel + butoanele de editare, adaugare imagini si stergere (apelate functiile edit_details, room_images si remove_room din js)
        $i++;
    }
    echo $data; //afiseaza toate camerele din baza de date
}



if(isset($_POST['get_room'])){ //preia datele camerei pentru a fi editate
    $frm = filteration($_POST);
    $res1 = select("SELECT * FROM `rooms` WHERE `id`=?", [$frm['get_room']], 'i'); //preia datele camerei din baza de date
    $res2 = select("SELECT * FROM `room_features` WHERE `room_id`=?", [$frm['get_room']], 'i'); 
    $res3 = select("SELECT * FROM `room_facilities` WHERE `room_id`=?", [$frm['get_room']], 'i');

    $roomdata = mysqli_fetch_assoc($res1); //preia datele camerei din baza de date intr-un array 
    $features = []; //array pentru features
    $facilities = []; 

    if(mysqli_num_rows($res2)>0){ //daca camera are features
        while($row = mysqli_fetch_assoc($res2)){ //pentru fiecare feature
            array_push($features, $row['features_id']); //adauga feature-ul in array
        }
    }

    if(mysqli_num_rows($res3)>0){ //daca camera are facilitati
        while($row = mysqli_fetch_assoc($res3)){
            array_push($facilities, $row['facilities_id']);
        }
    }

    $data = [
        'roomdata' => $roomdata, 
        'features' => $features,
        'facilities' => $facilities
    ]; //array cu datele camerei

    $data = json_encode($data); //transforma array-ul in json 

    echo $data;
}

if(isset($_POST['edit_room'])){ //editeaza camera
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `rooms` SET `name`=?,`area`=?,`price`=?,`quantity`=?,`adult`=?,`children`=?,`description`=? WHERE `id`=?";

    $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc'], $frm_data['room_id']];

    if(update($q1, $values, 'siiiiisi')){
        $flag = 1;
    }

    $del_features = delete("DELETE FROM `room_features` WHERE `room_id`=?", [$frm_data['room_id']], 'i'); // sterge facilitatile camerei din baza de date
    $del_facilities = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']], 'i'); // sterge dotarile camerei din baza de date

    if(!($del_features && $del_facilities)){ //daca nu s-au putut sterge facilitatile si dotarile camerei din baza de date
        $flag = 0;
    }
    else{
        $flag = 1;
    }

    $q2 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?, ?)"; //query pentru inserarea dotarilor camerei in baza de date

    if($stmt = mysqli_prepare($con, $q2)){ //pregateste query-ul pentru a fi executat
        foreach($facilities as $f){ //pentru fiecare dotare din array-ul cu dotari
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f); //leaga parametrii de query
            mysqli_stmt_execute($stmt); //executa query-ul
        }
        $flag = 1; //daca query-ul a fost executat cu succes, flag-ul devine 1
        mysqli_stmt_close($stmt); //inchide query-ul
    }
    else{
        $flag = 0;
        die('query failed - insert room facilities');
    }
    $q3 = "INSERT INTO `room_features` (`room_id`, `features_id`) VALUES (?, ?)";

    if($stmt = mysqli_prepare($con, $q3)){
        foreach($features as $f){ //pentru fiecare facilitate din array-ul cu facilitati
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        $flag = 1;
        mysqli_stmt_close($stmt);
    }
    else{
        $flag = 0;
        die('query failed - insert room features');
    }
    if($flag){
        echo 1;
    }
    else{
        echo 0;
    }

    
}

if(isset($_POST['toggle_status'])){ //schimba statusul camerei (active/inactive)
    $frm_data = filteration($_POST);

    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?"; //query pentru schimbarea statusului camerei
    $v = [$frm_data['value'], $frm_data['toggle_status']]; //value = 0 (inactive) sau 1 (active)

    if(update($q, $v, 'ii')){ //executa query-ul
        echo 1;
    }
    else{
        echo 0;
    };

}

if(isset($_POST['add_image'])){ //adauga imaginea in baza de date si in folderul cu imagini al camerei
    $frm_data = filteration($_POST);
    
    $img_r = uploadImage($_FILES['image'], ROOMS_FOLDER); //incarca imaginea in folderul cu imagini al camerei (incarca imaginea in folderul rooms din folderul img din folderul admin)

    if($img_r == "Invalid Image Format"){
        echo $img_r;
    }
    else if($img_r == "Image size should be less than 2MB"){
        echo $img_r;
    }
    else if($img_r == "Image cannot be uploaded"){
        echo $img_r;
    }
    else{
        $q = "INSERT INTO `room_image`(`room_id`, `image`) VALUES (?,?)";
        $values = [$frm_data['room_id'], $img_r]; //id-ul camerei si numele imaginii
        $res = insert ($q, $values, "is"); //executa query-ul
        echo $res;
    }


}

if(isset($_POST['get_room_images'])){ //get all images of a room
    $frm_data = filteration($_POST); //room id
    $res = select ("SELECT * FROM `room_image` WHERE `room_id`=?", [$frm_data['get_room_images']], 'i'); //primeste id-ul camerei si returneaza toate imaginile ei din baza de date in ordinea in care au fost adaugate (id-ul este auto incrementat)
    
    $path = ROOMS_IMG_PATH; //path-ul catre folderul cu imaginile camerelor

    while($row = mysqli_fetch_assoc($res)){ //pentru fiecare imagine din baza de date
        if ($row['thumb'] == 1) { //daca imaginea este thumbnail
            $thumb_btn = "<i class='bi bi-check-circle-fill text-light bg-success px-2 py-1 rounded fs-5'></i>"; //afiseaza butonul de thumbnail
        } else {
            $thumb_btn = "<button type='button' onclick='thumb_image({$row['sr_no']}, {$row['room_id']})' class='btn btn-secondary shadow-none'>
            <i class='bi bi-check-circle-fill'></i></button>"; //afiseaza butonul de setare ca thumbnail (apelat functia thumb_image din js)
        } 

        echo <<<data
        <tr class = 'align-middle'>
            <td><img src = '$path$row[image]' class='img-fluid'></td>
            <td>$thumb_btn</td>
            <td>
                <button type='button' onclick='rem_image($row[sr_no], $row[room_id])' class='btn shadow-none'>
                    <i class='bi bi-trash'></i></button>
                </td>
            </tr>
        data; //afiseaza imaginea in tabel  + buton de stergere a imaginii (apelat functia rem_image din js) 
    } 


}

if(isset($_POST['rem_image'])){ //sterge imaginea din baza de date si din folderul cu imagini al camerei
    $frm_data = filteration($_POST); //id-ul imaginii din baza de date
    $values = [$frm_data['image_id'], $frm_data['room_id']]; //id-ul imaginii din baza de date si id-ul camerei

    $pre_q = "SELECT * FROM `room_image` WHERE `sr_no`=? AND `room_id`=?"; //selecteaza imaginea din baza de date (pentru a afla numele imaginii) 
    $res = select($pre_q, $values, "ii"); //executa query-ul
    $img = mysqli_fetch_assoc($res); //preia rezultatul query-ului

    if(deleteImage($img['image'], ROOMS_FOLDER)){ //sterge imaginea din folderul cu imagini al camerei
        $q = "DELETE FROM `room_image` WHERE `sr_no`=? AND `room_id`=?"; //  sterge imaginea din baza de date
        $res = delete($q, $values, "ii"); //executa query-ul
        echo $res;  //returneaza rezultatul query-ului
    }
    else{
        echo 0; //daca nu s-a putut sterge imaginea din folderul cu imagini al camerei
    } 
} 


if(isset($_POST['thumb_image'])) //seteaza imaginea ca thumbnail
{ 
    $frm_data = filteration($_POST);  

    // Sterge thumbnail-ul curent
    $pre_q = "UPDATE `room_image` SET `thumb`=? WHERE `room_id`=?";
    $pre_v = [0, $frm_data['room_id']]; // 0 = not a thumbnail
    $pre_res = update($pre_q, $pre_v, "ii"); //executa query-ul

    // Seteaza imaginea ca thumbnail
    $q = "UPDATE `room_image` SET `thumb`=? WHERE `sr_no`=? AND `room_id`=?";
    $v = [1, $frm_data['image_id'], $frm_data['room_id']];
    $res = update($q, $v, "iii");

    echo $res;
}

if(isset($_POST['remove_room'])){  //sterge camera din baza de date si din folderul cu imagini al camerei
    $frm_data = filteration($_POST); 

    // Delete all images for the specified room
    $res1 = select("SELECT * FROM `room_image` WHERE `room_id`=?", [$frm_data['room_id']], 'i'); //primeste id-ul camerei si returneaza toate imaginile ei din baza de date in ordinea in care au fost adaugate (id-ul este auto incrementat)
    while($row = mysqli_fetch_assoc($res1)){ //pentru fiecare imagine din baza de date
        deleteImage($row['image'], ROOMS_FOLDER); //sterge imaginea din folderul cu imagini al camerei
    }

    $res2 = delete("DELETE FROM `room_image` WHERE `room_id`=?", [$frm_data['room_id']], 'i'); //sterge imaginile camerei din baza de date
    $res3 = delete("DELETE FROM `room_features` WHERE `room_id`=?", [$frm_data['room_id']], 'i'); //sterge facilitatile camerei din baza de date
    $res4 = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']], 'i'); //sterge dotarile camerei din baza de date
    $res5 = update("UPDATE `rooms` SET `removed`=? WHERE `id`=?", [1, $frm_data['room_id']], 'ii'); //seteaza statusul camerei pe 0 (inactive

    if($res2 || $res3 || $res4 || $res5){ // daca s-au putut sterge imaginile, facilitatile, dotarile si s-a putut seta statusul camerei pe 0
        echo 1;
    }
    else{
        echo 0;
    }

}

?>

