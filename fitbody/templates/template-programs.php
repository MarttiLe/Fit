<?php
/**
* Template Name: Programs page
*/
?>

<?php get_header(); ?>

<?php
    $user_programs = theme_get_user_programs();
    $is_logged_in = is_user_logged_in();
    if($is_logged_in) {
        $user_id = get_current_user_id();
    }

    $all_programs = new WP_Query([
        'posts_per_page'    => -1,
        'post_type'         => 'program',
        'status'            => 'publish',
        'post_parent'       => 0,
        'suppress_filters'  => false
    ]);

    $args = array(
        'post_type'         => 'program',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
        'post_parent'       => 0,
        'suppress_filters'  => false,
    );


        $args['tax_query'] = array(
            array(
                'taxonomy' => 'program_cat',
                'field' => 'slug',
                'terms' => '',
								'operator' => 'AND',
            )
        );
    $all_programs = new WP_Query($args);
    $categories = get_categories([
        'hide_empty'    => true,
        'taxonomy' => 'program_cat'
    ]);

    $programs_for_category = [];


    $all_programs = $all_programs->posts;
    $all_programs = [];

    if(!empty($all_programs)) {
        foreach($all_programs as $key => $program) {
            $product_id = get_field('linked_product', $program->ID);
            $is_valid = theme_validate_program_product($program->ID, $product_id);

            if($is_valid) {
                $program_cats = get_the_terms( $program->ID, 'program_cat' );
                if (is_array($program_cats)){
                     foreach ($program_cats as $program_cat){
                         $cat_slug = $program_cat->slug;
                     }
                }

            } else {
                unset($all_programs[$key]);
            }
        }
    }

    $banner_image = get_the_post_thumbnail_url(get_the_ID(), 'page-banner');
    $heading_style = '';
    if(!empty($banner_image)) {
        $heading_style = ' style="background-image: url('. $banner_image .');"';
    }
?>

