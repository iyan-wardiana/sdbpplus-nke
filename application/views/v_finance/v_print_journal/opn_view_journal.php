<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 April 2015
 * File Name	= r_matrequest_report.php
*/
?>
<?php
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
// Start : Query ini wajib dipasang disetiap halaman View
$sql = "SELECT * FROM tbl_language WHERE Language_Status = 1";
$reslang = $this->db->query($sql)->result();
foreach($reslang as $row) :
	$Language_ID	= $row->Language_ID;
endforeach;

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
$odbc 		= odbc_connect ("DBaseNKE3", "" , "");
$getTID		= "SELECT PRJCODE, TRXUSER FROM OPNHD.DBF WHERE OPNCODE = '$OPNCODE'";
$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
while($vTID = odbc_fetch_array($qTID))
{
	$PRJCODE		= $vTID['PRJCODE'];
	$PRJCODE1		= $vTID['PRJCODE'];
	$TRXUSER		= $vTID['TRXUSER'];
	$APPRUSR		= '';
}
					
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

/*$hasilDTSUP		= "SELECT SPLCODE, SPLDESC, SPLTELP, SPLNPWP FROM SUPPLIER.DBF WHERE SPLCODE = '$SPLCODE'";
$qrhasilSUP		= odbc_exec($odbc, $hasilDTSUP) or die (odbc_errormsg());
while($hasilSUP = odbc_fetch_array($qrhasilSUP))
{
	$SPLCODE	= $hasilSUP['SPLCODE'];
	$SPLDESC	= $hasilSUP['SPLDESC'];
	$SPLTELP	= $hasilSUP['SPLTELP'];
	$SPLNPWP	= $hasilSUP['SPLNPWP'];
}*/
$sqlsp3	= "tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$sqlsp3R= $this->db->count_all($sqlsp3);
if($sqlsp3R > 0)
{
	$sqlsp4		= "SELECT SPLCODE, SPLDESC, SPLTELP, SPLNPWP FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
	$sqlsp4R	= $this->db->query($sqlsp4)->result();
	foreach($sqlsp4R as $rowsp4) :
		$SPLCODE		= $rowsp4->SPLCODE;
		$SPLDESC		= $rowsp4->SPLDESC;
		$SPLTELP		= $rowsp4->SPLTELP;
		$SPLNPWP		= $rowsp4->SPLNPWP;
	endforeach;
}
else
{
	$dbSPL=dbase_open('C:/DBaseNKE/SDBPN/SUPPLIER.DBF',0);
	$jmlSPL=dbase_numrecords($dbSPL);
			
	for($xSPL=1;$xSPL<=$jmlSPL;$xSPL++)
	{
		$hasilSPL		= dbase_get_record_with_names($dbSPL,$xSPL);
		$SPLCODEX		= $hasilSPL['SPLCODE'];
		$SPLDESC		= $hasilSPL['SPLDESC'];
		$SPLTELP		= $hasilSPL['SPLTELP'];
		$SPLNPWP		= $hasilSPL['SPLNPWP'];
		if(trim($SPLCODE) == trim($SPLCODEX))
		{
			break;
		}
	}
}				

$Creater_Desc	= "$TRXUSER - $APPRUSR - $CreaterNm2 - $localIP";
$Creater_Desc2	= "$Creater_Code$localIP2-$TRXUSER$APPRUSR$CreaterNm2";

$sqlInsHist 	= "INSERT INTO tbl_trackcreater (Creater_Code, Creater_Desc) VALUES ('$Creater_Code', '$Creater_Desc')";		
$this->db->query($sqlInsHist);

