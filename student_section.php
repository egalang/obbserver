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

Editor::inst( $db, 'enrollment_list' )
	->where( 'sy', '2024-2025' )
	->where( 'deleted', 'N')
	->where( 'accepted', 'Y')
	->fields(
		Field::inst( 'enrollment_list.id' ),
		Field::inst( 'enrollment_list.lastname' ),
		Field::inst( 'enrollment_list.firstname' ),
		Field::inst( 'enrollment_list.middlename' ),
		Field::inst( 'enrollment_list.level' ),
		Field::inst( 'enrollment_list.section_id' )
			->options( Options::inst()
				->table( 'sections ' )
				->value( 'id' )
				->label( 'name' )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'grade_levels.id' ),
		Field::inst( 'grade_levels.name' ),
		Field::inst( 'sections.id' ),
		Field::inst( 'sections.name' ),
		Field::inst( 'enrollment_list.email2' ),
		Field::inst( 'enrollment_list.barcode' )
	)
	->join(
		Mjoin::inst( 'files' )
			->link( 'enrollment_list.id', 'users_files.user_id' )
			->link( 'files.id', 'users_files.file_id' )
			->fields(
				Field::inst( 'id' )
					->upload( Upload::inst( 'uploads/__ID__.__EXTN__' )
						->db( 'files', 'id', array(
							'filename'    => Upload::DB_FILE_NAME,
							'filesize'    => Upload::DB_FILE_SIZE,
							'web_path'    => Upload::DB_WEB_PATH,
							'system_path' => Upload::DB_SYSTEM_PATH
						) )
						->validator( Validate::fileSize( 500000, 'Files must be smaller that 500K' ) )
						->validator( Validate::fileExtensions( array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf' ), "Please upload an image" ) )
					)
			)
	)
	  ->leftjoin( 'sections', 'enrollment_list.section_id', '=', 'sections.id' )
	->leftjoin( 'grade_levels', 'enrollment_list.level', '=', 'grade_levels.id' )
	->process( $_POST )
	->json();
