<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Februari2017
 * File Name	= op_view_journal.php
 * Location		= -
*/
?>
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

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
// Start : Query ini wajib dipasang disetiap halaman View

/*$sql = "SELECT * FROM tbl_language WHERE Language_Status = 1";
$reslang = $this->db->query($sql)->result();
foreach($reslang as $row) :
	$Language_ID	= $row->Language_ID;
endforeach;*/

/*$sql = "SELECT code_translate, transalate_$Language_ID as myTransalte FROM tbl_translate";
$restrans = $this->db->query($sql)->result();
foreach($restrans as $row) :
	$code_translate	= $row->code_translate;
	if($code_translate == 'Nomor')$Nomor = $row->myTransalte;
	if($code_translate == 'VendCode')$VendCode = $row->myTransalte;
	if($code_translate == 'VendName')$VendName = $row->myTransalte;
	if($code_translate == 'Phone')$Phone = $row->myTransalte;
	if($code_translate == 'VendAddress')$VendAddress = $row->myTransalte;
	if($code_translate == 'materialbudgetreport')$materialbudgetreport = $row->myTransalte;
	if($code_translate == 'nodata')$nodata = $row->myTransalte;
endforeach;*/
// End : Query ini wajib dipasang disetiap halaman View
/*if($DisplayType == 'excel')
{
	$dateNow = date('Ymd');
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=BankGuarantee_$dateNow.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}*/

// CREATE FOR TRACER ID
$TRXUSER	= '';
$APPRUSR	= '';
//$odbc		= odbc_connect ("DBaseNKE3", "" , "");
$odbc 		= odbc_connect ('DBaseNKE4', '', '') or die('Could Not Connect to ODBC Database!');
/*$getTID		= "SELECT TRXUSER, APPRUSR FROM OP_HD WHERE OP_CODE = '$OP_CODE'";
$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
while($vTID = odbc_fetch_array($qTID))
{
	$TRXUSER		= $vTID['TRXUSER'];
	$APPRUSR		= $vTID['APPRUSR'];
}*/
$SPLDESC		= '';
$PRJNAME		= '';

$sql2 			= "SELECT PRJNAME FROM project WHERE PRJCODE = '$PRJCODE'";
$result2 		= $this->db->query($sql2)->result();
foreach($result2 as $row2) :
	$PRJNAME 	= $row2 ->PRJNAME;
endforeach;
						
date_default_timezone_set("Asia/Jakarta"); 
$myYear 		= date('y');
$myMonth 		= date('m');
$myDays 		= date('d');
$myHours 		= date('H');
$myMinutes 		= date('i');
$mySeconds 		= date('s');
$CreaterNm 		= getenv("username");
$CreaterNm2		= str_replace('$', '', $CreaterNm);
$localIP 		= getHostByName(php_uname('n'));
$localIP2 		= str_replace('.', '', $localIP);
$Creater_Code	= "TR$myMonth$myYear$myDays$myHours$myMinutes$mySeconds";
if($TRXUSER == '')
{
	$TRXUSER = "XX";
}
if($APPRUSR == '')
{
	$APPRUSR = "XX";
}

$TRXDATESPP		= $TRXDATE;
/*$hasilSPP		= "SELECT TRXDATE FROM SPPHD.DBF WHERE SPPCODE = '$SPPCODE'";
$qrhasilSPP		= odbc_exec($odbc, $hasilSPP) or die (odbc_errormsg());
while($hasilSPP = odbc_fetch_array($qrhasilSPP))
{
	$TRXDATESPP	= $hasilSPP['TRXDATE'];
}*/
$sqlspp		= "SPPHD WHERE SPPCODE = '$SPPCODE'";
$sqlspp		= $this->db->count_all($sqlspp);
if($sqlspp > 0)
{
	$sqlsp4		= "SELECT TRXDATE FROM spphd WHERE SPPCODE = '$SPPCODE'";
	$sqlsp4R	= $this->db->query($sqlsp4)->result();
	foreach($sqlsp4R as $rowsp4) :
		$TRXDATESPP		= $rowsp4->TRXDATE;
	endforeach;
}
else
{
	$dbSPL=dbase_open('C:/DBaseNKE/SDBP/SPPHD.DBF',0);
	$jmlSPL=dbase_numrecords($dbSPL);		
	for($xSPL=1;$xSPL<=$jmlSPL;$xSPL++)
	{
		$hasilSPL		= dbase_get_record_with_names($dbSPL,$xSPL);
		$SPPCODEX		= $hasilSPL['SPPCODE'];
		$TRXDATESPP		= $hasilSPL['TRXDATE'];
		if(trim($SPPCODE) == trim($SPPCODEX))
		{
			break;
		}
	}
}
/*$sqlSPP		= "SELECT TRXDATE FROM SPPHD WHERE SPPCODE = '$SPPCODE'";
$resSPP		= $this->db->query($sqlSPP)->result();
foreach($resSPP as $hasilSPP) :
	$TRXDATESPP		= $hasilSPP->TRXDATE;
endforeach;*/

