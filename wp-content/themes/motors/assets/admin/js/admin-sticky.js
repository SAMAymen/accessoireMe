jQuery( function( $ ) {

    // Editing an individual custom post
    if ( sscpt.screen == 'post' ) {

        // Change visibility label if appropriate
        if ( parseInt( sscpt.is_sticky ) )
            $( '#post-visibility-display' ).text( sscpt.sticky_visibility_text );

        // Add checkbox to visibility form
        $( '#post-visibility-select label[for="visibility-radio-public"]' ).next( 'br' ).after(
            '<span id="sticky-span">' +
                '<input id="sticky" name="sticky" type="checkbox" value="sticky"' + sscpt.checked_attribute + ' /> ' +
                '<label for="sticky" class="selectit">' + sscpt.label_text + '</label>' +
                '<br />' +
            '</span>'
        );


    // Browsing custom posts
    } else {

        // Add "Sticky" filter above post table if appropriate
        if ( parseInt( sscpt.sticky_count ) > 0 ) {
            var publish_li = $( '.subsubsub > .publish' );

            publish_li.append( ' |' );
            publish_li.after(
                '<li class="sticky">' +
                    '<a href="edit.php?post_type=' + sscpt.post_type + '&show_sticky=1">' +
                    sscpt.sticky_text +
                    ' <span class="count">(' + sscpt.sticky_count + ')</span>' +
                    '</a>' +
                '</li>'
            );
        }

        // Add checkbox to quickedit forms
        $( 'span.title:contains("' + sscpt.status_label_text + '")' ).parent().after(
            '<label class="alignleft">' +
                '<input type="checkbox" name="sticky" value="sticky" /> ' +
                '<span class="checkbox-title">' + sscpt.label_text + '</span>' +
            '</label>'
        );

    }

} );
