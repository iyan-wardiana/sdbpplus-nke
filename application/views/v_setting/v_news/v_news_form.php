<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 20 Oktober 2018
 * File Name    = v_prodstep_form.php
 * Location     = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;

$FlagUSER       = $this->session->userdata['FlagUSER'];
$DefEmp_ID      = $this->session->userdata['Emp_ID'];

if($task == 'add')
{
    $PATT_YEAR  = date('Y');
    $PATT_MONTH = date('m');    
    $PATT_DAYS  = date('d');
    
    $sql = "SELECT MAX(NEWSH_PATTNO) as maxNumber 
                FROM tbl_news_header WHERE YEAR(NEWSH_DATE) = $PATT_YEAR AND MONTH(NEWSH_DATE) = $PATT_MONTH";
    $result = $this->db->query($sql)->result();
    
    foreach($result as $row) :
        $myMax = $row->maxNumber;
        $myMax = $myMax+1;
    endforeach; 
        
    $lastPatternNumb = $myMax;
    $lastPatternNumb1 = $myMax;
    $len = strlen($lastPatternNumb);
    $nol = '';
    $Pattern_Length = 5;
    if($Pattern_Length==2)
    {
        if($len==1) $nol="0";
    }
    elseif($Pattern_Length==3)
    {if($len==1) $nol="00";else if($len==2) $nol="0";
    }
    elseif($Pattern_Length==4)
    {if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
    }
    elseif($Pattern_Length==5)
    {if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
    }
    elseif($Pattern_Length==6)
    {if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
    }
    elseif($Pattern_Length==7)
    {if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
    }
    $lastPatternNumb    = $nol.$lastPatternNumb;
    
    $DocNumber      = "$PATT_YEAR$PATT_MONTH$PATT_DAYS$lastPatternNumb";
    $NEWSH_CODE     = $DocNumber;
    $NEWSH_DATE     = date('m/d/Y');
    $NEWS_RECTYPE   = 1;
    $NEWSH_RECEIVER = '';
    $NEWSH_TITLE    = '';
    $NEWSH_CONTENT  = '';
    $NEWSH_CREATER  = $DefEmp_ID;
    $NEWSH_STAT     = 1;
    $NEWSH_PATTNO   = $myMax;
}
else
{
    $NEWSH_CODE     = $default['NEWSH_CODE'];
    $NEWSH_DATE     = $default['NEWSH_DATE'];
    $NEWSH_DATE     = date('m/d/Y', strtotime($default['NEWSH_DATE']));
    $NEWS_RECTYPE   = $default['NEWS_RECTYPE'];
    $NEWSH_RECEIVER = $default['NEWSH_RECEIVER'];       
    $NEWSH_TITLE    = $default['NEWSH_TITLE'];
    $NEWSH_CONTENT  = $default['NEWSH_CONTENT'];
    $NEWSH_CREATER  = $default['NEWSH_CREATER'];
    $NEWSH_STAT     = $default['NEWSH_STAT'];
    $NEWSH_PATTNO   = $default['NEWSH_PATTNO'];
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
        
        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $ISDELETE   = $this->session->userdata['ISDELETE'];
        $LangID     = $this->session->userdata['LangID'];

        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;
                
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
        endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
                <small>&nbsp;</small>
            </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">News ID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="NEWSH_CODE" id="NEWSH_CODE" value="<?php echo $NEWSH_CODE; ?>" style="display:none"/>
                                <input type="text" class="form-control" name="NEWSH_CODE1" id="NEWSH_CODE1" value="<?php echo $NEWSH_CODE; ?>" disabled />
                                <input type="hidden" name="NEWSH_CREATER" id="NEWSH_CREATER" value="<?php echo $NEWSH_CREATER; ?>" />
                                <input type="hidden" name="NEWSH_PATTNO" id="NEWSH_PATTNO" value="<?php echo $NEWSH_PATTNO; ?>" />
                                <input type="hidden" name="isTask" id="isTask" value="<?php echo $task; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Date</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                    </div><input type="text" name="NEWSH_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $NEWSH_DATE; ?>" style="width:100px">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Receiver Type</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <input type="radio" name="NEWS_RECTYPE" id="NEWS_RECTYPE1" value="1" <?php if($NEWS_RECTYPE == 1) { ?> checked <?php } ?> onClick="showRecGroup(1)"> All&nbsp;
                                    <input type="radio" name="NEWS_RECTYPE" id="NEWS_RECTYPE2" value="2" <?php if($NEWS_RECTYPE == 2) { ?> checked <?php } ?> onClick="showRecGroup(2)"> Grouping
                                </div>
                            </div>
                        </div>
                        <script>
                            function showRecGroup(thisVal)
                            {
                                if(thisVal == 1)
                                {
                                    document.getElementById('RecGroup').style.display   = 'none';
                                }
                                else
                                {
                                    document.getElementById('RecGroup').style.display   = '';
                                }
                            }
                        </script>
                        <div id="RecGroup" class="form-group"<?php if($NEWS_RECTYPE == 1) { ?> style="display:none" <?php } ?>>
                            <label for="inputName" class="col-sm-2 control-label">Receiver Group</label>
                            <div class="col-sm-10">
                                <select name="NEWSH_RECEIVER[]" id="NEWSH_RECEIVER" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;News For" style="width: 100%;">
                                <?php
                                    $sqlMG  = "SELECT MG_CODE, MG_NAME FROM tbl_mailgroup_header ORDER BY MG_NAME ASC";
                                    $sqlMG  = $this->db->query($sqlMG)->result();
                                    foreach($sqlMG as $rowMG) :
                                        $MG_CODE1   = $rowMG->MG_CODE;
                                        $MG_NAME1   = $rowMG->MG_NAME;
                                        ?>
                                            <option value="<?php echo "$MG_CODE1"; ?>" <?php if($MG_CODE1 == $NEWSH_RECEIVER) { ?> selected <?php } ?>>
                                                <?php echo "$MG_NAME1"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="NEWSH_TITLE" id="NEWSH_TITLE" value="<?php echo $NEWSH_TITLE; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Contents</label>
                            <div class="col-sm-10">
                                <textarea name="NEWSH_CONTENT" class="form-control" id="NEWSH_CONTENT" style="height:70px" ><?php echo $NEWSH_CONTENT; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Title Picture</label>
                            <div class="col-sm-10">
                                <input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <select name="NEWSH_STAT" id="NEWSH_STAT" class="form-control select2">
                                    <option value="1" <?php if($NEWSH_STAT == 1) { ?> selected <?php } ?>>Enable</option>
                                    <option value="2" <?php if($NEWSH_STAT == 2) { ?> selected <?php } ?>>Disable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                                Other images (Optional)
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Image 1</label>
                            <div class="col-sm-10">
                                <input type="file" name="userfile1" class="filestyle" data-buttonName="btn-primary"/>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Image 2</label>
                            <div class="col-sm-10">
                                <input type="file" name="userfile2" class="filestyle" data-buttonName="btn-primary"/>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Image 3</label>
                            <div class="col-sm-10">
                                <input type="file" name="userfile3" class="filestyle" data-buttonName="btn-primary"/>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Image 4</label>
                            <div class="col-sm-10">
                                <input type="file" name="userfile4" class="filestyle" data-buttonName="btn-primary"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?php
                                    if($ISCREATE == 1)
                                    {
                                        if($task=='add')
                                        {
                                            ?>
                                                <button class="btn btn-primary">
                                                <i class="fa fa-save"></i></button>&nbsp;
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <button class="btn btn-primary" >
                                                <i class="fa fa-save"></i></button>&nbsp;
                                            <?php
                                        }
                                    }
                                
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                ?>
                            </div>
                        </div>
                    </form>
                    <?php
                        $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefEmp_ID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
                </div>
            </div>
        </section>
    </body>
</html>

<script>
    $(function () {
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
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('#datepicker').datepicker({
      autoclose: true
    });
    
    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true,
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
</script>

<script>
    function validateData()
    {
        var NEWS_RECTYPE    = document.getElementById('NEWS_RECTYPE').value;
        var NEWSH_RECEIVER  = document.getElementById('NEWSH_RECEIVER').value;
        var NEWSH_TITLE     = document.getElementById('NEWSH_TITLE').value;
        var NEWSH_TITLE     = document.getElementById('NEWSH_TITLE').value;
        var NEWSH_CONTENT   = document.getElementById('NEWSH_CONTENT').value;
        
        if(NEWS_RECTYPE == 2 && NEWSH_RECEIVER == '')
        {
            alert('Please select receiver group');
            document.getElementById('NEWSH_RECEIVER').focus();
            return false;
        }
        
        if(NEWSH_TITLE == '')
        {
            alert('The title of news can not be empty.');
            document.getElementById('NEWSH_TITLE').focus();
            return false;
        }
        
        if(NEWSH_CONTENT == '')
        {
            alert('The content of news can not be empty.');
            document.getElementById('NEWSH_CONTENT').focus();
            return false;
        }
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