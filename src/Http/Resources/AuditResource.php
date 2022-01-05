<?php

namespace Auditeur\Auditeur\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ErrorException ; 
use BadFunctionCallException ;

class AuditResource extends JsonResource {


	public function toArray($request)
	{

		// dd(config('auditeur.user_types')) ;
		return [ 
			'Authenticatable' => $this ->user ? $this ->user ->{config('auditeur.user_types') ['attribute']} : '' , 
			'Event'  => config('auditeur.events') [$this ->event] , 
			'Auditable' => config('auditeur.auditable_types') [$this ->auditable_type]['name'] ?? $this ->auditable_type , 
			'o' => $this ->_parse($this ->old_values) ,
			'n' => $this ->_parse($this ->new_values) , 
			'Client' => $this ->user_agent , 
			'IP Address' => $this ->ip_address , 
			'Time' => $this ->created_at ->format('Y-m-d l') ,
		] ;
	}

	public function _parse(array $o)
	{

		$r = [] ;

		foreach ($o as $k => $v) {

			try {
				if (is_array(config('auditeur.auditable_types') [$this ->auditable_type]['attributes'][$k])) {

					// dd(config('auditeur.auditable_types') [$this ->auditable_type]['attributes'][$k]['name']) ; 
					$r [config('auditeur.auditable_types') [$this ->auditable_type]['attributes'][$k]['name']] = $this ->_attatch(config('auditeur.auditable_types') [$this ->auditable_type]['attributes'][$k]) ;

				} else {
					$r [config('auditeur.auditable_types') [$this ->auditable_type]['attributes'][$k]] = $v;

				}

			} catch (ErrorException $e) {

				$r [$k] = $v ; 

			} catch (BadFunctionCallException $e) {

				$r [config('auditeur.auditable_types') [$this ->auditable_type]['attributes'][$k]['name']] = $v ; 
			}
		}

		return $r ; 
	}

	public function _attatch(array $r)
	{


		if (! method_exists($this ->auditable, $r ['relation']['name'])) throw new BadFunctionCallException() ;
		return $this ->auditable ->{$r ['relation']['name']} ->{$r ['relation']['attribute']} ;
	}


}

