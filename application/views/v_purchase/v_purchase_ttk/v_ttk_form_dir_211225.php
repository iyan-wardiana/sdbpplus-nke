<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Oktober 2017
 * File Name	= purchase_req_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

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
	$year = (int)$Pattern_YearAktive;
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_ttk_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ttk_header
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
	$lastPattNumb = $myMax;
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
	
	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb-D";
	$TTK_NUM	= $DocNumber;
	$TTK_CODE	= "$lastPatternNumb-D";
	
	$TTK_DATEY 			= date('Y');
	$TTK_DATEM			= date('m');
	$TTK_DATED 			= date('d');
	$TTK_DATE 			= date('m/d/Y');
	$Patt_Year 			= date('Y');
	$TTK_DUEDATE		= date('m/d/Y');
	$TTK_ESTDATE		= date('m/d/Y');
	$TTK_CATEG			= 'DP';
	$TTK_NOTES			= '';
	$TTK_NOTES1			= '';
	$TTK_CHECKER		= '';
	$TTK_GUARANTEE		= '';
	$TTK_GUARANTEE_EXPD	= date('m/d/Y');
	$TTK_STAT		= 1;

	if(isset($_POST['submitSrch1']))
	{
		$mySPLCODE 		= $_POST['SPLCODE1'];
		$SPLCODE		= $mySPLCODE;
	}
	else
	{
		$mySPLCODE		= "";
		$SPLCODE		= $mySPLCODE;

		// default supplier dari Uang Muka
		$sqlSUPLC	= "tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.DP_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
		$countSUPL	= $this->db->count_all($sqlSUPLC);
		
		$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.DP_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
		$vwSUPL	= $this->db->query($sqlSUPL)->result();

		// show input nomor surat jaminan dan tanggal masa berlaku jaminan
		$divGuarantee = "style='display: block';";
	}
	$TTK_AMOUNT			= 0;
	$TTK_AMOUNT_PPNH	= 0;
	$TTK_AMOUNT_PPHH	= 0;
	$TTK_AMOUNT_DPBH	= 0;
	$TTK_AMOUNT_RETH	= 0;
	$TTK_AMOUNT_POTH	= 0;
	$TTK_AMOUNT_OTH 	= 0;
	$TTK_GTOTAL			= 0;
	$TTK_GTOTALV		= 0;
	$TTK_ACC_OTH		= '';
	
}
else
{
	$TTK_NUM 		= $default['TTK_NUM'];
	$DocNumber 		= $default['TTK_NUM'];
	$TTK_CODE 		= $default['TTK_CODE'];
	$TTK_DATE 		= date('m/d/Y', strtotime($default['TTK_DATE']));
	$TTK_DUEDATE 	= date('m/d/Y', strtotime($default['TTK_DUEDATE']));
	$TTK_ESTDATE 	= date('m/d/Y', strtotime($default['TTK_ESTDATE']));
	$TTK_CHECKER 	= $default['TTK_CHECKER'];
	$TTK_NOTES 		= $default['TTK_NOTES'];
	$TTK_NOTES1 	= $default['TTK_NOTES1'];
	$TTK_AMOUNT 	= $default['TTK_AMOUNT'];
	$TTK_AMOUNT_RET = $default['TTK_AMOUNT_RET'];
	$TTK_AMOUNT_POT = $default['TTK_AMOUNT_POT'];
	$TTK_AMOUNT_PPN = $default['TTK_AMOUNT_PPN'];
	$TTK_AMOUNT_OTH = $default['TTK_AMOUNT_OTH'];
	$TTK_GTOTAL 	= $default['TTK_GTOTAL'];
	$TTK_ACC_OTH 	= $default['TTK_ACC_OTH'];
	$TTK_CATEG 		= $default['TTK_CATEG'];
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$TTK_STAT 		= $default['TTK_STAT'];
	$Patt_Number 	= $default['Patt_Number'];
	$lastPattNumb	= $Patt_Number;
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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

	    .form-group div#GuaranteeExpDate {
	    	position: absolute;
	    	text-align: left;
	    	margin-left: 412px;
	    	padding-left: 0px;
	    	padding-top: 20px;
	    	padding-bottom: 0px;
	    }

	    .form-group div#GexpDate {
	    	padding-left: 0px;
	    	margin-top: 0px;
	    }

	    .form-group div#estDPay {
	    	position: absolute;
	    	text-align: left;
	    	margin-left: 425px;
	    	padding-left: 0px;
	    	padding-top: 20px;
	    }

	    .form-group div#estDPay label{
	    	text-align: left;
	    	padding-left: 0px;
	    }

	    .form-group div#taxNo {
	    	padding-left: 0px;
	    }

	    .form-group div#taxNo a {
	    	display: block;
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
			if($TranslCode == 'Invoice')$Invoice = $LangTransl;
			if($TranslCode == 'TTKNumber')$TTKNumber = $LangTransl;
			if($TranslCode == 'TTKCode')$TTKCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'GuaranteeNo')$GuaranteeNo = $LangTransl;
			if($TranslCode == 'GuaranteeExpDate')$GuaranteeExpDate = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Receiver')$Receiver = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'BillDate')$BillDate = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;				
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SuplInvNo')$SuplInvNo = $LangTransl;
			if($TranslCode == 'InvList')$InvList = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'ReceiptSel')$ReceiptSel = $LangTransl;
			if($TranslCode == 'ItemAcceptence')$ItemAcceptence = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;

			if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'Substitute')$Substitute = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'Requester')$Requester = $LangTransl;
			if($TranslCode == 'BudName')$BudName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'greaterBud')$greaterBud = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'inpMRQTY')$inpMRQTY = $LangTransl;
			if($TranslCode == 'inpMRD')$inpMRD = $LangTransl;

			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'estDPay')$estDPay = $LangTransl;
			if($TranslCode == 'PPNValue')$PPNValue = $LangTransl;
			if($TranslCode == 'splInvNo')$splInvNo = $LangTransl;

		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title 	= "Tambah";
			$h2_title 	= "Tanda Terima Faktur";
			$h3_title	= "Penerimaan Barang";
			$alert1		= "Silahkan pilih ".$BudName."";
			$alert2		= "Silahkan pilih salah satu item yang akan diminta";
			$alert3		= "Silahkan pilih salah satu pemasok";
			$alert4		= "Anda belum memasukan detil item";
			$alert5		= "Silahkan tambahkan Nomor seri pajak";
			$alert6 	= "Silahkan centang Cek Total.";
			$alert7 	= "Tanggal Faktur Pajak tidak boleh kosong";
			$alert8 	= "Nomor seri pajak tidak boleh kosong";
			$alert9 	= "Nomor faktur supplier tidak boleh kosong";
			$alert10 	= "Nilai tagihan tidak boleh kosong";
		}
		else
		{
			$h1_title 	= "Add";
			$h2_title 	= "Invoice Receipt";
			$h3_title	= "Receiving Goods";
			$alert1		= "Please select ".$BudName."";
			$alert2		= "Please select an item will be requested";
			$alert3		= "Please select a Supplier";
			$alert4		= "Detail Document Receipt can not be empty";
			$alert5		= "Please add tax serial number";
			$alert6 	= "Please check Total Check.";
			$alert7 	= "Tax Invoice Date can not empty";
			$alert8 	= "Tax serial number can not empty";
			$alert9 	= "Invoice supplier number can not empty";
			$alert10 	= "Bill value can not empty";
		}

		$PRJCODE1	= $PRJCODE;
		$SPLCODE1	= $SPLCODE;
		$TTK_CATEG1	= $TTK_CATEG;
		if(isset($_POST['PRJCODE1']))
		{
			$PRJCODE1	= $_POST['PRJCODE1'];
			$SPLCODE1	= $_POST['SPLCODE1'];
			$TTK_CATEG1	= $_POST['TTK_CATEG1'];
		}
		
		$SPLADD1	= '';
		$sqlSUPL	= "SELECT SPLADD1 FROM tbl_supplier WHERE SPLCODE = '$SPLCODE1' AND SPLSTAT = '1' LIMIT 1";
		$resSUPL	= $this->db->query($sqlSUPL)->result();
		foreach($resSUPL as $rowSUPL):
			$SPLADD1	= $rowSUPL->SPLADD1;
		endforeach;

		if(isset($_POST['submitSrch1']))
		{
			$myTTKCATEG		= $_POST['TTK_CATEG1'];
			$TTK_CATEG		= $myTTKCATEG;
			if($TTK_CATEG == 'DP')
			{
				$sqlSUPLC	= "tbl_dp_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.DP_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_dp_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.DP_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();

				$divGuarantee = "style='display: block';";
			}
			else
			{
				$divGuarantee = "style='display: none';";
			}
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - TTK_VALUE
			$EMPN_1 	= "";
			$EMPN_2 	= "";
			$EMPN_3 	= "";
			$EMPN_4		= "";
			$EMPN_5 	= "";

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
				$APPROVE_AMOUNT 	= 10000000000;
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
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo "$PRJCODE - $PRJNAME"; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
		    	<!-- after get Supplier code -->
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display: none;">
                    <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE1; ?>" />
                    <input type="text" name="SPLCODE1" id="SPLCODE1" value="<?php echo $SPLCODE1; ?>" />
                    <input type="text" name="TTK_CATEG1" id="TTK_CATEG1" value="<?php echo $TTK_CATEG1; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <!-- End -->
	            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
			                	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
			                    <input type="hidden" name="rowCount" id="rowCount" value="0">
			                    <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPattNumb; ?>">
			                    <div class="form-group" style="display:none">
			                      	<label for="inputName" class="col-sm-3 control-label"><?php echo $TTKNumber; ?></label>
			                      	<div class="col-sm-9">
			                    		<input type="hidden" class="textbox" name="TTK_NUM" id="TTK_NUM" size="30" value="<?php echo $TTK_NUM; ?>" />
			                            <input type="text" class="form-control" style="max-width:195px" name="TTK_NUM1" id="TTK_NUM1" value="<?php echo $TTK_NUM; ?>" readonly >
			                      	</div>
			                    </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $TTKCode; ?></label>
		                          	<div class="col-sm-5">
		                          		<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                            		<input type="text" class="form-control" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>" >
		                        		<?php } else { ?>
			                        		<input type="hidden" class="form-control" style="max-width:150px" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>" >
		                            		<input type="text" class="form-control" name="TTK_CODEX" id="TTK_CODEX" value="<?php echo $TTK_CODE; ?>" disabled >
		                        		<?php } ?>
		                          	</div>
		                          	<div class="col-sm-4" id="estDPay">
		                          		<label for="inputName" class="col-sm-12 control-label"><?php echo $estDPay; ?></label>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $TTKCode; ?></label>
		                          	<div class="col-sm-9">
		                                <label>
		                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked="checked">
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
		                                    <input type="text" name="TTK_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $TTK_DATE; ?>" style="width:120px">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-4">
		                          		<div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="TTK_ESTDATE" class="form-control pull-left" id="datepicker3" value="<?php echo $TTK_ESTDATE; ?>" >
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
		                          	<div class="col-sm-3">
		                            	<select name="TTK_CATEG" id="TTK_CATEG" class="form-control select2" onChange="getSUPPLIER(this.value)">
		                                    <option value="DP" <?php if($TTK_CATEG1 == 'DP') { ?> selected <?php } ?>>Uang Muka</option>
		                                    <option value="OTH" <?php if($TTK_CATEG1 == 'OTH') { ?> selected <?php } ?>><?php echo $Others; ?> - OTH</option>
		                                </select>
		                          	</div>
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Receiver; ?></label>
		                          	<div class="col-sm-4">
		                                <input type="text" class="form-control" name="TTK_CHECKER" id="TTK_CHECKER" value="<?php echo $TTK_CHECKER; ?>" >
		                          	</div>
		                        </div>
			                    <div class="form-group">
			                      	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName; ?></label>
			                      	<div class="col-sm-9">
			                      		<?php if($TTK_STAT != 1): ?> 
				                      		<input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE1; ?>" />
				                        	<select name="SPLCODE1" id="SPLCODE1" class="form-control select2" onChange="getSUPPLIER(this.value)" disabled>
				                                <option value=""> --- </option>
				                                <?php echo $i = 0;
				                                if($countSUPL > 0)
				                                {
				                                    foreach($vwSUPL as $row) :
				                                    ?>
				                                        <option value="<?php echo $row->SPLCODE; ?>" <?php if($SPLCODE == $row->SPLCODE) { ?> selected <?php } ?>>
				                                    		<?php echo $row->SPLDESC; ?>
				                                    	</option>
				                                    <?php
				                                    endforeach;
				                                }
				                                ?>
				                            </select>
				                        <?php else: ?>
				                        	<select name="SPLCODE" id="SPLCODE" class="form-control select2" onChange="getSUPPLIER(this.value)">
		                                        <option value=""> --- </option>
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
		                                <?php endif; ?>
			                      	</div>
			                    </div>
			                    <script>
									function getSUPPLIER(SPLCODE) 
									{
										TTK_CATEG	= document.getElementById("TTK_CATEG").value
										document.getElementById("TTK_CATEG1").value = TTK_CATEG;
										SPLCODE	= document.getElementById("SPLCODE").value
										document.getElementById("SPLCODE1").value = SPLCODE;
										PRJCODE	= document.getElementById("PRJCODE").value
										document.getElementById("PRJCODE1").value = PRJCODE;
										document.frmsrch1.submitSrch1.click();
									}
								</script>
								<div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $DueDate; ?></label>
		                          	<div class="col-sm-5">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="TTK_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $TTK_DUEDATE; ?>">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-4" id="GuaranteeExpDate" <?php echo $divGuarantee; ?>>
		                          		<label for="inputName" class="control-label" id="GuaranteeExpDate"><?php echo $GuaranteeExpDate; ?></label>
		                          	</div>
		                        </div>
		                        <div class="form-group" <?php echo $divGuarantee; ?>>
		                        	<label for="inputName" class="col-sm-3 control-label"><?php echo $GuaranteeNo; ?></label>
		                        	<div class="col-sm-5">
		                        		<input type="text" class="form-control" name="TTK_GUARANTEE" id="TTK_GUARANTEE" value="<?php echo $TTK_GUARANTEE; ?>">
		                        	</div>
		                        	<div class="col-sm-4" id="GexpDate">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="TTK_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $TTK_DUEDATE; ?>">
		                                </div>
		                          	</div>
		                        </div>
			                    <div class="form-group" style="display: none;">
			                      	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
			                      	<div class="col-sm-9">
			                        	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
				                        <select name="selPRJCODE" id="selPRJCODE" class="form-control select2" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
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
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-info-circle"></i>
								<h3 class="box-title"><?php echo $othInfo; ?></h3>
							</div>
							<div class="box-body">
								<div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Cek Total / PPn</label>
		                            <div class="col-sm-5">
		                                <div class="input-group">
		                                    <span class="input-group-addon">
		                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalTTK()">
		                                    </span>
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT" id="TTK_AMOUNT" value="<?php echo $TTK_AMOUNT; ?>" >
			                                <?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
			                               		<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNTX" id="TTK_AMOUNTX" value="<?php echo number_format($TTK_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" >
			                                <?php } else { ?>
			                                	<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNTX" id="TTK_AMOUNTX" value="<?php echo number_format($TTK_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
			                                <?php } ?>
		                        		</div>
		                            </div>
		                            <div class="col-sm-4">
		                                <?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                               		<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPNX" id="TTK_AMOUNT_PPNX" value="<?php echo number_format($TTK_AMOUNT_PPNH, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" readonly>
		                                <?php } else { ?>
		                                	<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPNX" id="TTK_AMOUNT_PPNX" value="<?php echo number_format($TTK_AMOUNT_PPNH, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
		                                <?php } ?>
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_PPN" id="TTK_AMOUNT_PPN" value="<?php echo $TTK_AMOUNT_PPNH; ?>" >
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_PPH" id="TTK_AMOUNT_PPH" value="<?php echo $TTK_AMOUNT_PPHH; ?>" >
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_DPB" id="TTK_AMOUNT_DPB" value="<?php echo $TTK_AMOUNT_DPBH; ?>" >
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_RET" id="TTK_AMOUNT_RET" value="<?php echo $TTK_AMOUNT_RETH; ?>" >
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_POT" id="TTK_AMOUNT_POT" value="<?php echo $TTK_AMOUNT_POTH; ?>" >
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Grand Total</label>
		                            <div class="col-sm-9">
		                                <input type="hidden" class="form-control" style="text-align:right" name="TTK_GTOTAL" id="TTK_GTOTAL" value="<?php echo $TTK_GTOTAL; ?>" >
		                                <input type="text" class="form-control" style="text-align:right" name="TTK_GTOTALX" id="TTK_GTOTALX" value="<?php echo number_format($TTK_GTOTALV, 2); ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
		                            </div>
		                        </div>
		                        <script type="text/javascript">
		                        	function getAmountPPn(thisVal)
		                        	{
		                        		var TTK_PPN 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_PPN;
		                        		document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_PPN)),2));

		                        		var TTK_TOTAL 	= document.getElementById('TTK_AMOUNT').value;
		                        		var TTK_POT 	= document.getElementById('TTK_AMOUNT_POT').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POT);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}

		                        	function getAmountPot(thisVal)
		                        	{
		                        		var TTK_POT 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT_POT').value 	= TTK_POT;
		                        		document.getElementById('TTK_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_POT)),2));

		                        		var TTK_TOTAL 	= document.getElementById('TTK_AMOUNT').value;
		                        		var TTK_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POT);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}
		                        </script>
			                    <div class="form-group">
			                   	  <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
			                      	<div class="col-sm-9">
			                            <textarea class="form-control" name="TTK_NOTES"  id="TTK_NOTES" style="height: 70px"><?php echo $TTK_NOTES; ?></textarea>
			                      	</div>
			                    </div>
			                    <div class="form-group">
			                        <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
			                        <div class="col-sm-5">
			                            <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $TTK_STAT; ?>">
			                                <?php
												if( $task == "add")
												{
													?>
														<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" width="100%">
															<option value="1">New</option>
															<option value="2">Confirm</option>
														</select>
													<?php
												}
												else
												{
													if(($TTK_STAT == 1 || $TTK_STAT == 4))
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" width="100%">
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
													elseif($TTK_STAT == 2)
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" width="100%">
																<option value="1"<?php if($TTK_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																<option value="2"<?php if($TTK_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																<option value="3"<?php if($TTK_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																<option value="4"<?php if($TTK_STAT == 4) { ?> selected <?php } ?>>Revising</option>
																<option value="5"<?php if($TTK_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<option value="6"<?php if($TTK_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($TTK_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																<option value="9"<?php if($TTK_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
															</select>
														<?php
													}
													else
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" width="100%">
																<option value="1"<?php if($TTK_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																<option value="2"<?php if($TTK_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																<option value="3"<?php if($TTK_STAT == 3) { ?> selected <?php } ?> disabled>Approved</option>
																<option value="4"<?php if($TTK_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																<option value="5"<?php if($TTK_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																<option value="6"<?php if($TTK_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($TTK_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																<option value="9"<?php if($TTK_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
															</select>
														<?php
													}
												}
												// END : FOR ALL APPROVAL FUNCTION
											?>
			                            </select>
			                        </div>
				                    <div class="col-sm-4" style="display: none;">
				                        <div class="pull-right">
				                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addItm">
				                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
				                        	</a>
				                        </div>
				                    </div>
				                    <div class="col-sm-4" id="taxNo">
			                        	<a class="btn btn-sm btn-warning" onClick="add_TAXNO()">
			                        		<i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;<?php echo "Tambah No. Seri Pajak"; ?>
			                        	</a>
				                    </div>
			                    </div>
			                    <br>
							</div>
						</div>
					</div>
	                <div class="col-md-12">
	                    <div class="box box-primary">
	                    	<!-- pada bagian detail tidak lagi base item (RAP) berdasarkah hasil meeting tgl 21 Desember 2021 
	                        <div class="search-table-outter" style="display: none;">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                <tr style="background:#CCCCCC">
	                                  	<th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
	                                  	<th width="3%" rowspan="2" style="text-align:center; vertical-align: middle;" nowrap><?php //echo $ItemCode ?> </th>
	                                  	<th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php //echo $ItemName ?> </th>
	                                  	<th colspan="3" style="text-align:center"><?php //echo $ItemQty; ?> </th>
	                                  	<th rowspan="2" style="text-align:center; vertical-align: middle;"><?php //echo $Unit ?> </th>
	                                  	<th width="24%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php //echo $Remarks ?> </th>
	                                </tr>
	                                <tr style="background:#CCCCCC">
	                                  	<th style="text-align:center;"><?php //echo $Planning ?> </th>
	                                  	<th style="text-align:center;"><?php //echo $Requested ?> </th>
	                                  	<th style="text-align:center"><?php //echo $RequestNow ?></th>
	                                </tr>
	                                <?php /* ----------------- hidden -----------------------------------------------
	                                if($task == 'edit')
	                                {
	                                    $sqlDET	= "SELECT A.TTK_NUM, A.TTK_CODE, A.TTK_DATE, A.PRJCODE,
	                                    				A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
	                                    				A.ITM_CODE, A.TTK_VOLM, A.TTK_PRICE, A.TTK_TOTAL, A.ITM_UNIT, A.PR_VOLM, A.PR_AMOUNT,
	                                    				A.ITM_VOLMBG, A.ITM_BUDG, A.TTK_DESC, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
	                                                    B.ITM_CODE_H, B.ITM_NAME, B.ITM_TYPE
	                                                FROM tbl_ttk_detail_itm A
	                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                        AND B.PRJCODE = '$PRJCODE'
	                                                WHERE TTK_NUM = '$TTK_NUM' 
	                                                    AND B.PRJCODE = '$PRJCODE'";
	                                    $result = $this->db->query($sqlDET)->result();
	                                    $i		= 0;
	                                    $j		= 0;
	                                    
	                                    foreach($result as $row) :
	                                        $currentRow  	= ++$i;
	                                        $TTK_NUM 		= $row->TTK_NUM;
	                                        $TTK_CODE 		= $row->TTK_CODE;
	                                        $PRJCODE 		= $row->PRJCODE;
	                                        $JOBCODEDET		= $row->JOBCODEDET;
	                                        $JOBCODEID 		= $row->JOBCODEID;
	                                        $JOBPARENT		= $row->JOBPARENT;
	                                        $JOBPARDESC 	= $row->JOBPARDESC;
	                                        $ITM_CODE 		= $row->ITM_CODE;
	                                        $TTK_VOLM 		= $row->TTK_VOLM;
	                                        $TTK_PRICE 		= $row->TTK_PRICE;
	                                        $TTK_TOTAL 		= $row->TTK_TOTAL;
	                                        $ITM_UNIT 		= $row->ITM_UNIT;
	                                        $PR_VOLM 		= $row->PR_VOLM;
	                                        $PR_AMOUNT 		= $row->PR_AMOUNT;
	                                        $ITM_VOLMBG 	= $row->ITM_VOLMBG;
	                                        $ITM_BUDG 		= $row->ITM_BUDG;
	                                        $TTK_DESC 		= $row->TTK_DESC;
	                                        $TAXCODE1 		= $row->TAXCODE1;
	                                        $TAXPRICE1 		= $row->TAXPRICE1;
	                                        $ITM_CODE_H		= $row->ITM_CODE_H;
	                                        $ITM_NAME 		= $row->ITM_NAME;
	                                        $ITM_TYPE 		= $row->ITM_TYPE;
	                                        $itemConvertion = 1;

	                                        if($JOBPARDESC == '')
	                                        {
	                                            $sqlJPAR	= "SELECT A.JOBDESC FROM tbl_joblist_detail A 
																WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
																	WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')";
	                                            $resJPAR	= $this->db->query($sqlJPAR)->result();
	                                            foreach($resJPAR as $rowJPAR) :
	                                                $JOBPARENT	= $rowJPAR->JOBDESC;
	                                                $JOBPARDESC	= $rowJPAR->JOBDESC;
	                                            endforeach;
	                                        }

	                                        ?> 
	                                        <tr id="tr_<?php echo $currentRow; ?>" style="vertical-align: middle;">
	                                        	<td style="text-align:center; vertical-align: middle;">
		                                          	<?php
			                                            if($TTK_STAT == 1 || $TTK_STAT == 4)
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
		                                        	<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
		                                            <!-- Checkbox -->
		                                        </td>
		                                        <td style="text-align:left; vertical-align: middle;" nowrap> <!-- Item Code -->
		                                          	<?php echo $ITM_CODE; ?>
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>TTK_NUM" name="data[<?php echo $currentRow; ?>][TTK_NUM]" value="<?php echo $TTK_NUM; ?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>TTK_CODE" name="data[<?php echo $currentRow; ?>][TTK_CODE]" value="<?php echo $TTK_CODE; ?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                      	</td>
		                                      	<td style="text-align:left; vertical-align: middle;">
		                                        	<?php echo $ITM_NAME; ?> <!-- Item Name -->
		                                        	<div style="font-style: italic;">
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?=$JOBPARDESC?>
												  	</div>
		                                        </td>
		                                        <?php
		                                            // CARI TOTAL REGUSEST BUDGET APPROVED
		                                            $TOT_TTKAMOUNT	= 0;
		                                            $TOT_TTKQTY		= 0;
		                                            $sqlTOTBUDG		= "SELECT DISTINCT SUM(A.TTK_VOLM * A.TTK_PRICE) AS TOT_TTKAMOUNT, 
		                                                                    SUM(A.TTK_VOLM) AS TOT_TTKQTY
		                                                                FROM tbl_ttk_detail_itm A
		                                                                INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM
		                                                                    AND B.PRJCODE = '$PRJCODE'
		                                                                WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' 
		                                                                    AND A.JOBCODEDET = '$JOBCODEDET' AND B.TTK_STAT IN ('2','3','6')
		                                                                    AND A.TTK_NUM != '$TTK_NUM'";
		                                            $resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
		                                            foreach($resTOTBUDG as $rowTOTBUDG) :
		                                                $TOT_TTKAMOUNT	= $rowTOTBUDG->TOT_TTKAMOUNT;
		                                                $TOT_TTKQTY		= $rowTOTBUDG->TOT_TTKQTY;
		                                            endforeach;
													if($TOT_TTKQTY == '')
													{
														$TOT_TTKAMOUNT	= 0;
														$TOT_TTKQTY		= 0;
													}
		                                            if($ITM_TYPE == 'SUBS')
		                                            {
		                                                $REQ_VOLM	= 0;
		                                                $REQ_AMOUNT	= 0;
		                                                $sqlREQ		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail 
		                                                                WHERE PRJCODE = '$PRJCODE'
		                                                                    AND ITM_CODE = '$ITM_CODE_H'";
		                                                $resREQ		= $this->db->query($sqlREQ)->result();
		                                                foreach($resREQ as $rowREQ) :
		                                                    $TOT_TTKQTY		= $rowREQ->REQ_VOLM;
		                                                    $TOT_TTKAMOUNT	= $rowREQ->REQ_AMOUNT;
		                                                endforeach;
		                                            }
		                                            
		                                            $TOT_USEBUDG	= $TOT_TTKAMOUNT;				// 15
		                                            $ITM_BUDG		= $row->ITM_BUDG;				// 16
		                                            if($ITM_BUDG == '')
		                                                $ITM_BUDG	= 0;										
		                                            
		                                            $ITM_VOLM	= 0;
		                                            $ADD_VOLM	= 0;
		                                            
		                                            $sqlITM		= "SELECT A.ITM_VOLM, A.ADD_VOLM
		                                                            FROM tbl_joblist_detail A
		                                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                                                AND B.PRJCODE = '$PRJCODE'
		                                                            WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','I','T') 
			                                                            AND A.ITM_CODE = '$ITM_CODE'
			                                                            AND A.JOBPARENT = '$JOBCODEID'";
		                                                            
		                                            $BUDG_VOLM		= 0;
		                                            $ITM_BUDQTY		= 0;
		                                            $resITM			= $this->db->query($sqlITM)->result();
		                                            foreach($resITM as $rowITM) :
		                                                $ITM_VOLM	= $rowITM->ITM_VOLM;
		                                                $ADD_VOLM	= $rowITM->ADD_VOLM;
		                                                $BUDG_VOLM	= $ITM_VOLM + $ADD_VOLM;
		                                            endforeach;
		                                            $BUDG_VOLM		= $ITM_VOLMBG + $ADD_VOLM;									
		                                            $ITM_BUDQTY		= $BUDG_VOLM;
		                                            
		                                            // SISA QTY PR
		                                            $REMPRQTY		= $ITM_BUDQTY - $TOT_TTKQTY;
		                                        ?>
		                                        <td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
		                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                                          		<input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_VOLMBGx<?php echo $currentRow; ?>" id="ITM_VOLMBGx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_BUDQTY, $decFormat); ?>" disabled >
		                                      		<?php } else { print number_format($ITM_BUDQTY, $decFormat); ?> <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_VOLMBGx<?php echo $currentRow; ?>" id="ITM_VOLMBGx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_BUDQTY, $decFormat); ?>" disabled >
		                                      		<?php } ?>
		                                          	<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_VOLMBG]" id="ITM_VOLMBG<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
		                                      	</td>
		                                      	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
		                                      		<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                                          		<input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_TTKQTYx<?php echo $currentRow; ?>" id="TOT_TTKQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOT_TTKQTY, $decFormat); ?>" disabled >
		                                      		<?php } else { print number_format($TOT_TTKQTY, $decFormat); ?> <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_TTKQTYx<?php echo $currentRow; ?>" id="TOT_TTKQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOT_TTKQTY, $decFormat); ?>" disabled >
		                                      		<?php } ?>
			                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOT_TTKAMOUNT; ?>" >
			                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_TTKQTY<?php echo $currentRow; ?>" id="TOT_TTKQTY<?php echo $currentRow; ?>" value="<?php print $TOT_TTKQTY; ?>" >
			                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx<?php echo $currentRow; ?>" id="ITM_REQUESTEDx<?php echo $currentRow; ?>" value="<?php print number_format($TOT_TTKAMOUNT, $decFormat); ?>" disabled >
			                                    </td>
		                                     	<td style="text-align:right; vertical-align: middle;"> <!-- Item Request Now -- TTK_VOLM -->
		                                      		<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                                          		<input type="text" name="TTK_VOLM<?php echo $currentRow; ?>" id="TTK_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($TTK_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                      		<?php } else { print number_format($TTK_VOLM, $decFormat); ?> <input type="hidden" name="TTK_VOLM<?php echo $currentRow; ?>" id="TTK_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($TTK_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                      		<?php } ?>
													<input type="hidden" name="data[<?php echo $currentRow; ?>][TTK_VOLM]" id="data<?php echo $currentRow; ?>TTK_VOLM" value="<?php print $TTK_VOLM; ?>" class="form-control" style="max-width:300px;" ><input type="hidden" name="data[<?php echo $currentRow; ?>][TTK_PRICE]" id="data<?php echo $currentRow; ?>TTK_PRICE" value="<?php print $TTK_PRICE; ?>" class="form-control" style="max-width:300px;" >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][TTK_TOTAL]" id="data<?php echo $currentRow; ?>TTK_TOTAL" value="<?php print $TTK_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
													<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
		                                        </td>
		                                        <td style="text-align:center; vertical-align: middle;" nowrap>
		                                          <?php echo $ITM_UNIT; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
		                                        <!-- Item Unit Type -- ITM_UNIT --></td>
		                                        <td style="text-align:left; vertical-align: middle;">
		                                      		<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                                          		<input type="text" name="data[<?php echo $currentRow; ?>][TTK_DESC]" id="data<?php echo $currentRow; ?>TTK_DESC" size="20" value="<?php print $TTK_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
		                                      		<?php } else { print $TTK_DESC; ?> <input type="hidden" name="data[<?php echo $currentRow; ?>][TTK_DESC]" id="data<?php echo $currentRow; ?>TTK_DESC" size="20" value="<?php print $TTK_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
		                                      		<?php } ?>
		                                            
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
		                                        </td>
	                                  		</tr>
	                                    <?php
	                                    endforeach;
	                                }
	                                >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> endphp hidden >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */
	                                ?>
	                                <input type="hidden" name="totalrow" id="totalrow" value="<?php //echo $currentRow; ?>">
	                            </table>
	                        </div>
							----------------------------------------------- endhtml hidden detail RAP ------------------------------> 
	                        <div class="search-table-outter">
		                            <table id="tbl_tax" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="5%" style="text-align:center">&nbsp;</th>
	                                        <th width="20%" style="text-align:center" nowrap><?php echo $Date; ?></th>
	                                        <th width="25%" style="text-align:center" nowrap><?php echo "No. Seri Pajak"; ?></th>
	                                        <th width="20%" style="text-align:center" nowrap><?php echo $splInvNo; ?></th>
	                                        <th width="15%" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></th>
	                                        <th width="15%" style="text-align:center" nowrap><?php echo $PPNValue; ?></th>
	                                    </tr>
										<?php
											$currentRow 	= 0;
											$TOT_AMOUNT2	= 0;
		                                    if($task == 'edit')
		                                    {
		                                        // count data
		                                        $sqlIRc		= "tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM'";
		                                        $resIRc 	= $this->db->count_all($sqlIRc);
		                                        // End count data

		                                        $sqlIR			= "SELECT A.* FROM tbl_ttk_tax A
																	WHERE A.TTK_NUM = '$TTK_NUM' AND A.PRJCODE = '$PRJCODE'";
		                                        $resIRc 		= $this->db->query($sqlIR)->result();
		                                        $i				= 0;
		                                        if($resIRc > 0)
		                                        {
		                                            foreach($resIRc as $row) :
		                                                $currentRow  	= ++$i;
		                                                $TTKT_DATE 		= $row->TTKT_DATE;
		                                                $TTKT_TAXNO 	= $row->TTKT_TAXNO;
		                                                $TTKT_SPLINVNO 	= $row->TTKT_SPLINVNO;
		                                                $TTKT_AMOUNT	= $row->TTKT_AMOUNT;
														$TTKT_TAXAMOUNT	= $row->TTKT_TAXAMOUNT;
		                                                ?>
		                                                    <tr id="trTAX_<?php echo $currentRow; ?>">
		                                                        <td height="25" style="text-align:left">
																	<?php
																		if($TTK_STAT == 1)
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
		                                                        </td>
		                                                        <td style="text-align:center;">
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $currentRow; ?>TTKT_DATE" name="dataTAX[<?php echo $currentRow; ?>][TTKT_DATE]" value="<?php echo $TTKT_DATE; ?>" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD" maxlength="10">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo strftime('%d %b %Y', strtotime($TTKT_DATE));
			                                                        		?>
			                                                            	<input type="hidden" id="dataTAX<?php echo $currentRow; ?>TTKT_DATE" name="dataTAX[<?php echo $currentRow; ?>][TTKT_DATE]" value="<?php echo $TTKT_DATE; ?>" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD">
			                                                        		<?php 
			                                                        	}
		                                                        	?>
		                                                            
		                                                        </td>
		                                                        <td style="text-align:left;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $currentRow; ?>TTKT_TAXNO" name="dataTAX[<?php echo $currentRow; ?>][TTKT_TAXNO]" value="<?php echo $TTKT_TAXNO; ?>" class="form-control" onKeyUp="chkMask(this)" placeholder="XXX.XXX-XX.XXXXXXXX" onChange="chkCont(this, <?php echo $currentRow; ?>)" maxlength="19">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo $TTKT_TAXNO;
			                                                        		?>
			                                                            	<input type="hidden" id="dataTAX<?php echo $currentRow; ?>TTKT_TAXNO" name="dataTAX[<?php echo $currentRow; ?>][TTKT_TAXNO]" value="<?php echo $TTKT_TAXNO; ?>" class="form-control" onKeyUp="chkMask(this)" placeholder="XXX.XXX-XX.XXXXXXXX" maxlength="19">
			                                                        		<?php 
			                                                        	}
		                                                        	?>
		                                                        </td>
		                                                        <td style="text-align:left;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $currentRow; ?>TTKT_SPLINVNO" name="dataTAX[<?php echo $currentRow; ?>][TTKT_SPLINVNO]" value="<?php echo $TTKT_SPLINVNO; ?>" class="form-control" maxlength="100">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo $TTKT_TAXNO;
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $currentRow; ?>TTKT_SPLINVNO" name="dataTAX[<?php echo $currentRow; ?>][TTKT_SPLINVNO]" value="<?php echo $TTKT_SPLINVNO; ?>" class="form-control" maxlength="100">
			                                                        		<?php 
			                                                        	}
		                                                        	?>
		                                                        </td>
		                                                        <td style="text-align:right;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{ 
			                                                        		?>
			                                                            	<input type="text" id="TTKT_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($TTKT_AMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, <?php echo $currentRow; ?>)">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{ 
			                                                        		echo number_format($TTKT_AMOUNT, 2); 
			                                                        		?>
			                                                            	<input type="hidden" id="TTKT_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($TTKT_AMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, <?php echo $currentRow; ?>)">
			                                                        		<?php 
			                                                        	}
		                                                        	?>

		                                                        	<input type="hidden" id="dataTAX<?php echo $currentRow; ?>TTKT_AMOUNT" name="dataTAX[<?php echo $currentRow; ?>][TTKT_AMOUNT]" value="<?php echo $TTKT_AMOUNT; ?>" class="form-control" style="min-width:100px;">
		                                                        </td>
		                                                        <td style="text-align:right;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{ 
			                                                        		?>
			                                                            	<input type="text" id="TTKT_TAXAMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($TTKT_TAXAMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgPPN(this, <?php echo $currentRow; ?>)">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{ 
			                                                        		echo number_format($TTKT_TAXAMOUNT, 2); 
			                                                        		?>
			                                                            	<input type="hidden" id="TTKT_TAXAMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($TTKT_TAXAMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgPPN(this, <?php echo $currentRow; ?>)">
			                                                        		<?php 
			                                                        	}
		                                                        	?>

		                                                        	
		                                                        	<input type="hidden" id="dataTAX<?php echo $currentRow; ?>TTKT_TAXAMOUNT" name="dataTAX[<?php echo $currentRow; ?>][TTKT_TAXAMOUNT]" value="<?php echo $TTKT_TAXAMOUNT; ?>" class="form-control" style="min-width:100px;">
		                                                        </td>
		                                                    </tr>
		                                                <?php
		                                            endforeach;
		                                        }
		                                    }
	                                    ?>
	                                    <input type="hidden" name="totalrowTAX" id="totalrowTAX" value="<?php echo $currentRow; ?>">
	                                </table>
	                            </div>
	                  	</div>
		            </div>

                    <div class="col-md-6">
						<div>
							<div class="box-body">
								<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                          	<div class="col-sm-9">
		                        	<?php
										if($ISAPPROVE == 1)
											$ISCREATE = 1;
										
										if($task=='add')
										{
											if($TTK_STAT == 1 && $ISCREATE == 1)
											{
												?>
													<button class="btn btn-primary">
													<i class="fa fa-save"></i>
													</button>&nbsp;
												<?php
											}
										}
										else
										{
											if($ISAPPROVE == 1 && $TTK_STAT == 2)
											{
												?>
													<button class="btn btn-primary">
													<i class="fa fa-save"></i>
													</button>&nbsp;
												<?php
											}
											elseif($ISAPPROVE == 1 && $TTK_STAT == 3)
											{
												?>
			                                        <button class="btn btn-primary" style="display:none">
			                                        <i class="fa fa-save"></i>
			                                        </button>&nbsp;
												<?php
											}
											elseif($ISCREATE == 1 && ($TTK_STAT == 1 || $TTK_STAT == 4))
											{
												?>
													<button class="btn btn-primary" >
													<i class="fa fa-save"></i>
													</button>&nbsp;
												<?php
											}
											else
											{
												?>
			                                        <button class="btn btn-primary" style="display:none">
			                                        <i class="fa fa-save"></i>
			                                        </button>&nbsp;
												<?php
											}
										}
										$backURL	= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
									?>
								</div>
							</div>
	                    </div>
	                </div>
	            </form>

		        <div class="col-md-12" style="display: none;">
					<?php
	                    $DOC_NUM	= $TTK_NUM;
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
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $ItemList; ?></a>
						                    </li>	
						                    <li id="li2">
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)"><?php echo $Substitute; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="40%" nowrap><?php echo $ItemName; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="10%" nowrap><?php echo $BudgetQty; ?>  </th>
			                                                        <th width="10%" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Ordered; ?> </th>
			                                                        <th width="10%" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>

							            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
			                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
		                                                        <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="44%" nowrap><?php echo $ItemName; ?> </th>
			                                                        <th width="3%" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
			                                                        <th width="6%" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="6%" nowrap><?php echo $Ordered; ?> </th>
			                                                        <th width="9%" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="text" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';
						}
						else
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';
						}
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
					        "ajax": "<?php echo site_url('c_purchase/c_pi180c23/get_AllDataITM/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

				    	$('#example2').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataITMS/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});

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
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
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
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
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

					   	$("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk2']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
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
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var TTK_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var TTK_CODEx 	= "<?php echo $TTK_CODE; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_SN			= arrItem[6];
		ITM_UNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_VOLMB 		= arrItem[9];				// VOLUME BUDGET
		ITM_VOLMBG		= parseFloat(ITM_VOLMB);	// VOLUME BUDGET
		ITM_STOCK 		= arrItem[10];
		ITM_USED 		= arrItem[11];
		itemConvertion	= arrItem[12];
		TOT_BUDG		= arrItem[13];				// TOTAL BUDGET AMOUNT
		TOT_MAX_REQ		= arrItem[14];				// TOTAL MAX REQUEST
		TOT_USEBUDG		= arrItem[15];				// USED BY PO
		//ITM_BUDG		= arrItem[16];
		ITM_BUDG		= TOT_BUDG;					// TOTAL BUDGET AMOUNT
		TOT_USEDQTY		= arrItem[17];				// USED BY PR
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		intIndex 		= parseInt(objTable.rows.length) - 1;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'TTK_NUM" name="data['+intIndex+'][TTK_NUM]" value="'+TTK_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_CODE" name="data['+intIndex+'][TTK_CODE]" value="'+TTK_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;">';
		//console.log('c');
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// Item Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_VOLMBGx'+intIndex+'" id="ITM_VOLMBGx'+intIndex+'" value="'+ITM_VOLMBG+'" disabled ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_VOLMBG]" id="ITM_VOLMBG'+intIndex+'" value="'+ITM_VOLMBG+'" ><input type="hidden" id="data'+intIndex+'ITM_BUDG" name="data['+intIndex+'][ITM_BUDG]" value="'+ITM_BUDG+'">';
		
		// Item Requested FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_REQUESTED'+intIndex+'" id="ITM_REQUESTED'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx'+intIndex+'" id="ITM_REQUESTEDx'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="text-align:right" name="TOT_TTKQTY'+intIndex+'" id="TOT_TTKQTY'+intIndex+'" value="'+TOT_USEDQTY+'" ><input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_TTKQTY'+intIndex+'" id="TOT_TTKQTYx'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';
		
		// Item Request Now -- TTK_VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="TTK_VOLM'+intIndex+'" id="TTK_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][TTK_VOLM]" id="data'+intIndex+'TTK_VOLM" value="0.00" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][TTK_PRICE]" id="data'+intIndex+'TTK_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][TTK_TOTAL]" id="data'+intIndex+'TTK_TOTAL" value="'+TOT_BUDG+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
		
		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// Remarks -- TTK_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][TTK_DESC]" id="data'+intIndex+'TTK_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('ITM_VOLMBG'+intIndex).value
		document.getElementById('ITM_VOLMBG'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('ITM_VOLMBGx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		var ITM_REQUESTED											= document.getElementById('ITM_REQUESTED'+intIndex).value;
		document.getElementById('TOT_TTKQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('ITM_REQUESTED'+intIndex).value 	= parseFloat(Math.abs(ITM_REQUESTED));
		document.getElementById('ITM_REQUESTEDx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REQUESTED)),decFormat));
		document.getElementById('TOT_TTKQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('TOT_TTKQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)),decFormat));
		document.getElementById('totalrow').value = intIndex;
	}

	function add_TAXNO() 
	{
		var TTK_NUM			= '<?php echo $TTK_NUM; ?>';
		var TTKT_TAXNO 		= "";
		var TTKT_SPLINVNO 	= "";
		var TTKT_AMOUNT 	= 0;
		var TTKT_TAXAMOUNT 	= 0;
		
		textIn 				= "";
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrowTAX').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl_tax');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// Checkbox
			objTD 			= objTR.insertCell(objTR.cells.length);
			objTD.align 	= "center";
			objTD.noWrap 	= true;
			objTD.innerHTML = '<a href="#" onClick="deleteRowTAX('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// TTKT_DATE
			objTD 			= objTR.insertCell(objTR.cells.length);
			objTD.align 	= "center";
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_DATE" name="dataTAX['+intIndex+'][TTKT_DATE]" value="" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD" maxlength="10">';
			
		// TTKT_TAXNO
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_TAXNO" name="dataTAX['+intIndex+'][TTKT_TAXNO]" value="'+TTKT_TAXNO+'" class="form-control" onKeyUp="chkMask(this)" placeholder="XXX.XXX-XX.XXXXXXXX" maxlength="19" onChange="chkCont(this, '+intIndex+')">';
			
		// TTKT_SPLINVNO
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_SPLINVNO" name="dataTAX['+intIndex+'][TTKT_SPLINVNO]" value="'+TTKT_SPLINVNO+'" class="form-control" maxlength="100">';

		// TTKT_AMOUNT
			TTKT_AMOUNTV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_AMOUNT)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="TTKT_AMOUNT'+intIndex+'" value="'+TTKT_AMOUNTV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, '+intIndex+')"><input type="hidden" id="dataTAX'+intIndex+'TTKT_AMOUNT" name="dataTAX['+intIndex+'][TTKT_AMOUNT]" value="'+TTKT_AMOUNT+'" class="form-control" style="min-width:100px;">';

		// TTKT_TAXAMOUNT
			TTKT_TAXAMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_TAXAMOUNT)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="TTKT_TAXAMOUNT'+intIndex+'" value="'+TTKT_TAXAMOUNTV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgPPN(this, '+intIndex+')"><input type="hidden" id="dataTAX'+intIndex+'TTKT_TAXAMOUNT" name="dataTAX['+intIndex+'][TTKT_TAXAMOUNT]" value="'+TTKT_TAXAMOUNT+'" class="form-control" style="min-width:100px;">';
		
		document.getElementById('totalrowTAX').value = intIndex;
	}

	function chkDate(thisVal)
	{
		var totChar 	= thisVal.value.length;
		var theCont 	= thisVal.value;

		if(totChar == 4)
		{
			theYear 		= theCont;
			thisVal.value 	= thisVal.value+'-';
		}
		else if(totChar == 7)
		{
			theMonth 	= theCont.substring(5, 7);
			if(theMonth > 12)
			{
				swal("Masukan bulan dengan angka : 01 - 12")
				.then(function()
				{
					tYear 			= theCont.substring(0, 5);
					swal.close();
					thisVal.value 	= tYear;
					thisVal.focus();
					return false;
				})
			}
			else
			{
				thisVal.value 	= theCont+'-';
			}
		}
		else
		{
			theDate 	= theCont.substring(8, 10);
			if(theDate > 31)
			{
				swal("Masukan tanggal dengan angka : 01 - 31")
				.then(function()
				{
					tMonth 			= theCont.substring(0, 8);
					swal.close();
					thisVal.value 	= tMonth;
					thisVal.focus();
					return false;
				})
			}
			else
			{
				thisVal.value 	= theCont;
			}
		}
	}

	function chkMask(thisVal)
	{
		var totChar 	= thisVal.value.length;

		if(totChar == 3)
			thisValc 	= thisVal.value+'.';
		else if(totChar == 7)
			thisValc 	= thisVal.value+'-';
		else if(totChar == 10)
			thisValc 	= thisVal.value+'.';
		/*else if(totChar == 19)
		{
			swal("Melebihi batas maksimal penomoran pajak")
			.then(function()
			{
				swal.close();
				thisVal.focus();
				return false;
			})
		}*/
		else
			thisValc 	= thisVal.value;

		thisVal.value = thisValc;
	}

	function chkCont(thisVal, row)
	{
		if(thisVal.value == '000.000-00.00000000')
		{
			swal("Masukan nomo seri faktur pajak dengan benar.",
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				document.getElementById('dataTAX'+row+'TTKT_TAXNO').focus();
				document.getElementById('dataTAX'+row+'TTKT_TAXNO').value 	= '';
			})
		}
	}
	
	function chgUA(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTK_REF1_AM	= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTK_REF1_AM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_AM)),decFormat));
		document.getElementById('dataUA'+row+'TTK_REF1_AM').value 	= parseFloat(Math.abs(TTK_REF1_AM));
		changeValue_UA(TTK_REF1_AM, row)
	}
	
	function chgAMN(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTKT_AMOUNT		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTKT_AMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_AMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_AMOUNT').value 	= parseFloat(Math.abs(TTKT_AMOUNT));

		var TTKT_TAXAMOUNT 	= parseFloat(0.1 * TTKT_AMOUNT);
		
		document.getElementById('TTKT_TAXAMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_TAXAMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_TAXAMOUNT').value 	= parseFloat(Math.abs(TTKT_TAXAMOUNT));
	}
	
	function chgPPN(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTKT_TAXAMOUNT	= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTKT_TAXAMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_TAXAMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_TAXAMOUNT').value 	= parseFloat(Math.abs(TTKT_TAXAMOUNT));

		var TTKT_AMOUNT 	= parseFloat(10 * TTKT_TAXAMOUNT);
		
		document.getElementById('TTKT_AMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_AMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_AMOUNT').value 	= parseFloat(Math.abs(TTKT_AMOUNT));
	}
	
	function chgTAX(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTK_REF1_PPN	= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTK_REF1_PPN'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),decFormat));
		document.getElementById('data'+row+'TTK_REF1_PPN').value 	= parseFloat(Math.abs(TTK_REF1_PPN));
		changeValue_new(TTK_REF1_PPN, row)
	}

	function checkTotalTTK()
	{
		TTK_AMOUNT		= 0;
		TTK_AMOUNT_PPN	= 0;
		TTK_GTOTAL		= 0;
		TTK_AMOUNTAX	= 0;

		var varTAX = document.getElementById('totalrowTAX');
		if (typeof varTAX !== 'undefined' && varTAX !== null)
		{
			totalrowTAX		= document.getElementById('totalrowTAX').value;
			console.log('totalrowTAX = '+totalrowTAX)

			if(totalrowTAX == 0) 
			{
				swal('<?php echo $alert5; ?>',
				{
					icon: "warning",
				}).then(function()
				{
					swal.close();
					$('#chkTotal').prop('checked', false);
				});
				return false;
			}

			for(i=1; i<=totalrowTAX; i++)
			{
				let myObj 	= document.getElementById('dataTAX'+i+'TTKT_TAXAMOUNT');
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					TTKT_AMOUNT		= document.getElementById('dataTAX'+i+'TTKT_AMOUNT').value;
					TTKT_TAXAMOUNT	= document.getElementById('dataTAX'+i+'TTKT_TAXAMOUNT').value;
					TTK_AMOUNT 		= parseFloat(TTK_AMOUNT) + parseFloat(TTKT_AMOUNT);
					TTK_AMOUNTAX	= parseFloat(TTK_AMOUNTAX) + parseFloat(TTKT_TAXAMOUNT);
				}
			}
		}

		TTK_AMOUNT_PPN 		= parseFloat(TTK_AMOUNTAX);
		console.log('TTK_AMOUNT_PPN = '+TTK_AMOUNT_PPN)
		//document.getElementById('chkTotal').checked = true;

		if(TTK_AMOUNT_PPN == 0)
			var TTK_AMOUNT_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;
			
		console.log('d2')
		document.getElementById('TTK_AMOUNT').value 		= TTK_AMOUNT;
		document.getElementById('TTK_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT)),2));
		
		console.log('d3')
		document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_AMOUNT_PPN;
		document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_PPN)),2));

		TTK_GTOTAL			= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN);
		
		console.log('g = '+TTK_GTOTAL)
		document.getElementById('TTK_GTOTAL').value 	= TTK_GTOTAL;
		document.getElementById('TTK_GTOTALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		thisVal 			= eval(thisVal1).value.split(",").join("");
		itemConvertion		= document.getElementById('itemConvertion'+row).value;	
		TTK_PRICE			= parseFloat(document.getElementById('data'+row+'TTK_PRICE').value);			// Item Price
		ITM_VOLMBG			= eval(document.getElementById('ITM_VOLMBG'+row)).value.split(",").join("");	// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);				// Budget Amount
		TOT_TTKQTY			= eval(document.getElementById('TOT_TTKQTY'+row)).value.split(",").join("");		// Total Requested
		TOT_TTKAMOUNT			= parseFloat(TOT_TTKQTY) * parseFloat(TTK_PRICE);									// Total Requested Amount
		
		REQ_NOW_QTY1		= eval(document.getElementById('TTK_VOLM'+row)).value.split(",").join("");		// Request Qty - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(TTK_PRICE);								// Request Qty Amount - Now
		console.log(REQ_NOW_AMOUNT)

		// REMAIN
		REM_TTK_QTY			= parseFloat(ITM_VOLMBG) - parseFloat(TOT_TTKQTY);
		REM_TTK_AMOUNT		= parseFloat(ITM_BUDG) - parseFloat(TOT_TTKAMOUNT);

		if(REQ_NOW_QTY1 > REM_TTK_QTY)
		{
			REM_TTK_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_TTK_QTY)),decFormat));
			REM_TTK_AMOUNTV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_TTK_AMOUNT)),decFormat));
            swal('<?php echo $greaterBud; ?> '+REM_TTK_QTYV,
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'TTK_VOLM').value = REM_TTK_QTY;
			document.getElementById('TTK_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_TTK_QTY)),decFormat));
			return false;
		}
		
		console.log(REQ_NOW_AMOUNT)
		console.log(REQ_NOW_QTY1)
		document.getElementById('data'+row+'TTK_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('data'+row+'TTK_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('TTK_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat));

		document.getElementById('TTK_AMOUNT').value 			= REQ_NOW_AMOUNT;
		document.getElementById('TTK_AMOUNTX').value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

		REQ_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;
		GT_AMOUNT	= parseFloat(REQ_NOW_AMOUNT) + parseFloat(REQ_PPN);
		document.getElementById('TTK_GTOTAL').value 			= GT_AMOUNT;
		document.getElementById('TTK_GTOTALX').value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GT_AMOUNT)),decFormat));
	}
	
	function checkForm(value)
	{
		var totrowTAX 	= document.getElementById('totalrowTAX').value;
		var SPLCODE 	= document.getElementById('SPLCODE').value;
		var TTK_NOTES 	= document.getElementById('TTK_NOTES').value;
		var STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
		var TTK_STAT 	= document.getElementById('TTK_STAT').value;
		var chkTotal	= document.getElementById('chkTotal').checked;
		
		if(SPLCODE == "")
		{
			swal('<?php echo $alert3; ?>',
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
		
		if(TTK_NOTES == "")
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#TTK_NOTES').focus();
            });
			return false;
		}

		if(TTK_STAT != 6 && TTK_STAT != 9)
		{
			if(chkTotal == false)
			{
				swal('<?php echo $alert6; ?>',
				{
					icon: "warning",
				}).then(function()
				{
					swal.close();
					checkTotalTTK();
					$('#chkTotal').prop('checked', true);
				});
				return false;
			}
		}

		if(STAT_BEFORE == 1 || STAT_BEFORE == 4)
		{
			for(i=1;i<=totrowTAX;i++)
			{
				let TTKT_DATE 		= document.getElementById('dataTAX'+i+'TTKT_DATE').value;
				let TTKT_TAXNO 		= document.getElementById('dataTAX'+i+'TTKT_TAXNO').value;
				let TTKT_SPLINVNO 	= document.getElementById('dataTAX'+i+'TTKT_SPLINVNO').value;
				let TTKT_AMOUNT 	= document.getElementById('dataTAX'+i+'TTKT_AMOUNT').value;
				if(TTKT_DATE == "")
				{
					swal('<?php echo $alert7; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
		                $('#dataTAX'+i+'TTKT_DATE').focus();
		            });
					return false;
				}

				if(TTKT_TAXNO == "")
				{
					swal('<?php echo $alert8; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
		                $('#dataTAX'+i+'TTKT_TAXNO').focus();
		            });
					return false;
				}

				if(TTKT_SPLINVNO == "")
				{
					swal('<?php echo $alert9; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
		                $('#dataTAX'+i+'TTKT_SPLINVNO').focus();
		            });
					return false;
				}

				if(TTKT_AMOUNT == "" || TTKT_AMOUNT == 0)
				{
					swal('<?php echo $alert10; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
		                $('#TTKT_AMOUNT'+i).focus();
		            });
					return false;
				}
			}

			if(totrowTAX == 0)
			{
				swal('<?php echo $alert5; ?>',
				{
					icon: "warning",
				});
				return false;
			}
			else
			{
				var variable = document.getElementById('btnSave');
				if (typeof variable !== 'undefined' && variable !== null)
				{
					document.getElementById('btnSave').style.display 	= 'none';
				}

				document.frm.submit();
			}
		}
		else
		{
			//swal('Can not update this document. The document has Confirmed.');
			//return false;
		}
	}
  
	function doDecimalFormat(angka)
	{
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
	
	function RoundNDecimal(X, N)
	{
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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