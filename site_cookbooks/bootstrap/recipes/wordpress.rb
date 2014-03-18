remote_file "/usr/local/bin/wp" do
  source 'https://raw.github.com/wp-cli/builds/gh-pages/phar/wp-cli.phar'
  mode 0755
  action :create_if_missing
end

bash 'install wordpress' do
  code <<-EOF
  wp core download --locale=ja --path=/vagrant/application/wordpress
  cd /vagrant/application/wordpress &&
  wp core config --dbname=wordpress --dbuser=root --dbpass=vagrant &&
  wp db create &&
  wp core install --url=192.168.33.10/wordpress --title="AwesomePress" --admin_name=admin --admin_email=toyamarinyon@gmail.com --admin_password=admin
  EOF
end
