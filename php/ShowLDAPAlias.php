<?php 
//print_r($_POST);
/*
$data='<div class="frmSearch">
        <input type="text" id="search-box" placeholder="User" />
        <div id="suggesstion-box"></div>
      </div>';
*/
$data='<div class="row">
			<div class="col-md-6 col-lg-3">
                <div class="card">
                    <img class="img-fluid" src="images/big/img1.jpg" alt="">
					<div class="card-body">
                        <h5 class="card-title">Buscar Usuario</h5>
						<div class="frmSearch">
        						<input type="text" id="search-box" placeholder="User" />
        						<div id="suggesstion-box"></div>
						</div>
                    	<p class="card-text">Buscar en LDAP</p>
                    	<p class="card-text"><small class="text-muted">Que monita card</small></p>
                    </div>
				</div>
			</div>
		</div>';
$data='';

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $data,
    'error' => $err,
);
echo json_encode ($jsonSearchResults);

return false;
?>