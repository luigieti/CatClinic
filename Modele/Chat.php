<?php

final class Chat extends CorrespondanceTable implements CorrespondanceTableInterface
{
	private $_I_age;

	private $_S_tatouage;

	private $_S_nom;

	public function __construct()
	{
		parent::__construct(Constantes::TABLE_CHAT);
	}

	// Mes mutateurs (setters)
	public function changeIdentifiant ($identifiant)
	{
		$this->_I_identifiant = $identifiant;
	}

	public function changeAge ($age)
	{
		$this->_I_age = $age;
	}

	public function changeNom ($nom)
	{
		$this->_S_nom = $nom;
	}

	public function changeTatouage ($tatouage)
	{
		$this->_S_tatouage = $tatouage;
	}

	// Mes accesseurs (getters)

	public function donneNom ()
	{
		return $this->_S_nom;
	}

	public function donneAge ()
	{
		return $this->_I_age;
	}

	public function donneTatouage ()
	{
		return $this->_S_tatouage;
	}

	public function trouverParIdentifiant ($I_identifiant)
	{
		if (isset($I_identifiant)) {
			$S_requete    = "SELECT nom, age, tatouage FROM " . $this->_S_nomTable .
	                        " WHERE id = $I_identifiant";
			$O_connexion  = ConnexionMySQL::recupererInstance();

			if ($A_chat = $O_connexion->projeter($S_requete))
			{
                $O_chat = $A_chat[0];
				// Je récupère son identifiant et je le stocke dans la variable d'instance prévue à cet effet
				if (is_object($O_chat)) {
					$this->_I_identifiant = $I_identifiant;
					$this->_I_age = $O_chat->age;
					$this->_S_nom = $O_chat->nom;
					$this->_S_tatouage = $O_chat->tatouage;
				}
			}
			else
			{
				// Je n'ai rien trouvé, je lève une exception pour le signaler au client de ma classe
				throw new Exception ("Il n'existe pas de chat pour l'identifiant '$I_identifiant'");
			}
		}
		else
		{
			throw new Exception ("L'identifiant d'un chat ne peut �tre vide et doit �tre un entier");
		}
	}

	public function creer()
	{
		if (empty($this->_S_nom) || empty($this->_I_age) || empty($this->_S_tatouage))
		{
			throw new Exception ("Impossible d'enregistrer le chat");
		}

		$S_tatouage = $this->_S_tatouage;
		$S_nom    = $this->_S_nom;
		$I_age    = $this->_I_age;

		$S_requete   = "INSERT INTO " . $this->_S_nomTable . " (nom, age, tatouage) VALUES ('$S_nom', $I_age, '$S_tatouage')";

		$O_connexion = ConnexionMySQL::recupererInstance();

		// j'insère en table et inserer me renvoie l'identifiant de mon nouvel enregistrement...je le stocke
		$this->_I_identifiant = $O_connexion->inserer($S_requete);
	}

	public function actualiser()
	{
		if (null != $this->_I_identifiant)
		{
			if (empty($this->_S_nom) || empty($this->_I_age) || empty($this->_S_tatouage))
			{
				throw new Exception ("Impossible d'enregistrer le chat");
			}

			$S_tatouage = $this->_S_tatouage;
			$S_nom    = $this->_S_nom;
			$I_age    = $this->_I_age;

			$S_requete   = "UPDATE " . $this->_S_nomTable . " SET nom = '$S_nom', tatouage = '$S_tatouage', age = $I_age WHERE identifiant = " . $this->_I_identifiant;
			$O_connexion = ConnexionMySQL::recupererInstance();
			$O_connexion->modifier($S_requete);

			return; // j'ai ce que je voulais...je débranche ici
		}

		throw new Exception ("Impossible de mettre a jour un chat qui n'existe pas");
	}

	public function supprimer()
	{
		if (null != $this->_I_identifiant)
		{
			// il me faut absolument un identifiant pour faire une suppression
			$S_requete   = "DELETE FROM " . $this->_S_nomTable . " WHERE identifiant = " . $this->_I_identifiant;
			$O_connexion = ConnexionMySQL::recupererInstance();

			// si modifier echoue elle me renvoie false, si aucun enregistrement n'est supprimé, elle renvoie zéro
			// attention donc à bien utiliser l'égalité stricte ici !
			if (false === $O_connexion->modifier($S_requete))
			{
				throw new Exception ("Impossible d'effacer le chat d'identifiant " . $this->_I_identifiant);
			}

			return;
		}

		throw new Exception ("Impossible d'effacer un enseignant sans identifiant");
	}
}