$sqlsp3		= "supplier WHERE SPLCODE = '$SPLCODE'";
$sqlsp3R	= $this->db->count_all($sqlsp3);
if($sqlsp3R > 0)
{
	$sqlsp4		= "SELECT SPLCODE, SPLDESC, SPLADD1, SPLTELP, SPLNPWP FROM supplier WHERE SPLCODE = '$SPLCODE'";
	$sqlsp4R	= $this->db->query($sqlsp4)->result();
	foreach($sqlsp4R as $rowsp4) :
		$SPLCODE		= $rowsp4->SPLCODE;
		$SPLDESC		= $rowsp4->SPLDESC;
		$SPLADD1		= $rowsp4->SPLADD1;
		$SPLTELP		= $rowsp4->SPLTELP;
		$SPLNPWP		= $rowsp4->SPLNPWP;
	endforeach;
}
else
{
	/*$dbSPL=dbase_open('C:/DBaseNKE/SDBPN/SUPPLIER.DBF',0);
	$jmlSPL=dbase_numrecords($dbSPL);
			
	for($xSPL=1;$xSPL<=$jmlSPL;$xSPL++)
	{
		$hasilSPL		= dbase_get_record_with_names($dbSPL,$xSPL);
		$SPLCODEX		= $hasilSPL['SPLCODE'];
		$SPLDESC		= $hasilSPL['SPLDESC'];
		$SPLADD1		= $hasilSPL['SPLADD1'];
		$SPLTELP		= $hasilSPL['SPLTELP'];
		$SPLNPWP		= $hasilSPL['SPLNPWP'];
		if(trim($SPLCODE) == trim($SPLCODEX))
		{
			break;
		}
	}*/
	$getHD		= "SELECT SPLCODE, SPLDESC, SPLADD1, SPLTELP, SPLNPWP
						FROM SUPPLIER.DBF
					WHERE SPLCODE = '$SPLCODE'";
	$qrHD		= odbc_exec($odbc, $getHD) or die (odbc_errormsg());
	while($vHD	= odbc_fetch_array($qrHD))
	{
		$SPLDESC	= $vHD['SPLDESC'];
		$SPLADD1	= $vHD['SPLADD1'];
		$SPLTELP	= $vHD['SPLTELP'];
		$SPLNPWP	= $vHD['SPLNPWP'];
	}
}

$Creater_Desc	= "$TRXUSER - $APPRUSR - $CreaterNm2 - $localIP";
$Creater_Desc2	= "$Creater_Code$localIP2-$TRXUSER$APPRUSR$CreaterNm2";

$sqlInsHist 	= "INSERT INTO tbl_trackcreater (Creater_Code, Creater_Desc) VALUES ('$Creater_Code', '$Creater_Desc')";		
$this->db->query($sqlInsHist);

