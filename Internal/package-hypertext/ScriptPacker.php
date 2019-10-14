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

class ScriptPacker 
{
    /**
     * Protected $script
     * 
     * @var string
     */
    protected $script = '';
    
    /**
     * Protected $encoding
     * 
     * @var int
     */
    protected $encoding = 62;
    
    /**
     * Protected $fastDecode
     * 
     * @var bool
     */
    protected $fastDecode = true;
    
    /**
     * Protected $specialChars
     * 
     * @var bool
     */
    protected $specialChars = false;

    /**
     * Protected $parsers
     * 
     * @var array
     */
    protected $parsers = [];

    /**
     * Protected $count
     * 
     * @var array
     */
    protected $count = [];

    /**
     * Protected $buffer
     * 
     * @var string
     */
    protected $buffer;	

    /**
     * Protected $literalEncoding
     * 
     * @var array
     */
    protected $literalEncoding =
    [
		'none'       => 0,
		'numeric'    => 10,
		'normal'     => 62,
		'ascii'      => 95
    ];
    
    /**
     * Magic method constructor
     * 
     * @param string $script
     * @param string $encoding     = 'normal' - [none|numeric|normal|ascii]
     * @param bool   $fastDecode   = true
     * @param bool   $specialChars = false
     */
	public function __construct(String $script, String $encoding = 'normal', Bool $fastDecode = true, Bool $specialChars = false)
	{
        $this->script = $script . "\n";
        
        if( array_key_exists($encoding, $this->literalEncoding) )
        {
            $encoding = $this->literalEncoding[$encoding];
        }
        
		$this->encoding     = min((int)$encoding, 95);
		$this->fastDecode   = $fastDecode;	
		$this->specialChars = $specialChars;
    }
    
    /**
     * Pack
     * 
     * @return string
     */
    public function pack() 
    {
        $this->addParser('basicCompression');
        
        if( $this->specialChars )
        {
            $this->addParser('encodeSpecialChars');
        }
			
        if( $this->encoding )
        {
            $this->addParser('encodeKeywords');
        }

		return $this->packing($this->script);
	}
    
    /**
     * Protected packing
     */
    protected function packing($script) 
    {
        for( $i = 0; isset($this->parsers[$i]); $i++ ) 
        {
			$script = call_user_func(array(&$this,$this->parsers[$i]), $script);
        }
        
		return $script;
	}
    
    /**
     * Protected add parser
     */
    protected function addParser($parser) 
    {
		$this->parsers[] = $parser;
	}
	
	/**
     * Protected basic compression
     * 
     * zero encoding - just removal of white space and comments
     */
    protected function basicCompression($script) 
    {
		$parser = new ScriptParser();
        
        # make safe
		$parser->escapeChar = '\\';
        
        # protect strings
		$parser->add('/\'[^\'\\n\\r]*\'/', '$1');
		$parser->add('/"[^"\\n\\r]*"/', '$1');
        
        # remove comments
		$parser->add('/\\/\\/[^\\n\\r]*[\\n\\r]/', ' ');
		$parser->add('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', ' ');
        
        # protect regular expressions
		$parser->add('/\\s+(\\/[^\\/\\n\\r\\*][^\\/\\n\\r]*\\/g?i?)/', '$2'); // IGNORE
		$parser->add('/[^\\w\\x24\\/\'"*)\\?:]\\/[^\\/\\n\\r\\*][^\\/\\n\\r]*\\/g?i?/', '$1');
        
        # remove: ;;; doSomething();
        if( $this->specialChars ) 
        {
            $parser->add('/;;;[^\\n\\r]+[\\n\\r]/');
        }

        # remove redundant semi-colons
		$parser->add('/\\(;;\\)/', '$1'); # protect for (;;) loops
        $parser->add('/;+\\s*([};])/', '$2');
        
		# apply the above
		$script = $parser->exec($script);

		# remove white-space
		$parser->add('/(\\b|\\x24)\\s+(\\b|\\x24)/', '$2 $3');
		$parser->add('/([+\\-])\\s+([+\\-])/', '$2 $3');
        $parser->add('/\\s+/', '');
        
		return $parser->exec($script);
	}
    
