<?php 
	//info de la BD

	class ConfigInicial {

		private static $server = "localhost";
		private static $port = "3306";
		private static $dbName = "demoprogra";
		private static $user = "root";
		private static $pass = "";

		public static function Host () {
			return self::$server . ':' . self::$port;
		}

		public static function DB () {
			return self::$dbName;
		}

		public static function User () {
			return self::$user;
		}

		public static function Pass () {
			return self::$pass;
		}
	}	

 ?>