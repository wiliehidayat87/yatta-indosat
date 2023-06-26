<?php

/*
 * 
 *  Crepo tool for XMP
 *  Content Repository db model
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-09-25 17:03:42 +0700 (Tue, 25 Sep 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2934 $
 * 
 */

class Crepo_model extends CI_Model {

    public $db,
           $db_table_schedule = "push_schedule",
           $db_table_content = "push_content";

    function __construct() {
        parent::__construct();        
    }

    
}
?>