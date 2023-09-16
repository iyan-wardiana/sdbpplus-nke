<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 11 November 2017
	* File Name	= v_po_form.php
	* Location		= -
*/

// $this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat	= 2;

$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE']; 

$PRJHO 			= "";
$PRJNAME		= '';
$PO_RECEIVLOC	= '';
$sql 			= "SELECT PRJCODE_HO, PRJNAME, PRJADD FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 		= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJHO 			= $row ->PRJCODE_HO;
	$PRJNAME 		= $row ->PRJNAME;
	$PO_RECEIVLOC 	= $row ->PRJADD;
endforeach;

$currentRow = 0;
$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
$r_PattC 	= $this->db->count_all($s_PattC);
if($r_PattC > 0)
{
	$isSetDocNo = 1;
	$s_Patt		= "SELECT Pattern_Code, Pattern_Length FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
	$r_Patt 	= $this->db->query($s_Patt)->result();
	foreach($r_Patt as $row) :
		$PATTCODE 	= $row->Pattern_Code;
	endforeach;
}
else
{
	$PATTCODE 		= "XXX";
}

if($task == 'add')
{
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	
	$PO_NUM			= '';
	$PR_NUM			= '';
	$PR_CODE		= '';
	$PO_CODE 		= '';
	$PO_TYPE 		= 1; 		// Internal menjadi 1 = PO, 2 = RO
	$PO_CAT			= 0;		// In Direct
	$PO_DATE		= '';
	$SPLCODE 		= '0';
	$SPLDESC 		= '';
	$SPLADD1 		= '';
	$PO_CURR 		= '';
	$PO_CURRATE		= 1;
	$PO_TAXCURR 	= 'IDR';
	$PO_TAXRATE 	= 1;
	$PO_TOTCOST		= 0;
	$DP_CODE		= '';
	$PO_DPPER 		= 0;
	$PO_DPVAL 		= 0;
	$DP_PPN_		= 0;
	$DP_JUML		= 0;
	$PO_PAYTYPE 	= 0;
	$PO_TENOR 		= 0;
	$PO_STAT 		= 1;
	$PO_INVSTAT		= 0;					
	$PO_NOTES		= '';
	$PO_NOTES1 		= '';
	$PO_PAYNOTES	= '';
	$IR_VOLM		= 0;
	
	$totalAmount 		= '0.00';
	$BtotalAmount 		= '0.00';
	$totalDiscAmount 	= '0.00';
	$totalAmountAfDisc 	= '0.00';
	$totTaxPPnAmount 	= '0.00';
	$totTaxPPhAmount 	= '0.00';
	$GtotalAmount 		= '0.00';
	$Base_ITM_COST		= '0.00';
	$totDiscPrice 		= '0.00';
	$BtotDiscPrice 		= '0.00';
	$ITM_COST 			= '0.00';					
	$proj_Number 		= '';

	if($PO_TYPE == 1) 
	{
		$POType = '01';
	}
	else if($PO_TYPE == 2) 
	{
		$POType = '02';
	}
	
	$TRXTIME		= date('ymdHis');
	$PO_NUM			= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$PO_CODE		= $DocNumber;

	$PO_DATE 		= date('d/m/Y');
	$ETD			= $PO_DATE;
	$PO_PLANIRY		= date('Y');
	$PO_PLANIRM		= date('m');
	$PO_PLANIRD		= date('d');
	$PO_PLANIR		= date('d/m/Y');
	$PO_NOTESIR 	= "";
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	$PR_NUMX		= '';
	
	$PO_RECEIVCP	= '';
	$PO_SENTROLES	= '';
	$PO_REFRENS		= '';
	$PO_CONTRNO 	= '';
	$PO_CATEG 		= 0;

	$PO_TAXCODE1 	= '';
}
else
{
	$isSetDocNo = 1;
	$PO_NUM 		= $default['PO_NUM'];
	$DocNumber		= $PO_NUM;
	$PO_CODE 		= $default['PO_CODE'];
	$PO_CATEG 		= $default['PO_CATEG'];
	$PO_TYPE 		= $default['PO_TYPE'];
	$PO_CAT 		= $default['PO_CAT'];
	$PO_DATE 		= $default['PO_DATE'];
	$PO_DATE		= date('d/m/Y', strtotime($PO_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$DEPCODE 		= $default['DEPCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$PR_NUM 		= $default['PR_NUM'];
	$PR_NUMX		= $PR_NUM;
	$PO_CURR 		= $default['PO_CURR'];
	$PO_DPPER 		= $default['PO_DPPER'];
	$PO_DPVAL 		= $default['PO_DPVAL'];
	$PO_CURRATE 	= $default['PO_CURRATE'];
	$PO_PAYTYPE 	= $default['PO_PAYTYPE'];
	$PO_TENOR 		= $default['PO_TENOR'];
	$PO_PLANIR 		= $default['PO_PLANIR'];
	$PO_PLANIR		= date('d/m/Y', strtotime($PO_PLANIR));
	$PO_NOTESIR 	= $default['PO_NOTESIR'];
	$PO_NOTES 		= $default['PO_NOTES'];
	$PO_NOTES1 		= $default['PO_NOTES1'];
	$PO_PAYNOTES	= $default['PO_PAYNOTES'];
	$PO_MEMO 		= $default['PO_MEMO'];
	$PRJNAME1 		= $default['PRJNAME'];
	$PO_STAT 		= $default['PO_STAT'];
	$PO_TOTCOST		= $default['PO_TOTCOST'];
	$lastPatternNumb1= $default['Patt_Number'];
	
	$PO_TAXRATE			= 1;
	$totTaxPPnAmount	= 1;
	$totTaxPPhAmount	= 1;
	
	$PO_RECEIVLOC	= $default['PO_RECEIVLOC'];
	$PO_RECEIVCP	= $default['PO_RECEIVCP'];
	$PO_SENTROLES 	= $default['PO_SENTROLES'];
	$PO_REFRENS 	= $default['PO_REFRENS'];
	$PO_CONTRNO 	= $default['PO_CONTRNO'];

	$PO_TAXCODE1 	= $default['PO_TAXCODE1'];
	
	$PR_CODE = "";
	$sqlJCOB		= "SELECT PR_CODE FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
	$resJCOB 		= $this->db->query($sqlJCOB)->result();
	foreach($resJCOB as $rowJOB) :
		$PR_CODE 	= $rowJOB->PR_CODE;
	endforeach;
}

$JOBCODE	= '';
$PO_CATEG 	= 0;
$PR_DIVID 	= "";
if(isset($_POST['PR_NUMX']))
{
	$PR_NUMX		= $_POST['PR_NUMX'];
	$PO_CODE		= $_POST['PO_CODEY'];
	$sqlJCOB		= "SELECT PR_CODE, JOBCODE, PR_CATEG, PR_DIVID FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
	$resJCOB 		= $this->db->query($sqlJCOB)->result();
	foreach($resJCOB as $rowJOB) :
		$PR_CODE 	= $rowJOB->PR_CODE;
		$JOBCODE 	= $rowJOB->JOBCODE;
		$PO_CATEG 	= $rowJOB->PR_CATEG;
		$PR_DIVID 	= $rowJOB->PR_DIVID;
		$MenuApp 	= $rowJOB->PR_DIVID;
	endforeach;
}
else
{
	$PR_CODE		= $PR_CODE;
	$PR_NUMX		= $PR_NUMX;
	$sqlJCOB		= "SELECT JOBCODE, PR_CATEG FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
	$resJCOB 		= $this->db->query($sqlJCOB)->result();
	foreach($resJCOB as $rowJOB) :
		$JOBCODE 	= $rowJOB->JOBCODE;
		$PO_CATEG 	= $rowJOB->PR_CATEG;
	endforeach;
}

// REJECT FUNCTION
	// CEK ACCESS OTORIZATION
		$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN020'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		$resAPP	= $this->db->count_all($sqlAPP);
	// CEK IR
		$DOC_NO	= '';
		$sqlIRC	= "tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9)";
		$isUSED	= $this->db->count_all($sqlIRC);
		if($isUSED > 0)
		{
			$sqlIR 	= "SELECT IR_CODE FROM tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9) LIMIT 1";
			$resIR	= $this->db->query($sqlIR)->result();
			foreach($resIR as $rowIR):
				$DOC_NO	= $rowIR->IR_CODE;
			endforeach;
		}

$secGenCode	= base_url().'index.php/c_purchase/c_p180c21o/genCode/'; // Generate Code

$AddItm 	= base_url().'index.php/c_purchase/c_pr180d0c/addItmTmp/?id=';
$AddDesc	= base_url().'index.php/c_purchase/c_pr180d0c/addDesc/?id=';
$updDESC 	= base_url().'index.php/c_purchase/c_pr180d0c/updDESC/?id=';
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

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }

		table.dataTable tbody td {
			vertical-align: middle;
			white-space:nowrap;
		}

		.uploaded_area {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
		}

		.file {
			display: grid;
			grid-template-columns: max-content 1fr;
			grid-template-areas: "iconfile titlefile"
								 "iconfile actfile";
		}

		.iconfile {
			grid-area: iconfile;
			padding-right: 5px;
		}

		.titlefile {
			grid-area: titlefile;
			font-size: 8pt;
		}

		.actfile {
			grid-area: actfile;
			font-size: 8pt;
		}

		table.dataTable tfoot td {
			background-color:rgba(140, 145, 141, 0.5);
			padding: 8px;
		}
		
		.odd#dtTable {
			display: none;
		}
	</style>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE	= $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Request')$Request = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'payMethod')$payMethod = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
			if($TranslCode == 'QuotNo')$QuotNo = $LangTransl;
			if($TranslCode == 'DPValue')$DPValue = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'usedItm')$usedItm = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'TotalPrice')$TotalPrice = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Specification')$Specification = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			//Updated [iyan]
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Freight')$Freight = $LangTransl;
			if($TranslCode == 'Substitute')$Substitute = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Wage')$Wage = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;

			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'PREmpty')$PREmpty = $LangTransl;
			if($TranslCode == 'selSpl')$selSpl = $LangTransl;
			if($TranslCode == 'OrdMTReq')$OrdMTReq = $LangTransl;
			if($TranslCode == 'greaterQty')$greaterQty = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'DateRequest')$DateRequest = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
			if($TranslCode == 'Deviation')$Deviation = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'POVol')$POVol = $LangTransl;
			if($TranslCode == 'Receiver')$Receiver = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'dokLam')$dokLam = $LangTransl;
			if($TranslCode == 'ItmList')$ItmList = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;

		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= 'Jumlah pemesanan tidak boleh kosong';
			$alert2		= 'Silahkan pilih nama supplier';
			$alert3		= 'Tidak ada item yang akan dipesan.';
			$isManual	= "Centang untuk kode manual.";
			$alertREJ	= "Tidak dapat direject. Sudah digunakan oleh Dokumen No.: ";
			$reqList 	= "Daftar Permintaan";
			$selReq 	= "Silahkan pilih nomor permintaan";
			//Update [iyan]
			$alert4		= "Silahkan pilih salah satu item upah angkut yang akan diminta";
			$alert5		= 'Silahkan tentukan mata uang PO - IDR (Rp) / USD';
			$alert6 	= "Masukan volume yang akan dibatalkan.";
			$alert7 	= "Hanya File PDF yang bisa diunggah";
			$alert8 	= "Anda yakin akan menghapus file ini?";
			$ContractNo = "No.Kontrak";
			$noAtth 	= "Tidak ada dokumen dilampirkan";
			$NotesNE 	= "Catatan tidak boleh kosong.";
			$alert17	= "Anda yakin akan menghapus komponen ini?";
		}
		else
		{
			$alert1		= 'Qty order can not be empty';
			$alert2		= 'Please select a supplier name';
			$alert3		= 'There are no items to order.';
			$isManual	= "Check to manual code.";
			$alertREJ	= "Can not be rejected. Used by document No.: ";
			$reqList 	= "Request List";
			$selReq 	= "Please select a Request No.";
			$alert4		= "Please select a freight item that will be requested";
			$alert5		= 'Please select a PO Curency - IDR (Rp) / USD';
			$alert6 	= "Please enter the value that will you canceled.";
			$alert7 	= "Only file PDF can be uploaded";
			$alert8 	= "Are you sure want to delete this file?";
			$ContractNo = "Contract No.";
			$noAtth 	= "No document(s) attached";
			$NotesNE 	= "Notes can not empty.";
			$alert17	= "Are you sure wanto delete this component?";
		}
		
		if($task == 'add')
		{
			$PR_RECEIPTD = date('m/d/Y');
			$PR_MEMO	= '';
			$sqlRECD	= "SELECT PR_RECEIPTD, PR_MEMO FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
			$resRECD	= $this->db->query($sqlRECD)->result();
			foreach($resRECD as $rowRD) :
				$PR_RECEIPTD	= $rowRD->PR_RECEIPTD;
				$PR_MEMO		= $rowRD->PR_MEMO;
			endforeach;
			$PO_PLANIR	= $PR_RECEIPTD;
			if($PO_PLANIR == '0000-00-00')
				$PO_PLANIR = date('d/m/Y');
			else
				$PO_PLANIR = date('d/m/Y',strtotime($PO_PLANIR));
		}
		else
		{
			$PO_PLANIR		= $PO_PLANIR;
			$PO_RECEIVLOC	= $PO_RECEIVLOC;
		}

		// SETTINGAN UNTUK APPROVAL PER DIVISI
			if($PRJHO == 'KTR')
			{
				$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_po_detail A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'";
				$r_DIV 	= $this->db->query($s_DIV)->result();
				foreach($r_DIV as $rw_DIV):
					$MenuApp 	= $rw_DIV->JOBCOD1;

					$s_UPD1 	= "UPDATE tbl_po_detail SET PO_DIVID = '$MenuApp' WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_UPD1);

					$s_UPD2 	= "UPDATE tbl_po_header SET PO_DIVID = '$MenuApp' WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_UPD2);
				endforeach;
			}
			
			if($MenuApp == 'MN437')
				$MenuApp 	= "MN447";
			elseif($MenuApp == 'MN438')
				$MenuApp 	= "MN448";
			elseif($MenuApp == 'MN439')
				$MenuApp 	= "MN449";
			elseif($MenuApp == 'MN440')
				$MenuApp 	= "MN450";
			elseif($MenuApp == 'MN441')
				$MenuApp 	= "MN451";
			elseif($MenuApp == 'MN442')
				$MenuApp 	= "MN452";
			elseif($MenuApp == 'MN443')
				$MenuApp 	= "MN453";
			elseif($MenuApp == 'MN444')
				$MenuApp 	= "MN454";
			elseif($MenuApp == 'MN445')
				$MenuApp 	= "MN455";
			elseif($MenuApp == 'MN446')
				$MenuApp 	= "MN456";
			else
				$MenuApp 	= "MN020";
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PO_TOTCOST
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP		= $rowAPP->MAX_STEP;
					$APPROVER_1		= $rowAPP->APPROVER_1;
					if($APPROVER_1 != '')
					{
						$EMPN_1		= '';
						$sqlEMPC_1	= "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
						$resEMPC_1	= $this->db->count_all($sqlEMPC_1);
						if($resEMPC_1 > 0)
						{
							$sqlEMP_1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
							$resEMP_1	= $this->db->query($sqlEMP_1)->result();
							foreach($resEMP_1 as $rowEMP) :
								$FN_1	= $rowEMP->First_Name;
								$LN_1	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_1		= "$FN_1 $LN_1";
						}
					}
					$APPROVER_2	= $rowAPP->APPROVER_2;
					if($APPROVER_2 != '')
					{
						$EMPN_2		= '';
						$sqlEMPC_2	= "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
						$resEMPC_2	= $this->db->count_all($sqlEMPC_2);
						if($resEMPC_2 > 0)
						{
							$sqlEMP_2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
							$resEMP_2	= $this->db->query($sqlEMP_2)->result();
							foreach($resEMP_2 as $rowEMP) :
								$FN_2	= $rowEMP->First_Name;
								$LN_2	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_2		= "$FN_2 $LN_2";
						}
					}
					$APPROVER_3	= $rowAPP->APPROVER_3;
					if($APPROVER_3 != '')
					{
						$EMPN_3		= '';

						$sqlEMPC_3	= "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
						$resEMPC_3	= $this->db->count_all($sqlEMPC_3);
						if($resEMPC_3 > 0)
						{
							$sqlEMP_3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
							$resEMP_3	= $this->db->query($sqlEMP_3)->result();
							foreach($resEMP_3 as $rowEMP) :
								$FN_3	= $rowEMP->First_Name;
								$LN_3	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_3		= "$FN_3 $LN_3";
						}
					}
					$APPROVER_4	= $rowAPP->APPROVER_4;
					if($APPROVER_4 != '')
					{
						$EMPN_4		= '';
						$sqlEMPC_4	= "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
						$resEMPC_4	= $this->db->count_all($sqlEMPC_4);
						if($resEMPC_4 > 0)
						{
							$sqlEMP_4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
							$resEMP_4	= $this->db->query($sqlEMP_4)->result();
							foreach($resEMP_4 as $rowEMP) :
								$FN_4	= $rowEMP->First_Name;
								$LN_4	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_4		= "$FN_4 $LN_4";
						}
					}
					$APPROVER_5	= $rowAPP->APPROVER_5;
					if($APPROVER_5 != '')
					{
						$EMPN_5		= '';
						$sqlEMPC_5	= "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
						$resEMPC_5	= $this->db->count_all($sqlEMPC_5);
						if($resEMPC_5 > 0)
						{
							$sqlEMP_5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
							$resEMP_5	= $this->db->query($sqlEMP_5)->result();
							foreach($resEMP_5 as $rowEMP) :
								$FN_5	= $rowEMP->First_Name;
								$LN_5	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_5		= "$FN_5 $LN_5";
						}
					}
				endforeach;
				$disableAll	= 0;
			
				// CHECK AUTH APPROVE TYPE
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				
				$BefStepApp	= $APP_STEP - 1;
				if($resC_App == $BefStepApp)
				{
					$canApprove	= 1;
				}
				elseif($resC_App == $APP_STEP)
				{
					$canApprove	= 0;
					$descApp	= "You have Approved";
					$statcoloer	= "success";
				}
				else
				{
					$canApprove	= 0;
					$descApp	= "Awaiting";
					$statcoloer	= "warning";
				}
							 
				if($APP_STEP == $MAX_STEP)
					$IS_LAST		= 1;
				else
					$IS_LAST		= 0;
				
				// Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
				// This roles are for All Approval. Except PR and Receipt
				// NOTES
				// $APPLIMIT_1 		= Maximum Limit to Approve
				// $APPROVE_AMOUNT	= Amount must be Approved
				$APPROVE_AMOUNT 	= $PO_TOTCOST;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
				}
			}
			else
			{
				$canApprove	= 0;
				$descApp	= "You can not approve this document.";
				$statcoloer	= "danger";
				$IS_LAST	= 0;
				$APP_STEP	= 0;
			}
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE
		
		$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
		$resultPRJ 		= $this->db->query($sqlPRJ)->result();
		
		foreach($resultPRJ as $rowPRJ) :
			$PRJNAMEHO 	= $rowPRJ->PRJNAME;
		endforeach;
		
		$avail_ed		= 0;
		if($PO_STAT == 1 || $PO_STAT == 4)
			$avail_ed	= 1;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_ord.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="text" name="PR_NUMX" id="PR_NUMX" class="textbox" value="<?php echo $PR_NUMX; ?>" />
                    <input type="text" name="PO_CODEY" id="PO_CODEY" class="textbox" value="<?php echo $PO_CODE; ?>" />
                    <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
                <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
                    <table>
                        <tr>
                            <td>
                                <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
                                <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
                                <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
                                <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
                                <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
                                <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
                                <input type="hidden" name="PODate" id="PODate" value="">
                            </td>
                            <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                        </tr>
                    </table>
                </form>
                <!-- End -->
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		            			<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
		                    	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
				                <input type="hidden" name="selROW" id="selROW" value="">
				                <input type="hidden" name="selITM" id="selITM" value="">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
		                        <input type="hidden" name="JOBCODE" id="JOBCODE" value="<?php echo $JOBCODE; ?>">
		                        <input type="hidden" name="PO_TYPE" id="PO_TYPE" value="<?php echo $PO_TYPE; ?>">
		                        <input type="hidden" name="PRJHO" id="PRJHO" value="<?php echo $PRJHO; ?>">
								<?php if($isSetDocNo == 0) { ?>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
			                            <div class="col-sm-9">
			                                <div class="alert alert-danger alert-dismissible">
			                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                                    <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
			                                    <?php echo $docalert2; ?>
			                                </div>
			                            </div>
			                        </div>
		                        <?php } ?>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PONumber ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:195px" name="PO_NUM1" id="PO_NUM1" value="<?php echo $PO_NUM; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="PO_NUM" id="PO_NUM" size="30" value="<?php echo $PO_NUM; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
		                          	<div class="col-sm-4">
		                            	<input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="PO_CODE" id="PO_CODE" value="<?php echo $PO_CODE; ?>" >
		                            	<input type="text" class="form-control" name="PO_CODEX" id="PO_CODEX" value="<?php echo $PO_CODE; ?>" disabled >
		                          	</div>
		                          	<div class="col-sm-3">
		                                <div class="input-group date">
		                                    <!-- <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div> -->
		                                    <input type="text" name="PO_DATE1" class="form-control pull-left" id="PO_DATE1" value="<?php echo $PO_DATE; ?>" disabled>
		                                    <input type="hidden" name="PO_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PO_DATE; ?>">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-2">
		                        		<select name="PO_CURR" id="PO_CURR" class="form-control select2">
		                                	<option value="" > --- </option>
		                                	<option value="IDR" <?php if($PO_CURR == 'IDR') { ?> selected <?php } ?>>IDR</option>
		                                	<option value="USD" <?php if($PO_CURR == 'USD') { ?> selected <?php } ?>>USD</option>    
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
		                          	<div class="col-sm-9">
		                                <label>
		                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
		                                </label>
		                                <label style="font-style:italic">
		                                    <?php echo $isManual; ?>
		                                </label>
		                          	</div>
		                        </div>
		                        <script>
		                            function getPO_NUM(selDate)
		                            {
		                                document.getElementById('PODate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
			
									/*$(document).ready(function()
									{
										$(".tombol-date").click(function()
										{
											var add_PO	= "<?php echo $secGenCode; ?>";
											var formAction 	= $('#sendDate')[0].action;
											var data = $('.form-user').serialize();
											$.ajax({
												type: 'POST',
												url: formAction,
												data: data,
												success: function(response)
												{
													var myarr = response.split("~");
													document.getElementById('PO_NUM1').value 	= myarr[0];
													document.getElementById('PO_NUM').value 	= myarr[0];
													document.getElementById('PO_CODE').value 	= myarr[1];
													document.getElementById('PO_CODEX').value 	= myarr[1];
												}
											});
										});
									});*/
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">No. SPP</label>
		                          	<div class="col-sm-4">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdl_addPR" title="Cari SPP"><i class="glyphicon glyphicon-search"></i></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="PR_CODE" id="PR_CODE" style="max-width:160px" value="<?php echo $PR_CODE; ?>" >
		                                    <input type="text" class="form-control" name="PR_NUM1" id="PR_NUM1" data-toggle="tooltip" value="<?php echo $PR_CODE; ?>" data-toggle="modal" data-target="#mdl_addPR" disabled>
		                                </div>
		                            </div>
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
				                    <div class="col-sm-3">
		                                <select name="PO_CATEG1" id="PO_CATEG1" class="form-control select2" disabled>
                                            <option value="0"<?php if($PO_CATEG == 0) { ?> selected <?php } ?>>---</option>
                                            <option value="1"<?php if($PO_CATEG == 1) { ?> selected <?php } ?>>Alat</option>
		                                </select>
		                                <input type="hidden" class="form-control" name="PO_CATEG" id="PO_CATEG" style="max-width:160px" value="<?php echo $PO_CATEG; ?>" >
				                    </div>
		                        </div>
								<?php
									//$url_selAURCODE	= site_url('c_asset/c_asset_usage/popupallaur/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
									$url_selPR_CODE		= site_url('c_purchase/c_p180c21o/popupallPR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selPR_CODE;?>";
									function pleaseCheck()
									{
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
		                          <option value="none">--- None ---</option>
		                          <?php echo $i = 0;
		                            if($countPRJ > 0)
		                            {
		                                foreach($vwPRJ as $row) :
		                                    $PRJCODE1 	= $row->PRJCODE;
		                                    $PRJNAME 	= $row->PRJNAME;
		                                    ?>
		                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
		                                  <?php
		                                endforeach;
		                            }
		                            else
		                            {
		                                ?>
		                                  <option value="none">--- No Unit Found ---</option>
		                              <?php
		                            }
		                            ?>
		                        </select>
		                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName ?> </label>
		                          	<div class="col-sm-9">
		                        		<select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" onChange="getTOP(this.value)" >
		                        			<option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>> --- </option>
		                                    <?php
		                                    if($countVend > 0)
		                                    {
		                                        foreach($vwvendor as $row) :
		                                            $SPLCODE1	= $row->SPLCODE;
		                                            $SPLDESC1	= $row->SPLDESC;
		                                            ?>
		                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div> 
		                        <script>
									function getTOP(SPLCODE)
									{
										var PO_CURR = document.getElementById('PO_CURR').value;
										if(PO_CURR == '')
										{
											
											swal({
									            text: "<?php echo $alert5; ?> Secara default akan kami set ke IDR (Rp) !",
									            icon: "warning",
									            buttons: ["No", "Yes"],
									        })
									        .then((willDelete) => 
									        {
									            if (willDelete) 
									            {
									            	$('#PO_CURR').val('IDR').trigger('change');
									            } 
									        });
										}

										if(window.XMLHttpRequest)
										{
											//code for IE7+,Firefox,Chrome,Opera,Safari
											xmlhttpTask=new XMLHttpRequest();
										}
										else
										{
											xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
										}
										xmlhttpTask.onreadystatechange=function()
										{
											if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
											{
												if(xmlhttpTask.responseText != '')
												{
													var colData  	= xmlhttpTask.responseText;
													var SPLTOP1		= colData.split("~");
													var SPLTOP		= SPLTOP1[0];
													var SPLTOPD		= SPLTOP1[1];
													$('#PO_PAYTYPE').val(SPLTOP).trigger('change');
													$('#PO_TENOR').val(SPLTOPD).trigger('change');
												}
											}
										}
										xmlhttpTask.open("GET","<?php echo base_url().'index.php/c_purchase/C_p180c21o/getDetTOP/';?>"+SPLCODE,true);
										xmlhttpTask.send();
									}
								</script>
		                        <div class="form-group">
		                       	  	<label for="inputName" class="col-sm-3 control-label"><?php echo $PaymentType ?> </label>
                                	<div class="col-sm-4">
                                		<!-- <input type="hidden" class="form-control" name="PO_PAYTYPE" id="PO_PAYTYPE" size="30" value="<?php echo $PO_PAYTYPE; ?>" /> -->
		                                <select name="PO_PAYTYPE" id="PO_PAYTYPE" class="form-control select2" onChange="selPO_PAYTYPE(this.value)">
		                                    <option value="0" <?php if($PO_PAYTYPE == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="1" <?php if($PO_PAYTYPE == 1) { ?> selected <?php } ?>>Credit</option>
		                                </select>
                                	</div>
                                	<div class="col-sm-5">
                                		<!-- <input type="hidden" class="form-control" name="PO_TENOR" id="PO_TENOR" size="30" value="<?php echo $PO_TENOR; ?>" /> -->
		                                <select name="PO_TENOR" id="PO_TENOR" class="form-control select2">
		                                    <option value="0" <?php if($PO_TENOR == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($PO_TENOR == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="14" <?php if($PO_TENOR == 14) { ?> selected <?php } ?>>14 Days</option>
		                                    <option value="21" <?php if($PO_TENOR == 21) { ?> selected <?php } ?>>21 Days</option>
		                                    <option value="30" <?php if($PO_TENOR == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($PO_TENOR == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($PO_TENOR == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="101" <?php if($PO_TENOR == 101) { ?> selected <?php } ?>>Back to Back</option>
		                                    <option value="120" <?php if($PO_TENOR == 102) { ?> selected <?php } ?>>Turn Key</option>
		                                </select>
                                	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $QuotNo; ?></label>
		                            <div class="col-sm-3">
		                                <input type="text" class="form-control" name="PO_REFRENS" id="PO_REFRENS" size="30" value="<?php echo $PO_REFRENS; ?>" />
		                            </div>
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ContractNo; ?></label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="PO_CONTRNO" id="PO_CONTRNO" size="30" value="<?php echo $PO_CONTRNO; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $payMethod; ?></label>
		                            <div class="col-sm-9">
		                                <!-- <input type="text" class="form-control" name="PO_PAYNOTES" id="PO_PAYNOTES" value="<?php // echo $PO_PAYNOTES; ?>" /> -->
										<textarea class="form-control" name="PO_PAYNOTES" id="PO_PAYNOTES" rows="4"><?php echo $PO_PAYNOTES; ?></textarea>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-6">
		                            	<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default" style="text-align: left; width: 100%;">
						                	<font size="2" id="PO_TOTCOSTD1">Total OP</font><br>
						                	<font size="4" id="PO_TOTCOSTD2"><?php echo number_format($PO_TOTCOST, 2); ?></font>
						              	</button>
		                            </div>
		                            <div class="col-sm-3">
					                	<label for="inputName" class="control-label">DP (%)</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PO_DPPERX" id="PO_DPPERX" value="<?php echo number_format($PO_DPPER, $decFormat); ?>" onBlur="getDPer()">
		                                <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PO_DPPER" id="PO_DPPER" value="<?php echo $PO_DPPER; ?>">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PO_DPVAL" id="PO_DPVAL" value="<?php echo $PO_DPVAL; ?>">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="PO_DPVALX" id="PO_DPVALX" value="<?php echo number_format($PO_DPVAL, 2); ?>">
		                                <input type="hidden" class="form-control" name="PO_TOTCOSTX" id="PO_TOTCOSTX" size="30" style="text-align: right;" value="<?php echo number_format($PO_TOTCOST, 2); ?>" title="Total PO" readonly />
		                                <input type="hidden" class="form-control" name="PO_TOTCOST" id="PO_TOTCOST" size="30" value="<?php echo $PO_TOTCOST; ?>" title="Total PO" />
		                            </div>
		                        </div>
				                <script>
									function getDPer()
									{
										var decFormat	= document.getElementById('decFormat').value;
										var thisVal1 	= document.getElementById('PO_DPPERX');
										thisVal 		= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

										document.getElementById('PO_DPPER').value	= thisVal;
										document.getElementById('PO_DPPERX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));

										totRow		= document.getElementById('totalrow').value;
										TOTPO_AMN 	= 0;
										if(totRow > 0)
										{
											for(i=1;i<=totRow;i++)
											{
												let myObj 	= document.getElementById('data'+i+'PO_COST');
												var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
												
												if(theObj != null)
												{
													var PO_COST 	= parseFloat(document.getElementById('data'+i+'PO_COST').value);
													var TOTPO_AMN 	= parseFloat(TOTPO_AMN + PO_COST);
												}
											}
										}
										else
										{
											swal("<?php echo $alert3; ?>",
											{
												icon: "warning",
											});
											document.getElementById('PO_DPPER').value 	= 0;
											document.getElementById('PO_DPPERX').value 	= RoundNDecimal(parseFloat(Math.abs(0)), 2);
											document.getElementById('PO_DPPERD2').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
											return false;
										}

										PO_DPVAL		= parseFloat(thisVal * TOTPO_AMN) / 100;
										document.getElementById('PO_DPVAL').value	= RoundNDecimal(parseFloat(Math.abs(PO_DPVAL)),2);
										document.getElementById('PO_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DPVAL)),2));
									}
								</script>

		                        <script>
									function selPO_PAYTYPE(theValue)
									{
										if(theValue == 1)
											$('#PO_TENOR').val(7).trigger('change');
										else
											$('#PO_TENOR').val(0).trigger('change');

										return false;
									}
									
									function selPO_TENOR(theValue)
									{
										if(theValue > 0)
											$('#PO_PAYTYPE').val(1).trigger('change');
										else
											$('#PO_PAYTYPE').val(0).trigger('change');

										return false;
									}
								</script>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label"><?php echo "PPn"; ?></label>
									<div class="col-sm-9">
										<select name="PO_TAXCODE1" class="form-control select2" id="PO_TAXCODE1" data-placeholder="Please select PPn">
											<option value=""></option>
											<?php
												$sPPN		= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn";
												$rPPN		= $this->db->query($sPPN)->result();
												foreach($rPPN as $rwPPN) :
													$TAXLA_NUM	= $rwPPN->TAXLA_NUM;
													$TAXLA_DESC	= $rwPPN->TAXLA_DESC;
													?>
													<option value="<?php echo $TAXLA_NUM; ?>" <?php if ($TAXLA_NUM == $PO_TAXCODE1) { ?> selected <?php } ?>><?php echo $TAXLA_DESC; ?></option>
													<?php
												endforeach;
											?>
										</select>
									</div>
								</div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptLoc; ?></label>
		                            <div class="col-sm-5">
		                            	<input type="text" class="form-control" name="PO_RECEIVLOC" id="PO_RECEIVLOC" value="<?php echo $PO_RECEIVLOC; ?>" />
		                            </div>
		                            <div class="col-sm-4">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="PO_PLANIR" class="form-control pull-left" id="datepicker2" value="<?php echo $PO_PLANIR; ?>" style="width:106px">
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Receiver ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="PO_RECEIVCP" id="PO_RECEIVCP" value="<?php echo $PO_RECEIVCP; ?>" />
		                          	</div>
		                        </div>
								<div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo "Ket. Pengiriman"; ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="PO_NOTESIR"  id="PO_NOTESIR" rows="1"><?php echo $PO_NOTESIR; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $SentRoles; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="PO_SENTROLES" id="PO_SENTROLES" value="<?php echo $PO_SENTROLES; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="PO_NOTES"  id="PO_NOTES"><?php echo $PO_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div id="revMemo" class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?></label>
		                            <div class="col-sm-9">
		                                <textarea name="PO_NOTES1" class="form-control" id="PO_NOTES1" disabled><?php echo $PO_NOTES1; ?></textarea>
		                            </div>
		                        </div>
		                        <!--
		                        	APPROVE STATUS
		                            1 - New
		                            2 - Confirm
		                            3 - Approve
		                        -->
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>

		                          	<div class="col-sm-4">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PO_STAT; ?>">
		                                <?php
											$isDisabled = 1;
											if($PO_STAT == 1 || $PO_STAT == 4)
											{
												$isDisabled = 0;
											}

											// CHEK APAKAH SUDHA DIBUATKAN LPM
												$can_revise = 0;
												$s_LPMC 	= "tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE' AND IR_STAT IN (1,2,3,4,6)";
												$r_LPMC 	= $this->db->count_all($s_LPMC);
												if($r_LPMC == 0)
													$can_revise 	= 1;
										?>
		                                <select name="PO_STAT" id="PO_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
		                                    <?php
												$disableBtn	= 0;
												if($PO_STAT == 1 || $PO_STAT == 2 || $PO_STAT == 4 || $PO_STAT == 5 || $PO_STAT == 6 || $PO_STAT == 9)
												{
													$disableBtn	= 1;
												}

												if($PO_STAT != 1 AND $PO_STAT != 4) 
												{
													?>
														<option value="1"<?php if($PO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
														<option value="2"<?php if($PO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
														<option value="3"<?php if($PO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
														<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
														<option value="5"<?php if($PO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
														<option value="6"<?php if($PO_STAT == 6) { ?> selected <?php } if($PO_STAT != 3) { ?> disabled <?php } ?>>Closed</option>
														<option value="7"<?php if($PO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
														<option value="9"<?php if($PO_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
													<?php
												}
												else
												{
													?>
														<option value="1"<?php if($PO_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($PO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
														<?php if($PO_STAT == 4) { ?>
																<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?>>Revise</option>
														<?php }
												}
		                                    ?>
		                                </select>
		                            </div>
									<div class="col-sm-2" style="padding-right: 0;">
				                        <div class="pull-right" style="display: <?php if($PR_NUMX <> '' && ($PO_STAT == 1 || $PO_STAT == 4)) echo ""; else echo "none";?>;">
				                        	<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdl_addItemPR">
				                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
				                        	</a>
				                        </div>
				                    </div>
		                            <!-- Update [iyan] -->
		                            <div class="col-sm-3" style="padding-left: 0;">
				                        <div class="pull-right" style="display: <?php if($PR_NUMX <> '' && ($PO_STAT == 1 || $PO_STAT == 4)) echo ""; else echo "none";?>;">
				                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addItmU">
				                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $Freight; ?>
				                        	</a>
				                        </div>
				                    </div>
				                    <!-- End Update [iyan] -->
		                        </div>
		                        <?php
		                        /*if($PO_STAT == 3) 
								{
									?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProcessStatus ?> </label>
		                                    <div class="col-sm-9">
		                                        <select name="OP_PROCS" id="OP_PROCS" class="form-control" style="max-width:130px" <?php if($OP_PROCS != 1) { ?> disabled <?php } ?>>
		                                            <option value="1" <?php if($OP_PROCS == 1) { ?> selected <?php } ?>>Processing</option>
		                                            <option value="2" <?php if($OP_PROCS == 2) { ?> selected <?php } ?>>Finish</option>
		                                            <option value="3" <?php if($OP_PROCS == 3) { ?> selected <?php } ?>>Canceled</option>
		                                        </select>
		                                    </div>
		                                </div>
									<?php
								}*/
								$url_AddItem	= site_url('c_purchase/c_p180c21o/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									function chkSTAT(selSTAT)
									{
										if(selSTAT == 4 || selSTAT == 5 || selSTAT == 9)
										{
											var isUSED	= document.getElementById('isUSED').value;
											if(isUSED > 0)
											{
												swal('<?php echo $alertREJ; ?>'+' <?php echo $DOC_NO; ?>',
												{
													icon: "warning",
												});
												return false;
											}
											else
											{
												document.getElementById('btnSave').style.display = '';
												document.getElementById('revMemo').style.display = '';
												document.getElementById('PO_NOTES1').disabled = false;
											}
										}
										else if(selSTAT == 6)
										{
											document.getElementById('btnSave').style.display = '';
											document.getElementById('revMemo').style.display = '';
										}
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
										<script>
											var url = "<?php echo $url_AddItem;?>";
											function selectitem()
											{
												title = 'Select Item';
												w = 1000;
												h = 550;
												//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
												var left = (screen.width/2)-(w/2);
												var top = (screen.height/2)-(h/2);
												return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
											}
										</script>
										<a href="javascript:void(null);" onClick="selectitem();">
											Add Item [+]
		                                </a></div>
		                        </div>
							</div>
						</div>
					</div>

					<?php
						$shAttc 	= 0;
						if($PO_STAT == 1 || $PO_STAT == 4)
						{
							$shAttc = 1;
							$shTInp = 1;
							$smTAtt = 4;
							$smTDok = 8;
						}
						else
						{
							$shTInp = 0;
							$smTAtt = 4;
							$smTDok = 12;
							$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx WHERE REF_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
							$resUPL_DOC = $this->db->query($getUPL_DOC);
							if($resUPL_DOC->num_rows() > 0)
								$shAttc = 1;
						}
					?>

					<div class="col-md-12" <?php if($shAttc == 0) { ?> style="display: none;" <?php } ?>>
						<div class="box box-default">
							<div class="box-header with-border">
								<label for="inputName"><?php echo $dokLam; ?></label>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
              					</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<div class="col-sm-4" <?php if($shTInp == 0) { ?> style="display: none;" <?php } ?>>
				                		<input type="file" class="form-control" name="userfile[]" id="userfile" accept=".pdf" multiple>
										<span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span>
				                	</div>
									<div class="col-sm-<?=$smTDok?>">
										<?php
											// GET Upload Doc TRx
											$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
															WHERE REF_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
											$resUPL_DOC = $this->db->query($getUPL_DOC);
											if($resUPL_DOC->num_rows() > 0)
											{
												?>
													<label>List Uploaded</label>
													<div class="uploaded_area">
												<?php
													$newRow = 0;
													foreach($resUPL_DOC->result() as $rDOC):
														$newRow 		= $newRow + 1;
														$UPL_NUM		= $rDOC->UPL_NUM;
														$REF_NUM		= $rDOC->REF_NUM;
														$REF_CODE		= $rDOC->REF_CODE;
														$UPL_PRJCODE	= $rDOC->PRJCODE;
														$UPL_DATE		= $rDOC->UPL_DATE;
														$UPL_FILENAME	= $rDOC->UPL_FILENAME;
														$UPL_FILESIZE	= $rDOC->UPL_FILESIZE;
														$UPL_FILETYPE	= $rDOC->UPL_FILETYPE;

														?>
															<div class="itemFile_<?=$newRow?>">
																<?php
																	if($UPL_FILETYPE == 'application/pdf') $fileicon = "fa-file-pdf-o";
																	else $fileicon = "fa-file-image-o";

																	if($PO_STAT == 1 || $PO_STAT == 4)
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<!-- Download => Hold 
																					<a href="<?php // echo site_url("c_purchase/c_p180c21o/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
																						<?php // echo $UPL_FILENAME; ?>
																					</a>
																					---------------------- End Hold -------------------------->
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- Hapus File -->
																					<a href="#" onclick="trashItemFile(<?=$newRow?>, '<?php echo $UPL_FILENAME;?>')" title="Hapus File">
																						<i class="fa fa-trash" style="color: red;"></i> Delete
																					</a> 
																					&nbsp;&nbsp;&nbsp;
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																				</div>
																			</div>
																			
																		<?php
																	}
																	else
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<!-- Download => Hold 
																					<a href="<?php // echo site_url("c_purchase/c_p180c21o/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
																						<?php // echo $UPL_FILENAME; ?>
																					</a>
																					---------------------- End Hold -------------------------->
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																				</div>
																			</div>
																		<?php
																	}
																?>
															</div>
														<?php
													endforeach;

												?>
													</div>
												<?php
											}
										?>
									</div>
				                </div>
							</div>
						</div>
					</div>

					<div class="col-md-12" <?php if($shAttc == 1) { ?> style="display: none;" <?php } ?>>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-ban"></i> <?=$noAtth?>
                        </div>
					</div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tblITMDET" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr style="background:#CCCCCC">
                                            <th style="text-align:center; vertical-align: middle;">No.</th>
                                            <th style="text-align:center; display:none" nowrap><?php echo $ItemCode; ?> </th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemName; ?> </th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Request; ?></th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Remain; ?></th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $POVol; ?> </th> 
											<th style="text-align:center; vertical-align: middle; display: none;" nowrap><?php echo "Rekap Item"; ?> </th> 
                                            <!-- Input Manual -->
                                            <th style="text-align:center; vertical-align: middle;" nowrap>Sat.</th>
                                            <th style="text-align:center; vertical-align: middle;" ><?php echo $UnitPrice; ?> </th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?><br>
                                            (%)</th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?> </th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Tax; ?></th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $TotalPrice; ?></th>
                                            <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Specification; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
									<input type="hidden" name="totalrowPR" id="totalrowPR" value="0">
                                    <input type="hidden" name="totalrow" id="totalrow" value="0">
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
	                    <div class="form-group">
                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                            <div class="col-sm-9">
	                        	<?php
	                        		if($resCAPP > 0)
	                        		{
										if($task=='add')
										{
											if(($PO_STAT == 1 || $PO_STAT == 4) && $ISCREATE == 1)
											{
												?>
													<button class="btn btn-primary" id="btnSave">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										else
										{
											if(($PO_STAT == 1 || $PO_STAT == 4) && $ISCREATE == 1)
											{
												?>
													<button class="btn btn-primary" id="btnSave" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											elseif($PO_STAT == 3 && $ISDELETE == 1)
											{
												?>
													<button class="btn btn-primary" id="btnSave" style="display:none" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" id="btnSave" style="display:none" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
									}
									$backURL	= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
								?>
	                        </div>
	                    </div>
	                </div>
				</form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $PO_NUM;
                        $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH	= $this->db->count_all($sqlCAPPH);
						$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
										AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
						$resAPP		= $this->db->query($sqlAPP)->result();
						foreach($resAPP as $rowAPP) :
							$MAX_STEP		= $rowAPP->MAX_STEP;
							$APPROVER_1		= $rowAPP->APPROVER_1;
							$APPROVER_2		= $rowAPP->APPROVER_2;
							$APPROVER_3		= $rowAPP->APPROVER_3;
							$APPROVER_4		= $rowAPP->APPROVER_4;
							$APPROVER_5		= $rowAPP->APPROVER_5;
						endforeach;
						
                    	if($resCAPP == 0)
                    	{
                    		if($LangID == 'IND')
							{
								$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
							}
							else
							{
								$zerSetApp	= "There are no arrangements for the approval of this document.";
							}
                    		?>
                    			<div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <?php echo $zerSetApp; ?>
				              	</div>
                    		<?php
                    	}
                    ?>
	                <div class="row">
	                    <div class="col-md-12">
	                        <div class="box box-danger collapsed-box">
	                            <div class="box-header with-border">
	                                <h3 class="box-title"><?php echo $Approval; ?></h3>
	                                <div class="box-tools pull-right">
	                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
	                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                                    </button>
	                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
	                                    </button>
	                                </div>
	                            </div>
	                            <div class="box-body">
						            <div class="box-body no-padding">
		                        		<div class="search-table-outter">
							              	<table id="tbl" class="table table-striped" width="100%" border="0">
												<?php
													$s_STEP		= "SELECT DISTINCT APP_STEP FROM tbl_docstepapp_det
																	WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' ORDER BY APP_STEP";
													$r_STEP		= $this->db->query($s_STEP)->result();
													foreach($r_STEP as $rw_STEP) :
														$STEP	= $rw_STEP->APP_STEP;
														$HIDE 	= 0;
														?>
											                <tr>
											                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
																<?php
																	$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP'";
								                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
								                                    if($r_APPH_1 > 0)
								                                    {
																		$s_00	= "SELECT DISTINCT A.AH_APPROVER, A.AH_APPROVED,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																					WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->AH_APPROVER;
																			$APP_NME_1	= $rw_00->complName;
																			$APP_DAT_1	= $rw_00->AH_APPROVED;

									                                    	$APPCOL 	= "success";
									                                    	$APPIC 		= "check";
																			?>
																				<td style="width: 2%;">
																					<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																						<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																					</div>
																				</td>
																				<td>
																					<?=$APP_NME_1?><br>
																					<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APP_DAT_1?></span>
																				</td>
																			<?php
																		endforeach;
																	}
																	else
																	{
																		$s_00	= "SELECT DISTINCT A.APPROVER_1,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
																					WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND APP_STEP = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->APPROVER_1;
																			$APP_NME_1	= $rw_00->complName;
																			$OTHAPP 	= 0;
																			$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
										                                    if($r_APPH_1 > 0)
										                                    {
										                                    	$HIDE 	= 1;
										                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT	= $rw_01->AH_APPROVED;
										                                        endforeach;

										                                    	$APPCOL 	= "success";
										                                    	$APPIC 		= "check";
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																					</td>
																				<?php
										                                    }
										                                    else
										                                    {
										                                    	$APPCOL 	= "danger";
										                                    	$APPIC 		= "close";
										                                    	$APPDT 		= "-";
										                                    	$s_APPH_O	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
											                                    $r_APPH_O	= $this->db->count_all($s_APPH_O);
											                                    if($r_APPH_O > 0)
											                                    	$OTHAPP = 1;
										                                    }
										                                    if($HIDE == 0)
										                                    {
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																					</td>
																				<?php
																			}

																			if($OTHAPP > 0)
																			{
																				$APPDT_OTH 	= "-";
																				$APPNM_OTH 	= "-";
										                                    	$s_01	= "SELECT A.AH_APPROVED, A.AH_APPLEV,
										                                    					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME
										                                    				FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT_LEV	= $rw_01->AH_APPLEV;
										                                            $APPDT_OTH	= $rw_01->AH_APPROVED;
										                                            $APPNM_OTH	= $rw_01->COMPLNAME;

											                                    	$APPCOL 	= "success";
											                                    	$APPIC 		= "check";
																					?>
																		                <tr>
																		                  	<td style="width: 10%" nowrap>&nbsp;</td>
																							<td style="width: 2%;">
																								<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																									<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																								</div>
																							</td>
																							<td>
																								<?=$APPNM_OTH?><br>
																								<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT_OTH?></span>
																							</td>
																						</tr>
																					<?php
										                                        endforeach;
										                                    }
																		endforeach;
																	}
																?>
															</tr>
														<?php
													endforeach;
												?>
							              	</table>
						              	</div>
						            </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
		        </div>
		    </div>
			<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_delItm" id="btnModal" style="display: none;">
        		<i class="glyphicon glyphicon-search"></i>
        	</a>

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 600px;   
					}

					.datatable td span{
					    width: 80%;
					    display: block;
					    overflow: hidden;
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addPR" name='mdl_addPR' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
							            <div class="box-header">
							              	<ul class="nav nav-tabs">
							                    <li id="li1" <?php echo $Active1Cls; ?>>
							                    	<a href="#" data-toggle="tab"><?php echo $reqList; ?></a>
							                    </li>
							                </ul>
							            </div>
							            <div class="box-body">
	                                        <div class="form-group">
	                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
		                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                                                <thead>
		                                                    <tr>
		                                                        <th width="2%">&nbsp;</th>
																<th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
																<th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Date; ?></th>
																<th width="70%" nowrap style="vertical-align: middle; text-align:center"><?php echo $Description; ?></th>
																<th width="8%" nowrap style="text-align:center"><?php echo $ReceivePlan; ?></th>
		                                                  </tr>
		                                                </thead>
		                                                <tbody>
		                                                </tbody>
		                                            </table>
                                  					<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                                	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                	</button>
                                                	<button type="button" id="idClose" class="btn btn-danger" data-dismiss="modal">
                                                		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                	</button>
													<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
														<i class="glyphicon glyphicon-refresh"></i>
													</button>
	                                            </form>
	                                      	</div>
		                                </div>
		                            </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>
				<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
				<!-- Update [iyan] -->
				<div class="modal fade" id="mdl_addItmU" name='mdl_addItmU' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $Freight; ?></a>
						                    </li>	
						                    <li id="li2" style="display: none;">
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)"><?php echo $Substitute; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="40%" nowrap><?php echo $ItemName; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="10%" nowrap><?php echo $BudgetQty; ?>  </th>
			                                                        <th width="10%" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Ordered; ?> </th>
			                                                        <th width="10%" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh2" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>

							            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
			                                            <table id="example3" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
		                                                        <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="44%" nowrap><?php echo $ItemName; ?> </th>
			                                                        <th width="3%" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
			                                                        <th width="6%" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="6%" nowrap><?php echo $Ordered; ?> </th>
			                                                        <th width="9%" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose_itm" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh3" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck_itm" id="rowCheck_itm" value="0">
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>
				<!-- End Update [iyan] -->

				<script type="text/javascript">
					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					//Update [iyan]
					function pickThis2(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk2']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck_itm").val(favorite.length);
					}

					var selectedRows4 = 0;
					function pickThis4(thisobj) 
					{
						console.log('thisobj=>'+thisobj)
						var favorite = [];
						$.each($("input[name='chk4']:checked"), function(e) {
							favorite.push($(this).val());

							console.log('evt = '+e);

							var strItem = $(this).val();
							arrItem = strItem.split('|');		
							// alert($(this).val());
							selRow 		= arrItem[24];
							$('#PO_REQVOLMX'+selRow).prop('disabled', false);
							
							// $('#chkRow_'+selRow).bind('change', function(e){
							// 	console.log(e);
							// 	if(e.target.checked == true)
							// 	{
							// 		$('#PO_REQVOLMX'+selRow).prop('disabled', false);
							// 	}
							// 	else
							// 	{
							// 		$('#PO_REQVOLMX'+selRow).prop('disabled', true);
							// 	}
							// });
						});
						$("#rowCheck4").val(favorite.length);
					}

					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';
						}
						else
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';
						}
					}

					$(document).ready(function()
					{
				    	$('#example1').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_p180c21o/get_AllDataPREQ/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4], className: 'dt-body-center' },
											{ "width": "5px", "targets": [0] },
											{ "width": "20px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $selReq; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_header($(this).val());
						      	//console.log($(this).val());
						    });
						    return false;
						    $('#mdl_addPR').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

						$("#idRefresh1").click(function()
						{
							$('#example1').DataTable().ajax.reload();
						});

						//Update [iyan]
						$('#example2').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_p180c21o/get_AllDataITMU/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						// Update [iyan]
					    $("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck_itm").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert4; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk2']:checked"), function()
						    {
						      	add_itemU($(this).val());
						      	//console.log($(this).val());
						    });

						    $('#mdl_addItmU').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input[type=checkbox]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose_itm").click();
					    });
					    // End Update [iyan]
						
						$("#idRefresh2").click(function()
						{
							$('#example2').DataTable().ajax.reload();
						});

						$('#example3').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_p180c21o/get_AllDataITMS/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
						
						$("#idRefresh3").click(function()
						{
							$('#example3').DataTable().ajax.reload();
						});

						$('#example4').DataTable(
						{
							"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>"+
									"<'row'<'col-sm-12'tr>>",
							"ordering": false,
							"bDestroy": true,
							"processing": true,
							"serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
							"ajax": {
								"url": "<?php echo site_url('c_purchase/c_p180c21o/get_AllDataIITMREQDET/?id='.$PRJCODE.'&PO_NUM='.$PO_NUM.'&PO_CODE='.$PO_CODE.'&PR_NUM='.$PR_NUMX.'&task='.$task)?>",
								"type": "POST",
								"data": function(data) {
									data.ITM_SRC 	= $('#ITM_SRC').val();
									data.PRDESC_SRC = $('#PRDESC_SRC').val();
								},
								"beforeSend": function() {
									const ITM_SRC 		= $('#ITM_SRC').val();
									const PRDESC_SRC 	= $('#PRDESC_SRC').val();
									if(ITM_SRC != '' && PRDESC_SRC != '') 
									{
										$('#chgVolPO').prop('disabled', true);
										$('#chgVolPO').attr('placeholder', 'Watting...');
									}
								},
								"complete": function() {
									const ITM_SRC 		= $('#ITM_SRC').val();
									const PRDESC_SRC 	= $('#PRDESC_SRC').val();
									if(ITM_SRC != '' && PRDESC_SRC != '')
									{
										$('#chgVolPO').attr('placeholder', 'Total Volume');
										$('#chgVolPO').prop('disabled', false);
									}
								}
							},
							"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
							// "lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"pageLength": -1,
							"columnDefs": [	{ targets: [0,1], className: 'dt-body-center' },
											{ targets: [3,4,5,6,7], className: 'dt-body-right' },
											{ targets: [1], visible: false},
											{ targets: [4,5,6,7], 
												render: function(data, type, row, meta) {
													// var currentCell = $("#example4").DataTable().cells({"row":meta.row, "column":meta.col}).nodes(0);
													// data => value column
													// type => display
													// row => indexValue row[index]
													// meta = {'col','row','setting'}
													// return meta.row / meta.col / meta.setting; // index row
													return doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(data)), 2));
													// $.ajax({
													// 	url: "<?php // echo site_url('c_purchase/c_p180c21o/getColumnData');?>",
													// 	type: "POST",
													// 	data: {PRJCODE:<?php // echo $PRJCODE;?>, JOBCODEID:row[0]},
													// }).done(function (data) {
													// 	$(currentCell).text(data+' - '+meta.row);
													// });
													// return null;
												}
											}
										],
							"language": {
								"infoFiltered":"",
								"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
							},
							"drawCallback": function( settings ) {
								var api 	= this.api();
								var LnRow 	= api.rows( {page:'current'} ).data().length;
								$('#totalrow4').val(LnRow);
							},
							"footerCallback": function (row, data, start, end, display) {
								var api = this.api();
				
								// Remove the formatting to get integer data for summation
								var intVal = function (i) {
									return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
								};

								totalBUDQTY = api
									.column(4)
									.data()
									.reduce(function (a, b) {
										return intVal(a) + intVal(b);
									}, 0);

								var totalBUDQTYV = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totalBUDQTY)), 2));

								// Update footer
								$(api.column(4).footer()).html('<div style="font-weight: bold;">'+totalBUDQTYV+'</div>');

								totalTOTPRQTY = api
									.column(5)
									.data()
									.reduce(function (a, b) {
										return intVal(a) + intVal(b);
									}, 0);

								var totalTOTPRQTYV = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totalTOTPRQTY)), 2));

								// Update footer
								$(api.column(5).footer()).html('<div style="font-weight: bold;">'+totalTOTPRQTYV+'</div>');
					
								
								totalPR_VOLM = api
									.column(6)
									.data()
									.reduce(function (a, b) {
										return intVal(a) + intVal(b);
									}, 0);
								var totalPRVOLMV = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totalPR_VOLM)), 2));

								// Update footer
								$(api.column(6).footer()).html('<div style="font-weight: bold;">'+totalPRVOLMV+'</div>');

								totalREMORDERVOL = api
									.column(7)
									.data()
									.reduce(function (a, b) {
										return intVal(a) + intVal(b);
									}, 0);
								var totalREMORDERVOLV = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totalREMORDERVOL)), 2));
								$('#TOTRemVolPO').val(totalREMORDERVOL);
								// Update footer
								$(api.column(7).footer()).html('<div style="font-weight: bold;">'+totalREMORDERVOLV+'</div>');
							}
						});

						$('div.toolbar').html('<form id="form-filter" class="form-horizontal">'+
							  '<select class="form-control select2" name="ITM_SRC" id="ITM_SRC" data-placeholder="Cari Item" style="width: 150px;">'+
							  '<option value=""></option></select>&nbsp;'+
							  '<select class="form-control select2" name="PRDESC_SRC" id="PRDESC_SRC" data-placeholder="Cari Deskripsi" style="width: 150px;">'+
							  '<option value=""></option></select>&nbsp;'+
							  '<input type="text" class="form-control" id="chgVolPO" name="chgVolPO" value="" placeholder="Total Volume" onKeyPress="return isIntOnlyNew(event);" style="max-width: 120px" disabled>'+
							  '<input type="hidden" class="form-control" id="TOTRemVolPO" name="TOTRemVolPO" value="" placeholder="Total Sisa Vol. PO" style="max-width: 120px">'+
							  '</form>');

						// GET ITM_SRC
							var PRJCODE = "<?=$PRJCODE?>";
							var PR_NUM 	= "<?=$PR_NUMX?>";
							$.ajax({
								url: "<?php echo site_url('c_purchase/c_p180c21o/get_ITMSRC'); ?>",
								type: "POST",
								dataType: "JSON",
								data: {PRJCODE:PRJCODE, PR_NUM:PR_NUM},
								success: function(result) {
									var SRC_ITM = "<option value=''></option>";
									for(let i in result) {
										ITMCODE_SRC = result[i]['ITM_CODE'];
										ITMNAME_SRC = result[i]['ITM_NAME'];
										SRC_ITM += '<option value="'+ITMCODE_SRC+'">'+ITMNAME_SRC+'</option>';
									}
									$('#ITM_SRC').html(SRC_ITM);
								}
							});

							$('#ITM_SRC').bind('change', function(e){
								var PRJCODE = "<?=$PRJCODE?>";
								var PR_NUM 	= "<?=$PR_NUMX?>";
								var ITM_CODE = $(this).val();
								$.ajax({
									url: "<?php echo site_url('c_purchase/c_p180c21o/get_PRDESCSRC'); ?>",
									type: "POST",
									dataType: "JSON",
									data: {PRJCODE:PRJCODE, PR_NUM:PR_NUM, ITM_CODE:ITM_CODE},
									success: function(result) {
										var SRC_PRDESC = "<option value=''></option>";
										for(let i in result) {
											PRDESCID_SRC = result[i]['PR_DESC_ID'];
											PRDESC_SRC 	= result[i]['PR_DESC'];
											ITMCODE_SRC = result[i]['ITM_CODE'];
											SRC_PRDESC += '<option value="'+PRDESCID_SRC+'">'+PRDESC_SRC+'</option>';
										}
										$('#PRDESC_SRC').html(SRC_PRDESC);
										$('#example4').DataTable().ajax.reload();
									}
								});
							});

							$('#PRDESC_SRC').bind('change', function() {
								$('#example4').DataTable().ajax.reload();
								// $('#chgVolPO').prop('disabled', false);
							})

							// $('#btn-filter').bind('click', function(){
							// 	$('#example4').DataTable().ajax.reload();
							// });

							$('#btn-reset').click(function(){
								$('#ITM_SRC').val('').trigger('change');
								$('#PRDESC_SRC').val('').trigger('change');
								$('#example4').DataTable().ajax.reload();
							});

						// End ITM_SRC

						// if chk4 checked => PO_REQVOLMx, disabled => false
						// var chkITM = $("input[name='chk4']");
						// console.log(chkITM);
						$('#chgVolPO').bind('blur', function(){
							// alert(thisValue);
							const decFormat		= $('#decFormat').val();
							let jmlItmCUR 		= $('#totalrow4').val();
							let POVOL_REQVOL	= parseFloat($(this).val());
							let TOTREMPO_VOL 	= parseFloat($('#TOTRemVolPO').val());

							$(this).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POVOL_REQVOL)), decFormat)));
							// $(this).prop('disabled', true);
							// Clear Checkbox
							$('input[name="chk4"]').removeAttr('checked').trigger('change');

							if(POVOL_REQVOL <= TOTREMPO_VOL)
							{
								for(let i=1; i<=jmlItmCUR; i++) {
									// POVOL_REQVOL = POVOL_REQVOL - PO_REMVOLM;
									// console.log(POVOL_REQVOL+' = '+POVOL_REQVOL+' - '+PO_REMVOLM);
									let PO_REMVOLM 		= parseFloat(document.getElementById('PO_REMVOLM'+i).value);
									if(PO_REMVOLM > 0)
									{
										console.log('a1 = row'+i+' ='+POVOL_REQVOL+' > '+PO_REMVOLM);
										if(POVOL_REQVOL > PO_REMVOLM)
										{
											console.log('b1 = row'+i+' ='+POVOL_REQVOL+' > '+PO_REMVOLM);
											$('#chkRow_'+i).prop('checked', true).trigger('change');
											POVOL_REQVOL 		= POVOL_REQVOL - PO_REMVOLM;
										}
										else
										{
											$('#PO_REQVOLMX'+i).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POVOL_REQVOL)), decFormat)));
											$('#PO_REQVOLM'+i).val(POVOL_REQVOL);
											$('#chkRow_'+i).prop('checked', true).trigger('change');
											
											break;
										}
									}

									// if(PO_REMVOLM > 0)
									// {
									// 	if(POVOL_REQVOL < PO_REMVOLM)
									// 	{
									// 		console.log('a1 = row'+i+' ='+POVOL_REQVOL+' < '+PO_REMVOLM);
									// 	}
									// }
								}
							}
							else
							{
								swal("Tidak boleh melebihi total sisa PO", 
								{ 
									icon:"warning" 
								}).then(function() {
									$('#chgVolPO').val('');
									// $('#chgVolPO').prop('disabled', false);
								});
								return false;
							}
						});

						$("#btnDetail4").click(function()
						{
							var totChck4 	= $("#rowCheck4").val();
							if(totChck4 == 0)
							{
								swal('<?php echo $alert1; ?>',
								{
									icon: "warning",
								})
								.then(function()
								{
									swal.close();
								});
								return false;
							}

							$.each($("input[name='chk4']:checked"), function()
							{
								// console.log($(this).val());
								add_item($(this).val());
							});

							$('#mdl_addItemPR').on('hidden.bs.modal', function () {
								$(this)
									.find("input,textarea,select")
										//.val('')
										.end()
									.find("input[type=checkbox], input[type=radio]")
									.prop("checked", "")
									.end();
							});
							document.getElementById("idClose4").click()
						});
						
						$("#idRefresh4").click(function()
						{
							// $('#example4').DataTable().ajax.reload();
							$('#ITM_SRC').val('').trigger('change');
							$('#PRDESC_SRC').val('').trigger('change');
							$('#example4').DataTable().ajax.reload();
						});

						$('#chk4All').change(function(e) {
							const totRow4 = $('#totalrow4').val();
							if(e.target.checked == true)
							{
								for(let i = 1; i <= totRow4; i++) {
									$('#chkRow_'+i).prop('checked', true).trigger('change');
								}
							}
							else
							{
								$('input[name="chk4"]').removeAttr('checked').trigger('change');
							}
						});
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

	    	<!-- ============ START MODAL ITEM DESCRIPTION =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active3		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addCMN" name='mdl_addCMN' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setDesc(1)">Daftar Deskripsi Item</a>
						                    </li>	
						                    <li id="li2">
						                    	<a href="#itm2" data-toggle="tab" onClick="setDesc(2)">Tambah Deskripsi Baru</a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itmD1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example5" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th style="text-align: center; vertical-align: middle;">&nbsp;</th>
			                                                        <th style="text-align: center; vertical-align: middle;"  nowrap><?php echo $Description; ?></th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail5" name="btnDetail5">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose5" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh5" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>

							            	<div class="<?php echo $Active2; ?> tab-pane" id="itmD2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return checkFormDesc()">
														<div class="box-body">
											                <div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName" class="control-label"><?php echo $ItemName; ?></label>
											                    	<input type="hidden" class="form-control" style="text-align:left" id="ITM_CODE" name="ITM_CODE" value="" />
											                    	<input type="text" class="form-control" style="text-align:left" id="ITM_CODEX" name="ITM_CODEX" value="" readonly />
											                    </div>
											                </div>
											                <div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName" class="control-label"><?php echo $Description; ?></label>
											                    	<textarea class="form-control" name="DESC_NOTES"  id="DESC_NOTES"></textarea>
											                    </div>
											                </div>
											            </div>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail6" name="btnDetail6">
                                                    		<i class="glyphicon glyphicon-floppy-disk"></i>
                                                    	</button>
                                      					<button type="button" id="idClose6" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>
                                                    	</button>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
		                                </div>
                                      	<input type="hidden" name="rowCheck5" id="rowCheck5" value="0">
                                      	<button type="button" id="idClose5X" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setDesc(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itmD1').style.display	= '';
							document.getElementById('itmD2').style.display	= 'none';
						}
						else
						{
							document.getElementById('itmD1').style.display	= 'none';
							document.getElementById('itmD2').style.display	= '';
						}
					}

					$(document).ready(function()
					{
						let PR_NUM = $('#PR_NUMX').val();
						let PO_NUM = $('#PO_NUM').val();
						let PO_STAT = <?=$PO_STAT?>;
						let task 	= "<?=$task?>";
						// if(PR_NUM != '' && PO_STAT == 3)
						if(PR_NUM != '' && task != 'add')
						{
							// alert(PR_NUM+'-'+PO_STAT+'-'+PO_NUM);
							$('#tblITMDET').DataTable(
							{
								"processing": true,
								"serverSide": true,
								// "scrollX": true,
								"autoWidth": true,
								"filter": false,
								"info": false,
								"lengthChange": false,
								"paging": false,
								"ajax": "<?php echo site_url('c_purchase/c_p180c21o/get_AllDataIITMDET/?id='.$PRJCODE.'&PO_NUM='.$PO_NUM.'&PR_NUM='.$PR_NUMX.'&PO_STAT='.$PO_STAT)?>",
								//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
								// "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
								"columnDefs": [	{ targets: [0,7,9], className: 'dt-body-center' },
												{ targets: [3,4,5,6,8,10,11,12], className: 'dt-body-right' },
												{ targets: [1,6], visible: false },
											],
								"order": [[ 2, "asc" ]],
								"drawCallback": function( settings ) {
									var api 	= this.api();
									var LnRow 	= api.rows( {page:'current'} ).data().length;
									$('#totalrow').val(LnRow);
								},
								'fnCreatedRow': function (nRow, aData, iDataIndex) {
									let newRow = parseInt(iDataIndex + 1);
									$(nRow).attr('id', 'tr_' + newRow); // or whatever you choose to set as the id
									console.log(nRow);
								},
								"language": {
									"infoFiltered":"",
									"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />",
									// "sZeroRecords": "", 
									// "sEmptyTable": ""
								},
							});

							$('.select2').select2();
						}

						$("#idRefresh5").click(function()
						{
							$('#example5').DataTable().ajax.reload();
						});

					   	$("#btnDetail5").click(function()
					    {
							var totChck 	= $("#rowCheck5").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk5']:checked"), function()
						    {
						      	add_desc($(this).val());
						    });

						    $('#mdl_addCMN').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose5X").click()
					    });

					   	$("#btnDetail6").click(function()
					    {
					    	var ITMCODE 	= $("#ITM_CODE").val();
					    	var ITMDESC 	= $("#DESC_NOTES").val();
					    	console.log(ITMCODE);
					    	console.log(ITMDESC);

							collID 			= ITMCODE+'|'+ITMDESC;

							url 			= "<?=$AddDesc?>";
							$.ajax({
					            type: 'POST',
					            url: url,
					            data: {collID: collID},
					            success: function(response)
					            {
					            	$("#DESC_NOTES").val('');
					            	$('#example5').DataTable(
							    	{
							    		"destroy": true,
								        "processing": true,
								        "serverSide": true,
										//"scrollX": false,
										"autoWidth": true,
										"filter": true,
								        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataDESC/?ITMCODE=')?>"+ITMCODE,
								        "type": "POST",
										"lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
										//"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
										"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
														{ width: 5, targets: 0 }
													  ],
										"language": {
								            "infoFiltered":"",
								            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
								        },
									});
									document.getElementById('itmD1').style.display	= '';
									document.getElementById('itmD2').style.display	= 'none';
					            }
					        });
					    });
					});

					var selectedRows = 0;
					function pickThis5(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk5']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck5").val(favorite.length);
					}
				</script>
			<!-- ============ END MODAL ITEM DESCRIPTION =============== -->

			<!-- ============ START MODAL CANCEL ITEM =============== -->
			<div class="modal fade" id="mdl_delItm" name='mdl_delItm' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" >Pembatalan Volume OP Item : <i style='font-size: 14px;' id="itmName1"></i></a>
						                    </li>	
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
			                                    	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                        	<div class="col-md-12">
									                    	<div class="row">
										                    	<div class="col-md-3" style="white-space: nowrap;">
										                    		<?php echo "<strong>$ItemCode</strong>"; ?>
										                    	</div>
										                    	<div class="col-md-9" style="white-space: nowrap;">
										                    		<?php echo "<strong>$Description</strong>"; ?>
										                    	</div>
									                    	</div>
										                  	<div class="row">
										                    	<div class="col-md-3" style="white-space: nowrap;">
										                    		<i style='font-size: 14px;' id="itmCode"></i>
										                    	</div>
										                    	<div class="col-md-9" style="white-space: nowrap;">
										                    		<i style='font-size: 14px;' id="itmName"></i>
										                    	</div>
									                    	</div>
										                  	<div class="row">
										                    	<div class="col-md-3">&nbsp;</div>
										                    	<div class="col-md-9">
										                    		<i style='font-size: 14px;' id="jobName"></i>
										                    	</div>
									                    	</div>
										                  	<div class="row">
										                    	<div class="col-md-3">&nbsp;</div>
										                    	<div class="col-md-9">
										                    		<div class="row">
												                    	<div class="col-md-4" style="white-space: nowrap;">
												                    		<?php echo "<i><strong>OP</strong></i><br><i class='text-primary' style='font-size: 16px;' id='itmPOVol'><strong></strong></i>"; ?>
												                    	</div>
												                    	<div class="col-md-4" style="white-space: nowrap;">
												                    		<?php echo "<i><strong>LPM</strong></i><br><i class='text-yellow' style='font-size: 16px;' id='itmIRVol'><strong></strong></i>"; ?>
												                    	</div>
												                    	<div class="col-md-4" style="white-space: nowrap;">
												                    		<?php echo "<i><strong>$Remain:</strong></i><br><i class='text-yellow' style='font-size: 16px;' id='itmREMVol'><strong></strong></i>"; ?>
												                    	</div>
											                    	</div>
										                    	</div>
									                    	</div>
									                    	<br>
									                    	<div class="row">
										                    	<div class="col-md-6">
										                    		<?php echo "<strong>Dok. Referensi</strong>"; ?>
										                    	</div>
										                    	<div class="col-md-6">
										                    		<?php echo "<strong>Vol. $Cancel</strong> (<i><strong>$alert6</strong></i>)"; ?>
										                    	</div>
									                    	</div>
									                    	<div class="row">
										                    	<div class="col-md-6" style="white-space: nowrap;">
										                    		<input type="text" name="V_DOCREF" id="V_DOCREF" value="" class="form-control" placeholder="No. Dokumen Pembatalan">
										                    	</div>
										                    	<div class="col-md-6" style="white-space: nowrap;">
										                    		<input type="text" name="PO_CVOLX" id="PO_CVOLX" value="" class="form-control" style="min-width:80px; max-width:110px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgCncVol(this.value);" placeholder="Volume Batal" >
																	<input type="hidden" name="itmRow" id="itmRow" value="0">
																	<input type="hidden" name="PO_RVOL" id="PO_RVOL" value="0">
																	<input type="hidden" name="PO_CVOL" id="PO_CVOL" value="0">
										                    	</div>
									                    	</div>
									                    	<br>
										                  	<div class="row">
										                    	<div class="col-md-6">
																	<button type="button" class="btn btn-warning" onClick="proc_cnc()"><i class="fa fa-save"></i></button>
																	<button type="button" id="idCloseDRow" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i></button>
										                    	</div>
									                    	</div>
									                    </div>
			                                        </form>
			                                    </div>
			                                </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idCloseDRow" class="btn btn-default" data-dismiss="modal" style="display: none;">Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>
	    	<!-- ============ END MODAL CANCEL ITEM =============== -->

			<!-- ============ START MODAL ITEM LIST =============== -->
				<style type="text/css">
					.modal-dialog{
						position: relative;
						display: table; /* This is important */ 
						overflow-y: auto;    
						overflow-x: auto;
						width: auto;
						min-width: 300px;   
					}
				</style>
				<div class="modal fade" id="mdl_addItemPR" name='mdl_addItemPR' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<ul class="nav nav-tabs">
											<li id="li1" class="active">
												<a href="#itm1" data-toggle="tab"><?php echo $ItmList; ?></a>
											</li>
										</ul>
										<div class="box-body">
											<div class="active tab-pane" id="itm1">
												<div class="form-group">
													<form method="post" name="frmSearch1" id="frmSearch1" action="">
														<div class="search-table-outter">
															<table id="example4" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
																<thead>
																	<tr>
																		<th width="2%" height="25" style="text-align: center;"><input type="checkbox" name="chk4All" id="chk4All" value="AllITM_PR"></th>
																		<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
																		<th width="27%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
																		<th width="3%" style="text-align:center; vertical-align: middle;">Sat.</th>
																		<th width="10%" style="text-align:center; vertical-align: middle;">Vol.<br><?php echo $Planning ?> </th>
																		<th width="10%" style="text-align:center; vertical-align: middle;">Vol.<br><?php echo $Requested ?></th>
																		<th width="10%" style="text-align:center; vertical-align: middle;">Vol.<br>PO</th>
																		<th width="10%" style="text-align:center; vertical-align: middle;">Vol.Sisa PO</th>
																		<th width="10%" style="text-align:center; vertical-align: middle;">Jumlah <br>Vol. PO</th>
																	</tr>
																</thead>
																<tbody>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="4" style="font-weight: bold; text-align: right;">Total</td>
																		<td style="text-align: right;"></td>
																		<td style="text-align: right;"></td>
																		<td style="text-align: right;"></td>
																		<td style="text-align: right;"></td>
																		<td style="text-align: right;"></td>
																	</tr>
																</tfoot>
															</table>
														</div>
														<br>
														<button class="btn btn-primary" type="button" id="btnDetail4" name="btnDetail4">
															<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
														</button>
														<button type="button" id="idClose4" class="btn btn-danger" data-dismiss="modal">
															<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
														</button>
														<button class="btn btn-warning" type="button" id="idRefresh4" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
													</form>
												</div>
											</div>
										</div>
										<input type="hidden" name="totalrow4" id="totalrow4" value="0">
										<input type="hidden" name="rowCheck4" id="rowCheck4" value="0">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					
				</script>
	    	<!-- ============ END MODAL ITEM LIST =============== -->

        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
