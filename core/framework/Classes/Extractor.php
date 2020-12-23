<?php
	namespace Application\Classes;

	class Extractor
	{
		public static function find($data, $startsWith, $endsWith='')
		{
			if($endsWith == '')
				$endsWith = $startsWith;

			// // try {
				preg_match_all('/' . $startsWith . '(.*?)' . $endsWith . '/usm', $data, $matches);
			// // } catch (\Exception $e) {
			// // 	vd([$startsWith,$endsWith]);
			// // }
			// 	// vd($data);
			// 	// vd('/' . $startsWith . '(.*)' . $endsWith . '/');
			// vd($data);
				// vd($matches);
			// preg_match_all("/framework(.*)is/", $data, $matches);
			if(isset($matches[1]))
				$matches = $matches[1];

			if(!count($matches))
				return null;
			
			return $matches;


		}
	}

?>