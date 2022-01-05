#http://www.tizag.com/perlT/perlhashes.php
#package Routines;
sub get3octets() {
        my @octetspot = split(/\./, $_[0]);
	return $octetspot[0].".".$octetspot[1].".".$octetspot[2];
}

sub checkTelnetScv {
        my $jos=qx(chkconfig | grep telnet);
        if ($jos =~ /on/) { return 1; }
}

sub checkStringInFile {
        my $jos=qx(cat $_[0] | grep $_[1]);
        if (length($jos) > 0) { return 1; }
}

sub getmplsbranches {
	my @mplsbranches  = qw/PWM TEP TOL CUL ZAP IZT COB MCH TIJ GDL NOG MTY MEX QUE CHI MER MAZ MXL MT1 TPZ MTY LGT PUE STA VIL JUA CCN/;
	return @mplsbranches;
}

sub getvlvbranches {
        my @vlvbranches  = qw/VCL/;
        return @vlvbranches;
}

sub expect_prompt(){
        my $ssh_exp = shift;
        my $timeout = shift;
        my $prompt = shift;
print "aki".$prompt;
        $ssh_exp->expect($timeout, '-re' , $prompt) or die "expect error" . $ssh_exp->exp_error() . "\n";
print "aki";
        my $out = $ssh_exp->before();
        return $out;
}

sub ssh_testuser (){
        use Expect;
        my $user = shift;
        my $password = shift;
        my $server = shift;
        my $timeout = shift;
        my $prompt = '\$\s*';
        my $root_prompt = '\#\s*';
        my $spawn_ok;
        my $ssh_exp = new Expect;
        $ssh_exp->log_stdout(0);
        $ssh_exp->spawn("ssh $user\@$server") or die "Cannot spawn ssh: $!\n";
        $ssh_exp->expect(5, [qr'\(yes/no\)\s*' , sub {my $exph = shift; print $exph "yes\r" ;exp_continue; }],
                [qr'word:\s*' , sub {my $exph = shift; print $exph "$password\r";exp_continue; }],
                [EOF => sub {die "Error: Could not login!\n"; }],
                [timeout => sub {die "Error: Could not login!\n"; }],
                '-re', '\$');
        $ssh_exp->send("whoami\n");
        $ssh_exp->expect(5,'operador');
        my $match=$ssh_exp->match();
        if ($match eq 'operador') {
                return 1;
        } else {
                return 0;
        }
        $ssh_exp->hard_close();
}

sub checkdeclaredhost {
        my $jos=qx(cat /etc/hosts | grep $_[0]);
        if (length($jos) > 0) { return 1; }
}

sub checklang {
        my $lang=qx(locale | grep LANG);
        if ($lang) { return 1; }
}

sub checkprocess2 {
	my $vpnproc=qx(ps -eaf |grep $_[0] |grep -v grep);
	if ($vpnproc) { return 1; }
}

sub getCelBajaRegValue {
        my $reg;
        use Switch;
        switch ($_[0]) {
        	case "OCCIDENTE" { $reg="BAJA_CEL_OCT"; }
        	case "NOROESTE" { $reg="BAJA_CEL_NST"; }
        	case "NORTE" { $reg="BAJA_CEL_NOR"; }
        	case "SURESTE" { $reg="BAJA_CEL_SUR"; }
        	case "CENTRO" { $reg="BAJA_CEL_CNT"; }
        	
        }
	return $reg;
}


sub getCelBajaRegClave {
    my $reg;
    use Switch;
    switch ($_[0]) {
    	case "BAJA_CEL_NOR" { $reg="49"; }
    	case "BAJA_CEL_NST" { $reg="50"; }    	    	
    	case "BAJA_CEL_OCT" { $reg="51"; }
    	case "BAJA_CEL_SUR" { $reg="52"; }    	    	
    	case "BAJA_CEL_CNT" { $reg="53"; }    	    	
    }
    return $reg;
}


sub chkserialmpls {
	#%ENV=(%ENV, TERM=>vt100, term=>vt100);
	use Expect;
	my $rootexpect;
	my @params;
	my $netid=getnetfromhost();
	my $wanmpls=($netid+100);
	my $command="telnet 192.168.$wanmpls.254";
	$rootexpect = Expect->spawn($command, @params) or die "Cannot spawn $command: $!\n";
	$rootexpect->expect(15, "name:");
	$rootexpect->send("1nghm0\n");
	$rootexpect->expect(15, "ord:");
	$rootexpect->send("R1hM0\n");
	$rootexpect->expect(15, "#");
	$rootexpect->clear_accum();
	$rootexpect->send_slow(0,"sh ip int b | incl Serial\n");
	$rootexpect->send_slow(0,"!\n");
	$rootexpect->expect(15,"!");
	$cursh = $rootexpect->before();
	my @run = split("\n", $cursh);
	my $runline;
	for $runline ( @run ) {
	        if ($runline =~ /Serial.+NVRAM\s+(\w+)\s+(\w+)/) {
	      		my $serialstat=$1;
	      		my $protocolstat=$2;
			if (($serialstat eq "up")&&($protocolstat eq "up")) { print "\nOK!!!! ENLACE OK\n"; }
			if (($serialstat eq "up")&&($protocolstat eq "down")) { print "\nERROR!!! Conexion NTU OK, Sesion a uninet abajo, reporte el ENLACE\n"; }
			if (($serialstat eq "down")&&($protocolstat eq "down")) { print "\nERROR!!!! Conexion NTU abajo, reporte el ENLACE\n"; }
	        }
	}
}



