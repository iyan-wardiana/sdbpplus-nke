<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 November 2017
 * File Name	= itemreceipt_sel_po.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
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

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
    </head>

    <?php
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
    		if($TranslCode == 'PONumber')$PONumber = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
    		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    	endforeach;
    ?>

    <body class="<?php echo $appBody; ?>">
        <section class="content-header">
        </section>
        <style>
        	.search-table, td, th {
        		border-collapse: collapse;
        	}
        	.search-table-outter { overflow-x: scroll; }
        </style>
        <!-- Main content -->

        <section class="content">
            <div class="row">
                <div class="box-body">
                    <div class="callout callout-success" style="vertical-align:top">
                        <?php echo "$PRJCODE - $PRJNAME"; ?>
                    </div>
                	<div class="search-table-outter">
                        <form method="post" name="frmSearch" action="">
                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                        <th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $PONumber; ?></th>
                                        <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                                        <th width="24%" nowrap style="text-align:center"><?php echo $Supplier; ?></th>
                                        <th width="52%" nowrap style="text-align:center"><span style="text-align:center;"><?php echo $Description; ?></span></th>
                                        <th width="6%" nowrap style="text-align:center;"><span style="text-align:center"><?php echo $ReceivePlan; ?></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    $j = 0;
                                    $sqlPOC		= "tbl_po_header A
                                                        INNER JOIN  tbl_employee B ON A.PO_CREATER = B.Emp_ID
                                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                    WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'";				
                                    $resPOC 	= $this->db->count_all($sqlPOC);
                                
                                    $sqlPOV		= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                                                        A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                                                        B.First_Name, B.Middle_Name, B.Last_Name,
                                                        C.PRJCODE, C.PRJNAME
                                                    FROM tbl_po_header A
                                                        INNER JOIN  tbl_employee B ON A.PO_CREATER = B.Emp_ID
                                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                    WHERE A.PO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                                                    ORDER BY A.PO_NUM ASC";
                                    $viewAllPOV	= $this->db->query($sqlPOV)->result();
                                    if($resPOC > 0)
                                    {
                                        $totRow	= 0;
                                        foreach($viewAllPOV as $row) :
                                            $PO_NUM 		= $row->PO_NUM;
                                            $PO_CODE 		= $row->PO_CODE;
                                            $PO_DATE 		= $row->PO_DATE;
                                            $PO_CREATER 	= $row->PO_CREATER;
                                            $PO_STAT 		= $row->PO_STAT;
                                            $PO_APPROVER 	= $row->PO_APPROVER;
                                            $PO_NOTES 		= $row->PO_NOTES;
                                            $PO_PLANIR		= $row->PO_PLANIR;
                                            $PRJCODE		= $row->PRJCODE;
                                            $SPLCODE		= $row->SPLCODE;
                                            $PRJNAME		= $row->PRJNAME;
                                            $First_Name		= $row->First_Name;
                                            $Middle_Name	= $row->Middle_Name;
                                            $Last_Name		= $row->Last_Name;
                                            $compName 		= "$First_Name $Middle_Name $Last_Name";
                                            
                                            $SPLDESC		= '';
                                            if($SPLCODE != '')
                                            {
                                                $sqlSPL			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
                                                $resSPL			= $this->db->query($sqlSPL)->result();
                                                foreach($resSPL as $rowSPL) :
                                                    $SPLDESC	= $rowSPL->SPLDESC;
                                                endforeach;
                                            }
                                                                    
                                            $totRow			= $totRow + 1;
                                        
                                            if ($j==1) {
                                                echo "<tr class=zebra1>";
                                                $j++;
                                            } else {
                                                echo "<tr class=zebra2>";
                                                $j--;
                                            }
                                            ?>
                                            <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $PO_NUM;?>|<?php echo $PO_CODE;?>" onClick="pickThis(this);" /></td>
                                            <td nowrap>
                                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                                                <?php echo $PO_CODE; ?></a> 
                                                <input type="hidden" name="PO_NUM<?php echo $totRow; ?>" id="PR_NUM<?php echo $totRow; ?>" value="<?php echo $PO_NUM; ?>" />
                                            </td>
                                            <td nowrap=""><?php echo date('d M Y', strtotime($PO_DATE)); ?></td>
                                            <td><?php echo $SPLDESC; ?></td>
                                            <td><?php echo $PO_NOTES; ?></td>
                                            <td style="text-align:center"><?php echo date('d M Y', strtotime($PO_PLANIR)); ?></td>
                                            </tr>
                                        <?php
                                        endforeach;
                                    }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" nowrap>                
                                            <button class="btn btn-primary" type="button" onClick="get_req();">
                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                    <!-- /.box-body -->
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
</script>

<script>
var selectedRows = 0

function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

function pickThisA(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	//swal(NumOfRows)
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if(selectedRows > 1)
	{
		swal('Please select one Request');
		return false;
	}
	
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}

function get_req() 
	{
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_header(document.frmSearch.chk[i].value);				
				}
			}
		}
		else
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_header(document.frmSearch.chk.value);
				//swal('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					swal(B)
				}*/
			}
		}
		window.close();		
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
    //$this->load->view('template/aside');

    //$this->load->view('template/js_data');

    //$this->load->view('template/foot');
?>