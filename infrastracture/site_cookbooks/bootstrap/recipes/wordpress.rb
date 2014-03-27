remote_file "/usr/local/bin/wp" do
  source "https://raw.github.com/wp-cli/builds/gh-pages/phar/wp-cli.phar"
  mode 0755
  action :create_if_missing
end

bash "install wordpress" do
  code <<-EOF
  wp core download --locale=ja --path=/vagrant/application/wp
  cd /vagrant/application/wp
  wp core config \
    --dbname=#{node["wordpress"]["databasename"]}\
    --dbuser=#{node["mysql"]["user"]}\
    --dbpass=#{node["mysql"]["server_root_password"]}\
    --dbhost=#{node["mysql"]["host"]}
  wp db create
  wp core install --url=192.168.33.10/wp --title="AwesomePress" --admin_name=admin --admin_email=toyamarinyon@gmail.com --admin_password=admin
  EOF
  not_if { ::File.directory?("/vagrant/application/wp") }
end

node["wordpress"]["plugins"].each do |plugin|
  bash "install wordpress plugin" do
    cwd "/vagrant/application/wp"
    code <<-EOF
    wp plugin install #{plugin} --activate
    EOF
  end
end
