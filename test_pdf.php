<?php
/**
 * Simple test script to verify DomPDF functionality
 * This can be run from the command line to test PDF generation
 */

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Create a simple HTML test
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background: #f7f7f7; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PDF Generation Test</h1>
        <p>Testing DomPDF functionality</p>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Test Item 1</td>
                <td>This is a test description</td>
                <td>$100.00</td>
            </tr>
            <tr>
                <td>Test Item 2</td>
                <td>Another test description</td>
                <td>$200.00</td>
            </tr>
        </tbody>
    </table>
    
    <p style="margin-top: 20px;">
        <strong>Total: $300.00</strong>
    </p>
</body>
</html>';

// Configure DomPDF options
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', true);
$options->set('isFontSubsettingEnabled', true);
$options->set('dpi', 150);

// Create DomPDF instance
$dompdf = new Dompdf($options);

// Load HTML
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF (save to file for testing)
$output = $dompdf->output();
file_put_contents('test_output.pdf', $output);

echo "PDF test completed successfully! Check test_output.pdf\n";