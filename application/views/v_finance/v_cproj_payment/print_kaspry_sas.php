<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Juni 2018
 * File Name	= v_print_bpayment.php
 * Location		= -
*/

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

$LangID 	= $this->session->userdata['LangID'];
if($LangID == 'IND')
{
	$h1_title 	= 'VOUCHER PENGGUNAAN KAS/BANK';
}
else
{
	$h1_title 	= 'VOUCHER USED CASH/BANK';
}
//$CB_DATE = date('Y-m-d',strtotime($CB_DATE));

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

$JH_CODE	= '';
$JH_CODEM	= '';
$BNK_DESC	= '';
$JH_DESC	= '';
$sqlJH 		= "SELECT A.JournalH_Code, A.Manual_No, A.JournalH_Date, A.JournalH_Desc, A.acc_number, B.Account_NameId
				FROM tbl_journalheader A
					INNER JOIN tbl_chartaccount B ON A.acc_number = B.Account_Number
				WHERE JournalH_Code = '$CB_NUM'";
$resJH 	= $this->db->query($sqlJH)->result();
foreach($resJH as $rowJH) :
	$JH_CODE 	= $rowJH->JournalH_Code;
	$JH_CODEM 	= $rowJH->Manual_No;
	$JH_DATEY 	= date('Y', strtotime($rowJH->JournalH_Date));
	$JH_DATEM 	= date('m', strtotime($rowJH->JournalH_Date));
	$JH_DESC 	= $rowJH->JournalH_Desc;
	$BNK_DESC 	= $rowJH->Account_NameId;
