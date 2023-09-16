<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Junli 2018
	* File Name	= profit_loss_view.php
	* Location		= -
*/

if($viewType == 1)
{
	$repDate 	= date('ymdHis');
	$fileNm 	= "ItemBudgetDet_".$repDate;
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileNm.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$Periode1 = date('YmdHis');
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ			= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;
$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
$ITM_NAME 		= "-";
$sqlITM			= "SELECT ITM_NAME FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE'";
$resITM			= $this->db->query($sqlITM)->result();
foreach($resITM as $rowITM) :
	$ITM_NAME 	= $rowITM->ITM_NAME;
endforeach;
$ITMDESC 		= "$ITM_CODE - $ITM_NAME";

$showINV 		= 0;
if($DefEmp_ID == 'D15040004221' || $DefEmp_ID == 'E13120003019' || $DefEmp_ID == 'W98060000156' || $DefEmp_ID == 'L15120004415')
	$showINV 	= 1;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title>Laporan Detil Transaksi Item <?=$ITMDESC?></title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
		<!-- DataTables -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
		<!-- daterange picker -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
		<!-- Bootstrap time Picker -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- DataTables -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
		<!-- Select2 -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body style="overflow:auto">
		<div class="page">
			<section class="content">
		        <table width="100%" border="0" style="size:auto">
		            <tr>
		                <td width="100%" class="style2" style="text-align:left;">&nbsp;</td>
		            </tr>
		          	<tr>
		                <td class="style2" style="text-align:left;">
		                    <table width="100%" style="size:auto; font-size:12px;">
		                        <tr style="text-align:left;">
		                            <td width="10%" nowrap>PROYEK</td>
		                          	<td width="1%">:</td>
		                            <td style="text-align:left; font-weight:bold"><?php echo strtoupper($PRJNAME); ?></td>
		                      	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>NAMA LAPORAN</td>
		                          	<td>:</td>
		                          	<td>LAPORAN HISTORI ITEM</td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>KODE ITEM</td>
		                          	<td>:</td>
		                          	<td><?php echo $ITM_CODE;?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>NAMA ITEM</td>
		                          	<td>:</td>
		                          	<td><?php echo $ITM_NAME;?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                          <td nowrap valign="top">&nbsp;</td>
		                          <td>&nbsp;</td>
		                          <td>&nbsp;</td>
		                        </tr>
		                    </table>
				    	</td>
		            </tr>
		            <tr>
						<td class="style2" style="text-align:left; font-size:10px">
		              	<table width="100%" border="1" style="size:auto; font-size:10px;" rules="all">
		                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:11px;">
		                    	<td width="5%" rowspan="2" style="text-align:center">&nbsp;No.&nbsp;</td>
		                      	<td width="10%" rowspan="2" style="text-align:center">NO. DOK.</td>
		                      	<td width="5%" rowspan="2" style="text-align:center">TANGGAL</td>
		                        <td width="20%" rowspan="2" style="text-align:center">DESKRIPSI</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>BUDGET AWAL</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>SPP</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>OP / SPK</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>LPM</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>VCASH / VLK / PPD</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>SISA THD REQUEST</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>SISA THD BUDGET</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>SISA ITEM</td>
		                    </tr>
		                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:11px;">
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                        <td width="5%" style="text-align:center" nowrap>VOL</td>
		                        <td width="5%" style="text-align:center" nowrap>TOTAL</td>
		                    </tr>
		                    <?php
		                    	$addQJOBPAR = "";
				                if($JOBPARENT[0] != 1)
				                {
				                    $joinJOBPAR     = join("','", $JOBPARENT);
				                    $addQJOBPAR     = "WHERE JOBPARENT IN ('$joinJOBPAR')";
				                }

				                $JOBCODEID  = [];
				                $getJOBPAR  = "SELECT JOBCODEID FROM tbl_joblist_detail_$PRJCODEVW $addQJOBPAR";
				                $resJOBPAR  = $this->db->query($getJOBPAR);
				                if($resJOBPAR->num_rows() > 0)
				                {
				                    foreach($resJOBPAR->result() as $rJOB):
				                        $JOBCODEID[] = $rJOB->JOBCODEID;
				                    endforeach;
				                }

				                $JoinJOBPAR     = join("','", $JOBCODEID);
		                    	$RAPT_VOL 	= 0;
		                    	$RAPT_VAL 	= 0;
		                    	$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		                    	/*$s_ITMT		= "SELECT SUM(ITM_VOLM + AMD_VOL - AMDM_VOL) AS RAPT_VOL, SUM(ITM_BUDG + AMD_VAL - AMDM_VAL) AS RAPT_VAL
		                    					FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
		                    	$s_ITMT		= "SELECT SUM(ITM_VOLM) AS RAPT_VOL, SUM(ITM_BUDG) AS RAPT_VAL
		                    					FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'
		                    						AND JOBCODEID IN ('$JoinJOBPAR')";
								$rw_ITMT	= $this->db->query($s_ITMT)->result();
								foreach($rw_ITMT as $rw_ITMT) :
									$RAPT_VOL = $rw_ITMT->RAPT_VOL;
									$RAPT_VAL = $rw_ITMT->RAPT_VAL;
								endforeach;

								$no			= 0;
								$no2 		= 0;
								$totAm		= 0;
								$RAP_VOL 	= 0;
								$RAP_VAL 	= 0;
								$PRT_VOL 	= 0;
								$PRT_VAL 	= 0;
								$POT_VOL 	= 0;
								$POT_VAL 	= 0;
								$TRXR_VOL 	= 0;
								$TRXR_VAL 	= 0;

								$IRT_VOL 	= 0;
								$IRT_VAL 	= 0;
								$UMT_VOL 	= 0;
								$UMT_VAL 	= 0;
								$RAPIT_VOL 	= 0;
								$RAPIT_VAL 	= 0;
								$RAPUT_VOL 	= 0;
								$RAPUT_VAL 	= 0;
								$JOBCODEID2 = "";
								$ITMT_VOL 	= 0;
								$ITMT_VAL 	= 0;
								$colStyl 	= "background-color: rgb(233, 233, 233);";
								$colStyl2 	= "";
								$sqlJD		= "SELECT * FROM tbl_item_log WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND DOC_CATEG != 'UM'
													AND DOC_DATE BETWEEN '$DATES' AND '$DATEE' AND DOC_STAT NOT IN (5,9) AND JOBCODEID IN ('$JoinJOBPAR')
												ORDER BY JOBCODEID, DOC_DATE, DOC_CODE, DOC_ID";
								$resJD		= $this->db->query($sqlJD)->result();
								foreach($resJD as $rowJD) :
									$no				= $no+1;
									$DOC_ID 		= $rowJD->DOC_ID;
									$DOC_NUM 		= $rowJD->DOC_NUM;
									$DOC_CODE 		= $rowJD->DOC_CODE;
									$DOC_DATE 		= $rowJD->DOC_DATE;
									$DOC_CATEG 		= $rowJD->DOC_CATEG;
									$REF_ID 		= $rowJD->REF_ID;
									$JOBCODEID 		= $rowJD->JOBCODEID;
									$ITM_CODE 		= $rowJD->ITM_CODE;
									$ITM_GROUP 		= $rowJD->ITM_GROUP;
									$ITM_UNIT 		= $rowJD->ITM_UNIT;
									$DOC_DESC 		= $rowJD->DOC_DESC;
									$RAP_VOL 		= $rowJD->RAP_VOL;
									$RAP_VAL 		= $rowJD->RAP_VAL;
									$DOC_VOL 		= $rowJD->DOC_VOL;
									$DOC_VAL 		= $rowJD->DOC_VAL;
									$DOC_CVOL 		= $rowJD->DOC_CVOL;
									$DOC_CVAL 		= $rowJD->DOC_CVAL;
									$DOC_DESC 		= $rowJD->DOC_DESC;

									$RAPVW_VOL 		= 0;
									$RAPVW_VAL 		= 0;
									if($JOBCODEID != $JOBCODEID2)
									{
										$RAPVW_VOL 	= $RAP_VOL;
										$RAPVW_VAL 	= $RAP_VAL;
										$POT_VOL 	= 0;
										$POT_VAL 	= 0;
										$IRT_VOL 	= 0;
										$IRT_VAL 	= 0;
										?>
			                                <tr style="<?=$colStyl?>">
			                                    <td style="text-align:center" colspan="20">&nbsp;</td>
			                                </tr>
			                    		<?php
			                    		if($no == 1)
			                    		{
										?>
				                            <tr>
				                                <td style="text-align:center" colspan="18">&nbsp;</td>
				                                <td style="text-align:right;"><?php echo number_format($RAPT_VOL,2); ?></td>
				                                <td style="text-align:right;"><?php echo number_format($RAPT_VAL,2); ?></td>
				                            </tr>
			                    		<?php
			                    		}
									}

									$PR_VOL 		= 0;
									$PR_VAL 		= 0;
									$PO_VOL 		= 0;
									$PO_VAL 		= 0;
									$IR_VOL 		= 0;
									$IR_VAL 		= 0;
									$UM_VOL 		= 0;
									$UM_VAL 		= 0;
									$REF_NUM 		= "";
									if($DOC_CATEG == 'AMD')
									{
										$AMD_VOL 	= $DOC_VOL-$DOC_CVOL;
										$AMD_VAL 	= $DOC_VAL-$DOC_CVAL;
										$RAPVW_VOL 	= $AMD_VOL;
										$RAPVW_VAL 	= $AMD_VAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$RAPT_VOL 	= $RAPT_VOL + $AMD_VOL;
											$RAPT_VAL 	= $RAPT_VAL + $AMD_VAL;
											$RAPIT_VOL 	= $RAPIT_VOL + $AMD_VOL;
											$RAPIT_VAL 	= $RAPIT_VAL + $AMD_VAL;
										}
										elseif($JOBCODEID == $JOBCODEID2)
										{
											$RAPT_VOL 	= $RAPT_VOL + $AMD_VOL;
											$RAPT_VAL 	= $RAPT_VAL + $AMD_VAL;
											$RAPIT_VOL 	= $RAPIT_VOL + $AMD_VOL;
											$RAPIT_VAL 	= $RAPIT_VAL + $AMD_VAL;
										}
										else
										{
											$RAPT_VOL 	= $AMD_VOL;
											$RAPT_VAL 	= $AMD_VAL;
											$RAPIT_VOL 	= $AMD_VOL;
											$RAPIT_VAL 	= $AMD_VAL;
										}

										$REMA_VOL 		= $RAP_VOL + $RAPIT_VOL - $PRT_VOL;
										$REMA_VAL 		= $RAP_VAL + $RAPIT_VAL - $PRT_VAL;

										$REMB_VOL 		= $RAP_VOL + $RAPIT_VOL - $RAPUT_VOL;
										$REMB_VAL 		= $RAP_VAL + $RAPIT_VAL - $RAPUT_VAL;
									}
									else if($DOC_CATEG == 'PR')
									{
										$PR_VOL 	= $DOC_VOL-$DOC_CVOL;
										//$PR_VAL 	= $DOC_VAL-$DOC_CVAL;
										$PR_VAL 	= 0;

										$ITMT_VOL 	= $ITMT_VOL + $PR_VOL;
										$ITMT_VAL 	= $ITMT_VAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$PRT_VOL 	= $PRT_VOL + $PR_VOL;
											$PRT_VAL 	= $PRT_VAL + $PR_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL + $PR_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL;
										}
										elseif($JOBCODEID == $JOBCODEID2)
										{
											$PRT_VOL 	= $PRT_VOL + $PR_VOL;
											$PRT_VAL 	= $PRT_VAL + $PR_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL + $PR_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL;
										}
										else
										{
											$PRT_VOL 	= $PR_VOL;
											$PRT_VAL 	= $PR_VAL;
											$RAPUT_VOL 	= $PR_VOL;
											$RAPUT_VAL 	= $PR_VAL;
										}

										$REMA_VOL 		= 0;
										$REMA_VAL 		= 0;

										$REMB_VOL 		= $RAPT_VOL - $RAPUT_VOL;
										$REMB_VAL 		= $RAPT_VAL - $RAPUT_VAL;
									}
									elseif($DOC_CATEG == 'PO')
									{
										$PO_VOL 	= $DOC_VOL-$DOC_CVOL;
										$PO_VAL 	= $DOC_VAL-$DOC_CVAL;

										$ITMT_VOL 	= $ITMT_VOL;
										$ITMT_VAL 	= $ITMT_VAL + $PO_VAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$POT_VOL 	= $POT_VOL + $PO_VOL;
											$POT_VAL 	= $POT_VAL + $PO_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL + $PO_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL + $PO_VAL;
										}
										elseif($JOBCODEID == $JOBCODEID2)
										{
											$POT_VOL 	= $POT_VOL + $PO_VOL;
											$POT_VAL 	= $POT_VAL + $PO_VAL;
											$RAPUT_VOL 	= $POT_VOL;
											$RAPUT_VAL 	= $POT_VAL;
										}
										else
										{
											$POT_VOL 	= $PO_VOL;
											$POT_VAL 	= $PO_VAL;
											$RAPUT_VOL 	= $PO_VOL;
											$RAPUT_VAL 	= $PO_VAL;
										}

										$REMA_VOL 		= $PRT_VOL - $POT_VOL;
										$REMA_VAL 		= $POT_VAL - $POT_VAL;

										//$REMB_VOLV 	= "$RAPT_VOL - $POT_VOL";
										$REMB_VOL 		= $RAPT_VOL - $POT_VOL;
										//echo "$no $REMB_VOL = $REMB_VOLV<br>";
										$REMB_VAL 		= $RAPT_VAL - $POT_VAL;
									}
									elseif($DOC_CATEG == 'IR')
									{
										$IR_VOL 	= $DOC_VOL-$DOC_CVOL;
										$IR_VAL 	= $DOC_VAL-$DOC_CVAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$IRT_VOL 	= $IRT_VOL + $IR_VOL;
											$IRT_VAL 	= $IRT_VAL + $IR_VAL;
										}
										elseif($JOBCODEID == $JOBCODEID2)
										{
											$IRT_VOL 	= $IRT_VOL + $IR_VOL;
											$IRT_VAL 	= $IRT_VAL + $IR_VAL;
										}
										else
										{
											$IRT_VOL 	= $IR_VOL;
											$IRT_VAL 	= $IR_VAL;
										}

										$REMA_VOL 		= $POT_VOL - $IRT_VOL;
										$REMA_VAL 		= $POT_VAL - $IRT_VAL;

										$REMB_VOL 		= 0;
										$REMB_VAL 		= 0;

										$INV_REF 		= $rowJD->INV_REF;
										$BP_REF 		= $rowJD->BP_REF;
										if(($INV_REF != "" || $BP_REF != "") AND $showINV == 1)
											$REF_NUM 	= "$INV_REF. $BP_REF";
									}
									elseif($DOC_CATEG == 'UM')
									{
										$UM_VOL 	= $DOC_VOL-$DOC_CVOL;
										$UM_VAL 	= $DOC_VAL-$DOC_CVAL;

										if($JOBCODEID == $JOBCODEID2)
										{
											$RAP_VOL 	= $IRT_VOL;
											$RAP_VAL 	= $IRT_VAL;
											$UMT_VOL 	= $UMT_VOL + $UM_VOL;
											$UMT_VAL 	= $UMT_VAL + $UM_VAL;
										}
										$REMA_VOL 		= $RAP_VOL - $UM_VOL;
										$REMA_VAL 		= $RAP_VAL - $UM_VAL;

										$REMB_VOL 		= 0;
										$REMB_VAL 		= 0;
									}
									elseif($DOC_CATEG == 'WO')
									{
										$PO_VOL 	= $DOC_VOL-$DOC_CVOL;
										$PO_VAL 	= $DOC_VAL-$DOC_CVAL;

										$ITMT_VOL 	= $ITMT_VOL + $PO_VOL;
										$ITMT_VAL 	= $ITMT_VAL + $PO_VAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$POT_VOL 	= $POT_VOL + $PO_VOL;
											$POT_VAL 	= $POT_VAL + $PO_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL + $PO_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL + $PO_VAL;
										}
										elseif($JOBCODEID == $JOBCODEID2)
										{
											$POT_VOL 	= $PO_VOL;
											$POT_VAL 	= $PO_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL;
										}
										else
										{
											$POT_VOL 	= $PO_VOL;
											$POT_VAL 	= $PO_VAL;
											$RAPUT_VOL 	= $PO_VOL;
											$RAPUT_VAL 	= $PO_VAL;
										}

										$REMA_VOL 		= 0;
										$REMA_VAL 		= 0;

										$REMB_VOL 		= $RAPT_VOL - $RAPUT_VOL;
										$REMB_VAL 		= $RAPT_VAL - $RAPUT_VAL;
									}
									elseif($DOC_CATEG == 'OPN')
									{
										$UM_VOL 	= $DOC_VOL-$DOC_CVOL;
										$UM_VAL 	= $DOC_VAL-$DOC_CVAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$UMT_VOL 	= $UMT_VOL + $UM_VOL;
											$UMT_VAL 	= $UMT_VAL + $UM_VAL;
										}
										else
										{
											$UMT_VOL 	= $UM_VOL;
											$UMT_VAL 	= $UM_VAL;
										}

										$REMA_VOL 		= $POT_VOL - $UMT_VOL;
										$REMA_VAL 		= $POT_VAL - $UMT_VAL;

										$REMB_VOL 		= 0;
										$REMB_VAL 		= 0;

										$INV_REF 		= $rowJD->INV_REF;
										$BP_REF 		= $rowJD->BP_REF;
										if(($INV_REF != "" || $BP_REF != "") AND $showINV == 1)
											$REF_NUM 	= "$INV_REF. $BP_REF";
									}
									elseif($DOC_CATEG == 'VCASH' || $DOC_CATEG == 'VLK' || $DOC_CATEG == 'PPD')
									{
										$UM_VOL 	= $DOC_VOL-$DOC_CVOL;
										$UM_VAL 	= $DOC_VAL-$DOC_CVAL;

										$ITMT_VOL 	= $ITMT_VOL + $UM_VOL;
										$ITMT_VAL 	= $ITMT_VAL + $UM_VAL;

										if($JOBCODEID == $JOBCODEID2 && $DOC_CATEG == $DOC_CATEG2)
										{
											$UMT_VOL 	= $UMT_VOL + $UM_VOL;
											$UMT_VAL 	= $UMT_VAL + $UM_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL + $UM_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL + $UM_VAL;
										}
										elseif($JOBCODEID == $JOBCODEID2)
										{
											$UMT_VOL 	= $UM_VOL;
											$UMT_VAL 	= $UM_VAL;
											$RAPUT_VOL 	= $RAPUT_VOL + $UM_VOL;
											$RAPUT_VAL 	= $RAPUT_VAL + $UM_VAL;
										}
										else
										{
											$UMT_VOL 	= $UM_VOL;
											$UMT_VAL 	= $UM_VAL;
											$RAPUT_VOL 	= $UM_VOL;
											$RAPUT_VAL 	= $UM_VAL;
										}

										$REMA_VOL 		= 0;
										$REMA_VAL 		= 0;

										$REMB_VOL 		= $RAPT_VOL - $RAPUT_VOL;
										$REMB_VAL 		= $RAPT_VAL - $RAPUT_VAL;

										/*$POT_VOL 		= 0;
										$POT_VAL 		= 0;*/
									}

									$RAPR_VOL 	= $RAPT_VOL - $ITMT_VOL;
									$RAPR_VAL 	= $RAPT_VAL - $ITMT_VAL;

									$s_isLS = "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
									$r_isLS = $this->db->count_all($s_isLS);

									$alrtStyl1 		= "";
									$alrtStyl2 		= "";
									if(($REMA_VOL < 0 OR $REMA_VAL < 0) && $r_isLS == 0)
									{
										$alrtStyl1 	= "background-color: gray;";
										$alrtStyl2 	= "background-color: gray;";
									}
									elseif($REMA_VAL < 0 && $r_isLS > 0)
									{
										$alrtStyl1 	= "";
										$alrtStyl2 	= "background-color: gray;";
									}

									$alrtStyl3 		= "background-color: rgb(233, 233, 233);";
									$alrtStyl4 		= "background-color: rgb(233, 233, 233);";
									if(($REMB_VOL < 0 OR $REMB_VAL < 0) && $r_isLS == 0)
									{
										$alrtStyl3 	= "background-color: gray;";
										$alrtStyl4 	= "background-color: gray;";
									}
									elseif($REMA_VAL < 0 && $r_isLS > 0)
									{
										$alrtStyl3 	= "";
										$alrtStyl4 	= "background-color: gray;";
									}

									$alrtStyl5 		= "";
									$alrtStyl6 		= "";
									if(($RAPR_VOL < 0 OR $RAPR_VAL < 0) && $r_isLS == 0)
									{
										$alrtStyl5 	= "background-color: gray;";
										$alrtStyl6 	= "background-color: gray;";
									}
									elseif($RAPR_VAL < 0 && $r_isLS > 0)
									{
										$alrtStyl5 	= "";
										$alrtStyl6 	= "background-color: gray;";
									}
									?>
		                                <tr>
		                                    <td style="text-align:center"><?php echo $no; ?></td>
		                                    <td style="text-align:left;" nowrap><?php echo $DOC_CODE; ?><br><span style="font-size: 8px; font-style: italic;"><?=$REF_NUM?></span></td>
		                                    <td style="text-align:center" nowrap><?php echo $DOC_DATE; ?></td>
		                                    <td style="text-align:left"><?php echo "$JOBCODEID : $REF_ID<br>$DOC_DESC ($ITM_UNIT)"; ?></td>
		                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($RAPVW_VOL,2); ?></td>
		                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($RAPVW_VAL,2); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($PR_VOL,2); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($PR_VAL,2); ?></td>
		                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($PO_VOL,2); ?></td>
		                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($PO_VAL,2); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($IR_VOL,2); ?></td>
		                                    <td style="text-align:right;"><?php echo number_format($IR_VAL,2); ?></td>
		                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($UM_VOL,2); ?></td>
		                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($UM_VAL,2); ?></td>
		                                    <td style="text-align:right; <?=$alrtStyl1?>"><?php echo number_format($REMA_VOL,2); ?> <?php //echo $REMA_VOLV;?></td>
		                                    <td style="text-align:right; <?=$alrtStyl2?>"><?php echo number_format($REMA_VAL,2); ?> <?php //echo $REMA_VALV;?></td>
		                                    <td style="text-align:right; <?=$alrtStyl3?>"><?php echo number_format($REMB_VOL,2); ?></td>
		                                    <td style="text-align:right; <?=$alrtStyl4?>"><?php echo number_format($REMB_VAL,2); ?></td>
		                                    <td style="text-align:right; <?=$alrtStyl5?>"><?php echo number_format($RAPR_VOL,2); ?> <?php //echo $REMA_VOLV;?></td>
		                                    <td style="text-align:right; <?=$alrtStyl6?>"><?php echo number_format($RAPR_VAL,2); ?> <?php //echo $REMA_VALV;?></td>
		                                </tr>
		                    		<?php
									$JOBCODEID2 	= $JOBCODEID;
									$DOC_CATEG2 	= $DOC_CATEG;
								endforeach;
							?>
		                </table>
		           	  </td>
		            </tr>
		        </table>
			</section>
		</div>
	</body>
</html>