<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 27 Februari 2019
 * File Name	= v_inb_prodprocess.php
 * Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
?>

<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk  = $rowcss->cssjs_lnk;
              ?>
                  <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              <?php
          endforeach;

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk1  = $rowcss->cssjs_lnk;
              ?>
                  <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              <?php
          endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
    	$this->load->view('template/topbar');
    	$this->load->view('template/sidebar');
    	
    	$ISREAD 	= $this->session->userdata['ISREAD'];
    	$ISCREATE 	= $this->session->userdata['ISCREATE'];
    	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
    	$ISDWONL 	= $this->session->userdata['ISDWONL'];
    	$LangID 	= $this->session->userdata['LangID'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'ProcessStep')$ProcessStep = $LangTransl;
    		if($TranslCode == 'JOCode')$JOCode = $LangTransl;
    		if($TranslCode == 'SOCode')$SOCode = $LangTransl;
    		if($TranslCode == 'DestProcess')$DestProcess = $LangTransl;
    		if($TranslCode == 'Term')$Term = $LangTransl;
    		if($TranslCode == 'Approve')$Approve = $LangTransl;
    		if($TranslCode == 'User')$User = $LangTransl;
    		if($TranslCode == 'PurchaseOrder')$PurchaseOrder = $LangTransl;
    		if($TranslCode == 'Purchase')$Purchase = $LangTransl;
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'PODirect')$PODirect = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$sureDelete	= "Anda yakin akan menghapus data ini?";
    	}
    	else
    	{
    		$sureDelete	= "Are your sure want to delete?";
    	}
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
                <small><?php echo $PRJNAME; ?></small>
                </h1>
            </section>
    
            <section class="content">
                <div class="box">
                    <!-- /.box-header -->
            		<div class="box-body">
                        <div class="search-table-outter">
                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                <tr>
                                    <!--<th width="4%" rowspan="2" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>-->
                                    <th width="2%" style="vertical-align:middle; text-align:center; display:none">No</th>
                                    <th width="8%" style="vertical-align:middle; text-align:center; display:none"><?php echo $Code ?> </th>
                                    <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Code ?> </th>
                                    <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                                    <th width="13%" style="vertical-align:middle; text-align:center"><?php echo $ProcessStep  ?> </th>
                                    <th width="14%" style="vertical-align:middle; text-align:center"><?php echo $DestProcess ?> </th>
                                    <th width="28%" style="vertical-align:middle; text-align:center"><?php echo $SOCode ?> </th>
                                    <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $JOCode ?> </th>
                                    <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $SOCode ?> </th>                   
                                    <th width="5%" style="vertical-align:middle; text-align:center">Status </th>
                                    <th width="4%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody> 
                                    <?php 
                                    $i = 0;
                                    $j = 0;
                                    if($cData > 0)
                                    {
                                        $Unit_Type_Name2	= '';
                                        foreach($vData as $row) :
                                            $myNewNo 		= ++$i;
                                            $STF_NUM 		= $row->STF_NUM;
                                            $STF_CODE 		= $row->STF_CODE;
                                            $PRJCODE 		= $row->PRJCODE;
                                            $STF_DATE 		= $row->STF_DATE;
                                            $PRJCODE 		= $row->PRJCODE;
                                            $CUST_CODE 		= $row->CUST_CODE;
                                            $CUST_DESC 		= $row->CUST_DESC;
                                            $JO_NUM			= $row->JO_NUM;
            								$JO_CODE		= $row->JO_CODE;
                                            $SO_NUM 		= $row->SO_NUM;
                                            $SO_CODE 		= $row->SO_CODE;
                                            $CCAL_NUM		= $row->CCAL_NUM;
                                            $CCAL_CODE		= $row->CCAL_CODE;
                                            $BOM_NUM		= $row->BOM_NUM;
                                            $BOM_CODE		= $row->BOM_CODE;
                                            $STF_FROM		= $row->STF_FROM;
                                            $STF_DEST		= $row->STF_DEST;
                                            $STF_NOTES		= $row->STF_NOTES;
                                            $STF_STAT		= $row->STF_STAT;
                                            $STATDESC		= $row->STATDESC;
                                            $STATCOL		= $row->STATCOL;
                                            $CREATERNM		= $row->CREATERNM;
            								
            								$ORIG_ID		= 0;
            								$ORIG_NAME		= '';
            								$sqlSTEP1		= "SELECT PRODS_ID, PRODS_NAME FROM tbl_prodstep WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
            								$resSTEP1		= $this->db->query($sqlSTEP1)->result();
            								foreach($resSTEP1 as $rowSTP1):
            									$ORIG_ID	= $rowSTP1->PRODS_ID;
            									$ORIG_NAME	= $rowSTP1->PRODS_NAME;
            								endforeach;
            								
            								$DEST_ID		= 0;
            								$DEST_NAME		= '';
            								$sqlSTEP2		= "SELECT PRODS_ID, PRODS_NAME FROM tbl_prodstep WHERE PRODS_STEP = '$STF_DEST' LIMIT 1";
            								$resSTEP2		= $this->db->query($sqlSTEP2)->result();
            								foreach($resSTEP2 as $rowSTP2):
            									$DEST_ID	= $rowSTP2->PRODS_ID;
            									$DEST_NAME	= $rowSTP2->PRODS_NAME;
            								endforeach;
                                            
            								$CollID			= "$STF_NUM~$JO_NUM~$PRJCODE";
            								
            								$secUpd			= site_url('c_production/c_pR04uctpr0535/u775o_pR041nb/?id='.$this->url_encryption_helper->encode_url($CollID));
            								$secPrint		= site_url('c_production/c_pR04uctpr0535/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($STF_NUM));
            								
                                            if ($j==1) {
                                                echo "<tr class=zebra1>";
                                                $j++;
                                            } else {
                                                echo "<tr class=zebra2>";
                                                $j--;
                                            }
                                    		?>
                                                <td style="text-align:center; display:none" nowrap><?php print $myNewNo; ?>.</td>                   
                                                <td nowrap style="text-align:left; display:none"> <?php print $STF_NUM; ?> </td>                   
                                                <td nowrap style="text-align:left"> <?php print $STF_CODE; ?> </td>
                                                <td style="text-align:center" nowrap><?php print $STF_DATE; ?> </td>
                                                <td style="text-align:left" nowrap><?php print "$ORIG_ID. $ORIG_NAME"; ?> </td>
                                                <td style="text-align:left" nowrap><?php print "$DEST_ID. $DEST_NAME"; ?> </td>
                                                <td style="text-align:left"><?php print "$CUST_CODE - $CUST_DESC"; ?></td>
                                                <td style="text-align:center"><?php print $JO_CODE; ?></td>
                                                <td style="text-align:center" nowrap><?php print $SO_CODE; ?> </td>
                                                <td style="text-align:center">
                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                                        <?php
                                                            echo $STATDESC;
                                                         ?>
                                                     </span>
                                                </td>      
                                                <input type="hidden" name="urlPrint<?php echo $myNewNo; ?>" id="urlPrint<?php echo $myNewNo; ?>" value="<?php echo $secPrint; ?>">
                                                <td style="text-align:center" nowrap>
                                                    <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                    </a>
                                                    <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="View Receipt" onClick="printIRList('<?php echo $myNewNo; ?>')" style="display:none">
                                                        <i class="glyphicon glyphicon-list"></i>
                                                    </a>
                                                    <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                                        <i class="glyphicon glyphicon-print"></i>
                                                    </a>
                                                    <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($STF_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </a>
                                            	</td>
                                        	</tr>
                                        	<?php 
                               			endforeach;
                            		}
            						$url_addQRCode	= site_url('c_production/c_pR04uctpr0535/a44QR_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                    ?> 
                                </tbody>
                                <tr>
                                    <td colspan="11">
                                        <?php
            								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
            								if ( ! empty($link))
            								{
            									foreach($link as $links)
            									{
            										echo $links;
            									}
            								}
            							?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                  	<!-- /.box -->
                </div>
            </section>
        </div>
    </body>
</html>

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
	
	function printIRList(row)
	{
		var url	= document.getElementById('urlIRList'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function printDocument(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>