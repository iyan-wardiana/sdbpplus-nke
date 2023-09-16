<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Barang</title>
    <link href="<?php echo base_url().'assets/css/bootstrap.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/jquery.dataTables.min.css'?>" rel="stylesheet">
</head>
<body>
 
<div class="container">
    <h1>Data <small>Barang!</small>
        <div class="pull-right"><a class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal_add_new"> Add New</a></div>       
    </h1>
 
    <table class="table table-bordered table-striped" id="mydata">
        <thead>
            <tr>
                <td>Kode Barang</td>
                <td>Nama Barang</td>
                <td>Satuan</td>
                <td>Harga</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($data->result_array() as $i):
                    $barang_id=$i['barang_id'];
                    $barang_nama=$i['barang_nama'];
                    $barang_satuan=$i['barang_satuan'];
                    $barang_harga=$i['barang_harga'];
            ?>
            <tr>
                <td><?php echo $barang_id;?></td>
                <td><?php echo $barang_nama;?></td>
                <td><?php echo $barang_satuan;?></td>
                <td><?php echo 'Rp '.number_format($barang_harga);?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
     
</div>
 
 
    <!-- ============ MODAL ADD BARANG =============== -->
        <div class="modal fade" id="modal_add_new" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 class="modal-title" id="myModalLabel">Add New Barang</h3>
            </div>
            <form class="form-horizontal" method="post" action="<?php echo base_url().'index.php/barang/simpan_barang'?>">
                <div class="modal-body">
 
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Kode Barang</label>
                        <div class="col-xs-8">
                            <input name="kode_barang" class="form-control" type="text" placeholder="Kode Barang..." required>
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Nama Barang</label>
                        <div class="col-xs-8">
                            <input name="nama_barang" class="form-control" type="text" placeholder="Nama Barang..." required>
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Satuan</label>
                        <div class="col-xs-8">
                             <select name="satuan" class="form-control" required>
                                <option value="">-PILIH-</option>
                                <option value="Unit">Unit</option>
                                <option value="Kotak">Kotak</option>
                                <option value="Botol">Botol</option>
                                <option value="Sachet">Sachet</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Dus">Dus</option>
                             </select>
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Harga</label>
                        <div class="col-xs-8">
                            <input name="harga" class="form-control" type="text" placeholder="Harga..." required>
                        </div>
                    </div>
 
                </div>
 
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                    <button class="btn btn-info">Simpan</button>
                </div>
            </form>
            </div>
            </div>
        </div>
    <!--END MODAL ADD BARANG-->
 
<script src="<?php echo base_url().'assets/js/jquery-2.2.4.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/bootstrap.js'?>"></script>
<script src="<?php echo base_url().'assets/js/jquery.dataTables.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/moment.js'?>"></script>
<script>
    $(document).ready(function(){
        $('#mydata').DataTable();
    });
</script>
</body>
</html>