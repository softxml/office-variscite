<?php
$opt = get_field('optage_sidestrip_settings', 'option');


// build links  
$linkList = '';
foreach($opt['sidestrip_links'] as $link) {
    $linkList .= '<li ><a href="'.$link['sidestrip_link_url'].'" style=" color: '.$opt['sidestrip_popup_text_color'].'" 
     >'.$link['sidestrip_link_label'].'</a></li>';
}
$linkList = '<ul class="links-list lsnone p0 m0">'.$linkList.'</ul>';

// SHOW OR HIDE SIDE STRIP
?>
<div id="globalSideStripMenu" class="slide-out">
    <div class="inner">
        <div class="closed" style="background-color: <?php echo $opt['sidestrip_popup_color']; ?>">
            <div class="closed-label" style="color: <?php echo $opt['sidestrip_popup_text_color']; ?>"><?php echo $opt['sidestrip_closed_label']; ?></div>
        </div>
        <div class="open" style="display: none; background-color: <?php echo $opt['sidestrip_popup_color']; ?>">
            <div class="row">
                <div class="col-md-7 col-sm-7"><?php echo $linkList; ?></div>
                <div class="col-md-3 col-sm-3 pl0">
                    <a href="<?php echo $opt['big_blue_button_url']; ?>" class="blue-box" style="background-color: <?php echo $opt['sidestrip_popup_button_color']; ?>"><?php echo $opt['sidestrip_blue_label']; ?></a>
                </div>
                <div class="col-md-2 col-sm-2 pl0">
                    <button class="btn btn-link btn-lg closeStrip"> <img src="<?php echo IMG_URL; ?>/x-strip.png" alt="close icon"> </button>
                </div>
            </div>
        </div>
    </div>
</div>