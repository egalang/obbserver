<?php

include( "editor/lib/DataTables.php" );

use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

Editor::inst( $db, 'sections' )
	->fields(
		Field::inst( 'sections.id' ),
		Field::inst( 'sections.name' ),
		Field::inst( 'sections.level_id' ),
		Field::inst( 'sections.adviser_id' )
			->options( Options::inst()
				->table( 'teachers' )
				->value( 'id' )
				->label( array('firstname', 'lastname') )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'grade_levels.id' ),
		Field::inst( 'grade_levels.name' ),
		Field::inst( 'teachers.firstname' ),
		Field::inst( 'teachers.middlename' ),
		Field::inst( 'teachers.lastname' )
	)
	->leftjoin( 'grade_levels', 'sections.level_id', '=', 'grade_levels.id' )
	->leftjoin( 'teachers', 'sections.adviser_id', '=', 'teachers.id' )
	->process( $_POST )
	->json();
