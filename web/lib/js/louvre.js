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
    Disable ticket type if time of day is above 14h
    ----------------------------------------- */

    var $bookingDate = $('#booking_bookingdate');
    var $radioDay = $('#' + $('.booking_tickettype').find('input[type=radio]')[0].id);
    var $date = new Date();
    var $dateOfDay = ('0' + $date.getDate()).slice(-2) + '-' + ('0' + ($date.getMonth() + 1)).slice(-2) + '-' + $date.getFullYear();
    var $timeOfDay = $date.getHours();

    $bookingDate.change(function () {
        var $selectBookingDate = $bookingDate.val();

        if (($selectBookingDate == $dateOfDay) && ($timeOfDay >= 15)) {
            $radioDay.attr('disabled', true).prop('checked', false);
        } else {
            $radioDay.attr('disabled', false);
        }
    });


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

        if (valTicket >= 11) {
            console.log('futur message : veuillez contacter le musée');
            addMessage($collectionHolder);
        } else {
            for (var iadd = 1; iadd <= valTicket; iadd++) {
                addTagForm($collectionHolder);
            }
        }
    }

    function addTagForm($collectionHolder) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<div class="form-posts"></div>').append(newForm);
        $collectionHolder.append($newFormLi);

        //Ecoute tous les champs date de naissance et au changement execute la function exec
        $newFormLi.find('.datepicker-dateofbirth').bind('blur', function () {
            exec();
        });
    }

    function addMessage($collectionHolder) {
        var $content = '<h2 class="card-title form-posts-title text-center">Pour les réservations supérieures à 10 visiteurs :</h2>' +
            '<div class="form-row">Merci de contacter directement le Musée au 00 00 00 00 00</div>';
        var $newAlerte = $('<div class="form-posts"></div>').append($content);
        $collectionHolder.append($newAlerte);
    }

    /* -----------------------------------------
    MAJ ORDER
    ----------------------------------------- */

    //var $dayOfBirth = $('.datepicker-dateofbirth');
    var $tickets = $('.add-ticketnumber-form-widget');

    /*$dayOfBirth.blur(function () {
        console.log($dayOfBirth.val());
    });*/

    $tickets.change(function () {
        exec();

    });

});


function exec() {
    var $bookingCurrentOrder = $('#booking-current-order').data('create-url');

    var $dateOfBirth = $('.datepicker-dateofbirth');
    
    var p = {};
    p.tabDate = [];
    p.tabReduce = [];
    $dateOfBirth.each(function () {
        if (this.value !== "") {
            p.tabDate.push(this.value);
        }
        else {
            p.tabDate.push([]);
        }

    });

    $.ajax({
            type: "POST",
            url: $bookingCurrentOrder,
            data: p,
            dataType: 'json',
            success: function (data) {
                var a, details = data.details;

                for (var id in details) {
                    a = details[id];
                    /*console.dir(a);*/

                    $('#tab-order-qt-' + id).html(a.quantity);
                    $('#tab-order-price-' + id).html(a.price);
                }
            }
        }
    )
}

/*function exec() {
    var $bookingCurrentOrder = $('#booking-current-order').data('create-url');

    var $titi = $('.datepicker-dateofbirth');
    var p = {};
    p.tab = [];

    $titi.each(function () {
        p.tab.push(this.value);

    });

    $.ajax({
            type: "POST",
            url: $bookingCurrentOrder,
            data: p,
            success: function (data) {
                var doc = eval('(' + data + ')');
                if (doc) {
                    alert(doc.toto); //affiche 345
                    alert(doc.titi); //affiche khgv

                }
            }
        }
    )
}*/


