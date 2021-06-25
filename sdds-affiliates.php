<?php
/**
 * Plugin Name: SDDS Affiliates
 * Description: Create front end affiliate links for SDDS
 * Version: 2.1.0
 * Author: Pamela Sillah
 * Text Domain: sddsaff
 */

//Plugin activated
function sdds_affiliates_activate()
{
    register_post_type('sdds-affiliate');

    //create database table
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    $tablename = $wpdb->prefix . 'sdds_affiliates';
    $sql = "CREATE TABLE $tablename (
      id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      fullname VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      site_from VARCHAR(255) NOT NULL,
      ip_address VARCHAR(255) NOT NULL,
      dated DATETIME(6) NOT NULL )";
    maybe_create_table($wpdb->prefix . $tablename, $sql);

    //TODO: register affiliate manager role
    
}
register_activation_hook(__FILE__, 'sdds_affiliates_activate');

function affliate_styles()
{
    wp_enqueue_style('afcss', plugins_url('affiliates.css', __FILE__));
    wp_enqueue_style('dashcss', plugins_url('assets/dashboard.css', __FILE__));
    wp_enqueue_script('afjs', plugins_url('affiliates.js', __FILE__) , array(
        'jquery'
    ) , '', true);
    wp_enqueue_script('afjs2', plugins_url('affiliates-onload.js', __FILE__) , array(
        'jquery'
    ) , '', true);

    $script_params = array(
        'user_id' => get_current_user_id() ,
        'ajaxurl' => admin_url('admin-ajax.php')
    );

    wp_localize_script('afjs', 'script_params', $script_params);
    wp_localize_script('afjs2', 'script_params2', $script_params);

}
add_action('wp_enqueue_scripts', 'affliate_styles');




function redirect_admin( $redirect_to, $request, $user ){
    //is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if ( in_array( 'affiliate', $user->roles ) ) {
            $redirect_to = WP_HOME.'/affiliates-dashboard'; // Your redirect URL
        }
    }
    return $redirect_to;
}

add_filter( 'login_redirect', 'redirect_admin', 10, 3 );




//add query vars to get url params
function add_query_vars_filter($vars) {
    $vars[] = "sddsref-id";
    $vars[] = "src";
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');




//insert the reffered person LOADS
add_action('wp_ajax_addReferal', 'addReferal');
add_action('wp_ajax_nopriv_addReferal', 'addReferal');

function addReferal()
{
    //var_dump(get_query_var('sddsref-id'));

    global $user_ID;
    if (is_page('affiliate'))
    {
         //if user is logged in && affiliate, redirect to dashboard
         if ( is_user_logged_in() || check_user_role('affiliate')) {
            wp_redirect(home_url() . '/affiliates-dashboard');
        }
        if (get_query_var('sddsref-id'))
        {
            //if user is clicking own link
            echo ((string)get_current_user_id() == get_query_var('sddsref-id'));
            if ( (string)get_current_user_id() === get_query_var('sddsref-id'))
            {
                #redirect to dashboard
                wp_redirect(home_url() . '/affiliates-dashboard');
            }
            else {
                $user = get_user_by('id', get_query_var('sddsref-id'));
                $fullname = $user->first_name . ' ' . $user->last_name;
                $email = $user->user_email;
                $sitefrom = get_query_var('src');
                global $wpdb;
                $tablename = $wpdb->prefix . 'sdds_affiliates';
                $wpdb->insert($tablename, array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'site_from' => $sitefrom,
                    'ip_address' => "Zimbabwe", //$_POST['country'],
                    'dated' => date('Y-m-d H:i:s')
                ) , array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ));
            

            }

           
        }

    }
   // wp_die();
}
add_action('wp', 'addReferal');


//check and add a new user role if not exists
function role_exists($role)
{

    if (!empty($role))
    {
        return $GLOBALS['wp_roles']->is_role($role);
    }

    return false;
}

function addUserRole()
{
    global $wpdb;
    //add affiliate user role
    if (!role_exists('affiliate'))
    {
        add_role('affiliate', __('Affiliate') , array(
            'read' => true,
            'edit_posts' => false
        ));
    }
}
add_action('init', 'addUserRole');


//check current user  role
function check_user_role($roles, $user_id = null) {
	if ($user_id) $user = get_userdata($user_id);
	else $user = wp_get_current_user();
	if (empty($user)) return false;
	foreach ($user->roles as $role) {
		if (in_array($role, $roles)) {
			return true;
		}
	}
	return false;
}
function add_affiliate_menu_items()
{
    add_menu_page(
        __('Affiliate Referals', 'sddsaff'),
        'Affiliate Referals', 
        'manage_options',
        'affiliate-referals',
        'render_list'
    );
    // This is the hidden page
    add_submenu_page(null, null, null, 'activate_plugins', 'view_affiliate', 'affiliate_details_page_callback');
}
add_action('admin_menu', 'add_affiliate_menu_items');




