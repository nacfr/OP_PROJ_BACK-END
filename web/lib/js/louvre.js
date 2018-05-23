$(document).ready(function () {
    /* -----------------------------------------
    Datepickers
    ----------------------------------------- */


    $(".datepicker[id='booking_bookingdate']").datepicker({
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        firstDay: 1,
        dateFormat: 'dd/mm/yy',
        minDate: new Date(),
        beforeShowDay: function (date) {
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [dates.indexOf(string) == -1]
        },
        onSelect: function (dateText, dateObj) {
            var minDate = new Date(dateObj.selectedYear, dateObj.selectedMonth, dateObj.selectedDay);
            minDate.setDate(minDate.getDate() + 1);
            $(".datepicker[name='depart']").datepicker('option', 'minDate', minDate);
        }
    });

    /* -----------------------------------------
    Add / delete formulaire
    ----------------------------------------- */
    // setup an "add a tag" link
    var $addTagLink = $('<a href="#" class="add_tag_link">Ajouter un billet</a>');
    var $newLinkLi = $('<div></div>').append($addTagLink);


    // Get the ul that holds the collection of tags
    var $collectionHolder = $('div.tags');
    var indexform = $collectionHolder.find(':input').length;

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    if (indexform === 0) {
        addTagForm($collectionHolder, $newLinkLi);

        //console.log(document.getElementById('booking_ticketnumber').value);
    }

    $('.add-ticketnumber-form-widget').change(function () {

        removeTagForm()

        var numberTicket = document.getElementById('booking_ticketnumber').value;

        for (var i = 1; i <= numberTicket; i++) {

            //console.log(i);

            addTagForm($collectionHolder, $newLinkLi);
        }
    });

    $addTagLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addTagForm($collectionHolder, $newLinkLi);
    });


    function addTagForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<div class="form-posts" id="tata"></div>').append(newForm);

        // also add a remove button, just for this example
        // $newFormLi.append('<a href="#" class="remove-tag">x</a>');

        $newLinkLi.before($newFormLi);

        // handle the removal, just for this example
        /*$('.remove-tag').click(function (e) {
            e.preventDefault();

            $(this).parent().remove();

            return false;
        });*/
    }


    function removeTagForm() {

        var parentNode = document.getElementById("toto");
        var childs = document.getElementById("tata");

        var $collectionHolder = $('div.tags');
        var indexform = $collectionHolder.find(':input').length;

        console.log(indexform);

        /*
        for (var i = 1; i <= indexform; i++) {

            //console.log(i);

            indexform.removeChild();
        }
        */

    }


});

