<?php namespace ZN\ErrorHandling;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;
use ZN\Lang;
use ZN\Config;
use ZN\Helper;
use ZN\Datatype;
use ZN\Inclusion;

class Exceptions extends \Exception implements ExceptionsInterface
{   
    /**
     * Error codes
     * 
     * @var array
     */
    public static $errorCodes = 
    [
        1       => 'ERROR',
        2       => 'WARNING',
        4       => 'PARSE',
        8       => 'NOTICE',
        16      => 'CORE_ERROR',
        32      => 'CORE_WARNING',
        64      => 'COMPILE_ERROR',
        128     => 'COMPILE_WARNING',
        256     => 'USER_ERROR',
        512     => 'USER_WARNING',
        1024    => 'USER_NOTICE',
        2048    => 'STRICT',
        4096    => 'RECOVERABLE_ERROR',
        8192    => 'DEPRECATED',
        16384   => 'USER_DEPRECATED',
        32767   => 'ALL'
    ];

    /**
     * Magic to string 
     * 
     * @param void
     * 
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return $this->importExceptionTemplate($this->getMessage(), $this->getFile(), $this->getLine(), $this->getTrace());
    }

    /**
     * Throw exception
     * 
     * @param string $message = NULL
     * @param string $key     = NULL
     * @param mixed  $send    = NULL
     * 
     * @return void
     */
    public static function throws(?string $message = NULL, ?string $key = NULL, $send = NULL)
    {
        $debug = self::throwFinder(debug_backtrace(2), 0, 2);

        if( $lang = Lang::default('ZN\CoreDefaultLanguage')::select($message, $key, $send) )
        {
            $message = '['.self::cleanInternalPrefixFromClassName($debug['class']).'::'.$debug['function'].'()] '.$lang;
        }

        self::table('self', $message, $debug['file'], $debug['line']);
    }

    /**
     * Get exception table
     * 
     * @param mixed  $no    = NULL
     * @param string $msg   = NULL
     * @param string $file  = NULL
     * @param string $line  = NULL
     * @param array  $trace = NULL
     * 
     * @return void
     */
    public static function table($no = NULL, ?string $msg = NULL, ?string $file = NULL, ?string $line = NULL, array $trace = NULL)
    {
        if( is_object($no) )
        {
            $msg   = $no->getMessage();
            $file  = $no->getFile();
            $line  = $no->getLine();
            $trace = $no->getTrace(); 
            
            $no    = 'NULL';
        }

        if( ! is_array($trace) )
        {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        }

        $source = self::getProjectSourceLocation($file, $line, $trace);
        $file   = $source->file;
        $line   = $source->line;

        $lang    = Lang::default('ZN\ErrorHandling\ErrorHandlingDefaultLanguage')::select('Templates');
        $message = $lang['line'].':'.$line.', '.$lang['file'].':'.$file.', '.$lang['message'].':'.$msg;

        Helper::report('ExceptionError', $message, 'ExceptionError');

        $table = self::importExceptionTemplate($msg, $file, $line, $no, $trace);

        $projectError = Config::default('ZN\ErrorHandling\ErrorHandlingDefaultConfiguration')::get('Project');

        if
        ( 
            in_array($no, $projectError['exitErrors'] ?? [], true) || 
            in_array(self::$errorCodes[$no] ?? NULL, $projectError['exitErrors'] ?? [], true) 
        )
        {
            defined('ZN_REDIRECT_NOEXIT') || exit($table); // @codeCoverageIgnore
        }

        echo $table;
    }

    /**
     * Prefer the first application call site when an error originates in a
     * framework/package class. Project source errors keep their own location.
     *
     * A Wizard template error is a special case: it surfaces through Wizard's
     * own eval() mechanism (Buffering.php/Callback.php), several frames below
     * whatever project file happened to trigger the render (a controller, a
     * layout helper, ...). The generic "first project file in the trace" rule
     * below would stop at that unrelated project file and never reach the
     * real .wizard.php source, so the wizard-aware resolution is tried first
     * and only falls through to the generic rule when it finds nothing.
     *
     * @param string $file
     * @param int    $line
     * @param array  $trace
     *
     * @return object
     */
    protected static function getProjectSourceLocation($file, $line, $trace)
    {
        if( $wizardSource = self::resolveWizardSourceLocation($file, $line, $trace) )
        {
            return $wizardSource;
        }

        if( self::isProjectSourceFile($file) )
        {
            return (object) ['file' => $file, 'line' => $line];
        }

        foreach( $trace as $frame )
        {
            if
            (
                ! empty($frame['file']) &&
                ! stristr($frame['file'], 'Facade.php') &&
                self::isProjectSourceFile($frame['file'])
            )
            {
                return (object)
                [
                    'file' => $frame['file'],
                    'line' => $frame['line'] ?? $line
                ];
            }
        }

        return (object) ['file' => $file, 'line' => $line];
    }

    /**
     * When an error passes through Wizard's eval() mechanism -- the raw file
     * is Buffering.php/Callback.php, or one of those appears in the trace --
     * resolve the original .wizard.php source instead of letting the generic
     * "first project file in the trace" rule stop early at an unrelated
     * project library that merely triggered the render.
     *
     * The starting line fed into the compiled-line -> source-line remap
     * (further down the pipeline, in getWizardSourceLine()) must be the
     * line WITHIN THE EVAL'D BUFFER where the offending call was made. For
     * a Wizard parse/syntax error that is simply the error's own line (it
     * is thrown while eval()'ing). For a runtime error thrown from inside a
     * Facade-dispatched call (see tracePassesThroughWizardEval()), the
     * error's own line is some unrelated line deep in whatever class
     * implements the call -- the eval-buffer line instead comes from the
     * trace frame findWizardEvalTraceFrame() located.
     *
     * @param string $file
     * @param int    $line
     * @param array  $trace
     *
     * @return object|null
     */
    protected static function resolveWizardSourceLocation($file, $line, $trace)
    {
        $evalFrame = self::findWizardEvalTraceFrame($file, $trace);

        if( ! $evalFrame )
        {
            return NULL;
        }

        $wizardFile = $file;
        $wizardLine = $evalFrame['line'] ?? $line;

        self::searchErrorWizardFile((array) $trace, $wizardFile, $wizardLine);

        self::isWizardOrStandartFileExists($wizardFile, $trace);

        if( ! is_file($wizardFile) || ! stristr($wizardFile, '.wizard.php') )
        {
            return NULL;
        }

        return (object) ['file' => $wizardFile, 'line' => $wizardLine];
    }

