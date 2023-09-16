<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Maret 2014
 * File Name	= vendor.css
 * Location		= ./system/application/views/v_purchase/v_vendor/vendor.php
*/
?>
<div class="HCSSTableGenerator">
    <table width="100%" border="0">
        <tr height="20">
            <td colspan="3"><b><?php echo $h2_title; ?></b></td>
        </tr>
    </table>
</div>

<form action="<?php print site_url();?>/c_purchase/purchase_requisition/searchvendor" method=POST>
    <select name="selSearchType" id="selSearchType" class="listmenu">
        <option value="PURREQNumber">Requisition Number</option>
        <option value="PURREQVenName">Vendor Name</option>
        <option value="PURREQReqBy">Requester</option>
    </select>
    <input type="text" name="txtSearch" id="txtSearch" class="textbox"value="" />
    <input type="submit" class="button_css" name="submit" id="submit" value=" search " />
    <?php echo anchor('c_purchase/purchase_requisition/','<input type="button" name="btnShowAll" id="btnShowAll" class="button_css" value=" Show All " />');?>
</form>
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3" style="text-align:right"><?php /*?><?php echo $pagination; ?><?php */?></td>
    </tr>
</table>

<div class="CSSTableGenerator">
<form action="<?php print site_url();?>/c_purchase/purchase_requisition/delete" onsubmit="confirmDelete();" method=POST>
<table width="100%">
    <tr>
        <td width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></td>
        <td width="3%">No.</td>
        <td width="15%"> Requisition No.</td>
      <td width="10%">Requisition Date</td>
      <td width="23%">Vendor Name</td>
      <td width="20%">Requested By.</td>
      <td width="8%">PR Status</td>
      <td width="9%">Approval Status</td>
      <?php /*?><td width="10%">Notes</td><?php */?>
    </tr>
	<?php 
	$i = 0;
	if($recordcount >0)
	{
	foreach($viewpurreq as $row) : 
		$empID		= $row->PR_EmpID;
		$firstName	= $row->First_Name;
		$MiddleName	= $row->Middle_Name;
		$lastName	= $row->Last_Name;
		$PR_Status	= $row->PR_Status;
		if($PR_Status == 0) $PR_StatusDesc = "New";
		elseif($PR_Status == 1) $PR_StatusDesc = "Confirm";
		
		$Approval_Status	= $row->Approval_Status;
		if($Approval_Status == 0) $Approval_StatusDes = "Waiting";
		elseif($Approval_Status == 1) $Approval_StatusDes = "Approved";
		elseif($Approval_Status == 2) $Approval_StatusDes = "Revising";
		elseif($Approval_Status == 3) $Approval_StatusDes = "Reject";
	?>
	<tr>
        <td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$row->PR_Number.'" />'; ?> </td>
        <td> <?php print ++$i; ?>. </td>
        <td> <?php print anchor('c_purchase/purchase_requisition/update/'.$row->PR_Number,$row->PR_Number,array('class' => 'update')).' '; ?> </td>
        <td> <?php print $row->PR_Date; ?> </td>
        <td> <?php print $row->Vend_Name; ?> </td>
        <td> <?php print "$firstName $MiddleName $lastName"; ?> </td>
        <td> <?php print $PR_StatusDesc ?> </td>
        <td> <?php print $Approval_StatusDes; ?> </td>
       <?php /*?> <td> <?php print $row->Vend_Status; ?> </td><?php */?>

    </tr>
    <?php endforeach; 
	}
	else
	{
	?>
		<tr>
            <td style="text-align:center" colspan="8"> --- None ---</td>
    	</tr>
    <?php
	}
	?>
    
</table>
</div>
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3">&nbsp;</td>
    </tr>
</table>
&nbsp;<?php echo anchor('c_purchase/purchase_requisition/add','<input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value="Add Purchase Requisition" />');?>&nbsp;
<input type="submit" name="btnDelete" id="btnDelete" class="button_css" value="Change Status" />
</form>
