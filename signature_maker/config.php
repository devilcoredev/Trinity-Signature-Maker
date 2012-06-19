<?php
    //Disable errors reporting.
    error_reporting(0);

    if(!defined("ARMOY_INCLUDED"))
        return;

    include("mysql_connector.php");

    //Global array.
    $realm_name  = array();
    $host        = array();
    $username    = array();
    $password    = array();
    $database    = array();

    //Name of the server showed in the signature (it is recommended to use an acronym).
    $server_name = "";

    //Connection's data server's DBS (to add a new server just to copy a block and change the key with the acronym of the new server),
    //the field "$realm_name" will be displayed in signature, the field "$armory_name" is the name of the server showed in the armory link.
    //Data connection to the database of the first server.
    $realm_name[""]   = "";
    $armory_name[""]  = "";
    $host[""]         = "";
    $username[""]     = "";
    $password[""]     = "";
    $database[""]     = "";

    //Data connection to the database of the second server (if present, otherwise comment on this block).
    $realm_name[""]   = "";
    $armory_name[""]  = "";
    $host[""]         = "";
    $username[""]     = "";
    $password[""]     = "";
    $database[""]     = "";

    //Data connection to the database of the third server (if present, otherwise comment on this block).
    $realm_name[""]   = "";
    $armory_name[""]  = "";
    $host[""]         = "";
    $username[""]     = "";
    $password[""]     = "";
    $database[""]     = "";

    //Data connection to the database of titles and achievements.
    $site_host      = "";
    $site_username  = "";
    $site_password  = "";
    $site_database  = "";

    //w = width,
    //h = height,
    //t = type,
    //a = attr.

    //Name of the image displayed when the data entered are incorrect.
    $incorrect_data      = "incorrect_data.png";
    $dim_incorrect_data  = filesize("images/$incorrect_data"); //File's size.
    list($w_incorrect_data, $h_incorrect_data, $t_incorrect_data, $a_incorrect_data) = getimagesize("images/$incorrect_data");

    //Name of the image displayed when the signature doesn't load well.
    $charging_error      = "charging_error.png";
    $dim_charging_error  = filesize("images/$charging_error"); //File's size.
    list($w_charging_error, $h_charging_error, $t_charging_error, $a_charging_error) = getimagesize("images/$charging_error");

    //Error message printed if you do not enter a valid PG's name.
    $error_pg = "Insert a valid PG name!";

    //A generic link to the armory, %s will be the server name, %p will be the character's name. (EG: http://mysite/character-sheet.xml?r=MyServer&cn=MyPG)
    $armory_template_link = "http://mysite/character-sheet.xml?r=%s&cn=%p";

    //Time in seconds after which the signatures are automatically deleted if not used.
    $image_expire_time = 10 * 60;

    //Signature's size.
    $x = 500; //Width.
    $y = 70;  //Height.

    //The dimension must be different of the error images.
    while(($x == $w_incorrect_data && $y == $h_incorrect_data) || ($x == $w_charging_error && $y == $h_charging_error))
    {
        $x--;
        $y--;
    }

    //Enable resizing of images (in proportion) using query string.
    $image_resize_enabled = false;

    //Default background color (red to black).
    $to_img          = false;
    $start_bg_red    = 255;
    $start_bg_green  = 0;
    $start_bg_blue   = 0;
    $end_bg_red      = 0;
    $end_bg_green    = 0;
    $end_bg_blue     = 0;

    //Default text color (gold).
    //                      red green blue.
    $text_vet_color = array(255, 215, 0);

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

    $fonts["comic"] = array();
    $fonts["comic"]["text"]              = "Comic Sans";
    $fonts["comic"]["name"]              = "comicbd.ttf";
    $fonts["comic"]["pg_name"]           = 19;
    $fonts["comic"]["stats"]             = 8;
    $fonts["comic"]["addictional_info"]  = 10;
    $fonts["comic"]['x']                 = 230;
    $fonts["comic"]['y']                 = 50;

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

    //Item stats.
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

    //Once every 24 hours are re-written information on achievements in the configuration file so you must not always read them from db.
    $time = file_get_contents("custom/check_day.lock");
    if($time == false || (time()-$time) >= (24*60*60))
    {
        file_put_contents("custom/check_day.lock", time());

        //Total number of achievements (extracted from db).
        $num_ach = 0;

        if($row = $site_connection->query("SELECT COUNT(*) AS number FROM achievement WHERE points <> 0;", true))
            $num_ach = $row["number"]; //I select only the obtanible achievements (those that give points) and insert them into the vector $achievements.

        //If there are achievements i put the last 2 entries for stat's configuring.
        if($num_ach)
        {
            if($ach_file = fopen("custom/achievements.php", 'w'))
            {
                fputs($ach_file, "<?php\r\n");
                fputs($ach_file, "    \$num_achievements = $num_ach;\r\n\r\n");
                fputs($ach_file, "    \$stats[\"achievements\"]       = array(\"name\" => \"Achievements\", \"field_name\" => \"achievements\", \"text\" => \"Ach.: %s/\$num_achievements\"); //Obtained achievements / Obtanible achievements.\r\n");
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
    //Code that automatically deletes all the images are not used for 10 minutes (check carried out every 2 minutes to avoid spam).
    $time = file_get_contents("custom/check_clean.lock");
    if($time == false || (time()-$time) >= 120)
    {
        file_put_contents("custom/check_clean.lock", time());

        $id_query_clean = $site_connection->query("SELECT * FROM savedimages WHERE lastEdit < (UNIX_TIMESTAMP() - $image_expire_time);");
        while($row = $site_connection->getNextResult($id_query_clean))
            if(file_exists("saved/" . $row["imageName"]))
                unlink("saved/" . $row["imageName"]);

        $site_connection->query("DELETE FROM savedimages WHERE lastEdit < (UNIX_TIMESTAMP() - $image_expire_time);");
    }


    $time = file_get_contents("custom/check_week.lock");
    if($time == false || (time()-$time) >= (7*24*60*60))
    {
        file_put_contents("custom/check_week.lock", time());

        $path_name = "./temp_images/";
        if($handle = opendir($path_name))
        {
            while(false !== ($file = readdir($handle)))
            {
                $file_path = $path_name . $file;
                if(is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) != "htm") //Delete all images.
                    unlink($file_path);
            }
            closedir($handle);
        }
    }
?>
<?php
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
    //STANDARD GD FUNCTION - END.
?>