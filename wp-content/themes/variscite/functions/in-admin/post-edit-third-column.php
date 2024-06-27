<?php
if (!defined('BANG_THIRD_COLUMN_DEBUG'))
  define('BANG_THIRD_COLUMN_DEBUG', false);


//  Initialise

add_action('add_meta_boxes', 'third_column_edit_form_init');
function third_column_edit_form_init() {
  if (BANG_THIRD_COLUMN_DEBUG) do_action('log', 'Third column: Initialise edit form');

  // the JS and CSS
  add_action('admin_enqueue_scripts', 'third_column_enqueue_scripts');

  // the actual sidebar writers
  add_action('edit_form_top', 'third_column_edit_form_top', 10, 1);
  add_action('edit_form_after_title', 'third_column_edit_form_after_title', 10, 1);
  add_action('edit_form_after_editor', 'third_column_edit_form_after_editor', 10, 1);
  add_action('dbx_post_sidebar', 'third_column_dbx_post_sidebar', 10, 1);
}

function third_column_enqueue_scripts () {
  //  change the screen options
  add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
   add_screen_option('mini_columns', array('max' => 2, 'default' => 2, 'label' => 'Mini columns') );

}


/* Edit form */


function third_column_edit_form_top($post) {
}

function third_column_edit_form_after_title($post) {
}

function third_column_edit_form_after_editor($post) {
  ?>
  <div id='postbox-subcols'>
    <div id="postbox-container-left" class="postbox-container">
      <?php do_meta_boxes(null, 'left', $post); ?>
    </div>
    <div id="postbox-container-right" class="postbox-container">
      <?php do_meta_boxes(null, 'right', $post); ?>
    </div>
  </div>
  <?php
}

function third_column_dbx_post_sidebar($post) {
	$post_type = $post->post_type;
	?>
	
	<div id="postbox-container-3" class="postbox-container">
		<?php do_meta_boxes($post_type, 'column3', $post); ?>
	</div>
	
	
	<style type="text/css">
	<?php if(is_rtl()) {?>
	#postdivrich{margin-bottom: 15px;}
	#post-body.columns-3{margin-left:600px;position:relative}
	#post-body.columns-3 #postbox-container-1{float:left;width:280px;margin-left:-300px}
	#post-body.columns-3 #postbox-container-3{position:absolute;top:0;left:-600px;width:280px}
	#postbox-subcols:after{clear:both;height:0;width:0}
	#poststuff #postbox-container-left{width:50%;float:right}
	#poststuff #postbox-container-right{width:50%;float:left}
	#poststuff #left-sortables{padding-left:10px}
	#poststuff #right-sortables{padding-right:10px}
	.sortable-bump{min-height:100px;padding-bottom:50px}
	<?php } else { ?>
	#postdivrich{margin-bottom: 15px;}
	#post-body.columns-3{margin-right:600px;position:relative}
	#post-body.columns-3 #postbox-container-1{float:right;width:280px;margin-right:-300px}
	#post-body.columns-3 #postbox-container-3{position:absolute;top:0;right:-600px;width:280px}
	#postbox-subcols:after{clear:both;height:0;width:0}
	#poststuff #postbox-container-left{width:50%;float:left}
	#poststuff #postbox-container-right{width:50%;float:right}
	#poststuff #left-sortables{padding-right:10px}
	#poststuff #right-sortables{padding-left:10px}
	.sortable-bump{min-height:100px;padding-bottom:50px}
	<?php } ?>
	</style>
	<script type="text/javascript">
	jQuery(function ($) {
		if ($('#adv-settings .columns-prefs-3 input').is(':checked')) {
		$('#post-body').removeClass('columns-2').addClass('columns-3');
		}

		$('#left-sortables, #right-sortables, #column3-sortables').on('sortactivate', function(event, ui) {
		$(this).addClass('sortable-bump');
		}).on('sortdeactivate', function(event, ui) {
		$(this).removeClass('sortable-bump');
		});
	});
	</script>
  <?php
}

// add_action('submitpost_box', 'third_column_edit_form');
// add_action('submitpage_box', 'third_column_edit_form');
function third_column_edit_form() {
  global $post;
  $post_type = $post->post_type;
  //  check post types
  $side_meta_boxes2 = do_meta_boxes($post_type, 'column3', $post);

  /*  Obscure case:
   *  The taxBox javascript is only initialised if the page loads with a tax box within
   *  #side-sortables, #normal-sortables or #advanced-sortables
   *  Since we're adding an unanticipated #column3-sortables, we need to force it to initialise.
   */
  ?><script type='text/javascript'>
    jQuery(document).ready(function($) {
      var toInit = false;
      $('#column3-sortables div.postbox').each(function(){
        if ( this.id.indexOf('tagsdiv-') === 0 ) {
          toInit = true;
        }
      });
      $('#side-sortables, #normal-sortables, #advanced-sortables').children('div.postbox').each(function(){
        if ( this.id.indexOf('tagsdiv-') === 0 ) {
          toInit = false;
        }
      });
      tagBox.init();
    });
  </script><?php
}