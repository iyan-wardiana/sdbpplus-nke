<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 3 November 2019
	* File Name	= v_saleinv_form.php
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
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	$PRJCODE 			= $PRJCODE;
	$CUST_CODE 			= '';
	$CUST_DESC 			= '';
	$CUST_ADDRESS 		= '';
	$CUST_ADDRESS 		= '';
	$SINV_TAXCURR 		= 'IDR';
	$Tax_Currency_Rate 	= 1;
	$SINV_NOTES 			= '';
	$SINV_STATUS 		= 1;
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
	$myCUST_CODE			= '';
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

	$year = date('Y');
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_sinv_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_sinv_header
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
	$Patt_Number = $myMax;
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
	
	//$DocNumber = "$Pattern_Code$groupPattern$INVType-$lastPatternNumb";
	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$SINV_NUM		= $DocNumber;
	
	$VOCCODE		= substr($lastPatternNumb, -4);
	$VOCYEAR		= date('y');
	$VOCMONTH		= date('m');
	$SINV_CODE		= "$Pattern_Code.$VOCCODE.$VOCYEAR.$VOCMONTH"; // MANUAL CODE
	
	$SINV_TYPE 		= 0;	// 0 = Indirect, 1 = Direct
	$SINV_CATEG		= '';
	$SO_NUM			= '';	
	$SINV_DATE 		= date('m/d/Y');
	$SINV_DUEDATE 	= $SINV_DATE;

	if(isset($_POST['submitSrch1']))
	{
		$myCUST_CODE 	= $_POST['CUST_CODE1'];
		$CUST_CODE		= $myCUST_CODE;
	}
	else
	{
		$myCUST_CODE	= '';
		$CUST_CODE		= $myCUST_CODE;
	}
	
	$CUST_ADDRESS		= '';
	$SINV_CURRENCY		= 'IDR';
	$SINV_TAXCURR		= 1;
	$SINV_AMOUNT		= 0;
	$SINV_AMOUNT_PPN	= 0;
	$SINV_AMOUNT_PPH	= 0;
	$SINV_TERM			= 0;
	$SINV_STAT			= 1;
	$SINV_PAYSTAT		= 0;
	$SINV_NOTES			= '';
	$SINV_NOTES2		= '';
	$DP_NUM				= '';
	$DP_AMOUNT			= 0;
	$SINV_PPH			= '';
	$SINV_PPHVAL		= 0;
	$Patt_Year 			= date('Y');
	
	$dataColl 			= "$PRJCODE~$Pattern_Code~tbl_sinv_header~$Pattern_Length";
	$dataTarget			= "SINV_CODE";
}
else
{
	$isSetDocNo 		= 1;
	$SINV_NUM 			= $default['SINV_NUM'];
	$DocNumber			= $default['SINV_NUM'];
	$SINV_CODE			= $default['SINV_CODE'];
	$SINV_TYPE			= $default['SINV_TYPE'];
	$SINV_CATEG			= $default['SINV_CATEG'];
	$SO_NUM				= $default['SO_NUM'];
	$PRJCODE			= $default['PRJCODE'];
	$SINV_DATE			= $default['SINV_DATE'];
	$SINV_DATE			= date('m/d/Y', strtotime($SINV_DATE));
	$SINV_DUEDATE		= $default['SINV_DUEDATE'];
	$SINV_DUEDATE		= date('m/d/Y', strtotime($SINV_DUEDATE));
	$CUST_CODE			= $default['CUST_CODE'];
	$CUST_CODE1			= $CUST_CODE;
	$SINV_ADDRESS		= $default['SINV_ADDRESS'];
	$SINV_CURRENCY		= $default['SINV_CURRENCY'];
	$SINV_TAXCURR		= $default['SINV_TAXCURR'];
	$SINV_AMOUNT		= $default['SINV_AMOUNT'];
	$SINV_AMOUNT_PPN	= $default['SINV_AMOUNT_PPN'];
	$SINV_AMOUNT_PPH	= $default['SINV_AMOUNT_PPH'];
	$SINV_AMOUNT_PAID	= $default['SINV_AMOUNT_PAID'];
	$SINV_AMOUNT_FINAL	= $default['SINV_AMOUNT_FINAL'];
	$SINV_LISTTAX		= $default['SINV_LISTTAX'];
	$SINV_LISTTAXVAL	= $default['SINV_LISTTAXVAL'];
	$SINV_PPH			= $default['SINV_PPH'];
	$SINV_PPHVAL		= $default['SINV_PPHVAL'];
	$DP_NUM				= $default['DP_NUM'];
	$DP_AMOUNT			= $default['DP_AMOUNT'];
	$SINV_NOTES			= $default['SINV_NOTES'];
	$SINV_NOTES2		= $default['SINV_NOTES2'];
	$SINV_STAT			= $default['SINV_STAT'];
	$COMPANY_ID			= $default['COMPANY_ID'];
	$CREATED			= $default['CREATED'];
	$CREATER			= $default['CREATER'];
	$Patt_Year			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
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
			if($TranslCode == 'Invoice')$Invoice = $LangTransl;
			if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'SalesOrder')$SalesOrder = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'BillAddress')$BillAddress = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
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
			if($TranslCode == 'ShipmentList')$ShipmentList = $LangTransl;
			if($TranslCode == 'OPNList')$OPNList = $LangTransl;
			if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Destination')$Destination = $LangTransl;
			if($TranslCode == 'DestAdd')$DestAdd = $LangTransl;
			if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'DPCode')$DPCode = $LangTransl;
			if($TranslCode == 'ShipmentNo')$ShipmentNo = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
		endforeach;
		
		$PRJCODE1	= $PRJCODE;
		$CUST_CODE1	= $CUST_CODE;
		if(isset($_POST['PRJCODE1']))
		{
			$PRJCODE1	= $_POST['PRJCODE1'];
			$CUST_CODE1	= $_POST['CUST_CODE1'];
		}

		$SINV_ADDRESS	= '';
		//if($SINV_ADDRESS == '')
		//{
			$sqlSUPL		= "SELECT CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE1' AND CUST_STAT = '1' LIMIT 1";
			$resSUPL		= $this->db->query($sqlSUPL)->result();
			foreach($resSUPL as $rowSUPL):
				$SINV_ADDRESS	= $rowSUPL->CUST_ADD1;
			endforeach;
		//}
		
		/*if(isset($_POST['TTK_NUM1']))
		{
			$TTK_NUM1	= $_POST['TTK_NUM1'];
		}*/
		
		$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
		foreach($restPRJ1 as $rowPRJ1) :
			$PRJNAME1 	= $rowPRJ1->PRJNAME;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Silahkan centang Cek Total.";
			$alert2		= "Jumlah Uang Muka yang dimasukan terlalu besar.";
			$alert3		= "Pilih nama pelanggan.";
			$alert4		= "Pilih nomor order.";
			$isManual	= "Centang untuk kode manual.";
			$alertREJ	= "Tidak dapat direject. Sudah digunakan oleh Dokumen No.: ";
		}
		else
		{
			$alert1		= "Please Check the Total Checkbox.";
			$alert2		= "Total of DP Amount is too large.";
			$alert3		= "Please select a Customer.";
			$alert4		= "Select sales order number.";
			$isManual	= "Check to manual code.";
			$alertREJ	= "Can not be rejected. Used by document No.: ";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - SINV_AMOUNT
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
				$APPROVE_AMOUNT 	= $SINV_AMOUNT;
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

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/invoice.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
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
                    <input type="text" name="CUST_CODE1" id="CUST_CODE1" value="<?php echo $CUST_CODE1; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <!-- End -->
                
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">

		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" class="textbox" name="SINV_TYPE" id="SINV_TYPE" size="30" value="<?php echo $SINV_TYPE; ?>" />
		                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
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
		                                <input type="text" class="form-control" style="max-width:195px" name="SINV_NUMx" id="SINV_NUMx" value="<?php echo $SINV_NUM; ?>" disabled >
		                        		<input type="hidden" class="textbox" name="SINV_NUM" id="SINV_NUM" size="30" value="<?php echo $SINV_NUM; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="max-width:150px" name="SINV_CODE" id="SINV_CODE" value="<?php echo $SINV_CODE; ?>" >
		                            	<input type="text" class="form-control" name="SINV_CODEX" id="SINV_CODEX" value="<?php echo $SINV_CODE; ?>" disabled >
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
		                          	<div class="col-sm-9">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="SINV_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $SINV_DATE; ?>" style="width:105px">
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $DueDate; ?></label>
		                          	<div class="col-sm-9">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="SINV_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $SINV_DUEDATE; ?>" style="width:105px">
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $CustName; ?></label>
		                          	<div class="col-sm-9">
		                            	<select name="CUST_CODE1" id="CUST_CODE1" class="form-control select2" onChange="getCUST(this.value)" disabled>
		                                    <option value=""> --- </option>
		                                    <?php echo $i = 0;
		                                    //if($countCUST > 0)
		                                    //{
			                                    if($task == 'add')
			                                    {
			                                    	$sqlCUST	= "SELECT DISTINCT A.CUST_CODE, B.CUST_DESC, B.CUST_ADD1
																	FROM tbl_sn_header A
																		INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
																	WHERE A.SN_STAT = '3'
																		AND A.PRJCODE = '$PRJCODE'
																		AND A.SINV_CREATED = '0'";
												}
												else
												{
			                                    	$sqlCUST	= "SELECT DISTINCT A.CUST_CODE, B.CUST_DESC, B.CUST_ADD1
																	FROM tbl_sn_header A
																		INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
																	WHERE A.SN_STAT = '3'
																		AND A.PRJCODE = '$PRJCODE'";
												}
												$resCUST 		= $this->db->query($sqlCUST)->result();
		                                        foreach($resCUST as $row) :
													$CUST_CODE2	= $row->CUST_CODE;
													$CUST_DESC2	= $row->CUST_DESC;
		                                        ?>
		                                            <option value="<?php echo $CUST_CODE2; ?>" <?php if($CUST_CODE1 == $CUST_CODE2) { ?> selected <?php } ?>>
		                                        		<?php echo $CUST_DESC2; ?>
		                                        	</option>
		                                        <?php
		                                        endforeach;
		                                    //}
		                                    ?>
		                                </select>
		                                <input type="hidden" class="form-control" name="CUST_CODE" id="CUST_CODE" value="<?php echo $CUST_CODE; ?>" >
		                          	</div>
		                        </div>
		                        <script>
									function getCUST(CUST_CODE) 
									{
										document.getElementById("CUST_CODE1").value = CUST_CODE;
										document.frmsrch1.submitSrch1.click();
									}
								</script>
		                        <?php if($SINV_ADDRESS != '') { ?>
		                            <div class="form-group">
		                                <label for="inputName" class="col-sm-3 control-label"><?php echo $BillAddress; ?></label>
		                                <div class="col-sm-9">
		                                    <textarea name="SINV_ADDRESS" id="SINV_ADDRESS" cols="50" class="form-control"  style="height:87px;"><?php echo $SINV_ADDRESS; ?></textarea>
		                                </div>
		                            </div>
		                        <?php } ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
							<?php
		                        $SONUM	= '';
		                        $sqlSOH	= "SELECT A.SO_NUM, A.SO_CODE, A.CUST_CODE, CUST_DESC
		                                        FROM tbl_so_header A
		                                            INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
		                                        WHERE A.CUST_CODE = '$CUST_CODE' AND PRJCODE = '$PRJCODE' 
													AND SO_STAT = 3";
		                        $resSOH	= $this->db->query($sqlSOH)->result();
		                        foreach($resSOH as $row_1) :
		                            $SO_NUM1	= $row_1->SO_NUM;
		                            $SO_CODE	= $row_1->SO_CODE;
		                            $CUST_CODE	= $row_1->CUST_CODE;
		                            $CUST_DESC	= $row_1->CUST_DESC;

		                            $JIDExplode = explode('~', $SO_NUM);
		                            
		                            $SELECTED	= 0;
		                            foreach($JIDExplode as $i => $key)
		                            {
		                                $SONUM	= $key;
		                                if($SO_NUM1 == $SONUM)
		                                {
		                                    $SELECTED	= 1;
		                                }
		                            }
		                        endforeach;
		                        ?>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SalesOrder; ?></label>
				                    <div class="col-sm-9">
		                                <select name="SO_NUM1[]" id="SO_NUM1" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;No. SO" disabled>
				                            <option value=""> --- </option>
				                            <?php
		                                        $Disabled_1	= 0;
		                        				$sqlSOH	= "SELECT A.SO_NUM, A.SO_CODE, A.CUST_CODE, B.CUST_DESC
		                                                        FROM tbl_so_header A
		                                                            INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
		                                                        WHERE A.CUST_CODE = '$CUST_CODE' AND PRJCODE = '$PRJCODE' 
																	AND A.SO_STAT IN (3,6)";
		                                        $resSOH	= $this->db->query($sqlSOH)->result();
		                                        foreach($resSOH as $row_1) :
		                                            $SO_NUM1	= $row_1->SO_NUM;
		                                            $SO_CODE	= $row_1->SO_CODE;
		                                            $CUST_CODE	= $row_1->CUST_CODE;
		                                            $CUST_DESC	= $row_1->CUST_DESC;

				                                    $JIDExplode = explode('~', $SO_NUM);
				                                    $SONUM		= '';
				                                    $SELECTED	= 0;
				                                    foreach($JIDExplode as $i => $key)
				                                    {
				                                        $SONUM	= $key;
				                                        if($SO_NUM1 == $SONUM)
				                                        {
				                                            $SELECTED	= 1;
				                                        }
				                                    }
		                                            ?>
			                                            <option value="<?php echo "$SO_NUM1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?> title="<?php echo $SO_CODE; ?>">
					                                        <?php echo "$SO_CODE"; ?>
					                                    </option>
		                                            <?php
		                                        endforeach;
		                                    ?>
				                        </select>
				                        <input type="hidden" class="form-control" name="SO_NUM[]" id="SO_NUM" value="<?php echo $SONUM; ?>" >
				                    </div>
				                </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
		                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" style="max-width:250px" <?php if($SINV_STATUS != 1) { ?> disabled <?php } ?>>
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
		                                <textarea class="form-control" name="SINV_NOTES"  id="SINV_NOTES" style="height:60px"><?php echo $SINV_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                       	  	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="SINV_NOTES2"  id="SINV_NOTES2" style="height:60px" disabled><?php echo $SINV_NOTES2; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Cek Total</label>
		                            <div class="col-sm-4">
		                                <div class="input-group">
		                                    <span class="input-group-addon">
		                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalSINV()">
		                                    </span>
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="SINV_AMOUNT" id="SINV_AMOUNT" value="<?php echo $SINV_AMOUNT; ?>" >
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="SINV_AMOUNTX" id="SINV_AMOUNTX" value="<?php echo number_format($SINV_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" readonly>
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="SINV_AMOUNT_FINAL" id="SINV_AMOUNT_FINAL" value="<?php echo $SINV_AMOUNT_FINAL; ?>" >
		                                    <input type="text" class="form-control" style="max-width:120px; text-align:right" name="SINV_AMOUNT_FINALX" id="SINV_AMOUNT_FINALX" value="<?php echo number_format($SINV_AMOUNT_FINAL, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" readonly>
		                        		</div>
		                            </div>
		                            <div class="col-sm-5">
		                            	<label for="inputName" class="col-sm-2 control-label">PPn</label>
		                            	<div class="col-sm-10">
			                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="SINV_AMOUNT_PPN" id="SINV_AMOUNT_PPN" value="<?php echo $SINV_AMOUNT_PPN; ?>" >
			                                <input type="text" class="form-control" style="text-align:right" name="SINV_AMOUNT_PPNX" id="SINV_AMOUNT_PPNX" value="<?php echo number_format($SINV_AMOUNT_PPN, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" readonly>
			                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="SINV_AMOUNT_PPH" id="SINV_AMOUNT_PPH" value="<?php echo $SINV_AMOUNT_PPH; ?>" >
			                            </div>
		                            </div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                            <label for="inputName" class="col-sm-3 control-label">PPh</label>
		                            <div class="col-sm-9">
		                            <label>
		                                <select name="SINV_PPH" id="SINV_PPH" class="form-control" style="max-width:160px" onChange="selPPhStat(this.value)">
		                                	<option value=""> --- none ---</option>
		                                	<?php
												$sqlTLA 	= "SELECT TAXLA_NUM, TAXLA_CODE FROM tbl_tax_la";
												$resTLA 	= $this->db->query($sqlTLA)->result(); 
												foreach($resTLA as $rowTLA): 
													$TAXLA_NUM	= $rowTLA->TAXLA_NUM;
													$TAXLA_CODE	= $rowTLA->TAXLA_CODE;
													?>
													<option value="<?php echo $TAXLA_NUM; ?>"<?php if($TAXLA_NUM == $SINV_PPH) { ?> selected <?php } ?>><?php echo $TAXLA_CODE; ?></option>
													<?php
												endforeach;
											?>
		                                </select>
		                            </label>
		                            <label id="showPPh" <?php if($SINV_PPH == '') { ?> style="display:none" <?php } ?>>
		                            	<input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="SINV_PPHVAL" id="SINV_PPHVAL" value="<?php echo $SINV_PPHVAL; ?>" >
		                                <input type="text" class="form-control" style="max-width:120px; text-align:right" name="SINV_PPHVALX" id="SINV_PPHVALX" value="<?php echo number_format($SINV_PPHVAL, 2); ?>" onBlur="getAmountPPh(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </label>
		                            </div>
		                        </div>
		                        <script>
									function selPPhStat(thisVal)
									{
										if(thisVal == '')
											document.getElementById('showPPh').style.display = 'none';
										else
											document.getElementById('showPPh').style.display = '';
									}
									
									function getAmountPPn(thisVal)
									{
										
										INV_PPNVAL	= parseFloat(eval(thisVal).value.split(",").join(""));
										document.getElementById('INV_LISTTAXVAL').value 	= INV_PPNVAL;
										document.getElementById('INV_LISTTAXVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPNVAL)), 2));
									}
									
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
												$collID			= "$PRJCODE~$CUST_CODE1";
												$url_popLPM		= site_url('c_sales/c_s4l3InV/popupall_DP/?id='.$this->url_encryption_helper->encode_url($collID));
											?>       
											<script>
												var urlLPM 	= "<?php echo $url_popLPM;?>";
												function selectIR()
												{
													CUST_CODE	= document.getElementById('CUST_CODE').value;
													if(CUST_CODE == 'none')
													{
														swal('Please select a Supplier');
														document.getElementById('CUST_CODE').focus();
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
		                                <input type="hidden" class="form-control" style="max-width:160px; text-align:right" name="DP_AMOUNT" id="DP_AMOUNT" value="<?php echo $DP_AMOUNT_REM; ?>" >
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
											swal('<?php echo $alert2; ?>');
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
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $SINV_STAT; ?>">
		                                <?php
				                            $isDisabled = 1;
				                            if($SINV_STAT == 1 || $SINV_STAT == 4)
				                            {
				                                $isDisabled = 0;
				                            }
			                            ?>
		                                <select name="SINV_STAT" id="SINV_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
		                                    <?php
			                                    $disableBtn	= 0;
			                                    if($SINV_STAT == 5 || $SINV_STAT == 6 || $SINV_STAT == 9)
			                                    {
			                                        $disableBtn	= 1;
			                                    }
			                                    if($SINV_STAT != 1 AND $SINV_STAT != 4) 
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($SINV_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                            <option value="2"<?php if($SINV_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
			                                            <option value="3"<?php if($SINV_STAT == 3) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Approve</option>
			                                            <option value="4"<?php if($SINV_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                            <option value="5"<?php if($SINV_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                            <option value="6"<?php if($SINV_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
			                                            <option value="7"<?php if($SINV_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
			                                            <option value="9"<?php if($SINV_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($SINV_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                            <option value="2"<?php if($SINV_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                        <?php
			                                    }
		                                    ?>
		                                </select>
		                            </div>
		                        </div>
				                <script>
				                    function chkSTAT(selSTAT)
				                    {
										var STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
										if(STAT_BEFORE == 3)
										{
					                        if(selSTAT == 5 || selSTAT == 9)
					                        {
				                                document.getElementById('btnREJECT').style.display 	= '';
				                                document.getElementById('SINV_NOTES2').disabled		= false;
					                        }
					                        else if(selSTAT == 6)
					                        {
					                            document.getElementById('btnREJECT').style.display 	= '';
				                                document.getElementById('SINV_NOTES2').disabled		= false;
					                        }
					                        else if(selSTAT == 3)
					                        {
				                                document.getElementById('btnREJECT').style.display 	= 'none';
				                                document.getElementById('SINV_NOTES2').disabled		= true;
					                        }
				                    	}
				                    }
				                </script>
		                        <?php
									$url_AddItem	= site_url('c_sales/c_s4l3InV/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <div class="form-group" style="display: none;">
		                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
		                                <script>
		                                    var url = "<?php echo $url_AddItem;?>";
		                                    function selectitem()
		                                    {
												PRJCODE		= document.getElementById('PRJCODE').value;
												CUST_CODE	= document.getElementById('CUST_CODE').value;
												if(CUST_CODE == '')
												{
													swal('<?php echo $alert3; ?>');
													document.getElementById('CUST_CODE').focus()
													return false;
												}
												
												SO_NUM	= document.getElementById('SO_NUM').value;
												if(SO_NUM == '')
												{
													swal('<?php echo $alert4; ?>');
													return false;
												}
												
		                                        title = 'Select Item';
		                                        w = 1000;
		                                        h = 550;
		                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                        var left = (screen.width/2)-(w/2);
		                                        var top = (screen.height/2)-(h/2);
												return window.open(url+'&cu57c0d3='+CUST_CODE+'&pr1h0ec0c0d3='+PRJCODE+'&s0n_0='+SO_NUM, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                                    }
		                                </script>
		                                <button class="btn btn-success" type="button" onClick="selectitem();">
		                                <i class="fa fa-truck"></i>&nbsp;&nbsp;<?php echo $ShipmentNo; ?>
		                                </button>
		                        	</div>
		                        </div>
							</div>
						</div>
					</div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                  	<tr style="background:#CCCCCC">
                                      	<th width="3%" height="25" style="text-align:center; vertical-align: middle;">No.</th>
                                      	<th width="10%" style="text-align:center; display:none" nowrap><?php echo $ShipmentNo; ?></th>
                                      	<th width="4%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ShipmentNo; ?></th>
                                      	<th width="2%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Date; ?></th>
                                      	<th width="4%" style="text-align:center; display: none;" nowrap><?php echo $ReferenceNumber; ?></th>
                                      	<th width="16%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Destination; ?></th>
                                        <th width="38%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $DestAdd; ?></th>
                                      	<th width="8%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $AmountReceipt; ?><br>(Rp)</th>
                                      	<th width="8%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?><br>(Rp)</th>
                                      	<th width="7%" style="text-align:center; vertical-align: middle;" nowrap>PPn<br>(Rp)</th>
                                      	<th width="8%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $TotAmount; ?><br>(Rp)</th>
                                  	</tr>
                                  	<?php
                                        $resultC	= 0;
                                        if($task == 'edit')
                                        {
                                            $TOT_AMOUNT2	= 0;
                                            $resSNHC		= 0;		
											
                                            $sqlSNHC	= "tbl_sinv_detail A
																INNER JOIN tbl_sinv_header B ON B.SINV_NUM = A.SINV_NUM
																	AND B.PRJCODE = '$PRJCODE'
															WHERE A.SINV_NUM = '$SINV_NUM'";
                                            $resSNHC	= $this->db->count_all($sqlSNHC);
											
                                            $sqlSNH	= "SELECT A.*
														FROM tbl_sinv_detail A
															INNER JOIN tbl_sinv_header B ON B.SINV_NUM = A.SINV_NUM
																AND B.PRJCODE = '$PRJCODE'
														WHERE A.SINV_NUM = '$SINV_NUM'";
                                            $resSNH 	= $this->db->query($sqlSNH)->result();
                                            
                                            $collIR			= '';	
                                            if($resSNHC > 0)
                                            {
                                                $TOT_AMOUNT1	= 0;
                                                $TOT_AMOUNT2	= 0;
                                                $TOT_DISC		= 0;
                                                $TOT_TAX1		= 0;
                                                $TOT_TAX2		= 0;
                                                $collIR			= '';
                                                foreach($resSNH as $row) :
                                                    $currentRow  	= ++$i;											
                                                    $SINV_NUM 		= $row->SINV_NUM;
                                                    $SINV_CODE		= $row->SINV_CODE;
                                                    $SN_NUM		 	= $row->SN_NUM;
													
													$sqlSN			= "SELECT SN_CODE, SN_DATE, CUST_CODE, CUST_ADDRESS
																		FROM tbl_sn_header
																		WHERE SN_NUM = '$SN_NUM' LIMIT 1";
													$resSN 			= $this->db->query($sqlSN)->result();															
													foreach($resSN as $rowSN) :
														$SN_CODE 		= $rowSN->SN_CODE;
														$SN_DATE 		= $rowSN->SN_DATE;
														$CUST_CODE 		= $rowSN->CUST_CODE;
														$CUST_ADDRESS	= $rowSN->CUST_ADDRESS;
														
														$CUST_DESC		= '';
														$sqlCUST 		= "SELECT CUST_DESC 
																			FROM tbl_customer 
																			WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
														$resCUST 		= $this->db->query($sqlCUST)->result();
														foreach($resCUST as $rowCUST) :															
															$CUST_DESC	= $rowCUST->CUST_DESC;
														endforeach;
													endforeach;
													
                                                    $REF_NUM		= $row->REF_NUM;
                                                    $REF_CODE 		= $row->REF_CODE;
                                                    $PRJCODE		= $row->PRJCODE;
                                                    $ITM_CODE 		= $row->ITM_CODE;
                                                    $ITM_UNIT		= $row->ITM_UNIT;
                                                    $ITM_QTY		= $row->ITM_QTY;
                                                    $ITM_UNITP		= $row->ITM_UNITP;
                                                    $ITM_DISC		= $row->ITM_DISC;
                                                    $ITM_AMOUNT		= $row->ITM_AMOUNT;
                                                    $TOT_AMNV		= number_format($ITM_AMOUNT, 2);
                                                    $TAX_AMOUNT_PPn	= $row->TAX_AMOUNT_PPn1;
                                                    $TAX_AMOUNT_PPh	= $row->TAX_AMOUNT_PPh1;
                                                    $TAXCODE1		= $row->TAXCODE1;
                                                    $ITM_UNIT		= $row->ITM_UNIT;

														
													$ITM_NM		= '';
													$sqlITMNM 	= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
													$resITMNM 	= $this->db->query($sqlITMNM)->result();
													foreach($resITMNM as $rowITMNM) :															
														$ITM_NM	= $rowITMNM->ITM_NAME;
													endforeach;

													$TOT_VOLM 	= 0;
													$TOT_AMN	= number_format(0, 2); 
													//$TOT_AMNV	= number_format(0, 2); 
													$sqlITMD 	= "SELECT SUM(QRC_VOLM) AS TOT_VOLM, SUM(QRC_VOLM * QRC_PRICE) AS TOT_AMN,
																	ITM_UNIT
																	FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM' GROUP BY ITM_CODE";
													$resITMD 	= $this->db->query($sqlITMD)->result();
													foreach($resITMD as $rowITMD) :
														$TOT_VOLM	= $rowITMD->TOT_VOLM;
														$TOT_AMN	= $rowITMD->TOT_AMN;
														//$TOT_AMNV	= number_format($rowITMD->TOT_AMN, 2);	// SEHARUSNYA JUMLAH TOTAL SO
														$ITM_UNIT	= $rowITMD->ITM_UNIT;
													endforeach;

													// IF NOT FROM QRC
														$sqlQRC 	= "tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM'";
														$resQRC 	= $this->db->count_all($sqlQRC);
														if($resQRC == 0)
														{
															$sqlITM = "SELECT SUM(SN_VOLM) AS TOT_VOLM, SUM(SN_VOLM * SN_PRICE) AS TOT_AMN,
																			ITM_UNIT
																		FROM tbl_sn_detail WHERE SN_NUM = '$SN_NUM' GROUP BY ITM_CODE";
															$resITM = $this->db->query($sqlITM)->result();
															foreach($resITM as $rowITM) :
																$TOT_VOLM	= $rowITM->TOT_VOLM;
																$TOT_AMN	= $rowITM->TOT_AMN;
																//$TOT_AMNV	= number_format($rowITM->TOT_AMN, 2);
																$ITM_UNIT	= $rowITM->ITM_UNIT;
															endforeach;
														}

													// CEK SN PRICE
														$SN_PRICE 	= 0;
														$sqlSNPRC 	= "SELECT SN_PRICE FROM tbl_sn_detail
																		WHERE SN_NUM = '$SN_NUM' AND ITM_CODE = '$ITM_CODE'";
														$resSNPRC 	= $this->db->query($sqlSNPRC)->result();
														foreach($resSNPRC as $rowSNPRC) :
															$SN_PRICE	= $rowSNPRC->SN_PRICE;
														endforeach;
                                                    ?>
                                                      	<tr id="tr_<?php echo $currentRow; ?>">
                                                          	<td width="3%" height="25" style="text-align:center;">
                                                          		<?php echo "$currentRow."; ?>
                                                              	<a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs" style="display: none;"><i class="fa fa-trash-o"></i></a>
                                                              	<input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onclick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                              	<input type="Checkbox" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="" style="display:none" >
															</td>
															<td width="10%" style="text-align:left; display:none" nowrap>
																<?php print $SINV_NUM; ?>
																<input type="hidden" id="data<?php echo $currentRow; ?>SINV_NUM" name="data[<?php echo $currentRow; ?>][SINV_NUM]" value="<?php print $SINV_NUM; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>SINV_CODE" name="data[<?php echo $currentRow; ?>][SINV_CODE]" value="<?php print $SINV_CODE; ?>"><input type="hidden" id="data<?php echo $currentRow; ?>SN_NUM" name="data[<?php echo $currentRow; ?>][SN_NUM]" value="<?php print $SN_NUM; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>REF_NUM" name="data[<?php echo $currentRow; ?>][REF_NUM]" value="<?php print $SO_NUM; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>REF_CODE" name="data[<?php echo $currentRow; ?>][REF_CODE]" value="<?php print $SO_CODE; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>">
															</td>
															<td width="4%" style="text-align:left" nowrap>
															  	<?php print $SN_CODE; ?>
															</td>
															<td width="2%" style="text-align:center" nowrap>
															  	<?php print date('d M Y', strtotime($SN_DATE)); ?>
															</td>
															<td width="4%" style="text-align:center; display: none;" nowrap>
															  	<?php echo $SO_CODE; ?>
															</td>
															<td nowrap>
															  	<?php print $CUST_DESC; ?>
															</td>
															<td>
															  	<?php
															  		print $CUST_ADDRESS."<br>";
															  		$ITM_UNITPV 	= number_format($ITM_UNITP,2);
															  		$SN_PRICEV 		= number_format($SN_PRICE,2);
															  		$SN_TOTAMN 		= $TOT_VOLM * $SN_PRICE;
															  		$SN_TOTAMNV 	= number_format($SN_TOTAMN,2);

													  				$ITMDISC 		= number_format($ITM_DISC,2);
													  				$ITMTOTV 		= number_format($ITM_AMOUNT-$ITM_DISC,2);
													  				$ITMTOT 		= $ITM_AMOUNT-$ITM_DISC;

															  		if($ITM_UNITP > $SN_PRICE)
															  		{
															  			$DEVPRC 	= $ITM_UNITP - $SN_PRICE;
															  			$DEVTOT 	= $ITMTOT - $SN_TOTAMN;
															  			$STATPRC	= " ( <i class='glyphicon glyphicon-chevron-up text-green'>".number_format($DEVPRC, 2)." </i> ) ";
															  			$STATTPRC	= "<i class='glyphicon glyphicon-chevron-up text-green'>".number_format($DEVTOT, 2)." </i>";
															  		}
															  		else
															  		{
															  			$DEVPRC 	= $ITM_UNITP - $SN_PRICE;
															  			$DEVTOT 	= $TOT_AMN - $SN_TOTAMN;
															  			$STATPRC	= " ( <i class='glyphicon glyphicon-triangle-bottom text-danger'>".number_format($DEVPRC, 2)." </i> ) ";
															  			$STATTPRC	= "<i class='glyphicon glyphicon-triangle-bottom text-danger'>".number_format($DEVTOT, 2)." </i>";
															  		}

															  		$TOT_VOLMV 		= number_format($TOT_VOLM, 2);
															  	?>
															  	<strong><i class='fa fa-truck margin-r-5'></i> <?php echo "$Description $STATTPRC"; ?> </strong>
														  		<div style="margin-left: 20px">
															  		<p class='text-muted'>
															  			<?php
															  				//echo "$ITM_NM ($TOT_VOLMV $ITM_UNIT @$SN_PRICEV = $SN_TOTAMNV) /sn<br>";
															  				//echo "$TOT_VOLMV $ITM_UNIT @$ITM_UNITPV $STATPRC = $TOT_AMNV $STATTPRC";
															  				echo "$ITM_NM<br>$TOT_VOLMV@$SN_PRICEV = $SN_TOTAMNV /sn<br>";
															  				echo "$TOT_VOLMV@$ITM_UNITPV $STATPRC-$ITMDISC = $ITMTOTV";
															  			?>
															  		</p>
															  	</div>
															</td>
                                                          	<td style="text-align:right">
                                                          		<?php echo number_format($ITM_AMOUNT, 2); ?>
																<input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="ITM_UNITP<?php echo $currentRow; ?>" id="ITM_UNITP<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITM_UNITP, 2); ?>" disabled>
																<input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNITP" name="data[<?php echo $currentRow; ?>][ITM_UNITP]" value="<?php echo $ITM_UNITP; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>">
																<input type="hidden" id="data<?php echo $currentRow; ?>ITM_QTY" name="data[<?php echo $currentRow; ?>][ITM_QTY]" value="<?php echo $ITM_QTY; ?>">
															</td>
                                                          	<td style="text-align:right">
                                                          		<?php echo number_format($ITM_DISC, 2); ?>
																<input type="hidden" id="data<?php echo $currentRow; ?>ITM_DISC" name="data[<?php echo $currentRow; ?>][ITM_DISC]" value="<?php echo $ITM_DISC; ?>">
															</td>
                                                          	<td style="text-align:right">
                                                          		<?php echo number_format($TAX_AMOUNT_PPn, 2); ?>
																<input type="hidden" id="data<?php echo $currentRow; ?>TAXCODE1" name="data[<?php echo $currentRow; ?>][TAXCODE1]" value="<?php echo $TAXCODE1; ?>">
																<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="TAX_AMOUNT_PPn1<?php echo $currentRow; ?>" id="TAX_AMOUNT_PPn1<?php echo $currentRow; ?>" value="<?php echo number_format($TAX_AMOUNT_PPn, 2); ?>" disabled>
																<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn1]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPn1" value="<?php echo $TAX_AMOUNT_PPn; ?>">
																<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPh1]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPh1" value="<?php echo $TAX_AMOUNT_PPh; ?>">
															</td>
															<td style="text-align:right">
                                                          		<?php echo number_format($ITM_AMOUNT-$ITM_DISC+$TAX_AMOUNT_PPn-$TAX_AMOUNT_PPh, 2); ?>
															  	<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_AMOUNT<?php echo $currentRow; ?>" id="ITM_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_AMOUNT, 2); ?>" disabled>
															  	<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_AMOUNT]" id="data<?php echo $currentRow; ?>ITM_AMOUNT" value="<?php echo $ITM_AMOUNT; ?>">
															</td>
                                                      </tr>
                                                  <?php
                                                endforeach;
                                            }
                                        }
                                    ?>   
                                  	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                              	</table>
                          	</div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-9">
							<?php
	                            if($task=='add')
	                            {
	                                if(($SINV_STAT == 1 || $SINV_STAT == 4) && $ISCREATE == 1)
	                                {
	                                    ?>
	                                        <button class="btn btn-primary" id="btnSave">
	                                        <i class="fa fa-save"></i></button>&nbsp;
	                                    <?php
	                                }
	                            }
	                            else
	                            {
	                                if(($SINV_STAT == 1 || $SINV_STAT == 4) && $ISCREATE == 1)
	                                {
	                                    ?>
	                                        <button class="btn btn-primary" id="btnSave">
	                                        <i class="fa fa-save"></i></button>&nbsp;
	                                    <?php
	                                }
	                                elseif($SINV_STAT == 3)
	                                {
	                                    ?>
	                                        <button class="btn btn-primary" id="btnREJECT" style="display:none" >
	                                        <i class="fa fa-save"></i></button>&nbsp;
	                                    <?php
	                                }
	                                /*elseif($resAPP == 1)
	                                {
	                                    ?>
	                                        <button class="btn btn-primary" id="btnREJECT" style="display:none" >
	                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
	                                        </button>&nbsp;
	                                    <?php
	                                }*/
	                            }
	                            $backURL	= site_url('c_sales/c_s4l3InV/gls4l3Inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
	                        ?>
                        </div>
                    </div>
                </form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $SINV_NUM;
                        $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH	= $this->db->count_all($sqlCAPPH);
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
		            <?php
		                $DefID      = $this->session->userdata['Emp_ID'];
		                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		                if($DefID == 'D15040004221')
		                    echo "<font size='1'><i>$act_lnk</i></font>";
		            ?>
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
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var SINV_NUM 	= "<?php echo $DocNumber; ?>";
		var SINV_CODE 	= "<?php echo $SINV_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		SN_NUM 			= arrItem[0];
		SN_CODE 		= arrItem[1];
		SN_DATE 		= arrItem[2];
		CUST_CODE 		= arrItem[3];
		CUST_DESC		= arrItem[4];
		CUST_ADDRESS 	= arrItem[5];
		SN_TOTCOST 		= arrItem[6];
		SN_TOTPPN		= arrItem[7];
		SN_TOTPPH		= arrItem[8];
		SN_TOTDISC		= arrItem[9];
		SO_NUM			= arrItem[10];
		SO_CODE			= arrItem[11];
		
		var TAXCODE1	= '';
		var TAXCODE2	= '';
		if(SN_TOTPPN != '')
			var TAXCODE1 = 'TAX01';
			
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// SHIPMENT NO.
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+SN_CODE+'<input type="hidden" id="data'+intIndex+'SINV_NUM" name="data['+intIndex+'][SINV_NUM]" value="'+SINV_NUM+'"><input type="hidden" id="data'+intIndex+'SINV_CODE" name="data['+intIndex+'][SINV_CODE]" value="'+SINV_CODE+'"><input type="hidden" id="data'+intIndex+'SN_NUM" name="data['+intIndex+'][SN_NUM]" value="'+SN_NUM+'"><input type="hidden" id="data'+intIndex+'REF_NUM" name="data['+intIndex+'][REF_NUM]" value="'+SO_NUM+'"><input type="hidden" id="data'+intIndex+'REF_CODE" name="data['+intIndex+'][REF_CODE]" value="'+SO_CODE+'"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'">';
		
		// DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+SN_DATE+'';
		
		// REF. NUM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+SN_CODE+'';
		
		// DESTINATION
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+CUST_DESC+'';
		
		// DESTINATION ADDRESS
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = ''+CUST_ADDRESS+'';
		
		// INV. AMOUNT
		SN_TOTCOST		= parseFloat(Math.abs(SN_TOTCOST));
		SN_TOTCOSTV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SN_TOTCOST)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="ITM_UNITP'+intIndex+'" id="ITM_UNITP'+intIndex+'" size="10" value="'+SN_TOTCOSTV+'" disabled><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+SN_NUM+'"><input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="LS"><input type="hidden" id="data'+intIndex+'ITM_QTY" name="data['+intIndex+'][ITM_QTY]" value="1"><input type="hidden" id="data'+intIndex+'ITM_UNITP" name="data['+intIndex+'][ITM_UNITP]" value="'+SN_TOTCOST+'"><input type="hidden" id="data'+intIndex+'ITM_DISC" name="data['+intIndex+'][ITM_DISC]" value="'+SN_TOTDISC+'">';
		
		// ITEM TAX
		SN_TOTPPN		= parseFloat(Math.abs(SN_TOTPPN));
		SN_TOTPPNV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SN_TOTPPN)),decFormat));
		SN_TOTPPH		= parseFloat(Math.abs(SN_TOTPPH));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" id="data'+intIndex+'TAXCODE1" name="data['+intIndex+'][TAXCODE1]" value="'+TAXCODE1+'"><input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="TAX_AMOUNT_PPn1'+intIndex+'" id="TAX_AMOUNT_PPn1'+intIndex+'" value="'+SN_TOTPPNV+'" disabled><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAX_AMOUNT_PPn1]" id="data'+intIndex+'TAX_AMOUNT_PPn1" value="'+SN_TOTPPN+'"><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAX_AMOUNT_PPh1]" id="data'+intIndex+'TAX_AMOUNT_PPh1" value="'+SN_TOTPPH+'">';
		
		// ITM TOTAL
		ITM_AMOUNT		= parseFloat(Math.abs(SN_TOTCOST));
		ITM_AMOUNTV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SN_TOTCOST)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_AMOUNT'+intIndex+'" id="ITM_AMOUNT'+intIndex+'" value="'+ITM_AMOUNTV+'" disabled><input style="text-align:right" type="hidden" name="data['+intIndex+'][ITM_AMOUNT]" id="data'+intIndex+'ITM_AMOUNT" value="'+ITM_AMOUNT+'">';
		document.getElementById('totalrow').value = intIndex;
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function checkTotalSINV()
	{
		var decFormat	= document.getElementById('decFormat').value;
		totalrow		= document.getElementById('totalrow').value;
		SINV_TOT_AM		= 0;
		SINV_TOT_PPN	= 0;
		SINV_TOT_PPH	= 0;
		SINV_GTOTAL		= 0;
		SINV_TOT_FINAL	= 0;
		for (i = 1; i <= totalrow; i++) 
		{
			SINV_REF1_AM	= document.getElementById('data'+i+'ITM_AMOUNT').value;
			SINV_REF1_DISC	= document.getElementById('data'+i+'ITM_DISC').value;
			SINV_REF1_PPN	= document.getElementById('data'+i+'TAX_AMOUNT_PPn1').value;
			SINV_REF1_PPH	= document.getElementById('data'+i+'TAX_AMOUNT_PPh1').value;
			SINV_TOT_AM		= parseFloat(SINV_TOT_AM) + parseFloat(SINV_REF1_AM);
			SINV_TOT_PPN	= parseFloat(SINV_TOT_PPN) + parseFloat(SINV_REF1_PPN);
			SINV_TOT_PPH	= parseFloat(SINV_TOT_PPH) + parseFloat(SINV_REF1_PPH);
			SINVTOTFINAL	= parseFloat(SINV_REF1_AM) - parseFloat(SINV_REF1_DISC) + parseFloat(SINV_REF1_PPN) - parseFloat(SINV_REF1_PPH);
			SINV_TOT_FINAL 	= parseFloat(SINV_TOT_FINAL) + parseFloat(SINVTOTFINAL);
		}

		SINV_GTOTAL	= parseFloat(SINV_TOT_AM) + parseFloat(SINV_TOT_PPN);
		document.getElementById('SINV_AMOUNT').value  		= SINV_TOT_AM;
		document.getElementById('SINV_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SINV_TOT_AM)),decFormat));
		document.getElementById('SINV_AMOUNT_FINAL').value 	= SINV_TOT_FINAL;
		document.getElementById('SINV_AMOUNT_FINALX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SINV_TOT_FINAL)),decFormat));
		
		document.getElementById('SINV_AMOUNT_PPN').value 	= SINV_TOT_PPN;
		document.getElementById('SINV_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SINV_TOT_PPN)),decFormat));

		document.getElementById('SINV_AMOUNT_PPH').value 	= SINV_TOT_PPH;
	}
	
	function checkInp()
	{
		chkTotal	= document.getElementById('chkTotal').checked;
		SINV_NOTES	= document.getElementById('SINV_NOTES').value;

		if(SINV_NOTES == '')
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#SINV_NOTES').focus();
			});
			return false;
		}
		
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