<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Januari 2018
 * File Name	= print_ir.php
 * Location		= -
*/

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
if($LangID == 'IND')
{
	$Transl_01	= "Halaman ini merupakan contoh untuk mencetak dokumen permintaan pembelian. Silahkan ajukan kepada kami untuk membuat halaman yang sebenarnya.";
}
else
{
	$Transl_01	= "This page is an example to print a purchase request document. Please feel free to ask us to create an actual page.";
}

$sql_01	= "SELECT * FROM tappname";
$res_01	= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;


/*
$sqlPRJ		= "SELECT PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME	= $rowPRJ->PRJNAME;
	$PRJLOCT	= $rowPRJ->PRJLOCT;
endforeach;*/

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
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

		if($TranslCode == 'From')$From = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
		if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
		if($TranslCode == 'OrderID')$OrderID = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
	endforeach
?>

<body class="hold-transition skin-blue sidebar-mini">

<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-y: hidden; }

    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

    <?php /*?><div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div><?php */?>
    <!-- Main content -->
<div class="col-xs-12">
<table width="100%" border="0" style="size:auto">
    <tr>
        <td width="16%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">&nbsp;Print</a>&nbsp;&nbsp;
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="45%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="1" style="size:auto" rules="all">
    	<tr>
        	<td style="border-bottom:solid; border-top:solid; border-left:solid;" width="50px" rowspan="2"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:100px; max-height:120px" ></td>
        	<td style="border-bottom:solid; border-top:solid;text-align:left; font-weight:bold; font-size:24px" width="300px" rowspan="2">
            BON PERMINTAAN DAN PENGELUARAN GUDANG
          </td>
        	<td style="border-bottom:solid; border-top:solid;vertical-align:bottom;font-weight:bold;font-size:14px" width="100px" rowspan="2">No.: <?=$UM_CODE?></td>
					<td width="150px" style="border-top:solid;">Lbr Putih : Gudang,  Lbr Merah : Pelaksana/Mek/yang meminta</td>
					<td style="border-bottom:solid; border-top:solid; border-right:solid" width="100px" rowspan="2">
						No. Dok. : 340<br>
						Amd. : 19-01-2018<br>
						Revisi : -
					</td>
        </tr>
				<tr style="border-bottom:solid;">
					<td width="150px" style="font-weight:bold;font-size:12px">KODE PROYEK : <?=$PRJCODE?></td>
				</tr>
				<tr style="border-bottom:solid;border-right:solid;border-left:solid; height:50px;">
					<td colspan="5" style="vertical-align:top; padding-left:30px;">
						Catatan : &nbsp;
						1. &nbsp;Sebelum mengajukan, pastikan barang yang akan diminta telah tersedia (di Gudang).<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						2. &nbsp;Barang dinyatakan telah disampaikan dan diterima oleh yang meminta, jika Bon ini telah ditanda-tangani oleh Kepala Gudang. !
					</td>
				</tr>
    </table>
	  <table width="100%" border="1" rules="all">
	  	<tr>
	    	<td rowspan="2" style="border-bottom:solid;border-left:solid; text-align:center;font-weight:bold;">NO</td>
				<td rowspan="2" style="border-bottom:solid; text-align:center;font-weight:bold;">BAHAN / SPARE  PART / ALAT</td>
				<td rowspan="2" style="border-bottom:solid; text-align:center;font-weight:bold;">T Y P E</td>
				<td colspan="2" style="text-align:center;font-weight:bold;">K  O  D  E</td>
				<td rowspan="2" style="border-bottom:solid; text-align:center;font-weight:bold;">SAT</td>
				<td rowspan="2" style="border-bottom:solid; text-align:center;font-weight:bold;">JUMLAH</td>
				<td rowspan="2" style="border-bottom:solid;border-right:solid; text-align:center;font-weight:bold;">UNTUK  PEKERJAAN</td>
	    </tr>
			<tr>
				<td style="border-bottom:solid; text-align:center;font-weight:bold;">ITEM</td>
				<td style="border-bottom:solid; text-align:center;font-weight:bold;">POS</td>
			</tr>
            <?php
				$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

				$nU		= 0;
				$sqlUMD	= "SELECT A.ITM_CODE, B.ITM_NAME, A.ITM_UNIT, A.ITM_QTY, A.UM_DESC, A.JOBCODEID, A.JOBPARENT
							FROM tbl_um_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.UM_NUM = '$UM_NUM'";
				$resUMD	= $this->db->query($sqlUMD)->result();
				foreach($resUMD as $rowUMD) :
					$nU			= $nU + 1;
					$ITM_CODE	= $rowUMD->ITM_CODE;
					$ITM_NAME	= $rowUMD->ITM_NAME;
					$ITM_UNIT	= $rowUMD->ITM_UNIT;
					$ITM_QTY	= $rowUMD->ITM_QTY;
					$UM_DESC	= $rowUMD->UM_DESC;
					$JOBCODEID 	= $rowUMD->JOBCODEID;
					$JOBPARENT 	= $rowUMD->JOBPARENT;

					// GET JOBDESC
						$JOBD 		= "";
						$sqlJobD	= "SELECT JOBDESC FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
						$resJobD	= $this->db->query($sqlJobD)->result();
						foreach($resJobD as $rowJD) :
							$JOBD 	= $rowJD->JOBDESC;
						endforeach;
						$JOBCODEIDD	= "$JOBCODEID : $JOBD";
				?>
                    <tr height="40px">
                        <td style="border-left:solid; text-align:center"><?php echo $nU; ?>.</td>
                        <td><?php echo $ITM_NAME; ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $ITM_CODE; ?></td>
                        <td><?=$JOBCODEIDD?></td>
                        <td><?php echo $ITM_UNIT; ?></td>
                        <td style="text-align:right"><?php echo number_format($ITM_QTY, 2); ?></td>
                        <td style="border-right:solid;"><?php echo $UM_DESC; ?></td>
                    </tr>
            	<?php
				endforeach;
			?>
			<tr height="50px">
				<td style="border-left:solid;">&nbsp;</td>
				<td style="vertical-align:bottom;font-weight:bold;">*) : Coret tyang tidak perlu !</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border-right:solid;">&nbsp;</td>
			</tr>
	  </table>
	  <table width="100%" border="1" rules="all">
	      <tr>
	          <td colspan="2" style="border-left:solid;border-top:solid; border-bottom:hidden;font-weight:bold;">DIMINTA/ DIAJUKAN OLEH :</td>
	          <td width="28%" style="border-top:solid;border-bottom:hidden;font-weight:bold;">D I S E T U J U I  :</td>
	          <td width="22%" style="border-top:solid;border-bottom:hidden;font-weight:bold;">D I K E L U A R K A N :</td>
	          <td width="23%" style="border-right:solid;border-top:solid;border-bottom:hidden;font-weight:bold;	">D  I  T  E  R  I  M  A   :  !</td>
	      </tr>
	        <tr>
	          <td colspan="2" style="border-left:solid">PELAKSANA/ MEKANIK *) !</td>
	          <td> SITE MANAGER (SM)/ PROJ. MGR. (PM) *)!</td>
	          <td>KEPALA GUDANG</td>
	          <td style="border-right:solid">&nbsp;</td>
	        </tr>
	        <tr height="50px">
	          <td colspan="2" style="border-left:solid">&nbsp;</td>
	          <td>&nbsp;</td>
	          <td>&nbsp;</td>
	          <td style="border-right:solid">&nbsp;</td>
	        </tr>
	        <tr>
	          <td width="7%" style="border-left:solid">Nama :</td>
	          <td width="20%" style="border-left:hidden">&nbsp;</td>
	          <td>Nama :</td>
	          <td>Nama :</td>
	          <td style="border-right:solid">Nama :</td>
	        </tr>
	        <tr style="border-bottom:solid">
	          <td colspan="2" style="border-left:solid;">Tanggal :</td>
	          <td>Tanggal :</td>
	          <td>Tanggal :</td>
	          <td style="border-right:solid">Tanggal :</td>
	        </tr>
	        <tr>
	          <td colspan="2" style="border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
	          <td style="border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
	          <td style="border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
	          <td style="border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
	        </tr>
	  </table>
		<table width="100%" border="0" style="line-height:3px;font-size:10px;">
			<tr>
				<td align="left">©Hakcipta, PT. SASMITO</td>
				<td style="text-align:right">Doc.File:IQ340.03 Spl.Doc., Auth : BY</td>
			</tr>
		</table>
</div>
    <!-- /.content -->
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
