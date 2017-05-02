Before running `vagrant up`, you should install the vbguest plugin if you haven't.
Just run the following command:

`vagrant plugin install vagrant-vbguest`

The first time you `vagrant up`, it will probably take around 3 minutes to fully
provision the VM.  Don't worry, it won't do this every time.

When it finishes setting everything up, navigate to http://192.168.123.10 in your
browser to view the application.