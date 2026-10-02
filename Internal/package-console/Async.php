<?php namespace ZN\Console;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Throwable;
use ZN\Base;
use ZN\Config;
use ZN\Request;
use ZN\Buffering;
use ZN\Filesystem;
use ZN\Lang;
use ZN\Support;
use DB;
use DBTool;
use ZN\Database\Properties;
use ZN\Protection\Json;
use ZN\Helpers\Converter;
use ZN\ErrorHandling\Errors;
use ZN\ErrorHandling\Exceptions;

/**
 * @codeCoverageIgnore
 */
class Async
{
    /**
     * Marker prepended to the bare proc key when a job is created while
     * the 'db' driver is active. It lets process() — running in a
     * separate CLI process that never called driver('db') itself —
     * detect from the proc id alone that it must switch to the 'db'
     * driver before reading the job's data, without needing that
     * driver choice to be duplicated in the CLI bootstrap.
     *
     * @var string DB_PROC_PREFIX
     */
    const DB_PROC_PREFIX = 'db__';

    /**
     * Keeps process ID.
     *
     * @var string $procId
     */
    protected static $procId = '';

    /**
     * Keeps process directory.
     * 
     * @var string $procDir
     */
    protected static $procDir = FILES_DIR;
    
    /**
     * Keeps soket URI.
     * 
     * @var string $socket
     */
    protected static $socketURI = '';
    
    /**
     * Keeps socket success
     * 
     * @var string $success
     */
    protected static $success = '';

    /**
     * Keeps socket error
     * 
     * @var string $error
     */
    protected static $error   = '';

    /**
     * Keeps the active storage driver ('file' or 'db').
     * 
     * @var string $driver
     */
    protected static $driver = 'file';

    /**
     * Keeps the table name used by the 'db' driver.
     * 
     * @var string $table
     */
    protected static $table = 'AsyncProcFiles';

    /**
     * Whether the 'db' driver's table has already been created/verified
     * in this process.
     *
     * @var bool $tablePrepared
     */
    protected static $tablePrepared = false;

    /**
     * Sets process directory.
     *
     * @param string $directory
     *
     * @return self
     */
    public static function setProcDirectory(string $directory) : Async
    {
        self::$procDir = $directory;

        return new self;
    }

    /**
     * Sets socket URI.
     * 
     * @param string $directory
     * 
     * @return self
     */
    public static function setSocketURI(string $socketURI) : Async
    {
        self::$socketURI = $socketURI;

        return new self;
    }

    /**
     * Sets the storage driver used to keep proc data.
     * 
     * Call this once (typically in the application's Initialize controller) with
     * Async::driver('db') to store procs in the application's currently connected
     * database instead of the filesystem. Not calling this, or calling it with
     * 'file', keeps the original file-based behavior unchanged.
     * 
     * @param string      $driver - 'file' or 'db'
     * @param string|null $table  = NULL - custom table name for the 'db' driver,
     *                               only needed if the default table name
     *                               ('AsyncProcFiles') collides with an existing,
     *                               differently structured table.
     * 
     * @return self
     */
    public static function driver(string $driver, ?string $table = NULL) : Async
    {
        self::$driver = $driver;

        if( $table !== NULL )
        {
            self::$table         = $table;
            self::$tablePrepared = false;
        }

        if( $driver === 'db' )
        {
            self::prepareTable();
        }

        return new self;
    }

    /**
     * Get process data.
     * 
     * @param string $procId = current-process
     * 
     * @return array
     */
    public static function getData(string $procId = '') : array
    {
        if( self::$driver === 'db' )
        {
            $key = self::dbKey($procId);

            $row = DB::where('procKey', $key)->get(self::$table)->row();

            return $row ? Json::decodeArray($row->data) : [];
        }

        $procFile = self::getProcFile($procId);

        if( is_file($procFile) )
        {
            return Json::decodeArray(file_get_contents($procFile));
        }

        return [];
    }

    /**
     * Get process Id.
     */
    public static function getProcId() : string
    {
        return self::$procId;
    }

