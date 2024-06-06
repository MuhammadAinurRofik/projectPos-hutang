<?php 
include '../koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "select * from hutang where hutang_id='$id'");
$d = mysqli_fetch_assoc($data);
$foto = $d['hutang_foto'];
unlink("../gambar/hutang/$foto");
mysqli_query($koneksi, "delete from hutang where hutang_id='$id'");
header("location:hutang.php");
