<?php
    //Nome del server mostrato nella firma (si consiglia di usare un acronimo).
    $server_name = "";

    //Dati di connessione ai servers (per aggiungere un nuovo server basta copiare un blocco e cambiare la chiave
    //con l'acronimo del nuovo server), il campo "$realm_name" sarà visualizzato in firma.
    //Dati di connessione al database del primo server.
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Dati di connessione al database del secondo server (se presente, altrimenti commentare questo blocco).
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Dati di connessione al database del terzo server (se presente, altrimenti commentare questo blocco).
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Dati di connessione al database contenente titoli ed achievements.
    $site_host      = "";
    $site_username  = "";
    $site_password  = "";
    $site_database  = "";

    //Nome dell'immagine mostrata quando i dati inseriti sono errati.
    $dati_errati      = "dati_errati.png";
    $dim_dati_errati  = @filesize("images/$dati_errati"); //Dimensione del file.

    //Nome dell'immagine mostrata quando la firma non viene caricata bene.
    $errore_caricamento      = "errore_caricamento.png";
    $dim_errore_caricamento  = @filesize("images/$errore_caricamento"); //Dimensione del file.

    //Messaggio d'errore stampato in caso non venga inserito il nome del pg nell'apposito campo.
    $error_pg = "Inserire un nome valido di un personaggio!";

    //Link generico all'armory, %s rappresenterà il nome del server, %p il nome del personaggio. (ES: http://miosito/character-sheet.xml?r=MioServer&cn=MioPG)
    $armory_template_link = "http://miosito/character-sheet.xml?r=%s&cn=%p";

    //Tempo in secondi dopo il quale le firme vengono cancellate automaticamente se non usate.
    $image_expire_time = 10 * 60;

    //Dimensioni dell'immagine.
    $x = 500; //Larghezza.
    $y = 70;  //Altezza.

    //Abilitazione ridimensionamento delle immagini (in proporzione) tramite query string.
    $image_resize_enabled = false;

    //Colore dello sfondo di default (rosso).
    $to_img          = false;
    $start_bg_red    = 255;
    $start_bg_green  = 0;
    $start_bg_blue   = 0;

    //Colore del testo di default (oro).
    $text_vet_color[0] = 255; //Graduazione rossa.
    $text_vet_color[1] = 215; //Graduazione verde.
    $text_vet_color[2] = 0;   //Graduazione blu.

    //Modificando questo valore è possibile modificare l'inizio della sfumatura verso il nero.
    $proporzione_sfumatura_y = 2.5;

    //Caratteri.
    //Per aggiungere un nuovo font, copiare il file del font nella cartella "fonts" e aggiungere un nuovo blocco con le sue caratteristiche
    //come descritto sotto.
    //Descrizione campi:
    // - text: nome visualizzato del font,
    // - name: nome del file rappresentante il font (inserire i files nella cartella "fonts"),
    // - nome_pg: grandezza del testo contenente il nome del personaggio in firma,
    // - stats: grandezza del testo contenente le stats in firma,
    // - addictional_info: grandezza del testo contenente le informazioni addizionali (classe, razza, livello, gilda, rank, server) in firma,
    // - x: larghezza dell'anteprima del carattere,
    // - y: altezza dell'anteprima del carattere.
    $fonts["jokerman"]["text"]              = "Jokerman";
    $fonts["jokerman"]["name"]              = "JOKERMAN.TTF";
    $fonts["jokerman"]["nome_pg"]           = 15;
    $fonts["jokerman"]["stats"]             = 7;
    $fonts["jokerman"]["addictional_info"]  = 9;
    $fonts["jokerman"]["x"]                 = 215;
    $fonts["jokerman"]["y"]                 = 50;

    $fonts["morpheus"]["text"]              = "Morpheus";
    $fonts["morpheus"]["name"]              = "MORPHEUS_0.TTF";
    $fonts["morpheus"]["nome_pg"]           = 16;
    $fonts["morpheus"]["stats"]             = 9;
    $fonts["morpheus"]["addictional_info"]  = 10;
    $fonts["morpheus"]["x"]                 = 185;
    $fonts["morpheus"]["y"]                 = 55;

    $fonts["verdana"]["text"]              = "Verdana";
    $fonts["verdana"]["name"]              = "verdanab.ttf";
    $fonts["verdana"]["nome_pg"]           = 15;
    $fonts["verdana"]["stats"]             = 6;
    $fonts["verdana"]["addictional_info"]  = 8;
    $fonts["verdana"]["x"]                 = 210;
    $fonts["verdana"]["y"]                 = 50;

    $fonts["flashd"]["text"]              = "Flash D";
    $fonts["flashd"]["name"]              = "FlashD.ttf";
    $fonts["flashd"]["nome_pg"]           = 20;
    $fonts["flashd"]["stats"]             = 9;
    $fonts["flashd"]["addictional_info"]  = 11;
    $fonts["flashd"]["x"]                 = 190;
    $fonts["flashd"]["y"]                 = 50;

    //Font di default (verdana).
    $font                  = $fonts["verdana"]["name"];
    $dim_nome_pg           = $fonts["verdana"]["nome_pg"];
    $dim_stats             = $fonts["verdana"]["stats"];
    $dim_addictional_info  = $fonts["verdana"]["addictional_info"];

    //Vettore contenente le razze.
    $races[1]   = "Human";
    $races[2]   = "Orc";
    $races[3]   = "Dwarf";
    $races[4]   = "Night Elf";
    $races[5]   = "Undead";
    $races[6]   = "Tauren";
    $races[7]   = "Gnome";
    $races[8]   = "Troll";
    $races[10]  = "Blood Elf";
    $races[11]  = "Draenei";

    //Vettore contenente le classi e il nome dell'immagine della classe.
    $classes[1]["name"]   = "Warrior";
    $classes[1]["img"]    = "180px-Warrior_crest";
    $classes[2]["name"]   = "Paladin";
    $classes[2]["img"]    = "180px-Paladin_crest";
    $classes[3]["name"]   = "Hunter";
    $classes[3]["img"]    = "180px-Hunter_crest";
    $classes[4]["name"]   = "Rogue";
    $classes[4]["img"]    = "180px-Rogue_crest";
    $classes[5]["name"]   = "Priest";
    $classes[5]["img"]    = "180px-Priest_crest";
    $classes[6]["name"]   = "Death Knight";
    $classes[6]["img"]    = "180px-Death_knight_crest";
    $classes[7]["name"]   = "Shaman";
    $classes[7]["img"]    = "180px-Shaman_crest";
    $classes[8]["name"]   = "Mage";
    $classes[8]["img"]    = "180px-Mage_crest";
    $classes[9]["name"]   = "Warlock";
    $classes[9]["img"]    = "180px-Warlock_crest";
    $classes[11]["name"]  = "Druid";
    $classes[11]["img"]   = "180px-Druid_crest";

    //Nomi dei rami talento a partire dalla classe e tab.
    $tab_names[1][0] = "Arms";
    $tab_names[1][1] = "Fury";
    $tab_names[1][2] = "Protection";

    $tab_names[2][0] = "Holy";
    $tab_names[2][1] = "Protection";
    $tab_names[2][2] = "Retribution";

    $tab_names[3][0] = "Beast Mastery";
    $tab_names[3][1] = "Marksmanship";
    $tab_names[3][2] = "Survival";

    $tab_names[4][0] = "Assassination";
    $tab_names[4][1] = "Combat";
    $tab_names[4][2] = "Subtlety";

    $tab_names[5][0] = "Discipline";
    $tab_names[5][1] = "Holy";
    $tab_names[5][2] = "Shadow";

    $tab_names[6][0] = "Blood";
    $tab_names[6][1] = "Frost";
    $tab_names[6][2] = "Unholy";

    $tab_names[7][0] = "Elemental";
    $tab_names[7][1] = "Enhancement";
    $tab_names[7][2] = "Restoration";

    $tab_names[8][0] = "Arcane";
    $tab_names[8][1] = "Fire";
    $tab_names[8][2] = "Frost";

    $tab_names[9][0] = "Affliction";
    $tab_names[9][1] = "Demonology";
    $tab_names[9][2] = "Destruction";

    $tab_names[11][0] = "Balance";
    $tab_names[11][1] = "Feral Combat";
    $tab_names[11][2] = "Restoration";

    //Vettore contenente le stats da stampare,
    //"name" sarà visualizzato nel menu a tendina dell'index,
    //"field_name" sarà il nome del campo da ricercare nel database,
    //"text" è il testo da stampare in firma con il valore della stat al posto di %s.
    $stats["maxhealth"]["name"]                = "Health";
    $stats["maxhealth"]["field_name"]          = "maxhealth";
    $stats["maxhealth"]["text"]                = "Health: %s";
    $stats["mana"]["name"]                     = "Mana";
    $stats["mana"]["field_name"]               = "mana";
    $stats["mana"]["text"]                     = "Mana: %s";
    $stats["rune"]["name"]                     = "Rune";
    $stats["rune"]["field_name"]               = "rune";
    $stats["rune"]["text"]                     = "Rune: %s";
    $stats["runicpower"]["name"]               = "Runic Power";
    $stats["runicpower"]["field_name"]         = "runicPower";
    $stats["runicpower"]["text"]               = "Runic Power: %s";
    $stats["strength"]["name"]                 = "Strength";
    $stats["strength"]["field_name"]           = "strength";
    $stats["strength"]["text"]                 = "Strength: %s";
    $stats["agility"]["name"]                  = "Agility";
    $stats["agility"]["field_name"]            = "agility";
    $stats["agility"]["text"]                  = "Agility: %s";
    $stats["stamina"]["name"]                  = "Stamina";
    $stats["stamina"]["field_name"]            = "stamina";
    $stats["stamina"]["text"]                  = "Stamina: %s";
    $stats["intellect"]["name"]                = "Intellect";
    $stats["intellect"]["field_name"]          = "intellect";
    $stats["intellect"]["text"]                = "Intellect: %s";
    $stats["spirit"]["name"]                   = "Spirit";
    $stats["spirit"]["field_name"]             = "spirit";
    $stats["spirit"]["text"]                   = "Spirit: %s";
    $stats["armor"]["name"]                    = "Armor";
    $stats["armor"]["field_name"]              = "armor";
    $stats["armor"]["text"]                    = "Armor: %s";
    $stats["blockpct"]["name"]                 = "Block Pct";
    $stats["blockpct"]["field_name"]           = "blockPct";
    $stats["blockpct"]["text"]                 = "Block: %s%";
    $stats["dodgepct"]["name"]                 = "Dodge Pct";
    $stats["dodgepct"]["field_name"]           = "dodgePct";
    $stats["dodgepct"]["text"]                 = "Dodge: %s%";
    $stats["parrypct"]["name"]                 = "Parry Pct";
    $stats["parrypct"]["field_name"]           = "parryPct";
    $stats["parrypct"]["text"]                 = "Parry: %s%";
    $stats["critpct"]["name"]                  = "Crit Pct";
    $stats["critpct"]["field_name"]            = "critPct";
    $stats["critpct"]["text"]                  = "Crit Chance: %s%";
    $stats["rangedcritpct"]["name"]            = "Ranged Crit Pct";
    $stats["rangedcritpct"]["field_name"]      = "rangedCritPct";
    $stats["rangedcritpct"]["text"]            = "Ranged Crit: %s%";
    $stats["spellcritpct"]["name"]             = "Spell Crit Pct";
    $stats["spellcritpct"]["field_name"]       = "spellCritPct";
    $stats["spellcritpct"]["text"]             = "Spell Crit: %s%";
    $stats["attackpower"]["name"]              = "Attack Power";
    $stats["attackpower"]["field_name"]        = "attackPower";
    $stats["attackpower"]["text"]              = "Attack Power: %s";
    $stats["rangedattackpower"]["name"]        = "Ranged Attack Power";
    $stats["rangedattackpower"]["field_name"]  = "rangedAttackPower";
    $stats["rangedattackpower"]["text"]        = "Ranged AP: %s";
    $stats["spellpower"]["name"]               = "Spell Power";
    $stats["spellpower"]["field_name"]         = "spellPower";
    $stats["spellpower"]["text"]               = "Spell Power: %s";
    $stats["resilience"]["name"]               = "Resilience";
    $stats["resilience"]["field_name"]         = "resilience";
    $stats["resilience"]["text"]               = "Resilience: %s";
    $stats["talents"]["name"]                  = "Talenti";
    $stats["talents"]["field_name"]            = "talents";
    $stats["talents"]["text"]                  = "Talenti: (%s)";

    //Una volta ogni 24 ore vengono riscritte le informazioni sugli achievements sul file di configurazione in modo da non leggerli sempre da db.
    $to_fill_achievements = false;
    if($file = @fopen("custom/check_achievements.lock", 'r'))
    {
        if(!@feof($file))
        {
            $time = @fgets($file, 255);
            if($time < (@time() - (24*60*60)))
                $to_fill_achievements = true;
        }else $to_fill_achievements = true;
        @fclose($file);
    }else $to_fill_achievements = true;

    if($to_fill_achievements)
    {
        if($file = @fopen("custom/check_achievements.lock", 'w'))
        {
            @fputs($file, @time());
            @fclose($file);
        }

        if($ach_file = @fopen("custom/achievements.php", 'w'))
        {
            @fputs($ach_file, "<?php\r\n");

            //Numero totale di achievements ottenibili (estratto da db).
            $num_ach = 0;
            if($conn = @mysql_connect($site_host, $site_username, $site_password, true))
            {
                if(@mysql_select_db($site_database, $conn))
                    if($result = @mysql_query("SELECT COUNT(*) AS numero FROM achievement WHERE points <> 0;", $conn))
                    {
                        if($row = @mysql_fetch_array($result, MYSQL_ASSOC)) //Seleziono solo gli achievements ottenibili (cioè quelli che danno punti) e li inserisco nel vettore $achievements.
                        {
                            $num_ach = $row["numero"];
                            @fputs($ach_file, "    \$num_achievements = $num_ach;\r\n\r\n");
                        }
                        @mysql_free_result($result);
                    }
                @mysql_close($conn);
            }

            //Se ci sono achievements inserisco le ultime 2 voci di configurazione delle stats.
            if($num_ach)
            {
                @fputs($ach_file, "    \$stats[\"achievements\"][\"name\"]             = \"Achievements\";\r\n");
                @fputs($ach_file, "    \$stats[\"achievements\"][\"field_name\"]       = \"achievements\";\r\n");
                @fputs($ach_file, "    \$stats[\"achievements\"][\"text\"]             = \"Ach.: %s/\$num_achievements\"; //Achievements ottenuti / Achievements ottenibili.\r\n");
                @fputs($ach_file, "    \$stats[\"achievementpoints\"][\"name\"]        = \"Achievement Points\";\r\n");
                @fputs($ach_file, "    \$stats[\"achievementpoints\"][\"field_name\"]  = \"achievementPoints\";\r\n");
                @fputs($ach_file, "    \$stats[\"achievementpoints\"][\"text\"]        = \"Ach. Points: %s\";\r\n");
            }

            @fputs($ach_file, "?>");

            @fclose($file);
        }
    }

    if(@file_exists("custom/achievements.php"))
        include("custom/achievements.php");
