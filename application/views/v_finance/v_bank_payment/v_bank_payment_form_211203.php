<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 12 November 2017
	* File Name		= v_bank_payment_form.php
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

$currentRow = 0;
if($task == 'add')
{
	foreach($vwDocPatt as $row) :
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
	
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	/*$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_bp_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_bp_header
			WHERE Patt_Year = $yearC AND CB_TYPE = 'BP'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$sqlC 	= "tbl_bp_header WHERE Patt_Year = $yearC AND CB_TYPE = 'BP' AND PRJCODE = '$PRJCODE'";
	$resC 	= $this->db->count_all($sqlC);
	$myMax 	= $resC+1;
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
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
	
	$DocNumber 		= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$CB_NUM			= "$DocNumber";
	$CB_SOURCE		= 'PINV';
	$JournalH_Code	= $CB_NUM;
	$CB_CODE		= "$lastPatternNumb"; // MANUAL CODE
	
	$CBCODE			= substr($lastPatternNumb, -4);
	$CBYEAR			= date('y');
	$CBMONTH		= date('m');
	$CB_CODE		= "$Pattern_Code.$CBCODE.$CBYEAR.$CBMONTH"; // MANUAL CODE
	
	$CB_DATE		= date('d/m/Y');
	$CB_TYPE		= 'BP';
	$CB_CURRID		= 'IDR';
	$CB_CURRCONV	= 1;
	$CB_DOCTYPE		= '';
	$BankAcc_ID		= '';
	$CB_PAYFOR		= '';
	$CB_CHEQNO		= '';
	$CB_NOTES		= '';
	$CB_STAT 		= 1;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;	
	$PR_VALUE		= 0;
	$PR_VALUEAPP	= 0;
	$CB_TOTAM		= 0;
	$CB_TOTAM_PPN	= 0;
	$CB_TOTAM_DISC 	= 0;
	$CB_DPAMOUNT	= 0;
	$CB_SOURCENO	= '';
	$TOTDiscPay		= 0;
	$CB_RECTYPE		= 'NPRJ';
	$VOID_REASON	= '';
}
else
{
	$isSetDocNo = 1;
	$CB_NUM 		= $default['CB_NUM'];
	$CB_SOURCE 		= $default['CB_SOURCE'];
	$CB_SOURCENO	= $default['CB_SOURCENO'];
	$PRJCODE		= $default['PRJCODE']; 
	$JournalH_Code	= $CB_NUM;
	$DocNumber		= $CB_NUM;
	$CB_CODE 		= $default['CB_CODE'];
	$CB_DATE1		=  $default['CB_DATE'];
	$CB_DATE		= date('d/m/Y',strtotime($CB_DATE1));
	$CB_TYPE 		= $default['CB_TYPE'];
	$CB_RECTYPE		= $default['CB_RECTYPE'];
	$CB_CURRID 		= $default['CB_CURRID'];
	$CB_DOCTYPE		= $default['CB_DOCTYPE'];
	$CB_CURRCONV	= $default['CB_CURRCONV'];
	$BankAcc_ID		= $default['ACC_NUM'];
	$CB_PAYFOR 		= $default['CB_PAYFOR'];
	$CB_CHEQNO 		= $default['CB_CHEQNO'];
	$CB_TOTAM 		= $default['CB_TOTAM'];
	$CB_TOTAM_PPN	= $default['CB_TOTAM_PPN'];
	$CB_DPAMOUNT	= $default['CB_DPAMOUNT'];
	$CB_NOTES 		= $default['CB_NOTES'];
	$VOID_REASON	= $default['VOID_REASON'];
	$CB_STAT		= $default['CB_STAT'];
	$CB_ACCID 		= $default['CB_ACCID'];
	$ACC_NUM		= $default['CB_ACCID'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];
}
	
if(isset($_POST['submit1']))
{
	$SelCurr 		= $this->input->post('CB_CURRIDA');
	$CB_ACCID 		= $this->input->post('AccSelected');
	$CB_PAYFOR 		= $this->input->post('SPLSelected');
	$CB_SOURCE		= $this->input->post('SRCSelected');
	$CB_SOURCENO	= $this->input->post('SRCNumber');
}
else
{
	$SelCurr 		= 'IDR';
	$CB_ACCID	 	= $BankAcc_ID;
	$CB_PAYFOR 		= $CB_PAYFOR;
	$CB_SOURCE		= $CB_SOURCE;
	$CB_SOURCENO	= $CB_SOURCENO;
}

	
/* ON 31 JAN 2019 : KARENA SUDAH DITETAPKAN PRJCODE NYA SAAT INDEXING
if($CB_DOCTYPE == 'PINV')
{
	$sqlPRJ 	= "SELECT A.PRJCODE FROM tbl_pinv_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.SPLCODE = '$CB_PAYFOR'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :

		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
elseif($CB_DOCTYPE == 'DP')
{
	$sqlPRJ 	= "SELECT A.PRJCODE FROM tbl_dp_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.SPLCODE = '$CB_PAYFOR'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
else
{
	$sqlPRJ 	= "SELECT DISTINCT A.PRJCODE FROM tbl_fpa_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.SPLCODE = '$CB_PAYFOR'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}*/
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
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;		
			if($TranslCode == 'ChooseInvoice')$ChooseInvoice = $LangTransl;
			if($TranslCode == 'Payment')$Payment = $LangTransl;
			if($TranslCode == 'Finance')$Finance = $LangTransl;		
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;		
			if($TranslCode == 'Payment')$Payment = $LangTransl;
			
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'PaymentNow')$PaymentNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'PaymentFor')$PaymentFor = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'BankAccount')$BankAccount = $LangTransl;
			if($TranslCode == 'ActualBalance')$ActualBalance = $LangTransl;
			if($TranslCode == 'Reserved')$Reserved = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'BGNumber')$BGNumber = $LangTransl;
			if($TranslCode == 'BGAmount')$BGAmount = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'BGRemaining')$BGRemaining = $LangTransl;
			if($TranslCode == 'Remaining')$Remain = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'DocNumber')$Doc_Number = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'DPAkum')$DPAkum = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'VoidNotes')$VoidNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
			if($TranslCode == 'billList')$billList = $LangTransl;
			if($TranslCode == 'amGrThen')$amGrThen = $LangTransl;
			if($TranslCode == 'selInv')$selInv = $LangTransl;
			if($TranslCode == 'inAmInv')$inAmInv = $LangTransl;
			if($TranslCode == 'selSrcPay')$selSrcPay = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'InvList')$InvList = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			/*if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
			if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
			if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;*/
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1	= "Anda belum memilih faktur yang akan dibayar.";
			$alert2	= "Melebihi maksimum pembayaran.";
			$alert3	= "Anda tidak memiliki Uang Muka untuk Supplier ini.";
			$alert4	= "Silahkan pilih salah satu tujuan pembayaran.";
			$alert5	= "Masukan alasan mengapa dokumen ini di-batalkan/close.";
			$alert6	= "Nilai Uang Muka yang Anda masukan melebihi nilai maksimal.";
			$alert7	= "Nilai pembayaran yang dimasukan lebih besar dari Saldo bank yang dipilih.";
			$alert8	= "Saldo bank yang dipilih kurang dari total yang harus dibayar.";
			
			$docNo	= "Dok. Sumber";
		}
		else
		{
			$alert1	= "You have not selected an invoice to pay.";
			$alert2	= "Amount payment more then Invoice Remain";
			$alert3	= "You do not have Advances for this Supplier.";
			$alert4	= "Please choose one of the payment destinations.";
			$alert5	= "Input the reason why you close/void this document.";
			$alert6	= "The Advance Value you entered exceeds the maximum value.";
			$alert7	= "The payment value entered is greater than the selected bank balance.";
			$alert8	= "Amount of Bank Account is less then Total Payment.";
			
			$docNo	= "Source Doc.";
		}
		
		$secGenCode	= base_url().'index.php/c_finance/c_bp0c07180851/genCode/'; // Generate Code
		
		$TOT_AMOUNT_DP	= 0;
		$GEJ_ACCID		= '';
		if($CB_SOURCE == 'PINV')
		{
			$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.INV_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0' AND INV_CATEG != 'OTH'
							AND A.PRJCODE = '$PRJCODE'
						UNION ALL
						SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_opn_inv A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNI_STAT = '3' AND A.selectedINV = '1' AND A.OPNI_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0'
							AND A.PRJCODE = '$PRJCODE'";
			if($task == 'edit')
			{
				$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_pinv_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.ISVOID = '0' AND INV_CATEG != 'OTH' AND A.PRJCODE = '$PRJCODE'
							UNION ALL
							SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_opn_inv A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNI_STAT = '3' AND A.selectedINV = '1' AND A.ISVOID = '0' AND A.PRJCODE = '$PRJCODE'";
			}
			$vwSPL	= $this->db->query($sql)->result();
			$PAGEFORM	= "PINV";
		}
		elseif($CB_SOURCE == 'DP')
		{
			$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'";
			if($task == 'edit')
			{
				$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_dp_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.DP_STAT = '3' AND A.PRJCODE = '$PRJCODE'";
			}
			$vwSPL	= $this->db->query($sql)->result();
			$PAGEFORM	= "DP";
		}
		else
		{
			$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.INV_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0' AND INV_CATEG = 'OTH'
							 AND A.PRJCODE = '$PRJCODE'";
			if($task == 'edit')
			{
				$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_pinv_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.ISVOID = '0' AND INV_CATEG = 'OTH' AND A.PRJCODE = '$PRJCODE'";
			}
			$vwSPL	= $this->db->query($sql)->result();
			
			// GET ACCOUNT
			$GEJ_ACCID	= '';
			$sqlAcc		= "SELECT GEJ_ACCID
							FROM tbl_fpa_header
							WHERE SPLCODE = '$CB_PAYFOR' AND FPA_STAT = 3 AND PRJCODE = '$PRJCODE'";
			$resAcc		= $this->db->query($sqlAcc)->result();
			foreach($resAcc as $rowAcc):
				$GEJ_ACCID	= $rowAcc->GEJ_ACCID;
			endforeach;
			
			$PAGEFORM	= "OTH";
		}
		
		// GET DP SUPPLIER
		/*$sqlTOTDP	= "SELECT SUM(DP_AMOUNT - DP_AMOUNT_USED) AS TOT_AMOUNT_DP
						FROM tbl_dp_header
						WHERE DP_STAT = '3' AND SPLCODE = '$CB_PAYFOR'";
		$resTOTDP	= $this->db->query($sqlTOTDP)->result();
		foreach($resTOTDP as $rowTOTDP):
			$TOT_AMOUNT_DP	= $rowTOTDP->TOT_AMOUNT_DP;
		endforeach;*/
		$sqlTOTDP	= "SELECT SUM(A.DP_AMOUNT - A.DP_AMOUNT_USED) AS TOT_AMOUNT_DP FROM tbl_dp_header A
						INNER JOIN tbl_bp_detail B ON A.DP_NUM = B.CBD_DOCNO
							AND B.CB_CATEG = 'DP'
						INNER JOIN tbl_bp_header C ON B.CB_NUM = C.CB_NUM
							AND C.CB_PAYFOR = '$CB_PAYFOR'
						WHERE A.DP_AMOUNT > A.DP_AMOUNT_USED";
		$resTOTDP	= $this->db->query($sqlTOTDP)->result();
		foreach($resTOTDP as $rowTOTDP):
			$TOT_AMOUNT_DP	= $rowTOTDP->TOT_AMOUNT_DP;
		endforeach;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - CB_TOTAM
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
				$APPROVE_AMOUNT 	= $CB_TOTAM;
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
	?>

	<style>
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}

		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <?php echo $mnName; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
		  	<?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  	</ol><?php */?>
		</section>

		<section class="content">
			<div class="row">
		        <form class="form-horizontal" name="frm1" id="frm1" method="post" action="" style="display:none">
		        	<input type="text" name="CB_CURRIDA" id="CB_CURRIDA" value="<?php echo $SelCurr; ?>" />
		        	<input type="text" name="AccSelected" id="AccSelected" value="<?php echo $CB_ACCID; ?>" />
		        	<input type="text" name="SPLSelected" id="SPLSelected" value="<?php echo $CB_PAYFOR; ?>" />
		        	<input type="text" name="SRCSelected" id="SRCSelected" value="<?php echo $CB_SOURCE; ?>" />
		        	<input type="text" name="SRCNumber" id="SRCNumber" value="<?php echo $CB_SOURCENO; ?>" />
		            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
		        </form>
		        <form methSod="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
		                        <input type="TEXT" name="CB_TYPE" id="CB_TYPE" value="<?php echo $CB_TYPE; ?>">
		                        <input type="TEXT" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
		                        <input type="TEXT" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="TEXT" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="TEXT" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="TEXT" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="TEXT" name="CBDate" id="CBDate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		    
			    <form name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		            <input type="Hidden" name="rowCount" id="rowCount" value="0">
	            	<?php if($isSetDocNo == 0) { ?>
	                    <div class="col-sm-12">
	                        <div class="alert alert-warning alert-dismissible">
	                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
		                            <?php echo $docalert2; ?>
	                        </div>
	                    </div>
	            	<?php } ?>
	                <div class="col-md-6">
	                    <div class="box box-success">
	                        <div class="box-header with-border">
	                            <h3 class="box-title"><?php echo $DetInfo; ?></h3>
	                        </div>
	                        <div class="box-body">
	                        	<div class="col-sm-12"> <!-- CB_NUM -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Code; ?></label>
			                            <input type="hidden" name="CB_NUM" id="CB_NUM" value="<?php echo $DocNumber; ?>">
			                      		<input type="text" class="form-control" name="CB_CODE" id="CB_CODE" value="<?php echo $CB_CODE; ?>" />
	                                </div>
	                            </div>
	                        	<div class="col-sm-12"> <!-- CB_DATE -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Date; ?></label>
	                                    <div class="input-group date">
			                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                            	<input type="text" name="CB_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $CB_DATE; ?>" style="width:100px" >
			                            </div>
	                                </div>
	                            </div>
	                        	<div class="col-sm-4"> <!-- CB_SOURCE -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $SourceDocument; ?></label>
	                                    <select name="CB_SOURCE" id="CB_SOURCE" class="form-control select2" onChange="selectAccount(this.value)">
			                                <option value=""> --- </option>
			                                <option value="PINV" <?php if($CB_SOURCE == 'PINV'){ ?> selected <?php } ?>>Faktur</option>
			                                <option value="DP" <?php if($CB_SOURCE == 'DP'){ ?> selected <?php } ?>><?php echo $DownPayment; ?></option>
			                                <!-- <option value="GEJ" <?php if($CB_SOURCE == 'GEJ'){ ?> selected <?php } ?> style="display:none">General Journal</option> -->
			                                <option value="OTH" <?php if($CB_SOURCE == 'OTH'){ ?> selected <?php } ?>><?php echo $Others; ?></option>
			                            </select>
	                                </div>
	                            </div>
	                        	<div class="col-sm-8"> <!-- CB_PAYFOR -->
			                        <?php if($CB_SOURCENO == '') { ?>
	                                    <div class="form-group">
	                                        <label for="exampleInputEmail1"><?php echo $PaymentFor; ?></label>
	                                        <select name="CB_PAYFOR" id="CB_PAYFOR" class="form-control select2" onChange="selectAccount(this.value)" <?php if($CB_SOURCE == 'GEJ') { ?> disabled <?php } ?>>
				                                <option value=""> --- </option>
				                                <?php echo $i = 0;
				                                    foreach($vwSPL as $row) :
				                                         $SPLCODE1	= $row->SPLCODE;
				                                         $SPLDESC1	= $row->SPLDESC;
				                                        ?>
				                                            <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $CB_PAYFOR) { ?> selected <?php } ?>><?php echo "$SPLCODE1 - $SPLDESC1"; ?></option>
				                                        <?php
				                                    endforeach;
				                                ?>
				                            </select>
	                                    </div>
			                        <?php } else { 
			                        	if($CB_PAYFOR == 'none')
											$CB_PAYFOR	= '';
			                        	?>
			                        	<input type="text" class="form-control" name="CB_PAYFOR" id="CB_PAYFOR" value="<?php echo $CB_PAYFOR; ?>" >
			                        <?php } ?>
			                    </div>
	                        	<div class="col-sm-12"> <!-- NOTES -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Notes; ?></label>
	                                    <textarea name="CB_NOTES" class="form-control" id="CB_NOTES" cols="30" style="height: 75px"><?php echo $CB_NOTES; ?></textarea>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="box box-warning">
	                        <div class="box-header with-border">
	                            <h3 class="box-title">Sumber Pembayaran</h3>
	                        </div>
	                        <div class="box-body">
	                        	<div class="col-sm-12"> <!-- CB_SOURCENO -->
	                            	<?php if($CB_SOURCE == "") { ?>
	                                    <div class="form-group">
	                                        <label for="exampleInputEmail1"><?php echo $Doc_Number; ?></label>
	                                    </div>
	                                	<input type="text" class="form-control" name="CB_SOURCENO" id="CB_SOURCENO" value="<?php echo $CB_SOURCENO; ?>" onClick="selectSources()" >
	                            	<?php } else { ?>
	                                	<input type="hidden" class="form-control" name="CB_SOURCENO" id="CB_SOURCENO" value="<?php echo $CB_SOURCENO; ?>" onClick="selectSources()" >
	                            	<?php } ?>
	                            </div>
			                    <?php
			                    	$url_selGEJ_NUM1	= site_url('c_finance/c_bp0c07180851/sgejbp0c07180851/?id='.$this->url_encryption_helper->encode_url($DocNumber));
								?>
			                    <script>
									var url1 = "<?php echo $url_selGEJ_NUM1;?>";
									function selectSources()
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
	                        	<div class="col-sm-12"> <!-- CB_CURRID, Acc_ID -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $BankAccount; ?></label>
	                                    <select name="CB_CURRID" id="CB_CURRID" class="form-control" style="display: none;" onChange="selectAccount(this.value)">
			                                <option value="IDR" <?php if($SelCurr == 'IDR'){ ?> selected <?php } ?>>IDR</option>
			                                <option value="USD" <?php if($SelCurr == 'USD'){ ?> selected <?php } ?>>USD</option>
			                            </select>
			                            <select name="CB_ACCID" id="CB_ACCID" class="form-control select2" onChange="selectAccount(this.value)">
			                            	<option value="0"> --- </option>
			                                <?php echo $i = 0;
				                                if($countAcc > 0)
				                                {
													foreach($vwAcc as $row) :
														$Acc_ID					= $row->Acc_ID;
														$Account_Category		= $row->Account_Category;
														$Account_Number 		= $row->Account_Number;
														$Account_Name 			= cut_text ($row->Account_Name, 50);
														$Base_OpeningBalance 	= $row->Base_OpeningBalance;
														$Base_Debet 			= $row->Base_Debet;
														$Base_Kredit 			= $row->Base_Kredit;
														$BalAcc					= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
														if($BalAcc <= 0)
															$disable = 1;
														else
															$disable = 0;
														?>
															<option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $CB_ACCID){ ?> selected <?php } if($disable == 1) { ?> style="color:#999" disabled <?php } else { ?> style="color:#00F; font-weight:bold" <?php } ?>>
																<?php echo "$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
															</option>
													 	<?php
													endforeach;
				                                }
												if($CB_SOURCE == 'OTH')
												{
													$sqlx	 = "SELECT DISTINCT
																	B.Acc_ID, 
																	B.Account_Number, 
																	B.Account_Nameen as Account_Name,
																	B.Account_Category,
																	B.Account_Class,			
																	B.currency_ID,
																	B.Base_OpeningBalance,
																	B.Base_Debet,
																	B.Base_Kredit
																FROM tbl_chartaccount B
																WHERE B.isLast = '1'
																	AND B.PRJCODE = '$PRJCODE'
																	AND B.Currency_id = '$SelCurr'
																	AND B.Account_Number = '$GEJ_ACCID'
																Order by B.Account_Category, B.Account_Number";
													$resx	= $this->db->query($sqlx)->result();
													foreach($resx as $rowX) :
														$Acc_ID					= $rowX->Acc_ID;
														$Account_Category		= $rowX->Account_Category;
														$Account_Number 		= $rowX->Account_Number;
														$Account_Name 			= $rowX->Account_Name;
														$Base_OpeningBalance 	= $rowX->Base_OpeningBalance;
														$Base_Debet 			= $rowX->Base_Debet;
														$Base_Kredit 			= $rowX->Base_Kredit;
														$BalAcc					= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
														if($BalAcc <= 0)
															$disable1 = 1;
														else
															$disable1 = 0;
														?>
														<option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $CB_ACCID){ ?> selected <?php } if($disable1 == 1) { ?> style="color:#999" disabled <?php } else { ?> style="color:#00F; font-weight:bold" <?php } ?>>
															<?php echo "$Account_Category-$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
														</option>
													 <?php
													 endforeach;
												}
			                                ?>
			                            </select>
				                        <script>
				                            function selectAccount()
				                            {
												CB_ACCID	= document.getElementById('CB_ACCID').value;
												document.getElementById('AccSelected').value = CB_ACCID;
												CB_CURRIDA	= document.getElementById('CB_CURRID').value;
												document.getElementById('CB_CURRIDA').value = CB_CURRIDA;
												SPLSelected	= document.getElementById('CB_PAYFOR').value;
												document.getElementById('SPLSelected').value = SPLSelected;
												SRCSelected	= document.getElementById('CB_SOURCE').value;
												document.getElementById('SRCSelected').value = SRCSelected;
												SRCNumber	= document.getElementById('CB_SOURCENO').value;
												document.getElementById('SRCNumber').value = SRCNumber;
												
				                                document.frm1.submit1.click();
				                            }
				                        </script>
	                                </div>
	                            </div>
	                            <?php
	                            	// CEK SALDO AKUN KAS BANK
			                            $sql1C 		= "tbl_chartaccount A
			                                            INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
			                                            WHERE
															A.Account_Number = '$CB_ACCID'
															AND A.PRJCODE = '$PRJCODE'
			                                            Order by A.Account_Category, A.Account_Number";
			                            $retSQL1C 	= $this->db->count_all($sql1C);
			                            
			                            if($retSQL1C > 0)
			                            {
			                                $sql1 = "SELECT A.Base_OpeningBalance, A.Base_Debet, A.Base_Kredit
			                                        FROM tbl_chartaccount A
			                                        INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
			                                        WHERE
														A.Account_Number = '$CB_ACCID'
														AND A.PRJCODE = '$PRJCODE'
			                                        Order by A.Account_Category, A.Account_Number";
			                                $retSQL1 	= $this->db->query($sql1)->result();
			                                foreach($retSQL1 as $row1):
			                                    $opBal		= $row1->Base_OpeningBalance;
			                                    $BaseDebet	= $row1->Base_Debet;
			                                    $BaseKredit	= $row1->Base_Kredit;
			                                endforeach;
			                            }
			                            else
			                            {
			                                $opBal		= 0;
			                                $BaseDebet	= 0;
			                                $BaseKredit	= 0;
			                            }
									
									// CEK TOTAL YANG AKAN DIBAYAR
			                            $GTInvoicePayX	= 0;
										if($CB_SOURCENO == '' && $task == 'edit' && $CB_STAT == 3)
			                            {
			                                $sqlDET	= "SELECT A.INV_AMOUNT, A.INV_AMOUNT_PPN, A.CBD_AMOUNT
														FROM tbl_bp_detail A
															INNER JOIN tbl_bp_header B ON A.JournalH_Code = B.JournalH_Code
														WHERE A.JournalH_Code = '$JournalH_Code'";
			                                // count data
			                                    $resultCount = $this->db->where('JournalH_Code', $JournalH_Code);
			                                    $resultCount = $this->db->count_all_results('tbl_bp_detail');
			                                // End count data
			                                $result = $this->db->query($sqlDET)->result();
			                                if($resultCount > 0)
			                                {
												$GTInvoicePayX	= 0;
			                                    foreach($result as $row) :
			                                        $INV_AMOUNT1		= $row->INV_AMOUNT;
			                                        $CBD_AMOUNT 		= $row->CBD_AMOUNT;
												
													$GTInvoicePayX		= $GTInvoicePayX + $CBD_AMOUNT;
												endforeach;
											}
										}

		                            $ActBal	= $opBal + $BaseDebet - $BaseKredit + $GTInvoicePayX;

		                            // TOTAL JUMLAH PEMBAYARAN BERSTATUS RESERVE; dari Bank Payment yang belum di Approve dan tidak reject
										$CB_TOTAM		= 0;
										$CB_TOTAM_PPN	= 0;
			                            $sql2 			= "SELECT SUM(CB_TOTAM) AS Tot_AM, SUM(CB_TOTAM_PPN) AS Tot_AMPPn
															FROM tbl_bp_header
															WHERE 
																CB_STAT IN (2) AND 
																CB_CURRID = '$SelCurr'
																AND CB_ACCID = '$CB_ACCID'
																AND JournalH_Code != '$JournalH_Code'";
			                            $retSQL2 	= $this->db->query($sql2)->result();
			                            foreach($retSQL2 as $row2):
			                                $CB_TOTAM		= $row2->Tot_AM;
			                                $CB_TOTAM_PPN	= $row2->Tot_AMPPn;
			                            endforeach;
			                            //$TotReserve	= $CB_TOTAM + $CB_TOTAM_PPN;
			                            $TotReserve		= $CB_TOTAM;

		                            // Total Ammount : Total nilai yang saat ini akan dibayarkan
		                            	$TotAmmount	= 0;
		                            
		                            // Total Remain
		                            	$TotRemain	= $ActBal - $TotReserve - $TotAmmount;				 
		                        ?>
	                        	<div class="col-sm-12"> <!-- AMOUNT -->
			                        <div class="row">
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $ActualBalance; ?></label>
	                                            <div class="form-group has-primary">
	                                            	<a href="" class="btn btn-primary btn-xs">
			                                            <?php echo number_format($ActBal, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $Reserved; ?></label>
	                                            <div class="form-group has-error">
	                                            	<a href="" class="btn btn-danger btn-xs">
			                                            <?php echo number_format($TotReserve, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $Amount; ?></label>
	                                            <div class="form-group has-warning">
	                                            	<a href="" class="btn btn-warning btn-xs">
			                                            <?php echo number_format($TotAmmount, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $Remain; ?></label>
	                                            <div class="form-group has-success">
	                                            	<a href="" class="btn btn-success btn-xs">
			                                            <?php echo number_format($TotRemain, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                            <input type="hidden" name="TotRemAccount" id="TotRemAccount" value="<?php echo $TotRemain; ?>" class="form-control" style="max-width:80px; text-align:right">
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        	<div class="col-sm-12"> <!-- STATUS -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Status; ?></label>
	                                    <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $CB_STAT; ?>" />
			                        	<?php
				                        	$isDisabled = 1;
											if($CB_STAT == 1 || $CB_STAT == 4)
											{
												$isDisabled = 0;
											}

				                            if($ISCREATE == 1)
				                            {
												?>
												<select name="CB_STAT" id="CB_STAT" class="form-control select2" onChange="selStat(this.value)">
												<?php
												$disableBtn	= 0;
												if($CB_STAT == 5 || $CB_STAT == 6 || $CB_STAT == 9)
												{
													$disableBtn	= 1;
												}
												if($CB_STAT != 1 AND $CB_STAT != 4) 
												{
													?>
														<option value="1"<?php if($CB_STAT == 1) { ?> selected <?php } if($CB_STAT != 1) { ?> disabled <?php } ?>>New</option>
														<option value="2"<?php if($CB_STAT == 2) { ?> selected <?php } if($CB_STAT != 2) { ?> disabled <?php } ?>>Confirm</option>
														<option value="3"<?php if($CB_STAT == 3) { ?> selected <?php } if($CB_STAT != 3) { ?> disabled <?php } ?>>Approve</option>
														<option value="4"<?php if($CB_STAT == 4) { ?> selected <?php } if($CB_STAT != 4) { ?> disabled <?php } ?>>Revising</option>
														<option value="5"<?php if($CB_STAT == 5) { ?> selected <?php } if($CB_STAT != 5) { ?> disabled <?php } ?>>Rejected</option>
														<option value="6"<?php if($CB_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
														<option value="7"<?php if($CB_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
														<option value="9"<?php if($CB_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
													<?php
												}
												else
												{
													?>
														<option value="1"<?php if($CB_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($CB_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													<?php
												}
													?>
												</select>
											<?php
				                            }
				                        ?>
	                                </div>
	                            </div>
								<script>
			                        function selStat(thisValue)
			                        {
			                        	var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
			                            if(thisValue == 9)
			                            {
			                                document.getElementById('tblUpdate').style.display 		= '';
			                                document.getElementById('labVoid').style.display 		= '';
			                                document.getElementById('VOID_REASON').style.display 	= '';
			                            }
			                            else if(thisValue == 2)
			                            {
			                            	if(STAT_BEFORE == 4)
			                                	document.getElementById('tblUpdate').style.display 		= '';
			                            }
			                            else
			                            {
			                                document.getElementById('tblUpdate').style.display 		= 'none';
			                                document.getElementById('labVoid').style.display 		= 'none';
			                                document.getElementById('VOID_REASON').style.display 	= 'none';
			                            }
			                        }
			                    </script>
	                        	<div class="col-sm-12">
				                    <?php if($CB_SOURCE != 'DP') { ?>
				                        <div class="row"> <!-- DP -->
	                                        <div class="col-xs-6">
	                                            <div class="form-group">
	                                                <label for="exampleInputPassword1"><?php echo $DPAkum; ?></label><br>
	                                                <input type="text" name="TOT_AMOUNT_DPX" id="TOT_AMOUNT_DPX" value="<?php echo number_format($TOT_AMOUNT_DP, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" readonly >
	                                            </div>
	                                        </div>
	                                        <div class="col-xs-6">
	                                            <div class="form-group">
	                                                <label for="exampleInputPassword1"><?php echo "-"; ?></label><br>
	                                                <input type="text" name="CB_DPAMOUNTX" id="CB_DPAMOUNTX" value="<?php echo number_format($CB_DPAMOUNT, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAMOUNT_DP(this);" readonly>
													<input type="hidden" name="CB_DPAMOUNT" id="CB_DPAMOUNT" value="<?php echo $CB_DPAMOUNT; ?>" class="form-control" style="min-width:130px; max-width:200px; text-align:right" >
	                                            </div>
	                                        </div>
	                                    </div>
				                    <?php } else { ?>
			                            <input type="hidden" name="CB_DPAMOUNTX" id="CB_DPAMOUNTX" value="<?php echo number_format($CB_DPAMOUNT, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAMOUNT_DP(this);" >
			                            <input type="hidden" name="CB_DPAMOUNT" id="CB_DPAMOUNT" value="<?php echo $CB_DPAMOUNT; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" >
			                        <?php } ?>
			                    </div>
			                    <select name="CB_RECTYPE" id="CB_RECTYPE" class="form-control" style="max-width:120px; display: none;">
	                                <option value="PRJ" <?php if($CB_RECTYPE == 'PRJ'){ ?> selected <?php } ?>><?php echo $Project; ?></option>
	                                <option value="NPRJ" <?php if($CB_RECTYPE == 'NPRJ'){ ?> selected <?php } ?>>Non <?php echo $Project; ?></option>
	                            </select><br>
			                    <?php
									$colPAYERID	= "$CB_PAYFOR~$PAGEFORM~$PRJCODE";
									$selSource	= site_url('c_finance/c_bp0c07180851/pall180c2cinv/?id='.$this->url_encryption_helper->encode_url($colPAYERID));
								?>
	                        	<div class="col-sm-12">
									<?php if($CB_SOURCE != 'GEJ') { ?>
	                                    <div class="form-group"> <!-- CHOOSE DOC. -->
	                                        <div class="form-group has-error" style="display: none;">
		                                        <span class="help-block" style="font-style: italic;">Silahkan pilih dokumen yang akan dibayar</span>
		                                    </div>
	                                        <button class="btn btn-warning" type="button" onClick="selectitem();" style="display: none;" <?php if($CB_STAT != 1 && $CB_STAT != 4) { ?>disabled <?php } ?>>
				                        		<i class="fa fa-folder-open"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
				                        	</button>

				                        	<?php
				                        		if(($CB_PAYFOR != '') && ($CB_STAT == 1 || $CB_STAT == 4))
				                        		{
				                        			?>
														<button class="btn btn-warning" type="button" onClick="selectitem();">
															<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
														</button>
														<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addTTK" id="btnModal" style="display: none;">
							                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
							                        	</a>
				                        			<?php
				                        		}
				                        	?>
	                                    </div>
									<?php } ?>
								</div>
			                    <script>
									var url = "<?php echo $selSource;?>";
									function selectitem()
									{
										CB_PAYFOR	= document.getElementById('CB_PAYFOR').value;
										if(CB_PAYFOR == '')
										{
											swal('<?php echo $alert4; ?>',
											{
												icon: "warning",
											});
											document.getElementById('CB_PAYFOR').focus();
											return false;
										}

										SEL_ACC	= document.getElementById('CB_ACCID').value;
										if(SEL_ACC == 0)
										{
											swal('<?php echo $selSrcPay; ?>',
											{
												icon: "warning",
											});
											document.getElementById('CB_ACCID').focus();
											return false;
										}
										
										/*title = 'Select Item';
										w = 1200;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/

										document.getElementById('btnModal').click();
									}

									/* DITUTUP SEMENTARA : 02-11-2021
										function chgAMOUNT_DP(thisVal)
										{
											var decFormat		= document.getElementById('decFormat').value;
											var TOT_AMOUNT_DPX	= eval(document.getElementById('TOT_AMOUNT_DPX')).value.split(",").join("");
											var CB_DPAMOUNT		= eval(thisVal).value.split(",").join("");
											if(CB_DPAMOUNT > TOT_AMOUNT_DPX)
											{
												swal('<?php echo $alert6; ?>',
												{
													icon: "warning",
												})
												.then(function()
												{
													document.getElementById('CB_DPAMOUNTX').focus();
													document.getElementById('CB_DPAMOUNTX').value = '0.00';
												});
												return false;
											}
											document.getElementById('CB_DPAMOUNT').value	= CB_DPAMOUNT;
											document.getElementById('CB_DPAMOUNTX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CB_DPAMOUNT)), decFormat));
						
											var totRow = document.getElementById('totalrow').value;
											var TOTInvoice_p 	= 0;
											var TOTPPn_p		= 0;
											var TOTDisc_p		= 0;
											var TOTDiscG_p		= 0;
											var GTInvoice_p1	= 0;
											
											if(totRow > 0)
											{
												for(i=1;i<=totRow;i++)
												{
													var element = document.getElementById('data'+i+'INV_AMOUNT');
													if (element != null)
													{
														var INV_AMOUNT			= document.getElementById('data'+i+'INV_AMOUNT').value;
														var INV_AMOUNT_p		= document.getElementById('data'+i+'Amount').value;
													}
													else
													{
														var INV_AMOUNT			= 0;
														var INV_AMOUNT_p		= 0;
													}
													
													var element = document.getElementById('data'+i+'PPhAmount');
													if (element != null)
													{
														var PPhAmount		= document.getElementById('data'+i+'PPhAmount').value;
														var INV_AMOUNT_PPH	= document.getElementById('data'+i+'INV_AMOUNT_PPH').value;
													}
													else
													{
														var PPhAmount		= 0;
														var INV_AMOUNT_PPH	= 0;
													}
													
													var element = document.getElementById('data'+i+'RetAmount');
													if (element != null)
													{
														var RetAmount		= document.getElementById('data'+i+'INV_AMOUNT_Ret').value;
														var INV_AMOUNT_Ret	= document.getElementById('data'+i+'INV_AMOUNT_Ret').value;
													}
													else
													{
														var RetAmount		= 0;
														var INV_AMOUNT_Ret	= 0;
													}

													var element = document.getElementById('data'+i+'CBD_AMOUNT_DISC');
													if (element != null)
													{
														var CBD_AMOUNT_DISC		= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
														var INV_AMOUNT_DISC	= document.getElementById('data'+i+'INV_AMOUNT_DISC').value;
													}
													else
													{
														var CBD_AMOUNT_DISC		= 0;
														var INV_AMOUNT_DISC	= 0;
													}

													TOTInvoicex_p		= parseFloat(INV_AMOUNT_p);
													TOTInvoice_p		= parseFloat(TOTInvoice_p) + parseFloat(INV_AMOUNT_p);
													//TOTPPn_p			= parseFloat(TOTPPn_p) + parseFloat(INV_AMOUNT_PPN_p);	
													TOTDisc_p			= parseFloat(TOTDisc_p) + parseFloat(CBD_AMOUNT_DISC);		
													GTInvoice_p1		= parseFloat(GTInvoice_p1) + parseFloat(TOTInvoicex_p);
												}
											}
											
											TOTDisc_p1			= parseFloat(CB_DPAMOUNT) + parseFloat(TOTDisc_p);
											GTInvoice_p			= parseFloat(TOTInvoice_p) - parseFloat(TOTDisc_p1);
											GTInvoice_p			= parseFloat(TOTDisc_p1);
											
											document.getElementById('TOTDisc1_p').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTDisc_p1)),decFormat));
											document.getElementById('GTInvoice1_p').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat((GTInvoice_p)),decFormat));
											document.getElementById('GTInPay').value			= parseFloat(GTInvoice_p);
										}
									*/
	                       		</script>
	                        </div>
	                    </div>
	                </div>

	                <div class="col-sm-12">
	                	<div class="box box-primary">
	                        <div class="box-header with-border">
	                            <h3 class="box-title center"><?php echo $billList; ?></h3>
	                        </div>
	                        <div class="box-body">
					            <div class="search-table-outter">
					                <table id="tbl" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                            <tr style="background:#CCCCCC">
			                              	<th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
			                              	<th width="10%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $InvoiceNo; ?> </th>
			                              	<th width="30%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Description; ?> </th>
			                              	<th colspan="2" style="text-align:center"><?php echo $InvoiceAmount; ?> </th>
			                              	<th colspan="5" style="text-align:center"><?php echo $Payment; ?> </th>
			                            </tr>
			                            <tr style="background:#CCCCCC">
			                              	<th width="10%" style="text-align:center;"><?php echo $Amount; ?> </th>
			                              	<th width="10%" style="text-align:center;"><?php echo $Paid; ?> </th>
			                              	<th width="10%" style="text-align:center; display:none">Disc.</th>
			                              	<th width="10%" style="text-align:center;"><?php echo $Amount; ?> </th>
			                              	<th width="10%" style="text-align:center; display:none">PPh</th>
			                              	<th width="10%" style="text-align:center; display:none">&nbsp;</th>
			                              	<th width="10%" style="text-align:center;" nowrap>DP</th>
			                            </tr>
			                            <?php
											$CB_TOTAM		= 0;
											$CB_TOTAM_PPN	= 0;
											$CB_TOTAM_DISC	= 0;
											$CB_TPAID 		= 0;
											$CB_TPAID_DISC 	= 0;
											
				                            if($CB_SOURCENO == '' && $task == 'edit')
				                            {

				                                $sqlDET	= "SELECT A.* FROM tbl_bp_detail A
																INNER JOIN tbl_bp_header B ON A.JournalH_Code = B.JournalH_Code
															WHERE A.JournalH_Code = '$JournalH_Code'";
				                                // count data
				                                    $resultCount = $this->db->where('JournalH_Code', $JournalH_Code);
				                                    $resultCount = $this->db->count_all_results('tbl_bp_detail');
				                                // End count data
				                                $result = $this->db->query($sqlDET)->result();
				                                $i		= 0;
				                                $j		= 0;
												
				                                if($resultCount > 0)
				                                {
				                                    foreach($result as $row) :
				                                        $currentRow  	= ++$i;
				                                        $JournalH_Code 	= $row->JournalH_Code;
				                                        $CB_NUM 		= $row->CB_NUM;
				                                        $CB_CODE 		= $row->CB_CODE;
				                                        $CB_CATEG		= $row->CB_CATEG;
				                                        $CBD_DOCNO		= $row->CBD_DOCNO;
				                                        $CBD_DOCCODE	= $row->CBD_DOCCODE;
				                                        $CBD_DOCREF		= $row->CBD_DOCREF;
				                                        $CBD_DESC		= $row->CBD_DESC;
				                                        $CBD_DEBCRED 	= $row->CBD_DEBCRED;
				                                        $CBD_ACCID 		= $row->CBD_ACCID;
				                                        $INV_AMOUNT		= $row->INV_AMOUNT;
				                                        $INV_AMOUNT_PPN	= $row->INV_AMOUNT_PPN;
				                                        $INV_AMOUNT_PPH	= $row->INV_AMOUNT_PPH;
				                                        $INV_AMOUNT_DISC= $row->INV_AMOUNT_DISC;
				                                        $AMOUNT_PAID 	= $row->AMOUNT_PAID;
				                                        $AMOUNT_DP		= $row->AMOUNT_DP;
				                                        $CBD_AMOUNT 	= $row->CBD_AMOUNT;
				                                        $CBD_AMOUNT_DISC= $row->CBD_AMOUNT_DISC;
				                                        $Notes			= $row->Notes;
														$INV_CODE		= $CBD_DOCREF;

														$INV_AMOUNT_REM = $INV_AMOUNT - $AMOUNT_PAID;

														$INV_DATEV 		= "";
														$SPLCODE 		= "";
														$INV_NOTES 		= "";
						                                $s_INVH			= "SELECT INV_DATE, SPLCODE, INV_NOTES
						                                					FROM tbl_pinv_header WHERE INV_NUM = '$CBD_DOCNO' LIMIT 1";
						                                $r_INVH 		= $this->db->query($s_INVH)->result();
					                                    foreach($r_INVH as $rw_INVH) :
					                                        $INV_DATE 	= $rw_INVH->INV_DATE;
					                                        $INV_DATEV	= strftime('%d %b %Y', strtotime($INV_DATE));
					                                        $SPLCODE 	= $rw_INVH->SPLCODE;
					                                        $INV_NOTES 	= $rw_INVH->INV_NOTES;
					                                    endforeach;
															
				                                        /*if ($j==1) {
				                                            echo "<tr class=zebra1>";
				                                            $j++;
				                                        } else {
				                                            echo "<tr class=zebra2>";
				                                            $j--;
				                                        }*/
				                                        ?>
				                                        <tr>
					                                        <td style="text-align:left"> <!-- CB_NUM, CB_CODE -->
																<?php
						                                            if($CB_STAT == 1)
						                                            {
						                                                ?>
						                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>); countAllInvoice();" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
						                                                <?php
						                                            }
						                                            else
						                                            {
						                                                echo "$currentRow.";
						                                            }
					                                            ?>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>JournalH_Code" name="data[<?php echo $currentRow; ?>][JournalH_Code]" value="<?php echo $CB_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CB_NUM" name="data[<?php echo $currentRow; ?>][CB_NUM]" value="<?php echo $CB_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CB_CODE" name="data[<?php echo $currentRow; ?>][CB_CODE]" value="<?php echo $CB_CODE; ?>" class="form-control" style="max-width:300px;" readonly>
					                                      	</td>
					                                        
					                                      	<td style="text-align:left" nowrap> <!-- CBD_DOCNO, CBD_DOCREF, PRJCODE -->
																<?php echo $CBD_DOCCODE; ?><br>
																<strong><i class='fa fa-calendar margin-r-5'></i> <?=$Date?> </strong>
														  		<div style='margin-left: 18px'>
															  		<p class='text-muted'>
															  			<?=$INV_DATEV?>
															  		</p>
															  	</div>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCNO" name="data[<?php echo $currentRow; ?>][CBD_DOCNO]" value="<?php echo $CBD_DOCNO; ?>" class="form-control" style="max-width:300px;" >
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCCODE" name="data[<?php echo $currentRow; ?>][CBD_DOCCODE]" value="<?php echo $CBD_DOCCODE; ?>" class="form-control" style="max-width:300px;" >
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCREF" name="data[<?php echo $currentRow; ?>][CBD_DOCREF]" value="<?php echo $CBD_DOCREF; ?>" class="form-control" style="max-width:300px;" >
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:300px;" readonly>
					                                        </td>
					                                        
					                                      	<td style="text-align:left"> <!-- CBD_DESC, CB_CATEG -->
																<?php echo "$Description. $INV_NOTES"; ?><br>
																<strong><i class='fa fa-users margin-r-5'></i><?=$SPLCODE?> </strong>
														  		<div style='margin-left: 18px'>
															  		<p class='text-muted'>
															  			<?=$CBD_DESC?>
															  		</p>
															  	</div>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CBD_DESC]" id="data<?php echo $currentRow; ?>CBD_DESC" value="<?php echo $CBD_DESC; ?>" class="form-control" style="max-width:600px;" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CB_CATEG]" id="data<?php echo $currentRow; ?>CB_CATEG" value="<?php echo $CB_CATEG; ?>" class="form-control" style="max-width:600px;" >
					                                       	</td>
					                                        
					                                        <td style="text-align:right" > <!-- INV_AMOUNT -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
																	<input type="text" name="INV_AMOUNT<?php echo $currentRow; ?>" id="INV_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($INV_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:150px; text-align:right" title="Nilai Total Inv" disabled >
																<?php } else { ?>
																	<?php echo number_format($INV_AMOUNT, $decFormat); ?>
																	<input type="hidden" name="INV_AMOUNT<?php echo $currentRow; ?>" id="INV_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($INV_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:150px; text-align:right" title="Nilai Total Inv" disabled >
																<?php } ?>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][INV_AMOUNT]" id="data<?php echo $currentRow; ?>INV_AMOUNT" size="10" value="<?php echo $INV_AMOUNT; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Inv" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][INV_AMOUNT_PPN]" id="data<?php echo $currentRow; ?>INV_AMOUNT_PPN" size="10" value="<?php echo $INV_AMOUNT_PPN; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai PPn Inv" >
					                                        </td>

					                                        <td style="text-align:right" > <!-- AMOUNT_PAID, INV_AMOUNT_REM -->
					                                        	<?php echo number_format($AMOUNT_PAID, $decFormat); ?>
																<input type="hidden" name="AMOUNT_PAID<?php echo $currentRow; ?>" id="AMOUNT_PAID<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_PAID, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" readonly>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][AMOUNT_PAID]" id="data<?php echo $currentRow; ?>AMOUNT_PAID" size="10" value="<?php echo $AMOUNT_PAID; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" >
					                                            <input type="hidden" name="INV_AMOUNT_REM<?php echo $currentRow; ?>" id="INV_AMOUNT_REM<?php echo $currentRow; ?>" value="<?php echo $INV_AMOUNT_REM; ?>" title="Nilai Max Pemb." >
					                                        </td>
					                                      	
					                                        <td style="text-align:right; display: none;" > <!-- CBD_AMOUNT_DISC -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
																	<input type="text" name="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" id="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT_DISC, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDisc(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } else { ?>
																	<?php echo number_format($CBD_AMOUNT_DISC, $decFormat); ?>
																	<input type="hidden" name="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" id="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT_DISC, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDisc(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } ?>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CBD_AMOUNT_DISC]" id="data<?php echo $currentRow; ?>CBD_AMOUNT_DISC" size="10" value="<?php echo $CBD_AMOUNT_DISC; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" >
					                                        </td>
					                                      	
					                                        <td style="text-align:right" > <!-- CBD_AMOUNT -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
																	<input type="text" name="CBD_AMOUNT<?php echo $currentRow; ?>" id="CBD_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } else { ?>
																	<?php echo number_format($CBD_AMOUNT, $decFormat); ?>
																	<input type="hidden" name="CBD_AMOUNT<?php echo $currentRow; ?>" id="CBD_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } ?>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CBD_AMOUNT]" id="data<?php echo $currentRow; ?>CBD_AMOUNT" size="10" value="<?php echo $CBD_AMOUNT; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" >
					                                        </td>

					                                      	<td style="text-align:right;"> <!-- AMOUNT_DP -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
					                                        		<input type="text" name="AMOUNT_DP<?php echo $currentRow; ?>" id="AMOUNT_DP<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_DP, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDP(this,<?php echo $currentRow; ?>);" title="Nilai Pot Pemb." >
																<?php } else { ?>
																	<?php echo number_format($AMOUNT_DP, $decFormat); ?>
					                                        		<input type="hidden" name="AMOUNT_DP<?php echo $currentRow; ?>" id="AMOUNT_DP<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_DP, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDP(this,<?php echo $currentRow; ?>);" title="Nilai Pot Pemb." >
																<?php } ?>
																<input type="hidden" name="data[<?php echo $currentRow; ?>][AMOUNT_DP]" id="data<?php echo $currentRow; ?>AMOUNT_DP" size="10" value="<?php echo $AMOUNT_DP; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." >
					                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Notes]" id="data<?php echo $currentRow; ?>Notes" value="<?php echo $Notes; ?>" class="form-control" style="max-width:500px;" >
					                                  		</td>
				                                  		</tr>
				                                		<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
				                              		<?php
				                                    endforeach;
				                                }
												/*$CB_TOTAM		= $CB_TOTAM + $INV_AMOUNT;
												$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $INV_AMOUNT_PPN;
												$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $INV_AMOUNT_DISC;*/

												$CB_TOTAM		= $CB_TOTAM + $CBD_AMOUNT;
												$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $INV_AMOUNT_PPN;
												$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $CBD_AMOUNT_DISC;
				                            }
				                            elseif($CB_SOURCENO != '' && $task == 'add')
				                            {
				                                $sqlDET	= "SELECT A.JournalH_Code, A.JournalH_Code AS CB_NUM, A.Acc_ID, 
																A.JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax,
																A.ITM_CODE, A.Ref_Number, A.Notes, A.Journal_DK, A.isTax
															FROM tbl_journaldetail A
																INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
															WHERE A.JournalH_Code = '$CB_SOURCENO' AND ITM_CODE = ''";
				                                $result = $this->db->query($sqlDET)->result();
				                                $i		= 0;
				                                $j		= 0;
												
												$TotD		= 0;
												$TotDTax	= 0;
												$TotK		= 0;
												$TotKTax	= 0;
				                                foreach($result as $row) :
													$currentRow  		= ++$i;
													$JournalH_Code 		= $row->JournalH_Code;
													$CB_NUM 			= $row->CB_NUM;
													$CB_CODE 			= $row->CB_NUM;
													$CBD_DOCNO			= $row->CB_NUM;
													$Acc_ID 			= $row->Acc_ID;
													$CBD_DOCREF		= $row->Ref_Number;
													$CBD_DESC		= $row->Notes;
													$CBD_DEBCRED 			= $row->Journal_DK;
													$JournalD_Debet		= $row->JournalD_Debet;
													$JournalD_Debet_tax	= $row->JournalD_Debet_tax;
													$JournalD_Kredit 	= $row->JournalD_Kredit;
													$JournalD_Kredit_tax= $row->JournalD_Kredit_tax;
													$ITM_CODE			= $row->ITM_CODE;
													$Ref_Number			= $row->Ref_Number;
													$Notes				= $row->Notes;
													$Journal_DK			= $row->Journal_DK;
													$isTax				= $row->isTax;
													
													$ITM_NAME			= '';
													if($ITM_CODE != '')
													{
														$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
														$resITM 	= $this->db->query($sqlITM)->result();
														foreach($resITM as $rowITM) :
															$ITM_NAME 	= $rowITM->ITM_NAME;
														endforeach;
													}
													else
													{
														$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_ID'";
														$resITM 	= $this->db->query($sqlITM)->result();
														foreach($resITM as $rowITM) :
															$ITM_NAME 	= $rowITM->Account_NameId;
														endforeach;
													}
													
													if($CBD_DEBCRED == 'D')
													{
														$INV_AMOUNT		= $JournalD_Debet;
														$INV_AMOUNT_PPN	= $JournalD_Debet_tax;
														$TotD			= $TotD + $JournalD_Debet;
														$TotDTax		= $TotDTax + $JournalD_Debet_tax;
													}
													else
													{
														$INV_AMOUNT		= $JournalD_Kredit;
														$INV_AMOUNT_PPN	= $JournalD_Kredit_tax;
														$TotK			= $TotK + $JournalD_Kredit;
														$TotKTax		= $TotKTax + $JournalD_Kredit_tax;
													}
													
													$RemAm			= $TotD - $TotK;
													$RemAmTax		= $TotDTax - $TotKTax;
													
													$TOTInvoice		= abs($TOTInvoice + $RemAm);
													$TOTPPn			= abs($TOTPPn + $RemAmTax);
													$GTInvoice		= $GTInvoice + $TOTInvoice + $TOTPPn;
													
													$TOTInvoicePay		= abs($TOTInvoicePay + $RemAm);
													$TOTPPnPay			= abs($TOTPPnPay + $RemAmTax);
													$GTInvoicePay		= $GTInvoicePay + $TOTInvoice + $TOTPPn;
													
													$Amount			= 0;
													$AMOUNT_PAID_PPN		= 0;
										
													if ($j==1) {
														echo "<tr class=zebra1>";
														$j++;
													} else {
														echo "<tr class=zebra2>";
														$j--;
													}
													?>
														<!-- JournalH_Code, CB_NUM -->
														<tr>
															<td width="2%" height="25" style="text-align:left">
																<?php
																if($CB_STAT == 1)
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
																<input type="hidden" id="data<?php echo $currentRow; ?>JournalH_Code" name="data[<?php echo $currentRow; ?>][JournalH_Code]" value="<?php echo $DocNumber; ?>" class="form-control" style="max-width:300px;" readonly>
																<input type="hidden" id="data<?php echo $currentRow; ?>CB_NUM" name="data[<?php echo $currentRow; ?>][CB_NUM]" value="<?php echo $DocNumber; ?>" class="form-control" style="max-width:300px;" readonly>
															</td>
															<!-- CB_CODE, CBD_DOCNO, CBD_DOCREF -->
															<td width="10%" style="text-align:left" nowrap>
																<?php echo $CBD_DOCNO; ?>
																<input type="hidden" id="data[<?php echo $currentRow; ?>][CB_CODE]" name="data[<?php echo $currentRow; ?>][CB_CODE]" value="<?php echo $DocNumber; ?>" class="form-control" style="max-width:300px;" readonly>
																<input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCNO" name="data[<?php echo $currentRow; ?>][CBD_DOCNO]" value="<?php echo $CBD_DOCNO; ?>" class="form-control" style="max-width:300px;" readonly>
																<input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCREF" name="data[<?php echo $currentRow; ?>][CBD_DOCREF]" value="<?php echo $CBD_DOCREF; ?>" class="form-control" style="max-width:300px;" readonly>										</td>
															<!-- CBD_DESC -->
															<td width="47%" style="text-align:left">
																<input type="text" name="data[<?php echo $currentRow; ?>][CBD_DESC]" id="data<?php echo $currentRow; ?>CBD_DESC" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:600px;" >
															</td>
															<td width="10%" style="text-align:right" nowrap>
																<?php echo number_format($INV_AMOUNT, $decFormat); ?><input type="hidden" name="INV_AMOUNT<?php echo $currentRow; ?>" id="INV_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($INV_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:200px; text-align:right" size="20" disabled >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][INV_AMOUNT]" id="data<?php echo $currentRow; ?>INV_AMOUNT" value="<?php echo $INV_AMOUNT; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
						                                        <input type="hidden" name="INV_AMOUNT_PPN<?php echo $currentRow; ?>" id="INV_AMOUNT_PPN<?php echo $currentRow; ?>" value="<?php echo number_format($INV_AMOUNT_PPN, $decFormat); ?>" size="15" class="form-control" style="min-width:110px; max-width:200px; text-align:right" disabled >
						                                        <input type="hidden" name="data<?php echo $currentRow; ?>INV_AMOUNT_PPN" id="data<?php echo $currentRow; ?>INV_AMOUNT_PPN2" value="<?php echo $INV_AMOUNT_PPN; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
						                                    </td>
															<td width="13%" style="text-align:right">
																<input type="text" name="Amount<?php echo $currentRow; ?>" id="Amount<?php echo $currentRow; ?>" value="<?php echo number_format($Amount, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:200px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" size="20" >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][Amount]" id="data<?php echo $currentRow; ?>Amount" value="<?php echo $Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
						                                        
																<input type="hidden" name="AMOUNT_PAID_PPN<?php echo $currentRow; ?>" id="AMOUNT_PAID_PPN<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_PAID_PPN, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:200px; text-align:right" onKeyPress="return isIntOnlyNew(event);"  onBlur="chgPayAmountPPn(this,<?php echo $currentRow; ?>);" size="15" >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][AMOUNT_PAID_PPN]" id="data<?php echo $currentRow; ?>AMOUNT_PAID_PPN" value="<?php echo $AMOUNT_PAID_PPN; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >	
															</td>
															<!-- Amount -->
															<td width="3%" style="display:none" nowrap>&nbsp;
						                                        <select name="PPhTax" id="PPhTax" class="form-control" style="max-width:100px;display:none" onChange="selPPhStat(this.value)">
						                                            <option value="">-- none --</option>
						                                            <?php
						                                                $sqlTLA		= "SELECT TAXLA_NUM, TAXLA_CODE FROM tbl_tax_la";
						                                                $resTLA 	= $this->db->query($sqlTLA)->result();
						                                                foreach($resTLA as $rowTLA):
						                                                    $TAXLA_NUM	= $rowTLA->TAXLA_NUM;
						                                                    $TAXLA_CODE	= $rowTLA->TAXLA_CODE;
						                                                    ?>
						                                                    <option value="<?php echo $TAXLA_NUM; ?>"<?php if($PPhTax == $TAXLA_NUM) { ?> selected <?php } ?>><?php echo $TAXLA_CODE; ?></option>
						                                                    <?php
						                                                endforeach;
						                                            ?>
						                                        </select>
						                                    </td>
															<!-- AMOUNT_PAID_PPN -->
															<td width="4%" nowrap style="text-align:right; display:none">&nbsp;									</td>
															<td width="9%" nowrap style="text-align:right">&nbsp;</td>
															<td width="2%" style="text-align:center; display:none">
																<input type="hidden" name="data[<?php echo $currentRow; ?>][Notes]" id="data<?php echo $currentRow; ?>Notes" value="<?php echo $Notes; ?>" class="form-control" style="max-width:500px;" >
															</td>
					                                	</tr>
					                                	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
					                              	<?php
													/*$CB_TOTAM		= $CB_TOTAM + $INV_AMOUNT;
													$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $INV_AMOUNT_PPN;
													$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $INV_AMOUNT_DISC;*/

													$CB_TOTAM		= $CB_TOTAM + $CBD_AMOUNT;
													$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $INV_AMOUNT_PPN;
													$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $CBD_AMOUNT_DISC;
				                                endforeach;
				                            }
				                            if($task == 'add')
				                            {
				                                ?>
				                                  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
				                              <?php
				                            }							
			                            ?>
			                            <input type="hidden" name="CB_TOTAM" id="CB_TOTAM" value="<?php echo $CB_TOTAM; ?>">
			                            <input type="hidden" name="CB_TOTAM_PPN" id="CB_TOTAM_PPN" value="<?php echo $CB_TOTAM_PPN; ?>">
			                            <input type="hidden" name="CB_TOTAM_DISC" id="CB_TOTAM_DISC" value="<?php echo $CB_TOTAM_DISC; ?>">
			                        </table>
			                    </div>
				            </div>
	                    </div>
				    </div>

	                <div class="col-sm-6">
	                    <div class="box-body">
	                        <div class="form-group">
	                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                            <div class="col-sm-9">
									<?php
										$ShowBtn	= 0;
										if($ISCREATE == 1 && ($CB_STAT == 1 ||  $CB_STAT == 3 ||  $CB_STAT == 4))
										{
											$ShowBtn	= 1;
										}
										/*else if($ISAPPROVE == 1 && $CB_STAT != 3)
										{
											$ShowBtn	= 1;
										}*/
										
		                                if($ShowBtn == 1)
		                                {
		                                    if($task=='add')
		                                    {
		                                        ?>
		                                            <button class="btn btn-primary" id="tblUpdate1" style="display:none">
		                                            <i class="fa fa-save"></i></button>
		                                            <button class="btn btn-primary" id="tblUpdate">
		                                            <i class="fa fa-save"></i></button>&nbsp;
		                                        <?php
		                                    }
		                                    else
		                                    {
		                                        ?>
		                                            <button class="btn btn-primary" id="tblUpdate" <?php if($CB_STAT == 3) { ?> style="display:none" <?php } ?>>
		                                            <i class="fa fa-save"></i></button>&nbsp;
		                                        <?php
		                                    }
		                                }
		                                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>&nbsp;');
										$secPrint	= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
									if($task != 'add')
									{
		                            ?>
		                                <input type="hidden" name="urlPrint" id="urlPrint" value="<?php echo $secPrint; ?>">
		                                <button class="btn btn-warning" type="button" onClick="printDocument()"><i class="fa fa-print"></i></button>
		                            <?php
									}
									?>
		                            <script>
										function printDocument(row)
										{
											var url	= document.getElementById('urlPrint').value;
											w = 900;
											h = 550;
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
											form.target = 'formpopup';
										}
									</script>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <br>
					<?php
	                    $DOC_NUM	= $CB_NUM;
	                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
	                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
	                ?>
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
	                                
	                                /*if($resCAPPH == 1)
	                                {
	                                    $Approver	= $Awaiting;
	                                    $boxCol_3	= "yellow";
	                                    $class		= "glyphicon glyphicon-info-sign";
	                                }*/
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
	                                
	                                /*if($resCAPPH == 2)
	                                {
	                                    $Approver	= $Awaiting;
	                                    $boxCol_4	= "yellow";
	                                    $class		= "glyphicon glyphicon-info-sign";
	                                }*/
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
	                                
	                                /*if($resCAPPH == 3)
	                                {
	                                    $Approver	= $Awaiting;
	                                    $boxCol_5	= "yellow";
	                                    $class		= "glyphicon glyphicon-info-sign";
	                                }*/
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
				</form>

			    <?php
			    	if(($CB_PAYFOR != '') && ($CB_STAT == 1 || $CB_STAT == 4))
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
										                    	<a href="#itm1" data-toggle="tab"><?php echo $InvList." - ".$SPLDESC; ?></a>
										                    </li>
										                </ul>
											            <div class="box-body">
											            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
						                                        <div class="form-group">
						                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
							                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
							                                                <thead>
							                                                    <tr>
															                        <th width="2%" style="text-align:center; vertical-align: middle;">&nbsp;</th>
															                        <th width="16%" style="text-align:center; vertical-align: middle;"><?php echo $InvoiceNo; ?> </th>
															                        <th width="37%" style="text-align:center; vertical-align: middle;"><?php echo $Description; ?> </th>
															                        <th width="6%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $DueDate; ?>  </th>
															                        <th width="7%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Amount; ?></th>
															                        <th width="6%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $PPn; ?></th>
															                        <th width="3%" style="text-align:center; vertical-align: middle;" nowrap>PPh</th>
															                        <th width="3%" style="text-align:center; vertical-align: middle;" nowrap>Retensi</th>
															                        <th width="6%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Paid; ?></th>
															                        <th width="8%" style="text-align:center; vertical-align: middle;" nowrap>Total <?php echo "($Remain)"; ?></th>
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
				                                      	<input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
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
									        "ajax": "<?php echo site_url('c_finance/c_bp0c07180851/get_AllDataINV/?id='.$colPAYERID)?>",
									        "type": "POST",
											//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
											"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
											"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
															{ targets: [,4,5,6,7,8,9], className: 'dt-body-right' },
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
											console.log('aA')
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
										      	add_item($(this).val());
										    });

										    $('#mdl_addTTK').on('hidden.bs.modal', function () {
											    $(this)
												    .find("input,textarea,select")
													    //.val('')
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
	            ?>
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
		  endDate: '+1d'
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

	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}
		
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}
	
	function add_gej(strItem) 
	{
		CB_ACCID	= document.getElementById('CB_ACCID').value;
		document.getElementById('AccSelected').value = CB_ACCID;
		CB_CURRIDA	= document.getElementById('CB_CURRID').value;
		document.getElementById('CB_CURRIDA').value = CB_CURRIDA;
		SPLSelected	= document.getElementById('CB_PAYFOR').value;
		document.getElementById('SPLSelected').value = SPLSelected;
		SRCSelected	= document.getElementById('CB_SOURCE').value;
		document.getElementById('SRCSelected').value = SRCSelected;
		SRCNumber	= document.getElementById('CB_SOURCENO').value;
		document.getElementById('SRCNumber').value = strItem;
		
		
		document.frm1.submit1.click();
	}
	
	function add_item(strItem) 						// USED
	{
		var decFormat	= document.getElementById('decFormat').value;

		TOTInvoice		= 0;
		TOTPPn			= 0;
		TOTPPh			= 0;
		TOTRet			= 0;
		TOTDisc			= 0;
		TOTDP			= 0;
		GTInvoice		= 0;
		TOTInvoiceR		= 0;
		TOTPPnR			= 0;
		GTInvoiceR		= 0;
		
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var CB_NUM 	= "<?php echo $DocNumber; ?>";
		
		var CB_CODE 	= "<?php echo $CB_CODE; ?>";
		ilvl = arrItem[1];
		
		//validateDouble(arrItem[0],arrItem[1])
		//if(validateDouble(arrItem[0],arrItem[1]))
		//{
			//swal("Double Item for " + arrItem[0]);
			//return;
		//}

		INV_NUM 			= arrItem[0];
		INV_CODE 			= arrItem[1];
		PRJCODE 			= arrItem[2];
		INV_AMOUNT 			= arrItem[3];
		INV_AMOUNT_PPN		= arrItem[4];
		INV_AMOUNT_PPH 		= arrItem[5];
		INV_AMOUNT_DPB 		= arrItem[6];
		INV_AMOUNT_RET 		= arrItem[7];
		INV_AMOUNT_POT 		= arrItem[8];
		INV_AMOUNT_OTH 		= arrItem[9];
		INV_AMOUNT_TOT		= arrItem[10];
		AMOUNT_PAID			= arrItem[11];
		INV_ACC_OTH			= arrItem[12];
		INV_PPN				= arrItem[13];
		PPN_PERC			= arrItem[14];
		INV_PPH				= arrItem[15];
		PPH_PERC			= arrItem[16];
		INV_NOTES			= arrItem[17];
		SPLCODE				= arrItem[18];

		INV_AMOUNT_REM 		= parseFloat(INV_AMOUNT_TOT) - parseFloat(AMOUNT_PAID);

		IR_NUM 				= "";
		CB_CATEG 			= "";
		INV_CATEG 			= "";
		INV_AMOUNT_DP 		= 0;

		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		//console.log('c')
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		document.getElementById('PRJCODE').value	= PRJCODE;
		
		// CB_NUM, CB_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+'); countAllInvoice();" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="data'+intIndex+'CB_NUM" name="data['+intIndex+'][CB_NUM]" value="'+CB_NUM+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data['+intIndex+'][CB_CODE]" name="data['+intIndex+'][CB_CODE]" value="'+CB_CODE+'" class="form-control" style="max-width:300px;" readonly>';
		
		//console.log('d')
		// CBD_DOCNO, CBD_DOCREF, PRJCODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+INV_CODE+'<input type="hidden" id="data'+intIndex+'CBD_DOCNO" name="data['+intIndex+'][CBD_DOCNO]" value="'+INV_NUM+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'CBD_DOCCODE" name="data['+intIndex+'][CBD_DOCCODE]" value="'+INV_CODE+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'CBD_DOCREF" name="data['+intIndex+'][CBD_DOCREF]" value="'+IR_NUM+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" class="form-control" style="max-width:300px;" readonly>';
		
		//console.log('e')
		// CBD_DESC, CB_CATEG
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = false;
			objTD.innerHTML = ''+INV_NOTES+'<input type="hidden" name="data['+intIndex+'][CBD_DESC]" id="data'+intIndex+'CBD_DESC" size="10" value="'+INV_NOTES+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][CB_CATEG]" id="data'+intIndex+'CB_CATEG" size="10" value="'+INV_CATEG+'" class="form-control" style="max-width:300px;" >';
		
		//console.log('f')
		// INV_AMOUNT
			INV_AMOUNT_TOTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_AMOUNT_TOT)), 2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="INV_AMOUNT'+intIndex+'" id="INV_AMOUNT'+intIndex+'" value="'+INV_AMOUNT_TOTV+'" class="form-control" style="min-width:120px; max-width:150px; text-align:right" title="Nilai Total Inv" disabled ><input type="hidden" name="data['+intIndex+'][INV_AMOUNT]" id="data'+intIndex+'INV_AMOUNT" size="10" value="'+INV_AMOUNT_TOT+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Inv" ><input type="hidden" name="data['+intIndex+'][INV_AMOUNT_PPN]" id="data'+intIndex+'INV_AMOUNT_PPN" size="10" value="'+INV_AMOUNT_PPN+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Inv" >';
		
		//console.log('g')
		// AMOUNT_PAID, INV_AMOUNT_REM
			AMOUNT_PAIDV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AMOUNT_PAID)), 2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="AMOUNT_PAID'+intIndex+'" id="AMOUNT_PAID'+intIndex+'" value="'+AMOUNT_PAIDV+'" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" title="Nilai Total Dibayar" readonly ><input type="hidden" name="data['+intIndex+'][AMOUNT_PAID]" id="data'+intIndex+'AMOUNT_PAID" size="10" value="'+AMOUNT_PAID+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" ><input type="hidden" name="INV_AMOUNT_REM'+intIndex+'" id="INV_AMOUNT_REM'+intIndex+'" value="'+INV_AMOUNT_REM+'" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" title="Nilai Total Dibayar" readonly >';
		
		//console.log('h')
		// CBD_AMOUNT_DISC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = '<input type="text" name="CBD_AMOUNT_DISC'+intIndex+'" id="CBD_AMOUNT_DISC'+intIndex+'" value="0.00" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDisc(this,'+intIndex+');" title="Nilai Pot Pemb."><input type="hidden" name="data['+intIndex+'][CBD_AMOUNT_DISC]" id="data'+intIndex+'CBD_AMOUNT_DISC" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." >';
		
		//console.log('g')
		// CBD_AMOUNT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="CBD_AMOUNT'+intIndex+'" id="CBD_AMOUNT'+intIndex+'" value="0.00" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,'+intIndex+');"><input type="hidden" name="data['+intIndex+'][CBD_AMOUNT]" id="data'+intIndex+'CBD_AMOUNT" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);">';
		
		//console.log('i')
		// AMOUNT_DP
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.style.display = '';
			objTD.innerHTML = '<input type="text" name="AMOUNT_DP'+intIndex+'" id="AMOUNT_DP'+intIndex+'" value="0.00" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDP(this,'+intIndex+');" title="Nilai Pot Pemb." readonly><input type="hidden" name="data['+intIndex+'][AMOUNT_DP]" id="data'+intIndex+'AMOUNT_DP" size="10" value="'+INV_AMOUNT_DP+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." >';
		
		//console.log('j')
		document.getElementById('totalrow').value = intIndex;
		//console.log('k')
		//countAllInvoice();
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();	
			
		countAllInvoice()
	}
	
	function chgAftDisc(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		var INV_AMOUNT_REM	= parseFloat(document.getElementById('INV_AMOUNT_REM'+row).value);

		var CBD_AMOUNT		= document.getElementById('data'+row+'CBD_AMOUNT').value;
		var CBD_AMOUNT_DISC	= document.getElementById('data'+row+'CBD_AMOUNT_DISC').value;
		var thisVal			= eval(thisVal1).value.split(",").join("");
		var CBD_AMOUNT_DISC	= parseFloat(thisVal);

		CBD_AMOUNT_TOT 		= parseFloat(CBD_AMOUNT) + parseFloat(CBD_AMOUNT_DISC);
		INV_REMAIN_NEW 		= parseFloat(INV_AMOUNT_REM) - parseFloat(CBD_AMOUNT);

		if(CBD_AMOUNT_TOT > INV_AMOUNT_REM)
		{
			DELTA_AMN 		= parseFloat(CBD_AMOUNT_TOT - INV_AMOUNT_REM);
			if(DELTA_AMN > 0.1)
			{
				swal('<?php echo $alert2; ?>',
				{
					icon: "warning",
				});
				document.getElementById('data'+row+'CBD_AMOUNT_DISC').value = INV_REMAIN_NEW;
				document.getElementById('CBD_AMOUNT_DISC'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_REMAIN_NEW)), 2));
			}
		}
		else
		{
			document.getElementById('data'+row+'CBD_AMOUNT_DISC').value = CBD_AMOUNT_DISC;
			document.getElementById('CBD_AMOUNT_DISC'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CBD_AMOUNT_DISC)), 2));
		}

		var TotRemAccount 	= document.getElementById('TotRemAccount').value;
		if(parseFloat(TotRemAccount) < parseFloat(CBD_AMOUNT_TOT))
		{
			swal('<?php echo $alert7; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('CBD_AMOUNT'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
				document.getElementById('data'+row+'CBD_AMOUNT').value	= 0;
			});
		}
		
		TOT_AMOUNT		= 0;
		TOT_AMOUNT_DISC	= 0;
		for(i=1;i<=totRow;i++)
		{
			var CBD_AMOUNT		= document.getElementById('data'+i+'CBD_AMOUNT').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(CBD_AMOUNT);
			var CBD_AMOUNT_DISC	= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
			var TOT_AMOUNT_DISC	= parseFloat(TOT_AMOUNT_DISC) + parseFloat(CBD_AMOUNT_DISC);
		}

		GTOT_AMOUNT 			= parseFloat(TOT_AMOUNT) + parseFloat(TOT_AMOUNT_DISC);
		document.getElementById('CB_TOTAM').value			= parseFloat(TOT_AMOUNT);
		document.getElementById('CB_TOTAM_DISC').value		= parseFloat(TOT_AMOUNT_DISC);

		var TotRemAccount 	= document.getElementById('TotRemAccount').value;
		if(parseFloat(TotRemAccount) < parseFloat(GTOT_AMOUNT))
		{
			swal('<?php echo $alert8; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('CBD_AMOUNT_DISC'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
				document.getElementById('data'+row+'CBD_AMOUNT_DISC').value	= 0;
		
				TOT_AMOUNT		= 0;
				TOT_AMOUNT_DISC	= 0;
				for(i=1;i<=totRow;i++)
				{
					var CBD_AMOUNT		= document.getElementById('data'+i+'CBD_AMOUNT').value;
					var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(CBD_AMOUNT);
					var CBD_AMOUNT_DISC	= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
					var TOT_AMOUNT_DISC	= parseFloat(TOT_AMOUNT_DISC) + parseFloat(CBD_AMOUNT_DISC);
				}

				document.getElementById('CB_TOTAM').value			= parseFloat(TOT_AMOUNT);
				document.getElementById('CB_TOTAM_DISC').value		= parseFloat(TOT_AMOUNT_DISC);
			});
		}
		else
		{
			TOT_AMOUNT		= 0;
			TOT_AMOUNT_DISC	= 0;
			for(i=1;i<=totRow;i++)
			{
				var CBD_AMOUNT		= document.getElementById('data'+i+'CBD_AMOUNT').value;
				var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(CBD_AMOUNT);
				var CBD_AMOUNT_DISC	= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
				var TOT_AMOUNT_DISC	= parseFloat(TOT_AMOUNT_DISC) + parseFloat(CBD_AMOUNT_DISC);
			}

			document.getElementById('CB_TOTAM').value			= parseFloat(TOT_AMOUNT);
			document.getElementById('CB_TOTAM_DISC').value		= parseFloat(TOT_AMOUNT_DISC);
		}
				
		//countAllInvoice();
	}
	
	function chgPayAmount(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		var INV_AMOUNT_REM	= parseFloat(document.getElementById('INV_AMOUNT_REM'+row).value);

		var CBD_AMOUNT_DISC	= document.getElementById('data'+row+'CBD_AMOUNT_DISC').value;
		var thisVal			= eval(thisVal1).value.split(",").join("");
		var CBD_AMOUNT		= parseFloat(thisVal);

		CBD_AMOUNT_TOT 		= parseFloat(CBD_AMOUNT) + parseFloat(CBD_AMOUNT_DISC);
		INV_REMAIN_NEW 		= parseFloat(INV_AMOUNT_REM) - parseFloat(CBD_AMOUNT_DISC);

		if(CBD_AMOUNT_TOT > INV_AMOUNT_REM)
		{
			DELTA_AMN 		= parseFloat(CBD_AMOUNT_TOT - INV_AMOUNT_REM);
			if(DELTA_AMN > 0.1)
			{
				swal('<?php echo $alert2; ?>',
				{
					icon: "warning",
				});
				document.getElementById('CBD_AMOUNT'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_REMAIN_NEW)), 2));
				document.getElementById('data'+row+'CBD_AMOUNT').value	= INV_REMAIN_NEW;
			}
		}
		else
		{
			document.getElementById('CBD_AMOUNT'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CBD_AMOUNT)), 2));
			document.getElementById('data'+row+'CBD_AMOUNT').value	= CBD_AMOUNT;
		}

		var TotRemAccount 	= document.getElementById('TotRemAccount').value;
		if(parseFloat(TotRemAccount) < parseFloat(CBD_AMOUNT))
		{
			swal('<?php echo $alert7; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('CBD_AMOUNT'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
				document.getElementById('data'+row+'CBD_AMOUNT').value	= 0;
			});
		}
		
		TOT_AMOUNT		= 0;
		TOT_AMOUNT_DISC	= 0;
		for(i=1;i<=totRow;i++)
		{
			var CBD_AMOUNT		= document.getElementById('data'+i+'CBD_AMOUNT').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(CBD_AMOUNT);
			var CBD_AMOUNT_DISC	= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
			var TOT_AMOUNT_DISC	= parseFloat(TOT_AMOUNT_DISC) + parseFloat(CBD_AMOUNT_DISC);
		}

		GTOT_AMOUNT 			= parseFloat(TOT_AMOUNT) + parseFloat(TOT_AMOUNT_DISC);
		document.getElementById('CB_TOTAM').value			= parseFloat(TOT_AMOUNT);
		document.getElementById('CB_TOTAM_DISC').value		= parseFloat(TOT_AMOUNT_DISC);

		var TotRemAccount 	= document.getElementById('TotRemAccount').value;
		if(parseFloat(TotRemAccount) < parseFloat(GTOT_AMOUNT))
		{
			swal('<?php echo $alert8; ?> aa',
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('CBD_AMOUNT'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
				document.getElementById('data'+row+'CBD_AMOUNT').value	= 0;
		
				TOT_AMOUNT		= 0;
				TOT_AMOUNT_DISC	= 0;
				for(i=1;i<=totRow;i++)
				{
					var CBD_AMOUNT		= document.getElementById('data'+i+'CBD_AMOUNT').value;
					console.log('CBD_AMOUNTaa = '+CBD_AMOUNT)
					var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(CBD_AMOUNT);
					var CBD_AMOUNT_DISC	= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
					var TOT_AMOUNT_DISC	= parseFloat(TOT_AMOUNT_DISC) + parseFloat(CBD_AMOUNT_DISC);
				}
				document.getElementById('CB_TOTAM').value			= parseFloat(TOT_AMOUNT);
				document.getElementById('CB_TOTAM_DISC').value		= parseFloat(TOT_AMOUNT_DISC);
			});
		}
		else
		{
			TOT_AMOUNT		= 0;
			TOT_AMOUNT_DISC	= 0;
			for(i=1;i<=totRow;i++)
			{
				var CBD_AMOUNT		= document.getElementById('data'+i+'CBD_AMOUNT').value;
				console.log('CBD_AMOUNTaa = '+CBD_AMOUNT)
				var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(CBD_AMOUNT);
				var CBD_AMOUNT_DISC	= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
				var TOT_AMOUNT_DISC	= parseFloat(TOT_AMOUNT_DISC) + parseFloat(CBD_AMOUNT_DISC);
			}
			document.getElementById('CB_TOTAM').value			= parseFloat(TOT_AMOUNT);
			document.getElementById('CB_TOTAM_DISC').value		= parseFloat(TOT_AMOUNT_DISC);
		}
		
		//countAllInvoice();
	}
	
	function chgAftDP(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		var DP_REM		= eval(document.getElementById('TOT_AMOUNT_DPX')).value.split(",").join("");

		var pot_dp1		= eval(thisVal1).value.split(",").join("");
		var pot_dp		= parseFloat(pot_dp1);
		var CBD_AMOUNT_DISC	= parseFloat(document.getElementById('data'+row+'CBD_AMOUNT_DISC').value);
		var InvAmount	= parseFloat(document.getElementById('data'+row+'Amount').value);
		if(DP_REM > 0)
		{
			if(parseFloat(pot_dp) > parseFloat(DP_REM))
			{
				swal('<?php echo $alert3; ?>',
				{
					icon: "warning",
				});
				document.getElementById('data'+row+'AMOUNT_DP').value 	= 0;
				document.getElementById('AMOUNT_DP'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
			}
			else
			{
				document.getElementById('data'+row+'AMOUNT_DP').value 	= pot_dp;
				document.getElementById('AMOUNT_DP'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(pot_dp)),decFormat));
			}
		}
		else
		{
			document.getElementById('data'+row+'AMOUNT_DP').value 	= 0;
			document.getElementById('AMOUNT_DP'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
		}

		// TIDAK PERLU MENGURANGI NILAI PEMBAYARAN, DILAKUKANNYA SAAT DI JURNAL SAJA
		// var remInvAm	= parseFloat(InvAmount) - parseFloat(CBD_AMOUNT_DISC) - parseFloat(pot_dp);
		// var remInvAm	= parseFloat(InvAmount);
		// document.getElementById('data'+row+'Amount').value 		= remInvAm;
		// document.getElementById('Amountx'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(remInvAm)),decFormat)); // nilai yang dibayarkan berubah
		
		
		var TOT_POTDP	= 0;
		for(i=1;i<=totRow;i++)
		{
			var POT_DP		= document.getElementById('data'+row+'AMOUNT_DP').value;
			var TOT_POTDP	= parseFloat(TOT_POTDP) + parseFloat(POT_DP);
		}

		document.getElementById('CB_DPAMOUNT').value	= TOT_POTDP;
		document.getElementById('CB_DPAMOUNTX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_POTDP)),decFormat));

		var AMOUNT_DP 		= document.getElementById('CB_DPAMOUNT');
		//chgAMOUNT_DP(AMOUNT_DP);
		countAllInvoice();
	}
	
	function chgPayAmountPPn(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		
		var thisVal			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'AMOUNT_PAID_PPN').value 	= thisVal;
		document.getElementById('AMOUNT_PAID_PPN'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PAID_PPN	= 0;
		for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var AMOUNT_PAID_PPN		= document.getElementById('data'+row+'AMOUNT_PAID_PPN').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PAID_PPN	= parseFloat(TOT_AMOUNT_PAID_PPN) + parseFloat(AMOUNT_PAID_PPN);
		}
		document.getElementById('CB_TOTAM').value		= parseFloat(TOT_AMOUNT);
		document.getElementById('CB_TOTAM_PPN').value	= parseFloat(TOT_AMOUNT_PAID_PPN);
		
		countAllInvoice();
	}
	
	function chgPPhAm(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		
		var PPhAmount	= eval(thisVal1).value.split(",").join("");
		var InvAmount	= document.getElementById('data'+row+'Amount').value;
		var remInvAm	= parseFloat(InvAmount - PPhAmount);
		document.getElementById('data'+row+'Amount').value 		= remInvAm;
		document.getElementById('Amount'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(remInvAm)),decFormat));
		
		document.getElementById('data'+row+'PPhAmount').value 	= PPhAmount;
		document.getElementById('PPhAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPhAmount)),decFormat));
		
		countAllInvoice();
	}
	
	function countAllInvoice()						// USED
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totrow			= document.getElementById('totalrow').value;	
		
		var GT_INV_AMOUNT	= 0;
		
		for(i=1;i<=totrow;i++)
		{
			INV_AMOUNT		= parseFloat(document.getElementById('data'+i+'INV_AMOUNT').value);
			GT_INV_AMOUNT 	= parseFloat(GT_INV_AMOUNT) + parseFloat(INV_AMOUNT);
		}
		document.getElementById('Amount'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Pay_Amount)),decFormat));
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
	
	function validateDouble(vcode, SNCODE) 
	{
		var thechk=new Array();
		var duplicate = false;
		
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData()
	{
		var totrow 			= document.getElementById('totalrow').value;
		var TotRemAccount 	= document.getElementById('TotRemAccount').value;
		var GTInPay 		= document.getElementById('CB_TOTAM').value;

		CB_PAYFOR	= document.getElementById('CB_PAYFOR').value;
		if(CB_PAYFOR == '')
		{
			swal('<?php echo $alert4; ?>',
			{
				icon: "warning",
			});
			document.getElementById('CB_PAYFOR').focus();
			return false;
		}
		
		CB_NOTES	= document.getElementById('CB_NOTES').value;
		/*if(CB_NOTES == '')
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#CB_NOTES').focus();

            });
			return false;
		}*/

		SEL_ACC	= document.getElementById('CB_ACCID').value;
		if(SEL_ACC == 0)
		{
			swal('<?php echo $selSrcPay; ?>',
			{
				icon: "warning",
			});
			document.getElementById('CB_ACCID').focus();
			return false;
		}
		
		if(parseFloat(TotRemAccount) < parseFloat(GTInPay))
		{
			swal('<?php echo $alert8; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		/*for(i=1;i<=totrow;i++)
		{
			var thisVal		= document.getElementById('data'+i+'Amount').value;
			
			if(thisVal == 0)
			{
				swal('<?php echo $inAmInv; ?>',
				{
					icon: "warning",
				});
				document.getElementById('Amount'+i).value = '0';
				document.getElementById('Amount'+i).focus();
				return false;
			}
		}*/
		
		if(totrow == 0)
		{
			swal('<?php echo $selInv; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		else
		{
			CB_STAT	= document.getElementById("CB_STAT").value;
		
			if(CB_STAT == 9)
			{
				VOID_REASON		= document.getElementById('VOID_REASON').value;
				if(VOID_REASON == '')
				{
					swal('<?php echo $alert5; ?>',
					{
						icon: "warning",
					});
					document.getElementById('VOID_REASON').focus();
					return false;
				}
			}
			document.getElementById('tblUpdate').style.display = 'none';
			document.frm.submit();
		}
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