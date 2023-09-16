<?php
	class moneyFormat
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

	$moneyFormat = new moneyFormat();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	<link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/contract.png'; ?>" sizes="32x32">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        @page { margin: 0 }
        body { margin: 0 }
        .sheet {
          margin: 0;
          overflow: hidden;
          position: relative;
          box-sizing: border-box;
          page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; height: 419mm }
        body.A3.landscape     .sheet { width: 420mm; height: 296mm }
        body.A4               .sheet { width: 210mm; height: 296mm }
        body.A4.landscape     .sheet { width: 297mm; height: 209mm }
        body.A5               .sheet { width: 148mm; height: 209mm }
        body.A5.landscape     .sheet { width: 210mm; height: 147mm }
        body.letter           .sheet { width: 216mm; height: 279mm }
        body.letter.landscape .sheet { width: 280mm; height: 215mm }
        body.legal            .sheet { width: 216mm; height: 356mm }
        body.legal.landscape  .sheet { width: 357mm; height: 215mm }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 1cm 1cm 0.97cm 1cm }

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
            position: relative;
            /*border: 2px solid;*/
            font-family: "Arial";
            font-size: 8pt;
        }
        .box-header {
            position: relative;
            width: 100%;
            height: 70px;
            padding: 5px;
            /*border-bottom: 2px solid;*/
            /*background-color: gold;*/
        }
        .box-header .box-column-logo {
            float: left;
            width: 20%;
            /*border: 1px solid;*/
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 5cm;
        }
        .box-header .box-column-title {
            position: absolute;
            top: 10px;
            float: left;
            width: 100%;
            height: 50px;
            padding-top: 15px;
            /*border: 1px solid;*/
            text-align: center;
            /*background-color: green;*/
        }
        .box-header .box-column-title > span {
            font-size: 12pt;
            font-weight: bold;
        }
        .box-header-detail table td {
        	border: hidden;
        }
        .box-detail {
        	width: 100%;
        	margin-top: 10px;
        }
        .box-detail table thead th, tbody td, tfoot td {
        	border-top: 1px dashed rgba(140, 145, 141, 0.3);
        	border-bottom: 1px dashed rgba(140, 145, 141, 0.3);
        	padding: 3px;
        	font-family: Courier, monospace;
        }
        .box-detail table thead th {
        	text-align: center;
        }
        #Layer1 {
        	position: absolute;
        	top: 10px;
        	left: 730px;
        }
    </style>
</head>
<body class="page A4">
	<section class="page sheet custom">
		<div class="cont">
			<div class="box-header">
				<div class="box-column-title">
                    <span><?php echo "History Pembayaran Voucher"; ?></span>
                </div>
			</div>
			<div class="box-header-detail">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="150">Kode</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo $TLK_CODE; ?></td>
					</tr>
					<tr>
						<td width="150">Tanggal</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo date('d-m-Y', strtotime($TLK_DATE)); ?></td>
					</tr>
					<tr>
						<td width="150">Jumlah Voucher Transfer LK</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo number_format($TLK_AMOUNT, 2); ?></td>
					</tr>
					<tr>
						<td width="150">Catatan</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo $TLK_DESC; ?></td>
					</tr>
				</table>
			</div>
			<div class="box-detail">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="50">No</th>
							<th width="100">KODE REALISASI</th>
							<th width="80" style="word-wrap: break-word;">TGL. REALISASI</th>
							<th width="80">AKUN</th>
							<th>DESKRIPSI</th>
							<th width="100">NOMINAL</th>
							<th width="100">SISA REALISASI</th>
						</tr>
					</thead>
					<tbody>
						<?php  
							// get pay history
							$this->db->select("A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.JOBCODEID,
												A.JournalD_Debet, A.JournalD_Kredit, A.isDirect, A.Notes,
												A.Ref_Number, A.Other_Desc, A.Journal_DK, B.Manual_No, B.JournalH_Desc");
							$this->db->from("tbl_journaldetail A");
							$this->db->join("tbl_journalheader B", "B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code", "INNER");
							$this->db->where(["A.Ref_Number" => $TLK_NUM, 
											  "A.Journal_DK" => 'K', 
											  "A.proj_Code" => $PRJCODE]);
							$getTLKHist = $this->db->get();
							if($getTLKHist->num_rows() > 0)
							{
								$no = 0;
								$Tot_AmountV = 0;
								foreach($getTLKHist->result() as $rTLK):
									$no 			= $no + 1;
									$JournalH_Code 		= $rTLK->JournalH_Code;
									$JournalH_Date 		= $rTLK->JournalH_Date;
									$Manual_No 			= $rTLK->Manual_No;
									$JournalH_Desc 		= $rTLK->JournalH_Desc;
									$Acc_Id 			= $rTLK->Acc_Id;
									$JOBCODEID 			= $rTLK->JOBCODEID;
									$JournalD_Debet 	= $rTLK->JournalD_Debet;
									$JournalD_Kredit 	= $rTLK->JournalD_Kredit;
									$isDirect 			= $rTLK->isDirect;
									$Notes 				= $rTLK->Notes;

									$Ref_Number 		= $rTLK->Ref_Number;
									$Other_Desc 		= $rTLK->Other_Desc;
									$Journal_DK 		= $rTLK->Journal_DK;
									
									if($Journal_DK == 'D')
									{
										$AmountV		= $JournalD_Debet;
										$Tot_AmountV 	= $Tot_AmountV + $AmountV;
									}
									else
									{
										$AmountV		= $JournalD_Kredit;
										$Tot_AmountV 	= $Tot_AmountV + $AmountV;
									}

									$TLK_REMAM 	= $TLK_AMOUNT - $Tot_AmountV;
									?>
										<tr>
											<td style="text-align: center;"><?php echo $no; ?></td>
											<td style="text-align: center;"><?php echo $Manual_No; ?></td>
											<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
											<td style="text-align: center;"><?php echo $Acc_Id; ?></td>
											<td style="text-align: left; padding-left: 15px;"><?php echo $JournalH_Desc; ?></td>
											<td style="text-align: right;"><?php echo number_format($AmountV, 2); ?>&nbsp;</td>
											<td style="text-align: right;"><?php echo number_format($TLK_REMAM, 2); ?>&nbsp;</td>
										</tr>
									<?php
								endforeach;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
	</section>
</body>
</html>
<script type="text/javascript">
	document.addEventListener("keydown", function (event) {
		console.log(event);
	    if (event.ctrlKey) {
	        event.preventDefault();
	        // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
	    }   
	});
</script>