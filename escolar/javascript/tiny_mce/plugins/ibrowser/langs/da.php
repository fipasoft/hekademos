<?php
    // ================================================
    // PHP image browser - iBrowser
    // ================================================
    // iBrowser - language file: English
    // ================================================
    // Developed: net4visions.com
    // Copyright: net4visions.com
    // License: GPL - see license.txt
    // (c)2005 All rights reserved.
    // ================================================
    // Revision: 1.1                   Date: 05/25/2005
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
        'im_001' => 'Billed browser',
        'im_002' => 'iBrowser',
        'im_003' => 'Menu',
        'im_004' => 'Velkommen',
        'im_005' => 'Inds�t',
        'im_006' => 'Afbryd',
        'im_007' => 'Inds�t',
        'im_008' => 'Inds�t/rediger',
        'im_009' => 'Egenskaber',
        'im_010' => 'Billed egenskaber',
        'im_013' => 'Popup',
        'im_014' => 'Billed popup',
        'im_015' => 'Om iBrowser',
        'im_016' => 'Afsnit',
        'im_097' => 'Please wait while loading...',
        'im_098' => '�ben afsnit',
        'im_099' => 'Luk afsnit',
        //-------------------------------------------------------------------------
        // insert/change screen - in
        'in_001' => 'Inds�t/rediger billede',
        'in_002' => 'Mapper',
        'in_003' => 'V�lg en billed mappe',
        'in_004' => 'Billeder',
        'in_005' => 'Preview',
        'in_006' => 'Slet billede',
        'in_007' => 'Klik for st�rre billede',
        'in_008' => '�ben billed upload, omd�b, eller slet sektion',
        'in_009' => 'Information',
        'in_010' => 'Popup',
        'in_013' => 'Opret et link s�ledes billedet �bnes i et ny vindue.',
        'in_014' => 'Fjern popup link',
        'in_015' => 'File',
        'in_016' => 'Omd�b',
        'in_017' => 'Omd�b billede',
        'in_018' => 'Upload',
        'in_019' => 'Upload billede',
        'in_020' => 'St�rrelse(r)',
        'in_021' => 'Marker den �nskede st�rrelse(r) som billed(er) skal uploadesi',
        'in_022' => 'Original',
        'in_023' => 'Billedet vil blive besk�ret',
        'in_024' => 'Slet',
        'in_025' => 'Folder',
        'in_026' => 'Klik for at oprette en ny mappe',
        'in_027' => 'Opret en mappe',
        'in_028' => 'Brede',
        'in_029' => 'H�jde',
        'in_030' => 'Type',
        'in_031' => 'St�rrelse',
        'in_032' => 'Navn',
        'in_033' => 'Oprettet',
        'in_034' => '�ndret',
        'in_035' => 'Billed info',
        'in_036' => 'Klik p� billedet for at lukke vinduet',
        'in_037' => 'Roter',
        'in_038' => 'Auto roter: sat til exif info, for at bruge EXIF orientering 
gemt af kamera. Kan ogs� s�ttes til +180&grader; eller -180&grader; for 
landskab, eller +90&grader; eller -90&grader; for portr�t. Positive v�rdier 
for med-uret og negative v�rdier for mod-uret.',
        'in_041' => '',
        'in_042' => 'intet',
        'in_043' => 'portr�t',
        'in_044' => '+ 90&grader;',
        'in_045' => '- 90&grader;',
        'in_046' => 'landskab',
        'in_047' => '+ 180&grader;',
        'in_048' => '- 180&grader;',
        'in_049' => 'Kamera',
        'in_050' => 'exif info',
        'in_051' => 'ADVARSEL: F�lgende billede er et dynamisk thumbnail oprettet 
af iManager - parameterne vil g� tabet hvis billedet �ndres.',
        'in_052' => 'Switch image selection view',
        'in_099' => 'grundindstilling',
        //-------------------------------------------------------------------------
        // properties, attributes - at
        'at_001' => 'Billed egenskaber',
        'at_002' => 'Kilde',
        'at_003' => 'Titel',
        'at_004' => 'TITEL - Viser billed beskrivelse ved mouse-over',
        'at_005' => 'Beskrivelse',
        'at_006' => 'ALT -  tekst udskiftning for billede, til fremvisning eller 
