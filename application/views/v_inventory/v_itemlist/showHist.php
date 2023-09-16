<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 15 Agustus2023
	* File Name		= showHist.php
	* Location		= -
*/
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
$resPRJ	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;

$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$ITM_NAME 		= "";
$sqlITM			= "SELECT ITM_NAME FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE'";
$resITM			= $this->db->query($sqlITM)->result();
foreach($resITM as $rowITM) :
	$ITM_NAME 	= $rowITM->ITM_NAME;
endforeach;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title><?php echo $h1_title; ?></title>
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
		                            <td width="23%" nowrap>PROYEK</td>
		                          	<td width="1%">:</td>
		                            <td style="text-align:left; font-weight:bold"><?php echo strtoupper($PRJNAME); ?></td>
		                      	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>KODE ITEM</td>
		                          	<td>:</td>
		                          	<td style="text-align:left;"><?php echo $ITM_CODE;?></td>
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
						<td class="style2" style="text-align:left; font-size:12px">
			              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
			                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:12px; height: 20px;">
			                    	<td width="5%" style="text-align:center;" rowspan="2">No.</td>
			                      	<td width="10%" style="text-align:center;" rowspan="2">KODE DOK.</td>
			                      	<td width="10%" style="text-align:center;" rowspan="2">TANGGAL</td>
			                        <td width="45%" style="text-align:center;" rowspan="2">DESKRIPSI</td>
			                        <td width="10%" style="text-align:center;" colspan="2">LPM/SPK</td>
			                        <td width="10%" style="text-align:center;" colspan="2">UM/OPN</td>
			                        <td width="10%" style="text-align:center" colspan="3">SISA</td>
			                    </tr>
			                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:12px; height: 20px;">
			                        <td style="text-align:center;">VOL</td>
			                        <td style="text-align:center">HARGA</td>
			                        <td style="text-align:center;">VOL</td>
			                        <td style="text-align:center">HARGA</td>
			                        <td style="text-align:center;">VOL</td>
			                        <td style="text-align:center">VAL</td>
			                        <td style="text-align:center">HARGA</td>
			                    </tr>
			                    <?php
									$no		= 0;
									$totVol	= 0;
									$totVal	= 0;
									$s_HIST	= "SELECT DOC_CODE, DOC_DATE, DOC_CATEG, JOBCODEID, ITM_CODE, ITM_UNIT, DOC_VOL, DOC_VAL, DOC_DESC
												FROM tbl_item_logbook_$PRJCODEVW WHERE DOC_CATEG IN ('IR','UM','WO', 'OPN') AND ITM_CODE = '$ITM_CODE'
												ORDER BY DOC_DATE, DOC_CATEG, DOC_CODE ASC";
									$r_HIST	= $this->db->query($s_HIST)->result();
									foreach($r_HIST as $rw_HIST) :
										$no				= $no+1;
										$DOC_CODE 		= $rw_HIST->DOC_CODE;
										$DOC_DATE 		= $rw_HIST->DOC_DATE;
										$DOC_CATEG 		= $rw_HIST->DOC_CATEG;
										$JOBCODEID 		= $rw_HIST->JOBCODEID;
										$ITM_CODE 		= $rw_HIST->ITM_CODE;
										$ITM_UNIT 		= $rw_HIST->ITM_UNIT;
										$DOC_VOL 		= $rw_HIST->DOC_VOL;
										$DOC_VAL 		= $rw_HIST->DOC_VAL;
										$DOC_DESC 		= $rw_HIST->DOC_DESC;
										if($DOC_CATEG == 'IR' || $DOC_CATEG == 'WO')
										{
											$volIN 		= $DOC_VOL;
											$volOUT		= 0;

											$VolP 		= $volIN;
											if($VolP == 0 || $VolP == '')
												$VolP 	= 1;

											$AVGItmIN 	= $DOC_VAL / $VolP;
											$AVGItmOUT 	= 0;

											$totVol 	= $totVol+$DOC_VOL;
											$totVal 	= $totVal+$DOC_VAL;
										}
										else
										{
											$volIN 		= 0;
											$volOUT		= $DOC_VOL;

											$VolP 		= $volOUT;
											if($VolP == 0 || $VolP == '')
												$VolP 	= 1;

											$AVGItmIN 	= 0;
											$AVGItmOUT 	= $DOC_VAL / $VolP;

											$totVol 	= $totVol-$DOC_VOL;
											$totVal 	= $totVal-$DOC_VAL;
										}

										$totVolP 		= $totVol;
										if($totVolP == 0 || $totVolP == '')
											$totVolP 	= 1;

										$AVGPRC 		= $totVal / $totVolP;
										?>
			                                <tr>
			                                    <td style="text-align:center"><?php echo $no; ?></td>
			                                    <td style="text-align:center" nowrap><?php echo $DOC_CODE; ?></td>
			                                    <td style="text-align:center" nowrap><?php echo $DOC_DATE; ?></td>
			                                    <td style="text-align:left"><?php echo $DOC_DESC; ?></td>
			                                    <td style="text-align:right"><?php echo number_format($volIN,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($AVGItmIN,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($volOUT,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($AVGItmOUT,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($totVol,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($totVal,2); ?></td>
			                                    <td style="text-align:right"><?php echo number_format($AVGPRC,2); ?></td>
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