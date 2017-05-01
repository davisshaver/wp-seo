<?php
/**
 * Class file for WP_SEO_Fields.
 *
 * @package WP_SEO
 */

/**
 * Manages the plugin settings page, and provides helpers to some option values.
 */
class WP_SEO_Fields {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Unused.
	 *
	 * @codeCoverageIgnore
	 */
	private function __construct() {
		// Don't do anything, needs to be initialized via instance() method.
	}

	/**
	 * Unused.
	 *
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		wp_die( esc_html__( "Please don't __clone WP_SEO_Settings", 'wp-seo' ) );
	}

	/**
	 * Unused.
	 *
	 * @codeCoverageIgnore
	 */
	public function __wakeup() {
		wp_die( esc_html__( "Please don't __wakeup WP_SEO_Settings", 'wp-seo' ) );
	}

	/**
	 * Get the instance of this class.
	 *
	 * @codeCoverageIgnore
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WP_SEO_Fields;
		}
		return self::$instance;
	}

	/**
	 * Render a settings text field.
	 *
	 * @param array  $args {
	 *     An array of arguments for the text field.
	 *
	 *     @type string $field  The field name.
	 *     @type string $type   The field type. Default 'text'.
	 *     @type string $size   The field size. Default 80.
	 * }
	 * @param string $value The current field value.
	 * @param  string $slug Optional slug for context use, defaults to WP_SEO slug.
	 * @return void Prints text field.
	 */
	public function render_text_field( $args, $value, $slug = WP_SEO_Settings::SLUG ) {
		$args = wp_parse_args( $args, array(
			'type' => 'text',
			'size' => 80,
		) );

		printf(
			'<input type="%s" name="%s[%s]" value="%s" size="%s" />',
			esc_attr( $args['type'] ),
			esc_attr( $slug ),
			esc_attr( $args['field'] ),
			esc_attr( $value ),
			esc_attr( $args['size'] )
		);
	}

	/**
	 * Render a settings textarea.
	 *
	 * @param array  $args {
	 *     An array of arguments for the textarea.
	 *
	 *     @type  string $field The field name.
	 *     @type  int    $rows  Rows in the textarea. Default 2.
	 *     @type  int    $cols  Columns in the textarea. Default 80.
	 * }
	 * @param string $value The current field value.
	 * @param  string $slug Optional slug for context use, defaults to WP_SEO slug.
	 * @return void Prints textarea field.
	 */
	public function render_textarea( $args, $value, $slug = WP_SEO_Settings::SLUG ) {
		$args = wp_parse_args( $args, array(
			'rows' => 2,
			'cols' => 80,
		) );

		printf(
			'<textarea name="%s[%s]" rows="%d" cols="%d">%s</textarea>',
			esc_attr( WP_SEO_Settings::SLUG ),
			esc_attr( $args['field'] ),
			esc_attr( $args['rows'] ),
			esc_attr( $args['cols'] ),
			esc_textarea( $value )
		);
	}

	/**
	 * Render settings checkboxes.
	 *
	 * @param  array  $args {
	 *     An array of arguments for the checkboxes.
	 *
	 *     @type string $field The field name.
	 *     @type array  $boxes An associative array of the value and label
	 *                         of each checkbox.
	 * }
	 * @param  array  $values Indexed array of current field values.
	 * @param  string $slug Optional slug for context use, defaults to WP_SEO slug.
	 * @return void Prints checkbox field.
	 */
	public function render_checkboxes( $args, $values, $slug = WP_SEO_Settings::SLUG ) {
		foreach ( $args['boxes'] as $box_value => $box_label ) {
			printf( '
					<label for="%1$s_%2$s_%3$s">
						<input id="%1$s_%2$s_%3$s" type="checkbox" name="%1$s[%2$s][]" value="%3$s" %4$s>
						%5$s
					</label><br>',
				esc_attr( $slug ),
				esc_attr( $args['field'] ),
				esc_attr( $box_value ),
				is_array( $values ) ? checked( in_array( $box_value, $values ), true, false ) : '',
				esc_html( $box_label )
			);
		}
	}

