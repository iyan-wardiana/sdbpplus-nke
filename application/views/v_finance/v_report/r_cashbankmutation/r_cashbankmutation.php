<?php
/* 
	* Author   	= Dian Hermanto
	* Create Date  = 22 Agustus 2020
	* File Name  	= r_cashbankmutation.php
	* Location   	= -
*/

$this->load->view('template/head');

$appName  	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$PRJSCATEG  = $this->session->userdata('PRJSCATEG');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
  $Display_Rows = $row->Display_Rows;
  $decFormat = $row->decFormat;
  $APPLEV = $row->APPLEV;
endforeach;
$decFormat    = 2;

if($PRJSCATEG == 1) // 1. Kontraktor, 2. Manufacture
	$ADDQRY		= "A.PRJCODE";
else
	$ADDQRY		= "A.PRJCODE_HO";

$DefEmp_ID    	= $this->session->userdata['Emp_ID'];

$Start_DateY  	= date('Y');
$Start_DateM  	= date('m');
$Start_DateD  	= date('d');
$Start_Date   	= date('d/m/Y');
$End_Date     	= date('d/m/Y');

$DefEmp_ID    	= $this->session->userdata['Emp_ID'];

$getproject   	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
          			WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject     	= $this->db->query($getproject)->result();
	
$getCust  		= "SELECT DISTINCT A.CUST_CODE, A.CUST_DESC FROM tbl_sinv_header A WHERE A.SINV_PAYSTAT IN ('NR','HR') ORDER BY A.CUST_DESC";
$qCustomer    	= $this->db->query($getCust)->result();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

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
			if($TranslCode == 'ProjectName')$Prjnm = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'DateUntilto')$DateUntilto = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'ViewType')$ViewType = $LangTransl;
			if($TranslCode == 'Summary')$Summary = $LangTransl;
			if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
			if($TranslCode == 'Excel')$Excel = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
		endforeach;
		
		if($LangID = 'IND')
		{
			$alert1	= "Silahkan pilih satu atau lebih proyek.";
			$alert2	= "Silahkan pilih satu atau lebih pelanggan.";
			$alert3	= "Silahkan pilih kategori laporan.";
		}
		else
		{
			$alert1	= "Please select one or all project.";
			$alert2	= "Please select one or all customer.";
			$alert3	= "Please select Category.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
        	<h1>
                <?php echo $mnName; ?>
                <small><?php //echo $h3_title; ?></small>
          	</h1>
        </section>

		<section class="content">
		    <div class="box box-primary">
                <div class="box-body chart-responsive">
			   	  	<form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
			   	  		<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Budget ?></label>
                            <div class="col-sm-10">
								<select name="PRJCODE[]" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;<?php echo $Prjnm; ?>" >
									<option value="0" > --- </option>
									<option value="All" > <?=$All?> </option>
									<?php
										foreach($qProject as $row) :
											$PRJCODE1   = $row->PRJCODE;
											$PRJNAME  = $row->PRJNAME;
											?>
											 	<option value="<?php echo $PRJCODE1; ?>" ><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
											<?php
										endforeach;
									?>
								</select>
                            </div>
                        </div>
                      	<div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $CustName ?></label>
                            <div class="col-sm-10">
                              	<select name="CUST_CODE[]" id="CUST_CODE" class="form-control select2" data-placeholder="&nbsp;<?php echo $CustName; ?>" >
	                                <option value="0" > --- </option>
									<option value="All" > <?=$All?> </option>
	                              	<?php
	                              		foreach($qCustomer as $rowCUST) :
	                                      	$CUST_CODE  = $rowCUST->CUST_CODE;
	                                      	$CUST_DESC  = $rowCUST->CUST_DESC;
	                                  		?>
	                                      		<option value="<?php echo $CUST_CODE; ?>" ><?php echo "$CUST_DESC"; ?></option>
		                                	<?php
		                             	endforeach;
	                              	?>
                          		</select>
                        	</div>
                        </div>
                      	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate ?></label>
                            <div class="col-sm-10">
                              	<div class="input-group date">
                                	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                	<input type="text" name="Start_Date" class="form-control pull-left" id="datepicker" value="<?php echo $Start_Date; ?>" size="10" style="width:120px" >
                            	</div>
                            </div>
                        </div>
                      	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $DateUntilto ?></label>
                            <div class="col-sm-10">
                              	<div class="input-group date">
                                	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                	<input type="text" name="End_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $End_Date; ?>" size="10" style="width:120px" >
                            	</div>
                            </div>
                        </div>
                      	<div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Category ?></label>
                            <div class="col-sm-10">
                              	<select name="CFType" id="CFType" class="form-control SELECT2" >
			                        <option value="1" ><?php echo $Summary ?></option>
			                        <option value="2" >Detail</option>
			                    </select>
                            </div>
                        </div>
                      	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType ?></label>
                            <div class="col-sm-10">
                              	<input type="radio" name="viewType" id="viewType" class="flat-red" value="0" checked /> 
		                        <?php echo $WebViewer ?>&nbsp;&nbsp;&nbsp;
		                        <input type="radio" name="viewType" id="viewType" class="flat-red" value="1" /> 
		                        <?php echo $Excel ?>
                            </div>
                        </div>
                      	<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                              	<button class="btn btn-primary"><i class="glyphicon glyphicon-export"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
                            </div>
                        </div>
			      	</form>
		      	</div>
		    </div>
		</section>
	</body>
</html>
<script>
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
      autoclose: true
    });
	
	//Date picker
	$('#datepicker1').datepicker({
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
</script>
<script>
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		PRJCODE 	= document.getElementById('PRJCODE').value;
		//CUST_CODE 	= document.getElementById('CUST_CODE').value;

        if(PRJCODE == 0)
        {
            swal ( "" ,  "<?php echo $alert1; ?>" ,  "warning" )
            return false;
        }

        /*if(CUST_CODE == 0)
        {
            swal ( "" ,  "<?php echo $alert2; ?>" ,  "warning" )
            return false;
        }*/

		title = '<?php echo $mnName; ?>';
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	var url1 = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
	function exporttoexcel()
	{
		window.open(url1,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
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