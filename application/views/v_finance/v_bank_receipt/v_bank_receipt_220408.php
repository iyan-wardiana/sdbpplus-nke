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

$DefEmp_ID      = $this->session->userdata['Emp_ID'];
$DEPCODE        = $this->session->userdata['DEPCODE'];

$PRJCODE        = $PRJCODE;
$PRJNAME        = '';
$sqlPRJ         = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ      = $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
    $PRJNAME    = $rowPRJ->PRJNAME;
endforeach;
?>

<style>
    .search-table, td, th {
        border-collapse: collapse;
    }
    .search-table-outter { overflow-x: scroll; }
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
                <?php echo $mnName; ?>
                <small><?php echo $PRJNAME; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="search-table-outter">
                        <form class="form-horizontal" name="frmDel" id="frmDel" method="post" action="" onSubmit="return checkInp()">
                            <input type="hidden" name="colSelINV" id="colSelINV" value="">
                            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                       </form>
                        <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%" style="text-align:center" nowrap><?php echo $Code; ?></th>
                                    <th width="5%" style="text-align:center" nowrap><?php echo $Date; ?></th>
                                    <th width="20%" style="text-align:center" nowrap><?php echo $From; ?></th>
                                    <th width="25%" style="text-align:center" nowrap><?php echo $BankAccount; ?></th>
                                    <th width="30%" style="text-align:center"><?php echo $Description; ?></th>
                                    <th width="5%" style="text-align:center" nowrap><?php echo $Status; ?></th>
                                    <th width="5%" style="text-align:center" nowrap>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <?php
                        if($ISCREATE == 1)
                        {
                            echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
                        }
                        echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                    ?>
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
    $(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_finance/c_br180c2cd0d/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [0,1,5,6], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] }
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