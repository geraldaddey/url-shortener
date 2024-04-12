<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UrlshortenerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

	    try
	    {		    
			    $longUrl = $request->get( 'url' );
			    $domain = $request->get( 'domain' );
				$hash = $request->get( 'hash' );
				$shortDomain = 'shortly'; 
				$newGeneratedUrl = $domain . $shortDomain . '/' . $hash;

			    if ( $longUrl != '' || $newGeneratedUrl != '' )
			    {
				    $urlFound = Url::where( 'old_url', $longUrl )->get( [ 'id', 'new_url' ] )->toArray();


				    if ( !empty( $urlFound ) )
				    {
					    return $urlFound[ 0 ][ 'new_url' ];
				    }
				    else
				    {
					    $urlTable          = new Url;
					    $urlTable->old_url = $longUrl;
					    $urlTable->new_url = $newGeneratedUrl;
					    $urlTable->user_ip = $_SERVER['REQUEST_URI'];
					    $urlTable->save();
					    if ( $urlTable->save() )
					    {
						    return $urlTable->new_url;
					    }
				    }
			
		    }
	    }
	    catch(Exception $e)
	    {
	    	Log::error($e->getMessage());
	    }
    }

    /**
     * handles the url
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function redirect(Request $request, $id){
        $uri = $_SERVER['REQUEST_URI'];
        $url = Url::Where('new_url', 'like', '%' . $uri . '%')->get('old_url');

        if($uri =='' || $url =='' || count($url)==0){
            return abort(403);
        }else{
            return redirect($url[0]['old_url']);
        }

    }
}
