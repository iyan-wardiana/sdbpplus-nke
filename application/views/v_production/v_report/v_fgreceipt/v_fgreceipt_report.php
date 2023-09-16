<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 09 November 2020
 * File Name	= v_fgreceipt_report.php
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
		$PRJNAME 		= "All";
		if($PRJCODE != "'All'")
		{
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME= $rowPRJ->PRJNAME;
			endforeach;
			$ADDQRY1	= "A.PRJCODE IN ('$PRJCODE') AND";
		}
		/*if($COLREFCUST != "'All'")
			$ADDQRY2	= "A.CUST_CODE IN ($COLREFCUST) AND";*/

  		// TIAP PAGE 30 BARIS, kecuali halaman terakhir maximal 25
  		$maxRowDef  = 45;
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
		$sqlTRX		= "tbl_stf_detail A
						INNER JOIN tbl_prodstep B ON B.PRODS_STEP = A.STF_DEST
							AND B.PRODS_LAST = 1
						WHERE $ADDQRY1
							A.STF_DATE = '$Start_Date' AND ITM_TYPE = 'OUT'";
		$resTRXC 	= $this->db->count_all($sqlTRX);
		$totPage 	= ceil($resTRXC / $maxRowDef);

		$$resTRXC	= 0;
		if($resTRXC == 0)
		{
			?>
				<div class="page">
					<table border="0" width="100%">
					    <tr style="display: none;">
					        <td colspan="4" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $comp_name; ?></td>
					  	</tr>
					    <tr style="display: none;">
					      	<td colspan="4" style="text-align:center; font-weight:bold;"><?=$h1_title?></td>
					    </tr>
					    <tr style="display: none;">
					      	<td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
					    </tr>
					    <tr style="display: none;">
					        <td width="10%" nowrap>Anggaran</td>
					        <td colspan="3">: <?=$PRJNAME?></td>
					    </tr>
					    <tr style="display: none;">
					        <td nowrap width="10%">Tanggal Pelaporan</td>
					        <td colspan="2">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
					        <td nowrap width="15%">&nbsp;</td>
					    </tr>
					    <tr style="display: none;">
					        <td nowrap>Periode</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
					    </tr>
					    <tr>
					        <td colspan="4" style="line-height: 4px">&nbsp;</td>
					    </tr>
					</table>
					<table border="1" width="100%">
			        	<tr class="hcol1" style="font-weight: bold;">
							<td colspan="3" rowspan="3" style="text-align: center; font-size: 18px"><?=$h1_title?></td>
							<td>TANGGAL</td>
							<td style="text-align: center;">=</td>
							<td colspan="2"><?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
				      	</tr>
			        	<tr class="hcol1" style="font-weight: bold;">
							<td>SHIFT</td>
							<td style="text-align: center;">=</td>
							<td colspan="2">&nbsp;</td>
				      	</tr>
				      	<?php
				      		$sqlSEC			= "SELECT PRODS_NAME FROM tbl_prodstep WHERE PRODS_CODE = '$CATEG'";
							$sresSEC		= $this->db->query($sqlSEC)->result();
							foreach($sresSEC as $rowSEC) :
								$PRODS_NAME	= $rowSEC->PRODS_NAME;
							endforeach;
				      	?>
			        	<tr class="hcol1" style="font-weight: bold;">
							<td>BAGIAN</td>
							<td style="text-align: center;">=</td>
							<td colspan="2"><?=$PRODS_NAME?></td>
				      	</tr>
			        	<tr class="hcol1" style="font-weight: bold;">
							<td width="10%" nowrap rowspan="2" style="text-align: center;">PO</td>
							<td width="15%" nowrap rowspan="2" style="text-align: center;">WIP (BODY-RIB)</td>
							<td width="30%" nowrap rowspan="2" style="text-align: center;">CUSTOMER</td>
							<td width="10%" nowrap rowspan="2" style="text-align: center;">WARNA</td>
							<td nowrap colspan="2"  style="text-align: center;">QUANTITY</td>
							<td width="15%" nowrap rowspan="2" style="text-align: center;">KETERANGAN</td>
				      	</tr>
			        	<tr class="hcol1" style="font-weight: bold;">
							<td width="8%" nowrap style="text-align: center;">ROLL</td>
							<td width="12%" nowrap style="text-align: center;">KG</td>
				      	</tr>
				      	<?php
					      	for($i=0;$i<45;$i++)
				            {
				            	?>
								<tr>
				                    <td nowrap>&nbsp;</td>
				                    <td nowrap>&nbsp;</td>
				                    <td nowrap>&nbsp;</td>
				                    <td nowrap>&nbsp;</td>
				                    <td nowrap>&nbsp;</td>
				                    <td nowrap>&nbsp;</td>
				                    <td nowrap>&nbsp;</td>
								</tr>
								<?php
				       		}
			       		?>
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
					$maxRow = 45;
				}
				else
				{
					$strQry	= $strQry + $maxRow;
					$maxRow = $maxRowDef;
				}
				if($pageNo == $totPage)
					$maxRow = 45;
				?>
				    <div class="page">
	        			<?php
							if($pageNo == 1)
							{
								// START : HEADER
									?>
								        <table border="0" width="100%">
								            <tr style="display: none;">
										        <td colspan="4" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $comp_name; ?></td>
										  	</tr>
										    <tr style="display: none;">
										      	<td colspan="4" style="text-align:center; font-weight:bold;"><?=$h1_title?></td>
										    </tr>
										    <tr style="display: none;">
										      	<td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
										    </tr>
										    <tr style="display: none;">
										        <td width="10%" nowrap>Anggaran</td>
										        <td colspan="3">: <?=$PRJNAME?></td>
										    </tr>
										    <tr style="display: none;">
										        <td nowrap width="10%">Tanggal Pelaporan</td>
										        <td colspan="2">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
										        <td nowrap width="15%">&nbsp;</td>
										    </tr>
										    <tr style="display: none;">
										        <td nowrap>Periode</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
										    </tr>
										    <tr>
										        <td colspan="4" style="line-height: 4px">&nbsp;</td>
										    </tr>
										</table>
									<?php
								// END : HEADER
							}
							// START : DETAIL
								?>
									<table border="1" width="100%">
							        	<tr class="hcol1" style="border-top: double; border-bottom: double; line-height: 20px">
											<td width="5%" nowrap style="text-align:center; font-weight:bold; border-left: hidden;">No.</td>
											<td width="15%" nowrap style="text-align:center; font-weight:bold; border-left: hidden;">Kode</td>
											<td width="40%" nowrap style="text-align:left; font-weight:bold; border-left: hidden;">Jenis Obat</td>
											<td width="20%" nowrap style="text-align:right; font-weight:bold; border-left: hidden;">Quantity</td>
											<td width="20%" nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">Nominal</td>
								      	</tr>
										<?php
											$noU 			= 0;
											$GT_VOLM		= 0;
											$GT_AMN			= 0;
											$sqlTRX 		= "SELECT A.Item_Code, B.ITM_NAME, SUM(A.Qty_Min) AS TOT_OUT,
																	SUM(Transaction_Value) AS TOT_OUTAMN
																FROM tbl_stf_detail A
																INNER JOIN tbl_prodstep B ON B.PRODS_STEP = A.STF_DEST
																	AND B.PRODS_LAST = 1
																WHERE $ADDQRY1
																	A.STF_DATE = '$Start_Date' AND ITM_TYPE = 'OUT'
																ORDER BY B.ITM_NAME LIMIT $strQry, $maxRow";
											$resTRX 		= $this->db->query($sqlTRX)->result();
											foreach($resTRX as $rowTRX) :
												$noU 		= $noU+1;
												$nextNo 	= $nextNo+1;
												$Item_Code	= $rowTRX->Item_Code;
												$ITM_NAME	= $rowTRX->ITM_NAME;
												$TOT_OUTVOL	= $rowTRX->TOT_OUT;
												$TOT_OUTAMN	= $rowTRX->TOT_OUTAMN;

												$GT_VOLM 	= $GT_VOLM + $TOT_OUTVOL;
												$GT_AMN 	= $GT_AMN + $TOT_OUTAMN;

												$T_OUTVOLV 	= number_format($TOT_OUTVOL,2);
												$T_OUTAMNV 	= number_format($TOT_OUTAMN,2);

												if($noU<=$maxRow)
												{
													?>
													<tr>
														<td style="text-align:center; border-left: hidden;" nowrap><?=$nextNo?>.</td>
									                    <td style="text-align:center; border-left: hidden;" nowrap><?=$Item_Code?></td>
									                    <td style="text-align:left; border-left: hidden;" nowrap><?=$ITM_NAME?></td>
									                    <td style="text-align:right; border-left: hidden;" nowrap><?=$T_OUTVOLV?></td>
									                    <td style="text-align:right; border-left: hidden; border-right: hidden;" nowrap><?=$T_OUTAMNV?></td>
													</tr>
													<?php
								                	$remRow = $maxRow-$noU;
								                }
								            endforeach;
									      	for($i=0;$i<45;$i++)
								            {
								            	?>
												<tr>
								                    <td style="text-align:center; border-left: hidden;" nowrap>&nbsp;</td>
								                    <td style="text-align:center; border-left: hidden;" nowrap>&nbsp;</td>
								                    <td style="text-align:center; border-left: hidden;" nowrap>&nbsp;</td>
								                    <td style="text-align:center; border-left: hidden;" nowrap>&nbsp;</td>
								                    <td style="text-align:center; border-left: hidden; border-right: hidden;" nowrap>&nbsp;</td>
												</tr>
												<?php
								       		}
											$GT_VOLV 	= number_format($GT_VOLM,2);
											$GT_AMNV 	= number_format($GT_AMN,2);
							       		?>
										<tr style="border-top: double; border-bottom: double; font-weight: bold;">
						                    <td style="text-align:center; border-left: hidden; text-align: center;" colspan="3" nowrap>T O T A L</td>
						                    <td style="text-align:center; border-left: hidden; text-align: right;" nowrap>
							                    <?=$GT_VOLV?>
							                </td>
						                    <td style="text-align:center; border-left: hidden; border-right: hidden; text-align: right;" nowrap>
							                    <?=$GT_AMNV?>
							                </td>
										</tr>
									</table>
									<br>
								<?php
							// END : DETAIL
							if($pageNo == $totPage)
							{
								?>
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