%W{php php-common php-cli php-bcmath php-cli php-common
   php-devel php-gd php-imap php-mbstring php-mcrypt
   php-mysql php-odbc php-pdo php-pear php-soap php-xml}.each do |pkg|
  package pkg do
    action [:install]
  end
end

directory "/vagrant/application" do
  owner "vagrant"
  group "vagrant"
  mode 0777
end

template "php.ini" do
  path "/etc/php.ini"
  source "php.ini.erb"
  owner "root"
  group "root"
  mode 0644
  notifies :reload, "service[httpd]"
end
