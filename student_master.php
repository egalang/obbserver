

<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "editor/lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'enrollment_list' )
	->where( 'deleted', 'N' )
	->where( 'accepted', 'Y' )
	->fields(
		Field::inst( 'enrollment_list.id' ),
		Field::inst( 'enrollment_list.sy' ),
		Field::inst( 'enrollment_list.lrn' ),
		Field::inst( 'enrollment_list.firstname' )
			->validator( Validate::notEmpty( ValidateOptions::inst()
				->message( 'A first name is required' )
			) ),
		Field::inst( 'enrollment_list.lastname' )
			->validator( Validate::notEmpty( ValidateOptions::inst()
				->message( 'A last name is required' )
			) ),
		Field::inst( 'enrollment_list.middlename' ),
		Field::inst( 'enrollment_list.extname' ),
		Field::inst( 'enrollment_list.psa' ),
		Field::inst( 'enrollment_list.level' ),
		Field::inst( 'grade_levels.name' ),
		Field::inst( 'enrollment_list.birth' ),
		Field::inst( 'enrollment_list.age' ),
		Field::inst( 'enrollment_list.sex' ),
		Field::inst( 'enrollment_list.mt' ),
		Field::inst( 'enrollment_list.add1' ),
		Field::inst( 'enrollment_list.add2' ),
		Field::inst( 'enrollment_list.add3' ),
		Field::inst( 'enrollment_list.zip' ),
		Field::inst( 'enrollment_list.father' ),
		Field::inst( 'enrollment_list.mother' ),
		Field::inst( 'enrollment_list.guardian' ),
		Field::inst( 'enrollment_list.wp_email' ),
		Field::inst( 'enrollment_list.phone1' ),
		Field::inst( 'enrollment_list.phone2' ),
		Field::inst( 'enrollment_list.phone3' ),
		Field::inst( 'enrollment_list.terms' ),
		Field::inst( 'payment_terms.id' ),
		Field::inst( 'payment_terms.name' ),
		Field::inst( 'enrollment_list.balance' ),
		Field::inst( 'enrollment_list.reviewed' ),
		Field::inst( 'enrollment_list.accepted' ),
		Field::inst( 'enrollment_list.comments' )
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
	->leftJoin( 'payment_terms', 'enrollment_list.terms', '=', 'payment_terms.id' )
	->leftJoin( 'grade_levels', 'enrollment_list.level', '=', 'grade_levels.id' )
	->process( $_POST )
	->json();
