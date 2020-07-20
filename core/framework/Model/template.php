<?php
	return "
		<?php
			require_once __DIR__ . '/./Model.php';
			class %s extends Model
			{
				public function index()
				{
					// Model code goes here
				}
			}
		?>
	";
?>