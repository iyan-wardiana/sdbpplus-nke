<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 23 Agustus 2020
 * File Name	= r_cashbankmutation_report.php
 * Location		= -*/

setlocale(LC_ALL, 'id-ID', 'id_ID');

$this->load->view('template/head');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');
$LangID 	= $this->session->userdata['LangID'];

$cd 		= date('d');
$cm 		= date('m');
$cy 		= date('y');
$docNo 		= $cd."/".$cm."/".$cy."-FBS-MR";
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
            font: 12pt Calibri, Helvetica, sans-serif;
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
            .hcol2{
                background-color: #2ECC71 !important;
            }
        }

        table.tblPosit {
		    width: auto;
		    margin-right: 0px;
		    margin-left: auto;
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
        if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
        if($TranslCode == 'checkedBy')$checkedBy = $LangTransl;
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
		$ADDQRY1 		= "isHO = 1 AND";
		$ADDQRY2 		= "";
		if($COLREFPRJ != "'All'")
			$ADDQRY1	= "A.PRJCODE IN ($COLREFPRJ) AND";
		/*if($COLREFCUST != "'All'")
			$ADDQRY2	= "A.CUST_CODE IN ($COLREFCUST) AND";*/

  		// TIAP PAGE 30 BARIS, kecuali halaman terakhir maximal 25
  		$maxRowDef  = 15;
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
		$sqlCUST 	= "tbl_chartaccount WHERE $ADDQRY1 Account_Class IN (3,4) AND isLast = 1";
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
					      <td colspan="4" style="text-align:center; font-weight:bold;">LAPORAN HUTANG</td>
					    </tr>
					    <tr>
					      <td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
					    </tr>
					    <tr>
					        <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
					    </tr>
					    <tr>
					        <td nowrap>No. Dokume an</td>
					        <td colspan="3">:</td>
					    </tr>
					    <tr>
					        <td nowrap>No Ref</td>
					        <td colspan="3">: </td>
					    </tr>
					    <tr>
					        <td nowrap>Periode</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
					    </tr>
					</table>
					<br>
					<table border="1" width="100%">
			        	<tr class="hcol1" style="background-color: #2ECC71;">
							<td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
							<td width="45%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Customer</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Awal</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Pengiriman<br>(+)</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Dana Masuk<br>(-)</td>
							<td width="10%" colspan="3" nowrap style="text-align:center; font-weight:bold">Potongan</td>
							<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Akhir</td>
				      	</tr>
			        	<tr class="hcol1" style="background-color: #2ECC71;">
							<td nowrap style="text-align:center; font-weight:bold">Retur</td>
							<td nowrap style="text-align:center; font-weight:bold">Claim</td>
							<td nowrap style="text-align:center; font-weight:bold">PPH 23</td>
				      	</tr>
				      	<?php
					      	for($i=0;$i<41;$i++)
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

				        <tr class="hcol1" style="background-color: #2ECC71;">
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
			                <td style="text-align: center;"><?=$checkedBy?></td>
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
					$maxRow = 15;
				}
				else
				{
					$strQry	= $strQry + $maxRow;
					$maxRow = $maxRowDef;
				}
				if($pageNo == $totPage)
					$maxRow = 15;
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
										      <td colspan="4" style="text-align:center; font-weight:bold;"><?=$h1_title?></td>
										    </tr>
										    <tr>
										      <td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
										    </tr>
										    <tr>
										        <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
										    </tr>
										    <tr>
										        <td nowrap>No. Dokumen</td>
										        <td colspan="3">: <?php echo $docNo; ?></td>
										    </tr>
										    <tr>
										        <td nowrap>No. Reff</td>
										        <td colspan="3">: </td>
										    </tr>
										    <tr>
										        <td nowrap>Tanggal</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))?></td>
										    </tr>
										</table>
										<br>
									<?php
								// END : HEADER
							}
							// START : DETAIL
								?>
									<table border="1" width="100%">
							        	<tr class="hcol1" style="background-color: #2ECC71;">
											<td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No.</td>
											<td width="45%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Nama Bank</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No. Acc</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Awal</td>
											<td width="10%" colspan="2" nowrap style="text-align:center; font-weight:bold">Mutasi</td>
											<td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo Akhir</td>
								      	</tr>
							        	<tr class="hcol1" style="background-color: #2ECC71;">
											<td nowrap style="text-align:center; font-weight:bold">Penerimaan</td>
											<td nowrap style="text-align:center; font-weight:bold">Pengeluaran</td>
								      	</tr>
										<?php
											$noU 		= 0;
											$sqlCOA 	= "SELECT A.Account_Number, A.Account_NameId, A.Base_OpeningBalance, A.Base_Debet, A.Base_Kredit
																FROM tbl_chartaccount A
															WHERE $ADDQRY1
																A.Account_Class IN (3,4) AND A.isLast = 1
															ORDER BY A.Account_Number LIMIT $strQry, $maxRow";
											$resCOA 	= $this->db->query($sqlCOA)->result();
											foreach($resCOA as $rowCOA) :
												$noU 		= $noU+1;
												$nextNo 	= $nextNo+1;
												$ACCNUMB	= $rowCOA->Account_Number;
												$ACCNAME	= $rowCOA->Account_NameId;
												$ACCOPBAL	= $rowCOA->Base_OpeningBalance;
												$ACCDEBET	= $rowCOA->Base_Debet;
												$ACCKREDIT	= $rowCOA->Base_Kredit;

												// SALDO AWAL : SALDO SEBELUM TANGGAL LAPORAN
													$BaseBD = 0;
													$BaseBK = 0;
													$sqlBef	= "SELECT Base_Debet, Base_Kredit FROM tbl_journaldetail WHERE Acc_Id = $ACCNUMB
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
													$reSBef	= $this->db->query($sqlBef)->result();
													foreach($reSBef as $rowBef) :
														$BaseBD	= $rowBef->Base_Debet;
														$BaseBK	= $rowBef->Base_Kredit;
													endforeach;
													$BAL_S	= $BaseBD - $BaseBK;

												// PENERIMAAN : TOTAL PENERIMAAN PER PERIODE LAPORAN
													$BaseCD = 0;
													$BaseCK = 0;
													$sqlCur	= "SELECT Base_Debet, Base_Kredit FROM tbl_journaldetail WHERE Acc_Id = $ACCNUMB
																AND JournalH_Date >= '$Start_Date' AND JournalH_Date <= '$End_Date' AND GEJ_STAT = 3";
													$reSCur	= $this->db->query($sqlCur)->result();
													foreach($reSCur as $rowCur) :
														$BaseCD	= $rowCur->Base_Debet;
														$BaseCK	= $rowCur->Base_Kredit;
													endforeach;
													$TOT_I	= $BaseCD - $BaseCK;

												// PENGELUARAN : TOTAL PENGELUARAN PER PERIODE LAPORAN
													$TOT_O	= 0;

												// SALDO AKHIR
													$BSL_E	= $BAL_S + $TOT_I - $TOT_O;

												if($noU<=$maxRow)
												{
													?>
													<tr>
								                        <td style="text-align:center;" nowrap><?=$nextNo?>.</td>
								                        <td style="text-align:left;" nowrap><?=$ACCNAME?></td>
								                        <td style="text-align:center;" nowrap><?=$ACCNUMB?></td>
								                        <td style="text-align:right;" nowrap><?=number_format($BAL_S,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_I,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($TOT_O,2)?></td>
														<td style="text-align:right;" nowrap><?=number_format($BSL_E,2)?></td>
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
												</tr>
												<?php
								       		}
						                ?>
									</table>
									<br>
							        <table width="100%" border="0">
							            <tr>
							                <td>Tembusan :</td>
							                <td>1. Head Committe </td>
							                <td>2. Finance Committe</td>
							                <td>3. Corporate Finance Accounting & Controller</td>
							                <td>4. Internal Controller</td>
							            </tr>
							        </table>
								<?php
							// END : DETAIL
							if($pageNo == $totPage)
							{
								?>
							        <table width="100%" border="1" rules="all" style="display: none;">
							            <tr height="60px" style="border-color:#000; vertical-align:top">
							                <td colspan="9">Catatan: <?php //echo $PR_NOTE; ?></td>
							            </tr>
							        </table>
							        <br>
							        <table width="100%" border="1" rules="all">
							            <tr class="hcol2" style="background-color: #2ECC71; font-weight: bold;">
							                <td colspan="4" style="text-align: center;"><?=$comp_name?></td>
							            </tr>
							            <tr class="hcol2" style="background-color: #2ECC71; font-weight: bold;">
							                <td style="text-align: center;"><?=$CreatedBy?></td>
							                <td style="text-align: center;"><?=$checkedBy?></td>
							                <td style="text-align: center;"><?=$ApprovedBy?></td>
							                <td style="text-align: center;"><?=$knownBy?></td>
							            </tr>
							            <tr>
							                <td style="text-align: center;"><br><br><br><br><br></td>
							                <td style="text-align: center;">&nbsp;</td>
							                <td style="text-align: center;">&nbsp;</td>
							                <td style="text-align: center;">&nbsp;</td>
							            </tr>
							            <tr>
							                <td>Nama : </td>
							                <td>Nama : </td>
							                <td>Nama : </td>
							                <td>Nama : </td>
							            </tr>
							            <tr>
							                <td style="text-align: center;">ADM</td>
							                <td style="text-align: center;">SF</td>
							                <td style="text-align: center;">SA</td>
							                <td style="text-align: center;">FAM</td>
							            </tr>
							        </table>
							        <table width="100%" border="0">
							            <tr>
							                <td width="10%">Keterangan : </td>
							                <td width="5%">ADM</td>
							                <td width="85%">= Admin/Staf</td>
							            </tr>
							            <tr>
							                <td>&nbsp;</td>
							                <td>SF</td>
							                <td>= Chief/Senior Finance</td>
							            </tr>
							            <tr>
							                <td>&nbsp;</td>
							                <td>SA</td>
							                <td>= Chief/Senior Accounting</td>
							            </tr>
							            <tr>
							                <td>&nbsp;</td>
							                <td>FAM</td>
							                <td>= Finance Accounting Manager</td>
							            </tr>
							            <tr style="text-align: right;">
							                <td>&nbsp;</td>
							                <td>&nbsp;</td>
							                <td style="text-align: right;">
							                	<table border="1" rules="all" class="tblPosit">
							                		<tr>
							                			<td>FG/19/MOF-LP/01</td>
							                		</tr>
							                	</table>
							                </td>
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