<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

class View 
{

    public static $cache_path = BASE . '/Caches/';
    public static $cache_enabled = false;
    public static $views_path = BASE . '/App/Modules/';
    private static $blocks = array();

    public function __construct($file, $data = array(),$compress=TRUE) {
        $cached_file = self::cache($file);
        extract($data, EXTR_SKIP);
        if($compress)
        {
            ob_start("Minifier");
        }
        include $cached_file;
        if($compress)
        {
            ob_end_flush();
        }
    }

    private static function cache($file) {
        if (!file_exists(self::$cache_path)) {
            mkdir(self::$cache_path, 0744);
        }
        $cached_file = self::$cache_path . str_replace(array('/', '.html'), array('_', ''), $file . '.php');
        if (!self::$cache_enabled || !file_exists($cached_file) || filemtime($cached_file) < filemtime(self::$views_path .$file)) {
            $code = self::includeFiles($file);
            $code = self::compileCode($code);
            file_put_contents($cached_file, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        }
        return $cached_file;
    }

    public static function clearCache() {
        foreach(glob(self::$cache_path . '*') as $file) {
            unlink($file);
        }
    }

    private static function compileCode($code) {
        $code = self::compileBlock($code);
        $code = self::compileVar($code);
        $code = self::compileEscapedEchos($code);
        $code = self::compileEchos($code);
        $code = self::compilePHP($code);
        $code = self::compileReplaceCodeBlack($code);
        return $code;
    }

    private static function includeFiles($file) {
        $filename = self::$views_path . $file . '.php';
        $code = file_get_contents($filename);
        preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeFiles($value[2]), $code);
        }
        $code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
        return $code;
    }

    private static function compilePHP($code) {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
    }

    private static function compileEchos($code) {
        return preg_replace('~\{!\s*(.+?)\s*\!}~is', '<?php echo $1 ?>', $code);
    }

    private static function compileEscapedEchos($code) {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    private static function compileBlock($code) {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], self::$blocks)) self::$blocks[$value[1]] = '';
            if (strpos($value[2], '@parent') === false) {
                self::$blocks[$value[1]] = $value[2];
            } else {
                self::$blocks[$value[1]] = str_replace('@parent', self::$blocks[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    private static function compileReplaceCodeBlack($code) {
        preg_match_all('/{\( (.*?) \)}/is', $code, $matches, PREG_SET_ORDER);
        foreach($matches as $value) {
            $staticFunction = explode( ',', $value[1]);
            $data = call_user_func_array([$staticFunction[0], $staticFunction[1]], array_slice($staticFunction, 2));
            $code = str_replace($value[0], $data, $code);
        }

        return $code;

    }	

    private static function compileVar($code) {
        foreach(self::$blocks as $block => $value) {
            $code = preg_replace('/{% ?var ?' . $block . ' ?%}/', $value, $code);
        }
        $code = preg_replace('/{% ?var ?(.*?) ?%}/i', '', $code);
        return $code;
    }

    public function __destruct()
    {
        $files = glob(self::$cache_path.'*');
        foreach($files as $file){
            if(is_file($file)) {
                unlink($file);
            }
        }
    }

}