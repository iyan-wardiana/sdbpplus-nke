<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Maret 2017
 * File Name	= bcaupload_report.php
*/
if($isExcel == 2)
{
	$empID	= $this->session->userdata('Emp_ID');
	$header = '';
	$result ='';
	
	$SQLC = "tbl_uploadbca_data WHERE empID = '$empID'";
	$fields = $this->db->count_all($SQLC);
	
	$SQL = "SELECT data_desc FROM tbl_uploadbca_data WHERE empID = '$empID'";
	$exportData = $this->db->query($SQL)->result();
	//$fields = mysql_num_fields ($exportData);
	//return false;
	/*for ($i = 0; $i < $fields; $i++)
	{
		$header .= mysql_field_name($exportData , $i) . "\t";
	}*/
	 
	//while($row = mysql_fetch_assoc($exportData))
	//{
		foreach($exportData as $therow )
		{
			$line = '';
			$value = $therow->data_desc;
			if ((!isset($value)) || ($value == ""))
			{
				$value = "\t";
				
			}
			else
			{
				$value = str_replace('"','""', $value);
				$value = '"' . $value . '"' . "\t";
			}
			$line .= $value;
			$result .= trim($line) . "\n";
		}
	//}
	$result = str_replace("\r","", $result);
	
	if ($result == "")
	{
		$result = "<br>No Record(s) Found!<br>";                        
	}
	echo $result;
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=export_txt.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	//print "$result";
}
?>