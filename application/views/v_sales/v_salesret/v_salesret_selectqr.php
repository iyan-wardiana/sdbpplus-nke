<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 07 Februari 2020
 * File Name    = v_salesret_selectqr.php
 * Location     = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$comp_name  = $this->session->userdata('comp_name');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;

$FlagUSER       = $this->session->userdata['FlagUSER'];
$DefEmp_ID      = $this->session->userdata['Emp_ID'];

$currentRow = 0;

$DefEmp_ID          = $this->session->userdata['Emp_ID'];

$SO_NUM         = '';
$SC_NUM         = '';
$SO_CODE        = '';
$SO_TYPE        = 1; // Internal
$SO_CAT         = 1;
$SO_DATE        = '';
$PRJNAME        = '';
$CUST_CODE      = '0';
$CUST_DESC      = '';
$CUST_ADD1      = '';
$SO_CURR        = 'IDR';
$SO_CURRATE     = 1;
$SO_TAXCURR     = 'IDR';
$SO_TAXRATE     = 1;
$SO_TOTCOST     = 0;
$DP_CODE        = '';
$DP_JUML        = 0;
$SO_PAYTYPE     = 'Cash';
$SO_TENOR       = 0;
$SO_STAT        = 1;
$SO_INVSTAT     = 0;                    
$SO_NOTES       = '';
$SO_MEMO        = '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/css/bootstrapxxx.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/css/style.css'; ?>">

    <?php
        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_lnk like '%sweet%' AND cssjs_typ = 'css' AND isAct = 1";
        $rescss = $this->db->query($sqlcss)->result();
        foreach($rescss as $rowcss) :
            $cssjs_lnk  = $rowcss->cssjs_lnk;
            ?>
                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
            <?php
        endforeach;
    ?>
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
    //$this->load->view('template/topbar');
    //$this->load->view('template/sidebar');
    
    $ISREAD     = $this->session->userdata['ISREAD'];
    $ISCREATE   = $this->session->userdata['ISCREATE'];
    $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
    $ISDWONL    = $this->session->userdata['ISDWONL'];
    $LangID     = $this->session->userdata['LangID'];

    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl      = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;
        
        if($TranslCode == 'Add')$Add = $LangTransl;
        if($TranslCode == 'Edit')$Edit = $LangTransl;
        if($TranslCode == 'Code')$Code = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
        if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
        if($TranslCode == 'OwnerName')$OwnerName = $LangTransl;
        if($TranslCode == 'Customer')$Customer = $LangTransl;
        if($TranslCode == 'IRCode')$IRCode = $LangTransl;
        if($TranslCode == 'SOCode')$SOCode = $LangTransl;
        if($TranslCode == 'SODate')$SODate = $LangTransl;
        if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
        if($TranslCode == 'JOCode')$JOCode = $LangTransl;
        if($TranslCode == 'JODate')$JODate = $LangTransl;
        if($TranslCode == 'ShipmentNo')$ShipmentNo = $LangTransl;
        if($TranslCode == 'SendDate')$SendDate = $LangTransl;
        if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
        if($TranslCode == 'Description')$Description = $LangTransl;
        if($TranslCode == 'Status')$Status = $LangTransl;
        if($TranslCode == 'ShowDetail')$ShowDetail = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Select')$Select = $LangTransl;
        if($TranslCode == 'Close')$Close = $LangTransl;
        if($TranslCode == 'Received')$Received = $LangTransl;
        if($TranslCode == 'Sender')$Sender = $LangTransl;
        if($TranslCode == 'Address')$Address = $LangTransl;
        if($TranslCode == 'maxQty')$maxQty = $LangTransl;
        /*if($TranslCode == 'sentDest')$sentDest = $LangTransl;
        if($TranslCode == 'sentAdd')$sentAdd = $LangTransl;*/
    endforeach;
    if($LangID == 'IND')
    {
        $alert1     = "Silahkan Scan QR Code.";
        $alert2     = "Silahkan pilih nama supplier";
        $alert3     = "QRC tidak terdaftar.";
        $alert4     = "QRC sudah pernah diterima dengan nomor pengembalian ";
        $alert5     = "Pengiriman ini sudah dibuatkan faktur : ";
        $isManual   = "Centang untuk kode manual.";
        $sentDest   = "Tujuan pengiriman";
        $sentAdd   = "Tujuan pengiriman";
    }
    else
    {
        $alert1     = "Please scan the QR Code.";
        $alert2     = "Please select a supplier name";
        $alert3     = "QRC is not yet registered..";
        $alert4     = "QRC has been received with return no. ";
        $alert5     = "This shipment has been invoiced No. : ";
        $isManual   = "Check to manual code.";
        $sentDest   = "Tujuan pengiriman";
        $sentAdd   = "Tujuan pengiriman";
    }
    // IRFBS19-FBSHO-191023063819010002 -- GRP.19.093204.00001 -- 191030171118
    $QRCode     = 'JO.0001.19.11';
    $QRCType    = '';
    $PRJCODE    = '';
    $ITM_CODE   = '';
    $IR_CODE    = '';
    $QRC_CODEV  = '';
    $QRC_DATE   = '';
    $CUST_DESC  = '';
    $IR_NUM     = '';
    $IR_CODE    = '';
    $IR_DATE    = '';
    $ITM_CODE   = '';
    $ITM_NAME   = '';
    $GRP_CODE   = '';
    $JO_NUM     = '';
    $JO_CODE    = '';
    $JO_DATE    = '';
    $JO_DATEV   = '';
    $JO_DESC    = '';
    $SO_NUM     = '';
    $SO_CODE    = '';
    $SO_DATEV   = '';
    $SN_CODE    = '';
    $SN_DATEV   = '';
    $TOT_SOVOLM = 0;
    $BOM_CODE   = '';
    $ITM_CODE   = '';
    $BOM_NAME   = '';
    $QRC_PATT   = '';
    $ITM_UNIT   = '';
    $ITM_QTY    = 0;
    $collDATA   = '';

    if(isset($_POST['scanned-QRText']))
    {
        $QRCode     = $_POST['scanned-QRText'];
        $QRCType    = $_POST['QRCType'];
        $collDATA   = $_POST['collDATA'];
        if($QRCType == 'QRCL')
        {
            $ISEXIST    = 1;
            $PRJCODE    = '';
            $ITM_CODE   = '';
            $IR_CODE    = '';
            $QRC_CODEV  = '';
            $QRC_DATE   = '';
            $CUST_DESC  = '';
            $IR_NUM     = '';
            $IR_CODE    = '';
            $IR_DATE    = '';
            $ITM_CODE   = '';
            $ITM_NAME   = '';
            $GRP_CODE   = '';
            $JO_NUM     = '';
            $JO_CODE    = '';
            $JO_DESC    = '';
            $SO_NUM     = '';
            $SO_CODE    = '';
            $SO_DATEV   = '';
            $TOT_SOVOLM = 0;
            $BOM_CODE   = '';
            $ITM_CODE   = '';
            $BOM_NAME   = '';
            $QRC_PATT   = '';
            $ITM_UNIT   = '';
            $ITM_QTY    = 0;
            $sqlQRC     = "SELECT PRJCODE, QRC_CODEV, QRC_DATE, REC_DESC AS CUST_DESC, IR_NUM, IR_CODE, ITM_CODE, ITM_NAME, GRP_CODE, JO_NUM, QRC_PATT, ITM_UNIT, ITM_QTY
                                FROM tbl_qrc_detail WHERE QRC_NUM = '$QRCode' LIMIT 1";
            $resQRC     = $this->db->query($sqlQRC)->result();
            foreach($resQRC as $rowQRC) :
                $PRJCODE    = $rowQRC->PRJCODE;
                $QRC_CODEV  = $rowQRC->QRC_CODEV;
                $QRC_DATE   = $rowQRC->QRC_DATE;
                $CUST_DESC  = $rowQRC->CUST_DESC;
                $IR_NUM     = $rowQRC->IR_NUM;
                $IR_CODE    = $rowQRC->IR_CODE;
                $ITM_CODE   = $rowQRC->ITM_CODE;
                $ITM_NAME   = $rowQRC->ITM_NAME;
                $GRP_CODE   = $rowQRC->GRP_CODE;
                $JO_NUM     = $rowQRC->JO_NUM;
                $QRC_PATT   = $rowQRC->QRC_PATT;
                $ITM_UNIT   = $rowQRC->ITM_UNIT;
                $ITM_QTY    = $rowQRC->ITM_QTY;
            endforeach;

            $sqlIR          = "SELECT IR_DATE FROM tbl_ir_header WHERE IR_NUM = '$IR_NUM' LIMIT 1";
            $resIR          = $this->db->query($sqlIR)->result();
            foreach($resIR as $rowIR) :
                $IR_DATE    = date('d-m-Y', strtotime($rowIR->IR_DATE));
            endforeach;

            if($JO_NUM != '')
            {
                $sqlJO     = "SELECT A.JO_CODE, A.JO_DATE, A.JO_DESC, A.SO_NUM, A.SO_CODE, A.BOM_CODE, B.ITM_CODE, C.SO_DATE
                                FROM tbl_jo_header A
                                    INNER JOIN tbl_jo_detail B ON B.JO_NUM = A.JO_NUM
                                    INNER JOIN tbl_so_header C ON C.SO_NUM = A.SO_NUM
                                WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
                $resJO      = $this->db->query($sqlJO)->result();
                foreach($resJO as $rowJO) :
                    $JO_CODE    = $rowJO->JO_CODE;
                    $JO_DESC    = $rowJO->JO_DESC;
                    $JO_DATEV   = date('d-m-Y', strtotime($rowJO->JO_DATE));
                    $SO_NUM     = $rowJO->SO_NUM;
                    $SO_CODE    = $rowJO->SO_CODE;
                    $SO_DATEV   = date('d-m-Y', strtotime($rowJO->SO_DATE));
                    $BOM_CODE   = $rowJO->BOM_CODE;
                    $ITM_CODE   = $rowJO->ITM_CODE;
                endforeach;

                if($SO_NUM != '')
                {
                    $sqlSO      = "SELECT SUM(SO_VOLM) AS TOT_SOVOLM from tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
                    $resSO      = $this->db->query($sqlSO)->result();
                    foreach($resSO as $rowSO) :
                        $TOT_SOVOLM    = $rowSO->TOT_SOVOLM;
                    endforeach;
                }

                if($BOM_CODE != '')
                {
                    $sqlBOM     = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
                    $sqlBOM     = $this->db->query($sqlBOM)->result();
                    foreach($sqlBOM as $rowBOM) :
                        $BOM_NAME   = $rowBOM->BOM_NAME;
                    endforeach;                     
                }
            }
        }
        else if($QRCType == 'GRPL')
        {
            $ISEXIST    = 1;
            $PRJCODE    = '';
            $ITM_CODE   = '';
            $IR_CODE    = '';
            $QRC_CODEV  = '';
            $QRC_DATE   = '';
            $CUST_DESC  = '';
            $IR_CODE    = '';
            $ITM_NAME   = '';
            $GRP_CODE   = '';
            $JO_NUM     = '';
            $sqlSO      = "SELECT PRJCODE, ICOLL_CODE AS QRC_CODEV, ICOLL_CREATED AS QRC_DATE, CUST_DESC, ICOLL_REFNUM AS IR_CODE,
                                    ICOLL_NOTES AS ITM_NAME, ICOLL_CODE AS GRP_CODE, JO_NUM
                                FROM tbl_item_collh WHERE ICOLL_CODE = '$QRCode' LIMIT 1";
            $resSO      = $this->db->query($sqlSO)->result();
            foreach($resSO as $rowSO) :
                $PRJCODE    = $rowSO->PRJCODE;
                $QRC_CODEV  = $rowSO->QRC_CODEV;
                $QRC_DATE   = $rowSO->QRC_DATE;
                $CUST_DESC  = $rowSO->CUST_DESC;
                $IR_CODE    = $rowSO->IR_CODE;
                $ITM_NAME   = $rowSO->ITM_NAME;
                $GRP_CODE   = $rowSO->GRP_CODE;
                $JO_NUM     = $rowSO->JO_NUM;
            endforeach;

            $JO_CODE    = '';
            $JO_DESC    = '';
            $SO_NUM     = '';
            $SO_CODE    = '';
            $SO_DATEV   = '';
            $TOT_SOVOLM = 0;
            $BOM_CODE   = '';
            $ITM_CODE   = '';
            $BOM_NAME   = '';
            if($JO_NUM != '')
            {
                $sqlJO     = "SELECT A.JO_CODE, A.JO_DATE, A.JO_DESC, A.SO_NUM, A.SO_CODE, A.BOM_CODE, B.ITM_CODE, C.SO_DATE
                                FROM tbl_jo_header A
                                    INNER JOIN tbl_jo_detail B ON B.JO_NUM = A.JO_NUM
                                    INNER JOIN tbl_so_header C ON C.SO_NUM = A.SO_NUM
                                WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
                $resJO      = $this->db->query($sqlJO)->result();
                foreach($resJO as $rowJO) :
                    $JO_CODE    = $rowJO->JO_CODE;
                    $JO_DESC    = $rowJO->JO_DESC;
                    $JO_DATEV   = date('d-m-Y', strtotime($rowJO->JO_DATE));
                    $SO_NUM     = $rowJO->SO_NUM;
                    $SO_CODE    = $rowJO->SO_CODE;
                    $SO_DATEV   = date('d-m-Y', strtotime($rowJO->SO_DATE));
                    $BOM_CODE   = $rowJO->BOM_CODE;
                    $ITM_CODE   = $rowJO->ITM_CODE;
                endforeach;

                if($SO_NUM != '')
                {
                    $sqlSO      = "SELECT SUM(SO_VOLM) AS TOT_SOVOLM from tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
                    $resSO      = $this->db->query($sqlSO)->result();
                    foreach($resSO as $rowSO) :
                        $TOT_SOVOLM    = $rowSO->TOT_SOVOLM;
                    endforeach;
                }

                if($BOM_CODE != '')
                {
                    $sqlBOM     = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
                    $sqlBOM     = $this->db->query($sqlBOM)->result();
                    foreach($sqlBOM as $rowBOM) :
                        $BOM_NAME   = $rowBOM->BOM_NAME;
                    endforeach;                     
                }
            }
        }
        else if($QRCType == 'JOL')
        {
            $ISEXIST    = 1;
            $PRJCODE    = '';
            $ITM_CODE   = '';
            $IR_CODE    = '';
            $QRC_CODEV  = '';
            $QRC_DATE   = '';
            $CUST_DESC  = '';
            $IR_CODE    = '';
            $ITM_NAME   = '';
            $GRP_CODE   = '';
            $JO_NUM     = '';
            $JO_CODE    = '';
            $JO_DESC    = '';
            $SO_NUM     = '';
            $SO_CODE    = '';
            $SO_DATEV   = '';
            $TOT_SOVOLM = 0;
            $BOM_CODE   = '';
            $ITM_CODE   = '';
            $BOM_NAME   = '';

            $sqlJO      = "SELECT A.JO_NUM, A.JO_CODE, A.JO_DATE, A.JO_DESC, A.SO_NUM, A.SO_CODE, A.BOM_CODE, B.ITM_CODE, C.SO_DATE,
                                A.PRJCODE, A.JO_CODE AS QRC_CODEV, A.JO_DATE AS QRC_DATE, A.CUST_DESC, '' AS IR_CODE, '' AS GRP_CODE,
                                C.SO_DATE, A.BOM_CODE
                            FROM tbl_jo_header A
                                INNER JOIN tbl_jo_detail B ON B.JO_NUM = A.JO_NUM
                                INNER JOIN tbl_so_header C ON C.SO_NUM = A.SO_NUM
                            WHERE A.JO_UC = '$QRCode' LIMIT 1";
            $resJO      = $this->db->query($sqlJO)->result();
            foreach($resJO as $rowJO) :
                $PRJCODE    = $rowJO->PRJCODE;
                $QRC_CODEV  = $rowJO->QRC_CODEV;
                $QRC_DATE   = $rowJO->QRC_DATE;
                $CUST_DESC  = $rowJO->CUST_DESC;
                $IR_CODE    = $rowJO->IR_CODE;
                $ITM_NAME   = '';
                $GRP_CODE   = $rowJO->GRP_CODE;
                $JO_NUM     = $rowJO->JO_NUM;
                $JO_CODE    = $rowJO->JO_CODE;
                $JO_DESC    = $rowJO->JO_DESC;
                $JO_DATEV   = date('d-m-Y', strtotime($rowJO->JO_DATE));
                $SO_NUM     = $rowJO->SO_NUM;
                $SO_CODE    = $rowJO->SO_CODE;
                $SO_DATEV   = date('d-m-Y', strtotime($rowJO->SO_DATE));
                $BOM_CODE   = $rowJO->BOM_CODE;
                $ITM_CODE   = $rowJO->ITM_CODE;
            endforeach;

            if($SO_NUM != '')
            {
                $sqlSO      = "SELECT SUM(SO_VOLM) AS TOT_SOVOLM from tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
                $resSO      = $this->db->query($sqlSO)->result();
                foreach($resSO as $rowSO) :
                    $TOT_SOVOLM    = $rowSO->TOT_SOVOLM;
                endforeach;
            }

            if($BOM_CODE != '')
            {
                $sqlBOM     = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
                $sqlBOM     = $this->db->query($sqlBOM)->result();
                foreach($sqlBOM as $rowBOM) :
                    $BOM_NAME   = $rowBOM->BOM_NAME;
                endforeach;                     
            }
        }
    }
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <!-- <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/secttransfer.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php //echo $h1_title; ?>
    <small><?php //echo $PRJNAME1; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?> -->
