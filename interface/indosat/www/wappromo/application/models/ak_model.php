<?php
class Ak_model extends CI_Model {
    public  $db_newwap,
            $db_xmp,
            $db_table   = "ak_schedule";
            //$db_table2  = "dev_wapreg_component";
            //$db_table3  = "adn";

    function __construct() {
                parent::__construct();

     //   $this->load->database();
    }

    function read_all_ak()
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql	= "SELECT * FROM ak_schedule WHERE status = '1' AND name != 'ALL' AND name != 'OFF'";
            $query	=  $this->db_newwap->query($sql);

            if($query->num_rows() != 0)
            {
                    return $query->result_array();
            }
            else
            {
                    return FALSE;
            }
    }

    function read_all()
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM ak_schedule WHERE status = '1' order by name desc";
            $query	= $this->db_newwap->query($sql);

            if($query->num_rows() != 0)
            {
                    return $query->result_array();
            }
            else
            {
                    return FALSE;
            }
    }

    function read_ak_by_id($id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM ak_schedule 
                                                            WHERE status = '1' AND name != 'ALL' AND name != 'OFF' AND id = ? LIMIT 1";
            $query	= $this->db_newwap->query($sql, $id);

            if($query->num_rows() != 0)
            {
                    return $query->result_array();
            }
            else
            {
                    return FALSE;
            }
    }

    function read_ak_by_name($name)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql	= "SELECT * FROM ak_schedule WHERE status = '1' AND name = ? LIMIT 1";
            $query	= $this->db_newwap->query($sql, $name);

            if($query->num_rows() != 0)
            {
                    return $query->result_array();
            }
            else
            {
                    return FALSE;
            }
    }

    function read_ak_by_name_id($id, $name)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM ak_schedule WHERE status = '1' AND name = ? AND id != ? LIMIT 1";
            $query	= $this->db_newwap->query($sql, array($name, $id));

            if($query->num_rows() != 0)
            {
                    return $query->result_array();
            }
            else
            {
                    return FALSE;
            }
    }

    function create_ak($name, $description)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "INSERT INTO ak_schedule(name, description, status) VALUES(?, ?, '1')";
            $query	= $this->db_newwap->query($sql, array($name, $description));

            return $query;		
    }

    function update_ak($id, $name, $description)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "UPDATE ak_schedule SET name = ?, description = ? WHERE id = ? AND status = '1' LIMIT 1";
            $query	= $this->db_newwap->query($sql, array($name, $description, $id));

            return $query;
    }

    function unactivate_ak($id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "UPDATE ak_schedule SET status = '0' WHERE id = ? AND name != 'ALL' AND name != 'OFF' LIMIT 1";
            $query	= $this->db_newwap->query($sql, $id);

            return $query;
    }
}

?>
