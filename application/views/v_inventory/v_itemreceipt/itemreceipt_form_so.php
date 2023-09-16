<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 16 Februari 2019
	* File Name	= itemreceipt_form_so.php
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
$DEPCODE 		= $this->session->userdata['DEPCODE'];
$DEPCODEX 		= $this->session->userdata['DEPCODE'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
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
	
	$year		= substr($year, 2, 4);
	$Patt_Year 	= (int)$Pattern_YearAktive;

	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	/*$myCount = $this->db->count_all('tbl_ir_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ir_header
			WHERE Patt_Year = $Patt_Year AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$sql 		= "tbl_ir_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->count_all($sql);
	$myMax 		= $result+1;
	
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
		$groupPattern = "$PRJCODE$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$PRJCODE$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$PRJCODE$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$PRJCODE$year";
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
	
	$DocNumber		= "$Pattern_Code$groupPattern-$lastPatternNumb";
	
	$IR_NUM 		= $DocNumber;
	$IR_NUM_BEF		= '';
	$IR_CODE 		= $lastPatternNumb;
	
	//$IRCODE		= substr($lastPatternNumb, -4);
	$IRCODE			= $lastPatternNumb;
	$IRYEAR			= date('y');
	$IRMONTH		= date('m');
	$IR_CODE		= "$Pattern_Code"."G".".$IRCODE.$IRYEAR.$IRMONTH"; // MANUAL CODE
	
	$IR_SOURCE		= 4;
	$IR_DATE		= date('d/m/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$IR_REFER		= '';
	$IR_REFER1		= '';
	$IR_REFER2		= '';
	$PO_NUM			= '';
	$PO_CODE		= '';
	$PR_NUM			= '';
	$PR_CODE		= '';
	$IR_AMOUNT		= 0;
	$APPROVE		= 0;
	$IR_STAT		= 1;
	$IR_NOTE		= '';
	$IR_NOTE2		= '';
	$REVMEMO		= '';
	$WH_CODE		= '';
	$Patt_Number	= $lastPatternNumb1;
	$PO_NUMX		= '';
	
	if(isset($_POST['SO_NUMX']))
	{
		$PO_NUMX	= $_POST['SO_NUMX'];
		$PO_NUM		= $_POST['SO_NUMX'];
	}
	
	$sqlSOH		= "SELECT SO_CODE, OFF_NUM, OFF_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
					FROM tbl_so_header WHERE SO_NUM = '$PO_NUMX' AND PRJCODE = '$PRJCODE' LIMIT 1";
	$resSOH 	= $this->db->query($sqlSOH)->result();
	foreach($resSOH as $row1):
		$PO_CODE	= $row1->SO_CODE;
		$PR_NUM		= $row1->OFF_NUM;
		$PR_CODE	= $row1->OFF_CODE;
		$CCAL_NUM	= $row1->CCAL_NUM;
		$CCAL_CODE	= $row1->CCAL_CODE;
		$IR_REFER	= "$CCAL_NUM~$CCAL_CODE";
		$BOM_NUM	= $row1->BOM_NUM;
		$BOM_CODE	= $row1->BOM_CODE;
		$IR_REFER1	= "$BOM_NUM~$BOM_CODE";
	endforeach;
	$GT_TOTAMOUNT	= 0;
	//echo $TERM_PAY;
	$IR_LOC			= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_ir_header~$Pattern_Length";
	$dataTarget		= "IR_CODE";
}
else
{
	$isSetDocNo = 1;
	$IR_NUM 		= $default['IR_NUM'];
	$DocNumber 		= $default['IR_NUM'];
	$IR_NUM_BEF		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_SOURCE 		= $default['IR_SOURCE'];
	$IR_DATE 		= $default['IR_DATE'];
	$IR_DATE		= date('d/m/Y', strtotime($IR_DATE));
	$IR_DUEDATE		= $default['IR_DUEDATE'];
	$IR_DUEDATE		= date('d/m/Y', strtotime($IR_DUEDATE));
	$PRJCODE 		= $default['PRJCODE'];
	$DEPCODE 		= $default['DEPCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$PO_NUM 		= $default['PO_NUM'];
	$PO_NUMX 		= $default['PO_NUM'];
	$PO_CODE 		= $default['PO_CODE'];
	$PR_NUM 		= $default['PR_NUM'];
	$PR_CODE 		= $default['PR_CODE'];
	$IR_REFER 		= $default['IR_REFER'];
	$IR_REFER1		= $default['IR_REFER1'];
	$IR_REFER2		= $default['IR_REFER2'];
	$IR_AMOUNT 		= $default['IR_AMOUNT'];
	$TERM_PAY 		= $default['TERM_PAY'];
	$TRXUSER 		= $default['TRXUSER'];
	$APPROVE 		= $default['APPROVE'];
	$IR_STAT 		= $default['IR_STAT'];
	$INVSTAT 		= $default['INVSTAT'];
	$IR_NOTE 		= $default['IR_NOTE'];
	$IR_NOTE2 		= $default['IR_NOTE2'];
	$REVMEMO		= $default['REVMEMO'];
	$WH_CODE		= $default['WH_CODE'];
	$IR_LOC			= $default['IR_LOC'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Number	= $default['Patt_Number'];
	$GT_TOTAMOUNT	= $IR_AMOUNT;
}

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

// Warehouse List
$sqlWHC		= "tbl_warehouse WHERE PRJCODE = '$PRJCODE_HO'";
$resWHC		= $this->db->count_all($sqlWHC);

$sqlWH 		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE_HO' ORDER BY WH_NAME";
$resWH		= $this->db->query($sqlWH)->result();

// REJECT FUNCTION
	// CEK ACCESS OTORIZATION
		$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN020'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		//$resAPP	= $this->db->count_all($sqlAPP);
	// CEK IR
		$DOC_NO		= '';
		$isUSED		= 0;
		$sqlIRC		= "SELECT TTK_CREATED FROM tbl_ir_header WHERE IR_NUM = '$IR_NUM'";
		$resIRC		= $this->db->query($sqlIRC)->result();
		foreach($resIRC as $rowIRC):
			$isUSED	= $rowIRC->TTK_CREATED;
		endforeach;
		if($isUSED == 1)
		{
			$sqlIR 	= "SELECT TTK_NUM FROM tbl_ttk_detail WHERE TTK_REF1 = '$IR_NUM' LIMIT 1";
			$resIR	= $this->db->query($sqlIR)->result();
			foreach($resIR as $rowIR):
				$DOC_NO	= $rowIR->TTK_NUM;
			endforeach;
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
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;		
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
			if($TranslCode == 'Warehouse')$Warehouse = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Receipt')$Receipt = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'List')$List = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'LocPlace')$LocPlace = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		$secGenCode	= base_url().'index.php/c_inventory/c_ir180c15/genCode/'; // Generate Code
		
		if($LangID == 'IND')
		{
			$alert1		= "Jumlah yang akan dipesan lebih besar dari sisa permintaan";
			$alert2		= "Silahkan Tentukan No. PO.";
			$alert3		= "Belum ada detail item.";
			$alert4		= "Silahkan tulis alasan revisi/tolak/membatalkan dokumen.";
			$alert5		= "Jumlah penerimaan tidak boleh kosong.";
			$alert6		= "Silahkan tentukan pengirim.";
			$alert7		= "Silahkan tentukan gudang penyimpanan.";
			$alert8		= "Nomor surat jalan pelanggan tidak boleh kosong.";
			$isManual	= "Centang untuk kode manual.";
			$alertREJ	= "Tidak dapat diproses. Sudah digunakan oleh Dokumen No.: ";
		}
		else
		{
			$alert1		= "Remain Qty is greather then Remain  Request QTY";
			$alert2		= "Please select PO Number";
			$alert3		= "Item can not be empty";
			$alert4		= "Plese input the reason why you revise/reject/void the document.";
			$alert5		= "Item qty can not be empty.";
			$alert6		= "Please select a sender.";
			$alert7		= "Please specify a warehouse.";
			$alert8		= "Customer Delivery Order No. can not be empty.";
			$isManual	= "Check to manual code.";
			$alertREJ	= "Can not be processed. Used by document No.: ";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - IR_AMOUNT
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
				$APPROVE_AMOUNT 	= $IR_AMOUNT;
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
			    <?php echo $h2_title; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">	
		    <div class="row">
            	<!-- Mencari Kode Purchase Order Number -->
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="text" name="SO_NUMX" id="SO_NUMX" class="textbox" value="<?php echo $PO_NUMX; ?>" />
                    <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
                <!-- End -->
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
                                <input type="hidden" name="IRDate" id="IRDate" value="">
                            </td>
                            <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                        </tr>
                    </table>
                </form>
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                    	<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
		                    	<input type="hidden" name="PageFrom" id="PageFrom" value="SO">
		                    	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptCode ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:175px" disabled >
		                                <input type="hidden" name="Patt_Year" id="Patt_Year" value="<?php echo $Patt_Year; ?>">
		                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
		                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
		                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
		                            	<input type="text" class="form-control" name="IR_CODEX" id="IR_CODEX" value="<?php echo $IR_CODE; ?>" disabled >
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?> </label>
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
		                            <label for="inputName" class="col-sm-3 control-label">SJ No.</label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="IR_REFER2" id="IR_REFER2" value="<?php echo $IR_REFER2; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <?php
												if($task == 'add')
												{
													?>
		                                            <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
		                                            <?php
												}
												else
												{
													?>
		                                            <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
		                                            <?php
												}
											?>
		                                    
		                            	</div>
		                            </div>
		                        </div>
								<script>
		                            function getIR_NUM(selDate)
		                            {
		                                document.getElementById('IRDate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
			
									$(document).ready(function()
									{
										$(".tombol-date").click(function()
										{
											var add_IR	= "<?php echo $secGenCode; ?>";
											var formAction 	= $('#sendDate')[0].action;
											var data = $('.form-user').serialize();
											$.ajax({
												type: 'POST',
												url: formAction,
												data: data,
												success: function(response)
												{
													var myarr = response.split("~");
													document.getElementById('IR_NUM1').value 	= myarr[0];
													document.getElementById('IR_NUM').value 	= myarr[0];
													document.getElementById('IR_CODE').value 	= myarr[1];
												}
											});
										});
									});
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SourceDocument; ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE1" value="1" <?php if($IR_SOURCE == 1) { ?> checked <?php } ?>>
		                                &nbsp;&nbsp;Direct&nbsp;&nbsp;&nbsp;&nbsp;
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE2" value="2" <?php if($IR_SOURCE == 2) { ?> checked <?php } ?>>
		                                &nbsp;&nbsp;MR&nbsp;&nbsp;&nbsp;&nbsp;
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" <?php if($IR_SOURCE == 3) { ?> checked <?php } ?>>
		                                &nbsp;&nbsp;PO
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE4" value="4" <?php if($IR_SOURCE == 4) { ?> checked <?php } ?>>
		                                &nbsp;&nbsp;SO
		                            </div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $RefNumber; ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search; ?> </button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PO_NUM" id="PO_NUM" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="PO_CODE" id="PO_CODE" style="max-width:160px" value="<?php echo $PO_CODE; ?>" >
		                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUM; ?>" >
		                                    <input type="hidden" class="form-control" name="PR_CODE" id="PR_CODE" style="max-width:160px" value="<?php echo $PR_CODE; ?>" >
		                                    <input type="hidden" class="form-control" name="IR_REFER" id="IR_REFER" style="max-width:160px" value="<?php echo $IR_REFER; ?>" >
		                                    <input type="hidden" class="form-control" name="IR_REFER1" id="IR_REFER1" style="max-width:160px" value="<?php echo $IR_REFER1; ?>" >
		                                    <input type="text" class="form-control" name="PO_NUM1" id="PO_NUM1" style="max-width:200px" value="<?php echo $PO_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?>>
		                                </div>
		                            </div>
		                        </div>
								<?php
									$url_selIR_CODE		= site_url('c_inventory/c_ir180c15/all180c155o/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selIR_CODE;?>";
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
		                           		<input type="text" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
		                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:300px" >
		                                  	<?php
		                                        if($resPLC > 0)
		                                        {
		                                            foreach($resPL as $rowPL) :
		                                                $PRJCODE1 = $rowPL->PRJCODE;
		                                                $PRJNAME1 = $rowPL->PRJNAME;
		                                                ?>
		                                  				<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
		                                  	<?php
		                                            endforeach;
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                  				<option value="none">--- No Project Found ---</option>
		                                  	<?php
		                                        }
		                                        ?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptFrom; ?> </label>
		                          	<div class="col-sm-9">
		                        		<select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ReceiptFrom; ?>" >
		                                	<option value="none"> --- </option>
		                                    <?php
		                                    $i = 0;
		                                    if($countCUST > 0)
		                                    {
		                                        foreach($vwCUST as $row) :
		                                            $CUST_CODE1	= $row->CUST_CODE;
		                                            $CUST_DESC1	= $row->CUST_DESC;
		                                            ?>
		                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$CUST_CODE1 - $CUST_DESC1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PaymentTerm; ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="TERM_PAY" id="TERM_PAY" class="form-control" style="max-width:100px">
		                                    <option value="0" <?php if($TERM_PAY == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($TERM_PAY == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="15" <?php if($TERM_PAY == 15) { ?> selected <?php } ?>>15 Days</option>
		                                    <option value="30" <?php if($TERM_PAY == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($TERM_PAY == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($TERM_PAY == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="75" <?php if($TERM_PAY == 75) { ?> selected <?php } ?>>75 Days</option>
		                                    <option value="90" <?php if($TERM_PAY == 90) { ?> selected <?php } ?>>90 Days</option>
		                                    <option value="120" <?php if($TERM_PAY == 120) { ?> selected <?php } ?>>120 Days</option>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Warehouse ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="WH_CODE" id="WH_CODE" class="form-control select2" >
		                                	<option value="none"> --- </option>
		                                  	<?php
		                                        if($resWHC > 0)
		                                        {
		                                            foreach($resWH as $rowWH) :
		                                                $WH_CODE1 = $rowWH->WH_CODE;
		                                                $WH_NAME1 = $rowWH->WH_NAME;
		                                                ?>
		                                  				<option value="<?php echo $WH_CODE1; ?>" <?php if($WH_CODE1 == $WH_CODE) { ?> selected <?php } ?>><?php echo "$WH_CODE1 - $WH_NAME1"; ?></option>
		                                  				<?php
		                                            endforeach;
		                                        }
		                                    ?>
		                                </select>
		                            </div>
		                        </div>
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
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $LocPlace; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="IR_LOC" id="IR_LOC" value="<?php echo $IR_LOC; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="height:60px"><?php echo $IR_NOTE; ?></textarea>
		                            </div>
		                        </div>
		                        <div id="IRNOTE2" class="form-group" <?php if($IR_NOTE2 == '') { ?> style="display:none" <?php } ?>>
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="IR_NOTE2"  id="IR_NOTE2" style="height:60px" disabled><?php echo $IR_NOTE2; ?></textarea>
		                            </div>
		                        </div>
		                        <!--
		                        	APPROVE STATUS
		                            1 - New
		                            2 - Confirm
		                            3 - Approve
		                        -->
		                        <?php
									$isDisabled = 1;
									if($IR_STAT == 0 || $IR_STAT == 1 || $IR_STAT == 4)
									{
										$isDisabled = 0;
									}
								?>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="IR_STAT" id="IR_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
		                                    <?php
												$disableBtn	= 0;
												if($IR_STAT == 5 || $IR_STAT == 6 || $IR_STAT == 9)
												{
													$disableBtn	= 1;
												}
												if($IR_STAT != 1 AND $IR_STAT != 4) 
												{
													?>
		                                                <option value="1"<?php if($IR_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
		                                                <option value="2"<?php if($IR_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
		                                                <option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
		                                                <option value="4"<?php if($IR_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
		                                                <option value="5"<?php if($IR_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
		                                                <option value="6"<?php if($IR_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
		                                                <option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
		                                                <option value="9"<?php if($IR_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
													<?php
												}
												else
												{
													?>
														<option value="1"<?php if($IR_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($IR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													<?php
												}
											?>
		                                </select>
		                            </div>
		                        </div>
		                        <script>
									function chkSTAT(selSTAT)
									{
										if(selSTAT == 5 || selSTAT == 9)
										{
											document.getElementById('IRNOTE2').style.display = '';
											document.getElementById("IR_NOTE2").disabled = false;
											
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
											}
										}
										else if(selSTAT == 6)
										{
											document.getElementById('IRNOTE2').style.display = '';
											document.getElementById("IR_NOTE2").disabled = false;
											
											document.getElementById('btnREJECT').style.display = '';
										}
										else
										{
											document.getElementById('btnREJECT').style.display = 'none';
											document.getElementById("IR_NOTE2").disabled = true;
										}
									}
								</script>
								<?php
									$url_AddItm	= site_url('c_inventory/c_ir180c15/pop180c22all_f9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_AddQC	= site_url('c_inventory/c_ir180c15/pop4dD_QC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <div class="form-group" <?php if($IR_STAT != 1 && $IR_STAT != 4) { ?> style="display:none <?php } ?>">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
										<script>
											var url = "<?php echo $url_AddItm;?>";
											function selectitem()
											{
												var SPLCODE	= $('#SPLCODE').val();
												var WH_CODE	= $('#WH_CODE').val();
												if(SPLCODE == 'none')
												{
													swal('<?php echo $alert6; ?>',
													{
														icon: "warning",
													});
													document.getElementById('SPLCODE').focus();
													return false;
												}
												
												if(WH_CODE == 'none')
												{
													swal('<?php echo $alert7; ?>',
													{
														icon: "warning",
													});
													document.getElementById('WH_CODE').focus();
													return false;
												}
												
												title = 'Select Item';
												w = 1000;
												h = 550;
												//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
												var left = (screen.width/2)-(w/2);
												var top = (screen.height/2)-(h/2);
												return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
											}
										</script>
		                                
		                                <button class="btn btn-success" type="button" onClick="selectitem();">
		                                    <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
		                                </button><br>
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
                                        <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                      	<th width="10%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $ItemCode; ?> </th>
                                      	<th width="28%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $ItemName; ?> </th>
                                      	<th colspan="4" style="text-align:center"><?php echo $Receipt; ?> </th>
                                        <th rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $Total; ?></th>
                                        <th width="5%" rowspan="2" style="text-align:center; display:none">Bonus<br>(Qty)</th>
                                        <th width="22%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $Remarks ?></th>
                                        <th width="2%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo "$List<br>QR"; ?></th>
                                  	</tr>
                                    <tr style="background:#CCCCCC">
                                        <th style="text-align:center;"><?php echo $Quantity; ?> </th>
                                        <th style="text-align:center;"><?php echo $Unit; ?> </th>
                                        <th style="text-align:center;">Roll</th>
                                        <th style="text-align:center"><?php echo $Price; ?> </th>
                                    </tr>
                                    <?php
									$isEdit 	= 0;
									if($IR_STAT == 1 || $IR_STAT == 4)
										$isEdit	= 1;

									$resultC	= 0;
									if($task == 'add' && $PO_NUMX != '')
									{
										/*$sqlDETPO	= "SELECT A.PRJCODE, A.OFF_NUM AS JOBCODEDET, A.OFF_CODE AS JOBCODEID,
															A.SO_NUM, A.SO_CODE, A.ITM_CODE, A.ITM_UNIT,
															A.SO_VOLM AS ITM_QTY, 0 AS PO_VOLM, A.SOD_ID AS POD_ID,
															0 AS ITM_QTY_BONUS, A.SO_PRICE AS ITM_PRICE, A.SO_COST AS ITM_TOTAL, 
															A.SO_DISP AS ITM_DISP, A.SO_DISC AS ITM_DISC, A.SO_DESC AS NOTES, 
															A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
															B.ITM_NAME, B.ACC_ID, B.ITM_GROUP,
															B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
															B.ISFASTM, B.ISWAGE
														FROM tbl_so_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
														WHERE SO_NUM = '$PO_NUMX' 
															AND B.PRJCODE = '$PRJCODE'";*/
										// DETAIL PENERIMAAN DIDAPAT DARI DETAIL MATERIAL PADA ITEM CALCULATION
										$sqlDETPO	= "SELECT 0 AS IR_ID, A.PRJCODE, A.OFF_NUM AS JOBCODEDET, A.OFF_CODE AS JOBCODEID,
															A.SO_NUM, A.SO_CODE, A.ITM_CODE, A.ITM_UNIT,
															A.SO_VOLM AS ITM_QTY, 0 AS ITM_QTY2, 0 AS PO_VOLM, A.SOD_ID AS POD_ID,
															0 AS ITM_QTY_BONUS, A.SO_PRICE AS ITM_PRICE, A.SO_COST AS ITM_TOTAL, 
															A.SO_DISP AS ITM_DISP, A.SO_DISC AS ITM_DISC, A.SO_DESC AS NOTES, 
															A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
															B.ITM_NAME, B.ACC_ID, B.ITM_GROUP, 0 AS CREATEQRC,
															B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
															B.ISFASTM, B.ISWAGE
														FROM tbl_so_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
														WHERE SO_NUM = '$PO_NUMX' 
															AND B.PRJCODE = '$PRJCODE'";
										$result 	= $this->db->query($sqlDETPO)->result();
										
										$sqlDETC	= "tbl_so_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
														WHERE SO_NUM = '$PO_NUMX' 
															AND B.PRJCODE = '$PRJCODE'";
										$resultC 	= $this->db->count_all($sqlDETC);
									}
									else
									{
										if($task == 'edit')
										{
											$sqlDET	= "SELECT A.IR_ID, A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
															A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT,
															A.ITM_QTY, A.ITM_QTY2, 0 AS PO_VOLM, A.POD_ID,
															A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, 
															A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
															B.ITM_NAME, B.ACC_ID, B.ITM_GROUP, A.CREATEQRC,
															B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
															B.ISFASTM, B.ISWAGE
														FROM tbl_ir_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
														WHERE 
														A.IR_NUM = '$IR_NUM' 
														AND A.PRJCODE = '$PRJCODE'";
											$result = $this->db->query($sqlDET)->result();
											// count data
											$sqlDETC	= "tbl_ir_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE 
															A.IR_NUM = '$IR_NUM' 
															AND A.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
										}
									}
									
									$i		= 0;
									$j		= 0;
									if($resultC > 0)
									{
										$GT_TOTAMOUNT		= 0;
										foreach($result as $row) :
											$currentRow  	= ++$i;
											$IR_NUM 		= $IR_NUM;
											$IR_CODE 		= $IR_CODE;
											$PO_NUM 		= $PO_NUM;
											if($task == 'add' && $PO_NUMX != '')
											{
												$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
											}
											elseif($PO_NUMX != '')
											{
												$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
											}
											else
											{
												$PO_NUM 	= $PO_NUM;
												$ADDQIERY	= "";
											}
											
											$PRJCODE		= $PRJCODE;
											$IR_ID 			= $row->IR_ID;
											$JOBCODEDET 	= $row->JOBCODEDET;
											$JOBCODEID		= $row->JOBCODEID;
											$ACC_ID 		= $row->ACC_ID;
											$POD_ID 		= $row->POD_ID;
											$ITM_CODE 		= $row->ITM_CODE;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITM_GROUP 		= $row->ITM_GROUP;
											$ITM_NAME 		= $row->ITM_NAME;
											$ITM_QTY 		= $row->ITM_QTY;
											$ITM_QTY1 		= $row->ITM_QTY;
											$ITM_QTY2		= $row->ITM_QTY2;
											
											// GET REMAIN
											$TOT_IRQTY	= $ITM_QTY1;
											$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
															FROM tbl_ir_detail A
																INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
															WHERE 
																B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
																AND A.IR_NUM != '$IR_NUM' AND IR_STAT = '3'
																AND A.POD_ID = $POD_ID AND A.IR_SOURCE = 3
																$ADDQIERY";
											$resQTY 	= $this->db->query($sqlQTY)->result();
											foreach($resQTY as $row1a) :
												$TOT_IRQTY 	= $row1a->TOT_IRQTY;
											endforeach;
											
											$PO_VOLM 		= $row->PO_VOLM;
											if($task == 'edit')
											{
												// GET PO QTY
												// TIDAK ADA PO
												$PO_VOLM	= 0;
												$sqlPOQTY	= "SELECT SUM(A.PO_VOLM) AS PO_VOLM 
																FROM tbl_po_detail A
																WHERE 
																	A.PO_NUM = '$PO_NUM' AND A.PRJCODE = '$PRJCODE'
																	AND A.ITM_CODE = '$ITM_CODE' AND A.POD_ID = $POD_ID";
												//$resPOQTY 	= $this->db->query($sqlPOQTY)->result();
												//foreach($resPOQTY as $rowPO) :
													//$PO_VOLM 	= $rowPO->PO_VOLM;
												//endforeach;
											}
											
											//$ITM_QTY 		= $ITM_QTY1 - $TOT_IRQTY;
											$ITM_QTY_BONUS	= $row->ITM_QTY_BONUS;
											$ITM_PRICE 		= $row->ITM_PRICE;
											$ITM_DISP 		= $row->ITM_DISP;
											$ITM_DISC 		= $row->ITM_DISC;
											$ITM_TOTAL 		= $row->ITM_TOTAL;
											$ITM_DESC 		= $row->NOTES;
											$CREATEQRC 		= $row->CREATEQRC;
											//$IR_VOLM 		= $row->IR_VOLM;
											//$IR_AMOUNT 	= $row->IR_AMOUNT;
											$TAXCODE1		= $row->TAXCODE1;
											$TAXCODE2		= $row->TAXCODE2;
											$TAXPRICE1		= $row->TAXPRICE1;
											$TAXPRICE2		= $row->TAXPRICE2;
											$itemConvertion	= 1;
											$ISMTRL 		= $row->ISMTRL;
											$ISRENT 		= $row->ISRENT;
											$ISPART 		= $row->ISPART;
											$ISFUEL 		= $row->ISFUEL;
											$ISLUBRIC 		= $row->ISLUBRIC;
											$ISFASTM 		= $row->ISFASTM;
											$ISWAGE 		= $row->ISWAGE;
											if($ISMTRL == 1)
												$ITM_TYPE	= 1;
											elseif($ISRENT == 1)
												$ITM_TYPE	= 2;
											elseif($ISPART == 1)
												$ITM_TYPE	= 3;
											elseif($ISFUEL == 1)
												$ITM_TYPE	= 4;
											elseif($ISLUBRIC == 1)
												$ITM_TYPE	= 5;
											elseif($ISFASTM == 1)
												$ITM_TYPE	= 6;
											else
												$ITM_TYPE	= 1;
											
											if($task == 'add')
											{
												$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;
											}
											else
											{
												$ITM_TOTAL 	= $row->ITM_TOTAL;			// Non-PPn
											}
											$GT_ITMPRICE	= $ITM_TOTAL - $ITM_DISC;
											
											if($TAXCODE1 == 'TAX01')
											{
												$TAXPRICE1	= $ITM_TOTAL * 0.1;
											}
											
											$TOTITMPRICE	= $GT_ITMPRICE;
											if($TAXCODE1 == 'TAX01')
											{
												$TOTITMPRICE	= $GT_ITMPRICE + $TAXPRICE1;
											}
											if($TAXCODE1 == 'TAX02')
											{
												$TOTITMPRICE	= $GT_ITMPRICE - $TAXPRICE1;
											}
											
											$ITM_TOTALnPPn	= $TOTITMPRICE;	
											
											$GT_TOTAMOUNT	= $GT_TOTAMOUNT + $ITM_TOTALnPPn;
											
											/*$sqlDETPO	= "SELECT A.PO_VOLM
															FROM tbl_po_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE PO_NUM = '$PO_NUM' 
																AND B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'";	
											$resDETPO 	= $this->db->query($sqlDETPO)->result();	
											foreach($resDETPO as $rowDETPO) :
												$PO_VOLM 	= $rowDETPO->PO_VOLM;
											endforeach;*/
											
											$REMAINQTY	= $ITM_QTY1 - $TOT_IRQTY;
											//$REMAINQTY	= $PO_VOLM;
											
											//echo $REMAINQTY;
											if($task == 'add')
												$ITM_QTY 	= $REMAINQTY;
											else
												$ITM_QTY 	= $ITM_QTY;
												
											/*if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?>
                                            <tr><td width="2%" height="25" style="text-align:left">
                                                <?php if($IR_STAT == 1 || $IR_STAT == 4) { ?>
                                                	<a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                               	<?php } else {
                                                	echo "$currentRow.";
												} ?>
                                                <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                <input type="Checkbox" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="" style="display:none">
                                                <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                <input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php echo $IR_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>IR_CODE" name="data[<?php echo $currentRow; ?>][IR_CODE]" value="<?php echo $IR_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php echo $PO_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
												<!-- Checkbox -->
											</td>
											<td width="10%" style="text-align:left" nowrap>
												<?php echo $ITM_CODE; ?>
												<input type="hidden" id="data<?php echo $currentRow; ?>POD_ID" name="data[<?php echo $currentRow; ?>][POD_ID]" value="<?php echo $POD_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15" readonly class="form-control">
												<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php echo $JOBCODEDET; ?>" width="10" size="15" readonly class="form-control">
												<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" width="10" size="15" readonly class="form-control">
												<!-- Item Code -->
                                           	</td>
											<td width="28%" style="text-align:left">
												<?php echo $ITM_NAME; ?>
												<input type="hidden" class="form-control" name="itemname<?php echo $currentRow; ?>" id="itemname<?php echo $currentRow; ?>" value="<?php echo $ITM_NAME; ?>" >
                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_NAME]" id="data<?php echo $currentRow; ?>ITM_NAME" value="<?php echo $ITM_NAME; ?>" >
												<!-- Item Name -->
                                            </td>
											<td width="8%" style="text-align:right">
												<?php 
													if($isEdit == 0)
													{
														print number_format($ITM_QTY, $decFormat); ?>
	                                                    <input type="hidden" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="4" >
	                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" size="10" value="<?php echo $ITM_QTY; ?>" >
														<input type="hidden" style="text-align:right" id="REMAINQTY<?php echo $currentRow; ?>" size="10" value="<?php echo $REMAINQTY; ?>" >
														<?php
													}
													else 
													{
														?>
	                                                    <input type="text" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="4" >
	                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" size="10" value="<?php echo $ITM_QTY; ?>" >
														<input type="hidden" style="text-align:right" id="REMAINQTY<?php echo $currentRow; ?>" size="10" value="<?php echo $REMAINQTY; ?>" >
														<?php
													}
												?>
												<!-- Item Qty -->
                                            </td>
											<td width="7%" nowrap style="text-align:center">
												<?php echo $ITM_UNIT; ?>
												<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
                                                <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
                                                <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
												<!-- Item Unit -->
											</td>
											<td width="7%" nowrap style="text-align:right;">
												<?php 
													if($isEdit == 0)
													{
														print number_format($ITM_QTY2, $decFormat); ?>
	                                                    <input type="hidden" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY2<?php echo $currentRow; ?>" id="ITM_QTY2<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY2, $decFormat); ?>" onBlur="changeValueRoll(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="4" >
                                                		<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY2]" id="data<?php echo $currentRow; ?>ITM_QTY2" size="10" value="<?php echo $ITM_QTY2; ?>" >
														<?php
													}
													else 
													{
														?>
	                                                    <input type="text" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY2<?php echo $currentRow; ?>" id="ITM_QTY2<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY2, $decFormat); ?>" onBlur="changeValueRoll(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="4" >
                                                		<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY2]" id="data<?php echo $currentRow; ?>ITM_QTY2" size="10" value="<?php echo $ITM_QTY2; ?>" >
														<?php
													}
												?>
                                            </td>
											<td width="4%" style="text-align:center; font-style:italic">
                                            	hidden
                                                <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" >
                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" size="10" value="<?php echo $ITM_PRICE; ?>" >
                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="data<?php echo $currentRow; ?>ITM_DISP" size="10" value="<?php echo $ITM_DISP; ?>" >
                                                <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_DISC<?php echo $currentRow; ?>" id="ITM_DISC<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_DISC, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValueDisc(this, <?php echo $currentRow; ?>)" >
                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="data<?php echo $currentRow; ?>ITM_DISC" size="10" value="<?php echo $ITM_DISC; ?>" >                                                    
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" value="<?php echo $TAXCODE1; ?>">
                                                <select name="data<?php echo $currentRow; ?>TAXCODE1" class="form-control" id="TAXCODE1<?php echo $currentRow; ?>"  onChange="getValueIR(this, <?php echo $currentRow; ?>);" style="min-width:100px; max-width:150px; display:none" disabled>
													<option value=""> --- no tax --- </option>
													<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?PHP } ?>>PPn 10% </option>
													<option value="TAX02" <?php if($TAXCODE1 == 'TAX02') { ?> selected <?PHP } ?>>PPh 3%</option>
												</select>
												<!-- Item Price -->
                                            </td>
											<td width="4%" style="text-align:center; font-style:italic">
                                            	hidden
                                                <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTAL<?php echo $currentRow; ?>" id="ITM_TOTAL<?php echo $currentRow; ?>" value="<?php echo $ITM_TOTAL; ?>" >
                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="data<?php echo $currentRow; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>" >
                                                <input type="hidden" style="min-width:130px; max-width:350px; text-align:right" name="GT_ITMPRICE<?php echo $currentRow; ?>" id="data<?php echo $currentRow; ?>GT_ITMPRICE" value="<?php print number_format($ITM_TOTALnPPn, $decFormat); ?>">
												<!-- Item Price Total -->
                                            </td>
											<td width="5%" style="text-align:center; display:none">
                                            	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY_BONUSx<?php echo $currentRow; ?>" id="ITM_QTY_BONUSx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY_BONUS, $decFormat); ?>" onBlur="changeValueQtyBonus(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="4" >
												<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_BONUS]" id="ITM_QTY_BONUS<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_BONUS; ?>" ></td>
											<td width="22%" style="text-align:left;">
												<?php 
													if($isEdit == 0)
													{
														print $ITM_DESC; ?>
	                                                	<input type="hidden" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $ITM_DESC; ?>" class="form-control" style="max-width:250px;text-align:left" size="10">
														<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="TAXPRICE1<?php echo $currentRow; ?>" size="10" value="<?php echo $TAXPRICE1; ?>" >
														<?php
													}
													else 
													{
														?>
	                                                	<input type="text" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $ITM_DESC; ?>" class="form-control" style="max-width:250px;text-align:left" size="10">
														<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="TAXPRICE1<?php echo $currentRow; ?>" size="10" value="<?php echo $TAXPRICE1; ?>" >
														<?php
													}
												?>
                                                <!-- Notes -->
                                            </td>
                                            <?php
                                            	$collDET	= "$IR_NUM~$ITM_CODE~$IR_ID";
                                            	$secPrintQR	= site_url('c_inventory/c_ir180c15/printQRDET/?id='.$this->url_encryption_helper->encode_url($collDET));
                                            ?>
											<td width="2%" style="text-align:center">
												<input type="hidden" name="urlPrintQR<?php echo $currentRow; ?>" id="urlPrintQR<?php echo $currentRow; ?>" value="<?php echo $secPrintQR; ?>">
                                            	<?php if($IR_STAT == 3 || $IR_STAT == 6) { ?>
                                                	<a href="avascript:void(null);" title="Show QRC" onClick="printQR('<?php echo $currentRow; ?>')"><i class="glyphicon glyphicon-qrcode"></i></a>
                                                    <input type="checkbox" id="data<?php echo $currentRow; ?>crtQRC" onclick="crtQRC(<?php echo $currentRow; ?>)" <?php if($CREATEQRC == 1) { ?> checked <?php } ?> style="display: none;">
                                                <?php } else {?>
                                                	<a href="avascript:void(null);" class="btn btn-danger btn-xs" onClick="addQC(<?php echo $currentRow; ?>)" style="display:none"><i class="glyphicon glyphicon-lock"></i></a>
                                                	<input type="checkbox" id="data<?php echo $currentRow; ?>crtQRC" onclick="crtQRC(<?php echo $currentRow; ?>)" <?php if($CREATEQRC == 1) { ?> checked <?php } ?>>
                                                <?php } ?>
                                                <input type="hidden" id="data<?php echo $currentRow; ?>CREATEQRC" name="data[<?php echo $currentRow; ?>][CREATEQRC]" value="<?php echo $CREATEQRC; ?>">
                                            </td>
											<td width="1%" style="text-align:center; <?php if($IR_STAT != 1) { ?> display: none; <?php } ?>">
                                                <i class="glyphicon glyphicon-plus" style="cursor:pointer" onClick="addRow(this,<?php echo $currentRow; ?>)"></i><i class="glyphicon glyphicon-minus" style="cursor:pointer" onClick="delRow(<?php echo $currentRow; ?>)"></i>
                                            </td>
							          </tr>
										<?php
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
                    <script>
						function changeValueQtyBonus(thisVal, theRow)
						{
							var decFormat	= document.getElementById('decFormat').value;	
							var ITM_QTY_BNS	= eval(thisVal).value.split(",").join("");
							
							document.getElementById('ITM_QTY_BONUS'+theRow).value 	= parseFloat(Math.abs(ITM_QTY_BNS));
							document.getElementById('ITM_QTY_BONUSx'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_BNS)),decFormat));
						}
					</script>
                    <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $GT_TOTAMOUNT; ?>">
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-9">
                        	<?php
								$showBtn	= 0;
								if($IR_STAT == 2 || $IR_STAT == 3)
								{
									$showBtn	= 0;
								}
								else
								{
									$showBtn	= 1;
								}
								if($ISCREATE == 1 && $showBtn == 1)
								{
									if($task=='add')
									{
										?>
											<button class="btn btn-primary">
											<i class="fa fa-save"></i>
											</button>&nbsp;
										<?php
									}
									else
									{
										?>
											<button class="btn btn-primary" >
											<i class="fa fa-save"></i>
											</button>&nbsp;
										<?php
									}
								}
								?>
                               		<button class="btn btn-primary" id="btnREJECT" style="display:none" >
                                    <i class="fa fa-save"></i>
                                    </button>
                               	<?php
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
							?>
                        </div>
                    </div>
                </form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $IR_NUM;
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
                	<?php
						$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                		if($DefEmp_ID == 'D15040004221')
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
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
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
	
	function checkInp()
	{
		totalrow	= document.getElementById("totalrow").value;
		SPLCODE		= document.getElementById("SPLCODE").value;
		WH_CODE		= document.getElementById("WH_CODE").value;
		PO_NUM1		= document.getElementById("PO_NUM1").value;
		IR_STAT		= document.getElementById("IR_STAT").value;
		IR_REFER2	= document.getElementById("IR_REFER2").value;
		
		if(IR_REFER2 == '')
		{
			swal('<?php echo $alert8; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('IR_REFER2').focus();
			});
			return false;
		}
		
		if(SPLCODE == 'none')
		{
			swal('<?php echo $alert6; ?>',
			{
				icon: "warning",
			});
			document.getElementById('SPLCODE').focus();
			return false;
		}
		
		if(WH_CODE == 'none')
		{
			swal('<?php echo $alert7; ?>',
			{
				icon: "warning",
			});
			document.getElementById('WH_CODE').focus();
			return false;
		}
		
		/*if(PO_NUM1 == '')
		{
			swal("<?php echo $alert2; ?>");
			return false;
		}*/
		
		if(IR_STAT == 5 || IR_STAT == 6 || IR_STAT == 9)
		{
			IR_NOTE2		= document.getElementById("IR_NOTE2").value;
			if(IR_NOTE2 == '')
			{
				swal("<?php echo $alert4; ?>",
				{
					icon: "warning",
				})
	            .then(function()
	            {
	                swal.close();
	                $('#IR_NOTE2').focus();
	            });
				return false;
			}
		}
		
		if(totalrow == 0)
		{
			swal("<?php echo $alert3; ?>",
			{
				icon: "warning",
			});
			return false;
		}
		
		for(i=1; i<=totalrow; i++)
		{
			ITM_QTY	= document.getElementById('data'+theRow+'ITM_QTY').value;
			ITM_NM	= document.getElementById('itemname'+i).value;
			if(ITM_QTY == 0)
			{
				swal('Item '+ ITM_NM +' qty can not be empty.',
				{
					icon: "warning",
				});
				document.getElementById('ITM_QTY'+i).focus();
				return false;
			}
		}
	}
	
	function changeValue(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		/* tidak ada batasan penerimaan
		var REMAINQTY	= parseFloat(document.getElementById('REMAINQTY'+theRow).value);
		
		//swal('ITM_QTYx = '+ITM_QTYx)	
		//swal('REMAINQTY = '+REMAINQTY)	
		if(ITM_QTYx > REMAINQTY)
		{
			swal('<?php echo $alert1; ?>');
			document.getElementById('ITM_QTYx'+theRow).value 	= REMAINQTY;
			document.getElementById('ITM_QTY'+theRow).value 	= parseFloat(Math.abs(REMAINQTY));
			document.getElementById('ITM_QTYx'+theRow).focus();
			return false;
		}*/
		document.getElementById('data'+theRow+'ITM_QTY').value		= parseFloat(Math.abs(ITM_QTYx));
		document.getElementById('ITM_QTY'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		
		var ITM_DISP			= document.getElementById('data'+theRow+'ITM_DISP').value;
		var ITM_QTY				= document.getElementById('data'+theRow+'ITM_QTY').value;
		var ITM_PRICE			= document.getElementById('data'+theRow+'ITM_PRICE').value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT			= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		document.getElementById('data'+theRow+'ITM_DISC').value		= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISC'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		document.getElementById('data'+theRow+'ITM_TOTAL').value	= parseFloat(Math.abs(TOT_ITMTEMP));
		document.getElementById('ITM_TOTAL'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_ITMTEMP)),decFormat));
		
		var theTAX				= document.getElementById('data'+theRow+'TAXCODE1').value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('data'+theRow+'TAXPRICE1').value	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+theRow+'GT_ITMPRICE').value	= RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+theRow+'TAXPRICE1').value	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+theRow+'GT_ITMPRICE').value	= RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+theRow+'TAXPRICE1').value	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+theRow+'GT_ITMPRICE').value	= RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		//document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(G_itmTot));
		//document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('data'+i+'GT_ITMPRICE').value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function changeValueRoll(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_QTY2').value		= parseFloat(Math.abs(ITM_QTYx));
		document.getElementById('ITM_QTY2'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISP		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(ITM_DISP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISP)),decFormat));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_PRICE		= document.getElementById('ITM_PRICE'+row).value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT		= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
				
		var GT_ITMPRICE 	= document.getElementById('GT_ITMPRICE'+row).value
		var DISCOUNTP		= parseFloat(ITM_DISC / GT_ITMPRICE * 100);
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function getValueIR(thisVal, row)
	{
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function changeValueTax(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		//var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		//var ITM_QTY		= eval(document.getElementById('ITM_QTY'+theRow)).value.split(",").join("");
		/*if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			swal('Qty can not greater then '+ ITM_QTY_MIN);
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{*/
			//document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY));
			//document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		//}
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		// PAJAK
		theTAX			= document.getElementById('TAXCODE1'+theRow).value;
		
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.1;
			G_itmTot	= parseFloat(ITM_TOTAL) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.03;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('GT_ITMPRICE'+i).value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEx));
		document.getElementById('ITM_PRICEx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		SO_NUM		= arrItem[0];
		
		document.getElementById("SO_NUMX").value = SO_NUM;
		document.frmsrch.submitSrch.click();
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var IR_NUM 	= "<?php echo $IR_NUM; ?>";
		var IR_CODE	= "<?php echo $IR_CODE; ?>";
		var PRJCODE = "<?php echo $PRJCODE; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[0], PRJCODE)
		if(validateDouble(arrItem[0], PRJCODE))
		{
			swal("Double Item for " + arrItem[0],
			{
				icon: "warning",
			});
			return false;
		}
		
		itemcode 		= arrItem[0];
		itemserial 		= arrItem[1];
		itemname 		= arrItem[2];
		itemUnit 		= arrItem[3];
		itemUnitName 	= arrItem[4];
		itemUnit2 		= arrItem[5];
		itemUnitName2 	= arrItem[6];
		itemConvertion 	= arrItem[9];
		itemQty 		= 1;
		itemPrice 		= arrItem[11];
		Acc_Id 			= arrItem[12];
		JOBCODEDET		= arrItem[13];
		JOBCODEID		= arrItem[14];
		ITM_DISP		= 0;
		ITM_DISC		= 0;
		
		ITM_TOTAL		= itemQty * itemPrice;
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" ><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		// Item Code
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'POD_ID" name="data['+intIndex+'][POD_ID]" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+Acc_Id+'" width="10" size="15" readonly class="form-control"><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" class="form-control"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control">';
		
		// Item Name
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = ''+itemname+'<input type="hidden" class="form-control" name="itemname'+intIndex+'" id="itemname'+intIndex+'" value="'+itemname+'" >';
		
		// Item Qty
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" size="10" value="'+itemQty+'" >';
		
		// Item Unit
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" ><input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Qty 2 / Roll
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY2'+intIndex+'" id="ITM_QTY2'+intIndex+'" value="1.00" onBlur="changeValueRoll(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY2]" id="data'+intIndex+'ITM_QTY2" size="10" value="1" >';
		
		// Item Price
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" size="10" value="'+itemPrice+'" >';
		
		// Item Disc Percentation
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = 'hidden<input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_DISP]" id="data'+intIndex+'ITM_DISP" size="10" value="'+ITM_DISP+'" >';
		
		// Item Disc
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_DISC'+intIndex+'" id="ITM_DISC'+intIndex+'" value="'+ITM_DISC+'" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValueDisc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_DISC]" id="data'+intIndex+'ITM_DISC" size="10" value="'+ITM_DISC+'" >';
		
		// Item Tax
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = 'hidden<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="min-width:100px; max-width:150px; display:none" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// Item Total
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTAL'+intIndex+'" id="ITM_TOTAL'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="'+ITM_TOTAL+'" ><input type="hidden" style="min-width:130px; max-width:350px; text-align:right" name="GT_ITMPRICE'+intIndex+'" id="data'+intIndex+'GT_ITMPRICE" value="0">';
		
		// Notes
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:250px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0">';
		
		// List QRCode
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'center';
			objTD.innerHTML = '<a href="avascript:void(null);" class="btn btn-danger btn-xs" onClick="addQC('+intIndex+')" style="display:none"><i class="glyphicon glyphicon-lock"></i></a><input type="Checkbox" id="data'+intIndex+'crtQRC" onclick="crtQRC('+intIndex+')" checked><input type="hidden" id="data'+intIndex+'CREATEQRC" name="data['+intIndex+'][CREATEQRC]" value="1">';
		
		// Plus / Min
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<i class="glyphicon glyphicon-plus" style="cursor:pointer" onClick="addRow(this,'+intIndex+')"></i><i class="glyphicon glyphicon-minus" style="cursor:pointer" onClick="delRow('+intIndex+')"></i>';
		
		var decFormat												= document.getElementById('decFormat').value;
		var ITM_QTY													= document.getElementById('ITM_QTY'+intIndex).value;
		document.getElementById('data'+intIndex+'ITM_QTY').value	= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE												= document.getElementById('ITM_PRICE'+intIndex).value;
		document.getElementById('data'+intIndex+'ITM_PRICE').value	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL												= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_TOTAL').value	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTAL'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 					= intIndex;
	}
	
	function addRow(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var lastStep	= document.getElementById('totalrow').value;
		add_item2(theRow);
	}
	
	function add_item2(theRow) 
	{
		var objTable, objTR, objTD, intIndex;
		var IR_NUM 		= document.getElementById('IR_NUM').value;
		var IR_CODE		= document.getElementById('IR_CODE').value;
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		intIndex 		= parseInt(objTable.rows.length) - 1;
		
		itemcode 		= document.getElementById('data'+theRow+'ITM_CODE').value;
		itemserial 		= '';
		itemname 		= document.getElementById('itemname'+theRow).value;
		itemUnit 		= document.getElementById('ITM_UNIT'+theRow).value;
		itemUnitName 	= document.getElementById('ITM_UNIT'+theRow).value;
		itemUnit2 		= document.getElementById('ITM_UNIT'+theRow).value;
		itemUnitName2 	= document.getElementById('ITM_UNIT'+theRow).value;
		itemConvertion 	= 1;
		itemQty 		= 1;
		itemPrice 		= document.getElementById('data'+theRow+'ITM_PRICE').value;
		Acc_Id 			= document.getElementById('data'+theRow+'ACC_ID').value;
		JOBCODEDET		= document.getElementById('data'+theRow+'JOBCODEDET').value;
		JOBCODEID		= document.getElementById('data'+theRow+'JOBCODEID').value;
		ITM_DISP		= 0;
		ITM_DISC		= 0;
		
		ITM_TOTAL		= itemQty * itemPrice;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" ><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		// Item Code
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'POD_ID" name="data['+intIndex+'][POD_ID]" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+Acc_Id+'" width="10" size="15" readonly class="form-control"><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" class="form-control"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control">';
		
		// Item Name
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = ''+itemname+'<input type="hidden" class="form-control" name="itemname'+intIndex+'" id="itemname'+intIndex+'" value="'+itemname+'" >';
		
		// Item Qty
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" size="10" value="'+itemQty+'" >';
		
		// Item Unit
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" ><input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Qty 2 / Roll
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="max-width:100px; text-align:right" name="ITM_QTY2'+intIndex+'" id="ITM_QTY2'+intIndex+'" value="1.00" onBlur="changeValueRoll(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY2]" id="data'+intIndex+'ITM_QTY2" size="10" value="1" >';
		
		// Item Price
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" size="10" value="'+itemPrice+'" >';
		
		// Item Disc Percentation
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = 'hidden<input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_DISP]" id="data'+intIndex+'ITM_DISP" size="10" value="'+ITM_DISP+'" >';
		
		// Item Disc
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_DISC'+intIndex+'" id="ITM_DISC'+intIndex+'" value="'+ITM_DISC+'" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValueDisc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_DISC]" id="data'+intIndex+'ITM_DISC" size="10" value="'+ITM_DISC+'" >';
		
		// Item Tax
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.innerHTML = 'hidden<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="min-width:100px; max-width:150px; display:none" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// Item Total
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTAL'+intIndex+'" id="ITM_TOTAL'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="'+ITM_TOTAL+'" ><input type="hidden" style="min-width:130px; max-width:350px; text-align:right" name="GT_ITMPRICE'+intIndex+'" id="data'+intIndex+'GT_ITMPRICE" value="0">';
		
		// Notes
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:250px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0">';
		
		// List QRCode
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'center';
			objTD.innerHTML = '<a href="avascript:void(null);" class="btn btn-danger btn-xs" onClick="addQC('+intIndex+')" style="display:none"><i class="glyphicon glyphicon-lock"></i></a><input type="Checkbox" id="data'+intIndex+'crtQRC" onclick="crtQRC('+intIndex+')" checked><input type="hidden" id="data'+intIndex+'CREATEQRC" name="data['+intIndex+'][CREATEQRC]" value="1">';
		
		// Plus / Min
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<i class="glyphicon glyphicon-plus" style="cursor:pointer" onClick="addRow(this,'+intIndex+')"></i><i class="glyphicon glyphicon-minus" style="cursor:pointer" onClick="delRow('+intIndex+')"></i>';
		
		var decFormat												= document.getElementById('decFormat').value;
		var ITM_QTY													= document.getElementById('ITM_QTY'+intIndex).value;
		document.getElementById('data'+intIndex+'ITM_QTY').value	= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE												= document.getElementById('ITM_PRICE'+intIndex).value;
		document.getElementById('data'+intIndex+'ITM_PRICE').value	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL												= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_TOTAL').value	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTAL'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 					= intIndex;
	}
	
	function crtQRC(irRow)
	{
		var IRQTY	= document.getElementById('data'+irRow+'ITM_QTY').value;
		if(IRQTY == 0)
		{
			swal('<?php echo $alert5; ?>',
			{
				icon: "warning",
			});
			document.getElementById('data'+irRow+'crtQRC').checked = false;
			document.getElementById('data'+irRow+'ITM_QTY').focus();
			return false;
		}
		else
		{
			isChck = document.getElementById('data'+irRow+'crtQRC').checked;
			if(isChck == true)
				document.getElementById('data'+irRow+'CREATEQRC').value = 1;
			else
				document.getElementById('data'+irRow+'CREATEQRC').value = 0;
		}
	}
	
	function printQR(row)
	{
		var url	= document.getElementById('urlPrintQR'+row).value;
		w = 700;
		h = 500;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function validateDouble(vcode,PRJCODE) 
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
				var elitem1		= document.getElementById('data'+i+'ITM_CODE').value;
				var PRJCODE1	= document.getElementById('data'+i+'PRJCODE').value;
				if (elitem1 == vcode && PRJCODE == PRJCODE)
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
	
	function addQC(irRow)
	{
		var url 	= "<?php echo $url_AddQC;?>";
		var IRQTY	= document.getElementById('data'+irRow+'ITM_QTY').value;
		if(IRQTY == 0)
		{
			swal('<?php echo $alert5; ?>',
			{
				icon: "warning",
			});
			document.getElementById('data'+irRow+'ITM_QTY').focus();
			return false;
		}
		
		title = 'Add QRCode';
		w = 1000;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url+'&IRQTY='+IRQTY, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function delRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
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