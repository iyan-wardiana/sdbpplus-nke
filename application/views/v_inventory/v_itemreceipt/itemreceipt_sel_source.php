<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Februari 2019
 * File Name	= itemreceipt_sel_source.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

function cut_text($var, $len = 200, $txt_titik = "...") 
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
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
</head>

<?php
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
		if($TranslCode == 'PONumber')$PONumber = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
		if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'CustName')$CustName = $LangTransl;
		if($TranslCode == 'ColorCode')$ColorCode = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'POList')$POList = $LangTransl;
		if($TranslCode == 'SOList')$SOList = $LangTransl;
	endforeach;
	
	if(isset($_POST['submit1']))
	{
		$List_Type 		= $this->input->post('List_Type');
		if($List_Type == 1)
		{
			$Active1		= "active";
			$Active2		= "";
			$Active1Cls		= "class='active'";
			$Active2Cls		= "";
		}
		else
		{
			$Active1		= "";
			$Active2		= "active";
			$Active1Cls		= "";
			$Active2Cls		= "class='active'";
		}
	}
	else
	{
		$List_Type		= 1;
		$Active1		= "active";
		$Active2		= "";
		$Active1Cls		= "class='active'";
		$Active2Cls		= "";
	}
	
	if($LangID == 'IND')
	{
		$alert1		= "Pilih salah satu nomor SO.";
	}
	else
	{
		$alert1		= "Select one of SO Number.";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
    <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
</form>
<section class="content">
	<div class="row">
    	 <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active2Cls; ?> style="display:none"><a href="#profPicture" data-toggle="tab" onClick="return false"><?php echo $POList; ?></a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $SOList; ?></a></li> 		<!-- Tab 1 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<form method="post" name="frmSearch" action="">
                	<?php
						if($List_Type == 2)
						{
							?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="profPicture">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="10%" style="vertical-align:middle; text-align:center" nowrap>PO No.</th>
                                                        <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                                                        <th width="24%" nowrap style="text-align:center"><?php echo $Supplier; ?></th>
                                                        <th width="52%" nowrap style="text-align:center"><span style="text-align:center;"><?php echo $Description; ?></span></th>
                                                        <th width="6%" nowrap style="text-align:center;"><span style="text-align:center"><?php echo $ReceivePlan; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $j = 0;
                                                    $sqlPOC		= "tbl_po_header A
                                                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                                    WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'";				
                                                    $resPOC 	= $this->db->count_all($sqlPOC);
                                                
                                                    $sqlPOV		= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                                                                        A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                                                                        C.PRJCODE, C.PRJNAME
                                                                    FROM tbl_po_header A
                                                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                                    WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                                                                    ORDER BY A.PO_NUM ASC";
                                                    $viewAllPOV	= $this->db->query($sqlPOV)->result();
                                                    if($resPOC > 0)
                                                    {
                                                        $totRow	= 0;
                                                        foreach($viewAllPOV as $row) :
                                                            $PO_NUM 		= $row->PO_NUM;
                                                            $PO_CODE 		= $row->PO_CODE;
                                                            $PO_DATE 		= $row->PO_DATE;
                                                            $PO_CREATER 	= $row->PO_CREATER;
                                                            $PO_STAT 		= $row->PO_STAT;
                                                            $PO_APPROVER 	= $row->PO_APPROVER;
                                                            $PO_NOTES 		= $row->PO_NOTES;
                                                            $PO_PLANIR		= $row->PO_PLANIR;
                                                            $PRJCODE		= $row->PRJCODE;
                                                            $SPLCODE		= $row->SPLCODE;
                                                            $PRJNAME		= $row->PRJNAME;
                                                            //$First_Name	= $row->First_Name;
                                                            //$Middle_Name	= $row->Middle_Name;
                                                            //$Last_Name	= $row->Last_Name;
                                                            //$compName 	= "$First_Name $Middle_Name $Last_Name";
                                                            
                                                            $SPLDESC		= '';
                                                            if($SPLCODE != '')
                                                            {
                                                                $sqlSPL			= "SELECT SPLDESC FROM tbl_supplier 
																					WHERE SPLCODE = '$SPLCODE' LIMIT 1";
                                                                $resSPL			= $this->db->query($sqlSPL)->result();
                                                                foreach($resSPL as $rowSPL) :
                                                                    $SPLDESC	= $rowSPL->SPLDESC;
                                                                endforeach;
                                                            }
                                                                                    
                                                            $totRow			= $totRow + 1;
                                                        
                                                            if ($j==1) {
                                                                echo "<tr class=zebra1>";
                                                                $j++;
                                                            } else {
                                                                echo "<tr class=zebra2>";
                                                                $j--;
                                                            }
                                                            ?>
                                                            <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $PO_NUM;?>|<?php echo $PO_CODE;?>|PO" onClick="pickThis(this);" /></td>
                                                            <td nowrap>
                                                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                                                                <?php echo $PO_CODE; ?></a> 
                                                                <input type="hidden" name="PO_NUM<?php echo $totRow; ?>" id="PR_NUM<?php echo $totRow; ?>" value="<?php echo $PO_NUM; ?>" />
                                                            </td>
                                                            <td><?php echo date('d M Y', strtotime($PO_DATE)); ?></td>
                                                            <td><?php echo $SPLDESC; ?></td>
                                                            <td><?php echo $PO_NOTES; ?></td>
                                                            <td style="text-align:center"><?php echo date('d M Y', strtotime($PO_PLANIR)); ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                    <td colspan="6" nowrap>                
                                                        <button class="btn btn-primary" type="button" onClick="get_req();">
                                                        	<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    									</button> 
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        	<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    									</button>
                                                    </td>
                                                </tr>
                                            </table>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						elseif($List_Type == 1)
						{
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="settings">
                                    <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr style="text-align:center">
                                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="4%" style="vertical-align:middle; text-align:center" nowrap>SO No.</th>
                                                        <th width="2%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                                                        <th width="2%" nowrap style="text-align:center"><?php echo $ColorCode; ?></th>
                                                        <th width="19%" nowrap style="text-align:center"><?php echo $CustName; ?></th>
                                                        <th width="67%" nowrap style="text-align:center"><span style="text-align:center;"><?php echo $Description; ?></span></th>
                                                        <th width="3%" style="text-align:center;"><span style="text-align:center"><?php echo $ProdPlan; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $j = 0;
                                                    $sqlPOC		= "tbl_so_header A
                                                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                                    WHERE A.SO_STAT = 3 AND A.PRJCODE = '$PRJCODE'";				
                                                    $resPOC 	= $this->db->count_all($sqlPOC);
                                                
                                                    $sqlPOV		= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_CREATER, A.SO_APPROVER, 
                                                                        A.BOM_NUM, A.BOM_CODE, A.SO_NOTES, A.SO_STAT, A.SO_MEMO, 
																		A.SO_PRODD AS SO_PLANIR, A.CUST_CODE AS SPLCODE,
																		A.BOM_NUM, A.BOM_CODE,
                                                                        C.PRJCODE, C.PRJNAME
                                                                    FROM tbl_so_header A
                                                                        INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
                                                                    WHERE A.SO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                                                                    ORDER BY A.SO_NUM ASC";
                                                    $viewAllPOV	= $this->db->query($sqlPOV)->result();
                                                    if($resPOC > 0)
                                                    {
                                                        $totRow	= 0;
                                                        foreach($viewAllPOV as $row) :
                                                            $SO_NUM 		= $row->SO_NUM;
                                                            $SO_CODE 		= $row->SO_CODE;
                                                            $SO_DATE 		= $row->SO_DATE;
                                                            $BOM_CODE 		= $row->BOM_CODE;
                                                            $SO_CREATER 	= $row->SO_CREATER;
                                                            $SO_STAT 		= $row->SO_STAT;
                                                            $SO_APPROVER 	= $row->SO_APPROVER;
                                                            $SO_NOTES 		= $row->SO_NOTES;
                                                            $SO_PLANIR		= $row->SO_PLANIR;
                                                            $PRJCODE		= $row->PRJCODE;
                                                            $SPLCODE		= $row->SPLCODE;
                                                            $PRJNAME		= $row->PRJNAME;
                                                            //$First_Name	= $row->First_Name;
                                                            //$Middle_Name	= $row->Middle_Name;
                                                            //$Last_Name	= $row->Last_Name;
                                                            //$compName 	= "$First_Name $Middle_Name $Last_Name";
                                                            
                                                            $SPLDESC		= '';
                                                            if($SPLCODE != '')
                                                            {
                                                                $sqlSPL			= "SELECT CUST_DESC FROM tbl_customer
																					WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
                                                                $resSPL			= $this->db->query($sqlSPL)->result();
                                                                foreach($resSPL as $rowSPL) :
                                                                    $SPLDESC	= $rowSPL->CUST_DESC;
                                                                endforeach;
                                                            }
                                                                                    
                                                            $totRow			= $totRow + 1;
                                                        
                                                            if ($j==1) {
                                                                echo "<tr class=zebra1>";
                                                                $j++;
                                                            } else {
                                                                echo "<tr class=zebra2>";
                                                                $j--;
                                                            }
                                                            ?>
                                                            <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $SO_NUM;?>|<?php echo $SO_CODE;?>|SO" onClick="pickThis(this);" /></td>
                                                            <td nowrap>
                                                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                                                                <?php echo $SO_CODE; ?></a> 
                                                                <input type="hidden" name="SO_NUM<?php echo $totRow; ?>" id="PR_NUM<?php echo $totRow; ?>" value="<?php echo $SO_NUM; ?>" />
                                                            </td>
                                                            <td nowrap><?php echo date('d M Y', strtotime($SO_DATE)); ?></td>
                                                            <td nowrap><?php echo $BOM_CODE; ?></td>
                                                            <td nowrap><?php echo $SPLDESC; ?></td>
                                                            <td><?php echo $SO_NOTES; ?></td>
                                                            <td style="text-align:center" nowrap><?php echo date('d M Y', strtotime($SO_PLANIR)); ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                    <td colspan="7" nowrap>                
                                                        <button class="btn btn-primary" type="button" onClick="get_req();">
                                                        	<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    									</button> 
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        	<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    									</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
						}
					?>
                    </form>
                </div>
            </div>
		 </div>
	</div>
</section>
</body>

</html>
<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
	$(function () 
	  {
		$("#example1").DataTable();
		$('#example2').DataTable({
		  "paging": true,
		  "lengthChange": false,
		  "searching": false,
		  "ordering": true,
		  "info": true,
		  "autoWidth": false
		});
	  });
	  
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
  
	var selectedRows = 0;
	
	function setType(thisValue)
	{
		if(thisValue == 1)
		{
			document.getElementById('List_Type').value = thisValue;
		}
		else
		{
			document.getElementById('List_Type').value = thisValue;
		}
		document.frm_01.submit1.click();
	}
	
	function check_all(chk) {
		if(chk.checked) {
			if(typeof(document.frmSearch.chk[0]) == 'object') {
				for(i=0;i<document.frmSearch.chk.length;i++) {
					document.frmSearch.chk[i].checked = true;
				}
			} else {
				document.frmSearch.chk.checked = true;
			}
			selectedRows = document.frmSearch.chk.length;
		} else {
			if(typeof(document.frmSearch.chk[0]) == 'object') {
				for(i=0;i<document.frmSearch.chk.length;i++) {
					document.frmSearch.chk[i].checked = false;
				}
			} else {
				document.frmSearch.chk.checked = false;
			}
			selectedRows = 0;
		}
	}
	
	function pickThisA(thisobj) 
	{
		var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
		//alert(NumOfRows)
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		if(selectedRows > 1)
		{
			alert('Please select one Request');
			return false;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frmSearch.ChkAllItem.checked = true;
		}
		else
		{
			document.frmSearch.ChkAllItem.checked = false;
		}
	}
	

	function get_req() 
	{
		var chkMW = document.querySelector('input[name = "chk"]:checked');
		if(chkMW != null)
		{
			if(typeof(document.frmSearch.chk[0]) == 'object') 
			{
				for(i=0;i<document.frmSearch.chk.length;i++) 
				{
					if(document.frmSearch.chk[i].checked) 
					{
						A = document.frmSearch.chk[i].value
						arrItem = A.split('|');
						arrparent = document.frmSearch.chk[i].value.split('|');
	
						window.opener.add_header(document.frmSearch.chk[i].value);				
					}
				}
			}
			else
			{
				if(document.frmSearch.chk.checked)
				{
					window.opener.add_header(document.frmSearch.chk.value);
					//alert('2' + '\n' + document.frmSearch.chk.value)
					/*A = document.frmSearch.chk.value
					arrItem = A.split('|');
					//alert(arrItem)
					for(z=1;z<=5;z++)
					{
						alert('1')
						B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
						//window.opener.add_item(B,'child');
						alert(B)
					}*/
				}
			}
			window.close();		
		}
		else
		{
			alert('<?php echo $alert1; ?>');
			return false;
		}
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>