    /**
     * Run command
     *
     * @param string $command
     * @param array  $data = []
     * @param string $name = NULL
     */
    public static function run(string $command, array $data = [], ?string $name = NULL) : string
    {
        $uniq = self::applyDriverPrefix($name ? preg_replace('/\W+/', '', $name) : uniqid());

        self::$procId = $procId = self::$procDir . $uniq;

        $processor = Config::default('ZN\Prompt\PromptDefaultConfiguration')::get('Services', 'processor');

        if( ! file_exists($processor['path']) ) 
        {
            $path = 'php';
        }
        else
        {
            $path = $processor['path'];
        }

        $open = proc_open($path . ' zerocore ' . $command . ' "' . $procId . '"', [], $arr);

        $data['status'] = proc_get_status($open);

        $data['status']['run']      = $command;
        $data['status']['file']     = $uniq;
        $data['status']['path']     = self::$procId;
        
        self::putData($uniq, $data);

        return $procId;
    }

    /**
     * List
     * 
     * @return array
     */
    public static function list() : array
    {
        $processList = [];

        if( self::$driver === 'db' )
        {
            foreach( DB::get(self::$table)->result() as $row )
            {
                // 'procKey'/'addDate' are prepended (never overwritten, see
                // the '+' operator below) so callers that need to identify
                // or act on a specific entry (e.g. an admin listing view)
                // don't have to separately reach into the raw db row —
                // decodeArray()'s own keys always win if they ever collide.
                $processList[] = ['procKey' => $row->procKey, 'addDate' => $row->addDate] + Json::decodeArray($row->data);
            }

            return $processList;
        }

        foreach( Filesystem::getFiles(self::$procDir, NULL, true) as $file )
        {
            // Same 'procKey' addition as the 'db' branch above, for the
            // file driver: $file is already a full path (see clear()'s note
            // on Filesystem::getFiles()'s $isPath=true), so the key is its
            // basename, matching getProcKey()'s own derivation.
            $processList[] = ['procKey' => self::getProcKey($file)] + self::getData($file);
        }

        return $processList;
    }

    /**
     * Close proc
     * 
     * @param string $procId = current-process
     * 
     * @return string|false
     */
    public static function close(string $procId = '')
    {
        // The proc record must be cleaned up whether or not a pid could be
        // read back (a failed/empty read must not leave the record behind
        // forever — isFinish()/status() key the "still running" check off
        // its mere existence, see below). The pid is only needed to also
        // kill a still-running OS process, which is a separate concern; it
        // is read here, before remove() deletes the very record it comes
        // from.
        $pid = self::getData($procId)['status']['pid'] ?? NULL;

        // The main record is removed unconditionally, whether the job
        // succeeded or threw: when it threw, process() has already written
        // a separate '-error' report for it (see getError()), under its
        // own '-error'-suffixed key, which remove() never touches. That is
        // what's meant to stay behind for inspection — not this record,
        // which only holds the job's original input payload, not what
        // went wrong. status()/isFinish() check for that '-error' record's
        // existence directly (not this one's), so polling still correctly
        // stops either way.
        self::remove($procId);

        return $pid ? self::closeProcess($pid) : false;
    }

    /**
     * Close All Process
     *
     */
    public static function closeAll()
    {
        foreach( self::list() as $proc )
        {
            if( isset($proc['file']) )
            {
                self::close($proc['file']);
            }
        }
    }

    /**
     * Is Exists
     * 
     * @param string $procId = current-process
     * 
     * @return bool
     */
    public static function isExists(string $procId = '') : bool
    {
        if( self::$driver === 'db' )
        {
            return (bool) DB::where('procKey', self::getProcKey($procId))->get(self::$table)->row();
        }

        $procFile = self::getProcFile($procId);

        return is_file($procFile);
    }

    /**
     * Command process
     * 
     * @param string   $procId
     * @param callback $callable
     * @param bool     $displayError = false
     */
    public static function process(string $procId, callable $callable, bool $displayError = false) : void
    {
        self::$procId = $procId;

        // The CLI process never calls driver('db') itself — it reads the
        // choice back from the proc id, which run() marked at creation
        // time on the web side. This is the single source of truth: the
        // driver is only ever chosen where run() is called.
        if( strpos(self::getProcKey($procId), self::DB_PROC_PREFIX) === 0 )
        {
            self::driver('db');
        }

        $data = self::getData($procId);

        try
        {
            $callable($data, $procId);
        }
        catch( Throwable $e )
        {
            if( $displayError )
            {
                $error = 
                [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => $e->getTrace()
                ];

                self::report($error, 'error');
            }
        }

        self::close();
    }
    
