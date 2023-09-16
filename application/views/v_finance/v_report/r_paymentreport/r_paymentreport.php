<?php
/* 
   * Author		= Dian Hermanto
  	* Create Date	= 14 Agustus 2018
  	* File Name	= r_invselect.php
  	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
$Start_Date 	= date('d/m/Y');
$End_Date 		= date('d/m/Y');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();

$getSupplier 	= "SELECT A.SPLCODE, A.SPLDESC FROM tbl_supplier A WHERE SPLSTAT = 1 ORDER BY A.SPLDESC";
$qSupplier 		= $this->db->query($getSupplier)->result();
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
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
            if($TranslCode == 'AccountName')$AccountName = $LangTransl;
            if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'DateUntilto')$DateUntilto = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'ViewType')$ViewType = $LangTransl;
			if($TranslCode == 'Summary')$Summary = $LangTransl;
			if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
			if($TranslCode == 'Excel')$Excel = $LangTransl;

		endforeach;
		
		if($LangID = 'IND')
		{
			$alert1	= "Silahkan pilih satu atau lebih proyek.";
            $alert2 = "Silahkan pilih satu atau lebih supplier.";
            $alert3 = "Silahkan pilih satu atau lebih Kode Akun.";
		}
		else
		{
			$alert1	= "Please select one or all project.";
			$alert2	= "Please select one or all supplier.";
            $alert3 = "Please select one or all Account Code.";
		}

    $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
        	<h1>
            <?php echo $mnName; ?>
          	</h1>
        </section>

	     <section class="content">
	         <div class="box box-primary">
                <div class="box-body chart-responsive">
                	<form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Prjnm ?></label>
                            <div class="col-sm-10">
    							<select name="PRJCODE[]" id="PRJCODE" multiple class="form-control select2" data-placeholder="&nbsp;<?php echo $Prjnm; ?>" >
                                <option value="All"> Semua </option>
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
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName; ?></label>
                            <div class="col-sm-10">
                                <select name="SPLCODE[]" id="SPLCODE" class="form-control select2" multiple>
                                    <option value="All"> Semua </option>
                                    <?php 
                                        // get supplier/Employee
                                            $SPL_NAME       = "";
                                            $s_spl          =  "-- SELECT Emp_ID AS SPL_CODE, CONCAT(First_Name, ' ', Last_Name) AS SPL_NAME 
                                                                -- FROM tbl_employee WHERE Emp_Status = 1
                                                                -- UNION
                                                                SELECT SPLCODE AS SPL_CODE, SPLDESC AS SPL_NAME FROM tbl_supplier WHERE SPLSTAT = 1 ORDER BY SPL_NAME ASC";
                                            $r_spl          = $this->db->query($s_spl)->result();
                                            foreach($r_spl as $rw_spl) :
                                                $SPL_CODE   = $rw_spl->SPL_CODE;
                                                $SPL_NAME   = $rw_spl->SPL_NAME;
                                                ?>
                                                    <option value="<?php echo $SPL_CODE; ?>"><?php echo "$SPL_CODE - $SPL_NAME"; ?></option>
                                                <?php
                                            endforeach;
                                    ?>     
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $AccountName ?></label>
                            <div class="col-sm-10">
                            	<select name="selAccount[]" id="selAccount" class="form-control select2" multiple data-placeholder="&nbsp;<?php echo $AccountName; ?>" >
                                    <option value="All"> Semua </option>
	        						<?php
	                                    $sqlDataACC 	= "SELECT DISTINCT
	                                                            A.Acc_ID, 
	                                                            A.Account_Number, 
	                                                            A.Account_Nameen as Account_Name,
	                                                            A.Account_Category,
	                                                            A.Account_Class,			
	                                                            A.currency_ID
	                                                        FROM tbl_chartaccount A
	                                                            INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
	                                                        WHERE A.Account_Class IN (3,4)
	                                                            AND A.Currency_id = 'IDR'
	                                                            Order by A.Account_Category, A.Account_Number AND A.PRJCODE = '$ISHO_PRJ'";
	                                        $resDataACC 	= $this->db->query($sqlDataACC)->result();
	                                        foreach($resDataACC as $rowDACC) :
	                                            $Acc_ID1 		= $rowDACC->Acc_ID;
	                                            $Account_Number = $rowDACC->Account_Number;
	                                            $Account_Name	= $rowDACC->Account_Name;
	                                        ?>
	                                            <option value="<?php echo $Account_Number; ?>"><?php echo "$Acc_ID1 - $Account_Name";?></option>
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
                                    <input type="text" name="End_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $Start_Date; ?>" size="10" style="width:120px" >
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

    $("#PRJCODE").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 'All')
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#PRJCODE option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#PRJCODE option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#PRJCODE option:nth-child(1)").prop("disabled", false);
        }
    });

    $("#SPLCODE").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 'All')
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#SPLCODE option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#SPLCODE option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#SPLCODE option:nth-child(1)").prop("disabled", false);
        }
    });

    $("#selAccount").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 'All')
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#selAccount option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#selAccount option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#selAccount option:nth-child(1)").prop("disabled", false);
        }
    });

  });
</script>

<script>
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
        PRJCODE     = document.getElementById('PRJCODE').value;
        SPLCODE     = document.getElementById('SPLCODE').value;
        selAccount  = document.getElementById('selAccount').value;

        if(PRJCODE == 0)
        {
            swal ( "" ,  "<?php echo $alert1; ?>" ,  "warning" )
            return false;
        }

        if(SPLCODE == 0)
        {
            swal ( "" ,  "<?php echo $alert2; ?>" ,  "warning" )
            return false;
        }

        if(selAccount == 0)
        {
            swal ( "" ,  "<?php echo $alert3; ?>" ,  "warning" )
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