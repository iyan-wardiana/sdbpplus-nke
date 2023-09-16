<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Agustus 2014
 * File Name	= bank_payment.php
 * Location		= 
*/
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

		
$dateNow = date('Y-m-d');
$dateNow2 = date('Y-m-d', strtotime('+30 days', strtotime($dateNow))); // mengurangi 

$empID = $this->session->userdata('Emp_ID');
$sql 	= "sd_tbnkgrs_cc WHERE grsdate2 <= '$dateNow2'";
$sqlRes	= $this->db->count_all($sql);

$dateNow = date('Y-m-d');
$dateNow2 = date('Y-m-d', strtotime('+30 days', strtotime($dateNow))); // Menentukan tgl maksimum untuk batas due date dari tgl hari ini 
?>
<style type="text/css">
	body{
		margin: 0;
	}
	#info_message{
		display: none;
		width: 100%;
		height: 51px;
		position: absolute;
		top: 0;
		position: fixed;
		z-index: 50000;
		margin: 0;
		padding: 0;
	}
	.center_auto{
		margin: 0 auto;
		width: 950px;
		padding: 15px 25px;
	}
	
	.wadah-mengetik
	{
		font-size: 16px;
		width: 740px;
		white-space:nowrap;
		overflow:hidden;
		-webkit-animation: ketik 5s steps(50, end);
		animation: ketik 5s steps(50, end);
		text-shadow:
			0px 0px 10px #FFFFA0,
			0px 0px 14px #00FF33,
			0px 0px 18px #FFFF41,
			0px 0px 20px #FFFF2A,
			0px 0px 30px #FFFF4D,
			0px 0px 40px #00FF33,
			0px 0px 50px #00FF33		
		;
	}
	@keyframes ketik{
			from { width: 0; }
	}
	@-webkit-keyframes ketik{
			from { width: 0; }
	}
	
	#info_message .message_area{
		float: left; 
		width: 98%;
	}
	#info_message .message_area span.link_ribbon{
		color: #999999;
		text-decoration: underline;
		cursor: pointer;
	}
	#info_message .button_area{
		float: left;
		width: 11px;
		height: 10px;
		margin-top: 3px;
	}
	.error_bg{
		background: url("<?php echo base_url().'images/error_bg.png';?>") 0 0 repeat-x;
	}
	.error_bg .message_area{
		font:bold 14px arial;
		color: #a20510;
		text-shadow: 0 1px 0 #fff; 
	}
	.error_bg .button_area{
		background: url("<?php echo base_url().'images/error_close.png';?>") 0 0 no-repeat;    
		cursor: pointer;
	}
	.error_bg .info_more_descrption{
	
		-moz-box-shadow: 0 0 5px #b2495b;
		-webkit-box-shadow: 0 0 5px #b2495b;
		box-shadow: 0 0 5px #b2495b;
	}
	.succ_bg{
		background: url("<?php echo base_url().'images/succ_bg.png';?>") 0 0 repeat-x;
	}
	.succ_bg .message_area{
		font:bold 14px arial;
		color: #2f7c00;
		text-shadow: 0 1px 0 #fff; 
	}
	.succ_bg .button_area{
		background: url("<?php echo base_url().'images/succ_close.png';?>") 0 0 no-repeat;    
		cursor: pointer;
	}
	.info_bg{
		background: url("<?php echo base_url().'images/info_bg.png';?>") 0 0 repeat-x;
	}
	.info_bg .message_area{
		font:bold 14px arial;
		color: #0d9a95;
		text-shadow: 0 1px 0 #fff; 
	}
	.info_bg .button_area{
		background: url("<?php echo base_url().'images/info_close.png';?>") 0 0 no-repeat;    
		cursor: pointer;
	}
	.warn_bg{
		background: url("<?php echo base_url().'images/warn_bg.png';?>") 0 0 repeat-x;
	}
	.warn_bg .message_area{
		font:bold 14px arial;
		color: #a39709;
		text-shadow: 0 1px 0 #fff; 
	}
	.warn_bg .button_area{
		background: url("<?php echo base_url().'images/warn_close.png';?>") 0 0 no-repeat;    
		cursor: pointer;
	}
	.clearboth{
		clear: both;
	}
	.info_more_descrption{
		display: none;
		width: 950px;
		height: 300px;
		background: #fff;
		margin: 0 auto;
		padding: 10px;
		background: #fbfbfb;
		overflow: auto;
	}
	.succ_bg .info_more_descrption{    
		-moz-box-shadow: 0 0 5px #56a25e;
		-webkit-box-shadow: 0 0 5px #56a25e;
		box-shadow: 0 0 5px #56a25e;
	}
