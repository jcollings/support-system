<?php

require 'class-wt-admin-commentform.php';
require 'class-wt-admin-actionbox.php';

add_action( 'add_meta_boxes', 'wt_add_meta_boxes' );
function wt_add_meta_boxes(){

	// publish meta box
	add_meta_box( 'wpticket-ticket-actions', __( 'Ticket Actions', 'wp-tickets' ), 'wt_ticket_actions_meta_box', 'ticket', 'side', 'high');

	// ticket info meta box
	add_meta_box( 'wpticket-ticket-info', __( 'Ticket Info', 'wp-tickets' ), 'wt_ticket_info_meta_box', 'ticket', 'normal', 'high');

	// ticket internal notes meta box
	// add_meta_box( 'wpticket-ticket-internal-comments', __( 'Internal Comments', 'wp-tickets' ), 'wt_ticket_internal_comment_meta_box', 'ticket', 'normal', 'high');

	// ticket comment meta box
	add_meta_box( 'wpticket-ticket-comments', __( 'Ticket Thread', 'wp-tickets' ), 'wt_ticket_comment_meta_box', 'ticket', 'normal', 'high');
}

add_action( 'admin_menu' , 'wt_remove_meta_boxes' );
function wt_remove_meta_boxes() {
	
	// remove department and status
	remove_meta_box( 'departmentdiv', 'ticket', 'side' );
	remove_meta_box( 'statusdiv', 'ticket', 'side' );

	// remove slug
	remove_meta_box('slugdiv', 'ticket', 'normal' );
}

/**
 * Modify publish box
 * 
 * @return void
 */
function wt_ticket_actions_meta_box(){
	global $post;

	// todo: display real information here
	?>
	<style type="text/css">
	#wpticket-ticket-actions .inside{
		margin:0;
		padding:0;
	}
	</style>
	<?php
	/**
	 * Hooked:
	 *
	 * show_action_box 10
	 */
	do_action( 'wt_admin_action_box' );
	?>
	<div id="major-publishing-actions">

		<div id="publishing-action">
			<span class="spinner"></span>
			<input name="original_publish" type="hidden" id="original_publish" value="Publish">
			<input type="submit" name="publish" id="publish" class="button button-primary" value="Save Ticket" accesskey="p">
		</div>
		<div class="clear"></div>
	</div>
	<?php
}

/**
 * Display info about the ticket
 * @return void
 */
function wt_ticket_info_meta_box(){
	global $post;
	setup_postdata($post);

	// todo: gather and display ticket information
	?>
	<div class="wpss-two-cols">
		<dl class="wpss-pull-left wpss-one-col wpss-dl">
			
			<dt>Subject:</dt>
			<dd><?php the_title(); ?></dd>

			<dt>Department:</dt>
			<dd><?php echo wt_get_ticket_department(); ?></dd>

			<dt>Status:</dt>
			<dd><?php echo wt_get_ticket_status(); ?></dd>

			<dt>Priority:</dt>
			<dd><?php echo wt_get_ticket_priority(); ?></dd>

			<dt>Source</dt>
			<dd><?php echo wt_get_ticket_source($post->ID); ?></dd>

			<dt>Created:</dt>
			<dd><?php the_time('F j, Y \a\t g:i a'); ?></dd>

		</dl>

		<dl class="wpss-pull-right wpss-one-col wpss-dl">
			<dt>Author:</dt>
			<dd><?php echo wt_get_ticket_author_meta($post->ID, 'name'); ?></dd>

			<dt>Email:</dt>
			<dd><?php echo wt_get_ticket_author_meta($post->ID, 'email'); ?></dd>

		</dl>
	</div>
	<?php
}

/**
 * Display ticket comments
 * @return void
 */
function wt_ticket_comment_meta_box(){

	/**
	 * Hooked:
	 *
	 * show_admin_ticket_message 5
	 * show_admin_ticket_comments 10
	 * show_admin_ticket_commentform 20
	 */
	do_action( 'wt_admin_comment_box' );	
}

/**
 * Display ticket internal notes
 * @return void
 */
function wt_ticket_internal_comment_meta_box(){
	global $post;

	// todo: gather and display ticket internal comments
}