	/**
	 * Render settings dropdown.
	 *
	 * @param  array  $args {
	 *     An array of arguments for the dropdown.
	 *
	 *     @type string $field The field name.
	 *     @type array  $boxes An associative array of the value and label
	 *                         of each dropdown option.
	 * }
	 * @param  array  $values Indexed array of current field values.
	 * @param  string $slug Optional slug for context use, defaults to WP_SEO slug.
	 * @return void Prints dropdown field.
	 */
	public function render_dropdown( $args, $values, $slug = WP_SEO_Settings::SLUG ) {
		printf( '<select id="%1$s_%2$s" name="%1$s[%2$s]">',
			esc_attr( $slug ),
			esc_attr( $args['field'] )
		);
		$count = 0;
		if ( empty( $values ) ) {
			$selected = 'selected';
			$disabled = 'disabled';
		} else {
			$selected = '';
			$disabled = '';
		}
		printf(
			'<option value="" %1$s %2$s>%3$s</option>',
			esc_attr( $selected ),
			esc_attr( $disabled ),
			esc_html( __( 'Select', 'wp-seo' ) )
		);
		foreach ( $args['boxes'] as $box_value => $box_label ) {
			printf(
				'<option id="%1$s_%2$s_%3$s" value="%4$s" %5$s>%6$s</option>',
				esc_attr( $slug ),
				esc_attr( $args['field'] ),
				esc_attr( $count ),
				esc_attr( $box_value ),
				selected( $values, $box_value, true ),
				esc_html( $box_label )
			);
			$count++;
		}
		echo '</select>';
	}

	/**
	 * Render image field.
	 *
	 * @param array $args {
	 *     An array of arguments for the image field.
	 *
	 *     @type string $field The field name.
	 *     @type string $size  Optional. Preview image size. Default 'thumbnail'.
	 *     @type string $slug  Optional. Form field name prefix. Default WP_SEO_Settings::SLUG.
	 * }
	 * @param int   $value The current field value.
	 */
	public function render_image_field( $args, $value ) {
		$args = wp_parse_args( $args, array(
			'size' => 'thumbnail',
			'slug' => WP_SEO_Settings::SLUG,
		) );

		wp_enqueue_media();

		$img_src = '';
		if ( $value ) {
			$img_src = wp_get_attachment_image_url( $value, $args['size'] );
		}

		echo '<div class="wp-seo-image-container">';

		// If we have an image, output it.
		echo '<div class="custom-img-container">';
		if ( $img_src ) {
			printf(
				'<img src="%1$s" alt="%2$s" />',
				esc_url( $img_src ),
				esc_attr( get_post_meta( $value, '_wp_attachment_image_alt', true ) )
			);
		}
		echo '</div>';

		// If we have an image, hide the add button, and vice versa.
		echo '<p class="hide-if-no-js">';
		printf(
			'<a class="upload-custom-img %1$s" href="%2$s">%3$s</a>',
			esc_attr( $img_src ? 'hidden' : '' ),
			esc_url( get_upload_iframe_src( 'image' ) ),
			esc_html__( 'Set image', 'wp-seo' )
		);
		printf(
			'<a class="delete-custom-img %1$s" href="#">%2$s</a>',
			esc_attr( $img_src ? '' : 'hidden' ),
			esc_html__( 'Remove this image', 'wp-seo' )
		);
		echo '</p>';

		printf(
			'<input id="%1$s_%2$s" class="custom-img-id" name="%1$s[%2$s]" type="hidden" value="%3$s" />',
			esc_attr( $args['slug'] ),
			esc_attr( $args['field'] ),
			esc_attr( $value )
		);

		echo '</div>';
	}

