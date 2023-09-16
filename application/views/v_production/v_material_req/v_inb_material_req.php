<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 13 Februari 2019
    * File Name	= v_inb_material_req.php
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
    		if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'FinGoods')$FinGoods = $LangTransl;
    		if($TranslCode == 'RequestStatus')$RequestStatus = $LangTransl;
    		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
    		if($TranslCode == 'PurchaseRequest')$PurchaseRequest = $LangTransl;
    		if($TranslCode == 'AddNew')$AddNew = $LangTransl;
    		if($TranslCode == 'Print')$Print = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
    		if($TranslCode == 'RequestedBy')$RequestedBy = $LangTransl;
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

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
        
        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>
    <?php

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >
                <?php echo $mnName; ?>
                <small><?php echo $PRJNAME; ?></small>
              </h1>
              <?php /*?><ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Data tables</li>
              </ol><?php */?>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                          <table id="example1" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                                    <th style="vertical-align:middle; text-align:center; display:none" width="14%" nowrap><?php echo $RequestCode; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $Code; ?>  </th>
                                    <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date; ?>  </th>
                                    <th width="38%" style="vertical-align:middle; text-align:center"><?php echo $Description; ?> </th>
                                    <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $FinGoods; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $RequestedBy; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="4%" nowrap>Status </th>
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
                                        $myNewNo1 			= ++$i;
                                        $MR_NUM			= $row->MR_NUM;
                                        $MR_CODE		= $row->MR_CODE;
                                        $MR_DATE		= $row->MR_DATE;
                                        $PRJCODE		= $row->PRJCODE;
                                        //$JOBCODE		= $row->JOBCODE;
                                        $MR_NOTE		= $row->MR_NOTE;
            							$ITM_FG			= $row->ITM_CODE;
                                        $MR_STAT		= $row->MR_STAT;
                                        $MR_REFNO		= $row->MR_REFNO;
                                        $MR_CREATER		= $row->MR_CREATER;
                                        $MR_ISCLOSE		= $row->MR_ISCLOSE;
                                        // CARI TOTAL REGUSEST BUDGET APPROVED
                                            $JOBDESC		= '';
                                            /*$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$MR_REFNO' LIMIT 1";
                                            $resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
                                            foreach($resJOBDESC as $rowJOBDESC) :
                                                $JOBDESC	= $rowJOBDESC->JOBDESC;
                                            endforeach;*/
            								
                                        // CARI DATA KARYAWAN
                                            $empName		= '';
                                            $sqlJOBDESC		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$MR_CREATER' LIMIT 1";
                                            $resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
                                            foreach($resJOBDESC as $rowJOBDESC) :
                                                $First_Name	= $rowJOBDESC->First_Name;
                                                $Last_Name	= $rowJOBDESC->Last_Name;
            									$empName1	= "$First_Name $Last_Name";
            									$empName	= cut_text ("$empName1", 20);
                                            endforeach;
                                        
                                        if($MR_STAT == 0)
                                        {
                                            $MR_STATD 	= 'fake';
                                            $STATCOL	= 'danger';
                                        }
                                        elseif($MR_STAT == 1)
                                        {
                                            $MR_STATD 	= 'New';
                                            $STATCOL	= 'warning';
                                        }
                                        elseif($MR_STAT == 2)
                                        {
                                            $MR_STATD 	= 'Confirm';
                                            $STATCOL	= 'primary';
                                        }
                                        elseif($MR_STAT == 3)
                                        {
                                            $MR_STATD 	= 'Approved';
                                            $STATCOL	= 'success';
                                        }
                                        elseif($MR_STAT == 4)
                                        {
                                            $MR_STATD 	= 'Revise';
                                            $STATCOL	= 'danger';
                                        }
                                        elseif($MR_STAT == 5)
                                        {
                                            $MR_STATD 	= 'Rejected';
                                            $STATCOL	= 'danger';
                                        }
                                        elseif($MR_STAT == 6)
                                        {
                                            $MR_STATD 	= 'Close';
                                            $STATCOL	= 'danger';
                                        }
                                        else
                                        {
                                            $MR_STATD 	= 'Awaiting';
                                            $STATCOL	= 'warning';
                                        }
                                        
                                        if($MR_ISCLOSE == 1)
                                        {
                                            $MR_STATD 	= 'Closed';
                                            $STATCOL	= 'success';
                                        }
                                        
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
                                                        <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $MR_NUM;?>" onClick="getValueNo(this);" style="display:none" />
                                                    </td>
                                                    <td style="display:none" nowrap>
                                                        <?php echo $MR_NUM;?>
                                                    </td>
                                                    <td nowrap> <?php print $MR_CODE; ?></td>
                                                    <td style="text-align:center"><?php
                                                            $date = new DateTime($MR_DATE);
                                                            echo $MR_DATE;
                                                        ?>                            </td>
                                                    <td><?php print $MR_NOTE; ?> </td>
                                                    <td><?php print $ITM_FG; ?></td>
                                                    <td style="text-align:center"><?php echo strtolower($empName); ?></td>
                                                    <td style="text-align:center">
                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                                        <?php 
                                                            echo "&nbsp;&nbsp;$MR_STATD&nbsp;&nbsp;";
                                                         ?>
                                                    </span>
                                                    </td>
                                                    <?php
                                                        $secUpd		= site_url('c_production/c_mr180d0c/up180djinb/?id='.$this->url_encryption_helper->encode_url($MR_NUM));
                                                        $secPPOList	= site_url('c_production/c_mr180d0c/printpolist/?id='.$this->url_encryption_helper->encode_url($MR_NUM));
                                                        $secPrint	= site_url('c_production/c_mr180d0c/printdocument/?id='.$this->url_encryption_helper->encode_url($MR_NUM));
                                                        $CollID		= "$MR_NUM~$PRJCODE";
                                                        $secDel_PO 	= base_url().'index.php/c_production/c_mr180d0c/trash_PO/?id='.$MR_NUM;
                                                    ?>
                                                    <input type="hidden" name="urlPOList<?php echo $myNewNo1; ?>" id="urlPOList<?php echo $myNewNo1; ?>" value="<?php echo $secPPOList; ?>">
                                                    <input type="hidden" name="urlPrint<?php echo $myNewNo1; ?>" id="urlPrint<?php echo $myNewNo1; ?>" value="<?php echo $secPrint; ?>">
                                                    <td style="text-align:center" nowrap>
                                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                                            <i class="glyphicon glyphicon-pencil"></i>
                                                        </a>
                                                        <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="View Receipt" onClick="printPOList('<?php echo $myNewNo1; ?>')" style="display:none">
                                                            <i class="glyphicon glyphicon-list"></i>
                                                        </a>
                                                        <a href="javascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo1; ?>')">
                                                            <i class="glyphicon glyphicon-print"></i>
                                                        </a>
                                                        <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="Print" onClick="printQR('<?php echo $myNewNo1; ?>')" style="display:none">
                                                            <i class="fa fa-qrcode"></i>
                                                        </a>
                                                        <a href="#" onClick="deleteMail('<?php echo $secDel_PO; ?>')" title="Delete file" class="btn btn-danger btn-xs" <?php if($MR_STAT > 1) { ?>disabled="disabled" <?php } ?> style="display:none">
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
                            </tfoot>
                        </table>
                        
                        <form method="post" name="sendDelete" id="sendDelete" class="form-user" action="" style="display:none">		
                            <table>
                                <tr>
                                    <td></td>
                                    <td><a class="tombol-delete" id="delClass">Simpan</a></td>
                                </tr>
                            </table>
                        </form>
                        <?php
                            $secIndex_PR	= site_url('c_production/c_mr180d0c/g4ll_m4tr3q/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?>
                    </div>
                    <br>
                    <?php
                       // echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>');
                    ?>&nbsp;
                        <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
                    <?php
                        echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
                    ?>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
	
	$(document).ready(function()
	{
		$(".tombol-delete").click(function()
		{
			swal('a')
			var index_Mail	= "<?php echo $secIndex_PR; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			swal('b')
			var data = $('.form-user').serialize();
			swal('c')
			$.ajax({
				type: 'POST',
				url: formAction,
				data: data,
				success: function(response)
				{
					$( "#example1" ).load( ""+index_Mail+" #example1" );
				}
			});
			
		});
	});
  
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