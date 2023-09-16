<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 22 Maret 2019
 * File Name  = r_outstandingPD.php
 * Location   = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
$Start_Date   = "$Start_DateD/$Start_DateM/$Start_DateY";
$End_Date     = "$Start_DateD/$Start_DateM/$Start_DateY";
$datePeriod   = "$Start_Date - $End_Date";
$getDTRX      = "SELECT DATEDIFF(NOW(),JournalH_Date) AS DateTRX
                    FROM tbl_journalheader_pd ORDER BY PD_Date ASC LIMIT 1";
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
          $vers     = $this->session->userdata('vers');

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
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
        
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
            if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'AccountName')$AccountName = $LangTransl;
            if($TranslCode == 'Periode')$Periode = $LangTransl;
            if($TranslCode == 'Select')$Select = $LangTransl;
            if($TranslCode == 'All')$All = $LangTransl;
            if($TranslCode == 'Employee')$Employee = $LangTransl;
            if($TranslCode == 'empName')$empName = $LangTransl;
            if($TranslCode == 'Supplier')$Supplier = $LangTransl;
            if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
            if($TranslCode == 'ViewType')$ViewType = $LangTransl;
            if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
            if($TranslCode == 'Excel')$Excel = $LangTransl;
            if($TranslCode == 'Type')$Type = $LangTransl;
            if($TranslCode == 'Summary')$Summary = $LangTransl;
            if($TranslCode == 'Detail')$Detail = $LangTransl;
            if($TranslCode == 'StartDate')$StartDate = $LangTransl;
        endforeach;
        
        if($LangID == 'IND')
        {
            $alert1     = "Silahkan pilih salah satu proyek.";
            $alert2     = "Silahkan pilih salah satu akun kas / bank";
        }
        else
        {
            $alert1     = "Please select a project.";
            $alert2     = "Please select a cash / bank account";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h1_title; ?>
                <small><?php echo $h2_title; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
                        <?php
                            $sqlPRJC  = "tbl_project 
                                            WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
                            $resPRJC  = $this->db->count_all($sqlPRJC);
                        ?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                            <div class="col-sm-10">
                                <select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;<?php echo $Project; ?>" >
                                <option value="0"> --- </option>
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
                                            <option value="<?php echo $PRJCODE1; ?>"> <?php echo "$PRJCODE1 - $PRJNAME1"; ?> </option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo "$Employee/$Supplier"; ?></label>
                            <div class="col-sm-10">
                                <select name="SPLCODE[]" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo "$empName/$SupplierName"; ?>" multiple>
                                    <option value="All"> Semua </option>
                                    <?php 
                                        // get supplier/Employee
                                            $SPL_NAME       = "";
                                            $s_spl          =  "SELECT * FROM 
                                                                (
                                                                    SELECT Emp_ID AS SPL_CODE, 
                                                                    CONCAT(First_Name, ' ', Last_Name) AS SPL_NAME 
                                                                    FROM tbl_employee WHERE Emp_Status = 1 AND Emp_ID IN (SELECT DISTINCT PERSL_EMPID FROM tbl_journalheader_pd)
                                                                    UNION
                                                                    SELECT SPLCODE AS SPL_CODE, SPLDESC AS SPL_NAME FROM tbl_supplier WHERE SPLSTAT = 1 AND SPLCODE IN (SELECT DISTINCT PERSL_EMPID FROM tbl_journalheader_pd)
                                                                ) tbl_empSPL ORDER BY SPL_NAME ASC";
                                            $r_spl          = $this->db->query($s_spl)->result();
                                            foreach($r_spl as $rw_spl) :
                                                $SPL_CODE   = $rw_spl->SPL_CODE;
                                                $SPL_NAME   = $rw_spl->SPL_NAME;
                                                ?>
                                                    <option value="<?php echo $SPL_CODE; ?>"><?php echo "$SPL_CODE - $SPL_NAME"; ?></option>
                                                <?php
                                            endforeach;
                                    ?>     
                                </select>
                            </div>
                        </div>    
                        <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                            <div class="col-sm-10">
                                <select name="Type" id="Type" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $Type; ?>">
                                    <option value="All"> All </option>
                                    <option value="OUT"> Outstanding </option>
                                    <option value="CURR"> Current </option>
                                    <option value="REALZ"> Realization </option>
                                </select>
                            </div>
                        </div>       
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                  <input type="text" name="datePeriod" class="form-control pull-left" id="daterange-btn" value="<?php echo $datePeriod; ?>" style="width:200px">
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
        </section>
    </body>
</html>

<script>
    $(function () {
        let DateTRX  = <?php echo $DateTRX; ?>;

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
          autoclose: true,
          format: 'dd/mm/yyyy'
        });

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true,
          format: 'dd/mm/yyyy'
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


        $("#SPLCODE").on('change', function(){
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

                    $("#SPLCODE option:nth-child(1)").prop("disabled", false);
                }
                else
                {
                    $(this).select2({
                        maximumSelectionLength: 0
                    });

                    $("#SPLCODE option:nth-child(1)").prop("disabled", true);
                }
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#SPLCODE option:nth-child(1)").prop("disabled", false);
            }
        });
    });
</script>

<script>
    var url = "<?php echo $form_action; ?>";
    function target_popup(form)
    {
        const url       = "<?php echo $form_action; ?>";
        const PRJCODE   = document.getElementById('PRJCODE');
        
        if(PRJCODE.value == '')
        {
          alert("<?php echo $alert1; ?>");
          PRJCODE.focus();
          return false;
        }
        
        title = 'Select Item';
        w = 1200;
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