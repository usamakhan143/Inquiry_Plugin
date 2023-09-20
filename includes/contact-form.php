<?php

add_shortcode('contact', 'show_contact_form');
add_action('rest_api_init', 'create_rest_endpoint');
add_action('init', 'create_submissions_page');
add_action( 'add_meta_boxes', 'create_meta_box' );



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
            'singular_name' => 'Submission'
        ],

        /**
         * To Disable the Add New feature in the Post-type.
         */
        // 'capabilities' => [ 'create_posts' => 'do_not_allow' ],
        
        /**
         * This will allows you to add custom fields in your post-type. (If we need title and other default fields in this post-type so we need to define them in this array.)
         * 'supports' => false // No fields are supported by this custom post-type or we can't be able to show any field inside the post-type
         * 'supports' => ['custom-fields'] // only support custom fields 
         */        
        'supports' => false 
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

    // var_dump($params);

    // Send to Email.
    $headers = [];
    $admin_email = get_bloginfo('admin_email');
    $admin_name = get_bloginfo('name');
    $headers[] = "From: {$admin_name} <{$admin_email}>";
    $headers[] = "Reply-to: {$params['name']} <{$params['email']}>";
    $subject = "New Inquiry from {$params['name']}";
    $message = '';
    $message .= "Inquiry Sent by {$params['name']}";

    $postarr = [
        'post_title' => $params['name'],
        'post_type' => 'submission'
    ];

    $post_id = wp_insert_post($postarr);

    foreach ($params as $label => $value) {
        $message .= ucfirst($label) . ':' . $value;
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
        echo '<li><strong>' . ucfirst($key) . ':</strong> <br/>' . $value[0].'</li>';
    }
    
    echo '</ul>';
}