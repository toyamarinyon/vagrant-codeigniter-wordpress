git "/vagrant/application/codeigniter" do
  repository 'https://github.com/toyamarinyon/ikeike55.git'
  revision 'master'
  action :sync
  mode 0777
  action :create_if_missing
end
