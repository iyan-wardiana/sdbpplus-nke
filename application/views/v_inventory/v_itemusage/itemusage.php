<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 11 Desember 2017
	* File Name		= itemusage.php
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
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar'); 
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;
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
          	$vers   = $this->session->userdata['vers'];

          	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
          	$rescss = $this->db->query($sqlcss)->result();
          	foreach($rescss as $rowcss) :
              	$cssjs_lnk  = $rowcss->cssjs_lnk;
              	?>
                  	<link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              	<?php
          	endforeach;

          	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
          	$rescss = $this->db->query($sqlcss)->result();
          	foreach($rescss as $rowcss) :
              	$cssjs_lnk1  = $rowcss->cssjs_lnk;
              	?>
                  	<script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              	<?php
          	endforeach;
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>
    
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h_title	= 'Penggunaan Material';
			$sureDelete	= "Anda yakin akan menghapus data ini?";
		}
		else
		{
			$h_title	= 'Material Usage';
			$sureDelete	= "Are your sure want to delete?";
		}

		$comp_color = $this->session->userdata('comp_color');
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
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
	    <section class="content-header">
	        <h1>
		        <?php echo "$mnName ($PRJCODE)"; ?>
		        <small><?php echo $PRJNAME; ?></small>
                <div class="pull-right">
	            	<?php
						$secAddURL 	= site_url('c_inventory/c_iu180c16/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						$secAddURLDir= site_url('c_inventory/c_iu180c16/addDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						if($ISCREATE == 1)
						{
							echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>');
						}

                    	//if($ISDELETE == 1)
                    		echo '&nbsp;<button class="btn btn-warning" onClick="syncUM()" title="Sync. Data LPM vs UM"><i class="glyphicon glyphicon-refresh"></i></button>';

						echo anchor("$backURL",'&nbsp;<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
					?>
                </div>
	        </h1>
	    </section>

        <section class="content">
			<div class="row" id="syncUMDESC" style="display: none;">
                <div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
                        Proses ini akan melakukan:<br>
                        1. Menghitung / mengkalkulasi nilai penerimaan (LPM)<br> 
                        2. Menghitung / mengkalkulasi nilai penggunaan (UM)<br>
                        <button class="btn btn-info" onClick="syncUMPROC()"></i>Lanjutkan</button>
                    </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		    <div class="box">
		        <div class="box-body">
		           	<div class="search-table-outter">
			            <table id="example" class="table table-bordered table-striped" width="100%">
			            	<thead>
			               	  	<tr>
			                      	<th style="text-align:center">No.</th>
			                      	<th style="text-align:center"><?php echo $Code ?>  </th>
			                      	<th style="text-align:center"><?php echo $Date ?> </th>
			                      	<th style="text-align:center"><?php echo $JobName; ?> </th>
			                      	<th style="text-align:center"><?php echo $Description ?></th>
			                     	<th style="text-align:center"><?php echo $Status ?> </th>
									<th style="text-align:center" nowrap><?php echo $IsVoid ?> </th>
									<th style="text-align:center" nowrap>&nbsp;</th>
			                	</tr>
			                </thead>
			                <tbody>
			                </tbody>
			                <tfoot>
						    </tfoot>
			            </table>
			      	</div>
			    </div>
				<div id="loading_1" class="overlay" style="display:none">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
		    </div>

			<div class="row">
				<div class="col-md-12" id="idprogbarXY" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXY" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
			
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
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
        "ajax": "<?php echo site_url('c_inventory/c_iu180c16/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [0,1,2,5,6], className: 'dt-body-center' },
						{ "width": "100px", "targets": [1] }
					  ],
		"order": [[ 1, "desc" ]],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );

	function syncUM()
	{
		document.getElementById('syncUMDESC').style.display 	= '';
	}
	
	function syncUMPROC()
	{
		document.getElementById('syncUMDESC').style.display 	= 'none';

		document.getElementById('idprogbar').style.display 			= '';
	    document.getElementById("progressbarXX").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 		= '';
	    document.getElementById("progressbarXY").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		//document.getElementById('loading_1').style.display 			= '';

    	var PRJCODE	= "<?php echo $PRJCODE; ?>";
		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'SYNCUM';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'SYNCUM';
		butSubm.submit();
	}

	function updStat()
	{
		var timer = setInterval(function()
		{
	       	clsBar();
      	}, 2000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display 		= 'none';
		document.getElementById('idprogbarXY').style.display 	= 'none';
	}
	
	function deleteDOC(row)
	{
	    swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlDel'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	swal(response, 
						{
							icon: "success",
						});
		                $('#example').DataTable().ajax.reload();
		            }
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}

	function voidDOC(row)
    {
        document.getElementById('loading_1').style.display = '';   
        swal({
            text: "<?php echo $sureVoid; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
            	swal("Masukan alasan pembatalan", {
				  	content: "input",
				})
				.then((value) => {
					if (value)
					{
					  	var voidNotes	=  value;
					  	var collID1  	= document.getElementById('urlVoid'+row).value;
					  	var collID 		= collID1+'~'+voidNotes;
		                var myarr   	= collID.split("~");
		                var url     	= myarr[0];

		                $.ajax({
		                    type: 'POST',
		                    url: url,
		                    data: {collID: collID},
		                    success: function(response)
		                    {
		                        swal(response, 
		                        {
		                            icon: "success",
		                        })
		                        .then(function()
		                        {
		                            $('#example').DataTable().ajax.reload();
		                            document.getElementById('loading_1').style.display = 'none';
		                        })
		                    }
		                });
		            }
		            else
		            {
		            	swal("Mohon Maaf", "Proses pembatalan dokumen dibatalkan karena Anda tidak memasukan alasan apapun", "warning");
		            }
				});
            } 
            else 
            {
                document.getElementById('loading_1').style.display = 'none';
            }
        });
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