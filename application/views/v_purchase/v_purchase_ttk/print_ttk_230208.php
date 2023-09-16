<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 27 Oktober 2018
 * File Name	= update_ttk.php
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

$TTK_NUM 		= $default['TTK_NUM'];
$TTK_CODE 		= $default['TTK_CODE'];
$TTK_DATE 		= $default['TTK_DATE'];
$TTK_DUEDATE 	= $default['TTK_DUEDATE'];
$TTK_ESTDATE 	= $default['TTK_ESTDATE'];
$TTK_NOTES 		= $default['TTK_NOTES'];
$TTK_NOTES1		= $default['TTK_NOTES1'];
$TTK_AMOUNT 	= $default['TTK_AMOUNT'];
$TTK_AMOUNT_PPN = $default['TTK_AMOUNT_PPN'];
$TTK_AMOUNT_RET = $default['TTK_AMOUNT_RET'];
$TTK_AMOUNT_POT = $default['TTK_AMOUNT_POT'];

$TTK_GTOTAL 	= $default['TTK_GTOTAL'];
$TTK_CATEG 		= $default['TTK_CATEG'];
$PRJCODE 		= $default['PRJCODE'];
$SPLCODE		= $default['SPLCODE'];
$TTK_CHECKER	= $default['TTK_CHECKER'];
$TTK_STAT 		= $default['TTK_STAT'];

$TTKP_RECDATE 	= $default1['TTKP_RECDATE'];
$TTKP_DENIED 	= $default1['TTKP_DENIED'];
$TTKP_DOCTYPE 	= $default1['TTKP_DOCTYPE'];
$TTKP_KWITPEN 	= $default1['TTKP_KWITPEN'];
$TTKP_FAKPAJAK 	= $default1['TTKP_FAKPAJAK'];
$TTKP_COPYPO 	= $default1['TTKP_COPYPO'];
$TTKP_BAKEM		= $default1['TTKP_BAKEM'];
$TTKP_LKEM 		= $default1['TTKP_LKEM'];
$TTKP_BAPRES 	= $default1['TTKP_BAPRES'];
$TTKP_SJ 		= $default1['TTKP_SJ'];
$TTKP_KMA 		= $default1['TTKP_KMA'];
$TTKP_KPPSA 	= $default1['TTKP_KPPSA'];
$TTKP_SI		= $default1['TTKP_SI'];
$TTKP_PKER		= $default1['TTKP_PKER'];
$TTKP_LPK		= $default1['TTKP_LPK'];
$TTKP_KDAB 		= $default1['TTKP_KDAB'];
$TTKP_FPMSM 	= $default1['TTKP_FPMSM'];
$TTKP_BOL 		= $default1['TTKP_BOL'];
$TTKP_LPP 		= $default1['TTKP_LPP'];
$TTKP_LPA 		= $default1['TTKP_LPA'];
$TTKP_KPM 		= $default1['TTKP_KPM'];
$TTKP_JAMSER 	= $default1['TTKP_JAMSER'];
$TTKP_TDPO 		= $default1['TTKP_TDPO'];
$TTKP_JUM 		= $default1['TTKP_JUM'];
$TTKP_JPEL 		= $default1['TTKP_JPEL'];
$TTKP_MPEL 		= $default1['TTKP_MPEL'];
$TTKP_LPD 		= $default1['TTKP_LPD'];
$TTKP_LHP 		= $default1['TTKP_LHP'];
$TTKP_JADPEL 	= $default1['TTKP_JADPEL'];
$TTKP_STRO 		= $default1['TTKP_STRO'];
$TTKP_SCURVE 	= $default1['TTKP_SCURVE'];

$TTK_DATEV	= date('d M Y', strtotime($TTK_DATE));

$TTK_CATEG	= '';
$sqlTTK		= "SELECT TTK_CATEG FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM' LIMIT 1";
$resTTK		= $this->db->query($sqlTTK)->result();
foreach($resTTK as $rowTTK) :
	$TTK_CATEG= $rowTTK->TTK_CATEG;
endforeach;

