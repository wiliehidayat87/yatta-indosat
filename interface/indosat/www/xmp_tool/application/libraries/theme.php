<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class theme {

    protected $theme;
    protected $theme_path;
    protected $path;

    public function __construct($theme = null) {
        if (empty($theme)) {
            $config = & get_config();
            $this->theme = strtolower($config['default_theme']);
        } else {
            $this->theme = $theme;
        }

        $this->setPath();
    }

    protected function setPath() {
        $config = & get_config();
        $this->path = $config['base_url'] . '/themes';
        $this->theme_path = $this->path . '/' . $this->theme;
    }

    public function setTheme($theme) {
        $this->theme = $theme;

        $this->setPath();
    }

    public function getTheme($theme) {
        return $this->theme;
    }

    public function getImage($name) {
        return $this->getImagePath() . $name;
    }

    public function getImagePath() {
        return $this->theme_path . "/img";
    }

    public function getThemePath() {
        return $this->theme_path;
    }

    public function load_css($theme = null) {
        if (!empty($theme)) {
            $this->theme = $theme;
            $this->setPath();
        }

        return '<link rel="stylesheet" href="' . $this->theme_path . '/css/style.css" type="text/css" />' . "\n";
    }

}