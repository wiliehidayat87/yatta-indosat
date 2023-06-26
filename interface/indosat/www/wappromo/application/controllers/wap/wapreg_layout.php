
<?php
class Wapreg_layout extends CI_Controller
{
	public $limit = 0;
	
	function __construct( )
	{
		parent::__construct();
		if ( !$this->session->userdata( 'wap_username' ) )
			redirect( base_url() . 'login' );
		$this->klogger->log( "" );
		$this->load->model( 'navigation_model' );
		$this->mysmarty->assign( 'navigation', $this->navigation_model->getMenuHtml() );
		$this->mysmarty->assign( 'wap_username', $this->session->userdata( 'wap_username' ) );
		$this->mysmarty->assign( 'base_url', base_url() );
		$this->limit = $this->config->item( 'limit' );
		$config      = get_config();
	}
	
	function index( $id = NULL )
	{
		$this->klogger->log( "" );
		$wapsite_id = $id;
		$this->klogger->log( "wapsite id: " . $wapsite_id );
		$this->load->model( array(
			 'wapreg',
			'wapreg_component' 
		) );
		$read_wapreg = $this->wapreg->read_wapreg_by_id( $wapsite_id );
		$this->klogger->log( "read wapreg:" . $read_wapreg );
		if ( $read_wapreg == FALSE )
			redirect( 'wap' );
		$this->mysmarty->assign( 'id', $id );
		$this->mysmarty->view( 'wap/wapreg_layout.html' );
	}
	
	function ajaxReadComponentById( )
	{
		$this->klogger->log( "" );
		$component_id = $this->input->post( 'param', TRUE );
		$this->klogger->log( "comp id:" . $component_id );
		$this->load->model( array(
			 'wapreg',
			'wapreg_component' 
		) );
		$read_component = $this->wapreg_component->read_component_by_id( $component_id );
		$this->klogger->log( "read_component:" . implode( "|", $read_component ) );
		$result                 = array( );
		$result['name']         = $read_component[0]['name'];
		$result['type']         = $read_component[0]['type'];
		$result['value']        = $read_component[0]['value'];
		$result['is_link']      = $read_component[0]['is_link'];
		$result['image']        = $read_component[0]['image'];
		$result['component-id'] = $component_id;
		$response               = array(
			 'result' => $result 
		);
		echo json_encode( $response );
		exit;
	}
	