    /**
     * Protected encode special chars
     */
    protected function encodeSpecialChars($script) 
    {
		$parser = new ScriptParser();
        
        # replace: $name -> n, $$name -> na
        $parser->add('/((\\x24+)([a-zA-Z$_]+))(\\d*)/', ['fn' => 'replaceName']);
        
		# replace: _name -> _0, double-underscore (__name) is ignored
		$regexp = '/\\b_[A-Za-z\\d]\\w*/';
        
        # build the word list
		$keywords = $this->analyze($script, $regexp, 'encodePrivate');
        
        # quick ref
		$encoded = $keywords['encoded'];
        
        # encode
        $parser->add($regexp, ['fn' => 'replaceEncoded', 'data' => $encoded]);
        
		return $parser->exec($script);
	}
    
    /**
     * Protected encode keywords
     */
    protected function encodeKeywords($script) 
    {
		# escape high-ascii values already in the script (i.e. in strings)
        if( $this->encoding > 62 )
        {
            $script = $this->escape95($script);
        }
			
		# create the parser
        $parser = new ScriptParser();
        
        $encode = $this->getEncoder($this->encoding);
        
		# for high-ascii, don't encode single character low-ascii
        $regexp = $this->encoding > 62 ? '/\\w\\w+/' : '/\\w+/';
        
		# build the word list
		$keywords = $this->analyze($script, $regexp, $encode);
		$encoded  = $keywords['encoded'];
		
		# encode
        $parser->add($regexp, ['fn' => 'replaceEncoded', 'data' => $encoded]);
        
        if( empty($script) ) 
        {
            return $script;
        }
        else 
        {
			return $this->bootstrap($parser->exec($script), $keywords);
		}
	}
    
    /**
     * Protected analyze
     */
    protected function analyze($script, $regexp, $encode) 
    {
		# analyse
		# retreive all words in the script
        $all = [];
        
        preg_match_all($regexp, $script, $all);
        
		$_sorted    = []; # list of words sorted by frequency
		$_encoded   = []; # dictionary of word->encoding
        $_protected = []; # instances of "protected" words
        
        $all = $all[0]; # simulate the javascript comportement of global match
        
        if( ! empty($all) ) 
        {
			$unsorted  = []; # same list, not sorted
			$protected = []; # "protected" words (dictionary of word->"word")
            $value     = []; # dictionary of charCode->encoding (eg. 256->ff)
            
            $this->count = []; # word->count
            
            $i = count($all); $j = 0; # $word = null;
            
			# count the occurrences - used for sorting later
            do 
            {
				--$i;
                $word = '$' . $all[$i];
                
                if( ! isset($this->count[$word]) ) 
                {
                    $this->count[$word] = 0;
                    
                    $unsorted[$j] = $word;
                    
					# make a dictionary of all of the protected words in this script
					# these are words that might be mistaken for encoding
                    # if (is_string($encode) && method_exists($this, $encode) )
                    
                    $values[$j] = call_user_func([&$this, $encode], $j);
                    
					$protected['$' . $values[$j]] = $j++;
				}
                
                # increment the word counter
                $this->count[$word]++;
                
            } while ($i > 0);
            
			# prepare to sort the word list, first we must protect
			# words that are also used as codes. we assign them a code
			# equivalent to the word itself.
			# e.g. if "do" falls within our encoding range
			# then we store keywords["do"] = "do";
			# this avoids problems when decoding
            $i = count($unsorted);
            
            do 
            {
                $word = $unsorted[--$i];
                
                if( isset($protected[$word]) ) 
                {
					$_sorted[$protected[$word]]     = substr($word, 1);
                    $_protected[$protected[$word]]  = true;
                    
					$this->count[$word]  = 0;
                }
                
			} while( $i );
			
			# sort the words by frequency
			# Note: the javascript and php version of sort can be different :
			# in php manual, usort :
			# " If two members compare as equal,
			# their order in the sorted array is undefined."
			# so the final packed script is different of the Dean's javascript version
			# but equivalent.
			# the ECMAscript standard does not guarantee this behaviour,
			# and thus not all browsers (e.g. Mozilla versions dating back to at
			# least 2003) respect this. 
            usort($unsorted, [&$this, 'sortWords']);
            
            $j = 0;
            
			# because there are "protected" words in the list
			# we must add the sorted words around them
            do 
            {
                if( ! isset($_sorted[$i]) )
                {
                    $_sorted[$i] = substr($unsorted[$j++], 1);
                }
					
                $_encoded[$_sorted[$i]] = $values[$i];
                
			} while( ++$i < count($unsorted) );
        }
        
        return 
        [
            'sorted'    => $_sorted,
			'encoded'   => $_encoded,
			'protected' => $_protected
        ];
	}
	
