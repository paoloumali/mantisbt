<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page stores the reported bug
 *
 * @package MantisBT
 * @copyright Copyright 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright 2002  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses core.php
 * @uses api_token_api.php
 */

require_once( 'core.php' );
require_api( 'api_token_api.php' );

form_security_validate( 'revoke_api_token_form' );

auth_ensure_user_authenticated();
auth_reauthenticate();

$f_token_id = gpc_get_int( 'token_id' );
$t_token_row = api_token_get( $f_token_id );
$t_token_name = $t_token_row['name'];

$t_user_id = auth_get_current_user_id();

$t_data = array(
	'query' => array(
		'user_id' => $t_user_id,
		'id' => $f_token_id,
	),
);

$t_command = new UserTokenDeleteCommand( $t_data );
$t_command->execute();

form_security_purge( 'revoke_api_token_form' );

layout_page_header_begin();
html_meta_redirect( 'api_tokens_page.php' );
layout_page_header_end();

layout_page_begin( 'api_tokens_page.php' );

echo '<div class="col-md-12 col-xs-12">';
echo '<div class="space-10"></div>';
echo '<div class="lead">' . sprintf( lang_get( 'api_token_revoked' ), string_display_line( $t_token_name ) ) . '</div>';
echo '<div class="space-10"></div>';
print_link_button( 'api_tokens_page.php', lang_get( 'api_tokens_link' ) );
echo '</div>';

layout_page_end();