?>
<?php
    //Codice che cancella automaticamente tutte le immagini non usate per 10 minuti (controllo effettuato ogni 2 minuti per evitare lo spam).
    $todo_clean = false;
    if($file = @fopen("custom/check_clean.lock", 'r'))
    {
        if(!@feof($file))
        {
            $time = @fgets($file, 255);
            if($time < (@time() - 120))
                $todo_clean = true;
        }else $todo_clean = true;
        @fclose($file);
    }else $todo_clean = true;

    if($todo_clean)
    {
        if($file = @fopen("custom/check_clean.lock", 'w'))
        {
            @fputs($file, @time());
            @fclose($file);
        }

        if($connessione = @mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(@mysql_select_db($site_database, $connessione))
            {
                if($result = @mysql_query("SELECT * FROM immaginisalvate WHERE ultimaModifica < (UNIX_TIMESTAMP() - $image_expire_time);", $connessione))
                {
                    while($row = @mysql_fetch_array($result, MYSQL_ASSOC))
                        if(@file_exists("saved/" . $row["nomeImmagine"]) && @is_file("saved/" . $row["nomeImmagine"]))
                            @unlink("saved/" . $row["nomeImmagine"]);
                    @mysql_free_result($result);
                }
                @mysql_query("DELETE FROM immaginisalvate WHERE ultimaModifica < (UNIX_TIMESTAMP() - $image_expire_time);", $connessione);
            }
            @mysql_close($connessione);
        }
    }