    /**
     * Remove process ID
     * 
     * @param string $procId = current-process
     * 
     * @return bool
     */
    public static function remove(string $procId = '') : bool
    {
        if( self::$driver === 'db' )
        {
            $key = self::dbKey($procId);

            if( ! DB::where('procKey', $key)->get(self::$table)->row() )
            {
                return false;
            }

            return (bool) DB::where('procKey', $key)->delete(self::$table);
        }

        $procFile = self::getProcFile($procId);

        if( is_file($procFile) )
        {
            return unlink($procFile);
        }

        return false;
    }
    
    /**
     * Deletes stale derived records (or files, for the 'file' driver) —
     * anything report()/output() wrote under a '-suffix' key (e.g.
     * '-error', '-output', '-report', or any other custom suffix a caller
     * passes to report()) that was never consumed by getError()/socket()/
     * displayError(). Nothing else in this class ever removes these on
     * its own, so without this they accumulate indefinitely once a caller
     * stops reading them back.
     *
     * A hyphen is what identifies one of these: run() only ever produces
     * a bare key from uniqid() or a \W-stripped $name, and
     * DB_PROC_PREFIX is 'db__' — none of those can ever contain a '-',
     * while every derived key is built as the base key plus '-' plus a
     * suffix (see report()). So "contains a hyphen" reliably matches
     * every derived record/file, including any custom suffix, while
     * never matching a job's own un-suffixed proc record (still pending,
     * or already closed by close()) — a currently running job can never
     * be swept up by mistake.
     *
     * This is deliberately a manual, on-demand cleanup: nothing in this
     * class calls it automatically on a timer. It only runs when the
     * application explicitly invokes it (e.g. from an admin action or
     * its own scheduled command).
     *
     * @param int $olderThanMinutes = 0 - 0 (default) removes all of them
     *                                 regardless of age; pass a positive
     *                                 number to only remove records/files
     *                                 at least that many minutes old.
     *
     * @return int number of removed records/files
     */
    public static function clear(int $olderThanMinutes = 0) : int
    {
        if( self::$driver === 'db' )
        {
            $threshold = $olderThanMinutes > 0 ? date('Y-m-d H:i:s', time() - $olderThanMinutes * 60) : NULL;

            DB::whereLike('procKey', '-');

            if( $threshold !== NULL )
            {
                DB::where('addDate <', $threshold);
            }

            $count = (int) DB::count('*')->get(self::$table)->value();

            if( $count > 0 )
            {
                DB::whereLike('procKey', '-');

                if( $threshold !== NULL )
                {
                    DB::where('addDate <', $threshold);
                }

                DB::delete(self::$table);
            }

            return $count;
        }

        $threshold = $olderThanMinutes > 0 ? time() - $olderThanMinutes * 60 : NULL;
        $removed   = 0;

        // Filesystem::getFiles($dir, $extension, $isPath = true) already
        // returns each entry prefixed with self::$procDir (same call shape
        // list() above uses), so $file here is already a full, usable path
        // — it must not be prefixed again.
        foreach( Filesystem::getFiles(self::$procDir, NULL, true) as $file )
        {
            if( strpos(basename($file), '-') === false )
            {
                continue;
            }

            if( $threshold !== NULL && is_file($file) && filemtime($file) > $threshold )
            {
                continue;
            }

            if( is_file($file) && unlink($file) )
            {
                $removed++;
            }
        }

        return $removed;
    }

    /**
     * Create report
     * 
     * @param array $data
     * 
     * @return int
     */
    public static function report(array $data, string $suffix = 'report') : int
    {
        if( self::$driver === 'db' )
        {
            self::putData(self::getProcKey() . '-' . $suffix, $data);

            return 1;
        }

        return file_put_contents(self::$procId . '-' . $suffix, Json::encode($data));
    }

    /**
     * Output
     * 
     * @param array $data
     * 
     * @return int
     */
    public static function output(array $data) : int
    {
        return self::report($data, 'output');
    }

