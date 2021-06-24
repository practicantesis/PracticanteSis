# CAMBIO
#!/usr/bin/perl
use Expect;
use DBI;
use IO::Socket::PortState qw(check_ports);

my $loginsrvrpv="jferiag";
my $passwsrvrpv='8fnj5JQ3';
my @params;
my $rootexpect;
my $uninetrpvserver = "200.33.150.129";

my $rootexpect;
my $command = "ssh -l $loginsrvrpv $uninetrpvserver";
my $hostsql = "localhost";
my $database = "pint";
my $dbh2 = conectamysql($database,$hostsql,"feria","bodycombat");


$rootexpect = Expect->spawn($command, @params) or die "Cannot spawn $command: $!\n";
$rootexpect->expect(22, "password:");
$rootexpect->send($passwsrvrpv."\n");
$rootexpect->send("\n");
$rootexpect->expect(22, 'ssix');


print "\n SSH Connected, lets party motherfuckers!!! \n";

my $sql_query="select * from RevisionPasswords where status='1000'";
print $sql_query."\n";
my $st = $dbh2->prepare($sql_query);
$st->execute();
$rows = $st->rows;
if ($rows > 0) {
	while ( my $rowa = $st->fetchrow_hashref()) {
		my $LOGGED="NO";
		print "Working on  $rowa->{id}\n";
		#foreach $a (@pass){
			if ($LOGGED eq "NO") {
				$serv='DUNNO';
				print $a."\n";
				@p = split(/xxx/, $a); 
				print $p[0]."->".$p[1]."\n";
				$rootexpect->clear_accum();  
				$rootexpect->send("\n");
				$rootexpect->expect(11, 'ssix');
				$rootexpect->send("\n");
				$rootexpect->expect(11, 'ssix');
				$rootexpect->send("\n");
				$rootexpect->expect(11, 'ssix');
				$rootexpect->send("\n");
				$rootexpect->expect(11, 'ssix');
				$rootexpect->send("telnet ".$rowa->{ip}." \n");
				print "First telnet cmd sent\n";
				# USERNAME
				$rootexpect->expect(11111,
					['Username:' => sub{
						$rootexpect->send($rowa->{badu}."\n");
						$serv='TELNET';
					}
					],
					['refused' => sub{
						$rootexpect->send("\n");
						$rootexpect->expect(11, 'ssix');
						$rootexpect->send("ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -l '".$p[0]."' ".$rowa->{ip}." \n");
					}
					],
					['No route to host' => sub{
						$rootexpect->send("\n");
						$rootexpect->expect(11, 'ssix');
						$rootexpect->send("ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -l '".$p[0]."' ".$rowa->{ip}." \n");
					}
					],
					['timed out' => sub{
						$rootexpect->send("\n");
						$rootexpect->expect(11, 'ssix');
						$rootexpect->send("ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -l '".$p[0]."' ".$rowa->{ip}." \n");
					}
					]

				);
				# Password
				$rootexpect->expect(11111,
					['Password:' => sub{
						$rootexpect->send($rowa->{badp}."\n");
						if ($serv eq 'DUNNO') {
							$serv='SSH';	
						}
					}
					],
					['No route to host' => sub{
						my $sql_queryb="UPDATE RevisionPasswords set status='8000'  where ip='".$rowa->{ip}."'";
						#UPDATE RevisionPasswords set status='1000', badu='reduno', badp='reduno'  where ip='201.118.104.186'
						print $sql_queryb."\n";
						my $stb = $dbh2->prepare($sql_queryb);
						$stb->execute();
						$LOGGED="NOROUTE";
					}
					],
					['Connection refused' => sub{
						my $sql_queryb="UPDATE RevisionPasswords set status='4000'  where ip='".$rowa->{ip}."'";
						#UPDATE RevisionPasswords set status='1000', badu='reduno', badp='reduno'  where ip='201.118.104.186'
						print $sql_queryb."\n";
						my $stb = $dbh2->prepare($sql_queryb);
						$stb->execute();
						$LOGGED="REFUSED";
					}
					],

					['Connection closed by' => sub{
						print "CLOSED!!!";
						$rootexpect->send("\n");
						$rootexpect->send("\n");
					}
					],
					['timed out' => sub{
						my $sql_queryb="UPDATE RevisionPasswords set status='9000'  where ip='".$rowa->{ip}."'";
						#UPDATE RevisionPasswords set status='1000', badu='reduno', badp='reduno'  where ip='201.118.104.186'
						print $sql_queryb."\n";
						my $stb = $dbh2->prepare($sql_queryb);
						$stb->execute();
						if ($a eq 'redunoxxxreduno') {
							$LOGGED="TIMEOUT";
						}
					}
					]
				);
				$rootexpect->expect(11111,
					['#' => sub{
						#my $sql_queryb="UPDATE RevisionPasswords set status='1000', badu='".$p[0]."', badp='".$p[1]."'  where ip='".$rowa->{ip}."'";
						#UPDATE RevisionPasswords set status='1000', badu='reduno', badp='reduno'  where ip='201.118.104.186'
						#print $sql_queryb."\n";
						#my $stb = $dbh2->prepare($sql_queryb);
						#$stb->execute();


						$rootexpect->send("conf t\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege interface all level 10 ip accounting output-packets\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege interface level 10 ip accounting\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege interface level 10 ip\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 1 ping\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 terminal monitor\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 terminal\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show access-lists\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip accounting\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip nbar protocol-discovery\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip nbar\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip route\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip interface brief\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip interface\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show ip\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show cdp neighbors\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show cdp\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show logging\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show startup-config\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show running-config\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show configuration\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 show\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 clear ip accounting\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 1 clear counters\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("privilege exec level 10 clear\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("username monitorgsi privilege 10 secret m0nitor%cL1ent3\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("end\n");
						$rootexpect->expect(11,'fig');

						$rootexpect->send("sh runn | incl privilege\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("sh runn | incl user\n");
						$rootexpect->expect(11,'fig');
						$rootexpect->send("wr\n");
						$rootexpect->expect(11,'fig');



						$rootexpect->send("exit\n");
						$rootexpect->expect(11, 'ssix');
						$LOGGED="CONFIGURED";
						print "antes last ";
						#last;
						#exp_continue;
						print "despues last ";
					}
					],
					['ssix' => sub{
						$rootexpect->send("\n");
						$rootexpect->expect(11, 'ssix');
					}
					],
					['% Login invalid' => sub{
						$rootexpect->send("x\n");
						$rootexpect->send("x\n");
						$rootexpect->send("s\n");
						$rootexpect->send("c\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->expect(11, 'ssix');
					}
					],
					['% Authentication failed' => sub{
						$rootexpect->send("x\n");
						$rootexpect->send("x\n");
						$rootexpect->send("s\n");
						$rootexpect->send("c\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->expect(1111, 'ssix');
					}
					],
					['word:' => sub{
						$rootexpect->send("x\n");
						$rootexpect->expect(11, 'word:');
						$rootexpect->send("x\n");
						$rootexpect->expect(11, 'word:');
						$rootexpect->send("s\n");
						$rootexpect->send("c\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->send("d\n");
						$rootexpect->expect(11, 'ssix');
					}
					]
				);
			}
		#}
		if ($LOGGED eq "NO") {
			my $sql_queryb="UPDATE RevisionPasswords set status='500' where ip='".$rowa->{ip}."'";
			my $stb = $dbh2->prepare($sql_queryb);
			$stb->execute();
		}
		if ($LOGGED eq "CONFIGURED") {
			my $sql_queryb="UPDATE RevisionPasswords set status='15000' where ip='".$rowa->{ip}."'";
			my $stb = $dbh2->prepare($sql_queryb);
			$stb->execute();
		}

		if ($LOGGED eq "YES") {
			print "BINGO! ".$rowa->{ip}." LOGGED!!! ";			
		}
		my $sql_queryc="UPDATE RevisionPasswords set SERVICIO='".$serv."' where ip='".$rowa->{ip}."'";
		my $stc = $dbh2->prepare($sql_queryc);
		$stc->execute();

		#exit;
	}
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

