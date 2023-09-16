<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Agustus 2017
 * File Name	= v_task_request.php
 * Location		= -
*/
// $this->load->view('template/head');
setlocale(LC_ALL, 'id-ID', 'id_ID');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
$sysMnt		= $this->session->userdata['sysMnt'];
$LastMntD	= $this->session->userdata['LastMntD'];

$tgl1 = new DateTime($LastMntD);
$tgl2 = new DateTime();
 
$dif1 = $tgl1->diff($tgl2);
$dif2 = $dif1->days;

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;


/*function cut_text($var, $len = 200, $txt_titik = "...") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
	if (strlen ($var) < $len) 
	{ 
		return $var; 
	}
	if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
	{
		return $match [1] . $txt_titik;
	}
	else
	{
		return substr ($var, 0, $len) . $txt_titik;
	}
}*/
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>

	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Title')$Title = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Sender')$Sender = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Warning')$Warning = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$mntWarn1	= "Layanan '1stWeb Assistance' akan segera berakhir pada tanggal : ";
			$mntWarn2	= "Silahkan hubungi kami agar tetap mendapatkan layanan '1stWeb Assistance'.";
			$mntWarn3	= "Layanan '1stWeb Assistance' sudah berakhir per ";
			$mntWarn4	= "Mengapa saya melihat ini?";
		}
		else
		{
			$mntWarn1	= "Sorry, '1stWeb Assistance' services will be finished on : ";
			$mntWarn2	= "Please contact us to get '1stWeb Assistance' services.";
			$mntWarn3	= "Sorry, we have finished '1stWeb Assistance' services per ";
			$mntWarn4	= "Why did I see this message?";
		}
	?>

	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }

		p {
		    word-break: break-all;
		    white-space: normal;
		}
	</style>

	<?php
        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $h2_title; ?>
			    <small><?php echo $mnName; ?></small>
			    <div class="pull-right">
                    <?php
                    	echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>');
                    ?>
					<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
						<i class="glyphicon glyphicon-refresh"></i>
					</button>
	            </div>
			</h1>
		</section>


	    <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-sm-3">
                                <select name="REQUESTER" id="REQUESTER" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value="0"> --- </option>
                                    <?php
                                        /*$s_REQ  = "SELECT DISTINCT A.TASK_REQUESTER, CONCAT(B.First_Name,' ',B.Last_Name) AS REQ_NAME
													FROM tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
													WHERE A.TASK_REQUESTER = '$EMP_ID' OR (TASK_AUTHOR LIKE '%$EMP_ID%' OR TASK_TO LIKE '%$EMP_ID%' OR TASK_TO = 'All') AND TASK_STAT != 99
													ORDER BY REQ_NAME ASC;";*/
                                        $s_REQ  = "SELECT DISTINCT A.TASK_REQUESTER, CONCAT(B.First_Name,' ',B.Last_Name) AS REQ_NAME
													FROM tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
													WHERE TASK_STAT != 99 AND (TASK_REQUESTER LIKE '%$DefEmp_ID%' OR TASK_TO LIKE '%$DefEmp_ID%')
													ORDER BY REQ_NAME ASC;";
                                        $r_REQ  = $this->db->query($s_REQ)->result();
                                        foreach($r_REQ as $rw_REQ) :
                                            $TASK_REQ   = $rw_REQ->TASK_REQUESTER;
                                            $REQ_NAME   = $rw_REQ->REQ_NAME;
                                            ?>
                                            <option value="<?php echo $TASK_REQ; ?>"><?php echo "$REQ_NAME"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="TASK_STAT" id="TASK_STAT" class="form-control select2" onChange="grpData(this.value)">
                                    <option value="0"> --- </option>
                                    <option value="1"> New </option>
                                    <option value="2"> Process </option>
                                    <option value="3"> Closed </option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="TASKD_RSTAT" id="TASKD_RSTAT" class="form-control select2" onChange="grpData(this.value)">
                                    <option value="0"> --- </option>
                                    <option value="1"> Unread </option>
                                    <option value="2"> Read </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                function grpData()
                {
                    var TREQ   = document.getElementById('REQUESTER').value;
                    var TSTAT  = document.getElementById('TASK_STAT').value;
                    var TRSTAT = document.getElementById('TASKD_RSTAT').value;

                    $('#task_list').DataTable(
			    	{
			            "destroy": true,
			            "processing": true,
			            "serverSide": true,
			            //"scrollX": false,
			            "autoWidth": true,
			            "filter": true,
				        "ajax": "<?php echo site_url('c_help/c_t180c2hr/get_AllData/?id='.$DefEmp_ID)?>"+'&TREQ='+TREQ+'&TSTAT='+TSTAT+'&TRSTAT='+TRSTAT,
				        "type": "POST",
						//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
						"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
						"columnDefs": [	{ targets: [3,4,5], className: 'dt-body-center' },
										{ "width": "100px", "targets": [1] }
									  ],
			        	"order": [[ 0, "desc" ]],
						"language": {
				            "infoFiltered":"",
				            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
				        },
					});

					$("#idRefresh1").click(function()
					{
						$('#task_list').DataTable().ajax.reload();
					});
                }
            </script>
			<div class="box">
			    <div class="box-body">
					<?php 
						if($sysMnt == 1 && $tgl1 <= $tgl2)
						{
							?>
					        <div class="alert alert-danger alert-dismissible">
					            <h4><i class="icon fa fa-warning"></i> <?php echo $Warning; ?></h4>
					            <?php echo "$mntWarn3".date('d-M-Y', strtotime($LastMntD)).". $mntWarn2"; ?>
					        </div>
				    		<?php 
						}
						elseif($sysMnt == 1 && $dif2 < 6) 
						{
							?>
					        <div class="alert alert-warning alert-dismissible">
					            <h4><i class="icon fa fa-warning"></i> <?php echo $Warning; ?></h4>
					            <?php echo "$mntWarn1".date('d-M-Y', strtotime($LastMntD)).". $mntWarn2"; ?>
					        </div>
				    		<?php 
						}
					?>
			        <div class="search-table-outter">
			            <table id="task_list" class="table table-bordered table-striped" width="100%">
			                <thead>
			                    <tr>
			                        <th style="vertical-align:middle; text-align:center" width="15%"><?php echo $Code; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="20%"><?php echo $Category; ?></th>
					                <th style="vertical-align:middle; text-align:center" width="50%"><?php echo $Description; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%"><?php echo $Status; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%"><?php echo $Progress; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%">&nbsp;</th>
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
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
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
		var TREQ 	= 0;
		var TSTAT 	= 0;
		var TRSTAT 	= 0;

        $('#task_list').DataTable(
    	{
            "destroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
	        "ajax": "<?php echo site_url('c_help/c_t180c2hr/get_AllData/?id='.$DefEmp_ID)?>"+'&TREQ='+TREQ+'&TSTAT='+TSTAT+'&TRSTAT='+TRSTAT,
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [3,4,5], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						  ],
        	"order": [[ 0, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});
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