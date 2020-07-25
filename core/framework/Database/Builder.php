<?php
	namespace Application\Database;
	require_once __DIR__ . DS . 'Connection' . PHP_EXT;
	trait Builder
	{
		protected $connection;
		private $wheres = [];
		private $select = '*';
		private $isFirstWhere = true;
		// private $table;
		private $string;
		private $query;
		private $fetchType = 'assoc';
		private $bindParams = [];
		private $fetchTypes = [
			'assoc' => \PDO::FETCH_ASSOC,
			'obj' => \PDO::FETCH_OBJ,
			'coll' => 'Collection'
		];
		protected function _construct()
		{
			$this->connection = Connection::connect();
		}
		public function isFirst()
		{
			return count($this->wheres);
		}
		public function where(...$conditions)
		{
			foreach($conditions as $condition) {
				if(!is_array($condition)) {
					if(count($conditions) == 2) {
						$this->doubleParamWhere($conditions);
						break;
					}
					else if(count($conditions) == 3)  {
						$this->tripleParamWhere($conditions);
						break;
					}
				}else {
					if(count($condition) == 2)
						$this->doubleParamWhere($condition);
					else if(count($condition) == 3)
						$this->tripleParamWhere($condition);
				}
			}
			// if(count($conditions) == 2) {
			// 	dd($conditions[0]);
			// 	dd(\array_key_exists('id',$conditions[0]));
			// }
			// if(!\isAssoc($conditions) && is_array($conditions) && count($conditions) == 2) {
			// 	$this->doubleParamWhere($conditions);
			// }else if(!\isAssoc($conditions) && is_array($conditions) && (count($conditions) == 3)) {
			// 	$this->tripleParamWhere($conditions);
			// }else {
			// 	foreach($conditions as &$condition) {
			// 		if(!\isAssoc($condition[0]) && is_array($condition[0]))
			// 			$condition = $condition[0];
			// 		if(is_array($condition) && count($condition) == 2) {
			// 			if(is_array($condition[0]) && count($condition) == 2)
			// 				$this->doubleParamWhere($condition[0]);
			// 			else if(is_array($condition[0]) && count($condition) == 3)
			// 				$this->tripleParamWhere($condition[0]);
			// 			else $this->doubleParamWhere($condition);
			// 		}
			// 		if(is_array($condition) && count($condition) == 3) 
			// 			$this->tripleParamWhere($condition);
			// 	}
			// }
			// if(count($this->wheres) == 2)
			// 	vd($this->wheres);
			return $this;
		}

		private function doubleParamWhere(array &$array)
		{
			// dd($array);
			// dd(is_array($array[0]) );
			if(!\isAssoc($array)) {
				foreach($array as $k => $el) {
					$this->wheres[] = [
						'column' => $el[0],
						'operator' => '=',
						'value' => $el[1]
					];
				}
			}else {
				$this->wheres[] = [
					'column' => $array[0],
					'operator' => '=',
					'value' => $array[1]
				];
			}
		}

		private function tripleParamWhere(array &$array)
		{
			// dd($array);
			// dd($this->wheres);
			$this->wheres[] = [
				'column' => $array[0],
				'operator' => $array[1],
				'value' => $array[2]
			];
		}

		public static function table(string $table) 
		{
			$builder = new static;
			$builder->table = $table;
			return $builder;
		}

		public function setFetchType(string $type):void 
		{
			if(!in_array($type, array_keys($this->fetchTypes)))
				throw new Exception('Arguments are invalid');

			$this->$fetchType = $type;
		}

		private function addWhere()
		{

		}

		public function buildQuery()
		{
			$query = <<< 'EOQ'
				SELECT %s
				FROM %s
			EOQ;

			$select = ($this->select == '*')? '*' : (is_string($this->select)) ? $this->select : implode(',', $this->select);
			$query = sprintf($query, $select, $this->table);
			$wheres = '';
			if(count($this->wheres) == 1) {
				$wh = $this->wheres[0];
				if($this->isFirstWhere)
					$query .= " WHERE " . $wh['column'] . " " . $wh['operator'] . " :" .$wh['column'];
				else $query .= " AND(" . $wh['column'] . " " . $wh['operator'] . " :" . $wh['column'] . ')';
			}
			else if(count($this->wheres) > 1) {
				$cols = array_keys($this->wheres);
				foreach ($cols as $k => &$v) {
					$v = ':' . $v;
				}
				$query .= " WHERE (";
				$query .= implode(',', array_keys($cols));
				$query .= ")";
				// $query .= " WHERE(?)";
				// vd($query);
			}

			$this->query = $query;
			
			// vd($this->connection->pdo);
			// vd([$query, $wheres]);

			// if(count($this->wheres) == 1)


			// $arr = [
			// 	$this->select,
			// 	$this->table,
			// 	$wheres
			// ];

		}

		public function exec()
		{
			$this->buildQuery();
			// vd($this->connection);
			$st = $this->connection->pdo->prepare($this->query);
			vd($this->wheres);
			foreach($this->wheres as $i => $where) {
				$st->bindParam(':'.$where['column'], $where['value']);
			}

			try {
				$st->execute();
			} catch (\PDOException $e) {
				vd($st->getLastError());
			}

			$fetch_t = '';
			foreach($this->fetchTypes as $k => $type) {
				if($k == $this->fetchType) {
					$fetch_t = $type;
					break;
				} 
			}

			$this->results = $st->fetchAll($fetch_t);
			return $this->results;
		}

		public function sql()
		{
			return $this->query;
		}


	}

?>