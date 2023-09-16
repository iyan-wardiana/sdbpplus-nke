<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 13 Maret 2023
	* File Name		= v_itembudet_detil_hist_itm.php
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
$ITM_UNIT 		= "-";
$sqlITM			= "SELECT ITM_NAME, ITM_UNIT FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE'";
$resITM			= $this->db->query($sqlITM)->result();
foreach($resITM as $rowITM) :
	$ITM_NAME 	= $rowITM->ITM_NAME;
	$ITM_UNIT 	= $rowITM->ITM_UNIT;
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
		                            <td style="text-align:left; font-weight:bold"><?php echo $PRJCODE." - ". strtoupper($PRJNAME); ?></td>
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
		                          	<td><?php echo "$ITM_NAME ($ITM_UNIT)";?></td>
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
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>SPP</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>OP / SPK</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>LPM</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>VCASH / VLK / PPD / OPN</td>
		                        <td width="10%" colspan="2" style="text-align:center" nowrap>TOTAL TRANSAKSI</td>
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
		                    </tr>
		                    <?php
		                    	if($DOC_CATEG == 'PR')
		                    		$DOCCATEG 	= "'PR','WO','VCASH','CPRJ','PPD'";
		                    	elseif($DOC_CATEG == 'PO')
		                    		$DOCCATEG 	= "'PO','WO','VCASH','CPRJ','PPD'";
		                    	elseif($DOC_CATEG == 'IR')
		                    	{
		                    		$DOCCATEG 	= "'IR','WO','VCASH','CPRJ','PPD'";
		                    		if($ISLS > 1)
		                    			$DOCCATEG 	= "'IR','OPN','VCASH','CPRJ','PPD'";
		                    	}
		                    	elseif($DOC_CATEG == 'UM')
		                    		$DOCCATEG 	= "'UM','OPN','VCASH','CPRJ','PPD'";

		                    	$RAPT_VOL 	= 0;
		                    	$RAPT_VAL 	= 0;
		                    	$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		                    	$s_ITMT		= "SELECT SUM(ITM_VOLM) AS RAPT_VOL, SUM(ITM_BUDG) AS RAPT_VAL
		                    					FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$rw_ITMT	= $this->db->query($s_ITMT)->result();
								foreach($rw_ITMT as $rw_ITMT) :
									$RAPT_VOL = $rw_ITMT->RAPT_VOL;
									$RAPT_VAL = $rw_ITMT->RAPT_VAL;
								endforeach;

								$no			= 0;
								$no2 		= 0;
								$totAm		= 0;
								$REMB_VOL 	= 0;
								$REMB_VAL 	= 0;
								$AMD_VOL 	= 0;
								$AMD_VAL 	= 0;
								$REMC_VOL 	= $RAPT_VOL;		// TOTOL VOL RAP ITEM
								$REMC_VAL 	= $RAPT_VAL;		// TOTAL VAL RAP ITEM
								$RAPUT_VOL 	= 0;
								$RAPUT_VAL 	= 0;

								$PRT_VOL 	= 0;
								$PRT_VAL 	= 0;
								$POT_VOL 	= 0;
								$POT_VAL 	= 0;
								$IRT_VOL 	= 0;
								$IRT_VAL 	= 0;
								$UMT_VOL 	= 0;
								$UMT_VAL 	= 0;

								$REMA_VOL 	= 0;
								$REMA_VAL 	= 0;
								$REMB_VOL 	= 0;
								$REMB_VAL 	= 0;
								$REMC_VOL 	= $RAPT_VOL;
								$REMC_VAL 	= $RAPT_VAL;
								$TOT_PRVOL 	= 0;
								$TOT_PRVAL 	= 0;
								$TOT_POVOL 	= 0;
								$TOT_POVAL 	= 0;
								$TOT_IRVOL 	= 0;
								$TOT_IRVAL 	= 0;
								$TOT_UMVOL 	= 0;
								$TOT_UMVAL 	= 0;
								$TOT_VOL 	= 0;
								$TOT_VAL 	= 0;

								$JOBCODEID2 = "";
								$ITMT_VOL 	= 0;
								$ITMT_VAL 	= 0;
								$colStyl 	= "background-color: rgb(233, 233, 233);";
								$colStyl2 	= "";
								$r_JLC 		= 0;
								$sqlJD		= "SELECT *, IF(DOC_STAT=3 OR DOC_STAT=6, 1, 0) AS STAT_PLUS FROM tbl_item_logbook_$PRJCODEVW
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND DOC_CATEG IN ($DOCCATEG) AND DOC_DATE BETWEEN '$Start_Date' AND '$End_Date'
												ORDER BY DOC_DATE, DOC_CODE, JOBCODEID, DOC_ID";
								$resJD		= $this->db->query($sqlJD)->result();
								foreach($resJD as $rowJD) :
									$no2 			= $no2+1;
									$JOBCODEID 		= $rowJD->JOBCODEID;
									$DOC_ID 		= $rowJD->DOC_ID;
									$DOC_NUM 		= $rowJD->DOC_NUM;
									$DOC_CODE 		= $rowJD->DOC_CODE;
									$DOC_DATE 		= $rowJD->DOC_DATE;
									$DOC_CATEG 		= $rowJD->DOC_CATEG;
									$REF_ID 		= $rowJD->REF_ID;
									$ITM_CODE 		= $rowJD->ITM_CODE;
									$ITM_GROUP 		= $rowJD->ITM_GROUP;
									$ITM_UNIT 		= $rowJD->ITM_UNIT;
									$DOC_DESC 		= $rowJD->DOC_DESC;
									$RAP_VOL1 		= $rowJD->RAP_VOL;
									$RAP_VAL1 		= $rowJD->RAP_VAL;
									$REMB_VOLA 		= $rowJD->RAP_VOL;
									$REMB_VALA 		= $rowJD->RAP_VAL;
									$DOC_VOL 		= $rowJD->DOC_VOL;
									$DOC_VAL 		= $rowJD->DOC_VAL;
									$DOC_CVOL 		= $rowJD->DOC_CVOL;
									$DOC_CVAL 		= $rowJD->DOC_CVAL;
									$DOC_STAT 		= $rowJD->DOC_STAT;
									$STAT_PLUS 		= $rowJD->STAT_PLUS;

									$RAPVW_VOL 		= 0;
									$RAPVW_VAL 		= 0;
									if($JOBCODEID != $JOBCODEID2)
									{
										// VARIABEL YANG DIRESET SETIAP GANTI KODE PEKERJAAN
										$REMA_VOL 	= 0;
										$REMA_VAL 	= 0;
										$REMB_VOL 	= $REMB_VOLA;
										$REMB_VAL 	= $REMB_VALA;
										$RAPVW_VOL 	= $REMB_VOL;
										$RAPVW_VAL 	= $REMB_VAL;

										$AMDT_VOL 	= 0;
										$AMDT_VAL 	= 0;
										$PRT_VOL 	= 0;
										$PRT_VAL 	= 0;
										$POT_VOL 	= 0;
										$POT_VAL 	= 0;
										$IRT_VOL 	= 0;
										$IRT_VAL 	= 0;
										$UMT_VOL 	= 0;
										$UMT_VAL 	= 0;
										$WOT_VOL 	= 0;
										$WOT_VAL 	= 0;
										$OPNT_VOL 	= 0;
										$OPNT_VAL 	= 0;
										$VCT_VOL 	= 0;
										$VCT_VAL 	= 0;
										$VLKT_VOL 	= 0;
										$VLKT_VAL 	= 0;
										$PPDT_VOL 	= 0;
										$PPDT_VAL 	= 0;
			                    		if($no2 == 1)
			                    		{
										?>
				                            <tr>
				                                <td style="text-align:center" colspan="14">&nbsp;</td>
				                            </tr>
			                    		<?php
			                    		}

										$s_JLC		= "tbl_item_logbook_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND DOC_CATEG IN ($DOCCATEG)";
										$r_JLC		= $this->db->count_all($s_JLC);
									}

									$PR_VOL 		= 0;
									$PR_VAL 		= 0;
									$PO_VOL 		= 0;
									$PO_VAL 		= 0;
									$PWO_VOL 		= 0;
									$PWO_VAL 		= 0;
									$IR_VOL 		= 0;
									$IR_VAL 		= 0;
									$UM_VOL 		= 0;
									$UM_VAL 		= 0;
									$REF_NUM 		= "";
									$PR_VOLV 		= "";
									if($DOC_CATEG == 'AMD')
									{
										$AMD_VOL 	= $DOC_VOL-$DOC_CVOL;
										$AMD_VAL 	= $DOC_VAL-$DOC_CVAL;
										$AMDT_VOL 	= $AMDT_VOL+$AMD_VOL;
										$AMDT_VAL 	= $AMDT_VAL+$AMD_VAL;

										$RAPVW_VOL 	= $AMD_VOL;
										$RAPVW_VAL 	= $AMD_VAL;

										$REMA_VOL 	= round($REMA_VOL,2);
										$REMA_VAL 	= round($REMA_VAL,2);

										$REMB_VOL 	= round($REMB_VOL,2);
										$REMB_VAL 	= round($REMB_VAL,2);

										$REMC_VOL 	= round($REMC_VOL,2);
										$REMC_VAL 	= round($REMC_VAL,2);
										if($STAT_PLUS == 1)
										{
											$REMB_VOL 	= round($REMB_VOL + $AMD_VOL,2);
											$REMB_VAL 	= round($REMB_VAL + $AMD_VAL,2);

											$REMC_VOL 	= round($REMC_VOL + $AMD_VOL,2);
											$REMC_VAL 	= round($REMC_VAL + $AMD_VAL,2);
										}

										// START : VIEW
											$REMAV_VOL 	= round(0,2);
											$REMAV_VAL 	= round(0,2);

											$REMBV_VOL 	= round($REMB_VOL,2);
											$REMBV_VAL 	= round($REMB_VAL,2);

											$REMCV_VOL 	= round($REMC_VOL,2);
											$REMCV_VAL 	= round($REMC_VAL,2);
										// END : VIEW
									}
									elseif($DOC_CATEG == 'AMDSUB')
									{
										$AMD_VOL 	= $DOC_VOL-$DOC_CVOL;
										$AMD_VAL 	= $DOC_VAL-$DOC_CVAL;
										$AMDT_VOL 	= $AMDT_VOL-$AMD_VOL;
										$AMDT_VAL 	= $AMDT_VAL-$AMD_VAL;
										$REMB_VOL 	= $REMB_VOL-$AMD_VOL;
										$REMB_VAL 	= $REMB_VAL-$AMD_VAL;
										$REMC_VOL 	= $REMC_VOL-$AMD_VOL;
										$REMC_VAL 	= $REMC_VAL-$AMD_VAL;

										$RAPVW_VOL 	= $AMD_VOL;
										$RAPVW_VAL 	= $AMD_VAL;

										$REMA_VOL 	= round($REMA_VOL,2);
										$REMA_VAL 	= round($REMA_VAL,2);

										$REMB_VOL 	= round($REMB_VOL,2);
										$REMB_VAL 	= round($REMB_VAL,2);

										$REMC_VOL 	= round($REMC_VOL,2);
										$REMC_VAL 	= round($REMC_VAL,2);
										if($STAT_PLUS == 1)
										{
											$REMB_VOL 	= round($REMB_VOL - $AMD_VOL,2);
											$REMB_VAL 	= round($REMB_VAL - $AMD_VAL,2);

											$REMC_VOL 	= round($REMC_VOL - $AMD_VOL,2);
											$REMC_VAL 	= round($REMC_VAL - $AMD_VAL,2);
										}

										// START : VIEW
											$REMAV_VOL 	= round(0,2);
											$REMAV_VAL 	= round(0,2);

											$REMBV_VOL 	= round($REMB_VOL,2);
											$REMBV_VAL 	= round($REMB_VAL,2);

											$REMCV_VOL 	= round($REMC_VOL,2);
											$REMCV_VAL 	= round($REMC_VAL,2);
										// END : VIEW
									}
									else if($DOC_CATEG == 'PR')
									{
										$PR_VOL 	= $DOC_VOL-$DOC_CVOL;
										$PR_VOLV 	= "$DOC_VOL-$DOC_CVOL";
										$PR_VAL 	= $PR_VAL;
										$PRT_VOL 	= $PRT_VOL+$PR_VOL;
										$PRT_VAL 	= $PRT_VAL;

										$REMA_VOL 	= round($REMA_VOL + $PR_VOL,2);
										$REMA_VAL 	= round($REMA_VAL + $PR_VAL,2);

										$REMB_VOL 	= round($REMB_VOL - $PR_VOL,2);
										$REMB_VAL 	= round($REMB_VAL,2);

										$REMC_VOL 	= round($REMC_VOL - $PR_VOL,2);
										$REMC_VAL 	= round($REMC_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round($REMB_VOL,2);
											$REMBV_VAL 	= round($REMB_VAL,2);

											$REMCV_VOL 	= round($REMC_VOL,2);
											$REMCV_VAL 	= round($REMC_VAL,2);
										// END : VIEW

										$TOT_PRVOL 	= $TOT_PRVOL 	+ $PR_VOL;
										$TOT_PRVAL 	= $TOT_PRVAL 	+ 0;
										$TOT_VOL 	= $TOT_VOL 		+ $PR_VOL;
										$TOT_VAL 	= $TOT_VAL 		+ 0;
									}
									elseif($DOC_CATEG == 'PO')
									{
										$PO_VOL 	= $DOC_VOL-$DOC_CVOL;
										$PO_VAL 	= $DOC_VAL-$DOC_CVAL;
										$POT_VOL 	= $POT_VOL + $PO_VOL;
										$POT_VAL 	= $POT_VAL + $PO_VAL;

										$PWO_VOL 	= $PO_VOL;
										$PWO_VAL 	= $PO_VAL;

										$REMA_VOL 	= round($REMA_VOL - $PO_VOL,2);
										$REMA_VAL 	= round($REMA_VAL - $PO_VAL,2);

										$REMB_VOL 	= round($REMB_VOL,2);
										$REMB_VAL 	= round($REMB_VAL - $PO_VAL,2);

										$REMC_VOL 	= round($REMC_VOL,2);
										$REMC_VAL 	= round($REMC_VAL - $PO_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round(0,2);
											$REMBV_VAL 	= round(0,2);

											$REMCV_VOL 	= round(0,2);
											$REMCV_VAL 	= round(0,2);
										// END : VIEW

										$TOT_POVOL 	= $TOT_POVOL 	+ $PO_VOL;
										$TOT_POVAL 	= $TOT_POVAL 	+ $PO_VAL;
										$TOT_VOL 	= $TOT_VOL 		+ $PO_VOL;
										$TOT_VAL 	= $TOT_VAL 		+ $PO_VAL;
									}
									elseif($DOC_CATEG == 'IR')
									{
										$IR_VOL 	= $DOC_VOL-$DOC_CVOL;
										$IR_VAL 	= $DOC_VAL-$DOC_CVAL;
										$IRT_VOL 	= $IRT_VOL + $IR_VOL;
										$IRT_VAL 	= $IRT_VAL + $IR_VAL;

										$POT_VOL 	= $POT_VOL - $IR_VOL;
										$POT_VAL 	= $POT_VAL - $IR_VAL;

										$REMA_VOL 	= round($POT_VOL,2);
										$REMA_VAL 	= round($POT_VAL,2);

										$REMB_VOL 	= round($REMB_VOL,2);
										$REMB_VAL 	= round($REMB_VAL,2);

										$REMC_VOL 	= round($REMC_VOL,2);
										$REMC_VAL 	= round($REMC_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round(0,2);
											$REMBV_VAL 	= round(0,2);

											$REMCV_VOL 	= round(0,2);
											$REMCV_VAL 	= round(0,2);
										// END : VIEW

										$INV_REF 		= $rowJD->INV_REF;
										$BP_REF 		= $rowJD->BP_REF;
										if(($INV_REF != "" || $BP_REF != "") AND $showINV == 1)
											$REF_NUM 	= "$INV_REF. $BP_REF";


										$TOT_IRVOL 	= $TOT_IRVOL;
										$TOT_IRVAL 	= $TOT_IRVAL;
										$TOT_VOL 	= $TOT_VOL;
										$TOT_VAL 	= $TOT_VAL;
										if($STAT_PLUS == 1)
										{
											$TOT_IRVOL 	= $TOT_IRVOL 	+ $IR_VOL;
											$TOT_IRVAL 	= $TOT_IRVAL 	+ $IR_VAL;
											$TOT_VOL 	= $TOT_VOL 		+ $IR_VOL;
											$TOT_VAL 	= $TOT_VAL 		+ $IR_VAL;
										}
									}
									elseif($DOC_CATEG == 'UM')
									{
										$UM_VOL 	= $DOC_VOL - $DOC_CVOL;
										$UM_VAL 	= $DOC_VAL - $DOC_CVAL;
										$UMT_VOL 	= $UMT_VOL + $UM_VOL;
										$UMT_VAL 	= $UMT_VAL + $UM_VAL;

										$POT_VOL 	= $POT_VOL - $UM_VOL;
										$POT_VAL 	= $POT_VAL - $UM_VAL;

										$REMA_VOL 	= round($POT_VOL,2);
										$REMA_VAL 	= round($POT_VAL,2);

										$REMB_VOL 	= round($REMB_VOL,2);
										$REMB_VAL 	= round($REMB_VAL,2);

										$REMC_VOL 	= round($REMC_VOL,2);
										$REMC_VAL 	= round($REMC_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round(0,2);
											$REMBV_VAL 	= round(0,2);

											$REMCV_VOL 	= round(0,2);
											$REMCV_VAL 	= round(0,2);
										// END : VIEW

										$INV_REF 		= $rowJD->INV_REF;
										$BP_REF 		= $rowJD->BP_REF;
										if(($INV_REF != "" || $BP_REF != "") AND $showINV == 1)
											$REF_NUM 	= "$INV_REF. $BP_REF";

										$TOT_UMVOL 	= $TOT_UMVOL 	+ $UM_VOL;
										$TOT_UMVAL 	= $TOT_UMVAL 	+ $UM_VAL;
										$TOT_VOL 	= $TOT_VOL 		+ $UM_VOL;
										$TOT_VAL 	= $TOT_VAL 		+ $UM_VAL;
									}
									elseif($DOC_CATEG == 'WO')
									{
										$WO_VOL 	= $DOC_VOL-$DOC_CVOL;
										$WO_VAL 	= $DOC_VAL-$DOC_CVAL;
										$POT_VOL 	= $POT_VOL + $WO_VOL;
										$POT_VAL 	= $POT_VAL + $WO_VAL;

										$REMA_VOL 	= round($POT_VOL,2);
										$REMA_VAL 	= round($POT_VAL,2);

										$REMB_VOL 	= round($REMB_VOL - $WO_VOL,2);
										$REMB_VAL 	= round($REMB_VAL - $WO_VAL,2);

										$REMC_VOL 	= round($REMC_VOL - $WO_VOL,2);
										$REMC_VAL 	= round($REMC_VAL - $WO_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round($REMB_VOL,2);
											$REMBV_VAL 	= round($REMB_VAL,2);

											$REMCV_VOL 	= round($REMC_VOL,2);
											$REMCV_VAL 	= round($REMC_VAL,2);
										// END : VIEW

										$PWO_VOL 	= $WO_VOL;
										$PWO_VAL 	= $WO_VAL;

										$TOT_POVOL 	= $TOT_POVOL;
										$TOT_POVAL 	= $TOT_POVAL;
										$TOT_VOL 	= $TOT_VOL;
										$TOT_VAL 	= $TOT_VAL;
										if($STAT_PLUS == 1)
										{
											$TOT_POVOL 	= $TOT_POVOL 	+ $WO_VOL;
											$TOT_POVAL 	= $TOT_POVAL 	+ $WO_VAL;
											$TOT_VOL 	= $TOT_VOL 		+ $WO_VOL;
											$TOT_VAL 	= $TOT_VAL 		+ $WO_VAL;
										}
									}
									elseif($DOC_CATEG == 'OPN')
									{
										$UM_VOL 	= $DOC_VOL - $DOC_CVOL;
										$UM_VAL 	= $DOC_VAL - $DOC_CVAL;
										$UMT_VOL 	= $UMT_VOL + $UM_VOL;
										$UMT_VAL 	= $UMT_VAL + $UM_VAL;

										$POT_VOL 	= $POT_VOL - $UM_VOL;
										$POT_VAL 	= $POT_VAL - $UM_VAL;

										$REMA_VOL 	= round($POT_VOL,2);
										$REMA_VAL 	= round($POT_VAL,2);

										$REMB_VOL 	= round($REMB_VOL,2);
										$REMB_VAL 	= round($REMB_VAL,2);

										$REMC_VOL 	= round($REMC_VOL,2);
										$REMC_VAL 	= round($REMC_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round(0,2);
											$REMBV_VAL 	= round(0,2);

											$REMCV_VOL 	= round(0,2);
											$REMCV_VAL 	= round(0,2);
										// END : VIEW

										$INV_REF 		= $rowJD->INV_REF;
										$BP_REF 		= $rowJD->BP_REF;
										if(($INV_REF != "" || $BP_REF != "") AND $showINV == 1)
											$REF_NUM 	= "$INV_REF. $BP_REF";

										$TOT_UMVOL 	= $TOT_UMVOL 	+ $UM_VOL;
										$TOT_UMVAL 	= $TOT_UMVAL 	+ $UM_VAL;
										$TOT_VOL 	= $TOT_VOL 		+ $UM_VOL;
										$TOT_VAL 	= $TOT_VAL 		+ $UM_VAL;
									}
									elseif($DOC_CATEG == 'VCASH' || $DOC_CATEG == 'CPRJ' || $DOC_CATEG == 'VLK' || $DOC_CATEG == 'PPD')
									{
										$UM_VOL 	= $DOC_VOL-$DOC_CVOL;
										$UM_VAL 	= $DOC_VAL-$DOC_CVAL;
										$UMT_VOL 	= $UMT_VOL + $UM_VOL;
										$UMT_VAL 	= $UMT_VAL + $UM_VAL;

										$REMA_VOL 	= round($REMA_VOL,2);
										$REMA_VAL 	= round($REMA_VAL,2);

										$REMB_VOL 	= round($REMB_VOL - $UM_VOL,2);
										$REMB_VAL 	= round($REMB_VAL - $UM_VAL,2);

										$REMC_VOL 	= round($REMC_VOL - $UM_VOL,2);
										$REMC_VAL 	= round($REMC_VAL - $UM_VAL,2);

										// START : VIEW
											$REMAV_VOL 	= round($REMA_VOL,2);
											$REMAV_VAL 	= round($REMA_VAL,2);

											$REMBV_VOL 	= round($REMB_VOL,2);
											$REMBV_VAL 	= round($REMB_VAL,2);

											$REMCV_VOL 	= round($REMC_VOL,2);
											$REMCV_VAL 	= round($REMC_VAL,2);
										// END : VIEW

										$TOT_UMVOL 	= $TOT_UMVOL 	+ $UM_VOL;
										$TOT_UMVAL 	= $TOT_UMVAL 	+ $UM_VAL;
										$TOT_VOL 	= $TOT_VOL 		+ $UM_VOL;
										$TOT_VAL 	= $TOT_VAL 		+ $UM_VAL;
									}

									$alrtStyl1 		= "";
									$alrtStyl2 		= "";
									$alrtStyl3 		= "";
									$alrtStyl4 		= "";
									$alrtStyl5 		= "";
									$alrtStyl6 		= "";
									if($PR_VOL > 0 || $PWO_VOL > 0 || $IR_VOL > 0 || $UM_VOL > 0)
									{
										$no		= $no+1;
										?>
			                                <tr>
			                                    <td style="text-align:center"><?php echo $no; ?></td>
			                                    <td style="text-align:left;" nowrap><?php echo $DOC_CODE; ?><br><span style="font-size: 8px; font-style: italic;"><?=$REF_NUM?></span></td>
			                                    <td style="text-align:center" nowrap><?php echo $DOC_DATE; ?></td>
			                                    <td style="text-align:left"><?php echo "$JOBCODEID ($REF_ID) : $DOC_DESC"; ?></td>
			                                    <td style="text-align:right"><?php echo number_format($PR_VOL,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($PR_VAL,2); ?></td>
			                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($PWO_VOL,2); ?></td>
			                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($PWO_VAL,2); ?></td>
			                                    <td style="text-align:right; <?php if($STAT_PLUS == 0) { ?> background-color: lightyellow; <?php } ?>"><?php echo number_format($IR_VOL,2); ?></td>
			                                    <td style="text-align:right;"><?php echo number_format($IR_VAL,2); ?></td>
			                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($UM_VOL,2); ?></td>
			                                    <td style="text-align:right; background-color: rgb(233, 233, 233);"><?php echo number_format($UM_VAL,2); ?></td>
			                                    <td style="text-align:right; <?=$alrtStyl1?>"><?php echo number_format($TOT_VOL,2); ?> <?php //echo $REMA_VOLV;?></td>
			                                    <td style="text-align:right; <?=$alrtStyl2?>"><?php echo number_format($TOT_VAL,2); ?> <?php //echo $REMA_VALV;?></td>
			                                </tr>
			                    		<?php
			                    	}
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