<?php

/**
 * Created by PhpStorm.
 * User: marcus
 * Date: 13.11.15
 * Time: 13:16
 */
class MY_Loader extends CI_Loader {
    public function __construct() {
        parent::__construct();

        $this->add_package_path(PROJECTPATH);
    }
}