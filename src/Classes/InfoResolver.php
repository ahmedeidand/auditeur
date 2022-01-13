<?php

namespace Auditeur\Auditeur\Classes;

use OwenIt\Auditing\Models\Audit;

class InfoResolver extends AuditeurResolver {

	private $_for ;

	public function __construct(Audit $audit, string $for) 
	{

		$this ->audit = $audit ; 
		$this ->_for = $for ; 

	}

	public function info() 
	{	

		if ($this ->_for == $this ->audit ->auditable_type) return parent::info($this ->audit) ; 

		return $this ->_parse(array_filter($this ->getOriginal(), function ($attribute) {
			return in_array($attribute, $this ->attributes()) ;
		},ARRAY_FILTER_USE_KEY)) ; 
	}

	private function attributes()
	{

		foreach (config('auditeur.info')  as $for => $data) {

			if ($for != $this ->_for) continue ; 
			return $data [$this ->audit ->auditable_type] ['attributes']  ;
		}

		return [] ;

	}

}