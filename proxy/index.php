<?php

// SIMPLE DESTINY REST PROXY TO REDIRECT METHODS

// Bungie.Net

function BungieNetReq($search,$data) {

    $DSTN_API_Key = '';
    $URL = '';

    if($search == 'clan' && !empty($data)) {
        $URL = 'https://www.bungie.net/platform/Group/Name/'.rawurlencode($data).'/';

    } elseif($search == 'members' && !empty($data)) {
        $URL = 'https://www.bungie.net/platform/Group/'.$data.'/MembersV3/?currentPage=1';
    } elseif($search == 'member' && !empty($data)) {
        $URL = 'https://www.bungie.net/platform/User/GetBungieAccount/'.$data.'/254/';
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key:' . $DSTN_API_Key));
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if($search == 'clan') {
        return $result['Response']['detail']['groupId'];
    } elseif($search == 'members') {
        $members = array();
        foreach($result['Response']['results'] as $member) {
            $members[] = $member['user']['membershipId'];
        }
        return $members;
    } else {
        return $result;
    }
}

function DestinyTrackerReq($search) {

    $userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36';
    $URL = 'http://destinytracker.com/destiny/overview/ps/'.$search;
    $classname = 'value';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($result);
    libxml_clear_errors();
    $DTRFinder = new DOMXPath($doc);

    $DTRScore = $DTRFinder->query("//*[@class='" . $classname . "']");
    if ($DTRScore->length > 0) {
        return $DTRScore->item(7)->nodeValue;
    }
    return 'ERROR';

}

if($_SERVER['REQUEST_METHOD'] == 'GET') {

    if(isset($_GET['clan'])) {

        $result = BungieNetReq('clan',$_GET['clan']);
        echo $result;

    } elseif(isset($_GET['members'])) {

        $result = BungieNetReq('members',$_GET['members']);

        echo json_encode($result, true);

    } elseif(isset($_GET['member'])) {

        $result = BungieNetReq('member', $_GET['member']);
        $result = $result['Response'];
        echo '['.json_encode($result, true).']';

    } elseif(isset($_GET['full'])) {

        $DSTN_Clan_ID = BungieNetReq('clan',$_GET['full']);

        $DSTN_Clan_Members = BungieNetReq('members',$DSTN_Clan_ID);

        foreach($DSTN_Clan_Members as $DSTN_Member) {
            echo json_encode(BungieNetReq('member',$DSTN_Member),true);
        }

    } elseif(isset($_GET['crucible'])) {

	$DSTN_Crucible = DestinyTrackerReq($_GET['crucible']);
	echo $DSTN_Crucible;

    } else {
        die('ERROR: REQUIRED PARAMETERS MISSING!');
    }
}
