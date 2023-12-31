<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 April 2017
 * File Name	= r_product.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

$noUc			= 0;
$NPRJCODE		= '';
//$getAsset 	= "SELECT AS_CODE, AS_NAME FROM tbl_asset_list WHERE AS_STAT NOT IN (2) ORDER BY AS_NAME";
// MENCARI NAMA ASSET YANG SUDAH DIBUATKAN DI TABEL ASSET DAN MAINTENANCE
/*$getAsset 		= "SELECT DISTINCT A.AU_AS_CODE, B.AS_CODE, B.AS_NAME 
					FROM tbl_asset_usage A
						INNER JOIN tbl_asset_list B ON A.AU_AS_CODE = B.AS_CODE
					WHERE B.AS_STAT NOT IN (2)
					ORDER BY B.AS_NAME";*/
$getAsset 		= "SELECT DISTINCT RASTC_ASTCODE AS AS_CODE, RASTC_ASTDESC AS AS_NAME
					FROM tbl_asset_rcost ORDER BY RASTC_ASTDESC";
$qAsset 		= $this->db->query($getAsset)->result();

$noUcM		= 0;
$NPRJCODEM	= '101';
/*$getAssetM	= "SELECT DISTINCT A.AU_PRJCODE AS PRJCODEX
					FROM tbl_asset_usage A
						INNER JOIN tbl_asset_list B ON A.AU_AS_CODE = B.AS_CODE
						WHERE
							B.AS_STAT NOT IN (2)
							
					UNION ALL
					
					SELECT DISTINCT C.AM_PRJCODE AS PRJCODEX
					FROM tbl_asset_mainten C
						INNER JOIN tbl_asset_list D ON C.AM_AS_CODE = D.AS_CODE
						WHERE
							D.AS_STAT NOT IN (2)
					GROUP BY PRJCODEX";*/
$getAssetM 	= "SELECT DISTINCT RASTC_PRJCODE AS PRJCODEX FROM tbl_asset_rcost GROUP BY PRJCODEX";
$qAssetM 	= $this->db->query($getAssetM)->result();
foreach($qAssetM as $rowASTM) :
	$noUcM		= $noUcM + 1;
	$AM_PRJCODE	= $rowASTM->PRJCODEX;
	if($noUcM == 1)
	{
		$NPRJCODEM = "'$AM_PRJCODE'";
	}
	else if($noUcM == 2)
	{
		$NPRJCODEM = "$NPRJCODEM, '$AM_PRJCODE'";
	}
	else if($noUcM > 2)
	{
		$NPRJCODEM = "$NPRJCODEM, '$AM_PRJCODE'";
	}
endforeach;

/*$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";*/
$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					WHERE A.PRJCODE IN ($NPRJCODEM) ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();

$getSupplier 	= "SELECT A.SPLCODE, A.SPLDESC FROM tbl_supplier A WHERE SPLSTAT = 1 ORDER BY A.SPLDESC";
$qSupplier 		= $this->db->query($getSupplier)->result();

