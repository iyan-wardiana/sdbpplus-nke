<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Mei 2016
 * File Name	= v_voc_view_journal.php
*/
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
$sql = "SELECT * FROM tbl_language WHERE Language_Status = 1";
$reslang = $this->db->query($sql)->result();
foreach($reslang as $row) :
	$Language_ID	= $row->Language_ID;
endforeach;
if($Language_ID == 1)
{
	$Lang_ID	= 'IND';
}
else
{
	$Lang_ID	= 'ENG';
}

$sql = "SELECT MLANG_CODE, MLANG_$Lang_ID as WordTranslate FROM tbl_translate";
$restrans = $this->db->query($sql)->result();
foreach($restrans as $row) :
	$MLANG_CODE	= $row->MLANG_CODE;
	if($MLANG_CODE == 'Nomor')$Nomor = $row->WordTranslate;
	if($MLANG_CODE == 'VendCode')$VendCode = $row->WordTranslate;
	if($MLANG_CODE == 'VendName')$VendName = $row->WordTranslate;
	if($MLANG_CODE == 'Phone')$Phone = $row->WordTranslate;
	if($MLANG_CODE == 'VendAddress')$VendAddress = $row->WordTranslate;
	if($MLANG_CODE == 'materialbudgetreport')$materialbudgetreport = $row->WordTranslate;
	if($MLANG_CODE == 'nodata')$nodata = $row->WordTranslate;
endforeach;
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
//$odbc 		= odbc_connect ("DBaseNKE3", "" , "");
/* $odbc 		= odbc_connect ('DBaseNKE4', '', '') or die('Could Not Connect to ODBC Database!');
$getTID		= "SELECT TRXUSER, TRXDATE, APPRUSR FROM VLKHD.DBF WHERE VOCCODE = '$VOCCODE'";
$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
while($vTID = odbc_fetch_array($qTID))
{
	$TRXUSER		= $vTID['TRXUSER'];
	$TRXDATE		= $vTID['TRXDATE'];
	$APPRUSR		= $vTID['APPRUSR'];
} */
$getTID		= "SELECT TRXUSER, TRXDATE, APPRUSR FROM VLKHD WHERE VOCCODE = '$VOCCODE'";
$qTID		= $this->db->query($getTID)->result();
foreach($qTID as $vTID) :
	$TRXUSER		= $vTID->TRXUSER;
	$TRXDATE		= $vTID->TRXDATE;
	$APPRUSR		= $vTID->APPRUSR;
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
$localIPxx 		= gethostbyaddr($_SERVER['REMOTE_ADDR']);

///////////////////////////////
function get_real_ip() {
    $clientip      = isset( $_SERVER['HTTP_CLIENT_IP'] )       && $_SERVER['HTTP_CLIENT_IP']       ?
                     $_SERVER['HTTP_CLIENT_IP']         : false;
    $xforwarderfor = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && $_SERVER['HTTP_X_FORWARDED_FOR'] ?
                     $_SERVER['HTTP_X_FORWARDED_FOR']   : false;
    $xforwarded    = isset( $_SERVER['HTTP_X_FORWARDED'] )     && $_SERVER['HTTP_X_FORWARDED']     ?
                     $_SERVER['HTTP_X_FORWARDED']       : false;
    $forwardedfor  = isset( $_SERVER['HTTP_FORWARDED_FOR'] )   && $_SERVER['HTTP_FORWARDED_FOR']   ?
                     $_SERVER['HTTP_FORWARDED_FOR']     : false;
    $forwarded     = isset( $_SERVER['HTTP_FORWARDED'] )       && $_SERVER['HTTP_FORWARDED']       ?
                     $_SERVER['HTTP_FORWARDED']         : false;
    $remoteadd     = isset( $_SERVER['REMOTE_ADDR'] )          && $_SERVER['REMOTE_ADDR']          ?
                     $_SERVER['REMOTE_ADDR']            : false;
    
    // Function to get the client ip address
    if ( $clientip          !== false ) {
        $ipaddress = $clientip;
    }
    elseif( $xforwarderfor  !== false ) {
        $ipaddress = $xforwarderfor;
    }
    elseif( $xforwarded     !== false ) {
        $ipaddress = $xforwarded;
    }
    elseif( $forwardedfor   !== false ) {
        $ipaddress = $forwardedfor;
    }
    elseif( $forwarded      !== false ) {
        $ipaddress = $forwarded;
    }
    elseif( $remoteadd      !== false ) {
        $ipaddress = $remoteadd;
    }
    else{
        $ipaddress = false; # unknown
    }
    return $ipaddress;
}
$APPRUSR 		= '';
$localIP 		= get_real_ip();
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
$sqlsp3	= "supplier WHERE SPLCODE = '$SPLCODE'";
$sqlsp3R= $this->db->count_all($sqlsp3);
if($sqlsp3R > 0)
{
	$sqlsp4		= "SELECT SPLCODE, SPLDESC, SPLTELP, SPLNPWP FROM supplier WHERE SPLCODE = '$SPLCODE'";
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
	/*$dbSPL=dbase_open('C:/DBaseNKE/SDBP/SUPPLIER.DBF',0);
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
	}*/	
	/*$getSPLC		= "SELECT COUNT(*) AS cnt FROM SUPPLIER.DBF";
	$qSPLC 			= odbc_exec($odbc, $getSPLC) or die (odbc_errormsg());
	while($vSPLC	= odbc_fetch_array($qSPLC))
	{
		$MYSUM		= $vSPLC['cnt'];
	}

	$qSPL 		= odbc_exec($odbc, $getSPL) or die (odbc_errormsg());
	while($vSPL	= odbc_fetch_array($qSPL))
	{
		$SPLCODEX		= $vSPL['SPLCODE'];
		$SPLDESC		= $vSPL['SPLDESC'];
		$SPLTELP		= $vSPL['SPLTELP'];
		$SPLNPWP		= $vSPL['SPLNPWP'];
	}*/
	$SPLCODE		= $SPLCODE;
	$SPLCODEX		= $SPLCODE;
	$SPLDESC		= '';
	$SPLTELP		= '';
	$SPLNPWP		= '';
}

