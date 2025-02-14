


<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
require('../inc/essentials.php');
require('../inc/db_config.php');
require('../../fpdf/fpdf.php');




adminLogin();


if(isset($_POST['get_bookings'])){ // Preia toate booking-urile și le afișează în tabel
    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
              INNER JOIN `booking_details` bd ON bo.id = bd.order_id
              WHERE bo.status = 'confirmed' ORDER BY bo.id ASC";

    $res = mysqli_query($con, $query);
    $i = 1;
    $table_data = "";

    while ($data = mysqli_fetch_assoc($res)) {
        // Formatăm datele
        $date = date("d-m-Y", strtotime($data['created_at']));
        $checkin = date("d-m-Y", strtotime($data['checkin_date']));
        $checkout = date("d-m-Y", strtotime($data['checkout_date']));
        
        // Construim rândul din tabel folosind indexarea corectă a array-ului și escape pentru protecție
        $table_data .= "
        <tr>
            <td>{$i}</td>
            <td>
                <span class='badge bg-primary'>
                    Order ID: " . htmlspecialchars($data['order_id']) . "
                </span>
                <br>
                <b>Name:</b> " . htmlspecialchars($data['name']) . "
                <br>
                <b>Phone:</b> " . htmlspecialchars($data['phone']) . "
            </td>
            <td>
                <b>Room:</b> " . htmlspecialchars($data['room_id']) . "
                <br>
                <b>Price:</b> $" . htmlspecialchars($data['payment']) . "
            </td>
            <td>
                <b>Check In:</b> " . $checkin . "
                <br>
                <b>Check Out:</b> " . $checkout . "
                <br>
                <b>Date:</b> " . $date . "
            </td>
            <td>
                <button type='button' onclick='remove_booking(" . htmlspecialchars($data['id']) . ", " . htmlspecialchars($data['order_id']) . ")' class='btn btn-sm btn-danger'>Remove</button>
                <a href='generate_excel.php?order_id=" . urlencode($data['order_id']) . "' class='btn btn-sm btn-primary'>Generate Excel</a>
            </td>
        </tr>";
        $i++;
    }
    echo $table_data;
}


// Verifică dacă order_id este transmis
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
              INNER JOIN `booking_details` bd ON bo.id = bd.order_id
              WHERE bo.order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("No order found for order_id: " . $order_id);
    }

    // Setează antetele pentru descărcarea fișierului CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=order_' . $order_id . '.csv');

    $output = fopen('php://output', 'w');
    // Scrie antetele coloanelor – modifică după structura exactă a datelor
    fputcsv($output, array('Order ID', 'User Name', 'Phone', 'Room', 'Price', 'Check In', 'Check Out', 'Created At'));

    while ($row = $result->fetch_assoc()){
        fputcsv($output, array(
             $row['order_id'],
             $row['user_name'],   // Asigură-te că numele coloanei este corect
             $row['phone'],
             $row['name'],        // Poate reprezintă numele camerei
             $row['payment'],
             $row['check_in'],
             $row['check_out'],
             $row['datentime']
        ));
    }
    fclose($output);
    exit; // Ieșim din script pentru a nu executa restul codului
}


?>