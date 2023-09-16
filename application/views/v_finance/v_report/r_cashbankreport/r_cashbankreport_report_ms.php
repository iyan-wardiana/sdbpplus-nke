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

if($TOTPROJ == 1)
{
	$PRJNAMED	= '';
	$sqlPRJ	= "SELECT * FROM tbl_project WHERE PRJCODE = $PRJCODECOL";
	$resPRJ	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ):
		$PRJNAMED	= $rowPRJ->PRJNAME;
	endforeach;
}
else
{
	$THEROW		= 0;
	$PRJNAMED	= '';
	$sqlPRJ	= "SELECT * FROM tbl_project WHERE PRJCODE IN ($PRJCODECOL)";
	$resPRJ	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ):
		$PRJNAME	= $rowPRJ->PRJNAME;
		if($THEROW == 1)
			$PRJNAMED	= "$PRJNAME";
		else
			$PRJNAMED	= "$PRJNAMED, $PRJNAME";
	endforeach;
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
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <title><?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td width="58%" class="style2">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?> <br>
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
            	<td width="10%">Proyek</td>
                <td width="1%">:</td>
                <td width="89%"><?php echo $PRJNAMED; ?></td>
            </tr>
        </table>
      </td>
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
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="4%" nowrap style="text-align:center; font-weight:bold">No.</td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold">Nomor</td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold">Tanggal</td>
                  <td width="38%" nowrap style="text-align:center; font-weight:bold">Deskripsi</td>
                  <td width="11%" nowrap style="text-align:center; font-weight:bold">Debet</td>
                  <td width="11%" nowrap style="text-align:center; font-weight:bold">Kredit</td>
                  <td width="11%" nowrap style="text-align:center; font-weight:bold">Saldo</td>
                  <td width="11%" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
              </tr>
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
						if($viewProj == 0)	// SELECTED PROJECT
						{
							// GET PREVIOS TRANSACTION
								$TOT_BKRED	= 0;
								$sqlA 		= "SELECT SUM(A.Base_Debet + A.Base_Debet_tax - A.Base_Kredit - A.Base_Kredit_tax) 
													AS TOT_BKRED
												FROM tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType IN ('GEJ', 'VGEJ', 'BP', 'CPRJ', 'VCPRJ', 'BR', 'O-EXP') 
														AND B.proj_Code IN ($PRJCODECOL)
														AND B.JournalH_Date < '$Start_Date'
												WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3";
								$resA 			= $this->db->query($sqlA)->result();
								foreach($resA AS $rowOB):
									$TOT_BKRED	= $rowOB->TOT_BKRED;
								endforeach;
								
								$sql0 		= "tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType IN ('GEJ', 'VGEJ', 'BP', 'CPRJ', 'VCPRJ', 'BR', 'O-EXP') 
														AND B.proj_Code IN ($PRJCODECOL)
														AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
												WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3";
								$sql1 		= "SELECT DISTINCT A.JournalH_Code, A.Base_Debet, A.Base_Debet_tax,
													A.Base_Kredit, A.Base_Kredit_tax, A.Notes,
													B.JournalH_Date, B.JournalH_Desc, B.JournalType, B.Manual_No
												FROM tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType IN ('GEJ', 'VGEJ', 'BP', 'CPRJ', 'VCPRJ', 'BR', 'O-EXP') 
														AND B.proj_Code IN ($PRJCODECOL)
														AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
												WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3 
												ORDER BY B.Manual_No, B.JournalH_Date ASC";
						}
						else				// ALL PROJECT
						{
							// GET PREVIOS TRANSACTION
								$TOT_BKRED	= 0;
								$sqlA 		= "SELECT DISTINCT SUM(A.Base_Debet + A.Base_Debet_tax - A.Base_Kredit - A.Base_Kredit_tax) 
													AS TOT_BKRED
												FROM tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType IN ('GEJ', 'VGEJ', 'BP', 'CPRJ', 'VCPRJ', 'BR', 'O-EXP') 
														AND B.proj_Code IN ($PRJCODECOL)
														AND B.JournalH_Date < '$Start_Date'
												WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3";
								$resA 			= $this->db->query($sqlA)->result();
								foreach($resA AS $rowOB):
									$TOT_BKRED	= $rowOB->TOT_BKRED;
								endforeach;
							
								$sql0 		= "tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType IN ('GEJ', 'VGEJ', 'BP', 'CPRJ', 'VCPRJ', 'BR', 'O-EXP')
														AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
												WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3";
								$sql1 		= "SELECT DISTINCT A.JournalH_Code, A.Base_Debet, A.Base_Debet_tax,
													A.Base_Kredit, A.Base_Kredit_tax, A.Notes,
													B.JournalH_Date, B.JournalH_Desc, B.JournalType, B.Manual_No
												FROM tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
														AND B.JournalType IN ('GEJ', 'VGEJ', 'BP', 'CPRJ', 'VCPRJ', 'BR', 'O-EXP')
														AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
												WHERE A.Acc_Id IN ($ACCSELCOL) AND B.GEJ_STAT = 3
												ORDER BY B.Manual_No, B.JournalH_Date ASC";
						}
						
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
                            <td nowrap style="text-align:center;">&nbsp;</td>
                            <td colspan="5" nowrap style="text-align:center;">Saldo sebelumnya</td>
                            <td style="text-align:right;" nowrap><?php echo number_format($OPBAL, 2); ?>&nbsp;</td>
                            <td>&nbsp;</td>
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
								$Manual_No 			= $rowsql1->Manual_No;
								$JournalH_Date 		= $rowsql1->JournalH_Date;
								$JournalH_Desc 		= $rowsql1->JournalH_Desc;
								if($JournalH_Desc == '')
									$JournalH_Desc	= "Pembayaran";
									
								if($Manual_No == '')
									$Manual_No	= $JournalH_Code;
									
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
								$descAdd = "";
								if($JournalType == 'VCPRJ')
								{
									$descAdd = " &nbsp;- Void Doc.";
								}
								if($JournalType == 'BP')
								{
									$sqlCB 		= "SELECT CB_NOTES FROM tbl_bp_header WHERE CB_NUM = '$JournalH_Code'";
									$resCB 		= $this->db->query($sqlCB)->result();
									foreach($resCB AS $rowCB):
										$JournalH_Desc	= $rowCB->CB_NOTES;
									endforeach;
								}
								?>
								<tr>
									<td nowrap style="text-align:center;">&nbsp;
										<?php
											echo "$therow.";
										?>
									</td>
									<td nowrap style="text-align:left;">&nbsp;
										<?php
											echo $Manual_No;
										?>
                                    </td>
									<td nowrap style="text-align:center;">&nbsp;
										<?php
											echo date('Y-m-d',strtotime($JournalH_Date));
										?>
                                    </td>
									<td style="text-align:left;" nowrap>&nbsp;
										<?php
											echo "$JournalH_Desc $descAdd";
										?>
                                    </td>
									<td style="text-align:right;" nowrap><?php echo number_format($JournalD_D_tot, 2); ?></td>
									<td style="text-align:right;" nowrap><?php echo number_format($JournalD_K_tot, 2); ?></td>
									<td style="text-align:right;" nowrap><?php echo number_format($JournalH_Saldo, 2); ?></td>
									<td>&nbsp;
										<?php
											echo $Notes;
										?>
                                    </td>
								</tr>
								
								<?php
							endforeach;
							?>
                            <tr>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
                                <td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOT_D, 2); ?></td>
                                <td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOT_K, 2); ?></td>
								<td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($JournalH_Saldo, 2); ?></td>
                                <td nowrap style="text-align:center;">&nbsp;</td>
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
</body>
</html>