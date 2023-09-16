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
		<style>
			body { 
				/*margin: 0;*/
				padding: 0;
				background-color: #FAFAFA;
				font: 9pt Arial, Helvetica, sans-serif;
			}

			* {
				box-sizing: border-box;
				-moz-box-sizing: border-box;
			}

			.page {
				width: 300mm;
				/*min-height: 296mm;*/
				padding-left: 0.5cm;
				padding-right: 0.5cm;
				padding-top: 1cm;
				padding-bottom: 1cm;
				margin: 0.5cm auto;
				border: 1px #D3D3D3 solid;
				border-radius: 5px;
				background: white;
				/* background-size: 400px 200px !important;*/
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
			}

			@page {
				/*size: auto;*/
				size: a4;
				/* margin: 0; */
			}

			@media print {

				@page{size: a4;}
				.page {
					margin: 0;
					padding: 0;
					border: initial;
					border-radius: initial;
					width: initial;
					min-height: initial;
					box-shadow: initial;
					background: initial;
					page-break-after: always;
				}
			}

			.style2 table th, td {
				padding: 3px;
			}

			#Layer1 {
				position: absolute;
				top: 40px;
			}
		</style>
    </head>

	<body style="overflow:auto">
		<div class="page">
			<div id="Layer1">
				<a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
				<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">&nbsp;Print</a>
			</div>
			<section class="content">
			    <table width="100%" border="0" style="size:auto">
				    <tr>
				        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
				        <td width="58%" class="style2">&nbsp;</td>
				        <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
				    </tr>
				    <tr>
				        <td width="100" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="190"></td>
				        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px">Buku Bank<br>
				          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px"><?php echo $comp_name; ?></span>
				          <br>
				          <span class="style2" style="text-align:center; font-weight:bold; font-size:12px">(<?php echo $Account_Name; ?>)</span>
				          </td>
				  	</tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
				    </tr>
				    <tr>
				      <td colspan="3" class="style2" style="text-align:left; font-style:italic">
				      	<table width="100%" border="0" style="size:auto">
				        	<tr>
				            	<td width="10%">Periode</td>
				                <td width="1%">:</td>
				                <td width="89%"><?php echo "$StartDate s.d. $EndDate"; ?></td>
				            </tr>
				        </table>
				      </td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2">
				            <table width="100%" border="1" rules="all">
								<thead>
									<tr style="background:#CCCCCC">
										<th rowspan="2" style="text-align:center; font-weight:bold">No.</th>
										<th rowspan="2" width="50" style="text-align:center; font-weight:bold">Kode Proyek</th>
										<th rowspan="2" nowrap style="text-align:center; font-weight:bold">Tanggal</th>
										<th rowspan="2" style="text-align:center; font-weight:bold">No. Jurnal Bayar</th>
										<th rowspan="2" width="150" style="text-align:center; font-weight:bold">Nama Supplier</th>
										<th rowspan="2" style="text-align:center; font-weight:bold">Uraian</th>
										<th colspan="2" style="text-align:center; font-weight:bold">Nilai</th>
										<th width="100" rowspan="2" style="text-align:center; font-weight:bold">Saldo</th>
									</tr>
									<tr style="background:#CCCCCC">
										<th width="80" style="text-align:center; font-weight:bold">Debet</th>
										<th width="80" style="text-align:center; font-weight:bold">Kredit</th>
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
															ORDER BY B.JournalH_Date, B.JournalH_Id, B.Manual_No ASC";
															
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
										?>
				                        <tr>
				                            <td colspan="8" nowrap style="text-align:right; font-weight:bold;">Saldo Sebelumnya</td>
				                            <td style="text-align:right;" nowrap><?php echo number_format($OPBAL, 2); ?>&nbsp;</td>
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
													$JournalH_Desc	= "Pembayaran";
													
												if($Manual_No == '')
													$Manual_No	= $JournalH_Code;
													
												if($JournalType == 'CHO-PD')
												    $Manual_No	= $REF_CODE;
													
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
												}

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
												?>
												<tr>
													<td style="text-align:center;">
														<?php
															echo "$therow.";
														?>
													</td>
													<td nowrap style="text-align:center;"><?php echo $projCode; ?></td>
													<td nowrap style="text-align:center;">
														<?php
															echo date('Y-m-d',strtotime($JournalH_Date));
															?>
				                                    </td>
													<td nowrap style="text-align:left;">
														<?php
															echo "$Manual_No";
														?>
													</td>
													<td><?php echo "$SPLCODE - $EMP_NAME"; ?></td>
													<td style="text-align:left;">
														<?php
															echo "$JournalH_Desc $descAdd";
															// echo "$Other_Desc $descAdd";
														?>
				                                    </td>
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
										else
										{
											?>
				                            <tr>
				                              <td nowrap style="text-align:center;" colspan="8">--- no data ---</td>
				                            </tr>
											<?php
										}
										?>
										</tbody>
										<tfoot>
										</tfoot>
				                        <?php
									}
				                ?>
				            </table>
				            <table width="100%" border="0" style="size:auto">
				                <tr>
				                    <td>&nbsp;</td>
				                </tr>
				            </table>
				            <table width="100%" border="0" style="size:auto">
				                <tr>
				                    <td width="56%">&nbsp;</td>
				                    <td width="44%" nowrap>
				                    	<table width="100%" border="1" style="size:auto" rules="all">
				                        	<tr>
				                            	<td colspan="3" style="text-align:center">Ditandatangani oleh</td>
				                                <td width="25%" style="text-align:center">dibuat :</td>
				                            </tr>
				                            <tr>
				                            	<td width="25%" style="text-align:center">Dir Operasional</td>
				                                <td width="25%" style="text-align:center">M. Finance</td>
				                                <td width="25%" style="text-align:center">Ass. M. finance</td>
				                                <td style="text-align:center">Finance</td>
				                            </tr>
				                            <tr>
				                            	<td width="25%" style="text-align:center" height="60px">&nbsp;</td>
				                                <td width="25%" style="text-align:center" height="60px">&nbsp;</td>
				                                <td width="25%" style="text-align:center" height="60px">&nbsp;</td>
				                                <td style="text-align:center" height="60px">&nbsp;</td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				            </table>
					  </td>
				    </tr>
				</table>
			</section>
		</div>
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