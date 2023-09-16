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
	$h1_title 	= 'VOUCHER PEMBAYARAN';
}
else
{
	$dh1_title 	= 'VOUCHER PAYMENT';
}
$CB_DATE = date('Y-m-d',strtotime($CB_DATE));

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
    <style>
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 12pt Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
        width: 21cm;
        min-height: 29.7cm;
        margin: 0.5cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
    </style>
</head>
<body style="overflow:auto">
<div class="page">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td colspan="17" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
      </tr>
    <tr>
        <td width="1%" rowspan="4" valign="top" class="style2" style="text-align:left; font-weight:bold; padding:10px"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="16" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><u><?php echo $h1_title; ?></u>&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
    <tr>
      <td colspan="11" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td width="39%" colspan="4" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="11" class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td colspan="2" rowspan="2" class="style2" style="text-align:left;">
      	<table border="1" rules="all" width="100%">
          <tr>
            <td width="25%" style="text-align:center">TAHUN</td>
            <td width="25%" style="text-align:center">BULAN</td>
            <td width="50%" style="text-align:center">NO. URUT</td>
            </tr>
          	<tr>
                <td style="text-align:center">&nbsp;<?php echo $CB_DATEY; ?></td>
                <td style="text-align:center">&nbsp;<?php echo $CB_DATEM; ?></td>
                <td style="text-align:center">&nbsp;<?php echo $CB_CODE; ?></td>
            </tr>
          </table>
      </td>
      </tr>
    <tr>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td class="style2" style="text-align:center; font-weight:bold; font-size:18px">&nbsp;</td>
      <td width="26%" class="style2" style="text-align:right;">NO. URUT VOUCHER :</td>
      </tr>
    <tr>
      <td colspan="11" nowrap class="style2" style="text-align:left; padding-left:10px;">DIBAYAR KEPADA:</td>
      <td class="style2" style="text-align:right;" nowrap>&nbsp;</td>
      <td colspan="2" rowspan="2" nowrap class="style2" style="text-align:left;">
      	<table border="1" rules="all" width="100%">
          <tr>
            <td width="25%" style="text-align:center">TAHUN</td>
            <td width="25%" style="text-align:center">BULAN</td>
            <td width="50%" style="text-align:center">NO. URUT</td>
            </tr>
            <?php
			if($countDocInv > 0)
			{
				foreach($vwDocInv as $r):
					$docNum 	= $r->docNum;
					$dateInv	= $r->dateInv;
					$dateInvY 	= date('Y', strtotime($r->dateInv));
					$dateInvM 	= date('m', strtotime($r->dateInv));
				endforeach;
			}
			?>
          <tr>
            <td style="text-align:center">&nbsp;<?php echo $dateInvY; ?></td>
            <td style="text-align:center">&nbsp;<?php echo $dateInvM; ?></td>
            <td style="text-align:center">&nbsp;<?php echo $docNum; ?></td>
            </tr>
          </table>
      </td>
      </tr>
    <tr>
      <td colspan="11" nowrap class="style2" style="text-align:left;"><input type="checkbox"> UANG TUNAI/KAS</td>
      <td class="style2" style="text-align:right;" nowrap><span class="style2" style="text-align:right;">DIBAYAR/JATUH TEMPO :</span></td>
      </tr>
    <tr>
      <td colspan="11" nowrap class="style2" style="text-align:left;"><input type="checkbox"> CHEQUE/GIRO NO. :</td>
      <td class="style2" style="text-align:right;" nowrap>&nbsp;</td>
      <td colspan="2" rowspan="2" nowrap class="style2" style="text-align:left;">
      	<table border="1" rules="all" width="100%">
          <tr>
            <td width="16%" style="text-align:center">CODE</td>
            <td width="20%" style="text-align:center">PRY</td>
            <td width="11%" style="text-align:center">THN</td>
            <td width="13%" style="text-align:center">BLN</td>
            <td width="10%" style="text-align:center">TGL.</td>
            <td width="30%" style="text-align:center" nowrap>NO. URUT</td>
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
      <td colspan="11" class="style2" style="text-align:left; padding-left:10px; vertical-align:bottom">
        BANK :
        <?php
	  if($countAccName > 0)
	  {
	  	foreach($vwAccName as $row):
			echo $row->Account_Name;
		endforeach;
	  }
	  ?>
      </td>
      <td class="style2" style="text-align:right; padding-left:10px; vertical-align:top" nowrap>NO. PEMBAYRAN (KASIR) :</td>
      </tr>
    <tr>
      <td colspan="17" class="style2" height="4" style="text-align:left; line-height:2px;">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="17" class="style2">
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
					$CB_PAYFOR 		= '';
					$CB_TOTAM 		= '';
					$CB_TOTAM_PPn 	= '';
					$CB_NOTES 		= '';
					$sqlq1 	= "SELECT A.CB_PAYFOR, A.CB_TOTAM, A.CB_TOTAM_PPn, A.CB_NOTES,
										B.SPLDESC, B.SPLNPWP, B.SPLNOREK, B.SPLNMREK, B.SPLBANK
									FROM tbl_bp_header A
										INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
									WHERE CB_NUM = '$CB_NUM'";
					$resq1 	= $this->db->query($sqlq1)->result();
					foreach($resq1 as $rowsqlq1) :
						$CB_PAYFOR 		= $rowsqlq1->CB_PAYFOR;
						$CB_TOTAM 		= $rowsqlq1->CB_TOTAM;
						$CB_TOTAM_PPn 	= $rowsqlq1->CB_TOTAM_PPn;
						$GCB_TOTAM		= $CB_TOTAM + $CB_TOTAM_PPn;
						$CB_NOTES 		= $rowsqlq1->CB_NOTES;
						$SPLDESC 		= $rowsqlq1->SPLDESC;
						$SPLNPWP 		= $rowsqlq1->SPLNPWP;
						$SPLNOREK 		= $rowsqlq1->SPLNOREK;
						$SPLNMREK 		= $rowsqlq1->SPLNMREK;
						$SPLBANK 		= $rowsqlq1->SPLBANK;
					endforeach;
					
					$maxROW	= 10;
					$noU	= 0;
					$i		= 0;
					$totVOC	= 0;
					$sqlq2 	= "SELECT PRJCODE, CB_CODE, CB_CATEG, DocumentNo, DocumentRef, Description,
									Inv_Amount, Inv_Amount_PPn, Amount, Amount_PPn, Acc_ID
								FROM tbl_bp_detail
								WHERE CB_NUM = '$CB_NUM'";
					$resq2 	= $this->db->query($sqlq2)->result();
					foreach($resq2 as $rowsqlq2) :
						$noU			= $noU + 1;
						$PRJCODE 		= $rowsqlq2->PRJCODE;
						$CB_CODE 		= $rowsqlq2->CB_CODE;
						$CB_CATEG 		= $rowsqlq2->CB_CATEG;
						$DocumentNo 	= $rowsqlq2->DocumentNo;
						$DocumentRef 	= $rowsqlq2->DocumentRef;
						$Description 	= $rowsqlq2->Description;
						$Inv_Amount 	= $rowsqlq2->Inv_Amount;
						$Inv_Amount 	= $rowsqlq2->Inv_Amount;
						$Inv_Amount_PPn = $rowsqlq2->Inv_Amount_PPn;
						$Amount 		= $rowsqlq2->Amount;
						$Amount_PPn 	= $rowsqlq2->Amount_PPn;
						$Acc_ID 		= $rowsqlq2->Acc_ID;
						$totAmount		= $Amount + $Amount_PPn;
						$totVOC			= $totVOC + $totAmount;
						?>
							<tr>
								<td nowrap style="text-align:center;">
									<?php echo "$noU."; ?>
								</td>
								<td nowrap style="text-align:justify;" width="304">
									<?php echo "$Description"; ?>
								</td>
								<td style="text-align:center;" nowrap width="83">
									<?php echo "$Acc_ID"; ?>
								</td>
								<td nowrap style="text-align:center;" width="76">
									<?php echo "$PRJCODE"; ?>
								</td>
								<td nowrap style="text-align:justify;" width="76">
									<?php echo "$DocumentNo"; ?>
								</td>
								<td nowrap style="text-align:left;" width="75">
									<?php //echo "$PRJCODE"; ?>
								</td>
								<td nowrap style="text-align:right;" width="106">
									<?php echo number_format($totAmount, 2); ?>
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
                    	<font style="font-style:italic"><?php echo $terbilang = $moneyFormat->terbilang($totVOC); ?></font>
                    </td>
                    <td nowrap style="text-align:right; font-weight:bold">TOTAL :</td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($totVOC, 2); ?></td>
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
                    <td colspan="2" style="text-align:center">DIBUAT/DIINPUT</td>
                    <td colspan="2" style="text-align:center">DIPERIKSA</td>
                    <td colspan="2" style="text-align:center">DISETUJUI</td>
                    <td colspan="2" style="text-align:center">DIBAYAR</td>
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
</div>
</body>
</html>