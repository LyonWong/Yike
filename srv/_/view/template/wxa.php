<html>
<body>
<?= \view::js('js/jweixin-1.3.0') ?>
<script>
    window.onload = function () {
        var handle = setInterval(function () {
            wx.miniProgram.<?=$this->method?>({
                url: '<?=$this->wxaUrl?>', complete: function () {
                    clearInterval(handle);
                }
            })
        }, 100)
    }
</script>
</body>
</html>