<?php
	$secGTax 	= base_url().'index.php/__l1y/getTaxP/?id=';
	$tblTax 	= "tbl_tax_ppn";
?>
<script>
	<?php
		// START : GENERATE MANUAL CODE
			if($task == 'add')
			{
				?>
					$(document).ready(function()
					{
						setInterval(function(){addUCODE()}, 1000);
					});
				<?php
			}
		// END : GENERATE MANUAL CODE
	?>

	function addUCODE()
	{
		var task 		= "<?=$task?>";
		var DOCNUM		= document.getElementById('PO_NUM').value;
		var DOCCODE		= document.getElementById('PO_CODE').value;
		var DOCDATE		= document.getElementById('datepicker').value;
		var ACC_ID		= "";
		var PDManNo 	= "";

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: PDManNo,
							DOCDATE 		: DOCDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: 'PR'
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	//console.log(response)
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	if(task == 'add')
            	{
	            	$('#PO_NUM').val(docNum);
	            }

            	$('#PO_CODE').val(docCode);
            	$('#PO_CODEX').val(docCode);
            	$('#PO_CODEY').val(docCode);
            }
        });
	}

	// Catch the keydown for the entire document
	$(document).keydown(function(e)
	{
		// Set self as the current item in focus
		var self = $(':focus'),
		// Set the form by the current item in focus
		form = self.parents('form:eq(0)'),
		focusable;

		// Array of Indexable/Tab-able items
		focusable = form.find('input,a,select,button,textarea,div[contenteditable=true]').filter(':visible');

		function enterKey()
		{
			if (e.which === 13 && !self.is('textarea,div[contenteditable=true]')) { // [Enter] key
				// If not a regular hyperlink/button/textarea
				if ($.inArray(self, focusable) && (!self.is('a,button'))){
				// Then prevent the default [Enter] key behaviour from submitting the form
					e.preventDefault();
				} // Otherwise follow the link/button as by design, or put new line in textarea

				// Focus on the next item (either previous or next depending on shift)
				focusable.eq(focusable.index(self) + (e.shiftKey ? -1 : 1)).focus();

				return false;
			}
		}
		// We need to capture the [Shift] key and check the [Enter] key either way.
		if (e.shiftKey) { enterKey() } else { enterKey() }
	});

  	$(function ()
  	{
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
	      autoclose: true,
		  startDate: '-0d',
		  endDate: '+0d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
		});

	    //Date picker
	    $('#datepicker2').datepicker({
			autoclose: true,
			startDate: '+0d'
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

		$("#PO_TAXCODE1").bind("select2:select", function(e){
			var data = e.params.data;
			var totalrow = $("#totalrow").val();
			for(let i=1; i<=totalrow; i++) {
				console.log(i+'='+$(this).val());
				$("#data"+i+"TAXCODE1").val(data.id).select2();
				getValuePO(i);
			}
		});

		$("#userfile").bind("change", (e) => {
			let files 				= e.target.files;
			console.log(files);
			// const validExtensions 	= ["image/jpeg", "image/jpg", "image/png", "application/pdf"]; 
			const validExtensions 	= ["application/pdf"]; 
			for(let i = 0; i < files.length; i++) {
				let fileType 	= e.target.files[i].type; //getting selected file type
				if(validExtensions.includes(fileType) == true) {
					console.log(fileType);
				} else {
					swal("<?=$alert7?>");
					$(this).val('');
				}
			}
		});

		$('#PR_NUM1').tooltip('show');
	});
  
	var decFormat		= 2;

	/* ----------------------------------------- Before Update -------------------------------------------------
	function getValuePO(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		//tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		//ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);
		//PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);
		
		//var PO_VOLM 		= eval(thisVal).value.split(",").join("");
		PO_VOLM1			= document.getElementById('PO_VOLMX'+row);
		PO_VOLM 			= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
		
		ITM_REMAIN			= parseFloat(document.getElementById('ITM_REMAIN'+row).value);
		if(PO_VOLM > ITM_REMAIN)
		{
			swal('<?php //echo $OrdMTReq; ?>',
			{
				icon: "warning",
			});
			document.getElementById('PO_VOLMX'+row).focus();
			document.getElementById('PO_VOLMX'+row).value = ITM_REMAIN;
			return false;
		}
		
		var PO_VOLM 		= parseFloat(document.getElementById('PO_VOLMX'+row).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_VOLM').value 	= parseFloat(Math.abs(PO_VOLM));
		document.getElementById('PO_VOLMX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)),decFormat));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEX'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
		document.getElementById('PO_PRICEX'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
		
		// PO DISCOUNT
		thisValDISC			= document.getElementById('PO_DISC'+row);
		PO_DISC				= parseFloat(eval(thisValDISC).value.split(",").join(""));
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat((PO_VOLM * PO_PRICE) - PO_DISC);
		
		// PO TAX		
		var TAXCODE1		= document.getElementById('data'+row+'TAXCODE1').value;
		var taxPerc			= 0;
		if(TAXCODE1 == 0 || TAXCODE1 == '')
		{
        	taxPerc 	= parseFloat(0 / 100);
        	TAX1VAL		= parseFloat(ITMPRICE_TEMP * taxPerc);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			PO_COSTnPPn	= parseFloat(ITMPRICE_TEMP + TAX1VAL);

			document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
			document.getElementById('PO_COSTnPPn'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));
		}
		else
		{
			var url 		= "<?php echo $secGTax; ?>";
			var collID		= "<?php echo $tblTax; ?>~"+TAXCODE1;
			$.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(taxPerc2)
	            {
	            	var myarr 		= taxPerc2.split("~");
	            	var taxType 	= myarr[0];
	            	var taxPerc1 	= myarr[1];

	            	taxPerc 		= parseFloat(taxPerc1 / 100);
	            	TAX1VAL			= parseFloat(ITMPRICE_TEMP * taxPerc);
					document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));

					if(taxType == 1)		// 1 = PPn, 2 = PPh
						PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
					else
						PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP - TAX1VAL);

					document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
					document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));
	            }
	        });
		}


		/*if(TAXCODE1 == 'TAX01')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.1);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			//PO_COST		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
			PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
		}
		else if(TAXCODE1 == 'TAX02')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.03);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			//PO_COST		= parseFloat(ITMPRICE_TEMP - TAX1VAL);
			PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP - TAX1VAL);
		}
		else
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			//PO_COST		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
			PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP);
		}
		document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
		document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));*//*
	}
	 ----------------------------------------- End Before Update ------------------------------------------------------------- */
	
	/* --------------------------------- Update [iyan][211115] ----------------------- */
	function getValuePO_221007(thisVal, row)
	{
		console.log(row);
		var decFormat		= document.getElementById('decFormat').value;
		
		let totalrow	= document.getElementById('totalrow').value;
		
		//tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		//ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);
		//PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);
		
		//var PO_VOLM 		= eval(thisVal).value.split(",").join("");
		PO_VOLMBF			= document.getElementById('data'+row+'PO_VOLM').value;

		PO_VOLM1			= document.getElementById('PO_VOLMX'+row);
		PO_VOLM 			= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
		
		ITM_CATEG 			= document.getElementById('ITM_CATEG'+row).value;

		if(ITM_CATEG != 'UA')
		{
			ITM_REMAIN		= parseFloat(document.getElementById('ITM_REMAIN'+row).value);
			ITM_MAXREQ_AMN	= parseFloat(document.getElementById('ITM_MAXREQ_AMN'+row).value);
			ORIG_PRC 		= document.getElementById('data'+row+'PO_PRICE').value;
			JOBCODEID1 		= document.getElementById('data'+row+'JOBCODEID').value;

			getPrc			= document.getElementById('PO_PRICEX'+row);
			POPRICE			= parseFloat(eval(getPrc).value.split(",").join(""));
			PO_NOW_AMN 		= parseFloat(PO_VOLM) * parseFloat(POPRICE);

			ITM_REMVOLV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAIN)), 2));
			ITM_REMAMNV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_MAXREQ_AMN)), 2));

			console.log('A = '+PO_VOLM+'>'+ITM_REMAIN)
			if(PO_VOLM > ITM_REMAIN)
			{
				swal('<?php echo $OrdMTReq; ?> ('+ITM_REMVOLV+')',
				{
					icon: "warning",
				});
				document.getElementById('PO_VOLMX'+row).focus();
				document.getElementById('PO_VOLMX'+row).value = ITM_REMAIN;
				return false;
			}

			console.log('B = '+PO_NOW_AMN+'>'+ITM_MAXREQ_AMN)
			if(PO_NOW_AMN > ITM_MAXREQ_AMN)
			{
				swal('<?php echo $OrdMTReq; ?> ('+ITM_REMAMNV+')',
				{
					icon: "warning",
				});
				document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(ORIG_PRC));
				document.getElementById('PO_PRICEX'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ORIG_PRC)), 2));
				return false;
			}

			// NEW REMAIN
				NEW_MAXREQ_AMN 	= parseFloat(ITM_MAXREQ_AMN - PO_NOW_AMN);

			console.log('C = '+NEW_MAXREQ_AMN)
			// START : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE
				totRow		= document.getElementById('totalrow').value;
				for(i=1;i<=totRow;i++)
				{
					if(i > row)
					{
						let myObj 	= document.getElementById('data'+i+'JOBCODEID');
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
						
						if(theObj != null)
						{
							var JOBCODEID2 	= document.getElementById('data'+i+'JOBCODEID').value;
							//console.log('JOBCODEID1 = '+JOBCODEID1+' vs '+JOBCODEID2)
							if(JOBCODEID1 == JOBCODEID2)
							{
								document.getElementById('ITM_MAXREQ_AMN'+i).value 	= NEW_MAXREQ_AMN;
								document.getElementById('remValue_'+i).innerHTML	= "Sisa : "+doDecimalFormat(RoundNDecimal(parseFloat(NEW_MAXREQ_AMN), 2));
								
								POVOLM2			= parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
								POPRICE2		= parseFloat(document.getElementById('data'+i+'PO_PRICE').value);
								PO_NOW_AMN2 	= parseFloat(POVOLM2) * parseFloat(POPRICE2);
								ITM_MAXREQ_AMN2	= parseFloat(document.getElementById('ITM_MAXREQ_AMN'+i).value);

								console.log(i+' = '+PO_NOW_AMN2+'>'+ITM_MAXREQ_AMN2)
								if(PO_NOW_AMN2 > ITM_MAXREQ_AMN2)
								{
									console.log(i+' == '+PO_NOW_AMN2+' > '+ITM_MAXREQ_AMN2);
									document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(0));
									document.getElementById('PO_PRICEX'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
								}
								else
								{
									NEW_MAXREQ_AMN 	= parseFloat(NEW_MAXREQ_AMN - PO_NOW_AMN2);
								}
							}
						}
					}
				}
			// END : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE
		}
		
		var PO_VOLM 		= parseFloat(document.getElementById('PO_VOLMX'+row).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_VOLM').value 	= parseFloat(Math.abs(PO_VOLM));
		document.getElementById('PO_VOLMX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)),decFormat));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEX'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
		document.getElementById('PO_PRICEX'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
		
		// PO DISCOUNT
		thisValDISC			= document.getElementById('PO_DISC'+row);
		PO_DISC				= parseFloat(eval(thisValDISC).value.split(",").join(""));
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat((PO_VOLM * PO_PRICE) - PO_DISC);
		
		// PO TAX		
		var TAXCODE1		= document.getElementById('data'+row+'TAXCODE1').value;
		var taxPerc			= 0;
		if(TAXCODE1 == 0 || TAXCODE1 == '')
		{
        	taxPerc 	= parseFloat(0 / 100);
        	TAX1VAL		= parseFloat(ITMPRICE_TEMP * taxPerc);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			PO_COSTnPPn	= parseFloat(ITMPRICE_TEMP + TAX1VAL);

			document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
			document.getElementById('PO_COSTnPPn'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));

		}
		else
		{
			var url 		= "<?php echo $secGTax; ?>";
			var collID		= "<?php echo $tblTax; ?>~"+TAXCODE1;
			$.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
				async: false,
	            success: function(taxPerc2)
	            {
	            	var myarr 		= taxPerc2.split("~");
	            	var taxType 	= myarr[0];
	            	var taxPerc1 	= myarr[1];

	            	taxPerc 		= parseFloat(taxPerc1 / 100);
	            	TAX1VAL			= parseFloat(ITMPRICE_TEMP * taxPerc);
					document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));

					if(taxType == 1)		// 1 = PPn, 2 = PPh
						PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
					else
						PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP - TAX1VAL);

					document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
					document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));
	            }
	        });
		}

		// GET ITMVOL_R
			let task 		= "<?=$task?>";
			let PRJCODE 	= document.getElementById('data'+row+'PRJCODE').value;
			let PR_NUM 		= document.getElementById('data'+row+'PR_NUM').value;
			let PO_NUM 		= document.getElementById('data'+row+'PO_NUM').value;
			let ITM_CODE	= document.getElementById('data'+row+'ITM_CODE').value;
			let PO_DESC_ID	= document.getElementById('data'+row+'PO_DESC_ID').value;
			// let PO_VOLM		= document.getElementById('data'+row+'PO_VOLM').value;
			// alert(totalrow);
			
			// PO_VOLMBF 			= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
			// alert(PO_VOLMBF);
			$.ajax({
				type: 'POST',
	            url: '<?php echo site_url('c_purchase/c_p180c21o/getITMVOL_R'); ?>',
				dataType: 'JSON',
	            data: {task: task, PRJCODE:PRJCODE, PR_NUM:PR_NUM, PO_NUM:PO_NUM, ITM_CODE:ITM_CODE, PO_DESC_ID:PO_DESC_ID, PO_VOLMBF:PO_VOLMBF, PO_VOLM:PO_VOLM},
				success: function(result) {
					console.log(result);
					for(let i = 1; i <= totalrow; i++) {
						let ITM_CODEx 	= document.getElementById('data'+i+'ITM_CODE').value;
						let PO_DESC_IDx	= document.getElementById('data'+i+'PO_DESC_ID').value;
						
						if(ITM_CODEx == ITM_CODE && PO_DESC_IDx == PO_DESC_ID)
						{
							// alert(ITM_CODEx+'=='+ITM_CODE+'&&'+PO_DESC_IDx+'=='+PO_DESC_ID);
							$('#ITMVOL_R'+result['PO_DESC_ID']+i).html(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(result['ITMVOLM_R'])),decFormat)));
							
							PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
							document.getElementById('data'+i+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
							document.getElementById('PO_PRICEX'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
						}
					}
					// let ITMVOL_R = $('#ITMVOL_R'+row);
					// console.log(ITMVOL_R.length);
					// let ITMVOL_R = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(result)),decFormat));
					// ITMVOL_R.html(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(result)),decFormat)));
				}
			});

		getTOTOP();
		getDPer();

		/*if(TAXCODE1 == 'TAX01')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.1);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			//PO_COST		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
			PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
		}
		else if(TAXCODE1 == 'TAX02')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.03);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			//PO_COST		= parseFloat(ITMPRICE_TEMP - TAX1VAL);
			PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP - TAX1VAL);
		}
		else
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			//PO_COST		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
			PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP);
		}
		document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
		document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));*/
	}
	/* --------------------------------- End Update [iyan][211115] ----------------------- */

	function getValuePO_221201(row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		let totalrow		= document.getElementById('totalrow').value;
		
		PO_VOLMBF			= document.getElementById('data'+row+'PO_VOLM').value;
		
		PO_VOLM1			= document.getElementById('PO_VOLMX'+row);
		PO_VOL 				= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
		
		ITM_CATEG 			= document.getElementById('ITM_CATEG'+row).value;

		if(ITM_CATEG != 'UA')
		{
			REM_VOL			= parseFloat(document.getElementById('ITM_REMAIN'+row).value);
			REM_VAL			= parseFloat(document.getElementById('ITM_MAXREQ_AMN'+row).value);
			ORIG_PRC 		= document.getElementById('data'+row+'PO_PRICE').value;
			JOBCODEID1 		= document.getElementById('data'+row+'JOBCODEID').value;
			PRD_ID1 		= document.getElementById('data'+row+'PRD_ID').value;

			getPrc			= document.getElementById('PO_PRICEX'+row);
			POPRICE			= parseFloat(eval(getPrc).value.split(",").join(""));
			PO_VAL 			= parseFloat(PO_VOL) * parseFloat(POPRICE);

			REM_VOLV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_VOL)), 2));
			REM_VALV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_VAL)), 2));

			if(PO_VOL > REM_VOL)
			{
				swal('<?php echo $OrdMTReq; ?> ('+REM_VOLV+')',
				{
					icon: "warning",
				});
				document.getElementById('PO_VOLMX'+row).focus();
				document.getElementById('PO_VOLMX'+row).value 			= REM_VOL;
				document.getElementById('data'+row+'PO_VOLM').value 	= REM_VOL;

				PO_VOL 		= parseFloat(REM_VOL);
			}

			document.getElementById('data'+row+'PO_VOLM').value 	= parseFloat(Math.abs(PO_VOL));
			document.getElementById('PO_VOLMX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOL)), 2));

			// START : RECHECK
				// 0. HITUNG TOTAL VAL ITEM FROM OTHER DOC
				// 1. HITUNG TOTAL VOL PO PER ITEM SPP
				// 2. HITUNG TOTAL VAL PO PER ITEM SPP
				// 3. HITUNG TOTAL VAL PO PER ITEM RAP
		
				ITM_BUDGVAL		= parseFloat(document.getElementById('ITM_BUDGVAL'+row).value);
				TOTVAL_OTHDOC	= parseFloat(document.getElementById('TOTVAL_OTHDOC'+row).value);
				TOTPO_VAL1 		= 0;
				TOTPO_VAL2 		= 0;
				totRow			= document.getElementById('totalrow').value;
				for(i=1;i<=totRow;i++)
				{
					if(i != row)
					{
						let myObj 	= document.getElementById('data'+i+'JOBCODEID');
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
						
						if(theObj != null)
						{
							var JOBCODEID2 	= document.getElementById('data'+i+'JOBCODEID').value;
							var ITM_CODE2 	= document.getElementById('data'+i+'ITM_CODE').value;
							var PRD_ID2 	= document.getElementById('data'+i+'PRD_ID').value;

							
							if(JOBCODEID1 == JOBCODEID2)
							{
								PO_VOL2		= parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
								POPRICE2	= parseFloat(document.getElementById('data'+i+'PO_PRICE').value);
								PO_VAL2 	= parseFloat(PO_VOL2) * parseFloat(POPRICE2);
								TOTPO_VAL1 	= parseFloat(TOTPO_VAL1) + parseFloat(PO_VAL2);

								if(PRD_ID1 == PRD_ID2)
								{
									TOTPO_VAL2 	= parseFloat(TOTPO_VAL2) + parseFloat(PO_VAL2);
								}
							}
						}
					}
				}

				TOTPO_VAL2 	= parseFloat(TOTVAL_OTHDOC) + parseFloat(TOTPO_VAL1);

				// JANGAN MENGAMBIL SISA VALUE DARI SPP, MELAINKAN DARI SISA VALUE BUDGET KECUALI VOLUME (BETUL DARI SPP)
				REM_RAPVAL 	= parseFloat(REM_VAL) - parseFloat(TOTPO_VAL2);
				//REM_RAPVAL 	= parseFloat(ITM_BUDGVAL) - parseFloat(TOTPO_VAL2);
				console.log(REM_RAPVAL 	+'='+ parseFloat(REM_VAL) +'-'+ parseFloat(TOTPO_VAL2));
			// END : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE

			if(PO_VAL > REM_RAPVAL)
			{
				swal('<?php echo $OrdMTReq; ?> ('+REM_VALV+')',
				{
					icon: "warning",
				})
				.then(function()
				{
					if(PO_VOL == 0)
						PO_PRICE2 	= 0;
					else
						PO_PRICE2 	= parseFloat(REM_RAPVAL / PO_VOL);

					document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE2));
					document.getElementById('PO_PRICEX'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE2)), 2));

					PO_PRICE 	= parseFloat(PO_PRICE2);
				});
			}
			else
			{
				document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(POPRICE));
				document.getElementById('PO_PRICEX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POPRICE)), 2));

				PO_PRICE 		= parseFloat(POPRICE);
			}
			console.log('b'+row)

			// START : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE
				totRow		= document.getElementById('totalrow').value;
				for(i=1;i<=totRow;i++)
				{
					/*if(i != row)
					{*/
						let myObj 	= document.getElementById('data'+i+'JOBCODEID');
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
						
						if(theObj != null)
						{
							var JOBCODEID2 	= document.getElementById('data'+i+'JOBCODEID').value;

							if(JOBCODEID1 == JOBCODEID2)
							{
								PO_VOL2		= parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
								POPRICE2	= parseFloat(document.getElementById('data'+i+'PO_PRICE').value);
								PO_VAL2 	= parseFloat(PO_VOL2) * parseFloat(POPRICE2);
								REM_RAPVAL2	= parseFloat(REM_RAPVAL) - parseFloat(PO_VAL2);

								document.getElementById('ITM_MAXREQ_AMN'+i).value 	= REM_RAPVAL2;
								document.getElementById('remValue_'+i).innerHTML	= "S : "+doDecimalFormat(RoundNDecimal(parseFloat(REM_RAPVAL2), 2));
							}
						}
					//}
				}
			// END : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE
		
			// PO DISCOUNT
				thisValDISC		= document.getElementById('PO_DISC'+row);
				PO_DISC			= parseFloat(eval(thisValDISC).value.split(",").join(""));
				document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		
			// PO TEMP
				ITMPRICE_TEMP	= parseFloat((PO_VOL * PO_PRICE) - PO_DISC);
		
			// PO TAX		
				var TAXCODE1	= document.getElementById('data'+row+'TAXCODE1').value;
				var taxPerc		= 0;
				if(TAXCODE1 == 0 || TAXCODE1 == '')
				{
					document.getElementById('data'+row+'TAXPRICE1').value 	= 0;
					PO_COSTnPPn	= parseFloat(ITMPRICE_TEMP);

					document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
					document.getElementById('PO_COSTnPPn'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)), 2));
				}
				else
				{
					var url 		= "<?php echo $secGTax; ?>";
					var collID		= "<?php echo $tblTax; ?>~"+TAXCODE1;
					$.ajax({
			            type: 'POST',
			            url: url,
			            data: {collID: collID},
						async: false,
			            success: function(taxPerc2)
			            {
			            	var myarr 		= taxPerc2.split("~");
			            	var taxType 	= myarr[0];
			            	var taxPerc1 	= myarr[1];

			            	taxPerc 		= parseFloat(taxPerc1 / 100);
			            	TAX1VAL			= parseFloat(ITMPRICE_TEMP * taxPerc);
							document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));

							if(taxType == 1)		// 1 = PPn, 2 = PPh
								PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
							else
								PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP - TAX1VAL);

							document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
							document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)), 2));
			            }
			        });
				}
				console.log('c'+row)
			// GET ITMVOL_R
				let task 		= "<?=$task?>";
				let PRJCODE 	= document.getElementById('data'+row+'PRJCODE').value;
				let PR_NUM 		= document.getElementById('data'+row+'PR_NUM').value;
				let PO_NUM 		= document.getElementById('data'+row+'PO_NUM').value;
				let ITM_CODE	= document.getElementById('data'+row+'ITM_CODE').value;
				let PO_DESC_ID	= document.getElementById('data'+row+'PO_DESC_ID').value;

				console.log('d'+row)
				console.log('task: '+task+' , PRJCODE: '+PRJCODE+' , PR_NUM: '+PR_NUM+', PO_NUM: '+PO_NUM+', ITM_CODE: '+ITM_CODE+', PO_DESC_ID: '+PO_DESC_ID+', PO_VOLMBF: '+PO_VOLMBF+', PO_VOL: '+PO_VOL)
				$.ajax({
					type: 'POST',
		            url: '<?php echo site_url('c_purchase/c_p180c21o/getITMVOL_R'); ?>',
					dataType: 'JSON',
		            data: {task: task, PRJCODE:PRJCODE, PR_NUM:PR_NUM, PO_NUM:PO_NUM, ITM_CODE:ITM_CODE, PO_DESC_ID:PO_DESC_ID, PO_VOLMBF:PO_VOLMBF, PO_VOL:PO_VOL},
					success: function(result) {
						console.log(result);
						for(let i = 1; i <= totalrow; i++) {
							let ITM_CODEx 	= document.getElementById('data'+i+'ITM_CODE').value;
							let PO_DESC_IDx	= document.getElementById('data'+i+'PO_DESC_ID').value;
							
							if(ITM_CODEx == ITM_CODE && PO_DESC_IDx == PO_DESC_ID)
							{
								$('#ITMVOL_R'+result['PO_DESC_ID']+i).html(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(result['ITMVOLM_R'])),decFormat)));
								
								/* ------------------------ HOLD ------------------------------------------
								PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
								document.getElementById('data'+i+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
								document.getElementById('PO_PRICEX'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
								-------------------- END HOLD ----------------------------- */
							}
						}
					}
				});

			getTOTOP();
			getDPer();
		}
		
	}

	function getValuePO(row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		let totalrow		= document.getElementById('totalrow').value;
		PO_VOLMBF			= document.getElementById('data'+row+'PO_VOLM').value;
		PO_VOLM1			= document.getElementById('PO_VOLMX'+row);
		PO_VOL 				= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
		ITM_CATEG 			= document.getElementById('ITM_CATEG'+row).value;

		if(ITM_CATEG != 'UA')
		{
			REM_VOL			= parseFloat(document.getElementById('ITM_REMAIN'+row).value);
			REM_VAL			= parseFloat(document.getElementById('ITM_MAXREQ_AMN'+row).value);
			REM_VAL_JOB		= parseFloat(document.getElementById('JOB_MAXREQ_AMN'+row).value);
			ORIG_PRC 		= document.getElementById('data'+row+'PO_PRICE').value;
			JOBCODEID1 		= document.getElementById('data'+row+'JOBCODEID').value;
			PO_DESCID1 		= document.getElementById('data'+row+'PO_DESC_ID').value;
			PRD_ID1 		= document.getElementById('data'+row+'PRD_ID').value;

			getPrc			= document.getElementById('PO_PRICEX'+row);
			POPRICE			= parseFloat(eval(getPrc).value.split(",").join(""));
			PO_VAL0			= parseFloat(PO_VOL) * parseFloat(POPRICE);
			PODISC1			= parseFloat(document.getElementById('data'+row+'PO_DISC').value);
			PO_VAL			= parseFloat(PO_VAL0) - parseFloat(PODISC1);

			REM_VOLV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_VOL)), 2));
			REM_VALV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_VAL)), 2));

			if(PO_VOL > REM_VOL)
			{
				swal('<?php echo $OrdMTReq; ?> ('+REM_VOLV+')',
				{
					icon: "warning",
				});
				document.getElementById('PO_VOLMX'+row).focus();
				document.getElementById('PO_VOLMX'+row).value 			= REM_VOL;
				document.getElementById('data'+row+'PO_VOLM').value 	= REM_VOL;

				PO_VOL 		= parseFloat(REM_VOL);
			}

			document.getElementById('data'+row+'PO_VOLM').value 	= parseFloat(Math.abs(PO_VOL));
			document.getElementById('PO_VOLMX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOL)), 2));

			// START : RECHECK
				// 0. HITUNG TOTAL VAL ITEM FROM OTHER DOC
				// 1. HITUNG TOTAL VOL PO PER ITEM SPP
				// 2. HITUNG TOTAL VAL PO PER ITEM SPP
				// 3. HITUNG TOTAL VAL PO PER ITEM RAP
		
				ITM_BUDGVAL		= parseFloat(document.getElementById('ITM_BUDGVAL'+row).value);
				TOTVAL_OTHDOC	= parseFloat(document.getElementById('TOTVAL_OTHDOC'+row).value);
				TOTPO_VAL1 		= 0;
				TOTPO_VAL2 		= 0;
				totRow			= document.getElementById('totalrow').value;
				for(i=0;i<=totRow;i++)
				{
					if(i != row)
					{
						let myObj 	= document.getElementById('data'+i+'JOBCODEID');
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
						
						if(theObj != null)
						{
							var JOBCODEID2 	= document.getElementById('data'+i+'JOBCODEID').value;
							var PO_DESCID2 	= document.getElementById('data'+i+'PO_DESC_ID').value;
							var ITM_CODE2 	= document.getElementById('data'+i+'ITM_CODE').value;
							var PRD_ID2 	= document.getElementById('data'+i+'PRD_ID').value;
							
							// SAAT PENGHITUNGAN TOTAL NILAI YANG DIPESAN, TIDAK MEMPERHATIKAN PRID ATAU POID, KECUALI UNTUK PEMBATASAN VOLUME.
							// KARENA PEMBATASAN TOTAL NILAO SECARA LANGSUNG MEMBANDINGKAN KODE PEKERJAAN TERHADAP BUDGET 

							if(JOBCODEID1 == JOBCODEID2)
							{
								PO_VOL2		= parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
								POPRICE2	= parseFloat(document.getElementById('data'+i+'PO_PRICE').value);
								PODISC2		= parseFloat(document.getElementById('data'+i+'PO_DISC').value);
								PO_VAL2 	= parseFloat(PO_VOL2) * parseFloat(POPRICE2);
								TOTPO_VAL1 	= parseFloat(TOTPO_VAL1) + parseFloat(PO_VAL2) - parseFloat(PODISC2);
								/*if(PRD_ID1 == PRD_ID2)
								{
									TOTPO_VAL2 	= parseFloat(TOTPO_VAL2) + parseFloat(PO_VAL2);
								}*/
							}
						}
					}

					//document.getElementById('ITM_MAXREQ_AMN'+i).value 	= REM_RAPVAL2;
					//document.getElementById('remValue_'+i).innerHTML	= "S : "+doDecimalFormat(RoundNDecimal(parseFloat(TOTPO_VAL3), 2));
				}

				// TOTAL NILAI YANG DIPESAN DARI KESELURUHAN PO (CURRENT ROW AND OTHER ROW)
				TOTPO_VAL2 	= parseFloat(TOTVAL_OTHDOC) + parseFloat(TOTPO_VAL1);
				console.log('1. '+TOTPO_VAL2 	+'='+ parseFloat(TOTVAL_OTHDOC) +'+'+ parseFloat(TOTPO_VAL1));

				REM_RAPVAL 	= parseFloat(REM_VAL_JOB) - parseFloat(TOTPO_VAL2);
				//REM_RAPVAL 	= parseFloat(ITM_BUDGVAL) - parseFloat(TOTPO_VAL2);
				console.log('2. '+REM_RAPVAL 	+'='+ parseFloat(REM_VAL_JOB) +'-'+ parseFloat(TOTPO_VAL2));
			// END : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE

			if(PO_VAL > REM_RAPVAL)
			{
				swal('<?php echo $OrdMTReq; ?> ('+REM_VALV+')',
				{
					icon: "warning",
				})
				.then(function()
				{
					if(PO_VOL == 0)
						PO_PRICE2 	= 0;
					else
						PO_PRICE2 	= parseFloat(REM_RAPVAL / PO_VOL);

					document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE2));
					document.getElementById('PO_PRICEX'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE2)), 2));

					PO_PRICE 	= parseFloat(PO_PRICE2);
				});
			}
			else
			{
				document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(POPRICE));
				document.getElementById('PO_PRICEX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POPRICE)), 2));

				PO_PRICE 		= parseFloat(POPRICE);
			}
			console.log('b'+row)

			for(i=0;i<=totRow;i++)
			{
				let myObj 	= document.getElementById('data'+i+'JOBCODEID');
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
				
				if(theObj != null)
				{
					var JOBCODEID2 	= document.getElementById('data'+i+'JOBCODEID').value;

					TOTPO_VAL3 	= 0;
					for(j=1;j<=totRow;j++)
					{
						let myObj 	= document.getElementById('data'+j+'JOBCODEID');
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
						
						if(theObj != null)
						{
							var JOBCODEID3 	= document.getElementById('data'+j+'JOBCODEID').value;

							if(JOBCODEID3 == JOBCODEID2 && i != j)
							{
								PO_VOL3		= parseFloat(document.getElementById('data'+j+'PO_VOLM').value);
								POPRICE3	= parseFloat(document.getElementById('data'+j+'PO_PRICE').value);
								PODISC3		= parseFloat(document.getElementById('data'+j+'PO_DISC').value);
								PO_VAL3 	= parseFloat(PO_VOL3) * parseFloat(POPRICE3);
								TOTPO_VAL3 	= parseFloat(TOTPO_VAL3) + parseFloat(PO_VAL3) - parseFloat(PODISC3);
							}
						}
					}

					ITM_RAPVAL	= parseFloat(REM_VAL_JOB) - parseFloat(TOTPO_VAL3);
					document.getElementById('ITM_MAXREQ_AMN'+i).value 	= ITM_RAPVAL;
					document.getElementById('remValue_'+i).innerHTML	= "S : "+doDecimalFormat(RoundNDecimal(parseFloat(ITM_RAPVAL), 2));
				}
			}

			// START : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE
				/*totRow		= document.getElementById('totalrow').value;
				for(i=1;i<=totRow;i++)
				{
					let myObj 	= document.getElementById('data'+i+'JOBCODEID');
					var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
					
					if(theObj != null)
					{
						var JOBCODEID2 	= document.getElementById('data'+i+'JOBCODEID').value;
						var PO_DESCID2 	= document.getElementById('data'+i+'PO_DESC_ID').value;
						console.log(JOBCODEID1+' vs '+JOBCODEID2+'  && '+PO_DESCID1+' != '+PO_DESCID2)
						if(JOBCODEID1 == JOBCODEID2 && PO_DESCID1 != PO_DESCID2)
						{
							PO_VOL2		= parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
							POPRICE2	= parseFloat(document.getElementById('data'+i+'PO_PRICE').value);
							PO_VAL2 	= parseFloat(PO_VOL2) * parseFloat(POPRICE2);
							REM_RAPVAL2	= parseFloat(REM_RAPVAL) - parseFloat(PO_VAL2);

							document.getElementById('ITM_MAXREQ_AMN'+i).value 	= REM_RAPVAL2;
							document.getElementById('remValue_'+i).innerHTML	= "S : "+doDecimalFormat(RoundNDecimal(parseFloat(REM_RAPVAL2), 2));
						}
					}
				}*/
			// END : RECHECK REMAIN FOR THE NEXT ROW FOR THE SAME JOBCODEID AND ITM_CODE
		
			// PO DISCOUNT
				thisValDISC		= document.getElementById('PO_DISC'+row);
				PO_DISC			= parseFloat(eval(thisValDISC).value.split(",").join(""));
				document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		
			// PO TEMP
				ITMPRICE_TEMP	= parseFloat((PO_VOL * PO_PRICE) - PO_DISC);
		
			// PO TAX		
				var TAXCODE1	= document.getElementById('data'+row+'TAXCODE1').value;
				var taxPerc		= 0;
				if(TAXCODE1 == 0 || TAXCODE1 == '')
				{
					document.getElementById('data'+row+'TAXPRICE1').value 	= 0;
					PO_COSTnPPn	= parseFloat(ITMPRICE_TEMP);

					document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
					document.getElementById('PO_COSTnPPn'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)), 2));
				}
				else
				{
					var url 		= "<?php echo $secGTax; ?>";
					var collID		= "<?php echo $tblTax; ?>~"+TAXCODE1;
					$.ajax({
			            type: 'POST',
			            url: url,
			            data: {collID: collID},
						async: false,
			            success: function(taxPerc2)
			            {
			            	var myarr 		= taxPerc2.split("~");
			            	var taxType 	= myarr[0];
			            	var taxPerc1 	= myarr[1];

			            	taxPerc 		= parseFloat(taxPerc1 / 100);
			            	TAX1VAL			= parseFloat(ITMPRICE_TEMP * taxPerc);
							document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));

							if(taxType == 1)		// 1 = PPn, 2 = PPh
								PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP + TAX1VAL);
							else
								PO_COSTnPPn		= parseFloat(ITMPRICE_TEMP - TAX1VAL);

							document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(ITMPRICE_TEMP));
							document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)), 2));
			            }
			        });
				}
				console.log('c'+row)
			// GET ITMVOL_R
				let task 		= "<?=$task?>";
				let PRJCODE 	= document.getElementById('data'+row+'PRJCODE').value;
				let PR_NUM 		= document.getElementById('data'+row+'PR_NUM').value;
				let PO_NUM 		= document.getElementById('data'+row+'PO_NUM').value;
				let ITM_CODE	= document.getElementById('data'+row+'ITM_CODE').value;
				let PO_DESC_ID	= document.getElementById('data'+row+'PO_DESC_ID').value;

				console.log('d'+row)
				console.log('task: '+task+' , PRJCODE: '+PRJCODE+' , PR_NUM: '+PR_NUM+', PO_NUM: '+PO_NUM+', ITM_CODE: '+ITM_CODE+', PO_DESC_ID: '+PO_DESC_ID+', PO_VOLMBF: '+PO_VOLMBF+', PO_VOL: '+PO_VOL)
				$.ajax({
					type: 'POST',
		            url: '<?php echo site_url('c_purchase/c_p180c21o/getITMVOL_R'); ?>',
					dataType: 'JSON',
		            data: {task: task, PRJCODE:PRJCODE, PR_NUM:PR_NUM, PO_NUM:PO_NUM, ITM_CODE:ITM_CODE, PO_DESC_ID:PO_DESC_ID, PO_VOLMBF:PO_VOLMBF, PO_VOL:PO_VOL},
					success: function(result) {
						console.log(result);
						for(let i = 1; i <= totalrow; i++) {
							let ITM_CODEx 	= document.getElementById('data'+i+'ITM_CODE').value;
							let PO_DESC_IDx	= document.getElementById('data'+i+'PO_DESC_ID').value;
							
							if(ITM_CODEx == ITM_CODE && PO_DESC_IDx == PO_DESC_ID)
							{
								$('#ITMVOL_R'+result['PO_DESC_ID']+i).html(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(result['ITMVOLM_R'])),decFormat)));
								
								/* ------------------------ HOLD ------------------------------------------
								PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
								document.getElementById('data'+i+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
								document.getElementById('PO_PRICEX'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
								-------------------- END HOLD ----------------------------- */
							}
						}
					}
				});

			getTOTOP();
			getDPer();
		}
		
	}

	function chgReqVol(thisVal, row)
	{
		// console.log(row);
		var decFormat		= document.getElementById('decFormat').value;
		console.log(thisVal);
		var PO_VOLMX 		= thisVal;
		if(PO_VOLMX.indexOf(',') > 1) PO_VOLMX 		= parseFloat(eval(thisVal).value.split(",").join(""));
		console.log(PO_VOLMX);
		var ITM_REMAIN		= parseFloat(document.getElementById('PO_REMVOLM'+row).value);
		var ITM_REMVOLV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAIN)), decFormat));
		if(PO_VOLMX > ITM_REMAIN)
		{
			// alert(PO_VOLMX+' > '+ITM_REMAIN);
			swal('<?php echo $OrdMTReq; ?> ('+ITM_REMVOLV+')',
			{
				icon: "warning",
			});
			document.getElementById('PO_REQVOLMX'+row).focus();
			document.getElementById('PO_REQVOLMX'+row).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMVOLV)), decFormat));
			document.getElementById('PO_REQVOLM'+row).value = ITM_REMVOLV;
			return false;
		}
		else
		{
			document.getElementById('PO_REQVOLMX'+row).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), decFormat));
			document.getElementById('PO_REQVOLM'+row).value = thisVal;
		}
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICP				= document.getElementById('PO_DISPx'+row);
		PO_DISP				= parseFloat(eval(valDICP).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_DISP').value 	= parseFloat(Math.abs(PO_DISP));
		document.getElementById('PO_DISPx'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISP)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('PO_VOLMX'+row);
		PO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEX'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(PO_VOLM * PO_PRICE);
		DISCOUNT			= parseFloat(PO_DISP * ITMPRICE_TEMP / 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('PO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));

		
		getValuePO(row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICC				= document.getElementById('PO_DISC'+row);
		PO_DISC				= parseFloat(eval(valDICC).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		document.getElementById('PO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISC)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('PO_VOLMX'+row);
		PO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEX'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(PO_VOLM * PO_PRICE);
		DISCOUNTP			= parseFloat(PO_DISC / ITMPRICE_TEMP * 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'PO_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('PO_DISPx'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		getValuePO(row)
	}

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PR_NUM		= arrItem[0];
		//swal(PR_NUM);
		//document.getElementById("PO_NUM1").value = PO_NUM;
		document.getElementById("PR_NUMX").value = PR_NUM;
		document.frmsrch.submitSrch.click();
	}
	
	function add_desc(strItem) 
	{
		arrItem 	= strItem.split("|");
		row 		= document.getElementById('selROW').value;
		DESC_ID 	= arrItem[0];
		DESC_NOTES 	= arrItem[1];

		ITMCODEA 	= document.getElementById('selITM').value;
		JCIDA 		= document.getElementById('data'+row+'JOBCODEID');
		DESCIDA 	= DESC_ID;

		totRow		= document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'PO_DESC_ID');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(theObj != null)
			{
				var ITMCODEB 	= document.getElementById('data'+i+'ITM_CODE').value;
				var JCIDB 		= document.getElementById('data'+i+'JOBCODEID').value;
				var DESCIDB 	= document.getElementById('data'+i+'PO_DESC_ID').value;

				if(row != i)
				{
					// if(ITMCODEA == ITMCODEB && DESCIDA == DESCIDB)
					if(JCIDA == JCIDB)
					{
						swal('Terdapat item dengan keterangan yang sama',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('data'+row+'PO_DESC_ID').value 	= 0;
							document.getElementById('data'+row+'PO_DESC').value 		= "";
						});
						return false;
					}
				}

			}
		}

		document.getElementById('data'+row+'PO_DESC_ID').value 	= DESC_ID;
		document.getElementById('data'+row+'PO_DESC').value 	= DESC_NOTES;
	}
	
	function checkForm()
	{
		totRow	= document.getElementById('totalrow').value;

		PR_NUM1	= document.getElementById('PR_NUM1').value;
		if(PR_NUM1 == '')
		{
			swal("<?php echo $PREmpty; ?>",
			{
				icon: "warning",
			});
			document.getElementById('PR_NUM1').focus();
			return false;
		}

		SPLCODE	= document.getElementById('SPLCODE').value;
		if(SPLCODE == 0)
		{
			swal("<?php echo $selSpl; ?>",
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#SPLCODE').focus();
            });
			return false;
		}
		PO_NOTES = document.getElementById('PO_NOTES').value;
		if(PO_NOTES == '')
		{
			swal("<?php echo $NotesNE; ?>",
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#PO_NOTES').focus();
            });
			return false;
		}

		if(totRow > 0)
		{
			for(i=1;i<=totRow;i++)
			{
				let myObj 	= document.getElementById('data'+i+'PO_VOLM');
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					var PO_VOLM = parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
					if(PO_VOLM == 0)
					{
						swal("<?php echo $alert1; ?>",
						{
							icon: "warning",
						});
						document.getElementById('PO_VOLMX'+i).focus();
						return false;	
					}
				}
			}

			for(i=1;i<=totRow;i++)
			{
				let myObj 	= document.getElementById('PO_COSTnPPn'+i);
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					var totITM = parseFloat(Math.abs(eval(myObj).value.split(",").join("")));
					totOP = parseFloat(totOP) + parseFloat(totITM);
				}
			}
		}
		else
		{
			swal("<?php echo $alert3; ?>",
			{
				icon: "warning",
			});
			return false;
		}

		var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		}
	}
	
	function changeValue(thisVal, theRow)
	{
		var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			swal('<?php echo $greaterQty; ?> '+ ITM_QTY_MIN,
			{
				icon: "warning",
			});
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		}
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var ITM_PRICEx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEx));
		document.getElementById('ITM_PRICEx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function deleteRow(btn)
	{
		// var row = document.getElementById("tr_" + btn);
		// row.remove();
		let row 	= document.getElementById("tr_" + btn);
		let totRow	= document.getElementById("totalrow").value;
		swal({
            text: "<?php echo $alert17; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				swal("Your Item has been deleted!", {
					icon: "success",
				});
				row.remove();
				
				let remTRow		= parseInt(totRow - 1);
				document.getElementById("totalrow").value = remTRow;
				getTOTOP();
				getDPer();
            } 
            else 
            {
				swal("Your Item is safe!");
            }
        });
	}

	function trashItemFile(row, fileName)
	{		
		swal({
            text: "<?php echo $alert8; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        }).then((willDelete) => {
			if (willDelete) {
				let PO_NUM	= "<?php echo $PO_NUM; ?>";
				let PRJCODE	= "<?php echo $PRJCODE; ?>";
				$.ajax({
					type: "POST",
					url: "<?php echo site_url("c_purchase/c_p180c21o/trashFile"); ?>",
					data: {PO_NUM:PO_NUM, PRJCODE:PRJCODE, fileName:fileName},
					beforeSend: function(xhr) {
						console.log(xhr);
					},
					success: function(callback) {
						console.log(callback);
						swal("File has been deleted!", {icon: "success",});
						$('.itemFile_'+row).remove();
					},
				});
			}
			else {
				swal("Your file is safe!");
			}
		});
	}

	function viewFile(fileName)
	{
		const url 		= "<?php echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		const urlOpen	= "<?php echo base_url(); ?>";
		let PRJCODE 	= "<?php echo $PRJCODE; ?>";
		let path 		= "PO_Document/"+PRJCODE+"/"+fileName+"";
		let FileUpName	= ''+path+'&base_url='+urlOpen;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		let left = (screen.width/2)-(w/2);
		let top = (screen.height/2)-(h/2);
		return window.open(url+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}

	function cancelRow(row) 
	{
		var collID  	= document.getElementById('urlgetID'+row).value;
        var myarr   	= collID.split("~");
        var url     	= myarr[0];
        var PO_NUM     	= myarr[1];
        var PRJCODE     = myarr[2];
        var ITM_CODE    = myarr[3];
        var ITM_NAME    = myarr[4];
        var JOBPARDESC 	= myarr[5];
        var PO_VOLM     = myarr[6];
        var IR_VOLM     = myarr[7];
        var REM_VOLPO   = myarr[8];
        var ITM_UNIT   	= myarr[9];

        var JobNm 		= "<?=$JobNm?>";

		document.getElementById('itmCode').innerHTML 	= ITM_CODE;
		document.getElementById('itmName').innerHTML 	= ITM_NAME;
		document.getElementById('itmName1').innerHTML 	= ITM_NAME;
		//document.getElementById('itmUnit').innerHTML 	= ITM_UNIT;
		document.getElementById('itmRow').value 		= row;
		document.getElementById('jobName').innerHTML 	= JobNm+' : '+JOBPARDESC;
		document.getElementById('itmPOVol').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)), 2))+' '+ITM_UNIT;
		document.getElementById('itmIRVol').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(IR_VOLM)), 2))+' '+ITM_UNIT;
		document.getElementById('itmREMVol').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_VOLPO)), 2))+' '+ITM_UNIT;
		document.getElementById('PO_RVOL').value 		= RoundNDecimal(parseFloat(Math.abs(REM_VOLPO)), 2);
		document.getElementById('V_DOCREF').value 		= "";
		document.getElementById('PO_CVOL').value 		= parseFloat(0);
		document.getElementById('PO_CVOLX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

		document.getElementById('btnModal').click();
	}

	function chgCncVol(cncVol)
	{
		var REMVOL 	= parseFloat(document.getElementById('PO_RVOL').value);
		var CANVOL 	= parseFloat(cncVol);
		if(isNaN(CANVOL) == true)
			CANVOL 	= 0;

		if(CANVOL > REMVOL)
		{
			swal("Jumlah yang akan dibatalkan lebih besar dari sisa volume.",
			{
				icon:"warning",
			})
			.then(function()
			{
				swal.close();
				document.getElementById('PO_CVOL').value 	= parseFloat(REMVOL);
				document.getElementById('PO_CVOLX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMVOL)), 2));
			})
		}
		else
		{
			document.getElementById('PO_CVOL').value 		= parseFloat(CANVOL);
			document.getElementById('PO_CVOLX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CANVOL)), 2));
		}
	}

	function proc_cnc()
	{
		var row 		= document.getElementById('itmRow').value;
		var V_DOCREF 	= document.getElementById('V_DOCREF').value;
        var PO_CVOL 	= parseFloat(document.getElementById('PO_CVOL').value);

        if(V_DOCREF == '')
        {
        	swal("Nomor dokumen pembatalan tidak boleh kosong.",
        	{
        		icon:"warning"
        	})
        	.then(function()
        	{
        		swal.close();
        		document.getElementById('V_DOCREF').focus();
        		return false;
        	})
        }
        else if(PO_CVOL == 0)
        {
        	swal("Volume pembatalan tidak boleh kosong.",
        	{
        		icon:"warning"
        	})
        	.then(function()
        	{
        		swal.close();
        		document.getElementById('PO_CVOLX').focus();
        		return false;
        	})
        }
        else
        {
	        swal({
	            text: "<?php echo $sureVoid; ?>",
	            icon: "warning",
	            buttons: ["No", "Yes"],
	        })
	        .then((willDelete) => 
	        {
	            if (willDelete) 
	            {
					var collID1  	= document.getElementById('urlcanD'+row).value;
					var collID  	= collID1+'~'+V_DOCREF+'~'+PO_CVOL;
			        var myarr   	= collID.split("~");
			        var url     	= myarr[0];

	                $.ajax({
	                    type: 'POST',
	                    url: url,
	                    data: {collID: collID},
	                    success: function(response)
	                    {
	                        swal(response, 
	                        {
	                            icon: "success",
	                        });
	                        document.getElementById('idCloseDRow').click();
	                        $('#tbl').DataTable().ajax.reload();
	                    }
	                });
	            } 
	            else 
	            {
	                //...
	            }
	        });
	    }
    }

	function add_item_221009(strItem) 
	{
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var PO_NUM 		= "<?php echo $DocNumber; ?>";
		var PO_CODE 	= "<?php echo $PO_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		//swal(PO_NUM);
		//swal(PO_CODE);
		//swal(PRJCODE);
		
		ilvl = arrItem[1];
		//swal(PR_NUMx);
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		ITM_CODE 		= arrItem[0];
		CSTDESC 		= arrItem[1];
		unit_type_code 	= arrItem[2];
		IR_VOLM			= "";
		PO_VOLM			= "";
		Unit_Price		= arrItem[3];
		CSTDISP			= "";
		CSTDISC			= "";
		ITM_COST			= "";
		
		//swal(unit_type_code);
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// PO_NUM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" ><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PO_NUM" name="data['+intIndex+'][PO_NUM]" value="'+PO_NUM+'" width="10" size="15" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PO_CODE" name="data['+intIndex+'][PO_CODE]" value="'+PO_CODE+'" width="10" size="15" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" class="form-control" style="max-width:300px;"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		//swal(totalrow);
		
		// ITM_CODE [0]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';
		//swal(ITM_CODE);
		
		// CSTDESC [1]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+CSTDESC+'';
		//swal(CSTDESC);
		
		// Item IR_VOLM [2] >> ??
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+IR_VOLM+'<input type="hidden" id="data'+intIndex+'IR_VOLM" name="data['+intIndex+'][IR_VOLM]" value="'+IR_VOLM+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';
		//swal(IR_VOLM);
				
		// PO_VOLM [3]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" id="data'+intIndex+'PO_VOLM" name="data['+intIndex+'][PO_VOLM]" value="'+PO_VOLM+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';
		//swal(PO_VOLM);
		
		// ITM_UNIT [4]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+unit_type_code+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+unit_type_code+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';		
		//swal(unit_type_code);
		
		// Unit CSTPUNT [5]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" id="data['+intIndex+'][CSTPUNTx]" name="data['+intIndex+'][CSTPUNTx]" value="'+Unit_Price+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right" disabled><input type="hidden" id="data['+intIndex+'][CSTPUNT]" name="data['+intIndex+'][CSTPUNT]" value="'+Unit_Price+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';
		//swal(CSTPUNT);
		
		
		// CSTDISP (%)
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" id="data['+intIndex+'][CSTDISP]" name="data['+intIndex+'][CSTDISP]" value="'+CSTDISP+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';

		
		// CSTDISC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" id="data['+intIndex+'][CSTDISC]" name="data['+intIndex+'][CSTDISC]" value="'+CSTDISC+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';
		
		// ITM_COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" id="data['+intIndex+'][ITM_COST]" name="data['+intIndex+'][ITM_COST]" value="'+ITM_COST+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';
		
		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="max-width:150px" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		//swal(objTD.innerHTML);
		
		//var decFormat												= document.getElementById('decFormat').value;
		//var UnitPrice_Budget										= document.getElementById('UnitPrice'+intIndex).value
		//swal(UnitPrice_Budget);
		//document.getElementById('UnitPrice'+intIndex).value 		= parseFloat(Math.abs(UnitPrice_Budget));
		//swal(document.getElementById('UnitPrice'+intIndex).value);
		//document.getElementById('UnitPricex'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(UnitPrice_Budget)),decFormat));
		//var PPMat_Requested											= document.getElementById('PPMat_Requested'+intIndex).value;
		//document.getElementById('PPMat_Requested'+intIndex).value 	= parseFloat(Math.abs(PPMat_Requested));
		//document.getElementById('PPMat_Requestedx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Requested)),decFormat));
		
		//var IR_VOLM													= document.getElementById('IR_VOLM'+intIndex).value
		//document.getElementById('IR_VOLM'+intIndex).value 			= parseFloat(Math.abs(IR_VOLM));
		//document.getElementById('IR_VOLM'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(IR_VOLM)),decFormat));
		
		//document.getElementById('totalrow').value = intIndex;	
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		console.log(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var PO_NUM 		= "<?php echo $DocNumber; ?>";
		var PO_CODE 	= "<?php echo $PO_CODE; ?>";
		var PR_NUM 		= "<?php echo $PR_NUMX; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		var task 		= "<?php echo $task; ?>";
		// let row 		= 1;
		const decFormat	= document.getElementById('decFormat').value;
		const totRow 	= document.getElementById('totalrow').value;

		// if(task == 'add') 
		// {
		// 	$('#tblITMDET .odd').empty();
		// 	row = 0;
		// }

		ilvl = arrItem[1];

		PO_ID 			= arrItem[0];
		PRD_ID 			= arrItem[1];
		ITM_CODE 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		JOBCODEDET 		= arrItem[4];
		JOBCODEID 		= arrItem[5];
		JOBPARENT 		= arrItem[6];
		JOBPARDESC 		= arrItem[7];
		JOBDESC 		= arrItem[8];
		RAP_PRICE 		= arrItem[9];
		ITM_BUDGVAL 	= arrItem[10];
		ORDERED_VAL 	= arrItem[11];
		ITM_REMVOL 		= arrItem[12];
		ITM_REMVAL 		= arrItem[13];
		ITM_CATEG 		= arrItem[14];
		PR_VOLM 		= arrItem[15];
		PR_AMOUNT 		= arrItem[16];
		TOTVAL_OTHDOC	= arrItem[17];
		// PO_VOLM 		= arrItem[18];
		ITM_PRICE 		= 0;
		PO_DISP 		= 0;
		PO_DISC 		= 0;
		TAXPRICE1 		= 0;
		PO_COST 		= 0;
		PO_COSTnPPn 	= 0;
		ITMVOLM_R 		= arrItem[19];
		ITM_UNIT 		= arrItem[20];
		PR_DESC_ID 		= arrItem[21];
		PO_DESC_ID 		= arrItem[22];
		PO_DESC 		= arrItem[23];
		selRow 			= arrItem[24];
		PO_VOLM 		= document.getElementById('PO_REQVOLM'+selRow).value;

		// Validate double ITM_CODE, JOBCODEID, PR_DESC_ID
		if(totRow > 0)
		{
			let TOT_VOLPODET = 0;
			let REM_VOLPODET = 0;
			for(let i=1; i<=totRow; i++) {
				let ITM_CODE_DET 	= document.getElementById('data'+i+'ITM_CODE').value;
				let JOBCODEID_DET 	= document.getElementById('data'+i+'JOBCODEID').value;
				let PR_DESC_ID_DET 	= document.getElementById('data'+i+'PR_DESC_ID').value;
				let PO_DESC_ID_DET 	= document.getElementById('data'+i+'PO_DESC_ID').value;
				let PO_VOLM_DET 	= document.getElementById('data'+i+'PO_VOLM').value;
				// alert("PO_VOLM_DET = "+PO_VOLM_DET);
				// alert(ITM_CODE_DET+'='+arrItem[2]+' - '+JOBCODEID_DET+'='+arrItem[5]+''+PO_DESC_ID_DET+'='+arrItem[21]);
				if(ITM_CODE_DET == arrItem[2] && JOBCODEID_DET == arrItem[5])
				{
					if(PO_DESC_ID_DET == arrItem[22])
					{
						swal(arrItem[23]+" dengan kode pekerjaan "+arrItem[5]+" sudah ditambahkan.", { icon:"warning" });
						return false;
					}
					else if(PR_DESC_ID_DET == arrItem[21])
					{
						// cek volume SPP
						TOT_VOLPODET = parseFloat(TOT_VOLPODET) + parseFloat(PO_VOLM_DET);
						// alert("TOT_VOLPODET = "+TOT_VOLPODET);
						REM_VOLPODET = parseFloat(PR_VOLM) - parseFloat(TOT_VOLPODET);
						// alert("REM_VOLPODET = "+REM_VOLPODET);
						if(PO_VOLM > REM_VOLPODET)
						{
							swal(arrItem[23]+" dengan kode pekerjaan "+arrItem[5]+" tidak boleh melebihi volume yang diminta.", { icon:"warning" });
							return false;
						}
					}
				}
			}
		}
		
		objTable1 		= document.getElementById('tblITMDET');
		objTable 		= objTable1.getElementsByTagName('tbody')[0];
		console.log(objTable);
		// var totalRowCount = table.rows.length; // 5
		// var tbodyRowCount = table.tBodies[0].rows.length; // 3
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		// intIndex = parseInt(document.frm.rowCount.value) + 1;
		
		intIndex = parseInt(objTable.rows.length) + 1;
		//intIndex = intTable;
		// document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// alert('intTable = '+intTable+' intIndex = '+intIndex);
		// selRow 		= arrItem[5];
		
		// PO_REQVOLM = document.getElementById('PO_REQVOLMx'+selRow).value;
		// console.log(PO_REQVOLM);

		// <!-- NO URUT -->
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>'+
						  '<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis4(this,'+intIndex+')" style="display:none">'+
						  '<input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" >'+
						  '<input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">'+
						  '<input type="hidden" id="data'+intIndex+'PO_NUM" name="data['+intIndex+'][PO_NUM]" value="'+PO_NUM+'" width="10" size="15" class="form-control" style="max-width:300px;">'+
						  '<input type="hidden" id="data'+intIndex+'PO_CODE" name="data['+intIndex+'][PO_CODE]" value="'+PO_CODE+'" width="10" size="15" class="form-control" style="max-width:300px;">'+
						  '<input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" class="form-control" style="max-width:300px;">'+
						//   '<input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">'+
						  '<input type="hidden" id="data'+intIndex+'PO_ID" name="data['+intIndex+'][PO_ID]" value="'+PO_ID+'" width="10" size="15">';
		//swal(totalrow);
		
		console.log(intIndex+' => 1');
		
		// <!-- ITEM CODE -->
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.textAlign = 'center';
		objTD.style.display = 'none';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">'+
						  '<input type="hidden" id="data'+intIndex+'PRD_ID" name="data['+intIndex+'][PRD_ID]" value="'+PRD_ID+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'ITM_NAME" value="'+ITM_NAME+'" >'+
						  '<input type="hidden" id="data'+intIndex+'PO_NUM" name="data['+intIndex+'][PO_NUM]" value="'+PO_NUM+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'PO_CODE" name="data['+intIndex+'][PO_CODE]" value="'+PO_CODE+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'PR_NUM" name="data['+intIndex+'][PR_NUM]" value="'+PR_NUM+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'JOBPARENT" name="data['+intIndex+'][JOBPARENT]" value="'+JOBPARENT+'" width="10" size="15">'+
						  '<input type="hidden" id="data'+intIndex+'JOBPARDESC" name="data['+intIndex+'][JOBPARDESC]" value="'+JOBDESC+'" width="10" size="15">';
		//swal(ITM_CODE);

		console.log(intIndex+' => 2');
		
		// <!-- ITEM NAME -->
		RAP_PRICEvx 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RAP_PRICE)), decFormat));
		ITM_BUDGVALvx 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGVAL)), decFormat));
		ORDERED_VALvx 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ORDERED_VAL)), decFormat));
		ITM_REMVALvx 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMVAL)), decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.textAlign = 'left';
		objTD.noWrap 	= true;
		objTD.innerHTML = ''+ITM_CODE+' : '+ITM_NAME+ '<span class="text-red" style="font-style: italic; font-weight: bold;">(Hrg.RAP : '+RAP_PRICEvx+')</span>'+
						  '<div style="font-style: italic;"><i class="text-muted fa fa-chevron-circle-right"></i>&nbsp;&nbsp;'+JOBDESC+' ('+JOBPARENT+')</div>'+
						  '<div style="font-style: italic;"><i class="text-muted fa fa-commenting-o"></i>&nbsp;&nbsp;'+PO_DESC+'</div>'+
						  '<div style="font-style: italic; margin-left: 15px;">'+
						  '<span style="font-style: italic; font-weight: bold;">RAP : &nbsp;&nbsp;'+ITM_BUDGVALvx+'</span>'+
						  '<span id="usedValue_'+intIndex+'" style="font-style: italic; font-weight: bold; display: none;">'+
						  'R : &nbsp;&nbsp;'+ORDERED_VALvx+'</span>&nbsp;&nbsp;'+
						  '<span id="remValue_'+intIndex+'" style="font-style: italic; font-weight: bold;">'+
						  'S : '+ITM_REMVALvx+'</span>'+
						  '</div>';
		//swal(CSTDESC);

		console.log(intIndex+' => 3');
		
		// <!-- ITEM BUDGET -->
		PR_VOLMvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PR_VOLM)), decFormat));
		if(ITM_CATEG != 'UA') PR_VOLMv = PR_VOLMvx;
		else PR_VOLMv = '-';
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = PR_VOLMv;

		console.log(intIndex+' => 4');
				
		// <!-- ITEM REMAIN -->
		ITM_REMVOLvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMVOL)), decFormat));
		if(ITM_CATEG != 'UA') ITM_REMVOLv = ITM_REMVOLvx;
		else ITM_REMVOLv = '-';
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+ITM_REMVOLv+'<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_CATEG'+intIndex+'" id="ITM_CATEG'+intIndex+'" value="'+ITM_CATEG+'" >'+
						  '<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN'+intIndex+'" id="ITM_REMAIN'+intIndex+'" value="'+ITM_REMVOL+'" >'+
						  '<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_BUDGVAL'+intIndex+'" id="ITM_BUDGVAL'+intIndex+'" value="'+ITM_BUDGVAL+'" >'+
						  '<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="TOTVAL_OTHDOC'+intIndex+'" id="TOTVAL_OTHDOC'+intIndex+'" value="'+TOTVAL_OTHDOC+'" >'+
						  '<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_MAXREQ_AMN'+intIndex+'" id="ITM_MAXREQ_AMN'+intIndex+'" value="'+ITM_REMVAL+'" >'+
						  '<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="JOB_MAXREQ_AMN'+intIndex+'" id="JOB_MAXREQ_AMN'+intIndex+'" value="'+ITM_REMVAL+'" >';
		//swal(PO_VOLM);

		console.log(intIndex+' => 5');
		
		// <!-- ITEM ORDER NOW -->
		PO_VOLMvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)), decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:90px; max-width:300px; text-align:right" name="PO_VOLMX'+intIndex+'" id="PO_VOLMX'+intIndex+'" value="'+PO_VOLMvx+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO('+intIndex+');">'+
						  '<input type="hidden" id="data'+intIndex+'PR_VOLM" name="data['+intIndex+'][PR_VOLM]" value="'+PR_VOLM+'">'+
						  '<input type="hidden" id="data'+intIndex+'PR_AMOUNT" name="data['+intIndex+'][PR_AMOUNT]" value="'+PR_AMOUNT+'">'+
						  '<input type="hidden" id="data'+intIndex+'PO_VOLM" name="data['+intIndex+'][PO_VOLM]" value="'+PO_VOLM+'">';		
		//swal(unit_type_code);

		console.log(intIndex+' => 6');
		
		// Rekap Item => hidden ---- 
		/* --------------------
		ITMVOLM_Rvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMVOLM_R)), decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<span id="ITMVOL_R'+PO_DESC_ID+''+intIndex+'" style="text-align: right;">'+ITMVOLM_Rvx+'</span>';
		//swal(CSTPUNT);
		------------------------ */

		console.log(intIndex+' => 7');
		
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		console.log(intIndex+' => 8');

		// <!-- ITEM PRICE -->
		ITM_PRICEvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)), decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:90px" name="PO_PRICEX'+intIndex+'" id="PO_PRICEX'+intIndex+'" size="10" value="'+ITM_PRICEvx+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO('+intIndex+');">'+
						  '<input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_PRICE]" id="data'+intIndex+'PO_PRICE" size="6" value="'+ITM_PRICE+'">';
		
		console.log(intIndex+' => 9');

		// <!-- ITEM DISCOUNT PERCENTATION -->
		PO_DISPvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISP)), decFormat));
		if(ITM_CATEG != 'UA') 
			PO_DISPv = '<input type="text" class="form-control" size="10"  style=" min-width:70px; max-width:150px; text-align:right" name="PO_DISPx'+intIndex+'" id="PO_DISPx'+intIndex+'" value="'+PO_DISPvx+'" onBlur="countDisp(this.value, '+intIndex+');" >';
		else 
			PO_DISPv = '-<input type="hidden" class="form-control" size="10"  style=" min-width:70px; max-width:150px; text-align:right" name="PO_DISPx'+intIndex+'" id="PO_DISPx'+intIndex+'" value="'+PO_DISP+'" onBlur="countDisp(this.value, '+currentRow+');" >';
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = PO_DISPv+'<input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_DISP]" id="data'+intIndex+'PO_DISP" value="'+PO_DISP+'">';

		console.log(intIndex+' => 10');

		// <!-- ITEM DISCOUNT -->
		PO_DISCvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISC)), decFormat));
		if(ITM_CATEG != 'UA') 
			PO_DISCv = '<input type="text" class="form-control" style="min-width:90px; max-width:350px; text-align:right" name="PO_DISC'+intIndex+'" id="PO_DISC'+intIndex+'" value="'+PO_DISCvx+'" onBlur="countDisc(this.value, '+intIndex+');" >';
		else 
			PO_DISCv = '-<input type="hidden" class="form-control" style="min-width:90px; max-width:350px; text-align:right" name="PO_DISC'+intIndex+'" id="PO_DISC'+intIndex+'" value="'+PO_DISC+'" onBlur="countDisc(this.value, '+intIndex+');" >';
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+PO_DISCv+'<input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_DISC]" id="data'+intIndex+'PO_DISC" value="'+PO_DISC+'">';
		//swal(objTD.innerHTML);

		console.log(intIndex+' => 11');

		// <!-- ITEM TAX -->
		console.log(intIndex+' => 12');
		if(ITM_CATEG != 'UA') 
			TAXCODE1v = '<select name="data['+intIndex+'][TAXCODE1]" class="form-control select2" id="data'+intIndex+'TAXCODE1"  onChange="getValuePO('+intIndex+');" style="width:150px;">'+optTAX1+
						'</select>';
		else 
			TAXCODE1v = '-<input type="hidden" name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1"  value="">';
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.style.width = '200px';
		objTD.innerHTML = TAXCODE1v;
		//swal(objTD.innerHTML);
		var optTAX1 = "<option value=''> --- </option>";
		// GET TAXCODE1
		$.ajax({
			url: "<?php echo site_url('c_purchase/c_p180c21o/getTAXCODE') ?>",
			dataType: "JSON",
			success: function(result) {
				console.log(result);
				// var optTAX1 = "";
				for(let i in result) {
					TAXLA_NUM 	= result[i]['TAXLA_NUM'];
					TAXLA_DESC 	= result[i]['TAXLA_DESC'];
					TAXTYPE 	= result[i]['TAXTYPE'];
					optTAX1 	+= '<option value="'+TAXLA_NUM+'">'+TAXLA_DESC+'</option>';
				}
				$('#data'+intIndex+'TAXCODE1').html(optTAX1);
			}
		});
		
		$('.select2').select2();

		console.log(intIndex+' => 13');

		// <!-- ITEM TOTAL COST -->
		PO_COSTnPPnvx = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)), decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_COST]" id="data'+intIndex+'PO_COST" value="'+PO_COST+'">'+
						  '<input type="text" class="form-control" style="min-width:110px; max-width:350px; text-align:right" name="PO_COSTnPPn'+intIndex+'" id="PO_COSTnPPn'+intIndex+'" value="'+PO_COSTnPPnvx+'" size="5" >'+
						  '<input style="text-align:right" type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="'+TAXPRICE1+'">';
		//swal(objTD.innerHTML);

		console.log(intIndex+' => 14');

		// <!-- PO DESC -->
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = '<div class="input-group">'+
								'<div class="input-group-btn">'+
									'<button type="button" class="btn btn-primary" onClick="getDL('+intIndex+')"><i class=" fa fa-commenting"></i></button>'+
								'</div>'+
								'<input type="hidden" name="data['+intIndex+'][PR_DESC_ID]" id="data'+intIndex+'PR_DESC_ID" size="20" value="'+PR_DESC_ID+'" class="form-control" style="min-width:110px; max-width:500px; text-align:left">'+
								'<input type="hidden" name="data['+intIndex+'][PO_DESC_ID]" id="data'+intIndex+'PO_DESC_ID" size="20" value="'+PO_DESC_ID+'" class="form-control" style="min-width:110px; max-width:500px; text-align:left" data-toggle="modal" data-target="#mdl_addCMN">'+
								'<input type="text" name="data['+intIndex+'][PO_DESC]" id="data'+intIndex+'PO_DESC" size="20" value="'+PO_DESC+'" class="form-control" style="min-width:110px; max-width:500px; text-align:left" readonly>'+
							'</div>';
		//swal(objTD.innerHTML);

		console.log(intIndex+' => 15');
		

		//var decFormat												= document.getElementById('decFormat').value;
		//var UnitPrice_Budget										= document.getElementById('UnitPrice'+intIndex).value
		//swal(UnitPrice_Budget);
		//document.getElementById('UnitPrice'+intIndex).value 		= parseFloat(Math.abs(UnitPrice_Budget));
		//swal(document.getElementById('UnitPrice'+intIndex).value);
		//document.getElementById('UnitPricex'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(UnitPrice_Budget)),decFormat));
		//var PPMat_Requested											= document.getElementById('PPMat_Requested'+intIndex).value;
		//document.getElementById('PPMat_Requested'+intIndex).value 	= parseFloat(Math.abs(PPMat_Requested));
		//document.getElementById('PPMat_Requestedx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Requested)),decFormat));
		
		//var IR_VOLM													= document.getElementById('IR_VOLM'+intIndex).value
		//document.getElementById('IR_VOLM'+intIndex).value 			= parseFloat(Math.abs(IR_VOLM));
		//document.getElementById('IR_VOLM'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(IR_VOLM)),decFormat));
		
		document.getElementById('totalrow').value = intIndex;	

		
	}

	function chgVolPOREQ_221013(thisValue)
	{
		// alert(thisValue);
		const decFormat		= document.getElementById('decFormat').value;
		let jmlItmCUR 		= document.getElementById('totalrow4').value;
		let POVOL_REQVOL	= parseFloat(thisValue);

		document.getElementById('chgVolPO').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisValue)), decFormat));

		for(let i=1; i<=jmlItmCUR; i++) {
			// POVOL_REQVOL = POVOL_REQVOL - PO_REMVOLM;
			// console.log(POVOL_REQVOL+' = '+POVOL_REQVOL+' - '+PO_REMVOLM);
			let PO_REMVOLM = parseFloat(document.getElementById('PO_REMVOLM'+i).value);
			if(PO_REMVOLM > 0)
			{
				console.log('a1 = row'+i+' ='+POVOL_REQVOL+' < '+PO_REMVOLM);
				if(POVOL_REQVOL > PO_REMVOLM)
				{
					console.log('b1 = row'+i+' ='+POVOL_REQVOL+' < '+PO_REMVOLM);
					POVOL_REQVOL = POVOL_REQVOL - PO_REMVOLM;
					$('#chkRow_'+i).prop('checked', true);
					if(POVOL_REQVOL < PO_REMVOLM)
					{
						$('#chkRow_'+i).prop('checked', true);
						$('#PO_REQVOLMX'+i).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POVOL_REQVOL)), decFormat)));
						$('#PO_REQVOLM'+i).val(POVOL_REQVOL);
					}
					// // break;
				}
				else
				{
					$('#chkRow_'+i).prop('checked', true);
					$('#PO_REQVOLMX'+i).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POVOL_REQVOL)), decFormat)));
					$('#PO_REQVOLM'+i).val(POVOL_REQVOL);

					break;
				}
			}
		}
	}
	
	//Update [iyan]
	function add_itemU(strItem) //ADD Upah Angkut
	{
		arrItem = strItem.split('|');		
		//alert(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var totrowPR 	= document.getElementById('totalrowPR').value;
		//alert(totrowPR);
		var PO_NUM 		= "<?php echo $DocNumber; ?>";
		var PO_CODE 	= "<?php echo $PO_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		var PR_NUMX 	= "<?php echo $PR_NUMX; ?>";
		// alert(PO_NUM);
		// alert(PO_CODE);
		// alert(PRJCODE);
		
		ilvl = arrItem[1];
		// alert(PR_NUMX);
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBPARENT 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_UNIT 		= arrItem[6];
		PO_PRICE 		= arrItem[7];
		JOBDESCH 		= arrItem[8];
		ITM_CATEG 		= arrItem[9];
		PO_VOLM 		= 1;
		PR_VOLM 		= 0; // non request SPP utk upah angkut
		PR_AMOUNT 		= 0; // non request SPP utk upah angkut
		ITM_REMAIN 		= 0; // non budget RAP utk upah angkut
		PO_DISP 		= 0; // non disc % utk upah angkut
		PO_DISC 		= 0; // non disc price utk upah angkut
		TAXCODE1 		= 0; // non tax utk upah angkut
		TAXPRICE1		= 0; // non tax utk upah angkut
		TAXPRICE2 		= 0; // non tax utk upah angkut
		PO_COST 		= PO_VOLM * PO_PRICE;
		PO_COSTnPPn		= PO_COST + TAXPRICE1 - TAXPRICE2;
		
		//swal(unit_type_code);
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1 + parseInt(totrowPR);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// PO_NUM, PO_CODE, PRJCODE, PR_NUM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.style = 'vertical-align: middle;';
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PO_NUM" name="data['+intIndex+'][PO_NUM]" value="'+PO_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PO_CODE" name="data['+intIndex+'][PO_CODE]" value="'+PO_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PR_NUM" name="data['+intIndex+'][PR_NUM]" value="'+PR_NUMX+'" width="10" size="15">';

		// ITM_CODE [1]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.style = 'vertical-align: middle;';
		objTD.innerHTML = ''+ITM_CODE+' : '+ITM_NAME+'<div style="font-style: italic;"><i class="text-muted fa fa-chevron-circle-right"></i>&nbsp;&nbsp;'+JOBCODEDET+' : '+JOBDESCH+'</div><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBPARENT" name="data['+intIndex+'][JOBPARENT]" value="'+JOBPARENT+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBPARDESC" name="data['+intIndex+'][JOBPARDESC]" value="'+JOBDESCH+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" name="ITM_CATEG'+intIndex+'" id="ITM_CATEG'+intIndex+'" value="'+ITM_CATEG+'">';
		
		// Permintaan [2]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style = "vertical-align: middle; text-align: center;";
		objTD.innerHTML = '-';
				
		// Sisa [3]
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style = "vertical-align: middle; text-align: center;";
		objTD.innerHTML = '-'+'<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN'+intIndex+'" id="ITM_REMAIN'+intIndex+'" value="'+ITM_REMAIN+'" >';

		// ITEM ORDER NOW
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.style = 'vertical-align: middle;';
		objTD.innerHTML = '<input type="text" id="PO_VOLMX'+intIndex+'" name="PO_VOLMX'+intIndex+'" value="'+PO_VOLM+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO('+intIndex+');" width="10" size="15" class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PO_VOLM" name="data['+intIndex+'][PO_VOLM]" value="'+PO_VOLM+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PR_VOLM" name="data['+intIndex+'][PR_VOLM]" value="'+PR_VOLM+'"><input type="hidden" id="data'+intIndex+'PR_AMOUNT" name="data['+intIndex+'][PR_AMOUNT]" value="'+PR_AMOUNT+'">';
		
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style = "vertical-align: middle; text-align: center;";
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right">';
		
		// PO_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.style = 'vertical-align: middle;';
		objTD.innerHTML = '<input type="text" id="PO_PRICEX'+intIndex+'" name="PO_PRICEX'+intIndex+'" value="'+PO_PRICE+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO('+intIndex+');" width="10" size="15" class="form-control" style="max-width:400px;text-align:right"><input type="hidden" id="data'+intIndex+'PO_PRICE" name="data['+intIndex+'][PO_PRICE]" value="'+PO_PRICE+'" width="10" size="15" class="form-control" style="max-width:400px;text-align:right">';
		
		// Disc %
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style = "vertical-align: middle; text-align: center;";
		objTD.innerHTML = '-'+'<input type="hidden" class="form-control" size="10"  style=" min-width:70px; max-width:150px; text-align:right" name="PO_DISPx'+intIndex+'" id="PO_DISPx'+intIndex+'" value="'+PO_DISP+'" onBlur="countDisp(this.value, '+intIndex+'" ><input type="hidden" name="data['+intIndex+'][PO_DISP]" id="data'+intIndex+'PO_DISP" value="'+PO_DISP+'">';

		
		// Disc
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style = "vertical-align: middle; text-align: center;";
		objTD.innerHTML = '-'+'<input type="hidden" class="form-control" style="min-width:90px; max-width:350px; text-align:right" name="PO_DISC'+intIndex+'" id="PO_DISC'+intIndex+'" value="'+PO_DISC+'" onBlur="countDisc(this.value, '+intIndex+'" ><input type="hidden" name="data['+intIndex+'][PO_DISC]" id="data'+intIndex+'PO_DISC" value="'+PO_DISC+'">';

		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style = "vertical-align: middle; text-align: center;";
		objTD.innerHTML = '-'+'<input type="hidden" name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" value="'+TAXCODE1+'">';
		
		// PO_COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.style = 'vertical-align: middle;';
		objTD.innerHTML = '<input type="hidden" id="data'+intIndex+'PO_COST" name="data['+intIndex+'][PO_COST]" value="'+PO_COST+'" width="10" size="15" class="form-control" style="max-width:350px;text-align:right"><input type="text" class="form-control" style="min-width:110px; max-width:350px; text-align:right" name="PO_COSTnPPn'+intIndex+'" id="PO_COSTnPPn'+intIndex+'" value="'+PO_COSTnPPn+'" size="5" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="'+TAXPRICE1+'">';

		// Spesifikasi
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.style = 'vertical-align: middle;';
		objTD.innerHTML = '<input type="text" id="data['+intIndex+'][PO_DESC]" name="data['+intIndex+'][PO_DESC]" value="" width="10" size="15" class="form-control" style="max-width:350px;text-align:left">';

		//swal(objTD.innerHTML);
		
		
		var decFormat												= document.getElementById('decFormat').value;
		var PO_PRICE												= document.getElementById('data'+intIndex+'PO_PRICE').value;
		var PO_VOLM 												= document.getElementById('data'+intIndex+'PO_VOLM').value;
		var PO_COSTnPPn												= document.getElementById('PO_COSTnPPn'+intIndex).value;
		// alert(PO_VOLM);

		document.getElementById('PO_PRICEX'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
		document.getElementById('PO_VOLMX'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)),decFormat));
		document.getElementById('PO_COSTnPPn'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));
		//var PPMat_Requested											= document.getElementById('PPMat_Requested'+intIndex).value;
		//document.getElementById('PPMat_Requested'+intIndex).value 	= parseFloat(Math.abs(PPMat_Requested));
		//document.getElementById('PPMat_Requestedx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Requested)),decFormat));
		
		//var IR_VOLM													= document.getElementById('IR_VOLM'+intIndex).value
		//document.getElementById('IR_VOLM'+intIndex).value 			= parseFloat(Math.abs(IR_VOLM));
		//document.getElementById('IR_VOLM'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(IR_VOLM)),decFormat));
		
		document.getElementById('totalrow').value = intIndex;	
	}
	// End Update [iyan]
	
	function getTOTOP()
	{
		decFormat	= document.getElementById('decFormat').value;
		totRow		= document.getElementById('totalrow').value;
		totOP 		= 0;
		if(totRow > 0)
		{
			for(i=1;i<=totRow;i++)
			{
				let myObj 	= document.getElementById('PO_COSTnPPn'+i);
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					var totITM = parseFloat(Math.abs(eval(myObj).value.split(",").join("")));
					totOP = parseFloat(totOP) + parseFloat(totITM);
				}
			}
		}
		document.getElementById('PO_TOTCOST').value 		= totOP;
		document.getElementById('PO_TOTCOSTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOP)), decFormat));
		document.getElementById('PO_TOTCOSTD2').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOP)), decFormat));
	}

	function getDL(row)
	{
		ITM_CODE 	= document.getElementById('data'+row+'ITM_CODE').value;
		ITM_NAME 	= document.getElementById('data'+row+'ITM_NAME').value;
		document.getElementById('selROW').value = row;
		document.getElementById('selITM').value = ITM_CODE;
		document.getElementById('ITM_CODE').value 	= ITM_CODE;
		document.getElementById('ITM_CODEX').value 	= ITM_CODE+' : '+ITM_NAME;

		$('#example5').DataTable(
    	{
    		"destroy": true,
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataDESC/?ITMCODE=')?>"+ITM_CODE,
	        "type": "POST",
			"lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
			//"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
							{ width: 10, targets: 0 }
						  ],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});

		document.getElementById('data'+row+'PO_DESC_ID').click();
	}
	
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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