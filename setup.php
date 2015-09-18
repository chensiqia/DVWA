<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Setup'.$page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'setup';

if( isset( $_POST[ 'create_db' ] ) ) {
	// Anti-CSRF
	checkTokens( $_REQUEST[ 'user_token' ] , 'index.php' );

	if( $DBMS == 'MySQL' ) {
		include_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/DBMS/MySQL.php';
	}
	elseif($DBMS == 'PGSQL') {
		// include_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/DBMS/PGSQL.php';
		dvwaMessagePush( 'PostgreSQL is not yet fully supported.' );
		dvwaPageReload();
	}
	else {
		dvwaMessagePush( 'ERROR: Invalid database selected. Please review the config file syntax.' );
		dvwaPageReload();
	}
}

// Anti-CSRF
generateTokens();

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Database Setup <img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/spanner.png\" /></h1>

	<p>Click on the 'Create / Reset Database' button below to create or reset your database.</br>
	If you get an error make sure you have the correct user credentials in: <em>" . realpath(  getcwd()  ) . "/config/config.inc.php</em></p>

	<p>If the database already exists, <em>it will be cleared and the data will be reset</em>.<br />
	You can also use this to reset the administrator credentials (\"<em>admin</em> // <em>password</em>\") at any stage.</p>
	<hr />
	<br />

	<h2>Setup Check</h2>

	{$DVWAOS}<br />
	Backend Database: <em>".$DBMS."</em><br />
	<br />
	PHP Version: v<em>".phpversion()."</em><br />
	{$phpSafeMode}<br/ >
	{$phpDisplayErrors}<br />
	{$phpURLInclude}<br/ >
	{$phpURLFopen}<br />
	{$phpMagicQuotes}<br />
	<br />
	{$DVWARecaptcha}<br />
	<br />
	{$DVWAUploadsWrite}<br />
	{$DVWAPHPWrite}<br />
	<br />
	<i>Status with \"(*)\" indicate there could be possible issue(s) with a certain modules.</i><br />
	<br /><br /><br />

	<!-- Create db button -->
	<form action=\"#\" method=\"post\">
		<input name=\"create_db\" type=\"submit\" value=\"Create / Reset Database\">
		".tokenField()."
	</form>
</div>
";

dvwaHtmlEcho( $page );

?>
