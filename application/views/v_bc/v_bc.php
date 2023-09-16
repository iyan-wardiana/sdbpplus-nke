<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 07 Agustus 2023
	* File Name		= v_bc.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 		= $this->session->userdata('appName');
$appBody 		= $this->session->userdata['appBody'];
$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$s_app 	= "SELECT app_stat FROM tappname";
$r_app 	= $this->db->query($s_app)->result();
foreach($r_app as $rw_app) :
	$app_stat = $rw_app->app_stat;		
endforeach;

$img_filenameX 	= "";
$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$DefEmp_ID'";
$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$img_filenameX 		= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID.'/'.$img_filenameX);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css';?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <?php
        $this->load->view('template/mna');
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
        
        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
			<h1>
			<?php echo $mnName; ?>
			</h1>
		</section>

        <section class="content">
        	<form class="form-horizontal" name="frm" id="frm" method="post" action="" onSubmit="return checkForm()">
	            <div class="row">
	                <div class="col-md-3">
				      	<div class="row">
				      		<div class="col-md-12">
				                <div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="display: none;">&times;</button>
					                <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
					                Fitur ini digunakan untuk mengirim pesan secara bersamaan ke seluruh pengguna aktif di SdBP+ tanpa terkecuali. Silahkan masukan pesan yang akan dikirim sesuai dengan kepentingan.
					            </div>
					        </div>
					    </div>
	                    <div class="box box-success">
	                        <div class="box-header with-border" style="display: none;">
	                            <h3 class="box-title">Data Pengunci Transaksi</h3>
	                        </div>
	                        <div class="box-body">
				                <div class="row">
				                	<?php
			                    		$compName 	= "-";
			                    		$s_Empl		= "SELECT CONCAT(First_Name,' ', Last_Name) AS compName, Birth_Place, Date_Of_Birth, Pos_Code
			                    						FROM tbl_employee
			                    						WHERE Emp_ID = '$DefEmp_ID' LIMIT 1";
										$r_Empl 	= $this->db->query($s_Empl)->result();
										foreach($r_Empl as $rw_Empl) :
											$compName 	= $rw_Empl->compName;
											$bPlace 	= $rw_Empl->Birth_Place;
											$bDate 		= strftime('%d %b %Y', strtotime($rw_Empl->Date_Of_Birth));
											$PosCode 	= $rw_Empl->Pos_Code;

											$POSS_NAME 	= "-";			
											$s_possC	= "SELECT POSS_NAME, POSS_DESC FROM tbl_position_str WHERE POSS_CODE = '$PosCode' LIMIT 1";
											$r_possC 	= $this->db->query($s_possC)->result();
											foreach($r_possC as $rw_possC) :
												$POSS_NAME 	= $rw_possC->POSS_NAME;
												$POSS_DESC 	= $rw_possC->POSS_DESC;
											endforeach;
										endforeach;
				                	?>
					                <div class="col-md-5" style="text-align: center;">
					                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
					                </div>

					                <div class="col-md-7">
					                  	<div class="row">
					                    	<div class="col-md-12">
					                    		<?php echo "<strong>Nama Lengkap</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$compName"; ?>
					                    	</div>
					                    	<div class="col-md-12">
					                    		<?php echo "<strong>TTL</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$bPlace, $bDate"; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<strong>Posisi</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$POSS_NAME"; ?>
					                    	</div>
				                    	</div>
				                    </div>
					            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-9">
	                    <div class="box box-primary">
	                        <div class="box-header with-border">
	                            <h3 class="box-title">Pesan Broadcast</h3>
	                        </div>
	                        <div class="box-body">
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">Judul</label>
				                    <div class="col-sm-10">
	                                    <input type="text" class="form-control" name="BC_TITLE" id="BC_TITLE" value="" maxlength="40" placeholder="max 50 char" />
	                                </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">Isi Pesan</label>
				                    <div class="col-sm-10">
	                                    <textarea name="BC_CONTENT" id="compose-textarea" class="form-control" style="height: 150px"></textarea>
	                                </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
				                    <div class="col-sm-10">
	                                    <a class="btn btn-primary" id="btnSave" onclick="sendBC();" title="Kirim">
											<i class="fa fa-send"></i>&nbsp;&nbsp;&nbsp;Kirim
										</a>
	                                </div>
				                </div>
				            </div>
				        </div>
				    </div>
	            </div>

	            <div class="row">
					<div class="col-md-12">
						<div class="search-table-outter">
							<table id="example" class="table table-bordered table-striped" width="100%">
								<thead>
									<tr style="background:#CCCCCC">
										<th width="5%" style="text-align: center;">No.</th>
										<th width="10%" style="text-align: center;">Tgl. Kirim</th>
										<th width="25%" style="text-align: center;">Judul</th>
										<th width="45%" style="text-align: center;">Isi Broadcast</th>
										<th width="10%" style="text-align: center;">Pengirim</th>
										<th width="5%" style="text-align: center;">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</form>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                //if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

<script>
    $(function () {
    //Add text editor
       $("#compose-textarea").wysihtml5();
    });

    $(function () {
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
        $.fn.datepicker.defaults.format = "dd/mm/yyyy";
        $('#datepicker').datepicker({
            autoclose: true,
            startDate: '-3d',
            endDate: '+0d'
        });

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true,
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
	    $('#example').DataTable(
		{
	        "destroy": true,
	        "processing": true,
	        "serverSide": true,
	        //"scrollX": false,
			"dom": "lfrt",
	        "autoWidth": true,
	        "filter": true,
	        "ajax": "<?php echo site_url('bcmsg/get_AllData/?id=')?>",
	        "type": "POST",
			"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			// "lengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,1,4,5], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						  ],
	    	"order": [[ 1, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});

    function sendBC()
    {
        var BC_TITLE    = document.getElementById('BC_TITLE').value;
        var BC_CONTENT  = document.getElementById('compose-textarea').value;
        var isSend 		= 1;

        if(BC_TITLE == '')
        {
        	isSend 		= 0;

        	swal('Judul broadcast tidak boleh kosong!',
            {
                icon: "warning",
            })
            .then(function()
        	{
        		swal.close();
       			document.getElementById('BC_TITLE').focus();
        	});
            return false;
        }

        if(BC_CONTENT == '')
        {
        	isSend 		= 0;
        	
        	swal('Isi broadcast tidak boleh kosong!',
            {
                icon: "warning",
            })
            .then(function()
        	{
        		swal.close();
       			document.getElementById('compose-textarea').focus();
        	});
            return false;
        }

        if(isSend == 1)
        {
        	var formData 	= 	{
        							BC_TITLE 	: BC_TITLE,
        							BC_CONTENT 	: BC_CONTENT
        						};

        	$.ajax({
	            type: 'POST',
	            url: "<?php echo site_url('bcmsg/saveBC')?>",
	            data: formData,
	            success: function(response)
	            {
	            	swal(response,
					{
						icon: "warning",
					})
					.then(function()
		            {
		            	$('#frm').trigger("reset");
		            	$('#example').DataTable().ajax.reload();
		                swal.close();
		            });
	            }
	        });
        }
    }
</script>

<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
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
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js';?>"></script>