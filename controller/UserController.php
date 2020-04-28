<?php
	class UserController extends Controller
	{

		public function loadAll(Request $r, $all, $pera)
		{
			return $all;
		}

		public function home(Request $r)
		{
			pr($r->get('sdgsg'));
		}
	}
?>