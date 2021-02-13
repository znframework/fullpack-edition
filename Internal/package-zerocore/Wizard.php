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
    const CRLF = '(((\s*)(\n|' . PHP_EOL . '))|\:|\s*$)';

    /**
     * END
     * 
     * @const string
     */
    const END = '((\s|\n|' . PHP_EOL . ')|\:|$)';

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

        $code = Buffering::code($code, $data);

        $code = self::phpClean($code);

        return $code;
    }

    /**
     * Protected convert wizard content
     */
    protected static function convertWizardContent($string)
    {
        self::textControl($string, $jsData); # 5.4.6[added]
        
        $string  = self::encodeParenthesis($string);

        $pattern = array_merge
        (
            self::callableJS(),
            self::tags(),
            $jsData,
            self::changeVariables(),
            self::symbolsHeader(),
            self::keywords(),
            self::printable(),
            self::functions(),
            self::symbolsFooter(),
            self::comments(),       
            self::html()
        );

        $string = self::replace($pattern, $string);

        $string = self::decodeParenthesis($string);

        return $string;
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
        $array = [];

        if( self::$config['callableJS'] ?? true )
        {
            $array    =
            [
                '/(function\((.*?)\)\s*)*(use\(.*?\)\s*)*\{\<(\s)/s' => 'function($2)$3{$4?>',  # Function
                '/(\s)\>\}/s'                                        => '$1<?php }'
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
                '/\{\{\{\s*(.*?)\s*\}\}\}/s'        => '<?php echo htmlentities($1) ?>',
                '/\{\{\s*/'                         => '<?php echo ',
                '/\s*\}\}/'                         => ' ?>',

				'/\{\[\=(.*?)\]\}/'                 => '<?php echo $1 ?>',
                '/(\s*\]\}|@endphp'.self::END.')/'  => ' ?>',
                '/(\{\[\s*|@php'.self::END.')/'     => '<?php ',
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
     * Protected change variables
     */
    protected static function changeVariables()
    {
        return 
        [
            '/\$this(\,|\-\>|\))/' => '$getZNClassInstance$1'
        ];
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
                '/@selector\s*\((.*?)\)/'                                                        => '<?php echo ZN\Singleton::class(\'ZN\Hypertext\JQueryBuilder\')->selector($1)',
                '/@ajax\s*\((.*?)\)/'                                                            => '<?php echo Ajax::url($1)',
                '/@(cview|view|script|style|font|template|theme|plugin)\s*\((.*?)\)'.self::CRLF.'/sm' => '<?php Import::$1($2) ?>$4',
                '/@endform'.self::CRLF.'/sm'                                                     => '<?php echo Form::close() ?>$2',
                '/@form\s*\((.*?)\)'.self::CRLF.'/sm'                                            => '<?php echo Form::open($1) ?>$3',
                '/@(hidden|textarea|text|checkbox|radio|file|email|submit|button|multiselect|select|password)\s*\((.*?)\)'.self::CRLF.'/sm' => '<?php echo Form::$1($2) ?>$4',
                '/@(anchor|mailto|image)\s*\((.*?)\)'.self::CRLF.'/sm'                           => '<?php echo Html::$1($2) ?>$4',
                '/@container'.self::END.'/sm'                                                    => '<?php echo Html::startContainerDiv() ?>$2',
                '/@containerFluid'.self::END.'/sm'                                               => '<?php echo Html::startFluidContainerDiv() ?>$2',
                '/@row'.self::END.'/sm'                                                          => '<?php echo Html::startRowDiv() ?>$2',
                '/@col(([a-zA-Z]{2})([0-9]{1,2}))'.self::END.'/sm'                               => '<?php echo Html::startColumnDiv("$2-$3") ?>$5',
                '/@end(col|row|container)'.self::END.'/sm'                                       => '<?php echo Html::endDiv() ?>$3',
                '/@endperm'.self::CRLF.'/sm'                                                     => '<?php Permission::end() ?>$2',
                '/@perm\s*\((.*?)\)'.self::CRLF.'/sm'                                            => '<?php Permission::start($1)?>$3',  
                '/@login\s*\((.*?)\)'.self::CRLF.'/sm'                                           => '<?php if( User::isLogin() ):?>$3',           
                '/@view'.self::CRLF.'/sm'                                                        => '<?php echo $view ?>$2',  
                '/@(endforelse|endlogin|endvalid)'.self::CRLF.'*/m'                              => '<?php endif; ?>$3',      
                '/@invalid\s*\((.*?)\)'.self::CRLF.'/sm'                                         => '<?php elseif( $1 = Validation::error(\'string\') ):?>$3', 
                '/@valid\s*\((.*?)\)'.self::CRLF.'/sm'                                           => '<?php if( Validation::check($1) ):?>$3',                                   
                '/@forelse\s*\((\s*(.*?)\s+as\s+(.*?))\)'.self::CRLF.'/sm'                       => '<?php if( ! empty($2) ):foreach($1):?>$5',
                '/@empty'.self::CRLF.'/m'                                                        => '<?php endforeach; else:?>$2',     
                '/@loop\s*\((.*?)\)'.self::CRLF.'/sm'                                            => '<?php foreach($1 as $key => $value):?>$3',    
                '/@endloop'.self::CRLF.'/m'                                                      => '<?php endforeach; ?>$2',         
                '/@(endif|endforeach|endfor|endwhile|break|continue)'.self::CRLF.'*/m'           => '<?php $1 ?>$3',
                '/@(elseif|if|foreach|for|while)\s*\((.*?)\)'.self::CRLF.'/sm'                   => '<?php $1($2):?>$4',
                '/@(else|not)'.self::CRLF.'*/m'                                                  => '<?php else:?>$3',
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
            
            $outputVariableCoalesce = '<?php echo $$1 ?? NULL ?>$4';
            $outputVariable         = '<?php echo $$1 ?>$4';

            $outputCosntantCoalesce = '$2<?php echo defined("$4") ? ($3 ?? NULL) : NULL ?>$10';
            $outputCosntant         = '$2<?php echo $3 ?>$10';
            
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
            $array    =
            [   
                '/((\W)@|^@)(\w+)/'                                   => '$2<?php echo $3',
                '/\)\:(\"|\'|\<|\s|\n|'.PHP_EOL.'|$)/sm'              => ')?>$1',
                '/\)\s*(?!\s*(\-\>|\)|\{|\?\>))(\n|'.PHP_EOL.'|$)/sm' => ')?>$1$2$3'
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
        $htmlAttributesTag = '(^|\s)(>*)\#(!*\w+)\s*(\[(.*?)\])*';

        if( self::$config['html'] ?? true )
        {
            $array =
            [
                '/\/#/'                                => '+[symbol??dies]+',
                '/\s+\#\#(\w+)/'                       => '</$1>',
                '/'.$htmlAttributesTag.self::CRLF.'/m' => '$2<$3 $5>',
                '/'.$htmlAttributesTag.'\s+/'          => '$2<$3 $5>',
                '/'.$htmlAttributesTag.'\s*/'          => '$2<$3 $5>',
                '/\<(\w+)\s+\>/'                       => '<$1>',
                '/\+\[symbol\?\?dies\]\+/'             => '#'
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
    protected static function textControl(&$string, &$jsData)
    {   
        self::internalTextControl('style' , ['#' => '/#', '@' => '/@', ':' => '/:'], $string);

        $convertScriptChars['#'] = '/#';

        if( $jsData = self::jsdata() )
        {
            $convertScriptChars['[{'] = '[ {';
            $convertScriptChars['}]'] = '} ]';
        }

        self::internalTextControl('script', $convertScriptChars, $string);
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

    /**
     * Protected php clean
     */
    protected static function phpClean($string)
    {
        return str_replace('?>', NULL, $string);
    }

    /**
     * Protected encode parenthesis
     */
    protected static function encodeParenthesis($string)
    {
        return preg_replace_callback('/(?<open>\{\-\-|@php'.self::END.'|\{\{|\{\[|\[\{)(?<content>.*?)(?<close>\}\}|\]\}|@endphp'.self::END.'|\}\]|\-\-\})/s', function($data)
        {
            return $data['open'] . preg_replace('/\)\s*(\n|'.PHP_EOL.'|$)/m', '+[symbol??parenthesis]+', $data['content']) . $data['close'];
        }, $string);
    }

    /**
     * Protected decode parenthesis
     */
    protected static function decodeParenthesis($string)
    {
        return preg_replace('/\+\[symbol\?\?parenthesis\]\+/', ')' . PHP_EOL, $string);
    }
}