    /**
     * Locate the trace frame representing Wizard's eval()'d compiled view
     * code, if the error passes through it at all -- deliberately narrow.
     * Scanning the whole trace for a Buffering.php/Callback.php frame is far
     * too broad on this framework: almost every request is rendered through
     * Wizard, so nearly any unrelated error would have such a frame
     * somewhere in its (often deep) trace and would incorrectly be routed
     * into wizard-file resolution below.
     *
     * Two shapes are recognised, both bounded to a fixed-shape PREFIX of the
     * trace (never a search through the whole thing):
     *
     *  1) The error's own file IS the eval buffer -- a Wizard compile/parse
     *     error, thrown directly while eval()'ing. A synthetic frame is
     *     returned with the error's own file and a NULL line (the caller
     *     already has the correct line in this case: its own).
     *  2) A runtime error (TypeError, ArgumentCountError, ...) thrown from
     *     INSIDE a Facade-dispatched method call, e.g. a stray
     *     `{{ Date::toReadable(null) }}` in a template: the error's own
     *     file is wherever that method happens to be implemented, but the
     *     trace's leading frames are the Facade dispatch mechanism itself
     *     (Facade.php -- typically __callStatic/__call, then
     *     useClassName(), i.e. `Singleton::class(...)->$method(...)`), and
     *     the frame immediately after that fixed-shape prefix is the eval
     *     buffer. Only that immediate next frame is inspected -- if it
     *     is not Buffering.php/Callback.php, NULL is returned rather than
     *     continuing to scan deeper.
     *
     * @param string $file
     * @param array  $trace
     *
     * @return array|null
     */
    protected static function findWizardEvalTraceFrame($file, $trace)
    {
        if( stristr((string) $file, DS . 'Buffering.php') || stristr((string) $file, DS . 'Callback.php') )
        {
            return ['file' => $file, 'line' => NULL];
        }

        foreach( (array) $trace as $frame )
        {
            if( ! isset($frame['file']) )
            {
                continue;
            }

            if( stristr($frame['file'], DS . 'Facade.php') )
            {
                continue;
            }

            if( stristr($frame['file'], DS . 'Buffering.php') || stristr($frame['file'], DS . 'Callback.php') )
            {
                return $frame;
            }

            return NULL;
        }

        return NULL;
    }

    /**
     * @param string $file
     * @param array  $trace
     *
     * @return bool
     */
    protected static function tracePassesThroughWizardEval($file, $trace)
    {
        return self::findWizardEvalTraceFrame($file, $trace) !== NULL;
    }

    /**
     * Check whether a file belongs to an application project.
     *
     * @param string $file
     *
     * @return bool
     */
    protected static function isProjectSourceFile($file)
    {
        $file       = str_replace('\\', '/', (string) $file);
        $projectDir = str_replace('\\', '/', PROJECTS_DIR);

        return strpos($file, $projectDir) !== false;
    }

    /**
     * Continue exception
     * 
     * @param string $msg
     * @param string $file
     * @param string $line
     * 
     * @return string
     */
    public static function continue($msg, $file, $line)
    {
        return self::importExceptionTemplate($msg, $file, $line, NULL, NULL);
    }

    /**
     * Restore exception
     * 
     * @param void
     * 
     * @return bool
     */
    public static function restore() : bool
    {
        return restore_exception_handler();
    }

    /**
     * Set exception handler
     * 
     * @param void
     * 
     * @return void
     */
    public static function handler()
    {
        set_exception_handler([__CLASS__, 'table']);
    }

    /**
     * protected exception template
     * 
     * @param string $msg
     * @param string $file
     * @param string $line
     * @param string $no
     * @param array  $trace
     * 
     * @return string
     */
    private static function importExceptionTemplate($msg, $file, $line, $no, $trace)
    {
        $projects = Config::default('ZN\ErrorHandling\ErrorHandlingDefaultConfiguration')::get('Project');
        $language = Lang::default('ZN\ErrorHandling\ErrorHandlingDefaultLanguage')::select('Templates');

        if( ! $projects['errorReporting'] )
        {
            return false;
        }

        if( in_array($no, $projects['escapeErrors'], true) || in_array(self::$errorCodes[$no] ?? NULL, $projects['escapeErrors'], true) )
        {
            return false; // @codeCoverageIgnore
        }

        $wizardErrorData = self::getTemplateWizardErrorData($file, $line, $trace, $msg);

        $exceptionData =
        [
            'type'    => self::$errorCodes[$no] ?? 'ERROR',
            'msg'     => $msg,
            'file'    => $wizardErrorData->file,
            'line'    => $wizardErrorData->line,
            'trace'   => $trace,
            'request' => self::getRequestDebugData(),
            'runtime' => self::getRuntimeDebugData(),
            'language'=> $language,
            'suggestions' => self::getErrorSuggestions($msg, $language, $wizardErrorData->file)
        ];

        if( ob_get_level() )
        {
            ob_end_clean();
        }

        if( self::isAjaxRequest() )
        {
            # jQuery'nin hata zincirini çalıştırarak çekirdek hata modalını açar.
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');

            $debugHTML = Inclusion\View::use('Table', $exceptionData, true, __DIR__ . '/Resources/');

            return self::debugJSON
            ([
                'error'        => true,
                'errorCode'    => $exceptionData['type'],
                'errorMessage' => $msg,
                'debug'        =>
                [
                    'file'        => $exceptionData['file'],
                    'line'        => $exceptionData['line'],
                    'suggestions' => $exceptionData['suggestions'],
                    'html'        => $debugHTML
                ]
            ]);
        }
        
        return Inclusion\View::use('Table', $exceptionData, true, __DIR__ . '/Resources/');
    }

