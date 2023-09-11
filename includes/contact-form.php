<?php

add_shortcode('contact', 'show_contact_form');
add_action('rest_api_init', 'create_rest_endpoint');
add_action('init', 'create_submissions_page');



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
        // 'capabilities' => [ 'create_posts' => 'do_not_allow' ], // To Disable the Add New feature in the Post-type.
        'supports' => ['custom-fields'] // This will allows you to add custom fields in your post-type. (If we need title and other default fields in this post-type so we need to define them in this array.)
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
    }

    wp_mail($admin_email, $subject, $message, $headers);

    return new WP_REST_Response('Message Sent Successfully!', 200);
}
