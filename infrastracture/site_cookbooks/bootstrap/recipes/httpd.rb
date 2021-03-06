%w{httpd httpd-devel}.each do |pkg|
  package pkg do
    action [:install]
  end
end

directory "/vagrant/application" do
  owner "vagrant"
  group "vagrant"
  mode 0777
end

directory "/vagrant/application/codeigniter" do
  owner "vagrant"
  group "vagrant"
  mode 0777
end

cookbook_file "/vagrant/application/index.php" do
  mode 0777
end

template "httpd.conf" do
  path "/etc/httpd/conf/httpd.conf"
  source "httpd.conf.erb"
  owner "root"
  group "root"
  mode 0644
  notifies :restart, "service[httpd]"
end

service "httpd" do
  supports [:restart, :status]
  action [:enable, :start]
end
