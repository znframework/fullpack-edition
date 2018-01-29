<?php
class InternalMigrateUsers extends ZN\Database\InternalMigration
{
	//--------------------------------------------------------------------------------------------------------
	// Class/Table Name
	//--------------------------------------------------------------------------------------------------------
	const table = 'users';

	//--------------------------------------------------------------------------------------------------------
	// Up
	//--------------------------------------------------------------------------------------------------------
	public function up()
	{
		// Default Query
		return $this->createTable
		([
		    'id' 		=> [DB::int(11), DB::primaryKey(), DB::autoIncrement()],
			'email' 	=> [DB::varchar(250)],
			'name' 		=> [DB::varchar(250)],
			'password' 	=> [DB::varchar(64)],
			'gender' 	=> [DB::varchar(20)],
			'birthdate' => [DB::datetime()],
			'about'     => [DB::text()],
			'website' 	=> [DB::varchar(300)],
			'role_id' 	=> [DB::int(11)],
			'ip' 		=> [DB::varchar(64)],
			'mobile' 	=> [DB::varchar(30)],
			'address' 	=> [DB::text()],
			'photo' 	=> [DB::varchar(255)],
			'address' 	=> [DB::text()],
			'date' 		=> [DB::datetime(), 'DEFAULT CURRENT_TIMESTAMP']
		]);
	}

	//--------------------------------------------------------------------------------------------------------
	// Down
	//--------------------------------------------------------------------------------------------------------
	public function down()
	{
		// Default Query
		return $this->dropTable();
	}
}