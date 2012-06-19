<?php
    define("ARMORY_INCLUDED", true);
    //Inclusioni librerie.
    include("config.php");
?>
<?php
    //Restituisce un numero decimale a partire da un numero esadecimale nella forma "HHHHHH".
    function GetRGBFromHex($input)
    {
        return array(hexdec(substr($input, 0, 2)), hexdec(substr($input, 2, 2)), hexdec(substr($input, 4, 2)));
    }

    //Funzione che restituisce la query string ordinata alfabeticamente.
    function getOrderQueryString($input)
    {
        $arr = explode('&', strtolower($input)); //Divido la query string in un array contenente chiave=valore.
        sort($arr); //Ordino l'array in ordine alfabetico (in questo caso vengono ordinate solo le chiavi della querystring).
        return mysql_real_escape_string(implode('&', $arr));
    }

    //Funzione che effettua una conversione da UInt32 a float.
    function UInt32ToFloat($input)
    {
        $txt = unpack('f', pack('L', $input));
        return $txt[1];
    }

    function getItemTemplate($item_id)
    {
        global $site_connection;
        return $site_connection->query("SELECT * FROM itemtemplate WHERE entry = $item_id;", true);
    }

    function sumItemEnchantments(&$input, $data)
    {
        global $site_connection, $stats_rating_div;

        $data_array = explode(' ', $data);
        for($i = 0; $i < count($data_array); $i += 3)
            if($row = $site_connection->query("SELECT * FROM itemenchantment WHERE ID = " . $data_array[$i] . ';', true))
                for($j = 0; $j < 3; ++$j)
                    if($row["type$j"] == 5 && in_array($row["spellid$j"], $stats_rating_div))
                    {
                        $key = array_search($row["spellid$j"], $stats_rating_div);
                        $input["$key"] += $row["amount$j"];
                    }
    }

    function getRating($level, $rating_name)
    {
        global $site_connection;

        if($row = $site_connection->query("SELECT $rating_name FROM rating WHERE level = $level;", true))
            return $row["$rating_name"];

        return 1;
    }

    //La funzione ricava tutte le stats del PG, cerca prima nella tabella character_stats, se non trova nulla cerca in armory_character_stats.
    function fill_stats(&$input, $intput_conn)
    {
        $guid = $input["guid"];
        $find_stats = false;

        $query = "SELECT maxhealth, maxpower1 AS mana, maxpower6 AS rune, maxpower7 AS runicPower, strength, agility, stamina, intellect, spirit,
                    armor, blockPct, dodgePct, parryPct, critPct, rangedCritPct, spellCritPct, attackPower, rangedAttackPower, spellPower,
                    resilience FROM character_stats WHERE guid = $guid;";
        if($row = $intput_conn->query($query, true)) //Armory interna (se abilitata).
        {
            $find_stats = true;
            $input = array_merge($input, $row);
        }

        if(!$find_stats)
            if($row = $intput_conn->query("SELECT data FROM armory_character_stats WHERE guid = $guid;", true)) //Shadez armory (solo se l'armory interna non � abilitata).
            {
                $input_array = explode(' ', $row["data"]);

                $input["maxhealth"]          = $input_array[0x0006 + 0x001A];                           //OBJECT_END + UNIT_FIELD_MAXHEALTH.
                $input["mana"]               = $input_array[0x0006 + 0x001B];                           //OBJECT_END + UNIT_FIELD_MAXPOWER1.
                $input["rune"]               = $input_array[0x0006 + 0x001B + 0x0005];                  //OBJECT_END + UNIT_FIELD_MAXPOWER6.
                $input["runicPower"]         = $input_array[0x0006 + 0x001B + 0x0006];                  //OBJECT_END + UNIT_FIELD_MAXPOWER7.
                $input["strength"]           = $input_array[0x0006 + 0x004E];                           //OBJECT_END + UNIT_FIELD_STAT0.
                $input["agility"]            = $input_array[0x0006 + 0x004E + 0x0001];                  //OBJECT_END + UNIT_FIELD_STAT1.
                $input["stamina"]            = $input_array[0x0006 + 0x004E + 0x0002];                  //OBJECT_END + UNIT_FIELD_STAT2.
                $input["intellect"]          = $input_array[0x0006 + 0x004E + 0x0003];                  //OBJECT_END + UNIT_FIELD_STAT3.
                $input["spirit"]             = $input_array[0x0006 + 0x004E + 0x0004];                  //OBJECT_END + UNIT_FIELD_STAT4.
                $input["armor"]              = $input_array[0x0006 + 0x005D];                           //OBJECT_END + UNIT_FIELD_RESISTANCES.
                $input["blockPct"]           = UInt32ToFloat($input_array[0x0006 + 0x008E + 0x036C]);   //OBJECT_END + UNIT_END + PLAYER_BLOCK_PERCENTAGE.
                $input["dodgePct"]           = UInt32ToFloat($input_array[0x0006 + 0x008E + 0x036D]);   //OBJECT_END + UNIT_END + PLAYER_DODGE_PERCENTAGE.
                $input["parryPct"]           = UInt32ToFloat($input_array[0x0006 + 0x008E + 0x036E]);   //OBJECT_END + UNIT_END + PLAYER_PARRY_PERCENTAGE.
                $input["critPct"]            = UInt32ToFloat($input_array[0x0006 + 0x008E + 0x0371]);   //OBJECT_END + UNIT_END + PLAYER_CRIT_PERCENTAGE.
                $input["rangedCritPct"]      = UInt32ToFloat($input_array[0x0006 + 0x008E + 0x0372]);   //OBJECT_END + UNIT_END + PLAYER_RANGED_CRIT_PERCENTAGE.
                $input["attackPower"]        = $input_array[0x0006 + 0x0075];                           //OBJECT_END + UNIT_FIELD_ATTACK_POWER.
                $input["rangedAttackPower"]  = $input_array[0x0006 + 0x0078];                           //OBJECT_END + UNIT_FIELD_RANGED_ATTACK_POWER.
                $input["resilience"]         = $input_array[0x0006 + 0x008E + 0x043B + 0x0010];         //OBJECT_END + UNIT_END + PLAYER_FIELD_COMBAT_RATING_1 + CR_CRIT_TAKEN_SPELL.

                $input["spellPower"]         = $input_array[0x0494];                                    //OBJECT_END + UNIT_END + PLAYER_FIELD_MOD_DAMAGE_DONE_POS.
                for($i = 0x0495; $i < 0x049A; ++$i) //Il valore minimo � lo spell power attuale del PG.
                    $input["spellPower"]     = min($input["spellPower"], $input_array[$i]);

                $input["spellCritPct"]       = UInt32ToFloat($input_array[0x0409]);                     //OBJECT_END + UNIT_END + PLAYER_SPELL_CRIT_PERCENTAGE1.
                for($i = 0x040A; $i < 0x040F; ++$i) //Il valore minimo � lo spell crit attuale del PG.
                    $input["spellCritPct"]   = min($input["spellCritPct"], UInt32ToFloat($input_array[$i]));
            }

        if($input["class"] != 6) //Solo i Death Knight hanno rune e runic power.
        {
            $input["rune"]       = "N/D";
            $input["runicPower"] = "N/D";
        }
        if($input["class"] == 1 || $input["class"] == 4 || $input["class"] == 6) //Warrior, rogue e Death Knight non hanno mana.
            $input["mana"] = "N/D";

        //Personal e team rating 2v2, 3v3 e 5v5.
        $team_types = array(2, 3, 5);
        foreach($team_types as $i => $value)
        {
            $input["teamName$value"]        = "N/D";
            $input["teamRating$value"]      = "N/D";
            $input["personalRating$value"]  = "N/D";

            $query = "SELECT arena_team.name, arena_team.rating, arena_team_member.personalRating FROM arena_team,
                        arena_team_member WHERE arena_team.arenaTeamId = arena_team_member.arenaTeamId AND
                        arena_team_member.guid = $guid AND arena_team.type = $value;";
            if($row = $intput_conn->query($query, true))
            {
                $input["teamName$value"]        = $row["name"];
                $input["teamRating$value"]      = $row["rating"];
                $input["personalRating$value"]  = $row["personalRating"];
            }
        }

        //Haste e Hit.
        global $stats_rating_div;
        foreach($stats_rating_div as $i => $value)
        {
            $input["$i"] = 0;
        }
        $query = "SELECT item_instance.itemEntry, item_instance.enchantments FROM character_inventory, item_instance
                    WHERE character_inventory.item = item_instance.guid AND character_inventory.guid = $guid AND character_inventory.slot<18;";
        $id_query = $intput_conn->query($query);
        if($id_query != -1)
        {
            while($row = $intput_conn->getNextResult($id_query))
            {
                sumItemEnchantments($input, $row["enchantments"]);
                if($item_template = getItemTemplate($row["itemEntry"]))
                    for($i = 1; $i <= $item_template["StatsCount"]; ++$i)
                        if(in_array($item_template["stat_type$i"], $stats_rating_div))
                        {
                            $key = array_search($item_template["stat_type$i"], $stats_rating_div);
                            $input["$key"] += $item_template["stat_value$i"];
                        }
            }
            $input["meleeHit"]     += $input["hit"];
            $input["rangedHit"]    += $input["hit"];
            $input["spellHit"]     += $input["hit"];
            $input["meleeHaste"]   += $input["haste"];
            $input["rangedHaste"]  += $input["haste"];
            $input["spellHaste"]   += $input["haste"];
        }
        $input["hit"]          /= getRating($input["level"], "CR_HIT_MELEE");
        $input["meleeHit"]     /= getRating($input["level"], "CR_HIT_MELEE");
        $input["rangedHit"]    /= getRating($input["level"], "CR_HIT_RANGED");
        $input["spellHit"]     /= getRating($input["level"], "CR_HIT_SPELL");
        $input["haste"]        /= getRating($input["level"], "CR_HASTE_MELEE");
        $input["meleeHaste"]   /= getRating($input["level"], "CR_HASTE_MELEE");
        $input["rangedHaste"]  /= getRating($input["level"], "CR_HASTE_RANGED");
        $input["spellHaste"]   /= getRating($input["level"], "CR_HASTE_SPELL");
    }

    //La funzione restituisce 0 se l'achievement non � valido (ad esempio achievement di first kill oppure Feast of Strenght) oppure i punti dell'achievement se � valido.
    function isValidAchievement($achievement_id)
    {
        global $site_connection;
        if($row = $site_connection->query("SELECT points FROM achievement WHERE ID = $achievement_id;", true))
            return $row["points"];
        return false;
    }

    //La funzione restituisce le informazioni su un dato talento.
    function getTalentInfo($spellId)
    {
        global $site_connection;
        return $site_connection->query("SELECT rankId, tabPage FROM talent WHERE spellTalent = $spellId;", true);
    }
