require(
    [
        'jquery',
        'Magento_Ui/js/modal/confirm'
    ], function ($, confirmation) {
    $("body").on('click', '#easy-cache-clean', function (event) {
        event.preventDefault();
        var element = $(this);
        confirmation({
            title: $.mage.__('Are you sure?'),
            content: $.mage.__('After refreshing the cache, the next page load will be slow.'),
            actions: {
                confirm: function(){
                    $('body').trigger('processStart');
                    $.ajax({
                        url: element.attr("href"),
                        type: 'GET',
                    }).done(function( response ) {
                        $('body').trigger('processStop');
                        var responseText = JSON.parse(JSON.stringify(response));
                        var parentWrapper = element.parent();
                        parentWrapper.removeClass('message-warning').html(responseText.message);
                        if (responseText.error == true) {
                            parentWrapper.addClass('message-error');
                            return false;
                        } else {
                            parentWrapper.addClass('message-success');
                        }
                    });
                },
                cancel: function(){return false},
                always: function(){}
            }
        });
    });
});
