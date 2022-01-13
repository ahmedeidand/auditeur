<?php

namespace Auditeur\Auditeur\Classes;

use OwenIt\Auditing\Models\Audit;

class Info {

	private $_args ;

	private $_merge ; 

	public function __construct(array $args)
	{
		$this ->_args = $args ; 
		$this ->_merge = [] ;
	}

	public function resolve()
	{

		if (! isset(config('auditeur.info') [$this ->_args ['name']])) return [] ;

		array_walk(config('auditeur.info') [$this ->_args ['name']], function ($data, $relation) {

			return Audit::whereHasMorph('auditable', $relation) 
			->wherejsonContains('new_values->' . $data ['attribute'], $this ->_args ['id']) 
			->orWhereJsonContains('old_values->' . $data ['attribute'], $this ->_args ['id']) 
			->get() 
			->each(function ($item) {

				if (! $item ->auditable) return  ;
				return $item ->auditable ->audits 
				->each(function ($audit) {

					array_push($this ->_merge, $audit) ;
					// array_push($this ->_merge, (new Auditeur) ->resolve($audit)) ;
				}) ;
			})  ;


		}) ; 


		Audit::whereHasMorph('auditable', $this ->_args ['name'], function ($q) {
			$q ->where('id', $this ->_args ['id']) ;
		}) 
		->get() 
		->each(function ($item) {
			array_push($this ->_merge, $item) ;
			// array_push($this ->_merge, (new Auditeur) ->resolve($item)) ;
		})   ;


	}

	public function merge()
	{
		$this ->resolve() ;
		usort($this ->_merge, function ($a, $b) {
			// return (new DateTime($b ['Time'])) ->getTimestamp() > (new DateTime($a ['Time'])) ->getTimestamp() ;
			return $a ->id > $b ->id ;
		}) ;

		return array_map(function ($item) {
			return (new InfoResolver($item, $this ->_args ['name'])) ->resolve()	 ;
		}, $this ->_merge) ;
	}
}
