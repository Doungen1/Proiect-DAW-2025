

<?php
require('../../fpdf/fpdf.php');
if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == 'all_rooms') {
    $frm_data = filteration($_GET);
    // Create a new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Fetch all room details from the database
    $res = select("SELECT * FROM `rooms` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
    while($row = mysqli_fetch_assoc($res)){
        // Add each room detail to the PDF
        $pdf->Cell(40, 10, $row['name']);
        $pdf->Cell(40, 10, $row['area']);
        $pdf->Cell(40, 10, $row['price']);
        $pdf->Cell(40, 10, $row['quantity']);
        $pdf->Cell(40, 10, $row['adult']);
        $pdf->Cell(40, 10, $row['children']);
        $pdf->Cell(40, 10, $row['description']);

    }

    // Output the PDF to the browser
    $pdf->Output('D', 'all_room_data.pdf');
    exit;
}

?>