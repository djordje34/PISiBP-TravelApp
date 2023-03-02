setInterval(function () {
    $.ajax({
        url: 'loading.php',
        type: 'post',
        data: {proveri: 1 },
        success: function (response) {
            if (response) {
                window.location.href = 'index.php'
            }
        }
    });
}, 10000);