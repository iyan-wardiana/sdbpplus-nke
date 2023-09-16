<?php
	$dayList = array(
		'Sun' => 'Minggu',
		'Mon' => 'Senin',
		'Tue' => 'Selasa',
		'Wed' => 'Rabu',
		'Thu' => 'Kamis',
		'Fri' => 'Jumat',
		'Sat' => 'Sabtu'
	);

	function konversi($x)
	{
		$x = abs($x);
		$angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
	
		if($x < 12){
			$temp = " ".$angka[$x];
		}else if($x<20){
			$temp = konversi($x - 10)." belas";
		}else if ($x<100){
			$temp = konversi($x/10)." puluh". konversi($x%10);
		}else if($x<200){
			$temp = " seratus".konversi($x-100);
		}else if($x<1000){
			$temp = konversi($x/100)." ratus".konversi($x%100);   
		}else if($x<2000){
			$temp = " seribu".konversi($x-1000);
		}else if($x<1000000){
			$temp = konversi($x/1000)." ribu".konversi($x%1000);   
		}else if($x<1000000000){
			$temp = konversi($x/1000000)." juta".konversi($x%1000000);
		}else if($x<1000000000000){
			$temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
		}
	
		return $temp;
	}
	
	function tkoma($x)
	{
		$str_dec = ',';
		if(strpos($x, $str_dec) == false)
			$str_dec = '.';

		$str = stristr($x, $str_dec);
		$ex = explode($str_dec, $x);

		if(($ex[1]/10) >= 1){
			$a = abs($ex[1]);
		}

		$string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
		$temp = "";
	
		$a = $ex[1]/10;
		$pjg = strlen($str);
		$i =1;
	
		if($a>=1 && $a< 12){   
			$temp .= " ".$string[$a];
		}else if($a>12 && $a < 20){   
			$temp .= konversi($a - 10)." belas";
		}else if ($a>20 && $a<100){   
			$temp .= konversi($a / 10)." puluh". konversi($a % 10);
		}else{
			if($a<1){
				while ($i<$pjg){     
					$char = substr($str,$i,1);     
					$i++;
					$temp .= " ".$string[$char];
				}
			}
		}  

		return $temp;
	}
	
	function terbilang($x)
	{
		if($x<0){
			$hasil = "minus ".trim(konversi($x));
		}else{
			$str_dec = ',';
			if(strpos($x, $str_dec) == false)
				$str_dec = '.';

			$ex = explode($str_dec, $x);
			if(count($ex) != 1)
			{
				$poin 	= trim(tkoma($x));
				$hasil1 = trim(konversi($x));
				$hasil 	= $hasil1." koma ".$poin;
			}
			else
			{
				$hasil1 = trim(konversi($x));
				$hasil 	= $hasil1;
			}

		}

		return $hasil;  
	}

	function tanggal_indo($tanggal)
	{
		$bulan = array (1 =>	'Januari',
								'Februari',
								'Maret',
								'April',
								'Mei',
								'Juni',
								'Juli',
								'Agustus',
								'September',
								'Oktober',
								'November',
								'Desember'
							);
		$split = explode('-', $tanggal);
		return $bulan[ (int)$split[1] ];
	}

	$SPLDESC 	= "";
	$getSPL 	= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
	$resSPL 	= $this->db->query($getSPL);
	if($resSPL->num_rows() > 0)
	{
		foreach($resSPL->result() as $rSPL):
			$SPLDESC 	= $rSPL->SPLDESC;
		endforeach;
	}

	$s_com 	= "SELECT comp_add FROM tappname";
	$r_com 	= $this->db->query($s_com)->result();
	foreach($r_com as $rw_com):
		$comp_add = $rw_com->comp_add;
	endforeach;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?></title>

	<link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/contract.png'; ?>" sizes="32x32">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<style type="text/css">
		 /*@page { margin: 0 }*/
		 body { margin: 0; font-size: 9pt; }
        .sheet {
			margin: 0;
			overflow: hidden;
			position: relative;
			box-sizing: border-box;
			page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; }
        body.A3.landscape     .sheet { width: 420mm; }
        body.A4               .sheet { width: 210mm; }
        body.A4.landscape     .sheet { width: 297mm; }
        body.A5               .sheet { width: 148mm; }
        body.A5.landscape     .sheet { width: 210mm; }
        body.letter           .sheet { width: 216mm; }
        body.letter.landscape .sheet { width: 280mm; }
        body.legal            .sheet { width: 216mm; }
        body.legal.landscape  .sheet { width: 357mm; }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 0.5cm 0.5cm 0.97cm 0.5cm }

        /** For screen preview **/
        @media screen {
			body { background: #e0e0e0 }
			.sheet {
				background: white;
				box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
				margin: 5mm auto;
				border-radius: 5px 5px 5px 5px;
			}
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
          	/* @page { size: a4;} */
          	body.A3.landscape { width: 420mm }
          	body.A3, body.A4.landscape { width: 297mm }
          	body.A4, body.A5.landscape { width: 210mm }
          	body.A5                    { width: 148mm }
          	body.letter, body.legal    { width: 216mm }
          	body.letter.landscape      { width: 280mm }
          	body.legal.landscape       { width: 357mm }

		  .sheet { padding: 0.5cm 0.5cm 0.97cm 0.5cm }
        }

		.flex-container {
			border: 1px solid;
			padding: 2px;
			margin-top: 10px;
		}

		.flex-container > div.header {
			display: flex;
			flex-wrap: wrap;
			border-top: 1px solid;
			border-left: 1px solid;
			border-right: 1px solid;
			width: 100%;
			/* height: 110px; */
			padding: 3px;
		}

		.flex-container > div.header > div.logo {
			width: 150px;
			margin: auto 2px;
		}

		.flex-container > div.header > div.logo img {
			width: 200px;
		}

		.flex-container > div.header > div.title {
			width: 435px;
			text-align: center;
			margin-bottom: 10px;
		}

		.flex-container > div.header > div.title > div:first-child {
			font-size: 16pt;
			font-weight: bold;
			text-align: center;
		}

		.flex-container > div.header > div.title > div:last-child {
			font-size: 12pt;
			font-weight: bold;
			text-align: center;
		}

		.flex-container > div.header > div.frmDoc {
			width: 150px;
			font-size: 8pt;
		}

		.flex-container > div.header > div.header-doc {
			width: 100%;
		}

		.flex-container > div.header > div.header-doc table td {
			padding: 2px;
		}

		.flex-container > div.detail-content {
			width: 100%;
		}

		.flex-container > div.detail-asign {
			display: flex;
			width: 100%;
			min-height: 20px;
			justify-content: space-between;
			padding-top: 3px;
			padding-bottom: 3px;
		}

		.detail-asign > div {
			width: 247px;
		}

		.detail-asign > div table td {
			border: 1px solid;
			font-size: 9pt;
			padding: 1px;
		}

		.flex-container > div.detail-notes {
			width: 100%;
			border: 1px solid;
			padding: 5px;
			font-size: 7pt;
			font-style: italic;
		}

		div.detail-content table {
			border: 1px solid;
		}

		div.detail-content table th {
			border: 1px solid;
			padding: 3px;
			vertical-align: middle;
			font-size: 9pt;
			text-align: center;
		}

		div.detail-content table td {
			/* border: 1px solid; */
			padding: 3px;
			vertical-align: top;
            border-right: 1px dashed lightgray !important;
		}

		div.footnotes {
			display: flex;
			justify-content: space-between;
			font-size: 6pt;
			font-style: italic;
			margin-top: 2px;
		}

		#tfoot {
			border: 1px solid;
			background-color: #ccc;
		}
		
        #Layer1 {
            position: absolute;
            top: 5px;
            left: 20px;
        }
	</style>
