<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <style>
        .room {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        h3 {
            margin: 0;
        }
    </style>
</head>
<body>
    <h2>Booked Rooms</h2>

    <?php
    include 'admin/inc/db_config.php';
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "You must be logged in to view your bookings.";
        exit;
    }

    $userId = $_SESSION['uID'];

    $query = "SELECT r.name, r.area, r.price, bo.checkin_date, bo.checkout_date
              FROM rooms r
              JOIN booking_order bo ON r.id = bo.room_id
              WHERE bo.user_id = ? AND bo.status = 'confirmed'";

    if ($stmt = mysqli_prepare($con, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $roomName, $roomArea, $roomPrice, $checkinDate, $checkoutDate);

        $hasBookings = false;

        while (mysqli_stmt_fetch($stmt)) {
            $hasBookings = true;

            // Calcularea numărului de zile
            $checkin = new DateTime($checkinDate);
            $checkout = new DateTime($checkoutDate);
            $interval = $checkin->diff($checkout);
            $days = $interval->days;

            echo "<div class='room'>";
            echo "<h3>" . htmlspecialchars($roomName) . "</h3>";
            echo "<p>Area: " . htmlspecialchars($roomArea) . " sq.ft</p>";
            echo "<p>Price: $" . htmlspecialchars($roomPrice) . "</p>";
            echo "<p>Check-in Date: " . htmlspecialchars($checkinDate) . "</p>";
            echo "<p>Check-out Date: " . htmlspecialchars($checkoutDate) . "</p>";
            echo "<p>Days: " . $days . "</p>";
            echo "</div>";
        }

        if (!$hasBookings) {
            echo "<p>You have no confirmed bookings yet.</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Database error: " . mysqli_error($con);
    }

    mysqli_close($con);
    ?>
</body>
</html>
