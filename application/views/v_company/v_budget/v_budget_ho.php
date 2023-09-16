<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 13 Desember 2021
	* File Name		= v_budget_ho.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

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

$PRJSELECT		= $PRJCODE;
$PRJCODE		= $PRJCODE;
$sql 			= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 		= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME 	= $row ->PRJNAME;
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

	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
			if($TranslCode == 'CompanyCode')$CompanyCode = $LangTransl;
			if($TranslCode == 'BudCode')$BudCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Periode')$Periode = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'SITotal')$SITotal = $LangTransl;
			if($TranslCode == 'ProgressBar')$ProgressBar = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'BudList')$BudList = $LangTransl;
			if($TranslCode == 'Budgeting')$Budgeting = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'NoBudg')$NoBudg = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        	if($TranslCode == 'PercentProgress')$PercentProgress = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$sureDelete	= "Anda yakin akan menghapus data ini?";
			$sureShutD	= 'Sistem akan menonaktifkan budget/RAP yang Anda pilih. Yakin?';
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
			$sureShutD	= 'The system will deactivate the budget/RAP you selected. Sure?';
		}
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
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $PRJNAME; ?> 
		    <small><?php echo $mnName; ?> </small>
		  </h1>
		</section>

        <section class="content">
			<div class="row" id="SHUTDOWNRAP" style="display: none;">
                <div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
                        Fungsi ini hanya berfungsi untuk proses pergantian budget dari periode sebelumnya ke periode tahun yang aktif. Proses ini akan melakukan:<br>
                        1. Menonaktifkan budget/RAP tahun sebelumnya, dan hanya akan mengaktifkan tahun saat ini.<br> 
                        2. Menyinkronisasi dokumen SPP, OP, LPM, SPK, Opname, TTK, Voucher dan BP yangmasih outstanding.<br>
                        <button class="btn btn-info" onClick="sDRAP()"></i>Lanjutkan</button>
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
			        	 <input type="hidden" name="SELPRJCODE" id="SELPRJCODE" value="">
			          	<table id="example" class="table table-bordered table-striped" width="100%">
				            <thead>
				                <tr>
									<th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
									<th width="20%" style="text-align:center; vertical-align:middle"><?php echo $Periode ?> </th>
									<th width="30%" style="text-align:center; vertical-align:middle"><?php echo $Remarks ?> </th>
									<th width="30%" style="text-align:center; vertical-align:middle"><?php echo $PercentProgress; ?></th>
									<th width="5%" style="text-align:center; vertical-align:middle" nowrap>Status</th>
									<th width="10%" style="text-align:center; vertical-align:middle">&nbsp;</th>
				                </tr>
				            </thead>
				            <tbody>
				            </tbody>
				            <tfoot>
					        </tfoot>
				        </table>
			        </div>
			        <br>
					<?php
						if($ISCREATE == 1)
						{
                        	$url_add 	= site_url('c_comprof/c_bUd93tL15t/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&pgFrom=HO');
                        	echo anchor("$url_add",'<button class="btn btn-primary">&nbsp;<i class="fa fa-plus"></i></button>&nbsp;&nbsp;');
                        	if($pgFrom == 'HO')
                        		echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>');
		            	}
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
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
	</body>
</html>

<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": true,
		//"autoWidth": false,
		"filter": true,
        "ajax": "<?php echo site_url('c_comprof/c_bUd93tL15t/get_AllDataHO/?id='.$PRJCODE.'&pgFrom='.$pgFrom)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [3,4,5], className: 'dt-body-center' }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );

	function shutDRAP(PRJCODE)
	{
		document.getElementById('SHUTDOWNRAP').style.display 	= '';
		document.getElementById('SELPRJCODE').value 			= PRJCODE;
	}
		
	function sDRAP()
	{
		PRJCODE 	= document.getElementById('SELPRJCODE').value;
	    swal({
            text: "<?php echo $sureShutD; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('SHUTDOWNRAP').style.display 	= 'none';

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				/*document.getElementById('idprogbarXY').style.display = '';
			    document.getElementById("progressbarXY").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";*/
				//document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'SHUTDRAP';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'SHUTDRAP';
				butSubm.submit();

		        /*$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
		            	swal(response, 
						{
							icon: "success",
						});
		            }
		        });*/
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display 		= 'none';
		document.getElementById('idprogbarXX').style.display 	= 'none';
	}
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
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