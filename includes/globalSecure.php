<?php

/**
 * This file should be included by all php pages.
 * If the page does not have it's own security script,
 *   this script prevents the page from being loaded.
 *   This is especially useful for includes, which should 
 *   never be viewed independently.
 *
 *    ***** INCLUDE AFTER ANY secure.php SCRIPTS *****
 */

if( $security_override_active == TRUE ) {

}
else {
  header( 'HTTP/1.0 403 Forbidden' );
  exit( '' );
}


?>