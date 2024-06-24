<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

class Controller 
{   
    private array $_loaded = [];
    public array $config;

    public function __construct()
    {
        $file = BASE . '/App/Configs/App.php';
        if(file_exists($file))
        {
            include($file);
            if (is_array($config)) {
                $this->config = $config;
            }
        }
    }

    public function Load($path='')
    {
        if(!empty($path))
        {
            $pathinfo = pathinfo($path);
            if(!isset($pathinfo['extension']))
            {
                $path = $path . '.php';
            }
            if(!file_exists($path))
            {
                $path = BASE . '/' . $path;
            }
            if(file_exists($path))
            {
                $pathinfo = pathinfo($path);
                if(!isset($this->_loaded[$pathinfo['filename']]))
                {
                    include($path);
                    if(class_exists($pathinfo['filename']))
                    {
                        $class = $pathinfo['filename'];
                        $this->$class = new $class();
                        $this->_loaded[$class] = TRUE;
                    }
                }
            }
        }
        else
        {
            return NULL;
        }
    }

}