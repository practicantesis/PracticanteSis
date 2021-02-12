<?php
$err='NO';
$data='

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">IPs asignados por generador FW</h4>
                                <p class="text-muted"><code></code>
                                </p>
                                <div id="accordion-one" class="accordion">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4"><i class="fa" aria-hidden="true"></i>Hermosillo</h5>
                                        </div>
                                        <div id="collapseOne4" class="collapse" data-parent="#accordion-one">
                                            <div class="card-body"><pre>';
                                                $url='http://192.168.120.222/TRAVELLERS/travellers.txt';
                                                $lines_string=file_get_contents($url);
                                                $data.=$lines_string;
                                                $data.='</pre>
                                            </div>
                                        </div>
                                    </div>';
                                                $remotas = array('MCH','GDL');
                                                foreach($remotas as $ofi) {
                          $data .= '<div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0" data-toggle="collapse" data-target="#collapse'.$ofi.'" aria-expanded="true" aria-controls="collapse'.$ofi.'"><i class="fa" aria-hidden="true"></i>'.$ofi.'</h5>
                                        </div>
                                        <div id="collapse'.$ofi.'" class="collapse" data-parent="#accordion-one">
                                        <div class="card-body"><pre>';
                                            $url='http://192.168.120.170/TRAVELLERS/'.$ofi.'.txt';
                                            $lines_string=file_get_contents($url);
                                            $data.=$lines_string;
                                            $data.='</pre>
                                            </div>
                                        </div>';
                        }


                                    $data .='</div>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
';

$jsonSearchResults[] =  array(
	'success' => 'YES',
	'data' => $data,
	'error' => $err,
);
echo json_encode ($jsonSearchResults);

return false;

?>



