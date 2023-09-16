<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 16 Juni 2023
	* File Name		= v_itemrec_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];

$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows 	= $row->Display_Rows;
    $decFormat 		= $row->decFormat;
    $APPLEV 		= $row->APPLEV;
endforeach;
$decFormat      = 2;

$DefEmp_ID      = $this->session->userdata['Emp_ID'];

if($APPLEV == 'HO')
    $PRJTYPE    = "1";
else
    $PRJTYPE    = "2,3";

if($PRJSCATEG == 1)
    $PRJTYPE    = "2,3";
else
    $PRJTYPE    = "1";

$get_project    = "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE NOT IN (SELECT DISTINCT PRJCODE FROM tbl_autoum) AND PRJLEV IN (2,3) ORDER BY PRJCODE";
$qry_Proj       = $this->db->query($get_project)->result();

$qry_itemC 		= 0;
$srcTEXT 		= "";
if(isset($_POST['srcTEXT']))
{
	$srcTEXT 	= $_POST['srcTEXT'];

	$get_item   = "SELECT DISTINCT ITM_CODE, ITM_NAME FROM tbl_item WHERE ITM_CODE NOT IN (SELECT DISTINCT ITM_CODE FROM tbl_autoum)
					AND STATUS = 1 AND ITM_GROUP IN ('M', 'T')
					AND (ITM_NAME LIKE '%$srcTEXT%' ESCAPE '!' OR ITM_CODE LIKE '%$srcTEXT%' ESCAPE '!')
					ORDER BY ITM_CODE";
	$qry_item   = $this->db->query($get_item)->result();

	$get_itemC  = "tbl_item WHERE ITM_CODE NOT IN (SELECT DISTINCT ITM_CODE FROM tbl_autoum)
					AND STATUS = 1 AND ITM_GROUP IN ('M', 'T')
					AND (ITM_NAME LIKE '%$srcTEXT%' ESCAPE '!' OR ITM_CODE LIKE '%$srcTEXT%' ESCAPE '!')";
	$qry_itemC  = $this->db->count_all($get_itemC);
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
        
        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $LangID     = $this->session->userdata['LangID'];
        
        if($LangID == 'IND')
        {
            $subTitleH  = "Tambah";
            $subTitleD  = "penggunaan kas proyek";
            $alert0     = "Silahkan pilih proyek pada kolom sebelah kanan.";
            $alert1     = "Silahkan pilih akun pada kolom sebelah kanan.";
            $alert2     = "Anda belum/tidak menentukan akun untuk karyawan ini.";
        }
        else
        {
            $subTitleH  = "Add";
            $subTitleD  = "project cash payment";
            $alert0     = "Please select the project(s) in the right side.";
            $alert1     = "Please select the account(s) in the right side.";
            $alert2     = "You do not select an Account(s) for this employee(s)";
        }
    ?>

    <?php
      $urlUpdAuth     = site_url('c_hr/c_employee/c_employee/employee_project_process/?id='.$this->url_encryption_helper->encode_url($appName));

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $mnName; ?>
                <small>setting</small>
            </h1>
        </section>

        <section class="content">   
            <form name="src_form" method="post" action="" style="display: none;">
            	<input type="text" name="srcTEXT" id="srcTEXT" class="form-control">
            	<button type="submit" id="btnSubmit">Go!</button>
            </form>
            <form class="form-horizontal" name="absen_form" method="post" action="<?php echo $urlUpdAuth; ?>" enctype="multipart/form-data" onSubmit="return confirmDelete()">
                <td width="16%"><input type="hidden" id="Emp_ID1" name="Emp_ID1" value="<?php print $DefEmp_ID; ?>" width="10" size="10" class="textbox">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <i class="fa fa-cogs"></i>
                                <h3 class="box-title">Pengaturan Proyek</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <select multiple class="form-control" name="pavailable" onclick="MoveOption(this.form.pavailable, this.form.packageelements, 1)" style="height: 150px">
                                            <?php
                                                foreach($qry_Proj as $rowPRJ) :
                                                    $PRJCODE    = $rowPRJ->PRJCODE;
                                                    $PRJNAME    = $rowPRJ->PRJNAME;
                                                    ?>
                                                        <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select multiple class="form-control" name="packageelements[]" id="packageelements" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable, 2)" style="height: 150px">
                                            <?php
                                                $getData        = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_autoum A ORDER BY A.PRJCODE";
                                                $resGetData     = $this->db->query($getData)->result();
                                                foreach($resGetData as $rowData) :
                                                    $PRJCODE  = $rowData->PRJCODE;
                                                    $PRJNAME  = $rowData->PRJNAME;
                                                    ?>
                                                        <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <i class="fa fa-child"></i>
                                <h3 class="box-title">Pengaturan Item (<?=number_format($qry_itemC,0)?>)</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                    	<div class="input-group input-group-sm">
						                	<input type="text" id="txtSRCITM" class="form-control" value="<?=$srcTEXT?>">
						                    <span class="input-group-btn">
						                      	<button type="button" class="btn btn-info btn-flat" onClick="keyITM()">Go!</button>
						                    </span>
						              	</div>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                	function keyITM()
                                	{
                                		var txtSRCITM 	= document.getElementById('txtSRCITM').value;
                                		document.getElementById('srcTEXT').value = txtSRCITM;
                                		document.getElementById('btnSubmit').click();
                                	}
                                </script>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <select multiple class="form-control" name="pavailableCB" onclick="MoveOption(this.form.pavailableCB, this.form.packageelementsCB, 3)" style="height: 150px">
                                            <?php
                                            	if($qry_itemC > 0)
                                            	{
	                                                foreach($qry_item as $rw_ITM) :
	                                                    $ITM_CODE 	= $rw_ITM->ITM_CODE;
	                                                    $ITM_NAME 	= $rw_ITM->ITM_NAME;
		                                                ?>
		                                                    <option value="<?php echo $ITM_CODE; ?>"><?php echo "$ITM_CODE - $ITM_NAME";?></option>
		                                                <?php
	                                                endforeach;
	                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select multiple class="form-control" name="packageelementsCB[]" id="packageelementsCB" ondblclick="MoveOption(this.form.packageelementsCB, this.form.pavailableCB, 4)" style="height: 150px">
                                            <?php
                                                $getData        = "SELECT A.ITM_CODE, A.ITM_NAME FROM tbl_autoum A WHERE ITM_CODE != '' ORDER BY A.ITM_NAME";
                                                $resGetData     = $this->db->query($getData)->result();
                                                foreach($resGetData as $rowData) :
                                                    $ITM_CODE  = $rowData->ITM_CODE;
                                                    $ITM_NAME  = $rowData->ITM_NAME;
                                                    ?>
                                                        <option value="<?php echo $ITM_CODE; ?>"><?php echo "$ITM_CODE - $ITM_NAME";?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-9">
                                <!-- <input type="submit" name="btnDelete" id="btnDelete" class="btn btn-primary" value="Update Project" />&nbsp; -->
                                <button class="btn btn-primary" style="display: none;"><i class="fa fa-save"></i></button>&nbsp;
                                <?php
                                    $backURL        = site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>&nbsp;');
                                    /*if ( ! empty($link))
                                    {
                                        foreach($link as $links)
                                        {
                                            echo $links;
                                        }
                                    }*/
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>
<?php
    $secPRJSv    = base_url().'index.php/__l1y/addPRJAuth_IR/?id=';
    $secPRJDel   = base_url().'index.php/__l1y/delPRJAuth_IR/?id=';
    $secITMSv    = base_url().'index.php/__l1y/addITMAuth_IR/?id=';
    $secITMDel   = base_url().'index.php/__l1y/delITMAuth_IR/?id=';
?>
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
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"> 
    function MoveOption(objSourceElement, objTargetElement, konst) 
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
                 var intTargetLen   = objTargetElement.length++; 
                 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text;
                 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
                 var OBJVAL        = objSourceElement.options[i].value;
                 var Emp_ID         = document.getElementById('Emp_ID1').value;

                 if(konst == 1)
                    var url         = "<?=$secPRJSv?>";
                 else if(konst == 2)
                    var url         = "<?=$secPRJDel?>";
                 else if(konst == 3)
                    var url         = "<?=$secITMSv?>";
                 else if(konst == 4)
                    var url         = "<?=$secITMDel?>";

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {OBJVAL: OBJVAL, Emp_ID: Emp_ID},
                    /*success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        });
                    }*/
                });
            } 
            else
            { 
                 //storing options that stay to recreate select element 
                 var objTempValues = new Object(); 
                 objTempValues.text = objSourceElement.options[i].text; 
                 objTempValues.value = objSourceElement.options[i].value; 
                 aryTempSourceOptions[x] = objTempValues; 
                 x++; 
                 //console.log('theTargX = '+objSourceElement.options[i].value);
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
    
    function selectAll(objTargetElement) 
    { 
        for (var i = 0; i < objTargetElement.length; i++) 
        { 
            objTargetElement.options[i].selected = true; 
        } 
        //return false;
    }
  //--> 
 </SCRIPT> 
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