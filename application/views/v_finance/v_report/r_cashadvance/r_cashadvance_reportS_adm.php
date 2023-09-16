<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2018
 * File Name	= r_purchasereq.php
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
	
$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJDATE_CO, A.PRJEDAT,
			A.PRJCOST, A.PRJCATEG,
			A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.ISCHANGE, A.REFCHGNO, A.PRJCOST2, 
			A.PRJ_MNG, A.PRJBOQ,
			A.CHGUSER, A.CHGSTAT, A.PRJPROG, A.QTY_SPYR, A.PRC_STRK, A.PRC_ARST, A.PRC_MKNK, A.PRC_ELCT, A.PRJ_IMGNAME
		FROM tbl_project A
		WHERE A.PRJCODE = $PRJCODECOL";

$resPROJ	= $this->db->query($sql)->result();
foreach($resPROJ as $rowPROJ){
	$PRJCODE	= $rowPROJ->PRJCODE;
	$PRJNAME	= $rowPROJ->PRJNAME;
	$PRJCNUM	= $rowPROJ->PRJCNUM;
	$PRJDATE	= $rowPROJ->PRJDATE;
	$PRJEDAT	= $rowPROJ->PRJEDAT;
	$PRJOWN		= $rowPROJ->PRJOWN;
	$PRJLOCT	= $rowPROJ->PRJLOCT;
	$PRJ_MNG	= $rowPROJ->PRJ_MNG;
}
$MNGP_NAME	= '';
if($PRJ_MNG != '')
{
	$sqlMNGP 	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
	$resMNGP 	= $this->db->query($sqlMNGP)->result();
	foreach($resMNGP as $rowMNGP) :
		$First_Name = $rowMNGP->First_Name;
		$Last_Name 	= $rowMNGP->Last_Name;
	endforeach;
	$MNGP_NAME	= $First_Name.$Last_Name;
}

$StartDate	= date('Y-m-d', strtotime($Start_Date));
$EndDate	= date('Y-m-d', strtotime($End_Date));
$StartDateV	= date('d M Y', strtotime($Start_Date));
$EndDateV	= date('d M Y', strtotime($End_Date));
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
<div id="Layer1">
    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
    <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
    <a href="#" onClick="window.close();" class="button"> close </a>
