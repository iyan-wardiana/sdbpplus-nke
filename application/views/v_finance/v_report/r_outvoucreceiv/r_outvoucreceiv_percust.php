<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Agustus 2020
 * File Name	= r_outvoucreceiv_percust.php
 * Location		= -*/

setlocale(LC_ALL, 'id-ID', 'id_ID');

$this->load->view('template/head');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');
$LangID 	= $this->session->userdata['LangID'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt Arial, Helvetica, sans-serif;
        }
        * {
          box-sizing: border-box;
          -moz-box-sizing: border-box;
        }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding-left: 1cm;
            padding-right: 1cm;
            padding-top: 1.5cm;
            padding-bottom: 1cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            background-size: 400px 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        @page {
           /* size: A4;*/
            margin: 0;
        }
        @media print {
            /*@page{size: portrait;}*/
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            .hcol1{
                background-color: #CCCCCC !important;
            }
        }
    </style>
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>


<?php
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Color')$Color = $LangTransl;
        if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        if($TranslCode == 'Nominal')$Nominal = $LangTransl;
        if($TranslCode == 'salesPrcCust')$salesPrcCust = $LangTransl;
        if($TranslCode == 'reportedBy')$reportedBy = $LangTransl;
        if($TranslCode == 'ApprovedBy')$ApprovedBy = $LangTransl;
        if($TranslCode == 'knownBy')$knownBy = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
	<?php
		$ADDQRY1 		= "";
		$ADDQRY2 		= "";
		if($COLREFPRJ != "'All'")
			$ADDQRY1	= "A.PRJCODE IN ($COLREFPRJ) AND";
		if($COLREFCUST != "'All'")
			$ADDQRY2	= "A.CUST_CODE IN ($COLREFCUST) AND";

  		// TIAP PAGE 30 BARIS, kecuali halaman terakhir maximal 25
  		$maxRowDef  = 40;
  		$pageNo 	= 0;
  		$nextNo 	= 0;
		$TOTINVPAY 	= 0;
		$GTOTBEF	= 0;
		$GTOTCUR	= 0;
		$GTOTINC	= 0;
		$GTOTRET	= 0;
		$GTOTCLM	= 0;
		$GTOTPPH	= 0;
		$GTOTREM	= 0;
		$sqlCUST 	= "tbl_sinv_header A WHERE $ADDQRY1 $ADDQRY2 A.SINV_PAYSTAT IN ('NR','HR') AND A.SINV_STAT IN (3,6)";
		$resCUSTC 	= $this->db->count_all($sqlCUST);
		$totPage 	= ceil($resCUSTC / $maxRowDef);
		if($resCUSTC == 0)
		{
			?>
				<div class="page">
					<table border="0" width="100%">
			            <tr>
					        <td width="10%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
					        <td width="5%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
					        <td width="60%" class="style2">&nbsp;</td>
					        <td width="25%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
					    </tr>
					    <tr>
					        <td rowspan="3" colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:180px; max-height:180px" ></td>
					        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:26px"><?php echo $comp_name; ?><br>
					          </td>
					  	</tr>
					    <tr>
					      <td colspan="4" style="text-align:center; font-weight:bold;">LAPORAN PIUTANG</td>
					    </tr>
					    <tr>
					      <td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
					    </tr>
					    <tr>
					        <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
					    </tr>
					    <tr>
					        <td nowrap>No. Dokumen</td>
					        <td colspan="3">:</td>
					    </tr>
					    <tr>
					        <td nowrap>Tanggal Pelaporan</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
					    </tr>
					    <tr>
					        <td nowrap>Periode</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
					    </tr>
					</table>
					<br>
					<table border="1" width="100%">
			        	<tr class="hcol1" style="background:#CCCCCC">
							<td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
							<td width="45%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Customer</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Awal</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Pengiriman<br>(+)</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Dana Masuk<br>(-)</td>
							<td width="10%" colspan="3" nowrap style="text-align:center; font-weight:bold">Potongan</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Akhir</td>
				      	</tr>
			        	<tr class="hcol1" style="background:#CCCCCC">
							<td nowrap style="text-align:center; font-weight:bold">Retur</td>
							<td nowrap style="text-align:center; font-weight:bold">Claim</td>
							<td nowrap style="text-align:center; font-weight:bold">PPH 23</td>
				      	</tr>
				      	<?php
					      	for($i=0;$i<25;$i++)
				            {
				            	?>
								<tr>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
				                    <td style="text-align:center;" nowrap>&nbsp;</td>
								</tr>
								<?php
				       		}
			       		?>

				        <tr class="hcol1" style="background:#CCCCCC">
							<td width="50%" colspan="2" nowrap style="text-align:center; font-weight:bold">GRAND TOTAL</td>
				            <td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTBEF,2)?></td>
							<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTCUR,2)?></td>
							<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTINC,2)?></td>
							<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTRET,2)?></td>
							<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTCLM,2)?></td>
							<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTPPH,2)?></td>
							<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTREM,2)?></td>
				      	</tr>
					</table>
					<br>
			        <table width="100%" border="1" rules="all">
			            <tr height="60px" style="border-color:#000; vertical-align:top">
			                <td colspan="9">Catatan: <?php //echo $PR_NOTE; ?></td>
			            </tr>
			        </table>
			        <br>
			        <table width="100%" border="1" rules="all">
			            <tr>
			                <td width="34%" style="text-align: center;"><?=$comp_name?></td>
			                <td width="32%" style="text-align: center;">CORPORATE</td>
			                <td width="34%" style="text-align: center;">COMMITEE</td>
			            </tr>
			            <tr>
			                <td style="text-align: center;"><?=$reportedBy?></td>
			                <td style="text-align: center;"><?=$ApprovedBy?></td>
			                <td style="text-align: center;"><?=$knownBy?></td>
			            </tr>
			            <tr>
			                <td style="text-align: center;"><br><br><br><br><br></td>
			                <td style="text-align: center;">&nbsp;</td>
			                <td style="text-align: center;">&nbsp;</td>
			            </tr>
			            <tr>
			                <td>Nama : </td>
			                <td>Nama : </td>
			                <td>Nama : </td>
			            </tr>
			            <tr>
			                <td style="text-align: center;">FAM</td>
			                <td style="text-align: center;">CFAC</td>
			                <td style="text-align: center;">FC</td>
			            </tr>
			        </table>
			        <table width="100%" border="0" style="display: none;">
			            <tr>
			                <td width="13%">Keterangan : </td>
			                <td width="30%">FAM = Finance Accounting Manager</td>
			                <td width="55%">FC = Finance Committee</td>
			            </tr>
			        </table>
			        <br>
			        <div class="row no-print">
			            <div class="col-xs-12">
			                <div id="Layer1">
			                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
			                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
			                    <i class="fa fa-download"></i> Generate PDF
			                    </button>
			                </div>
			            </div>
			        </div>
				</div>
			<?php
		}
		else
		{
			for($pg=0;$pg<$totPage;$pg++)
			{
				$pageNo 	= $pageNo+1;
				//if($pageNo == 1 || $pageNo == $totPage)
				if($pageNo == 1)
				{
					$strQry	= 0;
					$maxRow = 40;
				}
				else
				{
					$strQry	= $strQry + $maxRow;
					$maxRow = $maxRowDef;
				}
				if($pageNo == $totPage)
					$maxRow = 25;
				?>
				    <div class="page">
	        			<?php
							if($pageNo == 1)
							{
								// START : HEADER
									?>
								        <table border="0" width="100%">
								            <tr>
										        <td width="10%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
										        <td width="5%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
										        <td width="60%" class="style2">&nbsp;</td>
										        <td width="25%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
										    </tr>
										    <tr>
										        <td rowspan="3" colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:180px; max-height:180px" ></td>
										        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:26px"><?php echo $comp_name; ?><br>
										          </td>
										  	</tr>
										    <tr>
										      <td colspan="4" style="text-align:center; font-weight:bold;">LAPORAN PIUTANG</td>
										    </tr>
										    <tr>
										      <td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
										    </tr>
										    <tr>
										        <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
										    </tr>
										    <tr>
										        <td nowrap>No. Dokumen</td>
										        <td colspan="3">:</td>
										    </tr>
										    <tr>
										        <td nowrap>Tanggal Pelaporan</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
										    </tr>
										    <tr>
										        <td nowrap>Periode</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
										    </tr>
										</table>
										<br>
									<?php
								// END : HEADER
							}
							// START : DETAIL
								?>
									<table border="1" width="100%">
							        	<tr class="hcol1" style="background:#CCCCCC">
											<td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
											<td width="45%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Customer</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Awal</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Pengiriman<br>(+)</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Dana Masuk<br>(-)</td>
											<td width="10%" colspan="3" nowrap style="text-align:center; font-weight:bold">Potongan</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Akhir</td>
								      	</tr>
							        	<tr class="hcol1" style="background:#CCCCCC">
											<td nowrap style="text-align:center; font-weight:bold">Retur</td>
											<td nowrap style="text-align:center; font-weight:bold">Claim</td>
											<td nowrap style="text-align:center; font-weight:bold">PPH 23</td>
								      	</tr>
										<?php
											$noU 		= 0;
											$remRow 	= $maxRow;
											$sqlCUST 	= "SELECT DISTINCT A.CUST_CODE, A.CUST_DESC FROM tbl_sinv_header A
															WHERE $ADDQRY1 $ADDQRY2
																A.SINV_PAYSTAT IN ('NR','HR') AND A.SINV_STAT IN (3,6)
															ORDER BY A.CUST_DESC LIMIT $strQry, $maxRow";
											$resCUST 	= $this->db->query($sqlCUST)->result();
											foreach($resCUST as $rowCUST) :
												$noU 		= $noU+1;
												$nextNo 	= $nextNo+1;
												$CUSTCODE	= $rowCUST->CUST_CODE;
												$CUSTDESC	= $rowCUST->CUST_DESC;

												// START : BEFORE
													$TOT_INVBEF	= 0;
													$TOT_POTBEF	= 0;
													$TOT_PPNBEF	= 0;
													$TOT_PPHBEF	= 0;
													$TOT_PAYBEF	= 0;
													$sqlBEF 	= "SELECT SUM(A.ITM_AMOUNT) AS TOT_INVBEF, SUM(A.ITM_DISC) AS TOT_POT,
																		SUM(A.TAX_AMOUNT_PPn1) AS TOT_PPN, SUM(A.TAX_AMOUNT_PPh1) AS TOT_PPH,
																		SUM(B.SINV_AMOUNT_PAID) AS TOT_PAYBEF
																	FROM tbl_sinv_detail A
																		INNER JOIN tbl_sinv_header B ON B.SINV_NUM = A.SINV_NUM
																	WHERE $ADDQRY1 B.CUST_CODE = '$CUSTCODE' AND B.SINV_DATE < '$Start_Date'
																		AND B.SINV_STAT IN (3,6)";
													$resBEF 	= $this->db->query($sqlBEF)->result();
													foreach($resBEF as $rowBEF) :
														$TOT_INVBEF	= $rowBEF->TOT_INVBEF;
														$TOT_POTBEF	= $rowBEF->TOT_POT;
														$TOT_PPNBEF	= $rowBEF->TOT_PPN;
														$TOT_PPHBEF	= $rowBEF->TOT_PPH;
														$TOT_PAYBEF	= $rowBEF->TOT_PAYBEF;
													endforeach;
													$TOTINVBEF 		= $TOT_INVBEF + $TOT_PPNBEF - $TOT_PPHBEF - $TOT_POTBEF - $TOT_PAYBEF;

													$isShow 		= 0;
													if($TOTINVBEF > 0)
														$isShow 	= 1;
												// END : BEFORE

												// START : CURRENT
													$TOT_INVCUR	= 0;
													$TOT_POTCUR	= 0;
													$TOT_PPNCUR	= 0;
													$TOT_PPHCUR	= 0;
													$TOT_PAYCUR	= 0;
													$sqlCUR 	= "SELECT SUM(A.ITM_AMOUNT) AS TOT_INVCUR, SUM(A.ITM_DISC) AS TOT_POT,
																		SUM(A.TAX_AMOUNT_PPn1) AS TOT_PPN, SUM(A.TAX_AMOUNT_PPh1) AS TOT_PPH,
																		SUM(B.SINV_AMOUNT_PAID) AS TOT_PAYCUR
																	FROM tbl_sinv_detail A
																		INNER JOIN tbl_sinv_header B ON B.SINV_NUM = A.SINV_NUM
																	WHERE $ADDQRY1 B.CUST_CODE = '$CUSTCODE' AND
																		B.SINV_DATE >= '$Start_Date' AND B.SINV_DATE <= '$End_Date'
																		AND B.SINV_STAT IN (3,6)";
													$resCUR 	= $this->db->query($sqlCUR)->result();
													foreach($resCUR as $rowCUR) :
														$TOT_INVCUR	= $rowCUR->TOT_INVCUR;
														$TOT_POTCUR	= $rowCUR->TOT_POT;
														$TOT_PPNCUR	= $rowCUR->TOT_PPN;
														$TOT_PPHCUR	= $rowCUR->TOT_PPH;
														$TOT_PAYCUR	= $rowCUR->TOT_PAYCUR;

														$TOTINVCUR 	= $TOT_INVCUR + $TOT_PPNCUR;
														$TOTPOTCUR 	= $TOT_POTCUR + $TOT_PPHCUR;
													endforeach;
													if($TOTINVCUR > 0)
														$isShow 	= 1;
												// END : CURRENT

												// START : DANA MASUK
													$TOT_INCOME	= 0;
												// END : DANA MASUK

												// START : RETUR
													$TOT_RETUR	= 0;
												// END : RETUR

												// START : CLAIM
													$TOT_CLAIM	= 0;
												// END : CLAIM
											
												$AP_BALANCE		= $TOTINVBEF + $TOTINVCUR - $TOT_INCOME - $TOT_RETUR - $TOT_CLAIM - $TOT_PPHCUR;

												$GTOTBEF		= $GTOTBEF + $TOTINVBEF;
												$GTOTCUR		= $GTOTCUR + $TOTINVCUR;
												$GTOTINC		= $GTOTINC + $TOT_INCOME;
												$GTOTRET		= $GTOTRET + $TOT_RETUR;
												$GTOTCLM		= $GTOTCLM + $TOT_CLAIM;
												$GTOTPPH		= $GTOTPPH + $TOT_PPHCUR;
												$GTOTREM		= $GTOTREM + $AP_BALANCE;
												if($noU<=$maxRow && $isShow > 0)
												{
													?>
													<tr>
								                        <td style="text-align:center;" nowrap><?=$nextNo?>.</td>
								                        <td style="text-align:left;" nowrap><?=$CUSTDESC?></td>
								                        <td style="text-align:right;" nowrap><?=number_format($TOTINVBEF,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOTINVCUR,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_INCOME,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_RETUR,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_CLAIM,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_PPHCUR,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($AP_BALANCE,2)?></td>
													</tr>
													<?php
								                	$remRow = $maxRow-$noU;
								                }
								            endforeach;
								            for($i=0;$i<$remRow;$i++)
								            {
								            	?>
												<tr>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
								                    <td style="text-align:center;" nowrap>&nbsp;</td>
												</tr>
												<?php
								       		}
						                ?>
								        <tr class="hcol1" style="background:#CCCCCC">
											<td width="50%" colspan="2" nowrap style="text-align:center; font-weight:bold">GRAND TOTAL</td>
								            <td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTBEF,2)?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTCUR,2)?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTINC,2)?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTRET,2)?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTCLM,2)?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTPPH,2)?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?=number_format($GTOTREM,2)?></td>
								      	</tr>
									</table>
									<br>
								<?php
							// END : DETAIL
							if($pageNo == $totPage)
							{
								?>
							        <table width="100%" border="1" rules="all">
							            <tr height="60px" style="border-color:#000; vertical-align:top">
							                <td colspan="9">Catatan: <?php //echo $PR_NOTE; ?></td>
							            </tr>
							        </table>
							        <br>
							        <table width="100%" border="1" rules="all">
							            <tr>
							                <td width="34%" style="text-align: center;"><?=$comp_name?></td>
							                <td width="32%" style="text-align: center;">CORPORATE</td>
							                <td width="34%" style="text-align: center;">COMMITEE</td>
							            </tr>
							            <tr>
							                <td style="text-align: center;"><?=$reportedBy?></td>
							                <td style="text-align: center;"><?=$ApprovedBy?></td>
							                <td style="text-align: center;"><?=$knownBy?></td>
							            </tr>
							            <tr>
							                <td style="text-align: center;"><br><br><br><br><br></td>
							                <td style="text-align: center;">&nbsp;</td>
							                <td style="text-align: center;">&nbsp;</td>
							            </tr>
							            <tr>
							                <td>Nama : </td>
							                <td>Nama : </td>
							                <td>Nama : </td>
							            </tr>
							            <tr>
							                <td style="text-align: center;">FAM</td>
							                <td style="text-align: center;">CFAC</td>
							                <td style="text-align: center;">FC</td>
							            </tr>
							        </table>
							        <table width="100%" border="0" style="display: none;">
							            <tr>
							                <td width="13%">Keterangan : </td>
							                <td width="30%">FAM = Finance Accounting Manager</td>
							                <td width="55%">FC = Finance Committee</td>
							            </tr>
							        </table>
							        <br>
							        <div class="row no-print">
							            <div class="col-xs-12">
							                <div id="Layer1">
							                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
							                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
							                    <i class="fa fa-download"></i> Generate PDF
							                    </button>
							                </div>
							            </div>
							        </div>
						        <?php
						    }
						?>
					</div>
				<?php
			}
		}
	?>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
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

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>