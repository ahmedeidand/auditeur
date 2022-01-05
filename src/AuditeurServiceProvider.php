<?php

namespace Auditeur\Auditeur;

use Illuminate\Support\ServiceProvider;


class AuditeurServiceProvider extends ServiceProvider 
{
	public function boot()
	{

	}

	public function register() 
	{
		$this->publishes([
			__DIR__.'/auditeur.php' => config_path('auditeur.php'),
		]);

	}

}
