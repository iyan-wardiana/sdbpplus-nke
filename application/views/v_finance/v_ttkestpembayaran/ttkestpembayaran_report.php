<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 4 April 2017
 * File Name	= r_paymentplan_report_adm.php
 * Location		= -
*/
/*if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}*/
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
if($isExcel == 2)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=TTKEST_$dateReport.xls");
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
  <title>Est. Pembayaran</title>
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
            <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/Logo1.jpg') ?>" width="181" height="44"></td>
          	<td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">ESTIMASI PEMBAYARAN</td>
      </tr>
        <tr>
			<td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">PT NUSA KONSTRUKSI ENJINIRING, Tbk,</span></td>
		</tr>
            <?php
                //$StartDate1 = date('Y/m/d',strtotime($Start_Date));
                //$EndDate1 = date('Y/m/d',strtotime($End_Date));
            ?>
        <tr>
            <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        </tr>
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
                        <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NO.</td>
                        <td colspan="2" nowrap style="text-align:center; font-weight:bold">VOUCHER<br></td>
                        <td width="7%" rowspan="2" style="text-align:center; font-weight:bold">RECEIPT<br>DATE</td>
                        <td colspan="3" nowrap style="text-align:center; font-weight:bold">TTK</td>
                        <td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">SUPPLIER</td>
                        <td width="20%" rowspan="2" nowrap style="text-align:center; font-weight:bold">DESCRIPTION</td>
                        <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">PROJECT</td>
                        <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NOMINAL</td>
                        <td width="9%" rowspan="2" nowrap style="text-align:center; font-weight:bold">BANK NAME</td>
                    </tr>
                    <tr style="background:#CCCCCC">
                      <td width="4%" nowrap style="text-align:center; font-weight:bold">CODE</td>
                      <td width="5%" nowrap style="text-align:center; font-weight:bold">DATE</td>
                      <td width="8%" nowrap style="text-align:center; font-weight:bold">TTK CODE</td>
                      <td width="7%" nowrap style="text-align:center; font-weight:bold">TTK DATE</td>
                      <td width="8%" nowrap style="text-align:center; font-weight:bold">DUE DATE</td>
                    </tr>
                    <?php
						
					// Mengumpulkan semua VOCCODE yang TDPCODE != NULL, digunakan untuk pengecualian
					
					$noUx			= 0;
					$NVOCCODEX		= '';
					
					$therow			= 0;
					$GTOTAL			= 0;
					$noU			= 0;
					$noUa			= 0;
					$noUb			= 0;
					
					$sqlq3 			= "SELECT * FROM tbl_ttkestinvoice WHERE empID = '$DefEmp_ID'";
					$resq3 			= $this->db->query($sqlq3)->result();							
					foreach($resq3 as $rowsqlq3) :
						$therow		= $therow + 1;
						$VOCCODE 	= $rowsqlq3->VOCCODE;
						$VOC_DATE 	= $rowsqlq3->VOC_DATE;
						$VOC_NOM 	= $rowsqlq3->VOC_NOM;
						$KWIT_DATE 	= $rowsqlq3->KWIT_DATE;
						$TTK_CODE 	= $rowsqlq3->TTK_CODE;
						$TTK_DATE 	= $rowsqlq3->TTK_DATE;
						$TTK_DUED 	= $rowsqlq3->TTK_DUED;
						$TTK_SUPL 	= $rowsqlq3->TTK_SUPL;
						$TTK_DESC 	= $rowsqlq3->TTK_DESC;
						$PRJCODE 	= $rowsqlq3->PRJCODE;
						$BANK_NAME 	= $rowsqlq3->BANK_NAME;
						$GTOTAL		= $GTOTAL + $VOC_NOM;
						?>
							<tr>
								<td nowrap style="text-align:left;"><?php echo $therow; ?>.</td>
								<td nowrap style="text-align:left;"><?php echo $VOCCODE; ?></td>
								<td nowrap style="text-align:left;"><?php echo date('d/m/Y',strtotime($VOC_DATE)); ?></td>
								<td nowrap style="text-align:left;"><?php echo date('d/m/Y',strtotime($KWIT_DATE)); ?></td>
								<td nowrap style="text-align:left;"><?php echo $TTK_CODE; ?></td>
								<td nowrap style="text-align:left;"><?php echo date('d/m/Y',strtotime($TTK_DATE)); ?></td>
							  	<td nowrap style="text-align:left;"><?php echo date('d/m/Y',strtotime($TTK_DUED)); ?></td>
							  	<td nowrap style="text-align:left;"><?php echo $TTK_SUPL; ?></td>
								<td nowrap style="text-align:left;"><?php echo $TTK_DESC; ?></td>
								<td nowrap style="text-align:left;"><?php echo $PRJCODE; ?></td>
								<td nowrap style="text-align:right;"><?php echo round($VOC_NOM); ?></td>
								<td nowrap style="text-align:right;"><?php echo $BANK_NAME; ?></td>
							</tr>
						<?php
					endforeach;
					?>
                    <tr>
                        <td colspan="3" nowrap style="text-align:left; font-weight:bold">T O T A L</td>
                        <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:left;">&nbsp;</td>
                      <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTAL, $decFormat); ?>&nbsp;</td>
                        <td nowrap style="text-align:left;">&nbsp;</td>
                    </tr>
          </table>
          </td>
        </tr>
    </table>
</section>
</body>
</html>