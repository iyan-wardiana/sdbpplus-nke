<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 24 Oktober 2017
	* File Name	= v_salesret_form.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$SR_NUM			= '';
	$SC_NUM			= '';
	$SR_CODE 		= '';
	$SR_TYPE 		= 1; // Internal
	$SR_DATE		= date('Y-m-d');
	$CUST_CODE 		= '0';
	$CUST_DESC 		= '';
	$CUST_ADD		= '';
	$SO_NUM 		= '';
	$SO_CODE		= '';
	$SO_DATE 		= '';
	$SN_NUM 		= '';
	$SN_CODE		= '';
	$SN_DATE 		= '';
	$SR_TOTCOST 	= 0;
	$SR_TOTPPN		= 0;
	$SR_TOTDISC		= 0;
	$SR_NOTES		= '';
	$SR_NOTES1		= '';
	$SR_TOTVOLM 	= 0;
	$SR_STAT 		= 1;
	
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
	$myCount = $this->db->count_all('tbl_sr_header');
	
	$yearCur	= date('Y');
	$sqlC		= "tbl_sr_header WHERE Patt_Year = $yearCur AND PRJCODE = '$PRJCODE'";
	$myCount 	= $this->db->count_all($sqlC);
	
	/*$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pr_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$myMax 		= $myCount+1;
	$thisMonth 	= $month;
	
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
		if($len==1) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0"; else $nol="";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber1		= "$Pattern_Code$PRJCODE$year$month$days-$lastPatternNumb";
	//$DocNumber	= "$DocNumber1"."-D";
	$DocNumber		= "$DocNumber1";
	$SR_NUM			= $DocNumber;
	$SR_CODE		= "$lastPatternNumb"; // OP MANUAL
	
	//$DOCCODE		= substr($lastPatternNumb, -4);
	$DOCCODE		= $lastPatternNumb;
	$DOCYEAR		= date('y');
	$DOCMONTH		= date('m');
	$SR_CODE		= "$Pattern_Code.$DOCCODE.$DOCYEAR.$DOCMONTH"; // MANUAL CODE
	
	$SR_DATE 		= date('d/m/Y');	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_sr_header~$Pattern_Length";
	$dataTarget		= "SR_CODE";
	
	$Patt_Number	= $lastPatternNumb1;
}
else
{
	$isSetDocNo 	= 1;
	$SR_NUM 		= $default['SR_NUM'];
	$SR_CODE 		= $default['SR_CODE'];
	$SR_DATE		= date('d/m/Y', strtotime($default['SR_DATE']));
	$SR_TYPE 		= $default['SR_TYPE'];
	$PRJCODE		= $default['PRJCODE'];
	$CUST_CODE		= $default['CUST_CODE'];
	$CUST_DESC		= $default['CUST_DESC'];
	$CUST_ADD 		= $default['CUST_ADD'];
	$SO_NUM			= $default['SO_NUM'];
	$SO_CODE 		= $default['SO_CODE'];
	$SO_DATE		= $default['SO_DATE'];
	$SN_NUM			= $default['SN_NUM'];
	$SN_CODE 		= $default['SN_CODE'];
	$SN_DATE		= $default['SN_DATE'];
	$SR_TOTVOLM		= $default['SR_TOTVOLM'];
	$SR_TOTCOST		= $default['SR_TOTCOST'];
	$SR_TOTPPN		= $default['SR_TOTPPN'];
	$SR_TOTDISC		= $default['SR_TOTDISC'];
	$VEH_CODE		= $default['VEH_CODE'];
	$VEH_NOPOL		= $default['VEH_NOPOL'];
	$SN_DRIVER		= $default['SN_DRIVER'];
	$SR_NOTES 		= $default['SR_NOTES'];
	$SR_NOTES1 		= $default['SR_NOTES1'];
	$SR_CREATER		= $DefEmp_ID;
	$SR_CREATED		= $default['SR_CREATED'];
	$SR_STAT		= $default['SR_STAT'];
	$Patt_Year		= $default['Patt_Year']; 
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];
}

$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;
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
		$ISDELETE	= $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
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
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'Sent')$Sent = $LangTransl;
			if($TranslCode == 'Return')$Return = $LangTransl;
			if($TranslCode == 'ShipmentNo')$ShipmentNo = $LangTransl;
			if($TranslCode == 'shiplist')$shiplist = $LangTransl;
			if($TranslCode == 'ProductionC')$ProductionC = $LangTransl;
			if($TranslCode == 'selCust')$selCust = $LangTransl;
			if($TranslCode == 'ReturnType')$ReturnType = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'noMtrSent')$noMtrSent = $LangTransl;
			if($TranslCode == 'qtyGTSent')$qtyGTSent = $LangTransl;
			//if($TranslCode == 'checkMC')$checkMC = $LangTransl;
			if($TranslCode == 'selSNNo')$selSNNo = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'maxQty')$maxQty = $LangTransl;
		endforeach;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PR_VALUE
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
				$APPROVE_AMOUNT 	= $SR_TOTCOST;
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
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
					            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
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

				                <!-- SR_NUM, SR_CODE -->
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $DocNumber ?> </label>
					                    <div class="col-sm-9">
				                            <input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="SR_NUM" id="SR_NUM" value="<?php echo $SR_NUM; ?>" >
				                            <input type="text" class="form-control" name="SR_CODE" id="SR_CODE" value="<?php echo $SR_CODE; ?>" readonly >
					                    </div>
					                </div>

				                <!-- SR_DATE -->
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
					                    <div class="col-sm-9">
					                        <div class="input-group date">
					                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
					                            <input type="text" name="SR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $SR_DATE; ?>" style="width:105px">
					                        </div>
					                    </div>
					                </div>

				                <!-- PRJCODE -->
					                <div class="form-group" style="display:none">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
					                    <div class="col-sm-9">
					                        <select name="PRJCODE1" id="PRJCODE1" class="form-control" onChange="chooseProject()" disabled>
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

				                <!-- CUST_CODE -->
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptFrom ?> </label>
					                    <div class="col-sm-9">
					                        <!-- <select name="CUST_CODE" id="CUST_CODE" class="form-control select2" onChange="getSNCODE()"> -->
					                        <select name="CUST_CODE" id="CUST_CODE" class="form-control select2">
					                            <option value="0" > --- </option>
					                            <?php
					                            $i = 0;
					                            if($countCUST > 0)
					                            {
					                                foreach($vwCUST as $row) :
					                                    $CUST_CODE1	= $row->CUST_CODE;
					                                    $CUST_DESC1	= $row->CUST_DESC;
					                                    ?>
					                                        <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
					                                    <?php
					                                endforeach;
					                            }
					                            else
					                            {
					                                ?>
					                                    <option value="">--- No Data Found ---</option>
					                                <?php
					                            }
					                            ?>
					                        </select>
					                    </div>
					                </div>

				                <!-- SN_NUM -->
					            	<div class="form-group" style="display: none;">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ShipmentNo; ?></label>
					                    <div class="col-sm-9">
					                        <select name="SN_NUM" id="SN_NUM" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ShipmentNo; ?>">
					                        <?php
												if($task == 'edit')
												{
													$sqlSN	= "SELECT SN_NUM, SN_CODE, DATE_FORMAT(SN_DATE,'%d %m %Y') AS SN_DATE, SO_CODE FROM tbl_sn_header 
																WHERE PRJCODE  = '$PRJCODE' AND CUST_CODE = '$CUST_CODE'";
													$resSN	= $this->db->query($sqlSN)->result();
													foreach($resSN as $rowSN):
														$SN_NUM1	= $rowSN->SN_NUM;
														$SN_CODE1	= $rowSN->SN_CODE;
														$SN_DATE1	= $rowSN->SO_CODE;
														$SO_CODE1	= $rowSN->SO_CODE;
														?>
															<option value="<?php echo $SN_NUM1; ?>" <?php if($SN_NUM1 == $SN_NUM) { ?> selected <?php } ?>><?php echo "$SN_CODE1&nbsp;&nbsp;|&nbsp;&nbsp;$SN_DATE1&nbsp;&nbsp;|&nbsp;&nbsp;$SO_CODE1"; ?></option>
														<?php
													endforeach;
												}
											?>
					                        </select>
					                    </div>
					                </div>
					                <?php
					                    $selSNCODE	= site_url('c_sales/c_r3tu7N/g3t4llSn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					                    $selSNCODE1	= site_url('c_sales/c_r3tu7N/getSNLIST/');
					                ?>
					                <script>
					                    var url1 = "<?php echo $selSNCODE;?>";
					                    function pleaseCheck()
					                    {
					                        var CUSTCODE	= $("#CUST_CODE").val();
					                        if(CUSTCODE == '')
					                        {
					                            swal('<?php echo $selCust; ?>',
				                            	{
				                            		icon: "warning",
				                            	});
					                            $("#CUST_CODE").focus();
					                            return false;
					                        }
					                        
					                        title = 'Select Item';
					                        w = 1000;
					                        h = 550;
					                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
					                        var left = (screen.width/2)-(w/2);
					                        var top = (screen.height/2)-(h/2);
					                        return window.open(url1+'&CST='+CUSTCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
					                    }
					                </script>

				                <!-- SR_TYPE -->
					                <div class="form-group">
					                  <label for="inputName" class="col-sm-3 control-label"><?php echo $Reason ?> </label>
					                    <div class="col-sm-9">
					                        <select name="SR_TYPE" id="SR_TYPE" class="form-control select2">
					                            <option value="0"> --- </option>
					                            <option value="1" <?php if($SR_TYPE == 1) { ?> selected <?php } ?>>Rusak</option>
					                            <option value="2" <?php if($SR_TYPE == 2) { ?> selected <?php } ?>>Tidak Sesuai</option>
					                        </select>
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
				                <!-- SR_NOTES -->
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
					                    <div class="col-sm-9">
					                        <textarea class="form-control" name="SR_NOTES"  id="SR_NOTES" style="height:85px"><?php echo $SR_NOTES; ?></textarea>
					                        <input type="hidden" name="SR_TOTCOST" id="SR_TOTCOST" value="<?php echo $SR_TOTCOST; ?>">
					                    </div>
			                		</div>

				                <!-- SR_TOTVOLM -->
					                <div class="form-group">
					                  	<label for="inputName" class="col-sm-3 control-label">Total (Qty) </label>
					                  	<div class="col-sm-9">
					                        <input type="text" class="form-control" name="SR_TOTVOLM" id="SR_TOTVOLM" value="<?php echo $SR_TOTVOLM; ?>" style="display: none;" >
					                    	<input type="text" class="form-control" name="SR_TOTVOLM1" id="SR_TOTVOLM1" value="<?php echo number_format($SR_TOTVOLM, 2); ?>" readonly >
					                  	</div>
					                </div>

				                <!-- SR_STAT -->
					                <div class="form-group" >
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
					                    <div class="col-sm-9">
					                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $SR_STAT; ?>">
					                       	<?php
						                        $isDisabled = 1;
						                        if($SR_STAT == 1 || $SR_STAT == 4)
						                        {
						                            $isDisabled = 0;
						                        }
						                    ?>
						                    <select name="SR_STAT" id="SR_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
						                        <?php
						                            $disableBtn	= 0;
						                            if($SR_STAT == 5 || $SR_STAT == 6 || $SR_STAT == 9)
						                            {
						                                $disableBtn	= 1;
						                            }
						                            if($SR_STAT != 1 AND $SR_STAT != 4) 
						                            {
						                                ?>
						                                    <option value="1"<?php if($SR_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
						                                    <option value="2"<?php if($SR_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
						                                    <option value="3"<?php if($SR_STAT == 3) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Approve</option>
						                                    <option value="4"<?php if($SR_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
						                                    <option value="5"<?php if($SR_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
						                                    <option value="6"<?php if($SR_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
						                                    <option value="7"<?php if($SR_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
						                                    <option value="9"<?php if($SR_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
						                                <?php
						                            }
						                            else
						                            {
						                                ?>
						                                    <option value="1"<?php if($SR_STAT == 1) { ?> selected <?php } ?>>New</option>
						                                    <option value="2"<?php if($SR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
						                                <?php
						                            }
						                        ?>
						                    </select>
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
					                </div>
					        </div>
					    </div>
					</div>	
	                <?php
						$AddItm	= site_url('c_sales/c_r3tu7N/s3l4llit3m/?id=');
	                    $AddItm	= site_url('c_sales/c_r3tu7N/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					?>
                    <div class="col-md-12">
		                <div class="form-group" <?php if($SR_STAT != 1 OR $SR_STAT != 4) { ?> style="display:none" <?php } ?>>
		                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                        <script>
		                            var url = "<?php echo $AddItm;?>";
		                            function selectitem()
		                            {
		                                var	PRJCODE		= '<?php echo $PRJCODE; ?>';
										var	CUST_CODE	= document.getElementById('CUST_CODE').value;
										var	SN_NUM		= document.getElementById('SN_NUM').value;
										var dataColl	= PRJCODE+'~'+CUST_CODE+'~'+SN_NUM;
										
		                                if(CUST_CODE == '')
		                                {
		                                    swal('<?php echo $selCust; ?>',
			                            	{
			                            		icon: "warning",
			                            	});
		                                    document.getElementById('CUST_CODE').focus();
		                                    return false;
		                                }
										
		                                if(SN_NUM == 0)
		                                {
		                                    swal('<?php echo $selSNNo; ?>',
			                            	{
			                            		icon: "warning",
			                            	});
		                                    document.getElementById('SN_NUM').focus();
		                                    return false;
		                                }
		                                
		                                title = 'Select Item';
		                                w = 1000;
		                                h = 550;
		                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                var left = (screen.width/2)-(w/2);
		                                var top = (screen.height/2)-(h/2);
		                                return window.open(url+'&d4t30ll='+dataColl, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                            }
		                        </script>
		                        <button class="btn btn-success" type="button" onClick="selectitem();">
		                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
		                        </button>
		                	</div>
		                </div>
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                        	<?php
		                        	$collDATA		= "$PRJCODE";
									$url_qrlist		= site_url('c_sales/c_r3tu7N/s3l4llQRC/?id='.$this->url_encryption_helper->encode_url($collDATA));
									if($SR_STAT == 1 | $SR_STAT == 4)
									{
		                        		?>
				                        <div class="form-group" style="display: none;">
				                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                            <div class="col-sm-9">
				                                <script>
				                                    var urlQR = "<?php echo $url_qrlist;?>";
				                                    function selectqr()
				                                    {
														title = 'Select QRC';
				                                        w = 1000;
				                                        h = 550;
				                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                                        var left = (screen.width/2)-(w/2);
				                                        var top = (screen.height/2)-(h/2);
				                                        return window.open(urlQR, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                                    }
				                                </script>
				                                <button class="btn btn-warning" type="button" onClick="selectqr();">
				                                <i class='glyphicon glyphicon-qrcode'></i>&nbsp;&nbsp;QR
				                                </button>
				                        	</div>
				                        </div>
					                    <?php 
					                }
					                if($SR_STAT == 1 || $SR_STAT == 4)
					                {
				            			?>
				                			<div style="text-align: center; font-weight: bold;"><?php echo $shiplist; ?> [ <a href="javascript:selectqr();"><i class='glyphicon glyphicon-qrcode'></i></a> ]</div>
		                				<?php
		                			} 
		                		?>
	                            <table id="tblQRC" class="table table-bordered table-striped" width="100%">
	                                <tr style="background:#CCCCCC">
	                                    <th width="2%" height="25" style="text-align:center">No.</th>
	                                  	<th width="10%" style="text-align:center" nowrap>No. Roll </th>
	                                  	<th width="10%" style="text-align:center" nowrap><?php echo $ShipmentNo; ?> </th>
	                                  	<th width="5%" style="text-align:center" nowrap><?php echo $Date; ?> </th>
	                                  	<th width="25%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
	                                  	<th width="8%" style="text-align:center" nowrap>Qty </th>
	                                  	<th width="5%" style="text-align:center" nowrap>Unit</th>
	                                  	<th width="15%" style="text-align:center" nowrap><?php echo $Notes; ?></th>
	                              	</tr>
	                                <?php
	                                    $resSNC		= 0;
	                                    $currRow 	= 0;
	                                    if($task == 'edit')
	                                    {
	                                        $sqlSR	= "SELECT A.*, B.ITM_NAME
	                                                    FROM tbl_sr_detail_qrc A
	                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
	                                                    WHERE 
	                                                        A.SR_NUM = '$SR_NUM' 
	                                                        AND A.PRJCODE = '$PRJCODE'";
	                                        $resSR = $this->db->query($sqlSR)->result();

	                                        // count data
	                                        $sqlSRC	= "tbl_sr_detail_qrc A
	                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
	                                                    WHERE 
	                                                        A.SR_NUM = '$SR_NUM' 
	                                                        AND A.PRJCODE = '$PRJCODE'";
	                                        $resSRC = $this->db->count_all($sqlSRC);
	                                        
		                                    $i			= 0;
		                                    $j			= 0;
		                                    if($resSRC > 0)
		                                    {
		                                        $GT_ITMPRICE		= 0;
		                                        foreach($resSR as $rowSR) :
		                                            $currRow  		= ++$i;
		                                            $SN_NUM 		= $rowSR->SN_NUM;
		                                            $SN_CODE 		= $rowSR->SN_CODE;
		                                            $SN_DATE 		= date('d-m-Y', strtotime($rowSR->SN_DATE));
		                                            $SO_NUM 		= $rowSR->SO_NUM;
		                                            $SO_CODE 		= $rowSR->SO_CODE;
		                                            $JO_NUM 		= $rowSR->JO_NUM;
		                                            $JO_CODE 		= $rowSR->JO_CODE;
		                                            $ITM_CODE 		= $rowSR->ITM_CODE;
		                                            $ITM_NAME 		= $rowSR->ITM_NAME;
		                                            $ITM_UNIT 		= $rowSR->ITM_UNIT;
		                                            $QRC_NUM 		= $rowSR->QRC_NUM;
		                                            $QRC_CODEV 		= $rowSR->QRC_CODEV;
		                                            $QRC_VOLM 		= $rowSR->QRC_VOLM;
		                                            $QRC_VOLM_RET 	= $rowSR->QRC_VOLM_RET;
		                                            $QRC_PRICE 		= $rowSR->QRC_PRICE;
		                                            $QRC_NOTE 		= $rowSR->QRC_NOTE;
		                                
		                                            if ($j==1) {
		                                                echo "<tr class=zebra1>";
		                                                $j++;
		                                            } else {
		                                                echo "<tr class=zebra2>";
		                                                $j--;
		                                            }
			                                    	?>
				                                        <tr id="trQRC_<?php echo $currRow; ?>">
				                                            <!-- NO URUT -->
				                                            <td width="2%" height="25" style="text-align: center;">
				                                                <?php
				                                                    if($SR_STAT == 1 || $SR_STAT == 4)
				                                                    {
				                                                        ?>
				                                                            <a href="#" onClick="deleteRow(<?php echo $currRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
				                                                        <?php
				                                                    }
				                                                    else
				                                                    {
				                                                        echo "$currRow.";
				                                                    }
				                                                ?>
				                                                <input style="display:none" type="Checkbox" id="dataQRC[<?php echo $currRow; ?>][chk1]" name="dataQRC[<?php echo $currRow; ?>][chk1]" value="<?php echo $currRow; ?>" onClick="pickThis(this,<?php echo $currRow; ?>)">
				                                                <input type="hidden" id="chk1" name="chk1" value="<?php echo $currRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;"> 
				                                            </td>

				                                            <!-- NO. ROLL : ITEM CODE, QRC_NUM, QRC_CODEV -->
				                                            <td width="10%" style="text-align:left" nowrap>
				                                                <?php print $QRC_CODEV; ?>
				                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_NUM" name="dataQRC[<?php echo $currRow; ?>][QRC_NUM]" value="<?php echo $QRC_NUM; ?>" width="10" size="15">
				                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_CODEV" name="dataQRC[<?php echo $currRow; ?>][QRC_CODEV]" value="<?php echo $QRC_CODEV; ?>" width="10" size="15">
				                                            </td>
				                                            
				                                            <!-- SN_CODE, JO_NUM, JO_CODE -->  
				                                            <td width="10%" style="text-align:center;">
				                                            	<?php echo $SN_CODE; ?>
				                                                 <input type="hidden" id="dataQRC<?php echo $currRow; ?>JO_NUM" name="dataQRC[<?php echo $currRow; ?>][JO_NUM]" value="<?php print $JO_NUM; ?>">
				                                                 <input type="hidden" id="dataQRC<?php echo $currRow; ?>JO_CODE" name="dataQRC[<?php echo $currRow; ?>][JO_CODE]" value="<?php print $JO_CODE; ?>">
				                                                 <input type="hidden" id="dataQRC<?php echo $currRow; ?>SN_NUM" name="dataQRC[<?php echo $currRow; ?>][SN_NUM]" value="<?php print $SN_NUM; ?>">
				                                                 <input type="hidden" id="dataQRC<?php echo $currRow; ?>SN_CODE" name="dataQRC[<?php echo $currRow; ?>][SN_CODE]" value="<?php print $SN_CODE; ?>">
				                                            </td>
				                                            
				                                            <!-- SN_DATE -->
				                                            <td width="5%" style="text-align:left" nowrap>
				                                            	<?php echo $SN_DATE; ?>
				                                            </td>
				                                            
				                                            <!-- ITEM NAME -->
				                                            <td width="25%" style="text-align:left">
				                                            	<?php echo $ITM_NAME; ?>
				                                            	<input type="hidden" id="dataQRC<?php echo $currRow; ?>ITM_CODE" name="dataQRC[<?php echo $currRow; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>">
				                                            </td>
				                                            
				                                            <!-- QRC_VOLM -->  
				                                            <td width="8%" style="text-align:right">
				                                            	<?php
				                                                    if($SR_STAT == 1 || $SR_STAT == 4)
				                                                    {
				                                                        ?>
							                                                <input type="text" name="QRC_VOLM_RET<?php echo $currRow; ?>" id="QRC_VOLM_RET<?php echo $currRow; ?>" value="<?php echo number_format($QRC_VOLM_RET, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolm(this,<?php echo $currRow; ?>);" >
							                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_VOLM_RET" name="dataQRC[<?php echo $currRow; ?>][QRC_VOLM_RET]" value="<?php print $QRC_VOLM_RET; ?>">
							                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_VOLM" name="dataQRC[<?php echo $currRow; ?>][QRC_VOLM]" value="<?php print $QRC_VOLM; ?>">
				                                            			<?php 
				                                            		} else 
				                                            		{
				                                            			echo number_format($QRC_VOLM_RET, 2);
				                                                        ?>
							                                                <input type="hidden" name="QRC_VOLM_RET<?php echo $currRow; ?>" id="QRC_VOLM_RET<?php echo $currRow; ?>" value="<?php echo number_format($QRC_VOLM_RET, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolm(this,<?php echo $currRow; ?>);" >
							                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_VOLM_RET" name="dataQRC[<?php echo $currRow; ?>][QRC_VOLM_RET]" value="<?php print $QRC_VOLM_RET; ?>">
							                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_VOLM" name="dataQRC[<?php echo $currRow; ?>][QRC_VOLM]" value="<?php print $QRC_VOLM; ?>">
				                                            			<?php 
				                                            		}
				                                            	?>
				                                            </td>
				                                            
				                                            <!-- ITM_UNIT -->  
				                                            <td width="5%" style="text-align:center;">
				                                            	<?php echo $ITM_UNIT; ?>
				                                                <input type="hidden" id="dataQRC<?php echo $currRow; ?>ITM_UNIT" name="dataQRC[<?php echo $currRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
				                                            </td>
				                                            
				                                            <!-- QRC_NOTE -->  
				                                            <td width="5%" style="text-align:left;">
				                                            	<?php
																	if($SR_STAT == 1)
																	{
																	    ?>
																	        <input type="text" class="form-control" id="dataQRC<?php echo $currRow; ?>QRC_NOTE" name="dataQRC[<?php echo $currRow; ?>][QRC_NOTE]" value="<?php print $QRC_NOTE; ?>">
																	    <?php
																	}
																	else
																	{
																	    echo $QRC_NOTE;
																	    ?>
																	        <input type="hidden" id="dataQRC<?php echo $currRow; ?>QRC_NOTE" name="dataQRC[<?php echo $currRow; ?>][QRC_NOTE]" value="<?php print $QRC_NOTE; ?>">
																	    <?php
																	}
				                                            	 ?>
				                                            </td>
				                                        </tr>
			                                    	<?php
			                                    endforeach;
		                                    }
		                                }
	                                ?>
	                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currRow; ?>">
	                            </table>
		                    </div>
		                </div>
		                <br>
		                <div class="form-group">
		                    <div class="col-sm-offset-2 col-sm-10">
		                        <?php
		                        	if($task=='add')
		                            {
		                                if(($SR_STAT == 1 || $SR_STAT == 4) && $ISCREATE == 1)
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
		                                if(($SR_STAT == 1 || $SR_STAT == 4) && $ISCREATE == 1)
		                                {
		                                    ?>
		                                        <button class="btn btn-primary" >
		                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
		                                        </button>&nbsp;
		                                    <?php
		                                }
		                                elseif($SR_STAT == 3)
		                                {
		                                    ?>
		                                        <button class="btn btn-primary" id="btnREJECT" style="display:none" >
		                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
		                                        </button>&nbsp;
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
		                            $backURL	= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
		                        ?>
		                    </div>
		                </div>
					</form>
					<?php
                        $DOC_NUM	= $SR_NUM;
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
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('#datepicker').datepicker({
      autoclose: true,
	  endDate: '+1d'
    });
	
	//Date picker
	$('#datepicker1').datepicker({
	  autoclose: true,
	  startDate: '+0d'
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
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		if(validateDouble(arrItem[0]))
		{
			swal("Double Item for " + arrItem[3],
				{
					icon:"error",
				});
			return;
		}

		ICOLLQTYTOT		= document.getElementById('SR_TOTVOLM').value;

		QRC_NUM 		= arrItem[0];
		JO_NUM			= arrItem[1];
		JO_CODE			= arrItem[2];
		QRC_CODEV 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_UNIT 		= arrItem[6];
		QRC_VOLM 		= arrItem[7];
		QRC_PRICE 		= arrItem[8];
		SN_NUM 			= arrItem[9];
		SN_CODE 		= arrItem[10];
		SN_DATE 		= arrItem[11];
		CUST_CODE 		= arrItem[12];
		BOM_NAME 		= arrItem[13];
		BOM_NAME 		= arrItem[13];
		QRC_VOLM_RET 	= arrItem[14];
		QRC_VOLMV 		= doDecimalFormat(RoundNDecimal(QRC_VOLM, 2));
		QRC_VOLM_RETV	= doDecimalFormat(RoundNDecimal(QRC_VOLM_RET, 2));
		SNQTYTOT2		= parseFloat(ICOLLQTYTOT) + parseFloat(QRC_VOLM);
		document.getElementById('SR_TOTVOLM').value 	= SNQTYTOT2;
		document.getElementById('SR_TOTVOLM1').value 	= doDecimalFormat(RoundNDecimal(SNQTYTOT2, 2));

		$('#CUST_CODE').val(CUST_CODE).trigger('change');

		ITM_CODE1		= ITM_CODE+' '+QRC_NUM;
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tblQRC');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'trQRC_' + intIndex;
		
		// CHECKBOX
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk1]" name="data['+intIndex+'][chk1]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="hidden" id="chk1" name="chk1" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';

		// QRC_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+QRC_CODEV+'<input type="hidden" id="dataQRC'+intIndex+'QRC_NUM" name="dataQRC['+intIndex+'][QRC_NUM]" value="'+QRC_NUM+'" width="10" size="15"><input type="hidden" id="dataQRC'+intIndex+'QRC_CODEV" name="dataQRC['+intIndex+'][QRC_CODEV]" value="'+QRC_CODEV+'" width="10" size="15"><input type="hidden" id="dataQRC'+intIndex+'SN_NUM" name="dataQRC['+intIndex+'][SN_NUM]" value="'+SN_NUM+'" width="10" size="15"><input type="hidden" id="dataQRC'+intIndex+'SN_CODE" name="dataQRC['+intIndex+'][SN_CODE]" value="'+SN_CODE+'" width="10" size="15">';
		
		// SN_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+SN_CODE+'<input type="hidden" id="dataQRC'+intIndex+'JO_NUM" name="dataQRC['+intIndex+'][JO_NUM]" value="'+JO_NUM+'" width="10" size="15"><input type="hidden" id="dataQRC'+intIndex+'JO_CODE" name="dataQRC['+intIndex+'][JO_CODE]" value="'+JO_CODE+'" width="10" size="15">';
		
		// QRC DATE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = SN_DATE;
		
		// ITM_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_NAME+' : '+BOM_NAME+'<input type="hidden" id="dataQRC'+intIndex+'ITM_CODE" name="dataQRC['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15">';
		
		// ITM_NAME
		/*objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';*/
		
		// QRC QTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" name="QRC_VOLM_RET'+intIndex+'" id="QRC_VOLM_RET'+intIndex+'" value="'+QRC_VOLM_RETV+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolm(this,'+intIndex+');" ><input type="hidden" id="dataQRC'+intIndex+'QRC_VOLM_RET" name="dataQRC['+intIndex+'][QRC_VOLM_RET]" value="'+QRC_VOLM_RET+'"><input type="hidden" id="dataQRC'+intIndex+'QRC_VOLM" name="dataQRC['+intIndex+'][QRC_VOLM]" value="'+QRC_VOLM+'">';
		
		// ITM UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataQRC'+intIndex+'ITM_UNIT" name="dataQRC['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// QRC NOTES
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" name="dataQRC['+intIndex+'][QRC_NOTE]" id="dataQRC'+intIndex+'QRC_NOTE" value="">';
				
		document.getElementById('totalrow').value = intIndex;
	}

	function chgVolm(thisVal1, row)
	{
		thisVal 	= eval(thisVal1).value.split(",").join("");
		val_ori 	= parseFloat(document.getElementById('dataQRC'+row+'QRC_VOLM').value);
		max_qty 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(val_ori)), 2));
		if(thisVal > val_ori)
		{
			swal('<?php echo $maxQty; ?>'+max_qty,
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('QRC_VOLM_RET'+row).value 			= max_qty;
				document.getElementById('dataQRC'+row+'QRC_VOLM_RET').value = val_ori;
				document.getElementById('QRC_VOLM_RET'+row).focus();
			})
			return false;
		}
		document.getElementById('QRC_VOLM_RET'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
		document.getElementById('dataQRC'+row+'QRC_VOLM_RET').value = thisVal;
	}
	
	function validateDouble(vcode) 
	{
		var thechk=new Array();
		var duplicate = false;
		
		var jumchk = document.getElementsByName('chk1').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk1');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk1');
			panjang = 0;
		}
		var panjang = panjang + 1;

		for (var i=0;i<panjang;i++) 
		{
			var temp = 'trQRC_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1 = document.getElementById('dataQRC'+i+'QRC_NUM').value;
				if (elitem1 == vcode)
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
	
	function deleteRow(btn)
	{
		var row = document.getElementById("trQRC_" + btn);
		row.remove();
	}
	
	function checkInp()
	{
		totRow	= document.getElementById('totalrow').value;

		CUST_CODE	= document.getElementById('CUST_CODE').value;
		
		if(CUST_CODE == 0)
		{
			swal("<?php echo $selCust; ?>",
			{
				icon: "warning",
			});
			document.getElementById('CUST_CODE').focus();
			return false;
		}
		
		SR_TYPE	= document.getElementById('SR_TYPE').value;
		if(SR_TYPE == 0)
		{
			swal("<?php echo $ReturnType; ?>",
			{
				icon: "warning",
			});
			document.getElementById('SR_TYPE').focus();
			return false;
		}
		
		SR_NOTES	= document.getElementById('SR_NOTES').value;
		if(SR_NOTES == '')
		{
			swal("<?php echo $docNotes; ?>",
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('SR_NOTES').focus();
			});
			return false;
		}

		if(totRow == 0)
		{
			swal("<?php echo $noMtrSent; ?>",
			{
				icon: "warning",
			});
			return false;
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