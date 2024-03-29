<?php 

use Michelf\Markdown;
// vd(get_included_files());
use Application\Classes\Markdown as CustomMarkdown;
// use \Application\View;
	class Controller extends \View
	{
		protected $markdown_path;
		protected $html_content;
		public function loadMarkdown($path = '')
		{
			if(!empty($path)) {
				$this->markdown_path = $path;
			}
			$this->markdown_path = str_replace('.', '/', $this->markdown_path);
			$markdown_path = ROOT . DS . $this->markdown_path;

			// vd(path($markdown_path . DOT . 'md'));
			if(!is_file($markdown_path . '.md')) {
				require_file('core.framework.views.file_not_found');die();
			}
			$this->markdown = file_get_contents(realpath($markdown_path . '.md'));
			$this->transformMarkdown();
			// vd($_SERVER['DOCUMENT_ROOT']);
			// $this->markdown = include realpath($markdown_path . '.md');
		}

		protected function transformMarkdown()
		{
			$customMarkdown = new CustomMarkdown($this->markdown);
			$this->markdown = $customMarkdown->parse();
			//$this->markdown = str_replace("\t", "", $this->markdown);
		}

		protected function returnMarkdown()
		{
			$html_content = $this->html_content ?? '';
			$html_content .= Markdown::defaultTransform($this->markdown);
			$html_content .= CustomMarkdown::appendScripts();
			// vd(headers_list());
			// ob_get_clean();
			if(!headers_sent())
				header('Content-Type', 'text/markdown');
			echo $html_content;
			die();
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
		public function index()
		{
			dd('Tu si ticoo');
			// Your code goes here
		}

	}
?>