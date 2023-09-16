<?php
	/* 
		* Author		= Dian Hermanto
		* Create Date	= 07 Maret 2019
		* File Name	= v_budgetmtring.php
		* Location		= -
	*/

	$this->load->view('template/head');

	$appName 	  = $this->session->userdata('appName');
	$appBody    = $this->session->userdata('appBody');

	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');

	// _global function
	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$decFormat		= 2;

	$Start_DateY 	= date('Y');
	$Start_DateM 	= date('m');
	$Start_DateD 	= date('d');
	$Start_Date		= date('m/d/Y');	
	$End_Date 		= date('m/d/Y');	

	$getDTRX      = "SELECT DATEDIFF(NOW(),JournalH_Date) AS DateTRX 
                    FROM tbl_journalheader ORDER BY JournalH_Date ASC LIMIT 1";
	$resDTRX      = $this->db->query($getDTRX);
	$DateTRX      = '';
	if($resDTRX->num_rows() > 0)
	{
		foreach($resDTRX->result() as $rDTRX):
			$DateTRX    = $rDTRX->DateTRX;
		endforeach;
	}

	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

	$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
	$qProject 		= $this->db->query($getproject)->result();
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
    		
    		if($TranslCode == 'Save')$Save = $LangTransl;
    		if($TranslCode == 'Update')$Update = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
    		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
    		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
    		if($TranslCode == 'Excel')$Excel = $LangTransl;
			if($TranslCode == 'Periode')$Periode = $LangTransl;
    		if($TranslCode == 'ItemGroup')$ItemGroup = $LangTransl;
    		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
    		if($TranslCode == 'All')$All = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$alert0		= "Pilih salah tipe laporan ...!";
    		$alert1		= "Pilih salah satu proyek ...!";
    		$alert2		= "Pilih salah satu kategori atau semua kategori.";
    		$alert3		= "Nama gudang tidak boleh kosong.";
    		$alert4		= "Lokasi gudang tidak boleh kosong.";
    	}
    	else
    	{
    		$alert0		= "Select one of the project type ...";
    		$alert1		= "Select one of the project ...";
    		$alert2		= "Select one or all type category.";
    		$alert3		= "Warehouse Name can not be empty.";
    		$alert4		= "Warehouse location can not be empty.";
    	}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $mnName; ?>
                <small><?php //echo $h2_title; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                  	<form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);">
  						<?php
							$sqlPRJC	= "tbl_project 
												WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
							$resPRJC 	= $this->db->count_all($sqlPRJC);

							$sqlPRJ		= "SELECT PRJCODE, PRJNAME FROM tbl_project
											WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
											ORDER BY PRJCODE";
							$resPRJ 	= $this->db->query($sqlPRJ)->result();
                        ?>
                      	<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                          	<div class="col-sm-10">
                              	<select name="PRJCODE" id="PRJCODE" class="form-control select2">
                                  	<option value="0" > --- </option>
                                  	<?php
      									if($resPRJC>0)
      									{
      										foreach($resPRJ as $rowPRJ) :
      											$PRJCODE1	= $rowPRJ->PRJCODE;
      											$PRJNAME1	= $rowPRJ->PRJNAME;
      											?>
      											<option value="<?php echo $PRJCODE1; ?>"> <?php echo "$PRJCODE1 - $PRJNAME1"; ?> </option>
      											<?php
      										endforeach;
      									}
      									else
      									{
      										?>
                                              	<option value="" > ---- No Data Found ----</option>
                                          	<?php
      									}
									?>
                              	</select>
                          	</div>
                      	</div>
                      	<div class="form-group">
                        	<label for="inputName" class="col-sm-2 control-label"><?php echo $ItemGroup ?> </label>
                        	<div class="col-sm-10">
                              	<select name="ITM_GROUP" id="ITM_GROUP" class="form-control select2">
                                  	<option value="All"> -- Semua -- </option>
									<?php
										$sqlC		= "tbl_itemgroup";
										$resC		= $this->db->count_all($sqlC);
										if($resC > 0)
										{
											$sql		= "SELECT IG_Num, IG_Code, IG_Name
															FROM  tbl_itemgroup";
											$viewGrp	= $this->db->query($sql)->result();
										  	foreach($viewGrp as $row) :
										      	$IG_Num1	= $row->IG_Num;
										      	$IG_Code1 	= $row->IG_Code;
										      	$IG_Name1 	= $row->IG_Name;
										      	?>
										      		<option value="<?php echo $IG_Code1; ?>"><?php echo "$IG_Name1 - $IG_Code1"; ?></option>
										      	<?php
										  	endforeach;
										}
										else
										{
										  	?>
										      	<option value="none">--- None ---</option>
										  	<?php
										}
									?>
                                </select>
                        	</div>
                      	</div>
						<div class="form-group">
                        	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                        	<div class="col-sm-10">
                              	<select class="form-control pull-left select2" id="CFType" name="CFType" style="max-width:150px" onchange="showDesc(this.value)">
                                  	<option value="0" selected> --- </option>
                                  	<option value="1">Summary</option>
                                  	<option value="2">Request</option>
                              	</select>
                        	</div>
                      	</div>
                      	<script type="text/javascript">
                      		function showDesc(theVal)
                      		{
                      			if(theVal == 1)
                      			{
                      				document.getElementById('lap_01').style.display 	= "";
                      				document.getElementById('lap_02').style.display 	= "none";
                      			}
                      			else if(theVal == 2)
                      			{
                      				document.getElementById('lap_01').style.display 	= "none";
                      				document.getElementById('lap_02').style.display 	= "";
                      			}
                      		}
                      	</script>
						<div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                  <input type="text" name="datePeriod" class="form-control pull-left" id="datePeriod" style="width:200px">
                              </div>
                            </div>
                        </div>
                      	<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType; ?></label>
                          	<div class="col-sm-10">
                              	<label>
                                  	<input type="radio" name="viewType" id="viewType" value="0" checked /> 
                                  	<?php echo $WebViewer ?> 
                                  	<input type="radio" name="viewType" id="viewType" value="1" /> 
                                  	<?php echo $Excel ?>
                              	</label>
                          	</div>
                      	</div>
	                   		<div class="form-group" id="lap_01" style="display: none;">
	                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
	                            <div class="col-sm-10">
	                                <div class="alert alert-warning alert-dismissible">
	                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                                    <h4><i class="icon fa fa-exclamation-triangle"></i> Perhatian!</h4>
	                                    Laporan ini akan menampilkan laporan daftar ringkasan penggunaan anggaran dari setiap item sesuai dengan group kategori (MUSTIRO) yang dipilih.
	                                </div>
	                            </div>
	                        </div>
	                   		<div class="form-group" id="lap_02" style="display: none;">
	                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
	                            <div class="col-sm-10">
	                                <div class="alert alert-warning alert-dismissible">
	                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                                    <h4><i class="icon fa fa-ban"></i> Perhatian!</h4>
	                                    Laporan ini akan menampilkan laporan daftar ringkasan permintaan (request) dan penggunaan (realisasi) anggaran dari setiap item sesuai dengan group kategori (MUSTIRO) yang dipilih.
	                                </div>
	                            </div>
	                        </div>
                      	<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                          	<div class="col-sm-10">
                              	<button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
                          	</div>
                      	</div>
                  	</form>
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
  	$(function () {
		var DateTRX  = <?php echo $DateTRX; ?>;
		if(DateTRX != '')
			var startTRX = moment().subtract(DateTRX, 'days');
		else
			var startTRX = moment().subtract(1, 'month');

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

		$('#datePeriod').daterangepicker({
			locale: {
				format: 'DD/MM/YYYY'
			},
			ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			// 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			'Last Transaction': [startTRX.startOf('month'), moment().subtract(1, 'month').endOf('month')],
			'All Periode': [startTRX.startOf('month'), moment()]
			}
		});

	    //Date picker
	    $('#datepicker').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
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

	function target_popup(form)
	{
		var url 	= "<?php echo $form_action; ?>";
		CFTYPE 		= $('#CFType').val();
		PRJCODE 	= $('#PRJCODE').val();
		ITM_GROUP 	= $('#ITM_GROUP').val();
		
		if(CFTYPE == 0)
		{
			swal("<?php echo $alert0; ?>",
			{
				icon:"warning",
			});
			$('#PRJCODE').focus();
			return false;
		}
		
		if(PRJCODE == 0)
		{
			swal("<?php echo $alert1; ?>",
			{
				icon:"warning",
			});
			$('#PRJCODE').focus();
			return false;
		}
		
		if(ITM_GROUP == 0)
		{
			swal("<?php echo $alert2; ?>",
			{
				icon:"warning",
			});
			$('#ITM_GROUP').focus();
			return false;
		}
		
		title = 'Select Item';
		w = 1300;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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