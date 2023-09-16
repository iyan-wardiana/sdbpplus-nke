<?php
/*  
* Author        = Dian Hermanto
* Create Date   = 12 September 2022
* File Name     = v_inb_po4lt_list.php
* Location      = -
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
$DefEmp_ID      = $this->session->userdata['Emp_ID'];
$PRJCODE        = $PRJCODE;

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME = $row ->PRJNAME;
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
            if($TranslCode == 'OPCode')$OPCode = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'Supplier')$Supplier = $LangTransl;
            if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
            if($TranslCode == 'PRCode')$PRCode = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
            if($TranslCode == 'Term')$Term = $LangTransl;
            if($TranslCode == 'Approve')$Approve = $LangTransl;
            if($TranslCode == 'User')$User = $LangTransl;
            if($TranslCode == 'PurchaseOrder')$PurchaseOrder = $LangTransl;
            if($TranslCode == 'Purchase')$Purchase = $LangTransl;
            if($TranslCode == 'AddPO')$AddPO = $LangTransl;
            if($TranslCode == 'PODirect')$PODirect = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Print')$Print = $LangTransl;
            if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
            $h1_title   = 'Persetujuan';
            $h2_title   = 'Pemesanan Pembelian';
        }
        else
        {
            $h1_title   = 'Aproval';
            $h2_title   = 'Purchase Order';
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
                <small><?php echo $PRJNAME; ?></small>
                <div class="pull-right">
                    <?php
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
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label"><?php echo $SupplierName ?> </label>
                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPL  = "SELECT DISTINCT B.SPLCODE, B.SPLDESC FROM tbl_po_header A
                                                        INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                                                    WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT IN (2,7) AND A.PO_CATEG = 1 ORDER BY B.SPLDESC ASC";
                                        $r_SPL  = $this->db->query($s_SPL)->result();
                                        foreach($r_SPL as $rw_SPL) :
                                            $SPLCODE    = $rw_SPL->SPLCODE;
                                            $SPLDESC    = $rw_SPL->SPLDESC;
                                            ?>
                                            <option value="<?php echo $SPLCODE; ?>"><?php echo "$SPLCODE - $SPLDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">No. SPP</label>
                                <select name="PR_NUM" id="PR_NUM" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPP  = "SELECT DISTINCT PR_NUM, PR_CODE FROM tbl_po_header
                                                    WHERE PR_NUM != '' AND PO_CATEG = 1 AND PO_STAT IN (2,7) AND PRJCODE = '$PRJCODE' ORDER BY PR_CODE ASC";
                                        $r_SPP  = $this->db->query($s_SPP)->result();
                                        foreach($r_SPP as $rw_SPP) :
                                            $PR_NUM    = $rw_SPP->PR_NUM;
                                            $PR_CODE   = $rw_SPP->PR_CODE;
                                            ?>
                                            <option value="<?php echo $PR_NUM; ?>"><?php echo "$PR_CODE"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2" style="display: none;">
                                <label for="inputName" class="control-label">Status</label>
                                <select name="PO_STAT" id="PO_STAT" class="form-control select2" onChange="grpData(this.value)">
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
                function grpData()
                {
                    var SRC     = "";
                    var SPLC    = document.getElementById('SPLCODE').value;
                    var SPNO    = document.getElementById('PR_NUM').value;
                    var DSTAT   = document.getElementById('PO_STAT').value;

                    $('#example').DataTable( {
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_purchase/c_p180c21o/get_AllData_1n2GRP4lt/?id='.$PRJCODE)?>"+'&SPLC='+SPLC+'&SPNO='+SPNO+'&DSTAT='+DSTAT+'&SRC='+SRC,
                        "type": "POST",
                        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
                        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                        "columnDefs": [ { "width": "100px", "targets": [1] }
                                      ],
                        "order": [[ 0, "desc" ]],
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
                                <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $OPCode ?> </th>
                                <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                                <th width="25%" style="vertical-align:middle; text-align:center"><?php echo $Supplier  ?> </th>
                                <th width="37%" style="vertical-align:middle; text-align:center"><?php echo $Description ?> </th>
                                <th width="13%" style="vertical-align:middle; text-align:center">No. SPP</th>
                                <th width="5%" style="vertical-align:middle; text-align:center">Status</th>
                                <th width="5%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody> 
                            </tbody>
                        </table>
                    </div>
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
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_purchase/c_p180c21o/get_AllData_1n24lt/?id='.$PRJCODE)?>",
            "type": "POST",
            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { "width": "100px", "targets": [1] }
                          ],
            "order": [[ 0, "desc" ]],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    } );
    
    function pRn_1rl(row)
    {
        var url = document.getElementById('urlIRList'+row).value;
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }
    
    function printDocument(row)
    {
        var url = document.getElementById('urlPrint'+row).value;
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