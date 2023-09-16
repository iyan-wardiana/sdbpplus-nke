<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= itemreceipt_form.php
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
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$year 	= (int)$Pattern_YearAktive;
	$month 	= (int)$Pattern_MonthAktive;
	$date 	= (int)$Pattern_DateAktive;
	
	$year		= substr($year, 2, 4);
	$Patt_Year 	= (int)$Pattern_YearAktive;

	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_ir_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ir_header
			WHERE Patt_Year = $Patt_Year AND PRJCODE = '$PRJCODE'";
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
	$IR_SOURCE		= 1;
	$IR_DATE		= date('d/m/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$IR_REFER		= '';
	$PO_NUM			= '';
	$IR_AMOUNT		= 0;
	$APPROVE		= 0;
	$IR_STAT		= 0;
	$IR_NOTE		= '';
	$REVMEMO		= '';
	$WH_CODE		= '';
	$IR_STAT		= '';
	$Patt_Number	= $lastPatternNumb1;
	$PO_NUMX		= '';
	
	if(isset($_POST['PO_NUMX']))
	{
		$PO_NUMX		= $_POST['PO_NUMX'];
	}

	$PR_NUM		= '';
	$SPLCODE	= '';
	$TERM_PAY	= 0;
	$sqlPOH		= "SELECT PR_NUM, SPLCODE, PO_TENOR FROM tbl_po_header WHERE PO_NUM = '$PO_NUMX' AND PRJCODE = '$PRJCODE'";
	$resPOH 	= $this->db->query($sqlPOH)->result();
	foreach($resPOH as $row1):
		$PR_NUM		= $row1->PR_NUM;
		$SPLCODE	= $row1->SPLCODE;
		$TERM_PAY	= $row1->PO_TENOR;
	endforeach;
}
else
{	
	$IR_NUM 		= $default['IR_NUM'];
	$IR_NUM_BEF		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_SOURCE 		= $default['IR_SOURCE'];
	$IR_DATE 		= $default['IR_DATE'];
	$IR_DUEDATE		= $default['IR_DUEDATE'];
	$IR_DATE		= date('d/m/Y', strtotime($IR_DATE));
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
	$IR_AMOUNT 		= $default['IR_AMOUNT'];
	$TERM_PAY 		= $default['TERM_PAY'];
	$TRXUSER 		= $default['TRXUSER'];
	$APPROVE 		= $default['APPROVE'];
	$IR_STAT 		= $default['IR_STAT'];
	$INVSTAT 		= $default['INVSTAT'];
	$IR_NOTE 		= $default['IR_NOTE'];
	$IR_NOTE2		= $default['IR_NOTE2'];
	$REVMEMO		= $default['REVMEMO'];
	$WH_CODE		= $default['WH_CODE'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Number	= $default['Patt_Number'];
}

if($IR_SOURCE == 4)
{
	$form_action	= site_url('c_inventory/c_ir180c15/update_inbox_process_SO');
}

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

// Warehouse List
$sqlWHC		= "tbl_warehouse";
$resWHC		= $this->db->count_all($sqlWHC);

$sqlWH 		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse ORDER BY WH_NAME";
$resWH		= $this->db->query($sqlWH)->result();

$sqlTAX		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse ORDER BY WH_NAME";
$resTAX		= $this->db->query($sqlTAX)->result();
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
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
		if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'WHLocation')$WHLocation = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
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
		if($TranslCode == 'payType')$payType = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'Approver')$Approver = $LangTransl;
		if($TranslCode == 'Approved')$Approved = $LangTransl;
		if($TranslCode == 'Approval')$Approval = $LangTransl;
		if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
		if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
	endforeach;
	$secGenCode	= base_url().'index.php/c_inventory/c_item_receipt/genCode/'; // Generate Code
	
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
		$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV' AND POSCODE = '$DEPCODE'";
		$resCAPP	= $this->db->count_all($sqlCAPP);
		if($resCAPP > 0)
		{
			$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
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
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
			$resAPPT	= $this->db->query($sqlAPP)->result();
			foreach($resAPPT as $rowAPPT) :
				$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
			endforeach;
		}
		
		$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
		$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
		
		if($resSTEPAPP > 0)
		{
			$canApprove	= 1;
			$APPLIMIT_1	= 0;
			
			$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
						AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
			$resAPP	= $this->db->query($sqlAPP)->result();
			foreach($resAPP as $rowAPP) :
				$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
				$APP_STEP	= $rowAPP->APP_STEP;
				$MAX_STEP	= $rowAPP->MAX_STEP;
			endforeach;
			$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM'";
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
	
	$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
	$resultPRJ 		= $this->db->query($sqlPRJ)->result();
	
	foreach($resultPRJ as $rowPRJ) :
		$PRJNAMEHO 	= $rowPRJ->PRJNAME;
	endforeach;
?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
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
                	<!-- Mencari Kode Purchase Order Number -->
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="PO_NUMX" id="PO_NUMX" class="textbox" value="<?php echo $PO_NUMX; ?>" />
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
                                    <input type="hidden" name="IRDate" id="IRDate" value="">
                                </td>
                                <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                            </tr>
                        </table>
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
			                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
			                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
			                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
			                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptCode ?> </label>
			                          	<div class="col-sm-9">
			                                <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:150px" disabled >
			                                <input type="hidden" name="Patt_Year" id="Patt_Year" value="<?php echo $Patt_Year; ?>">
			                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
			                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
			                                <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
			                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?> </label>
			                          	<div class="col-sm-9">
			                                <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" class="form-control" style="max-width:150px" >
			                                <input type="text" name="IR_CODE1" id="IR_CODE1" value="<?php echo $IR_CODE; ?>" class="form-control" disabled >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
			                          	<div class="col-sm-9">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    	<input type="hidden" name="IR_DATE" class="form-control pull-left" id="IR_DATE" value="<?php echo $IR_DATE; ?>" >
			                                    	<input type="hidden" name="IR_DUEDATE" class="form-control pull-left" id="IR_DUEDATE" value="<?php echo $IR_DUEDATE; ?>" >
			                                    	<input type="text" name="IR_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px" disabled>
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
			                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE1" value="1" <?php if($IR_SOURCE == 1) { ?> checked <?php } ?> disabled>
			                                &nbsp;&nbsp;Direct&nbsp;&nbsp;&nbsp;&nbsp;
			                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE2" value="2" <?php if($IR_SOURCE == 2) { ?> checked <?php } ?> disabled>
			                                &nbsp;&nbsp;MR&nbsp;&nbsp;&nbsp;&nbsp;
			                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" <?php if($IR_SOURCE == 3) { ?> checked <?php } ?>>
			                                &nbsp;&nbsp;PO
			                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE4" value="4" <?php if($IR_SOURCE == 4) { ?> checked <?php } ?>>
			                                &nbsp;&nbsp;SO
			                            </div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $RefNumber; ?> </label>
			                          	<div class="col-sm-9">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search; ?> </button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="Ref_NumberPO" id="Ref_NumberPO" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
			                                    <input type="hidden" class="form-control" name="PO_NUM" id="PO_NUM" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
			                                    <input type="hidden" class="form-control" name="PO_CODE" id="PO_CODE" style="max-width:160px" value="<?php echo $PO_CODE; ?>" >
			                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUM; ?>" >
			                                    <input type="hidden" class="form-control" name="PR_CODE" id="PR_CODE" style="max-width:160px" value="<?php echo $PR_CODE; ?>" >
			                                    <input type="hidden" class="form-control" name="IR_REFER" id="IR_REFER" style="max-width:160px" value="<?php echo $IR_REFER; ?>" >
			                                    <input type="text" class="form-control" name="PO_NUM1" id="PO_NUM1" value="<?php echo $PO_NUMX; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?>>
			                                </div>
			                            </div>
			                        </div>
									<?php
										$url_selIR_CODE		= site_url('c_inventory/c_item_receipt/popupallpo/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			                           		<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
			                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" disabled >
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
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName; ?> </label>
			                          	<div class="col-sm-9">
			                            	<input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>">
			                            	<select name="SPLCODE1" id="SPLCODE1" class="form-control" onChange="getVendName(this.value)" disabled>
			                                    <?php
			                                    $i = 0;
			                                    if($countSUPL > 0)
			                                    {
			                                        foreach($vwSUPL as $row) :
			                                            $SPLCODE1	= $row->SPLCODE;
			                                            $SPLDESC1	= $row->SPLDESC;
			                                            ?>
			                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
			                                            <?php
			                                        endforeach;
			                                        if($task == 'add')
			                                        {
			                                            ?>
			                                                <option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>>--- None ---</option>
			                                            <?php
			                                        }
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="">--- No Vendor Found ---</option>
			                                        <?php
			                                    }
			                                    ?>
			                                </select>
			                          	</div>
			                        </div>
			                        <?php if($IR_SOURCE != 4) { ?>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $payType; ?> </label>
			                          	<div class="col-sm-9">
			                            	<input type="hidden" name="TERM_PAY" id="TERM_PAY" value="<?php echo $TERM_PAY; ?>">
			                                <select name="TERM_PAY1" id="TERM_PAY1" class="form-control" disabled>
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
			                        <?php } ?>
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
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $WHLocation ?> </label>
			                          	<div class="col-sm-9">
			                            	<input type="hidden" name="WH_CODE" id="WH_CODE" value="<?php echo $WH_CODE; ?>">
			                                <select name="WH_CODE1" id="WH_CODE1" class="form-control" disabled >
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
			                                        else
			                                        {
			                                            ?>
			                                  				<option value="" style="font-style:italic; text-align:center">--- No Data Warehouse ---</option>
			                                  			<?php
			                                        }
			                                        ?>
			                                </select>
			                            </div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="height:60px; display:none"><?php echo $IR_NOTE; ?></textarea>
			                                <textarea class="form-control" name="IR_NOTE1"  id="IR_NOTE1" style="height:60px" disabled><?php echo $IR_NOTE; ?></textarea>
			                            </div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes ?> </label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="IR_NOTE2"  id="IR_NOTE2" style="height:60px"><?php echo $IR_NOTE2; ?></textarea>
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
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
											<?php
												// START : FOR ALL APPROVAL FUNCTION
													if($disableAll == 0)
													{
														if($canApprove == 1)
														{
															$disButton	= 0;
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															?>
																<select name="IR_STAT" id="IR_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> -- </option>
																	<option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																	<option value="4"<?php if($IR_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																	<option value="5"<?php if($IR_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																	<option value="6"<?php if($IR_STAT == 6) { ?> selected <?php } ?>>Closed</option>
																	<option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
																</select>
															<?php
														}
														else
														{
															?>
																<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs" title="ssss">
																	<?php echo $descApp; ?>
																</a>
															<?php
														}
													}
													else
													{
														?>
															<a href="" class="btn btn-danger btn-xs">
																Step approval not set;
															</a>
														<?php
													}
												// END : FOR ALL APPROVAL FUNCTION
			                                ?>
			                            </div>
			                        </div>
									<?php
										$url_AddItem	= site_url('c_inventory/c_item_receipt/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
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
											<!--<a href="javascript:void(null);" onClick="selectitem();">
												Add Item [+]
			                                </a>-->
			                                
			                                <button class="btn btn-success" type="button" onClick="selectitem();">
			                                    <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
			                                </button><br>
			                                </div>
			                        </div>
								</div>
							</div>
						</div>
                        <div class="col-md-12">
                            <div class="search-table-outter">
                                <table width="100%" border="1" id="tbl">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                      	<th width="9%" rowspan="2" style="text-align:center"><?php echo $ItemCode; ?> </th>
                                      	<th width="20%" rowspan="2" style="text-align:center"><?php echo $ItemName; ?> </th>
                                      	<th colspan="3" style="text-align:center"><?php echo $Receipt; ?> </th>
                                        <th rowspan="2" style="text-align:center"><?php echo $Discount; ?><br>(%)</th>
                                        <th rowspan="2" style="text-align:center"><?php echo $Discount; ?></th>
                                        <th rowspan="2" style="text-align:center"><?php echo $PPn; ?></th>
                                        <th rowspan="2" style="text-align:center"><?php echo $Total; ?></th>
                                        <th width="9%" rowspan="2" style="text-align:center">Bonus<br>(Qty)</th>
                                        <th width="12%" rowspan="2" style="text-align:center"><?php echo $Remarks ?></th>
                                  	</tr>
                                    <tr style="background:#CCCCCC">
                                        <th style="text-align:center;"><?php echo $Quantity; ?> </th>
                                        <th style="text-align:center;"><?php echo $Unit; ?> </th>
                                        <th style="text-align:center"><?php echo $Price; ?> </th>
                                    </tr>
                                    <?php
									$resultC	= 0;
									
									$sqlDET		= "SELECT A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
														A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.POD_ID,
														A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, 
														A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, 
														A.TAXPRICE2, B.ITM_NAME, B.ACC_ID, B.ITM_GROUP, 
														B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
														B.ISFASTM, B.ISWAGE, B.ISRM, B.ISWIP, B.ISFG, B.ISCOST
													FROM tbl_ir_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE 
													A.IR_NUM = '$IR_NUM' 
													AND A.PRJCODE = '$PRJCODE'
													AND A.ITM_QTY > 0";
									$result = $this->db->query($sqlDET)->result();
									// count data
									$sqlDETC	= "tbl_ir_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE 
													A.IR_NUM = '$IR_NUM' 
													AND A.PRJCODE = '$PRJCODE'";
									$resultC 	= $this->db->count_all($sqlDETC);
									
									
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
												$PR_NUM 	= $row->PR_NUM;
												$PO_NUM 	= $row->PO_NUM;
												$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
											}
											else
											{
												$PR_NUM 	= $PR_NUM;
												$PO_NUM 	= $PO_NUM;
												$ADDQIERY	= "";
											}
											$PRJCODE		= $PRJCODE;
											$JOBCODEDET 	= $row->JOBCODEDET;
											$JOBCODEID		= $row->JOBCODEID;
											$ACC_ID 		= $row->ACC_ID;
											$POD_ID 		= $row->POD_ID;
											$ITM_CODE 		= $row->ITM_CODE;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITM_GROUP 		= $row->ITM_GROUP;
											$ISMTRL 		= $row->ISMTRL;
											$ISRENT 		= $row->ISRENT;
											$ISPART 		= $row->ISPART;
											$ISFUEL 		= $row->ISFUEL;
											$ISLUBRIC 		= $row->ISLUBRIC;
											$ISFASTM 		= $row->ISFASTM;
											$ISWAGE 		= $row->ISWAGE;
											$ISRM 			= $row->ISRM;
											$ISWIP 			= $row->ISWIP;
											$ISCOST 		= $row->ISCOST;
											$ISFG 			= $row->ISFG;
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
											elseif($ISWAGE == 1)
												$ITM_TYPE	= 7;
											elseif($ISRM == 1)
												$ITM_TYPE	= 8;
											elseif($ISWIP == 1)
												$ITM_TYPE	= 9;
											elseif($ISFG == 1)
												$ITM_TYPE	= 10;
											elseif($ISCOST == 1)
												$ITM_TYPE	= 11;
											else
												$ITM_TYPE	= 1;
															
											$ITM_NAME 		= $row->ITM_NAME;
											$ITM_QTY 		= $row->ITM_QTY;
											$ITM_QTY_BONUS	= $row->ITM_QTY_BONUS;
											$ITM_PRICE 		= $row->ITM_PRICE;
											$ITM_DISP 		= $row->ITM_DISP;
											$ITM_DISC 		= $row->ITM_DISC;
											$ITM_TOTAL 		= $row->ITM_TOTAL;
											$ITM_DESC 		= $row->NOTES;
											//$IR_VOLM 		= $row->IR_VOLM;
											//$IR_AMOUNT 	= $row->IR_AMOUNT;
											$TAXCODE1		= $row->TAXCODE1;
											$TAXCODE2		= $row->TAXCODE2;
											$TAXPRICE1		= $row->TAXPRICE1;
											$TAXPRICE2		= $row->TAXPRICE2;
											$itemConvertion	= 1;
											
											if($task == 'add')
												$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;
											else
												$ITM_TOTAL 	= $row->ITM_TOTAL;			// Non-PPn
											
											$GT_ITMPRICE	= $ITM_TOTAL - $ITM_DISC;
											
											$TOTITMPRICE	= $GT_ITMPRICE;
											/*if($TAXCODE1 == 'TAX01')
											{
												$TOTITMPRICE	= $GT_ITMPRICE + $TAXPRICE1;
											}
											if($TAXCODE1 == 'TAX02')
											{
												$TOTITMPRICE	= $GT_ITMPRICE - $TAXPRICE1;
											}*/
											$TOTITMPRICE	= $GT_ITMPRICE + $TAXPRICE1;
											
											$ITM_TOTALnPPn	= $TOTITMPRICE;	
											
											$GT_TOTAMOUNT	= $GT_TOTAMOUNT + $ITM_TOTALnPPn;
											
											// GET REMAIN
											$TOT_IRQTY	= 0;
											$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY FROM tbl_ir_detail A
																INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
															WHERE 
															B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
																AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (2,3)
																AND A.POD_ID = $POD_ID
																$ADDQIERY";
											$resQTY 	= $this->db->query($sqlQTY)->result();
											foreach($resQTY as $row1a) :
												$TOT_IRQTY 	= $row1a->TOT_IRQTY;
											endforeach;
											$REMAINQTY	= $ITM_QTY - $TOT_IRQTY; 
											//echo $REMAINQTY;
											if($task == 'add')
												$ITM_QTY 	= $REMAINQTY;
											else
												$ITM_QTY 	= $ITM_QTY;
												
											if ($j==1) {
												echo "<tr>";
												$j++;
											} else {
												echo "<tr>";
												$j--;
											}
											?> 
											<tr><td width="2%" height="25" style="text-align:left">
												<?php echo "$currentRow."; ?>
												<input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
												<input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="">
												<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php echo $IR_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>IR_CODE" name="data[<?php echo $currentRow; ?>][IR_CODE]" value="<?php echo $IR_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php echo $PO_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<!-- Checkbox -->
											</td>
											<td width="9%" style="text-align:left" nowrap>
												<?php echo $ITM_CODE; ?>
												<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
												<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15" readonly class="form-control">
												<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php echo $JOBCODEDET; ?>" width="10" size="15" readonly class="form-control">
												<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" width="10" size="15" readonly class="form-control">
												<!-- Item Code -->												</td>
											<td width="20%" style="text-align:left">
												<?php echo $ITM_NAME; ?>
												<input type="hidden" class="form-control" name="itemname<?php echo $currentRow; ?>" id="itemname<?php echo $currentRow; ?>" value="<?php echo $ITM_NAME; ?>" >
												<input type="hidden" id="data<?php echo $currentRow; ?>ITM_NAME" name="data[<?php echo $currentRow; ?>][ITM_NAME]" value="<?php echo $ITM_NAME; ?>" width="10" size="15" readonly class="form-control">
												<!-- Item Name -->												</td>
											<td width="12%" style="text-align:right" nowrap>
												<?php print number_format($ITM_QTY, $decFormat); ?>
												<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="ITM_QTY<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
												<input type="hidden" style="text-align:right" id="REMAINQTY<?php echo $currentRow; ?>" size="10" value="<?php echo $REMAINQTY; ?>" >
												<!-- Item Qty -->												</td>
											<td width="3%" nowrap style="text-align:center">
												<?php echo $ITM_UNIT; ?>
												<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
												<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
                                                <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
												<!-- Item Unit -->												</td>
											<td width="12%" nowrap style="text-align:right">                                                    <?php print number_format($ITM_PRICE, $decFormat); ?>
												<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="ITM_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_PRICE; ?>" >
												<!-- Item Price -->												</td>
											<td width="6%" nowrap style="text-align:right">
												<?php print number_format($ITM_DISP, $decFormat); ?>
												<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="ITM_DISP<?php echo $currentRow; ?>" value="<?php echo $ITM_DISP; ?>"></td>
											<td width="3%" nowrap style="text-align:right">
												<?php print number_format($ITM_DISC, $decFormat); ?>
												<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="ITM_DISC<?php echo $currentRow; ?>" value="<?php echo $ITM_DISC; ?>"></td>
											<td width="3%" nowrap style="text-align:right">
                                            	<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" value="<?php echo $TAXCODE1; ?>">
                                                <?php 
                                                    /*if($TAXCODE1 == 'TAX01')
													{ 
														echo "PPn 10%";
													}
													elseif($TAXCODE1 == 'TAX02')
													{
														echo "PPh 3%";
													}*/
													$TAXLA_CODE	= "-";
													$sPPN		= "SELECT TAXLA_CODE FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'
                                                    				UNION ALL
                                                    				SELECT TAXLA_CODE FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE1'";
                                                    $rPPN		= $this->db->query($sPPN)->result();
                                                    foreach($rPPN as $rwPPN) :
                                                        $TAXLA_CODE	= $rwPPN->TAXLA_CODE;
                                                    endforeach;
                                                    echo $TAXLA_CODE;
												?>
												<!-- Item Price Total PPn --></td>
											<td width="8%" nowrap style="text-align:center; font-style:italic">
                                            	<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="ITM_TOTAL<?php echo $currentRow; ?>" size="10" value="<?php echo $GT_ITMPRICE; ?>" >
												<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="TAXPRICE1<?php echo $currentRow; ?>" size="10" value="<?php echo $TAXPRICE1; ?>" >
                                                <?php print number_format($ITM_TOTALnPPn, $decFormat); ?>
												<!-- Item Price Total --></td>
											<td width="9%" style="text-align:center">
                                            	<?php
													if($ITM_QTY_BONUS == '')
														$ITM_QTY_BONUS = 0;
														
													print number_format($ITM_QTY_BONUS, $decFormat); 
												?>
												<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_BONUS]" id="ITM_QTY_BONUS<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_BONUS; ?>" ></td>
											<td width="12%" style="text-align:center"><?php echo $ITM_DESC; ?></td>
											<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
									  </tr>
										<?php
										endforeach;
										?>
										<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
										<?php
									}
                                    ?>
                                </table>
                          	</div>
                        </div>
                        <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $IR_AMOUNT; ?>">
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<?php
									if($disableAll == 0)
									{
										if(($IR_STAT == 2 || $IR_STAT == 7) && ($ISAPPROVE == 1 && $canApprove == 1))
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}
                                    $backURL	= site_url('c_inventory/c_ir180c15/in180c15box/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
								?>
                            </div>
                        </div>
			        	<div class="col-md-12">
							<?php
	                            $DOC_NUM	= $IR_NUM;
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
			        	</div>
			        </div>
			    </div>
			</section>
		</div>
	</body>
</html>

<?php
	if($LangID == 'IND')
	{
		$alert1	= "Silahkan tambahkan item...!";
		$alert2	= "Silahkan pilih status persetujuan...!";
		$alert3	= "qty tidak boleh kosong.";
		$alert4	= "Isikan alasan pengoreksian dokumen.";
	}
	else
	{
		$alert1	= "Please add item...!";
		$alert2	= "Please select approval status...!";
		$alert3	= "qty can not be empty.";
		$alert4	= "Please input revise notes.";
	}
?>

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
  
	var decFormat		= 2;
	
	function checkInp()
	{
		IR_STAT	= document.getElementById("IR_STAT").value;
		
		totalrow	= document.getElementById("totalrow").value;
		if(totalrow == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			selectitem();
			return false;
		}
		
		if(IR_STAT == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			document.getElementById("IR_STAT").focus();
			return false;
		}
		else if(IR_STAT == 4 || IR_STAT == 5)
		{
			IR_NOTE2 = document.getElementById("IR_NOTE2").value;
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
		
		var TOTQTY	= 0;
		for(i=1; i<=totalrow; i++)
		{
			ITM_QTY	= document.getElementById('ITM_QTY'+i).value;
			ITM_NM	= document.getElementById('itemname'+i).value;
			if(ITM_QTY == 0)
			{
				//swal('Item '+ ITM_NM +' <?php echo $alert3; ?>');
				//document.getElementById('ITM_QTYx'+i).focus();
				//return false;
			}
			TOTQTY	= parseFloat(TOTQTY) + parseFloat(ITM_QTY);
		}
		
		if(TOTQTY == 0)
		{
			swal("qty can not be empty.",
			{
				icon: "warning",
			});
			//document.getElementById('ITM_QTYx'+i).focus();
			return false;
		}
	}
	
	function changeValue(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");		
		document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
		document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		
		var ITM_DISP			= document.getElementById('ITM_DISP'+theRow).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE			= document.getElementById('ITM_PRICE'+theRow).value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT			= parseFloat(ITM_DISP * ITM_TOTAL / 100);		
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		document.getElementById('ITM_DISC'+theRow).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCx'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var theTAX				= document.getElementById('TAXCODE1'+theRow).value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(G_itmTot));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('GT_ITMPRICE'+i).value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
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
		
		PR_NUM		= arrItem[0];
		//swal(PR_NUM);
		//document.getElementById("PO_NUM1").value = PO_NUM;
		document.getElementById("PO_NUMX").value = PR_NUM;
		document.frmsrch.submitSrch.click();
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var IR_NUM 	= "<?php echo $IR_NUM; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[0], PRJCODE)
		if(validateDouble(arrItem[0], PRJCODE))
		{
			swal("Double Item for " + arrItem[0]);
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
		itemQty 		= 0;
		itemPrice 		= arrItem[11];
		Acc_Id 			= arrItem[12];
		
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
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onClick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value=""><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_CODE" name="data['+intIndex+'][IR_CODE]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+Acc_Id+'" width="10" size="15" readonly class="form-control">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'<input type="hidden" class="form-control" name="itemname'+intIndex+'" id="itemname'+intIndex+'" value="'+itemname+'" >';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx'+intIndex+'" id="ITM_QTYx'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="ITM_QTY'+intIndex+'" size="10" value="'+itemQty+'" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx'+intIndex+'" id="ITM_PRICEx'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" size="10" value="'+itemPrice+'" >';
		
		// Item Price Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALx'+intIndex+'" id="ITM_TOTALx'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" size="10" value="'+ITM_TOTAL+'" >';
		
		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="max-width:150px" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:450px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="TAXPRICE1'+intIndex+'" value=""><input type="hidden" style="text-align:right" name="GT_ITMPRICE'+intIndex+'" id="GT_ITMPRICE'+intIndex+'" value="">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var ITM_QTY												= document.getElementById('ITM_QTY'+intIndex).value
		document.getElementById('ITM_QTY'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE											= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('ITM_PRICE'+intIndex).value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICEx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL											= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('ITM_TOTAL'+intIndex).value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 				= intIndex;
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>