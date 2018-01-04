function submit(url) {
    $.ajax({
        url: url,
        type: 'post',
        data: $('form').serializeArray(),
        dataType: 'json',
        success: function (response) {
            layer.msg(response.message, {
                icon: response.code,
                time: 600
            }, function () {
                if (response.url) {
                    window.location.href = response.url;
                }
            });
        }
    });
}
function del(id, url) {
    layer.confirm('确定删除本条数据？', {title: '提示'}, function (index) {
        $.ajax({
            url: url,
            type: 'post',
            data: {id: id},
            dataType: 'json',
            success: function (response) {
                layer.msg(response.message, {
                    icon: response.code,
                    time: 600
                }, function () {
                    if (response.url) {
                        window.location.href = response.url;
                    }
                });
            }
        });
        layer.close(index);
    });
}