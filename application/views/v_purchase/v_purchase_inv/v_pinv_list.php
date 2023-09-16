<?php
/*  
    * Author        = Dian Hermanto
    * Create Date   = 11 November 2017
    * File Name = v_pinv_list.php
    * Location      = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
    $APPLEV = $row->APPLEV;
endforeach;
$decFormat  = 2;

$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];

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
            if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'DueDate')$DueDate = $LangTransl;
            if($TranslCode == 'PONo')$PONo = $LangTransl;
            if($TranslCode == 'ReceiveNo')$ReceiveNo = $LangTransl;
            if($TranslCode == 'Supplier')$Supplier = $LangTransl;
            if($TranslCode == 'PaymentStatus')$PaymentStatus = $LangTransl;
            if($TranslCode == 'Notes')$Notes = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'Direct')$Direct = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'yesDel')$yesDel = $LangTransl;
            if($TranslCode == 'cancDel')$cancDel = $LangTransl;
            if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
            if($TranslCode == 'Budget')$Budget = $LangTransl;
        endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
                <small><?php echo $PRJNAME; ?></small>
                <div class="pull-right">
                    <?php
                        $url_add    = site_url('c_purchase/c_pi180c23/a180c23dd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        $url_addDir = site_url('c_purchase/c_pi180c23/a180c23ddDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        echo anchor("$url_add",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                        //echo anchor("$url_addDir",'<button class="btn btn-warning"><i class="cus-invoice-16x16"></i>&nbsp;&nbsp;Direct</button>&nbsp;&nbsp;');                                
                        echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');                            
                        if ( ! empty($link))
                        {
                            foreach($link as $links)
                            {
                                echo $links;
                            }
                        }
                    ?>
                </div>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label"><?=$Budget?></label>
                                <select name="PRJCODEA" id="PRJCODEA" class="form-control select2">
                                    <option value=""> --- </option>
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
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label">Supplier</label>
                                <select name="SPLCODEA" id="SPLCODEA" class="form-control select2" style="width: 100%">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPL  = "SELECT DISTINCT A.SPLCODE, B.SPLDESC FROM tbl_ttk_header A
                                                    INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                                                    WHERE A.PRJCODE IN (SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO = '$PRJHO') ORDER BY B.SPLDESC ASC";
                                        $r_SPL  = $this->db->query($s_SPL)->result();
                                        foreach($r_SPL as $rw_SPL) :
                                            $SPLCODE   = $rw_SPL->SPLCODE;
                                            $SPLDESC   = $rw_SPL->SPLDESC;
                                            ?>
                                            <option value="<?php echo $SPLCODE; ?>"><?php echo "$SPLDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label">No. LPM/Opn</label>
                                <select name="DOC_REF" id="DOC_REF" class="form-control select2">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_DOCREF   = "SELECT IR_NUM AS DOC_NUM, IR_CODE AS DOC_CODE FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (3,6)
                                                        UNION
                                                        SELECT OPNH_NUM AS DOC_NUM, OPNH_CODE AS DOC_CODE FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE' AND OPNH_STAT IN (3,6)";
                                        $r_DOCREF   = $this->db->query($s_DOCREF);
                                        if($r_DOCREF->num_rows() > 0)
                                        {
                                            foreach($r_DOCREF->result() as $rw_DOCREF):
                                                $DOC_NUM    = $rw_DOCREF->DOC_NUM;
                                                $DOC_CODE   = $rw_DOCREF->DOC_CODE;
                                                ?>
                                                <option value="<?=$DOC_NUM?>"><?=$DOC_CODE?></option>
                                                <?php
                                            endforeach;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                function grpData()
                {
                    var PROJECT = document.getElementById('PRJCODEA').value;
                    var SPLCODE = document.getElementById('SPLCODEA').value;

                    $('#example').DataTable(
                    {
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_purchase/c_pi180c23/get_AllDataGRP/?id='.$PRJCODE)?>"+'&SPLCODE='+SPLCODE+'&PROJECT='+PROJECT,
                        "type": "POST",
                        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                        "columnDefs": [ { targets: [0,6], className: 'dt-body-center' },
                                      ],
                        "order": [[ 1, "desc" ]],
                        "language": {
                            "infoFiltered":"",
                            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
                        },
                    });
                }
            </script>
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                            <tr>
                                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $InvoiceNo; ?>  </th>
                                <th width="15%" style="text-align:center; vertical-align:middle"><?php echo $Date; ?> </th>
                                <th width="30%" style="text-align:center; vertical-align:middle"><?php echo $Supplier; ?> </th>
                                <th width="30%" style="text-align:center; vertical-align:middle"><?php echo $Notes; ?> </th>
                                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $AmountReceipt; ?></th>
                                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status; ?></th>
                                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div id="loading_1" class="overlay" style="display:none">
                    <i class="fa fa-refresh fa-spin"></i>
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
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        // "ajax": "<?php // echo site_url('c_purchase/c_pi180c23/get_AllData/?id='.$PRJCODE)?>",
        "ajax": {
            "url": "<?php echo site_url('c_purchase/c_pi180c23/get_AllData/?id='.$PRJCODE)?>",
            "type": "GET",
            "data": function(data) {
                data.DOC_NUM    = $('#DOC_NUM').val();
                data.SPLCODE    = $('#SPLCODEA').val();
                data.PRJPERIOD  = $('#PRJCODEA').val();
            }
        },
        // "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [0,6], className: 'dt-body-center' },
                      ],
        "order": [[ 1, "desc" ]],
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
    
    function deleteDOC(row)
    {
        document.getElementById('loading_1').style.display = '';
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
                        })
                        .then(function()
                        {
                            $('#example').DataTable().ajax.reload();
                            document.getElementById('loading_1').style.display = 'none';
                        })
                    }
                });
            } 
            else 
            {
                document.getElementById('loading_1').style.display = 'none';
            }
        });
    }
    
    function voidDOC(row)
    {
        document.getElementById('loading_1').style.display = '';
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
                        })
                        .then(function()
                        {
                            $('#example').DataTable().ajax.reload();
                            document.getElementById('loading_1').style.display = 'none';
                        })
                    }
                });
            } 
            else 
            {
                document.getElementById('loading_1').style.display = 'none';
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