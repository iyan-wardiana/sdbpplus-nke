<?php
/* 
 	* Author   = Dian Hermanto
 	* Create Date  = 21 Januari 2018
 	* File Name  = print_po.php
 	* Location   = -
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

$PO_DATEV	= strftime('%d %B %Y', strtotime($PO_DATE));
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME= $rowPRJ->PRJNAME;
endforeach;

$DrafTTD1   = "white";

$sqlSUPL  	= "SELECT SPLDESC, SPLADD1, SPLADD2, SPLTELP, SPLMAIL, SPLPERS, SPLNPWP
          		FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$resSUPL  	= $this->db->query($sqlSUPL)->result();
foreach($resSUPL as $rowSUPL) :
	$SPLDESC  = $rowSUPL->SPLDESC;
	$SPLADD1  = $rowSUPL->SPLADD1;
	$SPLADD2  = $rowSUPL->SPLADD2;
	$SPLPERS  = $rowSUPL->SPLPERS;
	$SPLTELP  = $rowSUPL->SPLTELP;
	$SPLMAIL  = $rowSUPL->SPLMAIL;
	$SPLNPWP  = $rowSUPL->SPLNPWP;
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
                <td style="text-align: right; font-weight: bold; font-size: 18px"><?=$header?></td>
            </tr>
        </table>
        <table border="0" width="100%">
            <tr>
            	<td>
            		<table border="1" width="100%">
            			<tr>
			                <td width="75%" style="vertical-align: top;">
			                	<?php echo $SPLDESC; ?><br>
			                	to<br>
			                	<?php echo $SPLADD1; ?><br><br><br>
			                	Telp. : <?php echo $SPLTELP; ?>
			                </td>
			                <td width="25%">
			                	<table>
			                		<tr>
			                			<td>PAGE</td>
			                			<td>:</td>
			                			<td>1</td>
			                		</tr>
			                		<tr>
			                			<td>DATE</td>
			                			<td>:</td>
			                			<td><?php echo date('d-m-Y', strtotime($PO_DATE)); ?></td>
			                		</tr>
			                		<tr style="vertical-align: top;">
			                			<td>NO</td>
			                			<td>:</td>
			                			<td><?php echo $PO_CODE; ?><br><br><br></td>
			                		</tr>
			                	</table>
			                </td>
			            </tr>
            		</table>
            	</td>
            </tr>
        	<tr>
				<td style="line-height: 1px">&nbsp;</td>
	      	</tr>
            <tr>
            	<td>
            		<table border="1" width="100%">
            			<tr style="font-weight: bold;">
			                <td style="border-right-style: hidden;" width="15%" nowrap>ITEM CODE</td>
			                <td style="border-right-style: hidden;" width="30%">DESCRIPTION</td>
			                <td style="border-right-style: hidden;" width="10%">QUANTITY</td>
			                <td style="border-right-style: hidden;" width="5%">UOM</td>
			                <td style="border-right-style: hidden;" width="10%" nowrap>UNIT PRICE</td>
			                <td style="border-right-style: hidden;" width="10%">DISC. %</td>
			                <td width="15%" style="text-align: center;">TOTAL</td>
			            </tr>
			            <?php
			            	$max 		= 5;
			            	$PONOTE 	= '-';
			            	$IR_PLN 	= date('Y-m-d');
			            	$PAYTYP 	= 0;
			            	$PTENOR 	= 0;
			            	$sqlPOH 	= "SELECT PO_NOTES, PO_PLANIR, PO_PAYTYPE, PO_TENOR FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
				            $resPOH   	= $this->db->query($sqlPOH)->result();
				            foreach($resPOH as $row) :
								$PONOTE = $row->PO_NOTES;
								$IR_PLN = $row->PO_PLANIR;
								$PAYTYP = $row->PO_PAYTYPE;
								$PTENOR = $row->PO_TENOR;
							endforeach;
							if($PAYTYP == 0)
								$PAYTYPD	= "Cash";
							else
								$PAYTYPD	= "Credit";

			            	$sqlPODET 	= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE,
											A.ITM_UNIT, A.PR_VOLM, A.PO_VOLM, A.IR_VOLM,
											A.IR_AMOUNT, A.IR_PAVG, A.PO_PRICE, A.PO_DISP,
											A.PO_DISC, A.PO_COST, A.PO_DESC, A.PO_DESC1,
											A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
											B.ITM_NAME
					                    FROM tbl_po_detail A
					                      	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
					                    WHERE 
					                      	A.PO_NUM = '$PO_NUM' 
					                      	AND B.PRJCODE = '$PRJCODE'
					                    GROUP BY A.ITM_CODE";
				            $resPODET   = $this->db->query($sqlPODET)->result();

				            $i      		= 0;
				            $j      		= 0;
				            $GTITMPRICE   	= 0;
				            $PO_TOTCOST   	= 0;
				            $TOTDISC    	= 0;
				            $TOTPPN     	= 0;
				            $TOT_COST    	= 0;
				            $TOTPRICE2    	= 0;
				            foreach($resPODET as $row) :
								$curRow   		= ++$i;
								$PR_NUM     	= $row->PR_NUM;
								$PO_CODE    	= $row->PO_CODE;
								$PO_DATE    	= $row->PO_DATE;
								$JOBCODEDET   	= $row->JOBCODEDET;
								$JOBCODEID    	= $row->JOBCODEID;
								$ITM_CODE     	= $row->ITM_CODE;
								$ITM_NAME     	= $row->ITM_NAME;
								$ITM_UNIT     	= $row->ITM_UNIT;
								$ITM_PRICE    	= $row->PO_PRICE;
								$PR_VOLM    	= $row->PR_VOLM;
								$PO_VOLM    	= $row->PO_VOLM;
								$IR_VOLM    	= $row->IR_VOLM;
								$IR_AMOUNT    	= $row->IR_AMOUNT;
								$IR_PAVG    	= $row->IR_PAVG;
								$PO_PRICE     	= $row->PO_PRICE;
								$PO_DISP    	= $row->PO_DISP;
								$PO_DISC    	= $row->PO_DISC;
								$PO_COST    	= $row->PO_COST;
								$TAXPRICE1    	= $row->TAXPRICE1;
								$ITM_TOTP   	= $PO_VOLM * $ITM_PRICE;
								$TOTDISC    	= $TOTDISC + $PO_DISC;
								$TOT_COST    	= $TOT_COST + $PO_COST;
								$TOTPPN     	= $TOTPPN + $TAXPRICE1;
								$TOTITEM 		= $ITM_TOTP - $TOTDISC;
				              
				              	$PO_DESC    	= $row->PO_DESC;
				              	?>
			            			<tr style="height: 20px">
						                <td style="border-right-style: hidden; <?php if($i != $max) { ?> border-bottom-style: hidden;<?php } ?>">
							                <?=$ITM_CODE?>
							            </td>
						                <td style="border-right-style: hidden; <?php if($i != $max) { ?> border-bottom-style: hidden;<?php } ?>">
							                <?=$ITM_NAME?>
							            </td>
						                <td style="border-right-style: hidden; <?php if($i != $max) { ?> border-bottom-style: hidden;<?php } ?>">
							                <?=number_format($PO_VOLM,2)?>
							            </td>
						                <td style="border-right-style: hidden; <?php if($i != $max) { ?> border-bottom-style: hidden;<?php } ?>">
							                <?=$ITM_UNIT?>
							            </td>
						                <td style="border-right-style: hidden; <?php if($i != $max) { ?> border-bottom-style: hidden;<?php } ?>">
							                <?=number_format($PO_PRICE,2)?>
							            </td>
						                <td style="border-right-style: hidden; <?php if($i != $max) { ?> border-bottom-style: hidden;<?php } ?>">
							                <?=number_format($PO_DISC,2)?>
							            </td>
						                <td <?php if($i != $max) { ?> style="border-bottom-style: hidden; text-align: right;"<?php } ?>>
							                <?=number_format($PO_COST,2)?>&nbsp;&nbsp;
							            </td>
						            </tr>
						        <?php
						    endforeach;
						    $GTOTAL 	= $TOT_COST + $TOTPPN;
						    $remRow 	= $max - $curRow;
						    $nxtRow		= $curRow;
			            	for($i=1;$i<=$remRow;$i++)
			            	{
			            		$nxtRow	= $nxtRow+1;
			            		?>
			            			<tr style="height: 20px">
						                <td style="border-right-style: hidden; <?php if($nxtRow != $max) { ?> border-bottom-style: hidden;<?php } ?>">&nbsp;</td>
						                <td style="border-right-style: hidden; <?php if($nxtRow != $max) { ?> border-bottom-style: hidden;<?php } ?>">&nbsp;</td>
						                <td style="border-right-style: hidden; <?php if($nxtRow != $max) { ?> border-bottom-style: hidden;<?php } ?>">&nbsp;</td>
						                <td style="border-right-style: hidden; <?php if($nxtRow != $max) { ?> border-bottom-style: hidden;<?php } ?>">&nbsp;</td>
						                <td style="border-right-style: hidden; <?php if($nxtRow != $max) { ?> border-bottom-style: hidden;<?php } ?>">&nbsp;</td>
						                <td style="border-right-style: hidden; <?php if($nxtRow != $max) { ?> border-bottom-style: hidden;<?php } ?>">&nbsp;</td>
						                <td <?php if($nxtRow != $max) { ?> style="border-bottom-style: hidden;"<?php } ?>>&nbsp;</td>
						            </tr>
						        <?php
						    }
						?>
            			<tr>
			                <td colspan="5" rowspan="3" style="vertical-align: top;">Remarks : <?php echo $PONOTE; ?></td>
			                <td style="height: 30px; vertical-align: middle; border-right-style: hidden; border-bottom-style: hidden; font-weight: bold;" nowrap>SUB TOTAL </td>
			                <td style="vertical-align: middle; border-bottom-style: hidden; text-align: right; font-weight: bold;"><?=number_format($TOT_COST,2)?>&nbsp;&nbsp;</td>
			            </tr>
            			<tr>
			                <td style="height: 10px; vertical-align: middle; border-right-style: hidden; font-weight: bold;">VAT 10%</td>
			                <td style="vertical-align: middle; text-align: right; font-weight: bold;"><?=number_format($TOTPPN,2)?>&nbsp;&nbsp;</td>
			            </tr>
            			<tr>
			                <td style="height: 30px; vertical-align: middle; border-right-style: hidden; font-weight: bold;">TOTAL </td>
			                <td style="height: 30px; vertical-align: middle; text-align: right; font-weight: bold;"><?=number_format($GTOTAL,2)?>&nbsp;&nbsp;</td>
			            </tr>
			        	<tr>
							<td colspan="7" style="line-height: 1px; border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;">&nbsp;</td>
				      	</tr>
            			<tr>
			                <td nowrap style="border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden;">Delivery Date </td>
			                <td style="border-bottom-style: hidden; border-right-style: hidden;" colspan="5">: <?php echo date('d-m-Y', strtotime($IR_PLN)); ?></td>
			            </tr>
            			<tr>
			                <td nowrap style="border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden;">Shipping Address </td>
			                <td style="border-bottom-style: hidden; border-right-style: hidden;" colspan="5">: </td>
			            </tr>
            			<tr>
			                <td nowrap style="border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden;">Term Of Payment </td>
			                <td style="border-bottom-style: hidden; border-right-style: hidden;" colspan="5">: <?php echo $PTENOR; ?> day(s)</td>
			            </tr>
            			<tr>
			                <td nowrap style="border-bottom-style: hidden;border-right-style: hidden; border-left-style: hidden;">Says </td>
			                <td style="border-bottom-style: hidden; border-right-style: hidden;" colspan="5">: </td>
			            </tr>
            		</table>
            	</td>
            </tr>
        </table>

        <br>
        <table width="100%" border="1">
            <tr>
                <td colspan="4" style="text-align: center; border-top-style: hidden; border-right-style: hidden; border-left-style: hidden; font-weight: bold;">
                	NPWP: 01.455.340.8.418.000 A/N PT FRANS BROTHERS SEJATI
               	</td>
            </tr>
            <tr>
                <td width="25%" style="text-align: center;">SUPPLIER</td>
                <td width="25%" style="text-align: center;">PREPARE & CHECKED BY</td>
                <td width="25%" style="text-align: center;">CHECKED BY</td>
                <td width="25%" style="text-align: center;">APPROVED BY</td>
            </tr>
            <tr>
                <td width="25%" style="text-align: center;"><br><br><br><br><br></td></td>
                <td width="25%" style="text-align: center;">&nbsp;</td>
                <td width="25%" style="text-align: center;">&nbsp;</td>
                <td width="25%" style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td width="25%" style="text-align: center;">SIGNED & CHOPPED</td>
                <td width="25%" style="text-align: center;">PURCHASING DEPT</td>
                <td width="25%" style="text-align: center;">ACCOUNTING DEPT</td>
                <td width="25%" style="text-align: center;">GENERAL MGR.</td>
            </tr>
            <tr>
                <td colspan="4">PO ASLI INI HARUS DISERTAKAN PADA SAAT PENAGIHAN OLEH SUPPLIER YANG AKAN DIBERIKAN KEPADA ACCOUNTING</td>
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