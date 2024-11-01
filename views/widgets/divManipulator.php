<p>
    <label for="<?php echo $this->get_field_id('div_name'); ?>">
        <?php echo 'Div ID:'; ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id('div_name'); ?>" name="<?php echo $this->get_field_name('div_name'); ?>" type="text" value="<?php echo esc_attr($div_name); ?>" />
</p>