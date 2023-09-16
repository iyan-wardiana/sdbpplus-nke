<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 18 Oktober 2017
	* File Name		= purchase_req.php
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

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$DEPCODE 		= $this->session->userdata['DEPCODE'];

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
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
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
			if($TranslCode == 'Requester')$Requester = $LangTransl;
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
			if($TranslCode == 'yesDel')$yesDel = $LangTransl;
			if($TranslCode == 'cancDel')$cancDel = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= "Pengaturan Departemen Pengguna";
			$alert2		= "Status pengguna belum ditentukan pada departemen manapun, sehingga tidak dapat membuat dokumen ini. Silahkan hubungi admin untuk meminta bantuan.";
		}
		else
		{
			$alert1		= "User department setting";
			$alert2		= "User not yet set department, so can not create this document. Please call administrator to get help.";
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
			    <div class="box-body">
			        <div class="search-table-outter">
			            <table id="example" class="table table-bordered table-striped" width="100%">
			                <thead>
			                    <tr>
			                        <th style="vertical-align:middle; text-align:center" width="3%">&nbsp;</th>
			                        <th style="vertical-align:middle; text-align:center" width="15%" nowrap><?php echo $Code; ?>  </th>
			                        <th width="8%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date; ?>  </th>
			                        <th width="50%" style="vertical-align:middle; text-align:center"><?php echo $Description; ?> </th>
			                        <th style="vertical-align:middle; text-align:center" width="10%" nowrap><?php echo $Requester; ?> </th>
			                        <th style="vertical-align:middle; text-align:center" width="6%" nowrap><?php echo $Status; ?> </th>
			                        <th style="vertical-align:middle; text-align:center" width="6%" nowrap>&nbsp;</th>
			                  	</tr>
			                </thead>
			                <tbody>
			                </tbody>
			                <tfoot>
			                    <div class="alert alert-warning alert-dismissible" <?php if($DEPCODE != '') { ?> style="display: none;" <?php } ?>>
			                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                        <h4><i class="icon fa fa-warning"></i> <?php echo $alert1; ?>!</h4>
			                        <?php echo $alert2; ?>
			                    </div>
			                </tfoot>
			            </table>
			        </div>
			        <br>
                    <?php if($ISCREATE == 1 && $DEPCODE != '') {
                        echo anchor("$addURL",'<button class="btn btn-primary" title="'.$Add.'"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                    }
                    ?>
                        <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
                    <?php
                        echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>');
                    ?>
			    </div>
			</div>
			<!-- ============ START MODAL PRINT OUT =============== -->
			<style type="text/css">
				.modal-body {
				  overflow-y: auto;
				  height: 31.5cm;
				}
				.page {
					width: 20cm;
					height: 29.70cm;
					background-color: white;
					margin: 0.5cm auto;
					box-shadow: 1px -2px 5px 1px;
				}

				@media screen {
					#printSection {
				      display: none;
				  	}
				}

				@media print {
					body * {
				    	visibility:hidden;
				  	}
				  	#printSection, #printSection * {
						visibility: visible;
					}
					#printSection {
						position: absolute;
					    left: 0;
					    top: 0;
					}

					.modal-body {
				  		height: 0;
				  	}

				  	.page {
				  		position: absolute;
				  		width: 0;
				  		height: 0;
				  		margin: 0;
				  	}
				}
			</style>
			<div class="modal fade" id="mdl_print" name='mdl_print' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	            <div class="modal-dialog modal-lg">
		            <div class="modal-content">
		            	<div class="modal-header">
		            		<h3 class="modal-title">SURAT PERMINTAAN PENGADAAN / PENYERAHAN (SPP/P)</h3>
		            	</div>
		                <div class="modal-body">
		                	<div class="page" id="printThis">Content 1</div>
		                	<div class="page" id="printThis2">Content 2</div>
		                </div>
		                <div class="modal-footer">
		                	<button id="btnPrint">Print</button>
		                </div>
			        </div>
			    </div>
			</div>
			<script type="text/javascript">
				function printElement(elem, append, delimiter)
				{
					var domClone = elem.cloneNode(true);

				    var $printSection = document.getElementById("printSection");

				    if (!$printSection) {
				        var $printSection = document.createElement("div");
				        $printSection.id = "printSection";
				        document.body.appendChild($printSection);
				    }

				    if (append !== true) {
				        $printSection.innerHTML = "";
				    }

				    else if (append === true) {
				        if (typeof(delimiter) === "string") {
				            $printSection.innerHTML += delimiter;
				        }
				        else if (typeof(delimiter) === "object") {
				            $printSection.appendChlid(delimiter);
				        }
				    }

				    $printSection.appendChild(domClone);
				}
			</script>

			<!-- ============ END MODAL PRINT OUT =============== -->			
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>

<script>
	$(document).ready(function()
	{
		$('#btnPrint').on('click', function(){
			window.print();
		});

    	$('#example').DataTable(
    	{
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllData/?id='.$PRJCODE)?>",
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,1,2,4,5], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						  ],
        	"order": [[ 2, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});

	<?php
		$secIdx_DOC	= site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	?>
    
    function voidDOC(row)
    {
        swal({
            text: "<?php echo $sureVoid; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlVoid'+row).value;
                var myarr   = collID.split("~");

                var url     = myarr[0];

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
                //...
            }
        });
    }
  
	function deleteDOCA(thisVal)
	{
		var r = confirm("<?php echo $sureDelete; ?>");
		if (r == true) 
		{
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
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	
	function pRn_P0l(row)
	{
		var url	= document.getElementById('urlPOList'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
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