sub checkospfd {
%ENV=(%ENV, TERM=>vt100, term=>vt100);
use Expect;
my $rootexpect;
my @params;
my $command="telnet localhost ospfd";
$rootexpect = Expect->spawn($command, @params) or die "Cannot spawn $command: $!\n";
$rootexpect->expect(15, "assword:");
$rootexpect->send("sistemaspitic\n");
$rootexpect->expect(15, ">");
$rootexpect->clear_accum();
$rootexpect->send_slow(0,"sh ip ospf neighbor\n");
$rootexpect->send_slow(0,"!\n");
$rootexpect->expect(15,"!");
$cursh = $rootexpect->before();
my @run = split("\n", $cursh);
my $runline;
for $runline ( @run ) {
	if ($runline =~ /Full/) {
		print "\n AL PARECER SI ESTA ARRIBA LA SESION OSPF\n";		
	}
}
}


sub testvpn {
	my $vpntst;
	my $vpnproc=qx(ps -eaf |grep openvpn |grep -v grep);
	if ($vpnproc) {
		my $vpnif=qx(/sbin/ifconfig tun0 | grep "Link encap" | awk {'print $1'});
		if ($vpnif) {
			$vpntst="VPNIFOK"
		} else {
			$vpntst="NOVPNIF";
		}
	} else {
		$vpntst="NOVPNPROC";
	}
	return $vpntst;
}


sub testport {
	my $serv=IO::Socket::INET->new(PeerAddr=>$_[0],Proto=>"tcp",PeerPort=>$_[1],Timeout=>"5");
        if ($serv){
                return 1;
	}
	close $serv;
}


sub getdefroute {
	my $defrout=qx(/sbin/ip route | grep default | awk '{print $3}');
	return $defrout;
}

sub pinghost {
	use Net::Ping;
	$p = Net::Ping->new();
	return 1 if $p->ping($_[0]);
	$p->close();
}

sub checkinternet {
	my $res="no";
	if (getdefroute()) {
		if(testport("200.34.32.130","23")) {
			$res="INTERNETOK";	
		} else {
			$res="DEFGWEXISTSbtNONET";
		}
	} else {
		$res="NODEFROUTE";
	}
	 return $res;
}

sub getnamefrmabrev {
	use Switch;
	switch ($_[0]) {
        	case /DFA/ { $nombre="Administracion" }
	        case /SIS/ { $nombre="Sistemas" }
	        case /RH/ { $nombre="Recursos humanos" }
	        case /GPO/ { $nombre="Corporativo Hermosillo" }
	        case /DG/ { $nombre="Direccion General" }
	        case /DO/ { $nombre="Direccion de Operaciones" }
	        case /DC/ { $nombre="Direccion comercial" }
	        case /TRA/ { $nombre="Transporte" }
	        case /VHL/ { $nombre="Volvo Hermosillo" }
        	case /TDI/ { $nombre="Tecnologia Diesel" }
	        case /HLO/ { $nombre="Hermosillo" }
        	case /BTX/ { $nombre="Batuc express" }
		return $nombre;
	}
}

#/sbin/iptables -t nat -I PREROUTING -p udp -d 148.233.136.210 --dport 53 -j DNAT --to-destination 148.223.74.118:53
#iptables -n -t nat -L | grep 148.223.74.118
#DNAT       udp  --  0.0.0.0/0            148.233.136.210     udp dpt:53 to:148.223.74.118:53

sub verifywwwsvc {
	my $gueb=qx(lynx -connect_timeout=5 --source $_[0]/ip2.php);
        if ($gueb =~ /192\.168\./) { return 1; }
}

sub verifywwwsvcext {
        my $gueb=qx(lynx -connect_timeout=5 --source $_[0]/ip2.php);
	return $gueb;
        #if ($gueb =~ /192\.168\./) { return 1; }
}

