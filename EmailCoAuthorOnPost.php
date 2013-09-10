<?php
/*
Plugin Name: Email CoAuthor On Post
Version: 2.1
Plugin URI: http://mrdenny.com/go/EmailCoAuthorOnPost
Description: Emails other people when you publish a blog post
Author: Denny Cherry
Author URI: http://mrdenny.com/
*/

class emailcoauthor_class {

	function activation() {

		// Default options
		$emailcoauthor_options = array (
			'fromname' => 'Site Admin',
			'emailsubject' => 'Sample Subject.',
			'emailbody' => 'This is the default message which will be sent out. You should customize this as needed.',
		'advertiseplugin' => '',
		'includeadmin' => '',
		'emailcoauthor_donate' => '',
		'allto' => '',
		'sendhtml' => ''
		);
    
		// Add options
		add_option('emailcoauthor_options', $emailcoauthor_options);

	 }

	function post_install_message() {

		$options = get_option('emailcoauthor_options');

		if ($options['fromname'] == 'Site Admin' && $options['emailsubject'] == 'Sample Subject.') {
			echo '<div if="message" class="error"><p>This plugin "Email GoAuthor On Post" needs to be <a href="options-general.php?

page=emailcoauthor">configured</a> before it can be used.  Please correct the configuration before having the plugin sending emails.</div>';
		} 
	}