function affiliate_details_page_callback()
{

?>
    <div class="wrap">
        <div id="icon-users" class="icon32"></div>
    </div> 
    <?php
     require_once ('wplist-table-referal.php');
    $referals_list = new Referals_List();
    $referals_list->prepare_items();  ?>
    <div class="wrap">
              <div id="icon-users" class="icon32"></div>
              <h2>Affiliate Referals</h2>
              <form id="affiliates-filter">
                  <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                  <?php $referals_list->display(); ?>
              </form>
          </div> 
          <?php
    
}

function render_list()
{
    require_once ('wplist-table.php');
    $exampleListTable = new Affliates_List();
    $exampleListTable->prepare_items();
?>
          <div class="wrap">
              <div id="icon-users" class="icon32"></div>
              <h2>Affiliate Entries</h2>
              <form id="affiliates-filter">
                  <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                  <?php $exampleListTable->display(); ?>
              </form>
          </div> 
          <?php
}


add_action('admin_post_view_affiliate','view_all_referals_from_this_email');
function view_all_referals_from_this_email(){
    //print_r($_POST);
    //you can access $_POST, $GET and $_REQUEST values here. 
    wp_redirect(admin_url('admin.php?page=view_affiliate&email='.$_GET['email'].''));
   //apparently when finished, die(); is required. 
}


// add_shortcode('generate_link', 'generate_affiliate');
//register the user
function sdds_registration_form()
{
    if (!is_user_logged_in())
    {
        global $load_affiliate_css;
        //set to true so css is loaded
        $load_affiliate_css = true;
        $registration_enabled = get_option('users_can_register');

        if ($registration_enabled)
        {
            $output = sdds_registration_form_fields();
        }
        else
        {
            $output = __('User registration is not allowed. Contact the site admin to change this setting');
        }
        return $output;
    }
    else
    {
        $output = __("You're already logged in");
    }
}
add_shortcode('sdds_custom_registration', 'sdds_registration_form');

//the user login form
function sdds_login_form()
{
    if (!is_user_logged_in())
    {   
        global $load_affiliate_css;
        $load_affiliate_css = true;

        $output = sdds_login_form_fields();
    }
    else
    {
        //show user dashboard link here?
        require_once 'affiliates-dashboard.php';
        $output = generate_affiliate();
    }
    return $output;
}
add_shortcode('sdds_custom_login', 'sdds_login_form');

//regsitration form html
function sdds_registration_form_fields()
{
    ob_start(); ?>
        <h3 class="sdds-alffiliate-header"><?php _e('Register to become an affiliate'); ?> </h3>
        

        <?php
    //error messages after submission
    sdds_affiliate_show_error_messages(); ?>
	
		<div class="aff-form-container">
			 <form id="sdds-affliate-reg-form" method="post" class="sdds-affiliate-registration-form">
				<fieldset>
					<p>
						<label for="aff-user-first"><?php _e('First Name'); ?> </label>
						<input name="aff-user-first" id="aff-user-first" class="aff-required" type="text" />    
					</p> 

					 <p>
						<label for="aff-user-last"><?php _e('Last Name'); ?> </label>
						<input name="aff-user-last" id="aff-user-last" class="aff-required" type="text" />    
					</p>     

					 <p>
						<label for="aff-user-email"><?php _e('Email'); ?> </label>
						<input name="aff-user-email" id="aff-user-email" class="aff-required" type="text" />    
					</p> 

					 <p>
						<label for="aff-user-phone"><?php _e('Phone Number'); ?> </label>
						<input name="aff-user-phone" id="aff-user-phone" class="aff-required" type="text" />    
					</p> 

					<p>
					<input type="checkbox" id="aff-user-agree" class="aff-required-check" name="aff-user-agree" onchange="doalert(this)" />
					I have read and understood the <a href="<?php  echo home_url('/affiliate-terms-and-conditions')  ?>">Terms and conditions</a>
					</p>

					<p>
						<input type="hidden" name="aff-register-nonce" value="<?php echo wp_create_nonce('aff-reg-nonce'); ?>"/>
						<input type="submit" id="aff-register-btn" value="<?php _e('Register Your Account'); ?>" disabled/>
					</p>        
				</fieldset>
			</form>
		</div>

       
        <?php
    return ob_get_clean();
}

function still_visible($always_visible)
{
    $always_visible = true;
    return $always_visible;
}
add_filter('page_row_actions', 'still_visible');