endforeach;
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
        <td width="5%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td colspan="7" class="style2">&nbsp;</td>
        <td colspan="3" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="4" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="10" class="style2" style="text-align:center; font-weight:bold; font-size:18px; font-style:italic"><?php echo $h1_title; ?><br></td>
      </tr>
    <tr>
      <td width="7%" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td colspan="4" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td width="18%" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td width="1%" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td width="17%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
      <td width="1%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
      <td width="13%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td colspan="3" rowspan="2"  nowrap class="style2" style="text-align:left;">
        <?php /*?>PUTIH &nbsp;<br>
        MERAH&nbsp;&nbsp;<br>
        KUNING&nbsp;&nbsp;<?php */?> </td>
      <td width="29%" rowspan="2"  nowrap class="style2" style="text-align:left;">
      <?php /*?><span class="style2" style="text-align:left;">
      : &nbsp;GENERAL ACCOUNTING<br>
      : &nbsp;COST/BUDGET CONTROL<br>
      : &nbsp;ARSIP  
      </span><?php */?>
      </td>
      <td class="style2" style="text-align:left;">&nbsp;</td>
      <td colspan="4" rowspan="2" class="style2" style="text-align:left;">
        <table border="1" rules="all" width="100%">
          <tr>
            <td width="25%" style="text-align:center">TAHUN</td>
            <td width="25%" style="text-align:center">BULAN</td>
            <td width="50%" style="text-align:center">NO. URUT</td>
            </tr>
          <tr>
            <td style="text-align:center"><?php echo $JH_DATEY; ?></td>
            <td style="text-align:center"><?php echo $JH_DATEM; ?></td>
            <td style="text-align:center"><?php echo $JH_CODEM; ?></td>
            </tr>
          </table>      </td>
      </tr>
    <tr>
      <td class="style2" style="text-align:right;" nowrap>NO. URUT VOUCHER : </td>
    </tr>
    <tr>
      <td colspan="6" nowrap class="style2" style="text-align:left;">DIBAYAR KEPADA:</td>
      <td class="style2" style="text-align:right;" nowrap>&nbsp;</td>
      <td colspan="4" rowspan="2" nowrap class="style2" style="text-align:left;">
      	<table border="1" rules="all" width="100%">
          <tr>
            <td width="25%" style="text-align:center">TAHUN</td>
            <td width="25%" style="text-align:center">BULAN</td>
            <td width="50%" style="text-align:center">NO. URUT</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
      </td>
      </tr>
    <tr>
      <td colspan="6" nowrap class="style2" style="text-align:left;"><input type="checkbox"> UANG TUNAI/KAS</td>
      <td class="style2" style="text-align:right;" nowrap><span class="style2" style="text-align:right;">DIBAYAR/JATUH TEMPO :</span></td>
    </tr>
    <tr>
      <td colspan="6" nowrap class="style2" style="text-align:left;"><input type="checkbox"> CHEQUE/GIRO NO. :</td>
      <td class="style2" style="text-align:right;" nowrap>&nbsp;</td>
      <td colspan="4" rowspan="2" nowrap class="style2" style="text-align:left;">
      	<table border="1" rules="all" width="100%">
          <tr>
            <td width="16%" style="text-align:center">CODE</td>
            <td width="20%" style="text-align:center">PRY</td>
            <td width="11%" style="text-align:center">THN</td>
            <td width="13%" style="text-align:center">BLN</td>
            <td width="10%" style="text-align:center">TGL.</td>
            <td width="30%" style="text-align:center">NO. URUT</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
      </td>
      </tr>
    <tr>
      <td colspan="6" nowrap class="style2" style="text-align:left;">BANK : <?php echo $BNK_DESC; ?></td>
      <td class="style2" style="text-align:right;" nowrap>NO. PEMBAYRAN (KASIR) :</td>
    </tr>
    <tr>
      <td colspan="11" class="style2" height="4" style="text-align:left">DESKRIPSI : <?php echo $JH_DESC; ?></td>
    </tr>
    <tr>
      <td colspan="11" class="style2" height="4" style="text-align:left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" class="style2">
          <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="4%" height="37" nowrap style="text-align:center; font-weight:bold">NO.</td>
                    <td width="40%" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold">NO. PERK.</td>
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">PROYEK</td>
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">ITEM</td>
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">A3</td>
                    <td width="15%" nowrap style="text-align:center; font-weight:bold">JUMLAH</td>
                </tr>
                <?php
					$maxROW		= 10;
					$noU		= 0;
					$i			= 0;
					$totAmount	= 0;
					$sqlq2 	= "SELECT DISTINCT A.Acc_Id, A.proj_Code, A.Base_Kredit, A.JournalD_Debet, A.JournalD_Debet_tax,
									A.JournalD_Kredit, A.JournalD_Kredit_tax, A.isDirect, A.Notes, A.ITM_CODE,
									A.Ref_Number, A.Other_Desc, A.Journal_DK, A.isTax, B.Account_NameEn
									FROM tbl_journaldetail A
									INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
										AND B.PRJCODE = 100
									INNER JOIN tbl_journalheader C ON A.JournalH_Code = C.JournalH_Code
								WHERE A.JournalH_Code = '$CB_NUM' AND A.Journal_DK = 'D'";
					$resq2 	= $this->db->query($sqlq2)->result();
					foreach($resq2 as $rowsqlq2) :
						$noU				= $noU + 1;
						$Acc_Id 			= $rowsqlq2->Acc_Id;
						$PRJCODE 			= $rowsqlq2->proj_Code;
						$Base_Kredit		= $rowsqlq2->Base_Kredit;
						$Description		= $rowsqlq2->Account_NameEn;
						$JournalD_Debet 	= $rowsqlq2->JournalD_Debet;
						$JournalD_Debet_tax = $rowsqlq2->JournalD_Debet_tax;
						$JournalD_Kredit 	= $rowsqlq2->JournalD_Kredit;
						$JournalD_Kredit_tax= $rowsqlq2->JournalD_Kredit_tax;
						$isDirect 			= $rowsqlq2->isDirect;
						$Ref_Number 		= $rowsqlq2->Ref_Number;
						$Other_Desc 		= $rowsqlq2->Other_Desc;
						$Journal_DK 		= $rowsqlq2->Journal_DK;
						$isTax 				= $rowsqlq2->isTax;
												
						if($Journal_DK == 'D')
						{
							$AmountV		= $JournalD_Debet;
						}
						else
						{
							$AmountV		= $JournalD_Kredit;
						}
						
						$totAmount			= $totAmount + $AmountV;	
							
						if($isTax == 1)
						{
							if($Journal_DK == 'D')
							{
								$AmountV		= $JournalD_Debet_tax;
							}
							else
							{
								$AmountV		= $JournalD_Kredit_tax;
							}
							$isTaxD			= 'Tax';
						}
						else
						{
							$isTaxD			= 'No';
						}					
						
						?>
							<tr>
								<td nowrap style="text-align:center;">
									<?php echo "$noU."; ?>&nbsp;
								</td>
								<td nowrap style="text-align:left;">
									<?php echo "$Other_Desc"; ?>&nbsp;
								</td>
								<td style="text-align:center;" nowrap>
									<?php echo "$Acc_Id"; ?>&nbsp;
								</td>
								<td nowrap style="text-align:center;">
									<?php echo "$PRJCODE"; ?>&nbsp;
								</td>
								<td nowrap style="text-align:left;">
									<?php //echo "$DocumentNo"; ?>&nbsp;
								</td>
								<td nowrap style="text-align:left;">
									<?php //echo "$PRJCODE"; ?>&nbsp;
								</td>
								<td nowrap style="text-align:right;">
									<?php //echo number_format($Base_Kredit, 2); 
									echo number_format($AmountV, 2);
									?>&nbsp;
								</td>
							</tr>
						<?php
						$remROW	= $maxROW - $noU;
					endforeach;
					
					for($i=0;$i<=$remROW;$i++)
					{
					?>
                        <tr>
                            <td nowrap style="text-align:center;">&nbsp;</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:right;">&nbsp;</td>
                        </tr>
                    <?php
					}
				?>
                <tr height="40px">
                    <td colspan="5" nowrap style="text-align:left; border-right:none">TERBILANG :
                    	<font style="font-style:italic"><?php echo $terbilang = $moneyFormat->terbilang($totAmount); ?>&nbsp;</font>
                    </td>
                    <td nowrap style="text-align:right; font-weight:bold">TOTAL :</td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($totAmount, 2); ?>&nbsp;</td>
                </tr>
       	</table>
            <br>
        <table width="100%" border="1" rules="all">
                <tr>
                    <td width="8%" style="text-align:center">ACC</td>
                    <td width="8%" style="text-align:center">&nbsp;</td>
                    <td width="11%" style="text-align:center" nowrap>&nbsp;</td>
                  <td width="73%" rowspan="2" style="text-align:center; border-top-color:#FFF; border-bottom-color:#FFF; border-right-color:#FFF">CATATAN : TANDA TANGAN HARUS LENGKAP DENGAN NAMA DAN<br>TANGGAL SAAT MENANDATANGANI</td>
                </tr>
                <tr>
                    <td style="text-align:center">FIAT</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
        </table>
            <br>
            <table width="100%" border="1" rules="all">
                <tr>
                    <td colspan="2" style="text-align:center">YANG MEMBUAT</td>
                    <td colspan="2" style="text-align:center">DIPERIKSA</td>
                    <td colspan="2" style="text-align:center">DIINPUT</td>
                    <td colspan="2" style="text-align:center">KASIR</td>
                    <td colspan="2" style="text-align:center">PENERIMA</td>
                </tr>
                <tr height="60px">
                  <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                  <td width="8%" style="text-align:center" valign="top">TGL.</td>
                  <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                  <td width="8%" style="text-align:center" valign="top">TGL.</td>
                  <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                  <td width="8%" style="text-align:center" valign="top">TGL.</td>
                  <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                  <td width="8%" style="text-align:center" valign="top">TGL.</td>
                  <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                  <td width="8%" style="text-align:center" valign="top">TGL.</td>
                </tr>
                <tr height="25px">
                  <td colspan="2" style="text-align:left">NAMA :</td>
                  <td colspan="2" style="text-align:left">NAMA :</td>
                  <td colspan="2" style="text-align:left">NAMA :</td>
                  <td colspan="2" style="text-align:left">NAMA :</td>
                  <td colspan="2" style="text-align:left">NAMA :</td>
                </tr>
            </table>
        <font size="-4" style="font-style:italic">©Hakcipta, PT. SASMITO</font>
    	</td>
    </tr>
</table>
</section>
</body>
</html>