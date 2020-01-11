<?php

class DatabaseObject {

	static protected $database;
	static protected $table_name = "";
	static protected $columns = [];
	public $errors = [];

	static public function set_database ($database) {
		self::$database = $database;
	}

	static public function find_all () {
		$sql = "SELECT * FROM ".static::$table_name." ";
		return static::find_by_sql($sql);
	}

	static public function find_by_id ($id) {
		$sql = "SELECT * FROM ";
		$sql .= static::$table_name;
		$sql .= " WHERE id='".self::$database->escape_string($id) ."'";

		$result = static::find_by_sql($sql);

		if (!empty($result)) {
		  return array_shift($result);
		} else {
		  return false;
		}
	}

	static public function find_all_pagination ($per_page, $offset) {

		$sql = "SELECT * FROM ".static::$table_name." ";
		$sql .= "LIMIT {$per_page} ";
		$sql .= "OFFSET {$offset}";

		return self::find_by_sql($sql);
	}
	
	static public function total_count () {
		$sql = "SELECT COUNT(*) FROM ";
		$sql .= static::$table_name;

		$result_set =  self::$database->query($sql);
		$row = $result_set->fetch_array();
		return array_shift($row);
	
	}

	static public function find_by_sql ($sql) {
		$result = self::$database->query($sql);
		if(!$result) exit("Database query failed");

		//convert the results into objects
		$object_array = [];

		while($record = $result->fetch_assoc()) {
		  $object_array[] = static::instantiate($record);
		}
		$result->free();
		return $object_array;
	}


	static protected function instantiate ($record) {
		$obj = new static();

		foreach ($record as $property => $value) {
		  if (property_exists($obj, $property)){
		    $obj->$property = $value;
		  }
		}

		return $obj;
	}

	protected function validate () {

		$this->errors = [];

		//Add custom validations

		return $this->errors;
	}

	protected function create () {

		if (!empty($this->validate())) return false;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql .= join(', ', static::$db_columns);
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";

		$results = self::$database->query($sql);
		if ($results) {
		  $this->id = self::$database->insert_id;
		}
		return $results;
	}

	protected function update () {

		if (!empty($this->validate())) return false;	
		$attributes = $this->sanitized_attributes();
		$attributes_pairs = [];
		$sql = "UPDATE ".static::$table_name." SET ";

		foreach ($attributes as $column => $values) {
		  if($values) {
		    $attributes_pairs[] = "{$column}='{$values}'";
		  }
		}

		$sql .= join(', ',$attributes_pairs);
		$sql .= " WHERE id='". self::$database->escape_string($this->id) . "' ";
		$sql .= "LIMIT 1";

		return self::$database->query($sql);
	}

	public function delete () {
		$sql = "DELETE FROM ".static::$table_name." ";
		$sql .= " WHERE id='". self::$database->escape_string($this->id) . "' ";
		$sql .= "LIMIT 1";

		return self::$database->query($sql);

	}

	public function save () {
		if (isset($this->id)) {
		  return $this->update();
		} else {
		  return $this->create();
		}
	}

	public function merge_attributes ($args) {

		foreach ($args as $property => $value) {
		  if (property_exists($this, $property) AND !is_null($value)) {
		    $this->$property = $value;
		  }
		}
	}

	public function attributes () {
		$attributes = []; 

		foreach (static::$db_columns as $column) {
		  if ($column != 'id') {
		    $attributes[$column] = $this->$column;
		  }
		}

		return $attributes;
	}

	protected function sanitized_attributes () {
		$sanitized = []; 

		foreach ($this->attributes() as $key => $value) {
		  $sanitized[$key] = self::$database->escape_string($value);
		}

		return $sanitized;
	}
}

?>