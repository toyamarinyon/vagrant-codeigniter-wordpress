package "httpd" do
  action [:install]
end

directory "/vagrant/application" do
  owner "vagrant"
  group "vagrant"
  mode 0777
end

template "httpd.conf" do
  path "/etc/httpd/conf/httpd.conf"
  source "httpd.conf.erb"
  owner "root"
  group "root"
  mode 0644
  notifies :reload, "service[httpd]"
end

service "httpd" do
  supports [:restart, :reload, :status]
  action [:enable, :start]
end
