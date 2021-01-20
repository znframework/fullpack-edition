<?php
function specialWord($data = '')
{
	$data = preg_replace('/\[\|\]/', '\\', $data);
	
	$data = preg_replace_callback('/\<pre\>\s*\<code\>(?<content>.*?)\<\/code\>\s*\<\/pre\>/s', function($data)
    {
        return '<pre><code>' . str_replace(['[|', '|]'], ['&lt;', '&gt;'], $data['content']) . '</code></pre>';
	}, $data);
	
	$data = preg_replace
	(
		[
			'/(\[ADDED\])/',
			'/(\[CHANGED\])/',
			'/(\[FIXED\])/',
			'/(\[REMOVED\])/'
		], 
		[
			'<span style="color:#4f992b;font-family: consolas,monospace;">$1</span>',
			'<span style="color:#008bb9;font-family: consolas,monospace;">$1</span>',
			'<span style="color:#fcb307;font-family: consolas,monospace;">$1</span>',
			'<span style="color:#c7254e;font-family: consolas,monospace;">$1</span>'
		], 
		$data
	);

	$data = preg_replace_callback('/\[((2|3)[0-9]{3}\-[0-9]{2}\-[0-9]{2})\]/', function($change)
	{
		$date = $change[1];

		$dateStyle = '<span style="color:#008bb9;font-family:">[' . $date . ']</span>';

		if( Date::diffDayDown($date, Date::now()) <= 30 )
		{
			return $dateStyle . ' <span class="label label-danger">new</span>';
		}

		return $dateStyle;
		
	}, $data);

	$data = preg_replace(array('/(v\.[0-9]+\.[0-9]+\.[0-9]+)/i', '/(\#)\s/'), array('<span style="color:#00BFFF">$1</span>', '<span style="color:#00BFFF">$1</span> '), $data);
	$data = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<span style="color:#00BFFF">$1</span>', $data);
	$data = preg_replace(['/\{\[/', '/\]\}/'], ['<span style="color:#fc9b9b">{[</span>', '<span style="color:#fc9b9b">]}</span>'], $data);
	$data = preg_replace('/(@\w+)(\(.*?\))/', '<span style="color:#00bbd0;font-family: consolas,monospace;">$1</span>$2', $data);
	$data = preg_replace('/(@end\w+)/', '<span style="color:#00bbd0;font-family: consolas,monospace;">$1</span>', $data);
	$data = preg_replace('/(@\$\w+\:*)/', '<span style="color:#00bbd0">$1</span>', $data);
	$data = preg_replace('/((\.\.\.)*\$\w+)/', '<span style="font-family: consolas,monospace;color:#fc9b9b">$1</span>', $data);
	$data = preg_replace('/(\s|\>|\(|\[|\{)(@*\w+(\[\|\]\w+){0,})\:\:/', '$1<span style="font-family: consolas,monospace;color:#00bbd0">$2</span><span style="font-family: consolas,monospace;">::</span>', $data);
	$data = preg_replace(['/@php/', '/@endphp/'], ['<span style="color:#fc9b9b">@php</span>', '<span style="color:#fc9b9b">@endphp</span>'], $data);
	$data = preg_replace('/\[\[\s*(.*?)\s*\]\]/', '<span style="color:#c7254e">$1</span>', $data);
    $data = preg_replace('/\[php\]/', '&#60;?php', $data);
	$data = preg_replace('/\[php\-close\]/', '?&#62;', $data);
	$data = preg_replace(['/\{\|/', '/\|\}/'], ['<span style="color:#fc9b9b">{{</span>', '<span style="color:#fc9b9b">}}</span>'], $data);
	$data = preg_replace('/(\s\=\s)([0-9]+|\[\])/i', '$1<span style="font-family: consolas,monospace;color:#e62a5a">$2</span>', $data);
	$data = preg_replace('/(\s|\>|\()((string|int|\$this|this|array|mixed|bool|object|false|float|true|scalar|callable|null|void|SQL|PHP|HTML))\**((\s|\<|\)))/i', '$1<span style="font-family: consolas,monospace;color:#00bbd0">$2</span>$4', $data);
	$data = preg_replace('/(\s|^|\W)(USER_DEPRACATED|RECOVERABLE_ERROR|CORE_ERROR|CORE_WARNING|COMPILE_ERROR|COMPILE_WARNING|USER_ERROR|DEPRECATED|USER_WARNING|USER_NOTICE|STRICT|ERROR|WARNING|PARSE|NOTICE|IFNULL|NULLIF|CURRENT_USER|SESSION_USER|SYSTEM_USER|CONNECTION_ID|SCHEMA|SCHEME|LAST_INSERT_UNIQUE|EXISTS|CONSTRAINT|DEFAULT|LIKE|SERIAL|FOREIGN|SEQUENCE|IDENTITY|BETWEEN|LONG|UTC|INTEGER|BINARY|INT|DOUBLE|PRECISION|VARYING|VARCHAR|TIMESTAMP|SMALL|BIG|TINY|MEDIUM|NUMERIC|DECIMAL|FLOAT|TIME|DATETIME|DATE|TEXT|BLOB|CLOB|BEFORE|FOR|ROW|EACH|AFTER|FOLLOWS|PRECEDES|BEGIN|END|IF|CASE|MYSQL|MYISAM|InnoDB|BDB|CHARACTER|CHAR|COLLATE|REPAIR|OPTIMIZE|GRANT|REVOKE|DESC|ASC|AND|OR|NOT|AUTO|INCREMENT|PRIMARY|KEY|ON|FULL|INSERT|INTO|VALUES|DATABASE|TABLE|ALTER|COLUMN|RENAME|ADD|TO|MODIFY|SET|GET|POST|REQUEST|SESSION|ENV|COOKIE|FILES|SELECT|WHERE|UNION|FROM|HAVING|INNER|JOIN|OUTER|ALL|LEFT|RIGHT|UPDATE|TRIGGER|DELETE|DROP|CREATE|LIMIT|ORDER|BY|GROUP|DISTINCT|SUM|COUNT|MIN|MAX|AVG|ELSE|IN)(\s|$|\W)/', '$1<span style="font-family: consolas,monospace;color:#00bbd0">$2</span>$3', $data);
	
	$data = preg_replace_callback('/([0-9]+(\.[0-9]+){2,})/', function($data)
	{
		return '<span style="color:#00BFFF">'.$data[1].'</span>';
		
	}, $data);
	$data = preg_replace('/([^\w])((\&\#39\;|\&\#34\;).*?(\&\#39\;|\&\#34\;))/', '$1<span style="font-family: consolas,monospace;color:#a9b932">$2</span>', $data);
	$data = preg_replace_callback('/((\w+)\(.*?\))/', function($change)
	{
		if( ! in_array($change[2], ['rgb']) )
		{
			return '<span style="font-family: consolas,monospace;">'.$change[1].'</span>';
		}

		return $change[1];
		
	}, $data);
	$data = preg_replace('/(\s\=\s|(\-|\=)\&gt\;)/', '<span style="font-family: consolas,monospace;">$1</span>', $data);
	$data = preg_replace('/((return))/i', '<span style="font-family: consolas,monospace;">$1</span>', $data);
	$data = preg_replace('/(\s|\>)(\/*\w+\/(\/*\w+\-*(\.\w+)*){0,}\/*)/i', '$1<span style="color:#00bbd0">$2</span>', $data);
	$data = preg_replace('/(\s|\>)((single|custom|fullpack)\-edition)/i', '$1<span style="color:#a9b932">$2</span>', $data);
	$data = preg_replace('/(\s|\>)(znframework)(\s|\<)/i', '$1<span style="color:#a9b932">$2</span>$3', $data);
	$data = preg_replace('/(\s|\>)(package(-\w+){1,})/i', '$1<span style="color:#a9b932">$2</span>', $data);
	$data = preg_replace('/(ZN\s(CE|SE|FE|OE))/i', '<span style="color:#00BFFF">$1</span>', $data);
	$data = preg_replace('/<strong>/', '<strong style="color:#738b9c;">', $data);

	

	$data = str_replace(
		[
			'**'
		], 
		
		[
			'<span style="color:#00BFFF">&#x25cf;</span> '
		], $data);


	$data = preg_replace
	(
		[
			'/\&lt\;(\w+)(.*?)&gt;/',
			'/\&lt\;\/(\w+)&gt;/'
		], 
		[
			'<span style="color:#85d6f7">&lt;$1</span><span style="color:#ffedc2">$2</span><span style="color:#85d6f7">&gt;</span>',
			'<span style="color:#85d6f7">&lt;/$1&gt;</span>'
		], 
		$data
	);

	return $data;
}
