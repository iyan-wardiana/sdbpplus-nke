<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Januari 2018
 * File Name	= print_matreq.php
 * Location		= -*/

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

$PR_NUM		= $default['PR_NUM'];
$PR_CODE	= $default['PR_CODE'];
$PR_DATE	= $default['PR_DATE'];
$PRJCODE	= $default['PRJCODE'];
$PR_NOTE	= $default['PR_NOTE'];
$PR_STAT	= $default['PR_STAT'];
$PR_PLAN_IR	= $default['PR_PLAN_IR'];

$PR_DATEV	= date('d M Y', strtotime($PR_DATE));
$PR_PLAN_IRV= date('d M Y', strtotime($PR_PLAN_IR));
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME= $rowPRJ->PRJNAME;
endforeach;
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
                        <td width="23%" rowspan="2"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="120" height="60" ></td>
                        <td width="20%">&nbsp;</td>
                        <td width="41%">&nbsp;</td>
                        <td width="16%">No. Ref. : <?php echo $PR_CODE; ?></td>
                    </tr>
                    <tr>
                      <td colspan="2" style="text-align:center; font-size:18px; font-weight:bold">FORM PERMINTAAN PENGADAAN</td>
                      <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
   	  <div class="row invoice-info">
        <table border="0" width="100%">
                <tr>
                  <td nowrap>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                </tr>
                <tr>
                    <td width="10%" nowrap>Tanggal Permintaan</td>
                    <td width="1%">:</td>
                    <td width="40%" nowrap><?php echo $PR_DATEV; ?></td>
                    <td width="14%" nowrap>Nama Proyek</td>
                    <td width="35%" nowrap>: <?php echo $PRJNAME; ?></td>
                </tr>
                <tr>
                    <td nowrap>Tanggal Dibutuhkan</td>
                  <td>:</td>
                    <td nowrap><?php echo $PR_PLAN_IRV; ?></td>
                  <td nowrap>Nomor Proyek</td>
                  <td nowrap>: <?php echo $PRJCODE; ?></td>
                </tr>
                <tr>
                    <td nowrap>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td nowrap>Lokasi Pengiriman</td>
                  <td nowrap>:</td>
                </tr>
                <tr>
                    <td nowrap>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
        </table>
       		<div class="col-sm-4 invoice-col"></div>
      </div>
           
            <table border="1" width="100%">
                <tr style="background-color:#999">
                    <th width="3%" rowspan="2" style="background-color:#999; vertical-align:middle">NO.</th>
                    <th width="23%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">DESKRIPSI</th>
                    <th width="10%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">SPESIFIKASI</th>
                    <th width="7%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">RENCANA<br>VOLUME</th>
                    <th width="6%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">SATUAN</th>
                    <th colspan="3" style="background-color:#999; vertical-align:middle; text-align:center">PENGADAAN</th><th width="29%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">KETERANGAN</th>
                </tr>
                <tr>
                    <th width="8%" style="background-color:#999; text-align:center" nowrap>YANG LALU</th>
                    <th width="6%" style="background-color:#999; text-align:center" nowrap>SAAT INI</th>
                    <th width="8%" style="background-color:#999; text-align:center" nowrap>S.D. SAT INI</th>
                </tr>
                <?php
					$maxRow		= 10;
                    $rowNo		= 0;
					$rowNo1		= 0;
					$TOTROW		= 0;
                    $sqlPRDetC	= "SELECT COUNT(DISTINCT JOBCODEID) AS TOTROW FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
                    $resPRDetC	= $this->db->query($sqlPRDetC)->result();
					foreach($resPRDetC as $rowPRC) :
						$TOTROW	= $rowPRC->TOTROW;
					endforeach;
					
					$ITM_CODE1	= '';
					$ITM_NAME2	= '';
                    /*$sqlPRDet	= "SELECT JOBCODEID, ITM_CODE, ITM_UNIT, SUM(PR_VOLM) AS PR_VOLM, PR_PRICE, PR_DESC
									FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' GROUP BY JOBCODEID, ITM_CODE";*/
                    $sqlPRDet	= "SELECT JOBCODEID, ITM_CODE, ITM_UNIT, SUM(PR_VOLM) AS PR_VOLM, PR_PRICE, PR_DESC
									FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' GROUP BY ITM_CODE";
                    $resPRDet	= $this->db->query($sqlPRDet)->result();
					foreach($resPRDet as $rowPR) :
						$rowNo1		= $rowNo1 + 1;
						$JOBCODEID	= $rowPR->JOBCODEID;
						$ITM_CODE	= $rowPR->ITM_CODE;
						if($rowNo1 == 1)
						{
							$JOBCODEID1 	= " <em>($JOBCODEID)</em>";
						}
						
						if($rowNo1 == 2 && $ITM_CODE1 == $ITM_CODE)
						{
							$ITM_NAME2 	= $JOBCODEID1;
						}
						$ITM_CODE1	= $ITM_CODE;
					endforeach;
					
					if($TOTROW <= 20)
					{
						$ITM_NAME1		= "";
						foreach($resPRDet as $rowPR) :
							$rowNo		= $rowNo + 1;
							$JOBCODEID	= $rowPR->JOBCODEID;
							$ITM_CODE	= $rowPR->ITM_CODE;
							$ITM_NAME	= '';
							$ITM_DESC	= '';
							$sqlITM		= "SELECT ITM_NAME, ITM_DESC FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
							$resITM		= $this->db->query($sqlITM)->result();
								foreach($resITM as $rowITM) :
									$ITM_NAME	= $rowITM->ITM_NAME;
									$ITM_DESC	= $rowITM->ITM_DESC;								
								endforeach;
							
							$ITMDESC	= $ITM_NAME2;
							if($ITM_NAME1 == $ITM_NAME)
							{
								$ITMDESC	= " <em>($JOBCODEID)</em>";
							}
								
							$ITM_UNIT	= $rowPR->ITM_UNIT;
							$PR_VOLM	= $rowPR->PR_VOLM;
							$PR_PRICE	= $rowPR->PR_PRICE;
							$PR_DESC	= $rowPR->PR_DESC;
							
							/*$TOTPR_BEF	= 0;
							$sqlBEF		= "SELECT SUM(A.PR_VOLM) AS TOTPR_BEF FROM tbl_pr_detail A
												INNER JOIN tbl_pr_header B ON A.PR_CODE = B.PR_CODE
											WHERE B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND B.PR_STAT = 3";
							$resBEF		= $this->db->query($sqlBEF)->result();
							foreach($sqlBEF as $rowBEF) :
								$TOTPR_BEF	= $rowBEF->TOTPR_BEF;								
							endforeach;*/
							
							$ITM_VOLMB	= 0;
							/*$sqlBEF		= "SELECT SUM(ITM_VOLM) AS ITM_VOLM, REQ_VOLM, ITM_STOCK FROM tbl_joblist_detail
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'";*/
							$sqlBEF		= "SELECT SUM(ITM_VOLM) AS ITM_VOLM, REQ_VOLM, ITM_STOCK FROM tbl_joblist_detail
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
							$resBEF		= $this->db->query($sqlBEF)->result();
							foreach($resBEF as $rowBEF) :
								$ITM_VOLMB	= $rowBEF->ITM_VOLM;							
							endforeach;
							
							$sqlBEF		= "SELECT SUM(A.PR_VOLM) AS TOT_PR
											FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
											WHERE
												A.ITM_CODE = '$ITM_CODE'
												AND A.PRJCODE = '$PRJCODE'
												AND B.PR_STAT IN (3,6)
												-- AND A.PR_NUM != '$PR_NUM' AND JOBCODEID = '$JOBCODEID'
												AND A.PR_NUM != '$PR_NUM'";
							$resBEF		= $this->db->query($sqlBEF)->result();
							foreach($resBEF as $rowBEF) :
								$TOT_PR	= $rowBEF->TOT_PR;						
							endforeach;
							
							/*if($PR_STAT == 3)
								$REQ_BEFORE	= $REQ_VOLM - $PR_VOLM;
							else*/
								$REQ_BEFORE	= $TOT_PR;
							
							$TOTPR_VOLM		= $REQ_BEFORE + $PR_VOLM
							?>
								<tr>
									<td style="text-align:center"><?php echo $rowNo; ?>.</td>
									<td nowrap><?php echo "$ITM_NAME$ITMDESC"; ?></td>
									<td nowrap>&nbsp;</td>
									<td style="text-align:right"><?php echo number_format($ITM_VOLMB, $decFormat); ?></td>
									<td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
									<td style="text-align:right"><?php echo number_format($REQ_BEFORE, $decFormat); ?></td>
									<td style="text-align:right"><?php echo number_format($PR_VOLM, $decFormat); ?></td>
									<td style="text-align:right"><?php echo number_format($TOTPR_VOLM, $decFormat); ?></td>
									<td><?php echo $PR_DESC; ?></td>
								</tr>
							<?php
							$ITM_NAME1	= $ITM_NAME;
						endforeach;
						$rowRem	= $maxRow - $rowNo;
						for($i=0; $i < $rowRem; $i++)
						{
						?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="text-align:center">&nbsp;</td>
                                <td style="text-align:right">&nbsp;</td>
                                <td style="text-align:right">&nbsp;</td>
                                <td style="text-align:right">&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
						<?php
						}
					}
                ?>
                <tr height="80px" style="border-color:#000">
                    <td colspan="9">&nbsp;</td>
                </tr>
            </table>
            <table width="100%" border="1">
                <tr>
                    <td width="25%">Diterima Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                    <td width="25%">Diperiksa Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                    <td width="25%">Dibuat Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                    <td width="25%">No. Form : <br><br>Paraf : <br><br>Tanggal :</td>
                </tr>
            </table>
        <br>
        <br>
        <!-- /.row --><!-- /.row -->
      <!-- this row will not appear when printing -->
        <div class="row no-print" style="display:none">
            <div class="col-xs-12">
                <div id="Layer1">
                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
</section>
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