    /**
     * protected clean class name
     * 
     * @param string $class
     * 
     * @return string
     */
    protected static function cleanInternalPrefixFromClassName($class)
    {
        return str_ireplace(INTERNAL_ACCESS, '', Datatype::divide($class, '\\', -1));
    }

    /**
     * Throw finder
     * 
     * @param array $trace
     * @param int   $p1 = 2
     * @param int   $p2 = 0
     * 
     * @return array
     */
    protected static function throwFinder($trace, $p1 = 3, $p2 = 5)
    {
        $classInfo = $trace[$p1];
        $fileInfo  = $trace[$p2];

        // @codeCoverageIgnoreStart
        if( ! isset($classInfo['class']) && isset($classInfo['function']) )
        {
            $classInfo['class'] = $classInfo['function'];
            $fileInfo['file']   = $classInfo['file'];
            $fileInfo['line']   = $classInfo['line'];
        }
        // @codeCoverageIgnoreEnd

        return
        [
            'class'    => self::cleanInternalPrefixFromClassName($classInfo['class']),
            'function' => $classInfo['function'],
            'file'     => $fileInfo['file'],
            'line'     => $fileInfo['line'],
            'trace'    => $trace
        ];
    }

    /**
     * Handle template wizard
     * 
     * @param void
     * 
     * @return string|null
     * 
     * @codeCoverageIgnore
     */
    protected static function getTemplateWizardErrorData($file, $line, $trace = [], $message = '')
    {
        if
        (
            stristr($file, DS . 'Buffering.php') ||
            stristr($file, DS . 'Callback.php')
        )
        {
            self::searchErrorWizardFile((array) $trace, $file, $line);

            self::isWizardOrStandartFileExists($file, $trace);
        }

        if( is_file($file) && stristr($file, '.wizard.php') )
        {
            $line = self::getWizardSourceLine($file, $line, $message);
        }

        return (object)
        [ 
            'file' => $file,
            'line' => $line
        ];
    }

    /**
     * Resolve the original wizard line for parse errors raised by eval().
     *
     * Wizard conversion can add generated lines before PHP parses the code.
     * For an "unexpected variable" error, the variable in the PHP message is
     * a reliable anchor in the original view file.
     *
     * @param string $file
     * @param int    $line
     * @param string $message
     *
     * @return int
     */
    protected static function getWizardSourceLine($file, $line, $message)
    {
        if( preg_match('/unexpected\s+variable\s+["\']?(\$[a-z_][a-z0-9_]*)/i', $message, $match) )
        {
            $variable = $match[1];
            $content  = file($file);

            foreach( $content as $index => $sourceLine )
            {
                if( strpos($sourceLine, $variable) !== false )
                {
                    return $index + 1;
                }
            }

            return (int) $line;
        }

        if( $remapped = self::remapWizardCompiledLine($file, $line) )
        {
            return $remapped;
        }

        return (int) $line;
    }

    /**
     * General-purpose fallback for any other Wizard eval() error (not just
     * "unexpected variable"): Wizard's own directive compilation can turn one
     * source line into several compiled lines (e.g. {{ }} expands across two
     * lines), which shifts the compiled line number PHP reports away from the
     * real source line. Since we cannot know $data used at render time, we
     * recompile the source (read-only, never eval()'d) with Wizard's own
     * compiler to reconstruct the exact text that was fed to eval().
     *
     * A missing/mismatched quote is a special case worth handling on its own:
     * PHP's tokenizer then swallows everything up to the NEXT matching quote
     * it finds -- possibly many lines later, e.g. an apostrophe inside
     * ordinary text -- into one long string token, and reports the error at
     * that unrelated, much later line (verified against the real compiler:
     * a single missing "'" reported 9 lines away from the real mistake).
     * token_get_all() on the reconstructed buffer finds that anomalous
     * multi-line string token and corrects the target line to where it
     * actually starts, before the anchor search below ever runs.
     *
     * Once the target compiled line is right (corrected or not), its
     * generated "<?php echo ... ?>" wrapper is stripped and what remains is
     * used as a literal anchor to find the matching line back in the
     * original source -- the same anchor-search idea already used above for
     * "unexpected variable", generalized to any message by deriving the
     * anchor from the compiled output itself instead of from the message
     * text.
     *
     * Best-effort throughout: any failure (missing class, unexpected shape,
     * no match) returns NULL and the caller keeps the original, unmapped
     * line number.
     *
     * @param string $file
     * @param int    $line
     *
     * @return int|null
     */
    protected static function remapWizardCompiledLine($file, $line)
    {
        if( ! is_file($file) || ! class_exists('\ZN\Wizard') )
        {
            return NULL;
        }

        try
        {
            $sourceLines = file($file);

            if( empty($sourceLines) )
            {
                return NULL;
            }

            $ref = new \ReflectionClass('\ZN\Wizard');

            if( $ref->hasProperty('config') )
            {
                $configProperty = $ref->getProperty('config');
                $configProperty->setAccessible(true);

                if( $configProperty->getValue() === NULL && class_exists('\ZN\Config') )
                {
                    $configProperty->setValue(NULL, \ZN\Config::get('ViewObjects', 'wizard'));
                }
            }

            $compileMethod = $ref->getMethod('convertWizardContent');
            $compileMethod->setAccessible(true);

            $compiled = $compileMethod->invoke(NULL, implode('', $sourceLines));

            if( ! is_string($compiled) )
            {
                return NULL;
            }

            // Mirror Buffering::code()'s eval() prefix exactly, so the
            // tokenizer's line numbers land on the same lines PHP itself
            // reported for the real error (the leading close-tag adds no line).
            $targetLine = self::findWizardRunawayStringLine('?>' . $compiled, $line) ?? $line;

            $compiledLines = explode("\n", $compiled);
            $compiledLine  = $compiledLines[$targetLine - 1] ?? NULL;

            if( $compiledLine === NULL )
            {
                return NULL;
            }

            $phpTagPosition = strpos($compiledLine, '<?php');

            if( $phpTagPosition === false )
            {
                return NULL;
            }

            $anchor = substr($compiledLine, $phpTagPosition + strlen('<?php'));
            $anchor = preg_replace('/^\s*echo\s*/i', '', $anchor);
            $anchor = preg_replace('/\s*\?\>.*$/s', '', $anchor);
            $anchor = trim($anchor);

            if( $anchor === '' || strlen($anchor) < 4 )
            {
                return NULL;
            }

            foreach( $sourceLines as $index => $sourceLine )
            {
                if( strpos($sourceLine, $anchor) !== false )
                {
                    return $index + 1;
                }
            }
        }
        catch( \Throwable $e )
        {
            return NULL;
        }

        return NULL;
    }

