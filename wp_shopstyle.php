<?php
/*
Plugin Name: WP-ShopStyle-API
Version: 1.0.0
Plugin URI: 
Description: Allows interaction with ShopStyle API to configure a URL
Author: Scott Baeder
Author URI:
*/

/**
 * @author Scott Baeder
 * @copyright Scott Baeder, 2015-
 * @license GPL v2
 */

add_action( 'init', 'wpss_api_register_my_cpts' );

add_action( 'admin_menu', 'wpss_api_add_admin_menu' );
add_action( 'admin_init', 'wpss_api_settings_init' );

function wpss_api_register_my_cpts() {
	$labels = array(
		"name" => "ShopStyle_Lists",
		"singular_name" => "ShopStyle_List",
		"menu_name" => "ShopStyle List",
		"all_items" => "All ShopStyle Lists",
		"add_new" => "Add New",
		"add_new_item" => "Add New List",
		"edit" => "Edit",
		"edit_item" => "Edit List",
		"new_item" => "New List",
		"view" => "View",
		"view_item" => "View List",
		"search_items" => "Search ShopStyle Lists",
		);

	$args = array(
		"labels" => $labels,
		"description" => "Custom type to manage the information about a ShopStyle List (i.e. the ID number and updates, etc.)",
		"public" => true,
		"show_ui" => true,
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "shopstyle", "with_front" => true ),
		"query_var" => true,
		"menu_position" => 30,"menu_icon" => "dashicons-admin-page",		
		"supports" => array( "title", "editor", "custom-fields", "page-attributes" ),		
		"taxonomies" => array( "category" )
	);
	register_post_type( "shopstyle", $args );

// End of wpss_api_register_my_cpts()
}


function wpss_api_add_admin_menu(  ) { 

	add_submenu_page( 'edit.php?post_type=shopstyle',  'WP_ShopStyle_API', 'WP_ShopStyle_API', 'manage_options', 'wp_shopstyle_api', 'wpss_api_options_page' );
		
	}


function wpss_api_settings_init(  ) { 

	register_setting( 'wpss_api_pluginPage', 'wpss_api_settings' );

	add_settings_section('wpss_api_pluginPage_section', 
		__( 'WP_ShopStyle_API: Basic Settings', 'wordpress' ), 
		'wpss_api_settings_section_callback', 
		'wpss_api_pluginPage'
	);
	add_settings_field( 'shopstyle_username', 
		__( 'ShopStyle Username', 'wordpress' ), 
		'shopstyle_username_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
	add_settings_field( 'shopstyle_pid', 
		__( 'ShopStyle UID / API Key', 'wordpress' ), 
		'shopstyle_pid_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
	add_settings_field( 'shopstyle_format', 
		__( 'Output format', 'wordpress' ), 
		'shopstyle_format_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
	add_settings_field( 'shopstyle_limit', 
		__( 'Number of results to return.<br>*Recommended max of 20', 'wordpress' ), 
		'shopstyle_limit_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
	add_settings_field( 'shopstyle_offset', 
		__( 'Starting index of results.<br>*Start of page "1" is at 0', 'wordpress' ), 
		'shopstyle_offset_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
		add_settings_field( 'shopstyle_method', 
		__( 'API Method to use', 'wordpress' ), 
		'shopstyle_method_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
	add_settings_field( 'shopstyle_list_id', 
		__( 'List-ID  *(used when getting items from list)', 'wordpress' ), 
		'shopstyle_list_id_render', 
		'wpss_api_pluginPage', 
		'wpss_api_pluginPage_section' 
	);
	add_settings_section('wpss_api_pluginPage_section2', 
		__( 'Generated ShopStyle API', 'wordpress' ), 
		'wpss_api_settings_section2_callback', 
		'wpss_api_pluginPage2'
	);
}


function shopstyle_username_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<input type='text' name='wpss_api_settings[shopstyle_username]' value='<?php echo $options['shopstyle_username']; ?>'>
	<?php
}


function shopstyle_pid_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<input type='text' name='wpss_api_settings[shopstyle_pid]' value='<?php echo $options['shopstyle_pid']; ?>'>
	<?php
}


function shopstyle_format_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<form>
	<input type='radio' name='wpss_api_settings[shopstyle_format]' <?php checked( $options['shopstyle_format'], 'xml' ); ?> value='xml' checked > XML
	&nbsp; &nbsp; &nbsp; &nbsp; 
	<input type='radio' name='wpss_api_settings[shopstyle_format]' <?php checked( $options['shopstyle_format'], 'json' ); ?> value='json'> JSON
	</form>
	<?php
}


function shopstyle_limit_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<input type='text' name='wpss_api_settings[shopstyle_limit]' value='<?php echo $options['shopstyle_limit']; ?>'>
	<?php
}


function shopstyle_offset_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<input type='text' name='wpss_api_settings[shopstyle_offset]' value='<?php echo $options['shopstyle_offset']; ?>'>
	<?php
}


function shopstyle_method_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<select name='wpss_api_settings[shopstyle_method]'>
		<option value='Lists' 
			<?php selected( $options['shopstyle_method'], 'Lists' ); ?>>Lists
		</option>
		<option value='List Items' 
			<?php selected( $options['shopstyle_method'],'List Items' ); ?>>List Items
		</option>
	</select>
<?php

}


