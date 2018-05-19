$(document).ready(function() {
	/* -----------------------------------------
	Datepickers
	----------------------------------------- */


	$( ".datepicker[id='booking_date']" ).datepicker({
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
        firstDay: 1 ,
		dateFormat: 'dd/mm/yy',
		minDate: new Date(),
        beforeShowDay: function(date){
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ dates.indexOf(string) == -1 ]
        },
		onSelect: function(dateText, dateObj){
            var minDate = new Date( dateObj.selectedYear, dateObj.selectedMonth, dateObj.selectedDay );
            minDate.setDate(minDate.getDate() + 1);
            $( ".datepicker[name='depart']" ).datepicker('option', 'minDate', minDate );
        }
	});
});