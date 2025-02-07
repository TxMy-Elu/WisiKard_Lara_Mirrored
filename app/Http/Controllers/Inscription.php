<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Logs;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class Inscription extends Controller
{
    /**
     * Affiche le formulaire d'inscription avec les rôles disponibles.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireInscription avec les rôles disponibles.
     */
    public function afficherFormulaireInscription()
    {
        $roles = DB::table('compte')->select('role')->distinct()->get();
        return view('Formulaire.formulaireInscription', ['roles' => $roles]);
    }

    /**
     * Gère la soumission du formulaire d'inscription.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireInscription avec des messages d'erreur ou de succès.
     */
    public function boutonInscription()
    {
        // Récupère les rôles de la table compte
        $roles = DB::table('compte')->select('role')->distinct()->get();

        if (isset($_POST["boutonInscription"])) {
            $validationFormulaire = true;
            $messagesErreur = array();

            if (Compte::existeEmail($_POST["email"])) {
                $messagesErreur[] = "Cette adresse email a déjà été utilisée";
                $validationFormulaire = false;
            }
            if ($_POST["motDePasse1"] != $_POST["motDePasse2"]) {
                $messagesErreur[] = "Les deux mots de passe saisis ne sont pas identiques";
                $validationFormulaire = false;
            }
            if (preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[!@#%^&*()\$_+÷%§€\-=\[\]{}|;':\",.\/<>?~`]).{12,}$/", $_POST["motDePasse1"]) === 0) {
                $messagesErreur[] = "Le mot de passe doit contenir au minimum 12 caractères comportant au moins une minuscule, une majuscule, un chiffre et un caractère spécial.";
                $validationFormulaire = false;
            }

            if ($validationFormulaire === false) {
                return view('Formulaire.formulaireInscription', ["messagesErreur" => $messagesErreur, 'roles' => $roles]);
            } else {
                $motDePasseHashe = password_hash($_POST["motDePasse1"], PASSWORD_BCRYPT);

                Compte::inscription($_POST["email"], $motDePasseHashe, $_POST["role"], $_POST["entreprise"]);
                Logs::ecrireLog($_POST["email"], "Inscription");


                // Récupération des entreprises et des mails des comptes
                $entreprises = Carte::join('compte', 'carte.idCompte', '=', 'compte.idCompte')
                    ->select('carte.*', 'compte.*')
                    ->get();

                // Formatage des numéros de téléphone de chaque entreprise
                foreach ($entreprises as $entreprise) {
                    $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
                }

                // Récupération du dernier message à afficher
                $message =Message::orderBy('id', 'desc')->first();
                $messageContent = $message ? $message->message : 'Aucun message disponible';

                return view('Admin.dashboardAdmin', ["messageSucces" => "Inscription réussie, vous pouvez maintenant vous connecter", 'roles' => $roles, 'entreprises' => $entreprises, 'messageContent' => $messageContent]);
            }
        }

        // Si le formulaire n'est pas soumis, afficher le formulaire avec les rôles
        return view('Formulaire.formulaireInscription', ['roles' => $roles]);
    }

    /**
     * Formate un numéro de téléphone en ajoutant un point entre chaque groupe de deux chiffres.
     *
     * @param string $phoneNumber Numéro de téléphone à formater.
     * @return string Le numéro de téléphone formaté avec des points entre chaque groupe de deux chiffres.
     *
     * Cette méthode utilise une expression régulière pour diviser le numéro de téléphone en groupes
     * de deux chiffres, en insérant un point (.) après chaque groupe, sauf le dernier.
     * Par exemple, un numéro "0612345678" sera transformé en "06.12.34.56.78".
     */
    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

}
