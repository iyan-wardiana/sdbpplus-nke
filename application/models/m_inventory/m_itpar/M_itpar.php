<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 Agustus 2022
 * File Name	= M_itpar.php
 * Location		= -
*/

class M_itpar extends CI_Model
{
	function get_AllDataIPC($search) // GOOD
	{
		$sql = "tbl_item_parent WHERE PITM_CODE LIKE '%$search%' ESCAPE '!' OR PITM_NAME LIKE '%$search%' ESCAPE '!'";
		//return $this->db->count_all($sql);
		return $sql;
	}
	
	function get_AllDataIPL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_item_parent
						WHERE PITM_CODE LIKE '%$search%' ESCAPE '!' OR PITM_NAME LIKE '%$search%' ESCAPE '!' ORDER BY PITM_CODE, $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_item_parent
						WHERE PITM_CODE LIKE '%$search%' ESCAPE '!' OR PITM_NAME LIKE '%$search%' ESCAPE '!' ORDER BY PITM_CODE";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_item_parent
						WHERE PITM_CODE LIKE '%$search%' ESCAPE '!' OR PITM_NAME LIKE '%$search%' ESCAPE '!' ORDER BY PITM_CODE, $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_item_parent
						WHERE PITM_CODE LIKE '%$search%' ESCAPE '!' OR PITM_NAME LIKE '%$search%' ESCAPE '!' ORDER BY PITM_CODE LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function add($iparcomp) // OK
	{
		$this->db->insert('tbl_item_parent', $iparcomp);
	}
	
	function get_itempar_by_code($PITM_CODE) // OK
	{
		$sql	= "SELECT * FROM  tbl_item_parent
					WHERE PITM_CODE = '$PITM_CODE'";
		return $this->db->query($sql);
	}
	
	function update($IC_Num, $iparcomp) // OK
	{
		$this->db->where('IC_Num', $IC_Num);
		$this->db->update('tbl_item_parent', $iparcomp);
	}
}
?>