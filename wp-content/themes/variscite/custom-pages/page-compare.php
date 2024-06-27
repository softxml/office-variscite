<?php
/*
Template Name: Compare
*/
get_header();


$pageID         = get_the_ID();
$headData       = get_field('compare_dlxls_group');
$addProdLink    = get_field('compare_addproducts_link');
$urlProducts    = $_SERVER['QUERY_STRING'];
$cQueryFound    = '';
$npc_tablecls   = '';


/*************************************************
** BUILD WRAP TABLE HEADER
*************************************************/
$fixedTHeadData = '';
$fixedTHeadData     .= '<th class="addProducts" data-index="0"><a href="'.$addProdLink.'"><i class="fa fa-plus-circle"></i> '.__('Add Products', THEME_NAME).'</a></th>';

    // if no products selected add text
    if( empty($urlProducts) ) {

        $npc_tablecls   = 'npc-table';
        $npc_settings   = get_field('noprod_settings', $pageID);
        $npc_title      = $npc_settings['npc_title'];
        $npc_subtitle   = $npc_settings['npc_subtitle'];
        $npc_arrow      = $npc_settings['npc_arrow'];


        $fixedTHeadData .= '
        <th class="noProductsHelper" colspan="4">
            <h3 class="npc-title">'.$npc_title.'</h3>
            <h4 class="npc-subtitle">'.$npc_subtitle.'</h4>
            <div class="npc-arrow"><img src="'.$npc_arrow.'" alt="arrow"></div>
        </th>
        ';
    }



/********************************************************
**  TABLE PROPERTIES (FIRST COLOUMN)
********************************************************/
$table_properties   = get_field('compare_table_properties');

