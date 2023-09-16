<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2018
 * File Name	= v_ttk_sel_ir.php
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

<?php
	$LangID 		= $this->session->userdata['LangID'];
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
		if($TranslCode == 'OpnNo')$OpnNo = $LangTransl;
		if($TranslCode == 'Others')$Others = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'deduction')$deduction = $LangTransl;
	endforeach;
	
	if($TTK_CATEG == 'IR')
		$ReceiptNumber	= $ReceiptNumber;
	elseif($TTK_CATEG == 'OPN')
		$ReceiptNumber	= $OpnNo;
	else
		$ReceiptNumber	= $Others;
	
?>
<div class="box">
    <!-- /.box-header -->
<div class="box-body">
    <div class="callout callout-success" style="vertical-align:top">
        <?php echo "$PRJCODE - $PRJNAME"; ?>
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
            <thead>
                <tr>
                    <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                    <th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $ReceiptNumber; ?></th>
                    <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
					<th width="40%" nowrap style="text-align:center"><?php echo $Description; ?></th>
                    <th width="10%" nowrap style="text-align:center"><?php echo $Amount; ?></th>
					<th width="5%" nowrap style="text-align:center">PPn</th>
					<th width="10%" nowrap style="text-align:center"><?php echo $deduction; ?></th>
					<th width="10%" nowrap style="text-align:center;">Retensi</th>
					<th width="10%" nowrap style="text-align:center">G-Total</th>
              	</tr>
            </thead>
            <tbody>
            <?php
                $i = 0;
                $j = 0;
				//echo "test $countAllIR";
                if($countAllIR > 0)
                {
                    $totRow	= 0;
                    foreach($viewAllIR as $row) :
                        $IR_NUM 		= $row->IR_NUM;
                        $IR_CODE		= $row->IR_CODE;
                        $IR_DATEV 		= $row->IR_DATE;
						/*$IR_DATE1		= date_create($IR_DATE);
                        $IR_DATEV 		= date_format($IR_DATE1,"d-m-Y");*/
                        $IR_DUEDATEV	= $row->IR_DUEDATE;
						/*$IR_DUEDATE1	= date_create($IR_DUEDATE);
                        $IR_DUEDATEV	= date_format($IR_DUEDATE1,"d-m-Y");*/
                        $IR_REFER 		= $row->IR_REFER;
                        $SPLCODE 		= $row->SPLCODE;
                        $PO_NUM 		= $row->PO_NUM;
                        $PO_CODE 		= '';
						$PO_DATE		= "Not Set";
						$PO_DATEV		= "Not Set";
                        $OPNH_RETAMN	= $row->OPNH_RETAMN;						// RETENSI for OPNAME ONLY
						
						if($TTK_CATEG == 'OTH')
						{
							$sqlPOCODE	= "SELECT GEJ_NUM AS PO_NUM, GEJ_CODE AS PO_CODE
											FROM tbl_fpa_header WHERE FPA_NUM = '$IR_NUM' LIMIT 1";
							$resPOCODE	= $this->db->query($sqlPOCODE)->result();
							foreach($resPOCODE as $rowPOCODE) :
								$PO_NUM		= $rowPOCODE->PO_NUM;
								$PO_CODE	= $rowPOCODE->PO_CODE;
							endforeach;
						}
						
						$sqlPO			= "SELECT PO_DATE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
						$resPO			= $this->db->query($sqlPO)->result();
						foreach($resPO as $rowPO) :
							$PO_DATE	= $rowPO->PO_DATE;
							$PO_DATE1	= date_create($PO_DATE);
                        	$PO_DATEV	= date_format($PO_DATE1,"d-m-Y");
						endforeach;
						
						// TAX
						$TotPPn			= 0;
						$TotPPh			= 0;
						$TotDP			= 0;
						$TotDisc		= 0;
						$TotIRAmn 		= 0;
						
						//if($TTK_CATEG == 'IR' AND $PO_NUM != '')
						if($TTK_CATEG == 'IR')
						{
							$sqlTAX			= "SELECT SUM(ITM_TOTAL) AS TotIRAmn, SUM(ITM_DISC) AS TotDisc,
													SUM(TAXPRICE1) AS TotPPn, SUM(TAXPRICE2) AS TotPPh
												FROM tbl_ir_detail
												WHERE IR_NUM = '$IR_NUM'";
							$resTAX			= $this->db->query($sqlTAX)->result();
							foreach($resTAX as $rowTAX) :
								$TotIRAmn	= $rowTAX->TotIRAmn;
								$TotPPn		= $rowTAX->TotPPn;
								$TotPPh		= $rowTAX->TotPPh;
								$TotDisc	= $rowTAX->TotDisc;
							endforeach;
						}
						elseif($TTK_CATEG == 'OPN')
						{
							$OPNH_AMOUNTPPN	= 0;
							$OPNH_DPVAL		= 0;
							$OPNH_RETAMN	= 0;
							$OPNH_POT		= 0;
							$sqlOPNH	= "SELECT OPNH_AMOUNTPPN, OPNH_DPVAL, OPNH_RETAMN, OPNH_POT
											FROM tbl_opn_header
											WHERE OPNH_NUM = '$IR_NUM' LIMIT 1";
							$resOPNH	= $this->db->query($sqlOPNH)->result();
							foreach($resOPNH as $rowOPNH) :
								$OPNH_AMOUNTPPN	= $rowOPNH->OPNH_AMOUNTPPN;
								$OPNH_DPVAL		= $rowOPNH->OPNH_DPVAL;
								$OPNH_RETAMN	= $rowOPNH->OPNH_RETAMN;
								$OPNH_POT		= $rowOPNH->OPNH_POT;
							endforeach;
							// KARENA YANG BERPENGARUH HANYA POTONGAN, JADI YANG DIPAKAI HANYA POTONGAN
							$TotPPh	= 0;
							$TotDP	= $OPNH_DPVAL;
							$TotPPn	= $OPNH_AMOUNTPPN;
							$TotDisc= $OPNH_POT;
						}
						
						$GTTax			= $TotPPn - $TotPPh;
						$TotDisc		= $TotDisc;
						
                        //$IR_AMOUNT2		= $row->IR_AMOUNT;							// ORIGINAL tanpa potongan dan pajak
                        $IR_AMOUNT2		= $TotIRAmn;								// ORIGINAL tanpa potongan dan pajak
						if($OPNH_RETAMN == '')
							$OPNH_RETAMN = 0;
						
						if($TTK_CATEG == 'IR')
						{
                        	$IR_AMOUNT2		= $TotIRAmn;									// ORIGINAL tanpa potongan dan pajak
							$IR_AMOUNT		= $IR_AMOUNT2;									// Nilai invoice tanpa PPn
							//$IR_AMOUNTV	= $IR_AMOUNT2 - $TotDisc - $GTTax;				// Nilai invoice
							$IR_AMOUNTV		= $IR_AMOUNT2 + $TotPPn - $TotDisc;				// Nilai invoice
						}
						elseif($TTK_CATEG == 'OPN')
						{
                        	$IR_AMOUNT2		= $row->IR_AMOUNT;								// ORIGINAL tanpa potongan dan pajak
							/*$IR_AMOUNT		= $IR_AMOUNT2;								// Nilai invoice / Nilai Akhir Opname
							$IR_AMOUNTV		= $IR_AMOUNT2 - $TotDisc;						// Nilai invoice sebelum dipotong potongan
							$IR_AMOUNT2V	= $IR_AMOUNT2;*/
							$IR_AMOUNT		= $IR_AMOUNT2 - $TotDP - $OPNH_RETAMN;			// Nilai invoice / Nilai Akhir Opname
							if($TotPPn > 0)
							{
								//$TotPPn		= 0.1 * $IR_AMOUNT;
								// Pajak langsung diambil dari data opname
							}
							$TotDisc 		= $TotDisc + $TotDP;
							$GTTax			= $TotPPn - $TotPPh;
							$IR_AMOUNTV		= $IR_AMOUNT2 + $GTTax - $TotDisc - $OPNH_RETAMN;		// Nilai invoice sebelum dipotong potongan
						}
						else
						{
                        	$IR_AMOUNT2		= $row->IR_AMOUNT;						// ORIGINAL tanpa potongan dan pajak
							$IR_AMOUNT		= $IR_AMOUNT2;							// Nilai invoice / Nilai Akhir Opname
							$IR_AMOUNTV		= $IR_AMOUNT2 - $TotDisc;				// Nilai invoice sebelum dipotong potongan
							$IR_AMOUNT2V	= $IR_AMOUNT2;
						}
						$GIR_AMOUNTV		= $IR_AMOUNTV;							// Include Disc and PPn
						//$GIR_AMOUNT		= $IR_AMOUNT - $GTTax;					// Non PPn
						//$GIR_AMOUNT		= $IR_AMOUNT + $OPNH_RETAMN - $GTTax;	// Non PPn
						//$GIR_AMOUNT		= $IR_AMOUNT;							// Non PPn

                        $IR_NOTE		= $row->IR_NOTE;
						$IR_NOTE		= str_replace('"', '', $IR_NOTE);
						$IR_NOTE		= str_replace("'", "", $IR_NOTE);
                        $IR_NOTE2 		= $row->IR_NOTE2;
						$IR_NOTE2		= str_replace('"', '', $IR_NOTE2);
						$IR_NOTE2		= str_replace("'", "", $IR_NOTE2);
                        $SPLDESC		= $row->SPLDESC;
                        $compName 		= "";
						
						if($TTK_CATEG == 'IR' AND $PO_NUM != '')
						{
							$sqlPOCODE	= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' LIMIT 1";
							$resPOCODE	= $this->db->query($sqlPOCODE)->result();
							foreach($resPOCODE as $rowPOCODE) :
								$PO_CODE	= $rowPOCODE->PO_CODE;
							endforeach;
						}
						elseif($TTK_CATEG == 'OPN')
						{
							$sqlPOCODE	= "SELECT WO_CODE AS PO_CODE FROM tbl_wo_header WHERE WO_NUM = '$PO_NUM' LIMIT 1";
							$resPOCODE	= $this->db->query($sqlPOCODE)->result();
							foreach($resPOCODE as $rowPOCODE) :
								$PO_CODE	= $rowPOCODE->PO_CODE;
							endforeach;
						}
						
                        $totRow			= $totRow + 1;
                    
                        /*if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }*/
                        ?>
                        <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $IR_NUM;?>|<?php echo $IR_DATEV;?>|<?php echo $IR_DUEDATEV;?>|<?php echo $PO_NUM;?>|<?php echo $PO_DATEV;?>|<?php echo $IR_AMOUNT2;?>|<?php echo $GTTax;?>|<?php echo $GIR_AMOUNTV;?>|<?php echo $IR_NOTE;?>|<?php echo $IR_CODE;?>|<?php echo $PO_CODE;?>|<?php echo $OPNH_RETAMN;?>|<?php echo $TotDisc;?>|<?php echo $TotDP;?>" onClick="pickThis(this);" /></td>
                        <td nowrap>
                            <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                            <?php echo $IR_CODE; ?></a> 
                            <input type="hidden" name="PR_NUM<?php echo $totRow; ?>" id="PR_NUM<?php echo $totRow; ?>" value="<?php echo $IR_NUM; ?>" />
                        </td>
                        <td style="text-align:center" nowrap><?php echo date('d M Y', strtotime($IR_DATEV)); ?></td>
                        <td><?php echo $IR_NOTE; ?></td>
                        <td style="text-align:right"><?php echo number_format($IR_AMOUNT2, $decFormat); ?></td>
                        <td style="text-align:right"><?php echo number_format($GTTax, $decFormat); ?></td>
                        <td style="text-align:right"><?php echo number_format($TotDisc, $decFormat); ?></td>
                        <td style="text-align:right"><?php echo number_format($OPNH_RETAMN, $decFormat); ?></td>
                        <td style="text-align:right; font-weight:bold"><?php echo number_format($GIR_AMOUNTV, $decFormat); ?></td>
                        </tr>
                    <?php
                    endforeach;
                }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="10" nowrap>
                <button class="btn btn-primary" type="button" onClick="get_ir();">
                    <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                </button> 
                <button class="btn btn-danger" type="button" onClick="window.close()">
                    <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                </button>
                </td>
            </tr>
            </tfoot>
        </table>
        </form>
                
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
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
	/*if(selectedRows > 1)
	{
		swal('Please select one Request');
		return false;
	}*/
	
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_ir() 
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
					
					window.opener.add_IR(document.frmSearch.chk[i].value);				
				}
			}
		}
		else
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_IR(document.frmSearch.chk.value);
				//swal('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					swal(B)
				}*/
			}
		}
		window.close();		
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>