$Creater_Desc	= "$TRXUSER - $APPRUSR - $CreaterNm2 - $localIPxx";
$Creater_Desc2	= "$Creater_Code$localIP2-$TRXUSER$APPRUSR$localIPxx";

$sqlInsHist 	= "INSERT INTO tbl_trackcreater (Creater_Code, Creater_Desc) VALUES ('$Creater_Code', '$Creater_Desc')";		
$this->db->query($sqlInsHist);

$sqlgetLast 	= "tbl_printdoc WHERE printdoc_Ref = '$VOCCODE'";
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
				VALUES ('$VOCCODE', 'VLK', 1, '$Creater_Code')";
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
    	<td width="22%">
     		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
        <td colspan="3" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="18%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
  	</tr>
    <?php
		// Project
		$DBFnamex = "VLKDT.DBF";
		$splCode 	= $SPLCODE;
		
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
				$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM PROJECT WHERE PRJCODE = '$PRJCODE'";
				$sqlR		= $this->db->query($sql)->result();
				foreach($sqlR as $rowR) :
					$PRJCODE		= $rowR->PRJCODE;
					$PRJNAME		= $rowR->PRJNAME;
					$PRJLOCT		= $rowR->PRJLOCT;
				endforeach;
			}
			else
			{
				$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM PROJECT WHERE PRJCODE = '$PRJCODE'";
				$sqlR		= $this->db->query($sql)->result();
				foreach($sqlR as $rowR) :
					$PRJCODE		= $rowR->PRJCODE;
					$PRJNAME		= $rowR->PRJNAME;
					$PRJLOCT		= $rowR->PRJLOCT;
				endforeach;
			}
		}
		// Rounding Detail
		$NoU	= 0;
		
		$NoUx			= 0;
		$myVOCCodex 	= $VOCCODE;
		$VOCCODE2x	= 1;
		
		$myNewNoc = 0;			
		/*$hasilC		= "SELECT COUNT(*) AS COUNTME FROM VLKDT.DBF WHERE VOCCODE = '$myVOCCodex'";
		$qrhasilC	= odbc_exec($odbc, $hasilC) or die (odbc_errormsg());
		while($hasilC = odbc_fetch_array($qrhasilC))
		{
			$jumlah		= $hasilC['COUNTME'];
			$NoUx		= $jumlah;
		}*/
			
						
		$totPage = $NoUx; // ACAK
		if($totPage > 30)
		{
			$totPage = "004";
		}
		else if($totPage > 20)
		{
			$totPage = "003";
		}
		else if($totPage > 10)
		{
			$totPage = "002";
		}
		else
		{
			$totPage = "001";
		}
	?>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
        <td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
        <td class="style2">&nbsp;</td>
    </tr>
    <tr>
        <td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
        <td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:15px">:&nbsp;<?php echo $VOCCODE; ?></td>
    </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:left;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
        Uang Tunai</td>
        <td width="15%" valign="top" class="style2" style="text-align:left;">Dibayar/Jatuh tempo</td>
      <td colspan="2" valign="top" class="style2" style="text-align:left;">:</td>
    </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:left;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
        Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
        <td valign="top" class="style2" style="text-align:left;">No. Pembayaran (Kasir)</td>
        <td colspan="2" valign="top" class="style2" style="text-align:left; font-size:12px">:</td>
    </tr>
    <tr>
        <td colspan="2" class="style2" style="text-align:left;">Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:		</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">Hal : 001/<?php echo $totPage; ?></td>
    </tr>
    <tr>
    	<td colspan="5" class="style2" style="text-align:left; font-weight:bold; font-size:14px">Dibayar Kepada&nbsp;:&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
  	</tr>
    <tr>
        <td colspan="5" class="style2">
        	<table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="5%" style="text-align:center; font-weight:bold">No</td>
                    <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                    <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                    <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                    <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                    <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                </tr>
                <?php					
					$totCSTCOST2	= 0;
					$sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
					if($sqlDT1R > 0)
					{							
						// DENGAN DIRECT DBF
						$NoU	= 0;
						//$DBFname = "VLKDT.DBF";
						//$db=dbase_open('./assets/files/'.$DBFname,0);
						//$db=dbase_open('F:/NKE/SDBP/'.$DBFname,0);
						//$jumlah=dbase_numrecords($db);			
						//$odbc 		= odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
						$myVOCCode 	= $VOCCODE;
						$VOCCODE2	= 1;
						$i			= 0;
						/* $getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, PJTCODE FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
						$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
						while($hasilDT	= odbc_fetch_array($qrVOCDT)) */
						$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, PJTCODE FROM VLKDT WHERE VOCCODE = '$VOCCODE'";
						$qrVOCDT		= $this->db->query($getVOCDT)->result();
						foreach($qrVOCDT as $rowVLK) :
						{
							$i				= $i +1;
							$VOCCODE		= $rowVLK->VOCCODE;
							$VOCCODEDT		= $rowVLK->VOCCODE;
							$ACCCODE		= $rowVLK->ACCCODE;
							
							$LPODESC		= $rowVLK->LPODESC;
							$CSTCODE		= $rowVLK->CSTCODE;
							$CSTUNIT		= $rowVLK->CSTUNIT;
							$CSTPUNT		= $rowVLK->CSTPUNT;
							$CSTCOST		= $rowVLK->CSTCOST;							
							$VOCDESC		= $rowVLK->LPODESC;
							$VOCVOLM		= $rowVLK->LPOVOLM;
							$VOCCODEX		= $rowVLK->VOCCODE;
							$PJTCODE		= $rowVLK->PJTCODE;
							$VOCCODEY		= trim($myVOCCode);
							//echo "$VOCCODEDT == $myVOCCode<br>";
							if(trim($VOCCODEDT) == trim($myVOCCode))
							{							
								$NoU	= $NoU + 1;
								
								//$sqlPRJ		= "SELECT PRJCODE FROM VLKHD WHERE VOCCODE = '$VOCCODE'";
								//$sqlPRJR	= $this->db->query($sqlPRJ)->result();
								//foreach($sqlPRJR as $rowprj ) :
									//$PRJCODE		= $rowprj->PRJCODE;
								//endforeach;
								$totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
								?>
									<tr>
										<td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
										<td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
										<td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
										<td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
										<td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
										<td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
										<td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
										<td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
										<td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
									</tr>
								<?php
								$VOCCODE2		= 2;
								if($NoU >= 35)
								{
									break;
								}
							}
							//if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
							//{
								//break;
							//}
						}
						endforeach;
						if($NoUx <= 35)
						{
						?>
                            <tr>
                                <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                              <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                            </tr>
                    	<?php
						}
					}
					else
					{
                     	?>
						    <tr>
                                <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                            </tr>
                    	<?php
					}
				?>		
      </table>   	  </td>
  	</tr>
	<?php						
        $terbilang = $moneyFormat->terbilang($totCSTCOST2);						
    ?>
    <tr>
      <td colspan="5" class="style2" style="font-weight:bold; font-style:italic">TERBILANG : "<?php echo $terbilang; ?> Rupiah"</td>
    </tr>
    <tr>
		<td class="style2" valign="top">
            <table width="100%" border="1" rules="all">
                <tr>
                    <td width="25%">ACC</td>
                    <td width="33%">&nbsp;</td>
                    <td width="42%">&nbsp;</td>
                </tr>
                <tr>
                    <td>FIAT</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <table width="100%" border="0">
                <tr>
                    <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                    <td width="79%" style="font-size:11px" nowrap>Tanda Tangan Lengkap dengan Nama dan <br />Tanggal saat menanda Tangani</td>
                </tr>
            </table>        </td>
        <td colspan="4" class="style2" style="text-align:right" valign="top">        
            <table width="100%" border="1" align="right" rules="all">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>    	</td>
    </tr>
    <?php						
		// START : UJI COBA
		//$odbc = odbc_connect ('LPMDT.DBF', '', '') or die('Could Not Connect to ODBC Database!');
		/*$odbc = odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
		$hasilDTUC		= "SELECT TRXUSER, TRXDATE, APPRUSR FROM VLKHD.DBF WHERE VOCCODE = '$VOCCODE'";
		$qrhasilUC 		= odbc_exec($odbc, $hasilDTUC) or die (odbc_errormsg());
		while($hasilUC = odbc_fetch_array($qrhasilUC))
		{
			$TRXUSER		= $hasilUC['TRXUSER'];
			$APPRUSR		= $hasilUC['APPRUSR'];
		}
		
		// CREATE FOR TRACER ID
		date_default_timezone_set("Asia/Jakarta"); 
		$myYear 		= date('y');
		$myMonth 		= date('m');
		$myDays 		= date('d');
		$myHours 		= date('H');
		$mySecond 		= date('i');
		$CreaterNm 		= getenv("username");
		$localIP 		= getHostByName(php_uname('n'));
		$Creater_Code	= "TR$myMonth$myYear$myDays$myHours$mySecond";
		$Creater_Desc	= "$TRXUSER - $APPRUSR - $CreaterNm - $localIP";
						
		$sqlgetLast 	= "tbl_printdoc WHERE printdoc_Ref = '$VOCCODE'";
		$restgetLast 	= $this->db->count_all($sqlgetLast);
		if($restgetLast > 0)
		{
			$isOri = "Copy";
		}
		else
		{
			$isOri = "Original";
			$sqlInsert = "INSERT INTO tbl_printdoc (printdoc_Ref, printdoc_Cat, printdoc_No, printdoc_Notes)
						VALUES ('$VOCCODE', 'SPP', 1, '$Creater_Code')";
			$this->db->query($sqlInsert);
		}*/
	?>
    <tr>
      <td colspan="5" class="style2"><?php echo "$Creater_Desc2"; ?></td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 35)
		{
		?>
            <tr>
                <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
                <td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
                <td class="style2">&nbsp;</td>
            </tr>
            <tr>
                <td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
              <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
                <td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:15px">:<?php echo $VOCCODE; ?></td>
            </tr>
            <tr>
                <td colspan="2" valign="top" class="style2" style="text-align:left;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Uang Tunai</td>
                <td width="15%" valign="top" class="style2" style="text-align:left;">Dibayar/Jatuh tempo</td>
              <td colspan="2" valign="top" class="style2" style="text-align:left;">:</td>
            </tr>
            <tr>
                <td colspan="2" valign="top" class="style2" style="text-align:left;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
                <td valign="top" class="style2" style="text-align:left;">No. Pembayaran (Kasir)</td>
                <td colspan="2" valign="top" class="style2" style="text-align:left; font-size:12px">:</td>
            </tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left;">Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:		</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">Hal : 002/<?php echo $totPage; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:left; font-weight:bold; font-size:14px">Dibayar Kepada&nbsp;:&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
            </tr>
            <tr>
				<td colspan="5" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								//$i			= 0;
								/* $getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT)) */
								
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, PJTCODE FROM VLKDT WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= $this->db->query($getVOCDT)->result();
								foreach($qrVOCDT as $rowVLK) :
								{
									$i				= $i +1;
									$VOCCODE		= $rowVLK->VOCCODE;
									$VOCCODEDT		= $rowVLK->VOCCODE;
									$ACCCODE		= $rowVLK->ACCCODE;
									
									$LPODESC		= $rowVLK->LPODESC;
									$CSTCODE		= $rowVLK->CSTCODE;
									$CSTUNIT		= $rowVLK->CSTUNIT;
									$CSTPUNT		= $rowVLK->CSTPUNT;
									$CSTCOST		= $rowVLK->CSTCOST;							
									$VOCDESC		= $rowVLK->LPODESC;
									$VOCVOLM		= $rowVLK->LPOVOLM;
									$VOCCODEX		= $rowVLK->VOCCODE;
									$VOCCODEY		= trim($myVOCCode);
									
                                    if(trim($VOCCODEDT) == trim($myVOCCode))
                                    {							
                                        $NoU	= $NoU + 1;
                                        
                                        /*$sqlPRJ		= "SELECT PRJCODE FROM VLKHD WHERE VOCCODE = '$VOCCODE'";
                                        $sqlPRJR	= $this->db->query($sqlPRJ)->result();
                                        foreach($sqlPRJR as $rowprj ) :
                                            $PRJCODE		= $rowprj->PRJCODE;
                                        endforeach;*/
                                        if($NoU	> 35)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
                                                <td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
                                                <td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
                                                <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
                                                <td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
                                                <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                                            </tr>
                                        <?php
                                        }
                                        $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                        $VOCCODE2		= 2;
                                        if($NoU >= 60)
                                        {
                                            break;
                                        }
                                    }
                                   // if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
								endforeach;
                                if($NoUx <= 70)
                                {
                                ?>
                                    <tr>
                                        <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                      <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>		
      				</table>            	</td>
            </tr>
    		<tr>
                <td class="style2" valign="top">
                    <table width="100%" border="1">
                        <tr>
                            <td width="25%">ACC</td>
                            <td width="33%">&nbsp;</td>
                            <td width="42%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>FIAT</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                        <tr>
                            <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                            <td width="79%" style="font-size:11px">Tanda Tangan Lengkap dengan Nama dan Tanggal saat menanda Tangani</td>
                        </tr>
                    </table>                </td>
        		<td colspan="4" class="style2" style="text-align:right" valign="top">        
                    <table width="100%" border="1" align="right">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>                </td>
            </tr>
            <tr>
              <td colspan="5" class="style2"><?php echo "$Creater_Code - $isOri"; ?></td>
            </tr>
    	<?php
		}
	?>
    <?php
		if($NoU	>= 60)
		{
		?>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
                <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
                <td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
                <td class="style2">&nbsp;</td>
            </tr>
            <tr>
                <td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
              <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
                <td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:15px">:<?php echo $VOCCODE; ?></td>
            </tr>
            <tr>
                <td colspan="2" valign="top" class="style2" style="text-align:left;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Uang Tunai</td>
                <td width="15%" valign="top" class="style2" style="text-align:left;">Dibayar/Jatuh tempo</td>
              <td colspan="2" valign="top" class="style2" style="text-align:left;">:</td>
            </tr>
            <tr>
                <td colspan="2" valign="top" class="style2" style="text-align:left;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
                <td valign="top" class="style2" style="text-align:left;">No. Pembayaran (Kasir)</td>
                <td colspan="2" valign="top" class="style2" style="text-align:left; font-size:12px">:</td>
            </tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left;">Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:		</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">Hal : 003/<?php echo $totPage; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:left; font-weight:bold; font-size:14px">Dibayar Kepada&nbsp;:&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
            </tr>
            <tr>
				<td colspan="5" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;	
						
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								$i			= 0;
								/* $getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									
									$LPODESC		= $hasilDT['LPODESC'];
									$CSTCODE		= $hasilDT['CSTCODE'];
									$CSTUNIT		= $hasilDT['CSTUNIT'];
									$CSTPUNT		= $hasilDT['CSTPUNT'];
									$CSTCOST		= $hasilDT['CSTCOST'];							
									$VOCDESC		= $hasilDT['LPODESC'];
									$VOCVOLM		= $hasilDT['LPOVOLM'];
									$VOCCODEX		= $hasilDT['VOCCODE']; */
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, PJTCODE FROM VLKDT WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= $this->db->query($getVOCDT)->result();
								foreach($qrVOCDT as $rowVLK) :
								{
									$i				= $i +1;
									$VOCCODE		= $rowVLK->VOCCODE;
									$VOCCODEDT		= $rowVLK->VOCCODE;
									$ACCCODE		= $rowVLK->ACCCODE;
									
									$LPODESC		= $rowVLK->LPODESC;
									$CSTCODE		= $rowVLK->CSTCODE;
									$CSTUNIT		= $rowVLK->CSTUNIT;
									$CSTPUNT		= $rowVLK->CSTPUNT;
									$CSTCOST		= $rowVLK->CSTCOST;							
									$VOCDESC		= $rowVLK->LPODESC;
									$VOCVOLM		= $rowVLK->LPOVOLM;
									$VOCCODEX		= $rowVLK->VOCCODE;
									$PJTCODE		= $rowVLK->PJTCODE;
									$VOCCODEY		= trim($myVOCCode);
									$VOCCODEY		= trim($myVOCCode);
							
                                    if(trim($VOCCODEDT) == trim($myVOCCode))
                                    {							
                                        $NoU	= $NoU + 1;
                                        
                                        /*$sqlPRJ		= "SELECT PRJCODE FROM tVOChd WHERE VOCCODE = '$VOCCODE'";
                                        $sqlPRJR	= $this->db->query($sqlPRJ)->result();
                                        foreach($sqlPRJR as $rowprj ) :
                                            $PRJCODE		= $rowprj->PRJCODE;
                                        endforeach;*/
                                        if($NoU	> 60)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
                                                <td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
                                                <td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
                                                <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
                                                <td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
                                                <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                                            </tr>
                                        <?php
                                        }
                                        $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                        $VOCCODE2		= 2;
                                        if($NoU >= 90)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
								endforeach;
                                if($NoUx <= 90)
                                {
                                ?>
                                    <tr>
                                        <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                      <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>		
      				</table>      			</td>
            </tr>
    		<tr>
                <td class="style2" valign="top">
                    <table width="100%" border="1">
                        <tr>
                            <td width="25%">ACC</td>
                            <td width="33%">&nbsp;</td>
                            <td width="42%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>FIAT</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                        <tr>
                            <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                            <td width="79%" style="font-size:11px" nowrap>Tanda Tangan Lengkap dengan Nama dan<br /> Tanggal saat menanda Tangani</td>
                        </tr>
                    </table>                </td>
        		<td colspan="4" class="style2" style="text-align:right" valign="top">        
                    <table width="100%" border="1" align="right">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>                </td>
            </tr>
            <tr>
              <td colspan="5" class="style2"><?php echo "$Creater_Code - $isOri"; ?></td>
            </tr>
    	<?php
		}
	?>
    <?php
		if($NoU	>= 90)
		{
		?>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
              	<td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
              	<td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
           	  <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
                <td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">: <span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $VOCCODE; ?></span></span></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Uang Tunai</td>
              	<td width="15%" valign="top" class="style2" style="text-align:left; font-weight:bold;">Dibayar/Jatuh tempo</td>
           	  <td width="29%" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">:</td>
           	  <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold;">No. Pembayaran (Kasir)</td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:12px">:</td>
              	<td class="style2" style="text-align:left;">&nbsp;</td>
  			</tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left; font-weight:bold">Bank :</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">Hal : 004/<?php echo $totPage; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <?php
                // Project
                /*$splCode 	= $SPLCODE;
				$sqlsp1	= "SUPPLIER WHERE SPLCODE = '$splCode'";
				$sqlsp1R= $this->db->count_all($sqlsp1);
				if($sqlsp1R > 0)
				{
					$sqlsp2		= "SELECT SPLDESC FROM SUPPLIER WHERE SPLCODE = '$splCode'";
					$sqlsp2R	= $this->db->query($sqlsp2)->result();
					foreach($sqlsp2R as $rowsp2) :
						$SPLDESC		= $rowsp2->SPLDESC;
					endforeach;
				}
				else
				{
					$sqlsp3	= "SUPPLIER_upd WHERE SPLCODE = '$splCode'";
					$sqlsp3R= $this->db->count_all($sqlsp3);
					if($sqlsp3R > 0)
					{
						$sqlsp4		= "SELECT SPLDESC FROM SUPPLIER_upd WHERE SPLCODE = '$splCode'";
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
            <tr>
                <td colspan="5" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:center">&nbsp;</td>
            </tr>
            <tr>
				<td colspan="5" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;						
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								$i			= 0;
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									
									$LPODESC		= $hasilDT['LPODESC'];
									$CSTCODE		= $hasilDT['CSTCODE'];
									$CSTUNIT		= $hasilDT['CSTUNIT'];
									$CSTPUNT		= $hasilDT['CSTPUNT'];
									$CSTCOST		= $hasilDT['CSTCOST'];							
									$VOCDESC		= $hasilDT['LPODESC'];
									$VOCVOLM		= $hasilDT['LPOVOLM'];
									$VOCCODEX		= $hasilDT['VOCCODE'];
									$VOCCODEY		= trim($myVOCCode);
                                    if(trim($VOCCODEDT) == trim($myVOCCode))
                                    {							
                                        $NoU	= $NoU + 1;
                                        
                                        /*$sqlPRJ		= "SELECT PRJCODE FROM tVOChd WHERE VOCCODE = '$VOCCODE'";
                                        $sqlPRJR	= $this->db->query($sqlPRJ)->result();
                                        foreach($sqlPRJR as $rowprj ) :
                                            $PRJCODE		= $rowprj->PRJCODE;
                                        endforeach;*/
                                        if($NoU	> 90)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
                                                <td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
                                                <td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
                                                <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
                                                <td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
                                                <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                                            </tr>
                                        <?php
                                        }
                                        $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                        $VOCCODE2		= 2;
                                        if($NoU >= 120)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 120)
                                {
                                ?>
                                    <tr>
                                        <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                      <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>		
      				</table>      			</td>
            </tr>
    		<tr>
                <td class="style2" valign="top">
                    <table width="100%" border="1">
                        <tr>
                            <td width="25%">ACC</td>
                            <td width="33%">&nbsp;</td>
                            <td width="42%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>FIAT</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                        <tr>
                            <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                            <td width="79%" style="font-size:11px">Tanda Tangan Lengkap dengan Nama dan Tanggal saat menanda Tangani</td>
                        </tr>
                    </table>                </td>
        		<td colspan="4" class="style2" style="text-align:right" valign="top">        
                    <table width="100%" border="1" align="right">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>                </td>
            </tr>
        <tr>
          <td colspan="5" class="style2"><?php echo "$Creater_Code - $isOri"; ?></td>
        </tr>
    	<?php
		}
	?>
    <?php
		if($NoU	>= 120)
		{
		?>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
              	<td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
              	<td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
           	  <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
                <td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">: <span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $VOCCODE; ?></span></span></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Uang Tunai</td>
              	<td width="15%" valign="top" class="style2" style="text-align:left; font-weight:bold;">Dibayar/Jatuh tempo</td>
           	  <td width="29%" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">:</td>
           	  <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold;">No. Pembayaran (Kasir)</td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:12px">:</td>
              	<td class="style2" style="text-align:left;">&nbsp;</td>
  			</tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left; font-weight:bold">Bank :</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">Hal : 005/<?php echo $totPage; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <?php
                // Project
                /*$splCode 	= $SPLCODE;
				$sqlsp1	= "SUPPLIER WHERE SPLCODE = '$splCode'";
				$sqlsp1R= $this->db->count_all($sqlsp1);
				if($sqlsp1R > 0)
				{
					$sqlsp2		= "SELECT SPLDESC FROM SUPPLIER WHERE SPLCODE = '$splCode'";
					$sqlsp2R	= $this->db->query($sqlsp2)->result();
					foreach($sqlsp2R as $rowsp2) :
						$SPLDESC		= $rowsp2->SPLDESC;
					endforeach;
				}
				else
				{
					$sqlsp3	= "SUPPLIER_upd WHERE SPLCODE = '$splCode'";
					$sqlsp3R= $this->db->count_all($sqlsp3);
					if($sqlsp3R > 0)
					{
						$sqlsp4		= "SELECT SPLDESC FROM SUPPLIER_upd WHERE SPLCODE = '$splCode'";
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
            <tr>
                <td colspan="5" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:center">&nbsp;</td>
            </tr>
            <tr>
				<td colspan="5" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;						
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								$i			= 0;
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									
									$LPODESC		= $hasilDT['LPODESC'];
									$CSTCODE		= $hasilDT['CSTCODE'];
									$CSTUNIT		= $hasilDT['CSTUNIT'];
									$CSTPUNT		= $hasilDT['CSTPUNT'];
									$CSTCOST		= $hasilDT['CSTCOST'];							
									$VOCDESC		= $hasilDT['LPODESC'];
									$VOCVOLM		= $hasilDT['LPOVOLM'];
									$VOCCODEX		= $hasilDT['VOCCODE'];
									$VOCCODEY		= trim($myVOCCode);
                                    if(trim($VOCCODEDT) == trim($myVOCCode))
                                    {							
                                        $NoU	= $NoU + 1;
                                        
                                        /*$sqlPRJ		= "SELECT PRJCODE FROM tVOChd WHERE VOCCODE = '$VOCCODE'";
                                        $sqlPRJR	= $this->db->query($sqlPRJ)->result();
                                        foreach($sqlPRJR as $rowprj ) :
                                            $PRJCODE		= $rowprj->PRJCODE;
                                        endforeach;*/
                                        if($NoU	> 120)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
                                                <td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
                                                <td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
                                                <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
                                                <td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
                                                <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                                            </tr>
                                        <?php
                                        }
                                        $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                        $VOCCODE2		= 2;
                                        if($NoU >= 150)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 150)
                                {
                                ?>
                                    <tr>
                                        <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                      <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>		
      				</table>            	</td>
            </tr>
    		<tr>
                <td class="style2" valign="top">
                    <table width="100%" border="1">
                        <tr>
                            <td width="25%">ACC</td>
                            <td width="33%">&nbsp;</td>
                            <td width="42%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>FIAT</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                        <tr>
                            <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                            <td width="79%" style="font-size:11px">Tanda Tangan Lengkap dengan Nama dan Tanggal saat menanda Tangani</td>
                        </tr>
                    </table>                </td>
        		<td colspan="4" class="style2" style="text-align:right" valign="top">        
                    <table width="100%" border="1" align="right">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>                </td>
            </tr>
        <tr>
          <td colspan="5" class="style2"><?php echo "$Creater_Code - $isOri"; ?></td>
        </tr>
    	<?php
		}
	?>
    <?php
		if($NoU	>= 150)
		{
		?>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
			<tr>
              	<td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
              	<td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
              	<td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
           	  <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
                <td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">: <span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $VOCCODE; ?></span></span></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Uang Tunai</td>
              	<td width="15%" valign="top" class="style2" style="text-align:left; font-weight:bold;">Dibayar/Jatuh tempo</td>
           	  <td width="29%" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">:</td>
           	  <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold;">No. Pembayaran (Kasir)</td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:12px">:</td>
              	<td class="style2" style="text-align:left;">&nbsp;</td>
  			</tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left; font-weight:bold">Bank :</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">Hal : 006/<?php echo $totPage; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <?php
                // Project
                /*$splCode 	= $SPLCODE;
				$sqlsp1	= "SUPPLIER WHERE SPLCODE = '$splCode'";
				$sqlsp1R= $this->db->count_all($sqlsp1);
				if($sqlsp1R > 0)
				{
					$sqlsp2		= "SELECT SPLDESC FROM SUPPLIER WHERE SPLCODE = '$splCode'";
					$sqlsp2R	= $this->db->query($sqlsp2)->result();
					foreach($sqlsp2R as $rowsp2) :
						$SPLDESC		= $rowsp2->SPLDESC;
					endforeach;
				}
				else
				{
					$sqlsp3	= "SUPPLIER_upd WHERE SPLCODE = '$splCode'";
					$sqlsp3R= $this->db->count_all($sqlsp3);
					if($sqlsp3R > 0)
					{
						$sqlsp4		= "SELECT SPLDESC FROM SUPPLIER_upd WHERE SPLCODE = '$splCode'";
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
            <tr>
                <td colspan="5" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:center">&nbsp;</td>
            </tr>
            <tr>
				<td colspan="5" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;						
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								$i			= 0;
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									
									$LPODESC		= $hasilDT['LPODESC'];
									$CSTCODE		= $hasilDT['CSTCODE'];
									$CSTUNIT		= $hasilDT['CSTUNIT'];
									$CSTPUNT		= $hasilDT['CSTPUNT'];
									$CSTCOST		= $hasilDT['CSTCOST'];							
									$VOCDESC		= $hasilDT['LPODESC'];
									$VOCVOLM		= $hasilDT['LPOVOLM'];
									$VOCCODEX		= $hasilDT['VOCCODE'];
									$VOCCODEY		= trim($myVOCCode);
                                    if(trim($VOCCODEDT) == trim($myVOCCode))
                                    {							
                                        $NoU	= $NoU + 1;
                                        
                                        /*$sqlPRJ		= "SELECT PRJCODE FROM tVOChd WHERE VOCCODE = '$VOCCODE'";
                                        $sqlPRJR	= $this->db->query($sqlPRJ)->result();
                                        foreach($sqlPRJR as $rowprj ) :
                                            $PRJCODE		= $rowprj->PRJCODE;
                                        endforeach;*/
                                        if($NoU	> 150)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
                                                <td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
                                                <td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
                                                <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
                                                <td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
                                                <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                                            </tr>
                                        <?php
                                        }
                                        $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                        $VOCCODE2		= 2;
                                        if($NoU >= 180)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 90)
                                {
                                ?>
                                    <tr>
                                        <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                      <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>
      				</table>            	</td>
            </tr>
    		<tr>
                <td class="style2" valign="top">
                    <table width="100%" border="1">
                        <tr>
                            <td width="25%">ACC</td>
                            <td width="33%">&nbsp;</td>
                            <td width="42%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>FIAT</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                        <tr>
                            <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                            <td width="79%" style="font-size:11px">Tanda Tangan Lengkap dengan Nama dan Tanggal saat menanda Tangani</td>
                        </tr>
                    </table>                </td>
        			<td colspan="4" class="style2" style="text-align:right" valign="top">        
                    <table width="100%" border="1" align="right">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>            	</td>
            </tr>
            <tr>
              <td colspan="5" class="style2"><?php echo "$Creater_Code - $isOri"; ?></td>
            </tr>
    	<?php
		}
	?>
    <?php
		if($NoU	>= 180)
		{
		?>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" class="style2">&nbsp;</td>
            </tr>
			<tr>
              	<td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="250" height="50"></td>
              	<td colspan="3" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER PEMBAYARAN</td>
              	<td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td width="16%" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
           	  <td valign="top" class="style2" style="text-align:left; font-weight:bold;">Nomor</td>
                <td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">: <span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><span class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $VOCCODE; ?></span></span></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Uang Tunai</td>
              	<td width="15%" valign="top" class="style2" style="text-align:left; font-weight:bold;">Dibayar/Jatuh tempo</td>
           	  <td width="29%" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:18px">:</td>
           	  <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              	<td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><input type="checkbox" name="checkbox1" id="checkbox1" value="1" /> 
                Giro No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold;">No. Pembayaran (Kasir)</td>
              	<td valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:12px">:</td>
              	<td class="style2" style="text-align:left;">&nbsp;</td>
  			</tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left; font-weight:bold">Bank :</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold">Hal : 007/<?php echo $totPage; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <?php
                // Project
                /*$splCode 	= $SPLCODE;
				$sqlsp1	= "SUPPLIER WHERE SPLCODE = '$splCode'";
				$sqlsp1R= $this->db->count_all($sqlsp1);
				if($sqlsp1R > 0)
				{
					$sqlsp2		= "SELECT SPLDESC FROM SUPPLIER WHERE SPLCODE = '$splCode'";
					$sqlsp2R	= $this->db->query($sqlsp2)->result();
					foreach($sqlsp2R as $rowsp2) :
						$SPLDESC		= $rowsp2->SPLDESC;
					endforeach;
				}
				else
				{
					$sqlsp3	= "SUPPLIER_upd WHERE SPLCODE = '$splCode'";
					$sqlsp3R= $this->db->count_all($sqlsp3);
					if($sqlsp3R > 0)
					{
						$sqlsp4		= "SELECT SPLDESC FROM SUPPLIER_upd WHERE SPLCODE = '$splCode'";
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
            <tr>
                <td colspan="5" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="style2" style="text-align:center">&nbsp;</td>
            </tr>
            <tr>
				<td colspan="5" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Satuan</td>
                            <td width="14%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;						
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								$i			= 0;
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM FROM VLKDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									
									$LPODESC		= $hasilDT['LPODESC'];
									$CSTCODE		= $hasilDT['CSTCODE'];
									$CSTUNIT		= $hasilDT['CSTUNIT'];
									$CSTPUNT		= $hasilDT['CSTPUNT'];
									$CSTCOST		= $hasilDT['CSTCOST'];							
									$VOCDESC		= $hasilDT['LPODESC'];
									$VOCVOLM		= $hasilDT['LPOVOLM'];
									$VOCCODEX		= $hasilDT['VOCCODE'];
									$VOCCODEY		= trim($myVOCCode);
                                    if(trim($VOCCODEDT) == trim($myVOCCode))
                                    {							
                                        $NoU	= $NoU + 1;
                                        
                                        /*$sqlPRJ		= "SELECT PRJCODE FROM tVOChd WHERE VOCCODE = '$VOCCODE'";
                                        $sqlPRJR	= $this->db->query($sqlPRJ)->result();
                                        foreach($sqlPRJR as $rowprj ) :
                                            $PRJCODE		= $rowprj->PRJCODE;
                                        endforeach;*/
                                        if($NoU	> 180)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCDESC"; ?></td>
                                                <td width="7%" style="text-align:center" nowrap>&nbsp;<?php echo "$PRJCODE"; ?></td>
                                                <td width="8%" style="text-align:center" nowrap>&nbsp;<?php echo $ACCCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:center"><?php echo $CSTCODE; ?></td>
                                                <td width="0%" nowrap style="text-align:right"><?php print number_format($VOCVOLM, $decFormat); ?>&nbsp;</td>
                                                <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?></td>
                                                <td width="7%" nowrap style="text-align:right"><?php print number_format($CSTPUNT, $decFormat); ?>&nbsp;</td>
                                                <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                                            </tr>
                                        <?php
                                        }
                                        $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
                                        $VOCCODE2		= 2;
                                        if($NoU >= 210)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 210)
                                {
                                ?>
                                    <tr>
                                        <td colspan="8" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                      <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="9" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>
      				</table>            	</td>
            </tr>
    		<tr>
                <td class="style2" valign="top">
                    <table width="100%" border="1">
                        <tr>
                            <td width="25%">ACC</td>
                            <td width="33%">&nbsp;</td>
                            <td width="42%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>FIAT</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                        <tr>
                            <td width="21%" style="font-size:11px; font-weight:bold" valign="top" nowrap>CATATAN :</td>
                            <td width="79%" style="font-size:11px">Tanda Tangan Lengkap dengan Nama dan Tanggal saat menanda Tangani</td>
                        </tr>
                    </table>                </td>
        			<td colspan="4" class="style2" style="text-align:right" valign="top">        
                    <table width="100%" border="1" align="right">
                <tr>
                    <td colspan="2">Yang membuat</td>
                    <td colspan="2">Diperiksa</td>
                    <td colspan="2">Kasir</td>
                    <td colspan="2">Diterima</td>
                </tr>
                <tr>
                    <td width="20%">&nbsp;<br /><br /></td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" align="center" valign="top">Tgl.</td>
                    <td width="20%">&nbsp;</td>
                    <td width="5%" valign="top" align="center">Tgl.</td>
                </tr>
   	  </table>            	</td>
            </tr>
            <tr>
              <td colspan="5" class="style2"><?php echo "$Creater_Code - $isOri"; ?></td>
            </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2">&nbsp;</td>
    </tr>
</table>
</body>
</html>