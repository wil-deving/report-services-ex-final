<?php 

	
	class Conexion {
		private static $conexion;

		static $success = false;

		public static function abrirConexion(){
			if(!isset(self::$conexion)){
				try{
					include_once 'ConfigInicial.php';
					$host = ConfigInicial::Host();
					$DB = ConfigInicial::DB();
					$user = ConfigInicial::User();
					$pass = ConfigInicial::Pass();
					//msqli es unicamente para mysql
					//pdo usaremos esta, ya que sooprta mas de 20 SGBD
					self::$conexion = new PDO("mysql:host=$host; dbname=$DB", $user, $pass);
					self::$conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					self::$conexion -> exec("SET CHARACTER SET utf8");
					//print ("CONEXION Abierta");
				}catch(PDOException $ex){
					print "Error: " . $ex -> getMessage() . "<br>";
					die();
				}
			}
		}

		public static function cerrarConexion(){
			if(isset(self::$conexion)){
				self::$conexion = null;
				//print ("CONEXION Cerrada");
			}
		}

		public static function obtenerConexion(){
			return self::$conexion;
		}

	}


 ?>