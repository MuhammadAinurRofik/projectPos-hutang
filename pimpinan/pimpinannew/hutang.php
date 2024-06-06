<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <h1>
      Hutang
      <small>Data Hutang</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Hutang</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">

          <div class="box-header">
            <h3 class="box-title">Hutang</h3>
            <a href="hutang_tambah.php" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp Tambah Hutang Baru</a>              
          </div>
          
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr>
                    <th width="1%">NO</th>
                    <th>NAMA PENGHUTANG</th>
                    <th>PRODUK</th>
                    <th>JUMLAH</th>
                    <th>HARGA</th>
                    <th>ALASAN</th>
                    <th>TEMPO HUTANG (hari)</th>
                    <th>TANGGAL JATUH TEMPO</th>
                    <th>JUMLAH CICILAN</th>
                    <th width="5%">FOTO</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  $data = mysqli_query($koneksi, "SELECT * FROM hutang ORDER BY hutang_id DESC");
                  while($d = mysqli_fetch_array($data)){
                  ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $d['hutang_nama']; ?></td>
                    <td><?php echo $d['hutang_kategori']; ?></td>
                    <td><?php echo $d['hutang_jumlah']; ?></td>
                    <td><?php echo "Rp." . number_format($d['hutang_harga']) . ",-"; ?></td>
                    <td><?php echo $d['hutang_keterangan']; ?></td>
                    <td><?php echo $d['tempo_hutang']; ?></td>
                    <td><?php echo $d['tanggal_jatuh_tempo']; ?></td>
                    <td><?php echo $d['jumlah_cicilan']; ?></td>
                    <td>
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#foto_<?php echo $d['hutang_id'] ?>">
                        <i class="fa fa-image"></i> Lihat
                      </button>
                      
                      <div class="modal fade" id="foto_<?php echo $d['hutang_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <center>
                                <?php if($d['hutang_foto'] == ""){ ?>
                                  <img src="../gambar/sistem/hutang.png" style="width: 100%;height: auto">
                                <?php } else { ?>
                                  <img src="../gambar/hutang/<?php echo $d['hutang_foto'] ?>" style="width: 100% ;height: auto">
                                <?php } ?>
                              </center>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                    
                  </tr>
                  <?php 
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </section>
    </div>
  </section>

</div>
<?php include 'footer.php'; ?>