    /**
     * Reads back the error report() recorded for $procId (process() writes
     * one automatically when the job's callable throws and displayError
     * is true), if any. Consumes it — a second call for the same proc
     * returns NULL — matching socket()'s read-once '-output' handling.
     *
     * @param string $procId = current-process
     *
     * @return array|null
     */
    public static function getError(string $procId = '') : ?array
    {
        $procId = $procId ?: self::$procId;

        if( self::$driver === 'db' )
        {
            $key = self::getProcKey($procId) . '-error';

            $row = DB::where('procKey', $key)->get(self::$table)->row();

            if( ! $row )
            {
                return NULL;
            }

            DB::where('procKey', $key)->delete(self::$table);

            return Json::decodeArray($row->data);
        }

        $errorFile = Base::suffix(self::getProcFile($procId), '-error');

        if( ! is_file($errorFile) )
        {
            return NULL;
        }

        $error = Json::decodeArray(file_get_contents($errorFile));

        unlink($errorFile);

        return $error;
    }

    /**
     * Creates socket
     */
    public static function socket()
    {
        $procId = $_POST['procId'] ?? '';

        // Proc ids are always server-generated (uniqid() or a \W-stripped
        // name, see run()); rejecting anything outside that character set
        // closes off path traversal (e.g. '../') in the file driver below
        // without affecting any legitimate value.
        if( ! preg_match('/^[A-Za-z0-9_]+$/', $procId) )
        {
            echo json_encode([]);

            exit;
        }

        if( self::$driver === 'db' )
        {
            $key = self::getProcKey($procId);

            if( DB::where('procKey', $key)->get(self::$table)->row() )
            {
                echo json_encode(['status' => 'processing']);
            }
            else
            {
                $outputKey = $key . '-output';
                $row       = DB::where('procKey', $outputKey)->get(self::$table)->row();

                if( $row )
                {
                    echo $row->data;

                    DB::where('procKey', $outputKey)->delete(self::$table);
                }
                else
                {
                    echo json_encode([]);
                }
            }

            exit;
        }

        $originFile = self::getProcFile($procId);

        $procFile = Base::suffix($originFile, '-output');

        if( is_file($originFile) )
        {
            echo json_encode(['status' => 'processing']);
        }
        else if( is_file($procFile) )
        {
            echo file_get_contents($procFile);

            unlink($procFile);
        }
        else
        {
            echo json_encode([]);
        }

        exit;
    }

    
    /**
     * Sets socket success 
     * 
     * @param callable $callback
     */
    public static function success(callable $callable)
    {
        self::$success = $callable;

        return new self;
    }

    /**
     * Sets socket error 
     * 
     * @param callable $callback
     */
    public static function error(callable $callable)
    {
        self::$error = $callable;

        return new self;
    }

    /**
     * @param string $procId
     * @param int    $time = 1000
     * 
     * @return string
     */
    public static function listen($procId, int $time = 1000) : string
    {
        $var = 'socket' . uniqid();

        $procData = ( is_scalar($procId) ? '"' . str_replace(self::$procDir, '', $procId) . '"' : Buffering\Callback::do($procId) );
        
        $return =
        '   
            var ' . $var . ' = setInterval(function()
            {
                $.ajax
                ({
                    url: "' . Request::getSiteURL(self::$socketURI) . '",
                    type: "post",
                    dataType: "json",
                    data: {procId: ' . $procData . '},
                    success: function(data)
                    {
                        ' . (self::$success ? Buffering\Callback::do(self::$success) : '') . '
                        
                        if( data.status != "processing" )
                        {
                            clearInterval(' . $var . ');
                        }
                    },
                    error: function(data)
                    {
                        ' . (self::$error ? Buffering\Callback::do(self::$error) : '') . '
                    }
                })
            }, ' . $time . ');
        ';

        self::$success = '';

        return $return;
    }

    /**
     * Status
     * 
     * @param string ...$procIds
     * 
     * @return array
     */
    public static function status(string ...$procIds) : array
    {
        $pending = [];

        foreach( $procIds as $procId )
        {
            // A record whose job errored is kept around by close() instead
            // of being removed (see close()), so its mere existence is no
            // longer enough to mean "still running" — an error-flagged
            // record counts as finished here too.
            if( self::isExists(self::getProcKey($procId) . '-error') )
            {
                continue;
            }

            if( self::$driver === 'db' )
            {
                if( DB::where('procKey', self::getProcKey($procId))->get(self::$table)->row() )
                {
                    $pending[$procId] = 1; # pending.
                }

                continue;
            }

            $procFile = self::getProcFile($procId);

            if( is_file($procFile) )
            {
                $pending[$procFile] = 1; # pending.
            }
        }

        return $pending;
    }