</section>
<style>
.inplabel {border:none;background-color:white;}
.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
.inpdim {border:none;background-color:white;}
</style>
<!-- Main content -->
<section class="content">   
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border" style="display:none">               
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="container" id="QR-Code">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="form-control" id="camera-select" style="display:none"></select>
                                <div class="form-group" style="text-align:center">
                                    <input id="image-url" type="text" class="form-control" placeholder="Image url" style="display:none">
                                    <button title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip" style="display:none"><span class="glyphicon glyphicon-upload"></span></button>
                                    <button title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip" style="display:none"><span class="glyphicon glyphicon-picture"></span></button>
                                    <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play"></span></button>
                                    <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-pause"></span></button>
                                    <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop"></span></button>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-group" style="text-align:center">
                                    <div class="well" style="position: relative;display: inline-block;">
                                        <canvas style="max-width: 250px; max-height: 250px" id="webcodecam-canvas"></canvas>
                                        <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                                        <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                                        <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                                        <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $urlGetData = base_url().'index.php/y_5c4nQR/GetDataQRCSR/';
                        ?>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <form method="post" name="sendData" id="sendData" class="form-user" action="">
                                    <div class="form-group" style="text-align:center">
                                        <div class="thumbnail" id="result">
                                            <div class="well" style="overflow: hidden; display:none">
                                                <img width="150" height="150" id="scanned-img" src="">
                                            </div>
                                            <div class="caption">
                                                <p id="scanned-QR"></p>
                                                <input type="text" name="scanned-QRText" id="scanned-QRText" value="STW20102610215901002" width="250px" class="inplabel" style="display:none" >
                                                <input type="hidden" name="QRCType" id="QRCType" value="<?php echo $QRCType; ?>">
                                                <button type="button" class="btn btn-primary" id="btnOk" <?php if($collDATA != '') { ?> style="display: none;" <?php } ?>>
                                                    <i class="glyphicon glyphicon glyphicon-ok"></i>&nbsp;OK
                                                </button>
                                                <button class="btn btn-warning" id="btnShD" style="display: none;">
                                                    <i class="glyphicon glyphicon-leaf"></i>&nbsp;<?php echo $ShowDetail; ?>
                                                </button>
                                                <button class="btn btn-success" type="button" onClick="get_item();" id="btnSel" <?php if($collDATA == '') { ?> style="display: none;" <?php } ?>>
                                                <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                </button>
                                                <button class="btn btn-danger" type="button" onClick="window.close()">
                                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                </button>
                                                <input type="hidden" name="collDATA" id="collDATA" value="<?php echo $collDATA; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#btnOk").button().click(function()
                                    {
                                        var textField = $("#scanned-QRText").val();
                                        //swal(textField)

                                        if(textField == '')
                                        {
                                            swal('<?php echo $alert1; ?>');
                                            return false;
                                        }
                                            
                                        $.ajax({
                                            type: 'POST',
                                            url: '<?php echo $urlGetData; ?>',
                                            data: $('#sendData').serialize(),
                                            success: function(response)
                                            {
                                                // swal(response)
                                                // 1~QRCL~$PRJCODE~$CUST_CODE~$CUST_DESC~$SO_NUM~$SO_CODE~$SO_DATEV~$JO_NUM~$JO_CODE~$JO_DATEV~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$QRC_NUM~$QRC_CODEV~$QRC_VOLM~$BOM_CODE~$JO_NOTES
                                                var myarr   = response.split("~");
                                                var count   = Object.keys(myarr).length;
                                                
                                                if(count == 1)
                                                {
                                                    document.getElementById('usedQRC').style.display    = 'none';
                                                    document.getElementById('noQRC').style.display      = '';
                                                    document.getElementById('detInf').style.display     = 'none';
                                                    //document.getElementById('btnShD').style.display     = 'none';
                                                    document.getElementById('btnSel').style.display     = 'none';
                                                }
                                                else
                                                {
                                                    isExist     = myarr[0];

                                                    document.getElementById('QRCType').value            = myarr[1];
                                                    if(isExist == 1)
                                                    {
                                                        PRJCODE     = myarr[2];
                                                        CUST_CODE   = myarr[3];
                                                        CUST_DESC   = myarr[4];
                                                        SO_NUM      = myarr[5];
                                                        SO_CODE     = myarr[6];
                                                        SO_DATE     = myarr[7];
                                                        JO_NUM      = myarr[8];
                                                        JO_CODE     = myarr[9];
                                                        JO_DATE     = myarr[10];
                                                        ITM_CODE    = myarr[11];
                                                        ITM_NAME    = myarr[12];
                                                        ITM_UNIT    = myarr[13];
                                                        QRC_NUM     = myarr[14];
                                                        QRC_CODEV   = myarr[15];
                                                        QRC_VOLM    = myarr[16];
                                                        QRC_PRICE   = myarr[17];
                                                        BOM_CODE    = myarr[18];
                                                        BOM_NAME    = myarr[19];
                                                        JO_NOTES    = myarr[20];
                                                        SN_NUM      = myarr[21];
                                                        SN_CODE     = myarr[22];
                                                        SN_DATEV    = myarr[23];
                                                        CUST_ADD    = myarr[24];
                                                        SR_CODE     = myarr[25];
                                                        SR_DATE     = myarr[26];
                                                        SINVSTAT    = myarr[27];
                                                        SINVDET     = myarr[28];
                                                        QRC_VOLM    = doDecimalFormat(RoundNDecimal(QRC_VOLM, 2));

                                                        document.getElementById('noQRC').style.display  = 'none';
                                                        document.getElementById('detInf').style.display = '';
                                                        //document.getElementById('btnShD').style.display = '';
                                                        document.getElementById('btnSel').style.display = '';
                                                        document.getElementById('custName').innerHTML   = CUST_DESC;
                                                        document.getElementById('custAdd').innerHTML    = CUST_ADD;
                                                        document.getElementById('itemCode').innerHTML   = ITM_CODE +' ( '+ QRC_VOLM +' KG )';
                                                        document.getElementById('SNCode').innerHTML     = SN_CODE +' / '+ SN_DATEV;
                                                        document.getElementById('SOCode').innerHTML     = SO_CODE +' / '+ SO_DATE;
                                                        document.getElementById('BOMCode').innerHTML    = BOM_CODE;
                                                        if(JO_NOTES != '')
                                                        {
                                                            document.getElementById('JONotes').innerHTML    = JO_NOTES;
                                                        }
                                                        document.getElementById('QRC_VOLM').value       = QRC_VOLM;
                                                        document.getElementById('QRC_VOLM_RET').value   = QRC_VOLM;
                                                        document.getElementById('QRC_VOLM_RETX').value  = doDecimalFormat(RoundNDecimal(QRC_VOLM, 2));

                                                        collDATA    = QRC_NUM+'|'+JO_NUM+'|'+JO_CODE+'|'+QRC_CODEV+'|'+ITM_CODE+'|'+ITM_NAME+'|'+ITM_UNIT+'|'+QRC_VOLM+'|'+QRC_PRICE+'|'+SN_NUM+'|'+SN_CODE+'|'+SN_DATEV+'|'+CUST_CODE+'|'+BOM_NAME;
                                                        document.getElementById('collDATA').value       = collDATA;

                                                        if(SINVSTAT == 1)
                                                        {
                                                            //document.getElementById('btnSel').style.display         = 'none';
                                                            document.getElementById('detInf').style.display         = 'none';
                                                            document.getElementById('detIsInv').style.display       = '';
                                                            document.getElementById('sinvNo').innerHTML             = SINVDET;
                                                        }
                                                    }
                                                    else if(isExist == 2)
                                                    {
                                                        SR_CODE     = myarr[25];
                                                        SR_DATE     = myarr[26];

                                                        document.getElementById('usedQRC').style.display    = '';
                                                        document.getElementById('SRNom').innerHTML          = SR_CODE+' / '+SR_DATE;
                                                        document.getElementById('noQRC').style.display      = 'none';
                                                        document.getElementById('detInf').style.display     = 'none';
                                                        //document.getElementById('btnShD').style.display     = 'none';
                                                        document.getElementById('btnSel').style.display     = 'none';
                                                        document.getElementById('detIsInv').style.display   = 'none';
                                                    }
                                                    else
                                                    {
                                                        document.getElementById('usedQRC').style.display    = 'none';
                                                        document.getElementById('noQRC').style.display      = '';
                                                        document.getElementById('detInf').style.display     = 'none';
                                                        //document.getElementById('btnShD').style.display     = 'none';
                                                        document.getElementById('btnSel').style.display     = 'none';
                                                        document.getElementById('detIsInv').style.display   = 'none';
                                                    }
                                                }
                                            }
                                        });
                                    });
                                });

                                function get_item() 
                                {
                                    collData1       = document.getElementById('collDATA').value;
                                    QRC_VOLM_RET    = document.getElementById('QRC_VOLM_RET').value;
                                    collData2       = collData1+'|'+QRC_VOLM_RET;
                                    document.getElementById('collDATA').value   = collData2;

                                    window.opener.add_item(document.getElementById('collDATA').value);
                                    window.close();     
                                }
                            </script>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-widget widget-user-2">
                                            <div id="noQRC" class="widget-user-header bg-red" style="text-align:center; display: none;">
                                                <?php echo $alert3; ?>
                                            </div>
                                            <div id="usedQRC" class="widget-user-header bg-red" style="text-align:center; display: none;">
                                                <?php echo $alert4; ?> <div id="SRNom"></div>
                                            </div>
                                            <div id="detInf" class="widget-user-header bg-yellow" style="text-align:center">
                                                <?php echo $DetInfo; ?>
                                            </div>
                                            <div id="detIsInv" class="widget-user-header bg-red" style="text-align:center; display: none;">
                                                <?php echo $alert5; ?> <div id="sinvNo"></div>
                                            </div>
                                            <div class="box-footer no-padding">
                                                <ul class="nav nav-stacked">
                                                    <?php $ITM_QTYV   = number_format($ITM_QTY, 2); ?>
                                                    <li style="display: none;"><a href="#" onClick="return false"><?php echo $OwnerName; ?>
                                                        <span class="pull-right badge bg-blue">
                                                            <div id="compName"><?php echo $comp_name; ?></div>
                                                        </span></a>
                                                    </li>
                                                    <li><a href="#" onClick="return false"><?php echo $Sender; ?>
                                                        <span class="pull-right badge bg-blue">
                                                            <div id="custName"><?php echo $CUST_DESC; ?></div>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#" onClick="return false"><?php echo $Address; ?>
                                                        <span class="pull-right badge bg-red">
                                                            <div id="custAdd"><?php echo "$BOM_CODE / $BOM_NAME"; ?></div>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#" onClick="return false"><?php echo $ShipmentNo; ?>
                                                        <span class="pull-right badge bg-purple">
                                                            <div id="SNCode"><?php echo "$SN_CODE / $SN_DATEV"; ?></div>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#" onClick="return false"><?php echo $SOCode; ?>
                                                        <span class="pull-right badge bg-green">
                                                            <div id="SOCode"><?php echo "$SO_CODE / $SO_DATEV"; ?></div>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#" onClick="return false"><?php echo $ItemCode; ?>
                                                        <span class="pull-right badge bg-aqua">
                                                            <div id="itemCode">
                                                                <?php echo "$ITM_CODE - $QRC_PATT / $ITM_NAME ($ITM_QTYV $ITM_UNIT)"; ?>
                                                            </div>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li style="display: none;"><a href="#" onClick="return false"><?php echo $BOMCode; ?>
                                                        <span class="pull-right badge bg-red">
                                                            <div id="BOMCode"><?php echo "$BOM_CODE / $BOM_NAME"; ?></div>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#" onClick="return false"><?php echo $Description; ?></a>
                                                    </li>
                                                    <li><a href="#" onClick="return false">
                                                        <div class="alert alert-info alert-dismissible" id="JONotes">
                                                            <p><?php echo $JO_DESC; ?></p>
                                                        </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" onClick="return false">Qty  <?php echo $Received; ?></a>
                                                    </li>
                                                </ul>
                                                <input type="hidden" id="QRC_VOLM" name="QRC_VOLM" class="form-control" value="0.00">
                                                <input type="hidden" id="QRC_VOLM_RET" name="QRC_VOLM_RET" class="form-control" value="0.00">
                                                <input type="text" id="QRC_VOLM_RETX" name="QRC_VOLM_RETX" class="form-control" value="0.00" style="text-align: center;" onBlur="chgVolm(this);">
                                                <script type="text/javascript">
                                                    function chgVolm(thisVal1)
                                                    {
                                                        thisVal     = eval(thisVal1).value.split(",").join("");
                                                        val_ori     = parseFloat(document.getElementById('QRC_VOLM').value);
                                                        max_qty     = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(val_ori)), 2));
                                                        if(thisVal > val_ori)
                                                        {
                                                            swal('<?php echo $maxQty; ?>'+max_qty,
                                                            {
                                                                icon:"warning",
                                                            })
                                                            .then(function()
                                                            {
                                                                document.getElementById('QRC_VOLM_RETX').value  = doDecimalFormat(RoundNDecimal(max_qty, 2));
                                                                document.getElementById('QRC_VOLM_RET').value   = max_qty;
                                                                document.getElementById('QRC_VOLM_RET').focus();
                                                            })
                                                            thisVal     = max_qty;
                                                        }
                                                        document.getElementById('QRC_VOLM_RETX').value      = doDecimalFormat(RoundNDecimal(thisVal, 2));
                                                        document.getElementById('QRC_VOLM_RET').value       = thisVal;
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>


