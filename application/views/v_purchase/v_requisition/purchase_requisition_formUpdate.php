<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Maret 2014
 * File Name	= purchase_requisition_form.css
 * Location		= ./system/application/views/v_purchase/v_requisition/purchase_requisition_form.php
*/
?>
<?php
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
?>
<body>
<div class="HCSSTableGenerator">
<table width="100%" border="0" style="size:auto">
  <tr>
    <td colspan="3" class="style2"><?php echo $h2_title; ?></td>
  </tr>
</table>
</div>
<?php
		$DocNumber = $default['PR_Number'];
		$PR_Date = $default['PR_Date'];
		$PR_Class = $default['PR_Class'];
		$PR_Type = $default['PR_Type'];
		$PR_ProjectCode = $default['PR_ProjectCode'];
		$PR_DepID = $default['PR_DepID'];
		$PR_EmpID = $default['PR_EmpID'];
		$Vend_Code = $default['Vend_Code'];
		$Approval_Status = $default['Approval_Status'];
		$Approve_Date = $default['Approve_Date'];
		$NoteOfRevise = $default['NoteOfRevise'];
		$PR_EmpID = $default['PR_EmpID'];
		$lastPatternNumb1 = $default['Patt_Number'];
		$Patt_Year = $default['Patt_Year'];
		$First_Name = $default['First_Name'];
		$Middle_Name = $default['Middle_Name'];
		$Last_Name = $default['Last_Name'];
		$CompName = "$First_Name $Middle_Name $Last_Name";
		$Dept_Name = $default['Dept_Name'];
		$Vend_Name = $default['Vend_Name'];
		$PR_Notes = $default['PR_Notes'];
		if($PR_Notes == '') $PR_Notes = "-";
		$myYear = $default['myYear'];
		$myMonth = $default['myMonth'];
		$myDay = $default['myDay'];
		if($myMonth == 01) $myMonthDesc = "January"; elseif($myMonth == 02) $myMonthDesc = "February"; elseif($myMonth == 03) $myMonthDesc = "March";
		elseif($myMonth == 04) $myMonthDesc = "April"; elseif($myMonth == 05) $myMonthDesc = "Mey"; elseif($myMonth == 06) $myMonthDesc = "June";
	 	elseif($myMonth == 07) $myMonthDesc = "July"; elseif($myMonth == 08) $myMonthDesc = "August"; elseif($myMonth == 09) $myMonthDesc = "September";
	 	elseif($myMonth == 10) $myMonthDesc = "October"; elseif($myMonth == 11) $myMonthDesc = "November"; elseif($myMonth == 12) $myMonthDesc = "December";
		$compDate = "$myDay $myMonthDesc $myYear";
?>

