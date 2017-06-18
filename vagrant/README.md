This application is set up to use [Vagrant](https://www.vagrantup.com/) for 
development.  You will need to have both Vagrant and Virtualbox installed on
your machine before beginning, but you don't need to install any other dependencies
(PHP, Laravel, PostgreSQL, etc).

Once you have installed Vagrant, open a command line window and navigate to the
project root.  If you do not already have the VirtualBox Guest Additions plugin
installed, run this before anything else:

`vagrant plugin install vagrant-vbguest`

When you are ready to start developing, type this into the console:

`vagrant up`

The first time you `vagrant up`, it will probably take around 3 minutes to fully
provision the VM.  It's downloading the VM, configuring it, installing dependencies,
building the database tables and starting up the server, so just be patient.  
Don't worry, it won't do this every time.

When it finishes setting everything up, navigate to http://192.168.123.10 in your
browser to view the application.  I recommend editing your hosts file to point "scb"
to this IP address, allowing you to access the application in your browser just by
going to http://scb.  This is the internal URL defined in Laravel, so it will make
any navigation much easier.