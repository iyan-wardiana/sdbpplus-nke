<?php
/*  
    * Author       = Dian Hermanto
    * Create Date  = 22 Oktober 2018
    * File Name    = v_prodprocess.php
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
$sql            = "SELECT PRJNAME FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
$result         = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME    = $row ->PRJNAME;
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
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'ProcessStep')$ProcessStep = $LangTransl;
            if($TranslCode == 'JOCode')$JOCode = $LangTransl;
            if($TranslCode == 'SOCode')$SOCode = $LangTransl;
            if($TranslCode == 'DestProcess')$DestProcess = $LangTransl;
            if($TranslCode == 'Term')$Term = $LangTransl;
            if($TranslCode == 'Approve')$Approve = $LangTransl;
            if($TranslCode == 'User')$User = $LangTransl;
            if($TranslCode == 'CustName')$CustName = $LangTransl;
            if($TranslCode == 'Purchase')$Purchase = $LangTransl;
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'PODirect')$PODirect = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
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

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
            <small><?php echo $PRJNAME; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                            <tr>
                                <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Code ?> </th>
                                <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                                <th width="13%" style="vertical-align:middle; text-align:center"><?php echo $ProcessStep  ?> </th>
                                <th width="14%" style="vertical-align:middle; text-align:center"><?php echo $DestProcess ?> </th>
                                <th width="28%" style="vertical-align:middle; text-align:center"><?php echo $CustName ?> </th>
                                <th width="8%" style="vertical-align:middle; text-align:center" nowrap><?php echo $JOCode ?> </th>
                                <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $SOCode ?> </th>                   
                                <th width="5%" style="vertical-align:middle; text-align:center">Status </th>
                                <th width="4%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody> 
                            </tbody>
                            <tfoot>
                                <!-- <div class="alert alert-warning alert-dismissible" <?php if($DEPCODE != '') { ?> style="display: none;" <?php } ?>>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-warning"></i> <?php echo $alert1; ?>!</h4>
                                    <?php echo $alert2; ?>
                                </div> -->
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <?php
                        $url_add         = site_url('c_production/c_pR04uctpr0535/a44_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        $url_addQRCode  = site_url('c_production/c_pR04uctpr0535/a44QR_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

                        //if($ISCREATE == 1 && $DEPCODE != '') 
                        //{
                            echo anchor("$url_addQRCode",'<button class="btn btn-success"><i class="glyphicon glyphicon-qrcode"></i></button>&nbsp;&nbsp;');
                        //}
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
                <!-- /.box -->
            </div>
        </section>
    </body>
</html>

<script>
    /*	$(document).ready(function() {
    var oTable = $('#example').dataTable();
    $('#example thead th:eq(2)').unbind('click').click(function(){
    var aaSorting = oTable.fnSettings().aaSorting;
    if( aaSorting[0][0] !=2 || aaSorting[0][1] == 'desc'){
    oTable.fnSort( [[2,'asc'], [3,'asc']]);
    }else{
    oTable.fnSort( [[2,'desc'], [3,'asc']]);
    }
    });
    } );*/
    $(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_production/c_pR04uctpr0535/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [0,1,5,6,7,8], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] }
                      ],
        "order": [[ 2, "desc" ]],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } ); 

    /*$(document).ready(function() {
		$('#example').DataTable( {
			"order": [1, 'desc'],
			"processing": true,
			"serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
			"ajax": "<?php echo site_url('c_production/c_pR04uctpr0535/get_AllData/?id='.$PRJCODE)?>",
			"type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [ { 
				targets: [0,1,5,6,7,8], 
				className: 'dt-body-center' }, { "width": "100px", "targets": [1] } ],
			"language": {
				"infoFiltered":"",
				"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
			},
			} );*/
			
			/*var oTable = $('#example').dataTable();
			$('#example thead th:eq(2)').unbind('click').click(function()
			{
				var aaSorting = oTable.fnSettings().aaSorting;
				if( aaSorting[0][0] !=2 || aaSorting[0][1] == 'desc')
				{
					//oTable.fnSort( [[5,'asc'], [1,'desc']]);
					oTable.fnSort([[5,'desc'],[1,'desc']]).draw();
				}
				else
				{
					//oTable.fnSort( [[5,'asc'], [1,'desc']]);
					oTable.fnSort([[5,"desc"],[1,"desc"]]).draw();
				}
			});
    	} );*/
    
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