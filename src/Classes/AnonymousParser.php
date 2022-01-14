<?php

namespace Auditeur\Auditeur\Classes;

use Exception;
use OwenIt\Auditing\Models\Audit;

class AnonymousParser {

	private $_audit ; 
	private $_attributes ;
	private $_only_defined_attributes ;

	public function __construct(Audit $audit)
	{
		$this ->_audit = $audit ; 

		if (! isset(config('auditeur.auditable_types') [$this ->_audit ->auditable_type])) throw new Exception('Type not set', 1001) ; 
		if (! isset(config('auditeur.auditable_types') [$this ->_audit ->auditable_type]['attributes'])) throw new Exception('Type attributes not set', 1001) ; 

		$this ->_attributes = config('auditeur.auditable_types') [$this ->_audit ->auditable_type]['attributes'] ; 

		$this ->_only_defined_attributes = $this ->getOnlyDefinedAttributes() ;


	}

	public function parse(array $attributes)
	{

		$r = [] ;

		foreach ($attributes as $attribute => $value) {

			if (! in_array($attribute, array_keys($this ->_attributes)) && $this ->_only_defined_attributes) continue  ;


			$parser = (! in_array($attribute, array_keys($this ->_attributes)) && ! $this ->_only_defined_attributes) ?

			(new AttributeParser($value, $attribute, $this ->_audit)) ->parse() :
			(new AttributeParser($value, $this ->_attributes [$attribute], $this ->_audit)) ->parse() ;


			$r [$parser ['name']] = $parser ['value'] ;

		}

		return $r ;

	}

	public function attributes()
	{
		return $this ->attributes() ;
	}

	public function audit()
	{

		return $this ->_audit ;
	}

	public function getOnlyDefinedAttributes()
	{
		// if (! isset(config('auditeur.auditable_types') [$this ->_audit ->auditable_type]['only_defined_attributes'])) throw new Exception('[only_defined_attributes] not set ' . $this ->_audit ->auditable_type) ;
		if (! isset(config('auditeur.auditable_types') [$this ->_audit ->auditable_type]['only_defined_attributes'])) return false ;

		return  config('auditeur.auditable_types') [$this ->_audit ->auditable_type]['only_defined_attributes'] ;

	}

}