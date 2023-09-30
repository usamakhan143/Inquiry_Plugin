<div class="form-div">
    <h3>Contact Form</h3>
    <form id="contact-form">

        <?php wp_nonce_field('wp_rest'); ?>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your name..">

        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Your Email Address..">

        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Your Message Here.."></textarea>

        <input type="submit" value="Submit">
    </form>
    <div class="success"></div>
    <div class="error"></div>
</div>

<script src="<?php echo $_SERVER['REQUEST_URI'] . 'wp-content/plugins/contact-form/node_modules/jquery/dist/jquery.min.js'; ?>"></script>

<script>
    jQuery(document).ready(function (e) {
  $("#contact-form").submit(function (e) {
    e.preventDefault();

    var formData = $(this);
    // alert(formData.serialize());

    $.ajax({
      type: "POST",
      url: "<?php echo get_rest_url(null, '/api/v1/submit'); ?>",
      data: formData.serialize(),
      success: function (res) {
        formData.hide();
        $(".success").html(res).show().fadeIn;
      },
      error: function (res) {
        $(".error").html(res).fadeIn;
      },
    });
  });
});
</script>