<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 04 Agustus 2022
	* File Name		= v_itempar_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

if($task == "add")
{
	$PITM_CODE	   = "";
	$PITM_PARENT   = "";
	$PITM_ORD 	   = 0;
	$PITM_GROUP	   = "";
	$PITM_LEVEL	   = 1;
	$PITM_NAME     = "";
	$PITM_PATT     = 0;
}
else
{
	$IC_Num 	= $default['IC_Num'];
	$IG_Code 	= $default['IG_Code'];
	$IC_Code 	= $default['IC_Code'];
	$IC_Name 	= $default['IC_Name'];
}
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers 	= $this->session->userdata['vers'];

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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Parent')$Parent = $LangTransl;
			if($TranslCode == 'ItemGroup')$ItemGroup = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo "$Add $mnName"; ?>
			</h1>
		</section>
		<section class="content">	
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-header with-border" style="display:none">               
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Parent; ?></label>
                                    <div class="col-sm-10">
                                      <select name="PITM_PARENT" id="PITM_PARENT" class="form-control select2" style="width: 100%">
                                            <option value="0"> --- </option>
                                            <?php
                                                $s_PAR    = "SELECT PITM_CODE, PITM_LEVEL, PITM_NAME FROM tbl_item_parent ORDER BY PITM_ORD";
                                                $r_PAR    = $this->db->query($s_PAR)->result();
                                                foreach($r_PAR as $rw_PAR) :
                                                    $PITMCODE  = $rw_PAR->PITM_CODE;
                                                    $PITMLEVEL = $rw_PAR->PITM_LEVEL;
                                                    $PITMNAME  = $rw_PAR->PITM_NAME;

                                                    // SPACE
                                                        if($PITMLEVEL == 1)
                                                            $spaceLev   = "";
                                                        elseif($PITMLEVEL == 2)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 3)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 4)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 5)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 6)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 7)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 8)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 9)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 10)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 11)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        elseif($PITMLEVEL == 12)
                                                            $spaceLev   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    ?>
                                                    <option value="<?php echo $PITMCODE; ?>"><?php echo "$spaceLev$PITMCODE : $PITMNAME"; ?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="PITM_CODE" id="PITM_CODE" value="<?php echo $PITM_CODE; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="PITM_NAME" id="PITM_NAME" value="<?php echo $PITM_NAME; ?>">
                                    </div>
                                </div>
		                        <script>
                                    $(document).ready(function()
                                    {
                                        $("#PITM_PARENT").change(function()
                                        {
                                            var PITM_PARENT     = document.getElementById("PITM_PARENT").value;

                                            $.ajax({
                                                url: '<?php echo site_url('c_inventory/c_itp4r/getNCODE/?id=')?>',
                                                type: 'post',
                                                data: {PITM_PARENT:PITM_PARENT},
                                                dataType: 'json',
                                                success:function(response)
                                                {
                                                    document.getElementById("PITM_CODE").value  = response;
                                                }
                                            });
                                        });
                                    });
								</script>
		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">                                
		                                <?php
											if($ISCREATE == 1)
											{
												if($task=='add')
												{
													?>
														<button class="btn btn-primary" id="tblSave">
															<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
												else
												{
													?>
														<button class="btn btn-primary" id="tblUpdate">
															<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
											}
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
										?>
		                            </div>
		                        </div>
		                    </form>
		                </div>
		            </div>
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
	function checkInp(idName, theValue)
	{
		PITM_PARENT	= document.getElementById("PITM_PARENT").value;
		if(PITM_PARENT == 0)
		{
			swal('Induk item tidak boleh kosong.',
            {
                icon:"warning"
            });
			return false;
		}
        
        PITM_CODE   = document.getElementById("PITM_CODE").value;
        if(PITM_CODE == '')
        {
            swal('Induk item tidak boleh kosong.',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById("PITM_CODE").focus();
            });
            return false;
        }
        
        PITM_NAME   = document.getElementById("PITM_NAME").value;
        if(PITM_NAME == '')
        {
            swal('Nama Induk item tidak boleh kosong.',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById("PITM_NAME").focus();
            });
            return false;
        }
	}
</script>

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
