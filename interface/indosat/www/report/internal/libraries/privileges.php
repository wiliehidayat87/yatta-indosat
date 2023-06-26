<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Privileges
{
    public $menu;

    public function __construct() {
        $this->menu = array(
            'dashboard' => array(
                'text' => 'Dashboard',
                'url'  => DOMAIN . 'account',
                'title' => 'Dashboard',
                'status' => 'active'
            ),
            /*'reporting' => array(
                'text' => 'Reporting',
                'url'  => DOMAIN . 'operator',
                'title' => 'Reporting',
                'status' => 'active',
                'child' => array(
                    'operator' => array(
                        'text' => 'Operator',
                        'url'  => DOMAIN . 'operator',
                        'title' => 'Operator',
                        'status' => 'active',
                    ),
                    'close_reason' => array(
                        'text' => 'Close Reason',
                        'url'  => DOMAIN . 'close_reason',
                        'title' => 'Close Reason',
                        'status' => 'active',
                    ),
                    'traffic' => array(
                        'text' => 'Traffic',
                        'url'  => DOMAIN . 'traffic',
                        'title' => 'Traffic',
                        'status' => 'active',
                    ),
                    'service' => array(
                        'text' => 'Service',
                        'url'  => DOMAIN . 'service',
                        'title' => 'Service',
                        'status' => 'active',
                    ),
                    'subject' => array(
                        'text' => 'Subject',
                        'url'  => DOMAIN . 'subject',
                        'title' => 'Subject',
                        'status' => 'active',
                    ),
                    'subscriber' => array(
                        'text' => 'Subscriber',
                        'url'  => DOMAIN . 'subscriber',
                        'title' => 'Subscriber',
                        'status' => 'active',
                    ),
                    'user' => array(
                        'text' => 'User',
                        'url'  => DOMAIN . 'user',
                        'title' => 'User',
                        'status' => 'active',
                    ),
                    'content_download' => array(
                        'text' => 'Content Download',
                        'url'  => DOMAIN . 'content_download',
                        'title' => 'Content Download',
                        'status' => 'active',
                    )
                )
            ),*/
            'operator' => array(
                'text' => 'Operator',
                'url'  => DOMAIN . 'operator',
                'title' => 'Operator',
                'status' => 'active',
            ),
         /*   'close_reason' => array(
                'text' => 'Close Reason',
                'url'  => DOMAIN . 'close_reason',
                'title' => 'Close Reason',
                'status' => 'active',
            ),
            'traffic' => array(
                'text' => 'Traffic',
                'url'  => DOMAIN . 'traffic',
                'title' => 'Traffic',
                'status' => 'active',
            ),*/
            'service' => array(
                'text' => 'Service',
                'url'  => DOMAIN . 'service',
                'title' => 'Service',
                'status' => 'active',
            ),
//            'subject' => array(
//                'text' => 'Subject',
//                'url'  => DOMAIN . 'subject',
//                'title' => 'Subject',
//                'status' => 'active',
//            ),
 /*           'subscriber' => array(
                'text' => 'Subscriber',
                'url'  => DOMAIN . 'subscriber',
                'title' => 'Subscriber',
                'status' => 'active',
            ),
            'user' => array(
                'text' => 'User',
                'url'  => DOMAIN . 'user',
                'title' => 'User',
                'status' => 'active',
            ),
            'content_download' => array(
                'text' => 'Content Download',
                'url'  => DOMAIN . 'content_download',
                'title' => 'Content Download',
                'status' => 'active',
            ),
            'user_report' => array(
                'text' => 'User Report',
                'url'  => DOMAIN . 'user_report',
                'title' => 'User Report',
                'status' => 'active',
            ),*/
            'signOut' => array(
                'text' => 'Sign Out',
                'url'  => DOMAIN . 'login/logout',
                'title' => 'Sign Out',
                'status' => 'active'
            )
        );
    }

    public function hasPrivilege($section, $privileges) {
        if (true === in_array($section, $privileges)) {
            return true;
        }

        header("Location: " . DOMAIN . 'account/dashboard'); exit;
    }

    public function parseMenu($role, $privileges) {
        if (true !== is_array($privileges)) {
            $privileges = array($privileges);
        }

        $menu = '<ul id="navigation" class="clearfix">';

        if (false !== is_array($privileges)) {
            foreach ($this->menu AS $key => $value) {
                if (false !== in_array($key, $privileges)) {
                    $menu .= '<li>';
                    $menu .= ('active' == $value['status']) ? '<a href="' . $value['url'] . '" title="' . $value['title'] . '">' : '<span class="inactive">';
                    $menu .= $value['text'];
                    $menu .= ('active' == $value['status']) ? '</a>' : '</span>';

                    if (false !== isset($value['child'])) {
                        $subMenu = $value['child'];
                        $menu .= '<ul>';

                        foreach ($subMenu AS $subKey => $subMenuContent) {
                            $menu .= '<li>';
                            $menu .= ('active' == $subMenuContent['status']) ? '<a href="' . $subMenuContent['url'] . '" title="' . $subMenuContent['title'] . '">' : '<span class="inactive">';
                            $menu .= $subMenuContent['text'];
                            $menu .= ('active' == $subMenuContent['status']) ? '</a>' : '</span>';
                            $menu .= '</li>';
                        }

                        $menu .= '</ul>';
                    }

                    $menu .= '</li>';
                }
            }
        }

        $menu .= '</ul>';
        return $menu;
    }
}

