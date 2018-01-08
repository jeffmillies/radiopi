var loadingHtml = '<div class="fa-3x text-center" style="padding-top: 50px;"><span class="fa fa-spin fa-circle-o-notch"></span></div>';

function formatStationList(records) {

    var html = '';
    for (var i = 0; i < records.length; i++) {
        var row = records[i];
        html += '<div style="background: white; width: 100%; margin-bottom: 15px; border: 1px solid rgba(223,215,202,0.75); border-radius: 0.25rem; padding: 10px;"><div class="float-left d-inline-block"><button class="save-station btn btn-outline-primary " data-station-id="' + row['ID'] + '" ' + (row['saved'] ? "disabled" : "") + '>' +
            (row['saved'] ? "Saved" : "Save") + '</button><div id="' + row['ID'] + '" class="hidden">' + JSON.stringify(row) + '</div></div>' +
            '<div class="d-inline-block" style="margin-left: 15px;"><b>' + row['Name'] + ':</b><br> ' + row['CurrentTrack'] + ' <br>' + row['Listeners'] + ' listening at ' + row['Bitrate'] + 'kb/s</div></div>';
    }
    return html;
}

$(document).ready(function () {
    $(document).on('click', '.ajax', function () {
        var _this = $(this);
        var request = {
            command: _this.attr('data-command')
        };
        var id = _this.attr('data-id');
        if (id) {
            request['id'] = id;
        }
        ajax.get(request).done(function (result) {
            if (result.success) {
                location.reload();
            }
        });
    });

    $(document).on('keyup', '#search-station', function () {
        var _this = $(this);
        typewatch(function () {
            $('#stations').html(loadingHtml);
            ajax.get({
                command: 'search',
                id: _this.val()
            }).done(function (result) {
                console.log('using html');
                $('#stations').html(result['html']);
            });
        }, 750);
    });

    $(document).on('click', '.save-station', function () {
        var _this = $(this);
        ajax.get({
            command: 'save',
            id: $('#' + _this.attr('data-station-id')).html()
        }).done(function () {
            _this.html('Saved').attr('disabled', true);
        });
    });

    $(document).on('click', '.listen-station', function () {
        var _this = $(this);
        $('.listen-station').each(function () {
            $(this).html('Listen').removeAttr('disabled');
        });
        ajax.get({
            command: 'listen',
            id: $('#' + _this.attr('data-station-id')).html()
        }).done(function (result) {
            $('#current-title').html(result['data']);
            $('#current-button').show();
            _this.html('Listening').attr('disabled', true);
        });
    });

    $(document).on('click', '#stop-station', function () {
        var _this = $(this);
        $('.listen-station').each(function () {
            $(this).html('Listen').removeAttr('disabled');
        });
        ajax.get({
            command: 'stop'
        }).done(function (result) {
            $('#current-title').html('RadioPi not playing');
            $('#current-button').hide();
        });
    });

    $(document).on('change', '.station', function () {
        var _this = $(this);
        $('#stations').html(loadingHtml);
        ajax.get({
            command: 'stations',
            id: _this.val()
        }).done(function (result) {
            console.log('using html');
            $('#stations').html(result['html']);
        });
    });

    $(document).on('change', '#parent', function () {
        var value = $(this).val();
        console.log(value);
        $('.child').each(function () {
            $(this).hide();
        });
        $('#' + value).show();
    });

});