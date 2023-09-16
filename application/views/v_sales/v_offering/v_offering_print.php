<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 19 Agustus 2020
 * File Name	= v_offering_print.php
 * Location		= -*/

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

$OFF_NUM     = $default['OFF_NUM'];
$OFF_CODE    = $default['OFF_CODE'];
$OFF_DATE    = $default['OFF_DATE'];
$PRJCODE     = $default['PRJCODE'];
$DEPCODE     = $default['DEPCODE'];
$CUST_CODE   = $default['CUST_CODE'];
$CUST_DESC   = "-";
$sqlCust     = "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
$resCust     = $this->db->query($sqlCust)->result();
foreach($resCust as $rowCust) :
    $CUST_DESC  = $rowCust->CUST_DESC;
endforeach;

$CUST_ADDRESS= $default['CUST_ADDRESS'];
$CCAL_NUM    = $default['CCAL_NUM'];
$CCAL_CODE   = $default['CCAL_CODE'];
$BOM_NUM     = $default['BOM_NUM'];
$BOM_CODE    = $default['BOM_CODE'];
$SO_NUM      = $default['SO_NUM'];
$OFF_TOTCOST = $default['OFF_TOTCOST'];
$OFF_TOTDISC = $default['OFF_TOTDISC'];
$OFF_TOTPPN  = $default['OFF_TOTPPN'];
$OFF_NOTES   = $default['OFF_NOTES'];
$OFF_NOTES1  = $default['OFF_NOTES1'];
$OFF_MEMO    = $default['OFF_MEMO'];
$PRJNAME     = $default['PRJNAME'];
$OFF_STAT    = $default['OFF_STAT'];
$OFF_SOSTAT  = $default['OFF_SOSTAT'];

$OFF_DATEV	= strftime('%d %B %Y', strtotime($OFF_DATE));
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME= $rowPRJ->PRJNAME;
endforeach;