sub checkroute {
        my @routes = qx(/sbin/route -n | grep $_[0]);
        if (($#routes +1) eq "1") { return 1; }
}

sub checknatrule {
	my @natrules = qx(/sbin/iptables -n -t nat -L | grep 148.223.74.118);
	if (($#natrules +1) eq "1") { return 1; }
}


sub resolvhost {
	use Net::DNS;
	my $res = new Net::DNS::Resolver;
	$res->nameservers($_[0]);
	my $query = $res->search($_[1]);
	#return $query;
	if ($query) { return 1; }
}

sub getnetfromhost {
	my $siglas;
	my @hostname = qx(hostname | awk -F. {'print  $1'});
	$hostname[0]=uc($hostname[0]);
	if ($hostname[0] =~ /TP(\w\w\w)/) {
		$siglas=uc($1);
		$netid=getnetfromsiglas($siglas);
		if ( $netid eq "ERR" ) { 
			print "ERROR red no registrada!\n";
		} else {
			print "Red registrada para $siglas ! ( $netid ) :-) \n";
		}
		
	}
	return $netid;
	
}

sub getRegFromSiglas {
        my $reg;
        use Switch;
        switch ($_[0]) {
                case "GDL" { $reg="OCCIDENTE"; }
                case "OBR" { $reg="NOROESTE"; }
                case "COB" { $reg="NOROESTE"; }
                case "TIJ" { $reg="NOROESTE"; }
                case "MCH" { $reg="OCCIDENTE"; }
                case "CUL" { $reg="OCCIDENTE"; }
                case "NOG" { $reg="NOROESTE"; }
                case "MTY" { $reg="NORTE"; }
				case "MT1" { $reg="NORTE"; }                
                case "TLA" { $reg="CENTRO"; }
                case "MAZ" { $reg="OCCIDENTE"; }
                case "MNZ" { $reg="OCCIDENTE"; }
                case "MXL" { $reg="NOROESTE"; }
                case "MER" { $reg="SURESTE"; }
                case "CCN" { $reg="SURESTE"; }
                case "NVL" { $reg="NORTE"; }
                case "IZT" { $reg="CENTRO"; }
                case "ZAP" { $reg="OCCIDENTE"; }
                case "CHI" { $reg="NORTE"; }
                case "QUE" { $reg="CENTRO"; }
                case "OMA" { $reg="CENTRO"; }
                case "LGT" { $reg="OCCIDENTE"; }
                case "MEX" { $reg="CENTRO"; }
                case "STA" { $reg="NOROESTE"; }
                case "HLO" { $reg="NOROESTE"; }
                case "TPZ" { $reg="CENTRO"; }
                case "JUA" { $reg="NORTE"; }
                case "TEP" { $reg="OCCIDENTE"; }
                case "TOL" { $reg="CENTRO"; }
                case "VIL" { $reg="SURESTE"; }
                case "PUE" { $reg="CENTRO"; }
                case "PWM" { $reg="CENTRO"; }                
                case "PDM" { $reg="CENTRO"; }
                else { $reg="ERR" }

        }
        return $reg;
}

sub getnetfromsiglas {
	my $netid;
	use Switch;
	switch ($_[0]) {
                case "GDL" { $netid="2"; }
                case "OBR" { $netid="4"; }
                case "TIJ" { $netid="5"; }
                case "MCH" { $netid="6"; }
                case "CUL" { $netid="7"; }
                case "NOG" { $netid="8"; }
                case "MTY" { $netid="9"; }
                case "TLA" { $netid="10"; }
                case "MAZ" { $netid="12"; }
                case "MXL" { $netid="13"; }
                case "MER" { $netid="14"; }
                case "CCN" { $netid="15"; }
                case "NVL" { $netid="17"; }
                case "IZT" { $netid="18"; }
                case "ZAP" { $netid="19"; }
                case "CHI" { $netid="21"; }
                case "QUE" { $netid="22"; }
                case "OMA" { $netid="24"; }
		case "LGT" { $netid="35"; }
		case "MEX" { $netid="10"; }
                case "STA" { $netid="20"; }
		case "HLO" { $netid="98"; }
		case "TPZ" { $netid="34"; }
		case "JUA" { $netid="36"; }
                case "TEP" { $netid="23"; }
		case "TOL" { $netid="33"; }
		case "VIL" { $netid="30"; }
		case "PUE" { $netid="29"; }
		else { $netid="ERR" }

	}
	return $netid;		
}

sub GetSiglasFromName {
        my $sig;
        use Switch;
        switch ($_[0]) {
                case "Guadalajara" { $sig="GDL"; }
                case "Obregon" { $sig="OBR"; }
                case "Tijuana" { $sig="TIJ"; }
                case "Mochis" { $sig="MCH"; }
                case "Culiacan" { $sig="CUL"; }
                case "Nogales" { $sig="NOG"; }
                case "Monterrey" { $sig="MTY"; }
                case "Tlalnepantla" { $sig="TLA"; }
                case "Mazatlan" { $sig="MAZ"; }
                case "Mexicali" { $sig="MXL"; }
                case "Merida" { $sig="MER"; }
                case "Can Cun" { $sig="CCN"; }
                case "Nuevo Laredo" { $sig="NVL"; }
                case "Iztapalapa" { $sig="IZT"; }
                case "Zapopan" { $sig="ZAP"; }
                case "Chihuahua" { $sig="CHI"; }
                case "Queretaro" { $sig="QUE"; }
                case "Santa Ana" { $sig="STA"; }
                case "Tepic" { $sig="TEP"; }
                case "Cd. Juarez" { $sig="JUA"; }
                case "Cancun" { $sig="CCN"; }
                case "Cd. Obregon" { $sig="OBR"; }
                case "Gudalajara" { $sig="GDL"; }
                case "Hermosillo" { $sig="HLO"; }
                case "Leon" { $sig="LGT"; }
                case "leon" { $sig="LGT"; }
                case "Los Mochis" { $sig="MCH"; }
                case "Mexico" { $sig="MEX"; }
                case "puebla" { $sig="PUE"; }
                case "SantaAna" { $sig="STA"; }
                case "tepic" { $sig="TEP"; }
                case "Tepotzotlan" { $sig="TPZ"; }
                case "Toluca" { $sig="TOL"; }
                case "zapopan" { $sig="ZAP"; }
                case "Villahermosa" { $sig="VIL"; }


                else { $sig="ERR" }

        }
        return $sig;
}

sub getnetfromsiglasb {
	use DBI;
	my $dbh2 = conectamysql("firewall","dbmsql.transportespitic.com","feria","bodycombat");
	$sql_query="select LAN from oficinas where abrev='".$_[0]."'";
	my $result=$dbh2->prepare($sql_query);
	$result->execute();
	my $rows = $result->rows;
	if ($rows eq 0) {
		return "ERR";
	} else {
		while ( my $row = $result->fetchrow_hashref()) {
			my @oct=split(/\./, $row->{LAN});
			return $oct[2];
		}
	}

}

sub settmpip {
	print "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXx";
	getnetfromhost();
        #my $ifconf=qx(ifconfig );
        #if ($vpnproc) { return 1; }
}

sub trim($)
{
        my $string = shift;
        $string =~ s/^\s+//;
	$string =~ s/^\t+//;
        $string =~ s/\s+$//;
        return $string;
}

sub checkprocess {
    my @lines = `ps -ef | grep $_[0]`;
        my $process="no";
    if(@lines) {
        foreach $line (@lines) {
                if (defined($_[1])) {
                        $resto="1";
                } else {
                        $resto=".";
                }
            if ($line =~ /^\s*(\w+)\s+(\d+).+$resto/) {
                $user = $1;
                $pid = $2;
                #print "$user  pid: $pid $line\n";
                $process="si";
            }
        }
    }
return $process;
}

sub havempls{
	my $file = "/home/feria/scripts/oficinas.mpls";
	my $existent = "0";
	open(FILE, $file) or die "[ERROR] Can't find file $file\n";
	while (<FILE>) {
		if (trim($_) eq trim($_[0])) {
			$existent="1";
		}
	}
	return $existent;
	close(FILE);
}


sub md5sum{
  my $file = shift;
  my $digest = "";
  eval{
    open(FILE, $file) or die "[ERROR] md5sum: Can't find file $file\n";
    my $ctx = Digest::MD5->new;
    $ctx->addfile(*FILE);
    $digest = $ctx->hexdigest;
    close(FILE);
  };
  if($@){
    print $@;
    return "";
  }
  return $digest;
}

sub hostmenos {
        my $host=removecidr($_[0]);
        my $newip;
        my @aipi = split(/\./, $host);
        $newip=$aipi[0].".".$aipi[1].".".$aipi[2].".".($aipi[3]-1);
        return $newip
}

sub hostmas {
        #my $host=$_[0];
	my $host=removecidr($_[0]);
        my $newip;
        my @aipi = split(/\./, $host);
        $newip=$aipi[0].".".$aipi[1].".".$aipi[2].".".($aipi[3]+1);
        return $newip
}


sub removecidr {
	my $fullip=$_[0];
	my $newip;
	my @aipi = split(/\//, $fullip); 
	$newip=$aipi[0];
	return $newip
}

sub hello {
    my $hello = shift;
    print "$hello\n";
}


sub getlocaloffice {
        my $hostnameout = qx(hostname);
        $hostnameout = uc(substr($hostnameout,2,3));
        print "\n".$hostnameout;
        return $hostnameout;
}


sub getldapserver {
	my $ldapserver="ldap.tpitic.com.mx";
	#my $ldapserver="torrent";
	return $ldapserver;	
}


# Usage (conectamysql("firewall","192.168.100.143","feria","perro");)
sub conectamysql {
	# database information
	$db="$_[0]";
	$host="$_[1]";
	$userid="$_[2]";
	$passwd="$_[3]";
	$connectionInfo="dbi:mysql:$db;$host";
	# make connection to database
	$dbh = DBI->connect($connectionInfo,$userid,$passwd) or die "Unable to connect: $DBI::errstr\n";
	return $dbh;
}


sub connectssh {
        my $command = "ssh -l $_[1] $_[0]";
print $command;
        $rootexpect = Expect->spawn($command, my @params) or die "Cannot spawn $command: $!\n";
	$rootexpect->log_stdout(1);
        # TODO: Add strict key checking yes
        $rootexpect->expect($_[4], "assword");
        $rootexpect->send("$_[2]\n");
        $rootexpect->expect('5', '#');
	return $rootexpect;
}



sub getlocalinterfaces {
	my @activeinterfaces = qx(/sbin/ifconfig);
	my $currentip;
	my $currentif;
        foreach my $rayac (@activeinterfaces) {
		if ($rayac =~ /inet\saddr:(\d+\.\d+\.\d+\.\d+)/) {
			$currentip = $1;
		}


               if ($rayac =~ /(\w+)\s+Link\sencap/) {
                        $currentif = $1;
               }
	#print " DDDDDDDDDDDDDDDDDDDDDDDDd el ip de $currentif es $currentip \n";

		if ($rayac =~ /RX\sbytes/) {
			#print " DDDDDDDDDDDDDDDDDDDDDDDDd el ip de $currentif es $currentip \n";
			#$interfaces{'ifname'} = $currentif;
			$interfaces{"$currentif"}->{'ip'} = $currentip;
			#$file_attachments{'test2.zip'}->{'price'} = '18.00';

			#$interfaces{'ip'} = $currentip;		
			$currentif="";
			$currentip="";
		}

        }



#use Data::Dumper;
#print Dumper(%interfaces);

return %interfaces;

#foreach my $k (keys (%interfaces))
#{ print "$k OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO";
#}

}

##
#foreach my $k (keys (%interfaces))
#{
 #       print "$k OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO";
#}



sub getinterfaces {
	#my @activeinterfaces = `/sbin/ifconfig  | grep "Link encap" | awk {'print $1'}`
	$rootexpect->send("/sbin/ifconfig  | grep \"Link encap\" \n");
	$rootexpect->send("###\n");
	$rootexpect->expect('5', '###');
	my $currentif;
	my $currentip;
	my @outp = split /\n/, $rootexpect->exp_before();
	foreach my $rayax (@outp) {
		if ($rayax =~ /(\w+\d)\s+Link\sencap/) {
			$currentif = $1;
			$rootexpect->send("/sbin/ifconfig $currentif  | grep inet\n");
			$rootexpect->send("###\n");
			$rootexpect->expect('5', '###');
			my @bulkip = split /\n/, $rootexpect->exp_before();
			foreach my $rayay (@bulkip) {
				if ($rayay =~ /inet\saddr:(\S+)/) {
					#print " DDDDDDDDDDDDDDDDDDDDDDDDd el ip de $currentif es $1 \n";
				}
			}
		}

	}
	


}

sub getrhversion {
        $rootexpect->send("cat /etc/redhat-release | grep edora \n");
        $rootexpect->send("###\n");
        $rootexpect->expect('5', '###');

        my @outp = split /\n/, $rootexpect->exp_before();
        foreach my $rayaz (@outp) {
		print "parsing $rayaz";
                if ($rayaz =~ /Fedora/) {
                        $currentver = $rayaz;
			print $currentver;
                }

        }
 if ($rootexpect->exp_before =~ /Fedora\sCore\srelease\s(\d)/) {
                        $currentver = $1;
                        print " ZZZZZZZZZ $currentver HHHHHHHH";
                }

	#return $currentver;	
}

# dothenat('dslif')
sub dothenat {
        print FW "echo NAT\n";
        print FW "iptables -t nat -F\n";
        print FW "iptables -t nat -A POSTROUTING -o $_[0] -j MASQUERADE\n";
	print FW "iptables -t nat -A POSTROUTING -o DSL -j MASQUERADE\n";
	print FW "iptables -t nat -A POSTROUTING -o brDSL -j MASQUERADE\n";
	print FW "#Not tryin 2525\n";
        print FW "iptables -t nat -A PREROUTING -p tcp -d 200.34.32.158 --dport 25 -j DNAT --to-destination 200.34.32.158:2526\n";
        print FW "iptables -t nat -I PREROUTING -p tcp -d 148.233.136.210 --dport 5222 -j DNAT --to-destination 148.233.136.211:5222\n";

}


sub getoctet() {
	
	my @octetspot = split(/\./, $_[0]);
		return $octetspot[($_[1]-1)];
}


# passwifi ( ip,ap# )
sub passwifi {
	print FW "\n";
	print FW "# WIRELESS ACCESS POINT # $_[1] \n";
	print FW "iptables -A FORWARD -p tcp -s $_[0] -d 192.168.3.190/32 -m multiport --dport 1521,1421 -j ACCEPT\n";
	print FW "iptables -A FORWARD -p tcp -s $_[0] -d 192.168.100.132/32 -m multiport --dport 1521,1421 -j ACCEPT\n";
	print FW "iptables -A FORWARD -p tcp -s $_[0] -d 192.168.100.2/32 -m multiport --dport 80,443 -j ACCEPT\n";
	print FW "iptables -A FORWARD -p tcp -s $_[0] -d 148.233.136.210/32 -m multiport --dport 80,443,53 -j ACCEPT\n";
	print FW "iptables -A FORWARD -p udp -s $_[0] -d 148.233.136.210/32 -m multiport --dport 80,443,53 -j ACCEPT\n";
	print FW "iptables -A FORWARD -s $_[0]/32 -j LOG --log-prefix \"ACCESS_POINT_TRAFFIC \"\n";
	print FW "iptables -A FORWARD -s $_[0]/32 -j DROP\n";
}


sub allowadmin {
	print FW "# Administration\n";
        print FW "iptables -A INPUT -s 192.168.141.0/24 -j ACCEPT\n";
        print FW "iptables -A INPUT -d 192.168.141.0/24 -j ACCEPT\n";
        print FW "iptables -A INPUT -s 192.168.140.0/24 -j ACCEPT\n";
        print FW "iptables -A INPUT -d 192.168.140.0/24 -j ACCEPT\n";
        print FW "iptables -A FORWARD -s 192.168.140.0/24 -j ACCEPT\n";
	print FW "iptables -A INPUT -s 192.168.3.0/24 -j ACCEPT\n";
	print FW "iptables -A INPUT -d 192.168.3.0/24 -j ACCEPT\n";
	print FW "iptables -A FORWARD -s 192.168.3.0/24 -j ACCEPT\n";
	print FW "iptables -A INPUT -d 192.168.120.0/24 -j ACCEPT\n";
	print FW "iptables -A OUTPUT -d 192.168.120.0/24 -j ACCEPT\n";
	print FW "iptables -A FORWARD -d 192.168.120.0/24 -j ACCEPT\n";
        print FW "iptables -A INPUT -s 192.168.120.0/24 -j ACCEPT\n";
        print FW "iptables -A OUTPUT -s 192.168.120.0/24 -j ACCEPT\n";
        print FW "iptables -A FORWARD -s 192.168.120.0/24 -j ACCEPT\n";
	print FW "iptables -A FORWARD -d 192.168.3.0/24 -j ACCEPT\n";
	print FW "iptables -A INPUT -s 148.223.74.118 -j ACCEPT\n";
	print FW "iptables -A INPUT -d 148.223.74.118 -j ACCEPT\n";
	print FW "iptables -A FORWARD -s 148.223.74.118 -j ACCEPT\n";
	print FW "iptables -A FORWARD -d 148.223.74.118 -j ACCEPT\n";
	print FW "iptables -A INPUT -s 148.223.74.117 -j ACCEPT\n";
	print FW "iptables -A INPUT -d 148.223.74.117 -j ACCEPT\n";
	print FW "iptables -A FORWARD -s 148.223.74.117 -j ACCEPT\n";
	print FW "iptables -A FORWARD -d 148.223.74.117 -j ACCEPT\n";
	print FW "iptables -A INPUT -s 148.233.139.61 -j ACCEPT\n";
	print FW "\n";
}

# Service -> KARSPERSKI ANTIVIRUS
#iptables -A FORWARD -p tcp -s 192.168.7.0/24 -d KARSPERSKI ANTIVIRUS/32  --dport 02] -j ACCEPT

#              $services=servpass("tcp","192.168.100.150","14315","$row->{LAN}","KARSPERSKI ANTIVIRUS");


sub conectatelnet {
        my $command = "telnet $_[0]";
        $rootexpect = Expect->spawn($command, my @params) or die "Cannot spawn $command: $!\n";
}

sub conectapde {
        my $command = "telnet $_[0]";
        $rootexpect = Expect->spawn($command, my @params) or die "Cannot spawn $command: $!\n";
        $rootexpect->expect(5,"name");
        $rootexpect->send("1nghm0\n");
	$rootexpect->expect(5,"word");
	$rootexpect->send("R1hM0\n");
        $rootexpect->expect(5,'#');
}

sub telnetinside {
        $rootexpect->send("telnet $_[0]\n");
}


sub sshinside {
        $rootexpect->send("ssh -l root $_[0]\n");
	$rootexpect->expect(5,"word");
	$rootexpect->send("sistemaspitic\n");
	$rootexpect->expect(5,'#');
}

sub autenticacisco {
        $rootexpect->expect(5,"name");
        $rootexpect->send("1nghm0\n");
        $rootexpect->expect(5,"word");
        $rootexpect->send("R1hM0\n");
        $rootexpect->expect(5,'#');
}

sub autenticalinux {
        $rootexpect->expect(15,"ogin");
        $rootexpect->send("operador\n");
        $rootexpect->expect(15,"word");
        $rootexpect->send("oficinatp\n");
        $rootexpect->expect(15,'$');
	$rootexpect->send("su\n");
	$rootexpect->expect(15,"word");
	$rootexpect->send("supersisadmin\n");
	$rootexpect->expect(15,'#');
}

sub interact {
        print "\n CONECTADO VIA PDE, salga tecleando XX\n";
        my $x;
        $rootexpect->interact($x, 'XX');
}

# servpass ( proto, dest, port lan description)
sub servpass {
	my $port;
	# check if multiple port
	if ($_[2] =~ /,/) {
		$port="-m multiport --dport";
	} else {
		$port="--dport";
	}
	print FW "\n";
	print FW "# Service -> $_[4]\n";
	print FW "iptables -A FORWARD -p $_[0] -s $_[3] -d $_[1]/32 $port $_[2] -j ACCEPT\n";
	print FW "\n";
}


sub chkmeeting {
        print "Numero de interface lan (un solo numero) teclee 0 para eth0\n";
        my $luserinputif =  <STDIN>;
        chomp ($luserinputif);
        print "Numero de ip completa192.168.X.X\n";
        my $luserinputip =  <STDIN>;
        chomp ($luserinputip);
        #my $tcommand="tcpdump -n -i eth".$luserinputif." host ".$luserinputip;
        my $tcommand="tcpdump -n -i eth".$luserinputif." host ".$luserinputip." and not host 204.176.46.248 and not host 148.233.136.210 and not host 200.34.32.158 and not host 148.233.136.219 and not net 204.176.46.0/24 and not net 64.41.193.0/24 and not net 65.221.5.0/24 and not net 65.102.5.0/24 and not dst port 3478 and not dst net 192.168.0.0/16";
        system($tcommand);
}
#1;

sub isvalidip {
	if ($_[0] =~ /(\d+\.\d+\.\d+\.\d+)/) {
		return "SI";
	}
	return "NO";
}
sub isvalidmac {
	if ($_[0] =~ /^([0-9A-F]{2}[:]){5}([0-9A-F]{2})$/) {
		return "SI";
	}
	return "NO";
}


sub GetOfiVPNdn {

my $dbhdf = conectamysql("firewall","dbmsql.transportespitic.com","feria","bodycombat");
    my $qdn="select * from openvpnpeers where oficina='".$_[0]."' and status=2";
    my $std = $dbhdf->prepare($qdn);
    $std->execute();
    while ( my $rowd = $std->fetchrow_hashref()) {
	    return $rowd->{cn};
    }
}

sub GetOfiVPNip {
    my $dbhde = conectamysql("firewall","dbmsql.transportespitic.com","feria","bodycombat");
    my $qdn="select * from openvpnpeers where oficina='".$_[0]."' and status=2";
    my $std = $dbhde->prepare($qdn);
    $std->execute();
    while ( my $rowd = $std->fetchrow_hashref()) {
            return $rowd->{ipadd};
    }
}

sub doping {
	use Net::Ping;
	$p = Net::Ping->new("icmp");
	$hostname=$_[0];
    #print "pingin $_[0] \n";
	my $time = 0;
	my $success = 0;
	my $n=1;
	foreach my $c (1 .. $n) {
		my ($ret, $duration, $ip) = $p->ping($hostname);
		if ($ret) {
		    $success++;
		    $time += $duration;
		}
	}
	if (not $success) {
		return "NO";
	} else {
        if ($success < $n) {
		#say( ($n - $success), " lost packets. Packet loss ratio: ", int ( 100 * ($n - $success) / $n ));
		return "YES";
        }
    }
}


sub GetAliasesFromLDAP {
	my @attrsb;
        #print "OBTENIENDO ALIASES PARA : ".$_[0]."\n";
        $ldapb = Net::LDAP->new( 'ldap.tpitic.com.mx' ) or die "$@";
        my $mesgb = $ldapb->bind("cn=feria,dc=transportespitic,dc=com", password=>"sistemaspitic");
        $mesgb = $ldapb->search( base => "ou=People,dc=transportespitic,dc=com", filter => "uid=$_[0]");
        my $maxb = $mesgb->count;
	my $entryb = $mesgb->entry(0);
	my $dnb = $entryb->dn;
	#@attrsb = $entryb->attributes;
	use Data::Dumper;
	#print Dumper @attrsb;
	my $valortz = $entryb->get_value( "aliasdecorreo", asref => 1 );
	return $valortz;
}

sub GetGoogleGroupFromLDAP {
	my @names = ();
        #print "OBTENIENDO ALIASES PARA GRUPO DE GOOGLE : ".$_[0]."\n";
        $ldapbc = Net::LDAP->new( 'ldap.tpitic.com.mx' ) or die "$@";
        my $mesgbc = $ldapbc->bind("cn=feria,dc=transportespitic,dc=com", password=>"sistemaspitic");
        $mesgbc = $ldapbc->search( base => "ou=TPiticGoogleAliases,ou=groups,dc=transportespitic,dc=com", filter => "cn=".$_[0] );
        my @entries = $mesgbc->entries();
	my $maxbc = $mesgbc->count;
	for( my $indexbc = 0 ; $indexbc < $maxbc ; $indexbc++)  {
		my $entrybc = $mesgbc->entry($indexbc);
		my $dnbc = $entrybc->dn;
		my @attrsbc = $entrybc->attributes;
		foreach my $varbc (@attrsbc) {
			my $attrbc = $entrybc->get_value( $varbc, asref => 1 );
			if ( defined($attrbc) ) {          
				foreach my $valuebc ( @$attrbc )    {
					#print "El valor de $varbc es: $valuebc\n";
					push @names, $valuebc;
				}                                                                       
			}	
		}
	}
	$mesgbc = $ldapbc->unbind;
	return @names;
}

sub GetGroupHashFromLDAP {
use warnings;
use Net::LDAP;
use Net::LDAP::Constant;
use Net::LDAP::Util qw(ldap_error_text);

	my %table;
	my $actual="DUNNO";
        print "OBTENIENDO HASHES DE GRUPO DE GOOGLE DESDE LDAP \n";
        $ldapbcd = Net::LDAP->new( 'ldap.tpitic.com.mx' ) or die "$@";
        my $mesgbcd = $ldapbcd->bind("cn=feria,dc=transportespitic,dc=com", password=>"sistemaspitic");
        $mesgbcd = $ldapbcd->search( base => "ou=TPiticGoogleAliases,ou=groups,dc=transportespitic,dc=com", filter => "cn=*" );
        my @entries = $mesgbcd->entries();
	my $maxbcd = $mesgbcd->count;
	for( my $indexbcd = 0 ; $indexbcd < $maxbcd ; $indexbcd++)  {
		my $entrybcd = $mesgbcd->entry($indexbcd);
		my $dnbcd = $entrybcd->dn;
		my @attrsbcd = $entrybcd->attributes;
		foreach my $varbcd (@attrsbcd) {
			my $attr = $entrybcd->get_value( $varbcd, asref => 1 );
	   		if ( defined($attr) ) {
	       			foreach my $value ( @$attr )    {
					if ( $varbcd eq "cn" ) {
						$table{$value} = [] unless exists $table{$value};
						$actual=$value;
						#print "grupo $actual \n";
					}
					if ( $varbcd eq "member" ) {
						#print "El valor de $varbcd es: $value\n";
						if ($value =~ /uid=(\w+),/) {
							push @{$table{$actual}}, $1;
						}
					}
	   			}
			}
		}
	}
	$mesgbcd = $ldapbcd->unbind;
	return %table;
}

sub ChkExistentGoogleGroupFromLDAP ($\%) {
	my $grupo       = shift;
	my %tablex      = ${shift()};
	use Data::Dumper;


=pod
print "que pedo $grupo \n";
use Data::Dumper;

	$existegp='NO';
	for my $alias (sort keys %tablex) {
print $alias."------------------------------ \n";
		if ($alias eq $grupo) {
			$existegp='SI';
			print "GRUPO $grupo EXISTE \n";
		} else {
			print "GRUPO $grupo NOOOOOOOOOOOO EXISTE \n";
		}
	}
	return $existegp;
=cut
}


sub GetUserHashFromLDAP {
        my %utable;
        my $actual="DUNNO";
        print "OBTENIENDO HASHES DE USUARIOS DESDE LDAP \n";
        $ldapbcd = Net::LDAP->new( 'ldap.tpitic.com.mx' ) or die "$@";
        my $mesgbcd = $ldapbcd->bind("cn=feria,dc=transportespitic,dc=com", password=>"sistemaspitic");
        $mesgbcd = $ldapbcd->search( base => "ou=People,dc=transportespitic,dc=com", filter => "uid=*" );
        my @entries = $mesgbcd->entries();
        my $maxbcd = $mesgbcd->count;
        for( my $indexbcd = 0 ; $indexbcd < $maxbcd ; $indexbcd++)  {
                my $entrybcd = $mesgbcd->entry($indexbcd);
                my $dnbcd = $entrybcd->dn;
                my @attrsbcd = $entrybcd->attributes;
                foreach my $varbcd (@attrsbcd) {
                        my $attr = $entrybcd->get_value( $varbcd, asref => 1 );
                        if ( defined($attr) ) {
                                foreach my $value ( @$attr )    {
                                        if ( $varbcd eq "uid" ) {
                                                $utable{$value} = [] unless exists $utable{$value};
                                                $actual=$value;
                                                #print "grupo $actual \n";
                                        }
                                        if ( $varbcd eq "aliasdecorreo" ) {
                                                #print "El valor de $varbcd es: $value\n";
                                                #if ($value =~ /uid=(\w+),/) {
                                                        push @{$utable{$actual}}, $value;
                                                #}
                                        }
                                }
                        }
                }
        }
        $mesgbcd = $ldapbcd->unbind;
        return %utable;
}


sub GetOfiFromHost {
        my $siglas;
        my $hostnam = qx(hostname | awk -F. {'print $1'});
	$hostnam = qx(hostname);
	chomp($hostnam);
	print "FFFFFFFFFFFFFFFFFFFFF".$hostnam."xxxxxxxxxxxxxxxxx";
        $hostnam[0]=uc($hostnam[0]);
        if ($hostnam[0] =~ /TP(\w\w\w)/) {
                $siglas=uc($1);
        }
        return $siglas;

}

sub GetOfiFromHostExp {
$rootexpect=$_[0];
        my $siglas;
	$cm="hostname | awk -F. {'print \$1'}";
	$rootexpect->send("$cm\n");
	$rootexpect->send("#\n");
	$rootexpect->expect('5', '# #');
        my $cursh = $rootexpect->before();
	#print $cursh
        $cursh=uc($cursh);
        if ($cursh =~ /TP(\w\w\w)/) {
                $siglas=uc($1);
        }
        return $siglas;

}


return 1;