    /**
     * Correct the reported error line when it lands inside a "runaway"
     * multi-line string token.
     *
     * A missing/mismatched quote in Wizard source does not raise a syntax
     * error where the quote is missing: PHP's tokenizer keeps consuming
     * characters as string content until it finds the NEXT matching quote,
     * which can be many lines later. The syntax error is then reported at
     * that far-away line, not at the true bug location.
     *
     * This scans the tokenized eval string for a T_CONSTANT_ENCAPSED_STRING
     * or T_ENCAPSED_AND_WHITESPACE token that spans multiple lines and whose
     * span covers the reported error line; that token's START line is the
     * true bug location.
     *
     * @param string $evalString
     * @param int    $errorLine
     *
     * @return int|null
     */
    protected static function findWizardRunawayStringLine($evalString, $errorLine)
    {
        $tokens = @token_get_all($evalString);

        if( $tokens === false )
        {
            return NULL;
        }

        foreach( $tokens as $token )
        {
            if( ! is_array($token) )
            {
                continue;
            }

            [$id, $text, $tokenLine] = $token;

            if( $id !== T_CONSTANT_ENCAPSED_STRING && $id !== T_ENCAPSED_AND_WHITESPACE )
            {
                continue;
            }

            $span = substr_count($text, "\n");

            if( $span < 1 )
            {
                continue;
            }

            $endLine = $tokenLine + $span;

            if( $tokenLine <= $errorLine && $endLine >= $errorLine )
            {
                return $tokenLine;
            }
        }

        return NULL;
    }

    /**
     * Get safe request data for the error information panel.
     *
     * @return array
     */
    protected static function getRequestDebugData()
    {
        return
        [
            'get'   => self::hideSensitiveDebugData($_GET ?? []),
            'post'  => self::hideSensitiveDebugData($_POST ?? []),
            'files' => self::hideSensitiveDebugData($_FILES ?? [])
        ];
    }

    /**
     * Get non-sensitive runtime information for the error information panel.
     *
     * @return array
     */
    protected static function getRuntimeDebugData()
    {
        return
        [
            'method'       => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'uri'          => $_SERVER['REQUEST_URI'] ?? NULL,
            'phpVersion'   => PHP_VERSION,
            'memoryUsage'  => memory_get_usage(true),
            'memoryPeak'   => memory_get_peak_usage(true)
        ];
    }

    /**
     * Hide credentials recursively before they are written to the browser.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected static function hideSensitiveDebugData($data)
    {
        if( ! is_array($data) )
        {
            return $data;
        }

        foreach( $data as $key => $value )
        {
            if( preg_match('/pass(word)?|token|secret|api.?key|authorization|cookie|identity/i', (string) $key) )
            {
                $data[$key] = '********';
            }
            elseif( is_array($value) )
            {
                $data[$key] = self::hideSensitiveDebugData($value);
            }
        }

        return $data;
    }

    /**
     * Convert debug data to browser-safe JSON.
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function debugJSON($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) ?: '{}';
    }

    /** Check whether the current request was sent with XMLHttpRequest. */
    protected static function isAjaxRequest()
    {
        $requestedWith = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
        $accept         = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');

        return $requestedWith === 'xmlhttprequest' || strpos($accept, 'application/json') !== false;
    }

