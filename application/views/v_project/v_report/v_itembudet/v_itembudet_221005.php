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

$DefEmp_ID    = $this->session->userdata['Emp_ID'];

date_default_timezone_set("Asia/Jakarta");

$Start_DateY  = date('Y');
$Start_DateM  = date('m');
$Start_DateD  = date('d');
$Start_Date   = "$Start_DateM/$Start_DateD/$Start_DateY";
$End_Date     = "$Start_DateM/$Start_DateD/$Start_DateY";
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
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>
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
            <div class="box box-primary">
                <div class="box-header with-border" style="display:none">               
                  <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">  
                    <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                        <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                    </form>
                    <form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);">
                        <?php
                            $sqlPRJC  = "tbl_project 
                                            WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
                            $resPRJC  = $this->db->count_all($sqlPRJC);
                        ?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                            <div class="col-sm-10">
                                <select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ProjectName; ?>">
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
                                <select name="JOBPARENT" id="JOBPARENT" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobParent; ?>">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ItemName; ?></label>
                            <div class="col-sm-10">
                                <select name="ITM_CODE[]" id="ITM_CODE" class="form-control select2" multiple data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ItemName; ?>">
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $TransType; ?></label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="TransType" id="TransType" value="0" checked /> 
                                    <?php echo "Request"; ?> &nbsp;&nbsp;
                                    <input type="radio" name="TransType" id="TransType" value="1" /> 
                                    <?php echo "Realisasi"; ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                  <input type="hidden" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php echo $End_Date; ?>" size="10" style="width:150px" >
                                  <input type="text" name="datePeriod" class="form-control pull-left" id="daterange-btn" style="width:200px">
                              </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType; ?></label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="viewType" id="viewType" value="0" checked /> 
                                    <?php echo $WebViewer ?> &nbsp;&nbsp;
                                    <input type="radio" name="viewType" id="viewType" value="1" /> 
                                    <?php echo $Excel ?>
                                </label>
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
    $(".select2").select2();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
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
                'Last Transaction': [moment().subtract(DateTRX, 'days').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'All Periode': [moment().subtract(DateTRX, 'days').startOf('month'), moment()]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
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

    $("#ITM_CODE").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 'All')
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#ITM_CODE option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#ITM_CODE option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#ITM_CODE option:nth-child(1)").prop("disabled", false);
        }
    });

    getJOBPARENT = function(){
        let PRJCODE = $('#PRJCODE').val();
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
                    dataOpt += '<option value="'+JOBCODEID+'" '+disabled+'>'+JOBLEVP+''+JOBCODEID+' - '+JOBDESC+'</option>';
                }

                $('#JOBPARENT').html(dataOpt);
            }
        })
    }

    getITM_CODE = function() {
        let PRJCODE     = $('#PRJCODE').val();
        let JOBPARENT    = $('#JOBPARENT').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("c_project/c_r3p/getITM_CODE") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE, JOBPARENT:JOBPARENT},
            success: function(data) {
                // console.log(data);
                let lnData  = data.length;
                let dataOpt = '';
                if(lnData != 0) dataOpt = '<option value="All">Semua</option>';
                for(let i=0; i<lnData; i++) {
                    let ITM_CODE = data[i].JOBCODEID;
                    let ITM_NAME = data[i].JOBDESC;
                    dataOpt += '<option value="'+ITM_CODE+'">'+ITM_CODE+' - '+ITM_NAME+'</option>';
                }

                $('#ITM_CODE').html(dataOpt);
            }
        });
    }
    
    $('#PRJCODE').on('change', function(){
        getJOBPARENT();
    });

    $('#JOBPARENT').on('change', function(){
        getITM_CODE(); 
    });
    
  });
  
  function selPRJ(PRJCODE) 
  {
    PRJCODE = document.getElementById("PRJCODE").value;
    document.getElementById("PRJCODE1").value = PRJCODE;
    document.frmsrch1.submitSrch1.click();
  }
  
  function chgType(thisVal)
  {
    var type  = thisVal.value;
    PRJCODE = document.getElementById("PRJCODE").value;
    CFType1 = document.getElementById("CFType").value;
    
    PRJCODE = $('#PRJCODE').val();
    if(PRJCODE == '')
    {
      alert("<?php echo $alert1; ?>");
      $('#PRJCODE').focus();
      return false;
    }
    
    document.getElementById("PRJCODE1").value   = PRJCODE;
    document.getElementById("CFType1").value  = CFType1;
    document.frmsrch1.submitSrch1.click();
  }
  
  function target_popup(form)
  {
    var url = "<?php echo $form_action; ?>";
    
    PRJCODE = $('#PRJCODE').val();
    if(PRJCODE == '')
    {
      alert("<?php echo $alert1; ?>");
      $('#PRJCODE').focus();
      return false;
    }
    
    JOBPARENT = $('#JOBPARENT').val();
    if(JOBPARENT == '')
    {
      alert("<?php echo $alert3; ?>");
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