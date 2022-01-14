<?php

namespace Auditeur\Auditeur\Classes;

use Error;
use Exception;
use OwenIt\Auditing\Models\Audit;

class AttributeParser 
{

	private $_value ;
	private $_config ;
	private $_audit ;

	public function __construct($value, $config, Audit $audit)
	{
		$this ->_value = $value ; 
		$this ->_config = $config ;
		$this ->_audit = $audit ; 
	}

	public function parse()
	{

		if (! is_array($this ->_config)) 
			return [
				'name' => $this ->_config, 
				'value' => $this ->_value
			] ;


		if (! isset($this ->_config['relation'])) throw new Exception('[relation] not set') ;
		if (! isset($this ->_config['relation']['name'])) throw new Exception('[name] not set') ;


		try {

			return  [
				'name' => $this ->_config ['name'] ,
				'value' => $this ->_audit ->auditable() ->withTrashed() ->first() ->{$this ->_config ['relation']['name']} ->{$this ->_config ['relation']['attribute']}
			] ;

		} catch (Exception $e) {

			return [
				'name' => $this ->_config ['name'] , 
				'value' => $this ->_value 
			] ; 

		} catch (Error $e) {

			return [
				'name' => $this ->_config ['name'] , 
				'value' => $this ->_value 
			] ; 

		}

	}


}