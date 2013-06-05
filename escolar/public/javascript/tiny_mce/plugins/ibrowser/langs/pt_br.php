<?php
    // ================================================
    // PHP image browser - iBrowser 
    // ================================================
    // iBrowser - language file: Portug�s - Brasil
    // ================================================
    // Developed: net4visions.com
    // Copyright: net4visions.com
    // License: GPL - see license.txt
    // (c)2005 All rights reserved.
    // ================================================
    // Revision: 1.1                   Date: 26/07/2006
    // Por Ronaldo Chevalier - www.rcsigns.com.br
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
        'im_001' => 'Visualiza��o de Imagem',
        'im_002' => 'iBrowser',
        'im_003' => 'Menu',
        'im_004' => 'Bem Vindo',
        'im_005' => 'Inserir',
        'im_006' => 'Cancelar',
        'im_007' => 'Inserir',        
        'im_008' => 'Inserir/trocar',
        'im_009' => 'Propriedades',
        'im_010' => 'Propriedades da Imagem',
        'im_013' => 'Janela Popup',
        'im_014' => 'Imagem em popup',
        'im_015' => 'Sobre iBrowser',
        'im_016' => 'Se��o',
        'im_097' => 'Por favor aguarde enquanto carrega...',
        'im_098' =>    'Abrir se��o',
        'im_099' => 'Fechar se��o',
        //-------------------------------------------------------------------------
        // insert/change screen - in    
        'in_001' => 'Inserir/trocar imagem',
        'in_002' => 'Biblioteca',
        'in_003' => 'Selecione uma imagem da biblioteca',
        'in_004' => 'Imagens',
        'in_005' => 'Visualiza��o',
        'in_006' => 'Deletar imagem',
        'in_007' => 'Clique para visualizar a imagem em tamanho maior',
        'in_008' => 'Abrir a imagem carregada, renomear ou deletar se��o',    
        'in_009' => 'Informa��o',
        'in_010' => 'Janela Popup',        
        'in_013' => 'Criar um link para uma imagem ser aberta em nova janela.',
        'in_014' => 'Remover link popup',    
        'in_015' => 'Aquivo',    
        'in_016' => 'Renomear',
        'in_017' => 'Renomear imagem',
        'in_018' => 'Carregar',
        'in_019' => 'Carregar imagem',    
        'in_020' => 'Tamanho(s)',
        'in_021' => 'Marque o(s) tamanho(s) desejado para criar enquanto a(s) imagem(ns) � carregada',
        'in_022' => 'Original',
        'in_023' => 'A Imagem ser� cortada',
        'in_024' => 'Deletar',
        'in_025' => 'Diret�rio',
        'in_026' => 'Clique para criar um diret�rio',
        'in_027' => 'Crie um diret�rio',
        'in_028' => 'Largura',
        'in_029' => 'Altura',
        'in_030' => 'Tipo',
        'in_031' => 'Tamanho',
        'in_032' => 'Nome',
        'in_033' => 'Criado',
        'in_034' => 'Modificado',
        'in_035' => 'Informa��o da Imagem',
        'in_036' => 'Clique na imagem para fechar a janela',
        'in_037' => 'Rotacionar',
        'in_038' => 'Rotacionar Autom�tico: ajuste a informa��o do exif, para usar a orienta��o pelo EXIF armazenado pela c�mera. Voc� pode ajustar tamb�m para +180&deg; ou -180&deg; para tipo paisagem, ou +90&deg; ou -90&deg; para retrato. Valores positivos para sentido hor�rio e valores negativos para sentido anti-hor�rio.',
        'in_041' => '',
        'in_042' => 'Nenhum',        
        'in_043' => 'Retrato',
        'in_044' => '+ 90&deg;',    
        'in_045' => '- 90&deg;',
        'in_046' => 'Paisagem',    
        'in_047' => '+ 180&deg;',    
        'in_048' => '- 180&deg;',
        'in_049' => 'C�mera',    
        'in_050' => 'exif info',
        'in_051' => 'AVISO: A imagem atual � uma miniatura criada din�micamente pelo iManager - os par�metros ser� perdidos na troca da imagem.',
        'in_052' => 'Clique para visualizar outra imagem',
        'in_053' => 'Aleat�rio',
        'in_054' => 'Se marcado, uma imagem aleat�ria ser� inserida',
        'in_055' => 'Marque para inserir uma imagem aleat�ria',
        'in_056' => 'Par�metros',
        'in_057' => 'Clique para voltar os par�metros para seus valores padr�o',
        'in_099' => 'Padr�o',    
        //-------------------------------------------------------------------------
        // properties, attributes - at
        'at_001' => 'Atributos da Imagem',
        'at_002' => 'C�digo',
        'at_003' => 'T�tulo',
        'at_004' => 'T�tulo - mostrar descri��o da imagem quando o mouse estiver em cima',
        'at_005' => 'Descri��o',
        'at_006' => 'ALT - recoloca��o textual para a imagem, para ser indicado ou usado no lugar da imagem ',
        'at_007' => 'Estilo',
        'at_008' => 'Por favor, tenha certeza que o estilo selecionado existe na sua folha de estilos!',
        'at_009' => 'Estilos CSS',    
        'at_010' => 'Atributos',
        'at_011' => 'Os \'align\', \'border\', \'hspace\', and \'vspace\' atributos dos elementos da imagem n�o s�o suportados pelo XHTML 1.0 Strict DTD. Por favor use o estilo CSS dispon�vel.',
        'at_012' => 'Alinhamento',    
        'at_013' => 'padr�o',
        'at_014' => 'esquerda',
        'at_015' => 'direita',
        'at_016' => 'topo',
        'at_017' => 'meio',
        'at_018' => 'base',
        'at_019' => 'absmeio',
        'at_020' => 'texttop',
        'at_021' => 'linha de base',        
        'at_022' => 'Tamanho',
        'at_023' => 'Largura',
        'at_024' => 'Altura',
        'at_025' => 'Borda',
        'at_026' => 'Espa�o Vertical',
        'at_027' => 'Espa�o Horizontal',
        'at_028' => 'Visualizar',    
        'at_029' => 'Clique para inserir caracteres especiais no campo de t�tulo',
        'at_030' => 'Clique para inserir caracteres especiais no campo descri��o',
        'at_031' => 'Voltar dimens�es da imagem � seus valores padr�o',
        'at_032' => 'Subt�tulo',
        'at_033' => 'marcado: ajustar subt�tulo da imagem / desmarcado: sem subt�tulo ou limpar subt�tulo da imagem',
        'at_034' => 'Ajustar subt�tulo da imagem',
        'at_099' => 'padr�o',    
        //-------------------------------------------------------------------------        
        // error messages - er
        'er_001' => 'Erro',
        'er_002' => 'Nenhuma imagem selecionada!',
        'er_003' => 'Largura n�o � n�mero',
        'er_004' => 'Altura n�o � n�mero',
        'er_005' => 'Borda n�o � n�mero',
        'er_006' => 'Espa�o Horizontal n�o � n�mero',
        'er_007' => 'Espa�o Vertical n�o � n�mero',
        'er_008' => 'Clique em OK para deletar a imagem',
        'er_009' => 'Renomear miniatura n�o est� dispon�vel! Por favor renomeie a imagem principal se quiser renomear a miniatura.',
        'er_010' => 'Cliqu OK renomear a imagem',
        'er_011' => 'O novo nome est� vazio ou n�o foi alterado!',
        'er_014' => 'Entre com um novo nome para o arquivo!',
        'er_015' => 'Entre com um novo v�lido!',
        'er_016' => 'Miniaturas n�o dispon�vel! Ajuste o tamanho da miniatura no arquivo de configura��o para habilitar.',
        'er_021' => 'Clique em OK to carregar a imagem(ns).',
        'er_022' => 'Carregando imagem - por favor aguarde...',
        'er_023' => 'Nenhuma imagem foi selecionada ou nenhum tamanho de arquivo foi marcado.',
        'er_024' => 'Arquivo',
        'er_025' => 'Este arquivo j� existe! Clique em OK para regravar o arquivo...',
        'er_026' => 'Entre com um novo nome!',
        'er_027' => 'Pasta destino n�o existe fisicamente',
        'er_028' => 'Ocorreu um erro enquanto carregava o arquivo. Por favor tente novamente.',
        'er_029' => 'Tipo de imagem inv�lido',
        'er_030' => 'Falha para deletar o arquivo! Por favor tente novamente.',
        'er_031' => 'Regravado',
        'er_032' => 'Visualiza��o em tamanho maior somente funciona para imagens maiores que o tamanho visualizado.',
        'er_033' => 'Renomear o arquivo falhou! Por favor tente novamente.',
        'er_034' => 'Criar pasta falhou! Please try again.',
        'er_035' => 'Aumentar n�o est� dispon�vel!',
        'er_036' => 'Erro construindo lista de imagens!',
      ),      
      //-------------------------------------------------------------------------
      // symbols
        'symbols'        => array (
        'title'         => 'Symbolos',
        'ok'             => 'OK',
        'cancel'         => 'Cancelar',
      ),      
    )
?>