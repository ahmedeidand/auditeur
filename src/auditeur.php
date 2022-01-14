<?php 

return [

	'user_types' => [
		// 'name' => 'Authenticatable' ,
		'attribute' => 'name'
	] , 

	//{$u}: Authenticatable name . 
	//{$m}: Model name . 

	'events' => [
		'created' => 'New {$m} has been created by {$u}' , 
		'deleted' => '{$m} has been deleted by {$u}' , 
		'updated' => '{$m} has been changed by {$u}' , 
	] , 

	'only_auditable_type' => true , 

	'models_path' => 'App' ,

	'info' => [

		App\OneSchedule::class => [
			App\OneScheduleDaytime::class => [
				'attribute' => 'schedule_id' , 
				'attributes' => [
					'day_from' ,
					'day_to' , 
					'time_from' , 
					'time_to' ,
					'admin_show'
				]
			] , 

			App\OneSession::class => [
				'attribute' => 'schedule_id'  , 
				'attributes' => [
					'session_date' , 
					'start_time' , 
					'end_time' ,
					'student_attendance' , 
					'teacher_attendance' , 
					'deleted_at' ,
					'course_id'

				]
			]
		]
	] ,



	'auditable_types' => [


		App\OneSession::class => [
			'only_defined_attributes' => true ,

			// 'parser' => Auditeur\Auditeur\Classes\AnonymousParser::class , 

			'name' => 'Session' ,
			'attributes' => [


				// 'shift_id' => 'Shift' ,
				'shift_id' => [
					'name' => 'Shift' , 
					// 'parser' => Auditeur\Auditeur\Classes\AttributeParser::class , 
					'relation' => [
						'name' => 'shift' ,
						'attribute' => 'name'
					]
				] ,

				'level_id' => [
					'name' => 'Level' , 
					'relation' => [
						'name' => 'level' , 
						'attribute' => 'title'
					]
				]  , 
				'course_id' => [
					'name' => 'Course' , 
					'relation' => [
						'name' => 'course' , 
						'attribute' => 'title'
					] 
				] ,

				'teacher_id' => [
					'name' => 'Teacher' , 
					'relation' => [
						'name' => 'teacherAccount' , 
						'attribute' => 'name'
					] 
				] ,

				'student_id' => [
					'name' => 'Student' , 
					'relation' => [
						'name' => 'studentAccount' , 
						'attribute' => 'name'
					] 
				]  ,
				'end_time' => 'End' ,
				'start_time' => 'Start' ,
				'session_date' => 'Date' ,
				'admin_show' => 'Showable' ,
				'student_attendance' => 'Student attendance' , 
				'teacher_attendance' => 'Teacher attendance' , 
				'deleted_at' => 'Deleted at' , 



			] 
		] ,

		App\TransactionHistory::class => [
			'name' => 'Transaction History'
		] ,
		App\Transaction::class => [
			'name' => 'Transaction' , 
			'attributes' => [
				'type' => 'Type'
			]
		] ,

		App\OneSchedule::class => [
			'only_defined_attributes' => true  ,
			'name' => 'Schedule' , 
			'attributes' => [

				'status' => 'Status' ,
				'shift_id' => [
					'name' => 'Shift' , 
					'relation' => [
						'name' => 'shift' ,
						'attribute' => 'name'
					]
				] ,

				'course_id' => [
					'name' => 'Course' , 
					'relation' => [
						'name' => 'course' , 
						'attribute' => 'title'
					] 
				]  ,

				'notify_student_to_buy_coins' => 'Notify to buy coins' ,
				'last_time_to_pay_next_schedule' => 'Last time to pay' ,
				'request_upcoming_schedule' => 'Request coming schedule' ,
				'notify_student_to_renew_subscription' => 'Notify to renew' , 
				'teacher_id' => [
					'name' => 'Teacher' , 
					'relation' => [
						'name' => 'teacherAccount' , 
						'attribute' => 'name'
					] 
				] ,

				'student_id' => [
					'name' => 'Student' , 
					'relation' => [
						'name' => 'studentAccount' , 
						'attribute' => 'name'
					] 
				] 



			]
		] ,


		App\Student::class 	=> [
			'name' => 'Student' , 
			'only_defined_attributes' => true ,
			'attributes' => [
				'id' => '#'  , 
				'balance' => 'Balance' , 
			]
		] , 

		// App\OneSchedule::class => [

		// 	'name' => 'Schedule' , 
		// 	'attributes' => [
		// 		'status' => 'Status' , 
		// 		'notify_student_to_renew_subscription' => 'Student notified to renew subscription' , 
		// 		'notify_student_to_buy_coins' => 'Student notified to buy coins'

		// 	]
		// ] ,

		App\OneScheduleDaytime::class => [

			'name' => 'Day' ,
			'only_defined_attributes' => true ,
			'attributes' => [
				'id' => '#' , 
				'schedule_id' => [
					'name' => 'Schedule' ,
					'relation' => [
						'name' => 'schedule' , 
						'attribute' => 'id'
					]

				] , 
				'day_from' => 'From' , 
				'day_to' => 'To' ,
				'time_from' => 'Time from' ,
				'time_to' => 'Time to' , 
				'admin_show' => 'Showable'

			]
		] ,

		// App\TransactionHistory::class => [

		// 	'attributes' => [
		// 		'id' => '#' , 
		// 		'total' => 'Total'
		// 	]
		// ]

	] ,

] ;
