jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery("time.timeago").timeago();
    var acoptions = { 
        paramName: 'query',
        serviceUrl: '/api/v1/node/autocomplete.json',
        minChars:3,
        width:320,
        onSelect: function(suggestion) {
            console.log(suggestion['value']);
            location.assign('/nodes/' + suggestion['value']);
        }
    };
    $('#autocomplete').autocomplete(acoptions);
});