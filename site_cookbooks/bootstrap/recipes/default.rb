# include_recipe "build-essential"
# include_recipe "git"
# include_recipe "apache2"
# include_recipe "apache2::mod_rewrite"
# include_recipe "apache2::mod_ssl"
# include_recipe "mysql::server"
# include_recipe "php"
# include_recipe "php::module_mysql"
# include_recipe "php::module_apc"
# include_recipe "php::module_curl"
# include_recipe "apache2::mod_php5"
# include_recipe "composer"
# include_recipe "phing"
# include_recipe "php-box"

# Install packages
%w{ vim }.each do |pkg|
  package pkg do
    action :install
  end
end
