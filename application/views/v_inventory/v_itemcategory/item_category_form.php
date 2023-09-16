<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 4 April 2017
	* File Name		= item_category_form.php
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
	$getC		= "tbl_itemcategory";
	$resC1		= $this->db->count_all($getC);
	$resC 		= $resC1 + 1;
	$len		= strlen($resC);
	$nol		="";
	if($len==1) $nol="0";
	$lastNumn	= $nol.$resC;
	$IC_Num 	= "CAT$lastNumn";
	$IG_Code	= "";
	$IC_Code 	= "";
	$IC_Name	= "";
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
			if($TranslCode == 'CategoryCode')$CategoryCode = $LangTransl;
			if($TranslCode == 'CategoryName')$CategoryName = $LangTransl;
			if($TranslCode == 'ItemGroup')$ItemGroup = $LangTransl;
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
			    <?php echo "$Add $Category"; ?>
			    <small><?php echo $Inventory; ?></small>
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
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CategoryCode ?> </label>
		                          	<div class="col-sm-10">
		                                <label>
										<?php
		                                    if($task == "add")
		                                    {
		                                	?>
		                                        <input type="text" class="form-control" name="IC_Num" id="IC_Num" value="<?php echo $IC_Num; ?>" onChange="functioncheck(this.value)" />
		                                	<?php
		                                    }
		                                    else
		                                    {
		                                	?>
		                                        <input type="text" class="form-control" name="IC_Num1" id="IC_Num1" value="<?php echo $IC_Num; ?>" disabled>
		                                        <input type="hidden" class="form-control" name="IC_Num" id="IC_Num" value="<?php echo $IC_Num; ?>">
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
										document.getElementById('IC_Code').value = myValue;
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
										var IC_Num = document.getElementById('IC_Num').value;
										
										ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_inventory/c_item_category/getTheCode/';?>" + IC_Num, true);
										ajaxRequest.send(null);
									}
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ItemGroup ?> </label>
		                          	<div class="col-sm-10">
		                            	<select name="IG_Code" id="IG_Code" class="form-control select2" >
		                            		<option value="">---</option>
											<?php
											$sqlC		= "tbl_itemgroup";
											$resC		= $this->db->count_all($sqlC);
		                                    echo $i = 0;
		                                    if($resC > 0)
		                                    {
												$sql		= "SELECT IG_Num, IG_Code, IG_Name
																FROM  tbl_itemgroup ORDER BY ID ASC";
												$viewCateg	= $this->db->query($sql)->result();
		                                        foreach($viewCateg as $row) :
		                                            $IG_Num1	= $row->IG_Num;
		                                            $IG_Code1 	= $row->IG_Code;
		                                            $IG_Name1 	= $row->IG_Name;
		                                            ?>
		                                            <option value="<?php echo $IG_Code1; ?>" <?php if($IG_Code1 == $IG_Code) { ?> selected <?php } ?>><?php echo "$IG_Code1 - $IG_Name1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    }
		                                    else
		                                    {
		                                        ?>
		                                            <option value="none">--- None ---</option>
		                                        <?php
		                                    }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CategoryName ?> </label>
		                          	<div class="col-sm-10">
		                            	<input type="text" class="form-control" name="IC_Code" id="IC_Code" value="<?php echo $IC_Num; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CategoryName ?> </label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="IC_Name" id="IC_Name" value="<?php echo $IC_Name; ?>" />
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
		IC_CODE	= document.getElementById("IC_Code").value;
		if(IC_CODE == '')
		{
			swal('Item category code is empty.');
			document.getElementById("IC_Code").focus();
			return false;
		}
		
		IC_NAME	= document.getElementById("IC_Name").value;
		if(IC_NAME == '')
		{
			swal('Item category name is empty.');
			document.getElementById("IC_Name").focus();
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
