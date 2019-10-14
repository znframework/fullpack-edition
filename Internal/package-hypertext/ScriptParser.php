<?php namespace ZN\Hypertext;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class ScriptParser
{
	/**
	 * Ignore Case
	 * 
	 * @var bool
	 */
	public $ignoreCase = false;

	/**
	 * Escape Character
	 */
	public $escapeChar = '';
	
	/**
	 * EXPRESSION
	 * 
	 * @const int
	 */
	const EXPRESSION = 0;

	/**
	 * REPLACEMENT
	 * 
	 * @const int
	 */
	const REPLACEMENT = 1;

	/**
	 * LENGTH
	 * 
	 * @const int
	 */
	const LENGTH = 2;
	
	/**
	 * Protected groups
	 * 
	 * @var string
	 */
	protected $groups = '/\\(/';//g

	/**
	 * Protected subreplace
	 * 
	 * @var string
	 */
	protected $subreplace = '/\\$\\d/';

	/**
	 * Protected indexed
	 * 
	 * @var string
	 */
	protected $indexed = '/^\\$\\d+$/';

	/**
	 * Protected trim
	 * 
	 * @var string
	 */
	protected $trim = '/([\'"])\\1\\.(.*)\\.\\1\\1$/';

	/**
	 * Protected escape
	 * 
	 * @var string
	 */
	protected $escape = '/\\\./';//g

	/**
	 * Protected quote
	 * 
	 * @var string
	 */
	protected $quote = '/\'/';

	/**
	 * Protected deleted
	 * 
	 * @var string
	 */
	protected $deleted = '/\\x01[^\\x01]*\\x01/';//g
	
	/**
	 * Protected escaped characters
	 * 
	 * @var array
	 */
	protected $escaped = []; 

	/**
	 * Protected patterns stored by index
	 * 
	 * @var array
	 */
	protected $patterns = [];

	/**
	 * Protected buffer
	 * 
	 * @var string
	 */
	protected $buffer;
	
	/**
	 * add
	 * 
	 * @param string $expression
	 * @param string $replacement
	 */
	public function add($expression, $replacement = '') 
	{
		# count the number of sub-expressions
		# add one because each pattern is itself a sub-expression
		$length = 1 + preg_match_all($this->groups, $this->internalEscape((string)$expression), $out);
		
		# treat only strings $replacement
		if( is_string($replacement) ) 
		{
			# does the pattern deal with sub-expressions?
			if( preg_match($this->subreplace, $replacement) ) 
			{
				# a simple lookup? (e.g. "$2")
				if( preg_match($this->indexed, $replacement) ) 
				{
					# store the index (used for fast retrieval of matched strings)
					$replacement = (int)(substr($replacement, 1)) - 1;
				} 
				else 
				{ 
					# a complicated lookup (e.g. "Hello $2 $1")
					# build a function to do the lookup

					$quote = preg_match($this->quote, $this->internalEscape($replacement)) ? '"' : "'";

					$replacement = 
					[
						'fn' 	=> 'backReferences',
						'data' 	=> 
						[
							'replacement' 	=> $replacement,
							'length' 		=> $length,
							'quote'			=> $quote
						]
					];
				}
			}
		}

		# pass the modified arguments
		$this->adding($expression ?: '/^$/', $replacement, $length);
	}
	
	/**
	 * Exec
	 * 
	 * @param string
	 * 
	 * @return string
	 */
	public function exec($string) 
	{
		# execute the global replacement
		$this->escaped = [];
		
		# simulate the _patterns.toSTring of Dean
		$regexp = '/';

		foreach( $this->patterns as $reg ) 
		{
			$regexp .= '(' . substr($reg[self::EXPRESSION], 1, -1) . ')|';
		}

		$regexp = substr($regexp, 0, -1) . '/';
		$regexp.= ($this->ignoreCase) ? 'i' : '';
		
		$string = $this->escape($string, $this->escapeChar);
		$string = preg_replace_callback($regexp, [&$this, 'replacement'], $string);
		$string = $this->unescape($string, $this->escapeChar);
		
		return preg_replace($this->deleted, '', $string);
	}
		
	/**
	 * reset
	 */
	public function reset() 
	{
		# clear the patterns collection so that this object may be re-used
		$this->patterns = [];
	}
	
	/**
	 * Protected adding
	 * 
	 * create and add a new pattern to the patterns collection
	 */
	protected function adding() 
	{
		$arguments = func_get_args();
		$this->patterns[] = $arguments;
	}
	
	/**
	 * Protected replacemenet
	 * 
	 * this is the global replace function (it's quite complicated)
	 */
	protected function replacement($arguments) 
	{
		if( empty($arguments) ) 
		{
			return '';
		}
		
		$i = 1; $j = 0;

		# loop through the patterns
		while( isset($this->patterns[$j]) ) 
		{
			$pattern = $this->patterns[$j++];

			# do we have a result?
			if( isset($arguments[$i]) && ($arguments[$i] != '') ) 
			{
				$replacement = $pattern[self::REPLACEMENT];
				
				if( is_array($replacement) && isset($replacement['fn']) ) 
				{
					if( isset($replacement['data']) ) 
					{
						$this->buffer = $replacement['data'];
					}
					
					return call_user_func([&$this, $replacement['fn']], $arguments, $i);
					
				} 
				elseif( is_int($replacement) ) 
				{
					return $arguments[$replacement + $i];
				
				}

				$delete = ($this->escapeChar == '' || strpos($arguments[$i], $this->escapeChar) === false) ? '' : "\x01" . $arguments[$i] . "\x01";

				return $delete . $replacement;
			
			# skip over references to sub-expressions
			} 
			else 
			{
				$i += $pattern[self::LENGTH];
			}
		}
	}
	
	/**
	 * Protected back references
	 */
	protected function backReferences($match, $offset) 
	{
		$replacement = $this->buffer['replacement'];
		$quote = $this->buffer['quote'];
		$i = $this->buffer['length'];

		while( $i ) 
		{
			$replacement = str_replace('$'.$i--, $match[$offset + $i], $replacement);
		}

		return $replacement;
	}
	
	/**
	 * Protected replace name
	 */
	protected function replaceName($match, $offset)
	{
		$length = strlen($match[$offset + 2]);
		$start  = $length - max($length - strlen($match[$offset + 3]), 0);

		return substr($match[$offset + 1], $start, $length) . $match[$offset + 4];
	}
	
	/**
	 * Protected replace encoded
	 */
	protected function replaceEncoded($match, $offset) 
	{
		return $this->buffer[$match[$offset]];
	}
	
	/**
	 * Protected escape
	 * 
	 * encode escaped characters
	 */
	protected function escape($string, $escapeChar) 
	{
		if( $escapeChar ) 
		{
			$this->buffer = $escapeChar;

			return preg_replace_callback('/\\' . $escapeChar . '(.)' .'/', [&$this, 'escapeBis'], $string);
			
		} 
		
		return $string;
	}

	/**
	 * Protected escape bis
	 */
	protected function escapeBis($match) 
	{
		$this->escaped[] = $match[1];

		return $this->buffer;
	}
	
	/**
	 * Protected unescape
	 * 
	 * decode escaped characters
	 */
	protected function unescape($string, $escapeChar) 
	{
		if( $escapeChar ) 
		{
			$regexp = '/'.'\\'.$escapeChar.'/';
			$this->buffer = ['escapeChar'=> $escapeChar, 'i' => 0];

			return preg_replace_callback($regexp, [&$this, 'unescapeBis'], $string);	
		} 
		
		return $string;
	}

	/**
	 * Protected unescape bis
	 */
	protected function unescapeBis()
	{
		if( isset($this->escaped[$this->buffer['i']]) && $this->escaped[$this->buffer['i']] != '' )
		{
			 $temp = $this->escaped[$this->buffer['i']];
		} 
		else 
		{
			$temp = '';
		}

		$this->buffer['i']++;

		return $this->buffer['escapeChar'] . $temp;
	}
	
	/**
	 * Protected internal escape
	 */
	protected function internalEscape($string)
	{
		return preg_replace($this->escape, '', $string);
	}
}
