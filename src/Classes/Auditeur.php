<?php

namespace Auditeur\Auditeur\Classes;

use Auditeur\Auditeur\Http\Resources\AuditResource;
use OwenIt\Auditing\Models\Audit;

class Auditeur {

	public function collection(array $args)
	{
		return AuditResource::collection($this ->_query($args)) ;
	}

	public function query(array $args)
	{

		return $this ->_query($args) ;
	}

	protected function _query(array $args)
	{
		return Audit::where(function ($q) {

			if (config('auditeur.only_auditable_type')) $q ->whereHasMorph('auditable', array_keys(config('auditeur.auditable_types'))) ;

		})
		->when($args ['from'] ?? null, function ($q) use ($args) {

			$q ->where('created_at', '>=', $args ['from']) ;
		})
		->when($args ['to'] ?? null, function ($q) use ($args) {

			$q ->where('created_at', '<=', $args ['from']) ;

		})
		->when($args ['user_id'] ?? null, function ($q) use ($args) {

			$q ->where('user_id', $args ['user_id']) ;
		})

		->when($args ['name'] ?? null, function ($q) use ($args) {

			$q ->whereHas('user', function ($q) use ($args) {
				$q ->where('name', 'like', '%' . $args ['name'] . '%') ; 
			}) ;
		})

		->paginate() ;
	}
}
