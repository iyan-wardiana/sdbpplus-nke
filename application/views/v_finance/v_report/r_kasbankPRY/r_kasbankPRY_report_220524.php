<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2018
 * File Name	= r_cashbankreport_report.php
 * Location		= -
*/

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
		 <!-- Latest compiled and minified CSS -->
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		<style>
			body { 
				font-family: Arial, Helvetica, sans-serif;
				font-size: 10pt;
			}

			* {
				box-sizing: border-box;
				-moz-box-sizing: border-box;
			}

			.sheet {
				overflow: hidden;
				position: relative;
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
				.sheet.custom { padding: 10mm 5mm 10mm 5mm }

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
					@page { 
						size: a4;
					}

					body.A3.landscape { width: 420mm }
					body.A3, body.A4.landscape { width: 297mm }
					body.A4, body.A5.landscape { width: 210mm }
					body.A5                    { width: 148mm }
					body.letter, body.legal    { width: 216mm }
					body.letter.landscape      { width: 280mm }
					body.legal.landscape       { width: 357mm }

					/** Padding area **/
						.page .sheet {
							margin: 0;
							padding: 0;
							background: initial;
							box-shadow: initial;
							border-radius: initial;
						}
				}

				#Layer1 {
					position: absolute;
					top: 10px;
					left: 10px;
				}

			/* Header */
				.header {
					width: 100%;
					/* background-color: blue; */
				}
				.header .logo {
					width: 150px;
					height: 70px;
					padding-top: 15px;
					/* background-color: red; */
					float: left;
				}
				.header .logo img {
					width: 130px;
				}
				.header .title {
					/* background-color: aqua; */
                    width: 480px;
					height: 70px;
					text-align: center;
					font-size: 12pt;
                    float: left;
				}
                .header .lampiran {
                    float: right;
                }
				.header .title div:first-child {
					font-size: 12pt;
					font-weight: bold;
					/* text-align: center; */
				}
				.header .title div:nth-child(2) {
					font-size: 12pt;
					font-weight: bold;
					text-align: center;
				}
				.header .title div:last-child {
					font-size: 9pt;
					font-weight: bold;
					text-align: center;
				}
				.header .header-content {
					width: 100%;
					margin-top: 20px;
				}
				.header .header-content span {
					font-style: italic;
				}
			/* Content */
				.content .content-detail {
					width: 100%;
				}
				.content .content-detail span {
					display: inline-block;
					text-align: left;
				}
				.content .content-detail span:nth-child(1) {
					width: 5%;
					/* background-color: green; */
					vertical-align: top;
				}
				.content .content-detail span:nth-child(2) {
					width: 90%;
					/* background-color: red; */
					text-align: justify;
				}
				.content .content-detail table thead th {
					border: 2px double black;
					padding: 5px;
					text-align: center;
				}
				.content .content-detail table tbody {
					border: 2px double black;
				}
				.content .content-detail table tbody td {
					padding: 2px;
				}
				.content-asign {
					border: 4px double;
					height: 200px;
				}
				.content-asign #tbl-asign table td {
					border: 1px double;
				}

				ul.notes {
					list-style-type: decimal;
				}
				ul.notes li {
					padding-left: 10px;
				}

			/* Footer */
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
			<div class="header">
				<div class="logo">
					<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
				</div>
				<div class="title">
                    <div class="company"><?php echo $comp_name; ?></div>
                    <div class="h1_title"><?php echo $h1_title; ?></div>
                    <div class="periode">PERIODE: <?php echo $datePeriod; ?></div>
				</div>
                <div class="lampiran">
                    <span style="font-style: italic; font-weight:bold;">LAMPIRAN 7</span>
                    <table width="100%" border="0" style="font-size: 7pt;">
                        <tr>
                            <td width="50">DOC.NO</td>
                            <td width="10">:</td>
                            <td>IQ231</td>
                        </tr>
                        <tr>
                            <td width="50">REVISI</td>
                            <td width="10">:</td>
                            <td>(03/07/17)</td>
                        </tr>
                        <tr>
                            <td width="50">AMD.</td>
                            <td width="10">:</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td width="50">Lembar</td>
                            <td width="10">:</td>
                            <td>1/1</td>
                        </tr>
                    </table>
                </div>
				<div class="header-content">
                    <table width="100%" border="0">
						<tr>
							<td width="50">Proyek</td>
							<td width="10">:</td>
							<td><?php echo "$PRJCODE"; ?></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="content">
				<div class="content-detail">
					<table width="100%" border="1">
						<thead>
							<tr>
								<th colspan="3">
									<span>CATATAN</span>
									<span>
										<ul class="notes">
											<li>SALDO AKHIR BANK DIBAWAH AGAR DILAMPIRI PRINT OUT REKENING KORAN BANK (MINGGUAN) YANG MENUNJUKAN JUMLAH SALDO SAMA.</li>
											<li>JIKA ADA TRANSAKSI PADA BUTIR IVb, c, AGAR DILAMPIRKAN DATANYA SEBAGAIMANA ISIAN FORMULIR PADA LAMPIRAN 09 & 10.</li>
										</ul>
									</span>
								</th>
							</tr>
							<tr>
								<th>KETERANGAN</th>
								<th>PERIODE INI</th>
								<th>KUMULATIF</th>
							</tr>
						</thead>
						<tbody>
							<?php
								// get data realisasi kas / bank proyek
									$TOT_BR 			= 0;
									$OBalance_KAS		= 0;
									$OBalance_BANK		= 0;
									$Acc_KAS 			= 0;
									$Acc_BANK			= 0;
									$TOT_TF 			= 0;
									$TOT_GIRO 			= 0;
									$TOT_GETTF 			= 0;
									$TOT_REALZ1			= 0;
									$TOT_ADM 			= 0;
									$TOT_REALZ 			= 0;
									$TOT_REALZK 		= 0;
									$TOT_GETTFK 		= 0;
									$TOT_KR 			= 0;
									$TOT_KR_BF			= 0;

									$TOT_BEXPD 			= 0;
									$TOT_KEXPD 			= 0;
									$SALDO_BANK 		= 0;
									$SALDO_KAS 			= 0;

									$OBalance_KAS_KOM 	= 0;
									$TOT_GETTFK_KOM 	= 0;
									$TOT_REALZK_KOM 	= 0;
									$SALDO_BANK_KOM		= 0;
									$SALDO_KAS_KOM		= 0;

									$TOT_KR_KOM 		= 0;

									$TOTAL_KB 		= 0;
									$TOTAL_KB_KOM	= 0;

								// BANK SAAT INI 
									$getREALZB = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, 
													SUM(A.JournalD_Debet), SUM(A.JournalD_Kredit), 
													C.Account_Class 
													FROM
														tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
														INNER JOIN tbl_chartaccount C ON C.Account_Number = A.Acc_Id 
														AND C.PRJCODE = A.proj_Code 
													WHERE
														B.proj_Code = '$PRJCODE' 
														AND B.JournalType = 'CPRJ' 
														AND C.Account_Class = 4 
														AND A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
														AND B.GEJ_STAT = 3 
													GROUP BY
														A.Acc_Id";
									$resREALZB = $this->db->query($getREALZB);
									if($resREALZB->num_rows() > 0)
									{
										foreach($resREALZB->result() as $rRB):
											$JournalH_Code_B 		= $rRB->JournalH_Code;	
											$JournalH_Date_B 		= $rRB->JournalH_Date;	
											$JournalType_B 			= $rRB->JournalType;	
											$Acc_Id_B 				= $rRB->Acc_Id;
											$Account_Class_B 		= $rRB->Account_Class;

											// get opening balance BANK
												$getOBBANK 	= "SELECT Base_OpeningBalance, Account_Number AS Acc_BANK FROM tbl_chartaccount 
																WHERE PRJCODE = '$PRJCODE' AND Account_Class = 4 AND Account_Number = '$Acc_Id_B'";
												$resOBBANK 	= $this->db->query($getOBBANK);
												if($resOBBANK->num_rows() > 0)
												{
													foreach($resOBBANK->result() as $rOBB):
														$OBalance_BANK 	= $rOBB->Base_OpeningBalance;
														$Acc_BANK 		= $rOBB->Acc_BANK;
													endforeach;
												}

											// get transfer dari pusat => PRJ_HO
												$getPB_HO 	= "SELECT SUM(JournalD_Debet) AS TOT_TF 
																FROM tbl_journaldetail 
																WHERE JournalType = 'PINBUK' AND proj_CodeHO = 'KTR' AND Acc_Id = '$Acc_BANK'
																AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resPB_HO 	= $this->db->query($getPB_HO);
												if($resPB_HO->num_rows() > 0)
												{
													foreach($resPB_HO->result() as $rPBHO):
														$TOT_TF 	= $rPBHO->TOT_TF;
													endforeach;
												}

											// get penerimaan jasa giro
												$Acc_GIRO 	= '7104.02'; // PENDAPATAN JASA GIRO
												$getGIRO 	= "SELECT SUM(JournalD_Kredit) AS TOT_GIRO FROM tbl_journaldetail
																WHERE JournalType = 'BR' AND proj_Code = '$PRJCODE'
																AND Acc_Id = '$Acc_GIRO' AND Acc_Id_Cross = '$Acc_BANK'
																AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resGIRO 	= $this->db->query($getGIRO);
												if($resGIRO->num_rows() > 0)
												{
													foreach($resGIRO->result() as $rG):
														$TOT_GIRO 	= $rG->TOT_GIRO;
													endforeach;
												}

												$TOT_BR = $TOT_TF + $TOT_GIRO;

											// pengambilan utk BANK
												$getPB_PRY 	= "SELECT SUM(JournalD_Kredit) AS TOT_GETTF 
																FROM tbl_journaldetail 
																WHERE JournalType = 'PINBUK' AND proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_BANK'
																AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resPB_PRY 	= $this->db->query($getPB_PRY);
												if($resPB_PRY->num_rows() > 0)
												{
													foreach($resPB_PRY->result() as $rPBPRY):
														$TOT_GETTF 	= $rPBPRY->TOT_GETTF;
													endforeach;
												}

											// pengeluaran/realisasi pihak ketiga => supplier
												$getREALZ 	= "SELECT SUM(A.JournalD_Kredit) AS TOT_REALZ
																FROM tbl_journaldetail A
																WHERE A.JournalType = 'CPRJ' AND A.proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_BANK'
																AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resREALZ 	= $this->db->query($getREALZ);
												if($resREALZ->num_rows() > 0)
												{
													foreach($resREALZ->result() as $rRLZ):
														$TOT_REALZ 	= $rRLZ->TOT_REALZ;
													endforeach;
												}

											// biaya administrasi bank
												$Acc_ADM 	= '5103.041'; // ADM & PROVISI BANK
												$getADM 	= "SELECT SUM(JournalD_Debet) AS TOT_ADM
																FROM tbl_journaldetail
																WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_ADM' AND Acc_Id_Cross = '$Acc_BANK'
																AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resADM 	= $this->db->query($getADM);
												if($resADM->num_rows() > 0)
												{
													foreach($resADM->result() as $rADM):
														$TOT_ADM 	= $rADM->TOT_ADM;
													endforeach;
												}

												$TOT_REALZ1 = $TOT_REALZ - $TOT_ADM;

												$TOT_BEXPD 	= $TOT_GETTF + $TOT_REALZ1 + $TOT_ADM;
										endforeach;
									}

								// KAS SAAT INI 
									$getREALZK = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, 
													SUM(A.JournalD_Debet), SUM(A.JournalD_Kredit), 
													C.Account_Class 
													FROM
														tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
														INNER JOIN tbl_chartaccount C ON C.Account_Number = A.Acc_Id 
														AND C.PRJCODE = A.proj_Code 
													WHERE
														B.proj_Code = '$PRJCODE' 
														AND B.JournalType = 'CPRJ' 
														AND C.Account_Class = 3 
														AND A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
														AND B.GEJ_STAT = 3 
													GROUP BY
														A.Acc_Id";
									$resREALZK = $this->db->query($getREALZK);
									if($resREALZK->num_rows() > 0)
									{
										foreach($resREALZK->result() as $rRK):
											$JournalH_Code_K 	= $rRK->JournalH_Code;	
											$JournalH_Date_K 	= $rRK->JournalH_Date;	
											$JournalType_K 		= $rRK->JournalType;	
											$Acc_Id_K 			= $rRK->Acc_Id;
											$Account_Class_K 	= $rRK->Account_Class;

											// get opening balance KAS
												$getOBKAS 	= "SELECT Base_OpeningBalance, Account_Number AS Acc_KAS FROM tbl_chartaccount 
																WHERE PRJCODE = '$PRJCODE' AND Account_Class = 3 AND Account_Number = '$Acc_Id_K'";
												$resOBKAS 	= $this->db->query($getOBKAS);
												if($resOBKAS->num_rows() > 0)
												{
													foreach($resOBKAS->result() as $rOBK):
														$OBalance_KAS 	= $rOBK->Base_OpeningBalance;
														$Acc_KAS 		= $rOBK->Acc_KAS;
													endforeach;
												}

											// pengambilan utk KAS
												$getPB_PRYK 	= "SELECT SUM(JournalD_Kredit) AS TOT_GETTFK 
																	FROM tbl_journaldetail 
																	WHERE JournalType = 'PINBUK' AND proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_KAS'
																	AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resPB_PRYK 	= $this->db->query($getPB_PRYK);
												if($resPB_PRYK->num_rows() > 0)
												{
													foreach($resPB_PRYK->result() as $rPBPRYK):
														$TOT_GETTFK 	= $rPBPRYK->TOT_GETTFK;
													endforeach;
												}

											// pengeluaran/realisasi pihak ketiga => supplier
												$getREALZK 	= "SELECT SUM(A.JournalD_Kredit) AS TOT_REALZK
																FROM tbl_journaldetail A
																WHERE A.JournalType = 'CPRJ' AND A.proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_KAS'
																AND JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND GEJ_STAT = 3";
												$resREALZK 	= $this->db->query($getREALZK);
												if($resREALZK->num_rows() > 0)
												{
													foreach($resREALZK->result() as $rRLZK):
														$TOT_REALZK 	= $rRLZK->TOT_REALZK;
													endforeach;
												}

												$TOT_KR = $TOT_GETTFK;
												$TOT_KEXPD 	= $TOT_GETTFK + $TOT_REALZK;
										endforeach;
									}

								// BANK KOMULATIF
									$getREALZB_BF = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, 
													SUM(A.JournalD_Debet), SUM(A.JournalD_Kredit), 
													C.Account_Class 
													FROM
														tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
														INNER JOIN tbl_chartaccount C ON C.Account_Number = A.Acc_Id 
														AND C.PRJCODE = A.proj_Code 
													WHERE
														B.proj_Code = '$PRJCODE' 
														AND B.JournalType = 'CPRJ' 
														AND C.Account_Class = 4 
														AND A.JournalH_Date < '$Start_Date'
														AND B.GEJ_STAT = 3 
													GROUP BY
														A.Acc_Id";
									$resREALZB_BF = $this->db->query($getREALZB_BF);

									
									if($resREALZB_BF->num_rows() > 0)
									{
										foreach($resREALZB_BF->result() as $rRKB_BF):
											$JournalH_Code_BF 		= $rRKB_BF->JournalH_Code;	
											$JournalH_Date_BF 		= $rRKB_BF->JournalH_Date;	
											$JournalType_BF 		= $rRKB_BF->JournalType;	
											$Acc_Id_BF 				= $rRKB_BF->Acc_Id;
											$Account_Class_BF 		= $rRKB_BF->Account_Class;

											// get opening balance BANK
												$getOBBANK_BF 	= "SELECT Base_OpeningBalance, Account_Number AS Acc_BANK FROM tbl_chartaccount 
																WHERE PRJCODE = '$PRJCODE' AND Account_Class = 4 AND Account_Number = '$Acc_Id_BF'";
												$resOBBANK_BF 	= $this->db->query($getOBBANK_BF);
												if($resOBBANK_BF->num_rows() > 0)
												{
													foreach($resOBBANK_BF->result() as $rOBB_BF):
														$OBalance_BANK_BF 	= $rOBB_BF->Base_OpeningBalance;
														$Acc_BANK_BF 		= $rOBB_BF->Acc_BANK;
													endforeach;
												}

											// get transfer dari pusat => PRJ_HO
												$getPB_HO_BF 	= "SELECT SUM(JournalD_Debet) AS TOT_TF 
																FROM tbl_journaldetail 
																WHERE JournalType = 'PINBUK' AND proj_CodeHO = 'KTR' AND Acc_Id = '$Acc_BANK_BF'
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resPB_HO_BF 	= $this->db->query($getPB_HO_BF);
												if($resPB_HO_BF->num_rows() > 0)
												{
													foreach($resPB_HO_BF->result() as $rPBHO_BF):
														$TOT_TF_BF 	= $rPBHO_BF->TOT_TF;
													endforeach;
												}

											// get penerimaan jasa giro before
												$Acc_GIRO_BF 	= '7104.02'; // PENDAPATAN JASA GIRO
												$getGIRO_BF 	= "SELECT SUM(JournalD_Kredit) AS TOT_GIRO FROM tbl_journaldetail
																WHERE JournalType = 'BR' AND proj_Code = '$PRJCODE'
																AND Acc_Id = '$Acc_GIRO_BF' AND Acc_Id_Cross = '$Acc_BANK_BF'
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resGIRO_BF 	= $this->db->query($getGIRO_BF);
												if($resGIRO_BF->num_rows() > 0)
												{
													foreach($resGIRO_BF->result() as $rG_BF):
														$TOT_GIRO_BF 	= $rG_BF->TOT_GIRO;
													endforeach;
												}

												$TOT_BR_BF = $TOT_TF_BF + $TOT_GIRO_BF;

											// pengambilan utk BANK
												$getPB_PRY_BF 	= "SELECT SUM(JournalD_Kredit) AS TOT_GETTF 
																FROM tbl_journaldetail 
																WHERE JournalType = 'PINBUK' AND proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_BANK_BF'
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resPB_PRY_BF 	= $this->db->query($getPB_PRY_BF);
												if($resPB_PRY_BF->num_rows() > 0)
												{
													foreach($resPB_PRY_BF->result() as $rPBPRY_BF):
														$TOT_GETTF_BF 	= $rPBPRY_BF->TOT_GETTF;
													endforeach;
												}

											// pengeluaran/realisasi pihak ketiga => supplier before
												$getREALZ_BF 	= "SELECT SUM(A.JournalD_Kredit) AS TOT_REALZ
																FROM tbl_journaldetail A
																WHERE A.JournalType = 'CPRJ' AND A.proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_BANK_BF'
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resREALZ_BF 	= $this->db->query($getREALZ_BF);
												if($resREALZ_BF->num_rows() > 0)
												{
													foreach($resREALZ_BF->result() as $rRLZ_BF):
														$TOT_REALZ_BF 	= $rRLZ_BF->TOT_REALZ;
													endforeach;
												}

											// biaya administrasi bank before
												$Acc_ADM_BF 	= '5103.041'; // ADM & PROVISI BANK
												$getADM_BF 		= "SELECT SUM(JournalD_Debet) AS TOT_ADM
																FROM tbl_journaldetail
																WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_ADM_BF' AND Acc_Id_Cross = '$Acc_BANK_BF'
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resADM_BF 	= $this->db->query($getADM_BF);
												if($resADM_BF->num_rows() > 0)
												{
													foreach($resADM_BF->result() as $rADM_BF):
														$TOT_ADM_BF 	= $rADM_BF->TOT_ADM;
													endforeach;
												}

												$TOT_REALZ1_BF 	= $TOT_REALZ_BF - $TOT_ADM_BF;

												$TOT_BEXPD_BF	= $TOT_GETTF_BF + $TOT_REALZ1_BF + $TOT_ADM_BF;
										endforeach;
									}

								// KAS KOMULATIF
									$getREALZK_BF = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, 
													SUM(A.JournalD_Debet), SUM(A.JournalD_Kredit), 
													C.Account_Class 
													FROM
														tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
														INNER JOIN tbl_chartaccount C ON C.Account_Number = A.Acc_Id 
														AND C.PRJCODE = A.proj_Code 
													WHERE
														B.proj_Code = '$PRJCODE' 
														AND B.JournalType = 'CPRJ' 
														AND C.Account_Class = 3 
														AND A.JournalH_Date < '$Start_Date'
														AND B.GEJ_STAT = 3 
													GROUP BY
														A.Acc_Id";
									$resREALZK_BF = $this->db->query($getREALZK_BF);
									if($resREALZK_BF->num_rows() > 0)
									{
										foreach($resREALZK_BF->result() as $rRKK_BF):
											$JournalH_Code_KBF 		= $rRKK_BF->JournalH_Code;	
											$JournalH_Date_KBF 		= $rRKK_BF->JournalH_Date;	
											$JournalType_KBF 		= $rRKK_BF->JournalType;	
											$Acc_Id_KBF 			= $rRKK_BF->Acc_Id;
											$Account_Class_KBF 		= $rRKK_BF->Account_Class;

											// get opening balance KAS
												$getOBKAS_BF 	= "SELECT Base_OpeningBalance, Account_Number AS Acc_KAS FROM tbl_chartaccount 
																	WHERE PRJCODE = '$PRJCODE' AND Account_Class = 3 AND Acc_Id = '$Acc_Id_KBF'";
												$resOBKAS_BF 	= $this->db->query($getOBKAS_BF);
												if($resOBKAS_BF->num_rows() > 0)
												{
													foreach($resOBKAS_BF->result() as $rOBK_BF):
														$OBalance_KAS_BF 	= $rOBK_BF->Base_OpeningBalance;
														$Acc_KAS_BF 		= $rOBK_BF->Acc_KAS;
													endforeach;
												}
											
											// pengambilan utk KAS before
												$getPB_PRYK_BF 	= "SELECT SUM(JournalD_Debet) AS TOT_GETTFK_BF 
																	FROM tbl_journaldetail 
																	WHERE JournalType = 'PINBUK' AND proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_KAS_BF'
																	AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resPB_PRYK_BF 	= $this->db->query($getPB_PRYK_BF);
												if($resPB_PRYK_BF->num_rows() > 0)
												{
													foreach($resPB_PRYK_BF->result() as $rPBPRYK_BF):
														$TOT_GETTFK_BF 	= $rPBPRYK_BF->TOT_GETTFK_BF;
													endforeach;
												}

											// pengeluaran/realisasi pihak ketiga => supplier
												$getREALZK_BF 	= "SELECT SUM(A.JournalD_Kredit) AS TOT_REALZK_BF
																FROM tbl_journaldetail A
																WHERE A.JournalType = 'CPRJ' AND A.proj_Code = '$PRJCODE' AND Acc_Id = '$Acc_KAS_BF'
																AND JournalH_Date < '$Start_Date' AND GEJ_STAT = 3";
												$resREALZK_BF 	= $this->db->query($getREALZK_BF);
												if($resREALZK_BF->num_rows() > 0)
												{
													foreach($resREALZK_BF->result() as $rRLZK_BF):
														$TOT_REALZK_BF 	= $rRLZK_BF->TOT_REALZK_BF;
													endforeach;
												}

												$TOT_KEXPD_BF	= $TOT_GETTFK_BF + $TOT_REALZK_BF;

										endforeach;
									}
									
								// SALDO AKHIR BANK
									$SALDO_BANK 		= $OBalance_BANK + $TOT_BR + $TOT_BEXPD;

								// SALDO AKHIR KAS
									$SALDO_KAS 		= $OBalance_KAS + $TOT_KR + $TOT_KEXPD;

								// TOTAL KOMULATIF BANK
									$OBalance_BANK_KOM 	= $OBalance_BANK + $OBalance_BANK_BF;
									$TOT_TF_KOM 		= $TOT_TF + $TOT_TF_BF;
									$TOT_GIRO_KOM 		= $TOT_GIRO + $TOT_GIRO_BF;
									$TOT_BR_KOM 		= $TOT_BR + $TOT_BR_BF;
									$TOT_GETTF_KOM 		= $TOT_GETTF + $TOT_GETTF_BF;
									$TOT_REALZ1_KOM 	= $TOT_REALZ1 + $TOT_REALZ1_BF;
									$TOT_ADM_KOM 		= $TOT_ADM + $TOT_ADM_BF;
									$TOT_BEXPD_KOM 		= $TOT_BEXPD + $TOT_BEXPD_BF;

								// SALDO AKHIR BANK KOMULATIF
									$SALDO_BANK_KOM 	= $OBalance_BANK_BF + $TOT_BR_BF + $TOT_BEXPD_BF;
								
								// SALDO AKHIR KAS KOMULATIF
									$SALDO_KAS_KOM 	= $OBalance_KAS_BF + $TOT_KR_BF + $TOT_KEXPD_BF;

								// TOTAL KOMULATIF KAS
									$OBalance_KAS_KOM 	= $OBalance_KAS + $OBalance_KAS_BF;
									$TOT_GETTFK_KOM 	= $TOT_GETTFK + $TOT_GETTFK_BF;
									$TOT_REALZK_KOM 	= $TOT_REALZK + $TOT_REALZK_BF;

									$TOT_KR_KOM 		=  $TOT_GETTFK + $TOT_GETTFK_BF;
									$TOT_KEXPD_KOM 		= $TOT_KEXPD + $TOT_KEXPD_BF;

								// TOTAL SALDO KAS & BANK
									$TOTAL_KB 		= $SALDO_BANK + $SALDO_KAS;
									$TOTAL_KB_KOM	= $SALDO_BANK_KOM + $SALDO_KAS_KOM;

							?>
							<tr>
								<td style="font-weight: bold;">I. BANK</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="padding-left: 15px;">a. Saldo Awal Bank</td>
								<td style="text-align: right;"><?php echo number_format($OBalance_BANK, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($OBalance_BANK_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px; font-weight: bold;">b. Penerimaan :</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">1. Transfer dari Pusat</td>
								<td style="text-align: right;"><?php echo number_format($TOT_TF, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_TF_KOM); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">2. Jasa Giro (Netto)</td>
								<td style="text-align: right;"><?php echo number_format($TOT_GIRO, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_GIRO_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="text-align: right; font-weight: bold;">Jumlah Penerimaan</td>
								<td style="text-align: right;"><?php echo number_format($TOT_BR, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_BR_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px; font-weight: bold;">c. Pengeluaran :</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">3. Pengambilan Untuk Kas = II f.6</td>
								<td style="text-align: right;"><?php echo number_format($TOT_GETTF, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_GETTF_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">4. Pihak Ketiga</td>
								<td style="text-align: right;"><?php echo number_format($TOT_REALZ1, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_REALZ1_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">5. Biaya Administrasi</td>
								<td style="text-align: right;"><?php echo number_format($TOT_ADM, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_ADM_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="text-align: right; font-weight: bold;">Jumlah Pengeluaran</td>
								<td style="text-align: right;"><?php echo number_format($TOT_BEXPD, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_BEXPD_KOM, 2); ?></td>
							</tr>
							<tr style="border-bottom: 2px double;">
								<td style="padding-left: 15px; font-weight: bold;">d. Saldo Akhir Bank</td>
								<td style="text-align: right;"><?php echo number_format($SALDO_BANK, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($SALDO_BANK_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="font-weight: bold; padding-top: 10px;">II. KAS</td>
								<td style="padding-top: 10px;">&nbsp;</td>
								<td style="padding-top: 10px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="padding-left: 15px;">e. Saldo Awal Kas</td>
								<td style="text-align: right;"><?php echo number_format($OBalance_KAS, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($OBalance_KAS_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px; font-weight: bold;">f. Penerimaan :</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">6. Dari Bank = I c.3</td>
								<td style="text-align: right;"><?php echo number_format($TOT_GETTFK, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_GETTFK_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="text-align: right; font-weight: bold;">Jumlah Penerimaan</td>
								<td style="text-align: right;"><?php echo number_format($TOT_KR, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_KR_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px; font-weight: bold;">g. Pengeluaran :</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="padding-left: 25px;">7. Pihak Ketiga</td>
								<td style="text-align: right;"><?php echo number_format($TOT_REALZK, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_REALZK_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="text-align: right; font-weight: bold;">Jumlah Pengeluaran</td>
								<td style="text-align: right;"><?php echo number_format($TOT_KEXPD, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOT_KEXPD_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;">h. Saldo Akhir Kas</td>
								<td style="text-align: right;"><?php echo number_format($SALDO_KAS, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($SALDO_KAS_KOM, 2); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 15px;">i. Saldo Akhir Bank & Kas</td>
								<td style="text-align: right;"><?php echo number_format($TOTAL_KB, 2); ?></td>
								<td style="text-align: right;"><?php echo number_format($TOTAL_KB_KOM, 2); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="content-asign">
					<?php
						$PRJLOCT 	= $this->db->get_where("tbl_project", ["PRJCODE" => $PRJCODE])->row("PRJLOCT");
					?>
					<span style="display: inline-block; margin-bottom: 10px;"><?php echo $PRJLOCT.", ".date('d-m-Y'); ?></span>
					<table id="tbl-asign" width="100%" border="0" style="font-weight: bold;">
						<tr>
							<td width="25%">DIBUAT,</td>
							<td width="25%">MENGETAHUI,</td>
							<td width="25%">MENGETAHUI,</td>
							<td width="25%">DIPERIKSA,</td>
						</tr>
						<tr>
							<td width="25%">
								<div style="font-size: 8pt; padding-top: 100px">Nama: _____________________</div>
								<div style="font-size: 8pt; padding-top: 5px;">Tanggal: _____________________</div>
							</td>
							<td width="25%">
								<div style="font-size: 8pt; padding-top: 100px">Nama: _____________________</div>
								<div style="font-size: 8pt; padding-top: 5px;">Tanggal: _____________________</div>
							</td>
							<td width="25%">
								<div style="font-size: 8pt; padding-top: 100px">Nama: _____________________</div>
								<div style="font-size: 8pt; padding-top: 5px;">Tanggal: _____________________</div>
							</td>
							<td width="25%">
								<div style="font-size: 8pt; padding-top: 100px">Nama: _____________________</div>
								<div style="font-size: 8pt; padding-top: 5px;">Tanggal: _____________________</div>
							</td>
						</tr>
						
					</table>
				</div>
			</div>
			<div class="footer">
				<span style="font-size: 7pt; font-style:italic;">&copy;PT NUSA KONSTRUKSI ENJINIRING Tbk. Indonesia</span>
			</div>
		</section>
	</body>
</html>
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
<script>
	$(function(){
		// window.print();
		document.onkeydown = (event) => {
			console.log(event);
		    if (event.ctrlKey) {
		        event.preventDefault();
		        // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
		    }   
		};

		const mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function(mql) {
		    if (mql.matches) {
		        console.log('onbeforeprint equivalent');
		    } else {
		        console.log('onafterprint equivalent');
		        window.opener.location.reload();
				close();
		    }
		});
	});
</script>