<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 23 Agustus 2020
	* File Name		= v_itemmutation_report.php
	* Location		= -
 */

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
                background-color: #5DADE2 !important;
            }
            .hcol2{
                background-color: #2ECC71 !important;
            }
            .hcol3{
                background-color: #CCCCCC !important;
            }
            .hcol4{
                background-color: #76D7C4 !important;
            }
            .hcol5{
                background-color: #F7DC6F !important;
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
        if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
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
		$start_dat1 	= date('Y-m-d', strtotime($Start_Date));
		$end_date1 		= date('Y-m-d', strtotime($End_Date));
		$start_date 	= new DateTime("$start_dat1");
		$end_date 		= new DateTime("$end_date1");
		$interval 		= $start_date->diff($end_date);
		$dayElaps		= $interval->days;

		$ADDQRY1 		= "";
		$ADDQRY2 		= "";
		if($PRJCODE != "'All'")
			$ADDQRY1	= "B.PRJCODE = '$PRJCODE' AND";
		/*if($COLREFSPL != "'All'")
			$ADDQRY2	= "B.SPLCODE IN ($COLREFSPL) AND";*/

  		// TIAP PAGE 30 BARIS, kecuali halaman terakhir maximal 25
  		$maxRowDef  = 8;
  		$maxLastPg  = 6;
  		$pageNo 	= 0;
  		$nextNo 	= 0;
		$TOTINVPAY 	= 0;
		$GTOTBEF	= 0;
		$GTOTCUR	= 0;
		$GTOTPAY	= 0;
		$GTOTPOT	= 0;
		$GTOTREM	= 0;
		$sqlGRIG 	= "tbl_ir_detail A
						INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
						INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
							AND (C.ISWIP = 1 OR C.ISRIB = 1)
						WHERE B.IR_STAT IN (3)";
		$resGRIG 	= $this->db->count_all($sqlGRIG);
		$totPage 	= ceil($dayElaps / $maxRowDef);
		if($dayElaps == $maxRowDef)
			$totPage= 2;

		if($dayElaps == 0)
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
					        <td colspan="3">:</td>
					    </tr>
					    <tr>
					        <td nowrap>Tanggal Pelaporan</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
					    </tr>
					    <tr style="display: none;">
					        <td nowrap>Periode</td>
					        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
					    </tr>
					</table>
					<br>
					<table border="1" width="100%">
			        	<tr class="hcol1" style="background:#5DADE2">
							<td width="5%" nowrap style="text-align:center; font-weight:bold" rowspan="2">No.</td>
							<td width="15%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Tanggal</td>
							<td width="25%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Gudang</td>
							<td width="13%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Saldo Awal</td>
							<td width="26%" nowrap style="text-align:center; font-weight:bold" colspan="2">Mutasi</td>
							<td width="13%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Saldo Akhir</td>
				      	</tr>
			        	<tr class="hcol1" style="background:#5DADE2">
							<td nowrap style="text-align:center; font-weight:bold">Penerimaan</td>
							<td nowrap style="text-align:center; font-weight:bold">Pengeluaran</td>
				      	</tr>
				      	<?php
				      		$bfDay 	= date('Y-m-d', strtotime('-1 days', strtotime($start_dat1)));
							$noU 	= 0;
					      	for($i=0;$i<$maxLastPg;$i++)
				            {
				            	$noU 		= $noU+1;
								$nextNo 	= $nextNo+1;
								$bfDay		= date('Y-m-d', strtotime('+1 days', strtotime($bfDay)));

								// GREIGE TANPA PO (A1) - SALDO AWAL
									$TOT_A1 	= 0;
									$sqlGRIG_A1 = "SELECT SUM(ITM_QTY) AS TOT_A1
													FROM tbl_ir_detail A
														INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
														INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
															AND (C.ISWIP = 1 OR C.ISRIB = 1)
													WHERE B.IR_STAT IN (3) AND A.IR_DATE < '$bfDay'";
									$resGRIG_A1 = $this->db->query($sqlGRIG_A1)->result();
									foreach($resGRIG_A1 as $rowGRIG_A1) :
										$TOT_A1	= $rowGRIG_A1->TOT_A1;
									endforeach;
									$TOT_A1		= $TOT_A1 ?: 0;

								// GREIGE TANPA PO (A2) - MUTASI PENERIMAAN PER TGL LAPORAN
									$TOT_A2 	= 0;
									$sqlGRIG_A2 = "SELECT SUM(ITM_QTY) AS TOT_A2
													FROM tbl_ir_detail A
														INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
														INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
															AND (C.ISWIP = 1 OR C.ISRIB = 1)
													WHERE B.IR_STAT IN (3) AND A.IR_DATE = '$bfDay'";
									$resGRIG_A2 = $this->db->query($sqlGRIG_A2)->result();
									foreach($resGRIG_A2 as $rowGRIG_A2) :
										$TOT_A2	= $rowGRIG_A2->TOT_A2;
									endforeach;
									$TOT_A2		= $TOT_A2 ?: 0;

								// GREIGE TANPA PO (A3) - MUTASI PENGELUARAN PER TGL LAPORAN
									$TOT_A3 	= 0;

								// PO SIAP CELUP (B1) - SALDO AWAL
									$TOT_B1 	= 0;
									$sqlGRIG_B1 = "SELECT SUM(SO_VOLM) AS TOT_B1
													FROM tbl_so_detail A
														INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
													WHERE B.SO_STAT IN (3) AND A.SO_DATE < '$bfDay'";
									$resGRIG_B1 = $this->db->query($sqlGRIG_B1)->result();
									foreach($resGRIG_B1 as $rowGRIG_B1) :
										$TOT_B1	= $rowGRIG_B1->TOT_B1;
									endforeach;
									$TOT_B1		= $TOT_B1 ?: 0;

								// PO SIAP CELUP (B) - MUTASI PENERIMAAN PER TGL LAPORAN
									$TOT_B2 	= 0;
									$sqlGRIG_B2 = "SELECT SUM(SO_VOLM) AS TOT_B2
													FROM tbl_so_detail A
														INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
													WHERE B.SO_STAT IN (3) AND A.SO_DATE = '$bfDay'";
									$resGRIG_B2 = $this->db->query($sqlGRIG_B2)->result();
									foreach($resGRIG_B2 as $rowGRIG_B2) :
										$TOT_B2	= $rowGRIG_B2->TOT_B2;
									endforeach;
									$TOT_B2		= $TOT_B2 ?: 0;

								// PO SIAP CELUP (B) - MUTASI PENGELUARAN PER TGL LAPORAN
									$TOT_B3 	= 0;

								$GTOT_A			= $TOT_A1 + $TOT_A2 - $TOT_A3;
								$GTOT_B			= $TOT_B1 + $TOT_B2 - $TOT_B3;
								$TOT_AB1		= $TOT_A1 + $TOT_B1;
								$TOT_AB2		= $TOT_A2 + $TOT_B2;
								$TOT_AB3		= $TOT_A3 + $TOT_B3;
								$GTOT_AB		= $TOT_AB1 + $TOT_AB2 - $TOT_AB3;

								// BARANG JADI FG_A1 - SALDO AWAL
									$TOTFG_A1 	= 0;
									$sqlFG_A1 	= "SELECT SUM(STF_VOLM) AS TOTFG_A1
													FROM tbl_stf_detail A
													WHERE STF_DATE < '$bfDay' AND ITM_CATEG = 'FG' AND STF_STAT = 3";
									$resFG_A1 	= $this->db->query($sqlFG_A1)->result();
									foreach($resFG_A1 as $rowFG_A1) :
										$TOTFG_A1	= $rowFG_A1->TOTFG_A1;
									endforeach;
									$TOTFG_A1		= $TOTFG_A1 ?: 0;

								// BARANG JADI FG_A2 - MUTASI PENERIMAAN
									$TOTFG_A2 	= 0;
									$sqlFG_A2 	= "SELECT SUM(STF_VOLM) AS TOTFG_A2
													FROM tbl_stf_detail A
													WHERE STF_DATE = '$bfDay' AND ITM_CATEG = 'FG' AND STF_STAT = 3";
									$resFG_A2 	= $this->db->query($sqlFG_A2)->result();
									foreach($resFG_A2 as $rowFG_A2) :
										$TOTFG_A2	= $rowFG_A2->TOTFG_A2;
									endforeach;
									$TOTFG_A2	= $TOTFG_A2 ?: 0;

								// BARANG JADI FG_A3 - MUTASI PENGELUARAN
									$TOTFG_A3 	= 0;

								$GTOT_FG		= $TOTFG_A1 + $TOTFG_A2 - $TOTFG_A3;
								if($bfDay <= $end_date1)
								{
					            	?>
										<tr>
						                    <td rowspan="4" style="text-align:center;" nowrap><?=$noU?>.</td>
						                    <td rowspan="4" style="text-align:center;" nowrap><?=$bfDay?></td>
						                    <td style="text-align:left;" nowrap>Greige tanpa PO (A)</td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_A1,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_A2,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_A3,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($GTOT_A,2)?></td>
										</tr>
										<tr>
						                    <td style="text-align:left;" nowrap>PO Siap Celup (B)</td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_B1,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_B2,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_B3,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($GTOT_B,2)?></td>
										</tr>
										<tr class="hcol4" style="background-color: #76D7C4">
						                    <td style="text-align:left;" nowrap>Total Greige (A+B)</td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_AB1,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_AB2,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOT_AB3,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($GTOT_AB,2)?></td>
										</tr>
										<tr class="hcol5" style="background-color: #F7DC6F">
						                    <td style="text-align:left;" nowrap>Barang Jadi</td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOTFG_A1,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOTFG_A2,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($TOTFG_A3,2)?></td>
						                    <td style="text-align:right;" nowrap><?=number_format($GTOT_FG,2)?></td>
										</tr>
										<tr>
						                    <td style="text-align:left; border-left: hidden; border-right: hidden;" nowrap>&nbsp;</td>
										</tr>
									<?php
								}
								else
								{
									?>
										<tr>
						                    <td rowspan="4" style="text-align:center;" nowrap>&nbsp;</td>
						                    <td rowspan="4" style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:left;" nowrap>Greige tanpa PO (A)</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
										</tr>
										<tr>
						                    <td style="text-align:left;" nowrap>PO Siap Celup (B)</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
										</tr>
										<tr class="hcol4" style="background-color: #76D7C4">
						                    <td style="text-align:left;" nowrap>Total Greige (A+B)</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
										</tr>
										<tr class="hcol5" style="background-color: #F7DC6F">
						                    <td style="text-align:left;" nowrap>Barang Jadi</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
						                    <td style="text-align:center;" nowrap>&nbsp;</td>
										</tr>
										<tr>
						                    <td style="text-align:left; border-left: hidden; border-right: hidden; <?php if($noU==$maxLastPg) { ?> border-bottom: hidden; <?php } ?>" nowrap>&nbsp;</td>
										</tr>
									<?php
								}
				       		}
			       		?>
					</table>
			        <table width="100%" border="0">
			            <tr>
			                <td>Tembusan :</td>
			                <td>1. Head Committe </td>
			                <td>2. Finance Committe</td>
			                <td>3. Corporate Finance Accounting & Controller</td>
			                <td>4. Internal Controller</td>
			            </tr>
			        </table>
			        <br>
			        <table width="100%" border="1" rules="all">
			            <tr class="hcol2" style="background-color: #2ECC71; font-weight: bold;">
			                <td colspan="2" style="text-align: center;"><?=strtoupper($comp_name)?></td>
			            </tr>
			            <tr class="hcol2" style="background-color: #2ECC71; font-weight: bold;">
			                <td style="text-align: center;"><?=$CreatedBy?></td>
			                <td style="text-align: center;"><?=$knownBy?></td>
			            </tr>
			            <tr>
			                <td style="text-align: center;"><br><br><br><br><br></td>
			                <td style="text-align: center;">&nbsp;</td>
			            </tr>
			            <tr>
			                <td>Nama : </td>
			                <td>Nama : </td>
			            </tr>
			            <tr>
			                <td style="text-align: center;">ADM</td>
			                <td style="text-align: center;">FU</td>
			            </tr>
			        </table>
			        <br>
			        <table width="100%" border="0">
			            <tr>
			                <td width="10%">Paraf &nbsp;&nbsp;&nbsp;&nbsp;: </td>
			                <td width="90%">
			                	<table border="1" rules="all">
			                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
				</div>
			<?php
		}
		else
		{
			$remRow 	= $dayElaps;
			$totRow 	= $dayElaps;

			if($dayElaps == 0)
				$totRow	= $dayElaps+1;

			$bfDay 		= date('Y-m-d', strtotime('-1 days', strtotime($start_dat1)));
			$noU 		= 0;
			for($pg=0;$pg<$totPage;$pg++)
			{
				$pageNo 	= $pageNo+1;
				if($pageNo == 1)
				{
					$maxRow = $maxRowDef;
				}
				else
				{
					$maxRow = $maxRowDef;
				}

				if($pageNo == $totPage)
				{
					$maxRow = $maxLastPg;
				}

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
										        <td colspan="3">:</td>
										    </tr>
										    <tr>
										        <td nowrap>Tanggal Pelaporan</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
										    </tr>
										    <tr style="display: none;">
										        <td nowrap>Periode</td>
										        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))." - ".strftime('%d %B %Y', strtotime($End_Date))?></td>
										    </tr>
										</table>
										<br>
									<?php
								// END : HEADER
							}
						?>
						<table border="1" width="100%">
				        	<tr class="hcol1" style="background:#5DADE2">
								<td width="5%" nowrap style="text-align:center; font-weight:bold" rowspan="2">No.</td>
								<td width="15%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Tanggal</td>
								<td width="25%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Gudang</td>
								<td width="13%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Saldo Awal</td>
								<td width="26%" nowrap style="text-align:center; font-weight:bold" colspan="2">Mutasi</td>
								<td width="13%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Saldo Akhir</td>
					      	</tr>
				        	<tr class="hcol1" style="background:#5DADE2">
								<td nowrap style="text-align:center; font-weight:bold">Penerimaan</td>
								<td nowrap style="text-align:center; font-weight:bold">Pengeluaran</td>
					      	</tr>
							<?php
								for($i=1;$i<=$maxRow;$i++)
								{
									$noU 		= $noU+1;
									$nextNo 	= $nextNo+1;
									$bfDay		= date('Y-m-d', strtotime('+1 days', strtotime($bfDay)));

									// GREIGE TANPA PO (A1) - SALDO AWAL
										$TOT_A1 	= 0;
										$sqlGRIG_A1 = "SELECT SUM(ITM_QTY) AS TOT_A1
														FROM tbl_ir_detail A
															INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
															INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
																AND (C.ISWIP = 1 OR C.ISRIB = 1)
														WHERE B.IR_STAT IN (3) AND A.IR_DATE < '$bfDay'";
										$resGRIG_A1 = $this->db->query($sqlGRIG_A1)->result();
										foreach($resGRIG_A1 as $rowGRIG_A1) :
											$TOT_A1	= $rowGRIG_A1->TOT_A1;
										endforeach;
										$TOT_A1		= $TOT_A1 ?: 0;

									// GREIGE TANPA PO (A2) - MUTASI PENERIMAAN PER TGL LAPORAN
										$TOT_A2 	= 0;
										$sqlGRIG_A2 = "SELECT SUM(ITM_QTY) AS TOT_A2
														FROM tbl_ir_detail A
															INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
															INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
																AND (C.ISWIP = 1 OR C.ISRIB = 1)
														WHERE B.IR_STAT IN (3) AND A.IR_DATE = '$bfDay'";
										$resGRIG_A2 = $this->db->query($sqlGRIG_A2)->result();
										foreach($resGRIG_A2 as $rowGRIG_A2) :
											$TOT_A2	= $rowGRIG_A2->TOT_A2;
										endforeach;
										$TOT_A2		= $TOT_A2 ?: 0;

									// GREIGE TANPA PO (A3) - MUTASI PENGELUARAN PER TGL LAPORAN
										$TOT_A3 	= 0;

									// PO SIAP CELUP (B1) - SALDO AWAL
										$TOT_B1 	= 0;
										$sqlGRIG_B1 = "SELECT SUM(SO_VOLM) AS TOT_B1
														FROM tbl_so_detail A
															INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
														WHERE B.SO_STAT IN (3) AND A.SO_DATE < '$bfDay'";
										$resGRIG_B1 = $this->db->query($sqlGRIG_B1)->result();
										foreach($resGRIG_B1 as $rowGRIG_B1) :
											$TOT_B1	= $rowGRIG_B1->TOT_B1;
										endforeach;
										$TOT_B1		= $TOT_B1 ?: 0;

									// PO SIAP CELUP (B) - MUTASI PENERIMAAN PER TGL LAPORAN
										$TOT_B2 	= 0;
										$sqlGRIG_B2 = "SELECT SUM(SO_VOLM) AS TOT_B2
														FROM tbl_so_detail A
															INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
														WHERE B.SO_STAT IN (3) AND A.SO_DATE = '$bfDay'";
										$resGRIG_B2 = $this->db->query($sqlGRIG_B2)->result();
										foreach($resGRIG_B2 as $rowGRIG_B2) :
											$TOT_B2	= $rowGRIG_B2->TOT_B2;
										endforeach;
										$TOT_B2		= $TOT_B2 ?: 0;

									// PO SIAP CELUP (B) - MUTASI PENGELUARAN PER TGL LAPORAN
										$TOT_B3 	= 0;

									$GTOT_A			= $TOT_A1 + $TOT_A2 - $TOT_A3;
									$GTOT_B			= $TOT_B1 + $TOT_B2 - $TOT_B3;
									$TOT_AB1		= $TOT_A1 + $TOT_B1;
									$TOT_AB2		= $TOT_A2 + $TOT_B2;
									$TOT_AB3		= $TOT_A3 + $TOT_B3;
									$GTOT_AB		= $TOT_AB1 + $TOT_AB2 - $TOT_AB3;

									// BARANG JADI FG_A1 - SALDO AWAL
										$TOTFG_A1 	= 0;
										$sqlFG_A1 	= "SELECT SUM(STF_VOLM) AS TOTFG_A1
														FROM tbl_stf_detail A
														WHERE STF_DATE < '$bfDay' AND ITM_CATEG = 'FG' AND STF_STAT = 3";
										$resFG_A1 	= $this->db->query($sqlFG_A1)->result();
										foreach($resFG_A1 as $rowFG_A1) :
											$TOTFG_A1	= $rowFG_A1->TOTFG_A1;
										endforeach;
										$TOTFG_A1		= $TOTFG_A1 ?: 0;

									// BARANG JADI FG_A2 - MUTASI PENERIMAAN
										$TOTFG_A2 	= 0;
										$sqlFG_A2 	= "SELECT SUM(STF_VOLM) AS TOTFG_A2
														FROM tbl_stf_detail A
														WHERE STF_DATE = '$bfDay' AND ITM_CATEG = 'FG' AND STF_STAT = 3";
										$resFG_A2 	= $this->db->query($sqlFG_A2)->result();
										foreach($resFG_A2 as $rowFG_A2) :
											$TOTFG_A2	= $rowFG_A2->TOTFG_A2;
										endforeach;
										$TOTFG_A2	= $TOTFG_A2 ?: 0;

									// BARANG JADI FG_A3 - MUTASI PENGELUARAN
										$TOTFG_A3 	= 0;

									$GTOT_FG		= $TOTFG_A1 + $TOTFG_A2 - $TOTFG_A3;
									if($bfDay <= $end_date1)
									{
						            	?>
											<tr>
							                    <td rowspan="4" style="text-align:center;" nowrap><?=$noU?>.</td>
							                    <td rowspan="4" style="text-align:center;" nowrap><?=$bfDay?></td>
							                    <td style="text-align:left;" nowrap>Greige tanpa PO (A)</td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_A1,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_A2,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_A3,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($GTOT_A,2)?></td>
											</tr>
											<tr>
							                    <td style="text-align:left;" nowrap>PO Siap Celup (B)</td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_B1,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_B2,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_B3,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($GTOT_B,2)?></td>
											</tr>
											<tr class="hcol4" style="background-color: #76D7C4">
							                    <td style="text-align:left;" nowrap>Total Greige (A+B)</td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_AB1,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_AB2,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOT_AB3,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($GTOT_AB,2)?></td>
											</tr>
											<tr class="hcol5" style="background-color: #F7DC6F">
							                    <td style="text-align:left;" nowrap>Barang Jadi</td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOTFG_A1,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOTFG_A2,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($TOTFG_A3,2)?></td>
							                    <td style="text-align:right;" nowrap><?=number_format($GTOT_FG,2)?></td>
											</tr>
											<tr>
							                    <td style="text-align:left; border-left: hidden; border-right: hidden; <?php if($i==$maxRow) { ?> border-bottom: hidden; <?php } ?>" nowrap>&nbsp;</td>
											</tr>
										<?php
									}
									else
									{
										?>
											<tr>
							                    <td rowspan="4" style="text-align:center;" nowrap>&nbsp;</td>
							                    <td rowspan="4" style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:left;" nowrap>Greige tanpa PO (A)</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
											</tr>
											<tr>
							                    <td style="text-align:left;" nowrap>PO Siap Celup (B)</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
											</tr>
											<tr class="hcol4" style="background-color: #76D7C4">
							                    <td style="text-align:left;" nowrap>Total Greige (A+B)</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
											</tr>
											<tr class="hcol5" style="background-color: #F7DC6F">
							                    <td style="text-align:left;" nowrap>Barang Jadi</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
							                    <td style="text-align:center;" nowrap>&nbsp;</td>
											</tr>
											<tr>
							                    <td style="text-align:left; border-left: hidden; border-right: hidden; <?php if($i==$maxRow) { ?> border-bottom: hidden; <?php } ?>" nowrap>&nbsp;</td>
											</tr>
										<?php
									}
									$remRow = $maxRow-$i;
					       		}
				       		?>
						</table>
						<?php
							if($pageNo == $totPage)
							{
								?>
							        <table width="100%" border="0">
							            <tr>
							                <td>Tembusan :</td>
							                <td>1. Head Committe </td>
							                <td>2. Finance Committe</td>
							                <td>3. Corporate Finance Accounting & Controller</td>
							                <td>4. Internal Controller</td>
							            </tr>
							        </table>
				        			<br>
							        <table width="100%" border="1" rules="all">
							            <tr class="hcol2" style="background-color: #2ECC71; font-weight: bold;">
							                <td colspan="2" style="text-align: center;"><?=strtoupper($comp_name)?></td>
							            </tr>
							            <tr class="hcol2" style="background-color: #2ECC71; font-weight: bold;">
							                <td style="text-align: center;"><?=$CreatedBy?></td>
							                <td style="text-align: center;"><?=$knownBy?></td>
							            </tr>
							            <tr>
							                <td style="text-align: center;"><br><br><br><br><br></td>
							                <td style="text-align: center;">&nbsp;</td>
							            </tr>
							            <tr>
							                <td>Nama : </td>
							                <td>Nama : </td>
							            </tr>
							            <tr>
							                <td style="text-align: center;">ADM</td>
							                <td style="text-align: center;">FU</td>
							            </tr>
							        </table>
							        <br>
							        <table width="100%" border="0">
							            <tr>
							                <td width="10%">Paraf &nbsp;&nbsp;&nbsp;&nbsp;: </td>
							                <td width="90%">
							                	<table border="1" rules="all">
							                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							                		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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