    /**
     * Is finish
     * 
     * @param string ...$procIds
     * 
     * @return bool
     */
    public static function isFinish(string ...$procIds) : bool
    {
        return  ! in_array(1, self::status(...$procIds));
    }

    /**
     * Dispay Report
     * 
     * @param string $errorFile
     * 
     * @return string
     */
    public static function displayError(string $errorFile) : string
    {
        // Legitimate values are always a server-generated proc key plus a
        // literal '-report'/'-error'/'-output' suffix (see report()), so
        // only letters, digits, underscore and hyphen are expected; this
        // closes off path traversal (e.g. '../') in the file driver below
        // without affecting any legitimate value.
        if( ! preg_match('/^[A-Za-z0-9_\-]+$/', $errorFile) )
        {
            return Errors::message('File not found!');
        }

        if( self::$driver === 'db' )
        {
            $row = DB::where('procKey', $errorFile)->get(self::$table)->row();

            if( $row )
            {
                $data = Json::decodeArray($row->data);

                return Buffering\Callback::do(function() use($data)
                { 
                    Exceptions::table($data['code'] ?? NULL, $data['message'], $data['file'], $data['line'], $data['trace']); 
                });
            }

            return Errors::message('File not found!');
        }

        if( is_file($file = self::$procDir . $errorFile) && ( $fileContent = file_get_contents($file) ) ) 
        {
            $data = Json::decodeArray($fileContent);

            return Buffering\Callback::do(function() use($data)
            { 
                Exceptions::table($data['code'] ?? NULL, $data['message'], $data['file'], $data['line'], $data['trace']); 
            });
        }
        
        return Errors::message('File not found!');
    }

    /**
     * Loop
     * 
     * @param int $count
     * @param int $waitSecond
     * @param callable $callback
     */
    public static function loop(int $count, int $waitSecond, callable $callback)
    {
        $i = 1;

        while( true )
        {
            $callback($i, $waitSecond);

            if( $i == $count )
            {
                self::close();
            }

            $i++;

            sleep($waitSecond);
        }
    } 

    /**
     * Loop Every Hour
     * 
     * @param callable $callback
     * @param bool     $firstTrigger = true
     */
    public static function loopEveryHour(callable $callback, bool $firstTrigger = true)
    {
        $check = false;

        while( true )
        {
            if( ! $check )
            {
                $minute = (int) date('i');
                $second = (int) date('s');
    
                $remaining = ((60 - $minute) * 60) - $second;
            }
            else
            {
                $remaining = 3600;
            }

            if( $firstTrigger === true )
            {
                $callback();
            }
            
            sleep($remaining);

            if( $firstTrigger === false )
            {
                $callback();
            }

            $check = true;
        }
    }

    /**
     * Loop Every Minute
     * 
     * @param callable $callback
     * @param bool     $firstTrigger = true
     */
    public static function loopEveryMinute(callable $callback, bool $firstTrigger = true)
    {
        $check = false;

        while( true )
        {
            if( ! $check )
            {
                $second = (int) date('s');
    
                $remaining = 60 - $second;
            }
            else
            {
                $remaining = 60;
            }

            if( $firstTrigger === true )
            {
                $callback();
            }
            
            sleep($remaining);

            if( $firstTrigger === false )
            {
                $callback();
            }

            $check = true;
        }
    }