if($urlProducts) {

    $tableBodyArr   = array();
    $AllSpecsArr   = array();
    $urlProducts    = explode( ',', explode('=', $urlProducts)[1] );


    /*********************************************
    ** BODY TABLE LABELS & VALUE KEYS
    *********************************************/
    $counter            = 2;
    $headingList        = '';


    foreach($table_properties as $tprop) {
        if( !empty($tprop['compare_section_title'][0]) ) {
            $tableBodyArr[]   = '<th>'.$tprop['compare_field_name'].'</th>';
            $headingList    .= '<tr><th>'.$tprop['compare_field_name'].'</th></tr>';
        }
        else {
            $tableBodyArr[]   = '<td class="sub">'.$tprop['compare_field_name'].'</td>';
            $headingList    .= '<tr><td class="sub">'.$tprop['compare_field_name'].'</td></tr>';
        }
    }
    $headingList = '<td> <table class="table table-responsive table-headings table-sub">'.$headingList.'</table> </td>';




	
    /*************************************************
    ** COMPARE TABLE BODY
    *************************************************/
    $indexCount     = 1;
    $prodBriefs		= '';
    $prodTables     = '';
    $compareKeys    = array();


    // The Loop
    $cQuery         = new WP_Query( array( 'post_type' => array('specs'), 'post__in' => $urlProducts, 'posts_per_page' => -1, 'meta_key' => 'comparing_order_priority', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
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
            $fixedTHeadData .= '
            <th data-index="'.$indexCount.'">
                <div class="innner-box">
                    <div class="thumb"><a href="'.$plink.'">'.$thumb.'</a></div>
                    <div class="title"><a href="'.$plink.'" class="c1">'.$title.'</a></div>
                    <div class="actions">
                        <div class="row">
                            <div class="col-md-8 col-xs-12"><a href="'.$plink.'#get-a-quote" class="btn btn-warning upcase compareGetQuote"><span>'.__('Get a quote', THEME_NAME).'</span> <img src="'.IMG_URL.'/button-arrow-mini.png" alt="Arrow"></a></div>
                            <div class="col-md-4 col-xs-12"><button class="btn btn-link c00 uline removeItem" data-itemid="'.$pid.'">'.__('Remove', THEME_NAME).'</button></div>
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

} // end: if($urlProducts)


/*************************************************
** TESTING ALTERNATE TABLE BODY LOOP
*************************************************/
$allTableData = '';
// echo count($table_properties) . '<br>'; 
if( !empty($table_properties) ) {
    foreach($table_properties as $key => $tblPrpty) {
        
        $count          = 0;
        $allTableData   .= '<tr>';

        if( !empty($tblPrpty['compare_section_title'][0]) ) {
            $allTableData .= '<th>'.$tblPrpty['compare_field_name'].'</th>';

            while($count < $cQueryFound) {
                $index = $count + 1;
                $allTableData .= '<th data-index="' . $index . '"></th>';
                $count++;
            }


            
            // IF NO PRODUCTS WERE SET - APPLY DESIGN = 4 EMPTY CELLS
            if( empty($urlProducts) ) {
                $allTableData .= str_repeat("<th></th>", 4);
            }
        }
        else {
            $allTableData .= '<td>'.$tblPrpty['compare_field_name'].'</td>';
            
            while($count < $cQueryFound) {
                $index = $count + 1;
                
                if( empty($allSpecsArr[$count][ $tblPrpty['compare_field_name'] ][0]) ) {
                    $allSpecsArr[$count][ $tblPrpty['compare_field_name'] ][0] = '';
                }
                $allTableData .= '<td data-index="' . $index . '">'.$allSpecsArr[$count][ $tblPrpty['compare_field_name'] ][0].'</td>';
                
                $count++;
            }


            
            // IF NO PRODUCTS WERE SET - APPLY DESIGN = 4 EMPTY CELLS
            if( empty($urlProducts) ) {
                $allTableData .= str_repeat("<td></td>", 4);
            }

        }

        $allTableData .= '</tr>';
    }
}
?>


<div id="page-compare-wrap">
    <div class="container">

        <div class="title-box">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <h1><?php echo str_replace('[count]', '<span class="comcount">'.$cQueryFound.'</span>', get_the_title()); ?></h1>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="view-hint-list">
                        <?php
                            $viewlist   = '';
                            $viewcount  = 1;
                            while($viewcount <= $cQueryFound) {
                                $viewlist .= '<li><span id="viewItem-'.$viewcount.'" class="label label-default">'.$viewcount.'</span></li>';
                                $viewcount++;
                            }

                            echo '<ul class="views-list list-inline p0 n0">'.$viewlist.'</ul>';
                        ?>
                    </div>
                </div>
                <div id="dlxls-box" class="col-md-3 hidden-xs dlxls-box">
                    <button class="btn btn-link xls" id="dlXls"><?php echo $headData['compare_dlxls_btnlbl']; ?></button>
                </div>
            </div>
        </div>
    
        <div class="products-nav hidden-xs">
            <ul class="lsnone list-inline">
                <li><button class="btn btn-link compare-prev" data-current="2" data-action="prev"><img src="<?php echo IMG_URL; ?>/compare-prev.svg" alt="Prev Product"></button></li>
                <li><button class="btn btn-link compare-next" data-current="6" data-action="next"><img src="<?php echo IMG_URL; ?>/compare-next.svg" alt="Next Product"></button></li>
            </ul>
        </div>
    </div>


    <div class="container container-table-wrap compare-table-wrap">
        <div class="compare-table-box">
			<table id="compare-table" class="compare-table <?php echo $npc_tablecls; ?>" cellspacing="0" width="100%">
				<thead><?php echo $fixedTHeadData; ?></thead>
				<tbody>
                    <?php echo $allTableData; ?>
				</tbody>
            </table>
            <input type="hidden" id="currentSlide" value="2">
        </div>
    </div>


</div>


<script>
jQuery(document).ready(function($) {

    var lastUrl = localStorage.getItem('lasturl');
    var prev_cur = parseInt( $('.compare-prev').attr('data-current') );
    var next_cur = parseInt( $('.compare-next').attr('data-current') );

    $('.compareGetQuote').click(function() {
        dataLayer.push({'event': 'ComparisonPage-GetQuote-Click'});
    });

    // set if product TH is visible
    function setThVisible(first, last) {
        
        // console.log(first, last);
        
        $('.views-list li span').each(function() {
            $(this).removeClass('active');
        });
        
        i = first;
        while (i >= first && i < last) {
            // console.log("f12 The number is " + i);
            $('.not-mobile .views-list li:nth-child('+ i +') span').addClass('active');
            i++;
        }
    }

    $('.compare-table-box thead, .products-nav ul').affix({
        offset: {
            top: 100,
            bottom: function () {
                return (this.bottom = $('.footer').outerHeight(true) + 200)
            }
        }
    });

    function prodVisibility() {
        $('.compare-table [data-index]').each(function(){
            if( $(this).attr('data-index') > 0 && $(this).attr('data-index') < 5 ) {
                $(this).addClass('active');
            }
            else if( $(this).attr('data-index') > 5 ) {
                $(this).removeClass('active');
            }
        });
    }

    prodVisibility();
    setThVisible(1,5);

    /*************************************************
     ** REMOVE TABLE COLUMN IN COMPARE PAGE
     *************************************************/
    $('#page-compare-wrap .removeItem').click(function ( event ) {

        var compareVals = localStorage.getObj('compare');
        var itemid      = $(this).attr('data-itemid');
        var ndx         = $(this).parents('th').index(); // Get index of parent TD among its siblings (add one for nth-child)

        // Find all elements with the same index
        $('.compare-table [data-index="' + ndx + '"]').remove();

        // save new compare values
        compareVals.remove( itemid );
        localStorage.setObj('compare', compareVals);

        // update url with new structure
        curl            = location.href.split("?")[0];
        vals            = localStorage.getObj('compare');
        newurl          = curl + '?c=' + vals;

        window.history.pushState('object', document.title, newurl);
        $('.comcount').text(vals.length);

        var navItems = $('#page-compare-wrap .views-list li').length;
        if (navItems > vals.length) {
            $('#page-compare-wrap .views-list li:last-child').remove();
        }

        // $('.compare-table tr th:not(.addProducts)').length
        if (vals.length < 5) {
            $('#page-compare-wrap .products-nav').hide();
        }
        
        // reset data-current values
        $('.compare-prev').attr('data-current', (prev_cur));
        $('.compare-next').attr('data-current', (next_cur));

        // re-initiate data-index values
        $('.compare-table tr').each(function(){
            var rowCells = $(this).find('[data-index]:not(.addProducts)');
            rowCells.each(function(key){
                $(this).attr('data-index', key + 1);
            });
        });

        prodVisibility();
        setThVisible(1,5);

        if (vals.length < 1) {
            window.location.replace(lastUrl);
        }
    });


    // HIDE & SHOW COLUMN IN TABLE
    if (window.matchMedia("(max-width: 767px)").matches) {

        var next_max = parseInt("<?php echo is_array($urlProducts) ? count($urlProducts) : 0; ?>") + 1;

        $('#compare-table').swipe({
            //Generic swipe handler for all directions
            'allowPageScroll': 'vertical',
            swipe: function (event, direction, distance, duration, fingerCount, fingerData) {


                if (direction == 'left') {
                    var currentSlide = parseInt($('#currentSlide').val()) + 1;

                    if (currentSlide >= 2 && currentSlide <= next_max) {
                        $('.compare-table th:not(:nth-child(1)), .compare-table td:not(:nth-child(1)').removeClass('active');
                        $('.compare-table th:nth-child(' + currentSlide + '), .compare-table td:nth-child(' + currentSlide + ')').addClass('active');
                        // $('.compare-table th:nth-child('+currentSlide+'), .compare-table td:nth-child('+currentSlide+')').hide();

                        if (currentSlide > next_max) {
                            currentSlide = next_max;
                        }

                        console.log('Left - CurrentSLide: ' + currentSlide);
                        $('#currentSlide').val(currentSlide);
                    }
                } else {
                    var currentSlide = parseInt($('#currentSlide').val());

                    if (currentSlide > 2) {
                        currentSlide = parseInt(currentSlide - 1);
                        $('.compare-table th:not(:nth-child(1)), .compare-table td:not(:nth-child(1))').removeClass('active');
                        $('.compare-table th:nth-child(' + currentSlide + '), .compare-table td:nth-child(' + currentSlide + ')').addClass('active');


                        console.log('Right - CurrentSLide: ' + currentSlide);
                        $('#currentSlide').val(currentSlide);
                    }
                }

            }
        });

    } else {
        $('.compare-prev, .compare-next').click(function() {

            var action = $(this).attr('data-action');
            var prev_cur = parseInt( $('.compare-prev').attr('data-current') );
            var next_cur = parseInt( $('.compare-next').attr('data-current') );
            var next_max = $('.compare-table thead th:not(.addProducts)').length + 2;

            if(action == 'prev') {

                // actual values
                real_prev = prev_cur - 1;
                real_next = next_cur - 1;

                if( real_prev > 1 ) {

                    // show next index
                    $('th:nth-child(' + (real_prev + 4) + ')').removeClass('active');
                    $('td:nth-child(' + (real_prev + 4) + ')').removeClass('active');
                    
                    // show prev index
                    $('th:nth-child(' + real_prev + ')').addClass('active');
                    $('td:nth-child(' + real_prev + ')').addClass('active');


                    $('.compare-prev').attr('data-current', (real_prev));
                    $('.compare-next').attr('data-current', (real_next));

                    
                    var navprev_first = real_prev - 1;
                    var navprev_last = real_next - 1;

                    setThVisible( navprev_first, navprev_last );
                }

            }

            else if (action == 'next' && next_cur < next_max) {

                // show prev index
                $('th:nth-child(' + (next_cur - 4) + ')').removeClass('active');
                $('td:nth-child(' + (next_cur - 4) + ')').removeClass('active');

                // show next index
                $('th:nth-child(' + (next_cur) + ')').addClass('active');
                $('td:nth-child(' + (next_cur) + ')').addClass('active');

                $('.compare-prev').attr('data-current', (prev_cur + 1));
                $('.compare-next').attr('data-current', (next_cur + 1));

                // actual values
                real_prev = prev_cur;
                real_next = next_cur;

                setThVisible(real_prev, real_next);
            }

        });
    }
});


</script>



<?php
get_sidestrip();
get_footer();
?>