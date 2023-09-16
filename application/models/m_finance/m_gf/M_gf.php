<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Agustus 2023
 * File Name	= M_gf.php
 * Location		= -
*/

class M_gf extends CI_Model
{
	function count_all_gf() // GOOD
	{
		return $this->db->count_all('tbl_gfile');
	}
	
	function get_all_gf() // GOOD
	{
		$sql = "SELECT * FROM tbl_gfile";
		return $this->db->query($sql);
	}
	
	function add($InsGF) // GOOD
	{
		$this->db->insert('tbl_gfile', $InsGF);
	}
	
	function get_gf($GF_NUM) // GOOD
	{		
		$sql = "SELECT * FROM tbl_gfile WHERE GF_NUM = '$GF_NUM'";
		return $this->db->query($sql);
	}
	
	function update($GF_NUM, $UpdGF) // GOOD
	{
		$this->db->where('GF_NUM', $GF_NUM);
		$this->db->update('tbl_gfile', $UpdGF);
	}

	function get_AllDataWOC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_wo_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE'						
						AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!' OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataWOL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE'							
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!' OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE'							
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!' OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE'							
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!' OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE'							
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!' OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function uplDOC_TRX($uplFile)
	{
		$this->db->insert("tbl_upload_doctrx", $uplFile);
	}

	function delUPL_DOC($GF_NUM, $PRJCODE, $fileName)
	{
		$this->db->delete("tbl_upload_doctrx", ["REF_NUM" => $GF_NUM, "PRJCODE" => $PRJCODE, "UPL_FILENAME" => $fileName]);
	}
}
?>