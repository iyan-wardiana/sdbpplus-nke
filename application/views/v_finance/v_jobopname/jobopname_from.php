<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2017
 * File Name	= jobopname_from.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$sqlAUTH		= "SELECT ISREAD, ISCREATE, ISAPPROVE, ISDELETE, ISDWONL
					FROM tusermenu WHERE emp_id = '$DefEmp_ID' AND menu_code = '$MenuCode'";
$resAUTH 		= $this->db->query($sqlAUTH)->result();
foreach($resAUTH as $rowAUTH) :
	$ISREAD 	= $rowAUTH->ISREAD;
	$ISCREATE 	= $rowAUTH->ISCREATE;
	$ISAPPROVE 	= $rowAUTH->ISAPPROVE;
	$ISDELETE	= $rowAUTH->ISDELETE;
	$ISDWONL	= $rowAUTH->ISDWONL;
endforeach;

if($ISDELETE == 1)
{
	$ISREAD		= 1;
	$ISCREATE	= 1;
	$ISAPPROVE	= 1;
	$ISDWONL	= 1;
}
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$LangID 	= $this->session->userdata['LangID'];

$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl		= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;
	if($TranslCode == 'Add')$Add = $LangTransl;
	if($TranslCode == 'Edit')$Edit = $LangTransl;
	if($TranslCode == 'SPKCode')$SPKCode = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
	if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'SPKCost')$SPKCost = $LangTransl;
	if($TranslCode == 'PrintOpname')$PrintOpname = $LangTransl;
	if($TranslCode == 'Print')$Print = $LangTransl;
	if($TranslCode == 'Back')$Back = $LangTransl;
	if($TranslCode == 'Save')$Save = $LangTransl;
	if($TranslCode == 'Mandor')$Mandor = $LangTransl;
	if($TranslCode == 'Address')$Address = $LangTransl;
	if($TranslCode == 'StartDate')$StartDate = $LangTransl;
	if($TranslCode == 'EndDate')$EndDate = $LangTransl;
	if($TranslCode == 'StartPeriod')$StartPeriod = $LangTransl;
	if($TranslCode == 'EndPeriod')$EndPeriod = $LangTransl;
	if($TranslCode == 'Close')$Close = $LangTransl;
	if($TranslCode == 'OPNStep')$OPNStep = $LangTransl;
endforeach;
$START_PER	= date("m/d/Y");
$END_PER	= date("m/d/Y");

$sqlOPStep	 = "tbl_jobopname WHERE SPKCODE = '$SPKCODE'";
$cOPStep 	= $this->db->count_all($sqlOPStep);
if($cOPStep == 0)
{
	$nextOPStep = 1;
}
else
{
	$qGetMaxOPStep	= "SELECT MAX(OPSTEP) AS MaxOPSTEP FROM tbl_jobopname WHERE SPKCODE = '$SPKCODE'";
	$resultOPStep 	= $this->db->query($qGetMaxOPStep)->result();
	foreach($resultOPStep as $rowOPStep) :
		$MaxOPSTEP = $rowOPStep->MaxOPSTEP;
	endforeach;
	$nextOPStep = $MaxOPSTEP + 1;
}

// Start --- Get SPK Header
$qGetSPKCode	= "SELECT A.*, B.PRJNAME, B.PRJLOCT
					FROM opn_spkhd A 
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE SPKCODE = '$SPKCODE'";
$resultHD = $this->db->query($qGetSPKCode)->result();
foreach($resultHD as $rowHD) :
	$SPKCODE = $rowHD->SPKCODE;
	$SPKTYPE = $rowHD->SPKTYPE;
	$TRXDATE = $rowHD->TRXDATE;
	$PRJCODE = $rowHD->PRJCODE;
	$PRJNAME = $rowHD->PRJNAME;
	$PRJLOCT = $rowHD->PRJLOCT;
	$SPLCODE = $rowHD->SPLCODE;
	$SPKCOST = $rowHD->SPKCOST;
	$SPKPPNH = $rowHD->SPKPPNH;
	$TRXCOMP = $rowHD->TRXCOMP;
	$TRXPOST = $rowHD->TRXPOST;
	$TRXPDAT = $rowHD->TRXPDAT;
	$TRXOPEN = $rowHD->TRXOPEN;
	$TRXUSER = $rowHD->TRXUSER;
	$DP_CODE = $rowHD->DP_CODE;
	$DP_PPN_ = $rowHD->DP_PPN_;
	$DP_JUML = $rowHD->DP_JUML;
	$DP_JPPN = $rowHD->DP_JPPN;
