<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 November 2018
 * File Name	= v_itemcal_sel_bom.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;

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
</head>
<?php
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl	= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'ColorName')$ColorName = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'CustName')$CustName = $LangTransl;
		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
		if($TranslCode == 'Deviation')$Deviation = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$alert1		= "Pilih salah satu kode pencocokan warna.";
	}
	else
	{
		$alert1		= "Select one of color matching code.";
	}

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
                    <li <?php echo $Active1Cls; ?>><a href="#wiplist" data-toggle="tab" onClick="setType(1)">Khusus</a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active2Cls; ?>><a href="#others" data-toggle="tab" onClick="setType(2)">Umum</a></li> 		<!-- Tab 1 -->
                </ul>
                <script type="text/javascript">
                	function checkRow(theVal)
                	{
                		document.getElementById('DET_'+theVal).style.display = '';
                	}
                </script>
                <!-- Biodata -->
                <div class="tab-content">
                	<?php
						if($List_Type == 1)
						{  
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="wiplist">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                        		<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
									                <thead>
									                    <tr>
									                        <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
									                        <th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $BOMCode; ?></th>
									                        <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
									                        <th width="17%" nowrap style="text-align:center"><?php echo $ColorName; ?></th>
									                        <th width="32%" nowrap style="text-align:center"><?php echo $Description; ?></th>
									                        <th width="30%" nowrap style="text-align:center"><?php echo $CustName; ?></th>
									                    </tr>
									                </thead>
									                <tbody>
									                <?php
									                    $i = 0;
									                    $j = 0;
									                    $sqlReqC	= "tbl_bom_header A
									                                        LEFT JOIN  tbl_employee B ON A.BOM_CREATER = B.Emp_ID
									                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
									                                    WHERE A.BOM_STAT = 3 AND A.CUST_CODE = '$CUST_CODE'
																			AND A.PRJCODE IN (SELECT PRJCODE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE')";				
									                    $resReqC 	= $this->db->count_all($sqlReqC);
									                
									                    $sql 		= "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name,
									                                        C.PRJCODE, C.PRJNAME
									                                    FROM tbl_bom_header A
									                                        LEFT JOIN  tbl_employee B ON A.BOM_CREATER = B.Emp_ID
									                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
									                                    WHERE A.BOM_STAT = 3 AND A.CUST_CODE = '$CUST_CODE'
																			AND A.PRJCODE IN (SELECT PRJCODE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE')
									                                    ORDER BY A.BOM_NUM ASC";
									                    $viewAllMR 	= $this->db->query($sql)->result();
									                    if($resReqC > 0)
									                    {
									                        $totRow	= 0;
									                        foreach($viewAllMR as $row) :
									                            $BOM_NUM 		= $row->BOM_NUM;
									                            $BOM_CODE 		= $row->BOM_CODE;
									                            $BOM_NAME 		= $row->BOM_NAME;
									                            $BOM_DESC 		= $row->BOM_DESC;
									                            $BOM_CREATER 	= $row->BOM_CREATER;
									                            $BOM_CREATED 	= $row->BOM_CREATED;
									                            $BOM_CREATED	= date('d M Y', strtotime($BOM_CREATED));
									                            $BOM_STAT 		= $row->BOM_STAT;
									                            $PRJCODE		= $row->PRJCODE;
									                            $PRJNAME		= $row->PRJNAME;
									                            $First_Name		= $row->First_Name;
									                            $Middle_Name	= $row->Middle_Name;
									                            $Last_Name		= $row->Last_Name;
									                            $compName 		= "$First_Name $Middle_Name $Last_Name";	
									                            
									                            $totRow			= $totRow + 1;
									                        
									                            if ($j==1) {
									                                echo "<tr class=zebra1>";
									                                $j++;
									                            } else {
									                                echo "<tr class=zebra2>";
									                                $j--;
									                            }
									                            ?>
									                			<td style="text-align:center"><input type="radio" name="chk" value="<?php echo $BOM_NUM;?>|<?php echo $BOM_CODE;?>" onClick="pickThis(this);" /></td>
									                            <td nowrap>
									                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
									                                <?php echo $BOM_CODE; ?></a> 
									                                <input type="hidden" name="BOM_NUM<?php echo $totRow; ?>" id="BOM_NUM<?php echo $totRow; ?>" value="<?php echo $BOM_NUM; ?>" />
									                            </td>
									                            <td><?php echo $BOM_CREATED; ?></td>
									                            <td><?php echo $BOM_NAME; ?></td>
									                            <td><?php echo $BOM_DESC; ?></td>
									                            <td><?php echo $CUST_DESC; ?></td>
									                            </tr>
									                        <?php
									                        endforeach;
									                    }
									                ?>
									                </tbody>
									                <tfoot>
									                <tr>
									                    <td colspan="6" nowrap>
									                    <button class="btn btn-primary" type="button" onClick="get_req();">
									                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
									                        <button class="btn btn-danger" type="button" onClick="window.close()">
									                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
									                    </td>
									                </tr>
									                </tfoot>
											  </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						else
						{
							?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="others">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                        		<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
									                <thead>
									                    <tr>
									                        <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
									                        <th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $BOMCode; ?></th>
									                        <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
									                        <th width="17%" nowrap style="text-align:center"><?php echo $ColorName; ?></th>
									                        <th width="32%" nowrap style="text-align:center"><?php echo $Description; ?></th>
									                        <th width="30%" nowrap style="text-align:center"><?php echo $CustName; ?></th>
									                    </tr>
									                </thead>
									                <tbody>
									                <?php
									                    $i = 0;
									                    $j = 0;
									                    $sqlReqC	= "tbl_bom_header A
									                                        LEFT JOIN  tbl_employee B ON A.BOM_CREATER = B.Emp_ID
									                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
									                                    WHERE A.BOM_STAT = 3
																			AND A.PRJCODE IN (SELECT PRJCODE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE')";				
									                    $resReqC 	= $this->db->count_all($sqlReqC);
									                
									                    $sql 		= "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name,
									                                        C.PRJCODE, C.PRJNAME
									                                    FROM tbl_bom_header A
									                                        LEFT JOIN  tbl_employee B ON A.BOM_CREATER = B.Emp_ID
									                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
									                                    WHERE A.BOM_STAT = 3
																			AND A.PRJCODE IN (SELECT PRJCODE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE')
									                                    ORDER BY A.BOM_NUM ASC";
									                    $viewAllMR 	= $this->db->query($sql)->result();
									                    if($resReqC > 0)
									                    {
									                        $totRow	= 0;
									                        foreach($viewAllMR as $row) :
									                            $BOM_NUM 		= $row->BOM_NUM;
									                            $BOM_CODE 		= $row->BOM_CODE;
									                            $BOM_NAME 		= $row->BOM_NAME;
									                            $BOM_DESC 		= $row->BOM_DESC;
									                            $BOM_CREATER 	= $row->BOM_CREATER;
									                            $BOM_CREATED 	= $row->BOM_CREATED;
									                            $BOM_CREATED	= date('d M Y', strtotime($BOM_CREATED));
									                            $BOM_STAT 		= $row->BOM_STAT;
									                            $PRJCODE		= $row->PRJCODE;
									                            $PRJNAME		= $row->PRJNAME;
									                            $First_Name		= $row->First_Name;
									                            $Middle_Name	= $row->Middle_Name;
									                            $Last_Name		= $row->Last_Name;
									                            $compName 		= "$First_Name $Middle_Name $Last_Name";	
									                            
									                            $totRow			= $totRow + 1;
									                        
									                            if ($j==1) {
									                                echo "<tr class=zebra1>";
									                                $j++;
									                            } else {
									                                echo "<tr class=zebra2>";
									                                $j--;
									                            }
									                            ?>
									                			<td style="text-align:center"><input type="radio" name="chk" value="<?php echo $BOM_NUM;?>|<?php echo $BOM_CODE;?>" onClick="pickThis(this);" /></td>
									                            <td nowrap>
									                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
									                                <?php echo $BOM_CODE; ?></a> 
									                                <input type="hidden" name="BOM_NUM<?php echo $totRow; ?>" id="BOM_NUM<?php echo $totRow; ?>" value="<?php echo $BOM_NUM; ?>" />
									                            </td>
									                            <td><?php echo $BOM_CREATED; ?></td>
									                            <td><?php echo $BOM_NAME; ?></td>
									                            <td><?php echo $BOM_DESC; ?></td>
									                            <td><?php echo $CUST_DESC; ?></td>
									                            </tr>
									                        <?php
									                        endforeach;
									                    }
									                ?>
									                </tbody>
									                <tfoot>
									                <tr>
									                    <td colspan="6" nowrap>
									                    <button class="btn btn-primary" type="button" onClick="get_req();">
									                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
									                        <button class="btn btn-danger" type="button" onClick="window.close()">
									                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
									                    </td>
									                </tr>
									                </tfoot>
											  </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
					?>
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
</script>

<script>
var selectedRows = 0

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

function pickThis(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
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
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>