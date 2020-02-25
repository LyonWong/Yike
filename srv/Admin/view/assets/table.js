/**
 * Author: LyonWong
 * Date: 2015-12-16
 */


var Table = function(){
    var joTable;
    var trEditing;
    var lists = {};
    var filter = function(location, config) {
        console.log('config', config);
        var table = $(location).DataTable(config||{});
        var joFilter = $('#filter');
        joFilter.keyup(function () {
            document.location.hash = 'filter='+$(this).val();
            table.search($(this).val()).draw();
        });
        var match;
        if ( match = document.location.hash.match(/filter=([^&=]+)/)) {
            joFilter.val(match[1]);
            table.search(match[1]).draw();
        }
    };
    var editable = function(location, postURL) {
        joTable = $(location);
        joTable.find('thead > tr > th').each(function(n){
            lists[n] = $(this).data('list');
        });
        joTable.on('click','.edit', function(e) {
            e.preventDefault();
            trEditing = $(this).parents('tr');
            $(this).hide();
            $(this).parent().find('.save').show();
            var name, value, html;
            trEditing.find('> td').each(function(n) {
                console.log($(this)[0]);
                name = $(this).data('name');
                value = $(this).data('value');
                switch ($(this).data('edit')) {
                    case 'text':
                        $(this).html('<input value="'+value+'">');
                        break;
                    case 'list':
                        html = '<div class="btn-group" data-toggle="buttons">';
                        for (var i in lists[n]) {
                            html += '<label class="btn '+ lists[n][i]['color'] + (i==value ? ' active' :'') +'"><input type="radio" class="toggle" value="'+i+'">'+lists[n][i]['text']+'</label>';
                        }
                        html += '</div>';
                        $(this).html(html);
                        break;
                }
            })
        });
        joTable.on('click', '.save', function(e) {
            e.preventDefault();
            var id,name,value,data={};
            trEditing = $(this).parents('tr');
            data['id'] = trEditing.data('id');
            trEditing.find('> td').each(function(n) {
                name = $(this).data('name');
                if (!name) {
                    return;
                }
                switch ($(this).data('edit')) {
                    case 'text':
                        value = $(this).find('input').val();
                        $(this).html(value);
                        break;
                    case 'list':
                        value = $(this).find('.active > input').val();
                        console.log(value);
                        $(this).html('<span class="tag bg-'+lists[n][value]['color']+'">'+lists[n][value]['text']+'</span>');
                        break;
                }
                data[name] = value;
                $(this).data('value', value);
            });
            $(this).hide();
            $(this).parent().find('.edit').show();
            $.post(postURL, data).success(function(res){});
        });

    };



    return {
        edit: function(tableId, postURL) {
            editable(tableId, postURL);
        },
        filter: function(tableId, config) {
            filter(tableId, config);
        }
    }
}();
