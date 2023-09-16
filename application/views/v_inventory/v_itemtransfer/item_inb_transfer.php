<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 28 Januari 2019
    * File Name	= item_inb_transfer.php
    * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
?>
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
    	$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
    	//______$this->load->view('template/sidebar');
    	
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
    		if($TranslCode == 'TsfNo')$TsfNo = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;
    		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
    		if($TranslCode == 'PurchaseRequest')$PurchaseRequest = $LangTransl;
    		if($TranslCode == 'AddNew')$AddNew = $LangTransl;
    		if($TranslCode == 'Print')$Print = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
    		if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$title1		= "Pembebanan Aset";
    		$sureDelete	= "Anda yakin akan menghapus data ini?";
    	}
    	else
    	{
    		$title1		= "Asset Expense";
    		$sureDelete	= "Are your sure want to delete?";
    	}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h2_title; ?>
                <small><?php echo $PRJNAME; ?></small>
              </h1>
              <?php /*?><ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Data tables</li>
              </ol><?php */?>
        </section>
        <style type="text/css">
        	.search-table, td, th {
        		border-collapse: collapse;
        	}
        	.search-table-outter { overflow-x: scroll; }
        	
            a[disabled="disabled"] {
                pointer-events: none;
            }
        </style>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                          <table id="example1" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                                    <th style="vertical-align:middle; text-align:center" width="14%" nowrap><?php echo $TsfNo; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $Date; ?>  </th>
                                    <th style="vertical-align:middle; text-align:center" width="10%">Kode JO</th>
                                    <th style="vertical-align:middle; text-align:center" width="30%"><?php echo $Description; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $CreatedBy; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="4%" nowrap><?php echo $Status; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="4%" nowrap>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 0;
                                $j = 0;						
                                if($cData >0)
                                {
                                    foreach($vData as $row) :													
                                        $myNewNo1           = ++$i;
                                        $ITMTSF_NUM         = $row->ITMTSF_NUM;
                                        $ITMTSF_CODE        = $row->ITMTSF_CODE;
                                        $ITMTSF_DATE        = $row->ITMTSF_DATE;
                                        $ITMTSF_ORIGIN      = $row->ITMTSF_ORIGIN;
                                        $ITMTSF_DEST        = $row->ITMTSF_DEST;
                                        $JOBCODEID          = $row->JOBCODEID;
                                        $PRJNAME            = $row->PRJNAME;
                                        $ITMTSF_NOTE        = $row->ITMTSF_NOTE;
                                        $ITMTSF_STAT        = $row->ITMTSF_STAT;
                                        $ITMTSF_CREATER     = $row->ITMTSF_CREATER;
                                        $ITMTSF_AMOUNT      = $row->ITMTSF_AMOUNT;
                                        $ITMTSF_REFNO       = $row->ITMTSF_REFNO;
                                        $ITMTSF_STATD       = $row->STATDESC;
                                        $STATCOL            = $row->STATCOL;
                                        $CREATERNM          = $row->CREATERNM;                          
                                        $empName            = cut_text ("$CREATERNM", 15);

                                        $TSFFROM    = '-';
                                        $sqlWH      = "SELECT WH_NAME FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_ORIGIN' LIMIT 1";
                                        $resWH      = $this->db->query($sqlWH)->result();
                                        foreach($resWH as $rowWH) :
                                            $TSFFROM    = $rowWH->WH_NAME;
                                        endforeach;

                                        $TSFDEST    = '-';
                                        $sqlWH      = "SELECT WH_NAME FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_DEST' LIMIT 1";
                                        $resWH      = $this->db->query($sqlWH)->result();
                                        foreach($resWH as $rowWH) :
                                            $TSFDEST    = $rowWH->WH_NAME;
                                        endforeach;

                                        $JO_CODE    = '';
                                        $sqlMR      = "SELECT JO_CODE FROM tbl_mr_header WHERE MR_NUM = '$ITMTSF_REFNO' LIMIT 1";
                                        $resMR      = $this->db->query($sqlMR)->result();
                                        foreach($resMR as $rowMR) :
                                            $JO_CODE    = $rowMR->JO_CODE;
                                        endforeach;
                                        
                                        $JOBDESC            = '';
                                        $sqlJOBDESC         = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
                                        $resJOBDESC         = $this->db->query($sqlJOBDESC)->result();
                                        foreach($resJOBDESC as $rowJOBDESC) :
                                            $JOBDESC        = $rowJOBDESC->JOBDESC;
                                        endforeach;
                                        
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                            ?>
                                                    <td style="text-align:center">
                                                        <?php echo $myNewNo1; ?>.
                                                        <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $ITMTSF_NUM;?>" onClick="getValueNo(this);" style="display:none" />
                                                    </td>
                                                    <td nowrap>
                                                        <?php echo $ITMTSF_CODE;?>
                                                    </td>
                                                    <td style="text-align:center"><?php
                                                            $date = new DateTime($ITMTSF_DATE);
                                                            echo $ITMTSF_DATE;
                                                        ?></td>
                                                    <td ><?php echo $JO_CODE; ?></td>
                                                    <td><?php echo "$TSFFROM - $TSFDEST"; ?> </td>
                                                    <td style="text-align:center"><?php echo $empName; ?></td>
                                                    <td style="text-align:center">
                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                                        <?php 
                                                            echo "&nbsp;&nbsp;$ITMTSF_STATD&nbsp;&nbsp;";
                                                         ?>
                                                    </span>
                                                    </td>
                                                    <?php
                                                        $secUpd     = site_url('c_inventory/c_tr4n5p3r/up180djinb/?id='.$this->url_encryption_helper->encode_url($ITMTSF_NUM));
                                                        $secPrint   = site_url('c_inventory/c_tr4n5p3r/printdocument/?id='.$this->url_encryption_helper->encode_url($ITMTSF_NUM));
                                                        $CollID     = "$ITMTSF_NUM~$PRJCODE";
                                                        $secDel_PO  = base_url().'index.php/c_inventory/c_tr4n5p3r/trash_PO/?id='.$ITMTSF_NUM;
                                                    ?>
                                                    <input type="hidden" name="urlPrint<?php echo $myNewNo1; ?>" id="urlPrint<?php echo $myNewNo1; ?>" value="<?php echo $secPrint; ?>">
                                                    <td style="text-align:center" nowrap>
                                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                                            <i class="glyphicon glyphicon-pencil"></i>
                                                        </a>
                                                        <a href="javascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo1; ?>')">
                                                            <i class="glyphicon glyphicon-print"></i>
                                                        </a>
                                                        <a href="#" onClick="deleteMail('<?php echo $secDel_PO; ?>')" title="Delete file" class="btn btn-danger btn-xs" <?php if($ITMTSF_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                    endforeach; 
                                }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td colspan="8">
                                    <?php
                                        echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
                                    ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
                </div>
            </div>
        </section>
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
  
	function deleteMail(thisVal)
	{
		//swal(thisVal)
		//document.sendDelete.action = thisVal;
		//document.sendDelete.submit();
		//document.getElementById('delClass').click();
		document.sendDelete.action = thisVal;
		document.getElementById('delClass').click();
	}
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	
	function printPOList(row)
	{
		var url	= document.getElementById('urlPOList'+row).value;
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>