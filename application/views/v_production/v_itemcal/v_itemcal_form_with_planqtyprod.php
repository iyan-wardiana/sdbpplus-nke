<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= v_bom_form.php
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

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$CCAL_NUM		= '';
	$CCAL_CODE 		= '';
	$PRJCODE 		= $PRJCODE;
	$BOM_NUM		= '';
	$BOM_CODE		= '';
	$CCAL_NAME		= '';
	$CCAL_DESC 		= '';
	$CCAL_STAT 		= 1;
	$CCAL_CREATER	= $DefEmp_ID;					
	$CCAL_CREATED	= '';
	
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

	$this->db->where('PRJCODE', $PRJCODE);
	$myCount = $this->db->count_all('tbl_ccal_header');
	
	$myMax 	= $myCount+1;
			
	
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
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$CCAL_NUM1		= "$Pattern_Code$PRJCODE$year$month$days$lastPatternNumb";
	$CCAL_NUM		= "$CCAL_NUM1";
	$DocNumber		= $CCAL_NUM;
	$CCAL_CODE		= "$lastPatternNumb";
	
	$CCALCODE		= substr($lastPatternNumb, -4);
	$CCALYEAR		= date('y');
	$CCALMONTH		= date('m');
	$CCAL_CODE		= "$Pattern_Code.$CCALCODE.$CCALYEAR.$CCALMONTH"; // MANUAL CODE
	
	$CUST_CODE		= '';
	$CCAL_DESC		= '';
	$CCAL_VOLM		= 1;
	$CCAL_RMCOST	= 0;
	$CCAL_OTHCOST	= 0;
	$CCAL_TOTCOST	= 0;
	$CCAL_PROFIT	= 0;
	$CCAL_PROFITAM	= 0;
	$CCAL_ITMPRICE	= 0;
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_ccal_header~$Pattern_Length";
	$dataTarget		= "CCAL_CODE";
}
else
{
	$isSetDocNo = 1;
	
	$CCAL_NUM 		= $default['CCAL_NUM'];
	$DocNumber 		= $default['CCAL_NUM'];
	$CCAL_CODE 		= $default['CCAL_CODE'];
	$PRJCODE 		= $default['PRJCODE'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$BOM_NUM 		= $default['BOM_NUM'];
	$BOM_CODE 		= $default['BOM_CODE'];
	$CCAL_NAME 		= $default['CCAL_NAME'];
	$CCAL_DESC 		= $default['CCAL_DESC'];
	$CCAL_VOLM 		= $default['CCAL_VOLM'];
	$CCAL_RMCOST 	= $default['CCAL_RMCOST'];
	$CCAL_OTHCOST	= $default['CCAL_OTHCOST'];
	$CCAL_TOTCOST	= $default['CCAL_TOTCOST'];
	$CCAL_PROFIT	= $default['CCAL_PROFIT'];
	$CCAL_PROFITAM	= $default['CCAL_PROFITAM'];
	$CCAL_ITMPRICE	= $default['CCAL_ITMPRICE'];
	$CCAL_STAT 		= $default['CCAL_STAT'];
}

$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();

foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;
//echo $SC_NUMX;
$secGenCode	= base_url().'index.php/c_production/c_b0fm47/genCode/'; // Generate Code
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
			$this->load->view('template/topbar');
			$this->load->view('template/sidebar');
			
			$ISREAD 	= $this->session->userdata['ISREAD'];
			$ISCREATE 	= $this->session->userdata['ISCREATE'];
			$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
			$ISDELETE 	= $this->session->userdata['ISDELETE'];
			$ISDWONL 	= $this->session->userdata['ISDWONL'];
			$LangID 	= $this->session->userdata['LangID'];

			$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
			$resTransl		= $this->db->query($sqlTransl)->result();
			foreach($resTransl as $rowTransl) :
				$TranslCode	= $rowTransl->MLANG_CODE;
				$LangTransl	= $rowTransl->LangTransl;
				
				if($TranslCode == 'Add')$Add = $LangTransl;
				if($TranslCode == 'Edit')$Edit = $LangTransl;
				if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
				if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
				if($TranslCode == 'Date')$Date = $LangTransl;
				if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
				if($TranslCode == 'Project')$Project = $LangTransl;
				if($TranslCode == 'CustName')$CustName = $LangTransl;
				if($TranslCode == 'Currency')$Currency = $LangTransl;
				if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
				if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
				if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
				if($TranslCode == 'Description')$Description = $LangTransl;
				if($TranslCode == 'ProductionVolume')$ProductionVolume = $LangTransl;
				if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
				if($TranslCode == 'Status')$Status = $LangTransl;
				if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
				if($TranslCode == 'Search')$Search = $LangTransl;
				
				if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
				if($TranslCode == 'ItemName')$ItemName = $LangTransl;
				if($TranslCode == 'Remain')$Remain = $LangTransl;
				if($TranslCode == 'Quantity')$Quantity = $LangTransl;
				if($TranslCode == 'Unit')$Unit = $LangTransl;
				if($TranslCode == 'Price')$Price = $LangTransl;
				if($TranslCode == 'Discount')$Discount = $LangTransl;
				if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
				if($TranslCode == 'Tax')$Tax = $LangTransl;
				if($TranslCode == 'Purchase')$Purchase = $LangTransl;
				if($TranslCode == 'Tax')$Tax = $LangTransl;
				if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
				if($TranslCode == 'Material')$Material = $LangTransl;
				if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
				if($TranslCode == 'OthCost')$OthCost = $LangTransl;
				if($TranslCode == 'Save')$Save = $LangTransl;
				if($TranslCode == 'Update')$Update = $LangTransl;
				if($TranslCode == 'Cancel')$Cancel = $LangTransl;
				if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
				if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
				if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
				if($TranslCode == 'ExpAccum')$ExpAccum = $LangTransl;
				if($TranslCode == 'ProfitCal')$ProfitCal = $LangTransl;
				if($TranslCode == 'ItemPrice')$ItemPrice = $LangTransl;
				if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
				if($TranslCode == 'Approval')$Approval = $LangTransl;
				if($TranslCode == 'Approved')$Approved = $LangTransl;
				if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
				if($TranslCode == 'rejected')$rejected = $LangTransl;
				if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			endforeach;
			if($LangID == 'IND')
			{
				$alert1		= 'Jumlah pemesanan tidak boleh kosong';
				$alert2		= 'Silahkan pilih nama pelanggan';
				$isManual	= 'Centang untuk kode manual.';
				$alertFG	= 'Jumlah produk tidak boleh kosong';
				$alertRM	= 'Jumlah bahan material tidak boleh kosong';
				$alertOTH	= 'Jumlah biaya lainnya tidak boleh kosong';
			}
			else
			{
				$alert1		= 'Qty order can not be empty';
				$alert2		= 'Please select a customer name';
				$isManual	= 'Check to manual code.';
				$alertFG	= 'Qty of product can not be empty';
				$alertRM	= 'Qty of Raw Material can not be empty';
				$alertOTH	= 'Qty of other cost can not be empty';
			}

			$BOM_NUMX		= '';
			$CCAL_NAME		= $CCAL_NAME;
			if(isset($_POST['BOM_NUMX']))
			{
				$CUST_CODE	= '';
				$BOM_NUMX	= $_POST['BOM_NUMX'];
				$sqlBOM		= "SELECT BOM_NUM, BOM_CODE, BOM_NAME, CUST_CODE FROM tbl_bom_header 
								WHERE BOM_NUM = '$BOM_NUMX'";
				$resBOM 	= $this->db->query($sqlBOM)->result();
				foreach($resBOM as $rowBOM) :
					$BOM_NUM 	= $rowBOM->BOM_NUM;
					$BOM_CODE 	= $rowBOM->BOM_CODE;
					$BOM_NAME 	= $rowBOM->BOM_NAME;
					$CUST_CODE	= $rowBOM->CUST_CODE;
				endforeach;
				$CCAL_NAME	= $BOM_NAME;
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
					$APPROVE_AMOUNT 	= 0;
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

			if($task == 'edit')
		    {												
		        $sqlDETC	= "tbl_ccal_detail A
		                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                        WHERE 
		                            A.CCAL_NUM = '$CCAL_NUM' 
		                            AND B.PRJCODE = '$PRJCODE'
									AND A.ITM_CATEG = 'RM'";
		        //$resultC 	= $this->db->count_all($sqlDETC);
				
				$sqlDET		= "SELECT A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_NOTES,
									A.ITM_CATEG, B.ITM_NAME
								FROM tbl_ccal_detail A
									LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
								WHERE CCAL_NUM = '$CCAL_NUM' 
									AND B.PRJCODE = '$PRJCODE'
									AND A.ITM_CATEG = 'RM'";
				$result = $this->db->query($sqlDET)->result();
		    }
			else
			{
				$sqlDET		= "SELECT SUM(A.ITM_QTY * A.ITM_PRICE) AS TOTRM_AMOUNT FROM tbl_bom_stfdetail A
									LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
								WHERE A.BOM_NUM = '$BOM_NUM' AND A.PRJCODE = '$PRJCODE' AND B.ISRM = 1 AND A.BOMSTF_TYPE = 'IN'";
				$resDET 	= $this->db->query($sqlDET)->result();
				foreach($resDET as $rowDET) :
		            $CCAL_RMCOST	= $rowDET->TOTRM_AMOUNT;
		        endforeach;
			}
			//echo "CCAL_RMCOST = $CCAL_RMCOST";

		    $disRow	= 1;
		    if($CCAL_STAT == 1 || $CCAL_STAT == 2 || $CCAL_STAT == 4)
		    {
		    	$disRow	= 0;
		    }
		?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
			<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bom.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
			</section>
			<style>
				.search-table, td, th {
					border-collapse: collapse;
				}
				.search-table-outter { overflow-x: scroll; }
				
				.inplabel {border:none;background-color:white;}
				.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
				.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
				.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
				.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
				.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
				.inpdim {border:none;background-color:white;}
				
				input[type="checkbox"] {
				  -webkit-appearance: none;
				  -moz-appearance: none;
				  appearance: none;
				
				  /* Styling checkbox */
				  width: 16px;
				  height: 16px;
				  background-color: red;
				  cursor:pointer;
				}
				
				input[type="checkbox"]:checked {
				  background-color: green;
				  cursor:pointer;
				}
			</style>

			<section class="content">	
			    <div class="row">
			        <div class="col-md-12">
			            <div class="box box-primary">
			                <div class="box-header with-border" style="display:none">               
			              		<div class="box-tools pull-right">
			                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			                        </button>
			                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			                    </div>
			                </div>
			                <div class="box-body chart-responsive">
			                	<!-- Mencari Kode Purchase Request Number -->
			                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
			                        <input type="text" name="BOM_NUMX" id="BOM_NUMX" class="textbox" value="<?php echo $BOM_NUM; ?>" />
			                        <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
			                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
			                    </form>
			                    <!-- End -->
			                    
			                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
			                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
			            			<input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
									<?php if($isSetDocNo == 0) { ?>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                            <div class="col-sm-10">
			                                <div class="alert alert-danger alert-dismissible">
			                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                                    <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
			                                    <?php echo $docalert2; ?>
			                                </div>
			                            </div>
			                        </div>
			                        <?php } ?>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $DocNumber ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" style="max-width:195px" name="CCAL_NUM1" id="CCAL_NUM1" value="<?php echo $CCAL_NUM; ?>" disabled >
			                       			<input type="hidden" class="textbox" name="CCAL_NUM" id="CCAL_NUM" size="30" value="<?php echo $CCAL_NUM; ?>" />
			                       			<input type="hidden" class="textbox" name="CCAL_NAME" id="CCAL_NAME" size="30" value="<?php echo $CCAL_NAME; ?>" />
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode ?> </label>
			                          	<div class="col-sm-10">
			                                <label>
			                                    <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="CCAL_CODE" id="CCAL_CODE" value="<?php echo $CCAL_CODE; ?>" >
			                                </label>
			                                <label>
			                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
			                                </label>
			                                <label style="font-style:italic">
			                                    <?php echo $isManual; ?>
			                                </label>
			                          	</div>
			                        </div>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
			                          <option value="none"> --- </option>
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
			                            ?>
			                        </select>
			                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CustName ?> </label>
			                          	<div class="col-sm-10">
			                                <?php if($disRow == 0) { ?>
				                            	<select name="CUST_CODE" id="CUST_CODE" class="form-control select2">
				                                	<option value="" > --- </option>
				                                    <?php
				                                    $i = 0;
				                                    $countCUST	= 1;
				                                    if($countCUST > 0)
				                                    {

														$sqlCUST 	= "SELECT DISTINCT A.CUST_CODE, A.CUST_DESC FROM tbl_customer A";
														$resCUST	= $this->db->query($sqlCUST)->result();
				                                        foreach($resCUST as $row) :
				                                            $CUST_CODE1	= $row->CUST_CODE;
				                                            $CUST_DESC1	= $row->CUST_DESC;
				                                            ?>
				                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
				                                            <?php
				                                        endforeach;
				                                    }
				                                    ?>
				                                </select>
			                                	<input type="hidden" class="form-control" id="CUST_CODE2" name="CUST_CODE2" size="20" value="<?php echo $CUST_CODE; ?>" />
			                            	<?php } else { ?>
				                            	<select name="CUST_CODE" id="CUST_CODE" class="form-control select2" disabled="">
				                                	<option value="" > --- </option>
				                                    <?php
				                                    $i = 0;
				                                    $countCUST	= 1;
				                                    if($countCUST > 0)
				                                    {

														$sqlCUST 	= "SELECT DISTINCT A.CUST_CODE, A.CUST_DESC FROM tbl_customer A";
														$resCUST	= $this->db->query($sqlCUST)->result();
				                                        foreach($resCUST as $row) :
				                                            $CUST_CODE1	= $row->CUST_CODE;
				                                            $CUST_DESC1	= $row->CUST_DESC;
				                                            ?>
				                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
				                                            <?php
				                                        endforeach;
				                                    }
				                                    ?>
				                                </select>
			                                	<input type="hidden" class="form-control" id="CUST_CODE2" name="CUST_CODE2" size="20" value="<?php echo $CUST_CODE; ?>" />
			                            	<?php } ?>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $BOMCode ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="BOM_NUM" id="BOM_NUM" style="max-width:160px" value="<?php echo $BOM_NUM; ?>" >
			                                    <input type="hidden" class="form-control" name="BOM_CODE" id="BOM_CODE" style="max-width:160px" value="<?php echo $BOM_CODE; ?>" >
			                                    <input type="text" class="form-control" name="BOM_NUM1" id="BOM_NUM1" value="<?php echo $BOM_CODE; ?>" onClick="getBOMCODE();" <?php if($task != 'add') { ?> disabled <?php } ?>>
			                                </div>
			                            </div>
			                        </div>
									<?php
										$url_selBOM		= site_url('c_production/c_I73mc05tc4l/g3t_4llB0m/?id=');
			                        ?>
			                        <script>
										var url1 = "<?php echo $url_selBOM;?>";
										function getBOMCODE()
										{
											PRJCODE		= document.getElementById('PRJCODE').value;
											CUST_CODE	= document.getElementById('CUST_CODE').value;
											if(CUST_CODE == '')
											{
												swal("<?php echo $alert2; ?>");
												document.getElementById('CUST_CODE').focus();
												return false;
											}
											collDATA	= PRJCODE+'~'+CUST_CODE;
											
											title = 'Select Item';
											w = 1000;
											h = 550;
											//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											return window.open(url1+collDATA, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
									</script>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
			                          	<div class="col-sm-10">
			                          		<?php if($disRow == 0) { ?>
			                                	<textarea class="form-control" name="CCAL_DESC"  id="CCAL_DESC" style="height:70px"><?php echo $CCAL_DESC; ?></textarea>
			                            	<?php } else { ?>
				                                <textarea class="form-control" name="CCAL_DESC1"  id="CCAL_DESC1" style="height:70px" disabled><?php echo $CCAL_DESC; ?></textarea>
				                                <textarea class="form-control" name="CCAL_DESC"  id="CCAL_DESC" style="height:70px; display: none;"><?php echo $CCAL_DESC; ?></textarea>
			                            	<?php } ?>
			                          	</div>
			                        </div>
			                        <?php
			                        	$TOTPROD		= 0;
			                        	$sqlDETPRD		= "SELECT SUM(A.ITM_QTY) as TOTPROD
															FROM tbl_bom_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
															WHERE BOM_NUM = '$BOM_NUM' AND B.PRJCODE = '$PRJCODE' AND A.BOM_TYPE = 'OUT' AND B.ISFG = 1";
										$resDETPRD 		= $this->db->query($sqlDETPRD)->result();
										foreach($resDETPRD as $rowPRD):
											$TOTPROD	= $rowPRD->TOTPROD;
										endforeach;
			                        ?>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ProductionVolume ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" style="max-width:120px; text-align:right" name="CCAL_VOLMX" id="CCAL_VOLMX" value="<?php print number_format($TOTPROD, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getVolITM(this);" readonly >
			                                <input type="hidden" id="CCAL_VOLM" name="CCAL_VOLM" value="<?php print $TOTPROD; ?>">
			                          	</div>
			                        </div>
			                        <!--
			                        	APPROVE STATUS
			                            1 - New
			                            2 - Confirm
			                            3 - Approve
			                        -->
			                        <div class="form-group" >
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $CCAL_STAT; ?>">
											<?php
											// START : FOR ALL APPROVAL FUNCTION
												$disButton	= 0;
												$disButtonE	= 0;
												if($task == 'add')
												{
													if($ISCREATE == 1 || $ISAPPROVE == 1)
													{
														?>
															<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" >
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
														if($CCAL_STAT == 1 || $CCAL_STAT == 4)
														{
															$disButton	= 0;
															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" >
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($CCAL_STAT == 2 || $CCAL_STAT == 7)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$CCAL_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
														
															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($CCAL_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																	<option value="2"<?php if($CCAL_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																	<option value="3"<?php if($CCAL_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($CCAL_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($CCAL_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($CCAL_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($CCAL_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																	<?php if($CCAL_STAT == 3 || $CCAL_STAT == 9) { ?>
																	<option value="9"<?php if($CCAL_STAT == 9) { ?> selected <?php } ?>>Void</option>
																	<?php } ?>
																</select>
															<?php
														}
														elseif($CCAL_STAT == 3)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;

															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$CCAL_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															if($ISDELETE == 1)
																$disButton	= 0;

															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																	<option value="1"<?php if($CCAL_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($CCAL_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($CCAL_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($CCAL_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($CCAL_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($CCAL_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($CCAL_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($CCAL_STAT == 9) { ?> selected <?php } ?>>Void</option>
																</select>
															<?php
														}
													}
													elseif($ISAPPROVE == 1)
													{
														if($CCAL_STAT == 1 || $CCAL_STAT == 4)
														{
															$disButton	= 1;
															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														else if($CCAL_STAT == 2 || $CCAL_STAT == 3 || $CCAL_STAT == 7)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
																
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$CCAL_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($CCAL_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																	<option value="2"<?php if($CCAL_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																	<option value="3"<?php if($CCAL_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($CCAL_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($CCAL_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($CCAL_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($CCAL_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																	<?php if($CCAL_STAT == 3 || $CCAL_STAT == 9) { ?>
																	<option value="9"<?php if($CCAL_STAT == 9) { ?> selected <?php } ?>>Void</option>
																	<?php } ?>
																</select>
															<?php
														}
													}
													elseif($ISCREATE == 1)
													{
														if($CCAL_STAT == 1 || $CCAL_STAT == 4)
														{
															$disButton	= 0;
															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($CCAL_STAT == 1) { ?> selected <?php } ?> >New</option>
																	<option value="2"<?php if($CCAL_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																	<option value="3"<?php if($CCAL_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																	<option value="4"<?php if($CCAL_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																	<option value="5"<?php if($CCAL_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>

																	<option value="6"<?php if($CCAL_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($CCAL_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<?php if($CCAL_STAT == 3 || $CCAL_STAT == 9) { ?>
																	<option value="9"<?php if($CCAL_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																	<?php } ?>
																</select>
															<?php
														}
														else
														{
															$disButton	= 1;
															?>
																<select name="CCAL_STAT" id="CCAL_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($CCAL_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																	<option value="2"<?php if($CCAL_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
																	<option value="3"<?php if($CCAL_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																	<option value="4"<?php if($CCAL_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																	<option value="5"<?php if($CCAL_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																	<option value="6"<?php if($CCAL_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($CCAL_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<?php if($CCAL_STAT == 3 || $CCAL_STAT == 9) { ?>
																	<option value="9"<?php if($CCAL_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
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
			                        <script type="text/javascript">
			                        	function selStat(statVal)
			                        	{
			                        		var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
			                        		if(STAT_BEFORE == 3 && statVal == 6)
			                        		{
			                        			document.getElementById('tblClose').style.display = '';
			                        		}
			                        		else
			                        		{
			                        			document.getElementById('tblClose').style.display = 'none';
			                        		}
			                        	}
			                        </script>
			                        <?php
									$url_RawMtr	= site_url('c_production/c_I73mc05tc4l/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_OthC	= site_url('c_production/c_I73mc05tc4l/s3l4ll07hc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-success">
			                                	<br>
			                               		<table width="100%" border="1" id="tbl_fg">
			                                        <tr style="background:#00A65A; font-weight:bold; color:#FFF">
			                                            <td width="2%" height="25" style="text-align:center">No.</td>
			                                          	<td width="10%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </td>
			                                          	<td width="20%" style="text-align:center" nowrap><?php echo $ItemName; ?> </td>
			                                          	<td width="10%" style="text-align:center" nowrap><?php echo $Quantity; ?> </td>
			                                          	<td width="10%" style="text-align:center" nowrap><?php echo $ProdPlan; ?> </td> 
			                                            <!-- Input Manual -->
			                                            <td width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </td>
			                                          	<td width="10%" style="text-align:center" nowrap><?php echo $UnitPrice; ?> </td>
			                                          	<td width="10%" style="text-align:center" nowrap>Total</td>
			                                          	<td width="30%" style="text-align:center" nowrap><?php echo $Description; ?></td>
			                                      	</tr>
													<?php
			                                            $resultC	= 0;
														if($task == 'add' && $BOM_NUM != '')
														{
															/*$sqlDET		= "SELECT '' AS ITM_CATEG, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY,
																				A.ITM_PRICE, A.ITM_TOTAL, A.ITM_NOTES,
																				B.ITM_NAME
																			FROM tbl_bom_detail A
																				INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE BOM_NUM = '$BOM_NUM' 
																				AND B.PRJCODE = '$PRJCODE'";*/
															/*$sqlDET		= "SELECT B.ITM_CATEG, B.ITM_CODE, B.ITM_UNIT, 0 AS ITM_QTY,
																				0 AS ITM_PRICE, 0 AS ITM_TOTAL, '' AS ITM_NOTES,
																				B.ITM_NAME
																			FROM tbl_bom_header A
																				INNER JOIN tbl_item B ON A.BOM_FG = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE BOM_NUM = '$BOM_NUM' 
																				AND B.PRJCODE = '$PRJCODE' ";*/
															$sqlDET		= "SELECT B.ITM_CATEG, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.ITM_QTY AS ITM_QTY_PLAN,
																				A.ITM_PRICE, (A.ITM_QTY * A.ITM_PRICE) AS ITM_TOTAL, '' AS ITM_NOTES,
																				B.ITM_NAME
																			FROM tbl_bom_stfdetail A
																				LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE BOM_NUM = '$BOM_NUM' 
																				AND B.PRJCODE = '$PRJCODE'
																				AND A.BOMSTF_TYPE = 'OUT'
																				AND B.ISFG = 1";
															$result 	= $this->db->query($sqlDET)->result();
															
															$sqlDETC	= "tbl_bom_stfdetail A
																				LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE BOM_NUM = '$BOM_NUM' 
																				AND B.PRJCODE = '$PRJCODE'
																				AND A.BOMSTF_TYPE = 'OUT'
																				AND B.ISFG = 1";
															$resultC 	= $this->db->count_all($sqlDETC);
														}
			                                            else
			                                            {
			                                                $sqlDET		= "SELECT A.ITM_CATEG, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.ITM_QTY_PLAN,
																				A.ITM_PRICE, A.ITM_TOTAL, A.ITM_NOTES,
																				B.ITM_NAME
																			FROM tbl_ccal_detail A
																				LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE CCAL_NUM = '$CCAL_NUM' 
																				AND B.PRJCODE = '$PRJCODE'
																				AND A.ITM_CATEG = 'FG'";
			                                                $result = $this->db->query($sqlDET)->result();
															
			                                                $sqlDETC	= "tbl_ccal_detail A
			                                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                WHERE 
			                                                                    A.CCAL_NUM = '$CCAL_NUM' 
			                                                                    AND B.PRJCODE = '$PRJCODE'
																				AND A.ITM_CATEG = 'FG'";
			                                                $resultC 	= $this->db->count_all($sqlDETC);
			                                            }
			                                                
			                                            $i	= 0;
			                                            $j	= 0;
			                                            $CCAL_RMCOSTA	= 0;
														$currentRow_fg	= 0;
			                                            if($resultC > 0)
			                                            {
			                                                foreach($result as $row) :
			                                                    $currentRow_fg  = ++$i;																
			                                                    $CCAL_NUM 		= $CCAL_NUM;
			                                                    $CCAL_CODE 		= $CCAL_CODE;
			                                                    $PRJCODE		= $PRJCODE;
			                                                    $ITM_CATEG		= $row->ITM_CATEG;
			                                                    $ITM_CODE 		= $row->ITM_CODE;
			                                                    $ITM_NAME 		= $row->ITM_NAME;
			                                                    $ITM_QTY 		= $row->ITM_QTY;
			                                                    $ITM_QTY_PLAN 	= $row->ITM_QTY_PLAN ?: 1;
			                                                    $ITM_QTYD		= $ITM_QTY;
			                                                    if($ITM_QTY == 0 || $ITM_QTY == '')
			                                                    	$ITM_QTYD = 1;
			                                                    $ITM_UNIT 		= $row->ITM_UNIT;
			                                                    $ITM_PRICE 		= $row->ITM_PRICE;
			                                                    $ITM_TOTAL 		= $row->ITM_TOTAL;
			                                                    if($task == 'add')
			                                                    {
			                                                    	$ITM_TOTAL 	= $CCAL_RMCOST;
			                                                    	$ITM_PRICE 	= $ITM_TOTAL / $ITM_QTYD;
			                                                    }

			                                                    $ITM_NOTES 		= $row->ITM_NOTES;
																
																//$CCAL_RMCOSTA	= $CCAL_RMCOSTA + $ITM_TOTAL;
																
			                                                    $itemConvertion	= 1;
			                                        
			                                                    if ($j==1) {
			                                                        echo "<tr class=zebra1>";
			                                                        $j++;
			                                                    } else {
			                                                        echo "<tr class=zebra2>";
			                                                        $j--;
			                                                    }
			                                                    ?> 
			                                                	<tr>
			                                                        <!-- NO URUT -->
			                                                        <td height="25" style="text-align:center">
			                                                            <?php
																			echo "$currentRow_fg.";
			                                                            ?>
			                                                            <input style="display:none" type="Checkbox" id="data_fg[<?php echo $currentRow; ?>][chk]" name="data_fg[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
			                                                            <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
			                                                        </td>
			                                                        
			                                                        <!-- ITEM CODE -->
			                                                        <td style="text-align:left">
			                                                            <?php print $ITM_CODE; ?>
			                                                            <input type="hidden" id="data_fg<?php echo $currentRow; ?>ITM_CODE" name="data_fg[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_fg<?php echo $currentRow; ?>CCAL_NUM" name="data_fg[<?php echo $currentRow; ?>][CCAL_NUM]" value="<?php print $CCAL_NUM; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_fg<?php echo $currentRow; ?>CCAL_CODE" name="data_fg[<?php echo $currentRow; ?>][CCAL_CODE]" value="<?php print $CCAL_CODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_fg<?php echo $currentRow; ?>PRJCODE" name="data_fg[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_fg<?php echo $currentRow; ?>ITM_CATEG" name="data_fg[<?php echo $currentRow; ?>][ITM_CATEG]" value="FG" width="10" size="15">
			                                                        </td>
			                                                        
			                                                        <!-- ITEM NAME -->
			                                                        <td style="text-align:left"><?php echo $ITM_NAME; ?></td>
			                                                        
			                                                        <!-- ITEM QTY -->  
			                                                        <td style="text-align:right">
			                                                            <input type="text" class="form-control" style="text-align:right" name="ITM_QTYX_fg<?php echo $currentRow; ?>" id="ITM_QTYX_fg<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM_fg(this, <?php echo $currentRow; ?>);" disabled>
			                                                             <input type="hidden" id="data_fg<?php echo $currentRow; ?>ITM_QTY" name="data_fg[<?php echo $currentRow; ?>][ITM_QTY]" value="<?php print $ITM_QTY; ?>">
			                                                        </td>
			                                                        
			                                                        <!-- ITEM QTY PLAN-->  
			                                                        <td style="text-align:right">
			                                                            <input type="text" class="form-control" style="text-align:right" name="ITM_QTY_PLANX_fg<?php echo $currentRow; ?>" id="ITM_QTY_PLANX_fg<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY_PLAN, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM_fg(this, <?php echo $currentRow; ?>);" >
			                                                             <input type="hidden" id="data_fg<?php echo $currentRow; ?>ITM_QTY_PLAN" name="data_fg[<?php echo $currentRow; ?>][ITM_QTY_PLAN]" value="<?php print $ITM_QTY_PLAN; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM UNIT -->
			                                                        <td style="text-align:center">
			                                                            <?php print $ITM_UNIT; ?>  
			                                                            <input type="hidden" id="data_fg<?php echo $currentRow; ?>ITM_UNIT" name="data_fg[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
			                                                        </td>
			                                                             
			                                                        <!-- ITEM PRICE -->
			                                                        <td style="text-align:left">
			                                                            <input type="text" class="form-control" style="text-align:right; min-width:100px" name="ITM_PRICEX_fg<?php echo $currentRow; ?>" id="ITM_PRICEX_fg<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getPRICE_fg(this, <?php echo $currentRow; ?>);" disabled>
			                                                            <input style="text-align:right" type="hidden" name="data_fg[<?php echo $currentRow; ?>][ITM_PRICE]" id="data_fg<?php echo $currentRow; ?>ITM_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM TOTAL COST -->
			                                                        <td style="text-align:left">
			                                                            <input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALX_fg<?php echo $currentRow; ?>" id="ITM_TOTALX_fg<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" size="10" disabled >
			                                                            <input style="text-align:right" type="hidden" name="data_fg[<?php echo $currentRow; ?>][ITM_TOTAL]" id="data_fg<?php echo $currentRow; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM NOTES -->
			                                                        <td style="text-align:left">
			                                                            <input type="text" class="form-control" name="data_fg[<?php echo $currentRow; ?>][ITM_NOTES]" id="data_fg<?php echo $currentRow; ?>ITM_NOTES" value="<?php echo $ITM_NOTES; ?>">
			                                                        </td>
			                                                    </tr>
			                                                    <?php
			                                                endforeach;
			                                            }
			                                        ?>
			                                        <input type="hidden" name="totalrow_fg" id="totalrow_fg" value="<?php echo $currentRow_fg; ?>">
			                                	</table>
			                              	</div>
			                            </div>
			                        </div>
			                        <br>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-success">
			                                	<br>
			                                    <?php if($CCAL_STAT == 1 || $CCAL_STAT == 4) { ?>
			                                    <div class="form-group" style="display:none">
			                                        <div class="col-sm-10">
			                                            <script>
			                                                var url_rm = "<?php echo $url_RawMtr;?>";
			                                                function selectitem_rm()
			                                                {
			                                                    title = 'Select Item';
			                                                    w = 1000;
			                                                    h = 550;
			                                                    //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
			                                                    var left = (screen.width/2)-(w/2);
			                                                    var top = (screen.height/2)-(h/2);
			                                                    return window.open(url_rm, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			                                                }
			                                            </script>
			                                            <button class="btn btn-primary" type="button" onClick="selectitem_rm();">
			                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo $Material; ?>
			                                            </button>
			                                        </div>
			                                    </div>
			                                    <?php } ?>
			                               		<table width="100%" border="1" id="tbl_rm">
			                                        <tr style="background:#3C8DB5; font-weight:bold; color:#FFF">
			                                            <td width="2%" height="25" style="text-align:center">No.</td>
			                                          	<td width="13%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </td>
			                                          	<td width="22%" style="text-align:center" nowrap><?php echo $ItemName; ?> </td>
			                                          	<td width="12%" style="text-align:center" nowrap><?php echo $Quantity; ?> </td> 
			                                            <!-- Input Manual -->
			                                            <td width="8%" style="text-align:center" nowrap><?php echo $Unit; ?> </td>
			                                          	<td width="9%" style="text-align:center" nowrap><?php echo $UnitPrice; ?> </td>
			                                          	<td width="13%" style="text-align:center" nowrap>Total</td>
			                                          	<td width="21%" style="text-align:center" nowrap><?php echo $Description; ?></td>
			                                      	</tr>
													<?php
			                                            $resultC	= 0;
			                                            if($task == 'edit')
			                                            {
			                                                $sqlDETC	= "tbl_ccal_detail A
			                                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                WHERE 
			                                                                    A.CCAL_NUM = '$CCAL_NUM' 
			                                                                    AND B.PRJCODE = '$PRJCODE'
																				AND A.ITM_CATEG = 'RM'";
			                                                //$resultC 	= $this->db->count_all($sqlDETC);
															
															$sqlDET		= "SELECT A.ITM_CODE, A.ITM_UNIT,
																				A.ITM_QTY, A.ITM_QTY_PLAN, A.ITM_PRICE, A.ITM_TOTAL,
																				A.ITM_NOTES, A.ITM_CATEG, B.ITM_NAME
																			FROM tbl_ccal_detail A
																				LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE CCAL_NUM = '$CCAL_NUM' 
																				AND B.PRJCODE = '$PRJCODE'
																				AND A.ITM_CATEG = 'RM'";
															$result = $this->db->query($sqlDET)->result();
			                                            }
														else
														{
															/*$sqlDET		= "SELECT A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_NOTES,
																				B.ITM_CATEG, B.ITM_NAME
																			FROM tbl_bom_detail A
																				INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE BOM_NUM = '$BOM_NUM'";*/
															$sqlDET		= "SELECT B.ITM_CATEG, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY,
																				A.ITM_PRICE, A.ITM_TOTAL, A.ITM_NOTES,
																				B.ITM_NAME
																			FROM tbl_bom_detail A
																				LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE BOM_NUM = '$BOM_NUM' 
																				AND B.PRJCODE = '$PRJCODE'
																				AND A.BOM_TYPE = 'IN'
																				AND B.ISRM = 1";
															$result = $this->db->query($sqlDET)->result();
														}
			                                                
			                                            $i	= 0;
			                                            $j	= 0;
														$CCAL_RMCOSTA	= 0;
														$currentRow_rm	= 0;
			                                            //if($resultC > 0)
			                                            //{
			                                                foreach($result as $row) :
			                                                    $currentRow_rm  = ++$i;																
			                                                    $CCAL_NUM 		= $CCAL_NUM;
			                                                    $CCAL_CODE 		= $CCAL_CODE;
			                                                    $PRJCODE		= $PRJCODE;
			                                                    $ITM_CATEG		= $row->ITM_CATEG;
			                                                    $ITM_CODE 		= $row->ITM_CODE;
			                                                    $ITM_NAME 		= $row->ITM_NAME;
			                                                    $ITM_QTY 		= $row->ITM_QTY;
			                                                    $ITM_UNIT 		= $row->ITM_UNIT;
			                                                    $ITM_PRICE 		= $row->ITM_PRICE;
			                                                    $ITM_TOTAL 		= $row->ITM_TOTAL;
			                                                    $ITM_NOTES 		= $row->ITM_NOTES;
																
																$CCAL_RMCOSTA	= $CCAL_RMCOSTA + $ITM_TOTAL;
																
			                                                    $itemConvertion	= 1;
			                                        
			                                                    if ($j==1) {
			                                                        echo "<tr class=zebra1>";
			                                                        $j++;
			                                                    } else {
			                                                        echo "<tr class=zebra2>";
			                                                        $j--;
			                                                    }
			                                                    ?> 
			                                                	<tr>
			                                                        <!-- NO URUT -->
			                                                        <td width="2%" height="25" style="text-align:center">
			                                                            <?php
			                                                                /*if($CCAL_STAT == 1)
			                                                                {
			                                                                    ?>
			                                                                        <a href="#" onClick="deleteRow(<?php echo $currentRow_rm; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
			                                                                    <?php
			                                                                }
			                                                                else
			                                                                {*/
			                                                                    echo "$currentRow_rm.";
			                                                                //}
			                                                            ?>
			                                                            <input style="display:none" type="Checkbox" id="data_rm[<?php echo $currentRow_rm; ?>][chk]" name="data_rm[<?php echo $currentRow_rm; ?>][chk]" value="<?php echo $currentRow_rm; ?>" onClick="pickThis(this,<?php echo $currentRow_rm; ?>)">
			                                                            <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
			                                                        </td>
			                                                        
			                                                        <!-- ITEM CODE -->
			                                                        <td width="13%" style="text-align:left">
			                                                            <?php print $ITM_CODE; ?>
			                                                            <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>ITM_CODE" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>CCAL_NUM" name="data_rm[<?php echo $currentRow_rm; ?>][CCAL_NUM]" value="<?php echo $CCAL_NUM; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>CCAL_CODE" name="data_rm[<?php echo $currentRow_rm; ?>][CCAL_CODE]" value="<?php echo $CCAL_CODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>PRJCODE" name="data_rm[<?php echo $currentRow_rm; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>ITM_CATEG" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_CATEG]" value="RM" width="10" size="15">
			                                                        </td>
			                                                        
			                                                        <!-- ITEM NAME -->
			                                                        <td width="22%" style="text-align:left"><?php echo $ITM_NAME; ?></td>
			                                                        
			                                                        <!-- ITEM QTY -->  
			                                                        <td width="12%" style="text-align:right">
			                                                             <input type="text" class="form-control" style="max-width:200px; text-align:right" name="ITM_QTYX_rm<?php echo $currentRow_rm; ?>" id="ITM_QTYX_rm<?php echo $currentRow_rm; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM_rm(this, <?php echo $currentRow_rm; ?>);" disabled >
			                                                             <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>ITM_QTY" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_QTY]" value="<?php print $ITM_QTY; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM UNIT -->
			                                                        <td width="8%" style="text-align:center">
			                                                            <?php print $ITM_UNIT; ?>  
			                                                            <input type="hidden" id="data_rm<?php echo $currentRow_rm; ?>ITM_UNIT" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
			                                                        </td>
			                                                             
			                                                        <!-- ITEM PRICE -->
			                                                        <td width="9%" style="text-align:left"> 
			                                                            <input type="text" class="form-control" style="text-align:right; min-width:100px" name="ITM_PRICEX_rm<?php echo $currentRow_rm; ?>" id="ITM_PRICEX_rm<?php echo $currentRow_rm; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getPRICE_rm(this, <?php echo $currentRow_rm; ?>);" disabled>
			                                                            <input type="hidden" style="text-align:right" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_PRICE]" id="data_rm<?php echo $currentRow_rm; ?>ITM_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM TOTAL COST -->
			                                                        <td width="13%" style="text-align:left">
			                                                            <input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALX_rm<?php echo $currentRow_rm; ?>" id="ITM_TOTALX_rm<?php echo $currentRow_rm; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" disabled>
			                                                            <input style="text-align:right" type="hidden" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_TOTAL]" id="data_rm<?php echo $currentRow_rm; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM NOTES -->
			                                                        <td width="21%" style="text-align:left">
			                                                            <input type="text" class="form-control" name="data_rm[<?php echo $currentRow_rm; ?>][ITM_NOTES]" id="data_rm<?php echo $currentRow_rm; ?>ITM_NOTES" value="<?php echo $ITM_NOTES; ?>">
			                                                        </td>
			                                                    </tr>
			                                                    <?php
			                                                endforeach;
			                                            //}
			                                        ?>
			                                        <input type="hidden" name="totalrow_rm" id="totalrow_rm" value="<?php echo $currentRow_rm; ?>">
			                                	</table>
			                              	</div>
			                            </div>
			                        </div>
			                        <br>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-warning">
			                                	<br>
			                                    <?php if($CCAL_STAT == 1 || $CCAL_STAT == 4) { ?>
			                                    <div class="form-group">
			                                        <div class="col-sm-10">
			                                            <script>
			                                                var url = "<?php echo $url_OthC;?>";
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
			                                            <button class="btn btn-warning" type="button" onClick="selectitem();">
			                                            <i class="fa fa-dollar"></i>&nbsp;&nbsp;<?php echo $OthCost; ?>
			                                            </button>
			                                        </div>
			                                    </div>
			                                    <?php } ?>
			                               		<table width="100%" border="1" id="tbl_oth">
			                                        <tr style="background:#EC971F; font-weight:bold; color:#FFF">
			                                            <td width="2%" height="25" style="text-align:center">No.</td>
			                                          	<td width="12%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </td>
			                                          	<td width="23%" style="text-align:center" nowrap><?php echo $ItemName; ?> </td>
			                                          	<td width="12%" style="text-align:center" nowrap><?php echo $Quantity; ?> </td> 
			                                            <!-- Input Manual -->
			                                            <td width="8%" style="text-align:center" nowrap><?php echo $Unit; ?> </td>
			                                          	<td width="9%" style="text-align:center" nowrap><?php echo $UnitPrice; ?> </td>
			                                          	<td width="13%" style="text-align:center" nowrap>Total</td>
			                                          	<td width="21%" style="text-align:center" nowrap><?php echo $Description; ?></td>
			                                      	</tr>
													<?php
			                                            $resultC	= 0;
			                                            if($task == 'edit')
			                                            {
			                                                $sqlDET		= "SELECT A.ITM_CATEG, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY,
																				A.ITM_PRICE, A.ITM_TOTAL, A.ITM_NOTES,
																				B.ITM_NAME
																			FROM tbl_ccal_detail A
																				INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE CCAL_NUM = '$CCAL_NUM' 
																				AND B.PRJCODE = '$PRJCODE'
																				AND A.ITM_CATEG = 'OTH'";
			                                                $result = $this->db->query($sqlDET)->result();
															
			                                                $sqlDETC	= "tbl_ccal_detail A
			                                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                WHERE 
			                                                                    A.CCAL_NUM = '$CCAL_NUM' 
			                                                                    AND B.PRJCODE = '$PRJCODE'
																				AND A.ITM_CATEG = 'OTH'";
			                                                $resultC 	= $this->db->count_all($sqlDETC);
			                                            }

			                                            $i	= 0;
			                                            $j	= 0;
														$CCAL_RMCOSTB	= 0;
														$currentRow_oth	= 0;
			                                            if($resultC > 0)
			                                            {
			                                                foreach($result as $row) :
			                                                    $currentRow_oth = ++$i;																
			                                                    $CCAL_NUM 		= $CCAL_NUM;
			                                                    $CCAL_CODE 		= $CCAL_CODE;
			                                                    $PRJCODE		= $PRJCODE;
			                                                    $ITM_CATEG		= $row->ITM_CATEG;
			                                                    $ITM_CODE 		= $row->ITM_CODE;
			                                                    $ITM_NAME 		= $row->ITM_NAME;
			                                                    $ITM_QTY 		= $row->ITM_QTY;
			                                                    $ITM_UNIT 		= $row->ITM_UNIT;
			                                                    $ITM_PRICE 		= $row->ITM_PRICE;
			                                                    $ITM_TOTAL 		= $row->ITM_TOTAL;
			                                                    $ITM_NOTES 		= $row->ITM_NOTES;
																
																$CCAL_RMCOSTB	= $CCAL_RMCOSTB + $ITM_TOTAL;
																
			                                                    $itemConvertion	= 1;
			                                        
			                                                    if ($j==1) {
			                                                        echo "<tr class=zebra1>";
			                                                        $j++;
			                                                    } else {
			                                                        echo "<tr class=zebra2>";
			                                                        $j--;
			                                                    }
			                                                    ?> 
			                                                	<tr>
			                                                        <!-- NO URUT -->
			                                                        <td width="2%" height="25" style="text-align:center">
			                                                            <?php
			                                                                if($CCAL_STAT == 1)
			                                                                {
			                                                                    ?>
			                                                                        <a href="#" onClick="deleteRow(<?php echo $currentRow_oth; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
			                                                                    <?php
			                                                                }
			                                                                else
			                                                                {
			                                                                    echo "$currentRow_oth.";
			                                                                }
			                                                            ?>
			                                                            <input style="display:none" type="Checkbox" id="data_oth[<?php echo $currentRow_oth; ?>][chk]" name="data_oth[<?php echo $currentRow_oth; ?>][chk]" value="<?php echo $currentRow_oth; ?>" onClick="pickThis(this,<?php echo $currentRow_oth; ?>)">
			                                                            <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
			                                                        </td>
			                                                        
			                                                        <!-- ITEM CODE -->
			                                                        <td width="12%" style="text-align:left">
			                                                            <?php print $ITM_CODE; ?>
			                                                            <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>ITM_CODE" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>BOM_NUM" name="data_oth[<?php echo $currentRow_oth; ?>][CCAL_NUM]" value="<?php echo $CCAL_NUM; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>CCAL_CODE" name="data_oth[<?php echo $currentRow_oth; ?>][CCAL_CODE]" value="<?php echo $CCAL_CODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>PRJCODE" name="data_oth[<?php echo $currentRow_oth; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15">
			                                                            <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>ITM_CATEG" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_CATEG]" value="OTH" width="10" size="15">
			                                                        </td>
			                                                        
			                                                        <!-- ITEM NAME -->
			                                                        <td width="23%" style="text-align:left"><?php echo $ITM_NAME; ?></td>
			                                                        
			                                                        <!-- ITEM QTY -->  
			                                                        <td width="12%" style="text-align:right">
			                                                             <input type="text" class="form-control" style="max-width:200px; text-align:right" name="ITM_QTYX_oth<?php echo $currentRow_oth; ?>" id="ITM_QTYX_oth<?php echo $currentRow_oth; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM_oth(this, <?php echo $currentRow_oth; ?>);" <?php if($disRow == 1) { ?> disabled <?php } ?>>
			                                                             <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>ITM_QTY" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_QTY]" value="<?php print $ITM_QTY; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM UNIT -->
			                                                        <td width="8%" style="text-align:center">
			                                                            <?php print $ITM_UNIT; ?>  
			                                                            <input type="hidden" id="data_oth<?php echo $currentRow_oth; ?>ITM_UNIT" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
			                                                        </td>
			                                                             
			                                                        <!-- ITEM PRICE -->
			                                                        <td width="9%" style="text-align:left">
			                                                            <input type="text" class="form-control" style="text-align:right; min-width:100px" name="ITM_PRICEX_oth<?php echo $currentRow_oth; ?>" id="ITM_PRICEX_oth<?php echo $currentRow_oth; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getPRICE_oth(this, <?php echo $currentRow_oth; ?>);" <?php if($disRow == 1) { ?> disabled <?php } ?>>
			                                                            <input type="hidden" style="text-align:right" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_PRICE]" id="data_oth<?php echo $currentRow_oth; ?>ITM_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>">
			                                                        </td>
			                                                            
			                                                        <!-- ITEM TOTAL COST -->
			                                                        <td width="13%" style="text-align:left">
			                                                            <input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALX_oth<?php echo $currentRow_oth; ?>" id="ITM_TOTALX_oth<?php echo $currentRow_oth; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" disabled >
			                                                            <input style="text-align:right" type="hidden" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_TOTAL]" id="data_oth<?php echo $currentRow_oth; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>">
			                                                            
			                                                        </td>
			                                                            
			                                                        <!-- ITEM NOTES -->
			                                                        <td width="21%" style="text-align:left">
			                                                            <input type="text" class="form-control" name="data_oth[<?php echo $currentRow_oth; ?>][ITM_NOTES]" id="data_oth<?php echo $currentRow_oth; ?>ITM_NOTES" value="<?php echo $ITM_NOTES; ?>">
			                                                        </td>
			                                                    </tr>
			                                                    <?php
			                                                endforeach;
			                                            }
			                                        ?>
			                                        <input type="hidden" name="totalrow_oth" id="totalrow_oth" value="<?php echo $currentRow_oth; ?>">
			                                	</table>
			                              	</div>
			                            </div>
			                        </div>
			                        <br>
			                        <div class="row">
			                            <div class="col-md-12">
			                              <div class="box box-info">
			                               	<div class="box-header with-border">
			                                      	<h3 class="box-title"><?php echo $ExpAccum; ?></h3>
			                                    </div>
			                               		<table width="100%" border="0" id="tbl">
			                                        <tr>
			                                            <td width="3%" height="25" style="text-align:center">&nbsp;</td>
			                                          	<td width="8%" style="text-align:center" nowrap>&nbsp;</td>
			                                          	<td width="18%" style="text-align:center" nowrap>&nbsp;</td>
			                                          	<td width="14%" style="text-align:center" nowrap>&nbsp;</td> 
			                                            <!-- Input Manual -->
			                                            <td width="6%" style="text-align:center" nowrap>&nbsp;</td>
			                                          	<td width="6%" style="text-align:center" nowrap>&nbsp;</td>
			                                          	<td width="7%" style="text-align:center" nowrap>&nbsp;</td>
			                                          	<td width="4%" style="text-align:center" nowrap>&nbsp;</td>
			                                          	<td width="20%" style="text-align:right" nowrap><?php echo $RawMtr; ?> :</td>
			                                          	<td width="14%" style="text-align:right" nowrap>
			                                            	<input type="text" class="form-control" style="text-align:right" name="CCAL_RMCOSTX" id="CCAL_RMCOSTX" value="<?php echo number_format($CCAL_RMCOSTA, 2); ?>" disabled>
			                                            	<input type="hidden" class="form-control" name="CCAL_RMCOST" id="CCAL_RMCOST" value="<?php echo $CCAL_RMCOSTA; ?>">
			                                            </td>
			                                      	</tr>
			                                        <tr>
			                                            <td height="25" style="text-align:center">&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:right" nowrap><?php echo $OthCost; ?> :</td>
			                                            <td style="text-align:right; border-bottom:groove" nowrap>
			                                            	<input type="text" class="form-control" style="text-align:right" name="CCAL_RMCOSTX" id="CCAL_OTHCOSTX" value="<?php echo number_format($CCAL_RMCOSTB, 2); ?>" disabled>
			                                            	<input type="hidden" class="form-control" name="CCAL_OTHCOST" id="CCAL_OTHCOST" value="<?php echo $CCAL_RMCOSTB; ?>">
			                                       		</td>
			                                        </tr>
			                                        <?php
			                                        	$CCAL_VOLMP 	= $CCAL_VOLM;
			                                        	if($CCAL_VOLM == 0 || $CCAL_VOLM == '')
			                                        	{
			                                        		$CCAL_VOLMP	= 1;
			                                        	}
														$CCAL_TOTCOST	= $CCAL_RMCOSTA + $CCAL_RMCOSTB;
														$CCAL_PROFITV	= $CCAL_TOTCOST * $CCAL_PROFIT / 100;
														$CCAL_ITMPRICE	= ($CCAL_TOTCOST + $CCAL_PROFITAM) / $CCAL_VOLMP;
													?>
			                                        <tr>
			                                          <td height="25" style="text-align:center">&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:right; font-weight:bold" nowrap>Grand Total :</td>
			                                          <td style="text-align:right; font-weight:bold" nowrap>
			                                            	<input type="text" class="form-control" style="text-align:right" name="CCAL_TOTCOSTX" id="CCAL_TOTCOSTX" value="<?php echo number_format($CCAL_TOTCOST, 2); ?>" disabled>
			                                            	<input type="hidden" class="form-control" name="CCAL_TOTCOST" id="CCAL_TOTCOST" value="<?php echo $CCAL_TOTCOST; ?>">
			                                          </td>
			                                        </tr>
			                                        <tr>
			                                          	<td height="25" colspan="9" style="text-align:right">
			                                           		<label><?php echo $ProfitCal; ?> : &nbsp;</label>
			                                             	<label>
			                                                	<input type="text" class="form-control" style="max-width:80px; text-align:right" name="CCAL_PROFITX" id="CCAL_PROFITX" value="<?php echo number_format($CCAL_PROFIT, 2); ?>" onBlur="getProfit(this);" <?php if($disRow == 1) { ?> disabled <?php } ?>>
			                                                	<input type="hidden" class="form-control" style="max-width:80px" name="CCAL_PROFIT" id="CCAL_PROFIT" value="<?php echo $CCAL_PROFIT; ?>">
			                                                </label>
			                                           		<label>&nbsp;% :</label>
			                                          	</td>
			                                          	<td style="text-align:right; font-weight:bold" nowrap>
			                                                <input type="text" class="form-control" style="text-align:right" name="CCAL_PROFITAMX" id="CCAL_PROFITAMX" value="<?php echo number_format($CCAL_PROFITAM, 2); ?>" onBlur="getProfitP(this);" <?php if($disRow == 1) { ?> disabled <?php } ?>>
			                                            	<input type="hidden" class="form-control" style="text-align:right" name="CCAL_PROFITAM" id="CCAL_PROFITAM" value="<?php echo $CCAL_PROFITAM; ?>">
			                                            </td>
			                                        </tr>
			                                        <tr>
			                                            <td height="25" style="text-align:center">&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:center" nowrap>&nbsp;</td>
			                                            <td style="text-align:right; font-weight:bold" nowrap><?php echo $ItemPrice; ?> :</td>
			                                            <td style="text-align:right; font-weight:bold" nowrap>
			                                          		<input type="text" class="form-control" style="text-align:right" name="CCAL_ITMPRICEX" id="CCAL_ITMPRICEX" value="<?php echo number_format($CCAL_ITMPRICE, 2); ?>" disabled>
			                                          		<input type="hidden" class="form-control" style="text-align:right" name="CCAL_ITMPRICE" id="CCAL_ITMPRICE" value="<?php echo $CCAL_ITMPRICE; ?>">
			                                          </td>
			                                        </tr>
			                                        <tr>
			                                          <td height="25" style="text-align:center">&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:center" nowrap>&nbsp;</td>
			                                          <td style="text-align:right; font-weight:bold" nowrap>&nbsp;</td>
			                                          <td style="text-align:right; font-weight:bold" nowrap>&nbsp;</td>
			                                        </tr>
			                                        <tr>
			                                          <td height="25" colspan="10" style="text-align:left">
			                                          	<?php
															if($task=='add')
															{
																if($CCAL_STAT == 1 && $ISCREATE == 1)
																{
																	?>
																		<button class="btn btn-primary">
																		<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
																		</button>&nbsp;
																	<?php
																}
															}
															else
															{
																if($ISAPPROVE == 1 && $CCAL_STAT == 3)
																{
																	?>
																		<button class="btn btn-primary" style="display:none" id="tblClose">
																		<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
																		</button>&nbsp;
																	<?php
																}
																elseif($ISAPPROVE == 1 && $CCAL_STAT != 3)
																{
																	?>
																		<button class="btn btn-primary">
																		<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
																		</button>&nbsp;
																	<?php
																}
																elseif($ISCREATE == 1 && ($CCAL_STAT == 1 || $CCAL_STAT == 4))
																{
																	?>
																		<button class="btn btn-primary" >
																		<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
																		</button>&nbsp;
																	<?php
																}
															}
															$backURL	= site_url('c_production/c_I73mc05tc4l/glIt3mc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
															echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
														?><br><br>
			                                          </td>
			                                        </tr>
			                                	</table>
			                              	</div>
			                            </div>
			                        </div>

									<?php
			                            $DOC_NUM	= $CCAL_NUM;
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
			                    </form>
			                </div>
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
		$qtyDetail	= 'Detail item tidak boleh kosong.';
		$BOMNum		= 'Pilih kode pencocokan warna.';
		$volmAlert	= 'Qty order tidak boleh nol.';
		$RMnOTHDet	= 'Detail bahan baku atau biaya tidak boleh kosong.';
	}
	else
	{
		$qtyDetail	= 'Item Detail can not be empty.';
		$BOMNum		= 'Select a Mtaching Code.';
		$volmAlert	= 'Order qty can not be zero.';
		$RMnOTHDet	= 'Detail of Raw Material or Other Cost can not be empty.';
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

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PR_NUM		= arrItem[0];
		//swal(PR_NUM);
		//document.getElementById("PO_NUM1").value = PO_NUM;
		document.getElementById("BOM_NUMX").value = PR_NUM;
		document.frmsrch.submitSrch.click();
	}
	
	function getVolITM(thisVal)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var CCAL_VOLMX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('CCAL_VOLM').value 	= parseFloat(Math.abs(CCAL_VOLMX));
		document.getElementById('CCAL_VOLMX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CCAL_VOLMX)),decFormat));
		
		itemPrice();
	}
	
	function getQtyITM_fg(thisVal, theRow) // FG - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYFG 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data_fg'+theRow+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTYFG));
		document.getElementById('ITM_QTYX_fg'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYFG)),decFormat));
		
		document.getElementById('CCAL_VOLM').value 	= parseFloat(Math.abs(ITM_QTYFG));
		document.getElementById('CCAL_VOLMX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYFG)),decFormat));
		
		var ITM_PRICE 	= document.getElementById('data_fg'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYFG) * parseFloat(ITM_PRICE);
		
		document.getElementById('data_fg'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_fg'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));

		totalAllRow();
	}
	
	function getQtyITM_fg2(thisVal, theRow) // FG - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYFG 	= document.getElementById('data_fg'+theRow+'ITM_QTY').value;
		var ITM_PRICE 	= document.getElementById('data_fg'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYFG) * parseFloat(ITM_PRICE);
		document.getElementById('data_fg'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_fg'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function getPRICE_fg(thisVal, theRow) // FG - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data_fg'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX_fg'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data_fg'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data_fg'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_fg'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function add_item_rm(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var CCAL_NUM 	= "<?php echo $CCAL_NUM; ?>";
		var CCAL_CODE 	= "<?php echo $CCAL_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_NAME 		= arrItem[2];
		ITM_UNIT 		= arrItem[4];
		ITM_PRICE 		= arrItem[5];
		
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl_rm');
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data_rm['+intIndex+'][chk]" name="data_rm['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data_rm'+intIndex+'ITM_CODE" name="data_rm['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data_rm'+intIndex+'CCAL_NUM" name="data_rm['+intIndex+'][CCAL_NUM]" value="'+CCAL_NUM+'" width="10" size="15"><input type="hidden" id="data_rm'+intIndex+'CCAL_CODE" name="data_rm['+intIndex+'][CCAL_CODE]" value="'+CCAL_CODE+'" width="10" size="15"><input type="hidden" id="data_rm'+intIndex+'PRJCODE" name="data_rm['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data_rm'+intIndex+'ITM_CATEG" name="data_rm['+intIndex+'][ITM_CATEG]" value="RM" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITM QTY NEEDED
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="ITM_QTYX_rm'+intIndex+'" id="ITM_QTYX_rm'+intIndex+'" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM_rm(this, '+intIndex+');" ><input type="hidden" id="data_rm'+intIndex+'ITM_QTY" name="data_rm['+intIndex+'][ITM_QTY]" value="0">';
		
		// ITM UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data_rm'+intIndex+'ITM_UNIT" name="data_rm['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM PRICE
		ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
		ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="ITM_PRICEX_rm'+intIndex+'" id="ITM_PRICEX_rm'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getPRICE_rm(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data_rm['+intIndex+'][ITM_PRICE]" id="data_rm'+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'">';

		// ITEM TOTAL COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALX_rm'+intIndex+'" id="ITM_TOTALX_rm'+intIndex+'" value="0.00" disabled><input style="text-align:right" type="hidden" name="data_rm['+intIndex+'][ITM_TOTAL]" id="data_rm'+intIndex+'ITM_TOTAL" value="0.00">';
		
		// ITEM NOTES
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input style="text-align:right" type="text" class="form-control" name="data_rm['+intIndex+'][ITM_NOTES]" id="data_rm'+intIndex+'ITM_NOTES" value="">';
				
		document.getElementById('totalrow_rm').value = intIndex;
	}
	
	function getQtyITM_rm(thisVal, theRow) // RM - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data_rm'+theRow+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTYX));
		document.getElementById('ITM_QTYX_rm'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYX)),decFormat));
		
		var ITM_PRICE 	= document.getElementById('data_rm'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYX) * parseFloat(ITM_PRICE);
		document.getElementById('data_rm'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_rm'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function getPRICE_rm(thisVal, theRow) // RM - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data_rm'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX_rm'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data_rm'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data_rm'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_rm'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function add_item_oth(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var CCAL_NUM 	= "<?php echo $CCAL_NUM; ?>";
		var CCAL_CODE 	= "<?php echo $CCAL_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_NAME 		= arrItem[2];
		ITM_UNIT 		= arrItem[4];
		ITM_PRICE 		= arrItem[5];
		
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl_oth');
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data_oth['+intIndex+'][chk]" name="data_oth['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data_oth'+intIndex+'ITM_CODE" name="data_oth['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data_oth'+intIndex+'BOM_NUM" name="data_oth['+intIndex+'][CCAL_NUM]" value="'+CCAL_NUM+'" width="10" size="15"><input type="hidden" id="data_oth'+intIndex+'CCAL_CODE" name="data_oth['+intIndex+'][CCAL_CODE]" value="'+CCAL_CODE+'" width="10" size="15"><input type="hidden" id="data_oth'+intIndex+'PRJCODE" name="data_oth['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data_oth'+intIndex+'ITM_CATEG" name="data_oth['+intIndex+'][ITM_CATEG]" value="OTH" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITM QTY NEEDED
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="ITM_QTYX_oth'+intIndex+'" id="ITM_QTYX_oth'+intIndex+'" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM_oth(this, '+intIndex+');" ><input type="hidden" id="data_oth'+intIndex+'ITM_QTY" name="data_oth['+intIndex+'][ITM_QTY]" value="0">';
		
		// ITM UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data_oth'+intIndex+'ITM_UNIT" name="data_oth['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM PRICE
		ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
		ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="ITM_PRICEX_oth'+intIndex+'" id="ITM_PRICEX_oth'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getPRICE_oth(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data_oth['+intIndex+'][ITM_PRICE]" id="data_oth'+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'">';

		// ITEM TOTAL COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALX_oth'+intIndex+'" id="ITM_TOTALX_oth'+intIndex+'" value="0.00" disabled ><input style="text-align:right" type="hidden" name="data_oth['+intIndex+'][ITM_TOTAL]" id="data_oth'+intIndex+'ITM_TOTAL" value="0.00">';
		
		// ITEM NOTES
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input style="text-align:right" type="text" class="form-control" name="data_oth['+intIndex+'][ITM_NOTES]" id="data_oth'+intIndex+'ITM_NOTES" value="">';
				
		document.getElementById('totalrow_oth').value = intIndex;
	}
	
	function getQtyITM_oth(thisVal, theRow) // OTH - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data_oth'+theRow+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTYX));
		document.getElementById('ITM_QTYX_oth'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYX)),decFormat));
		
		var ITM_PRICE 	= document.getElementById('data_oth'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYX) * parseFloat(ITM_PRICE);
		document.getElementById('data_oth'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_oth'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function getPRICE_oth(thisVal, theRow) // OTH - OK
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data_oth'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX_oth'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data_oth'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data_oth'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX_oth'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function getPRICE(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function getQtyITM2(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTYX));
		document.getElementById('ITM_QTYX2'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYX)),decFormat));
		
		var ITM_PRICE 	= document.getElementById('data'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYX) * parseFloat(ITM_PRICE);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX2'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function getPRICE2(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX2'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX2'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		totalAllRow();
	}
	
	function totalAllRow()
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRowRM		= parseFloat(document.getElementById('totalrow_rm').value);
		var totRowOTH		= parseFloat(document.getElementById('totalrow_oth').value);

		var CCAL_RMCOSTA	= 0;
		if(totRowRM > 0)
		{
			for(i=1;i<=totRowRM;i++)
			{
				var ITM_TOTALA 	= parseFloat(document.getElementById('data_rm'+i+'ITM_TOTAL').value);
				CCAL_RMCOSTA	= parseFloat(CCAL_RMCOSTA) + parseFloat(ITM_TOTALA);
			}
		}

		document.getElementById('CCAL_RMCOST').value 	= parseFloat(Math.abs(CCAL_RMCOSTA));
		document.getElementById('CCAL_RMCOSTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CCAL_RMCOSTA)),decFormat));
		
		var CCAL_RMCOSTB	= 0;
		if(totRowOTH > 0)
		{
			for(i=1;i<=totRowOTH;i++)
			{
				var ITM_TOTALB 	= parseFloat(document.getElementById('data_oth'+i+'ITM_TOTAL').value);
				CCAL_RMCOSTB	= parseFloat(CCAL_RMCOSTB) + parseFloat(ITM_TOTALB);				
			}
		}
		
		document.getElementById('CCAL_OTHCOST').value 	= parseFloat(Math.abs(CCAL_RMCOSTB));
		document.getElementById('CCAL_OTHCOSTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CCAL_RMCOSTB)),decFormat));
		
		var CCAL_TOTCOSTX		= parseFloat(CCAL_RMCOSTA) + parseFloat(CCAL_RMCOSTB);
		document.getElementById('CCAL_TOTCOST').value 	= parseFloat(Math.abs(CCAL_TOTCOSTX));
		document.getElementById('CCAL_TOTCOSTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CCAL_TOTCOSTX)),decFormat));

		itemPrice();
	}
	
	function getProfit(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		var PROFITP			= eval(thisVal).value.split(",").join("");
		var CCAL_VOLM		= parseFloat(document.getElementById('CCAL_VOLM').value);
		
		document.getElementById('CCAL_PROFIT').value 	= parseFloat(Math.abs(PROFITP));
		document.getElementById('CCAL_PROFITX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROFITP)),decFormat));
		
		var CCAL_TOTCOST	= parseFloat(document.getElementById('CCAL_TOTCOST').value);		
				
		var PROFITV			= parseFloat(CCAL_TOTCOST * PROFITP) / 100;
		
		document.getElementById('CCAL_PROFITAMX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROFITV)),decFormat));
		document.getElementById('CCAL_PROFITAM').value 	= PROFITV;
		
		var CCAL_TOTCOSTG	= parseFloat(CCAL_TOTCOST) + parseFloat(PROFITV);
		//var ITMPRICE		= parseFloat(CCAL_TOTCOSTG / CCAL_VOLM);
		var ITMPRICE		= parseFloat(CCAL_TOTCOSTG);
		
		document.getElementById('CCAL_ITMPRICE').value 	= parseFloat(Math.abs(ITMPRICE));
		document.getElementById('CCAL_ITMPRICEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMPRICE)),decFormat));
		
		var totRowFG		= parseFloat(document.getElementById('totalrow_fg').value);
		if(totRowFG > 0)
		{
			for(i=0;i<totRowFG;i++)
			{
				document.getElementById('data_fg'+i+'ITM_PRICE').value	= ITMPRICE;
				document.getElementById('ITM_PRICEX_fg'+i).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMPRICE)),decFormat));
				getQtyITM_fg2(ITMPRICE, i);
			}
		}
	}
	
	function getProfitP(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		var PROFITAM		= eval(thisVal).value.split(",").join("");
		var CCAL_VOLM		= parseFloat(document.getElementById('CCAL_VOLM').value);
		var CCAL_TOTCOST	= parseFloat(document.getElementById('CCAL_TOTCOST').value);
				
		var PROFITPERC		= parseFloat(PROFITAM / CCAL_TOTCOST) * 100;
		
		document.getElementById('CCAL_PROFIT').value 	= parseFloat(Math.abs(PROFITPERC));
		document.getElementById('CCAL_PROFITX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROFITPERC)),decFormat));
				
		
		document.getElementById('CCAL_PROFITAMX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROFITAM)),decFormat));
		document.getElementById('CCAL_PROFITAM').value 	= PROFITAM;
		
		var CCAL_TOTCOSTG	= parseFloat(CCAL_TOTCOST) + parseFloat(PROFITAM);
		//var ITMPRICE		= parseFloat(CCAL_TOTCOSTG / CCAL_VOLM);
		var ITMPRICE		= parseFloat(CCAL_TOTCOSTG);
		
		document.getElementById('CCAL_ITMPRICE').value 	= parseFloat(Math.abs(ITMPRICE));
		document.getElementById('CCAL_ITMPRICEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMPRICE)),decFormat));
		
		var totRowFG		= parseFloat(document.getElementById('totalrow_fg').value);
		if(totRowFG > 0)
		{
			for(i=0;i<totRowFG;i++)
			{
				document.getElementById('data_fg'+i+'ITM_PRICE').value	= ITMPRICE;
				document.getElementById('ITM_PRICEX_fg'+i).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMPRICE)),decFormat));
				getQtyITM_fg2(ITMPRICE, i);
			}
		}
	}
	
	function itemPrice()
	{
		var decFormat		= document.getElementById('decFormat').value;
		var CCAL_RMCOST		= parseFloat(document.getElementById('CCAL_RMCOST').value);
		var CCAL_OTHCOST	= parseFloat(document.getElementById('CCAL_OTHCOST').value);
		var CCAL_PROFITP	= parseFloat(document.getElementById('CCAL_PROFIT').value);
		var CCAL_VOLM		= parseFloat(document.getElementById('CCAL_VOLM').value);

		var CCAL_TOTCOST	= parseFloat(CCAL_RMCOST) + parseFloat(CCAL_OTHCOST);
				
		var CCAL_PROFIT		= parseFloat(CCAL_TOTCOST * CCAL_PROFITP) / 100;
		
		var CCAL_TOTCOSTG	= parseFloat(CCAL_TOTCOST) + parseFloat(CCAL_PROFIT);
		if(CCAL_VOLM == 0)
			var ITMPRICE	= 0;
		else
		{
			//var ITMPRICE	= parseFloat(CCAL_TOTCOSTG) / parseFloat(CCAL_VOLM);
			var ITMPRICE	= parseFloat(CCAL_TOTCOSTG) / parseFloat(1);
		}

		document.getElementById('CCAL_ITMPRICE').value 	= parseFloat(Math.abs(ITMPRICE));
		document.getElementById('CCAL_ITMPRICEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMPRICE)),decFormat));
		
		var totRowFG		= parseFloat(document.getElementById('totalrow_fg').value);
		if(totRowFG > 0)
		{
			for(i=0;i<totRowFG;i++)
			{
				document.getElementById('data_fg'+i+'ITM_PRICE').value	= ITMPRICE;
				document.getElementById('ITM_PRICEX_fg'+i).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMPRICE)),decFormat));
				getQtyITM_fg2(ITMPRICE, i);
			}
		}
	}
	
	function checkInp()
	{
		totRowFG	= document.getElementById('totalrow_fg').value;
		CUST_CODE	= document.getElementById('CUST_CODE').value;
		BOM_NUM		= document.getElementById('BOM_NUM').value;
		
		if(CUST_CODE == 0)
		{
			swal("<?php echo $alert2; ?>");
			document.getElementById('CUST_CODE').focus();
			return false;	
		}
		if(BOM_NUM == '')
		{
			swal('<?php echo $BOMNum; ?>');
			document.getElementById('BOM_NUM1').focus();
			return false;
		}
		
		if(totRowFG == 0)
		{
			swal('<?php echo $qtyDetail; ?>');
			return false;
		}
		
		var totRowFG		= parseFloat(document.getElementById('totalrow_fg').value);
		if(totRowFG > 0)
		{
			for(i=0;i<totRowFG;i++)
			{
				var ITM_QTY = parseFloat(document.getElementById('data_fg'+i+'ITM_QTY').value);
				if(ITM_QTY == 0)
				{
					swal("<?php echo $alertFG; ?>");
					document.getElementById('ITM_QTYX_fg'+i).focus();
					return false;	
				}
			}
		}
		
		var totRowRM		= parseFloat(document.getElementById('totalrow_rm').value);
		if(totRowRM > 0)
		{
			for(i=1;i<=totRowRM;i++)
			{
				var ITM_QTY = parseFloat(document.getElementById('data_rm'+i+'ITM_QTY').value);
				if(ITM_QTY == 0)
				{
					swal("<?php echo $alertRM; ?>");
					document.getElementById('ITM_QTYX_rm'+i).focus();
					return false;	
				}
			}
		}
		
		var totRowOTH		= parseFloat(document.getElementById('totalrow_oth').value);
		if(totRowOTH > 0)
		{
			for(i=1;i<=totRowOTH;i++)
			{
				var ITM_QTY = parseFloat(document.getElementById('data_oth'+i+'ITM_QTY').value);
				if(ITM_QTY == 0)
				{
					swal("<?php echo $alertOTH; ?>");
					document.getElementById('ITM_QTYX_oth'+i).focus();
					return false;	
				}
			}
		}
		
		if(totRowRM == 0 && totRowOTH == 0)
		{
			swal('<?php echo $RMnOTHDet; ?>');
			return false;
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
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
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