		function EmailCoAuthor($new_status, $old_status, $post)  {
			if ($old_status=='publish') { //It's already published so do nothing.
			//echo "<div if='message' class='warning'><p>No email sent as this was being republished.</p></div>";
			return;
			}

			if ($new_status <> 'publish') { //Do nothing as this isn't published.
			return;
			}

			$post_var = get_object_vars($post);
			$post_id = $post_var['post_id'];

			$friends = get_post_custom_values('EmailTo', $post_id); 

			$domain = get_option('home');
			$admin_email = get_option('admin_email');
	    
			$settings = get_option('emailcoauthor_options');
			$email_to = get_post_custom_values('EmailToName', $post_id);
			$email_subject = get_post_custom_values('EmailSubject', $post_id);
			$email_body = get_post_custom_values('EmailBody', $post_id);

			if (empty($friends) && empty($settings['emailcoauthor_includeadmin']) && empty($settings['allto'])) {
			return;
			}

			// Set the from name
			if (!empty($settings['fromname'])) {
			$from = $settings['fromname'];
			$from = "From: $from <$admin_email>";
			} else {
			$from = "From: \"Site Admin\" <$admin_email>";
			}

			$header[] = $from;
			if (!empty($settings['sendhtml'])) {
			$header[] = 'content-type: text/html';
			}

			// Set the subject
				  if (!empty($email_subject[0])) {
			$subject = $email_subject[0];
			} else {
			$subject = $settings['emailsubject'];
			}

			// Set the message body
			if (!empty($email_body[0])) {
			$body = $email_body[0];
			} else {
			$body = $settings['emailbody'];
			}

			// Personalize as needed
				 if (!empty($email_to[0])) {

			if (!empty($settings['sendhtml'])) {
			$body = "$email_to[0],
	<br>
	$body";
			} else {
				$body = "$email_to[0],
	$body";
			}
		   }

			// Put an add on the email if allowed
				 if (!empty($settings['emailcoauthor_advertiseplugin'])) {
			if (!empty($settings['sendhtml'])) {
				$body = "$body<P>";
			}
			$body = "$body

	This email was sent via the \"Email CoAuthor On Post\" WordPress Plugin.  You can find out more about this plugin at 

http://mrdenny.com/go/EmailCoAuthorOnPost.";
		   }

		 //Swap out variables.
		$subject = str_replace('$domain', get_site_url(), $subject);
		$subject = str_replace('$post_url', get_permalink(), $subject);
		$subject = str_replace('$title', get_the_title(), $subject);

		$body = str_replace('$domain', get_site_url(), $body);
		$body = str_replace('$post_url', get_permalink(), $body);
		$body = str_replace('$title', get_the_title(), $body);

		// Send the emails or send an error email to the admin.
		if (!empty($subject) || !empty($body)) {
			if (!empty($settings['allto'])) {
				$allto = $settings['allto'];
				wp_mail($allto, $subject, $body, $header);
			}
			//Haven't decided if I like this or not. Maybe make this an option later.
			//if (empty($friends)) {
			//	wp_mail($admin_email, $subject, $body, $header);
			//}

			foreach ( $friends as $key => $value ) {
				if (isset($settings['emailcoauthor_includeadmin'] )) {
					if (empty($value)) {
						$value = $admin_email.', test2@mrdenny.com';
					} else {
						$value = $value.', '.$admin_email;
					}
				}
				wp_mail($value, $subject, $body, $header);
			}
 			} else {
				wp_mail($admin_email, 'Error with EmailCoAuthorOnPost plugin', 'The plugin EmailCoAuthorOnPost on $domain is not 

configured correctly.  Please check the configuration to resolve this issue.', $header);
			}
		}






	// Add "Settings" link to the plugins page

	function pluginmenu ($links, $file) {
		if ( $file != plugin_basename( __FILE__ ))
			return $links;

		$options = get_option('emailcoauthor_options');
		if (empty($options['emailcoauthor_donate'])) {
			$links[] = '<a href="http://mrdenny.com/go/EmailCoAuthorOnPost">' . __('Donate','') . '</a>';
		}

		return $links;
	}

	function action_links( $links, $file ) {
		if ( $file != plugin_basename( __FILE__ ))
			return $links;

		$settings_link = sprintf( '<a href="options-general.php?page=emailcoauthor">%s</a>', __( 'Settings', '' ) );

		array_unshift( $links, $settings_link );

		return $links;
	}

	function menu() {
		 add_submenu_page('options-general.php', 'Email CoAuthor On Post Settings', 'Email CoAuthor On Post', 
	'manage_options', 'emailcoauthor', array('emailcoauthor_class','options_page'));

	}

	// Display options page
	function options_page() {
		?>
		<div class="wrap">
		<h2><?php _e('Email Settings', TEXT_DOMAIN ); ?></h2>
        
			<form action="options.php" method="post">
				<?php settings_fields('emailcoauthor_options'); ?>
				<?php do_settings_sections('emailcoauthor'); ?>
				<p class="submit">
					<input name="submit" type="submit" class="button-primary" value="<?php _e('Save Changes', 

TEXT_DOMAIN ); ?>" />
				</p>
			</form>
	The "From Name", "Email Subject" and "Email Body" settings are required.  They must be filled out for the plugin to work correctly.  Even if you plan 

on using different values for the subject and body in every blog post, these values must be filled in with default values.
		</div>
		<?php
	}


