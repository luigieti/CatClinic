<?php

// J'interdis toute dérivation ultérieure de ma classe à l'aide de final
final class Visite extends CorrespondanceTable implements CorrespondanceTableInterface
{
	private $_F_prix;

	private $_S_date;

	private $_S_observations;

	public function __construct()
	{
		parent::__construct(Constantes::TABLE_VISITE);
	}

	// Mes mutateurs (setters)
	public function changeIdentifiant ($identifiant)
	{
		$this->_I_identifiant = $identifiant;
	}

	public function changePrix ($prix)
	{
		$this->_F_prix = $prix;
	}

	public function changeDate ($date)
	{
		$this->_S_date = $date;
	}

	public function changeObservations ($observations)
	{
		$this->_S_observations = $observations;
	}

	// Mes accesseurs (getters)
	public function donnePrix ()
	{
		return $this->_F_prix;
	}

	public function donneDate ()
	{
		return $this->_S_date;
	}

	public function donneObservations ()
	{
		return $this->_S_observations;
	}

    public function trouverParIdentifiant ($I_identifiant)
	{
		$S_requete    = "SELECT id_praticien, id_chat, date, prix, observations FROM " . $this->_S_nomTable .
                        " WHERE id = $I_identifiant";

		$O_connexion  = ConnexionMySQL::recupererInstance();

		if ($A_visite = $O_connexion->projeter($S_requete))
		{
			$O_visite = $A_visite[0];
			$this->F_prix          = $O_visite->prix;
			$this->_S_date         = $O_visite->date;
			$this->_S_observations = $O_visite->observations;
		}
		else
		{
			// Je n'ai rien trouvé, je lève une exception pour le signaler au client de ma classe
			throw new Exception ("Il n'existe pas de visite pour l'identifiant '$I_identifiant'");
		}
	}

	public function trouverParIdentifiantChat ($I_identifiantChat)
	{
		$S_requete    = "SELECT id_praticien, id_chat, date, prix, observations FROM " . $this->_S_nomTable .
                        " WHERE id_chat = $I_identifiantChat"; // on peut renvoyer plusieurs visites pour un chat

		$O_connexion  = ConnexionMySQL::recupererInstance();

		if ($A_visite = $O_connexion->projeter($S_requete))
		{
			$A_visites = null;

			foreach ($A_visite as $O_visiteEnBase)
			{
				$O_visite = new Visite;
				$O_visite->changePrix($O_visiteEnBase->prix);
				$O_visite->changeDate($O_visiteEnBase->date);
				$O_visite->changeObservations($O_visiteEnBase->observations);

				$A_visites[] = $O_visite;
			}

			return $A_visites;
		}
		else
		{
			// Je n'ai rien trouvé, je lève une exception pour le signaler au client de ma classe
			throw new Exception ("Il n'existe pas de visite pour l'identifiant de chat '$I_identifiantChat'");
		}
	}

	public function creer()
	{
	}

	public function actualiser()
	{
	}

	public function supprimer()
	{
	}
}