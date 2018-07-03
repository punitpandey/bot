<?php
// parameters
$hubVerifyToken = 'bottrial';
// oasis
// $accessToken =   "EAAZA0r5j8NgQBALvQ4wmaZBN09EVZCZBDcgaRQlRZAVF8ZAuVFd1aZA3jkKbioGtQF5RqZAEMsadnxOZBgWeOKF5LuqQBGL9riw4mTyBOB6L6xAVCF0VCMghZCrU0FAayIZC7RKUrDkkfiRSivzEARABZAN80EmoFOgIlnKfeLr6C0qHRgZDZD";
// tours
$accessToken =   "EAAC0RRZCnwNoBADClLH67V0zLNtXqmg0h5N55rMdZCo7PTneZBgisMlkDLZAbLpggEQvMSr1gyxVwyZCCUTzug0hhu7OcZCO95mLNub3Y1KKbdjw0INM761bZCTy8nayhnTlHUobcCDOWdoBOgky7b0IOK7YoHJZCjgFa7KisSkynupltoWWZA0S3";
// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$response = null;
//set Message
if($messageText == "hi") {
    $answer = "Hello";
    //send message to facebook bot
$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $answer ]
];
}
if($messageText == "blog"){
    $answer = ["attachment"=>[
     "type"=>"template",
     "payload"=>[
       "template_type"=>"generic",
       "elements"=>[
         [
           "title"=>"Welcome to Peter's Hats",
           "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
           "image_url"=>"https://www.cloudways.com/blog/wp-content/uploads/Migrating-Your-Symfony-Website-To-Cloudways-Banner.jpg",
           "subtitle"=>"We\'ve got the right hat for everyone.",
           "buttons"=>[
             [
               "type"=>"web_url",
               "url"=>"https://petersfancybrownhats.com",
               "title"=>"View Website"
             ],
             [
               "type"=>"postback",
               "title"=>"Start Chatting",
               "payload"=>"DEVELOPER_DEFINED_PAYLOAD"
             ]              
           ]
         ]
       ]
     ]
   ]];
    $response = [
   'recipient' => [ 'id' => $senderId ],
   'message' => $answer 
];
}
if($messageText == "more"){
    $answer = [
        "text"=>"Here is quick reply",
        "quick_replies"=>[
            [

                "content_type"=>"text",
                "title"=>"Search",
                "payload"=>"<POSTBACK_PAYLOAD>",
                "image_url"=>"http://example.com/img/red.png"
            ],
            [

                "content_type"=>"text",
                "title"=>"Search",
                "payload"=>"<POSTBACK_PAYLOAD>",
                "image_url"=>"http://example.com/img/red.png"
            ],
            [

                "content_type"=>"text",
                "title"=>"Search",
                "payload"=>"<POSTBACK_PAYLOAD>",
                "image_url"=>"http://example.com/img/red.png"
            ]
        ]];
    $response = [
   'recipient' => [ 'id' => $senderId ],
   'message' => $answer 
];
}


$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','charset=UTF-8'));
if(!empty($input)){
    $result = curl_exec($ch);
}
if (curl_error($ch)) {
    die('Send Facebook Curl error: ' . curl_error($ch));
}
curl_close($ch);
?>