$vPeriod		= "daily";
if(isset($_POST['vPeriodx']))
{
	$vPeriod 	= $_POST['vPeriodx'];
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
    	/*$this->load->view('template/topbar');
    	$this->load->view('template/sidebar');*/
    	
    	$ISREAD 	= $this->session->userdata['ISREAD'];
    	$ISCREATE 	= $this->session->userdata['ISCREATE'];
    	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
    	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

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
    		if($TranslCode == 'AssetName')$AssetName = $LangTransl;
    		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
    		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
    		if($TranslCode == 'Excel')$Excel = $LangTransl;
    		if($TranslCode == 'Detail')$Detail = $LangTransl;
    		if($TranslCode == 'Summary')$Summary = $LangTransl;
    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
                <small><?php echo $h3_title; ?></small>
            </h1>
        </section>
        
        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="vPeriodx" id="vPeriodx" class="textbox" value="<?php echo $vPeriod; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
               	  	<form method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
                        <table width="100%">
                            <tr>
                              <td width="14%" nowrap>&nbsp;&nbsp;<?php echo $ProjectName ?> <font color="#FFFFFF"><?php echo $ProjectName ?></font></td>
                              <td width="1%" nowrap>:</td>
                              <td colspan="2" nowrap>
                                <input type="hidden" name="isVPAll" id="isVPAll" value="0" />
                                <label>
                                    <input type="radio" name="viewProj" id="viewProj_0" value="0" onClick="changeVPType(0);" checked /> 
                                    <?php echo $Select ?> 
                                    <input type="radio" name="viewProj" id="viewProj_1" value="1" onClick="changeVPType(1)" /> 
                                    <?php echo $All ?> </label>				</td>
                            </tr>
                            <script>
                                function changeVPType(thisVal)
                                {
                                    if(thisVal == 0)
                                    {
                                        document.getElementById('projAll01').style.display = '';
                                        //document.getElementById('projAll02').style.display = '';
                                        //document.getElementById('projAll03').style.display = '';
                                        document.getElementById('isVPAll').value = 0;
                                    }
                                    else
                                    {
                                        document.getElementById('projAll01').style.display = 'none';
                                        //document.getElementById('projAll02').style.display = 'none';
                                        //document.getElementById('projAll03').style.display = 'none';
                                        document.getElementById('isVPAll').value = 1;
                                    }
                                }
                            </script>
                            <tr id="projAll01">
                                <td nowrap valign="top">&nbsp;</td>
                                <td nowrap valign="top">:</td>
                                <td width="14%" id="projAll02" nowrap>
                                    <select multiple="multiple" class="options" size="10" style="width: 350px;" name="pavailable" ondblclick="MoveOption(this.form.pavailable, this.form.packageelements)">
                                    <?php 
                                        foreach($qProject as $rowPRJ) :
                                            $PRJCODE 	= $rowPRJ->PRJCODE;
                                            $PRJNAME	= $rowPRJ->PRJNAME;
                                            ?>
                                                <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                    </select>                </td>
                                <td width="80%" id="projAll03" nowrap>
                                    <?php					
                                        $getCount		= "tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID'";
                                        $resGetCount	= $this->db->count_all($getCount);
                                    ?>
                                    <select multiple="multiple" name="packageelements[]" id="packageelements" size="10"  style="width: 300px;" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)">
                                  </select>                </td>
                            </tr>
                            <tr>
                              <td nowrap>&nbsp;&nbsp;<?php echo $AssetName ?> <font color="#FFFFFF">&nbsp;</font></td>
                              <td nowrap>:</td>
                              <td colspan="2" nowrap>
                                <input type="hidden" name="isVSAll" id="isVSAll" value="0" />
                                <label>
                                    <input type="radio" name="viewAsset" id="viewAsset_0" value="0" onClick="changeVSType(0);" checked /> 
                                    <?php echo $Select ?> 
                                    <input type="radio" name="viewAsset" id="viewAsset_1" value="1" onClick="changeVSType(1)" /> 
                                    <?php echo $All ?>         </label>              </td>
                            </tr>
                            <script>
                                function changeVSType(thisVal)
                                {
                                    if(thisVal == 0)
                                    {
                                        document.getElementById('projAll04').style.display = '';
                                        document.getElementById('isVSAll').value = 0;
                                    }
                                    else
                                    {
                                        document.getElementById('projAll04').style.display = 'none';
                                        document.getElementById('isVSAll').value = 1;
                                    }
                                }
                            </script>
                            <tr id="projAll04">
                                <td nowrap valign="top">&nbsp;</td>
                                <td nowrap valign="top">:</td>
                                <td width="14%" id="projAll05" nowrap>
                                    <select multiple="multiple" class="options" size="10" style="width: 350px;" name="pavailableAst" onclick="MoveOptionSpl(this.form.pavailableAst, this.form.packageelementsAst)">
                                    <?php
                                        foreach($qAsset as $rowAST) :
                                            $AS_CODE 	= $rowAST->AS_CODE;
                                            $AS_NAME	= $rowAST->AS_NAME;
                                            ?>
                                                <option value="<?php echo $AS_CODE; ?>"><?php echo "$AS_NAME - $AS_CODE";?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                    </select>                </td>
                                <td width="80%" id="projAll03" nowrap>
                                    <?php					
                                        $getCount		= "tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID'";
                                        $resGetCount	= $this->db->count_all($getCount);
                                    ?>
                                    <select multiple="multiple" name="packageelementsAst[]" id="packageelementsAst" size="10"  style="width: 300px;" ondblclick="MoveOptionSpl(this.form.packageelementsAst, this.form.pavailableAst)">
                                  </select>                </td>
                            </tr>
                            <script>
                                function changeVPType(thisVal)
                                {
                                    if(thisVal == 0)
                                    {
                                        document.getElementById('projAll01').style.display = '';
                                        document.getElementById('isVPAll').value = 0;
                                    }
                                    else
                                    {
                                        document.getElementById('projAll01').style.display = 'none';
                                        document.getElementById('isVPAll').value = 1;
                                    }
                                }
                            </script>
                            <tr>
                              	<td>&nbsp;&nbsp;<?php echo $StartDate ?> </td>
                           	  	<td nowrap>:</td>
                              	<td colspan="2" nowrap>
                                  <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" name="Start_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $Start_Date; ?>" size="10" style="width:150px" >
                                  </div>
                				</td>
                            </tr>
                            <tr>
                              	<td>&nbsp;&nbsp;<?php echo $EndDate ?> </td>
                              	<td nowrap>:</td>
                              	<td colspan="2" nowrap>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" name="End_Date" class="form-control pull-left" id="datepicker2" value="<?php echo $End_Date; ?>" size="10" style="width:150px" >
                                    </div>
                				</td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;<?php echo $Type ?> </td>
                                <td nowrap>:</td>
                                <td colspan="2" nowrap>
                                    <select name="CFType" id="CFType" class="form-control" style="max-width:110px" >
                						<option value="1" ><?php echo $Detail ?> </option>
                						<option value="2" ><?php echo $Summary ?> </option>
                                  	</select>				</td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;<?php echo $ViewType ?> </td>
                                <td nowrap>:</td>
                                <td colspan="2" nowrap>
                                <label>
                                    <input type="radio" name="viewType" id="viewType" value="0" checked /> 
                                    <?php echo $WebViewer ?> 
                                    <input type="radio" name="viewType" id="viewType" value="1" /> 
                                    <?php echo $Excel ?>         </label>    	</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td nowrap>&nbsp;</td>
                              <td colspan="2" nowrap>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td nowrap>&nbsp;</td>
                                <td colspan="2" nowrap>
                                    <!--<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Display Report" /> -->
                                    
                                    <button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>&nbsp;
                                                   </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td nowrap>&nbsp;</td>
                                <td colspan="2" nowrap><hr /></td>
                            </tr>
                        </table>
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
</script>
<script>
	function MoveOption(objSourceElement, objTargetElement) 
	{ 
		var aryTempSourceOptions = new Array(); 
		var aryTempTargetOptions = new Array(); 
		var x = 0; 
    
   		//looping through source element to find selected options 
   		for (var i = 0; i < objSourceElement.length; i++)
		{ 
    		if (objSourceElement.options[i].selected)
			{ 
				 //need to move this option to target element 
				 var intTargetLen = objTargetElement.length++; 
				 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
				 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
    		} 
    		else
			{ 
				 //storing options that stay to recreate select element 
				 var objTempValues = new Object(); 
				 objTempValues.text = objSourceElement.options[i].text; 
				 objTempValues.value = objSourceElement.options[i].value; 
				 aryTempSourceOptions[x] = objTempValues; 
				 x++; 
			} 
   		}
		
   		//sorting and refilling target list 
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
		} 

		aryTempTargetOptions.sort(sortByText); 

		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		}
		
   		//resetting length of source 
   		objSourceElement.length = aryTempSourceOptions.length; 
   		//looping through temp array to recreate source select element 
   		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		}
	}
	
	function MoveOptionSpl(objSourceElement, objTargetElement) 
	{
		var aryTempSourceOptions = new Array(); 
		var aryTempTargetOptions = new Array(); 
		var x = 0; 
    
   		//looping through source element to find selected options 
   		for (var i = 0; i < objSourceElement.length; i++)
		{ 
    		if (objSourceElement.options[i].selected)
			{ 
				 //need to move this option to target element 
				 var intTargetLen = objTargetElement.length++; 
				 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
				 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
    		} 
    		else
			{ 
				 //storing options that stay to recreate select element 
				 var objTempValues = new Object(); 
				 objTempValues.text = objSourceElement.options[i].text; 
				 objTempValues.value = objSourceElement.options[i].value; 
				 aryTempSourceOptions[x] = objTempValues; 
				 x++; 
			} 
   		}
		
   		//sorting and refilling target list 
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
		} 

		aryTempTargetOptions.sort(sortByText); 

		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		}
		
   		//resetting length of source 
   		objSourceElement.length = aryTempSourceOptions.length; 
   		//looping through temp array to recreate source select element 
   		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		}
	}

     function sortByText(a, b) 
     { 
		if (a.text < b.text) {return -1} 
		if (a.text > b.text) {return 1} 
		return 0; 
     } 
	
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		isVPAll			= document.getElementById('isVPAll').value;
		if(isVPAll == 0)
		{
			packageelements	= document.getElementById('packageelements').value;
			if(packageelements == '')
			{
				alert('Please select one or all project');
				return false;
			}
		}
		title = 'Select Item';
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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