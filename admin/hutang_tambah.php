<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <h1>
      Hutang
      <small>Tambah Hutang Baru</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Hutang</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-8 col-lg-offset-2">       
        <div class="box box-info">

          <div class="box-header">
            <h3 class="box-title">Tambah Hutang</h3>
            <a href="hutang.php" class="btn btn-primary btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>

          <div class="box-body">

           <?php 
           if(isset($_GET['alert'])){
            if($_GET['alert'] == "gagal"){
              echo "<div class='alert alert-danger'>File yang diperbolehkan hanya file gambar!</div>";
            }elseif($_GET['alert'] == "duplikat"){
              echo "<div class='alert alert-danger'><b>Kode Barang</b> sudah pernah digunakan!</div>";
            }
          }
          ?>

          <form action="hutang_act.php" method="post" enctype="multipart/form-data">

            <div class="form-group">
              <label>Nama Penghutang</label>
              <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama Penghutang .. (Wajib)">
            </div>

            <div class="form-group">
              <label>Nama Produk</label>
              <input type="text" class="form-control" id="nama_produk" name="nama_produk" required="required" placeholder="Masukkan Nama Produk .. (Wajib)" oninput="fetchProductPrice()">
            </div>

            <div class="form-group">
              <label>Jumlah</label>
              <input type="number" class="form-control" id="jumlah" name="jumlah" required="required" placeholder="Masukkan Jumlah .. (Wajib)" oninput="updateHargaHutang()">
            </div>

            <div class="form-group">
              <label>Harga</label>
              <input type="number" class="form-control" id="harga_produk" name="harga_hutang" readonly required="required" placeholder="Jumlah Hutang .. (Wajib)">
            </div>

            <div class="form-group">
              <label>Alasan</label>
              <textarea class="form-control" name="keterangan" placeholder="Masukkan Alasan Hutang .. (Opsional)"></textarea>
            </div>

            <div class="form-group">
              <label>Tempo Hutang (hari)</label>
              <input type="number" class="form-control" name="tempo_hutang" id="tempo_hutang" required="required" placeholder="Masukkan Tempo Hutang .. (Wajib)" oninput="calculateDueDate()">
            </div>

            <div class="form-group">
              <label>Tanggal Jatuh Tempo</label>
              <input type="text" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" readonly>
            </div>

            <div class="form-group">
              <label>Jumlah Cicilan</label>
              <input type="number" class="form-control" id="jumlah_cicilan" name="jumlah_cicilan" required="required" placeholder="Masukkan Jumlah Cicilan .. (Wajib)" oninput="updateHargaHutang()">
            </div>

            <div class="form-group">
              <label>Foto <small><i>Opsional</i></small></label>
              <input type="file" name="foto">
            </div>

            <div class="form-group pull-right">
              <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
            </div>
          </form>
        </div>

      </div>
    </section>
  </div>
</section>

</div>

<script>
function calculateDueDate() {
  const tempo = document.getElementById('tempo_hutang').value;
  if (tempo) {
    const today = new Date();
    today.setDate(today.getDate() + parseInt(tempo));
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero based
    const dd = String(today.getDate()).padStart(2, '0');
    const formattedDate = yyyy + '-' + mm + '-' + dd;
    document.getElementById('tanggal_jatuh_tempo').value = formattedDate;
  } else {
    document.getElementById('tanggal_jatuh_tempo').value = '';
  }
}

function fetchProductPrice() {
    const productName = document.getElementById('nama_produk').value;
    if (productName) {
        fetch(`get_product_price.php?name=${productName}`)
            .then(response => response.json())
            .then(data => {
                if (data.harga) {
                    document.getElementById('harga_produk').value = data.harga;
                    updateHargaHutang();
                } else {
                    document.getElementById('harga_produk').value = '';
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('harga_produk').value = '';
    }
}

function updateHargaHutang() {
    const jumlah = document.getElementById('jumlah').value;
    const harga_produk = document.getElementById('harga_produk').value;
    const jumlah_cicilan = document.getElementById('jumlah_cicilan').value;

    if (jumlah && harga_produk) {
        const total_hutang = jumlah * harga_produk;
        const harga_setelah_cicilan = total_hutang - (jumlah_cicilan ? jumlah_cicilan : 0);
        document.getElementById('harga_hutang').value = harga_setelah_cicilan;
    } else {
        document.getElementById('harga_hutang').value = '';
    }
}
</script>

<?php include 'footer.php'; ?>
