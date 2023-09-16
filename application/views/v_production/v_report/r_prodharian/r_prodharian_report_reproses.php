<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 10 September 2020
	* File Name		= r_prodharian_report_reproses.php
	* Location		= -
 */

$dateRep 	= date('ymdHis');
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LapReProsHarian_$dateRep.xls");
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

$cd 		= date('d');
$cm 		= date('m');
$cy 		= date('y');
$docNo 		= $cd."/".$cm."/".$cy."-FBS-MR";

// Menentukan Minggu Laporan
	$dt 	= date('d');
	$mn 	= $MONTH;
	$yr 	= $YEAR;
	$mnRep	= date('Y-m-t', strtotime("$yr-$mn-01"));
	if($cm == $mn)
	{
		$isL= 0;
		$dt = date('d');
	}
	else
	{
		$isL= 1;
		$dt = date('t',strtotime(date($mnRep)));
	}

	$cdtRep1= "$yr-$mn-$dt";
	$cdtRep2= "$yr-$mn-01";
	$fdtRep = date('Y-m-d',strtotime(date($cdtRep2)));
	$cdtRep	= date('Y-m-d', strtotime($cdtRep1));
	$ldtRep = date('Y-m-d', strtotime('-1 days', strtotime($cdtRep)));
	
	// MENENTUKAN TOTAL BARIS S.D. AKHIR BULAN
		$edtRep	= date('Y-m-t', strtotime($cdtRep1));
		$wLevm	= weekNumberOfMonth($edtRep);
		$totRm 	= $wLevm;
	
	// MENENTUKAN TOTAL BARIS S.D. TGL LAPORAN
		$wLev	= weekNumberOfMonth($cdtRep);
		$totRow = $wLev + 1;
		if($dt == '01')
			$totRow = 1;
		if($isL == 1)
			$totRow = $wLev;

		if($totRm < $totRow)
			$totRm	= $totRow;

	$start_date 	= new DateTime("$fdtRep");
	$end_date 		= new DateTime("$cdtRep");
	$interval 		= $start_date->diff($end_date);
	$dayElaps		= $interval->days;

	$ADDQRY1 		= "";
	$ADDQRY2 		= "";
	if($PRJCODE != "All")
		$ADDQRY1	= "PRJCODE = '$PRJCODE' AND";

	function weekNumberOfMonth($date)
	{
		$tgl 		= date_parse($date);
		$tanggal 	= $tgl['day'];
		$bulan   	= $tgl['month'];
		$tahun   	= $tgl['year'];

		//tanggal 1 tiap bulan
			$dtfMn 	= mktime(0, 0, 0, $bulan, 1, $tahun);
			$wkfMn 	= (int) date('W', $dtfMn);

		//tanggal sekarangs
			$cDt 	= mktime(0, 0, 0, $bulan, $tanggal, $tahun);
			$wDt 	= (int) date('W', $cDt);
			$wkLev 	= $wDt - $wkfMn + 1;
		return $wkLev;
	}

// Mendapatkan Nama Bulan Laporan
	if($MONTH == '01')
		$MONTHD		= "Januari";
	elseif($MONTH == '02')
		$MONTHD		= "Februari";
	elseif($MONTH == '03')
		$MONTHD		= "Maret";
	elseif($MONTH == '06')
		$MONTHD		= "April";
	elseif($MONTH == '05')
		$MONTHD		= "Mei";
	elseif($MONTH == '06')
		$MONTHD		= "Juni";
	elseif($MONTH == '07')
		$MONTHD		= "Juli";
	elseif($MONTH == '08')
		$MONTHD		= "Agustus";
	elseif($MONTH == '09')
		$MONTHD		= "September";
	elseif($MONTH == '10')
		$MONTHD		= "Oktober";
	elseif($MONTH == '11')
		$MONTHD		= "November";
	elseif($MONTH == '12')
		$MONTHD		= "Desember";

// Mendapatkan Tanggal Minggu Pertama
	/*$fWeekDt = array();
	$fWeekDy = date('w',strtotime(date("Y-m-01")));
	for($i = 1; $i <= 7 - $fWeekDy; $i++){
	    $fWeekDt[] = date('Y-m-0'.$i);
	}
	$totW 	= count($fWeekDt);*/
	$totW 	= $wLev;

