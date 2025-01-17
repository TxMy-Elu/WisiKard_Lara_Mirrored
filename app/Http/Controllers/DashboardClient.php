<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Compte;
use App\Models\Employer;
use App\Models\Logs;
use App\Models\Message;
use App\Models\Rediriger;
use App\Models\Social;
use App\Models\Vue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardClient extends Controller
{
    public function afficherDashboardClient(Request $request)
    {
        // Récupérer l'id du compte connecté
        $idCompte = session('connexion');

        // Recueperer les informations de la carte
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Recueperer les informations du compte
        $compte = Compte::where('idCompte', $idCompte)->first();

        // Récupérer le message
        $message = Message::where('afficher', true)->orderBy('id', 'desc')->first();
        $messageContent = $message ? $message->message : 'Aucun message disponible';

        // Formater le numéro de téléphone
        if ($carte) {
            $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
        }

        // couleur
        $couleur1 = $carte->couleur1;
        $couleur2 = $carte->couleur2;

        return view('client.dashboardClient', compact('messageContent', 'carte', 'compte', 'couleur1', 'couleur2'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }



    public function employer(Request $request)
    {
        $idCompte = session('connexion');
        $search = $request->input('search');

        // Récupérer les employés associés à la carte du compte connecté avec la relation carte
        $employes = Employer::with('carte')->join('carte', 'employer.idCarte', '=', 'carte.idCarte')
            ->where('carte.idCompte', $idCompte)
            ->when($search, function ($query, $search) {
                return $query->where('employer.nom', 'like', "%{$search}%")
                    ->orWhere('employer.prenom', 'like', "%{$search}%")
                    ->orWhere('employer.fonction', 'like', "%{$search}%");
            })
            ->select('employer.*')
            ->get();

        // Vérif si des résultats sont trouvés
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

    public function ajoutEmployer(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'idCarte' => 'required|integer'
        ]);

        // Créer un nouvel employé
        Employer::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'idCarte' => $request->idCarte
        ]);

        // Récupérer l'email du compte pour les logs
        $compte = Compte::find($request->idCarte);
        if ($compte) {
            $emailUtilisateur = $compte->email;
            // Écrire dans les logs
            Logs::ecrireLog($emailUtilisateur, "Ajout Employe");
        }

        return redirect()->back()->with('success', 'L\'employé a été ajouté avec succès.');
    }

    public function destroy($id)
    {
        try {
            $employer = Employer::findOrFail($id);
            $idCarte = $employer->idCarte;
            $employer->delete();

            $compte = Compte::find($idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                Logs::ecrireLog($emailUtilisateur, "Suppression Employe");
            }

            return redirect()->route('dashboardClientEmploye', ['idCarte' => $idCarte])->with('success', 'L\'employé a été supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'employé.');
        }
    }


    public function social()
    {
        $idCompte = session('connexion');
        $idCarte = Carte::where('idCompte', $idCompte)->first()->idCarte;

        // Récupérer tous les réseaux sociaux
        $allSocial = Social::all();

        // Récupérer les réseaux sociaux activés pour l'entreprise
        $activatedSocial = Rediriger::where('idCarte', $idCarte)
            ->join('social', 'rediriger.idSocial', '=', 'social.idSocial')
            ->select('social.idSocial', 'rediriger.activer', 'rediriger.lien')
            ->get();

        // Créer un tableau associatif pour les réseaux sociaux activés
        $activatedSocialArray = [];
        foreach ($activatedSocial as $social) {
            $activatedSocialArray[$social->idSocial] = ['activer' => $social->activer, 'lien' => $social->lien];
        }

        return view('client.dashboardClientSocial', [
            'allSocial' => $allSocial,
            'activatedSocial' => $activatedSocialArray,
            'idCarte' => $idCarte // Passez la variable $idCarte à la vue
        ]);
    }

    public function updateSocialLink(Request $request)
    {
        $request->validate([
            'idSocial' => 'required|integer',
            'idCarte' => 'required|integer',
            'lien' => 'nullable|url'
        ]);

        // Vérifiez si un enregistrement existe déjà
        $rediriger = Rediriger::where('idSocial', $request->idSocial)
            ->where('idCarte', $request->idCarte)
            ->first();

        if ($rediriger) {
            // Mettre à jour le lien existant
            $rediriger->lien = $request->lien;
            $rediriger->activer = $request->has('activer') ? 1 : 0; // Activer ou désactiver en fonction de la présence du champ
            $rediriger->save();
        } else {
            // Créer un nouvel enregistrement
            Rediriger::create([
                'idSocial' => $request->idSocial,
                'idCarte' => $request->idCarte,
                'lien' => $request->lien,
                'activer' => $request->has('activer') ? 1 : 0 // Activer par défaut si le champ est présent
            ]);
        }

        return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
    }
public function statistique(Request $request)
{
    $session = session('connexion');

    // Récupérer l'année, la semaine et le mois à partir de la requête
    $year = $request->query('year', date('Y'));
    $selectedWeek = $request->input('week', date('W')); // Utiliser la semaine actuelle par défaut
    $selectedMonth = $request->input('month', date('n')); // Utiliser le mois actuel par défaut

    // Récupérer l'idCarte associé au compte connecté
    $idCarte = Carte::where('idCompte', $session)->first()->idCarte;

    // Données annuelles
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

    // Données annuelles par employé
    $employerViews = Vue::selectRaw('employer.nom as nom, COUNT(*) as count')
        ->join('employer', 'vue.idEmp', '=', 'employer.idEmp')
        ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
        ->whereYear('date', $year)
        ->where('carte.idCarte', $idCarte)
        ->groupBy('nom')
        ->pluck('count', 'nom')
        ->toArray();

    // Génération de couleurs aléatoires pour les graphiques
    $colors = [];
    foreach ($employerViews as $key => $value) {
        do {
            $r = mt_rand(0, 255);
            $g = mt_rand(0, 255);
            $b = mt_rand(0, 255);
        } while (($r > 200 && $g < 100 && $b > 200) || ($r < 100 && $g > 200 && $b < 100)); // Exclude pink and green
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

    // Nombre total de vues en fonction de l'année et de l'idCarte
    $totalViewsCard = Vue::whereYear('date', $year)
        ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
        ->where('carte.idCompte', $session)
        ->count();

    // Nombre de vues par semaine
    $weeklyViewsQuery = Vue::selectRaw('WEEK(date, 1) as week, COUNT(*) as count')
        ->whereYear('date', $year)
        ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
        ->where('carte.idCompte', $session)
        ->groupBy('week');

    $weeklyViews = $weeklyViewsQuery->pluck('count', 'week')->toArray();

    // Nombre de vues par mois
    $monthlyViewsQuery = Vue::selectRaw('MONTH(date) as month, COUNT(*) as count')
        ->whereYear('date', $year)
        ->join('carte', 'vue.idCarte', '=', 'carte.idCarte')
        ->where('carte.idCompte', $session)
        ->groupBy('month');

    $monthlyViews = $monthlyViewsQuery->pluck('count', 'month')->toArray();

    // Années disponibles pour la sélection
    $years = range(date('Y'), date('Y') - 10);
    $selectedYear = $year;

    // Mois disponibles pour la sélection
    $months = range(1, 12);
    $selectedMonth = $selectedMonth;

    if ($request->ajax()) {
        return response()->json([
            'totalViewsCard' => $totalViewsCard,
            'monthlyViews' => $monthlyViews,
            'weeklyViews' => $weeklyViews,
            'selectedMonth' => $selectedMonth,
            'selectedWeek' => $selectedWeek,
            'employerData' => $employerData,
        ]);
    }

    return view('client.dashboardClientStatistique', compact('yearlyData', 'years', 'selectedYear', 'totalViewsCard', 'weeklyViews', 'selectedWeek', 'monthlyViews', 'selectedMonth', 'months', 'employerData'));
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

            // Récupérer l'email du compte pour les logs
            $compte = Compte::find($employe->idCarte);
            if ($compte) {
                $emailUtilisateur = $compte->email;
                // Écrire dans les logs
                Logs::ecrireLog($emailUtilisateur, "Modification Employe");
            }

            return redirect()->route('dashboardClientEmploye')->with('success', 'L\'employé a été modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'employé.');
        }
    }

    public function afficherDashboardClientPDF()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        // Définir le nom de l'entreprise
        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        // Lire les URLs YouTube enregistrées
        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        return view('client.dashboardClientPDF', compact('carte', 'youtubeUrls', 'idCompte'));
    }

   public function uploadFile(Request $request)
   {
       $request->validate([
           'file' => 'nullable|file|mimes:mp4,pdf,jpg,jpeg,png',
           'youtube_url' => 'nullable|url',
           'logo' => 'nullable|file|mimes:jpg,jpeg,png'
       ]);

       $idCompte = session('connexion');
       $carte = Carte::where('idCompte', $idCompte)->first();

       if (!$carte) {
           return redirect()->back()->with('error', 'Carte non trouvée.');
       }

       // Définir le nom de l'entreprise
       $entrepriseName = Str::slug($carte->nomEntreprise, '_');
       $folderName = "{$idCompte}_{$entrepriseName}";

       if ($request->hasFile('file')) { // Fichiers MP4
           $file = $request->file('file');
           $fileType = $file->getClientOriginalExtension();
           $mimeType = $file->getMimeType();

           // Vérifier le type MIME et l'extension
           if (($fileType === 'mp4' && $mimeType === 'video/mp4') ||
               ($fileType === 'pdf' && $mimeType === 'application/pdf') ||
               ($fileType === 'jpg' && $mimeType === 'image/jpeg') ||
               ($fileType === 'jpeg' && $mimeType === 'image/jpeg') ||
               ($fileType === 'png' && $mimeType === 'image/png')) {

               $filePath = '';

               switch ($fileType) {
                   case 'mp4':
                       $filePath = public_path("entreprises/{$folderName}/videos");
                       break;
                   case 'pdf':
                       $filePath = public_path("entreprises/{$folderName}/pdf");
                       break;
                   case 'jpg':
                   case 'jpeg':
                   case 'png':
                       $filePath = public_path("entreprises/{$folderName}/images");
                       break;
                   default:
                       return redirect()->back()->with('error', 'Type de fichier non supporté.');
               }

               if (!File::exists($filePath)) {
                   File::makeDirectory($filePath, 0755, true);
               }

               $fileName = time() . '.' . $fileType;
               $file->move($filePath, $fileName);

               return redirect()->back()->with('success', 'Fichier téléchargé avec succès.');
           } else {
               return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
           }
       }

       if ($request->hasFile('logo')) { // Logos
           $logo = $request->file('logo');
           $logoType = $logo->getClientOriginalExtension();
           $mimeType = $logo->getMimeType();

           // Vérifier le type MIME et l'extension
           if (($logoType === 'jpg' && $mimeType === 'image/jpeg') ||
               ($logoType === 'jpeg' && $mimeType === 'image/jpeg') ||
               ($logoType === 'png' && $mimeType === 'image/png')) {

               $logoPath = public_path("entreprises/{$folderName}/logos");

               if (!File::exists($logoPath)) {
                   File::makeDirectory($logoPath, 0755, true);
               }

               $logoFileName = 'logo.' . $logoType;
               $logo->move($logoPath, $logoFileName);

               return redirect()->back()->with('success', 'Logo téléchargé avec succès.');
           } else {
               return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
           }
       }

       if ($request->filled('youtube_url')) { // URLs YouTube
           $youtubeUrl = $request->input('youtube_url');
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

           return redirect()->back()->with('success', 'URL YouTube enregistrée avec succès.');
       }

       return redirect()->back()->with('error', 'Aucun fichier ou URL YouTube fourni.');
   }


    public function updateColor(Request $request)
    {
        $request->validate([
            'couleur1' => 'required|string|max:7',
            'couleur2' => 'required|string|max:7'
        ]);

        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $carte->couleur1 = $request->couleur1;
        $carte->couleur2 = $request->couleur2;
        $carte->save();

        Compte::QrCode($idCompte, $carte->nomEntreprise);

        return redirect()->back()->with('success', 'Couleurs mises à jour avec succès.');
    }

    public function downloadQrCodesColor()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();


        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Définir le nom de l'entreprise
        $entrepriseName = $carte->nomEntreprise;
        $folderName = "{$idCompte}_{$entrepriseName}";

        $qrCodesPath = public_path("entreprises/{$folderName}/QR_Codes/QR_Code.svg");

        if (!File::exists($qrCodesPath)) {
            return redirect()->back()->with('error', 'Aucun QR Code trouvé.');
        }


        return response()->download($qrCodesPath, 'QR_Code_Couleur.svg');
    }

    public function downloadQrCodes()
    {
        $idCompte = session('connexion');

        $url = "https://quickchart.io/qr?size=300&dark=000000&light=FFFFFF&&format=svg&text=127.0.0.1:9000/Templates?idCompte=" . $idCompte;

        return response()->streamDownload(function () use ($url) {
            echo file_get_contents($url);
        }, 'QR_Code.svg');

    }
    public function deleteImage($filename)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Définir le nom de l'entreprise
        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $filePath = public_path("entreprises/{$folderName}/images/{$filename}");

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->back()->with('success', 'Image supprimée avec succès.');
        } else {
            return redirect()->back()->with('error', 'Image non trouvée.');
        }
    }
    public function deleteLogo()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        // Définir le nom de l'entreprise
        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $logoPathJpg = public_path("entreprises/{$folderName}/logos/logo.jpg");
        $logoPathJpeg = public_path("entreprises/{$folderName}/logos/logo.jpeg");
        $logoPathPng = public_path("entreprises/{$folderName}/logos/logo.png");

        if (File::exists($logoPathJpg)) {
            File::delete($logoPathJpg);
        } elseif (File::exists($logoPathJpeg)) {
            File::delete($logoPathJpeg);
        } elseif (File::exists($logoPathPng)) {
            File::delete($logoPathPng);
        } else {
            return redirect()->back()->with('error', 'Logo non trouvé.');
        }

        return redirect()->back()->with('success', 'Logo supprimé avec succès.');
    }

   public function deleteVideo($index)
     {
         $idCompte = session('connexion');
         $carte = Carte::where('idCompte', $idCompte)->first();

         if (!$carte) {
             return redirect()->back()->with('error', 'Carte non trouvée.');
         }

         // Définir le nom de l'entreprise
         $entrepriseName = Str::slug($carte->nomEntreprise, '_');
         $folderName = "{$idCompte}_{$entrepriseName}";

         $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");

         if (File::exists($videosPath)) {
             $videosData = json_decode(File::get($videosPath), true);

             if (isset($videosData[$index])) {
                 unset($videosData[$index]);
                 $videosData = array_values($videosData); // Réindexer le tableau
                 File::put($videosPath, json_encode($videosData, JSON_PRETTY_PRINT));
                 return redirect()->back()->with('success', 'Vidéo YouTube supprimée avec succès.');
             } else {
                 return redirect()->back()->with('error', 'Vidéo YouTube non trouvée.');
             }
         } else {
             return redirect()->back()->with('error', 'Fichier de vidéos non trouvé.');
         }
     }

     public function uploadSlider(Request $request)
     {
         $request->validate([
             'slider_image' => 'required|file|mimes:jpg,jpeg,png',
         ]);

         $idCompte = session('connexion');
         $carte = Carte::where('idCompte', $idCompte)->first();

         if (!$carte) {
             return redirect()->back()->with('error', 'Carte non trouvée.');
         }

         // Définir le nom de l'entreprise
         $entrepriseName = Str::slug($carte->nomEntreprise, '_');
         $folderName = "{$idCompte}_{$entrepriseName}";

         $sliderImage = $request->file('slider_image');
         $sliderImageType = $sliderImage->getClientOriginalExtension();
         $mimeType = $sliderImage->getMimeType();

         // Vérifier le type MIME et l'extension
         if (($sliderImageType === 'jpg' && $mimeType === 'image/jpeg') ||
             ($sliderImageType === 'jpeg' && $mimeType === 'image/jpeg') ||
             ($sliderImageType === 'png' && $mimeType === 'image/png')) {

             $sliderPath = public_path("entreprises/{$folderName}/slider");

             if (!File::exists($sliderPath)) {
                 File::makeDirectory($sliderPath, 0755, true);
             }

             $sliderFileName = time() . '.' . $sliderImageType;
             $sliderImage->move($sliderPath, $sliderFileName);

             return redirect()->back()->with('success', 'Image de slider téléchargée avec succès.');
         } else {
             return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
         }
     }

     public function afficherSlider()
     {
         $idCompte = session('connexion');
         $carte = Carte::where('idCompte', $idCompte)->first();

         if (!$carte) {
             return redirect()->back()->with('error', 'Carte non trouvée.');
         }

         // Définir le nom de l'entreprise
         $entrepriseName = Str::slug($carte->nomEntreprise, '_');
         $folderName = "{$idCompte}_{$entrepriseName}";

         $sliderPath = public_path("entreprises/{$folderName}/slider");

         if (File::exists($sliderPath)) {
             $sliderImages = File::files($sliderPath);
             return view('client.dashboardClientPDF', compact('sliderImages', 'carte', 'idCompte'));
         } else {
             return view('client.dashboardClientPDF', compact('carte', 'idCompte'));
         }
     }

     public function deleteSliderImage($filename)
     {
         $idCompte = session('connexion');
         $carte = Carte::where('idCompte', $idCompte)->first();

         if (!$carte) {
             return redirect()->back()->with('error', 'Carte non trouvée.');
         }

         // Définir le nom de l'entreprise
         $entrepriseName = Str::slug($carte->nomEntreprise, '_');
         $folderName = "{$idCompte}_{$entrepriseName}";

         $sliderPath = public_path("entreprises/{$folderName}/slider/{$filename}");

         if (File::exists($sliderPath)) {
             File::delete($sliderPath);
             return redirect()->back()->with('success', 'Image de slider supprimée avec succès.');
         } else {
             return redirect()->back()->with('error', 'Image de slider non trouvée.');
         }
     }
 }


