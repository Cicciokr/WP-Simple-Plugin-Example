<?php 
$success_msg = '';
global $wpdb;
$idSportrace = isset($_GET['id']) ? $_GET['id'] : $_POST['id_sportrace'];
if (isset($_POST['submit'])) {
    $wpdb->show_errors();
    $table_name = $wpdb->prefix . "sportrace_members";
    $insert_query = " insert into " . $table_name . " (id_sportrace, name_member, position, time_done) values ('" . $_POST['id_sportrace'] . "', '" . $_POST['name_member'] . "', '" . $_POST['position'] . "', '" . $_POST['time_done'] . "') ";
    $insertResult = $wpdb->query($insert_query);

    if ($insertResult) {
        $success_msg = "Insert successfully.";
        get_permalink();
    }
    if ($wpdb->last_error !== '') :
        $wpdb->print_error();
    endif;
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
                    <h3>Registration member:
                        <?php echo $_GET['id']; ?>
                    </h3>
                </b></td>
        </tr>
        <tr>
            <td>Name:</td>
            <td><input style="width: 300px;" type="text" name="name_member" /></td>
        </tr>
        <tr>
            <td>Position (Arrival):</td>
            <td><input style="width: 300px;" type="text" name="position" /></td>
        </tr>
        <tr>
            <td>Time:</td>
            <td><input style="width: 300px;" type="text" name="time_done" /></td>
        </tr>
        <tr>
            <td></td>
            <td align="left">
                <input type="hidden" name="id_sportrace" value="<?php echo $idSportrace; ?>" />
                <?php submit_button(); ?>
            </td>
        </tr>
    </table>
</form>

<?php 
echo '<form method="post">';
require_once 'memebrs_sportrace_table.php';
$class = new Sportrace_Members_Table($idSportrace);
$class->prepare_items();
$class->display();
echo '</form>';
?>