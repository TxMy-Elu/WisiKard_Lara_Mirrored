<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Custom_link;
use App\Models\Employer;
use App\Models\Logs;
use App\Models\Message;
use App\Models\Rediriger;
use App\Models\Social;
use App\Models\Vue;
use App\Models\Horaires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

class DashboardClient extends Controller
{
    /**
     * Affiche le tableau de bord client.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne la vue du tableau de bord client avec les données nécessaires ou redirige en cas d'erreur.
     *
     * Cette méthode récupère les informations du compte client connecté pour afficher son tableau de bord.
     * Si une erreur survient, l'utilisateur est redirigé et une erreur est enregistrée dans les logs.
     */
    public function afficherDashboardClient(Request $request)
    {
        try {
            // Récupération de l'utilisateur connecté
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Récupération de l'email ou null si le compte est introuvable
            Log::info('Chargement du tableau de bord client', ['email' => $emailUtilisateur]);

            $carte = Carte::where('idCompte', $idCompte)->first();
            $compte = Compte::where('idCompte', $idCompte)->first();

            // Vérification de l'existence du compte
            if (!$compte) {
                Log::warning('Aucun compte trouvé', ['email' => $emailUtilisateur]);
            }

            // Récupération du message global à afficher
            $message = Message::where('afficher', true)->orderBy('id', 'desc')->first();
            $messageContent = $message ? $message->message : 'Aucun message disponible';

            if ($carte) {
                // Formatage spécifique des données de la carte
                $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
                $horaires = $carte->horaires;
            } else {
                Log::warning('Aucune carte associée au compte.', ['email' => $emailUtilisateur]);
                $horaires = collect(); // Retourne une collection vide si aucune carte n'est trouvée
            }

            // Rend la vue avec les informations nécessaires
            return view('Client.dashboardClient', [
                'messageContent' => $messageContent,
                'carte' => $carte,
                'compte' => $compte,
                'couleur1' => $carte->couleur1 ?? null,
                'couleur2' => $carte->couleur2 ?? null,
                'titre' => $carte->titre ?? null,
                'description' => $carte->descriptif ?? null,
                'idTemplate' => $carte->idTemplate ?? null,
                'horaires' => $horaires,
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs avec un message d'enregistrement des logs
            Log::error('Erreur lors du chargement du tableau de bord client', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors du chargement du tableau de bord.']);
        }
    }

    /**
     * Ajoute ou met à jour les horaires pour chaque jour de la semaine.
     *
     * @param Request $request L'objet de requête HTTP contenant les informations des horaires.
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur avec un message de succès ou d'erreur.
     *
     * Cette méthode récupère les horaires fournis dans la requête pour un utilisateur connecté,
     * traite les données et les enregistre dans la base de données. En cas d'erreur (par exemple, aucune carte associée),
     * elle renvoie un message d'erreur et effectue une redirection.
     */
    public function updateHoraires(Request $request)
    {
        // Identifier l'utilisateur connecté et récupérer les informations
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Récupération de l'email ou null si introuvable
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification de l'existence d'une carte associée à l'utilisateur
        if (!$carte) {
            Log::warning('Carte non trouvée pour mise à jour des horaires', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Liste des jours de la semaine à traiter
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        foreach ($jours as $jour) {
            // Extraction des horaires depuis la requête pour chaque jour
            $ouvertureMatin = $request->input($jour . '_ouverture_matin');
            $fermetureMatin = $request->input($jour . '_fermeture_matin');
            $ouvertureApresMidi = $request->input($jour . '_ouverture_aprmidi');
            $fermetureApresMidi = $request->input($jour . '_fermeture_aprmidi');

            // Mise à jour ou création de l'horaire pour le jour spécifique
            $horaire = Horaires::updateOrCreate(
                ['idCarte' => $carte->idCarte, 'jour' => $jour],
                [
                    'ouverture_matin' => $ouvertureMatin,
                    'fermeture_matin' => $fermetureMatin,
                    'ouverture_aprmidi' => $ouvertureApresMidi,
                    'fermeture_aprmidi' => $fermetureApresMidi
                ]
            );
        }

        // Enregistrement des logs en cas de succès
        Log::info('Horaires mis à jour avec succès', ['email' => $emailUtilisateur]);
        Logs::ecrireLog($emailUtilisateur, "Modification Horaires");

        // Redirection avec un message de réussite
        return redirect()->back()->with('success', 'Horaires mis à jour avec succès.');
    }

    /**
     * Formate un numéro de téléphone en ajoutant des points après chaque groupe de deux chiffres.
     *
     * @param string $phoneNumber Le numéro de téléphone brut.
     * @return string Le numéro de téléphone formaté.
     *
     * Cette méthode utilise une expression régulière pour diviser un numéro en groupes de deux chiffres
     * et insère un point entre les groupes.
     */
    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

    /**
     * Affiche les informations des employés associés à un compte client.
     *
     * @param Request $request L'objet de requête HTTP contenant les filtres de recherche.
     * @return \Illuminate\View\View Retourne la vue contenant les détails des employés filtrés ou l'ensemble des employés.
     *
     * Cette méthode récupère les employés associés au compte connecté, effectue une recherche facultative
     * basée sur le nom, le prénom ou la fonction des employés, et affiche les résultats.
     * Si aucun résultat n'est trouvé lors d'une recherche, elle renvoie un message d'erreur.
     */
    public function employer(Request $request)
    {
        // Récupération de l'utilisateur connecté et de ses informations
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email, ou null si non trouvé

        $compte = Compte::where('idCompte', $idCompte)->first();
        $search = $request->input('search'); // Recherche utilisateur facultative

        // Récupération des employés associés au compte
        $employes = Employer::with('carte')
            ->join('carte', 'employer.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $idCompte)
            ->when($search, function ($query, $search) {
                return $query->where('employer.nom', 'like', "%{$search}%")
                    ->orWhere('employer.prenom', 'like', "%{$search}%")
                    ->orWhere('employer.fonction', 'like', "%{$search}%");
            })
            ->select('employer.*')
            ->get();

        // Vérification s'il y a des résultats pour la recherche
        if ($employes->isEmpty() && !empty($search)) {
            return view('Client.dashboardClientEmploye', [
                'employes' => $employes,
                'search' => $search,
                'error' => 'Aucun résultat trouvé pour votre recherche.'
            ]);
        }

        // Retourne la vue des employés avec les informations nécessaires
        return view('Client.dashboardClientEmploye', [
            'employes' => $employes,
            'search' => $search,
            'compte' => $compte
        ]);
    }

    /**
     * Supprime un employé du système.
     *
     * @param int $id L'ID de l'employé à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur avec un message de succès ou d'erreur.
     *
     * Cette méthode supprime un employé en fonction de son ID, enregistre un log de l'action,
     * et redirige l'utilisateur vers l'écran des employés avec un message approprié.
     * Si une erreur se produit (par exemple, ID introuvable), un message d'erreur est affiché.
     */
    public function destroy($id)
    {
        try {
            // Log de tentative de suppression
            Log::info('Tentative de suppression de l\'employé', ['idEmploye' => $id]);

            // Recherche de l'employé
            $employer = Employer::findOrFail($id);

            // Récupération de la carte associée avant suppression
            $idCarte = $employer->idCarte;
            $employer->delete();

            // Enregistrement d'un log utilisateur si un compte est associé
            $compte = Compte::find($idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                Logs::ecrireLog($emailUtilisateur, "Suppression Employé");
            }

            // Confirmation de la suppression
            Log::info('Employé supprimé avec succès', ['idEmploye' => $id]);
            return redirect()->route('dashboardClientEmploye', ['idCarte' => $idCarte])->with('success', 'L\'employé a été supprimé avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs et enregistrement des logs
            Log::error('Erreur lors de la suppression de l\'employé', ['idEmploye' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de l\'employé.']);
        }
    }

    /**
     * Affiche les informations sur les réseaux sociaux et les liens personnalisés pour un compte client.
     *
     * @return \Illuminate\View\View Retourne la vue contenant les informations des réseaux sociaux et des liens personnalisés.
     *
     * Cette méthode récupère les réseaux sociaux disponibles, les réseaux sociaux activés,
     * ainsi que les liens personnalisés activés pour une carte spécifique, et affiche le tout
     * dans une vue dédiée.
     */
    public function social()
    {
        // Récupération des informations du compte connecté et de la carte associée
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email de l'utilisateur connecté ou null si non trouvé
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;
        $compte = Compte::where('idCompte', $idCompte)->first();

        // Récupération de tous les réseaux sociaux et des réseaux activés pour la carte
        $allSocial = Social::all();
        $activatedSocial = Rediriger::where('idCarte', $idCarte)
            ->join('social', 'rediriger.idSocial', '=', 'social.idSocial')
            ->select('social.idSocial', 'rediriger.activer', 'rediriger.lien')
            ->get();

        $activatedSocialArray = [];
        foreach ($activatedSocial as $social) {
            $activatedSocialArray[$social->idSocial] = [
                'activer' => $social->activer,
                'lien' => $social->lien
            ];
        }

        // Récupération des informations des liens personnalisés pour la carte
        $custom = Custom_link::where('idCarte', $idCarte)->get();
        $activatedCustomLinks = Custom_link::where('idCarte', $idCarte)
            ->select('id_link', 'activer', 'lien')
            ->get();

        $activatedCustomLinksArray = [];
        foreach ($activatedCustomLinks as $link) {
            $activatedCustomLinksArray[$link->id_link] = [
                'activer' => $link->activer,
                'lien' => $link->lien
            ];
        }

        // Affichage des données dans la vue appropriée
        return view('Client.dashboardClientSocial', [
            'allSocial' => $allSocial,
            'activatedSocial' => $activatedSocialArray,
            'idCarte' => $idCarte,
            'custom' => $custom,
            'activatedCustomLinks' => $activatedCustomLinksArray,
            'compte' => $compte
        ]);
    }

    /**
     * Met à jour ou crée un lien de réseau social pour une carte spécifique.
     *
     * @param Request $request L'objet de requête HTTP avec les données à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message confirmant la mise à jour ou un message d'erreur en cas d'échec.
     *
     * Cette méthode permet de mettre à jour un lien de réseau social existant ou d'en créer un nouveau
     * si le lien pour l'association réseau-carte n'existe pas encore. Elle valide les données reçues,
     * journalise les actions, et gère les erreurs potentielles.
     */
    public function updateSocialLink(Request $request)
    {
        try {
            // Validation des données reçues
            $request->validate([
                'idSocial' => 'required|integer',
                'idCarte' => 'required|integer',
                'lien' => 'required|url'
            ]);

            // Récupération des informations du compte connecté
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email utilisateur ou null si non trouvé

            // Recherche du lien social existant pour mise à jour
            $rediriger = Rediriger::where('idSocial', $request->idSocial)
                ->where('idCarte', $request->idCarte)
                ->first();

            if ($rediriger) {
                // Mise à jour du lien social existant
                Log::info('Mise à jour du lien social existant', [
                    'idSocial' => $request->idSocial,
                    'idCarte' => $request->idCarte,
                    'email' => $emailUtilisateur
                ]);

                $rediriger->lien = $request->lien;
                $rediriger->activer = $request->has('activer') ? 1 : 0; // Activer ou désactiver selon la sélection
                $rediriger->save();

                Logs::ecrireLog($emailUtilisateur, "Modification Lien Social");
            } else {
                // Création d'un nouveau lien social
                Log::info('Création d\'un nouveau lien social', [
                    'idSocial' => $request->idSocial,
                    'idCarte' => $request->idCarte,
                    'email' => $emailUtilisateur
                ]);

                Rediriger::create([
                    'idSocial' => $request->idSocial,
                    'idCarte' => $request->idCarte,
                    'lien' => $request->lien,
                    'activer' => 1 // Toujours actif par défaut
                ]);

                Logs::ecrireLog($emailUtilisateur, "Ajout Lien Social");
            }

            // Redirection avec confirmation de réussite
            return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs et journalisation
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email utilisateur ou null si non trouvé
            Log::error('Erreur lors de la mise à jour du lien social', [
                'error' => $e->getMessage(),
                'email' => $emailUtilisateur
            ]);

            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du lien social.']);
        }
    }

    /**
     * Affiche les statistiques des vues pour un compte client.
     *
     * @param Request $request L'objet de requête HTTP contenant les paramètres de filtre comme l'année, la semaine ou le mois.
     * @return \Illuminate\View\View Retourne la vue affichant les statistiques sous forme de graphiques et de données.
     *
     * Cette méthode collecte les données des vues en fonction des filtres (année, semaine ou mois), les organise pour une représentation graphique,
     * et les transmet à la vue dédiée au tableau de bord des statistiques.
     */
    public function statistique(Request $request)
    {
        // Récupération des informations du compte connecté
        $session = session('connexion');
        $emailUtilisateur = Compte::find($session)->email ?? null; // Email ou null si non trouvé
        $idCompte = session('connexion');
        $compte = Compte::where('idCompte', $idCompte)->first();

        // Récupération des filtres ou valeurs par défaut pour l'année, la semaine et le mois
        $year = $request->query('year', date('Y'));
        $selectedWeek = $request->input('week', date('W'));
        $selectedMonth = $request->input('month', date('n'));

        // Identification de la carte associée
        $idCarte = Carte::where('idCompte', $session)->first()->idCarte;

        // Statistiques des vues par mois pour l'année sélectionnée
        $yearlyViews = Vue::selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $yearlyData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Nombre de vues par mois',
                    'backgroundColor' => 'rgba(153, 27, 27, 0.2)',
                    'borderColor' => 'rgba(153, 27, 27, 1)',
                    'borderWidth' => 1,
                    'data' => array_values(array_replace(array_fill(1, 12, 0), $yearlyViews)),
                ],
            ],
        ];

        // Statistiques des vues par employé pour l'année sélectionnée
        $employerViews = Vue::selectRaw('employer.nom as nom, COUNT(*) as count')
            ->join('employer', 'vue.idEmp', '=', 'employer.idEmp')
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->whereYear('date', $year)
            ->where('carte.idCarte', $idCarte)
            ->groupBy('nom')
            ->pluck('count', 'nom')
            ->toArray();

        // Génération aléatoire de couleurs pour les graphiques des employés
        $colors = [];
        foreach ($employerViews as $key => $value) {
            do {
                $r = mt_rand(0, 255);
                $g = mt_rand(0, 255);
                $b = mt_rand(0, 255);
            } while (($r > 200 && $g < 100 && $b > 200) || ($r < 100 && $g > 200 && $b < 100));
            $colors[] = sprintf('rgba(%d, %d, %d, 0.755)', $r, $g, $b);
        }

        $employerData = [
            'labels' => array_keys($employerViews),
            'datasets' => [
                [
                    'label' => 'Nombre de vues par employé',
                    'backgroundColor' => $colors,
                    'borderColor' => 'rgba(0, 0, 0, 0.1)',
                    'borderWidth' => 1,
                    'data' => array_values($employerViews),
                ],
            ],
        ];

        // Total des vues de la carte pour l'année sélectionnée
        $totalViewsCard = Vue::whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->count();

        // Statistiques des vues hebdomadaires pour l'année sélectionnée
        $weeklyViewsQuery = Vue::selectRaw('WEEK(date, 1) as week, COUNT(*) as count')
            ->whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->groupBy('week');

        $weeklyViews = $weeklyViewsQuery->pluck('count', 'week')->toArray();

        // Récupération des années possibles pour la sélection
        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

        // Statistiques des vues mensuelles pour une comparaison
        $monthlyViews = Vue::selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Nombre de vues par mois',
                    'backgroundColor' => 'rgba(153, 27, 27, 0.2)',
                    'borderColor' => 'rgba(153, 27, 27, 1)',
                    'borderWidth' => 1,
                    'data' => array_values(array_replace(array_fill(1, 12, 0), $monthlyViews)),
                ],
            ],
        ];

        // Transmission des données à la vue dédiée aux statistiques
        return view('Client.dashboardClientStatistique', compact(
            'yearlyData',
            'years',
            'selectedYear',
            'totalViewsCard',
            'weeklyViews',
            'selectedWeek',
            'selectedMonth',
            'employerData',
            'monthlyData',
            'compte'
        ));
    }

    /**
     * Change les couleurs du QR Code associé à une carte.
     *
     * @param Request $request L'objet de requête HTTP contenant les nouvelles couleurs.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur en fonction du résultat de l'opération.
     *
     * Cette méthode permet de mettre à jour les couleurs du QR Code (couleur 1 et couleur 2)
     * associées à une carte spécifique. Elle effectue une validation des données reçues,
     * journalise les changements et gère les erreurs éventuelles.
     */
    public function updateColor(Request $request)
    {
        try {
            // Validation des couleurs envoyées dans la requête
            $request->validate([
                'couleur1' => 'required|string|max:7',
                'couleur2' => 'required|string|max:7',
            ]);

            // Récupération des informations de l'utilisateur connecté
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email utilisateur ou null si non trouvé
            $carte = Carte::where('idCompte', $idCompte)->first();

            // Vérification si la carte existe
            if (!$carte) {
                Log::warning('Carte non trouvée pour mise à jour des couleurs', ['email' => $emailUtilisateur]);
                return redirect()->back()->withErrors(['error' => 'Carte non trouvée.']);
            }

            // Sauvegarde des anciennes couleurs pour audit
            $oldColors = [
                'couleur1' => $carte->couleur1,
                'couleur2' => $carte->couleur2,
            ];

            // Mise à jour des nouvelles couleurs
            $carte->couleur1 = $request->couleur1;
            $carte->couleur2 = $request->couleur2;
            $carte->save();

            // Log des changements de couleurs
            Log::info('Couleurs mises à jour avec succès', [
                'email' => $emailUtilisateur,
                'oldColors' => $oldColors,
                'newColors' => ['couleur1' => $request->couleur1, 'couleur2' => $request->couleur2],
            ]);

            // Journalisation de l'action pour suivi
            Logs::ecrireLog($emailUtilisateur, "Modification Couleurs");

            // Mise à jour du QR Code avec les nouvelles couleurs
            Compte::QrCode($idCompte, $carte->nomEntreprise);

            // Redirection avec message de réussite
            return redirect()->back()->with('success', 'Couleurs mises à jour avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs et enregistrement dans le journal
            Log::error('Erreur lors de la mise à jour des couleurs', [
                'email' => $emailUtilisateur ?? null,
                'error' => $e->getMessage(),
            ]);

            // Redirection avec message d'erreur
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des couleurs.']);
        }
    }

    /**
     * Télécharge le QR Code de l'entreprise en couleur.
     *
     * @return \Illuminate\Http\Response Retourne le téléchargement du QR Code en couleur ou un message d'erreur si le fichier ou la carte n'est pas trouvé.
     *
     * Cette méthode gère le téléchargement du QR Code couleur associé à une entreprise.
     * Si le fichier ou la carte n'existe pas, une erreur est retournée à l'utilisateur.
     */
    public function downloadQrCodesColor()
    {
        // Récupération des informations de l'utilisateur connecté
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email de l'utilisateur ou null si non trouvé
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::info('Carte non trouvée pour idCompte: ' . $idCompte);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Construction du chemin d'accès au QR Code
        $entrepriseName = $carte->nomEntreprise;
        $folderName = "{$idCompte}_{$entrepriseName}";
        $qrCodesPath = public_path("entreprises/{$folderName}/QR_Codes/QR_Code.svg");

        // Vérification si le fichier QR Code existe
        if (!File::exists($qrCodesPath)) {
            Log::info('QR Code non trouvé pour idCompte: ' . $idCompte);
            return redirect()->back()->with('error', 'Aucun QR Code trouvé.');
        }

        // Journalisation de l'action de téléchargement
        Logs::ecrireLog($emailUtilisateur, "Téléchargement QR Code Couleur");
        Log::info('QR Code téléchargé pour idCompte: ' . $idCompte);

        // Téléchargement du fichier QR Code
        return response()->download($qrCodesPath, 'QR_Code_Couleur.svg');
    }

    /**
     * Télécharge le QR Code de l'entreprise en noir et blanc.
     *
     * @return \Illuminate\Http\Response Retourne le QR Code généré en noir et blanc pour téléchargement.
     *
     * Cette méthode génère un QR Code en fonction d'une URL personnalisée, puis retourne ce QR Code en tant que fichier téléchargeable.
     * Elle journalise également l'action de téléchargement.
     */
    public function downloadQrCodes()
    {
        // Récupération des informations de l'utilisateur connecté
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? null; // Email de l'utilisateur ou null si non trouvé

        // Génération de l'URL du QR Code avec les paramètres requis
        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg&text=127.0.0.1:9000/Templates?idCompte=" . $idCompte;

        // Journalisation si les informations d'utilisateur sont valides
        if (!empty($idCompte) && !empty($emailUtilisateur)) {
            Log::info('QR Code téléchargé pour idCompte: ' . $idCompte);
            Logs::ecrireLog($emailUtilisateur, "Téléchargement QR Code");
        }

        // Téléchargement du QR Code généré
        return response()->streamDownload(function () use ($url) {
            echo file_get_contents($url);
        }, 'QR_Code.svg');
    }

    /**
     * Affiche le tableau de bord client en PDF.
     *
     * @return \Illuminate\View\View Renvoie la vue "dashboardClientPDF" avec les données nécessaires issues des informations du compte, de la carte, des images et des vidéos.
     *
     * Cette méthode récupère les informations associées au compte et à la carte d'un utilisateur,
     * notamment les images, les vidéos YouTube et le logo de l'entreprise, puis les transmet à la vue
     * pour le rendu en PDF. Elle journalise également les étapes du processus.
     */
    public function afficherDashboardClientPDF()
    {
        // Récupération des informations du compte en session
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Email inconnu'; // Récupère l'email ou retourne par défaut "Email inconnu"
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::warning("Carte introuvable pour l'utilisateur : {$emailUtilisateur}");
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Construction du nom de dossier en assurant un format valide
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";

        // Récupération des images associées
        $imagesPath = public_path("entreprises/{$folderName}/images");
        $images = [];
        if (File::exists($imagesPath)) {
            $images = File::files($imagesPath);
            $images = array_map(function ($file) {
                return $file->getFilename();
            }, $images);
        }

        // Récupération des URLs des vidéos YouTube depuis un fichier JSON spécifique
        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        // Détection du logo avec différents formats compatibles
        $logoPath = '';
        $formats = ['svg', 'png', 'jpg', 'jpeg']; // Liste des formats pris en charge
        foreach ($formats as $format) {
            $path = public_path("entreprises/{$folderName}/logos/logo.{$format}");
            if (File::exists($path)) {
                $logoPath = asset("entreprises/{$folderName}/logos/logo.{$format}");
                break;
            }
        }

        // Journalisation des informations extraites pour le tableau de bord
        Log::info("Affichage du tableau de bord pour l'utilisateur : {$emailUtilisateur}", [
            'email' => $emailUtilisateur,
            'imagesCount' => count($images),
            'youtubeUrlsCount' => count($youtubeUrls),
            'logoPath' => $logoPath,
        ]);

        // Retour de la vue avec les données nécessaires
        return view('Client.dashboardClientPDF', compact('carte', 'images', 'folderName', 'idCompte', 'youtubeUrls', 'logoPath', 'compte'));
    }

    /**
     * Télécharge le logo de l'entreprise et enregistre son chemin dans la base de données tout en conservant la casse.
     *
     * @param Request $request L'objet de requête HTTP contenant le fichier logo.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     *
     * Cette méthode permet de télécharger un logo pour une entreprise, de le sauvegarder sous un chemin spécifique
     * en conservant la casse du fichier et d'enregistrer cette information dans la base de données.
     * Elle journalise également chaque étape importante (succès, erreur, avertissement).
     */
    public function uploadLogo(Request $request)
    {
        // Récupération des informations de l'utilisateur connecté
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur anonyme';

        // Vérification si la carte existe
        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement du logo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Construction du chemin d'accès pour sauvegarder les logos
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise); // Formater le nom de l'entreprise
        $folderName = "{$idCompte}_{$entrepriseName}";
        $logoPath = public_path("entreprises/{$folderName}/logos");

        // Création des répertoires de manière récursive si nécessaires
        if (!File::exists($logoPath)) {
            try {
                File::makeDirectory($logoPath, 0755, true); // Création des répertoires avec droits nécessaires
            } catch (\Exception $e) {
                Log::error("Erreur lors de la création du répertoire {$logoPath}", [
                    'email' => $emailUtilisateur,
                    'exception' => $e->getMessage(),
                ]);
                return redirect()->back()->with('error', 'Impossible de sauvegarder le logo.');
            }
        }

        // Gestion du fichier uploadé
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileType = $file->getClientOriginalExtension(); // Conserve l'extension avec sa casse originale
            $mimeType = $file->getMimeType();

            // Vérification des extensions et MIME types autorisés
            if (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png', 'svg']) && strpos($mimeType, 'image/') === 0) {
                // Suppression de tout ancien logo existant
                if (File::exists($logoPath)) {
                    $existingLogos = File::files($logoPath);
                    foreach ($existingLogos as $logoFile) {
                        File::delete($logoFile->getPathname());
                    }
                }

                // Sauvegarde du nouveau logo avec l'extension originale
                $fileName = "logo.{$fileType}"; // Fichier nommé "logo" avec extension préservée
                $file->move($logoPath, $fileName);

                // Mise à jour dans la base de données avec le chemin du nouveau logo
                $carte->imgLogo = "entreprises/{$folderName}/logos/{$fileName}";
                $carte->save();

                // Log de réussite
                Log::info('Logo téléchargé avec succès', ['email' => $emailUtilisateur, 'fileName' => $fileName]);
                Logs::ecrireLog($emailUtilisateur, "Téléchargement Logo");

                // Redirection avec message de succès
                return redirect()->route('dashboardClientPDF')->with('success', 'Logo téléchargé avec succès.');
            } else {
                // Log de fichier non valide
                Log::warning('Type de fichier ou extension non valide pour le logo', ['email' => $emailUtilisateur]);
                return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
            }
        }

        // Redirection si aucun fichier n’a été téléchargé
        return redirect()->back()->with('error', 'Aucun fichier téléchargé.');
    }