    /**
     * Return localized, message-aware debugging suggestions.
     *
     * @param string $message
     * @param array  $language
     * @param string $file
     *
     * @return array
     */
    protected static function getErrorSuggestions($message, $language, $file = NULL)
    {
        // A Wizard (.wizard.php) source error benefits from an explicit
        // Wizard directive/block-usage reminder IN ADDITION to the normal
        // message-aware suggestions below -- not instead of them. Returning
        // only the generic Wizard reminder would hide useful, message-
        // specific detail (e.g. exactly which bracket/quote/token is
        // involved) that the rules below already surface. Both are merged
        // together at the end of this method.
        $wizardSuggestions = self::getWizardUsageSuggestions($file, $message, $language);

        $suggestions = self::getTypeMismatchSuggestions($message, $language);
        $detail      = self::getMessageDetailSuggestion($message, $language);

        if( $detail !== NULL )
        {
            array_unshift($suggestions, $detail);
        }

        $rules =
        [
            '/syntax error|parse error|unexpected (token|variable|identifier)|unclosed|does not match|unterminated/i' => ['syntaxSuggestion', 'delimiterSuggestion'],
            '/undefined variable|undefined constant/i'                          => ['variableSuggestion'],
            '/undefined array key|undefined offset|illegal offset|array key/i'  => ['arraySuggestion'],
            '/undefined property|dynamic property|property .* does not exist/i' => ['propertySuggestion', 'nullSuggestion'],
            '/undefined method|call to .* member function/i'                    => ['methodSuggestion', 'nullSuggestion'],
            '/undefined function|call to undefined/i'                           => ['functionSuggestion'],
            '/class .* not found|interface .* not found|trait .* not found/i'   => ['classSuggestion'],
            '/too few arguments|too many arguments|argumentcount|expects? .* argument/i' => ['argumentSuggestion'],
            '/typeerror|must be of type|return value must be|cannot assign .* type/i' => ['typeSuggestion'],
            '/unsupported operand types|unsupported operand type/i'                 => ['operandSuggestion'],
            '/array to string conversion|object of class .* could not be converted to string/i' => ['conversionSuggestion'],
            '/trying to access array offset|illegal string offset|cannot use .* as array|cannot use \[\] for reading|cannot unset string offsets/i' => ['offsetSuggestion'],
            '/invalid argument supplied for foreach|foreach\(\) argument must be of type|must be traversable/i' => ['iterableSuggestion'],
            '/count\(\).*must be of type countable|count\(\): parameter must be an array/i' => ['countSuggestion'],
            '/non-numeric value encountered|a non well formed numeric value/i' => ['numericSuggestion'],
            '/valueerror|unhandledmatcherror|must not be empty|must be greater than|must be less than/i' => ['valueSuggestion'],
            '/readonly property|cannot modify readonly/i' => ['readonlySuggestion'],
            '/null.*(property|method|offset)|on null/i'                         => ['nullSuggestion'],
            '/division by zero|modulo by zero/i'                                 => ['mathSuggestion'],
            '/failed opening|required .* failed|include.*failed|no such file/i' => ['fileSuggestion'],
            '/permission denied|not writable|failed to open stream/i'           => ['permissionSuggestion'],
            '/unknown column|unknown table|base table|column .* not found/i'    => ['databaseSuggestion'],
            '/pdoexception|database.*(connect|connection)|access denied/i'     => ['databaseConnectionSuggestion'],
            '/duplicate entry|integrity constraint|foreign key|cannot be null/i'=> ['databaseConstraintSuggestion'],
            '/allowed memory size|out of memory/i'                               => ['memorySuggestion'],
            '/maximum execution time|timed out|timeout/i'                       => ['timeoutSuggestion'],
            '/curl|could not resolve host|ssl|connection refused|http.*error/i' => ['networkSuggestion'],
            '/json|malformed utf-8|utf-8/i'                                     => ['jsonSuggestion'],
            '/preg_|regular expression|compilation failed/i'                    => ['regexSuggestion'],
            '/upload|uploaded file|upload_max_filesize|post_max_size/i'         => ['uploadSuggestion'],
            '/session.*(not|undefined|start)|headers already sent/i'            => ['sessionSuggestion']
        ];

        foreach( $rules as $pattern => $keys )
        {
            if( preg_match($pattern, $message) )
            {
                foreach( $keys as $key )
                {
                    if( $key !== 'typeSuggestion' || empty($suggestions) )
                    {
                        $suggestions[] = $language[$key] ?? NULL;
                    }
                }
            }
        }

        // Merge the Wizard-specific reminder (if any) together with the
        // message-aware suggestions above -- the Wizard reminder goes
        // first since it orients the developer to the file type, then the
        // concrete, message-specific explanation follows.
        $suggestions = array_merge($wizardSuggestions, $suggestions);
        $suggestions = array_values(array_unique(array_filter($suggestions)));

        if( empty($suggestions) )
        {
            $suggestions[] = $language['genericSuggestion'] ?? 'Review the selected source line.';
        }

        return $suggestions;
    }

    /**
     * Return Wizard-directive-aware usage suggestions when the error's
     * resolved source file is an actual .wizard.php template.
     *
     * @param string $file
     * @param string $message
     * @param array  $language
     *
     * @return array
     */
    protected static function getWizardUsageSuggestions($file, $message, $language)
    {
        $suggestions = [];

        if( empty($file) || ! is_file($file) || ! stristr($file, '.wizard.php') )
        {
            return $suggestions;
        }

        $content = @file_get_contents($file);

        if( $content === false || $content === '' )
        {
            return $suggestions;
        }

        foreach( self::wizardBracketPairs() as $pair )
        {
            $openCount  = substr_count($content, $pair['open']);
            $closeCount = substr_count($content, $pair['close']);

            if( $openCount !== $closeCount )
            {
                $suggestions[] = self::formatWizardImbalance($language, $pair['label'], $pair['open'], $pair['close'], $openCount, $closeCount);
            }
        }

        foreach( self::wizardDirectivePairs() as $pair )
        {
            $openCount  = preg_match_all($pair['openRegex'], $content);
            $closeCount = preg_match_all($pair['closeRegex'], $content);

            if( $openCount !== $closeCount )
            {
                $suggestions[] = self::formatWizardImbalance($language, $pair['label'], $pair['open'], $pair['close'], $openCount, $closeCount);
            }
        }

        if( preg_match('/syntax error|parse error|unexpected/i', $message) && strpos($content, '?>') !== false )
        {
            $suggestions[] = $language['wizardStrayCloseTagSuggestion'] ?? NULL;
        }

        if( $unclosed = self::findUnclosedWizardFunctionDirective($content) )
        {
            $suggestions[] = self::formatWizardUnclosedDirective($language, $unclosed);
        }

        $suggestions = array_values(array_unique(array_filter($suggestions)));

        // Pinpoint over generic: once something specific was found, that IS
        // the answer -- do not also pad it with the generic reminder below.
        if( ! empty($suggestions) )
        {
            return $suggestions;
        }

        // The generic "this is a Wizard file, check directive/block syntax"
        // reminder below is only useful for something that plausibly IS a
        // Wizard authoring mistake (a syntax/parse-shaped message). A
        // runtime error (TypeError, ArgumentCountError, a database error,
        // ...) can just as easily be thrown from a perfectly well-formed
        // Wizard template -- e.g. `{{ Date::toReadable($x->maybeNull) }}`
        // when $x->maybeNull happens to be NULL at render time -- and
        // telling the developer to go check directive syntax there is
        // actively misleading (confirmed live: this exact message wrongly
        // got the Wizard-syntax reminder instead of a null-argument one).
        // Returning empty here lets getErrorSuggestions() fall through to
        // its own message-aware rules instead -- getTypeMismatchSuggestions()
        // in particular already has a dedicated "null given" suggestion.
        if( ! preg_match('/syntax error|parse error|unexpected (token|variable|identifier)|unclosed|does not match|unterminated/i', $message) )
        {
            return [];
        }

        return array_filter([$language['wizardSuggestion'] ?? NULL]);
    }