?>
<?php
    //La funzione restituisce 0 se l'achievement non è valido (ad esempio achievement di first kill oppure Feast of Strenght) oppure i punti dell'achievement se è valido.
    function isValidAchievement($achievement_id, $host, $user, $pass, $db)
    {
        if($my_conn = @mysql_connect($host, $user, $pass, true))
        {
            if(@mysql_select_db($db, $my_conn))
                if($my_result = @mysql_query("SELECT points FROM achievement WHERE ID = $achievement_id;", $my_conn))
                {
                    if($my_row = @mysql_fetch_array($my_result, MYSQL_ASSOC))
                    {
                        @mysql_free_result($my_result);
                        return $my_row["points"];
                    }
                    @mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return 0;
    }

    //La funzione restituisce le informazioni su un dato talento.
    function getTalentInfo($spellId, $host, $user, $pass, $db)
    {
        if($my_conn = @mysql_connect($host, $user, $pass, true))
        {
            if(@mysql_select_db($db, $my_conn))
                if($my_result = @mysql_query("SELECT rankId, tabPage FROM talent WHERE spellTalent = $spellId;", $my_conn))
                {
                    if($my_row = @mysql_fetch_array($my_result, MYSQL_ASSOC))
                    {
                        @mysql_free_result($my_result);
                        return $my_row;
                    }
                    @mysql_free_result($my_result);
                }
            @mysql_close($my_conn);
        }

        return 0;
    }
?>