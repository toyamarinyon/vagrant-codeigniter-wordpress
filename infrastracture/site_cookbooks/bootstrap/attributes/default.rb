default["httpd"]["rewrite_log_revel"] = 0
default["httpd"]["codeigniter"]["ignore_rewrite_conds"] = %W{wp/*
                                                             _devlove/*
                                                             .*\.html
                                                             .*\.css
                                                             .*\.js
                                                             .*\.png
                                                             .*\.gif
                                                             .*\.jpg
                                                             codeigniter/public/index\.php}

default["php"]["install_options"] = ""

default["mysql"]["host"] = "localhost"
default["mysql"]["user"] = "root"
default["mysql"]["server_root_password"] = "kado"
default["mysql"]["server_repl_password"] = "kado"
default["mysql"]["server_debian_password"] = "kado"

default["wordpress"]["databasename"] = "wordpress"