function shopstyle_list_id_render(  ) { 
	$options = get_option( 'wpss_api_settings' );
	?>
	<input type='text' name='wpss_api_settings[shopstyle_list_id]' value='<?php echo $options['shopstyle_list_id']; ?>'>
	<?php
}


function wpss_api_options_page(  ) { 
	?>
	<form action='options.php' method='post'>
		<?php
		settings_fields( 'wpss_api_pluginPage' );
		do_settings_sections( 'wpss_api_pluginPage' );
		submit_button('Save Changes and Generate URL');
		do_settings_sections( 'wpss_api_pluginPage2' );
		?>
	</form>
	<?php

}


function wpss_api_settings_section_callback(  ) { 
	echo __( 'Enter the basic settings to create a ShopStyle API URL. For more documentation, consult the API documentation at <a href="https://www.shopstylecollective.com/api/overview"  target="_blank">https://www.shopstylecollective.com/api/overview</a>', 'wordpress' );
}

// A bit messy below to generate a link based on the above data...but it works!
function wpss_api_settings_section2_callback(  ) { 
	$options = get_option( 'wpss_api_settings' );
	echo __( 'Based on the above parameters, the ShopStyle API URL would be:<br>', 'wordpress' );
	
	if (isset($options['shopstyle_method']) && $options['shopstyle_method'] == 'Lists' ) { ?>
<br>&nbsp; &nbsp; &nbsp; &nbsp;
<a href="http://api.shopstyle.com/api/v2/lists?pid=<?php echo $options['shopstyle_pid'] ?>&format=<?php echo $options['shopstyle_format'] ?>&userId=<?php echo $options['shopstyle_username'] ?>&limit=<?php echo $options['shopstyle_limit'] ?>&offset=<?php echo $options['shopstyle_offset'] ?>" target="_blank">
http://api.shopstyle.com/api/v2/lists?pid=<?php echo $options['shopstyle_pid'] ?>&format=<?php echo $options['shopstyle_format'] ?>&userId=<?php echo $options['shopstyle_username'] ?>&limit=<?php echo $options['shopstyle_limit'] ?>&offset=<?php echo $options['shopstyle_offset'] ?>
</a>
<?php
	} else { ?>
<br>&nbsp; &nbsp; &nbsp; &nbsp;  
<a href="http://api.shopstyle.com/api/v2/lists/<?php echo $options['shopstyle_list_id'] ?>/items?pid=<?php echo $options['shopstyle_pid'] ?>&format=<?php echo $options['shopstyle_format'] ?>&limit=<?php echo $options['shopstyle_limit'] ?>&offset=<?php echo $options['shopstyle_offset'] ?>" target="_blank">
http://api.shopstyle.com/api/v2/lists/<?php echo $options['shopstyle_list_id'] ?>/items?pid=<?php echo $options['shopstyle_pid'] ?>&format=<?php echo $options['shopstyle_format'] ?>&limit=<?php echo $options['shopstyle_limit'] ?>&offset=<?php echo $options['shopstyle_offset'] ?>
</a>
<?php
	}
}
