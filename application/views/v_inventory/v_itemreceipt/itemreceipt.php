<?php
/* 
    * Author        = Dian Hermanto
    * Create Date   = 18 April 2017
    * File Name     = itemreceipt.php 
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
$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID      = $this->session->userdata['Emp_ID'];
$DEPCODE        = $this->session->userdata['DEPCODE'];

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
            
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Edit')$Edit = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'Amount')$Amount = $LangTransl;
            if($TranslCode == 'Void')$Void = $LangTransl;
            if($TranslCode == 'Supplier')$Supplier = $LangTransl;
            if($TranslCode == 'Approval')$Approval = $LangTransl;
            if($TranslCode == 'Approved')$Approved = $LangTransl;
            if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
            if($TranslCode == 'rejected')$rejected = $LangTransl;
            if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'yesDel')$yesDel = $LangTransl;
            if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
            if($TranslCode == 'Budget')$Budget = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
            $sureDelete = "Anda yakin akan menghapus data ini?";
            $alert1     = "Pengaturan Departemen Pengguna";
            $alert2     = "Status pengguna belum ditentukan pada departemen manapun, sehingga tidak dapat membuat dokumen ini. Silahkan hubungi admin untuk meminta bantuan.";
        }
        else
        {
            $sureDelete = "Are your sure want to delete?";
            $alert1     = "User department setting";
            $alert2     = "User not yet set department, so can not create this document. Please call administrator to get help.";
        }
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
    <?php

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/barcode.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
                <small><?php echo $PRJNAME; ?></small>
                <div class="pull-right">
                    <?php
                        $secAddURL = site_url('c_inventory/c_ir180c15/a180c15dd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        $AddSO = site_url('c_inventory/c_ir180c15/a180c15dd50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        $AddDir= site_url('c_inventory/c_ir180c15/a180c15ddDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        if($ISCREATE == 1 && $DEPCODE != '')
                        {
                            echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                            //if($this->session->userdata['nSELP'] == 0)
                            //{
                            if($PRJSCATEG == 2)
                            {
                                echo anchor("$AddSO",'<button class="btn btn-success"><i class="fa fa-pause"></i></button>&nbsp;&nbsp;');
                            }
                            //}
                            
                            //echo anchor("$AddDir",'&nbsp;&nbsp;<button class="btn btn-warning"><i class="fa fa-cube"></i>&nbsp;&nbsp;IR Direct</button>&nbsp;');
                        }
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
                            <div class="col-sm-2" style="display: none;">
                                <label for="inputName" class="control-label"><?=$Budget?></label>
                                <select name="PRJCODEA" id="PRJCODEA" class="form-control select2" onChange="grpData(this.value)">
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
                                <label for="inputName" class="control-label"><?php echo $SupplierName ?> </label>
                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SPL  = "SELECT DISTINCT B.SPLCODE, B.SPLDESC FROM tbl_ir_header A
                                                    LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE AND A.PRJCODE = '$PRJCODE'
                                                    ORDER BY B.SPLDESC ASC";
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
                                <label for="inputName" class="control-label">No. PO</label>
                                <select name="PO_NUM" id="PO_NUM" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_PONO  = "SELECT DISTINCT A.PO_NUM, A.PO_CODE FROM tbl_ir_header A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PO_CODE ASC";
                                        $r_PONO  = $this->db->query($s_PONO)->result();
                                        foreach($r_PONO as $rw_PONO) :
                                            $PO_NUM    = $rw_PONO->PO_NUM;
                                            $PO_CODE   = $rw_PONO->PO_CODE;
                                            ?>
                                            <option value="<?php echo $PO_NUM; ?>"><?php echo "$PO_CODE"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">No. Gudang</label>
                                <select name="SJ_NUM" id="SJ_NUM" class="form-control select2" onChange="grpData(this.value)">
                                    <option value=""> --- </option>
                                    <?php
                                        $s_SJ     = "SELECT DISTINCT SJ_NUM FROM tbl_ir_detail WHERE PRJCODE = '$PRJCODE'";
                                        $r_SJ     = $this->db->query($s_SJ)->result();
                                        foreach($r_SJ as $rw_SJ) :
                                            $SJ_NUM    = $rw_SJ->SJ_NUM;
                                            ?>
                                            <option value="<?=$SJ_NUM?>" ><?=$SJ_NUM?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label">Status</label>
                                <select name="IR_STAT" id="IR_STAT" class="form-control select2" onChange="grpData(this.value)">
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
                    var PROJECT = document.getElementById('PRJCODEA').value;
                    var SPLC    = document.getElementById('SPLCODE').value;
                    var PONO    = document.getElementById('PO_NUM').value;
                    var SJNUM   = document.getElementById('SJ_NUM').value;
                    var DSTAT   = document.getElementById('IR_STAT').value;

                    $('#example').DataTable( {
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataGRP/?id='.$PRJCODE)?>"+'&SPLC='+SPLC+'&PONO='+PONO+'&DSTAT='+DSTAT+'&SRC='+SRC+'&PROJECT='+PROJECT+'&SJNUM='+SJNUM,
                        "type": "POST",
                        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
                        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                        "columnDefs": [ { targets: [1,2,5,7], className: 'dt-body-center' },
                                        { "width": "100px", "targets": [1] }
                                      ],
                        "order": [[ 1, "desc" ]],
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
                    <form class="form-horizontal" name="frmDel" id="frmDel" method="post" action="" onSubmit="return checkInp()">
                        <input type="hidden" name="colSelINV" id="colSelINV" value="">
                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                    </form>
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped table-hover table-responsive search-table inner" width="100%">
                            <thead>
                                <tr>
                                    <th width="2%">&nbsp;</th>
                                    <th width="10%" style="text-align:center"><?php echo $Code ?>  </th>
                                    <th width="5%" style="text-align:center" nowrap><?php echo $Date ?> </th>
                                    <th width="20%" style="text-align:center"><?php echo $Supplier; ?></th>
                                    <th width="40%" style="text-align:center"><?php echo $Description ?> </th>
                                    <th width="10%" style="text-align:center"><?php echo $Status ?> </th>
                                    <th width="10%" style="text-align:center" nowrap><?php echo $Amount ?> </th>
                                    <th width="3%" style="text-align:center" nowrap>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <div class="alert alert-warning alert-dismissible" <?php if($DEPCODE != '') { ?> style="display: none;" <?php } ?>>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-warning"></i> <?php echo $alert1; ?>!</h4>
                                    <?php echo $alert2; ?>
                                </div>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div id="loading_1" class="overlay" style="display:none">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
            <?php
                $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefEmp_ID == 'D15040004221')
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
        "ajax": "<?php echo site_url('c_inventory/c_ir180c15/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [1,2,5,7], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] }
                      ],
        "order": [[ 1, "desc" ]],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );

    <?php
        $secIdx_DOC = site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
    ?>
    
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
  
    function deleteDOCA(thisVal)
    {
        var r = confirm("<?php echo $sureDelete; ?>");
        if (r == true) 
        {
            var idxData = "<?php echo $secIdx_DOC; ?>";
            $.ajax({
                type  : 'ajax',
                url   : thisVal,                
                async : false,
                //dataType : 'json',
                success: function(data)
                {
                    //$( "#example1" ).load( ""+idxData+" #example1" );
                    //$('#example1').DataTable().ajax.reload();
                    window.location.reload(true);
                    
                    /*var html = '';
                    var i;
                    for(i=0; i<data.length; i++)
                    {
                        var IR_STAT     = data[i].IR_STAT;
                        var INVSTAT     = data[i].INVSTAT;
                        
                        if(IR_STAT == 1)
                        {
                            var IR_STATD    = 'New';
                            var STATCOL     = 'warning';
                        }
                        else if(IR_STAT == 2)
                        {
                            var IR_STATD    = 'Confirmed';
                            var STATCOL     = 'primary';
                        }
                        else if(IR_STAT == 3)
                        {
                            var IR_STATD    = 'New';
                            var STATCOL     = 'success';
                        }
                        else if(IR_STAT == 4)
                        {
                            var IR_STATD    = 'New';
                            var STATCOL     = 'danger';
                        }
                        else if(IR_STAT ==5)
                        {
                            var IR_STATD    = 'New';
                            var STATCOL     = 'danger';
                        }
                        else if(IR_STAT == 6)
                        {
                            var IR_STATD    = 'New';
                            var STATCOL     = 'info';
                        }
                        else if(IR_STAT == 7)
                        {
                            var IR_STATD    = 'New';
                            var STATCOL     = 'warning';
                        }
                        else if(IR_STAT == 9)
                        {
                            var IR_STATD    = 'Void';
                            var STATCOL     = 'danger';
                        }
                        else
                        {
                            var IR_STATD    = 'Fake';
                            var STATCOL     = 'danger';
                        }   
                                                                        
                        if(INVSTAT == 'NI')
                        {
                            var INVSTATDes  = 'No';
                            var STATCOLV    = 'danger';
                        }
                        else if(INVSTAT == 'HI')
                        {
                            var INVSTATDes  = 'Half';
                            var STATCOLV    = 'warning';
                        }
                        else if(INVSTAT == 'FI')
                        {
                            var INVSTATDes  = 'Full';
                            var STATCOLV    = 'success';
                        }
                        
                        html += '<tr>'+
                                '<td style="text-align:center; display:none">'+data[i].IR_NUM+'</td>'+
                                '<td nowrap>'+data[i].IR_CODE+'</td>'+
                                '<td nowrap>'+data[i].IR_DATE+'</td>'+
                                '<td nowrap>'+data[i].IR_REFER+'</td>'+
                                '<td nowrap>'+data[i].IR_NOTE+'</td>'+
                                '<td style="text-align:center"><span class="label label-'+STATCOL+'" style="font-size:11px">'+IR_STATD+'</span></td>'+
                                '<td nowrap style="text-align:center"><span class="label label-'+STATCOLV+'" style="font-size:11px">'+INVSTATDes+'</span></td>'+
                                '<td>'+data[i].INVSTAT+'</td>'+
                                '</tr>';
                    }
                    $('#show_data').html(html);*/
                }
            });
        }
    }
    
    function syncTOTHD(row)
    {
        var collID  = document.getElementById('urlSyncIRH'+row).value;
        var myarr   = collID.split("~");

        var url     = myarr[0];
        var IR_NUM  = myarr[1];

        /*document.getElementById('idprogbar').style.display = '';
        document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
        document.getElementById('loading_1').style.display = '';
        var collID  = PRJCODE;

        var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
        $("#myiFrame")[0].contentWindow.PRJCODE.value       = PRJCODE;
        $("#myiFrame")[0].contentWindow.IMP_CODEX.value     = 'RESETRAPT';
        $("#myiFrame")[0].contentWindow.IMP_TYPE.value      = 'RESETRAPT';
        $("#myiFrame")[0].contentWindow.DESCRIPT.value      = JOBID;
        butSubm.submit();*/

        document.getElementById('loading_1').style.display = '';
        $.ajax({
            type: 'POST',
            url: url,
            data: {IR_NUM: IR_NUM},
            success: function(response)
            {
                document.getElementById('loading_1').style.display = 'none';
                swal(response, 
                {
                    icon: "success",
                });
                $('#example').DataTable().ajax.reload();
            }
        });
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
    
    function printQR(row)
    {
        var url = document.getElementById('urlPrintQR'+row).value;
        w = 400;
        h = 300;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }
    
    /*function voidDoc(row)
    {
        var url = document.getElementById('urlVoid'+row).value;
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }*/
    
    function deleteDOC(row)
    {
        /*var r = confirm("<?php echo $sureDelete; ?>");
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
        }*/
        
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
                //
            }
        });
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