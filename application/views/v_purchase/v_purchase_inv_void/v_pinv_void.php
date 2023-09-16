<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Maret 2018
 * File Name	= v_pinv_void.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$LangID 	= $this->session->userdata['LangID'];

/*$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$sqlR		= $this->db->query($sql)->result();
foreach($sqlR as $rowR) :
	$PRJCODE		= $rowR->PRJCODE;
	$PRJNAME		= $rowR->PRJNAME;
	$PRJLOCT		= $rowR->PRJLOCT;
endforeach;*/

$hideSrch		= 0;
$showTable		= 0;
$LPMCODE		= '';
if (isset($_POST['submitSrch']))
{
	$INV_NUM 	= $_POST['txtSearch'];
	$hideSrch	= 1;
	$showTable	= 1;
}
elseif (isset($_POST['btnSrch']))
{
	$INV_NUM 	= "";
	$hideSrch	= 0;
}
else
{
	$INV_NUM	= "";
}

$ISVOID			= 0;
if (isset($_POST['submitSrch1']))
{
	$INV_NUM 		= $_POST['txtSearch'];
	$hideSrch		= 1;
	$showTable		= 1;
	
	$ISVOID			= 0;
	
	
	$countINV		= 0;
	//$sqlINVC		= "tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%' ESCAPE '!' OR INV_CODE LIKE '%$INV_NUM%'";
	$sqlINVC		= "tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%'";
	$countINV		= $this->db->count_all($sqlINVC);
	if($countINV > 0)
	{
		/*$sqlINVD		= "SELECT INV_NUM, INV_CATEG, INV_DATE, INV_DUEDATE, IR_NUM AS TTK_NUM, ISVOID, PRJCODE, INV_AMOUNT, INV_LISTTAXVAL, INV_PPHVAL
							FROM tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%' ESCAPE '!' OR INV_CODE LIKE '%$INV_NUM%'";*/
		$sqlINVD		= "SELECT INV_NUM, INV_CATEG, INV_DATE, INV_DUEDATE, IR_NUM AS TTK_NUM, ISVOID, PRJCODE, INV_AMOUNT, INV_LISTTAXVAL, INV_PPHVAL
							FROM tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%'";
		$resINVD		= $this->db->query($sqlINVD)->result();
		foreach($resINVD as $rowINVD):
			$INV_NUM		= $rowINVD->INV_NUM;
			$INV_CATEG		= $rowINVD->INV_CATEG;
			$TTK_NUMX		= $rowINVD->TTK_NUM;
			$INV_DATE		= $rowINVD->INV_DATE;
			$INV_DUEDATE	= $rowINVD->INV_DUEDATE;
			$ISVOID			= $rowINVD->ISVOID;
			$PRJCODE		= $rowINVD->PRJCODE;
			$INV_AMOUNT		= $rowINVD->INV_AMOUNT;
			$INV_AMOUNT		= $rowINVD->INV_AMOUNT;
			$INV_LISTTAXVAL	= $rowINVD->INV_LISTTAXVAL;
			$INV_PPHVAL		= $rowINVD->INV_PPHVAL;
		endforeach;
	}
	else
	{
		$sqlINVC		= "tbl_pinv_header WHERE INV_CODE LIKE '%$INV_NUM%'";
		$countINV		= $this->db->count_all($sqlINVC);
	
		if($countINV > 0)
		{
			$sqlINVD	= "SELECT INV_NUM, INV_CATEG, INV_DATE, INV_DUEDATE, IR_NUM AS TTK_NUM, ISVOID, PRJCODE, INV_AMOUNT, INV_LISTTAXVAL, INV_PPHVAL
							FROM tbl_pinv_header WHERE INV_CODE LIKE '%$INV_NUM%'";
			$resINVD	= $this->db->query($sqlINVD)->result();
			foreach($resINVD as $rowINVD):
				$INV_NUM		= $rowINVD->INV_NUM;
				$INV_CATEG		= $rowINVD->INV_CATEG;
				$TTK_NUMX		= $rowINVD->TTK_NUM;
				$INV_DATE		= $rowINVD->INV_DATE;
				$INV_DUEDATE	= $rowINVD->INV_DUEDATE;
				$ISVOID			= $rowINVD->ISVOID;
				$PRJCODE		= $rowINVD->PRJCODE;
				$INV_AMOUNT		= $rowINVD->INV_AMOUNT;
				$INV_AMOUNT		= $rowINVD->INV_AMOUNT;
				$INV_LISTTAXVAL	= $rowINVD->INV_LISTTAXVAL;
				$INV_PPHVAL		= $rowINVD->INV_PPHVAL;
			endforeach;
		}
	}
	
	$sqlINVC		= "tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%'";
	$countINV		= $this->db->count_all($sqlINVC);
	if($countINV > 0)
	{
		/*$sqlINVD		= "SELECT INV_NUM, INV_CATEG, INV_DATE, INV_DUEDATE, IR_NUM AS TTK_NUM, ISVOID, PRJCODE, INV_AMOUNT, INV_LISTTAXVAL, INV_PPHVAL
							FROM tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%' ESCAPE '!' OR INV_CODE LIKE '%$INV_NUM%'";*/
		$sqlINVD		= "SELECT INV_NUM, INV_CATEG, INV_DATE, INV_DUEDATE, IR_NUM AS TTK_NUM, ISVOID, PRJCODE, INV_AMOUNT, INV_LISTTAXVAL, INV_PPHVAL
							FROM tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%'";
		$resINVD		= $this->db->query($sqlINVD)->result();
		foreach($resINVD as $rowINVD):
			$INV_NUM		= $rowINVD->INV_NUM;
			$INV_CATEG		= $rowINVD->INV_CATEG;
			$TTK_NUMX		= $rowINVD->TTK_NUM;
			$INV_DATE		= $rowINVD->INV_DATE;
			$INV_DUEDATE	= $rowINVD->INV_DUEDATE;
			$ISVOID			= $rowINVD->ISVOID;
			$PRJCODE		= $rowINVD->PRJCODE;
			$INV_AMOUNT		= $rowINVD->INV_AMOUNT;
			$INV_AMOUNT		= $rowINVD->INV_AMOUNT;
			$INV_LISTTAXVAL	= $rowINVD->INV_LISTTAXVAL;
			$INV_PPHVAL		= $rowINVD->INV_PPHVAL;
			
			// UPDATE TTK
			// CHECK SEMUA INV YANG BERHUBUNGAN DENGAN TTK INI, BIASANYA 1 TTK = 1 INV
				$sqlTTK	= "UPDATE tbl_ttk_header SET INV_STAT = 'NI', INV_CREATED = 0 WHERE TTK_NUM = '$TTK_NUMX'";
				$this->db->query($sqlTTK);
			
			// UNTUK KEPERLUAN FINANCIAL TRACK
			// NILAI HUTANG SEBENARNYA = NILAI INVOICE (SBL DIPOTONG) - POTONGAN + PPN;
			// SAMA DENGAN FM_TOTVAL = (INV_VAL + PPN - POT - RET - PPH) + RET + PPH;
			$FM_TOTVAL		= $INV_AMOUNT + $INV_LISTTAXVAL + $INV_PPHVAL;
			
			$DOC_NUM 		= $INV_NUM;
			$DOC_DATE 		= $INV_DATE;
			$DOC_EDATE 		= $INV_DUEDATE;
			$PRJCODE 		= $PRJCODE;
			$FIELD_NAME1	= 'FT_AP';
			$FIELD_NAME2	= 'FM_AP';
			$TOT_AMOUNT		= $FM_TOTVAL;
			
			$sqlUpd			= "UPDATE tbl_financial_track SET
									FT_AP = FT_AP - $FM_TOTVAL 
								WHERE FT_PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpd);
			
			$sqlUpd			= "UPDATE tbl_financial_monitor SET 
									FM_AP = FM_AP - $FM_TOTVAL 
								WHERE FM_PRJCODE = '$PRJCODE' AND FM_TRANSD = '$DOC_DATE'";
			$this->db->query($sqlUpd);
		endforeach;
	}
	
	$docalert9	= '';
	if($ISVOID == 1)
	{
		if($LangID == 'IND')
		{
			$docalert9	= 'Dokumen ini sudah di VOID';
		}
		else
		{
			$docalert9	= 'This document has been voided';
		}
	}
	else
	{
		// UPDATE STATUS ITEM RECEIVE	 - HOLD
			/*$sqlCPINV	= "tbl_pinv_header WHERE INV_NUM NOT IN ('$INV_NUM') AND INV_STAT = '3' AND ISVOID = '0'";
			$resCPINV	= $this->db->count_all($sqlCPINV);
			if($resCPINV == 0)
			{
				$sqlUIR	= "UPDATE tbl_ir_header SET INVSTAT = 'NI'";
				$this->db->query($sqlUIR);
			}
			elseif($resCPINV > 0)
			{
				$sqlUIR	= "UPDATE tbl_ir_header SET INVSTAT = 'HI'";
				//$this->db->query($sqlUIR);
			}*/
		
		// UPDATE STATUS
			$sqlUPINV	= "UPDATE tbl_pinv_header SET INV_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', ISVOID = '1' 
							WHERE INV_NUM = '$INV_NUM'";
			$resUPINV	= $this->db->query($sqlUPINV);
		
		// UPDATE JOURNAL
			$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
							WHERE JournalH_Code = '$INV_NUM'";
			$this->db->query($sqlDELJH);
		
		// UPDATE TTK
			$TTK_NUM		= '';
			$sqlTTKD		= "SELECT IR_NUM AS TTK_NUM FROM tbl_pinv_header WHERE INV_NUM = '$INV_NUM' LIMIT 1";
			$resTTKD		= $this->db->query($sqlTTKD)->result();
			foreach($resTTKD as $rowTTKD):
				$TTK_NUM	= $rowTTKD->TTK_NUM;
			endforeach;
			$upTTKHD	= "UPDATE tbl_ttk_header SET INV_CREATED = 0, INV_STAT = 'NI' WHERE TTK_NUM = '$TTK_NUM'";
			$this->db->query($upTTKHD);
			
		// KURANGI NILAI COA
			$JD_NUM		= "V-$INV_NUM";
		
		// INSERT VOID JOURNAL - HEADER
			// GET JOURNAL HEADER
			$sqlJDH	= "SELECT JournalType, JournalH_Desc, JournalH_Date, Company_ID, Source, Emp_ID, Created, LastUpdate,
							Pattern_Type, KursAmount_tobase, Wh_id, Reference_Number, Reference_Type, proj_Code, Journal_Amount, GEJ_STAT
						FROM tbl_journalheader WHERE JournalH_Code = '$INV_NUM' LIMIT 1";
			$resJDH	= $this->db->query($sqlJDH)->result();
			foreach($resJDH as $rowJDH):
				$JournalType	= $rowJDH->JournalType;
				$JournalH_Desc	= $rowJDH->JournalH_Desc;
				$JournalH_Date	= $rowJDH->JournalH_Date;
				$Company_ID		= $rowJDH->Company_ID;
				$Source			= $rowJDH->Source;
				$Emp_ID			= $rowJDH->Emp_ID;
				$Created		= date('Y-m-d H:i:s');
				$LastUpdate		= date('Y-m-d H:i:s');
				$Pattern_Type	= $rowJDH->Pattern_Type;
				$KursAm_tobase	= $rowJDH->KursAmount_tobase;
				$Wh_id			= $rowJDH->Wh_id;
				$Ref_Number		= $rowJDH->Reference_Number;
				$Ref_Type		= $rowJDH->Reference_Type;
				$proj_Code		= $rowJDH->proj_Code;
				$Journal_Amount	= $rowJDH->Journal_Amount;
				$GEJ_STAT		= 9;
				
				$sqlGEJH 		= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source,
										Emp_ID, Created, LastUpdate, KursAmount_tobase, 
										Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT)
									VALUES ('$JD_NUM', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
										'$Emp_ID', '$Created', '$LastUpdate', $KursAm_tobase, 
										'$Wh_id', '$Ref_Number', '$Ref_Type', '$proj_Code', '$GEJ_STAT')";
				$this->db->query($sqlGEJH);
			endforeach;
		
		// INSERT VOID JOURNAL - DETAIL
			// GET JOURNAL DETAIL
			$getJDET	= "SELECT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
								INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
									AND B.PRJCODE = '$proj_Code'
							WHERE A.JournalH_Code LIKE '$INV_NUM%'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_CODE		= "V-$GEJ_CODE1";
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				// UPDATE JOURNAL
					$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', isCanceled = 1 WHERE JournalH_Code = '$GEJ_CODE'";
					$this->db->query($sqlDELJH);
				
				 // BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
									Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
									curr_rate, isDirect, Journal_DK)
								VALUES ('$GEJ_CODE', '$Acc_Numb', '$proj_Code', 'IDR', $Base_Debet, 
									$Base_Debet, $Base_Debet, 'Default', 1, 0, 'K')";
					$this->db->query($sqlGEJDD);
										
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								//echo "Base_Kredit = $jmD = $SYNC_PRJ = $Acc_Numb = $Base_Debet<br>";
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Debet,
													Base_Kredit2 = Base_Kredit2+$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
									Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
									curr_rate, isDirect, Journal_DK)
								VALUES ('$GEJ_CODE', '$Acc_Numb', '$proj_Code', 'IDR', $Base_Kredit, 
									$Base_Kredit, $Base_Kredit, 'Default', 1, 0, 'D')";
					$this->db->query($sqlGEJDD);
										
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								//echo "Base_Debet = $jmD == $SYNC_PRJ = $Acc_Numb = $Base_Kredit<br>";
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Kredit,
													Base_Debet2 = Base_Debet2+$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;
	}
}
$countINV		= 0;
$sqlINVC		= "tbl_pinv_header WHERE INV_NUM LIKE '%$INV_NUM%' ESCAPE '!' OR INV_CODE LIKE '%$INV_NUM%'";
$countINV		= $this->db->count_all($sqlINVC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Dashboard</title>
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
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title	= "Pembatan";
			$h2_title	= "Faktur";
			$InputCode	= "Masukan Kode Faktur";
			$SrcCode	= "Cari";
			$alert1		= "Masukan nomor faktur.";
			$docalert1	= 'Peringatan';
			$docalert2	= 'Untuk akurasi pembatalan dokumen, COPY Kode Unik Dokumen pada tabel di bawah, PASTE ke kolom Pencarian, TEKAN VOID.';
		}
		else
		{
			$h1_title	= "Invoice";
			$h2_title	= "Void";
			$InputCode	= "Input Invoice Code";
			$SrcCode	= "Search";
			$alert1		= "Please input invoice number.";
			$docalert1	= 'Warning';
			$docalert2	= 'For accuracy of document cancellation, COPY Document Unique Code in the table below, PASTE to the Search column above, PRESS VOID.';
		}
	?>
			
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>

	<script>
		function checkSearch()
		{
			txtSearch	= document.getElementById('txtSearch').value;
			document.getElementById('txtSearch').focus()
			if(txtSearch == '')
			{
				swal('<?php echo $alert1; ?>');
				return false;
			}
		}
	</script>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <?php echo $h1_title; ?>
				    <small><?php echo $h2_title; ?></small>
				  </h1>
				  <?php /*?><ol class="breadcrumb">
				    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				    <li><a href="#">Tables</a></li>
				    <li class="active">Data tables</li>
			  </ol><?php */?>
			</section>
    
            <section class="content">
				<div class="box">
					<div class="box-body">
						<?php
							//if($hideSrch == 0)
							//{
								?>
						        <form action="" method=POST onSubmit="return checkSearch();">
						            <table width="100%" border="0">
						                <tr height="20">
						                  <td style="text-align:center; vertical-align:middle"><?php echo $InputCode; ?></td>
						                </tr>
						                <tr height="20">
						                    <td style="text-align:center; vertical-align:top">
						                        <label><input type="text" name="txtSearch" id="txtSearch" class="form-control" style="max-width:250px; text-align:center" value="<?php echo $INV_NUM; ?>" />
						                        </label></td>
						                </tr>
						                <tr height="20">
						                  <td style="text-align:center; vertical-align:middle">
						                  	<input type="submit" class="btn btn-primary" name="submitSrch" id="submitSrch" value=" <?php echo $SrcCode; ?> " />&nbsp;&nbsp;
						                    <?php if($countINV > 0) { ?>
						                  	<input type="submit" class="btn btn-danger" name="submitSrch1" id="submitSrch1" value=" Void " />
						                    <?php } ?>
						                  </td>
						                </tr>
						                <?php if($ISVOID == 0) { ?>
						                <tr height="20">
						                  <td style="text-align:center; vertical-align:middle">
						                	<div class="col-sm-12">
						                        <div class="alert alert-danger alert-dismissible">
						                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
						                            <?php echo $docalert2; ?>
						                        </div>
						                    </div>
						                  </td>
						                </tr>
						                <?php } else { ?>
						                <tr height="20">
						                  <td style="text-align:center; vertical-align:middle">
						                	<div class="col-sm-12">
						                        <div class="alert alert-warning alert-dismissible">
						                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
						                            <?php echo $docalert9; ?>
						                        </div>
						                    </div>
						                  </td>
						                </tr>
						                <?php } ?>
						                <tr height="20">
						                  <td style="text-align:center; vertical-align:middle"><hr style="max-width:350px;"></td>
						                </tr>
						            </table>
						        </form>
								<?php
							//}
						?>
						<form name="isfrmSrch" id="isfrmSrch" action="" method=POST style="display:none">
							<input type="text" name="showSrch" id="showSrch" class="form-control" style="max-width:150px; text-align:center" value="1" />
						    <input type="submit" class="btn btn-primary" name="submitSrchAgain" id="submitSrchAgain" value=" search SPP " />
						</form>

						<?php
							if($showTable == 1)
							{
								$INV_NUM 	= $_POST['txtSearch'];
								
								$myNewNoc 	= 0;
								
								$countINV	= 0;
								$sqlINVC	= "tbl_pinv_header WHERE INV_NUM = '$INV_NUM' OR INV_CODE = '$INV_NUM'";
								$countINV	= $this->db->count_all($sqlINVC);
								?>
								<form name="frm" id="frm" method="post" action="" onSubmit="return target_popup(this);" >
									<div class="search-table-outter">
										<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
											<thead>
												<tr>
													<th width="2%" style="text-align:center" nowrap><?php echo $UniqCode; ?></th>
													<th width="2%" style="text-align:center" nowrap><?php echo $Code; ?></th>
													<th width="2%" style="text-align:center" nowrap><?php echo $Date; ?></th>
													<th width="2%" style="text-align:center" nowrap><?php echo $DueDate; ?></th>
													<th width="5%" style="text-align:center" nowrap>No. TTK</th>
													<th width="31%" style="text-align:center" nowrap><?php echo $Supplier; ?></th>
													<th width="44%" style="text-align:center" nowrap><?php echo $Description; ?></th>
													<th width="7%" style="text-align:center" nowrap><?php echo $Amount; ?></th>
													<th width="5%" style="text-align:center" nowrap>Status</th>
												</tr>
											</thead>
											<tbody>
											<?php
												$myNewNoc = 0;
												$i = 0;
												$j = 0;
												if($countINV > 0)
												{				
													$getINV		= "SELECT INV_NUM, INV_CODE, IR_NUM, INV_DATE, INV_DUEDATE, INV_CATEG, SPLCODE, INV_NOTES, 
																		INV_AMOUNT, INV_AMOUNT_RET, INV_STAT, ISVOID
																	FROM tbl_pinv_header WHERE INV_NUM = '$INV_NUM' OR INV_CODE = '$INV_NUM'";
													$resINV		= $this->db->query($getINV)->result();
													foreach($resINV as $rowINV):
													{
														$i				= $i +1;
														$INV_NUM		= $rowINV->INV_NUM;
														$INV_CODE		= $rowINV->INV_CODE;
														$IR_NUM			= $rowINV->IR_NUM;
														$INV_DATE		= $rowINV->INV_DATE;
														$INV_DUEDATE	= $rowINV->INV_DUEDATE;
														$INV_CATEG		= $rowINV->INV_CATEG;
														$SPLCODE		= $rowINV->SPLCODE;
														$INV_NOTES		= $rowINV->INV_NOTES;
														$INV_AMOUNT		= $rowINV->INV_AMOUNT;
														$INV_AMOUNT_RET	= $rowINV->INV_AMOUNT_RET;
														$INV_STAT		= $rowINV->INV_STAT;
														$ISVOID			= $rowINV->ISVOID;
														
														$INV_AMOUNTV	= $INV_AMOUNT + $INV_AMOUNT_RET;
														
														if($INV_STAT == 0)
														{
															$INV_STATD 		= 'fake';
															$STATCOL		= 'danger';
														}
														elseif($INV_STAT == 1)
														{
															$INV_STATD 		= 'New';
															$STATCOL		= 'warning';
														}
														elseif($INV_STAT == 2)
														{
															$INV_STATD 		= 'Confirm';
															$STATCOL		= 'primary';
														}
														elseif($INV_STAT == 3)
														{
															$INV_STATD 		= 'Approved';
															$STATCOL		= 'success';
														}
														elseif($INV_STAT == 4)
														{
															$INV_STATD 		= 'Revise';
															$STATCOL		= 'warning';
														}
														elseif($INV_STAT == 5)
														{
															$INV_STATD 		= 'Reject';
															$STATCOL		= 'danger';
														}
														elseif($INV_STAT == 6)
														{
															$INV_STATD 		= 'Close';
															$STATCOL		= 'info';
														}
														elseif($INV_STAT == 7)
														{
															$INV_STATD 		= 'Waiting';
															$STATCOL		= 'warning';
														}
														elseif($INV_STAT == 9)
														{
															$INV_STATD 		= 'Void';
															$STATCOL		= 'danger';
														}
														else
														{
															$INV_STATD 		= 'Not Range';
															$STATCOL		= 'danger';
														}
														
														if($ISVOID == 0)
														{
															$ISVOIDD 		= 'Active';
															$STATCOL		= 'success';
														}
														elseif($ISVOID == 1)
														{
															$ISVOIDD 		= 'Void';
															$STATCOL		= 'danger';
														}
														
														$TTKCODE	= '';
														$sqlTTK		= "SELECT TTK_CODE FROM tbl_ttk_header WHERE TTK_NUM = '$IR_NUM' LIMIT 1";
														$qryTTK		= $this->db->query($sqlTTK)->result();
														foreach($qryTTK as $rowTTK) :
															$TTKCODE	= $rowTTK->TTK_CODE;
														endforeach;
														
														$sqlSPL		= "SELECT SPLCODE, SPLDESC, SPLTELP, SPLNPWP FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
														$qrySPL		= $this->db->query($sqlSPL)->result();
														foreach($qrySPL as $rowSPL) :
															$SPLCODE	= $rowSPL->SPLCODE;
															$SPLDESC	= $rowSPL->SPLDESC;
															$SPLTELP	= $rowSPL->SPLTELP;
															$SPLNPWP	= $rowSPL->SPLNPWP;
														endforeach;
												
														if ($j==1) {
															echo "<tr class=zebra1>";
															$j++;
														} else {
															echo "<tr class=zebra2>";
															$j--;
														}
														?>
																<td nowrap><?php print $INV_NUM; ?></td>
																<td nowrap><?php print $INV_CODE; ?></td>
																<td style="text-align:center" nowrap> <?php print date('d M Y', strtotime($INV_DATE)); ?></td>
																<td style="text-align:center" nowrap> <?php print date('d M Y', strtotime($INV_DUEDATE)); ?></td>
																<td nowrap><?php print $TTKCODE; ?></td>
																<td nowrap><?php print "$SPLCODE - $SPLDESC"; ?></td>
																<td nowrap><?php print $INV_NOTES; ?></td>
																<td style="text-align:right" nowrap><?php print number_format($INV_AMOUNTV, 2); ?></td>
																<td style="text-align:center" nowrap>
							                                    	<span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
							                                            <?php
							                                                echo $ISVOIDD;
							                                             ?>
							                                         </span>
							                                    </td>
													        </tr>
														<?php
													}
													endforeach;
												}
											?>
										</table>
						            </div>
								</form>
								<?php
							}
						?>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>

<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
<script>
	function showSrchAgain()
	{
		document.isfrmSrch.submitSrchAgain.click();
	}
	
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		isCheck = document.getElementById('isCheck').value;
		
		if(isCheck == 0)
		{
			swal('Please search and check one of LPM Number');
			document.getElementById('txtSearch').focus();
			return false;
		}
		else
		{
			title = 'Select Item';
			w = 900;
			h = 550;
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			form.target = 'formpopup';
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>