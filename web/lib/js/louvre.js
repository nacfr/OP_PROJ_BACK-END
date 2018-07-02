$(document).ready(function () {

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
        $newFormLi.find('.check-input-reduceprice').bind('change', function () {
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

    var $tickets = $('.add-ticketnumber-form-widget');
    $tickets.change(function () {
        exec();
    });

});


function exec() {
    var $bookingCurrentOrder = $('#booking-current-order').data('create-url');
    var nbTicket = $('div.tags').find('div.form-posts').length;

    var p = {};
    p.tab = [];
    for (var i = 0; i <= nbTicket - 1; i++) {
        p.tab[i] = [];
        var dateOfBirth = $('#booking_tickets_' + [i + 1] + '_dateofbirth');
        var checkReduce = $('#booking_tickets_' + [i + 1] + '_reduceprice');

        console.log(dateOfBirth.val());
        console.log(checkReduce.is(':checked'));

        //p.tab[i][0] = dateOfBirth.val();
        //p.tab[i][1] = checkReduce.is(':checked');
        if (dateOfBirth.val() !== "") {
            p.tab[i][0] = dateOfBirth.val();
        } else {
            p.tab[i][0] = "";
        }

        if (checkReduce.is(':checked')) {
            p.tab[i][1] = 1;
        } else {
            p.tab[i][1] = 0;
        }
    }
    //console.log(p);

    $.ajax({
            type: "POST",
            url: $bookingCurrentOrder,
            data: p,
            dataType: 'json',
            success: function (data) {
                var a, details = data.details;
                var b = data.total;

                for (var id in details) {
                    a = details[id];
                    //console.dir(a);
                    $('#computer-tab-order-qt-' + id).html(a.quantity);
                    $('#computer-tab-order-price-' + id).html(a.price + " €");
                    $('#smartphone-tab-order-qt-' + id).html(a.quantity);
                    $('#smartphone-tab-order-price-' + id).html(a.price + " €");
                    $('#computer-tab-order-total').html(b + " €");
                    $('#smartphone-tab-order-total').html(b + " €");
                }


            }
        }
    )
}