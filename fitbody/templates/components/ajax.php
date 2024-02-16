// CHANGE ACTIVE CATEGORY (APPLY FILTERS AND LOAD NEW CONTENT)
$(document).on('click', '.filters-post', function () {

    $('#allEvents').empty();
    $('.event-filters').addClass('disabled');

    $('.all-events-container .loader').css('display', 'block');

    // Get active event type
    //var eventType = $('#eventTypeFilters li.control label input:checked').val();
    let eventTypes = new Array();
    $('#eventTypeFilters li.control label input:checkbox:checked').each(function() {
        if($(this).val() == "online" || $(this).val() == "consultation"){
            eventTypes = new Array();
        }
        eventTypes.push($(this).val());
    });

    // Get active event categories
    var eventCat = [];
    $('#eventCategoryFilters input:checkbox.eventCat').each(function () {
        if (this.checked) {
            eventCat.push($(this).val());
        }
    });

    data = {
        action: 'ajax_category_posts',
        eventType: eventTypes,
        postType: 'event',
        eventCategories: eventCat,
        postCount: 0,
        //page_no: 1
    };

    $.ajax({
        url: "<?php echo $addAjaxLangQuery; ?>",
        type: 'POST',
        data: data,
        dataType: 'json',

        success: function (data) {
            $('.all-events-container .loader').hide('1000');
            $('#postCount').val(data.postCount);
            $('#totalCount').val(data.postCount);
            $("#allEvents").append(data.html); // This will be the container where our content will be loaded
            $('.event-filters').removeClass('disabled');


        },

        error: function (errorObject) {
            console.log(errorObject.responseText);
            $('.all-events-container .loader').hide('1000');
            $('.event-filters').removeClass('disabled');
            $('#allEvents').append('<p>Something went wrong...</p>');
        }
    });
});



<ul class="programs__filters nav-tabs nav-tabs--margin-bottom-lg"><h4>Kes:</h4>
                <div class="valik"><li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="mees" data-tabs-id="programs" data-tabs-content-id="mees"><?php echo __( 'Mees', 'fitbody-theme' ); ?></label>
                <li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="naine" data-tabs-id="programs" data-tabs-content-id="naine"><?php echo __( 'Naine', 'fitbody-theme' ); ?></label></li></div></ul>
                <ul class="programs__filters nav-tabs nav-tabs--margin-bottom-lg"><h4>Kus:</h4>
                <div class="valik"><li class="nav-tabs__item program "><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="gym" data-tabs-id="programs" data-tabs-content-id="gym"><?php echo __( 'Gym', 'fitbody-theme' ); ?></label></li>
                <li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="kodus" data-tabs-id="programs" data-tabs-content-id="kodus"><?php echo __( 'Kodus', 'fitbody-theme' ); ?></label></li></div></ul>
                <ul class="programs__filters nav-tabs nav-tabs--margin-bottom-lg"><h4>Miks:</h4>
                <div class="valik"><li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="kaal" data-tabs-id="programs" data-tabs-content-id="kaal"><?php echo __( 'Kaalu langetada', 'fitbody-theme' ); ?></label>
                <li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="keha" data-tabs-id="programs" data-tabs-content-id="keha"><?php echo __( 'Kehakuju muuta', 'fitbody-theme' ); ?></label></li></div></ul>
                <ul class="programs__filters nav-tabs nav-tabs--margin-bottom-lg"><h4>Kui tihti:</h4>
                <div class="valik"><li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="2-3" data-tabs-id="programs" data-tabs-content-id="2-3"><?php echo __( '2-3', 'fitbody-theme' ); ?></label>
                <li class="nav-tabs__item"><label><input type="checkbox" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox" value="4" data-tabs-id="programs" data-tabs-content-id="4"><?php echo __( '4+', 'fitbody-theme' ); ?></label></li></div></ul>







