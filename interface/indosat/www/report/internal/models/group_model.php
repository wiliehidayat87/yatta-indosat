<?php
class Group_model extends CI_Model {
    public $db_reports,
		   $db_table1 = "acc_group";

	public function __construct() {
		parent::__construct();

        $this->db_reports = $this->load->database('reports', TRUE);
	}

    public function getGroupList($offset, $limit, $search = "") {
		$pSearch    = "%" . $search . "%";
        $sql_query  = "SELECT a.id, a.group_name, a.group_desc, a.group_menu, a.status FROM acc_group a ";
        $sql_query .= (!empty ($search)) ? sprintf("WHERE (UPPER(a.group_name) LIKE '%s' OR UPPER(a.group_desc) LIKE '%s' OR UPPER(a.group_menu) LIKE '%s' OR UPPER(a.status) LIKE '%s') ", $pSearch, $pSearch, $pSearch, $pSearch) : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = sprintf("LIMIT %d, %d ", $offset, $limit);

        try {
            $query     = $this->db_reports->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_reports->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array (
                'query'  => $sql_query . $sql_limit,
                'total'  => $total,
                'result' => array (
                    'data'  => $queryData->result_array(),
                    'total' => $totalData
                )
            );
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }

    public function addNewGroup($group_name, $group_desc, $group_menu) {
        $result = array ();
        
        try {
            $data = array (
                'group_name' => $group_name,
                'group_desc' => $group_desc,
                'group_menu' => $group_menu,
                'status'     => "1"
            );

            $this->db_reports->insert($this->db_table1, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    
    }

    public function editGroup($id) {
        $this->db_reports->select('group_name, group_desc, group_menu');
        $this->db_reports->where('id', $id);

        $query = $this->db_reports->get($this->db_table1);

        return $query->result_array();
    }

    public function updateGroup($id, $group_name, $group_desc, $group_menu) {
        $result = array ();

        try {
            $data = array (
                'group_name' => $group_name,
                'group_desc' => $group_desc,
                'group_menu' => $group_menu
            );

            $this->db_reports->where('id', $id);
            $this->db_reports->update($this->db_table1, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function deleteGroup($id) {
        try {
            $this->db_reports->where('id', $id);
            $this->db_reports->delete($this->db_table1);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function checkGroupList($group_name,$id) {
        $this->db_reports = $this->load->database('reports', TRUE);
                      
		$sql   = "SELECT * FROM acc_group WHERE group_name='$group_name' ";
        $sql   .=(!empty($id))?"AND id!='$id' ":""; 
        $query = $this->db_reports->query($sql);
                        	
        if($query->num_rows() != 0){
			return TRUE;
        }
            return FALSE;
              
    }
}
?>