<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/filereader.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/qrcodelib.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/webcodecamjs.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/main.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
<script>
    var decFormat       = 2;
    
    function doDecimalFormat(angka) {
        var a, b, c, dec, i, j;
        angka = String(angka);
        if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
        } else { a = angka; dec = -1; }
        b = a.replace(/[^\d]/g,"");
        c = "";
        var panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--) {
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
            else c = b.substr(i-1,1) + c;
        }
        if(dec == -1) return angka;
        else return (c + '.' + dec);
    }
    
    function RoundNDecimal(X, N) {
        var T, S=new String(Math.round(X*Number("1e"+N)))
        while (S.length<=N) S='0'+S
        return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
    }
        
    function RoundNDecimal(X, N) {
        var T, S=new String(Math.round(X*Number("1e"+N)))
        while (S.length<=N) S='0'+S
        return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
    }
        
    function isIntOnlyNew(evt)
    {
        if (evt.which){ var charCode = evt.which; }
        else if(document.all && event.keyCode){ var charCode = event.keyCode; }
        else { return true; }
        return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
    }

    function decimalin(ini)
    {   
        var i, j;
        var bil2 = deletecommaperiod(ini.value,'both')
        var bil3 = ""
        j = 0
        for (i=bil2.length-1;i>=0;i--)
        {
            j = j + 1;
            if (j == 3)
            {
                bil3 = "." + bil3
            }
            else if ((j >= 6) && ((j % 3) == 0))
            {
                bil3 = "," + bil3
            }
            bil3 = bil2.charAt(i) + "" + bil3
        }
        ini.value = bil3
    }
    
    function deleteRow(btn)
    {
        var row = document.getElementById("tr_" + btn);
        row.remove();
    }
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_lnk like '%sweet%' AND cssjs_typ = 'js' AND isAct = 1";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;
?>