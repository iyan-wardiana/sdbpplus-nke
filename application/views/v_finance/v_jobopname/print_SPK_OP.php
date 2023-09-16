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
$sql = "SELECT * FROM tlanguage WHERE Language_Status = 1";
$reslang = $this->db->query($sql)->result();
foreach($reslang as $row) :
	$Language_ID	= $row->Language_ID;
endforeach;

$sql = "SELECT code_translate, transalate_$Language_ID as myTransalte FROM ttranslate";
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
endforeach;
// End : Query ini wajib dipasang disetiap halaman View

// Start --- Get SPK Header
$qGetSPKCode	= "SELECT A.*, B.PRJNAME, B.PRJLOCT
					FROM SPKHD A INNER JOIN TPROJECT B ON A.PRJCODE = B.PRJCODE
					WHERE SPKCODE = '$chckSPKCode'";

$resultHD = $this->db->query($qGetSPKCode)->result();
foreach($resultHD as $rowHD) :
	$SPKCODE = $rowHD->SPKCODE;
	$SPKTYPE = $rowHD->SPKTYPE;
	$TRXDATE = $rowHD->TRXDATE;
	$PRJCODE = $rowHD->PRJCODE;
	$PRJNAME = $rowHD->PRJNAME;
	$PRJLOCT = $rowHD->PRJLOCT;
	$SPLCODE = $rowHD->SPLCODE;
	$SPKCOST = $rowHD->SPKCOST;
	$SPKPPNH = $rowHD->SPKPPNH;
	$TRXCOMP = $rowHD->TRXCOMP;
	$TRXPOST = $rowHD->TRXPOST;
	$TRXPDAT = $rowHD->TRXPDAT;
	$TRXOPEN = $rowHD->TRXOPEN;
	$TRXUSER = $rowHD->TRXUSER;
	$DP_CODE = $rowHD->DP_CODE;
	$DP_PPN_ = $rowHD->DP_PPN_;
	$DP_JUML = $rowHD->DP_JUML;
	$DP_JPPN = $rowHD->DP_JPPN;
endforeach;
$TRXDATE1 = date('Y/m/d',strtotime($TRXDATE));
$TRXPDAT1 = date('Y/m/d',strtotime($TRXPDAT));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">
@import url("<?php echo base_url() . 'css/style_table.css'; ?>");
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
    	<td width="16%">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
			<img src="<?php echo base_url().'images/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
        <td width="76%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="8%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
  </tr>
    <tr>
    	<td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
