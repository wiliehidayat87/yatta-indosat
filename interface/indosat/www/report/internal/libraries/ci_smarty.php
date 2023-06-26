<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Smarty Class
 *
 * @package    CodeIgniter
 * @subpackage  Libraries
 * @category  Smarty
 * @author    Kepler Gelotte
 * @link    http://www.coolphptools.com/codeigniter-smarty
 */
require_once( BASEPATH . 'smarty/libs/Smarty.class.php' );

class CI_Smarty extends Smarty {

  function CI_Smarty()
  {
    parent::Smarty();

    $this->compile_dir  = COMPILE_PATH;
    $this->template_dir = TEMPLATE_PATH;

    $this->smarty = new Smarty();
    $this->compile_check = true;
    $this->debugging = false;

    /**
     * Global Smarty variables.
     */
    $this->assign('domain',     DOMAIN);
    $this->assign('cssPath',    CSS_PATH);
    $this->assign('imagePath',  IMAGE_PATH);
    $this->assign('jsPath',     JS_PATH);
    $this->assign('pluginPath', PLUGIN_PATH);
    $this->assign('maxDashboardChart', MAX_DASHBOARD_CHART);

    log_message('debug', "Smarty Class Initialized");
  }


  /**
   *  Parse a template using the Smarty engine
   *
   * This is a convenience method that combines assign() and
   * display() into one step.
   *
   * Values to assign are passed in an associative array of
   * name => value pairs.
   *
   * If the output is to be returned as a string to the caller
   * instead of being output, pass true as the third parameter.
   *
   * @access  public
   * @param  string
   * @param  array
   * @param  bool
   * @return  string
   */
  function view($template, $data = array(), $return = FALSE)
  {
    foreach ($data as $key => $val)
    {
      $this->assign($key, $val);
    }

    if ($return == FALSE)
    {
      $CI =& get_instance();
      $CI->output->final_output = $this->fetch($template);
      return;
    }
    else
    {
      return $this->fetch($template);
    }
  }
}
// END Smarty Class
