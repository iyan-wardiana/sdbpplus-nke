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

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";

$THEROW		= 0;
$PRJNAMED	= 'Semua Proyek';

if($COLREFPRJ != "'All'")
{
	$sqlPRJ	= "SELECT * FROM tbl_project WHERE PRJCODE IN ($COLREFPRJ)";
	$resPRJ	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ):
		$PRJNAME	= $rowPRJ->PRJNAME;
		if($THEROW == 1)
			$PRJNAMED	= "$PRJNAME";
		else
			$PRJNAMED	= "$PRJNAMED, $PRJNAME";
	endforeach;

	$addQRY 	= "AND B.proj_Code IN ($COLREFPRJ)";
}
else
{
	$addQRY 	= "";
}

$Account_Name	= '';
$OPBAL1			= 0;
$sqlACN	= "SELECT A.Account_Nameen as Account_Name, A.Base_OpeningBalance AS OPBAL
			FROM tbl_chartaccount A
				INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
            WHERE A.Account_Number IN ($ACCSELCOL)";
$resACN	= $this->db->query($sqlACN)->result();
foreach($resACN as $rowACN):
	$Account_Name	= $rowACN->Account_Name;
	$OPBAL1			= $rowACN->OPBAL;
endforeach;

$Start_Date = date('Y-m-d',strtotime($Start_Date));
$End_Date	= date('Y-m-d',strtotime($End_Date));

