<?php

namespace Auditeur\Auditeur\Http\Resources;

use Auditeur\Auditeur\Classes\AuditeurResolver;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource {


	public function toArray($request)
	{
		return (new AuditeurResolver($this ->resource)) ->resolve() ;
	}


}

