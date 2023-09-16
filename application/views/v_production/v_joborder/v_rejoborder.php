<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 20 Oktober 2018
	* File Name	= v_joborder.php
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Value')$Value = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
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

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
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
		                <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                    <thead>
		                    <tr>
		                        <!--<th width="4%" rowspan="2" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>-->
		                        <th width="4%" style="vertical-align:middle; text-align:center">&nbsp;</th>
		                        <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $Code ?> </th>
		                        <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
		                        <th width="28%" style="vertical-align:middle; text-align:center"><?php echo $CustName  ?> </th>
		                        <th width="28%" style="vertical-align:middle; text-align:center"><?php echo $Description ?> </th>
		                        <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $Value ?> </th>
		                        <th width="4%" style="vertical-align:middle; text-align:center">Status</th>
		                        <th width="3%" style="vertical-align:middle; text-align:center">&nbsp;</th>
		                    </tr>
		                    </thead>
		                    <tbody>
		                    </tbody>
		                    <tfoot>
		                    <tr>
		                        <td colspan="8">
		                            <?php
										$url_add 	= site_url('c_production/c_rej0b0rd3r/a44_j0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										echo anchor("$url_add",'<button class="btn btn-primary">&nbsp;<i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
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
		                    </tfoot>
		                </table>
		            </div>
		        </div>
		      	<!-- /.box -->
		    </div>
		</section>
	</body>
</html>

<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_production/c_rej0b0rd3r/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [1,2,6], className: 'dt-body-center' },
						{ targets: [5], className: 'dt-body-right' },
						{ "width": "100px", "targets": [1] }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );

	<?php
		$secIdx_DOC	= site_url('c_production/c_rej0b0rd3r/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	?>
  
	function deleteDOC(thisVal)
	{
		var r = confirm("<?php echo $sureDelete; ?>");
		if (r == true) 
		{
			var idxData	= "<?php echo $secIdx_DOC; ?>";
			$.ajax({
				type: 'POST',
				url: thisVal,
				//data: $('#sendData').serialize(),
				success: function(data)
				{
					swal(data)
					$('#example').data.reload();
				}
			});
		}
	}
	
	function printD(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function showMR1(row)
	{
		var MR_NUM	= document.getElementById('MR_NUM'+row).value;
		var url		= document.getElementById('urlMR'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);

		if(MR_NUM != '')
			swal('Request No. : '+MR_NUM)
		else
			window.open(url);
	}
	
	function printQR(row)
	{
		var url	= document.getElementById('urlPrintQR'+row).value;
		w = 400;
		h = 300;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}

	function showMR(row)
	{
		var urlSec	= document.getElementById('urlMR'+row).value;
		$.ajax({
		    url: urlSec,
		    type: 'POST',
		    data: {option : row},
		    success: function(newScaleMcn) 
		    {
				swal(newScaleMcn)
		    }
		});


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