/***************** AJAX EVENT FILTER ********************/
function ajax_category_posts(){

    global $sitepress;
    global $post;


    $display_order = 'ASC';
    // Exclude hidden events query
    $hide_hidden_events_query = array(
        'key' => 'ekkkhidden_event',
        'compare' => 'NOT EXISTS'
    );
    // Exclude passed events query
    $hide_passed_events_query = array(
        'key' => 'ekkkend_date',
        'value' => strtotime(date('m/d/Y')),
        'compare' => '>=',
    );
    // Event type restriction query (all by default, excluding online)
    $event_type_query = array(
        'key' => 'ekkkevent_type',
        'value' => array('webinar', 'course', 'conference', 'devprogram', 'consultation'),
        'compare' => 'IN',
    );
    $sees = "not";
    // Check event type, change required parameters
    if(isset($_POST['eventType']) && !empty($_POST['eventType'])) {
        if($_POST['eventType'] != 'all') {
            //if($_POST['eventType'] == 'online') {
            if(esc_html(implode(",", $_POST['eventType'])) == 'online') {
                // Restrict events to online only, instead of excluding them
                $event_type_query = array(
                    'key' => 'ekkkevent_type',
                    'value' => 'online',
                    //'compare' => '=',
                    'compare' => 'IN',
                );
                $sees = "is in";
                // Cancel passed events query as we need to show passed events in online type
                $hide_passed_events_query = array();
                // Reverse display order, as newest online events need to be shown first
                $display_order = 'DESC';
            } else {
                // Restrict event type query to active type
                $event_type_query = array(
                    'key' => 'ekkkevent_type',
                    //'value' => esc_html__($_POST['eventType']),
                    'value' => explode(",", esc_html(implode(",", $_POST['eventType']))),
                    //'compare' => '=',
                    'compare' => 'IN',
                );
                $sees = "is out";
                //$sees = esc_html(implode(",", $_POST['eventType']));
            }
        }
    }

    $array_as_string = "";
    //foreach($_POST['eventType'] as $test){
    //    $array_as_string = $array_as_string + $test;
    //}
    $array_as_string = implode(",", $_POST['eventType']);
    $array_as_string = esc_html($array_as_string);

    // GENERATE CATEGORY FILTERS LIST (ONLY CATEGORIES THAT HAVE ACTIVE EVENTS IN THEM)
    $category_list = '';
    $cats = get_terms('event_categories', array('hide_empty' => true, 'parent' => 0, 'orderby' => 'menu_order', 'order' => 'ASC'));

    $catsSlugs = array();
    foreach($cats as $cat) {
        array_push($catsSlugs, $cat->slug);
    }
    $sub_query = array(
        'key' => 'ekkkend_date',
        'value' => strtotime(date('m/d/Y h:i A')),
        'compare' => '>=',
    );

    $args = array(
        'post_type' => 'event',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        //'paged' => 1,
        'order' => $display_order,
        'tax_query' => array(
            array(
            'taxonomy' => 'event_categories',
                'field' => 'slug',
                'terms' => $catsSlugs,
            ),
        ),
        'meta_query' => array(
            'relation' => 'AND',
            $hide_hidden_events_query,
            $event_type_query,
            $hide_passed_events_query
        ),
    );
    /*
    $args = array('post_type' => 'event',
        'meta_key' => 'ekkkend_date',
        'posts_per_page' => '-1',
        'orderby' => 'meta_value_num',
        'paged' => 1,
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
                $sub_query,
        ),

        'tax_query' => array(
            array(
                'taxonomy' => 'event_categories',
                'field' => 'slug',
                'terms' => $catsSlugs,
            ),
        ),
    );
    */

    $loop = new WP_Query($args);
	$posts = $loop->posts;
    $hasCats = array();

    foreach($posts as $post):
        $event_type = get_the_terms($post->ID, 'event_categories');
        foreach($event_type as $type){
            $hasCats[$type->name] = $type->slug;
        }
    endforeach;
    wp_reset_query();
    asort($hasCats);

    /*$filledcats = [];

    foreach($cats as $cat) {

        $args = array(
            'post_type' => 'event',
            'post_status' => 'publish',
            'posts_per_page' => '-1',
            //'paged' => 1,
            'order' => $display_order,
            'tax_query' => array(
                array(
                'taxonomy' => 'event_categories',
                    'field' => 'slug',
                    'terms' => $cat->slug,
                ),
            ),
            'meta_query' => array(
                'relation' => 'AND',
                $hide_hidden_events_query,
                $event_type_query,
                $hide_passed_events_query
            ),
        );

        $loop_ct = new WP_Query($args);

        if($loop_ct->have_posts()) {
            $filledcats[$cat->name] = $cat->slug;
        }

        wp_reset_query();
    }
*/
    $category_array = array();



    // HTML output for category filters
/*    foreach($filledcats as $key => $value) {
        array_push($category_array, $value);
        //$category_list .= '<li><label><input type="checkbox" class="eventCat" name="'.$value.'" id="'.$value.'" value="'. $value .'"><span>'. __($key, 'startertheme').'</span></label></li>';
    }
*/
    foreach($hasCats as $key => $value) {
        array_push($category_array, $value);
        //$category_list .= '<li><label><input type="checkbox" class="eventCat" name="'.$value.'" id="'.$value.'" value="'. $value .'"><span>'. __($key, 'startertheme').'</span></label></li>';
    }

    $count = "polnud sees";

    foreach($cats as $cat) {
        if(in_array($cat->slug, $category_array)){
            $category_list .= '
            <li>
                <label>
                    <input type="checkbox" class="eventCat" name="'. $cat->slug .'" id="'. $cat->slug .'" value="'. $cat->slug .'">
                    <span class="checkbox-checked"><img src="' . get_template_directory_uri() . '/assets/img/checkbox.svg" alt=""></span>
                    <span class="checkbox-empty"></span>
                    <span class="checkbox-content">
                        '. __($cat->name, 'startertheme') .'
                    </span>
                </label>
            </li>
            ';
            //$category_list .= '<li><label><input type="checkbox" class="eventCat" name="'. $cat->slug .'" id="'. $cat->slug .'" value="'. $cat->slug .'"><span>'. __($cat->name, 'startertheme') .'</span></label></li>';
        } else {
            $category_list .= '
            <li class="disabled">
                <label>
                    <input type="checkbox" class="eventCat" name="'. $cat->slug .'" id="'. $cat->slug .'" value="'. $cat->slug .'" disabled>
                    <span class="checkbox-checked"><img src="' . get_template_directory_uri() . '/assets/img/checkbox.svg" alt=""></span>
                    <span class="checkbox-empty"></span>
                    <span class="checkbox-content">
                        '. __($cat->name, 'startertheme') .'
                    </span>
                </label>
            </li>
            ';
            //$category_list .= '<li class="disabled"><label><input type="checkbox" class="eventCat" name="'. $cat->slug .'" id="'. $cat->slug .'" value="'. $cat->slug .'" disabled><span>'. __($cat->name, 'startertheme') .'</span></label></li>';
        }
    }


    // GENERATE POSTS/EVENTS
    $postCountOld =  $_POST['postCount'];
    $active_categories = [];

    // Check which categories were active in filter and adjust query
    if(isset($_POST['eventCategories']) && !empty($_POST['eventCategories'])){
        $active_categories = $_POST['eventCategories'];
    } else {
        $allterms = get_terms(array(
            'taxonomy' => 'event_categories',
            'hide_empty' => true,
        ));
        foreach($allterms as $singleterm) {
            $active_categories[] = $singleterm->slug;
        }
    }

    /*$paged = ( $_POST['page_no'] ) ? $_POST['page_no'] : 1;
        $sub_query = array(
            'key' => 'ekkkend_date',
            'value' => strtotime(date('m/d/Y h:i A')),
            'compare' => '>=',
        );
    $argsCount = array('post_type' => 'event',
        'meta_key' => 'ekkkend_date',
        'meta_query' => array(
            $sub_query
        ),
    );

    $the_query = new WP_Query($argsCount);*/

    wp_reset_query();

    $args = array('post_type ' => 'event',
        'posts_per_page' => '-1',
        'post_status' => 'publish',
        //'paged' => $_POST['page_no'],
        'meta_key' => 'ekkkstart_date',
        'orderby' => 'meta_value_num',
        'order' => $display_order,
        'suppress_filters' => false,
        'tax_query' => array(
            array(
                'taxonomy' => 'event_categories',
                'field'    => 'slug',
                'terms'    => $active_categories,
            ),
        ),
        'meta_query' => array(
            'relation' => 'AND',
            $hide_hidden_events_query,
            $hide_passed_events_query,
            $event_type_query
        ),
    );

    $postCount = 0;
    $totalCount = 0;

    $final_events = new WP_Query($args);
    $totalCount = $final_events->found_posts;
    $postCount = 0;

    $html = ''; // Container for final events list output
    if ($final_events->have_posts()) {
        /*
        while ($final_events->have_posts()) : $final_events->the_post();

            $html .= generate_event_card($post, false);
            $postCount++;

        endwhile;
        */
        $i = 0;
        $hideCard = false;
        while ($final_events->have_posts()) : $final_events->the_post();
            /*if($i == 6){
                $hideCard = true;
            }*/
            $html .= generate_ekkk_event_card($post, false, $i, $hideCard, false, false, "all");
            $postCount++;
            $i++;

        endwhile;
        /*if($i >= 6){
            $html .= '
                <div class="ekkk-show-events">Vaata kõiki</div>
            ';
        }*/

    }

    $postCount = $postCountOld + $postCount;
    if($postCount > 0) {
        $data = array('html' => $html, 'postCount' => $postCount, 'typelist' => $category_list );
    } else {
        $data = array('html' => '<p>'. __('Events not found.', 'startertheme') .'</p>', 'postCount' => 0 );
    }

    //$data = array('html' => $category_list, 'postCount' => $postCount, 'typelist' => $category_list );

    echo json_encode($data);
    wp_reset_postdata();
    wp_die();
}

