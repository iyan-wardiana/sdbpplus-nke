<?php
/* 
  * Author		= Dian Hermanto
  * Create Date	= 15 Desember 2017
  * File Name	= item_group_form.php
  * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody  = $this->session->userdata['appBody'];

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
	$getC		= "tbl_itemgroup";
	$resC1		= $this->db->count_all($getC);
	$resC 		= $resC1 + 1;
	$len		= strlen($resC);
	if($len==1) $nol="0";		
	$lastNumn	= $nol.$resC;
	$IG_Num 	= "GRP$lastNumn";
	$IG_Code 	= "";
	$IG_Name	= "";
}
else
{
	$IG_Num 	= $default['IG_Num'];
	$IG_Code 	= $default['IG_Code'];
	$IG_Name 	= $default['IG_Name'];
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
        	if($TranslCode == 'Back')$Back = $LangTransl;
        	if($TranslCode == 'GroupCode')$GroupCode = $LangTransl;
        	if($TranslCode == 'GroupName')$GroupName = $LangTransl;
        	if($TranslCode == 'Notes')$Notes = $LangTransl;
        	if($TranslCode == 'Save')$Save = $LangTransl;
        	if($TranslCode == 'Update')$Update = $LangTransl;
        	if($TranslCode == 'Inventory')$Inventory = $LangTransl;
        	if($TranslCode == 'Category')$Category = $LangTransl;
        endforeach;

    $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <?php echo "$Add Group"; ?>
            <small><?php echo $Inventory; ?></small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">	
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body chart-responsive">
                        	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $GroupCode ?> </label>
                                  	<div class="col-sm-10">
                                        <label>
        								<?php
                                            if($task == "add")
                                            {
                                        	?>
                                                <input type="text" class="form-control" name="IG_Num" id="IG_Num" value="<?php echo $IG_Num; ?>" onChange="functioncheck(this.value)" />
                                        	<?php
                                            }
                                            else
                                            {
                                        ?>
                                                <input type="text" class="form-control" name="IG_Num1" id="IG_Num1" value="<?php echo $IG_Num; ?>" disabled>
                                                <input type="hidden" class="form-control" name="IG_Num" id="IG_Num" value="<?php echo $IG_Num; ?>">
                                        <?php
                                            }
                                        ?>
                                        </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
                                        <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
                                  	</div>
                                </div>
                                <script>
        							function functioncheck(myValue)
        							{
        								var ajaxRequest;
        								try
        								{
        									ajaxRequest = new XMLHttpRequest();
        								}
        								catch (e)
        								{
        									swal("Something is wrong");
        									return false;
        								}
        								ajaxRequest.onreadystatechange = function()
        								{
        									if(ajaxRequest.readyState == 4)
        									{
        										recordcount = ajaxRequest.responseText;
        										if(recordcount > 0)
        										{
        											document.getElementById('CheckThe_Code').value= recordcount;
        											document.getElementById("isHidden").innerHTML = ' Translate code already exist ... !';
        											document.getElementById("isHidden").style.color = "#ff0000";
        											if (document.getElementById("tblSave") != null)
        											{
        												document.getElementById("tblSave").disabled = true;
        											}
        											if (document.getElementById("tblUpdate") != null)
        											{
        												document.getElementById("tblUpdate").disabled = true;
        											}
        										}
        										else
        										{
        											document.getElementById('CheckThe_Code').value= recordcount;
        											document.getElementById("isHidden").innerHTML = ' Translate Code : OK .. !';
        											document.getElementById("isHidden").style.color = "green";
        											if (document.getElementById("tblSave") != null)
        											{
        												document.getElementById("tblSave").disabled = false;
        											}
        											if (document.getElementById("tblUpdate") != null)
        											{
        												document.getElementById("tblUpdate").disabled = false;
        											}
        										}
        									}
        								}
        								var IG_Num = document.getElementById('IG_Num').value;
        								
        								ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_inventory/c_item_group/getTheCode/';?>" + IG_Num, true);
        								ajaxRequest.send(null);
        							}
        						</script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $GroupName ?> </label>
                                  	<div class="col-sm-10">
                                    	<input type="text" class="form-control" name="IG_Code" id="IG_Code" value="<?php echo $IG_Code; ?>" />
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
                                  	<div class="col-sm-10">
                                        <input type="text" class="form-control" name="IG_Name" id="IG_Name" value="<?php echo $IG_Name; ?>" />
                                  	</div>
                                </div>
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
        													<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
        												</button>&nbsp;
        											<?php
        										}
        										else
        										{
        											?>
        												<button class="btn btn-primary" id="tblUpdate" >
        													<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
        												</button>&nbsp;
        											<?php
        										}
        									}
        									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
        								?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
<script>
	function checkInp(idName, theValue)
	{
		IG_CODE	= document.getElementById("IG_Code").value;
		if(IG_CODE == '')
		{
			swal('Item category code is empty.');
			document.getElementById("IG_Code").focus();
			return false;
		}
		
		IG_NAME	= document.getElementById("IG_Name").value;
		if(IG_NAME == '')
		{
			swal('Item category name is empty.');
			document.getElementById("IG_Name").focus();
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