<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 14 Agustus 2023
 	* File Name		= r_opintreport.php
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
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";
$Start_Date 	= "$Start_DateM/$Start_DateD/$Start_DateY";

$getproject 	= "SELECT A.PRJCODE, A.PRJNAME, A.PRJDATE FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT PRJCODE FROM tbl_wo_header WHERE WO_CATEG = 'T' AND WO_STAT NOT IN (5,9)) ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();
foreach($qProject as $row) :
    $PRJCODE1   = $row->PRJCODE;
    $PRJDATE    = $row->PRJDATE;
endforeach;

$getDTRX      = "SELECT DATEDIFF(NOW(),PRJDATE) AS DateTRX 
                    FROM tbl_project ORDER BY PRJDATE ASC LIMIT 1";
$resDTRX      = $this->db->query($getDTRX);
$DateTRX      = '';
if($resDTRX->num_rows() > 0)
{
    foreach($resDTRX->result() as $rDTRX):
        $DateTRX    = $rDTRX->DateTRX;
    endforeach;
}

$getSupplier 	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC FROM tbl_wo_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                    WHERE A.WO_CATEG = 'T' AND A.WO_STAT NOT IN (5,9) AND B.SPLSTAT = 1 ORDER BY B.SPLDESC";
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
			if($TranslCode == 'SupplierName')$Splnm = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'DateUntilto')$DateUntilto = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Summary')$Summary = $LangTransl;
			if($TranslCode == 'Detail')$Detail = $LangTransl;
			if($TranslCode == 'ViewType')$ViewType = $LangTransl;
			if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
			if($TranslCode == 'Excel')$Excel = $LangTransl;
			if($TranslCode == 'Periode')$Periode = $LangTransl;

		endforeach;
		
		if($LangID = 'IND')
		{
			$alert1	= "Silahkan pilih satu atau lebih proyek.";
            $alert2 = "Silahkan pilih satu atau lebih supplier.";
            $AllPrj = "Semua Proyek";
            $AllSpl = "Semua Pemasok";
		}
		else
		{
			$alert1	= "Please select one or all project.";
			$alert2	= "Please select one or all supplier.";
            $AllPrj = "All Project";
            $AllSpl = "All Supplier";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
        	<h1>
            <?php echo $mnName; ?>
            <small><?php echo $h3_title; ?></small>
          	</h1>
        </section>

		<section class="content">
		    <div class="box box-primary">
                <div class="box-body chart-responsive">
                	<form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
			   	  		<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Prjnm ?></label>
                            <div class="col-sm-10">
								<select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;<?php echo $Prjnm; ?>" >
                                    <option value="0" >---</option>
                                    <!-- <option value="1" > <?php // echo $AllPrj;?> </option> -->
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
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Splnm ?></label>
                            <div class="col-sm-10">
                              	<select name="SPLCODE[]" id="SPLCODE" multiple class="form-control select2" data-placeholder="&nbsp;<?php echo $Splnm; ?>" >
                                    <option value="1"> <?php echo $AllSpl; ?> </option>
	                              	<?php
	                              		foreach($qSupplier as $rowSPL) :
	                                      	$SPLCODE  = $rowSPL->SPLCODE;
	                                      	$SPLDESC  = $rowSPL->SPLDESC;
	                                  		?>
	                                      		<option value="<?php echo $SPLCODE; ?>" ><?php echo "$SPLCODE - $SPLDESC"; ?></option>
		                                	<?php
		                             	endforeach;
	                              	?>
                          		</select>
                        	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="datePeriod" class="form-control pull-left" id="daterange-btn" style="width:200px">
                                </div>
                                <!-- <select class="form-control pull-left select2" id="monthPeriod" name="monthPeriod">
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">Nopember</option>
                                    <option value="12">Desember</option>
                                </select> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo "No. SPK"; ?></label>
                            <div class="col-sm-10">
                              	<select name="WO_NUM" id="WO_NUM" class="form-control select2" data-placeholder="&nbsp;<?php echo "No. SPK"; ?>" >
                                    <option value=""></option>
                          		</select>
                        	</div>
                        </div>
                        <div class="form-group" style="display: none;">
                        	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                        	<div class="col-sm-10">
                              	<select class="form-control pull-left select2" id="CFType" name="CFType" style="max-width:150px">
                                  	<option value="1">Summary</option>
                                  	<option value="2" selected>Detail</option>
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
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                // 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'All Periode': [moment().subtract(<?=$DateTRX?>, 'days').startOf('month'), moment()]
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
    
    $("#SPLCODE").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 1)
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

    // Get PO_CODE
        $("#daterange-btn").on("change", function(e) {
            let PRJCODE = $("#PRJCODE").val();
            let SPLCODE = $("#SPLCODE").val();
            let datePeriod = $('#daterange-btn').val();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url("c_purchase/c_r3p0rt/getWO") ?>",
                dataType: "JSON",
                data: {PRJCODE:PRJCODE, SPLCODE:SPLCODE, datePeriod:datePeriod},
                success: function(data) {
                    let lnData  = data.length;
                    let dataOpt = '';
                    if(lnData != 0)
                        dataOpt = '<option value="0">---</option>';

                    for(let i=0; i<lnData; i++) {
                        dataOpt += '<option value="'+data[i].WO_NUM+'">'+data[i].WO_CODE+'</option>';
                    }

                    $('#WO_NUM').html(dataOpt);
                }
            });
        });

    
  });
</script>
<script>

    var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		PRJCODE = document.getElementById('PRJCODE').value;
		SPLCODE = document.getElementById('SPLCODE').value;

        if(PRJCODE == '')
        {
            swal ( "" ,  "<?php echo $alert1; ?>" ,  "warning" )
            return false;
        }

        if(SPLCODE == '')
        {
            swal ( "" ,  "<?php echo $alert2; ?>" ,  "warning" )
            return false;
        }

		title = '<?php echo $mnName; ?>';
		w = 1200;
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