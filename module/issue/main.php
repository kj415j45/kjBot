<?php

global $Queue, $Text, $Event;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

$builder = new Github\HttpClient\Builder(new GuzzleClient());

$length = strpos($Text, "\r");
if(false===$length)$length=strlen($Text);
$title = substr($Text, 0, $length);
$body = substr($Text, $length+2);

if($title == '')leave('请提供 issue 标题');

if(coolDown("issue/{$Event['user_id']}")<0)leave('冷却中');
coolDown("issue/{$Event['user_id']}", 60*60*24);

$github = new Github\Client($builder, 'machine-man-preview');
$jwt = (new Builder)
    ->setIssuer(config('Github_Integration_ID'))
    ->setIssuedAt(time())
    ->setExpiration(time() + 60)
    ->sign(new Sha256(),  new Key(getData('kjBot-Github.pem')))
    ->getToken();

$github->authenticate($jwt, null, Github\Client::AUTH_JWT);
$token = $github->api('apps')->createInstallationToken(config('Github_Installation_ID'));
$github->authenticate($token['token'], null, Github\Client::AUTH_HTTP_TOKEN);

$result = $github->api('issue')->create('kj415j45', 'kjBot', [
    'title' => $title,
    'body' => '>创建者：'.$Event['user_id']."\n\n".$body,
    'assignees' => ['kj415j45'],
]);

$Queue[]= sendBack('Issue 创建成功！'.$result['html_url']);
$Queue[]= sendMaster($Event['user_id'].' 创建了新 issue '.$result['html_url'])

?>