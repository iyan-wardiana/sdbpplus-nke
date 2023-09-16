<?php
/* 
    * Author        = Dian Hermanto
    * Create Date   = 15 Maret 2017
    * File Name     = r_ttkreport.php
    * Location      = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;

$DefEmp_ID      = $this->session->userdata['Emp_ID'];

$Start_DateY    = date('Y');
$Start_DateM    = date('m');
$Start_DateD    = date('d');
// $Start_Date     = "$Start_DateY-$Start_DateM-$Start_DateD";
$Start_Date     = "$Start_DateD/$Start_DateM/$Start_DateY";

$getproject     = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
                    WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject       = $this->db->query($getproject)->result();

$getSupplier    = "SELECT A.SPLCODE, A.SPLDESC FROM tbl_supplier A WHERE SPLSTAT = 1 ORDER BY A.SPLDESC";
$qSupplier      = $this->db->query($getSupplier)->result();
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
            if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
            if($TranslCode == 'ProjectName')$Prjnm = $LangTransl;
            if($TranslCode == 'Select')$Select = $LangTransl;
            if($TranslCode == 'All')$All = $LangTransl;
            if($TranslCode == 'SupplierName')$Splnm = $LangTransl;
            if($TranslCode == 'StartDate')$StartDate = $LangTransl;
            if($TranslCode == 'DateUntilto')$DateUntilto = $LangTransl;
            if($TranslCode == 'Type')$Type = $LangTransl;
            if($TranslCode == 'Summary')$Summary = $LangTransl;
            if($TranslCode == 'Detail')$Detail = $LangTransl;
            if($TranslCode == 'ViewType')$ViewType = $LangTransl;
            if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
            if($TranslCode == 'Excel')$Excel = $LangTransl;
            if($TranslCode == 'VendCategory')$VendCategory = $LangTransl;
            if($TranslCode == 'VCNo')$VCNo = $LangTransl;

        endforeach;
        
        if($LangID = 'IND')
        {
            $alert1     = "Silahkan pilih satu atau lebih proyek.";
            $alert2     = "Silahkan pilih satu atau lebih supplier.";
            $alert3     = "Silahkan pilih satu Kategori pemasok.";
            $alert4     = "Silahkan pilih satu atau lebih voucher.";
            $AllPrj     = "Semua Proyek";
            $AllSpl     = "Semua Pemasok";
            $AllSplC    = "Semua Kategori Pemasok";
            $AllVC      = "Semua Voucher";
            $statDrop   = "Status Penurunan";
            $Droped     = "Sudah Diturunkan";
            $notDroped  = "Belum Diturunkan";
            $DROPNo     = "No. Penurunan";
        }
        else
        {
            $alert1     = "Please select one or all project.";
            $alert2     = "Please select one or all supplier.";
            $alert3     = "Please select one supplier category.";
            $alert4     = "Please select one or all voucher.";
            $AllPrj     = "All Project";
            $AllSpl     = "All Supplier";
            $AllSplC    = "All Supplier Category";
            $AllVC      = "All Voucher";
            $statDrop   = "Droped Status";
            $Droped     = "Droped";
            $notDroped  = "Not Yet Droped";
            $DROPNo     = "Number Droped";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <?php echo $mnName; ?>
            <small><?php echo $h3_title; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Prjnm ?></label>
                            <div class="col-sm-10">
                                <select name="PRJCODE[]" id="PRJCODE" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $Prjnm; ?>" >
                                    <!-- <option value="0" > --- </option> -->
                                    <option value="1" > <?=$AllPrj?> </option>
                                    <?php
                                        foreach($qProject as $row) :
                                            $PRJCODE1   = $row->PRJCODE;
                                            $PRJNAME  = $row->PRJNAME;
                                            ?>
                                                <option value="<?php echo $PRJCODE1; ?>" ><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $VendCategory ?></label>
                            <div class="col-sm-10">
                                <select name="DROP_CATEG[]" id="DROP_CATEG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $VendCategory; ?>" >
                                    <option value="1" > <?=$AllSplC?> </option>
                                    <?php
                                        $this->db->distinct("VendCatG_Code, VendCatG_Name");
                                        $this->db->select("VendCatG_Code, VendCatG_Name");
                                        $getVendCatGroup = $this->db->get("tbl_vendcat");
                                        if($getVendCatGroup->num_rows() > 0)
                                        {
                                            foreach($getVendCatGroup->result() as $rVCG):
                                                $VendCatG_Code  = $rVCG->VendCatG_Code;
                                                $VendCatG_Name  = $rVCG->VendCatG_Name;
                                                ?>
                                                    <option value="<?=$VendCatG_Code?>"><?=$VendCatG_Name?></option>
                                                <?php
                                            endforeach;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Splnm ?></label>
                            <div class="col-sm-10">
                                <select name="SPLCODE[]" id="SPLCODE" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $Splnm; ?>" >
                                    <!-- <option value="0" > --- </option> -->
                                    <option value="1" > <?=$AllSpl?> </option>
                                    <?php
                                        foreach($qSupplier as $rowSPL) :
                                            $SPLCODE  = $rowSPL->SPLCODE;
                                            $SPLDESC  = $rowSPL->SPLDESC;
                                            ?>
                                                <option value="<?php echo $SPLCODE; ?>" ><?php echo "$SPLCODE - $SPLDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="Start_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $Start_Date; ?>" size="10" style="width:120px" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $DateUntilto ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="End_Date" class="form-control pull-left" id="datepicker2" value="<?php echo $Start_Date; ?>" size="10" style="width:120px" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $statDrop ?></label>
                            <div class="col-sm-10">
                                <input type="radio" name="DROP_STAT" id="DROP_STAT0" class="flat-red" value="0" checked /> 
                                <?php echo $notDroped ?>&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="DROP_STAT" id="DROP_STAT1" class="flat-red" value="1" /> 
                                <?php echo $Droped ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $DROPNo; ?></label>
                            <div class="col-sm-10">
                                <select name="DROP_No" id="DROP_No" class="form-control select2" data-placeholder="&nbsp;<?php echo $DROPNo; ?>" >
                                    <!-- <option value="0" > --- </option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $VCNo; ?></label>
                            <div class="col-sm-10">
                                <select name="VOUCHER[]" id="VOUCHER" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $VCNo; ?>" >
                                    <!-- <option value="0" > --- </option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $VCNo; ?></label>
                            <div class="col-sm-10">
                                <select name="VOUCHER_DROP[]" id="VOUCHER_DROP" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $VCNo; ?>" >
                                    <!-- <option value="0" > --- </option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo "Flag Print" ?></label>
                            <div class="col-sm-2">
                                <select name="flagPrint" id="flagPrint" class="form-control select2" onchange="flagPrintDate(this.value)" style="width:160px">
                                    <option value="0" > Belum Diprint </option>
                                    <option value="1" > Sudah Diprint </option>
                                </select>
                            </div>
                            <div class="col-sm-8" id="Print_Date" style="display:none">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="Print_Date" class="form-control pull-left" id="datepicker3" value="<?php echo date('d/m/Y'); ?>" size="10" style="width:120px" >
                                </div>
                            </div>
                            <script type="text/javascript">
                                function flagPrintDate(thisVal) 
                                {
                                    if(thisVal == 0)
                                        $('#Print_Date').hide();
                                    else
                                        $('#Print_Date').show();
                                }
                            </script>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Category ?></label>
                            <div class="col-sm-10">
                                <select name="CFType" id="CFType" class="form-control SELECT2" >
                                    <option value="1" ><?php echo $Summary ?></option>
                                    <option value="2" >Detail</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType ?></label>
                            <div class="col-sm-10">
                                <input type="radio" name="viewType" id="viewType" class="flat-red" value="0" checked /> 
                                <?php echo $WebViewer ?>&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="viewType" id="viewType" class="flat-red" value="1" /> 
                                <?php echo $Excel ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <button type="submit" id="submit" class="btn btn-primary"><i class="glyphicon glyphicon-export"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>&nbsp;
                                <button type="button" id="syncVOC" class="btn btn-warning"><i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;Sync</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Loading (remove the following to stop the loading)-->
                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- end loading -->
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
    //Initialize Select2 Elements
    $(".select2").select2();
    //Datemask dd/mm/yyyyEuro
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money 
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
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (e) {
        getVoucher_Periode();
    });

    $('#datepicker2').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    }).on('changeDate', function (e) {
        getVoucher_Periode();
    });

    // $('#DROP_CATEG').on('change', function(){
    //     getVoucher_Periode();
    // });

    // $('#DROP_No').on('change', function(){
    //     getVoucherDROP_Periode();
    // });

    getVoucher_Periode  = function() {
        let PRJCODE     = $('#PRJCODE').val();
        // let DROP_CATEG  = $('#DROP_CATEG').val();
        let startDate   = $('#datepicker1').val();
        let endDate     = $('#datepicker2').val();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url("c_finance/c_f1nR3p07t/getVoucher") ?>",
            dataType: "JSON",
            data: {PRJCODE:PRJCODE, Start_Date:startDate, End_Date:endDate},
            success: function(data) {
                let lnData  = data.length;
                let dataOpt = '';
                if(lnData != 0)
                    dataOpt = '<option value="1"><?php echo $AllVC; ?></option>';

                for(let i=0; i<lnData; i++) {
                    dataOpt += '<option value="'+data[i].ORD_ID+'">'+data[i].DROP_VOCCODE+'</option>';
                }

                $('#VOUCHER').html(dataOpt);
            }
        });
    }

    // getVoucherDROP_Periode  = function() {
    //     let PRJCODE     = $('#PRJCODE').val();
    //     // let DROP_CATEG  = $('#DROP_CATEG').val();
    //     let startDate   = $('#datepicker1').val();
    //     let endDate     = $('#datepicker2').val();
    //     let DROP_CODE   = $('#DROP_No').val();

    //     $.ajax({
    //         type: "POST",
    //         url: "<?php // echo base_url("c_finance/c_f1nR3p07t/getVoucherDROP") ?>",
    //         dataType: "JSON",
    //         data: {PRJCODE:PRJCODE, DROP_CODE:DROP_CODE, Start_Date:startDate, End_Date:endDate},
    //         success: function(data) {
    //             let lnData  = data.length;
    //             let dataOpt = '';
    //             if(lnData != 0)
    //                 dataOpt = '<option value="1"><?php echo $AllVC; ?></option>';

    //             for(let i=0; i<lnData; i++) {
    //                 dataOpt += '<option value="'+data[i].VOC_NUM+'">'+data[i].VOC_CODE+'</option>';
    //             }

    //             $('#VOUCHER_DROP').html(dataOpt);
    //         }
    //     });
    // }

    $('#datepicker3').datepicker({
      format: 'dd/mm/yyyy',
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

    $("#PRJCODE").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 1)
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#PRJCODE option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#PRJCODE option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#PRJCODE option:nth-child(1)").prop("disabled", false);
        }

        getVoucher_Periode();
    });

    $("#VOUCHER").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 1)
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#VOUCHER option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#VOUCHER option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#VOUCHER option:nth-child(1)").prop("disabled", false);
        }
    });

    // $("#DROP_CATEG").on('change', function(){
    //     let select = $(this).select2();
    //     console.log(select);
    //     let val = $(this).val();
    //     if(val !== null)
    //     {
    //         if(val == 1)
    //         {
    //             $(this).select2({
    //                 maximumSelectionLength: 1
    //             });

    //             $("#DROP_CATEG option:nth-child(1)").prop("disabled", false);
    //         }
    //         else
    //         {
    //             $(this).select2({
    //                 maximumSelectionLength: 0
    //             });

    //             $("#DROP_CATEG option:nth-child(1)").prop("disabled", true);
    //         }
    //     }
    //     else
    //     {
    //         $(this).select2({
    //             maximumSelectionLength: 0
    //         });

    //         $("#DROP_CATEG option:nth-child(1)").prop("disabled", false);
    //     }

    //     getVoucher_Periode();
    // });

    $("#VOUCHER_DROP").on('change', function(){
        let select = $(this).select2();
        console.log(select);
        let val = $(this).val();
        if(val !== null)
        {
            if(val == 1)
            {
                $(this).select2({
                    maximumSelectionLength: 1
                });

                $("#VOUCHER_DROP option:nth-child(1)").prop("disabled", false);
            }
            else
            {
                $(this).select2({
                    maximumSelectionLength: 0
                });

                $("#VOUCHER_DROP option:nth-child(1)").prop("disabled", true);
            }
        }
        else
        {
            $(this).select2({
                maximumSelectionLength: 0
            });

            $("#VOUCHER_DROP option:nth-child(1)").prop("disabled", false);
        }
    });

    $('#VOUCHER_DROP').parents('div.form-group').css("display", "none");
    $('#DROP_No').parents('div.form-group').css("display", "none");
    $('input:radio[name="DROP_STAT"]').on('ifChecked', function(e) {
        let PRJCODE = $('#PRJCODE').val();
        // console.log(PRJCODE);
        // alert('callback = ' + e.type);
        // alert('checked = ' + e.target.checked);
        // alert('value = ' + e.target.value);
        if(e.target.checked == true)
        {
            if(e.target.value == 0)
            {
                $('#DROP_No').parents('div.form-group').css("display", "none");
                $('#VOUCHER_DROP').parents('div.form-group').css("display", "none");
                $('#VOUCHER').parents('div.form-group').css("display", "block");
                getVoucher_Periode();
            }
            else
            {
                $('#DROP_No').parents('div.form-group').css("display", "block");
                $('#VOUCHER_DROP').parents('div.form-group').css("display", "block");
                $('#VOUCHER').parents('div.form-group').css("display", "none");
                // get All DROP_No
                // $.ajax({
                //     type: "POST",
                //     url: "<?php // echo base_url("c_finance/c_f1nR3p07t/getDROP_NO") ?>",
                //     data: {PRJCODE:PRJCODE},
                //     dataType: "JSON",
                //     success: function(callBack)
                //     {
                //         let lnData  = callBack.length;
                //         let dataOpt = '<option value=""></option>';
                //         for(let i=0; i<lnData; i++) {
                //             dataOpt += '<option value="'+callBack[i].DROP_CODE+'">'+callBack[i].DROP_NO+'</option>';
                //         }

                //         $('#DROP_No').html(dataOpt);
                //     }
                // });
            }
        }
    });

    $(":button#syncVOC").on('click', function() {
        $.ajax({
            url:"<?php echo base_url("c_finance/c_f1nR3p07t/syncVOC") ?>",
            beforeSend: function(xhr) {
                // console.log(xhr);
                $('.overlay').css('display', '');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
            success: function(result,status,xhr) {
                console.log("result: "+result+" , status: "+status+ " , xhr: "+xhr);
                if(status == 'success') $('.overlay').css('display', 'none');
            }
        });
    });

  });
</script>
<script>

    var url = "<?php echo $form_action; ?>";
    function target_popup(form)
    {
        PRJCODE         = document.getElementById('PRJCODE').value;
        // SPLCODE      = document.getElementById('SPLCODE').value;
        // DROP_CATEG      = document.getElementById('DROP_CATEG').value;
        VOUCHER         = document.getElementById('VOUCHER').value;
        VOUCHER_DROP    = document.getElementById('VOUCHER_DROP').value;
        DROP_STAT       = $('.checked input:radio[name="DROP_STAT"]').val();

        if(PRJCODE == 0 || PRJCODE == '')
        {
            swal ( "" ,  "<?php echo $alert1; ?>" ,  "warning" )
            return false;
        }

        // if(DROP_CATEG == 0)
        // {
        //     swal ( "" ,  "<?php echo $alert3; ?>" ,  "warning" )
        //     return false;
        // }

        if(DROP_STAT == 0)
        {
            if(VOUCHER == 0)
            {
                swal ( "" ,  "<?php echo $alert4; ?>" ,  "warning" )
                return false;
            }
        }
        else
        {
            if(VOUCHER_DROP == 0)
            {
                swal ( "" ,  "<?php echo $alert4; ?>" ,  "warning" )
                return false;
            }
        }

        title = '<?php echo $mnName; ?>';
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }
    
    var url1 = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
    function exporttoexcel()
    {
        window.open(url1,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
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