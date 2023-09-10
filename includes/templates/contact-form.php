<style>
    /* Style inputs with type="text", select elements and textareas */
input[type=text], select, textarea {
  width: 100%; /* Full width */
  padding: 12px; /* Some padding */ 
  border: 1px solid #ccc; /* Gray border */
  border-radius: 4px; /* Rounded borders */
  box-sizing: border-box; /* Make sure that padding and width stays in place */
  margin-top: 6px; /* Add a top margin */
  margin-bottom: 16px; /* Bottom margin */
  resize: vertical /* Allow the user to vertically resize the textarea (not horizontally) */
}

/* Style the submit button with a specific background color etc */
input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

/* When moving the mouse over the submit button, add a darker green color */
input[type=submit]:hover {
  background-color: #45a049;
}

/* Add a background color and some padding around the form */
.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

.success 
{
    color: #4F8A10;
    background-color: #DFF2BF;
    padding: 8px;
    display: none;
}
.error.error{
    color: #D8000C;
    background-color: #FFBABA;
    display: none;
    padding: 8px;
}
</style>

<div class="form-div">
    <h3>Contact Form</h3>
    <form id="contact-form">

        <?php wp_nonce_field('wp_rest'); ?>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your name..">

        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Your last name..">

        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Your Message Here.."></textarea>
    
        <input type="submit" value="Submit">
    </form>
    <div class="success"></div>
    <div class="error"></div>
</div>

<script src="<?php echo $_SERVER['REQUEST_URI'] . 'wp-content/plugins/contact-form/node_modules/jquery/dist/jquery.min.js'; ?>"></script>

<script>

    jQuery(document).ready(function(e){

        $('#contact-form').submit(function(e){
            e.preventDefault();

            var formData = $(this);
            // alert(formData.serialize());

            $.ajax({
                type: "POST",
                url: "<?php echo get_rest_url(null, '/api/v1/submit'); ?>",
                data: formData.serialize(),
                success: function(res){
                    formData.hide();
                    $(".success").html(res).show().fadeIn;
                },
                error: function(res){
                    $(".error").html(res).fadeIn;
                }
            });
        });

    });

</script>