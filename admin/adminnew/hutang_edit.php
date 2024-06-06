<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <h1>
      Hutang
      <small>Edit Hutang</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li>Hutang</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-8 col-lg-offset-2">       
        <div class="box box-info">

          <div class="box-header">
            <h3 class="box-title">Edit Hutang</h3>
            <a href="hutang.php" class="btn btn-primary btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>

          <div class="box-body">

            <?php 
            if(isset($_GET['alert'])){
              if($_GET['alert'] == "gagal"){
                echo "<div class='alert alert-danger'>File yang diperbolehkan hanya file gambar!</div>";
              }
            }
            ?>
            <?php 
            $id = $_GET['id'];              
            $data = mysqli_query($koneksi, "SELECT * FROM hutang WHERE hutang_id='$id'");
            while($d = mysqli_fetch_array($data)){
              ?>

              <form action="hutang_update.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Nama</label>
                  <input type="hidden" name="id" value="<?php echo $d['hutang_id']; ?>">
                  <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama Penghutang .. (Wajib)" value="<?php echo $d['hutang_nama']; ?>">
                </div>

                <div class="form-group">
                  <label>Nama Produk</label>
                  <select class="form-control" name="kategori" required="required" onchange="updateHarga()">
                    <?php
                      // Query untuk mendapatkan semua produk
                      $query_produk = mysqli_query($koneksi, "SELECT * FROM produk");
                      while($produk = mysqli_fetch_assoc($query_produk)) {
                        $selected = $produk['produk_nama'] == $d['hutang_kategori'] ? 'selected' : '';
                        echo "<option value='".$produk['produk_nama']."' ".$selected.">".$produk['produk_nama']."</option>";
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label>Jumlah</label>
                  <input type="number" class="form-control" name="jumlah" required="required" placeholder="Masukkan Jumlah .. (Wajib)" value="<?php echo $d['hutang_jumlah']; ?>" onchange="updateHarga()">
                </div>

                <div class="form-group">
                  <label>Harga</label>
                  <input type="text" class="form-control" name="harga_hutang" required="required" placeholder="Masukkan Harga .. (Wajib)" value="<?php echo $d['hutang_harga']; ?>" readonly>
                </div>

                <div class="form-group">
                  <label>Alasan</label>
                  <textarea class="form-control" name="keterangan" placeholder="Masukkan Keterangan Alasan .. (Opsional)"><?php echo $d['hutang_keterangan']; ?></textarea>
                </div>

                <div class="form-group">
                  <label>Tempo Hutang (hari)</label>
                  <input type="number" class="form-control" name="tempo_hutang" id="tempo_hutang" required="required" placeholder="Masukkan Tempo Hutang .. (Wajib)" value="<?php echo $d['tempo_hutang']; ?>" oninput="calculateDueDate()">
                </div>

                <div class="form-group">
                  <label>Tanggal Jatuh Tempo</label>
                  <input type="text" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" readonly value="<?php echo $d['tanggal_jatuh_tempo']; ?>">
                </div>

                <div class="form-group">
                  <label>Jumlah Cicilan</label>
                  <input type="number" class="form-control" name="jumlah_cicilan" required="required" placeholder="Masukkan Jumlah Cicilan .. (Wajib)" value="<?php echo $d['jumlah_cicilan']; ?>">
                </div>

                <div class="form-group">
                  <label>Foto <small><i>Opsional</i></small></label>
                  <input type="file" name="foto">
                  <small class="text-muted"><i>Kosongkan jika tidak ingin diganti.</i></small>
                </div>

                <div class="form-group pull-right">
                  <input type="submit" class="btn btn-sm btn-primary" name="submit" value="Simpan">
                </div>
              </form>

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

                function updateHarga() {
                  var kategori = document.getElementsByName('kategori')[0].value;
                  var jumlah = document.getElementsByName('jumlah')[0].value;

                  // Fetch harga produk berdasarkan nama produk yang dipilih
                  fetch(`get_product_price.php?name=${kategori}`)
                    .then(response => response.json())
                    .then(data => {
                      if (data.harga) {
                        var total_harga = data.harga * jumlah;
                        var cicilan = document.getElementsByName('jumlah_cicilan')[0].value;
                        var harga_setelah_cicilan = total_harga - cicilan;
                        document.getElementsByName('harga_hutang')[0].value = harga_setelah_cicilan;
                      } else {
                        document.getElementsByName('harga_hutang')[0].value = '';
                      }
                    })
                    .catch(error => console.error('Error:', error));
                }
              </script>

              <?php
            }
            ?>
          </div>
        </div>
      </section>
    </div>
  </section>

</div>
<?php include 'footer.php'; ?>
