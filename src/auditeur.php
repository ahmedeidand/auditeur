<?php 

return [

	'user_types' => [
		// 'name' => 'Authenticatable' ,
		'attribute' => 'name'
	] , 

	'events' => [
		'created' => 'Created' , 
		'deleted' => 'Deleted' , 
		'updated' => 'Updated' , 
	] , 

	'only_auditable_type' => false , 

	'auditable_types' => [

		// App\Transaction::class 	=> [
		// 	'name' => 'Transaction' , 
		// 	'attributes' => [
		// 		'id' => '#'  , 
		// 		'to_id' => 'To' , 
		// 		'from_id' => [
		// 			'name' => 'From', 
		// 			'relation' => [
		// 				'name' => 'from' ,
		// 				'attribute' => 'name'
		// 			] ,

		// 		] ,
		// 	]
		// ] , 
		// App\OneSchedule::class => [

		// 	'name' => 'Schedule' , 
		// 	'attributes' => [
		// 		'status' => 'Status' , 
		// 		'notify_student_to_renew_subscription' => 'Student notified to renew subscription' , 
		// 		'notify_student_to_buy_coins' => 'Student notified to buy coins'

		// 	]
		// ] ,

		// App\OneScheduleDaytime::class => [

		// 	'attributes' => [
		// 		'id' => '#' , 
		// 		'schedule_id' => [
		// 			'name' => 'Schedule' ,
		// 			'relation' => [
		// 				'name' => 'schedule' , 
		// 				'attribute' => 'id'
		// 			]

		// 		] , 
		// 		'day_from' => 'From' , 
		// 		'day_to' => 'To' ,
		// 		'time_from' => 'Time from' ,
		// 		'time_to' => 'Time to' , 

		// 	]
		// ] ,

		// App\TransactionHistory::class => [

		// 	'attributes' => [
		// 		'id' => '#' , 
		// 		'total' => 'Total'
		// 	]
		// ]

	] ,

] ;
