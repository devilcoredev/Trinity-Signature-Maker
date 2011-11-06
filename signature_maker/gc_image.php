<?php
    //Inclusioni librerie.
    include("config.php");
?>
<?php
    //Restituisce true se l'immagine è png.
    function isPng($fileName)
    {
        return (strncmp(pathinfo($fileName, PATHINFO_EXTENSION), "png", 3) == 0);
    }

    //Restituisce un numero decimale a partire da un numero esadecimale nella forma "HHHHHH".
    function GetRGBFromHex($input)
    {
        $input = strtolower($input);

        $vet[0] = hexdec(substr($input, 0, 2));
        $vet[1] = hexdec(substr($input, 2, 2));
        $vet[2] = hexdec(substr($input, 4, 2));

        return $vet;
    }

    //Funzione che restituisce la query string ordinata alfabeticamente.
    function getOrderQueryString($input)
    {
        $output = '';

        $input = strtolower($input);
        $arr = explode('&', $input); //Divido la query string in un array contenente chiave=valore.
        $num_ele = count($arr);
        sort($arr); //Ordino l'array in ordine alfabetico (in questo caso vengono ordinate solo le chiavi della querystring).

        for($i=0; $i<$num_ele; ++$i) //Ricostruisco la query string ordinata alfabeticamente.
        {
            $output .= $arr[$i];
            if($i < ($num_ele - 1))
                $output .= '&';
        }

        return mysql_real_escape_string($output); //Evita le SQL-Injection.
    }

    //Funzione che effettua una conversione da UInt32 a float.
    function UInt32ToFloat($input)
    {
        $bin_string = decbin($input); //Mi ricavo la conversione binaria della cifra.
        $len = strlen($bin_string);
        if($len > 32)
            return -1;

        /*
            La struttura del float a 32 bit è la seguente:
                +---+----------+-------------------------+
                | x | xxxxxxxx | xxxxxxxxxxxxxxxxxxxxxxx |
                +---+----------+-------------------------+
                  ^       ^                 ^
                Sign  Exponent           Mantissa

            Il bit di segno (Sign) è trascurabile poichè per gli unsigned non esiste.
            La formula per calcolare il float a partire dal numero di bit è:
                    Sign    Exponent-Offset
            N = (-1)     * 2                * (1.Mantissa)
            La lunghezze in bit dell'esponente sono le seguenti:
             - Float 32 bit: 8 bits,
             - Float 64 bit: 11 bits,
             - Float 80 bit: 15 bits.
            Le lunghezze in bit della mantissa sono le seguenti:
             - Float 32 bit: 23 bits,
             - Float 64 bit: 52 bits,
             - Float 80 bit: 64 bits.
            Gli offset sono i seguenti:
             - Float 32 bit: 127,
             - Float 64 bit: 1023,
             - Float 80 bit: 16383.
            Poichè i numeri sono > 0 in questo caso possiamo trascurare il bit di segno, portando la formula a:
                 Exponent-Offset
            N = 2                * (1.Mantissa)
        */

        for($i=0; $i<(32-$len); ++$i) //Completo la successione di bit, mettendo gli 0 in testa a quella che già ho.
            $bin_string = '0' . $bin_string;
        $exp = intval(substr($bin_string, 1, 8), 2); //Prendo i bit dell'esponente (8 a partire dalla posizione 1).
        $mant = intval(substr($bin_string, 9), 2); //Prendo la mantissa (a partire dalla posizione 9, i restanti 23 bit).
        $f_mant = floatval("1.$mant"); //Mi ricavo 1.Mantissa.

        return (pow(2, $exp-127) * $f_mant) * 1.05; //Utilizzo la formula per ricavare il float (il valore è in difetto del 5% circa, applico la correzione forzata).
    }

    //La funzione ricava tutte le stats del PG, cerca prima nella tabella character_stats, se non trova nulla cerca in armory_character_stats.
    function fill_stats(&$input, $intput_conn, $guid)
    {
        $find_stats = false;
        $query = "SELECT maxhealth, maxpower1 AS mana, maxpower6 AS rune, maxpower7 AS runicPower, strength, agility, stamina, intellect, spirit,
                    armor, blockPct, dodgePct, parryPct, critPct, rangedCritPct, spellCritPct, attackPower, rangedAttackPower, spellPower,
                    resilience FROM character_stats WHERE guid = $guid;";
        if($result = mysql_query($query, $intput_conn)) //Armory interna (se abilitata).
        {
            if($row = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $find_stats = true;
                foreach($row as $i => $value)
                {
                    $input["$i"] = $value;
                }
            }
            mysql_free_result($result);
        }

        if(!$find_stats)
        {
            if($result = mysql_query("SELECT data FROM armory_character_stats WHERE guid = $guid;", $intput_conn))
            {
                if($row = mysql_fetch_array($result, MYSQL_ASSOC)) //Shadez armory (solo se l'armory interna non è abilitata).
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
                    for($i=0x0495; $i<0x049A; ++$i) //Il valore minimo è lo spell power attuale del PG.
                        $input["spellPower"]     = min($input["spellPower"], $input_array[$i]);

                    $input["spellCritPct"]       = UInt32ToFloat($input_array[0x0409]);                     //OBJECT_END + UNIT_END + PLAYER_SPELL_CRIT_PERCENTAGE1.
                    for($i=0x040A; $i<0x0410; ++$i) //Il valore minimo è lo spell crit attuale del PG.
                        $input["spellCritPct"]   = min($input["spellCritPct"], UInt32ToFloat($input_array[$i]));
                }
                mysql_free_result($result);
            }
        }
    }
