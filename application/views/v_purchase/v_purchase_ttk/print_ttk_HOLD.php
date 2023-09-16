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

	class textFormat
	{ 
		public function rupiah ($angka) 
		{
			$rupiah = number_format($angka ,2, ',' , '.' );
			return $rupiah;
		}
	
		public function terbilang ($angka)
		{
			$angka = (float)$angka;
			$bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
			if ($angka < 12) {
				return $bilangan[$angka];
			} else if ($angka < 20) {
				return $bilangan[$angka - 10] . ' Belas';
			} else if ($angka < 100) {
				$hasil_bagi = (int)($angka / 10);
				$hasil_mod = $angka % 10;
				return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
			} else if ($angka < 200) {
				return sprintf('Seratus %s', $this->terbilang($angka - 100));
			} else if ($angka < 1000) {
				$hasil_bagi = (int)($angka / 100);
				$hasil_mod = $angka % 100;
				return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
			} else if ($angka < 2000) {
				return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
			} else if ($angka < 1000000) {
				$hasil_bagi = (int)($angka / 1000); 
				$hasil_mod = $angka % 1000;
				return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
			} else if ($angka < 1000000000) {
				$hasil_bagi = (int)($angka / 1000000);
				$hasil_mod = $angka % 1000000;
				return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
			} else if ($angka < 1000000000000) {
				$hasil_bagi = (int)($angka / 1000000000);
				$hasil_mod = fmod($angka, 1000000000);
				return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
			} else if ($angka < 1000000000000000) {
				$hasil_bagi = $angka / 1000000000000;
				$hasil_mod = fmod($angka, 1000000000000);
				return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
			} else {
				return 'Data Salah';
			}
		}
	}
	$textFormat = new textFormat();

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
		 body { margin: 0; font-size: 8pt; }
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

		/* The container */
		.container-check {
			display: inline-block;
			font-weight: normal;
			/* width: 60px; */
			position: relative;
			/* padding-top: 2px; */
			padding-left: 28px;
			margin-left: -3px;
			/* padding-right: 10px; */
			/* margin-bottom: 6px; */
			cursor: pointer;
			font-size: 8pt;
			-webkit-user-select: none !important;
			-moz-user-select: none !important;
			-ms-user-select: none !important;
			user-select: none !important;
		}

		.container-check.list {
			display: block;
			left: 15px;
			font-size: 9pt;
		}

		.container-check.list > div:first-child {
			font-size: 9pt;
			margin-top: -5px;
		}
		.container-check.list > div:nth-child(2) {
			font-size: 6pt;
			margin-top: -5px;
			font-style: italic;
			color: red !important;
		}

		/* Hide the browser's default checkbox */
		.container-check input {
			position: absolute;
			opacity: 0;
			cursor: pointer;
			height: 0;
			width: 0;
		}

		/* Create a custom checkbox */
		.checkmark {
			position: absolute;
			top: 0;
			left: 2px;
			margin-top: 2px;
			margin-left: 13px;
			height: 10px;
			width: 10px;
			/*background-color: #eee;*/
			-webkit-box-shadow: 0px 0px 0px 1px rgba(0,0,0,1) !important;
			-moz-box-shadow: 0px 0px 0px 1px rgba(0,0,0,1) !important;
			box-shadow: 0px 0px 0px 1px rgba(0,0,0,1);
		}

		/* On mouse-over, add a grey background color */
		.container-check:hover input ~ .checkmark {
			background-color: #ccc;
		}

		/* When the checkbox is checked, add a blue background 
		.container-check input:checked ~ .checkmark {
			background-color: #2196F3;
		}
		--------------------------------------------------- */

		/* Create the checkmark/indicator (hidden when not checked) */
		.checkmark:after {
			content: "";
			position: absolute;
			display: none;
		}

		/* Show the checkmark when checked */
		.container-check input:checked ~ .checkmark:after {
			display: block;
		}

		/* Style the checkmark/indicator */
		.container-check .checkmark:after {
			left: 2px;
			top: -1px;
			width: 5px;
			height: 9px;
			border: solid black !important;
			border-width: 0 2px 2px 0 !important;
			-webkit-transform: rotate(45deg) !important;
			-ms-transform: rotate(45deg) !important;
			transform: rotate(45deg) !important;
		}

		.flex-container {
			display: flex;
			flex-wrap: wrap;
		}

		.flex-container > div.logo {
			width: 150px;
			margin: auto 2px;
		}

		.flex-container > div.logo img {
			width: 150px;
		}

		.flex-container > div.title {
			width: 450px;
			text-align: center;
		}

		.flex-container > div.title > div:first-child {
			font-size: 16pt;
			font-weight: bold;
			text-align: center;
		}

		.flex-container > div.title > div:last-child {
			font-size: 12pt;
			font-weight: bold;
			text-align: center;
		}

		.flex-container > div.frmDoc {
			width: 150px;
			font-size: 8pt;
		}

		.flex-container > div.header-doc {
			width: 100%;
		}

		.flex-container > div.detail-content {
			width: 100%;
		}

		.flex-container > div.detail-content table td {
			border: 1px solid;
			padding: 2px;
			vertical-align: top;
		}

		.flex-container > div.footer-asign {
			display: flex;
			width: 100%;
			border: 1px solid;
			border-top: hidden;
		}

		.footer-asign > div.asign-1 {
			width: 25%;
			text-align: center;
		}
		.footer-asign > div.asign-1 table td {
			font-weight: bold;
		}
		.footer-asign > div.asign-1 > div {
			border-bottom: 1px solid;
			border-right: 1px solid;
		}
		.footer-asign > div.asign-2 {
			width: 25%;
			text-align: center;
		}
		.footer-asign > div.asign-2 > div {
			border-bottom: 1px solid;
			border-right: 1px solid;
		}
		.footer-asign > div.asign-2 table td {
			text-align: left;
			font-weight: bold;
		}

		.footer-asign > div.asign-3 {
			width: 25%;
			text-align: center;
		}
		.footer-asign > div.asign-3 > div {
			border-bottom: 1px solid;
			border-right: 1px solid;
		}
		.footer-asign > div.asign-3 table td {
			border: hidden;
			font-weight: bold;
		}

		.footer-asign > div.asign-4 {
			width: 25%;
			text-align: center;
		}
		.footer-asign > div.asign-4 > div {
			border-bottom: 1px solid;
		}
		.footer-asign > div.asign-4 table td {
			border: hidden;
			font-weight: bold;
		}
	</style>
