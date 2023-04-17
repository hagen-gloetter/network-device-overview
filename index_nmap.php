<?php
# do not forget to set the sticky bit 
# chmod +s /usr/sbin/lft
$starttime = time();
print("<table>");
print shell_exec('for i in `seq 1 254`;do (LANG=C lft 192.168.4.$i | grep trace | grep \'(\' &);done | sed \'s#.* to \(\(.*\).fritz.box\) (\(.*\)):.*#<tr><td></td>' .
	'<td><a href="http://\1" target="_blank">\2</a></td><td><a href="http://\3/" target="_blank">\3</a></td><td><a href="nmap-hostinfo.php?ip=\3" target="_blank">' .
	'<img src="css/searchicon.png" alt="click for hostinfo"></a></td></tr>#\' | sort -f');
$mytime = time() - $starttime;
print("<tr><td colspan=\"4\">done in $mytime seconds</td></tr>\n");
print("</table>");

# tests
#$cmd = 'nmap     -sP 192.168.4.0/24 | grep  "scan report" | cut -d " " -f5-6 | sort -df';
#$cmd = 'nmap -sL -sP 192.168.4.0/24 --host-timeout 200 | grep "(" | grep "192" | cut -d " " -f5-6 | sort -df --ignore-case';
#$output = shell_exec($cmd);
#$a_hosts = preg_split("/\n/", $output);
#$i = 1;
#$a_hosts = array_filter($a_hosts, 'strlen');
#foreach ($a_hosts as $zeile) {
#	$zeile = trim($zeile);
#	$res = preg_split("/[\s,]+/", $zeile);
#	$hostname = $res[0];
#	$ip = $res[1];
#	if (empty($ip)) {
#		$ip = $hostname;
#	}
#	$ip = preg_replace('/\(/', '', $ip); # klammer raus
#	$ip = preg_replace('/\)/', '', $ip); # klammer raus
#	$hst = preg_replace('/\.fritz\.box/', '', $hostname); # klammer raus
#	print("<tr><td>$i</td><td><a href='http://$hostname' target='_blank'>$hst</a></td><td><a href='http://$ip' target='_blank'>$ip</a></td><td>
#		<a href='nmap-hostinfo.php?ip=$ip' target='_blank'><img src='css/searchicon.png' alt='click for hostinfo'></a></td></tr>\n");
#	$i++;
#}
?>