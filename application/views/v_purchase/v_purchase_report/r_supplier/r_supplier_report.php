<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2018
 * File Name	= r_supplier_report.php
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
$comp_name 	= $this->session->userdata['comp_name'];
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
<?php
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'All')$All = $LangTransl;
		if($TranslCode == 'Active')$Active = $LangTransl;
		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
	endforeach;
?>
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-weight:bold;">
        	<div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>
        </td>
      </tr>
    <tr>
        <td width="3%" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td width="97%" colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?><br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px; display:none"><?php echo $comp_name; ?></span></td>
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
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">NO.</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold; display:none">MAIN <br>SERVICE</td>
                    <td width="20%" nowrap style="text-align:center; font-weight:bold; display:none">OTHERS SERVICE</td>
                    <td width="28%" style="text-align:center; font-weight:bold">KODE</td>
                    <td width="17%" style="text-align:center; font-weight:bold">DESKRIPSI NAMA PERUSAHAAN</td>
                    <td width="6%" style="text-align:center; font-weight:bold">ALAMAT</td>
                    <td width="6%" style="text-align:center; font-weight:bold">ALAMAT 2</td>
                    <td width="9%" style="text-align:center; font-weight:bold">TELEPON</td>
                    <td width="9%" style="text-align:center; font-weight:bold; display:none">FAX</td>
                    <td width="9%" style="text-align:center; font-weight:bold">CONTACT PERSON</td>
                    <td width="9%" style="text-align:center; font-weight:bold">E-MAIL</td>
                    <td width="9%" style="text-align:center; font-weight:bold">LOKASI</td>
                    <td width="9%" style="text-align:center; font-weight:bold; display:none">COMPANY PROFILE /BROSUR</td>
                    <td width="9%" style="text-align:center; font-weight:bold">NO REKENING</td>
                    <td width="9%" style="text-align:center; font-weight:bold">NAMA BANK</td>
                    <td width="9%" style="text-align:center; font-weight:bold">NAMA DI BANK</td>
                    <td width="9%" style="text-align:center; font-weight:bold">KODE BI</td>
                    <td width="3%" style="text-align:center; font-weight:bold">KETERANGAN</td>
                </tr>
                <?php						
					if($SuppStat != 2)
					{
						$FILTER_1	= "AND SPLSTAT = '$SuppStat'";
					}
					else
					{
						$FILTER_1	= "";
					}
					
					if($SortBy == 'All')
					{
						if($FILTER_1 == '')
							$sql0 		= "tbl_supplier";
						else
							$sql0 		= "tbl_supplier WHERE SPLSTAT = '$SuppStat'";
					}
					else
					{
						$sql0 		= "tbl_supplier WHERE SPLCAT = '$SortBy' $FILTER_1";
					}
					$res0		= $this->db->count_all($sql0);
					
					if($res0 > 0)
					{
						$SPLCODE	= '';
						$SPLDESC	= '';
						$SPLADD1	= '';
						$SPLKOTA 	= '';
						$SPLTELP 	= '';
						$SPLMAIL 	= '';
						$SPLPERS	= '';
						$SPLSTAT 	= '';
						$therow		= 0;
						
						if($SortBy == 'All')
						{
							if($FILTER_1 == '')
								$sql1 		= "SELECT * FROM tbl_supplier";
							else
								$sql1 		= "SELECT * FROM tbl_supplier WHERE SPLSTAT = '$SuppStat'";
						}
						else
						{
							$sql1 		= "SELECT * FROM tbl_supplier WHERE SPLCAT = '$SortBy' $FILTER_1";
						}
						$res1 		= $this->db->query($sql1)->result();
						
						foreach($res1 as $row1) :
							$therow		= $therow + 1;
							$SPLCODE 	= $row1->SPLCODE;
							$SPLDESC 	= $row1->SPLDESC;
                            $SPLADD1    = $row1->SPLADD1;
                            $SPLADD2    = $row1->SPLADD2;
							$SPLKOTA 	= $row1->SPLKOTA;
							$SPLNPWP 	= $row1->SPLNPWP;
							$SPLTELP 	= $row1->SPLTELP;
							$SPLMAIL 	= $row1->SPLMAIL;
							$SPLSTAT 	= $row1->SPLSTAT;
							$SPLPERS 	= $row1->SPLPERS;
							
							$SPLKOTA 	= $row1->SPLKOTA;
							$SPLBANK 	= $row1->SPLBANK;
							$SPLNOREK 	= $row1->SPLNOREK;
							$SPLNMREK 	= $row1->SPLNMREK;
							$SPLBNKBI 	= $row1->SPLBNKBI;
							
							if($SPLPERS == '' || $SPLPERS == '-')
								$SPLPERSD = "";
							else
								$SPLPERSD = "$SPLPERS";
							
							if($SPLSTAT == 1)
								$SPLSTATD = $Active;
							else
								$SPLSTATD = $Inactive;
							?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$therow."; ?></td>
                                <td nowrap style="text-align:left; display:none"><?php //echo $SPLCODE; ?>&nbsp;</td>
                                <td nowrap style="text-align:left; display:none"><?php //echo $SPLDESC; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $SPLCODE; ?></td>
                                <td nowrap style="text-align:left"><?php //echo "$SPLNPWP"; ?><?php echo $SPLDESC; ?></td>
                                <td style="text-align:left"><?php echo $SPLADD1; ?></td>
                                <td style="text-align:left"><?php echo $SPLADD2; ?></td>
                                <td nowrap style="text-align:left;"><?php echo "$SPLTELP"; ?></td>
                                <td nowrap style="text-align:left; display:none"><?php //echo $SPLMAIL; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $SPLPERSD; ?>&nbsp;</td>
                                <td style="text-align:left;"><?php echo $SPLMAIL; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $SPLKOTA; ?>&nbsp;</td>
                                <td nowrap style="text-align:left; display:none"><?php //echo $SPLMAIL; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $SPLNOREK; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $SPLBANK; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $SPLNMREK; ?>&nbsp;</td>
                                <td nowrap style="text-align:left;"><?php echo $SPLBNKBI; ?>&nbsp;</td>
                                <td colspan="2" nowrap style="text-align:center;"><?php echo $SPLSTATD; ?></td>
                            </tr>
                            <?php
                        endforeach;
					}
					else
					{
					}
                ?>
            </table>
	  </td>
    </tr>
</table>
</section>
</body>
</html>