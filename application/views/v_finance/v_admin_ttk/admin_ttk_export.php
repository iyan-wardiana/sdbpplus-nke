<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Agustus 2015
 * File Name	= decreaseinvoice_export.php
 * Function		= -
 * Location		= -
*/
?>

<?php	
	$FileUpName;
	$myPath = "system/application/views/v_finance/v_decreaseinvoice/Uploads/$FileUpName";
	//return false;
	$file = file(base_url() . "$myPath"); # read file into array
	$count = count($file);
	if($count > 0) # file is not empty
	{
		$milestone_query = "INSERT into tdecreaseinvoice(index_no, ttk_no, ttk_date, ttk_duedate, ttk_supplier, ttk_projcode, ttk_nominal, ttk_voucherno) values";
		$i = 1;
		foreach($file as $row)
		{
			$milestone = explode('|',$row);
			$milestone_query .= "('$milestone[0]', '$milestone[1]', '$milestone[2]', '$milestone[3]', '$milestone[4]', '$milestone[5]', '$milestone[6]', '$milestone[7]')";
			$milestone_query .= $i < $count ? ',':'';
			$i++;
		}
		mysql_query($milestone_query) or die(mysql_error());
	}
			
	$header = '';
	$result ='';
	
	$SQL = "SELECT  * FROM tdecreaseinvoice";
	$exportData = mysql_query ($SQL) or die ("Sql error : " . mysql_error());
	$fields = mysql_num_fields ($exportData);
	 
	for ($i = 0; $i < $fields; $i++)
	{
		$header .= mysql_field_name($exportData , $i) . "\t";
	}
	 
	while( $row = mysql_fetch_row($exportData))
	{
		$line = '';
		foreach( $row as $value )
		{                                            
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
		}
		$result .= trim($line) . "\n";
	}
	$result = str_replace("\r","", $result);
	 
	if ($result == "")
	{
		$result = "\nNo Record(s) Found!\n";                        
	}
	 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=export_txt.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$result";
?>
