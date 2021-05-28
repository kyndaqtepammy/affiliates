<?php
/**
	 * Plugin Name: SDDS Affiliates
	 * Description: Create front end affiliate links for SDDS
	 * Version: 1.0
	 * Author: Pamela Sillah
	 * Text Domain:
*/
    require_once('wplist-table.php');
    function affliate_styles() {
        wp_enqueue_style('afcss', plugins_url( 'affiliates.css' , __FILE__ ));
        wp_enqueue_script('afjs', plugins_url( 'affiliates.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('afjs2', plugins_url( 'affiliates-onload.js', __FILE__), array('jquery'), '', true);

        $script_params = array(
        	'user_id' =>  get_current_user_id(),
        	'ajaxurl' =>admin_url('admin-ajax.php')
        );
        wp_localize_script('afjs', 'script_params', $script_params);
        wp_localize_script('afjs2', 'script_params2', $script_params);

    }
    add_action( 'wp_enqueue_scripts', 'affliate_styles' );

    //insert the reffered person click
    add_action('wp_ajax_addReferal', 'addReferal');
    add_action('wp_ajax_nopriv_addReferal', 'addReferal');

    function addReferal() {
        global $user_ID;
    	 if( is_page('affiliate') ) {
    		if ( get_query_var('sddsref-id') ) {
    	 	#insert details into db here
    			$new_post = array(
    				'post_title' => 'New referal',
                    'post_content' => "get site src",
    				'post_status' => 'publish',
    				'post_date'=> date('Y-m-d H:i:s'),
    				'post_author' => get_query_var('sddsref-id'),
    				'post_type' => 'sdds-affiliate'
    			);
    			$post_id = wp_insert_post($new_post, true);
                //echo $post_id;
    	    } else {
                echo " Query var not defined";
            }
           
    	 } 
    }
    add_action('wp', 'addReferal');



    //add query vars to get url params 
    function add_query_vars_filter( $vars ){
        $vars[] = "sddsref-id";
        return $vars;
   }
   add_filter( 'query_vars', 'add_query_vars_filter' );
    
    //check and add a new user role if not exists
    function role_exists( $role ) {

        if( ! empty( $role ) ) {
          return $GLOBALS['wp_roles']->is_role( $role );
        }
        
        return false;
      }


    function addUserRole() {
        //add affiliate user role
        if( !role_exists( 'affiliate' ) ) {
            add_role('affiliate', __('Affiliate'), array(
                'read' => true,
                'edit_posts' => false
            ));
          }

          //register affiliate post type
          register_post_type('sdds-affiliate');
    }
    add_action('init', 'addUserRole');

    //add backend menu page
    add_action('admin_menu', 'affiliates_custom_menu');
    function affiliates_custom_menu() {
        add_menu_page(
            'Affiliates',
            'View affiliates entries',
            'edit_posts',
            'affiliate-entries',
            'afiiliate_entries_callback_function',
            'dashicons-welcome-view-site', 5
        );
    }

    //html for the admin backend page
    function afiiliate_entries_callback_function() {
        ?>
        <h1>
			<?php esc_html_e( 'Welcome to my custom admin page.', 'my-plugin-textdomain' ); ?>
		</h1>
        <?php
        
    //     $postargs = array(
    //         'post_type' => 'sdds-affiliate',
    //         'posts_per_page' => '-1'
    //     );

    //     $query = new WP_Query($postargs);
    //     if( $query->have_posts() ):
    //         while( $query->have_posts() ): $query->the_post();
    //         the_title();
    //         the_excerpt();
    //     endwhile;
    // endif;
    // wp_reset_postdata();


        // $userargs = array(
        //     'role'    => 'affiliate',
        //     'orderby' => 'user_nicename',
        //     'order'   => 'ASC'
        // );
        // $users = get_users( $args );

        // echo '<ul>';
        // foreach ( $users as $user ) {
        //     echo '<li>' . esc_html( $user->display_name ) . '[' . esc_html( $user->user_email ) . ']</li>';
        // }
        // echo '</ul>';
        ?>

        <?php
    }

    function my_add_menu_items(){
        $hook = add_menu_page( 'My Plugin List Table', 'My List Table Example', 'activate_plugins', 'my_list_test', 'my_render_list_page' );
        add_action( "load-$hook", 'add_options' );
      }
      
      function add_options() {
        global $myListTable;
        $option = 'per_page';
        $args = array(
               'label' => 'Books',
               'default' => 10,
               'option' => 'books_per_page'
               );
        add_screen_option( $option, $args );
        $myListTable = new Affliates_List();
      }
      add_action( 'admin_menu', 'my_add_menu_items' );
      
      
      
      function my_render_list_page(){
        global $myListTable;
        echo '</pre><div class="wrap"><h2>My List Table Test</h2>'; 
        $myListTable->prepare_items(); 
      ?>
        <form method="post">
          <input type="hidden" name="page" value="ttest_list_table">
          <?php
          $myListTable->search_box( 'search', 'search_id' );
      
        $myListTable->display(); 
        echo '</form></div>'; 
      }
    

    //button to show popup and generate link
    function generate_affiliate() {   ?>
        
            <!-- <label for="fullname">Full name:</label><br>
            <input type="text" id="aff-fullname" name="fullname" required><br>
            <label for="email">Email Address:</label><br>
            <input type="email" id="aff-email" name="email" required><br><br>-->
            <button class="sdds-gen-link">Generate Link</button> 

            <div class="popup">
                <header>
                <span>Share Modal</span>
                <div class="close"><i class="uil uil-times"></i></div>
                </header>
                <div class="content">
                <p>Share this link via</p>
                <ul class="icons">
                    <a href="#" id="aff-fb"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" id="aff-tw"><i class="fab fa-twitter"></i></a>
                    <a href="#" id="aff-in"><i class="fa fa-linkedin-square"></i></a>
                </ul>
                <p>Or copy link</p>
                <div class="field">
                    <i class="url-icon uil uil-link"></i>
                    <input type="text" id="sdds-affiliate-link-field" readonly>
                    <button>Copy</button>
                </div>
                </div>
            </div>
        

        <?php
    }
    add_shortcode('generate_link', 'generate_affiliate');

    //register the user 
    function sdds_registration_form() {
       if( !is_user_logged_in() ) {
           global $load_affiliate_css;
           //set to true so css is loaded
           $load_affiliate_css = true;
           $registration_enabled = get_option('users_can_register');

           if( $registration_enabled) {
               $output = sdds_registration_form_fields();
           } else {
               $output = __('User registration is not allowed. Contact the site admin to change this setting');
           }
           return $output;
       }else {
           $output = __("You're already logged in");
       }
    }
    add_shortcode('sdds_custom_registration', 'sdds_registration_form');

    //the user login form
    function sdds_login_form() {
        if( !is_user_logged_in() ) {
            global $load_affiliate_css;
            $load_affiliate_css = true;

            $output = sdds_login_form_fields();
        } else {
            //show user dashboard link here?
        }
        return $output;
    }
    add_shortcode('sdds_custom_login', 'sdds_login_form');

    //regsitration form html
    function sdds_registration_form_fields() {
        ob_start(); ?>
        <h3 class="sdds-alffiliate-header"><?php _e('Register to become an affiliate'); ?> </h3>
        

        <?php
        //error messages after submission
        sdds_affiliate_show_error_messages();  ?>

        <form id="sdds-affliate-reg-form" method="post" class="sdds-affiliate-registration-form">
            <fieldset>
                <p>
                    <label for="aff-user-first"><?php _e('First Name');  ?> </label>
                    <input name="aff-user-first" id="aff-user-first" class="required" type="text" />    
                </p> 

                 <p>
                    <label for="aff-user-last"><?php _e('Last Name');  ?> </label>
                    <input name="aff-user-last" id="aff-user-last" class="required" type="text" />    
                </p>     

                 <p>
                    <label for="aff-user-email"><?php _e('Email');  ?> </label>
                    <input name="aff-user-email" id="aff-user-email" class="required" type="text" />    
                </p> 

                 <p>
                    <label for="aff-user-phone"><?php _e('Phone Number');  ?> </label>
                    <input name="aff-user-phone" id="aff-user-phone" class="required" type="text" />    
                </p> 

                <p>
					<input type="hidden" name="aff-register-nonce" value="<?php echo wp_create_nonce('aff-reg-nonce'); ?>"/>
					<input type="submit" value="<?php _e('Register Your Account'); ?>"/>
				</p>        
            </fieldset>
        </form>
        <?php
        return ob_get_clean();
    }

    //code that actually registers a new user 
    function sdds_add_new_member() {
        if ( isset( $_POST['aff-user-email'] )  && wp_verify_nonce( $_POST['aff-register-nonce'], 'aff-reg-nonce' )){
            $user_email = $_POST['aff-user-email'];
            $user_first = $_POST['aff-user-first'];
            $user_last  = $_POST['aff-user-last'];
            $user_phone = $_POST['aff-user-phone'];

            //required for username checks
           // require_once(ABSPATH . WPINC . '/registration.php');

            if( $user_email == '') {
                aff_errors()->add('email_empty', __('Please enter your email address'));
            }
            if( $user_first == '') {
                aff_errors()->add('name_empty', __('Please enter your first name'));
            }
            if( $user_last == '') {
                aff_errors()->add('name_empty', __('Please enter your last name'));
            }
            if( $user_phone == '') {
                aff_errors()->add('phone_empty', __('Please enter your phone number'));
            }
            if(!is_email($user_email)) {
                aff_errors()->add('email_invalid', __('Looks like this email address is not valid!'));
            }
            if(email_exists($user_email)) {
                aff_errors()->add('email_used', __('Looks like this email address is already registered! Please login to access your dashboard'));
            }

            $errors = aff_errors()->get_error_messages();

            //only create user if errors are empty
            $user_pass = "6Tr%#lKG_#@$%%7";
            if( empty($errors) ) {
                $new_user_id = wp_insert_user( array(
                    'user_login'    => $user_email,  //idk if an email can be a valid username
                    'user_pass'     => $user_pass, //autogenerate?
                    'user_email'    => $user_email,
                    'first_name'    => $user_first,
                    'last_name'     => $user_last, //maybe separate first and last to avoid explosion
                    'user_registered' => date('Y-m-d H:i:s'),
                    'role'          => 'affiliate'
                ));

                if( $new_user_id) {
                    //send alert to admin
                    wp_new_user_notification($new_user_id, 'both');
                    //log new user in
                    wp_setcookie($user_email, $user_pass, true);
                    wp_set_current_user($new_user_id, $user_email);
                    do_action('wp_login', $user_email);

                    //send user to dashboard page
                    wp_redirect(home_url().'/affiliates-dashboard'); exit;
                }
            }
        }
    }
    add_action('init', 'sdds_add_new_member');


    function sdds_affiliate_show_error_messages() {
        if( $codes = aff_errors()->get_error_codes() ) {
            echo '<div class="aff-errors">';
            //loop errors
            foreach($codes as $code ) {
                $message = aff_errors()->get_error_message($code);
                echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
            echo '</div>';
        }
    }

    function aff_errors() {
        static $wp_error;  //holds global variable safely
        return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
    }

?>