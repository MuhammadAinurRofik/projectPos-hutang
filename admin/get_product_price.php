<?php
include '../koneksi.php';

$namaProduk = $_GET['name'];
$response = array();

$query = mysqli_query($koneksi, "SELECT produk_harga_jual FROM produk WHERE produk_nama='$namaProduk'");
if ($row = mysqli_fetch_assoc($query)) {
    $response['harga'] = $row['produk_harga_jual'];
} else {
    $response['harga'] = null;
}

echo json_encode($response);
?>
