const $ = require('jquery');
import './resources/blk/blk.css';
require('./resources/blk/blk.min');

$(window).on("load", function () {

    $('.add-another-collection-widget').click(function (e) {
        var list = $($(this).attr('data-list-selector'));
        console.log(list);
        // Try to find the counter of the list or use the length of the list
        var counter = list.data('widget-counter') | list.children().length;
        console.log(counter);
        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        console.log(newWidget);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
})
