<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 23 Maret 2017
	* File Name	= vendor_category.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

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
            if($TranslCode == 'Name')$Name = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
        endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
      	<section class="content-header">
          	<h1>
              	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/supplier_categ.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?> 
              	<small>project</small>
            </h1>
      	</section>
      	<style>
          	.search-table, td, th {
          	border-collapse: collapse;
          	}
          	.search-table-outter { overflow-x: scroll; }
      	</style>
    
    	<section class="content">
          	<div class="box">
              	<div class="box-body">
                  	<div class="search-table-outter">
                      	<table id="example" class="table table-bordered table-striped" width="100%">
                          	<thead>
								<tr>
									<th width="2%">No.</th>
									<th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?></th>
									<th width="25%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Name ?></th>
									<th width="65%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?></th>
									<th width="2%" style="text-align:center; vertical-align:middle" nowrap></th>
								</tr>
                          	</thead>
                          	<tbody>
                      		</tbody>
                      		<tfoot>
	                          	<tr>
	                              	<td colspan="5" style="text-align:left">
                          				<?php
                        					if($ISCREATE == 1)
                        					{
                        						echo anchor("$secAddURL",'<button class="btn btn-primary">&nbsp;<i class="glyphicon glyphicon-plus"></i></button>');
                        					}
                          				?>
	                              	</td>
	                          	</tr>
                          	</tfoot>
                 		</table>
                  	</div>
                	<?php
                		$DefID 		= $this->session->userdata['Emp_ID'];
						$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                		if($DefID == 'D15040004221')
                        	echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
              	</div>
          	</div>
        </section>
  	</body>
</html>

<script>
	$(document).ready(function() 
	{
    	$('#example').DataTable({
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": "<?php echo site_url('c_purchase/c_vendcat/get_AllData')?>",
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,1], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});
	
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