$REFNUM		= '';
if($TTK_CATEG == 'IR')
{
	$PO_CODE1	= '';
	// $sqlIR		= "SELECT TTK_REF1_NUM FROM tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM'";
	// $resIR		= $this->db->query($sqlIR)->result();
	// foreach($resIR as $rowIR) :
	// 	$TTK_REF1_NUM	= $rowIR->TTK_REF1_NUM;
	// 	$sqlPO		= "SELECT PO_CODE FROM tbl_ir_header WHERE IR_NUM = '$TTK_REF1_NUM'";
	// 	$resPO		= $this->db->query($sqlPO)->result();
	// 	foreach($resPO as $rowPO) :
	// 		$PO_CODE	= $rowPO->PO_CODE;
	// 		if($PO_CODE1 == '')
	// 			$PO_CODE1	= $PO_CODE;
	// 		else
	// 			$PO_CODE1	= "$PO_CODE1, $PO_CODE";
	// 	endforeach;
	// endforeach;
  $sqlIR		= "SELECT TTK_REF2_CODE FROM tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM' GROUP BY TTK_REF2_CODE";
  $resIR		= $this->db->query($sqlIR)->result();
  foreach($resIR as $rowIR) :
    $PO_CODE[] = $rowIR->TTK_REF2_CODE;
  endforeach;
  $PO_CODE1 = join(", ", $PO_CODE);
	$REFNUM	= $PO_CODE1;
}
elseif($TTK_CATEG == 'OPN')
{
	$WO_CODE1	= '';
	$sqlOPN		= "SELECT TTK_REF1_NUM FROM tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM'";
	$resOPN		= $this->db->query($sqlOPN)->result();
	foreach($resOPN as $rowOPN) :
		$TTK_REF1_NUM	= $rowOPN->TTK_REF1_NUM;
		$sqlWO		= "SELECT WO_NUM FROM tbl_opn_header WHERE OPNH_NUM = '$TTK_REF1_NUM'";
		$resWO		= $this->db->query($sqlWO)->result();
		foreach($resWO as $rowWO) :
			$WO_NUM	= $rowWO->WO_NUM;
			if($WO_NUM != '')
			{
				$WO_CODE	= '';
				$sqlWO1		= "SELECT WO_CODE FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWO1		= $this->db->query($sqlWO1)->result();
				foreach($resWO1 as $rowWO1) :
					$WO_CODE	= $rowWO1->WO_CODE;
				endforeach;
			}
			else
			{
				$WO_CODE	= '';
			}
			
			if($WO_CODE1 == '')
				$WO_CODE1	= $WO_CODE;
			else
				$WO_CODE1	= "$WO_CODE1, $WO_CODE";
		endforeach;
	endforeach;
	$REFNUM	= $WO_CODE1;
}


$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME= $rowPRJ->PRJNAME;
endforeach;

$SPLDESC	= '';
$sqlSPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
$resSPL		= $this->db->query($sqlSPL)->result();
foreach($resSPL as $rowSPL) :
	$SPLDESC= $rowSPL->SPLDESC;
endforeach;

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
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
	endforeach;