	function ajaxUpdateComponent( )
	{
		$this->klogger->log( "" );
		$name         = str_replace( ' ', '_', $this->input->post( 'fld-name', TRUE ) );
		$type         = $this->input->post( 'fld-type', TRUE );
		$value        = $this->input->post( 'fld-value', TRUE );
		$is_link      = $this->input->post( 'fld-is_link', TRUE );
		$wapsite_id   = $this->input->post( 'fld-wapsite_id', TRUE );
		$component_id = $this->input->post( 'fld-component-id', TRUE );
		$image        = '';
		$error        = FALSE;
		if ( empty( $name ) || empty( $type ) || empty( $value ) ) {
			$error .= 'Required Field is Empty<br />';
		} else {
			if ( $is_link != 'yes' )
				$is_link = 'no';
			$this->load->model( array(
				 'wapreg',
				'wapreg_component' 
			) );
			$read_wapreg = $this->wapreg->read_wapreg_by_id( $wapsite_id );
			$is_exist    = $this->wapreg_component->read_component_by_id_wapid_name( $component_id, $wapsite_id, $name );
			if ( $is_exist != FALSE ) {
				$error .= 'Name for this component already exist<br />';
			} else {
				$image = $read_wapreg[0]['wap_name'] . '_' . $name;
				if ( isset( $_FILES['fld-image-1'] ) && $_FILES['fld-image-1']['size'] > 0 ) {
					$image_1   = $read_wapreg[0]['wap_name'] . '_' . $name . '_1';
					$do_upload = $this->upload( 'fld-image-1', $image_1 );
					if ( is_array( $do_upload ) )
						$error .= $do_upload['error'];
					$image_1 = $image_1 . '.jpg';
				}
				if ( isset( $_FILES['fld-image-2'] ) && $_FILES['fld-image-2']['size'] > 0 ) {
					$image_2   = $read_wapreg[0]['wap_name'] . '_' . $name . '_2';
					$do_upload = $this->upload( 'fld-image-2', $image_2 );
					if ( is_array( $do_upload ) )
						$error .= $do_upload['error'];
					$image_2 = $image_2 . '.jpg';
				}
				if ( isset( $_FILES['fld-image-3'] ) && $_FILES['fld-image-3']['size'] > 0 ) {
					$image_3   = $read_wapreg[0]['wap_name'] . '_' . $name . '_3';
					$do_upload = $this->upload( 'fld-image-3', $image_3 );
					if ( is_array( $do_upload ) )
						$error .= $do_upload['error'];
					$image_3 = $image_3 . '.jpg';
				}
			}
		}
		$a = 0;
		if ( $error == FALSE ) {
			if ( $image != '' ) {
				$a              = 1;
				$update_attempt = $this->wapreg_component->update_component( $component_id, $name, $type, $value, $is_link, $image_1 );
			} else {
				$a              = 2;
				$update_attempt = $this->wapreg_component->update_component_without_image( $component_id, $name, $type, $value, $is_link );
			}
		}
		if ( $error != FALSE ) {
			$this->mysmarty->assign( 'name', $name );
			$this->mysmarty->assign( 'type', $type );
			$this->mysmarty->assign( 'value', $value );
			$this->mysmarty->assign( 'is_link', $is_link );
			$this->mysmarty->assign( 'error', $error );
		} else {
			if ( isset( $_FILES['fld-image-1'] ) && $_FILES['fld-image-1']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
				$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'] );
			} else if ( isset( $_FILES['fld-image-2'] ) && $_FILES['fld-image-2']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
				$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'] );
			}
			if ( isset( $_FILES['fld-image-3'] ) && $_FILES['fld-image-3']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
				$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'] );
			} else {
			}
		}
		$response = array(
			 'result' => $update_attempt 
		);
		echo json_encode( $response );
		exit;
	}
	