</head>
<body class="page A4">
	<section class="page sheet custom">
		<div class="flex-container">
			<div class="logo"><img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg"></div>
			<div class="title">
				<div>Checklist Periksa Tagihan (CPT)</div>
				<div>Departemen Corporate Finance</div>
			</div>
			<div class="frmDoc">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="50">Dok. No.</td>
						<td>:</td>
						<td>&nbsp;FRM.NKE.13.27</td>
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
			<div style="text-align: center; width: 100%;">Catatan:[1] Checklist ini harus dilampirkan pada Voucher, [2] Beri tanda &#10003; pada <i class="fa fa-square-o"></i> (&#9745;), [3]*Coret / Hilangkan jika tidak perlu</div>
			<div class="header-doc" style="padding-top: 5px; font-weight: bold;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="120">No. Voucher</td>
						<td>:</td>
						<td></td>
					</tr>
					<tr>
						<td width="120">No. OP/SPK*</td>
						<td>:</td>
						<td></td>
					</tr>
					<tr>
						<td width="120">Pembayaran ke-</td>
						<td>:</td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="detail-content">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="text-align: center; font-weight: bold; letter-spacing: 5px;">Checklist dokumen:</td>
					</tr>
					<tr>
						<td width="250" style="text-align: center; font-weight: bold; letter-spacing: 2px;">UMUM</td>
						<td width="250" style="text-align: center; font-weight: bold; letter-spacing: 2px;">BARANG</td>
						<td style="text-align: center; font-weight: bold; letter-spacing: 2px;">PEK. PEMBORONGAN / SEWA ALAT</td>
					</tr>
					<tr>
						<td>
							<div>
								<div style="font-weight: bold;">1. OP/ SPK/ Kontrak & Add. Kontrak*</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCTRX" id="DOCTRX_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCTRX" id="DOCTRX_0" value="0">
									<span class="checkmark"></span>
								</label>
								<div style="font-weight: bold;">2. Jaminan (UM / PEL.) (sesuai kontrak)*</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<div style="font-weight: bold;">3. Surat Jalan (SJ)/ Opn. Pek./ Sewa Alat*</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Lokasi penerimaan/pekerjaan NKE*</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Deskripsi Produk/Jasa benar*</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Volume sesuai dgn diterima/dikerjakan</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Vol. diterima/dikerjakan &le; OP / SPK</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Ttd & Cap Penyedia Eksternal & NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Tgl terima Opn. > Tgl SPK*, alasan: ....</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Tgl terima SJ &le; Tgl OP*, alasan: ....</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>

								<div style="font-weight: bold;">4. Pembayaran dimuka (sewa alat)*</div>
								<label class="container-check">Tidak
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Ya, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>

								<div style="font-weight: bold;">5. BA Siap Operasi /Selesai Operasi (sw alat)*</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>

								<div style="font-weight: bold;">6. BA Pembayaran Pek. / Pemakaian Alat (sewa alat)*</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>

								<div style="font-weight: bold;">7. Retensi*</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							</div>
						</td>
						<td>
							<!-------------------------------START Kuitansi (RCP) --------------------------->
								<div style="font-weight: bold;">Kuitansi (RCP)</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nama NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Desk. barang sesuai dengan OP</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Materai memadai</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nilai RCP & terbilang sesuai INV</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Ttd &/ Cap* Penyedia Eksternal</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Bulan RCP sesuai SJ & FP (tgl)</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							<!-------------------------------END Kuitansi (RCP) --------------------------->

							<!-------------------------------START Invoice (INV) --------------------------->
								<div style="font-weight: bold;">Invoice (INV) *bila ada</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nama & Alamat NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Desk. barang sesuai dengan OP</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Qty sesuai dengan Surat Jalan</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Harga Satuan sesuai OP/Kontrak*</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Perhit. nilai tot. INV & terbilang benar</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Bulan INV sesuai SJ & FP (tgl)</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							<!-------------------------------END Invoice (INV) --------------------------->

							<!-------------------------------START Faktur Pajak (FP) --------------------------->
								<div style="font-weight: bold;">Faktur Pajak (FP) *bila ada</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nama, Alamat & NPWP NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Desk. barang sesuai INV</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Qty sesuai dengan total vol. SJ</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Perhit. FP & terbilang benar</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Bulan FP sesuai SJ & INV (tgl)</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							<!-------------------------------END Faktur Pajak (FP) --------------------------->
						</td>
						<td>
							<!-------------------------------START Kuitansi (RCP) --------------------------->
								<div style="font-weight: bold;">Kuitansi (RCP)</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nama NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Desk. Pek. sesuai dengan Opn. Pek.</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Materai memadai</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nilai RCP & terbilang sesuai INV</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Ttd &/ Cap* Penyedia Eksternal</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Bulan RCP sesuai Opn. & FP (tgl)</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							<!-------------------------------END Kuitansi (RCP) --------------------------->

							<!-------------------------------START Invoice (INV) --------------------------->
								<div style="font-weight: bold;">Invoice (INV) *bila ada</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nama & Alamat NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Desk. Pek. sesuai dengan Opname</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Vol. Pek. sesuai dengan Opname</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Hrg. Satuan sesuai SPK / Kontrak*</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Perhit. nilai tot. INV & terbilang benar</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Bulan INV sesuai Opn. & FP (tgl)</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							<!-------------------------------END Invoice (INV) --------------------------->

							<!-------------------------------START Faktur Pajak (FP) --------------------------->
								<div style="font-weight: bold;">Faktur Pajak (FP) *bila ada</div>
								<label class="container-check">Ada
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_1" value="1">
									<span class="checkmark"></span>
								</label>
								<label class="container-check">Tidak, alasan: 
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Nama, Alamat & NPWP NKE</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Desk. Pek. sesuai INV</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Qty sesuai dengan total vol. Opn. Pek.</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Perhit. FP & terbilang benar</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
								<label class="container-check list">
									<div>Bulan FP sesuai Opn. & INV (tgl)</div>
									<div>Jika tidak sesuai / salah data, kembalikan ke penyedia</div>
									<input type="checkbox" name="DOCGUARANT" id="DOCGUARANT_0" value="0">
									<span class="checkmark"></span>
								</label>
							<!-------------------------------END Faktur Pajak (FP) --------------------------->
						</td>
					</tr>
				</table>
			</div>
			<div class="footer-asign">
				<div class="asign-1">
					<div>DITERBITKAN</div>
					<div style="border-bottom: hidden;">
						Jurnal
					</div>
				</div>
				<div class="asign-2">
					<div>DIPERIKSA</div>
					<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="100">Oleh</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Tanggal</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Paraf</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Untuk direvisi oleh</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="asign-3">
					<div>DIPERIKSA (Rev1)*</div>
					<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="100">Oleh</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Tanggal</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Paraf</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Untuk direvisi oleh</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="asign-4">
					<div>DIPERIKSA (Rev2)*</div>
					<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="100">Oleh</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Tanggal</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Paraf</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width="100">Untuk direvisi oleh</td>
								<td width="10">:</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
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