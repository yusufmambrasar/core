<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
require(BASE.'/Core/Request.php');
require(BASE.'/Core/Response.php');
require(BASE.'/Core/Controller.php');
require(BASE.'/Core/Model.php');
require(BASE.'/Core/Library.php');
require(BASE.'/Core/View.php');
require(BASE.'/Core/Function.php');

class Core 
{
    public Request $Request;
    public Response $Response;
    public object $Instance;
    public function __construct()
    {
        $this->Request = new Request();
        $this->Response = new Response();
        if(!empty($this->Request->Query))
        {
            if(strpos($this->Request->Query,'&'))
            {
                $queries = explode('&',$this->Request->Query);
                $this->Request->Query = $queries[0];
            }
            if(strpos($this->Request->Query,'/'))
            {
                $queries = explode('/',$this->Request->Query);
                $location = 0;
                $file = '';
                if(isset($queries[1]))
                {
                    $file = BASE.'/App/Modules/'.ucfirst($queries[0]).
                            '/Controllers/'.ucfirst($queries[1]).'.php';
                    if(file_exists($file))
                    {
                        $location = 1;
                        $this->Response->Code = 200;
                        $this->Response->Module = ucfirst($queries[0]);
                        $this->Response->Class = ucfirst($queries[1]);
                        $this->Response->File = $file;
                    }
                    else
                    {   
                        $this->Response->Code = 404;
                    }
                }
                if($location==1)
                {
                    if(isset($queries[2]))
                    {
                        $this->Response->Function = strtolower($queries[2]);
                    }
                    if(count($queries)>3)
                    {
                        for($i=3;$i<=(count($queries)-1);$i++)
                        {
                            $this->Response->Params[] = $queries[$i];
                        }
                    }

                }
                if(isset($queries[0]))
                {
                    if($this->Response->Code==404)
                    {
                        $file = BASE.'/App/Modules/'.ucfirst($queries[0]).
                                '/Controllers/'.ucfirst($queries[0]).'.php';
                        if(file_exists($file))
                        {
                            $location = 2;
                            $this->Response->Code = 200;
                            $this->Response->Module = ucfirst($queries[0]);
                            $this->Response->Class = ucfirst($queries[0]);
                            $this->Response->Function = 'index';
                            $this->Response->File = $file;
                        }
                        else
                        {   
                            $file = BASE.'/App/Modules/'.ucfirst($queries[0]).
                            '/Controllers/Home.php';  
                            if(file_exists($file))
                            {
                                $location = 2;
                                $this->Response->Code = 200;
                                $this->Response->Module = ucfirst($queries[0]);
                                $this->Response->Class = 'Home';
                                $this->Response->Function = 'index';
                                $this->Response->File = $file;
                            }          
                            else
                            {          
                                $this->Response->Code = 404;
                            }
                        }
                    }
                }
                if($location==2)
                {
                    if(isset($queries[1]))
                    {
                        $this->Response->Function = strtolower($queries[1]);
                        if(count($queries)>2)
                        {
                            for($i=2;$i<=(count($queries)-1);$i++)
                            {
                                if(strpos($queries[$i],'#'))
                                {
                                    $qs = explode('#',$queries[$i]);
                                    $this->Response->Params[] = $qs[0];
                                }
                                else
                                {
                                    $this->Response->Params[] = $queries[$i];
                                }
                                if(strpos($queries[$i],'?'))
                                {
                                    $qs = explode('?',$queries[$i]);
                                    $this->Response->Params[] = $qs[0];
                                }
                                else
                                {
                                    $this->Response->Params[] = $queries[$i];
                                }
                            }
                        }
                    }
                    else
                    {
                        $this->Response->Function = 'index';
                    }
                }
            }
            else
            {
                $file = BASE.'/App/Modules/'.ucfirst($this->Request->Query).
                '/Controllers/'.ucfirst($this->Request->Query).'.php';
                if(file_exists($file))
                {
                    $this->Response->Code = 200;
                    $this->Response->File = $file;
                    $this->Response->Module = ucfirst($this->Request->Query);
                    $this->Response->Class = ucfirst($this->Request->Query);
                }
                else
                {
                    $this->Response->Code = 404;
                }
            }
        }
        else
        {
            $file = BASE.'/App/Modules/'.ucfirst($this->Response->Module).
                    '/Controllers/'.ucfirst($this->Response->Class).'.php';
            if(file_exists($file))
            {
                $this->Response->File = $file;
            }
            else
            {
                $this->Response->Code = 404;
            }
        }
        if($this->Response->Code == 200)
        {
            if(empty(trim($this->Response->Function)))
            {
                $this->Response->Function = 'index';
            }
            require_once($this->Response->File);
            if(class_exists($this->Response->Class))
            {
                if(method_exists(
                    $this->Response->Class,
                    $this->Response->Function
                ))
                {
                    $class = $this->Response->Class;
                    $function = $this->Response->Function;
                    $params = $this->Response->Params;
                    $this->Instance = new $class();
                    $this->Instance->$function(...$params);               
                }
                else
                {
                    $this->Response->Code = 404;
                }
            }
            else 
            {
                $this->Response->Code = 404;
            }
        }
        if($this->Response->Code==404)
        {
            header("HTTP/1.0 404 Not Found");
            die();
        }
    }

}
