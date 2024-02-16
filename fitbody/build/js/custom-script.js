jQuery(function($) {
    var $filterCheckboxes = $('.js-nav-tab');
    var $filterContent = $('.js-nav-tab-content');
    var $filterAllCheckbox = $('.js-nav-tab[data-tabs-content-id="all"]');
    
    $filterCheckboxes.on('click', function(e) {
        e.preventDefault();
        
        // Collect all selected category slugs
        var selectedCategories = [];
        $filterCheckboxes.each(function() {
            if($(this).is(':checked')) {
                if($(this).data('tabs-content-id') === 'all') {
                    // If "All" is selected, skip all other categories
                    return true;
                } else {
                    selectedCategories.push($(this).data('category-slug'));
                }
            }
        });
        
        // Show or hide program items based on selected categories
        $filterContent.each(function() {
            var contentCategories = String($(this).data('tabs-content-id')).split(' ');

            if(selectedCategories.length === 0) {
                $(this).removeClass('is-active');
            } else {
                var showItem = false;
                for(var i=0; i<contentCategories.length; i++) {
                    if(selectedCategories.indexOf(contentCategories[i]) > -1) {
                        showItem = true;
                        break;
                    }
                }
                if(showItem) {
                    $(this).addClass('is-active');
                } else {
                    $(this).removeClass('is-active');
                }
            }
        });
    });
});
