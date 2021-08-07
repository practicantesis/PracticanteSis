<?php 
// Conexion a bases de Oracle
	 	class oracle{
					
					var $nombre;	  var $database;  var $columna;
					var $usuario;   var $rows;      var $sql;
					var $password;  var $int;       var $query2;
					var $host;			var $array;
					var $db;				var $pointer;
					var $query;     var $dir;
					
					function oracle(){
					      $this->host = "192.168.100.132";
					      $this->port = "1521";
								$this->database = "tpitic";
								$this->usuario = "usuario_plight";
								$this->password = "u5uar10116ht";
								/*echo "<script language='javascript'>alert('Oracle DB');</script>";*/
					}
					
					function setNombre($nom){
								$this->nombre = $nom;	 
					}
					
					function setPassword($pass){
								$this->password = $pass;	 
					}
					
					function setUsuario($usu){
								$this->usuario = $usu;	 
					}
		
					function setHost($dirhost){
								$this->host = $dirhost;	 
					}
					
					function getNombre(){
								return $this->nombre;	 
					}

					function getUsuario(){
								return $this->usuario;	 
					}
					
					function getPassword(){
								return $this->password;	 
					}
					
					function direccion()
					{
					  $this->dir = $this->host.":".$this->port."/".$this->database;
						return $this->dir;
					}
					
					function conectar(){
					      $dir = $this->direccion();
								$this->db = OCILogon($this->usuario,$this->password,$dir);
								if ( ! $this->db ) {$this->db = var_dump( OCIError() ); die();}
								return $this->db; 
					}
					
					
					function close($dbs){
								OCILogoff($this->db);
					}
					
					function setDatabase($ndb){
								$this->database = $ndb;
								return $this->database;
					}		

					function setQuery($sqlstr){
					      $this->sql = $sqlstr;
					      $this->query = OCIparse($this->db,$sqlstr);
						$error = OCIExecute($this->query);
                if (!$error){ 
 									 			$error = OCIError($this->query); 
                        $error = "Error: ${error['code']} ${error['message']}";
												return $error;
								}else{ 
								return $this->query;
							  }
					}
					
					function setQueryAux($sqlstr){
					      $sql = $sqlstr;
					      $this->query2 = OCIparse($this->db,$sqlstr);
								$error = OCIExecute($this->query2);
                if (!$error){ 
 									 			$error = OCIError($this->query2); 
                        $error = "Error: ${error['code']} ${error['message']}";
												return $error;
								}else{ 
								return $this->query2;
							  }
					}
					
					function getNumrows($res){	
								$this->rows = OCIFetchStatement($res,$this->array);
								OCIExecute($this->query);
								return $this->rows;
					}
					
					function getNumrowsAux($res){	
								$rows = OCIFetchStatement($res,$array2);
								OCIExecute($this->query2);
								return $rows;
					}
					
					function getArray($qu){
					      $this->int = OcifetchInto($this->query,$this->array,OCI_ASSOC);
								if($this->int >= 1){return $this->array;}
					}
					
					function getArrayAux($qu){
					      $int2 = OcifetchInto($qu,$otro,OCI_ASSOC);
								if($int2 >= 1){return $otro;}
					}
					
					function movePointer($result,$row){
		           OCIExecute($result, OCI_DEFAULT);
			         for($i = 0; $i < $row; $i++){
							 				OCIFetch($result);
				       }
							 return $this->pointer;
					}
		}
// Conexion a bases de MySQL
		class mysql{
					
					var $nombre;	  var $database;
					var $usuario;   var $rows;
					var $password;  var $array;
					var $host;			var $objeto;
					var $db;				var $pointer;
					var $query;
					
					function mysql(){
					      $this->host = "192.168.100.143";
								/*echo "<script language='javascript'>alert('MySQL DB');</script>";*/
					}
					
					function setNombre($nom){
								$this->nombre = $nom;	 
					}
					
					function setPassword($pass){
								$this->password = $pass;	 
					}
					
					function setUsuario($usu){
								$this->usuario = $usu;	 
					}
		
					function setHost($dirhost){
								$this->host = $dirhost;	 
					}
					
					function getNombre(){
								return $this->nombre;	 
					}

					function getUsuario(){
								return $this->usuario;	 
					}
					
					function getPassword(){
								return $this->password;	 
					}
					
					
					function conectar(){
					      if($this->db > 0){}else{
								$this->db = mysql_connect("$this->host","$this->usuario","$this->password");}	
								return $this->db; 
					}
					
					
					function close($dbs){
								mysql_close($dbs);
					}
					
					function setDatabase($ndb){
					      if($this->db > 0){}else{$this->conectar();}
								$this->database = mysql_select_db($ndb);
								return $this->database;
					}		

					function setQuery($sqlstr){
					      $this->query = mysql_query("$sqlstr");
								return $this->query;
								
					}
					
					function getNumrows($res){	
								$this->rows = mysql_num_rows($res);
								return $this->rows;
					}
					
					function getArray($arre){
							  $this->array = mysql_fetch_array($arre);
								return $this->array;
					}
					
					function getObjeto($obj){
								$this->objeto = mysql_fetch_object($obj);
								return $this->objeto;
					}
					
					function movePointer($result,$num){
								$this->pointer = mysql_data_seek($result,$num);
								return $this->pointer;
					}
		}