istedet for billedet',
        'at_007' => 'Style',
        'at_008' => 'Check venligst at den valgte style findes i dit stylesheet!',
        'at_009' => 'CSS-style',
        'at_010' => 'Indstillinger',
        'at_011' => 'P� r�kke\', \'Border\', \'H-afstand\', og \'V-afstand\' 
indstillinger for billede er ikke underst�ttet i XHTML 1.0 Kun DTD. Brug 
CSS-style istedet for.',
        'at_012' => 'P� r�kke',
        'at_013' => 'Grundindstilling',
        'at_014' => 'Venstre',
        'at_015' => 'H�jre',
        'at_016' => 'Top',
        'at_017' => 'Midt i',
        'at_018' => 'Bund',
        'at_019' => 'absolut-midt',
        'at_020' => 'tekst-top',
        'at_021' => 'grundlinie',
        'at_022' => 'St�rrelse',
        'at_023' => 'Brede',
        'at_024' => 'H�jde',
        'at_025' => 'Border',
        'at_026' => 'V-afstand',
        'at_027' => 'H-afstand',
        'at_028' => 'Preview',
        'at_029' => 'Klik for at inds�tte speciale karaktere ind i titel feltet',
        'at_030' => 'Klik for at inds�tte speciale karaktere ind i beskrivelses 
feltet',
        'at_031' => 'Nulstil billed dimensioner til forud valgte v�rdier',
        'at_099' => 'Grundindstillingt',
        //-------------------------------------------------------------------------
        // error messages - er
        'er_001' => 'Fejl',
        'er_002' => 'Der er ikke valgt noget billede !',
        'er_003' => 'Brede er ikke et tal',
        'er_004' => 'H�jde er ikke et tal',
        'er_005' => 'Border er ikke et tal',
        'er_006' => 'Horisontal afstand er ikke et tal',
        'er_007' => 'Vertikal afstand er ikke et tal',
        'er_008' => 'Klik OK for at slette billedet',
        'er_009' => 'Omd�bning af Miniature er ikke tilladt! Omd�b original billedet, hvis du vil have nyt navn for Miniature billedet.',
        'er_010' => 'Klik OK for at omd�be billedet til',
        'er_011' => 'Nyt navn er enten tomt eller ikke �ndret!',
        'er_014' => 'Inds�t nyt file navn!',
        'er_015' => 'Inds�t venligst korrekt navn!',
        'er_016' => 'Miniature er ikke tilg�ngelig! S�t Miniature st�rrelsen i config filen.',
        'er_021' => 'Klik OK for at uploade billede(r) .',
        'er_022' => 'Uploader billede - Vent venligst...',
        'er_023' => 'Der er ikke valgt noget billede eller file st�rrelsen er markeret.',
        'er_024' => 'File',
        'er_025' => 'Eksistere allerede! Klik OK for at overskrive filen...',
        'er_026' => 'Inds�t nyt file navn!',
        'er_027' => 'Mappen eksistere ikke',
        'er_028' => 'Der opstod en fejl under oploadning af filen. Pr�v igen.',
        'er_029' => 'Forkert billed file type',
        'er_030' => 'Fejl ved sletning! Pr�v igen.',
        'er_031' => 'Overskriv',
        'er_032' => 'Fuld st�rrelse preview virker kun for billeder st�rre end preview st�rrelsen',
        'er_033' => 'Fejl ved omd�bning af file! Pr�v igen.',
        'er_034' => 'Fejl ved oprettelse af mappe! Pr�v igen.',
        'er_035' => 'Forst�rrelse er ikke tilladt!',
        'er_036' => 'Fejl ved opbygning af billed liste!',
      ),
      
//-------------------------------------------------------------------------
      // symbols
        'symbols'        => array (
        'title'         => 'Symbols',
        'ok'             => 'OK',
        'cancel'         => 'Fortryd',
      ),
    )
?>
