<?php

class YA_CL 
{
	public $iam,$folder,$token,$web;

	public $tts_lang="ru-RU",$tts_speed="1.12",$tts_voice="filipp",$tts_emo="neutral";



	// read more on 
	// https://cloud.yandex.ru/docs/iam/operations/iam-token/create
	public function get_iam()
	{
		$inp['url'] = "https://iam.api.cloud.yandex.net/iam/v1/tokens";
		$inp['raw'] = '{"yandexPassportOauthToken":"'.$this->token.'"}';
		

		$res = $this->web->get_cont($inp);
		$res = json_decode($res,1);
		//print_r($res);
		$this->iam = $res['iamToken'];
		return $this->iam;

	}


	public function speech($text,$file="./out.ogg",$p=[])
	{
		$post = [];
		$post['lang'] = arr_v($p,'lang',$this->tts_lang);
		$post['speed'] = arr_v($p,'speed',$this->tts_speed);
		$post['voice'] = arr_v($p,'voice',$this->tts_voice);
		$post['emotion'] = arr_v($p,'emo',$this->tts_emo);
		$post['format'] = arr_v($p,'format','oggopus');
		$post['folderId']=$this->folder;
		$post['text'] = $text;
		$inp['url'] = "https://tts.api.cloud.yandex.net/speech/v1/tts:synthesize";
		$inp['post'] = $post;
		$inp['headers'] = ['Authorization: Bearer '.$this->iam];

		$res = $this->web->get_cont($inp);


		file_put_contents($file, $res);
		chmod($file, 0777);
	}

}