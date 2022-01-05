<?php

namespace Auditeur\Auditeur\Facades;


use Illuminate\Support\Facades\Facade;


class Auditeur extends Facade 
{

	public static function getFacadeAccessor()
	{
		return \Auditeur\Auditeur\Classes\Auditeur::class ;
	}

}