endforeach;
$TRXDATE1 	= date('Y/m/d',strtotime($TRXDATE));
$TRXDATE1	= date("m/d/Y",strtotime($TRXDATE));
$TRXPDAT1 	= date('Y/m/d',strtotime($TRXPDAT));
$TRXPDAT1	= date("m/d/Y",strtotime($TRXPDAT));

if($LangID == 'IND')
{
	$AlertD1		= "Quantity Opname melebihi Qty SPK. Sisa = ";
	$AlertD2	= "Quantity Opname tidak boleh kosong.";
}
else
{
	$AlertD1	= "Opname Quantity exceeds the SPK Qty. Remain = ";
	$AlertD2	= "Quantity can not be empty.";
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<body class="hold-transition skin-blue sidebar-mini">
<div class="box-body">
    <div class="callout callout-success">
        <h4><?php echo $title; ?></h4> 
        <p>Please input data correctly.</p>
    </div>
    <div class="box-body chart-responsive">
        <form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return checkForm1()">
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $OPNStep; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nextOPStep" id="nextOPStep" style="max-width:50px" value="<?php echo $nextOPStep; ?>" disabled />
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $SPKCode; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="SPKCODE" id="SPKCODE" style="max-width:150px" value="<?php echo $SPKCODE; ?>" disabled />
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                <div class="col-sm-10">
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                        <input type="text" name="TRXDATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $TRXDATE1; ?>" style="width:100px">
                    </div>
                </div>
            </div>
            <?php
				// Start --- Get SPK Header
				$SPLDESC	= '';
				$SPLADD1	= '';
				$SPLADD2	= '';
				$SPLTELP	= '';
				$sqlsp1	= "tbl_supplier WHERE SPLCODE = '$SPLCODE'";
				$sqlsp1R= $this->db->count_all($sqlsp1);
				if($sqlsp1R > 0)
				{
					$sqlsp2		= "SELECT SPLDESC, SPLADD1, SPLADD2, SPLTELP FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
					$sqlsp2R	= $this->db->query($sqlsp2)->result();
					foreach($sqlsp2R as $rowsp2) :
						$SPLDESC	= $rowsp2->SPLDESC;
						$SPLADD1	= $rowsp2->SPLADD1;
						$SPLADD2	= $rowsp2->SPLADD2;
						$SPLTELP	= $rowsp2->SPLTELP;
					endforeach;
				}
				
				//$sqlCount	= "opn_spkdt2 WHERE SPKCODE = '$SPKCODE'";
				//$mysqlCount = $this->db->count_all($sqlCount);
			?>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                <div class="col-sm-10">
                	<input type="text" class="form-control" name="PRJCODE" id="PRJCODE" style="max-width:250px" value="<?php echo "$PRJCODE - $PRJNAME"; ?>" disabled />
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $Address ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="PRJLOCT" id="PRJLOCT" style="max-width:250px" value="<?php echo $PRJLOCT; ?>" disabled />
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $Mandor; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="SPLCODE" id="SPLCODE" style="max-width:250px" value="<?php echo "$SPLCODE - $SPLDESC"; ?>" disabled />
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $Address; ?></label>
                <div class="col-sm-10">
                    <?php
					$SPLADD1A	= $SPLADD1;
					if($SPLADD2!='')
					{ 
						$SPLADD1A	= "$SPLADD1A. $SPLADD2 - $SPLKOTA";
					} 
					if($SPLTELP != '')
					{
						$SPLADD1A	= "$SPLADD1A. TLP. $SPLTELP";
					} 
					?>
                    <input type="text" class="form-control" name="SPLCODE" id="SPLCODE" style="max-width:350px" value="<?php echo "$SPLADD1A"; ?>" disabled />
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?> SPK</label>
                <div class="col-sm-10">
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                        <input type="text" name="TRXDATE1A" class="form-control pull-left" id="datepicker2" value="<?php echo $TRXDATE1; ?>" style="width:100px">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?> SPK</label>
                <div class="col-sm-10">
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                        <input type="text" name="TRXPDAT1" class="form-control pull-left" id="datepicker3" value="<?php echo $TRXPDAT1; ?>" style="width:100px">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $StartPeriod; ?></label>
                <div class="col-sm-10">
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                        <input type="text" name="START_PER" class="form-control pull-left" id="datepicker4" value="<?php echo $START_PER; ?>" style="width:100px">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $EndPeriod; ?></label>
                <div class="col-sm-10">
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                        <input type="text" name="END_PER" class="form-control pull-left" id="datepicker5" value="<?php echo $END_PER; ?>" style="width:100px">
                    </div>
                </div>
            </div>
            <table width="100%" border="1" rules="all" id="tbl" >
                <input type="hidden" name="mySPKCode" id="mySPKCode" value="<?php echo $SPKCODE; ?>" />
                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                <tr style="font-size:12px; background:#CCCCCC">
                    <th width="2%" rowspan="2" style="text-align:center; font-family:Times New Roman" nowrap>No.</th>
                    <th width="7%" rowspan="2" style="text-align:center; font-family:Times New Roman" nowrap>Kode Item</th>
                    <th width="30%" rowspan="2" nowrap style="text-align:center; font-family:Times New Roman">Item Pekerjaan</th>
                    <th width="11%" rowspan="2" nowrap style="text-align:center; font-family:Times New Roman">Volume SPK</th>
                    <th width="8%" rowspan="2" style="text-align:center; font-family:Times New Roman">Harga SPK</th>
                    <th colspan="2" style="text-align:center; font-family:Times New Roman">Kumulatif Opname Sebelumnya</th>
                    <th colspan="2" style="text-align:center; font-family:Times New Roman">Opname Input</th>
                    <th colspan="2" style="text-align:center; font-family:Times New Roman">Total Opname</th>
                </tr>
                <tr style="font-size:12px; background:#CCCCCC"">
                    <td width="8%" style="text-align:center; font-family:Times New Roman">Volume</td>
                    <td width="10%" style="text-align:center; font-family:Times New Roman">Total Harga</td>
                    <td width="5%" nowrap style="text-align:center; font-family:Times New Roman">Volume</td>
                    <td width="7%" style="text-align:center; font-family:Times New Roman">Total Harga</td>
                    <td width="5%" style="text-align:center; font-family:Times New Roman">Volume</td>
                    <td width="7%" style="text-align:center; font-family:Times New Roman">Harga</td>
                </tr>
                <?php
                    // Start --- Get SPK Detail
                    $i = 0;
                    $totSPK = 0;
                    $totMaxRow = 32; 
                    $sqlR	= "opn_spkdt2 WHERE SPKCODE = '$SPKCODE'";
                    $qtyRow = $this->db->count_all($sqlR);

                    $remLoop = 32 - $qtyRow;
                    
                    $qGetSPKDet	= "SELECT A.*
                                        FROM opn_spkdt2 A INNER JOIN SPKHD B ON A.SPKCODE = B.SPKCODE
                                        WHERE A.SPKCODE = '$SPKCODE'";
                    $resultDT = $this->db->query($qGetSPKDet)->result();
                    foreach($resultDT as $rowDT) :
                        $myNewNo1 = ++$i;
                        $SPKCODE = $rowDT->SPKCODE;
                        $CSTCODE = $rowDT->CSTCODE;
                        $CSTUNIT = $rowDT->CSTUNIT;
                        $SPKVOLM = $rowDT->SPKVOLM;
                        $SPKVPRS = $rowDT->SPKVPRS;
                        $CSTPUNT = $rowDT->CSTPUNT;
                        $CSTCOST = $rowDT->CSTCOST;
                        $SPKDESC = $rowDT->SPKDESC;
                        $totSPK = $totSPK + $CSTCOST;
                        
                        $OTHEREXP	= 0;
                        $OTHEREXPTH = 0;
                        ?>
                        <tr style="font-size:12px;">
                            <td style="text-align:center; font-family:Times New Roman" nowrap><?php echo $myNewNo1; ?>.</td>
                            <td style="text-align:left; font-family:Times New Roman">
                            <input type="text" name="SPKCODE" id="SPKCODE" value="<?php echo $SPKCODE; ?>" size="6" style="display:none" />
                            <input type="text" name="CSTCODE" id="CSTCODE" value="<?php echo $CSTCODE; ?>" size="6" style="display:none" />&nbsp;<?php echo $CSTCODE; ?></td>
                            <td style="text-align:left; font-family:Times New Roman" nowrap>&nbsp;<?php echo $SPKDESC; ?></td>
                            <td style="text-align:right; font-family:Times New Roman" nowrap>
                                <input type="text" style="display:none" name="SPKVOLM_<?php echo $myNewNo1; ?>" id="SPKVOLM_<?php echo $myNewNo1; ?>" value="<?php echo $SPKVOLM; ?>" size="8" />
                                <input type="text" style="display:none" name="CSTPUNT_<?php echo $myNewNo1; ?>" id="CSTPUNT_<?php echo $myNewNo1; ?>" value="<?php echo $CSTPUNT; ?>" size="8" />
                                &nbsp;<?php print number_format($SPKVOLM, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-family:Times New Roman" nowrap>
                            <input type="text" style="display:none" name="CSTCOST_<?php echo $myNewNo1; ?>" id="CSTCOST_<?php echo $myNewNo1; ?>" value="<?php echo $CSTCOST; ?>" size="10" />
                           &nbsp; <?php print number_format($CSTCOST, $decFormat); ?>&nbsp;</td>
                            <?php
								// GET AKUMULATIF JOB OPNAME
                                $SPKVOLM2 		= 0;
                                $TOTHRG2 		= 0;
                                $oth_ItemSPK 	= "oth_$SPKCODE";
                                $qGetAkVol		= "SELECT SUM(OPNVOLM) AS OPNVOLM1, SUM(OPNTOTHRG) AS OPNTOTHRG1 
													FROM tbl_jobopname
                                                	WHERE SPKCODE = '$SPKCODE' AND CSTCODE = '$CSTCODE' AND OPSTEP < '$nextOPStep'";
                                
                                $resultAkVol = $this->db->query($qGetAkVol)->result();
                                foreach($resultAkVol as $rowAkVol) :
                                    $OPNVOLM1	= $rowAkVol->OPNVOLM1;
                                    if($OPNVOLM1 == '')
                                    {
                                        $OPNVOLM1 = 0;
                                    }
                                    $OPNTOTHRG1 	= $rowAkVol->OPNTOTHRG1;
                                    if($OPNTOTHRG1 == '')
                                    {
                                        $OPNTOTHRG1 = 0;
                                    }
                                endforeach;
                            ?>
                            <td style="text-align:right; font-family:Times New Roman" nowrap>
                                <input type="text" style="display:none" name="AkumVol_<?php echo $myNewNo1; ?>" id="AkumVol_<?php echo $myNewNo1; ?>" value="<?php echo $OPNVOLM1; ?>" size="8" />
                                &nbsp;<?php print number_format($OPNVOLM1, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-family:Times New Roman" nowrap>
                                <input type="text" style="display:none" name="AkumHrg_<?php echo $myNewNo1; ?>" id="AkumHrg_<?php echo $myNewNo1; ?>" value="<?php echo $OPNTOTHRG1; ?>" size="8" />
                               &nbsp; <?php print number_format($OPNTOTHRG1, $decFormat); ?>&nbsp;</td>
                            <td style="text-align:right; font-family:Times New Roman">
                                <input type="hidden" name="volQty[<?php echo $myNewNo1; ?>]" id="volQty_<?php echo $myNewNo1; ?>" size="8" maxlength="12" value="" style="text-align:right" />
                                <input type="text" name="volQty1[<?php echo $myNewNo1; ?>]" id="volQty1_<?php echo $myNewNo1; ?>" size="8" maxlength="12" value="" style="text-align:right" onBlur="countVolQty(<?php echo $myNewNo1; ?>);" onKeyPress="return isNumber(event)" />
                            </td>
                            <td style="text-align:right; font-family:Times New Roman">
                                <input type="hidden" name="opnHrg[<?php echo $myNewNo1; ?>]" id="opnHrg_<?php echo $myNewNo1; ?>" size="12" maxlength="12" value="" style="text-align:right" />
                                <input type="text" name="opnHrg1[<?php echo $myNewNo1; ?>]" id="opnHrg1_<?php echo $myNewNo1; ?>" size="12" maxlength="12" value="" style="text-align:right" onBlur="countHrgQty(<?php echo $myNewNo1; ?>);" onKeyPress="return isNumber(event)" />
                            </td>
                            <td style="text-align:left; font-family:Times New Roman">
                                <input type="hidden" class="inplabel" name="TotVolQty[<?php echo $myNewNo1; ?>]" id="TotVolQty_<?php echo $myNewNo1; ?>" value="" style="text-align:right" />
                                <input type="text" class="inplabel" name="TotVolQty1[<?php echo $myNewNo1; ?>]" id="TotVolQty1_<?php echo $myNewNo1; ?>" size="9" maxlength="20" value="" style="text-align:right" />
                            </td>
                            <td style="text-align:left; font-family:Times New Roman">
                                <input type="hidden"  class="inplabel"name="TotopnHrg[<?php echo $myNewNo1; ?>]" id="TotopnHrg_<?php echo $myNewNo1; ?>" maxlength="20" value="" style="text-align:right" />
                                <input type="text"  class="inplabel"name="TotopnHrg1[<?php echo $myNewNo1; ?>]" id="TotopnHrg1_<?php echo $myNewNo1; ?>" size="12" maxlength="20" value="" style="text-align:right" />
                                <input type="hidden" name="totrow" id="totrow" value="<?php echo $myNewNo1; ?>">
                            </td>
                        </tr>
                    <?php
                    endforeach;
                
                    $CSTCODEDESC = '';
                    $OTHEREXP2 = 0;
                    $OTHEREXPTH2 = 0;
                    $qGetOthExp	= "SELECT * FROM tbl_jobopname
                                    WHERE SPKCODE = '$SPKCODE' AND CSTCODE = '$oth_ItemSPK'";
                    $resultOExp = $this->db->query($qGetOthExp)->result();
                    foreach($resultOExp as $rowOExp) :
                        $CSTCODEDESC	= $rowOExp->CSTCODEDESC;
                        $OTHEREXP1		= $rowOExp->OTHEREXP;
                        $OTHEREXP2 		= $OTHEREXP2 + $OTHEREXP1;
                        $OTHEREXPTH1	= $rowOExp->OTHEREXPTH;
                        $OTHEREXPTH2	= $OTHEREXPTH2 + $OTHEREXPTH1;
                    endforeach;
                ?>
  		  	</table>
            <br>
            <div class="form-group">
                <?php 
                    if($ISCREATE == 1)
                    {
                        ?>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary" name="submitAddOpn" onClick="checkForm1()">
                                <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                            </button>&nbsp;&nbsp;
                            <button class="btn btn-danger" type="button" onClick="window.close();">
                                <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                            </button>
                        </div>
                        <?php
                    }
					else
					{
                        ?>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-danger" type="button" onClick="window.close();">
                                <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                            </button>
                        </div>
                        <?php
					}
                ?>
            </div>
        </form>
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
<?php
if (isset($_POST['submitAddOpn'])) 
{
	$myRow		= 0;
	$qGetDetSPK	= "SELECT SPKCODE, CSTCODE, CSTUNIT, SPKVOLM, CSTPUNT, CSTCOST, SPKDESC
						FROM opn_spkdt
						WHERE SPKCODE = '$SPKCODE'";
	$resDetSPK 	= $this->db->query($qGetDetSPK)->result();
	
	$volQty		= $_POST['volQty'];
	$opnHrg		= $_POST['opnHrg'];
	$STRDATE	= date('Y-m-d',strtotime($this->input->post('START_PER')));
	$ENDDATE	= date('Y-m-d',strtotime($this->input->post('END_PER')));

	foreach($resDetSPK as $rowDetSPK) :
		$myRow		=  $myRow+1;
		$SPKCODE	= $rowDetSPK->SPKCODE;
		$CSTCODE	= $rowDetSPK->CSTCODE;
		$CSTUNIT 	= $rowDetSPK->CSTUNIT;
		$SPKVOLM	= $rowDetSPK->SPKVOLM;
		$CSTPUNT	= $rowDetSPK->CSTPUNT;
		$CSTCOST	= $rowDetSPK->CSTCOST;
		$SPKDESC	= $rowDetSPK->SPKDESC;
		
		$data = array(
			   'SPKCODE' 	=> $SPKCODE, 
			   'PRJCODE' 	=> $PRJCODE, 
			   'OPSTEP' 	=> $nextOPStep,
			   'STRDATE' 	=> $STRDATE,
			   'ENDDATE' 	=> $ENDDATE,
			   'CSTCODE' 	=> $CSTCODE,
			   'CSTUNIT' 	=> $CSTUNIT,
			   'SPKVOLM' 	=> $SPKVOLM,
			   'TOTHRG' 	=> $CSTCOST,
			   'CSTCODEDESC' => $SPKDESC,
			   'OPNVOLM' 	=> $volQty[$myRow],
			   'OPNTOTHRG'	=> $opnHrg[$myRow]		
			   );
		$this->db->insert('tbl_jobopname', $data); 
	endforeach;

	echo "
	<script>
		window.close();
	</script>";
}
?>
<script>
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
	
    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker4').datepicker({
      autoclose: true
    });
	
	function countVolQty(valRow)
	{
		decFormat	= document.getElementById('decFormat').value;
		volSPK 		= parseFloat(document.getElementById('SPKVOLM_'+valRow).value);
		CSTPUNT		= parseFloat(document.getElementById('CSTPUNT_'+valRow).value);
		
		// Total Akumulasi Qty dan Harga Opname sebelumnya
		TotOpnQty	= parseFloat(document.getElementById('AkumVol_'+valRow).value);
		TotOpnHrg	= parseFloat(document.getElementById('AkumHrg_'+valRow).value);
		if(TotOpnQty == '')
		{
			TotOpnQty = 0;
		}
		if(TotOpnHrg == '')
		{
			TotOpnHrg = 0;
		}
		
		// Opname Qty - NOW
		TotOpnQtyIn 	= parseFloat(document.getElementById('volQty1_'+valRow).value);
		TotOpnQtyINan	= isNaN(parseFloat(document.getElementById('volQty1_'+valRow).value));
		if(TotOpnQtyIn == '' || TotOpnQtyINan == true)
		{
			TotOpnQtyIn = 0;
		}
		
		// Opname Price - NOW		
		TotOpnHrgIn		= TotOpnQtyIn * CSTPUNT;
		document.getElementById('opnHrg_'+valRow).value = TotOpnHrgIn;
		
		// Syarat TotOpnQty + TotOpnQtyIn <= volSPK
		TotOpnQtyN	= parseFloat(TotOpnQty) + parseFloat(TotOpnQtyIn);
		TotOpnHrgN	= parseFloat(TotOpnHrg) + parseFloat(TotOpnHrgIn);
		sisaVol		= parseFloat(volSPK) - parseFloat(TotOpnQty);
		if(TotOpnQtyN > volSPK)
		{
			alert('<?php echo $AlertD1; ?>'+sisaVol);
			// Volume OPN Now
			OpnNowQty	= parseFloat(sisaVol);
			OpnNowHrg	= parseFloat(sisaVol * CSTPUNT);
			document.getElementById('volQty_'+valRow).value		= OpnNowQty;
			document.getElementById('volQty1_'+valRow).value	= doDecimalFormat(RoundNDecimal(parseFloat(OpnNowQty),decFormat));
			document.getElementById('opnHrg_'+valRow).value		= OpnNowHrg;
			document.getElementById('opnHrg1_'+valRow).value	= doDecimalFormat(RoundNDecimal(parseFloat(OpnNowHrg),decFormat));
			// Volume OPN Akumulate
			totOpnQty	= parseFloat(sisaVol) + parseFloat(TotOpnQty);
			totOpnHrg	= parseFloat(totOpnQty * CSTPUNT);
			document.getElementById('TotVolQty_'+valRow).value 	= totOp;
			document.getElementById('TotVolQty1_'+valRow).value = doDecimalFormat(RoundNDecimal(parseFloat(totOp),decFormat));
			document.getElementById('TotopnHrg_'+valRow).value 	= totOpnHrg;
			document.getElementById('TotopnHrg1_'+valRow).value = doDecimalFormat(RoundNDecimal(parseFloat(totOpnHrg),decFormat));
		}
		else
		{
			// Volume OPN Now
			document.getElementById('volQty_'+valRow).value		= TotOpnQtyIn;
			document.getElementById('volQty1_'+valRow).value	= doDecimalFormat(RoundNDecimal(parseFloat(TotOpnQtyIn),decFormat));
			document.getElementById('opnHrg_'+valRow).value		= TotOpnHrgIn;
			document.getElementById('opnHrg1_'+valRow).value	= doDecimalFormat(RoundNDecimal(parseFloat(TotOpnHrgIn),decFormat));
			
			// Volume OPN Akumulate
			document.getElementById('TotVolQty_'+valRow).value 	= TotOpnQtyN;
			document.getElementById('TotVolQty1_'+valRow).value = doDecimalFormat(RoundNDecimal(parseFloat(TotOpnQtyN),decFormat));
			document.getElementById('TotopnHrg_'+valRow).value 	= TotOpnHrgN;
			document.getElementById('TotopnHrg1_'+valRow).value = doDecimalFormat(RoundNDecimal(parseFloat(TotOpnHrgN),decFormat));
		}
	}
		
	function checkForm1()
	{
		var totrow	= document.getElementById('totrow').value;
		for(var i=1; i<= totrow; i++)
		{
			var volQty_	= document.getElementById('volQty_'+i).value;
			if(volQty_ == '' || volQty_ == 0)
			{
				alert('<?php echo $AlertD2; ?>');
				document.getElementById('volQty1_'+i).focus();
				return false;
			}
		}
		//document.forms["frm"].submit();
		var x = document.getElementsByTagName("form");
		x[0].submit();
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>