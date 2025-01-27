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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DashboardClient extends Controller
{
    public function afficherDashboardClient(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::where('idCompte', $idCompte)->first();
        $message = Message::where('afficher', true)->orderBy('id', 'desc')->first();
        $messageContent = $message ? $message->message : 'Aucun message disponible';

        if ($carte) {
            $carte->formattedTel = $this->formatPhoneNumber($carte->tel);
        }

        $couleur1 = $carte->couleur1;
        $couleur2 = $carte->couleur2;
        $titre = $carte->titre;
        $description = $carte->descriptif;
        $idTemplate = Carte::where('idCompte', $idCompte)->first()->idTemplate;

        return view('client.dashboardClient', compact('messageContent', 'carte', 'compte', 'couleur1', 'couleur2', 'titre', 'description', 'idTemplate'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        return preg_replace("/(\d{2})(?=\d)/", "$1.", $phoneNumber);
    }

    public function employer(Request $request)
    {
        $idCompte = session('connexion');
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

    public function ajoutEmployer(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
        ]);

        Employer::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'idCarte' => $request->idCarte
        ]);

        $compte = Compte::find($request->idCarte);
        if ($compte) {
            $emailUtilisateur = $compte->email;
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
        $request->validate([
            'idSocial' => 'required|integer',
            'idCarte' => 'required|integer',
            'lien' => 'nullable|url'
        ]);

        $rediriger = Rediriger::where('idSocial', $request->idSocial)
            ->where('idCarte', $request->idCarte)
            ->first();

        if ($rediriger) {
            $rediriger->lien = $request->lien;
            $rediriger->activer = $request->has('activer') ? 1 : 0;
            $rediriger->save();
        } else {
            Rediriger::create([
                'idSocial' => $request->idSocial,
                'idCarte' => $request->idCarte,
                'lien' => $request->lien,
                'activer' => $request->has('activer') ? 1 : 0
            ]);
        }

        return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
    }

    public function statistique(Request $request)
    {
        $session = session('connexion');
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

        public function afficherDashboardClientPDF()
    {
        $idCompte = session('connexion');
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
            $images = array_map(function($file) {
                return $file->getFilename();
            }, $images);
        }

        $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");
        $youtubeUrls = [];
        if (File::exists($videosPath)) {
            $youtubeUrls = json_decode(File::get($videosPath), true);
        }

        return view('client.dashboardClientPDF', compact('carte', 'images', 'folderName', 'idCompte', 'youtubeUrls'));
    }

    public function uploadFile(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
    
        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }
    
        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";
    
        if ($request->hasFile('file')) { // PDF
            $file = $request->file('file');
            $fileType = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
    
            if ($fileType === 'pdf' && $mimeType === 'application/pdf') {
                $pdfPath = public_path("entreprises/{$folderName}/pdf");
    
                if (!File::exists($pdfPath)) {
                    File::makeDirectory($pdfPath, 0755, true);
                }
    
                $existingPdf = File::files($pdfPath);
                if (!empty($existingPdf)) {
                    return redirect()->back()->with('error', 'Vous ne pouvez enregistrer plus de 1 PDF.');
                }
    
                $fileName = time() . '.' . $fileType;
                $file->move($pdfPath, $fileName);
    
                return redirect()->route('dashboardClientPDF')->with('success', 'Fichier PDF téléchargé avec succès.');
            } else {
                return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
            }
        } else {
            return redirect()->back()->with('error', 'Aucun fichier téléchargé.');
        }
    }
    public function uploadLogo(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
    
        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }
    
        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";
        $logoPath = public_path("entreprises/{$folderName}/logos");
    
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileType = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
    
            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'svg']) && strpos($mimeType, 'image/') === 0) {
                // Supprimer l'ancien logo s'il existe
                $existingLogo = File::files($logoPath);
                if (!empty($existingLogo)) {
                    foreach ($existingLogo as $logoFile) {
                        File::delete($logoFile->getPathname());
                    }
                }
    
                if (!File::exists($logoPath)) {
                    File::makeDirectory($logoPath, 0755, true);
                }
    
                $fileName = "logo.{$fileType}";
                $file->move($logoPath, $fileName);
    
                // Mettre à jour la base de données avec le nouveau chemin du logo
                $carte->imgLogo = "entreprises/{$folderName}/logos/{$fileName}";
                $carte->save();
    
                return redirect()->route('dashboardClientPDF')->with('success', 'Logo téléchargé avec succès.');
            } else {
                return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
            }
        } else {
            return redirect()->back()->with('error', 'Aucun fichier téléchargé.');
        }
    }
    

    public function urlsrdv(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();
    
        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }
    
        if ($request->filled('rdv_url')) { // URL RDV
            $rdvUrl = $request->input('rdv_url');
    
            // Vérifier si l'URL contient "http" ou "https"
            if (preg_match('/^https?:\/\//', $rdvUrl)) {
                $carte->lienCommande = $rdvUrl;
                $carte->save();
    
                return redirect()->route('dashboardClientPDF')->with('success', 'URL Rdv enregistrée avec succès.');
            } else {
                return redirect()->back()->with('error', 'L\'URL doit commencer par http ou https.');
            }
        }
    
        return redirect()->back()->with('error', 'Aucune URL fournie.');
    }
    
    public function uploadImage(Request $request)
     {
         $idCompte = session('connexion');
         $carte = Carte::where('idCompte', $idCompte)->first();

         if (!$carte) {
             return redirect()->back()->with('error', 'Carte non trouvée.');
         }

         $entrepriseName = Str::slug($carte->nomEntreprise, '_');
         $folderName = "{$idCompte}_{$entrepriseName}";

         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $imageType = $image->getClientOriginalExtension();
             $mimeType = $image->getMimeType();

             // Vérifier si le type de fichier est valide
             if (in_array($imageType, ['jpg', 'jpeg', 'png']) && strpos($mimeType, 'image/') === 0) {
                 $imagePath = public_path("entreprises/{$folderName}/images");

                 if (!File::exists($imagePath)) {
                     File::makeDirectory($imagePath, 0755, true);
                 }

                 $imageFileName = time() . '.' . $imageType;
                 $image->move($imagePath, $imageFileName);

                 return redirect()->route('dashboardClientPDF')->with('success', 'Image téléchargée avec succès.');
             } else {
                 return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
             }
         } else {
             return redirect()->back()->with('error', 'Aucun fichier image téléchargé.');
         }
     }

    public function uploadYouTubeVideo(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
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

                return redirect()->route('dashboardClientPDF')->with('success', 'URL YouTube enregistrée avec succès.');
            } else {
                return redirect()->back()->with('error', 'URL YouTube non valide.');
            }
        } else {
            return redirect()->back()->with('error', 'Aucune URL YouTube fournie.');
        }
    }
   public function deleteImage($filename)
   {
       $idCompte = session('connexion');
       $carte = Carte::where('idCompte', $idCompte)->first();

       if (!$carte) {
           return redirect()->back()->with('error', 'Carte non trouvée.');
       }

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

    public function deleteSliderImage(Request $request)
    {
        $filenames = json_decode($request->input('filenames'), true);
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        foreach ($filenames as $filename) {
            $sliderPath = public_path("entreprises/{$folderName}/slider/{$filename}");
            if (File::exists($sliderPath)) {
                File::delete($sliderPath);
            }
        }

        return redirect()->back()->with('success', 'Images de slider supprimées avec succès.');
    }

    public function deletePDF($filename)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $filePath = public_path("entreprises/{$folderName}/pdf/{$filename}");

        if (File::exists($filePath)) {
            File::delete($filePath);

            // Mettre à jour les champs pdf et nomBtnPdf dans la table carte
            $carte->pdf = null;
            $carte->nomBtnPdf = null;
            $carte->save();

            return redirect()->back()->with('success', 'PDF supprimée avec succès.');
        } else {
            return redirect()->back()->with('error', 'PDF non trouvée.');
        }
    }

        public function deleteLogo()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
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
        } elseif (File::exists($logoPathJpeg)) {
            File::delete($logoPathJpeg);
        } elseif (File::exists($logoPathPng)) {
            File::delete($logoPathPng);
        } elseif (File::exists($logoPathSvg)) {
            File::delete($logoPathSvg);
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

       $entrepriseName = Str::slug($carte->nomEntreprise, '_');
       $folderName = "{$idCompte}_{$entrepriseName}";

       $videosPath = public_path("entreprises/{$folderName}/videos/videos.json");

       if (File::exists($videosPath)) {
           $videosData = json_decode(File::get($videosPath), true);

           if (isset($videosData[$index])) {
               unset($videosData[$index]);
               $videosData = array_values($videosData);
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
            'slider_images' => 'required|array',
            'slider_images.*' => 'file|mimes:jpg,jpeg,png',
        ]);

        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $entrepriseName = Str::slug($carte->nomEntreprise, '_');
        $folderName = "{$idCompte}_{$entrepriseName}";

        $sliderPath = public_path("entreprises/{$folderName}/slider");

        if (!File::exists($sliderPath)) {
            File::makeDirectory($sliderPath, 0755, true);
        }

        $existingImages = File::files($sliderPath);
        if (count($existingImages) >= 10) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas télécharger plus de 10 images pour le slider.');
        }

        foreach ($request->file('slider_images') as $sliderImage) {
            $sliderImageType = $sliderImage->getClientOriginalExtension();
            $mimeType = $sliderImage->getMimeType();

            if (($sliderImageType === 'jpg' && $mimeType === 'image/jpeg') ||
                ($sliderImageType === 'jpeg' && $mimeType === 'image/jpeg') ||
                ($sliderImageType === 'png' && $mimeType === 'image/png')) {

                $nextNumber = $this->getNextIncrementalNumber($sliderPath);
                $sliderFileName = "{$nextNumber}_slider.{$sliderImageType}";
                $sliderImage->move($sliderPath, $sliderFileName);
            } else {
                return redirect()->back()->with('error', 'Type de fichier ou extension non valide.');
            }
        }

        return redirect()->back()->with('success', 'Image(s) de slider téléchargée(s) avec succès.');
    }

    private function getNextIncrementalNumber($folderPath)
    {
        $files = File::files($folderPath);
        $maxNumber = 0;

        foreach ($files as $file) {
            $fileName = $file->getFilename();
            if (preg_match("/^(\d+)_/", $fileName, $matches)) {
                $number = (int)$matches[1];
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
        }
        return $maxNumber + 1;
    }

    public function afficherSlider()
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

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

    public function updateInfo(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'descriptif' => 'required|string|max:255',
        ]);

        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $carte->titre = $request->titre;
        $carte->descriptif = $request->descriptif;
        $carte->save();

        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }

    public function refreshQrCodeEmp($id, $idEmp)
    {
        $compte = Compte::find($id);
        $carte = Carte::where('idCompte', $compte->idCompte)->first();
        (new Employer)->QrCode($id, $carte->nomEntreprise, $idEmp);

        return redirect()->route('dashboardClientEmploye')->with('success', 'QR Code rafraîchi avec succès.');
    }

    public function afficherFormulaireEntreprise()
    {
        $idCompte = session('connexion');
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
        $carte = Carte::where('idCompte', $idCompte)->first();
        $compte = Compte::find($idCompte);

        if (!$carte) {
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
                    return redirect()->back()->with('error', 'Le dossier avec le nouveau nom existe déjà.');
                }

                File::move($oldPath, $newPath);
            } else {
                return redirect()->back()->with('error', 'Ancien dossier introuvable.');
            }

            $couleur1 = $carte->couleur1;
            $couleur2 = $carte->couleur2;

            $lien = "/entreprises/1_" . $request->nomEntreprise . "/QR_Codes/QR_Code.svg";
            $carte->lienQr = $lien;

            $carte->save();
        }

        $carte->save();

        $compte->email = $request->mail;
        $compte->save();

        Compte::creerVCard($request->nomEntreprise, $request->tel, $request->mail, $idCompte);

        return redirect()->back()->with('success', 'Informations de l\'entreprise mises à jour avec succès.');
    }

    public function updateTemplate(Request $request)
    {
        $idCompte = session('connexion');
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
        }

        $carte->save();

        return redirect()->back()->with('success', 'Template mis à jour avec succès.');
    }
    public function renamePdf(Request $request)
    {
        $currentFilename = $request->input('currentFilename');
        $newFilename = $request->input('newFilename');
        $idCarte = $request->input('idCarte');

        $carte = Carte::find($idCarte);

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $currentPath = public_path("entreprises/{$idCarte}_{$carte->nomEntreprise}/pdf/{$currentFilename}");
        $newPath = public_path("entreprises/{$idCarte}_{$carte->nomEntreprise}/pdf/{$newFilename}");
        $PathPdf = ("entreprises/{$idCarte}_{$carte->nomEntreprise}/pdf/{$newFilename}");

        if (File::exists($currentPath)) {
            File::move($currentPath, $newPath);

            $carte->nomBtnPdf = $newFilename;
            $carte->pdf = $PathPdf;
            $carte->save();

            return redirect()->back()->with('success', 'Fichier renommé avec succès.');
        } else {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }
    }

    public function updateCustomLink(Request $request)
    {
        $session = session('connexion');

        if (!$session) {
            return redirect()->back()->withErrors(['error' => 'La session utilisateur est expirée ou invalide.']);
        }

        $carte = Carte::where('idCompte', $session)->first();

        if (!$carte) {
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

        return redirect()->back()->with('success', 'Lien personnalisé ajouté avec succès.');
    }

    public function updateSocialLinkCustom(Request $request)
    {
        $customLink = Custom_Link::where('id_link', $request->id_link)->first();

        if ($customLink) {
            $customLink->lien = $request->lien;
            $customLink->activer = $request->has('activer') ? 1 : 0;
            $customLink->save();
        }

        return redirect()->back()->with('success', 'Lien mis à jour avec succès.');
    }


    public function updateFont(Request $request)
    {
        $idCompte = session('connexion');
        $carte = Carte::where('idCompte', $idCompte)->first();

        if (!$carte) {
            return redirect()->back()->with('error', 'Carte non trouvée.');
        }

        $carte->font = $request->font;
        $carte->save(); // Save the changes to the database

        return redirect()->back()->with('success', 'Police mise à jour avec succès.');
    }


}