if(isset($_POST['submitSYNC']))
{
	$TTKP_RECDATE 	= $_POST['TTKP_RECDATE'];
	$TTKP_DENIED 	= $_POST['TTKP_DENIED'];
	$TTKP_DOCTYPE 	= $_POST['TTKP_DOCTYPE'];
	$TTKP_KWITPEN 	= $_POST['TTKP_KWITPEN'];
	$TTKP_FAKPAJAK 	= $_POST['TTKP_FAKPAJAK'];
	$TTKP_COPYPO 	= $_POST['TTKP_COPYPO'];
	$TTKP_BAKEM		= $_POST['TTKP_BAKEM'];
	$TTKP_LKEM 		= $_POST['TTKP_LKEM'];
	$TTKP_BAPRES 	= $_POST['TTKP_BAPRES'];
	$TTKP_SJ 		= $_POST['TTKP_SJ'];
	$TTKP_KMA 		= $_POST['TTKP_KMA'];
	$TTKP_KPPSA 	= $_POST['TTKP_KPPSA'];
	$TTKP_SI		= $_POST['TTKP_SI'];
	$TTKP_PKER		= $_POST['TTKP_PKER'];
	$TTKP_LPK		= $_POST['TTKP_LPK'];
	$TTKP_KDAB 		= $_POST['TTKP_KDAB'];
	$TTKP_FPMSM 	= $_POST['TTKP_FPMSM'];
	$TTKP_BOL 		= $_POST['TTKP_BOL'];
	$TTKP_LPP 		= $_POST['TTKP_LPP'];
	$TTKP_LPA 		= $_POST['TTKP_LPA'];
	$TTKP_KPM 		= $_POST['TTKP_KPM'];
	$TTKP_JAMSER 	= $_POST['TTKP_JAMSER'];
	$TTKP_TDPO 		= $_POST['TTKP_TDPO'];
	$TTKP_JUM 		= $_POST['TTKP_JUM'];
	$TTKP_JPEL 		= $_POST['TTKP_JPEL'];
	$TTKP_MPEL 		= $_POST['TTKP_MPEL'];
	$TTKP_LPD 		= $_POST['TTKP_LPD'];
	$TTKP_LHP 		= $_POST['TTKP_LHP'];
	$TTKP_JADPEL 	= $_POST['TTKP_JADPEL'];
	$TTKP_STRO 		= $_POST['TTKP_STRO'];
	$TTKP_SCURVE 	= $_POST['TTKP_SCURVE'];
	$TTKP_CREATE	= date('Y-m-d H:i:s');
	
	$sqlTTKP		= "tbl_ttk_print WHERE TTKP_NUM = '$TTK_NUM'";
	$resTTKP		= $this->db->count_all($sqlTTKP);
	if($resTTKP == 0)
	{
		$sqlInsTTKP	= "INSERT INTO tbl_ttk_print 
								(TTKP_NUM, TTKP_CODE, PRJCODE, TTKP_CREATE,
								TTKP_RECDATE, TTKP_DENIED, TTKP_DOCTYPE, TTKP_KWITPEN , TTKP_FAKPAJAK, TTKP_COPYPO, TTKP_BAKEM, 
								TTKP_LKEM, TTKP_BAPRES, TTKP_SJ, TTKP_KMA, TTKP_KPPSA, TTKP_SI, TTKP_PKER, TTKP_LPK, TTKP_KDAB, 
								TTKP_FPMSM, TTKP_BOL, TTKP_LPP, TTKP_LPA, TTKP_KPM, TTKP_JAMSER, TTKP_TDPO, TTKP_JUM, TTKP_JPEL, 
								TTKP_MPEL, TTKP_LPD, TTKP_LHP, TTKP_JADPEL, TTKP_STRO, TTKP_SCURVE) VALUES
								('$TTK_NUM', '$TTK_CODE', '$PRJCODE', '$TTKP_CREATE',
								 '$TTKP_RECDATE', '$TTKP_DENIED', '$TTKP_DOCTYPE', '$TTKP_KWITPEN ', '$TTKP_FAKPAJAK', '$TTKP_COPYPO',
								 '$TTKP_BAKEM', '$TTKP_LKEM', '$TTKP_BAPRES', '$TTKP_SJ', '$TTKP_KMA', '$TTKP_KPPSA', '$TTKP_SI', 
								 '$TTKP_PKER', '$TTKP_LPK', '$TTKP_KDAB', '$TTKP_FPMSM', '$TTKP_BOL', '$TTKP_LPP', '$TTKP_LPA', 
								 '$TTKP_KPM', '$TTKP_JAMSER', '$TTKP_TDPO', '$TTKP_JUM', '$TTKP_JPEL', '$TTKP_MPEL', '$TTKP_LPD', 
								 '$TTKP_LHP', '$TTKP_JADPEL', '$TTKP_STRO', '$TTKP_SCURVE')";
		$this->db->query($sqlInsTTKP);
	}
	else
	{
		$sqlUpdTTKP	= "UPDATE tbl_ttk_print SET
								TTKP_RECDATE = '$TTKP_RECDATE', TTKP_DENIED = '$TTKP_DENIED', TTKP_DOCTYPE = '$TTKP_DOCTYPE',
								TTKP_KWITPEN = '$TTKP_KWITPEN', TTKP_FAKPAJAK = '$TTKP_FAKPAJAK', TTKP_COPYPO = '$TTKP_COPYPO',
								TTKP_BAKEM = '$TTKP_BAKEM', TTKP_LKEM = '$TTKP_LKEM', TTKP_BAPRES = '$TTKP_BAPRES',
								TTKP_SJ = '$TTKP_SJ', TTKP_KMA = '$TTKP_KMA', TTKP_KPPSA = '$TTKP_KPPSA',
								TTKP_SI = '$TTKP_SI', TTKP_PKER = '$TTKP_PKER', TTKP_LPK = '$TTKP_LPK',
								TTKP_KDAB = '$TTKP_SI', TTKP_FPMSM = '$TTKP_FPMSM', TTKP_BOL = '$TTKP_BOL',
								TTKP_LPP = '$TTKP_LPP', TTKP_LPA = '$TTKP_LPA', TTKP_KPM = '$TTKP_KPM',
								TTKP_JAMSER = '$TTKP_JAMSER', TTKP_TDPO = '$TTKP_TDPO', TTKP_JUM = '$TTKP_JUM',
								TTKP_JPEL = '$TTKP_JPEL', TTKP_MPEL = '$TTKP_MPEL', TTKP_LPD = '$TTKP_LPD',
								TTKP_LHP = '$TTKP_LHP', TTKP_JADPEL = '$TTKP_JADPEL', TTKP_STRO = '$TTKP_STRO',
								TTKP_SCURVE = '$TTKP_SCURVE'
							WHERE TTKP_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpdTTKP);
	}
}
$sqlTTKP	= "tbl_ttk_print WHERE TTKP_NUM = '$TTK_NUM'";
$resTTKP	= $this->db->count_all($sqlTTKP);
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

    <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php echo $Transl_01; ?>
        </div>
    </div>
    <!-- Main content -->
