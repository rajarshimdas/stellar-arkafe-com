<?php

// Get Team List under a User
function rdGetMyTeamList(int $target_uid, object $mysqli): ?array
{
    // Get Vars
    /* 
    +-------------------------------------------------------+
    | users                                                 |
    +-------------------------------------------------------+
    */
    $query = 'select id, fullname, loginname from users where active > 0 and domain_id = 2';

    $result = $mysqli->query($query);
    // $no = 1;
    while ($row = $result->fetch_assoc()) {
        // printf("%s %s (%s)\n\n", $no++, $row["id"], $row["fullname"]);
        $users[$row["id"]] = $row["loginname"];
        $lname2id[$row["loginname"]] = [
            'uid' => $row["id"],
            'fullname' => $row["fullname"]
        ];
    }

    //var_dump($users);
    //echo $users[152];

    /* 
    +-------------------------------------------------------+
    | rman : Reporting Managers                             |
    +-------------------------------------------------------+
    */
    $query = "select reports_to_user_id as rmid, user_id from users_a";

    $result = $mysqli->query($query);
    // $no = 1;
    while ($row = $result->fetch_assoc()) {
        // Reporting Manager by id
        $rman[$row["rmid"]][] = $row["user_id"];
    }
    // var_dump($rman);

    // Variables
    $tm = [];   // Team members stack
    $co = 0;    // Limit recursion

    $tm = rdGetTeamMembersRecursive($target_uid, $users, $rman, $tm, $co);

    // echo "<pre>" . var_dump($tm) . "</pre>";

    if (isset($tm)) {

        sort($tm);
        // Create a easy to use array
        $tm_co = count($tm);

        for ($i = 0; $i < $tm_co; $i++) {
            //echo $i . '. uid: ' . $lname2id[$tm[$i]]["uid"] . ' | ' . $lname2id[$tm[$i]]["fullname"] . '<br>';

            if (isset($lname2id[$tm[$i]]["uid"])) {
                // echo $i . '. uid: ' . $lname2id[$tm[$i]]["uid"] . ' | ' . $lname2id[$tm[$i]]["fullname"] . '<br>';
                $team[] = [
                    'uid' => $lname2id[$tm[$i]]["uid"],
                    'fullname' => $lname2id[$tm[$i]]["fullname"]
                ];
            }
        }

        return $team;
    } else {
        return NULL;
    }
}

// Recursive function
function rdGetTeamMembersRecursive(int $target_uid, array $users, array $rman, array $tm, int $co): ?array
{
    if (!isset($target_uid) || $co > 5000) {
        // echo "<br>Completed: " . $co;
        if ($target_uid < 1) return $tm;
    }

    $stack = isset($rman[$target_uid]) ? $rman[$target_uid] : null;
    // var_dump($rman[$target_uid]);
    $stack_co = (isset($stack)) ? count($stack) : 0;
    //echo '<br>target_uid: '.$target_uid.' Name: '.$users[$target_uid].' stack_co: ' . $stack_co;

    if ($stack_co > 0) {

        for ($i = 0; $i < $stack_co; $i++) {

            $tuid = $stack[$i];
            // echo '<br>' . $co . '. tuid: ' . $tuid;
            $co++;

            // Recursion
            $tm = rdGetTeamMembersRecursive($tuid, $users, $rman, $tm, $co);
        }
    }

    if (isset($users[$target_uid])) $tm[] = $users[$target_uid];    // loginname

    return $tm;
}
