<?php
    //Library inclusion.
    include("config.php");
?>
<?php
    //Returns true if the image is png.
    function isPng($fileName)
    {
        return (strncmp(pathinfo($fileName, PATHINFO_EXTENSION), "png", 3) == 0);
    }

    //Returns a decimal number from a hex number in the form "HHHHHH".
    function GetRGBFromHex($input)
    {
        $input = strtolower($input);

        $vet[0] = hexdec(substr($input, 0, 2));
        $vet[1] = hexdec(substr($input, 2, 2));
        $vet[2] = hexdec(substr($input, 4, 2));

        return $vet;
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
        $txt[1];
    }

    //The function gets all the stats of the PG, searches first in the table character_stats, if it doesn't find anything searches in the table armory_character_stats.
    function fill_stats(&$input, $intput_conn, $guid)
    {
        $find_stats = false;
        $query = "SELECT maxhealth, maxpower1 AS mana, maxpower6 AS rune, maxpower7 AS runicPower, strength, agility, stamina, intellect, spirit,
                    armor, blockPct, dodgePct, parryPct, critPct, rangedCritPct, spellCritPct, attackPower, rangedAttackPower, spellPower,
                    resilience FROM character_stats WHERE guid = $guid;";
        if($result = mysql_query($query, $intput_conn)) //Internal armory (if enabled).
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
                    for($i=0x040A; $i<0x0410; ++$i) //The min-value is the spell crit.
                        $input["spellCritPct"]   = min($input["spellCritPct"], UInt32ToFloat($input_array[$i]));
                }
                mysql_free_result($result);
            }
        }
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
                if($row = mysql_fetch_array($result, MYSQL_ASSOC))
                    if(file_exists("saved/" . $row["imageName"]))
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
                //The background is an image, (it starts with bg_), i construct a link to the image.
                if(!strncmp($_GET["background"], "bg_", 3))
                {
                    //All wallpapers are used for signature are png, they start with bg_ and are contained in the directory "images/bg".
                    if(file_exists("images/bg/" . $_GET["background"] . ".png") && is_file("images/bg/" . $_GET["background"] . ".png"))
                    {
                        $to_img = true;
                        $img_name = $_GET["background"];
                    }
                }
                else //The background is a color, search the 3 graduations in hexadecimal code.
                {
                    $bg_vet = GetRGBFromHex($_GET["background"]);

                    $to_img          = false;
                    $start_bg_red    = $bg_vet[0];
                    $start_bg_green  = $bg_vet[1];
                    $start_bg_blue   = $bg_vet[2];
                }
            }

            //The user has selected a text color different from the default, estraggo il colore.
            if(isset($_GET["text_color"]) && $_GET["text_color"]!='')
                $text_vet_color = GetRGBFromHex($_GET["text_color"]);

            //The user selects a font for the text different from the default, i extract it.
            if(isset($_GET["text_font"]) && $_GET["text_font"]!='')
            {
                $name_font = $_GET["text_font"];
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

            //Controllo dei campi inseriti, e controllo dell'esistenza del realm selezionato.
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
                                    fill_stats($row, $connection, $pg_GUID); //Fill stats.

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
                                    $talents[0] = 0;
                                    $talents[1] = 0;
                                    $talents[2] = 0;
                                    if($talents_result = mysql_query("SELECT spell FROM character_talent WHERE guid = $pg_GUID AND spec = $spec_id;", $connection))
                                    {
                                        while($talents_row = mysql_fetch_array($talents_result, MYSQL_ASSOC))
                                            if($vet = getTalentInfo($talents_row["spell"]))
                                            {
                                                $tab = $vet["tabPage"];
                                                $talents[$tab] += $vet["rankId"];
                                            }
                                        mysql_free_result($talents_result);
                                    }
                                    $row["talents"] = $talents[0] . '/' . $talents[1] . '/' . $talents[2]; //Talents in the form (x/x/x).

                                    //Spec name.
                                    $max_talent = max($talents[0], $talents[1], $talents[2]);
                                    if($max_talent)
                                    {
                                        $i_t = array_search($max_talent, $talents);
                                        $spec_name .= ' ' . $tab_names[$row["class"]][$i_t];
                                    }

                                    //Level - Class - Race.
                                    $string_info = "Level " . $row["level"] . ' ' . $races[$row["race"]] . ' ' . $classes[$row["class"]]["name"] . $spec_name;

                                    //If is given the url of a valid PNG image i insert it, otherwise insert the default image.
                                    if(isset($_GET["url_image"]) && $_GET["url_image"]!='' && isPng($_GET["url_image"]) && imagecreatefrompng($_GET["url_image"]))
                                    {
                                        $avatar_img = $_GET["url_image"];
                                        $external_image = true;
                                    }
                                    else
                                    {
                                        //The image shows the race and the class of the character.
                                        if(isset($_GET["type_image"]) && $_GET["type_image"]=="race_class")
                                        {
                                            $is_gif = true;
                                            //The images are in the form "gender-race-class.gif".
                                            $avatar_img = $row["gender"] . '-' . $row["race"] . '-' . $row["class"] . ".gif";
                                            if($row["class"]==6 || $row["level"]>=80) //If level is 80 or race is Death Knight because Death Knight have got only level 80 avatars.
                                                $avatar_img = "Level_80_Forum_Avatars/$avatar_img";
                                            else if($row["level"]>=70 && $row["level"]<80) //70 <= level <= 79, level 70 avatars.
                                                $avatar_img = "Level_70_Forum_Avatars/$avatar_img";
                                            else if($row["level"]>=60 && $row["level"]<70) //60 <= level <= 69, level 60 avatars.
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
                                    $string_guild .= "[$server_name " . $realm_name["$server_id"] . ']'; //[Server_Name Realm_Name]

                                    //Stats (in an array).
                                    $index = 0;
                                    for($i=1; $i<6; ++$i) //Up to 5 stats of your choice.
                                        if(isset($_GET["stat$i"]) && $_GET["stat$i"]!='') //Check if there is a template of that stat.
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
                                                        if($punti = isValidAchievement($achievements_row["achievement"]))
                                                        {
                                                            $ach_count += 1; //Increase the count of the obtained achievements.
                                                            $ach_points += $punti; //Increase the points of the obtained achievements.
                                                        }
                                                    mysql_free_result($achievements_result);
                                                }
                                                //Insert data obtained in the vector of stats.
                                                $row["achievements"] = $ach_count;
                                                $row["achievementPoints"] = $ach_points;
                                            }

                                            if(isset($stats["$get_stat"]["name"]))
                                            {
                                                $field_name = $stats["$get_stat"]["field_name"];

                                                if(is_numeric($row["$field_name"])) //I make the round-off only if the field is a number.
                                                    $field_value = round($row["$field_name"], 2);
                                                else $field_value = $row["$field_name"];

                                                //Replace the values to the template strings.
                                                $temp_string = str_replace("%s", $field_value, $stats["$get_stat"]["text"]);

                                                //Check to make sure to avoid double stats.
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
            if(GDVersion() == 1)
                $im = imagecreate($x, $y);
            else $im = imagecreatetruecolor($x, $y);

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
                        $bg_red   -= ($i * $prop_bg_red);
                        $bg_green -= ($i * $prop_bg_green);
                        $bg_blue  -= ($i * $prop_bg_blue);

                        $col = imagecolorallocate($im, $bg_red, $bg_green, $bg_blue);
                        imagefilledrectangle($im, 0, $i, $x, $i+1, $col);
                        imagecolordeallocate($im, $col);
                    }
                }
                else //Background image.
                {
                    $src_bg = imagecreatefrompng("images/bg/$img_name.png"); //Open th background image.
                    list($width_bg, $height_bg) = getimagesize("images/bg/$img_name.png");
                    imagecopyresized($im, $src_bg, 0, 0, 0, 0, $x, $y, $width_bg, $height_bg); //Resize to destination size.
                    imagedestroy($src_bg);
                }
            //CENTRAL COLOUR - END.

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
                if(!$is_gif) //The image contains only the character class, resize and scale it.
                {
                    $src_avatar = imagecreatefrompng($avatar_img);
                    list($width_avatar, $height_avatar) = getimagesize($avatar_img);
                    //If it is an external image reduce its size of 10px.
                    imagecopyresized($im, $src_avatar, 5, ($external_image ? 5 : 0), 0, 0, $y-($external_image ? 10 : 0), $y-($external_image ? 10 : 0), $width_avatar, $height_avatar);
                    imagedestroy($src_avatar);
                }
                else //I reduce the image size of 10px and the center it in a square on the left.
                {
                    $src_avatar = imagecreatefromgif($avatar_img);
                    list($width_avatar, $height_avatar) = getimagesize($avatar_img);
                    imagecopyresized($im, $src_avatar, 5, 5, 0, 0, $y-10, $y-10, $width_avatar, $height_avatar);
                    imagedestroy($src_avatar);
                }
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
                        mysql_query("REPLACE INTO savedimages VALUES ($pg_GUID, '$realm_name', '" . getOrderQueryString($_SERVER["QUERY_STRING"]) . "', '$img_save_name', UNIX_TIMESTAMP());", $connection);
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