	/**
     * Protected sort words
     */
    protected function sortWords($match1, $match2) 
    {
		return $this->count[$match2] - $this->count[$match1];
	}
	
	/**
     * Protected bootstrap
     * 
     * build the boot function used for loading and decoding
     */
    protected function bootstrap($packed, $keywords) 
    {
		$ENCODE = $this->safeRegExp('$encode\\($count\\)');

		# $packed: the packed script
		$packed = "'" . $this->escape($packed) . "'";

		# $ascii: base for encoding
        $ascii = min(count($keywords['sorted']), $this->encoding);
        
        if( $ascii === 0 ) 
        {
            $ascii = 1;
        }

		# $count: number of words contained in the script
		$count = count($keywords['sorted']);

		# $keywords: list of words contained in the script
        foreach( $keywords['protected'] as $i => $value ) 
        {
			$keywords['sorted'][$i] = '';
        }
        
		# convert from a string to an array
        ksort($keywords['sorted']);
        
		$keywords = "'" . implode('|',$keywords['sorted']) . "'.split('|')";

		$encode = ($this->encoding > 62) ? 'encode95' : $this->getEncoder($ascii);
		$encode = $this->getJSFunction($encode);
		$encode = preg_replace('/_encoding/','$ascii', $encode);
		$encode = preg_replace('/arguments\\.callee/','$encode', $encode);
		$inline = '\\$count' . ($ascii > 10 ? '.toString(\\$ascii)' : '');

		# $decode: code snippet to speed up decoding
        if( $this->fastDecode ) 
        {
			# create the decoder
            $decode = $this->getJSFunction('_decodeBody');
            
            if( $this->encoding > 62 )
            {
                $decode = preg_replace('/\\\\w/', '[\\xa1-\\xff]', $decode);
            }	
			# perform the encoding inline for lower ascii values
            elseif( $ascii < 36 )
            {
                $decode = preg_replace($ENCODE, $inline, $decode);
            }
				
			# special case: when $count==0 there are no keywords. I want to keep
			# the basic shape of the unpacking funcion so i'll frig the code...
            if( $count === 0 )
            {
                $decode = preg_replace($this->safeRegExp('($count)\\s*=\\s*1'), '$1=0', $decode, 1);
            }		
		}

		# boot function
        $unpack = $this->getJSFunction('_unpack');
        
        if( $this->fastDecode ) 
        {
			# insert the decoder
            $this->buffer = $decode;
            
			$unpack = preg_replace_callback('/\\{/', [&$this, 'insertFastDecode'], $unpack, 1);
        }
        
        $unpack = preg_replace('/"/', "'", $unpack);
        
        if( $this->encoding > 62 ) 
        { 
            # high-ascii
			# get rid of the word-boundaries for regexp matches
			$unpack = preg_replace('/\'\\\\\\\\b\'\s*\\+|\\+\s*\'\\\\\\\\b\'/', '', $unpack);
        }
        
        if( $ascii > 36 || $this->encoding > 62 || $this->fastDecode ) 
        {
			# insert the encode function
			$this->buffer = $encode;
			$unpack = preg_replace_callback('/\\{/', [&$this, 'insertFastEncode'], $unpack, 1);
        } 
        else 
        {
			# perform the encoding inline
			$unpack = preg_replace($ENCODE, $inline, $unpack);
        }
        
		# pack the boot function too
		$unpackPacker = new ScriptPacker($unpack, 0, false, true);
		$unpack = $unpackPacker->pack();
		
		# arguments
        $params = [$packed, $ascii, $count, $keywords];
        
        if( $this->fastDecode ) 
        {
			$params[] = 0;
			$params[] = '{}';
        }
        
		$params = implode(',', $params);
		
		# the whole thing
		return 'eval(' . $unpack . '(' . $params . "))\n";
	}
	
    /**
     * Protected insert fast decode
     */
    protected function insertFastDecode($match) 
    {
		return '{' . $this->buffer . ';';
    }
    
    /**
     * Protected insert fast encode
     */
    protected function insertFastEncode($match) 
    {
		return '{$encode=' . $this->buffer . ';';
	}
	
