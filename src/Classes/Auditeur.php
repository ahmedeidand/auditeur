<?php

namespace Auditeur\Auditeur\Classes;

use Auditeur\Auditeur\Http\Resources\AuditResource;
use OwenIt\Auditing\Models\Audit;

class Auditeur {

	public function collection(array $args)
	{
		return AuditResource::collection($this ->_query($args) ->paginate()) ;
	}

	public function query(array $args)
	{
		// $this ->info() ;

		return $this ->_query($args) ;
	}

	protected function _query(array $args)
	{

		return Audit::where(function ($q) {

			if (config('auditeur.only_auditable_type')) $q ->whereHasMorph('auditable', array_keys(config('auditeur.auditable_types'))) ;

		})
		->when($args ['auditable'] ?? null, function ($q) use ($args) {
			$q ->whereHasMorph('auditable', $this ->getReal($args ['auditable'])) ;
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

		->when($args ['id'] ?? null, function ($q) use ($args) {

			$q ->where('auditable_id', $args ['id']) ;
		})

		->when($args ['event'] ?? null, function ($q) use ($args) {

			$q ->where('event', $args ['event']) ;
		})


		->when($args ['name'] ?? null, function ($q) use ($args) {

			$q ->whereHas('user', function ($q) use ($args) {
				$q ->where('name', 'like', '%' . $args ['name'] . '%') ; 
			}) ;
		}) ->orderBy('created_at', 'DESC') ;

	}

	public function getReal($name)  
	{

		foreach (config('auditeur.auditable_types') as $model => $data) {
			if (isset($data ['name']) && $data ['name'] == $name) return $model ; 
		}

		return config('auditeur.models_path') . '\\' .$name ; 
	}

	public function info(array $args)
	{
		return (new Info($args)) ->merge() ;
	}

	public function resolve(Audit $audit)
	{
		return (new AuditeurResolver($audit)) ->resolve() ;
	}
}
