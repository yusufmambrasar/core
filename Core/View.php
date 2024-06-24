<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

class View
{

    private string $_layout;
    private $_data = [];
    
    public function __construct( $layout = '' )
    {
        if(!empty($layout))
        {
            $this->_layout = $layout;
        }
    }

    public function set( $name , $value )
    {
        $this->_data[ $name ] = $value;
		return $this;
    }

    public function unset( $name )
    {
        unset( $this->_data[ $name ] );
		return $this;
    }

    public function assign( $name , $value )
    {
        $this->{ $name } = $value;
		return $this;
    }

    public function unassign( $name  )
    {
        unset( $this->{ $name } );
		return $this;
    }

    public function part ( $name , $view )
    {
        $file = BASE . $view . '.php';
        $this->assign( $name , $this->_load( $view ) ) ;
        return $this;
    }

    public function unpart ( $name )
    {
        $this->unassign( $name );
        return $this;
    }

    public function render( $view )
    {
        $this->part( 'content' , $view );
        if(defined('SANITIZE')){
            ob_start("sanitize_output");
            echo $this->sanitize_output($this->_load( $this->_layout ));
        }else{
            echo $this->_load( $this->_layout );
        }
    }

    private function _load( $view )
    {
        $file = BASE . $view . '.php';
        $buffer = '';
        if( file_exists( $file ) )
        {
            ob_start();
            if(is_array($this->_data))
            {
                foreach($this->_data as $k => $v)
                {
                    $$k = $v;
                }
            }
            include ( $file );
            $buffer = ob_get_contents();
            ob_end_clean();
        }
        return $buffer;
    }

    public function json($data)
    {
        $jsondata = json_encode($data);
        header('Content-Type: application/json');
        echo $jsondata;
    }

    public function excel($name,$data)
    {
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=".$name.".xls");
        echo '<table border="1"';
        echo '<tr>';
        $i=0;
        foreach($data as $k=>$dt)
        {
            $i++;
            if($i==1)
            {
                foreach($dt as $j => $x)
                {
                    echo '<th>'.$j.'</th>';
                }
            }
        }
        echo '</tr>';
        foreach($data as $k=>$dt)
        {
            echo '<tr>';
            foreach($dt as $j => $x)
            {
                echo '<td>'.$x.'</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    function sanitize_output($buffer) 
    {
        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
    
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }

}