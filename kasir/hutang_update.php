<?php 
include '../koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$jumlah = $_POST['jumlah'];
$keterangan = $_POST['keterangan'];
$tempo_hutang = $_POST['tempo_hutang'];
$tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
$jumlah_cicilan = $_POST['jumlah_cicilan']; // Tambahkan input cicilan

// Ambil harga produk dari database berdasarkan kategori
$query_harga_produk = mysqli_query($koneksi, "SELECT produk_harga_jual FROM produk WHERE produk_nama='$kategori'");
$row_harga_produk = mysqli_fetch_assoc($query_harga_produk);
$harga_produk = $row_harga_produk['produk_harga_jual'];

// Hitung ulang harga berdasarkan jumlah produk yang diubah
$harga_hutang = $jumlah * $harga_produk;

// Kurangi harga hutang dengan jumlah cicilan
$harga_setelah_cicilan = $harga_hutang - $jumlah_cicilan;

$rand = rand();
$allowed = array('gif', 'png', 'jpg', 'jpeg');
$filename = $_FILES['foto']['name'];

if($filename == ""){
    // Update tanpa mengganti foto
    $query = "UPDATE hutang SET 
                hutang_nama='$nama', 
                hutang_kategori='$kategori', 
                hutang_jumlah='$jumlah', 
                hutang_harga='$harga_setelah_cicilan', 
                hutang_keterangan='$keterangan',
                tempo_hutang='$tempo_hutang',
                tanggal_jatuh_tempo='$tanggal_jatuh_tempo',
                jumlah_cicilan='$jumlah_cicilan' 
              WHERE hutang_id='$id'";
    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    header("location:hutang.php");
} else {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(!in_array($ext, $allowed)) {
        header("location:hutang.php?alert=gagal");
    } else {
        // Hapus foto lama
        $lama = mysqli_query($koneksi, "SELECT * FROM hutang WHERE hutang_id='$id'");
        $l = mysqli_fetch_assoc($lama);
        $foto_lama = $l['hutang_foto'];
        if($foto_lama != ""){
            unlink('../gambar/hutang/' . $foto_lama);
        }
        
        // Upload foto baru
        move_uploaded_file($_FILES['foto']['tmp_name'], '../gambar/hutang/' . $rand . '_' . $filename);
        $file_gambar = $rand . '_' . $filename;
        
        // Update data hutang termasuk foto
        $query = "UPDATE hutang SET 
                    hutang_nama='$nama', 
                    hutang_kategori='$kategori', 
                    hutang_jumlah='$jumlah', 
                    hutang_harga='$harga_setelah_cicilan', 
                    hutang_keterangan='$keterangan', 
                    hutang_foto='$file_gambar',
                    tempo_hutang='$tempo_hutang',
                    tanggal_jatuh_tempo='$tanggal_jatuh_tempo',
                    jumlah_cicilan='$jumlah_cicilan' 
                  WHERE hutang_id='$id'";
        mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
        header("location:hutang.php");
    }
}
?>