	function add( )
	{
		$this->klogger->log( "" );
		$read_config = get_config();
		$wapsite_id  = $this->uri->segment( 4 );
		if ( empty( $wapsite_id ) )
			redirect( base_url() . 'wap/subscription' );
		$this->load->model( array(
			 'wapreg',
			'wapreg_component' 
		) );
		$read_wapreg = $this->wapreg->read_wapreg_by_id( $wapsite_id );
		if ( $read_wapreg == FALSE )
			redirect( $this->shortcode . '/wapreg/wapreg_home' );
		if ( $this->input->post( 'submit' ) == TRUE ) {
			$name    = str_replace( ' ', '_', $this->input->post( 'name', TRUE ) );
			$type    = $this->input->post( 'type', TRUE );
			$value   = $this->input->post( 'value' );
			$is_link = $this->input->post( 'is_link', TRUE );
			$image   = '';
			$error   = FALSE;
			if ( empty( $name ) || empty( $type ) || empty( $value ) ) {
				$error .= 'Required Field is Empty<br />';
			} else {
				if ( $is_link != 'yes' )
					$is_link = 'no';
				$is_exist = $this->wapreg_component->read_component_by_wapid_name( $wapsite_id, $name );
				if ( $is_exist != FALSE ) {
					$error .= 'Name for this component already exist<br />';
				} else {
					$image = "";
					if ( isset( $_FILES['component_image_1'] ) && $_FILES['component_image_1']['size'] > 0 ) {
						$image_1   = $read_wapreg[0]['wap_name'] . '_' . $name . '_1';
						$do_upload = $this->upload( 'component_image_1', $image_1 );
						if ( is_array( $do_upload ) )
							$error .= $do_upload['error'];
						$image_1 = $image_1 . '.jpg';
					}
					if ( isset( $_FILES['component_image_2'] ) && $_FILES['component_image_2']['size'] > 0 ) {
						$image_2   = $read_wapreg[0]['wap_name'] . '_' . $name . '_2';
						$do_upload = $this->upload( 'component_image_2', $image_2 );
						if ( is_array( $do_upload ) )
							$error .= $do_upload['error'];
						$image_2 = $image_2 . '.jpg';
					}
					if ( isset( $_FILES['component_image_3'] ) && $_FILES['component_image_3']['size'] > 0 ) {
						$image_3   = $read_wapreg[0]['wap_name'] . '_' . $name . '_3';
						$do_upload = $this->upload( 'component_image_3', $image_3 );
						if ( is_array( $do_upload ) )
							$error .= $do_upload['error'];
						$image_3 = $image_3 . '.jpg';
					}
					if ( ( !empty( $image_1 ) ) || ( !empty( $image_2 ) ) || ( !empty( $image_3 ) ) )
						$image = $read_wapreg[0]['wap_name'] . '_' . $name;
				}
			}
			if ( $error == FALSE ) {
				$max_sort       = (int) ( $this->wapreg_component->read_component_max_sort( $wapsite_id ) );
				$next_sort      = ( $max_sort == 0 ) ? 1 : $max_sort + 1;
				$insert_attempt = $this->wapreg_component->create_component( $wapsite_id, $name, $type, $value, $is_link, $image, $next_sort );
				if ( $insert_attempt == FALSE )
					$error .= 'Error inserting into database. Please retry<br />';
			}
			if ( $error != FALSE ) {
				$this->mysmarty->assign( 'name', $name );
				$this->mysmarty->assign( 'type', $type );
				$this->mysmarty->assign( 'value', $value );
				$this->mysmarty->assign( 'is_link', $is_link );
				$this->mysmarty->assign( 'error', $error );
			} else {
				if ( isset( $_FILES['component_image_1'] ) && $_FILES['component_image_1']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
					$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'], base_url() . $this->shortcode . '/wapreg/wapreg_layout/index/' . $wapsite_id );
					echo $html_code;
					exit;
				} else if ( isset( $_FILES['component_image_2'] ) && $_FILES['component_image_2']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
					$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'], base_url() . $this->shortcode . '/wapreg/wapreg_layout/index/' . $wapsite_id );
					echo $html_code;
					exit;
				} else if ( isset( $_FILES['component_image_3'] ) && $_FILES['component_image_3']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
					$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'], base_url() . $this->shortcode . '/wapreg/wapreg_layout/index/' . $wapsite_id );
					echo $html_code;
					exit;
				} else {
					redirect( base_url() . 'wap/wapreg_layout/index/' . $wapsite_id );
				}
			}
		}
		$type_component = array(
			 'image',
			'text',
			'title',
			'space',
			'url',
			'catalog-a',
			'pull' 
		);
		$type_link      = array(
			 'yes',
			'no' 
		);
		$this->mysmarty->assign( 'wapsite_id', $wapsite_id );
		$this->mysmarty->assign( 'type_component', $type_component );
		$this->mysmarty->assign( 'type_link', $type_link );
		$this->mysmarty->assign( 'page_title', 'Add Wap Component' );
		$this->mysmarty->view( 'wap/layout_add.html' );
	}
	
