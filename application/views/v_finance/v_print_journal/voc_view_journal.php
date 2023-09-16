<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Oktober 2015
 * File Name	= v_voc_view_journal.php
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
$APPRUSR	= '';
$odbc = odbc_connect ("DBaseNKE2", "" , "");
$getTID		= "SELECT TRXUSER, APPRUSR FROM VOCHD.DBF WHERE VOCCODE = '$VOCCODE'";
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
	/*$getSPL		= "SELECT SPLCODE, SPLDESC,SPLTELP,SPLNPWP FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
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
$Creater_Desc	= "$TRXUSER - $APPRUSR - $CreaterNm2 - $localIP";
$Creater_Desc2	= "$Creater_Code$localIP2-$TRXUSER$APPRUSR$CreaterNm2";

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
				VALUES ('$VOCCODE', 'LPM', 1, '$Creater_Code')";
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
        <td width="54%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="22%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
  	</tr>
    <tr>
    	<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
        <td nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">VOUCHER JOURNAL</td>
   	    <td nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px">&nbsp;</td>
    </tr>
    <tr>
      	<td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">Nomor : <?php echo $VOCCODE; ?></td>
        <td valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
  </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold" valign="top" height="10">&nbsp;</td>
        <td class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <?php
		// Project
		$DBFnamex = "VOCDT.DBF";
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
			}
			else
			{
				$PRJCODE		= "None";
				$PRJNAME		= "None";
				$PRJLOCT		= "None";
			}
		}
		
		// Rounding Detail
		$NoU	= 0;
		
		$NoUx			= 0;
		$myVOCCodex 	= $VOCCODE;
		$VOCCODE2x	= 1;
		
		$myNewNoc = 0;			
		$hasilC		= "SELECT COUNT(*) AS COUNTME FROM VOCDT.DBF WHERE VOCCODE = '$myVOCCodex'";
		$qrhasilC	= odbc_exec($odbc, $hasilC) or die (odbc_errormsg());
		while($hasilC = odbc_fetch_array($qrhasilC))
		{
			$jumlah		= $hasilC['COUNTME'];
			$NoUx		= $jumlah;
		}
		
		$totPage = $NoUx; // ACAK
		//echo "testing $totPage";
		//return false;
		if($totPage > 280)
		{
			$totPage = "008";
		}
		elseif($totPage > 240)
		{
			$totPage = "007";
		}
		elseif($totPage > 200)
		{
			$totPage = "006";
		}
		elseif($totPage > 160)
		{
			$totPage = "005";
		}
		elseif($totPage > 120)
		{
			$totPage = "004";
		}
		else if($totPage > 80)
		{
			$totPage = "003";
		}
		else if($totPage > 40)
		{
			$totPage = "002";
		}
		else
		{
			$totPage = "001";
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
						//$DBFname = "VOCDT.DBF";
						//$db=dbase_open('./assets/files/'.$DBFname,0);
						//$db=dbase_open('F:/NKE/SDBP/'.$DBFname,0);
						//$jumlah=dbase_numrecords($db);			
						$odbc 		= odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
						$myVOCCode 	= $VOCCODE;
						$VOCCODE2	= 1;
						$i			= 0;
						$getVOCDT	= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
						//$rs = odbc_exec($odbc, "SELECT Count(*) AS counter FROM VOCDT1.DBF WHERE VOCCODE = '$VOCCODE'");
						//$arr = odbc_fetch_array($rs);

						//echo $arr['counter'];
						//return false;
						$qrVOCDT	= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
						while($hasilDT	= odbc_fetch_array($qrVOCDT))
						{
							$i				= $i +1;
							$VOCCODE		= $hasilDT['VOCCODE'];
							$VOCCODEDT		= $hasilDT['VOCCODE'];
							$ACCCODE		= $hasilDT['ACCCODE'];
							$PJTCODE		= $hasilDT['PJTCODE'];
							$LPODESC		= $hasilDT['LPODESC'];
							$CSTCODE		= $hasilDT['CSTCODE'];
							$CSTUNIT		= $hasilDT['CSTUNIT'];
							$CSTPUNT		= $hasilDT['CSTPUNT'];
							$CSTCOST		= $hasilDT['CSTCOST'];							
							$VOCDESC		= $hasilDT['LPODESC'];
							$VOCVOLM		= $hasilDT['LPOVOLM'];
							$VOCCODEX		= $hasilDT['VOCCODE'];
							$VOCCODEY		= trim($myVOCCode);
							//echo "$VOCCODEDT == $myVOCCode<br>";
							if(trim($VOCCODEDT) == trim($myVOCCode))
							{							
								$NoU	= $NoU + 1;
								
								/*$sqlPRJ		= "SELECT PRJCODE FROM tVOChd WHERE VOCCODE = '$VOCCODE'";
								$sqlPRJR	= $this->db->query($sqlPRJ)->result();
								foreach($sqlPRJR as $rowprj ) :
									$PRJCODE		= $rowprj->PRJCODE;
								endforeach;*/
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
								$totCSTCOST2 	= $totCSTCOST2 + $CSTCOST;
								$VOCCODE2		= 2;
								if($NoU >= 40)
								{
									break;
								}
							}
							//if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
							//{
								//break;
							//}
						}
						if($NoUx <= 40)
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
                    <td width="79%" style="font-size:11px">Tanda Tangan Lengkap dengan Nama dan Tanggal saat menanda Tangani</td>
                </tr>
            </table>        </td>
        <td colspan="2" class="style2" style="text-align:right" valign="top">        
            <table width="60%" border="1" align="right" rules="all">
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
        	</table>    	</td>
    </tr>
    <tr>
      <td colspan="3" class="style2"><?php echo "$Creater_Desc2"; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 40)
		{
		?>
            <tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px" nowrap>VOUCHER JOURNAL</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
           	  <td class="style2" style="text-align:center; font-weight:bold; font-size:18px" valign="top">Nomor : <?php echo $VOCCODE; ?></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 002/<?php echo $totPage; ?></td>
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
								$odbc1 		= odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');			
                                // DENGAN DIRECT DBF
                                $NoU	= 0;
								$myVOCCode 	= $VOCCODE;
								$VOCCODE2	= 1;
								//$i			= 0;
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc1, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
											
						/*$odbc 		= odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
						$myVOCCode 	= $VOCCODE;
						$VOCCODE2	= 1;
						$i			= 0;
						$getVOCDT	= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
						$qrVOCDT	= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
						while($hasilDT	= odbc_fetch_array($qrVOCDT))*/
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									$PJTCODE		= $hasilDT['PJTCODE'];
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
                                        if($NoU	> 40)
                                        {
                                        ?>
                                            <tr>
                                                <td width="5%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
                                                <td width="51%" nowrap>&nbsp;<?php echo "$VOCCODE - $VOCDESC"; ?></td>
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
                                        if($NoU >= 80)
                                        {
                                            break;
                                        }
                                    }
                                   // if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 80)
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
                    </table>                </td>
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
              <td colspan="3" class="style2">
			  <?php 
			  	//echo "$Creater_Code - $isOri"; 
				echo $Creater_Desc2;
			  ?>
              </td>
            </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 80)
		{
		?>
            <tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px" nowrap>VOUCHER JOURNAL</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
           	  <td class="style2" style="text-align:center; font-weight:bold; font-size:18px" valign="top">Nomor : <?php echo $VOCCODE; ?></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 003/<?php echo $totPage; ?></td>
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
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									$PJTCODE		= $hasilDT['PJTCODE'];
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
                                        if($NoU	> 80)
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
              <td colspan="3" class="style2">
			  <?php 
			  	//echo "$Creater_Code - $isOri"; 
				echo $Creater_Desc2;
			  ?>
              </td>
            </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 120)
		{
		?>
            <tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px" nowrap>VOUCHER JOURNAL</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
           	  <td class="style2" style="text-align:center; font-weight:bold; font-size:18px" valign="top">Nomor : <?php echo $VOCCODE; ?></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 004/<?php echo $totPage; ?></td>
  			</tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
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
                <td colspan="3" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
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
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									$PJTCODE		= $hasilDT['PJTCODE'];
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
                                        if($NoU >= 160)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 160)
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
          <td colspan="3" class="style2">
			  <?php 
			  	//echo "$Creater_Code - $isOri"; 
				echo $Creater_Desc2;
			  ?>
              </td>
        </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 160)
		{
		?>
            <tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px" nowrap>VOUCHER JOURNAL</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
           	  <td class="style2" style="text-align:center; font-weight:bold; font-size:18px" valign="top">Nomor : <?php echo $VOCCODE; ?></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 005/<?php echo $totPage; ?></td>
  			</tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
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
                <td colspan="3" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
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
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									$PJTCODE		= $hasilDT['PJTCODE'];
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
                                        if($NoU	> 160)
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
                                        if($NoU >= 200)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 200)
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
                    </table>                </td>
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
          <td colspan="3" class="style2">
			  <?php 
			  	//echo "$Creater_Code - $isOri"; 
				echo $Creater_Desc2;
			  ?>
              </td>
        </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 200)
		{
		?>
			<tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px" nowrap>VOUCHER JOURNAL</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
           	  <td class="style2" style="text-align:center; font-weight:bold; font-size:18px" valign="top">Nomor : <?php echo $VOCCODE; ?></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 006/<?php echo $totPage; ?></td>
  			</tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
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
                <td colspan="3" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
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
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									$PJTCODE		= $hasilDT['PJTCODE'];
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
                                        if($NoU	> 200)
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
                                        if($NoU >= 240)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 240)
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
                    </table>                </td>
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
                    </table>
            	</td>
            </tr>
            <tr>
              <td colspan="3" class="style2">
			  <?php 
			  	//echo "$Creater_Code - $isOri"; 
				echo $Creater_Desc2;
			  ?>
              </td>
            </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <?php
		if($NoU	>= 240)
		{
		?>
			<tr>
              <td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="300" height="70"></td>
              <td class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px" nowrap>VOUCHER JOURNAL</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
           	  <td class="style2" style="text-align:center; font-weight:bold; font-size:18px" valign="top">Nomor : <?php echo $VOCCODE; ?></td>
                <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
  			</tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2">&nbsp;</td>
            </tr>
            <tr>
              <td class="style2">&nbsp;</td>
              <td class="style2" style="text-align:left;">Hal : 007/<?php echo $totPage; ?></td>
  			</tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
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
                <td colspan="3" class="style2" style="text-align:left;">Supplier :&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
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
								$getVOCDT		= "SELECT VOCCODE, ACCCODE, PJTCODE, LPODESC, CSTCODE, CSTUNIT, CSTPUNT, CSTCOST, LPOVOLM, VOCCODE FROM VOCDT.DBF WHERE VOCCODE = '$VOCCODE'";
								$qrVOCDT		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());
								while($hasilDT	= odbc_fetch_array($qrVOCDT))
								{
									$i				= $i +1;
									$VOCCODE		= $hasilDT['VOCCODE'];
									$VOCCODEDT		= $hasilDT['VOCCODE'];
									$ACCCODE		= $hasilDT['ACCCODE'];
									$PJTCODE		= $hasilDT['PJTCODE'];
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
                                        if($NoU	> 240)
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
                                        if($NoU >= 280)
                                        {
                                            break;
                                        }
                                    }
                                    //if((trim($VOCCODEDT) != trim($myVOCCode)) && $VOCCODE2 == 2)
                                    //{
                                        //break;
                                    //}
                                }
                                if($NoUx <= 280)
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
                    </table>                </td>
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
                    </table>
            	</td>
            </tr>
            <tr>
              <td colspan="3" class="style2">
			  <?php 
			  	//echo "$Creater_Code - $isOri"; 
				echo $Creater_Desc2;
			  ?>
              </td>
            </tr>
    	<?php
		}
	?>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
</table>
</body>
</html>
