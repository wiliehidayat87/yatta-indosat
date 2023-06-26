<?php
class Navigation_model extends CI_Model {
    public $group_menu = "";
    public $db_xmptool;
    
	public function __construct() {
            parent::__construct();
            $this->db_xmptool = $this->load->database('default', TRUE);
            $this->group_menu   = $this->session->userdata('groupMenu');
	}

    public function getMenuHtml() {                    
        $result = "";
        $data   = $this->getMenuList(0, $this->group_menu);

        foreach ($data as $dt) {
            $id   = $dt['id'];
            $menu = $dt['menu'];
            $link = ($dt['link'] == "#") ? "#" : base_url() . $dt['link'];

            $result .= "<li>";
            $result .= "<a href=\"$link\">$menu</a>";
            $result .= $this->getSubMenuHtml($id);
            $result .= "</li>";
        }
        print_r($result);exit;
        return $result;
    }

    public function getSubMenuHtml($parent) {
        $result = "";
        $data   = $this->getMenuList($parent, $this->group_menu);

        if ($data) {
            $result .= "<ul>";

            foreach ($data as $dt) {
                $id   = $dt['id'];
                $menu = $dt['menu'];
                //$link = (empty ($dt['link'])) ? "" : base_url() . $dt['link'];
                $link = ($dt['link'] == "#") ? "#" : base_url() . $dt['link'];
                
                $result .= "<li>";
                $result .= "<a href=\"$link\">$menu</a>";
                $result .= $this->getSubMenuHtml($id);
                $result .= "</li>";
            }

            $result .= "</ul>";
        }

        return $result;
    }

    public function getMenuList($parent = 0, $group_menu_id = "") {
        $this->db_xmptool = $this->load->database('default', TRUE);
        
        $sql_query  = "SELECT a.id, a.menu, a.parent, a.link ";
        $sql_query .= "FROM menu a ";
        $sql_query .= "WHERE a.parent = " . $parent . " AND a.status = '1' ";
        $sql_query .= (!empty ($group_menu_id)) ? "AND a.id IN (" . $group_menu_id . ") " : "";
        $sql_query .= "ORDER BY a.sort ";

        try {
            $query  = $this->db_xmptool->query($sql_query);
            $total  = $query->num_rows();
            $result = $query->result_array();
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }

    public function getCheckBoxMenuList() {
        $this->db_xmptool = $this->load->database('default', TRUE);
        
        $sql_query  = "SELECT a.id, a.menu, a.parent, a.link ";
        $sql_query .= "FROM menu a ";
        $sql_query .= "WHERE a.status = '1' ";
        //$sql_query .= (!empty ($group_menu_id)) ? "AND a.id IN (" . $group_menu_id . ") " : "";
        $sql_query .= "ORDER BY a.parent, a.sort ";

        try {
            $query  = $this->db_xmptool->query($sql_query);
            //$total  = $query->num_rows();
            $result = $query->result_array();
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }
}
