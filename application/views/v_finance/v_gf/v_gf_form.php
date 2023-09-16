<?php
/* 
	* Author		= Dian Hermanto
    * Create Date  = 21 Agustus 2023
    * File Name    = v_gf_form.php
	* Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

if($task == 'add')
{
	$GF_NUM				= "";
	$GF_CODE			= "";
	$GF_NAME			= "";
	$GF_DATES			= date('d/m/Y');
	$GF_DATEE			= date('d/m/Y');
	$GF_PENJAMIN		= "";
	$GF_NILAI_JAMINAN	= 0;
	$GF_FILENAME		= "";
	$PRJCODE			= "";
	$SPLCODE			= "";
	$GF_STATDOC			= 0;
	$GF_STATUS 			= 0;
	$WO_NUM 			= "";
	$WO_CODE 			= "";
	$WO_DATE 			= "";
	$WO_VALUE 			= 0;
}
else
{
	$GF_DATES			= date('d/m/Y', strtotime($GF_DATES));
	$GF_DATEE			= date('d/m/Y', strtotime($GF_DATEE));
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
			if($TranslCode == 'Setting')$Setting = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'whName')$whName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
			if($TranslCode == 'WHLocation')$WHLocation = $LangTransl;
			if($TranslCode == 'whProduction')$whProduction = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'WOList')$WOList = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'dokLam')$dokLam = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= "Kode gudang tidak boleh kosong.";
			$alert2		= "Nama gudang tidak boleh kosong.";
			$alert3		= "Lokasi gudang tidak boleh kosong.";
			$alert4		= "Pilih salah satu lokasi proyek ...!";
			$alert5 	= "Anda yakin akan menghapus file ini?";
		}
		else
		{
			$alert1		= "Warehouse Code can not be empty.";
			$alert2		= "Warehouse Name can not be empty.";
			$alert3		= "Warehouse location can not be empty.";
			$alert4		= "Select one of the project location ...";
			$alert5 	= "Are you sure want to delete this file?";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $PRJCODE;

		// START : APPROVE PROCEDURE
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				//$appReady	= $APP_STEP;
				//if($resC_App == 0)
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
				
				if($GEJ_STAT == 3 && $resC_App > 0)
				{
					$canApprove = 1;
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
				$APPROVE_AMOUNT = $Journal_Amount;
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
		    	<?php echo $mnName; ?>
		  	</h1>
		</section>

		<script>
			function chekData()
			{
				GF_CODE			= document.getElementById('GF_CODE').value;
				GF_NAME			= document.getElementById('GF_NAME').value;
				GF_PENJAMIN		= document.getElementById('GF_PENJAMIN').value;
				GF_NILAI_JAM	= document.getElementById('GF_NILAI_JAMINAN').value;
				PRJCODE			= document.getElementById('PRJCODE').value;
				SPLCODE			= document.getElementById('SPLCODE').value;
				GF_STATUS		= document.getElementById('GF_STATUS').value;
				
				if(GF_CODE == "")
				{
					swal('No. Jaminan tidak boleh kosong',
		            {
		                icon: "warning",
		            })
		            .then(function()
		            {
		                swal.close();
		                $('#GF_CODE').focus();
		            });
		            return false;
				}

				if(GF_NAME == "")
				{
					swal('Nama Jaminan tdiak boleh kosong',
		            {
		                icon: "warning",
		            })
		            .then(function()
		            {
		                swal.close();
		                $('#GF_NAME').focus();
		            });
		            return false;
				}

				if(GF_PENJAMIN == '')
				{
					swal('Badan Penjamin tidak boleh kosong',
		            {
		                icon: "warning",
		            })
		            .then(function()
		            {
		                swal.close();
		                $('#GF_PENJAMIN').focus();
		            });
		            return false;
				}

				if(GF_NILAI_JAM == 0)
				{
					swal('Nilai Jaminan tidak boleh nol',
		            {
		                icon: "warning",
		            })
		            .then(function()
		            {
		                swal.close();
		                $('#GF_NILAI_JAMINANX').focus();
		            });
		            return false;
				}
				
				if(PRJCODE == "")
				{
					swal('Belum memilih nama proyek',
		            {
		                icon: "warning",
		            })
		            .then(function()
		            {
		                swal.close();
		                $('#PRJCODE').focus();
		            });
		            return false;
				}
				
				if(SPLCODE == "")
				{
					swal('Belum memilih nama supplier',
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
				
				if(GF_STATUS == 0)
				{
					swal('Silahkan pilih status berkas jaminan',
		            {
		                icon: "warning",
		            })
		            .then(function()
		            {
		                swal.close();
		                $('#GF_STATUS').focus();
		            });
		            return false;
				}
			}
		</script>

		<section class="content">
		    <form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return chekData()">
			    <div class="row">
			        <div class="col-md-6">
			            <div class="box box-primary">
	                        <div class="box-header with-border" style="display:  none;">
	                            <h3 class="box-title">&nbsp;</h3>
	                        </div>
			                <div class="box-body chart-responsive">
				                <?php
				                    $sqlCAPPH	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp'";
				                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
									
				                	if($resCAPPH == 0)
				                	{
				                		$disButton 		= 1;
				                		if($LangID == 'IND')
										{
											$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
										}
										else
										{
											$zerSetApp	= "There are no arrangements for the approval of this document.";
										}
				                	}
				                	else
										$disButton 	= 0;
				                ?>

			                    <?php
			                    	if($resCAPPH == 0)
				                	{
			                    		?>
				                			<div class="alert alert-warning alert-dismissible">
								                <?php echo $zerSetApp; ?>
							              	</div>
			                            <?php
			                        }
			                    ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">No. Jaminan</label>
		                            <input type="hidden" class="form-control" name="GF_NUM" id="GF_NUM" value="<?php echo $GF_NUM; ?>">
						            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
						            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		                          	<div class="col-sm-9">
			                           	<input type="text" class="form-control" name="GF_CODE" id="GF_CODE" value="<?php echo $GF_CODE; ?>" placeholder="Nomor Jaminan">
			                        </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Nama Jaminan</label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="GF_NAME" id="GF_NAME" value="<?php echo $GF_NAME; ?>" placeholder="Nama Jaminan" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Periode</label>
		                            <div class="col-sm-4">
		                            	<div class="input-group date">
			                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                	<input type="text" name="GF_DATES" class="form-control pull-left" id="datepicker" style="max-width: 100px" value="<?php echo $GF_DATES; ?>">
		                                </div>
			                        </div>
		                            <div class="col-sm-1">
		                            	<label for="inputName" class="control-label">s.d.</label>
		                            </div>
		                            <div class="col-sm-4 pull-right">
			                            <div class="input-group date">
			                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                	<input type="text" name="GF_DATEE" class="form-control" id="datepicker1" style="max-width: 100px" value="<?php echo $GF_DATEE; ?>">
			                            </div>
			                        </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Badan Penjamin</label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="GF_PENJAMIN" id="GF_PENJAMIN" value="<?php echo $GF_PENJAMIN; ?>" placeholder="Badan Penjamin" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Nilai Jaminan</label>
		                            <div class="col-sm-3">
		                                <input type="text" class="form-control" name="GF_NILAI_JAMINANX" id="GF_NILAI_JAMINANX" value="<?php echo number_format($GF_NILAI_JAMINAN,2); ?>" placeholder="Nilai Jaminan (Rp)" onBlur="chgAmount(this);" style="text-align: right;" onKeyPress="return isIntOnlyNew(event);" />
		                                <input type="hidden" class="form-control" name="GF_NILAI_JAMINAN" id="GF_NILAI_JAMINAN" value="<?php echo $GF_NILAI_JAMINAN; ?>" />
		                            </div>
		                            <label for="inputName" class="col-sm-2 control-label">Status Dok.</label>
		                            <div class="col-sm-4">
		                                <select name="GF_STATDOC" id="GF_STATDOC" class="form-control select2" >
		                                    <option value="0" > --- </option>
		                                    <option value="1" <?php if($GF_STATDOC == 1) { ?> selected <?php } ?> > Digunakan </option>
		                                    <option value="2" <?php if($GF_STATDOC == 2) { ?> selected <?php } ?> > Selesai </option>
		                                    <option value="3" <?php if($GF_STATDOC == 3) { ?> selected <?php } ?> > Diperpanjang </option>
		                                    <option value="4" <?php if($GF_STATDOC == 4) { ?> selected <?php } ?> > Kadaluwarsa </option>
		                                </select>
		                            </div>
		                        </div>
		                        <script type="text/javascript">
		                        	function chgAmount(thisVal)
		                        	{
			                        	var thisVal			= eval(thisVal).value.split(",").join("");
										document.getElementById('GF_NILAI_JAMINAN').value 	= thisVal;
										document.getElementById('GF_NILAI_JAMINANX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
									}
		                        </script>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-6">
			            <div class="box box-primary">
	                        <div class="box-header with-border" style="display: none;">
	                            <h3 class="box-title"><i class="fa fa-share-alt" style="padding-right: 10px"></i>Dokumen Terhubung</h3>
	                        </div>
			                <div class="box-body chart-responsive">
			                	<div class="row">
		                            <div class="col-sm-12">
		                            	<label for="inputName" class="control-label">Proyek</label>
		                                <select name="PRJCODE" id="PRJCODE" class="form-control select2" >
		                                    <option value="" > --- </option>
		                                    <?php
												$s_PRJ          = "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
		                                        $r_PRJ          = $this->db->query($s_PRJ);
		                                        foreach($r_PRJ->result() as $rw_PRJ):
		                                            $PRJCODE1    = $rw_PRJ->PRJCODE;
		                                            $PRJNAME1    = $rw_PRJ->PRJNAME;
													?>
													<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>>
														<?php echo "$PRJCODE1 - $PRJNAME1"; ?>
													</option>
													<?php
												endforeach;
											?>
		                                </select>
		                            </div>
			                    </div>
			                	<div class="row">
		                            <div class="col-sm-7">
		                            	<label for="inputName" class="control-label">Supplier</label>
		                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" >
		                                    <option value="" > --- </option>
		                                    <?php
												$s_SPL          = "SELECT DISTINCT A.SPLCODE, B.SPLDESC FROM tbl_wo_header A
																	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE ORDER BY B.SPLDESC";
		                                        $r_SPL          = $this->db->query($s_SPL);
		                                        foreach($r_SPL->result() as $rw_SPL):
		                                            $SPLCODE1    = $rw_SPL->SPLCODE;
		                                            $SPLDESC1    = $rw_SPL->SPLDESC;
													?>
													<option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>>
														<?php echo "$SPLCODE1 - $SPLDESC1"; ?>
													</option>
													<?php
												endforeach;
											?>
		                                </select>
		                            </div>
									<div class="col-sm-5">
		                            	<label for="inputName" class="control-label"><span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span></label>
				                		<input type="file" class="form-control" name="userfile[]" id="userfile" accept=".pdf" multiple>
				                	</div>
		                        </div>
			                	<div class="row">
		                            <div class="col-sm-5">
										<label for="inputName" class="control-label">No. SPK</label>
		                                <div class="input-group">
		                                    <div class="input-group-btn">
		                                        <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    <input type="text" autocomplete="off" class="form-control" name="WO_CODE" id="WO_CODE" value="<?php echo $WO_CODE; ?>"  data-toggle="modal" data-target="#mdl_addSPK">
		                                    <input type="hidden" class="form-control" name="WO_NUM" id="WO_NUM" style="max-width:200px" value="<?php echo $WO_NUM; ?>" >
		                                </div>
		                            </div>
		                            <div class="col-sm-4">
										<label for="inputName" class="control-label">Nilai SPK</label>
		                                <input type="text" class="form-control" style="text-align: right;" name="WO_VALUEX" id="WO_VALUEX" value="<?php echo number_format($WO_VALUE, 2); ?>" readonly>
		                                <input type="hidden" class="form-control" style="text-align: right;" name="WO_VALUE" id="WO_VALUE" value="<?php echo $WO_VALUE; ?>">
		                            </div>
		                            <div class="col-sm-3">
										<label for="inputName" class="control-label">Status</label>
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $GF_STATUS; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												$disabled 	= 0;
												if($task == 'add')
												{
													if($ISCREATE == 1)
													{
														?>
															<select name="GF_STATUS" id="GF_STATUS" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																<option value="0">---</option>
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
												}
												else
												{
													if(($GF_STATUS == 1 || $GF_STATUS == 4) && $ISCREATE == 1)
													{
														//$disButton	= 0;
														?>
															<select name="GF_STATUS" id="GF_STATUS" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																<option value="0">---</option>
																<option value="1"<?php if($GF_STATUS == 1) { ?> selected <?php } ?>>New</option>
																<option value="2"<?php if($GF_STATUS == 2) { ?> selected <?php } ?>>Confirm</option>
															</select>
														<?php
													}
													elseif(($GF_STATUS == 2 || $GF_STATUS == 7) && $ISAPPROVE == 1)
													{
														//$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;
														
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$GF_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;										
														?>
															<select name="GF_STATUS" id="GF_STATUS" class="form-control select2" >
																<option value="0">---</option>
																<option value="1"<?php if($GF_STATUS == 1) { ?> selected <?php } ?> disabled>New</option>
																<option value="2"<?php if($GF_STATUS == 2) { ?> selected <?php } ?>>Confirm</option>
																<option value="3"<?php if($GF_STATUS == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																<option value="4"<?php if($GF_STATUS == 4) { ?> selected <?php } ?> >Revising</option>
																<option value="5"<?php if($GF_STATUS == 5) { ?> selected <?php } ?> >Rejected</option>
																<option value="6"<?php if($GF_STATUS == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GF_STATUS == 7) { ?> selected <?php } ?> >Waiting</option>
																<option value="9"<?php if($GF_STATUS == 9) { ?> selected <?php } ?> disabled>Void</option>
															</select>
														<?php
													}
													else
													{
														$disButton	= 1;
														?>
															<select name="GF_STATUS" id="GF_STATUS" class="form-control select2" >
																<option value="1"<?php if($GF_STATUS == 1) { ?> selected <?php } ?> disabled>New</option>
																<option value="2"<?php if($GF_STATUS == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																<option value="3"<?php if($GF_STATUS == 3) { ?> selected <?php } ?> disabled>Approved</option>
																<option value="4"<?php if($GF_STATUS == 4) { ?> selected <?php } ?> disabled>Revising</option>
																<option value="5"<?php if($GF_STATUS == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																<option value="6"<?php if($GF_STATUS == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GF_STATUS == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																<option value="9"<?php if($GF_STATUS == 9) { ?> selected <?php } ?> disabled>Void</option>
															</select>
														<?php
													}
												}
											// END : FOR ALL APPROVAL FUNCTION
				                        ?>
		                            </div>
								</div>
			                	<div class="row">
		                            <div class="col-sm-5">
										<label for="inputName" class="control-label">&nbsp;</label>
				                        <div class="input-group">
				                            <?php
												if(($ISCREATE == 1 || $ISAPPROVE == 1) && $disButton == 0)
												{
													if($task=='add')
													{
														?>
															<button class="btn btn-primary">
															<i class="fa fa-save"></i></button>&nbsp;
														<?php
													}
													else
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i></button>&nbsp;
														<?php
													}
												}
											
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
											?>
				                        </div>
		                            </div>
		                        </div>
			                </div>
			            </div>
			        </div>

					<?php
						$shAttc 	= 0;
						if($GF_STATUS == 1 || $GF_STATUS == 4)
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
							$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
											WHERE REF_NUM = '$GF_NUM' AND PRJCODE = '$PRJCODE'";
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
									<div class="col-sm-12">
										<?php
											// GET Upload Doc TRx
											$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
															WHERE REF_NUM = '$GF_NUM' AND PRJCODE = '$PRJCODE'";
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
																$REF_CODE		= $rDOC->REF_CODE;
																$UPL_PRJCODE	= $rDOC->PRJCODE;
																$UPL_DATE		= $rDOC->UPL_DATE;
																$UPL_FILENAME	= $rDOC->UPL_FILENAME;
																$UPL_FILESIZE	= $rDOC->UPL_FILESIZE;
																$UPL_FILETYPE	= $rDOC->UPL_FILETYPE;

																?>
																	<div class="itemFile_<?=$newRow?>">
																		<?php
																			if($UPL_FILETYPE == 'application/pdf') $fileicon = "fa-file-pdf-o";
																			else $fileicon = "fa-file-image-o";

																			if($GF_STATUS == 1 || $GF_STATUS == 4)
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
																							<a href="<?php echo site_url("c_finance/c_grntf1l3/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
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
																							<a href="<?php echo site_url("c_finance/c_grntf1l3/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
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
			    </div>
			</form>

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
					$getDATA 		= "$PRJCODE~$SPLCODE";
		    	?>
		    	<div class="modal fade" id="mdl_addSPK" name='mdl_addSPK' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $WOList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example4" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Code; ?></th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Date; ?> </th>
			                                                        <th width="68%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Description; ?></th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Amount; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetailWO" name="btnDetailWO">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idCloseWO" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheckSPK" id="rowCheckSPK" value="0">
                                      	<button type="button" id="idCloseSPK" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$(document).ready(function()
					{
						$('#example4').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": {
					        	"url": "<?php echo site_url('c_finance/c_grntf1l3/s3l4llW_0L')?>",
					        	"type": "POST",
					        	"data": function(data)
					        	{
					        		data.SPLCODE = $('#SPLCODE').val();
					        		data.PRJCODE = $('#PRJCODE').val();
					        	}
					        },
					        // "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,1,2], className: 'dt-body-center' },
											{ targets: [4], className: 'dt-body-right' },
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						$("#idRefresh1").click(function()
						{
							$('#example4').DataTable().ajax.reload();
						});
					});
					function pickThisWO(thisobj) 
					{
						console.log(thisobj);
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheckSPK").val(favorite.length);
					}

					$(document).ready(function()
					{
						$("#btnDetailWO").click(function()
					    {
							let totChck 	= $("#rowCheckSPK").val();
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
						      	add_WO($(this).val());
						    });
                        	document.getElementById("idCloseSPK").click()
					    });

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
					});
				</script>
	    	<!-- ============ END MODAL =============== -->
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
</script>

<script>
	var decFormat		= 2;
	
	function add_WO(strItem) 
	{
		arrItem = strItem.split('|');
		WO_NUM		= arrItem[0];
		WO_CODE 	= arrItem[1];
		WO_VALUE 	= arrItem[2];
		document.getElementById('WO_NUM').value		= WO_NUM;
		document.getElementById('WO_CODE').value	= WO_CODE;
		document.getElementById('WO_VALUE').value 	= WO_VALUE;
		document.getElementById('WO_VALUEX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VALUE)),2));
	}

	function trashItemFile(row, fileName)
	{		
		swal({
			text: "<?php echo $alert5; ?>",
			icon: "warning",
			buttons: ["No", "Yes"],
		}).then((willDelete) => {
			if (willDelete) {
				let GF_NUM	= "<?php echo $GF_NUM; ?>";
				let PRJCODE	= "<?php echo $PRJCODE; ?>";
				$.ajax({
					type: "POST",
					url: "<?php echo site_url("c_finance/c_grntf1l3/trashFile"); ?>",
					data: {GF_NUM:GF_NUM, PRJCODE:PRJCODE, fileName:fileName},
					beforeSend: function(xhr) {
						//*** console.log(xhr);
					},
					success: function(callback) {
						//*** console.log(callback);
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
		// const url 		= "<?php // echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		const url 		= "<?php echo 'https://sdbpplus.nke.co.id/assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		// const urlOpen	= "<?php // echo base_url(); ?>";
		const urlOpen	= "<?php echo "https://sdbpplus.nke.co.id/"; ?>";
		// const urlDom	= "<?php // echo "https://sdbpplus.nke.co.id/"; ?>";
		let PRJCODE 	= "<?php echo $PRJCODE; ?>";
		let path 		= "GFile/"+PRJCODE+"/"+fileName+"";
		// let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen+'&base_urlDom='+urlDom;
		let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		let left = (screen.width/2)-(w/2);
		let top = (screen.height/2)-(h/2);
		return window.open(url+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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