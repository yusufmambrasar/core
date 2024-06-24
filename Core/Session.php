<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

class Session
{
    public string $name = 'MYSESSION';
    
    public function __construct( $name='' )
    {
        if(session_status() === PHP_SESSION_NONE) 
        {
            session_start();
        }
        if(!empty($name))
        {
            $this->name = $name;
        }
        return $this;
    }
    
    public function set( $name , $value )
    {
        $_SESSION[$this->name][$name] = $value;
    }

    public function unset( $name )
    {
        unset($_SESSION[$this->name][$name]);
        return $this;
    }

    public function isSet( $name )
    {
        if(isset($_SESSION[$this->name][$name]))
        {
            return TRUE;
        }
        return FALSE;
    }

    public function get( $name )
    {
        if(isset($_SESSION[$this->name][$name]))
        {
            return $_SESSION[$this->name][$name];
        }
        else
        {
            return FALSE;
        }
    }

    public function clear( )
    {
        $_SESSION[ $this->name ] = array();
        return $this;
    }

    public function destroy()
    {
        $_SESSION = array();
        session_destroy();
        return $this;
    }

}
