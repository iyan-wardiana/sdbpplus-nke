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
$Start_Date   = "$Start_DateD/$Start_DateM/$Start_DateY";
$End_Date     = "$Start_DateD/$Start_DateM/$Start_DateY";
$datePeriod   = "$Start_Date - $End_Date";

$getDTRX      = "SELECT DATEDIFF(NOW(),WO_DATE) AS DateTRX 
                FROM tbl_wo_header ORDER BY WO_DATE ASC LIMIT 1";
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
        if($TranslCode == 'AccountName')$AccountName = $LangTransl;
        if($TranslCode == 'Periode')$Periode = $LangTransl;
        if($TranslCode == 'Select')$Select = $LangTransl;
        if($TranslCode == 'All')$All = $LangTransl;
        if($TranslCode == 'ViewType')$ViewType = $LangTransl;
        if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
        if($TranslCode == 'Excel')$Excel = $LangTransl;
        if($TranslCode == 'Type')$Type = $LangTransl;
        if($TranslCode == 'Summary')$Summary = $LangTransl;
        if($TranslCode == 'Detail')$Detail = $LangTransl;
        if($TranslCode == 'Supplier')$Supplier = $LangTransl;
        if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
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
                                <!-- <option value="All" > Semua </option> -->
                                <option value="All"> --- </option>
                                    <?php
                                    if($resPRJC>0)
                                    {
                                        $sqlPRJ   = "SELECT PRJCODE, PRJNAME FROM tbl_project
                                                        WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj 
                                                            WHERE Emp_ID = '$DefEmp_ID')
                                                        ORDER BY PRJCODE";
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
                            <label for="inputName" class="col-sm-2 control-label"><?php echo "Kategori SPK"; ?></label>
                            <div class="col-sm-10">
                                <select name="WO_CATEG" id="WO_CATEG" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo "Kategori SPK"; ?>">
                                    <option value="All" > Semua </option>
                                    <?php
                                        $this->db->where_not_in("VendCat_Code", ["B"]);
                                        $getWOCAT = $this->db->get("tbl_vendcat");
                                        if($getWOCAT->num_rows() > 0)
                                        {
                                            foreach($getWOCAT->result() as $rWOCAT):
                                                $VendCat_Code 	= $rWOCAT->VendCat_Code;
                                                $VendCat_Name 	= $rWOCAT->VendCat_Name;
                                                ?>
                                                    <option value="<?=$VendCat_Code?>"><?=$VendCat_Name?></option>
                                                <?php
                                            endforeach;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Supplier; ?></label>
                            <div class="col-sm-10">
                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;<?php echo $SupplierName; ?>">
                                    <option value="All" > Semua </option>
                                    <?php
                                        $sqlSpl = "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = 1 ORDER BY SPLDESC ASC";
                                        $sqlSpl = $this->db->query($sqlSpl)->result();
                                        foreach($sqlSpl as $row) :
                                            $SPLCODE1   = $row->SPLCODE;
                                            $SPLDESC1   = $row->SPLDESC;
                                            ?>
                                                <option value="<?php echo "$SPLCODE1"; ?>">
                                                    <?php echo "$SPLDESC1 - $SPLCODE1"; ?>
                                                </option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label">Indexed by</label>
                            <div class="col-sm-10">
                                <select name="IDXSHOW" id="IDXSHOW" class="form-control select2">
                                    <option value="SPK" > SPK </option>
                                    <option value="PAY" > Pembayaran </option>
                                </select>
                            </div>
                        </div>           
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="datePeriod" class="form-control pull-left" id="daterange-btn" style="width:200px">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="Type" id="Type" class="flat-red" value="0" checked /> 
                                    <?php echo $Summary ?> &nbsp;&nbsp;
                                    <input type="radio" name="Type" id="Type" class="flat-red" value="1" /> 
                                    <?php echo $Detail ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType; ?></label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="viewType" id="viewType" class="flat-red" value="0" checked /> 
                                    <?php echo $WebViewer ?> &nbsp;&nbsp;
                                    <input type="radio" name="viewType" id="viewType" class="flat-red" value="1" /> 
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
    $('#datePeriod').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

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
  
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

    // $("#PRJCODE").on('change', function(){
    //     let select = $(this).select2();
    //     console.log(select);
    //     let val = $(this).val();
    //     if(val !== null)
    //     {
    //         if(val == 'All')
    //         {
    //             $(this).select2({
    //                 maximumSelectionLength: 1
    //             });

    //             $("#PRJCODE option:nth-child(1)").prop("disabled", false);
    //         }
    //         else
    //         {
    //             $(this).select2({
    //                 maximumSelectionLength: 0
    //             });

    //             $("#PRJCODE option:nth-child(1)").prop("disabled", true);
    //         }
    //     }
    //     else
    //     {
    //         $(this).select2({
    //             maximumSelectionLength: 0
    //         });

    //         $("#PRJCODE option:nth-child(1)").prop("disabled", false);
    //     }
    // });
    
  });
  
  function target_popup(form)
  {
    const url       = "<?php echo $form_action; ?>";
    const PRJCODE   = document.getElementById('PRJCODE');
    const SPLCODE   = document.getElementById('SPLCODE');
    
    if(PRJCODE.value == 'All')
    {
      alert("<?php echo $alert1; ?>");
      PRJCODE.focus();
      return false;
    }
    
    /*if(SPLCODE.value == 'All')
    {
      alert("Nama supplier tiak boleh kosong. Pilih semua atau salah satu.");
      SPLCODE.focus();
      return false;
    }*/
    
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
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;
?>