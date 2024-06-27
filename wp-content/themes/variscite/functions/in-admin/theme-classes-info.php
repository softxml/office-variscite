<?php/****************************************************** ADD A PAGE TO ADMIN WHERE YOU CAN VIEW THE COUNTS****************************************************/add_action('admin_menu', 'themeclasses_menuitem');function themeclasses_menuitem(){	$pagetitle	= __('Custom Css Classes', THEME_NAME);	$curpage 	= add_submenu_page('themes.php', $pagetitle, $pagetitle, 'administrator', 'cthemeclasses', 'cthemeclasses_pagedata'); 		add_action( 'admin_print_styles-' . $curpage, 'cthemeclasses_options_admin_styles' );	}// ENQUEUE SCRIPTS & STYLES TO THIS PAGEadd_action( 'admin_init', 'cthemeclasses_admin_init' );function cthemeclasses_admin_init() {   wp_register_style('bootstrapcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');   wp_register_script('bootstrapjs', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');}function cthemeclasses_options_admin_styles() {	wp_enqueue_style('bootstrapcss');	wp_enqueue_script('bootstrapjs');}// BUILD THE TABLE IN THIS PAGEfunction cthemeclasses_pagedata() {	?>	<div class="wrap">		<h2 style="border-bottom: 1px solid #ccc; padding: 10px 0;margin: 0 0 10px;"><?php _e('Theme Classes', THEME_NAME); ?></h2>		<p><?php _e('Across the site you can use smart classes to fully customize the display of your website. Here is a list of useful classes you can use in your website.', THEME_NAME); ?></p>		<table class="table table-responsive table-bordered table-striped bgFF text-left ltr">			<tr>				<th class="col-md-2"><?php _e('Class', THEME_NAME); ?></th>				<th class="col-md-4"><?php _e('Info', THEME_NAME); ?></th>				<th class=""></th>				<th class="col-md-2"><?php _e('Class', THEME_NAME); ?></th>				<th class="col-md-4"><?php _e('Info', THEME_NAME); ?></th>			</tr>						<tr class="bg-primary">				<td class="text-center" colspan="5"><h4><?php _e('Distance & Padding From Edges (Margin / Padding)', THEME_NAME); ?></h4></td>			</tr>			<?php			$sidesArray = array(''=>'', 'r'=>'-right', 'b'=>'-bottom', 'l'=>'-left', 't'=>'-top');						foreach($sidesArray as $key => $side) {				$counter = 0;				while($counter < 6) {					echo '					<tr>						<td><input type="text" class="form-control" value=".m'.$key.$counter.'" /></td>						<td><input type="text" class="form-control" value="margin'.$side.': '.$counter.'% !important;" /></td>						<td></td>						<td><input type="text" class="form-control" value=".p'.$key.$counter.'" /></td>						<td><input type="text" class="form-control" value="padding'.$side.': '.$counter.'% !important;" /></td>					</tr>					';											$counter++;				}			}						?>			<tr class="bg-primary">				<td class="text-center" colspan="5"><h4><?php _e('Font Size', THEME_NAME); ?></h4></td>			</tr>			<?php			$fontSizes = array('8' => '10', '11' => '12', '13' => '14', '15' => '16', '17' => '18', '19' => '20', '22' => '24', '26' => '28', '30' => '32', '34' => '35');						foreach($fontSizes as $key => $fs) {				echo '				<tr>					<td><input type="text" class="form-control" value=".fs'.$key.'" /></td>					<td><input type="text" class="form-control" value="font-size: '.$key.'px !important;" /></td>					<td></td>					<td><input type="text" class="form-control" value=".fs'.$fs.'" /></td>					<td><input type="text" class="form-control" value="font-size: '.$fs.'px !important;" /></td>				</tr>				';				}						?>		</table>	</div>	<?php}?>