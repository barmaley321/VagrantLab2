# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "barmaley321/centos7"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # NOTE: This will enable public access to the opened port
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine and only allow access
  # via 127.0.0.1 to disable public access
  #-------config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  #config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
  #end
  #
  # View the documentation for the provider you are using for more
  # information on available options.

  # Define a Vagrant Push strategy for pushing to Atlas. Other push strategies
  # such as FTP and Heroku are also available. See the documentation at
  # https://docs.vagrantup.com/v2/push/atlas.html for more information.
  # config.push.define "atlas" do |push|
  #   push.app = "YOUR_ATLAS_USERNAME/YOUR_APPLICATION_NAME"
  # end

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.

  config.vm.define "app" do |app|
      app.vm.synced_folder ".", "/custom_shared"
      app.vm.synced_folder ".", "/vagrant", disabled: true
      app.vm.hostname = "app"
      app.vm.provider "virtualbox" do |vb|
         vb.memory = "1024"
         vb.cpus = 1
         vb.customize ["modifyvm", :id, "--cpuexecutioncap", "50"]
      end
      app.vm.network "private_network", ip: "172.28.128.10"
      app.vm.provision "shell", inline: <<-SHELL
        sudo yum -y update
        sudo yum -y install httpd php php-mysql
        sudo systemctl enable httpd
        sudo sed -i 's/index.html/index.php/g' /etc/httpd/conf/httpd.conf
        sudo cp /custom_shared/index.php /var/www/html
        sudo systemctl start httpd
        sudo firewall-cmd --permanent --add-port=80/tcp
        sudo firewall-cmd --permanent --add-port=443/tcp
        sudo firewall-cmd --reload
        sudo setsebool -P httpd_can_network_connect_db=1
      SHELL
  end
  config.vm.define "db" do |db|
      db.vm.synced_folder ".", "/custom_shared"
      db.vm.synced_folder ".", "/vagrant", disabled: true
      db.vm.hostname = "db"
      db.vm.provider "virtualbox" do |vb|
         vb.memory = "1024"
         vb.cpus = 1
         vb.customize ["modifyvm", :id, "--cpuexecutioncap", "50"]
      end
      db.vm.network "private_network", ip: "172.28.128.100"
      db.vm.provision "shell", inline: <<-SHELL
         sudo yum -y update
         sudo yum -y install mariadb-server mariadb
         sudo systemctl enable mariadb
         sudo systemctl start mariadb
         mysql -u root  </custom_shared/createUser.sql
         sudo firewall-cmd --add-port=3306/tcp
         sudo firewall-cmd --permanent --add-port=3306/tcp
         sudo firewall-cmd --reload
      SHELL
  end
end
