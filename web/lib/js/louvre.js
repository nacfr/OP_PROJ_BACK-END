$(document).ready(function () {
    /* -----------------------------------------
    Datepickers
    ----------------------------------------- */

    /*  $(".datepicker[name='booking[bookingdate]']").datepicker({
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
              $(".datepicker[name='booking[bookingdate]']").datepicker('option', 'minDate', minDate);
          }
      });*/


    /* -----------------------------------------
    Add / delete formulaire
    ----------------------------------------- */

    $collectionHolder = $('div.tags');
    $collectionHolder.data('index', $collectionHolder.find(':input').length + 1);
    var indexform = $collectionHolder.find(':input').length;

    if (indexform === 0) {
        comboAdd();
    }

    $('.add-ticketnumber-form-widget').change(function () {
        var nbBlocTicket = $collectionHolder.find('div.form-posts').length;
        for (var d = 1; d <= nbBlocTicket; d++) {
            $collectionHolder.data('index', ($collectionHolder.data('index') - 1));
        }
        $collectionHolder.html("");
        comboAdd();
    });

    function comboAdd() {
        var valTicket = document.getElementById('booking_ticketnumber').value;
        for (var iadd = 1; iadd <= valTicket; iadd++) {
            addTagForm($collectionHolder);
        }
    }

    function addTagForm($collectionHolder) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<div class="form-posts"></div>').append(newForm);
        $collectionHolder.append($newFormLi);
    }

    /* -----------------------------------------
    Disable ticket type if time of day and above 14h
    ----------------------------------------- */

    var $bookingDate = $('#booking_bookingdate');
    var $radioDay = $('#'+$('.booking_tickettype').find('input[type=radio]')[0].id);
    var $date= new Date();
    var $dateOfDay = ('0' + $date.getDate()).slice(-2) + '-' + ('0' + ($date.getMonth()+1)).slice(-2) + '-' + $date.getFullYear();
    var $timeOfDay = $date.getHours();

    $bookingDate.change(function () {
        var $selectBookingDate = $bookingDate.val();

        if (($selectBookingDate == $dateOfDay) && ($timeOfDay >= 14)){
            console.log($radioDay);
            $radioDay.attr('disabled', true).prop('checked', false);
        }else{
            $radioDay.attr('disabled', false);
        }
            });






});