    /**
     * Detect a bare "@directive(...)" call (@t(...), @view(...), @anchor(...),
     * @selector(...), @ajax(...), form-field directives, etc. -- anything
     * compiled by Wizard's own functions() rule) that Wizard will fail to
     * close with "?>".
     *
     * Wizard's compiler inserts the closing "?>" right after the call's
     * closing ")" only when what immediately follows (ignoring whitespace)
     * is one of: a newline, end of file, "->", "::", another ")", "{", an
     * already-present "?>" -- OR a literal ":" placed right after the ")"
     * (Wizard has a dedicated rule for exactly this: "@t('Main'):" compiles
     * correctly even inline, verified against the real compiler). A call
     * glued directly to more inline markup with none of these -- e.g.
     * `>@t('Main')</a>` -- leaves the PHP tag open and the following markup
     * gets parsed as PHP code, which is exactly the bug this reproduces.
     *
     * @param string $content
     *
     * @return string|null the matched directive text, or NULL if none found
     */
    protected static function findUnclosedWizardFunctionDirective($content)
    {
        if
        (
            preg_match
            (
                '/@\w+\([^()]*\)(?!\s*(\r?\n|\-\>|\:\:|\:|\)|\{|\?\>|$))/',
                $content,
                $match
            )
        )
        {
            return trim($match[0]);
        }

        return NULL;
    }

    /**
     * @param array  $language
     * @param string $directive
     *
     * @return string
     */
    protected static function formatWizardUnclosedDirective($language, $directive)
    {
        $template = $language['wizardUnclosedDirectiveSuggestion']
            ?? '%directive% is not closed with ":" here. Fix: write %directiveWithColon%, or use {{ ... }} instead.';

        return str_replace
        (
            ['%directive%', '%directiveWithColon%'],
            [$directive, preg_replace('/\)$/', '):', $directive)],
            $template
        );
    }

    /**
     * Bracket-style Wizard blocks that must appear in matching pairs.
     *
     * @return array
     */
    protected static function wizardBracketPairs()
    {
        return
        [
            ['open' => '{[',  'close' => ']}',  'label' => '{[ ]}  (Ham PHP Bloğu / Raw PHP Block)'],
            ['open' => '{{',  'close' => '}}',  'label' => '{{ }}  (Echo Bloğu / Echo Block)'],
            ['open' => '{<',  'close' => '>}',  'label' => '{< >}  (JS Callback Bloğu / JS Callback Block)'],
            ['open' => '{--', 'close' => '--}', 'label' => '{-- --}  (HTML Yorumu / HTML Comment)']
        ];
    }

    /**
     * Word-style Wizard directive open/close pairs, matched with the same
     * case-sensitive patterns Wizard.php's own compiler uses.
     *
     * @return array
     */
    protected static function wizardDirectivePairs()
    {
        return
        [
            ['open' => '@if(',        'close' => '@endif',        'openRegex' => '/@if\(/',                          'closeRegex' => '/@endif\b/'],
            ['open' => '@foreach(',   'close' => '@endforeach',   'openRegex' => '/@foreach\(/',                     'closeRegex' => '/@endforeach\b/'],
            ['open' => '@for(',       'close' => '@endfor',       'openRegex' => '/@for\(/',                         'closeRegex' => '/@endfor\b/'],
            ['open' => '@while(',     'close' => '@endwhile',     'openRegex' => '/@while\(/',                       'closeRegex' => '/@endwhile\b/'],
            ['open' => '@loop(',      'close' => '@endloop',      'openRegex' => '/@loop\(/',                        'closeRegex' => '/@endloop\b/'],
            ['open' => '@forelse(',   'close' => '@endforelse',   'openRegex' => '/@forelse\(/',                     'closeRegex' => '/@endforelse\b/'],
            ['open' => '@form(',      'close' => '@endform',      'openRegex' => '/@form\(/',                        'closeRegex' => '/@endform\b/'],
            ['open' => '@login(',     'close' => '@endlogin',     'openRegex' => '/@login\(/',                       'closeRegex' => '/@endlogin\b/'],
            ['open' => '@valid(',     'close' => '@endvalid',     'openRegex' => '/@valid\(/',                       'closeRegex' => '/@endvalid\b/'],
            ['open' => '@perm(',      'close' => '@endperm',      'openRegex' => '/@perm\(/',                        'closeRegex' => '/@endperm\b/'],
            ['open' => '@container / @containerFluid', 'close' => '@endcontainer', 'openRegex' => '/@(?:container|containerFluid)\b/', 'closeRegex' => '/@endcontainer\b/'],
            ['open' => '@row',        'close' => '@endrow',       'openRegex' => '/@row\b/',                         'closeRegex' => '/@endrow\b/'],
            ['open' => '@col..NN',    'close' => '@endcol',       'openRegex' => '/@col[a-zA-Z]{2}[0-9]{1,2}\b/',    'closeRegex' => '/@endcol\b/']
        ];
    }