	/**
     * Protected get encoder
     */
    protected function getEncoder($ascii) 
    {
        return $ascii > 10 ? 
               $ascii > 36 ?
               $ascii > 62 ? 'encode95' : 'encode62' : 'encode36' : 'encode10';
	}
	
	/**
     * Protected encode 10
     * 
     * zero encoding
	 * characters: 0123456789
     */
    protected function encode10($charCode) 
    {
		return $charCode;
	}
    
    /**
     * Protected encode 36
     * 
     * inherent base36 support
     * characters: 0123456789abcdefghijklmnopqrstuvwxyz
     * 
     */
    protected function encode36($charCode) 
    {
		return base_convert($charCode, 10, 36);
	}
    
    /**
     * Protected encode 62
     * 
     * hitch a ride on base36 and add the upper case alpha characters
     * characters: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
     * 
     */
    protected function encode62($charCode) 
    {
        $res = '';
        
        if( $charCode >= $this->encoding ) 
        {
			$res = $this->encode62((int)($charCode / $this->encoding));
        }
        
		$charCode = $charCode % $this->encoding;
		
        if( $charCode > 35 )
        {
            return $res . chr($charCode + 29);
        }	
        else
        {
            return $res . base_convert($charCode, 10, 36);
        }		
	}
    
    /**
     * Protected encode 95
     * 
     * use high-ascii values
     * characters: ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþ
     */
    protected function encode95($charCode) 
    {
        $res = '';
        
        if( $charCode >= $this->encoding )
        {
            $res = $this->encode95($charCode / $this->encoding);
        }
			
		return $res . chr(($charCode % $this->encoding) + 161);
	}
    
    /**
     * Protected safe regex expressions
     */
    protected function safeRegExp($string) 
    {
		return '/'.preg_replace('/\$/', '\\\$', $string).'/';
	}
    
    /**
     * Protected encode private
     */
    protected function encodePrivate($charCode) 
    {
		return "_" . $charCode;
	}
	
	/**
     * Protected escape
     * 
     * protect characters used by the parser
     */
    protected function escape($script) 
    {
		return preg_replace('/([\\\\\'])/', '\\\$1', $script);
	}
	
	/**
     * Protected escape95
     * 
     * protect high-ascii characters already in the script
     */
    protected function escape95($script) 
    {
        return preg_replace_callback
        (
			'/[\\xa1-\\xff]/',
			[&$this, 'escape95Bis'],
			$script
		);
    }
    
    /**
     * Protected escape 95 bis
     */
    protected function escape95Bis($match) 
    {
		return '\x'.((string)dechex(ord($match)));
	}
	
	/**
     * Protected get javascdript function
     */
    protected function getJSFunction($aName) 
    {
        if( defined($jsFunction = 'self::JSFUNCTION' . $aName) )
        {
            return constant($jsFunction);
        }	
		
		return '';
	}
	
	const JSFUNCTION_unpack =
'function($packed, $ascii, $count, $keywords, $encode, $decode) {
    while ($count--) {
        if ($keywords[$count]) {
            $packed = $packed.replace(new RegExp(\'\\\\b\' + $encode($count) + \'\\\\b\', \'g\'), $keywords[$count]);
        }
    }
    return $packed;
}';

	const JSFUNCTION_decodeBody =
'    if (!\'\'.replace(/^/, String)) {
        // decode all the values we need
        while ($count--) {
            $decode[$encode($count)] = $keywords[$count] || $encode($count);
        }
        // global replacement function
        $keywords = [function ($encoded) {return $decode[$encoded]}];
        // generic match
        $encode = function () {return \'\\\\w+\'};
        // reset the loop counter -  we are now doing a global replace
        $count = 1;
    }
';

	 const JSFUNCTIONencode10 =
'function($charCode) {
    return $charCode;
}';

	 const JSFUNCTIONencode36 =
'function($charCode) {
    return $charCode.toString(36);
}';

	const JSFUNCTIONencode62 =
'function($charCode) {
    return ($charCode < _encoding ? \'\' : arguments.callee(parseInt($charCode / _encoding))) +
    (($charCode = $charCode % _encoding) > 35 ? String.fromCharCode($charCode + 29) : $charCode.toString(36));
}';
	
	const JSFUNCTIONencode95 =
'function($charCode) {
    return ($charCode < _encoding ? \'\' : arguments.callee($charCode / _encoding)) +
        String.fromCharCode($charCode % _encoding + 161);
}'; 
	
}