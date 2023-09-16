<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= r_dpreport_report_adm.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
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
<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="19%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">LAPORAN UANG MUKA PEMASOK (Detil)</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">s.d Periode : 
        <?php
            $StartDate1 = date('d/m/Y',strtotime($Start_Date));
            $EndDate1 = date('d/m/Y',strtotime($End_Date));
			echo $EndDate1;
        ?>
        </span></td>
    </tr>
        &nbsp;
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                  <th width="2%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
                  <th width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL.</th>
                  <th colspan="8" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">UANG MUKA / DOWN PAYMENT (DP)</th>
              </tr>
                <tr style="background:#CCCCCC">
                  <th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Nomor DP/Jurnal</th>
                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Supplier</th>
                  <th width="19%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Deskripsi</th>
                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Kategori Kontrak</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH DP</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">POT.DP</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">SISA DP</th>
                  <th width="13%" nowrap style="text-align:center; font-weight:bold;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">PROYEK</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="3" nowrap style="text-align:center;border:none">&nbsp;</td>
               </tr>
               <tr>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
               </tr>
               <tr>
                 <td colspan="6" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000"><b>TOTAL</b></td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
               </tr>
                <tr style="display:none">
                  <td colspan="10" nowrap style="text-align:center;">--- none ---</td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>