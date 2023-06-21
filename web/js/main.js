
$('.btnSubscribe').on('click', function () {
    let $modal = $('#subscribeModal');
    $modal.find('#authorName').text($(this).data('author-name'));
    $('#btnSubscribe').attr('data-author-id', $(this).data('author-id'));
    $modal.modal('show');
});

$('#btnSubscribe').on('click', function() {
    $('#phoneNumber').removeClass('is-invalid');
    var phoneNumber = $('#phoneNumber').val();
    if (phoneNumber === '') { // TODO: test validity there as well
        $('#phoneNumber').addClass('is-invalid');
    } else {
        $.post("/site/subscribe", {
            phoneNumber: phoneNumber,
            authorId: $(this).data('author-id')
        })
        .done(function(data) {
            $('#subscribeModal').modal('hide');
            if (typeof data.code !== undefined && data.code === 200) {
                alert('Subscription sucessful');
            } else {
                alert('There was an issue with the subscription');
            }
        })
        .fail(function(data) {
            $('#subscribeModal').modal('hide');
            alert('There was an issue with the subscription');
        });
    }
});