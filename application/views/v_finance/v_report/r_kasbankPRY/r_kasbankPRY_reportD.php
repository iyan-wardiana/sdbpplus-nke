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
				font-size: 9pt;
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
					padding-top: 15px;
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
					font-size: 10pt;
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
					border: 1px double black;
					text-align: center;
					padding: 3px;
				}
				.content .content-detail table tbody {
					border: 1px double black;
				}
				.content .content-detail table tbody td {
					padding: 3px;
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

				.text-vertical {
					writing-mode: vertical-rl !important;
					text-orientation: upright !important;
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
                    <div class="h1_title"><?php echo $h1_title; ?></div>
                    <div class="periode">PERIODE: <?php echo $datePeriod; ?></div>
				</div>
                <div class="lampiran">
                    <span style="font-style: italic; font-weight:bold;">LAMPIRAN 8</span>
                    <table width="100%" border="0" style="font-size: 7pt;">
                        <tr>
                            <td width="50">DOC.NO</td>
                            <td width="10">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="50">REVISI</td>
                            <td width="10">:</td>
                            <td></td>
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
							<td width="100">Bank</td>
							<td width="10">:</td>
							<td><?php echo ""; ?></td>
						</tr>
						<tr>
							<td width="100">Rek. No.</td>
							<td width="10">:</td>
							<td><?php echo ""; ?></td>
						</tr>
						<tr>
							<td width="100">CABANG</td>
							<td width="10">:</td>
							<td><?php echo ""; ?></td>
						</tr>
						<tr>
							<td width="100">PROYEK</td>
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
								<th rowspan="2" width="100">Tanggal</th>
								<th rowspan="2" width="100">No. Voucher Penerimaan/ Pembayaran</th>
								<th rowspan="2" width="100">No. Jurnal Memorial</th>
								<th rowspan="2" width="100">No. BG/CEK</th>
								<th rowspan="2">Keterangan</th>
								<th colspan="2">BANK</th>
								<th rowspan="2" class="text-vertical">CODE</th>
								<th colspan="2">KAS</th>
								<th rowspan="2" class="text-vertical">CODE</th>
							</tr>
							<tr>
								<th>Debet</th>
								<th>Kredit</th>
								<th>Debet</th>
								<th>Kredit</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// get Akun KAS
								$TOTK_Debet 	= 0;
								$TOTK_Kredit 	= 0;
								$OB_BANK 		= 0;
								$OB_KAS 		= 0;
								$get_AccK 	= "SELECT Account_Number, Base_OpeningBalance FROM tbl_chartaccount 
												WHERE PRJCODE = '$PRJCODE' AND Account_Class = 3
												AND Account_Number IN (SELECT Acc_Number FROM tbl_project_acc WHERE PRJCODE = '$PRJCODE')";
								$res_AccK 	= $this->db->query($get_AccK);
								if($res_AccK->num_rows() > 0)
								{
									$Acc_KAS = '';
									foreach($res_AccK->result() as $rAccK):
										$Acc_KAS 	= $rAccK->Account_Number;
										$OB_KAS 	= $rAccK->Base_OpeningBalance;
									endforeach;

									// get Penerimaan / Pembayaran KAS
										$get_receiptK 	= "SELECT A.JournalH_Code, A.JournalD_Debet, A.JournalD_Kredit, A.Other_Desc, 
															B.Manual_No, B.JournalH_Desc, B.JournalH_Date, B.JournalType
															FROM tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code 
															AND A.proj_Code = B.proj_Code
															WHERE B.proj_Code = '$PRJCODE' AND A.Acc_Id = '$Acc_KAS'
															AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
															AND B.GEJ_STAT = 3";
										$res_receiptK 	= $this->db->query($get_receiptK);
										if($res_receiptK->num_rows() > 0)
										{
											$TOTK_Debet 	= 0;
											$TOTK_Kredit 	= 0;
											foreach($res_receiptK->result() as $rPK):
												$JournalH_Code 		= $rPK->JournalH_Code;
												$JournalH_Date		= date('d/m/Y', strtotime($rPK->JournalH_Date));
												$JournalType 		= $rPK->JournalType;
												$JournalD_Debet 	= $rPK->JournalD_Debet;
												$JournalD_Kredit	= $rPK->JournalD_Kredit;
												$Other_Desc 		= $rPK->Other_Desc;
												$Manual_No 			= $rPK->Manual_No;
												$JournalH_Desc 		= $rPK->JournalH_Desc;

												if($JournalH_Desc == '') $JournalH_Desc = $Other_Desc;

												$TOTK_Debet 		= $TOTK_Debet + $JournalD_Debet;
												$TOTK_Kredit 		= $TOTK_Kredit + $JournalD_Kredit;

												?>
													<tr>
														<td><?=$JournalH_Date?></td>
														<td><?=$Manual_No?></td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td><?php echo "$JournalH_Desc"; ?></td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
														<td style="text-align: right;"><?php echo number_format($JournalD_Kredit, 2); ?></td>
														<td>&nbsp;</td>
													</tr>
												<?php
											endforeach;
										}
								}

							// get Akun BANK
								$TOTB_Debet 	= 0;
								$TOTB_Kredit 	= 0;
								$get_AccB 	= "SELECT Account_Number, Base_OpeningBalance FROM tbl_chartaccount 
												WHERE PRJCODE = '$PRJCODE' AND Account_Class = 4
												AND Account_Number IN (SELECT Acc_Number FROM tbl_project_acc WHERE PRJCODE = '$PRJCODE')";
								$res_AccB 	= $this->db->query($get_AccB);
								if($res_AccB->num_rows() > 0)
								{
									$Acc_BANK = '';
									foreach($res_AccB->result() as $rAccB):
										$Acc_BANK 	= $rAccB->Account_Number;
										$OB_BANK 	= $rAccB->Base_OpeningBalance;
									endforeach;

									// get Penerimaan / Pembayaran BANK
										$get_receiptB 	= "SELECT A.JournalH_Code, A.JournalD_Debet, A.JournalD_Kredit, A.Other_Desc, 
															B.Manual_No, B.JournalH_Desc, B.JournalH_Date, B.JournalType
															FROM tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code 
															AND A.proj_Code = B.proj_Code
															WHERE B.proj_Code = '$PRJCODE' AND A.Acc_Id = '$Acc_BANK'
															AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
															AND B.GEJ_STAT = 3
															UNION
															SELECT A.JournalH_Code, A.JournalD_Debet, A.JournalD_Kredit, A.Other_Desc, 
															B.Manual_No, B.JournalH_Desc, B.JournalH_Date, B.JournalType
															FROM tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code 
															AND A.proj_Code = B.proj_Code
															WHERE B.proj_Code = 'KTR-22' AND A.Acc_Id = '$Acc_BANK'
															AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
															AND B.GEJ_STAT = 3";
										$res_receiptB 	= $this->db->query($get_receiptB);
										if($res_receiptB->num_rows() > 0)
										{
											$TOTB_Debet 	= 0;
											$TOTB_Kredit 	= 0;
											foreach($res_receiptB->result() as $rPB):
												$JournalH_Code 		= $rPB->JournalH_Code;
												$JournalH_Date		= date('d/m/Y', strtotime($rPB->JournalH_Date));
												$JournalType 		= $rPB->JournalType;
												$JournalD_Debet 	= $rPB->JournalD_Debet;
												$JournalD_Kredit	= $rPB->JournalD_Kredit;
												$Other_Desc 		= $rPB->Other_Desc;
												$Manual_No 			= $rPB->Manual_No;
												$JournalH_Desc 		= $rPB->JournalH_Desc;

												if($JournalH_Desc == '') $JournalH_Desc = $Other_Desc;

												$TOTB_Debet 		= $TOTB_Debet + $JournalD_Debet;
												$TOTB_Kredit 		= $TOTB_Kredit + $JournalD_Kredit;

												?>
													<tr>
														<td><?=$JournalH_Date?></td>
														<td><?=$Manual_No?></td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td><?php echo "$JournalH_Desc"; ?></td>
														<td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
														<td style="text-align: right;"><?php echo number_format($JournalD_Kredit, 2); ?></td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
													</tr>
												<?php
											endforeach;
										}
								}
							?>
							<tr>
								<td colspan="5" style="text-align: center; font-weight:bold;">JUMLAH PERIODE INI</td>
								<td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTB_Debet, 2); ?></td>
								<td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTB_Kredit, 2); ?></td>
								<td>&nbsp;</td>
								<td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTK_Debet, 2); ?></td>
								<td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTK_Kredit, 2); ?></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="5" style="text-align: center; font-weight:bold;">SALDO AWAL BANK & KAS</td>
								<td colspan="2" style="text-align: right; font-weight: bold;"><?php echo number_format($OB_BANK, 2); ?></td>
								<td>&nbsp;</td>
								<td colspan="2" style="text-align: right; font-weight: bold;"><?php echo number_format($OB_KAS, 2); ?></td>
								<td>&nbsp;</td>
							</tr>
							<?php
								$SALDO_AKHIRB = $OB_BANK + ($TOTB_Debet - $TOTB_Kredit);
								$SALDO_AKHIRK = $OB_KAS + ($TOTK_Debet - $TOTK_Kredit);
							?>
							<tr>
								<td colspan="5" style="text-align: center; font-weight:bold;">SALDO AKHIR BANK & KAS</td>
								<td colspan="2" style="text-align: right; font-weight: bold;"><?php echo number_format($SALDO_AKHIRB, 2); ?></td>
								<td>&nbsp;</td>
								<td colspan="2" style="text-align: right; font-weight: bold;"><?php echo number_format($SALDO_AKHIRK, 2); ?></td>
								<td>&nbsp;</td>
							</tr>
						</tbody>
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