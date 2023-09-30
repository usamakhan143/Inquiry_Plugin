<?php

add_shortcode('contact', 'show_contact_form');
add_action('rest_api_init', 'create_rest_endpoint');
add_action('init', 'create_submissions_page');
add_action( 'add_meta_boxes', 'create_meta_box' );
add_filter('manage_submission_posts_columns', 'custom_submissions_columns');
add_action( 'manage_submission_posts_custom_column', 'place_submission_column_value', 10, 2);
add_action('admin_init', 'setup_search');
add_action('wp_enqueue_scripts', 'enqueue_custom_css');
add_action( 'wp_footer', 'enqueue_custom_js');

function show_contact_form()
{
    include MY_PLUGIN_PATH . '/includes/templates/contact-form.php';
}

function create_submissions_page()
{
    $args = [
        'public' => true,
        'has_archive' => true, // It means wordpress will assume the it should concider as post. As we all know we have certain types of posttypes such as pages, etc.
        'labels' => [
            'name' => 'Submissions',
            'singular_name' => 'Submission',
            'edit_item' => 'Edit Submission',
        ],

        'capability_type' => 'post',
        /**
         * To Disable the Add New feature in the Post-type.
         */
        'capabilities' => [ 
            'create_posts' => false 
        ],
        
        /**
         * This will allows you to add custom fields in your post-type. (If we need title and other default fields in this post-type so we need to define them in this array.)
         * 'supports' => false // No fields are supported by this custom post-type or we can't be able to show any field inside the post-type
         * 'supports' => ['custom-fields'] // only support custom fields 
         */        
        'supports' => false,
        'map_meta_cap' => true 
    ];
    register_post_type('submission', $args);
}

function create_rest_endpoint()
{

    register_rest_route('api/v1', 'submit', array(
        'methods' => 'POST',
        'callback' => 'handle_inquiry'
    ));
}

function handle_inquiry($data)
{
    $params = $data->get_paramS();

    if (!wp_verify_nonce($params['_wpnonce'], 'wp_rest')) {
        return new WP_REST_Response('Message Not Sent', 442);
    }

    unset($params['_wpnonce']);
    unset($params['_wp_http_referer']);

    // Data coming from the Form
    $param_name = sanitize_text_field($params['name']);
    $param_email = sanitize_email($params['email']);
    $param_message = sanitize_textarea_field($params['message']);

    // Send to Email.
    $headers = [];
    $admin_email = get_bloginfo('admin_email');
    $admin_name = get_bloginfo('name');
    $headers[] = "From: {$admin_name} <{$admin_email}>";
    $headers[] = "Reply-to: {$param_name} <{$param_email}>";
    $subject = "New Inquiry from {$param_name}";
    $message = '';
    $message .= "Inquiry Sent by {$param_name}";

    $postarr = [
        'post_title' => $param_name,
        'post_type' => 'submission',
        'post_status' => 'publish'
    ];

    $post_id = wp_insert_post($postarr);

    foreach ($params as $label => $value) {

        switch($label) {
            
            case 'email':
                $value = $param_email;
                break;

            case 'message':
                $value = $param_message;
                break;

            default:
                $value = sanitize_text_field($value);
        }

        $message .= ucfirst(sanitize_text_field($label)) . ':' . $value;
        add_post_meta( $post_id, $label, $value );
    }

    wp_mail($admin_email, $subject, $message, $headers);

    return new WP_REST_Response('Message Sent Successfully!', 200);
}

// Creating a metabox in to the custom post-type that we have named submissions.
function create_meta_box(){
    add_meta_box( 'custom_contact_form', 'Submission', 'display_submissions', 'submission' );
}

function display_submissions(){
    $post_metas = get_post_meta(get_the_ID());
    unset($post_metas['_edit_lock']);

    echo '<ul>';
    foreach($post_metas as $key => $value) {
        echo '<li><strong>' . ucfirst($key) . ':</strong> <br/>' . esc_html($value[0]).'</li>';
    }
    
    echo '</ul>';
}

function custom_submissions_columns($columns) {
    // dd($columns) ;

    // We are creating our own column.
    $columns = [
        'cb' => $columns['cb'], // Except this all are custom.
        'name' => __('Name', 'translate-contact-plugin'),
        'email' => __('Email', 'translate-contact-plugin'),
        'message' => __('Message', 'translate-contact-plugin'),
    ];

    return $columns;
}


function place_submission_column_value($columns, $post_id) {
    
    // Here we have set all the column values in their respective column.
    switch($columns){
        case 'name':
            echo esc_html(get_post_meta($post_id, 'name', true));
            break;
        case 'email':
            echo esc_html(get_post_meta($post_id, 'email', true));
            break;
        case 'message':
            echo esc_html(get_post_meta($post_id, 'message', true));
            break;
        default:
            echo 'ABC';
            break;
    }
}


function setup_search(){

    global $type;

    if($type === 'submission') {
        add_filter('posts_search', 'submissions_search_override', 10, 2);
    }

}

function submissions_search_override($search, $query) {
    global $wpdb;
    
}


function enqueue_custom_css(){
    wp_enqueue_style('contact_form_plugin', MY_PLUGIN_URL . 'assets/css/contact-plugin.css');
}

function enqueue_custom_js(){
    wp_enqueue_script('contact_form_plugin', MY_PLUGIN_URL . 'assets/js/contact-plugin.js');
}