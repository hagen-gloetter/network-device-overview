import threading
import ipaddress
import subprocess
import socket

class NetworkScanner:
    def __init__(self, subnet):
        self.subnet = subnet
        self.lock = threading.Lock()
        self.found_hosts = []

    def scan_host(self, ip):
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as sock:
            sock.settimeout(0.5)
            result = sock.connect_ex((str(ip), 80))
            return result == 0

    def scan_subnet_range(self, subnet_range, subnet):
        for i in subnet_range:
            ip_address = str(subnet.network_address + i)
            if self.scan_host(ip_address):
                hostname = ""
                try:
                    hostname = socket.gethostbyaddr(ip_address)[0]
                except socket.herror:
                    pass

                with self.lock:
                    mac_address = self.get_mac_address(ip_address)
                    self.found_hosts.append((hostname, ip_address, mac_address))

    def scan_subnet(self):
        subnet = ipaddress.ip_network(self.subnet)
        threads = []
        for i in range(0, 256, 16):
            subnet_range = range(i, i + 16)
            t = threading.Thread(target=self.scan_subnet_range, args=(subnet_range, subnet))
            threads.append(t)
            t.start()

        for t in threads:
            t.join()

    def get_mac_address(self, ip):
        with subprocess.Popen(["arp", "-a", ip], stdout=subprocess.PIPE) as proc:
            output = proc.communicate()[0].decode("utf-8")
            mac_address = ""
            for line in output.split("\n"):
                if ip in line:
                    mac_address = line.split()[1]
                    break
            return mac_address

    def scan_network(self):
        self.scan_subnet()
        self.found_hosts.sort(key=lambda x: x[1])
        print("{:<30} {:<20} {:<20}".format("Hostname", "IP-Adresse", "MAC-Adresse"))
        for host in self.found_hosts:
            print("{:<30} {:<20} {:<20}".format(host[0], host[1], host[2]))
            self.get_open_ports(host[1])

    def get_open_ports(self, ip):
        try:
            print(f"Offene Ports für {ip}")
            for port in range(1, 1025):
                with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
                    s.settimeout(0.5)
                    result = s.connect_ex((ip, port))
                    if result == 0:
                        print(f"Port {port} ist geöffnet")
        except Exception as e:
            print(f"Fehler beim Scannen von offenen Ports: {e}")

if __name__ == "__main__":
    subnet = "192.168.4.0/24"
    scanner = NetworkScanner(subnet)
    scanner.scan_network()
