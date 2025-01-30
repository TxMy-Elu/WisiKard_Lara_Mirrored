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

class DashboardClient extends Controller
{
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

            return view('client.dashboardClient', [
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

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

    public function employer(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
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
            return view('client.dashboardClientEmploye', [
                'employes' => $employes,
                'search' => $search,
                'error' => 'Aucun résultat trouvé pour votre recherche.'
            ]);
        }

        return view('client.dashboardClientEmploye', [
            'employes' => $employes,
            'search' => $search
        ]);
    }

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

    public function social()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;

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
        $activatedCustomLinks = Custom_Link::where('idCarte', $idCarte)
            ->select('id_link', 'activer', 'lien')
            ->get();

        $activatedCustomLinksArray = [];
        foreach ($activatedCustomLinks as $link) {
            $activatedCustomLinksArray[$link->id_link] = ['activer' => $link->activer, 'lien' => $link->lien];
        }

        return view('client.dashboardClientSocial', [
            'allSocial' => $allSocial,
            'activatedSocial' => $activatedSocialArray,
            'idCarte' => $idCarte,
            'custom' => $custom,
            'activatedCustomLinks' => $activatedCustomLinksArray
        ]);
    }

    public function updateSocialLink(Request $request)
    {
        try {
            $request->validate([
                'idSocial' => 'required|integer',
                'idCarte' => 'required|integer',
                'lien' => 'nullable|url'
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
                    'activer' => $request->has('activer') ? 1 : 0
                ]);

                Logs::ecrireLog($emailUtilisateur, "Ajout Lien Social");
            }

            return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du lien social', ['error' => $e->getMessage(), 'email' => $emailUtilisateur]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du lien social.']);
        }
    }


    public function statistique(Request $request)
    {
        $session = session('connexion');
        $emailUtilisateur = Compte::find($session)->email; // Récupérer l'email de l'utilisateur connecté
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

        return view('client.dashboardClientStatistique', compact('yearlyData', 'years', 'selectedYear', 'totalViewsCard', 'weeklyViews', 'selectedWeek', 'selectedMonth', 'employerData', 'monthlyData'));
    }

    public function afficherFormulaireModifEmpl($id)
    {
        $employe = Employer::findOrFail($id);
        return view('formulaire.formulaireModifEmploye', compact('employe'));
    }

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
                Logs::ecrireLog($emailUtilisateur, "Modification Employe");
            }

            return redirect()->route('dashboardClientEmploye')->with('success', 'L\'employé a été modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'employé.');
        }
    }

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

    public function afficherDashboardClientPDF()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $imagesPath = public_path("entreprises/{$folderName}/images");
        $images = [];
        if (File::exists($imagesPath)) {
            $images = File::files($imagesPath);
            $images = array_map(function ($file) {
                return $file->getFilename();
            }, $images);
        }

        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        // Détection des différents types de fichiers
        $logoPath = '';
        $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaire
        foreach ($formats as $format) {
            $path = public_path('entreprises/' . $carte->compte->idCompte . '_' . $carte->nomEntreprise . '/logos/logo.' . $format);
            if (file_exists($path)) {
                $logoPath = asset('entreprises/' . $carte->compte->idCompte . '_' . $carte->nomEntreprise . '/logos/logo.' . $format);
                break;
            }
        }

        return view('client.dashboardClientPDF', compact('carte', 'images', 'folderName', 'idCompte', 'youtubeUrls', 'logoPath'));
    }


    public function uploadLogo(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $emailUtilisateur = Compte::find($idCompte)->email ?? 'Utilisateur anonyme';

        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement du logo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";
        $logoPath = public_path("entreprises/{$folderName}/logos");

        // Créer récursivement tous les répertoires nécessaires (entreprises/ + sous-dossiers)
        if (!File::exists($logoPath)) {
            try {
                File::makeDirectory($logoPath, 0755, true); // Paramètre "true" pour une création récursive
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
            $fileType = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();

            // Vérifier les extensions et les MIME types acceptés
            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'svg']) && strpos($mimeType, 'image/') === 0) {
                // Supprimer l'ancien logo s'il existe
                if (File::exists($logoPath)) {
                    $existingLogos = File::files($logoPath);
                    foreach ($existingLogos as $logoFile) {
                        File::delete($logoFile->getPathname());
                    }
                }

                // Sauvegarder le nouveau logo
                $fileName = "logo.{$fileType}";
                $file->move($logoPath, $fileName);

                // Mettre à jour la base de données avec le nouveau chemin du logo
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

    public function uploadYouTubeVideo(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté

        if (!$carte) {
            Log::warning('Carte non trouvée pour le téléchargement de vidéo YouTube', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        if ($request->has('youtube_url')) {
            $youtubeUrl = $request->input('youtube_url');

            // Vérifier si l'URL YouTube est valide
            if (preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)$/', $youtubeUrl)) {
                $videosPath = public_path("entreprises/{$folderName}/videos");

                if (!File::exists($videosPath)) {
                    File::makeDirectory($videosPath, 0755, true);
                }

                $videosFile = $videosPath . '/videos.json';
                $videosData = [];

                if (File::exists($videosFile)) {
                    $videosData = json_decode(File::get($videosFile), true);
                }

                $videosData[] = $youtubeUrl;
                File::put($videosFile, json_encode($videosData, JSON_PRETTY_PRINT));

                Log::info('URL YouTube enregistrée avec succès', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                Logs::ecrireLog($emailUtilisateur, "Téléchargement URL YouTube");

                return redirect()->route('dashboardClientPDF')->with('success', 'URL YouTube enregistrée avec succès.');
            } else {
                Log::warning('URL YouTube non valide', ['email' => $emailUtilisateur, 'youtubeUrl' => $youtubeUrl]);
                return redirect()->back()->with('error', 'URL YouTube non valide.');
            }
        } else {
            Log::warning('Aucune URL YouTube fournie', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Aucune URL YouTube fournie.');
        }

        if ($request->filled('custom_url')) { // URLs personnalisées
            $customUrl = $request->input('custom_url');

            // Passer l'URL à la vue pour l'affichage
            Log::info('URL personnalisée enregistrée avec succès', ['email' => $emailUtilisateur, 'customUrl' => $customUrl]);
            Logs::ecrireLog($emailUtilisateur, "Téléchargement URL personnalisée");

            return view('client.dashboardClientPDF', [
                'carte' => $carte,
                'youtubeUrls' => $youtubeUrls ?? [],
                'idCompte' => $idCompte,
                'customUrl' => $customUrl
            ])->with('success', 'URL personnalisée enregistrée avec succès.');
        }

        Log::warning('Aucune URL fournie', ['email' => $emailUtilisateur]);
        return redirect()->back()->with('error', 'Aucune URL fournie.');
    }

    public function deleteImage($filename)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression d\'image', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $filePath = public_path("entreprises/{$folderName}/images/{$filename}");

        if (File::exists($filePath)) {
            File::delete($filePath);

            Log::info('Image supprimée avec succès', ['email' => $emailUtilisateur, 'filename' => $filename]);
            Logs::ecrireLog($emailUtilisateur, "Suppression Image");

            return redirect()->back()->with('success', 'Image supprimée avec succès.');
        } else {
            Log::warning('Image non trouvée pour la suppression', ['email' => $emailUtilisateur, 'filename' => $filename]);
            return redirect()->back()->with('error', 'Image non trouvée.');
        }
    }

    public function deleteSliderImage(Request $request)
    {
        $idCompte = session('connexion'); // Récupérer l'ID du compte
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression d\'image de slider');
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        Log::info('Carte trouvée : ' . $carte->nomEntreprise);

        // Construire le chemin des images
        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";
        $sliderImagesPath = public_path("entreprises/{$folderName}/slider");

        Log::info('Chemin des images slider : ' . $sliderImagesPath);

        // Vérifier si le dossier existe
        if (!File::exists($sliderImagesPath)) {
            Log::error('Répertoire slider inexistant : ' . $sliderImagesPath);
            return redirect()->back()->with('error', 'Aucune image trouvée.');
        }

        // Récupérer la liste des fichiers
        $sliderImages = File::files($sliderImagesPath);
        $sliderImages = array_map(function ($file) {
            return $file->getFilename(); // Retourner uniquement les noms des fichiers
        }, $sliderImages);

        // Récupérer le nom du fichier de la requête
        $filename = $request->input('filename');
        Log::info('Nom du fichier demandé pour suppression : ' . $filename);

        // Vérifier si le fichier demandé existe dans le slider
        if (in_array($filename, $sliderImages)) {
            $filePath = "{$sliderImagesPath}/{$filename}";
            Log::info('Chemin complet de l\'image : ' . $filePath);

            // Vérifier si le fichier existe réellement avant suppression
            if (File::exists($filePath)) {
                File::delete($filePath); // Supprimer le fichier

                Log::info('Image de slider supprimée avec succès', ['filename' => $filename]);
                return redirect()->back()->with('success', 'Image de slider supprimée avec succès.');
            }

            Log::warning('Fichier trouvé dans la liste mais inexistant ou inaccessible : ' . $filePath);
        }

        Log::error('Image non trouvée dans la liste des fichiers du slider.');
        return redirect()->back()->with('error', 'Image non trouvée.');
    }

    public function deletePdf()
    {

        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Remplacez 'path/to/pdf' par le chemin réel du répertoire des fichiers PDF
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

    public function deleteLogo()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression du logo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $logoPathJpg = public_path("entreprises/{$folderName}/logos/logo.jpg");
        $logoPathJpeg = public_path("entreprises/{$folderName}/logos/logo.jpeg");
        $logoPathPng = public_path("entreprises/{$folderName}/logos/logo.png");
        $logoPathSvg = public_path("entreprises/{$folderName}/logos/logo.svg");

        if (File::exists($logoPathJpg)) {
            File::delete($logoPathJpg);
            Log::info('Logo supprimé avec succès', ['email' => $emailUtilisateur, 'logoPath' => $logoPathJpg]);
            Logs::ecrireLog($emailUtilisateur, "Suppression Logo");
        } elseif (File::exists($logoPathJpeg)) {
            File::delete($logoPathJpeg);
            Log::info('Logo supprimé avec succès', ['email' => $emailUtilisateur, 'logoPath' => $logoPathJpeg]);
            Logs::ecrireLog($emailUtilisateur, "Suppression Logo");
        } elseif (File::exists($logoPathPng)) {
            File::delete($logoPathPng);
            Log::info('Logo supprimé avec succès', ['email' => $emailUtilisateur, 'logoPath' => $logoPathPng]);
            Logs::ecrireLog($emailUtilisateur, "Suppression Logo");
        } elseif (File::exists($logoPathSvg)) {
            File::delete($logoPathSvg);
            Log::info('Logo supprimé avec succès', ['email' => $emailUtilisateur, 'logoPath' => $logoPathSvg]);
            Logs::ecrireLog($emailUtilisateur, "Suppression Logo");
        } else {
            Log::warning('Logo non trouvé pour la suppression', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Logo non trouvé.');
        }

        return redirect()->back()->with('success', 'Logo supprimé avec succès.');
    }

    public function deleteVideo($index)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            Log::warning('Carte non trouvée pour la suppression de vidéo', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");

        if (File::exists($videosPath)) {
            $videosData = json_decode(File::get($videosPath), true);

            if (isset($videosData[$index])) {
                unset($videosData[$index]);
                $videosData = array_values($videosData);
                File::put($videosPath, json_encode($videosData, JSON_PRETTY_PRINT));

                Log::info('Vidéo YouTube supprimée avec succès', ['email' => $emailUtilisateur, 'index' => $index]);
                Logs::ecrireLog($emailUtilisateur, "Suppression Vidéo YouTube");

                return redirect()->back()->with('success', 'Vidéo YouTube supprimée avec succès.');
            } else {
                Log::warning('Vidéo YouTube non trouvée pour la suppression', ['email' => $emailUtilisateur, 'index' => $index]);
                return redirect()->back()->with('error', 'Vidéo YouTube non trouvée.');
            }
        } else {
            Log::warning('Fichier de vidéos non trouvé pour la suppression', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Fichier de vidéos non trouvé.');
        }
    }

    public function uploadSlider(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Vérification si la carte existe
        if (!$carte) {
            Log::error("Échec : Aucun compte trouvé pour idCompte : {$idCompte}");
            return redirect()->back()->with('error', 'Compte non trouvé.');
        }

        // Définit le chemin de destination pour les images
        $destinationPath = 'entreprises/' . $idCompte . '_' . Str::slug($carte->nomEntreprise, '_') . '/slider';

        try {
            // Valider le fichier d'image dans la requête
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:2048', // Limite à 2MB et extensions autorisées
            ]);

            // Récupère le fichier de la requête
            $file = $request->file('image');

            // Vérification du type MIME réel
            $realMimeType = $file->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($realMimeType, $allowedMimeTypes)) {
                Log::error("Type MIME invalide : {$realMimeType} pour le fichier {$file->getClientOriginalName()}");
                return redirect()->back()->with('error', 'Type de fichier non autorisé.');
            }

            // Vérification de l'extension finale
            $realExtension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            if (!in_array($realExtension, $allowedExtensions)) {
                Log::error("Extension invalide : {$realExtension} pour le fichier {$file->getClientOriginalName()}");
                return redirect()->back()->with('error', 'Extension de fichier non autorisée.');
            }

            // Crée le dossier cible s'il n'existe pas
            if (!file_exists(public_path($destinationPath))) {
                mkdir(public_path($destinationPath), 0755, true);
                Log::info("Création du dossier : {$destinationPath}");
            }

            // Définit un nom de fichier unique
            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            // Déplace le fichier vers le chemin cible
            $file->move(public_path($destinationPath), $fileName);
            Log::info("Fichier {$fileName} enregistré dans : {$destinationPath}");

            // Enregistrer un log en base de données
            Logs::ecrireLog($carte->compte->email, "Téléchargement Image Slider");

            // Redirige avec un message de succès
            return redirect()->back()->with('success', 'Image téléchargée avec succès.');

        } catch (\Exception $e) {
            // Log de l'erreur
            Log::error("Erreur lors du téléchargement de l'image pour le compte : {$idCompte}. Détails : {$e->getMessage()}");

            // Enregistrer l'erreur en base de données
            Logs::ecrireLog($carte->compte->email, "Erreur Téléchargement Image Slider");

            // Redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors du téléchargement de l\'image.');
        }
    }

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

    public function afficherFormulaireEntreprise()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::where('idCompte', $idCompte)->first();

        if ($carte) {
            $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
        }

        return view('formulaire.formulaireEntreprise', compact('carte', 'compte'));
    }

    public function updateEntreprise(Request $request)
    {
        $request->validate([
            'nomEntreprise' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'mail' => 'required|email|max:255',
            'adresse' => 'required|string|max:255',
        ]);

        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::find($idCompte);

        if (!$carte) {
            Log::warning('Carte non trouvée pour mise à jour des informations de l\'entreprise', ['email' => $emailUtilisateur]);
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $carte->nomEntreprise = $request->nomEntreprise;
        $carte->tel = $request->tel;
        $carte->ville = $request->adresse;

        $nomEntreprise = Carte::where('idCompte', $idCompte)->first()->nomEntreprise;
        if ($nomEntreprise != $request->nomEntreprise) {
            $entrepriseName = Str::slug($request->nomEntreprise, '_');
            $folderName = "{$idCompte}_{$entrepriseName}";
            $oldFolderName = "{$idCompte}_" . Str::slug($nomEntreprise, '_');

            $oldPath = public_path("entreprises/{$oldFolderName}");
            $newPath = public_path("entreprises/{$folderName}");

            if (File::exists($oldPath)) {
                if (File::exists($newPath)) {
                    Log::error('Le dossier avec le nouveau nom existe déjà', ['email' => $emailUtilisateur, 'oldPath' => $oldPath, 'newPath' => $newPath]);
                    return redirect()->back()->with('error', 'Le dossier avec le nouveau nom existe déjà.');
                }

                File::move($oldPath, $newPath);
            } else {
                Log::error('Ancien dossier introuvable', ['email' => $emailUtilisateur, 'oldPath' => $oldPath]);
                return redirect()->back()->with('error', 'Ancien dossier introuvable.');
            }

            $couleur1 = $carte->couleur1;
            $couleur2 = $carte->couleur2;

            $lien = "/entreprises/1_" . $request->nomEntreprise . "/QR_Codes/QR_Code.svg";
            $carte->lienQr = $lien;

            $carte->save();
            Logs::ecrireLog($emailUtilisateur, "Mise à jour du nom de l'entreprise et du lien QR Code");
            Log::info('Mise à jour du nom de l\'entreprise et du lien QR Code', ['email' => $emailUtilisateur, 'nomEntreprise' => $request->nomEntreprise, 'lienQr' => $lien]);
        }

        $carte->save();
        Log::info('Informations de l\'entreprise mises à jour avec succès', ['email' => $emailUtilisateur, 'nomEntreprise' => $request->nomEntreprise, 'tel' => $request->tel, 'adresse' => $request->adresse]);

        $compte->email = $request->mail;
        $compte->save();

        Compte::creerVCard($request->nomEntreprise, $request->tel, $request->mail, $idCompte);
        Logs::ecrireLog($emailUtilisateur, "Création de la VCard");
        Logs::ecrireLog($emailUtilisateur, "Modification Entreprise");
        Log::info('Création de la VCard', ['email' => $emailUtilisateur, 'nomEntreprise' => $request->nomEntreprise, 'tel' => $request->tel, 'mail' => $request->mail]);

        return redirect()->back()->with('success', 'Informations de l\'entreprise mises à jour avec succès.');
    }

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

        // Définir le chemin de destination pour l'enregistrement des fichiers PDF
        $destinationPath = 'entreprises/' . $idCompte . '_' . Str::slug($carte->nomEntreprise, '_') . '/pdf';

        try {
            // Valider l'entrée de la requête
            $request->validate([
                'pdf' => 'required|mimes:pdf', // Ensure the file is a PDF
                'new_name' => 'required|string', // Ensure the new name is a string and is required
            ]);

            // Vérifier si un fichier existe déjà dans le répertoire
            $fullPath = public_path($destinationPath); // Chemin complet
            if (File::exists($fullPath)) {
                // Scanner les fichiers dans le répertoire
                $existingFiles = File::files($fullPath);
                if (count($existingFiles) > 0) {
                    Log::error("Échec : Un fichier existe déjà dans le dossier {$destinationPath}.");
                    return redirect()->back()->with('error', 'Un fichier existe déjà. Supprimez-le avant de télécharger un nouveau fichier.');
                }
            }

            // Créer le dossier cible s'il n'existe pas
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
                Log::info("Création du dossier : {$fullPath}");
            }

            // Récupérer le fichier téléchargé
            $file = $request->file('pdf');

            // Récupérer le nouveau nom fourni (ou définir un nom par défaut)
            $newName = $request->input('new_name');
            $renamedFile = Str::slug($newName, '_') . '.pdf'; // Nommer le fichier dans un format sûr (slug)

            // Déplacer le fichier dans le dossier cible avec son nouveau nom
            $file->move($fullPath, $renamedFile);
            Log::info("Fichier PDF renommé en {$renamedFile} et enregistré dans : {$destinationPath}");

            // Enregistrer une entrée de log en base de données
            Logs::ecrireLog($carte->compte->email, "Téléchargement de PDF - Nom: {$renamedFile}");

            // Enregistrer dans la base de données le nom du lien PDF et le nom du bouton
            $carte->pdf = $destinationPath . '/' . $renamedFile;
            $carte->nomBtnPdf = $newName;
            $carte->save();

            $pdfe = $carte->pdf;
            $carte->lienPdf = $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&&format=svg&text=127.0.0.1:9000/pdf=" . $pdfe;
            $carte->save();

            // Succès : Redirection avec un message de confirmation
            return redirect()->back()->with('success', 'Votre fichier PDF a été téléchargé et renommé avec succès.');

        } catch (\Exception $e) {
            // Gestion des erreurs / Écriture d'un log d'erreur
            Log::error("Erreur lors du téléchargement du PDF pour idCompte : {$idCompte}. Détails : {$e->getMessage()} ");
            Log::debug('Données soumises pour télécharger un PDF : ', $request->all());

            // Enregistrer une erreur en base de données
            Logs::ecrireLog($carte->compte->email, "Erreur lors du téléchargement d'un PDF");

            // Retour avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement du fichier.');
        }
    }
    public function downloadQrCodesPDFColor()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $pdfe = $carte->nomBtnPdf;
        $qrCodesPath = $carte->lienPdf;

        if (!File::exists($qrCodesPath)) {
            return redirect()->back()->with('error', 'Aucun QR Code trouvé.');
        }

        Logs::ecrireLog($emailUtilisateur, "Téléchargement QR Code Couleur");
        Log::info('QR Code downloaded for PDF: ' . $pdfe);
        return response()->download($qrCodesPath, 'QR_Code_Couleur.svg');
    }

    public function downloadQrCodesPDF()
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $pdfe = $carte->nomBtnPdf;
        $qrCodesPath = $carte->lienPdf;

        if (!File::exists($qrCodesPath)) {
            return redirect()->back()->with('error', 'Aucun QR Code trouvé.');
        }

        Logs::ecrireLog($emailUtilisateur, "Téléchargement QR Code");
        Log::info('QR Code downloaded for PDF: ' . $pdfe);
        return response()->download($qrCodesPath, 'QR_Code.svg');
    }

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

        Custom_Link::create([
            'nom' => $request->input('nom'),
            'lien' => $request->input('lien'),
            'activer' => 0,
            'idCarte' => $carte->idCarte
        ]);

        Log::info('Lien personnalisé ajouté avec succès', ['email' => $emailUtilisateur, 'nom' => $request->input('nom'), 'lien' => $request->input('lien')]);
        Logs::ecrireLog($emailUtilisateur, "Ajout de lien personnalisé");

        return redirect()->back()->with('success', 'Lien personnalisé ajouté avec succès.');
    }

    public function updateSocialLinkCustom(Request $request)
    {
        $idCompte = session('connexion');
        $emailUtilisateur = Compte::find($idCompte)->email; // Récupérer l'email de l'utilisateur connecté

        $customLink = Custom_Link::where('id_link', $request->id_link)->first();

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
        $carte->save(); // Save the changes to the database

        Log::info('Police mise à jour avec succès', ['email' => $emailUtilisateur, 'font' => $request->font]);
        Logs::ecrireLog($emailUtilisateur, "Mise à jour de la police");

        return redirect()->back()->with('success', 'Police mise à jour avec succès.');
    }

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
