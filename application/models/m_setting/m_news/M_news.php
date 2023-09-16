<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Agustus 2017
 * File Name	= M_news.php
 * Location		= -
*/

class M_news extends CI_Model
{
	function count_all_news($Emp_ID) // OK
	{
		$sql = "tbl_news_header WHERE NEWSH_CREATER IN ('All','$Emp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_all_news($Emp_ID) // OK
	{
		$sql = "SELECT * FROM tbl_news_header WHERE NEWSH_CREATER IN ('All','$Emp_ID') ORDER BY NEWSH_CREATED DESC";
		return $this->db->query($sql);
	}
	
	function addH($InsNewsH) //OK
	{
		$this->db->insert('tbl_news_header', $InsNewsH);
	}
	
	function addD($InsNewsD) //OK
	{
		$this->db->insert('tbl_news_detail', $InsNewsD);
	}
	
	function get_news($NEWSH_CODE) // OK
	{		
		$sql = "SELECT * FROM tbl_news_header WHERE NEWSH_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function update($NEWSH_CODE, $UpdNewsH) // USE
	{
		$this->db->where('NEWSH_CODE', $NEWSH_CODE);
		$this->db->update('tbl_news_header', $UpdNewsH);
	}
	
	function viewNews($NEWSD_ID) // OK
	{		
		$sql = "SELECT * FROM tbl_news_detail WHERE NEWSD_ID = '$NEWSD_ID'";
		return $this->db->query($sql);
	}
	
	function delDetail($NEWSH_CODE) // OK
	{		
		$sql = "DELETE FROM tbl_news_detail WHERE NEWSD_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function get_IMG($NEWSH_CODE) // OK
	{		
		$sql = "SELECT NEWSD_IMG, NEWSD_IMG1, NEWSD_IMG2,NEWSD_IMG3, NEWSD_IMG4
					FROM tbl_news_detail WHERE NEWSD_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function count_allnews() // OK
	{
		$sql = "tbl_news_header WHERE NEWSH_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_allnews() // OK
	{
		$sql = "SELECT * FROM tbl_news_header WHERE NEWSH_STAT = '1' ORDER BY NEWSH_CREATED DESC";
		return $this->db->query($sql);
	}
	
	function viewNews_list($NEWSH_CODE) // OK
	{		
		$sql = "SELECT * FROM tbl_news_detail WHERE NEWSD_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
}
?>