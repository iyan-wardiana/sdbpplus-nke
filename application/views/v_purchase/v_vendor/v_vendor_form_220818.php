<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 25 November 2017
	* File Name	= v_vendor_form.php
	* Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

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
	
	$sql 	= "tbl_supplier WHERE SPLCAT = 'U'";
	$result = $this->db->count_all($sql);
	$myMax 	= $result+1;
	
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
	$SPLSTAT		= 1;
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
	$SPLDESC		= $default['SPLDESC'];
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
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title	= "Supplier";
			$h2_title	= "Pembelian";
		}
		else
		{
			$h1_title	= "Supplier";
			$h2_title	= "Purchase";
		}
		
		// START : APPROVE PROCEDURE
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
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
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
		                            	<select name="SPLCAT" id="SPLCAT" class="form-control select2" <?php if($task == 'add') { ?> onChange="selCAT(this.value)" <?php } ?>>
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
		                                /*document.getElementById("SPLCAT1").value = SPLCAT;
		                                document.frmsrch1.submitSrch1.click();*/
		                                $.ajax({
		                                    type: 'POST',
		                                    url: '<?php echo $urlGetData; ?>',
		                                    data: $('#frm').serialize(),
		                                    success: function(response)
		                                    {
		                                        document.getElementById('SPLCODE').value 	= response;
		                                        document.getElementById('SPLCODE1').value 	= response;
		                                    }
		                                });
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
		                                    	swal(response)
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
							<div class="box-body">
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
				                    $DOC_NUM	= $SPLCODE;
				                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
				                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
									$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
													AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj)";
									$resAPP		= $this->db->query($sqlAPP)->result();
									foreach($resAPP as $rowAPP) :
										$MAX_STEP		= $rowAPP->MAX_STEP;
										$APPROVER_1		= $rowAPP->APPROVER_1;
										$APPROVER_2		= $rowAPP->APPROVER_2;
										$APPROVER_3		= $rowAPP->APPROVER_3;
										$APPROVER_4		= $rowAPP->APPROVER_4;
										$APPROVER_5		= $rowAPP->APPROVER_5;
									endforeach;
				                ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <?php
					                	if($resCAPP == 0)
					                	{
					                		if($LangID == 'IND')
											{
												$zerSetApp	= "Belum ada pengaturan persetujuan.";
											}
											else
											{
												$zerSetApp	= "There are no arrangements for the approval.";
											}
					                		?>
								              	<div class="col-sm-9">
							                        <div class="alert alert-warning alert-dismissible">
										                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										                <?php echo $zerSetApp; ?>
									              	</div>
					                            </div>
					                		<?php
					                	}
					                	else
					                	{
											$SHOWOTH		= 0;
											$AH_ISLAST		= 0;
											$APPROVER_1A	= 0;
											$APPROVER_2A	= 0;
											$APPROVER_3A	= 0;
											$APPROVER_4A	= 0;
											$APPROVER_5A	= 0;
			                                if($APPROVER_1 != '')
			                                {
			                                    $boxCol_1	= "danger";
			                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
			                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
			                                    if($resCAPPH_1 > 0)
			                                    {
			                                        $boxCol_1	= "success";
			                                        $Approver	= $Approved;
			                                        $class		= "glyphicon glyphicon-ok-sign";
			                                        $SPLSTAT 	= 1;
			                                        
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
													$SPLSTAT 	= 0;
													
													$sqlCAPPH_1A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
													$resCAPPH_1A	= $this->db->count_all($sqlCAPPH_1A);
													if($resCAPPH_1A > 0)
													{
														$SHOWOTH	= 1;
														$APPROVER_1A= 1;
														$EMPN_1A	= '';
														$AH_ISLAST1A=0;
														$APPROVED_1A= '0000-00-00';
														$boxCol_1A	= "success";
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
													<div class="col-sm-4">
								                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_1; ?>">
											                <i class="<?=$class?>"></i> <?php echo cut_text ("$EMPN_1", 20); ?>
											            </a>
						                            </div>
												<?php
			                                }
			                                if($APPROVER_2 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_2	= "danger";
			                                    $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
			                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
			                                    if($resCAPPH_2 > 0)
			                                    {
			                                        $boxCol_2	= "success";
			                                        $class		= "glyphicon glyphicon-ok-sign";
			                                        $SPLSTAT 	= 1;
			                                        
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
			                                        $SPLSTAT 	= 0;
													
													$sqlCAPPH_2A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
													$resCAPPH_2A	= $this->db->count_all($sqlCAPPH_2A);
													if($resCAPPH_2A > 0)
													{
														$APPROVER_2A= 1;
														$EMPN_2A	= '';
														$AH_ISLAST2A=0;
														$APPROVED_2A= '0000-00-00';
														$boxCol_2A	= "success";
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
													<div class="col-sm-4">
								                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_2; ?>">
											                <i class="<?=$class?>"></i> <?php echo cut_text ("$EMPN_2", 20); ?>
											            </a>
						                            </div>
												<?php
			                                }
			                                if($APPROVER_3 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_3	= "danger";
			                                    $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
			                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
			                                    if($resCAPPH_3 > 0)
			                                    {
			                                        $boxCol_3	= "success";
			                                        $class		= "glyphicon glyphicon-ok-sign";
			                                        $SPLSTAT 	= 1;
			                                        
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
			                                        $SPLSTAT 	= 0;
													
													$sqlCAPPH_3A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
													$resCAPPH_3A	= $this->db->count_all($sqlCAPPH_3A);
													if($resCAPPH_3A > 0)
													{
														$APPROVER_3A= 1;
														$EMPN_3A	= '';
														$AH_ISLAST3A=0;
														$APPROVED_3A= '0000-00-00';
														$boxCol_3A	= "success";
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
													<div class="col-sm-4">
								                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_3; ?>">
											                <i class="glyphicon glyphicon-ok"></i> <?php echo cut_text ("$EMPN_3", 20); ?>
											            </a>
						                            </div>
												<?php
			                                }
			                                if($APPROVER_4 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_4	= "danger";
			                                    $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
			                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
			                                    if($resCAPPH_4 > 0)
			                                    {
			                                        $boxCol_4	= "success";
			                                        $class		= "glyphicon glyphicon-ok-sign";
			                                        $SPLSTAT 	= 1;
			                                        
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
			                                        $SPLSTAT 	= 0;
													
													$sqlCAPPH_4A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
													$resCAPPH_4A	= $this->db->count_all($sqlCAPPH_4A);
													if($resCAPPH_4A > 0)
													{
														$APPROVER_4A= 1;
														$EMPN_4A	= '';
														$AH_ISLAST4A=0;
														$APPROVED_4A= '0000-00-00';
														$boxCol_4A	= "success";
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
													<div class="col-sm-4">
								                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_4; ?>">
											                <i class="glyphicon glyphicon-ok"></i> <?php echo cut_text ("$EMPN_4", 20); ?>
											            </a>
						                            </div>
												<?php
			                                }
			                                if($APPROVER_5 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_5	= "danger";
			                                    $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
			                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
			                                    if($resCAPPH_5 > 0)
			                                    {
			                                        $boxCol_5	= "success";
			                                        $class		= "glyphicon glyphicon-ok-sign";
			                                        $SPLSTAT 	= 1;
			                                        
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
			                                        $SPLSTAT 	= 0;
													
													$sqlCAPPH_5A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
													$resCAPPH_5A	= $this->db->count_all($sqlCAPPH_5A);
													if($resCAPPH_5A > 0)
													{
														$APPROVER_5A= 1;
														$EMPN_5A	= '';
														$AH_ISLAST5A=0;
														$APPROVED_5A= '0000-00-00';
														$boxCol_5A	= "success";
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
													<div class="col-sm-4">
								                        <a class="btn btn-block btn-social btn-<?php echo $boxCol_5; ?>">
											                <i class="glyphicon glyphicon-ok"></i> <?php echo cut_text ("$EMPN_5", 20); ?>
											            </a>
						                            </div>
												<?php
			                                }
			                            }
		                            ?>
		                        </div>
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
					                                <select name="SPLSTAT" id="SPLSTAT" class="form-control select2" >
					                                    <option value="1" <?php if($SPLSTAT == 1) { ?> selected <?php } ?>><?php echo $Active ?></option>
					                                    <option value="0" <?php if($SPLSTAT == 0) { ?> selected <?php } ?>><?php echo $Inactive ?></option>
					                                </select>
					                            </div>
					                        </div>
					                    <?php
					                }
					            ?>
		                        <div class="form-group">
		                        	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
		                              	<?php
									
											if($task=='add')
											{
												?>
													<button class="btn btn-primary" style="display:none;">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary"  style="display:none;">
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