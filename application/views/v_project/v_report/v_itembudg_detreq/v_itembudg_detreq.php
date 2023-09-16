<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 22 Maret 2019
 * File Name  = v_itembudet.php
 * Location   = -
*/

// $this->load->view('template/head');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$Start_DateY  = date('Y');
$Start_DateM  = date('m');
$Start_DateD  = date('d');
$Start_Date   = date('m/d/Y'); 
$End_Date     = date('m/d/Y');  
$DateTRX      = date('Y-m-d');

$DefEmp_ID    = $this->session->userdata['Emp_ID'];

date_default_timezone_set("Asia/Jakarta");

$Start_DateY  = date('Y');
$Start_DateM  = date('m');
$Start_DateD  = date('d');
$Start_Date   = "$Start_DateM/$Start_DateD/$Start_DateY";
$End_Date     = "$Start_DateM/$Start_DateD/$Start_DateY";
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

<?php
    // $this->load->view('template/topbar');
    // $this->load->view('template/sidebar');
    
    $ISREAD     = $this->session->userdata['ISREAD'];
    $ISCREATE   = $this->session->userdata['ISCREATE'];
    $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
    $ISDWONL    = $this->session->userdata['ISDWONL'];
    $LangID     = $this->session->userdata['LangID'];

    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl      = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;
        
        if($TranslCode == 'Add')$Add = $LangTransl;
        if($TranslCode == 'Edit')$Edit = $LangTransl;
        if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
        if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
        if($TranslCode == 'Select')$Select = $LangTransl;
        if($TranslCode == 'All')$All = $LangTransl;
        if($TranslCode == 'ViewType')$ViewType = $LangTransl;
        if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
        if($TranslCode == 'Excel')$Excel = $LangTransl;
        if($TranslCode == 'TransType')$TransType = $LangTransl;
        if($TranslCode == 'Request')$Request = $LangTransl;
        if($TranslCode == 'Realization')$Realization = $LangTransl;
        if($TranslCode == 'Periode')$Periode = $LangTransl;
        if($TranslCode == 'ItemName')$ItemName = $LangTransl;
        if($TranslCode == 'SelectJob')$SelectJob = $LangTransl;
        if($TranslCode == 'JobName')$JobName = $LangTransl;
        if($TranslCode == 'JobParent')$JobParent = $LangTransl;
        if($TranslCode == 'ReportType')$ReportType = $LangTransl;
        if($TranslCode == 'DetailRequest')$DetailRequest = $LangTransl;
        if($TranslCode == 'DetailRealiz')$DetailRealiz = $LangTransl;
    endforeach;
    
    if($LangID == 'IND')
    {
        $alert1     = "Silahkan pilih salah satu proyek.";
        $alert2     = "Silahkan pilih item.";
        $alert3     = "Silahkan pilih salah satu induk pekerjaan.";
        $AllItem    = "Semua Item";
    }
    else
    {
        $alert1     = "Please select a project.";
        $alert2     = "Please select a item.";
        $alert3     = "Please select a job parent.";
        $AllItem    = "All Item";
    }
    
    $PRJCODE    = '';
    if(isset($_POST['PRJCODE1']))
    {
        $PRJCODE    = $_POST['PRJCODE1'];
    }
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $mnName; ?>
    <small><?php //echo $h2_title; ?></small>