</head>
<body class="page A4">
	<section class="page sheet custom">
		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
		<div class="flex-container">
			<div class="header">
				<div class="logo">
					<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
				</div>
				<div class="title">
					<div>TANDA TERIMA KWITANSI</div>
					<div>(TTK)</div>
				</div>
				<div class="frmDoc">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="50">Dok. No.</td>
							<td>:</td>
							<td>&nbsp;FRM.NKE.13.20</td>
						</tr>
						<tr>
							<td width="50">Revisi</td>
							<td>:</td>
							<td>&nbsp;(25/11/22)</td>
						</tr>
						<tr>
							<td width="50">Amand.</td>
							<td>:</td>
							<td></td>
						</tr>
					</table>
				</div>
				<div class="header-doc" style="padding-top: 5px;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="170">Telah diterima kwitansi dari</td>
							<td>:</td>
							<td><?php echo "$SPLCODE - $SPLDESC"; ?></td>
							<td width="30">NO.</td>
							<td>:</td>
							<td width="120"><?php echo $TTK_CODE; ?></td>
						</tr>
						<tr>
							<td style="vertical-align: top;" width="170">Tagihan Untuk</td>
							<td style="vertical-align: top;">:</td>
							<td style="vertical-align: top;"><?php echo $TTK_NOTES; ?></td>
							<td width="30">Tgl.</td>
							<td>:</td>
							<td width="120"><?php echo date('d/m/Y', strtotime($TTK_DATE)); ?></td>
						</tr>
					</table>
				</div>

			</div>
			<div class="detail-content">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th rowspan="2">PROYEK</th>
							<th rowspan="2">NO. OP / SPK</th>
							<th rowspan="2">NO. KWITANSI / INVOICE</th>
							<th rowspan="2">TANGGAL KWITANSI</th>
							<th colspan="2">JUMLAH</th>
						</tr>
						<tr>
							<th width="10">Kwitansi</th>
							<th width="10">Tagihan</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$TOT_KWIT 	= 0;
							$TTK_GTOTAL = 0;
							$TOT_PUM 	= 0;
							$TOT_RET 	= 0;
							$TOT_PPN 	= 0;
							$TOT_PPH 	= 0;

							$s_00 		= "SELECT TTK_AMOUNT, TTK_AMOUNT_PPH, TTK_AMOUNT_DPB, TTK_AMOUNT_RET FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM'";
							$r_00 		= $this->db->query($s_00);
							foreach($r_00->result() as $rw_00):
								$TTK_GTOTAL	= $rw_00->TTK_AMOUNT;
								$TOT_PUM	= $rw_00->TTK_AMOUNT_DPB;
								$TOT_PPH	= $rw_00->TTK_AMOUNT_PPH;
								$TOT_RET	= $rw_00->TTK_AMOUNT_RET;
							endforeach;

							$REF_COLL 	= "";
							$n1 		= 0;
							$s_01 		= "SELECT DISTINCT TTK_REF2_CODE FROM tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM'";
							$r_01 		= $this->db->query($s_01);
							foreach($r_01->result() as $rw_01):
								$n1 		= $n1+1;
								if($n1 == 1)
									$REF_COLL	= $rw_01->TTK_REF2_CODE;
								else
									$REF_COLL	= $REF_COLL."<br>".$rw_01->TTK_REF2_CODE;
							endforeach;

							$s_02 	= "SELECT PRJCODE, TTKT_SPLINVDATE, TTKT_SPLINVNO, TTKT_SPLINVVAL, TTKT_AMOUNT, TTKT_TAXAMOUNT FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM' ";
							$r_02 	= $this->db->query($s_02);
							$tRow 	= $r_02->num_rows();
							if($r_02->num_rows() > 0)
							{
								$no = 0;
								foreach($r_02->result() as $rw_02):
									$no 				= $no + 1;
									$PRJCODE			= $rw_02->PRJCODE;
									$TTKT_SPLINVDATE	= $rw_02->TTKT_SPLINVDATE;
									$TTKT_SPLINVNO		= $rw_02->TTKT_SPLINVNO;
									$TTKT_SPLINVVAL		= $rw_02->TTKT_SPLINVVAL;
									$TTKT_AMOUNT		= $rw_02->TTKT_AMOUNT;
									$TTKT_TAXAMOUNT		= $rw_02->TTKT_TAXAMOUNT;

									$TOT_KWIT 			= $TOT_KWIT + $TTKT_SPLINVVAL;
									$TOT_PPN 			= $TOT_PPN + $TTKT_TAXAMOUNT;

									$PRJCODED 			= "";
									$REF_COLLD 			= "";
									$TTK_AMOUNTD 		= "";
									$ISROWSPAN 			= "";
									if($no == 1)
									{
										$ISROWSPAN 		= 'rowspan="'.$tRow.'"';
										$PRJCODED 		= $PRJCODE;
										$REF_COLLD 		= $REF_COLL;
										$TTK_AMOUNTD 	= number_format($TTK_AMOUNT,2);
									}
									if($no == 1)
									{
										?>
											<tr>
												<td width="50" style="text-align: center;" nowrap><?php echo $PRJCODED; ?></td>
												<td width="150" <?=$ISROWSPAN?> style="text-align: center;"><?php echo $REF_COLLD; ?></td>
												<td style="text-align: center;"><?php echo $TTKT_SPLINVNO; ?></td>
												<td width="100" style="text-align: center;"><?php echo $TTKT_SPLINVDATE; ?></td>
												<td width="150" style="text-align: right;"><?php echo number_format($TTKT_SPLINVVAL,2); ?></td>
												<td width="150" style="text-align: right;"><?php echo $TTK_AMOUNTD; ?></td>
											</tr>
										<?php
									}
									else if($no <= $tRow)
									{
										?>
											<tr>
												<td width="50" style="text-align: center;" nowrap><?php echo $PRJCODED; ?></td>
												<td style="text-align: center;"><?php echo $TTKT_SPLINVNO; ?></td>
												<td width="100" style="text-align: center;"><?php echo $TTKT_SPLINVDATE; ?></td>
												<td width="150" style="text-align: right;"><?php echo number_format($TTKT_SPLINVVAL,2); ?></td>
												<td width="150" style="text-align: right;"><?php echo $TTK_AMOUNTD; ?></td>
											</tr>
										<?php
									}
								endforeach;

								if($no < 10)
								{
									$lnRow = 10 - $no;
									for($i=1;$i<=$lnRow;$i++) {
										?>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										<?php
									}
								}

								$TTK_GAMOUNT 	= $TTK_GTOTAL - $TOT_PUM - $TOT_RET + $TOT_PPN - $TOT_PPH;
								?>
									<tr style="font-weight: bold;">
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right;" nowrap>Total</td>
										<td width="150" style="text-align: right;"><?php echo number_format($TOT_KWIT,2); ?></td>
										<td width="150" style="text-align: right;"><?php echo number_format($TTK_GTOTAL,2); ?></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right;" nowrap>Uang Muka</td>
										<td>&nbsp;</td>
										<td width="150" style="text-align: right;"><?php echo number_format($TOT_PUM,2); ?></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right;" nowrap>Retensi</td>
										<td>&nbsp;</td>
										<td width="150" style="text-align: right;"><?php echo number_format($TOT_RET,2); ?></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right;">PPN</td>
										<td>&nbsp;</td>
										<td width="150" style="text-align: right;"><?php echo number_format($TOT_PPN,2); ?></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right;">PPH</td>
										<td>&nbsp;</td>
										<td width="150" style="text-align: right;"><?php echo number_format($TOT_PPH,2); ?></td>
									</tr>
									<tr style="font-weight: bold;">
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right;" nowrap>Grand Total</td>
										<td width="150" style="text-align: right;"><?php echo number_format($TOT_KWIT,2); ?></td>
										<td width="150" style="text-align: right;"><?php echo number_format($TTK_GAMOUNT,2); ?></td>
									</tr>
									<tr id="tfoot">
										<td colspan="6">Terbilang : <?php echo ucwords(terbilang(round($TTK_GAMOUNT,2))); ?> Rupiah</td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="detail-asign">
				<div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" style="text-align: center;">Mengetahui (PT. NKE)</td>
						</tr>
						<tr style="height: 50px;">
							<td>&nbsp;</td>
							<td width="100">Tgl.</td>
						</tr>
						<tr>
							<td colspan="2">Nama :</td>
						</tr>
					</table>
				</div>
				<div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" style="text-align: center;">Yang menerima (PT. NKE)</td>
						</tr>
						<tr style="height: 50px;">
							<td>&nbsp;</td>
							<td width="100">Tgl.</td>
						</tr>
						<tr>
							<td colspan="2">Nama :</td>
						</tr>
					</table>
				</div>
				<div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" style="text-align: center;">Yang menyerahkan (Pemasok)</td>
						</tr>
						<tr style="height: 50px;">
							<td>&nbsp;</td>
							<td width="100">Tgl.</td>
						</tr>
						<tr>
							<td colspan="2">Nama :</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="detail-notes">
				<span>CATATAN</span>
				<ul class="dash" style="padding-left: 15px;">
					<li>TTK INI DITERBITKAN JIKA BERKAS TAGIHAN LENGKAP DAN BENAR</li>
					<li>TTK INI DISERAHKAN DAN DITANDA TANGANI OLEH PIHAK YANG BERWENANG</li>
					<li>TANDA TANGAN HARUS LENGKAP DENGAN NAMA DAN TANGGAL</li>
					<li>TTK INI TIDAK BERLAKU LAGI, JIKA DANA PEMBAYARAN SUDAH EFEKTIF PADA REKENING PEMASOK</li>
				</ul>
			</div>
		</div>
		<div class="footnotes">
			<div>&copy;PT NUSA KONSTRUKSI ENJINIRING Tbk, Indonesia</div>
			<div>File : FRM.NKE 13.20.xls, Auth : ES, KAR.</div>
		</div>
	</section>
</body>
</html>
<script>
	<?php
		if(isset($this->session->userdata['vers']))
			$vers  = $this->session->userdata['vers'];
		else
			$vers  = '2.0.5';
		$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
		$rescss = $this->db->query($sqlcss)->result();
		foreach($rescss as $rowcss) :
			$cssjs_lnk1  = $rowcss->cssjs_lnk;
			?>
				<script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
			<?php
		endforeach;
	?>
</script>