$sqlgetLast 	= "tbl_printdoc WHERE printdoc_Ref = '$OPNCODE'";
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
						VALUES ('$OPNCODE', 'OPN', 1, '$Creater_Code')";
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
    	<td width="17%">
     		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
      <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
        <td width="65%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
      <td width="18%" class="style2" style="text-align:center; font-weight:bold"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
  </tr>
    <tr>
    	<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="230" height="50"></td>
        <td nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:18px">OPNAME PEKERJAAN</td>
   	    <td nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:18px">&nbsp;</td>
    </tr>
    <tr>
      	<td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:15px">Nomor : <?php echo $OPNCODE; ?></td>
        <td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:15px">&nbsp;</td>
  	</tr>
    <tr>
        <td height="10" valign="top" class="style2" style="text-align:center; font-weight:bold">Waktu Pelaksanaan : <?php echo "$sttgl/$stbln/$stthn s.d. $endtgl/$endbln/$endthn"; ?></td>
        <td height="10" valign="top" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <?php
		// Project
		/*$odbc = odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
		$strsql= "SELECT PRJCODE, PRJCODE FROM OPNHD.DBF WHERE SPKCODE = '$SPKCODE'";
		$query = odbc_exec($odbc, $strsql) or die (odbc_errormsg());
		while($row1 = odbc_fetch_array($query))
		{
			$PRJCODE1 	= $row1['PRJCODE'];
			$PRJCODE1 	= $row1['PRJCODE'];
		}
		$mySPLCode = $SPLCODE;*/
		
		/*$DBFname = "SUPPLIER.DBF";
		$db=dbase_open('F:/NKE/SDBP/'.$DBFname,0);
		$jumlah=dbase_numrecords($db);
		
		for($x=1;$x<=$jumlah;$x++)
		{
			$hasilDT=dbase_get_record_with_names($db,$x);
			$SPLCODE		= $hasilDT['SPLCODE'];
			$SPLDESC		= $hasilDT['SPLDESC'];
			$SPLTELP		= $hasilDT['SPLTELP'];
			$SPLNPWP		= $hasilDT['SPLNPWP'];
			if(trim($SPLCODE) == trim($mySPLCode))
			{
				break;
			}
		}*/						
		
		/*$strsql3= "SELECT * FROM SUPPLIER.DBF WHERE SPLCODE = '$SPLCODE'";
		$query13 = odbc_exec($odbc, $strsql3) or die (odbc_errormsg());
		while($row3 = odbc_fetch_array($query13))
		{
			$SPLCODE = $row3['SPLCODE'];
			$SPLDESC = $row3['SPLDESC'];
			$SPLTELP = $row3['SPLTELP'];
			$SPLNPWP = $row3['SPLNPWP'];
		}
		odbc_close($odbc);*/
		
		/*$odbc 		= odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
		$strsqlSpl	= "SELECT * FROM SUPPLIER.DBF WHERE SPLCODE LIKE '%$splCode%'";
		$querySpl = odbc_exec($odbc, $strsqlSpl) or die (odbc_errormsg());
			
		while($rowSpl = odbc_fetch_array($querySpl))
		{
			$SPLCODE = $rowSpl['SPLCODE'];
			$SPLDESC = $rowSpl['SPLDESC'];
			$SPLTELP = $rowSpl['SPLTELP'];
			$SPLNPWP = $rowSpl['SPLNPWP'];
		}
		odbc_close($odbc);*/
		
		// Rounding Detail
		// Menggunakan direct DBF
		$totPage = 10; // ACAK
		if($totPage <= 10)
		{
			$totPage = "001";
		}
		else
		{
			$totPage = "002";
		}
	?>
    <tr style="font-size:10px">
      	<td class="style2" style="text-align:left;font-style:italic" valign="top">&nbsp;</td>
      	<td class="style2" style="text-align:right;">Hal : 001/<?php echo $totPage; ?></td>
    </tr>  
	<?php
        // Get Data Project
        $myPROJCode = $PRJCODE;
		
		// GET PROJECT
		$sqlspPRJ		= "tbl_project WHERE PRJCODE = '$PRJCODE'";
		$sqlspPRJ		= $this->db->count_all($sqlspPRJ);
		if($sqlspPRJ > 0)
		{
			$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$sqlR		= $this->db->query($sql)->result();
			foreach($sqlR as $rowR) :
				$PRJCODE		= $rowR->PRJCODE;
				$PRJNAME		= $rowR->PRJNAME;
				$PRJLOCT		= $rowR->PRJLOCT;
			endforeach;
		}
		else
		{
			$sqlspPRJ		= "PROJ_A3 WHERE PRJCODE = '$PRJCODE'";
			$sqlspPRJ		= $this->db->count_all($sqlspPRJ);
			if($sqlspPRJ > 0)
			{
				$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM PROJ_A3 WHERE PRJCODE = '$PRJCODE'";
				$sqlR		= $this->db->query($sql)->result();
				foreach($sqlR as $rowR) :
					$PRJCODE		= $rowR->PRJCODE;
					$PRJNAME		= $rowR->PRJNAME;
					$PRJLOCT		= $rowR->PRJLOCT;
				endforeach;
			}
			else
			{
				$PRJCODE		= "None";
				$PRJNAME		= "None";
				$PRJLOCT		= "None";
			}
		}
		//odbc_close($odbc);							
        
        // Get SPK
		$strsqlSPK	= "SELECT SPKCODE, SPKTYPE FROM SPKHD.DBF WHERE SPKCODE = '$SPKCODE'";
		$querySPK	= odbc_exec($odbc, $strsqlSPK) or die (odbc_errormsg());
		while($rowSPK = odbc_fetch_array($querySPK))
		{
			$SPKCODE0 	= $rowSPK['SPKCODE'];
			$SPKTYPE 	= $rowSPK['SPKTYPE'];
		}
		//odbc_close($odbc);
        if($SPKTYPE == 2)
        {
            $spkTYPE = "SUBKONT";
        }
        elseif($SPKTYPE == 3)
        {
            $spkTYPE = "MANDOR";
        }
        elseif($SPKTYPE == 1 || $SPKTYPE == 4 || $SPKTYPE == 5)
        {
            $spkTYPE = "PEMASOK";
        }
    ?>
    <tr>
    	<td colspan="3" class="style2" style="text-align:left;">
            <table width="100%" border="1" style="font-size:9px">
                <tr style="font-size:10px">
                    <td width="8%" nowrap style="border-right:none; border-bottom:none">PROYEK</td>
                    <td width="11%" nowrap style="font-weight:bold;font-size:10px;border-left:none;border-right:none;border-bottom:none">: <?php echo "$PRJCODE - $PRJNAME"; ?></td>
                    <td width="10%" style="border-right:none;border-bottom:none"><?php echo $spkTYPE; ?></td>
                    <td colspan="3" style="font-weight:bold;font-size:11px;border-left:none;border-right:none;border-bottom:none">: <?php echo "$SPLCODE - $SPLDESC"; ?></td>
                    <td width="32%" rowspan="6" valign="top" style="font-size:7px" nowrap>
                    CATATAN:<BR />I. KETENTUAN<BR />
                    &nbsp;&nbsp;&nbsp;a) JIKA SPK, OPNAME TIDAK TERISI LENGKAP, DAN KETENTUAN2 SPK LEMBAR SEBALIKNYA TIDAK TERPENUHI,<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPK OPNAME DINYATAKAN TIDAK BERLAKU<br />
                    &nbsp;&nbsp;&nbsp;b) JIKA FORMULIR (ASLI) INI HILANG, SISA TAGIHAN OFNAME TIDAK DAPAT DIBAYARKAN<br />
                    &nbsp;&nbsp;&nbsp;c) JIKA BERKAS LAMPIRAN PERHITUNGAN OPNAME SUBKONT TIDAK ADA/TIDAK JELAS,<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OPNAME TIDAK DAPAT DIBAYAR<br />
                    &nbsp;&nbsp;&nbsp;d) JIKA FORM RETENSI (FORM PENGAKUAN JAMINAN PEMELIHARAN) HILANG, RETENSI TIDAK DAPAT DIBAYAR<br />
                    II. UNTUK PEMASOK<BR />
                    &nbsp;&nbsp;&nbsp;a) SETIAP PENGAMBILAN UANG (OPNAME PEKERJAAN), SPK (ASLI) HARUS DISERTAKAN<br />    
                    &nbsp;&nbsp;&nbsp;b) BILA PEKERJAAN MASIH BERLANGSUNG, SETIAP HABIS PEMBAYARAN, SPK (ASLI) KEMBALI KE MANDOR/<br />                
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUBKONT YANG BERSANGKUTAN<br />
                    &nbsp;&nbsp;&nbsp;c) BILA PEKERJAAN TELAH SELESAI (DENGAN RETENSI):<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) MANDOR/SUB MENYERAHKAN SPK : 1 LEMBAR ASLI, 2 LEMBAR FOTO COPY<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) SEHABIS PEMBAYARAN, BERKAS YANG KEMBALI KE MANDOR/SUB <br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(UNTUK PENCAIRAN RETENSI):-SPK (FOTO COPY NYA) 1 LEMBAR</td>
                </tr>
                <tr style="font-size:9px">
                    <td nowrap style="border-right:none; border-top:none; border-bottom:none">ALAMAT</td>
                    <td style="border-left:none;border-right:none;border-top:none;border-bottom:none">: <?php echo "$PRJLOCT"; ?></td>
                    <td style="border-right:none;border-top:none;border-bottom:none">NO. KTP</td>
                    <td style="border-left:none;border-right:none;border-top:none;border-bottom:none">: <?php echo "$SPLTELP"; ?></td>
                    <td style="border-left:none;border-right:none;border-top:none;border-bottom:none">&nbsp;</td>
                    <td style="border-left:none;border-right:none;border-top:none;border-bottom:none">&nbsp;</td>
                </tr>
                <tr style="font-size:10px">
                    <td style="border-right:none; border-top:none; border-bottom:none">PELAKSANA</td>
                    <td nowrap style="font-weight:bold;font-size:10px;border-left:none;border-right:none;border-top:none;border-bottom:none">: <?php echo "$txtPelaksana"; ?></td>
                  <td nowrap style="border-right:none;border-top:none;border-bottom:none">&nbsp;</td>
                  <td width="9%" nowrap style="border-left:none;border-right:none;border-top:none;border-bottom:none">&nbsp;</td>
                  <td width="9%" nowrap style="border-left:none;border-right:none;border-top:none;border-bottom:none">TELP./HP</td>
                    <td width="21%" nowrap style="border-left:none;border-right:none;border-top:none;border-bottom:none">: <?php echo "$SPLTELP"; ?></td>
                </tr>
                <tr style="font-size:10px">
                    <td nowrap style="border-right:none; border-top:none; border-bottom:none">&nbsp;</td>
                    <td style="font-weight:bold;font-size:10px;border-left:none;border-right:none;border-top:none;border-bottom:none" nowrap>&nbsp;</td>
                  <td nowrap style="border-right:none;border-top:none;border-bottom:none">NO. NPWP</td>
                    <td nowrap style="border-left:none;border-right:none;border-top:none;border-bottom:none">: <?php echo "$SPLNPWP"; ?></td>
                    <td nowrap style="border-left:none;border-right:none;border-top:none;border-bottom:none">&nbsp;</td>
                    <td nowrap style="border-left:none;border-right:none;border-top:none;border-bottom:none">&nbsp;</td>
                </tr>
                <tr style="font-size:9px">
                  <td colspan="2" style="border-left:none;border-right:none;border-bottom:none">Masa berlaku Jaminan Uang Muka, terhitung mulai tanggal</td>
                  <td colspan="4" nowrap style="border-left:none;border-right:none;border-bottom:none">: <?php echo "$sttgl1/$stbln1/$stthn1 s.d. $endtgl1/$endbln1/$endthn1"; ?></td>
                </tr>
                <tr style="font-size:9px">
                  <td colspan="2" style="border-left:none;border-right:none;border-top:none;border-bottom:none">Masa berlaku Jaminan Pelaksanaan, terhitung mulai tanggal</td>
                  <td colspan="4" style="border-left:none;border-right:none;border-top:none;border-bottom:none">: <?php echo "$sttgl2/$stbln2/$stthn2 s.d. $endtgl2/$endbln2/$endthn2"; ?></td>
                </tr>
            </table>
       	</td>
  	</tr>
    <tr>
        <td colspan="3" class="style2">
        	<table width="100%" border="1" style="font-size:10px;">
                <tr style="background:#CCCCCC">
                    <td width="2%" rowspan="4" style="text-align:center; font-weight:bold">No</td>
                    <td width="3%" rowspan="4" style="text-align:center; font-weight:bold">Kode<br />
                    Item</td>
                    <td width="49%" rowspan="4" style="text-align:center; font-weight:bold">URAIAN PEKERJAAN</td>
                    <td colspan="5" style="text-align:center; font-weight:bold">PERIODE OPNAME KE : <?php echo $myStep; ?> </td>
                </tr>
                <?php
					$qGetSPKDet	= "SELECT * FROM tbl_jobopname WHERE SPKCODE = '$SPKCODE' AND OPSTEP = '$myStep'";
					$resultDT = $this->db->query($qGetSPKDet)->result();
					foreach($resultDT as $rowDT) :					
						$STRDATE 	= $rowDT->STRDATE;		
						$ENDDATE 	= $rowDT->ENDDATE;
					endforeach;
					
					//$odbc = odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
					$strsql= "SELECT * FROM OPNHD.DBF WHERE OPNCODE = '$OPNCODE'";
					$query = odbc_exec($odbc, $strsql) or die (odbc_errormsg());
					while($row = odbc_fetch_array($query))
					{
						$STRDATE = $row['TRXDATE'];
						$ENDDATE = $row['TRXPDAT'];
					}
					odbc_close($odbc);
			
					$DateD = date('d', strtotime($STRDATE));
					$DateM = date('m', strtotime($STRDATE));
					$DateY = date('Y', strtotime($STRDATE));
					
					$DateD2 = date('d', strtotime($ENDDATE));
					$DateM2 = date('m', strtotime($ENDDATE));
					$DateY2 = date('Y', strtotime($ENDDATE));
					//return false;
				?>
                <tr style="background:#CCCCCC">
                  <td colspan="5" style="text-align:center; font-weight:bold">PERIODE : <?php echo "$DateD/$DateM/$DateY s.d. $DateD2/$DateM2/$DateY2"; ?></td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td rowspan="2" style="text-align:center; font-weight:bold">Qty</td>
                  <td width="10%" rowspan="2" style="text-align:center; font-weight:bold">Satuan</td>
                  <td width="10%" rowspan="2" style="text-align:center; font-weight:bold">Jumlah<br />Harga</td>
                  <td colspan="2" style="text-align:center; font-weight:bold; border-left:double">KUMULATIF</td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td style="text-align:center; font-weight:bold; border-left:double">Qty</td>
                  <td width="9%" style="text-align:center; font-weight:bold">Jml Harga</td>
                </tr>
                <?php					
					$totCSTCOST2	= 0;
					$sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
					$totalRow = 17;
					//if($sqlDT1R > 0)
					//{						
						// DENGAN DIRECT DBF
						$NoU	= 0;
						$myOPNCode = $OPNCODE;
						$GTOTCSTCOST = 0;
						$GTOTAKUMCOST = 0;
						
						$maxStep = $myStep;
						$TOTAKUMVOL = 0;
						$TOTAKUMCOST = 0;	
						
						$strsqlOPN	= "SELECT OPNCODE, CSTCODE, ACCCODE, OPNDESC, CSTCOST, OPNVOLM, CSTUNIT, CSTPUNT FROM OPNDT.DBF WHERE OPNCODE = '$OPNCODE'";
						$queryOPN 	= odbc_exec($odbc, $strsqlOPN) or die (odbc_errormsg());
						while($rowOPN = odbc_fetch_array($queryOPN))
						{
							$OPNCODE		= $rowOPN['OPNCODE'];
							$OPNCODEDT		= $rowOPN['OPNCODE'];
							$CSTCODE		= $rowOPN['CSTCODE'];
							$ACCCODE		= $rowOPN['ACCCODE'];
							$OPNDESC		= $rowOPN['OPNDESC'];
							$CSTCOST		= $rowOPN['CSTCOST'];
							$OPNVOLM		= $rowOPN['OPNVOLM'];
							$CSTUNIT		= $rowOPN['CSTUNIT'];
							$CSTPUNT		= $rowOPN['CSTPUNT'];
													
							$NoU	= $NoU + 1;
							$TOTAKUMVOL = 0;
							$TOTAKUMCOST = 0;
							for($xa=1;$xa<=$maxStep;$xa++)
							{
								
								$stepx = $xa;
								$lenx = strlen($stepx);
								if($lenx==1) $nolx="0";
								$newOPNCode = $SPKCODE.$nolx.$stepx;
								//echo "$newOPNCode<br>";
								
								//$odbc = odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
								$strsql= "SELECT OPNVOLM AS TOTOPNVOLM, CSTCOST AS TOTCSTCOST FROM OPNDT.DBF WHERE OPNCODE = '$newOPNCode' AND OPNDESC = '$OPNDESC'";
								$query = odbc_exec($odbc, $strsql) or die (odbc_errormsg());
								while($row = odbc_fetch_array($query))
								{
									$TOTOPNVOLM = $row['TOTOPNVOLM'];
									$TOTCSTCOST = $row['TOTCSTCOST'];
									$TOTAKUMVOL 	= $TOTAKUMVOL + $TOTOPNVOLM;
									$TOTAKUMCOST 	= $TOTAKUMCOST + $TOTCSTCOST;
								}
							}
							?>
								<tr>
									<td width="2%" nowrap style="text-align:center"><?php echo $NoU; ?>.</td>
									<td width="3%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
									<td width="49%" nowrap>&nbsp;<?php echo "$OPNDESC"; ?></td>
									<td width="9%" nowrap style="text-align:right"><?php print number_format($OPNVOLM, $decFormat); ?>&nbsp;</td>
									<td width="10%" nowrap style="text-align:center">&nbsp;<?php echo $CSTUNIT; ?></td>
									<td width="10%" nowrap style="text-align:right"><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
									<td width="8%" nowrap style="text-align:right; border-left:double"><?php print number_format($TOTAKUMVOL, $decFormat); ?>&nbsp;</td>
									<td width="9%" nowrap style="text-align:right"><?php print number_format($TOTAKUMCOST, $decFormat); ?>&nbsp;</td>
								</tr>
							<?php
							$GTOTCSTCOST = $GTOTCSTCOST + $CSTCOST;
							$GTOTAKUMCOST = $GTOTAKUMCOST + $TOTAKUMCOST;
						}
						//odbc_close($odbc);
						$TOPTOTHRG = $GTOTCSTCOST;
						$TAKUMHRG = $GTOTAKUMCOST;
						$sisaRow = $totalRow - $NoU;
						for($x=1;$x<=$sisaRow;$x++)
						{
							?>
								<tr>
									<td width="2%" nowrap style="text-align:center">&nbsp;</td>
									<td width="3%" nowrap style="text-align:center">&nbsp;</td>
									<td width="49%" nowrap>&nbsp;</td>
									<td width="9%" nowrap style="text-align:right">&nbsp;</td>
									<td width="10%" nowrap style="text-align:center">&nbsp;</td>
									<td width="10%" nowrap style="text-align:right">&nbsp;</td>
									<td width="8%" nowrap style="text-align:right; border-left:double">&nbsp;</td>
									<td width="9%" nowrap style="text-align:right">&nbsp;</td>
								</tr>
							<?php
						}
						?>
                        <tr>
                            <td colspan="5" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                            <td nowrap style="text-align:right; font-weight:bold"><?php print number_format($TOPTOTHRG, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right; font-weight:bold; border-left:double">&nbsp;</td>
                            <td nowrap style="text-align:right; font-weight:bold"><?php print number_format($TAKUMHRG, $decFormat); ?>&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="5" nowrap style="text-align:left; font-weight:bold">
                          <table width="100%" border="0">
                            <tr style="font-size:10px">
                              <td width="19%" nowrap style="text-align:left; font-weight:bold">UANG MUKA</td>
                              <td width="44%" style="text-align:left; font-weight:bold">: <?php echo $spkdp; ?> %</td>
                              <td style="text-align:right; font-weight:bold">N I L A I &nbsp;&nbsp;U A N G &nbsp;&nbsp;M U K A :</td>
                            </tr>
                          </table>                          </td>                          
							<?php
                                $nomDP = ($spkdp * $TOPTOTHRG) / 100;
								$SisaHrg = $TOPTOTHRG - $nomDP;
                            ?>
                          <td nowrap style="text-align:right; font-weight:bold"><?php print number_format($nomDP, $decFormat); ?>&nbsp;</td>
                          <td nowrap style="text-align:center; border-left:double">&nbsp;</td>
                          <td nowrap style="text-align:center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="5" nowrap style="text-align:left; font-weight:bold">&nbsp;</td>
                          <td nowrap  style="text-align:right; font-weight:bold"><?php print number_format($SisaHrg, $decFormat); ?>&nbsp;</td>
                          <td nowrap style="text-align:center; border-left:double">&nbsp;</td>
                          <td nowrap style="text-align:center">&nbsp;</td>
                        </tr>
                    	<?php
					//}
				?>		
      </table>   	  </td>
  	</tr>
    <tr>
		<td colspan="3" valign="top"><table width="100%" border="1">
          <tr style="font-size:8px;">
            <td style="text-align:center">PELAKSANA</td>
            <td style="text-align:center">SITE MANAGER<BR />(SM)</td>
            <td style="text-align:center">QC/QHSE</td>
            <td style="text-align:center">ENG<BR />(MON'G/QS)</td>
            <td style="text-align:center">COST CONTROL</td>
            <td style="text-align:center">KEUANGAN</td>
            <td style="text-align:center">PROJECT MANAGER<BR />(PM)</td>
            <td style="text-align:center">MANDOR/SUBKONT</td>
          </tr>
          <tr>
            <td>&nbsp;<BR /><BR /><BR /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
  	</tr>
    <tr>
      <td colspan="3" class="style2" style="font-size:8px; font-weight:bold"><?php echo "$Creater_Desc2"; ?></td>
    </tr>
    <?php
		if($NoU	>= 11)
		{
		?>
            <?php
                // Project
                /*$splCode 	= $SPLCODE;
				$sqlsp1	= "tvendor WHERE Vend_Code = '$splCode'";
				$sqlsp1R= $this->db->count_all($sqlsp1);
				if($sqlsp1R > 0)
				{
					$sqlsp2		= "SELECT Vend_Name FROM tvendor WHERE Vend_Code = '$splCode'";
					$sqlsp2R	= $this->db->query($sqlsp2)->result();
					foreach($sqlsp2R as $rowsp2) :
						$SPLDESC		= $rowsp2->SPLDESC;
					endforeach;
				}
				else
				{
					$sqlsp3	= "SUPPLIER WHERE SPLCODE = '$splCode'";
					$sqlsp3R= $this->db->count_all($sqlsp3);
					if($sqlsp3R > 0)
					{
						$sqlsp4		= "SELECT SPLDESC FROM SUPPLIER WHERE SPLCODE = '$splCode'";
						$sqlsp4R	= $this->db->query($sqlsp4)->result();
						foreach($sqlsp4R as $rowsp4) :
							$SPLDESC		= $rowsp4->SPLDESC;
						endforeach;
					}
					else
					{
						$SPLDESC		= " - ";
					}
				}*/
            ?>
    	<?php
		}
	?>
</table>
</body>
</html>
