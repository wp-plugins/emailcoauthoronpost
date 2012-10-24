<?php
/*
Plugin Name: Email CoAuthor On Post
Version: 1.0
Plugin URI: http://mrdenny.com/go/EmailCoAuthorOnPost
Description: Emails other people when you publish a blog post
Author: Denny Cherry
Author URI: http://mrdenny.com/
*/

function emailcoauthor_activation() {

    // Default options
    $emailcoauthor_options = array (
        'fromname' => 'Site Admin',
        'emailsubject' => 'Your $domain blog post has been published.',
        'emailbody' => 'The blog post which we worked on together was just published at $domain.',
	'advertiseplugin' => '',
	'includeadmin' => '',
	'emailcoauthor_donate' => '',
	'allto' => '',
	'sendhtml' => ''
    );
    
    // Add options
    add_option('emailcoauthor_options',$smtp_options);
 }

	function EmailCoAuthor($post_id)  {
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

This email was sent via the \"Email CoAuthor On Post\" WordPress Plugin.  You can find out more about this plugin at http://mrdenny.com/go/EmailCoAuthorOnPost.";
	   }

	    // Send the emails or send an error email to the admin.
	    if (!empty($subject) || !empty($body)) {
		if (!empty($settings['allto'])) {
			wp_mail($settings['allto'], $subject, $body, $header);
		}

		if (empty($friends)) {
			wp_mail($admin_email, $subject, $body, $header);
		}
		foreach ( $friends as $key => $value ) {
		    if (isset( $settings['emailcoauthor_includeadmin'] ) && !empty($settings['emailcoauthor_includeadmin'])) {
			if (empty($value)) {
				$value = $admin_email;
			} else {
				$value = $value.', '.$admin_email;
			}
		    }
		    wp_mail($value, $subject, $body, $header);
		}
  	    } else {
		wp_mail($admin_email, 'Error with EmailCoAuthorOnPost plugin', 'The plugin EmailCoAuthorOnPost on $domain is not configured correctly.  Please check the configuration to resolve this issue.', $header);
	    }
	}






// Add "Settings" link to the plugins page

function emailcoauthor_pluginmenu ($links, $file) {
	$options = get_option('emailcoauthor_options');
	if (empty($options['emailcoauthor_donate'])) {
		$links[] = '<a href="http://mrdenny.com/go/EmailCoAuthorOnPost">' . __('Donate','') . '</a>';
	}

	return $links;
}

function emailcoauthor_action_links( $links, $file ) {
    if ( $file != plugin_basename( __FILE__ ))
        return $links;

    $settings_link = sprintf( '<a href="options-general.php?page=emailcoauthor">%s</a>', __( 'Settings', '' ) );

    array_unshift( $links, $settings_link );

    return $links;
}

function emailcoauthor_menu() {
     add_submenu_page('options-general.php', 'Email CoAuthor On Post Settings', 'Email CoAuthor On Post', 
'manage_options', 'emailcoauthor', 'emailcoauthor_options_page');

}

// Display options page
function emailcoauthor_options_page() {
    ?>
    <div class="wrap">
    <h2><?php _e('Email Settings', TEXT_DOMAIN ); ?></h2>
        
        <form action="options.php" method="post">
            <?php settings_fields('emailcoauthor_options'); ?>
            <?php do_settings_sections('emailcoauthor'); ?>
            <p class="submit">
                <input name="submit" type="submit" class="button-primary" value="<?php _e('Save Changes', TEXT_DOMAIN ); ?>" />
            </p>
        </form>
The "From Name", "Email Subject" and "Email Body" settings are required.  They must be filled out for the plugin to work correctly.  Even if you plan on using different values for the subject and body in every blog post, these values must be filled in with default values.
    </div>
    <?php
}


