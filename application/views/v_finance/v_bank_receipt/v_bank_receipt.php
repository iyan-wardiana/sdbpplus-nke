<?php
/* 
    * Author       = Dian Hermanto
    * Create Date  = 18 April 2017
    * File Name    = itemreceipt.php
    * Location     = -
*/

// _global function
$this->db->select('Display_Rows,decFormat'); 
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$DEPCODE    = $this->session->userdata['DEPCODE'];

$PRJCODE    = $PRJCODE;
$PRJNAME    = '';
$PRJHO      = '';
$sql        = "SELECT PRJNAME, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result     = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME    = $row->PRJNAME;
    $PRJHO     = $row->PRJCODE_HO;
endforeach;
?>

<style>
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
            if($TranslCode == 'INVCode')$INVCode = $LangTransl;
            if($TranslCode == 'INVNo')$INVNo = $LangTransl;
            if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
            if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
            if($TranslCode == 'Amount')$Amount = $LangTransl;
            if($TranslCode == 'PPn')$PPn = $LangTransl;
            if($TranslCode == 'DueDate')$DueDate = $LangTransl;
            if($TranslCode == 'Approve')$Approve = $LangTransl;
            if($TranslCode == 'User')$User = $LangTransl;
            if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
            if($TranslCode == 'Finance')$Finance = $LangTransl;
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Print')$Print = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'From')$From = $LangTransl;
            if($TranslCode == 'BankAccount')$BankAccount = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'ReceiptVal')$ReceiptVal = $LangTransl;
            if($TranslCode == 'Budget')$Budget = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
            $alert1     = "Pengaturan Departemen Pengguna";
            $alert2     = "Status pengguna belum ditentukan pada departemen manapun, sehingga tidak dapat membuat dokumen ini. Silahkan hubungi admin untuk meminta bantuan.";
        }
        else
        {
            $alert1     = "User department setting";
            $alert2     = "User not yet set department, so can not create this document. Please call administrator to get help.";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
                <?php echo "$mnName ($PRJCODE)"; ?>
                <small><?php echo $PRJNAME; ?></small>
                <div class="pull-right">
                    <?php
                        if($ISCREATE == 1)
                            echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                        echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                    ?>
                </div>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-sm-1">
                                <label for="inputName" class="control-label"><?=$Budget?></label>
                                <select name="PRJCODEA" id="PRJCODEA" class="form-control select2" onChange="grpBR(this.value)">
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
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label"><?=$Category?></label>
                                <select name="BR_RECTYPE" id="BR_RECTYPE" class="form-control select2" onChange="grpBR(this.value)">
                                    <option value=""> --- </option>
                                    <option value="DP">DP</option>
                                    <option value="PRJ">Project</option></option>
                                    <option value="PPD">Penyelesaian PD</option>
                                    <option value="OTH">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label"><?php echo $ReceiptFrom ?> </label>
                                <select name="BR_PAYFROM" id="BR_PAYFROM" class="form-control select2" style="width: 100%" onChange="grpBR(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPL  = "SELECT A.BR_PAYFROM, B.own_Name FROM tbl_br_header A
                                                        LEFT JOIN tbl_owner B ON A.BR_PAYFROM = B.own_Code ORDER BY B.own_Name ASC";
                                        $r_SPL  = $this->db->query($s_SPL)->result();
                                        foreach($r_SPL as $rw_SPL) :
                                            $BR_PAYFROM     = $rw_SPL->BR_PAYFROM;
                                            $own_Name       = $rw_SPL->own_Name;
                                            ?>
                                            <option value="<?php echo $BR_PAYFROM; ?>"><?php echo "$own_Name"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Status</label>
                                <select name="BR_STAT" id="BR_STAT" class="form-control select2" onChange="grpBR(this.value)">
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
                function grpBR()
                {
                    var PROJECT = document.getElementById('PRJCODEA').value;
                    var SRC     = document.getElementById('BR_RECTYPE').value;
                    var SPLC    = document.getElementById('BR_PAYFROM').value;
                    var GSTAT   = document.getElementById('BR_STAT').value;

                    $('#example').DataTable( {
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_finance/c_br180c2cd0d/get_AllDataBPGRP/?id='.$PRJCODE)?>"+'&SPLC='+SPLC+'&GSTAT='+GSTAT+'&SRC='+SRC+'&PROJECT='+PROJECT,
                        "type": "POST",
                        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
                        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                        "columnDefs": [ { targets: [0,1,6,7], className: 'dt-body-center' },
                                        { targets: [4], className: 'dt-body-right' },
                                        { "width": "50px", "targets": [0,1] },
                                        { "width": "100px", "targets": [2] }
                                      ],
                        "language": {
                            "infoFiltered":"",
                            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
                        },
                    } );
                }
            </script>
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%" style="text-align:center; vertical-align:middle;" nowrap><?php echo $Code; ?></th>
                                    <th width="5%"  style="text-align:center; vertical-align:middle;" nowrap><?php echo $Date; ?></th>
                                    <th width="20%"  style="text-align:center; vertical-align:middle;" nowrap><?php echo $From; ?></th>
                                    <th width="20%"  style="text-align:center; vertical-align:middle;" nowrap><?php echo $BankAccount; ?></th>
                                    <th width="13%"  style="text-align:center; vertical-align:middle;"><?php echo $ReceiptVal; ?></th>
                                    <th width="22%"  style="text-align:center; vertical-align:middle;"><?php echo $Description; ?></th>
                                    <th width="5%"  style="text-align:center; vertical-align:middle;" nowrap><?php echo $Status; ?></th>
                                    <th width="5%"  style="text-align:center; vertical-align:middle;" nowrap>&nbsp;</th>
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
        "ajax": "<?php echo site_url('c_finance/c_br180c2cd0d/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [0,1,6,7], className: 'dt-body-center' },
                        { targets: [4], className: 'dt-body-right' },
                      ],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
    
    function printD(row)
    {
        var url = document.getElementById('urlPrint'+row).value;
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
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
    
    function deleteDOC(row)
    {
        var r = confirm("<?php echo $sureDelete; ?>");
        if (r == true) 
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
                    swal(response);
                    $('#example').DataTable().ajax.reload();
                }
            });
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