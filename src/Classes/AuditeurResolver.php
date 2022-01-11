<?php

namespace Auditeur\Auditeur\Classes;

use ErrorException ; 
use BadFunctionCallException ;
use OwenIt\Auditing\Models\Audit;
use ReflectionClass;

class AuditeurResolver {

	private $audit ;

	public function __construct(Audit $audit) 
	{
		$this ->audit = $audit ; 
	}

	public function resolve()
	{

		return [ 

			'Authenticatable' => $this ->audit ->user ? $this ->audit ->user ->{config('auditeur.user_types') ['attribute']} : '' , 
			'Event'  => config('auditeur.events') [$this ->audit ->event] , 
			// 'Auditable' => config('auditeur.auditable_types') [$this ->audit ->auditable_type]['name'] ?? $this ->audit ->auditable_type , 
			'Auditable' => config('auditeur.auditable_types') [$this ->audit ->auditable_type]['name'] ?? (new ReflectionClass($this ->audit ->auditable)) ->getShortName() , 
			'id' => $this ->audit ->auditable_id , 
			'o' => $this ->_parse($this ->audit ->old_values) ,
			'n' => $this ->_parse($this ->audit ->new_values) , 
			'Client' => $this ->audit ->user_agent , 
			'IP Address' => $this ->audit ->ip_address , 
			'Time' => $this ->audit ->created_at ->format('Y-m-d l') ,
		] ;

	}

	public function _parse(array $o)
	{

		$r = [] ;

		foreach ($o as $k => $v) {

			try {
				if (is_array(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k])) {

					// dd(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']) ; 
					$r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']] = $this ->_attatch(config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]) ;

				} else {
					$r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]] = $v;

				}

			} catch (ErrorException $e) {

				$r [$k] = $v ; 

			} catch (BadFunctionCallException $e) {

				$r [config('auditeur.auditable_types') [$this ->audit ->auditable_type]['attributes'][$k]['name']] = $v ; 
			}
		}

		return $r ; 
	}

	public function _attatch(array $r)
	{


		if (! method_exists($this ->audit ->auditable, $r ['relation']['name'])) throw new BadFunctionCallException() ;
		return $this ->audit ->auditable ->{$r ['relation']['name']} ->{$r ['relation']['attribute']} ;
	}

}