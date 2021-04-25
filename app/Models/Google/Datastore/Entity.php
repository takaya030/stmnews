<?php

namespace App\Models\Google\Datastore;

class Entity
{
	// key/partitionId
	protected $projectId;

	// key/path
	protected $kind;
	protected $name;
	protected $id;

	// properties
	protected $properties = [];

	// version
	protected $version;

	/**
	 * @param array $ent	An entity parameters
	 * @param int $version	Version of the entity (optional)
	 * @param string $name	Name of the entity
	 */
	public function __construct( array $ent = [], int $version = 0, string $name = '' )
	{
		$this->projectId = config('accounts.google.project_id');
		$this->kind = config('accounts.google.datastore_kind');
		$this->name = $name;
		$this->id = null;

		if( !empty($ent) )
		{
			$this->parse( $ent, $version );
		}
	}

	protected function parse( array $ent, int $version = 0 )
	{
		$this->projectId = isset($ent['key']['partitionId']['projectId']) ? $ent['key']['partitionId']['projectId'] : null;
		$this->kind = isset($ent['key']['path'][0]['kind']) ? $ent['key']['path'][0]['kind'] : null;
		$this->name = isset($ent['key']['path'][0]['name']) ? $ent['key']['path'][0]['name'] : null;
		$this->id = isset($ent['key']['path'][0]['id']) ? $ent['key']['path'][0]['id'] : null;

		if( is_array($ent['properties']) )
		{
			foreach( $ent['properties'] as $key => $value )
			{
				$this->properties[$key] = array_shift($value);
			}
		}

		$this->version = $version;
	}

	/**
	 * @param string $prop_name		property name	
	 * @return mixed|null
	 */
	public function get( string $prop_name )
	{
		return isset($this->properties[$prop_name]) ? $this->properties[$prop_name] : null;
	}

	/**
	 * @param string $prop_name		property name	
	 * @param mixed $prop_value		property value	
	 */
	public function set( string $prop_name, $prop_value )
	{
		$this->properties[$prop_name] = $prop_value;
	}

	/**
	 * Output json string of Entity format
	 *
	 * @param bool $with_version	If true, contain version
	 * @return string
	 */
	public function json( bool $with_version = false )
	{
		$result = json_encode([
			'key' => [
				'partitionId' => [ 'projectId' => $this->projectId ],
				'path' => [ ['kind' => $this->kind, 'name' => $this->name] ]
			],
			'properties' => $this->json_properties(),
		]);

		if( $with_version )
		{
			$result['version'] = (string)$version;
		}

		return $result;
	}

	protected function json_properties()
	{
		$result = [];
		foreach( $this->properties as $key => $value )
		{
			$result[$key] = [ $this->get_value_type($value) => (string)$value ];
		}

		return $result;
	}

	protected function get_value_type( $value )
	{
		if( is_numeric($value) )
			return 'integerVale';
		if( is_null($value) )
			return 'nullValue';

		return 'stringVale';
	}
}
