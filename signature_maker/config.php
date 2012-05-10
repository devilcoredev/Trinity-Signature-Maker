<?php
    //Disabilita tutti i logs d'errore.
    error_reporting(0);

    include("mysql_connector.php");

    //Array globali.
    $realm_name  = array();
    $host        = array();
    $username    = array();
    $password    = array();
    $database    = array();

    //Nome del server mostrato nella firma (si consiglia di usare un acronimo).
    $server_name = "";

    //Dati di connessione ai servers (per aggiungere un nuovo server basta copiare un blocco e cambiare la chiave
    //con l'acronimo del nuovo server), il campo "$realm_name" sarà visualizzato in firma, il campo "$armory_name"
    //sarà il nome del server visualizzato nel link all'armory.
    //Dati di connessione al database del primo server.
    $realm_name[""]   = "";
    $armory_name[""]  = "";
    $host[""]         = "";
    $username[""]     = "";
    $password[""]     = "";
    $database[""]     = "";

    //Dati di connessione al database del secondo server (se presente, altrimenti commentare questo blocco).
    $realm_name[""]   = "";
    $armory_name[""]  = "";
    $host[""]         = "";
    $username[""]     = "";
    $password[""]     = "";
    $database[""]     = "";

    //Dati di connessione al database del terzo server (se presente, altrimenti commentare questo blocco).
    $realm_name[""]   = "";
    $armory_name[""]  = "";
    $host[""]         = "";
    $username[""]     = "";
    $password[""]     = "";
    $database[""]     = "";

    //Dati di connessione al database contenente titoli ed achievements.
    $site_host      = "";
    $site_username  = "";
    $site_password  = "";
    $site_database  = "";

    //Nome dell'immagine mostrata quando i dati inseriti sono errati.
    $dati_errati      = "dati_errati.png";
    $dim_dati_errati  = filesize("images/$dati_errati"); //Dimensione del file.

    //Nome dell'immagine mostrata quando la firma non viene caricata bene.
    $errore_caricamento      = "errore_caricamento.png";
    $dim_errore_caricamento  = filesize("images/$errore_caricamento"); //Dimensione del file.

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

    //Colore dello sfondo di default (dal rosso al nero).
    $to_img          = false;
    $start_bg_red    = 255;
    $start_bg_green  = 0;
    $start_bg_blue   = 0;
    $end_bg_red      = 0;
    $end_bg_green    = 0;
    $end_bg_blue     = 0;

    //Colore del testo di default (oro).
    //                    rosso verde blu.
    $text_vet_color = array(255, 215, 0);

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
    $fonts = array();

    $fonts["jokerman"] = array();
    $fonts["jokerman"]["text"]              = "Jokerman";
    $fonts["jokerman"]["name"]              = "JOKERMAN.TTF";
    $fonts["jokerman"]["nome_pg"]           = 15;
    $fonts["jokerman"]["stats"]             = 7;
    $fonts["jokerman"]["addictional_info"]  = 9;
    $fonts["jokerman"]['x']                 = 215;
    $fonts["jokerman"]['y']                 = 50;

    $fonts["morpheus"] = array();
    $fonts["morpheus"]["text"]              = "Morpheus";
    $fonts["morpheus"]["name"]              = "MORPHEUS_0.TTF";
    $fonts["morpheus"]["nome_pg"]           = 16;
    $fonts["morpheus"]["stats"]             = 9;
    $fonts["morpheus"]["addictional_info"]  = 10;
    $fonts["morpheus"]['x']                 = 185;
    $fonts["morpheus"]['y']                 = 55;

    $fonts["verdana"] = array();
    $fonts["verdana"]["text"]              = "Verdana";
    $fonts["verdana"]["name"]              = "verdanab.ttf";
    $fonts["verdana"]["nome_pg"]           = 15;
    $fonts["verdana"]["stats"]             = 6;
    $fonts["verdana"]["addictional_info"]  = 8;
    $fonts["verdana"]['x']                 = 210;
    $fonts["verdana"]['y']                 = 50;

    $fonts["flashd"] = array();
    $fonts["flashd"]["text"]              = "Flash D";
    $fonts["flashd"]["name"]              = "FlashD.ttf";
    $fonts["flashd"]["nome_pg"]           = 20;
    $fonts["flashd"]["stats"]             = 9;
    $fonts["flashd"]["addictional_info"]  = 11;
    $fonts["flashd"]['x']                 = 190;
    $fonts["flashd"]['y']                 = 50;

    $fonts["comic"] = array();
    $fonts["comic"]["text"]              = "Comic Sans";
    $fonts["comic"]["name"]              = "comicbd.ttf";
    $fonts["comic"]["nome_pg"]           = 19;
    $fonts["comic"]["stats"]             = 8;
    $fonts["comic"]["addictional_info"]  = 10;
    $fonts["comic"]['x']                 = 230;
    $fonts["comic"]['y']                 = 50;

    //Sfondi per le firme.
    $backgrounds = array();
    $backgrounds[] = "bg_abstract";
    $backgrounds[] = "bg_blue";
    $backgrounds[] = "bg_blues";
    $backgrounds[] = "bg_chain";
    $backgrounds[] = "bg_dragon";
    $backgrounds[] = "bg_electric";
    $backgrounds[] = "bg_lava";
    $backgrounds[] = "bg_rippedmetal";
    $backgrounds[] = "bg_space";
    $backgrounds[] = "bg_stone";
    $backgrounds[] = "bg_swag";
    $backgrounds[] = "bg_swirls";
    $backgrounds[] = "bg_swirls_bw";
    $backgrounds[] = "bg_tornado";
    $backgrounds[] = "bg_drake";
    $backgrounds[] = "bg_lightning";
    $backgrounds[] = "bg_vulcan";
    $backgrounds[] = "bg_arrow";
    $backgrounds[] = "bg_black_temple";
    $backgrounds[] = "bg_cataclysm";
    $backgrounds[] = "bg_dawn";
    $backgrounds[] = "bg_deathwing";
    $backgrounds[] = "bg_eyes";
    $backgrounds[] = "bg_female_orc";
    $backgrounds[] = "bg_fire";
    $backgrounds[] = "bg_frostmurne";
    $backgrounds[] = "bg_icc_01";
    $backgrounds[] = "bg_icc_02";
    $backgrounds[] = "bg_illidan_01";
    $backgrounds[] = "bg_illidan_02";
    $backgrounds[] = "bg_lanathel";
    $backgrounds[] = "bg_lich_king_eyes";
    $backgrounds[] = "bg_moon_01";
    $backgrounds[] = "bg_moon_02";
    $backgrounds[] = "bg_naga";
    $backgrounds[] = "bg_panda_01";
    $backgrounds[] = "bg_pumpkin";
    $backgrounds[] = "bg_vashj";
    $backgrounds[] = "bg_war";
    $backgrounds[] = "bg_woodland";
    $backgrounds[] = "bg_worgren";
    $backgrounds[] = "bg_wrist";

    //Effetti visivi.
    $effects = array();
    $effects[] = "grid_left_to_right";
    $effects[] = "grid_right_to_left";
    $effects[] = "grid_pointer";
    $effects[] = "grid_square";

    //Font di default (verdana).
    $font                  = $fonts["verdana"]["name"];
    $dim_nome_pg           = $fonts["verdana"]["nome_pg"];
    $dim_stats             = $fonts["verdana"]["stats"];
    $dim_addictional_info  = $fonts["verdana"]["addictional_info"];

    //Vettore contenente le razze.
    $races = array();
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
    $classes = array();
    $classes[1]   = array("name" => "Warrior", "img" => "180px-Warrior_crest");
    $classes[2]   = array("name" => "Paladin", "img" => "180px-Paladin_crest");
    $classes[3]   = array("name" => "Hunter", "img" => "180px-Hunter_crest");
    $classes[4]   = array("name" => "Rogue", "img" => "180px-Rogue_crest");
    $classes[5]   = array("name" => "Priest", "img" => "180px-Priest_crest");
    $classes[6]   = array("name" => "Death Knight", "img" => "180px-Death_knight_crest");
    $classes[7]   = array("name" => "Shaman", "img" => "180px-Shaman_crest");
    $classes[8]   = array("name" => "Mage", "img" => "180px-Mage_crest");
    $classes[9]   = array("name" => "Warlock", "img" => "180px-Warlock_crest");
    $classes[11]  = array("name" => "Druid", "img" => "180px-Druid_crest");

    //Nomi dei rami talento a partire dalla classe e tab.
    $tab_names = array();
    $tab_names[1]   = array("Arms", "Fury", "Protection");
    $tab_names[2]   = array("Holy", "Protection", "Retribution");
    $tab_names[3]   = array("Beast Mastery", "Marksmanship", "Survival");
    $tab_names[4]   = array("Assassination", "Combat", "Subtlety");
    $tab_names[5]   = array("Discipline", "Holy", "Shadow");
    $tab_names[6]   = array("Blood", "Frost", "Unholy");
    $tab_names[7]   = array("Elemental", "Enhancement", "Restoration");
    $tab_names[8]   = array("Arcane", "Fire", "Frost");
    $tab_names[9]   = array("Affliction", "Demonology", "Destruction");
    $tab_names[11]  = array("Balance", "Feral Combat", "Restoration");

    //Vettore contenente le stats da stampare,
    //"name" sarà visualizzato nel menu a tendina dell'index,
    //"field_name" sarà il nome del campo da ricercare nel database,
    //"text" è il testo da stampare in firma con il valore della stat al posto di %s.
    $stats = array();
    $stats["maxhealth"]          = array("name" => "Health", "field_name" => "maxhealth", "text" => "Health: %s");
    $stats["mana"]               = array("name" => "Mana", "field_name" => "mana", "text" => "Mana: %s");
    $stats["rune"]               = array("name" => "Rune", "field_name" => "rune", "text" => "Rune: %s");
    $stats["runicpower"]         = array("name" => "Runic Power", "field_name" => "runicPower", "text" => "Runic Pwr: %s");
    $stats["strength"]           = array("name" => "Strength", "field_name" => "strength", "text" => "Strength: %s");
    $stats["agility"]            = array("name" => "Agility", "field_name" => "agility", "text" => "Agility: %s");
    $stats["stamina"]            = array("name" => "Stamina", "field_name" => "stamina", "text" => "Stamina: %s");
    $stats["intellect"]          = array("name" => "Intellect", "field_name" => "intellect", "text" => "Intellect: %s");
    $stats["spirit"]             = array("name" => "Spirit", "field_name" => "spirit", "text" => "Spirit: %s");
    $stats["armor"]              = array("name" => "Armor", "field_name" => "armor", "text" => "Armor: %s");
    $stats["blockpct"]           = array("name" => "Block Pct", "field_name" => "blockPct", "text" => "Block: %s%");
    $stats["dodgepct"]           = array("name" => "Dodge Pct", "field_name" => "dodgePct", "text" => "Dodge: %s%");
    $stats["parrypct"]           = array("name" => "Parry Pct", "field_name" => "parryPct", "text" => "Parry: %s%");
    $stats["critpct"]            = array("name" => "Crit Pct", "field_name" => "critPct", "text" => "Crit Chance: %s%");
    $stats["rangedcritpct"]      = array("name" => "Ranged Crit Pct", "field_name" => "rangedCritPct", "text" => "Ranged Crit: %s%");
    $stats["spellcritpct"]       = array("name" => "Spell Crit Pct", "field_name" => "spellCritPct", "text" => "Spell Crit: %s%");
    $stats["hit"]                = array("name" => "Hit Pct", "field_name" => "hit", "text" => "Hit: %s%");
    $stats["meleehit"]           = array("name" => "Melee Hit Pct", "field_name" => "meleeHit", "text" => "Melee Hit: %s%");
    $stats["rangedhit"]          = array("name" => "Ranged Hit Pct", "field_name" => "rangedHit", "text" => "Ranged Hit: %s%");
    $stats["spellhit"]           = array("name" => "Spell Hit Pct", "field_name" => "spellHit", "text" => "Spell Hit: %s%");
    $stats["haste"]              = array("name" => "Haste Pct", "field_name" => "haste", "text" => "Haste: %s%");
    $stats["meleehaste"]         = array("name" => "Melee Haste Pct", "field_name" => "meleeHaste", "text" => "Melee Haste: %s%");
    $stats["rangedhaste"]        = array("name" => "Ranged Haste Pct", "field_name" => "rangedHaste", "text" => "Ranged Haste: %s%");
    $stats["spellhaste"]         = array("name" => "Spell Haste Pct", "field_name" => "spellHaste", "text" => "Spell Haste: %s%");
    $stats["attackpower"]        = array("name" => "Attack Power", "field_name" => "attackPower", "text" => "Attack Pwr: %s");
    $stats["rangedattackpower"]  = array("name" => "Ranged Attack Power", "field_name" => "rangedAttackPower", "text" => "Ranged Attack Pwr: %s");
    $stats["spellpower"]         = array("name" => "Spell Power", "field_name" => "spellPower", "text" => "Spell Pwr: %s");
    $stats["resilience"]         = array("name" => "Resilience", "field_name" => "resilience", "text" => "Resilience: %s");
    $stats["talents"]            = array("name" => "Talenti", "field_name" => "talents", "text" => "Talenti: (%s)");
    $stats["arenapoints"]        = array("name" => "Punti arena", "field_name" => "arenaPoints", "text" => "Pt arena: %s");
    $stats["honorpoints"]        = array("name" => "Punti honor", "field_name" => "totalHonorPoints", "text" => "Pt honor: %s");
    $stats["pvpkills"]           = array("name" => "Kill PvP", "field_name" => "totalKills", "text" => "Kill PvP: %s");
    $stats["teamrating2"]        = array("name" => "Team Rating 2v2", "field_name" => "teamRating2", "text" => "Team Rating 2v2: %s");
    $stats["teamrating3"]        = array("name" => "Team Rating 3v3", "field_name" => "teamRating3", "text" => "Team Rating 3v3: %s");
    $stats["teamrating5"]        = array("name" => "Team Rating 5v5", "field_name" => "teamRating5", "text" => "Team Rating 5v5: %s");
    $stats["personalrating2"]    = array("name" => "Personal Rating 2v2", "field_name" => "personalRating2", "text" => "Personal Rating 2v2: %s");
    $stats["personalrating3"]    = array("name" => "Personal Rating 3v3", "field_name" => "personalRating3", "text" => "Personal Rating 3v3: %s");
    $stats["personalrating5"]    = array("name" => "Personal Rating 5v5", "field_name" => "personalRating5", "text" => "Personal Rating 5v5: %s");

    //Stats degli item.
    $stats_rating_div = array();
    $stats_rating_div["meleeHit"]     = 16;
    $stats_rating_div["rangedHit"]    = 17;
    $stats_rating_div["spellHit"]     = 18;
    $stats_rating_div["meleeHaste"]   = 28;
    $stats_rating_div["rangedHaste"]  = 29;
    $stats_rating_div["spellHaste"]   = 30;
    $stats_rating_div["hit"]          = 31;
    $stats_rating_div["haste"]        = 36;

    $site_connection = new mysql_connector($site_host, $site_username, $site_password, $site_database);
    $site_connection->connect();

    //Una volta ogni 24 ore vengono riscritte le informazioni sugli achievements sul file di configurazione in modo da non leggerli sempre da db.
    $time = file_get_contents("custom/check_day.lock");
    if($time == false || (time()-$time) >= (24*60*60))
    {
        file_put_contents("custom/check_day.lock", time());

        //Numero totale di achievements ottenibili (estratto da db).
        $num_ach = 0;
        if($row = $site_connection->query("SELECT COUNT(*) AS numero FROM achievement WHERE points <> 0;", true))
            $num_ach = $row["numero"]; //Seleziono solo gli achievements ottenibili (cioè quelli che danno punti) e li inserisco nel vettore $achievements.

        //Se ci sono achievements inserisco le ultime 2 voci di configurazione delle stats.
        if($num_ach)
        {
            if($ach_file = fopen("custom/achievements.php", 'w'))
            {
                fputs($ach_file, "<?php\r\n");
                fputs($ach_file, "    \$num_achievements = $num_ach;\r\n\r\n");
                fputs($ach_file, "    \$stats[\"achievements\"]       = array(\"name\" => \"Achievements\", \"field_name\" => \"achievements\", \"text\" => \"Ach.: %s/\$num_achievements\"); //Achievements ottenuti / Achievements ottenibili.\r\n");
                fputs($ach_file, "    \$stats[\"achievementpoints\"]  = array(\"name\" => \"Achievement Points\", \"field_name\" => \"achievementPoints\", \"text\" => \"Ach. Pts: %s\");\r\n");
                fputs($ach_file, "?>");
                fclose($ach_file);
            }
        }
    }

    if(file_exists("custom/achievements.php"))
        include("custom/achievements.php");
