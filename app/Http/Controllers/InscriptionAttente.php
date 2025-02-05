<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Employer;
use App\Models\Carte;
use App\Models\Inscription_attente;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InscriptionAttente extends Controller
{
     public function index(Request $request)
        {
            try {
                $idCompte = session('connexion');
                $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
                Log::info('Chargement du tableau de bord client', ['email' => $emailUtilisateur]);

                $carte = Carte::where('idCompte', $idCompte)->first();
                $compte = Compte::where('idCompte', $idCompte)->first();

                $inscriptions = Inscription_attente::all();

                if ($inscriptions->isEmpty()) {
                    Log::warning('Aucun inscrit en attente trouvé');
                }

                $message = Message::where('afficher', true)->orderBy('id', 'desc')->first(); //Message
                $messageContent = $message ? $message->message : 'Aucun message disponible';

                return view('Admin.dashboardAdminInscriptionAttente', [
                    'messageContent' => $messageContent,
                    'inscriptions' => $inscriptions,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors du chargement du tableau de bord client', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors du chargement du tableau de bord.']);
            }
        }

  public function ajout($id) //Ajout dans carte et compte
      {

          try {
              // Récupérer l'inscription spécifique en utilisant l'ID
              $inscription = Inscription_attente::findOrFail($id);

              // Créer un nouveau compte
              $nouvelUtilisateur = new Compte();
              $nouvelUtilisateur->email = $inscription->mail;
              $nouvelUtilisateur->password = $inscription->mdp;
              $nouvelUtilisateur->role = $inscription->role;
              $nouvelUtilisateur->save();

              // Insérer les informations dans la table `carte`
              $carte = new Carte();
              $carte->nomEntreprise = $inscription->nom_entre;
              $carte->titre = NULL; // Vous pouvez définir un titre par défaut ou le récupérer d'une autre manière
              $carte->tel = NULL; // Vous pouvez définir un téléphone par défaut ou le récupérer d'une autre manière
              $carte->ville = NULL; // Vous pouvez définir une ville par défaut ou la récupérer d'une autre manière
              $carte->idCompte = $nouvelUtilisateur->idCompte; // Assurez-vous que l'ID du compte est correct
              $carte->idTemplate = 1; // Définissez un ID de template par défaut ou récupérez-le d'une autre manière
              $carte->couleur1 = "#000000";
              $carte->couleur2 = "#FFFFFF";
              $carte->lienQr = "/entreprises/{$nouvelUtilisateur->idCompte}_{$inscription->nom_entre}/QR_Codes/QR_Code.svg";
              $carte->save();

              // Appeler les méthodes pour générer le QR Code et la vCard
              Compte::QrCode($nouvelUtilisateur->idCompte, $inscription->nom_entre);
              Compte::creerVCard($carte->nomEntreprise, $carte->tel, $nouvelUtilisateur->email, $nouvelUtilisateur->idCompte);

              // Supprimer l'inscription de la table `inscript_attente`
              $inscription->delete();

              // Enregistrer un log
              Logs::ecrireLog($inscription->mail, "Inscription ajoutée à la table carte");

              return redirect()->route('InscriptionAttente')->with('success', 'L\'inscription a été ajoutée à la table carte avec succès.');
          } catch (\Exception $e) {
              Log::error('Erreur lors de l\'ajout de l\'inscription à la table carte', ['id_inscripAttente' => $id, 'error' => $e->getMessage()]);
              return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de l\'inscription à la table carte.']);
          }
      }

public function boutonInscriptionClient(Request $request) //Ajout dans la table inscription_attente
{

    if ($request->isMethod('post')) {
        $validationFormulaire = true;
        $messagesErreur = array();

        $mail = $request->input('mail');
        if (Inscription_attente::where('mail', $mail)->exists()) {
            $messagesErreur[] = "Cette adresse email a déjà été utilisée";
            $validationFormulaire = false;
        }

        if ($request->input('motDePasse1') != $request->input('motDePasse2')) {
            $messagesErreur[] = "Les deux mots de passe saisis ne sont pas identiques";
            $validationFormulaire = false;
        }

        if (preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[!@#%^&*()\$_+÷%§€\-=\[\]{}|;':\",.\/<>?~`]).{12,}$/", $request->input('motDePasse1')) === 0) {
            $messagesErreur[] = "Le mot de passe doit contenir au minimum 12 caractères comportant au moins une minuscule, une majuscule, un chiffre et un caractère spécial.";
            $validationFormulaire = false;
        }

        if ($validationFormulaire === false) {
            return view('Formulaire.formulaireInscriptionClient', ["messagesErreur" => $messagesErreur]);
        } else {
            $motDePasseHashe = password_hash($request->input('motDePasse1'), PASSWORD_BCRYPT);
            $role = $request->input('prodId');
            date_default_timezone_get();
            $date = date('Y/m/d');

          Inscription_attente::create([
                'nom_entre' => $request->input('entreprise'),
                'mail' => $request->input('mail'),
                'mdp' => $motDePasseHashe,
                'role' => $role,
                'date_inscription' => $date,
            ]);

            Logs::ecrireLog($request->input('mail'), "Inscription");
           return view('Formulaire.formulaireInscriptionClient', ["messageSucces" => "Inscription réussie, vous pouvez maintenant vous connecter"]);
        }
    }

    // Si le formulaire n'est pas soumis, afficher le formulaire avec les rôles
    return view('Formulaire.formulaireInscriptionClient');
}


    public function destroy($id)
    {
        try {
            Log::info('Tentative de suppression de l\'inscrit en attente', ['id_inscripAttente' => $id]);
            $inscription = Inscription_attente::findOrFail($id);
            $inscription->delete();

            Log::info('Inscription supprimée avec succès', ['id_inscripAttente' => $id]);
            return redirect()->route('InscriptionAttente')->with('success', 'L\'inscription a été supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'inscription', ['id_inscripAttente' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de l\'inscription.']);
        }
    }
}