$StartDate 	= date('d/m/Y',strtotime($Start_Date));
$EndDate	= date('d/m/Y',strtotime($End_Date));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Buku Bank</title>
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
				font-size: 8pt;
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
					width: 200px;
					height: 70px;
					padding-top: 15px;
					/* background-color: red; */
					float: left;
				}
				.header .logo img {
					width: 190px;
				}
				.header .title {
					/* background-color: aqua; */
					width: 100%;
					height: 70px;
					text-align: center;
				}
				.header .title div:first-child {
					font-size: 12pt;
					font-weight: bold;
					text-align: center;
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
				.content .content-detail table thead {
					background-color: #CCCCCC !important;
					border: 1px black;
				}
				.content .content-detail table thead th {
					text-align: center;
				}
				.content .content-detail table tbody {
					border: 1px black;
				}
				.content .content-detail table tbody td {
					padding: 2px;
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
					<div>Buku Bank</div>
					<div><?php echo $comp_name; ?></div>
					<div>(<?php echo $Account_Name; ?>)</div>
				</div>
				<div class="header-content">
					<span>Periode : &nbsp;<?php echo "$StartDate s.d. $EndDate"; ?></span>
				</div>
			</div>
			<div class="content">
				<div class="content-detail">
					<table width="100%" border="1">
						<thead>
							<tr>
								<th rowspan="2">No.</th>
								<th rowspan="2">Kode Proyek</th>
								<th rowspan="2">Tanggal</th>
								<th rowspan="2">No. Jurnal Bayar</th>
								<th rowspan="2">Nama Supplier</th>
								<th rowspan="2">Uraian</th>
								<th colspan="2">Nilai</th>
								<th rowspan="2">Saldo</th>
							</tr>
							<tr style="background:#CCCCCC">
								<th>Debet</th>
								<th>Kredit</th>
							</tr>
						</thead>
						<tbody>
							<?php
								function hitungHari($awal,$akhir)
								{
									$tglAwal = strtotime($awal);
									$tglAkhir = strtotime($akhir);
									$jeda = $tglAkhir - $tglAwal;
									return floor($jeda/(60*60*24));
								}
								if($CFType == 1) // 1. Detail, 2. Sumamry
								{
									$therow		= 0;

									// GET PREVIOS TRANSACTION
										$TOT_BKRED	= 0;
										$sqlA 		= "SELECT SUM(A.Base_Debet + A.Base_Debet_tax - A.Base_Kredit - A.Base_Kredit_tax) 
															AS TOT_BKRED
														FROM tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
																$addQRY
																AND B.JournalH_Date < '$Start_Date'
														WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3";
										$resA 			= $this->db->query($sqlA)->result();
										foreach($resA AS $rowOB):
											$TOT_BKRED	= $rowOB->TOT_BKRED;
										endforeach;
										
										$sql0 		= "tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
																$addQRY
																AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
														WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3";
										$sql1 		= "SELECT DISTINCT A.JournalH_Code, A.Acc_Id, A.Base_Debet, A.Base_Debet_tax,
															A.Base_Kredit, A.Base_Kredit_tax, A.Notes, A.Other_Desc,
															B.JournalH_Date, B.JournalH_Desc, B.JournalH_Desc3, B.JournalType, 
															B.Manual_No, B.proj_Code, B.PERSL_EMPID, B.SPLCODE, B.REF_CODE
														FROM tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
																$addQRY
																AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
														WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3 
														ORDER BY B.JournalH_Date, B.Manual_No ASC";	
										$res0 			= $this->db->count_all($sql0);
										$res1 			= $this->db->query($sql1)->result();

										$JournalH_Code		= '';
										$JournalH_Date 		= '';
										$JournalH_Desc		= '';
										$Base_Debet			= 0;
										$Base_Debet_tax		= 0;
										$Base_Kredit 		= 0;
										$Base_Kredit_tax	= 0;
										$OPBAL				= $TOT_BKRED + $OPBAL1;
										//echo "$OPBAL				= $TOT_BKRED + $OPBAL1;";
										$JournalH_Saldo		= $OPBAL;
										$Notes				= '';
								}
							?>
							<tr>
								<td colspan="8" nowrap style="text-align: right; font-weight:bold;">Saldo Sebelumnya</td>
								<td nowrap style="text-align: right; font-weight:bold;"><?php echo number_format($OPBAL, 2); ?>&nbsp;</td>
							</tr>
							<?php
								if($res0 > 0)
								{
									$TOT_D	= 0;
									$TOT_K	= 0;
									foreach($res1 as $rowsql1) :
										$therow				= $therow + 1;
										$JournalType 		= $rowsql1->JournalType;
										$JournalH_Code 		= $rowsql1->JournalH_Code;
										$Acc_Id 			= $rowsql1->Acc_Id;
										$Manual_No 			= $rowsql1->Manual_No;
										$REF_CODE           = $rowsql1->REF_CODE;
										$projCode 			= $rowsql1->proj_Code;
										$PERSL_EMPID 		= $rowsql1->PERSL_EMPID;
										$SPLCODE 			= $rowsql1->SPLCODE;
										$JournalH_Date 		= $rowsql1->JournalH_Date;
										$JournalH_Desc 		= $rowsql1->JournalH_Desc;
										$JournalH_Desc3		= $rowsql1->JournalH_Desc3;
										$Other_Desc         = $rowsql1->Other_Desc;
										if($JournalH_Desc == '')
											$JournalH_Desc	= "$Other_Desc";
											
										if($Manual_No == '')
											$Manual_No	= $JournalH_Code;
											
										if($JournalType == 'CHO-PD')
										{
											if($REF_CODE != '')
												$Manual_No	= $REF_CODE;
											else
												$Manual_No	= $JournalH_Desc3;
										}
											
											
										$JournalD_D 		= $rowsql1->Base_Debet;
										$JournalD_D_tax		= $rowsql1->Base_Debet_tax;
										$JournalD_D_tot		= $JournalD_D + $JournalD_D_tax;
										$TOT_D				= $TOT_D + $JournalD_D_tot;
										$JournalD_K			= $rowsql1->Base_Kredit;
										$JournalD_K_tax		= $rowsql1->Base_Kredit_tax;
										$JournalD_K_tot		= $JournalD_K + $JournalD_K_tax;
										$TOT_K				= $TOT_K + $JournalD_K_tot;
										$JournalH_Saldo		= $JournalH_Saldo + $JournalD_D_tot - $JournalD_K_tot;
										$Notes 				= $rowsql1->Notes;
										if($JournalD_D_tot == 0) $JournalD_D_tot = '-';
										else $JournalD_D_tot = number_format($JournalD_D_tot, 2);
										if($JournalD_K_tot == 0) $JournalD_K_tot = '-';
										else $JournalD_K_tot = number_format($JournalD_K_tot, 2);

										if($SPLCODE == '')
											$SPLCODE = $PERSL_EMPID;

										$EMP_NAME       = "";
										$s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$SPLCODE'
															UNION
															SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
										$r_emp          = $this->db->query($s_emp)->result();
										foreach($r_emp as $rw_emp) :
											$EMP_NAME   = $rw_emp->EMP_NAME;
										endforeach;

										$descAdd = "";
										if($JournalType == 'VCPRJ')
										{
											$descAdd = " &nbsp;- Void Doc.";
										}
										if($JournalType == 'BP')
										{
											// $sqlCB 		= "SELECT CB_NOTES FROM tbl_bp_header WHERE CB_NUM = '$JournalH_Code'";
											// $resCB 		= $this->db->query($sqlCB)->result();
											// foreach($resCB AS $rowCB):
											// 	$JournalH_Desc	= $rowCB->CB_NOTES;
											// endforeach;
											
											$sqlCB 		= "SELECT CBD_DOCCODE, CBD_DESC 
															FROM tbl_bp_detail WHERE CB_NUM = '$JournalH_Code'";
											$resCB 		= $this->db->query($sqlCB)->result();
											$row 		= 0;
											$CBD_DOCCODE 	= '';
											$CBD_DESC 	= '';
											foreach($resCB AS $rowCB):
												$row 		= $row + 1;
												$CBD_DOCCODE= $rowCB->CBD_DOCCODE;
												$CBD_DESC 	= $rowCB->CBD_DESC;
												if($row == 1)
												{
													$JournalH_Desc = "<div>$CBD_DOCCODE - <span style='font-style: italic;'>$CBD_DESC</span></div>";
												}
												else
												{
													$JournalH_Desc = "$JournalH_Desc<div>$CBD_DOCCODE - <span style='font-style: italic;'>$CBD_DESC</span></div>";
												}
											endforeach;

											$s_SPL          =  "SELECT A.CB_PAYFOR FROM tbl_bp_header A WHERE A.CB_NUM = '$JournalH_Code'";
											$r_SPL          = $this->db->query($s_SPL)->result();
											foreach($r_SPL as $rw_SPL) :
												$SPLCODE 	= $rw_SPL->CB_PAYFOR;
											endforeach;
											
											$s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$SPLCODE'
																UNION
																SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
											$r_emp          = $this->db->query($s_emp)->result();
											foreach($r_emp as $rw_emp) :
												$EMP_NAME   = $rw_emp->EMP_NAME;
											endforeach;
										}
										else if($JournalType == 'PD')
										{
											$s_SPL          =  "SELECT A.CB_PAYFOR FROM tbl_bp_header A WHERE A.CB_NUM = '$JournalH_Code'";
											$r_SPL          = $this->db->query($s_SPL)->result();
											foreach($r_SPL as $rw_SPL) :
												$SPLCODE 	= $rw_SPL->CB_PAYFOR;
											endforeach;
											
											$s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$SPLCODE'
																UNION
																SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
											$r_emp          = $this->db->query($s_emp)->result();
											foreach($r_emp as $rw_emp) :
												$EMP_NAME   = $rw_emp->EMP_NAME;
											endforeach;
										}

										?>
											<tr>
												<td style="text-align:center;"><?php echo "$therow."; ?></td>
												<td nowrap style="text-align:center;"><?php echo $projCode; ?></td>
												<td nowrap style="text-align:center;"><?php echo date('d-m-Y',strtotime($JournalH_Date));?></td>
												<td nowrap style="text-align:center;"><?php echo "$Manual_No";?></td>
												<td><?php echo "$SPLCODE - $EMP_NAME"; ?></td>
												<td style="text-align:left;"><?php echo "$JournalH_Desc $descAdd";?></td>
												<td style="text-align:right;"><?php echo $JournalD_D_tot; ?></td>
												<td style="text-align:right;"><?php echo $JournalD_K_tot; ?></td>
												<td style="text-align:right;"><?php echo number_format($JournalH_Saldo, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr>
											<td nowrap style="text-align:center;">&nbsp;</td>
											<td nowrap style="text-align:center;">&nbsp;</td>
											<td nowrap style="text-align:center;">&nbsp;</td>
											<td nowrap style="text-align:center;">&nbsp;</td>
											<td nowrap style="text-align:center;">&nbsp;</td>
											<td nowrap style="text-align:center;">&nbsp;</td>
											<td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOT_D, 2); ?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOT_K, 2); ?></td>
											<td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($JournalH_Saldo, 2); ?></td>
										</tr>
									<?php
								}
							?>
						</tbody>
					</table>
				</div>
				<div class="content-asign">

				</div>
			</div>
			<div class="footer">
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
		document.onkeydown = (event) => {
			console.log(event);
		    if (event.ctrlKey) {
		        event.preventDefault();
		        // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
		    }   
		};
	});
</script>