<?php
    // ================================================
    // PHP image browser - iBrowser 
    // ================================================
    // iBrowser - language file: Czech
    // Translated by Tomas Vaverka (Pche)
    // ================================================
    // Developed: net4visions.com
    // Copyright: net4visions.com
    // License: GPL - see license.txt
    // (c)2005 All rights reserved.
    // ================================================
    // Revision: 1.1                   Date: 17/02/2006
    // ================================================
    
    //-------------------------------------------------------------------------
    // charset to be used in dialogs
    // pouzita znakova sada
    $lang_charset = 'windows-1250';
    // text direction for the current language to be used in dialogs
    // smer textu v danem jazyce
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
        'im_004' => 'Vitajte',
        'im_005' => 'Vlo�it',
        'im_006' => 'Storno',
        'im_007' => 'Vlo�i�',        
        'im_008' => 'Vlo�it/Zmeni�',
        'im_009' => 'Vlastnosti',
        'im_010' => 'Vlastnosti obr�zku',
        'im_013' => 'Vyskakovacie okn�',
        'im_014' => 'Obr�zok vo vyskakovacom okne;',
        'im_015' => 'O programe',
        'im_016' => 'Sekcie',
        'im_097' => 'Chvi�ku strpenia, nahr�vam...',
        'im_098' =>    'Otvori� sekciu',
        'im_099' => 'Zatvori� sekciu',
        //-------------------------------------------------------------------------
        // insert/change screen - in    
        'in_001' => 'Vlo�i�/Zmeni� obr�zok',
        'in_002' => 'Kni�nica',
        'in_003' => 'Vyberte kni�nicu obr�zkov',
        'in_004' => 'Obr�zky',
        'in_005' => 'N�h�ad',
        'in_006' => 'Zmaza� obr�zok',
        'in_007' => 'Kliknite pre zv��enie obr�zku',
        'in_008' => 'Otvori� upload obr�zku, premenovanie, alebo zmazanie sekcie',    
        'in_009' => 'Inform�cie',
        'in_010' => 'Vyskakovacie okno',        
        'in_013' => 'Vytvorenie odkazu na obr�zok otv�ran� v novom okne.',
        'in_014' => 'Odstrani� odkaz na vyskakovacie okno',    
        'in_015' => 'S�bor',    
        'in_016' => 'Premenova�',
        'in_017' => 'Premenova� obr�zok',
        'in_018' => 'Upload',
        'in_019' => 'Uploadova� obr�zok',    
        'in_020' => 'Ve�kos�(i)',
        'in_021' => 'Za�krtnite po�adovan� ve�kosti pre upload obr�zkov',
        'in_022' => 'Origin�l',
        'in_023' => 'Obr�zok bude orezan�',
        'in_024' => 'Zmaza�',
        'in_025' => 'Adres�r',
        'in_026' => 'Kliknite pre vytvorenie adres�ra',
        'in_027' => 'Vytvori� adres�r',
        'in_028' => '��rka',
        'in_029' => 'V�ka',
        'in_030' => 'Typ',
        'in_031' => 'Ve�kos�',
        'in_032' => 'Meno',
        'in_033' => 'Vytvoren�',
        'in_034' => 'Zmenen�',
        'in_035' => 'Inform�cie o obr�zku',
        'in_036' => 'Kliknite na obr�zok pre zazatvorenie okna',
        'in_037' => 'Oto�it',
        'in_038' => 'Automatick� oto�enie: nastavi� na EXIF inform�cie, pre pou�itie EXIF orient�cie ulo�en� fotoapar�tom. M��e by� tie� nastaven� na +180&deg; alebo -180&deg; pre obr�zok na ��rku, alebo +90&deg; alebo -90&deg; pre obr�zok na v�ku. Kladn� hodnoty pre posun v smere hodinov�ch ru�i�iek, z�porn� proti smeru.',
        'in_041' => '',
        'in_042' => '�iadny',        
        'in_043' => 'na v�ku',
        'in_044' => '+ 90&deg;',    
        'in_045' => '- 90&deg;',
        'in_046' => 'na ��rku',    
        'in_047' => '+ 180&deg;',    
        'in_048' => '- 180&deg;',
        'in_049' => 'fotoapar�t',    
        'in_050' => 'exif inform�cie',
        'in_051' => 'POZOR: Tento obr�zok je dynamick� n�h�ad vytvoren� iManagerom - parametre bud� straten� pri zmmene obr�zku.',
        'in_052' => 'Kliknite pre zmenu n�h�adu vybran�ho obr�zka',
        'in_053' => 'N�hodn�',
        'in_054' => 'Ak je za�krtnut�, bude vybrn� n�hodn� obr�zok',
        'in_055' => 'Za�krtnite pre vlo�enie n�hodn�ho obr�zku',
        'in_056' => 'Parametre',
        'in_057' => 'Kliknite pre nastavenie v�chodz�ch parametrov',
        'in_099' => 'v�chodzie',    
        //-------------------------------------------------------------------------
        // properties, attributes - at
        'at_001' => 'Vlastnosti obr�zku',
        'at_002' => 'Zdroj',
        'at_003' => 'Titulok',
        'at_004' => 'TITLE - titulok obr�zku, zobraz� sa po prejden� my�ou nad obr�zok',
        'at_005' => 'Popis',
        'at_006' => 'ALT -  alternat�vny text obr�zku, zobraz� sa pri nena��tan� obr�zku',
        'at_007' => '�t�l',
        'at_008' => 'Uistite sa, �e zadan� �t�l existuje vo va�ej defin�cii �t�lov.',
        'at_009' => 'CSS-�t�l',    
        'at_010' => 'Atribu�y',
        'at_011' => 'Atribu�y \'umiestnenie\', \'okraj\', \'horiz_medzera\' a \'vert_medzera\' elementu IMAGE nies� podporovan� v XHTML 1.0 Strict DTD. Pou�ite namiesto toho CSS �t�ly.',
        'at_012' => 'Zarovnanie',    
        'at_013' => 'v�chodzie',
        'at_014' => 'v�avo',
        'at_015' => 'vpravo',
        'at_016' => 'nahor',
        'at_017' => 'doprostred',
        'at_018' => 'dole',
        'at_019' => 'stred obr�zku zarovnan� so stredom textu',
        'at_020' => 'vrch obr�zku zarovnan� s vrchom textu',
        'at_021' => 'na �iaru',        
        'at_022' => 've�kos�',
        'at_023' => '��rka',
        'at_024' => 'V�ka',
        'at_025' => 'R�m�ek',
        'at_026' => 'V-odsadenie',
        'at_027' => 'H-odsadenie',
        'at_028' => 'N�h�ad',    
        'at_029' => 'Kliknite pre vlo�enie �peci�lnych znakov do po�a titulku',
        'at_030' => 'Kliknite pre vlo�enie �peci�lnych znakov do po�a popisu',
        'at_031' => 'Nastavi� v�chodzie rozmery obr�zku',
        'at_032' => 'Z�hlavie',
        'at_033' => 'za�krtnut�: nastavi� z�hlavie obr�zku / neza�krtnut�: bez z�hlavia alebo zru�enie z�hlavia',
        'at_034' => 'nastavi� z�hlavie obr�zku',
        'at_099' => 'v�chodzie',    
        //-------------------------------------------------------------------------        
        // error messages - er
        'er_001' => 'Chyba',
        'er_002' => 'Nie je vybran� obr�zok!',
        'er_003' => '��rka nie je ��slo',
        'er_004' => 'V�ka nie je ��slo',
        'er_005' => 'R�m�ek nie je ��slo',
        'er_006' => 'Horizont�lne odsadenie nie je ��slo',
        'er_007' => 'Vertik�lne odsadenie nie je ��slo',
        'er_008' => 'Kliknite na OK pre zmazanie obr�zku',
        'er_009' => 'Premenovanie n�h�adu nie je dovolen�! Premenujte obr�zok, ak chcete premenovat jeho n�h�ad.',
        'er_010' => 'Kliknite na OK pre premenovanie obr�zku na',
        'er_011' => 'Nov� meno je pr�zdne, alebo nebolo zmenen�!',
        'er_014' => 'Zadajte nov� meno s�boru!',
        'er_015' => 'Zadajte validn� meno s�boru!',
        'er_016' => 'N�h�ad nie je k dispoz�cii! Pre zapnutie n�h�adov nastavte ve�kost n�h�adov v konfigura�nom s�bore.',
        'er_021' => 'Kliknite na OK pre upload obr�zku.',
        'er_022' => 'Upload obr�zku - pros�m vydr�te...',
        'er_023' => 'Nebol vybran� �iadnz obr�zok, alebo nebol ozna�en� �iadnz s�bor.',
        'er_024' => 's�bor',
        'er_025' => 'u� existuje! Kliknite na OK pre prep�sanie...',
        'er_026' => 'zadajte nov� meno s�boru!',
        'er_027' => 'Adres�r fyzicky neexistuje',
        'er_028' => 'Do�lo k chybe pri obsluhe uploadu s�boru. Sk�ste to pros�m znovu.',
        'er_029' => 'Naplatn� typ obrazov�ho s�boru',
        'er_030' => 'Mazanie zlyhalo! Sk�ste to pros�m znovu.',
        'er_031' => 'Prep�sa�',
        'er_032' => 'N�h�ad skuto�nej ve�kosti funguje len pre obr�zky v���ch rozmerov ako okno n�h�adu',
        'er_033' => 'Premenovanie s�boru zlyhalo! Sk�ste to pros�m znovu.',
        'er_034' => 'Vytvoren� adres�re zlyhalo! Sk�ste to pros�m znovu.',
        'er_035' => 'Zv��enie nie je podporovan�!',
        'er_036' => 'Chyba pri vytv�ran� zoznamu obr�zkov!',
      ),      
      //-------------------------------------------------------------------------
      // symbols
        'symbols'        => array (
        'title'         => 'Symboly',
        'ok'             => 'OK',
        'cancel'         => 'Storno',
      ),      
    )
?>
