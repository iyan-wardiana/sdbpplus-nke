<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 14 Februari 2018
	* File Name		= r_purchasereq.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$PRJSCATEG  = $this->session->userdata('PRJSCATEG');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateM/$Start_DateD/$Start_DateY";	
$End_Date 		= "$Start_DateM/$Start_DateD/$Start_DateY";	

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$getSupplier 	= "SELECT A.SPLCODE, A.SPLDESC FROM tbl_supplier A WHERE SPLSTAT = 1 ORDER BY A.SPLDESC";
$qSupplier 		= $this->db->query($getSupplier)->result();

if($PRJSCATEG == 1) // 1. Kontraktor, 2. Manufacture
	$ADDQRY		= "A.PRJCODE";
else
	$ADDQRY		= "A.PRJCODE_HO";

$getproject   	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
          			WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject     	= $this->db->query($getproject)->result();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          	$vers     = $this->session->userdata['vers'];

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
			if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
			if($TranslCode == 'SPPCode')$SPPCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'ViewType')$ViewType = $LangTransl;
			if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
			if($TranslCode == 'Excel')$Excel = $LangTransl;
		endforeach;
		
		if($LangID = 'IND')
		{
			$alert1	= "Anda belum menentukan anggaran / proyek.";
		}
		else
		{
			$alert1	= "You haven't set a budget / project yet.";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $mnName; ?>
                <small><?php //echo $h2_title; ?></small>
              </h1>
              <?php /*?><ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Data tables</li>
              </ol><?php */?>
        </section>

        <section class="content">
            <div class="box box-primary">
            	<div class="box-body chart-responsive">
                	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return target_popup(this)">
                    	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName ?></label>
                            <div class="col-sm-10">
                            	<select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ProjectName; ?>">
                                	<option value="0"> --- </option>
                                	<option value="All"> Semua </option>
        							<?php
                                        foreach($qProject as $row_1) :
                                            $PRJCODE	= $row_1->PRJCODE;
                                            $PRJNAME	= $row_1->PRJNAME;
                                            ?>
                                            <option value="<?php echo $PRJCODE; ?>">
                                                <?php echo $PRJCODE." ".$PRJNAME; ?>
                                            </option>
                                           	<?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?></label>
                            <div class="col-sm-10">
                            	<div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="Start_Date" class="form-control pull-left" id="datepicker" value="<?php echo $Start_Date; ?>" size="10" style="width:150px" >
                            	</div>
                            </div>
                        </div>
                    	<div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate ?></label>
                            <div class="col-sm-10">
                            	<div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="End_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $Start_Date; ?>" size="10" style="width:150px" >
                            	</div>
                            </div>
                        </div>
                    	<div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                            	<select name="RepStatus" id="RepStatus" class="form-control select2" >
                                	<option value="All">--- <?php echo $All; ?> ---</option>
                                	<option value="1"> New </option>
                                	<option value="2"> Confirmed </option>
                                	<option value="3"> Approved </option>
                                	<option value="4"> Revised </option>
                                	<option value="5"> Rejected </option>
                                	<option value="6"> Closed </option>
                                	<option value="7"> Awaiting </option>
                                	<option value="9"> Void </option>
                                </select>
                            </div>
                        </div>
                    	<div class="form-group" style="display:none">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?></label>
                            <div class="col-sm-10">
                            	<label>
                                <input type="radio" name="CFType" id="CFType1" value="1" checked /> 
                                <?php echo $Summary ?> <br />
                                <input type="radio" name="CFType" id="CFType2" value="2" /> 
                          		<?php echo $Detail ?>                     </label>
                            </div>
                        </div>
                    	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType ?></label>
                            <div class="col-sm-10">
                            	<label>
                                <input type="radio" name="viewType" value="0" class="flat-red" checked>
                                <?php echo $WebViewer ?> &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="viewType" value="1" class="flat-red">
                              	<?php echo $Excel ?>         </label>
                            </div>
                        </div>
                    	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                            	<button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
                            </div>
                        </div>
                    </form>
            	</div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
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
	    $('#datepicker').datepicker({
	      autoclose: true
	    });

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

	    //Date picker
	    $('#datepicker4').datepicker({
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
	
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		PRJCODE	= document.getElementById('PRJCODE').value;
		if(PRJCODE == 0)
        {
            swal ( "" ,  "<?php echo $alert1; ?>" ,  "warning" )
            return false;
        }
		
		title = '<?php echo $mnName; ?>';
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	var url = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
	function exporttoexcel()
	{
		window.open(url,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
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