add_action( 'wp_ajax_ajax_category_posts', 'ajax_category_posts' );
add_action( 'wp_ajax_nopriv_ajax_category_posts', 'ajax_category_posts' );








<?php
$ajaxUrl = admin_url('admin-ajax.php');
?>





<section class="programs page-content">
  <form action="choose_programs.php" method="post">
    <div class="container">
      <div class="program-page-content__inner">
        <h4>Filtreeri kategooria järgi:</h4>

        <div id="valik1" class="valik1">
          <h4>Kes:</h4>
          <select name="who" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
            <option value="none">Vali</option>
            <option value="mees">Mees</option>
            <option value="naine">Naine</option>
          </select>
        </div>

        <div id="valik2" class="valik2">
          <h4>Kus:</h4>
          <select name="where" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
            <option value="none">Vali</option>
            <option value="gym">Gym</option>
            <option value="kodus">Kodus</option>
          </select>
        </div>

        <div id="valik3" class="valik3">
          <h4>Miks:</h4>
          <select name="why" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
            <option value="none">Vali</option>
            <option value="kaal">Kaalu langetada</option>
            <option value="keha">Kehakuju muuta</option>
          </select>
        </div>

        <div id="valik4" class="valik4">
          <h4>Kui tihti:</h4>
          <select name="howoften" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
            <option value="none">Vali</option>
            <option value="2-3">2-3</option>
            <option value="4+">4+</option>
          </select>
        </div>

        <button type="submit">Vali programmid</button>

      </div>
    </div>
  </form>
</section>