	/**
	 * Render a repeatable text field.
	 *
	 * @param  array  $args {
	 *     An array of arguments for setting up the repeatable fields.
	 *
	 *     @type string $field  The field name.
	 *     @type array  $repeat Associative array of field names and labels to
	 *                          include in each repeated instance of the field.
	 *     @type string $size   Optional. The field size. Default 70.
	 * }
	 * @param  array  $values The current field values.
	 * @param  string $slug Optional slug for context use, defaults to WP_SEO slug.
	 * @return void Prints repeatable field.
	 */
	public function render_repeatable_field( $args, $values, $slug = WP_SEO_Settings::SLUG ) {
		$args = wp_parse_args( $args, array(
			'size' => 70,
		) );
		$data_start = ( 0 === count( $values ) ) ? 1 : count( $values );
		?>
			<div class="wp-seo-repeatable">
				<div class="nodes">
					<?php if ( ! empty( $values ) ) : ?>
						<?php foreach ( (array) $values as $i => $group ) : ?>
							<div class="wp-seo-repeatable-group">
								<?php foreach ( $group as $name => $value ) : ?>
									<div class="wp-seo-repeatable-field">
										<?php
											printf( '
												<label for="%1$s_%2$s_%3$s_%4$s">
													%5$s
												</label>
												<input class="repeatable" type="text" id="%1$s_%2$s_%3$s_%4$s" name="%1$s[%2$s][%3$s][%4$s]" size="%6$s" value="%7$s" />',
												esc_attr( $slug ),
												esc_attr( $args['field'] ),
												intval( $i ),
												esc_attr( $name ),
												esc_attr( $args['repeat'][ $name ] ),
												esc_attr( $args['size'] ),
												esc_attr( $value )
											);
										?>
									</div><!-- .wp-seo-repeatable-field -->
								<?php endforeach; ?>
							</div><!-- .wp-seo-repeatable-group -->
						<?php endforeach; ?>
					<?php else : ?>
						<div class="wp-seo-repeatable-group">
							<?php foreach ( $args['repeat'] as $name => $label ) : ?>
								<div class="wp-seo-repeatable-field">
									<?php
										printf( '
											<label for="%1$s_%2$s_%3$s_%4$s">
												%5$s
											</label>
											<input class="repeatable" type="text" id="%1$s_%2$s_%3$s_%4$s" name="%1$s[%2$s][%3$s][%4$s]" size="%6$s" value="%7$s" />',
											esc_attr( $slug ),
											esc_attr( $args['field'] ),
											0,
											esc_attr( $name ),
											esc_attr( $label ),
											esc_attr( $args['size'] ),
											''
										);
									?>
								</div><!-- .wp-seo-repeatable-field -->
							<?php endforeach; ?>
						</div><!-- .wp-seo-repeatable-group -->
					<?php endif; ?>
				</div><!-- .nodes -->

				<script type="text/template" class="wp-seo-template" data-start="<?php echo absint( $data_start ); ?>">
					<div class="wp-seo-repeatable-group">
						<?php foreach ( $args['repeat'] as $name => $label ) : ?>
							<div class="wp-seo-repeatable-field">
								<?php
									printf( '
										<label for="%1$s_%2$s_%3$s_%4$s">
											%5$s
										</label>
										<input class="repeatable" type="text" id="%1$s_%2$s_%3$s_%4$s" name="%1$s[%2$s][%3$s][%4$s]" size="%6$s" value="%7$s" />',
										esc_attr( $slug ),
										esc_attr( $args['field'] ),
										'<%= i %>',
										esc_attr( $name ),
										esc_attr( $label ),
										esc_attr( $args['size'] ),
										''
									);
								?>
							</div><!-- .wp-seo-repeatable-field -->
						<?php endforeach; ?>
						<a href="#" class="wp-seo-delete"><%= wp_seo_admin.repeatable_remove_label %></a>
					</div><!-- .wp-seo-repeatable-group -->
				</script>
			</div><!-- .wp-seo-repeatable -->
		<?php
	}
}
/**
 * Helper function to use the class instance.
 *
 * @return object
 */
function wp_seo_fields() {
	return WP_SEO_Fields::instance();
}
