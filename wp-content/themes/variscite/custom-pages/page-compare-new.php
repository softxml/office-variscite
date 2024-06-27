<?php
/*
Template Name: Compare New
*/
get_header();


$pageID             = get_the_ID();
$headData           = get_field('compare_dlxls_group');
$addProdLink        = get_field('compare_addproducts_link');
$urlProducts        = $_SERVER['QUERY_STRING'];
$table_properties   = get_field('compare_table_properties');

$sideTable      = ''; // side table wont move and have only one cell 

if($urlProducts) {

    $tableBodyArr   = array();
    $AllSpecsArr    = array();
    $urlProducts    = explode( ',', explode('=', $urlProducts)[1] );
    $productsCount  = count($urlProducts);


    /********************************************************
    **  SET UP LABELS TABLE
    ********************************************************/
    $labelsTable         = '';

    foreach($table_properties as $tprop) {
        if( !empty($tprop['compare_section_title'][0]) ) {
            $tableBodyArr[]   = '<th>'.$tprop['compare_field_name'].'</th>';
            $labelsTable    .= '<tr><th>'.$tprop['compare_field_name'].'</th></tr>';
        }
        else {
            $tableBodyArr[]   = '<td class="sub">'.$tprop['compare_field_name'].'</td>';
            $labelsTable    .= '<tr><td class="sub">'.$tprop['compare_field_name'].'</td></tr>';
        }
    }
    $labelsTable = '
    <table class="table table-responsive table-headings table-sub">
        <thead><th class="addProducts" data-index="0"><a href="'.$addProdLink.'"><i class="fa fa-plus-circle"></i> '.__('Add Products', THEME_NAME).'</a></th></thead>
        <tbody>'.$labelsTable.'</tbody>
    </table>';




    /********************************************************
    **  SET UP PRODUCTS TABLE -- HEAD PART (fixed)
    ********************************************************/
    $main_table     = '';
    $indexCount     = 1;
    $prodBriefs		= '';
    $prodTables     = '';
    $compareKeys    = array();


    // The Loop
    $cQuery         = new WP_Query( array( 'post_type' => array('specs'), 'post__in' => $urlProducts, 'posts_per_page' => -1 ) );
    $cQueryFound    = $cQuery->found_posts;


    if ( $cQuery->have_posts() ) {
        while ( $cQuery->have_posts() ) {
            $cQuery->the_post();

            $pid        = get_the_ID();
            $plink      = get_permalink();

            // FIX TITLE
            $title      = get_the_title();
            $clnTitle   = get_the_title();
            $title      = explode(':', $title);
            if(count($title) == 1) { $title = $title[0]; }
            elseif( count($title) == 2) { $title = '<strong>'.$title[0].':</strong> '.$title[1]; }


            // PRODUCT THUMBNAIL
            $thumb  = smart_thumbnail($pid, 155, 90, '', $clnTitle, get_field('optage_defaults_specs_related_img', 'option'));


            // PRODUCT SPECS META DATA
            $prodBrief 	= get_field('vrs_specs_product_brief', $pid);


			// TABLE HEADER
            $main_table['head'] .= '
            <th data-index="'.$indexCount.'">
                <div class="innner-box">
                    <div class="thumb"><a href="'.$plink.'">'.$thumb.'</a></div>
                    <div class="title"><a href="'.$plink.'" class="c1">'.$title.'</a></div>
                    <div class="actions">
                        <div class="row">
                            <div class="col-md-8 col-md-12"><a href="'.$plink.'#get-a-quote" class="btn btn-warning upcase compareGetQuote"><span>'.__('Get a quote', THEME_NAME).'</span> <img src="'.IMG_URL.'/button-arrow-mini.png" alt="Arrow"></a></div>
                            <div class="col-md-4 hidden-xs"><button class="btn btn-link c00 uline removeItem" data-itemid="'.$pid.'">'.__('Remove', THEME_NAME).'</button></div>
                        </div>
                    </div>
                </div>
            </th>
			';

			// build each product table
			$tmpProdTrs     = '';
            $allSpecsArr[]  = post_tabs_specs_array($pid);
        
            $indexCount++;            
        }
        wp_reset_postdata();
    }



    /********************************************************
    **  SET UP PRODUCTS TABLE -- BODY PART (fixed)
    ********************************************************/
    $main_table['body'] = '';

    if( !empty($table_properties) ) {
        foreach($table_properties as $key => $tblPrpty) {

            $count          = 0;
            $main_table['body']   .= '<tr>';

            if( $tblPrpty['compare_section_title'][0] ) {
                // $main_table['body'] .= '<th>'.$tblPrpty['compare_field_name'].'</th>';

                while($count < $cQueryFound) {
                    $main_table['body'] .= '<th></th>';
                    $count++;
                }
            }
            else {
                // $main_table['body'] .= '<td>'.$tblPrpty['compare_field_name'].'</td>';

                while($count < $cQueryFound) {
                    $main_table['body'] .= '<td>'.$allSpecsArr[$count][ $tblPrpty['compare_field_name'] ][0].'</td>';
                    $count++;
                }
            }

            $main_table['body'] .= '</tr>';
        }
    }

}


