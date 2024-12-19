(function($) {
    $(document).ready(function() {
        var uniform_selectors = ':checkbox:not("#createaccount"),' +
            ':radio:not(".input-radio")';

        $(uniform_selectors).not('#make_featured').uniform({});
    });
})(jQuery)