<?php

/**
 * Created by PhpStorm.
 * User: marcus
 * Date: 12.11.15
 * Time: 16:08
 */
class MY_Router extends CI_Router
{
    private $project_folder;

    public function __construct($routing = NULL) {
        parent::__construct($routing);

        $this->project_folder = PROJECTPATH . 'controllers';

        // Zuerst prüfen, ob der Controller im Projekt vorhanden ist
        $this->routingWithDir($this->projectDirectory(), $routing);
        $class = ucfirst($this->class);

        if(!file_exists(APPPATH.'controllers/'.$this->directory.$class.'.php')) {
            $this->routingWithDir('', $routing);
        }

        log_message('info', 'Router Subclass Initialized');
    }

    protected function routingWithDir($dir, $routing) {
        $this->set_directory_safe($dir);

        // If a directory override is configured, it has to be set before any dynamic routing logic
        is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory'], true);

        $this->_set_routing();

        // Set any routing overrides that may exist in the main index file
        if (is_array($routing))
        {
            empty($routing['controller']) OR $this->set_class($routing['controller']);
            empty($routing['function'])   OR $this->set_method($routing['function']);
        }
    }

    protected function projectDirectory() {
        $base_folder = APPPATH . 'controllers';

        $project_runner = $this->project_folder;
        $base_runner = $base_folder;
        $dir_path = '';
        $stepper = '';
        while($project_runner != $base_runner) {
            $dir_path = implode('/', array_slice(explode('/', $project_runner), -1)) . '/' . $dir_path;
            $stepper .= '../';
            $project_runner = implode('/', array_slice(explode('/', $project_runner), 0, -1));
            $base_runner    = implode('/', array_slice(explode('/', $base_runner), 0, -1));
        }
        $dir_path = $stepper . $dir_path;

        return $dir_path;
    }

    /**
     * Set directory name without removing '.'
     *
     * @param	string	$dir	Directory name
     * @param	bool	$append	Whether we're appending rather than setting the full value
     * @return	void
     */
    public function set_directory_safe($dir, $append = FALSE)
    {
        if ($append !== TRUE OR empty($this->directory))
        {
            $this->directory = trim($dir, '/').'/';
        }
        else
        {
            $this->directory .= trim($dir, '/').'/';
        }
    }
}