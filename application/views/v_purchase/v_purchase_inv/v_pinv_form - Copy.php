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
	$DocNumber 	= "$Pattern_Code$groupPattern-$lastPatternNumb";
	$INV_NUM	= $DocNumber;
	$PO_NUM		= '';
	$IR_NUM		= '';
	
	$VOCCODE	= substr($lastPatternNumb, -4);
	$VOCYEAR	= date('y');
	$VOCMONTH	= date('m');
	$INV_CODE	= "VOC.$VOCCODE.$VOCYEAR.$VOCMONTH"; // MANUAL CODE
	$INV_DATE 	= date('d/m/Y');

	$Patt_Year 	= date('Y');
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

	$DP_NUM				= "";
	$DP_AMOUNT			= 0;
	$INV_AMOUNT 		= 0;
	$INV_AMOUNT_PPN		= 0;
	$INV_AMOUNT_PPH		= 0;
	$INV_AMOUNT_DPB		= 0;
	$INV_AMOUNT_RET		= 0;
	$INV_AMOUNT_POT		= 0;
	$INV_AMOUNT_OTH		= 0;
	$INV_AMOUNT_TOT 	= 0;
	$INV_AMOUNT_PAID 	= 0;
	$INV_ACC_OTH		= "";
	$PPH_PERC 			= 0;
	$INV_TERM			= 30;
	$INV_LISTTAX		= 0;
	$INV_LISTTAXVAL		= 0;
	$INV_PPH			= '';
	$INV_PPHVAL			= 0;
	$INV_STAT			= 1;
	$VENDINV_NUM		= "";
	$INV_NOTES 			= "";
	$INV_NOTES1 		= "";
	$TTK_NUM1			= "";
	
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
			if($TranslCode == 'Address')$Address = $LangTransl;
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
			if($TranslCode == 'TTKList')$TTKList = $LangTransl;
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
			if($TranslCode == 'TTKList')$TTKList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'TTKNumber')$TTKNumber = $LangTransl;
			if($TranslCode == 'OthExp')$OthExp = $LangTransl;
			if($TranslCode == 'selTTK')$selTTK = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
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
		                          	<div class="col-sm-5">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="INV_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $INV_DATE; ?>" style="width:120px">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-4">
		                          		<label for="inputName" class="col-sm-12 control-label"><?php echo $DueDate; ?></label>
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
		                          	<div class="col-sm-5">
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
		                          	<div class="col-sm-4">
                        				<div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="INV_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $INV_DUEDATE; ?>">
		                                </div>
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Address; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea name="SPLADD1" id="SPLADD1" cols="50" class="form-control"  style="height:85px"><?php echo $SPLADD1; ?></textarea>
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
		                                <textarea class="form-control" name="INV_NOTES"  id="INV_NOTES" style="height:80px"><?php echo $INV_NOTES; ?></textarea>
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
		                       	  <label for="inputName" class="col-sm-3 control-label"><?php echo $SuplInvNo; ?></label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="VENDINV_NUM" id="VENDINV_NUM" value="<?php echo $VENDINV_NUM; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Cek Total</label>
		                            <div class="col-sm-5">
		                                <div class="input-group">
		                                    <span class="input-group-addon">
		                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalTTK()">
		                                    </span>
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT" id="INV_AMOUNT" value="<?php echo $INV_AMOUNT; ?>" >
		                                    <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNTX" id="INV_AMOUNTX" value="<?php echo number_format($INV_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" >
		                        		</div>
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_PPN" id="INV_AMOUNT_PPN" value="<?php echo $INV_AMOUNT_PPN; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_PPNX" id="INV_AMOUNT_PPNX" value="<?php echo number_format($INV_AMOUNT_PPN, 2); ?>" onBlur="countAmn_ppn(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">PPh</label>
		                            <div class="col-sm-5">
		                                <select name="INV_PPH" id="INV_PPH" class="form-control select2" style="max-width:190px" onChange="selPPh(this.value)">
		                                	<option value=""> --- </option>
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
													<option value="<?php echo "$TAXLA_NUM"; ?>"<?php if($INV_PPH1 == $INV_PPH) { ?> selected <?php } ?>><?php echo "$TAXLA_CODE"; ?></option>
													<?php
												endforeach;
											?>
		                                </select>
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="PPH_PERC" id="PPH_PERC" value="<?php echo $PPH_PERC; ?>" >
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_PPH" id="INV_AMOUNT_PPH" value="<?php echo $INV_AMOUNT_PPH; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_PPHX" id="INV_AMOUNT_PPHX" value="<?php echo number_format($INV_AMOUNT_PPH, 2); ?>" onBlur="countAmn_pph(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                        </div>
			                	<?php
			                		$secTax = base_url().'index.php/c_purchase/c_pi180c23/getTax/?id=';
			                	?>
		                        <script>
									function selPPh(thisVal)
									{
										var url 	= "<?php echo $secTax; ?>";
								        $.ajax({
								            type: 'POST',
								            url: url,
								            data: {taxCode: thisVal},
								            success: function(response)
								            {
										        PPH_PERC 		= parseFloat(response);
												document.getElementById('PPH_PERC').value 	= PPH_PERC;

										        INV_AMOUNT 		= document.getElementById('INV_AMOUNT').value;
										        INV_AMOUNT_PPN 	= document.getElementById('INV_AMOUNT_PPN').value;
										        INV_AMOUNT_RET 	= document.getElementById('INV_AMOUNT_RET').value;
										        INV_AMOUNT_POT 	= document.getElementById('INV_AMOUNT_POT').value;
												INV_AMOUNT_OTH 	= document.getElementById('INV_AMOUNT_OTH').value;
										        INV_AMOUNT_DPB 	= document.getElementById('INV_AMOUNT_DPB').value;
										        
										        GTOTAL_AMN 		= parseFloat(INV_AMOUNT) + parseFloat(INV_AMOUNT_PPN) - parseFloat(INV_AMOUNT_RET) - parseFloat(INV_AMOUNT_POT) + parseFloat(INV_AMOUNT_OTH) - parseFloat(INV_AMOUNT_DPB);

										        PPH_AMN 		= parseFloat(PPH_PERC) * parseFloat(GTOTAL_AMN) / 100;

												document.getElementById('INV_AMOUNT_PPH').value 	= PPH_AMN;
												document.getElementById('INV_AMOUNT_PPHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_AMN)),2));
								            }
								        });
									}

									function countAmn_ppn(thisVal)
									{
		                        		var INV_PPN 	= eval(thisVal).value.split(",").join("");
										document.getElementById('INV_AMOUNT_PPN').value 	= INV_PPN;
										document.getElementById('INV_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPN)),2));

										countAmn();
									}

									function countAmn_pph(thisVal)
									{
		                        		var INV_PPH 	= eval(thisVal).value.split(",").join("");
										document.getElementById('INV_AMOUNT_PPH').value 	= INV_PPH;
										document.getElementById('INV_AMOUNT_PPHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPH)),2));

										countAmn();
									}

									function countAmn_ret(thisVal)
									{
		                        		var INV_RET 	= eval(thisVal).value.split(",").join("");
										document.getElementById('INV_AMOUNT_RET').value 	= INV_RET;
										document.getElementById('INV_AMOUNT_RETX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_RET)),2));

										countAmn();
									}

									function countAmn_pot(thisVal)
									{
		                        		var INV_POT 	= eval(thisVal).value.split(",").join("");
										document.getElementById('INV_AMOUNT_POT').value 	= INV_POT;
										document.getElementById('INV_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_POT)),2));

										countAmn();
									}

									function countAmn_oth(thisVal)
									{
		                        		var INV_OTH 	= eval(thisVal).value.split(",").join("");
										document.getElementById('INV_AMOUNT_OTH').value 	= INV_OTH;
										document.getElementById('INV_AMOUNT_OTHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_OTH)),2));

										countAmn();
									}

									function countAmn_dpb(thisVal)
									{
		                        		var INV_DPB 	= eval(thisVal).value.split(",").join("");
										document.getElementById('INV_AMOUNT_DPB').value 	= INV_DPB;
										document.getElementById('INV_AMOUNT_DPBX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_DPB)),2));

										countAmn();
									}
								</script>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Retensi / Pot.</label>
		                            <div class="col-sm-5">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_RET" id="INV_AMOUNT_RET" value="<?php echo $INV_AMOUNT_RET; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_RETX" id="INV_AMOUNT_RETX" value="<?php echo number_format($INV_AMOUNT_RET, 2); ?>" onBlur="countAmn_ret(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_POT" id="INV_AMOUNT_POT" value="<?php echo $INV_AMOUNT_POT; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_POTX" id="INV_AMOUNT_POTX" value="<?php echo number_format($INV_AMOUNT_POT, 2); ?>" onBlur="countAmn_pot(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                        </div>
								<?php
		                            $sqlC0a		= "tbl_chartaccount WHERE Account_Category IN (5,6,7,8) AND PRJCODE = '$PRJCODE'";
		                            $resC0a 	= $this->db->count_all($sqlC0a);
		                            
		                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
														Acc_DirParent, isLast
		                                            FROM tbl_chartaccount WHERE Account_Category IN (5,6,7,8) AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
		                            $resC0b 	= $this->db->query($sqlC0b)->result();
		                        ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $OthExp; ?></label>
		                            <div class="col-sm-9">
		                            	<div class="row">
		                                    <div class="col-xs-4">
		                                    	<input type="hidden" class="form-control" style="text-align:right" name="INV_AMOUNT_OTH" id="INV_AMOUNT_OTH" value="<?php echo $INV_AMOUNT_OTH; ?>" >
		                                		<input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_OTHX" id="INV_AMOUNT_OTHX" value="<?php echo number_format($INV_AMOUNT_OTH, 2); ?>" onBlur="countAmn_oth(this)" onKeyPress="return isIntOnlyNew(event);" >
		                                    </div>
		                                    <div class="col-xs-8">
		                                        <select name="INV_ACC_OTH" id="INV_ACC_OTH" class="form-control select2">
				                        			<option value=""> --- </option>
				                                    <?php
													if($resC0a>0)
													{
														foreach($resC0b as $rowC0b) :
															$Acc_ID0		= $rowC0b->Acc_ID;
															$Account_Number0= $rowC0b->Account_Number;
															$Acc_DirParent0	= $rowC0b->Acc_DirParent;
															$Account_Level0	= $rowC0b->Account_Level;
															if($LangID == 'IND')
															{
																$Account_Name0	= $rowC0b->Account_NameId;
															}
															else
															{
																$Account_Name0	= $rowC0b->Account_NameEn;
															}
															
															$Acc_ParentList0	= $rowC0b->Acc_ParentList;
															$isLast_0			= $rowC0b->isLast;
															$disbaled_0			= 0;
															if($isLast_0 == 0)
																$disbaled_0		= 1;
																
															if($Account_Level0 == 0)
																$level_coa1			= "";
															elseif($Account_Level0 == 1)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 2)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 3)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 4)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 5)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 6)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 7)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															
															$collData0	= "$Account_Number0";
															?>
															<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $INV_ACC_OTH) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
															<?php
														endforeach;
													}
													?>
		                                        </select>
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">P. UM / G. Total</label>
		                            <div class="col-sm-3">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_DPB" id="INV_AMOUNT_DPB" value="<?php echo $INV_AMOUNT_DPB; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_DPBX" id="INV_AMOUNT_DPBX" value="<?php echo number_format($INV_AMOUNT_DPB, 2); ?>" onBlur="countAmn_dpb(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                            <div class="col-sm-6">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT_TOT" id="INV_AMOUNT_TOT" value="<?php echo $INV_AMOUNT_TOT; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="INV_AMOUNT_TOTX" id="INV_AMOUNT_TOTX" value="<?php echo number_format($INV_AMOUNT_TOT, 2); ?>" onKeyPress="return isIntOnlyNew(event);" readonly >
		                            </div>
		                        </div>
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
		                          	<div class="col-sm-6">
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
			                        <?php
										$theProjCode 	= "$PRJCODE~$SPLCODE1";
										$url_TTKList	= site_url('c_purchase/c_pi180c23/get_AllDataTTKL/?id='.$this->url_encryption_helper->encode_url($theProjCode));
										
										if($SPLCODE1 != '0')
										{
											?>
												<div class="col-sm-3">
													<script>
														var url = "<?php echo $url_TTKList;?>";
														function selectitem()
														{
															document.getElementById('btnModal').click();
														}
													</script>
													<button class="btn btn-warning" type="button" onClick="selectitem();" style="display: none;">
														<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $selTTK; ?>
													</button>
													<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addTTK" id="btnModal" >
						                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $selTTK; ?>
						                        	</a>
												</div>
											<?php
										}
									?>
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
							</div>
						</div>
					</div>
					<div class="col-md-12">	
                        <div class="row">
                            <div class="col-md-12">
                              	<div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $TTKList; ?></h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove" style="display: none;"><i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
	                                    <table id="tbl" class="table table-bordered table-striped" width="100%">
		                                    <tr style="background:#CCCCCC">
		                                        <th width="2%" height="25" style="text-align:center">No.</th>
		                                        <th width="10%" style="text-align:center" nowrap>Kode</th>
		                                        <th width="5%" style="text-align:center" nowrap><?php echo $Date; ?></th>
		                                        <th width="15%" style="text-align:center" nowrap><?php echo $Description; ?></th>
		                                        <th width="10%" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></th>
		                                        <th width="10%" style="text-align:center" nowrap>PPn</th>
		                                        <th width="10%" style="text-align:center" nowrap>PPh</th>
		                                        <th width="10%" style="text-align:center" nowrap>P. UM</th>
		                                        <th width="8%" style="text-align:center" nowrap>Retensi</th>
		                                        <th width="10%" style="text-align:center" nowrap>Potongan</th>
		                                        <th width="10%" style="text-align:center" nowrap><?php echo $TotAmount; ?></th>
		                                    </tr>
		                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                </table>
		                            </div>
	                            </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
						<div class="box-header with-border" style="display: none;">
							<i class="fa fa-cloud-upload"></i>
							<h3 class="box-title">&nbsp;</h3>
						</div>
						<div class="box-body">
	                        <div class="form-group">
	                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                          	<div class="col-sm-9">
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
	                      </div>
	                  	</div>
		            </div>
                </form>
		    </div>

		    <?php
		    	if($SPLCODE1 != '')
		    	{
		    		$SPLDESC 	= "";
		    		$sqlSPL 	= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE1'";
                    $resSPL 	= $this->db->query($sqlSPL)->result();
					foreach($resSPL as $rowSPL) :
						$SPLDESC 	= $rowSPL->SPLDESC;
					endforeach;
		    	?>
			    	<!-- ============ START MODAL TTK LIST =============== -->
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
							$Active1Cls		= "class='active'";
							$Active2Cls		= "";
				    	?>
				        <div class="modal fade" id="mdl_addTTK" name='mdl_addTTK' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				            <div class="modal-dialog">
					            <div class="modal-content">
					                <div class="modal-body">
										<div class="row">
									    	<div class="col-md-12">
								              	<ul class="nav nav-tabs">
								                    <li id="li1" <?php echo $Active1Cls; ?>>
								                    	<a href="#itm1" data-toggle="tab"><?php echo $TTKList." - ".$SPLDESC; ?></a>
								                    </li>
								                </ul>
									            <div class="box-body">
									            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
				                                        <div class="form-group">
				                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
					                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					                                                <thead>
					                                                    <tr>
													                        <th width="3%">&nbsp;</th>
														                    <th width="8%" style="vertical-align:middle; text-align:center" nowrap><?php echo $TTKNumber; ?></th>
														                    <th width="7%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
														                    <th width="8%" nowrap style="text-align:center"><?php echo $DueDate; ?></th>
															                <th width="63%" nowrap style="text-align:center"><?php echo $Description; ?></th>
															                <th width="11%" nowrap style="text-align:center"><?php echo $TotAmount; ?></th>
													                  	</tr>
					                                                </thead>
					                                                <tbody>
					                                                </tbody>
					                                            </table>
		                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
		                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
		                                                    	</button>&nbsp;
		                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
		                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
		                                                    	</button>
				                                            </form>
				                                      	</div>
				                                    </div>
		                                      	</div>
		                                      	<input type="text" name="rowCheck0" id="rowCheck0" value="0">
			                                </div>
			                            </div>
					                </div>
						        </div>
						    </div>
						</div>

						<script type="text/javascript">
							$(document).ready(function()
							{
						    	$('#example0').DataTable(
						    	{
							        "processing": true,
							        "serverSide": true,
									//"scrollX": false,
									"autoWidth": true,
									"filter": true,
							        "ajax": "<?php echo site_url('c_purchase/c_pi180c23/get_AllDataTTKL/?id='.$collID)?>",
							        "type": "POST",
									//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
									"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
									"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
													{ "width": "2px", "targets": [0] },
													{ "width": "98px", "targets": [1] }
												  ],
									"language": {
							            "infoFiltered":"",
							            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
							        },
								});
							});

							var selectedRows = 0;
							function pickThis0(thisobj) 
							{
								var favorite = [];
								$.each($("input[name='chk0']:checked"), function() {
							      	favorite.push($(this).val());
							    });
							    $("#rowCheck0").val(favorite.length);
							}

							$(document).ready(function()
							{
							   	$("#btnDetail0").click(function()
							    {
									var totChck 	= $("#rowCheck0").val();
									
									if(totChck == 0)
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

								    $.each($("input[name='chk0']:checked"), function()
								    {
								      	add_DETIL($(this).val());
								    });

								    $('#mdl_addTTK').on('hidden.bs.modal', function () {
									    $(this)
										    .find("input,textarea,select")
											    .val('')
											    .end()
										    .find("input[type=checkbox], input[type=radio]")
										       .prop("checked", "")
										       .end();
									});
		                        	document.getElementById("idClose0").click()
							    });
							});
						</script>
			    	<!-- ============ END MODAL TTK LIST =============== -->
	            <?php
	       		}
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
	
	function add_DETIL(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		ilvl = arrItem[1];
		
		var INV_NUM		= '<?php echo $INV_NUM; ?>';

		TTK_NUM			= arrItem[0];	//
		TTK_CODE		= arrItem[1];	//
		TTK_DATE		= arrItem[2];	//
		TTK_DUEDATE		= arrItem[3];	//
		TTK_NOTES		= arrItem[4];	//
		TTK_CATEG		= arrItem[5];	//
		TTK_AMOUNT		= arrItem[6];	//
		TTK_AMOUNT_PPN	= arrItem[7];	//
		TTK_AMOUNT_PPH	= arrItem[8];	//
		TTK_AMOUNT_DPB	= arrItem[9];	//
		TTK_AMOUNT_RET	= arrItem[10];	//
		TTK_AMOUNT_POT	= arrItem[11];	//
		TTK_AMOUNT_OTH	= arrItem[12];	//
		TTK_GTOTAL		= arrItem[13];	//
		PRJCODE			= arrItem[14];	//
		SPLCODE			= arrItem[15];	//

		ITM_CODE 		= "";
		ACC_ID 			= "";
		ITM_UNIT 		= "";
		ITM_QTY 		= 1;
		ITM_UNITP 		= TTK_AMOUNT;
		ITM_UNITP_BASE 	= TTK_AMOUNT;
		ITM_AMOUNT 		= TTK_AMOUNT;
		ITM_AMOUNT_PPN 	= TTK_AMOUNT_PPN;
		ITM_AMOUNT_PPH 	= TTK_AMOUNT_PPH;
		ITM_AMOUNT_DPB 	= TTK_AMOUNT_DPB;
		ITM_AMOUNT_RET 	= TTK_AMOUNT_RET;
		ITM_AMOUNT_POT 	= TTK_AMOUNT_POT;
		ITM_AMOUNT_TOT 	= TTK_GTOTAL;
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		
		console.log('b')
		intIndex = parseInt(objTable.rows.length);
		document.frm.rowCount.value = intIndex;
		
		objTR 		= objTable.insertRow(intTable);
		objTR.id 	= 'tr_' + intIndex;
		console.log('c')
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';

		console.log('d')
		// INV_NUM
			objTD = objTR.insertCell(objTR.cells.length);
				objTD.align = "center";
				//objTD.noWrap = true;
				objTD.innerHTML = ''+TTK_CODE+'<input type="hidden" id="data'+intIndex+'INV_NUM" name="data['+intIndex+'][INV_NUM]" value="'+INV_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+TTK_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_NUM" name="data['+intIndex+'][TTK_NUM]" value="'+TTK_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_CODE" name="data['+intIndex+'][TTK_CODE]" value="'+TTK_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" class="form-control" style="max-width:300px;">';
			
		console.log('d1')
		// TTK DATE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = TTK_DATE;
		
		console.log('d2')
		// TTK DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'left';
			//objTD.noWrap = true;
			objTD.innerHTML = TTK_NOTES;
		
		console.log('e')
		// TTK AMOUNT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT)),2))+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+ACC_ID+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="TTKD" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_QTY" name="data['+intIndex+'][ITM_QTY]" value="'+ITM_QTY+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_UNITP" name="data['+intIndex+'][ITM_UNITP]" value="'+ITM_UNITP+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_UNITP_BASE" name="data['+intIndex+'][ITM_UNITP_BASE]" value="'+ITM_UNITP_BASE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_AMOUNT" name="data['+intIndex+'][ITM_AMOUNT]" value="'+ITM_AMOUNT+'" class="form-control" style="max-width:300px;">';
		
		// TTK PPN
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT_PPN)),2))+'<input type="hidden" id="data'+intIndex+'ITM_AMOUNT_PPN" name="data['+intIndex+'][ITM_AMOUNT_PPN]" value="'+ITM_AMOUNT_PPN+'" class="form-control" style="min-width:100px;">';
		
		console.log('f')
		// TTK PPH
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT_PPH)),2))+'<input type="hidden" id="data'+intIndex+'ITM_AMOUNT_PPH" name="data['+intIndex+'][ITM_AMOUNT_PPH]" value="'+ITM_AMOUNT_PPH+'" class="form-control" style="min-width:100px;">';
		
		// TTK DPB
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT_DPB)),2))+'<input type="hidden" id="data'+intIndex+'ITM_AMOUNT_DPB" name="data['+intIndex+'][ITM_AMOUNT_DPB]" value="'+ITM_AMOUNT_DPB+'" class="form-control" style="min-width:100px;">';
		
		console.log('g')
		// TTK RET
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT_RET)),2))+'<input type="hidden" id="data'+intIndex+'ITM_AMOUNT_RET" name="data['+intIndex+'][ITM_AMOUNT_RET]" value="'+ITM_AMOUNT_RET+'" class="form-control" style="min-width:100px;">';
		
		console.log('h')
		// TTK POT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT_POT)),2))+'<input type="hidden" id="data'+intIndex+'ITM_AMOUNT_POT" name="data['+intIndex+'][ITM_AMOUNT_POT]" value="'+ITM_AMOUNT_POT+'" class="form-control" style="min-width:100px; text-align:right;">';
		
		console.log('j = '+ITM_AMOUNT_TOT)
		// TTK GTOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AMOUNT_TOT)),2))+'<input type="hidden" id="data'+intIndex+'ITM_AMOUNT_TOT" name="data['+intIndex+'][ITM_AMOUNT_TOT]" value="'+ITM_AMOUNT_TOT+'" class="form-control" style="min-width:100px; text-align:right;">';
		
		document.getElementById('totalrow').value = intIndex;
	}
	
	function checkTotalTTK()
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		totalrow		= document.getElementById('totalrow').value;
		INV_TOT_AMN		= 0;
		INV_TOT_PPN		= 0;
		INV_TOT_PPH		= 0;
		INV_TOT_DPB		= 0;
		INV_TOT_RET		= 0;
		INV_TOT_POT		= 0;
		INV_TOT_GTOTAL	= 0;
		console.log('a')
		for (i = 1; i <= totalrow; i++) 
		{
			ITM_AMOUNT		= document.getElementById('data'+i+'ITM_AMOUNT').value;
			ITM_AMOUNT_PPN	= document.getElementById('data'+i+'ITM_AMOUNT_PPN').value;
			ITM_AMOUNT_PPH	= document.getElementById('data'+i+'ITM_AMOUNT_PPH').value;
			ITM_AMOUNT_DPB	= document.getElementById('data'+i+'ITM_AMOUNT_DPB').value;
			ITM_AMOUNT_RET	= document.getElementById('data'+i+'ITM_AMOUNT_RET').value;
			ITM_AMOUNT_POT	= document.getElementById('data'+i+'ITM_AMOUNT_POT').value;
			ITM_AMOUNT_TOT	= document.getElementById('data'+i+'ITM_AMOUNT_TOT').value;

			INV_TOT_AMN 	= parseFloat(INV_TOT_AMN) + parseFloat(ITM_AMOUNT);
			INV_TOT_PPN 	= parseFloat(INV_TOT_PPN) + parseFloat(ITM_AMOUNT_PPN);
			INV_TOT_PPH 	= parseFloat(INV_TOT_PPH) + parseFloat(ITM_AMOUNT_PPH);
			INV_TOT_DPB 	= parseFloat(INV_TOT_DPB) + parseFloat(ITM_AMOUNT_DPB);
			INV_TOT_RET 	= parseFloat(INV_TOT_RET) + parseFloat(ITM_AMOUNT_RET);
			INV_TOT_POT 	= parseFloat(INV_TOT_POT) + parseFloat(ITM_AMOUNT_POT);
			INV_TOT_GTOTAL 	= parseFloat(INV_TOT_GTOTAL) + parseFloat(ITM_AMOUNT_TOT);
		}
		console.log('b')
		
		document.getElementById('INV_AMOUNT').value 		= INV_TOT_AMN;
		document.getElementById('INV_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_AMN)),decFormat));
		
		document.getElementById('INV_AMOUNT_PPN').value 	= INV_TOT_PPN;
		document.getElementById('INV_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_PPN)),decFormat));
		
		document.getElementById('INV_AMOUNT_PPH').value 	= INV_TOT_PPH;
		document.getElementById('INV_AMOUNT_PPHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_PPH)),decFormat));
		
		document.getElementById('INV_AMOUNT_DPB').value 	= INV_TOT_DPB;
		document.getElementById('INV_AMOUNT_DPBX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_DPB)),decFormat));
		
		document.getElementById('INV_AMOUNT_RET').value 	= INV_TOT_RET;
		document.getElementById('INV_AMOUNT_RETX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_RET)),decFormat));
		
		document.getElementById('INV_AMOUNT_POT').value 	= INV_TOT_POT;
		document.getElementById('INV_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_POT)),decFormat));
		
		document.getElementById('INV_AMOUNT_TOT').value 	= INV_TOT_GTOTAL;
		document.getElementById('INV_AMOUNT_TOTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_TOT_GTOTAL)),decFormat));
	}

	function countAmn()
	{
        INV_AMOUNT 		= document.getElementById('INV_AMOUNT').value;
        INV_AMOUNT_PPN 	= document.getElementById('INV_AMOUNT_PPN').value;
        INV_AMOUNT_PPH 	= document.getElementById('INV_AMOUNT_PPH').value;
        INV_AMOUNT_RET 	= document.getElementById('INV_AMOUNT_RET').value;
        INV_AMOUNT_POT 	= document.getElementById('INV_AMOUNT_POT').value;
		INV_AMOUNT_OTH 	= document.getElementById('INV_AMOUNT_OTH').value;
        INV_AMOUNT_DPB 	= document.getElementById('INV_AMOUNT_DPB').value;
        
        GTOTAL_AMN 		= parseFloat(INV_AMOUNT) + parseFloat(INV_AMOUNT_PPN) - parseFloat(INV_AMOUNT_PPH) - parseFloat(INV_AMOUNT_RET) - parseFloat(INV_AMOUNT_POT) + parseFloat(INV_AMOUNT_OTH) - parseFloat(INV_AMOUNT_DPB);

		document.getElementById('INV_AMOUNT_TOT').value 	= GTOTAL_AMN;
		document.getElementById('INV_AMOUNT_TOTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTAL_AMN)),2));
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

	/*function add_DP(strItem) 
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		arrItem 	= strItem.split('~');
		DP_NUM 		= arrItem[0];
		DP_AMOUNT	= arrItem[1];
		document.getElementById("DP_NUM").value 	= DP_NUM;
		document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT)),decFormat));
		document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT));
	}*/

	/*function add_header(IR_NUM) 
	{
		document.getElementById("TTK_NUM1").value = IR_NUM;
		document.frmsrch.submitSrch.click();
	}*/
	
	/*function countDisp(thisVal, row)
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
	}*/
	
	/*function countDisc(thisVal, row)
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
	}*/
	
	/*function changeValue(thisVal, row)
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
	}*/
	
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