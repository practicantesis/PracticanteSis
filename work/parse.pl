#!/usr/bin/perl
use warnings;
use Net::LDAP;
use Net::LDAP::Constant;
use Net::LDAP::Util qw(ldap_error_text);
use DBI;
require "/home/feria/scripts/Routines.pl";
my $ldap;
$ldap = Net::LDAP->new( '172.16.3.20' ) or die "$@";
my $mesg = $ldap->bind("cn=feria,dc=transportespitic,dc=com", password=>"sistemaspitic");

open(CFG_FILE, "lidn.txt") or die "Can not open file, $!";

while (<CFG_FILE>) {
        push @ips, $_;
}
close (CFG_FILE) or die "Can not close file correctly";

my %cnt;

foreach my $raya (@ips) {
    chomp($raya);
    my @f = split(/###/, $raya);

    if ($f[8] =~ /BAJA_CEL/) { 
        print "\n  Cell ".$f[0]." Ya en Baja administrativa\n";
    } else {

        if ($f[1] eq "No Encontrado en API") {
                print "\n  Dando de baja ".$f[0]."  \n";
                if ($f[0] =~ /CEL(\w\w\w)/) {
                    $reg=getRegFromSiglas($1);
                    $OfiBaja=getCelBajaRegValue($reg);
                    $CveOfiBaja=getCelBajaRegClave($OfiBaja);
                    print "\n Tag: ".$f[0]."  Oficina: ".$1." Regional: $reg Ofibaja: $OfiBaja \n";
                    $NewDN="DeviceTAG=".$f[0].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
                    print "\n $NewDN \n";
                    ##$result = $ldap->modify($NewDN, replace => { "DeviceOffice" => $OfiBaja } );
                    ##print $result->error."\n";
                    my $dbhde = conectamysql("ocsweb","mysqlora","ocs",'#sistemaspitiC#123');
                    my $qdn="UPDATE accountinfo set fields_3='".$CveOfiBaja."' where TAG='".$f[0]."'";
                    print "\n cuery $qdn \n";
                    ##my $std = $dbhde->prepare($qdn);
                    ##$std->execute();
                }
        }

        if ($f[1] ne "No Encontrado en API") {
            $NewDN="DeviceTAG=".$f[0].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
            print "working on: \n TAG: ".$f[0]."\n DeviceLastSeen: ".$f[1]."\n Serie: ".$f[2]."\n Id: ".$f[3]."\n NoEmpleado: ".$f[4]."\n Oficina: ".$f[5]."\n Nombre: ".$f[6]."\n Asignado a: ".$f[7]."\n dn: ".$NewDN."\n ############################\n";
            ##$result = $ldap->modify($NewDN, replace => { "DeviceLastSeen" => $f[1] } );
            ##print $result->error."\n";
            #sleep 1000;

        }

        
    }        

    
}

###298184############mcallejas##


#curl -s -X POST -d 'DeviceId=' --header 'Authorization: Basic amZlcmlhOkxldHR5b3J0ZWdh' --header 'aw-tenant-code: Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=' https://as257.awmdm.com/api/mdm/devices/?searchby=Serialnumber&id=$1

#    $basic_auth='amZlcmlhOkxldHR5b3J0ZWdh';
#    $ch = curl_init();
#    $api_key='Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=';
#    $baseurl="https://as257.awmdm.com";
#    if ($tipo == "ALLDEVS") {
#        $endpoint="/API/mdm/devices/search";    
#    }
#    if ($tipo == "DEVICE") {
#        $endpoint="/api/mdm/devices/?searchby=Serialnumber&id=".$serie;
#    }


