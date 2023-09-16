<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 23 Maret 2023
	* File Name		= v_ir_hist_itm.php
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
$PO_VOL 		= $PO_VOLM-$PO_CVOL;
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
		                         	<td nowrap>KODE PO</td>
		                          	<td>:</td>
		                          	<td style="text-align:left; font-weight:bold"><?=$PO_CODE?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>ITEM / KOMPONEN</td>
		                          	<td>:</td>
		                          	<td><?php echo "$ITM_CODE : $ITM_NAME ($ITM_UNIT)";?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>PEKERJAAN</td>
		                          	<td>:</td>
		                          	<td><?php echo "$JOBPARENT : $JOBPARDESC";?></td>
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
		                    	<td width="5%" style="text-align:center">&nbsp;No.&nbsp;</td>
		                      	<td width="15%" style="text-align:center">NO. LPM</td>
		                      	<td width="10%" style="text-align:center">TANGGAL</td>
		                        <td width="15%" style="text-align:center">VOL. LPM</td>
		                        <td width="15%" style="text-align:center" nowrap>SISA PO</td>
		                        <td width="10%" style="text-align:center" nowrap>STATUS</td>
		                        <td width="30%" style="text-align:center" nowrap>DESKRIPSI</td>
		                    </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                                <td style="text-align:right"><?php echo number_format($PO_VOL,2); ?></td>
                                <td colspan="2">&nbsp;</td>
                            </tr>
		                    <?php
								$JOBCODEID2 = "";
								$ITMT_VOL 	= 0;
								$ITMT_VAL 	= 0;
								$colStyl 	= "background-color: rgb(233, 233, 233);";
								$colStyl2 	= "";
								$r_JLC 		= 0;
								$no 		= 0;
								$TPO_VOL 	= 0;
								$sqlJD		= "SELECT IR_CODE, IR_DATE, IR_STAT, JOBCODEID, ITM_UNIT, ITM_UNIT2, ITM_QTY, NOTES
												FROM tbl_ir_detail WHERE PO_NUM = '$PO_NUM' AND POD_ID = $PO_ID
												ORDER BY IR_CODE, IR_DATE";
								$resJD		= $this->db->query($sqlJD)->result();
								foreach($resJD as $rowJD) :
									$no			= $no+1;
									$IR_CODE 	= $rowJD->IR_CODE;
									$IR_DATE 	= $rowJD->IR_DATE;
									$IR_STAT 	= $rowJD->IR_STAT;
									$JOBCODEID 	= $rowJD->JOBCODEID;
									$ITM_UNIT2 	= $rowJD->ITM_UNIT2;
									$ITM_QTY 	= $rowJD->ITM_QTY;
									$NOTES 		= $rowJD->NOTES;
									$IR_STATD 	= "-";
									if($IR_STAT == 1) {
										$IR_STATD 	= "New";
										$TPO_VOL 	= $TPO_VOL+$ITM_QTY;
									}
									elseif($IR_STAT == 2) {
										$IR_STATD = "Confirmed";
										$TPO_VOL 	= $TPO_VOL+$ITM_QTY;
									}
									elseif($IR_STAT == 3) {
										$IR_STATD = "Approved";
										$TPO_VOL 	= $TPO_VOL+$ITM_QTY;
									}
									elseif($IR_STAT == 4) {
										$IR_STATD = "Revised";
										$TPO_VOL 	= $TPO_VOL+$ITM_QTY;
									}
									elseif($IR_STAT == 5) {
										$IR_STATD = "Rejected";
										$TPO_VOL 	= $TPO_VOL+0;
									}
									elseif($IR_STAT == 6) {
										$IR_STATD = "Closed";
										$TPO_VOL 	= $TPO_VOL+$ITM_QTY;
									}
									elseif($IR_STAT == 7) {
										$IR_STATD = "Awaiting";
										$TPO_VOL 	= $TPO_VOL+$ITM_QTY;
									}
									elseif($IR_STAT == 9) {
										$IR_STATD = "Void";
										$TPO_VOL 	= $TPO_VOL+0;
									}
									$RPO_VOL 		= $PO_VOL-$TPO_VOL;
									?>
		                                <tr>
		                                    <td style="text-align:center"><?php echo $no; ?></td>
		                                    <td style="text-align:left;" nowrap><?php echo $IR_CODE; ?></td>
		                                    <td style="text-align:center" nowrap><?php echo $IR_DATE; ?></td>
		                                    <td style="text-align:right"><?php echo number_format($ITM_QTY,2); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($RPO_VOL,2); ?></td>
		                                    <td style="text-align:center;" nowrap><?php echo $IR_STATD; ?></td>
		                                    <td style="text-align:left;" nowrap><?php echo $NOTES; ?></td>
		                                </tr>
		                    		<?php
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