// Mendapatkan Tanggal Pertama Minggu Pertama dalam Bula Laporan
	$fWDtS 	= date('Y-m-d', strtotime($cdtRep2));
	//$fDate 	= date('d',strtotime(date("Y-m-01")));
	$fDate 	= date('d', strtotime($cdtRep2));
	//$x  	= mktime(0, 0, 0, date("m"), date("01"), date("Y"));
	$x  	= mktime(0, 0, 0, $mn, date("01"), $yr);
	$dayNm 	= date("l", $x);
	if ($dayNm == "Sunday")
	{
		$dayS 	= 1;
		$dayAdd	= 6;
	}
	else if ($dayNm == "Monday")
	{
		$dayS 	= 0;
		$dayAdd	= 6;
	}
	else if ($dayNm == "Tuesday")
	{
		$dayS 	= 0;
		$dayAdd	= 5;
	}
	else if ($dayNm == "Wednesday")
	{
		$dayS 	= 0;
		$dayAdd	= 4;
	}
	else if ($dayNm == "Thursday")
	{
		$dayS 	= 0;
		$dayAdd	= 3;
	}
	else if ($dayNm == "Friday")
	{
		$dayS 	= 0;
		$dayAdd	= 1;
	}
	else if ($dayNm == "Saturday")
	{
		$dayS 	= 0;
		$dayAdd	= 1;
	}
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
	            background-size: 600px 200px !important;
	            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	        }
	        
	        @page {
	           /* size: A6;*/
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
	            .hcol6{
	                background-color: #76D7C6 !important;
	            }
	            .hcol5{
	                background-color: #F7DC6F !important;
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
	        if($TranslCode == 'ApprovedBy')$ApprovedBy = $LangTransl;
	        if($TranslCode == 'knownBy')$knownBy = $LangTransl;
		endforeach;
?>
<body class="hold-transition skin-blue sidebar-mini">
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
		      <td colspan="6" style="text-align:center; font-weight:bold;"><?=$h1_title?></td>
		    </tr>
		    <tr>
		      <td colspan="6" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
		    </tr>
		    <tr>
		        <td colspan="6" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
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
				<td width="25%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Lokasi</td>
				<td width="13%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Saldo Awal</td>
				<td width="26%" nowrap style="text-align:center; font-weight:bold" colspan="2">Mutasi</td>
				<td width="13%" nowrap style="text-align:center; font-weight:bold" rowspan="2">Saldo Akhir</td>
	      	</tr>
        	<tr class="hcol1" style="background:#5DADE2">
				<td nowrap style="text-align:center; font-weight:bold">Penerimaan</td>
				<td nowrap style="text-align:center; font-weight:bold">Pengeluaran</td>
	      	</tr>
        	<tr>
				<td colspan="7" style="line-height: 8px; text-align:left; border-left: hidden; border-right: hidden;">&nbsp;</td>
	      	</tr>
			<?php
				// INGAT TAHAPAN-TAHAPAN BERIKUT
					/*
					ONE	Buka Kain
					TWO	Mercer
					THR	DYEING			--- YANG DIGUNAKAN UNTUK REPORT INI
					FOU	Calator
					FIV	Dryer
					SIX	Calendar
					SEV	Packaging
					*/

				$maxRow 	= $totRm;	// Jumlah minggu dalam bulan
				$totRow		= $totRow;	// Total bari laporan
				$noU 		= 0;
				$bfDay 		= date('Y-m-d');
				$eDtW_B 	= $cdtRep;
				$bal_e 		= 0;
				for($i=1;$i<=$maxRow;$i++)
				{
					if($i<=$totRow)
					{
						if($i==$totRow)							// Baris terakhir
						{
							$fDtWL 		= $cdtRep;				// Tanggal Laporan
							$fDyWL		= date('d', strtotime($fDtWL));
							$dtView		= "$fDyWL $MONTHD $YEAR";

							// SALDO AWAL - DIDAPATKAN DARI SALDO AKHIR MINGGU SEBELUMNYA
								$bal_f		= $bal_e;

							// MUTASI PENERIMAAN DIDAPATKAN DARI REPROSES, YAITU HASIL REPROSES DYEING
								$TOTDYE_A1 	= 0;
								$sqlDYE_A1 	= "SELECT SUM(STF_VOLM) AS TOTDYE_A1
												FROM tbl_stf_detail
												WHERE $ADDQRY1 STF_FROM = 'THR'
													AND JO_CATEG = 2 AND STF_STAT IN (3,6) AND STF_DATE = '$fDtWL'";
								$resDYE_A1 	= $this->db->query($sqlDYE_A1)->result();
								foreach($resDYE_A1 as $rowDYE_A1) :
									$TOTDYE_A1	= $rowDYE_A1->TOTDYE_A1;
								endforeach;
								$tot_in		= $TOTDYE_A1 ?: 0;

							// MUTASI PENGELUARAN SELALU KOOSNG 
								$tot_out		= 0;

							// SALDO AKHIR - DIGUNAKAN UNTUK SALDO AWAL MINGGU BERIKUTNYA
								$bal_e		= $bal_f + $tot_in - $tot_out;
						}
						elseif($i==1)
						{
							$fDtWL 		= "fDtW_1";
							$eDtWL 		= "eDtW_1";
							$fDyWL 		= "fDyW_1";
							$eDyWL 		= "eDyW_1";
							$eDtWL		= date('Y-m-d', strtotime('+'.$dayAdd.' days', strtotime($fWDtS)));	// Tanggal Terakhir Minggu Pertama
							if($i==$wLev)
							{
								$eDtWL	= $ldtRep;
							}
							$fDyWL 		= "01";
							$eDyWL		= date('d', strtotime($eDtWL));
							$dtView		= "$fDyWL - $eDyWL $MONTHD $YEAR";

							// SALDO AWAL - DIDAPATKAN DARI SALDO AKHIR MINGGU SEBELUMNYA (MINGGU KE-1 SELALU 0)
								$bal_f		= 0;

							// MUTASI PENERIMAAN DIDAPATKAN DARI REPROSES, YAITU HASIL REPROSES DYEING
								$TOTDYE_A1 	= 0;
								$sqlDYE_A1 	= "SELECT SUM(STF_VOLM) AS TOTDYE_A1
												FROM tbl_stf_detail
												WHERE $ADDQRY1 STF_FROM = 'THR' AND JO_CATEG = 2 AND STF_STAT IN (3,6)
													AND (STF_DATE >= '$fWDtS' AND STF_DATE <= '$eDtWL')";
								$resDYE_A1 	= $this->db->query($sqlDYE_A1)->result();
								foreach($resDYE_A1 as $rowDYE_A1) :
									$TOTDYE_A1	= $rowDYE_A1->TOTDYE_A1;
								endforeach;
								$tot_in		= $TOTDYE_A1 ?: 0;

							// MUTASI PENGELUARAN SELALU KOOSNG (BY. ADIT : 10 09 20)
								$tot_out		= 0;

							// SALDO AKHIR - DIGUNAKAN UNTUK SALDO AWAL MINGGU BERIKUTNYA
								$bal_e		= $bal_f + $tot_in - $tot_out;
						}
						else
						{
							$eDtW_B 	= $i-1;
							$eDtW_B 	= $eDtWL;
							$fDtWL 		= "fDtW_$totW";
							$eDtWL 		= "eDtW_$totW";
							$fDyWL 		= "fDyW_$totW";
							$eDyWL 		= "eDyW_$totW";
							$fDtWL		= date('Y-m-d', strtotime('+1 days', strtotime($eDtW_B)));			// Tanggal Pertama
							$eDtWL		= date('Y-m-d', strtotime('+6 days', strtotime($fDtWL)));			// Tanggal Terakhir
							if($i==$wLev)
							{
								$eDtWL	= $ldtRep;
							}
							$fDyWL		= date('d', strtotime($fDtWL));
							$eDyWL		= date('d', strtotime($eDtWL));
							$dtView		= "$fDyWL - $eDyWL $MONTHD $YEAR";

							// SALDO AWAL - DIDAPATKAN DARI SALDO AKHIR MINGGU SEBELUMNYA
								$bal_f		= $bal_e;

							// MUTASI PENERIMAAN DIDAPATKAN DARI Reproses COMPAKTOR, YAITU MESIN TERAKHIR SEBELUM PACKING
								$TOTDYE_A1 	= 0;
								$sqlDYE_A1 	= "SELECT SUM(STF_VOLM) AS TOTDYE_A1
												FROM tbl_stf_detail
												WHERE $ADDQRY1 STF_FROM = 'THR' AND JO_CATEG = 2 AND STF_STAT IN (3,6)
													AND (STF_DATE >= '$fDtWL' AND STF_DATE <= '$eDtWL')";
								$resDYE_A1 	= $this->db->query($sqlDYE_A1)->result();
								foreach($resDYE_A1 as $rowDYE_A1) :
									$TOTDYE_A1	= $rowDYE_A1->TOTDYE_A1;
								endforeach;
								$tot_in		= $TOTDYE_A1 ?: 0;

							// MUTASI PENGELUARAN SELALU KOOSNG (BY. ADIT : 10 09 20)
								$tot_out	= 0;

							// SALDO AKHIR - DIGUNAKAN UNTUK SALDO AWAL MINGGU BERIKUTNYA
								$bal_e		= $bal_f + $tot_in - $tot_out;
						}
		            	?>
							<tr style="line-height: 25px;">
			                    <td style="text-align:center; vertical-align: middle;" nowrap><?=$i?>.</td>
			                    <td style="text-align:center; vertical-align: middle;" nowrap><?=$dtView?></td>
			                    <td style="text-align:left; vertical-align: middle;" nowrap>Reproses</td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($bal_f,2)?></td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($tot_in,2)?></td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($tot_out,2)?></td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($bal_e,2)?></td>
							</tr>
							<tr>
			                    <td style="line-height: 8px; text-align:left; border-left: hidden; border-right: hidden; <?php if($i==$maxRow) { ?> border-bottom: hidden; <?php } ?>" nowrap>&nbsp;</td>
							</tr>
						<?php
					}
					else
					{
		            	?>
							<tr style="line-height: 25px;">
			                    <td style="text-align:center; vertical-align: middle;" nowrap>&nbsp;</td>
			                    <td style="text-align:center; vertical-align: middle;" nowrap>&nbsp;</td>
			                    <td style="text-align:left; vertical-align: middle;" nowrap>&nbsp;</td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap>&nbsp;</td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap>&nbsp;</td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap>&nbsp;</td>
			                    <td style="text-align:right; vertical-align: middle;" nowrap>&nbsp;</td>
							</tr>
							<tr>
			                    <td style="line-height: 8px; text-align:left; border-left: hidden; border-right: hidden; <?php if($i==$maxRow) { ?> border-bottom: hidden; <?php } ?>" nowrap>&nbsp;</td>
							</tr>
						<?php
					}
	       		}
       		?>
			<tr>
                <td style="line-height: 8px; text-align:left; border-left: hidden; border-right: hidden; " nowrap>&nbsp;</td>
			</tr>
			<tr style="font-weight: bold;">
                <td style="text-align:center; vertical-align: middle;" colspan="4" rowspan="2" nowrap>Rata2 Reproses <?php echo "$dt $MONTHD $YEAR"; ?></td>
                <td style="text-align:center; vertical-align: middle;" nowrap>Total Reproses</td>
                <td style="text-align:center; vertical-align: middle;" nowrap>Jumlah Hari</td>
                <td style="text-align:center; vertical-align: middle;" nowrap>Rata2/hari</td>
			</tr>
			<?php
				$dayElaps 	= $dayElaps + 1;
				$avgProd	= $bal_e / $dayElaps;
			?>
			<tr>
                <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($bal_e,2)?></td>
                <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($dayElaps,0)?></td>
                <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($avgProd,2)?></td>
			</tr>
			<tr>
                <td style="line-height: 8px; text-align:left; border-left: hidden; border-right: hidden; " nowrap>&nbsp;</td>
			</tr>
			<tr style="font-weight: bold;">
                <td style="text-align:center; vertical-align: middle;" colspan="4" rowspan="2" nowrap>Persentase Reproses <?php echo "$dt $MONTHD $YEAR"; ?></td>
                <td style="text-align:center; vertical-align: middle;" nowrap>Total Reproses</td>
                <td style="text-align:center; vertical-align: middle;" nowrap>Total Celup</td>
                <td style="text-align:center; vertical-align: middle;" nowrap>Rata2/hari</td>
			</tr>
			<?php
				// Total SN dari Awal Bulan sampai tgl. Laporan
				$dateS 		= date('Y-m-d', strtotime($fWDtS));
				$dateE 		= date('Y-m-d', strtotime($cdtRep));
				$TOTDYE_A1 	= 0;
				$sqlDYE_A1 	= "SELECT SUM(STF_VOLM) AS TOTDYE_A1
								FROM tbl_stf_detail
								WHERE $ADDQRY1 STF_FROM = 'THR' AND STF_STAT IN (3,6)
									AND (STF_DATE >= '$dateS' AND STF_DATE <= '$dateE')";
				$resDYE_A1 	= $this->db->query($sqlDYE_A1)->result();
				foreach($resDYE_A1 as $rowDYE_A1) :
					$TOTDYE_A1	= $rowDYE_A1->TOTDYE_A1;
				endforeach;
				$tot_dye	= $TOTDYE_A1 ?: 0;
				$tot_dyeP	= $TOTDYE_A1 ?: 1;
				$avgDyeP	= $bal_e / $tot_dyeP * 100;
			?>
			<tr>
                <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($bal_e,2)?></td>
                <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($tot_dye,0)?></td>
                <td style="text-align:right; vertical-align: middle;" nowrap><?=number_format($avgDyeP,2)?></td>
			</tr>
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
                <td>Nama : DWI ASRIATI</td>
                <td>Nama : VERY CAHYADI</td>
            </tr>
            <tr>
                <td style="text-align: left;">ADM PRODUKSI</td>
                <td style="text-align: left;">FM</td>
            </tr>
        </table>
        <table width="100%" border="0">
            <tr style="font-weight: bold;">
                <td nowrap width="30%">Keterangan:  ADM - Administrasi / Staff </td>
                <td nowrap width="70%">FM - Factory Manager</td>
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