$sqlgetLast 	= "tbl_printdoc WHERE printdoc_Ref = '$OP_CODE'";
$restgetLast 	= $this->db->count_all($sqlgetLast);
if($restgetLast > 0)
{
	$isOri = "Copy";
	$image	= "printCopy.png";
}
else
{
	$isOri 	= "Original";
	$image	= "printOriginal.png";
	$sqlInsert = "INSERT INTO tbl_printdoc (printdoc_Ref, printdoc_Cat, printdoc_No, printdoc_Notes)
				VALUES ('$OP_CODE', 'OP', 1, '$Creater_Code')";
	$this->db->query($sqlInsert);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'assets/css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'assets/css/style.css'; ?>");</style>
<style type="text/css">
@import url("<?php echo base_url() . 'assets/css/style_table.css'; ?>");
</style>
<?php /*?><script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script><?php */?>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">
<style>
	.inplabel {border:none;background-color:white;}
	.inpdim {border:none;background-color:white;}
</style>
<table width="100%" border="0" style="size:auto">
    <tr>
    	<td width="31%">
     		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
        <td colspan="5" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="19%" class="style2" style="text-align:center; font-size:24px"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
  	</tr>
    <tr>
    	<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td colspan="5" class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="4" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:9px">
        	<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="200" height="50" /><br />
            Jalan Sunan Kalijaga No. 64 Jakarta 12160, Indonesia<br />
            Telp : (62 21) 7221003, Fax. (62 21) 7396580		</td>
      	<td width="1%" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">&nbsp;</td>
      	<td colspan="4" nowrap class="style2" style="text-align:left; font-weight:bold; text-transform:uppercase; font-size:22px">ORDER PEMBELIAN (OP)</td>
      <td nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">&nbsp;</td>
      	<td width="8%" valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>Nomor Order</td>
      	<td width="11%" valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>: <?php echo $OP_CODE; ?></td>
      	<td width="7%" valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>Tanggal Order</td>
      	<td width="23%" valign="top" class="style2" style="text-align:left; font-size:12px">: <?php echo $TRXDATE; ?></td>
      	<td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">&nbsp;</td>
  </tr>
    <tr>
      <td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">&nbsp;</td>
      	<td valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>Nomor SPP/P</td>
      	<td valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>: <?php echo $SPPCODE; ?></td>
      	<td valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>Tanggal SPP/P</td>
      	<td valign="top" class="style2" style="text-align:left; font-size:12px">:  <?php echo $TRXDATESPP; ?></td>
      	<td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px">&nbsp;</td> 
      <td valign="top" class="style2" style="text-align:left; font-size:12px" nowrap>Kode Proyek</td>
      <td colspan="3" valign="top" class="style2" style="text-align:left; font-size:12px">: <?php echo "$PRJCODE - $PRJNAME"; ?></td>
      	<td class="style2" style="text-align:left;">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="7" class="style2" style="text-align:center">&nbsp;</td>
  	</tr>
    <tr>
        <td colspan="7" class="style2">
        	<table width="100%" border="1">
                <tr style="background:#CCCCCC">
                    <td width="10%" style="text-align:left; font-weight:bold; border-right:none; border-bottom:none" nowrap>
                    	KEPADA YTH. <br />
                        ALAMAT
                    </td>
                    <td width="40%" style="text-align:left; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none">
                    	: <?php echo "$SPLCODE - $SPLDESC"; ?><br />
                        : <?php echo $SPLADD1; ?>
                    </td>
                    <td width="50%" style="text-align:left; font-weight:bold;" valign="top">MOHON DIKIRIM KE : </td>
                </tr>
                <tr>
                    <td colspan="3" nowrap style="text-align:center">
                        <table width="100%" border="0">
                            <tr style="font-size:11px">
                                <td colspan="9" style="font-weight:bold; border-right:none; border-bottom:none; border-top:none; border-top-color:#FFF">&nbsp;</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td width="2%" style="font-weight:bold; border-right:none; border-bottom:none; border-top:none; border-top-color:#FFF">NO.</td>
                                <td width="6%" style="text-align:left; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none">KODE</td>
                                <td width="36%" style="text-align:center; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none">KETERANGAN</td>
                                <td width="7%" style="text-align:right; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none" nowrap>VOLUME</td>
                                <td width="7%" style="text-align:center; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none">SAT</td>
                                <td width="12%" style="text-align:right; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none" nowrap>HARGA SATUAN</td>
                                <td width="9%" style="text-align:right; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none" nowrap>DSC %</td>
                                <td width="10%" style="text-align:right; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none" nowrap>DISCOUNT</td>
                                <td width="11%" style="text-align:right; font-weight:bold; font-weight:bold;border-left:none;border-right:none;border-bottom:none" nowrap>JUMLAH NET</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td colspan="9" style="font-weight:bold; border-right:none; border-bottom:none; border-top:none; border-top-color:#FFF"><hr /></td>
                            </tr>
								<?php					
                                    $totCSTCOST2	= 0;
                                    $sqlDT1R 		= 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                                    if($sqlDT1R > 0)
                                    {
                                        $totCSTCOST2	= 0;
                                        $therow			= 0;
                                        
                                        /*$sql2 			= "SELECT A.OP_CODE, A.CSTCODE, A.CSTUNIT, A.OP_VOLM, A.CSTPUNT, A.CSTCOST, A.CSTDISP, A.CSTDISC,
																A.OP_DESC, A.OP_DES1 
                                                            FROM OP_DT A
                                                                INNER JOIN OP_HD B ON A.OP_CODE = B.OP_CODE		
                                                            WHERE B.OP_CODE = '$OP_CODE'";
                                        $result2 		= $this->db->query($sql2)->result();
                                        foreach($result2 as $row2) :
                                            $therow			= $therow + 1;
                                            $OP_CODE 		= $row2 ->OP_CODE;
                                            $CSTCODE 		= $row2 ->CSTCODE;
                                            $CSTUNIT 		= $row2 ->CSTUNIT;
                                            $OP_VOLM 		= $row2 ->OP_VOLM;
                                            $CSTPUNT 		= $row2 ->CSTPUNT;
                                            $CSTCOST 		= $row2 ->CSTCOST;
                                            $CSTDISP 		= $row2 ->CSTDISP;
                                            $CSTDISC 		= $row2 ->CSTDISC;
                                            $CSTCOSTFINAL	= $CSTCOST - $CSTDISC;											
                                            $OP_DESC 		= $row2 ->OP_DESC;
                                            $OP_DES1 		= $row2 ->OP_DES1;
                                            $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;*/
											
										$hasilLPMDT		= "SELECT OP_CODE, CSTCODE, CSTUNIT, OP_VOLM, CSTPUNT, 
																CSTCOST, CSTDISP, CSTDISC, OP_DESC, OP_DES1 
															FROM OP_DT.DBF
															WHERE OP_CODE = '$OP_CODE'";
										$qrLPMDT		= odbc_exec($odbc, $hasilLPMDT) or die (odbc_errormsg());
										while($getLPMDT = odbc_fetch_array($qrLPMDT))
										{
                                            $therow			= $therow + 1;
                                            $OP_CODE 		= $getLPMDT['OP_CODE'];
                                            $CSTCODE 		= $getLPMDT['CSTCODE'];
                                            $CSTUNIT 		= $getLPMDT['CSTUNIT'];
                                            $OP_VOLM 		= $getLPMDT['OP_VOLM'];
                                            $CSTPUNT 		= $getLPMDT['CSTPUNT'];
                                            $CSTCOST 		= $getLPMDT['CSTCOST'];
                                            $CSTDISP 		= $getLPMDT['CSTDISP'];
                                            $CSTDISC 		= $getLPMDT['CSTDISC'];
                                            $CSTCOSTFINAL	= $CSTCOST - $CSTDISC;											
                                            $OP_DESC 		= $getLPMDT['OP_DESC'];
                                            $OP_DES1 		= $getLPMDT['OP_DES1'];
                                            $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                            
                                            $DISCPERC		= $CSTDISP;
                                            $DISCVAL		= $CSTDISC;
                                            ?>
                                                <tr style="font-size:10px">
                                                    <td style="border-right:none; border-bottom:none"><?php echo $therow; ?>.</td>
                                                    <td style="border-left:none;border-right:none;border-bottom:none"><?php echo $CSTCODE; ?></td>
                                                    <td style="border-left:none;border-right:none;border-bottom:none"><?php echo $OP_DES1; ?></td>
                                                    <td style="text-align:right; border-left:none;border-right:none;border-bottom:none" nowrap><?php echo number_format($OP_VOLM, $decFormat); ?></td>
                                                    <td style="text-align:center; border-left:none;border-right:none;border-bottom:none" nowrap><?php echo $CSTUNIT; ?></td>
                                                    <td style="text-align:right; border-left:none;border-right:none;border-bottom:none" nowrap><?php echo number_format($CSTPUNT, $decFormat); ?></td>
                                                    <td style="text-align:right; border-left:none;border-right:none;border-bottom:none" nowrap><?php echo number_format($DISCPERC, $decFormat); ?></td>
                                                    <td style="text-align:right; border-left:none;border-right:none;border-bottom:none" nowrap><?php echo number_format($DISCVAL, $decFormat); ?></td>
                                                    <td style="text-align:right; border-left:none;border-right:none;border-bottom:none" nowrap><?php echo number_format($CSTCOST, $decFormat); ?></td>
                                                </tr>
                                            <?php
										}
                                    }
									$terbilang = $moneyFormat->terbilang($totCSTCOST2);
                                ?>
                            <tr style="font-size:11px">
                                <td colspan="9" style="font-weight:bold; border-right:none; border-bottom:none; border-top:none; border-top-color:#FFF"><hr /></td>
                            </tr>
                            <tr style="font-size:10px; text-align:left">
                                <td colspan="7" style="font-weight:bold">TERBILANG : "<?php echo $terbilang; ?> Rupiah"</td>
                                <td style="font-weight:bold; text-align:right">TOTAL</td>
                                <td style="font-weight:bold; text-align:right"><?php echo number_format($totCSTCOST2, $decFormat); ?></td>
                            </tr>
                            <tr style="font-size:10px; text-align:left">
                              <td colspan="7">&nbsp;</td>
                              <td style="font-weight:bold; text-align:right">&nbsp;</td>
                              <td style="font-weight:bold; text-align:right">&nbsp;</td>
                            </tr>	
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" nowrap style="text-align:center">
                    	<table width="100%" border="0">
                            <tr style="font-size:10px">
                                <td nowrap>
                                    <font style="font-weight:bold"> CATATAN :</font><br />
                                    <font style="font-weight:bold"> 1.	Pengiriman paling lambat tanggal : <?php echo $DELIVDT; ?></font><br />
                                    <font style="font-weight:bold"> 2.	Syarat Pembayaran : <?php echo $CONDITI; ?></font><br />
                                    <font style="font-weight:bold"> 3.	Order Pembelian (OP) ini tidak berlaku dan Tagihan tidak dapat dibayar, bila :</font><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;-Tidak mencantumkan No. Order ini ke dalam Surat Jalan & Nota / Faktur tagihan atau Invoice.<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;-Pesanan dikirim tidak sesuai dengan Spesifikasi Mutu, Harga dan jumlah yang tertulis disini.<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;-Tidak memenuhi persyaratan K3 (Peraturan Pemerintah No. 50 Tahun 2012)<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;-Surat jalan tidak tertulis No. GUDANG dan No.LAPORAN PENERIMAAN MATERIAL (LPM) dari Proyek.<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;-Surat Jalan asli hilang dan Tanda Terima Kwitansi (TTK) asli hilang.<br />
                                    <font style="font-weight:bold"> 4.	Tagihan Disampaikan dengan menyerahkan :</font><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;- Kuitansi Bermeterai,	- Faktur  PPN  (Bila ada),<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;- Nota / Faktur Tagihan atau Invoice,	- Berkas² lain yang diperlukan untuk kelengkapan tagihan.<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;- Surat Jalan asli dan Order Pembelian (OP) asli,<br />
                                </td>
                                <td valign="top" nowrap>
                                    <br />
                                    <font style="font-weight:bold"> 5.	Keterlambatan </font><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;Keterlambatan Pengiriman Dikenakan Penalty <br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;0,1% per hari, maksimum 5% dari nilai pemesanan <br /><br />
                                    <font style="font-weight:bold"> 6.	Kode item </font><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;Kode item OP diatas diisi sesuai  kode item SPP/P  <br /><br />
                                    <font style="font-weight:bold"> 7.	Perubahan OP </font><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;Jika OP batal / berubah, OP ditarik, diganti OP baru <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>	
      		</table> 
   		</td>
  	</tr>
    <tr>
		<td colspan="7" valign="top" class="style2">
        	<table width="100%" border="0">
                <tr style="font-size:10px; text-align:center">
                    <td width="35%" nowrap>
                   	  <table width="239">
                    	<tr>
                       	  <td style="text-align:center">Diajukan,</td>
                        </tr>
                    </table>
                    </td>
                    <td width="30%" nowrap>
                   	  <table width="239">
                    	<tr>
                       	  <td style="text-align:center">Menyetuji,</td>
                        </tr>
                    </table>
                    </td>
                    <td width="35%" valign="top" nowrap>
                    	<table width="239">
                    	<tr>
                       	  <td style="text-align:center">Pernyataan Setuju (Supplier),</td>
                        </tr>
                    </table>
                    </td>
            	</tr>
                <tr style="font-size:10px">
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td valign="top" nowrap>&nbsp;</td>
                </tr>
                <tr style="font-size:10px">
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td valign="top" nowrap>&nbsp;</td>
                </tr>
                <tr style="font-size:10px">
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td valign="top" nowrap>&nbsp;</td>
                </tr>
                <tr style="font-size:10px; text-align:center">
                  <td nowrap>
                  	<table width="239">
                    	<tr>
                        	<td>Nama :<br /><hr />Jab. :</td>
                        </tr>
                    </table>
                  </td>
                  <td nowrap>
                  	<table width="239">
                    	<tr>
                        	<td>Nama :<br /><hr />Jab. :</td>
                        </tr>
                    </table>
                  </td>
                  <td valign="top" nowrap>
                  	<table width="239">
                    	<tr>
                        	<td>Nama :<br /><hr />Jab. :</td>
                        </tr>
                    </table>
                  </td>
                </tr>
         	</table>
        </td>
  	</tr>    
    <tr>
      <td colspan="7" class="style2" style="font-size:8px; font-weight:bold">
	  <?php 
	  	//echo "$Creater_Desc2";
	  ?>      </td>
    </tr>
    <tr>
      <td colspan="7" class="style2">&nbsp;</td>
    </tr>
</table>
</body>
</html>
