<?php
	namespace Application\Database;
	require_once __DIR__ . DS . 'Connection' . PHP_EXT;
	trait Builder
	{
		protected $connection;
		private $wheres = [];
		private $orderBy = [];
		private $select = '*';
		private $limit;
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
			$this->buildQuery();
			return $this;
		}

		public function first()
		{
			$this->limit = 1;
			$this->fetchType = 'obj';
			$this->exec();
			if(isset($this->results[0]))
				return $this->results[0];
			else return $this->results;

		}

		public function orderBy($column, $sorting = 'asc') {
			if(!in_array($sorting, ['asc', 'desc']))
				throw new \Error('Order parameter is incorrect');

			$this->orderBy[] = [
				'column' => $column,
				'order' => $sorting
			];
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
				$v= [];
				$query .= " WHERE (";
				foreach ($this->wheres as &$wh) {
					if(!array_key_exists(0, $wh)) {
						if($this->isFirstWhere)
							$v[] = "(" . $wh['column'] . " " . $wh['operator'] . ' :' . $wh['column'] . ")";
					}
				}
				$query .= implode(" AND ", $v);
				$query .= ")";
			}

			if(count($this->orderBy)) {
				$query .= SPACE . 'ORDER BY' . SPACE;
				foreach($this->orderBy as $row) {
					$query .= $row['column'] . SPACE . $row['order'];
				}
			}

			if(isset($this->limit)) {
				$query .= SPACE . 'LIMIT' . SPACE . $this->limit;
			}

			$this->query = $query;
			
		}

		public function exec()
		{
			$this->buildQuery();
			// vd($this->query);
			$st = $this->connection->pdo->prepare($this->query);
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