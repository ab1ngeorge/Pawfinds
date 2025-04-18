<?php
ob_start();
session_start();
include('includes/db.php');

// Check authentication
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}
if (!isset($_GET['request_id'])) {
    die("No request ID provided");
}

$request_id = $_GET['request_id'];
$user_id = $_SESSION['user_id'];

// Fetch adoption details
$query = "SELECT r.*, p.name as pet_name, p.category, u.username as adopter_name 
          FROM requests r 
          JOIN pets p ON r.pet_id = p.pet_id 
          JOIN users u ON r.adopter_id = u.user_id 
          WHERE r.request_id = ? AND r.adopter_id = ? AND r.status = 'approved'";

if (!$conn) die("Database connection failed");
$stmt = $conn->prepare($query) or die("Error preparing statement: " . $conn->error);
$stmt->bind_param("ii", $request_id, $user_id) or die("Error binding parameters: " . $stmt->error);
$stmt->execute() or die("Error executing statement: " . $stmt->error);
$result = $stmt->get_result() or die("Error getting result set: " . $stmt->error);
if ($result->num_rows === 0) die("No matching adoption request found or unauthorized access.");
$adoption = $result->fetch_assoc();

require_once('vendor/autoload.php');
if (!class_exists('TCPDF')) die('TCPDF library not found.');

// Create PDF (portrait for better single-page fit)
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Pawfind');
$pdf->SetAuthor('Pawfind');
$pdf->SetTitle('Pet Adoption Certificate');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Elegant black and white design
$pdf->SetFillColor(255, 255, 255); // White background
$pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');

// Certificate border with black accent
$pdf->SetLineWidth(1.5);
$pdf->SetDrawColor(0, 0, 0);
$pdf->RoundedRect(10, 10, 190, 270, 5, '1111');

// Logo placement (assuming black/white logo)
$pdf->Image('assets/images/logo.png', 80, 20, 50, 50, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);

// Main title with black styling
$pdf->SetFont('helvetica', 'B', 22);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(75);
$pdf->Cell(0, 10, 'CERTIFICATE OF ADOPTION', 0, 1, 'C');

// Decorative divider
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(50, $pdf->GetY() + 5, 160, $pdf->GetY() + 5);

// Main content with optimized spacing
$pdf->SetFont('helvetica', '', 14);
$pdf->SetTextColor(50, 50, 50);
$pdf->Ln(15);
$pdf->Cell(0, 8, 'This certifies that', 0, 1, 'C');

// Adopter's name in bold black
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 12, htmlspecialchars($adoption['adopter_name']), 0, 1, 'C');

// Adoption text
$pdf->SetFont('helvetica', '', 14);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 8, 'has lovingly adopted', 0, 1, 'C');

// Pet's name in bold black
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 12, htmlspecialchars($adoption['pet_name']), 0, 1, 'C');

// Pet details in gray box
$pdf->SetFillColor(245, 245, 245);
$pdf->SetDrawColor(200, 200, 200);
$pdf->RoundedRect(60, $pdf->GetY() + 5, 90, 20, 3, '1111', 'DF');
$pdf->SetFont('helvetica', 'I', 12);
$pdf->SetTextColor(70, 70, 70);
$pet_details = htmlspecialchars($adoption['category']);
if (!empty($adoption['breed'])) {
    $pet_details .= ' (' . htmlspecialchars($adoption['breed']) . ')';
}
$pdf->Cell(0, 20, $pet_details, 0, 1, 'C');

// Adoption date
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, 'on ' . date('F j, Y', strtotime($adoption['created_at'])), 0, 1, 'C');

// Signatures section (optimized for space)
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);

// Left signature (adopter)
$pdf->SetX(40);
$pdf->Cell(60, 5, '________________________', 0, 0, 'C');
$pdf->SetX(120);
$pdf->Cell(60, 5, '________________________', 0, 1, 'C');

$pdf->SetX(40);
$pdf->Cell(60, 5, 'Adopter Signature', 0, 0, 'C');
$pdf->SetX(120);
$pdf->Cell(60, 5, 'Pawfind Representative', 0, 1, 'C');

// Add digital signature image below Pawfind Representative
$signature_path = 'assets/images/digital_signature.png';
if (file_exists($signature_path)) {
    $pdf->Image($signature_path, 120, $pdf->GetY(), 40, 15, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->SetY($pdf->GetY() + 15); // Move Y position down after image
}

// Certificate ID
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Certificate ID: ' . uniqid(), 0, 1, 'C');

// Footer
$pdf->SetY(250);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'This certificate officially recognizes a loving forever home for ' . htmlspecialchars($adoption['pet_name']), 0, 1, 'C');
$pdf->Cell(0, 5, 'Issued by Pawfind - Where Pets Find Their Forever Homes', 0, 1, 'C');

ob_end_clean();
$pdf->Output('Adoption_Certificate_' . $adoption['pet_name'] . '.pdf', 'D');
?>