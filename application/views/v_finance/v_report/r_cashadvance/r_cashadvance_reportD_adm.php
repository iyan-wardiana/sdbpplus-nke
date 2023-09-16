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

function cut_text2($var, $len = 200, $txt_titik = "-") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
	if (strlen ($var) < $len) 
	{ 
		return $var; 
	}
	if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
	{
		return $match [1] . $txt_titik;
	}
	else
	{
		return substr ($var, 0, $len) . $txt_titik;
	}
}
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
        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td width="58%" class="style2">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?> (<?php echo $CFTyped; ?>)<br>
          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px; display: none;"><?php echo $comp_name; ?></span></td>
  </tr>
    <tr>
      <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td width="15%">Nomor &amp; Nama Proyek </td>
                    <td width="1%">:</td>
                    <td width="34%"><?php echo "$PRJCODE - $PRJNAME";?></td>
                    <td width="12%">Pemilik</td>
                    <td width="1%">:</td>
                    <td width="37%">
                    <?php
					  $own_Code 	= '';
					  $own_Name		= '';
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
            	  <td>Kontraktor Utama</td>
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
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NO.</td>
                    <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Tanggal</td>
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No. Voucher</td>
                    <td width="4%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Deskripsi</td>
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Vol</td>
                    <td width="4%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Sat</td>
                    <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Uang Masuk<br>
                    Rp.</td>
                    <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Uang Keluar<br>
                    Rp.</td>
                    <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Saldo <br>
                    Rp.</td>
                    <td colspan="7" nowrap style="text-align:center; font-weight:bold"> Biaya</td>
                    <td width="9%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Total</td>
              </tr>
                <tr style="background:#CCCCCC">
                    <td width="6%" nowrap style="text-align:center; font-weight:bold">Bahan</td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold">Upah</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">Alat</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold">Subkontraktor</td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold">Persiapan</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">Umum</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold">Rupa-Rupa</td>
                </tr>
                <?php
					$TOT_INOB	= 0;
					$sqlINOB	= "SELECT SUM(A.Base_Debet + A.Base_Debet_tax) 
										AS TOT_INOB
									FROM
											tbl_journaldetail A
										INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											-- AND B.JournalType IN ('CPRJ', 'VCPRJ', 'GEJ')
											-- AND B.proj_Code = '$PRJCODE'
											AND B.GEJ_STAT IN (3, 9)
										LEFT JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
											AND C.PRJCODE = '$PRJCODE'
									WHERE
										-- A.proj_Code = '$PRJCODE' AND
										B.JournalH_Date <= '$StartDate'
										AND A.Acc_Id = '$selAccount'
										AND A.GEJ_STAT IN (3, 9)";
					$resINOB	= $this->db->query($sqlINOB)->result();
					foreach($resINOB AS $rowINOB):
						$TOT_INOB	= $rowINOB->TOT_INOB;
					endforeach;
					
					$TOT_OUTOB	= 0;
					$sqlOUTOB	= "SELECT SUM(A.Base_Kredit - A.Base_Kredit_tax) 
										AS TOT_OUTOB
									FROM
											tbl_journaldetail A
										INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											-- AND B.JournalType IN ('CPRJ', 'VCPRJ', 'GEJ')
											-- AND B.proj_Code = '$PRJCODE'
											AND B.GEJ_STAT IN (3, 9)
										LEFT JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
											AND C.PRJCODE = '$PRJCODE'
									WHERE
										-- A.proj_Code = '$PRJCODE' AND
										B.JournalH_Date <= '$StartDate'
										AND A.Acc_Id = '$selAccount'
										AND A.GEJ_STAT IN (3, 9)";
					$resOUTOB	= $this->db->query($sqlOUTOB)->result();
					foreach($resOUTOB AS $rowOUTOB):
						$TOT_OUTOB	= $rowOUTOB->TOT_OUTOB;
					endforeach;
					
					$OUT_MX 	= 0;
					$OUT_UX 	= 0;
					$OUT_TX 	= 0;
					$OUT_SCX 	= 0;
					$OUT_OX 	= 0;
					$OUT_GEX 	= 0;
					$OUT_IX 	= 0;
					
					$OUT_MTX	= 0;
					$OUT_UTX	= 0;
					$OUT_TTX	= 0;
					$OUT_SCTX	= 0;
					$OUT_OTX	= 0;
					$OUT_GETX	= 0;
					$OUT_ITX	= 0;
					
					$sqlCPRJX	= "SELECT DISTINCT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JournalD_Debet, A.JournalD_Debet_tax,
										A.JournalD_Kredit, A.JournalD_Kredit_tax,
										A.ITM_CODE, A.Other_Desc,
										B.JournalType, B.Manual_No, B.JournalH_Date,
										C.ITM_CATEG, C.ITM_UNIT
									FROM
											tbl_journaldetail A
										INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											-- AND B.JournalType IN ('CPRJ', 'VCPRJ', 'GEJ')
											-- AND B.proj_Code = '$PRJCODE'
											AND B.GEJ_STAT IN (3, 9)
										LEFT JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
											AND C.PRJCODE = '$PRJCODE'
									WHERE
										-- A.proj_Code = '$PRJCODE' AND
										B.JournalH_Date <= '$StartDate'
										AND A.Acc_Id = '$selAccount'
										AND A.GEJ_STAT IN (3, 9)
									ORDER BY B.JournalH_Date ASC";
					$resCPRJX	= $this->db->query($sqlCPRJX)->result();
					foreach($resCPRJX as $rowCPRJX):
						$JournalTypeX	= $rowCPRJX->JournalType;
						$ITM_CATEGX		= $rowCPRJX->ITM_CATEG;
						$CASH_INX		= 0;
						$CASH_OUTX		= 0;
						if($JournalTypeX == 'GEJ')
						{
							$CASH_INX	= $rowCPRJX->JournalD_Debet;
						}
						elseif($JournalTypeX == 'CPRJ')
						{
							$CASH_OUTX		= $rowCPRJX->JournalD_Debet;
						}
						
						if($ITM_CATEGX == 'M')
							$OUT_MX		= $CASH_OUTX;
						elseif($ITM_CATEGX == 'U')
							$OUT_UX		= $CASH_OUTX;
						elseif($ITM_CATEGX == 'T')
							$OUT_TX		= $CASH_OUTX;
						elseif($ITM_CATEGX == 'SC')
							$OUT_SCX	= $CASH_OUTX;
						elseif($ITM_CATEGX == 'O' || $ITM_CATEGX == 'S')
							$OUT_OX		= $CASH_OUTX;
						elseif($ITM_CATEGX == 'GE')
							$OUT_GEX	= $CASH_OUTX;
						elseif($ITM_CATEGX == 'I')
							$OUT_IX		= $CASH_OUTX;
						
						$OUT_MTX		= $OUT_MTX + $OUT_MX;
						$OUT_UTX		= $OUT_UTX + $OUT_UX;
						$OUT_TTX		= $OUT_TTX + $OUT_TX;
						$OUT_SCTX		= $OUT_SCTX + $OUT_SCX;
						$OUT_OTX		= $OUT_OTX + $OUT_OX;
						$OUT_GETX		= $OUT_GETX + $OUT_GEX;
						$OUT_ITX		= $OUT_ITX + $OUT_IX;
					endforeach;
					
					$TOT_BKRED	= 0;
					$sqlA		= "SELECT SUM(A.Base_Debet + A.Base_Debet_tax - A.Base_Kredit - A.Base_Kredit_tax) 
										AS TOT_BKRED
									FROM
											tbl_journaldetail A
										INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											-- AND B.JournalType IN ('CPRJ', 'VCPRJ', 'GEJ')
											-- AND B.proj_Code = '$PRJCODE'
											AND B.GEJ_STAT IN (3, 9)
										LEFT JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
											AND C.PRJCODE = '$PRJCODE'
									WHERE
										-- A.proj_Code = '$PRJCODE' AND
										B.JournalH_Date <= '$StartDate'
										AND A.Acc_Id = '$selAccount'
										AND A.GEJ_STAT IN (3, 9)";
					$resA		= $this->db->query($sqlA)->result();
					foreach($resA AS $rowOB):
						$TOT_BKRED	= $rowOB->TOT_BKRED;
					endforeach;
				?>
                <tr>
                    <td nowrap style="text-align:center;">&nbsp;</td>
                    <td nowrap style="text-align:center;">&nbsp;</td>
                    <td nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:left; font-weight:bold; font-style:italic"><?php echo "Sisa Saldo Minggu Lalu."; ?></td>
                    <td nowrap style="text-align:left;">&nbsp;</td>
                    <td nowrap style="text-align:center;">&nbsp;</td>
                    <td nowrap style="text-align:right;"><?php echo number_format($TOT_INOB, 2); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($TOT_OUTOB, 2); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($TOT_BKRED, 2); ?></td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                    <td nowrap style="text-align:right;">&nbsp;</td>
                </tr>
                <?php
					// CASH IN COLLECT
					$therow		= 0;
					$CASH_SALDO	= $TOT_BKRED;
					$CASH_INTOT	= 0;
					$CASH_OUTTOT= 0;
					$OUT_M 		= 0;
					$OUT_U 		= 0;
					$OUT_T 		= 0;
					$OUT_SC 	= 0;
					$OUT_O 		= 0;
					$OUT_GE 	= 0;
					$OUT_I 		= 0;
					
					$OUT_MT		= 0;
					$OUT_UT		= 0;
					$OUT_TT		= 0;
					$OUT_SCT	= 0;
					$OUT_OT		= 0;
					$OUT_GET	= 0;
					$OUT_IT		= 0;
					$CASH_GSALDOT	= 0;
					
					$sqlCPRJ	= "SELECT DISTINCT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JournalD_Debet, A.JournalD_Debet_tax,
										A.JournalD_Kredit, A.JournalD_Kredit_tax,
										A.ITM_CODE, A.Other_Desc, B.JournalH_Desc,
										B.JournalType, B.Manual_No, B.JournalH_Date,
										C.ITM_CATEG, C.ITM_GROUP, C.ITM_UNIT
									FROM
											tbl_journaldetail A
										INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
											-- AND B.JournalType IN ('CPRJ', 'VCPRJ', 'GEJ')
											-- AND B.proj_Code = '$PRJCODE'
											AND B.GEJ_STAT IN (3, 9)
										LEFT JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
											AND C.PRJCODE = '$PRJCODE'
									WHERE
										-- A.proj_Code = '$PRJCODE' AND
										B.JournalH_Date >= '$StartDate'
										AND B.JournalH_Date <= '$EndDate'
										AND A.Acc_Id = '$selAccount'
										AND A.GEJ_STAT IN (3, 9)
									ORDER BY B.JournalH_Date, B.LastUpdate ASC";
					$resCPRJ	= $this->db->query($sqlCPRJ)->result();
					foreach($resCPRJ as $rowCPRJ):
						$JournalH_Code	= $rowCPRJ->JournalH_Code;
						$JournalH_Date	= $rowCPRJ->JournalH_Date;
						$JournalH_Date	= date('Y-m-d', strtotime($JournalH_Date));
						$Manual_No		= $rowCPRJ->Manual_No;
						if($Manual_No == '')
							$Manual_No	= $JournalH_Code;
						$JournalType	= $rowCPRJ->JournalType;
						$ITM_CATEG		= $rowCPRJ->ITM_CATEG;
						$ITM_GROUP		= $rowCPRJ->ITM_GROUP;
						$ITM_UNIT		= $rowCPRJ->ITM_UNIT;
						$CASH_IN		= 0;
						$CASH_OUT		= 0;
						//if($JournalType == 'GEJ')
						//{
							$CASH_IN	= $rowCPRJ->JournalD_Debet;
							$CASH_INTOT	= $CASH_INTOT + $CASH_IN;
						//}
						//elseif($JournalType == 'CPRJ' || $JournalType == 'O-EXP')
						//{
							// Karena ini adalah Pengeluaran Kas Proyek, maka dibalik
							$CASH_OUT		= $rowCPRJ->JournalD_Kredit;
							$CASH_OUTTOT	= $CASH_OUTTOT + $CASH_OUT;
						//}
						
						if($ITM_GROUP == 'M' || $ITM_GROUP == '')
							$OUT_M		= $CASH_OUT;
						elseif($ITM_GROUP == 'U')
							$OUT_U		= $CASH_OUT;
						elseif($ITM_GROUP == 'T')
							$OUT_T		= $CASH_OUT;
						elseif($ITM_GROUP == 'SC')
							$OUT_SC		= $CASH_OUT;
						elseif($ITM_GROUP == 'O' || $ITM_GROUP == 'S')
							$OUT_O		= $CASH_OUT;
						elseif($ITM_GROUP == 'GE')
							$OUT_GE		= $CASH_OUT;
						elseif($ITM_GROUP == 'I')
							$OUT_I		= $CASH_OUT;
						
						$OUT_MT			= $OUT_MT + $OUT_M;
						$OUT_UT			= $OUT_UT + $OUT_U;
						$OUT_TT			= $OUT_TT + $OUT_T;
						$OUT_SCT		= $OUT_SCT + $OUT_SC;
						$OUT_OT			= $OUT_OT + $OUT_O;
						$OUT_GET		= $OUT_GET + $OUT_GE;
						$OUT_IT			= $OUT_IT + $OUT_I;
							
						$CASH_GSALDO	= $OUT_M + $OUT_U + $OUT_T + $OUT_SC + $OUT_O + $OUT_GE + $OUT_I;
						$CASH_GSALDOT	= $CASH_GSALDOT + $CASH_GSALDO;
						
						$Other_Desc		= $rowCPRJ->Other_Desc;
						$JournalH_Desc	= $rowCPRJ->JournalH_Desc;
						if($Other_Desc == '')
						{
							$Other_Desc = $JournalH_Desc;
						}
						//if($CASH_IN > 0 || $CASH_OUT > 0)
						//{
							$therow			= $therow + 1;
							?>
							<tr>
								<td nowrap style="text-align:center;"><?php echo "$therow."; ?></td>
								<td nowrap style="text-align:center;"><?php echo "$JournalH_Date"; ?></td>
								<td nowrap style="text-align:left;"><?php echo "$Manual_No"; ?></td>
								<td nowrap style="text-align:left;">
									<?php
										$Other_Desc	= $Other_Desc;
										$totAbj	= strlen($Other_Desc);
										if($totAbj > 100)
										{
											$remAbj		= $totAbj - 100;
											$Other_Desc1= substr($Other_Desc,0,100);
											$Other_Desc2= substr($Other_Desc,-$remAbj);
											$Other_Desc	= $Other_Desc1."<br>".$Other_Desc2;
										}
										echo "$Other_Desc";
									?>
                                </td>
								<td nowrap style="text-align:left;">&nbsp;</td>
								<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($CASH_IN, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($CASH_OUT, 2); ?></td>
								<?php
									$CASH_SALDO	= $CASH_SALDO + $CASH_IN - $CASH_OUT;
								?>
								<td nowrap style="text-align:right;"><?php echo number_format($CASH_SALDO, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_M, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_U, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_T, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_SC, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_O, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_GE, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($OUT_I, 2); ?></td>
								<td nowrap style="text-align:right;"><?php echo number_format($CASH_GSALDO, 2); ?></td>
							</tr>
							<?php
							$OUT_M 		= 0;
							$OUT_U 		= 0;
							$OUT_T 		= 0;
							$OUT_SC 	= 0;
							$OUT_O 		= 0;
							$OUT_GE 	= 0;
							$OUT_I 		= 0;
						//}
					endforeach;
					$CASH_SALDO	= $CASH_INTOT - $CASH_OUTTOT;
				?>
                <tr>
                    <td colspan="6" nowrap style="text-align:center; font-weight:bold">SUB TOTAL</td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASH_INTOT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASH_OUTTOT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASH_SALDO, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_MT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_UT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_TT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_SCT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_OT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_GET, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($OUT_IT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASH_GSALDOT, 2); ?></td>
                </tr>
                <?php
					$GTOT_INOB	= $TOT_INOB + $CASH_INTOT;
					$GTOT_OUTOB	= $TOT_OUTOB + $CASH_OUTTOT;
					$GCASH_SALDO= $GTOT_INOB - $GTOT_OUTOB;
					
					$GOUT_MT	= $OUT_MTX + $OUT_MT;
					$GOUT_UT	= $OUT_UTX + $OUT_UT;
					$GOUT_TT	= $OUT_TTX + $OUT_TT;
					$GOUT_SCT	= $OUT_SCTX + $OUT_SCT;
					$GOUT_OT	= $OUT_OTX + $OUT_OT;
					$GOUT_GET	= $OUT_GETX + $OUT_GET;
					$GOUT_IT	= $OUT_ITX + $OUT_IT;
					
					$GCASH_GSALDOT	= $GOUT_MT + $GOUT_UT + $GOUT_TT + $GOUT_SCT + $GOUT_OT + $GOUT_GET +$GOUT_IT;
				?>
                <tr style="font-weight:bold">
                    <td colspan="6" nowrap style="text-align:center; font-weight:bold">TOTAL</td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GTOT_INOB, 2); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GTOT_OUTOB, 2); ?></td>
                    <td nowrap style="text-align:right;"><?php echo number_format($GCASH_SALDO, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_MT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_UT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_TT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_SCT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_OT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_GET, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GOUT_IT, 2); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GCASH_GSALDOT, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="17" nowrap style="text-align:center;">
                    	<table width="100%" border="0">
                            <tr>
                                <td width="25%" nowrap style="text-align:left">&nbsp;</td>
                                <td width="1%" nowrap style="text-align:left">&nbsp;</td>
                                <td width="13%" nowrap style="text-align:right">&nbsp;</td>
                                <td width="61%" nowrap style="text-align:left">&nbsp;</td>
                          	</tr>
                            <tr>
                              <td nowrap style="text-align:left">Sisa Saldo Minggu Lalu</td>
                              <td nowrap style="text-align:left">:</td>
                              <td nowrap style="text-align:right"><?php echo number_format($TOT_BKRED, 2); ?></td>
                              <td nowrap style="text-align:left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:left">Tambahan Saldo Minggu Ini</td>
                              <td nowrap style="text-align:left">:</td>
                              <td nowrap style="text-align:right"><?php echo number_format($CASH_INTOT, 2); ?></td>
                              <td nowrap style="text-align:left">( + )</td>
                            </tr>
                            <?php
								$TOTSALDO	= $TOT_BKRED + $CASH_INTOT;
							?>
                            <tr style="font-weight:bold">
                              <td nowrap style="text-align:left">Total Saldo</td>
                              <td nowrap style="text-align:left">:</td>
                              <td nowrap style="text-align:right"><?php echo number_format($TOTSALDO, 2); ?></td>
                              <td nowrap style="text-align:left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:left">Total Pengeluaran</td>
                              <td nowrap style="text-align:left">:</td>
                              <td nowrap style="text-align:right"><?php echo number_format($CASH_OUTTOT, 2); ?></td>
                              <td nowrap style="text-align:left">( - )</td>
                            </tr>
                            <?php
								$TOTSALDO2	= $TOTSALDO - $CASH_OUTTOT;
							?>
                            <tr style="font-weight:bold">
                              <td nowrap style="text-align:left">Total Saldo Minggu Ini</td>
                              <td nowrap style="text-align:left">:</td>
                              <td nowrap style="text-align:right"><?php echo number_format($TOTSALDO2, 2); ?></td>
                              <td nowrap style="text-align:left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td nowrap style="text-align:left">&nbsp;</td>
                              <td nowrap style="text-align:left">&nbsp;</td>
                              <td nowrap style="text-align:left">&nbsp;</td>
                              <td nowrap style="text-align:left">&nbsp;</td>
                            </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="17" nowrap style="text-align:center;">
                    	<table width="100%" border="1" rules="all">
                            <tr>
                                <td width="36%" nowrap style="text-align:left; border-top:hidden; border-left:hidden; border-bottom:hidden" height="70px">
                                Dibuat Oleh : <b>Adm Umum &  Keuangan Proyek</b><br><br>
                                Paraf : <br><br>
                                Tanggal :
                                </td>
                                <td width="35%" nowrap style="text-align:left; border-top:hidden; border-bottom:hidden" height="70px">
                                Diperiksa Oleh : <b>Kepala Proyek</b><br><br>
                                Paraf : <br><br>
                                Tanggal :
                                </td>
                                <td width="29%" nowrap style="text-align:left; border-top:hidden; border-bottom:hidden; border-right:hidden" height="70px">
                                Diverifikasi Oleh : <b>Keuangan Pusat</b><br><br>
                                Paraf : <br><br>
                                Tanggal :
                                </td>
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