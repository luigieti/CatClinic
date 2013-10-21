<?php

// Cette classe qui gère la connexion
// avec la base de donnée MySQL ne gère pas les injections SQL potentielles
// L'idéal serait d'utiliser des requêtes préparées
// Ce n'est pas fait pour garder tout ça le plus simple possible !

class ConnexionMySQL
{
	private static $O_instance;

	protected $_connection; // C'est là que réside ma connexion avec la base via PDO

	private function __construct ($S_nomBase)
	{
		// Les tables utilisent une collation latin1 et les pages xhtml
        // affichent de l'UTF8...je dis à MySQL que durant la connexion,
        // j'exige qu'on me donne de l'UTF8 (regardez le dernier paramètre du constructeur)
		$this->_connection = new PDO('mysql:host=' . Constantes::DATABASE_HOST . 
                                     ';dbname=' . Constantes::DATABASE_NAME,
                                     Constantes::DATABASE_USER,
                                     Constantes::DATABASE_PWD,
									 array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

		$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	static public function recupererInstance($S_nomBase = 'catclinic') // par défaut je travaille sur catclinic
	{
		if (!self::$O_instance instanceof self)
		{
			self::$O_instance = new self ($S_nomBase);
		}

		return self::$O_instance;
	}

	public function projeter ($S_requete)
	{
		return $this->_retournerTableau ($this->_connection->query($S_requete));
	}

	public function inserer ($S_requete)
	{
		$this->_connection->exec($S_requete);
		return $this->_connection->lastInsertId();
	}

	public function modifier ($S_requete)
	{
		return $this->_connection->exec($S_requete);
	}

    private function _retournerTableau (PDOStatement $O_pdoStatement)
    {
        $A_tuples = array();

        if ($O_pdoStatement)
		{
            while ($O_tuple = $O_pdoStatement->fetch (PDO::FETCH_OBJ))
			{
                $A_tuples[] = $O_tuple;
            }
        }

        return $A_tuples;
    }
}