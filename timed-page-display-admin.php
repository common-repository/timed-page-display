<?php
/**
 * Time Page Display settings page (admin)
 */

function time_page_display_settings() {
	// Require admin privs
	if ( ! current_user_can( 'manage_options' ) )
		return false;

	$new_options = array();

	if ( isset( $_POST['Submit'] ) ) {
		// Nonce verification 
		check_admin_referer( 'timed-page-display-update-options' );

		$new_options['time_page_display_page'] = ( isset( $_POST['time_page_display_page'] ) ) ? sanitize_text_field($_POST['time_page_display_page']) : '0';
		$new_options['time_page_display_redirect_page'] = ( isset( $_POST['time_page_display_redirect_page'] ) ) ? sanitize_text_field($_POST['time_page_display_redirect_page']) : '0';

		//$new_options['time_page_display_opening_hours'] = ( isset( $_POST['time_page_display_opening_hours'] ) ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['time_page_display_opening_hours'] ) ) : array();

		//$new_options['time_page_display_opening_hours'] = ( isset( $_POST['time_page_display_opening_hours'] ) ) ? array_filter(array_map('array_filter', $_POST['time_page_display_opening_hours'])) : array();


		$new_options['time_page_display_opening_hours'] = isset( $_POST['time_page_display_opening_hours'] ) ? ( wp_unslash( array_filter(array_map('array_filter', $_POST['time_page_display_opening_hours'])) ) ) : array();
			array_walk_recursive( $new_options['time_page_display_opening_hours'], 'sanitize_text_field' );


		// Get all existing AddToAny options
		$existing_options = get_option( 'time_page_display_options', array() );

		// Merge $new_options into $existing_options to retain AddToAny options from all other screens/tabs
		if ( $existing_options ) {
			$new_options = array_merge( $existing_options, $new_options );
		}
		
		update_option( 'time_page_display_options', $new_options );

		?>
		<div class="updated"><p><?php _e( 'Settings saved.' ); ?></p></div>
		<?php
	}

	$options = stripslashes_deep( get_option( 'time_page_display_options', array() ) );
	//echo "<pre>";		print_r($new_options);	echo "</pre>";		die();
?>

	<div class="wrap">
		<h1><?php _e( 'Timed Page Display Settings', 'timed-page-display' ); ?></h1>
		<form id="time_page_display_admin_form" method="post" action="">
			<?php wp_nonce_field('timed-page-display-update-options'); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Select Page</th>
						<td><?php wp_dropdown_pages(array('name' => 'time_page_display_page', 'selected' => $options['time_page_display_page'] )); ?>
							<p class="description" id="tagline-description">Select the page which will need the redirect</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Redirect To</th>
						<td><?php wp_dropdown_pages(array('name' => 'time_page_display_redirect_page', 'selected' => $options['time_page_display_redirect_page'])); ?>
							<p class="description" id="tagline-description">Select the page which will serve as the redirect page</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Redirect Hours</th>
						<td>
							<div class="opening-hours">
								<table class="form-table form-opening-hours">
									<tbody>
										<p class="description" id="tagline-description">Set the redirect hours</p>
										<tr class="periods-day">

											<td class="col-times" colspan="2" valign="top">
												<div class="period-container">

													<table class="period-table">
														<tbody>

															<tr class="period">
																<td class="col-name" valign="top">
																	Monday				
																</td>

																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Mon][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Mon']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Mon][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Mon']['end'] ; ?>">
																</td>
															</tr>

															<tr class="period">
																<td class="col-name" valign="top">
																	Tuesday				
																</td>
																							
																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Tue][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Tue']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Tue][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Tue']['end'] ; ?>">
																</td>
															</tr>

															<tr class="period">
																<td class="col-name" valign="top">
																	Wednesday				
																</td>
																							
																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Wed][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Wed']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Wed][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Wed']['end'] ; ?>">
																</td>
															</tr>

															<tr class="period">
																<td class="col-name" valign="top">
																	Thursday				
																</td>
																							
																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Thu][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Thu']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Thu][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Thu']['end'] ; ?>">
																</td>
															</tr>

															<tr class="period">
																<td class="col-name" valign="top">
																	Friday				
																</td>
																							
																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Fri][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Fri']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Fri][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Fri']['end'] ; ?>">
																</td>
															</tr>

															<tr class="period">
																<td class="col-name" valign="top">
																	Saturday				
																</td>
																							
																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Sat][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Sat']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Sat][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Sat']['end'] ; ?>">
																</td>
															</tr>

															<tr class="period">
																<td class="col-name" valign="top">
																	Sunday				
																</td>
																							
																<td class="col-time-start">
																	<input name="time_page_display_opening_hours[Sun][start]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Sun']['start'] ; ?>">
																</td>
																<td class="col-time-end">
																	<input name="time_page_display_opening_hours[Sun][end]" type="text" class="input-timepicker input-time-end" value="<?php echo $options['time_page_display_opening_hours']['Sun']['end'] ; ?>">
																</td>
															</tr>					

														</tbody>
													</table>
												</div>
											</td>
											
										</tr>
									</tbody>
								</table>
							</div>
						</td>
					</tr>					
				</tbody>
			</table>
			<p class="submit">
				<input class="button-primary" type="submit" name="Submit" value="<?php _e('Save Changes', 'timed-page-display' ) ?>" />
			</p>			
		</form>
	</div>

<?php }