<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 April 2019
 * File Name	= v_prodprocess_selQRC.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

function cut_text($var, $len = 200, $txt_titik = "...") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
	if (strlen ($var) < $len) 
	{ 
		return $var; 
	}
	if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
	{
		return $match [1] . $txt_titik;
	}
	else
	{
		return substr ($var, 0, $len) . $txt_titik;
	}
}
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

<?php
	$LangID 		= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'Remain')$Remain = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'ProductionVolume')$ProductionVolume = $LangTransl;
		if($TranslCode == 'Substitute')$Substitute = $LangTransl;
		if($TranslCode == 'QRCList')$QRCList = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Process')$Process = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
	endforeach;
	$ISCREATE	= 1;

	if(isset($_POST['submit1']))
	{
		$List_Type 		= $this->input->post('List_Type');
		if($List_Type == 1)
		{
			$Active1		= "active";
			$Active2		= "";
			$Active1Cls		= "class='active'";
			$Active2Cls		= "";
		
			$cDataItm		= $this->db->count_all("tbl_item WHERE PRJCODE = '$PRJCODE' AND (ISRM = 1 OR ISWIP = 1) AND STATUS = 1");
		}
		else
		{
			$Active1		= "";
			$Active2		= "active";
			$Active1Cls		= "";
			$Active2Cls		= "class='active'";
		
			$cDataItm		= $this->db->count_all("tbl_item WHERE PRJCODE = '$PRJCODE' AND (ISRM = 1 OR ISWIP = 1) AND STATUS = 1");
		}
	}
	else
	{
		$List_Type		= 1;
		$Active1		= "active";
		$Active2		= "";
		$Active1Cls		= "class='active'";
		$Active2Cls		= "";
	}
	
	$ITM_NAME	= '';
	$sqlITM	= "SELECT ITM_NAME FROM tbl_item 
				WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
	$resITM	= $this->db->query($sqlITM)->result();
	foreach($resITM as $rowITM) :
		$ITM_NAME	= $rowITM->ITM_NAME;
	endforeach;
	
	$JO_CODE	= '';
	$sqlJO	= "SELECT JO_CODE FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
	$resJO	= $this->db->query($sqlJO)->result();
	foreach($resJO as $rowJO) :
		$JO_CODE	= $rowJO->JO_CODE;
	endforeach;
	
	if($LangID == 'IND')
	{
		$alert0	= "Silahkan pilih Kode QR pada kolom sebelah kanan.";
	}
	else
	{
		$alert0	= "Please select the QR Code in the right side.";
	}
	
	if(isset($_POST['btnSubmit']))
	{
		// DELETE ALL DATA PER QRC_NUM
		$sqlDEL		= "DELETE FROM tbl_jo_stfdetail_qrc 
						WHERE PRJCODE = '$PRJCODE' AND JOSTF_STEP = '$PRODS_STEP' AND JO_NUM = '$JO_NUM'
							AND ITM_CODE = '$ITM_CODE' AND JOSTF_CAT = 'IN'";
		$this->db->query($sqlDEL);

		$INP_CAT	= $_POST['INP_CAT'];
		
		if($INP_CAT == 1)
		{
			$packelementsPRJ	= $_POST['packageelements'];
			if (count($packelementsPRJ) > 0)
			{
				$mySelQRC = $_POST['packageelements'];
				foreach ($mySelQRC as $QRC_NUM)
				{
					$sqlITMQRC	= "SELECT IR_NUM, IR_CODE, ITM_CODE, ITM_NAME, QRC_PATT FROM tbl_qrc_detail 
									WHERE PRJCODE = '$PRJCODE' AND QRC_NUM = '$QRC_NUM' LIMIT 1";
					$resITMQRC	= $this->db->query($sqlITMQRC)->result();
					foreach($resITMQRC as $rowITMQRC) :
						$IRNUM		= $rowITMQRC->IR_NUM;
						$IRCODE		= $rowITMQRC->IR_CODE;
						$ITMCODE	= $rowITMQRC->ITM_CODE;
						$ITMNAME	= $rowITMQRC->ITM_NAME;
						$QRCPATT	= $rowITMQRC->QRC_PATT;
					endforeach;
					
					// INSERT AGAIN
					$insQRC	= "INSERT INTO tbl_jo_stfdetail_qrc (PRJCODE, JOSTF_NUM, JO_NUM, JOSTF_STEP, JOSTF_CAT, 
								QRC_NUM, IR_NUM, IR_CODE, ITM_CODE, ITM_NAME, QRC_PATT)
								VALUES 
								('$PRJCODE', '$JOSTF_NUM', '$JO_NUM', '$PRODS_STEP', 'IN', '$QRC_NUM', 
								'$IRNUM', '$IRCODE', '$ITMCODE', '$ITMNAME', '$QRCPATT')";
					$this->db->query($insQRC);

					// UPDATE QRCODE 9 = ID PENGGUNAAN SEMENTARA
					$updQRC	= "UPDATE tbl_qrc_detail SET QRC_STAT = 9 WHERE QRC_NUM = '$QRC_NUM'";
					$this->db->query($updQRC);
				}
			}
		}
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
    <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
