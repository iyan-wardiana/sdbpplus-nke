<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= v_so_form.php
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

$sql 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$tblName	= "tbl_so_header";

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	
	$SO_NUM			= '';
	$OFF_NUM			= '';
	$SO_CODE 		= '';
	$SO_TYPE 		= 1; // Internal
	$SO_CAT			= 1;
	$SO_DATE		= '';
	$PRJNAME 		= '';
	$CUST_CODE 		= '0';
	$CUST_DESC 		= '';
	$CUST_ADD1 		= '';
	$SO_CURR 		= 'IDR';
	$SO_CURRATE		= 1;
	$SO_TAXCURR 	= 'IDR';
	$SO_TAXRATE 	= 1;
	$SO_TOTCOST		= 0;
	$DP_CODE		= '';
	$DP_JUML		= 0;
	$SO_PAYTYPE 	= 0;
	$SO_TENOR 		= 0;
	$SO_STAT 		= 1;
	$SO_INVSTAT		= 0;					
	$SO_NOTES		= '';
	$SO_MEMO 		= '';
	
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
	
	$yearC 	= (int)$Pattern_YearAktive;
	$year 	= substr($Pattern_YearAktive,2,2);
	$month 	= (int)$Pattern_MonthAktive;
	$date 	= (int)$Pattern_DateAktive;
	
	$sql 	= "$tblName WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$myMax 	= $this->db->count_all($sql);
	$myMax	= $myMax + 1;
	
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
	
		
	$lastPattNumb 	= $myMax;
	$lastPattNumb1 	= $myMax;
	$len = strlen($lastPattNumb);
	
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
	$lastPattNumb = $nol.$lastPattNumb;
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber1		= "$Pattern_Code$PRJCODE$year$month$days-$lastPattNumb";
	//$DocNumber	= "$DocNumber1"."-D";
	$DocNumber		= "$DocNumber1";	
	//$DOCCODE		= substr($lastPattNumb, -4);
	$DOCCODE		= $lastPattNumb;	
	$DOCYEAR		= date('y');
	$DOCMONTH		= date('m');
	//$DOC_CODE		= "$Pattern_Code.$DOCCODE.$DOCYEAR.$DOCMONTH-D"; // MANUAL CODE
	$DOC_NUM		= $DocNumber;
	$DOC_CODE		= "$Pattern_Code.$DOCCODE.$DOCYEAR.$DOCMONTH"; // MANUAL CODE
	
	$SO_NUM			= $DOC_NUM;
	$SO_CODE		= $DOC_CODE;
	$SO_DATE 		= date('m/d/Y');
	$ETD			= $SO_DATE;
	$SO_PRODD		= date('m/d/Y');
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	$OFF_NUMX		= '';
	
	$SO_RECEIVLOC	= '';
	$SO_RECEIVCP	= '';
	$SO_SENTROLES	= '';
	$SO_REFRENS		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_so_header~$Pattern_Length";
	$dataTarget		= "SO_CODE";
	$CUST_ADDRESS	= '';
	$SO_NOTES1		= '';
	$OFF_NUMX		= '';
	$CUST_CODE		= '';
	$CUST_ADDRESS	= '';
	
	if(isset($_POST['OFF_NUMX']))
	{
		$OFF_NUMX		= $_POST['OFF_NUMX'];
		$CUST_CODE		= $_POST['CUST_CODEX'];
		$CUST_ADDRESS	= $_POST['CUST_ADDX'];

		$sqlCUST		= "SELECT CUST_TOP, CUST_TOPD, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
		$resCUST 		= $this->db->query($sqlCUST)->result();
		foreach($resCUST as $row) :
			$SO_PAYTYPE = $row->CUST_TOP;
			$SO_TENOR 	= $row->CUST_TOPD;
		endforeach;
	}
	$OFF_CODE	= '';
	$sqlOFFH	= "SELECT OFF_CODE FROM tbl_offering_h WHERE OFF_NUM = '$OFF_NUMX' AND PRJCODE = '$PRJCODE' LIMIT 1";
	$resOFFH 	= $this->db->query($sqlOFFH)->result();
	foreach($resOFFH as $row1):
		$OFF_CODE	= $row1->OFF_CODE;
	endforeach;
}
else
{
	$isSetDocNo = 1;
	$SO_NUM 		= $default['SO_NUM'];
	$DocNumber		= $SO_NUM;
	$SO_CODE 		= $default['SO_CODE'];
	$SO_TYPE 		= $default['SO_TYPE'];
	$SO_CAT 		= $default['SO_CAT'];
	$SO_DATE 		= $default['SO_DATE'];
	$SO_DATE		= date('m/d/Y', strtotime($SO_DATE));
	$SO_DUED 		= $default['SO_DUED'];
	$SO_DUED		= date('m/d/Y', strtotime($SO_DUED));
	$SO_PRODD 		= $default['SO_PRODD'];
	$SO_PRODD		= date('m/d/Y', strtotime($SO_PRODD));
	$PRJCODE 		= $default['PRJCODE'];
	$DEPCODE 		= $default['DEPCODE'];
	if($DEPCODE == '')
		$DEPCODE 	= $DEPCODEX;
	$CUST_CODE 		= $default['CUST_CODE'];
	$CUST_ADDRESS	= $default['CUST_ADDRESS'];
	$OFF_NUM		= $default['OFF_NUM'];
	$OFF_NUMX		= $default['OFF_NUM'];
	$OFF_CODE		= $default['OFF_CODE'];
	$SO_CURR 		= $default['SO_CURR'];
	$SO_CURRATE 	= $default['SO_CURRATE'];
	$SO_TOTCOST 	= $default['SO_TOTCOST'];
	$SO_TOTPPN 		= $default['SO_TOTPPN'];
	$SO_PAYTYPE 	= $default['SO_PAYTYPE'];
	$SO_TENOR 		= $default['SO_TENOR'];
	$SO_NOTES 		= $default['SO_NOTES'];
	$SO_NOTES1 		= $default['SO_NOTES1'];
	$SO_MEMO 		= $default['SO_MEMO'];
	$PRJNAME 		= $default['PRJNAME'];
	$SO_STAT 		= $default['SO_STAT'];
	$SO_REFRENS 	= $default['SO_REFRENS'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number 	= $default['Patt_Number'];
	$lastPattNumb1 = $default['Patt_Number'];
	
	$SO_TAXRATE			= 1;
	$totTaxPPnAmount	= 1;
	$totTaxPPhAmount	= 1;
}
$sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 	= $this->db->query($sqlPRJ)->result();

foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;
//echo $OFF_NUMX;
$secGenCode	= base_url().'index.php/c_sales/c_s4l350r4/genCode/'; // Generate Code

if(isset($_POST['SO_CATX']))
{
	$SO_CAT	= $_POST['SO_CATX'];
}
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
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Source')$Source = $LangTransl;
			if($TranslCode == 'QuotNo')$QuotNo = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'offer')$offer = $LangTransl;
			
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
			if($TranslCode == 'Product')$Product = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'custPONo')$custPONo = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'qtySOEmpty')$qtySOEmpty = $LangTransl;
			if($TranslCode == 'selCust')$selCust = $LangTransl;
			if($TranslCode == 'quotNoEmpty')$quotNoEmpty = $LangTransl;
			if($TranslCode == 'chkManCode')$chkManCode = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;

			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// SO_STAT - SO_TOTCOST
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode1' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode1'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP	= $rowAPP->MAX_STEP;
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
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode1'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode1' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode1'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$SO_STAT'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				//$appReady	= $APP_STEP;
				//if($resC_App == 0)
				//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
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
				$APPROVE_AMOUNT 	= $SO_TOTCOST;
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
	?>
	
	<body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/salesorder.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
				    <small><?php echo $PRJNAME1; ?></small>  </h1>
				  <?php /*?><ol class="breadcrumb">
				    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				    <li><a href="#">Tables</a></li>
				    <li class="active">Data tables</li>
				  </ol><?php */?>
			</section>

			<section class="content">
			    <div class="row">
                    <form name="frmsrchx" id="frmsrchx" action="" method=POST style="display: none;">
                        <input type="text" name="SO_CATX" id="SO_CATX" class="textbox" value="<?php echo $SO_CAT; ?>" />
                        <input type="submit" class="button_css" name="submitSrchx" id="submitSrchx" value=" search " />
                    </form>
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="OFF_NUMX" id="OFF_NUMX" class="textbox" value="<?php echo $OFF_NUMX; ?>" />
                        <input type="text" name="CUST_CODEX" id="CUST_CODEX" class="textbox" value="<?php echo $CUST_CODE; ?>" />
                        <input type="text" name="CUST_ADDX" id="CUST_ADDX" class="textbox" value="<?php echo $CUST_ADDRESS; ?>" />
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
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
						<div class="col-md-6">
							<div class="box box-primary">
								<div class="box-header with-border" style="display: none;">
									<i class="fa fa-cloud-upload"></i>
									<h3 class="box-title">&nbsp;</h3>
								</div>
								<div class="box-body">
			                    	<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
			                        <input type="hidden" name="SO_TYPE" id="SO_TYPE" value="<?php echo $SO_TYPE; ?>">
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
			                          	<label for="inputName" class="col-sm-3 control-label"><?php //echo $PONumber ?> </label>
			                          	<div class="col-sm-9">
			                                <input type="text" class="form-control" style="max-width:195px" name="SO_NUM1" id="SO_NUM1" value="<?php echo $DocNumber; ?>" disabled >
			                       			<input type="hidden" class="textbox" name="SO_NUM" id="SO_NUM" size="30" value="<?php echo $DocNumber; ?>" />
			                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPattNumb1; ?>">
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
			                          	<div class="col-sm-9">
			                            	<input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="SO_CODE" id="SO_CODE" value="<?php echo $SO_CODE; ?>" >
			                            	<input type="text" class="form-control" name="SO_CODEX" id="SO_CODEX" value="<?php echo $SO_CODE; ?>" disabled >
			                          	</div>
			                        </div>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
			                          	<div class="col-sm-9">
			                                <label>
			                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
			                                </label>
			                                <label style="font-style:italic">
			                                    <?php echo $chkManCode; ?>
			                                </label>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?>  / <?php echo $Source ?> </label>
			                          	<div class="col-sm-4">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <input type="text" name="SO_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $SO_DATE; ?>" style="width:107px">
			                                </div>
			                          	</div>
			                          	<div class="col-sm-5">
			                                <select name="SO_CAT" id="SO_CAT" class="form-control select2" onchange="chgCateg(this.value)">
			                                	<option value="1" <?php if($SO_CAT == 1) { ?> selected <?php } ?>> <?php echo $offer; ?> </option>
			                                	<option value="2" <?php if($SO_CAT == 2) { ?> selected <?php } ?>> Direct </option>
			                                </select>
			                          	</div>
			                        </div>
			                        <script type="text/javascript">
			                        	function chgCateg(SO_CAT) 
										{
											document.getElementById("SO_CATX").value = SO_CAT;
											document.frmsrchx.submitSrchx.click();
										}
			                        </script>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-9">
			                            	<select name="PRJCODE" id="PRJCODE" class="form-control" onChange="chooseProject()" disabled>
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
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $CustName ?> </label>
			                          	<div class="col-sm-9">
			                            	<select name="CUST_CODE" id="CUST_CODE" class="form-control select2" onChange="getTOP(this.value)">
			                                	<option value="" > --- </option>
			                                    <?php
			                                    $i 		= 0;
			                                    if($SO_CAT == 1)
			                                    {
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
														$sqlCUST	= "SELECT CUST_CODE, CUST_DESC, CUST_ADD1
																		FROM tbl_customer
																		WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
														$resCUST	= $this->db->query($sqlCUST)->result();
						
				                                        foreach($resCUST as $sqlCUST) :
				                                            $CUST_CODE1	= $sqlCUST->CUST_CODE;
				                                            $CUST_DESC1	= $sqlCUST->CUST_DESC;
				                                            ?>
				                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
				                                            <?php
				                                        endforeach;										
													}
				                                }
				                                else
				                                {
													$sqlCUST	= "SELECT CUST_CODE, CUST_DESC, CUST_ADD1
																	FROM tbl_customer WHERE CUST_STAT = '1'";
													$resCUST	= $this->db->query($sqlCUST)->result();
					
			                                        foreach($resCUST as $sqlCUST) :
			                                            $CUST_CODE1	= $sqlCUST->CUST_CODE;
			                                            $CUST_DESC1	= $sqlCUST->CUST_DESC;
			                                            ?>
			                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
			                                            <?php
			                                        endforeach;										
												}
			                                    ?>
			                                </select>
			                          	</div>
			                        </div>
			                        <script>
										function getTOP(CUST_CODE)
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
														var CUSTTOP1	= colData.split("~");
														var CUSTTOP		= CUSTTOP1[0];
														var CUSTTOPD	= CUSTTOP1[1];
														var CUSTADD1	= CUSTTOP1[2];
														document.getElementById('SO_PAYTYPE').value 	= CUSTTOP;
														document.getElementById('SO_PAYTYPEX').value 	= CUSTTOP;
														document.getElementById('SO_TENOR').value 		= CUSTTOPD;
														document.getElementById('SO_TENORX').value 		= CUSTTOPD;
														document.getElementById('CUST_ADDRESS').value 	= CUSTADD1;
													}
												}
											}
											xmlhttpTask.open("GET","<?php echo base_url().'index.php/c_sales/C_s4l350r4/getDetTOP/';?>"+CUST_CODE,true);
											xmlhttpTask.send();
										}
									</script>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $AdditAddress ?> </label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="CUST_ADDRESS"  id="CUST_ADDRESS" style="height:100px" placeholder="<?php echo $AdditAddress; ?>"><?php echo $CUST_ADDRESS; ?></textarea>
			                          	</div>
			                        </div>
			                        <div class="form-group" <?php if($SO_CAT == 2) { ?> style="display: none;" <?php } ?>>
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $QuotNo ?> </label>
			                          	<div class="col-sm-9">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="OFF_NUM" id="OFF_NUM" style="max-width:160px" value="<?php echo $OFF_NUMX; ?>" >
			                                    <input type="hidden" class="form-control" name="OFF_CODE" id="OFF_CODE" style="max-width:160px" value="<?php echo $OFF_CODE; ?>" >
			                                    <input type="text" class="form-control" name="OFF_NUM1" id="OFF_NUM1" value="<?php echo $OFF_CODE; ?>" onClick="pleaseCheck();" <?php if($SO_STAT != 1 && $SO_STAT != 4) { ?> disabled <?php } ?>>
			                                </div>
			                            </div>
			                        </div>
									<?php
										//$url_selAURCODE	= site_url('c_asset/c_asset_usage/popupallaur/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
										$url_selOFF	= site_url('c_sales/c_s4l350r4/all10ff3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <script>
										var url1 = "<?php echo $url_selOFF;?>";
										function pleaseCheck()
										{
											var CUSTCODE	= $("#CUST_CODE").val();
											if(CUSTCODE == '')
											{
												swal("<?php echo $selCust; ?>",
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
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Currency ?> </label>
			                          	<div class="col-sm-9">
			                            	<select name="SO_CURR" id="SO_CURR" class="form-control" style="max-width:75px">
			                                	<option value="IDR" <?php if($SO_CURR == 'IDR') { ?> selected <?php } ?>>IDR</option>
			                                	<option value="USD" <?php if($SO_CURR == 'USD') { ?> selected <?php } ?>>USD</option>    
			                                </select>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                       	  <label for="inputName" class="col-sm-3 control-label"><?php echo $PaymentType ?> </label>
			                          	<div class="col-sm-4">
		                            		<input type="hidden" class="form-control" style="max-width:300px;" name="SO_PAYTYPE" id="SO_PAYTYPE" size="30" value="<?php echo $SO_PAYTYPE; ?>" />
		                                    <select name="SO_PAYTYPEX" id="SO_PAYTYPEX" class="form-control" onChange="selSO_PAYTYPE(this.value)" disabled>
		                                        <option value="0" <?php if($SO_PAYTYPE == 0) { ?> selected="selected" <?php } ?>>Cash</option>
		                                        <option value="1" <?php if($SO_PAYTYPE == 1) { ?> selected="selected" <?php } ?>>Credit</option>
		                                    </select>
		                                </div>
			                          	<div class="col-sm-5">
		                            		<input type="hidden" class="form-control" name="SO_TENOR" id="SO_TENOR" size="30" value="<?php echo $SO_TENOR; ?>" />
		                                    <select name="SO_TENORX" id="SO_TENORX" class="form-control" onChange="selSO_TENOR(this.value)" disabled>
		                                        <option class="hide1" value="0" <?php if($SO_TENOR == 0) { ?> selected <?php } ?>>Cash</option>
		                                        <option value="7" <?php if($SO_TENOR == 7) { ?> selected <?php } ?>>7 Days</option>
		                                        <option value="15" <?php if($SO_TENOR == 15) { ?> selected <?php } ?>>15 Days</option>
		                                        <option value="30" <?php if($SO_TENOR == 30) { ?> selected <?php } ?>>30 Days</option>
		                                        <option value="45" <?php if($SO_TENOR == 45) { ?> selected <?php } ?>>45 Days</option>
		                                        <option value="60" <?php if($SO_TENOR == 60) { ?> selected <?php } ?>>60 Days</option>
		                                        <option value="75" <?php if($SO_TENOR == 75) { ?> selected <?php } ?>>75 Days</option>
		                                        <option value="90" <?php if($SO_TENOR == 90) { ?> selected <?php } ?>>90 Days</option>
		                                        <option value="120" <?php if($SO_TENOR == 120) { ?> selected <?php } ?>>120 Days</option>
		                                    </select>
			                        	</div>
			                        </div>
			                        <script>
										function selSO_PAYTYPE(theValue)
										{
											if(theValue == 1)
											{
												document.getElementById('SO_TENOR').value = 0;
												$("select span option").unwrap();
											}
											else
											{
												document.getElementById('SO_TENOR').value = 7;
												$("select>option.hide1").wrap('<span>');
											}
										}
										
										function selSO_TENOR(theValue)
										{
											if(theValue > 0)
											{
												document.getElementById('SO_PAYTYPE').value = 2;
											}
											else
											{
												document.getElementById('SO_PAYTYPE').value = 1;
											}
										}
									</script>
									<?php
										$url_AddItem	= site_url('c_sales/c_s4l350r4/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										$url_ItmSale	= site_url('c_sales/c_s4l350r4/s3l4llit3mSales/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									?>
			                        <div class="form-group" <?php if($SO_CAT == 1) { ?> style="display: none;" <?php } ?>>
			                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
			                          	<div class="col-sm-9">
		                                    <script>
		                                        var urlSales = "<?php echo $url_ItmSale;?>";
		                                        function selectitemSale()
		                                        {
		                                        	var CUSTCODE	= $("#CUST_CODE").val();
													if(CUSTCODE == '')
													{
														swal("<?php echo $selCust; ?>",
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
		                                            return window.open(urlSales, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                                        }
		                                    </script>
		                                    <button class="btn btn-warning" type="button" onClick="selectitemSale();" <?php if($SO_STAT != 1 && $SO_STAT != 4) { ?> disabled <?php } ?>>
		                                    	<i class="fa fa-cubes"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
		                                    </button>
		                           		</div>
			                        </div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="box box-warning">
								<div class="box-header with-border">
									<i class="fa fa-info-circle"></i>
									<h3 class="box-title"><?=$othInfo?></h3>
								</div>
								<div class="box-body">
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ProdPlan ?> </label>
			                          	<div class="col-sm-9">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <input type="text" name="SO_PRODD" class="form-control pull-left" id="datepicker2" value="<?php echo $SO_PRODD; ?>" style="width:107px">
			                                </div>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-3 control-label"><?php echo $custPONo; ?></label>
			                            <div class="col-sm-9">
			                                <input type="text" class="form-control" style="" name="SO_REFRENS" id="SO_REFRENS" size="30" value="<?php echo $SO_REFRENS; ?>" placeholder="<?php echo $custPONo; ?>" />
			                            </div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="SO_NOTES"  id="SO_NOTES" style="height:70px" placeholder="<?php echo $Notes; ?>"><?php echo $SO_NOTES; ?></textarea>
			                                <input type="hidden" name="SO_TOTCOST" id="SO_TOTCOST" value="<?php echo $SO_TOTCOST; ?>">
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes ?> </label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="SO_NOTES1"  id="SO_NOTES1" style="height:70px" placeholder="<?php echo $ApproverNotes; ?>" disabled><?php echo $SO_NOTES1; ?></textarea>
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
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $SO_STAT; ?>">
			                                <?php
												$isDisabled = 1;
												if($SO_STAT == 1 || $SO_STAT == 4)
												{
													$isDisabled = 0;
												}
											?>
			                                <select name="SO_STAT" id="SO_STAT" class="form-control select2" onChange="selStat(this.value)">
			                                    <?php
			                                    if($SO_STAT != 1 AND $SO_STAT != 4) 
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($SO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                            <option value="2"<?php if($SO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
			                                            <option value="3"<?php if($SO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
			                                            <option value="4"<?php if($SO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                            <option value="5"<?php if($SO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                            <option value="6"<?php if($SO_STAT == 6) { ?> selected <?php } ?>>Closed</option>
			                                            <option value="7"<?php if($SO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($SO_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                            <option value="2"<?php if($SO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                        <?php
			                                    }
			                                    ?>
			                                </select>
			                            </div>
			                        </div>
			                        <script type="text/javascript">
			                        	function selStat(statVal)
			                        	{
			                        		/*var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
			                        		if(STAT_BEFORE == 3 && statVal == 6)
			                        		{
			                        			document.getElementById('tblClose').style.display = '';
			                        		}
			                        		else
			                        		{
			                        			document.getElementById('tblClose').style.display = 'none';
			                        		}*/
			                        	}
			                        </script>
			                        <?php
									if($SO_STAT == 1 || $SO_STAT == 4) 
									{
										?>
			                       		<div class="form-group" style="display: none;">
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
			                                    <button class="btn btn-success" type="button" onClick="selectitem();">
			                                    	<i class="fa fa-cubes"></i>&nbsp;&nbsp;<?php echo $Product; ?>
			                                    </button>
			                           		</div>
			                            </div>
										<?php							
									} 
									?>
								</div>
							</div>
						</div>

                        <div class="col-md-12">
                            <div class="box box-primary">
		                        <div class="search-table-outter">
		                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="2%" style="text-align:center; vertical-align: middle;">No.</th>
	                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemCode; ?> </th>
	                                      	<th width="25%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemName; ?> </th>
	                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Quantity; ?> </th> 
	                              			<!-- Input Manual -->
	                                        <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Unit; ?> </th>
	                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $UnitPrice; ?> </th>
	                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?><br>
	                                      	(%)</th>
	                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?> </th>
	                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Tax; ?></th>
	                                      	<th width="13%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Price; ?></th>
	                                  	</tr>
	                                    <?php
											$resultC	= 0;
											if($task == 'add' && $OFF_NUMX != '')
											{
												$sqlDETPO	= "SELECT A.OFF_NUM, A.OFF_CODE, A.BOM_CODE, A.ITM_CODE, A.CCAL_NUM,
																	A.ITM_UNIT, A.OFF_VOLM AS SO_VOLM, A.OFF_PRICE AS SO_PRICE, 
																	A.OFF_DISCP AS SO_DISP, A.OFF_DISC AS SO_DISC,
																	A.OFF_TOTCOST AS SO_COST, A.OFF_DESC AS SO_DESC, A.OFF_DESC1 AS SO_DESC1,
																	A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
																	B.ITM_NAME, B.ITM_GROUP
																FROM tbl_offering_d A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE A.OFF_NUM = '$OFF_NUMX' 
																	AND B.PRJCODE = '$PRJCODE'";
												$result 	= $this->db->query($sqlDETPO)->result();
												
												$sqlDETC	= "tbl_offering_d A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE OFF_NUM = '$OFF_NUMX' 
																	AND B.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
											else
											{
												if($task == 'edit')
												{
													$sqlDET		= "SELECT A.*, B.ITM_NAME
																	FROM tbl_so_detail A
																		INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	WHERE 
																		A.SO_NUM = '$SO_NUM' 
																		AND B.PRJCODE = '$PRJCODE'";
													$result = $this->db->query($sqlDET)->result();
													// count data
													$sqlDETC	= "tbl_so_detail A
																		INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	WHERE 
																		A.SO_NUM = '$SO_NUM' 
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
													$currentRow  	= ++$i;
													$SO_NUM 		= $SO_NUM;
													$SO_CODE 		= $SO_CODE;
													$PRJCODE		= $PRJCODE;
													$ITM_CODE 		= $row->ITM_CODE;
													$ITM_NAME 		= $row->ITM_NAME;
													$ITM_UNIT 		= $row->ITM_UNIT;
													$SO_VOLM 		= $row->SO_VOLM;
													if($SO_VOLM == '')
														$SO_VOLM	= 0;
													$SO_PRICE 		= $row->SO_PRICE;												
													$SO_DISP 		= $row->SO_DISP;
													if($SO_DISP == '')
														$SO_DISP	= 0;
													$SO_DISC 		= $row->SO_DISC;
													if($SO_DISC == '')
														$SO_DISC	= 0;
													$SO_COST 		= $row->SO_COST;
													if($SO_COST == '')
														$SO_COST	= 0;
													$SO_DESC 		= $row->SO_DESC;
													$SO_DESC1 		= $row->SO_DESC1;
													$TAXCODE1 		= $row->TAXCODE1;
													$TAXPRICE1 		= $row->TAXPRICE1;

													$TAXPERC 		= 0;

													// COLOR NAME
													$CCAL_NUM 		= $row->CCAL_NUM;
													$CCAL_NAME 		= '';
													$sqlCOLNM 		= "SELECT CCAL_NAME FROM tbl_ccal_header WHERE CCAL_NUM = '$CCAL_NUM' LIMIT 1";
													$resCOLNM 		= $this->db->query($sqlCOLNM)->result();
													foreach($resCOLNM as $rowCOLNM) :
														$CCAL_NAME 	= $rowCOLNM->CCAL_NAME;
													endforeach;
										
													/*if ($j==1) {
														echo "<tr class=zebra1>";
														$j++;
													} else {
														echo "<tr class=zebra2>";
														$j--;
													}*/
												?>
	                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
	                                            <tr id="tr_<?php echo $currentRow; ?>">
													<!-- NO URUT -->
													<td style="text-align:center; vertical-align: middle;">
														<?php
	                                                        if($SO_STAT == 1)
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
	                                                <td style="text-align:left; vertical-align: middle;">
														<?php print $ITM_CODE; ?>
	                                                    <input type="hidden" id="data<?php echo $currentRow; ?>CCAL_NUM" name="data[<?php echo $currentRow; ?>][CCAL_NUM]" value="<?php echo $CCAL_NUM; ?>" width="10" size="15">
	                                                    <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
	                                                    <input type="hidden" id="data<?php echo $currentRow; ?>SO_NUM" name="data[<?php echo $currentRow; ?>][SO_NUM]" value="<?php echo $SO_NUM; ?>" width="10" size="15">
	                                                    <input type="hidden" id="data<?php echo $currentRow; ?>SO_CODE" name="data[<?php echo $currentRow; ?>][SO_CODE]" value="<?php echo $SO_CODE; ?>" width="10" size="15">
	                                                    <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15">
	                                                </td>
	                                                
	                                                <!-- ITEM NAME -->
													<td style="text-align:left; vertical-align: middle;">
														<?php echo $ITM_NAME; ?>
													  	<div style="margin-left: 15px; font-style: italic;">
													  		<i class='text-muted fa fa-rss'></i>&nbsp;&nbsp;<?php echo $CCAL_NAME; ?>
													  	</div>
													</td>
	                                                
												  	<!-- ITEM ORDER NOW -->  
												  	<td style="text-align:right; vertical-align: middle;">
			                                      		<?php if($SO_STAT == 1 || $SO_STAT == 4) { ?>
			                                          		<input type="text" class="form-control" style="max-width:200px; text-align:right" name="SO_VOLM<?php echo $currentRow; ?>" id="SO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($SO_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, <?php echo $currentRow; ?>);" >
			                                      		<?php } else { ?>
			                                      			<?php print number_format($SO_VOLM, $decFormat); ?>
			                                      			<input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="SO_VOLM<?php echo $currentRow; ?>" id="SO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($SO_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, <?php echo $currentRow; ?>);" >
			                                      		<?php } ?>
	                                                	
	                                                    <input type="hidden" id="data<?php echo $currentRow; ?>SO_VOLM" name="data[<?php echo $currentRow; ?>][SO_VOLM]" value="<?php echo $SO_VOLM; ?>">
	                                                </td>
	                                                    
	                                                <!-- ITEM UNIT -->
													<td style="text-align:center; vertical-align: middle;">
														<?php print $ITM_UNIT; ?>  
	                                                     <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
	                                                </td>
	                                                
	                                                <!-- ITEM PRICE -->
												  	<td style="text-align:right; vertical-align: middle;">
			                                      		<?php if($SO_STAT == 1 || $SO_STAT == 4) { ?>
			                                          		<input type="text" class="form-control" style="text-align:right; min-width:100px" name="SO_PRICE<?php echo $currentRow; ?>" id="SO_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($SO_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, <?php echo $currentRow; ?>);">
			                                      		<?php } else { ?>
			                                      			<?php print number_format($SO_PRICE, $decFormat); ?>
			                                      			<input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="SO_PRICE<?php echo $currentRow; ?>" id="SO_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($SO_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, <?php echo $currentRow; ?>);">
			                                      		<?php } ?>
	                                                	
	                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][SO_PRICE]" id="data<?php echo $currentRow; ?>SO_PRICE" size="6" value="<?php echo $SO_PRICE; ?>">
	                                                </td>
	                                                 
	                                                <!-- ITEM DISCOUNT PERCENTATION -->
												  	<td style="text-align:right; vertical-align: middle;">
			                                      		<?php if($SO_STAT == 1 || $SO_STAT == 4) { ?>
			                                          		<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="SO_DISP<?php echo $currentRow; ?>" id="SO_DISP<?php echo $currentRow; ?>" value="<?php print number_format($SO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" >
			                                      		<?php } else { ?>
			                                      			<?php print number_format($SO_DISP, $decFormat); ?>
			                                      			<input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="SO_DISP<?php echo $currentRow; ?>" id="SO_DISP<?php echo $currentRow; ?>" value="<?php print number_format($SO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" >
			                                      		<?php } ?>
														
														<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][SO_DISP]" id="data<?php echo $currentRow; ?>SO_DISP" value="<?php echo $SO_DISP; ?>">
	                                                </td>
														
													<!-- ITEM DISCOUNT -->
												  	<td style="text-align:right; vertical-align: middle;">
			                                      		<?php if($SO_STAT == 1 || $SO_STAT == 4) { ?>
			                                          		<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="SO_DISC<?php echo $currentRow; ?>" id="SO_DISC<?php echo $currentRow; ?>" value="<?php print number_format($SO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" >
			                                      		<?php } else { ?>
			                                      			<?php print number_format($SO_DISC, $decFormat); ?>
			                                      			<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="SO_DISC<?php echo $currentRow; ?>" id="SO_DISC<?php echo $currentRow; ?>" value="<?php print number_format($SO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" >
			                                      		<?php } ?>
														
														<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][SO_DISC]" id="data<?php echo $currentRow; ?>SO_DISC" value="<?php echo $SO_DISC; ?>">
	                                               	</td>
	                                                    
													<!-- ITEM TAX -->
												  	<td style="text-align:center; vertical-align: middle;">
                                                    	<?php
                                                    		if($SO_STAT == 1 || $SO_STAT == 4)
                                                    		{
                                                    			?>
			                                                      	<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control" id="data<?php echo $currentRow; ?>TAXCODE1" onChange="getTax(this.value, <?php echo $currentRow; ?>);" style="min-width:100px; max-width:150px">
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

			                                                $TAXPERC 	= 0;
	                                                		$sTAX		= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
		                                                    $rTAX		= $this->db->query($sTAX)->result();
		                                                    foreach($rTAX as $rwTAX) :
		                                                        $TAXPERC= $rwTAX->TAXLA_PERC;
	                                                        endforeach;
			                                            ?>
			                                            <input style="text-align:right" type="hidden" name="data<?php echo $currentRow; ?>TAXPERC" id="data<?php echo $currentRow; ?>TAXPERC" value="<?php echo $TAXPERC; ?>">
	                                               	</td>
	                                               	<?php
	                                               		$secGTax 	= base_url().'index.php/__l1y/getTaxP/?id=';
	                                               	?>
	                                               	<script type="text/javascript">
													    function getTax(thisVal, row)
													    {
											                var url     = "<?php echo $secGTax; ?>";
											                var collID	= url+'~'+thisVal;

											                if(thisVal == 0)
											                {
																taxPerc		= 0;
										                       	document.getElementById('data'+row+'TAXPERC').value = taxPerc;
										                       	var thisVal = document.getElementById('data'+row+'TAXPERC');
										                       	getValueSO(thisVal, row)
											                }
											                else
											                {
												                $.ajax({
												                    type: 'POST',
												                    url: url,
												                    data: {collID: collID},
												                    success: function(response)
												                    {
												                    	arrItem 	= response.split('~');
																		taxType 	= arrItem[0];
																		taxPerc		= arrItem[1];
												                       	document.getElementById('data'+row+'TAXPERC').value = taxPerc;
												                       	var thisVal = document.getElementById('data'+row+'TAXPERC');
												                       	getValueSO(thisVal, row)
												                    }
												                });
												            }
													    }
	                                               	</script>
	                                                
												  	<!-- ITEM TOTAL COST -->
												  	<td style="text-align:right; vertical-align: middle;">
			                                      		<?php if($SO_STAT == 1 || $SO_STAT == 4) { ?>
			                                          		<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="SO_COST<?php echo $currentRow; ?>" id="SO_COST<?php echo $currentRow; ?>" value="<?php print number_format($SO_COST, $decFormat); ?>" >
			                                      		<?php } else { ?>
			                                      			<?php print number_format($SO_COST, $decFormat); ?>
			                                      			<input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="SO_COST<?php echo $currentRow; ?>" id="SO_COST<?php echo $currentRow; ?>" value="<?php print number_format($SO_COST, $decFormat); ?>" >
			                                      		<?php } ?>
	                                           	    	
														<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][SO_COST]" id="data<?php echo $currentRow; ?>SO_COST" value="<?php echo $SO_COST; ?>">
	                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
	                                                </td>
												</tr>
													<?php
												endforeach;
												?>
												<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
												<?php
											}
											else
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
							<div>
								<div class="box-body">
									<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
		                            	<?php
											if($task=='add')
											{
												if(($SO_STAT == 1 || $SO_STAT == 4) && $ISCREATE == 1)
												{
													?>
														<button class="btn btn-primary" id="tblClose">
														<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
														</button>&nbsp;
													<?php
												}
											}
											else
											{
												if($ISAPPROVE == 1 && $SO_STAT == 3)
												{
													?>
														<button class="btn btn-primary" style="display:none" id="tblClose">
														<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
														</button>&nbsp;
													<?php
												}
												elseif(($ISCREATE == 1 || $ISAPPROVE == 1) && ($SO_STAT == 1 || $SO_STAT == 4))
												{
													?>
														<button class="btn btn-primary" id="tblClose">
														<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
														</button>&nbsp;
													<?php
												}
											}
											$backURL	= site_url('c_sales/c_s4l350r4/g4Ll50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
										?>
									</div>
	                            </div>
	                        </div>
	                    </div>
					</form>
			        <div class="col-md-12">
						<?php
                            $DOC_NUM	= $SO_NUM;
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
							$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
										AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
							$resAPP	= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$MAX_STEP		= $rowAPP->MAX_STEP;
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;
							endforeach;
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
			</section>
		</div>
	</body>
</html>

<?php
	if($LangID == 'IND')
	{
		$qtyDetail	= 'Detail item tidak boleh kosong.';
		$volmAlert	= 'Qty order tidak boleh nol.';
	}
	else
	{
		$qtyDetail	= 'Item Detail can not be empty.';
		$volmAlert	= 'Order qty can not be zero.';
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
      autoclose: true,
      endDate: '+1d'
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
		
	$(document).ready(function()
	{
		/*$("#CUST_CODE").change(function ()
		{
    		CUST_CODE = $("#CUST_CODE :selected").attr('value');
			//swal(CUST_CODE)
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
					$("#CUST_ADDRESS").val(xmlhttpTask.responseText);
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCust/';?>"+CUST_CODE,true);
			xmlhttpTask.send();			
		});*/
	});

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];

		OFF_NUM		= arrItem[0];
		CUST_CODE	= $("#CUST_CODE").val();
		CUST_ADD	= $("#CUST_ADDRESS").val();
		
		document.getElementById("OFF_NUMX").value 	= OFF_NUM;
		document.getElementById("CUST_CODEX").value = CUST_CODE;
		document.getElementById("CUST_ADDX").value 	= CUST_ADD;
		document.frmsrch.submitSrch.click();
	}
		
	function getValueSO(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		SO_VOLM1			= document.getElementById('SO_VOLM'+row);
		SO_VOLM 			= parseFloat(eval(SO_VOLM1).value.split(",").join(""));

		//var SO_VOLM 		= parseFloat(document.getElementById('SO_VOLM'+row).value);
		document.getElementById('data'+row+'SO_VOLM').value 	= parseFloat(Math.abs(SO_VOLM));
		document.getElementById('SO_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_VOLM)),decFormat));

		// SO PRICE
			SO_PRICE1			= document.getElementById('SO_PRICE'+row);
			SO_PRICE 			= parseFloat(eval(SO_PRICE1).value.split(",").join(""));
			document.getElementById('data'+row+'SO_PRICE').value 	= parseFloat(Math.abs(SO_PRICE));
			document.getElementById('SO_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(SO_PRICE),decFormat));

		// SO DISCOUNT
			valDICP				= document.getElementById('SO_DISP'+row);
			SO_DISP				= parseFloat(eval(valDICP).value.split(",").join(""));
			ITMPRICE_TEMP		= parseFloat(SO_VOLM * SO_PRICE);
			DISCOUNT			= parseFloat(SO_DISP * ITMPRICE_TEMP / 100);

		// SO DISCOUNT	
			document.getElementById('data'+row+'SO_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
			document.getElementById('SO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));

			thisValDISC			= document.getElementById('SO_DISC'+row);
			SO_DISC				= parseFloat(eval(thisValDISC).value.split(",").join(""));

		// SO TEMP
			ITMPRICE_TEMP		= parseFloat((SO_VOLM * SO_PRICE) - SO_DISC);
		
		// SO TAX		
			//TAXCODE1			= document.getElementById('data'+row+'TAXCODE1').value;
			TAXPERC				= document.getElementById('data'+row+'TAXPERC').value

		/*if(TAXCODE1 == 'TAX01')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.1);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			SO_COST			= parseFloat(ITMPRICE_TEMP + TAX1VAL);
		}
		else if(TAXCODE1 == 'TAX02')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.03);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			SO_COST			= parseFloat(ITMPRICE_TEMP - TAX1VAL);
		}
		if(TAXCODE1 == '' || TAXCODE1 == 0)
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			SO_COST			= parseFloat(ITMPRICE_TEMP);
		}*/
		TAX1VAL				= parseFloat(ITMPRICE_TEMP * TAXPERC / 100);
		document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
		SO_COST				= parseFloat(ITMPRICE_TEMP + TAX1VAL);

		document.getElementById('data'+row+'SO_COST').value 	= parseFloat(Math.abs(SO_COST));
		document.getElementById('SO_COST'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_COST)),decFormat));
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICP				= document.getElementById('SO_DISP'+row);
		SO_DISP				= parseFloat(eval(valDICP).value.split(",").join(""));
		
		document.getElementById('data'+row+'SO_DISP').value 	= parseFloat(Math.abs(SO_DISP));
		document.getElementById('SO_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_DISP)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('SO_VOLM'+row);
		SO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// SO_PRICE
		SO_PRICE			= parseFloat(document.getElementById('data'+row+'SO_PRICE').value);
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(SO_VOLM * SO_PRICE);
		DISCOUNT			= parseFloat(SO_DISP * ITMPRICE_TEMP / 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'SO_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('SO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		getValueSO(thisVal, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICC				= document.getElementById('SO_DISC'+row);
		SO_DISC				= parseFloat(eval(valDICC).value.split(",").join(""));
		
		document.getElementById('data'+row+'SO_DISC').value 	= parseFloat(Math.abs(SO_DISC));
		document.getElementById('SO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_DISC)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('SO_VOLM'+row);
		SO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// SO_PRICE
		SO_PRICE			= parseFloat(document.getElementById('data'+row+'SO_PRICE').value);
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(SO_VOLM * SO_PRICE);
		DISCOUNTP			= parseFloat(SO_DISC / ITMPRICE_TEMP * 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'SO_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('SO_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		getValueSO(thisVal, row)
	}
	
	function checkInp()
	{
		totRow		= document.getElementById('totalrow').value;		
		CUST_CODE	= document.getElementById('CUST_CODE').value;
		OFF_NUMM1	= document.getElementById('OFF_NUM1').value;
		SO_CAT		= document.getElementById('SO_CAT').value;
		
		if(CUST_CODE == 0)
		{
			swal("<?php echo $selCust; ?>",
			{
				icon: "warning",
			})
			.then(function()
			{
				document.getElementById('CUST_CODE').focus();
			});
			return false;	
		}
		
		if(SO_CAT == 1)
		{
			if(OFF_NUM1 == '')
			{
				swal("<?php echo $quotNoEmpty; ?>",
				{
					icon: "warning",
				});
				document.getElementById('OFF_NUM1').focus();
				return false;	
			}
		}
		
		if(totRow == 0)
		{
			swal('<?php echo $qtyDetail; ?>',
			{
				icon: "warning",
			});
			return false;
		}	
		
		var SO_TCOST	= 0;
		for(i=1;i<=totRow;i++)
		{
			var SO_COST = parseFloat(document.getElementById('data'+i+'SO_COST').value);
			SO_TCOST	= parseFloat(SO_TCOST) + parseFloat(SO_COST);
				
		}
		document.getElementById('SO_TOTCOST').value = SO_TCOST;
		
		for(i=1;i<=totRow;i++)
		{
			var SO_VOLM = parseFloat(document.getElementById('data'+i+'SO_VOLM').value);
			if(SO_VOLM == 0)
			{
				swal("<?php echo $qtySOEmpty; ?>",
				{
					icon: "warning",
				});
				document.getElementById('SO_VOLM'+i).focus();
				return false;	
			}
		}
	}
	
	function changeValue(thisVal, theRow)
	{
		var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			swal('Qty can not greater then '+ ITM_QTY_MIN,
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
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var SO_NUM 		= "<?php echo $DocNumber; ?>";
		var SO_CODE 	= "<?php echo $SO_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		OFF_NUM 		= arrItem[0];
		OFF_CODE 		= arrItem[1];
		PRJCODE 		= arrItem[2];
		ITM_CODE		= arrItem[3];
		ITM_NAME 		= arrItem[4];
		ITM_UNIT 		= arrItem[5];
		OFF_VOLM		= arrItem[6];
		ITM_PRICE 		= arrItem[7];
		OFF_COST		= arrItem[8];
		OFF_DISC 		= arrItem[9];
		OFF_DISCP 		= arrItem[10];
		TAXCODE1 		= arrItem[11];
		TAXPRICE1 		= arrItem[12];
		OFF_TOTCOST		= arrItem[13];
		ITM_VOLM		= arrItem[14];
		
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
		
		// ITM_CODE, SO_NUM, SO_CODE, PRJCODE, OFF_NUM, OFF_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SO_NUM" name="data['+intIndex+'][SO_NUM]" value="'+SO_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SO_CODE" name="data['+intIndex+'][SO_CODE]" value="'+SO_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'OFF_NUM" name="data['+intIndex+'][OFF_NUM]" value="'+OFF_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'OFF_CODE" name="data['+intIndex+'][OFF_CODE]" value="'+OFF_CODE+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITM ORDER NOW : SO_VOLM
		OFF_VOLM		= parseFloat(Math.abs(OFF_VOLM));
		OFF_VOLMV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_VOLM)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="SO_VOLM'+intIndex+'" id="SO_VOLM'+intIndex+'" value="'+OFF_VOLMV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, '+intIndex+');" ><input type="hidden" id="data'+intIndex+'SO_VOLM" name="data['+intIndex+'][SO_VOLM]" value="'+OFF_VOLM+'">';
		
		// ITM UNIT : ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM PRICE : SO_PRICE
		ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
		ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="SO_PRICE'+intIndex+'" id="SO_PRICE'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data['+intIndex+'][SO_PRICE]" id="data'+intIndex+'SO_PRICE" size="6" value="'+ITM_PRICE+'">';
		
		// ITM DISCP : OFF_DISCP
		OFF_DISCP		= parseFloat(Math.abs(OFF_DISCP));
		OFF_DISCPV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISCP)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="SO_DISP'+intIndex+'" id="SO_DISP'+intIndex+'" value="'+OFF_DISCPV+'" onBlur="countDisp(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_DISP]" id="data'+intIndex+'SO_DISP" value="'+OFF_DISCP+'">';
		
		// ITM DISC : OFF_DISCV
		OFF_DISC		= parseFloat(Math.abs(OFF_DISC));
		OFF_DISCV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISC)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="SO_DISC'+intIndex+'" id="SO_DISC'+intIndex+'" value="'+OFF_DISCV+'" onBlur="countDisc(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_DISC]" id="data'+intIndex+'SO_DISC" value="'+OFF_DISC+'">';
		
		// ITEM TAX : TAXCODE1, TAXPRICE1
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="max-width:150px" onChange="getValueSO(this, '+intIndex+');"><option value=""> --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0">';

		// ITEM TOTAL COST
		OFF_TOTCOST		= parseFloat(Math.abs(OFF_TOTCOST));
		OFF_TOTCOSTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_TOTCOST)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="SO_COST'+intIndex+'" id="SO_COST'+intIndex+'" value="'+OFF_TOTCOSTV+'" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_COST]" id="data'+intIndex+'SO_COST" value="'+OFF_TOTCOST+'"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		document.getElementById('data'+intIndex+'TAXCODE1').value 	= TAXCODE1;
		document.getElementById('data'+intIndex+'TAXPRICE1').value 	= parseFloat(Math.abs(TAXPRICE1));
		document.getElementById('totalrow').value = intIndex;
	}
	
	function add_itemDir(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var SO_NUM 		= "<?php echo $DocNumber; ?>";
		var SO_CODE 	= "<?php echo $SO_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET		= arrItem[0];
		JOBCODEID		= arrItem[1];
		JOBCODE			= arrItem[2];
		PRJCODE			= arrItem[3];
		ITM_CODE		= arrItem[4];
		JOBDESC1		= arrItem[5];
		serialNumber	= arrItem[6];
		ITM_UNIT		= arrItem[7];
		ITM_PRICE		= arrItem[8];
		TOT_VOLMBG		= arrItem[9];
		ITM_STOCK		= arrItem[10];
		ITM_USED		= arrItem[11];
		itemConvertion	= arrItem[12];
		TOT_AMOUNTBG	= arrItem[13];
		tempTotMax		= arrItem[14];
		PO_AMOUNT		= arrItem[15];
		TOT_BUDG		= arrItem[16];
		TOT_REQ			= arrItem[17];
		ITM_TYPE		= arrItem[18];
		ITM_NAME		= arrItem[19];
		ITM_DISC 		= 0;
		ITM_DISCP 		= 0;
		TAXCODE1 		= '';
		TAXPRICE1 		= 0;
		ITM_TOTCOST		= 0;
		OFF_NUM 		= 'DIR';
		OFF_CODE 		= 'DIR';
		
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
		
		// ITM_CODE, SO_NUM, SO_CODE, PRJCODE, OFF_NUM, OFF_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SO_NUM" name="data['+intIndex+'][SO_NUM]" value="'+SO_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SO_CODE" name="data['+intIndex+'][SO_CODE]" value="'+SO_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'OFF_NUM" name="data['+intIndex+'][OFF_NUM]" value="'+OFF_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'OFF_CODE" name="data['+intIndex+'][OFF_CODE]" value="'+OFF_CODE+'" width="10" size="15">';
		
		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITM ORDER NOW : SO_VOLM
			SO_VOLM			= parseFloat(Math.abs(0));
			SO_VOLMV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
			
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="SO_VOLM'+intIndex+'" id="SO_VOLM'+intIndex+'" value="'+SO_VOLMV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, '+intIndex+');" ><input type="hidden" id="data'+intIndex+'SO_VOLM" name="data['+intIndex+'][SO_VOLM]" value="'+SO_VOLM+'">';
		
		// ITM UNIT : ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM PRICE : SO_PRICE
			ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
			ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
			
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="SO_PRICE'+intIndex+'" id="SO_PRICE'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data['+intIndex+'][SO_PRICE]" id="data'+intIndex+'SO_PRICE" size="6" value="'+ITM_PRICE+'">';
		
		// ITM DISCP : ITM_DISCP
			ITM_DISCP		= parseFloat(Math.abs(ITM_DISCP));
			ITM_DISCPV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISCP)),decFormat));
			
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="SO_DISP'+intIndex+'" id="SO_DISP'+intIndex+'" value="'+ITM_DISCPV+'" onBlur="countDisp(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_DISP]" id="data'+intIndex+'SO_DISP" value="'+ITM_DISCP+'">';
		
		// ITM DISC : ITM_DISCV
			ITM_DISC		= parseFloat(Math.abs(ITM_DISC));
			ITM_DISCV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
			
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="SO_DISC'+intIndex+'" id="SO_DISC'+intIndex+'" value="'+ITM_DISCV+'" onBlur="countDisc(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_DISC]" id="data'+intIndex+'SO_DISC" value="'+ITM_DISC+'">';
		
		// ITEM TAX : TAXCODE1, TAXPRICE1
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="max-width:150px" onChange="getValueSO(this, '+intIndex+');"><option value=""> --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0">';

		// ITEM TOTAL COST
			ITM_TOTCOST		= parseFloat(Math.abs(ITM_TOTCOST));
			ITM_TOTCOSTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTCOST)),decFormat));
			
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="SO_COST'+intIndex+'" id="SO_COST'+intIndex+'" value="'+ITM_TOTCOSTV+'" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_COST]" id="data'+intIndex+'SO_COST" value="'+ITM_TOTCOST+'"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		document.getElementById('data'+intIndex+'TAXCODE1').value 	= TAXCODE1;
		document.getElementById('data'+intIndex+'TAXPRICE1').value 	= parseFloat(Math.abs(TAXPRICE1));
		document.getElementById('totalrow').value = intIndex;
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