<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 10 Mei 2022
	* File Name		= v_cho_jrnrevision.php
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

$PRJNAME	= '';
$PRJSTAT	= 0;
$sql 		= "SELECT PRJNAME, PRJSTAT FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
$result		= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
	$PRJSTAT = $row ->PRJSTAT;
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
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'AddNew')$AddNew = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
			if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'yesDel')$yesDel = $LangTransl;
            if($TranslCode == 'cancDel')$cancDel = $LangTransl;
            if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$subTitleD	= "Penggunaan Kas Kantor";
			$sureDelete	= "Anda yakin akan menghapus data ini?";
		}
		else
		{
			$subTitleD	= "Office Cash Payment";
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
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo "$mnName ($PRJCODE)"; ?>
			    <small><?php echo $PRJNAME; ?></small>
	            <div class="pull-right">
		            <?php
						if($PRJSTAT == 1)
		                	echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
						echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
		            ?>
	            </div>
			</h1>
		</section>

        <section class="content">
		    <div class="row">
		    	<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-body">
	                        <div class="col-sm-4">
	                          	<label for="inputName" class="control-label"><?php echo $SupplierName ?> </label>
                            	<select name="SPLCODE" id="SPLCODE" class="form-control select2" style="width: 100%" onChange="grpVC(this.value)">
                            		<option value=""> --- </option>
									<?php
										$s_SPL	= "SELECT DISTINCT B.SPLCODE, B.SPLDESC FROM tbl_journalheader_revision A
													INNER JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE ORDER BY B.SPLDESC ASC";
										$r_SPL	= $this->db->query($s_SPL)->result();
                                        foreach($r_SPL as $rw_SPL) :
                                            $SPLCODE	= $rw_SPL->SPLCODE;
                                            $SPLDESC 	= $rw_SPL->SPLDESC;
                                            ?>
                                            <option value="<?php echo $SPLCODE; ?>"><?php echo "$SPLCODE - $SPLDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                          	<div class="col-sm-2">
	                          	<label for="inputName" class="control-label">Status</label>
                            	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="grpVC(this.value)">
                            		<option value="0"> --- </option>
                            		<option value="1"> New </option>
                            		<option value="2"> Confirmed </option>
                            		<option value="3"> Approve </option>
                            		<option value="4"> Revise </option>
                            		<option value="6"> Closed </option>
                                </select>
                          	</div>
                          	<div class="col-sm-1" style="display: none;">
	                          	<label for="inputName" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            	<button class="btn btn-primary" type="button" id="btnGrp" name="btnGrp" onClick="grpVC()">
	                        		<i class="glyphicon glyphicon-ok"></i>
	                        	</button>
                          	</div>
	                    </div>
	            	</div>
		        </div>
		    </div>
		    <script type="text/javascript">
		    	function grpVC()
		    	{
		    		var SPLC 	= document.getElementById('SPLCODE').value;
		    		var GSTAT 	= document.getElementById('GEJ_STAT').value;

		    		$('#example').DataTable({
		    			"destroy": true,
				        "processing": true,
				        "serverSide": true,
						//"scrollX": false,
						"autoWidth": true,
						"filter": true,
				        "ajax": "<?php echo site_url('c_finance/c_cho70d18/get_AllDataJRNREVISIONGRP/?id='.$PRJCODE.'&jrnType='.$jrnType)?>"+'&SPLC='+SPLC+'&GSTAT='+GSTAT,
				        "type": "POST",
						//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
						"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
						"columnDefs": [	{ targets: [0,2,5,6], className: 'dt-body-center' },
										{ "width": "100px", "targets": [1] }
									  ],
						"order": [[ 2, "desc" ]],
						"language": {
				            "infoFiltered":"",
				            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
				        },
					});
		    	}
		    </script>
		  	<div class="box">
				<div class="box-body">
					<div class="search-table-outter">
				      	<table id="example" class="table table-bordered table-striped" width="100%">
							<thead>
					            <tr>
					                <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
					                <th style="vertical-align:middle; text-align:center" width="14%" nowrap><?php echo $Code; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $Date; ?>  </th>
					                <th width="48%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Description; ?>  </th>
					                <th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $TotalAmount; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="8%" nowrap>ID</th>
					                <th style="vertical-align:middle; text-align:center" width="4%" nowrap><?php echo $Status; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="4%" nowrap>&nbsp;</th>
					          </tr>
					        </thead>
					        <tbody>
					        </tbody>
					        <tfoot>
					        </tfoot>
				   		</table>
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
		"destroy": true,
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_finance/c_cho70d18/get_AllDataJRNREVISION/?id='.$PRJCODE.'&jrnType='.$jrnType)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [0,2,5,6], className: 'dt-body-center' },
						{ "width": "100px", "targets": [1] }
					  ],
		"order": [[ 1, "desc" ]],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	});
	
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
                var collID  = document.getElementById('urlDel'+row).value;
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

    function viewHistpaid(row)
    {
        var url = document.getElementById('urlViewpaid'+row).value;
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