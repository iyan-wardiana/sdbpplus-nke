<?php
/*
    * Author        = Dian Hermanto
    * Create Date   = 02 Januari 2018
    * File Name = spk_list.php
    * Location      = -
*/
// $this->load->view('template/head');

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
if($decFormat == 0)
    $decFormat      = 2;

$selProject = '';
if(isset($_POST['submit']))
{
    $selProject = $_POST['selProject'];
}

$DefEmp_ID      = $this->session->userdata['Emp_ID'];
$PRJNAME    = '';
$PRJHO      = '';
$sql        = "SELECT PRJNAME, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result     = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME    = $row->PRJNAME;
    $PRJHO     = $row->PRJCODE_HO;
endforeach;
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
        $ISDELETE   = $this->session->userdata['ISDELETE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $LangID     = $this->session->userdata['LangID'];

        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Edit')$Edit = $LangTransl;
            if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
            if($TranslCode == 'WorkOrder')$WorkOrder = $LangTransl;
            if($TranslCode == 'AddNew')$AddNew = $LangTransl;
            if($TranslCode == 'Print')$Print = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
            if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'yesDel')$yesDel = $LangTransl;
            if($TranslCode == 'cancDel')$cancDel = $LangTransl;
            if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
            if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;
            if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
            if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'Budget')$Budget = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
            $sureDelete = "Anda yakin akan menghapus data ini?";
        }
        else
        {
            $sureDelete = "Are your sure want to delete?";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }

        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>
    
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
                <small><?php echo $PRJNAME; ?></small>
                <div class="pull-right">
                    <?php 
                        echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');

                        //if($ISDELETE == 1)
                            echo '<button class="btn btn-warning" onClick="syncWODESC()" title="Sync. Data Opname"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';

                        echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-mail-reply"></i></button>');
                    ?>
                    <button class="btn btn-warning" type="button" title="Sembunyikan Dok. Close / Selesai" onClick="hideDocCls()" id="btnCls"><i class="fa fa-eye-slash"></i></button>
                    <button class="btn btn-warning" type="button" title="Tampilkan Dok. Close / Selesai" onClick="showDocShw()" id="btnShw" style="display: none;"><i class="fa fa-eye"></i></button>
                </div>
            </h1>
        </section>

        <section class="content">
            <div class="row" id="syncWODESCDESC" style="display: none;">
                <div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
                        Proses ini akan melakukan sinkronisasi data Opname terhadap SPK.<br>
                        <button class="btn btn-info" onClick="syncWODESCPROC()"></i>Lanjutkan</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" id="idprogbar" style="display: none;">
                    <div class="cssProgress">
                        <div class="cssProgress">
                            <div class="progress3">
                                <div id="progressbarXX" style="text-align: center;">0%</div>
                            </div>
                            <span class="cssProgress-label" id="information" ></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label"><?=$Budget?></label>
                                <select name="PRJCODEA" id="PRJCODEA" class="form-control select2" onChange="grpBP(this.value)">
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
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label"><?=$Category?></label>
                                <select name="WO_CATEG" id="WO_CATEG" class="form-control select2" style="width: 100%" onChange="grpBP(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_WOC  = "SELECT VendCat_Code, VendCat_Name FROM tbl_vendcat WHERE VendCat_Code NOT IN ('B')";
                                        $r_WOC  = $this->db->query($s_WOC)->result();
                                        foreach($r_WOC as $rw_WOC) :
                                            $WO_CATEG    = $rw_WOC->VendCat_Code;
                                            $WO_CATDESC   = $rw_WOC->VendCat_Name;
                                            ?>
                                            <option value="<?php echo $WO_CATEG; ?>"><?php echo "$WO_CATEG - $WO_CATDESC"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label"><?php echo $SupplierName ?> </label>
                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" style="width: 100%" onChange="grpBP(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPL  = "SELECT DISTINCT B.SPLCODE, B.SPLDESC FROM tbl_wo_header A
                                                    LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE ORDER BY B.SPLDESC ASC";
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
                                <label for="inputName" class="control-label">Status</label>
                                <select name="WO_STAT" id="WO_STAT" class="form-control select2" onChange="grpBP(this.value)">
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
                function grpBP()
                {
                    var PROJECT = document.getElementById('PRJCODEA').value;
                    var SRC     = document.getElementById('WO_CATEG').value;
                    var SPLC    = document.getElementById('SPLCODE').value;
                    var GSTAT   = document.getElementById('WO_STAT').value;

                    $('#example').DataTable( {
                        "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
                                "<'row'<'col-sm-12'tr>>",
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataWOGRP/?id='.$PRJCODE)?>"+'&SPLC='+SPLC+'&GSTAT='+GSTAT+'&SRC='+SRC+'&PROJECT='+PROJECT,
                        "type": "POST",
                        "lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                        // "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                        "columnDefs": [ { targets: [5,7], className: 'dt-body-center' },
                                        { "width": "100px", "targets": [1] }
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
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle; text-align:center" width="12%" nowrap><?php echo $NoSPK; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="20%" nowrap>Supplier</th>
                                    <th style="vertical-align:middle; text-align:center" width="28%"><?php echo $Description; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="10%" nowrap><?=$SPKVal?></th>
                                    <th style="vertical-align:middle; text-align:center" width="10%" nowrap><?=$IsVoid?></th>
                                    <th style="vertical-align:middle; text-align:center" width="10%" nowrap>ID</th>
                                    <th style="vertical-align:middle; text-align:center" width="5%" nowrap><?php echo $Status; ?> </th>
                                    <th style="vertical-align:middle; text-align:center" width="5%" nowrap>&nbsp;</th>
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

            <div class="row">
                <div class="col-md-12" id="idprogbarXY" style="display: none;">
                    <div class="cssProgress">
                        <div class="cssProgress">
                            <div class="progress3">
                                <div id="progressbarXY" style="text-align: center;">0%</div>
                            </div>
                            <span class="cssProgress-label" id="information" ></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefEmp_ID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
        <iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
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
            "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
                    "<'row'<'col-sm-12'tr>>",
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllData/?id='.$PRJCODE)?>",
            "type": "POST",
            "lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            // "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { targets: [5,7], className: 'dt-body-center' },
                            { "width": "100px", "targets": [1] }
                          ],
            "order": [[ 0, "desc" ]],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    } );
    
    function hideDocCls()
    {
        document.getElementById('btnCls').style.display     = 'none';
        document.getElementById('btnShw').style.display     = '';

        $('#example').DataTable( {
            "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
                    "<'row'<'col-sm-12'tr>>",
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataSH/?id='.$PRJCODE.'&ISCLS=')?>"+1,
            "type": "POST",
            "lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            // "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { targets: [4,5,6], className: 'dt-body-center' },
                            { "width": "100px", "targets": [1] }
                          ],
            "order": [[ 0, "desc" ]],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    }
    
    function showDocShw()
    {
        document.getElementById('btnCls').style.display     = '';
        document.getElementById('btnShw').style.display     = 'none';
        
        $('#example').DataTable( {
            "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
                    "<'row'<'col-sm-12'tr>>",
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataSH/?id='.$PRJCODE.'&ISCLS=')?>"+0,
            "type": "POST",
            "lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
            // "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { targets: [4,5,6], className: 'dt-body-center' },
                            { "width": "100px", "targets": [1] }
                          ],
            "order": [[ 0, "desc" ]],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    }

    function createDocument(row)
    {
        var url = document.getElementById('urlCreate'+row).value;
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }

    function printD(row)
    {
        var url = document.getElementById('urlPrint'+row).value;
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        // window.open(url, 'formpopup', 'height=' + screen.height + ',width=' + screen.width + ',resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=yes');
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }
    
    function deleteDOC(row)
    {
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
                        });
                        $('#example').DataTable().ajax.reload();
                    }
                });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
                {
                    icon: "error",
                });*/
            }
        });
    }

    function syncWODESC()
    {
        document.getElementById('syncWODESCDESC').style.display     = '';
    }
    
    function syncWODESCPROC()
    {
        document.getElementById('syncWODESCDESC').style.display     = 'none';

        document.getElementById('idprogbar').style.display          = '';
        document.getElementById("progressbarXX").innerHTML          ="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
        document.getElementById('idprogbarXY').style.display        = '';
        document.getElementById("progressbarXY").innerHTML          ="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
        //document.getElementById('loading_1').style.display            = '';

        var PRJCODE = "<?php echo $PRJCODE; ?>";
        var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
        $("#myiFrame")[0].contentWindow.PRJCODE.value       = PRJCODE;
        $("#myiFrame")[0].contentWindow.IMP_CODEX.value     = 'SYNCWO';
        $("#myiFrame")[0].contentWindow.IMP_TYPE.value      = 'SYNCWO';
        butSubm.submit();
    }

    function updStat()
    {
        var timer = setInterval(function()
        {
            clsBar();
        }, 2000);
    }

    function clsBar()
    {
        document.getElementById('idprogbar').style.display      = 'none';
        document.getElementById('idprogbarXY').style.display    = 'none';
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