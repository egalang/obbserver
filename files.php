<?php
include('../wp-load.php');
$wp = wp_get_current_user();
$wp_id = $wp->ID;

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
	->where( 'enrollment_list.wp_id', $wp_id )
	->where( 'enrollment_list.deleted', 'N' )
	->fields(
		Field::inst( 'enrollment_list.id' ),
		Field::inst( 'enrollment_list.sy' ),
    Field::inst( 'enrollment_list.lastname' ),
    Field::inst( 'enrollment_list.firstname' ),
		Field::inst( 'enrollment_list.middlename' ),
		Field::inst( 'enrollment_list.reviewed' ),
		Field::inst( 'enrollment_list.accepted' ),
    Field::inst( 'enrollment_list.reserved' ),
    Field::inst( 'enrollment_list.card' )
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
	->process( $_POST )
	->json();
