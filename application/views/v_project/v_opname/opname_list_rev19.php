<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Februari 2018
 * File Name	= opname_list.php
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
		if($TranslCode == 'OpnNo')$OpnNo = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
		if($TranslCode == 'JobOpname')$JobOpname = $LangTransl;
		if($TranslCode == 'AddNew')$AddNew = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
		if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
		if($TranslCode == 'Create')$Create = $LangTransl;
		if($TranslCode == 'Invoice')$Invoice = $LangTransl;
		if($TranslCode == 'SPKCode')$SPKCode = $LangTransl;
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

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $JobOpname; ?>
    <small><?php echo $PRJNAME; ?></small>
  </h1><br>
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
<!-- Main content -->

  <div class="box">
        <?php /*?><div class="box-header with-border">
            <h3 class="box-title"><?php echo $PRJNAME; ?></h3>
        </div><?php */?>
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped" width="100%">
		<thead>
            <tr>
                <th style="vertical-align:middle; text-align:center; display:none" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                <th style="vertical-align:middle; text-align:center; display:none" width="14%" nowrap><?php echo $OpnNo; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $Code; ?>  </th>
                <th width="5%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date; ?>  </th>
                <th width="5%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $SPKCode; ?> </th>
                <th width="48%" style="vertical-align:middle; text-align:center"><?php echo $Description; ?> </th>
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
					$myNewNo1 		= ++$i;
					$OPNH_NUM		= $row->OPNH_NUM;
					$OPNH_CODE		= $row->OPNH_CODE;
					$OPNH_DATE		= $row->OPNH_DATE;
					$PRJCODE		= $row->PRJCODE;
					//$PRJNAME		= $row->PRJNAME;
					$OPNH_NOTE		= $row->OPNH_NOTE;
					$OPNH_STAT		= $row->OPNH_STAT;
					$WO_NUM			= $row->WO_NUM;
					$OPNH_CREATER	= $row->OPNH_CREATER;
					$OPNH_ISCLOSE	= $row->OPNH_ISCLOSE;
					$JOBCODEID		= $row->JOBCODEID;
					
					$CREATER_NAME	= '';
					$First_Name		= '';
					$Last_Name		= '';
					$sqlEmp 		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$OPNH_CREATER'";
					$resEmp			= $this->db->query($sqlEmp)->result();
					foreach($resEmp as $rowEmp) :
						$First_Name = $rowEmp->First_Name;
						$Last_Name	= $rowEmp->Last_Name;
					endforeach;
					$CREATER_NAME	= "$First_Name $Last_Name";
					
					$splitJOB 		= explode("~", $JOBCODEID);
					$JOBCODEID		= $splitJOB[0];
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;
					if($JOBDESC == '')
						$JOBDESC	= $OPNH_NOTE;
						
					$WO_CODE		= '';
					$sqlWO 			= "SELECT WO_CODE FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
					$resWO			= $this->db->query($sqlWO)->result();
					foreach($resWO as $rowWO) :
						$WO_CODE = $rowWO->WO_CODE;
					endforeach;
					if($WO_CODE == '')
						$WO_CODE	= "-";
					
					if($OPNH_STAT == 0)
					{
						$OPNH_STATD 	= 'fake';
						$STATCOL		= 'danger';
					}
					elseif($OPNH_STAT == 1)
					{
						$OPNH_STATD 	= 'New';
						$STATCOL		= 'warning';
					}
					elseif($OPNH_STAT == 2)
					{
						$OPNH_STATD 	= 'Confirm';
						$STATCOL		= 'primary';
					}
					elseif($OPNH_STAT == 3)
					{
						$OPNH_STATD 	= 'Approved';
						$STATCOL		= 'success';
					}
					elseif($OPNH_STAT == 4)
					{
						$OPNH_STATD 	= 'Revise';
						$STATCOL		= 'danger';
					}
					elseif($OPNH_STAT == 5)
					{
						$OPNH_STATD 	= 'Rejected';
						$STATCOL		= 'danger';
					}
					elseif($OPNH_STAT == 6)
					{
						$OPNH_STATD 	= 'Close';
						$STATCOL		= 'info';
					}
					elseif($OPNH_STAT == 7)
					{
						$OPNH_STATD 	= 'Awaiting';
						$STATCOL		= 'warning';
					}
					elseif($OPNH_STAT == 9)
					{
						$OPNH_STATD 	= 'Void';
						$STATCOL		= 'danger';
					}
					else
					{
						$OPNH_STATD 	= 'Fake';
						$STATCOL		= 'warning';
					}
					
					if($OPNH_ISCLOSE == 1)
					{
						$OPNH_STATD 	= 'Closed';
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
								<td style="text-align:center; display:none">
									<input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $OPNH_NUM;?>" onClick="getValueNo(this);" />
                                </td>
								<td style="display:none" nowrap>
									<?php echo $OPNH_NUM;?>
                                </td>
								<td> <?php print $OPNH_CODE; ?></td>
								<td style="text-align:center"><?php
										$date = new DateTime($OPNH_DATE);
									   	echo date('d M Y', strtotime($OPNH_DATE));
									?>
                                </td>
								<td style="text-align:left" nowrap><?php echo $WO_CODE; ?></td>
								<td><?php print $JOBDESC; ?> </td>
								<td style="text-align:center" nowrap><?php echo $CREATER_NAME; ?></td>
								<td style="text-align:center">
                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
									<?php 
                                        echo "&nbsp;&nbsp;$OPNH_STATD&nbsp;&nbsp;";
                                     ?>
                                </span>
                                </td>
                                <?php
									$secUpd			= site_url('c_project/c_o180d0bpnm/update/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
									$secPrint		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b4/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
									$secCreateINV	= site_url('c_project/c_o180d0bpnm/cRI180d0eNV/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
									$CollID			= "$OPNH_NUM~$PRJCODE";
									$secDel_OPN 	= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id='.$OPNH_NUM;
								?>
                                <input type="hidden" name="urlPrint<?php echo $myNewNo1; ?>" id="urlPrint<?php echo $myNewNo1; ?>" value="<?php echo $secPrint; ?>">
                                <input type="hidden" name="urlCreateINV<?php echo $myNewNo1; ?>" id="urlCreateINV<?php echo $myNewNo1; ?>" value="<?php echo $secCreateINV; ?>">
								<td style="text-align:center" nowrap>
                                    <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="<?php echo "$Create $Invoice"; ?>" onClick="createInvoice('<?php echo $myNewNo1; ?>')" <?php if($OPNH_STAT != 3) { ?>disabled="disabled" <?php } ?> style="display:none">
                                        <i class="glyphicon glyphicon-export"></i>
                                    </a>
                                    <a href="javascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo1; ?>')">
                                        <i class="glyphicon glyphicon-print"></i>
                                    </a>
                                    <a href="#" onClick="deleteMail('<?php echo $secDel_OPN; ?>')" title="Delete file" class="btn btn-danger btn-xs" <?php if($OPNH_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
						    </tr>
						<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tr>
          <td colspan="9">
            <?php
                echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>');
			?>&nbsp;
                <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
            <?php
				echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-mail-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?>
            </td>
        </tr>
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
		$secIndex_PR	= site_url('c_project/c_o180d0bpnm/get_all_WO/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	?>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
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
  
	function deleteMail(thisVal)
	{
		//alert(thisVal)
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
			alert('a')
			var index_Mail	= "<?php echo $secIndex_PR; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			alert('b')
			var data = $('.form-user').serialize();
			alert('c')
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
	
	function createInvoice(row)
	{
		var url	= document.getElementById('urlCreateINV'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>