    /**
     * Is Run
     * 
     * @param string $procId = current-process
     * 
     * @return bool
     */
    public static function isRun(string $procId = '') : bool
    {
        if( $pid = self::getData($procId)['status']['pid'] ?? NULL )
        {
            if( stripos(php_uname('s'), 'win') > -1 ) 
            {
                $output = [];
    
                exec("tasklist /FI \"PID eq $pid\"", $output);
                
                foreach( $output as $line) 
                {
                    if( strpos($line, (string)$pid) !== false ) 
                    {
                        return true;
                    }
                }
            } 
            else 
            {
                exec("ps -p $pid", $output);
                
                if( count($output) > 1 ) 
                {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * protected get proc file
     */
    protected static function getProcFile(string $procId = '')
    {
        return $procId ? Base::prefix($procId, self::$procDir) : self::$procId;
    }

    /**
     * Returns the bare proc key (the proc directory prefix stripped off),
     * used as the row identifier ('procKey' column) by the 'db' driver.
     *
     * Deliberately procDir-independent: $procId may be read back in a
     * separate CLI process whose self::$procDir default never matches
     * the web process's configured value, so a literal
     * str_replace(self::$procDir, ...) is not reliable across that
     * boundary. The key run() ever produces (uniqid(), or $name with
     * \W stripped) never contains a path separator, so taking the
     * final path segment is equivalent whenever procDir does match,
     * and correct even when it doesn't.
     *
     * @param string $procId = current-process
     *
     * @return string
     */
    protected static function getProcKey(string $procId = '') : string
    {
        $procId = $procId ?: self::$procId;

        return basename(str_replace('\\', '/', $procId));
    }

    /**
     * Prepends DB_PROC_PREFIX when the 'db' driver is active, otherwise
     * returns $uniq unchanged. Used by run() to build a new job's key.
     *
     * @param string $uniq
     *
     * @return string
     */
    protected static function applyDriverPrefix(string $uniq) : string
    {
        return self::$driver === 'db' ? self::DB_PROC_PREFIX . $uniq : $uniq;
    }

    /**
     * Resolves $procId to the exact procKey a 'db'-driver job is stored
     * under, automatically — so getData()/remove() (and, built on top of
     * them, close()/isRun()) match a caller's bare, independently
     * recomputed name (e.g. a cron command row's name + branch) without
     * that caller having to pre-format it itself. Idempotent on a key
     * that already carries the 'db__' prefix (e.g. one derived from
     * run()'s own return value), so it never double-prefixes.
     *
     * Deliberately only used by getData() and remove(): isExists(),
     * getError(), socket() and status() key on a '-error'/'-output'
     * suffixed form of this same value, and preg_replace('/\W+/', ...)
     * would strip that suffix's hyphen — those keep using getProcKey()
     * directly, unchanged.
     *
     * @param string $procId
     *
     * @return string
     */
    protected static function dbKey(string $procId) : string
    {
        $key = preg_replace('/\W+/', '', self::getProcKey($procId));

        return strpos($key, self::DB_PROC_PREFIX) === 0 ? $key : self::DB_PROC_PREFIX . $key;
    }

    /**
     * Writes proc data through the active driver: a JSON file for 'file',
     * an insert/update against the proc table for 'db'.
     *
     * @param string $key
     * @param array  $data
     *
     * @return void
     */
    protected static function putData(string $key, array $data) : void
    {
        if( self::$driver === 'db' )
        {
            $json = self::jsonForSqlStorage($data);

            // duplicateCheckUpdate('procKey')->insert(...) is internally the
            // exact same check-then-update-or-insert sequence this method
            // used to spell out by hand (see DB::duplicateCheckProcess()) —
            // kept here for codebase-convention consistency, not because it
            // changes behavior or atomicity.
            DB::duplicateCheckUpdate('procKey')->insert(self::$table, ['procKey' => $key, 'data' => $json]);

            return;
        }

        file_put_contents(self::$procDir . $key, Json::encode($data));
    }

    /**
     * Encodes $data for the 'db' driver's raw-SQL insert/update path.
     *
     * ZN's query builder (Connection::nailEncode()) only neutralizes
     * single quotes (it rewrites ' to &#39;) before splicing a value into
     * the SQL text it sends to the driver; it never escapes backslashes.
     * MySQL's own string-literal parser *does* treat backslash as an
     * escape character though: '\\' collapses to a single '\', and '\X'
     * for any other X silently drops the backslash and keeps just X.
     * json_encode() (via Json::encode()) emits exactly these sequences —
     * every '/' becomes '\/', and so on — so without this step MySQL
     * silently eats a backslash out of the stored text for each one,
     * corrupting the JSON (decodeArray() then gets a NULL back from
     * json_decode() and casts it to an empty array, i.e. a job's own
     * callable receives $data = [] with no error of any kind).
     * Doubling every backslash here cancels that collapse out exactly,
     * so what MySQL ends up storing is byte-for-byte Json::encode()'s
     * original output.
     *
     * @param array $data
     *
     * @return string
     */
    protected static function jsonForSqlStorage(array $data) : string
    {
        return str_replace('\\', '\\\\', Json::encode($data));
    }

    /**
     * Returns the application's configured database driver name
     * (mysqli, postgres, sqlite, sqlserver, oracle, odbc).
     *
     * @return string
     */
    protected static function getDbDriver() : string
    {
        return Config::get('Database', 'database')['driver'] ?? 'mysqli';
    }

    /**
     * Returns the 'db' driver's expected table columns, adjusted for the
     * active database driver's identifier case folding (PostgreSQL lowercases
     * unquoted identifiers, Oracle uppercases them; the other supported
     * drivers preserve the declared case, so no adjustment is needed for
     * them).
     *
     * @return array
     */
    protected static function getExpectedColumns() : array
    {
        $columns = ['ID', 'procKey', 'data', 'addDate'];

        switch( self::getDbDriver() )
        {
            case 'postgres':

                return array_map('strtolower', $columns);

            case 'oracle':

                return array_map('strtoupper', $columns);

            default:

                return $columns;
        }
    }

    /**
     * Reads the given table's existing column names using the query syntax
     * of the active database driver.
     *
     * @param string $table
     *
     * @return array
     */
    protected static function getExistingColumns(string $table) : array
    {
        switch( self::getDbDriver() )
        {
            case 'postgres':

                $rows = DB::query("SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "' ORDER BY ordinal_position")->resultArray();

                return array_column($rows, 'column_name');

            case 'sqlite':

                $rows = DB::query('PRAGMA table_info(' . $table . ')')->resultArray();

                return array_column($rows, 'name');

            case 'sqlserver':

                $rows = DB::query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $table . "' ORDER BY ORDINAL_POSITION")->resultArray();

                return array_column($rows, 'COLUMN_NAME');

            case 'oracle':

                // Identifiers are unquoted in createTableSql(), so Oracle
                // stores (and reports back) the table name in upper case.
                $rows = DB::query("SELECT COLUMN_NAME FROM USER_TAB_COLUMNS WHERE TABLE_NAME = '" . strtoupper($table) . "' ORDER BY COLUMN_ID")->resultArray();

                return array_column($rows, 'COLUMN_NAME');

            case 'odbc':

                // MS Access (ZN's odbc driver) has no SQL-queryable catalog
                // (no information_schema, no portable system table), so an
                // existing table's columns can not actually be read back
                // here. Expected columns are returned as-is, which skips the
                // structure-mismatch check for this driver only; tableExists()
                // is what guards table creation for odbc.
                return self::getExpectedColumns();

            default: // mysqli

                Support::driver(['mysqli', 'postgres', 'sqlite', 'sqlserver', 'oracle', 'odbc'], self::getDbDriver());

                $rows = DB::query('SHOW COLUMNS FROM `' . $table . '`')->resultArray();

                return array_column($rows, 'Field');
        }
    }

    /**
     * Returns the CREATE TABLE statement for the 'db' driver's proc table,
     * written in the syntax of the active database driver.
     *
     * @param string $table
     *
     * @return string
     */
    protected static function createTableSql(string $table) : string
    {
        switch( self::getDbDriver() )
        {
            case 'postgres':

                return 'CREATE TABLE ' . $table . ' (
                    ID SERIAL PRIMARY KEY,
                    procKey VARCHAR(191) NOT NULL UNIQUE,
                    data TEXT NULL,
                    addDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                )';

            case 'sqlite':

                return 'CREATE TABLE "' . $table . '" (
                    "ID" INTEGER PRIMARY KEY AUTOINCREMENT,
                    "procKey" VARCHAR(191) NOT NULL UNIQUE,
                    "data" TEXT,
                    "addDate" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                )';

            case 'sqlserver':

                return 'CREATE TABLE [' . $table . '] (
                    [ID] INT IDENTITY(1,1) PRIMARY KEY,
                    [procKey] VARCHAR(191) NOT NULL UNIQUE,
                    [data] NVARCHAR(MAX) NULL,
                    [addDate] DATETIME NOT NULL DEFAULT GETDATE()
                )';

            case 'oracle':

                // Requires Oracle 12c or newer (GENERATED ... AS IDENTITY).
                // Identifiers are left unquoted on purpose, see getExistingColumns().
                return 'CREATE TABLE ' . $table . ' (
                    ID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
                    procKey VARCHAR2(191) NOT NULL UNIQUE,
                    data CLOB NULL,
                    addDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
                )';

            case 'odbc':

                // Identifiers bracket-quoted as MS Access expects; AUTOINCREMENT
                // is its own standalone type here, not a modifier on INTEGER.
                return 'CREATE TABLE [' . $table . '] (
                    [ID] AUTOINCREMENT PRIMARY KEY,
                    [procKey] VARCHAR(191) NOT NULL UNIQUE,
                    [data] MEMO NULL,
                    [addDate] DATETIME NOT NULL DEFAULT Now()
                )';

            default: // mysqli

                Support::driver(['mysqli', 'postgres', 'sqlite', 'sqlserver', 'oracle', 'odbc'], self::getDbDriver());

                // The table's charset/collation must match the app's actual
                // connection charset (Config/Database.php), not a hardcoded
                // value: a mismatch (e.g. table in utf8mb4 while the
                // connection negotiates plain utf8) makes MySQL silently
                // corrupt/truncate any stored value containing a multi-byte
                // character the connection's charset can't represent.
                $connection = Config::get('Database', 'database');
                $charset    = $connection['charset']    ?? 'utf8';
                $collation  = $connection['collation']   ?? ($charset . '_general_ci');

                return 'CREATE TABLE `' . $table . '` (
                    `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `procKey` VARCHAR(191) NOT NULL,
                    `data` LONGTEXT NULL,
                    `addDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`ID`),
                    UNIQUE KEY `procKey` (`procKey`)
                ) ENGINE=InnoDB DEFAULT CHARSET=' . $charset . ' COLLATE=' . $collation;
        }
    }

    /**
     * Creates the 'db' driver's proc table in the application's currently
     * connected database if it does not exist yet, using the active database
     * driver's own SQL syntax. If a table with the same name already exists
     * with a different column structure, an exception is thrown instead of
     * silently reusing it, so a custom table name can be supplied via
     * Async::driver('db', 'CustomTableName'). Exception: for odbc (MS
     * Access), this structure check can not be performed (see
     * getExistingColumns()), so an existing table with the configured name
     * is always assumed to be compatible.
     *
     * @return void
     */
    protected static function prepareTable() : void
    {
        if( self::$tablePrepared )
        {
            return;
        }

        Support::driver(['mysqli', 'postgres', 'sqlite', 'sqlserver', 'oracle', 'odbc'], self::getDbDriver());

        $table = Properties::$prefix . self::$table;

        if( self::tableExists($table) )
        {
            if( self::getExistingColumns($table) !== self::getExpectedColumns() )
            {
                throw new \RuntimeException(Lang::default('ZN\Console\ConsoleDefaultLanguage')::select('Console', 'asyncTableStructureMismatch', self::$table));
            }
        }
        else
        {
            try
            {
                DB::execQuery(self::createTableSql($table));
            }
            catch( Throwable $e )
            {
                // Another concurrent request may have created the table in the
                // meantime; only re-throw if it still does not exist.
                if( ! self::tableExists($table) )
                {
                    throw $e;
                }
            }
        }

        self::$tablePrepared = true;
    }

    /**
     * Checks whether the given table already exists, using the active
     * database driver's own catalog. DBTool::listTables() is not used
     * directly for oracle/odbc because its generic fallback ('SHOW TABLES')
     * is mysqli-only syntax, and neither driver has an override for it in
     * ZN, so it would error out against them:
     * - Oracle has a real queryable catalog (USER_TABLES), used directly.
     * - MS Access (odbc) has no SQL-queryable catalog at all, so a
     *   lightweight read of the table itself is attempted instead; an
     *   error means the table does not exist yet.
     *
     * @param string $table
     *
     * @return bool
     */
    protected static function tableExists(string $table) : bool
    {
        switch( self::getDbDriver() )
        {
            case 'oracle':

                $rows = DB::query("SELECT TABLE_NAME FROM USER_TABLES WHERE TABLE_NAME = '" . strtoupper($table) . "'")->resultArray();

                return ! empty($rows);

            case 'odbc':

                try
                {
                    DB::query('SELECT COUNT(*) FROM [' . $table . ']');

                    return true;
                }
                catch( Throwable $e )
                {
                    return false;
                }

            default:

                return in_array($table, DBTool::listTables());
        }
    }

    /**
     * protected close process
     */
    protected static function closeProcess($pid)
    {
        return stripos(php_uname('s'), 'win') > -1 ? exec("taskkill /F /T /PID $pid") : exec("kill -9 $pid");
    }
}