	function edit( )
	{
		$this->klogger->log( "" );
		$read_config  = get_config();
		$wapsite_id   = $this->uri->segment( 4 );
		$component_id = $this->uri->segment( 5 );
		if ( empty( $wapsite_id ) || empty( $component_id ) )
			redirect( base_url() . 'wap/subscription' );
		$this->load->model( array(
			 'wapreg',
			'wapreg_component' 
		) );
		$read_wapreg = $this->wapreg->read_wapreg_by_id( $wapsite_id );
		if ( $read_wapreg == FALSE )
			redirect( 'wapreg/wapreg_home' );
		$read_component = $this->wapreg_component->read_component_by_id( $component_id );
		if ( $read_component == FALSE )
			redirect( 'wapreg/wapreg_layout/' . $wapsite_id );
		if ( $this->input->post( 'submit' ) == TRUE ) {
			$name    = str_replace( ' ', '_', $this->input->post( 'name', TRUE ) );
			$type    = $this->input->post( 'type', TRUE );
			$value   = $this->input->post( 'value' );
			$is_link = $this->input->post( 'is_link', TRUE );
			$image   = '';
			$error   = FALSE;
			if ( empty( $name ) || empty( $type ) || empty( $value ) ) {
				$error .= 'Required Field is Empty<br />';
			} else {
				if ( $is_link != 'yes' )
					$is_link = 'no';
				$is_exist = $this->wapreg_component->read_component_by_id_wapid_name( $component_id, $wapsite_id, $name );
				if ( $is_exist != FALSE ) {
					$error .= 'Name for this component already exist<br />';
				} else {
					$image = $read_wapreg[0]['wap_name'] . '_' . $name;
					if ( isset( $_FILES['component_image_1'] ) && $_FILES['component_image_1']['size'] > 0 ) {
						$image_1   = $read_wapreg[0]['wap_name'] . '_' . $name . '_1';
						$do_upload = $this->upload( 'component_image_1', $image_1 );
						if ( is_array( $do_upload ) )
							$error .= $do_upload['error'];
						$image_1 = $image_1 . '.jpg';
					}
					if ( isset( $_FILES['component_image_2'] ) && $_FILES['component_image_2']['size'] > 0 ) {
						$image_2   = $read_wapreg[0]['wap_name'] . '_' . $name . '_2';
						$do_upload = $this->upload( 'component_image_2', $image_2 );
						if ( is_array( $do_upload ) )
							$error .= $do_upload['error'];
						$image_2 = $image_2 . '.jpg';
					}
					if ( isset( $_FILES['component_image_3'] ) && $_FILES['component_image_3']['size'] > 0 ) {
						$image_3   = $read_wapreg[0]['wap_name'] . '_' . $name . '_3';
						$do_upload = $this->upload( 'component_image_3', $image_3 );
						if ( is_array( $do_upload ) )
							$error .= $do_upload['error'];
						$image_3 = $image_3 . '.jpg';
					}
				}
			}
			if ( $error == FALSE ) {
				if ( strlen( $image_1 ) > 4 ) {
					$update_attempt = $this->wapreg_component->update_component( $component_id, $name, $type, $value, $is_link, $image_1 );
				} else {
					$update_attempt = $this->wapreg_component->update_component_without_image( $component_id, $name, $type, $value, $is_link );
				}
				if ( $update_attempt == FALSE )
					$error .= 'Error updating database. Please retry<br />';
			}
			if ( $error != FALSE ) {
				$this->mysmarty->assign( 'name', $name );
				$this->mysmarty->assign( 'type', $type );
				$this->mysmarty->assign( 'value', $value );
				$this->mysmarty->assign( 'is_link', $is_link );
				$this->mysmarty->assign( 'error', $error );
			} else {
				if ( isset( $_FILES['component_image_1'] ) && $_FILES['component_image_1']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
					$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'], base_url() . $this->shortcode . '/wapreg/wapreg_layout/index/' . $wapsite_id );
					echo $html_code;
					exit;
				} else if ( isset( $_FILES['component_image_2'] ) && $_FILES['component_image_2']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
					$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'], base_url() . $this->shortcode . '/wapreg/wapreg_layout/index/' . $wapsite_id );
					echo $html_code;
					exit;
				}
				if ( isset( $_FILES['component_image_3'] ) && $_FILES['component_image_3']['size'] > ( $read_config['alert_file_size'] * 1024 ) && $read_config['alert_file_size'] != 0 ) {
					$html_code = sprintf( '<html>
                                    <script>
                            alert("You\'re uploading a file that larger than %s Kb");
                            location = "%s";
                                    </script>
                            </html>', $read_config['alert_file_size'], base_url() . $this->shortcode . '/wapreg/wapreg_layout/index/' . $wapsite_id );
					echo $html_code;
					exit;
				} else {
					redirect( base_url() . '/wap/wapreg_layout/index/' . $wapsite_id );
				}
			}
		} else {
			$this->mysmarty->assign( 'name', $read_component[0]['name'] );
			$this->mysmarty->assign( 'type', $read_component[0]['type'] );
			$this->mysmarty->assign( 'value', $read_component[0]['value'] );
			$this->mysmarty->assign( 'is_link', $read_component[0]['is_link'] );
			$this->mysmarty->assign( 'image', $read_component[0]['image'] );
		}
		$type_component = array(
			 'image',
			 'text',
			 'title',
			 'space',
			 'url',
			 'catalog-a',
			 'pull' 
		);
		$type_link      = array(
			 'yes',
			 'no' 
		);
		$this->mysmarty->assign( 'type_component', $type_component );
		$this->mysmarty->assign( 'type_link', $type_link );
		$this->mysmarty->assign( 'wapsite_id', $wapsite_id );
		$this->mysmarty->assign( 'component_id', $component_id );
		$this->mysmarty->assign( 'page_title', 'Edit Wap Component' );
		$this->mysmarty->view( 'wap/layout_edit.html' );
	}
	
	function ajaxDelete( )
	{
		$this->klogger->log( "" );
		$wapsite_id = $this->input->post( 'wapsite', TRUE );
		;
		$params_id = $this->input->post( 'param', TRUE );
		;
		if ( empty( $wapsite_id ) || empty( $params_id ) )
			redirect( 'wap' );
		$this->load->model( 'wapreg_component' );
		$result   = $this->wapreg_component->unactivate_component_by_id( $params_id );
		$response = array(
			 'status' => TRUE 
		);
		echo json_encode( $response );
		exit;
	}
	
	public function ajaxReadWapregComponentsById( )
	{
		$this->klogger->log( "" );
		$debug      = array( );
		$wapsite_id = strtoupper( $this->input->post( "id" ) );
		if ( intval( $wapsite_id ) <= 0 ) {
			$wapsite_id = strtoupper( $this->input->get( "id" ) );
		}
		$page   = $this->uri->segment( 4 );
		$offset = ( isset( $page ) ) ? (int) $page : 0;
		$limit  = $this->limit;
		$paging = "";
		$result = "";
		$this->klogger->log( "start load  model" );
		$this->load->model( array(
			 'wapreg',
			'wapreg_component' 
		) );
		$this->klogger->log( "after model | wapsite id:" . $wapsite_id );
		$allComponent = $this->wapreg_component->read_all_component( $wapsite_id );
		$this->klogger->log( "after load all" );
		$max   = $this->wapreg_component->read_component_max_sort( $wapsite_id );
		$min   = $this->wapreg_component->read_component_min_sort( $wapsite_id );
		$total = $allComponent['total'];
		$data  = $allComponent['result']['data'];
		if ( !isset( $data ) or intval( $total ) == 0 ) {
			$this->klogger->log( "data component not found." );
			$this->return_component_empty();
			return;
		}
		if ( !is_array( $data ) ) {
			$this->klogger->log( "array component not exists." );
			$this->return_component_empty();
			return;
		}
		$this->klogger->log( "debug total:" . $total );
		foreach ( $data as $key => $dt ) {
			$id    = $dt['id'];
			$sort  = $dt['sort'];
			$name  = $dt['name'];
			$type  = $dt['type'];
			$value = $dt['value'];
			if ( $sort < $max ) {
				$url         = base_url() . 'wap/wapreg_layout/sort/' . $wapsite_id . '/' . $sort . '/' . ( $sort + 1 );
				$move        = "down";
				$down_button = "<a onclick=\"parent.moveComponent('$url', '$move', '$wapsite_id')\"> + </a>";
			} else {
				$down_button = "&nbsp;&nbsp;&nbsp";
			}
			if ( $sort > $min ) {
				$url       = base_url() . 'wap/wapreg_layout/sort/' . $wapsite_id . '/' . $sort . '/' . ( $sort - 1 );
				$move      = "up";
				$up_button = "<a onclick=\"parent.moveComponent('$url', '$move', '$wapsite_id')\"> - </a>";
			} else {
				$up_button = "&nbsp;";
			}
			$result .= "<tr>";
			$result .= "<td>$down_button&nbsp;&nbsp;&nbsp$up_button</td>";
			$result .= "<td>$sort</td>";
			$result .= "<td>$name</td>";
			$result .= "<td>$type</td>";
			$result .= "<td>$value</td>";
			$result .= "<td><div class=\"menulink\"><a href='" . base_url() . "wap/wapreg_layout/edit/" . $wapsite_id . "/" . $id . "'>Edit</a> <a onclick=\"deleteComponent($wapsite_id, $id);\">Delete</a></div></td>";
			$result .= "</tr>";
		}
		$this->klogger->log( "debug end loop" );
		$read_wapreg     = $this->wapreg->read_wapreg_by_id( $wapsite_id );
		$homepage_html_1 = $this->get_homepage_html( $read_wapreg[0]['wap_name'], 1 );
		$homepage_html_2 = $this->get_homepage_html( $read_wapreg[0]['wap_name'], 2 );
		$homepage_html_3 = $this->get_homepage_html( $read_wapreg[0]['wap_name'], 3 );
		$homepage_size_1 = $this->get_homepage_size( $read_wapreg[0]['wap_name'], 1 );
		$homepage_size_2 = $this->get_homepage_size( $read_wapreg[0]['wap_name'], 2 );
		$homepage_size_3 = $this->get_homepage_size( $read_wapreg[0]['wap_name'], 3 );
		$element_size_1  = $this->get_homepage_element_size( $homepage_html_1 );
		$element_size_2  = $this->get_homepage_element_size( $homepage_html_2 );
		$element_size_3  = $this->get_homepage_element_size( $homepage_html_3 );
		$total_size_1    = ( $homepage_size_1 + $element_size_1 ) / 1000;
		$total_size_2    = ( $homepage_size_2 + $element_size_2 ) / 1000;
		$total_size_3    = ( $homepage_size_3 + $element_size_3 ) / 1000;
		$this->klogger->log( "debug 1" );
		$load_time_1 = array( );
		$read_config = get_config();
		$this->klogger->log( "debug 2" );
		foreach ( $read_config['download_speed'] as $arch => $speed ) {
			$download_speed_1 = round( ( $total_size_1 / $speed ), 2 );
			array_push( $load_time_1, array(
				 'arch' => $arch,
				'speed' => $speed,
				'download_speed' => $download_speed_1 
			) );
		}
		$load_time_2 = array( );
		$this->klogger->log( "debug 3" );
		foreach ( $read_config['download_speed'] as $arch => $speed ) {
			$download_speed_2 = round( ( $total_size_2 / $speed ), 2 );
			array_push( $load_time_2, array(
				 'arch' => $arch,
				'speed' => $speed,
				'download_speed' => $download_speed_2 
			) );
		}
		$load_time_3 = array( );
		$this->klogger->log( "debug 4" );
		foreach ( $read_config['download_speed'] as $arch => $speed ) {
			$download_speed_3 = round( ( $total_size_3 / $speed ), 2 );
			array_push( $load_time_3, array(
				 'arch' => $arch,
				'speed' => $speed,
				'download_speed' => $download_speed_3 
			) );
		}
		$load_time                                = array(
			 1 => $load_time_1,
			2 => $load_time_2,
			3 => $load_time_3 
		);
		$homepage_size                            = array(
			 1 => $total_size_1,
			2 => $total_size_2,
			3 => $total_size_3 
		);
		$homepage_html                            = array(
			 1 => $homepage_html_1,
			2 => $homepage_html_2,
			3 => $homepage_html_3 
		);
		$download_title                           = '';
		$download_header                          = '<table id="subscription-list-table" class="datagrid2" width="30%">
                <thead>
                    <tr>
                        <th width="10%">System</th>
                        <th width="10%">Speed</th>
                        <th width="10%">Download Time</th>
                    </tr>
                </thead><tbody>
		';
		$download_footer                          = '</tbody><tfoot>
                    <tr>
                        <td colspan="7">
                        </td>
                    </tr>
                </tfoot>
            </table>
		';
		$download_properties['preview_header'][1] = "<br /><br />
			 Preview Page 1 <span class='info-img'>(176 x 220 px)</span><hr /><br /><br />
			 <span class='preview_wap'>";
		$download_properties['preview_header'][2] = "<br /><br />
                Preview Page 2 <span class='info-img'>(240 x 320 px)</span><hr /><br /><br />
                <span class='preview_wap'>";
		$download_properties['preview_header'][3] = "<br /><br />
                Preview Page 3 <span class='info-img'>(480 x 320 px)</span><hr /><br /><br />
                <span class='preview_wap'>";
		$download_properties['preview_footer']    = array(
			 1 => "</span>",
			2 => "</span>",
			3 => "</span>" 
		);
		$download_body                            = array( );
		$download_properties['homepage_size']     = $homepage_size;
		$download_properties['homepage_html']     = $homepage_html;
		$download_properties['title'][1]          = "Landing Page 1 size (as previewed with Sony Ericsson W800i User Agent): ";
		$download_properties['title'][2]          = "Landing Page 2 size (as previewed with Nokia 6120 User Agent): ";
		$download_properties['title'][3]          = "Landing Page 3 size (as previewed with Blackberry Bold 9000 User Agent): ";
		for ( $i = 1; $i < 4; $i++ ) {
			$download_properties['load_time_count'][$i] = count( $load_time[$i] );
			$download_body[$i]                          = '';
			foreach ( $load_time[$i] as $item ) {
				$download_body[$i] .= "<tr>";
				$download_body[$i] .= "<td>" . $item['arch'] . "</td>";
				$download_body[$i] .= "<td>" . $item['speed'] . "</td>";
				$download_body[$i] .= "<td>" . $item['download_speed'] . "</td>";
				$download_body[$i] .= "</tr>";
			}
		}
		$response = array(
			 'status' => 1,
			'result' => $result,
			'result_download' => array(
				 'title' => $download_properties['title'],
				'header' => $download_header,
				'body' => $download_body,
				'footer' => $download_footer,
				'properties' => $download_properties 
			) 
		);
		echo json_encode( $response );
		$this->klogger->log( "debug 11" );
	}
	
	function return_component_empty( )
	{
		$response = array(
			 'status' => 0 
		);
		echo json_encode( $response );
	}
	
	function sort( $wapsite_id = NULL, $old_sort = NULL, $new_sort = NULL )
	{
		$this->klogger->log( "" );
		$this->load->model( 'wapreg_component' );
		$max_sort = $this->wapreg_component->read_component_max_sort( $wapsite_id );
		if ( $old_sort > $max_sort || $new_sort > $max_sort )
			redirect( 'wapreg_layout/index/' . $wapsite_id );
		$this->wapreg_component->update_component_sort_order( $wapsite_id, $old_sort, $new_sort );
		exit;
	}
	
	function get_homepage_element_size( $html_code )
	{
		$this->klogger->log( "" );
		preg_match_all( "/[img|input|embed|script]+.*[\s]*(src|background)[\040]*=[\040]*\"?([^\"' >]+)/ie", $html_code, $arr_source );
		$total_size = 0;
		foreach ( $arr_source[2] as $source_url ) {
			$total_size += $this->get_element_size( $source_url );
		}
		return $total_size;
	}
	
	function get_homepage_size( $wap_name, $type = 1 )
	{
		$this->klogger->log( "" );
		$config    = get_config();
		$url       = $config['wap_home_layout'] . $wap_name . '/';
		$html_size = $this->get_element_size( $url, $type );
		return $html_size;
	}
	
	function get_element_size( $url, $type = 1 )
	{
		$this->klogger->log( "" );
		$usr_agn = '';
		if ( $type == 1 )
			$usr_agn = 'SonyEricssonW800i/R1AA Browser/SEMC-Browser/4.2 Profile/MIDP-2.0 Configuration/CLDC-1.1';
		else if ( $type == 2 )
			$usr_agn = 'Mozilla/5.0 (SymbianOS/9.2; U; Series60/3.1 Nokia6120c/3.83; Profile/MIDP-2.0 Configuration/CLDC-1.1) AppleWebKit/413 (KHTML, like Gecko) Safari/413';
		else
			$usr_agn = 'BlackBerry9000/4.6.0.65 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/102';
		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, TRUE );
		curl_setopt( $curl, CURLOPT_USERAGENT, $usr_agn );
		curl_exec( $curl );
		return curl_getinfo( $curl, CURLINFO_SIZE_DOWNLOAD );
	}
	
