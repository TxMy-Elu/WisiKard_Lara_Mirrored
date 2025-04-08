<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Logs;
use App\Models\Vue;
use App\Models\Carte;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardAdmin extends Controller
{
    protected $compte;
    protected $vue;
    protected $carte;
    protected $message;

    /**
     * Constructeur du contrôleur DashboardAdmin.
     *
     * @param Compte $compte Instance du modèle Compte pour gérer les comptes des utilisateurs.
     * @param Vue $vue Instance du modèle Vue pour gérer les vues.
     * @param Carte $carte Instance du modèle Carte pour gérer les entreprises/cartes.
     * @param Message $message Instance du modèle Message pour gérer les messages administratifs.
     *
     * Ce constructeur injecte les dépendances nécessaires pour le fonctionnement du tableau de bord de l'administrateur.
     * Ces modèles sont utilisés pour interagir avec les données des comptes, des vues, des entreprises (cartes)
     * et des messages dans les différentes méthodes du contrôleur.
     */
    public function __construct(Compte $compte, Vue $vue, Carte $carte, Message $message)
    {
        $this->compte = $compte;
        $this->vue = $vue;
        $this->carte = $carte;
        $this->message = $message;
    }

    /**
     * Affiche le tableau de bord de l'administrateur avec les détails des entreprises et le dernier message.
     *
     * @param Request $request Requête HTTP contenant les paramètres, notamment la recherche.
     * @return \Illuminate\View\View Retourne la vue du tableau de bord de l'administrateur.
     *
     * Cette méthode récupère et affiche les informations des entreprises (avec recherche facultative
     * par nom ou email) ainsi que le dernier message à afficher. Elle utilise un formatage dédié pour
     * les numéros de téléphone et intègre les résultats dans la vue destinée au tableau de bord.
     */
    public function afficherDashboardAdmin(Request $request)
    {
        // Récupération du paramètre de recherche (si fourni)
        $search = $request->input('search');

        // Récupération des entreprises avec recherche
        $entreprises = $this->carte->join('compte', 'carte.idCompte', '=', 'compte.idCompte')
            ->when($search, function ($query, $search) {
                return $query->where('carte.nomEntreprise', 'like', "%{$search}%")
                    ->orWhere('compte.email', 'like', "%{$search}%");
            })
            ->select('carte.*', 'compte.email as compte_email', 'compte.role as compte_role')
            ->get();

        // Formatage des numéros de téléphone
        foreach ($entreprises as $entreprise) {
            $entreprise->formattedTel = $this->formatPhoneNumber($entreprise->tel);
        }

        // Récupération de tous les messages actifs
        $messages = $this->message->where('afficher', true)
                            ->orderBy('id', 'desc')
                            ->get();

        // Retourne la vue avec les données
        return view('Admin.dashboardAdmin', compact('entreprises', 'search', 'messages'));
    }

    /**
     * Affiche le formulaire de modification du mot de passe d'un compte spécifique.
     *
     * @param int $id Identifiant du compte pour lequel le mot de passe sera modifié.
     * @return \Illuminate\View\View Retourne la vue contenant le formulaire de modification.
     *
     * Cette méthode recherche le compte correspondant à l'identifiant fourni. Si le compte existe,
     * elle retourne une vue avec les informations du compte. Si aucun compte n'est trouvé, elle
     * retourne une erreur 404 avec un message explicite indiquant que le compte n'a pas été trouvé.
     */
    public function showModifyPasswordForm($id)
    {
        try {
            // Recherche du compte correspondant à l'identifiant
            $compte = Compte::find($id);

            // Si le compte n'existe pas, lancer une erreur 404
            if (!$compte) {
                abort(404, 'Compte non trouvé');
            }

            // Retourne la vue avec les données du compte
            return view('Formulaire.formulaireModifMDP', compact('compte'));
        } catch (\Exception $e) {
            // Gestion des exceptions imprévues
            Log::error("Erreur lors du chargement du formulaire : " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Met à jour le mot de passe d'un utilisateur.
     *
     * @param Request $request Requête HTTP contenant les données soumises par l'utilisateur.
     * @param int $id Identifiant de l'utilisateur dont le mot de passe doit être modifié.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View Une redirection ou une vue, selon le résultat.
     *
     * Cette méthode permet de modifier le mot de passe d'un utilisateur après vérification :
     * - Les deux mots de passe saisis doivent être identiques.
     * - Le mot de passe doit respecter des règles strictes de complexité (au moins 12 caractères comprenant une minuscule,
     *   une majuscule, un chiffre et un caractère spécial).
     * Si les validations sont réussies, le mot de passe est hashé et sauvegardé en base de données. Les actions importantes
     * et les erreurs sont enregistrées dans les logs. En cas de succès, l'utilisateur est redirigé avec un message de succès.
     * En cas d'échec, des messages d'erreur sont retournés.
     */
    public function updateMDP(Request $request, $id)
    {
        try {
            // Affiche les données entrantes
            Log::info('Données entrantes : ', $request->all());

            // Votre validation
            $validated = $request->validate([
                'mdp1' => [
                    'required',
                    'min:12',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^&\*]).{12,}$/'
                ],
                'mdp2' => ['required', 'same:mdp1']
            ], [
                'mdp1.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial et faire au moins 12 caractères.',
                'mdp2.same' => 'Les mots de passe doivent correspondre.'
            ]);

            // Procedure normale si valides
            $compte = Compte::findOrFail($id);
            $compte->password = bcrypt($validated['mdp1']);
            $compte->save();

            return redirect()->route('dashboardAdmin')->with('success', 'Mot de passe modifié avec succès.');
        } catch (\ValidationException $e) {
            // Affiche les erreurs de validation dans les logs
            Log::error('Validation échouée : ', $e->validator->errors()->all());
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Autres erreurs générales
            Log::error('Erreur lors de la mise à jour du mot de passe : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }


    /**
     * Affiche les statistiques annuelles et mensuelles des vues et données entreprises.
     *
     * @param Request $request Requête HTTP contenant les paramètres de l'utilisateur (année et mois optionnels).
     * @return \Illuminate\View\View La vue affichant les statistiques au tableau de bord de l'administration.
     *
     * Cette méthode récupère et formate les statistiques basées sur les vues mensuelles et le nombre
     * total d'entreprises pour une année donnée. Par défaut, elle affiche les données pour l'année
     * en cours si aucun paramètre d'année n'est fourni dans la requête. Les données statistiques
     * sont regroupées par mois et utilisées pour générer des graphiques. La méthode retourne ensuite
     * une vue contenant les données compilées.
     */
    public function statistique(Request $request)
    {
        // Récupération de l'année (par défaut l'année en cours) et le mois (optionnel) depuis les paramètres de la requête
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        // Récupération des vues mensuelles pour l'année spécifiée
        $yearlyViews = $this->vue->selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Organisation des données annuelles pour des graphiques
        $yearlyData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Nombre de vues par mois',
                    'backgroundColor' => 'rgba(153, 27, 27, 0.2)',
                    'borderColor' => 'rgba(153, 27, 27, 1)',
                    'borderWidth' => 1,
                    'data' => array_values(array_replace(array_fill(1, 12, 0), $yearlyViews)), // Associer les vues mensuelles à chaque mois
                ],
            ],
        ];

        // Statistiques du nombre de compte advanced et starter
        $compteAdvanced = $this->compte->where('role', 'advanced')->count();
        $compteStarter = $this->compte->where('role', 'starter')->count();

        // Récupération dynamique des données des templates
        $templates = $this->carte->selectRaw('idTemplate, COUNT(*) as count')
            ->groupBy('idTemplate')
            ->pluck('count', 'idTemplate')
            ->toArray();

// Traduire les ID spécifiques en labels
        $labels = [
            1 => '1: base',
            2 => '2: custom',
            3 => '3: pomme',
            4 => '4: classy',
        ];

// Construire les labels et les données correspondantes
        $finalLabels = [];
        $data = [];

        foreach ($labels as $id => $label) {
            $finalLabels[] = $label;
            $data[] = $templates[$id] ?? 0; // Si un ID n'existe pas, sa valeur sera 0
        }

// Générer la structure Chart.js
        $nbTemplateData = [
            'labels' => $finalLabels, // Labels dynamiques
            'datasets' => [
                [
                    'label' => 'Nombre de cartes par template',
                    'backgroundColor' => array_map(function ($index) {
                        $colors = [
                            'rgba(153, 27, 27, 0.2)',  // Rouge
                            'rgba(27, 153, 27, 0.2)',  // Vert
                            'rgba(27, 27, 153, 0.2)',  // Bleu
                            'rgba(153, 153, 27, 0.2)', // Jaune
                            'rgba(153, 27, 153, 0.2)'  // Violet
                        ];
                        return $colors[$index % count($colors)];
                    }, array_keys($labels)), // Génération des couleurs dynamiquement
                    'borderColor' => array_map(function ($index) {
                        $borderColors = [
                            'rgba(153, 27, 27, 1)',  // Rouge
                            'rgba(27, 153, 27, 1)',  // Vert
                            'rgba(27, 27, 153, 1)',  // Bleu
                            'rgba(153, 153, 27, 1)', // Jaune
                            'rgba(153, 27, 153, 1)'  // Violet
                        ];
                        return $borderColors[$index % count($borderColors)];
                    }, array_keys($labels)), // Génération des bordures dynamiques
                    'borderWidth' => 1,
                    'data' => $data, // Données dynamiques pour chaque label
                ],
            ],
        ];

        // Statistiques générales pour l'année
        $totalViews = $this->vue->whereYear('date', $year)->count(); // Total des vues pour l'année
        $totalEntreprise = $this->carte->count(); // Total des entreprises enregistrées

        $nbCompteData = [
            'labels' => ['Compte Advanced', 'Compte Starter'],
            'datasets' => [
                [
                    'label' => 'Nombre de comptes',
                    'backgroundColor' => ['rgba(153, 27, 27, 0.2)', 'rgba(27, 153, 27, 0.2)'],
                    'borderColor' => ['rgba(153, 27, 27, 1)', 'rgba(27, 153, 27, 1)'],
                    'borderWidth' => 1,
                    'data' => [$compteAdvanced, $compteStarter],
                ],
            ],
        ];

        // Liste des années pour le choix dans l'interface (10 dernières années)
        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year; // L'année sélectionnée pour l'affichage

        // Récupérer le nombre de vues par compte
        $vuesParCompte = DB::table('vue')
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->join('compte', 'carte.idCompte', '=', 'compte.idCompte')
            ->select('compte.email', 'carte.nomEntreprise', DB::raw('COUNT(*) as total_vues'))
            ->groupBy('compte.email', 'carte.nomEntreprise')
            ->having('total_vues', '>', 1)
            ->orderBy('total_vues', 'DESC')
            ->get();

        // Retourner la vue des statistiques avec les données préparées
        return view('Admin.dashboardAdminStatistique', compact(
            'yearlyData', 
            'years', 
            'selectedYear', 
            'month', 
            'totalViews', 
            'totalEntreprise', 
            'nbCompteData', 
            'nbTemplateData',
            'vuesParCompte'
        ));
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

    /**
     * Ajoute un nouveau message dans la base de données.
     *
     * @param Request $request Requête HTTP contenant les données du client.
     * @return \Illuminate\Http\RedirectResponse Une redirection vers le tableau de bord de l'administrateur.
     *
     * Cette méthode permet de valider les données fournies pour la création d'un nouveau message.
     * Elle vérifie que le champ message est obligatoire, de type chaîne de caractères et limité à
     * 255 caractères. En cas d'erreur de validation, l'utilisateur est redirigé vers la page précédente
     * avec les messages d'erreur associés. Si la validation est réussie, le message est créé et affiché
     * par défaut. Une fois l'opération terminée, l'utilisateur est redirigé vers le tableau de bord
     * de l'administrateur.
     */
    public function ajoutMessage(Request $request)
    {
        // Validation des données envoyées
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255', // Le champ "message" est obligatoire, texte et max 255 caractères
        ]);

        // Gestion des échecs de validation
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Retourne avec les erreurs et l'entrée initiale
        }

        // Création d'un nouveau message avec l'état "affiché" par défaut
        $this->message->create([
            'message' => $request->input('message'),
            'afficher' => true, // Affichage activé par défaut
        ]);

        // Redirection vers le tableau de bord
        return redirect()->route('dashboardAdminMessage');
    }

    /**
     * Alterne l'état d'affichage d'un message dans la base de données.
     *
     * @param int $id Identifiant unique du message dont l'état doit être alterné.
     * @return \Illuminate\Http\RedirectResponse Une redirection vers le tableau de bord de l'administrateur.
     *
     * Cette méthode permet de modifier l'état d'affichage d'un message (affiché ou non affiché).
     * Si le message est trouvé, son état d'affichage est inversé, il est sauvegardé dans la base de données,
     * et une trace de l'opération est ajoutée dans les logs. Si aucun message correspondant à l'identifiant
     * fourni n'est trouvé, un log est également créé pour l'indiquer. Une redirection est effectuée vers
     * le tableau de bord après l'exécution.
     */
    public function toggleMessage($id)
    {
        // Recherche du message à modifier
        $message = $this->message->find($id);

        if ($message) {
            // Alterner l'état d'affichage
            $message->afficher = !$message->afficher;
            $message->save();

            // Enregistrement dans les logs
            Log::info('État du message ' . $message->id . ' inversé');
        } else {
            // Log en cas d'absence du message
            Log::info('Message avec l\'id ' . $id . ' non trouvé');
        }

        // Redirection vers le tableau de bord
        return redirect()->route('dashboardAdminMessage');
    }

    /**
     * Modifie un message existant dans la base de données.
     *
     * @param Request $request Requête HTTP contenant les données du client.
     * @param int $id Identifiant unique du message à modifier.
     * @return \Illuminate\Http\RedirectResponse Une redirection vers le tableau de bord de l'administrateur
     *                              accompagnée d'un message de succès ou d'erreur.
     *
     * Cette méthode permet de valider les données fournies avant de modifier le message
     * spécifié par son identifiant. En cas d'erreur de validation, l'utilisateur est redirigé
     * vers la page précédente avec les messages d'erreur correspondants. Une fois la validation
     * réussie, les modifications sont appliquées, et des logs sont enregistrés pour tracer
     * l'opération. En cas de succès, l'utilisateur est redirigé avec une confirmation de la mise à jour.
     */
    public function modifierMessage(Request $request, $id)
    {
        // Validation des données envoyées dans la requête
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255', // Le message est obligatoire, de type chaîne, et limité à 255 caractères
        ]);

        // Gestion des erreurs de validation
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Retourner avec les erreurs et les données saisies
        }

        // Recherche du message existant
        $message = $this->message->findOrFail($id);

        // Mise à jour du contenu du message
        $message->message = $request->input('message');
        $message->save();

        // Enregistrement dans les logs d'application
        Log::info('Message ' . $message->id . ' modifié avec succès');

        // Enregistrement dans les logs personnalisés avec l'email de l'utilisateur connecté
        Logs::ecrireLog($request->session()->get('email'), 'Modification d’un message');

        // Redirection avec un message de succès
        return redirect()->route('dashboardAdminMessage')->with('success', 'Message mis à jour avec succès.');
    }

    /**
     * Supprime un message spécifique de la base de données.
     *
     * @param Request $request Requête HTTP contenant les données du client.
     * @param mixed $id Identifiant unique du message à supprimer.
     * @return \Illuminate\Http\RedirectResponse Une redirection vers le tableau de bord de l'administrateur
     *                              accompagnée d'un message de succès.
     *
     * Cette méthode permet de retrouver et de supprimer un message en se basant sur son identifiant.
     * Si l'identifiant ne correspond à aucun message, une exception sera levée.
     * Une fois le message supprimé, une entrée sera ajoutée dans les logs pour suivre l'opération.
     * Enfin, l'utilisateur est redirigé vers le tableau de bord avec un retour indiquant le succès
     * de l’opération.
     */
    public function SupprimerMessage(Request $request, $id)
    {
        // Recherche du message à supprimer dans la base de données
        $message = $this->message->findOrFail($id);

        // Suppression du message de la base de données
        $message->delete();

        // Enregistrement dans les logs d'application
        Log::info('Message ' . $message->id . ' supprimé avec succès');

        // Enregistrement dans les logs personnalisés avec l'email de l'utilisateur connecté
        Logs::ecrireLog($request->session()->get('email'), 'Suppression d’un message');

        // Redirection vers le tableau de bord de l'administrateur avec un message de succès
        return redirect()->route('dashboardAdminMessage')->with('success', 'Message supprimé avec succès.');
    }

    /**
     * Affiche tous les messages dans le tableau de bord de l'administrateur.
     *
     * @return \Illuminate\View\View Retourne la vue contenant la liste de tous les messages.
     *
     * Cette méthode récupère tous les messages disponibles dans la base de données
     * et les affiche dans une vue dédiée au tableau de bord administratif des messages.
     */
    public function afficherAllMessage()
    {
        // Récupération de tous les messages
        $messages = $this->message->all();

        // Retourne la vue avec les messages
        return view('Admin.dashboardAdminMessage', compact('messages'));
    }

    /**
     * Rafraîchit le QR Code d'une entreprise et met à jour les informations correspondantes.
     *
     * @param int $id Identifiant du compte auquel l'entreprise est associée.
     * @return \Illuminate\Http\RedirectResponse Redirige vers le tableau de bord de l'administrateur après l'opération.
     *
     * Cette méthode trouve le compte lié à l'identifiant donné, puis récupère l'entreprise associée à ce compte.
     * Si une entreprise est trouvée, elle régénère le QR Code, met à jour le lien vers ce dernier et enregistre
     * les modifications. Enfin, elle consigne le processus dans les journaux.
     */
    public function refreshQrCode($id)
    {
        // Recherche du compte associé à l'identifiant fourni
        $compte = $this->compte->find($id);
        if ($compte) {
            // Recherche de l'entreprise associée à ce compte
            $carte = $this->carte->where('idCompte', $compte->idCompte)->first();
            if ($carte) {
                // Remplacement des espaces par des underscores dans le nom de l'entreprise
                $nomEntrepriseFormatte = str_replace(' ', '_', $carte->nomEntreprise);

                // Génération du QR Code avec les informations du compte et du nom d'entreprise formaté
                $compte->QrCode($compte->idCompte, $nomEntrepriseFormatte);

                // Mise à jour du lien vers le QR Code dans l'entreprise
                $carte->lienQr = "/entreprises/{$compte->idCompte}/QR_Codes/QR_Code.svg";
                $carte->save();

                // Enregistrement des informations dans les logs
                Log::info('QR Code for ' . $nomEntrepriseFormatte . ' refreshed');
                Logs::ecrireLog($compte->email, 'Rafraîchissement du QR Code');
            }
        }

        // Redirection vers le tableau de bord administratif après l'opération
        return redirect()->route('dashboardAdmin');
    }
}