<div class="page-heading"<?php echo $heading_style; ?>>
    <div class="container">
        <div class="page-heading__inner">
            <div class="page-heading__breadcrumb">
                <?php theme_breadcrumbs_list(); ?>
            </div>

            <h1 class="page-heading__title h2"><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<section class="programs page-content">
    <div class="container">
      <div class="program-page-content__inner">
              <form id="filter-form" method="post">
              <h4 class="filter-head">Leia sobiv treening:</h4>
              <div class="programs__filters nav-tabs nav-tabs--margin-bottom-lg">
              <select name="kes" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
                  <option value="none"><?php echo __( 'Vali sugu', 'fitbody-theme' ); ?></option>
                  <option value="mees" data-tabs-id="programs" data-tabs-content-id="mees"><?php echo __( 'Mees', 'fitbody-theme' ); ?></option>
                  <option value="naine" data-tabs-id="programs" data-tabs-content-id="naine"><?php echo __( 'Naine', 'fitbody-theme' ); ?></option>
              </select>
          </div>
          <div class="programs__filters nav-tabs nav-tabs--margin-bottom-lg">
              <select name="kus" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
                  <option value="none"><?php echo __( 'Kus treenid', 'fitbody-theme' ); ?></option>
                  <option value="gym" data-tabs-id="programs" data-tabs-content-id="gym"><?php echo __( 'Gym', 'fitbody-theme' ); ?></option>
                  <option value="kodus" data-tabs-id="programs" data-tabs-content-id="kodus"><?php echo __( 'Kodus', 'fitbody-theme' ); ?></option>
              </select>
          </div>
          <div class="programs__filters nav-tabs nav-tabs--margin-bottom-lg">
              <select name="miks" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
                  <option value="none"><?php echo __( 'Miks', 'fitbody-theme' ); ?></option>
                  <option value="kaal" data-tabs-id="programs" data-tabs-content-id="kaal"><?php echo __( 'Kaalu langetada', 'fitbody-theme' ); ?></option>
                  <option value="keha" data-tabs-id="programs" data-tabs-content-id="keha"><?php echo __( 'Kehakuju muuta', 'fitbody-theme' ); ?></option>
              </select>
          </div>
          <div class="programs__filters nav-tabs nav-tabs--margin-bottom-lg">
              <select name="kui_tihti" class="nav-tabs__anchor js-nav-tab is-active filter-checkbox">
                  <option value="none"><?php echo __( 'Kui tihti treenid', 'fitbody-theme' ); ?></option>
                  <option value="2-3" data-tabs-id="programs" data-tabs-content-id="2-3"><?php echo __( '2-3', 'fitbody-theme' ); ?></option>
                  <option value="4" data-tabs-id="programs" data-tabs-content-id="4"><?php echo __( '4+', 'fitbody-theme' ); ?></option>
              </select>
          </div>


      </form>

        </div>

        <div class="programs__content nav-tabs-content">
            <div class="programs__content tab-content js-nav-tab-content is-active" value="all" data-tabs-id="programs" data-tabs-content-id="all">
                <?php if(!empty($all_programs)) : ?>
                <ul class="programs__items programs-list">
                    <?php foreach($all_programs as $program) : ?>
                        <li class="programs-list__item">
                            <?php
                                $card_attr = [
                                    'post_id'   => $program->ID,
                                ];

                                if($is_logged_in) {
                                    $program_is_owned = theme_get_program_ownership_status($program->ID, false, $user_programs);
                                    $card_attr['is_owned'] = $program_is_owned;
                                    if($program_is_owned) {
                                        $card_attr['ownership_dates'] = theme_get_program_ownership_dates($program->ID, false, $user_programs);
                                    }
                                }

                                get_template_part('templates/components/card-program', null, $card_attr);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else : ?>
                
                <?php endif; ?>
            </div>

            <?php  foreach($categories as $category) : ?>
                <div class="programs__content tab-content js-nav-tab-content" data-tabs-id="programs" value="<?php echo $category->slug; ?>" data-tabs-content-id="<?php echo $category->slug; ?>">
                    <?php if(!empty($all_programs[ $category->slug ])) : ?>
                        <ul class="programs__items programs-list">
                            <?php foreach($all_programs[ $category->slug ] as $program) : ?>
                                <li class="programs-list__item">
                                    <?php
                                    $card_attr = [
                                        'post_id'   => $program->ID,
                                    ];

                                    if($is_logged_in) {
                                        $program_is_owned = theme_get_program_ownership_status($program->ID, false, $user_programs);
                                        $card_attr['is_owned'] = $program_is_owned;
                                        if($program_is_owned) {
                                            $card_attr['ownership_dates'] = theme_get_program_ownership_dates($program->ID, false, $user_programs);
                                        }
                                    }

                                    get_template_part('templates/components/card-program', null, $card_attr);
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>

                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<script>
//$('.filter-checkbox').on("change", function(event) {

  //console.log("heihei");
//});

//$('#filter-form').on('submit', function(event) {
  $('.filter-checkbox').on("change", function(event) {
    event.preventDefault(); // Prevent the form from submitting normally

    var selected_categories = [];

    // Loop through each select element and add its selected value to the selected_categories array
    $('select[name=kes], select[name=kus], select[name=miks], select[name=kui_tihti]').each(function() {
        var selected_value = $(this).val();
        if (selected_value != 'none') { // Ignore the "none" option
            selected_categories.push(selected_value);
        }
    });

    // Make an AJAX request to the filter_programs function
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'filter_programs',
            categories: selected_categories
        },
        success: function(response) {
            // Parse the response as JSON
            str = response.substring(0, response.length - 3);
            $('.programs__content.is-active').html(str);
        }
    });
});

myFunction();
//$('#filter-form').on('submit', function(event) {
    function myFunction() {


    var selected_categories = [];

    // Loop through each select element and add its selected value to the selected_categories array
    $('select[name=kes], select[name=kus], select[name=miks], select[name=kui_tihti]').each(function() {
        var selected_value = $(this).val();
        if (selected_value != 'none') { // Ignore the "none" option
            selected_categories.push(selected_value);
        }
    });

    // Make an AJAX request to the filter_programs function
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'filter_programs',
            categories: selected_categories
        },
        success: function(response) {
            // Parse the response as JSON
            str = response.substring(0, response.length - 3);
            $('.programs__content.is-active').html(str);
        }
    });
    }
</script>

<?php get_footer(); ?>
