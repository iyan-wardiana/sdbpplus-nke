<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 21 Oktober 2018
 * File Name	= v_jobselect.php
 * Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 2;
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;


$PRJNAME	= '';
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project where PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
{
	$PRJNAME = $rowPRJ->PRJNAME;
}
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

    <?php
        $this->load->view('template/mna');
		//______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
        
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
            if($TranslCode == 'INVCode')$INVCode = $LangTransl;
            if($TranslCode == 'INVNo')$INVNo = $LangTransl;
            if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'DueDate')$DueDate = $LangTransl;
            if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
            if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
            if($TranslCode == 'Amount')$Amount = $LangTransl;
            if($TranslCode == 'PPn')$PPn = $LangTransl;
            if($TranslCode == 'DueDate')$DueDate = $LangTransl;
            if($TranslCode == 'Approve')$Approve = $LangTransl;
            if($TranslCode == 'User')$User = $LangTransl;
            if($TranslCode == 'InvoiceSelection')$InvoiceSelection = $LangTransl;
            if($TranslCode == 'Invoice')$Invoice = $LangTransl;
            if($TranslCode == 'Process')$Process = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'CustName')$CustName = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'Ordered')$Ordered = $LangTransl;
            if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
            if($TranslCode == 'ItemName')$ItemName = $LangTransl;
            if($TranslCode == 'Stock')$Stock = $LangTransl;
            if($TranslCode == 'Unit')$Unit = $LangTransl;
            if($TranslCode == 'sureProcDOC')$sureProcDOC = $LangTransl;
        endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >
            <?php echo $mnName; ?>
            <small><?php echo $PRJNAME; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                            <input type="hidden" name="colSelINV" id="colSelINV" value="">
                            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                            <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                <tr>
                                    <th width="2%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                                    <th width="7%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?> JO</th>
                                    <th width="8%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Date; ?> </th>
                                    <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $ProdPlan; ?></th>
                                    <th width="30%" style="vertical-align:middle; text-align:center" nowrap><?php echo $CustName; ?></th>
                                  	<th width="31%" style="vertical-align:middle; text-align:center"><?php echo $Description; ?></th>
                                  	<th width="5%" style="vertical-align:middle; text-align:center" nowrap>Volume</th>
                                    <th width="11%" style="vertical-align:middle; text-align:center"><?php echo $Amount; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </form>
                        <script>
                            var selectedRows = 0
                            function check_all(chk)
                            {
                                if(chk.checked) {
                                    if(typeof(document.frm.chk[0]) == 'object') {
                                        for(i=0;i<document.frm.chk.length;i++) {
                                            document.frm.chk[i].checked = true;
                                        }
                                    } else {
                                        document.frm.chk.checked = true;
                                    }
                                    selectedRows = document.frm.chk.length;
                                } else {
                                    if(typeof(document.frm.chk[0]) == 'object') {
                                        for(i=0;i<document.frm.chk.length;i++) {
                                            document.frm.chk[i].checked = false;
                                        }
                                    } else {
                                        document.frm.chk.checked = false;
                                    }
                                    selectedRows = 0;
                                }
                            }

                            function pickThis(thisobj) 
                            {
            					var PRJCODE 	= document.getElementById('PRJCODE').value;
                                var checkbox 	= document.getElementsByName('chk[]')
                                var lnChk 		= checkbox.length;
            					var totrow		= $("#totrow").val();
                                
                                if (thisobj!= '') 
                                {
                                    if (thisobj.checked) selectedRows++;
                                    else selectedRows--;
                                }
                                
                                if (selectedRows==lnChk) 
                                {
                                    document.frm.ChkAllItem.checked = true;
                                }
                                else
                                {
                                    document.frm.ChkAllItem.checked = false;
                                }
                                
                                theCheck1	= '';
            					theCheck0	= document.getElementById('colSelINV').value;					
                                if(typeof(document.getElementsByName('chk[]')) == 'object') 
                                {
                                    var j = 0;
            						for(i=1;i<=totrow;i++) 
            						{
            							//if(document.frm.chk[i].checked)
            							var chkVal = document.getElementById('chk_'+i);
            							if(chkVal != null)
            							{
            								if(chkVal.checked)
            								{
            									j = j + 1;
            									theCheck	= document.getElementById('chk_'+i).value;
            									if(j == 1)
            									{
            										if(theCheck0 != '')
            											theCheck1	= theCheck0+'~'+theCheck;
            										else
            											theCheck1	= theCheck;
            									}
            									else
            									{
            										if(theCheck0 != '')
            											theCheck1	= theCheck0+'~'+theCheck1+'~'+theCheck;
            										else
            											theCheck1	= theCheck1+'~'+theCheck;
            									}
            								}
            							}
            						}
                                }
                                document.getElementById('colSelINV').value = theCheck1+'~'+PRJCODE;
                            }
                            
                            function checkInp()
                            {
            					var totCheck = $('[name="chk[]"]:checked').length;
                                if(totCheck == 0)
                                {
                                    swal('<?php echo $alert1; ?>');
                                    return false;
                                }
                            }
                        </script>
                    </div>
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
    $(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_production/c_j0rd3rsel/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [1,2,3], className: 'dt-body-center' },
                        { targets: [6,7], className: 'dt-body-right' },
                        { "width": "100px", "targets": [1] }
                      ],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
    
    function procJO(row)
    {
        swal({
            text: "<?php echo $sureProcDOC; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlProc'+row).value;
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
                //..
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