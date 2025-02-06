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
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne la vue dashboardClient avec les informations nécessaires ou redirige en cas d'erreur.
     */
    public function afficherDashboardClient(Request $request)
    {
        try {
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
            Log::info('Chargement du tableau de bord client', ['email' => $emailUtilisateur]);

            $carte = Carte::where('idCompte', $idCompte)->first();
            $compte = Compte::where('idCompte', $idCompte)->first();

            if (!$compte) {
                Log::warning('Aucun compte trouvé', ['email' => $emailUtilisateur]);
            }

            $message = Message::where('afficher', true)->orderBy('id', 'desc')->first();
            $messageContent = $message ? $message->message : 'Aucun message disponible';

            if ($carte) {
                $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
                $horaires = $carte->horaires;
            } else {
                Log::warning('Aucune carte associée au compte.', ['email' => $emailUtilisateur]);
                $horaires = collect();
            }

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
            Log::error('Erreur lors du chargement du tableau de bord client', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors du chargement du tableau de bord.']);
        }
    }

    /**
     * Ajoute et met à jour les horaires.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateHoraires(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour mise à jour des horaires', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        foreach ($jours as $jour) {
            $ouvertureMatin = $request->input($jour . '_ouverture_matin');
            $fermetureMatin = $request->input($jour . '_fermeture_matin');
            $ouvertureApresMidi = $request->input($jour . '_ouverture_aprmidi');
            $fermetureApresMidi = $request->input($jour . '_fermeture_aprmidi');

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

        Log::info('Horaires mis à jour avec succès', ['email' => $emailUtilisateur]);
        Logs::ecrireLog($emailUtilisateur, "Modification Horaires");

        return redirect()->back()->with('success', 'Horaires mis à jour avec succès.');
    }

    /**
     * Formate un numéro de téléphone.
     *
     * @param string $phoneNumber Le numéro de téléphone à formater.
     * @return string Le numéro de téléphone formaté.
     */
    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

    /**
     * Affiche les informations des employés.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\View\View Retourne la vue dashboardClientEmploye avec les informations des employés.
     */
    public function employer(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté

        $compte = Compte::where('idCompte', $idCompte)->first();
        $search = $request->input('search');
        $employes = Employer::with('carte')->join('carte', 'employer.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $idCompte)
            ->when($search, function ($query, $search) {
                return $query->where('employer.nom', 'like', "%{$search}%")
                    ->orWhere('employer.prenom', 'like', "%{$search}%")
                    ->orWhere('employer.fonction', 'like', "%{$search}%");
            })
            ->select('employer.*')
            ->get();

        if ($employes->isEmpty() && !empty($search)) {
            return view('Client.dashboardClientEmploye', [
                'employes' => $employes,
                'search' => $search,
                'error' => 'Aucun résultat trouvé pour votre recherche.'
            ]);
        }

        return view('Client.dashboardClientEmploye', [
            'employes' => $employes,
            'search' => $search,
            'compte' => $compte
        ]);
    }

    /**
     * Supprime un employé.
     *
     * @param int $id L'ID de l'employé à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function destroy($id)
    {
        try {
            Log::info('Tentative de suppression de l\'employé', ['idEmploye' => $id]);
            $employer = Employer::findOrFail($id);

            $idCarte = $employer->idCarte;
            $employer->delete();

            $compte = Compte::find($idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                Logs::ecrireLog($emailUtilisateur, "Suppression Employé");
            }

            Log::info('Employé supprimé avec succès', ['idEmploye' => $id]);
            return redirect()->route('dashboardClientEmploye', ['idCarte' => $idCarte])->with('success', 'L\'employé a été supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'employé', ['idEmploye' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de l\'employé.']);
        }
    }

    /**
     * Affiche les informations des réseaux sociaux.
     *
     * @return \Illuminate\View\View Retourne la vue dashboardClientSocial avec les informations des réseaux sociaux.
     */
    public function social()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;
        $compte = Compte::where('idCompte', $idCompte)->first();

        $allSocial = Social::all();
        $activatedSocial = Rediriger::where('idCarte', $idCarte)
            ->join('social', 'rediriger.idSocial', '=', 'social.idSocial')
            ->select('social.idSocial', 'rediriger.activer', 'rediriger.lien')
            ->get();

        $activatedSocialArray = [];
        foreach ($activatedSocial as $social) {
            $activatedSocialArray[$social->idSocial] = ['activer' => $social->activer, 'lien' => $social->lien];
        }

        $custom = Custom_link::where('idCarte', $idCarte)->get();
        $activatedCustomLinks = Custom_link::where('idCarte', $idCarte)
            ->select('id_link', 'activer', 'lien')
            ->get();

        $activatedCustomLinksArray = [];
        foreach ($activatedCustomLinks as $link) {
            $activatedCustomLinksArray[$link->id_link] = ['activer' => $link->activer, 'lien' => $link->lien];
        }

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
     * Met à jour un lien de réseau social.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateSocialLink(Request $request)
    {
        try {
            $request->validate([
                'idSocial' => 'required|integer',
                'idCarte' => 'required|integer',
                'lien' => 'required|url'
            ]);

            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté

            $rediriger = Rediriger::where('idSocial', $request->idSocial)
                ->where('idCarte', $request->idCarte)
                ->first();

            if ($rediriger) {
                Log::info('Mise à jour du lien social existant', [
                    'idSocial' => $request->idSocial,
                    'idCarte' => $request->idCarte,
                    'email' => $emailUtilisateur
                ]);

                $rediriger->lien = $request->lien;
                $rediriger->activer = $request->has('activer') ? 1 : 0;
                $rediriger->save();

                Logs::ecrireLog($emailUtilisateur, "Modification Lien Social");
            } else {
                Log::info('Création d\'un nouveau lien social', [
                    'idSocial' => $request->idSocial,
                    'idCarte' => $request->idCarte,
                    'email' => $emailUtilisateur
                ]);

                Rediriger::create([
                    'idSocial' => $request->idSocial,
                    'idCarte' => $request->idCarte,
                    'lien' => $request->lien,
                    // patch pour activer le lien par défaut
                    'activer' => $request->has('activer', 1)
                ]);

                Logs::ecrireLog($emailUtilisateur, "Ajout Lien Social");
            }

            return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
        } catch (\Exception $e) {
            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
            Log::error('Erreur lors de la mise à jour du lien social', ['error' => $e->getMessage(), 'email' => $emailUtilisateur]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du lien social.']);
        }
    }

    /**
     * Affiche les statistiques.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\View\View Retourne la vue dashboardClientStatistique avec les statistiques.
     */
    public function statistique(Request $request)
    {
        $session = session('connexion');
        $emailUtilisateur = Compte::find($session)->email; // Récupérer l'email de l'utilisateur connecté
        $idCompte = session('connexion');
        $compte = Compte::where('idCompte', $idCompte)->first();
        $year = $request->query('year', date('Y'));
        $selectedWeek = $request->input('week', date('W'));
        $selectedMonth = $request->input('month', date('n'));
        $idCarte = Carte::where('idCompte', $session)->first()->idCarte;
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

        $employerViews = Vue::selectRaw('employer.nom as nom, COUNT(*) as count')
            ->join('employer', 'vue.idEmp', '=', 'employer.idEmp')
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->whereYear('date', $year)
            ->where('carte.idCarte', $idCarte)
            ->groupBy('nom')
            ->pluck('count', 'nom')
            ->toArray();

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

        $totalViewsCard = Vue::whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->count();

        $weeklyViewsQuery = Vue::selectRaw('WEEK(date, 1) as week, COUNT(*) as count')
            ->whereYear('date', $year)
            ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $session)
            ->groupBy('week');

        $weeklyViews = $weeklyViewsQuery->pluck('count', 'week')->toArray();
        $years = range(date('Y'), date('Y') - 10);
        $selectedYear = $year;

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

        return view('Client.dashboardClientStatistique', compact('yearlyData', 'years', 'selectedYear', 'totalViewsCard', 'weeklyViews', 'selectedWeek', 'selectedMonth', 'employerData', 'monthlyData', 'compte'));
    }

    /**
     * Affiche le formulaire de modification d'un employé.
     *
     * @param int $id L'ID de l'employé à modifier.
     * @return \Illuminate\View\View Retourne la vue formulaireModifEmploye avec les informations de l'employé.
     */
    public function afficherFormulaireModifEmpl($id)
    {
        $employe = Employer::findOrFail($id);
        return view('Formulaire.formulaireModifEmploye', compact('employe'));
    }

    /**
     * Modifie un employé.
     *
     * @param Request $request L'objet de requête HTTP.
     * @param int $id L'ID de l'employé à modifier.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function modifierEmploye(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'fonction' => 'required|string|max:255',
        ]);

        try {
            $employe = Employer::findOrFail($id);
            $employe->nom = $request->nom;
            $employe->prenom = $request->prenom;
            $employe->mail = $request->email;
            $employe->telephone = $request->tel;
            $employe->fonction = $request->fonction;
            $employe->save();

            $compte = Compte::find($employe->idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                Logs::ecrireLog($emailUtilisateur, "Modification Employé");
            }

            return redirect()->route('dashboardClientEmploye')->with('success', 'L\'employé a été modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'employé.');
        }
    }

    /**
     * Change la couleur du QR Code.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateColor(Request $request)
    {
        try {
            $request->validate([
                'couleur1' => 'required|string|max:7',
                'couleur2' => 'required|string|max:7',
            ]);

            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
            $carte = Carte::where('idCompte', $idCompte)->first();

            if (!$carte) {
                Log::warning('Carte non trouvée pour mise à jour des couleurs', ['email' => $emailUtilisateur]);
                return redirect()->back()->withErrors(['error' => 'Carte non trouvée.']);
            }

            $oldColors = [
                'couleur1' => $carte->couleur1,
                'couleur2' => $carte->couleur2,
            ];

            $carte->couleur1 = $request->couleur1;
            $carte->couleur2 = $request->couleur2;
            $carte->save();

            Log::info('Couleurs mises à jour avec succès', [
                'email' => $emailUtilisateur,
                'oldColors' => $oldColors,
                'newColors' => ['couleur1' => $request->couleur1, 'couleur2' => $request->couleur2],
            ]);

            Logs::ecrireLog($emailUtilisateur, "Modification Couleurs");

            Compte::QrCode($idCompte, $carte->nomEntreprise);

            return redirect()->back()->with('success', 'Couleurs mises à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des couleurs', ['email' => $emailUtilisateur, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des couleurs.']);
        }
    }

    /**
     * Télécharge le QR Code entreprise en couleur.
     *
     * @return \Illuminate\Http\Response Retourne le QR Code en couleur en tant que réponse de téléchargement.
     */
    public function downloadQrCodesColor()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
            Log::info('Carte not found for idCompte: ' . $idCompte);
        }

        $entrepriseName = $carte->nomEntreprise;
        $folderName = "{$idCompte}_{$entrepriseName}";

        $qrCodesPath = public_path("entreprises/{$folderName}/QR_Codes/QR_Code.svg");

        if (!File::exists($qrCodesPath)) {
            return redirect()->back()->with('error', 'Aucun QR Code trouvé.');
            Log::info('QR Code not found for idCompte: ' . $idCompte);
        }

        Logs::ecrireLog($emailUtilisateur, "Téléchargement QR Code Couleur");
        Log::info('QR Code downloaded for idCompte: ' . $idCompte);
        return response()->download($qrCodesPath, 'QR_Code_Couleur.svg');
    }

    /**
     * Télécharge le QR Code entreprise.
     *
     * @return \Illuminate\Http\Response Retourne le QR Code en noir et blanc en tant que réponse de téléchargement.
     */
    public function downloadQrCodes()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&&format=svg&text=127.0.0.1:9000/Templates?idCompte=" . $idCompte;

        if (!empty($idCompte) && !empty($emailUtilisateur)) {
            Log::info('QR Code downloaded for idCompte: ' . $idCompte);
            Logs::ecrireLog($emailUtilisateur, "Téléchargement QR Code");
        }

        return response()->streamDownload(function () use ($url) {
            echo file_get_contents($url);
        }, 'QR_Code.svg');
    }

    /**
     * Affiche le tableau de bord client en PDF.
     *
     * @return \Illuminate\View\View Retourne la vue dashboardClientPDF avec les informations nécessaires.
     */
    public function afficherDashboardClientPDF()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Email inconnu'; // Récupération de l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning("Carte introuvable pour l'utilisateur : {$emailUtilisateur}");
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Conserve les majuscules dans les noms d'entreprise
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";

        // Récupération des images dans le répertoire
        $imagesPath = public_path("entreprises/{$folderName}/images");
        $images = [];
        if (File::exists($imagesPath)) {
            $images = File::files($imagesPath);
            $images = array_map(function ($file) {
                return $file->getFilename();
            }, $images);
        }

        // Récupération des URLs YouTube depuis le fichier videos.json
        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        // Détection du logo parmi plusieurs formats possibles
        $logoPath = '';
        $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaires
        foreach ($formats as $format) {
            $path = public_path("entreprises/{$folderName}/logos/logo.{$format}");
            if (File::exists($path)) {
                $logoPath = asset("entreprises/{$folderName}/logos/logo.{$format}");
                break;
            }
        }

        // Log le processus de récupération des données pour le dashboard
        Log::info("Affichage du tableau de bord pour l'utilisateur : {$emailUtilisateur}", [
            'email' => $emailUtilisateur,
            'imagesCount' => count($images),
            'youtubeUrlsCount' => count($youtubeUrls),
            'logoPath' => $logoPath,
        ]);

        // Retourner la vue avec les données récupérées du compte, de la carte, des images et des vidéos
        return view('Client.dashboardClientPDF', compact('carte', 'images', 'folderName', 'idCompte', 'youtubeUrls', 'logoPath', 'compte'));
    }

    /**
     * Télécharge le logo dans les fichiers et enregistre le chemin dans la BD tout en conservant la casse.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function uploadLogo(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur anonyme';

        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement du logo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise); // Conserve majuscules dans le dossier.
        $folderName = "{$idCompte}_{$entrepriseName}";
        $logoPath = public_path("entreprises/{$folderName}/logos");

        // Créer récursivement tous les répertoires nécessaires (entreprises/ + sous-dossiers)
        if (!File::exists($logoPath)) {
            try {
                File::makeDirectory($logoPath, 0755, true); // Création récursive des répertoires.
            } catch (\Exception $e) {
                Log::error("Erreur lors de la création du répertoire {$logoPath}", [
                    'email' => $emailUtilisateur,
                    'exception' => $e->getMessage(),
                ]);
                return redirect()->back()->with('error', 'Impossible de sauvegarder le logo.');
            }
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileType = $file->getClientOriginalExtension(); // Conserve la casse originale de l'extension.
            $mimeType = $file->getMimeType();

            // Vérifier les extensions et les MIME types acceptés
            if (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png', 'svg']) && strpos($mimeType, 'image/') === 0) {
                // Supprimer l'ancien logo s'il existe
                if (File::exists($logoPath)) {
                    $existingLogos = File::files($logoPath);
                    foreach ($existingLogos as $logoFile) {
                        File::delete($logoFile->getPathname());
                    }
                }

                // Sauvegarder le nouveau logo tout en conservant l'extension d'origine
                $fileName = "logo.{$fileType}"; // Conserve les majuscules de l'extension si présentes.
                $file->move($logoPath, $fileName);

                // Mettre à jour la base de données avec le nouveau chemin du logo (avec casse conservée)
                $carte->imgLogo = "entreprises/{$folderName}/logos/{$fileName}";
                $carte->save();

                Log::info('Logo téléchargé avec succès', ['email' => $emailUtilisateur, 'fileName' => $fileName]);
                Logs::ecrireLog($emailUtilisateur, "Téléchargement Logo");

                return redirect()->route('dashboardClientPDF')->with('success', 'Logo téléchargé avec succès.');
            } else {
                Log::warning('Type de fichier ou extension non valide pour le logo', ['email' => $emailUtilisateur]);
                return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
            }
        }

        return redirect()->back()->with('error', 'Aucun fichier téléchargé.');
    }

    /**
     * Enregistre dans la BD l'URL de prise de rendez-vous.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function urlsrdv(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté

        if (!$carte) {
            Log::warning('Carte non trouvée pour l\'URL RDV', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        if ($request->filled('rdv_url')) { // URL RDV
            $rdvUrl = $request->input('rdv_url');

            if (preg_match('/^https?:\/\//', $rdvUrl)) {
                $carte->lienCommande = $rdvUrl;
                $carte->save();

                Log::info('URL RDV enregistrée avec succès', ['email' => $emailUtilisateur, 'rdvUrl' => $rdvUrl]);
                Logs::ecrireLog($emailUtilisateur, "Téléchargement Url RDV");

                return redirect()->route('dashboardClientPDF')->with('success', 'URL Rdv enregistrée avec succès.');
            } else {
                Log::warning('URL RDV non valide', ['email' => $emailUtilisateur, 'rdvUrl' => $rdvUrl]);
                return redirect()->back()->with('error', 'L\'URL doit commencer par http ou https.');
            }
        }

        Log::warning('Aucune URL fournie pour RDV', ['email' => $emailUtilisateur]);
        return redirect()->back()->with('error', 'Aucune URL fournie.');
    }

    /**
     * Télécharge et enregistre les vidéos YouTube ou URL personnalisées.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function uploadYouTubeVideo(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur anonyme';

        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement de vidéo YouTube', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Conserve les majuscules lors de la création du dossier
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";

        if ($request->has('youtube_url')) {
            $youtubeUrl = $request->input('youtube_url');

            // Vérifier la validité de l'URL YouTube avec une expression améliorée
            if (preg_match('/^https:\/\/(www\.)?youtube\.com\/watch\?v=[A-Za-z0-9_-]+$/', $youtubeUrl)) {
                $videosPath = public_path("entreprises/{$folderName}/videos");

                if (!File::exists($videosPath)) {
                    File::makeDirectory($videosPath, 0755, true); // Créer le dossier si inexistant
                }

                $videosFile = $videosPath . '/videos.json';
                $videosData = [];

                // Charger les données existantes si le fichier existe
                if (File::exists($videosFile)) {
                    $videosData = json_decode(File::get($videosFile), true);
                }

                // Ajouter la nouvelle URL à la liste des vidéos
                $videosData[] = $youtubeUrl;
                File::put($videosFile, json_encode($videosData, JSON_PRETTY_PRINT));

                Log::info('URL YouTube enregistrée avec succès', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                Logs::ecrireLog($emailUtilisateur, "Téléchargement URL YouTube");

                return redirect()->route('dashboardClientPDF')->with('success', 'URL YouTube enregistrée avec succès.');
            } else {
                // Si l'URL est invalide, log niveau warning
                Log::warning('URL YouTube non valide', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                return redirect()->back()->with('error', 'URL YouTube non valide.');
            }
        }

        // Gestion des URLs personnalisées (le champ `custom_url`)
        if ($request->filled('custom_url')) {
            $customUrl = $request->input('custom_url');

            // Valider la structure de l'URL personnalisée (facultatif, peut être étendu)
            if (filter_var($customUrl, FILTER_VALIDATE_URL)) {
                Log::info('URL personnalisée enregistrée avec succès', ['email' => $emailUtilisateur, 'customUrl' => $customUrl]);
                Logs::ecrireLog($emailUtilisateur, "Téléchargement URL personnalisée");

                return view('Client.dashboardClientPDF', [
                    'carte' => $carte,
                    'youtubeUrls' => isset($youtubeUrls) ? $youtubeUrls : [],
                    'idCompte' => $idCompte,
                    'customUrl' => $customUrl
                ])->with('success', 'URL personnalisée enregistrée avec succès.');
            } else {
                Log::warning('URL personnalisée invalide', ['email' => $emailUtilisateur, 'customUrl' => $customUrl]);
                return redirect()->back()->with('error', 'URL personnalisée invalide.');
            }
        }

        // Si aucune URL n'est fournie
        Log::warning('Aucune URL fournie', ['email' => $emailUtilisateur]);
        return redirect()->back()->with('error', 'Aucune URL fournie.');
    }

    /**
     * Supprime les images.
     *
     * @param string $filename Le nom du fichier à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deleteImage($filename)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur inconnu'; // Gestion en cas d'email manquant
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning("Carte introuvable pour la suppression d'une image", ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Conserver les majuscules dans les noms des dossiers
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $folderName = "{$idCompte}_{$entrepriseName}";

        // Construction du chemin du fichier
        $filePath = public_path("entreprises/{$folderName}/images/{$filename}");

        // Vérification et suppression du fichier
        if (File::exists($filePath)) {
            try {
                File::delete($filePath);

                Log::info('Image supprimée avec succès', [
                    'email' => $emailUtilisateur,
                    'filename' => $filename,
                    'filePath' => $filePath,
                ]);
                Logs::ecrireLog($emailUtilisateur, "Suppression Image");

                return redirect()->back()->with('success', 'Image supprimée avec succès.');
            } catch (\Exception $e) {
                Log::error("Erreur lors de la suppression de l'image", [
                    'email' => $emailUtilisateur,
                    'filename' => $filename,
                    'filePath' => $filePath,
                    'exception' => $e->getMessage(),
                ]);
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image.');
            }
        } else {
            Log::warning('Image non trouvée pour suppression', [
                'email' => $emailUtilisateur,
                'filename' => $filename,
                'filePath' => $filePath,
            ]);
            return redirect()->back()->with('error', 'Image non trouvée.');
        }
    }

    /**
     * Supprime les images du slider.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
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
     * Supprime le PDF, le chemin dans la BD et le nom dans la BD.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deletePdf()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Remplacer 'path/to/pdf' par le chemin réel du répertoire des fichiers PDF
        $filePath = $carte->pdf;

        if (file_exists($filePath)) {
            unlink($filePath);
            $carte->pdf = null;
            $carte->nomBtnPdf = null;
            $carte->save();
            return redirect()->back()->with('success', 'Fichier supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Fichier introuvable.');
    }

    /**
     * Supprime le logo.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deleteLogo()
    {
        $idCompte = session('connexion');
        $compte = Compte::find($idCompte);
        $emailUtilisateur = $compte ? $compte->email : 'Utilisateur inconnu'; // Gestion du cas où le compte est introuvable
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression du logo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Définir les chemins des logos en fonction des formats
        $entrepriseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $carte->nomEntreprise); // Garder les majuscules et caractères valides
        $folderName = "{$idCompte}_{$entrepriseName}";
        $logoFormats = ['jpg', 'jpeg', 'png', 'svg'];

        $logoDeleted = false; // Pour suivre si un fichier a été supprimé

        foreach ($logoFormats as $format) {
            $logoPath = public_path("entreprises/{$folderName}/logos/logo.{$format}");

            if (File::exists($logoPath)) {
                File::delete($logoPath);
                $logoDeleted = true;

                // Logging
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
     * Télécharge les images du slider.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function uploadSlider(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::error("Échec : Aucun compte trouvé pour idCompte : {$idCompte}");
            return redirect()->back()->with('error', 'Compte non trouvé.');
        }

        // Conserver les majuscules dans le nom de l'entreprise
        $entrepriseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $carte->nomEntreprise);
        $destinationPath = "entreprises/{$idCompte}_{$entrepriseName}/slider";

        try {
            // Valider le fichier d'image dans la requête
            $validated = $request->validate([
                'image' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Limite à 2048KB avec formats autorisés
            ]);

            // Récupérer les informations du fichier
            $file = $request->file('image');

            // Vérification supplémentaire du type MIME réel
            $realMimeType = $file->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($realMimeType, $allowedMimeTypes)) {
                Log::error("Type MIME invalide : {$realMimeType} pour le fichier {$file->getClientOriginalName()}");
                return redirect()->back()->with('error', 'Type de fichier non autorisé.');
            }

            // Définir un nom de fichier unique
            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            // Créer le répertoire cible s'il n'existe pas
            if (!File::exists(public_path($destinationPath))) {
                File::makeDirectory(public_path($destinationPath), 0755, true);
                Log::info("Création du dossier : {$destinationPath}");
            }

            // Déplacer le fichier vers le répertoire cible
            $file->move(public_path($destinationPath), $fileName);
            Log::info("Fichier téléchargé avec succès : {$fileName} dans {$destinationPath}");

            // Enregistrer un log personnalisé en base de données
            Logs::ecrireLog($carte->compte->email, "Téléchargement Image Slider");

            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'Image téléchargée avec succès.');

        } catch (\Exception $e) {
            // En cas d'erreur, journaliser les détails
            Log::error("Erreur lors du téléchargement de l'image pour le compte : {$idCompte}. Message : {$e->getMessage()}");

            // Ajouter un log d'erreur en base de données
            Logs::ecrireLog($carte->compte->email, "Erreur Téléchargement Image Slider");

            // Redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors du téléchargement de l\'image.');
        }
    }

    /**
     * Met à jour la description.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateInfo(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'descriptif' => 'required|string|max:255',
        ]);

        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
            Log::info('Carte not found for idCompte: ' . $idCompte);
        }

        $carte->titre = $request->titre;
        $carte->descriptif = $request->descriptif;
        $carte->save();

        Logs::ecrireLog($emailUtilisateur, "Modification Info");
        Log::info('Info updated for idCompte: ' . $idCompte);

        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }

    /**
     * Rafraîchit le QR Code d'un employé.
     *
     * @param int $id L'ID du compte.
     * @param int $idEmp L'ID de l'employé.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function refreshQrCodeEmp($id, $idEmp)
    {
        try {
            // Vérification du compte
            $compte = Compte::find($id);
            if (!$compte) {
                Log::error("Compte non trouvé pour l'ID : {$id}");
                return redirect()->back()->with('error', 'Compte non trouvé.');
            }

            $idCompte = session('connexion');
            $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
            // Vérification de la carte associée
            $carte = Carte::where('idCompte', $compte->idCompte)->first();
            if (!$carte) {
                Log::info("Carte non trouvée pour idCompte : {$compte->idCompte}");
                return redirect()->back()->with('error', 'Carte non trouvée.');
            }

            // Vérification de l'employé
            $emp = Employer::find($idEmp);
            if (!$emp) {
                Log::error("Employé non trouvé pour l'ID : {$idEmp}");
                return redirect()->back()->with('error', 'Employé non trouvé.');
            }

            // Génération du QR code
            $result = $emp::QrCodeEmploye($id, $carte->nomEntreprise, $idEmp);

            if (!$result) {
                return redirect()->back()->with('error', 'Erreur lors de la génération du QR Code.');
            }

            Log::info("QR code rafraîchi avec succès pour idCompte : {$compte->idCompte}");
            Logs::ecrireLog($emailUtilisateur, "Rafraîchissement QR Code Employé");
            return redirect()->back()->with('success', 'QR Code rafraîchi avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur lors du rafraîchissement du QR code : " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
        }
    }

    /**
     * Affiche le formulaire d'entreprise.
     *
     * @return \Illuminate\View\View Retourne la vue formulaireEntreprise avec les informations nécessaires.
     */
    public function afficherFormulaireEntreprise()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::where('idCompte', $idCompte)->first();

        if ($carte) {
            $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
        }

        return view('Formulaire.formulaireEntreprise', compact('carte', 'compte'));
    }

    /**
     * Met à jour les informations de l'entreprise.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     * */
    public function updateEntreprise(Request $request)
    {
        // Validation des données de requête
        $request->validate([
            'nomEntreprise' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'mail' => 'required|email|max:255',
            'adresse' => 'required|string|max:255',
        ]);

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

        // Mise à jour des informations de la carte
        $carte->tel = $request->tel;
        $carte->ville = $request->adresse;

        // Ancien et nouveau nom de l'entreprise
        $ancienNomEntreprise = $carte->nomEntreprise;
        $nouveauNomEntreprise = $request->nomEntreprise;

        if ($ancienNomEntreprise !== $nouveauNomEntreprise) {
            $ancienFolderName = "{$idCompte}_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $ancienNomEntreprise);
            $nouveauFolderName = "{$idCompte}_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $nouveauNomEntreprise);

            $ancienPath = public_path("entreprises/{$ancienFolderName}");
            $nouveauPath = public_path("entreprises/{$nouveauFolderName}");

            // Si l'ancien dossier existe, on le renomme
            if (File::exists($ancienPath)) {
                // Cas où le nouveau dossier existe déjà
                if (strcasecmp($ancienFolderName, $nouveauFolderName) === 0) {
                    // Si seule la casse change, renommer directement
                    File::move($ancienPath, $nouveauPath);
                    Log::info("Dossier renommé avec changement de casse : {$ancienPath} -> {$nouveauPath}");
                } elseif (File::exists($nouveauPath)) {
                    Log::error('Un dossier avec le nouveau nom de l\'entreprise existe déjà.', [
                        'ancienPath' => $ancienPath,
                        'nouveauPath' => $nouveauPath,
                    ]);
                    return redirect()->back()->with('error', 'Le dossier avec le nouveau nom existe déjà.');
                } else {
                    // Renommer si aucun conflit
                    File::move($ancienPath, $nouveauPath);
                    Log::info("Dossier renommé avec succès : {$ancienPath} -> {$nouveauPath}");
                }
            } else {
                Log::error("L'ancien dossier est introuvable pour la mise à jour.", ['ancienPath' => $ancienPath]);
                return redirect()->back()->with('error', 'Ancien dossier introuvable.');
            }

            // Mise à jour des chemins dans la base de données
            if ($carte->imgLogo) {
                $carte->imgLogo = str_replace($ancienFolderName, $nouveauFolderName, $carte->imgLogo);
                Log::info('Chemin du logo mis à jour après le renommage du dossier.', ['imgLogo' => $carte->imgLogo]);
            }

            //recuperer le nom du pdf dans le dossier
            $pdf = $carte->pdf;
            $namePdf = basename($pdf);

            //modifier le chemin du pdf
           $carte->pdf = "/entreprises/{$nouveauFolderName}/pdf/".$namePdf;

            $carte->lienQr = "/entreprises/{$nouveauFolderName}/QR_Codes/QR_Code.svg";

            Log::info('Mise à jour des chemins dans la base de données après le renommage du dossier.', [
                'imglogo' => $carte->imgLogo,
                'lienQr' => $carte->lienQr,
            ]);
        }

        // Mise à jour des informations (nom, tel, adresse) dans la base de données
        $carte->nomEntreprise = $request->nomEntreprise;
        $carte->save();

        // Mise à jour de l'email dans le compte
        $compte->email = $request->mail;
        $compte->save();

        // Création de la nouvelle vCard
        Compte::creerVCard($request->nomEntreprise, $request->tel, $request->mail, $idCompte);
        Logs::ecrireLog($emailUtilisateur, "Création de la VCard");
        Logs::ecrireLog($emailUtilisateur, "Modification des informations de l'entreprise");

        // Journaliser la mise à jour réussie
        Log::info('Informations de l\'entreprise mises à jour avec succès.', [
            'email' => $emailUtilisateur,
            'nomEntreprise' => $request->nomEntreprise,
            'tel' => $request->tel,
            'mail' => $request->mail,
            'adresse' => $request->adresse,
        ]);

        // Retour avec un message de succès
        return redirect()->back()->with('success', 'Informations de l\'entreprise mises à jour avec succès.');
    }

    /**
     * Met à jour le template.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateTemplate(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

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
        }

        $carte->save();
        Log::info('Template mis à jour avec succès', ['email' => $emailUtilisateur, 'idTemplate' => $request->idTemplate]);
        Logs::ecrireLog($emailUtilisateur, "Modification Template");

        return redirect()->back()->with('success', 'Template mis à jour avec succès.');
    }

    /**
     * Télécharge un PDF en conservant les majuscules dans les liens.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function uploadPdf(Request $request)
    {
        // Récupérer l'identifiant du compte via la session
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérifier si la carte existe
        if (!$carte) {
            Log::error("Échec : Aucun compte trouvé pour idCompte : {$idCompte}");
            return redirect()->back()->with('error', 'Compte introuvable.');
        }

        // Conserver les majuscules et créer le chemin de destination
        $entrepriseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $carte->nomEntreprise);
        $destinationPath = "entreprises/{$idCompte}_{$entrepriseName}/pdf";

        try {
            // Valider les données de la requête
            $request->validate([
                'pdf' => 'required|mimes:pdf', // Autoriser uniquement les fichiers PDF
                'new_name' => 'required|string|max:255', // Le nouveau nom doit être une chaîne valide
            ]);

            // Vérifier et gérer les fichiers existants dans le répertoire
            $fullPath = public_path($destinationPath);
            if (File::exists($fullPath)) {
                $existingFiles = File::files($fullPath);
                if (count($existingFiles) > 0) {
                    Log::error("Échec : Un fichier existe déjà dans le répertoire {$destinationPath}.");
                    return redirect()->back()->with('error', 'Un fichier existe déjà dans ce dossier. Supprimez-le avant de télécharger un nouveau fichier.');
                }
            }

            // Créer le répertoire cible s'il n'existe pas
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
                Log::info("Création du répertoire : {$fullPath}");
            }

            // Récupérer le fichier et le nouveau nom soumis via la requête
            $file = $request->file('pdf');
            $newName = $request->input('new_name');

            // Renommer le fichier en conservant les majuscules et en remplaçant les caractères invalides
            $sanitizedFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $newName) . '.pdf';

            // Déplacer le fichier dans le répertoire cible
            $file->move($fullPath, $sanitizedFileName);
            Log::info("Le fichier PDF a été renommé en {$sanitizedFileName} et sauvegardé dans {$destinationPath}");

            // Mettre à jour la base de données
            $carte->pdf = "{$destinationPath}/{$sanitizedFileName}"; // Enregistrer le chemin relatif
            $carte->nomBtnPdf = $newName; // Mettre à jour le bouton selon le nouveau nom
            $carte->save();

            // Générer un lien de QR Code avec l'encodage nécessaire au texte
            $encodedPdfUrl = urlencode(url($carte->pdf));
            $carte->lienPdf = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg&text={$encodedPdfUrl}";
            $carte->save();

            // Ajouter une log pour le succès
            Logs::ecrireLog($carte->compte->email, "Téléchargement de PDF : {$sanitizedFileName}");

            // Retourner un message de succès
            return redirect()->back()->with('success', 'Votre fichier PDF a été téléchargé avec succès.');

        } catch (\Exception $e) {
            // Gérer et journaliser les erreurs
            Log::error("Erreur lors du téléchargement du fichier PDF pour idCompte : {$idCompte}. Détails : {$e->getMessage()}.");
            Log::debug('Données reçues pour le téléchargement : ', $request->all());

            // Enregistrer un log d'erreur en base de données
            Logs::ecrireLog($carte->compte->email, "Erreur lors du téléchargement du PDF");

            // Retourner un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement du fichier.');
        }
    }

    /**
     * Télécharge le QR Code PDF en couleur.
     *
     * @return \Illuminate\Http\Response Retourne le QR Code en couleur en tant que réponse de téléchargement.
     */
    public function downloadQrCodesPDFColor()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Récupérer la carte à partir de la base de données
        $carte = Carte::find($carte->idCarte);

        if (!$carte || !$carte->lienPdf) {
            return redirect()->back()->with('error', 'QR code PDF not found.');
        }

        // Générer le QR code en couleur
        $qrCodeContent = $carte->lienPdf;
        $qrCodeUrl = "https://quickchart.io/qr?size=300&dark=FF0000&light=FFFFFF&format=svg&text=" . urlencode($qrCodeContent);
        $qrCode = file_get_contents($qrCodeUrl);

        // Retourner le QR code en tant que réponse de téléchargement
        return Response::make($qrCode)->header('Content-Type', 'image/svg+xml')->header('Content-Disposition', 'attachment; filename="qrcode_color.svg"');
    }

    /**
     * Télécharge le QR Code PDF en noir et blanc.
     *
     * @return \Illuminate\Http\Response Retourne le QR code en noir et blanc en tant que réponse de téléchargement.
     */
    public function downloadQrCodesPDF()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Récupérer la carte à partir de la base de données
        $carte = Carte::find($carte->idCarte);

        if (!$carte || !$carte->lienPdf) {
            return redirect()->back()->with('error', 'QR code PDF not found.');
        }

        // Générer le QR code en noir et blanc
        $qrCodeContent = $carte->lienPdf;
        $qrCodeUrl = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&format=svg&text=" . urlencode($qrCodeContent);
        $qrCode = file_get_contents($qrCodeUrl);

        // Retourner le QR code en tant que réponse de téléchargement
        return Response::make($qrCode)->header('Content-Type', 'image/svg+xml')->header('Content-Disposition', 'attachment; filename="qrcode_bw.svg"');
    }

    /**
     * Met à jour un lien personnalisé.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateCustomLink(Request $request)
    {
        $session = session('connexion');

        if (!$session) {
            Log::error('Session utilisateur expirée ou invalide', ['session' => $session]);
            return redirect()->back()->withErrors(['error' => 'La session utilisateur est expirée ou invalide.']);
        }

        $emailUtilisateur = Compte::find($session)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $session)->first();

        if (!$carte) {
            Log::warning('Aucune carte associée à cet utilisateur trouvée', ['email' => $emailUtilisateur]);
            return redirect()->back()->withErrors(['error' => 'Aucune carte associée à cet utilisateur trouvée.']);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'lien' => 'required|url'
        ]);

        Custom_link::create([
            'nom' => $request->input('nom'),
            'lien' => $request->input('lien'),
            'activer' => 0,
            'idCarte' => $carte->idCarte
        ]);

        Log::info('Lien personnalisé ajouté avec succès', ['email' => $emailUtilisateur, 'nom' => $request->input('nom'), 'lien' => $request->input('lien')]);
        Logs::ecrireLog($emailUtilisateur, "Ajout de lien personnalisé");

        return redirect()->back()->with('success', 'Lien personnalisé ajouté avec succès.');
    }

    /**
     * Met à jour un lien personnalisé.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateSocialLinkCustom(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté

        $customLink = Custom_link::where('id_link', $request->id_link)->first();

        if ($customLink) {
            $customLink->lien = $request->lien;
            $customLink->activer = $request->has('activer') ? 1 : 0;
            $customLink->save();

            Log::info('Lien personnalisé mis à jour avec succès', ['email' => $emailUtilisateur, 'id_link' => $request->id_link, 'lien' => $request->lien, 'activer' => $customLink->activer]);
            Logs::ecrireLog($emailUtilisateur, "Mise à jour de lien personnalisé");
        } else {
            Log::warning('Lien personnalisé non trouvé', ['email' => $emailUtilisateur, 'id_link' => $request->id_link]);
        }

        return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
    }

    /**
     * Met à jour la police.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function updateFont(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour mise à jour de la police', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $carte->font = $request->font;
        $carte->save(); // Enregistrer les modifications dans la base de données

        Log::info('Police mise à jour avec succès', ['email' => $emailUtilisateur, 'font' => $request->font]);
        Logs::ecrireLog($emailUtilisateur, "Mise à jour de la police");

        return redirect()->back()->with('success', 'Police mise à jour avec succès.');
    }

    /**
     * Télécharge les avis.
     *
     * @param Request $request L'objet de requête HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function uploadAvis(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement d\'avis', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $carte->lienAvis = $request->avis_google;
        $carte->save();

        return redirect()->back()->with('success', 'Avis enregistré avec succès.');
    }

    /**
     * Supprime les avis.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deleteAvis()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if ($carte) {
            $carte->lienAvis = null;
            $carte->save();
            return redirect()->back()->with('success', 'Avis supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Carte non trouvée.');
    }

    /**
     * Supprime le lien RDV.
     *
     * @return \Illuminate\Http\RedirectResponse Redirige avec un message de succès ou d'erreur.
     */
    public function deleteRDV()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if ($carte) {
            $carte->LienCommande = null;
            $carte->save();
            return redirect()->back()->with('success', 'Lien RDV supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Carte non trouvée.');
    }
}