</form>
<section class="content">
	<div class="row">
    	 <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active1Cls; ?>><a href="#grpList" data-toggle="tab" onClick="setType(1)">Kelompok Kain</a></li>
                    <li <?php echo $Active2Cls; ?>><a href="#whqty" data-toggle="tab" onClick="setType(2)" style="display: none;"><?php echo $Substitute; ?></a></li> 		<!-- Tab 1 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<?php
						if($List_Type == 1)
						{
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="grpList">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                            <table id="tree-table" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="4%" style="text-align:center">&nbsp;</th>
                                                        <th width="4%" style="text-align:center">Kode</th>
                                                        <th width="10%" style="text-align:center" nowrap><?php echo $ItemCode; ?></th>
                                                        <th width="31%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                                        <th width="55%" style="text-align:center" nowrap><?php echo $QRCList; ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $l = 0;
													$sqlItm	= "SELECT A.ICOLL_NUM, A.ICOLL_CODE, A.CUST_CODE, A.CUST_DESC, A.ICOLL_NOTES,
																	A.ICOLL_FG, B.ITM_NAME, B.ITM_UNIT, A.ICOLL_REFNUM
																FROM tbl_item_collh A
																	INNER JOIN tbl_item B ON A.ICOLL_FG = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																 WHERE A.ICOLL_STAT = 2";
													$resItm	= $this->db->query($sqlItm)->result();
													$totRow	= 0;
													foreach($resItm as $row) :								
														$ICOLL_NUM 		= $row->ICOLL_NUM;
														$ICOLL_CODE		= $row->ICOLL_CODE;
														$CUST_CODE 		= $row->CUST_CODE;
														$CUST_DESC 		= $row->CUST_DESC;
														$ICOLL_FG 		= $row->ICOLL_FG;
														$ITM_NAME1 		= $row->ITM_NAME;
														$ITM_NAME 		= $row->ICOLL_NOTES;
														$ITM_UNIT 		= $row->ITM_UNIT;
														$ICOLL_REFNUM	= $row->ICOLL_REFNUM;
														
														$secUpd			= site_url(''.$this->url_encryption_helper->encode_url($ICOLL_NUM));
															
														$totRow			= $totRow + 1;

														$ITM_CODE		= $ICOLL_CODE;
														$ITM_GROUP		= 'M';
														$ITM_VOLM		= 1;
														$REM_QTY		= 1;
														$ITM_PRICE		= 0;
														$ACC_ID 		= '';
														$ACC_ID_UM 		= '';
														$ITM_STOCK		= 1;
														$NEEDQRC		= 1;
														$JO_QTY			= 0;
														$SOURCE			= '';
														$ITM_CODE1		= $ICOLL_CODE;
														
														if ($l==1) {
															echo "<tr class=zebra1>";
															$l++;
														} else {
															echo "<tr class=zebra2>";
															$l--;
														}
														?>
															<td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_GROUP;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_VOLM;?>|<?php echo $REM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ACC_ID;?>|<?php echo $ACC_ID_UM;?>|<?php echo $ITM_STOCK;?>|<?php echo $PRODS_STEP;?>|<?php echo $NEEDQRC;?>|<?php echo $JO_QTY;?>|<?php echo $SOURCE;?>|<?php echo $ITM_CODE1;?>|<?php echo $SELROW;?>" onClick="pickThis(this);" /></td>
															<td nowrap><?php echo $ICOLL_CODE; ?></td>
                                                            <td nowrap><?php echo $ICOLL_FG; ?></td>
                                                            <td nowrap><?php echo "$ITM_NAME - $ITM_NAME1<br>$CUST_DESC - $SELROW"; ?> </td>
                                                            <td><?php echo $ICOLL_REFNUM; ?></td>
                                                        </tr>
                                                    <?php
                                                    endforeach;
                                                ?>
                                                </tbody>
                                                <tr>
                                                  <td colspan="10" nowrap>
                                                  	<input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                    <button class="btn btn-primary" type="button" onClick="get_item();">
                                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                </tr>
                                            </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						elseif($List_Type == 2)
						{
							?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="whqty">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <?php
											// SELECT ALL QRC YANG DITERIMA
											$sqlQRC	= "SELECT pass1.* FROM 
														(SELECT C.QRC_NUM, C.IR_CODE, C.ITM_CODE, 
															C.ITM_NAME, C.QRC_STAT, C.QRC_PATT
														FROM tbl_qrc_detail C
														WHERE C.PRJCODE = '$PRJCODE'
															AND C.USED_QTY = 0
															AND C.REC_FROM = '$CUST_CODE'
															AND C.ITM_CODE = '$ITM_CODE'
															AND C.QRC_STAT = 9
															AND C.QRC_NUM NOT IN (SELECT D.QRC_NUM
																FROM tbl_jo_stfdetail_qrc D
																WHERE D.PRJCODE = '$PRJCODE'
																   AND D.JOSTF_STEP = '$PRODS_STEP'
																   AND D.ITM_CODE = '$ITM_CODE')
														ORDER BY C.IR_CODE, C.QRC_PATT ASC) AS pass1
														UNION ALL
														SELECT pass2.* FROM 
														(SELECT C.QRC_NUM, C.IR_CODE, C.ITM_CODE, 
															C.ITM_NAME, C.QRC_STAT, C.QRC_PATT
														FROM tbl_qrc_detail C
														WHERE C.PRJCODE = '$PRJCODE'
															AND C.USED_QTY = 0
															AND C.REC_FROM = '$CUST_CODE'
															AND C.ITM_CODE = '$ITM_CODE'
															AND C.QRC_STAT NOT IN (3,6,9)
															AND C.QRC_NUM NOT IN (SELECT D.QRC_NUM
																FROM tbl_jo_stfdetail_qrc D
																WHERE D.PRJCODE = '$PRJCODE'
																   AND D.JOSTF_STEP = '$PRODS_STEP'
																   AND D.ITM_CODE = '$ITM_CODE')
														ORDER BY C.IR_CODE, C.QRC_PATT ASC) AS pass2";
											$resQRC	= $this->db->query($sqlQRC)->result();
										?>
                                        <div class="form-group">
                                        	<form method="post" name="frmSubmit" id="frmSubmit" action="" onSubmit="return chkQRC()">
                                        		<input type="hidden" name="INP_CAT" id="INP_CAT" value="1">
                                              	<table width="100%" border="0">
                                                    <tr>
                                                      <td colspan="2">
                                                      		<input type="text" class="form-control" name="QRC_CODE" id="QRC_CODE" value="" onKeyPress="chg()"></td>
                                                    </tr>
                                                      <td width="50%">
                                                          	<select multiple="multiple" class="options" size="20" name="pavailable" id="pavailable" onclick="MoveOption(this.form.pavailable, this.form.packageelements)" style="min-width:300px;max-height:400px">
                                                                <?php
                                                                    foreach($resQRC as $rowQRC) :
                                                                        $QRC_NUM 	= $rowQRC->QRC_NUM;
                                                                        $IR_CODE	= $rowQRC->IR_CODE;
                                                                        $ITM_CODE	= $rowQRC->ITM_CODE;
                                                                        $ITM_NAME	= $rowQRC->ITM_NAME;
                                                                        $QRC_STAT	= $rowQRC->QRC_STAT;
                                                                        $QRC_PATT	= $rowQRC->QRC_PATT;

                                                                        $QRCDIS		= 0;
                                                                        if($QRC_STAT == 1 || $QRC_STAT == 2)
                                                                        	$QRCDIS	= 1;

                                                                        $QRCDESC	= "";
                                                                        if($QRC_STAT == 9)
                                                                        	$QRCDESC	= " - Selected";
                                                                        ?>
                                                                            <option value="<?php echo $QRC_NUM; ?>" <?php if($QRCDIS == 1) { ?> disabled <?php } ?>>
                                                                            	<?php echo "$IR_CODE - $ITM_NAME - $QRC_PATT $QRCDESC";?></option>
                                                                        <?php
                                                                    endforeach;
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td width="50%">
                                                            <?php					
                                                                $getQRCC	= "tbl_jo_stfdetail_qrc
                                                                                    WHERE PRJCODE = '$PRJCODE'
                                                                                    	AND JO_NUM = '$JO_NUM'
                                                                                        AND JOSTF_STEP = '$PRODS_STEP'
                                                                                        AND ITM_CODE = '$ITM_CODE'";
                                                                $resQRCC	= $this->db->count_all($getQRCC);
                                                            ?>
                                                            <select multiple="multiple" name="packageelements[]" id="packageelements" size="20" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)" style="min-width:300px; max-height:400px">
                                                                <?php
                                                                	$totRow	= 0;
                                                                    if($resQRCC > 0)
                                                                    {
                                                                        $getQRCL	= "SELECT QRC_NUM, IR_CODE, ITM_CODE, 
                                                                        					ITM_NAME, QRC_PATT
                                                                                        FROM tbl_jo_stfdetail_qrc A
	                                                                                    WHERE PRJCODE = '$PRJCODE'
	                                                                                    	AND JO_NUM = '$JO_NUM'
	                                                                                        AND JOSTF_STEP = '$PRODS_STEP'
	                                                                                        AND ITM_CODE = '$ITM_CODE'
	                                                                                    ORDER BY IR_CODE, QRC_PATT ASC";
                                                                        $resQRCL 	= $this->db->query($getQRCL)->result();
                                                                        foreach($resQRCL as $rowQRCL) :
                                                                        	$totRow		= $totRow + 1;
                                                                            $IR_CODE 	= $rowQRCL->IR_CODE;
                                                                            $QRC_NUM 	= $rowQRCL->QRC_NUM;
                                                                            $ITM_NAME 	= $rowQRCL->ITM_NAME;
                                                                            $QRC_PATT 	= $rowQRCL->QRC_PATT;
                                                                            ?>
                                                                                <option value="<?php echo $QRC_NUM; ?>"><?php echo "$IR_CODE - $ITM_NAME - $QRC_PATT";?></option>
                                                                            <?php
                                                                        endforeach; 
                                                                    }
                                                                ?>
                                                            </select>
                                                            <input type="hidden" name="totRow" id="totRow" value="<?php echo $totRow; ?>">
                                                            <input type="hidden" name="SELROW" id="SELROW" value="<?php echo $SELROW; ?>">
                                                            <input type="hidden" name="P_STEP" id="P_STEP" value="<?php echo $PRODS_STEP; ?>">
                                                            <input type="hidden" name="collD" id="collD" value="<?php echo "$totRow|$SELROW|$PRODS_STEP"; ?>">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" nowrap>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" nowrap>
                                                        <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                        <button class="btn btn-primary" name="btnSubmit" id="btnSubmit">
                                                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Process; ?>                    </button> 
                                                        <button class="btn btn-warning" type="button" onclick="resCat()">
                                                        <i class="fa fa-trash"></i>&nbsp;&nbsp;Reset</button> 
                                                        <button class="btn btn-danger" type="button" onClick="clsQRC()">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                    </tr>
                                                </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
					?>
                </div>
            </div>
		 </div>
	</div>
</section>
</body>
</html>
<?php
	$url	= base_url().'index.php/c_production/c_j0b0rd3r/getQRCL/';
?>
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
	  
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
  
  	function chg()
	{
		QRC_CODE		= $("#QRC_CODE").val();
		alert(QRC_CODE)
	}
	
	$("#QRC_CODE").keypress(function() 
	{
		$.ajax(
		{
			url: '<?php echo $url; ?>',
			method: 'GET',
			data: {QRC_CODE },
			cache: false,
			dataType: 'html',
			success: function(data) {
				alert(QRC_CODE)
			},
		});
	});
	
	
	
	var selectedRows = 0;
	function MoveOption(objSourceElement, objTargetElement) 
	{ 
		var aryTempSourceOptions = new Array(); 
		var aryTempTargetOptions = new Array(); 
		var x = 0; 
	
		//looping through source element to find selected options 
		for (var i = 0; i < objSourceElement.length; i++)
		{ 
			if (objSourceElement.options[i].selected)
			{ 
				 //need to move this option to target element 
				 var intTargetLen = objTargetElement.length++; 
				 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
				 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
			} 
			else
			{ 
				 //storing options that stay to recreate select element 
				 var objTempValues = new Object(); 
				 objTempValues.text = objSourceElement.options[i].text; 
				 objTempValues.value = objSourceElement.options[i].value; 
				 aryTempSourceOptions[x] = objTempValues; 
				 x++; 
			} 
		} 
	
		//sorting and refilling target list 
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
		} 
	
		aryTempTargetOptions.sort(sortByText); 
	
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		} 
	
		//resetting length of source 
		objSourceElement.length = aryTempSourceOptions.length; 
		//looping through temp array to recreate source select element 
		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		} 
	} 
	
	function sortByText(a, b) 
	{ 
		if (a.text < b.text) {return -1} 
		if (a.text > b.text) {return 1} 
		return 0;
	} 
	
	function selectAll(objTargetElement) 
	{ 
		for (var i = 0; i < objTargetElement.length; i++) 
		{ 
			objTargetElement.options[i].selected = true; 
		} 
		//return false;
  	}
	
	function chkQRC()
	{
		INP_CAT	= document.getElementById('INP_CAT').value;
		if(INP_CAT == 1)
		{
			column1	= document.getElementById('packageelements').value;
			if(column1 == '')
			{
				alert('<?php echo $alert0; ?>');
				document.getElementById('packageelements').focus();
				return false;
			}
		}
	}

	function resCat()
	{
		document.getElementById('INP_CAT').value = 2;
		document.frmSubmit.btnSubmit.click();
	}

	function clsQRC()
	{
		
		//var totRow	= document.getElementById('totRow').value;
		//var SELROW	= document.getElementById('SELROW').value;
		var collD	= document.getElementById('collD').value;
		//alert(collD)
		window.opener.getTotRowRM(collD);
		window.close();
	}

	function get_item() 
	{
		//alert(document.frmSearch.chk.length) 
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_itemRM(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_itemRM(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_itemRM(B,'child');
					alert(B)
				}*/
			}
		}
		window.close();		
	}
	
	function setType(thisValue)
	{
		if(thisValue == 1)
		{
			document.getElementById('List_Type').value = thisValue;
		}
		else
		{
			document.getElementById('List_Type').value = thisValue;
		}
		document.frm_01.submit1.click();
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>