?>
<?php
    //Variabili globali.
    $do_next_step    = true;      //Si potr� procedere alle altre funzioni solo se $do_next_step = true.
    $pg_name         = '';        //Nome del personaggio.
    $name_string     = '';        //Nome del personaggio comprensivo di titolo.
    $string_info     = '';        //Informazioni addizionali (razza, livello, classe).
    $is_gif          = false;     //La variabile posta a false indica che l'immagine � png, altrimenti � gif.
    $external_image  = false;     //La variabile indica se l'avar impostato � interno o esterno al sito.
    $avatar_img      = '';        //Link all'avatar.
    $string_guild    = '';        //Rank, guild, realm, server.
    $im              = FALSE;     //Link all'immagine.
    $to_make_image   = true;      //Serve a controllare se l'immagine deve essere costruita.
    $pg_GUID         = 0;         //ID univoco del personaggio.
    $spec_name       = '';        //Nome della spec.
    $effect          = '';        //Effetto per lo sfondo.
    $img_name        = '';        //Sfondo della firma.
    $server_id       = '';        //Id del server scelto.
?>
<?php
    //Controllo preliminare sull'esistenda dell'immagine (se la stessa immagine esiste gi� non la rielaboro).
    $query_string = getOrderQueryString($_SERVER["QUERY_STRING"]); //Controllo se esiste un'immagine salvata con la stessa query string.
    if($row = $site_connection->query("SELECT * FROM immaginisalvate WHERE queryString = '$query_string';", true)) //Forza l'aggiornamento dell'immagine ogni 24 ore.
        if(file_exists("saved/" . $row["nomeImmagine"]) && (time()-$row["creazione"])<(24*60*60))
        {
            $to_make_image = false;
            $site_connection->query("UPDATE immaginisalvate SET ultimaModifica = UNIX_TIMESTAMP() WHERE queryString = '$query_string';", true);

            //Se l'immagine esiste faccio un redirect a quella preesistete.
            header("Content-disposition: inline; filename=firma.png");
            header("Content-type: image/png");
            $im = imagecreatefrompng("saved/" . $row["nomeImmagine"]);
            imagepng($im);
            imagedestroy($im);
        }
