<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class SpotifyBaseService
{
    /**
     * @var string
     */
    private $client;
    /**
     * @var string[]
     */
    private $headers;

    public function __construct()
    {
        $this->client = 'https://api.spotify.com/v1';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->authenticate()
        ];
    }

    /**
     *
     * Authenticate
     *
     */
    public function authenticate()
    {

        $client_id = 'cfe60c5bf9f24618af55e88b52dfa859';
        $client_secret = '7ae1009aeb4e4ac29bb80e687aac59fc';
        // generate a basic token
        $basic = base64_encode($client_id .':'. $client_secret);

        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://accounts.spotify.com/api/token?grant_type=client_credentials', ['headers'=> [ 'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . $basic],
          ]);

//        echo $this->getResponse($res->getBody());
//        echo $res->getStatusCode(); // 200
       return json_decode($res->getBody(), true)['access_token'];

    }

    /**
     *
     * GET all endpoint
     *
     */
    public function get(string $url, $with = [])
    {

        try {

            $response = Http::withHeaders($this->headers)->get($this->client . '/'.$url);

        } catch (\Throwable $th) {

            $response = $th->getMessage();
            return $response;
        }

        return ($this->getResponse($response));
    }

    /**
     *
     * GET single endpoint
     *
     */
    public function getById(string $url, int $id, $with = '')
    {
        try {

            $response = ($with) ? Http::withHeaders($this->headers)->get($this->client .'/'. $url.'/'.$with) : Http::withHeaders($this->headers)->get($this->client .'/'. $url.'/'.$id);

        } catch (\Throwable $th) {
            $response = $th->getMessage();
        }

        return $this->getResponse($response);
    }

    /**
     *
     * POST create new entry
     *
     */
    public function create(string $url, array $attributes, $session_id = false)
    {
        try {
            $response = ($session_id) ? Http::withHeaders($this->headers)->post($this->client .'/'. $url.'?session_id=1', $attributes) : Http::withHeaders($this->headers)->post($this->client .'/'. $url, $attributes) ;
        } catch (\Throwable $th) {
            $response = $th->getMessage();
        }

        return $this->getResponse($response);

    }

    /**
     *
     * PUT update existing entry
     *
     */
    public function update(string $url, int $id, array $attributes)
    {
        try {
            $response = Http::withHeaders($this->headers)->put($this->client . $url.'/'.$id, $attributes);
        } catch (\Throwable $th) {
            $response = $th->getMessage();
        }

        return $this->getResponse($response);
    }

    /**
     *
     * DELETE existing entry
     *
     */
    public function delete(string $url, int $id)
    {
        try {
            $response = Http::withHeaders($this->headers)->delete($this->client . $url.'/'.$id);
        } catch (\Throwable $th) {
            $response = $th->getMessage();
        }

        return $this->getResponse($response);
    }

    /**
     *
     * Convert the json into a string for better reading
     *
     */
    public function getResponse($response)
    {
        $string = (string) $response;
        return json_decode($string);
    }
}

