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
    $collectionHolder = $('div.tags');
    var indexform = $collectionHolder.find(':input').length;
    if (indexform === 0) {
        addTagForm($collectionHolder);
    }

    $('.add-ticketnumber-form-widget').change(function () {
        $collectionHolder.html("");
        var numberTicket = document.getElementById('booking_ticketnumber').value;
        for (var i = 1; i <= numberTicket; i++) {
            addTagForm($collectionHolder);
        }
    });

    function addTagForm($collectionHolder) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<div class="form-posts"></div>').append(newForm);
        $collectionHolder.append($newFormLi);
    }
});

