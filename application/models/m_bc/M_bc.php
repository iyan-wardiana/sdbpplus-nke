<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 08 Agustus 2023
	* File Name		= M_bc.php
	* Location		= -
*/

class M_bc extends CI_Model
{
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_bc A INNER JOIN tbl_employee B ON A.BC_SENDER = B.Emp_ID
				WHERE A.BC_TITLE LIKE '%$search%' ESCAPE '!' OR A.BC_CONTENT LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.*, CONCAT(B.First_Name,' ', B.Last_Name) AS complName
					FROM tbl_bc A INNER JOIN tbl_employee B ON A.BC_SENDER = B.Emp_ID
					WHERE A.BC_TITLE LIKE '%$search%' ESCAPE '!' OR A.BC_CONTENT LIKE '%$search%' ESCAPE '!'";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*, CONCAT(B.First_Name,' ', B.Last_Name) AS complName
					FROM tbl_bc A INNER JOIN tbl_employee B ON A.BC_SENDER = B.Emp_ID
					WHERE A.BC_TITLE LIKE '%$search%' ESCAPE '!' OR A.BC_CONTENT LIKE '%$search%' ESCAPE '!'
					LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
}
?>