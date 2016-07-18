<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\VehicleLocations;

function sendMail($to, $subject, $message){
            $headers = "From: app@sea-watch.org\r\n".
               'Reply-To: nic@transparency-everywhere.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);
}

function curl_download($Url){
 
    // is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
 
    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();
 
    // Now set some options (most are optional)
 
    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $Url);
 
    // Set a referer
    curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
 
    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);
 
    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
 
    // Download the given URL, and return output
    $output = curl_exec($ch);
 
    // Close the cURL resource, and free system resources
    curl_close($ch);
 
    return $output;
}

function dmsToDec($dmsString){
    $parts = explode('.', $dmsString);
    $deg  = (int)$parts[0];
    $restParts = str_split($parts[1],2);
    $min = $restParts[0];
    $sec = $restParts[1].'.';
    $i =2;
    while(isset($restParts[$i])){
        $sec .= $restParts[$i];
        $i++;
    }
    return (int)$deg+((((int)$min*60)+((float)$sec))/3600);
}

function getPositionFromEpak(){
    $str = curl_download('https://monitor.epak.de/api.php?apikey='.env('EPAK_APIKEY').'&tid=63&item=position:text');
   
    $str = str_replace('E', '', $str);
    $latAndLon = explode('N', $str);
    return array((float)$latAndLon[0], (float)$latAndLon[1]);
}

/*taken from: tony gil
 * stackoverflow.com - http://stackoverflow.com/questions/7672759/how-to-calculate-distance-from-lat-long-in-php
 */
function distanceGeoPoints ($lat1, $lng1, $lat2, $lng2) {

    $earthRadius = 3958.75;

    $dLat = deg2rad($lat2-$lat1);
    $dLng = deg2rad($lng2-$lng1);


    $a = sin($dLat/2) * sin($dLat/2) +
       cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
       sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $dist = $earthRadius * $c;

    // from miles
    $meterConversion = 1609;
    $geopointDistance = $dist * $meterConversion;

    return $geopointDistance;
}

class swPositionWatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swPositionWatch:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $jsonString = '[[[14.8513,22.86295],[14.143871,22.491289],[13.581425,23.040506],[11.999506,23.471668],[11.560669,24.097909],[10.771364,24.562532],[10.303847,24.379313],[9.948261,24.936954],[9.910693,25.365455],[9.319411,26.094325],[9.716286,26.512206],[9.629056,27.140953],[9.756128,27.688259],[9.683885,28.144174],[9.859998,28.95999],[9.805634,29.424638],[9.48214,30.307556],[9.970017,30.539325],[10.056575,30.961831],[9.950225,31.37607],[10.636901,31.761421],[10.94479,32.081815],[11.432253,32.368903],[11.488787,33.136996],[12.66331,32.79278],[13.08326,32.87882],[13.91868,32.71196],[15.24563,32.26508],[15.71394,31.37626],[16.61162,31.18218],[18.02109,30.76357],[19.08641,30.26639],[19.57404,30.52582],[20.05335,30.98576],[19.82033,31.75179],[20.13397,32.2382],[20.85452,32.7068],[21.54298,32.8432],[22.89576,32.63858],[23.2368,32.19149],[23.60913,32.18726],[23.9275,32.01667],[24.92114,31.89936],[25.16482,31.56915],[24.80287,31.08929],[24.95762,30.6616],[24.70007,30.04419],[25,29.238655],[25,25.6825],[25,22],[25,20.00304],[23.85,20],[23.83766,19.58047],[19.84926,21.49509],[15.86085,23.40972],[14.8513,22.86295]]]';
        $posSW = getPositionFromEpak();
        
	$vehicleLocation = new \App\VehicleLocation(array('lat'=>$posSW[0], 'lon'=>$posSW[1], 'vehicle_id'=>16, 'timestamp'=>time(),'connection_type'=>'epak'));
        $vehicleLocation->save();
        
        $countryPolygon = json_decode($jsonString);
        $smallestDistance = 9999999;
        foreach($countryPolygon[0] AS $coordinates){
            $distance = distanceGeoPoints($coordinates[1], $coordinates[0], $posSW[0], $posSW[1]);
            if($distance < $smallestDistance){
                $smallestDistance = $distance;
                $smallestDistanceCoordinates = $coordinates;
            }
        }
        $this->info('asdf');
        $this->info($smallestDistance);
        
        if($smallestDistance < 44448){
             
             $this->info('sendmail!');
             
             $message = "Hey there,\n the sea-watch is currently ".round($smallestDistance/1852)." Nautical Miles away from the libyan shore.\n For more infos visit app.sea-watch.org/admin/public/map.\n Best regards,\n The Sea-Watch-App Team.";
             sendMail('nic@transparency-everywhere.com,giorgia@sea-watch.org,axel@sea-watch.org,harald@sea-watch.org', 'SW2 within 24 Miles Zone', $message);
           
        }
        $this->info($smallestDistanceCoordinates[0].'-'.$smallestDistanceCoordinates[1]);
        /*$this->info($smallestDistanceCoordinates[1]);
        $this->info($smallestDistanceCoordinates[0]);
        $this->info('Display this on the screen');*/
    }
}