</style>
<script type="text/javascript">
	function loadDuedateGuarantee() 
	{
        totRow = document.getElementById('countRow').value;
		if(totRow > 0)
		{
			showNotification({
				type : "error",
				message: "Ada ",
				message1: totRow,
				message2: " Bank Garansi yang Akan segera berakhir. Klik ",
				message3: "menampilkan semua Data."
			}); 
		}
	}
	window.onload = loadDuedateGuarantee;
	
	window.setTimeout(function()
	{ 
		loadDuedateGuarantee();loadDuedateGuaranteex(); 
	}, 50000);	
	
	function loadDuedateGuaranteex()
	{
		window.setTimeout(function()
		{ 
			loadDuedateGuarantee();loadDuedateGuaranteex()
		}, 50000);
		
	}
	
	function gotoallduedate()
	{
		var thisform = document.getElementById('gotoAllDueD').click();
	}
</script>
<div class="HCSSTableGenerator">
    <table width="100%" border="0">
        <tr height="20">
            <td colspan="3"><b><?php echo $h2_title; ?></b></td>
        </tr>
    </table>
</div>

<?php
	// Searching Function
	$selSearchgrstype1     	= $this->session->userdata['dtSessSrc1']['selSearchgrstype'];
	$selSearchType1     	= $this->session->userdata['dtSessSrc1']['selSearchType'];
	$selSearchbptype1     	= $this->session->userdata['dtSessSrc1']['selSearchbptype'];
	$txtSearch1        		= $this->session->userdata['dtSessSrc1']['txtSearch'];
?>
<form action="<?php print $srch_url;?>" method=POST>
    <select name="selSearchgrstype" id="selSearchgrstype" class="listmenu">
        <option value="" > --- All ---</option>
        <option value="AdvPayBond" <?php if($selSearchgrstype1 == 'AdvPayBond') { ?> selected <?php } ?>>Advance Payment Bond</option>
        <option value="BidBond" <?php if($selSearchgrstype1 == 'BidBond') { ?> selected <?php } ?>>Bid Bond</option>
        <option value="ConstRisk" <?php if($selSearchgrstype1 == 'ConstRisk') { ?> selected <?php } ?>>Construction All Risk</option>
        <option value="InsBond" <?php if($selSearchgrstype1 == 'InsBond') { ?> selected <?php } ?>>Maintenance Bond</option>
        <option value="PerBond" <?php if($selSearchgrstype1 == 'PerBond') { ?> selected <?php } ?>>Performance Bond</option>
    </select>    
    <select name="selSearchType" id="selSearchType" class="listmenu">
        <option value="GuaranteeNo" <?php if($selSearchType1 == 'GuaranteeNo') { ?> selected <?php } ?>>Guarantee Number</option>
        <option value="ProjNumber" <?php if($selSearchType1 == 'ProjNumber') { ?> selected <?php } ?>>Project Number</option>
    </select>
    <select name="selSearchbptype" id="selSearchbptype" class="listmenu">
    	<option value="" > --- All ---</option>
		<?php
            $sqlBP 		= "SELECT *
                        FROM tbankpublisher ORDER BY bankpub_desc";
            $resultBP 	= $this->db->query($sqlBP)->result();
            
            foreach($resultBP as $rowBP) :
                $bankpub_code		= $rowBP->bankpub_code;
                $bankpub_name		= $rowBP->bankpub_name;
                $bankpub_desc		= $rowBP->bankpub_desc;
      	?>
      	<option value="<?php echo $bankpub_code; ?>" <?php if($selSearchbptype1 == $bankpub_code) { ?> selected <?php } ?>><?php echo "$bankpub_name"; ?></option>
     	<?php
            endforeach;
         ?>
    </select>
    <input type="text" name="txtSearch" id="txtSearch" class="textbox"value="<?php echo $txtSearch1; ?>" />
    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />    
    <?php echo anchor('c_finance/bank_guarantee_cc/','<input type="button" name="btnShowAll" id="btnShowAll" class="button_css" value=" Show All " />');?>
    <?php print anchor($showIndexGD,'<input type="button" class="button_css" name="gotoAllDueD" id="gotoAllDueD" value="Show Due Date Guarantee" onClick="return showSuccessMessage()"/>'); ?>
