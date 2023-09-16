<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 28 Juni 2018
 * File Name    = v_print_bpayment.php
 * Location     = -
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
    
$sqlApp         = "SELECT * FROM tappname";
$resultaApp     = $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
    $appName    = $therow->app_name;
    $comp_init  = $therow->comp_init;
    $comp_name  = $therow->comp_name;
endforeach;

$DefEmp_ID  = $this->session->userdata['Emp_ID'];

$LangID     = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
    $h1_title   = 'Bukti Pengeluaran Kas / Bank';
}
else
{
    $dh1_title  = 'Bukti Pengeluaran Kas / Bank';
}
$CB_DATE = date('d-m-Y',strtotime($CB_DATE));

$CB_PAYFOR      = '';
$CB_TOTAM       = '';
$CB_TOTAM_PPn   = '';
$CB_NOTES       = '';
$sqlq1  = "SELECT A.CB_CODE, A.CB_PAYFOR, A.CB_TOTAM, A.CB_TOTAM_PPn, A.CB_NOTES, B.SPLDESC, B.SPLNPWP, B.SPLNOREK, B.SPLNMREK, B.SPLBANK
                FROM tbl_bp_header A
                    INNER JOIN tbl_supplier B ON A.CB_PAYFOR = B.SPLCODE
                WHERE CB_NUM = '$CB_NUM'";
$resq1  = $this->db->query($sqlq1)->result();
foreach($resq1 as $rowsqlq1) :
    $CB_CODE        = $rowsqlq1->CB_CODE;
    $CB_PAYFOR      = $rowsqlq1->CB_PAYFOR;
    $CB_TOTAM       = $rowsqlq1->CB_TOTAM;
    $CB_TOTAM_PPn   = $rowsqlq1->CB_TOTAM_PPn;
    $GCB_TOTAM      = $CB_TOTAM + $CB_TOTAM_PPn;
    $CB_NOTES       = $rowsqlq1->CB_NOTES;
    $SPLDESC        = $rowsqlq1->SPLDESC;
    $SPLNPWP        = $rowsqlq1->SPLNPWP;
    $SPLNOREK       = $rowsqlq1->SPLNOREK;
    $SPLNMREK       = $rowsqlq1->SPLNMREK;
    $SPLBANK        = $rowsqlq1->SPLBANK;
endforeach;
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
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
        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td width="83%" class="style2">&nbsp;</td>
        <td colspan="3" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/'. $comp_init . '/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td class="style2" style="text-align:center; font-weight:bold; font-size:18px"><u><?php echo $h1_title; ?></u><br>
          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px">( B P K )</span>
      </td>
        <td width="3%" valign="top" class="style2" style="text-align:left; font-size:12px">No.</td>
        <td width="1%" valign="top" class="style2" style="text-align:left; font-size:12px"><span class="style2" style="text-align:left; font-size:12px"> :</span></td>
        <td width="10%" class="style2" style="text-align:left; font-size:12px" valign="top"><u><?php echo $CB_CODE; ?></u></td>
    </tr>
    <tr>
        <td colspan="5" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="style2" style="text-align:left">
        <table width="100%" border="0" style="size:auto">
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="12%"><input type="checkbox">&nbsp;Bahan</td>
                <td width="15%"><input type="checkbox">&nbsp;Biaya Umum</td>
                <td width="16%"><input type="checkbox">&nbsp;Alat</td>
                <td width="12%"><input type="checkbox">&nbsp;Rupa-rupa</td>
                <td width="15%">&nbsp;</td>
                <td width="9%">No. Proyek</td>
                <td width="1%">:</td>
                <td width="10%"><u><?php echo $PRJCODE; ?></u></td>
            </tr>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="12%"><input type="checkbox">&nbsp;Upah</td>
                <td width="15%"><input type="checkbox">&nbsp;Persiapan</td>
                <td width="16%"><input type="checkbox">&nbsp;Sub Kontraktor</td>
                <td width="12%">&nbsp;</td>
                <td width="15%">&nbsp;</td>
                <td width="9%">KBP,</td>
                <td width="1%">:</td>
                <td width="10%"><u><?php echo $CB_DATE; ?></u></td>
            </tr>
            <tr>
              <td height="28">&nbsp;</td>
              <td valign="bottom">Bayar Kepada</td>
              <td valign="bottom">: <?php echo $SPLDESC; ?></td>
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
        <td colspan="5" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="34%" height="37" nowrap style="text-align:center; font-weight:bold">Keterangan</td>
                    <td width="33%" nowrap style="text-align:center; font-weight:bold">No. Rekening</td>
                    <td width="33%" nowrap style="text-align:center; font-weight:bold">J u m l a h</td>
                </tr>
                <tr height="300px">
                    <td nowrap style="text-align:center; vertical-align:top">&nbsp;
                        <?php
                            echo "$CB_NOTES";
                        ?>
                    </td>
                    <td style="text-align:center; vertical-align:top" nowrap>&nbsp;
                        <?php
                            echo "$SPLNOREK an. $SPLNMREK<br>$SPLBANK";
                        ?>
                    </td>
                    <td style="text-align:right; vertical-align:top" nowrap>&nbsp;
                        <?php
                            echo number_format($GCB_TOTAM, 2);
                        ?>
                    </td>
                </tr>
            </table>
            <table width="100%" border="1" style="size:auto; border-top:hidden" rules="all">
                <tr>
                    <td width="17%">
                        <div class="col-xs-12" style="text-align:center">Dibayar dengan :</div>
                        <div class="col-xs-12" style="padding-left:10px; padding-right:10px">
                            <ul type="square">
                                <li>Tunai</li>
                                <li>Cek / GB</li>
                            </ul>
                        </div>
                        <div class="col-xs-12" style="padding-left:15px;">
                            No.&nbsp;&nbsp;&nbsp;&nbsp;: .....................
                        </div>
                        <div class="col-xs-12" style="padding-left:15px;">
                            Bank : ....................
                        </div>
                    </td>
                    <td width="42%" valign="top">
                        <table width="100%" border="0">
                            <tr height="30px" valign="middle">
                                <td width="32%" height="129" style="text-align:center">
                                    Dibuat oleh :
                                    <br><br><br><br><br>
                                    <u>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </u>
                                </td>
                                <td width="36%" style="text-align:center">
                                    Diperiksa :
                                    <br><br><br><br><br>
                                    <u>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </u>
                                </td>
                                <td width="32%" style="text-align:center">
                                    Disetujui :
                                    <br><br><br><br><br>
                                    <u>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </u>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="41%">
                        <table width="100%" border="0">
                            <tr height="30px" valign="middle">
                                <td width="32%" height="129">
                                    Telah diterima sejumlah
                                    <br><br><br><br><br>
                                    <div style="text-align:center">________________<br>
                                    Penerima</div>
                                </td>
                              <td width="36%">
                                    Rp <?php echo number_format($GCB_TOTAM, 2); ?>
                                    <br><br><br><br><br>
                                    <div style="text-align:center"><?php echo $CB_DATE; ?><br>
                                    Tanggal</div>
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