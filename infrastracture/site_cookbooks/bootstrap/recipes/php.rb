%W{php54 php54-common php54-cli php54-bcmath php54-cli php54-common
   php54-devel php54-gd php54-imap php54-mbstring php54-mcrypt
   php54-mysql php54-odbc php54-pdo php54-pear php54-soap php54-xml}.each do |pkg|
  package pkg do
    action [:install]
    options node['php']['install_options']
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
  notifies :restart, "service[httpd]"
end
