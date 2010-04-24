<?php
/*
Plugin Name: ArcadePress
Plugin URI: http://www.skybox3d.com/store/products/arcadepress-open-source-wordpress-plugin-php-arcade-script/
Description: <a href="http://www.skybox3d.com/store/products/arcadepress-open-source-wordpress-plugin-php-arcade-script/" target="blank">ArcadePress</a> is an open source arcade plugin for Wordpress that allows you to turn any Wordpress site into a full arcade site, including flash game uploads, categories, highscores, game feeds & more.
Version: 0.65
Author: skybox3d.com
Author URI: http://www.skybox3d.com/
License: GPL2
*/

/*  
Copyright 2010 Skybox3d.com  (email : admin@skybox3d.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Global variables:
$arcadepress_version = 0.65;
$arcadepress_db_version = 0.65;
$APjavascriptQueue = NULL;

// Pre-2.6 compatibility, which is actually frivilous since we use the 2.8+ widget technique
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

// Create the proper directory structure if it is not already created
if(!is_dir(WP_CONTENT_DIR . '/uploads/')) {
	mkdir(WP_CONTENT_DIR . '/uploads/', 0777, true);
}
if(!is_dir(WP_CONTENT_DIR . '/uploads/arcadepress/')) {
	mkdir(WP_CONTENT_DIR . '/uploads/arcadepress/', 0777, true);
}
if(!is_dir(WP_CONTENT_DIR . '/uploads/arcadepress/games/')) {
	mkdir(WP_CONTENT_DIR . '/uploads/arcadepress/games/', 0777, true);
}	
if(!is_dir(WP_CONTENT_DIR . '/uploads/arcadepress/images/')) {
	mkdir(WP_CONTENT_DIR . '/uploads/arcadepress/images/', 0777, true);
}		
	

 /**
 * ===============================================================================================================
 * Main ArcadePress Class
 */	
