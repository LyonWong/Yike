``` nginxconfig
upstream yike-cgi {
    # 建议个软币exec等危险函数
    server unix:/run/php/php7.4-fpm-cgi.sock;
}
upstream yike-cli {
    # 需开启exec函数,使PHP能调用系统命令
    server unix:/run/php/php7.4-fpm-cli.sock;
}
# 主服务
server {
    server_name yike.local www.yike.local;
    root /srv/Projects/Yike/srv/_/public;
    access_log /var/log/nginx/yike.log;
    error_log /var/log/nginx/yike.err;
    index index.php;
    location /_/ k
        try_files $uri $uri/ /_index_.php?$query_string;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ _index_.php {
        fastcgi_pass    yike-cli;
        fastcgi_index   index.php;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include         fastcgi_params;
    }
    location ~ index.php {
        fastcgi_pass    yike-cgi;
        fastcgi_index   index.php;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include         fastcgi_params;
    }
}
# 管理后台
server {
    server_name admin.yike.local;
    root /srv/Projects/Yike/srv/Admin/public;
    access_log /var/log/nginx/yike-admin.log;
    error_log /var/log/nginx/yike-admin.err;
    index index.php;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ index.php {
        fastcgi_pass    yike-cgi;
        fastcgi_index   index.php;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include         fastcgi_params;
    }
}
# 讲师后台
server {
    server_name teacher.yike.local;
    root /srv/Projects/Yike/srv/Teacher/public;
    access_log /var/log/nginx/yike-student.log;
    error_log /var/log/nginx/yike-student.err;
    index index.php;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ index.php {
        fastcgi_pass    yike-cgi;
        fastcgi_index   index.php;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include         fastcgi_params;
    }
}
# 旧学员端
server {
    server_name student.yike.local;
    root /srv/Projects/Yike/srv/Student/public;
    access_log /var/log/nginx/yike-studnet.log;
    error_log /var/log/nginx/yike-student.err;
    index index.php;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ index.php {
        fastcgi_pass    yike-cgi;
        fastcgi_index   index.php;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include         fastcgi_params;
    }
}
# 静态资源
server {
    server_name assets.yike.local;
    access_log /var/log/nginx/yike.log;
    error_log /var/log/nginx/yike.err;
    location /static/ {
        if ($http_origin ~ \.?yike\.local$){ # 允许*.yike.local跨域访问资源
            add_header Access-Control-Allow-Origin $http_origin;
            add_header Access-Control-Request-Method GET;
        }
        root /srv/Projects/Yike/wpa/dist;
    }
    location / {
    if ($http_origin ~ [a-z]+\.yike\.local$){ # 同上
        add_header Access-Control-Allow-Origin $http_origin;
        add_header Access-Control-Request-Method GET;
    }
        root /srv/Projects/Yike/srv/_/public/assets;
    }
}
```