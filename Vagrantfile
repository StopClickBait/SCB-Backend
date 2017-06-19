# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

	config.vm.box = "debian/jessie64"

	#config.vm.network "forwarded_port", guest: 80, host: 8080
	config.vm.network "private_network", ip: "192.168.123.10"

	# added vagrant files
	config.vm.synced_folder "./vagrant", "/vagrant", type: "virtualbox"
	
	# add code to web directory
	config.vm.synced_folder "./backend", "/var/www/scb", type: "virtualbox"
	
	# add puppet environment
	config.vm.synced_folder "./vagrant/puppet/environments/dev", "/etc/puppetlabs/code/environments/dev", type: "virtualbox"

	# we need to make sure puppet is installed
	config.vm.provision :shell, :path => "./vagrant/bin/install_puppet_jessie.sh"
	
	# then we need to install some extra puppet modules
	config.vm.provision :shell, :inline => "puppet module install --environment=dev --version 2.4.0 puppetlabs/apt"
	# the apt and postgresql modules have conflicting version requirements for the 
	config.vm.provision :shell, :inline => "puppet module install --environment=dev puppetlabs/postgresql"
	
	# use "puppet apply" to handle more complicated provisioning
	config.vm.provision "puppet" do |puppet|
		puppet.binary_path = "/opt/puppetlabs/bin"
		puppet.environment_path = "./vagrant/puppet/environments"
		puppet.environment = "dev"
	end
	
end
