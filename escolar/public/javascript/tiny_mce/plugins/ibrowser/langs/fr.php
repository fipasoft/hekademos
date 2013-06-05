<?php
    // ================================================
    // PHP image browser - iBrowser
    // ================================================
    // iBrowser - language file: French
    // ================================================
    // Developed: net4visions.com
    // Copyright: net4visions.com
    // License: GPL - see license.txt
    // (c)2005 All rights reserved.
    // ================================================
     // Revision: 1.2                   Date: 07/05/2006
    // Contributor     Simon BILLETTE (simon@billette.fr)
    // ================================================

    //-------------------------------------------------------------------------
    // charset to be used in dialogs
    $lang_charset = 'iso-8859-1';
    // text direction for the current language to be used in dialogs
    $lang_direction = 'ltr';
    //-------------------------------------------------------------------------

    // language text data array
    // first dimension - block, second - exact phrase
    //-------------------------------------------------------------------------
    // iBrowser
    $lang_data = array (
        'ibrowser' => array (
        //-------------------------------------------------------------------------
        // common - im
        'im_001' => 'Image browser',
        'im_002' => 'iBrowser',
        'im_003' => 'Menu',
        'im_004' => 'Bienvenue',
        'im_005' => 'Ins�rer',
        'im_006' => 'Annuler',
        'im_007' => 'Ins�rer',
        'im_008' => 'Ins�rer/Changer',
        'im_009' => 'Propri�t�s',
        'im_010' => 'Propri�t�s de l\\\'image',
        'im_013' => 'Popup',
        'im_014' => 'Image en popup',
        'im_015' => 'A propos de iBrowser',
        'im_016' => 'Section',
        'im_097' => 'Chargement en cours...',
        'im_098' =>    'Ouvrir la section',
        'im_099' => 'Fermer la section',
        //-------------------------------------------------------------------------
        // insert/change screen - in
        'in_001' => 'Ins�rer/Changer une image',
        'in_002' => 'Librairie',
        'in_003' => 'Selectionner une image de la librairie',
        'in_004' => 'Images',
        'in_005' => 'Preview',
        'in_006' => 'Supprimer cette image',
        'in_007' => 'Cliquer pour voir cette image en taille r�elle',
        'in_008' => 'Ouvrir la section de mise en ligne, de renommage et de suppression des images',
        'in_009' => 'Information',
        'in_010' => 'Popup',
        'in_013' => 'Cr�er une lien vers cette image pour l\'ouvrir dans une nouvelle fen�tre.',
        'in_014' => 'supprimer le lien popup',
        'in_015' => 'Fichier',
        'in_016' => 'Renommer',
        'in_017' => 'Renommer cette image',
        'in_018' => 'Uploader',
        'in_019' => 'Uploader une image',
        'in_020' => 'Taille(s)',
        'in_021' => 'Cocher la ou les taille(s) d�sir�e(s) pendant l\'upload de l\'image.',
        'in_022' => 'Original',
        'in_023' => 'Cette image sera d�coupp�e',
        'in_024' => 'Supprimer',
        'in_025' => 'R�pertoire',
        'in_026' => 'Cliquer pour cr�er un r�pertoire',
        'in_027' => 'Cr�er un r�pertoire',
        'in_028' => 'Largeur',
        'in_029' => 'Hauteur',
        'in_030' => 'Type',
        'in_031' => 'Taille',
        'in_032' => 'Nom',
        'in_033' => 'Cr��e',
        'in_034' => 'Modifi�e',
        'in_035' => 'Info image',
        'in_036' => 'Cliquer sur cette image pour fermer la fen�tre',
        'in_037' => 'Pivoter',
        'in_038' => 'Autorotation: en fonction des informations exif fournies par l\'appareil photo. Peut aussi �tre fix� � +180&deg; ou -180&deg; pour l\'afichage paysage, ou +90&deg; ou -90&deg; pour l\'affiche portrait. Les valeurs seront positives pour le sens des aiguilles d\'une montre et n�gatives pour le sens contraire des aiguilles d\'une montre.',
        'in_041' => '',
        'in_042' => 'aucun',
        'in_043' => 'portrait',
        'in_044' => '+ 90&deg;',
        'in_045' => '- 90&deg;',
        'in_046' => 'paysage',
        'in_047' => '+ 180&deg;',
        'in_048' => '- 180&deg;',
        'in_049' => 'camera',
        'in_050' => 'info exif',
        'in_051' => 'AVERTISSEMENT: Cette image est une vignette dynamique cr��e par iBrowser - Les param�tres seront perdus lors de la modification de cette image.',
        'in_052' => 'Cliquer pour basculer la vue des images',
        'in_053' => 'Al�atoire',
        'in_054' => 'Si coch�, une image al�atoirement choisie sera ins�r�e',
        'in_055' => 'Cocher pour ins�rer une image al�atoire',
        'in_056' => 'Param�tres',
        'in_057' => 'cliquer pour r�initialiser les param�tres � leurs valeurs d\'origine',
        'in_099' => 'd�faut',
        //-------------------------------------------------------------------------
        // properties, attributes - at
        'at_001' => 'Attributs de cette image',
        'at_002' => 'Source',
        'at_003' => 'Titre',
        'at_004' => 'TITRE - affiche la description de cette image en onmouseover',
        'at_005' => 'Description',
        'at_006' => 'ALT -  texte de remplacement pour cette image, il sera affich� � la place de l\'image si celle-ci n\'apparait pas',
        'at_007' => 'Style',
        'at_008' => 'Veuillez v�rifier que le style s�lectionn� existe dans votre feuille de style !',
        'at_009' => 'Style CSS',
        'at_010' => 'Attributs',
        'at_011' => 'Les attributs \'align\', \'border\', \'hspace\', et \'vspace\' ne sont pas support�s pour les images en XHTML 1.0 Strict DTD. Veuillez plut�t utiliser les propri�t�s CSS.',
        'at_012' => 'Alignement',
        'at_013' => 'par d�faut',
        'at_014' => 'gauche',
        'at_015' => 'droite',
        'at_016' => 'haut',
        'at_017' => 'milieu',
        'at_018' => 'bas',
        'at_019' => 'bas absolu',
        'at_020' => 'texttop',
        'at_021' => 'baseline',
        'at_022' => 'Taille',
        'at_023' => 'Largeur',
        'at_024' => 'Hauteur',
        'at_025' => 'Bordure',
        'at_026' => 'V-space',
        'at_027' => 'H-space',
        'at_028' => 'Preview',
        'at_029' => 'Cliquer pour entrer des caract�res sp�ciaux dans le champs titre',
        'at_030' => 'Cliquer pour entrer des caract�res sp�ciaux dans le champs description',
        'at_031' => 'R�initialiser les dimensions � leures valeurs initiales',
        'at_032' => 'Sous Titre',
        'at_033' => 'coch�: d�finir le sous-titre de l\'image / d�coch�: aucun sous-titre pour l\'image d�fini ou suppression du sous-titre',
        'at_034' => 'd�finir le sous-titre de l\'image',
        'at_099' => 'par d�faut',
        //-------------------------------------------------------------------------
        // error messages - er
        'er_001' => 'Erreur',
        'er_002' => 'Aucune image s�lectionn�e!',
        'er_003' => 'La largeur n\'est pas un nombre',
        'er_004' => 'La hauteur n\'est pas un nombre',
        'er_005' => 'La bordure n\'est pas un nombre',
        'er_006' => 'L\'espace horizontal n\'est pas un nombre',
        'er_007' => 'L\'espace vertical n\'est pas un nombre',
        'er_008' => 'Cliquer sur OK pour supprimer l\\\'image',
        'er_009' => 'Le renommage des vignettes n\'est pas autoris�! Veuillez renommer l\'image originale si vous souhaitez renommer la vignette.',
        'er_010' => 'Cliquer sur OK pour renommer l\\\'image en',
        'er_011' => 'Le nouveau nom est vide ou n\\\'a pas chang�!',
        'er_014' => 'Veuillez entrer un nouveau nom de fichier!',
        'er_015' => 'Veuillez entre un nom de fichier valide!',
        'er_016' => 'Vignette non disponible! Veuillez sp�cifier la taille des vignettes dans le fichier de configuration pour activer la fonctionnalit�.',
        'er_021' => 'Cliquer sur OK pour uploader la ou les image(s).',
        'er_022' => 'Upload de l\'image en cours - veuillez patienter ...',
        'er_023' => 'Aucune image n\\\'a �t� s�lectionn�e ou aucune taille de fichier n\\\'a �t� coch�e.',
        'er_024' => 'Fichier',
        'er_025' => 'existe d�j�! Veuiller cliquer sur OK pour remplacer le fichier ...',
        'er_026' => 'Veuillez entrer le nouveau nom de fichier!',
        'er_027' => 'Le r�pertoire n\'existe pas physiquement!',
        'er_028' => 'Une erreur a �t� rencontr�e lors de l\'upload du fichier. Veuillez recommencer.',
        'er_029' => 'Mauvais type d\'image',
        'er_030' => 'Echec de la suppression! Veuillez recommencer.',
        'er_031' => 'Remplacer',
        'er_032' => 'La visualisation en taille r�elle ne fonctionne que pour les images dont la taille est sup�rieure � celle de la vignette',
        'er_033' => 'Echec du renommage du fichier! Veuillez recommencer.',
        'er_034' => 'Echec de la cr�ation du r�pertoire! Veuillez recommencer.',
        'er_035' => 'Augmenter la taille n\\\'est pas autoris�!',
        'er_036' => 'Erreur lors de la cr�ation de la liste d\'image(s)!',
      ),
      //-------------------------------------------------------------------------
      // symbols
        'symbols'        => array (
        'title'         => 'Symbols',
        'ok'             => 'OK',
        'cancel'         => 'Annuler',
      ),
    )
?>