?>
<?php
    //Codice che cancella automaticamente tutte le immagini non usate per 10 minuti (controllo effettuato ogni 2 minuti per evitare lo spam).
    $time = file_get_contents("custom/check_clean.lock");
    if($time == false || (time()-$time) >= 120)
    {
        file_put_contents("custom/check_clean.lock", time());

        $id_query_clean = $site_connection->query("SELECT * FROM immaginisalvate WHERE ultimaModifica < (UNIX_TIMESTAMP() - $image_expire_time);");
        while($row = $site_connection->getNextResult($id_query_clean))
            if(file_exists("saved/" . $row["nomeImmagine"]))
                unlink("saved/" . $row["nomeImmagine"]);

        $site_connection->query("DELETE FROM immaginisalvate WHERE ultimaModifica < (UNIX_TIMESTAMP() - $image_expire_time);");
    }


    $time = file_get_contents("custom/check_week.lock");
    if($time == false || (time()-$time) >= (7*24*60*60))
    {
        file_put_contents("custom/check_week.lock", time());

        $path_name = "./temp_images/";
        if($handle = opendir($path_name))
        {
            while(($file = readdir($handle)) !== false)
            {
                $file_path = $path_name . $file;
                if(is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) != "htm") //Cancello tutte le immagini.
                    unlink($file_path);
            }
            closedir($handle);
        }
    }
?>
<?php
    //INIZIO FUNZIONI STANDARD GD.
        function isGD2supported()
        {
            global $GD2;
            if(isset($GD2) && $GD2)
                return $GD2;
            else
            {
                $php_ver_arr = explode('.', phpversion());
                $php_ver = intval($php_ver_arr[0])*100 + intval($php_ver_arr[1]);

                if($php_ver < 402) // PHP <= 4.1.x
                    $GD2 = in_array("imagegd2", get_extension_funcs("gd"));
                else if($php_ver < 403) // PHP = 4.2.x
                {
                    if($im = imagecreatetruecolor(10, 10))
                    {
                        $GD2 = 1;
                        imagedestroy($im);
                    }else $GD2 = 0;
                }else $GD2 = function_exists("imagecreatetruecolor"); // PHP = 4.3.x
            }

            return $GD2;
        }

        function GDVersion()
        {
            if(!in_array("gd", get_loaded_extensions()))
                return 0;
            else if(isGD2supported())
                return 2;
            else return 1;
        }

        function imageCreateFromVersion($x, $y)
        {
            if(GDVersion() == 1)
                return imagecreate($x, $y);
            else return imagecreatetruecolor($x, $y);
        }
    //FINE FUNZIONI STANDARD GD.
?>