?>
<?php
    //Controllo dei campi inseriti, e controllo dell'esistenza del realm selezionato.
    if(isset($_GET["server"]) && $_GET["server"] != '' && isset($_GET["nome_pg"]) && $_GET["nome_pg"] != '')
    {
        $server_id = strtolower($_GET["server"]);
        if(isset($realm_name["$server_id"]))
        {
            $server_conn = new mysql_connector($host["$server_id"], $username["$server_id"], $password["$server_id"], $database["$server_id"]);
            $server_conn->connect();
            $do_next_step = $server_conn->isOpen();
        }
    }
?>
<?php
    //Recupero dei dati per la creazione dell'immagine.
    if($to_make_image && $do_next_step)
    {
        if(GDVersion() == 0) //Nel sistema non esiste GC.
            $do_next_step = false;

        if($do_next_step)
        {
            //L'utente ha selezionato uno sfondo diverso da quello di default (graduazione di rosso).
            if(isset($_GET["sfondo"]) && $_GET["sfondo"] != '')
            {
                $sfondo = strtolower($_GET["sfondo"]);
                //Lo sfondo � un'immagine, (comincia con bg_), mi costruisco il link all'immagine.
                if(!strncmp($sfondo, "bg_", 3))
                {
                    //Tutti gli sfondi utilizzati per la firma sono in png, cominciano con bg_ e sono contenuti nella directory "images/bg".
                    if(in_array($sfondo, $backgrounds))
                    {
                        $to_img = true;
                        $img_name = $sfondo;
                    }
                }
                else //Lo sfondo � un colore, cerco le 3 graduazioni nel codice esadecimale.
                {
                    $bg_vet = GetRGBFromHex($sfondo);

                    $to_img          = false;
                    $start_bg_red    = $bg_vet[0];
                    $start_bg_green  = $bg_vet[1];
                    $start_bg_blue   = $bg_vet[2];
                }
            }

            //L'utente ha selezionato il colore finale dello sfondo.
            if(isset($_GET["fine_sfondo"]) && $_GET["fine_sfondo"] != '')
            {
                $bg_vet = GetRGBFromHex(strtolower($_GET["fine_sfondo"]));
                $end_bg_red      = $bg_vet[0];
                $end_bg_green    = $bg_vet[1];
                $end_bg_blue     = $bg_vet[2];
            }

            //Effetto per lo sfondo.
            if(isset($_GET["effects"]) && $_GET["effects"] != '')
            {
                $effect = strtolower($_GET["effects"]);
                if(!in_array($effect, $effects)) $effect = '';
            }

            //L'utente ha selezionato un colore di testo diverso da quello di default, elaboro il codice esadecimale.
            if(isset($_GET["colore_testo"]) && $_GET["colore_testo"] != '')
                $text_vet_color = GetRGBFromHex($_GET["colore_testo"]);

            //L'utente ha selezionato un carattere per il testo diverso da quello di default, lo estraggo.
            if(isset($_GET["text_font"]) && $_GET["text_font"] != '')
            {
                $name_font = strtolower($_GET["text_font"]);
                if(isset($fonts["$name_font"]["name"]))
                {
                    $font                  = $fonts["$name_font"]["name"];
                    $dim_nome_pg           = $fonts["$name_font"]["nome_pg"];
                    $dim_stats             = $fonts["$name_font"]["stats"];
                    $dim_addictional_info  = $fonts["$name_font"]["addictional_info"];
                }
            }
            //Completo il path del file contenente il font.
            $font = "fonts/$font";

            //Primo carattere maiuscolo, resto minuscolo. Esempi: TEST=>Test, test=>Test, tEsT=>Test.
            $nome_pg = mysql_real_escape_string(ucfirst(strtolower($_GET["nome_pg"]))); //Evita le SQL-Injection.

            //Nome, Razza, Classe, Livello, Titolo, Spec, Punti arena, Punti honor, Kill PvP.
            $query = "SELECT guid, name, race, class, gender, level, chosenTitle, activespec, arenaPoints, totalHonorPoints,
                        totalKills FROM characters WHERE name = '$nome_pg';";
            if($row = $server_conn->query($query, true))
            {
                $pg_GUID = $row["guid"];
                $pg_name = $row["name"];
                $spec_id = $row["activespec"];
                fill_stats($row, $server_conn); //Riempio il resto delle stats.

                //Se il pg ha un titolo identificato (cercando nel database del sito) lo inserisco nella firma.
                $name_string = $row["name"];
                if($row["chosenTitle"] != 0)
                    if($title_row = $site_connection->query("SELECT nameString FROM chartitles WHERE titleId = " . $row["chosenTitle"] . ';', true))
                        $name_string = str_replace("%s", $row["name"], $title_row["nameString"]);

                //Talenti, eseguo questa operazione a prescindere per trovare il nome della spec.
                $talents = array(0, 0, 0);
                $id_query_talents = $server_conn->query("SELECT spell FROM character_talent WHERE guid = $pg_GUID AND spec = $spec_id;");
                while($talents_row = $server_conn->getNextResult($id_query_talents))
                    if($vet = getTalentInfo($talents_row["spell"]))
                        $talents[$vet["tabPage"]] += $vet["rankId"];
                $row["talents"] = $talents[0] . '/' . $talents[1] . '/' . $talents[2]; //Talenti nella forma (x/x/x).

                //Nome della spec.
                if($max_talent = max($talents[0], $talents[1], $talents[2]))
                    $spec_name .= ' ' . $tab_names[$row["class"]][array_search($max_talent, $talents)];

                //Livello - Classe - Razza.
                $string_info = "Level " . $row["level"] . ' ' . $races[$row["race"]] . ' ' . $classes[$row["class"]]["name"] . $spec_name;

                //Se viene dato l'url di un'immagine png valida lo inserisco, altrimenti inserisco quella di default della classe.
                if(isset($_GET["url_image"]) && $_GET["url_image"] != '')
                {
                    $avatar_img  = utf8_decode($_GET["url_image"]);
                    $file_name   = "temp_images/" . sha1($avatar_img) . '.' . pathinfo($avatar_img, PATHINFO_EXTENSION); //Ricavo il nome univoco dell'immagine.

                    if(!file_exists($file_name)) //Se l'immagine non esiste la copio in cache.
                        if($contents = file_get_contents($avatar_img))
                            file_put_contents($file_name, $contents);

                    $avatar_img = $file_name; //Restituisco il nuovo link all'immagine.
                    if($check_im = imagecreatefromstring(file_get_contents($avatar_img)))
                    {
                        $external_image = true;
                        imagedestroy($check_im);
                    }else unlink($avatar_img); //Se il file non � un'immagine lo rimuovo.
                }

                if(!$external_image)
                {
                    //L'immagine indica sia la razza che la classe del personaggio.
                    if(isset($_GET["type_image"]) && strtolower($_GET["type_image"]) == "race_class")
                    {
                        $is_gif = true;
                        //Le immagini sono nella forma "gender-race-class.gif".
                        $avatar_img = $row["gender"] . '-' . $row["race"] . '-' . $row["class"] . ".gif";
                        if($row["class"] == 6 || $row["level"] >= 80) //Se � livello 80 oppure � un Death Knight seleziono gli avatar livello 80 (i DK hanno solo avatar livello 80).
                            $avatar_img = "Level_80_Forum_Avatars/$avatar_img";
                        else if($row["level"] >= 70) //Livello compreso tra 70 e 79, seleziono gli avatar livello 70.
                            $avatar_img = "Level_70_Forum_Avatars/$avatar_img";
                        else if($row["level"] >= 60) //Livello compreso tra 60 e 69, seleziono gli avatar livello 60.
                            $avatar_img = "Level_60_Forum_Avatars/$avatar_img";
                        else $avatar_img = "Level_1_Forum_Avatars/$avatar_img"; //Livello compreso tra 1 e 59, seleziono gli avatar livello 1.
                    }else $avatar_img = $classes[$row["class"]]["img"] . ".png"; //L'immagine indica solo la classe del personaggio, faccio riferimento ai config.
                    $avatar_img = "images/classes/$avatar_img"; //Completo il path dell'avatar.
                }

                //Guild e Rank.
                $guild_query = "SELECT guild.name, guild_rank.rname FROM guild_member, guild, guild_rank WHERE guild_member.guildid = guild.guildid
                                AND guild_member.rank = guild_rank.rid AND guild_rank.guildid = guild.guildid AND guild_member.guid = $pg_GUID;";
                if($guild_row = $server_conn->query($guild_query, true))
                    $string_guild = '"' . $guild_row["rname"] . "\" of <" . $guild_row["name"] . "> "; //"Rank" of <Nome Guild>
                if($server_name != '' || $server_id != '') //[Nome_Server Nome_Realm]
                {
                    $string_guild .= "[$server_name";
                    if($server_name != '' && $server_id != '') $string_guild .= ' ';
                        $string_guild .= $realm_name["$server_id"] . ']';
                }

                //Stats (messe in array per comodit�).
                $index = 0;
                $show_stats = array();
                $temp_string = '';
                for($i = 1; $i < 6; ++$i) //Al massimo 5 stats a scelta.
                {
                    if(isset($_GET["custom_stat$i"]) && $_GET["custom_stat$i"] != '')
                        $temp_string = substr(utf8_decode($_GET["custom_stat$i"]), 0, 20);
                    else if(isset($_GET["stat$i"]) && $_GET["stat$i"] != '') //Controllo se esiste il template di quella stat.
                    {
                        $get_stat = strtolower($_GET["stat$i"]);

                        //Achievements, eseguo questa operazione solo se richiesta per risparmiare risorse.
                        if(($get_stat == "achievements" && !isset($row["achievements"])) || ($get_stat == "achievementpoints" && !isset($row["achievementPoints"])))
                        {
                            $ach_count = 0;
                            $ach_points = 0;
                            //Seleziono solo gli achievements che danno punti, il resto sono "Feats of Strength" oppure first kill.
                            $id_query_achievements = $server_conn->query("SELECT achievement FROM character_achievement WHERE guid = $pg_GUID;");
                            while($achievements_row = $server_conn->getNextResult($id_query_achievements))
                                if($punti = isValidAchievement($achievements_row["achievement"]))
                                {
                                    $ach_count ++; //Incremento il conto degli achievements ottenuti.
                                    $ach_points += $punti; //Incremento il punteggio degli achivements ottenuti.
                                }
                            //Inserisco nel vettore delle stats i dati ottenuti.
                            $row["achievements"]       = $ach_count;
                            $row["achievementPoints"]  = $ach_points;
                        }

                        if(isset($stats["$get_stat"]["name"]))
                        {
                            $field_name = $stats["$get_stat"]["field_name"];

                            if(is_numeric($row["$field_name"])) //Effettuo l'arrotondamento solo se il campo � un numero.
                                $field_value = round($row["$field_name"], 2);
                            else $field_value = $row["$field_name"];

                            //Sostituisco i valori alle stringhe di template.
                            $temp_string = str_replace("%s", $field_value, $stats["$get_stat"]["text"]);
                        }
                    }

                    //Check per evitare di mettere stats doppie.
                    if(!in_array($temp_string, $show_stats) && $temp_string != '')
                        $show_stats[$index++] = $temp_string;
                }
            }else $do_next_step = false;
        }
    }
