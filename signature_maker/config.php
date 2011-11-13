<?php
    //Disable errors reporting.
    error_reporting(0);

    //Global array.
    $realm_name  = array();
    $host        = array();
    $username    = array();
    $password    = array();
    $database    = array();

    //Name of the server showed in the signature (it is recommended to use an acronym).
    $server_name = "";

    //Connection's data server's DBS (to add a new server just to copy a block and change the key with the acronym of the new server),
    //the field "$realm_name" will be displayed in signature.
    //Data connection to the database of the first server.
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Data connection to the database of the second server (if present, otherwise comment on this block).
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Data connection to the database of the third server (if present, otherwise comment on this block).
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Data connection to the database of titles and achievements.
    $site_host      = "";
    $site_username  = "";
    $site_password  = "";
    $site_database  = "";

    //Name of the image displayed when the data entered are incorrect.
    $incorrect_data      = "incorrect_data.png";
    $dim_incorrect_data  = filesize("images/$incorrect_data"); //File's size.

    //Name of the image displayed when the signature doesn't load well.
    $charging_error      = "charging_error.png";
    $dim_charging_error  = filesize("images/$charging_error"); //File's size.

    //Error message printed if you do not enter a valid PG's name.
    $error_pg = "Insert a valid PG name!";

    //A generic link to the armory, %s will be the server name, %p will be the character's name. (EG: http://mysite/character-sheet.xml?r=MyServer&cn=MyPG)
    $armory_template_link = "http://mysite/character-sheet.xml?r=%s&cn=%p";

    //Time in seconds after which the signatures are automatically deleted if not used.
    $image_expire_time = 10 * 60;

    //Signature's size.
    $x = 500; //Width.
    $y = 70;  //Height.

    //Enable resizing of images (in proportion) using query string.
    $image_resize_enabled = false;

    //Default background color (red).
    $to_img          = false;
    $start_bg_red    = 255;
    $start_bg_green  = 0;
    $start_bg_blue   = 0;

    //Default text color (gold).
    //                      red green blue.
    $text_vet_color = array(255, 215, 0);

    //Changing this value can change the start of the gradient to black.
    $gradient_proportion_y = 2.5;

    //Fonts.
    //To add a new font, copy the font file in the folder "fonts" and add a new block with its characteristics as described below.
    //Description of the fields:
    // - text: displayed name of the font,
    // - name: font file's name (put the files in the folder "fonts"),
    // - pg_name: size of the text containing the character's name in the signature,
    // - stats: size of the text containing the stats in the signature,
    // - addictional_info: size of the text containing the additional informations (class, race, level, guild, rank, server) in the signature,
    // - x: width of the preview of the font,
    // - y: height of the preview of the font.
    $fonts = array();

    $fonts["jokerman"] = array();
    $fonts["jokerman"]["text"]              = "Jokerman";
    $fonts["jokerman"]["name"]              = "JOKERMAN.TTF";
    $fonts["jokerman"]["pg_name"]           = 15;
    $fonts["jokerman"]["stats"]             = 7;
    $fonts["jokerman"]["addictional_info"]  = 9;
    $fonts["jokerman"]['x']                 = 215;
    $fonts["jokerman"]['y']                 = 50;

    $fonts["morpheus"] = array();
    $fonts["morpheus"]["text"]              = "Morpheus";
    $fonts["morpheus"]["name"]              = "MORPHEUS_0.TTF";
    $fonts["morpheus"]["pg_name"]           = 16;
    $fonts["morpheus"]["stats"]             = 9;
    $fonts["morpheus"]["addictional_info"]  = 10;
    $fonts["morpheus"]['x']                 = 185;
    $fonts["morpheus"]['y']                 = 55;

    $fonts["verdana"] = array();
    $fonts["verdana"]["text"]              = "Verdana";
    $fonts["verdana"]["name"]              = "verdanab.ttf";
    $fonts["verdana"]["pg_name"]           = 15;
    $fonts["verdana"]["stats"]             = 6;
    $fonts["verdana"]["addictional_info"]  = 8;
    $fonts["verdana"]['x']                 = 210;
    $fonts["verdana"]['y']                 = 50;

    $fonts["flashd"] = array();
    $fonts["flashd"]["text"]              = "Flash D";
    $fonts["flashd"]["name"]              = "FlashD.ttf";
    $fonts["flashd"]["pg_name"]           = 20;
    $fonts["flashd"]["stats"]             = 9;
    $fonts["flashd"]["addictional_info"]  = 11;
    $fonts["flashd"]['x']                 = 190;
    $fonts["flashd"]['y']                 = 50;

    //Signature backgrounds.
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

    //Visive effects.
    $effects = array();
    $effects[] = "grid_left_to_right";
    $effects[] = "grid_right_to_left";
    $effects[] = "grid_pointer";
    $effects[] = "grid_square";

    //Default font (verdana).
    $font                  = $fonts["verdana"]["name"];
    $dim_pg_name           = $fonts["verdana"]["pg_name"];
    $dim_stats             = $fonts["verdana"]["stats"];
    $dim_addictional_info  = $fonts["verdana"]["addictional_info"];

    //Vector containing the races.
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

    //Vector containing the classes and their names.
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

    //Tab's names.
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

    //Vector containing the stats to print,
    //"name" will be displayed in the index's drop-down menu,
    //"field_name" will be the field name to search in the database,
    //"text" is the text to be printed in the signature with the value of the stat instead of %s.
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
    $stats["attackpower"]        = array("name" => "Attack Power", "field_name" => "attackPower", "text" => "Attack Pwr: %s");
    $stats["rangedattackpower"]  = array("name" => "Ranged Attack Power", "field_name" => "rangedAttackPower", "text" => "Ranged Attack Pwr: %s");
    $stats["spellpower"]         = array("name" => "Spell Power", "field_name" => "spellPower", "text" => "Spell Pwr: %s");
    $stats["resilience"]         = array("name" => "Resilience", "field_name" => "resilience", "text" => "Resilience: %s");
    $stats["talents"]            = array("name" => "Talents", "field_name" => "talents", "text" => "Talents: (%s)");
    $stats["arenapoints"]        = array("name" => "Arena Points", "field_name" => "arenaPoints", "text" => "Arena Pts: %s");
    $stats["honorpoints"]        = array("name" => "Honor Points", "field_name" => "totalHonorPoints", "text" => "Honor Pts: %s");
    $stats["pvpkills"]           = array("name" => "PvP Kills", "field_name" => "totalKills", "text" => "PvP Kills: %s");
    $stats["teamrating2"]        = array("name" => "Team Rating 2v2", "field_name" => "teamRating2", "text" => "Team Rating 2v2: %s");
    $stats["teamrating3"]        = array("name" => "Team Rating 3v3", "field_name" => "teamRating3", "text" => "Team Rating 3v3: %s");
    $stats["teamrating5"]        = array("name" => "Team Rating 5v5", "field_name" => "teamRating5", "text" => "Team Rating 5v5: %s");
    $stats["personalrating2"]    = array("name" => "Personal Rating 2v2", "field_name" => "personalRating2", "text" => "Personal Rating 2v2: %s");
    $stats["personalrating3"]    = array("name" => "Personal Rating 3v3", "field_name" => "personalRating3", "text" => "Personal Rating 3v3: %s");
    $stats["personalrating5"]    = array("name" => "Personal Rating 5v5", "field_name" => "personalRating5", "text" => "Personal Rating 5v5: %s");

    //Once every 24 hours are re-written information on achievements in the configuration file so you must not always read them from db.
    $to_fill_achievements = false;
    if($file = fopen("custom/check_achievements.lock", 'r'))
    {
        if(!feof($file))
        {
            $time = fgets($file, 255);
            if($time < (time() - (24*60*60)))
                $to_fill_achievements = true;
        }else $to_fill_achievements = true;
        fclose($file);
    }else $to_fill_achievements = true;

    if($to_fill_achievements)
    {
        if($file = fopen("custom/check_achievements.lock", 'w'))
        {
            fputs($file, time());
            fclose($file);
        }

        if($ach_file = fopen("custom/achievements.php", 'w'))
        {
            fputs($ach_file, "<?php\r\n");

            //Total number of achievements (extracted from db).
            $num_ach = 0;
            if($conn = mysql_connect($site_host, $site_username, $site_password, true))
            {
                if(mysql_select_db($site_database, $conn))
                    if($result = mysql_query("SELECT COUNT(*) AS number FROM achievement WHERE points <> 0;", $conn))
                    {
                        if($row = mysql_fetch_array($result, MYSQL_ASSOC)) //I select only the obtanible achievements (those that give points) and insert them into the vector $achievements.
                        {
                            $num_ach = $row["number"];
                            fputs($ach_file, "    \$num_achievements = $num_ach;\r\n\r\n");
                        }
                        mysql_free_result($result);
                    }
                mysql_close($conn);
            }

            //If there are achievements i put the last 2 entries for stat's configuring.
            if($num_ach)
            {
                fputs($ach_file, "    \$stats[\"achievements\"]       = array(\"name\" => \"Achievements\", \"field_name\" => \"achievements\", \"text\" => \"Ach.: %s/\$num_achievements\"); //Obtained achievements / Obtanible achievements.\r\n");
                fputs($ach_file, "    \$stats[\"achievementpoints\"]  = array(\"name\" => \"Achievement Points\", \"field_name\" => \"achievementPoints\", \"text\" => \"Ach. Pts: %s\");\r\n");
            }

            fputs($ach_file, "?>");

            fclose($file);
        }
    }

    if(file_exists("custom/achievements.php"))
        include("custom/achievements.php");
