<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Maret 2018
 * File Name	= v_select_invr.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;
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

$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'RealisasiNumber')$RealisasiNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'PPn')$PPn = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
		if($TranslCode == 'Paid')$Paid = $LangTransl;
		if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$h1_title	= "Silahkan pilih faktur di bawah ini.";
	}
	else
	{
		$h1_title	= "Please select an invoice below.";
	}
	
	if($BR_RECTYPE == 'SAL')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT '' AS own_Title, CUST_CODE AS own_Name FROM tbl_customer WHERE CUST_CODE = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
	}
	elseif($BR_RECTYPE == 'PRJ')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
	}
	elseif($BR_RECTYPE == 'DP')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
		$OWNDESC	= "$own_Name$own_Title";
	}
	elseif($BR_RECTYPE == 'PPD')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT '' AS own_Title, CONCAT(First_Name, ' ', Last_Name) AS own_Name
						FROM tbl_employee WHERE Emp_ID = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
		$OWNDESC	= "$own_Name$own_Title";
	}
	else
	{
		$OWNDESC	= '-';
		$own_Title	= "";
	}
	$OWNDESC	= "$own_Name$own_Title";
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<div class="box">
    <!-- /.box-header -->
<div class="box-body">
    <div class="callout callout-success">
        <?php echo $h1_title; ?>
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                    <tr>
                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                        <th width="10%" nowrap><?php echo $RealisasiNumber; ?> </th>
                        <th width="27%" style="text-align:center" nowrap><?php echo $ReceiptFrom; ?>  </th>
                        <th width="30%" style="text-align:center" nowrap><?php echo $Description; ?></th>
                        <th width="15%" style="text-align:center" nowrap><?php echo $Amount; ?></th>
                        <th width="15%" style="text-align:center;" nowrap>
                        	<?php
                        		if($BR_RECTYPE == 'PPD')
                        			echo "Penyelesaian";
                        		else
                        			echo $Paid; 
                        	?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    $j = 0;
                    if($countINV>0)
                    {
                        $totRow	= 0;
                        foreach($vwINV as $row) :							
							$PINV_CODE 			= $row->PINV_CODE;
							$PINV_MANNO			= $row->PINV_MANNO;
							$PINV_DATE			= $row->PINV_DATE;
							$PINV_ENDDATE		= $row->PINV_ENDDATE;
							$PINV_CAT			= $row->PINV_CAT;
							$PINV_DPVAL			= round($row->PINV_DPVAL, 0);
							$PINV_DPVALPPn		= round($row->PINV_DPVALPPn, 0);
							$PINV_AMOUNT 		= round($row->PINV_TOTVAL, 0);
							$PINV_AMOUNT_PPn	= round($row->PINV_TOTVALPPn, 0);

							$T_PPN 				= 0;
							$s_PPN 				= "SELECT SUM(PPN_Amount) AS TOT_PPN FROM tbl_journaldetail_pd A
													WHERE Manual_No = '$PINV_MANNO' AND A.proj_Code = '$PRJCODE'
													AND A.Journal_DK = 'D' AND A.ISPERSL = 1 AND A.ISPERSL_STEP = 1";
							$r_PPN 				= $this->db->query($s_PPN)->result();
							foreach($r_PPN as $rw_PPN):
								$T_PPN 			= $rw_PPN->TOT_PPN;
							endforeach;

							if($PINV_AMOUNT_PPn == '')
								$PINV_AMOUNT_PPn= 0;

							if($PINV_CAT == 1)
							{
								$PINV_AMOUNT 		= $PINV_DPVAL;
								$PINV_AMOUNT_PPn	= $PINV_DPVALPPn;
							}
							$TOTNPPN			= round($PINV_AMOUNT + $PINV_AMOUNT_PPn, 0);
							$PINV_AMOUNT_PPh	= round($row->PINV_TOTVALPPh,0);
							$PINV_AMOUNT_OTH	= 0;
							$PINV_NOTES 		= $row->PINV_NOTES;
							$PINV_Number 		= $row->PINV_CODE;
							$PINV_STEP			= $row->PINV_STEP;
							$PINV_STEP			= $row->PINV_STEP;
							$OWN_NAME			= $row->OWN_NAME;
							$PINV_TOTVAL		= round($row->PINV_TOTVAL, 0);
							$PINV_PAIDAM		= round($row->PINV_PAIDAM + $T_PPN, 0);
							//$GPINV_REMAIN		= round($PINV_TOTVAL - $PINV_PAIDAM, 0);

							// HASIL DISKUSI DGN BU LYTA BHW PPH TIDAK MASUK JURNAL (08 JUNI 2022)
							// SEHINGGA PPH TIDAK MENGURANGI NILAI TAGIHAN
							//$GTOTNPPN			= $TOTNPPN - $PINV_AMOUNT_PPh;
							//$GPINV_TOTVAL1	= round($row->GPINV_TOTVAL, 0);
							//$GPINV_TOTVAL 	= round($GPINV_TOTVAL1 + $PINV_AMOUNT_PPh, 0);
							$GPINV_REMAIN		= round($TOTNPPN - $PINV_AMOUNT_PPh - $PINV_PAIDAM, 0);
							
							if($PINV_CAT == 1)
								$PINV_CATD	= "Pembayaran DP termin ke - $PINV_STEP";
							elseif($PINV_CAT == 2)
								$PINV_CATD	= "Pembayaran MC termin ke -  $PINV_STEP";
							elseif($PINV_CAT == 2)
								$PINV_CATD	= "Pembayaran SI termin ke -  $PINV_STEP";
							else
								$PINV_CATD	= $PINV_NOTES;
							
							$PRINV_DESC 		= $PINV_CATD;
							
                            $totRow		= $totRow + 1;

							$invDate 	= 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-calendar margin-r-5'></i>  ".$Date."</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$PINV_DATE."
										  	</div>";


							$invAmn 	= 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-money margin-r-5'></i> ".$InvoiceAmount."</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".number_format($TOTNPPN, 2)."
										  	</div>";
							$invRec 	= 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-check-square-o margin-r-5'></i> PPh</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".number_format($PINV_AMOUNT_PPh, 2)."
										  	</div>";
							$invPaid 	= 	"<div style='white-space:nowrap'>
												<strong><i class='glyphicon glyphicon-saved margin-r-5'></i> Diterima</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".number_format($PINV_PAIDAM, 2)."
										  	</div>";
							$invRem 	= 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-check-square-o margin-r-5'></i> Sisa</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".number_format($GPINV_REMAIN, 2)."
										  	</div>";
						
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							
							
							?>
                            <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PINV_CODE;?>|<?php echo $PINV_CATD;?>|<?php echo $PINV_AMOUNT;?>|<?php echo $PINV_AMOUNT_PPn;?>|<?php echo $PINV_AMOUNT_PPh;?>|<?php echo $PINV_AMOUNT_OTH;?>|<?php echo $PINV_CATD;?>|<?php echo $PINV_Number;?>|<?php echo $PINV_MANNO;?>|<?php echo $TOTNPPN;?>|<?php echo $GPINV_REMAIN;?>|<?php echo $PINV_PAIDAM
                            ;?>" onClick="pickThis(this);" /></td>
                            <td nowrap><?php echo "$PINV_MANNO $invDate"; ?></td>
                            <td nowrap><?php echo $OWN_NAME; ?>&nbsp;</td>
                            <td nowrap><?php echo wordwrap($PINV_NOTES, 60, "<br>", TRUE);; ?></td>
                            <td nowrap><?php echo "$invAmn $invRec"; ?></td>
                            <td nowrap>
                            	<?php //echo number_format($PINV_PAIDAM, $decFormat); ?>
                            	<?php echo "$invPaid $invRem"; ?>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                    }
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan="8" nowrap>
                    <button class="btn btn-primary" type="button" onClick="get_item();">
                    <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                    </button>&nbsp;
                    <button class="btn btn-danger" type="button" onClick="window.close()">
                    <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                    </button>
                  </td>
                </tr>
                </tfoot>
            </table>
        </form>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
</body>

</html>
<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
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
</script>

<script>
var selectedRows = 0
function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

function pickThis(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_item() 
	{ 
		//alert(document.frmSearch.chk.length) 
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_item(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_item(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					alert(B)
				}*/
			}
		}
		window.close();		
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>