<form name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="validateInVendCat();">
	<input type="Hidden" name="rowCount" id="rowCount" value="0">
    <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
        <tr>
            <th width="17%" align="left" class="style1" style="border-bottom-color:#FFFFFF">Purhase Requisition Number</th>
          	<th width="1%" align="left" class="style1">:</th>
            <th width="37%" align="left" class="style1"> <?php echo $DocNumber; ?>
        	<input type="hidden" class="textbox" name="PR_Number" id="PR_Number" size="30" value="<?php echo set_value('PR_Number', isset($default['PR_Number']) ? $default['PR_Number'] : $DocNumber); ?>" />
        <input type="hidden" class="textbox" name="Patt_Year" id="Patt_Year" size="30" value="<?php echo set_value('Patt_Year', isset($default['Patt_Year']) ? $default['Patt_Year'] : $Patt_Year); ?>" /></th>
      <th width="11%" align="left" class="style1">Date</th>
      <th width="34%" align="left" class="style1">: &nbsp;<?php echo $compDate; ?>      </th>
      </tr>
        <tr>
            <th align="left" class="style1">Request Class</th>
            <th align="left" class="style1">:</th>
            <th colspan="3" align="left" class="style1"> <?php echo $PR_Class; ?> </th>
        </tr>
        <tr>
            <th align="left" class="style1">Request Type</th>
            <th align="left" class="style1">:</th>
            <th align="left" class="style1">  <?php echo $PR_Type; ?> </th> 
            <th align="left" class="style1" id="labelProject1">Project No.</th>
            <th align="left" class="style1" id="labelProject2">: &nbsp;<?php
					if($PR_ProjectCode == '') $PR_ProjectCode = "-";
					echo $PR_ProjectCode; 
				?>            </th>
        </tr>
        <tr>
            <th align="left" class="style1">Requester Department</th>
            <th align="left" class="style1">:</th>
            <th align="left" class="style1"><?php echo $Dept_Name; ?></th> 
          <th align="left" class="style1">Requester Name</th>
            <th align="left" class="style1">: &nbsp;<?php echo $CompName; ?> </th>
        </tr>
        <tr>
            <th align="left" class="style1">Vendor Name</th>
            <th align="left" class="style1">:</th>
            <th align="left"><span class="style1"><?php echo $Vend_Name; ?></span></th> 
            <th align="left">Approval Status</th>
            <th align="left">:
            	<input type="radio" name="AppStatus" id="AppStatus" value="1" onClick="HiddenRevNote();" <?php if($Approval_Status == 1) { ?>checked<?php } ?>> Approve
            	<input type="radio" name="AppStatus" id="AppStatus" value="2" onClick="ShowRevNote();" <?php if($Approval_Status == 2) { ?>checked<?php } ?>> Revise
            	<input type="radio" name="AppStatus" id="AppStatus" value="3" onClick="HiddenRevNote();" <?php if($Approval_Status == 3) { ?>checked<?php } ?>> Reject
        	</th>
        </tr>
        <script>
			function ShowRevNote()
			{
				document.getElementById('ReviseNote').style.display = '';
			}
			function HiddenRevNote()
			{
				document.getElementById('ReviseNote').style.display = 'none';
			}
		</script>
        <tr>
            <th align="left" class="style1" valign="top">Notes</th>
            <th align="left" class="style1" valign="top">:</th>
            <th align="left" class="style1" valign="top"><?php echo $PR_Notes; ?></th> 
            <th align="left" class="style1">&nbsp;</th>
            <th align="left" class="style1"><div id="ReviseNote" style="display:none">&nbsp;&nbsp;<textarea name="NoteOfRevise" id="NoteOfRevise"></textarea></div></th>
        </tr>
        <tr>
          <th colspan="5" align="left" class="style1" style="font-style:italic">&nbsp;</th>
      	</tr>
        <tr>
            <th colspan="5" align="left" class="style1">
            	<div class="CSSTableGenerator">
                <table width="100%" border="0" id="tbl" >
                    <tr>
                      	<td width="2%" height="25" style="text-align:left">No.</td>
                      <td width="11%" style="text-align:left">Item Code</td>
                        <td width="11%" style="text-align:left">Serial Number</td>
                      	<td width="30%" style="text-align:left">Item Name</td>
                      	<td width="8%" style="text-align:left">Qty</td>
                      <td width="6%" style="text-align:left">Unit</td>
                      <td width="32%" style="text-align:left">Remarks</td>
                    </tr>
                    <?php
						$sql		= "SELECT A.PR_Number,A.Item_code,A.unit_type_id,A.unit_type_id2,A.request_qty,A.request_qty2,A.remarks,A.desc1,
										B.Item_Name,B.serialNumber,C.Unit_Type_Code,C.Unit_Type_Name
										FROM TPReq_Detail A
										INNER JOIN TItem B ON A.Item_code = B.Item_code
										INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id
										WHERE PR_Number = '$DocNumber'";
						// count data
							$resultCount = $this->db->where('PR_Number', $DocNumber);
							$resultCount = $this->db->count_all('TPReq_Header');
						// End count data
						$result = $this->db->query($sql)->result();
						$i = 0;
						if($resultCount > 0)
						{
							foreach($result as $row) :
							$currentRow  = ++$i;
							$Unit_Type_ID2 = $row->unit_type_id2;

							$sql1		= "SELECT * FROM tunittype WHERE Unit_Type_ID = '$Unit_Type_ID2'";
							$result1 	= $this->db->query($sql1)->result();
							foreach($result1 as $row1) :
							$Unit_Name2	= $row1->Unit_Type_Name;
							endforeach;
						?>
                            <tr>
                                <td width="2%" height="25" style="text-align:left"><?php print $currentRow; ?></td>
                                <td width="11%" style="text-align:left"> <?php print $row->Item_code; ?> </td>
                                <td width="11%" style="text-align:left"> <?php print $row->serialNumber; ?> </td>
                                <td width="30%" style="text-align:left"> <?php print $row->Item_Name; ?> </td>
                                <td width="8%" style="text-align:left"> <?php print number_format($row->request_qty, $decFormat); ?> | <?php print number_format($row->request_qty2, $decFormat); ?> </td>
                                <td width="6%" style="text-align:left"> <?php print $row->Unit_Type_Name; ?> | <?php print $Unit_Name2; ?> </td>
                                <td width="32%" style="text-align:left"> <?php print $row->remarks; ?> </td>
                            </tr>
                        <?php
							endforeach;
						}
					?>
                </table>
                </div>        	</th>
        </tr>
        <tr>
          <th colspan="5" align="left" class="style1"><input type="submit" class="button_css" name="submit" id="submit" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" align="left" />
          <?php 
					if ( ! empty($link))
					{
						foreach($link as $links)
						{
							echo $links;
						}
					}
				?></th>
        </tr>
        <tr>
          <th colspan="5" align="left" class="style1"><?php 
                    echo ! empty($message) ? '<p class="message">' . $message . '</p>': ''; 
                    echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
                ?></th>
        </tr> 
    </table>
</form>
</body>
</html>
