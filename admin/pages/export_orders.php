<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$stmt = $pdo->query('SELECT orders.id, products.name AS product_name, order_items.quantity, orders.total_price, orders.status, orders.created_at 
                     FROM orders 
                     JOIN order_items ON orders.id = order_items.order_id
                     JOIN products ON order_items.product_id = products.id');

$orders = $stmt->fetchAll();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Product');
$sheet->setCellValue('C1', 'Quantity');
$sheet->setCellValue('D1', 'Total Price');
$sheet->setCellValue('E1', 'Status');
$sheet->setCellValue('F1', 'Created At');

$row = 2;
foreach ($orders as $order) {
    $sheet->setCellValue('A' . $row, $order['id']);
    $sheet->setCellValue('B' . $row, $order['product_name']);
    $sheet->setCellValue('C' . $row, $order['quantity']);
    $sheet->setCellValue('D' . $row, $order['total_price']);
    $sheet->setCellValue('E' . $row, $order['status']);
    $sheet->setCellValue('F' . $row, $order['created_at']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'orders_' . date('Y-m-d_H-i-s') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>