?>
<?php
    //Variabili globali.
    $do_next_step    = true;      //Si potrà procedere alle altre funzioni solo se $do_next_step = true.
    $pg_name         = '';        //Nome del personaggio.
    $name_string     = '';        //Nome del personaggio comprensivo di titolo.
    $string_info     = '';        //Informazioni addizionali (razza, livello, classe).
    $is_gif          = false;     //La variabile posta a false indica che l'immagine è png, altrimenti è gif.
    $external_image  = false;     //La variabile indica se l'avar impostato è interno o esterno al sito.
    $avatar_img      = '';        //Link all'avatar.
    $string_guild    = '';        //Rank, guild, realm, server.
    $im              = FALSE;     //Link all'immagine.
    $to_make_image   = true;      //Serve a controllare se l'immagine deve essere costruita.
    $pg_GUID         = 0;         //ID univoco del personaggio.
    $spec_name       = '';        //Nome della spec.
?>
<?php
    //Controllo preliminare sull'esistenda dell'immagine (se la stessa immagine esiste già non la rielaboro).
    if($connessione = mysql_connect($site_host, $site_username, $site_password, true))
    {
        if(mysql_select_db($site_database, $connessione))
        {
            $query_string = getOrderQueryString($_SERVER["QUERY_STRING"]); //Controllo se esiste un'immagine salvata con la stessa query string.
            if($result = mysql_query("SELECT * FROM immaginisalvate WHERE queryString = '$query_string';", $connessione))
            {
                if($row = mysql_fetch_array($result, MYSQL_ASSOC))
                    if(file_exists("saved/" . $row["nomeImmagine"]))
                    {
                        $to_make_image = false;
                        mysql_query("UPDATE immaginisalvate SET ultimaModifica = UNIX_TIMESTAMP() WHERE queryString = '$query_string';", $connessione);
                        header("location: saved/" . $row["nomeImmagine"]); //Se l'immagine esiste faccio un redirect a quella preesistete.
                    }
                mysql_free_result($result);
            }
        }
        mysql_close($connessione);
    }
