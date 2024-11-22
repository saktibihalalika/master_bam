<?php
// Sertakan autoloader Composer
require_once '../helper/connection.php';
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Konfigurasi Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Aktifkan jika menggunakan URL eksternal seperti CSS, gambar

$dompdf = new Dompdf($options);

// Ambil output HTML
ob_start();
include('laporan-cetak.php'); // Ganti dengan file HTML Anda
$html = ob_get_clean();

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran kertas dan orientasi (opsional)
$dompdf->setPaper('A4', 'portrait'); // atau 'landscape'

// Render HTML ke PDF
$dompdf->render();

// Kirim hasil ke browser untuk diunduh
$dompdf->stream("laporan_perangkingan.pdf", ["Attachment" => 0]); // Attachment = 1 untuk unduhan otomatis