</h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content"> 
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#JobDetail" data-toggle="tab">Per Pekerjaan</a></li>
                    <li><a href="#ItemDetail" data-toggle="tab">Per Item</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="JobDetail">
                        <form class="form-horizontal" method="post" name="frm1" id="frm1" action="<?php echo $form_action; ?>" onSubmit="return targetJOB_popup(this);">
                            <?php
                                $sqlPRJC  = "tbl_project 
                                                WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
                                $resPRJC  = $this->db->count_all($sqlPRJC);
                            ?>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                                <div class="col-sm-10">
                                    <select name="PRJCODE" id="frm1PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ProjectName; ?>">
                                        <option value="">--- None ---</option>
                                        <?php
                                        if($resPRJC>0)
                                        {
                                            $sqlPRJ   = "SELECT PRJCODE, PRJNAME FROM tbl_project
                                                            WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj 
                                                                WHERE Emp_ID = '$DefEmp_ID')
                                                            ORDER BY PRJNAME";
                                            $resPRJ   = $this->db->query($sqlPRJ)->result();
                                            foreach($resPRJ as $rowPRJ) :
                                                $PRJCODE1 = $rowPRJ->PRJCODE;
                                                $PRJNAME1 = $rowPRJ->PRJNAME;
                                                ?>
                                                <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE){?> selected <?php } ?>> <?php echo "$PRJCODE1 - $PRJNAME1"; ?> </option>
                                                <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $JobParent; ?></label>
                                <div class="col-sm-10">
                                    <select name="JOBPARENT" id="frm1JOBPARENT" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobParent; ?>">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ItemName; ?></label>
                                <div class="col-sm-10">
                                    <select name="ITM_CODE[]" id="frm1ITM_CODE" multiple class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ItemName; ?>">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $TransType; ?></label>
                                <div class="col-sm-10">
                                    <label>
                                        <input type="radio" name="TransType" id="frm1TransType" value="1" checked /> 
                                        <?php echo "Detail Pekerjaan"; ?> &nbsp;&nbsp;
                                        <input type="radio" name="TransType" id="frm1TransType" value="2" /> 
                                        <?php echo "Detail Item"; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" name="datePeriod" class="form-control pull-left" id="form1daterange-btn" style="width:200px">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ReportType; ?></label>
                                <div class="col-sm-4">
                                    <select name="ReportType" id="ReportType" class="form-control select2">
                                        <option value="0"><?php echo $DetailRequest ?></option>
                                        <option value="2"><?php echo $DetailRequest ?> - Basic</option>
                                        <option value="1"><?php echo $DetailRealiz ?></option>
                                        <option value="3"><?php echo $DetailRealiz ?> - Basic</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="inputName" class="control-label pull-right"><?php echo $ViewType; ?></label>
                                </div>
                                <div class="col-sm-4">
                                    <select name="viewType" id="viewType" class="form-control select2">
                                        <option value="0"><?php echo $WebViewer ?></option>
                                        <option value="1"><?php echo $Excel ?></option>
                                    </select>
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
                    <div class="tab-pane" id="ItemDetail">
                        <form class="form-horizontal" method="post" name="frm2" id="frm2" action="<?php echo $form_action; ?>" onSubmit="return targetITM_popup(this);">
                            <?php
                                $sqlPRJC  = "tbl_project 
                                                WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
                                $resPRJC  = $this->db->count_all($sqlPRJC);
                            ?>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                                <div class="col-sm-10">
                                    <select name="PRJCODE" id="frm2PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ProjectName; ?>">
                                        <option value="">--- None ---</option>
                                        <?php
                                        if($resPRJC>0)
                                        {
                                            $sqlPRJ   = "SELECT PRJCODE, PRJNAME FROM tbl_project
                                                            WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj 
                                                                WHERE Emp_ID = '$DefEmp_ID')
                                                            ORDER BY PRJNAME";
                                            $resPRJ   = $this->db->query($sqlPRJ)->result();
                                            foreach($resPRJ as $rowPRJ) :
                                                $PRJCODE1 = $rowPRJ->PRJCODE;
                                                $PRJNAME1 = $rowPRJ->PRJNAME;
                                                ?>
                                                <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE){?> selected <?php } ?>> <?php echo "$PRJCODE1 - $PRJNAME1"; ?> </option>
                                                <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ItemName; ?></label>
                                <div class="col-sm-10">
                                    <select name="ITM_CODE" id="frm2ITM_CODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ItemName; ?>">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $JobParent; ?></label>
                                <div class="col-sm-10">
                                    <select name="JOBPARENT[]" id="frm2JOBPARENT" multiple class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobParent; ?>">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $TransType; ?></label>
                                <div class="col-sm-10">
                                    <label>
                                        <input type="radio" name="TransType" id="frm2TransType" value="1" /> 
                                        <?php echo "Detail Pekerjaan"; ?> &nbsp;&nbsp;
                                        <input type="radio" name="TransType" id="frm2TransType" value="2" checked /> 
                                        <?php echo "Detail Item"; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" name="datePeriod" class="form-control pull-left" id="form2daterange-btn" style="width:200px">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ReportType; ?></label>
                                <div class="col-sm-4">
                                    <select name="ReportType" id="ReportType" class="form-control select2">
                                        <option value="0"><?php echo $DetailRequest ?></option>
                                        <option value="1"><?php echo $DetailRealiz ?></option>
                                        <option value="2">Detil Transaksi - Basic</option>
                                        <?php if($DefEmp_ID == 'D15040004221') { ?>
                                            <option value="3">Detil Transaksi - Only Anomali</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="inputName" class="control-label pull-right"><?php echo $ViewType; ?></label>
                                </div>
                                <div class="col-sm-4">
                                    <select name="viewType" id="viewType" class="form-control select2">
                                        <option value="0"><?php echo $WebViewer ?></option>
                                        <option value="1"><?php echo $Excel ?></option>
                                    </select>
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
$(function () {
    let DateTRX  = <?php echo $DateTRX; ?>;
    
    //Initialize Select2 Elements
    $(".select2").select2({
        dropdownAutoWidth : true,
        width: '100%'
    });

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#frm1daterange-btn').daterangepicker(
        {
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
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
            $('#frm1daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    $('#frm2daterange-btn').daterangepicker(
        {
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
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
            $('#frm2daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );
    
    $('#form1daterange-btn').daterangepicker(
        {
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                //'Last Transaction': [moment().subtract(DateTRX, 'days').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'All Periode': [moment().subtract(DateTRX, 'days').startOf('month'), moment()]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
          $('#form1daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );
    
    $('#form2daterange-btn').daterangepicker(
        {
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                //'Last Transaction': [moment().subtract(DateTRX, 'days').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'All Periode': [moment().subtract(DateTRX, 'days').startOf('month'), moment()]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
          $('#form2daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
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

    $("#frm1ITM_CODE").on('change', function(){
        let select = $(this).select2();
        console.log(select[0].value);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 1)
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#frm1ITM_CODE option:eq(0)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#frm1ITM_CODE option:eq(0)").prop("disabled", false);
        }
    });

    $("#frm2JOBPARENT").on('change', function(){
        let select = $(this).select2();
        console.log(select[0].value);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 1)
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#frm2JOBPARENT option:eq(0)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#frm2JOBPARENT option:eq(0)").prop("disabled", false);
        }
    });

    getJOBPARENT = function(){
        let PRJCODE = $('#frm1PRJCODE').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("c_project/c_r3p/getJOBPARENT") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE},
            success: function(data) {
                console.log(data);
                let lnData  = data.length;
                let dataOpt = '';
                if(lnData != 0) dataOpt = '<option value=""></option>';

                for(let i=0; i<lnData; i++) {
                    let JOBLEV      = data[i].IS_LEVEL;
                    let JOBCODEID   = data[i].JOBCODEID;
                    let JOBDESC     = data[i].JOBDESC;
                    let ISLASTH     = data[i].ISLASTH;
                    let ISLAST      = data[i].ISLAST;

                    if(ISLASTH == 0) disabled = "disabled=disabled";
                    else disabled = "";

                    let JOBLEVP     = "";
                    if(JOBLEV == 2) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 3) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 4) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 5) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 6) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 7) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 8) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    if(JOBLEV == 9) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // dataOpt += '<option value="'+JOBCODEID+'" '+disabled+'>'+JOBLEVP+''+JOBCODEID+' - '+JOBDESC+'</option>';
                    dataOpt += '<option value="'+JOBCODEID+'">'+JOBLEVP+''+JOBCODEID+' - '+JOBDESC+'</option>';
                }

                $('#frm1JOBPARENT').html(dataOpt);
            }
        })
    }

    getAllJOBPARENT = function(){
        let PRJCODE = $('#frm2PRJCODE').val();
        let ITM_CODE= $('#frm2ITM_CODE').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("c_project/c_r3p/getAllJOBPARENT") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE,ITM_CODE:ITM_CODE},
            success: function(data) {
                console.log(data);
                let lnData  = data.length;
                let dataOpt = '';
                // if(lnData != 0) dataOpt = '<option value=""></option>';
                if(lnData != 0) dataOpt = '<option value="1">Semua</option>';
                for(let i=0; i<lnData; i++) {
                    let JOBLEV      = data[i].IS_LEVEL;
                    let JOBCODEID   = data[i].JOBCODEID;
                    let JOBDESC     = data[i].JOBDESC;
                    let ISLASTH     = data[i].ISLASTH;
                    let ISLAST      = data[i].ISLAST;

                    if(ISLASTH == 0) disabled = "disabled=disabled";
                    else disabled = "";

                    // let JOBLEVP     = "";
                    // if(JOBLEV == 2) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 3) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 4) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 5) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 6) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 7) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 8) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // if(JOBLEV == 9) JOBLEVP = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    // dataOpt += '<option value="'+JOBCODEID+'" '+disabled+'>'+JOBLEVP+''+JOBCODEID+' - '+JOBDESC+'</option>';
                    dataOpt += '<option value="'+JOBCODEID+'">'+JOBCODEID+' - '+JOBDESC+'</option>';
                }

                $('#frm2JOBPARENT').html(dataOpt);
            }
        })
    }

    getITM_CODE = function() {
        let PRJCODE     = $('#frm1PRJCODE').val();
        let JOBPARENT   = $('#frm1JOBPARENT').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("c_project/c_r3p/getITM_CODE") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE, JOBPARENT:JOBPARENT},
            success: function(data) {
                // console.log(data);
                let lnData  = data.length;
                let dataOpt = '<option value=""></option>';
                if(lnData != 0) dataOpt = '<option value="1">Semua</option>';
                for(let i=0; i<lnData; i++) {
                    let ITM_CODE = data[i].ITM_CODE;
                    let ITM_NAME = data[i].ITM_NAME;
                    dataOpt += '<option value="'+ITM_CODE+'">'+ITM_CODE+' - '+ITM_NAME+'</option>';
                }

                $('#frm1ITM_CODE').html(dataOpt);
            }
        });
    }

    getAllITM_CODE = function() {
        let PRJCODE     = $('#frm2PRJCODE').val();
        let ITM_GROUP   = ['M','U','S','T','I','R','O'];
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("c_project/c_r3p/getAllITM_CODE") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE, ITM_GROUP:ITM_GROUP},
            success: function(data) {
                // console.log(data);
                let lnData  = data.length;
                let dataOpt = '<option value=""></option>';
                // if(lnData != 0) dataOpt = '<option value="1">Semua</option>';
                for(let i=0; i<lnData; i++) {
                    let ITM_CODE = data[i].ITM_CODE;
                    let ITM_NAME = data[i].ITM_NAME;
                    dataOpt += '<option value="'+ITM_CODE+'">'+ITM_CODE+' - '+ITM_NAME+'</option>';
                }

                $('#frm2ITM_CODE').html(dataOpt);
            }
        });
    }

    /* -------------------- HOLD -------------------------------
    getITM_CODE = function() {
        let PRJCODE     = $('#frm1PRJCODE').val();
        let JOBPARENT   = $('#frm1JOBPARENT').val();
        $.ajax({
            type: "POST",
            url: "<?php // echo base_url("c_project/c_r3p/getITM_CODE") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE, JOBPARENT:JOBPARENT},
            beforeSend: function() {
                $('#frm1ITM_CODE optgroup').remove();
                $('#frm1ITM_CODE option').remove();
            },
            success: function(data) {
                console.log(data);
                $('#frm1ITM_CODE').select2({
                    data: data
                });
            }
        });
    }
    ---------------------- END HOLD -------------------------------------------- */
    
    $('#frm1PRJCODE').on('change', function(){
        getJOBPARENT();
        // getITM_CODE();
    });

    $('#frm1JOBPARENT').on('change', function(){
        getITM_CODE(); 
    });
    
    $('#frm2PRJCODE').on('change', function(){
        getAllITM_CODE();
    });

    $('#frm2ITM_CODE').on('change', function(){
        getAllJOBPARENT(); 
    });

  });
  
  function targetJOB_popup(form)
  {
    var url = "<?php echo $form_action; ?>";
    
    PRJCODE1 = $('#frm1PRJCODE').val();
    if(PRJCODE1 == '')
    {
      alert("<?php echo $alert1; ?>");
      if(PRJCODE1 == '') $('#frm1PRJCODE').focus();
      return false;
    }
    
    JOBPARENT1 = $('#frm1JOBPARENT').val();
    if(JOBPARENT1 == '')
    {
      alert("<?php echo $alert3; ?>");
      if(JOBPARENT1 == '') $('#frm1JOBPARENT').focus();
      return false;
    }

    // JOBCODEID = $('#JOBCODEID').val();
    // if(JOBCODEID == '' || JOBCODEID == null)
    // {
    //   alert("<?php echo $alert2; ?>");
    //   return false;
    // }
    
    title = 'Select Item';
    w = 1300;
    h = 550;
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    form.target = 'formpopup';
  }

  function targetITM_popup(form)
  {
    var url = "<?php echo $form_action; ?>";
    
    PRJCODE2 = $('#frm2PRJCODE').val();
    if(PRJCODE2 == '')
    {
      alert("<?php echo $alert1; ?>");
      if(PRJCODE2 == '') $('#frm2PRJCODE').focus();
      return false;
    }
    
    JOBPARENT2 = $('#frm2JOBPARENT').val();
    if(JOBPARENT2 == '')
    {
      alert("<?php echo $alert3; ?>");
      if(JOBPARENT2 == '') $('#frm2JOBPARENT').focus();
      return false;
    }

    // JOBCODEID = $('#JOBCODEID').val();
    // if(JOBCODEID == '' || JOBCODEID == null)
    // {
    //   alert("<?php echo $alert2; ?>");
    //   return false;
    // }
    
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
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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