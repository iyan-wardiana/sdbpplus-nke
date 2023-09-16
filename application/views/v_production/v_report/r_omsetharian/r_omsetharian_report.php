<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Agustus 2020
 * File Name	= r_omsetharian_report.php
 * Location		= -
 */

$dateRep 	= date('ymdHis');
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LapOmsetHarian_$dateRep.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

setlocale(LC_ALL, 'id-ID', 'id_ID');

if($viewType == 0)
	$this->load->view('template/head');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');
$LangID 	= $this->session->userdata['LangID'];

?>
<!DOCTYPE html>
<html>
<?php if($viewType == 0)
{
	?>
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
	            padding-top: 1cm;
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

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
	<?php } ?>

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
        if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
        if($TranslCode == 'reportedBy')$reportedBy = $LangTransl;
        if($TranslCode == 'ApprovedBy')$ApprovedBy = $LangTransl;
        if($TranslCode == 'checkedBy')$checkedBy = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
	<?php
		$ADDQRY1 		= "";
		$ADDQRY2 		= "";
		if($COLREFPRJ != "'All'")
			$ADDQRY1	= "A.PRJCODE IN ($COLREFPRJ) AND";
		/*if($COLREFCUST != "'All'")
			$ADDQRY2	= "A.CUST_CODE IN ($COLREFCUST) AND";*/

  		// TIAP PAGE 30 BARIS, kecuali halaman terakhir maximal 25
  		$maxRowDef  = 50;
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
		$sqlSO 		= "tbl_sn_detail A
							INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM AND B.SN_STAT IN (3,6)
						WHERE $ADDQRY1
							B.SN_DATE >= '$Start_Date' AND B.SN_DATE <= '$End_Date'";
		$resSOC 	= $this->db->count_all($sqlSO);
		$totPage 	= ceil($resSOC / $maxRowDef);
		if($resSOC == 0)
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
					      	<td colspan="4" style="text-align:center; font-weight:bold;">LAPORAN OMSET HARIAN</td>
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
					        <td nowrap>No. Reff</td>
					        <td colspan="3">:</td>
					    </tr>
					    <tr>
					        <td nowrap>Tanggal Pelaporan</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
					    </tr>
					</table>
					<br>
					<table border="1" width="100%">
			        	<tr class="hcol1" style="background:#CCCCCC">
							<td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
							<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Tanggal</td>
							<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Qty. Penjualan</td>
							<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Retur Penjualan</td>
							<td width="35%" colspan="2" nowrap style="text-align:center; font-weight:bold">Total Penjualan</td>
							<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Harga Rata-Rata</td>
				      	</tr>
			        	<tr class="hcol1" style="background:#CCCCCC">
							<td width="30" nowrap style="text-align:center; font-weight:bold">Quantity Nett</td>
							<td width="70" nowrap style="text-align:center; font-weight:bold">Total (Rp.)</td>
				      	</tr>
				      	<?php
					      	for($i=0;$i<35;$i++)
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
								</tr>
								<?php
				       		}
			       		?>
					</table>
					<br>
			        <table width="100%" border="1" rules="all">
			            <tr height="60px" style="border-color:#000; vertical-align:top">
			                <td colspan="9">Catatan: <?php //echo $PR_NOTE; ?></td>
			            </tr>
			        </table>
			        <br>
			        <table width="100%" border="0">
			            <tr>
			                <td width="15%" style="text-align: left;" nowrap>Tembusan :</td>
			                <td width="40%" style="text-align: left;">1. Internal Controller</td>
			                <td width="45%" style="text-align: left;">3. Finance Committee</td>
			            </tr>
			            <tr>
			                <td style="text-align: left;">&nbsp;</td>
			                <td style="text-align: left;" nowrap>2. Corporate Finance Accounting Controller</td>
			                <td style="text-align: left;">4. Head Committee</td>
			            </tr>
			        </table>
			        <br>
			        <table width="100%" border="1" rules="all">
			            <tr>
			                <td style="text-align: center;" colspan="3"><?=$comp_name?></td>
			            </tr>
			            <tr>
			                <td width="34%" style="text-align: center;"><?=$CreatedBy?></td>
			                <td width="32%" style="text-align: center;"><?=$checkedBy?></td>
			                <td width="34%" style="text-align: center;"><?=$ApprovedBy?></td>
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
			                <td style="text-align: center;">ADM</td>
			                <td style="text-align: center;">SA</td>
			                <td style="text-align: center;">FAM</td>
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
					$maxRow = 35;
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
										      	<td colspan="4" style="text-align:center; font-weight:bold;">LAPORAN OMSET HARIAN</td>
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
										        <td nowrap>No. Reff</td>
										        <td colspan="3">:</td>
										    </tr>
										    <tr>
										        <td nowrap>Tanggal Pelaporan</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
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
											<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Tanggal</td>
											<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Qty. Penjualan</td>
											<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Retur Penjualan</td>
											<td width="35%" colspan="2" nowrap style="text-align:center; font-weight:bold">Total Penjualan</td>
											<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Harga Rata-Rata</td>
								      	</tr>
							        	<tr class="hcol1" style="background:#CCCCCC">
											<td width="30" nowrap style="text-align:center; font-weight:bold">Quantity Nett</td>
											<td width="70" nowrap style="text-align:center; font-weight:bold">Total (Rp.)</td>
								      	</tr>
										<?php
											$noU 		= 0;
											$sqlSN 		= "SELECT A.SN_DATE, SUM(A.SN_VOLM) AS TOT_SN_VOLM, SUM(A.SN_TOTAL) AS TOT_SN_PRICE, SUM(A.SR_VOLM) AS TOT_SR_VOLM,
																SUM(A.SR_VOLM * A.SR_PRICE) AS TOT_SR_PRICE
															FROM tbl_sn_detail A
																INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM AND B.SN_STAT IN (3,6)
															WHERE $ADDQRY1
																B.SN_DATE >= '$Start_Date' AND B.SN_DATE <= '$End_Date'
															 GROUP BY A.SN_DATE ORDER BY SN_DATE LIMIT $strQry, $maxRow";
											$resSN 		= $this->db->query($sqlSN)->result();
											foreach($resSN as $rowSN) :
												$noU 		= $noU+1;
												$nextNo 	= $nextNo+1;
												$SN_DATE	= date('d-m-Y', strtotime($rowSN->SN_DATE));
												$TOT_SNVOLM	= $rowSN->TOT_SN_VOLM;
												$TOT_SNCOST	= $rowSN->TOT_SN_PRICE;
												$TOT_SRVOLM	= $rowSN->TOT_SR_VOLM;
												$TOT_SRCOST	= $rowSN->TOT_SR_PRICE;

												// START : RETUR PENJUALAN
													$TOT_RETVOL	= $TOT_SRVOLM;
													$TOT_RETAMN	= $TOT_SRCOST;
												// END : RETUR PENJUALAN

												// START : QTY / TOTAL NETT
													$TOT_NETVOL	= $TOT_SNVOLM - $TOT_RETVOL;
													$TOT_NETVOLP= ($TOT_SNVOLM - $TOT_RETVOL) ?: 1;
													$TOT_NETAMN	= $TOT_SNCOST - $TOT_RETAMN;
												// END : QTY / TOTAL NETT

												// START : AVG
													$TOT_AVG	= $TOT_NETAMN / $TOT_NETVOLP;
												// END : TOTAL NET
												if($noU<=$maxRow)
												{
													?>
													<tr>
								                        <td style="text-align:center;" nowrap><?=$nextNo?>.</td>
								                        <td style="text-align:center;" nowrap><?=$SN_DATE?></td>
								                        <td style="text-align:right;" nowrap><?=number_format($TOT_SNVOLM,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_RETVOL,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_NETVOL,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_NETAMN,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_AVG,2)?></td>
													</tr>
													<?php
								                	$remRow = $maxRow-$noU;
								                }
								            endforeach;
									      	for($i=0;$i<35;$i++)
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
												</tr>
												<?php
								       		}
							       		?>
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
							        <table width="100%" border="0">
							            <tr>
							                <td width="15%" style="text-align: left;" nowrap>Tembusan :</td>
							                <td width="40%" style="text-align: left;">1. Internal Controller</td>
							                <td width="45%" style="text-align: left;">3. Finance Committee</td>
							            </tr>
							            <tr>
							                <td style="text-align: left;">&nbsp;</td>
							                <td style="text-align: left;" nowrap>2. Corporate Finance Accounting Controller</td>
							                <td style="text-align: left;">4. Head Committee</td>
							            </tr>
							        </table>
							        <br>
							        <table width="100%" border="1" rules="all">
							            <tr>
							                <td style="text-align: center;" colspan="3"><?=$comp_name?></td>
							            </tr>
							            <tr>
							                <td width="34%" style="text-align: center;"><?=$CreatedBy?></td>
							                <td width="32%" style="text-align: center;"><?=$checkedBy?></td>
							                <td width="34%" style="text-align: center;"><?=$ApprovedBy?></td>
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
							                <td style="text-align: center;">ADM</td>
							                <td style="text-align: center;">SA</td>
							                <td style="text-align: center;">FAM</td>
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

<?php if($viewType == 0)
{
	?>
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
}
?>