// Register settings, add sections and fields
function emailcoauthor_admin_init(){
    register_setting( 'emailcoauthor_options', 'emailcoauthor_options', 'emailcoauthor_validate' );
    add_settings_section('emailcoauthor_main', __( 'Settings', '' ), 'emailcoauthor_section', 'emailcoauthor');
    add_settings_field('fromname', __( 'From Name:', '' ), 'emailcoauthor_fromname', 'emailcoauthor', 'emailcoauthor_main');
    add_settings_field('allto', __( 'Notifiy On Publishing Of All Posts:', ''), 'emailcoauthor_allto', 'emailcoauthor', 'emailcoauthor_main');
    add_settings_field('emailsubject', __( 'Email Subject:', '' ), 'emailcoauthor_emailsubject', 'emailcoauthor', 'emailcoauthor_main');
    add_settings_field('emailbody', __( 'Email Body:', '' ), 'emailcoauthor_emailbody', 'emailcoauthor', 'emailcoauthor_main');
    add_settings_field('sendhtml', __( '', ''), 'emailsoauthor_sendhtml', 'emailcoauthor', 'emailcoauthor_main');
    add_settings_field('advertiseplugin', __( '', ''), 'emailcoauthor_advertise', 'emailcoauthor', 'emailcoauthor_main');
    add_settings_field('includeadmin', __( '', ''), 'emailcoauthor_includeadmin', 'emailcoauthor', 'emailcoauthor_main');


// This setting should always be last. Don't move it up.
    add_settings_field('emailcoauthor_donate', __( '', ''), 'emailcoauthor_donate', 'emailcoauthor', 'emailcoauthor_main');
}

function emailcoauthor_section() {
    echo '<p>' . __( 'Please enter your default email settings.', TEXT_DOMAIN ) . '</p>';
}

function emailcoauthor_fromname() {
    $options = get_option('emailcoauthor_options');
    echo "<input id='fromname' name='emailcoauthor_options[fromname]' type='text' class='regular-text' value='{$options['fromname']}' />";
}


function emailcoauthor_allto() {
	$options = get_option('emailcoauthor_options');
	echo "<input id='allto' name='emailcoauthor_options[allto]' type='text' class='regular-text' value='{$options['allto']}' />";
}

function emailcoauthor_emailsubject() {
    $options = get_option('emailcoauthor_options');
    echo "<input id='emailsubject' name='emailcoauthor_options[emailsubject]' type='text' class='regular-text' value='{$options['emailsubject']}' />";
}

function emailcoauthor_emailbody() {
    $options = get_option('emailcoauthor_options');
    echo "<textarea id='emailbody' name='emailcoauthor_options[emailbody]' rows='5' cols='60'/>{$options['emailbody']}</textarea>";
}

function emailcoauthor_advertise() {
    $options = get_option('emailcoauthor_options');
    echo "<input id='emailcoauthor_advertiseplugin' name='emailcoauthor_options[emailcoauthor_advertiseplugin]' type='checkbox' value='yes'";
    if (isset($options['emailcoauthor_advertiseplugin'])) {
        echo " checked";
    }
    echo "/> Emails which are sent may include a reference to the plugin.";
}

function emailsoauthor_sendhtml() {
    $options = get_option('emailcoauthor_options');
    echo "<input id='sendhtml' name='emailcoauthor_options[sendhtml]' type='checkbox' value='yes'";
    if (isset($options['sendhtml'])) {
	echo " checked";
    }
    echo "/> Send Email As HTML";
}


function emailcoauthor_includeadmin() {
    $options = get_option('emailcoauthor_options');
    echo "<input id='emailcoauthor_includeadmin' name='emailcoauthor_options[emailcoauthor_includeadmin]' type='checkbox' value='yes'";
    if (isset($options['emailcoauthor_includeadmin'])) {
        echo " checked";
    }
    echo "/> Include the admin account on all emails sent? (Good for testing and verification)";
}

function emailcoauthor_donate() {
    $options = get_option('emailcoauthor_options');
    if (empty($options['emailcoauthor_donate'])) {
        echo "<input id='emailcoauthor_donate' name='emailcoauthor_options[emailcoauthor_donate]' type='checkbox' value='yes'/> I have <a href=\"http://mrdenny.com/go/EmailCoAuthorOnPost\">donated</a> to the support of this plugin.";
    } else {
        echo "<input id='emailcoauthor_donate' name='emailcoauthor_options[emailcoauthor_donate]' type='hidden' value='yes'/>";
    }

}

function emailcoauthor_validate($input) {
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


add_action('publish_post', 'EmailCoAuthor');
add_action('admin_menu', 'emailcoauthor_menu');
add_filter('plugin_action_links', 'emailcoauthor_action_links',10,2);
add_action('admin_init','emailcoauthor_admin_init');
add_action('plugins_loaded', 'emailcoauthor_activation' );
register_activation_hook(__FILE__,'emailcoauthor_activation');
add_filter('plugin_row_meta', 'emailcoauthor_pluginmenu',10,2);