


<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
require('../inc/essentials.php');
require('../inc/db_config.php');
require('../../fpdf/fpdf.php');




adminLogin();


// Check if the request is to generate a PDF for all rooms

if(isset($_POST['get_users'])){ //preia toate camerele din baza de date si le afiseaza in tabel
    $res = selectAll('user_cred');
    $i=1;
    $path = USERS_IMG_PATH;


    $data = ""; //variabila care va contine toate camerele din baza de date


    while($row = mysqli_fetch_assoc($res)){ //pentru fiecare camera din baza de date

        $del_but = "<button type='button' onclick='remove_user($row[id])' class='btn btn-sm btn-danger'>Remove</button>";

        $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";

        if($row['is_verified']){
            $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            $del_but = "";
        }

        $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-sm btn-danger'>Inactive</button>";

        if(!$row['status']){
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-sm btn-success'>Active</button>";
        }

        $date = date('d-m-Y', strtotime($row['datentime']));

        $data .= "<tr>
                <td>$i</td>
                <td><img src='$path$row[profile]' width='55px'>
                <br>
                $row[name]
                </td>
                <td>$row[email]</td>
                <td>$row[phonenum]</td>
                <td>$row[address] | $row[pincode]</td>
                <td>$row[dob]</td>
                <td>$verified</td>
                <td>$status</td>
                <td>$date</td>
                <td>$del_but</td>
                </tr>";
        $i++;
    }
    echo $data; //afiseaza toate camerele din baza de date
}



if(isset($_POST['toggle_status'])){ //schimba statusul camerei (active/inactive)
    $frm_data = filteration($_POST);

    $q = "UPDATE `user_cred` SET `status`=? WHERE `id`=?"; //query pentru schimbarea statusului camerei
    $v = [$frm_data['value'], $frm_data['toggle_status']]; //value = 0 (inactive) sau 1 (active)

    if(update($q, $v, 'ii')){ //executa query-ul
        echo 1;
    }
    else{
        echo 0;
    };

}



if(isset($_POST['remove_user'])){  //sterge camera din baza de date si din folderul cu imagini al camerei
    $frm_data = filteration($_POST); 


    $res = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?", [$frm_data['user_id'],0], 'ii');

    if($res){ // daca s-au putut sterge imaginile, facilitatile, dotarile si s-a putut seta statusul camerei pe 0
        echo 1;
    }
    else{
        echo 0;
    }

}

if(isset($_POST['search_user'])){
    $frm_data = filteration($_POST);
    
    $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";
    $res = select($query, ["%$frm_data[name]%"], 's');
    $i=1;
    $path = USERS_IMG_PATH;


    $data = ""; //variabila care va contine toate camerele din baza de date


    while($row = mysqli_fetch_assoc($res)){ //pentru fiecare camera din baza de date

        $del_but = "<button type='button' onclick='remove_user($row[id])' class='btn btn-sm btn-danger'>Remove</button>";

        $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";

        if($row['is_verified']){
            $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            $del_but = "";
        }

        $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-sm btn-danger'>Inactive</button>";

        if(!$row['status']){
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-sm btn-success'>Active</button>";
        }

        $date = date('d-m-Y', strtotime($row['datentime']));

        $data .= "<tr>
                <td>$i</td>
                <td><img src='$path$row[profile]' width='55px'>
                <br>
                $row[name]
                </td>
                <td>$row[email]</td>
                <td>$row[phonenum]</td>
                <td>$row[address] | $row[pincode]</td>
                <td>$row[dob]</td>
                <td>$verified</td>
                <td>$status</td>
                <td>$date</td>
                <td>$del_but</td>
                </tr>";
        $i++;
    }
    echo $data; //afiseaza toate camerele din baza de date
}




?>