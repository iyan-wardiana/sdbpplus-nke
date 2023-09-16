<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
		body { margin: 0 }
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
		.sheet.custom { padding: 0.48cm 0.38cm 0.97cm 0.5cm }

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
		  @page { size: a4;}
		  body.A3.landscape { width: 420mm }
		  body.A3, body.A4.landscape { width: 297mm }
		  body.A4, body.A5.landscape { width: 210mm }
		  body.A5                    { width: 148mm }
		  body.letter, body.legal    { width: 216mm }
		  body.letter.landscape      { width: 280mm }
		  body.legal.landscape       { width: 357mm }
		}

		.cont {
			border: 1px solid;
			padding: 1px;
			position: relative;
		}
		.box-header {
			border: 1px solid;
			height: 50px;
		}
		.box-header .logo {
			float: left;
			border-right: 1px solid;
			width: 100px;
			height: 48px;
		}
		.box-header .logo img {
			margin: 15px auto;
			padding-left: 5px;
			width: 90px;
		}
		.box-header .text-header-title {
			float: left;
			border-right: 1px solid;
			border-bottom: 1px solid;
			width: 310px;
			height: 25px;
			font-family: Impact;
			font-size: 10pt;
			text-align: center;
			padding-top: 4px;
			box-sizing: border-box;
		}
		.box-header .text-header-no {
			float: left;
			font-family: Impact;
			font-size: 7pt;
			padding-left: 5px;
			padding-top: 5px;
			border-bottom: 1px solid;
			width: 110px;
			height: 25px;
			box-sizing: border-box;
		}
		.box-header .box-note1 {
			float: left;
			border-left: 1px solid;
			border-right: 1px solid;
			width: 120px;
			height: 48px;
			box-sizing: border-box;
			font-family: "Arial Narrow", Arial, sans-serif;
			font-size: 5pt;
			font-weight: bold;
			padding-left: 5px;
		}
		table {
			border: 1px;
			border-collapse: collapse;
		}
		.box-header .box-note1 table {
			margin: 5px auto;
		}
		.box-header .box-note2 {
			float: left;
			width: 110px;
			height: 50px;
			box-sizing: border-box;
			font-family: "Arial Narrow", Arial, sans-serif;
			font-size: 5pt;
			font-weight: bold;
			padding-left: 5px;
		}
		.box-header .box-note2 table {
			margin: 5px auto;
			padding-left: 5PX;
		}
		.box-header .box-proyek {
			position: absolute;
			top: 26px;
			left: 110px;
			width: 250px;
			height: 24px;
			border-right: 1px solid;
			font-family: Impact;
			font-size: 7pt;
		}
		.box-header .box-proyek span {
			display: inline-block;
			padding-top: 7px;
			padding-left: 5px;
		}
		.box-header .box-code {
			position: absolute;
			font-family: Impact;
			font-size: 7pt;
			top: 26px;
			left: 360px;
		}
		.box-header .box-code span {
			display: inline-block;
			padding-top: 7px;
			padding-left: 5px;
		}
		.box-notes {
			border: 1px solid;
			margin-top: 1px;
			font-family: "Arial Black";
			font-size: 7pt;
			padding-left: 5px;
			padding-top: 5px;
		}
		.box-notes p {
			font-family: "Arial";
		}
		.content-table {
			margin-top: 1px;
		}
		.content-table table {
			border: 1px solid;
		}
		.content-table thead {
			border: 1px solid;
			margin-top: 1px;
			font-family: "Arial";
			font-size: 6pt;
			letter-spacing: 2px;
			font-weight: bold;
		}
		.content-table thead th {
			border: 1px solid;
			padding: 2px;
			text-align: center;
		}
		.content-table tbody {
			font-family: "Arial";
			font-size: 7pt;
		}
		.content-table .line-spacing {
			line-height: 1px;
			border-left: hidden;
			border-right: hidden;
			padding: 0;
		}
		.content-table .content-padding td {
			border-bottom-style: dashed; 
			border-bottom-width: 1px; 
			border-bottom-color: rgba(140, 145, 141, 0.3);
			border-left: 1px solid;
			padding: 3px;
		}
		.blank-line {
			border-bottom-style: dashed; 
			border-bottom-width: 1px; 
			border-bottom-color: rgba(140, 145, 141, 0.3);
		}
		.blank-line td {
			border-left: 1px solid;
		}
		.box-asign {
			position: relative;
			margin-top: 1px;
		}
		.box-asign .asign-top {
			display: block;
			margin-top: 5px;
			margin-bottom: 5px;
			font-family: "Arial";
			font-size: 6pt;
			font-weight: bold;
		}
		.box-asign .asign-center {
			display: block;
			margin: 35px;
		}
		.box-asign .asign-bottom {
			top: 100px;
			position: absolute;
			display: block;
			border-top-style: dashed; 
			border-top-width: 1px; 
			border-top-color: rgba(140, 145, 141, 0.3);
			border-bottom-style: dashed; 
			border-bottom-width: 1px; 
			border-bottom-color: rgba(140, 145, 141, 0.3);
			padding: 5px;
			font-family: "Arial";
			font-size: 5pt;
			font-style: italic;
			text-align: left;
		}
		.box-asign .asign-bottom#asign-nama {
			margin-top: 7.5px;
			width: 110px;
		}
		.box-asign .asign-bottom#asign-tanggal {
			margin-top: 28px;
			width: 110px;
		}
		.copy-right {
			font-family: "Arial";
			font-size: 5pt;
			font-weight: bold;
			font-style: italic;
			margin-top: 5px;
		}
		.copy-right #company {
			float: left;
		}
		.copy-right #text-file {
			float: right;
		}
		#Layer1 {
			margin-top: 20px;
		}
	</style>

	<?php
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
   			$hasil = "minus ".trim(konversi(x));
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


	$PR_NUM		= $default['PR_NUM'];
	$PR_CODE	= $default['PR_CODE'];
	$PR_DATE	= $default['PR_DATE'];
	$PRJCODE	= $default['PRJCODE'];
	$PR_NOTE	= $default['PR_NOTE'];
	$PR_STAT	= $default['PR_STAT'];
	$PR_PLAN_IR	= $default['PR_PLAN_IR'];

	$PR_DATEV	= date('d M Y', strtotime($PR_DATE));
	$PR_PLAN_IRV= date('d M Y', strtotime($PR_PLAN_IR));
	$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resPRJ		= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJNAME= $rowPRJ->PRJNAME;
	endforeach;
	?>