function sdds_login_form_fields()
{
    ob_start(); ?>
		<h3 class="aff_header"><?php _e('<div class="aff-login-header"><p>Login</p></div>'); ?></h3>
 
		<?php
    // show any error messages after form submission
    sdds_affiliate_show_error_messages(); ?>
	<div class="aff-login-form-container">
		<form id="aff_login_form"  class="aff_form"action="" method="post">
			<fieldset>
				<p>
					<label for="aff_user_Login">Username</label>
					<input name="aff_user_login" id="aff_user_login" class="aff-required" type="text"/>
				</p>
				<p>
					<label for="aff_user_pass">Password</label>
					<input name="aff_user_pass" id="aff_user_pass" class="aff-required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="aff_login_nonce" value="<?php echo wp_create_nonce('aff-login-nonce'); ?>"/>
					<input id="aff_login_submit" type="submit" value="Login"/>
				</p>
                <p>Don't have an affiliate account? <a href="<?php echo get_bloginfo('wpurl'); ?>">Create one</a></p>
			</fieldset>
		</form>
	</div>

	<?php
    return ob_get_clean();
}

function sdds_login_member()
{
    if (isset($_POST['aff_user_login']) && wp_verify_nonce($_POST['aff_login_nonce'], 'aff-login-nonce'))
    {
        // this returns the user ID and other info from the user name
        $user = get_user_by('user_nicename', $_POST['aff_user_login']);

        if (!$user)
        {
            // if the user name doesn't exist
            aff_errors()->add('empty_username', __('Invalid username'));
        }

        if (!isset($_POST['aff_user_pass']) || $_POST['aff_user_pass'] == '')
        {
            // if no password was entered
            aff_errors()->add('empty_password', __('Please enter a password'));
        }

        // check the user's login with their password
        if (!wp_check_password($_POST['aff_user_pass'], $user->user_pass, $user->ID))
        {
            // if the password is incorrect for the specified user
            aff_errors()
                ->add('empty_password', __('Incorrect password'));
        }

        // retrieve all error messages
        $errors = aff_errors()->get_error_messages();

        // only log the user in if there are no errors
        if (empty($errors))
        {

            //wp_setcookie($_POST['aff_user_login'], $_POST['aff_user_pass'], true);
            wp_set_current_user($user->ID, $_POST['aff_user_login']);
            do_action('wp_login', $_POST['aff_user_login']);

            wp_redirect(home_url() . '/affiliates-dashboard');
            exit;
        }
    }

}
add_action('init', 'sdds_login_member');

//code that actually registers a new user
function sdds_add_new_member()
{
    if (isset($_POST['aff-user-email']) && wp_verify_nonce($_POST['aff-register-nonce'], 'aff-reg-nonce'))
    {
        $user_email = $_POST['aff-user-email'];
        $user_first = $_POST['aff-user-first'];
        $user_last = $_POST['aff-user-last'];
        $user_phone = $_POST['aff-user-phone'];

        //required for username checks
        // require_once(ABSPATH . WPINC . '/registration.php');
        if ($user_email == '')
        {
            aff_errors()->add('email_empty', __('Please enter your email address'));
        }
        if ($user_first == '')
        {
            aff_errors()->add('name_empty', __('Please enter your first name'));
        }
        if ($user_last == '')
        {
            aff_errors()->add('name_empty', __('Please enter your last name'));
        }
        if ($user_phone == '')
        {
            aff_errors()->add('phone_empty', __('Please enter your phone number'));
        }
        if (!is_email($user_email))
        {
            aff_errors()->add('email_invalid', __('Looks like this email address is not valid!'));
        }
        if (email_exists($user_email))
        {
            aff_errors()->add('email_used', __('Looks like this email address is already registered! Please login to access your dashboard'));
        }

        $errors = aff_errors()->get_error_messages();

        //only create user if errors are empty
        $user_pass = "6Tr%#lKG_#@$%%7";
        if (empty($errors))
        {
            $new_user_id = wp_insert_user(array(
                'user_login' => $user_email, //idk if an email can be a valid username
                'user_pass' => $user_pass, //autogenerate?
                'user_email' => $user_email,
                'first_name' => $user_first,
                'last_name' => $user_last, //maybe separate first and last to avoid explosion
                'user_registered' => date('Y-m-d H:i:s') ,
                'role' => 'affiliate'
            ));

            if ($new_user_id)
            {
                //send alert to admin
                wp_new_user_notification($new_user_id, null, 'both');
                //log new user in
                wp_set_auth_cookie($user_email, true);
                wp_set_current_user($new_user_id, $user_email);
                do_action('wp_login', $user_email, $new_user_id);

                //send user to dashboard page
                wp_redirect(home_url() . '/affiliate-thank-you');
                exit;
            }
        }
    }
}
add_action('init', 'sdds_add_new_member');

function sdds_affiliate_show_error_messages()
{
    if ($codes = aff_errors()->get_error_codes())
    {
        echo '<div class="aff-errors">';
        //loop errors
        foreach ($codes as $code)
        {
            $message = aff_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }
}

function aff_errors()
{
    static $wp_error; //holds global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

//get the user iP address
function get_ip_address()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    return $_SERVER['REMOTE_ADDR'];
    //return var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip)));
}




?>