if (!class_exists("ArcadePress")) {
    class ArcadePress {
		var $adminOptionsName = "ArcadePressAdminOptions";
		
        function ArcadePress() { //constructor
			
        }

		function  init() {
            $this->getAdminOptions();
        }
		
		//Returns an array of admin options
        function getAdminOptions() {
		
            $apAdminOptions = array('mainpage' => '',
									'turnon_arcadepress' => 'true',
									'height' => '300',
									'width' => '400',
									'alt' => '<p>'.__('The Flash plugin is required to view this object.', 'arcadepress').'</p>',
									'required_player_version' => '8.0.0',
									'express_install_swf' => WP_PLUGIN_URL.'/arcadepress/swf/expressInstall.swf',
									'allowfullscreen' => 'true',
									'wmode' => 'window',
									'showgamethumbnail' => 'true',
									'showgamedescription' => 'true',
									'creategameundercategory' => 'false'
									);									

            $devOptions = get_option($this->adminOptionsName);
            if (!empty($devOptions)) {
                foreach ($devOptions as $key => $option)
                    $apAdminOptions[$key] = $option;
            }            
            update_option($this->adminOptionsName, $apAdminOptions);
            return $apAdminOptions;
        }
		
		//Prints out the admin page ================================================================================
        function printAdminPage() {
			global $wpdb;

			$devOptions = $this->getAdminOptions();
			if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
				die(__('Cheatin&#8217; uh?'));
			}
		
			if (isset($_POST['update_arcadePressSettings'])) {
				if (isset($_POST['arcadePressmainpage'])) {
					$devOptions['mainpage'] = $wpdb->escape($_POST['arcadePressmainpage']);
				} 			
				if (isset($_POST['turnArcadePressOn'])) {
					$devOptions['turnon_arcadepress'] = $wpdb->escape($_POST['turnArcadePressOn']);
				}   
				if (isset($_POST['allowfullscreen'])) {
					$devOptions['allowfullscreen'] = $wpdb->escape($_POST['allowfullscreen']);
				}  				
				if (isset($_POST['arcadePressalt'])) {
					$devOptions['alt'] = apply_filters('content_save_pre', $_POST['arcadePressalt']);
				}
				if (isset($_POST['arcadePresswidth'])) {
					$devOptions['width'] = $wpdb->escape($_POST['arcadePresswidth']);
				}
				if (isset($_POST['arcadePressheight'])) {
					$devOptions['height'] = $wpdb->escape($_POST['arcadePressheight']);
				}		
				if (isset($_POST['arcadePresswmode'])) {
					$devOptions['wmode'] = $wpdb->escape($_POST['arcadePresswmode']);
				}				
				if (isset($_POST['arcadePressrequired_player_version'])) {
					$devOptions['required_player_version'] = $wpdb->escape($_POST['arcadePressrequired_player_version']);
				}
				if (isset($_POST['arcadePressexpress_install_swf'])) {
					$devOptions['express_install_swf'] = $wpdb->escape($_POST['arcadePressexpress_install_swf']);
				}		
				if (isset($_POST['showgamethumbnail'])) {
					$devOptions['showgamethumbnail'] = $wpdb->escape($_POST['showgamethumbnail']);
				}
				if (isset($_POST['showgamedescription'])) {
					$devOptions['showgamedescription'] = $wpdb->escape($_POST['showgamedescription']);
				}				
				if (isset($_POST['creategameundercategory'])) {
					$devOptions['creategameundercategory'] = $wpdb->escape($_POST['creategameundercategory']);
				}					
				update_option($this->adminOptionsName, $devOptions);
			   
				echo '<div class="updated"><p><strong>';
				_e("Settings Updated.", "ArcadePress");
				echo '</strong></p></div>';
			
			}
			
			echo '
			<style type="text/css">
				.tableDescription {
					width:350px;
					max-width:350px;
				}
			</style>
			<div class="wrap">
			<form method="post" action="'. $_SERVER["REQUEST_URI"].'">
			<div style="padding: 20px 10px 10px 10px;">
			<div style="float:left;"><img src="'.WP_PLUGIN_URL.'/arcadepress/images/logo.png" alt="arcadepress" /></div>
			<div style="float:left;">Please support ArcadePress by donating:
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3992HCPDPGQZJ" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" /></a></div>
			</div>
			<br style="clear:both;" /><br />
			<h2>ArcadePress General Options</h2>
			';
			
			echo '<table class="widefat">
			<thead><tr><th>Option</th><th>Description</th><th>Value</th></tr></thead><tbody>
			';			

			echo '
			<tr><td><h3>ArcadePress Main Page:</h3></td>
			<td class="tableDescription"><p>You need to use a Page as the base for ArcadePress.  Insert the POST ID of that page here:</p></td>
			<td><select name="arcadePressmainpage"> 
			 <option value="">
						';
			  attribute_escape(__('Select page')); 
			  echo '</option>'; 
			  
			  $pages = get_pages(); 
			  foreach ($pages as $pagg) {
				$option = '<option value="'.$pagg->ID.'"';
				if($pagg->ID==$devOptions['mainpage']) {
					$option .= ' selected="selected"';
				}
				$option .='>';
				$option .= $pagg->post_title;
				$option .= '</option>';
				echo $option;
			  }

			echo '
			</select>
			</td></tr>

			<tr><td><h3>Use categories?</h3></td>
			<td class="tableDescription"><p>Selecting "No" means all games will be child pages of the Main Page, and no category functions will be available. Selecting "Yes" means that all games will be children pages of the main category you select for a game (example, if a game\'s main category is Action, then your game would listed as a child of the page Action, which itself would be a child of Main Page, i.e. MainPage > Action > YourGame)</p></td>
			<td><p><label for="creategameundercategory_yes"><input type="radio" id="creategameundercategory_yes" name="creategameundercategory" value="true" '; if ($devOptions['creategameundercategory'] == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="creategameundercategory_no"><input type="radio" id="creategameundercategory_no" name="creategameundercategory" value="false" '; if ($devOptions['creategameundercategory'] == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p></td>
			</td></tr>						
			
			<tr><td><h3>Turn ArcadePress on?</h3></td>
			<td class="tableDescription"><p>Selecting "No" will turn off ArcadePress, but will not deactivate it.</p></td>
			<td><p><label for="turnArcadePressOn_yes"><input type="radio" id="turnArcadePressOn_yes" name="turnArcadePressOn" value="true" '; if ($devOptions['turnon_arcadepress'] == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="turnArcadePressOn_no"><input type="radio" id="turnArcadePressOn_no" name="turnArcadePressOn" value="false" '; if ($devOptions['turnon_arcadepress'] == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p></td>
			</td></tr>

	
			<tr><td><h3>Required Flash Player version</h3></td>
			<td class="tableDescription">The minimum version of Flash required to play any game.</td>
			<td><input type="text" name="arcadePressrequired_player_version" style="width: 88px;" value="'; _e(apply_filters('format_to_edit',$devOptions['required_player_version']), 'ArcadePress'); echo'" />  Example: <i>8.0.0</i></td>
			</tr>
			
			<tr style="display:none;"><td><h3>Allow fullscreen?</h3></td>
			<td class="tableDescription"></td>
			<td><p><label for="allowfullscreen_yes"><input type="radio" id="allowfullscreen_yes" name="allowfullscreen" value="true" '; if ($devOptions['allowfullscreen'] == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="allowfullscreen_no"><input type="radio" id="allowfullscreen_no" name="allowfullscreen" value="false" '; if ($devOptions['allowfullscreen'] == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p>
			</td></tr>

			<tr><td><h3>Flash WMODE</h3></td>
			<td class="tableDescription"><p>The wmode value when embedding Flash.</p></td>
			<td><input type="text" name="arcadePresswmode" style="width: 80%;" value="'; _e(apply_filters('format_to_edit',$devOptions['wmode']), 'ArcadePress'); echo'" />
			</td></tr>

			<tr><td><h3>URL to Express Install</h3></td>
			<td class="tableDescription"><p>This Flash file allows easy upgrading of Flash.</p></td>
			<td><input type="text" name="arcadePressexpress_install_swf" style="width: 80%;" value="'; _e(apply_filters('format_to_edit',$devOptions['express_install_swf']), 'ArcadePress'); echo'" />
			</td></tr>

			<tr><td><h3>Alternative Text</h3></td>
			<td class="tableDescription"><p>Alternative text when Flash is not activated or installed, or if the game is missing.</p></td>
			<td><textarea name="arcadePressalt" style="width: 80%; height: 80px;">'; _e(apply_filters('format_to_edit',$devOptions['alt']), 'ArcadePress'); echo'</textarea>
			</td></tr>
			</table>
			<br style="clear:both;" /><br />
			<h2>Game Display Options</h2>';
			
			echo '<table class="widefat">
			<thead><tr><th>Option</th><th>Description</th><th>Value</th></tr></thead><tbody>

			<tr><td><h3>Default Width & Height</h3></td>
			<td class="tableDescription"><p>All this value does is determine what is in the width and height field by default when you visit the Add New Game admin page.</p></td>
			<td>Width: <input type="text" name="arcadePresswidth" style="width: 58px;" value="'; _e(apply_filters('format_to_edit',$devOptions['width']), 'ArcadePress'); echo'" />  <br />Height: <input type="text" name="arcadePressheight" style="width: 58px;" value="'; _e(apply_filters('format_to_edit',$devOptions['height']), 'ArcadePress'); echo'" />
			</td></tr>

			<tr><td><h3>Display thumbnail under game?</h3></td>
			<td class="tableDescription"><p>If set to Yes, the thumbnail for the game will be displayed underneath the game itself</p></td>
			<td><p><label for="showgamethumbnail"><input type="radio" id="showgamethumbnail_yes" name="showgamethumbnail" value="true" '; if ($devOptions['showgamethumbnail'] == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="showgamethumbnail_no"><input type="radio" id="showgamethumbnail_no" name="showgamethumbnail" value="false" '; if ($devOptions['showgamethumbnail'] == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p>		
			</td></tr>

			<tr><td><h3>Display description under game?</h3></td>
			<td class="tableDescription"><p>If set to Yes, the description for the game is written underneath the game, after the thumbnail.</p></td>
			<td><p><label for="showgamedescription"><input type="radio" id="showgamedescription_yes" name="showgamedescription" value="true" '; if ($devOptions['showgamedescription'] == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="showgamedescription_no"><input type="radio" id="showgamedescription_no" name="showgamedescription" value="false" '; if ($devOptions['showgamedescription'] == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p>
			</td></tr>
			</table>
			
			<br style="clear:both;" /><br />
			<div class="submit">
			<input type="submit" name="update_arcadePressSettings" value="'; _e('Update Settings', 'ArcadePress'); echo'" /></div>
			</form>
			 </div>';		
		
		}
		//END Prints out the admin page ================================================================================		
		
		
		
		
		
		//Prints out the Add Games admin page =======================================================================
        function printAdminPageAddGames() {
			global $wpdb, $user_level;

			//Apparently this code doesn't work:
			//get_currentuserinfo();
			//if ($user_level <  8) {die('This page is not for you, but thanks for being sneaky and trying');};
			if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
				die(__('Cheatin&#8217; uh?'));
			}		
		
			$devOptions = $this->getAdminOptions();
			$table_name = $wpdb->prefix . "arcadepress_games";
			
			if(!isset($devOptions['mainpage']) || !is_numeric($devOptions['mainpage'])) {
				echo '<br /><br /><h3>ERROR: ArcadePress is configured incorrectly.  Visit the ArcadePress options page and set the main page to the numeric POST ID of a dedicated PAGE that you have created for your arcade.</h3>';
				return false;
			}
			
			// For new games
			if(!isset($_GET['keytoedit'])) {
				// Default form values
				$arcadePressgame_name = '';
				$arcadePressgame_description = '';
				$arcadePressgame_type = '';
				$arcadePressgame_file = '';
				$arcadePressgame_width = $devOptions['width'];
				$arcadePressgame_height = $devOptions['height'];
				$arcadePressgame_thumbnail = '';
				$arcadePressgame_tags = '';
				$keytoedit=0;
			} 
			
			
			// To edit a previous game
			$isanedit = false;
			if ($_GET['keytoedit']!=0 && is_numeric($_GET['keytoedit'])) {
				$isanedit = true;
				
				if (isset($_POST['arcadePressgame_name']) && isset($_POST['arcadePressgame_description']) && isset($_POST['arcadePressgame_type']) && isset($_POST['arcadePressgame_file']) && $_POST['arcadePressgame_width'] && $_POST['arcadePressgame_height'] && is_numeric($_POST['arcadePressgame_width']) && is_numeric($_POST['arcadePressgame_height']) && isset($_POST['arcadePressgame_thumbnail']) && isset($_POST['arcadePressgame_tags']) ) {
					$arcadePressgame_name = $wpdb->escape($_POST['arcadePressgame_name']);
					$arcadePressgame_description = $wpdb->escape($_POST['arcadePressgame_description']);
					$arcadePressgame_type = $wpdb->escape($_POST['arcadePressgame_type']);
					$arcadePressgame_file = $wpdb->escape($_POST['arcadePressgame_file']);
					$arcadePressgame_width = $wpdb->escape($_POST['arcadePressgame_width']);
					$arcadePressgame_height = $wpdb->escape($_POST['arcadePressgame_height']);	
					$timestamp = date('Ymd');
					$arcadePressgame_thumbnail = $wpdb->escape($_POST['arcadePressgame_thumbnail']);
					$arcadePressgame_tags = $wpdb->escape($_POST['arcadePressgame_tags']);
					$cleanKey = $wpdb->escape($_GET['keytoedit']);
					
					$updateSQL = "
					UPDATE `{$table_name}` SET `name` = '{$arcadePressgame_name}',
					`description` = '{$arcadePressgame_description}',
					`type` = '{$arcadePressgame_type}',
					`file` = '{$arcadePressgame_file}',
					`tags` = '{$arcadePressgame_tags}',
					`dateadded` = '{$timestamp}',
					`width` = '{$arcadePressgame_width}',
					`height` = '{$arcadePressgame_height}',
					`thumbnail` = '{$arcadePressgame_thumbnail}'
					WHERE `primkey` ={$cleanKey} LIMIT 1 ;
					";

					$results = $wpdb->query($updateSQL);
					
					if($results===false) {
						echo '<div class="updated"><p><strong>';
						_e("ERROR 2: There was a problem with your form!  The database query was invalid. ", "ArcadePress");
						echo $wpdb->print_error();
						echo '</strong></p></div>';							
					} else { // If we get this far, we are still successful					
						echo '<div class="updated"><p><strong>';
						_e("Edit successful!  Your game details have been saved.", "ArcadePress");
						echo '</strong></p></div>';	
					} 
					
				}
				
				
				
				$keytoedit=$_GET['keytoedit'];	
				$grabrecord = "SELECT * FROM {$table_name} WHERE `primkey`={$keytoedit};";					
				
				$results = $wpdb->get_results( $grabrecord , ARRAY_A );		
				if(isset($results)) {
					foreach ($results as $result) {
						$arcadePressgame_name = stripslashes($result['name']);
						$arcadePressgame_description = stripslashes($result['description']);
						$arcadePressgame_type = stripslashes($result['type']);
						$arcadePressgame_file = stripslashes($result['file']);
						$arcadePressgame_width = stripslashes($result['width']);
						$arcadePressgame_height = stripslashes($result['height']);
						$arcadePressgame_thumbnail = stripslashes($result['thumbnail']);
						$arcadePressgame_tags = stripslashes($result['tags']);
					}
				} else {
					echo '<div class="updated"><p><strong>';
					echo "There was a problem loading the game you wish to edit.  The query was: {$grabrecord} ";
					echo '</strong></p></div>';					
				}
			}
			
			if (isset($_POST['addNewArcadePress_Game']) && $isanedit == false) {
			
				if (isset($_POST['arcadePressgame_name']) && isset($_POST['arcadePressgame_description']) && isset($_POST['arcadePressgame_type']) && isset($_POST['arcadePressgame_file']) && $_POST['arcadePressgame_width'] && $_POST['arcadePressgame_height'] && is_numeric($_POST['arcadePressgame_width']) && is_numeric($_POST['arcadePressgame_height']) && isset($_POST['arcadePressgame_thumbnail']) && isset($_POST['arcadePressgame_tags']) ) {
					$arcadePressgame_name = $wpdb->escape($_POST['arcadePressgame_name']);
					$arcadePressgame_description = $wpdb->escape($_POST['arcadePressgame_description']);
					$arcadePressgame_type = $wpdb->escape($_POST['arcadePressgame_type']);
					$arcadePressgame_file = $wpdb->escape($_POST['arcadePressgame_file']);
					$arcadePressgame_width = $wpdb->escape($_POST['arcadePressgame_width']);
					$arcadePressgame_height = $wpdb->escape($_POST['arcadePressgame_height']);	
					$timestamp = date('Ymd');
					$arcadePressgame_thumbnail = $wpdb->escape($_POST['arcadePressgame_thumbnail']);
					$arcadePressgame_tags = $wpdb->escape($_POST['arcadePressgame_tags']);
	
	
	
					$devOptions = $this->getAdminOptions();
					
					// Create our PAGE in draft mode in order to get the POST ID
					$my_post = array();
					$my_post['post_title'] = stripslashes($arcadePressgame_name);
					$my_post['post_type'] = 'page';
					$my_post['post_content'] = '';
					$my_post['post_status'] = 'draft';
					$my_post['post_author'] = 1;
					
					// Create the page as either directly under the main page or underneath a category page
					if($devOptions['creategameundercategory']==false) {
						$my_post['post_parent'] = $devOptions['mainpage'];
					} else {
						$theCategories = explode(',',$arcadePressgame_tags);
						
						if(isset($theCategories[0])) {
							$catSQL = "SELECT `ID` FROM `{$wpdb->prefix}posts` WHERE `post_title` LIKE '%{$theCategories[0]}%' AND `post_parent`={$devOptions['mainpage']} AND `post_status`='publish';";
							$catResults = $wpdb->get_results( $catSQL , ARRAY_A );	
							if(!isset($catResults[0]['ID'])) {
								$my_cat_post = array();
								$my_cat_post['post_title'] = stripslashes($theCategories[0]);
								$my_cat_post['post_type'] = 'page';
								$my_cat_post['post_content'] = '[arcadepress category="'.stripslashes($theCategories[0]).'"]';
								$my_cat_post['post_status'] = 'publish';
								$my_cat_post['post_author'] = 1;	
								$my_cat_post['post_parent'] = $devOptions['mainpage'];
								$theCatPostID = wp_insert_post( $my_cat_post );
							} else {
								$theCatPostID = $catResults[0]['ID'];
							}
							$my_post['post_parent'] = $theCatPostID;
						} else {
							$my_post['post_parent'] = $devOptions['mainpage'];
						}
					}

					// Insert the PAGE into the WP database
					$thePostID = wp_insert_post( $my_post );	
					if($thePostID==0) {
						echo '<div class="updated"><p><strong>';
						_e("ERROR 4: Wordpress didn't like your data and failed to create a page for it!", "ArcadePress");
						echo $wpdb->print_error();
						echo '</strong></p></div>';	
						return false;
					}
	
					// Now insert the game into the ArcadePress database
					$insert = "INSERT INTO {$table_name} (`primkey`, `name`, `description`, `type`, `file`, `tags`, `gameplays`, `dateadded`, `width`, `height`, `thumbnail`, `url`, `postid`) 
						VALUES (NULL, '{$arcadePressgame_name}', '{$arcadePressgame_description}', '{$arcadePressgame_type}', '{$arcadePressgame_file}', '{$arcadePressgame_tags}', '0', '{$timestamp}', '{$arcadePressgame_width}', '{$arcadePressgame_height}', '{$arcadePressgame_thumbnail}', '', '{$thePostID}');";					
					
					$results = $wpdb->query( $insert );
					$lastID = $wpdb->insert_id;
	
					// Now that we've inserted both the PAGE and the GAME, let's update and publish our post with the correct content
					$my_post = array();
					$my_post['ID'] = $thePostID;
					$my_post['post_content'] = '[arcadepress display="game" primkey="'.$lastID.'"]';
					$my_post['post_status'] = 'publish';
					wp_update_post( $my_post );

	

					if($results===false) {
						echo '<div class="updated"><p><strong>';
						_e("ERROR 2: There was a problem with your form!  The database query was invalid. ", "ArcadePress");
						echo $wpdb->print_error();
						echo '</strong></p></div>';							
					} else { // If we get this far, we are still successful					
						echo '<div class="updated"><p><strong>';
						_e("Your game details have been saved.", "ArcadePress");
						echo '</strong></p></div>';	
					}  
	
				} else {
				
					echo '<div class="updated"><p><strong>';
					_e("There was a problem with your form!  Did not save data.", "ArcadePress");
					echo '</strong></p></div>';				
				
				}


			
			}
		
			echo '
			<style type="text/css">
				.arcadepressoptions {
					float:left;
					border:1px solid #CCCCCC;
					padding: 4px 4px 4px 4px;
					margin: 2px 2px 2px 2px;
					width:300px;
					max-width:300px;
					min-height:110px;
				}
			</style>
			<div class="wrap">
			';
			
			if($isanedit==true) { // An edit's REQUEST_URL will already have the key appended, while a new game won't
				$codeForKeyToEdit = NULL;
			} else {
				$codeForKeyToEdit = '&keytoedit='.$keytoedit;
			}
			if(isset($lastID)) {
				$codeForKeyToEdit = '&keytoedit='.$lastID;
			}
			
			echo '
			<form method="post" action="'. $_SERVER["REQUEST_URI"].$codeForKeyToEdit.'" name="arcadepressaddgameform" id="arcadepressaddgameform">
			<div style="padding: 20px 10px 10px 10px;">
			<div style="float:left;"><img src="'.WP_PLUGIN_URL.'/arcadepress/images/logo.png" alt="arcadepress" /></div>
			<div style="float:left;">Please support ArcadePress by donating:
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3992HCPDPGQZJ" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" /></a></div>
			</div>
			<br style="clear:both;" /><br />
			<h2>Add or Edit a Game</h2>';
			
			echo '<table class="widefat">
			<thead><tr><th>Game Attribute</th><th>Value</th><th>Description</th></tr></thead><tbody>
			';
			
			echo '
			<tr>
			<td><h3>Game Name:</h3></td>
			<td><input type="text" name="arcadePressgame_name" style="width: 80%;" value="'.$arcadePressgame_name.'" /></td>
			<td><div style="width:300px;">The title of the game.</div></td>
			</tr>';			
			
			echo '
			<tr>
			<td><h3>Description:</h3></td>
			<td><textarea name="arcadePressgame_description" style="width: 80%;">'.$arcadePressgame_description.'</textarea>  </td>
			<td><div style="width:300px;">You should be very detailed and include not only the backstory of the game, but also helpful information like instructions, controls, and objectives.</div></td>
			</tr>';			
	
			echo '
			<tr>
			<td><h3>Tags</h3></td>
			<td><input type="text" name="arcadePressgame_tags" style="width: 200px;" value="'.$arcadePressgame_tags.'" />  </td>
			<td><div style="width:300px;">Comma seperated list of tags.  In ArcadePress, tags serve as categories.</div></td>
			</tr>';	
	
			echo '
			<tr>
			<td><h3>File:</h3></td>
			<td>URL: <input type="text" name="arcadePressgame_file" style="width: 200px;" value="'.$arcadePressgame_file.'" /> or<br />
			Upload a file: <span id="spanSWFUploadButton"></span>
			</td>
			<td><div style="width:300px;">Either a full URL to a file, or use the upload form to select a Flash file from your computer.  Max filesize is either: '.ini_get('post_max_size').' or '.ini_get('upload_max_filesize').', whichever is lower.</div></td>
			</tr>';			
			
			echo '
			<tr style="display:none;">
			<td><h3>File type:</h3></td>			
			<td><select name="arcadePressgame_type">
			  <option value="swf">Flash (SWF)</option>
			</select></td>
			<td><div style="width:300px;">Select if you are using a SWF, DCR, or FLV file (only SWF is supported at the moment.)</div></td>
			</tr>
			';
			
			echo '
			<tr>
			<td><h3>Game Thumbnail:</h3></td>
			<td>URL: <input type="text" name="arcadePressgame_thumbnail" style="width: 250px;" value="'.$arcadePressgame_thumbnail.'" /> or<br />
			Upload a file: <span id="spanSWFUploadButton2"></span>
			</td>
			<td><div style="width:300px;">Either a full URL to an image file, or use the upload form to select an image file from your computer.</div></td>
			</tr>';			
			
			echo '
			<tr>
			<td><h3>Width & Height:</h3></td>
			<td>Width: <input type="text" name="arcadePressgame_width" style="width: 58px;" value="'.$arcadePressgame_width.'" />  &nbsp; &nbsp; &nbsp; &nbsp; Height: <input type="text" name="arcadePressgame_height" style="width: 58px;" value="'.$arcadePressgame_height.'" /></td>
			<td><div style="width:300px;">The width and height of the embedded Flash element.  Make sure your theme supports this size of content!</div></td>
			</tr>';			
			
			echo '
			</tbody>
			</table>
			<br style="clear:both;" />
			<div class="submit">
			<input type="submit" name="addNewArcadePress_Game" value="'; _e('Submit Game', 'ArcadePress'); echo'" /></div>
			</form>
			 </div>';	
		
		}	
		// END Prints out the Add Games admin page =======================================================================		
		
		
		
		
		//Prints out the Edit Games admin page =======================================================================
        function printAdminPageEditGames() {
			global $wpdb, $user_level;

			// Apparently this code doesn't work
			//get_currentuserinfo();
			//if ($user_level <  8) {die('This page is not for you, but thanks for being sneaky and trying');};		
			if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
				die(__('Cheatin&#8217; uh?'));
			}			
			
			$table_name = $wpdb->prefix . "arcadepress_games";
			
			if(isset($_GET['keytodelete']) && is_numeric($_GET['keytodelete']))  {
				$sqlbeforedelete = "SELECT `postid` FROM {$table_name} WHERE `primkey`={$_GET['keytodelete']};";
				$theDeleteResults = $wpdb->get_results( $sqlbeforedelete , ARRAY_A );		
				
				if(isset($theDeleteResults)) { // Delete the post
					wp_delete_post($theDeleteResults[0]['postid']);
				}
				
				$wpdb->query("DELETE FROM `{$table_name}` WHERE `primkey`={$_GET['keytodelete']};");
				echo '<div class="updated"><p><strong>';
				_e("Game was removed from the database.", "ArcadePress");
				echo '</strong></p></div>';					
			}
			
			echo '
			<div class="wrap">
			<div style="padding: 20px 10px 10px 10px;">
			<div style="float:left;"><img src="'.WP_PLUGIN_URL.'/arcadepress/images/logo.png" alt="arcadepress" /></div>
			<div style="float:left;">Please support ArcadePress by donating:
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3992HCPDPGQZJ" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" /></a></div>
			</div>
			<br style="clear:both;" /><br />
			
			<h2>Edit Games</h2>
			
			<table class="widefat">
			<thead><tr><th>Action</th><th>Name</th><th>Description</th><th>Type</th><th>File</th><th>Width</th><th>Height</th><th>Thumbnail</th><th>Tags</th></tr></thead><tbody>
			';
			
			$startrecord = 0;
			if(isset($_GET['startrecord']) && is_numeric($_GET['startrecord'])) {
				$startrecord = $_GET['startrecord'];
			}
			$numberofrecords = 10;
			
			$totalrecordssql = "SELECT COUNT(`primkey`) AS num FROM `{$table_name}`";
			$totalrecordsres = $wpdb->get_results( $totalrecordssql , ARRAY_A );
			$totalrecords = $totalrecordsres[0]['num'];
			$numberofpages = ceil($totalrecords / $numberofrecords);
			
								

			echo '<div> Pages: ';
			$icounter = 0;
			while ($icounter < $numberofpages) {
				$pagenum = $icounter + 1;
				$offeset = $icounter * $numberofrecords;
				echo '<a href="admin.php?page=arcadepress-edit-games&startrecord='.$offeset.'">'.$pagenum.'</a> ';
				$icounter++;
			}
			echo '</div><br />';
			
			$grabrecord = "SELECT * FROM `{$table_name}` LIMIT {$startrecord}, {$numberofrecords};";
			
			$results = $wpdb->get_results( $grabrecord , ARRAY_A );		
			if(isset($results)) {
				foreach ($results as $result) {
					$arcadePressgame_name = $result['name'];
					$arcadePressgame_description = $result['description'];
					$arcadePressgame_type = $result['type'];
					$arcadePressgame_file = $result['file'];
					$arcadePressgame_width = $result['width'];
					$arcadePressgame_height = $result['height'];
					$arcadePressgame_thumbnail = $result['thumbnail'];
					$arcadePressgame_tags = $result['tags'];
					
					echo "<tr><td><a href=\"admin.php?page=arcadepress-add-games&keytoedit={$result['primkey']}\">Edit</a> | <a onclick=\"if (! confirm('Are you sure you want to delete this game?')) { return false;}\" href=\"admin.php?page=arcadepress-edit-games&keytodelete={$result['primkey']}\">Delete</a></td><td>".stripslashes($arcadePressgame_name)."</td><td>".stripslashes($arcadePressgame_description)."</td><td>{$arcadePressgame_type}</td><td>{$arcadePressgame_file}</td><td>{$arcadePressgame_width}</td><td>{$arcadePressgame_height}</td><td><img src=\"{$arcadePressgame_thumbnail}\" alt=\"\" style=\"max-width:50px;max-height:50px;\" /></td><td>".stripslashes($arcadePressgame_tags)."</td></tr>";
				

				}
			}			
			
			echo '
			</tbody></table>
			</div>
			';
		
		}		
		//END Prints out the Edit Games admin page =======================================================================
		
		
		
		
		// Dashboard widget code=======================================================================
		function arcadepress_main_dashboard_widget_function() {
			global $wpdb;
			
			$devOptions = $this->getAdminOptions();
			
			$table_name = $wpdb->prefix . "arcadepress_games";
			
			$totalrecordssql = "SELECT COUNT(`primkey`) AS num FROM `{$table_name}`";
			$totalrecordsres = $wpdb->get_results( $totalrecordssql , ARRAY_A );
			if(isset($totalrecordsres)) {
				$totalrecords = $totalrecordsres[0]['num'];		
			} else {
				$totalrecords = 0;
			}
			
			$totalgameplayssql = "SELECT SUM(`gameplays`)  AS num FROM `{$table_name}`;";
			$totalgameplaysres = $wpdb->get_results( $totalgameplayssql , ARRAY_A );
			if(isset($totalgameplaysres)) {
				$totalgameplays = $totalgameplaysres[0]['num'];		
			} else {
				$totalgameplays = 0;
			}			
			
			$permalink = get_permalink( $devOptions['mainpage'] );
			
			echo '<p>ArcadePress main page: <a href="'.$permalink.'" target="_blank">here</a></p>';
			echo "<p>Games installed: {$totalrecords} </p>";
			echo "<p>Number of times games played: {$totalgameplays}</p>";
		} 
		
		// Create the function use in the action hook
		function arcadepress_main_add_dashboard_widgets() {
			wp_add_dashboard_widget('arcadepress_main_dashboard_widgets', 'ArcadePress Overview', array(&$this, 'arcadepress_main_dashboard_widget_function'));	
		} 
		
		
		
		function  addHeaderCode() {

			echo '<!-- ArcadePress -->';
       
        }
		
		function  addContent($content = '') {
            $content .= "<p>ArcadePress</p>";
            return $content;
        }
				

		// Installation ==============================================================================================		
		function arcadepress_install() {
		   global $wpdb;
		   global $arcadepress_db_version;

		   $table_name = $wpdb->prefix . "arcadepress_games";
		   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			  
			$sql = "
				CREATE TABLE {$table_name} (
				`primkey` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
				`name` VARCHAR(255) NOT NULL, 
				`description` TEXT NOT NULL, 
				`type` VARCHAR(32) NOT NULL, 
				`file` VARCHAR(512) NOT NULL, 
				`tags` VARCHAR(512) NOT NULL, 
				`gameplays` INT(11) NOT NULL, 
				`dateadded` INT(8) NOT NULL, 
				`width` INT(4) NOT NULL, 
				`height` INT(4) NOT NULL, 
				`thumbnail` VARCHAR(255) NOT NULL, 
				`url` VARCHAR(255) NOT NULL,
				`postid` int(11) NOT NULL);			
			";
			  

			  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			  dbDelta($sql);
		
			  add_option("arcadepress_db_version", $arcadepress_db_version);

		   }
		}
		// END Installation ==============================================================================================
				

		// Shortcode =========================================
		function arcadepress_mainshortcode($atts) {
			global $wpdb;
			
			$table_name = $wpdb->prefix . "arcadepress_games";
		
			$devOptions = $this->getAdminOptions();		
		
			extract(shortcode_atts(array(
				'display' => 'categories',
				'primkey' => '0',
				'quantity' => '10',
				'usetext' => 'true',
				'usepictures' => 'false',
				'category' => ''
			), $atts));

			$output = '';
			if($category!='') {
				if(is_numeric($quantity)){
					$sql = "SELECT * FROM `{$table_name}` WHERE `tags` LIKE '%{$category}%' ORDER BY `dateadded` DESC LIMIT 0, {$quantity};";
					$results = $wpdb->get_results( $sql , ARRAY_A );
					if(isset($results)) {
						foreach ($results as $result) {
							$permalink = get_permalink( $result['postid'] ); // Grab the permalink based on the post id associated with the game
							if($usepictures=='true') {
								$output .= '<a href="'.$permalink.'"><img src="'.$result['thumbnail'].'" alt="'.$result['name'].'" /></a>';
							}
							if($usetext=='true') {
								$output .= '<p><a href="'.$permalink.'">'.$result['name'].'</a></p>';
							}
						}
					}
				} else {
					$output .= 'ArcadePress did not like your category shortcode!  The quantity field contained non-numeric data. Please fix your page or consult the ArcadePress documentation for help.';
				}				
			}
			switch ($display) {
				case 'recentgames': // Recent game shortcode =========================================================
					if(is_numeric($quantity)){
						$sql = "SELECT * FROM `{$table_name}` ORDER BY `dateadded` DESC LIMIT 0, {$quantity};";
						$results = $wpdb->get_results( $sql , ARRAY_A );
						if(isset($results)) {
							foreach ($results as $result) {
								$permalink = get_permalink( $result['postid'] ); // Grab the permalink based on the post id associated with the game
								if($usepictures=='true') {
									$output .= '<a href="'.$permalink.'"><img src="'.$result['thumbnail'].'" alt="'.$result['name'].'" /></a>';
								}
								if($usetext=='true') {
									$output .= '<p><a href="'.$permalink.'">'.$result['name'].'</a></p>';
								}
							}
						}
					} else {
						$output .= 'ArcadePress did not like your recentgames shortcode!  The quantity field contained non-numeric data. Please fix your page or consult the ArcadePress documentation for help.';
					}
					break;
				case 'topgames': // Top game shortcode =========================================================
					if(is_numeric($quantity)){
						$sql = "SELECT * FROM `{$table_name}` ORDER BY `gameplays` DESC LIMIT 0, {$quantity};";
						$results = $wpdb->get_results( $sql , ARRAY_A );
						if(isset($results)) {
							foreach ($results as $result) {
								$permalink = get_permalink( $result['postid'] ); // Grab the permalink based on the post id associated with the game
								if($usepictures=='true') {
									$output .= '<a href="'.$permalink.'"><img src="'.$result['thumbnail'].'" alt="'.$result['name'].'" /></a>';
								}
								if($usetext=='true') {
									$output .= '<p><a href="'.$permalink.'">'.$result['name'].'</a></p>';
								}
							}
						}
					} else {
						$output .= 'ArcadePress did not like your topgames shortcode!  The quantity field contained non-numeric data. Please fix your page or consult the ArcadePress documentation for help.';
					}
					break;					
				case 'categories': // Categories shortcode =========================================================
					if($devOptions['creategameundercategory']==true) {
						$sql = "SELECT `ID`, `post_title` FROM `{$wpdb->prefix}posts` WHERE `post_parent`={$devOptions['mainpage']} AND `post_status`='publish' ORDER BY `post_title` ASC";
						$results = $wpdb->get_results( $sql , ARRAY_A );
						if(isset($results)) {
							$output .= '<ul>';
							foreach ($results as $result) {
								$permalink = get_permalink( $result['ID'] ); // Grab the permalink based on the post id associated with the game
								$output .= '<li><a href="'.$permalink.'">'.$result['post_title'].'</a></li>';
							}
							$output .= '</ul>';
						}		
					} else {
						$output .= 'ArcadePress is configured to not use categories.';
					}
					break;
				case 'game': // Individual game shortcode =========================================================
					if(isset($primkey) && is_numeric($primkey)) {
						$sql = "SELECT * FROM `{$table_name}` WHERE `primkey`={$primkey};";
						$results = $wpdb->get_results( $sql , ARRAY_A );			
						if(isset($results)) {
							$newGamePlayValue = $results[0]['gameplays'] + 1; // Increment the play counter
							$updatedSQL = "UPDATE `{$table_name}` SET `gameplays` = '{$newGamePlayValue}' WHERE `primkey` ={$primkey} LIMIT 1 ;";
							$wpdb->query($updatedSQL);
							$output .= '
							  <script type="text/javascript">
								//<![CDATA[
								  swfobject.registerObject("ArcadePressFlashGame'.$results[0]['primkey'].'", "'.$devOptions['required_player_version'].'", "'.$devOptions['express_install_swf'].'");
								//]]>
							  </script>
							  <object id="ArcadePressFlashGame'.$results[0]['primkey'].'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$results[0]['width'].'" height="'.$results[0]['height'].'">
								<param name="movie" value="'.$results[0]['file'].'" />
								<param name="wmode" value="'.$devOptions['wmode'].'" />
								<param name="allowfullscreen" value="'.$devOptions['allowfullscreen'].'" />
								<!--[if !IE]>-->
								<object type="application/x-shockwave-flash" data="'.$results[0]['file'].'" width="'.$results[0]['width'].'" height="'.$results[0]['height'].'">
								<!--<![endif]-->
								  <p>'.$devOptions['alt'].'</p>
								<!--[if !IE]>-->
								</object>
								<!--<![endif]-->
							  </object>
							  <br />';
							  
							if($devOptions['showgamethumbnail']=='true') {
								$output .= '<img src="'.$results[0]['thumbnail'].'" alt="'.$results[0]['name'].'" /><br />';
							}
							
							if($devOptions['showgamedescription']=='true') {
								$output .= $results[0]['description'];
							}
							  
						} else {
							$output .= 'This game has been removed, but the shortcode associated with it was not.';
						}
					} else {
						$output .= 'ArcadePress did not like the primkey in your shortcode!  The primkey field contained non-numeric data. Please fix your page or consult the ArcadePress documentation for help.';
					}
					break;
			}			
			
			return $output;
		}
		// END SHORTCODE ================================================

		function add_script_swfobject($posts){
			if (empty($posts)) return $posts;
		 
			wp_enqueue_script('swfobject');

			return $posts;
		}		
		
		function my_admin_scripts(){
			global $APjavascriptQueue;
		 
			wp_enqueue_script('swfupload');

			if (session_id() == "") {@session_start();};
			
			$APjavascriptQueue .= '
			<script type="text/javascript">
			//<![CDATA[
			
			var gameUploadStartEventHandler = function (file) { 
				var continue_with_upload; 
				
				continue_with_upload = true; 

				return continue_with_upload; 
			}; 

			var gameUploadSuccessEventHandler = function (file, server_data, receivedResponse) { 
				document.arcadepressaddgameform.arcadePressgame_file.value = "'.get_option( 'siteurl' ).'/wp-content/uploads/arcadepress/games/" + file.name;
			}; 
			
			var gameUploadSuccessEventHandler2 = function (file, server_data, receivedResponse) { 
				document.arcadepressaddgameform.arcadePressgame_thumbnail.value = "'.get_option( 'siteurl' ).'/wp-content/uploads/arcadepress/images/" + file.name;
			}; 			
			
			function uploadError(file, errorCode, message) {
				try {

					switch (errorCode) {
					case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
						alert("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
						alert("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
						alert("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.IO_ERROR:
						alert("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
						alert("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
						alert("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
						alert("Error Code: The file was not found, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
						alert("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
						break;
					case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
						break;
					case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
						break;
					default:
						alert("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
						break;
					}
				} catch (ex) {
					this.debug(ex);
				}
			}

			function beginTheUpload(selected, addtoqueue, inqueuealready) {
				this.startUpload();
			}
			
			function debugSWFUpload (message) {
				try {
					if (window.console && typeof(window.console.error) === "function" && typeof(window.console.log) === "function") {
						if (typeof(message) === "object" && typeof(message.name) === "string" && typeof(message.message) === "string") {
							window.console.error(message);
						} else {
							window.console.log(message);
						}
					}
				} catch (ex) {
				}
				try {
					if (this.settings.debug) {
						this.debugMessage(message);
					}
				} catch (ex1) {
				}
			}
			
			var swfu; 
			var swfu2;
			window.onload = function () { 
				var settings_object = { 
					upload_url : "'.WP_PLUGIN_URL.'/arcadepress/php/upload.php", 
					post_params: {"PHPSESSID" : "'.session_id().'"},
					flash_url : "'.get_option( 'siteurl' ).'/wp-includes/js/swfupload/swfupload.swf", 
					file_size_limit : "200 MB",
					file_types : "*.swf",
					file_types_description : "SWF Flash files",
					file_upload_limit : "1",
					file_post_name: "Filedata",					
					button_placeholder_id : "spanSWFUploadButton",
					button_image_url : "'.WP_PLUGIN_URL.'/arcadepress/images/XPButtonUploadText_61x22.png",
					button_width: 61,
					button_height: 22,
					debug : false, 
					debug_handler : debugSWFUpload,
					file_dialog_complete_handler: beginTheUpload,
					upload_start_handler : gameUploadStartEventHandler, 
					upload_success_handler : gameUploadSuccessEventHandler,
					upload_error_handler : uploadError
				}; 
				
				var settings_object2 = { 
					upload_url : "'.WP_PLUGIN_URL.'/arcadepress/php/upload.php", 
					post_params: {"PHPSESSID" : "'.session_id().'"},
					flash_url : "'.get_option( 'siteurl' ).'/wp-includes/js/swfupload/swfupload.swf", 
					file_size_limit : "200 MB",
					file_types : "*.jpg;*.gif;*.png;",
					file_types_description : "Image files",
					file_upload_limit : "1",
					file_post_name: "Filedata",					
					button_placeholder_id : "spanSWFUploadButton2",
					button_image_url : "'.WP_PLUGIN_URL.'/arcadepress/images/XPButtonUploadText_61x22.png",
					button_width: 61,
					button_height: 22,
					debug : false, 
					debug_handler : debugSWFUpload,
					file_dialog_complete_handler: beginTheUpload,
					upload_start_handler : gameUploadStartEventHandler, 
					upload_success_handler : gameUploadSuccessEventHandler2,
					upload_error_handler : uploadError
				}; 				
				
				swfu = new SWFUpload(settings_object); 
				swfu2 = new SWFUpload(settings_object2); 
			};
			//]]>
			</script>			
			';

		}			
				
		function placeAdminHeaderCode() {
			global $APjavascriptQueue;
			echo $APjavascriptQueue;
		}
				
   
    }
 /**
 * ===============================================================================================================
 * End Main ArcadePress Class
 */	
} 
// The end of the IF statement


 
 
 

/**
 * ===============================================================================================================
 * ArcadePressTopGamesWidget SIDEBAR WIDGET
 */
if (class_exists("WP_Widget")) {
	class ArcadePressTopGamesWidget extends WP_Widget {
		/** constructor */
		function ArcadePressTopGamesWidget() {
			parent::WP_Widget(false, $name = 'ArcadePress Top Games');	
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {		
			global $wpdb;
			$table_name = $wpdb->prefix . "arcadepress_games";
		
			extract( $args );
			$title = apply_filters('widget_title', $instance['title']);
			$numberOfGamesToDisplay = empty($instance['numberOfGamesToDisplay']) ? '10' : $instance['numberOfGamesToDisplay'];
			$widgetShowGameImages = empty($instance['widgetShowGameImages']) ? 'false' : $instance['widgetShowGameImages'];

			echo $before_widget;
			if ( $title ) { echo $before_title . $title . $after_title; }
			if(is_numeric($numberOfGamesToDisplay)){
				$sql = "SELECT * FROM `{$table_name}` ORDER BY `gameplays` DESC LIMIT 0, {$numberOfGamesToDisplay};";
				$results = $wpdb->get_results( $sql , ARRAY_A );
				if(isset($results)) {
					foreach ($results as $result) {
						$permalink = get_permalink( $result['postid'] ); // Grab the permalink based on the post id associated with the game
						if($widgetShowGameImages=='true') {
							$output .= '<a href="'.$permalink.'"><img src="'.$result['thumbnail'].'" alt="'.$result['name'].'" /></a>';
						}
						$output .= '<p><a href="'.$permalink.'">'.$result['name'].'</a></p>';
					}
				}
			} else {
				$output .= 'ArcadePress did not like your widget!  The number of games to display contained non-numeric data. Please fix your widget or consult the ArcadePress documentation for help.';
			}
			echo $output;
			echo $after_widget;
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {	
			$instance['title']= strip_tags(stripslashes($new_instance['title']));
			$instance['numberOfGamesToDisplay'] = strip_tags(stripslashes($new_instance['numberOfGamesToDisplay']));
			$instance['widgetShowGameImages'] = strip_tags(stripslashes($new_instance['widgetShowGameImages']));

			return $instance;
		}

		/** @see WP_Widget::form */
		function form($instance) {				
			$title = esc_attr($instance['title']);
			$numberOfGamesToDisplay = htmlspecialchars($instance['numberOfGamesToDisplay']);
			$widgetShowGameImages = htmlspecialchars($instance['widgetShowGameImages']);

			echo '<p><label for="'. $this->get_field_id('title') .'">'; _e('Title:'); echo ' <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. $title .'" /></label></p>';
			echo '<p style="text-align:left;"><label for="' . $this->get_field_name('numberOfGamesToDisplay') . '">' . __('Number of games to display:') . ' <input style="width: 80px;" id="' . $this->get_field_id('numberOfGamesToDisplay') . '" name="' . $this->get_field_name('numberOfGamesToDisplay') . '" type="text" value="' . $numberOfGamesToDisplay . '" /></label></p>';
			//echo '<p style="text-align:left;"><label for="' . $this->get_field_name('widgetShowGameImages') . '">' . __('Show images:') . ' <input style="width: 200px;" id="' . $this->get_field_id('widgetShowGameImages') . '" name="' . $this->get_field_name('widgetShowGameImages') . '" type="text" value="' . $widgetShowGameImages . '" /></label></p>';
			echo '<p><label for="' . $this->get_field_name('widgetShowGameImages') . '">' . __('Show images:') . '<label for="' . $this->get_field_name('widgetShowGameImages') . '_yes"><input type="radio" id="' . $this->get_field_id('widgetShowGameImages') . '_yes" name="' . $this->get_field_name('widgetShowGameImages') . '" value="true" '; if ($widgetShowGameImages == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="' . $this->get_field_name('widgetShowGameImages') . '_no"><input type="radio" id="' . $this->get_field_id('widgetShowGameImages') . '_no" name="' . $this->get_field_name('widgetShowGameImages') . '" value="false" '; if ($widgetShowGameImages == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p>';
		}

	} 
	
	class ArcadePressRecentGamesWidget extends WP_Widget {
		/** constructor */
		function ArcadePressRecentGamesWidget() {
			parent::WP_Widget(false, $name = 'ArcadePress Recent Games');	
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {		
			global $wpdb;
			$table_name = $wpdb->prefix . "arcadepress_games";
		
			extract( $args );
			$title = apply_filters('widget_title', $instance['title']);
			$numberOfGamesToDisplay = empty($instance['numberOfGamesToDisplay']) ? '10' : $instance['numberOfGamesToDisplay'];
			$widgetShowGameImages = empty($instance['widgetShowGameImages']) ? 'false' : $instance['widgetShowGameImages'];

			echo $before_widget;
			if ( $title ) { echo $before_title . $title . $after_title; }
			if(is_numeric($numberOfGamesToDisplay)){
				$sql = "SELECT * FROM `{$table_name}` ORDER BY `dateadded` DESC LIMIT 0, {$numberOfGamesToDisplay};";
				$results = $wpdb->get_results( $sql , ARRAY_A );
				if(isset($results)) {
					foreach ($results as $result) {
						$permalink = get_permalink( $result['postid'] ); // Grab the permalink based on the post id associated with the game
						if($widgetShowGameImages=='true') {
							$output .= '<a href="'.$permalink.'"><img src="'.$result['thumbnail'].'" alt="'.$result['name'].'" /></a>';
						}
						$output .= '<p><a href="'.$permalink.'">'.$result['name'].'</a></p>';
					}
				}
			} else {
				$output .= 'ArcadePress did not like your widget!  The number of games to display contained non-numeric data. Please fix your widget or consult the ArcadePress documentation for help.';
			}
			echo $output;
			echo $after_widget;
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {	
			$instance['title']= strip_tags(stripslashes($new_instance['title']));
			$instance['numberOfGamesToDisplay'] = strip_tags(stripslashes($new_instance['numberOfGamesToDisplay']));
			$instance['widgetShowGameImages'] = strip_tags(stripslashes($new_instance['widgetShowGameImages']));

			return $instance;
		}

		/** @see WP_Widget::form */
		function form($instance) {				
			$title = esc_attr($instance['title']);
			$numberOfGamesToDisplay = htmlspecialchars($instance['numberOfGamesToDisplay']);
			$widgetShowGameImages = htmlspecialchars($instance['widgetShowGameImages']);

			echo '<p><label for="'. $this->get_field_id('title') .'">'; _e('Title:'); echo ' <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. $title .'" /></label></p>';
			echo '<p style="text-align:left;"><label for="' . $this->get_field_name('numberOfGamesToDisplay') . '">' . __('Number of games to display:') . ' <input style="width: 80px;" id="' . $this->get_field_id('numberOfGamesToDisplay') . '" name="' . $this->get_field_name('numberOfGamesToDisplay') . '" type="text" value="' . $numberOfGamesToDisplay . '" /></label></p>';
			//echo '<p style="text-align:left;"><label for="' . $this->get_field_name('widgetShowGameImages') . '">' . __('Show images:') . ' <input style="width: 200px;" id="' . $this->get_field_id('widgetShowGameImages') . '" name="' . $this->get_field_name('widgetShowGameImages') . '" type="text" value="' . $widgetShowGameImages . '" /></label></p>';
			echo '<p><label for="' . $this->get_field_name('widgetShowGameImages') . '">' . __('Show images:') . '<label for="' . $this->get_field_name('widgetShowGameImages') . '_yes"><input type="radio" id="' . $this->get_field_id('widgetShowGameImages') . '_yes" name="' . $this->get_field_name('widgetShowGameImages') . '" value="true" '; if ($widgetShowGameImages == "true") { _e('checked="checked"', "ArcadePress"); }; echo '/> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="' . $this->get_field_name('widgetShowGameImages') . '_no"><input type="radio" id="' . $this->get_field_id('widgetShowGameImages') . '_no" name="' . $this->get_field_name('widgetShowGameImages') . '" value="false" '; if ($widgetShowGameImages == "false") { _e('checked="checked"', "ArcadePress"); }; echo '/> No</label></p>';
		}

	} 	
	
	
}
/**
 * ===============================================================================================================
 * END ArcadePressTopGamesWidget SIDEBAR WIDGET
 */

 /**
 * ===============================================================================================================
 * ArcadePressCategoryGamesWidget SIDEBAR WIDGET
 */
if (class_exists("WP_Widget")) {
	class ArcadePressCategoryGamesWidget extends WP_Widget {
		/** constructor */
		function ArcadePressCategoryGamesWidget() {
			parent::WP_Widget(false, $name = 'ArcadePress Category List');	
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {		
			global $wpdb, $arcadePress;

			$devOptions = $arcadePress->getAdminOptions();
		
			extract( $args );
			$title = apply_filters('widget_title', $instance['title']);

			echo $before_widget;
			if ( $title ) { echo $before_title . $title . $after_title; }
			if($devOptions['creategameundercategory']==true) {
				$sql = "SELECT `ID`, `post_title` FROM `{$wpdb->prefix}posts` WHERE `post_parent`={$devOptions['mainpage']} AND `post_status`='publish' ORDER BY `post_title` ASC";
				$results = $wpdb->get_results( $sql , ARRAY_A );
				if(isset($results)) {
					$output .= '<ul>';
					foreach ($results as $result) {
						$permalink = get_permalink( $result['ID'] ); // Grab the permalink based on the post id associated with the game
						$output .= '<li><a href="'.$permalink.'">'.$result['post_title'].'</a></li>';
					}
					$output .= '</ul>';
				}	
			} else {
				$output .= 'ArcadePress is configured to not use categories.';
			}				
			echo $output;
			echo $after_widget;
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {	
			$instance['title']= strip_tags(stripslashes($new_instance['title']));

			return $instance;
		}

		/** @see WP_Widget::form */
		function form($instance) {				
			$title = esc_attr($instance['title']);

			echo '<p><label for="'. $this->get_field_id('title') .'">'; _e('Title:'); echo ' <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. $title .'" /></label></p>';
		}

	}
}	
 
 
 
 

 /**
 * ===============================================================================================================
 * Initialize the admin panel
 */
if (!function_exists("arcadePressAdminPanel")) {
    function arcadePressAdminPanel() {
        global $arcadePress;
        if (!isset($arcadePress)) {
            return;
        }
        if (function_exists('add_menu_page')) {
			add_menu_page('ArcadePress - Open Source Arcade CMS Plugin', 'ArcadePress', 9, 'arcadepress-admin', array(&$arcadePress, 'printAdminPage'), WP_PLUGIN_URL.'/arcadepress/images/controller.png');
			$page = add_submenu_page('arcadepress-admin','Add Game - ArcadePress ', 'Add Game', 9, 'arcadepress-add-games', array(&$arcadePress, 'printAdminPageAddGames'));
			add_submenu_page('arcadepress-admin','Edit Games - ArcadePress ', 'Edit Games', 9, 'arcadepress-edit-games', array(&$arcadePress, 'printAdminPageEditGames'));
			add_action( "admin_print_scripts-$page", array(&$arcadePress, 'my_admin_scripts') );
 }
    }   
}
 /**
 * ===============================================================================================================
 * END Initialize the admin panel
 */
 
 
 /**
 * ===============================================================================================================
 * Call everything
 */
if (class_exists("ArcadePress")) {
    $arcadePress = new ArcadePress();
}

//Actions and Filters   
if (isset($arcadePress)) {
    //Actions
	register_activation_hook(__FILE__, array(&$arcadePress, 'arcadepress_install')); // Install DB schema
	add_action('arcadepress/arcadepress.php',  array(&$arcadePress, 'init')); // Create options on activation
	add_action('admin_menu', 'arcadePressAdminPanel'); // Create admin panel
	add_action('wp_dashboard_setup', array(&$arcadePress, 'arcadepress_main_add_dashboard_widgets') ); // Dashboard widget
    add_action('wp_head', array(&$arcadePress, 'addHeaderCode'), 1); // Place ArcadePress comment into header
    add_action('widgets_init', create_function('', 'return register_widget("ArcadePressTopGamesWidget");')); // Register the widget: ArcadePressTopGamesWidget
	add_action('widgets_init', create_function('', 'return register_widget("ArcadePressRecentGamesWidget");')); // Register the widget: ArcadePressRecentGamesWidget
	add_action('widgets_init', create_function('', 'return register_widget("ArcadePressCategoryGamesWidget");'));
	add_shortcode('arcadepress', array(&$arcadePress, 'arcadepress_mainshortcode'));
	add_action('admin_head', array(&$arcadePress, 'placeAdminHeaderCode'), 1); // Place ArcadePress comment into header
 
   
    //Filters
	add_filter('the_posts', array(&$arcadePress, 'add_script_swfobject')); 
	//add_filter('the_posts', array(&$arcadePress, 'add_script_swfupload'));

}
 /**
 * ===============================================================================================================
 * Call everything
 */

?>