<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 08 Maret 2017
 * File Name	= v_cashflow.php
 * Location		= -
*/
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$dateNow = date('Y-m-d');
$dateReport = date('Ymd');

if($isExcel == 2)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=TTK_$dateReport.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $appName; ?> | Data Tables</title>
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

<?php
	if($isExcel == 2) // is Excel
	{
	?>
        <table width="100%" border="0" style="size:auto">
            <tr>
                <td width="19%">
                <div id="Layer1">
                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                    <img src="<?php echo base_url().'images/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                    <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
                <td colspan="2" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
                <td width="16%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left; font-weight:bold;">PT NUSA KONSTRUKSI ENJINIRING</td>
                <td width="53%" style="text-align:right" class="style2">Tanggal :&nbsp;</td>
                <td class="style2" style="text-align:left;">&nbsp;<?php echo $dateNow; ?></td>
            </tr>    
            <tr>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:right;">Halaman :&nbsp;</td>
                <td class="style2" style="text-align:left;">&nbsp;1</td>
            </tr>
            <tr>
                <td colspan="4" class="style2" style="text-align:center; font-size:18px">Bukti Penurunan Kwitansi</td>
            </tr>
            <tr>
                <td class="style2" style="text-align:left;">Tanggal : <?php echo $dateNow; ?></td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" class="style2" style="text-align:center"><hr /></td>
            </tr>
            <tr>
                <td colspan="4" class="style2">
                    <table width="100%" border="1" rules="all">
                        <tr style="background:#CCCCCC">
                            <td width="3%" style="text-align:center; font-weight:bold">No.</td>
                            <td width="9%" style="text-align:center; font-weight:bold">No. TTK</td>
                          <td width="11%" style="text-align:center; font-weight:bold">Tanggal</td>
                          <td width="11%" style="text-align:center; font-weight:bold">Jatuh Tempo</td>

                          <td width="40%" style="text-align:center; font-weight:bold">Supplier</td>
                          <td width="6%" style="text-align:center; font-weight:bold">Proyek</td>
                          <td width="10%" style="text-align:center; font-weight:bold">Nominal TTK</td>
                          <td width="5%" style="text-align:center; font-weight:bold">Voucher</td>
                          <td width="2%" style="text-align:center; font-weight:bold">Nominal Voucher</td>
                          <td width="3%" style="text-align:center; font-weight:bold">User Voucher</td>
                        </tr>
                        <?php
                            $empID	= $this->session->userdata('Emp_ID');
                            $sqlGetData		= "SELECT index_no, ttk_no, ttk_date, ttk_duedate, ttk_supplier, ttk_projcode, ttk_nominal, ttk_voucherno, ttk_nominalVouch, ttk_vocuser
                                                FROM tbl_decreaseinvoice
                                                WHERE empID = '$empID'
                                                ORDER BY index_no";
                                                
                            $sqlGetDataC	= "tbl_decreaseinvoice WHERE empID = '$empID'";
                            // count data
                                $resultCountH	= $this->db->count_all($sqlGetDataC);
                                $CountRs 		= $this->db->count_all($sqlGetDataC);
                            // End count data
                            $resultH = $this->db->query($sqlGetData)->result();
                            $totalNominal = 0;
                            $totalNominalV= 0;
                            $myIndex = 0;
                            if($resultCountH > 0)
                            {
                                foreach($resultH as $rowH) :
                                    $myIndex		= $myIndex + 1;
                                    $index_no 		= $rowH->index_no;
                                    $ttk_no 		= $rowH->ttk_no;
                                    $ttk_date1 		= date('m-d-Y',strtotime($rowH->ttk_date));
                                    $ttk_duedate1	= date('m-d-Y',strtotime($rowH->ttk_duedate));
                                    $ttk_supplier 	= $rowH->ttk_supplier;
                                    $ttk_projcode 	= $rowH->ttk_projcode;
                                    $ttk_nominal	= $rowH->ttk_nominal;
                                    $ttk_voucherno	= $rowH->ttk_voucherno;
                                    $ttk_nominalV	= $rowH->ttk_nominalVouch;
                                    $ttk_vocuser	= $rowH->ttk_vocuser;
                                    $totalNominal	= $totalNominal + $ttk_nominal;
                                    $totalNominalV	= $totalNominalV + $ttk_nominalV;
									$angka1	= substr($ttk_no, 0,1);
									if($angka1 == 1)
									{
										$ttk_no1 = $ttk_no;
									}
									else
									{
										$ttk_no1 = "'$ttk_no";
									}
                                    ?>
                                    <tr>
                                      <td style="text-align:center;"><?php echo $myIndex; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_no1; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_date1; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_duedate1; ?></td>
                                      <td style="text-align:left;">&nbsp;<?php echo $ttk_supplier; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_projcode; ?></td>
                                      <td style="text-align:right;"><?php print number_format($ttk_nominal, $decFormat); ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_voucherno; ?></td>
                                      <td style="text-align:right;"><?php print number_format($ttk_nominalV, $decFormat); ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_vocuser; ?></td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                                    <tr>
                                      <td colspan="6" style="text-align:right;">T o t a l :&nbsp;</td>
                                      <td style="text-align:right; font-weight:bold"><?php print number_format($totalNominal, $decFormat); ?></td>
                                      <td style="text-align:center;">&nbsp;</td>
                                      <td style="text-align:right; font-weight:bold"><?php print number_format($totalNominalV, $decFormat); ?></td>
                                      <td style="text-align:right; font-weight:bold">&nbsp;</td>
                                    </tr>
                                <?php
                            }
                            else
                            {
                        ?>
                            <tr>
                              <td colspan="10" style="text-align:center;"> ---  NO DATA ---</td>
                            </tr>
                        <?php
                            }
                        ?>
                    </table>
              </td>
            </tr>
        </table>    
    <?php
	}
	else
	{
	?>
        <table width="100%" border="0" style="size:auto">
            <tr>
                <td width="19%">
                <div id="Layer1">
                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                    <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                    <a href="#" onClick="window.close();" class="button"> close </a>    	</div>        </td>
                <td colspan="2" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
                <td width="16%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="style2" style="text-align:left; font-weight:bold;">PT NUSA KONSTRUKSI ENJINIRING</td>
                <td width="53%" style="text-align:right" class="style2">Tanggal :&nbsp;</td>
                <td class="style2" style="text-align:left;">&nbsp;<?php echo $dateNow; ?></td>
            </tr>    
            <tr>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:right;">Halaman :&nbsp;</td>
                <td class="style2" style="text-align:left;">&nbsp;1</td>
            </tr>
            <tr>
                <td colspan="4" class="style2" style="text-align:center; font-size:18px">Bukti Penurunan Kwitansi</td>
            </tr>
            <tr>
                <td class="style2" style="text-align:left;">Tanggal : <?php echo $dateNow; ?></td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" class="style2" style="text-align:center"><hr /></td>
            </tr>
            <tr>
                <td colspan="4" class="style2">
                    <table width="100%" border="1" rules="all">
                        <tr style="background:#CCCCCC">
                            <td width="3%" style="text-align:center; font-weight:bold">No.</td>
                            <td width="9%" style="text-align:center; font-weight:bold">No. TTK</td>
                          <td width="11%" style="text-align:center; font-weight:bold">Tanggal</td>
                          <td width="11%" style="text-align:center; font-weight:bold">Jatuh Tempo</td>
                          <td width="40%" style="text-align:center; font-weight:bold">Supplier</td>
                          <td width="6%" style="text-align:center; font-weight:bold">Proyek</td>
                          <td width="10%" style="text-align:center; font-weight:bold">Nominal TTK</td>
                          <td width="5%" style="text-align:center; font-weight:bold">Voucher</td>
                          <td width="2%" style="text-align:center; font-weight:bold">Nominal Voucher</td>
                          <td width="3%" style="text-align:center; font-weight:bold">User Voucher</td>
                        </tr>
                        <?php
                            $empID	= $this->session->userdata('Emp_ID');
                            $sqlGetData		= "SELECT index_no, ttk_no, ttk_date, ttk_duedate, ttk_supplier, ttk_projcode, ttk_nominal, ttk_voucherno, ttk_nominalVouch, ttk_vocuser
                                                FROM tbl_decreaseinvoice
                                                WHERE empID = '$empID'
                                                ORDER BY index_no";
                                                
                            $sqlGetDataC	= "tbl_decreaseinvoice WHERE empID = '$empID'";
                            // count data
                                $resultCountH	= $this->db->count_all($sqlGetDataC);
                                $CountRs 		= $this->db->count_all($sqlGetDataC);
                            // End count data
                            $resultH = $this->db->query($sqlGetData)->result();
                            $totalNominal = 0;
                            $totalNominalV= 0;
                            $myIndex = 0;
                            if($resultCountH > 0)
                            {
                                foreach($resultH as $rowH) :
                                    $myIndex		= $myIndex + 1;
                                    $index_no 		= $rowH->index_no;
                                    $ttk_no 		= $rowH->ttk_no;
                                    $ttk_date1 		= date('m-d-Y',strtotime($rowH->ttk_date));
                                    $ttk_duedate1	= date('m-d-Y',strtotime($rowH->ttk_duedate));
                                    $ttk_supplier 	= $rowH->ttk_supplier;
                                    $ttk_projcode 	= $rowH->ttk_projcode;
                                    $ttk_nominal	= $rowH->ttk_nominal;
                                    $ttk_voucherno	= $rowH->ttk_voucherno;
                                    $ttk_nominalV	= $rowH->ttk_nominalVouch;
                                    $ttk_vocuser	= $rowH->ttk_vocuser;
                                    $totalNominal	= $totalNominal + $ttk_nominal;
                                    $totalNominalV	= $totalNominalV + $ttk_nominalV;
									$angka1	= substr($ttk_no, 0,1);
									if($angka1 == 1)
									{
										$ttk_no1 = $ttk_no;
									}
									else
									{
										$ttk_no1 = "$ttk_no";
									}
                                    ?>
                                    <tr>
                                      <td style="text-align:center;"><?php echo $myIndex; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_no1; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_date1; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_duedate1; ?></td>
                                      <td style="text-align:left;">&nbsp;<?php echo $ttk_supplier; ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_projcode; ?></td>
                                      <td style="text-align:right;"><?php print number_format($ttk_nominal, $decFormat); ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_voucherno; ?></td>
                                      <td style="text-align:right;"><?php print number_format($ttk_nominalV, $decFormat); ?></td>
                                      <td style="text-align:center;"><?php echo $ttk_vocuser; ?></td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                                    <tr>
                                      <td colspan="6" style="text-align:right;">T o t a l :&nbsp;</td>
                                      <td style="text-align:right; font-weight:bold"><?php print number_format($totalNominal, $decFormat); ?></td>
                                      <td style="text-align:center;">&nbsp;</td>
                                      <td style="text-align:right; font-weight:bold"><?php print number_format($totalNominalV, $decFormat); ?></td>
                                      <td style="text-align:right; font-weight:bold">&nbsp;</td>
                                    </tr>
                                <?php
                            }
                            else
                            {
                        ?>
                            <tr>
                              <td colspan="10" style="text-align:center;"> ---  NO DATA ---</td>
                            </tr>
                        <?php
                            }
                        ?>
                    </table>
              </td>
            </tr>
        </table>
    <?php
	}
?>
</section>
</body>
</html>