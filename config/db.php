<?php
/**
 * Provee las constantes para conectarse a la base de datos
 * 
 */

require_once 'parametros.php';


define("TNSPROD", "(DESCRIPTION =
                  (ADDRESS_LIST =
                    (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.2.90)(PORT = 1521))
                  )
                  (CONNECT_DATA =
                    (SERVICE_NAME = OPTIMA)
                  )
                )");

define("TNSBCK", "(DESCRIPTION =
                (ADDRESS_LIST =
                  (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.97)(PORT = 1521))
                )
                (CONNECT_DATA =
                  (SERVICE_NAME = BCKOP)
                )
              )");     
              
IF (ENVIROMENT === 'PROD') {
    define("DATABASE", "OPTIMA"); // Nombre del db
    define("USERNAME", "MYSHUZZ"); // Nombre del usuario
    define("PASSWORD", "MYSHUZZ"); // Nombre de la constraseña
    define("TNS", TNSPROD);
} ELSE {
    define("DATABASE", "BCKOP"); // Nombre del db
    define("USERNAME", "MYSHUZZ"); // Nombre del usuario
    define("PASSWORD", "MYSHUZZ"); // Nombre de la constraseña
    define("TNS", TNSBCK);
}              

?>