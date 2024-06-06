<?php 
include '../koneksi.php';

$nama = $_POST['nama'];
$nama_produk = $_POST['nama_produk'];
$jumlah = $_POST['jumlah'];

// Ambil harga produk dari database berdasarkan nama produk yang dimasukkan
$query_produk = mysqli_query($koneksi, "SELECT produk_harga_jual FROM produk WHERE produk_nama='$nama_produk'");
$data_produk = mysqli_fetch_assoc($query_produk);
$harga_produk = $data_produk['produk_harga_jual'];

// Hitung harga hutang secara otomatis
$harga_hutang = $harga_produk * $jumlah;

$keterangan = $_POST['keterangan'];
$tempo_hutang = $_POST['tempo_hutang'];

// Hitung tanggal jatuh tempo
$tanggal_transaksi = date("Y-m-d");
$tanggal_jatuh_tempo = date('Y-m-d', strtotime($tanggal_transaksi . ' + ' . $tempo_hutang . ' days'));

// Ambil jumlah cicilan dari form
$jumlah_cicilan = $_POST['jumlah_cicilan'];

// Kurangi harga hutang dengan jumlah cicilan
$harga_hutang_setelah_cicilan = $harga_hutang - $jumlah_cicilan;

$rand = rand();
$allowed = array('gif', 'png', 'jpg', 'jpeg');
$filename = $_FILES['foto']['name'];

if($filename == ""){
    mysqli_query($koneksi, "INSERT INTO hutang (hutang_nama, hutang_kategori, hutang_jumlah, hutang_harga, hutang_keterangan, tempo_hutang, tanggal_jatuh_tempo, hutang_foto, jumlah_cicilan) VALUES ('$nama', '$nama_produk', '$jumlah', '$harga_hutang_setelah_cicilan', '$keterangan', '$tempo_hutang', '$tanggal_jatuh_tempo', '', '$jumlah_cicilan')") or die(mysqli_error($koneksi));
    header("location:hutang.php");
} else {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(!in_array($ext, $allowed)) {
        header("location:hutang.php?alert=gagal");
    } else {
        move_uploaded_file($_FILES['foto']['tmp_name'], '../gambar/hutang/' . $rand . '_' . $filename);
        $file_gambar = $rand . '_' . $filename;
        mysqli_query($koneksi, "INSERT INTO hutang (hutang_nama, hutang_kategori, hutang_jumlah, hutang_harga, hutang_keterangan, tempo_hutang, tanggal_jatuh_tempo, hutang_foto, jumlah_cicilan) VALUES ('$nama', '$nama_produk', '$jumlah', '$harga_hutang_setelah_cicilan', '$keterangan', '$tempo_hutang', '$tanggal_jatuh_tempo', '$file_gambar', '$jumlah_cicilan')") or die(mysqli_error($koneksi));
        header("location:hutang.php");
    }
}
?>
