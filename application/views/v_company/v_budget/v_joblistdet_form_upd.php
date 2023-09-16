<?php
/* 
    * Author        = Dian Hermanto
    * Create Date   = 22 Mei 2018
    * File Name = joblistdet_form.php
    * Location      = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$Emp_ID     = $this->session->userdata['Emp_ID'];
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME = $row ->PRJNAME;
endforeach;
$PRJCODEVW  = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$JOBCODEID  = $default['JOBCODEID'];
$JOBCODEIDV = $default['JOBCODEIDV'];
$JOBPARENT  = $default['JOBPARENT'];
$PRJCODE    = $default['PRJCODE'];
$PRJCODE    = $default['PRJCODE'];
$JOBDESC    = $default['JOBDESC'];
$JOBGRP     = $default['JOBGRP'];
$JOBUNIT    = strtoupper($default['JOBUNIT']);
$JOBLEV     = $default['JOBLEV'];
$BOQ_VOLM   = $default['BOQ_VOLM'];
$BOQ_PRICE  = $default['BOQ_PRICE'];
$ITM_VOLM   = $default['JOBVOLM'];
$ITM_PRICE  = $default['PRICE'];
$PRICE      = $default['PRICE'];
$JOBCOST    = $default['JOBCOST'];
$ISLASTH    = $default['ISLASTH'];
$ISLAST     = $default['ISLAST'];
$ITM_GROUP  = $default['ITM_GROUP'];
$ISHEADER   = $default['ISHEADER'];

if($PRICE == 0)
{
    if($BOQ_VOLM == 0)
        $PRICE  = 0;
    else
        $PRICE  = $JOBCOST / $BOQ_VOLM;
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
            $vers     = $this->session->userdata['vers'];

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

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
        
        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>

    <?php
        $this->load->view('template/mna');
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
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Parent')$Parent = $LangTransl;
            if($TranslCode == 'Unit')$Unit = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
            if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'Price')$Price = $LangTransl;
            if($TranslCode == 'Type')$Type = $LangTransl;
            if($TranslCode == 'ItemName')$ItemName = $LangTransl;
            if($TranslCode == 'JobName')$JobName = $LangTransl;
            if($TranslCode == 'JobLevel')$JobLevel = $LangTransl;
            if($TranslCode == 'TopLevel')$TopLevel = $LangTransl;
            if($TranslCode == 'LastLevel')$LastLevel = $LangTransl;
            if($TranslCode == 'JobParent')$JobParent = $LangTransl;
            if($TranslCode == 'JobCode')$JobCode = $LangTransl;
        endforeach;
        
        if($LangID == 'IND')
        {
            $subTitleH  = "Tambah Pekerjaan";
            $subTitleD  = "detail pekerjaan";
            $Invoiced   = " sudah dibuatkan faktur";
            $alert1     = "Anda belum menentukan level angsaran.";
            $alert2     = "Anda belum menentukan induk pekerjaan.";
            $alert3     = "Kode pekerjaan tidak boleh kosong.";
            $alert4     = "Nama pekerjaan tidak boleh kosong.";
            $alert5     = "Anda belum menentukan unit pekerjaan.";
            $alert6     = "Silahkan tentukan level pekerjaan.";
            $miscell    = "Rupa-Rupa";
        }
        else
        {
            $subTitleH  = "Add Job";
            $subTitleD  = "job detail";
            $Invoiced   = " has already been created an invoice.";
            $alert1     = "You have not set a budget level yet.";
            $alert2     = "You have not defined a parent job yet.";
            $alert3     = "Job code can not be empty.";
            $alert4     = "Job description can not be empty.";
            $alert5     = "You have not defined a job unit yet.";
            $alert6     = "Please set level of job.";
            $miscell    = "Miscellaneous";
        }

        $JOBDESCD   = '';
        $sqlJD      = "SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID'";
        $resJD      = $this->db->query($sqlJD)->result();
        foreach($resJD as $rowJD) :
            $JOBDESCD = $rowJD->JOBDESC;
        endforeach;

        $JOBDESCP   = '';
        $JOBDESC1   = '';
        $sqlJDP     = "SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT'";
        $resJDP     = $this->db->query($sqlJDP)->result();
        foreach($resJDP as $rowJDP) :
            $JOBDESCP = $rowJDP->JOBDESC;
        endforeach;
        if($JOBPARENT != '')
            $JOBDESC1   = "$JOBPARENT : $JOBDESCP";

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
                <small><?php echo $subTitleD; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return submitForm()">
                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                        <input type="hidden" name="JOBPARENTB" id="JOBPARENTB" value="<?php echo $JOBPARENT; ?>" />
                        <input type="Hidden" name="rowCount" id="rowCount" value="0">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobParent; ?></label>
                            <div class="col-sm-10">
                              <select name="JOBPARENTX" id="JOBPARENTX" class="form-control select2" style="width: 100%" disabled>
                                    <option value="0"> --- </option>
                                    <?php
                                        $s_BOQ    = "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
                                        $r_BOQ    = $this->db->query($s_BOQ)->result();
                                        foreach($r_BOQ as $rw_BOQ) :
                                            $JOBID      = $rw_BOQ->JOBCODEID;
                                            $JOBDESC    = $rw_BOQ->JOBDESC;
                                            ?>
                                            <option value="<?php echo $JOBID; ?>" <?php if($JOBID == $JOBPARENT) { ?> selected <?php } ?>><?php echo "$JOBID : $JOBDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                                <input type="hidden" class="form-control" style="text-align:left" name="JOBPARENT" id="JOBPARENT" value="<?=$JOBPARENT?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobCode; ?></label>
                            <div class="col-sm-6">
                                <input type="hidden" class="form-control" style="text-align:left" name="JOBCODEIDB" id="JOBCODEIDB" value="<?=$JOBCODEID?>" />
                                <input type="text" class="form-control" style="text-align:left" name="JOBCODEID" id="JOBCODEID" value="<?=$JOBCODEID?>" readonly />
                            </div>
                            <label for="inputName" class="col-sm-2 control-label" title="Induk RAP"><?php echo $LastLevel; ?></label>
                            <div class="col-sm-2">
                                <select name="ISLASTHX" id="ISLASTHX" class="form-control select2" disabled>
                                    <option value=""> --- </option>
                                    <option value="1" <?php if($ISLASTH == 1) { ?> selected <?php } ?>> Ya </option>
                                    <option value="0" <?php if($ISLASTH == 0) { ?> selected <?php } ?>> Bukan </option>
                                </select>
                                <input type="hidden" class="form-control" style="text-align:left" name="ISLASTH" id="ISLASTH" value="<?=$ISLASTH?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label" style="vertical-align:top"><?php echo $JobName; ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" style="text-align:left" name="JOBDESC" id="JOBDESC" value="<?=$JOBDESCD?>" />
                            </div>
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Unit; ?></label>
                            <div class="col-sm-2">
                                <select name="JOBUNIT" id="JOBUNIT" class="form-control select2">
                                    <option value="0"> --- </option>
                                    <?php
                                        $sqlUnit    = "SELECT * FROM tbl_unittype";
                                        $resUnit    = $this->db->query($sqlUnit)->result();
                                        foreach($resUnit as $rowUM) :
                                            $Unit_Type_Code = $rowUM->Unit_Type_Code;
                                            $UMCODE         = strtoupper($rowUM->Unit_Type_Code);
                                            $Unit_Type_Name = $rowUM->Unit_Type_Name;
                                            ?>
                                            <option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $JOBUNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">BoQ</label>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Volume</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="inputName" class="control-label"><?=$Price?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="BOQ_VOLM1" id="BOQ_VOLM1" value="<?php echo number_format($BOQ_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolmBQ(this);" readonly />
                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="BOQ_VOLM" id="BOQ_VOLM" value="<?=$BOQ_VOLM?>" />
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="BOQ_PRICE1" id="BOQ_PRICE1" value="<?php echo number_format($BOQ_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrcBQ(this);" readonly />
                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="BOQ_PRICE" id="BOQ_PRICE" value="<?=$BOQ_PRICE?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">RAP</label>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Volume</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="inputName" class="control-label"><?=$Price?></label>
                            </div>  
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_VOLM1" id="ITM_VOLM1" value="<?php echo number_format($ITM_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolmRAP(this);" readonly />
                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_VOLM" id="ITM_VOLM" value="<?=$ITM_VOLM?>" />
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE1" id="ITM_PRICE1" value="<?php echo number_format($ITM_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrcRAP(this);" readonly />
                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE" id="ITM_PRICE" value="<?=$ITM_PRICE?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" type="button" onClick="saveData()">
                                    <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                </button>&nbsp;
                                <?php
                                    //echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                ?>
                            </div>
                        </div>
                    </form>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
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
        $('#datepicker').datepicker({
          autoclose: true
        });
        
        //Date picker
        $('#datepicker1').datepicker({
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

    function saveData()
    {
        var JOBPARENT   = document.getElementById('JOBPARENT').value;
        var JOBCODEID   = document.getElementById('JOBCODEID').value;
        var ISLASTH     = document.getElementById('ISLASTH').value;
        var JOBDESC     = document.getElementById('JOBDESC').value;
        var JOBUNIT     = document.getElementById('JOBUNIT').value;

        if(JOBPARENT == 0)
        {
            swal('<?php echo $alert2; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBPARENT').focus();
            });
            return false;
        }

        if(JOBCODEID == '')
        {
            swal('<?php echo $alert3; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBCODEID').focus();
            });
            return false;
        }

        if(ISLASTH == '')
        {
            swal('<?php echo $alert6; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('ISLASTH').focus();
            });
            return false;
        }

        if(JOBDESC == '')
        {
            swal('<?php echo $alert4; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBDESC').focus();
            });
            return false;
        }

        if(JOBUNIT == 0)
        {
            swal('<?php echo $alert5; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBUNIT').focus();
            });
            return false;
        }

        var formData    = {
                            PRJCODE     : $("#PRJCODE").val(),
                            JOBPARENT   : JOBPARENT,
                            JOBPARENTB  : $("#JOBPARENTB").val(),
                            JOBCODEID   : JOBCODEID,
                            JOBCODEIDB  : $("#JOBCODEIDB").val(),
                            ISLASTH     : ISLASTH,
                            JOBDESC     : JOBDESC,
                            JOBUNIT     : JOBUNIT,
                            BOQ_VOLM    : $("#BOQ_VOLM").val(),
                            BOQ_PRICE   : $("#BOQ_PRICE").val(),
                            ITM_VOLM    : $("#ITM_VOLM").val(),
                            ITM_PRICE   : $("#ITM_PRICE").val()
                        };
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url('c_comprof/c_bUd93tL15t/svJL')?>",
            data: formData,
            success: function(response)
            {
                //swal(response)
                swal('Data sudah diupdate. Silahkan refresh halaman RAP untuuk melihat perubahan.',
                {
                    icon:"warning",
                })
                .then(function()
                {
                    swal.close();
                    window.close();
                })
            }
        });
    }
    
    function isIntOnlyNew(evt)
    {
        if (evt.which){ var charCode = evt.which; }
        else if(document.all && event.keyCode){ var charCode = event.keyCode; }
        else { return true; }
        return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
    }
  
    function doDecimalFormat(angka) {
        var a, b, c, dec, i, j;
        angka = String(angka);
        if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
        } else { a = angka; dec = -1; }
        b = a.replace(/[^\d]/g,"");
        c = "";
        var panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--) {
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
            else c = b.substr(i-1,1) + c;
        }
        if(dec == -1) return angka;
        else return (c + '.' + dec); 
    }

    function RoundNDecimal(X, N) {
        var T, S=new String(Math.round(X*Number("1e"+N)))
        while (S.length<=N) S='0'+S
        return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
    }
    
    function chgLevel(thisValue)
    {
        if(thisValue == 1)
        {
            //document.getElementById('showITEM').style.display         = 'none';
            document.getElementById('ISHEADER').style.display       = 'none';
            document.getElementById('showJobParent').style.display  = 'none';
        }
        else
        {
            //document.getElementById('showITEM').style.display         = '';
            document.getElementById('ISHEADER').style.display       = '';
            document.getElementById('showJobParent').style.display  = '';
        }
    }
    
    function chgVolmBQ(thisVal)
    {
        var decFormat   = document.getElementById('decFormat').value;
        var BOQ_VOLM    = eval(document.getElementById('BOQ_VOLM1').value.split(",").join(""));
        
        document.getElementById('BOQ_VOLM').value = parseFloat(Math.abs(BOQ_VOLM));
        document.getElementById('BOQ_VOLM1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(BOQ_VOLM)),decFormat));
    }
    
    function chgPrcBQ(thisVal)
    {
        var decFormat   = document.getElementById('decFormat').value;
        var BOQ_PRICE1  = eval(document.getElementById('BOQ_PRICE1').value.split(",").join(""));
        
        document.getElementById('BOQ_PRICE').value = parseFloat(Math.abs(BOQ_PRICE1));
        document.getElementById('BOQ_PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(BOQ_PRICE1)),decFormat));
    }
    
    function chgVolmRAP(thisVal)
    {
        var decFormat   = document.getElementById('decFormat').value;
        var ITM_VOLM    = eval(document.getElementById('ITM_VOLM1').value.split(",").join(""));
        
        document.getElementById('ITM_VOLM').value = parseFloat(Math.abs(ITM_VOLM));
        document.getElementById('ITM_VOLM1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));
    }
    
    function chgPrcRAP(thisVal)
    {
        var decFormat   = document.getElementById('decFormat').value;
        var ITM_PRICE1  = eval(document.getElementById('ITM_PRICE1').value.split(",").join(""));
        
        document.getElementById('ITM_PRICE').value = parseFloat(Math.abs(ITM_PRICE1));
        document.getElementById('ITM_PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE1)),decFormat));
    }
    
    function chgType(isHeader)
    {
        if(isHeader == 1)
        {
            //document.getElementById('showITEM').style.display         = 'none';
            document.getElementById('showJobParent').style.display  = '';
        }
        else
        {
            //document.getElementById('showITEM').style.display         = '';
            document.getElementById('showJobParent').style.display  = '';
        }
    }
    
    $(document).ready(function()
    {
        /*$("#JOBLEV").change(function()
        {*/
            var PRJCODE     = document.getElementById("PRJCODE").value;
            //var deptid1   = $(this).val();
            var deptid      = PRJCODE;
            /*if(deptid1 == 1)
            {
                document.getElementById("JOBCODEID").value  = '<?php echo $NEWCODE; ?>';
            }
            else
            {*/
                /*$.ajax({
                    url: '<?php echo site_url('c_comprof/c_bUd93tL15t/getJOBLIST2/?id=')?>',
                    type: 'post',
                    data: {depart:deptid},
                    dataType: 'json',
                    success:function(response)
                    {
                        var len = response.length;
                        
                        $("#JOBPARENT").empty();
                        for( var i = 0; i<len; i++){
                            var JOBCODEID   = response[i]['JOBCODEID'];
                            var JOBDESC     = response[i]['JOBDESC'];
                            var ISDISABLED  = response[i]['ISDISABLED'];
                            
                            $("#JOBPARENT").append("<option value='"+JOBCODEID+"' "+ISDISABLED+">"+JOBDESC+"</option>");
                        }
                    }
                });*/
            //}
        //});
    });
    
    $(document).ready(function()
    {
        $("#JOBPARENT").change(function()
        {
            var PRJCODE     = document.getElementById("PRJCODE").value;
            var deptid1     = $(this).val();
            var deptid      = deptid1+'~'+PRJCODE;
            
            $.ajax({
                url: '<?php echo site_url('c_project/c_joblistdet/getCODEJOBLIST/?id=')?>',
                type: 'post',
                data: {depart:deptid},
                dataType: 'json',
                success:function(response)
                {
                    document.getElementById("JOBCODEID").value  = response;
                }
            });
        });
    });

    function submitForm()
    {
        var JOBPARENT   = document.getElementById('JOBPARENT').value;
        var JOBCODEID   = document.getElementById('JOBCODEID').value;
        var ISLASTH     = document.getElementById('ISLASTH').value;
        var JOBDESC     = document.getElementById('JOBDESC').value;
        var JOBUNIT     = document.getElementById('JOBUNIT').value;

        if(JOBPARENT == 0)
        {
            swal('<?php echo $alert2; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBPARENT').focus();
            });
            return false;
        }

        if(JOBCODEID == '')
        {
            swal('<?php echo $alert3; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBCODEID').focus();
            });
            return false;
        }

        if(ISLASTH == '')
        {
            swal('<?php echo $alert6; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('ISLASTH').focus();
            });
            return false;
        }

        if(JOBDESC == '')
        {
            swal('<?php echo $alert4; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBDESC').focus();
            });
            return false;
        }

        if(JOBUNIT == 0)
        {
            swal('<?php echo $alert5; ?>',
            {
                icon:"warning"
            })
            .then(function()
            {
                document.getElementById('JOBUNIT').focus();
            });
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