?>
<?php
    //Recupero dei dati per la creazione dell'immagine.
    if($to_make_image)
    {
        if(GDVersion()==0) //Nel sistema non esiste GC.
            $do_next_step = false;

        if($do_next_step)
        {
            //L'utente ha selezionato uno sfondo diverso da quello di default (graduazione di rosso).
            if(isset($_GET["sfondo"]) && $_GET["sfondo"]!='')
            {
                //Lo sfondo è un'immagine, (comincia con bg_), mi costruisco il link all'immagine.
                if(!strncmp($_GET["sfondo"], "bg_", 3))
                {
                    //Tutti gli sfondi utilizzati per la firma sono in png, cominciano con bg_ e sono contenuti nella directory "images/bg".
                    if(file_exists("images/bg/" . $_GET["sfondo"] . ".png") && is_file("images/bg/" . $_GET["sfondo"] . ".png"))
                    {
                        $to_img = true;
                        $img_name = $_GET["sfondo"];
                    }
                }
                else //Lo sfondo è un colore, cerco le 3 graduazioni nel codice esadecimale.
                {
                    $bg_vet = GetRGBFromHex($_GET["sfondo"]);

                    $to_img          = false;
                    $start_bg_red    = $bg_vet[0];
                    $start_bg_green  = $bg_vet[1];
                    $start_bg_blue   = $bg_vet[2];
                }
            }

            //L'utente ha selezionato un colore di testo diverso da quello di default, elaboro il codice esadecimale.
            if(isset($_GET["colore_testo"]) && $_GET["colore_testo"]!='')
                $text_vet_color = GetRGBFromHex($_GET["colore_testo"]);

            //L'utente ha selezionato un carattere per il testo diverso da quello di default, lo estraggo.
            if(isset($_GET["text_font"]) && $_GET["text_font"]!='')
            {
                $name_font = $_GET["text_font"];
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

            //Controllo dei campi inseriti, e controllo dell'esistenza del realm selezionato.
            if(isset($_GET["server"]) && $_GET["server"]!='' && isset($_GET["nome_pg"]) && $_GET["nome_pg"]!='')
            {
                $server_id = strtolower($_GET["server"]);
                if(isset($realm_name["$server_id"]))
                {
                    //Connessione al database dei characters e recupero dei dati.
                    if($connessione = mysql_connect($host["$server_id"], $username["$server_id"], $password["$server_id"], true))
                    {
                        if(mysql_select_db($database["$server_id"], $connessione))
                        {
                            //Primo carattere maiuscolo, resto minuscolo. Esempi: TEST=>Test, test=>Test, tEsT=>Test.
                            $nome_pg = mysql_real_escape_string(ucfirst(strtolower($_GET["nome_pg"]))); //Evita le SQL-Injection.

                            //Nome, Razza, Classe, Livello, Titolo, Spec, Punti arena, Punti honor, Kill PvP.
                            $query = "SELECT guid, name, race, class, gender, level, chosenTitle, activespec, arenaPoints, totalHonorPoints,
                                        totalKills FROM characters WHERE name = '$nome_pg';";
                            if($result = mysql_query($query, $connessione))
                            {
                                if($row = mysql_fetch_array($result, MYSQL_ASSOC))
                                {
                                    $pg_GUID = $row["guid"];
                                    $pg_name = $row["name"];
                                    $spec_id = $row["activespec"];
                                    fill_stats($row, $connessione, $pg_GUID); //Riempio il resto delle stats.

                                    //Se il pg ha un titolo identificato (cercando nel database del sito) lo inserisco nella firma.
                                    $name_string = $row["name"];
                                    if($row["chosenTitle"] != 0)
                                        if($site_connection = mysql_connect($site_host, $site_username, $site_password, true))
                                        {
                                            if(mysql_select_db($site_database, $site_connection))
                                                if($site_result = mysql_query("SELECT nameString FROM chartitles WHERE titleId = " . $row["chosenTitle"] . ';', $site_connection))
                                                {
                                                    if($title_row = mysql_fetch_array($site_result, MYSQL_ASSOC))
                                                        $name_string = str_replace("%s", $row["name"], $title_row["nameString"]);
                                                    mysql_free_result($site_result);
                                                }
                                            mysql_close($site_connection);
                                        }

                                    //Talenti, eseguo questa operazione a prescindere per trovare il nome della spec.
                                    $talents[0] = 0;
                                    $talents[1] = 0;
                                    $talents[2] = 0;
                                    if($talents_result = mysql_query("SELECT spell FROM character_talent WHERE guid = $pg_GUID AND spec = $spec_id;", $connessione))
                                    {
                                        while($talents_row = mysql_fetch_array($talents_result, MYSQL_ASSOC))
                                            if($vet = getTalentInfo($talents_row["spell"]))
                                            {
                                                $tab = $vet["tabPage"];
                                                $talents[$tab] += $vet["rankId"];
                                            }
                                        mysql_free_result($talents_result);
                                    }
                                    $row["talents"] = $talents[0] . '/' . $talents[1] . '/' . $talents[2]; //Talenti nella forma (x/x/x).

                                    //Nome della spec.
                                    $max_talent = max($talents[0], $talents[1], $talents[2]);
                                    if($max_talent)
                                    {
                                        $i_t = array_search($max_talent, $talents);
                                        $spec_name .= ' ' . $tab_names[$row["class"]][$i_t];
                                    }

                                    //Livello - Classe - Razza.
                                    $string_info = "Level " . $row["level"] . ' ' . $races[$row["race"]] . ' ' . $classes[$row["class"]]["name"] . $spec_name;

                                    //Se viene dato l'url di un'immagine png valida lo inserisco, altrimenti inserisco quella di default della classe.
                                    if(isset($_GET["url_image"]) && $_GET["url_image"]!='' && isPng($_GET["url_image"]) && imagecreatefrompng($_GET["url_image"]))
                                    {
                                        $avatar_img = $_GET["url_image"];
                                        $external_image = true;
                                    }
                                    else
                                    {
                                        //L'immagine indica sia la razza che la classe del personaggio.
                                        if(isset($_GET["type_image"]) && $_GET["type_image"]=="race_class")
                                        {
                                            $is_gif = true;
                                            //Le immagini sono nella forma "gender-race-class.gif".
                                            $avatar_img = $row["gender"] . '-' . $row["race"] . '-' . $row["class"] . ".gif";
                                            if($row["class"]==6 || $row["level"]>=80) //Se è livello 80 oppure è un Death Knight seleziono gli avatar livello 80 (i DK hanno solo avatar livello 80).
                                                $avatar_img = "Level_80_Forum_Avatars/$avatar_img";
                                            else if($row["level"]>=70 && $row["level"]<80) //Livello compreso tra 70 e 79, seleziono gli avatar livello 70.
                                                $avatar_img = "Level_70_Forum_Avatars/$avatar_img";
                                            else if($row["level"]>=60 && $row["level"]<70) //Livello compreso tra 60 e 69, seleziono gli avatar livello 60.
                                                $avatar_img = "Level_60_Forum_Avatars/$avatar_img";
                                            else $avatar_img = "Level_1_Forum_Avatars/$avatar_img"; //Livello compreso tra 1 e 59, seleziono gli avatar livello 1.
                                        }else $avatar_img = $classes[$row["class"]]["img"] . ".png"; //L'immagine indica solo la classe del personaggio, faccio riferimento ai config.
                                        $avatar_img = "images/classes/$avatar_img"; //Completo il path dell'avatar.
                                    }

                                    //Guild e Rank.
                                    $guild_query = "SELECT guild.name, guild_rank.rname FROM guild_member, guild, guild_rank WHERE guild_member.guildid = guild.guildid
                                                    AND guild_member.rank = guild_rank.rid AND guild_rank.guildid = guild.guildid AND guild_member.guid = $pg_GUID;";
                                    if($guild_result = mysql_query($guild_query, $connessione))
                                    {
                                        if($guild_row = mysql_fetch_array($guild_result, MYSQL_ASSOC))
                                            $string_guild = '"' . $guild_row["rname"] . "\" of <" . $guild_row["name"] . "> "; //"Rank" of <Nome Guild>
                                        mysql_free_result($guild_result);
                                    }
                                    $string_guild .= "[$server_name " . $realm_name["$server_id"] . ']'; //[Nome_Server Nome_Realm]

                                    //Stats (messe in array per comodità).
                                    $index = 0;
                                    for($i=1; $i<6; ++$i) //Al massimo 5 stats a scelta.
                                        if(isset($_GET["stat$i"]) && $_GET["stat$i"]!='') //Controllo se esiste il template di quella stat.
                                        {
                                            $get_stat = strtolower($_GET["stat$i"]);

                                            //Achievements, eseguo questa operazione solo se richiesta per risparmiare risorse.
                                            if(($get_stat=="achievements" && !isset($row["achievements"])) || ($get_stat=="achievementpoints" && !isset($row["achievementPoints"])))
                                            {
                                                $ach_count = 0;
                                                $ach_points = 0;
                                                //Seleziono solo gli achievements che danno punti, il resto sono "Feats of Strength" oppure first kill.
                                                if($achievements_result = mysql_query("SELECT achievement FROM character_achievement WHERE guid = $pg_GUID;", $connessione))
                                                {
                                                    while($achievements_row = mysql_fetch_array($achievements_result, MYSQL_ASSOC))
                                                        if($punti = isValidAchievement($achievements_row["achievement"]))
                                                        {
                                                            $ach_count += 1; //Incremento il conto degli achievements ottenuti.
                                                            $ach_points += $punti; //Incremento il punteggio degli achivements ottenuti.
                                                        }
                                                    mysql_free_result($achievements_result);
                                                }
                                                //Inserisco nel vettore delle stats i dati ottenuti.
                                                $row["achievements"] = $ach_count;
                                                $row["achievementPoints"] = $ach_points;
                                            }

                                            if(isset($stats["$get_stat"]["name"]))
                                            {
                                                $field_name = $stats["$get_stat"]["field_name"];

                                                if(is_numeric($row["$field_name"])) //Effettuo l'arrotondamento solo se il campo è un numero.
                                                    $field_value = round($row["$field_name"], 2);
                                                else $field_value = $row["$field_name"];

                                                //Sostituisco i valori alle stringhe di template.
                                                $temp_string = str_replace("%s", $field_value, $stats["$get_stat"]["text"]);

                                                //Check per evitare di mettere stats doppie.
                                                if(!in_array($temp_string, $show_stats))
                                                {
                                                    $show_stats[$index] = $temp_string;
                                                    $index++;
                                                }
                                            }
                                        }
                                }else $do_next_step = false;
                                mysql_free_result($result);
                            }else $do_next_step = false;
                        }else $do_next_step = false;
                        mysql_close($connessione);
                    }else $do_next_step = false;
                }else $do_next_step = false;
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
            if(GDVersion() == 1)
                $im = imagecreate($x, $y);
            else $im = imagecreatetruecolor($x, $y);

            $gold          = imagecolorallocate($im, 255, 215, 0); //Colore rettangolo.
            $ombra         = imagecolorallocate($im, 0, 0, 0); //Colore delle ombre.
            $colore_testo  = imagecolorallocate($im, $text_vet_color[0], $text_vet_color[1], $text_vet_color[2]); //Colore del testo.

            //INIZIO COLORAZIONE CENTRALE.
                if($to_img == false) //Colorazione in sfumatura.
                {
                    //Proporzioni per l'attenuazione dei colori verso il nero (r=0, g=0, b=0).
                    $prop_bg_red    = ($start_bg_red/$y)/($y/$proporzione_sfumatura_y);
                    $prop_bg_green  = ($start_bg_green/$y)/($y/$proporzione_sfumatura_y);
                    $prop_bg_blue   = ($start_bg_blue/$y)/($y/$proporzione_sfumatura_y);

                    $bg_red    = $start_bg_red;
                    $bg_green  = $start_bg_green;
                    $bg_blue   = $start_bg_blue;
                    for($i=0; $i<$y; ++$i) //Con un ciclo for coloro l'immagine a strisce di 1 px.
                    {
                        $bg_red   -= ($i * $prop_bg_red);
                        $bg_green -= ($i * $prop_bg_green);
                        $bg_blue  -= ($i * $prop_bg_blue);

                        $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                        imagefilledrectangle($im, 0, $i, $x, $i+1, $col);
                        imagecolordeallocate($im, $col);
                    }
                }
                else //Immagine di sfondo.
                {
                    $src_bg = imagecreatefrompng("images/bg/$img_name.png"); //Apro l'immagine di sfondo.
                    list($width_bg, $height_bg) = getimagesize("images/bg/$img_name.png");
                    imagecopyresized($im, $src_bg, 0, 0, 0, 0, $x, $y, $width_bg, $height_bg); //ridimensiono alle dimensioni di quella di destinazione.
                    imagedestroy($src_bg);
                }
            //FINE COLORAZIONE CENTRALE.

            //INIZIO COLORAZIONE BORDO.
                imagerectangle($im, 1, 1, $x-2, $y-2, $gold); //Disegno un rettangolo gold ad 1 px dal bordo.
            //FINE COLORAZIONE BORDO.

            //INIZIO LINEA A META' IMMAGINE.
                $pos_line = $y/2.5; //Calcolo la posizione della linea in proporzione all'altezza dell'immagine.
                imageline($im, $y+6, $pos_line+1, $x-$y-73, $pos_line+1, $ombra); //Disegno prima l'ombra spostata di 1px verso il basso e verso destra.
                imageline($im, $y+5, $pos_line, $x-$y-74, $pos_line, $gold); //Disegno la linea.
            //FINE LINEA A META' IMMAGINE.

            //INIZIO NOME PG.
                imagettftext($im, $dim_nome_pg, 0, $y+8, $pos_line-4, $ombra, $font, $name_string); //Ombra del nome del pg.
                imagettftext($im, $dim_nome_pg, 0, $y+7, $pos_line-5, $colore_testo, $font, $name_string); //Nome del pg.
            //FINE NOME PG.

            //INIZIO STATS.
                $prop_text = $y/5 - 1; //Mi calcolo il distanziamento tra una stat e l'altra in proporzione all'altezza dell'immagine.
                for($i=0; $i<count($show_stats); ++$i) //Stampo le stats selezionate.
                {
                    $box = imagettfbbox($dim_stats, 0, $font, $show_stats[$i]);
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-5, 13+$prop_text*$i, $ombra, $font, $show_stats[$i]); //Ombra della stat.
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-6, 12+$prop_text*$i, $colore_testo, $font, $show_stats[$i]); //Stat.
                }
            //FINE STATS.

            //INIZIO COPIA CLASSE.
                if(!$is_gif) //L'immagine selezionata è in png, vuol dire che contiene solo la classe del pg, la ridimensiono quanto l'altezza della firma.
                {
                    $src_avatar = imagecreatefrompng($avatar_img);
                    list($width_avatar, $height_avatar) = getimagesize($avatar_img);
                    //Se è un'immagine esterna la rimpicciolisco di 10px e la riposiziono.
                    imagecopyresized($im, $src_avatar, 5, ($external_image ? 5 : 0), 0, 0, $y-($external_image ? 10 : 0), $y-($external_image ? 10 : 0), $width_avatar, $height_avatar);
                    imagedestroy($src_avatar);
                }
                else //L'immagine è in gif, riduco le sue dimensioni di 10px e la centro in un quadrato a sinistra.
                {
                    $src_avatar = imagecreatefromgif($avatar_img);
                    list($width_avatar, $height_avatar) = getimagesize($avatar_img);
                    imagecopyresized($im, $src_avatar, 5, 5, 0, 0, $y-10, $y-10, $width_avatar, $height_avatar);
                    imagedestroy($src_avatar);
                }
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
                //Se da config è abilitato il ridimensionamento delle immagini.
                if($image_resize_enabled && ((isset($_GET['x']) && $_GET['x']!='' && is_numeric($_GET['x'])) || (isset($_GET['y']) && $_GET['y']!='' && is_numeric($_GET['y']))))
                {
                    //Mi calcolo le proporzioni delle dimensioni x e y.
                    if(isset($_GET['x']) && $_GET['x']!='' && is_numeric($_GET['x']))
                        $prop_x = $_GET['x'] / $x;
                    else $prop_x = 1;
                    if(isset($_GET['y']) && $_GET['y']!='' && is_numeric($_GET['y']))
                        $prop_y = $_GET['y'] / $y;
                    else $prop_y = 1;

                    //Funzionamento:
                    // - Se le proporzioni sono entrambe maggiori di 1 scelgo la più grande,
                    // - Se le proporzioni sono entrambe minori di 1 scelgo la più piccola,
                    // - Se le proporzioni sono una minore di 1 e una maggiore di 1 scelgo la più piccola,
                    // - Se una delle proporzioni è uguale ad 1 scelgo quella diversa da 1.
                    if($prop_x>1 && $prop_y>1)
                        $prop = $prop_x>$prop_y ? $prop_x : $prop_y;
                    else if($prop_x<1 && $prop_y<1)
                        $prop = $prop_x<$prop_y ? $prop_x : $prop_y;
                    else if(($prop_x!=1 && $prop_y==1) || ($prop_x<1 && $prop_y>1))
                        $prop = $prop_x;
                    else if(($prop_x==1 && $prop_y!=1) || ($prop_x>1 && $prop_y<1))
                        $prop = $prop_y;
                    else $prop = 1;

                    //Se la proporzione è cambiata è necessario ridimensionare l'immagine.
                    if($prop!=1)
                    {
                        $new_x = $x * $prop;
                        $new_y = $y * $prop;
                        if(GDVersion() == 1)
                            $img_resized = imagecreate($new_x, $new_y);
                        else $img_resized = imagecreatetruecolor($new_x, $new_y);
                        if(imagecopyresized($img_resized, $im, 0, 0, 0, 0, $new_x, $new_y, $x, $y))
                        {
                            imagedestroy($im);
                            $im = $img_resized;
                        }
                    }
                }
            //FINE RIDIMENSIONAMENTO IMMAGINE.

            //INIZIO SALVATAGGIO DELL'IMMAGINE.
                if($connessione = mysql_connect($site_host, $site_username, $site_password, true))
                {
                    if(mysql_select_db($site_database, $connessione))
                    {
                        $realm_name = mysql_real_escape_string(strtoupper($_GET["server"])); //Evita le SQL-Injections.
                        $img_save_name = $realm_name . "_$pg_name.png"; //SERVER_NomePg.png.
                        imagepng($im, "saved/$img_save_name"); //Salvo l'immagine nella cartella "saved".

                        //Salvo un record identificativo su DB.
                        mysql_query("REPLACE INTO immaginisalvate VALUES ($pg_GUID, '$realm_name', '" . getOrderQueryString($_SERVER["QUERY_STRING"]) . "', '$img_save_name', UNIX_TIMESTAMP());", $connessione);
                    }
                    mysql_close($connessione);
                }
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