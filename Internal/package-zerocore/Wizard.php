<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Wizard
{
    /**
     * CRLF
     * 
     * @const string
     */
    const CRLF = '\s*(\n|' . PHP_EOL . '|\:|$)';

    /**
     * Get config
     * 
     * @var array
     */
    protected static $config;

    /**
     * PHP tag isolation
     * 
     * @param string $data
     * 
     * @return void
     */
	public static function isolation(String $data = '')
	{
		Filesystem::replaceData($data, ['<?php', '<?', '?>'], ['{[', '{[', ']}']);
	}

    /**
     * Get data.
     * 
     * @param string $string
     * @param array  $data   = []
     * @param array  $config = []
     * 
     * @return string
     */
    public static function data(String $string, Array $data = [], Array $config = []) : String
    {
        self::$config = $config ?: Config::get('ViewObjects', 'wizard');

        self::textControl($string); # 5.4.6[added]

        $pattern = array_merge
        (
            self::symbolsHeader(),
            self::tags(),
            self::keywords(),
            self::printable(),
            self::functions(),
            self::symbolsFooter(),
            self::comments(),
            self::required(),
            self::jsdata(),
            self::html()
        );

        return Buffering::code(self::replace($pattern, $string), $data);
    }

    /**
     * protected text control.
     * 
     * @param string &$string
     * 
     * @return void
     */
    protected static function textControl(&$string)
    {
        if( self::$config['html'] ?? true )
        {
            preg_match_all('/\<(style|script)(.*?)*\>(.*?)\<\/(style|script)\>/si', $string, $standart);
            preg_match_all('/\#(style|script)(.*?)*\s(.*?)\s\##(style|script)/si', $string, $wizard);

            $patterns = array_merge((array) $standart[3], (array) $wizard[3]);
            
            if( ! empty($patterns) ) 
            {
                $changes = [];
    
                foreach( $patterns as $pattern )
                {
                    $changes[] = str_replace(['/#', '#'], ['#', '/#'], $pattern);
                }
    
                $string = str_replace($patterns, $changes, $string);
            }
        } 
    }

    /**
     * protected replace.
     * 
     * @param array  $pattern
     * @param string $string
     * 
     * @return string
     */
    protected static function replace($pattern, $string)
    {
        return preg_replace(array_keys($pattern), array_values($pattern), $string);
    }

    /**
     * protected required
     * 
     * @param void
     * 
     * @return array
     */
    protected static function required()
    {
        return
        [
            '/\{\{\{\s*(.*?)\s*\}\}\}/s' => '<?php echo htmlentities($1) ?>',
            '/\{\{(\s*.*?)\s*\}\}/s'     => '<?php echo $1 ?>',
        ];
    }

    /**
     * protected keywords
     * 
     * @param void
     * 
     * @return array
     */
    protected static function keywords()
    {
        $array = [];

        if( self::$config['keywords'] ?? true )
        {
            $array =
            [
                '/@(view|script|style|template|theme|plugin)\s*\((.*?)\)'.self::CRLF.'/sm' => '<?php Import::$1($2) ?>',
                '/@endform'.self::CRLF.'/sm'                                               => '<?php echo Form::close() ?>',
                '/@form\s*\((.*?)\)'.self::CRLF.'/sm'                                      => '<?php echo Form::open($1) ?>',
                '/@(hidden|textarea|text|checkbox|radio|file|email|submit|button|multiselect|select|password)\s*\((.*?)\)'.self::CRLF.'/sm' => '<?php echo Form::$1($2) ?>',
                '/@(anchor|mailto|image)\s*\((.*?)\)'.self::CRLF.'/sm'                     => '<?php echo Html::$1($2) ?>',
                '/@endperm'.self::CRLF.'/sm'                                               => '<?php Permission::end() ?>',
                '/@perm\s*\((.*?)\)'.self::CRLF.'/sm'                                      => '<?php Permission::start($1) ?>',  
                '/@login\s*\((.*?)\)'.self::CRLF.'/sm'                                     => '<?php if( User::isLogin() ): ?>', 
                '/@view'.self::CRLF.'/sm'                                                  => '<?php echo $view ?>',  
                '/@(endforelse|endlogin)'.self::CRLF.'*/m'                                 => '<?php endif; ?>',                                       
                '/@forelse\s*\((\s*(.*?)\s+as\s+(.*?))\)'.self::CRLF.'/sm'                 => '<?php if( ! empty($2) ): foreach($1): ?>',
                '/@empty'.self::CRLF.'/m'                                                  => '<?php endforeach; else: ?>',     
                '/@loop\s*\((.*?)\)'.self::CRLF.'/sm'                                      => '<?php foreach($1 as $key => $value): ?>',    
                '/@endloop'.self::CRLF.'/m'                                                => '<?php endforeach; ?>',         
                '/@(endif|endforeach|endfor|endwhile|break|continue)'.self::CRLF.'*/m'     => '<?php $1 ?>',
                '/@(elseif|if|foreach|for|while)\s*(.*?)'.self::CRLF.'/sm'                 => '<?php $1$2: ?>',
                '/@(else|not)'.self::CRLF.'*/m'                                            => '<?php else: ?>',
            ];
        }

        return $array;
    }

    /**
     * protected printable
     * 
     * @param void
     * 
     * @return array
     */
    protected static function printable()
    {
        $array = [];

        if( self::$config['printable'] ?? true )
        {
            $suffix   = self::CRLF.'/sm';
            $coalesce = '\?';
            $constant = '((\w+)(\[(\'|\")*.*?(\'|\")*\])*)';
            $variable = '/@\$(\w+.*?)';
            $start    = '((\W)@|^@)';
            
            $outputVariableCoalesce = '<?php echo $$1 ?? NULL ?>';
            $outputVariable         = '<?php echo $$1 ?>';

            $outputCosntantCoalesce = '$2<?php echo defined("$4") ? ($3 ?? NULL) : NULL ?>';
            $outputCosntant         = '$2<?php echo $3 ?>';
            
            $array    =
            [
                $variable                . $coalesce . $suffix  => $outputVariableCoalesce, # Variable
                $variable                            . $suffix  => $outputVariable,         # Variable
                '/' . $start . $constant . $coalesce . $suffix  => $outputCosntantCoalesce, # Constant
                '/' . $start . $constant . $suffix              => $outputCosntant          # Constant
            ];
        }

        return $array;
    }

    /**
     * protected functions
     * 
     * @param void
     * 
     * @return array
     */
    protected static function functions()
    {
        $array = [];

        if( self::$config['functions'] ?? true )
        {
            $function = '(\w+.*?(\)|\}|\]|\-\>\w+))'.self::CRLF.'/sm';
            $array    =
            [
                '/((\W)@|^@)' . $function => '$2<?php if( is_scalar($3) ) echo $3; ?>'  # Function
            ];
        }

        return $array;
    }

    /**
     * protected comments
     * 
     * @param void
     * 
     * @return array
     */
    protected static function comments()
    {
        $array = [];

        if( self::$config['comments'] ?? true )
        {
            $array =
            [

                '/\{\-\-\s*(.*?)\s*\-\-\}/s' => '<!--$1-->'
            ];
        }

        return $array;
    }

    /**
     * protected javascript data
     * 
     * @param void
     * 
     * @return array
     */
    protected static function jsdata()
    {
        $array = [];

        if( self::$config['jsdata'] ?? true )
        {
            $array =
            [
                '/\[\{\s*(.*?)\s*\}\]/s' => '{{$1}}'
            ];
        }

        return $array;
    }

    /**
     * protected tags
     * 
     * @param void
     * 
     * @return array
     */
    protected static function tags()
    {
        $array = [];

        if( self::$config['tags'] ?? true )
        {
            $array =
            [
				# 5.3.4[added]
				'/\{\[\=(.*?)\]\}/'      => '<?php echo $1 ?>',
                '/\{\[\s*(.*?)\s*\]\}/s' => '<?php $1 ?>',

                # 5.5.80
                '/@php\:*/'              => '<?php',
                '/@endphp\:*/'           => '?>'
            ];
        }

        return $array;
    }

    /**
     * protected html
     * 
     * @param void
     * 
     * @return array
     */
    protected static function html()
    {
        $array             = [];
        $htmlAttributesTag = '(^|\s)\#(!*\w+)\s*(\[(.*?)\])*';

        if( self::$config['html'] ?? true )
        {
            $array =
            [
                '/\/#/'                                         => '+[symbol??dies]+',
                '/\s+\#\#(\w+)/'                                => '</$1>',
                '/'.$htmlAttributesTag.self::CRLF.'/m'          => '<$2 $4>',
                '/'.$htmlAttributesTag.'\s+/'                   => '<$2 $4>',
                '/'.$htmlAttributesTag.'\s*\(\s*(.*?)\s*\)'.self::CRLF.'/sm' => '<$2 $4>$5</$2>',
                '/'.$htmlAttributesTag.'\s*/'                   => '<$2 $4>',
                '/\<(\w+)\s+\>/'                                => '<$1>',
                '/\+\[symbol\?\?dies\]\+/'                      => '#'
            ];
        }

        return $array;
    }

    /**
     * protected symbols header
     * 
     * @param void
     * 
     * @return array
     */
    protected static function symbolsHeader()
    {
        return
        [
            '/\/@/' => '+[symbol??at]+',
            '/::/'  => '+[symbol??static]+',
            '/\/:/' => '+[symbol??colon]+',
        ];
    }

    /**
     * protected symbols footer
     * 
     * @param void
     * 
     * @return array
     */
    protected static function symbolsFooter()
    {
        return
        [
            '/\+\[symbol\?\?at\]\+/'     => '@',
            '/\+\[symbol\?\?static\]\+/' => '::',
            '/\+\[symbol\?\?colon\]\+/'  => ':',
        ];
    }
}
