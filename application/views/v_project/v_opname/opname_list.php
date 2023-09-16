<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 1 Februari 2018
	* File Name		= opname_list.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
$PRJNAME    = '';
$PRJHO      = '';
$sql        = "SELECT PRJNAME, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result     = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME    = $row->PRJNAME;
    $PRJHO     = $row->PRJCODE_HO;
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
			if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'yesDel')$yesDel = $LangTransl;
            if($TranslCode == 'cancDel')$cancDel = $LangTransl;
            if($TranslCode == 'Budget')$Budget = $LangTransl;
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
    <style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	    a#paidval:link  {
        color: #777;
	    }
	    a#paidval:hover  {
	        color: #777;
	        font-weight: bold;
	    }
	    a#paidval:active  {
	        color: #777;
	        font-weight: normal;
	    }
	</style>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>"> 
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
			    <small><?php echo $PRJNAME; ?></small>
			    <div class="pull-right">
		            <?php
		                echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>');
					?>
		                <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
		            <?php
						echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-mail-reply"></i></button>');
		            ?>
	                <button class="btn btn-warning" type="button" title="Sembunyikan Dok. Close / Selesai" onClick="hideDocCls()" id="btnCls"><i class="fa fa-eye-slash"></i></button>
	                <button class="btn btn-warning" type="button" title="Tampilkan Dok. Close / Selesai" onClick="showDocShw()" id="btnShw" style="display: none;"><i class="fa fa-eye"></i></button>
	            </div>
		 	</h1>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label"><?=$Budget?></label>
                                <select name="PRJCODEA" id="PRJCODEA" class="form-control select2" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_BUDG     = "SELECT DISTINCT PRJCODE, PRJPERIOD FROM tbl_project WHERE PRJCODE_HO = '$PRJHO'";
                                        $r_BUDG     = $this->db->query($s_BUDG)->result();
                                        foreach($r_BUDG as $rw_BUDG) :
                                            $PRJCODEA    = $rw_BUDG->PRJCODE;
                                            $PRJPERIODA  = $rw_BUDG->PRJPERIOD;
                                            ?>
                                            <option value="<?=$PRJCODEA?>" ><?=$PRJPERIODA?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label"><?php echo $SupplierName ?> </label>
                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPL  = "SELECT DISTINCT B.SPLCODE, B.SPLDESC FROM tbl_opn_header A
                                                    LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE ORDER BY B.SPLDESC ASC";
                                        $r_SPL  = $this->db->query($s_SPL)->result();
                                        foreach($r_SPL as $rw_SPL) :
                                            $SPLCODE    = $rw_SPL->SPLCODE;
                                            $SPLDESC    = $rw_SPL->SPLDESC;
                                            ?>
                                            <option value="<?php echo $SPLCODE; ?>"><?php echo "$SPLCODE - $SPLDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">No. SPK</label>
                                <select name="WO_CODE" id="WO_CODE" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_WO  = "SELECT DISTINCT A.WO_CODE FROM tbl_opn_header A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.WO_CODE ASC";
                                        $r_WO  = $this->db->query($s_WO)->result();
                                        foreach($r_WO as $rw_WO) :
                                            $WO_CODE    = $rw_WO->WO_CODE;
                                            ?>
                                            <option value="<?php echo $WO_CODE; ?>"><?php echo "$WO_CODE"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Status</label>
                                <select name="OPNH_STAT" id="OPNH_STAT" class="form-control select2" onChange="grpData(this.value)">
                                    <option value="0"> --- </option>
                                    <option value="1"> New </option>
                                    <option value="2"> Confirmed </option>
                                    <option value="3"> Approve </option>
                                    <option value="4"> Revise </option>
                                    <option value="6"> Closed </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                function grpData()
                {
                    var SRC     = "";
                    var PROJECT = document.getElementById('PRJCODEA').value;
                    var SPLC    = document.getElementById('SPLCODE').value;
                    var WOCODE 	= document.getElementById('WO_CODE').value;
                    var DSTAT   = document.getElementById('OPNH_STAT').value;

                    $('#example').DataTable( {
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataGRP/?id='.$PRJCODE)?>"+'&SPLC='+SPLC+'&DSTAT='+DSTAT+'&SRC='+SRC+'&WOCODE='+WOCODE+'&PROJECT='+PROJECT,
				        "type": "POST",
						//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
						"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
						"columnDefs": [	{ targets: [4,5], className: 'dt-body-center' },
										{ "width": "100px", "targets": [1] }
									  ],
				        "order": [[ 0, "desc" ]],
						"language": {
				            "infoFiltered":"",
				            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
				        },
                    } );
                }
            </script>
			<div class="box">
				<div class="box-body">
					<div class="search-table-outter">
					    <table id="example" class="table table-bordered table-striped" width="100%">
							<thead>
					            <tr>
					                <th style="vertical-align:middle; text-align:center" width="12%" nowrap><?php echo $Code; ?>  </th>
					                <th style="vertical-align:middle; text-align:center" width="20%" nowrap>Mandor / Sub.</th>
					                <th style="vertical-align:middle; text-align:center" width="20%" nowrap><?php echo $SPKCode; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="30%" ><?php echo $Description; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="10%" nowrap><?php echo $CreatedBy; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%" nowrap><?php echo $Status; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%" nowrap>&nbsp;</th>
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
            <?php
                $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefEmp_ID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>

<script>
    $(function ()
    {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            },
            function (start, end) {
              $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true
        });

        //Date picker
        $('#datepicker2').datepicker({
          autoclose: true
        });

        //Date picker
        $('#datepicker3').datepicker({
          autoclose: true
        });

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
    });

	$(document).ready(function() {
	    $('#example').DataTable( {
	        "bDestroy": true,
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllData/?id='.$PRJCODE)?>",
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [4], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						  ],
	        "order": [[ 0, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});
    
    function hideDocCls()
    {
        document.getElementById('btnCls').style.display     = 'none';
        document.getElementById('btnShw').style.display     = '';

        $('#example').DataTable( {
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataSH/?id='.$PRJCODE.'&ISCLS=')?>"+1,
            "type": "POST",
            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
           "columnDefs": [	{ targets: [4,5], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
                          ],
            "order": [[ 0, "desc" ]],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    }
    
    function showDocShw()
    {
        document.getElementById('btnCls').style.display     = '';
        document.getElementById('btnShw').style.display     = 'none';
        
        $('#example').DataTable( {
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataSH/?id='.$PRJCODE.'&ISCLS=')?>"+0,
            "type": "POST",
            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [	{ targets: [4,5], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
                          ],
            "order": [[ 0, "desc" ]],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    }
    
    function deleteDOC(row)
    {
        document.getElementById('loading_1').style.display = ''; 
        swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlDel'+row).value;
                var myarr   = collID.split("~");

                var url     = myarr[0];

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {collID: collID},
                    success: function(response)
                    {
                        swal('Opname '+response+' sudah dihapus.', 
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
                document.getElementById('loading_1').style.display = 'none';
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
		w = 1200;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function printD3(row)
	{
		var url	= document.getElementById('urlPrint3'+row).value;
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