?>

<div id="page-compare-wrap">
    <div class="container">

        <div class="title-box">
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <h1><?php echo str_replace('[count]', '<span class="comcount">'.$cQueryFound.'</span>', get_the_title()); ?></h1>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="view-hint-list">
                        <?php
                        if($cQueryFound > 4) {
                            $viewlist   = '';
                            $viewcount  = 1;
                            while($viewcount <= $cQueryFound) {
                                $viewlist .= '<li><span id="viewItem-'.$viewcount.'" class="label label-default">'.$viewcount.'</span></li>';
                                $viewcount++;
                            }

                            echo '<ul class="views-list list-inline p0 n0">'.$viewlist.'</ul>';
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-4 hidden-xs dlxls-box">
                    <button class="btn btn-link xls" id="dlXls" onclick="tableToExcel('compare-table', 'W3C Example Table')"><?php echo $headData['compare_dlxls_btnlbl']; ?></button> 
                </div>
            </div>
        </div>
    

        <div class="products-nav hidden-xs">
            <ul class="lsnone list-inline">
                <li><button class="btn btn-link comparePrev" data-action="prev"><img src="<?php echo IMG_URL; ?>/compare-prev.svg" alt="Prev Product"></button></li>
                <li><button class="btn btn-link compareNext" data-action="next"><img src="<?php echo IMG_URL; ?>/compare-next.svg" alt="Next Product"></button></li>
            </ul>
        </div>

    </div>


    <div class="container container-table-wrap compare-table-wrap">
        <div class="compare-flex">
            <div class="static-table">
                <?php echo $labelsTable; ?>
            </div>
            <div class="scroll-table-box">
                <table id="compare-table" class="compare-table" cellspacing="0" width="100%">
                    <thead><?php echo $main_table['head']; ?></thead>
                    <tbody><?php echo $main_table['body']; ?></tbody>
                </table>
            </div>
            <input type="hidden" id="slidesPos" data-max="<?php echo (is_mobile() ? $productsCount : ($productsCount - 3)); ?>" data-first="1">
            <input type="hidden" id="swipedPos" value="0">
            <input type="hidden" id="maxWidth" value="<?php echo ( ($productsCount * 295) / 2); ?>">
            <input type="hidden" id="totalItems" value="<?php echo $productsCount; ?>">
        </div>
    </div>
</div>




<script>
jQuery(function($){


    // fix td height across tables (maybe move to top)
    $('.static-table table tbody tr').each(function(i) {
        $(this).css( 'height', $('#compare-table tbody tr').eq(i).outerHeight() );
    });


    // turn products table header affixed
    $('#compare-table thead').height( $('#compare-table thead').height() );

    $('#compare-table thead, .products-nav ul').affix({ 
        offset: {
            top: 100,
            bottom: function () {
                return (this.bottom = $('.footer').outerHeight(true) + 200)
            }
        }
    });


    function runSwipeLeft(maxWidth, maxCount) {
        var firstSlide  = parseInt( $("#slidesPos").attr('data-first') );
        var swipePos    = parseInt( $("#swipedPos").val() );
        var newVal      = swipePos + 295;

        if( newVal <= maxWidth) { 
            $("#swipedPos").val( newVal );
            $('.scroll-table-box').animate( { scrollLeft: newVal }, 100);
            // $('.scroll-table-box').scrollLeft(newVal);
        }

        if(firstSlide > 0 && firstSlide < maxCount) {
            $("#slidesPos").attr('data-first', (firstSlide + 1) );
        }
    }

    function runSwipeRight(maxWidth, maxCount) {
        var firstSlide  = parseInt( $("#slidesPos").attr('data-first') );
        var swipePos    = parseInt( $("#swipedPos").val() );
        var newVal      = swipePos - 295;

        if( newVal >= 0) { 
            $("#swipedPos").val( newVal );
            
            $('.scroll-table-box').animate( { scrollLeft: newVal }, 100);
            // $('.scroll-table-box').scrollLeft(newVal);
        }

        if(firstSlide > 1 && firstSlide < maxCount) {
            $("#slidesPos").attr('data-first', (firstSlide - 1) );
        }
    }


    function visibiltyTheadTh() {

        var firstSlide  = parseInt( $("#slidesPos").attr('data-first') );
        var lastSlide   = firstSlide + 3;
        var totalItems  = $("#totalItems").val();

        console.log('firstSlide: ' + firstSlide);
        console.log('lastSlide: ' + lastSlide);

        if( $('body').hasClass('mobile') ) {
            var nextSlide = firstSlide + 1;
            $('#compare-table thead th[data-index="'+nextSlide+'"]').fadeIn();
            $('#compare-table thead th[data-index="'+firstSlide+'"]').fadeOut();
        }
        else {
            for (i = firstSlide; i <= lastSlide; i++) {
                $('#compare-table thead.affix th[data-index="'+i+'"]').fadeIn();
            }

            for (i = 1; i < firstSlide || i > lastSlide; i++) {
                $('#compare-table thead.affix th[data-index="'+i+'"]').fadeOut();
            }

        }
    }




    // Build swipe element
    var compareTable    = document.getElementById('compare-table');
    var tableEle        = new Hammer( compareTable );
    var maxWidth        = $('#maxWidth').val();
    var maxCount        = $("#slidesPos").attr('data-max');


    // listen to events...
    tableEle.on("swipeleft", function(ev) {
        runSwipeLeft(maxWidth, maxCount);
    });

    $('.compareNext').click(function() {
        runSwipeLeft(maxWidth, maxCount);
        visibiltyTheadTh();
    });


    tableEle.on("swiperight", function(ev) {
        runSwipeRight(maxWidth, maxCount);
    });
    
    $('.comparePrev').click(function() {
        runSwipeRight(maxWidth, maxCount);
        visibiltyTheadTh();
    });

});


jQuery(document).ready(function($) {

    
    // function visibiltyTheadThLoad() {

    //     var firstSlide  = parseInt( $("#slidesPos").attr('data-first') );
    //     var lastSlide   = firstSlide + 3;
    //     var totalItems  = $("#totalItems").val();

    //     if( $('body').hasClass('mobile') ) {
    //         for (i = firstSlide + 1; i > firstSlide; i++) {
    //             $('#compare-table thead th[data-index="'+i+'"]').fadeOut();
    //             i++;
    //         }
    //     }
    //     else {
    //         for (i = lastSlide + 1; i <= totalItems; i++) {
    //             $('#compare-table thead th[data-index="'+i+'"]').fadeOut();
    //         }
    //     }
    // }
    // visibiltyTheadThLoad();
 
});


var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>

<?php
get_sidestrip();
get_footer();
?>