    /**
     * Enregistre l'URL de prise de rendez-vous dans la base de données.
     *
     * @param Request $request Requête HTTP contenant l'URL de prise de rendez-vous.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     *
     * Cette méthode permet de valider et d'enregistrer une URL pour la prise de rendez-vous associée à une carte
     * dans la base de données. Elle journalise les étapes importantes et retourne un message selon le résultat.
     */
    public function urlsrdv(Request $request)
    {
        // Récupération des informations du compte en session
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur anonyme';

        // Vérification de l'existence de la carte
        if (!$carte) {
            Log::warning('Carte non trouvée pour l\'enregistrement de l\'URL RDV', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Vérification si une URL de RDV est présente dans la requête
        if ($request->filled('rdv_url')) {
            $rdvUrl = $request->input('rdv_url');

            // Validation de l'URL (doit commencer par http ou https)
            if (preg_match('/^https?:\/\//', $rdvUrl)) {
                // Sauvegarde de l'URL dans la base de données
                $carte->lienCommande = $rdvUrl;
                $carte->save();

                // Journalisation de la réussite
                Log::info('URL RDV enregistrée avec succès', ['email' => $emailUtilisateur, 'rdvUrl' => $rdvUrl]);
                Logs::ecrireLog($emailUtilisateur, "Enregistrement URL RDV");

                return redirect()->route('dashboardClientPDF')->with('success', 'URL RDV enregistrée avec succès.');
            } else {
                // Journalisation d'une URL non valide
                Log::warning('URL RDV non valide', ['email' => $emailUtilisateur, 'rdvUrl' => $rdvUrl]);
                return redirect()->back()->with('error', 'L\'URL doit commencer par http ou https.');
            }
        }

        // Journalisation dans le cas où aucune URL n'est fournie
        Log::warning('Aucune URL fournie pour RDV', ['email' => $emailUtilisateur]);
        return redirect()->back()->with('error', 'Aucune URL fournie.');
    }

    /**
     * Télécharge et enregistre les vidéos YouTube dans un fichier JSON.
     *
     * @param Request $request Requête HTTP contenant l'URL YouTube.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     *
     * Cette méthode permet de valider une URL YouTube et de l'ajouter à un fichier JSON
     * spécifique correspondant aux vidéos YouTube de l'entreprise. Chaque étape est journalisée.
     */
    public function uploadYouTubeVideo(Request $request)
    {
        // Récupération des informations de l'utilisateur connecté
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur anonyme';

        // Vérification si la carte existe
        if (!$carte) {
            Log::warning('Carte non trouvée pour l\'ajout de vidéo YouTube', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Construction du chemin pour enregistrer les vidéos
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";
        $videosPath = public_path("entreprises/{$folderName}/videos");

        // Vérification si une URL YouTube est fournie
        if ($request->has('youtube_url')) {
            $youtubeUrl = $request->input('youtube_url');

            // Validation de l'URL YouTube avec une expression régulière
            if (preg_match('/^https:\/\/(www\.)?youtube\.com\/watch\?v=[A-Za-z0-9_-]+$/', $youtubeUrl)) {
                // Création du dossier de vidéos s'il n'existe pas
                if (!File::exists($videosPath)) {
                    File::makeDirectory($videosPath, 0755, true);
                }

                // Chargement ou création du fichier JSON contenant les vidéos
                $videosFile = $videosPath . '/videos.json';
                $videosData = [];

                if (File::exists($videosFile)) {
                    $videosData = json_decode(File::get($videosFile), true);
                }

                // Ajouter la nouvelle URL si elle n'existe pas déjà
                if (!in_array($youtubeUrl, $videosData)) {
                    $videosData[] = $youtubeUrl;
                    File::put($videosFile, json_encode($videosData, JSON_PRETTY_PRINT));

                    // Journaliser l'ajout de la vidéo YouTube
                    Log::info('URL YouTube ajoutée avec succès', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                    Logs::ecrireLog($emailUtilisateur, "Ajout URL YouTube");

                    return redirect()->route('dashboardClientPDF')->with('success', 'URL YouTube enregistrée avec succès.');
                } else {
                    // Si l'URL existe déjà, avertir l'utilisateur
                    Log::warning('URL YouTube déjà existante', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                    return redirect()->back()->with('warning', 'Cette URL YouTube existe déjà.');
                }
            } else {
                // Journaliser une tentative avec une URL non valide
                Log::warning('URL YouTube non valide', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                return redirect()->back()->with('error', 'URL YouTube non valide.');
            }
        }

        // Si aucune URL n'a été fournie dans la requête
        Log::warning('Aucune URL YouTube fournie', ['email' => $emailUtilisateur]);
        return redirect()->back()->with('error', 'Aucune URL YouTube fournie.');
    }

    /**
     * Supprime les vidéos YouTube d'un fichier JSON.
     *
     * @param string $youtubeUrl L'URL YouTube à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     *
     * Cette méthode permet de supprimer une vidéo YouTube spécifique enregistrée dans un fichier JSON.
     * Chaque action est journalisée pour garantir la traçabilité.
     */
    public function deleteYouTubeVideo($youtubeUrl)
    {
        // Récupération des informations de l'utilisateur connecté
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu'; // Gestion en cas de données manquantes
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::warning("Carte introuvable pour la suppression d'une vidéo YouTube", ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Construction du chemin vers le fichier JSON contenant les vidéos
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";
        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");

        // Vérifier si le fichier JSON contenant les vidéos existe
        if (File::exists($videosPath)) {
            try {
                // Charger les données existantes
                $videosData = json_decode(File::get($videosPath), true);

                // Vérifier si l'URL YouTube est dans la liste
                if (($key = array_search($youtubeUrl, $videosData)) !== false) {
                    unset($videosData[$key]); // Supprimer l'URL spécifique
                    $videosData = array_values($videosData); // Réindexer le tableau

                    // Mettre à jour le fichier JSON
                    File::put($videosPath, json_encode($videosData, JSON_PRETTY_PRINT));

                    Log::info('Vidéo YouTube supprimée avec succès', [
                        'email' => $emailUtilisateur,
                        'youtubeUrl' => $youtubeUrl,
                    ]);
                    Logs::ecrireLog($emailUtilisateur, "Suppression URL YouTube");

                    return redirect()->back()->with('success', 'Vidéo YouTube supprimée avec succès.');
                } else {
                    // Log si l'URL YouTube n'est pas trouvée
                    Log::warning('URL YouTube non trouvée dans le fichier JSON', [
                        'email' => $emailUtilisateur,
                        'youtubeUrl' => $youtubeUrl,
                    ]);
                    return redirect()->back()->with('error', 'URL YouTube non trouvée.');
                }
            } catch (\Exception $e) {
                // Log en cas d'erreur lors de la suppression
                Log::error("Erreur lors de la suppression de la vidéo YouTube", [
                    'email' => $emailUtilisateur,
                    'youtubeUrl' => $youtubeUrl,
                    'exception' => $e->getMessage(),
                ]);
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de la vidéo.');
            }
        } else {
            // Log si le fichier JSON n'existe pas
            Log::warning('Fichier JSON des vidéos YouTube introuvable', [
                'email' => $emailUtilisateur,
                'youtubeUrl' => $youtubeUrl,
                'videosPath' => $videosPath,
            ]);
            return redirect()->back()->with('error', 'Fichier JSON introuvable.');
        }
    }

    /**
     * Supprime une image du slider d'une entreprise donnée.
     *
     * Cette méthode permet de supprimer une image spécifique du répertoire "slider" associé
     * à une entreprise, en se basant sur le fichier envoyé dans la requête HTTP. Les chemins
     * et actions sont validés et logués.
     *
     * Fonctionnement détaillé :
     * - Récupère l'ID de compte de l'utilisateur connecté depuis la session.
     * - Trouve la carte correspondante (profil de l'entreprise).
     * - Valide l'existence du répertoire "slider" contenant les images.
     * - Vérifie si le fichier à supprimer existe dans ce répertoire avant d'effectuer l'opération.
     * - Logue chaque étape et retourne un message d'erreur ou de succès en conséquence.
     *
     * @param Request $request L'objet de requête HTTP contenant les données nécessaires (nom du fichier).
     *                         - `filename` : Nom du fichier image à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur vers la page précédente avec un message
     *                                            indiquant le succès ou l'erreur de l'opération.
     *
     * Logs utilisés :
     * - Warnings en cas de carte ou fichier introuvable.
     * - Infos pour les étapes clés comme la réussite de la suppression.
     * - Erreurs en cas de problèmes techniques (exception ou répertoire manquant).
     */
    public function deleteSliderImage(Request $request)
    {
        $idCompte = session('connexion'); // Récupérer l'ID du compte
        $carte = Carte::where('idCompte', $idCompte)->first();
    
        // Vérification si la carte est introuvable
        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression d\'image du slider');
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }
    
        Log::info('Carte trouvée : ' . $carte->nomEntreprise);
    
        // Construire le nom du répertoire tout en conservant les majuscules
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";
        $sliderImagesPath = public_path("entreprises/{$folderName}/slider");
    
        Log::info('Chemin des images slider : ' . $sliderImagesPath);
    
        // Vérifier si le répertoire existe
        if (!File::exists($sliderImagesPath)) {
            Log::error('Répertoire slider inexistant : ' . $sliderImagesPath);
            return redirect()->back()->with('error', 'Aucune image trouvée.');
        }
    
        // Récupérer tous les fichiers présents dans le répertoire slider
        $sliderImages = File::files($sliderImagesPath);
        $sliderImages = array_map(function ($file) {
            return $file->getFilename(); // Retourner uniquement le nom des fichiers
        }, $sliderImages);

        // Récupérer le nom du fichier envoyé dans la requête
        $filename = $request->input('filename');
            Log::info('Nom du fichier demandé pour suppression : ' . $filename);
    
        // Vérifier si le fichier existe dans la liste des fichiers
        if (in_array($filename, $sliderImages)) {
            $filePath = "{$sliderImagesPath}/{$filename}";
            Log::info('Chemin complet du fichier à supprimer : ' . $filePath);

            // Vérification de l'existence réelle du fichier avant la suppression
            if (File::exists($filePath)) {
                try {
                    File::delete($filePath); // Supprimer le fichier correspondant

                    Log::info('Image de slider supprimée avec succès', [
                        'filename' => $filename
                    ]);
                    return redirect()->back()->with('success', 'Image de slider supprimée avec succès.');
                } catch (\Exception $e) {
                    // Enregistrer les erreurs rencontrées lors de la suppression
                    Log::error('Erreur lors de la suppression de l\'image de slider', [
                        'filename' => $filename,
                        'exception' => $e->getMessage()
                    ]);
                    return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'image.');
                }
            }

            Log::warning('Fichier trouvé dans la liste mais inexistant ou inaccessible : ' . $filePath);
            } else {
            // L'image n'est pas trouvée dans la liste
            Log::error('Image non trouvée dans la liste des fichiers du slider : ' . $filename);
        }
    
        return redirect()->back()->with('error', 'Image non trouvée.');
    }
    

    /**
     * Supprime un fichier PDF associé à une carte, ainsi que ses métadonnées dans la base de données.
     *
     * Cette méthode permet de supprimer physiquement le fichier PDF d'une entreprise depuis le système
     * de fichiers, ainsi que d'effacer le chemin et le nom du bouton PDF dans la base de données.
     *
     * Fonctionnement détaillé :
     * - Récupère l'ID de compte de l'utilisateur connecté depuis la session.
     * - Trouve la carte correspondante (les données de l'entreprise).
     * - Vérifie l'existence du fichier PDF sur le système de fichiers.
     * - Supprime le fichier du disque ainsi que les données `pdf` et `nomBtnPdf` dans la table associée.
     * - Retourne un message de succès si tout fonctionne, sinon un message d'erreur.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur vers la page précédente avec un
     *                                            message indiquant le succès ou l'échec de l'opération.
     *
     * Exceptions :
     * - Avertit si le fichier PDF est introuvable sur le disque.
     */
    public function deletePdf()
    {
        // Récupération de l'ID de compte de l'utilisateur connecté depuis la session
        $idCompte = session('connexion');

        // Recherche de la carte associée à cet ID de compte
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Obtention du chemin du fichier PDF à partir des données de la carte
        $filePath = $carte->pdf;

        // Vérification si le fichier PDF existe sur le système
        if (file_exists($filePath)) {
            // Suppression physique du fichier sur le disque
            unlink($filePath);

            // Réinitialisation des données associées au fichier PDF dans la base de données
            $carte->pdf = null;         // Suppression du chemin dans la base de données
            $carte->nomBtnPdf = null;   // Suppression du nom du bouton PDF
            $carte->save();             // Sauvegarde des modifications

            // Retour avec un message de succès
            return redirect()->back()->with('success', 'Fichier supprimé avec succès.');
        }

        // Retour avec un message d'erreur si le fichier est introuvable
        return redirect()->back()->with('error', 'Fichier introuvable.');
    }


    /**
     * Supprime le logo d'une entreprise, en recherchant dans différents formats d'image.
     *
     * Cette méthode permet de supprimer le logo d'une entreprise depuis le répertoire correspondant
     * dans le système de fichiers. Elle journalise toutes les étapes importantes, telles que la vérification
     * de la carte, la recherche du logo et la suppression avec succès.
     *
     * Fonctionnement détaillé :
     * - Récupère l'ID de compte de l'utilisateur connecté depuis la session.
     * - Trouve la carte correspondante (données de l'entreprise) pour obtenir le dossier du logo.
     * - Recherche le logo dans plusieurs formats possibles (`jpg`, `jpeg`, `png`, `svg`) au chemin indiqué.
     * - Supprime le premier fichier trouvé, journalise l'opération, et met à jour les logs personnalisés.
     * - Retourne un message d'erreur si aucun logo n'est trouvé ou si la carte est introuvable.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur avec un message de succès ou d'erreur
     *                                            en fonction du résultat de l'opération.
     *
     * Logs utilisés :
     * - Logs d'avertissement si une carte ou un logo est introuvable.
     * - Logs d'information pour toute suppression réussie incluant l'email et le chemin.
     * - Écriture de logs personnalisés (`Logs::ecrireLog()`) pour enregistrer les actions.
     */
    public function deleteLogo()
    {
        // Récupération de l'ID de compte depuis la session
        $idCompte = session('connexion');

        // Recherche du compte et de l'email associé
        $compte = Compte::find($idCompte);
        $emailUtilisateur = $compte ? $compte->email : 'Utilisateur inconnu'; // Gestion du cas où le compte est introuvable

        // Recherche de la carte associée à l'ID de compte
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression du logo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Construction du nom du dossier de l'entreprise
        $entrepriseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $carte->nomEntreprise); // Garde les caractères valides pour un nom de fichier
        $folderName = "{$idCompte}_{$entrepriseName}";

        // Formats possibles pour le fichier logo
        $logoFormats = ['jpg', 'jpeg', 'png', 'svg'];
        $logoDeleted = false; // Indicateur de suppression

        // Recherche et suppression du fichier logo
        foreach ($logoFormats as $format) {
            $logoPath = public_path("entreprises/{$folderName}/logos/logo.{$format}");

            if (File::exists($logoPath)) {
                // Suppression du logo
                File::delete($logoPath);
                $logoDeleted = true;

                // Journaux et logs personnalisés
                Log::info("Logo supprimé avec succès", ['email' => $emailUtilisateur, 'logoPath' => $logoPath]);
                Logs::ecrireLog($emailUtilisateur, "Suppression Logo");

                // Sortir de la boucle dès qu'un logo est supprimé
                break;
            }
        }

        // Si aucun logo n'a été trouvé et supprimé
        if (!$logoDeleted) {
            Log::warning('Logo non trouvé pour la suppression', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Logo non trouvé.');
        }

        // Succès : Redirection avec message
        return redirect()->back()->with('success', 'Logo supprimé avec succès.');
    }

    /**
     * Télécharge une image pour le slider d'une entreprise, en la stockant dans le répertoire prévu.
     *
     * Cette méthode permet de valider, traiter et enregistrer une image uploadée par l'utilisateur
     * dans le répertoire dédié à l'entreprise. Plusieurs étapes de vérification et journalisation
     * sont effectuées pour garantir un fonctionnement sécurisé et fiable.
     *
     * Fonctionnement détaillé :
     * - Récupère l'ID de compte de l'utilisateur connecté depuis la session.
     * - Valide l'existence de la carte (données de l'entreprise) associée à l'ID de compte.
     * - Valide le fichier image uploadé (taille max. 2MB, formats `.jpg`, `.jpeg`, `.png`).
     * - Crée un répertoire personnalisé pour stocker l'image si celui-ci n'existe pas.
     * - Enregistre l'image avec un nom unique.
     * - Journalise toutes les étapes importantes, y compris les erreurs ou réussites.
     *
     * @param Request $request L'objet de requête HTTP contenant les données nécessaires :
     *                         - `image` : Le fichier image à télécharger.
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur avec un message de succès ou d'erreur
     *                                            en fonction de l'opération.
     *
     * Logs utilisés :
     * - Logs d'erreur en cas de problème (compte introuvable, type MIME invalide, ou exception).
     * - Logs d'information en cas de succès (chemin et nom du fichier téléchargé, création de répertoire, etc.).
     * - Logs personnalisés avec `Logs::ecrireLog` pour enregistrer les opérations en base de données.
     *
     * Restrictions :
     * - Taille maximale autorisée : 2MB.
     * - Formats autorisés : `.jpg`, `.jpeg`, `.png`.
     */
public function uploadSlider(Request $request)
{
    // Récupérer l'ID de compte depuis la session
    $idCompte = session('connexion');

    // Trouver la carte associée au compte
    $carte = Carte::where('idCompte', $idCompte)->first();

    // Vérifier si la carte existe
    if (!$carte) {
        Log::error("Échec : Aucun compte trouvé pour idCompte : {$idCompte}");
        return response()->json(['error' => 'Compte introuvable.'], 404);
    }

    // Construire le chemin de destination pour le slider
    $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
    $destinationPath = "entreprises/{$idCompte}_{$entrepriseName}/slider";

    try {
        // Valider les données de la requête
        $request->validate([
            'image.*' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Taille max : 2MB et formats appropriés
        ]);

        // Récupérer les fichiers téléchargés
        $files = $request->file('image');
        Log::info('Fichiers téléchargés :', ['files' => $files]);

        // Vérifier si des fichiers ont été téléchargés
        if (!$files || !is_array($files)) {
            Log::error("Aucun fichier téléchargé pour le compte : {$idCompte}");
            return response()->json(['error' => 'Aucun fichier téléchargé.'], 400);
        }

        // Vérifier et créer le répertoire de destination si nécessaire
        $fullPath = public_path($destinationPath);
        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
            Log::info("Création du dossier : {$fullPath}");
        }

        // Enregistrer chaque fichier
        foreach ($files as $file) {
            // Vérification du type MIME réel du fichier téléchargé
            $realMimeType = $file->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($realMimeType, $allowedMimeTypes)) {
                Log::error("Type MIME invalide : {$realMimeType} pour le fichier {$file->getClientOriginalName()}");
                return response()->json(['error' => 'Type de fichier non autorisé.'], 400);
            }

            // Générer un nom de fichier unique
            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            // Déplacer le fichier vers le répertoire cible
            $file->move($fullPath, $fileName);
            Log::info("Fichier téléchargé avec succès : {$fileName} dans {$destinationPath}");
        }

        // Enregistrer un log personnalisé en base de données
        Logs::ecrireLog($carte->compte->email, "Téléchargement Image Slider");

        // Retourner un message de succès
        return response()->json(['success' => 'Images téléchargées avec succès.']);

    } catch (\Exception $e) {
        // Gestion des erreurs (exceptions)
        Log::error("Erreur lors du téléchargement de l'image pour le compte : {$idCompte}. Message : {$e->getMessage()}");

        // Ajouter un log d'erreur personnalisé en base de données
        Logs::ecrireLog($carte->compte->email, "Erreur Téléchargement Image Slider");

        // Retourner un message d'erreur
        return response()->json(['error' => 'Erreur lors du traitement des fichiers.'], 500);
    }
}


    /**
     * Met à jour les informations d'une carte, telles que le titre et la description.
     *
     * Cette méthode valide les données de la requête, met à jour les champs correspondants dans
     * la base de données, enregistre les modifications apportées et journalise toutes les étapes
     * importantes. Si la carte n'est pas trouvée, un message d'erreur est retourné.
     *
     * Fonctionnement détaillé :
     * - Valide les champs `titre` et `descriptif` envoyés via la requête pour s'assurer de leur conformité.
     * - Récupère l'email de l'utilisateur connecté via l'ID récupéré dans la session.
     * - Vérifie si la carte liée à l'utilisateur existe.
     * - Met à jour les champs `titre` et `descriptif` de la carte.
     * - Sauvegarde les modifications dans la base de données.
     * - Journalise les opérations dans les fichiers de logs ainsi qu'en base de données.
     *
     * @param Request $request L'objet de requête contenant les données nécessaires :
     *                         - `titre` : Le titre à mettre à jour (obligatoire, max : 255 caractères).
     *                         - `descriptif` : La description à mettre à jour (obligatoire, max : 255 caractères).
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur vers la page précédente avec un
     *                                            message de succès ou d'erreur en fonction du résultat.
     *
     * Exceptions et cas d'erreur :
     * - Retourne un message d'erreur si la carte liée à l'utilisateur est introuvable.
     * - Journalise (via `Log::info` et `Logs::ecrireLog`) toutes les erreurs ou succès de l'opération.
     */
    public function updateInfo(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'titre' => 'nullable|string|max:255',
            'descriptif' => 'nullable|string|max:255',
        ]);

        // Récupérer l'ID de compte de l'utilisateur depuis la session
        $idCompte = session('connexion');

        // Récupérer l'email de l'utilisateur connecté
        $compte = Compte::find($idCompte);
        $emailUtilisateur = $compte ? $compte->email : 'Utilisateur inconnu';

        // Rechercher la carte associée à l'utilisateur
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::info('Carte non trouvée pour idCompte: ' . $idCompte);

            // Retourner un message d'erreur si la carte est inexistante
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Mise à jour des informations de la carte
        $carte->titre = $request->input('titre', null);            // Mettre à jour ou à null si absent
        $carte->descriptif = $request->input('descriptif', null);  // Mettre à jour ou à null si absent
        $carte->save();                                            // Sauvegarde des modifications

        // Journaux pour consigner l'opération
        Logs::ecrireLog($emailUtilisateur, "Modification Info");
        Log::info('Informations mises à jour pour idCompte: ' . $idCompte);

        // Retourner un message de succès après la mise à jour
        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }

    /**
     * Rafraîchit le QR Code d'un employé spécifique et journalise les étapes importantes.
     *
     * Cette méthode permet de régénérer le QR Code d'un employé lié à une carte d'entreprise.
     * Elle vérifie l'existence du compte, de la carte et de l'employé avant d'exécuter
     * l'opération. En cas de succès ou d'erreur, l'opération est également journalisée.
     *
     * Fonctionnement détaillé :
     * - Vérifie l'existence du compte avec l'ID donné.
     * - Vérifie si une carte est associée au compte.
     * - Récupère les informations d'un employé lié au compte et à la carte.
     * - Génère un nouveau QR Code grâce à une méthode dédiée dans le modèle `Employer`.
     * - Retourne un message explicatif en cas de succès ou d'échec.
     *
     * @param int $id L'ID du compte (gestionnaire).
     * @param int $idEmp L'ID de l'employé pour lequel le QR Code doit être regénéré.
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur avec un message de succès ou d'erreur.
     *
     * Exceptions et cas d'erreur :
     * - Retourne une erreur si le compte, la carte, ou l'employé est introuvable.
     * - Enregistre toutes les erreurs et actions réussies dans les fichiers de logs et via
     *   le système personnalisé `Logs::ecrireLog`.
     */
    public function refreshQrCodeEmp($id, $idEmp)
    {
        try {
            // Vérification de l'existence du compte lié
            $compte = Compte::find($id);
            if (!$compte) {
                Log::error("Compte non trouvé pour l'ID : {$id}");
                return redirect()->back()->with('error', 'Compte non trouvé.');
            }

            // Récupérer l'ID de compte actif depuis la session
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu'; // Email pour journalisation

            // Vérification de la carte associée au compte
            $carte = Carte::where('idCompte', $compte->idCompte)->first();
            if (!$carte) {
                Log::info("Carte non trouvée pour idCompte : {$compte->idCompte}");
                return redirect()->back()->with('error', 'Carte non trouvée.');
            }

            // Vérification de l'existence de l'employé
            $emp = Employer::find($idEmp);
            if (!$emp) {
                Log::error("Employé non trouvé pour l'ID : {$idEmp}");
                return redirect()->back()->with('error', 'Employé non trouvé.');
            }

            // Génération du QR code pour l'employé
            $result = $emp::QrCodeEmploye($id, $carte->nomEntreprise, $idEmp);

            // Vérification du résultat de la génération
            if (!$result) {
                return redirect()->back()->with('error', 'Erreur lors de la génération du QR Code.');
            }

            // Journalisation de l'opération réussie
            Log::info("QR code rafraîchi avec succès pour idCompte : {$compte->idCompte}");
            Logs::ecrireLog($emailUtilisateur, "Rafraîchissement QR Code Employé");

            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'QR Code rafraîchi avec succès.');

        } catch (\Exception $e) {
            // Gestion des exceptions et journalisation des erreurs
            Log::error("Erreur lors du rafraîchissement du QR code : " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
        }
    }

    /**
     * Affiche le formulaire d'édition des informations de l'entreprise.
     *
     * Cette méthode permet de récupérer les informations nécessaires pour l'affichage du formulaire
     * de modification des données d'une entreprise. Un format personnalisé est appliqué au numéro de téléphone
     * si une carte est trouvée.
     *
     * @return \Illuminate\View\View Retourne la vue `Formulaire.formulaireEntreprise` avec les données :
     *                               - `carte` : Les informations de la carte associée à l'entreprise.
     *                               - `compte` : Les informations du compte utilisateur connecté.
     *
     * Logs :
     * - Cette méthode n'implémente pas la journalisation.
     */
    public function afficherFormulaireEntreprise()
    {
        // Récupérer l'ID du compte depuis la session
        $idCompte = session('connexion');

        // Récupérer l'email de l'utilisateur connecté
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu';

        // Rechercher la carte et le compte associés à l'utilisateur
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::where('idCompte', $idCompte)->first();

        // Formatage du numéro de téléphone si une carte est retrouvée
        if ($carte) {
            $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
        }

        // Retourner la vue avec les informations nécessaires
        return view('Formulaire.formulaireEntreprise', compact('carte', 'compte'));
    }

    /**
     * Met à jour les informations de l'entreprise.
     *
     * Cette méthode valide et sauvegarde les informations modifiées d'une entreprise et gère
     * le cas particulier du changement de nom de l'entreprise (avec renommage du dossier des fichiers associés).
     *
     * Fonctionnement détaillé :
     * - Valide les champs de la requête : `nomEntreprise`, `tel`, `mail`, et `adresse`.
     * - Vérifie l'existence du compte et de la carte associés.
     * - Met à jour si nécessaire les chemins des fichiers (logo, dossier PDF, QR code) en cas de changement de nom.
     * - Sauvegarde les nouvelles informations de la carte et du compte.
     * - Génère une nouvelle VCard.
     * - Journalise les actions dans les logs et via `Logs::ecrireLog`.
     *
     * @param Request $request L'objet de requête HTTP contenant :
     *                         - `nomEntreprise` : Nom modifié de l'entreprise.
     *                         - `tel` : Nouveau numéro de téléphone.
     *                         - `mail` : Nouvel email.
     *                         - `adresse` : Nouvelle adresse.
     * @return \Illuminate\Http\RedirectResponse Redirige l'utilisateur avec un message de succès ou d'erreur selon le déroulement.
     */
    public function updateEntreprise(Request $request)
    {
        // Validation des entrées utilisateur
        $request->validate([
            'nomEntreprise' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'mail' => 'required|email|max:255',
            'adresse' => 'required|string|max:255',
        ]);

        // Récupération de l'ID de compte depuis la session
        $idCompte = session('connexion');
        $compte = Compte::find($idCompte);

        // Vérification si le compte et la carte existent
        if (!$compte) {
            Log::warning('Compte non trouvé pour mise à jour des informations.', ['idCompte' => $idCompte]);
            return redirect()->back()->with('error', 'Compte non trouvé.');
        }

        $emailUtilisateur = $compte->email;
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte introuvable pour mise à jour des informations.', ['idCompte' => $idCompte]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Ancien et nouveau noms d'entreprise (gérer potentiels changements)
        $ancienNomEntreprise = $carte->nomEntreprise;
        $nouveauNomEntreprise = $request->nomEntreprise;

        if ($ancienNomEntreprise !== $nouveauNomEntreprise) {
            $ancienFolderName = "{$idCompte}_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $ancienNomEntreprise);
            $nouveauFolderName = "{$idCompte}_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $nouveauNomEntreprise);

            $ancienPath = public_path("entreprises/{$ancienFolderName}");
            $nouveauPath = public_path("entreprises/{$nouveauFolderName}");

            // Renommer le dossier si nécessaire
            if (File::exists($ancienPath)) {
                if (strcasecmp($ancienFolderName, $nouveauFolderName) === 0) {
                    File::move($ancienPath, $nouveauPath);
                    Log::info("Dossier renommé avec changement de casse : {$ancienPath} -> {$nouveauPath}");
                } elseif (File::exists($nouveauPath)) {
                    Log::error('Un dossier avec le nouveau nom de l\'entreprise existe déjà.', [
                        'ancienPath' => $ancienPath,
                        'nouveauPath' => $nouveauPath,
                    ]);
                    return redirect()->back()->with('error', 'Le dossier avec le nouveau nom existe déjà.');
                } else {
                    File::move($ancienPath, $nouveauPath);
                    Log::info("Dossier renommé avec succès : {$ancienPath} -> {$nouveauPath}");
                }
            } else {
                Log::error("L'ancien dossier est introuvable.", ['ancienPath' => $ancienPath]);
                return redirect()->back()->with('error', 'Ancien dossier introuvable.');
            }

            // Mise à jour des chemins des fichiers
            if ($carte->imgLogo) {
                $carte->imgLogo = str_replace($ancienFolderName, $nouveauFolderName, $carte->imgLogo);
            }

            $pdf = $carte->pdf;
            $namePdf = basename($pdf);

            $carte->pdf = "/entreprises/{$nouveauFolderName}/pdf/{$namePdf}";
            $carte->lienQr = "/entreprises/{$nouveauFolderName}/QR_Codes/QR_Code.svg";

            Log::info('Mise à jour des chemins dans la base de données.', [
                'imgLogo' => $carte->imgLogo,
                'lienQr' => $carte->lienQr,
            ]);
        }

        // Mise à jour des informations restantes
        $carte->tel = $request->tel;
        $carte->ville = $request->adresse;
        $carte->nomEntreprise = $request->nomEntreprise;
        $carte->save();

        // Mise à jour de l'email du compte
        $compte->email = $request->mail;
        $compte->save();

        // Génération de la nouvelle VCard
        Compte::creerVCard($request->nomEntreprise, $request->tel, $request->mail, $idCompte);
        Logs::ecrireLog($emailUtilisateur, "Création de la VCard");
        Logs::ecrireLog($emailUtilisateur, "Modification des informations de l'entreprise");

        // Journalisation et retour avec succès
        Log::info('Informations de l\'entreprise mises à jour avec succès.', [
            'email' => $emailUtilisateur,
            'nomEntreprise' => $request->nomEntreprise,
            'tel' => $request->tel,
            'mail' => $request->mail,
            'adresse' => $request->adresse,
        ]);

        return redirect()->back()->with('success', 'Informations de l\'entreprise mises à jour avec succès.');
    }

    /**
     * Met à jour le template sélectionné pour la carte de l'entreprise.
     *
     * Cette méthode permet de modifier le template associé à une carte d'entreprise en fonction
     * de l'identifiant de template choisi dans la requête. La mise à jour est sauvegardée
     * dans la base de données et journalisée.
     *
     * Fonctionnement détaillé :
     * - Vérifie l'existence de la carte associée à l'utilisateur connecté.
     * - Met à jour l'identifiant du template en fonction de la valeur sélectionnée.
     * - Journalise les informations sur la modification effectuée.
     * - Retourne un message de confirmation ou une erreur.
     *
     * @param Request $request L'objet de requête contenant les données nécessaires.
     *                         - `idTemplate` : Identifiant du template sélectionné (1 à 4).
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur en cas d'échec.
     */
    public function updateTemplate(Request $request)
    {
        // Récupérer l'ID du compte depuis la session
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu';

        // Recherche de la carte associée
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Mise à jour du template en fonction de l'identifiant sélectionné
        switch ($request->idTemplate) {
            case 1:
                $carte->idTemplate = 1;
                break;
            case 2:
                $carte->idTemplate = 2;
                break;
            case 3:
                $carte->idTemplate = 3;
                break;
            case 4:
                $carte->idTemplate = 4;
                break;
            default:
                return redirect()->back()->with('error', 'Template invalide sélectionné.');
        }

        // Sauvegarder les modifications
        $carte->save();

        // Journaliser l'opération réussie
        Log::info('Template mis à jour avec succès', ['email' => $emailUtilisateur, 'idTemplate' => $request->idTemplate]);
        Logs::ecrireLog($emailUtilisateur, "Modification Template");

        // Retourner un message de succès
        return redirect()->back()->with('success', 'Template mis à jour avec succès.');
    }

    /**
     * Télécharge un PDF pour une entreprise en veillant à éviter les collisions de fichiers existants
     * et en mettant à jour les informations dans la base de données.
     *
     * Fonctionnement détaillé :
     * - Valide la requête pour vérifier que le fichier est un PDF valide et que le nom est présent.
     * - Vérifie et crée le dossier cible de destination, si nécessaire.
     * - Détecte les fichiers existants pour éviter les conflits.
     * - Renomme et déplace le PDF téléchargé dans le dossier approprié.
     * - Met à jour les informations du PDF (chemin, nom du bouton, QR code) dans la base de données.
     * - Journalise les erreurs ou réussites.
     *
     * @param Request $request L'objet de requête contenant les données nécessaires :
     *                         - `pdf` : Le fichier PDF à télécharger.
     *                         - `new_name` : Le nom du bouton associé au fichier PDF.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur selon l'opération.
     */
    public function uploadPdf(Request $request)
    {
        // Récupérer l'ID de compte depuis la session
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérifier si la carte existe
        if (!$carte) {
            Log::error("Échec : Aucun compte trouvé pour idCompte : {$idCompte}");
            return redirect()->back()->with('error', 'Compte introuvable.');
        }

        // Construire le chemin de destination pour le PDF
        $entrepriseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $carte->nomEntreprise);
        $destinationPath = "entreprises/{$idCompte}_{$entrepriseName}/pdf";

        try {
            // Valider les données de la requête
            $request->validate([
                'pdf' => 'required|mimes:pdf', // Autorisation uniquement des fichiers PDF
                'new_name' => 'required|string|max:255', // Nouveau nom du bouton
            ]);

            // Vérifier l'existence du répertoire cible et des fichiers éventuels
            $fullPath = public_path($destinationPath);
            if (File::exists($fullPath)) {
                $existingFiles = File::files($fullPath);
                if (count($existingFiles) > 0) {
                    Log::error("Échec : Un fichier existe déjà dans le répertoire {$destinationPath}.");
                    return redirect()->back()->with('error', 'Un fichier existe déjà dans ce dossier. Supprimez-le avant d\'en télécharger un nouveau.');
                }
            }

            // Créer le répertoire cible s'il est inexistant
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
                Log::info("Création du répertoire : {$fullPath}");
            }

            // Récupérer et renommer le fichier
            $file = $request->file('pdf');
            $newName = $request->input('new_name');
            $sanitizedFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $newName) . '.pdf';