	function get_homepage_html( $wap_name, $type = 1 )
	{
		$this->klogger->log( "" );
		$config  = get_config();
		$url     = $config['wap_home_layout'] . $wap_name . '/';
		$usr_agn = '';
		if ( $type == 1 )
			$usr_agn = 'SonyEricssonW800i/R1AA Browser/SEMC-Browser/4.2 Profile/MIDP-2.0 Configuration/CLDC-1.1';
		else if ( $type == 2 )
			$usr_agn = 'Mozilla/5.0 (SymbianOS/9.2; U; Series60/3.1 Nokia6120c/3.83; Profile/MIDP-2.0 Configuration/CLDC-1.1) AppleWebKit/413 (KHTML, like Gecko) Safari/413';
		else
			$usr_agn = 'BlackBerry9000/4.6.0.65 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/102';
		ini_set( 'user_agent', $usr_agn );
		$fp = @file_get_contents( $url );
		if ( $fp === FALSE ) {
			$fcontent = '';
		} else {
			$fcontent = $fp;
		}
		$strip = preg_replace( array(
			 '/<\?xml version="1.0" encoding="UTF-8"\?>/',
			'/<!DOCTYPE html PUBLIC "-\/\/WAPFORUM\/\/DTD XHTML Mobile 1.0\/\/EN" "http:\/\/www.wapforum.org\/DTD\/xhtml-mobile10.dtd">/',
			'@<html[^>]*?>@',
			'@<head[^>]*?>.*?</head>@siu',
			'/<body>/',
			'/<br>/',
			'/<p>/',
			'/<\/p>/',
			'/<br>/',
			'/<br>/',
			'/<p>/',
			'/<\/p>/',
			'/<br>/',
			'/<br>/',
			'/<p>/',
			'/<\/p>/',
			'/<br>/',
			'/<h1>Error<\/h1>/',
			'/<\/body>/',
			'/<\/html>/' 
		), '', $fcontent );
		return $strip;
	}
	
