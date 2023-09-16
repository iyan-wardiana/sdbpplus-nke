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
	$h1_title 	= 'PEMBAYARAN UANG MUKA';
}
else
{
	$dh1_title 	= 'DOWN PAYMENT';
}

$sqlq1 	= "SELECT A.DP_CODE, A.DP_DATE, A.PRJCODE, A.SPLCODE, A.DP_AMOUNT, A.DP_NOTES,
					B.SPLDESC, B.SPLNPWP, B.SPLNOREK, B.SPLNMREK, B.SPLBANK
				FROM tbl_dp_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE DP_NUM = '$DP_NUM'";
$resq1 	= $this->db->query($sqlq1)->result();
foreach($resq1 as $rowsqlq1) :
	$DP_CODE 	= $rowsqlq1->DP_CODE;
	$DP_DATE 	= $rowsqlq1->DP_DATE;
	$DP_DATE 	= date('d/m/Y',strtotime($DP_DATE));
	$PRJCODE 	= $rowsqlq1->PRJCODE;
	$SPLCODE 	= $rowsqlq1->SPLCODE;
	$DP_AMOUNT	= $rowsqlq1->DP_AMOUNT;
	$DP_NOTES 	= $rowsqlq1->DP_NOTES;
	$SPLDESC 	= $rowsqlq1->SPLDESC;
	$SPLNPWP 	= $rowsqlq1->SPLNPWP;
	$SPLNOREK 	= $rowsqlq1->SPLNOREK;
	$SPLNMREK 	= $rowsqlq1->SPLNMREK;
	$SPLBANK 	= $rowsqlq1->SPLBANK;
endforeach;
$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
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
            padding-left: 0.5cm;
            padding-right: 0.5cm;
            padding-top: 1cm;
            padding-bottom: 1cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: <?php echo $DrafTTD1;?>;
            background-size: 400px 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        @page {
           /* size: A4;*/
            margin: 0;
        }
        @media print {
            /*@page{size: portrait;}*/
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
            .hcol1{
                background-color: #F7DC6F !important;
            }
        }
    </style>
    <body style="overflow:auto">
        <div class="page">
            <section class="content">
                <table width="100%" border="0" style="size:auto">
                    <tr>
                        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
                        <td width="83%" class="style2">&nbsp;</td>
                        <td colspan="3" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
                        <td class="style2" style="text-align:center; font-weight:bold; font-size:18px"><u><?php echo $h1_title; ?></u><br>
                          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px">( DP )</span>
                        </td>
                        <td width="3%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
                        <td width="1%" valign="top" class="style2" style="text-align:left; font-size:12px">&nbsp;</td>
                        <td width="10%" class="style2" style="text-align:left; font-size:12px" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="style2" style="text-align:left">
                          	<table width="100%" border="0" style="size:auto">
                            	<tr>
                                	<td width="10%"><input type="checkbox">&nbsp;Bahan</td>
                                    <td width="10%" nowrap><input type="checkbox">&nbsp;Biaya Umum</td>
                                    <td width="10%"><input type="checkbox">&nbsp;Alat</td>
                                    <td width="10%" nowrap><input type="checkbox">&nbsp;Rupa-rupa</td>
                                    <td width="10%">Kode Dok.</td>
                                    <td width="10%">: <?php echo $DP_CODE; ?></td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox">&nbsp;Upah</td>
                                    <td><input type="checkbox">&nbsp;Persiapan</td>
                                    <td><input type="checkbox">&nbsp;Sub Kontraktor</td>
                                    <td>&nbsp;</td>
                                    <td>Tanggal Dok.</td>
                                    <td>: <?php echo $DP_DATE; ?> </td>
                                </tr>
                                <tr>
                                    <td height="55" colspan="6" valign="bottom">
                                      	<table width="100%" border="0">
                                            <tr>
                                                <td width="7%">Proyek</td>
                                                <td width="1%">:</td>
                                                <td width="92%"><?php echo "$PRJCODE - $PRJNAME"; ?></td>
                                            </tr>
                                            <tr>
                                                <td nowrap>Tanggal Cetak</td>
                                                <td>:</td>
                                                <td><?php echo date('d/m/Y h:i:s'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="style2">
                            <table width="100%" border="1" rules="all">
                                <tr style="background:#CCCCCC">
                                    <td width="64%" height="37" nowrap style="text-align:center; font-weight:bold">Keterangan</td>
                                    <td width="19%" nowrap style="text-align:center; font-weight:bold">No. Rekening</td>
                                    <td width="17%" nowrap style="text-align:center; font-weight:bold">J u m l a h</td>
                                </tr>
                                <tr height="300px">
                                    <td nowrap style="text-align:left; vertical-align:top">
                                        <?php
                                        	echo "$SPLCODE : $SPLDESC<br>$DP_NOTES";
                                        ?>
                                    </td>
                                    <td style="text-align:left; vertical-align:top" nowrap>
                                        <?php
                                        	echo "Acc. No. :$SPLNOREK<br>
                								  an. : $SPLNMREK<br>
                								  Nama Bank : $SPLBANK";
                                        ?>
                                    </td>
                                    <td style="text-align:right; vertical-align:top" nowrap>&nbsp;
                                        <?php
                                        	echo number_format($DP_AMOUNT, 2);
                                    	?>
                                    </td>
                                </tr>
                			</table>
                          	<table width="100%" border="1" style="size:auto; border-top:hidden" rules="all">
                                <tr>
                                    <td width="69%">
                                        <table width="100%" border="0">
                                            <tr height="30px" valign="middle">
                                                <td width="32%" height="129" style="text-align:center"> Dibuat oleh : <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u>
                                                </td>
                                                <td width="36%" style="text-align:center"> Diperiksa : <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u>
                                                </td>
                                                <td width="32%" style="text-align:center"> Disetujui : <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="31%">
                                    	<table width="100%" border="0">
                                            <tr height="30px" valign="middle">
                                                <td width="55%" height="129" nowrap>
                                                	Telah diterima sejumlah
                                                    :<br><br><br><br><br>
                                                    <div style="text-align:center">__________________<br>
                                                    Penerima</div>
                                                </td>
                                              <td width="45%">
                                                	Rp <?php echo number_format($DP_AMOUNT, 2); ?>
                                                    <br><br><br><br><br>
                                                    <div style="text-align:center">_____________<br>
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
        </div>
    </body>
</html>