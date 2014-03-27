%w{ vim }.each do |pkg|
  package pkg do
    action :install
  end
end

# file '/etc/yum.conf' do
#   _file = Chef::Util::FileEdit.new(path)
#   _file.search_file_replace_line('exclude=kernel', "#exclude=kernel\n")
#   content _file.send(:contents).join
#   action :create
# end.run_action(:create)

# yum_repository 'remi' do
#   description 'Les RPM de Remi - Repository'
#   baseurl 'http://rpms.famillecollet.com/enterprise/6/remi/x86_64/'
#   gpgkey 'http://rpms.famillecollet.com/RPM-GPG-KEY-remi'
#   fastestmirror_enabled true
#   action :create
# end