?>
<?php
    //Code that automatically deletes all the images are not used for 10 minutes (check carried out every 2 minutes to avoid spam).
    $todo_clean = false;
    if($file = fopen("custom/check_clean.lock", 'r'))
    {
        if(!feof($file))
        {
            $time = fgets($file, 255);
            if($time < (time() - 120))
                $todo_clean = true;
        }else $todo_clean = true;
        fclose($file);
    }else $todo_clean = true;

    if($todo_clean)
    {
        if($file = fopen("custom/check_clean.lock", 'w'))
        {
            fputs($file, time());
            fclose($file);
        }

        if($connessione = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $connessione))
            {
                if($result = mysql_query("SELECT * FROM savedimages WHERE lastEdit < (UNIX_TIMESTAMP() - $image_expire_time);", $connessione))
                {
                    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
                        if(file_exists("saved/" . $row["imageName"]))
                            unlink("saved/" . $row["imageName"]);
                    mysql_free_result($result);
                }
                mysql_query("DELETE FROM savedimages WHERE lastEdit < (UNIX_TIMESTAMP() - $image_expire_time);", $connessione);
            }
            mysql_close($connessione);
        }
    }
?>
<?php
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

    //STANDARD GD FUNCTION - START.
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
                    $im = imagecreatetruecolor(10, 10);
                    if($im)
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

        function IsFormatSupported($format)
        {
            if(($format=="gif") && (imagetypes() & IMG_GIF))
                return true;
            else if(($format=="jpeg") && (imagetypes() & IMG_JPG))
                return true;
            else if(($format=="png") && (imagetypes() & IMG_PNG))
                return true;
            else return false;
        }
    //STANDARD GD FUNCTION - END.
?>