</form>
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3" style="text-align:right"><?php echo $pagination; ?></td>
    </tr>
</table>
<form action="" onsubmit="confirmDelete();" method=POST>
<div class="CSSTableGenerator">
<table width="100%">
    <tr>
        <?php /*?><td width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></td><?php */?>
        <td width="2%">No.</td>
        <td width="9%" nowrap>Guarantee  Code</td>
        <td width="7%" nowrap>Project Code</td>
        <td width="23%">Project Name</td>
        <td width="9%">Guarantee Type</td>
        <?php /*?><td width="7%" nowrap>Guarantee Type</td><?php */?>
        <td width="18%" nowrap>Supplier</td>
        <td width="7%" nowrap>Guarantee Cost<br />
        (USD)</td>
        <td width="3%" nowrap>Kurs</td>
        <td width="7%" nowrap>Guarantee Cost<br />
        (Rp)</td>
        <td width="5%" nowrap>Start Date</td>
        <td width="5%" nowrap>End Date</td>
        <td width="5%" nowrap>Claim Date</td>
        <?php /*?><td width="12%">Project</td><?php */?>
        <?php /*?><td width="10%">Notes</td><?php */?>
    </tr>
	<?php 
	$i = 0;
	$PRJNAME = '';
	if($recordcount >0)
	{
	foreach($viewbankguar as $row) : 
		$myNewNo1 = ++$i;
		$myNewNo = $moffset + $myNewNo1;
		//$ID_BG			= $row->ID_BG;
		$grscode		= $row->GRSCODE;
		$prjcode		= $row->PRJCODE;
		$grsrefr		= $row->GRSREFR;
		//$bankpublisher	= $row->bankpublisher;
		$splcode		= $row->SPLCODE;
		$grstype		= $row->GRSTYPE;
		$grsdesc		= $row->GRSDESC;
		$grscost		= $row->GRSCOST;
		$grsdate1		= $row->GRSDATE1;
		$grsdate2		= $row->GRSDATE2;
		$grsfinal		= $row->GRSFINAL;
		$DP_TRXC		= $row->DP_TRXC;
		//$grsdateclaim	= $row->grsdateclaim;
		//$grscostUSD		= $row->grscostUSD;
		//$grskurs		= $row->grskurs;
		//$MP				= $row->MP;
		$PRJNAME = '';
		$sql = "SELECT * FROM sd_tproject WHERE PRJCODE = '$prjcode'";
		$result = $this->db->query($sql)->result();
					
		foreach($result as $row) :
			$PRJCODE		= $row->PRJCODE;
			$PRJNAME		= $row->PRJNAME;
		endforeach;
		$grsdate1a 	= date('d M Y', strtotime($grsdate1));
		$grsdate2a 	= date('d M Y', strtotime($grsdate2));
		//$grsdateclaima 	= date('d M Y', strtotime($grsdateclaim));
							
		if($grstype == '1')
		{
			$grstypeName	= 'Jaminan Uang Muka';
		}
		elseif($grstype == '2')
		{
			$grstypeName	= 'Jaminan Pelaksanaan';
		}
		elseif($grstype == '3')
		{
			$grstypeName	= 'ADVANCE PAYMENT BOND';
		}
		elseif($grstype == '4')
		{
			$grstypeName	= 'CONSTRUCTION ALL RISK';
		}
		elseif($grstype == '5')
		{
			$grstypeName	= 'MAINTENANCE BOND';
		}
		elseif($grstype == '')
		{
			$grstypeName	= 'NONE';
		}
		
		$bankpublisher 	= '';
		$grscostUSD		= 0;
		$grskurs		= 0;
		$grsdateclaima	= '';
		
		//$lastPatternNumb = $myMax;
		//$lastPatternNumb1 = $myMax;
		$len = strlen($grscode);
		$Pattern_Length = 6;
		$grscode2	= $grscode;
		
		if($Pattern_Length==2)
		{
			if($len==1) $nol="0";
		}
		elseif($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0"; else $nol = '';
		}
		elseif($Pattern_Length==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		
		$grscode = $nol.$grscode2;
	?>
	<tr>
        <?php /*?><td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$row->grscode.'" />'; ?> </td><?php */?>
        <td> <?php print $myNewNo; ?>. </td>
        <td nowrap> <?php print anchor('c_finance/bank_guarantee_cc/update/'.$grscode,$grscode,array('class' => 'update')).' '; ?> </td>
        <td nowrap><?php print $prjcode; ?> </td>
        <td nowrap><?php print $PRJNAME; ?> </td>
        <td nowrap><?php print $grstypeName; ?></td>
        <?php /*?><td nowrap><?php print $grstypeName; ?></td><?php */?>
		<?php 
			$sqlSPLC = "sd_tsupplier WHERE SPLCODE = '$splcode'";
			$resultSPLC = $this->db->count_all($sqlSPLC);
			
			if($resultSPLC > 0)
			{
				$sqlSPL = "SELECT SPLDESC FROM sd_tsupplier WHERE SPLCODE = '$splcode'";
				$resultSPL = $this->db->query($sqlSPL)->result();
							
				foreach($resultSPL as $rowSPL) :
					$SPLDESC		= $rowSPL->SPLDESC;
				endforeach;
				?>
                <td nowrap>
					<?php
                    	print "$splcode - $SPLDESC";
					?>
                </td>
        		<?php
			}
			else
			{
				$SPLDESC		= "No Name";
			?>
                <td nowrap style="font-style:italic">
					<?php
                    	print "$splcode - $SPLDESC";
					?>
                </td>
            <?php
			}
		?>
        <td style="text-align:right" nowrap>&nbsp;<?php print number_format($grscostUSD, $decFormat); ?>&nbsp;</td>
        <td style="text-align:right" nowrap>&nbsp;<?php print number_format($grskurs, $decFormat); ?>&nbsp;</td>
        <td style="text-align:right" nowrap>&nbsp;<?php print number_format($grscost, $decFormat); ?>&nbsp;</td>
        <td style="text-align:center" nowrap><?php print $grsdate1a; ?> </td>
        <td style="text-align:center" nowrap><?php print $grsdate2a; ?> </td>
        <td style="text-align:center" nowrap><?php print $grsdateclaima; ?> </td>
    </tr>
    <?php endforeach; 
	}
	else
	{
	?>
		<tr>
            <td colspan="12" style="text-align:center"> --- None ---</td>
   	    </tr>
    <?php
	}
	?>
</table>
<input type="text" style="display:none" name="countRow" id="countRow" value="<?php echo $sqlRes; ?>">
</div>
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3"><hr /></td>
    </tr>
</table>
&nbsp;<?php echo anchor('c_finance/bank_guarantee_cc/add','<input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value="Add New" />');?>&nbsp;
<?php /*?><input type="submit" name="btnDelete" id="btnDelete" class="button_css" value="Change Status" /><?php */?>
</form>