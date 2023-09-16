<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 11 November 2017
	* File Name	= v_po_form.php
	* Location		= -
*/

$this->load->view('template/head');

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

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
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
	$PO_CURR 		= 'IDR';
	$PO_CURRATE		= 1;
	$PO_TAXCURR 	= 'IDR';
	$PO_TAXRATE 	= 1;
	$PO_TOTCOST		= 0;
	$DP_CODE		= '';
	$DP_PPN_		= 0;
	$DP_JUML		= 0;
	$PO_PAYTYPE 	= 'Cash';
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
	$Currency_USD		= 13000;
	
	foreach($viewDocPattern as $row) :
		$Pattern_Code 			= $row->Pattern_Code;
		$Pattern_Position 		= $row->Pattern_Position;
		$Pattern_YearAktive 	= $row->Pattern_YearAktive;
		$Pattern_MonthAktive 	= $row->Pattern_MonthAktive;
		$Pattern_DateAktive 	= $row->Pattern_DateAktive;
		$Pattern_Length 		= $row->Pattern_Length;
		$useYear 				= $row->useYear;
		$useMonth 				= $row->useMonth;
		$useDate 				= $row->useDate;
	endforeach;
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_po_header');
	
	/*$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM tbl_po_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax 	= $row->maxNumber;
			$myMax 	= $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$sql 		= "tbl_po_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->count_all($sql);
	$myMax 		= $result+1;
	
	$thisMonth 		= $month;
	
	$lenMonth 		= strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth 		= $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
	
		
	$lastPatternNumb 	= $myMax;



	$lastPatternNumb1 	= $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	if($PO_TYPE == 1) 
	{
		$POType = '01';
	}
	else if($PO_TYPE == 2) 
	{
		$POType = '02';
	}
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber 		= "$Pattern_Code$PRJCODE$year$month$days$POType-$lastPatternNumb";
	$PO_NUM			= $DocNumber;
	$PO_CODE		= "$lastPatternNumb"; // OP MANUAL
	
	//$POCODE		= substr($lastPatternNumb, -4);
	$POCODE			= $lastPatternNumb;
	$POYEAR			= date('y');
	$POMONTH		= date('m');
	$PO_CODE		= "$Pattern_Code.$POCODE.$POYEAR.$POMONTH"; // MANUAL CODE
	
	$PO_DATEY 		= date('Y');
	$PO_DATEM 		= date('m');
	$PO_DATED 		= date('d');
	$PO_DATE 		= date('d/m/Y');
	$ETD			= $PO_DATE;
	$PO_PLANIRY		= date('Y');
	$PO_PLANIRM		= date('m');
	$PO_PLANIRD		= date('d');
	$PO_PLANIR		= date('d/m/Y');
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	$PR_NUMX		= '';
	
	$PO_RECEIVLOC	= '';
	$PO_RECEIVCP	= '';
	$PO_SENTROLES	= '';
	$PO_REFRENS		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_po_header~$Pattern_Length";
	$dataTarget		= "PO_CODE";
}
else
{
	$isSetDocNo = 1;
	$PO_NUM 		= $default['PO_NUM'];
	$DocNumber		= $PO_NUM;
	$PO_CODE 		= $default['PO_CODE'];
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
	$PO_CURRATE 	= $default['PO_CURRATE'];
	$PO_PAYTYPE 	= $default['PO_PAYTYPE'];
	$PO_TENOR 		= $default['PO_TENOR'];
	$PO_PLANIR 		= $default['PO_PLANIR'];
	$PO_PLANIR		= date('d/m/Y', strtotime($PO_PLANIR));
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
	
	$sqlJCOB		= "SELECT PR_CODE FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
	$resJCOB 		= $this->db->query($sqlJCOB)->result();
	foreach($resJCOB as $rowJOB) :
		$PR_CODE 	= $rowJOB->PR_CODE;
	endforeach;
}

$JOBCODE	= '';
if(isset($_POST['PR_NUMX']))
{
	$PR_NUMX		= $_POST['PR_NUMX'];
	$sqlJCOB		= "SELECT PR_CODE, JOBCODE FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
	$resJCOB 		= $this->db->query($sqlJCOB)->result();
	foreach($resJCOB as $rowJOB) :
		$PR_CODE 	= $rowJOB->PR_CODE;
		$JOBCODE 	= $rowJOB->JOBCODE;
	endforeach;
}
else
{
	$PR_CODE		= $PR_CODE;
	$PR_NUMX		= $PR_NUMX;
	$sqlJCOB		= "SELECT JOBCODE FROM tbl_pr_header WHERE PR_NUM = '$PR_NUMX' AND PRJCODE = '$PRJCODE'";
	$resJCOB 		= $this->db->query($sqlJCOB)->result();
	foreach($resJCOB as $rowJOB) :
		$JOBCODE 	= $rowJOB->JOBCODE;
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

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
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
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
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
			if($TranslCode == 'Request')$Request = $LangTransl;
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
			$PO_RECEIVLOC	= $PR_MEMO;
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
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		            			<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
		                    	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
		                        <input type="hidden" name="JOBCODE" id="JOBCODE" value="<?php echo $JOBCODE; ?>">
		                        <input type="hidden" name="PO_TYPE" id="PO_TYPE" value="<?php echo $PO_TYPE; ?>">
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
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PONumber ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:195px" name="PO_NUM1" id="PO_NUM1" value="<?php echo $DocNumber; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="PO_NUM" id="PO_NUM" size="30" value="<?php echo $DocNumber; ?>" />
		                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="PO_CODE" id="PO_CODE" value="<?php echo $PO_CODE; ?>" >
		                            	<input type="text" class="form-control" name="PO_CODEX" id="PO_CODEX" value="<?php echo $PO_CODE; ?>" disabled >
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
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="PO_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PO_DATE; ?>" style="width:106px">
		                                </div>
		                          	</div>
		                        </div>
		                        <script>
		                            function getPO_NUM(selDate)
		                            {
		                                document.getElementById('PODate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
			
									$(document).ready(function()
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
									});
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Request ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="PR_CODE" id="PR_CODE" style="max-width:160px" value="<?php echo $PR_CODE; ?>" >
		                                    <input type="text" class="form-control" name="PR_NUM1" id="PR_NUM1" value="<?php echo $PR_CODE; ?>" data-toggle="modal" data-target="#mdl_addPR" <?php if($task != 'add') { ?> disabled <?php } ?>>
		                                </div>
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
													document.getElementById('PO_PAYTYPE').value 	= SPLTOP;
													document.getElementById('PO_PAYTYPEX').value 	= SPLTOP;
													document.getElementById('PO_TENOR').value 		= SPLTOPD;
													document.getElementById('PO_TENORX').value 		= SPLTOPD;
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
                                		<input type="hidden" class="form-control" name="PO_PAYTYPE" id="PO_PAYTYPE" size="30" value="<?php echo $PO_PAYTYPE; ?>" />
		                                <select name="PO_PAYTYPEX" id="PO_PAYTYPEX" class="form-control" onChange="selPO_PAYTYPE(this.value)" disabled>
		                                    <option value="0" <?php if($PO_PAYTYPE == 0) { ?> selected="selected" <?php } ?>>Cash</option>
		                                    <option value="1" <?php if($PO_PAYTYPE == 1) { ?> selected="selected" <?php } ?>>Credit</option>
		                                </select>
                                	</div>
                                	<div class="col-sm-5">
                                		<input type="hidden" class="form-control" name="PO_TENOR" id="PO_TENOR" size="30" value="<?php echo $PO_TENOR; ?>" />
		                                <select name="PO_TENORX" id="PO_TENORX" class="form-control" onChange="selPO_TENOR(this.value)" disabled >
		                                    <option value="0" <?php if($PO_TENOR == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($PO_TENOR == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="15" <?php if($PO_TENOR == 15) { ?> selected <?php } ?>>15 Days</option>
		                                    <option value="30" <?php if($PO_TENOR == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($PO_TENOR == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($PO_TENOR == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="75" <?php if($PO_TENOR == 75) { ?> selected <?php } ?>>75 Days</option>
		                                    <option value="90" <?php if($PO_TENOR == 90) { ?> selected <?php } ?>>90 Days</option>
		                                    <option value="120" <?php if($PO_TENOR == 120) { ?> selected <?php } ?>>120 Days</option>
		                                </select>
                                	</div>
		                        </div>
		                        <script>
									function selPO_PAYTYPE(theValue)
									{
										if(theValue == 1)
										{
											document.getElementById('PO_TENOR').value = 0;
										}
										else
										{
											document.getElementById('PO_TENOR').value = 7;
										}
									}
									
									function selPO_TENOR(theValue)
									{
										if(theValue > 0)
										{
											document.getElementById('PO_PAYTYPE').value = 2;
										}
										else
										{
											document.getElementById('PO_PAYTYPE').value = 1;
										}
									}
								</script>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $payMethod; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="PO_PAYNOTES" id="PO_PAYNOTES" value="<?php echo $PO_PAYNOTES; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Currency ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="PO_CURR" id="PO_CURR" class="form-control" style="max-width:75px">
		                                	<option value="IDR" <?php if($PO_CURR == 'IDR') { ?> selected <?php } ?>>IDR</option>
		                                	<option value="USD" <?php if($PO_CURR == 'USD') { ?> selected <?php } ?>>USD</option>    
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ReferenceNumber; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="PO_REFRENS" id="PO_REFRENS" size="30" value="<?php echo $PO_REFRENS; ?>" />
		                            </div>
		                        </div>
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptDate ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="PO_PLANIR" class="form-control pull-left" id="datepicker2" value="<?php echo $PO_PLANIR; ?>" style="width:106px">
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptLoc; ?></label>
		                            <div class="col-sm-9">
		                                <div class="row">
		                                	<div class="col-xs-6">
		                                		<input type="text" class="form-control" name="PO_RECEIVLOC" id="PO_RECEIVLOC" size="30" value="<?php echo $PO_RECEIVLOC; ?>" />
		                                	</div>
		                                	<div class="col-xs-6">
		                                		<input type="text" class="form-control" style="max-width:200px;" name="PO_RECEIVCP" id="PO_RECEIVCP" size="30" value="<?php echo $PO_RECEIVCP; ?>" />
		                                	</div>
		                                </div>
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
		                                <textarea class="form-control" name="PO_NOTES"  id="PO_NOTES" style="height:63px"><?php echo $PO_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div id="revMemo" class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?></label>
		                            <div class="col-sm-9">
		                                <textarea name="PO_NOTES1" class="form-control" id="PO_NOTES1" cols="30" disabled><?php echo $PO_NOTES1; ?></textarea>
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

		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PO_STAT; ?>">
		                                <?php
											$isDisabled = 1;
											if($PO_STAT == 1 || $PO_STAT == 4)
											{
												$isDisabled = 0;
											}
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
														<option value="1"<?php if($PO_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($PO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
														<option value="3"<?php if($PO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
														<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
														<option value="5"<?php if($PO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
														<option value="6"<?php if($PO_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
														<option value="7"<?php if($PO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
														<option value="9"<?php if($PO_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
													<?php
												}
												else
												{
													?>
														<option value="1"<?php if($PO_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($PO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													<?php
												}
		                                    ?>
		                                </select>
		                            </div>
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
										if(selSTAT == 5 || selSTAT == 9)
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
												document.getElementById('btnREJECT').style.display = '';
												document.getElementById('revMemo').style.display = '';
												document.getElementById('PO_NOTES1').disabled = false;
											}
										}
										else if(selSTAT == 6)
										{
											document.getElementById('btnREJECT').style.display = '';
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
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center">No.</th>
                                      	<th width="7%" style="text-align:center; display:none" nowrap><?php echo $ItemCode; ?> </th>
                                      	<th width="25%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemName; ?> </th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Request; ?></th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Remain; ?></th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Quantity; ?> </th> 
                              			<!-- Input Manual -->
                                        <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Unit; ?> </th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" ><?php echo $UnitPrice; ?> </th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?><br>
                                      	(%)</th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?> </th>
                                      	<th width="9%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Tax; ?></th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $TotalPrice; ?></th>
                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Specification; ?></th>
                                  	</tr>
                                    <?php
                                        $resultC	= 0;
                                        if($task == 'add' && $PR_NUMX != '')
                                        {
                                            $sqlDETPR	= "SELECT A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.SNCODE, 
                                                                A.ITM_UNIT, A.PR_VOLM,
                                                                A.PO_VOLM, A.IR_VOLM, A.IR_AMOUNT, 0 AS PO_DISP, 0 AS PO_DISC, 
                                                                A.PO_VOLM AS ORDERED_QTY, 
                                                                A.PR_PRICE AS ITM_PRICE, A.PR_PRICE AS PO_PRICE, A.PR_TOTAL,
                                                                A.PR_DESC AS PO_DESC, A.PR_DESC1 AS PO_DESC1, A.TAXCODE1, 
                                                                A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2, A.PR_ID AS PRD_ID, '' AS JOBPARDESC,
                                                                B.ITM_NAME
                                                            FROM tbl_pr_detail A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                    AND B.PRJCODE = '$PRJCODE'
                                                            WHERE PR_NUM = '$PR_NUMX' 
                                                                AND B.PRJCODE = '$PRJCODE'";
                                            $result 	= $this->db->query($sqlDETPR)->result();
                                            
                                            $sqlDETC	= "tbl_pr_detail A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                    AND B.PRJCODE = '$PRJCODE'
                                                            WHERE PR_NUM = '$PR_NUMX' 
                                                                AND B.PRJCODE = '$PRJCODE'";
                                            $resultC 	= $this->db->count_all($sqlDETC);
                                        }
                                        else
                                        {
                                            if($task == 'edit')
                                            {
                                                //*from data
                                                $sqlDET		= "SELECT A.PO_NUM, A.PO_DATE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, 
                                                                    A.ITM_CODE, B.ITM_NAME, A.ITM_UNIT, 
                                                                    A.PO_PRICE AS ITM_PRICE, A.PR_VOLM, A.PO_VOLM, A.PO_PRICE,
                                                                    A.IR_VOLM, A.IR_AMOUNT, A.PO_DISP, 
                                                                    A.PO_COST, A.PO_DISC, A.PO_DESC,A.PO_DESC1, A.TAXCODE1, 
                                                                    A.TAXCODE2, A.TAXPRICE1,
                                                                    A.TAXPRICE2, A.PRD_ID, A.JOBPARDESC
                                                                FROM tbl_po_detail A
                                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                WHERE 
                                                                    A.PO_NUM = '$PO_NUM' 
                                                                    AND B.PRJCODE = '$PRJCODE'";
                                                $result = $this->db->query($sqlDET)->result();
                                                // count data
                                                $sqlDETC	= "tbl_po_detail A
                                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                WHERE 
                                                                    A.PO_NUM = '$PO_NUM' 
                                                                    AND B.PRJCODE = '$PRJCODE'";
                                                $resultC 	= $this->db->count_all($sqlDETC);
                                            }
                                        }
                                        $i			= 0;
                                        $j			= 0;
                                        if($resultC > 0)
                                        {
                                            $GT_ITMPRICE	= 0;
                                            foreach($result as $row) :														
                                                $PO_NUM 		= $PO_NUM;
                                                $PO_CODE 		= $PO_CODE;
                                                $PRJCODE		= $PRJCODE;
                                                $PR_NUM			= $row->PR_NUM;
                                                $JOBCODEDET		= $row->JOBCODEDET;
                                                $JOBCODEID		= $row->JOBCODEID;
                                                $JOBPARDESC		= $row->JOBPARDESC;
                                                $PRD_ID 		= $row->PRD_ID;
                                                $ITM_CODE 		= $row->ITM_CODE;
                                                $ITM_NAME 		= $row->ITM_NAME;
                                                $ITM_UNIT 		= $row->ITM_UNIT;
                                                $ITM_PRICE 		= $row->ITM_PRICE;
                                                $PR_VOLM 		= $row->PR_VOLM;
                                                $PR_AMOUNT		= $PR_VOLM * $ITM_PRICE;
                                                $PO_VOLM 		= $row->PO_VOLM;
                                                if($PO_VOLM == '')
                                                    $PO_VOLM	= 0;
                                                $PO_PRICE 		= $row->PO_PRICE;
                                                $IR_VOLM 		= $row->IR_VOLM;
                                                $IR_PRICE 		= $row->IR_AMOUNT;
                                                $PO_DISP 		= $row->PO_DISP;
                                                $PO_DISC 		= $row->PO_DISC;
                                                
                                                // GET TOTAL ORDERED
                                                $ORDERED_QTY	= 0;
                                                $sqlTOTORD		= "SELECT SUM(A.PO_VOLM) AS ORDERED_QTY 
                                                                    FROM tbl_po_detail A
                                                                        INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
                                                                    WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEDET = '$JOBCODEDET'
                                                                        AND A.JOBCODEID = '$JOBCODEID'
																		AND A.PO_NUM != '$PO_NUM'
                                                                        AND A.PR_NUM = '$PR_NUM' AND B.PO_STAT IN (2,3,6)
                                                                        AND A.PRD_ID = $PRD_ID";
                                                $resTOTORD		= $this->db->query($sqlTOTORD)->result();
                                                foreach($resTOTORD as $rowTOTORD) :
                                                    $ORDERED_QTY= $rowTOTORD->ORDERED_QTY;
                                                endforeach;

                                                // GET JODESC
                                                $JOBPARENT		= '';
                                                $JOBDESC		= $JOBPARDESC;
		                                        if($JOBPARDESC == '')
		                                        {
	                                                $sqlJPAR	= "SELECT JOBCODEID AS JOBPARENT, A.JOBDESC FROM tbl_joblist_detail A 
																	WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
																		WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')";
	                                                $resJPAR	= $this->db->query($sqlJPAR)->result();
	                                                foreach($resJPAR as $rowJPAR) :
	                                                    $JOBPARENT	= $rowJPAR->JOBDESC;
	                                                    $JOBDESC	= $rowJPAR->JOBDESC;
	                                                endforeach;
		                                        }

                                                /*$ITM_NAME1		= $ITM_NAME;
                                                if($JOBDESC != '' AND $JOBDESC != $ITM_NAME)
                                                	$ITM_NAME1	= "$ITM_NAME - $JOBDESC";*/
                                                    
                                                $PR_DESC		= $row->PO_DESC;
                                                $PO_DES1		= $row->PO_DESC1;
                                                $TAXCODE1		= $row->TAXCODE1;
                                                $TAXCODE2		= $row->TAXCODE2;
                                                $TAXPRICE1		= $row->TAXPRICE1;
                                                $TAXPRICE2		= $row->TAXPRICE2;
                                                $itemConvertion	= 1;
                                                
                                                $ITM_REMAIN		= $PR_VOLM - $ORDERED_QTY;
                                                //echo "$ITM_REMAIN		= $PR_VOLM - $ORDERED_QTY;";
                                                if($task == 'add')
                                                    $PO_VOLM	= $ITM_REMAIN;
                                                else
                                                    $PO_VOLM	= $PO_VOLM;
                                                
                                                $ORDERED_QTY	= $ORDERED_QTY;
                                                if($task == 'add')
                                                    $PO_COST 	= $PO_VOLM * $ITM_PRICE;
                                                else
                                                    $PO_COST 	= $row->PO_COST;			// Non-PPn
                                                
                                                /*if($TAXCODE1 == 'TAX01')
                                                {
                                                    $GT_ITMPRICE= $GT_ITMPRICE + $PO_COST + $TAXPRICE1;
                                                }
                                                if($TAXCODE1 == 'TAX02')
                                                {
                                                    $GT_ITMPRICE= $GT_ITMPRICE + $PO_COST - $TAXPRICE1;
                                                }*/
                                                
                                                $PO_COSTnPPn	= $PO_COST + $TAXPRICE1 - $TAXPRICE2;
                                                if(($ITM_REMAIN > 0 && $task == 'add') || $task == 'edit')
                                                {
                                                	$currentRow  	= ++$i;	
                                                    /*if ($j==1) {
                                                        echo "<tr class=zebra1>";
                                                        $j++;
                                                    } else {
                                                        echo "<tr class=zebra2>";
                                                        $j--;
                                                    }*/
                                                    if($avail_ed == 1)
                                                    {
                                                    ?>
	                                                    <tr id="tr_<?php echo $currentRow; ?>">
		                                                    <!-- NO URUT -->
		                                                    <td style="text-align:center; vertical-align: middle;">
		                                                        <?php
		                                                            if($PO_STAT == 1)
		                                                            {
		                                                                ?>
		                                                                    <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
		                                                                <?php
		                                                            }
		                                                            else
		                                                            {
		                                                                echo "$currentRow.";
		                                                            }
		                                                        ?>
		                                                        <input style="display:none" type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
		                                                        <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
		                                                    </td>
		                                                    <!-- ITEM CODE -->
		                                                    <td style="text-align:left; vertical-align: middle; display: none;">
		                                                        <?php print $ITM_CODE; ?>
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRD_ID" name="data[<?php echo $currentRow; ?>][PRD_ID]" value="<?php echo $PRD_ID; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php print $DocNumber; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PO_CODE" name="data[<?php echo $currentRow; ?>][PO_CODE]" value="<?php print $PO_CODE; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PR_NUM" name="data[<?php echo $currentRow; ?>][PR_NUM]" value="<?php print $PR_NUMX; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php print $JOBCODEDET; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBPARENT" name="data[<?php echo $currentRow; ?>][JOBPARENT]" value="<?php print $JOBPARENT; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBPARDESC" name="data[<?php echo $currentRow; ?>][JOBPARDESC]" value="<?php print $JOBDESC; ?>" width="10" size="15"></td>
		                                                    <!-- ITEM NAME -->
		                                                   <td style="text-align:left; vertical-align: middle;" nowrap>
		                                                    	<?php echo $ITM_CODE." : ".$ITM_NAME; ?>
					                                        	<div style="font-style: italic;">
															  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?=$JOBDESC?>
															  	</div>
		                                                    </td>
		                                                    
		                                                    <!-- ITEM BUDGET -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                    	<?php print number_format($PR_VOLM, $decFormat); ?></td>
		                                                    
		                                                    <!-- ITEM REMAIN -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($ITM_REMAIN, $decFormat); ?>
		                                                        <input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN<?php echo $currentRow; ?>" id="ITM_REMAIN<?php echo $currentRow; ?>" value="<?php echo $ITM_REMAIN; ?>" ></td>
		                                                        
		                                                    <!-- ITEM ORDER NOW -->  
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <input type="text" class="form-control" style="min-width:90px; max-width:300px; text-align:right" name="PO_VOLMx<?php echo $currentRow; ?>" id="PO_VOLMx<?php echo $currentRow; ?>" value="<?php print number_format($PO_VOLM, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> >
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>PR_VOLM" name="data[<?php echo $currentRow; ?>][PR_VOLM]" value="<?php print $PR_VOLM; ?>">
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>PR_AMOUNT" name="data[<?php echo $currentRow; ?>][PR_AMOUNT]" value="<?php print $PR_AMOUNT; ?>">
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>PO_VOLM" name="data[<?php echo $currentRow; ?>][PO_VOLM]" value="<?php print $PO_VOLM; ?>"></td>
		                                                        
		                                                    <!-- ITEM UNIT -->
		                                                    <td style="text-align:center; vertical-align: middle;">
		                                                        <?php print $ITM_UNIT; ?>  
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>"></td>
			                                                    <?php
			                                                        /* Perhitungan ........
			                                                        $totPriceItem	= $PR_VOLM * $CSTPUNT;
			                                                        $totalAmount	= $totalAmount + $totPriceItem;
			                                                        $BtotalAmount	= $BtotalAmount + ($totPriceItem * $PO_CURRATE);
			                                                        End */
			                                                    ?>
		                                                    
		                                                    <!-- ITEM PRICE -->
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                        <input type="text" class="form-control" style="text-align:right; min-width:90px" name="PO_PRICEx<?php echo $currentRow; ?>" id="PO_PRICEx<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);">
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_PRICE]" id="data<?php echo $currentRow; ?>PO_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>"></td>
		                                                     
		                                                    <!-- ITEM DISCOUNT PERCENTATION -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <input type="text" class="form-control" size="10"  style=" min-width:70px; max-width:150px; text-align:right" name="PO_DISPx<?php echo $currentRow; ?>" id="PO_DISPx<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" >
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISP]" id="data<?php echo $currentRow; ?>PO_DISP" value="<?php echo $PO_DISP; ?>"></td>
		                                                        
		                                                    <!-- ITEM DISCOUNT -->
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                        <input type="text" class="form-control" style="min-width:90px; max-width:350px; text-align:right" name="PO_DISC<?php echo $currentRow; ?>" id="PO_DISC<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" >
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISC]" id="data<?php echo $currentRow; ?>PO_DISC" value="<?php echo $PO_DISC; ?>"></td>
		                                                        
		                                                    <!-- ITEM TAX -->
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                      	<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control select2" id="data<?php echo $currentRow; ?>TAXCODE1"  onChange="getValuePO(this, <?php echo $currentRow; ?>);" style="min-width:90px; max-width:150px">
		                                                            <option value="0"> --- </option>
		                                                            <?php
			                                                            $sPPN		= "SELECT TAXLA_NUM, TAXLA_DESC, 'PPN' AS TAXTYPE FROM tbl_tax_ppn
			                                                            				UNION ALL
			                                                            				SELECT TAXLA_NUM, TAXLA_DESC, 'PPH' AS TAXTYPE FROM tbl_tax_la";
					                                                    $rPPN		= $this->db->query($sPPN)->result();
					                                                    foreach($rPPN as $rwPPN) :
					                                                        $TAXLA_NUM	= $rwPPN->TAXLA_NUM;
					                                                        $TAXLA_DESC	= $rwPPN->TAXLA_DESC;
					                                                        $TAXTYPE	= $rwPPN->TAXTYPE;
				                                                            ?>
				                                                            <option value="<?php echo $TAXLA_NUM; ?>" <?php if ($TAXLA_NUM == $TAXCODE1) { ?> selected <?php } ?>><?php echo $TAXLA_DESC; ?></option>
				                                                            <?php
				                                                        endforeach;
				                                                    ?>
		                                                        </select>
		                                                    </td>
			                                                  	<?php
			                                                        //$BTAXPRICE1	= $TAXPRICE1 * $PO_TAXRATE;
			                                                        //$TAXCODE1	= "TAX01";
			                                                        /*if($TAXCODE1 == "TAX01")
			                                                        {
			                                                            $totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICE1;
			                                                            $TAXPRICEPPh1		= 0;
			                                                            $totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICEPPh1;
			                                                        }
			                                                        else
			                                                        {
			                                                            $totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICE1;
			                                                            $TAXPRICEPPn1		= 0;
			                                                            $totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICEPPn1;
			                                                        }*/
			                                                  	?>

		                                                  	<!-- ITEM TOTAL COST -->
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_COST]" id="data<?php echo $currentRow; ?>PO_COST" value="<?php echo $PO_COST; ?>">
		                                                        <input type="text" class="form-control" style="min-width:110px; max-width:350px; text-align:right" name="PO_COSTnPPn<?php echo $currentRow; ?>" id="PO_COSTnPPn<?php echo $currentRow; ?>" value="<?php print number_format($PO_COSTnPPn, $decFormat); ?>" size="5" >
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>"></td>
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                        <input class="form-control" style="min-width:130px;text-align:left" type="text" name="data[<?php echo $currentRow; ?>][PO_DESC]" id="data<?php echo $currentRow; ?>PO_DESC" value="<?php echo $PR_DESC; ?>" size="5" > </td>
                                                		</tr>
                                            		<?php
                                            		}
                                            		else
                                            		{
                                                    ?>
	                                                    <tr id="tr_<?php echo $currentRow; ?>">
		                                                    <!-- NO URUT -->
		                                                    <td style="text-align:center; vertical-align: middle;">
		                                                        <?php
		                                                            if($PO_STAT == 1)
		                                                            {
		                                                                ?>
		                                                                    <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
		                                                                <?php
		                                                            }
		                                                            else
		                                                            {
		                                                                echo "$currentRow.";
		                                                            }
		                                                        ?>
		                                                        <input style="display:none" type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
		                                                        <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       			</td>
		                                                    <!-- ITEM CODE -->
		                                                    <td style="text-align:left; vertical-align: middle; display: none;">
		                                                        <?php print $ITM_CODE; ?>
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRD_ID" name="data[<?php echo $currentRow; ?>][PRD_ID]" value="<?php echo $PRD_ID; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php print $DocNumber; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PO_CODE" name="data[<?php echo $currentRow; ?>][PO_CODE]" value="<?php print $PO_CODE; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PR_NUM" name="data[<?php echo $currentRow; ?>][PR_NUM]" value="<?php print $PR_NUMX; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php print $JOBCODEDET; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBPARENT" name="data[<?php echo $currentRow; ?>][JOBPARENT]" value="<?php print $JOBPARENT; ?>" width="10" size="15">
		                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBPARDESC" name="data[<?php echo $currentRow; ?>][JOBPARDESC]" value="<?php print $JOBDESC; ?>" width="10" size="15"></td>
		                                                    <!-- ITEM NAME -->
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                    	<?php echo $ITM_CODE." : ".$ITM_NAME; ?>
					                                        	<div style="font-style: italic;">
															  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?=$JOBDESC?>
															  	</div>
															</td>
		                                                    
		                                                    <!-- ITEM BUDGET -->
		                                                   	<td style="text-align:right; vertical-align: middle;">
		                                                   		<?php print number_format($PR_VOLM, $decFormat); ?></td>
		                                                    
		                                                    <!-- ITEM REMAIN -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($ITM_REMAIN, $decFormat); ?>
		                                                        <input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN<?php echo $currentRow; ?>" id="ITM_REMAIN<?php echo $currentRow; ?>" value="<?php echo $ITM_REMAIN; ?>" ></td>
		                                                        
		                                                    <!-- ITEM ORDER NOW -->  
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($PO_VOLM, $decFormat); ?>
		                                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="PO_VOLMx<?php echo $currentRow; ?>" id="PO_VOLMx<?php echo $currentRow; ?>" value="<?php print number_format($PO_VOLM, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> >
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>PR_VOLM" name="data[<?php echo $currentRow; ?>][PR_VOLM]" value="<?php print $PR_VOLM; ?>">
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>PR_AMOUNT" name="data[<?php echo $currentRow; ?>][PR_AMOUNT]" value="<?php print $PR_AMOUNT; ?>">
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>PO_VOLM" name="data[<?php echo $currentRow; ?>][PO_VOLM]" value="<?php print $PO_VOLM; ?>">
		                                                     </td>
		                                                        
		                                                    <!-- ITEM UNIT -->
		                                                    <td style="text-align:center; vertical-align: middle;">
		                                                        <?php print $ITM_UNIT; ?>  
		                                                         <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>"></td>
		                                                    <?php
		                                                        /* Perhitungan ........
		                                                        $totPriceItem	= $PR_VOLM * $CSTPUNT;
		                                                        $totalAmount	= $totalAmount + $totPriceItem;
		                                                        $BtotalAmount	= $BtotalAmount + ($totPriceItem * $PO_CURRATE);
		                                                        End */
		                                                    ?>
		                                                    
		                                                    <!-- ITEM PRICE -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($ITM_PRICE, $decFormat); ?>
		                                                        <input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="PO_PRICEx<?php echo $currentRow; ?>" id="PO_PRICEx<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);">
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_PRICE]" id="data<?php echo $currentRow; ?>PO_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>"></td>
		                                                     
		                                                    <!-- ITEM DISCOUNT PERCENTATION -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($PO_DISP, $decFormat); ?>
		                                                        <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="PO_DISPx<?php echo $currentRow; ?>" id="PO_DISPx<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" >
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISP]" id="data<?php echo $currentRow; ?>PO_DISP" value="<?php echo $PO_DISP; ?>"></td>
		                                                        
		                                                    <!-- ITEM DISCOUNT -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($PO_DISC, $decFormat); ?>
		                                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="PO_DISC<?php echo $currentRow; ?>" id="PO_DISC<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" >
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISC]" id="data<?php echo $currentRow; ?>PO_DISC" value="<?php echo $PO_DISC; ?>"></td>
		                                                        
		                                                    <!-- ITEM TAX -->
		                                                    <td style="text-align:center; vertical-align: middle;">
		                                                    	<?php
		                                                    		if($PO_STAT == 1 || $PO_STAT == 4)
		                                                    		{
		                                                    			?>
					                                                      	<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control" id="data<?php echo $currentRow; ?>TAXCODE1"  onChange="getValuePO(this, <?php echo $currentRow; ?>);" style="min-width:90px; max-width:150px;">
					                                                            <option value="0"> --- </option>
					                                                            <?php
						                                                            $sPPN		= "SELECT TAXLA_NUM, TAXLA_CODE, TAXLA_DESC FROM tbl_tax_ppn";
								                                                    $rPPN		= $this->db->query($sPPN)->result();
								                                                    foreach($rPPN as $rwPPN) :
								                                                        $TAXLA_NUM	= $rwPPN->TAXLA_NUM;
								                                                        $TAXLA_DESC	= $rwPPN->TAXLA_DESC;
							                                                            ?>
							                                                            <option value="<?php echo $TAXLA_NUM; ?>" <?php if ($TAXCODE1 == $TAXLA_NUM) { ?> selected <?php } ?>><?php echo $TAXLA_DESC; ?></option>
							                                                            <?php
							                                                        endforeach;
							                                                    ?>
					                                                        </select>
					                                                    <?php
					                                                }
					                                                else
					                                                {
					                                                	if($TAXCODE1 != '')
					                                                	{
					                                                		$TAXLA_DESC = "-";
					                                                		$sPPN		= "SELECT TAXLA_NUM, TAXLA_CODE, TAXLA_DESC
					                                                						FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
						                                                    $rPPN		= $this->db->query($sPPN)->result();
						                                                    foreach($rPPN as $rwPPN) :
						                                                        $TAXLA_NUM	= $rwPPN->TAXLA_NUM;
						                                                        $TAXLA_DESC	= $rwPPN->TAXLA_DESC;
					                                                        endforeach;
					                                                        echo $TAXLA_DESC;
					                                                	}
					                                                	else
					                                                	{
					                                                		echo "-";
					                                                	}
					                                                	?>
																			<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" value="<?php echo $TAXCODE1; ?>">
					                                                	<?php
					                                                }
					                                            ?>
		                                                    </td>
			                                                  	<?php
			                                                        //$BTAXPRICE1	= $TAXPRICE1 * $PO_TAXRATE;
			                                                        //$TAXCODE1	= "TAX01";
			                                                        /*if($TAXCODE1 == "TAX01")
			                                                        {
			                                                            $totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICE1;
			                                                            $TAXPRICEPPh1		= 0;
			                                                            $totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICEPPh1;
			                                                        }
			                                                        else
			                                                        {
			                                                            $totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICE1;
			                                                            $TAXPRICEPPn1		= 0;
			                                                            $totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICEPPn1;
			                                                        }*/
			                                                  	?>
		                                                  	<!-- ITEM TOTAL COST -->
		                                                    <td style="text-align:right; vertical-align: middle;">
		                                                        <?php print number_format($PO_COST, $decFormat); ?>
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_COST]" id="data<?php echo $currentRow; ?>PO_COST" value="<?php echo $PO_COST; ?>">
		                                                        <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="PO_COSTnPPn<?php echo $currentRow; ?>" id="PO_COSTnPPn<?php echo $currentRow; ?>" value="<?php print number_format($PO_COSTnPPn, $decFormat); ?>" size="5" >
		                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
		                                                    </td>
		                                                    <td style="text-align:left; vertical-align: middle;">
		                                                        <?php print $PR_DESC; ?>
		                                                        <input class="form-control" style="min-width:130px;text-align:left; display: none;" type="text" name="data[<?php echo $currentRow; ?>][PO_DESC]" id="data<?php echo $currentRow; ?>PO_DESC" value="<?php echo $PR_DESC; ?>" size="5" ></td>
                                                		</tr>
                                            		<?php
                                            		}
                                            	}
                                            endforeach;
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <?php
                                        }

                                        if($task == 'add')
                                        {
                                        	?>
                                        	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                        	<?php
                                        }
                                    ?>
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
													<button class="btn btn-primary" id="btnREJECT" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											elseif($PO_STAT == 3 && $ISDELETE == 1)
											{
												?>
													<button class="btn btn-primary" id="btnREJECT" style="display:none" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" id="btnREJECT" style="display:none" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
									}
									$backURL	= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
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
	                            <?php
									$SHOWOTH		= 0;
									$AH_ISLAST		= 0;
									$APPROVER_1A	= 0;
									$APPROVER_2A	= 0;
									$APPROVER_3A	= 0;
									$APPROVER_4A	= 0;
									$APPROVER_5A	= 0;
	                                if($APPROVER_1 != '')
	                                {
	                                    $boxCol_1	= "red";
	                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
	                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
	                                    if($resCAPPH_1 > 0)
	                                    {
	                                        $boxCol_1	= "green";
	                                        $Approver	= $Approved;
	                                        $class		= "glyphicon glyphicon-ok-sign";
	                                        
	                                        $sqlAPPH_1	= "SELECT AH_APPROVED, AH_ISLAST 
															FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
	                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
	                                        foreach($resAPPH_1 as $rowAPPH_1):
	                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
	                                            $AH_ISLAST	= $rowAPPH_1->AH_ISLAST;
	                                        endforeach;
	                                    }
	                                    elseif($resCAPPH_1 == 0)
	                                    {
											$Approver	= $NotYetApproved;
											$class		= "glyphicon glyphicon-remove-sign";
											$APPROVED_1	= "Not Set";
											
											$sqlCAPPH_1A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
											$resCAPPH_1A	= $this->db->count_all($sqlCAPPH_1A);
											if($resCAPPH_1A > 0)
											{
												$SHOWOTH	= 1;
												$APPROVER_1A= 1;
												$EMPN_1A	= '';
												$AH_ISLAST1A=0;
												$APPROVED_1A= '0000-00-00';
												$boxCol_1A	= "green";
												$Approver1A	= $Approved;
												$class1A	= "glyphicon glyphicon-ok-sign";
												
												$sqlAPPH_1A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																	CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																FROM tbl_approve_hist A 
																	INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
												$resAPPH_1A	= $this->db->query($sqlAPPH_1A)->result();
												foreach($resAPPH_1A as $rowAPPH_1A):
													$EMPN_1A		= $rowAPPH_1A->COMPNAME;
													$AH_ISLAST1A	= $rowAPPH_1A->AH_ISLAST;
													$APPROVED_1A	= $rowAPPH_1A->AH_APPROVED;
												endforeach;
											}
	                                    }
										?>
											<div class="col-md-3">
												<div class="info-box bg-<?php echo $boxCol_1; ?>">
													<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
													<div class="info-box-content">

														<span class="info-box-text"><?php echo $Approver; ?></span>
														<span class="info-box-number"><?php echo cut_text ("$EMPN_1", 20); ?></span>
														<div class="progress">
															<div class="progress-bar" style="width: 50%"></div>
														</div>
														<span class="progress-description">
															<?php echo $APPROVED_1; ?>
														</span>
													</div>
												</div>
											</div>
										<?php
	                                }
	                                if($APPROVER_2 != '' && $AH_ISLAST == 0)
	                                {
	                                    $boxCol_2	= "red";
	                                    $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
	                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
	                                    if($resCAPPH_2 > 0)
	                                    {
	                                        $boxCol_2	= "green";
	                                        $class		= "glyphicon glyphicon-ok-sign";
	                                        
	                                        $sqlAPPH_2	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
	                                        $resAPPH_2	= $this->db->query($sqlAPPH_2)->result();
	                                        foreach($resAPPH_2 as $rowAPPH_2):
	                                            $APPROVED_2	= $rowAPPH_2->AH_APPROVED;
	                                        endforeach;
	                                    }
	                                    elseif($resCAPPH_2 == 0)
	                                    {
	                                        $Approver	= $NotYetApproved;
	                                        $class		= "glyphicon glyphicon-remove-sign";
	                                        $APPROVED_2	= "Not Set";
											
											$sqlCAPPH_2A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
											$resCAPPH_2A	= $this->db->count_all($sqlCAPPH_2A);
											if($resCAPPH_2A > 0)
											{
												$APPROVER_2A= 1;
												$EMPN_2A	= '';
												$AH_ISLAST2A=0;
												$APPROVED_2A= '0000-00-00';
												$boxCol_2A	= "green";
												$Approver2A	= $Approved;
												$class2A	= "glyphicon glyphicon-ok-sign";
												
												$sqlAPPH_2A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																	CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																FROM tbl_approve_hist A 
																	INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
												$resAPPH_2A	= $this->db->query($sqlAPPH_2A)->result();
												foreach($resAPPH_2A as $rowAPPH_2A):
													$EMPN_2A		= $rowAPPH_2A->COMPNAME;
													$AH_ISLAST2A	= $rowAPPH_2A->AH_ISLAST;
													$APPROVED_2A	= $rowAPPH_2A->AH_APPROVED;
												endforeach;
											}
										}
	                                    
	                                    /*if($resCAPPH == 0)
	                                    {
	                                        $Approver	= $Awaiting;
	                                        $boxCol_2	= "yellow";
	                                        $class		= "glyphicon glyphicon-info-sign";
	                                    }*/
										?>
											<div class="col-md-3">
												<div class="info-box bg-<?php echo $boxCol_2; ?>">
													<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
													<div class="info-box-content">
														<span class="info-box-text"><?php echo $Approver; ?></span>
														<span class="info-box-number"><?php echo cut_text ("$EMPN_2", 20); ?></span>
														<div class="progress">
															<div class="progress-bar" style="width: 50%"></div>
														</div>
														<span class="progress-description">
															<?php echo $APPROVED_2; ?>
														</span>
													</div>
												</div>
											</div>
										<?php
	                                }
	                                if($APPROVER_3 != '' && $AH_ISLAST == 0)
	                                {
	                                    $boxCol_3	= "red";
	                                    $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
	                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
	                                    if($resCAPPH_3 > 0)
	                                    {
	                                        $boxCol_3	= "green";
	                                        $class		= "glyphicon glyphicon-ok-sign";
	                                        
	                                        $sqlAPPH_3	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
	                                        $resAPPH_3	= $this->db->query($sqlAPPH_3)->result();
	                                        foreach($resAPPH_3 as $rowAPPH_3):
	                                            $APPROVED_3	= $rowAPPH_3->AH_APPROVED;
	                                        endforeach;
	                                    }
	                                    elseif($resCAPPH_3 == 0)
	                                    {
	                                        $Approver	= $NotYetApproved;
	                                        $class		= "glyphicon glyphicon-remove-sign";
	                                        $APPROVED_3	= "Not Set";
											
											$sqlCAPPH_3A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
											$resCAPPH_3A	= $this->db->count_all($sqlCAPPH_3A);
											if($resCAPPH_3A > 0)
											{
												$APPROVER_3A= 1;
												$EMPN_3A	= '';
												$AH_ISLAST3A=0;
												$APPROVED_3A= '0000-00-00';
												$boxCol_3A	= "green";
												$Approver3A	= $Approved;
												$class3A	= "glyphicon glyphicon-ok-sign";
												
												$sqlAPPH_3A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																	CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																FROM tbl_approve_hist A 
																	INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
												$resAPPH_3A	= $this->db->query($sqlAPPH_3A)->result();
												foreach($resAPPH_3A as $rowAPPH_3A):
													$EMPN_3A		= $rowAPPH_3A->COMPNAME;
													$AH_ISLAST3A	= $rowAPPH_3A->AH_ISLAST;
													$APPROVED_3A	= $rowAPPH_3A->AH_APPROVED;
												endforeach;
											}
	                                    }
										?>
											<div class="col-md-3">
												<div class="info-box bg-<?php echo $boxCol_3; ?>">
													<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
													<div class="info-box-content">
														<span class="info-box-text"><?php echo $Approver; ?></span>
														<span class="info-box-number"><?php echo cut_text ("$EMPN_3", 20); ?></span>
														<div class="progress">
															<div class="progress-bar" style="width: 50%"></div>
														</div>
														<span class="progress-description">
															<?php echo $APPROVED_3; ?>
														</span>
													</div>
												</div>
											</div>
										<?php
	                                }
	                                if($APPROVER_4 != '' && $AH_ISLAST == 0)
	                                {
	                                    $boxCol_4	= "red";
	                                    $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
	                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
	                                    if($resCAPPH_4 > 0)
	                                    {
	                                        $boxCol_4	= "green";
	                                        $class		= "glyphicon glyphicon-ok-sign";
	                                        
	                                        $sqlAPPH_4	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
	                                        $resAPPH_4	= $this->db->query($sqlAPPH_4)->result();
	                                        foreach($resAPPH_4 as $rowAPPH_4):
	                                            $APPROVED_4	= $rowAPPH_4->AH_APPROVED;
	                                        endforeach;
	                                    }
	                                    elseif($resCAPPH_4 == 0)
	                                    {
	                                        $Approver	= $NotYetApproved;
	                                        $class		= "glyphicon glyphicon-remove-sign";
	                                        $APPROVED_4	= "Not Set";
											
											$sqlCAPPH_4A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
											$resCAPPH_4A	= $this->db->count_all($sqlCAPPH_4A);
											if($resCAPPH_4A > 0)
											{
												$APPROVER_4A= 1;
												$EMPN_4A	= '';
												$AH_ISLAST4A=0;
												$APPROVED_4A= '0000-00-00';
												$boxCol_4A	= "green";
												$Approver4A	= $Approved;
												$class4A	= "glyphicon glyphicon-ok-sign";
												
												$sqlAPPH_4A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																	CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																FROM tbl_approve_hist A 
																	INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
												$resAPPH_4A	= $this->db->query($sqlAPPH_4A)->result();
												foreach($resAPPH_4A as $rowAPPH_4A):
													$EMPN_4A		= $rowAPPH_4A->COMPNAME;
													$AH_ISLAST4A	= $rowAPPH_4A->AH_ISLAST;
													$APPROVED_4A	= $rowAPPH_4A->AH_APPROVED;
												endforeach;
											}
	                                    }
										?>
											<div class="col-md-3">
												<div class="info-box bg-<?php echo $boxCol_4; ?>">
													<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
													<div class="info-box-content">
														<span class="info-box-text"><?php echo $Approver; ?></span>
														<span class="info-box-number"><?php echo cut_text ("$EMPN_4", 20); ?></span>
														<div class="progress">
															<div class="progress-bar" style="width: 50%"></div>
														</div>
														<span class="progress-description">
															<?php echo $APPROVED_4; ?>
														</span>
													</div>
												</div>
											</div>
										<?php
	                                }
	                                if($APPROVER_5 != '' && $AH_ISLAST == 0)
	                                {
	                                    $boxCol_5	= "red";
	                                    $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
	                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
	                                    if($resCAPPH_5 > 0)
	                                    {
	                                        $boxCol_5	= "green";
	                                        $class		= "glyphicon glyphicon-ok-sign";
	                                        
	                                        $sqlAPPH_5	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
	                                        $resAPPH_5	= $this->db->query($sqlAPPH_5)->result();
	                                        foreach($resAPPH_5 as $rowAPPH_5):
	                                            $APPROVED_5	= $rowAPPH_5->AH_APPROVED;
	                                        endforeach;
	                                    }
	                                    elseif($resCAPPH_5 == 0)
	                                    {
	                                        $Approver	= $NotYetApproved;
	                                        $class		= "glyphicon glyphicon-remove-sign";
	                                        $APPROVED_5	= "Not Set";
											
											$sqlCAPPH_5A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
											$resCAPPH_5A	= $this->db->count_all($sqlCAPPH_5A);
											if($resCAPPH_5A > 0)
											{
												$APPROVER_5A= 1;
												$EMPN_5A	= '';
												$AH_ISLAST5A=0;
												$APPROVED_5A= '0000-00-00';
												$boxCol_5A	= "green";
												$Approver5A	= $Approved;
												$class5A	= "glyphicon glyphicon-ok-sign";
												
												$sqlAPPH_5A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																	CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																FROM tbl_approve_hist A 
																	INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
												$resAPPH_5A	= $this->db->query($sqlAPPH_5A)->result();
												foreach($resAPPH_5A as $rowAPPH_5A):
													$EMPN_5A		= $rowAPPH_5A->COMPNAME;
													$AH_ISLAST5A	= $rowAPPH_5A->AH_ISLAST;
													$APPROVED_5A	= $rowAPPH_5A->AH_APPROVED;
												endforeach;
											}
	                                    }
										?>
											<div class="col-md-3">
												<div class="info-box bg-<?php echo $boxCol_5; ?>">
													<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
													<div class="info-box-content">
														<span class="info-box-text"><?php echo $Approver; ?></span>
														<span class="info-box-number"><?php echo cut_text ("$EMPN_5", 20); ?></span>
														<div class="progress">
															<div class="progress-bar" style="width: 50%"></div>
														</div>
														<span class="progress-description">
															<?php echo $APPROVED_5; ?>
														</span>
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
	                <?php if($SHOWOTH == 1) { ?>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <div class="box box-danger collapsed-box">
	                                <div class="box-header with-border">
	                                    <h3 class="box-title"><?php echo $InOthSett; ?></h3>
	                                    <div class="box-tools pull-right">
	                                        <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
	                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                                        </button>
	                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
	                                        </button>
	                                    </div>
	                                </div>
	                                <div class="box-body">
	                                <?php
	                                    if($APPROVER_1A == 1)
	                                    {
	                                        ?>
	                                            <div class="col-md-3">
	                                                <div class="info-box bg-<?php echo $boxCol_1A; ?>">
	                                                    <span class="info-box-icon"><i class="<?php echo $class1A; ?>"></i></span>
	                                                    <div class="info-box-content">
	                                                        <span class="info-box-text"><?php echo $Approver1A; ?></span>
	                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_1A", 20); ?></span>
	                                                        <div class="progress">
	                                                            <div class="progress-bar" style="width: 50%"></div>
	                                                        </div>
	                                                        <span class="progress-description">
	                                                            <?php echo $APPROVED_1A; ?>
	                                                        </span>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        <?php
	                                    }
	                                    if($APPROVER_2A == 1)
	                                    {
	                                        ?>
	                                            <div class="col-md-3">
	                                                <div class="info-box bg-<?php echo $boxCol_2A; ?>">
	                                                    <span class="info-box-icon"><i class="<?php echo $class2A; ?>"></i></span>
	                                                    <div class="info-box-content">
	                                                        <span class="info-box-text"><?php echo $Approver2A; ?></span>
	                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_2A", 20); ?></span>
	                                                        <div class="progress">
	                                                            <div class="progress-bar" style="width: 50%"></div>
	                                                        </div>
	                                                        <span class="progress-description">
	                                                            <?php echo $APPROVED_2A; ?>
	                                                        </span>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        <?php
	                                    }
	                                    if($APPROVER_3A == 1)
	                                    {
	                                        ?>
	                                            <div class="col-md-3">
	                                                <div class="info-box bg-<?php echo $boxCol_3A; ?>">
	                                                    <span class="info-box-icon"><i class="<?php echo $class3A; ?>"></i></span>
	                                                    <div class="info-box-content">
	                                                        <span class="info-box-text"><?php echo $Approver3A; ?></span>
	                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_3A", 20); ?></span>
	                                                        <div class="progress">
	                                                            <div class="progress-bar" style="width: 50%"></div>
	                                                        </div>
	                                                        <span class="progress-description">
	                                                            <?php echo $APPROVED_3A; ?>
	                                                        </span>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        <?php
	                                    }
	                                    if($APPROVER_4A == 1)
	                                    {
	                                        ?>
	                                            <div class="col-md-3">
	                                                <div class="info-box bg-<?php echo $boxCol_4A; ?>">
	                                                    <span class="info-box-icon"><i class="<?php echo $class4A; ?>"></i></span>
	                                                    <div class="info-box-content">
	                                                        <span class="info-box-text"><?php echo $Approver4A; ?></span>
	                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_4A", 20); ?></span>
	                                                        <div class="progress">
	                                                            <div class="progress-bar" style="width: 50%"></div>
	                                                        </div>
	                                                        <span class="progress-description">
	                                                            <?php echo $APPROVED_4A; ?>
	                                                        </span>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        <?php
	                                    }
	                                    if($APPROVER_5A == 1)
	                                    {
	                                        ?>
	                                            <div class="col-md-3">
	                                                <div class="info-box bg-<?php echo $boxCol_5A; ?>">
	                                                    <span class="info-box-icon"><i class="<?php echo $class5A; ?>"></i></span>
	                                                    <div class="info-box-content">
	                                                        <span class="info-box-text"><?php echo $Approver5A; ?></span>
	                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_5A", 20); ?></span>
	                                                        <div class="progress">
	                                                            <div class="progress-bar" style="width: 50%"></div>
	                                                        </div>
	                                                        <span class="progress-description">
	                                                            <?php echo $APPROVED_5A; ?>
	                                                        </span>
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
	                <?php } ?>
		        </div>
		    </div>

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
																<th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $DateRequest; ?></th>
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
	                                            </form>
	                                      	</div>
		                                </div>
		                            </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

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
											{ "width": "100px", "targets": [1] }
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
									    .val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

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
	    $('#datepicker1').datepicker({
			autoclose: true,
			endDate: '+1d'
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
});
  
	<?php
	if($task == 'add')
	{
		?>
		$(document).ready(function()
		{
			setInterval(function(){getNewCode()}, 1000);
		});
		
		function getNewCode()
		{
			var	PRJCODE		= '<?php echo $dataColl; ?>';
			var isManual	= document.getElementById('isManual').checked;
			
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
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = xmlhttpTask.responseText;
					}
					else
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = '';
					}
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE,true);
			xmlhttpTask.send();
		}
		<?php
	}
	?>
  
	var decFormat		= 2;
	
	function getValuePO(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		//tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		//ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);
		//PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);
		
		//var PO_VOLM 		= eval(thisVal).value.split(",").join("");
		PO_VOLM1			= document.getElementById('PO_VOLMx'+row);
		PO_VOLM 			= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
		
		ITM_REMAIN			= parseFloat(document.getElementById('ITM_REMAIN'+row).value);
		if(PO_VOLM > ITM_REMAIN)
		{
			swal('<?php echo $OrdMTReq; ?>',
			{
				icon: "warning",
			});
			document.getElementById('PO_VOLMx'+row).focus();
			document.getElementById('PO_VOLMx'+row).value = ITM_REMAIN;
			return false;
		}
		
		var PO_VOLM 		= parseFloat(document.getElementById('PO_VOLMx'+row).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_VOLM').value 	= parseFloat(Math.abs(PO_VOLM));
		document.getElementById('PO_VOLMx'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)),decFormat));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEx'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
		document.getElementById('PO_PRICEx'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
		
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
		document.getElementById('PO_COSTnPPn'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COSTnPPn)),decFormat));*/
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICP				= document.getElementById('PO_DISPx'+row);
		PO_DISP				= parseFloat(eval(valDICP).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_DISP').value 	= parseFloat(Math.abs(PO_DISP));
		document.getElementById('PO_DISPx'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISP)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('PO_VOLMx'+row);
		PO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEx'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(PO_VOLM * PO_PRICE);
		DISCOUNT			= parseFloat(PO_DISP * ITMPRICE_TEMP / 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('PO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));

		
		getValuePO(thisVal, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICC				= document.getElementById('PO_DISC'+row);
		PO_DISC				= parseFloat(eval(valDICC).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		document.getElementById('PO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISC)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('PO_VOLMx'+row);
		PO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// PO_PRICE
		thisValPRC			= document.getElementById('PO_PRICEx'+row);
		PO_PRICE			= parseFloat(eval(thisValPRC).value.split(",").join(""));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(PO_VOLM * PO_PRICE);
		DISCOUNTP			= parseFloat(PO_DISC / ITMPRICE_TEMP * 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'PO_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('PO_DISPx'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		getValuePO(thisVal, row)
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
		if(totRow > 0)
		{
			for(i=1;i<=totRow;i++)
			{
				var PO_VOLM = parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
				if(PO_VOLM == 0)
				{
					swal("<?php echo $alert1; ?>",
					{
						icon: "warning",
					});
					document.getElementById('PO_VOLMx'+i).focus();
					return false;	
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
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function add_item(strItem) 
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