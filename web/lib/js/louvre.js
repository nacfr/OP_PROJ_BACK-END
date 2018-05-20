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



    // add-collection-widget.js
    jQuery(document).ready(function () {
        jQuery('.add-another-collection-widget').click(function (e) {
            e.preventDefault();
            var list = jQuery(jQuery(this).attr('data-list'));
            // Essayez de trouver le compteur de la liste.
            var counter = list.data('widget-counter') | list.children().length;
            // Si le compteur n'existe pas, utiliser la longueur de la liste.
            if (!counter) { counter = list.children().length; }

            // Récupère le gabarit du prototype
            var newWidget = list.attr('data-prototype');
            // remplacer le "__name__" utilisé dans l'identifiant et le nom du prototype
            // avec un numéro unique au bloc billet.
            // l'attribut de fin de nom ressemble à name="contact[tickets][2]
            newWidget = newWidget.replace(/__name__/g, counter);
            // Augmenter le compteur
            counter++;
            // Et stockez-le, la longueur ne peut pas être utilisée si la suppression de widgets est autorisée.
            list.data(' widget-counter', counter);

            // créer un nouvel élément de liste et l'ajouter à la liste
            var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
            newElem.appendTo(list);
        });
    });
});