<td colspan="3" class="style2" style="text-align:left;">
            <table width="100%" border="1" rules="all">
                <tr>
                    <td colspan="2" rowspan="3" style="border-right-color:#FFFFFF"><img src="<?php echo base_url().'images/LogoOpName.jpg'; ?>" width="120" height="50" /></td>
                    <td colspan="6" style="text-align:left; font-weight:bold; font-size:14px; border-bottom-color:#FFFFFF; font-family:Times New Roman">PT NUSA KONSTRUKSI ENJINIRING, Tbk</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:left; font-size:10px; border-bottom-color:#FFFFFF; font-family:Times New Roman">JL. SUNAN KALIJAGA NO. 64 JAKARTA 12160 - INDONESIA<br />
                    TELP. (62) 021 7221003 (HUNTING)., FAX. : (62) 021 7396580<br />
    E-mail : corporate@nusakonstruksi.com</td>
                </tr>
                <tr>
                    <td colspan="6" style="border-left-color:"><hr /></td>
                </tr>
                    <?php
                        $sqlOPStep	 = "tjobopname WHERE SPKCODE = '$SPKCODE'";
                        $cOPStep 	= $this->db->count_all($sqlOPStep);
                        if($cOPStep == 0)
                        {
                            $nextOPStep = 1;
                        }
                        else
                        {
                            $qGetMaxOPStep	= "SELECT MAX(OPSTEP) AS MaxOPSTEP FROM tjobopname WHERE SPKCODE = '$SPKCODE'";
                            $resultOPStep 	= $this->db->query($qGetMaxOPStep)->result();
                            foreach($resultOPStep as $rowOPStep) :
                                $MaxOPSTEP = $rowOPStep->MaxOPSTEP;
                            endforeach;
                            $nextOPStep = $MaxOPSTEP + 1;
                        }						
                    ?>
                <tr>
                  <td colspan="8" height="25" style="text-align:center; font-weight:bold; font-size:15px; border-bottom-color:#FFFFFF; font-family:Times New Roman">
                  OPNAME KE : <?php echo $myStep; ?>
                  <input type="text" name="nextOPStep" id="nextOPStep" value="<?php echo $nextOPStep; ?>" style="display:none" />                      </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td width="10%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td width="11%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td width="13%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td width="15%" style="border-left-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman;font-size:14px; font-weight:bold">No. SPK</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman;font-size:15px; font-weight:bold">: <?php echo $SPKCODE; ?></td>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman;font-size:12px;">&nbsp;</td>
                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman;font-size:12px;">Tgl. SPK</td>
                    <td colspan="3" style="border-left-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman;font-size:12px;">: <?php echo $TRXDATE1; ?></td>
                </tr>
                <tr style="font-size:12px;">
                    <td colspan="2" style="border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">Nama Proyek</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo "$PRJCODE - $PRJNAME"; ?></td>
                    <?php
                        // Start --- Get SPK Header
                        $qGetSupplier	= "SELECT * FROM tsupplier
                                            WHERE SPLCODE = '$SPLCODE'";
                        
                        $resultSp = $this->db->query($qGetSupplier)->result();
                        foreach($resultSp as $rowSp) :
                            $SPLCODE = $rowSp->SPLCODE;
                            $SPLDESC = $rowSp->SPLDESC;
                            $SPLADD1 = $rowSp->SPLADD1;
                            $SPLADD2 = $rowSp->SPLADD2;
                            $SPLKOTA = $rowSp->SPLKOTA;
                            $SPLTELP = $rowSp->SPLTELP;
                            $SPLFAXI = $rowSp->SPLFAXI;
                        endforeach;
                    ?>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">Mandor</td>
                    <td colspan="3" style="border-left-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo "$SPLCODE - $SPLDESC"; ?></td>
                </tr>
                <tr style="font-size:12px;">
                    <td colspan="2" style="border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman" valign="top">Alamat</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman" valign="top">: <?php echo $PRJLOCT; ?></td>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman" valign="top">Alamat</td>
                    <td colspan="3" style="border-left-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo "$SPLADD1,"; if($SPLADD2!=''){ ?>
                    <br />&nbsp;&nbsp;<?php echo "$SPLADD2 - $SPLKOTA"; } if($SPLTELP != ''){ ?>
                    <br />&nbsp;&nbsp;<?php echo "TLP. $SPLTELP"; } ?></td>
                </tr>
                <tr style="font-size:12px;">
                    <td colspan="2" style="border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">Tgl. Mulai</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo $TRXDATE1; ?></td>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">Tgl. Selesai</td>
                    <td colspan="3" style="border-left-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo $TRXPDAT1; ?></td>
                </tr>
                <?php
                    $SPKVOLM2 = 0;
                    $TOTHRG2 = 0;
                    $oth_ItemSPK = "oth_$SPKCODE";
                    $qGetAkVol	= "SELECT STRDATE, ENDDATE FROM tjobopname
                                    WHERE SPKCODE = '$SPKCODE' AND OPSTEP = '$myStep'";
                    
                    $resultAkVol = $this->db->query($qGetAkVol)->result();
                    foreach($resultAkVol as $rowAkVol) :
                        $STRDATE	= $rowAkVol->STRDATE;
                        $ENDDATE 	= $rowAkVol->ENDDATE;
                    endforeach;
                    $STRDATE1 = date('Y/m/d',strtotime($STRDATE));
                    $ENDDATE1 = date('Y/m/d',strtotime($ENDDATE));
                ?>
                <tr style="font-size:12px;">
                    <td colspan="2" style="border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">Periode Mulai</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo $STRDATE1; ?></td>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">&nbsp;</td>
                    <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">Periode Akhir</td>
                    <td colspan="3" style="border-left-color:#FFFFFF; border-bottom-color:#FFFFFF; font-family:Times New Roman">: <?php echo $ENDDATE1; ?></td>
                </tr>
                <tr style="font-size:12px;">
                    <td colspan="2" style="border-right-color:#FFFFFF;">&nbsp;</td>
                    <td width="29%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF;">&nbsp;</td>
                    <td width="9%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF;">&nbsp;</td>
                    <td width="10%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF;">&nbsp;</td>
                    <td width="11%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF;">&nbsp;</td>
                    <td width="13%" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF;">&nbsp;</td>
                    <td width="15%" style="border-left-color:#FFFFFF;">&nbsp;</td>
                </tr>
                <input type="hidden" name="mySPKCode" id="mySPKCode" value="<?php echo $myStep; ?>" />
                <tr style="font-size:12px;">
                    <td width="3%" rowspan="2" style="text-align:center; font-family:Times New Roman">No.</td>
                    <td width="10%" rowspan="2" style="text-align:center; font-family:Times New Roman">Kode Item</td>
                    <td rowspan="2" style="text-align:center; font-family:Times New Roman">Item Pekerjaan</td>
                    <td rowspan="2" style="text-align:center; font-family:Times New Roman">Volume</td>
                    <td rowspan="2" style="text-align:center; font-family:Times New Roman">Total Harga</td>
                    <td colspan="2" style="text-align:center; font-family:Times New Roman">Kumulatif</td>
                    <td rowspan="2" style="text-align:center; font-family:Times New Roman">Keterangan</td>
                </tr>
                <tr style="font-size:12px;">
                    <td style="text-align:center; font-family:Times New Roman">Volume</td>
                    <td style="text-align:center; font-family:Times New Roman">Total Harga</td>
                </tr>
                <?php
                    // Start --- Get SPK Detail
                    $i = 0;
                    $totSPK = 0;
                    $totMaxRow = 32; 
                    $sqlR	= "tjobopname WHERE SPKCODE = '$chckSPKCode' AND OPSTEP = '$myStep'";
                    $qtyRow = $this->db->count_all($sqlR);
    
                    $remLoop = 32 - $qtyRow;
                    
                    $qGetSPKDet	= "SELECT A.*
                                        FROM tjobopname A INNER JOIN SPKHD B ON A.SPKCODE = B.SPKCODE
                                        WHERE A.SPKCODE = '$chckSPKCode' AND OPSTEP = '$myStep'";
                    $resultDT = $this->db->query($qGetSPKDet)->result();
                    foreach($resultDT as $rowDT) :
                        $myNewNo1	= ++$i;
                        $SPKCODE 	= $rowDT->SPKCODE;
                        $CSTCODE 	= $rowDT->CSTCODE;
                        $CSTUNIT 	= $rowDT->CSTUNIT;						
                        $SPKVOLM 	= $rowDT->SPKVOLM;		
                        $TOTHRG 	= $rowDT->TOTHRG;
                        $SPKDESC 	= $rowDT->CSTCODEDESC;
                        $totSPK 	= $totSPK + $TOTHRG;
                        
                        $OTHEREXP	= 0;
                        $OTHEREXPTH = 0;
                ?>
                <tr style="font-size:12px;">
                    <td style="text-align:center; font-family:Times New Roman"><?php echo $myNewNo1; ?>.</td>
                    <td style="text-align:left; font-family:Times New Roman">
                    <input type="text" name="CSTCODE" id="CSTCODE" value="<?php echo $CSTCODE; ?>" size="6" style="display:none" />
                    <?php echo $CSTCODE; ?></td>
                    <td style="text-align:left; font-family:Times New Roman"><?php echo $SPKDESC; ?></td>
                    <td style="text-align:right; font-family:Times New Roman">
                    <input type="text" style="display:none" name="SPKVOLM" id="SPKVOLM" value="<?php echo $SPKVOLM; ?>" size="8" />
                    <?php print number_format($SPKVOLM, $decFormat); ?>&nbsp;</td>
                    <td style="text-align:right; font-family:Times New Roman">
                    <input type="text" name="TOTHRG" id="TOTHRG" value="<?php echo $TOTHRG; ?>" size="10" style="display:none" />
                    <?php print number_format($TOTHRG, $decFormat); ?>&nbsp;</td>
                    <?php
                        $SPKVOLM2 = 0;
                        $TOTHRG2 = 0;
                        $oth_ItemSPK = "oth_$SPKCODE";
                        $qGetAkVol	= "SELECT SPKVOLM, TOTHRG, OPVOLM, OPTOTHRG FROM tjobopname
                                        WHERE SPKCODE = '$SPKCODE' AND OPSTEP = '$myStep'";
                        
                        $resultAkVol = $this->db->query($qGetAkVol)->result();
                        foreach($resultAkVol as $rowAkVol) :
                            $SPKVOLM1	= $rowAkVol->SPKVOLM;
                            $TOTHRG1 	= $rowAkVol->TOTHRG;
                            $OPVOLM1 	= $rowAkVol->OPVOLM;
                            $OPTOTHRG1= $rowAkVol->OPTOTHRG;
                        endforeach;
                    ?>
                    <td style="text-align:right; font-family:Times New Roman"><?php print number_format($OPVOLM1, $decFormat); ?>&nbsp;</td>
                    <td style="text-align:right; font-family:Times New Roman"><?php print number_format($OPTOTHRG1, $decFormat); ?>&nbsp;</td>
                    <td style="text-align:left; font-family:Times New Roman"><?php echo "-"; ?></td>
                </tr>
                <?php
                    endforeach;
                    
                    $CSTCODEDESC = '';
                    $OTHEREXP2 = 0;
                    $OTHEREXPTH2 = 0;
                    $qGetOthExp	= "SELECT * FROM tjobopname
                                    WHERE SPKCODE = '$SPKCODE' AND CSTCODE = '$oth_ItemSPK'";
                    $resultOExp = $this->db->query($qGetOthExp)->result();
                    foreach($resultOExp as $rowOExp) :
                        $CSTCODEDESC	= $rowOExp->CSTCODEDESC;
                        $OTHEREXP1		= $rowOExp->OTHEREXP;
                        $OTHEREXP2 		= $OTHEREXP2 + $OTHEREXP1;
                        $OTHEREXPTH1	= $rowOExp->OTHEREXPTH;
                        $OTHEREXPTH2	= $OTHEREXPTH2 + $OTHEREXPTH1;
                    endforeach;
					for ($x = 0; $x < $remLoop; $x++)
					{
                ?>
                <tr style="font-size:12px;">
                    <td style="text-align:center; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:left; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:left; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:right; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:right; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:right; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:right; font-family:Times New Roman">&nbsp;</td>
                    <td style="text-align:left; font-family:Times New Roman">&nbsp;</td>
                </tr>
                <?php
					}
				?>
                <tr style="font-size:12px;">
                    <td colspan="8" style="text-align:center; font-family:Times New Roman">&nbsp;</td>
                </tr>
                <tr style="font-size:12px;">
					<td colspan="8" style="text-align:center; font-family:Times New Roman">
                        <table width="100%" border="1" rules="all">
                            <tr style="font-size:12px; font-family:Times New Roman">
                              <td width="20%" style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Engineering (QS)</td>
                              <td width="20%" style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Site Manager (SM)/<br />
                              Koord. MEP Proyek*)</td>
                              <td width="20%" style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Project Manager (PM)/<br />
                              ................................................*)</td>
                              <td width="20%" style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Cost Control (CC) Pusat/<br />
                              ................................................*)</td>
                              <td width="20%" style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;border-right-color:#FFFFFF;" nowrap >Mandor /<br />
                              Jab. : ...............................</td>
                            </tr>
                            <tr style="font-size:12px; font-family:Times New Roman">
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;border-right-color:#FFFFFF;" nowrap >&nbsp;</td>
                            </tr>
                            <tr style="font-size:12px; font-family:Times New Roman">
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;border-right-color:#FFFFFF;" nowrap >&nbsp;</td>
                            </tr>
                            <tr style="font-size:12px; font-family:Times New Roman">
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;border-right-color:#FFFFFF;" nowrap >&nbsp;</td>
                            </tr>
                            <tr style="font-size:12px; font-family:Times New Roman">
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>&nbsp;</td>
                              <td style="text-align:center; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;border-right-color:#FFFFFF;" nowrap >&nbsp;</td>
                            </tr>
                            <tr style="font-size:12px; font-family:Times New Roman">
                              <td style="text-align:left; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Nama: ..........................................<br />Tgl.:</td>
                              <td style="text-align:left; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Nama: ..........................................<br />Tgl.:</td>
                              <td style="text-align:left; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Nama: ..........................................<br />Tgl.:</td>
                              <td style="text-align:left; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;" nowrap>Nama: ..........................................<br />Tgl.:</td>
                              <td style="text-align:left; border-left-color:#FFFFFF;border-top-color:#FFFFFF;border-bottom-color:#FFFFFF;border-right-color:#FFFFFF;" nowrap>Nama: ..........................................<br />Tgl.:</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
      </td>
    </tr>
</table>
</body>
</html>