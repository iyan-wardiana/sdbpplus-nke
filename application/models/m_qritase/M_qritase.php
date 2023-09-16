<?php
/**
	* Author		= Dian Hermanto
	* Create Date	= 16 Juli 2023
	* File Name		= M_qritase.php
	* Location		= -
*/

class M_qritase extends CI_Model
{
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_qritase
				WHERE PRJCODE = '$PRJCODE' AND (QRIT_NOPOL LIKE '%$search%' ESCAPE '!' OR QRIT_DRIVER LIKE '%$search%' ESCAPE '!' 
					OR QRIT_DEST LIKE '%$search%' ESCAPE '!' OR QRIT_MATERIAL LIKE '%$search%' ESCAPE '!'
					OR QRIT_NOTES LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_qritase
						WHERE PRJCODE = '$PRJCODE' AND (QRIT_NOPOL LIKE '%$search%' ESCAPE '!' OR QRIT_DRIVER LIKE '%$search%' ESCAPE '!' 
							OR QRIT_DEST LIKE '%$search%' ESCAPE '!' OR QRIT_MATERIAL LIKE '%$search%' ESCAPE '!'
							OR QRIT_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_qritase
						WHERE PRJCODE = '$PRJCODE' AND (QRIT_NOPOL LIKE '%$search%' ESCAPE '!' OR QRIT_DRIVER LIKE '%$search%' ESCAPE '!' 
							OR QRIT_DEST LIKE '%$search%' ESCAPE '!' OR QRIT_MATERIAL LIKE '%$search%' ESCAPE '!'
							OR QRIT_NOTES LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
							

			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_qritase
						WHERE PRJCODE = '$PRJCODE' AND (QRIT_NOPOL LIKE '%$search%' ESCAPE '!' OR QRIT_DRIVER LIKE '%$search%' ESCAPE '!' 
							OR QRIT_DEST LIKE '%$search%' ESCAPE '!' OR QRIT_MATERIAL LIKE '%$search%' ESCAPE '!'
							OR QRIT_NOTES LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_qritase
						WHERE PRJCODE = '$PRJCODE' AND (QRIT_NOPOL LIKE '%$search%' ESCAPE '!' OR QRIT_DRIVER LIKE '%$search%' ESCAPE '!' 
							OR QRIT_DEST LIKE '%$search%' ESCAPE '!' OR QRIT_MATERIAL LIKE '%$search%' ESCAPE '!'
							OR QRIT_NOTES LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_qrit_by_number($QRIT_NUM) // OK
	{			
		$sql = "SELECT * FROM tbl_qritase WHERE QRIT_NUM = '$QRIT_NUM'";
		return $this->db->query($sql);
	}
}