<?php
class Menu_model extends CI_Model {
	public $db_table = "acc_menu";
	
	function __construct() {
		parent::__construct();
        $this->load->database('reports');
	}

    
    public function getMenuList($offset, $limit, $search = "") {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $sql_query  = "SELECT a.id, a.menu, a.link, a.sort, a.status, b.menu as parent FROM ".$this->db_table." as a ";
        $sql_query .= "LEFT JOIN ".$this->db_table." as b on a.parent=b.id ";
        $sql_query .= (!empty ($search)) ? "WHERE (UPPER(a.menu) LIKE '%" . $search . "%' OR UPPER(a.link) LIKE '%" . $search . "%' OR UPPER(a.sort) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.parent ASC, a.sort ";
        $sql_limit  = "LIMIT $offset, $limit ";

        try {
            $query     = $this->db_reports->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_reports->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array (
                'query'  => $sql_query . $sql_limit,
                'total'  => $total,
                'result' => array (
                'data'   => $queryData->result_array(),
                'total'  => $totalData
                )
            );
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }
    
    public function readParentList(){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $sql    = "SELECT * FROM ".$this->db_table." ";
        $sql   .= "WHERE parent='0' ";
        $sql   .= "ORDER BY id ";
        $query	= $this->db_reports->query($sql);
		
        return $query->result_array();
    }
    
    public function check_menu_name($menu_name){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $sql   = "SELECT * FROM ".$this->db_table." WHERE menu=? "; 
  	$query = $this->db_reports->query($sql,array($menu_name));
  	
        if($query->num_rows() != 0){
            return TRUE;
        }
        
        return FALSE;        
    }
    
    public function addMenu($menu_name, $parent ,$link, $status){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $result = array ();
        
        $sql        = "SELECT * FROM ".$this->db_table." WHERE parent = $parent ";
        $query      = $this->db_reports->query($sql);
        $sort_num   = $query->num_rows();

        try {
            $data = array ('menu'   => $menu_name,
                           'parent' => $parent,
                           'link'   => $link,
                           'status' => $status
                   );
            $this->db_reports->set('sort', $sort_num + '1', FALSE);
            $this->db_reports->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editMenu($id) {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $this->db_reports->select('menu, parent, link, sort, status');
        $this->db_reports->where('id', $id);

        $query = $this->db_reports->get($this->db_table);

        return $query->result_array();
    }
    
    public function updateMenu($id, $menu_name, $parent, $link, $sort, $sort_old, $status)
    {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $result = array ();        
        
        if ($sort==$sort_old){
            try {
                $data = array ( 'menu'      => $menu_name, 
                                'parent'    => $parent,
                                'link'      => $link,
                                'sort'      => $sort,
                                'status'    => $status
                );

                $this->db_reports->where('id', $id);                        
                $this->db_reports->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
            }

            catch (Exception $e) {
                $result = array ('status' => FALSE, 'message' => $e->getMessage());
            }
            
        }else{
            $sql   = "SELECT id FROM ".$this->db_table." WHERE sort=? AND parent=?"; 
            $query = $this->db_reports->query($sql, array($sort, $parent));
            $data  = $query->result_array();
            
            foreach ($data as $_data){
                $id_old = $_data['id'];
            }
            
            if($query->num_rows() != 0)
            {
                try {
                $data = array ( 
                                'sort'      => $sort_old
                );

                $this->db_reports->where('id', $id_old);                        
                $this->db_reports->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
                }

                catch (Exception $e) {
                    $result = array ('status' => FALSE, 'message' => $e->getMessage());
                }
            }
            
            try {
                $data = array ( 'menu'      => $menu_name, 
                                'parent'    => $parent,
                                'link'      => $link,
                                'sort'      => $sort,
                                'status'    => $status
                );

                $this->db_reports->where('id', $id);                        
                $this->db_reports->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
            }

            catch (Exception $e) {
                $result = array ('status' => FALSE, 'message' => $e->getMessage());
            }
        }
        return $result;
    }
    
    public function deleteMenu($id) {
        $this->db_reports = $this->load->database('reports', TRUE);
        $result = array ();
        
        try {
            $data = array ('status' => '0');
            
            $this->db_reports->where('id', $id);
            $this->db_reports->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

}
?>
