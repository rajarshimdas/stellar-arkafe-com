IPTABLES

To start iptables at boot time:
(Details: http://wiki.debian.org/iptables)


1. Create a new file 'iptables' in '/etc/network/if-pre-up.d/' folder

2. Add the location of the firewall rules to this file (/etc/network/if-pre-up.d/iptables)

#!/bin/bash
/sbin/iptables-restore < /etc/iptables.up.rules

3. Setup execution flag on this file:

chmod +x /etc/network/if-pre-up.d/iptables

Done rock-on.