	function upload( $file, $filename )
	{
		$this->klogger->log( "" );
		$read_config             = get_config();
		$config['upload_path']   = './temp/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = $read_config['allowed_file_size'];
		$config['file_name']     = $filename;
		$this->load->library( 'upload', $config );
		if ( $this->upload->do_upload( $file ) == FALSE ) {
			$return = array(
				 'result' => 'failed',
				'error' => $this->upload->display_errors() 
			);
		} else {
			$data        = $this->upload->data();
			$config_root = get_config();
			if ( !is_writable( $config_root['wapreg_image'] . $filename . '.jpg' ) ) {
				@chmod( $config_root['wapreg_image'] . $filename . '.jpg', 0777 );
			}
			if ( $data['file_type'] == 'image/jpeg' ) {
				$copy   = copy( $data['full_path'], $config_root['wapreg_image'] . $filename . '.jpg' );
				$return = ( $copy == FALSE ) ? array(
					 'result' => 'failed',
					'error' => 'failed to copy jpg file' 
				) : TRUE;
			} else {
				$image = ( $data['file_type'] == 'image/gif' ) ? imagecreatefromgif( $data['full_path'] ) : imagecreatefrompng( $data['full_path'] );
				$copy  = imagejpeg( $image, $config_root['wapreg_image'] . $filename . '.jpg' );
				@chmod( $config_root['wapreg_image'] . $filename . '.jpg', 0777 );
				$return = ( $copy == FALSE ) ? array(
					 'result' => 'failed',
					'error' => 'failed to convert jpg file' 
				) : TRUE;
			}
			unlink( $data['full_path'] );
		}
		return $return;
	}
}
?>