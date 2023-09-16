<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 17 Juli 2023
	* File Name		= v_qritase.php
	* Location		= -
*/
// $this->load->view('template/head');

date_default_timezone_set("Asia/Jakarta");

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach; 
if($decFormat == 0)
	$decFormat		= 2;

$PRJNAME		= '';
$PO_RECEIVLOC	= '';
$sql 			= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 		= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME 	= $row ->PRJNAME;
endforeach;

$s_TRUCK		= "SELECT TRUCK_DIM FROM tbl_truck_dim ORDER BY TRUCK_DIM";
$r_TRUCK 		= $this->db->query($s_TRUCK)->result();

$s_UNIT			= "SELECT Unit_Type_Code AS QRIT_UNIT FROM tbl_unittype ORDER BY QRIT_UNIT";
$r_UNIT 		= $this->db->query($s_UNIT)->result();

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$QRIT_NUM 		= "QRIT".$PRJCODE.".".date('ymdHis');

$QRIT_UNIT 		= "M3";
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          	$vers   = $this->session->userdata['vers'];

          	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
          	$rescss = $this->db->query($sqlcss)->result();
          	foreach($rescss as $rowcss) :
              	$cssjs_lnk  = $rowcss->cssjs_lnk;
              	?>
                  	<link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              	<?php
          	endforeach;

          	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
          	$rescss = $this->db->query($sqlcss)->result();
          	foreach($rescss as $rowcss) :
              	$cssjs_lnk1  = $rowcss->cssjs_lnk;
              	?>
                  	<script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              	<?php
          	endforeach;
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>
    
	<?php
		$this->load->view('template/mna');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$LangID 	= $this->session->userdata['LangID'];
		$complName 	= $this->session->userdata['completeName'];

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/truck.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName"; ?>
		    	<small><?php echo $complName; ?></small>
			    <div class="pull-right">
                    <button type="button" id="btn-form" class="btn btn-primary" data-toggle="modal" data-target="#mdl_delItm"><i class="glyphicon glyphicon-plus"></i></button>
	            </div>
		  	</h1>
	    </section>

    	<!-- ============ START MODAL ADD LKH =============== -->
	    	<style type="text/css">
	    		.modal-dialog{
				    position: relative;
				    display: table; /* This is important */ 
				    overflow-y: auto;    
				    overflow-x: auto;
				    width: auto;
				    min-width: 300px;   
				}
	    	</style>
	    	<?php
				$Active1		= "active";
				$Active2		= "";
				$Active3		= "";
				$Active4		= "";
				$Active1Cls		= "class='active'";
				$Active2Cls		= "";
				$Active3Cls		= "";
				$Active4Cls		= "";

				$QRIT_DATE 		= date('d/m/Y');
				$QRIT_DATEV 	= date('Y-m-d');
				$QRIT_DATEV		= strftime('%d %B %Y', strtotime($QRIT_DATEV));
				$QRIT_DATET 	= date('H:i:s');
	    	?>
    		<div class="modal fade" id="mdl_delItm" name='mdl_delItm' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	            <div class="modal-dialog">
		            <div class="modal-content">
		                <div class="modal-body">
							<div class="row">
						    	<div class="col-md-12">
					              	<ul class="nav nav-tabs">
					                    <li id="li1" <?php echo $Active1Cls; ?>>
					                    	<a href="#itm1" data-toggle="tab" >Tambah Ritase</a>
					                    </li>	
					                </ul>
						            <div class="box-body">
						            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
	                                        <div class="form-group">
		                                    	<form method="post" name="frmRIT" id="frmRIT" action="">
							                    	<div class="row">
								                    	<div class="col-md-12">
								                    		<?php echo "<strong>Proyek</strong>"; ?>
								                    	</div>
							                    	</div>
							                    	<div class="row">
								                    	<div class="col-md-12" style="white-space: nowrap;">
								                    		<input type="hidden" name="QRIT_TASK" id="QRIT_TASK" value="add" class="form-control">
								                    		<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?=$PRJCODE?>" class="form-control">
								                    		<input type="text" name="PRJCODEX" id="PRJCODEX" value="<?=$PRJCODE." : ".$PRJNAME;?>" class="form-control" disabled>
								                    	</div>
							                    	</div>
							                    	<br>
							                    	<div class="row">
								                    	<div class="col-md-4">
								                    		<?php echo "<strong>Tanggal / Waktu</strong>"; ?>
								                    		<input type="hidden" name="QRIT_DATE" class="form-control pull-left" id="datepicker1" value="<?=$QRIT_DATE?>">
								                    		<input type="text" name="QRIT_DATEX" class="form-control" id="QRIT_DATEX" value="<?=$QRIT_DATEV." ".$QRIT_DATET." WIB";?>" disabled>
								                    	</div>
								                    	<div class="col-md-3">
								                    		<?php echo "<strong>No. Polisi</strong>"; ?>
											                <input type="text" name="QRIT_NOPOL" id="QRIT_NOPOL" class="form-control">
								                    	</div>
								                    	<div class="col-md-5">
								                    		<?php echo "<strong>Driver</strong>"; ?>
								                    		<input type="text" name="QRIT_DRIVER" id="QRIT_DRIVER" class="form-control">
								                    	</div>
							                    	</div>
							                    	<br>
							                    	<div class="row">
								                    	<div class="col-md-8">
								                    		<?php echo "<strong>Lokasi Tujuan</strong>"; ?>
											                <input type="text" name="QRIT_DEST" id="QRIT_DEST" class="form-control">
								                    	</div>
								                    	<div class="col-md-4">
								                    		<?php echo "<strong>Muatan</strong>"; ?>
								                    		<input type="text" name="QRIT_MATERIAL" id="QRIT_MATERIAL" class="form-control">
								                    	</div>
							                    	</div>
							                    	<br>
							                    	<div class="row">
								                    	<div class="col-md-5">
								                    		<?php echo "<strong>Index Truck</strong>"; ?>
								                    		<select name="QRIT_DIM" id="QRIT_DIM" class="form-control select2" style="width: 100%">
							                                    <option value="0"> --- </option>
							                                    <?php
																	foreach($r_TRUCK as $rw_TRUCK) :
																		$TRUCK_DIM = $rw_TRUCK->TRUCK_DIM;
																		?>
																		<option value="<?=$TRUCK_DIM?>"><?=$TRUCK_DIM?></option>
																		<?php
																	endforeach;
																?>
															</select>
								                    	</div>
								                    	<div class="col-md-4">
								                    		<?php echo "<strong>Volume (M3)</strong>"; ?>
											                <input type="text" name="QRIT_VOL" id="QRIT_VOL" class="form-control">
								                    	</div>
								                    	<div class="col-md-3">
								                    		<?php echo "<strong>Satuan</strong>"; ?>
								                    		<select name="QRIT_UNIT" id="QRIT_UNIT" class="form-control select2" style="width: 100%">
							                                    <option value="0"> --- </option>
							                                    <?php
																	foreach($r_UNIT as $rw_UNIT) :
																		$QRITUNIT = $rw_UNIT->QRIT_UNIT;
																		?>
																		<option value="<?=$QRITUNIT?>"<?php if($QRITUNIT == $QRIT_UNIT) { ?> selected <?php } ?>><?=$QRIT_UNIT?></option>
																		<?php
																	endforeach;
																?>
															</select>
								                    	</div>
							                    	</div>
							                    	<br>
							                    	<div class="row">
								                    	<div class="col-md-12">
								                    		<?php echo "<strong>Deskripsi Tambahan</strong>"; ?>
								                    	</div>
							                    	</div>
							                    	<div class="row">
								                    	<div class="col-md-12" style="white-space: nowrap;">
								                    		<textarea name="QRIT_NOTES" class="form-control" id="QRIT_NOTES" cols="30" placeholder="&nbsp;Catatan tambahan"></textarea>  
								                    	</div>
							                    	</div>
							                    	<br>
								                  	<div class="row">
								                    	<div class="col-md-6">
															<button type="button" class="btn btn-warning" onClick="proc_inp()"><i class="fa fa-save"></i></button>
															<button type="button" id="idCloseDRow" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i></button>
								                    	</div>
							                    	</div>
		                                        </form>
		                                    </div>
		                                </div>
                                  	</div>
                                </div>
                            </div>
		                </div>
			        </div>
			    </div>
			</div>
    	<!-- ============ END MODAL CANCEL ITEM =============== -->

		<section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Proyek</label>
                                <input type="hidden" name="PRJCODEA" id="PRJCODEA" class="form-control" value="<?=$PRJCODE?>">
                                <select name="PRJCODEAX" id="PRJCODEAX" class="form-control select2" disabled>
                                    <option value=""> --- </option>
                                    <?php
                                        $s_BUDG     = "SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID'";
                                        $r_BUDG     = $this->db->query($s_BUDG)->result();
                                        foreach($r_BUDG as $rw_BUDG) :
                                            $PRJCODEA    = $rw_BUDG->proj_Code;
                                            ?>
                                            <option value="<?=$PRJCODEA?>" <?php if($PRJCODEA == $PRJCODE) { ?> selected <?php } ?>><?=$PRJCODEA?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">No. Kendaraan</label>
                                <select name="QRIT_NOPOLA" id="QRIT_NOPOLA" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_NOPOL  = "SELECT DISTINCT QRIT_NOPOL FROM tbl_qritase ORDER BY QRIT_NOPOL ASC;";
                                        $r_NOPOL  = $this->db->query($s_NOPOL)->result();
                                        foreach($r_NOPOL as $rw_NOPOL) :
                                            $QRIT_NOPOL    = $rw_NOPOL->QRIT_NOPOL;
                                            ?>
                                            <option value="<?php echo $QRIT_NOPOL; ?>"><?php echo "$QRIT_NOPOL"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Driver</label>
                                <select name="QRIT_DRIVERA" id="QRIT_DRIVERA" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_DRIVER  = "SELECT DISTINCT QRIT_DRIVER FROM tbl_qritase ORDER BY QRIT_DRIVER ASC;";
                                        $r_DRIVER  = $this->db->query($s_DRIVER)->result();
                                        foreach($r_DRIVER as $rw_DRIVER) :
                                            $QRIT_DRIVER    = $rw_DRIVER->QRIT_DRIVER;
                                            ?>
                                            <option value="<?php echo $QRIT_DRIVER; ?>"><?php echo "$QRIT_DRIVER"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                function grpData()
                {
			    	var PRJCODE 	= document.getElementById('PRJCODEA').value;
			        var QRIT_NOPOL  = document.getElementById('QRIT_NOPOL').value;
			        var QRIT_DRIVER = document.getElementById('QRIT_DRIVER').value;
			        
			        $('#example').DataTable(
			    	{
			            "destroy": true,
			            "processing": true,
			            "serverSide": true,
			            //"scrollX": false,
						"dom": "lfrt",
			            "autoWidth": true,
			            "filter": true,
				        "ajax": "<?php echo site_url('c_qritase/c_qritase/get_AllData/?id=')?>"+'&PRJCODE='+PRJCODE+'&QRIT_NOPOL='+QRIT_NOPOL+'&QRIT_DRIVER='+QRIT_DRIVER,
				        "type": "POST",
						"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
						// "lengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]],
						"columnDefs": [	{ targets: [0,3], className: 'dt-body-center' },
										{ targets: [6], className: 'dt-body-right' },
										{ "width": "100px", "targets": [1] }
									  ],
			        	"order": [[ 1, "desc" ]],
						"language": {
				            "infoFiltered":"",
				            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
				        },
					});
                }
            </script>
			<div class="box">
			    <div class="box-body">
			        <div class="search-table-outter">
			            <table id="example" class="table table-bordered table-striped" width="100%">
			                <thead>
			                    <tr>
			                        <th style="vertical-align:middle; text-align:center" width="5%">&nbsp;</th>
			                        <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Tgl. Kirim</th>
			                        <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Tgl. Terima</th>
			                        <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center">No. Polisi</th>
			                        <th width="20%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Driver</th>
			                        <th width="30%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Lokasi Tujuan</th>
			                        <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Volume</th>
			                        <th width="5%" nowrap="nowrap" style="vertical-align:middle; text-align:center">&nbsp;</th>
			                  	</tr>
			                </thead>
			                <tbody>
			                </tbody>
			                <tfoot>
			                </tfoot>
			            </table>
			        </div>
			    </div>
			</div>

			<div class="row">
				<div class="col-md-12" id="idprogbarXY" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXY" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>

        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
	</body>
