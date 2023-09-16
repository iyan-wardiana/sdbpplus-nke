<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari2017
 * File Name	= lpm_view_journal.php
 * Location		= -
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
$odbc = odbc_connect ("DBaseNKE3", "" , "");
$getTID		= "SELECT TRXUSER, APPRUSR FROM LPMHD.DBF WHERE LPMCODE = '$LPMCODE'";
$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
while($vTID = odbc_fetch_array($qTID))
{
	$TRXUSER		= $vTID['TRXUSER'];
	$APPRUSR		= $vTID['APPRUSR'];
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

$sqlgetLast 	= "tbl_printdoc WHERE printdoc_Ref = '$LPMCODE'";
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
				VALUES ('$LPMCODE', 'LPM', 1, '$Creater_Code')";
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
    	<td width="24%">
     		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
        <td width="56%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="20%" class="style2" style="text-align:center; font-size:24px"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
  	</tr>
    <tr>
    	<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><span class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/Logo1.jpg') ?>" width="300" height="70" /></span></td>
      <td colspan="2" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px"> LPM (LAPORAN PENERIMAAN MATERIAL)</td>
    </tr>
    <tr>
      	<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">Nomor : <?php echo $LPMCODE; ?></td>
  </tr>
    <tr>
      	<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">OP No. : <?php echo $OP_CODE; ?></td>
    </tr>
    <?php
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
    <tr>
      	<td class="style2" style="text-align:left;font-style:italic" valign="top">&nbsp;</td>
      	<td class="style2" style="text-align:left;">Hal : 001/<?php echo $totPage; ?></td>
    </tr>
    <tr>
    	<td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
  	</tr>
    <tr>
    	<td colspan="3" class="style2" style="text-align:center">&nbsp;</td>
  	</tr>
    <tr>
        <td colspan="3" class="style2">
        	<table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="5%" style="text-align:center; font-weight:bold">No</td>
                    <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                    <td width="7%" style="text-align:center; font-weight:bold">No.<br />Gudang</td>
                    <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Unit</td>
                    <td width="7%" style="text-align:center; font-weight:bold">Harga</td>
                    <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                </tr>
                <?php					
					$totCSTCOST2	= 0;
					$sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
					if($sqlDT1R > 0)
					{							
						// DENGAN DIRECT DBF
						$NoU	= 0;
						$DBFname 	= "LPMDT.DBF";
						$DBFnameSJ 	= "LPMSJ.DBF";
						//$db=dbase_open('./assets/files/'.$DBFname,0);
						//$db=dbase_open('C:/DBaseNKE/SDBP/'.$DBFname,0);
						//$jumlah=dbase_numrecords($db);
						//$dbSJ=dbase_open('C:/DBaseNKE/SDBP/'.$DBFnameSJ,0);
						//$jmlSJ=dbase_numrecords($dbSJ);						
						
						$hasilLPMSJ		= "SELECT LPMCODE, CSTCODE, LPMDESC, PRJCODE, SJ_CODE, SJ_VOLM FROM LPMSJ.DBF WHERE LPMCODE = '$LPMCODE'";
						$qrLPMSJ		= odbc_exec($odbc, $hasilLPMSJ) or die (odbc_errormsg());
						$myLPMCode 		= $LPMCODE;
						$totCSTCOST2	= 0;
						
						while($getLPMSJ = odbc_fetch_array($qrLPMSJ))
						{
							$LPMCODE2		= $getLPMSJ['LPMCODE'];
							$CSTCODE2		= $getLPMSJ['CSTCODE'];
							$LPMDESC2		= $getLPMSJ['LPMDESC'];
							$PRJCODE2		= $getLPMSJ['PRJCODE'];
							$SJ_CODE2		= $getLPMSJ['SJ_CODE'];
							$SJ_VOLM2		= $getLPMSJ['SJ_VOLM'];
							if($LPMCODE2 == $myLPMCode)
							{
								$hasilLPMDT		= "SELECT LPMCODE, CSTCODE, ACCCODE, CSTPUNT, CSTUNIT, CSTDISC, LPMDESC FROM LPMDT.DBF
													WHERE LPMCODE = '$LPMCODE' AND CSTCODE = '$CSTCODE2' AND LPMDESC = '$LPMDESC2'";
								$qrLPMDT		= odbc_exec($odbc, $hasilLPMDT) or die (odbc_errormsg());
								while($getLPMDT = odbc_fetch_array($qrLPMDT))
								{
									$NoU			= $NoU + 1;
									$LPMCODEX		= $getLPMDT['LPMCODE'];
									$CSTCODEX		= $getLPMDT['CSTCODE'];
									$ACCCODE		= $getLPMDT['ACCCODE'];
									$CSTPUNT		= $getLPMDT['CSTPUNT'];
									$CSTUNIT		= $getLPMDT['CSTUNIT'];
									$CSTDISC		= $getLPMDT['CSTDISC'];
									$LPMDESCX		= $getLPMDT['LPMDESC'];
									
									$ACCCODE2		= $ACCCODE;
									$ACCCODE2		= $ACCCODE;
									$HRGDISSAT		= $CSTDISC / $SJ_VOLM2;
									$CSTPUNT2		= $CSTPUNT - $HRGDISSAT;
									$CSTCOST2		= $CSTPUNT2 * $SJ_VOLM2;
									$totCSTCOST2 	= $totCSTCOST2 + $CSTCOST2;
									?>
                                        <tr>
                                            <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                            <td width="51%" nowrap>&nbsp;<?php echo "$LPMDESC2"; ?></td>
                                            <td width="7%" nowrap style="text-align:center">&nbsp;<?php echo "$PRJCODE2"; ?></td>
                                            <td width="8%" nowrap style="text-align:center">&nbsp;<?php echo $SJ_CODE2; ?></td>
                                            <td width="0%" nowrap style="text-align:center"><?php echo $ACCCODE2; ?></td>
                                            <td width="0%" nowrap style="text-align:right"><?php echo $CSTCODE2; ?>&nbsp;</td>
                                            <td width="7%" nowrap style="text-align:right"><?php print number_format($SJ_VOLM2, $decFormat); ?></td>
                                            <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?>&nbsp;</td>
                                            <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTPUNT2, $decFormat); ?>&nbsp;</td>
                                            <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST2, $decFormat); ?>&nbsp;</td>
                                        </tr>
                                    <?php
									if($NoU >= 20)
									{
										break;
									}
								}
							}
						}
						?>
                        <tr>
                            <td colspan="9" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                          <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                        </tr>
                    	<?php
					}
					else
					{
                     	?>
						    <tr>
                                <td colspan="10" nowrap style="text-align:center">--- None ---</td>
                            </tr>
                    	<?php
					}
				?>		
      		</table>    	</td>
  	</tr>
    <tr>
		<td colspan="3" valign="top" class="style2">
        <table width="100%" border="1" rules="all">
          <tr style="font-size:10px">
            <td width="3%">&nbsp;</td>
            <td width="10%" style="text-align:center">DITERIMA</td>
            <td width="13%" style="text-align:center">MENGETAHUI</td>
            <td width="12%" style="text-align:center">MENGETAHUI</td>
            <td width="12%" style="text-align:center">DIPERIKSA</td>
            <td width="17%" style="text-align:center">MENGETAHUI</td>
            <td width="13%" style="text-align:center">DITERIMA &amp; DIPERIKSA</td>
            <td width="20%" style="text-align:left" nowrap>DISETUJUI (OWNER)***<BR />PT :</td>
          </tr>
          <tr style="font-size:10px">
            <td>Tanggal</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr style="font-size:10px">
            <td>Nama</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr style="font-size:10px">
            <td>Jabatan</td>
            <td style="text-align:center" nowrap>GUDANG/<br />Penerima</td>
            <td style="text-align:center" nowrap>GEN. AFFAIR (GA)</td>
            <td style="text-align:center" nowrap>ENGINEERING/QC</td>
            <td style="text-align:center" nowrap>COST CONTROL<br />PROYEK</td>
            <td style="text-align:center" nowrap>PROJ.MANAGER (PM)/<BR />Mgr*) ...................</td>
            <td style="text-align:center" nowrap>COST CONTROL<BR />PUSAT/CAB*)</td>
            <td style="text-align:center" nowrap>&nbsp;</td>
          </tr>
          <tr style="font-size:10px">
            <td>Tanda-<br />Tangan</td>
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
      <td colspan="3" class="style2" style="font-size:8px; font-weight:bold">
	  <?php 
	  	echo "$Creater_Desc2";
	  ?>      </td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td class="style2">&nbsp;</td>
      <td class="style2">&nbsp;</td>
      <td class="style2" style="text-align:center">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 21)
		{
		?>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:center"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
            </tr>
            <tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="320" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:26px">JOURNAL LPM</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
                <td class="style2" style="text-align:left; font-weight:bold"><span class="style2" style="text-align:left; font-weight:bold"><?php echo $LPMCODE; ?></span></td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 002/002</td>
  			</tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo $SPLDESC; ?></td>
            </tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:center">&nbsp;</td>
            </tr>
            <tr>
				<td colspan="3" class="style2">
                    <table width="100%" border="1">
                        <tr style="background:#CCCCCC">
                            <td width="5%" style="text-align:center; font-weight:bold">No</td>
                            <td width="51%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Proyek</td>
                            <td width="7%" style="text-align:center; font-weight:bold">No.<br />Gudang</td>
                            <td width="8%" style="text-align:center; font-weight:bold">No.<br />Perk.</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Kode<br />Item</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Qty</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Unit</td>
                            <td width="7%" style="text-align:center; font-weight:bold">Harga</td>
                            <td width="15%" style="text-align:center; font-weight:bold">Nominal</td>
                        </tr>
                        <?php					
                            $totCSTCOST2	= 0;
                            $sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
                            if($sqlDT1R > 0)
                            {							
                                // DENGAN DIRECT DBF
                                $NoU	= 0;
                                $DBFname 	= "LPMDT.DBF";
                                $DBFnameSJ 	= "LPMSJ.DBF";
                                //$db=dbase_open('./assets/files/'.$DBFname,0);
                                //$db=dbase_open('C:/DBaseNKE/SDBP/'.$DBFname,0);
                                //$jumlah=dbase_numrecords($db);
                                //$dbSJ=dbase_open('C:/DBaseNKE/SDBP/'.$DBFnameSJ,0);
                                //$jmlSJ=dbase_numrecords($dbSJ);						
                                
                                $hasilLPMSJ		= "SELECT LPMCODE, CSTCODE, LPMDESC, PRJCODE, SJ_CODE, SJ_VOLM FROM LPMSJ.DBF WHERE LPMCODE = '$LPMCODE'";
                                $qrLPMSJ		= odbc_exec($odbc, $hasilLPMSJ) or die (odbc_errormsg());
                                $myLPMCode 		= $LPMCODE;
                                $totCSTCOST2	= 0;
                                
                                while($getLPMSJ = odbc_fetch_array($qrLPMSJ))
                                {
                                    $LPMCODE2		= $getLPMSJ['LPMCODE'];
                                    $CSTCODE2		= $getLPMSJ['CSTCODE'];
                                    $LPMDESC2		= $getLPMSJ['LPMDESC'];
                                    $PRJCODE2		= $getLPMSJ['PRJCODE'];
                                    $SJ_CODE2		= $getLPMSJ['SJ_CODE'];
                                    $SJ_VOLM2		= $getLPMSJ['SJ_VOLM'];
                                    if($LPMCODE2 == $myLPMCode)
                                    {
                                        $hasilLPMDT		= "SELECT LPMCODE, CSTCODE, ACCCODE, CSTPUNT, CSTUNIT, CSTDISC, LPMDESC FROM LPMDT.DBF
                                                            WHERE LPMCODE = '$LPMCODE' AND CSTCODE = '$CSTCODE2' AND LPMDESC = '$LPMDESC2'";
                                        $qrLPMDT		= odbc_exec($odbc, $hasilLPMDT) or die (odbc_errormsg());
                                        while($getLPMDT = odbc_fetch_array($qrLPMDT))
                                        {
                                            $NoU			= $NoU + 1;
                                            $LPMCODEX		= $getLPMDT['LPMCODE'];
                                            $CSTCODEX		= $getLPMDT['CSTCODE'];
                                            $ACCCODE		= $getLPMDT['ACCCODE'];
                                            $CSTPUNT		= $getLPMDT['CSTPUNT'];
                                            $CSTUNIT		= $getLPMDT['CSTUNIT'];
                                            $CSTDISC		= $getLPMDT['CSTDISC'];
                                            $LPMDESCX		= $getLPMDT['LPMDESC'];
                                            
                                            $ACCCODE2		= $ACCCODE;
                                            $ACCCODE2		= $ACCCODE;
                                            $HRGDISSAT		= $CSTDISC / $SJ_VOLM2;
                                            $CSTPUNT2		= $CSTPUNT - $HRGDISSAT;
                                            $CSTCOST2		= $CSTPUNT2 * $SJ_VOLM2;
                                            $totCSTCOST2 	= $totCSTCOST2 + $CSTCOST2;
											if($NoU	> 15)
											{
											?>
                                                <tr>
                                                    <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                    <td width="51%" nowrap>&nbsp;<?php echo "$LPMDESC2"; ?></td>
                                                    <td width="7%" nowrap style="text-align:center">&nbsp;<?php echo "$PRJCODE2"; ?></td>
                                                    <td width="8%" nowrap style="text-align:center">&nbsp;<?php echo $SJ_CODE2; ?></td>
                                                    <td width="0%" nowrap style="text-align:center"><?php echo $ACCCODE2; ?></td>
                                                    <td width="0%" nowrap style="text-align:right"><?php echo $CSTCODE2; ?>&nbsp;</td>
                                                    <td width="7%" nowrap style="text-align:right"><?php print number_format($SJ_VOLM2, $decFormat); ?></td>
                                                    <td width="7%" nowrap style="text-align:center"><?php echo $CSTUNIT; ?>&nbsp;</td>
                                                    <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTPUNT2, $decFormat); ?>&nbsp;</td>
                                                    <td width="15%" style="text-align:right" nowrap><?php print number_format($CSTCOST2, $decFormat); ?>&nbsp;</td>
                                                </tr>
                                            <?php
											}
											if($NoU >= 40)
											{
												break;
											}
                                        }
                                    }
                                }
                                ?>
                                <tr>
                                    <td colspan="9" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                                  <td width="15%" style="text-align:right; font-weight:bold" nowrap><?php print number_format($totCSTCOST2, $decFormat); ?>&nbsp;</td>
                                </tr>
                                <?php
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="10" nowrap style="text-align:center">--- None ---</td>
                                    </tr>
                                <?php
                            }
                        ?>		
                    </table>
            	</td>
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
                    </table>              </td>
                <td colspan="2" class="style2" style="text-align:right" valign="top">        
                    <table width="60%" border="1" align="right">
                        <tr>
                            <td colspan="2">Yang membuat</td>
                            <td colspan="2">Diperiksa</td>
                        </tr>
                        <tr>
                            <td width="46%">&nbsp;<br /><br /></td>
                            <td width="10%" valign="top" align="center">Tgl.</td>
                            <td width="35%">&nbsp;</td>
                            <td width="9%" valign="top" align="center">Tgl.</td>
                        </tr>
                    </table>                </td>
            </tr>
            <tr>
              <td colspan="3" valign="top" class="style2"><?php echo "$Creater_Desc2"; ?></td>
            </tr>
    	<?php
		}
	?>
</table>
</body>
</html>
