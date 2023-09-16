<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Junli 2018
 * File Name	= profit_loss_view.php
 * Location		= -
*/

$Periode1 = date('YmdHis');
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LapNeraca_$Periode1.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
if($TOTPROJ == 1)
{
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
					WHERE A.PRJCODE IN ($PRJCODECOL)
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
	endforeach;
	$PRJCODECOLL	= "'$PRJCODED'";
	$PRJNAMECOLL	= "$PRJNAMED";
}
else
{
	$PRJCODED	= 'Multi Project Code';
	$PRJNAMED 	= 'Multi Project Name';
	$myrow		= 0;
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
					WHERE A.PRJCODE IN ($PRJCODECOL)
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
		if($myrow == 1)
		{
			$PRJCODECOLL	= "$PRJCODED";
			$PRJCODECOL1	= "$PRJCODED";
			$PRJNAMECOLL	= "$PRJNAMED";
			$PRJNAMECOL1	= "$PRJNAMED";
		}
		if($myrow > 1)
		{
			$PRJCODECOL1	= "$PRJCODECOL1', '$PRJCODED";
			$PRJCODECOLL	= "'$PRJCODECOL1'";
			$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
			$PRJNAMECOLL	= "$PRJNAMECOL1";
		}		
	endforeach;
	//echo $PRJCODECOLL;
	//return false;
}
$year	= date('Y');
function getStartAndEndDate($week, $year)
{
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    //$time += ((7*$week)+1-$day)*24*3600;
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-n-j', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-n-j', $time);
    return $return;
}
//function indonesian_date ($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
function indonesian_date ($timestamp = '', $date_format = 'l, j F Y | H:i') {
    if (trim ($timestamp) == '')
    {
            $timestamp = time ();
    }
    elseif (!ctype_digit ($timestamp))
    {
        $timestamp = strtotime ($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace ("/S/", "", $date_format);
    $pattern = array (
        '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
        '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
        '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
        '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
        '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
        '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
        '/April/','/June/','/July/','/August/','/September/','/October/',
        '/November/','/December/',
    );
    $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
        'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
        'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
        'Oktober','November','Desember',
    );
    $date = date ($date_format, $timestamp);
    $date = preg_replace ($pattern, $replace, $date);
    $date = "{$date}";
    return $date;
}

$LangID 	= $this->session->userdata['LangID'];

$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl		= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;
	
	if($TranslCode == 'Project')$Project = $LangTransl;
	if($TranslCode == 'Code')$Code = $LangTransl;
	if($TranslCode == 'Name')$Name = $LangTransl;
	if($TranslCode == 'AccountPayable')$AccountPayable = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
	if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'EndDate')$EndDate = $LangTransl;
	if($TranslCode == 'Status')$Status = $LangTransl;
	if($TranslCode == 'PlayedStatus')$PlayedStatus = $LangTransl;
	if($TranslCode == 'CheckTheList')$CheckTheList = $LangTransl;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title>Report</title>
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
			font: 10px; Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
        width: 21cm;
        min-height: 29.7cm;
        padding: 0.5cm;
        margin: 0.5cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .subpage {
        padding: 1cm;
        height: 256mm;
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
		
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>
</head>
<body style="overflow:auto">
<div class="page">
<section class="content">
        <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="10%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/print.gif');?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="51%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" class="style2" style="text-align:left; font-weight:bold; font-size:14px"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="100" height="44"></td>
        <td colspan="2" valign="bottom" class="style2" style="text-align:center; font-weight:bold; font-size:14px"><span class="style2" style="text-align:center; font-weight:bold; font-size:14px"><?php echo strtoupper($comp_name); ?></span></td>
      </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:14px">
        	LAPORAN ARUS KAS<br>
        	Periode : 
        </td>
      </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr noshade /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
        	<table width="100%" border="0" style="size:auto">
                <?php
				?>
                <tr style="font-weight:bold">
                	<td width="25%"> ASET</td>
                    <td width="20%" style="text-align:right">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="25%">KEWAJIBAN</td>
                </tr>
                <tr>
                	<td style="font-weight:bold">&nbsp;&nbsp;Aset Lancar</td>
                    <td style="text-align:right; font-weight:bold">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Hutang Usaha</td>
                </tr>
                <tr>
                	<td style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Dan Setara Kas</td>
                    <td style="text-align:right; font-weight:bold">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Hutang Bank</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Kantor</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Hutang Lain-lain</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Proyek</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="font-weight:bold">EKUITAS</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piutang Usaha</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Modal Disetor</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piutang Lain-Lain</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Laba Rugi Ditahan</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Uang Muka Pembelian</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Laba Rugi Bulan Berjalan</td>
                </tr>
              <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persediaan</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Biaya dibayar dimuka</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pajak dibayar dimuka</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">&nbsp;&nbsp;Aset Tidak Lancar</td>
                    <td style="text-align:right; font-weight:bold">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aset Tetap</td>
                    <td style="text-align:right; font-weight:bold">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanah</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bangunan</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mesin</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kendaraan</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Proyek</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Kantor</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perlengkapan Proyek</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perlengkapan Kantor</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inventaris</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Akumulasi Penyusutan</td>
                    <td style="text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                    <td style="border-top:double; border-bottom:groove; text-align:right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                 <tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</section>
</div>
</body>
</html>