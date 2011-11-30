<?php
    //Library inclusion.
    include("config.php");
?>
<?php
    //Returns a decimal number from a hex number in the form "HHHHHH".
    function GetRGBFromHex($input)
    {
        return array(hexdec(substr($input, 0, 2)), hexdec(substr($input, 2, 2)), hexdec(substr($input, 4, 2)));
    }

    //Returns the queryString ordered alphabetically.
    function getOrderQueryString($input)
    {
        $output = '';

        $input = strtolower($input);
        $arr = explode('&', $input); //I divide the queryString into an array containing key = value.
        $count_elements = count($arr);
        sort($arr); //Sort the array in alphabetical order (in this case are ordered only the keys of the queryString).

        for($i=0; $i<$count_elements; ++$i) //Reconstruct the queryString ordered alphabetically.
        {
            $output .= $arr[$i];
            if($i < ($count_elements - 1))
                $output .= '&';
        }

        return mysql_real_escape_string($output); //To avoid SQL-Injections.
    }

    //Function that performs a conversion from UInt32 to float.
    function UInt32ToFloat($input)
    {
        $txt = unpack('f', pack('L', $input));
        return $txt[1];
    }

    function getItemTemplate($item_id)
    {
        global $site_host, $site_username, $site_password, $site_database;
        $returnValue = 0;

        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
                if($my_result = mysql_query("SELECT * FROM itemtemplate WHERE entry = $item_id;", $my_conn))
                {
                    if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                        $returnValue = $my_row;
                    mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return $returnValue;
    }

    function sumItemEnchantments(&$input, $data)
    {
        global $site_host, $site_username, $site_password, $site_database, $stats_rating_div;

        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
            {
                $data_array = explode(' ', $data);
                for($i=0; $i<count($data_array); $i+=3)
                {
                    if($data_array[$i] != 0)
                    {
                        $query = "SELECT * FROM itemenchantment WHERE ID = " . $data_array[$i] . ';';
                        if($my_result = mysql_query($query, $my_conn))
                        {
                            if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                                for($j=0; $j<3; ++$j)
                                    if($my_row["type$j"]==5 && in_array($my_row["spellid$j"], $stats_rating_div))
                                    {
                                        $key = array_search($my_row["spellid$j"], $stats_rating_div);
                                        $input["$key"] += $my_row["amount$j"];
                                    }
                            mysql_free_result($my_result);
                        }
                    }
                }
            }
            mysql_close($my_conn);
        }
    }

    function getRating($level, $rating_name)
    {
        global $site_host, $site_username, $site_password, $site_database;
        $returnValue = 1;

        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
                if($my_result = mysql_query("SELECT $rating_name FROM rating WHERE level = $level;", $my_conn))
                {
                    if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                        $returnValue = $my_row["$rating_name"];
                    mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return $returnValue;
    }

    //The function gets all the stats of the PG, searches first in the table character_stats, if it doesn't find anything searches in the table armory_character_stats.
    function fill_stats(&$input, $intput_conn)
    {
        $guid = $input["guid"];
        $find_stats = false;

        $query = "SELECT maxhealth, maxpower1 AS mana, maxpower6 AS rune, maxpower7 AS runicPower, strength, agility, stamina, intellect, spirit,
                    armor, blockPct, dodgePct, parryPct, critPct, rangedCritPct, spellCritPct, attackPower, rangedAttackPower, spellPower,
                    resilience FROM character_stats WHERE guid = $guid;";
        if($result = mysql_query($query, $intput_conn)) //Internal armory (if enabled).
        {
            if($row = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $find_stats = true;
                $input = array_merge($input, $row);
            }
            mysql_free_result($result);
        }

        if(!$find_stats)
        {
            if($result = mysql_query("SELECT data FROM armory_character_stats WHERE guid = $guid;", $intput_conn))
            {
                if($row = mysql_fetch_array($result, MYSQL_ASSOC)) //Shadez armory (only if the internal armory is disabled).
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
                    for($i=0x0495; $i<0x049A; ++$i) //The min-value is the spell power.
                        $input["spellPower"]     = min($input["spellPower"], $input_array[$i]);

                    $input["spellCritPct"]       = UInt32ToFloat($input_array[0x0409]);                     //OBJECT_END + UNIT_END + PLAYER_SPELL_CRIT_PERCENTAGE1.
                    for($i=0x040A; $i<0x040F; ++$i) //The min-value is the spell crit.
                        $input["spellCritPct"]   = min($input["spellCritPct"], UInt32ToFloat($input_array[$i]));
                }
                mysql_free_result($result);
            }
        }

        if($input["class"] != 6) //Only Death Knights have got rune and runic power.
        {
            $input["rune"]       = "N/D";
            $input["runicPower"] = "N/D";
        }
        if($input["class"]==1 || $input["class"]==2 || $input["class"]==6) //Warrior, rogue e Death Knight have not got mana.
            $input["mana"] = "N/D";

        //Personal and team rating 2v2, 3v3 and 5v5.
        $team_types = array(2, 3, 5);
        foreach($team_types as $i => $value)
        {
            $input["teamName$value"]        = "N/D";
            $input["teamRating$value"]      = "N/D";
            $input["personalRating$value"]  = "N/D";

            $query = "SELECT arena_team.name, arena_team.rating, arena_team_member.personalRating FROM arena_team,
                        arena_team_member WHERE arena_team.arenaTeamId = arena_team_member.arenaTeamId AND
                        arena_team_member.guid = $guid AND arena_team.type = $value;";
            if($result = mysql_query($query, $intput_conn))
            {
                if($row = mysql_fetch_array($result, MYSQL_ASSOC))
                {
                    $input["teamName$value"]        = $row["name"];
                    $input["teamRating$value"]      = $row["rating"];
                    $input["personalRating$value"]  = $row["personalRating"];
                }
                mysql_free_result($result);
            }
        }

        //Haste and Hit.
        $hit_hast_names = array("hit", "meleeHit", "rangedHit", "spellHit", "haste", "meleeHaste", "rangedHaste", "spellHaste");
        foreach($hit_hast_names as $i => $value)
        {
            $input["$value"] = 0;
        }
        $query = "SELECT item_instance.itemEntry, item_instance.enchantments FROM character_inventory, item_instance
                    WHERE character_inventory.item = item_instance.guid AND character_inventory.guid = $guid AND character_inventory.slot<18;";
        if($result = mysql_query($query, $intput_conn))
        {
            global $stats_rating_div;
            while($row = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                sumItemEnchantments($input, $row["enchantments"]);
                if($item_template = getItemTemplate($row["itemEntry"]))
                    for($i=1; $i<=$item_template["StatsCount"]; ++$i)
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
            mysql_free_result($result);
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

    //The function returns 0 if the achievement is not valid (eg achievement of first kill, or Feast of Strength) or achievement's points if it's valid.
    function isValidAchievement($achievement_id)
    {
        global $site_host, $site_username, $site_password, $site_database;
        $returnValue = 0;

        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
                if($my_result = mysql_query("SELECT points FROM achievement WHERE ID = $achievement_id;", $my_conn))
                {
                    if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                        $returnValue = $my_row["points"];
                    mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return $returnValue;
    }

    //The function returns information about a given talents.
    function getTalentInfo($spellId)
    {
        global $site_host, $site_username, $site_password, $site_database;
        $returnValue = 0;

        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
                if($my_result = mysql_query("SELECT rankId, tabPage FROM talent WHERE spellTalent = $spellId;", $my_conn))
                {
                    if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                        $returnValue = $my_row;
                    mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return $returnValue;
    }
?>
<?php
    //Global variables.
    $do_next_step    = true;      //You can go on only if $do_next_step = true.
    $pg_name         = '';        //Character's name.
    $name_string     = '';        //Character's name with his title.
    $string_info     = '';        //Additional informations (race, level, class).
    $is_gif          = false;     //The variable set to false indicates that the image is png, otherwise is gif.
    $external_image  = false;     //The variable indicates if the chosen avatar is internal or external to the site.
    $avatar_img      = '';        //Link to the avatar.
    $string_guild    = '';        //Rank, guild, realm, server.
    $im              = FALSE;     //Link to the image.
    $to_make_image   = true;      //Indicates if the image should be built.
    $pg_GUID         = 0;         //Unique ID of the character.
    $spec_name       = '';        //Spec's name.
    $effect          = '';        //Background effect.
    $img_name        = '';        //Background image.
?>
<?php
    //If the same image already exists i don't rebuild it.
    if($connection = mysql_connect($site_host, $site_username, $site_password, true))
    {
        if(mysql_select_db($site_database, $connection))
        {
            $query_string = getOrderQueryString($_SERVER["QUERY_STRING"]); //Check if exists an image with the same queryString.
            if($result = mysql_query("SELECT * FROM savedimages WHERE queryString = '$query_string';", $connection))
            {
                if($row = mysql_fetch_array($result, MYSQL_ASSOC))  //Force-refresh the image every 24 hours.
                    if(file_exists("saved/" . $row["imageName"]) && (time()-$row["creation"])<(24*60*60))
                    {
                        $to_make_image = false;
                        mysql_query("UPDATE savedimages SET lastEdit = UNIX_TIMESTAMP() WHERE queryString = '$query_string';", $connection);
                        header("location: saved/" . $row["imageName"]); //If the image exists do a redirect to it.
                    }
                mysql_free_result($result);
            }
        }
        mysql_close($connection);
    }
?>
<?php
    //Recovery data for the creation image.
    if($to_make_image)
    {
        if(GDVersion()==0) //On the system GD doesn't exist.
            $do_next_step = false;

        if($do_next_step)
        {
            //The user has selected a different background than the default (red graduation).
            if(isset($_GET["background"]) && $_GET["background"]!='')
            {
                $background = strtolower($_GET["background"]);
                //The background is an image, (it starts with bg_), i construct a link to the image.
                if(!strncmp($background, "bg_", 3))
                {
                    //All wallpapers are used for signature are png, they start with bg_ and are contained in the directory "images/bg".
                    if(in_array($background, $backgrounds))
                    {
                        $to_img = true;
                        $img_name = $background;
                    }
                }
                else //The background is a color, search the 3 graduations in hexadecimal code.
                {
                    $bg_vet = GetRGBFromHex($background);

                    $to_img          = false;
                    $start_bg_red    = $bg_vet[0];
                    $start_bg_green  = $bg_vet[1];
                    $start_bg_blue   = $bg_vet[2];
                }
            }

            //Background effect.
            if(isset($_GET["effects"]) && $_GET["effects"]!='')
            {
                $effect = strtolower($_GET["effects"]);
                if(!in_array($effect, $effects)) $effect = '';
            }

            //The user has selected a text color different from the default, i extract it.
            if(isset($_GET["text_color"]) && $_GET["text_color"]!='')
                $text_vet_color = GetRGBFromHex($_GET["text_color"]);

            //The user selects a font for the text different from the default, i extract it.
            if(isset($_GET["text_font"]) && $_GET["text_font"]!='')
            {
                $name_font = strtolower($_GET["text_font"]);
                if(isset($fonts["$name_font"]["name"]))
                {
                    $font                  = $fonts["$name_font"]["name"];
                    $dim_pg_name           = $fonts["$name_font"]["pg_name"];
                    $dim_stats             = $fonts["$name_font"]["stats"];
                    $dim_addictional_info  = $fonts["$name_font"]["addictional_info"];
                }
            }
            //Complete the path of the font file.
            $font = "fonts/$font";

            //Check of inserted fields and check if the selected realm exists.
            if(isset($_GET["server"]) && $_GET["server"]!='' && isset($_GET["pg_name"]) && $_GET["pg_name"]!='')
            {
                $server_id = strtolower($_GET["server"]);
                if(isset($realm_name["$server_id"]))
                {
                    //Connect to character's database and recovery data.
                    if($connection = mysql_connect($host["$server_id"], $username["$server_id"], $password["$server_id"], true))
                    {
                        if(mysql_select_db($database["$server_id"], $connection))
                        {
                            //First uppercase, rest lowercase. Eg: TEST=>Test, test=>Test, tEsT=>Test.
                            $pg_name = mysql_real_escape_string(ucfirst(strtolower($_GET["pg_name"]))); //Avoid SQL-Injections.

                            //Name, Race, Class, Level, Title, Spec, Arena Points, Honor Points, PvP Kills.
                            $query = "SELECT guid, name, race, class, gender, level, chosenTitle, activespec, arenaPoints, totalHonorPoints,
                                        totalKills FROM characters WHERE name = '$pg_name';";
                            if($result = mysql_query($query, $connection))
                            {
                                if($row = mysql_fetch_array($result, MYSQL_ASSOC))
                                {
                                    $pg_GUID = $row["guid"];
                                    $pg_name = $row["name"];
                                    $spec_id = $row["activespec"];
                                    fill_stats($row, $connection); //Fill stats.

                                    //If the PG has a title (searching it in the site database) i insert it in the signature.
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

                                    //Talents, do it for find the spec name.
                                    $talents = array(0, 0, 0);
                                    if($talents_result = mysql_query("SELECT spell FROM character_talent WHERE guid = $pg_GUID AND spec = $spec_id;", $connection))
                                    {
                                        while($talents_row = mysql_fetch_array($talents_result, MYSQL_ASSOC))
                                            if($vet = getTalentInfo($talents_row["spell"]))
                                                $talents[$vet["tabPage"]] += $vet["rankId"];
                                        mysql_free_result($talents_result);
                                    }
                                    $row["talents"] = $talents[0] . '/' . $talents[1] . '/' . $talents[2]; //Talents in the form (x/x/x).

                                    //Spec name.
                                    if($max_talent = max($talents[0], $talents[1], $talents[2]))
                                        $spec_name .= ' ' . $tab_names[$row["class"]][array_search($max_talent, $talents)];

                                    //Level - Class - Race.
                                    $string_info = "Level " . $row["level"] . ' ' . $races[$row["race"]] . ' ' . $classes[$row["class"]]["name"] . $spec_name;

                                    //If is given the url of a valid PNG image i insert it, otherwise insert the default image.
                                    if(isset($_GET["url_image"]) && $_GET["url_image"]!='')
                                    {
                                        $avatar_img  = utf8_decode($_GET["url_image"]);
                                        $file_name   = "temp_images/" . sha1($avatar_img) . '.' . pathinfo($avatar_img, PATHINFO_EXTENSION); //Search the name of the image.

                                        if(!file_exists($file_name)) //If the image does not exists i copy it to the cache.
                                            if($contents = file_get_contents($avatar_img))
                                                file_put_contents($file_name, $contents);

                                        $avatar_img = $file_name; //Return the new link to the image.
                                        if($check_im = imagecreatefromstring(file_get_contents($avatar_img)))
                                        {
                                            $external_image = true;
                                            imagedestroy($check_im);
                                        }else unlink($avatar_img); //If the file isn't an image i delete it.
                                    }

                                    if(!$external_image)
                                    {
                                        //The image shows the race and the class of the character.
                                        if(isset($_GET["type_image"]) && strtolower($_GET["type_image"])=="race_class")
                                        {
                                            $is_gif = true;
                                            //The images are in the form "gender-race-class.gif".
                                            $avatar_img = $row["gender"] . '-' . $row["race"] . '-' . $row["class"] . ".gif";
                                            if($row["class"]==6 || $row["level"]>=80) //If level is 80 or race is Death Knight because Death Knight have got only level 80 avatars.
                                                $avatar_img = "Level_80_Forum_Avatars/$avatar_img";
                                            else if($row["level"]>=70) //70 <= level <= 79, level 70 avatars.
                                                $avatar_img = "Level_70_Forum_Avatars/$avatar_img";
                                            else if($row["level"]>=60) //60 <= level <= 69, level 60 avatars.
                                                $avatar_img = "Level_60_Forum_Avatars/$avatar_img";
                                            else $avatar_img = "Level_1_Forum_Avatars/$avatar_img"; //1 <= level <= 59, level 1 avatars.
                                        }else $avatar_img = $classes[$row["class"]]["img"] . ".png"; //Only character's class, refear to config file.
                                        $avatar_img = "images/classes/$avatar_img"; //Complete the avatar path.
                                    }

                                    //Guild and Rank.
                                    $guild_query = "SELECT guild.name, guild_rank.rname FROM guild_member, guild, guild_rank WHERE guild_member.guildid = guild.guildid
                                                    AND guild_member.rank = guild_rank.rid AND guild_rank.guildid = guild.guildid AND guild_member.guid = $pg_GUID;";
                                    if($guild_result = mysql_query($guild_query, $connection))
                                    {
                                        if($guild_row = mysql_fetch_array($guild_result, MYSQL_ASSOC))
                                            $string_guild = '"' . $guild_row["rname"] . "\" of <" . $guild_row["name"] . "> "; //"Rank" of <Nome Guild>
                                        mysql_free_result($guild_result);
                                    }
                                    if($server_name!='' || $server_id!='') //[Server_Name Realm_Name]
                                    {
                                        $string_guild .= "[$server_name";
                                        if($server_name!='' && $server_id!='') $string_guild .= ' ';
                                        $string_guild .= $realm_name["$server_id"] . ']';
                                    }

                                    //Stats (in an array).
                                    $index = 0;
                                    $show_stats = array();
                                    $temp_string = '';
                                    for($i=1; $i<6; ++$i) //Up to 5 stats of your choice.
                                    {
                                        if(isset($_GET["custom_stat$i"]) && $_GET["custom_stat$i"]!='')
                                            $temp_string = substr(utf8_decode($_GET["custom_stat$i"]), 0, 20);
                                        else if(isset($_GET["stat$i"]) && $_GET["stat$i"]!='') //Check if there is a template of that stat.
                                        {
                                            $get_stat = strtolower($_GET["stat$i"]);

                                            //Achievements, i perform this operation only if required to save resources.
                                            if(($get_stat=="achievements" && !isset($row["achievements"])) || ($get_stat=="achievementpoints" && !isset($row["achievementPoints"])))
                                            {
                                                $ach_count = 0;
                                                $ach_points = 0;
                                                //Select only the achievements that give points, the rest are "Feats of Strength" or first kill.
                                                if($achievements_result = mysql_query("SELECT achievement FROM character_achievement WHERE guid = $pg_GUID;", $connection))
                                                {
                                                    while($achievements_row = mysql_fetch_array($achievements_result, MYSQL_ASSOC))
                                                        if($points = isValidAchievement($achievements_row["achievement"]))
                                                        {
                                                            $ach_count++; //Increase the count of the obtained achievements.
                                                            $ach_points += $points; //Increase the points of the obtained achievements.
                                                        }
                                                    mysql_free_result($achievements_result);
                                                }
                                                //Insert data obtained in the vector of stats.
                                                $row["achievements"]       = $ach_count;
                                                $row["achievementPoints"]  = $ach_points;
                                            }

                                            if(isset($stats["$get_stat"]["name"]))
                                            {
                                                $field_name = $stats["$get_stat"]["field_name"];

                                                if(is_numeric($row["$field_name"])) //I make the round-off only if the field is a number.
                                                    $field_value = round($row["$field_name"], 2);
                                                else $field_value = $row["$field_name"];

                                                //Replace the values to the template strings.
                                                $temp_string = str_replace("%s", $field_value, $stats["$get_stat"]["text"]);
                                            }
                                        }

                                        //Check to make sure to avoid double stats.
                                        if(!in_array($temp_string, $show_stats) && $temp_string!='')
                                            $show_stats[$index++] = $temp_string;
                                    }
                                }else $do_next_step = false;
                                mysql_free_result($result);
                            }else $do_next_step = false;
                        }else $do_next_step = false;
                        mysql_close($connection);
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
            header("Content-disposition: inline; filename=signature.png");
            header("Content-type: image/png");

            //It depends on the version of the GD image is created in a different way.
            $im = imageCreateFromVersion($x, $y);

            $gold        = imagecolorallocate($im, 255, 215, 0); //Rectangle color.
            $shadow      = imagecolorallocate($im, 0, 0, 0); //Shadow color.
            $text_color  = imagecolorallocate($im, $text_vet_color[0], $text_vet_color[1], $text_vet_color[2]); //Text color.

            //CENTRAL COLOUR - START.
                if($to_img == false) //Colour in shade.
                {
                    //Proportions for the attenuation of the color to black (r=0, g=0, b=0).
                    $prop_bg_red    = ($start_bg_red/$y)/($y/$gradient_proportion_y);
                    $prop_bg_green  = ($start_bg_green/$y)/($y/$gradient_proportion_y);
                    $prop_bg_blue   = ($start_bg_blue/$y)/($y/$gradient_proportion_y);

                    $bg_red    = $start_bg_red;
                    $bg_green  = $start_bg_green;
                    $bg_blue   = $start_bg_blue;
                    for($i=0; $i<$y; ++$i) //Those with a for loop i colour the image into strips of 1 px.
                    {
                        $bg_red    -= ($i * $prop_bg_red);
                        $bg_green  -= ($i * $prop_bg_green);
                        $bg_blue   -= ($i * $prop_bg_blue);

                        $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                        imagefilledrectangle($im, 0, $i, $x, $i+1, $col);
                        imagecolordeallocate($im, $col);
                    }
                }
                else //Background image.
                {
                    $src_bg = imagecreatefrompng("images/bg/$img_name.png"); //Open th background image.
                    list($width_bg, $height_bg) = getimagesize("images/bg/$img_name.png");
                    imagecopyresampled($im, $src_bg, 0, 0, 0, 0, $x, $y, $width_bg, $height_bg); //Resize to destination size.
                    imagedestroy($src_bg);
                }
            //CENTRAL COLOUR - END.

            //BACKGROUND EFFECT - START.
                if($effect != '')
                {
                    list($width_effect, $height_effect) = getimagesize("images/effects/$effect.png");
                    $src_effect = imagecreatefrompng("images/effects/$effect.png");
                    for($i=0; $i<ceil($y/$height_effect); ++$i)
                        for($j=0; $j<ceil($x/$width_effect); ++$j)
                            imagecopyresampled($im, $src_effect, $j*$width_effect, $i*$height_effect, 0, 0, $width_effect, $height_effect, $width_effect, $height_effect);
                    imagedestroy($src_effect);
                }
            //BACKGROUND EFFECT - END.

            //FILTERS - BEGIN.
                if(isset($_GET["filter"]) && $_GET["filter"]!='')
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
            //FILTERS - END.

            //BORDER COLOUR - START.
                imagerectangle($im, 1, 1, $x-2, $y-2, $gold); //Draw a gold rectangle to 1 px from the border.
            //BORDER COLOUR - END.

            //HALF IMAGE LINE - START.
                $pos_line = $y/2.5; //Calculating the position of the line in proportion to the height of the image.
                imageline($im, $y+6, $pos_line+1, $x-$y-73, $pos_line+1, $shadow); //Draw first the shadow moved 1px down and right.
                imageline($im, $y+5, $pos_line, $x-$y-74, $pos_line, $gold); //Draw the line.
            //HALF IMAGE LINE - END.

            //CHARACTER NAME - START.
                imagettftext($im, $dim_pg_name, 0, $y+8, $pos_line-4, $shadow, $font, $name_string); //Shadow of character name.
                imagettftext($im, $dim_pg_name, 0, $y+7, $pos_line-5, $text_color, $font, $name_string); //Character name.
            //CHARACTER NAME - END.

            //STATS - START.
                $prop_text = $y/5 - 1; //I calculate the distance between the stats in proportion to the height of the image.
                for($i=0; $i<count($show_stats); ++$i) //Print selected stats.
                {
                    $box = imagettfbbox($dim_stats, 0, $font, $show_stats[$i]);
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-5, 13+$prop_text*$i, $shadow, $font, $show_stats[$i]); //Stat's shadow.
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-6, 12+$prop_text*$i, $text_color, $font, $show_stats[$i]); //Stat.
                }
            //STATS - END.

            //CLASS IMG - START.
                $src_avatar = imagecreatefromstring(file_get_contents($avatar_img));
                list($width_avatar, $height_avatar) = getimagesize($avatar_img);
                if($external_image) //If it is an external image reduce its size of 10px.
                    imagecopyresampled($im, $src_avatar, 5, 5, 0, 0, $y-10, $y-10, $width_avatar, $height_avatar);
                else //The image contains only the character class, resize and scale it.
                    imagecopyresampled($im, $src_avatar, 5, ($is_gif ? 5 : 0), 0, 0, $y-($is_gif ? 10 : 0), $y-($is_gif ? 10 : 0), $width_avatar, $height_avatar);
                imagedestroy($src_avatar);
            //CLASS IMG - END.

            //LEVEL-CLASS-RACE - START.
                imagettftext($im, $dim_addictional_info, 0, $y+8, $pos_line+18, $shadow, $font, $string_info); //Addictional info shadow.
                imagettftext($im, $dim_addictional_info, 0, $y+7, $pos_line+17, $text_color, $font, $string_info); //Addictional info.
            //LEVEL-CLASS-RACE - END.

            //GUILD-SERVER - START.
                imagettftext($im, $dim_addictional_info, 0, $y+8, $pos_line+36, $shadow, $font, $string_guild); //Guild, server shadow.
                imagettftext($im, $dim_addictional_info, 0, $y+7, $pos_line+35, $text_color, $font, $string_guild); //Guild, server.
            //GUILD-SERVER - END.

            //IMAGE RESIZING - START (ONLY IF ENABLED).
                if($image_resize_enabled && ((isset($_GET['x']) && $_GET['x']!='' && is_numeric($_GET['x'])) || (isset($_GET['y']) && $_GET['y']!='' && is_numeric($_GET['y']))))
                {
                    //x and y proportion calculating.
                    if(isset($_GET['x']) && $_GET['x']!='' && is_numeric($_GET['x']))
                        $prop_x = $_GET['x'] / $x;
                    else $prop_x = 1;
                    if(isset($_GET['y']) && $_GET['y']!='' && is_numeric($_GET['y']))
                        $prop_y = $_GET['y'] / $y;
                    else $prop_y = 1;

                    //How it work:
                    // - If the proportions are both greater than 1 choose the largest,
                    // - If the proportions are both less than 1, choose the smallest,
                    // - If the proportions are one less than 1 and and greater than 1 choose the smallest,
                    // - If one of the proportions is equal to 1 i choose the one different from 1.
                    if($prop_x>1 && $prop_y>1)
                        $prop = $prop_x>$prop_y ? $prop_x : $prop_y;
                    else if($prop_x<1 && $prop_y<1)
                        $prop = $prop_x<$prop_y ? $prop_x : $prop_y;
                    else if(($prop_x!=1 && $prop_y==1) || ($prop_x<1 && $prop_y>1))
                        $prop = $prop_x;
                    else if(($prop_x==1 && $prop_y!=1) || ($prop_x>1 && $prop_y<1))
                        $prop = $prop_y;
                    else $prop = 1;

                    //If the proportion is changed, i must resize the image.
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
            //IMAGE RESIZING - END.

            //IMAGE SAVING - START.
                if($connection = mysql_connect($site_host, $site_username, $site_password, true))
                {
                    if(mysql_select_db($site_database, $connection))
                    {
                        $realm_name = mysql_real_escape_string(strtoupper($_GET["server"])); //Avoid SQL-Injections.
                        $img_save_name = $realm_name . "_$pg_name.png"; //SERVER_PgName.png.
                        imagepng($im, "saved/$img_save_name"); //Save the image in the "saved" directory.

                        //Save a identification record on DB.
                        mysql_query("REPLACE INTO savedimages VALUES ($pg_GUID, '$realm_name', '" . getOrderQueryString($_SERVER["QUERY_STRING"]) . "', '$img_save_name', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());", $connection);
                    }
                    mysql_close($connection);
                }
            //IMAGE SAVING - END.

            //COLOR DEALLOCATION - START.
                imagecolordeallocate($im, $gold);
                imagecolordeallocate($im, $shadow);
                imagecolordeallocate($im, $text_color);
            //COLOR DEALLOCATION - END.

            imagepng($im);
            imagedestroy($im);
        }else header("location: images/$incorrect_data");
    }
?>