    /**
     * Format an open/close mismatch message from the active language pack.
     *
     * @param array  $language
     * @param string $label
     * @param string $open
     * @param string $close
     * @param int    $openCount
     * @param int    $closeCount
     *
     * @return string
     */
    protected static function formatWizardImbalance($language, $label, $open, $close, $openCount, $closeCount)
    {
        $template = $language['wizardImbalanceSuggestion']
            ?? '%label% mismatch: %open% appears %openCount% time(s) but %close% appears %closeCount% time(s).';

        return str_replace
        (
            ['%label%', '%open%', '%close%', '%openCount%', '%closeCount%'],
            [$label, $open, $close, $openCount, $closeCount],
            $template
        );
    }

    /** Extract the concrete identifier involved in a runtime error. */
    protected static function getMessageDetailSuggestion($message, $language)
    {
        $rules =
        [
            '/unclosed\s+["\'](.+?)["\']\s+on line\s+(\d+)\s+does not match\s+["\'](.+?)["\']/i' => 'delimiterDetailSuggestion',
            '/undefined variable\s+(\$\w+)/i' => 'variableDetailSuggestion',
            '/undefined array key\s+["\']?([^"\']+)/i' => 'arrayDetailSuggestion',
            '/call to undefined method\s+([^\s(]+)/i' => 'methodDetailSuggestion',
            '/(?:class|interface|trait)\s+["\']?([^"\']+).*?not found/i' => 'classDetailSuggestion',
            '/unknown (?:column|table)\s+["\']?([^"\']+)/i' => 'databaseDetailSuggestion',
            '/(?:failed opening|required).*?["\']([^"\']+)["\']/i' => 'fileDetailSuggestion'
        ];

        foreach( $rules as $pattern => $key )
        {
            if( preg_match($pattern, $message, $match) )
            {
                if( $key === 'delimiterDetailSuggestion' )
                {
                    return str_replace
                    (
                        ['%open%', '%close%', '%line%'],
                        [$match[1], $match[3], $match[2]],
                        $language[$key] ?? ''
                    );
                }

                return str_replace('%value%', $match[1], $language[$key] ?? '');
            }
        }

        return NULL;
    }

    /**
     * Extract a concrete PHP argument type mismatch from an error message.
     *
     * @param string $message
     * @param array  $language
     *
     * @return array
     */
    protected static function getTypeMismatchSuggestions($message, $language)
    {
        if
        (
            ! preg_match
            (
                '/(.+?)::([\w]+)\(\):\s+Argument\s+#(\d+)\s+\(\$([\w]+)\)\s+must\s+be\s+of\s+type\s+([^,]+),\s+([\w]+)\s+given/i',
                $message,
                $match
            )
        )
        {
            return [];
        }

        $function = $match[1] . '::' . $match[2] . '()';
        $detail   = $language['typeMismatchSuggestion'] ?? '%function% expects `%expected%`, `%actual%` given.';
        $detail   = str_replace
        (
            ['%function%', '%argument%', '%parameter%', '%expected%', '%actual%'],
            [$function, $match[3], $match[4], trim($match[5]), strtolower($match[6])],
            $detail
        );

        $suggestions = [$detail];
        $actual      = strtolower($match[6]);

        if( $actual === 'array' )
        {
            $suggestions[] = $language['arrayTypeSuggestion'] ?? NULL;
        }
        elseif( $actual === 'null' )
        {
            $suggestions[] = $language['nullTypeSuggestion'] ?? NULL;
        }
        else
        {
            $suggestions[] = $language['scalarTypeSuggestion'] ?? NULL;
        }

        return array_filter($suggestions);
    }

    /**
     * Protected search error wizard file
     * 
     * @codeCoverageIgnore
     */
    protected static function searchErrorWizardFile($args, &$file, &$line)
    {
        foreach( $args as $value )
        {
            if( is_array($value) )
            {
                if( empty($line) && isset($value['file']) && stristr($value['file'], DS . 'Buffering.php') )
                {
                    $line = $value['line'] ?? NULL;
                }

                $find = $value['args'][0] ?? NULL;

                if( is_string($find) && preg_match('/(Views\/)*.*?\.\wizard(\.php)*/', $find) )
                {
                    $file = $find;

                    return;
                }

                self::searchErrorWizardFile($value, $file, $line);

                if( stristr($file, '.wizard') ) return;
            }
        }
    }

    /**
     * Protected is wizard or standart file exists
     * 
     * @codeCoverageIgnore
     */
    protected static function isWizardOrStandartFileExists(&$file, $trace)
    {
        if( ! is_file($file) )
        {
            $file = Base::prefix($file, VIEWS_DIR);

            if( ! is_file($file) )
            {
                if( ! is_file($rfile = $file . '.php') )
                {
                    if( ! is_file($rfile = $file . '.wizard.php') )
                    {
                        if( isset($trace[0]['file']) && is_file($rfile = Base::suffix(Base::prefix($trace[0]['file'], VIEWS_DIR), '.php')) )
                        {
                            $file = $rfile;
                        }
                        else
                        {
                            if( ! is_file($rfile) )
                            {
                                $file = VIEWS_DIR . CURRENT_CONTROLLER . '/' . CURRENT_CFUNCTION . '.wizard.php';
                            }
                        }          
                    }
                    else
                    {
                        $file = $rfile;
                    }
                }
                else
                {
                    $file = $rfile;    
                }
            } 
        }
    }

