<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 18 Maret 2018
	* File Name	= inb_itemusage_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$PRJSCATEG 	= $this->session->userdata['PRJSCATEG'];

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

$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$currentRow 		= 0;
$UM_NUM				= $default['UM_NUM'];
$DocNumber			= $default['UM_NUM'];
$UM_CODE			= $default['UM_CODE'];
$UM_TYPE			= $default['UM_TYPE'];
$UM_USER			= $default['UM_USER'];
$SPLCODE			= $default['SPLCODE'];
$PRJDEST			= $default['PRJDEST'];
$UM_NOREF			= $default['UM_NOREF'];
$UM_SPLNOTES		= $default['UM_SPLNOTES'];
$UM_DATED			= $default['UM_DATE'];
$JournalY 			= date('Y', strtotime($UM_DATED));
$JournalM 			= date('n', strtotime($UM_DATED));
// $UM_DATE			= date('m/d/Y',strtotime($UM_DATED));
$UM_DATE			= date('d/m/Y',strtotime($UM_DATED));
$PRJCODE			= $default['PRJCODE'];
$UM_NOTE			= $default['UM_NOTE'];
$WH_CODE			= $default['WH_CODE'];
$UM_NOTE2			= $default['UM_NOTE2'];
$UM_STAT			= $default['UM_STAT'];
$WH_CODE			= $default['WH_CODE'];
$JOBCODEID			= $default['JOBCODEID'];
$Patt_Year			= $default['Patt_Year'];
$Patt_Number		= $default['Patt_Number'];
$lastPatternNumb1	= $default['Patt_Number'];

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;

$dataSessSrc = array(
		'selSearchproj_Code' => $PRJCODE,
		'selSearchType' => $this->input->post('selSearchType'),
		'txtSearch' => $this->input->post('txtSearch'));
$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
$this->session->set_userdata('dtSessSrc2', $dataSessSrc);

$JOBD 		= "";
$sqlJobD	= "SELECT JOBDESC FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
$resJobD	= $this->db->query($sqlJobD)->result();
foreach($resJobD as $rowJD) :
    $JOBD 	= $rowJD->JOBDESC;