<section class="invoice">
  <div class="row">
            <div class="col-xs-12">
                <table border="0" width="100%">
                    <tr>
                      <td width="14%" rowspan="2"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="150" height="100"></td>
                      <td width="70%" style="text-align:center; font-size:22px; font-weight:bold">DAFTAR PERIKSA DOKUMEN</td>
                      <td width="16%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align:center; font-size:14px;">No. Ref. : <?php echo $TTK_CODE; ?></td>
                      <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
  </div>
        <form name="frmsync" id="frmsync" action="" method=POST>
        	<table border="0" width="100%" style="font-size:14px">
                <tr>
                  <td nowrap>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                </tr>
                <tr>
                    <td width="15%" nowrap>No. &amp; Nama Proyek</td>
                    <td width="2%">:</td>
                    <td width="38%" nowrap><?php echo "$PRJCODE - $PRJNAME"; ?></td>
                    <td width="15%" nowrap>Pengirim</td>
                    <td width="30%" nowrap>: <font style="font-weight:bold; font-size:16px"><?php echo $SPLDESC; ?></font></td>
                </tr>
                <tr>
                    <td nowrap>No. PO / SPK / Kontrak</td>
                  <td>:</td>
                    <td nowrap><?php echo $REFNUM; ?></td>
                  <td nowrap>Pemeriksa</td>
                  <td nowrap>: <font style="font-weight:bold; font-size:16px"><?php echo $TTK_CHECKER; ?></font></td>
                </tr>
                <tr>
                    <td nowrap>Paket</td>
                  <td>:</td>
                  <td nowrap><?php echo $TTK_NOTES; ?></td>
                  <td nowrap>Tanggal Periksa Dokumen</td>
                  <td nowrap>: <font style="font-weight:bold; font-size:16px"><?php echo date('d/m/Y', strtotime($TTK_DATE)); ?></font></td>
                </tr>
                <tr>
                    <td nowrap><?php /*?>Jatuh Tempo Bayar<?php */?>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td nowrap><?php //echo date('d/m/Y', strtotime($TTK_DUEDATE)); ?></td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" nowrap>Status Dokumen :</td>
                  <td colspan="2" nowrap>Jenis Dokumen :</td>
                </tr>
                <tr>
                  <td colspan="5" nowrap>
                  <table width="100%" border="0" style="font-size:14px">
                    <tr>
                      <td width="3%"><input type="checkbox" name="TTKP_DENIED" value="1" <?php if($TTKP_DENIED == 1) { ?> checked <?php }?>></td>
                      <td width="50%">Diterima tanggal : <font style="font-weight:bold; font-size:16px"><?php echo date('d/m/Y', strtotime($TTK_DATE)); ?></font>
                        <input type="hidden" name="TTKP_RECDATE" style="max-width:250px" value="<?php echo $TTK_DATE; ?>">
                      </td>
                      <td><input type="checkbox" name="TTKP_DOCTYPE" value="1" <?php if($TTKP_DOCTYPE == 1) { ?> checked <?php }?>></td>
                      <td>Uang Muka / Down Payment ( 1 )</td>
                    </tr>
                    <tr>
                      <td width="3%"><input type="checkbox" name="TTKP_DENIED" value="1" <?php if($TTKP_DENIED == 1) { ?> checked <?php }?>></td>
                      <td width="50%">Jatuh Tempo tanggal : <font style="font-weight:bold; font-size:16px"><?php echo date('d/m/Y', strtotime($TTK_DUEDATE)); ?></font>
                        <input type="hidden" name="TTKP_RECDATE" style="max-width:250px" value="<?php echo $TTK_DUEDATE; ?>">
                      </td>
                      <td width="3%"><input type="checkbox" name="TTKP_DOCTYPE" value="2" <?php if($TTKP_DOCTYPE == 2) { ?> checked <?php }?>></td>
                      <td width="44%">Kemajuan Pekerjaan / Progress ( 2 )</td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" name="TTKP_DENIED" value="2" <?php if($TTKP_DENIED == 2) { ?> checked <?php }?>></td>
                      <td>Ditolak</td>
                      <td width="3%"><input type="checkbox" name="TTKP_DOCTYPE" value="2" <?php if($TTKP_DOCTYPE == 2) { ?> checked <?php }?>></td>
                      <td>Pekerjaan / Pengadaan Selesai 100 % ( 3 )</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input type="checkbox" name="TTKP_DOCTYPE" value="3" <?php if($TTKP_DOCTYPE == 3) { ?> checked <?php }?>></td>
                      <td>Retensi Pekerjaan / Pengadaan ( 4 )</td>
                    </tr>
                  </table></td>
                </tr>
        	</table>
            <table border="1" width="100%" style="font-size:14px">
                <tr style="background-color:#999">
                    <th width="3%" style="background-color:#999; vertical-align:middle">No.</th>
                    <th width="8%" style="background-color:#999; vertical-align:middle; text-align:center">Status</th>
                    <th width="56%" style="background-color:#999; vertical-align:middle; text-align:center">Persyaratan Dokumen</th>
                    <th width="17%" style="background-color:#999; vertical-align:middle; text-align:center">Keterangan</th>
                    <th width="16%" style="background-color:#999; vertical-align:middle; text-align:center">Kode</th>
                </tr>
                <tr>
                    <td style="text-align:center">1</td>
                    <td style="text-align:center" nowrap><input type="checkbox" <?php if($TTKP_KWITPEN != '') { ?> checked <?php }?>></td>
                    <td nowrap>Kwitansi Penagihan (Bermaterai)</td>
                    <td><?php echo $TTKP_KWITPEN; ?></td>
                    <td style="text-align:center">1,2,3,4</td>
                </tr>
                <tr>
                    <td style="text-align:center">2</td>
                    <td style="text-align:center"><input type="checkbox" <?php if($TTKP_FAKPAJAK != '') { ?> checked <?php }?>></td>
                    <td>Faktur Pajak</td>
                    <td><?php echo $TTKP_FAKPAJAK; ?></td>
                    <td style="text-align:center">1,2,3,4</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">3</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_COPYPO != '') { ?> checked <?php }?>></td>
                  <td>Copy PO / SPK / Kontrak</td>
                  <td><?php echo $TTKP_COPYPO; ?></td>
                  <td style="text-align:center">1,2,3,4</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">4</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_BAKEM != '') { ?> checked <?php }?>></td>
                  <td>Berita Acara Kemajuan / Berita Acara Serah Terima</td>
                  <td><?php echo $TTKP_BAKEM; ?></td>
                  <td style="text-align:center">2,3,4</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">5</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_LKEM != '') { ?> checked <?php }?>></td>
                  <td>Laporan Kemajuan / Laporan Serah Terima</td>
                  <td><?php echo $TTKP_LKEM; ?></td>
                  <td style="text-align:center">2,3,4</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">6</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_BAPRES != '') { ?> checked <?php }?>></td>
                  <td>Berita Acara Prestasi</td>
                  <td><?php echo $TTKP_BAPRES; ?></td>
                  <td style="text-align:center">2,3,4</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">7</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_SJ != '') { ?> checked <?php }?>></td>
                  <td>Surat Jalan (bila ada)</td>
                  <td><?php echo $TTKP_SJ; ?></td>
                  <td style="text-align:center">2,3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">8</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_KMA != '') { ?> checked <?php }?>></td>
                  <td>Konfirmasi Mobilisasi Alat (bila ada)</td>
                  <td><?php echo $TTKP_KMA; ?></td>
                  <td style="text-align:center">1</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">9</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_KPPSA != '') { ?> checked <?php }?>></td>
                  <td>Konfirmasi Perpanjangan Sewa Alat</td>
                  <td><?php echo $TTKP_KPPSA; ?></td>
                  <td style="text-align:center">1</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">10</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_SI != '') { ?> checked <?php }?>></td>
                  <td>Site Instruction (bila ada)</td>
                  <td><?php echo $TTKP_SI; ?></td>
                  <td style="text-align:center">2,3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">11</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_PKER != '') { ?> checked <?php }?>></td>
                  <td>Perubahan Pekerjaan (bila ada)</td>
                  <td><?php echo $TTKP_PKER; ?></td>
                  <td style="text-align:center">2,3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">12</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_LPK != '') { ?> checked <?php }?>></td>
                  <td>Laporan Perubahan Pekerjaan (bila ada)</td>
                  <td><?php echo $TTKP_LPK; ?></td>
                  <td style="text-align:center">2,3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">13</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_KDAB != '') { ?> checked <?php }?>></td>
                  <td>Konfirmasi Demobilisasi Alat Berat (bila ada)</td>
                  <td><?php echo $TTKP_KDAB; ?></td>
                  <td style="text-align:center">3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">14</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_FPMSM != '') { ?> checked <?php }?>></td>
                  <td>Form Pemeriksaan Material Subkont Masuk (bila ada)</td>
                  <td><?php echo $TTKP_FPMSM; ?></td>
                  <td style="text-align:center">2,3,4</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">15</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_BOL != '') { ?> checked <?php }?>></td>
                  <td>Bill Of Lading (bila ada)</td>
                  <td><?php echo $TTKP_BOL; ?></td>
                  <td style="text-align:center">1,2</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">16</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_LPP != '') { ?> checked <?php }?>></td>
                  <td>Laporan Pelaksanaan Pekerjaan (bila ada)</td>
                  <td><?php echo $TTKP_LPP; ?></td>
                  <td style="text-align:center">3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">17</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_LPA != '') { ?> checked <?php }?>></td>
                  <td>Laporan Penggunaan Alat (Timesheet) (bila ada)</td>
                  <td><?php echo $TTKP_LPA; ?></td>
                  <td style="text-align:center">2,3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">18</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_KPM != '') { ?> checked <?php }?>></td>
                  <td>Konfirmasi Pesanan Material Berstampel (bila ada)</td>
                  <td><?php echo $TTKP_KPM; ?></td>
                  <td style="text-align:center">2,3</td>
                </tr>
                <tr style="border-color:#000">
                  <td style="text-align:center">19</td>
                  <td style="text-align:center"><input type="checkbox" <?php if($TTKP_JAMSER != '') { ?> checked <?php }?>></td>
                  <td>Jaminan / Sertifikat</td>
                  <td><?php echo $TTKP_JAMSER; ?></td>
                  <td style="text-align:center">3</td>
                </tr>
              <tr style="border-color:#000">
                  <td style="text-align:center">20</td>
                  <td style="text-align:center">&nbsp;</td>
                  <td>Tambahan Dokumen PO / SPK / Kontrak (bila ada) :</td>
                  <td><?php echo $TTKP_TDPO; ?></td>
                  <td style="text-align:center">&nbsp;</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_JUM != '') { ?> checked <?php }?>></td>
                <td>* Jaminan Uang Muka</td>
                <td><?php echo $TTKP_JUM; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_JPEL != '') { ?> checked <?php }?>></td>
                <td>* Jaminan Pelaksanaan</td>
                <td><?php echo $TTKP_JPEL; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_MPEL != '') { ?> checked <?php }?>></td>
                <td>* Metode Pelaksanaan</td>
                <td><?php echo $TTKP_MPEL; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_LPD != '') { ?> checked <?php }?>></td>
                <td>* Lampiran Perhitungan Design</td>
                <td><?php echo $TTKP_LPD; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_LHP != '') { ?> checked <?php }?>></td>
                <td>* Lampiran Hasil Pengujian</td>
                <td><?php echo $TTKP_LHP; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_JADPEL != '') { ?> checked <?php }?>></td>
                <td>* Jadwal Pelaksanaan</td>
                <td><?php echo $TTKP_JADPEL; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_STRO != '') { ?> checked <?php }?>></td>
                <td>* Struktur Organisasi</td>
                <td><?php echo $TTKP_STRO; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center"><input type="checkbox" <?php if($TTKP_SCURVE != '') { ?> checked <?php }?>></td>
                <td>* S Curve</td>
                <td><?php echo $TTKP_SCURVE; ?></td>
                <td style="text-align:center">1</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
              </tr>
              <tr style="border-color:#000">
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
              </tr>
          	</table>
            <table width="100%" border="1">
                <tr>
                    <td width="73%">
                    	
         	<table width="100%" border="0">
				<?php
					//$TTK_GTOTAL	= $TTK_GTOTAL - $TTK_AMOUNT_RET - $TTK_AMOUNT_POT;
					$TTK_GTOTAL	= round($TTK_GTOTAL,2);
                    $terbilang = $moneyFormat->terbilang($TTK_GTOTAL);
					$strlnRP	= strlen($terbilang);
                ?>
                <tr>
                    <td width="3%"><input type="checkbox"></td>
                    <td width="24%">Termasuk Pajak</td>
                    <td colspan="2">Nilai Invoice&nbsp;&nbsp;&nbsp;<font style="font-weight:bold; font-size:16px"> Rp. <?php echo number_format($TTK_GTOTAL, 2); ?></font></td>
                </tr>
                <tr>
                  <td><input type="checkbox"></td>
                  <td>Tidak Termasuk Pajak</td>
                  <td width="6%">Terbilang</td>
                  <td width="67%">:&nbsp;<font style="font-weight:bold; font-size:16px"><?php echo $terbilang; ?> Rupiah</font></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp; ______________________________________________________________________________</td>
                </tr>
          </table>
                    </td>
              </tr>
          </table>
          <table width="100%" border="1">
                <tr>
                    <td width="73%">
                    	<table width="100%" border="0">
                <tr>
                    <td colspan="2">Catatan :<br>
                      Seluruh dokumen harus diserahkan sejumlah 1 (satu) set asli dan 1 (satu) set copy (Kwintansi dan Faktur Pajak)<br>
                    Catatan Lainnya :</td>
                </tr>
                <?php
					if($TTK_NOTES1 == '')
						$TTK_NOTES1 = "__________________________________________________________________________________";
				?>
                <tr>
                  <td width="3%">&nbsp;</td>
                  <td><?php echo $TTK_NOTES1; ?><br>
                  __________________________________________________________________________________</td>
                  </tr>
                      </table>
                    </td>
              </tr>
          </table>
          
            <table width="100%" border="0">
                <tr>
                    <td colspan="2" style="font-style:italic">09.R0/KEU/17 (1 Juni 2017)</td>
                    </tr>
                <tr>
                  <td width="65%">&nbsp;</td>
                  <td width="35%" style="text-align:center"><p>Pemeriksa,</p>
                    <p>&nbsp;</p>
                    <p>_______________________<br>
                      Finance
                  </p></td>
              </tr>
                <tr>
                  <td colspan="2">Syarat dan Ketentuan :<br>
                    * Kekeliruan atau kekurangjelasan dokumen menjadi tanggung jawab penagih dan<br>
                  &nbsp;&nbsp;&nbsp;<?php echo $comp_name; ?> tidak bertanggung jawab terhadap segala kerugian yang timbul akibat hal tersebut.</td>
                </tr>
            	</table>
    	</form>
        <br>
        <br> 
         <?php
			$url_TTKP	= site_url('c_purchase/c_pi180c23/printTTKP/?id='.$this->url_encryption_helper->encode_url($TTK_NUM));
		?>
		<script>
			var url = "<?php echo $url_TTKP;?>";
			function getPRINT()
			{
				title = 'Select Item';
				w = 1000;
				h = 550;
				//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				var left = (screen.width/2)-(w/2);
				var top = (screen.height/2)-(h/2);
				return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			}
		</script>
    <!-- /.row --><!-- /.row -->
<!-- this row will not appear when printing --></section>
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