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

        $code = self::convertWizardContent($string);

        return Buffering::code($code, $data);
    }

    /**
     * Protected convert wizard content
     */
    protected static function convertWizardContent($string)
    {
        self::textControl($string); # 5.4.6[added]
        
        $pattern = array_merge
        (
            self::callableJS(),
            self::tags(),
            self::jsdata(),
            self::changeVariables(),
            self::symbolsHeader(),
            self::keywords(),
            self::printable(),
            self::functions(),
            self::symbolsFooter(),
            self::comments(),       
            self::html()
        );

        return self::replace($pattern, $string);
    }

    /**
     * protected functions
     * 
     * @param void
     * 
     * @return array
     */
    protected static function callableJS()
    {
        if( self::$config['callableJS'] ?? true )
        {
            $array    =
            [
                '/(function\((.*?)\)\s*)*(use\(.*?\)\s*)*\{\<\s/s' => 'function($2)$3{ ?>',  # Function
                '/\s\>\}/s'                                        => '<?php }'
            ];

            return $array;
        }
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
                '/\{\{\{\s*(.*?)\s*\}\}\}/s' => '<?php echo htmlentities($1) ?>',
                '/\{\{\s*/'                  => '<?php echo ',
                '/\s*\}\}/'                  => ' ?>',

				'/\{\[\=(.*?)\]\}/'          => '<?php echo $1 ?>',
                '/(\s*\]\}|@endphp\:*)/'     => ' ?>',
                '/(\{\[\s*|@php\:*)/'        => '<?php ',
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
                '/\[\{\s*/' => '{{',
                '/\s*\}\]/' => '}}'
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
                '/((\W)@|^@)' . $function => '$2<?php echo $3; ?>'  # Function
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
                '/\{\-\-\s*/' => '<!-- ',
                '/\s*\-\-\}/' => ' -->'
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
        $symbolHeader = [];

        if(self::$config['html'] === false )
        {
            $symbolHeader['/\/#/'] = '+[symbol??dies]+';
        }
        
        return $symbolHeader + 
        [
            '/\/@/' => '+[symbol??at]+',
            '/::/'  => '+[symbol??static]+',
            '/\/:/' => '+[symbol??colon]+'
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
        $symbolFooter = [];

        if(self::$config['html'] === false )
        {
            $symbolFooter['/\+\[symbol\?\?dies\]\+/'] = '#';
        }

        return $symbolFooter +
        [
            '/\+\[symbol\?\?at\]\+/'     => '@',
            '/\+\[symbol\?\?static\]\+/' => '::',
            '/\+\[symbol\?\?colon\]\+/'  => ':'
        ]; 
    }

    /**
     * Protected change variables
     */
    protected static function changeVariables()
    {
        return 
        [
            '/\$this\-\>/' => '$getZNClassInstance->'
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
                '/@(cview|view|script|style|template|theme|plugin)\s*\((.*?)\)'.self::CRLF.'/sm' => '<?php Import::$1($2) ?>',
                '/@endform'.self::CRLF.'/sm'                                                     => '<?php echo Form::close() ?>',
                '/@form\s*\((.*?)\)'.self::CRLF.'/sm'                                            => '<?php echo Form::open($1) ?>',
                '/@(hidden|textarea|text|checkbox|radio|file|email|submit|button|multiselect|select|password)\s*\((.*?)\)'.self::CRLF.'/sm' => '<?php echo Form::$1($2) ?>',
                '/@(anchor|mailto|image)\s*\((.*?)\)'.self::CRLF.'/sm'                           => '<?php echo Html::$1($2) ?>',
                '/@container'.self::CRLF.'/sm'                                                   => '<?php echo Html::startContainerDiv() ?>',
                '/@containerFluid'.self::CRLF.'/sm'                                              => '<?php echo Html::startFluidContainerDiv() ?>',
                '/@row'.self::CRLF.'/sm'                                                         => '<?php echo Html::startRowDiv() ?>',
                '/@col(([a-zA-Z]{2})([0-9]{1,2}))'.self::CRLF.'/sm'                              => '<?php echo Html::startColumnDiv("$2-$3") ?>',
                '/@end(col|row|container)'.self::CRLF.'/sm'                                      => '<?php echo Html::endDiv() ?>',
                '/@endperm'.self::CRLF.'/sm'                                                     => '<?php Permission::end() ?>',
                '/@perm\s*\((.*?)\)'.self::CRLF.'/sm'                                            => '<?php Permission::start($1) ?>',  
                '/@login\s*\((.*?)\)'.self::CRLF.'/sm'                                           => '<?php if( User::isLogin() ): ?>', 
                '/@view'.self::CRLF.'/sm'                                                        => '<?php echo $view ?>',  
                '/@(endforelse|endlogin)'.self::CRLF.'*/m'                                       => '<?php endif; ?>',                                       
                '/@forelse\s*\((\s*(.*?)\s+as\s+(.*?))\)'.self::CRLF.'/sm'                       => '<?php if( ! empty($2) ): foreach($1): ?>',
                '/@empty'.self::CRLF.'/m'                                                        => '<?php endforeach; else: ?>',     
                '/@loop\s*\((.*?)\)'.self::CRLF.'/sm'                                            => '<?php foreach($1 as $key => $value): ?>',    
                '/@endloop'.self::CRLF.'/m'                                                      => '<?php endforeach; ?>',         
                '/@(endif|endforeach|endfor|endwhile|break|continue)'.self::CRLF.'*/m'           => '<?php $1 ?>',
                '/@(elseif|if|foreach|for|while)\s*(.*?)'.self::CRLF.'/sm'                       => '<?php $1$2: ?>',
                '/@(else|not)'.self::CRLF.'*/m'                                                  => '<?php else: ?>',
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
     * protected text control.
     * 
     * [changed]5.7.5.1
     * [fixed  ]5.7.5.6|5.7.5.7
     * 
     * @param string &$string
     * 
     * @return void
     */
    protected static function textControl(&$string)
    {   
        self::internalTextControl('style' , ['#' => '/#', '@' => '/@', ':' => '/:'], $string);
        self::internalTextControl('script', ['#' => '/#'], $string);
    }
    
    /**
     * Protected internal text control
     * 
     * [added]5.7.5.7
     */
    protected static function internalTextControl($type, $controls, &$string)
    {
        # If the use of custom html is obvious or if the page uses an internal style or code
        preg_match_all('/(\<|\#)'.$type.'(?<data>.*?)(\<\/|\#\#)'.$type.'/si', $string, $standart);

        $patterns = $standart['data'];

        if( ! empty($patterns) ) 
        {
            $changes = [];

            $keys   = array_keys($controls);
            $values = array_values($controls);

            foreach( $patterns as $pattern )
            {
                $changes[] = str_replace($keys, $values, $pattern);
            }

            $string = str_replace($patterns, $changes, $string);
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
}
