<?php
    define("TOOL_INCLUDED", true);
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
        $arr = explode('&', strtolower($input)); //I divide the queryString into an array containing key = value.
        sort($arr); //Sort the array in alphabetical order (in this case are ordered only the keys of the queryString).
        return mysql_real_escape_string(implode('&', $arr));
    }

    //Function that performs a conversion from UInt32 to float.
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

    //The function gets all the stats of the PG, searches first in the table character_stats, if it doesn't find anything searches in the table armory_character_stats.
    function fill_stats(&$input, $intput_conn)
    {
        $guid = $input["guid"];
        $find_stats = false;

        $query = "SELECT maxhealth, maxpower1 AS mana, maxpower6 AS rune, maxpower7 AS runicPower, strength, agility, stamina, intellect, spirit,
                    armor, blockPct, dodgePct, parryPct, critPct, rangedCritPct, spellCritPct, attackPower, rangedAttackPower, spellPower,
                    resilience FROM character_stats WHERE guid = $guid;";
        if($row = $intput_conn->query($query, true)) //Internal armory (if enabled).
        {
            $find_stats = true;
            $input = array_merge($input, $row);
        }

        if(!$find_stats)
            if($row = $intput_conn->query("SELECT data FROM armory_character_stats WHERE guid = $guid;", true))
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
                for($i = 0x0495; $i < 0x049A; ++$i) //The min-value is the spell power.
                    $input["spellPower"]     = min($input["spellPower"], $input_array[$i]);

                $input["spellCritPct"]       = UInt32ToFloat($input_array[0x0409]);                     //OBJECT_END + UNIT_END + PLAYER_SPELL_CRIT_PERCENTAGE1.
                for($i = 0x040A; $i < 0x040F; ++$i) //The min-value is the spell crit.
                    $input["spellCritPct"]   = min($input["spellCritPct"], UInt32ToFloat($input_array[$i]));
            }

        if($input["class"] != 6) //Only Death Knights have got rune and runic power.
        {
            $input["rune"]       = "N/D";
            $input["runicPower"] = "N/D";
        }
        if($input["class"] == 1 || $input["class"] == 4 || $input["class"] == 6) //Warrior, rogue e Death Knight have not got mana.
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
            if($row = $intput_conn->query($query, true))
            {
                $input["teamName$value"]        = $row["name"];
                $input["teamRating$value"]      = $row["rating"];
                $input["personalRating$value"]  = $row["personalRating"];
            }
        }

        //Haste and Hit.
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

    //The function returns 0 if the achievement is not valid (eg achievement of first kill, or Feast of Strength) or achievement's points if it's valid.
    function isValidAchievement($achievement_id)
    {
        global $site_connection;
        if($row = $site_connection->query("SELECT points FROM achievement WHERE ID = $achievement_id;", true))
            return $row["points"];
        return false;
    }

    //The function returns information about a given talents.
    function getTalentInfo($spellId)
    {
        global $site_connection;
        return $site_connection->query("SELECT rankId, tabPage FROM talent WHERE spellTalent = $spellId;", true);
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
    $server_id       = '';        //Id of chosen server.
?>
<?php
    //If the same image already exists i don't rebuild it.
    $query_string = getOrderQueryString($_SERVER["QUERY_STRING"]); //Check if exists an image with the same queryString.
    if($row = $site_connection->query("SELECT * FROM savedimages WHERE queryString = '$query_string';", true)) //Force-refresh the image every 24 hours.
        if(file_exists("saved/" . $row["imageName"]) && (time()-$row["creation"])<(24*60*60))
        {
            $to_make_image = false;
            $site_connection->query("UPDATE savedimages SET lastEdit = UNIX_TIMESTAMP() WHERE queryString = '$query_string';", true);

            //If the image exists do a redirect to it.
            header("Content-disposition: inline; filename=signature.png");
            header("Content-type: image/png");
            $im = imagecreatefrompng("saved/" . $row["imageName"]);
            imagepng($im);
            imagedestroy($im);
        }
?>
<?php
    //Check of inserted fields and check if the selected realm exists.
    if(isset($_GET["server"]) && $_GET["server"] != '' && isset($_GET["pg_name"]) && $_GET["pg_name"] != '')
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
    //Recovery data for the creation image.
    if($to_make_image && $do_next_step)
    {
        if(GDVersion() == 0) //On the system GD doesn't exist.
            $do_next_step = false;

        if($do_next_step)
        {
            //The user has selected a different background than the default (red graduation).
            if(isset($_GET["background"]) && $_GET["background"] != '')
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

            //The user has selected a end bg color.
            if(isset($_GET["end_background"]) && $_GET["end_background"] != '')
            {
                $bg_vet = GetRGBFromHex(strtolower($_GET["end_background"]));
                $end_bg_red      = $bg_vet[0];
                $end_bg_green    = $bg_vet[1];
                $end_bg_blue     = $bg_vet[2];
            }

            //Background effect.
            if(isset($_GET["effects"]) && $_GET["effects"] != '')
            {
                $effect = strtolower($_GET["effects"]);
                if(!in_array($effect, $effects)) $effect = '';
            }

            //The user has selected a text color different from the default, i extract it.
            if(isset($_GET["text_color"]) && $_GET["text_color"] != '')
                $text_vet_color = GetRGBFromHex($_GET["text_color"]);

            //The user selects a font for the text different from the default, i extract it.
            if(isset($_GET["text_font"]) && $_GET["text_font"] != '')
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

            //First uppercase, rest lowercase. Eg: TEST=>Test, test=>Test, tEsT=>Test.
            $pg_name = mysql_real_escape_string(ucfirst(strtolower($_GET["pg_name"]))); //Avoid SQL-Injections.

            //Name, Race, Class, Level, Title, Spec, Arena Points, Honor Points, PvP Kills.
            $query = "SELECT guid, name, race, class, gender, level, chosenTitle, activespec, arenaPoints, totalHonorPoints,
                        totalKills FROM characters WHERE name = '$pg_name';";
            if($row = $server_conn->query($query, true))
            {
                $pg_GUID = $row["guid"];
                $pg_name = $row["name"];
                $spec_id = $row["activespec"];
                fill_stats($row, $server_conn); //Fill stats.

                //If the PG has a title (searching it in the site database) i insert it in the signature.
                $name_string = $row["name"];
                if($row["chosenTitle"] != 0)
                    if($title_row = $site_connection->query("SELECT nameString FROM chartitles WHERE titleId = " . $row["chosenTitle"] . ';', true))
                        $name_string = str_replace("%s", $row["name"], $title_row["nameString"]);

                //Talents, do it for find the spec name.
                $talents = array(0, 0, 0);
                $id_query_talents = $server_conn->query("SELECT spell FROM character_talent WHERE guid = $pg_GUID AND spec = $spec_id;");
                while($talents_row = $server_conn->getNextResult($id_query_talents))
                    if($vet = getTalentInfo($talents_row["spell"]))
                        $talents[$vet["tabPage"]] += $vet["rankId"];
                $row["talents"] = $talents[0] . '/' . $talents[1] . '/' . $talents[2]; //Talents in the form (x/x/x).

                //Spec name.
                if($max_talent = max($talents[0], $talents[1], $talents[2]))
                    $spec_name .= ' ' . $tab_names[$row["class"]][array_search($max_talent, $talents)];

                //Level - Class - Race.
                $string_info = "Level " . $row["level"] . ' ' . $races[$row["race"]] . ' ' . $classes[$row["class"]]["name"] . $spec_name;

                //If is given the url of a valid PNG image i insert it, otherwise insert the default image.
                if(isset($_GET["url_image"]) && $_GET["url_image"] != '')
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
                    if(isset($_GET["type_image"]) && strtolower($_GET["type_image"]) == "race_class")
                    {
                        $is_gif = true;
                        //The images are in the form "gender-race-class.gif".
                        $avatar_img = $row["gender"] . '-' . $row["race"] . '-' . $row["class"] . ".gif";
                        if($row["class"] == 6 || $row["level"] >= 80) //If level is 80 or race is Death Knight because Death Knight have got only level 80 avatars.
                            $avatar_img = "Level_80_Forum_Avatars/$avatar_img";
                        else if($row["level"] >= 70) //70 <= level <= 79, level 70 avatars.
                            $avatar_img = "Level_70_Forum_Avatars/$avatar_img";
                        else if($row["level"] >= 60) //60 <= level <= 69, level 60 avatars.
                            $avatar_img = "Level_60_Forum_Avatars/$avatar_img";
                        else $avatar_img = "Level_1_Forum_Avatars/$avatar_img"; //1 <= level <= 59, level 1 avatars.
                    }else $avatar_img = $classes[$row["class"]]["img"] . ".png"; //Only character's class, refear to config file.
                    $avatar_img = "images/classes/$avatar_img"; //Complete the avatar path.
                }

                //Guild and Rank.
                $guild_query = "SELECT guild.name, guild_rank.rname FROM guild_member, guild, guild_rank WHERE guild_member.guildid = guild.guildid
                                AND guild_member.rank = guild_rank.rid AND guild_rank.guildid = guild.guildid AND guild_member.guid = $pg_GUID;";
                if($guild_row = $server_conn->query($guild_query, true))
                    $string_guild = '"' . $guild_row["rname"] . "\" of <" . $guild_row["name"] . "> "; //"Rank" of <Nome Guild>
                if($server_name != '' || $server_id != '') //[Server_Name Realm_Name]
                {
                    $string_guild .= "[$server_name";
                    if($server_name!='' && $server_id!='') $string_guild .= ' ';
                        $string_guild .= $realm_name["$server_id"] . ']';
                }

                //Stats (in an array).
                $index = 0;
                $show_stats = array();
                $temp_string = '';
                for($i = 1; $i < 6; ++$i) //Up to 5 stats of your choice.
                {
                    if(isset($_GET["custom_stat$i"]) && $_GET["custom_stat$i"] != '')
                        $temp_string = substr(utf8_decode($_GET["custom_stat$i"]), 0, 20);
                    else if(isset($_GET["stat$i"]) && $_GET["stat$i"] != '') //Check if there is a template of that stat.
                    {
                        $get_stat = strtolower($_GET["stat$i"]);

                        //Achievements, i perform this operation only if required to save resources.
                        if(($get_stat == "achievements" && !isset($row["achievements"])) || ($get_stat == "achievementpoints" && !isset($row["achievementPoints"])))
                        {
                            $ach_count = 0;
                            $ach_points = 0;
                            //Select only the achievements that give points, the rest are "Feats of Strength" or first kill.
                            $id_query_achievements = $server_conn->query("SELECT achievement FROM character_achievement WHERE guid = $pg_GUID;");
                            while($achievements_row = $server_conn->getNextResult($id_query_achievements))
                                if($points = isValidAchievement($achievements_row["achievement"]))
                                {
                                    $ach_count++; //Increase the count of the obtained achievements.
                                    $ach_points += $points; //Increase the points of the obtained achievements.
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
                    $background_method = strtolower($_GET["background_method"]);
                    switch($background_method)
                    {
                    case "circle":
                        $bg = imagecolorallocate($im, $start_bg_red, $start_bg_green, $start_bg_blue);
                        imagefill($im, 0, 0, $bg);
                        imagecolordeallocate($im, $bg);

                        $size_to_div_color = ceil(sqrt(pow($x, 2) + pow($y, 2)) / 4);
                        $center_x = floor($x / 2);
                        $center_y = floor($y / 2);

                        //Proportions for the attenuation of the color.
                        $prop_bg_red    = ($start_bg_red - $end_bg_red) / $size_to_div_color;
                        $prop_bg_green  = ($start_bg_green - $end_bg_green) / $size_to_div_color;
                        $prop_bg_blue   = ($start_bg_blue - $end_bg_blue) / $size_to_div_color;

                        for($i = 0; $i < $size_to_div_color; ++$i) //Those with a for loop i colour the image into circles of 1 px.
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

                        //Proportions for the attenuation of the color.
                        $prop_bg_red    = ($start_bg_red - $end_bg_red) / $size_to_div_color;
                        $prop_bg_green  = ($start_bg_green - $end_bg_green) / $size_to_div_color;
                        $prop_bg_blue   = ($start_bg_blue - $end_bg_blue) / $size_to_div_color;

                        for($i = 0; $i < $size_to_div_color; ++$i) //Those with a for loop i colour the image into rectangles of 1 px.
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
                        if($background_method == "vertical")
                            $size_to_div_color = $x;

                        //Proportions for the attenuation of the color.
                        $prop_bg_red    = ($start_bg_red - $end_bg_red) / $size_to_div_color;
                        $prop_bg_green  = ($start_bg_green - $end_bg_green) / $size_to_div_color;
                        $prop_bg_blue   = ($start_bg_blue - $end_bg_blue) / $size_to_div_color;

                        for($i = 0; $i < $size_to_div_color; ++$i) //Those with a for loop i colour the image into strips of 1 px.
                        {
                            $bg_red    = $start_bg_red - ($i * $prop_bg_red);
                            $bg_green  = $start_bg_green - ($i * $prop_bg_green);
                            $bg_blue   = $start_bg_blue - ($i * $prop_bg_blue);

                            $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                            if($background_method == "vertical")
                                imageline($im, $i, 0, $i, $y, $col);
                            else imageline($im, 0, $i, $x, $i, $col);
                            imagecolordeallocate($im, $col);
                        }
                        break;
                    }
                }
                else //Background image.
                {
                    $src_bg = imagecreatefrompng("images/bg/$img_name.png"); //Open th background image.
                    imagecopyresampled($im, $src_bg, 0, 0, 0, 0, $x, $y, imagesx($src_bg), imagesy($src_bg)); //Resize to destination size.
                    imagedestroy($src_bg);
                }
            //CENTRAL COLOUR - END.

            //BACKGROUND EFFECT - START.
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
            //BACKGROUND EFFECT - END.

            //FILTERS - BEGIN.
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
            //FILTERS - END.

            //BORDER COLOUR - START.
                imagerectangle($im, 1, 1, $x-2, $y-2, $gold); //Draw a gold rectangle to 1 px from the border.
            //BORDER COLOUR - END.

            //HALF IMAGE LINE - START.
                $pos_line = $y / 2.5; //Calculating the position of the line in proportion to the height of the image.
                imageline($im, $y+6, $pos_line+1, $x-$y-73, $pos_line+1, $shadow); //Draw first the shadow moved 1px down and right.
                imageline($im, $y+5, $pos_line, $x-$y-74, $pos_line, $gold); //Draw the line.
            //HALF IMAGE LINE - END.

            //CHARACTER NAME - START.
                imagettftext($im, $dim_pg_name, 0, $y+8, $pos_line-4, $shadow, $font, $name_string); //Shadow of character name.
                imagettftext($im, $dim_pg_name, 0, $y+7, $pos_line-5, $text_color, $font, $name_string); //Character name.
            //CHARACTER NAME - END.

            //STATS - START.
                $prop_text = $y / 5 - 1; //I calculate the distance between the stats in proportion to the height of the image.
                for($i = 0; $i < count($show_stats); ++$i) //Print selected stats.
                {
                    $box = imagettfbbox($dim_stats, 0, $font, $show_stats[$i]);
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-5, 13+$prop_text*$i, $shadow, $font, $show_stats[$i]); //Stat's shadow.
                    imagettftext($im, $dim_stats, 0, $x-$box[2]-6, 12+$prop_text*$i, $text_color, $font, $show_stats[$i]); //Stat.
                }
            //STATS - END.

            //CLASS IMG - START.
                $src_avatar = imagecreatefromstring(file_get_contents($avatar_img));
                if($external_image) //If it is an external image reduce its size of 10px.
                    imagecopyresampled($im, $src_avatar, 5, 5, 0, 0, $y-10, $y-10, imagesx($src_avatar), imagesy($src_avatar));
                else //The image contains only the character class, resize and scale it.
                    imagecopyresampled($im, $src_avatar, 5, ($is_gif ? 5 : 0), 0, 0, $y-($is_gif ? 10 : 0), $y-($is_gif ? 10 : 0), imagesx($src_avatar), imagesy($src_avatar));
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
                if($image_resize_enabled && ((isset($_GET['x']) && $_GET['x'] != '' && is_numeric($_GET['x'])) || (isset($_GET['y']) && $_GET['y'] != '' && is_numeric($_GET['y']))))
                {
                    //x and y proportion calculating.
                    if(isset($_GET['x']) && $_GET['x'] != '' && is_numeric($_GET['x']))
                        $prop_x = $_GET['x'] / $x;
                    else $prop_x = 1;
                    if(isset($_GET['y']) && $_GET['y'] != '' && is_numeric($_GET['y']))
                        $prop_y = $_GET['y'] / $y;
                    else $prop_y = 1;

                    //How it work:
                    // - If the proportions are both greater than 1 choose the largest,
                    // - If the proportions are both less than 1, choose the smallest,
                    // - If the proportions are one less than 1 and and greater than 1 choose the smallest,
                    // - If one of the proportions is equal to 1 i choose the one different from 1.
                    if($prop_x > 1 && $prop_y > 1)
                        $prop = $prop_x>$prop_y ? $prop_x : $prop_y;
                    else if($prop_x < 1 && $prop_y < 1)
                        $prop = $prop_x<$prop_y ? $prop_x : $prop_y;
                    else if(($prop_x != 1 && $prop_y == 1) || ($prop_x < 1 && $prop_y > 1))
                        $prop = $prop_x;
                    else if(($prop_x == 1 && $prop_y !=1 ) || ($prop_x > 1 && $prop_y < 1))
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
                $realm_name = mysql_real_escape_string(strtoupper($_GET["server"])); //Avoid SQL-Injections.
                $img_save_name = $realm_name . "_$pg_name.png"; //SERVER_PgName.png.
                imagepng($im, "saved/$img_save_name"); //Save the image in the "saved" directory.

                //Save a identification record on DB.
                $site_connection->query("REPLACE INTO savedimages VALUES ($pg_GUID, '$realm_name', '" . getOrderQueryString($_SERVER["QUERY_STRING"]) . "', '$img_save_name', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());", true);
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