            // Déplacer le fichier dans le répertoire cible
            $file->move($fullPath, $sanitizedFileName);
            Log::info("Le fichier PDF a été sauvegardé avec succès dans {$destinationPath} sous le nom {$sanitizedFileName}");

            // Met à jour le chemin et les détails du PDF en base de données
            $carte->pdf = "{$destinationPath}/{$sanitizedFileName}";
            $carte->nomBtnPdf = $newName;

            // Génération du lien QR Code pour le PDF
            $encodedPdfUrl = urlencode(url($carte->pdf));
            $carte->lienPdf = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg&text={$encodedPdfUrl}";
            $carte->save();

            // Ajouter une entrée log pour confirmer le succès
            Logs::ecrireLog($carte->compte->email, "Téléchargement du fichier PDF : {$sanitizedFileName}");

            // Retourner un message de succès
            return redirect()->back()->with('success', 'Votre fichier PDF a été téléchargé avec succès.');

        } catch (\Exception $e) {
            // Gestion et journalisation des erreurs
            Log::error("Erreur lors du téléchargement du fichier PDF pour idCompte : {$idCompte}. Détails : {$e->getMessage()}");
            Logs::ecrireLog($carte->compte->email, "Erreur lors du téléchargement du fichier PDF");

            // Retourner un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement du fichier.');
        }
    }

    /**
     * Télécharge le QR Code PDF en couleur pour l'entreprise.
     *
     * Cette méthode génère un QR Code en couleur (rouge sur fond blanc) à partir du lien PDF
     * associé à la carte de l'entreprise et permet de le télécharger au format SVG.
     *
     * Fonctionnement détaillé :
     * - Vérifie l'existence d'une carte associée à l'utilisateur connecté.
     * - Génère une URL pour créer un QR Code en couleur à l'aide du service `quickchart.io`.
     * - Génère le QR Code au format SVG et le retourne sous forme de fichier téléchargeable.
     *
     * @return \Illuminate\Http\Response Télécharge le QR Code en tant que fichier image (SVG).
     */
    public function downloadQrCodesPDFColor()
    {
        // Récupérer l'ID du compte utilisateur depuis la session
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification de l'existence de la carte et du lien PDF
        if (!$carte || !$carte->lienPdf) {
            return redirect()->back()->with('error', 'QR code PDF introuvable.');
        }

        // Générer l'URL pour obtenir un QR Code rouge sur fond blanc
        $qrCodeContent = $carte->lienPdf;
        $qrCodeUrl = "https://quickchart.io/qr?size=300&dark=FF0000&light=FFFFFF&format=svg&text=" . urlencode($qrCodeContent);
        $qrCode = file_get_contents($qrCodeUrl);

        // Retourner le QR Code en couleur en tant que réponse téléchargeable
        return Response::make($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qrcode_color.svg"');
    }

    /**
     * Télécharge le QR Code PDF en noir et blanc pour l'entreprise.
     *
     * Cette méthode génère un QR Code classique (noir sur fond blanc) à partir du lien PDF
     * associé à la carte de l'entreprise et permet de le télécharger au format SVG.
     *
     * @return \Illuminate\Http\Response Télécharge le QR Code en tant que fichier image (SVG).
     */
    public function downloadQrCodesPDF()
    {
        // Récupérer l'ID du compte utilisateur depuis la session
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification de l'existence de la carte et du lien PDF
        if (!$carte || !$carte->lienPdf) {
            return redirect()->back()->with('error', 'QR code PDF introuvable.');
        }

        // Générer l'URL pour obtenir un QR Code noir sur fond blanc
        $qrCodeContent = $carte->lienPdf;
        $qrCodeUrl = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg&text=" . urlencode($qrCodeContent);
        $qrCode = file_get_contents($qrCodeUrl);

        // Retourner le QR Code en noir et blanc en tant que réponse téléchargeable
        return Response::make($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qrcode_bw.svg"');
    }

    /**
     * Ajoute un lien personnalisé associé à la carte de l'utilisateur connecté.
     *
     * Cette méthode permet d'associer un lien personnalisé à une carte d'entreprise, en le créant
     * dans la base de données après validation des informations de la requête.
     *
     * Fonctionnement détaillé :
     * - Vérifie la validité de la session utilisateur et la présence d'une carte associée.
     * - Valide les champs requis dans la requête (`nom` et `lien`).
     * - Crée un lien personnalisé dans la base de données avec les informations fournies.
     * - Journalise l'opération avec succès ou retourne un message d'erreur si un problème survient.
     *
     * @param Request $request L'objet de requête contenant les données nécessaires :
     *                         - `nom` : Nom du lien personnalisé.
     *                         - `lien` : URL du lien (format valide exigé).
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur selon le déroulement.
     */
    public function updateCustomLink(Request $request)
    {
        // Récupérer l'ID de session utilisateur
        $session = session('connexion');

        // Vérification de la session utilisateur
        if (!$session) {
            Log::error('Session utilisateur expirée ou invalide', ['session' => $session]);
            return redirect()->back()->withErrors(['error' => 'La session utilisateur est expirée ou invalide.']);
        }

        // Récupérer l'email de l'utilisateur connecté
        $emailUtilisateur = Compte::find($session)->email ?? 'Utilisateur inconnu';
        $carte = Carte::where('idCompte', $session)->first();

        // Vérification de l'existence de la carte associée
        if (!$carte) {
            Log::warning('Aucune carte associée à cet utilisateur trouvée', ['email' => $emailUtilisateur]);
            return redirect()->back()->withErrors(['error' => 'Aucune carte associée à cet utilisateur trouvée.']);
        }

        // Validation des données de la requête
        $request->validate([
            'nom' => 'required|string|max:255',
            'lien' => 'required|url'
        ]);

        // Création du lien personnalisé dans la base de données
        Custom_link::create([
            'nom' => $request->input('nom'),
            'lien' => $request->input('lien'),
            'activer' => 0,
            'idCarte' => $carte->idCarte
        ]);

        // Journaliser l'opération réussie
        Log::info('Lien personnalisé ajouté avec succès', [
            'email' => $emailUtilisateur,
            'nom' => $request->input('nom'),
            'lien' => $request->input('lien')
        ]);
        Logs::ecrireLog($emailUtilisateur, "Ajout de lien personnalisé");

        // Retourner un message de succès
        return redirect()->back()->with('success', 'Lien personnalisé ajouté avec succès.');
    }

    /**
     * Met à jour un lien personnalisé existant.
     *
     * Cette méthode permet de modifier le contenu et l'état d'activation d'un lien
     * personnalisé associé à la carte d'un utilisateur.
     *
     * Fonctionnement détaillé :
     * - Récupère et vérifie l'existence du lien à partir de l'identifiant fourni dans la requête.
     * - Met à jour le lien, son état `activer` et enregistre les modifications.
     * - Journalise l'opération avec succès ou avertit en cas de lien introuvable.
     *
     * @param Request $request L'objet de requête contenant les informations nécessaires :
     *                         - `id_link` : L'identifiant du lien à modifier.
     *                         - `lien` : La nouvelle URL du lien.
     *                         - `activer` : Optionnel, un champ représentant l'état d'activation.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès, même en cas d'avertissement.
     */
    public function updateSocialLinkCustom(Request $request)
    {
        // Récupérer l'email de l'utilisateur connecté
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu';

        // Rechercher le lien personnalisé
        $customLink = Custom_link::where('id_link', $request->id_link)->first();

        if ($customLink) {
            // Mise à jour des informations du lien
            $customLink->lien = $request->lien;
            $customLink->activer = $request->has('activer') ? 1 : 0;
            $customLink->save();

            // Journaliser le succès
            Log::info('Lien personnalisé mis à jour avec succès', [
                'email' => $emailUtilisateur,
                'id_link' => $request->id_link,
                'lien' => $request->lien,
                'activer' => $customLink->activer
            ]);
            Logs::ecrireLog($emailUtilisateur, "Mise à jour de lien personnalisé");
        } else {
            // Journaliser un avertissement si aucun lien n'a été trouvé
            Log::warning('Lien personnalisé non trouvé', [
                'email' => $emailUtilisateur,
                'id_link' => $request->id_link
            ]);
        }

        // Retourner un message de réussite
        return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
    }

    /**
     * Modifie la police utilisée sur la carte d'un utilisateur.
     *
     * Cette méthode met à jour le champ `font` associé à la carte d'un compte dans la base de données.
     *
     * Fonctionnement détaillé :
     * - Vérifie l'existence de la carte associée à l'utilisateur connecté.
     * - Met à jour le champ `font` et journalise les changements.
     *
     * @param Request $request L'objet de requête contenant les données nécessaires :
     *                         - `font` : La nouvelle police à appliquer.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateFont(Request $request)
    {
        // Récupérer l'email de l'utilisateur connecté
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu';

        // Vérifier l'existence de la carte associée au compte
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            // Journaliser un avertissement et retourner une erreur
            Log::warning('Carte non trouvée pour mise à jour de la police', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Mise à jour de la police et journalisation
        $carte->font = $request->font;
        $carte->save();

        Log::info('Police mise à jour avec succès', ['email' => $emailUtilisateur, 'font' => $request->font]);
        Logs::ecrireLog($emailUtilisateur, "Mise à jour de la police");

        // Retourner un message de succès
        return redirect()->back()->with('success', 'Police mise à jour avec succès.');
    }

    /**
     * Met à jour le lien d'avis Google associé à la carte d'un utilisateur.
     *
     * Cette méthode enregistre ou remplace le lien Google Avis dans la base de données.
     *
     * @param Request $request L'objet de requête contenant les données nécessaires :
     *                         - `avis_google` : Lien Google Avis à enregistrer.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function uploadAvis(Request $request)
    {
        // Récupérer l'email de l'utilisateur connecté
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu';

        // Vérifier la carte associée à l'utilisateur
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement d\'avis', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Mise à jour du lien d'avis Google
        $carte->lienAvis = $request->avis_google;
        $carte->save();

        // Retourner un message de succès
        return redirect()->back()->with('success', 'Avis enregistré avec succès.');
    }

    /**
     * Supprime le lien d'avis Google associé à la carte d'un utilisateur.
     *
     * Cette méthode met le champ `lienAvis` à null dans la base de données pour supprimer le lien.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deleteAvis()
    {
        // Récupérer la carte associée à l'utilisateur
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if ($carte) {
            // Supprimer le lien d'avis Google
            $carte->lienAvis = null;
            $carte->save();

            // Retourner un message de succès
            return redirect()->back()->with('success', 'Avis supprimé avec succès.');
        }

        // Retourner une erreur si la carte est introuvable
        return redirect()->back()->with('error', 'Carte non trouvée.');
    }

    /**
     * Supprime le lien RDV (lien de commande) associé à la carte d'un utilisateur.
     *
     * Cette méthode met le champ `LienCommande` à null dans la base de données pour supprimer le lien.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deleteRDV()
    {
        // Récupérer la carte associée à l'utilisateur
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if ($carte) {
            // Supprimer le lien RDV
            $carte->LienCommande = null;
            $carte->save();

            // Retourner un message de succès
            return redirect()->back()->with('success', 'Lien RDV supprimé avec succès.');
        }

        // Retourner une erreur si la carte est introuvable
        return redirect()->back()->with('error', 'Carte non trouvée.');
    }
}
