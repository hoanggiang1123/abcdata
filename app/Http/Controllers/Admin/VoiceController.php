<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeleSaleRecord;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\ClientToken;

use Illuminate\Support\Facades\Log;

class VoiceController extends Controller
{
    public function token(Request $request){
    	$data = $request->all();

    	$twilioAccountSid = env("TWILIO_ACCOUNT_SID");
		$twilioApiKey = env("TWILIO_API_KEY");
		$twilioApiSecret = env("TWILIO_API_SECRET");

		// Required for Voice grant
		$outgoingApplicationSid = env("TWILIO_SID");
		// An identifier for your app - can be anything you'd like
		$identity = $data["identity"]; // Jack // Client name

		// Create access token, which we will serialize and send to the client
		$token = new AccessToken(
		    $twilioAccountSid,
		    $twilioApiKey,
		    $twilioApiSecret,
		    3600,
		    $identity
		);

		// Create Voice grant
		$voiceGrant = new VoiceGrant();
		$voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);
        // $voiceGrant->setOutgoingApplicationSid('AP040e8f06f6e1ae99c69f43c592fd38cc');

		// Optional: add to allow incoming calls
		$voiceGrant->setIncomingAllow(true);

		// Add grant to token
		$token->addGrant($voiceGrant);

		return response()->json([
			"identity" => $data['identity'],
			"token" => $token->toJWT()
		]);


    }

    public function voice(Request $request){

    	$response = new VoiceResponse();

        $type = $request->type;
        $to = $request->To;
        $identity = $request->identity;
        $actionUrl = env('CALL_ACTION_URL') ?  env('CALL_ACTION_URL') : 'http://0272-14-180-3-134.ngrok.io/api/calling/actions';

        if ($type === 'outbound') {
            $twilioNumber = env('TWILIO_NUMBER');
            $domain = env('APP_URL');
            $dial = $response->dial('', ['callerId' => $twilioNumber, 'record' => 'record-from-answer', 'action' => $actionUrl]);
            $dial->number($to);
        }

        if ($type === 'internal') {

            $dial = $response->dial('', ['callerId' => $identity, 'record' => 'record-from-answer', 'action' => $actionUrl]);
            // $client = $dial->client($to, ['statusCallback' => 'https://chilly-swan-70.loca.lt/events', 'statusCallbackMethod' => 'POST', 'statusCallbackEvent' => 'initiated ringing answered completed']);
            $client = $dial->client($to);
            $client->parameter([
                "name" => "identity",
                "value" => $identity,
            ]);
        }

		return $response;
    }

    public function actions (Request $request) {

        $response = new VoiceResponse();
        $response->hangup();
        echo $response;
        $account = env('TWILIO_ACCOUNT_SID');
        $auth = env('TWILIO_ACCOUNT_AUTH');
        $domain = env('TWILIO_API_NAME');

        $data = $request->all();

        $from = $to = $callNumber = $duration = $startTime = $endTime = $callDirection = $recordingUrl = '';

        $callSid = $data['CallSid'];
        $status = $data['DialCallStatus'];
        $dialCallSid = isset($data['DialCallSid']) ? $data['DialCallSid'] : '';
        $from = $data['From'];
        $duration = isset($data['DialCallDuration']) ? $data['DialCallDuration']: 0;
        $recordingUrl = isset($data['RecordingUrl']) ? $data['RecordingUrl'] : '';

        $name = \str_replace('client:', '', $from);
        $user = User::where('name', $name)->first();
        $userId = $user ? $user->id : null;

        $twilio = new Client($account, $auth);
        $outboundCall = $twilio->calls($dialCallSid)->fetch();

        if ($outboundCall) {

            $callNumber = $outboundCall->from;
            $to = $outboundCall->to;
            $callDirection = $outboundCall->direction;

            if (strpos($to, 'client') !== false) {
                $to = \str_replace('client:', '', $to);
                $callDirection = 'inbound';
            }

            $startTime = $this->convertTime($outboundCall->startTime);
            $endTime = $this->convertTime($outboundCall->endTime);

        }
        // Log::info(\json_encode(['call_sid' => $callSid, 'from' => $name, 'to' => $to, 'call_number' => $callNumber, 'user_id' => $userId, 'duration' => $duration, 'start_time' => $startTime, 'end_time' => $endTime, 'direction' => $callDirection, 'recording_url' => $recordingUrl, 'status' => $status]));
        TeleSaleRecord::create(['call_sid' => $callSid, 'from' => $name, 'to' => $to, 'call_number' => $callNumber, 'user_id' => $userId, 'duration' => $duration, 'start_time' => $startTime, 'end_time' => $endTime, 'direction' => $callDirection, 'recording_url' => $recordingUrl, 'status' => $status]);
    }

    public function convertTime($time) {
        $time->setTimezone(new \DateTimeZone('Asia/Manila'));
        return $time->format('Y-m-d H:i:s');
    }

	public function save (Request $request) {

		$call_id = $request->call_id;

        $account = env('TWILIO_ACCOUNT_SID');
        $auth = env('TWILIO_ACCOUNT_AUTH');

        $twilio = new Client($account, $auth);

		if (isset($call_id) && !empty($call_id)) {

            $call = $twilio->calls($call_id)->fetch();

            if (!empty($call)) {
                Log::info($call->startTime->date);
            }
		}
        return 1;
	}
}
