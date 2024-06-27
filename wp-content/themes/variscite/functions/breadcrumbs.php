<?php
function breadcrumbs() {

    $cpid           = get_the_ID();
    $color          = get_field('pp_page_breadcrumbs_color', $cpid);

    $breadcrumbs    = array();
    $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="'.get_bloginfo('wpurl').'" itemprop="item"><span itemprop="name">'.__('Home', THEME_NAME).'</span></a> <meta itemprop="position" content="1" /></li>';

    $custom_bc      = get_field('custom_breadcrumbs', get_the_ID());



    /*************************************************
    ** CUSTOM BREADCRUMB STRUCTURE (YES!)
    *************************************************/
    if( !empty($custom_bc[0]) && $custom_bc[0] == 'on' ) {

        $bcrumbs = get_field('cbreadcrumbs_structure', get_the_ID());

        if( !empty($bcrumbs) ) {
            foreach($bcrumbs as $bc) {
                $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="'.get_permalink($bc['bc_page']->ID).'" itemprop="item"><span itemprop="name">'.$bc['bc_page']->post_title.'</span></a>  <meta itemprop="position" content="2" /></li>';
            }
        }
    }


    /*************************************************
    ** STANDARD CATEGORY
    *************************************************/
    elseif( is_category() ) {
        $ccat           = get_category( get_query_var( 'cat' ) );
        $catlink        = get_category_link( $ccat->term_id );
        $custom_title   = get_field('custom_cat_title', 'category_'.$ccat->term_id);;

        $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="'.esc_url( $catlink ).'" itemprop="item"><span itemprop="name">'.($custom_title ? $custom_title : $ccat->name).'</span></a>  <meta itemprop="position" content="2" /></li>';
    }



    /*************************************************
    ** POSTS & PAGES
    *************************************************/
    elseif( is_singular('post') || is_singular('page') ) {
        $postitle   = get_the_title();
        $postlink   = get_permalink();

        // FOR POSTS: CATEGORY
        if(is_singular('post')) {
            $postterms  = wp_get_post_terms( $cpid, 'category' );
            if( !empty($postterms) ) { $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="2" /><a href="'.esc_url( get_term_link( $postterms[0]->term_id ) ).'" itemprop="item"><span itemprop="name">'.$postterms[0]->name.'</span></a></li>'; }
        }


        // FOR PAGES: PARENT PAGE
        if(is_singular('page')) {
            $parent = wp_get_post_parent_id( $cpid );
            if($parent != '0') { $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="2" /><a href="'.esc_url( get_permalink( $parent ) ).'" itemprop="item"><span itemprop="name">'.get_the_title($parent).'</span></a></li>'; }
        }

        $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="3" /><a href="'.esc_url( $postlink ).'" itemprop="item"><span itemprop="name">'.$postitle.'</span></a></li>';
    }


    /*************************************************
    ** SINGLE SPECS
    *************************************************/
    elseif(is_singular('specs')) {

        $color          = get_field('specs_breadcrumbs_color', 'option');
        
        $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="1" /><a href="'.esc_url( get_bloginfo('wpurl').'/products/' ).'" itemprop="item"><span itemprop="name">'.__('Products', THEME_NAME).'</span></a></li>';

        $postitle       = get_the_title();
        $postlink       = get_permalink();

        // post terms
	    $posterms       = wp_get_post_terms($cpid, 'products');
        $termparent     = $posterms[0]->parent;
        
        // GET PRODUCT CATEGORY
        $cParentLinkParent  = get_field('cperm_bcurl_parent', $posterms[0]);
        $cParentLinkChild   = get_field('cperm_bcurl_child', $posterms[0]);

        if($termparent != 0) {
            $parent         = get_term($termparent, 'products');
            $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="1" /><a href="'.($cParentLinkParent ?  $cParentLinkParent : get_term_link($parent->term_id)).'" itemprop="item"><span itemprop="name">'.$parent->name.'</span></a></li>';
        }

        if( !empty($posterms) ) {$breadcrumbs[]  = '<li class="test" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="2" /><a href="'.($cParentLinkChild ? $cParentLinkChild : get_term_link($posterms[0]->term_id)).'" itemprop="item"><span itemprop="name">'.$posterms[0]->name.'</span></a></li>';}
        $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="3" /><a href="'.esc_url( $postlink ).'" itemprop="item"><span itemprop="name">'.$postitle.'</span></a></li>';
    }




    /*************************************************
    ** PRODUCTS (SPECS) TAXONOMY
    *************************************************/
    elseif( is_tax( 'products' ) ) {

        $ctax = get_queried_object();

        if($ctax->parent != 0) {
            $parent = get_term($ctax->parent, 'products');
            $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="2" /><a href="'.get_term_link($parent->term_id).'" itemprop="item"><span itemprop="name">'.$parent->name.'</span></a></li>';
        }

        $breadcrumbs[]  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"> <meta itemprop="position" content="2" /><a href="'.get_term_link($ctax->term_id).'" itemprop="item"><span itemprop="name">'.$ctax->name.'</span></a></li>';
    }

    return '<ul class="list-inline p0 breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList" '.($color ? 'style="color: '.$color.';"' : '').' >'.implode(' > ', $breadcrumbs).'</ul>';
}

function add_breadcrumbs_shortcode()
{
    ?>
    <div class="container relative">
        <div><?php echo breadcrumbs(); ?></div>
    </div>
    <?php
}

add_shortcode( 'custom_breadcrumbs', 'add_breadcrumbs_shortcode' );
?>