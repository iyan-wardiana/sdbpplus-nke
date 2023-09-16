<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 25 November 2017
	* File Name	= v_vendor_form.php
	* Location		= -
*/
?>
<?php
// $this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];
$ISREAD 	= $this->session->userdata['ISREAD'];
$ISAPPROVE 	= $this->session->userdata('ISAPPROVE');
$ISCREATE 	= $this->session->userdata('ISCREATE');

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

if($task == 'add')
{
	/*foreach($viewDocPattern as $row) :
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
	$Pattern_Position	= 'Especially';	
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_supplier";
	$result = $this->db->query($sql)->result();
	
	foreach($result as $row) :
		$myMax = $row->maxNumber;
		$myMax = $myMax+1;
	endforeach;	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	$nol = '';
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
	$DocNumber = "SUPL$lastPatternNumb";*/
	
	$SPLCAT	= "U";
	if(isset($_POST['SPLCAT1']))
	{
		$SPLCAT		= $_POST['SPLCAT1'];
	}
	
	/*$sql 	= "tbl_supplier WHERE SPLCAT = 'U'";
	$result = $this->db->count_all($sql);*/
	
	/*$MAXNUM = 0;
	$s_00 	= "SELECT MAX(RIGHT(SPLCODE,5)) AS MAXNUM FROM tbl_supplier WHERE SPLCAT = '$SPLCAT'";
	$r_00 	= $this->db->query($s_00)->result();
	foreach($r_00 as $rw_00):
		$MAXNUM = (int)$rw_00->MAXNUM;
	endforeach;
	$myMax 	= $MAXNUM+1;*/

	$Pattern_Length	= 5;
	$sql 	= "SELECT RIGHT(SPLCODE, $Pattern_Length) AS KODE 
				FROM tbl_supplier 
				WHERE SPLCAT = '$SPLCAT'
				ORDER BY SPLCODE DESC LIMIT 1";
	$result = $this->db->query($sql);
	if($result->num_rows() > 0)
	{
		foreach($result->result() as $r):
			$KODE 	= intval($r->KODE);
		endforeach;
		$myMax 	= $KODE + 1;
	}
	else
	{
		$myMax = 1;
	}
	
	$Pattern_Length	= 5;
	$len = strlen($myMax);
	$nol = '';
	if($Pattern_Length==3)
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
	
	$lastPatternNumb = $nol.$myMax;
	$DocNumber 		= "$SPLCAT$lastPatternNumb";
	$Patt_Number	= $myMax;
	
	$SPLCODE 		= "$DocNumber";
	$SPLDESC		= '';
	$SPLCAT			= $SPLCAT;
	$SPLADD1		= '';
	$SPLKOTA		= '';
	$SPLNPWP		= '';
	$SPLPERS		= '';
	$SPLTELP		= '';
	$SPLMAIL		= '';
	$SPLNOREK		= '';
	$SPLSCOPE		= '';
	$SPLNMREK		= '';
	$SPLBANK		= '';
	$SPLOTHR		= '';
	$SPLOTHR2		= 1;
	$SPLTOP			= 0;
	$SPLTOPD		= 0;
	$SPLSTAT		= 0;
	$SPLUSERN		= '';
	$SPLPASSW 		= '';
	$SPLKTP 		= "";
	$SPLBNKBI 		= "";
	$SPL_INVACC 	= "";

	$TOT_PO 		= 0;
}
else
{
	$SPLCODE		= $default['SPLCODE'];
	$DocNumber 		= $SPLCODE;
	$SPLDESC		= stripslashes($default['SPLDESC']);
	$SPLCAT			= $default['SPLCAT'];
	if(isset($_POST['SPLCAT1']))
	{
		$SPLCAT		= $_POST['SPLCAT1'];
	}
	$SPLADD1		= $default['SPLADD1'];
	$SPLKOTA		= $default['SPLKOTA'];
	$SPLNPWP		= $default['SPLNPWP'];
	$SPLPERS		= $default['SPLPERS'];
	$SPLTELP		= $default['SPLTELP'];
	$SPLMAIL		= $default['SPLMAIL'];
	$SPLNOREK		= $default['SPLNOREK'];
	$SPLSCOPE		= $default['SPLSCOPE'];
	$SPLNMREK		= $default['SPLNMREK'];
	$SPLBANK		= $default['SPLBANK'];
	$SPLOTHR		= $default['SPLOTHR'];
	$SPLOTHR2		= $default['SPLOTHR2'];
	$SPLTOP			= $default['SPLTOP'];
	$SPLTOPD		= $default['SPLTOPD'];
	$SPLSTAT		= $default['SPLSTAT'];
	$SPLUSERN		= $default['SPLUSERN'];
	$SPLPASSW		= $default['SPLPASSW'];
	$SPLBNKBI		= $default['SPLBNKBI'];
	$SPLKTP			= $default['SPLKTP'];
	$TOT_PO			= $default['TOT_PO'];
	$SPL_INVACC		= $default['SPL_INVACC'];
	$Patt_Number	= $default['Patt_Number'];
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
            $vers 	= $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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

		<style type="text/css">
			.uploaded_area {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			}

			.file {
				display: grid;
				grid-template-columns: max-content 1fr;
				grid-template-areas: "iconfile titlefile"
									"iconfile actfile";
			}

			.iconfile {
				grid-area: iconfile;
				padding-right: 5px;
			}

			.titlefile {
				grid-area: titlefile;
				font-size: 8pt;
			}

			.actfile {
				grid-area: actfile;
				font-size: 8pt;
			}
		</style>
    </head>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'City')$City = $LangTransl;
			if($TranslCode == 'ContactPersonName')$ContactPersonName = $LangTransl;
			if($TranslCode == 'Email')$Email = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'FuncDesc')$FuncDesc = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'Scope')$Scope = $LangTransl;
			if($TranslCode == 'RekNo')$RekNo = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'BankName')$BankName = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PayTenor')$PayTenor = $LangTransl;
			if($TranslCode == 'Authorization')$Authorization = $LangTransl;
			if($TranslCode == 'Day')$Day = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'venCatEmpty')$venCatEmpty = $LangTransl;
			if($TranslCode == 'suplNmEmpty')$suplNmEmpty = $LangTransl;
			if($TranslCode == 'payInfonOth')$payInfonOth = $LangTransl;
			if($TranslCode == 'totPurch')$totPurch = $LangTransl;
			if($TranslCode == 'totPaid')$totPaid = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'NotPayment')$NotPayment = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'dokLam')$dokLam = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title	= "Supplier";
			$h2_title	= "Pembelian";
			$noAtth 	= "Tidak ada dokumen dilampirkan";
			$alert7 	= "Anda yakin akan menghapus file ini?";
		}
		else
		{
			$h1_title	= "Supplier";
			$h2_title	= "Purchase";
			$noAtth 	= "No document(s) attached";
			$alert7 	= "Are you sure want to delete this file?";
		}
		
		// START : APPROVE PROCEDURE
			$disButton	= 1;
			// DocNumber - PR_VALUE
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
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$disButton	= 1;
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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
				//$APPROVE_AMOUNT 	= $PR_VALUE;
				$APPROVE_AMOUNT	= 10000000000;
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
		<section class="content-header" style="display: none;">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/supplier_add.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $h2_title; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<?php
			$POT 	= 0;
			$sTOT	= "SELECT SUM(PO_TOTCOST) AS PO_T FROM tbl_po_header WHERE SPLCODE = '$SPLCODE'
						AND PO_STAT IN (3,6)";
			$qTOT	= $this->db->query($sTOT)->result();
			foreach($qTOT as $rTOT) :
				$POT	= $rTOT->PO_T;
			endforeach;

			$DEB_T 	= 0;
			$PO_P 	= 0;
			$sTOT	= "SELECT SUM(INV_AMOUNT) AS DEB_T, SUM(INV_AMOUNT_PAID) AS PO_P FROM tbl_pinv_header WHERE SPLCODE = '$SPLCODE'";
			$qTOT	= $this->db->query($sTOT)->result();
			foreach($qTOT as $rTOT) :
				$DEB_T	= $rTOT->DEB_T;
				$PO_P	= $rTOT->PO_P;
			endforeach;

			$PO_T 		= $POT;						// PO TOTAL
			$PO_I 		= $DEB_T;					// PO INVOICED
			$PO_P 		= $PO_P;					// PO PAID
			$PO_R 		= $PO_T - $PO_P;			// PO REMAIN

			$PO_TP 		= $PO_T;
			if($PO_T == 0)
				$PO_TP	= 1;

	        $PO_RPER	= number_format($PO_R / $PO_TP * 100, 2);		// PERSENTASE BELUM DIBAYAR
	        $PO_PPER	= number_format($PO_P / $PO_TP * 100, 2);		// PERSENTASE SUDAH DIBAYAR

            // ----------- HUTANG
            	// SEMENTARA FROM PO
	        	//$DEB_T 	= $TOT_PO;
	        	$PO_T 	= $PO_T;
				if($PO_T < 1000)									// Ratusan
	            {
	            	$PO_TV = number_format($PO_T / 1, 2);
	            	$DBTCOD	= "";
	            }
				elseif($PO_T < 1000000)							// Juta per Seribu
	            {
	            	$PO_TV = number_format($PO_T / 1000, 2);
	            	$DBTCOD	= " RB";
	            }
				elseif($PO_T < 1000000000)							// Miliar per Sejuta
	            {
	            	$PO_TV = number_format($PO_T / 1000000, 2);
	            	$DBTCOD	= " JT";
	            }
	            elseif($PO_T < 1000000000000)						// Triliun per Semiliar
	            {
	            	$PO_TV = number_format($PO_T / 1000000000, 2);
	            	$DBTCOD	= " M";
	            }
	            else
	            {
	            	$PO_TV = number_format($PO_T / 1000000000000, 2);
	            	$DBTCOD	= " T";
	            }
	            $POTV 		= $PO_TV.$DBTCOD;

            // ----------- DIBAYAR
				if($PO_P < 1000)									// Ratusan
	            {
	            	$PO_PV = number_format($PO_P / 1, 2);
	            	$DBPCOD	= "";
	            }
				elseif($PO_P < 1000000)							// Juta per Seribu
	            {
	            	$PO_PV = number_format($PO_P / 1000, 2);
	            	$DBPCOD	= " RB";
	            }
	            elseif($PO_P < 1000000000)							// Miliar per Sejuta
	            {
	            	$PO_PV = number_format($PO_P / 1000000, 2);
	            	$DBPCOD	= " JT";
	            }
	            elseif($PO_P < 1000000000000)						// Triliun per Semiliar
	            {
	            	$PO_PV = number_format($PO_P / 1000000000, 2);
	            	$DBPCOD	= " M";
	            }
	            else
	            {
	            	$PO_PV = number_format($PO_P / 1000000000000, 2);
	            	$DBPCOD	= " T";
	            }
	            $POPV 		= $PO_PV.$DBPCOD;

            // ----------- SISA
	            $PM 		= "";
	            if($PO_R < 0)
	            {
	            	$PM 	= "-";
	            	$PO_R 	= abs($PO_R);
	            }

				if($PO_R < 1000)
	            {
	            	$PO_RV = number_format($PO_R / 1, 2);
	            	$DBRCOD	= "";
	            }
				elseif($PO_R < 1000000)
	            {
	            	$PO_RV = number_format($PO_R / 1000, 2);
	            	$DBRCOD	= " RB";
	            }
	            elseif($PO_R < 1000000000)
	            {
	            	$PO_RV = number_format($PO_R / 1000000, 2);
	            	$DBRCOD	= " JT";
	            }
	            elseif($PO_R < 1000000000000)
	            {
	            	$PO_RV = number_format($PO_R / 1000000000, 2);
	            	$DBRCOD	= " M";
	            }
	            else
	            {
	            	$PO_RV = number_format($PO_R / 1000000000000, 2);
	            	$DBRCOD	= " T";
	            }
	            $PORV 		= $PM.$PO_RV.$DBRCOD;
		?>
		<section class="content">
			<div class="row">
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-blue-gradient"><i class="glyphicon glyphicon-user"></i></span>
						<div class="info-box-content">
							<span class="info-box-text" style="font-weight: bold;"><?php echo $SPLDESC; ?></span>
							<span class="info-box-text"><?php echo $SPLKOTA; ?></span>
							<span class="progress-description"><?php echo $SPLTELP; ?></span>
							<span class="progress-description"><?php echo $SPLMAIL; ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-red-gradient">
						<span class="info-box-icon"><i class="glyphicon glyphicon-th-list"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $totPurch; ?></span>
							<span class="info-box-number"><?php echo $POTV; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$PO_RPER?>%"></div>
							</div>
							<span class="progress-description">
								<?=$PO_RPER?> % <?php echo $NotPayment; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-green-gradient">
						<span class="info-box-icon"><i class="glyphicon glyphicon-ok"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $totPaid; ?></span>
							<span class="info-box-number"><?php echo $POPV; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$PO_PPER?>%"></div>
							</div>
							<span class="progress-description">
								<?=$PO_PPER?> % <?php echo $Paid; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-yellow-gradient">
						<span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $Remain; ?></span>
							<span class="info-box-number"><?php echo $PORV; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$PO_PPER?>%"></div>
							</div>
							<span class="progress-description">
								Total <?php echo $NotPayment; ?>
							</span>
						</div>
					</div>
				</div>
			</div>

		    <div class="row">
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                    <input type="text" name="SPLCAT1" id="SPLCAT1" value="<?php echo $SPLCAT; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <?php
                    $urlGetData	= base_url().'index.php/c_purchase/c_v3N/gLastCd/';
                    $urlCekName	= base_url().'index.php/c_purchase/c_v3N/chkName/';
                ?>
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                		<input type="hidden" name="Patt_Number" id="Patt_Number" class="form-control" value="<?php echo $Patt_Number; ?>" />
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="SPLCODE" id="SPLCODE" class="form-control" value="<?php echo $SPLCODE; ?>" />
		                            	<input type="text" name="SPLCODE1" id="SPLCODE1" class="form-control" value="<?php echo $SPLCODE; ?>" disabled />
		                            	<input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		                            	<input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
		                          	<div class="col-sm-9">
		                            	<select name="SPLCAT1" id="SPLCAT1" class="form-control select2" <?php if($task == 'add') { ?> onChange="selCAT(this.value)" <?php } else { ?> disabled <?php } ?>>
											<?php
			                                    $sql 	= "SELECT VendCat_Code, VendCat_Name FROM tbl_vendcat";
			                                    $result = $this->db->query($sql)->result();
			                                    $i 		= 0;
			                                    foreach($result as $row) :
													$SPLCAT2	= $row->VendCat_Code;
													$SPLDESC1	= $row->VendCat_Name;
			                                        ?>
			                                        <option value="<?php echo $SPLCAT2; ?>" <?php if($SPLCAT == $SPLCAT2) { ?> selected <?php } ?>>
			                                        <?php echo $SPLDESC1; ?></option>
			                                        <?php
			                                    endforeach;
			                                ?>
			                            </select>
										<input type="hidden" class="form-control" name="SPLCAT" id="SPLCAT" value="<?php echo $SPLCAT; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Name; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" name="SPLDESC1" id="SPLDESC1" value="<?php echo $SPLDESC; ?>" />
		                            	<input type="text" class="form-control" name="SPLDESC" id="SPLDESC" placeholder="<?php echo $SupplierName; ?>" value="<?php echo $SPLDESC; ?>" onChange="checkSPLNM(this.value)" />
		                          	</div>
		                        </div>
								<script>
		                            function selCAT(SPLCAT) 
		                            {
		                                document.getElementById("SPLCAT").value = SPLCAT;
		                                /*document.frmsrch1.submitSrch1.click();*/
		                                $.ajax({
		                                    type: 'POST',
		                                    url: '<?php echo $urlGetData; ?>',
		                                    data: $('#frm').serialize(),
		                                    success: function(response)
		                                    {
												console.log(response);
		                                        document.getElementById('SPLCODE').value 	= response;
		                                        document.getElementById('SPLCODE1').value 	= response;
		                                    }
		                                });

										// Sementara per tanggal 27-03-2023
										// if(SPLCAT != 'A')
										// {
										// 	$('#btnSave').css('display','');
										// }
										// else
										// {
										// 	$('#btnSave').css('display','none');
										// }
		                            }

		                            function checkSPLNM(checkSPLNM) 
		                            {
		                                /*document.getElementById("SPLCAT1").value = SPLCAT;
		                                document.frmsrch1.submitSrch1.click();*/
		                                $.ajax({
		                                    type: 'POST',
		                                    url: '<?php echo $urlCekName; ?>',
		                                    data: $('#frm').serialize(),
		                                    success: function(response)
		                                    {
		                                    	// swal(response)
		                                    	arrVar 	= response.split('~');
		                                    	stat 	= parseInt(arrVar[0]);
		                                    	alert 	= arrVar[1];
		                                    	if(stat > 0)
		                                    	{
		                                    		swal(alert,
		                                    		{
		                                    			icon:"warning",
		                                    		})
		                                    		.then(function()
		                                    		{
		                                    			swal.close();
				                                        $('#SPLDESC1').val('');
				                                        $('#SPLDESC').val('');
				                                        $('#SPLDESC').focus();
		                                    		});
		                                    		return false;
		                                    	}
		                                    }
		                                });
		                            }
		                        </script>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Scope; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLSCOPE" id="SPLSCOPE" placeholder="<?php echo $Scope; ?>" value="<?php echo "$SPLSCOPE"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $VendAddress; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="SPLADD1"  id="SPLADD1" style="height:65px" placeholder="<?php echo $VendAddress; ?>"><?php echo $SPLADD1; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $City; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLKOTA" id="SPLKOTA" placeholder="<?php echo $City; ?>"  value="<?php echo $SPLKOTA; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">NPWP</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="SPLNPWP" id="SPLNPWP" placeholder="NPWP" value="<?php echo $SPLNPWP; ?>" onkeypress="return isIntOnlyNew(event)" />
		                            </div>
		                            <label for="inputName" class="col-sm-1 control-label">KTP</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="SPLKTP" id="SPLKTP" placeholder="No. Identitas / KTP" value="<?php echo $SPLKTP; ?>"/>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ContactPersonName; ?></label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="SPLPERS" id="SPLPERS" placeholder="<?php echo $ContactPersonName; ?>" value="<?php echo $SPLPERS; ?>" />
		                            </div>
		                            <label for="inputName" class="col-sm-1 control-label">Telp.</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="SPLTELP" id="SPLTELP" placeholder="<?php echo $Phone; ?>" value="<?php echo $SPLTELP; ?>" onkeypress="return isIntOnlyNew(event)" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Email; ?></label>
		                            <div class="col-sm-9">
		                                <input type="email" class="form-control" name="SPLMAIL" id="SPLMAIL" placeholder="Email" value="<?php echo "$SPLMAIL"; ?>">
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-info-circle"></i>
								<h3 class="box-title"><?php echo $payInfonOth; ?></h3>
							</div>
							<div class="box-body" style="padding-bottom: 50px;">
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $RekNo; ?></label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="SPLNOREK" id="SPLNOREK" placeholder="<?php echo $RekNo; ?>" value="<?php echo "$SPLNOREK"; ?>" onkeypress="return isIntOnlyNew(event)" >
		                            </div>
		                            <label for="inputName" class="col-sm-2 control-label">Kode BI</label>
		                            <div class="col-sm-3">
		                                <input type="text" class="form-control" name="SPLBNKBI" id="SPLBNKBI" placeholder="Kode BI" value="<?php echo "$SPLBNKBI"; ?>" onkeypress="return isIntOnlyNew(event)" >
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $AccountName; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLNMREK" id="SPLNMREK" placeholder="<?php echo $AccountName; ?>" value="<?php echo "$SPLNMREK"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $BankName; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLBANK" id="SPLBANK" placeholder="<?php echo $BankName; ?>" value="<?php echo "$SPLBANK"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Others; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLOTHR" id="SPLOTHR" placeholder="Informasi Lain" value="<?php echo "$SPLOTHR"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $FuncDesc; ?></label>
		                          	<div class="col-sm-9">
		                            	<select name="SPLOTHR2" id="SPLOTHR2" class="form-control select2" onChange="getOth(this.value)">
		                                    <option value=""> -- </option>
		                                    <option value="Area Manager" <?php if($SPLOTHR2 == 'Area Manager') { ?> selected <?php } ?>>Area Manager</option>
		                                    <option value="Direktur" <?php if($SPLOTHR2 == 'Direktur') { ?> selected <?php } ?>>Direktur</option>
		                                    <option value="Direktur Operasional" <?php if($SPLOTHR2 == 'Direktur Operasional') { ?> selected <?php } ?>>Direktur Operasional</option>
		                                    <option value=">Direktur Utama" <?php if($SPLOTHR2 == '>Direktur Utama') { ?> selected <?php } ?>>Direktur Utama</option>
		                                    <option value="Marketing Manager" <?php if($SPLOTHR2 == 'Marketing Manager') { ?> selected <?php } ?>>Marketing Manager</option>
		                                    <option value="Pemilik Alat" <?php if($SPLOTHR2 == 'Pemilik Alat') { ?> selected <?php } ?>>Pemilik Alat</option>
		                                    <option value="Pribadi" <?php if($SPLOTHR2 == 'Pribadi') { ?> selected <?php } ?>>Pribadi</option>
		                                    <option value="Sales Manager" <?php if($SPLOTHR2 == 'Sales Manager') { ?> selected <?php } ?>>Sales Manager</option>
		                                </select>
		                          	</div>
		                        </div>
		                        <script>
									function getOth(SPLOTHR2)
									{
										if(SPLOTHR2 == 5)
										{
											//$("#SPLDESC").val() = $("#SPLPERS").val();
											document.getElementById('SPLDESC').value = document.getElementById('SPLPERS').value;
										}
										else
										{
											document.getElementById('SPLDESC').value = document.getElementById('SPLDESC1').value
										}
									}
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PaymentType ?> </label>
		                          	<div class="col-sm-5">
		                                <select name="SPLTOP" id="SPLTOP" class="form-control select2" >
		                                    <option value="0" <?php if($SPLTOP == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="1" <?php if($SPLTOP == 1) { ?> selected <?php } ?>>Kredit</option>
		                                </select>
		                            </div>
		                          	<div class="col-sm-4">
		                                <select name="SPLTOPD" id="SPLTOPD" class="form-control select2" >
		                                    <option value="0" <?php if($SPLTOPD == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($SPLTOPD == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="14" <?php if($SPLTOPD == 14) { ?> selected <?php } ?>>15 Days</option>
		                                    <option value="21" <?php if($SPLTOPD == 21) { ?> selected <?php } ?>>21 Days</option>
		                                    <option value="30" <?php if($SPLTOPD == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($SPLTOPD == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($SPLTOPD == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="101" <?php if($SPLTOPD == 101) { ?> selected <?php } ?>>Back to Back</option>
		                                    <option value="120" <?php if($SPLTOPD == 102) { ?> selected <?php } ?>>Turn Key</option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Authorization ?> </label>
		                            <div class="col-sm-5">
		                                <input type="text" class="form-control" name="SPLUSERN" id="SPLUSERN" placeholder="Username" value="" readonly>
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="password" class="form-control" name="SPLPASSW" id="SPLPASSW" placeholder="Password" value="" readonly>
		                            </div>
		                        </div>
        						<?php
                                    $s_00		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
                                    $r_00 		= $this->db->query($s_00)->result();
                                    foreach($r_00 as $rw_00):
                                    	$PRJCODE = $rw_00->PRJCODE;
                                    endforeach;
                                    
                                    $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
        												Acc_DirParent, isLast
                                                    FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Category IN (1,2) ORDER BY ORD_ID ASC";
                                    $resC0b1 	= $this->db->query($sqlC0b)->result();
                                ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label" title="Link Account saat pembuatan voucher">LA. Voucher</label>
		                            <div class="col-sm-9">
		                                <select name="SPL_INVACC" id="SPL_INVACC" class="form-control select2">
                                			<option value="" > --- </option>
                                            <?php
        										foreach($resC0b1 as $rowC0b) :
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
        											
        											?>
        												<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $SPL_INVACC) { ?> selected <?php } if($disbaled_0 > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa1$Account_Name0"; ?></option>
        											<?php
        										endforeach;
        									?>
                                        </select>
		                            </div>
		                        </div>
								<?php
									// START : Approver yang ada disetting
										$APPROVER_1 	= "";
										$APPROVER_2 	= "";
										$Approver1_Nm	= "";
										$Approver2_Nm	= "";
										$app1Name 		= "&nbsp;";
										$app2Name 		= "&nbsp;";
										$boxCol_1 		= "danger";
										$boxCol_2 		= "danger";
										$st1class 		= "glyphicon glyphicon-remove-sign";
										$st2class 		= "glyphicon glyphicon-remove-sign";
										$val1App 		= 1;
										$val2App 		= 1;

										$s_EMP_APP 		= "SELECT APPROVER_1, APPROVER_2 FROM tbl_supplier_approver";
										$r_EMP_APP		= $this->db->query($s_EMP_APP)->result();
										foreach($r_EMP_APP as $rw_EMP_APP) :
											$APPROVER_1		= $rw_EMP_APP->APPROVER_1;
											$APPROVER_2		= $rw_EMP_APP->APPROVER_2;

						                    $s_app_1	= "SELECT CONCAT(First_Name,' ', Last_Name) AS ComplName
						                    				FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' LIMIT 1";
											$r_app_1	= $this->db->query($s_app_1)->result();
											foreach($r_app_1 as $rw_app_1) :
												$Approver1_Nm	= $rw_app_1->ComplName;
											endforeach;

						                    $s_app_2	= "SELECT CONCAT(First_Name,' ', Last_Name) AS ComplName
						                    				FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' LIMIT 1";
											$r_app_2	= $this->db->query($s_app_2)->result();
											foreach($r_app_2 as $rw_app_2) :
												$Approver2_Nm	= $rw_app_2->ComplName;
											endforeach;

											$app1Name 	= $Approver1_Nm;
											$app2Name 	= $Approver2_Nm;
										endforeach;
									// END : Approver yang ada disetting

									$canAPP1 		= 0;
									$canAPP2 		= 0;
									$chgStat1D 		= "";
									$chgStat2D 		= "";
									$isDis1 		= "disabled";
									$isDis2 		= "disabled";
									if($APPROVER_1 == $DefEmp_ID)
									{
										$canAPP1 	= 1;
										$chgStat1D 	= "chgStat1(1)";
										$isDis1		= "";
									}

									if($APPROVER_2 == $DefEmp_ID)
									{
										$canAPP2 	= 1;
										$chgStat2D 	= "chgStat2(1)";
										$isDis2		= "";
									}

									// START : Approver yang sudah menyetujui
										$app1Stat 		= 0;
										$app2Stat 		= 0;
										$Approved1_Nm	= "";
										$Approved2_Nm	= "";

					                    $s_appHist	= "SELECT * FROM tbl_supplier_app WHERE SPLCODE = '$SPLCODE'";
										$r_appHist	= $this->db->query($s_appHist)->result();
										foreach($r_appHist as $rw_appHist) :
											$APPROVER1		= $rw_appHist->APPROVER_1;
											$APPROVER2		= $rw_appHist->APPROVER_2;
											$APPROVED1		= $rw_appHist->APPROVED_1;
											$APPROVED2		= $rw_appHist->APPROVED_2;

						                    $s_app_1	= "SELECT CONCAT(First_Name,' ', Last_Name) AS ComplName
						                    				FROM tbl_employee WHERE Emp_ID = '$APPROVER1' LIMIT 1";
											$r_app_1	= $this->db->query($s_app_1)->result();
											foreach($r_app_1 as $rw_app_1) :
												$Approved1_Nm	= $rw_app_1->ComplName;
												$app1Name 		= $Approved1_Nm;
												$app1Stat 		= 1;
												$val1App 		= 0;
												$boxCol_1 		= "success";
												$st1class 		= "glyphicon glyphicon-ok";
												$chgStat1D 		= "chgStat1(0)";
												$isDis1			= "";
											endforeach;

						                    $s_app_2	= "SELECT CONCAT(First_Name,' ', Last_Name) AS ComplName
						                    				FROM tbl_employee WHERE Emp_ID = '$APPROVER2' LIMIT 1";
											$r_app_2	= $this->db->query($s_app_2)->result();
											foreach($r_app_2 as $rw_app_2) :
												$Approved2_Nm	= $rw_app_2->ComplName;
												$app2Name 		= $Approved2_Nm;
												$app2Stat 		= 1;
												$val2App 		= 0;
												$boxCol_2 		= "success";
												$st2class 		= "glyphicon glyphicon-ok";
												$chgStat2D 		= "chgStat2(0)";
												$isDis2			= "";
											endforeach;
										endforeach;
									// START : Approver yang sudah menyetujui

									if($APPROVER_1 != $DefEmp_ID)
										$isDis1		= "disabled";

									if($APPROVER_2 != $DefEmp_ID)
										$isDis2		= "disabled";
				                ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
									<div class="col-sm-4">
				                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_1; ?>" onClick="<?=$chgStat1D?>" <?=$isDis1?>>
							                <i class="<?=$st1class?>"></i> <?php echo cut_text ("$app1Name", 20); ?>
							            </a>
		                            </div>

									<div class="col-sm-4">
				                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_2; ?>" onClick="<?=$chgStat2D?>" <?=$isDis2?>>
							                <i class="<?=$st2class?>"></i> <?php echo cut_text ("$app2Name", 20); ?>
							            </a>
		                            </div>
		                        </div>
		                        <?php
		                        	$chgSTAT1 	= base_url().'index.php/c_purchase/c_v3N/chgSTAT1/';
		                        	$chgSTAT2 	= base_url().'index.php/c_purchase/c_v3N/chgSTAT2/';
		                        ?>
		                        <script type="text/javascript">
		                        	function chgStat1(chgVal)
		                        	{
		                        		var SPLCODE		= document.getElementById('SPLCODE').value;
		                        		var SPLSTAT 	= chgVal;
		                        		var APPROVER 	= "<?=$APPROVER_1?>";
		                        		var collID 		= SPLCODE+'~'+SPLSTAT+'~'+APPROVER;
										$.ajax({
						                    type: 'POST',
						                    url: '<?php echo $chgSTAT1; ?>',
						                    data: {collID: collID},
						                    success: function(msg)
						                    {
						                    	//console.log(msg)
						                        location.reload();
						                    }
						                });
		                        	}

		                        	function chgStat2(chgVal)
		                        	{
		                        		var SPLCODE		= document.getElementById('SPLCODE').value;
		                        		var SPLSTAT 	= chgVal;
		                        		var APPROVER 	= "<?=$APPROVER_2?>";
		                        		var collID 		= SPLCODE+'~'+SPLSTAT+'~'+APPROVER;
										$.ajax({
						                    type: 'POST',
						                    url: '<?php echo $chgSTAT2; ?>',
						                    data: {collID: collID},
						                    success: function(msg)
						                    {
						                    	//console.log(msg)
						                        location.reload();
												$('#SPLSTAT').val(1);
						                    }
						                });
		                        	}
		                        </script>
	                            <?php
				                	if($resCAPP == 0)
				                	{
				                		?>
					                        <div class="form-group" style="display: none;">
					                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
					                          	<div class="col-sm-9">
					                                <input type="text" name="SPLSTAT" id="SPLSTAT" class="form-control" value="<?php echo $SPLSTAT; ?>" />
					                            </div>
					                        </div>
					                    <?php
					                }
					                else
					                {
				                		?>
					                        <div class="form-group">
					                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
					                          	<div class="col-sm-9">
					                                <select name="SPLSTAT1" id="SPLSTAT1" class="form-control select2" >
					                                    <option value="1" <?php if($SPLSTAT == 1) { ?> selected <?php } ?>><?php echo $Active ?></option>
					                                    <option value="0" <?php if($SPLSTAT == 0) { ?> selected <?php } ?>><?php echo $Inactive ?></option>
					                                </select>
					                                <input type="hidden" name="SPLSTAT" id="SPLSTAT" class="form-control" value="<?php echo $SPLSTAT; ?>" />
					                            </div>
					                        </div>
					                    <?php
					                }
					            ?>
		                    </div>
		                </div>
		            </div>
					<?php
						$shAttc 	= 0;
						if($SPLSTAT == 0)
						{
							$shAttc = 1;
							$shTInp = 1;
							$smTAtt = 4;
							$smTDok = 8;
						}
						else
						{
							$shTInp = 0;
							$smTAtt = 4;
							$smTDok = 12;
							$getUPL_DOC = "SELECT * FROM tbl_upload_filespl WHERE REF_NUM = '$SPLCODE'";
							$resUPL_DOC = $this->db->query($getUPL_DOC);
							if($resUPL_DOC->num_rows() > 0)
								$shAttc = 1;
						}
					?>

					<div class="col-md-12" <?php if($shAttc == 0) { ?> style="display: none;" <?php } ?>>
						<div class="box box-default">
							<div class="box-header with-border">
								<label for="inputName"><?php echo $dokLam; ?></label>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<div class="col-sm-4" <?php if($shTInp == 0) { ?> style="display: none;" <?php } ?>>
										<input type="file" class="form-control" name="userfile[]" id="userfile" accept=".pdf" multiple>
										<span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span>
									</div>
									<div class="col-sm-<?=$smTDok?>">
										<?php
											// GET Upload Doc TRx
											$getUPL_DOC = "SELECT * FROM tbl_upload_filespl WHERE REF_NUM = '$SPLCODE'";
											$resUPL_DOC = $this->db->query($getUPL_DOC);
											if($resUPL_DOC->num_rows() > 0)
											{
												?>
													<label>List Uploaded</label>
													<div class="uploaded_area">
												<?php
													$newRow = 0;
													foreach($resUPL_DOC->result() as $rDOC):
														$newRow 		= $newRow + 1;
														$UPL_NUM		= $rDOC->UPL_NUM;
														$REF_NUM		= $rDOC->REF_NUM;
														$UPL_SPLCODE	= $rDOC->REF_NUM;
														$UPL_DATE		= $rDOC->UPL_DATE;
														$UPL_FILENAME	= $rDOC->UPL_FILENAME;
														$UPL_FILESIZE	= $rDOC->UPL_FILESIZE;
														$UPL_FILETYPE	= $rDOC->UPL_FILETYPE;

														?>
															<div class="itemFile_<?=$newRow?>">
																<?php
																	if($UPL_FILETYPE == 'application/pdf') $fileicon = "fa-file-pdf-o";
																	else $fileicon = "fa-file-image-o";

																	if($SPLSTAT == 0)
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- Hapus File -->
																					<a href="#" onclick="trashItemFile(<?=$newRow?>, '<?php echo $UPL_FILENAME;?>')" title="Hapus File">
																						<i class="fa fa-trash" style="color: red;"></i> Delete
																					</a> 
																					&nbsp;&nbsp;&nbsp;
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<!-- Download File -->
																					<a href="<?php echo site_url("c_purchase/c_v3N/downloadFile/?file=".$UPL_FILENAME."&SPLCODE=".$UPL_SPLCODE); ?>" title="Download File">
																						<i class="fa fa-download" style="color: green;"></i> Download
																					</a>
																				</div>
																			</div>
																			
																		<?php
																	}
																	else
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<a href="<?php echo site_url("c_purchase/c_v3N/downloadFile/?file=".$UPL_FILENAME."&SPLCODE=".$UPL_SPLCODE); ?>" title="Download File">
																						<i class="fa fa-download" style="color: green;"></i> Download
																					</a>
																				</div>
																			</div>
																		<?php
																	}
																?>
															</div>
														<?php
													endforeach;

												?>
													</div>
												<?php
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12" <?php if($shAttc == 1) { ?> style="display: none;" <?php } ?>>
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-ban"></i> <?=$noAtth?>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
							<div class="col-sm-9">
								<?php
									if($SPLSTAT == 0)
									{
										if($task=='add')
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										else
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									elseif($ISAPPROVE == 1)
									{
										?>
											<button class="btn btn-primary" id="btnSave">
											<i class="fa fa-save"></i></button>&nbsp;
										<?php
									}
								
							
								//echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
								?>
								<button class="btn btn-danger" id="tblClose" type="button"><i class="fa fa-reply"></i></button>
								<?php
									if($LangID == 'IND')
									{
										$alertCls1  = "Anda yakin?";
										$alertCls2  = "Sistem akan mengosongkan data inputan Anda.";
										$alertCls3  = "Data Anda aman.";
									}
									else
									{
										$alertCls1  = "Are you sure?";
										$alertCls2  = "The system will empty the data you entered.";
										$alertCls3  = "Your data is safe.";
									}
								?>
								<script type="text/javascript">
									$('#tblClose').on('click',function(e) 
									{
										// swal({
										//       title: "<?php echo $alertCls1; ?>",
										//       text: "<?php echo $alertCls2; ?>",
										//       //icon: "warning",
										//       buttons: ["No", "Yes"],
										//       dangerMode: true,
										//     })
										//     .then((willDelete) => {
										//     if (willDelete) 
										//     {
										//         window.location = "<?php echo $backURL; ?>";
										//     } else {
										//         swal("<?php echo $alertCls3; ?>", {icon: "success"})
										//     }
										// });
										window.location = "<?php echo $backURL; ?>";
									});
								</script>
							</div>
						</div>
					</div>
		        </form>
		    </div>
        	<?php
        		$DefID 		= $this->session->userdata['Emp_ID'];
				$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
</script>

<script>
	function checkInp()
	{
		SPLCAT = document.getElementById('SPLCAT').value;
		if(SPLCAT == 0)
		{
			swal('<?php echo $venCatEmpty; ?>',
			{
				icon: "warning",
			});
			return false;			
		}

		SPLDESC = document.getElementById('SPLDESC').value;
		if(SPLDESC == '')
		{
			swal('<?php echo $suplNmEmpty; ?>',
			{
				icon: "warning",
			});
			return false;			
		}
	}
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function trashItemFile(row, fileName)
	{		
		swal({
            text: "<?php echo $alert7; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        }).then((willDelete) => {
			if (willDelete) {
				let SPLCODE	= "<?php echo $SPLCODE; ?>";
				$.ajax({
					type: "POST",
					url: "<?php echo site_url("c_purchase/c_v3N/trashFile"); ?>",
					data: {SPLCODE:SPLCODE, fileName:fileName},
					beforeSend: function(xhr) {
						console.log(xhr);
					},
					success: function(callback) {
						console.log(callback);
						swal("File has been deleted!", {icon: "success",});
						$('.itemFile_'+row).remove();
					},
				});
			}
			else {
				swal("Your file is safe!");
			}
		});
	}

	function viewFile(fileName)
	{
		const url 		= "<?php echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		const urlOpen	= "<?php echo base_url(); ?>";
		const urlDom	= "<?php echo "https://sdbpplus.nke.co.id/"; ?>";
		let SPLCODE 	= "<?php echo $SPLCODE; ?>";
		let path 		= "SUPPLIER/"+SPLCODE+"/"+fileName+"";
		let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen+'&base_urlDom='+urlDom;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		let left = (screen.width/2)-(w/2);
		let top = (screen.height/2)-(h/2);
		return window.open(url+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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