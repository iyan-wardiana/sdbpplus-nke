<?php
/*  
    * Author		= Dian Hermanto
    * Create Date	= 27 Maret 2018
    * File Name	= v_inb_bank_receipt.php
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
//$PRJCODE		= $PRJCODE;


$PRJNAME	= '';
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project where PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME = $rowPRJ->PRJNAME;
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
    		if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
    		if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
    		if($TranslCode == 'Amount')$Amount = $LangTransl;
    		if($TranslCode == 'PPn')$PPn = $LangTransl;
    		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
    		if($TranslCode == 'Approve')$Approve = $LangTransl;
    		if($TranslCode == 'User')$User = $LangTransl;
    		if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
    		if($TranslCode == 'Approval')$Approval = $LangTransl;
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
            if($TranslCode == 'ReceiptVal')$ReceiptVal = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$sureDelete	= "Anda yakin akan menghapus data ini?";
    	}
    	else
    	{
    		$sureDelete	= "Are your sure want to delete?";
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
                        echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                    ?>
                </div>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <!-- /.box-header -->
        		<div class="box-body">
                    <div class="search-table-outter">
                        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
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
                                <?php 
                                $i = 0;
                                $j = 0;
                                if($countBP > 0)
                                {
                                    $Unit_Type_Name2	= '';
                                    foreach($vwBP as $row) :
                                        $myNewNo 		= ++$i;					
                                        $JournalH_Code	= $row->JournalH_Code;
                                        $BR_CODE        = $row->BR_CODE;
        								$BR_DATE		= $row->BR_DATE;
                                        $BR_RECTYPE     = $row->BR_RECTYPE;
        								$BR_TYPE		= $row->BR_TYPE; 
        								$Account_Name	= $row->Account_Name; 
        								$BR_PAYFROM		= $row->BR_PAYFROM;
                                        $BR_TOTAM       = $row->BR_TOTAM;
        								$own_Name	    = '';

                                        if($BR_RECTYPE == 'SAL')
                                        {
                                            $sqlOWN     = "SELECT '' AS own_Title, CUST_DESC AS own_Name FROM tbl_customer
                                                            WHERE CUST_CODE = '$BR_PAYFROM'";
                                            $resOWN     = $this->db->query($sqlOWN)->result();
                                            foreach($resOWN as $rowOWN) :
                                                $own_Title  = $rowOWN->own_Title;
                                                if($own_Title != '')
                                                    $own_Title  = ", $own_Title";
                                                else
                                                    $own_Title  = "";
                                                $own_Name   = $rowOWN->own_Name;
                                            endforeach;
                                        }
                                        else
                                        {
                                            $sqlOWN     = "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
                                            $resOWN     = $this->db->query($sqlOWN)->result();
                                            foreach($resOWN as $rowOWN) :
                                                $own_Title  = $rowOWN->own_Title;
                                                if($own_Title != '')
                                                    $own_Title  = ", $own_Title";
                                                else
                                                    $own_Title  = "";
                                                $own_Name   = $rowOWN->own_Name;
                                            endforeach;
                                        }
        								
        								$BR_NOTES		= $row->BR_NOTES;
        								$BR_STAT		= $row->BR_STAT;
        								$ISVOID			= $row->ISVOID;
        							
        								if($BR_STAT == 0)
        								{
        									$BR_STATD 	= 'fake';
        									$STATCOL		= 'danger';
        								}
        								elseif($BR_STAT == 1)
        								{
        									$BR_STATD 	= 'New';
        									$STATCOL		= 'warning';
        								}
        								elseif($BR_STAT == 2)
        								{
        									$BR_STATD 	= 'Confirm';
        									$STATCOL		= 'primary';
        								}
        								elseif($BR_STAT == 3)
        								{
        									$BR_STATD 	= 'Approved';
        									$STATCOL		= 'success';
        								}
        							
        								if($ISVOID == 0)
        								{
        									$CISVOIDD 		= 'no';
        									$STATVCOL		= 'success';
        								}
        								elseif($BR_STAT == 1)
        								{
        									$CISVOIDD 		= 'yes';
        									$STATVCOL		= 'danger';
        								}
                                        
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        
                                		?>
                                            <td nowrap><?php echo $BR_CODE; ?></td>
                                            <td nowrap><?php print $BR_DATE; ?></td>
                                            <td style="text-align:left" nowrap><?php echo $own_Name; ?></td>
                                            <td nowrap> <?php print $Account_Name; ?> </td>
                                            <td style="text-align:right;"><?php echo number_format($BR_TOTAM, 2); ?></td>
                                            <td><?php echo $BR_NOTES; ?></td>
                                            <td style="text-align:center">
                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
        										<?php 
                                                    echo "&nbsp;&nbsp;$BR_STATD&nbsp;&nbsp;";
                                                 ?>
                                            </span>
                                            </td>
                                            <?php
        										$secUpd	= site_url('c_finance/c_br180c2cd0d/uin180c2gdt/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
        									?>
                                            <td style="text-align:center" nowrap>
                                                <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                                <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                                    <i class="glyphicon glyphicon-print"></i>
                                                </a>
                                                <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($BR_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                            </td>
                                   	    </tr>
                                    	<?php 
                           			endforeach;
                        		}
                                ?> 
                            </tbody>
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
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
    
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