</html>

<script>
    $(function ()
    {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            },
            function (start, end) {
              $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true
        });

        //Date picker
        $('#datepicker2').datepicker({
          autoclose: true
        });

        //Date picker
        $('#datepicker3').datepicker({
          autoclose: true
        });

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
    });

	$(document).ready(function()
	{
    	var PRJCODE 	= document.getElementById('PRJCODEA').value;
        var QRIT_NOPOL  = document.getElementById('QRIT_NOPOL').value;
        var QRIT_DRIVER = document.getElementById('QRIT_DRIVER').value;
        
        $('#example').DataTable(
    	{
            "destroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
			"dom": "lfrt",
            "autoWidth": true,
            "filter": true,
	        "ajax": "<?php echo site_url('c_qritase/c_qritase/get_AllData/?id=')?>"+'&PRJCODE='+PRJCODE+'&QRIT_NOPOL='+QRIT_NOPOL+'&QRIT_DRIVER='+QRIT_DRIVER,
	        "type": "POST",
			"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			// "lengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,3], className: 'dt-body-center' },
							{ targets: [6], className: 'dt-body-right' },
							{ "width": "100px", "targets": [1] }
						  ],
        	"order": [[ 1, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});

	function proc_inp()
	{
		var QRIT_TASK		= document.getElementById('QRIT_TASK').value;
		var PRJCODE			= document.getElementById('PRJCODE').value;
		var QRIT_NOPOL		= document.getElementById('QRIT_NOPOL').value;
		var QRIT_DRIVER		= document.getElementById('QRIT_DRIVER').value;
		var QRIT_DEST		= document.getElementById('QRIT_DEST').value;
		var QRIT_MATERIAL	= document.getElementById('QRIT_MATERIAL').value;
		var QRIT_DIM		= document.getElementById('QRIT_DIM').value;
		var QRIT_VOL		= document.getElementById('QRIT_VOL').value;
		var QRIT_UNIT		= document.getElementById('QRIT_UNIT').value;
		var QRIT_NOTES		= document.getElementById('QRIT_NOTES').value;

		var formData 		= {
								QRIT_TASK 		: QRIT_TASK,
								PRJCODE 		: PRJCODE,
								QRIT_NOPOL 		: QRIT_NOPOL,
								QRIT_DRIVER 	: QRIT_DRIVER,
								QRIT_DEST		: QRIT_DEST,
								QRIT_MATERIAL 	: QRIT_MATERIAL,
								QRIT_DIM 		: QRIT_DIM,
								QRIT_VOL 		: QRIT_VOL,
								QRIT_UNIT 		: QRIT_UNIT,
								QRIT_NOTES 		: QRIT_NOTES
							};

		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('c_qritase/c_qritase/saveRIT')?>",
            data: formData,
            success: function(response)
            {
            	$('#example').DataTable().ajax.reload();
            }
        });

        document.getElementById("frmRIT").reset();
	}
	
	function printQR(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function deleteDOC(row)
	{
	    swal({
            text: "Anda yakin akan mengahpus dokumen ini?",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlDel'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	swal(response, 
						{
							icon: "success",
						});
		                $('#example').DataTable().ajax.reload();
		            }
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>