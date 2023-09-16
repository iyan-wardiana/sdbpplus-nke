<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 05 Oktober 2015
 * File Name	= v_spp_view_journal.php
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
$odbc 		= odbc_connect ("DBaseNKE3", "" , "");	
$getTID		= "SELECT SPPCODE, TRXDATE, PRJCODE, TRXUSER, APPRUSR FROM SPPHD.DBF WHERE SPPCODE = '$SPPCODE'";
$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
while($vTID = odbc_fetch_array($qTID))
{
	$SPPCODE		= $vTID['SPPCODE'];
	$TRXDATE		= $vTID['TRXDATE'];
	$PRJCODE		= $vTID['PRJCODE'];
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
				

$Creater_Desc	= "$TRXUSER - $APPRUSR - $CreaterNm2 - $localIP";
$Creater_Desc2	= "$Creater_Code$localIP2-$TRXUSER$APPRUSR$CreaterNm2";

$sqlInsHist 	= "INSERT INTO tbl_trackcreater (Creater_Code, Creater_Desc) VALUES ('$Creater_Code', '$Creater_Desc2')";		
$this->db->query($sqlInsHist);

$sqlgetLast 	= "tbl_printdoc WHERE printdoc_Ref = '$SPPCODE'";
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
						VALUES ('$SPPCODE', 'OPN', 1, '$Creater_Code')";
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
        <td width="22%" class="style2" style="text-align:center; font-weight:bold"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
  	</tr>
    <tr>
    	<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="320" height="70"></td>
        <td colspan="2" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px"> SPP (surat permintaan pengadaan)</td>
    </tr>
    <tr>
      	<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">Nomor : <?php echo $SPPCODE; ?></td>
  	</tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold" valign="top" height="10">&nbsp;</td>
        <td class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <?php
		// Rounding Detail
		$NoU	= 0;
		
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
		//$DBFnamex = "SPPDT.DBF";
		//$dbx=dbase_open('F:/NKE/SDBP/'.$DBFnamex,0);
		//$jumlahx=dbase_numrecords($dbx);
		$NoU	= 0;
		$NoUx			= 0;
		$mySPPCodex 	= $SPPCODE;
		$SPPCode2x		= 1;
		
		$hasilC		= "SELECT COUNT(*) AS COUNTME FROM SPPDT.DBF WHERE SPPCODE = '$SPPCODE'";
		$qrhasilC	= odbc_exec($odbc, $hasilC) or die (odbc_errormsg());
		while($hasilC = odbc_fetch_array($qrhasilC))
		{
			$jumlah		= $hasilC['COUNTME'];
			$NoUx		= $hasilC['COUNTME'];
		}
			
		/*for($xx=1;$xx<=$jumlahx;$xx++)
		{
			$hasilDT=dbase_get_record_with_names($dbx,$xx);
			$SPPCodeDTx		= $hasilDT['SPPCODE'];
			if(trim($SPPCodeDTx) == trim($mySPPCodex))
			{							
				$NoUx	= $NoUx + 1;
			}
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
      	<td class="style2" style="text-align:left;font-style:italic" valign="top">&nbsp;</td>
      	<td class="style2" style="text-align:left;">Hal : 001/<?php echo $totPage; ?></td>
    </tr>
    <tr>
    	<td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <?php
		$projCode 	= $PRJCODE;
		
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
	?>
    <tr>
    	<td colspan="3" class="style2" style="text-align:left;">Proyek :&nbsp;<?php echo "$PRJCODE - $PRJNAME"; ?></td>
  	</tr>
    <tr>
    	<td colspan="3" class="style2" style="text-align:center">&nbsp;</td>
  	</tr>
    <tr>
        <td colspan="3" class="style2">
        	<table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="6%" style="text-align:center; font-weight:bold">No</td>
                    <td width="68%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                    <td width="12%" style="text-align:center; font-weight:bold">Kode Item</td>
                    <td width="9%" style="text-align:center; font-weight:bold">Qty</td>
                    <td width="9%" style="text-align:center; font-weight:bold">Unit</td>
                    <td width="5%" style="text-align:center; font-weight:bold">Proses</td>
                </tr>
                <?php
					$totSPPVOLM2	= 0;
					$sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
					if($sqlDT1R > 0)
					{
						/*$sqlDT2		= "SELECT * FROM tsppdt WHERE SPPCODE = '$SPPCODE'";
						$sqlDT2R	= $this->db->query($sqlDT2)->result();
						foreach($sqlDT2R as $rowdt2) :
							$CSTCODE		= $rowdt2->CSTCODE;
							$SPPDESC		= $rowdt2->SPPDESC;
							$SPPVOLM		= $rowdt2->SPPVOLM;
							$SPPVPRS		= $rowdt2->SPPVPRS;*/
							
						// DENGAN DIRECT DBF
						$NoU	= 0;
						//$DBFname = "SPPDT.DBF";
						//$db=dbase_open('./assets/files/'.$DBFname,0);
						//$db=dbase_open('F:/NKE/SDBP/'.$DBFname,0);
						//$jumlah=dbase_numrecords($db);
						
						$mySPPCode = $SPPCODE;
						$getSPPDT	= "SELECT SPPCODE, CSTCODE, SPPDESC, SPPVOLM, SPPVOLM, SPPVPRS, CSTUNIT FROM SPPDT.DBF WHERE SPPCODE = '$SPPCODE'";
						$qSPPDT		= odbc_exec($odbc, $getSPPDT) or die (odbc_errormsg());
						while($vSPPDT = odbc_fetch_array($qSPPDT))
						//for($x=1;$x<=$jumlah;$x++)
						{
							//$hasilDT=dbase_get_record_with_names($db,$x);
							$SPPCODE		= $vSPPDT['SPPCODE'];
							$SPPCODEDT		= $vSPPDT['SPPCODE'];
							$CSTCODE		= $vSPPDT['CSTCODE'];
							$SPPDESC		= $vSPPDT['SPPDESC'];
							$SPPVOLM		= $vSPPDT['SPPVOLM'];
							$SPPVPRS		= $vSPPDT['SPPVPRS'];
							$CSTUNIT		= $vSPPDT['CSTUNIT'];
							
							//if(trim($SPPCODEDT) == trim($mySPPCode))
							//{				
							$NoU	= $NoU + 1;
							?>
							<tr>
								<td width="6%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
								<td width="68%" nowrap>&nbsp;<?php echo "$SPPDESC"; ?></td>
								<td width="12%" nowrap style="text-align:center">&nbsp;<?php echo $CSTCODE; ?></td>
								<td width="9%" nowrap style="text-align:right"><?php print number_format($SPPVOLM, $decFormat); ?>&nbsp;</td>
								<td width="9%" nowrap style="text-align:right"><?php echo $CSTUNIT; ?>&nbsp;</td>
								<td width="5%" nowrap style="text-align:center"><?php if($SPPVPRS == 1) { ?> <img src="<?php print base_url().'assets/AdminLTE-2.0.5/dist/img/checklist3.png'; ?>" width="20" height="20"> <?php } ?> </td>
							</tr>
							<?php
							$totSPPVOLM2 = $totSPPVOLM2 + $SPPVOLM;							
							if($NoU >= 11)
							{
								break;
							}
							//}
						}
						?>
                        <tr>
                            <td colspan="3" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
                            <td nowrap style="text-align:right; font-weight:bold"><?php print number_format($totSPPVOLM2, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                            <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                        </tr>
                    	<?php
					}
					else
					{
                     	?>
						    <tr>
                                <td colspan="6" nowrap style="text-align:center">--- None ---</td>
                            </tr>
                    	<?php
					}
				?>		
      		</table>      	</td>
  	</tr>
    <tr>
		<td colspan="3" valign="top">
            <table width="100%" border="0">
                <tr style="font-size:10px;">
                    <td width="20%" style="text-align:center">&nbsp;</td>
                    <td width="20%" style="text-align:center">&nbsp;</td>
                    <td width="20%" style="text-align:center">&nbsp;</td>
                    <td width="37%" style="text-align:right">Schedule Material on Site :</td>
                    <td width="3%" style="text-align:center" nowrap>&nbsp;
                        <?php $date = new DateTime($SchedMatOS);
                            echo $date->format('d F Y'); 
                        ?>                    </td>
              	</tr>
            </table>        </td>
  	</tr>
    <tr>
		<td colspan="3" valign="top">
        	<table width="100%" border="1" rules="all">
                <tr style="font-size:10px;">
                    <td width="14%" style="text-align:center">DIAJUKAN :<BR />PELAKSANA/ Koord.<br />PELAKSANA</td>
                    <td width="14%" style="text-align:center">DIPERIKSA : <BR />ENGINEERING</td>
                    <td width="14%" style="text-align:center">DIPERIKSA : <BR />COST CONTROL</td>
                    <td width="14%" style="text-align:center">DISETUJUI : <BR /> SITE MANAGER (SM)<br />...................*)</td>
                    <td width="14%" style="text-align:center">DISETUJUI : <BR />PROJ. MANAGER (PM)<br />...................*)</td>
                    <td width="15%" style="text-align:center">DIPERIKSA (FINAL) :<BR />COST CONT. PUSAT/<BR />CABAGN *)</td>
                    <td width="15%" style="text-align:center">DISETUJUI (FINAL / JIKA PERLU) :<BR />KEP. CABANG/ <BR />DIREKTUR ...........*)</td>
                </tr>
                <tr>
                    <td>&nbsp;<BR /><BR /><BR /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr style="font-size:8px; font-style:italic">
                    <td>NAMA :</td>
                    <td>NAMA :</td>
                    <td>NAMA :</td>
                    <td>NAMA :</td>
                    <td>NAMA :</td>
                    <td>NAMA :</td>
                    <td>NAMA :</td>
                </tr>
                <tr style="font-size:8px; font-style:italic">
                    <td>TANGGAL :</td>
                    <td>TANGGAL :</td>
                    <td>TANGGAL :</td>
                    <td>TANGGAL :</td>
                    <td>TANGGAL :</td>
                    <td>TANGGAL :</td>
                    <td>TANGGAL :</td>
                </tr>
        	</table>        </td>
  	</tr>
    <tr>
		<td colspan="3" class="style2" style="font-size:8px; font-weight:bold"><?php echo "$Creater_Desc2"; ?></td>
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
		if($NoU	>= 10)
		{
			?>
				<tr>
					<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
					<td class="style2">&nbsp;</td>
					<td class="style2" style="text-align:center; font-weight:bold"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
				</tr>
				<tr>
					<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="320" height="70"></td>
					<td colspan="2" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px"> SPP (surat permintaan pengadaan)</td>
				</tr>
				<tr>
					<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">Nomor : <?php echo $SPPCODE; ?></td>
				</tr>
				<tr>
					<td class="style2" style="text-align:left; font-weight:bold" valign="top" height="10">&nbsp;</td>
					<td class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
				</tr>
				<tr>
					<td class="style2" style="text-align:left;font-style:italic" valign="top">&nbsp;</td>
					<td class="style2" style="text-align:left;">Hal : 002/<?php echo $totPage; ?></td>
				</tr>
				<tr>
					<td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="style2" style="text-align:left;">Proyek :&nbsp;<span class="style2" style="text-align:left;"><?php echo "$PRJCODE - $PRJNAME"; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="style2" style="text-align:center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="style2">
						<table width="100%" border="1">
							<tr style="background:#CCCCCC">
								<td width="6%" style="text-align:center; font-weight:bold">No</td>
								<td width="68%" style="text-align:center; font-weight:bold">KETERANGAN</td>
								<td width="12%" style="text-align:center; font-weight:bold">Kode Item</td>
								<td width="9%" style="text-align:center; font-weight:bold">Qty</td>
								<td width="9%" style="text-align:center; font-weight:bold">Unit</td>
								<td width="5%" style="text-align:center; font-weight:bold">Proses</td>
							</tr>
							<?php
								$totSPPVOLM2	= 0;
								$sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
								if($sqlDT1R > 0)
								{
									/*$sqlDT2		= "SELECT * FROM tsppdt WHERE SPPCODE = '$SPPCODE'";
									$sqlDT2R	= $this->db->query($sqlDT2)->result();
									foreach($sqlDT2R as $rowdt2) :
										$CSTCODE		= $rowdt2->CSTCODE;
										$SPPDESC		= $rowdt2->SPPDESC;
										$SPPVOLM		= $rowdt2->SPPVOLM;
										$SPPVPRS		= $rowdt2->SPPVPRS;*/
										
									// DENGAN DIRECT DBF
									$NoU	= 0;
									//$DBFname = "SPPDT.DBF";
									//$db=dbase_open('./assets/files/'.$DBFname,0);
									//$db=dbase_open('F:/NKE/SDBP/'.$DBFname,0);
									//$jumlah=dbase_numrecords($db);
									
									$mySPPCode = $SPPCODE;
									$getSPPDT	= "SELECT SPPCODE, CSTCODE, SPPDESC, SPPVOLM, SPPVOLM, SPPVPRS, CSTUNIT FROM SPPDT.DBF WHERE SPPCODE = '$SPPCODE'";
									$qSPPDT		= odbc_exec($odbc, $getSPPDT) or die (odbc_errormsg());
									while($vSPPDT = odbc_fetch_array($qSPPDT))
									//for($x=1;$x<=$jumlah;$x++)
									{
										//$hasilDT=dbase_get_record_with_names($db,$x);
										$SPPCODE		= $vSPPDT['SPPCODE'];
										$SPPCODEDT		= $vSPPDT['SPPCODE'];
										$CSTCODE		= $vSPPDT['CSTCODE'];
										$SPPDESC		= $vSPPDT['SPPDESC'];
										$SPPVOLM		= $vSPPDT['SPPVOLM'];
										$SPPVPRS		= $vSPPDT['SPPVPRS'];
										$CSTUNIT		= $vSPPDT['CSTUNIT'];
										
										//if(trim($SPPCODEDT) == trim($mySPPCode))
										//{				
										$NoU	= $NoU + 1;
										if($NoU > 11)
										{
										?>
										<tr>
											<td width="6%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
											<td width="68%" nowrap>&nbsp;<?php echo "$SPPDESC"; ?></td>
											<td width="12%" nowrap style="text-align:center">&nbsp;<?php echo $CSTCODE; ?></td>
											<td width="9%" nowrap style="text-align:right"><?php print number_format($SPPVOLM, $decFormat); ?>&nbsp;</td>
											<td width="9%" nowrap style="text-align:right"><?php echo $CSTUNIT; ?>&nbsp;</td>
											<td width="5%" nowrap style="text-align:center"><?php if($SPPVPRS == 1) { ?> <img src="<?php print base_url().'assets/AdminLTE-2.0.5/dist/img/checklist3.png'; ?>" width="20" height="20"> <?php } ?> </td>
										</tr>
										<?php
										$totSPPVOLM2 = $totSPPVOLM2 + $SPPVOLM;		
										}					
										if($NoU >= 21)
										{
											break;
										}
										//}
									}
									?>
									<tr>
										<td colspan="3" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
										<td nowrap style="text-align:right; font-weight:bold"><?php print number_format($totSPPVOLM2, $decFormat); ?>&nbsp;</td>
										<td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
										<td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
									</tr>
									<?php
								}
								else
								{
									?>
										<tr>
											<td colspan="6" nowrap style="text-align:center">--- None ---</td>
										</tr>
									<?php
								}
							?>		
						</table>					</td>
				</tr>
				<tr>
					<td colspan="3" valign="top" class="style2">
						<table width="100%" border="0">
							<tr style="font-size:10px;">
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="37%" style="text-align:right">Schedule Material on Site :</td>
								<td width="3%" style="text-align:center" nowrap>&nbsp;
									<?php $date = new DateTime($SchedMatOS);
										echo $date->format('d F Y'); 
									?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td colspan="3" valign="top" class="style2">
						<table width="100%" border="1">
							<tr style="font-size:10px;">
								<td width="14%" style="text-align:center">DIAJUKAN :<BR />PELAKSANA/ Koord.<br />PELAKSANA</td>
								<td width="14%" style="text-align:center">DIPERIKSA : <BR />ENGINEERING</td>
								<td width="14%" style="text-align:center">DIPERIKSA : <BR />COST CONTROL</td>
								<td width="14%" style="text-align:center">DISETUJUI : <BR /> SITE MANAGER (SM)<br />...................*)</td>
								<td width="14%" style="text-align:center">DISETUJUI : <BR />PROJ. MANAGER (PM)<br />...................*)</td>
								<td width="15%" style="text-align:center">DIPERIKSA (FINAL) :<BR />COST CONT. PUSAT/<BR />CABAGN *)</td>
								<td width="15%" style="text-align:center">DISETUJUI (FINAL / JIKA PERLU) :<BR />KEP. CABANG/ <BR />DIREKTUR ...........*)</td>
							</tr>
							<tr>
								<td>&nbsp;<BR /><BR /><BR /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr style="font-size:8px; font-style:italic">
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
							</tr>
							<tr style="font-size:8px; font-style:italic">
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
							</tr>
						</table>					</td>
				</tr>
                <tr>
                    <td colspan="3" class="style2" style="font-size:8px; font-weight:bold"><?php echo "$Creater_Desc2"; ?></td>
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
    <?php
		if($NoU	>= 20)
		{
			?>
				<tr>
					<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
					<td class="style2">&nbsp;</td>
					<td class="style2" style="text-align:center; font-weight:bold"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/'.$image; ?>" width="80" height="30" /></td>
				</tr>
				<tr>
					<td rowspan="4" class="style2" style="text-align:left; font-weight:bold;" valign="top"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="320" height="70"></td>
					<td colspan="2" nowrap class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:24px"> SPP (surat permintaan pengadaan)</td>
				</tr>
				<tr>
					<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:18px">Nomor : <?php echo $SPPCODE; ?></td>
				</tr>
				<tr>
					<td class="style2" style="text-align:left; font-weight:bold" valign="top" height="10">&nbsp;</td>
					<td class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
				</tr>
				<tr>
					<td class="style2" style="text-align:left;font-style:italic" valign="top">&nbsp;</td>
					<td class="style2" style="text-align:left;">Hal : 002/<?php echo $totPage; ?></td>
				</tr>
				<tr>
					<td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="style2" style="text-align:left;">Proyek :&nbsp;<span class="style2" style="text-align:left;"><?php echo "$PRJCODE - $PRJNAME"; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="style2" style="text-align:center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="style2">
						<table width="100%" border="1">
							<tr style="background:#CCCCCC">
								<td width="6%" style="text-align:center; font-weight:bold">No</td>
								<td width="68%" style="text-align:center; font-weight:bold">KETERANGAN</td>
								<td width="12%" style="text-align:center; font-weight:bold">Kode Item</td>
								<td width="9%" style="text-align:center; font-weight:bold">Qty</td>
								<td width="9%" style="text-align:center; font-weight:bold">Unit</td>
								<td width="5%" style="text-align:center; font-weight:bold">Proses</td>
							</tr>
							<?php
								$totSPPVOLM2	= 0;
								$sqlDT1R = 1; // ACAK DAN UAT KARENA HARUSNYA DARI MySQL
								if($sqlDT1R > 0)
								{
									/*$sqlDT2		= "SELECT * FROM tsppdt WHERE SPPCODE = '$SPPCODE'";
									$sqlDT2R	= $this->db->query($sqlDT2)->result();
									foreach($sqlDT2R as $rowdt2) :
										$CSTCODE		= $rowdt2->CSTCODE;
										$SPPDESC		= $rowdt2->SPPDESC;
										$SPPVOLM		= $rowdt2->SPPVOLM;
										$SPPVPRS		= $rowdt2->SPPVPRS;*/
										
									// DENGAN DIRECT DBF
									$NoU	= 0;
									//$DBFname = "SPPDT.DBF";
									//$db=dbase_open('./assets/files/'.$DBFname,0);
									//$db=dbase_open('F:/NKE/SDBP/'.$DBFname,0);
									//$jumlah=dbase_numrecords($db);
									
									$mySPPCode = $SPPCODE;
									$getSPPDT	= "SELECT SPPCODE, CSTCODE, SPPDESC, SPPVOLM, SPPVOLM, SPPVPRS, CSTUNIT FROM SPPDT.DBF WHERE SPPCODE = '$SPPCODE'";
									$qSPPDT		= odbc_exec($odbc, $getSPPDT) or die (odbc_errormsg());
									while($vSPPDT = odbc_fetch_array($qSPPDT))
									//for($x=1;$x<=$jumlah;$x++)
									{
										//$hasilDT=dbase_get_record_with_names($db,$x);
										$SPPCODE		= $vSPPDT['SPPCODE'];
										$SPPCODEDT		= $vSPPDT['SPPCODE'];
										$CSTCODE		= $vSPPDT['CSTCODE'];
										$SPPDESC		= $vSPPDT['SPPDESC'];
										$SPPVOLM		= $vSPPDT['SPPVOLM'];
										$SPPVPRS		= $vSPPDT['SPPVPRS'];
										$CSTUNIT		= $vSPPDT['CSTUNIT'];
										
										//if(trim($SPPCODEDT) == trim($mySPPCode))
										//{				
										$NoU	= $NoU + 1;
										if($NoU > 21)
										{
										?>
										<tr>
											<td width="6%" style="text-align:center" nowrap><?php echo $NoU; ?>.</td>
											<td width="68%" nowrap>&nbsp;<?php echo "$SPPDESC"; ?></td>
											<td width="12%" nowrap style="text-align:center">&nbsp;<?php echo $CSTCODE; ?></td>
											<td width="9%" nowrap style="text-align:right"><?php print number_format($SPPVOLM, $decFormat); ?>&nbsp;</td>
											<td width="9%" nowrap style="text-align:right"><?php echo $CSTUNIT; ?>&nbsp;</td>
											<td width="5%" nowrap style="text-align:center"><?php if($SPPVPRS == 1) { ?> <img src="<?php print base_url().'assets/AdminLTE-2.0.5/dist/img/checklist3.png'; ?>" width="20" height="20"> <?php } ?> </td>
										</tr>
										<?php
										$totSPPVOLM2 = $totSPPVOLM2 + $SPPVOLM;		
										}					
										if($NoU >= 31)
										{
											break;
										}
										//}
									}
									?>
									<tr>
										<td colspan="3" nowrap style="text-align:right; font-weight:bold">T O T A L :</td>
										<td nowrap style="text-align:right; font-weight:bold"><?php print number_format($totSPPVOLM2, $decFormat); ?>&nbsp;</td>
										<td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
										<td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
									</tr>
									<?php
								}
								else
								{
									?>
										<tr>
											<td colspan="6" nowrap style="text-align:center">--- None ---</td>
										</tr>
									<?php
								}
							?>		
						</table>					</td>
				</tr>
				<tr>
					<td colspan="3" valign="top" class="style2">
						<table width="100%" border="0">
							<tr style="font-size:10px;">
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="37%" style="text-align:right">Schedule Material on Site :</td>
								<td width="3%" style="text-align:center" nowrap>&nbsp;
									<?php $date = new DateTime($SchedMatOS);
										echo $date->format('d F Y'); 
									?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td colspan="3" valign="top" class="style2">
						<table width="100%" border="1">
							<tr style="font-size:10px;">
								<td width="14%" style="text-align:center">DIAJUKAN :<BR />PELAKSANA/ Koord.<br />PELAKSANA</td>
								<td width="14%" style="text-align:center">DIPERIKSA : <BR />ENGINEERING</td>
								<td width="14%" style="text-align:center">DIPERIKSA : <BR />COST CONTROL</td>
								<td width="14%" style="text-align:center">DISETUJUI : <BR /> SITE MANAGER (SM)<br />...................*)</td>
								<td width="14%" style="text-align:center">DISETUJUI : <BR />PROJ. MANAGER (PM)<br />...................*)</td>
								<td width="15%" style="text-align:center">DIPERIKSA (FINAL) :<BR />COST CONT. PUSAT/<BR />CABAGN *)</td>
								<td width="15%" style="text-align:center">DISETUJUI (FINAL / JIKA PERLU) :<BR />KEP. CABANG/ <BR />DIREKTUR ...........*)</td>
							</tr>
							<tr>
								<td>&nbsp;<BR /><BR /><BR /></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr style="font-size:8px; font-style:italic">
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
								<td>NAMA :</td>
							</tr>
							<tr style="font-size:8px; font-style:italic">
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
								<td>TANGGAL :</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
				  <td colspan="3" valign="top" class="style2" style="font-size:8px; font-weight:bold"><?php echo "$Creater_Desc2"; ?></td>
  </tr>
			<?php
		}
	?>
</table>
</body>
</html>
