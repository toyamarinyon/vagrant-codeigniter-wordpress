# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "centos65-x86_64"
  config.vm.box_url = "https://github.com/2creatives/vagrant-centos/releases/download/v6.5.1/centos65-x86_64-20131205.box"

  #
  # If do not connect to vm, try below vm.box
  #
  # config.vm.box = "centos62-32"
  # config.vm.box_url = "https://dl.dropbox.com/sh/9rldlpj3cmdtntc/chqwU6EYaZ/centos-63-32bit-puppet.box"

  config.vm.provider :virtualbox do |vb|

    config.vm.network :forwarded_port, guest: 3306, host: 33066
    config.vm.network :forwarded_port, guest: 80, host: 8899
    config.vm.network :private_network, ip: "192.168.33.10"

    config.vm.synced_folder ".", "/vagrant", mount_options: ['dmode=777','fmode=666']

    vb.memory = 512

  end

  config.omnibus.chef_version = :latest

  config.vm.provision :chef_solo do |chef|

    chef.cookbooks_path = ["cookbooks","site_cookbooks"]
    chef.data_bags_path = "data_bags"

    # List of recipes to run
    chef.add_recipe "git"
    chef.add_recipe "bootstrap::default"
    chef.add_recipe "bootstrap::php"
    chef.add_recipe "bootstrap::httpd"
    chef.add_recipe "mysql::server"
    chef.add_recipe "bootstrap::wordpress"

    # Custom json data
    chef.json = {
      "mysql" => {
        "server_root_password" => "wagahaihanekodearu",
        "server_repl_password" => "wagahaihanekodearu",
        "server_debian_password" => "wagahaihanekodearu"
      },
      "wordpress" => {
        "plugins" => ["custom-field-template"]
      }
    }

  end

   # config.vm.provision :serverspec do |spec|
   #   spec.pattern = "spec/default/*_spec.rb"
   # end

end
