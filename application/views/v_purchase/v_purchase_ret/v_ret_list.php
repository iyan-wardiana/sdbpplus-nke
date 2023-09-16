<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 19 Maret 2018
 * Updated		= Dian Hermanto
 * File Name	= v_ret_list.php
 * Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;
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

    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <?php
    	$this->load->view('template/topbar');
    	$this->load->view('template/sidebar');
    	
    	$ISREAD 	= $this->session->userdata['ISREAD'];
    	$ISCREATE 	= $this->session->userdata['ISCREATE'];
    	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
    	$ISDWONL 	= $this->session->userdata['ISDWONL'];
    	$LangID 	= $this->session->userdata['LangID'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'IRCode')$IRCode = $LangTransl;
    		if($TranslCode == 'Approve')$Approve = $LangTransl;
    		if($TranslCode == 'User')$User = $LangTransl;
    		if($TranslCode == 'PurchaseOrder')$PurchaseOrder = $LangTransl;
    		if($TranslCode == 'Purchase')$Purchase = $LangTransl;
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'PODirect')$PODirect = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
            if($TranslCode == 'yesDel')$yesDel = $LangTransl;
            if($TranslCode == 'cancDel')$cancDel = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$sureDelete	= "Anda yakin akan menghapus data ini?";
    	}
    	else
    	{
    		$sureDelete	= "Are your sure want to delete?";
    	}
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                <?php echo $mnName; ?>
                <small><?php echo $PRJNAME; ?></small>
                </h1>
            </section>

            <section class="content">
                <div class="box">
            		<div class="box-body">
                        <div class="search-table-outter">
                            <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                <tr>
                                    <th width="2%" style="vertical-align:middle; text-align:center">No</th>
                                    <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $Code ?> </th>
                                    <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                                    <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $IRCode  ?> </th>
                                    <th width="26%" style="vertical-align:middle; text-align:center"><?php echo $Supplier ?> </th>
                                    <th width="44%" style="vertical-align:middle; text-align:center"><?php echo $Description ?></th>
                                    <th width="4%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Status; ?> </th>
                                    <th width="4%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tr>
                                    <td colspan="8">
                                        <?php
                                            $url_add    = site_url('c_purchase/c_po180c19ret/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
            								echo anchor("$url_add",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
            								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');	
            							?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
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
        "ajax": "<?php echo site_url('c_purchase/c_po180c19ret/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [1,2,6,7], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] }
                      ],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
    
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
                swal("<?php echo $cancDel; ?>", 
                {
                    icon: "error",
                });
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>