<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= inb_asset_usage.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];

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
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          	$vers   = $this->session->userdata['vers'];

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
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'AssetName')$AssetName = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStat')$ProcessStat = $LangTransl;

		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
		    	<small><?php echo $PRJNAME; ?></small>
			    <div class="pull-right">
                    <?php
                        echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                    ?>
	            </div>
		  	</h1>
	    </section>

		<section class="content">
			<div class="box">
			    <div class="box-body">
			        <div class="search-table-outter">
				    	<table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					        <thead>
					            <tr>
					                <th width="2%">&nbsp;</th>
					                <th width="2%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
					           	  	<th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date ?> </th>
					           	  	<th width="17%" style="text-align:center; vertical-align:middle" nowrap><?php echo $AssetName ?> </th>
					           	  	<th width="28%" style="text-align:center; vertical-align:middle" nowrap><?php echo $JobName ?> </th>
					           	  	<th width="43%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?> </th>
					           	  	<th width="1%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
					           	  	<th width="2%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ProcessStat ?></th>
					           	  	<th width="2%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					       	  	</tr>
				            </thead>
				            <tbody>
				            </tbody>
				   		</table>
    				</div>
				</div>
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
        "ajax": "<?php echo site_url('c_asset/c_453tu55493/get_AllData_1n2/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [1,2,6,7], className: 'dt-body-center' },
						{ "width": "100px", "targets": [1] }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );
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