<?php
/* 
 	* Author   		= Dian Hermanto
 	* Create Date  	= 08 November 2020
 	* File Name  	= v_shipment_print.php
 	* Location  	= -
*/

setlocale(LC_ALL, 'id-ID', 'id_ID');

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;
$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";
$LangID 		= $this->session->userdata['LangID'];

$sql_01	= "SELECT * FROM tappname";
$res_01	= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;

$SN_DATEV	= strftime('%d %B %Y', strtotime($SN_DATE));
$sqlPRJ     = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ     = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
    $PRJNAME= $rowPRJ->PRJNAME;
endforeach;

$sqlSN          = "SELECT SN_DATE, SN_CREATED, CUST_DESC FROM tbl_sn_header WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PRJCODE'";
$resSN          = $this->db->query($sqlSN)->result();
foreach($resSN as $rowSN) :
    $SN_DATE    = $rowSN->SN_DATE;
    $SN_CREATED = $rowSN->SN_CREATED;
    $CUST_DESC  = $rowSN->CUST_DESC;
endforeach;

$DrafTTD1   = "white";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
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
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<?php
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Color')$Color = $LangTransl;
        if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        if($TranslCode == 'Nominal')$Nominal = $LangTransl;
        if($TranslCode == 'salesPrcCust')$salesPrcCust = $LangTransl;
        if($TranslCode == 'Created')$Created = $LangTransl;
        if($TranslCode == 'Approved')$Approved = $LangTransl;
        if($TranslCode == 'Approved')$Approved = $LangTransl;
	endforeach;

    if($LangID == 'IND')
    {
        $header     = "PURCHASE ORDER";
        $alert1     = "Pengaturan Departemen Pengguna";
        $alert2     = "Status pengguna belum ditentukan pada departemen manapun, sehingga tidak dapat membuat dokumen ini. Silahkan hubungi admin untuk meminta bantuan.";
    }
    else
    {
        $header     = "PURCHASE ORDER";
        $alert1     = "User department setting";
        $alert2     = "User not yet set department, so can not create this document. Please call administrator to get help.";
    }
?>

<body class="hold-transition skin-blue sidebar-mini">

<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

    <!-- <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php //echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php //echo $Transl_01; ?>
        </div>
    </div> -->
    <!-- Main content -->
    <div class="page">
        <table border="0" width="100%">
            <tr>
            	<td>
            		<table border="0" width="100%">
                        <tr>
                            <td colspan="5" style="vertical-align: top; text-align: center; font-weight: bold; font-size: 14px;">
                                FORM PENGELUARAN BARANG JADI
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="vertical-align: top; text-align: center; font-weight: bold; line-height: 4px">&nbsp;</td>
                        </tr>
                        <tr style="border-top: hidden;">
                            <td width="10%" style="border-right: hidden;">TANGGAL</td>
                            <td width="20%" style="border-right: hidden;">: <?php echo date('d-m-Y', strtotime($SN_DATE)); ?></td>
                            <td width="5%" style="border-right: hidden;">JAM</td>
                            <td width="25%" style="border-right: hidden;">:</td>
                            <td width="40%">NO. FORM:</td>
                        </tr>
                        <tr style="border-top: hidden;">
                            <td style="border-right: hidden;">BAGIAN</td>
                            <td style="border-right: hidden;" colspan="4">:</td>
                        </tr>
				    </table>
            	</td>
            </tr>
            <tr>
            	<td>
            		<table border="1" width="100%">
                        <tr style="font-weight: bold; text-align: center;">
                            <td style="text-align-last: center;" rowspan="2" width="2%">NO</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">CUSTOMER</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">PO</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">WARNA</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">KODE</td>
                            <td style="text-align-last: center;" colspan="2">MT</td>
                            <td style="text-align-last: center;" colspan="2">BODY</td>
                            <td style="text-align-last: center;" colspan="2">RIB</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">NO.<br>MOBIL</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">OP GD<br>JADI</td>
                            <td style="text-align-last: center;" rowspan="2" width="10%">KETERANGAN</td>
                        </tr>
                        <tr style="font-weight: bold; text-align: center;">
                            <td style="text-align-last: center;" width="10%">BODY</td>
                            <td style="text-align-last: center;" width="10%">RIB</td>
                            <td style="text-align-last: center;" width="10%">ROLL</td>
                            <td style="text-align-last: center;" width="10%">KG</td>
                            <td style="text-align-last: center;" width="10%">ROLL</td>
                            <td style="text-align-last: center;" width="10%">KG</td>
                        </tr>
                            <tr style="text-align: center;">
                                <td style="text-align-last: center;">1. </td>
                                <td style="text-align-last: center;"><?=$CUST_DESC?></td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                                <td style="text-align-last: center;">&nbsp;</td>
                            </tr>
                        <?php
                            for($i=0; $i<10; $i++)
                            {
                                ?>
                                    <tr style="text-align: center;">
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                        <td style="text-align-last: center;">&nbsp;</td>
                                    </tr>
                                <?php
                            }
                        ?>
            		</table>
            	</td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="1">
                        <tr>
                            <td width="20%" style="text-align: center;">PEMOHON</td>
                            <td width="20%" style="text-align: center;">CHECKED</td>
                            <td width="20%" style="text-align: center;">DISETUJUI</td>
                            <td width="20%" style="text-align: center;">YANG MENGELUARKAN</td>
                            <td width="20%" style="text-align: center;">PENERIMA</td>
                        </tr>
                        <tr>
                            <td width="20%" style="text-align: center;"><br><br><br><br><br></td></td>
                            <td width="20%" style="text-align: center;">&nbsp;</td>
                            <td width="20%" style="text-align: center;">&nbsp;</td>
                            <td width="20%" style="text-align: center;">&nbsp;</td>
                            <td width="20%" style="text-align: center;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="20%" style="text-align: center;">PPIC</td>
                            <td width="20%" style="text-align: center;">QA</td>
                            <td width="20%" style="text-align: center;">KEPALA PABRIK</td>
                            <td width="20%" style="text-align: center;">GUDANG BARANG JADI</td>
                            <td width="20%" style="text-align: center;">EXPEDISI</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-style: italic; font-size: 10px; border-left: hidden; border-right: hidden; border-bottom: hidden;">
                                * Apabila RIB memiliki No. MT sendiri mohon dicatat terpisah
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <div class="row no-print">
            <div class="col-xs-12">
                <div id="Layer1">
                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
                    <i class="fa fa-download"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>