<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 11 November 2017
	* File Name	= v_pinv_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$TTK_CODE		= '';
$INV_ACC_OTH	= '';
$TTK_CATEG		= 'IR';
$currentRow 	= 0;
if($task == 'add')
{
	$INV_TYPE 			= 0;
	$Ref_Number 		= '';
	$PONumberRef		= '';
	$INVTaxType 		= 2;
	$PRJCODE 			= $PRJCODE;
	$SPLCODE 			= '';
	$SPLDESC 			= '';
	$SPLADD1 			= '';
	$SPLADD1 			= '';
	$Currency_ID 		= 'IDR';
	$Currency_Rate 		= 1;
	$INV_TAXCURR 	= 'IDR';
	$Tax_Currency_Rate 	= 1;
	$INV_NOTES 			= '';
	$INV_NOTES1			= '';
	$INV_STATUS 		= 1;
	$Payment_Tenor 		= 30;
	$Approval_Status 	= 1;
	$totalAmount 		= '0.00';
	$BtotalAmount 		= '0.00';
	$totalDiscAmount 	= '0.00';
	$totalAmountAfDisc 	= '0.00';
	$totTaxPPnAmount 	= '0.00';
	$totTaxPPhAmount 	= '0.00';
	$GtotalAmount 		= '0.00';
	$Base_TotalPrice	= '0.00';
	$totAkumRow 		= '0.00';
	$TotalPrice 		= '0.00';					
	$proj_Number  		= '';
	$Memo_Revisi 		= '';
	$WH_ID 				= 0;
	$RRSource			= '';
	$mySPLCODE			= '';
	foreach($viewDocPattern as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length = $row->Pattern_Length;
		$useYear = $row->useYear;
		$useMonth = $row->useMonth;
		$useDate = $row->useDate;
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
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_pinv_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pinv_header
			WHERE Patt_Year = $year";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	//echo $pattMonth;
	//echo "&nbsp;";
	//echo $pattDate;
	
	// group year, month and date
	$yearG = date('y');
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$yearG$pattMonth$pattDate";
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
	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
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
	if($INV_TYPE == 1) 
	{
		$INVType = '01';
	}
	else if($INV_TYPE == 2)
	{
		$INVType = '02';
	}
	
	//$DocNumber = "$Pattern_Code$groupPattern$INVType-$lastPatternNumb";
	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$INV_NUM	= $DocNumber;
	$INV_CODE	= $lastPatternNumb;
	
	$VOCCODE		= substr($lastPatternNumb, -4);
	$VOCYEAR		= date('y');
	$VOCMONTH		= date('m');
	$INV_CODE		= "VOC.$VOCCODE.$VOCYEAR.$VOCMONTH"; // MANUAL CODE
	
	$INV_DATEY 	= date('Y');
	$INV_DATEM 	= date('m');
	$INV_DATED 	= date('d');
	$INV_DATE 	= "$INV_DATEM/$INV_DATED/$INV_DATEY";
	$INV_DATE 	= date('d/m/Y');

	//$ETD = $INV_DATE;
	$Patt_Year = date('Y');
	$INV_DUEDATE = $INV_DATE;

	if(isset($_POST['submitSrch1']))
	{
		$mySPLCODE 		= $_POST['SPLCODE1'];
		$SPLCODE		= $mySPLCODE;
	}
	else
	{
		$mySPLCODE		= '';
		$SPLCODE		= $mySPLCODE;
	}
	$INV_STAT			= 1;
	$INV_TERM			= 30;
	$VENDINV_NUM		= '';
	$PO_NUM				= '';
	$TTK_NUM1			= '';
	$INV_AMOUNT 		= 0;
	$INV_AMOUNTX		= 0;
	$INV_AMOUNT_BASE	= 0;
	$INV_AMOUNT_RET		= 0;
	$INV_AMOUNT_POT		= 0;
	$INV_AMOUNT_OTH		= 0;
	$INV_LISTTAX		= 0;
	$INV_LISTTAXVAL		= 0;
	$INV_PPH			= '';
	$INV_PPHVAL			= 0;
	$DP_NUM				= '';
	$DP_AMOUNT			= 0;
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_pinv_header~$Pattern_Length";
	$dataTarget		= "INV_CODE";
}
else
{
	$isSetDocNo = 1;
	$INV_NUM 			= $default['INV_NUM'];
	$DocNumber 			= $default['INV_NUM'];
	$INV_CODE 			= $default['INV_CODE'];
	$INV_TYPE 			= $default['INV_TYPE'];
	$PO_NUM 			= $default['PO_NUM'];
	$IR_NUM 			= $default['IR_NUM'];
	$TTK_NUM1			= $IR_NUM;

	$TTK_CODE			= '';
	$INV_ACC_OTH		= '';
	$sqlTTK				= "SELECT TTK_CATEG, TTK_CODE, TTK_ACC_OTH
						FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM1' AND PRJCODE = '$PRJCODE' LIMIT 1";
	$resTTK	= $this->db->query($sqlTTK)->result();
	foreach($resTTK as $rowTTK):
		$TTK_CATEG		= $rowTTK->TTK_CATEG;
		$TTK_CODE		= $rowTTK->TTK_CODE;
		$INV_ACC_OTH	= $rowTTK->TTK_ACC_OTH;
	endforeach;

	$PRJCODE 			= $default['PRJCODE'];
	$INV_DATE1 			= $default['INV_DATE'];
	$INV_DATE			= date('d/m/Y', strtotime($INV_DATE1));
	$INV_DUEDATE1 		= $default['INV_DUEDATE'];
	$INV_DUEDATE		= date('d/m/Y', strtotime($INV_DUEDATE1));
	$SPLCODE 			= $default['SPLCODE'];
	$SPLCODE1 			= $default['SPLCODE'];
	$DP_NUM				= $default['DP_NUM'];
	$DP_AMOUNT			= $default['DP_AMOUNT'];
	$INV_CURRENCY		= $default['INV_CURRENCY'];
	$INV_TAXCURR	 	= $default['INV_TAXCURR'];
	$INV_AMOUNT1 		= $default['INV_AMOUNT'];
	$INV_AMOUNT_BASE	= $default['INV_AMOUNT_BASE'];
	$INV_AMOUNT_RET		= $default['INV_AMOUNT_RET'];
	$INV_AMOUNT_POT		= $default['INV_AMOUNT_POT'];
	$INV_AMOUNT_OTH		= $default['INV_AMOUNT_OTH'];
	$INV_LISTTAX		= $default['INV_LISTTAX'];
	$INV_LISTTAXVAL		= $default['INV_LISTTAXVAL'];
	$INV_PPH			= $default['INV_PPH'];
	$INV_PPHVAL			= $default['INV_PPHVAL'];
	$INV_TERM 			= $default['INV_TERM'];
	$INV_STAT 			= $default['INV_STAT'];
	$INV_PAYSTAT 		= $default['INV_PAYSTAT'];
	$COMPANY_ID 		= $default['COMPANY_ID'];
	$VENDINV_NUM		= $default['VENDINV_NUM'];
	$INV_NOTES 			= $default['INV_NOTES'];
	$INV_NOTES1			= $default['INV_NOTES1'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;

	/*$INV_AMOUNT			= $INV_AMOUNT1 - $INV_AMOUNT_RET + $INV_PPHVAL + $INV_LISTTAXVAL + $INV_AMOUNT_OTH;
	$INV_AMOUNTX		= $INV_AMOUNT1 - $INV_AMOUNT_RET + $INV_PPHVAL + $INV_LISTTAXVAL + $INV_AMOUNT_OTH;*/
	$INV_AMOUNT 		= $INV_AMOUNT1;
	$INV_AMOUNTX 		= $INV_AMOUNT1;

	/*$INV_AMOUNT			= $INV_AMOUNT - $INV_PPHVAL;
	if($INV_STAT == 3 || $INV_STAT == 6 || $INV_STAT == 9)
	{
		$INV_AMOUNTX	= $INV_AMOUNT - $INV_AMOUNT_RET + $INV_PPHVAL + $INV_LISTTAXVAL + $INV_AMOUNT_OTH;
	}
	else
	{
		//$INV_AMOUNTX	= $INV_AMOUNT - $INV_AMOUNT_RET + $INV_PPHVAL;
		$INV_AMOUNTX	= $INV_AMOUNT - $INV_AMOUNT_RET + $INV_PPHVAL + $INV_LISTTAXVAL + $INV_AMOUNT_OTH;
	}*/
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
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'Invoice')$Invoice = $LangTransl;
			if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;				
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SuplInvNo')$SuplInvNo = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
			if($TranslCode == 'IRList')$IRList = $LangTransl;
			if($TranslCode == 'OPNList')$OPNList = $LangTransl;
			if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'DPCode')$DPCode = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'deduction')$deduction = $LangTransl;
			if($TranslCode == 'Cost')$Cost = $LangTransl;
			if($TranslCode == 'deduction')$deduction = $LangTransl;
			if($TranslCode == 'selSpl')$selSpl = $LangTransl;
			if($TranslCode == 'selNoRef')$selNoRef = $LangTransl;
		endforeach;
		
		$PRJCODE1	= $PRJCODE;
		$SPLCODE1	= $SPLCODE;
		if(isset($_POST['PRJCODE1']))
		{
			$PRJCODE1	= $_POST['PRJCODE1'];
			$SPLCODE1	= $_POST['SPLCODE1'];
		}
		
		$SPLADD1	= '';
		$sqlSUPL	= "SELECT SPLADD1 FROM tbl_supplier WHERE SPLCODE = '$SPLCODE1' AND SPLSTAT = '1' LIMIT 1";
		$resSUPL	= $this->db->query($sqlSUPL)->result();
		foreach($resSUPL as $rowSUPL):
			$SPLADD1	= $rowSUPL->SPLADD1;
		endforeach;
		
		if(isset($_POST['TTK_NUM1']))
		{
			$TTK_NUM1	= $_POST['TTK_NUM1'];
		
			$TTK_CODE		= '';
			$TTK_CATEG		= 'IR';
			$INV_ACC_OTH	= '';
			$sqlTTK			= "SELECT TTK_CODE, TTK_CATEG, TTK_ACC_OTH, TTK_AMOUNT, TTK_AMOUNT_PPN, TTK_AMOUNT_RET, TTK_AMOUNT_OTH, TTK_AMOUNT_POT
								FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM1' AND PRJCODE = '$PRJCODE' LIMIT 1";
			$resTTK	= $this->db->query($sqlTTK)->result();
			foreach($resTTK as $rowTTK):
				$TTK_CODE		= $rowTTK->TTK_CODE;
				$TTK_CATEG		= $rowTTK->TTK_CATEG;
				$TTK_ACC_OTH	= $rowTTK->TTK_ACC_OTH;
				$INV_AMOUNTA	= $rowTTK->TTK_AMOUNT;
				$INV_AMOUNTAX	= $rowTTK->TTK_AMOUNT;
				$INV_LISTTAXVAL	= $rowTTK->TTK_AMOUNT_PPN;
				$INV_AMOUNT_RET	= $rowTTK->TTK_AMOUNT_RET;
				$INV_AMOUNT_OTH	= $rowTTK->TTK_AMOUNT_OTH;
				$INV_AMOUNT_POT	= $rowTTK->TTK_AMOUNT_POT;

				$INV_AMOUNT 	= $INV_AMOUNTA + $INV_LISTTAXVAL - $INV_AMOUNT_RET - $INV_AMOUNT_POT + $INV_AMOUNT_OTH;
				$INV_AMOUNTX 	= $INV_AMOUNTA + $INV_LISTTAXVAL - $INV_AMOUNT_RET - $INV_AMOUNT_POT + $INV_AMOUNT_OTH;
			endforeach;
		}
		
		$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
		foreach($restPRJ1 as $rowPRJ1) :
			$PRJNAME1 	= $rowPRJ1->PRJNAME;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Silahkan centang Cek Total.";
			$alert2		= "Jumlah Uang Muka yang dimasukan terlalu besar.";
			$alert3		= "Masukan alasan mengapa dokumen ini di-close.";
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert1		= "Please Check the Total Checkbox.";
			$alert2		= "Total of DP Amount is too large.";
			$alert3		= "Input the reason why you close this document.";
			$isManual	= "Check to manual code.";
		}
		
		if($TTK_CATEG == 'IR')
			$IRList	= $IRList;
		else
			$IRList	= $OPNList;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];

			// DocNumber - INV_AMOUNT
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
				$APPROVE_AMOUNT = $INV_AMOUNT;
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

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/invoice.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME1; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
            	<!-- after get Supplier code -->
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                    <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE1; ?>" />
                    <input type="text" name="SPLCODE1" id="SPLCODE1" value="<?php echo $SPLCODE1; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <!-- End -->
            	<!-- Mencari Kode Purchase Request Number -->
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE1; ?>" />
                    <input type="text" name="SPLCODE1" id="SPLCODE1" value="<?php echo $SPLCODE1; ?>" />
                    <input type="text" name="TTK_NUM1" id="TTK_NUM1" value="<?php echo $TTK_NUM1; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
                <!-- End -->
                
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkForm()">
		            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                	<input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" class="textbox" name="INV_TYPE" id="INV_TYPE" size="30" value="<?php echo $INV_TYPE; ?>" />
		                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $InvoiceNumber; ?></label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:195px" name="INV_NUMx" id="INV_NUMx" value="<?php echo $INV_NUM; ?>" disabled >
		                        		<input type="hidden" class="textbox" name="INV_NUM" id="INV_NUM" size="30" value="<?php echo $INV_NUM; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="max-width:150px" name="INV_CODE" id="INV_CODE" value="<?php echo $INV_CODE; ?>" >
		                            	<input type="text" class="form-control" name="INV_CODEX" id="INV_CODEX" value="<?php echo $INV_CODE; ?>" disabled >
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?></label>
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
		                          	<div class="col-sm-3">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="INV_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $INV_DATE; ?>" style="width:120px">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-6">
			                        	<table>
			                        		<tr>
			                        			<td width="40%"><label for="inputName" class="col-sm-12 control-label"><?php echo $DueDate; ?></label></td>
			                        			<td width="60%">
			                        				<div class="input-group date">
					                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
					                                    <input type="text" name="INV_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $INV_DUEDATE; ?>">
					                                </div>
			                        			</td>
			                        		</tr>
			                        	</table>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SourceDocument; ?></label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <select name="RRSource" id="RRSource" class="form-control" style="max-width:150px">
		                                        <option value="IR" >Item Receipt</option>
		                                        <option value="OTH" style="display:none">Other</option>    
		                                    </select>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName; ?></label>
		                          	<div class="col-sm-9">
		                            	<select name="SPLCODE" id="SPLCODE" class="form-control select2" onChange="getSUPPLIER(this.value)" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
		                                    <option value="none"> --- </option>
		                                    <?php echo $i = 0;
		                                    if($countSUPL > 0)
		                                    {
		                                        foreach($vwSUPL as $row) :
		                                        ?>
		                                            <option value="<?php echo $row->SPLCODE; ?>" <?php if($SPLCODE1 == $row->SPLCODE) { ?> selected <?php } ?>>
		                                        		<?php echo $row->SPLDESC; ?>
		                                        	</option>
		                                        <?php
		                                        endforeach;
		                                    }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
		                        <script>
									function getSUPPLIER(SPLCODE) 
									{
										document.getElementById("SPLCODE1").value = SPLCODE;
										PRJCODE	= document.getElementById("PRJCODE").value
										document.getElementById("PRJCODE1").value = PRJCODE;
										document.frmsrch1.submitSrch1.click();
									}
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $VendAddress; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea name="SPLADD1" id="SPLADD1" cols="50" class="form-control"  style="height:60px"><?php echo $SPLADD1; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
		                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
		                                <?php
		                                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		                                    $resultPRJ 	= $this->db->query($sqlPRJ)->result();
											
											foreach($resultPRJ as $rowPRJ) :
												$PRJCODE1 	= $rowPRJ->PRJCODE;
												$PRJNAME1 	= $rowPRJ->PRJNAME;
												?>
													<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?>selected <?php } ?>>
														<?php echo $PRJNAME1; ?>
													</option>
												<?php
											 endforeach;
												 
		                                ?>
		                            </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                       	  <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="INV_NOTES"  id="INV_NOTES" style="height:55px"><?php echo $INV_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group" <?php if($INV_STAT != 6 ) { ?> style="display:none" <?php } ?> id="tblReason">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Reason; ?></label>
		                            <div class="col-sm-9">
		                                <textarea name="INV_NOTES1" class="form-control" style="max-width:350px;" id="INV_NOTES1" cols="30"><?php echo $INV_NOTES1; ?></textarea>                        
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $RefNumber; ?></label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search; ?></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="Ref_Number" id="Ref_Number" value="<?php echo $TTK_NUM1; ?>" onClick="selectTTK();">
		                                    <input type="text" class="form-control" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>" onClick="selectTTK();">
		                                    <?php
												$collID			= "$PRJCODE~$SPLCODE1";
												$url_popTTK		= site_url('c_purchase/c_pi180c23/popupall_TTK/?id='.$this->url_encryption_helper->encode_url($collID));
											?>       
											<script>
												var urlTTK 	= "<?php echo $url_popTTK;?>";
												function selectTTK()
												{
													SPLCODE	= document.getElementById('SPLCODE').value;
													if(SPLCODE == 'none')
													{
														swal('<?php echo $selSpl; ?>',
														{
															icon: "warning",
														});
														document.getElementById('SPLCODE').focus();
														return false;
													}
													title = 'Select Item Receive';
													w = 780;
													h = 550;
													
													var left = (screen.width/2)-(w/2);
													var top = (screen.height/2)-(h/2);
													//return window.open(urlLPM+VC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
													return window.open(urlTTK, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
												}
											</script>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                       	  <label for="inputName" class="col-sm-3 control-label"><?php echo $SuplInvNo; ?></label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="VENDINV_NUM" id="VENDINV_NUM" value="<?php echo $VENDINV_NUM; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Cek Total</label>
		                            <div class="col-sm-9">
		                                <div class="input-group">
		                                    <span class="input-group-addon">
		                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalTTK()">
		                                    </span>
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT" id="INV_AMOUNT" value="<?php echo $INV_AMOUNT; ?>" >
		                                    <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNTX" id="INV_AMOUNTX" value="<?php echo number_format($INV_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" disabled >
		                                    <textarea class="form-control" name="REF_NOTES"  id="REF_NOTES" style="max-width:350px;height:70px; display:none" readonly></textarea>
		                        		</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">PPn / Retensi</label>
		                            <div class="col-sm-5">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_LISTTAXVAL" id="INV_LISTTAXVAL" value="<?php echo $INV_LISTTAXVAL; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_LISTTAXVALX" id="INV_LISTTAXVALX" value="<?php echo number_format($INV_LISTTAXVAL, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_RET" id="INV_AMOUNT_RET" value="<?php echo $INV_AMOUNT_RET; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_RETX" id="INV_AMOUNT_RETX" value="<?php echo number_format($INV_AMOUNT_RET, 2); ?>" onBlur="getAmountRet(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Cost; ?> / <?php echo $deduction; ?></label>
		                            <div class="col-sm-5">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_OTH" id="INV_AMOUNT_OTH" value="<?php echo $INV_AMOUNT_OTH; ?>" >
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_ACC_OTH" id="INV_ACC_OTH" value="<?php echo $INV_ACC_OTH; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_OTHX" id="INV_AMOUNT_OTHX" value="<?php echo number_format($INV_AMOUNT_OTH, 2); ?>" onBlur="getAmountExp(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_POT" id="INV_AMOUNT_POT" value="<?php echo $INV_AMOUNT_POT; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_POTX" id="INV_AMOUNT_POTX" value="<?php echo number_format($INV_AMOUNT_POT, 2); ?>" onBlur="getAmountPot(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                        </div>
		                        <script type="text/javascript">
		                        	function getAmountPPn(thisVal)
		                        	{
		                        		var INV_PPN 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('INV_LISTTAXVAL').value 	= INV_PPN;
		                        		document.getElementById('INV_LISTTAXVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPN)),2));

		                        		totalrow		= document.getElementById('totalrow').value;
										TTK_TOT_AM		= 0;
										for (i = 1; i <= totalrow; i++) 
										{
											TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
											TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
										}

		                        		var INV_TOT = parseFloat(TTK_TOT_AM);
		                        		var INV_RET = document.getElementById('INV_AMOUNT_RET').value;
		                        		var INV_POT = document.getElementById('INV_AMOUNT_POT').value;
		                        		var INV_EXP = document.getElementById('INV_AMOUNT_OTH').value;

		                        		var GTOT 	= parseFloat(INV_TOT) + parseFloat(INV_PPN) - parseFloat(INV_POT) + parseFloat(INV_EXP) - parseFloat(INV_RET);

		                        		document.getElementById('INV_AMOUNT').value 		= GTOT;
										document.getElementById('INV_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT)),2));
		                        	}

		                        	function getAmountRet(thisVal)
		                        	{
		                        		var INV_RET 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('INV_AMOUNT_RET').value 	= INV_RET;
		                        		document.getElementById('INV_AMOUNT_RETX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_RET)),2));

		                        		totalrow		= document.getElementById('totalrow').value;
										TTK_TOT_AM		= 0;
										for (i = 1; i <= totalrow; i++) 
										{
											TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
											TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
										}

		                        		var INV_TOT = parseFloat(TTK_TOT_AM);
		                        		var INV_PPN = document.getElementById('INV_LISTTAXVAL').value;
		                        		var INV_POT = document.getElementById('INV_AMOUNT_POT').value;
		                        		var INV_EXP = document.getElementById('INV_AMOUNT_OTH').value;

		                        		var GTOT 	= parseFloat(INV_TOT) + parseFloat(INV_PPN) - parseFloat(INV_POT) + parseFloat(INV_EXP) - parseFloat(INV_RET);

		                        		document.getElementById('INV_AMOUNT').value 		= GTOT;
										document.getElementById('INV_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT)),2));
		                        	}

		                        	function getAmountExp(thisVal)
		                        	{
		                        		var INV_EXP 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('INV_AMOUNT_OTH').value 	= INV_EXP;
		                        		document.getElementById('INV_AMOUNT_OTHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_EXP)),2));

		                        		totalrow		= document.getElementById('totalrow').value;
										TTK_TOT_AM		= 0;
										for (i = 1; i <= totalrow; i++) 
										{
											TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
											TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
										}

		                        		var INV_TOT = parseFloat(TTK_TOT_AM);
		                        		var INV_PPN = document.getElementById('INV_LISTTAXVAL').value;
		                        		var INV_POT = document.getElementById('INV_AMOUNT_POT').value;
		                        		var INV_RET = document.getElementById('INV_AMOUNT_RET').value;

		                        		var GTOT 	= parseFloat(INV_TOT) + parseFloat(INV_PPN) - parseFloat(INV_POT) + parseFloat(INV_EXP) - parseFloat(INV_RET);

		                        		document.getElementById('INV_AMOUNT').value 		= GTOT;
										document.getElementById('INV_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT)),2));
		                        	}

		                        	function getAmountPot(thisVal)
		                        	{
		                        		var INV_POT 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('INV_AMOUNT_POT').value 	= INV_POT;
		                        		document.getElementById('INV_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_POT)),2));

		                        		totalrow		= document.getElementById('totalrow').value;
										TTK_TOT_AM		= 0;
										for (i = 1; i <= totalrow; i++) 
										{
											TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
											TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
										}

		                        		var INV_TOT = parseFloat(TTK_TOT_AM);
		                        		var INV_PPN = document.getElementById('INV_LISTTAXVAL').value;
		                        		var INV_EXP = document.getElementById('INV_AMOUNT_OTH').value;
		                        		var INV_RET = document.getElementById('INV_AMOUNT_RET').value;

		                        		var GTOT 	= parseFloat(INV_TOT) + parseFloat(INV_PPN) - parseFloat(INV_POT) + parseFloat(INV_EXP) - parseFloat(INV_RET);

		                        		document.getElementById('INV_AMOUNT').value 		= GTOT;
										document.getElementById('INV_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT)),2));
		                        	}
		                        </script>
		                        <div class="form-group" style="display: none;">
		                            <label for="inputName" class="col-sm-3 control-label">PPh</label>
		                            <div class="col-sm-9">
		                            <label>
		                                <select name="INV_PPH" id="INV_PPH" class="form-control" style="max-width:190px" onChange="selPPhStat(this.value)">
		                                	<option value=""> --- none ---</option>
		                                	<?php
												$sqlTLA 	= "SELECT TAXLA_NUM, TAXLA_CODE, TAXLA_PERC FROM tbl_tax_la";
												$resTLA 	= $this->db->query($sqlTLA)->result(); 
												foreach($resTLA as $rowTLA): 
													$TAXLA_NUM	= $rowTLA->TAXLA_NUM;
													$TAXLA_CODE	= $rowTLA->TAXLA_CODE;
													$TAXLA_PERC	= $rowTLA->TAXLA_PERC;
													$TAXLA_PERC1= number_format($TAXLA_PERC, 2);
													//$INV_PPH1	= "$TAXLA_NUM~$TAXLA_PERC1";
													$INV_PPH1	= "$TAXLA_NUM";
													
													?>
													<option value="<?php echo "$TAXLA_NUM~$TAXLA_PERC"; ?>"<?php if($INV_PPH1 == $INV_PPH) { ?> selected <?php } ?>><?php echo "$TAXLA_CODE"; ?></option>
													<?php
												endforeach;
											?>
		                                </select>
		                            </label>
		                            <label id="showPPh" <?php if($INV_PPH == '') { ?> style="display:none" <?php } ?>>
		                            	<input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_PPHVAL" id="INV_PPHVAL" value="<?php echo $INV_PPHVAL; ?>" >
		                                <input type="text" class="form-control" style="max-width:120px; text-align:right" name="INV_PPHVALX" id="INV_PPHVALX" value="<?php echo number_format($INV_PPHVAL, 2); ?>" onBlur="getAmountPPh(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </label>
		                            </div>
		                        </div>
		                        <script>
									function selPPhStat(thisVal)
									{
										var perc1 			= thisVal.split("~");
										if(perc1 > 0 || perc1 != '')
										{
											var perc			= parseFloat(perc1[1]);
										}
										else
										{
											var perc			= 0;
										}
										var INV_AMOUNT		= parseFloat(document.getElementById('INV_AMOUNT').value);
										var INV_LISTTAXVAL	= parseFloat(document.getElementById('INV_LISTTAXVAL').value);
										var INV_AMOUNT_RET	= parseFloat(document.getElementById('INV_AMOUNT_RET').value);
										var INV_AMOUNT_POT	= parseFloat(document.getElementById('INV_AMOUNT_POT').value);
										var INV_AMOUNT_OTH	= parseFloat(document.getElementById('INV_AMOUNT_OTH').value);
										
										var totalrow		= document.getElementById("totalrow").value;
										TTK_TOT_AM			= 0;
										TTK_TOT_RET			= 0;
										TTK_TOT_POT			= 0;
										TTK_TOT_PPN			= 0;
										TTK_GTOTAL			= 0;
										for (i = 1; i <= totalrow; i++) 
										{
											TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
											TTK_REF1_RET	= document.getElementById('TTK_REF1_RET'+i).value;
											TTK_REF1_POT	= document.getElementById('TTK_REF1_POT'+i).value;
											TTK_REF1_PPN	= document.getElementById('TTK_REF1_PPN'+i).value;
											TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
											TTK_TOT_RET		= parseFloat(TTK_TOT_RET) + parseFloat(TTK_REF1_RET);
											TTK_TOT_POT		= parseFloat(TTK_TOT_POT) + parseFloat(TTK_REF1_POT);
											TTK_TOT_PPN		= parseFloat(TTK_TOT_PPN) + parseFloat(TTK_REF1_PPN);
										}
										var INV_AMOUNT		= parseFloat(TTK_TOT_AM);
										var INV_LISTTAXVAL	= parseFloat(TTK_TOT_PPN);
										var INV_AMOUNT_RET	= parseFloat(TTK_TOT_RET);
										var INV_AMOUNT_POT	= parseFloat(TTK_TOT_POT);
										
										TTK_GTOTAL			= parseFloat(TTK_TOT_AM) - parseFloat(TTK_TOT_RET) - parseFloat(TTK_TOT_POT) + parseFloat(TTK_TOT_PPN);
										// HASIL MEETING 27 DES 18 DI MS, RETENSI TIDAK TERMASUK DI INV, HANYA INFORMASI
										INV_CATEG		= document.getElementById('INV_CATEG').value;
										if(INV_CATEG == 'OPN')
										{
											TTK_GTOTAL		= parseFloat(TTK_TOT_AM) - parseFloat(TTK_TOT_POT);
											
											//var INV_AMOUNTX	= parseFloat(INV_AMOUNT - INV_LISTTAXVAL + INV_AMOUNT_RET);
											//var INV_AMOUNTX	= parseFloat(INV_AMOUNT);
										}
										else
										{
											//TTK_GTOTAL	= parseFloat(TTK_TOT_AM) - parseFloat(TTK_TOT_POT) + parseFloat(TTK_TOT_PPN);
											TTK_GTOTAL		= parseFloat(TTK_TOT_AM) - parseFloat(TTK_TOT_POT);
										}
										var INV_AMOUNTX		= parseFloat(INV_AMOUNT) - parseFloat(TTK_TOT_POT); // HANYA POTONGAN
										
										var TaxPPh			= parseFloat(perc * INV_AMOUNTX) / 100;
										
										if(thisVal == '')
											document.getElementById('showPPh').style.display = 'none';
										else
											document.getElementById('showPPh').style.display = '';
										
										document.getElementById('INV_PPHVAL').value 	= TaxPPh;
										document.getElementById('INV_PPHVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TaxPPh)), 2));
										
										// PERMINTAAN PAK ANDI 9 JAN 2019, YANG DITAMPILKAN ADALAH HASIL TOTAL + PPN - PPH - RETENSI;
										var NEWINV_AMOUNT	= parseFloat(TTK_GTOTAL) - parseFloat(TaxPPh) + parseFloat(INV_AMOUNT_OTH);
										var NEWINV_AMOUNTV	= parseFloat(TTK_GTOTAL) + parseFloat(TTK_TOT_PPN) + parseFloat(INV_AMOUNT_OTH) - parseFloat(TTK_TOT_RET) - parseFloat(TaxPPh);
										document.getElementById('INV_AMOUNT').value 	= NEWINV_AMOUNT;
										document.getElementById('INV_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(NEWINV_AMOUNTV)), 2));
									}
									
									/*function getAmountPPn(thisVal)
									{
										
										INV_PPNVAL	= parseFloat(eval(thisVal).value.split(",").join(""));
										document.getElementById('INV_LISTTAXVAL').value 	= INV_PPNVAL;
										document.getElementById('INV_LISTTAXVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPNVAL)), 2));
									}*/
									
									function getAmountPPh(thisVal)
									{
										
										INV_PPHVAL	= parseFloat(eval(thisVal).value.split(",").join(""));
										document.getElementById('INV_PPHVAL').value 	= INV_PPHVAL;
										document.getElementById('INV_PPHVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPHVAL)), 2));
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $DPCode; ?></label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search; ?></button>
		                                    </div>
		                                    <input type="text" class="form-control" name="DP_NUM" id="DP_NUM" style="max-width:160px" value="<?php echo $DP_NUM; ?>" onClick="selectIR();">
		                                    <?php
												$collID			= "$PRJCODE~$SPLCODE1";
												$url_popLPM		= site_url('c_purchase/c_pi180c23/popupall_DP/?id='.$this->url_encryption_helper->encode_url($collID));
											?>       
											<script>
												var urlLPM 	= "<?php echo $url_popLPM;?>";
												function selectIR()
												{
													SPLCODE	= document.getElementById('SPLCODE').value;
													if(SPLCODE == 'none')
													{
														swal('<?php echo $selSpl; ?>',
														{
															icon: "warning",
														});
														document.getElementById('SPLCODE').focus();
														return false;
													}
													title = 'Select Item Receive';
													w = 780;
													h = 550;
													
													var left = (screen.width/2)-(w/2);
													var top = (screen.height/2)-(h/2);
													//return window.open(urlLPM+VC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
													return window.open(urlLPM, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
												}
											</script>
		                                </div>
		                            </div>
		                        </div>
		                        <?php
									// DP REMAIN
									$DP_AMOUNT_REM	= $DP_AMOUNT;
									$DP_AMOUNT_USED	= 0;
									$selDP			= "tbl_dp_header WHERE DP_NUM = '$DP_NUM'";
									$resDP			= $this->db->count_all($selDP);
									if($resDP > 0)
									{
										$selDP1	= "SELECT DP_AMOUNT, DP_AMOUNT_USED FROM tbl_dp_header WHERE DP_NUM = '$DP_NUM'";
										$resDP1	= $this->db->query($selDP1)->result();
										foreach($resDP1 as $rowDP1):
											$DP_AMOUNT1		= $rowDP1->DP_AMOUNT;
											$DP_AMOUNT_USED	= $rowDP1->DP_AMOUNT_USED;
										endforeach;
										$DP_AMOUNT_REM	= $DP_AMOUNT1 - $DP_AMOUNT_USED;
									}
								?>
		                        <div class="form-group" style="display:none">
		                       	  <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:160px; text-align:right" name="DP_AMOUNT1" id="DP_AMOUNT1" value="<?php echo number_format($DP_AMOUNT_REM, 2); ?>" onBlur="getDPAmount(this);" >
		                                <input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="DP_AMOUNT" id="DP_AMOUNT" value="<?php echo $DP_AMOUNT_REM; ?>" >
		                          	</div>
		                        </div>
		                        <script>
									function getDPAmount(thisVal)
									{
										var decFormat		= document.getElementById('decFormat').value;
										
										DP_AMOUNT		= document.getElementById('DP_AMOUNT').value;
										DP_AMOUNT_REM	= parseFloat(eval(thisVal).value.split(",").join(""));
										if(DP_AMOUNT_REM > DP_AMOUNT)
										{
											swal('<?php echo $alert2; ?>',
											{
												icon: "warning",
											});
											document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT)),decFormat));
											document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT));
										}
										else
										{
											document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT_REM)),decFormat));
											document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT_REM));
										}
									}
								</script>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $INV_STAT; ?>">
								<?php
									// START : FOR ALL APPROVAL FUNCTION
										$disButton	= 0;
										$disButtonE	= 0;
										if($task == 'add')
										{
											if($ISCREATE == 1 || $ISAPPROVE == 1)
											{
												?>
													<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" >
														<option value="1">New</option>
														<option value="2">Confirm</option>
													</select>
												<?php
											}
										}
										else
										{
											$disButton	= 1;
											if(($ISCREATE == 1 && $ISAPPROVE == 1))
											{
												if($INV_STAT == 1 || $INV_STAT == 4)
												{
													$disButton	= 0;
													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" >
															<option value="1">New</option>
															<option value="2">Confirm</option>
														</select>
													<?php
												}
												elseif($INV_STAT == 2 || $INV_STAT == 7)
												{
													$disButton	= 0;
													if($canApprove == 0)
														$disButton	= 1;
													
													$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$INV_NUM' AND AH_APPROVER = '$DefEmp_ID'";
													$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
													if($resCAPPHE > 0)
														$disButton	= 1;
												
													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
															<option value="1"<?php if($INV_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
															<option value="2"<?php if($INV_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
															<option value="3"<?php if($INV_STAT == 3) { ?> selected <?php } ?> >Approved</option>
															<option value="4"<?php if($INV_STAT == 4) { ?> selected <?php } ?> >Revising</option>
															<option value="5"<?php if($INV_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
															<!-- <option value="6"<?php if($INV_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option> -->
															<option value="7"<?php if($INV_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
															<?php if($INV_STAT == 3 || $INV_STAT == 9) { ?>
															<option value="9"<?php if($INV_STAT == 9) { ?> selected <?php } ?>>Void</option>
															<?php } ?>
														</select>
													<?php
												}
												elseif($INV_STAT == 3)
												{
													$disButton	= 0;
													if($canApprove == 0)
														$disButton	= 1;

													$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$INV_NUM' AND AH_APPROVER = '$DefEmp_ID'";
													$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
													if($resCAPPHE > 0)
														$disButton	= 1;
													if($ISDELETE == 1)
														$disButton	= 0;

													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1){ ?> disabled <?php } ?>>
															<option value="1"<?php if($INV_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
															<option value="2"<?php if($INV_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
															<option value="3"<?php if($INV_STAT == 3) { ?> selected <?php } ?> >Approved</option>
															<option value="4"<?php if($INV_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
															<option value="5"<?php if($INV_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
															<option value="6"<?php if($INV_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
															<option value="7"<?php if($INV_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
															<option value="9"<?php if($INV_STAT == 9) { ?> selected <?php } ?>>Void</option>
														</select>
													<?php
												}
											}
											elseif($ISAPPROVE == 1)
											{
												if($INV_STAT == 1 || $INV_STAT == 4)
												{
													$disButton	= 1;
													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
															<option value="1">New</option>
															<option value="2">Confirm</option>
														</select>
													<?php
												}
												else if($INV_STAT == 2 || $INV_STAT == 3 || $INV_STAT == 7)
												{
													$disButton	= 0;
													if($canApprove == 0)
														$disButton	= 1;
														
													$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$INV_NUM' AND AH_APPROVER = '$DefEmp_ID'";
													$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
													if($resCAPPHE > 0)
														$disButton	= 1;
												
													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
															<option value="1"<?php if($INV_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
															<option value="2"<?php if($INV_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
															<option value="3"<?php if($INV_STAT == 3) { ?> selected <?php } ?> >Approved</option>
															<option value="4"<?php if($INV_STAT == 4) { ?> selected <?php } ?> >Revising</option>
															<option value="5"<?php if($INV_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
															<option value="6"<?php if($INV_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
															<option value="7"<?php if($INV_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
															<?php if($INV_STAT == 3 || $INV_STAT == 9) { ?>
															<option value="9"<?php if($INV_STAT == 9) { ?> selected <?php } ?>>Void</option>
															<?php } ?>
														</select>
													<?php
												}
											}
											elseif($ISCREATE == 1)
											{
												if($INV_STAT == 1 || $INV_STAT == 4)
												{
													$disButton	= 0;
													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
															<option value="1"<?php if($INV_STAT == 1) { ?> selected <?php } ?> >New</option>
															<option value="2"<?php if($INV_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
															<option value="3"<?php if($INV_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
															<option value="4"<?php if($INV_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
															<option value="5"<?php if($INV_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
															<option value="6"<?php if($INV_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
															<option value="7"<?php if($INV_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
															<?php if($INV_STAT == 3 || $INV_STAT == 9) { ?>
															<option value="9"<?php if($INV_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
															<?php } ?>
														</select>
													<?php
												}
												else
												{
													$disButton	= 1;
													?>
														<select name="INV_STAT" id="INV_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
															<option value="1"<?php if($INV_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
															<option value="2"<?php if($INV_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
															<option value="3"<?php if($INV_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
															<option value="4"<?php if($INV_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
															<option value="5"<?php if($INV_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
															<option value="6"<?php if($INV_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
															<option value="7"<?php if($INV_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
															<?php if($INV_STAT == 3 || $INV_STAT == 9) { ?>
															<option value="9"<?php if($INV_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
															<?php } ?>
														</select>
													<?php
												}
											}
										}
									// END : FOR ALL APPROVAL FUNCTION
		                        ?>
		                            </div>
		                        </div>
								<script>
		                            function selStat(thisValue)
		                            {
		                            	STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
		                            	if(STAT_BEFORE == 3)
		                            	{
			                                if(thisValue == 6)
			                                {
			                                    document.getElementById('tblClose').style.display 	= '';
			                                    document.getElementById('tblReason').style.display 	= '';
			                                }
			                                else
			                                {
			                                    document.getElementById('tblClose').style.display 	= 'none';
			                                    document.getElementById('tblReason').style.display 	= 'none';
			                                }
		                            	}
		                            }
		                        </script>
		                        <?php
									$sqlIRHeaderC	= "tbl_ttk_detail A
														INNER JOIN tbl_ir_header B ON A.TTK_REF1 = B.IR_NUM
															AND B.PRJCODE = '$PRJCODE'
														WHERE TTK_NUM = '$TTK_NUM1' AND PRJCODE = '$PRJCODE'";
									$resIRHeaderC	= $this->db->count_all($sqlIRHeaderC);
									$sqlIRHeader	= "SELECT A.TTK_NUM, A.TTK_REF1, A.TTK_REF1_DATE, A.TTK_REF1_DATED, 
															A.TTK_REF1_AM, A.TTK_REF1_PPN, A.TTK_REF2, A.TTK_REF2_DATE,
															A.TTK_DESC
														FROM tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$TTK_NUM1'";
									$resIRHeader 	= $this->db->query($sqlIRHeader)->result();
								?>
							</div>
						</div>
					</div>

					<div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                              <div class="box box-danger collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $IRList; ?></h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove" style="display: none;"><i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                    <table width="100%" border="1" id="tbl">
                                    <tr style="background:#CCCCCC">
                                        <td width="3%" height="25" style="text-align:center">No.</td>
                                        <td width="10%" style="text-align:center" nowrap><?php echo $ReceiptNumber; ?></td>
                                        <td width="3%" style="text-align:center; display:none" nowrap><?php echo $ReceiptCode; ?></td>
                                        <td width="2%" style="text-align:center" nowrap><?php echo $ReceiptDate; ?></td>
                                        <td width="17%" style="text-align:center" nowrap><?php echo $ReferenceNumber; ?></td>
                                        <td width="40%" style="text-align:center" nowrap><?php echo $Description; ?></td>
                                        <td width="8%" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></td>
                                        <td width="1%" style="text-align:center" nowrap>Retensi</td>
                                        <td width="1%" style="text-align:center" nowrap><?php echo $deduction; ?></td>
                                        <td width="5%" style="text-align:center" nowrap>PPn</td>
                                        <td width="10%" style="text-align:center" nowrap><?php echo $TotAmount; ?></td>
                                    </tr>
									<?php
									$TOT_AMOUNT2	= 0;
									$resIRHeaderC	= 0;
									
									$refNumber 		= $TTK_NUM1;						
									// count data
									$sqlIRHeaderC	= "tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$refNumber'";
									$resIRHeaderC	= $this->db->count_all($sqlIRHeaderC);
									// End count data
									
									// 1. Ambil detail IR
									$sqlIRHeader	= "SELECT A.TTK_NUM, A.TTK_REF1, A.TTK_REF1_DATE, A.TTK_REF1_DATED, 
															A.TTK_REF1_AM, A.TTK_REF1_RET, A.TTK_REF1_POT, A.TTK_REF1_PPN,
															A.TTK_REF2, A.TTK_REF2_DATE, A.TTK_DESC, C.TTK_CATEG
														FROM tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$refNumber'";
									$resIRHeader 	= $this->db->query($sqlIRHeader)->result();
									
									$collIR			= '';
									$TTK_CATEG		= 'IR';		
									if($resIRHeaderC > 0)
									{
										$TOT_AMOUNT1	= 0;
										$TOT_AMOUNT2	= 0;
										$TOT_DISC		= 0;
										$TOT_TAX1		= 0;
										$TOT_TAX2		= 0;
										$collIR			= '';
										foreach($resIRHeader as $row) :
											$currentRow  	= ++$i;											
											$TTK_REF1 		= $row->TTK_REF1;
											$TTK_CATEG		= $row->TTK_CATEG;
											$TTK_REF1_DATE	= $row->TTK_REF1_DATE;
											$TTK_REF1_AM	= $row->TTK_REF1_AM;
											$TTK_REF1_RET	= $row->TTK_REF1_RET;
											$TTK_REF1_POT	= $row->TTK_REF1_POT;
											$TTK_REF1_PPN	= $row->TTK_REF1_PPN;
											$TTK_TOTAL_AM	= $TTK_REF1_AM - $TTK_REF1_RET - $TTK_REF1_POT + $TTK_REF1_PPN;
											// HASIL MEETING 27 DES 18 DI MS, RETENSI TIDAK TERMASUK DI INV, HANYA INFORMASI
											$TTK_TOTAL_AM	= $TTK_REF1_AM - $TTK_REF1_POT;
											$TTK_TOTAL_AMV	= $TTK_REF1_AM - $TTK_REF1_RET - $TTK_REF1_POT + $TTK_REF1_PPN;
											if($TTK_CATEG == 'IR')
											{
												$TTK_TOTAL_AM	= $TTK_REF1_AM - $TTK_REF1_POT + $TTK_REF1_PPN;
												$TTK_TOTAL_AMV	= $TTK_REF1_AM - $TTK_REF1_POT + $TTK_REF1_PPN;
											}
												
											$TTK_REF2 		= $row->TTK_REF2;
											$TTK_REF2_DATE	= $row->TTK_REF2_DATE;
											$TTK_DESC 		= $row->TTK_DESC;

											$IR_CODE 		= $TTK_REF1;
											$PO_CODE 		= $TTK_REF2;
											$sqlIRHCODE		= "SELECT IR_CODE, PO_CODE FROM tbl_ir_header WHERE IR_NUM = '$TTK_REF1'";
											$resIRHCODE 	= $this->db->query($sqlIRHCODE)->result();
											foreach($resIRHCODE as $row) :
												$IR_CODE 	= $row->IR_CODE;								
												$PO_CODE 	= $row->PO_CODE;
											endforeach;
											
											if($currentRow == 1)
											{
												$collIR		= $TTK_REF1;
											}
											else
											{
												$collIR		= "$collIR','$TTK_REF1";
											}
											?>
												<tr>
													<td width="3%" height="25" style="text-align:left">
														<?php echo $currentRow; ?>.
													</td>
													<td width="10%" style="text-align:left" nowrap>
														<?php print $IR_CODE; ?>
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>INV_NUM" name="data1[<?php echo $currentRow; ?>][INV_NUM]" value="<?php print $INV_NUM; ?>">
                                                       <input type="hidden" id="data1<?php echo $currentRow; ?>INV_CODE" name="data1[<?php echo $currentRow; ?>][INV_CODE]" value="<?php print $INV_CODE; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>IR_NUM" name="data1[<?php echo $currentRow; ?>][IR_NUM]" value="<?php print $TTK_REF1; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>PRJCODE" name="data1[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>">
														<!-- IR_NUM -->
													</td>
													<td width="3%" style="text-align:left; display:none" nowrap>
														<?php //print date('d-m-Y', strtotime($TTK_REF1_DATE)); ?>
														<!-- IR_CODE -->
													</td>
													<td width="2%" style="text-align:center" nowrap>
														<?php print date('d-m-Y', strtotime($TTK_REF1_DATE)); ?>
														<!-- PO_NUM -->
													</td>
													<td width="17%" style="text-align:center" nowrap>
														<?php echo $PO_CODE; ?>
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>REF_NUM" name="data1[<?php echo $currentRow; ?>][REF_NUM]" value="<?php print $TTK_REF2; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_CODE" name="data1[<?php echo $currentRow; ?>][ITM_CODE]" value="">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ACC_ID" name="data1[<?php echo $currentRow; ?>][ACC_ID]" value="">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_QTY" name="data1[<?php echo $currentRow; ?>][ITM_QTY]" value="1">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_QTY" name="data1[<?php echo $currentRow; ?>][ITM_QTY]" value="1">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNIT" name="data1[<?php echo $currentRow; ?>][ITM_UNIT]" value="LS">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNITP" name="data1[<?php echo $currentRow; ?>][ITM_UNITP]" value="<?php print $TTK_REF1_AM; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_DISC" name="data1[<?php echo $currentRow; ?>][ITM_DISC]" value="0">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNITP_BASE" name="data1[<?php echo $currentRow; ?>][ITM_UNITP_BASE]" value="<?php print $TTK_REF1_AM; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNITP_BASE" name="data1[<?php echo $currentRow; ?>][ITM_UNITP_BASE]" value="<?php print $TTK_REF1_AM; ?>">
														<!-- PO_NUM -->
													</td>
													<td>
														<?php print $TTK_DESC; ?>
														<!-- IR_NOTE -->
													</td>
												  <td style="text-align:right">
														<?php print number_format($TTK_REF1_AM, $decFormat); ?>&nbsp;
												  		<input type="hidden" id="TTK_REF1_AM<?php echo $currentRow; ?>" name="TTK_REF1_AM<?php echo $currentRow; ?>" value="<?php print $TTK_REF1_AM; ?>">
                                                    	<input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT]" value="<?php print $TTK_REF1_AM; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT_BASE" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT_BASE]" value="<?php print $TTK_REF1_AM; ?>"></td>
												  <td style="text-align:right">
														<?php print number_format($TTK_REF1_RET, $decFormat); ?>&nbsp;
												  		<input type="hidden" id="TTK_REF1_RET<?php echo $currentRow; ?>" name="TTK_REF1_RET<?php echo $currentRow; ?>" value="<?php print $TTK_REF1_RET; ?>">
                                                    	<input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT_RET" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT_RET]" value="<?php print $TTK_REF1_RET; ?>">
                                                  </td>
												  <td style="text-align:right">
														<?php print number_format($TTK_REF1_POT, $decFormat); ?>&nbsp;
												  		<input type="hidden" id="TTK_REF1_POT<?php echo $currentRow; ?>" name="TTK_REF1_POT<?php echo $currentRow; ?>" value="<?php print $TTK_REF1_POT; ?>">
                                                    	<input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT_POT" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT_POT]" value="<?php print $TTK_REF1_POT; ?>">
                                                  </td>
													<td style="text-align:right">
                                                    	<?php
															$TAXCODE1			= '';
															$TAX_AMOUNT_PPn1	= 0;
															if($TTK_REF1_PPN > 0)
															{
																$TAXCODE1			= 'TAX01';
																$TAX_AMOUNT_PPn1	= $TTK_REF1_PPN;
															}
                                                        ?>
														<?php print number_format($TTK_REF1_PPN, $decFormat); ?>&nbsp;
													  	<input type="hidden" id="TTK_REF1_PPN<?php echo $currentRow; ?>" name="TTK_REF1_PPN<?php echo $currentRow; ?>" value="<?php print $TTK_REF1_PPN; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>TAXCODE1" name="data1[<?php echo $currentRow; ?>][TAXCODE1]" value="<?php print $TAXCODE1; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>TAX_AMOUNT_PPn1" name="data1[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn1]" value="<?php print $TAX_AMOUNT_PPn1; ?>">
                                                    </td>
													<td style="text-align:right">
														<?php print number_format($TTK_TOTAL_AMV, $decFormat); ?>&nbsp;
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT1" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT1]" value="<?php print $TTK_TOTAL_AM; ?>">
                                                    </td>
												</tr>
											<?php
										endforeach;
									}
									$collIR = "'$collIR'";
                                    ?>   
                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                </table>
                                    </div>
                              	</div>
                            </div>
                        </div>
                    </div>

					<div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                              	<div class="box box-primary">
	                              	<div class="search-table-outter">
		                           		<table id="tbl" class="table table-bordered table-striped" width="100%">
		                                    <tr style="background:#CCCCCC">
		                                        <th width="3%" height="25" style="text-align:center">No.</th>
		                                        <th width="2%" style="text-align:center" nowrap><?php echo $ItemCode; ?></th>
		                                        <th width="46%" style="text-align:center" nowrap><?php echo $ItemName; ?></th>
		                                        <th width="8%" style="text-align:center" nowrap><?php echo $ReceiptQty; ?></th>
		                                        <th width="2%" style="text-align:center" nowrap><?php echo $Unit; ?></th>
		                                        <th width="11%" style="text-align:center" nowrap><?php echo $Total; ?></th>
		                                        <th width="4%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?> <br>(%)</th>
		                                        <th width="4%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?></th>
		                                        <th width="9%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?> 1</th>
		                                        <th width="9%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?> 2</th>
		                                        <th width="2%" style="text-align:center; display:none" nowrap><?php echo $Total; ?></th>
		                                    </tr>
		                                    <input type="hidden" name="INV_CATEG" id="INV_CATEG" value="<?php echo $TTK_CATEG; ?>">
											<?php
											$TOT_AMOUNT2	= 0;
											$CountsqlItem	= 0;
											
											if($TTK_CATEG == 'IR')
											{
												$sqlIRItemc		= "tbl_ir_detail A
																		INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																			AND C.PRJCODE = '$PRJCODE'
																	WHERE A.IR_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'";
												$CountsqlItem 	= $this->db->count_all($sqlIRItemc);
												// End count data
												
												// 1. Ambil detail IR
												$sqlIRItem		= "SELECT B.IR_NUM, B.IR_CODE, B.IR_DATE, B.IR_DUEDATE, B.PO_NUM, 
																		A.ITM_CODE, A.ITM_UNIT,
																		SUM(A.ITM_QTY) AS TOTQTY,
																		A.ITM_PRICE, A.ITM_DISP,
																		SUM(A.ITM_DISC) AS TOTDISC,
																		A.TAXCODE1,
																		SUM(A.ITM_TOTAL) AS ITM_TOTAL,
																		C.ITM_NAME, C.ACC_ID
																	FROM tbl_ir_detail A
																		INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																			AND C.PRJCODE = '$PRJCODE'
																	WHERE A.IR_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'
																	GROUP BY A.ITM_CODE";
												$ressqlIRItem 		= $this->db->query($sqlIRItem)->result();
											}
											else if($TTK_CATEG == 'OPN')
											{
												$sqlIRItemc		= "tbl_opn_detail A
																		INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																			AND C.PRJCODE = '$PRJCODE'
																	WHERE A.OPNH_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'";
												$CountsqlItem 	= $this->db->count_all($sqlIRItemc);
												// End count data
												
												// 1. Ambil detail IR
												$sqlIRItem		= "SELECT B.OPNH_NUM AS IR_NUM, B.OPNH_CODE AS IR_CODE,
																		B.OPNH_DATE AS IR_DATE, '' AS IR_DUEDATE, B.WO_NUM AS PO_NUM, 
																		A.ITM_CODE, A.ITM_UNIT,
																		SUM(A.OPND_VOLM) AS TOTQTY,
																		A.OPND_ITMPRICE AS ITM_PRICE, 0 AS ITM_DISP,
																		0 AS TOTDISC,
																		A.TAXCODE1, SUM(A.OPND_ITMTOTAL) AS ITM_TOTAL,
																		C.ITM_NAME, C.ACC_ID
																	FROM tbl_opn_detail A
																		INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																			AND C.PRJCODE = '$PRJCODE'
																	WHERE A.OPNH_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'
																	GROUP BY A.ITM_CODE";
												$ressqlIRItem 		= $this->db->query($sqlIRItem)->result();
											}
											else if($TTK_CATEG == 'OTH')
											{
												$sqlIRItemc		= "tbl_fpa_detail A
																		INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																			AND C.PRJCODE = '$PRJCODE'
																	WHERE A.FPA_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'";
												$CountsqlItem 	= $this->db->count_all($sqlIRItemc);
												// End count data
												
												// 1. Ambil detail IR
												$sqlIRItem		= "SELECT B.FPA_NUM AS IR_NUM, B.FPA_CODE AS IR_CODE,
																		B.FPA_DATE AS IR_DATE, '' AS IR_DUEDATE, GEJ_NUM AS PO_NUM, 
																		A.ITM_CODE, A.ITM_UNIT,
																		SUM(A.FPA_VOLM) AS TOTQTY,
																		A.ITM_PRICE AS ITM_PRICE, 0 AS ITM_DISP,
																		0 AS TOTDISC,
																		A.TAXCODE1, SUM(A.FPA_TOTAL) AS ITM_TOTAL,
																		C.ITM_NAME, C.ACC_ID
																	FROM tbl_fpa_detail A
																		INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																			AND C.PRJCODE = '$PRJCODE'
																	WHERE A.FPA_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'
																	GROUP BY A.ITM_CODE";
												$ressqlIRItem 		= $this->db->query($sqlIRItem)->result();
											}
											
											$totalAmount		= 0;
											$totalDiscAmount 	= 0;
											$totTaxPPnAmount	= 0;
											$totTaxPPhAmount	= 0;
											if($CountsqlItem > 0)
											{
												$TOT_AMOUNT1	= 0;
												$TOT_AMOUNT2	= 0;
												$TOT_DISC		= 0;
												$TOT_TAX1		= 0;
												$TOT_TAX2		= 0;
		                                    	$currentRow		= 0;
												$i				= 0;
												foreach($ressqlIRItem as $row) :
													$currentRow  	= ++$i;
													$IR_NUM 		= $row->IR_NUM;
													$PO_NUM			= $row->PO_NUM;
													$ITM_CODE 		= $row->ITM_CODE;
													$ITM_UNIT 		= $row->ITM_UNIT;
													$ITM_NAME 		= $row->ITM_NAME;
													$ACC_ID 		= $row->ACC_ID;
													$ITM_QTY		= $row->TOTQTY;
													$ITM_UNITP 		= $row->ITM_PRICE;
													$ITM_DISP 		= $row->ITM_DISP;
													$ITM_DISC 		= $row->TOTDISC;
													if($ITM_DISC == '')
														$ITM_DISC	= 0;
													$TAXCODE1 		= $row->TAXCODE1;
													$ITM_TOTAL 		= $row->ITM_TOTAL;
													$itemConvertion = 1;
													$PRJCODE 		= $PRJCODE;
													$RRSource 		= 'PO';
													$Ref_Number 	= $PO_NUM; // No PO apabila referensi IR ber resources PO
													$Disc_percentage= 0;
													$Disc_UnitPrice = 0;
													$Tax_Code1 		= '';
													$Tax_Code2 		= '';
													
													// GET ITEM AMOUNT AFTER DISCOUNT
													//$ITM_AMOUNT1	= $ITM_TOTAL - $ITM_DISC;
													$ITM_AMOUNT1	= ($ITM_QTY * $ITM_UNITP) - $ITM_DISC;
													
													// GET ITEM AMOUNT AFTER TAX											
													if($TAXCODE1 == 'TAX01')
													{
														$Tax_AmountPPn1	= $ITM_AMOUNT1 * 10 / 100;
														$Tax_AmountPPh1 = 0;
													}
													else if($TAXCODE1 == 'TAX02')
													{
														$Tax_AmountPPn1	= 0;
														$Tax_AmountPPh1 = (-1) * $ITM_AMOUNT1 * 3 / 100;
													}
													else
													{
														$Tax_AmountPPn1	= 0;
														$Tax_AmountPPh1 = 0;
													}
													$ITM_AMOUNT2		= $ITM_AMOUNT1 + $Tax_AmountPPn1 - $Tax_AmountPPh1;
													$ITM_TOT_AMOUNT		= $ITM_AMOUNT2;
													
													$TOT_AMOUNT1		= $TOT_AMOUNT1 + $ITM_AMOUNT1;	// Before +- Tax
													$TOT_AMOUNT2		= $TOT_AMOUNT2 + $ITM_AMOUNT2;	// After +- Tax
													$TOT_DISC			= $TOT_DISC + $ITM_DISC;
													$TOT_TAX2			= $TOT_TAX2 + $Tax_AmountPPh1;
													$TOT_TAX1			= $TOT_TAX1 + $Tax_AmountPPn1;
													$TOT_TAX2			= $TOT_TAX2 + $Tax_AmountPPh1;
													
													$ITM_CONV			= 1;	// Default
													
													// Deafult
													$Tax_AmountPPn2		= 0;
													$Tax_AmountPPh2		= 0;
													?>
													  <tr>
														  <td width="3%" height="25" style="text-align:left">
															  <?php echo $currentRow; ?>.
														  </td>
														  <td width="2%" style="text-align:left" nowrap>
															  <input type="hidden" id="data[<?php echo $currentRow; ?>][INV_NUM]" name="data[<?php echo $currentRow; ?>][INV_NUM]" value="<?php print $INV_NUM; ?>">
															<input type="hidden" id="data[<?php echo $currentRow; ?>][INV_CODE]" name="data[<?php echo $currentRow; ?>][INV_CODE]" value="<?php print $INV_CODE; ?>">
															<input type="hidden" id="data[<?php echo $currentRow; ?>][IR_NUM]" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php print $IR_NUM; ?>">
															<input type="hidden" id="data[<?php echo $currentRow; ?>][REF_NUM]" name="data[<?php echo $currentRow; ?>][REF_NUM]" value="<?php print $PO_NUM; ?>">
															<input type="hidden" id="data[<?php echo $currentRow; ?>][PRJCODE]" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>">
															  <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>">
															  <input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php print $ACC_ID; ?>">
															  <?php print $ITM_CODE; ?>
															  <!-- ITM_CODE -->
														  </td>
														  <td width="46%" style="text-align:left">
															  <?php print $ITM_NAME; ?>
															  <!-- ITM_NAME -->
														  </td>
														  <td width="8%" style="text-align:right">
															  <?php print number_format($ITM_QTY, 2); ?>
															  <input type="hidden" class="form-control" style="text-align:right; max-width:120px" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" size="15" disabled>
															  <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" size="6" value="<?php print $ITM_QTY; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" >
															  <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY2]" id="data<?php echo $currentRow; ?>ITM_QTY2" size="6" value="<?php print $ITM_QTY; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" >
															  <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CONV]" id="data<?php echo $currentRow; ?>ITM_CONV" size="6" value="<?php print $ITM_CONV; ?>" >
															  <!-- ITM_QTY, ITM_QTY2, ITM_CONV -->
														  </td>
														  <td width="2%" style="text-align:center">
															  <?php print $ITM_UNIT; ?>
															  <input type="hidden" id="data[<?php echo $currentRow; ?>][ITM_UNIT]" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
															  <!-- ITM_UNIT -->
														  </td>
														  <td width="11%" style="text-align:right">
															  <?php print number_format($ITM_TOTAL, 2); ?>
															  <input type="hidden" class="form-control"  style="text-align:right; min-width:100px; max-width:200px" name="ITM_UNITPX<?php echo $currentRow; ?>" id="ITM_UNITPX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" size="20" disabled >
															  <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNITP" name="data[<?php echo $currentRow; ?>][ITM_UNITP]" value="<?php print $ITM_UNITP; ?>">
															  <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNITP_BASE" name="data[<?php echo $currentRow; ?>][ITM_UNITP_BASE]" value="<?php print $ITM_UNITP; ?>">
															  <!-- ITM_UNITP, ITM_UNITP_BASE -->
														  </td>
														  <td width="4%" style="text-align:right; display:none" nowrap>
															<input type="hidden" style="text-align:right; min-width:70px; max-width:150px" name="ITM_DISP<?php echo $currentRow; ?>" id="ITM_DISP<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISP, $decFormat); ?>" class="form-control" onBlur="countDisp(this, <?php echo $currentRow; ?>);" />
															  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="data<?php echo $currentRow; ?>ITM_DISP" value="<?php print $ITM_DISP; ?>" />
															  <!-- ITM_DISP -->
														  </td>
														  <td width="4%" style="text-align:right; display:none" nowrap>
															  <input type="hidden" style="text-align:right; max-width:150px" name="ITM_DISCX<?php echo $currentRow; ?>" id="ITM_DISCX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISC, $decFormat); ?>" class="form-control" onBlur="countDisc(this, <?php echo $currentRow; ?>);" />
															  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="data<?php echo $currentRow; ?>ITM_DISC" value="<?php print $ITM_DISC; ?>" />
															  <!-- ITM_DISC -->
														</td>
														  <td width="9%" style="text-align:center; display:none">
															<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" onChange="changeValue(this, <?php echo $currentRow; ?>)" class="form-control" style="max-width:120px">
																  <option value="">--- None --</option>
																  <option value="TAX01"<?php if($TAXCODE1 == 'TAX01') { ; ?> selected <?php } ?>>PPn 10%</option>
																  <option value="TAX02"<?php if($TAXCODE1 == 'TAX02') { ; ?> selected <?php } ?>>PPh</option>
															  </select>
															  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn1]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPn1"value="<?php print $Tax_AmountPPn1; ?>" />
															  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPh1]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPh1"value="<?php print $Tax_AmountPPh1; ?>" />
															  <!-- TAXCODE1 -->
														  </td>
														  <td width="9%" style="text-align:center; display:none">
															  <select name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="data<?php echo $currentRow; ?>TAXCODE2" onChange="calcTax2(this.value)" class="listmenu">
																  <option value="">--- None --</option>
																  <option value="TAX01"<?php if($TAXCODE2 == 'TAX01') { ; ?> selected <?php } ?>>PPn 10%</option>
																  <option value="TAX02"<?php if($TAXCODE2 == 'TAX02') { ; ?> selected <?php } ?>>PPh</option>
															  </select>
															  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn2]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPn2"value="<?php print $Tax_AmountPPn2; ?>" />
															  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPh2]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPh1"value="<?php print $Tax_AmountPPh2; ?>" />
															  <!-- TAXCODE2 -->
														  </td>
														  <td width="2%" style="text-align:right; display:none">
															<input type="hidden" class="form-control" style="text-align:right;max-width:150px" name="ITM_AMOUNTX<?php echo $currentRow; ?>" id="ITM_AMOUNTX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOT_AMOUNT, $decFormat); ?>" />
															  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_AMOUNT]" id="data<?php echo $currentRow; ?>ITM_AMOUNT" value="<?php print $ITM_AMOUNT1; ?>" />
															  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_AMOUNT_BASE]" id="data<?php echo $currentRow; ?>ITM_AMOUNT_BASE" value="<?php print $ITM_AMOUNT1; ?>" />
															  <!-- ITM_AMOUNT, ITM_AMOUNT_BASE -->
														  </td>
													  </tr>
												  <?php
												endforeach;
											}
		                                    
		                                    /*if($task == 'add')
		                                    {*/
		                                        ?>
		                                          <input type="hidden" name="PO_NUM" id="PO_NUM" value="<?php echo $PO_NUM; ?>">
		                                      <?php
		                                    /*}*/
		                                    ?>                                    
		                                </table>
	                                </div>
	                            </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
	                        <?php
								if($task=='add')
								{
									if($INV_STAT == 1 && $ISCREATE == 1 && $disButton == 0)
									{
										?>
											<button class="btn btn-primary" id="btnSave">
											<i class="fa fa-save"></i></button>&nbsp;
										<?php
									}
								}
								else
								{
									if($disButton == 0)
									{
										?>
	                                        <button class="btn btn-primary" id="btnSave">
	                                        <i class="fa fa-save"></i></button>&nbsp;
										<?php
									}
								}
								
								$backURL	= site_url('c_purchase/c_pi180c23/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
	                        ?>
                        </div>
                    </div>
                </form>
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
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('#datepicker1').datepicker({
      autoclose: true,
	  endDate: '+0d'
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

	function add_DP(strItem) 
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		arrItem 	= strItem.split('~');
		DP_NUM 		= arrItem[0];
		DP_AMOUNT	= arrItem[1];
		document.getElementById("DP_NUM").value 	= DP_NUM;
		document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT)),decFormat));
		document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT));
	}

	function add_header(IR_NUM) 
	{
		document.getElementById("TTK_NUM1").value = IR_NUM;
		document.frmsrch.submitSrch.click();
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISP		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('ITM_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISP)),decFormat));
		document.getElementById('data'+row+'ITM_DISP').value 	= parseFloat(Math.abs(ITM_DISP));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP		= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		var DISCOUNT		= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('data'+row+'ITM_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP		= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		
		var DISCOUNTP		= parseFloat(ITM_DISC / ITM_TOTAL * 100);
		
		document.getElementById('ITM_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		document.getElementById('data'+row+'ITM_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		
		document.getElementById('data'+row+'ITM_DISC').value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function changeValue(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		//var ITM_QTYx 		= eval(thisVal).value.split(",").join("");
		ITM_QTY1			= document.getElementById('ITM_QTY'+row);
		ITM_QTY 			= parseFloat(eval(ITM_QTY1).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_QTY').value = parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		//var ITM_DISP			= document.getElementById('ITM_DISP'+row).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP			= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		
		var DISCOUNT			= parseFloat(document.getElementById('data'+row+'ITM_DISC').value);
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		var theTAX				= document.getElementById('data'+row+'TAXCODE1').value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= 0;
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= 0;
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= 0;
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= 0;
		}
		document.getElementById('data'+row+'ITM_AMOUNT').value 		= parseFloat(Math.abs(G_itmTot));
		document.getElementById('data'+row+'ITM_AMOUNT_BASE').value = parseFloat(Math.abs(G_itmTot));
		document.getElementById('ITM_AMOUNTX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow		= document.getElementById("totalrow").value;
		INV_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('data'+i+'ITM_AMOUNT').value;
			INV_TOTAL_AM	= parseFloat(INV_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		
		document.getElementById('INV_AMOUNT').value = INV_TOTAL_AM;
	}
	
	function checkTotalTTK()
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		totalrow		= document.getElementById('totalrow').value;
		TTK_TOT_AM		= 0;
		TTK_TOT_RET		= 0;
		TTK_TOT_POT		= 0;
		TTK_TOT_PPN		= 0;
		TTK_GTOTAL		= 0;
		for (i = 1; i <= totalrow; i++) 
		{
			TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
			TTK_REF1_RET	= document.getElementById('TTK_REF1_RET'+i).value;
			TTK_REF1_POT	= document.getElementById('TTK_REF1_POT'+i).value;
			TTK_REF1_PPN	= document.getElementById('TTK_REF1_PPN'+i).value;
			TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
			TTK_TOT_RET		= parseFloat(TTK_TOT_RET) + parseFloat(TTK_REF1_RET);
			TTK_TOT_POT		= parseFloat(TTK_TOT_POT) + parseFloat(TTK_REF1_POT);
			TTK_TOT_PPN		= parseFloat(TTK_TOT_PPN) + parseFloat(TTK_REF1_PPN);
		}
		if(TTK_TOT_RET == 0)
			var TTK_TOT_RET 	= document.getElementById('INV_AMOUNT_RET').value;

		if(TTK_TOT_POT == 0)
			var TTK_TOT_POT 	= document.getElementById('INV_AMOUNT_POT').value;

		if(TTK_TOT_PPN == 0)
			var TTK_TOT_PPN 	= document.getElementById('INV_LISTTAXVAL').value;

		var INV_AMOUNT_OTH 		= document.getElementById('INV_AMOUNT_OTH').value;

		TTK_GTOTAL				= parseFloat(TTK_TOT_AM) + parseFloat(TTK_TOT_PPN) + parseFloat(INV_AMOUNT_OTH) - parseFloat(TTK_TOT_RET) - parseFloat(TTK_TOT_POT);

		// HASIL MEETING 27 DES 18 DI MS, RETENSI TIDAK TERMASUK DI INV, HANYA INFORMASI
			// HOLDED ON 11 OKT 2020
			/*INV_CATEG		= document.getElementById('INV_CATEG').value;
			if(INV_CATEG == 'OPN')
				TTK_GTOTAL			= parseFloat(TTK_TOT_AM) - parseFloat(TTK_TOT_POT);
			else
				TTK_GTOTAL			= parseFloat(TTK_TOT_AM) - parseFloat(TTK_TOT_POT);	// AWALNYA  + parseFloat(TTK_TOT_PPN)*/
			
		var INV_PPHVAL		= parseFloat(document.getElementById('INV_PPHVAL').value);
		
		// PERMINTAAN PAK ANDI 9 JAN 2019, YANG DITAMPILKAN ADALAH HASIL TOTAL + PPN - PPH - RETENSI;
			// HOLDED ON 11 OKT 2020
			/*INV_AMOUNT_OTH		= document.getElementById('INV_AMOUNT_OTH').value;
			TTK_TOT_AM1			= parseFloat(TTK_GTOTAL) - parseFloat(INV_PPHVAL);
			TTK_TOT_AM1V		= parseFloat(TTK_GTOTAL) + parseFloat(TTK_TOT_PPN) + parseFloat(INV_AMOUNT_OTH) - parseFloat(TTK_TOT_RET) - parseFloat(INV_PPHVAL);*/
		
		//document.getElementById('INV_AMOUNT').value 	= TTK_TOT_AM1;
		document.getElementById('INV_AMOUNT').value 	= TTK_GTOTAL;
		document.getElementById('INV_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_TOT_AM1V)),decFormat));
		
		document.getElementById('INV_AMOUNT_RET').value 	= TTK_TOT_RET;
		document.getElementById('INV_AMOUNT_RETX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_TOT_RET)),decFormat));
		
		document.getElementById('INV_AMOUNT_POT').value 	= TTK_TOT_POT;
		document.getElementById('INV_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_TOT_POT)),decFormat));
		
		document.getElementById('INV_LISTTAXVAL').value 	= TTK_TOT_PPN;
		document.getElementById('INV_LISTTAXVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_TOT_PPN)),decFormat));
	}
	
	function checkForm()
	{
		SPLCODE	= document.getElementById('SPLCODE').value;
		if(SPLCODE == 'none')
		{
			swal('<?php echo $selSpl; ?>',
			{
				icon: "warning",
			});
			document.getElementById('SPLCODE').focus();
			return false;
		}

		TTK_CODE	= document.getElementById('TTK_CODE').value;
		if(TTK_CODE == '')
		{
			swal('<?php echo $selNoRef; ?>',
			{
				icon: "warning",
			});
			document.getElementById('TTK_CODE').focus();
			return false;
		}

		INV_STAT		= document.getElementById("INV_STAT").value;
		if(INV_STAT == 6)
		{
			INV_NOTES1		= document.getElementById('INV_NOTES1').value;
			if(INV_NOTES1 == '')
			{
				swal('<?php echo $alert3; ?>',
				{
					icon: "warning",
				});
				document.getElementById('INV_NOTES1').focus();
				return false;
			}
		}
		
		chkTotal	= document.getElementById('chkTotal').checked;
		if(chkTotal == false)
		{
			swal('<?php echo $alert1; ?>',
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