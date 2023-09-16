<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2014
 * File Name	= purchase_reqselectitem.css
 * Location		= ./system/application/views/v_purchase/v_requisition/purchase_reqselectitem.php
*/
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_menu.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_table.css'; ?>");</style>
<script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">

<div id="mainPopUp">
<table width="750" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<div class="HCSSTableGenerator">
            <table width="90%" border="0" style="size:auto">
              <tr>
                <td colspan="3" class="style2"><?php echo $h2_title; ?></td>
              </tr>
            </table>
            </div>
        </td>
    </tr>
	<tr>
    	<td>	
            <form action="<?php print site_url();?>/c_purchase/purchase_requisition/searchvendor" method=POST>
                <select name="selSearchType" id="selSearchType" class="listmenu">
                    <option value="Item_Code">Item Code</option>
                    <option value="Item_Name">Item Name</option>
                </select>
                <select name="selVendStatus" id="selVendStatus" class="listmenu">
                    <option value="Aktive">Active</option>
                    <option value="Reject">Reject</option>
                </select>
                <input type="text" name="txtSearch" id="txtSearch" class="textbox"value="" />
                <input type="submit" class="button_css" name="submit" id="submit" value=" search " />
                <?php echo anchor('c_purchase/purchase_requisition/','<input type="button" name="btnShowAll" id="btnShowAll" class="button_css" value=" Show All " />');?>
            </form>
        </td>
    </tr>
	<tr>
    	<td>
        	
        </td>
    </tr>
</table>
<form method="post" name="frmSearch" action="">
    <table width="750" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="CSSTableGenerator">
                    <table width="100%" border="0">
                        <tr>
                            <td width="4%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onclick="check_all(this)" /></td>
                          <td width="20%">Item Code</td>
                          <td width="52%">Item Code</td>
                          <td width="11%" nowrap>Item Qty </td>
                          <td width="13%" nowrap>Unit Type</td>
                      </tr>
                        <?php
                            $i = 0;
                            if($recordcountAllItem>0)
                            {
                                foreach($viewAllItem as $row) :
                                    $Item_Code = $row->Item_Code;
                                    $serialNumber = $row->serialNumber;
                                    $Item_Name = $row->Item_Name;
                                    $Item_Qty = $row->Item_Qty;
                                    $Item_Qty2 = $row->Item_Qty2;
                                    $Unit_Type_ID = $row->Unit_Type_ID1;
                                    $Unit_Type_ID2 = $row->Unit_Type_ID2;
                                    $Unit_Name = $row->Unit_Type_Name;
									
									$sql		= "SELECT * FROM tunittype WHERE Unit_Type_ID = '$Unit_Type_ID2'";
									$result 	= $this->db->query($sql)->result();
									foreach($result as $row) :
									$Unit_Name2	= $row->Unit_Type_Name;
									endforeach;
                        ?>
                                    <tr>
                                        <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $Item_Code;?>|<?php echo $serialNumber;?>|<?php echo $Item_Name;?>|<?php echo $Unit_Type_ID;?>|<?php echo $Unit_Name;?>|<?php echo $Unit_Type_ID2;?>|<?php echo $Unit_Name2;?>" onClick="pickThis(this);" /></td>
                                        <td><?php echo $Item_Code; ?></td>
                                        <td><?php echo $Item_Name; ?></td>
                                        <td nowrap><?php echo number_format($Item_Qty, $decFormat); ?> | <?php echo number_format($Item_Qty2, $decFormat); ?></td>
                                        <td nowrap><?php echo $Unit_Name; ?> | <?php echo $Unit_Name2; ?></td>
                                    </tr>
                        <?php
                            endforeach;
                            }
                            else
                            {
                        ?>
                                <tr>
                                        <td colspan="4" style="text-align:center"> ---- None ----</td>
                                    </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value=" Select" onclick="get_item()" />&nbsp;
            <input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" /></td>
        </tr>
    </table>
</form>

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

 </div>
</body>
</html>
