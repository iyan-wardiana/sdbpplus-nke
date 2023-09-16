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

	// get supplier
	$SPLCODE = $PERSL_EMPID;
	$SPLDESC = '';
	$getSPL  = $this->db->distinct("SPLCODE, SPLDESC, SPLADD1")->where(["SPLCODE" => $PERSL_EMPID, "SPLSTAT" => 1])->get("tbl_supplier");
	if($getSPL->num_rows() > 0)
	{
		foreach($getSPL->result() as $rSPL):
			$SPLCODE = $rSPL->SPLCODE;
			$SPLDESC = $rSPL->SPLDESC;
			$SPLADD1 = $rSPL->SPLADD1;
		endforeach;
	}
	else
	{
		$getEmp = $this->db->select("Emp_ID, CONCAT(First_Name,' ',Last_Name) AS FullName", false)->from("tbl_employee")->where(["Emp_ID" => $PERSL_EMPID, "Employee_status" => 1])->get();
		if($getEmp->num_rows() > 0)
		{	
			foreach($getEmp->result() as $rSPL):
				$SPLCODE = $rSPL->Emp_ID;
				$SPLDESC = $rSPL->FullName;
			endforeach;
		}
	}
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
						<td>&nbsp;<?php echo $Manual_No; ?></td>
					</tr>
					<tr>
						<td width="150">Peminjam</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo "$SPLDESC - $SPLCODE"; ?></td>
					</tr>
					<tr>
						<td width="150">Tanggal</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
					</tr>
					<tr>
						<td width="150">Jumlah Pinj. Dinas</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo number_format($Journal_Amount, 2); ?></td>
					</tr>
					<tr>
						<td width="150">Catatan</td>
						<td width="5">:</td>
						<td>&nbsp;<?php echo $JournalH_Desc; ?></td>
					</tr>
				</table>
			</div>
			<div class="box-detail">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="50">No</th>
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
												A.JournalD_Debet, A.JournalD_Debet_tax,
												A.JournalD_Kredit, A.JournalD_Kredit_tax, A.isDirect, A.Notes, A.ITM_CODE,
												A.Ref_Number, A.Other_Desc, A.Journal_DK, A.isTax, A.ITM_CATEG, A.ITM_VOLM, 
												A.ITM_PRICE, A.ITM_UNIT, A.isExtra");
							$this->db->from("tbl_journaldetail_pd A");
							$this->db->where(["A.JournalH_Code" => $JournalH_Code, 
											  "A.Journal_DK" => 'D', 
											  "A.ISPERSL_STEP !=" => 0, 
											  "A.ISPERSL" => 1,
											  "A.proj_Code" => $PRJCODE]);
							$this->db->order_by("A.ISPERSL_STEP", "ASC");
							$getPDHist = $this->db->get();
							if($getPDHist->num_rows() > 0)
							{
								$no = 0;
								$Tot_AmountV = 0;
								foreach($getPDHist->result() as $rPD):
									$no 			= $no + 1;
									$JournalH_Code 		= $rPD->JournalH_Code;
									$JournalH_Date 		= $rPD->JournalH_Date;
									$Acc_Id 			= $rPD->Acc_Id;
									$JOBCODEID 			= $rPD->JOBCODEID;
									$JournalD_Debet 	= $rPD->JournalD_Debet;
									$JournalD_Debet_tax = $rPD->JournalD_Debet_tax;
									$JournalD_Kredit 	= $rPD->JournalD_Kredit;
									$JournalD_Kredit_tax= $rPD->JournalD_Kredit_tax;
									$isDirect 			= $rPD->isDirect;
									$Notes 				= $rPD->Notes;
									$ITM_CODE 			= $rPD->ITM_CODE;
									$ITM_CATEG 			= $rPD->ITM_CATEG;
									$ITM_VOLM 			= $rPD->ITM_VOLM;
									$ITM_PRICE 			= $rPD->ITM_PRICE;

									$sqlJOBD1			= "SELECT ITM_PRICE, JOBDESC, JOBPARENT
															FROM tbl_joblist_detail
															WHERE JOBCODEID = '$JOBCODEID'
																AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
									$resJOBD1			= $this->db->query($sqlJOBD1)->result();
									foreach($resJOBD1 as $rowJOBD1) :
										//$ITM_PRICE		= $rowJOBD1->ITM_PRICE;
										$JODBDESC		= $rowJOBD1->JOBDESC;
										$JOBPARENT		= $rowJOBD1->JOBPARENT;
									endforeach;

									$ITM_UNIT 			= $rPD->ITM_UNIT;
									$Ref_Number 		= $rPD->Ref_Number;
									$Other_Desc 		= $rPD->Other_Desc;
									$Journal_DK 		= $rPD->Journal_DK;
									$isTax 				= $rPD->isTax;
									$isExtra 			= $rPD->isExtra;
									
									$ITM_VOLMBG			= 0;
									$ITM_BUDG			= 0;
									$ITM_USED			= 0;
									$ITM_USED_AM		= 0;
									$sqlJOBD			= "SELECT ITM_VOLM AS ITM_VOLMBG, ITM_BUDG,
																ITM_USED, ITM_USED_AM
															FROM tbl_joblist_detail
															WHERE JOBCODEID = '$JOBCODEID'
																AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
									$resJOBD			= $this->db->query($sqlJOBD)->result();
									foreach($resJOBD as $rowJOBD) :
										$ITM_VOLMBG		= $rowJOBD->ITM_VOLMBG;
										$ITM_BUDG		= $rowJOBD->ITM_BUDG;
										$ITM_USED		= $rowJOBD->ITM_USED;
										$ITM_USED_AM	= $rowJOBD->ITM_USED_AM;
									endforeach;
									$BUDG_REMVOLM		= $ITM_VOLMBG - $ITM_USED;
									$BUDG_REMAMN		= $ITM_BUDG - $ITM_USED_AM;
									
									if($Journal_DK == 'D')
									{
										$AmountV		= $JournalD_Debet;
									}
									else
									{
										$AmountV		= $JournalD_Kredit;
									}
										
									if($isTax == 1)
									{
										if($Journal_DK == 'D')
										{
											$AmountV		= $JournalD_Debet_tax;
										}
										else
										{
											$AmountV		= $JournalD_Kredit_tax;
										}
										$isTaxD			= 'Tax';
									}
									else
									{
										$isTaxD			= 'No';
									}
									
									$ITM_NAME			= '';
									if($ITM_CODE != '')
									{
										$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
										$resITM 	= $this->db->query($sqlITM)->result();
										foreach($resITM as $rowITM) :
											$ITM_NAME 	= $rowITM->ITM_NAME;
										endforeach;
									}
									else
									{
										$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_Id'";
										$resITM 	= $this->db->query($sqlITM)->result();
										foreach($resITM as $rowITM) :
											$ITM_NAME 	= $rowITM->Account_NameId;
										endforeach;
									}
									
									// RESERVE
									$ITM_USEDR			= 0;
									$ITM_USEDR_AM		= 0;
									$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
															FROM tbl_journaldetail
															WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
																AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT_PD IN (2,7)
																AND JournalH_Code != '$JournalH_Code'";
									$resJOBDR			= $this->db->query($sqlJOBDR)->result();
									foreach($resJOBDR as $rowJOBDR) :
										$ITM_USEDR		= $rowJOBDR->TOTVOL;
										$ITM_USEDR_AM	= $rowJOBDR->TOTAMN;
									endforeach;
									
									$BUDG_REMVOLM	= $BUDG_REMVOLM - $ITM_USEDR;
									$BUDG_REMAMNT	= $BUDG_REMAMN - $ITM_USEDR_AM;

									$JobView		= "$JOBCODEID - $JODBDESC";
									$JobView 		= wordwrap($JobView, 50, "<br>", TRUE);

									$JOBDESCH		= "";
									$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
									$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
									foreach($resJOBDESC as $rowJOBDESC) :
										$JOBDESCH	= $rowJOBDESC->JOBDESC;
									endforeach;

									$JOBDESCH 		= wordwrap($JOBDESCH, 50, "<br>", TRUE);

									$Tot_AmountV 	= $Tot_AmountV + $AmountV;
									$RemAmountV 	= $Journal_Amount - $Tot_AmountV;
									?>
										<tr>
											<td style="text-align: center;"><?php echo $no; ?></td>
											<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
											<td style="text-align: center;"><?php echo $Acc_Id; ?></td>
											<td style="text-align: left; padding-left: 15px;">
												<?php echo $JobView; ?>
											  	<div style="margin-left: 15px; font-style: italic;">
											  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;<?php echo $JOBDESCH; ?>
											  	</div>
											</td>
											<td style="text-align: right;"><?php echo number_format($AmountV, 2); ?>&nbsp;</td>
											<td style="text-align: right;"><?php echo number_format($RemAmountV, 2); ?>&nbsp;</td>
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