if($OFF_STAT == 2)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafCONFIRM.png) no-repeat 50% 50% !important";
}
elseif($OFF_STAT == 9)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafVOID.png) no-repeat 50% 50% !important";
}
else
{
    $DrafTTD1   = "white";
}
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
            padding-left: 1cm;
            padding-right: 1cm;
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
        $header     = "PENAWARAN HARGA";
        $alert1     = "Pengaturan Departemen Pengguna";
        $alert2     = "Status pengguna belum ditentukan pada departemen manapun, sehingga tidak dapat membuat dokumen ini. Silahkan hubungi admin untuk meminta bantuan.";
    }
    else
    {
        $header     = "OFFERING LETTER OF PRICE";
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
            <tr style="display: none;">
                <td rowspan="2"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>No. Ref. : <?php echo $OFF_CODE; ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center; font-size:18px; font-weight:bold"><?php echo $header.'<br>'.$comp_name; ?></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table border="0" width="100%">
            <tr>
                <td width="120">&nbsp;</td>
                <td width="5">&nbsp;</td>
                <td width="200">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><?=$DocNumber?></td>
                <td>:</td>
                <td><?=$OFF_CODE?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><?=$Date?></td>
                <td>:</td>
                <td><?=$OFF_DATEV?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><?=$CustName?></td>
                <td>:</td>
                <td><?=$CUST_DESC?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <?php
            $maxRow     = 4;
            $rowNo      = 0;
            $sqlOFFDetC = "tbl_offering_h WHERE OFF_NUM = '$OFF_NUM'";
            $resOFFDetC = $this->db->count_all($sqlOFFDetC);
            
            $sqlOFFDet   = "SELECT A.OFF_NUM, A.OFF_CODE, A.ITM_CODE, A.OFF_VOLM, A.OFF_PRICE, A.OFF_COST, A.OFF_DISC, A.TAXPRICE1, A.OFF_TOTCOST,
                                B.CUST_CODE, B.BOM_NUM, B.BOM_CODE, B.CCAL_NUM, B.CCAL_CODE,
                                C.ITM_NAME, C.ITM_UNIT
                            FROM tbl_offering_d A
                                INNER JOIN tbl_offering_h B ON A.OFF_NUM = B.OFF_NUM
                                INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
                            WHERE A.OFF_NUM = '$OFF_NUM'";
            $resOFFDet   = $this->db->query($sqlOFFDet)->result();
            foreach($resOFFDet as $rowOFFD) :
                $rowNo          = $rowNo + 1;
                $OFF_NUM        = $rowOFFD->OFF_NUM;
                $OFF_CODE       = $rowOFFD->OFF_CODE;
                $CUST_CODE      = $rowOFFD->CUST_CODE;
                $BOM_NUM        = $rowOFFD->BOM_NUM;
                $BOM_CODE       = $rowOFFD->BOM_CODE;
                $CCAL_NUM       = $rowOFFD->CCAL_NUM;
                $CCAL_CODE      = $rowOFFD->CCAL_CODE;

                $ITM_CODE       = $rowOFFD->ITM_CODE;
                $ITM_NAME       = $rowOFFD->ITM_NAME;
                $ITM_UNIT       = $rowOFFD->ITM_UNIT;
                $OFF_VOLM       = $rowOFFD->OFF_VOLM;
                $OFF_PRICE      = $rowOFFD->OFF_PRICE;  // INCLUDE PPN
                $OFF_COST       = $rowOFFD->OFF_COST;
                $OFF_DISC       = $rowOFFD->OFF_DISC;
                $TAXPRICE1      = $rowOFFD->TAXPRICE1;
                $OFF_TOTCOST    = $rowOFFD->OFF_TOTCOST;

                $PRC_INCPPN     = $OFF_PRICE;           // SEHARUSNYA OFF_PRICE ITU HARGA / SATUAN UNIT PRODUKSI
                $PRC_NONPPN     = $PRC_INCPPN / 1.10;

                // HPP GET FROM RESEP/BOM
                    // 1. GET QTY PRODUKSI
                        $QTYPRD     = 0;
                        $sqlPRD     = "SELECT SUM(A.ITM_QTY) AS QTYPRD FROM tbl_bom_detail A
                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ISFG = 1
                                        WHERE A.BOM_NUM = '$BOM_NUM' AND A.BOM_TYPE = 'OUT' AND A.ITM_CODE = '$ITM_CODE'";
                        $resPRD     = $this->db->query($sqlPRD)->result();
                        foreach($resPRD as $rowPRD) :
                            $QTYPRD = $rowPRD->QTYPRD;
                        endforeach;
                        $QTYPRD     = $QTYPRD ?: 1;     // HARUS 1 JIKA NO, KARENA UNTUK PEMBAGIAN

                    // 2. GET TOTAL PRICE PRODUKSI
                        $PRCHPP     = 0;
                        $sqlHPP     = "SELECT SUM(A.ITM_TOTAL) AS PRC_HPP FROM tbl_bom_detail A
                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
                                            AND B.ISWIP = 0 AND B.ISFG = 0
                                        WHERE A.BOM_NUM = '$BOM_NUM'";      // TANPA DIFILTER EBRDASARKAN ITM_CODE, KARENA RM
                        $resHPP     = $this->db->query($sqlHPP)->result();
                        foreach($resHPP as $rowHPP) :
                            $PRCHPP = $rowHPP->PRC_HPP;
                        endforeach;
                        $PRCHPP     = $PRCHPP ?: 0;

                    // PASTIKAN BAHWA HPP INI PER SATUAN UNIT PRODUKSI
                        $PRC_HPP    = $PRCHPP / $QTYPRD;

                // PROFIT DIBANDINGKAN DENGAN HARGA POKOK
                    $PROFNL         = $PRC_NONPPN - $PRC_HPP;
                    $PROFNLP        = $PROFNL / $PRC_HPP * 100;

                // BOM
                    $BOM_NAME       = "-";
                    $sqlBOM         = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_NUM = '$BOM_NUM' AND BOM_FG = '$ITM_CODE'";
                    $resBOM         = $this->db->query($sqlBOM)->result();
                    foreach($resBOM as $rowBOM) :
                        $BOM_NAME   = $rowBOM->BOM_NAME;
                    endforeach;
                if($rowNo<=4)
                {
                ?>
                    <table border="1" width="100%">
                        <tr class="hcol1" style="background-color:#F7DC6F; font-weight: bold;">
                            <td colspan="3" style="vertical-align:middle"><?=strtoupper($Color)?> : <?=$ITM_NAME?></td>
                        </tr>
                        <tr style="background-color:#E5E8E8; font-weight: bold;">
                            <td colspan="2" style="text-align: center;"><?=strtoupper($Remarks)?></td>
                            <td style="text-align:center"><?=strtoupper($Nominal)?></td>
                        </tr>
                        <tr>
                            <td width="4%" style="vertical-align:middle">&nbsp;</td>
                            <td width="48%" style="vertical-align:middle"><?=$salesPrcCust?> (Include PPN)</td>
                            <td width="48%" style="vertical-align:middle; text-align:center"><?=number_format($PRC_INCPPN, 2)?></td>
                        </tr>
                        <tr>
                            <td width="4%" style="vertical-align:middle">&nbsp;</td>
                            <td width="48%" style="vertical-align:middle"><?=$salesPrcCust?></td>
                            <td width="48%" style="vertical-align:middle; text-align:center"><?=number_format($PRC_NONPPN, 2)?></td>
                        </tr>
                        <tr>
                            <td width="4%" style="vertical-align:middle">&nbsp;</td>
                            <td width="48%" style="vertical-align:middle">HPP</td>
                            <td width="48%" style="vertical-align:middle; text-align:center"><?=number_format($PRC_HPP, 2)?></td>
                        </tr>
                        <tr>
                            <td width="4%" style="vertical-align:middle">&nbsp;</td>
                            <td width="48%" style="vertical-align:middle">Profit / (Loss)</td>
                            <td width="48%" style="vertical-align:middle; text-align:center"><?=number_format($PROFNL, 2)?></td>
                        </tr>
                        <tr>
                            <td width="4%" style="vertical-align:middle">&nbsp;</td>
                            <td width="48%" style="vertical-align:middle">% Profit / (Loss)</td>
                            <td width="48%" style="vertical-align:middle; text-align:center"><?=number_format($PROFNLP, 2)?></td>
                        </tr>
                    </table>
                <?php
                $remRow = 4-$rowNo;
                }
            endforeach;
            for($i=0;$i<$remRow;$i++)
            {
            ?>
                <br>
                <table border="1" width="100%">
                    <tr class="hcol1" style="background-color:#F7DC6F; font-weight: bold;">
                        <td colspan="3" style="vertical-align:middle"><?=strtoupper($Color)?> : </td>
                    </tr>
                    <tr style="background-color:#E5E8E8; font-weight: bold;">
                        <td colspan="2" style="text-align: center;"><?=strtoupper($Remarks)?></td>
                        <td style="text-align:center"><?=strtoupper($Nominal)?></td>
                    </tr>
                    <tr>
                        <td width="4%" style="vertical-align:middle">&nbsp;</td>
                        <td width="48%" style="vertical-align:middle"><?=$salesPrcCust?> (Include PPN)</td>
                        <td width="48%" style="vertical-align:middle; text-align:center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="4%" style="vertical-align:middle">&nbsp;</td>
                        <td width="48%" style="vertical-align:middle"><?=$salesPrcCust?></td>
                        <td width="48%" style="vertical-align:middle; text-align:center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="4%" style="vertical-align:middle">&nbsp;</td>
                        <td width="48%" style="vertical-align:middle">HPP</td>
                        <td width="48%" style="vertical-align:middle; text-align:center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="4%" style="vertical-align:middle">&nbsp;</td>
                        <td width="48%" style="vertical-align:middle">Profit / (Loss)</td>
                        <td width="48%" style="vertical-align:middle; text-align:center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="4%" style="vertical-align:middle">&nbsp;</td>
                        <td width="48%" style="vertical-align:middle">% Profit / (Loss)</td>
                        <td width="48%" style="vertical-align:middle; text-align:center">&nbsp;</td>
                    </tr>
                </table>
                <?php
            }
            ?>
        <br>
        <table border="1" width="100%">
            <tr height="60px" style="border-color:#000; vertical-align:top">
                <td colspan="9">Catatan: <?php //echo $PR_NOTE; ?></td>
            </tr>
        </table>
        <br>
        <table width="100%" border="1">
            <tr>
                <td width="50%" style="text-align: center;"><?=$comp_name?></td>
                <td width="50%" style="text-align: center;">COMMITEE</td>
            </tr>
            <tr>
                <td style="text-align: center;"><?=strtoupper($Created)?></td>
                <td style="text-align: center;"><?=strtoupper($Approved)?></td>
            </tr>
            <tr>
                <td style="text-align: center;"><br><br><br><br><br></td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td>NAMA : </td>
                <td>NAMA : </td>
            </tr>
            <tr>
                <td style="text-align: center;">FAM</td>
                <td style="text-align: center;">FC</td>
            </tr>
        </table>
        <table width="100%" border="0">
            <tr>
                <td width="13%">Keterangan : </td>
                <td width="30%">FAM = Finance Accounting Manager</td>
                <td width="55%">FC = Finance Committee</td>
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