</div>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="4%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td width="58%" class="style2">&nbsp;</td>
        <td width="38%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?> (<?php echo $CFTyped; ?>)<br>
          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px"><?php echo $comp_name; ?></span></td>
  </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr noshade /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center">
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td width="15%" nowrap>Nomor &amp; Nama Proyek </td>
                    <td width="1%">:</td>
                    <td width="34%" nowrap><?php echo "$PRJCODE - $PRJNAME";?></td>
                    <td width="12%">Pemilik</td>
                    <td width="1%">:</td>
                    <td width="37%">
                    <?php
					  $own_Code 	= '';
					  $CountOwn 	= $this->db->count_all('tbl_owner');
					  $sqlOwn 	= "SELECT own_Name FROM tbl_owner WHERE own_Code = '$PRJOWN'";
					  $resultOwn = $this->db->query($sqlOwn)->result();
					  if($CountOwn > 0)
					  {
						  foreach($resultOwn as $rowOwn) :
							  $own_Name = $rowOwn->own_Name;
						  endforeach;
					  }
					  echo $own_Name;
				  	?>
                    </td>
                </tr>
            	<tr>
            	  <td>No. Kontrak / SPK </td>
            	  <td>:</td>
            	  <td><?=$PRJCNUM?></td>
            	  <td>Konsultan</td>
            	  <td>:</td>
            	  <td>&nbsp;</td>
          	  </tr>
            	<tr>
            	  <td>Waktu Pelaksanaan </td>
            	  <td>:</td>
            	  <td><?php echo date("d M Y", strtotime($PRJDATE));?></td>
            	  <td nowrap>Kontraktor Utama</td>
            	  <td>:</td>
            	  <td>&nbsp;</td>
          	  </tr>
            	<tr>
            	  <td>Periode </td>
            	  <td>:</td>
            	  <td><?php echo "$StartDateV s.d. $EndDateV"; ?></td>
            	  <td>Kepala Proyek</td>
            	  <td>:</td>
            	  <td><?php echo $MNGP_NAME; ?></td>
          	  </tr>
            	<tr>
            	  <td>Tanggal </td>
            	  <td>:</td>
            	  <td><?php echo date("d M Y");?></td>
            	  <td>Lokasi</td>
            	  <td>:</td>
            	  <td><?=$PRJLOCT?></td>
          	  </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr noshade /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                  <td colspan="4" nowrap style="text-align:center; font-weight:bold">UANG MASUK</td>
                  <td colspan="4" nowrap style="text-align:center; font-weight:bold">UANG KELUAR</td>
                  <td width="11%" rowspan="2" nowrap style="text-align:center; font-weight:bold">SALDO</td>
                  <td width="16%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
              	</tr>
                <tr style="background:#CCCCCC">
                  <td width="3%" nowrap style="text-align:center; font-weight:bold">NO.</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                  <td width="14%" nowrap style="text-align:center; font-weight:bold">DESKRIPSI</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">JUMLAH (Rp.)</td>
                  <td width="3%" nowrap style="text-align:center; font-weight:bold">NO.</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">PERIODE</td>
                  <td width="12%" nowrap style="text-align:center; font-weight:bold">DESKRIPSI</td>
                  <td width="11%" nowrap style="text-align:center; font-weight:bold">JUMLAH (Rp.)</td>
                </tr>
                <?php
					// CASH IN COLLECT
					$therow			= 0;
					$therow1		= 0;
					$therow2		= 0;
					$CASH_SALDO		= 0;
					$OUT_M 			= 0;
					$OUT_U 			= 0;
					$OUT_T 			= 0;
					$OUT_SC 		= 0;
					$OUT_O 			= 0;
					$OUT_GE 		= 0;
					$OUT_I 			= 0;
					$CASH_INTOT		= 0;
					$CASH_OUTTOT	= 0;
					$sqlCPRJ	= "SELECT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JournalD_Debet, A.JournalD_Debet_tax,
										A.JournalD_Kredit, A.JournalD_Kredit_tax,
										A.ITM_CODE, A.Other_Desc,
										B.JournalType, B.Manual_No, B.JournalH_Date,
										C.ITM_CATEG, C.ITM_UNIT
									FROM
											tbl_journaldetail A
										INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											-- AND B.JournalType IN ('CPRJ', 'VCPRJ', 'GEJ')
											AND B.proj_Code = '$PRJCODE'
											AND B.GEJ_STAT IN (3, 9)
										LEFT JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
											AND C.PRJCODE = '$PRJCODE'
									WHERE
										A.proj_Code = '$PRJCODE'
										AND B.JournalH_Date >= '$StartDate'
										AND B.JournalH_Date <= '$EndDate'
										AND A.Acc_Id = '$selAccount'
									ORDER BY B.JournalH_Date, B.LastUpdate ASC";
					$resCPRJ	= $this->db->query($sqlCPRJ)->result();
					foreach($resCPRJ as $rowCPRJ):
						$JournalH_Code	= '';
						$JournalH_Date	= '';
						$JournalH_Date	= '';
						$Manual_No		= '';
						$JournalType	= $rowCPRJ->JournalType;
						$ITM_CATEG		= '';
						$ITM_UNIT		= '';
						$Other_Desc		= '';
						$CASH_IN		= 0;
						$CASH_OUT		= 0;
						$therow1V		= '';
						$therow2V		= '';
						if($JournalType == 'GEJ')
						{
							$therow1		= $therow1 + 1;
							$therow1V		= "$therow1.";
							$JournalH_Code1	= $rowCPRJ->JournalH_Code;
							$JournalH_Date1	= $rowCPRJ->JournalH_Date;
							$JournalH_Date1	= date('Y-m-d', strtotime($JournalH_Date));
							$Manual_No1		= $rowCPRJ->Manual_No;
							$ITM_CATEG1		= $rowCPRJ->ITM_CATEG;
							$ITM_UNIT1		= $rowCPRJ->ITM_UNIT;
							$Other_Desc1	= $rowCPRJ->Other_Desc;
							$CASH_IN		= $rowCPRJ->JournalD_Debet;
							$CASH_INV		= number_format($CASH_IN, 2);
							$CASH_OUTV		= '';
							$CASH_INTOT		= $CASH_INTOT + $CASH_IN;
							
							$therow2		= '';
							$JournalH_Code2	= '';
							$JournalH_Date2	= '';
							$JournalH_Date2	= '';
							$Manual_No2		= '';
							$ITM_CATEG2		= '';
							$ITM_UNIT2		= '';
							$Other_Desc2	= '';
						}
						elseif($JournalType == 'CPRJ')
						{
							// Karena ini adalah Pengeluaran Kas Proyek, maka dibalik
							
							$therow1		= '';
							$JournalH_Code1	= '';
							$JournalH_Date1	= '';
							$JournalH_Date1	= '';
							$Manual_No1		= '';
							$ITM_CATEG1		= '';
							$ITM_UNIT1		= '';
							$Other_Desc1	= '';
							
							$therow2		= $therow2 + 1;
							$therow2V		= "$therow2.";
							$JournalH_Code2	= $rowCPRJ->JournalH_Code;
							$JournalH_Date2	= $rowCPRJ->JournalH_Date;
							$JournalH_Date2	= date('Y-m-d', strtotime($JournalH_Date));
							$Manual_No2		= $rowCPRJ->Manual_No;
							$ITM_CATEG2		= $rowCPRJ->ITM_CATEG;
							$ITM_UNIT2		= $rowCPRJ->ITM_UNIT;
							$Other_Desc2	= $rowCPRJ->Other_Desc;
							$CASH_INV		= '';
							$CASH_OUT		= $rowCPRJ->JournalD_Kredit;
							$CASH_OUTV		= number_format($CASH_OUT, 2);
							$CASH_OUTTOT	= $CASH_OUTTOT + $CASH_OUT;
						}
						if($ITM_CATEG == 'M')
							$OUT_M		= $CASH_OUT;
						elseif($ITM_CATEG == 'U')
							$OUT_U		= $CASH_OUT;
						elseif($ITM_CATEG == 'T')
							$OUT_T		= $CASH_OUT;
						elseif($ITM_CATEG == 'SC')
							$OUT_SUB	= $CASH_OUT;
						elseif($ITM_CATEG == 'O')
							$OUT_O		= $CASH_OUT;
						elseif($ITM_CATEG == 'GE')
							$OUT_GE		= $CASH_OUT;
						elseif($ITM_CATEG == 'I')
							$OUT_I		= $CASH_OUT;
							
						$CASH_GSALDO	= $OUT_M + $OUT_U + $OUT_T + $OUT_SC + $OUT_O + $OUT_GE + $OUT_I;						
						?>
						<tr>
							<td nowrap style="text-align:left;"><?php echo $therow1V; ?></td>
							<td nowrap style="text-align:left;"><?php echo $JournalH_Date1; ?></td>
							<td nowrap style="text-align:left;"><?php echo $Other_Desc1; ?></td>
							<td nowrap style="text-align:right;"><?php echo $CASH_INV; ?></td>
							<td nowrap style="text-align:left;"><?php echo $therow2V; ?></td>
							<td nowrap style="text-align:left;"><?php echo $JournalH_Date2; ?></td>
							<td nowrap style="text-align:left;"><?php echo $Other_Desc2; ?></td>
							<td nowrap style="text-align:right;"><?php echo $CASH_OUTV; ?></td>
							<td nowrap style="text-align:left;">&nbsp;</td>
							<td nowrap style="text-align:left;">&nbsp;</td>
						</tr>
                        <?php
					endforeach;
				?>
                <tr>
                  <td colspan="3" nowrap style="text-align:center;"><b>TOTAL UANG MASUK</b></td>
                  <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASH_INTOT, 2); ?></td>
                  <td colspan="3" nowrap style="text-align:center;"><b>TOTAL UANG KELUAR</b></td>
                  <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASH_OUTTOT, 2); ?></td>
                  <td nowrap style="text-align:left;">&nbsp;</td>
                  <td nowrap style="text-align:left;">&nbsp;</td>
			  </tr>
								<tr style="line-height:2px;">
									<td colspan="10" nowrap style="text-align:center; font-style:italic; border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
								</tr>
            </table>
            <table width="100%" border="1" rules="all">
                <tr>
                    <td width="21%">
                    	Dibuat oleh: Adm. Um. &amp; Keu. Proyek<br><br>
                        Paraf<br><br>
                        Tanggal
                    </td>
                    <td width="21%">
                    	Diperiksa Oleh : Kepala Proyek<br><br>
                        Paraf<br><br>
                        Tanggal
                    </td>
                    <td width="21%">
                    	Diverifikasi Oleh : Keuangan Pusat<br><br>
                        Paraf<br><br>
                        Tanggal
                    </td>
                    <td width="21%">
                    	Disetujui Oleh :  Direktur Operasional<br><br>
                        Paraf<br><br>
                        Tanggal
                    </td>
                    <td width="17%">
                    	No. Form : 16.R0/PRO/keu/17<br><br>
                        Revisi ke : 0<br><br>
                        Tanggal  : <?php echo date("d M Y", strtotime("now"));?>
                    </td>
                </tr>
            </table>
	  </td>
    </tr>
</table>

</section>
</body>
</html>