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
	if($task == 'add')
	{
		$default['PR_DepID'] = '';
		$default['PR_EmpID'] = '';
		$default['Vend_Code'] = '';
		foreach($viewDocPattern as $row) :
			$Pattern_Code = $row->Pattern_Code;
			$Pattern_Position = $row->Pattern_Position;
			$Pattern_YearAktive = $row->Pattern_YearAktive;
			$Pattern_MonthAktive = $row->Pattern_MonthAktive;
			$Pattern_DateAktive = $row->Pattern_DateAktive;
			$Pattern_Length = $row->Pattern_Length;
			$useYear = $row->useYear;
			$useMonth = $row->useMonth;
			$useDate = $row->useDate;
		endforeach;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive = date('Y');
			$Pattern_MonthAktive = date('m');
			$Pattern_DateAktive = date('d');
		}
		$year = (int)$Pattern_YearAktive;
		$month = (int)$Pattern_MonthAktive;
		$date = (int)$Pattern_DateAktive;
	
		$this->db->where('Patt_Year', $year);
		//$this->db->where('Patt_Month', $month);
		//$this->db->where('Patt_Date', $date);
		$myCount = $this->db->count_all('TPReq_Header');
		
		$sql = "SELECT MAX(Patt_Number) as maxNumber FROM TPReq_Header
				WHERE Patt_Year = $year";
		$result = $this->db->query($sql)->result();
		if($myCount>0)
		{
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	else	{		$myMax = 1;	}
		
		$thisMonth = $month;
		
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
		
		$thisDate = $date;
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;
		
		//echo $pattMonth;
		//echo "&nbsp;";
		//echo $pattDate;
		
		// group year, month and date
		if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$year$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$year$pattMonth";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$year$pattDate";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "$year";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$pattMonth";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$pattDate";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "";
		
			
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		$len = strlen($lastPatternNumb);
		
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
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($Pattern_Length==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$lastPatternNumb = $nol.$lastPatternNumb;
		$DocNumber = "$Pattern_Code$groupPattern-$lastPatternNumb";
		
		
		$PR_DateY = date('Y');
		$PR_DateM = date('m');
		$PR_DateD = date('d');
		$PR_Date = "$PR_DateY-$PR_DateM-$PR_DateD";
		$Patt_Year = date('Y');
	}
	else
	{
		$DocNumber = $default['PR_Number'];
		$lastPatternNumb1 = $default['Patt_Number'];
		$PR_Date = $default['PR_Date'];
		$Patt_Year = $default['Patt_Year'];
		$Approval_Status = $default['Approval_Status'];
	}
?>

<form name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="validateInVendCat();">
	<input type="Hidden" name="rowCount" id="rowCount" value="0">
    <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
        <tr>
            <th width="17%" align="left" class="style1">Purhase Requisition Number</th>
          <th width="1%" align="left" class="style1">:</th>
<th width="37%" align="left" class="style1"> <?php echo $DocNumber; ?>
        <input type="hidden" class="textbox" name="PR_Number" id="PR_Number" size="30" value="<?php echo set_value('PR_Number', isset($default['PR_Number']) ? $default['PR_Number'] : $DocNumber); ?>" />
        <input type="hidden" class="textbox" name="Patt_Year" id="Patt_Year" size="30" value="<?php echo set_value('Patt_Year', isset($default['Patt_Year']) ? $default['Patt_Year'] : $Patt_Year); ?>" /></th>
      <th width="11%" align="left" class="style1">Date</th>
      <th width="34%" align="left" class="style1">: &nbsp;
		  <script type="text/javascript">SunFishERP_DateTimePicker('PR_Date','<?php echo $PR_Date;?>','onMouseOver="mybirdthdates();"');</script>
          <input type="hidden" class="textbox" id="PR_Date1" name="PR_Date1" size="20" value="<?php echo $PR_Date; ?>" />
          <input type="hidden" class="textbox" id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" />      </th>
      </tr>
        <tr>
            <th align="left" class="style1">Request Class</th>
            <th align="left" class="style1">:</th>
            <th colspan="3" align="left" class="style1">
                <select name="PR_Class" id="PR_Class" class="listmenu">
                    <option value="Normal">Normal</option>
                    <option value="Urgent">Urgent</option>
                    <option value="Important">Important</option>
                </select>            </th>
        </tr>
        <tr>
            <th align="left" class="style1">Request Type</th>
            <th align="left" class="style1">:</th>
            <th align="left" class="style1">
                <select name="PR_Type" id="PR_Type" class="listmenu" onChange="selPR_Type(this)">
                    <option value="Internal">Internal</option>
                    <option value="Project">Project</option>
                </select>            </th> 
            <th align="left" class="style1" id="labelProject1" style="display:none">Project No.</th>
            <th align="left" class="style1" id="labelProject2" style="display:none">: &nbsp;<input type="text" class="textbox" name="PR_ProjectCode" id="PR_ProjectCode" size="20" value="<?php echo set_value('PR_ProjectCode', isset($default['PR_ProjectCode']) ? $default['PR_ProjectCode'] : ''); ?>" /> <?php echo form_error('PR_ProjectCode', '<p class="field_error">', '</p>');?>            </th>
        </tr>
        <tr>
            <th align="left" class="style1">Requester Department</th>
            <th align="left" class="style1">:</th>
            <th align="left" class="style1"><span class="style1">
              <select name="PR_DepID" id="PR_DepID" class="listmenu">
                <?php echo $i = 0;
			if($recordcountDept > 0)
			{
			foreach($viewDepartment as $row) :
			?>
                <option value="<?php echo $row->Dept_ID; ?>" <?php if($default['PR_DepID'] == $row->Dept_ID) { ?> selected <?php } ?>><?php echo $row->Dept_Name; ?></option>
                <?php
			 endforeach;
			 }
			 else
			 {
			 ?>
                <option value="none">--- None ---</option>
                <?php
			 }
			 ?>
              </select>
            </span>            </th> 
            <th align="left" class="style1">Requester Name</th>
            <th align="left" class="style1"><span class="style1">:&nbsp;
              <select name="PR_EmpID" id="PR_EmpID" class="listmenu">
                <?php echo $i = 0;
			if($recordcountEmpDept > 0)
			{
			foreach($viewEmployeeDept as $row) :
			?>
                <option value="<?php echo $row->Emp_ID; ?>" <?php if($default['PR_EmpID'] == $row->Emp_ID) { ?> selected <?php } ?>>
					<?php echo $row->First_name; echo '&nbsp;'; echo $row->Middle_Name; echo '&nbsp;'; echo $row->Last_Name; ?>                </option>
             <?php
			 endforeach;
			 }
			 else
			 {
			 ?>
                <option value="none">--- None ---</option>
                <?php
			 }
			 ?>
              </select>
            </span>            </th>
        </tr>
        <tr>
            <th align="left" class="style1">Vendor Name</th>
            <th align="left" class="style1">:</th>
            <th colspan="3" align="left"><span class="style1">
              <select name="Vend_Code" id="Vend_Code" class="listmenu">
              	<option value="none">--- None ---</option>
                <?php echo $i = 0;
				if($recordcountVend > 0)
				{
				foreach($viewvendor as $row) :
				?>
					<option value="<?php echo $row->Vend_Code; ?>" <?php if($default['Vend_Code'] == $row->Vend_Code) { ?> selected <?php } ?>>
					<?php echo $row->Vend_Name; ?>                    	</option>
					<?php
				 endforeach;
				 }
				 else
				 {
				 ?>
					<option value="none">--- No Vendor Found ---</option>
					<?php
				 }
				 ?>
				  </select>
            </span>      	</th> 
      </tr>
        <tr>
            <th align="left" class="style1" valign="top">Notes</th>
            <th align="left" class="style1" valign="top">:</th>
            <th align="left" class="style1"><textarea name="PR_Notes" class="textbox" id="PR_Notes" cols="30" style="height:70px"><?php echo set_value('PR_Notes', isset($default['PR_Notes']) ? $default['PR_Notes'] : ''); ?></textarea>
            <?php echo form_error('PR_Notes', '<p class="field_error">', '</p>');?> eee           </th> 
            <th align="left" class="style1" valign="top">Choose Status</th>
            <th align="left" class="style1" valign="top">:
            <label>
            	<input type="radio" name="Approval_Status" id="AS1" value="1" <?php if($Approval_Status == 1) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> New
            	<input type="radio" name="Approval_Status" id="AS2" value="2" <?php if($Approval_Status == 2) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> 
            	Confirm
            	<input type="radio" name="Approval_Status" id="AS3" value="3" <?php if($Approval_Status == 3) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> 
            	Close
        	</label></th>
        </tr>
        <tr>
            <th colspan="5" align="left" class="style1" style="font-style:italic">
            	<script>
					var url = "<?php echo base_url().'index.php/c_purchase/purchase_requisition/popupallitem';?>";
					function selectitem()
					{
						window.open(url,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
					}
				</script>
                <a href="javascript:void(null);" onClick="selectitem();">
                Add Item [+]                </a>
                <?php /*?>-- 
                Delete Item [-]<?php */?>            </th>
      </tr>
        <tr>
            <th colspan="5" align="left" class="style1">
            	<div class="CSSTableGenerator">
                <table width="100%" border="0" id="tbl" >
                    <tr>
                      	<td width="2%" height="25" style="text-align:left">
                   		  <input type="checkbox" name="HChkAllItem" id="HChkAllItem" onClick="check_all(this)" />                        </td>
                      <td width="9%" style="text-align:center">Item Code</td>
                      <td width="9%" style="text-align:center">Serial Number</td>
                   	  <td width="28%" style="text-align:center">Item Name</td>
                   	  <td width="7%" style="text-align:center">Qty 1</td>
                      <td width="10%" style="text-align:center">Unit</td>
                   	  <td width="4%" style="text-align:center">Qty 2</td>
                      <td width="14%" style="text-align:center">Unit</td>
                      <td width="17%" style="text-align:center">Remarks</td>
                  </tr>
                    <?php
                    if($task == 'edit')
					{
						$sql		= "SELECT A.PR_Number,A.Item_code,A.unit_type_id,A.request_qty,A.unit_type_id2,A.request_qty2,A.remarks,A.desc1,
										B.Item_Name,B.serialNumber,C.Unit_Type_Code,C.Unit_Type_Name
										FROM TPReq_Detail A
										INNER JOIN TItem B ON A.Item_code = B.Item_code
										INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id
										WHERE PR_Number = '$DocNumber'";
						// count data
							$resultCount = $this->db->where('PR_Number', $DocNumber);
							$resultCount = $this->db->count_all_results('TPReq_Detail');
						// End count data
						$result = $this->db->query($sql)->result();
						if($resultCount > 0)
						{
							foreach($result as $row) :
							$currentRow  = ++$i;
						?>
                            <tr>
                                <td width="2%" height="25" style="text-align:left">
                           	    <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php print $currentRow; ?>" onClick="pickThis(this,<?php print $currentRow; ?>)">
                                	<input type="Checkbox" style="display:none" id="chk<?php print $currentRow; ?>" name="chk<?php print $currentRow; ?>" value="<?php print $currentRow; ?>" >                                </td>
                          <td width="9%" style="text-align:left">
                   	<input type="hidden" id="data[<?php echo $currentRow; ?>][PR_Number]" name="data[<?php echo $currentRow; ?>][PR_Number]" value="<?php print $row->PR_Number; ?>" width="10" size="15" readonly class="textbox">
                                    <input type="hidden" id="data<?php echo $currentRow; ?>Item_code" name="data[<?php echo $currentRow; ?>][Item_code]" value="<?php print $row->Item_code; ?>" width="10" size="15" readonly class="textbox">
									<?php print $row->Item_code; ?>                                </td>
                          <td width="9%" style="text-align:left">
                    <input type="hidden" id="data<?php echo $currentRow; ?>serialNumber" name="data[<?php echo $currentRow; ?>][serialNumber]" value="<?php print $row->serialNumber; ?>" width="10" size="15" readonly class="textbox">
                                 	<?php print $row->serialNumber; ?>                                </td>
                              <td width="28%" style="text-align:left"> <?php print $row->Item_Name; ?> </td>
                          <td width="7%" style="text-align:center" nowrap>
               	<input type="text" name="data[<?php echo $currentRow; ?>][request_qty]" id="data<?php echo $currentRow; ?>request_qty" size="6" value="<?php print number_format($row->request_qty, $decFormat); ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >                                </td>
                          <td width="10%" style="text-align:left" nowrap>
<input type="hidden" name="data[<?php echo $currentRow; ?>][unit_type_id]" id="data<?php echo $currentRow; ?>unit_type_id" size="15" value="<?php print $row->unit_type_id; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >
                                 	<?php print $row->Unit_Type_Name; ?>                                </td>
                          <td width="4%" style="text-align:center" nowrap>
           	<input type="text" name="data[<?php echo $currentRow; ?>][request_qty2]" id="data<?php echo $currentRow; ?>request_qty2" size="6" value="<?php print number_format($row->request_qty2, $decFormat); ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >                                </td>
                                <td width="14%" style="text-align:left" nowrap>
                   	        <input type="hidden" name="data[<?php echo $currentRow; ?>][unit_type_id2]" id="data<?php echo $currentRow; ?>unit_type_id2" size="15" value="<?php print $row->unit_type_id2; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >
                                 	<?php print $row->Unit_Type_Name; ?>                                </td>
                          <td width="17%" style="text-align:center">
                       	<input type="text" name="data[<?php echo $currentRow; ?>][remarks]" id="data<?php echo $currentRow; ?>remarks" size="35" value="<?php print $row->remarks; ?>" class="textbox">                            	</td>
                  </tr>
                        <?php
							endforeach;
						}
					}
						if($task != 'add')
						{
							?>
								<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
							<?php
						}
					?>
                </table>
                </div>        	</th>
        </tr>
        <tr>
            <th colspan="5" align="left" class="style1">
            <?php
            if(($task == 'add') or ($task != 'add' && ($Approval_Status != 1 && $Approval_Status != 3)))
				{
			?>
          		<input type="submit" class="button_css" name="submit" id="submit" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" align="left" />
            <?php
				}
			?>
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
<script>
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}
		
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');	
		
		var objTable, objTR, objTD, intIndex, arrItem;
		var PR_Numberx = "<?php echo $DocNumber; ?>";
		ilvl = arrItem[1];

		validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}
		itemcode = arrItem[0];
		itemserial = arrItem[1];
		itemname = arrItem[2];
		itemUnitType = arrItem[3];
		itemNameType = arrItem[4];
		itemUnitType2 = arrItem[5];
		itemNameType2 = arrItem[6];
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';	
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+arrItem[0]+'<input type="hidden" id="data['+intIndex+'][PR_Number]" name="data['+intIndex+'][PR_Number]" value="'+PR_Numberx+'" width="10" size="15" readonly class="textbox"><input type="hidden" id="data'+intIndex+'Item_code" name="data['+intIndex+'][Item_code]" value="'+arrItem[0]+'" width="10" size="15" readonly class="textbox">';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+arrItem[1]+'<input type="hidden" id="data'+intIndex+'serialNumber" name="data['+intIndex+'][serialNumber]" value="'+arrItem[1]+'" width="10" size="15" readonly class="textbox">';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][request_qty]" id="data'+intIndex+'request_qty" size="6" value="" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+arrItem[4]+'<input type="hidden" name="data['+intIndex+'][unit_type_id]" id="data'+intIndex+'unit_type_id" size="15" value="'+arrItem[3]+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][request_qty2]" id="data'+intIndex+'request_qty2" size="6" value="" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+arrItem[6]+'<input type="hidden" name="data['+intIndex+'][unit_type_id2]" id="data'+intIndex+'unit_type_id2" size="6" value="'+arrItem[5]+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][remarks]" id="data'+intIndex+'remarks" size="35" value="" class="textbox">';
		
		document.getElementById('totalrow').value = intIndex;
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function validateDouble(vcode,serialNumber) 
	{
		var thechk=new Array();
		var duplicate = false;
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'Item_code').value;
				var iparent= document.getElementById('data'+i+'serialNumber').value;
				if (elitem1 == vcode && iparent == serialNumber)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
</script>
</body>
</html>