endforeach;
$JOBCODEIDD	= "$JOBCODEID : $JOBD";

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

		<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
			if($TranslCode == 'UsageCode')$UsageCode = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'BudName')$BudName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'selfUsed')$selfUsed = $LangTransl;
			if($TranslCode == 'usedTP')$usedTP = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;

			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'WHLocation')$WHLocation = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$subTitleH	= "Tambah";
			$subTitleD	= "penggunaan materil";
			$alert2		= "Silahkan pilih status persetujuan...!";
			$alertAcc 	= "Belum diset kode akun penggunaan.";
			$alertAccIR = "Belum diset kode akun penerimaan.";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$subTitleH	= "Add";
			$subTitleD	= "material usage";
			$alert2		= "Please select approval status...!";
			$alertAcc 	= "Not set account material usage.";
			$alertAccIR = "Not set account material receipt.";
		}
		
		$secAddURL	= site_url('c_inventory/c_iu180c16/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_inventory/c_iu180c16/genCode/'; // Generate Code
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
		
		// START : APPROVE PROCEDURE
			$EMPN_1 	= "";
			$EMPN_2 	= "";
			$EMPN_3 	= "";
			$EMPN_4 	= "";
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
				
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$UM_NUM'";
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
				$APPROVE_AMOUNT = 10000000000;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				$DOC_NUM		= $UM_NUM;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
					else
					{
						// CEK CURRENT APPROVER
							$BEF_STEP_APP	= $BefStepApp;
							$CURR_STEP_APP	= $APP_STEP;
							$MAX_STEP_APP	= $MAX_STEP;
						if(($CURR_STEP_APP > $BEF_STEP_APP) && ($BEF_STEP_APP > 0))
						{
							// CEK SIAPA APPROVER SEBELUMNYA
							$APPROVER_BEF	= '';
							$sqlBEFAPP		= "SELECT APPROVER_1 FROM tbl_docstepapp_det 
												WHERE 
													MENU_CODE = '$MenuApp'
													AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')
													AND APP_STEP = $BEF_STEP_APP
													AND APPLIMIT_1 > $APPROVE_AMOUNT";
							$resBEFAPP		= $this->db->query($sqlBEFAPP)->result();
							foreach($resBEFAPP as $rowBEFAPP) :
								$APPROVER_BEF	= $rowBEFAPP->APPROVER_1;
							endforeach;
							
							// CEK STATUS APPROVER SEBELUMNYA
							if($APPROVER_BEF == '')
							{
								$canApprove	= 1;
							}
							else
							{
								$sqlC_AppBEF	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_BEF'";
								$resC_AppBEF 	= $this->db->count_all($sqlC_AppBEF);
								
								if($resC_AppBEF == 0)
									$canApprove	= 0;
								else
									$canApprove	= 1;
							}
						}
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
		
		$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_LEV'";
		$resultPRJ 		= $this->db->query($sqlPRJ)->result();
		
		foreach($resultPRJ as $rowPRJ) :
			$PRJCODE1 	= $rowPRJ->PRJCODE;
			$PRJNAME1 	= $rowPRJ->PRJNAME;
		endforeach;

		$PRJNAME		= '';
		$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
		$resultPRJ 		= $this->db->query($sqlPRJ)->result();
		foreach($resultPRJ as $rowPRJ) :
			$PRJNAME 	= $rowPRJ->PRJNAME;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo "$mnName ($PRJCODE)"; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">
			<div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		    <div class="row">
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
		                        <input type="hidden" name="PRDate" id="PRDate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
					<?php
						// START : LOCK PROCEDURE
							$app_stat 	= $this->session->userdata['app_stat'];
							if($LangID == 'IND')
							{
								$appAlert1	= 'Terkunci!';
								$appAlert2	= 'Mohon maaf, saat ini transaksi penjurnalan sedang terkunci.';
							}
							else
							{
								$appAlert1	= 'Locked!';
								$appAlert2	= 'Sorry, the journalizing transaction is currently locked.';
							}
							?>
								<input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
					            <div class="col-sm-12" id="divAlert" style="display: none;">
					                <div class="form-group">
					                    <div class="col-sm-12">
					                        <div class="alert alert-danger alert-dismissible">
					                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                            <h4><i class="icon fa fa-ban"></i> <?php echo $appAlert1; ?>!</h4>
					                            <?php echo $appAlert2; ?>
					                        </div>
					                    </div>
					                </div>
					            </div>
						    <?php
	                	// END : LOCK PROCEDURE
	                ?>
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
					            <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $DocNumber; ?>">
					            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $UsageCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="UM_NUM1" id="UM_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="UM_NUM" id="UM_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
				                    <div class="col-sm-5">
			                    	 	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="UM_CODE" name="UM_CODE" size="5" value="<?php echo $UM_CODE; ?>" />
			                    	 	<input type="text" class="form-control" id="UM_CODEX" name="UM_CODEX" size="5" value="<?php echo $UM_CODE; ?>" disabled />
				                    </div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="UM_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $UM_DATE; ?>" style="width:106px" onChange="getUM_NUM(this.value)" readonly></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Type; ?></label>
				                    <div class="col-sm-3">
										<select name="UM_TYPEX" id="UM_TYPEX" class="form-control select2" disabled>
											<!-- <option value="0"> --- </option> -->
											<option value="1"> --- </option>
											<option value="2"<?php if($UM_TYPE == 2) { ?> selected <?php } ?>>Pihak ke-3</option>
											<option value="3"<?php if($UM_TYPE == 3) { ?> selected <?php } ?>>Proyek Lain</option>
										</select>
										<input type="hidden" class="form-control" id="UM_TYPE" name="UM_TYPE" size="5" value="<?php echo $UM_TYPE; ?>" />
				                    </div>
				                    <div class="col-sm-6">
		                        		<select name="PRJDESTX" id="PRJDESTX" class="form-control select2" disabled>
		                        			<option value="0" <?php if($PRJDEST == 0) { ?> selected <?php } ?>> --- </option>
		                                    <?php
		                                    	$q_PRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE != '$PRJCODE' ORDER BY PRJCODE";
		                                    	$r_PRJ 	= $this->db->query($q_PRJ)->result();
		                                        foreach($r_PRJ as $rw_PRJ) :
		                                            $PRJCODE1	= $rw_PRJ->PRJCODE;
		                                            $PRJNAME1	= $rw_PRJ->PRJNAME;
		                                            ?>
		                                                <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJDEST) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    ?>
		                                </select>
										<input type="hidden" class="form-control" id="PRJDEST" name="PRJDEST" size="5" value="<?php echo $PRJDEST; ?>" />
				                    </div>
				                </div>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-10">
				                    	<select name="PRJCODE" id="PRJCODE" class="form-control" onChange="chooseProject()">
				                          	<option value="none">--- None ---</option>
				                          	<?php
											  	$sqlCPRJ	= "tbl_project";
												$countPRJ	= $this->db->count_all($sqlCPRJ);
					                            if($countPRJ > 0)
					                            {
													$sqlPRJ 	= "SELECT PRJCODE, PRJNAME
																	FROM tbl_project WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj 
																		WHERE Emp_ID = '$DefEmp_ID')
																	ORDER BY PRJNAME";
													$resPRJ		= $this->db->query($sqlPRJ)->result();
					                                foreach($resPRJ as $row) :
					                                    $PRJCODE1 	= $row->PRJCODE;
					                                    $PRJNAME 	= $row->PRJNAME;
					                                    ?>
					                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $JobNm ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="JCODEID" id="JCODEID" value="<?php echo $JOBCODEID; ?>">
		                                    <input type="text" class="form-control" name="JCODEID1" id="JCODEID1" value="<?php echo $JOBCODEIDD; ?>" readonly>
		                                </div>
		                            </div>
		                        </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Remarks; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="UM_NOTE" class="form-control" id="UM_NOTE" cols="30"><?php echo $UM_NOTE; ?></textarea>
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="UM_NOTE2" class="form-control" id="UM_NOTE2" cols="30"><?php echo $UM_NOTE2; ?></textarea>
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
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName ?> </label>
		                          	<div class="col-sm-5">
		                        		<select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" onChange="getTOP(this.value)" <?php if($UM_TYPE == 1) { ?> disabled <?php } ?>>
		                        			<option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>> --- </option>
		                                    <?php
		                                    	$q_01 	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = 1";
		                                    	$r_01 	= $this->db->query($q_01)->result();
		                                        foreach($r_01 as $rw_01) :
		                                            $SPLCODE1	= $rw_01->SPLCODE;
		                                            $SPLDESC1	= $rw_01->SPLDESC;
		                                            ?>
		                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    ?>
		                                </select>
		                          	</div>
		                          	<div class="col-sm-4 pull">
		                          		<label for="inputName" class="control-label pull-right"><?php echo $WHLocation ?> </label>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">No. Ref. </label>
		                          	<div class="col-sm-4">
		                        		<input type="text" class="form-control" name="UM_NOREF" id="UM_NOREF" value="<?php echo $UM_NOREF; ?>" readonly>
		                          	</div>
		                          	<div class="col-sm-5">
		                          		<?php
											if($PRJSCATEG == 1)
												$ADDQRY = "PRJCODE = '$PRJCODE'";
											else
												$ADDQRY = "PRJCODE = '$PRJCODE_HO'";

											$sqlWHC		= "tbl_warehouse WHERE $ADDQRY";
											$resWHC		= $this->db->count_all($sqlWHC);
		                          		?>
		                        		<select name="WH_CODE" id="WH_CODE" class="form-control select2" >
		                                    <?php
		                                        if($resWHC > 0)
		                                        {
													?>
		                                            <option value="" style="font-style:italic"> --- </option>
		                                            <?php
													$sqlWH 		= "SELECT WH_NUM, WH_CODE, WH_NAME
																	FROM tbl_warehouse WHERE $ADDQRY ORDER BY WH_NAME";
													$resWH		= $this->db->query($sqlWH)->result();
		                                            foreach($resWH as $rowWH) :
		                                                $WH_CODE1 = $rowWH->WH_NUM;
		                                                $WH_CODE2 = $rowWH->WH_CODE;
		                                                $WH_NAME1 = $rowWH->WH_NAME;
		                                                ?>
		                                  				<option value="<?php echo $WH_CODE1; ?>" <?php if($WH_CODE1 == $WH_CODE) { ?> selected <?php } ?>><?php echo "$WH_CODE2 - $WH_NAME1"; ?></option>
		                                  			<?php
		                                            endforeach;
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                  				<option value="" style="font-style:italic; text-align:center"> --- </option>
		                                  			<?php
		                                        }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?=$Notes?></label>
		                          	<div class="col-sm-9">
		                        		<textarea name="UM_SPLNOTES" class="form-control" id="UM_SPLNOTES" cols="30"><?php echo $UM_SPLNOTES; ?></textarea>
		                          	</div>
		                        </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $UM_STAT; ?>">
				                        <?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$UM_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="UM_STAT" id="UM_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> --- </option>
																<option value="3"<?php if($UM_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																<option value="4"<?php if($UM_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($UM_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<!-- <option value="6"<?php if($UM_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																<option value="7"<?php if($UM_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
							</div>
						</div>
					</div>

					<div class="col-sm-12" id="alrtLockJ" style="display: none;">
						<div class="alert alert-warning alert-dismissible col-md-12">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-warning"></i> <?php echo $docalert1; ?>!</h4>
							<?php echo $docalert4; ?>
						</div>
					</div>

	                <div class="col-md-12">
	                    <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
		                              	<th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
		                              	<th width="3%" rowspan="2" style="text-align:center" nowrap><?php echo $ItemCode ?> </th>
		                              	<th width="33%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
		                              	<th colspan="2" style="text-align:center"><?php echo $ItemQty; ?> </th>
		                              	<th rowspan="2" style="text-align:center"><?php echo $Unit ?> </th>
		                              	<th width="24%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                              <th style="text-align:center;"><?php echo $Stock ?> </th>
		                              <th style="text-align:center"><?php echo $Used ?></th>
		                            </tr>
		                            <?php
		                                $sqlDET	= "SELECT A.ID, A.UM_NUM, A.UM_CODE, B.ACC_ID, B.ACC_ID_UM, A.PRJCODE, A.JOBCODEDET, A.JOBCODEID, 
														A.ITM_CODE, A.ITM_UNIT, A.ITM_STOCK, A.ITM_QTY, A.ITM_PRICE, A.UM_DESC,
														B.ITM_NAME, B.ITM_GROUP,
														B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, B.ISFASTM, B.ISWAGE
													FROM tbl_um_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE UM_NUM = '$UM_NUM' 
														AND B.PRJCODE = '$PRJCODE'";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										$showBtn 	= 1;
										$disbyPrc 	= 0;
										foreach($result as $row) :
											$currentRow  	= ++$i;
											$UMID 			= $row->ID;
											$UM_NUM 		= $row->UM_NUM;
											$UM_CODE 		= $row->UM_CODE;
											$PRJCODE 		= $row->PRJCODE;
											$JOBCODEDET		= $row->JOBCODEDET;
											$JOBCODEID 		= $row->JOBCODEID;
											$ITM_CODE 		= $row->ITM_CODE;
											$ACC_ID			= $row->ACC_ID;
											$ACC_ID_UM		= $row->ACC_ID_UM;
											$ITM_NAME 		= $row->ITM_NAME;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITM_GROUP 		= $row->ITM_GROUP;
											$ITM_STOCK1		= $row->ITM_STOCK;
											$ITM_QTY 		= $row->ITM_QTY;
											$ITM_PRICE 		= $row->ITM_PRICE;
									
											// GET ITEM STOCK
												/*$ITMSTOK	= 0;
												$s_STOCK	= "SELECT SUM(ITM_QTY) AS ITM_STOCK FROM tbl_ir_detail A
																INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																WHERE A.PRJCODE = '$PRJCODE' 
																	AND A.ITM_CODE = '$ITM_CODE' 
																	AND B.IR_STAT IN (3,6)";
												$r_STOCK	= $this->db->query($s_STOCK)->result();
												foreach($r_STOCK as $rw_STOCK) :
													$ITMSTOK= $rw_STOCK->ITM_STOCK;
													if($ITMSTOK == '')
														$ITMSTOK	= 0;
												endforeach;*/

												$ITM_STOCK_VOL 	= 0;
												$ITM_STOCK_VAL	= 0;
												$ITM_UVOL_R		= 0;
												$ITM_UVAL_R		= 0;
												$s_PRC	= "SELECT 	SUM(IR_VOL-UM_VOL) AS ITM_STOCK_VOL, SUM(IR_VAL-UM_VAL) AS ITM_STOCK_VAL,
																	SUM(UM_VOL_R) AS ITM_UM_VOL_R, SUM(UM_VAL_R) AS ITM_STOCK_VAL_R
															FROM tbl_item_logbook_$PRJCODEVW WHERE DOC_CATEG IN ('IR','UM') AND ITM_CODE = '$ITM_CODE'
																AND DOC_NUM != '$UM_NUM'";
												$r_PRC	= $this->db->query($s_PRC)->result();
												foreach($r_PRC as $rw_PRC) :
													$ITM_STOCK_VOL 	= $rw_PRC->ITM_STOCK_VOL;
													$ITM_STOCK_VAL	= $rw_PRC->ITM_STOCK_VAL;
													$ITM_UVOL_R		= $rw_PRC->ITM_UM_VOL_R;
													$ITM_UVAL_R		= $rw_PRC->ITM_STOCK_VAL_R;
												endforeach;
									
											// GET RESERVE ITEM (NEW, CONFIRM, APPROVE, CLOSED)
												/*$ITMRSV		= 0;
												$sqlITMRSV	= "SELECT SUM(A.ITM_QTY) AS ITMRESERVE FROM tbl_um_detail A
																INNER JOIN tbl_um_header B ON A.UM_NUM = B.UM_NUM
																WHERE A.PRJCODE = '$PRJCODE' 
																	AND A.ITM_CODE = '$ITM_CODE'
																	-- AND B.UM_STAT IN (2,7)
																	AND A.UM_NUM != '$UM_NUM'";
												$resITMRSV	= $this->db->query($sqlITMRSV)->result();
												foreach($resITMRSV as $rowITMRSV) :
													$ITMRSV	= $rowITMRSV->ITMRESERVE;
													if($ITMRSV == '')
														$ITMRSV	= 0;
												endforeach;*/
									
											// GET RESERVE ITEM IN THIS DOC AND OTEHR ROW
												$ITMRSV2	= 0;
												$s_ITMRSV2	= "SELECT SUM(A.ITM_QTY) AS ITMRESERVE2 FROM tbl_um_detail A
																INNER JOIN tbl_um_header B ON A.UM_NUM = B.UM_NUM
																WHERE A.PRJCODE = '$PRJCODE' 
																	AND A.ITM_CODE = '$ITM_CODE' 
																	AND A.UM_NUM = '$UM_NUM'
																	AND A.ID != '$UMID'";
												$r_ITMRSV2	= $this->db->query($s_ITMRSV2)->result();
												foreach($r_ITMRSV2 as $rw_ITMRSV2) :
													$ITMRSV2	= $rw_ITMRSV2->ITMRESERVE2;
													if($ITMRSV2 == '')
														$ITMRSV2	= 0;
												endforeach;
											
											// $MAX_UM_VOL 	= $ITM_STOCK_VOL - $ITM_UVOL_R - $ITMRSV2;
											// $REM_VOL 		= $ITM_STOCK_VOL - $ITM_UVOL_R - $ITMRSV2;
											$REM_VOL 		= $ITM_STOCK_VOL;
											// $REM_VAL 		= $ITM_STOCK_VAL - $ITM_UVAL_R;
											$REM_VAL 		= $ITM_STOCK_VAL;
											$REM_VOLP 		= $REM_VOL;
											if($REM_VOL == 0)
												$REM_VOLP 	= 1;

											$ITM_PRICE 		= $REM_VAL / $REM_VOLP;
									
											// GET ITEM AVG
												/*$s_PRC	= "SELECT (SUM(IR_VAL-UM_VAL) / SUM(IR_VOL-UM_VOL)) AS PRICE
															FROM tbl_item_logbook_$PRJCODEVW WHERE DOC_CATEG IN ('IR','UM') AND ITM_CODE = '$ITM_CODE'";
												$r_PRC	= $this->db->query($s_PRC)->result();
												foreach($r_PRC as $rw_PRC) :
													$ITM_PRICE 	= $rw_PRC->PRICE;
												endforeach;
												if($ITM_PRICE == '')
													$ITM_PRICE = 0;*/

											$bgCol 		= "";
											if($ITM_PRICE <= 0)
											{
												$bgCol 		= 'style="background-color: #E1C16E;"';
												$disbyPrc 	= 1;
											}

											//$ITM_PRICE 	= $row->ITM_PRICE;
											$UM_DESC 		= $row->UM_DESC;
											$ITM_NAME		= $row->ITM_NAME;
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
												
											$itemConvertion	= 1;

											$ItmCol0	= '';
											$ItmCol1	= '';
											$ItmCol2	= '';
											$ttl 		= '';
											$divDesc 	= '';
											$isDisabled = 0;
											$ACC_IDVw 	= '';
											if($UM_TYPE == 1 && $ACC_ID_UM == '')
											{
												$secCopyCOA	= base_url().'index.php/__l1y/CopyCOA/?id=';
												$urlCopyCOA = "$secCopyCOA~$PRJCODE~$ITM_CODE";
												$ACC_IDVw 	= "<input type='hidden' name='urlCopyCOA".$currentRow."' id='urlCopyCOA".$currentRow."' value='".$urlCopyCOA."'>
																<a href='javascript:void(null);' title='Silahkan klik untuk copy akun dari proyek lain' onClick='copyCOA(".$currentRow.")'><i class='fa fa-repeat'></i></a>";

												$disBtn 	= 1;
												$ItmCol0	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= 'Belum disetting kode akun penggunaan';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
												$isDisabled = 1;
											}
											if($UM_TYPE == 2 && $ACC_ID == '')
											{
												$disBtn 	= 1;
												$ItmCol0	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= 'Belum disetting kode akun penggunaan';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAccIR."";
												$isDisabled = 1;
											}

											$vwDET		= "$PRJCODE~$UM_NUM~$UM_CODE~$JOBCODEID~$ITM_CODE~$UMID";
											$secvwPRD 	= site_url('c_purchase/c_pr180d0c/shwIR_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwDET));
											$VW_UMDET	= "<a onClick='showITMDET('".$secvwPRD.")' title='Reserved' style='cursor: pointer; text-decoration:none; color: red'><i class='glyphicon glyphicon-triangle-bottom'></i>&nbsp;".number_format($ITM_UVOL_R, 2)."</a>";
								
											/*if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>" <?=$bgCol?>>
												<td width="3%" height="25" style="text-align:left">
												  	<?php
														if($UM_STAT == 1)
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
										 		<td width="3%" style="text-align:left" nowrap> <!-- Item Code -->
											  		<?php echo "$ItmCol0$ITM_CODE$ItmCol2"; ?>
		                                      		<input type="hidden" id="data<?php echo $currentRow; ?>UM_NUM" name="data[<?php echo $currentRow; ?>][UM_NUM]" value="<?php echo $UM_NUM; ?>" class="form-control" style="max-width:300px;">
		                                      		<input type="hidden" id="data<?php echo $currentRow; ?>UM_CODE" name="data[<?php echo $currentRow; ?>][UM_CODE]" value="<?php echo $UM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                      		<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                      		<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" class="form-control" style="max-width:300px;">
			                                  	</td>
											  	<td width="33%" style="text-align:left">
													<?php echo $ITM_NAME; ?> <!-- Item Name -->
													<?php echo "$ItmCol1$divDesc$ItmCol2"; ?>
													<span><?=$ACC_IDVw?></span>
												</td>
												<td width="11%" style="text-align:right"> <!-- Item Stock -->
													<?php
														echo number_format($ITM_STOCK_VOL, $decFormat)."<br>".
														$VW_UMDET;
													?>
			                                      	<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_STOCK<?php echo $currentRow; ?>" id="ITM_STOCK<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_STOCK_VOL, 2); ?>" disabled >
			                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_STOCK" name="data[<?php echo $currentRow; ?>][ITM_STOCK]" value="<?php echo $ITM_STOCK_VOL; ?>">
			                                  	</td>
											 	<td width="11%" style="text-align:right"> <!-- Item QTY Use -->
											 		<?php echo number_format($ITM_QTY, 2); ?><br>
			                                   		<input type="hidden" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_QTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
			                                   		<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>" class="form-control" style="max-width:300px;" >
			                                   		<input type=<?php if($DefEmp_ID == 'D15040004221') { echo "text"; } else { echo "hidden"; }?> name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:300px;" >
			                                   		<?php if($DefEmp_ID == 'D15040004221') { echo "Prc : ".number_format($ITM_PRICE,2); } ?>
			                                 	</td>
											  	<td width="4%" style="text-align:center" nowrap>
												  <?php echo $ITM_UNIT; ?>
			                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
			                                        <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
			                                         <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
			                                    <!-- Item Unit Type -- ITM_UNIT --></td>
											  	<td width="24%" style="text-align:left;">
											  		<?php echo $UM_DESC; ?>
									        		<input type="hidden" name="data[<?php echo $currentRow; ?>][UM_DESC]" id="data<?php echo $currentRow; ?>UM_DESC" size="20" value="<?php echo $UM_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
			                                        <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                                    </td>
										  	</tr>
		                              	<?php
		                             	endforeach;
		                            ?>
		                        </table>
	                        </div>
	                    </div>
	                </div>
	                <br>
					<?php
                        $DOC_NUM	= $UM_NUM;
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

                        $s_APPEMP	= "tbl_docstepapp WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MenuCode'
										AND (APPROVER_1 = '$DefEmp_ID' OR APPROVER_2 = '$DefEmp_ID' OR APPROVER_3 = '$DefEmp_ID'
										OR APPROVER_4 = '$DefEmp_ID' OR APPROVER_5 = '$DefEmp_ID')";
                        $r_APPEMP	= $this->db->count_all($s_APPEMP);
						
                    	if($resCAPP == 0)
                    	{
                    		$canApprove 	= 0;
                    		$isDisabled 	= 1;
                    		if($LangID == 'IND')
							{
								$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini. Atau Anda tidak memiliki pengaturan untuk menyetujui dokumen ini.";
							}
							else
							{
								$zerSetApp	= "There are no arrangements for the approval of this document. Or you don't have the settings to approve this document.";
							}
                    		?>
	                		<div class="col-md-12">
                    			<div class="alert alert-warning alert-dismissible">
				                	<?php echo $zerSetApp; ?>
				              	</div>
				            </div>
                    		<?php
                    	}
                    ?>
	                <div class="col-md-12">
		                <div class="form-group">
							<?php if($disbyPrc > 0) 
								{ ?>
			                    <div class="col-sm-12">
			                        <div class="alert alert-danger alert-dismissible">
			                            Ada kendala di item yang akan digunakan. Silahkan hubungi tim pengembang SdBP+.
			                        </div>
			                    </div>
					        <?php } else { ?>
			                    <div class="col-sm-9">
									<?php 
										if(($UM_STAT == 2 || $UM_STAT == 7) && $ISAPPROVE == 1 && $isDisabled == 0 && $disbyPrc == 0)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
										$backURL	= site_url('c_inventory/c_iu180c16/in180c18b0x/');
			                            echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>');
									?>
			                    </div>
					        <?php }?>
		                </div>
		            </div>
				</form>
		        <div class="col-md-12">
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

	// START : LOCK PROCEDURE
	$(document).ready(function()
		{
			setInterval(function(){chkAppStat()}, 1000);
		});

		function chkAppStat()
		{
			var url         = "<?php echo site_url('lck/appStat')?>";
			let DOC_DATE 	= $('#datepicker').val();
			console.log(DOC_DATE);
			
				
			$.ajax({
				type: 'POST',
				url: url,
				data: {DOC_DATE:DOC_DATE},
				dataType: "JSON",
				success: function(response)
				{
					// var arrVar      = response.split('~');
					// var arrStat     = arrVar[0];
					// var arrAlert    = arrVar[1];
					// var LockCateg 	= arrVar[2];
					// var app_stat    = document.getElementById('app_stat').value;

					let LockY		= response[0].LockY;	
					let LockM		= response[0].LockM;	
					let LockCateg	= response[0].LockCateg;	
					let isLockJ		= response[0].isLockJ;	
					let LockJDate	= response[0].LockJDate;	
					let UserJLock	= response[0].UserJLock;	
					let isLockT		= response[0].isLock;	
					let LockTDate	= response[0].LockDate;	
					let UserLockT	= response[0].UserLock;
					console.log("isLockT ="+isLockT+" isLockJ = "+isLockJ+" LockCateg = "+LockCateg);

					if(isLockJ == 1)
					{
						$('#alrtLockJ').css('display','');
						document.getElementById('divAlert').style.display   = 'none';
						$('#UM_STAT>option[value="3"]').attr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}
					else
					{
						$('#alrtLockJ').css('display','none');
						document.getElementById('divAlert').style.display   = 'none';
						$('#UM_STAT>option[value="3"]').removeAttr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							$('#alrtLockJ').css('display','');
							document.getElementById('divAlert').style.display   = 'none';
							$('#UM_STAT').removeAttr('disabled','disabled');
							$('#UM_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = '';
							$('#UM_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#UM_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#UM_STAT').removeAttr('disabled','disabled');
							$('#UM_STAT>option[value="3"]').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#UM_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#UM_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE
  
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
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var UM_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var UM_CODEx 	= "<?php echo $UM_CODE; ?>";
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
		ITM_UNIT 		= arrItem[6];
		ITM_PRICE 		= arrItem[7];
		ITM_VOLM 		= arrItem[8];
		ITM_STOCK 		= arrItem[9];
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'UM_NUM" name="data['+intIndex+'][UM_NUM]" value="'+UM_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'UM_CODE" name="data['+intIndex+'][UM_CODE]" value="'+UM_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// Item Stock
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_STOCK'+intIndex+'" id="ITM_STOCK'+intIndex+'" value="'+ITM_STOCK+'" disabled ><input type="hidden" id="data'+intIndex+'ITM_STOCK" name="data['+intIndex+'][ITM_STOCK]" value="'+ITM_STOCK+'">';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" value="0.00" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" >';
		
		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// Remarks -- UM_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][UM_DESC]" id="data'+intIndex+'UM_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('ITM_STOCK'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_STOCK').value 	= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('ITM_STOCK'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		document.getElementById('totalrow').value = intIndex;
	}

	function copyCOA(row)
	{
		let collID  	= document.getElementById('urlCopyCOA'+row).value;
		let myarr 		= collID.split('~');
    	let url			= myarr[0];

		document.getElementById('idprogbar').style.display 		= '';
		document.getElementById("progressbarXX").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";


        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
				txtArr 		= response.split("~");
				statAlert	= txtArr[0];
				txtAlert 	= txtArr[1];

				if(statAlert == 0) iconAlert = "warning";
				else iconAlert = "success";

                swal(txtAlert, 
                {
                    icon: iconAlert,
                })
                .then(function()
                {
                	location.reload();
					// document.getElementById('some_frame_id').contentWindow.location.reload();

					document.getElementById('idprogbar').style.display 		= 'none';
					document.getElementById("progressbarXX").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";
					
                })
            }
        });
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
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value));
		//itemConvertion		= document.getElementById('itemConvertion'+row).value;	
		itemConvertion		= 1;	
		document.getElementById('data'+row+'ITM_QTY').value = thisVal;
		ITM_QTY				= parseFloat(thisVal);													// Item Qty
		document.getElementById('ITM_QTY'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_QTY').value);		// Item Price
		ITM_STOCK			= parseFloat(document.getElementById('data'+row+'ITM_STOCK').value);	// Stock Qty
		TOT_UM_AM			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);							// Total UM Amount
		TOT_BU_AM			= parseFloat(ITM_STOCK) * parseFloat(ITM_PRICE);						// Total Budget Amount
		
		// REMAIN
		REM_UM_QTY			= parseFloat(ITM_STOCK) - parseFloat(ITM_QTY);
		REM_UM_AMOUNT		= parseFloat(TOT_BU_AM) - parseFloat(TOT_UM_AM);
				
		if(ITM_QTY > ITM_STOCK)
		{
			swal('Your Qty has input is Greater than Stock Qty. Maximum Qty is '+ITM_STOCK);
			document.getElementById('data'+row+'ITM_QTY').value = ITM_STOCK;
			document.getElementById('ITM_QTY'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_STOCK)),decFormat));
			return false;
		}
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		UM_STAT			= document.getElementById("UM_STAT").value;
		if(UM_STAT == 0)
		{
			swal("<?php echo $alert2; ?>",
			{
				icon:"warning",
			});
			document.getElementById("UM_STAT").focus();
			return false;
		}

		// document.getElementById('btnSave').style.display 	= 'none';

		let frm = document.getElementById('frm');
		frm.addEventListener('submit', (e) => {
			console.log(e)
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		});
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