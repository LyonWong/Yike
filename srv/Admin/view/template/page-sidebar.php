<?php namespace Admin;?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu" data-keep-expanded="true" data-auto-scroll="true" data-slide-speed="200">
            <?=wdgtMetronic::menu(servSession::$access->getScopeTree(function($item){
                return $item['type'] && servSession::$access->isAllowed($item['key'], servAccess::PRIV_VIEW);
            }), servSession::$scopeKey)?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.sub-more > a').click(function(){
            window.location.href = $(this)[0].href;
        });
        $('.page-sidebar-menu a').click(function(){
            if ($(this).attr('href')) {
                var map = {}, list = [], history = Cookie.read(Cookie.prefix+'history');
                
                $.each(history ? history.split('&') : [], function(i,v){
                    var item = v.split('=');
                    map[item[0]] = item[1];
                });
                var key = $(this).data('key');
                map[key] = (parseInt(map[key]) || 0) + 1;
                $.each(map, function(key, cnt){
                    list.push(key+'='+cnt);
                });
                Cookie.write(Cookie.prefix+'history', list.join('&'), 86400000*365);
            }
        });
    });
</script>