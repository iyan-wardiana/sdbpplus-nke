<?php $this->load->view('template/head.php'); ?>

<body class="theme-green">
    <!-- Page Loader -->
    <?php //$this->load->view('template/preloader'); ?>
    <!-- #END# Page Loader -->

    <!-- Overlay For Sidebars -->
    <div class="overlay" style="display:none"></div>
    <!-- #END# Overlay For Sidebars -->

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->

    <!-- Top Bar -->
    <?php $this->load->view('template/topbar'); ?>
    <!-- #Top Bar -->

    <section>
        <!-- Left Sidebar -->
        <?php $this->load->view('template/sidebar'); ?>
        <!-- #END# Left Sidebar -->

        <!-- Right Sidebar -->
        <?php //$this->load->view('template/rightsidebar'); ?>
        <!-- #END# Right Sidebar -->

    </section>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="defaultModalLabel">Add Item</h4>
              </div>
              <form id="frmReg" method="POST" action="<?php echo $add_itemlist; ?>" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="Kode_Brg" id="Kode_Brg" value="">
              <div class="modal-body">
                <label class="form-label">Kode Barang</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" class="form-control" name="Kode_Brgx" id="Kode_Brgx" value="" disabled>
                    </div>
                </div>
                <!-- Kode Barcode
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="Kode_Barcode_Brg" id="Kode_Barcode_Brg" value="" required>
                        <label class="form-label">Kode Barcode</label>
                    </div>
                </div>
                End Kode Barcode -->
                  <div class="form-group form-float">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Nama_Brg" id="Nama_Brg" value="" onChange="functioncheck1()" required>
                          <label class="form-label">Nama Barang</label>
                      </div>
                  </div>
                  <label class="form-label">Satuan Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Satuan" id="Kode_Satuan" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewUnitI->num_rows() > 0){
                                foreach($viewUnitI->result() as $rUnitI){
                                  $Kode_Satuan = $rUnitI->Kode_Satuan;
                                  $Nama_Satuan = $rUnitI->Nama_Satuan;
                                  ?>
                                  <option value="<?=$Kode_Satuan?>"><?=$Nama_Satuan?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <div class="form-group form-float">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Harga_Belix" id="Harga_Belix" autocomplete="off" style="text-align:right" value="" required>
                          <input type="hidden" class="form-control" name="Harga_Beli" id="Harga_Beli" autocomplete="off" style="text-align:right" value="">
                          <label class="form-label">Harga Beli</label>
                      </div>
                  </div>
                  <div class="form-group form-float">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Harga_Jualx" id="Harga_Jualx" autocomplete="off" style="text-align:right" value="" required>
                          <input type="hidden" class="form-control" name="Harga_Jual" id="Harga_Jual" autocomplete="off" style="text-align:right" value="">
                          <label class="form-label">Harga Jual</label>
                      </div>
                  </div>
                  <label class="form-label">Merek Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Merek" id="Kode_Merek" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewBrandI->num_rows() > 0){
                                foreach($viewBrandI->result() as $rBrandI){
                                  $Kode_Merek = $rBrandI->Kode_Merek;
                                  $Nama_Merek = $rBrandI->Nama_Merek;
                                  ?>
                                  <option value="<?=$Kode_Merek?>"><?=$Nama_Merek?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Kategori Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Kategori" id="Kode_Kategori" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewCatI->num_rows() > 0){
                                foreach($viewCatI->result() as $rCatI){
                                  $Kode_Kategori = $rCatI->Kode_Kategori;
                                  $Nama_Kategori = $rCatI->Nama_Kategori;
                                  ?>
                                  <option value="<?=$Kode_Kategori?>"><?=$Nama_Kategori?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Jenis Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Jenis" id="Kode_Jenis" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewTyI->num_rows() > 0){
                                foreach($viewTyI->result() as $rTyI){
                                  $Kode_Jenis = $rTyI->Kode_Jenis;
                                  $Nama_Jenis = $rTyI->Nama_Jenis;
                                  ?>
                                  <option value="<?=$Kode_Jenis?>"><?=$Nama_Jenis?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Supplier</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Supplier" id="Kode_Supplier" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewSPLL->num_rows() > 0){
                                foreach($viewSPLL->result() as $rSPLL){
                                  $Kode_Supplier = $rSPLL->Kode_Supplier;
                                  $Nama_Supplier = $rSPLL->Nama_Supplier;
                                  ?>
                                  <option value="<?=$Kode_Supplier?>"><?=$Nama_Supplier?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Lokasi Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Lokasi" id="Kode_Lokasi" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewLocI->num_rows() > 0){
                                foreach($viewLocI->result() as $rLocI){
                                  $Kode_Lokasi = $rLocI->Kode_Lokasi;
                                  $Nama_Lokasi = $rLocI->Nama_Lokasi;
                                  ?>
                                  <option value="<?=$Kode_Lokasi?>"><?=$Nama_Lokasi?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <div class="form-group form-float">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Stok" id="Stok" value="" required>
                          <label class="form-label">Stok Barang</label>
                      </div>
                  </div>
                  <div class="form-group form-float">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Stok_Minimal" id="Stok_Minimal" value="" required>
                          <label class="form-label">Stok Minimal</label>
                      </div>
                  </div>
                  <div class="form-group form-float">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Stok_Maximal" id="Stok_Maximal" value="" required>
                          <label class="form-label">Stok Maximal</label>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="form-label">Upload gambar</label>
                      <div class="form">
                        <div class="multiple-form-group input-group" style="max-width:300px">
                            <input type="file" name="images[]" id="images" class="form-control" autocomplete="off" multiple>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-add" id="btnUpload">+</button>
                            </span>
                        </div>
                        <span style="font-size:10px;font-style:italic">Format : JPG/JPEG & PNG</span>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-info waves-effect"><i class="fa fa-save (alias)"></i>&nbsp;&nbsp;Save</button>
                  <button type="button" class="btn btn-info waves-effect" data-dismiss="modal"><i class="fa fa-close (alias)"></i>&nbsp;&nbsp;Close</button>
              </div>
              </form>
          </div>
      </div>
  </div>

  <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="defaultModalLabel">Update Item</h4>
              </div>
              <form id="frmRegUp" method="POST" action="<?php echo $update_itemlist; ?>">
              <input type="hidden" class="form-control" name="Kode_Brg" id="Kode_Brg_E" value="">
              <div class="modal-body">
                  <label class="form-label">Kode Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Kode_Brgx" id="Kode_Brgx_E" value="" disabled>
                      </div>
                  </div>
                  <!-- Kode barcode
                  <label class="form-label">Kode Barcode</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Kode_Barcode_Brg" id="Kode_Barcode_Brg_E" value="" required>
                      </div>
                  </div>
                  End Kode Barcode -->
                  <label class="form-label">Nama Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Nama_Brg" id="Nama_Brg_E" value="" onChange="functioncheck1()" required>
                      </div>
                  </div>
                  <label class="form-label">Satuan Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Satuan" id="Kode_Satuan_E" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewUnitI->num_rows() > 0){
                                foreach($viewUnitI->result() as $rUnitI){
                                  $Kode_Satuan = $rUnitI->Kode_Satuan;
                                  $Nama_Satuan = $rUnitI->Nama_Satuan;
                                  ?>
                                  <option value="<?=$Kode_Satuan?>"><?=$Nama_Satuan?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Harga Beli</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Harga_Belix" id="Harga_Belix_E" autocomplete="off" style="text-align:right" value="" required>
                          <input type="hidden" class="form-control" name="Harga_Beli" id="Harga_Beli_E" autocomplete="off" style="text-align:right" value="">
                      </div>
                  </div>
                  <label class="form-label">Harga Jual</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Harga_Jualx" id="Harga_Jualx_E" autocomplete="off" style="text-align:right" value="" required>
                          <input type="hidden" class="form-control" name="Harga_Jual" id="Harga_Jual_E" autocomplete="off" style="text-align:right" value="">
                      </div>
                  </div>
                  <label class="form-label">Merek Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Merek" id="Kode_Merek_E" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewBrandI->num_rows() > 0){
                                foreach($viewBrandI->result() as $rBrandI){
                                  $Kode_Merek = $rBrandI->Kode_Merek;
                                  $Nama_Merek = $rBrandI->Nama_Merek;
                                  ?>
                                  <option value="<?=$Kode_Merek?>"><?=$Nama_Merek?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Kategori Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Kategori" id="Kode_Kategori_E" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewCatI->num_rows() > 0){
                                foreach($viewCatI->result() as $rCatI){
                                  $Kode_Kategori = $rCatI->Kode_Kategori;
                                  $Nama_Kategori = $rCatI->Nama_Kategori;
                                  ?>
                                  <option value="<?=$Kode_Kategori?>"><?=$Nama_Kategori?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Jenis Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Jenis" id="Kode_Jenis_E" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewTyI->num_rows() > 0){
                                foreach($viewTyI->result() as $rTyI){
                                  $Kode_Jenis = $rTyI->Kode_Jenis;
                                  $Nama_Jenis = $rTyI->Nama_Jenis;
                                  ?>
                                  <option value="<?=$Kode_Jenis?>"><?=$Nama_Jenis?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Supplier</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Supplier" id="Kode_Supplier_E" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewSPLL->num_rows() > 0){
                                foreach($viewSPLL->result() as $rSPLL){
                                  $Kode_Supplier = $rSPLL->Kode_Supplier;
                                  $Nama_Supplier = $rSPLL->Nama_Supplier;
                                  ?>
                                  <option value="<?=$Kode_Supplier?>"><?=$Nama_Supplier?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Lokasi Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <select name="Kode_Lokasi" id="Kode_Lokasi_E" class="form-control show-tick" required>
                              <option value=""></option>
                              <?php
                              if($viewLocI->num_rows() > 0){
                                foreach($viewLocI->result() as $rLocI){
                                  $Kode_Lokasi = $rLocI->Kode_Lokasi;
                                  $Nama_Lokasi = $rLocI->Nama_Lokasi;
                                  ?>
                                  <option value="<?=$Kode_Lokasi?>"><?=$Nama_Lokasi?></option>
                                  <?php
                                }
                              }
                              ?>
                          </select>
                      </div>
                  </div>
                  <label class="form-label">Stok Barang</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Stok" id="Stok_E" value="" required>
                      </div>
                  </div>
                  <label class="form-label">Stok Minimal</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Stok_Minimal" id="Stok_Minimal_E" value="" required>
                      </div>
                  </div>
                  <label class="form-label">Stok Maximal</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="Stok_Maximal" id="Stok_Maximal_E" value="" required>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="form-label">Upload gambar</label>
                      <div class="form">
                        <div class="multiple-form-group input-group" style="max-width:300px">
                            <input type="file" name="images[]" id="images_E" class="form-control" multiple>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-add">+</button>
                            </span>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-info waves-effect"><i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Update</button>
                  <button type="button" class="btn btn-info waves-effect" data-dismiss="modal"><i class="fa fa-close (alias)"></i>&nbsp;&nbsp;Close</button>
              </div>
              </form>
          </div>
      </div>
  </div>
	<!-- content -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none">
                <h2>&nbsp;</h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?php echo $title; ?>
                            </h2>
                            <ul class="header-dropdown m-r--5" style="display:none">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Update</a></li>
                                        <li><a href="javascript:void(0);">Delete</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">

                          <?php
                  					  $message = $this->session->flashdata('msg');
                  					  if (isset($message)) {
                  						  echo '<div class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    '.$message.'
                                </div>';
                  						   $this->session->unset_userdata('msg');
                  					  }
                  					?>
                            <div class="table-responsive" style="overflow-x:hidden">
                                <table id="example" class="display cell-border">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="white-space:nowrap; text-align:center">Kode Barang</th>
                                            <th style="white-space:nowrap; text-align:center">Nama Barang</th>
                                            <th style="text-align:center">Satuan</th>
                                            <th style="text-align:center">Harga Beli</th>
                                            <th style="text-align:center">Harga Jual</th>
                                            <th style="text-align:center">Merek</th>
                                            <th style="text-align:center">Kategori</th>
                                            <th style="text-align:center">Jenis</th>
                                            <th style="text-align:center">Supplier</th>
                                            <th style="text-align:center">Lokasi</th>
                                            <th style="text-align:center">Stok</th>
                                            <th style="text-align:center">Stok Minimal</th>
                                            <th style="text-align:center">Stok Maximal</th>
                                            <th style="text-align:center">File Gambar</th>
                                        </tr>
                                    </thead>
                                    <tfoot style="display:none">
                                      <tr>
                                        <th></th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Merek</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Supplier</th>
                                        <th>Lokasi</th>
                                        <th>Stok</th>
                                        <th>Stok Minimal</th>
                                        <th>Stok Maximal</th>
                                        <th>File Gambar</th>
                                      </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->
        </div>
    </section>

    <?php //$this->load->view('template/core-js'); ?>

	<!-- #END content -->

<!-- Custom Add JQuery -->
<script type="text/javascript">
  /* Fungsi formatRupiah */
  function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
  }

  $(document).ready(function() {
	$('#frmReg').validate({
		highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
		messages: {
      /*
      Kode_Barcode_Brg: {
				required: "Kode Barcode barang harus diisi"
			},*/
      Nama_Brg: {
				required: "Nama Barang harus diisi"
			},
			Kode_Satuan: {
				required: "Satuan Barang harus diisi"
			},
      Harga_Belix: {
				required: "Harga Beli barang harus diisi"
			},
      Harga_Jualx: {
				required: "Harga Jual Barang harus diisi"
			},
			Kode_Merek: {
				required: "Merek Barang harus diisi"
			},
      Kode_Kategori: {
				required: "Kategori Barang harus diisi"
			},
      Kode_Jenis: {
				required: "Jenis barang harus diisi"
			},
      Kode_Supplier: {
				required: "Supplier Barang harus diisi"
			},
			Kode_Lokasi: {
				required: "Lokasi Barang harus diisi"
			},
      Stok: {
				required: "Stok barang harus diisi"
			},
      Stok_Minimal: {
				required: "Stok Minimal Barang harus diisi"
			},
			Stok_Maximal: {
				required: "Stok maksimal Barang harus diisi"
			}
		}
	});

  $('#frmRegUp').validate({
    highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        messages: {
          /*
          Kode_Barcode_Brg: {
    				required: "Kode Barcode barang harus diisi"
    			},*/
          Nama_Brg: {
    				required: "Nama Barang harus diisi"
    			},
    			Kode_Satuan: {
    				required: "Satuan Barang harus diisi"
    			},
          Harga_Belix: {
    				required: "Harga Beli barang harus diisi"
    			},
          Harga_Jualx: {
    				required: "Harga Jual Barang harus diisi"
    			},
    			Kode_Merek: {
    				required: "Merek Barang harus diisi"
    			},
          Kode_Kategori: {
    				required: "Kategori Barang harus diisi"
    			},
          Kode_Jenis: {
    				required: "Jenis barang harus diisi"
    			},
          Kode_Supplier: {
    				required: "Supplier Barang harus diisi"
    			},
    			Kode_Lokasi: {
    				required: "Lokasi Barang harus diisi"
    			},
          Stok: {
    				required: "Stok barang harus diisi"
    			},
          Stok_Minimal: {
    				required: "Stok Minimal Barang harus diisi"
    			},
    			Stok_Maximal: {
    				required: "Stok maksimal Barang harus diisi"
    			}
    		}
	});

	//$(".preloader").fadeOut();
	var table = $('#example').DataTable({
    "processing": true,
    "serverSide": true,
    "order": [],
    "ajax": {
        "url": "<?php echo site_url('c_data/c_masteritem/get_data_item')?>",
        "type": "POST"
    },
    "select": {
        "style":    'multi'
    },
    "scrollX": true,
    "autoWidth": true,
    "filter": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "columnDefs": [
        { "width": "100px", "targets": [4,5,6,7,8] },
        { "width": "150px", "targets": [2,9,14] },
        {
            "targets":   0,
            "className": "text-center",
            "checkboxes": {
              "selectRow": true
            }
        },
      ],
		//order: [[ 1, "asc" ]],
        dom:  "<'row'<'col-md-12'B>>" +
              "<'row'<'col-md-6'l><'col-md-6'f>>"+
              "<'row'<'col-md-12'tr>>" +
              "<'row'<'col-md-6'i><'col-md-6'p>>",
        buttons: [
                  /* Select & Deselect All -----------------------
                  {
                    extend: 'selectAll',
                    text:'<i class="fa fa-check-square-o"></i>',
                    titleAttr: 'Select All',
                    className: 'btn btn-default waves-effect'
                  },
                  {
                    extend: 'selectNone',
                    text:'<i class="fa fa-close (alias)"></i>',
                    titleAttr: 'Deselect All',
                    className: 'btn btn-default waves-effect'
                  },
                  End -------------------*/
                  {
                    text:'<i class="fa fa-plus"></i>',
				            titleAttr: 'Add',
                    className: 'btn btn-default waves-effect',
                    action: function ( e, dt, node, config ){
                      $.ajax({
                        url:"<?php echo base_url().'index.php/c_data/c_masteritem/CreateAoutoCode'; ?>",
                        dataType:"JSON",
                        error: function (request, error, data) {
                          console.log(arguments);
                          alert(" Can't do because: " + error + " and data "+data);
                        },
                        success: function(data){
                          //alert(data);
                          $('#Kode_Brg').val(data);
                          $('#Kode_Brgx').val(data);
                        }
                      });
                      //$('#Kode_Brg').val();
                      $('#addModal').modal();
                    }
                  },
                  /* get multi cell data
                  {
                    text: 'Use Selected Receipts',
                    action: function ( e, dt, node, config) {
                         var data = table.rows('.selected').data().toArray();
                         var dataRow = table.rows('.selected').data().length;
                         //alert(dataRow);
                         var cellID = "";
                         var valID = "";
                         for(var i=0;i<dataRow;i++){
                           //alert(data[i][1]);
                           cellID = data[i][1];
                           if(i == 0){
                             valID = cellID;
                           }else{
                             valID = valID+'|'+cellID;
                           }
                         }
                         alert(valID);
                       }
                  },
                  End get multi cell data */
			            {
                    text:'<i class="fa fa-edit (alias)"></i>',
				            titleAttr: 'Update',
                    className: 'btn btn-default waves-effect',
                    action: function ( e, dt, node, config ){
                      var data = table.rows('.selected').data().toArray();
                      var dataRow = table.rows('.selected').data().length;
                      //alert(dataRow);
                      var cellID = "";
                      var valID = "";
                      for(var i=0;i<dataRow;i++){
                        //alert(data[i][1]);
                        cellID = data[i][1];
                        if(i == 0){
                          valID = cellID;
                        }else{
                          valID = valID+'|'+cellID;
                        }
                      }
                      //alert(valID);
            					if(dataRow == 0)
            					{
            						swal("Silahkan pilih data yang akan diedit");
            					}
                      else if(dataRow > 1)
                      {
                        swal("Silahkan pilih salah satu data yang akan diedit");
                      }
            					else
            					{
            						//var	Kode_Brg_1	= valID;

            						//alert(UserID_1);
            						//return false;
            						$.ajax({
            							type:"POST",
            							url:"<?php echo base_url().'index.php/c_data/c_masteritem/ViewupdIL'; ?>",
            							dataType:"JSON",
            							async: false,
            							data: {Kode_Brg_1:valID},
            							error: function (request, error, data) {
            								console.log(arguments);
            								alert(" Can't do because: " + error + " and data "+data);
            							},
            							success: function(data){
            								//alert(data[0].UserID);
            								$('#Kode_Brgx_E').val(data[0].Kode_Brg);
                            $('#Kode_Brg_E').val(data[0].Kode_Brg);
                            //$('#Kode_Barcode_Brg_E').val(data[0].Kode_Barcode_Brg);
                            $('#Nama_Brg_E').val(data[0].Nama_Brg);
                            $('#Kode_Satuan_E option[value="'+data[0].Kode_Satuan+'"]').prop('selected', true);
                            //$('#Kode_Satuan_E').selectpicker('val', data[0].Kode_Satuan);
                            $('#Harga_Belix_E').val(formatRupiah(data[0].Harga_Beli, "Rp. "));
                            $('#Harga_Beli_E').val(data[0].Harga_Beli);
                            $('#Harga_Jualx_E').val(formatRupiah(data[0].Harga_Jual, "Rp. "));
                            $('#Harga_Jual_E').val(data[0].Harga_Jual);
                            $('#Kode_Merek_E option[value="'+data[0].Kode_Merek+'"]').prop('selected', true);
                            //$('#Kode_Merek_E').selectpicker('val', data[0].Kode_Merek);
                            $('#Kode_Kategori_E option[value="'+data[0].Kode_Kategori+'"]').prop('selected', true);
                            //$('#Kode_Kategori_E').selectpicker('val', data[0].Kode_Kategori);
                            $('#Kode_Jenis_E option[value="'+data[0].Kode_Jenis+'"]').prop('selected', true);
                            //$('#Kode_Jenis_E').selectpicker('val', data[0].Kode_Jenis);
                            $('#Kode_Supplier_E option[value="'+data[0].Kode_Supplier+'"]').prop('selected', true);
                            //$('#Kode_Supplier_E').selectpicker('val', data[0].Kode_Supplier);
                            $('#Kode_Lokasi_E option[value="'+data[0].Kode_Lokasi+'"]').prop('selected', true);
                            //$('#Kode_Lokasi_E').selectpicker('val', data[0].Kode_Lokasi);
                            $('#Stok_E').val(data[0].Stok);
                            $('#Stok_Minimal_E').val(data[0].Stok_Minimal);
                            $('#Stok_Maximal_E').val(data[0].Stok_Maximal);
                            //var Kode_Kategori_E = data[0].Kode_Kategori;


            								$('#updateModal').modal();
            							}
            						});

            					}
                    }
                  },
			            {
                    text:'<i class="fa fa-trash-o"></i>',
				            titleAttr: 'Delete',
                    className: 'btn btn-default waves-effect',
                    action: function ( e, dt, node, config ){
                      var data = table.rows('.selected').data().toArray();
                      var dataRow = table.rows('.selected').data().length;
                      //alert(dataRow);
                      var cellID = "";
                      var valID = "";
                      for(var i=0;i<dataRow;i++){
                        //alert(data[i][1]);
                        cellID = data[i][1];
                        if(i == 0){
                          valID = cellID;
                        }else{
                          valID = valID+'|'+cellID;
                        }
                      }
                      //alert(valID);

            					if(dataRow == 0)
            					{
            						swal("Silahkan pilih data yang akan dihapus");
            					}
            					else
            					{
            						//var	Kode_Brg_1	= valID;
            						//alert(UserID_1a);
            						//return false;
            						swal({
            							title: "Are you sure?",
            							text: "You will not be able to recover this imaginary file!",
            							type: "warning",
            							showCancelButton: true,
            							confirmButtonColor: "#DD6B55",
            							confirmButtonText: "Yes, delete it!",
            							cancelButtonText: "No, cancel plx!",
            							closeOnConfirm: false,
            							closeOnCancel: false
            						}, function (isConfirm) {
            							if (isConfirm) {
            									$.ajax({
            									type:"POST",
            									url:"<?php echo site_url('c_data/c_masteritem/deleteIL'); ?>",
            									dataType:"JSON",
            									data: {Kode_Brg_1:valID},
            									success: function(data){
            										table.rows('.selected').remove().draw( false );
            										swal("Deleted!", "Your imaginary file has been deleted.", "success");

            									}
            								});
            								return false;
            							} else {
            								swal("Cancelled", "Your imaginary file is safe :)", "error");
            								return false;
            							}
            						});
            					}
                    }
                  },
			            {
                    extend: 'pdf',
                    text:'<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'Export To PDF',
                    className: 'btn btn-default waves-effect'
                  },
                  {
                    extend: 'excel',
                    text:'<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Export To Excel',
                    className: 'btn btn-default waves-effect'
                  },
                  {
                    extend: 'print',
                    text:'<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    className: 'btn btn-default waves-effect'
                  },
                  //'copyHtml5',
                  //'excelHtml5',
                  //'pdfHtml5'
                  //'csvHtml5'
                ],
                /*
                language: {
                    buttons: {
                        selectAll: "Select all items",
                        selectNone: "Select none"
                    }
                }
                End Language Button Datatables */
              });

    var addFormGroup = function (event) {
  			 event.preventDefault();

  			 var $formGroup = $(this).closest('.form-group');
  			 var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
  			 var $formGroupClone = $formGroup.clone();

  			 $(this)
  					 .toggleClass('btn-success btn-add btn-danger btn-remove')
  					 .html('–');

  			 $formGroupClone.find('input').val('');
  			 $formGroupClone.insertAfter($formGroup);

  			 var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
  			 if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
  					 $lastFormGroupLast.find('.btn-add').attr('disabled', true);
  			 }
  	 };

  	 var removeFormGroup = function (event) {
  			 event.preventDefault();

  			 var $formGroup = $(this).closest('.form-group');
  			 var $multipleFormGroup = $formGroup.closest('.multiple-form-group');

  			 var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
  			 if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
  					 $lastFormGroupLast.find('.btn-add').attr('disabled', false);
  			 }

  			 $formGroup.remove();
  	 };

  	 var countFormGroup = function ($form) {
  			 return $form.find('.form-group').length;
  	 };

  	 $(document).on('click', '.btn-add', addFormGroup);
  	 $(document).on('click', '.btn-remove', removeFormGroup);


    $('#Kode_Kategori').change(function() {
      /* Act on the event */
      var Kode_Kategori = $('#Kode_Kategori').val();
      //alert(Kode_Kategori);
      $.ajax({
        type:"POST",
        url:"<?php echo base_url().'index.php/c_data/c_masteritem/ViewOptJenis'; ?>",
        dataType:"JSON",
        async: false,
        data: {Kode_Kategori:Kode_Kategori},
        error: function (request, error, data) {
          console.log(arguments);
          alert(" Can't do because: " + error + " and data "+data);
        },
        success: function(data){
          //alert(data[0].UserID);
          //alert(data.length);
          var html = '<option value=""></option>';
          //alert(data.length);
          for(var i=0;i<data.length;i++){
            //alert(data[i].Kode_Jenis);
            html += '<option value='+data[i].Kode_Jenis+'>'+data[i].Nama_Jenis+'</option>';
          }
          //alert(html);
          $('#Kode_Jenis').html(html);
        }
      });
    });

    $('#Kode_Kategori_E').change(function() {
      /* Act on the event */
      var Kode_Kategori_E = $('#Kode_Kategori_E').val();
      //alert(Kode_Kategori_E);
      $.ajax({
        type:"POST",
        url:"<?php echo base_url().'index.php/c_data/c_masteritem/ViewOptJenis_E'; ?>",
        dataType:"JSON",
        async: false,
        data: {Kode_Kategori_E:Kode_Kategori_E},
        error: function (request, error, data) {
          console.log(arguments);
          alert(" Can't do because: " + error + " and data "+data);
        },
        success: function(data){
          //alert(data[0].UserID);
          //alert(data.length);
          var html = '<option value=""></option>';
          //alert(data.length);
          for(var i=0;i<data.length;i++){
            //alert(data[i].Kode_Jenis);
            html += '<option value='+data[i].Kode_Jenis+'>'+data[i].Nama_Jenis+'</option>';
          }
          $('#Kode_Jenis_E').html(html);
        }
      });
    });

    /* Kode Barcode Barang ........................
    $('#Kode_Barcode_Brg').change(function() {
      var ajaxRequest;
      var Kode_Barcode_Brg = $('#Kode_Barcode_Brg').val();
      //alert(Nama_Kategori);
      try
      {
        x = new XMLHttpRequest();
      }
      catch (e)
      {
        alert("Something is wrong");
        return false;
      }
      x.onreadystatechange = function()
      {
        //alert(x.readyState);
        if(x.readyState == 4 && x.status == 200)
        {
          recordcount = x.responseText;
          if(recordcount > 0)
          {
            swal("Maaf Kode Barcode Barang "+Kode_Barcode_Brg+" sudah digunakan");
            $('#Kode_Barcode_Brg').val('');
            $('#Kode_Barcode_Brg').focus();
          }
        }
      }

      x.open("GET", "<?php //echo base_url().'index.php/c_data/c_masteritem/getTheBarcode/';?>" + Kode_Barcode_Brg, true);
      x.send(null);
    });
    End Kode Barcode ...... */

    $('#Nama_Brg').change(function() {
      /* Act on the event */
      var ajaxRequest;
      var Nama_Brg = $('#Nama_Brg').val();
      //alert(Nama_Kategori);
      try
      {
        x = new XMLHttpRequest();
      }
      catch (e)
      {
        alert("Something is wrong");
        return false;
      }
      x.onreadystatechange = function()
      {
        //alert(x.readyState);
        if(x.readyState == 4 && x.status == 200)
        {
          recordcount = x.responseText;
          if(recordcount > 0)
          {
            swal("Maaf Nama Barang "+Nama_Brg+" sudah digunakan");
            $('#Nama_Brg').val('');
            $('#Nama_Brg').focus();
          }
        }
      }

      x.open("GET", "<?php echo base_url().'index.php/c_data/c_masteritem/getTheNama_Brg/';?>" + Nama_Brg, true);
      x.send(null);
    });

    /* Kode Barcode Barang .............
    $('#Kode_Barcode_Brg_E').change(function() {
      var ajaxRequest;
      var Kode_Barcode_Brg_E = $('#Kode_Barcode_Brg_E').val();
      //alert(Nama_Kategori);
      try
      {
        x = new XMLHttpRequest();
      }
      catch (e)
      {
        alert("Something is wrong");
        return false;
      }
      x.onreadystatechange = function()
      {
        //alert(x.readyState);
        if(x.readyState == 4 && x.status == 200)
        {
          recordcount = x.responseText;
          if(recordcount > 0)
          {
            swal("Maaf Kode Barcode Barang "+Kode_Barcode_Brg_E+" sudah digunakan");
            $('#Kode_Barcode_Brg_E').val('');
            $('#Kode_Barcode_Brg_E').focus();
          }
        }
      }

      x.open("GET", "<?php //echo base_url().'index.php/c_data/c_masteritem/getTheBarcode/';?>" + Kode_Barcode_Brg_E, true);
      x.send(null);
    });
    End Kode Barcode Barang .... */

    $('#Nama_Brg_E').change(function() {
      /* Act on the event */
      var ajaxRequest;
      var Nama_Brg_E = $('#Nama_Brg_E').val();
      //alert(Nama_Kategori);
      try
      {
        x = new XMLHttpRequest();
      }
      catch (e)
      {
        alert("Something is wrong");
        return false;
      }
      x.onreadystatechange = function()
      {
        //alert(x.readyState);
        if(x.readyState == 4 && x.status == 200)
        {
          recordcount = x.responseText;
          if(recordcount > 0)
          {
            swal("Maaf Nama Barang "+Nama_Brg_E+" sudah digunakan");
            $('#Nama_Brg_E').val('');
            $('#Nama_Brg_E').focus();
          }
        }
      }

      x.open("GET", "<?php echo base_url().'index.php/c_data/c_masteritem/getTheNama_Brg/';?>" + Nama_Brg_E, true);
      x.send(null);
    });

    $('#Harga_Belix').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9]/g,'');
      var Hrg_Beli =  $('#Harga_Belix').val();
      $('#Harga_Beli').val(Hrg_Beli);
    });

    $('#Harga_Belix').change(function(event) {
      /* Act on the event */
      $('#Harga_Belix').val(formatRupiah(this.value, "Rp. "))
    });

    $('#Harga_Jualx').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9]/g,'');
      var Hrg_Jual =  $('#Harga_Jualx').val();
      $('#Harga_Jual').val(Hrg_Jual);
    });

    $('#Harga_Jualx').change(function(event) {
      /* Act on the event */
      $('#Harga_Jualx').val(formatRupiah(this.value, "Rp. "))
    });

    $('#Harga_Belix_E').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9]/g,'');
      var Hrg_Beli_E =  $('#Harga_Belix_E').val();
      $('#Harga_Beli_E').val(Hrg_Beli_E);
    });

    $('#Harga_Belix_E').change(function(event) {
      /* Act on the event */
      $('#Harga_Belix_E').val(formatRupiah(this.value, "Rp. "))
    });

    $('#Harga_Jualx_E').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9]/g,'');
      var Hrg_Jual_E =  $('#Harga_Jualx_E').val();
      $('#Harga_Jual_E').val(Hrg_Jual_E);
    });

    $('#Harga_Jualx_E').change(function(event) {
      /* Act on the event */
      $('#Harga_Jualx_E').val(formatRupiah(this.value, "Rp. "))
    });

    $('#Stok').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9.]/g,'');
    });

    $('#Stok_Minimal').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9.]/g,'');
    });

    $('#Stok_Maximal').keyup(function() {
      /* Act on the event */
      this.value = this.value.replace(/[^0-9.]/g,'');
    });

});
</script>

</body>
</html>
