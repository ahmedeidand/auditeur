<?php

namespace Auditeur\Auditeur\Classes;

use ErrorException ; 
use BadFunctionCallException ;
use Error;
use OwenIt\Auditing\Models\Audit;
use ReflectionClass;

class AuditeurResolver {

	protected $audit ;

	public function __construct(Audit $audit) 
	{
		$this ->audit = $audit ; 
	}

	public function resolve()
	{

		return [ 

			'Authenticatable' => $this ->authenticatable() , 
			'Log'  => $this ->log() , 
			'Event' => $this ->audit ->event , 
			// 'Auditable' => config('auditeur.auditable_types') [$this ->audit ->auditable_type]['name'] ?? $this ->audit ->auditable_type , 
			'Auditable' => $this ->displayClass() , 
			'id' => $this ->audit ->auditable_id , 
			'o' => $this ->_parse($this ->audit ->old_values) ,
			'n' => $this ->_parse($this ->audit ->new_values) , 
			'Client' => $this ->audit ->user_agent , 
			'IP Address' => $this ->audit ->ip_address , 
			'Time' => $this ->audit ->created_at ->format('Y-m-d H:i:s ') ,
			'Info' => $this ->info() , 
			'Url' => $this ->audit ->url ,
			'a_id' => $this ->audit ->id
		] ;

	}

	public function _parse(array $o)
	{

		// if ($this ->audit ->event == 'deleted') return [] ;

		$r = [] ;

		foreach ($o as $k => $v) {


			// if (! isset(config('auditeur.auditable_types') [$this ->audit ->auditable_type] ['only_defined_attributes'])) continue ; 
			if (isset(config('auditeur.auditable_types') [$this ->audit ->auditable_type] ['only_defined_attributes']) && config('auditeur.auditable_types') [$this ->audit ->auditable_type] ['only_defined_attributes'] && !in_array($k, array_keys(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes']))) continue ; 
			// dd(config('auditeur.auditable_types') [$this ->audit ->auditable_type]) ;
			// dd(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes']) ; 
			// dd($k) ;
			// dd(is_array(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k])) ;
			try {

				if (is_array(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k])) {

					// dd(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']) ; 
					$r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']] = $this ->_attatch(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]) ;

				} else {
					$r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]] = $v;

				}

			} catch (ErrorException $e) {

				if (! $e ->getCode())  $r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']] = $v ;
				else $r [$k] = $v ;
			} catch (BadFunctionCallException $e) {

				$r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']] = $v ; 
			}
		}

		return $r ; 
	}

	public function _attatch(array $r)
	{


		if (! method_exists($this ->audit ->auditable() ->withTrashed() ->first(), $r ['relation']['name'])) throw new BadFunctionCallException() ;

		try {

			return $this ->audit ->auditable() ->withTrashed() ->first() ->{$r ['relation']['name']} ->{$r ['relation']['attribute']} ;

		} catch (Error $e) {

			return '' ; 
		}
		
	}

	public function info() 
	{		
		return $this ->_parse($this ->getOriginal()) ; 
	}

	protected function getOriginal()
	{
		if (! $this ->audit ->auditable() ->withTrashed() ->first()) return $this ->audit ->new_values ;
		return $this ->audit ->auditable() ->withTrashed() ->first() ->getOriginal() ?? [] ;
	}

	private function short()
	{
		return $this ->audit ->auditable ? (new ReflectionClass($this ->audit ->auditable)) ->getShortName() : '' ; 
	}

	private function log()
	{
		return str_replace('{$m}', $this ->displayClass(), str_replace('{$u}', $this ->authenticatable(), config('auditeur.events') [$this ->audit ->event])) ;
	}

	private function authenticatable()
	{
		return $this ->audit ->user ? $this ->audit ->user ->{config('auditeur.user_types') ['attribute']} : '' ;
	}

	private function displayClass()
	{
		return config('auditeur.auditable_types') [$this ->audit ->auditable_type]['name'] ?? $this ->short() ;
	}
}