</head>
<body class="page A4">
	<section class="page sheet custom">
		<div class="cont">
			<div class="box-header">
				<div class="logo">
					<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
				</div>
				<div class="text-header-title">SURAT PERMINTAAN PENGADAAN / PENYERAHAN (SPP/P)</div>
				<div class="text-header-no">No.: <span><?=$PR_CODE?></span></div>
				<div class="box-note1">
					<table width="100%" cellpadding="0" cellspacing="2" border="0">
						<tr>
							<td>PUTIH</td>
							<td>:</td>
							<td>LOG./ BAG.PENGADAAN</td>
						</tr>
						<tr>
							<td>MERAH</td>
							<td>:</td>
							<td>P E L A K S A N A</td>
						</tr>
						<tr>
							<td>KUNING</td>
							<td>:</td>
							<td>COST CONT. PROYEK</td>
						</tr>
						<tr>
							<td>HIJAU</td>
							<td>:</td>
							<td>COMPTROLLER PUSAT</td>
						</tr>
					</table>
				</div>
				<div class="box-note2">
					<table width="100%" cellpadding="0" cellspacing="2" border="0">
						<tr>
							<td>NO. DOK.</td>
							<td>:</td>
							<td>FRM.NKE.07.26</td>
						</tr>
						<tr>
							<td>REVISI</td>
							<td>:</td>
							<td>(06/02/20)</td>
						</tr>
						<tr>
							<td>AMAND.</td>
							<td>:</td>
							<td>-</td>
						</tr>
					</table>
				</div>
				<div class="box-proyek"><span>PROYEK / BAGIAN *)  : <?php echo "$PRJCODE - $PRJNAME"; ?></span></div>
				<div class="box-code"><span>KODE ITEM :</span></div>
			</div>
			<div class="box-notes">
				C A T A T A N  :
				<p><b>1.</b>SATU LEMBAR SPP/P  HANYA DIGUNAKAN UNTUK SATU ITEM BUDGET,   <b>2.</b> SPP/P DIAJUKAN KE COMPTROLLER  PUSAT PALING LAMBAT 10 HARI SEBELUM TANGGAL SCHEDULE BARANG TIBA DAN TERLAMPIR  CSPB (CONTROL STATUS PENGADAAN BARANG), <b>3.</b> KHUSUS UNTUK PENGADAAN ALAT, RENCANA PERIODE WAKTU PEMAKAIAN ALAT AGAR DITULISKAN DALAM KOLOM KETERANGAN,   <b>4.</b> SESUAI PERSYARATAN SMK3, PP NO.50 TAHUN 2012,  <b>5.</b> *) : CORET YANG TIDAK PERLU ! </p>
			</div>
			<div class="content-table">
				<table width="100%">
					<thead>
						<tr>
							<th rowspan="2" width="100">NAMA BARANG</th>
							<th rowspan="2" width="35">UKURAN</th>
							<th colspan="2" width="150">JUMLAH  KEBUTUHAN</th>
							<th rowspan="2" width="30">SATUAN</th>
							<th rowspan="2" width="80">SCHEDULE BARANG TIBA (Tanggal)</th>
							<th rowspan="2" width="80">KODE POS PEK.</th>
							<th rowspan="2">KETERANGAN  (PEKERJAAN)</th>
							<th colspan="2">DIISI  BAG. PENGADAAN</th>
						</tr>
						<tr>
							<th width="30">ANGKA</th>
							<th width="70">HURUF</th>
							<th>PEMASOK / SUPPLIER</th>
							<th>NO. OP/ SPK Sewa Alat*)</th>
						</tr>
					</thead>
					<tbody>
						<tr class="line-spacing">
							<td colspan="10">&nbsp;</td>
						</tr>
						<?php
							$this->db->select('A.PR_ID, A.PR_NUM, A.ITM_CODE, A.PR_VOLM, A.ITM_UNIT, A.JOBCODEID, 
											   A.JOBPARDESC, A.PR_DESC, B.PR_RECEIPTD, B.SPLCODE, C.ITM_NAME');
							$this->db->from('tbl_pr_detail A');
							$this->db->join('tbl_pr_header B','B.PR_NUM = A.PR_NUM AND B.PRJCODE = A.PRJCODE','INNER');
							$this->db->join('tbl_item C','C.ITM_CODE = A.ITM_CODE AND C.PRJCODE = A.PRJCODE','INNER');
							$this->db->where(['A.PR_NUM' => $PR_NUM, 'A.PRJCODE']);
							$query = $this->db->get();
							if($query->num_rows() > 0)
							{
								$no = 0;
								foreach($query->result() as $rPR):
									$no 			= $no + 1;
									$PR_ID 			= $rPR->PR_ID;
									$PR_NUM 		= $rPR->PR_NUM;
									$PR_DESC 		= $rPR->PR_DESC;
									$ITM_CODE 		= $rPR->ITM_CODE;
									$ITM_NAME 		= $rPR->ITM_NAME;
									$PR_VOLM 		= $rPR->PR_VOLM;
									$ITM_UNIT 		= $rPR->ITM_UNIT;
									$PR_RECEIPTD	= date('d M Y', strtotime($rPR->PR_RECEIPTD));
									$JOBCODEID 		= $rPR->JOBCODEID;
									$JOBPARDESC 	= $rPR->JOBPARDESC;
									$SPLCODE 		= $rPR->SPLCODE;

									?>
										<tr class="content-padding">
											<td><?php echo $ITM_NAME; ?></td>
											<td style="text-align: left;"><?php echo $PR_DESC; ?></td>
											<td style="text-align: right"><?php echo number_format($PR_VOLM,2); ?></td>
											<td style="text-align: left;"><?php echo terbilang($PR_VOLM); ?></td>
											<td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
											<td style="text-align: center;"><?php echo $PR_RECEIPTD; ?></td>
											<td style="text-align: center;"><?php echo $JOBCODEID; ?></td>
											<td style="text-align: left;"><?php echo $JOBPARDESC; ?></td>
											<td style="text-align:left"><?php echo $SPLCODE; ?></td>
											<td style="text-align: center;">&nbsp;</td>
										</tr>
									<?php
								endforeach;
									if($no <= 20)
									{
										$amRow = 20 - $no;
										for($i=0;$i<$amRow;$i++)
										{
											?>
												<tr class="blank-line">
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
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
							}
						?>
						
					</tbody>
				</table>
			</div>
			<div class="box-asign">
				<table width="100%" border="1" rules="all">
					<tr>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :  </span>
							<span class="asign-top">PELAKSANA/ Koord. PELAKSANA *)</span>
							<span class="asign-top">&nbsp;</span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :  </span>
							<span class="asign-top">ENGINEERING</span>
							<span class="asign-top">&nbsp;</span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :  </span>
							<span class="asign-top">COST CONTROL PROYEK</span>
							<span class="asign-top">&nbsp;</span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :  </span>
							<span class="asign-top">MANAJER LAP. (SM)/ </span>
							<span class="asign-top"> …………………………….. *) </span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :  </span>
							<span class="asign-top">MANAGER PROY. (PM)/ </span>
							<span class="asign-top"> …………………………….. *) </span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :  </span>
							<span class="asign-top">COMPTROLLER  PUSAT</span>
							<span class="asign-top">&nbsp;</span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
						<td width="100" height="150" style="text-align: center; vertical-align: top;">
							<span class="asign-top">DIAJUKAN :</span>
							<span class="asign-top">DIREKTUR</span>
							<span class="asign-top">&nbsp;</span>
							<span class="asign-center"></span>
							<span class="asign-bottom" id="asign-nama">NAMA :</span>
							<span class="asign-bottom" id="asign-tanggal">TANGGAL :</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="copy-right">
			<div id="company">®HakCipta PT. NUSA KONSTRUKSI ENJINIRING TBK</div>
			<div id="text-file">FILE: FRM.NKE.07.26.Xls, Auth :BES,</div>
			<div class="cf"></div>
			<div id="why" style="box-sizing: content-box;">WHY</div>
		</div>
		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
	</section>
</body>
</html>