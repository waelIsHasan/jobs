<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  \Illuminate\Support\Facades\Http;
use App\Traits\ResponseTrait;
class AIController extends Controller
{
    use ResponseTrait;

    public function generateDescriptionJob(Request $request){
    $response = Http::withToken(config('services.liama.secret'))->post('https://api.groq.com/openai/v1/chat/completions',
    [
        "messages"=> [
            [
              "role"=> "user",
              "content"=> "Write description at most with 100 words for this".$request['title_job']."and the things I need to hire employee for it without Greeting or thiss..descrption"
            ],
            ],
          "model"=>"llama3-70b-8192",
          "temperature"=>1,
          "max_tokens"=> 1024,
          "top_p"=>1,
          "stream"=> false,
          "stop"=> null
    ]
    )->json();
    if($response == null){
        return $this->failedResponse('You have to open VPN ...' , null ,403);
}
    $content = $response['choices'][0]['message']['content'];
        return $this->successResponse('describe '.$request['title_job'].' successfully ' , $content , 200);
    }
}
