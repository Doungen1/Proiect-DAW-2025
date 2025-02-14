

<?php

require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$result = mysqli_query($con, "
    SELECT b.id, u.name AS user_name, r.name AS room_name, b.start_date, b.end_date, b.booking_date 
    FROM bookings b 
    JOIN user_cred u ON b.user_id = u.id 
    JOIN rooms r ON b.room_id = r.id
");

while ($row = mysqli_fetch_assoc($result)) {
    echo "Booking ID: " . $row['id'] . " | User: " . $row['user_name'] . " | Room: " . $row['room_name'] . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Bookings</title>
    <?php require('inc/links.php'); ?>
</head>
<body>
<?php require('inc/header.php'); ?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">Bookings</h3>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive-md">
                        <table class="table table-hover border">
                            <thead>
                                <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Room</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Booking Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['username']}</td>
                                                <td>{$row['room_name']}</td>
                                                <td>{$row['start_date']}</td>
                                                <td>{$row['end_date']}</td>
                                                <td>{$row['booking_date']}</td>
                                                <td>
                                                    <button onclick='cancelBooking({$row['id']})' class='btn btn-danger btn-sm'>Cancel</button>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No bookings found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function cancelBooking(id) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        window.location.href = `cancel_booking.php?id=${id}`;
    }
}
</script>

<?php require('inc/scripts.php'); ?>
</body>
</html>
