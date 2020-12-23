<?php
	namespace Application\Classes;

	class Markdown
	{
		private $markdown;
		private $currentModifiers;
		private static $colorReplace = [
			'<font style="color:red">',
			'</font>'
		];
		public function __construct($markdown)
		{
			$this->markdown = $markdown;
		}
		public function parse()
		{
			$this->appendStyles();
			$this->parseCustomFontColor();
			$this->parseCustomCode();
			// echo "<pre>";
			// show_source($this->markdown);
			// echo "</pre>";
			// die();
			return $this->markdown;
		}

		public function parseCustomFontColor()
		{
			/*
			|	parse `text` from markdown
			|
			*/
			$this->currentModifiers = ['`'];
			$data = Extractor::find($this->markdown, $this->currentModifiers[0]);
			
			if($data)
				$this->iterateAndReplace($data, static::$colorReplace);

			$this->currentModifiers = null;
		}	

		public function parseCustomCode()
		{
			/*
			|	In code is enough to write <code>Your code</code>
			|	for code highlight
			|
			*/
			$this->currentModifiers = ['<code>'];
			$data = Extractor::find($this->markdown, $this->currentModifiers[0]);
			$this->markdown = str_replace($this->currentModifiers, static::getCodeReplacers()[0],$this->markdown);

			$this->currentModifiers = ['</code>'];
			$this->markdown = str_replace($this->currentModifiers, static::getCodeReplacers()[1],$this->markdown);

			$this->currentModifiers = null;
		}

		public function iterateAndReplace($data, $replacer)
		{
			if(!$data)
				return;

			$startsWith = $this->currentModifiers[0];
			$endsWith = $this->currentModifiers[0];
			if(array_key_exists(2, $this->currentModifiers) && isset($this->currentModifiers[2]))
				$endsWith = $this->currentModifiers[2];

			$replacerStart = $replacer[0];
			$replacerEnd = $replacer[0];
			if(isset($replacer[1]))
				$replacerEnd = $replacer[1];

			foreach($data as $match) {
				$full_match = $startsWith . $match . $endsWith;
				$full_replace = $replacerStart . $match . $replacerEnd;

				$this->markdown = str_replace($full_match, $full_replace, $this->markdown);

			}
		}

		public function encodeData(&$data)
		{
			$this->encodeSpace($data);
			$this->encodeNewLine($data);
			$this->encodeTab($data);
		}

		public function decodeData(&$data)
		{
			$this->decodeSpace($data);
			$this->decodeNewLine($data);
			$this->decodeTab($data);
		}

		public function encodeSpace(&$data)
		{
			$data = str_replace(" ", "\u00fc\u00be", $data);
		}

		public function decodeSpace(&$data)
		{
			$data = str_replace("\u00fc\u00be"," ", $data);
		}

		public function encodeNewLine(&$data)
		{
			$data = str_replace("\n","\u008c\u00a3",$data);
		}

		public function decodeNewLine(&$data)
		{
			$data = str_replace("\u008c\u00a3", "\n", $data);
		}

		public function encodeTab(&$data)
		{
			$data = str_replace("\t", "\u00a4\u00bc", $data);
		}

		public function decodeTab(&$data)
		{
			$data = str_replace("\u00a4\u00bc", "\t", $data);
		}

		public function getCodeReplacers()
		{
			$first = "
				<pre class=\"mvc-code\">
					<code class=\"language-php hljs\" style=\"font-size: +13;\">
			";
			$second = "
					</code>
				</pre>
			";
			return [trim($first),trim($second)];
		}

		public static function appendScripts()
		{
			return '
				<script type="text/javascript" src="/js/highlight.min.js"></script>
				<script>hljs.initHighlightingOnLoad();</script>
			';
		}

		public function appendStyles()
		{
			$stylesSrc = $this->loadStyles();

			if(!count($stylesSrc))
				return;

			$stylesStr = '';
			foreach($stylesSrc as $src) 
			{
				/*
					Not allowed external links here
				*/
				if($src[0] != '/')
					$src = '/' . $src;

				$styleStr = '<link rel="stylesheet"  type="text/css" href="' . $src . '">';
				$styleStr .= "\n";
				$stylesStr .= $styleStr;
			}
			if($this->markdown == '')
				$this->markdown = $stylesStr;
			else $this->markdown = $stylesStr . $this->markdown;
			// $this->showHTML();
		}

		public function loadStyles()
		{
			return [
				'styles/index.css',
				'styles/dracula.css',
			];
		}

		public function showHTML($hmtl = '')
		{
			if($html == '')
				$html = $this->markdown;

			$html .= '<?php echo "tjt";?>';

			$str = str_replace("<", "&lt", $html);
			$str = str_replace(">", "&gt", $str);

			echo "<pre>";
			echo $str;
			echo "</pre>";
			die();
		}
	}

?>