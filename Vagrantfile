# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "centos65-x86_64"
  config.vm.box_url = "https://github.com/2creatives/vagrant-centos/releases/download/v6.5.1/centos65-x86_64-20131205.box"

  config.vm.provider :virtualbox do |vb|

    vb.network :forwarded_port, guest: 3306, host: 33066
    vb.network :forwarded_port, guest: 80, host: 8899
    vb.network :private_network, ip: "192.168.33.10"

    vb.synced_folder ".", "/vagrant", mount_options: ['dmode=777','fmode=666']

    vb.memory = 512

  end

  config.omnibus.chef_version = :latest

  config.vm.provision :chef_solo do |chef|

    chef.cookbooks_path = ["cookbooks","site_cookbooks"]
    chef.data_bags_path = "data_bags"

    # List of recipes to run
    chef.add_recipe "git"
    chef.add_recipe "bootstrap::default"
    chef.add_recipe "bootstrap::httpd"
    chef.add_recipe "php::default"
    chef.add_recipe "php::default"

    # Custom json data
    chef.json = {
      "apache" => {
        "lesten_ports" => %w[80 443]
      }
    }

  end

end