<?php
class InternalMigratePermissions extends ZN\Database\InternalMigration
{
	const table = 'permissions';

	//--------------------------------------------------------------------------------------------------------
	// Up
	//--------------------------------------------------------------------------------------------------------
	public function up()
	{
		// Default Query
		return $this->createTable
		([
		    'id'    => [DB::int(11), DB::primaryKey(), DB::autoIncrement()],
			'type'  => [DB::varchar(10)],
			'rules' => [DB::text()]
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