    /**
     * Display exception table
     * 
     * @param string $file
     * @param string $line
     * @param string $key
     * 
     * @return void
     */
    public static function display($file, $line, $key)
    {
        $content = is_file($file) ? file($file) : [];
        $line    = max(1, (int) $line);
        $start   = max(0, $line - 9);
        $end     = min(count($content), $line + 8);
        ?>
        <div class="list-group-item panel-header" style="color:#999;">
            <span><i class="fa fa-file-code-o fa-fw panel-text"></i>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo htmlspecialchars((string) $file, ENT_QUOTES, 'UTF-8'); ?> : <?php echo $line; ?></span>
        </div>
        <div>
        <pre class="source-code"><?php
        if( empty($content) )
        {
            echo htmlspecialchars('Kaynak dosya okunamadı.', ENT_QUOTES, 'UTF-8');
        }
        else for( $i = $start; $i < $end; $i++ )
        {
            $index = $i + 1;
            $class = $index === $line ? 'source-line is-error' : 'source-line';
            $code  = self::highlightSourceLine(rtrim($content[$i], "\r\n"), $file);

            echo '<span class="'.$class.'"><span class="source-line-number">'.$index.'</span>'.$code.'</span>';
        }
        ?></pre></div><?php
    }

    /**
     * Apply lightweight HTML syntax colours without changing the source text.
     *
     * @param string $line
     *
     * @return string
     */
    protected static function highlightSourceLine($line, $file = '')
    {
        if( strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php' && ! stristr($file, '.wizard.php') )
        {
            return self::highlightPHPSourceLine($line);
        }

        $line = htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $line = preg_replace_callback
        (
            '/&lt;!--.*?--&gt;|&lt;\/?[a-z][\s\S]*?&gt;/i',
            function($match)
            {
                $tag = $match[0];

                if( substr($tag, 0, 7) === '&lt;!--' )
                {
                    return '<span class="source-html-comment">'.$tag.'</span>';
                }

                $tag = preg_replace
                (
                    '/^(&lt;\/?)([a-z][\w:-]*)/i',
                    '$1<span class="source-html-tag">$2</span>',
                    $tag
                );

                return preg_replace
                (
                    '/\b([\w:-]+)(=)(&quot;.*?&quot;|&#039;.*?&#039;)/',
                    '<span class="source-html-attribute">$1</span>$2<span class="source-html-value">$3</span>',
                    $tag
                );
            },
            $line
        );

        $line = preg_replace_callback
        (
            '/(\{\{.*?\}\}|\{\[.*?\]\})/',
            function($match)
            {
                $class = substr($match[0], 0, 2) === '{{'
                       ? 'source-wizard-expression'
                       : 'source-wizard-php';

                return '<span class="'.$class.'">'.$match[0].'</span>';
            },
            $line
        );

        return preg_replace
        (
            '/(?<![\w:-])(@[a-z][\w]*)/i',
            '<span class="source-wizard-directive">$1</span>',
            $line
        );
    }

    /**
     * Highlight a PHP source line with PHP's tokenizer.
     *
     * token_get_all() is lexical, so it still works when the source line
     * itself contains the syntax error currently being displayed.
     *
     * @param string $line
     *
     * @return string
     */
    protected static function highlightPHPSourceLine($line)
    {
        $keywords =
        [
            'abstract', 'and', 'array', 'as', 'break', 'callable', 'case',
            'catch', 'class', 'clone', 'const', 'continue', 'declare',
            'default', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare',
            'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'enum',
            'eval', 'exit', 'extends', 'false', 'final', 'finally', 'fn',
            'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements',
            'include', 'include_once', 'instanceof', 'insteadof', 'interface',
            'isset', 'list', 'match', 'namespace', 'new', 'null', 'or', 'print',
            'private', 'protected', 'public', 'readonly', 'require',
            'require_once', 'return', 'static', 'switch', 'throw', 'trait',
            'true', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield',
            'yield from', '__class__', '__dir__', '__file__', '__function__',
            '__line__', '__method__', '__namespace__', '__trait__'
        ];

        $output = '';

        foreach( token_get_all('<?php ' . $line) as $token )
        {
            if( ! is_array($token) )
            {
                $output .= htmlspecialchars($token, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                continue;
            }

            [$id, $text] = $token;

            if( $id === T_OPEN_TAG )
            {
                continue;
            }

            $class = NULL;
            $name  = token_name($id);
            $word  = strtolower(trim($text));

            if( in_array($word, $keywords, true) )
            {
                $class = 'source-php-keyword';
            }
            elseif( in_array($name, ['T_COMMENT', 'T_DOC_COMMENT'], true) )
            {
                $class = 'source-php-comment';
            }
            elseif( $name === 'T_VARIABLE' )
            {
                $class = 'source-php-variable';
            }
            elseif( in_array($name, ['T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'], true) )
            {
                $class = 'source-php-string';
            }
            elseif( in_array($name, ['T_LNUMBER', 'T_DNUMBER', 'T_NUM_STRING'], true) )
            {
                $class = 'source-php-number';
            }
            elseif
            (
                $name === 'T_STRING' ||
                strpos($name, 'T_NAME_') === 0 ||
                $name === 'T_NS_SEPARATOR'
            )
            {
                $class = 'source-php-name';
            }
            elseif( preg_match('/^[=+\-*\/%.!<>&|?:~]+$/', trim($text)) )
            {
                $class = 'source-php-operator';
            }

            $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $output .= $class === NULL ? $text : '<span class="'.$class.'">'.$text.'</span>';
        }

        return $output;
    }

}
