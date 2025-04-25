

<?php
if(isset($_GET['id'])){
	$id = $_GET['id'];
}
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
Editor::inst( $db, 'help_talk' )
	->where( 'help_talk.help_id', $id )
	->fields(
		Field::inst( 'help_talk.id' ),
		Field::inst( 'help_talk.help_id' ),
		Field::inst( 'help_talk.comments' ),
		Field::inst( 'help_talk.date' )
	)
	->join(
      Mjoin::inst( 'files' )
          ->link( 'help_talk.id', 'users_files.user_id' )
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
	//->leftJoin( 'payment_terms', 'enrollment_list.terms', '=', 'payment_terms.id' )
	//->leftJoin( 'grade_levels', 'enrollment_list.level', '=', 'grade_levels.id' )
	->process( $_POST )
	->json();
