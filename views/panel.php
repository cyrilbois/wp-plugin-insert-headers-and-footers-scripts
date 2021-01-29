<?php

// Security: If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?><div class="wrap"> 
	<?php 
	if ($this->view === 'admin') { ?>
	<h2><?php echo $this->displayName; ?> &gt; <?php esc_html_e('Settings', 'insert-headers-and-footers-scripts' ); ?></h2>
	<?php 	if ($this->message) { ?>
	<div class="updated fade"><p><?php echo $this->message; ?></p></div>
	<?php 	} ?>
	<?php 	if ($this->error) { ?>
	<div class="error fade"><p><?php echo $this->error; ?></p></div>
	<?php 	} ?>
	<?php } ?>
	<div id="poststuff">
		<div class="postbox">
			<div class="tab-menu">
				<ul class="wpcr_nav_tabs">
					<li><a href="#" class="tab-a active-a" data-id="tab-header"><?php esc_html_e('Header', 'insert-headers-and-footers-scripts' ); ?></a></li>
					<li><a href="#" class="tab-a" data-id="tab-footer"><?php esc_html_e('Footer', 'insert-headers-and-footers-scripts' ); ?></a></li>
			  </ul>
			</div>
			<form action="options-general.php?page=<?php echo $this->name; ?>" method="post">
				<?php wp_nonce_field( $this->name, $this->name.'nonce' ); ?>
				<div class="inside">
					<div class="tab tab-active" data-id="tab-header">
						<p>
							<label for="<?php echo $this->name; ?>header"><b><?php esc_html_e('Scripts in Header (in the head section)', 'insert-headers-and-footers-scripts' ); ?></b></label>
							<textarea name="<?php echo $this->name; ?>header" class="widefat" rows="10"><?php echo $this->header; ?></textarea>
						</p>
					</div>
					<div class="tab" data-id="tab-footer">
						<p>
							<label for="<?php echo $this->name; ?>footer"><b><?php esc_html_e('Scripts in Footer (above the closing </body> tag)', 'insert-headers-and-footers-scripts' ); ?></b></label>
							<textarea name="<?php echo $this->name; ?>footer" class="widefat" rows="10" ><?php echo $this->footer; ?></textarea>
						</p>
					</div>
					<?php 
					if ($this->view === 'admin') { ?>
					<p>
						<input name="submit" type="submit" value="<?php esc_html_e('Save', 'insert-headers-and-footers-scripts' ); ?>" class="button button-primary" />
					</p>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
</div>
