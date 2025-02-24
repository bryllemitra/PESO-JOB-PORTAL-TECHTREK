<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

// Retrieve session data
session_start();
$data = $_SESSION['resume_data'];

// Load the HTML template
ob_start();
include '../templates/resume-template.php';
$html = ob_get_clean();

// Initialize Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (inline or download)
$dompdf->stream("resume.pdf", ["Attachment" => true]);
exit;
?>