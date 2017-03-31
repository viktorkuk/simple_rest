Vagrant.configure(2) do |config|
    config.env.enable

    config.vm.box = "ubuntu/trusty64"
    config.vm.box_check_update = true

    config.vm.provider "virtualbox" do |vb, override|
        vb.gui = false
        vb.memory = "2048"
        vb.cpus = 2

        override.vm.network "forwarded_port", guest: 80, host: 8085, host_ip: "127.0.0.1"
        override.vm.network "forwarded_port", guest: 81, host: 8086, host_ip: "127.0.0.1"
        override.vm.network "private_network", ip: "192.168.33.10"
    end

    config.vm.provision :shell, path: "provision/bootstrap.sh", privileged: false
end
