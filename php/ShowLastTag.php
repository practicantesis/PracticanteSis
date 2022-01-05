<?php
$err='NO';
$data='
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Identificador Oficina (3 letras en altas):</h4>
                                <p class="text-muted"><code></code>
                                </p>
                                            <div class="card-body"><pre>
                                                <INPUT TYPE="TEXT" id="ofi"><button type="button" class="btn btn-primary mb-2" onclick="GetLastAvailTag()">Get!</button>
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