?>
<?php
    if($to_make_image)
    {
        if($do_next_step)
        {
            header("Content-disposition: inline; filename=firma.png");
            header("Content-type: image/png");

            //Dipende dalla versione di GD l'immagine viene creata in modo diverso.
            $im = imageCreateFromVersion($x, $y);

            $gold          = imagecolorallocate($im, 255, 215, 0); //Colore rettangolo.
            $ombra         = imagecolorallocate($im, 0, 0, 0); //Colore delle ombre.
            $colore_testo  = imagecolorallocate($im, $text_vet_color[0], $text_vet_color[1], $text_vet_color[2]); //Colore del testo.

            //INIZIO COLORAZIONE CENTRALE.
                if($to_img == false) //Colorazione in sfumatura.
                {
                    $metodo_sfondo = strtolower($_GET["metodo_sfondo"]);
                    switch($metodo_sfondo)
                    {
                    case "circle":
                        $bg = imagecolorallocate($im, $start_bg_red, $start_bg_green, $start_bg_blue);
                        imagefill($im, 0, 0, $bg);
                        imagecolordeallocate($im, $bg);

                        $size_to_div_color = ceil(sqrt(pow($x, 2) + pow($y, 2)) / 4);
                        $center_x = floor($x / 2);
                        $center_y = floor($y / 2);

                        //Proporzioni per l'attenuazione del colore.
                        $prop_bg_red    = ($start_bg_red - $end_bg_red) / $size_to_div_color;
                        $prop_bg_green  = ($start_bg_green - $end_bg_green) / $size_to_div_color;
                        $prop_bg_blue   = ($start_bg_blue - $end_bg_blue) / $size_to_div_color;

                        for($i = 0; $i < $size_to_div_color; ++$i) //Con un ciclo for coloro l'immagine a cerchi di 1 px.
                        {
                            $bg_red    = $start_bg_red - ($i * $prop_bg_red);
                            $bg_green  = $start_bg_green - ($i * $prop_bg_green);
                            $bg_blue   = $start_bg_blue - ($i * $prop_bg_blue);
                            $size = ($size_to_div_color - $i) * 2;

                            $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                            imagefilledarc($im, $center_x, $center_y, $size, $size, 0, 360, $col, IMG_ARC_PIE);
                            imagecolordeallocate($im, $col);
                        }
                        break;
                    case "radial":
                        $size_to_div_color = floor($y / 2);

                        //Proporzioni per l'attenuazione del colore.
                        $prop_bg_red    = ($start_bg_red - $end_bg_red) / $size_to_div_color;
                        $prop_bg_green  = ($start_bg_green - $end_bg_green) / $size_to_div_color;
                        $prop_bg_blue   = ($start_bg_blue - $end_bg_blue) / $size_to_div_color;

                        for($i = 0; $i < $size_to_div_color; ++$i) //Con un ciclo for coloro l'immagine a rettangoli di 1 px.
                        {
                            $bg_red    = $start_bg_red - ($i * $prop_bg_red);
                            $bg_green  = $start_bg_green - ($i * $prop_bg_green);
                            $bg_blue   = $start_bg_blue - ($i * $prop_bg_blue);

                            $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                            imagerectangle($im, $i, $i, $x-$i-1, $y-$i-1, $col);
                            imagecolordeallocate($im, $col);
                        }
                        break;
                    default:
                        $size_to_div_color = $y;
                        if($metodo_sfondo == "vertical")
                            $size_to_div_color = $x;

                        //Proporzioni per l'attenuazione del colore.
                        $prop_bg_red    = ($start_bg_red - $end_bg_red) / $size_to_div_color;
                        $prop_bg_green  = ($start_bg_green - $end_bg_green) / $size_to_div_color;
                        $prop_bg_blue   = ($start_bg_blue - $end_bg_blue) / $size_to_div_color;

                        for($i = 0; $i < $size_to_div_color; ++$i) //Con un ciclo for coloro l'immagine a strisce di 1 px.
                        {
                            $bg_red    = $start_bg_red - ($i * $prop_bg_red);
                            $bg_green  = $start_bg_green - ($i * $prop_bg_green);
                            $bg_blue   = $start_bg_blue - ($i * $prop_bg_blue);

                            $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                            if($metodo_sfondo == "vertical")
                                imageline($im, $i, 0, $i, $y, $col);
                            else imageline($im, 0, $i, $x, $i, $col);
                            imagecolordeallocate($im, $col);
                        }
                        break;
                    }
                }
                else //Immagine di sfondo.
                {
                    $src_bg = imagecreatefrompng("images/bg/$img_name.png"); //Apro l'immagine di sfondo.
                    imagecopyresampled($im, $src_bg, 0, 0, 0, 0, $x, $y, imagesx($src_bg), imagesy($src_bg)); //ridimensiono alle dimensioni di quella di destinazione.
                    imagedestroy($src_bg);
                }
            //FINE COLORAZIONE CENTRALE.

            //INIZIO INSERIMENTO EFFETTO.
                if($effect != '')
                {
                    $src_effect = imagecreatefrompng("images/effects/$effect.png");
                    $width_effect = imagesx($src_effect);
                    $height_effect = imagesy($src_effect);
                    for($i = 0; $i < ceil($y / $height_effect); ++$i)
                        for($j = 0; $j < ceil($x / $width_effect); ++$j)
                            imagecopyresampled($im, $src_effect, $j*$width_effect, $i*$height_effect, 0, 0, $width_effect, $height_effect, $width_effect, $height_effect);
                    imagedestroy($src_effect);
                }
            //FINE INSERIMENTO EFFETTO.

            //INIZIO FILTRI.
                if(isset($_GET["filter"]) && $_GET["filter"] != '')
                {
                    switch(strtolower($_GET["filter"]))
                    {
                    case "grayscale":
                        imagefilter($im, IMG_FILTER_GRAYSCALE);
                        break;
                    case "sepia":
                        imagefilter($im, IMG_FILTER_GRAYSCALE);
                        imagefilter($im, IMG_FILTER_COLORIZE, 112, 66, 20);
                        break;
                    case "negate":
                        imagefilter($im, IMG_FILTER_NEGATE);
                        break;
                    }
                }
            //FINE FILTRI.

            //INIZIO COLORAZIONE BORDO.
                imagerectangle($im, 1, 1, $x-2, $y-2, $gold); //Disegno un rettangolo gold ad 1 px dal bordo.
            //FINE COLORAZIONE BORDO.

            //INIZIO LINEA A META' IMMAGINE.
                $pos_line = $y / 2.5; //Calcolo la posizione della linea in proporzione all'altezza dell'immagine.
                imageline($im, $y+6, $pos_line+1, $x-$y-73, $pos_line+1, $ombra); //Disegno prima l'ombra spostata di 1px verso il basso e verso destra.
                imageline($im, $y+5, $pos_line, $x-$y-74, $pos_line, $gold); //Disegno la linea.
            //FINE LINEA A META' IMMAGINE.

            //INIZIO NOME PG.
                imagettftext($im, $dim_nome_pg, 0, $y+8, $pos_line-4, $ombra, $font, $name_string); //Ombra del nome del pg.
                imagettftext($im, $dim_nome_pg, 0, $y+7, $pos_line-5, $colore_testo, $font, $name_string); //Nome del pg.
            //FINE NOME PG.

            //INIZIO STATS.
                $prop_text = $y / 5 - 1; //Mi calcolo il distanziamento tra una stat e l'altra in proporzione all'altezza dell'immagine.
                for($i = 0; $i < count($show_stats); ++$i) //Stampo le stats selezionate.
                {
                    $box = imagettfbbox($dim_stats, 0, $font, $show_stats[$i]);
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-5, 13+$prop_text*$i, $ombra, $font, $show_stats[$i]); //Ombra della stat.
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-6, 12+$prop_text*$i, $colore_testo, $font, $show_stats[$i]); //Stat.
                }
            //FINE STATS.

            //INIZIO COPIA CLASSE.
                $src_avatar = imagecreatefromstring(file_get_contents($avatar_img));
                if($external_image) //Se � un'immagine esterna la rimpicciolisco di 10px e la riposiziono.
                    imagecopyresampled($im, $src_avatar, 5, 5, 0, 0, $y-10, $y-10, imagesx($src_avatar), imagesy($src_avatar));
                else //Immagine interna, effettuo il ridimensionamento a seconda del tipo di immagine scelta.
                    imagecopyresampled($im, $src_avatar, 5, ($is_gif ? 5 : 0), 0, 0, $y-($is_gif ? 10 : 0), $y-($is_gif ? 10 : 0), imagesx($src_avatar), imagesy($src_avatar));
                imagedestroy($src_avatar);
            //FINE COPIA CLASSE.

            //INIZIO LIVELLO-CLASSE-RAZZA.
                imagettftext($im, $dim_addictional_info, 0, $y+8, $pos_line+18, $ombra, $font, $string_info); //Ombra delle informazioni addizionali.
                imagettftext($im, $dim_addictional_info, 0, $y+7, $pos_line+17, $colore_testo, $font, $string_info); //Informazioni addizionali.
            //FINE LIVELLO-CLASSE-RAZZA.

            //INIZIO GILDA-SERVER.
                imagettftext($im, $dim_addictional_info, 0, $y+8, $pos_line+36, $ombra, $font, $string_guild); //Ombra guild, server.
                imagettftext($im, $dim_addictional_info, 0, $y+7, $pos_line+35, $colore_testo, $font, $string_guild); //Guild, server.
            //FINE GILDA-SERVER.

            //INIZIO RIDIMENSIONAMENTO IMMAGINE (MOMENTANEAMENTE DISABILITATO).
                //Se da config � abilitato il ridimensionamento delle immagini.
                if($image_resize_enabled && ((isset($_GET['x']) && $_GET['x'] != '' && is_numeric($_GET['x'])) || (isset($_GET['y']) && $_GET['y'] != '' && is_numeric($_GET['y']))))
                {
                    //Mi calcolo le proporzioni delle dimensioni x e y.
                    if(isset($_GET['x']) && $_GET['x'] != '' && is_numeric($_GET['x']))
                        $prop_x = $_GET['x'] / $x;
                    else $prop_x = 1;
                    if(isset($_GET['y']) && $_GET['y'] != '' && is_numeric($_GET['y']))
                        $prop_y = $_GET['y'] / $y;
                    else $prop_y = 1;

                    //Funzionamento:
                    // - Se le proporzioni sono entrambe maggiori di 1 scelgo la pi� grande,
                    // - Se le proporzioni sono entrambe minori di 1 scelgo la pi� piccola,
                    // - Se le proporzioni sono una minore di 1 e una maggiore di 1 scelgo la pi� piccola,
                    // - Se una delle proporzioni � uguale ad 1 scelgo quella diversa da 1.
                    if($prop_x > 1 && $prop_y > 1)
                        $prop = $prop_x>$prop_y ? $prop_x : $prop_y;
                    else if($prop_x < 1 && $prop_y < 1)
                        $prop = $prop_x<$prop_y ? $prop_x : $prop_y;
                    else if(($prop_x != 1 && $prop_y == 1) || ($prop_x < 1 && $prop_y > 1))
                        $prop = $prop_x;
                    else if(($prop_x == 1 && $prop_y != 1) || ($prop_x > 1 && $prop_y < 1))
                        $prop = $prop_y;
                    else $prop = 1;

                    //Se la proporzione � cambiata � necessario ridimensionare l'immagine.
                    if($prop != 1)
                    {
                        $new_x = $x * $prop;
                        $new_y = $y * $prop;
                        $img_resized = imageCreateFromVersion($new_x, $new_y);
                        if(imagecopyresampled($img_resized, $im, 0, 0, 0, 0, $new_x, $new_y, $x, $y))
                        {
                            imagedestroy($im);
                            $im = $img_resized;
                        }
                    }
                }
            //FINE RIDIMENSIONAMENTO IMMAGINE.

            //INIZIO SALVATAGGIO DELL'IMMAGINE.
                $realm_name = mysql_real_escape_string(strtoupper($_GET["server"])); //Evita le SQL-Injections.
                $img_save_name = $realm_name . "_$pg_name.png"; //SERVER_NomePg.png.
                imagepng($im, "saved/$img_save_name"); //Salvo l'immagine nella cartella "saved".

                //Salvo un record identificativo su DB.
                $site_connection->query("REPLACE INTO immaginisalvate VALUES ($pg_GUID, '$realm_name', '" . getOrderQueryString($_SERVER["QUERY_STRING"]) . "', '$img_save_name', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());", true);
            //FINE SALVATAGGIO DELL'IMMAGINE.

            //INIZIO DEALLOCAZIONE COLORI.
                imagecolordeallocate($im, $gold);
                imagecolordeallocate($im, $ombra);
                imagecolordeallocate($im, $colore_testo);
            //FINE DEALLOCAZIONE COLORI.

            imagepng($im);
            imagedestroy($im);
        }else header("location: images/$dati_errati");
    }
?>