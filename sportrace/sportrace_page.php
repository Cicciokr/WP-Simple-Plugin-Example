<?php 
$success_msg = '';
global $wpdb;

if (isset($_POST['submit'])) {
    $table_name = $wpdb->prefix . "sportrace";

    $photo = $_POST["photo"];
    $insert_query = " insert into " . $table_name . "(name_race, photo) values ('" . $_POST['name_race'] . "', '" . $photo . "') ";
    $insertResult = $wpdb->query($insert_query);
    if ($insertResult) {
        $success_msg = "Insert successfully.";
        get_permalink();
    }
}

// jQuery
wp_enqueue_script('jquery');
// This will enqueue the Media Uploader script
wp_enqueue_media();
?>
<form name="registration_form" method="post" action="<?php get_permalink(); ?>">
    <table border="0" cellspacing="4" cellspadding="4">
        <tr>
            <td colspan="2"><b>
                    <h3>Registration Sport Race</h3>
                </b></td>
        </tr>
        <tr>
            <td>Name race:</td>
            <td><input style="width: 300px;" type="text" name="name_race" /></td>
        </tr>
        <tr>
            <td>Photo race</td>
            <td>
                <div>
                    <input type="text" name="photo" id="photo" class="regular-text">
                    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary"
                        value="Upload photo">

                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="left">
                <?php submit_button(); ?>
            </td>
        </tr>
    </table>
</form>

<?php 
echo '<form method="post">';
require_once 'sportrace_table.php';
$class = new Sportrace_Table();
$class->prepare_items();
$class->display();
echo '</form>';
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
            .on('select', function(e) {
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                console.log(uploaded_image);
                var image_url = uploaded_image.toJSON().url;
                // Let's assign the url value to the input field
                $('#photo').val(image_url);
            });
    });
});
</script>