	// Register settings, add sections and fields
	function admin_init(){

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __('You are not allowed to access this part of the site') );
		}


		register_setting( 'emailcoauthor_options', 'emailcoauthor_options', array(&$this,'validate') );
		add_settings_section('emailcoauthor_main', __( 'Settings', '' ), array(&$this, 'section'), 'emailcoauthor');
		add_settings_field('fromname', __( 'From Name:', '' ), array(&$this, 'fromname'), 'emailcoauthor', 'emailcoauthor_main');
		add_settings_field('allto', __( 'Notifiy On Publishing Of All Posts:', ''), array(&$this, 'allto'), 'emailcoauthor', 'emailcoauthor_main');
		add_settings_field('emailsubject', __( 'Email Subject:', '' ), array(&$this, 'emailsubject'), 'emailcoauthor', 'emailcoauthor_main');
		add_settings_field('emailbody', __( 'Email Body:', '' ), array(&$this, 'emailbody'), 'emailcoauthor', 'emailcoauthor_main');
		add_settings_field('sendhtml', __( '', ''), array(&$this, 'sendhtml'), 'emailcoauthor', 'emailcoauthor_main');
		add_settings_field('advertiseplugin', __( '', ''), array(&$this, 'advertise'), 'emailcoauthor', 'emailcoauthor_main');
		add_settings_field('includeadmin', __( '', ''), array(&$this, 'includeadmin'), 'emailcoauthor', 'emailcoauthor_main');


	// This setting should always be last. Don't move it up.
		add_settings_field('emailcoauthor_donate', __( '', ''), array(&$this, 'donate'), 'emailcoauthor', 'emailcoauthor_main');
	}

	function section() {
		echo '<p>' . __( 'Please enter your default email settings.', TEXT_DOMAIN ) . '</p>';
	}

	function fromname() {
		$options = get_option('emailcoauthor_options');
		echo "<input id='fromname' name='emailcoauthor_options[fromname]' type='text' class='regular-text' value='{$options['fromname']}' />";
	}


	function allto() {
		$options = get_option('emailcoauthor_options');
		echo "<input id='allto' name='emailcoauthor_options[allto]' type='text' class='regular-text' value='{$options['allto']}' />";
	}

	function emailsubject() {
		$options = get_option('emailcoauthor_options');
		echo "<input id='emailsubject' name='emailcoauthor_options[emailsubject]' type='text' class='regular-text' value='{$options

['emailsubject']}' />";
	}

	function emailbody() {
		$options = get_option('emailcoauthor_options');
		echo "<textarea id='emailbody' name='emailcoauthor_options[emailbody]' rows='10' cols='120'/>{$options['emailbody']}</textarea>";
	}

	function advertise() {
		$options = get_option('emailcoauthor_options');
		echo "<input id='emailcoauthor_advertiseplugin' name='emailcoauthor_options[emailcoauthor_advertiseplugin]' type='checkbox' 

value='yes'";
		if (isset($options['emailcoauthor_advertiseplugin'])) {
			echo " checked";
		}
		echo "/> Emails which are sent may include a reference to the plugin.";
	}

	function sendhtml() {
		$options = get_option('emailcoauthor_options');
		echo "<input id='sendhtml' name='emailcoauthor_options[sendhtml]' type='checkbox' value='yes'";
		if (isset($options['sendhtml'])) {
		echo " checked";
		}
		echo "/> Send Email As HTML";
	}


	function includeadmin() {
		$options = get_option('emailcoauthor_options');
		echo "<input id='emailcoauthor_includeadmin' name='emailcoauthor_options[emailcoauthor_includeadmin]' type='checkbox' 

value='yes'";
		if (isset($options['emailcoauthor_includeadmin'])) {
			echo " checked";
		}
		echo "/> Include the admin account on all emails sent? (Good for testing and verification)";
	}

	function donate() {
		$options = get_option('emailcoauthor_options');
		if (empty($options['emailcoauthor_donate'])) {
			echo "<input id='emailcoauthor_donate' name='emailcoauthor_options[emailcoauthor_donate]' type='checkbox' value='yes'/> I 

have <a href=\"http://mrdenny.com/go/EmailCoAuthorOnPost\">donated</a> to the support of this plugin.";
		} else {
			echo "<input id='emailcoauthor_donate' name='emailcoauthor_options[emailcoauthor_donate]' type='hidden' value='yes'/>";
		}

	}

	function validate($input) {
		$emailcoauthor_options = get_option('emailcoauthor_options');

		if ($input['fromname'] == ''){
			$input['fromname'] = $emailcoauthor_options['fromname'];
		}

		if ($input['emailsubject'] == ''){
			$input['emailsubject'] = $emailcoauthor_options['emailsubject'];
		}

		if ($input['emailbody'] == ''){
			$input['emailbody'] = $emailcoauthor_options['emailbody'];
		}

		return $input;
	}

	function meta_box () {
		$emailcoauthor_c = new emailcoauthor_class();
		add_meta_box ('email-coauthor-on-post', esc_html__('Email CoAuthor On Post', 'Email CoAuthor On Post'), array($emailcoauthor_c, 'meta_box_draw'), 'post', 'side', 'default');

	}

	function meta_box_draw ($object, $box) {
		$EmailToName = get_post_custom_values('EmailToName', $post_id);
		$EmailTo = get_post_custom_values('EmailTo', $post_id);
		$EmailSubject = get_post_custom_values('EmailSubject', $post_id);
		$EmailBody = get_post_custom_values('EmailBody', $post_id);

		echo "<table>";
		echo "<tr><td>Name of person to email:</td><td>";
			if (count($EmailToName) == 0 || 1) {
				echo "<input class='widefat' type='text' name='EmailToName' value='";
				echo $EmailToName[0];
				echo "'>";
			} else {
				echo "<input class='widefat' type='text' name='EmailToName' value='Disabled: Edit via Custom Fields' disabled>";
				echo "<input type='hidden' name='EmailToName_Disabled' value='true' />";
			}
		echo "</td></tr>";
		echo "<tr><td>Email address of person to email:</td><td>";
			if (count($EmailTo) == 0 || 1) {
				echo "<input class='widefat' type='text' name='EmailTo' value='";
				echo $EmailTo[0];
				echo "'>";
			} else {
				echo "<input class='widefat' type='text' name='EmailTo' value='Disabled: Edit via Custom Fields' disabled>";
				echo "<input type='hidden' name='EmailTo_Disabled' value='true' />";
			}
		echo "</td></tr>";
		echo "<tr><td>Custom Subject:</td><td><input class='widefat' type='text' name='EmailSubject' value='";
			echo $EmailSubject[0];
		echo "'></td></tr>";
		ECHO "<tr><td>Custom Body:</td><td><textarea id='emailbody' name='EmailBody' rows='10' cols='30'/>";
			ECHO $EmailBody[0];
		ECHO "</textarea>";
		echo "</table>";
	}

	function meta_box_save($post_id, $post) {
		if ($_POST['EmailTo_Disabled'] == "") {
			//Save the EmailTo Field
			if ($_POST['EmailTo']) {
				update_post_meta($post_id, 'EmailTo', $_POST['EmailTo']);
			} else {
				delete_post_meta ($post_id, 'EmailTo');
			}
		}
		if ($_POST['EmailToName_Disabled'] == "") {
			//Save the EmailToName Field
			if ($_POST['EmailToName']) {
				update_post_meta($post_id, 'EmailToName', $_POST['EmailToName']);
			} else {
				delete_post_meta ($post_id, 'EmailToName');
			}
		}
		if ($_POST['EmailSubject']) {
			update_post_meta($post_id, 'EmailSubject', $_POST['EmailSubject']);
		} else {
			delete_post_meta ($post_id, 'EmailSubject');
		}
		if ($_POST['EmailBody']) {
			update_post_meta($post_id, 'EmailBody', $_POST['EmailBody']);
		} else {
			delete_post_meta ($post_id, 'EmailBody');
		}
	}

	function meta_box_setup() {
		$emailcoauthor_c = new emailcoauthor_class();
		add_action('add_meta_boxes', array($emailcoauthor_c, 'meta_box'));

		add_action( 'save_post', array($emailcoauthor_c, 'meta_box_save'), 10, 2 );

	}
} //End Class

$emailcoauthor_c = new emailcoauthor_class();

//add_action('publish_post', array($emailcoauthor_c, 'EmailCoAuthor'));
add_action('transition_post_status', array($emailcoauthor_c, 'EmailCoAuthor'), 10, 3); 
add_action('admin_menu', array($emailcoauthor_c, 'menu'));
add_filter('plugin_action_links', array($emailcoauthor_c, 'action_links'),10,2);
add_action('admin_init', array($emailcoauthor_c, 'admin_init'), 1);
register_activation_hook(__FILE__, array($emailcoauthor_c, 'activation'));
add_filter('plugin_row_meta', array($emailcoauthor_c, 'pluginmenu'),10,2);
add_action('admin_head', array($emailcoauthor_c, 'post_install_message')); 
add_action( 'load-post.php', array($emailcoauthor_c, 'meta_box_setup' ));
add_action( 'load-post-new.php', array($emailcoauthor_c, 'meta_box_setup' ));