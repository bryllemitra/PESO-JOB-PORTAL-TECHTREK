<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

// Start session to retrieve user data
session_start();
$data = $_SESSION['resume_data'];

// Load the HTML template
ob_start();
include 'templates/resume-template.php';
$html = ob_get_clean();

// Initialize Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF
$dompdf->stream("resume.pdf", ["Attachment" => true]);
exit;
?>